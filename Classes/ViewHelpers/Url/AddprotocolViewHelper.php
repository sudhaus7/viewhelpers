<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 28.08.18
 * Time: 11:02
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\Url;

use \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class AddprotocolViewHelper extends AbstractViewHelper {

	/**
	 * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
	 */
	public function initializeArguments()
	{
		parent::initializeArguments();
		$this->registerArgument('url','string','This URL needs to be checked and a protocoll will be added if needed', true);
		$this->registerArgument('protocol','string','The protocol to add if needed',false,'http');
	}


	public function render() {
		$theurl = $this->arguments['url'];
		$theprotocol = $this->arguments['protocol'];
		if ($aUrl = parse_url($theurl)) {
			if (!isset($aUrl['scheme']) || empty($aUrl['scheme'])) {
				$aUrl['scheme'] = $theprotocol;
			}
			$theurl = self::glue_url( $aUrl);

		}
		return $theurl;
	}

	static function glue_url($parsed) {
		if (!is_array($parsed)) {
			return false;
		}

		$uri = isset($parsed['scheme']) ? $parsed['scheme'].':'.((strtolower($parsed['scheme']) == 'mailto') ? '' : '//') : '';
		$uri .= isset($parsed['user']) ? $parsed['user'].(isset($parsed['pass']) ? ':'.$parsed['pass'] : '').'@' : '';
		$uri .= isset($parsed['host']) ? $parsed['host'] : '';
		$uri .= isset($parsed['port']) ? ':'.$parsed['port'] : '';

		if (isset($parsed['path'])) {
			$uri .= (substr($parsed['path'], 0, 1) == '/') ?
				$parsed['path'] : ((!empty($uri) ? '/' : '' ) . $parsed['path']);
		}

		$uri .= isset($parsed['query']) ? '?'.$parsed['query'] : '';
		$uri .= isset($parsed['fragment']) ? '#'.$parsed['fragment'] : '';

		return $uri;
	}
}
