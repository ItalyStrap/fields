<?php

use ItalyStrap\Fields\Fields;

class FieldsTest extends \Codeception\TestCase\WPTestCase
{

    private $fields_array;
    private $fields_type;
    private $widget;

    private $dom;

    /**
     * Html attribute
     *
     * @var array
     */
    private $attr = array();

    public function setUp() {
        // before
        parent::setUp();

        $this->fields_array = require( __DIR__ . '\..\_data\fields.php' );

        $this->dom = new \DOMDocument();

        $this->test_type_text = array(
            'name'      => __( 'Widget Class', 'italystrap' ),
            'desc'      => __( 'Enter the widget class name.', 'italystrap' ),
            'id'        => 'widget_class',
            // '_id'       => 'widget_class',
            // '_name'     => 'widget_class',
            'type'      => 'text',
            'class'     => 'widefat widget_class',
            'placeholder'     => 'widefat widget_class',
            'default'   => true,
            'value'     => 'general',
            'size'      => '',
        );

        $this->test_type_hidden = array(
                'name'      => __( 'Widget Class', 'italystrap' ),
                'desc'      => __( 'Enter the widget class name.', 'italystrap' ),
                'id'        => 'widget_class',
                // '_id'       => 'widget_class',
                // '_name'     => 'widget_class',
                'type'      => 'hidden',
                'class'     => 'widefat widget_class',
                'placeholder'     => 'widefat widget_class',
                'default'   => true,
                'value'     => 'general',
                'size'      => '',
                 );

        $this->test_type_textarea = array(
                'name'      => __( 'Widget Class', 'italystrap' ),
                'desc'      => __( 'Enter the widget class name.', 'italystrap' ),
                'id'        => 'widget_class',
                // '_id'       => 'widget_class',
                // '_name'     => 'widget_class',
                'class'     => 'widefat widget_class',
                'placeholder'     => 'widefat widget_class',
                'default'   => true,
                // 'value'      => 'Some_value',
                 );

        $this->test_type_checkbox = array(
                'name'      => __( 'Widget Class', 'italystrap' ),
                'desc'      => __( 'Enter the widget class name.', 'italystrap' ),
                'id'        => 'widget_class',
                // '_id'       => 'widget_class',
                // '_name'     => 'widget_class',
                'type'      => 'checkbox',
                'class'     => 'widefat widget_class',
                // 'default'   => '',
                'value'     => '1',
                 );

        $this->test_type_select = array(
                'name'      => __( 'Widget Class', 'italystrap' ),
                'desc'      => __( 'Enter the widget class name.', 'italystrap' ),
                'id'        => 'widget_class',
                // '_id'       => 'widget_class',
                // '_name'     => 'widget_class',
                'type'      => 'text',
                'class'     => 'widefat widget_class',
                'default'   => true,
                'option'   => array( 'key' => 'val' ),
                'value'     => 'Some value',
                 );

        $this->attr = array(
            'type'            => 'text',
            'class'           => $this->test_type_textkey['class'],
            'name'            => $this->test_type_textkey['_name'],
            'id'              => $this->test_type_textkey['_id'],
            'value'           => ( isset( $this->test_type_textkey['value'] ) ? $this->test_type_textkey['value'] : ( isset( $this->test_type_textkey['default'] ) ? $this->test_type_textkey['default'] : '' ) ),
        );

        // your set up methods here
    }

    public function tearDown() {
        // your tear down methods here

        // then
        parent::tearDown();
    }

    private function make_instance() {
        return new Fields();
    }

    /**
     * @test
     * it_should_be_field_array_settings_set
     */
    public function it_should_be_field_array_settings_set() {
        $this->assertTrue( isset( $this->fields_array ) );
    }

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable()
    {
        $sut = $this->make_instance();

        $this->assertInstanceOf( '\ItalyStrap\Fields\Fields', $sut );
        $this->assertInstanceOf( '\ItalyStrap\Fields\Fields_Interface', $sut );
    }

    /**
     * @return array
     */
    public function input_types_provider() {

        $sut = $this->make_instance();

        $array_map = [];
        foreach ( (array) $sut->get_all_types() as $key => $class ) {

            if ( ! mb_strpos( $class, 'Input' ) ) {
                continue;
            }
            $array_map[ $key ] = [ $class ];
        }
        return $array_map;
    }

    /**
     * @test
     * it_should_be_render_input_types
     * @dataProvider  input_types_provider
     */
    public function it_should_be_render_input_types( $type ) {

        $sut = $this->make_instance();
        $html = $sut->render( [ 'type' => $type ] );

        $this->assertContains( 'type="' . $type . '"', $html );
    }

