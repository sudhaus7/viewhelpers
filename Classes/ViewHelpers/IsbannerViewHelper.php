<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 10/10/2016
 * Time: 13:34
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;



use SUDHAUS7\Sudhaus7Base\Tools\Globals;

class IsbannerViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {


    /**
     * Initializes the "role" argument.
     * Renders <f:then> child if the current logged in FE user belongs to the specified role (aka usergroup)
     * otherwise renders <f:else> child.
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('image', 'mixed', 'Check this image');
    }



    /**
     * This method decides if the condition is TRUE or FALSE. It can be overriden in extending viewhelpers to adjust functionality.
     *
     * @param array $arguments ViewHelper arguments to evaluate the condition for this ViewHelper, allows for flexiblity in overriding this method.
     * @return bool
     */
    protected static function evaluateCondition($arguments = null)
    {

        $w = 0;
        $h = 1;
        $image = $arguments['image'];
        if (is_object($image)) {

            if ($image instanceof  \TYPO3\CMS\Extbase\Domain\Model\File ) {
                /** @var $image \TYPO3\CMS\Extbase\Domain\Model\File */
                $w = $image->getOriginalResource()->getProperty('width');
                $h = $image->getOriginalResource()->getProperty('height');
            } else if ($image instanceof  \TYPO3\CMS\Extbase\Domain\Model\FileReference) {
                /** @var $image \TYPO3\CMS\Extbase\Domain\Model\FileReference */
                $w = $image->getOriginalResource()->getProperty('width');
                $h = $image->getOriginalResource()->getProperty('height');
            }
        } else if (is_array($image)) {
            if (isset($image['identifier']) && is_file(PATH_Site.'/fileadmin/'.$image['identifier'])) {
                list($w,$h,$type,$attr) = getimagesize(PATH_Site.'/fileadmin/'.$image['identifier']);
            } else if (isset($image['width'])) {
                $w = $image['width'];
                $h = $image['height'];
            } else {
	            echo '<!-- '.__LINE__.' XX -->';
                return false;
            }
        } else if (is_file($image)) {
            list($w,$h,$type,$attr) = getimagesize($image);
        } else if (is_integer($image)) {
            $sysfile = Globals::db()->exec_SELECTgetSingleRow('*', 'sys_file', 'uid=' . (int)$image);
            if (isset($sysfile['identifier']) && is_file(PATH_Site . '/fileadmin/' . $sysfile['identifier'])) {
                list($w, $h, $type, $attr) = getimagesize(PATH_Site . '/fileadmin/' . $sysfile['identifier']);
            } else {
	            echo '<!-- '.__LINE__.' '.print_r($sysfile,true).' XX -->';
                return false;
            }
        } else {
        	echo '<!-- '.__LINE__.' XX -->';
            return false;
        }
	    //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump([$sysfile,$w,$h,$w/$h]);
        if ($w > $h && $w/$h > 1.6) {
            return true;
        }
        return false;
    }
}
