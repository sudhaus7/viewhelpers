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
    }

    public function render()
    {
        if (get_class($this->arguments['file']) == \TYPO3\CMS\Core\Resource\FileReference::class) {
            return $this->arguments['file']->getProperty($this->arguments['property']);
        }
        return '';
    }
}
