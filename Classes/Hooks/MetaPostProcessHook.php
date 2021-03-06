<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 16/11/15
 * Time: 09:44
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\Hooks;

class MetaPostProcessHook
{
    public $extcache = null;

    public function __construct()
    {
        $this->extcache = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache('sudhaus7viewhelpers_metatags');
    }

    /**
     * @param $conf
     * @param $pObj \TYPO3\CMS\Core\Page\PageRenderer
     * @return mixed
     */

    public function attach(&$conf, &$pObj)
    {

        // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array($this->extcache,$conf,$pObj,$_GET,$GLOBALS['TSFE']));
        /** @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $tsfe */
        $tsfe = $GLOBALS['TSFE'];


        $aMeta = array();
        $cacheKey = isset($_GET['cHash']) && !empty($_GET['cHash']) ? $_GET['cHash'] . '_'. $tsfe->id .'_'.$tsfe->sys_language_uid : 'pageId_' . $tsfe->id .'_'.$tsfe->sys_language_uid;
        if ($this->extcache && !isset($_REQUEST['no_cache']) && $a = $this->extcache->get($cacheKey)) {
            $aMeta = $a;
        }

        if (empty($aMeta) && isset($GLOBALS['SUDHAUS7_META_REGISTRY']) && is_array($GLOBALS['SUDHAUS7_META_REGISTRY'])) {
            ksort($GLOBALS['SUDHAUS7_META_REGISTRY']);
            foreach ($GLOBALS['SUDHAUS7_META_REGISTRY'] as $key=>$value) {
                if (strpos($key, 'image')!==false) {
                    if (substr($value,0,4)!='http') {
                        $value = $conf['params']['baseUrl'] . $value;
                    }
                }
                list($prop, $key) = self::checkType($key);
                $aMeta[$key] = array(
                    $prop => $key,
                    'content' => $value,
                );
            }
            if ($this->extcache) {
                $this->extcache->set($cacheKey, $aMeta, array('pageId_' . $tsfe->id), 0);
            }
        }
        foreach ($aMeta as $k => $v) {
            $conf['metaArray'][$k] = $v;
        }
        return $conf['metaArray'];
    }

    public static function checkType($s)
    {
        $ret = 'name';
        $a = explode(':', $s);

        if (sizeof($a)>1) {
            switch ($a[0]) {
                case 'og':
                case 'article':
                case 'fb':
                    $ret = 'property';
                    break;
                case 'gplus':
                    array_shift($a);
                    $s = implode(':', $a);
                    $ret = 'itemprop';
                    break;
                default:
                    $ret = 'name';
                    break;
            }
        }
        return array($ret,$s);
    }
}
