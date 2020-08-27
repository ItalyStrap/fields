<?php
declare(strict_types=1);

namespace ItalyStrap\Test;

use ItalyStrap\Fields\View\RenderableElementInterface;
use ItalyStrap\Fields\View\Select;

class SelectTest extends \Codeception\Test\Unit
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
	 * @return Select
	 */
	private function getInstance(): Select {
		$sut = new Select();
		$this->assertInstanceOf( RenderableElementInterface::class, $sut, '' );
		$this->assertInstanceOf( Select::class, $sut, '' );
		return $sut;
	}

	/**
	 * @test
	 */
    public function InstanceOk()
    {
		$sut = $this->getInstance();
	}

	/**
	 * @test
	 */
    public function itShouldRenderEmptySelect()
    {
		$sut = $this->getInstance();
		$this->assertStringContainsString('<select name="some-name" type="select"></select>', $sut->render(['name' => 'some-name']) );
	}

	/**
	 * @test
	 */
    public function itShouldThrownInvalidArgumentExceptionOnSelectWhitoutNameAttributes()
    {
    	$this->expectException( \InvalidArgumentException::class );
    	$this->expectExceptionMessage('"name" value must not be empty');
		$sut = $this->getInstance();
		codecept_debug( $sut->render(['type' => 'select']) );
	}

	/**
	 * @test
	 */
    public function itShouldRenderMultipleSelectWithNameAttributeWithSquareBrackets()
    {
		$sut = $this->getInstance();
		$this->assertStringContainsString(
			'<select type="select" name="some-name[]" size="6" multiple></select>',
			$sut->render(['type' => 'multiple', 'name' => 'some-name']),
			''
		);
	}

	/**
	 * @test
	 */
    public function itShouldRenderMultipleSelectWithNameAttributeWithSquareBracketsAndIdWithoutSquareBrackets()
    {
		$sut = $this->getInstance();
		$this->assertStringContainsString(
			'id="some-name"',
			$sut->render(['type' => 'multiple', 'name' => 'some-name', 'id' => 'some-name']),
			''
		);
		$this->assertStringContainsString(
			'<select type="select" name="some-name[]" id="some-name" size="6" multiple></select>',
			$sut->render(['type' => 'multiple', 'name' => 'some-name', 'id' => 'some-name']),
			''
		);
	}
}
