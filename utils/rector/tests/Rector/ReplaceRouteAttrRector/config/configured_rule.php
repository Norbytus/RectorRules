<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(
        \Utils\Rector\Rector\ReplaceRouteAttrRector::class,
        [
            Attribute::class => SensitiveParameter::class,
        ]
    );
};
