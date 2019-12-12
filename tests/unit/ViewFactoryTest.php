<?php
class ViewFactoryTest extends \Codeception\Test\Unit
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

	public function getInstance() {
		$sut = new \ItalyStrap\Fields\ViewFactory();
		$this->assertInstanceOf( \ItalyStrap\Fields\ViewFactory::class, $sut, '' );
		return $sut;
    }

	/**
	 * @test
	 */
    public function ItShouldBeInstantiable()
    {
		$this->getInstance();
    }

	/**
	 * @test
	 */
    public function ItShouldCreateFiledType()
    {
		$sut = $this->getInstance();
		$text = $sut->make( 'text' );
		$this->assertInstanceOf( \ItalyStrap\Fields\View\RenderableElementInterface::class, $text, '' );
		$this->assertInstanceOf( \ItalyStrap\Fields\View\AbstractView::class, $text, '' );
    }
}
