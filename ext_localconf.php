<?php

// Hook for manipulating Header Info

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-postProcess'][] = "BFACTOR\\BfactorViewhelpers\\Hooks\\RenderPostProcessHook->render";

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['BfactorViewhelpers']['RenderPostProcessHook']['0000-' . $_EXTKEY] = 'BFACTOR\\BfactorViewhelpers\\Hooks\\MetaPostProcessHook->attach';


if (!is_array($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['bfactorviewhelpers_metatags'])) {
    $TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['bfactorviewhelpers_metatags'] = array(
        'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
        'frontend' => 'TYPO3\\CMS\\Core\\Cache\\Frontend\\VariableFrontend',
        'groups' => array('pages'),
        'options' => array('defaultLifetime' => 0),
    );
}
