<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 01.10.18
 * Time: 13:44
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\Resource\Rendering;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\Rendering\FileRendererInterface;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Extbase\Service\ImageService;

class VideoTagRenderer  implements FileRendererInterface {

	/**
	 * Mime types that can be used in the HTML Video tag
	 *
	 * @var array
	 */
	protected $possibleMimeTypes = ['video/mp4', 'video/webm', 'video/ogg', 'application/ogg'];

	/**
	 * @return int
	 */
	public function getPriority() {
		return 50;
	}

	/**
	 * @param FileInterface $file
	 *
	 * @return bool
	 */
	public function canRender( FileInterface $file ) {
		return in_array($file->getMimeType(), $this->possibleMimeTypes, true);
	}


	/**
	 * @param FileInterface $file
	 * @param int|string $width
	 * @param int|string $height
	 * @param array $options
	 * @param bool $usedPathsRelativeToCurrentScript
	 *
	 * @return string
	 */
	public function render(
		FileInterface $file,
		$width,
		$height,
		array $options = [],
		$usedPathsRelativeToCurrentScript = false
	) {


		if (!empty($file->getProperty( 'tx_sudhaus7viewhelpers_posterimage'))) {
			$poster = $this->renderImage($file, $width, $height);
			$uid = $file->getProperty( 'uid_foreign');
			if (!empty($poster)) {

				$options['autoplay']=1;
				$video = $this->renderVideo( $file, $width, $height,$options,$usedPathsRelativeToCurrentScript);
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
				return sprintf('<img %s data-replace="%s" onClick="%s" class="s7-poster-image"/><script type="text/javascript">var h=document.getElementById(\'clickslider-trigger-%d\');if(h){h.classList.add(\'clickslider\');}</script>',$poster,\htmlentities( $video),str_replace("\n",' ',$js),$uid);

			}
		}

		return $this->renderVideo( $file, $width, $height,$options,$usedPathsRelativeToCurrentScript);

	}


	protected function renderVideo(FileInterface $file,
		$width,
		$height,
		array $options = [],
		$usedPathsRelativeToCurrentScript = false) {
		// If autoplay isn't set manually check if $file is a FileReference take autoplay from there
		if (!isset($options['autoplay']) && $file instanceof FileReference) {
			$autoplay = $file->getProperty('autoplay');
			if ($autoplay !== null && !isset($options['autoplay'])) {
				$options['autoplay'] = $autoplay;
			}
		}

		$attributes = [];
		if ((int)$width > 0) {
			$attributes[] = 'width="' . (int)$width . '"';
		}
		if ((int)$height > 0) {
			$attributes[] = 'height="' . (int)$height . '"';
		}
		if (!isset($options['controls']) || !empty($options['controls'])) {
			$attributes[] = 'controls';
		}
		if (!empty($options['autoplay'])) {
			$attributes[] = 'autoplay';
		}
		if (!empty($options['muted'])) {
			$attributes[] = 'muted';
		}
		if (!empty($options['loop'])) {
			$attributes[] = 'loop';
		}
		foreach (['class', 'dir', 'id', 'lang', 'style', 'title', 'accesskey', 'tabindex', 'onclick', 'controlsList'] as $key) {
			if (!empty($options[$key])) {
				$attributes[] = $key . '="' . htmlspecialchars($options[$key]) . '"';
			}
		}

		$url = htmlspecialchars($file->getPublicUrl($usedPathsRelativeToCurrentScript));
		if (substr($url,0,4)!='http' && substr($url,0,1)!='/') {
			$url = '/'.$url;
		}

		return sprintf(
			'<video%s><source src="%s" type="%s"></video>',
			empty($attributes) ? '' : ' ' . implode(' ', $attributes),
			$url,
			$file->getMimeType()
		);
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

		$imageconfig = GeneralUtility::trimExplode(' ',$video->getProperty( 'tx_sudhaus7viewhelpers_posterimage'),true);
		$imageID = GeneralUtility::trimExplode(':',$imageconfig[0],true);
		if (!$imageID[0]=='file' || !isset($imageID[1]) || empty($imageID[1])) return '';

		/** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
		/** @var ResourceFactory $resourceFactory */
		$resourceFactory = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance();

		try {
			$image = $resourceFactory->getFileObject( $imageID[1] );
		} catch (\TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException $e) {
			return '';
		}

		$crop = $video instanceof FileReference ? $video->getProperty('crop') : null;
		$processingInstructions = [
			'width' => $width,
			'height' => $height,
			'crop' => $crop,
		];


		$imageService = $objectManager->get(ImageService::class);
		$processedImage = $imageService->applyProcessingInstructions($image, $processingInstructions);
		$imageUri = $imageService->getImageUri($processedImage);

		$ret = [sprintf('src="%s"',$imageUri)];

		$ret[]=sprintf('width="%s"', $processedImage->getProperty('width'));
		$ret[]=sprintf('height="%s"', $processedImage->getProperty('height'));


		return implode(" ",$ret);
	}
}
