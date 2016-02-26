<?php

// Hook for manipulating Header Info

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-postProcess'][] = "SUDHAUS7\\Sudhaus7Viewhelpers\\Hooks\\RenderPostProcessHook->render";

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['Sudhaus7Viewhelpers']['RenderPostProcessHook']['0000-' . $_EXTKEY] = 'SUDHAUS7\\Sudhaus7Viewhelpers\\Hooks\\MetaPostProcessHook->attach';


if (!is_array($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['sudhaus7viewhelpers_metatags'])) {
    $TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['sudhaus7viewhelpers_metatags'] = array(
        'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
        'frontend' => 'TYPO3\\CMS\\Core\\Cache\\Frontend\\VariableFrontend',
        'groups' => array('pages'),
        'options' => array('defaultLifetime' => 0),
    );
}


