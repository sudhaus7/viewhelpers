<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 21/04/16
 * Time: 14:56
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;


use TYPO3\CMS\Core\Utility\GeneralUtility;

class FlexformViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{


    public function initializeArguments()
    {
        $this->registerArgument('xml', 'string', 'The Flexformdata to parse', TRUE);
        $this->registerArgument('field', 'string', 'fieldname', true);
        $this->registerArgument('sheet', 'string', 'sheet key', false, 'sDEF');
        $this->registerArgument('language', 'string', 'language key', false, 'lDEF');
        $this->registerArgument('return', 'int', 'return the data', false, 0);
        $this->registerArgument('as', 'string', 'variable', false, 'value');
    }

    public function render()
    {
        $value = null;
        $a = GeneralUtility::xml2array($this->arguments['xml']);
        if (isset($a['data'][$this->arguments['sheet']])) {
            if (isset($a['data'][$this->arguments['sheet']][$this->arguments['language']])) {
                if (isset($a['data'][$this->arguments['sheet']][$this->arguments['language']][$this->arguments['field']])) {
                    $value = $a['data'][$this->arguments['sheet']][$this->arguments['language']][$this->arguments['field']]['vDEF'];
                }
            }
        }


        $renderChildrenClosure = $this->buildRenderChildrenClosure();
        $templateVariableContainer = $this->renderingContext->getTemplateVariableContainer();
        $templateVariableContainer->add($this->arguments['as'], $value);
        $output = $renderChildrenClosure();
        $templateVariableContainer->remove($this->arguments['as']);

        return $output;
    }
}
