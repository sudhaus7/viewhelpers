<?php
namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;

use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class PagesViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * @var DatabaseConnection
     */
    protected $db;
    public function __construct()
    {
        $this->db = $GLOBALS['TYPO3_DB'];
    }


    /**
     * gets the pages with pid as parent page and writes them in subarrays (columns defines the number of subarrays)
     * every subarray contains an equal number of pages if it's possible
     * e.g. 10 pages, 4 columns => column 0: 3 pages, column 1: 3 pages, column 2: 2 pages, column 3: 2 pages
     *
     * @param int $pid
     * @param int $columns
     * @return array
     *
     * @throws nothing
     *
     */
    public function render($pid, $columns)
    {
        $ret = $this->db->exec_SELECTquery('*', 'pages', 'pid=' . $pid . ' AND hidden=0 AND deleted=0');
        $pages_temp = [];
        $pages = [];
        while ($res = $this->db->sql_fetch_assoc($ret)) {
            $pages_temp[$res['uid']] = $res;
            $count = $this->db->exec_SELECTcountRows('title', 'pages', 'pid=' . $res['uid'] . ' AND hidden=0 AND deleted=0');
            $pages_temp[$res['uid']]['subpages'] = $count;
        }

        $end = $columns;
        for ($i = 0; $i < $end; $i++) {
            $nr_pages_of_col = ceil(count($pages_temp) / $columns);
            $pages[] = array_slice($pages_temp, 0, $nr_pages_of_col);
            $pages_temp = array_slice($pages_temp, $nr_pages_of_col);
            $columns--;
        }

        return $pages;
    }
}
