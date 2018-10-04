<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 12/03/16
 * Time: 14:17
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\Render;

class AddafterViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    public function initializeArguments()
    {
        $this->registerArgument('section', 'string', 'Add to this Section of the Page', true);
        $this->registerArgument('content', 'string', 'The Content to add', false, null);
    }
    /**
     * Return a typolink-rendered link
     * @throws nothing
     */
    public function render()
    {
        if (!isset($GLOBALS['SUDHAUS7_ADDAFTER_REGISTRY'])) {
            $GLOBALS['SUDHAUS7_ADDAFTER_REGISTRY'] = array();
        }
        if (!isset($GLOBALS['SUDHAUS7_ADDAFTER_REGISTRY'][$this->arguments['section']])) {
            $GLOBALS['SUDHAUS7_ADDAFTER_REGISTRY'][$this->arguments['section']] = '';
        }

        if (!empty($this->arguments['content'])) {
            $GLOBALS['SUDHAUS7_ADDAFTER_REGISTRY'][$this->arguments['section']] .= $this->arguments['content'];
        } else {
            $GLOBALS['SUDHAUS7_ADDAFTER_REGISTRY'][$this->arguments['section']] .= $this->renderChildren();
        }
    }
}
