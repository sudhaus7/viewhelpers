<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 04/12/15
 * Time: 11:57
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Extbase\Domain\Model\AbstractFileFolder;

class ImageViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\ImageViewHelper
{
    /**
     * Resizes a given image (if required) and renders the respective img tag
     *
     * @see https://docs.typo3.org/typo3cms/TyposcriptReference/ContentObjects/Image/
     * @param string $src a path to a file, a combined FAL identifier or an uid (int). If $treatIdAsReference is set, the integer is considered the uid of the sys_file_reference record. If you already got a FAL object, consider using the $image parameter instead
     * @param string $width width of the image. This can be a numeric value representing the fixed width of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @param string $height height of the image. This can be a numeric value representing the fixed height of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @param int $minWidth minimum width of the image
     * @param int $minHeight minimum height of the image
     * @param int $maxWidth maximum width of the image
     * @param int $maxHeight maximum height of the image
     * @param bool $treatIdAsReference given src argument is a sys_file_reference record
     * @param FileInterface|AbstractFileFolder $image a FAL object
     * @param string|bool $crop overrule cropping of image (setting to FALSE disables the cropping set in FileReference)
     * @param bool $absolute Force absolute URL
     *
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     * @return string Rendered tag
     */
    public function render($src = null, $width = null, $height = null, $minWidth = null, $minHeight = null, $maxWidth = null, $maxHeight = null, $treatIdAsReference = false, $image = null, $crop = null, $absolute = false)
    {
        if (is_null($src) && is_null($image) || !is_null($src) && !is_null($image)) {
            throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception('You must either specify a string src or a File object.', 1382284106);
        }

        /** @var \TYPO3\CMS\Extbase\Domain\Model\FileReference $image */

        /* FIX FOR TRANSLATION */
        if (is_object($image) && $image instanceof \TYPO3\CMS\Extbase\Domain\Model\FileReference) {
            if ($image->_getProperty('_languageUid') > 0) {
                $src = $image->_getProperty('_localizedUid');
                $treatIdAsReference = 1;
                $image = null;
            }
        }
        /* END FIX FOR TRANSLATION */

        $image = $this->imageService->getImage($src, $image, $treatIdAsReference);
        if ($crop === null) {
            $crop = $image instanceof FileReference ? $image->getProperty('crop') : null;
        }

        $processingInstructions = array(
            'width' => $width,
            'height' => $height,
            'minWidth' => $minWidth,
            'minHeight' => $minHeight,
            'maxWidth' => $maxWidth,
            'maxHeight' => $maxHeight,
            'crop' => $crop,
        );
        $processedImage = $this->imageService->applyProcessingInstructions($image, $processingInstructions);

        $imageUri = $this->imageService->getImageUri($processedImage, $absolute);

        $this->tag->addAttribute('src', $imageUri);
        $this->tag->addAttribute('width', $processedImage->getProperty('width'));
        $this->tag->addAttribute('height', $processedImage->getProperty('height'));


        $alt = $image->getProperty('alternative');
        $title = $image->getProperty('title');

        // The alt-attribute is mandatory to have valid html-code, therefore add it even if it is empty
        if (empty($this->arguments['alt'])) {
            $this->tag->addAttribute('alt', $alt);
        }
        if (empty($this->arguments['title']) && $title) {
            $this->tag->addAttribute('title', $title);
        }

        $GLOBALS['BF_LASTIMAGE'] = array(
            'src'=>$image,
            'processed'=>$processedImage,
            'width'=>$processedImage->getProperty('width'),
            'height'=>$processedImage->getProperty('height')
        );


        return $this->tag->render();
    }
}
