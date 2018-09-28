<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 26.09.18
 * Time: 12:05
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\Image;


use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;

class IsportraitViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	/**
	 * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
	 */
	public function initializeArguments()
	{
		$this->registerArgument('image', 'mixed', 'The Image to check', true);
	}

	/**
	 * @param null $arguments
	 *
	 * @return bool
	 */
	protected static function evaluateCondition($arguments = null)
	{
		$w = 0;
		$h = 1;
		if (isset($arguments['image'])) {
			if (isset($arguments['image']['dimensions']) && isset($arguments['image']['dimensions'])) {
				$w = $arguments['image']['dimensions']['width'];
				$h = $arguments['image']['dimensions']['height'];
			} else if (isset($arguments['image']['media'])) {
				if (\is_object( $arguments['image']['media'])) {
					if ($arguments['image']['media'] instanceof FileReference) {

						$w = $arguments['image']['media']->getProperty('width');
						$h = $arguments['image']['media']->getProperty('height');
					}
					if ($arguments['image']['media'] instanceof File) {

						$w = $arguments['image']['media']->getProperty('width');
						$h = $arguments['image']['media']->getProperty('height');
					}
				}
			}
		}
		return $h > $w;
	}
}
