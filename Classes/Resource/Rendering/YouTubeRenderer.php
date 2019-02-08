<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 02.10.18
 * Time: 16:39
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\Resource\Rendering;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Extbase\Service\ImageService;

class YouTubeRenderer extends \TYPO3\CMS\Core\Resource\Rendering\YouTubeRenderer
{


    /**
     * @return int
     */
    public function getPriority()
    {
        return 50;
    }


    public function render(
        FileInterface $file,
        $width,
        $height,
        array $options = [],
        $usedPathsRelativeToCurrentScript = false
    ) {

        $options['autoplay']=1;
        $options['useInternalJavascript'] = isset($options['useInternalJavascript']) ? (bool)$options['useInternalJavascript'] : true;
        $youtube = parent::render($file, $width, $height, $options, $usedPathsRelativeToCurrentScript);

	    $properties = [];
        if ($file->hasProperty( 'tx_sudhaus7viewhelpers_posterimage') && !empty($file->getProperty('tx_sudhaus7viewhelpers_posterimage'))) {
	        list($poster,$properties) = $this->renderImage($file, $width, $height);
        } else {
            if ($file instanceof FileReference) {
                $orgFile = $file->getOriginalFile();
            } else {
                $orgFile = $file;
            }
            $videoId = $this->getOnlineMediaHelper($file)->getOnlineMediaId($orgFile);
            $directory = 'typo3temp/assets/images/s7viewhelpers';
            if (TYPO3_BRANCH == '7.6') {
                $directory= 'typo3temp/s7viewhelpers';
            }
            $cacheDir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($directory);
            if (!is_dir($cacheDir)) {
                \TYPO3\CMS\Core\Utility\GeneralUtility::mkdir($cacheDir);
            }
            $cacheDir .= '/youtube';
            if (!is_dir($cacheDir)) {
                \TYPO3\CMS\Core\Utility\GeneralUtility::mkdir($cacheDir);
            }
            $cacheFile = $cacheDir.'/'.$videoId.'.jpg';
            $now = time()-86400;
            if (is_file($cacheFile) && \filemtime( $cacheFile) < $now) {
            	@unlink($cacheFile);
            }
	        if (!is_file($cacheFile)) {
		        try {
			        $buf = file_get_contents( 'https://i3.ytimg.com/vi/' . $videoId . '/maxresdefault.jpg' );
			        \file_put_contents( $cacheFile, $buf );
		        } catch (\Exception $e) {}
	        }
	        if (!is_file($cacheFile)) {
		        try {
			        $buf = file_get_contents( 'https://i3.ytimg.com/vi/' . $videoId . '/hqdefault.jpg' );
			        \file_put_contents( $cacheFile, $buf );
		        } catch (\Exception $e) {}
	        }
	        if (!is_file($cacheFile)) {
		        try {
			        $buf = file_get_contents( 'https://i3.ytimg.com/vi/' . $videoId . '/mqdefault.jpg' );
			        \file_put_contents( $cacheFile, $buf );
		        } catch (\Exception $e) {}
	        }
	        if (!is_file($cacheFile)) {
		        try {
			        $buf = file_get_contents( 'https://i3.ytimg.com/vi/' . $videoId . '/sddefault.jpg' );
			        \file_put_contents( $cacheFile, $buf );
		        } catch (\Exception $e) {}
	        }
	        if (!is_file($cacheFile)) {
		        try {
			        $buf = file_get_contents( 'https://i3.ytimg.com/vi/' . $videoId . '/default.jpg' );
			        \file_put_contents( $cacheFile, $buf );
		        } catch (\Exception $e) {}
	        }

            $poster = 'src="/'.$directory.'/youtube/'.$videoId.'.jpg"';
        }
        $uid = $file->getProperty('uid_foreign');
        if (empty($uid)) {
            $uid = $file->getUid();
        }
        if (!empty($poster)) {
            $js = "
				var self=this;
				var p=this.parentNode;
				var temp=document.createElement('div');
				temp.innerHTML=this.dataset['replace'];
				var video= temp.firstChild;
				p.appendChild(video);
				p.removeChild(self);
				var h =document.getElementById('clickslider-trigger-".$uid."');
				if (h) { h.classList.add('clickslider-triggered'); }
				";
	        $wh = '';
	        if ($width > 0) $wh .= ' width="'.$width.'"';
	        if ($height > 0) $wh .= ' height="'.$height.'"';

	        $imgtag = sprintf('<img %s %s data-replace="%s" class="s7-poster-image"/>', $poster, $wh, \htmlentities($youtube));
	        /** @var Dispatcher $signalSlotDispatcher */
	        $signalSlotDispatcher = GeneralUtility::makeInstance(Dispatcher::class);
	        try {
		        $data = $signalSlotDispatcher->dispatch(__CLASS__, 'imgTag', [ 'imgtag'=>$imgtag,'properties'=>$properties ]);
		        $imgtag = $data[0]['imgtag'];
	        } catch (\Exception $e) {
	        }
	        if ($options['useInternalJavascript'] === false) {
                return $imgtag;
            }
            return sprintf('%s<script type="text/javascript">var h=document.getElementById(\'clickslider-trigger-%d\');if(h){h.classList.add(\'clickslider\');}</script>', $imgtag, \htmlentities($youtube), str_replace("\n", ' ', $js), $uid);
        }
        return parent::render($file, $width, $height, $options, $usedPathsRelativeToCurrentScript);
    }
    /**
     * @param FileInterface $video
     * @param $width
     * @param $height
     *
     * @return string
     */
    protected function renderImage(FileInterface $video, $width, $height)
    {
        $imageconfig = GeneralUtility::trimExplode(' ', $video->getProperty('tx_sudhaus7viewhelpers_posterimage'), true);
        $imageID = GeneralUtility::trimExplode(':', $imageconfig[0], true);
        if (!$imageID[0]=='file' || !isset($imageID[1]) || empty($imageID[1])) {
            return '';
        }

        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
        /** @var ResourceFactory $resourceFactory */
        $resourceFactory = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance();

        try {
            $image = $resourceFactory->getFileObject($imageID[1]);
        } catch (\TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException $e) {
            return '';
        }

        $crop = $video instanceof FileReference ? $video->getProperty('crop') : null;
        $processingInstructions = [
           // 'width' => $width,
           // 'height' => $height,
            'crop' => $crop,
        ];
	    $image->_getMetaData();

        $imageService = $objectManager->get(ImageService::class);
        $processedImage = $imageService->applyProcessingInstructions($image, $processingInstructions);
        $imageUri = $imageService->getImageUri($processedImage);

        $ret = [sprintf('src="%s"', $imageUri)];

        $ret[]=sprintf('width="%s"', $processedImage->getProperty('width'));
        $ret[]=sprintf('height="%s"', $processedImage->getProperty('height'));


	    return [implode(" ", $ret),$image->properties_];
    }
}
