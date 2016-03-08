<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 25/11/15
 * Time: 15:54
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\Register;


class SetViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
    public function initializeArguments() {
        $this->registerArgument('key', 'string', 'The Key to register', TRUE);
        $this->registerArgument('value', 'mixed', 'The Value', FALSE,null);
    }
    /**
     * Return a typolink-rendered link
     * @throws nothing
     */
    public function render() {
        if (!isset($GLOBALS['SUDHAUS7_FLUID_REGISTER'])) $GLOBALS['SUDHAUS7_FLUID_REGISTER'] = array();
        if (empty($this->arguments['value'])) {
            if (isset($GLOBALS['SUDHAUS7_FLUID_REGISTER'][$this->arguments['key']])) unset($GLOBALS['SUDHAUS7_FLUID_REGISTER'][$this->arguments['key']]);
        } else {
            $GLOBALS['SUDHAUS7_FLUID_REGISTER'][$this->arguments['key']] = $this->arguments['value'];
        }
    }
}
