<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Factories;

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;

/**
 * @author Sebastian Schreiber
 */
class CacheFactory
{
    /**
     * @var string
     */
    public const CACHE_KEY = 'bzgaberatungsstellensuche_cache_coordinates';

    /**
     * @var CacheManager
     */
    protected $cacheManager;

    public function injectCacheManager(CacheManager $cacheManager): void
    {
        $this->cacheManager = $cacheManager;
    }

    public function createInstance(): FrontendInterface
    {
        return $this->cacheManager->getCache(self::CACHE_KEY);
    }
}
