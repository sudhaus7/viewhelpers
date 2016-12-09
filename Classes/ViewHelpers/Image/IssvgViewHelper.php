<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 09/12/2016
 * Time: 10:51
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\Image;


class IssvgViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {
    /**
     * Initializes the "role" argument.
     * Renders <f:then> child if the current logged in FE user belongs to the specified role (aka usergroup)
     * otherwise renders <f:else> child.
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('file', 'mixed', 'The File to check');

    }



    /**
     * This method decides if the condition is TRUE or FALSE. It can be overriden in extending viewhelpers to adjust functionality.
     *
     * @param array $arguments ViewHelper arguments to evaluate the condition for this ViewHelper, allows for flexiblity in overriding this method.
     * @return bool
     */
    protected static function evaluateCondition($arguments = null)
    {
        $file = $arguments['file'];
        if (is_object($file)) {
            if ($file instanceof \TYPO3\CMS\Extbase\Domain\Model\FileReference) {

                $ext = $file->getOriginalResource()->getExtension();
                if ($ext == 'svg' || $ext == 'SVG') return true;


            }

        } else if (is_integer($file)) {

        } else if (is_string($file)) {
            if (strtolower(substr($file,-4,4))=='.svg') return true;
        }
        return false;

    }
}
