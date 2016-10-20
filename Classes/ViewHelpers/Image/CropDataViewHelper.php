<?php
/**
 * Created by PhpStorm.
 * User: markus
 * Date: 20.10.16
 * Time: 15:28
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\Image;

class CropDataViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
    /**
     * Return the first element of an array
     *
     * @param mixed $data data
     * @param string $as variable name
     * @return array
     *
     * @throws nothing
     *
     */
    public function render( $data, $as='elem') {

        $crop = $data->getProperty('crop');
        $elem = [
            'width' => (int) $data->getProperty('width'),
            'height' => (int)$data->getProperty('height'),
            'isCropped' => false
        ];
        if (!empty($crop)) {
            $croppingConfiguration = json_decode($crop, true);
            $elem['width'] = (int)$croppingConfiguration['width'];
            $elem['height'] = (int)$croppingConfiguration['height'];
            $elem['isCropped'] = true;
        }

//        return (int)$croppingConfiguration[$dimensionalProperty];

        $renderChildrenClosure =  $this->buildRenderChildrenClosure();
        $templateVariableContainer = $this->renderingContext->getTemplateVariableContainer();
        $templateVariableContainer->add($as,$elem);
        $output = $renderChildrenClosure();
        $templateVariableContainer->remove($as);
        return $output;

    }
}