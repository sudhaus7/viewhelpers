<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 26/01/2017
 * Time: 11:56
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\Render;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;

/**
 * Class CacheViewHelper
 * @package SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\Render
 */
class CacheViewHelper extends AbstractViewHelper {

    const ID_PREFIX = 'sudhaus7-viewhelper';

    const ID_SEPARATOR = '-';

    /**
     * @var \TYPO3\CMS\Core\Cache\Frontend\StringFrontend
     */
    protected $cache;

    /**
     * @return void
     */
    public function initialize() {
        $cacheManager = isset($GLOBALS['typo3CacheManager']) ? $GLOBALS['typo3CacheManager'] : GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager');
        $this->cache = $cacheManager->getCache('sudhaus7viewhelpers_cache');
    }

    /**
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        $this->registerArgument('key', 'mixed', 'Objekt oder ID-String', true);
        $this->registerArgument('tags', 'string', 'Tags to register', false,'');
        $this->registerArgument('content', 'string', 'Content to be cached instead of body', false, null);
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    public function render()
    {

        $identity = $this->arguments['key'];
        $content = $this->arguments['content'];



        if (FALSE === ctype_alnum(preg_replace('/[\-_]/i', '', $identity))) {
            if (true === $identity instanceof DomainObjectInterface) {
                $identity = get_class($identity) . self::ID_SEPARATOR . $identity->getUid();
            } elseif (true === method_exists($identity, '__toString')) {
                $identity = (string)$identity;
            } else {
                throw new \RuntimeException(
                    'Parameter $identity for Render/CacheViewHelper was not a string or a string-convertible object',
                    2352581782
                );
            }
        }

        // Hash the cache-key to circumvent disallowed chars
        $identity = sha1($identity);

        if (true === $this->has($identity)) {
            return $this->retrieve($identity);
        }

        if (is_null($content)) {
            $content = $this->renderChildren();
        }
        try {
            $this->store($content, $identity);
        } catch (\TYPO3\CMS\Core\Cache\Exception\InvalidDataException $e) {
            // ignore
        }
        return $content;
    }

    /**
     * @param string $id
     * @return boolean
     */
    protected function has($id) {
        return (boolean) $this->cache->has(self::ID_PREFIX . self::ID_SEPARATOR . $id);
    }

    /**
     * @param mixed $value
     * @param string $id
     * @return void
     * @throws \TYPO3\CMS\Core\Cache\Exception\InvalidDataException
     */
    protected function store($value, $id) {
        $this->cache->set(self::ID_PREFIX . self::ID_SEPARATOR . $id, $value, GeneralUtility::trimExplode(',',$this->arguments['tags']));
    }

    /**
     * @param string $id
     * @return mixed
     */
    protected function retrieve($id) {
        if ($this->cache->has(self::ID_PREFIX . self::ID_SEPARATOR . $id)) {
            return $this->cache->get(self::ID_PREFIX . self::ID_SEPARATOR . $id);
        }
        return NULL;
    }

}