	/**
	 * @test
	 * it_should_be_hidden
	 */
	public function it_should_be_type_text_by_default() {

		$sut = $this->make_instance();
		$html = $sut->render( ['type' => null ] );

//        $this->assertContains( 'type="text"', $html );
		$this->assertContains( '<input', $html );
	}

	/**
	 * @test
	 * it_should_be_hidden
	 */
	public function it_should_be_type_checkbox() {

		$sut = $this->make_instance();
		$attr = [
			'type'	=> 'checkbox'
		];
		$html = $sut->render( $attr );

		$this->assertContains( 'type="checkbox"', $html );
	}

	private function get_html( $cb ) {
		$sut = $this->make_instance();
		$attr[ 'type' ] = 'text';
		$attr[ 'show_on_cb' ] = $cb;
		return $sut->render( $attr );
	}

	/**
	 * @return array
	 */
	public function show_on_cb_show_provider() {
		return [
			[ '__return_true' ],
			[ true ],
			['true'],
			[ 1 ],
			[ '1' ],
			[ null ],
		];
	}

	/**
	 * @test
	 * it_should_be_render_input_types
	 * @dataProvider  show_on_cb_show_provider
	 */
	public function it_should_be_shown( $cb ) {
		$this->assertContains( '<input', $this->get_html( $cb ) );
	}

	/**
	 * @return array
	 */
	public function show_on_cb_hidden_provider() {
		return [
			[ '__return_false' ],
			[ false ],
			['false'],
			[ 0 ],
			[ '0' ],
			[ '' ],
			[ 0.0 ],
		];
	}

	/**
	 * @test
	 * it_should_be_render_input_types
	 * @dataProvider  show_on_cb_hidden_provider
	 */
	public function it_should_be_hidden( $cb ) {
		$this->assertEmpty( $this->get_html( $cb ) );
	}

    /**
     * @test
     */
    public function it_should_have_label()
    {

        $sut = $this->make_instance();
        $html = $sut->render( ['label' => 'Title label' ] );

        $this->assertContains( 'Title label', $html );

        $html = $sut->render(
            [
                'label' => [
                    'content'       => 'Title label',
                    'attributes'    => [
                        'class' => 'some_class',
                    ],
                ]
            ]
        );

        $this->assertContains( 'Title label', $html );
        $this->assertContains( 'class="some_class"', $html );

    }

    /**
     * @test
     */
    public function it_should_have_description()
    {

        $sut = $this->make_instance();
        $html = $sut->render( ['desc' => 'Description' ] );

        $this->assertContains( 'Description', $html );

        $html = $sut->render( ['desc' => [
            'content'      => 'Description',
            'attributes' => [
                'class' => 'some_desc_class',
            ],
        ] ] );

        $this->assertContains( 'Description', $html );
        $this->assertContains( 'class="some_desc_class"', $html );

    }

	/**
	 * @return array
	 */
	public function checkbox_val_provider() {
		/**
		 * default|value|instance
		 */
		return [
			/**
			 * Settato solo il valore di default
			 * la checkbox è spuntata
			 */
			[ 1, null, null ],
			[ '1', null, null ],
			[ true, null, null ],
			[ 'on', null, null ],
			[ 'vero', null, null ],
			/**
			 * Settato solo il valore di value
			 * la checkbox è spuntata
			 */
			[ null, 1, null ],
			[ null, '1', null ],
			[ null, true, null ],
			[ null, 'on', null ],
			[ null, 'vero', null ],
			/**
			 * Settato solo instance
			 * la checkbox è spuntata
			 */
			[ null, null, 1 ],
			[ null, null, '1' ],
			[ null, null, true ],
			[ null, null, 'on' ],
			[ null, null, 'vero' ],
			/**
			 * Se nulla è settato e la instance ritorna null o ""
			 * NON mostro la spunta
			 */
			[ null, null, '' ],
			[ null, null, null ],
			/**
			 * Se sono settati default e/o value ma la instance
			 * è "" non c'è la spunta.
			 */
			[ '1', null, '' ],
			[ null, '1', '' ],
			[ '1', '1', '' ],
		];
	}

	private function get_checkbox( $default, $value, $instance_val ) {
		$sut = $this->make_instance();
		$attr[ 'type' ] = 'checkbox';

		$id = 'checkbox_ID';
		$attr['id'] = $id;
		$attr['default'] = $default;
		$attr['value'] = $value;
		$instance[ $id ] = $instance_val;
		return $sut->render( $attr, $instance );
	}

	/**
	 * @test
	 * it_should_be_checkbox_checked
	 * @dataProvider  checkbox_val_provider
	 */
	public function it_should_be_checkbox_checked( $default, $value, $instance_val ) {
		$needle = 'checked="checked"';

		if ( '' === $instance_val || ( is_null( $default ) && is_null( $value ) && is_null( $instance_val ) ) ) {
			$this->assertNotContains( $needle, $this->get_checkbox( $default, $value, $instance_val ) );
		} else {
			$this->assertContains( $needle, $this->get_checkbox( $default, $value, $instance_val ) );
		}
	}

