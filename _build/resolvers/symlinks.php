<?php
/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;

    $dev = MODX_BASE_PATH . 'Extras/App/';
    /** @var xPDOCacheManager $cache */
    $cache = $modx->getCacheManager();
    if (file_exists($dev) && $cache) {
        if (!is_link($dev . 'assets/components/app')) {
            $cache->deleteTree(
                $dev . 'assets/components/app/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_ASSETS_PATH . 'components/app/', $dev . 'assets/components/app');
        }
        if (!is_link($dev . 'core/components/app')) {
            $cache->deleteTree(
                $dev . 'core/components/app/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_CORE_PATH . 'components/app/', $dev . 'core/components/app');
        }
    }
}

return true;