<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 03/08/16
 * Time: 16:34
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\File;

class ModelToResourceViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     *
     * @param mixed $file the file
     * @param string $as variable name
     * @return string
     *
     */
    public function render($file, $as='elem')
    {
        $elem = $file;
        if (get_class($file)==\TYPO3\CMS\Extbase\Domain\Model\FileReference::class) {
            $elem = new \TYPO3\CMS\Core\Resource\FileReference(['uid_local'=>$file->getUid()]);
        }
        $renderChildrenClosure =  $this->buildRenderChildrenClosure();
        $templateVariableContainer = $this->renderingContext->getTemplateVariableContainer();
        $templateVariableContainer->add($as, $elem);
        $output = $renderChildrenClosure();
        $templateVariableContainer->remove($as);
        return $output;
    }
}
