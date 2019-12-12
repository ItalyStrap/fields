<?php
class FieldsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    	\tad\FunctionMockerLe\define( 'apply_filters', function ( $filtername, $value ) { return $value; } );
    	\tad\FunctionMockerLe\define( 'esc_html', function ( $value ) { return $value; } );
    	\tad\FunctionMockerLe\define( 'esc_attr', function ( $value ) { return $value; } );
    	\tad\FunctionMockerLe\define( 'wp_kses_post', function ( $value ) { return $value; } );
    }

    protected function _after()
    {
    }

	private function getInstance() {
    	$sut = new \ItalyStrap\Fields\Fields();
		$this->assertInstanceOf( ItalyStrap\Fields\FieldsInterface::class, $sut );
		$this->assertInstanceOf( ItalyStrap\Fields\Fields::class, $sut );
		return $sut;
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable()
	{
		$this->getInstance();
	}

	/**
	 * @return array
	 */
	public function input_types_provider() {

		$all_types = ( new ItalyStrap\Fields\ViewFactory() )->getTypes();

        $array_map = [];
        foreach ( $all_types as $key => $class ) {

            if ( ! mb_strpos( $class, 'Input' ) ) {
                continue;
            }

            $array_map[] = [ $class => $key ];
        }

        return $array_map;
	}

	/**
	 * @test
	 * it_should_be_render_input_types
	 * @dataProvider  input_types_provider
	 */
	public function it_should_be_render_input_types( $type ) {

		$sut = $this->getInstance();
		$html = $sut->render( [ 'type' => $type ] );

		$this->assertStringContainsString( 'type="' . $type . '"', $html );
	}

	/**
	 * @test
	 * it_should_be_type_checkbox
	 */
	public function it_should_have_element_container() {

		$sut = $this->getInstance();
		$attr = [
			'type'	=> 'text',
			'label' => 'With Span Container element',
			'container'	=> [
				'tag'	=> 'span',
				'attr'	=> [
					'id'	=> 'some_id',
					'class'	=> 'some class',
				],
			],
		];
		$html = $sut->render( $attr );

		$this->assertStringContainsString( '<span', $html );
		$this->assertStringContainsString( 'id="some_id"', $html );
		$this->assertStringContainsString( 'class="some class"', $html );
	}
}
