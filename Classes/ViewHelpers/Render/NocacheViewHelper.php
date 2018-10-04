<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 12/03/16
 * Time: 11:59
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\Render;

/*
 * Inspiriert von vhs:render.uncache
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Uncaches partials. Use like ``f:render``.
 * The partial will then be rendered each time.
 * Please be aware that this will impact render time.
 * Arguments must be serializable and will be cached.
 *
 * @author Danilo Bürger <danilo.buerger@hmspl.de>, Heimspiel GmbH
 * @package Vhs
 * @subpackage ViewHelpers\Render
 */
class NocacheViewHelper extends AbstractViewHelper
{
    /**
     * Initialize
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('partial', 'string', 'Reference to a partial.', true);
        $this->registerArgument('section', 'string', 'Name of section inside the partial to render.', false, null);
        $this->registerArgument('arguments', 'array', 'Arguments to pass to the partial.', false, null);
    }
    /**
     * @return string
     */
    public function render()
    {
        $partialArguments = $this->arguments['arguments'];
        if (false === is_array($partialArguments)) {
            $partialArguments = array();
        }
        if (false === isset($partialArguments['settings']) && true === $this->templateVariableContainer->exists('settings')) {
            $partialArguments['settings'] = $this->templateVariableContainer->get('settings');
        }
        $substKey = 'INT_SCRIPT.' . $GLOBALS['TSFE']->uniqueHash();
        $content = '<!--' . $substKey . '-->';
        $templateView = GeneralUtility::makeInstance('SUDHAUS7\\Sudhaus7Viewhelpers\\View\\NocacheView');
        $GLOBALS['TSFE']->config['INTincScript'][$substKey] = array(
            'type' => 'POSTUSERFUNC',
            'cObj' => serialize($templateView),
            'postUserFunc' => 'render',
            'conf' => array(
                'partial' => $this->arguments['partial'],
                'section' => $this->arguments['section'],
                'arguments' => $partialArguments,
                'controllerContext' => $this->renderingContext->getControllerContext()
            ),
            'content' => $content
        );
        return $content;
    }
}
