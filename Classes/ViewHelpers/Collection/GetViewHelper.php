<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 08/03/16
 * Time: 01:35
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\Collection;


class GetViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
    /**
     * Return the first element of an array
     *
     * @param mixed $data data
     * @param string $key key name
     * @param string $as variable name
     * @return array
     *
     * @throws nothing
     *
     */
    public function render( $data, $key, $as='value') {
        if (is_object($data) && !method_exists($data,'toArray')) {
            throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception('GetViewHelper only supports arrays and objects implementing a toArray Method', 1248728393);
        }
        if (is_object($data)) $data = $data->toArray();
        //$elem = array_shift($data);


        $value = $data[$key];
        $renderChildrenClosure =  $this->buildRenderChildrenClosure();
        $templateVariableContainer = $this->renderingContext->getTemplateVariableContainer();
        $templateVariableContainer->add($as,$value);
        $output = $renderChildrenClosure();
        $templateVariableContainer->remove($as);
        return $output;

    }


}
