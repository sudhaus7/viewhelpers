<?php

namespace SUDHAUS7\Sudhaus7Viewhelpers\Hooks;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

class RenderPostProcessHook
{
    /**
     * @param array $params
     * @param object $pObj
     *
     * @return void
     */
    public function render(&$params, &$pObj)
    {
        /** @var Dispatcher $signalSlotDispatcher */
        $signalSlotDispatcher = GeneralUtility::makeInstance(Dispatcher::class);
        
        
        
        
        $settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sudhaus7_viewhelpers']);
        if (TYPO3_MODE == "FE") {
            $page = $GLOBALS['TSFE']->page;

            if ((int)$GLOBALS['TSFE']->config['config']['noPageTitle'] !== 2) {
                $sitetitle = trim($GLOBALS['TSFE']->tmpl->sitetitle);
                $pagetitle = trim(strip_tags($GLOBALS['TSFE']->page['title']));
                if ($sitetitle == $pagetitle && !empty($GLOBALS['TSFE']->page['nav_title'])) {
                    $pagetitle = trim(strip_tags($GLOBALS['TSFE']->page['nav_title']));
                }
                $params['title'] = $pagetitle.' : '.$sitetitle;
                if (empty($sitetitle)) {
                    $params['title'] = $pagetitle;
                }
            }
    
            try {
                list($params) = $signalSlotDispatcher->dispatch(__CLASS__, 'paramsAfterTitle', [ $params ]);
            } catch (\Exception $e) {
            }
            
            
            
            $metaArray = array(
                'og:title' => array(
                    'property' => 'og:title',
                    'content' => $params['title']
                ),
                'og:description' => array(
                    'property' => 'og:description',
                    'content' => $page['description']
                ),
                'og:type' => array(
                    'property' => 'og:type',
                    'content' => 'website'
                ),
                'description' => array(
                    'name' => 'description',
                    'content' => $page['description']
                ),
                'keywords' => array(
                    'name' => 'keywords',
                    'content' => $page['keywords']
                )
            );
            // get images
            $query = '
                SELECT sys_file.identifier,sys_file.storage
                FROM sys_file
                JOIN sys_file_reference
                ON sys_file.uid=sys_file_reference.uid_local
                JOIN tt_content
                ON tt_content.uid=sys_file_reference.uid_foreign
                AND tt_content.CType in ("textmedia" ,"image")
                AND tt_content.pid="%1$d"
                AND tt_content.hidden=0
                AND tt_content.deleted=0
                WHERE sys_file_reference.tablenames="tt_content"
                AND sys_file_reference.fieldname in ("media", "assets" ,"image")
                AND sys_file_reference.hidden=0
                AND sys_file_reference.deleted=0
                AND sys_file.type=2
                GROUP BY sys_file.identifier
                LIMIT 3
            ';

            $ret = $GLOBALS['TYPO3_DB']->sql_query(sprintf($query, $page['uid']));
            while ($res = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($ret)) {
                // actually only works with fileadmin
                if ($res['storage'] == 1) {
                    $image = $params['baseUrl'] . "fileadmin" . $res['identifier'];
                    $metaArray['og:image'] = array(
                        'property' => 'og:image',
                        'content' => $image
                    );
                }
            }
    
            try {
                list($params, $metaArray) = $signalSlotDispatcher->dispatch(__CLASS__, 'metadataAfterImage', [ $params, $metaArray ]);
            } catch (\Exception $e) {
            }
            
            // Hook for extensions to manipulate meta tags, beware to check if you are on page with your extension
            if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['Sudhaus7Viewhelpers']['RenderPostProcessHook'])) {
                ksort($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['Sudhaus7Viewhelpers']['RenderPostProcessHook'], SORT_NATURAL);
                foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['Sudhaus7Viewhelpers']['RenderPostProcessHook'] as $hook) {
                    $passArguments = array('params' => $params, 'metaArray' => $metaArray);

                    $metaArray = \TYPO3\CMS\Core\Utility\GeneralUtility::callUserFunction($hook, $passArguments, $pObj);
                    $params = $passArguments['params'];
                }
            }
            $newMeta = array();
            if (!isset($settings['disableCanonical']) || !$settings['disableCanonical']) {
                // set URL here, do not manipulate
                $getParams = $this->getUrlParams();
                $id = $page['uid'];
                unset($getParams['id']);

                $url = $GLOBALS['TSFE']->cObj->typoLink('', ['parameter' => $id,
                                                            'additionalParams' => GeneralUtility::implodeArrayForUrl('', $getParams),
                                                            'forceAbsoluteUrl' => 1,
                                                            'returnLast' => 'url']);
                
                if (strpos($url, '//', 8) !== false) {
                    $url = substr($url, 0, -1);
                }
               
                try {
                    list($url) = $signalSlotDispatcher->dispatch(__CLASS__, 'generateCannonical', [ $url ]);
                } catch (\Exception $e) {
                }
                $metaArray['og:url'] = array(
                    'property' => 'og:url',
                    'content' => $url
                );
            }
    
            try {
                list($params, $metaArray) = $signalSlotDispatcher->dispatch(__CLASS__, 'metadataAfterViewhelperdata', [ $params, $metaArray ]);
            } catch (\Exception $e) {
            }
            

            if (isset($metaArray['og:url']) && isset($metaArray['og:url']['content']) && (!isset($settings['disableCanonical']) || !$settings['disableCanonical'])) {
                $newMeta[] = '<link rel="canonical" href="' . $metaArray['og:url']['content'] . '" />';
            }
    
    
            
            
            
            if ($GLOBALS['TSFE']->page['no_search']) {
                $newMeta[] = '<META NAME="ROBOTS" CONTENT="NOINDEX,FOLLOW">';
            }

            foreach ($metaArray as $metaTag) {
                $tag = "<meta ";
                while ($content = current($metaTag)) {
                    $tag .= key($metaTag) . '="' . htmlspecialchars($content) . '" ';
                    next($metaTag);
                }
                $tag .= " >";
                $newMeta[] = $tag;
            }
    
            try {
                list($newMeta) = $signalSlotDispatcher->dispatch(__CLASS__, 'newMetadata', [ $newMeta, $params ]);
            } catch (\Exception $e) {
            }
            
            $params['headerData'] = array_merge($params['headerData'], $newMeta);
            if (isset($GLOBALS['SUDHAUS7_ADDAFTER_REGISTRY']) && !empty($GLOBALS['SUDHAUS7_ADDAFTER_REGISTRY'])) {
                foreach ($GLOBALS['SUDHAUS7_ADDAFTER_REGISTRY'] as $k=>$v) {
                    if (isset($params[$k]) && !empty($v)) {
                        $params[$k] .= $v;
                    }
                }
            }

            if (!isset($settings['disableCanonical']) || !$settings['disableCanonical']) {
                $params['bodyContent'] = str_replace(
                    '###CANONICALURL###',
                    urlencode($metaArray['og:url']['content']),
                    $params['bodyContent']
                );
                foreach ($params['headerData'] as $k => $v) {
                    $params['headerData'][$k] = str_replace('###CANONICALURL###', $metaArray['og:url']['content'], $v);
                }
            }
            try {
                list($params, $metaArray) = $signalSlotDispatcher->dispatch(__CLASS__, 'finish', [ $params, $metaArray ]);
            } catch (\Exception $e) {
            }
            return;
        }
    }

    private function getUrlParams()
    {
        $getParams = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET();
        return $getParams;
    }
}
