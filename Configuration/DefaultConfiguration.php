<?php


return [

    'SYS'=>[
        'caching'=>[
            'cacheConfigurations'=>[
                'sudhaus7viewhelpers_metatags'=>[
                    'backend'=>\TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend::class,
                    'frontend'=>\TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class,
                    'groups'=>['pages'],
                    'options'=>[
                        'defaultLifetime'=>0,
                    ]
                ],
                'sudhaus7viewhelpers_cache'=>[
                    'backend'=>\TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend::class,
                    'frontend'=>\TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class,
                    'groups'=>['pages'],
                    'options'=>[
                        'defaultLifetime'=>0,
                    ]
                ],
            ],
        ]
    ]
];
