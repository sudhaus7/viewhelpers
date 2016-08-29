<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 25/11/15
 * Time: 15:54
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\Link;


class TypolinkViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
    public function initializeArguments() {
        $this->registerArgument('configuration', 'array', 'The typoLink configuration', TRUE);
        $this->registerArgument('class', 'string', 'Class Defintions', FALSE,'');
        $this->registerArgument('title', 'string', 'Title attribute', FALSE,'');
        $this->registerArgument('target', 'string', 'target attribute', FALSE,'');
        $this->registerArgument('return', 'int', 'Return the link', FALSE,0);
    }
    /**
     * Return a typolink-rendered link
     * @throws nothing
     */
    public function render() {
        if ($this->arguments['class']) {
            if (!isset($this->arguments['configuration']['ATagParams'])) {
                $this->arguments['configuration']['ATagParams'] = ' class="'.$this->arguments['class'].'"';
            }
        }

        if (!isset($this->arguments['configuration']['target'])) {
            $this->arguments['configuration']['target'] = isset($this->arguments['target']) ? $this->arguments['target'] : '';
        }
        if (!isset($this->arguments['configuration']['fileTarget'])) {
            $this->arguments['configuration']['fileTarget'] = isset($this->arguments['target']) ? $this->arguments['target'] : '_blank';
        }
        if (!isset($this->arguments['configuration']['extTarget'])) {
            $this->arguments['configuration']['extTarget'] = isset($this->arguments['target']) ? $this->arguments['target'] : '_blank';
        }

        if ($this->arguments['title']) {
            if (!isset($this->arguments['configuration']['title'])) {
                $this->arguments['configuration']['title'] = $this->arguments['title'];
            }
        }
        if ($this->arguments['return']) {
            return $GLOBALS['TSFE']->cObj->typoLink_URL($this->arguments['configuration']);
        }
        return $GLOBALS['TSFE']->cObj->typoLink($this->renderChildren(), $this->arguments['configuration']);
    }
}
