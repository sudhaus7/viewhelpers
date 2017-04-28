<?php

// Hook for manipulating Header Info

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-postProcess'][] = "SUDHAUS7\\Sudhaus7Viewhelpers\\Hooks\\RenderPostProcessHook->render";

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['Sudhaus7Viewhelpers']['RenderPostProcessHook']['0000-' . $_EXTKEY] = 'SUDHAUS7\\Sudhaus7Viewhelpers\\Hooks\\MetaPostProcessHook->attach';



$defaultConfig = include( \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath( 'sudhaus7_viewhelpers' ) . '/Configuration/DefaultConfiguration.php' );
$GLOBALS['TYPO3_CONF_VARS'] = array_replace_recursive( $defaultConfig , $GLOBALS['TYPO3_CONF_VARS']);
