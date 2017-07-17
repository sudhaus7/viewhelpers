<?php
/**
 * Created by PhpStorm.
 * User: markus
 * Date: 17.07.17
 * Time: 11:06
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\String;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class CountViewHelper extends AbstractViewHelper {
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('string','string','The string to be counted', true);
    }
    public function render() {
        $theString = $this->arguments['string'];
        return strlen($theString);
    }
}