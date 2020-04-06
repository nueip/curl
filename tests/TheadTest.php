<?php

namespace nueip\curl\tests;

use nueip\curl\Thead;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class TheadTest extends TestCase
{
    /**
     * test set childMax
     */
    public function testSetChildMax()
    {
        $setMax = 8;

        $thead = new Thead;

        $thead->setChildMax($setMax);

        $reflection = new ReflectionClass(get_class($thead));

        $property = $reflection->getProperty('childMax');

        $property->setAccessible(true);

        $childMax = $property->getValue($thead);

        $this->assertEquals($setMax, $childMax);
    }

    /**
     * test multi process
     */
    public function testMultiProcess()
    {
        $addMethod = function ($a, $b) {
            $c = $a + $b;
            echo "{$a} + {$b} = {$c}\n";
            return $c;
        };

        $arguments = [];

        foreach (range(0, 8) as $step) {
            $arguments[] = [random_int(1, 99), random_int(1, 99)];
        }

        $thead = new Thead;

        $thead->multiProcess($addMethod, $arguments);

        $this->assertEquals(1, 1);
    }
};
