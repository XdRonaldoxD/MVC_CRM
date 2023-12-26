<?php

use PHPUnit\Framework\TestCase;

class PruebaTest extends TestCase{

    private $pruebas=[];
    public function testPrueba(){
        $this->pruebas=[0,2,3,4];
        $expected=[0,2,3,4];
        $this->assertEquals($expected,$this->pruebas);
    }

}