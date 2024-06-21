<?php

declare(strict_types=1);

namespace Utils\Rector\Tests\Rector\ReplaceRouteAttrRector;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Utils\Rector\Rector\ReplaceRouteAttrRector;

#[CoversClass(ReplaceRouteAttrRector::class)]
final class ReplaceRouteAttrRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): \Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
