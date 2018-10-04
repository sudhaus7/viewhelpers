<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 04/12/15
 * Time: 12:01
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;

class LastimageViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * Return the first element of an array
     *
     * @param string $as variable name
     * @return string
     *
     * @throws nothing
     *
     */
    public function render($as='lastimg')
    {
        if (!isset($GLOBALS['BF_LASTIMAGE']) || empty($GLOBALS['BF_LASTIMAGE'])) {
            return '';
        }

        $renderChildrenClosure =  $this->buildRenderChildrenClosure();
        $templateVariableContainer = $this->renderingContext->getTemplateVariableContainer();
        $templateVariableContainer->add($as, $GLOBALS['BF_LASTIMAGE']);
        $output = $renderChildrenClosure();
        $templateVariableContainer->remove($as);
        return $output;
    }
}
