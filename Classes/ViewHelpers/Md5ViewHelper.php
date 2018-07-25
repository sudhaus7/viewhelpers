<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 25.07.18
 * Time: 11:55
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;


class Md5ViewHelper  extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @return void
	 * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
	 */
	public function initializeArguments()
	{
		$this->registerArgument('data', 'string', 'calculate md5 string');
	}

	/**
	 *
	 * @return string
	 */
	public function render() {
		$data = $this->arguments['data'];
		return md5($data);
	}

}
