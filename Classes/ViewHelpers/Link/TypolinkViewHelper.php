<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 25/11/15
 * Time: 15:54
 */

namespace BFACTOR\BfactorViewhelpers\ViewHelpers\Link;


class TypolinkViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
    public function initializeArguments() {
        $this->registerArgument('configuration', 'array', 'The typoLink configuration', TRUE);
        $this->registerArgument('return', 'int', 'Return the link', FALSE,0);
    }
    /**
     * Return a typolink-rendered link
     * @throws nothing
     */
    public function render() {
        if ($this->arguments['return']) {
            return $GLOBALS['TSFE']->cObj->typoLink_URL($this->arguments['configuration']);
        }
        return $GLOBALS['TSFE']->cObj->typoLink($this->renderChildren(), $this->arguments['configuration']);
    }
}
