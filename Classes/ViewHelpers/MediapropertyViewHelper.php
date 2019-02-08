<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 22/04/16
 * Time: 16:31
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;

class MediapropertyViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    public function initializeArguments()
    {
        $this->registerArgument('file', 'object', 'The File Reference', true);
        $this->registerArgument('property', 'string', 'The Property to read', true);
        $this->registerArgument('prepend', 'string', 'Prepend Text', false, '');
        $this->registerArgument('append', 'string', 'Append Text', false, '');
    }

    public function render()
    {
        $s = '';

        $file = $this->arguments['file'];
        $type = '0';
	    if (\is_array($file)) {

		    // lets assume its a record from sys_file_reference
		    $file = new \TYPO3\CMS\Core\Resource\FileReference($file);
	    }



        if (get_class($file) == \TYPO3\CMS\Extbase\Domain\Model\FileReference::class || is_subclass_of($file,\TYPO3\CMS\Extbase\Domain\Model\FileReference::class )) {
            try {
                $file = $file->getOriginalResource();
                //$file = new \TYPO3\CMS\Core\Resource\FileReference(['uid_local' => $file->getOriginalResource()->getUid()]);
            } catch (\Exception $e) {
                return '';
            }
        }
        if (get_class($file) == \TYPO3\CMS\Core\Resource\FileReference::class) {
            // sorge dafür, dass in jedem Fall die Metadaten geladen sind
            $file->getOriginalFile()->_getMetaData();
            $s = $file->getProperties()[$this->arguments['property']];
            //$s = $file->getProperty($this->arguments['property']);
          //  $type = $file->getProperty('type');
        }

        //    if ($type=='3') return '';
        if (!empty($s)) {
            if (!empty($this->arguments['prepend'])) {
                $s = $this->arguments['prepend'] . $s;
            }
            if (!empty($this->arguments['append'])) {
                $s = $s . $this->arguments['append'];
            }
        }
        return $s;
    }
}
