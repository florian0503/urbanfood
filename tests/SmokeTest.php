<?php

namespace App\Tests;

use App\Kernel;
use PHPUnit\Framework\TestCase;

final class SmokeTest extends TestCase
{
    public function testKernelClassExists(): void
    {
        self::assertTrue(class_exists(Kernel::class));
    }
}
