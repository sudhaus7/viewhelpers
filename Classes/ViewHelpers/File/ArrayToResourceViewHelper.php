<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 2019-04-23
 * Time: 16:29
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\File;


class ArrayToResourceViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
	/**
	 *
	 * @param mixed $array the filereference_array
	 * @param string $as variable name
	 * @return string
	 *
	 */
	public function render($array, $as='elem')
	{

		$elem = new \TYPO3\CMS\Core\Resource\FileReference($array);

		$renderChildrenClosure =  $this->buildRenderChildrenClosure();
		$templateVariableContainer = $this->renderingContext->getTemplateVariableContainer();
		$templateVariableContainer->add($as, $elem);
		$output = $renderChildrenClosure();
		$templateVariableContainer->remove($as);
		return $output;
	}
}
