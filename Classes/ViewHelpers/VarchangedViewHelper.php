<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 30/09/2016
 * Time: 15:26
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;


class VarchangedViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {
    /**
     * Initializes the "role" argument.
     * Renders <f:then> child if the current logged in FE user belongs to the specified role (aka usergroup)
     * otherwise renders <f:else> child.
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('value', 'string', 'The Value to check if it has changed');
        $this->registerArgument('context', 'string', 'A context keyword');
    }



    /**
     * This method decides if the condition is TRUE or FALSE. It can be overriden in extending viewhelpers to adjust functionality.
     *
     * @param array $arguments ViewHelper arguments to evaluate the condition for this ViewHelper, allows for flexiblity in overriding this method.
     * @return bool
     */
    protected static function evaluateCondition($arguments = null)
    {

        $value = $arguments['value'];
        $context = $arguments['context'];
        if (!isset($GLOBALS['S7VIEWHELPERS_VARCHANGED']))  $GLOBALS['S7VIEWHELPERS_VARCHANGED'] = [];
        if (!isset($GLOBALS['S7VIEWHELPERS_VARCHANGED'][$context])) {
            $GLOBALS['S7VIEWHELPERS_VARCHANGED'][$context] = $value;
            return true;
        }
        if ($GLOBALS['S7VIEWHELPERS_VARCHANGED'][$context] == $value) {
            return false;
        }
        $GLOBALS['S7VIEWHELPERS_VARCHANGED'][$context] = $value;
        return true;
    }
}
