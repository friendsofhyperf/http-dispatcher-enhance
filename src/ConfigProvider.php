<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/http-dispatcher-enhance.
 *
 * @link     https://github.com/friendsofhyperf/http-dispatcher-enhance
 * @document https://github.com/friendsofhyperf/http-dispatcher-enhance/blob/1.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace FriendsOfHyperf\HttpDispatcherEnhance;

class ConfigProvider
{
    public function __invoke(): array
    {
        defined('BASE_PATH') or define('BASE_PATH', '');

        return [
            'dependencies' => [],
            'aspects' => [],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                    'class_map' => [
                        \Hyperf\Dispatcher\AbstractRequestHandler::class => __DIR__ . '/../class_map/Dispatcher/AbstractRequestHandler.php',
                    ],
                ],
            ],
            'commands' => [],
            'listeners' => [],
            'publish' => [],
        ];
    }
}
