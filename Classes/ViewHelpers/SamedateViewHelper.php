<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 14/11/15
 * Time: 10:42
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;

class SamedateViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper
{
    /**
     * Initializes the "role" argument.
     * Renders <f:then> child if the current logged in FE user belongs to the specified role (aka usergroup)
     * otherwise renders <f:else> child.
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('date1', 'string', 'Timestamp of the first date');
        $this->registerArgument('date2', 'string', 'Timestamp of the second date');
        $this->registerArgument('format', 'string', 'The format to compare');
    }



    /**
     * This method decides if the condition is TRUE or FALSE. It can be overriden in extending viewhelpers to adjust functionality.
     *
     * @param array $arguments ViewHelper arguments to evaluate the condition for this ViewHelper, allows for flexiblity in overriding this method.
     * @return bool
     */
    protected static function evaluateCondition($arguments = null)
    {
        $date1 = $arguments['date1'];
        $date2 = $arguments['date2'];
        $format = $arguments['format'];



        if (is_object($date1)) {
            $d1 = $date1->format($format);
        } else {
            $d1 = date($format, $date1);
        }
        if (is_object($date2)) {
            $d2 = $date2->format($format);
        } else {
            $d2 = date($format, $date2);
        }

        if ($d1 == $d2) {
            return true;
        }
        return false;
    }
}
