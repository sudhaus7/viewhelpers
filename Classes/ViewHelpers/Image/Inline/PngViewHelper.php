<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 01/03/2017
 * Time: 14:30
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\Image\Inline;


use TYPO3\CMS\Core\Utility\GeneralUtility;

class PngViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
    /**
     *
     * @param string $file the File
     * @param string $as variable name
     * @return array
     *
     * @throws nothing
     *
     */
    public function render( $file , $as='data') {

        $origfile = $file;
        if (substr($file,0,4)=='EXT:') {

            $file = GeneralUtility::getFileAbsFileName($file);
        }
        $elem = null;
        if (is_file($file)) {
            $elem = file_get_contents($file);
        }
        if (empty($elem)) {
            $renderChildrenClosure =  $this->buildRenderChildrenClosure();
            $templateVariableContainer = $this->renderingContext->getTemplateVariableContainer();
            $templateVariableContainer->add($as,$origfile);
            $output = $renderChildrenClosure();
            $templateVariableContainer->remove($as);
        } else {
            $renderChildrenClosure =  $this->buildRenderChildrenClosure();
            $templateVariableContainer = $this->renderingContext->getTemplateVariableContainer();
            $templateVariableContainer->add($as,'data:image/png;base64,'.base64_encode($elem));
            $output = $renderChildrenClosure();
            $templateVariableContainer->remove($as);
        }


        return $output;
    }

}
