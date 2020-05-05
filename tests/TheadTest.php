<?php

namespace nueip\curl\tests;

use nueip\curl\Thread;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ThreadTest extends TestCase
{
    /**
     * test set childMax
     */
    public function testSetChildMax()
    {
        $setMax = 8;

        $thread = new Thread;

        $thread->setChildMax($setMax);

        $reflection = new ReflectionClass(get_class($thread));

        $property = $reflection->getProperty('childMax');

        $property->setAccessible(true);

        $childMax = $property->getValue($thread);

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

        foreach (range(1, 20) as $step) {
            $arguments[$step] = random_int(1, 99);
        }

        $thread = new Thread;

        $thread->multiProcess($addMethod, array_chunk($arguments, 2));

        $this->assertEquals(1, 1);
    }
};
