<?php
namespace Tests\Unit\Domain\Invoice\ValueObjects;

use PHPUnit\Framework\TestCase;
use App\Domain\Invoice\ValueObjects\Total;

final class TotalTest extends TestCase
{
    public function testCanGetProperties(): void
    {
        $value = 100.0;

        $total = new Total($value);

        $this->assertEquals($value, $total->getTotal());
    }

    public function testCanCreateFromDataArray(): void
    {
        $data = [
            'total' => 50.0,
        ];

        $total = Total::fromArray($data);

        $this->assertEquals($data['total'], $total->getTotal());
    }
}
