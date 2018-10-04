<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 16/12/2016
 * Time: 16:03
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\Date;

class BeforeViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper
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

        if (!is_object($date1)) {
            $date1 = new \DateTime($date1);
        }

        if (!is_object($date2)) {
            $date2 = new \DateTime($date2);
        }

        return $date1 < $date2;
    }
}
