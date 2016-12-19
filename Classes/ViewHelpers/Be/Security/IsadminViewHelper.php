<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 17/12/2016
 * Time: 00:40
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\Be\Security;


class IsadminViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {



    protected static function evaluateCondition($arguments = null)
    {
        return $GLOBALS['BE_USER']->isAdmin();
    }
}
