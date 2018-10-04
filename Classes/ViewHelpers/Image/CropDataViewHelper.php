<?php
/**
 * Created by PhpStorm.
 * User: markus
 * Date: 20.10.16
 * Time: 15:28
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\Image;

class CropDataViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * Return the first element of an array
     *
     * @return array
     *
     * @throws nothing
     *
     */
    public function render()
    {
        $data = $this->arguments['data'];
        $as = $this->arguments['as'];
        $content = $this->arguments['content'];

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

        if (!empty($content)) {
            if ($content['imagewidth'] > 0) {
                $elem['width'] = $content['imagewidth'];
            }

            if ($content['imageheight'] > 0) {
                $elem['height'] = $content['imageheight'];
            }
        }

//        return (int)$croppingConfiguration[$dimensionalProperty];

        $renderChildrenClosure =  $this->buildRenderChildrenClosure();
        $templateVariableContainer = $this->renderingContext->getTemplateVariableContainer();
        $templateVariableContainer->add($as, $elem);
        $output = $renderChildrenClosure();
        $templateVariableContainer->remove($as);
        return $output;
    }

    public function initializeArguments()
    {
        $this->registerArgument('data', 'mixed', 'Image data', true);
        $this->registerArgument('as', 'string', 'The Property to read', false, 'elem');
        $this->registerArgument('content', 'array', 'The Property to read', false);
    }
}
