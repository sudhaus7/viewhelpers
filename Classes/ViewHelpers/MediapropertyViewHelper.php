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
        if (is_array($file)) {
            //$type = $file['type'];
            $s = $file[$this->arguments['property']];
        } else {
            if (get_class($file) == \TYPO3\CMS\Extbase\Domain\Model\FileReference::class) {
                $file = new \TYPO3\CMS\Core\Resource\FileReference(['uid_local' => $file->getOriginalResource()->getUid()]);
            }
            if (get_class($file) == \TYPO3\CMS\Core\Resource\FileReference::class) {
                $s = $file->getProperty($this->arguments['property']);
              //  $type = $file->getProperty('type');
            }
        }
    //    if ($type=='3') return '';
        if (!empty($s)) {
            if (!empty($this->arguments['prepend'])) $s = $this->arguments['prepend'] . $s;
            if (!empty($this->arguments['append'])) $s = $s . $this->arguments['append'];
        }
        return $s;
    }
}
