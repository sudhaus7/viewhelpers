<?php
namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\Collection;

class FirstViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
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
    public function render($data, $as='elem')
    {
        if (is_object($data) && !method_exists($data, 'toArray')) {
            throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception('FirstViewHelper only supports arrays and objects implementing a toArray Method', 1248728393);
        }
        if (is_object($data)) {
            $data = $data->toArray();
        }
        if (empty($data)) {
            return '';
        }
        $elem = array_shift($data);
        $renderChildrenClosure =  $this->buildRenderChildrenClosure();
        $templateVariableContainer = $this->renderingContext->getTemplateVariableContainer();
        $templateVariableContainer->add($as, $elem);
        $output = $renderChildrenClosure();
        $templateVariableContainer->remove($as);
        return $output;
    }
}
