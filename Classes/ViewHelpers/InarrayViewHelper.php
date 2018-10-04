<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 28/09/2016
 * Time: 12:22
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;

class InarrayViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper
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
        $this->registerArgument('haystack', 'array', 'The array to check');
        $this->registerArgument('needle', 'mixed', 'The value to check');
    }



    /**
     * This method decides if the condition is TRUE or FALSE. It can be overriden in extending viewhelpers to adjust functionality.
     *
     * @param array $arguments ViewHelper arguments to evaluate the condition for this ViewHelper, allows for flexiblity in overriding this method.
     * @return bool
     */
    protected static function evaluateCondition($arguments = null)
    {
        if (!is_array($arguments['haystack'])) {
            return false;
        }
        return in_array($arguments['needle'], $arguments['haystack']);
    }
}
