<?php
namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;

use TYPO3\CMS\Core\Resource\FileInterface;

class FalViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('data', 'mixed', 'The data', true);
        $this->registerArgument('table', 'string', 'the table to search', false, 'tt_content');
        $this->registerArgument('field', 'string', 'the field of the table', false, 'image');
        $this->registerArgument('foreign', 'string', 'the field containing the id', false, 'uid_foreign');
        $this->registerArgument('return', 'bool', 'return the data, do not render it', false, false);
        $this->registerArgument('as', 'string', 'the variable in the render block', false, 'properties');
        $this->registerArgument('retidx', 'int', 'default -1 return all values with foreach, > -1 return that index only', false, -1);
        $this->registerArgument('iteration', 'string', 'Define an iterator variable', false, null);
    }

    /**
     * Return the content of an FAL-Element
     *
     * @return array|string
     *
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
     */
    public function render()
    {
        $data = $this->arguments['data'];
        $table = $this->arguments['table'];
        $field = $this->arguments['field'];
        $foreign = $this->arguments['foreign'];
        $return = $this->arguments['return'];
        $as = $this->arguments['as'];
        $retidx = $this->arguments['retidx'];
        if ($data instanceof \TYPO3\CMS\Extbase\Domain\Model\FileReference) {
            $tmp = $data;
            $res = $tmp->getOriginalResource();
            $data = array(
                'uid'=>$tmp->getUid(),
                'pid'=>$tmp->getPid(),
            );
            $foreign='uid';
        }
        if (!is_array($data)) {
            $data = array('uid'=>$data);
        }
        $sql = sprintf('
            %4$s=%1$d
            AND tablenames="%2$s"
            AND fieldname="%3$s"
            AND deleted=0
            AND hidden=0
        ', $data['uid'], $table, $field, $foreign);
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'sys_file_reference', $sql, '', 'sorting_foreign ASC');
        $images = array();
        while ($ret = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $resfile = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'sys_file', 'uid='.$ret['uid_local']);
            $rowfile = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resfile);

            $resstorage = $GLOBALS['TYPO3_DB']->sql_query('select ExtractValue(configuration,\'//field[@index="basePath"]/value\') as base from sys_file_storage where uid='.$rowfile['storage']);
            $rowstorage =  $GLOBALS['TYPO3_DB']->sql_fetch_row($resstorage);
            $ret['identifier'] = str_replace('//', '/', $rowstorage[0].$rowfile['identifier']);
            $ret['extension'] = $rowfile['extension'];
            $ret['mime_type'] = $rowfile['mime_type'];
            $ret['size'] = $rowfile['size'];
            $ret['width'] = $rowfile['width'];
            $ret['height'] = $rowfile['height'];
            $origRet = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'sys_file_metadata', 'file='.$ret['uid_local']);
            $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($origRet);
            foreach ($row as $k=>$v) {
                if (isset($ret[$k]) && empty($ret[$k])) {
                    $ret[$k]=$v;
                }
                if (!isset($ret[$k])) {
                    $ret[$k]=$v;
                }
            }

            $images[] = $ret;
        }
        if ($return) {
            return $images;
        }

        if (empty($images)) {
            return '';
        }

        $output = '';
        if ($retidx > -1) {
            $renderChildrenClosure =  $this->buildRenderChildrenClosure();
            $templateVariableContainer = $this->renderingContext->getTemplateVariableContainer();
            $templateVariableContainer->add($as, $images[$retidx]);
            $output .= $renderChildrenClosure();
            $templateVariableContainer->remove($as);
        } else {
            if ($this->arguments['iteration'] !== null) {
                $iterationData = [
                    'index' => 0,
                    'cycle' => 1,
                    'total' => (int) ((is_array($this->arguments['data'][$this->arguments['field']])) ? count($this->arguments['data'][$this->arguments['field']]) : $this->arguments['data'][$this->arguments['field']])
                ];
            }
            foreach ($images as $img) {
                $renderChildrenClosure =  $this->buildRenderChildrenClosure();
                $templateVariableContainer = $this->renderingContext->getTemplateVariableContainer();
                if ($this->arguments['iteration'] !== null) {
                    $iterationData['isFirst'] = $iterationData['cycle'] === 1;
                    $iterationData['isLast'] = $iterationData['cycle'] === $iterationData['total'];
                    $iterationData['isEven'] = $iterationData['cycle'] % 2 === 0;
                    $iterationData['isOdd'] = !$iterationData['isEven'];
                    $templateVariableContainer->add($this->arguments['iteration'], $iterationData);
                    $iterationData['index']++;
                    $iterationData['cycle']++;
                }
                $templateVariableContainer->add($as, $img);
                $output .= $renderChildrenClosure();
                if ($this->arguments['iteration'] !== null) {
                    $templateVariableContainer->remove($this->arguments['iteration']);
                }
                $templateVariableContainer->remove($as);
            }
        }


        return $output;
    }

    /**
     * When retrieving the height or width for a media file
     * a possible cropping needs to be taken into account.
     *
     * @param FileInterface $data
     * @param string $dimensionalProperty 'width' or 'height'
     * @return int
     */
    protected function getCroppedDimensionalProperty($data, $dimensionalProperty)
    {
        if (!isset($data['crop']) || empty($data['crop'])) {
            return $data[$dimensionalProperty];
        }
        $croppingConfiguration = json_decode($data['crop'], true);
        return (int)$croppingConfiguration[$dimensionalProperty];
    }
}
