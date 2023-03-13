<?php
namespace Tests\Unit\Domain\Invoice\ValueObjects;

use PHPUnit\Framework\TestCase;
use App\Domain\Invoice\ValueObjects\Progressive;

final class ProgressiveTest extends TestCase
{
    public function testCanGetProperties()
    {
        $value = 'INV-001';

        $progressive = new Progressive($value);

        $this->assertEquals($value, $progressive->getProgressive());
    }

    public function testCanCreateFromDataArray()
    {
        $data = [
            'progressive' => '001',
        ];

        $progressive = Progressive::fromArray($data);

        $this->assertEquals($data['progressive'], $progressive->getProgressive());
    }
}
