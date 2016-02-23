<?php

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;

class HeaderViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
    /**
     * Checks header if is first on page
     *
     * @param array $data data array
     * @return bool
     *
     * @throws nothing
     *
     */
    public function render($data) {
        $ret = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tt_content','hidden=0 AND deleted=0 AND pid='.$data['pid'].' AND colPos=0 AND header!=""','','sorting ASC','1');
        $res = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($ret);
        $return = false;
        if (is_array($res) && $res['uid'] == $data['uid']) {
            $return = 1;
        }
        return $return;
    }
}