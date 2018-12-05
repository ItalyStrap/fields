<?php

use ItalyStrap\Fields\Fields;

class FieldsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable()
    {
        $sut = new Fields();
        $this->assertInstanceOf( '\ItalyStrap\Fields\Fields', $sut );
        $this->assertInstanceOf( '\ItalyStrap\Fields\Fields_Interface', $sut );
    }
}