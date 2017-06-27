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

    public function initializeArguments()
    {
        $this->registerArgument('record','mixed','The record to be matched',true);
        $this->registerArgument('tableName','string','The tablename to be matched',true);
        $this->registerArgument('relationFieldName','string','the field name in sys_file_reference',true);
        $this->registerArgument('as','string','the name for the returend value',false,'files');
        $this->registerArgument('renderExtbase','bool','wether to render as Extbase FielReference or Core FileReference, default: core',false,false);
    }

    /**
     *
     * @return string
     */
    public function render()
    {
        $record = $this->arguments['record'];
        $tableName = $this->arguments['tableName'];
        $relationFieldName = $this->arguments['relationFieldName'];
        $as = $this->arguments['as'];
        $renderExtbase = $this->arguments['renderExtbase'];
        /** @var FileCollector $fileCollector */
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
        /* necessary because webbookviewhelper expects extbase FileReference instead of core FileReference */
        if ($renderExtbase) {
            $mappedResult = [];
            /** @var \TYPO3\CMS\Core\Resource\File $file */
            foreach ($result as $file) {
                $otherFile = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
                $otherFile->setOriginalResource($file);
                $mappedResult[] = $otherFile;
            }
            $this->templateVariableContainer->add($as, $mappedResult);
        } else {
            $this->templateVariableContainer->add($as, $result);
        }

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