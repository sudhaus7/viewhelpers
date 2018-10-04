<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 04.10.18
 * Time: 11:24
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\Resource\Rendering;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\Rendering\FileRendererInterface;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Extbase\Service\ImageService;

class VimeoRenderer extends \TYPO3\CMS\Core\Resource\Rendering\VimeoRenderer {

	/**
	 * @return int
	 */
	public function getPriority() {
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
		$youtube = parent::render( $file, $width, $height, $options, $usedPathsRelativeToCurrentScript);


		if (!empty($file->getProperty( 'tx_sudhaus7viewhelpers_posterimage'))) {
			$poster = $this->renderImage( $file, $width, $height );
		} else {

			if ($file instanceof FileReference) {
				$orgFile = $file->getOriginalFile();
			} else {
				$orgFile = $file;
			}
			$videoId = $this->getOnlineMediaHelper($file)->getOnlineMediaId($orgFile);
			$cacheDir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('typo3temp/s7viewhelpers');
			if (!is_dir($cacheDir)) {
				\TYPO3\CMS\Core\Utility\GeneralUtility::mkdir($cacheDir);
			}
			$cacheDir .= '/vimeo';
			if (!is_dir($cacheDir)) {
				\TYPO3\CMS\Core\Utility\GeneralUtility::mkdir($cacheDir);
			}
			$cacheFile = $cacheDir.'/'.$videoId.'.jpg';
			if (!is_file($cacheFile)) {
				$json = json_decode(\file_get_contents( 'https://vimeo.com/api/v2/video/'.$videoId.'.json'),true);
				$buf = file_get_contents($json[0]['thumbnail_large']);
				\file_put_contents( $cacheFile, $buf);
			}

			$poster = 'src="/typo3temp/s7viewhelpers/vimeo/'.$videoId.'.jpg"';

		}
		$uid = $file->getProperty( 'uid_foreign');
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
			return sprintf('<img %s data-replace="%s" onClick="%s" class="s7-poster-image"/><script type="text/javascript">var h=document.getElementById(\'clickslider-trigger-%d\');if(h){h.classList.add(\'clickslider\');}</script>',$poster,\htmlentities( $youtube),str_replace("\n",' ',$js),$uid);

		}
		return parent::render( $file, $width, $height,$options,$usedPathsRelativeToCurrentScript);

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
