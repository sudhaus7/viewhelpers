<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 04/10/2016
 * Time: 16:37
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\Rendering\RendererRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\AbstractFileFolder;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3\CMS\Core\Resource\File;

use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;

/**
 * Render a given media file with the correct html tag.
 *
 * It asks the RendererRegister for the correct Renderer class and if not found it falls
 * back to the ImageViewHelper as that is the "Renderer" class for images in Fluid context.
 *
 * = Examples =
 *
 * <code title="Image Object">
 *     <s7:youtubemodal file="{file}" width="400" height="375" />
 * </code>
 * <output>
 *     <img alt="alt set in image record" src="fileadmin/_processed_/323223424.png" width="396" height="375" />
 * </output>
 *
 * <code title="MP4 Video Object">
 *     <s7:youtubemodal file="{file}" width="400" height="375" />
 * </code>
 * <output>
 *     <video width="400" height="375" controls><source src="fileadmin/user_upload/my-video.mp4" type="video/mp4"></video>
 * </output>
 *
 * <code title="MP4 Video Object with loop and autoplay option set">
 *     <s7:youtubemodal file="{file}" width="400" height="375" additionalConfig="{loop: '1', autoplay: '1'}" />
 * </code>
 * <output>
 *     <video width="400" height="375" controls loop><source src="fileadmin/user_upload/my-video.mp4" type="video/mp4"></video>
 * </output>
 */
class YoutubemodalViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\MediaViewHelper
{
    /**
     * @var OnlineMediaHelperInterface
     */
    protected $onlineMediaHelper;
    /**
     * @var string
     */
    protected $tagName = 'img';

    /**
     * Initialize arguments.
     *
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();

      //  $this->registerUniversalTagAttributes();
       // $this->registerTagAttribute('alt', 'string', 'Specifies an alternate text for an image', false);
    }

    /**
     * Render a given media file
     *
     * @param FileInterface|AbstractFileFolder $file
     * @param array $additionalConfig This array can hold additional configuration that is passed though to the Renderer object
     * @param string $width This can be a numeric value representing the fixed width of in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @param string $height This can be a numeric value representing the fixed height in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @return string Rendered tag
     */
    public function render($file, $additionalConfig = [], $width = null, $height = null)
    {

        // get Resource Object (non ExtBase version)
        if (is_callable([$file, 'getOriginalResource'])) {
            // We have a domain model, so we need to fetch the FAL resource object from there
            $file = $file->getOriginalResource();
        }



        list($movie,$img) = $this->youtuberender($file, $width, $height);
        return sprintf('<a href="%s" class="colorbox-iframe youtube-playbutton"><img src="%s"/></a>',$movie,$img);



        $img = $this->renderImage($file, $width, $height);


        $fileRenderer = RendererRegistry::getInstance()->getRenderer($file);

        // Fallback to image when no renderer is found
        if ($fileRenderer === null) {
            return $this->renderImage($file, $width, $height);
        } else {
            $additionalConfig = array_merge_recursive($this->arguments, $additionalConfig);
            return $fileRenderer->render($file, $width, $height, $additionalConfig);
        }
    }

    /**
     * Render img tag
     *
     * @param FileInterface $image
     * @param string $width
     * @param string $height
     * @return string Rendered img tag
     */
    protected function renderImage(FileInterface $image, $width, $height)
    {
        $crop = $image instanceof FileReference ? $image->getProperty('crop') : null;
        $processingInstructions = [
            'width' => $width,
            'height' => $height,
            'crop' => $crop,
        ];
        $imageService = $this->getImageService();
        $processedImage = $imageService->applyProcessingInstructions($image, $processingInstructions);
        $imageUri = $imageService->getImageUri($processedImage);

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

        return $this->tag->render();
    }



    public function youtuberender(FileInterface $file, $width, $height, array $options = null, $usedPathsRelativeToCurrentScript = false)
    {

        $options['autoplay'] = 1;
        $urlParams = ['autohide=1'];
        if (!isset($options['controls']) || !empty($options['controls'])) {
            $urlParams[] = 'controls=2';
        }
        if (!empty($options['autoplay'])) {
            $urlParams[] = 'autoplay=1';
        }
        if (!empty($options['loop'])) {
            $urlParams[] = 'loop=1';
        }
        if (!isset($options['enablejsapi']) || !empty($options['enablejsapi'])) {
            $urlParams[] = 'enablejsapi=1&amp;origin=' . GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL');
        }
        $urlParams[] = 'showinfo=' . (int)!empty($options['showinfo']);

        if ($file instanceof FileReference) {
            $orgFile = $file->getOriginalFile();
        } else {
            $orgFile = $file;
        }

        $videoId = $this->getOnlineMediaHelper($file)->getOnlineMediaId($orgFile);
        $src = sprintf(
            '//www.youtube%s.com/embed/%s?%s',
            !empty($options['no-cookie']) ? '-nocookie' : '',
            $videoId,
            implode('&amp;', $urlParams)
        );

        $attributes = ['allowfullscreen'];
        if ((int)$width > 0) {
            $attributes[] = 'width="' . (int)$width . '"';
        }
        if ((int)$height > 0) {
            $attributes[] = 'height="' . (int)$height . '"';
        }
        if (is_object($GLOBALS['TSFE']) && $GLOBALS['TSFE']->config['config']['doctype'] !== 'html5') {
            $attributes[] = 'frameborder="0"';
        }
        foreach (['class', 'dir', 'id', 'lang', 'style', 'title', 'accesskey', 'tabindex', 'onclick', 'poster', 'preload'] as $key) {
            if (!empty($options[$key])) {
                $attributes[] = $key . '="' . htmlspecialchars($options[$key]) . '"';
            }
        }

        return [$src,'https://img.youtube.com/vi/'.$videoId.'/hqdefault.jpg'];


    }

    /**
     * Get online media helper
     *
     * @param FileInterface $file
     * @return bool|OnlineMediaHelperInterface
     */
    protected function getOnlineMediaHelper(FileInterface $file)
    {
        if ($this->onlineMediaHelper === null) {
            $orgFile = $file;
            if ($orgFile instanceof FileReference) {
                $orgFile = $orgFile->getOriginalFile();
            }
            if ($orgFile instanceof File) {
                $this->onlineMediaHelper = OnlineMediaHelperRegistry::getInstance()->getOnlineMediaHelper($orgFile);
            } else {
                $this->onlineMediaHelper = false;
            }
        }
        return $this->onlineMediaHelper;
    }



    /**
     * Return an instance of ImageService
     *
     * @return ImageService
     */
    protected function getImageService()
    {
        return $this->objectManager->get(ImageService::class);
    }
}
