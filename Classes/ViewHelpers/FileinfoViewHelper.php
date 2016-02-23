<?php
namespace BFACTOR\BfactorViewhelpers\ViewHelpers;
class FileinfoViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
	/**
	* Returns the mimetypeof the current file
	* 
	* @param string $file
	* @return string
	*/
	public function render($file) {
		if (!empty($file) && is_file($file)) {
			return pathinfo($file, PATHINFO_EXTENSION);
		}
		return "";
	}
}
