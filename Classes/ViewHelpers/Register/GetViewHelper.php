<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 25/11/15
 * Time: 15:54
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\Register;


class GetViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
    public function initializeArguments() {
        $this->registerArgument('key', 'string', 'The Key to read', TRUE);
    }
    /**
     * Return a typolink-rendered link
     * @throws nothing
     */
    public function render() {


        if (isset($GLOBALS["TSFE"]->register[$this->arguments['key']])) return($GLOBALS["TSFE"]->register[$this->arguments['key']]);

        return false;
    }
}
