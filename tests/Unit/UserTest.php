<?php 
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_sum_function()
    {
        $result = $this->sum(2, 3);

        $this->assertEquals(5, $result);
    }

    private function sum($a, $b)
    {
        return $a + $b;
    }
}
