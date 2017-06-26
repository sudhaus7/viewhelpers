<?php
/**
 * Created by PhpStorm.
 * User: markus
 * Date: 26.06.17
 * Time: 15:57
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;

use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Frontend\Resource\FileCollector;

class FalTranslationFixViewHelper extends AbstractViewHelper {
    protected $escapeOutput = false;

    /**
     * @param mixed $record
     * @param string $tableName
     * @param string $relationFieldName
     * @param string $as
     *
     * @return string
     */
    public function render($record, $tableName, $relationFieldName, $as)
    {
        $fileCollector = GeneralUtility::makeInstance(FileCollector::class);

        if ( $record instanceof AbstractDomainObject ) {
            $rawRecord = $this->getRawRecord($record, $tableName);
        } elseif ( is_array($record) ) {
            $rawRecord = $record;
        } else {
            throw new \UnexpectedValueException('Supplied record must either be an AbstractDomainObject or an array.');
        }

        $fileCollector->addFilesFromRelation($tableName, $relationFieldName, $rawRecord);

        $result = $fileCollector->getFiles();

        $this->templateVariableContainer->add($as, $result);
        $output = $this->renderChildren();
        $this->templateVariableContainer->remove($as);

        return $output;
    }

    protected function getRawRecord($recordModel, $tableName) {
        /** @var DatabaseConnection $db */
        $db = $GLOBALS['TYPO3_DB'];
        $rawRecord = $db->exec_SELECTgetSingleRow('*',$tableName,'uid='.($recordModel->_getProperty('_localizedUid') ? $recordModel->_getProperty('_localizedUid') : $recordModel->getUid()). ' AND hidden=0 AND deleted=0');
        return $rawRecord;
    }
}