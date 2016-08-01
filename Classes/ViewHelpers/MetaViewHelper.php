<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 16/11/15
 * Time: 09:43
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;


use TYPO3\CMS\Core\Utility\GeneralUtility;

class MetaViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

    private static $facebook = array(
        'title'=>'og:title',
        'type'=>'og:type',
        'description'=>'og:description',
        'image'=>'og:image',
        'site'=>'og:site_name',
        'published'=>'article:published_time',
        'modified'=>'article:modified_time',
        'section'=>'article:section',
        'keywords'=>'article:tag',
    );
    private static $twitter = array(
        'title'=>'twitter:title',
        'description'=>'twitter:description',
        'image'=>'twitter:image',
    );

    private static $gplus = array(
        'title'=>'gplus:name',
       // 'description'=>'gplus:description',
        'image'=>'gplus:image',
    );
    private static $plain = array(
        'description'=>'description',
        'keywords'=>'keywords',
    );


    /**
     * Register / Overwrite a Meta tag
     *
     * @param string $key   the meta key
     * @param string $value the meta value
     * @param bool $auto    automatically create variations
     * @return void
     *
     * @throws nothing
     *
     */
    public function render($key, $value, $auto = true) {
        if (!isset($GLOBALS['SUDHAUS7_META_REGISTRY'])) $GLOBALS['SUDHAUS7_META_REGISTRY'] = array();
        self::handler($GLOBALS['SUDHAUS7_META_REGISTRY'], $key, $value, $auto);
        return;
    }

    static function handler(&$a, $key, $value, $auto) {
        if (substr($value,0,4)=='EXT:') {

            $value = GeneralUtility::getFileAbsFileName($value);
            $value = str_replace(PATH_site, '', $value);
            if (substr($value,0,4)!='http') $value = $_SERVER['HTTPS'] ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . '/' .$value;
        }
        if ($auto) {
            if (isset(self::$plain[$key])) $a[self::$plain[$key]] = $value;
            if (isset(self::$facebook[$key])) $a[self::$facebook[$key]] = $value;
            if (isset(self::$twitter[$key])) $a[self::$twitter[$key]] = $value;
            if (isset(self::$gplus[$key])) $a[self::$gplus[$key]] = $value;
        } else {
            $a[$key] = $value;
        }
    }
}
