<?php
namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;
class FalViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
    /**
     * Return the content of an FAL-Element
     *
     * @param mixed $data data
     * @param string $table table
     * @param string $field field
     * @param string $foreign the field containing the id
     * @param bool $return return the data, do not render it
     * @param string $as the variable in the render block
     * @param string $retidx default -1 return all values with foreach, > -1 return that index only
     * @return array
     *
     * @throws nothing
     *
     */
    public function render($data, $table = 'tt_content', $field = 'image',$foreign='uid_foreign', $return=0, $as="properties", $retidx=-1) {





        if ($data instanceof \TYPO3\CMS\Extbase\Domain\Model\FileReference) {

            $tmp = $data;
            $res = $tmp->getOriginalResource();
            $data = array(
                'uid'=>$tmp->getUid(),
                'pid'=>$tmp->getPid(),
            );
            $foreign='uid';
        }
        if (!is_array($data)) $data = array('uid'=>$data);
        $sql = sprintf('
            %4$s=%1$d
            AND tablenames="%2$s"
            AND fieldname="%3$s"
            AND deleted=0
            AND hidden=0
        ',$data['uid'],$table,$field,$foreign);
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','sys_file_reference',$sql,'','sorting_foreign ASC');
        $images = array();
        while($ret = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

            $resfile = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','sys_file','uid='.$ret['uid_local']);
            $rowfile = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resfile);


            $resstorage = $GLOBALS['TYPO3_DB']->sql_query('select ExtractValue(configuration,\'//field[@index="basePath"]/value\') as base from sys_file_storage where uid='.$rowfile['storage']);
            $rowstorage =  $GLOBALS['TYPO3_DB']->sql_fetch_row($resstorage);
            $ret['identifier'] = str_replace('//','/',$rowstorage[0].$rowfile['identifier']);
            $origRet = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','sys_file_metadata','file='.$ret['uid_local']);
            $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($origRet);
            foreach ($row as $k=>$v) {
                if (isset($ret[$k]) && empty($ret[$k])) $ret[$k]=$v;
                if (!isset($ret[$k])) $ret[$k]=$v;
            }

            $images[] = $ret;
        }
        if ($return) return $images;

        if (empty($images)) return '';

        $output = '';
        if ($retidx > -1) {
            $renderChildrenClosure =  $this->buildRenderChildrenClosure();
            $templateVariableContainer = $this->renderingContext->getTemplateVariableContainer();
            $templateVariableContainer->add($as,$images[$retidx]);
            $output .= $renderChildrenClosure();
            $templateVariableContainer->remove($as);
        } else {
            foreach ($images as $img) {
                $renderChildrenClosure =  $this->buildRenderChildrenClosure();
                $templateVariableContainer = $this->renderingContext->getTemplateVariableContainer();
                $templateVariableContainer->add($as,$img);
                $output .= $renderChildrenClosure();
                $templateVariableContainer->remove($as);
            }
        }


        return $output;

    }

    /**
     * When retrieving the height or width for a media file
     * a possible cropping needs to be taken into account.
     *
     * @param FileInterface $fileObject
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