    /**
     * Get fields_type output
     */
    public function get_fields_input_output( $type = 'text', $tag = 'input' ) {

        $sut = $this->make_instance();
    
        $out = $sut->$tag(
            [],
            [
                '_name'         => true,
                '_id'           => 'widget_class',
                'default'       => true,
                'placeholder'   => true,
                'size'          => true,
                'desc'          => true,
            ]
        );

        $this->dom->loadHTML( $out );

        return $this->dom->getElementById('widget_class');
    }

    public function input_types_and_attributes_provider() {
        return [
            [ 'text', 'type' ],
            [ 'text', 'class' ],
            [ 'text', 'name' ],
            [ 'text', 'id' ],
            [ 'text', 'value' ],
            [ 'text', 'placeholder' ],
            [ 'text', 'size' ],
            [ 'textarea', 'class' ],
            [ 'textarea', 'name' ],
            [ 'textarea', 'id' ],
            // [ 'textarea', 'cols' ], // Verificare perché non funziona, is empty
            // [ 'textarea', 'rows' ], // Verificare perché non funziona, is empty
            [ 'textarea', 'value' ],
            [ 'textarea', 'placeholder' ],
        ];
    }

    /**
     * @test
     * it should have proper attributes
     * @dataProvider  input_types_and_attributes_provider
     */
    // public function it_should_have_proper_attributes( $type, $attr ) {

    //     $element = $this->get_fields_input_output( $type );

    //     $this->assertNotEmpty( $element->getAttribute( $attr ), "Attribute $attr is empty for type $type" );

    // }

    /**
     * @test
     * it_should_be_have_html_attr_input
     * Method input from abstract class
     */
    // public function it_should_be_have_html_attr_input() {

    //     $sut = $this->make_instance();
    //     $out = $sut->input( array(), $this->test_type_text );
    //     foreach ( $this->attr as $key => $value ) {
    //         $this->assertTrue( false !== strpos( $out, $key ) );
    //     }
    // }

    /**
     * @test
     * it_should_be_the_output_a_string
     */
    // public function it_should_be_the_output_a_string() {

    //     $sut = $this->make_instance();
    //     $out = $sut->text( $this->test_type_text );
    //     $this->assertTrue( is_string( $out ) );
    // }

    /**
     * @test
     * it_should_be_have_html_attr
     */
    // public function it_should_be_have_html_attr() {
    //     $sut = $this->make_instance();
    //     $out = $sut->text( $this->test_type_text );
    //     foreach ( $this->attr as $key => $value ) {
    //         $this->assertTrue( false !== strpos( $out, $key ) );
    //     }
    // }

    /**
     * Get fields_type output
     */
    public function get_fields_type_output( $type = 'text' ) {
        $sut = $this->make_instance();

        // $fields_type = 'field_type_' . $type;
        $fields_type = $type;

        $test_type = 'test_type_' . $type;
    
        $out = $sut->$fields_type( $this->$test_type );

        $this->dom->loadHTML( $out );

        return $this->dom->getElementById('widget_class');
    
    }

    public function types_and_attributes_provider() {
        return [
            [ 'text', 'type' ],
            [ 'text', 'class' ],
            [ 'text', 'name' ],
            [ 'text', 'id' ],
            [ 'text', 'value' ],
            [ 'text', 'placeholder' ],
            [ 'hidden', 'type' ],
            [ 'hidden', 'class' ],
            [ 'hidden', 'name' ],
            [ 'hidden', 'id' ],
            [ 'hidden', 'value' ],
            [ 'hidden', 'placeholder' ],
            [ 'textarea', 'class' ],
            [ 'textarea', 'name' ],
            [ 'textarea', 'id' ],
            // [ 'textarea', 'value' ],
            [ 'textarea', 'placeholder' ],
            [ 'checkbox', 'type' ],
            [ 'checkbox', 'class' ],
            [ 'checkbox', 'name' ],
            [ 'checkbox', 'id' ],
            [ 'checkbox', 'value' ],
            [ 'checkbox', 'checked' ], // da testare: se non checked, value int e string e default int e string
            [ 'select', 'class' ],
            [ 'select', 'name' ],
            [ 'select', 'id' ],
            // [ 'select', 'option' ],
            // [ 'select', 'value' ],
        ];
    }

    /**
     * @test
     * it should have proper attributes
     * @dataProvider  types_and_attributes_provider
     */
    // public function it_should_have_proper_attributes( $type, $attr ) {

    //     $element = $this->get_fields_type_output( $type );

    //     $this->assertNotEmpty( $element->getAttribute( $attr ), "Attribute $attr is empty for type $type" );

    // }

}