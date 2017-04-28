<?php

// Hook for manipulating Header Info

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-postProcess'][] = "SUDHAUS7\\Sudhaus7Viewhelpers\\Hooks\\RenderPostProcessHook->render";

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['Sudhaus7Viewhelpers']['RenderPostProcessHook']['0000-' . $_EXTKEY] = 'SUDHAUS7\\Sudhaus7Viewhelpers\\Hooks\\MetaPostProcessHook->attach';

$acachetables = ['sudhaus7viewhelpers_metatags','sudhaus7viewhelpers_cache'];
foreach ($acachetables as $cachetable) {
	if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cachetable])) {
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cachetable]=[];
	}
	if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cachetable]['groups'])) {
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cachetable]['groups']=[];
	}
	if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cachetable]['options'])) {
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cachetable]['options']=[];
	}
	if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cachetable]['backend'])) {
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cachetable]['backend'] = 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend';
	}
	if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cachetable]['frontend'])) {
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cachetable]['frontend'] = 'TYPO3\\CMS\\Core\\Cache\\Frontend\\VariableFrontend';
	}

	if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cachetable]['options']['defaultLifetime'])) {
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cachetable]['options']['defaultLifetime'] = 0;
	}
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cachetable]['groups'][] = 'pages';
}


