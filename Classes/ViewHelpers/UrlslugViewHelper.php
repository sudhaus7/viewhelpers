<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 25/10/2016
 * Time: 15:52
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;

class UrlslugViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{


    /**
     * gets the pages with pid as parent page and writes them in subarrays (columns defines the number of subarrays)
     * every subarray contains an equal number of pages if it's possible
     * e.q. 10 pages, 4 columns => column 0: 3 pages, column 1: 3 pages, column 2: 2 pages, column 3: 2 pages
     *
     * @param string $label
     * @return array
     *
     * @throws nothing
     *
     */
    public function render($label)
    {
        return $this->generateslug($label);
    }

    private function generateslug($str)
    {
        $str = strtolower(trim($str));

        $str = preg_replace('~[^\\pL\d]+~u', '-', $str);
        $str = str_replace(
            array(
                'ß',
                'ä',
                'ü',
                'ö',
            ),
            array(
                'ss',
                'ae',
                'ue',
                'oe',
            ),
            $str
        );
        // Trim incl. dashes
        $str = trim($str, '-');
        if (function_exists('iconv') === true) {
            $str = iconv('utf-8', 'us-ascii//TRANSLIT', $str);
        }
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        $str = preg_replace('/-+/', "-", $str);

        return $str;
    }
}
