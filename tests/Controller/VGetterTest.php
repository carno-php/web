<?php
/**
 * VGetter test
 * User: moyo
 * Date: 2019-01-19
 * Time: 16:44
 */

namespace Carno\Web\Tests\Controller;

use Carno\Web\Controller\VGetter;
use PHPUnit\Framework\TestCase;

class VGetterTest extends TestCase
{
    public function testDefault()
    {
        $vg1 = new VGetter();

        $this->assertEquals(0, $vg1->integer('k1'));
        $this->assertEquals(1, $vg1->integer('k1', 1));
        $this->assertEquals(-1, $vg1->integer('k1', -1));

        $this->assertEquals('', $vg1->string('k2'));
        $this->assertEquals('hello', $vg1->string('k2', 'hello'));
        $this->assertEquals('123', $vg1->string('k2', 123));

        $this->assertEquals(false, $vg1->boolean('k3'));
        $this->assertEquals(true, $vg1->boolean('k3', true));
    }

    public function testBoolean()
    {
        $vg1 = new VGetter([
            'c1' => 'yes',
            'c2' => 'no',
            'c3' => 1,
            'c4' => 0,
            'c5' => 'true',
            'c6' => 'false'
        ]);

        $this->assertTrue($vg1->boolean('c1'));
        $this->assertTrue($vg1->boolean('c3'));
        $this->assertTrue($vg1->boolean('c5'));

        $this->assertFalse($vg1->boolean('c2'));
        $this->assertFalse($vg1->boolean('c4'));
        $this->assertFalse($vg1->boolean('c6'));

        $this->assertFalse($vg1->boolean('c7'));
    }
}
