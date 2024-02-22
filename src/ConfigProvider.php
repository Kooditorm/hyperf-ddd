<?php
declare(strict_types=1);

namespace DCore;

class ConfigProvider
{

    public function __invoke(): array
    {
        return [
            'dependencies' => [],
            // 合并到  config/autoload/annotations.php 文件
            'annotations'  => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
        ];
    }
}
