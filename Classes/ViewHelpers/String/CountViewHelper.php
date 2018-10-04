<?php
/**
 * Created by PhpStorm.
 * User: markus
 * Date: 17.07.17
 * Time: 11:06
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\String;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class CountViewHelper extends AbstractViewHelper
{
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('string', 'string', 'The string to be counted', true);
        $this->registerArgument('invert', 'bool', 'Result has to be inverted to an integer', false, false);
        $this->registerArgument('maxnum', 'int', 'The number from which it has to be inverted', false);
    }
    public function render()
    {
        $theString = $this->arguments['string'];
        if ($this->arguments['invert']) {
            return $this->arguments['maxnum'] - strlen($theString);
        }
        return strlen($theString);
    }
}
