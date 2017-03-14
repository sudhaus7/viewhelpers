<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 09/12/2016
 * Time: 10:45
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\Image;


use TYPO3\CMS\Core\Utility\GeneralUtility;

class InlinesvgViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
    /**
     * Return the first element of an array
     *
     * @param string $file the File
     * @param string $as variable name
     * @return array
     *
     * @throws nothing
     *
     */
    public function render( $file, $as='elem') {

        if (substr($file,0,4)=='EXT:') {
            $file = GeneralUtility::getFileAbsFileName($file);
        }
        if (substr($file,0,10) == "/fileadmin" || substr($file,0,8) == "/uploads") {
            $file = PATH_site.$file;
        }
        $elem = null;
        if (is_file($file)) {
            $elem = file_get_contents($file);
        }
        if (empty($elem)) return '';

        $renderChildrenClosure =  $this->buildRenderChildrenClosure();
        $templateVariableContainer = $this->renderingContext->getTemplateVariableContainer();
        $templateVariableContainer->add($as,$elem);
        $output = $renderChildrenClosure();
        $templateVariableContainer->remove($as);
        return $output;
    }
}
