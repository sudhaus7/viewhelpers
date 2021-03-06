<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 10/10/2016
 * Time: 13:34
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;

class IsbannerViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper
{



    /**
     * Initializes the "role" argument.
     * Renders <f:then> child if the current logged in FE user belongs to the specified role (aka usergroup)
     * otherwise renders <f:else> child.
     *
     * @return void
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
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
            if ($image instanceof  \TYPO3\CMS\Extbase\Domain\Model\File) {
                echo '<!-- '.__LINE__.'   File XX -->';
                /** @var $image \TYPO3\CMS\Extbase\Domain\Model\File */
                $w = $image->getOriginalResource()->getProperty('width');
                $h = $image->getOriginalResource()->getProperty('height');

                $crop = $image->getOriginalResource()->getProperty('crop');
                if (!empty($crop)) {
                    try {
                        $data = \json_decode($crop, true);
                        $w = isset($data['width']) ? $data['width'] : $w;
                        $h = isset($data['height']) ? $data['height'] : $h;
                    } catch (\Exception $e) {
                    }
                }
            } elseif ($image instanceof  \TYPO3\CMS\Extbase\Domain\Model\FileReference) {

                /** @var $image \TYPO3\CMS\Extbase\Domain\Model\FileReference */
                $w = $image->getOriginalResource()->getProperty('width');
                $h = $image->getOriginalResource()->getProperty('height');
                $crop = $image->getOriginalResource()->getProperty('crop');
                if (!empty($crop)) {
                    try {
                        $data = \json_decode($crop, true);
                        $w = isset($data['width']) ? $data['width'] : $w;
                        $h = isset($data['height']) ? $data['height'] : $h;
                    } catch (\Exception $e) {
                    }
                }
            }
        } elseif (is_array($image)) {
            if (isset($image['identifier']) && is_file(PATH_Site.'/fileadmin/'.$image['identifier'])) {
                list($w, $h, $type, $attr) = getimagesize(PATH_Site.'/fileadmin/'.$image['identifier']);
            } elseif (isset($image['width'])) {
                $w = $image['width'];
                $h = $image['height'];
            } else {
                return false;
            }
        } elseif (is_file($image)) {
            list($w, $h, $type, $attr) = getimagesize($image);
        } elseif (is_integer($image)) {
            $sysfile = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'sys_file', 'uid=' . (int)$image);
            if (isset($sysfile['identifier']) && is_file(PATH_Site . '/fileadmin/' . $sysfile['identifier'])) {
                list($w, $h, $type, $attr) = getimagesize(PATH_Site . '/fileadmin/' . $sysfile['identifier']);
            } else {
                return false;
            }
        } else {
            return false;
        }
        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump([$sysfile,$w,$h,$w/$h]);
        if ($w > $h && $w/$h > 1.6) {
            return true;
        }
        return false;
    }
}
