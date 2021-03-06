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

    public function setUp(): void {
        // before
        parent::setUp();

        $this->fields_array = require codecept_data_dir() . '\fields.php';

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

    public function tearDown(): void {
        // your tear down methods here

        // then
        parent::tearDown();
    }

    private function getInstance() {
        return new Fields();
    }

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable()
    {
        $sut = $this->getInstance();

		$this->assertInstanceOf( ItalyStrap\Fields\FieldsInterface::class, $sut );
        $this->assertInstanceOf( ItalyStrap\Fields\Fields::class, $sut );
    }

    /**
     * @return array
     */
    public function input_types_provider() {

//        $sut = $this->getInstance();

        $all_types = ( new ItalyStrap\Fields\ViewFactory() )->getTypes();

        $array_map = [];
        foreach ( $all_types as $key => $class ) {

            if ( ! mb_strpos( $class, 'Input' ) ) {
                continue;
            }
            $array_map[] = [ $class => $key ];
            break;
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

	/**
	 * @test
	 * it_should_be_type_checkbox
	 */
	public function it_should_not_have_element_container() {

		$sut = $this->getInstance();
		$attr = [
			'type'	=> 'text',
			'id'	=> 'no_container',
			'container'	=> [
				'tag'	=> null,
			],
		];
		$expected = '<input type="text" id="no_container" name="no_container"/>';
		$html = $sut->render( $attr );



		$this->assertStringNotContainsString( 'div', $html );
		$this->assertStringContainsString( $expected, $html );
	}

	/**
	 * @test
	 * it_should_be_type_text_by_default
	 */
	public function it_should_be_type_text_by_default() {

		$sut = $this->getInstance();
		$html = $sut->render( ['type' => null ] );
		$this->assertStringContainsString( '<input type="text"', $html );

		$html = $sut->render( [] );
		$this->assertStringContainsString( '<input type="text"', $html );
	}

	/**
	 * @test
	 * it_should_be_id_name_correct
	 */
	public function it_should_be_id_name_correct() {

		$sut = $this->getInstance();

		/**
		 * In ItalyStrap\Settings API
		 */
		$expected = 'italystrap_settings[show-ids]';
		$attr = [
			'type'	=> 'text',
			'_id'	=> $expected
		];
		$html = $sut->render( $attr );

		$this->assertStringContainsString( 'id="' . $expected . '"', $html );
		$this->assertStringContainsString( 'name="' . $expected . '"', $html );
		$this->assertStringNotContainsString( '_id="' . $expected . '"', $html );
		$this->assertStringNotContainsString( '_name="' . $expected . '"', $html );

		/**
		 * In ItalyStrap\Widget
		 */
		$expected_id = 'widget-italystrap-posts-6-add_permalink_wrapper';
		$expected_name = 'widget-italystrap-posts[6][add_permalink_wrapper]';
		$attr = [
			'type'	=> 'text',
			'_id'	=> $expected_id,
			'_name'	=> $expected_name,
		];
		$html = $sut->render( $attr );

		$this->assertStringContainsString( 'id="' . $expected_id . '"', $html );
		$this->assertStringContainsString( 'name="' . $expected_name . '"', $html );
		$this->assertStringNotContainsString( '_id="' . $expected_id . '"', $html );
		$this->assertStringNotContainsString( '_name="' . $expected_name . '"', $html );
	}

	/**
	 * @test
	 * it_should_be_type_checkbox
	 */
	public function it_should_be_type_checkbox() {

		$sut = $this->getInstance();
		$attr = [
			'type'	=> 'checkbox'
		];
		$html = $sut->render( $attr );

		$this->assertStringContainsString( 'type="checkbox"', $html );
	}

	/**
	 * @test
	 * it_should_have_id_and_name_with_same_value
	 */
	public function it_should_have_id_and_name_with_same_value() {

		$sut = $this->getInstance();
		$attr = [
			'type'	=> 'text',
			'id'	=> 'foo',
		];
		$html = $sut->render( $attr );

		$this->assertStringContainsString( 'id="foo"', $html );
		$this->assertStringContainsString( 'name="foo"', $html );
	}

	/**
	 * @test
	 * it_should_be_type_textarea
	 */
	public function it_should_be_type_textarea() {

		$sut = $this->getInstance();
		$attr = [
			'type'	=> 'textarea',
			'id'	=> 'foo',
			'value'	=> 'Some text',
		];
		$html = $sut->render( $attr );

		$this->assertStringContainsString( 'type="textarea"', $html );
		$this->assertStringContainsString( 'id="foo"', $html );
		$this->assertStringContainsString( 'name="foo"', $html );
		$this->assertStringContainsString( '>Some text</textarea>', $html );
		$this->assertStringNotContainsString( 'value="' . $attr['value'] . '"', $html );
	}

	/**
	 * @test
	 * it_should_be_type_editor
	 */
	public function it_should_be_type_editor() {

		$sut = $this->getInstance();
		$attr = [
			'type'	=> 'editor',
			'id'	=> 'foo',
			'value'	=> 'Some text',
		];
		$html = $sut->render( $attr );

		$this->assertStringContainsString( 'class="wp-editor-container"', $html );
		$this->assertStringContainsString( 'id="foo"', $html );
		$this->assertStringContainsString( 'name="foo"', $html );
		$this->assertStringContainsString( '>Some text</textarea>', $html );
	}

	private function get_html( $cb ) {
		$sut = $this->getInstance();
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
		$this->assertStringContainsString( '<input', $this->get_html( $cb ) );
	}

	/**
	 * @return array
	 */
	public function show_on_cb_hidden_provider() {
		return [
			[ '__return_false' ],
			[ false ],
//			['false'],
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

        $sut = $this->getInstance();
        $html = $sut->render( ['label' => 'Title label' ] );

        $this->assertStringContainsString( 'Title label', $html );

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

        $this->assertStringContainsString( 'Title label', $html );
        $this->assertStringContainsString( 'class="some_class"', $html );

    }

    /**
     * @test
     */
    public function it_should_have_description()
    {

        $sut = $this->getInstance();
        $html = $sut->render( ['desc' => 'Description' ] );

        $this->assertStringContainsString( 'Description', $html );

        $html = $sut->render( ['desc' => [
            'content'      => 'Description',
            'attributes' => [
                'class' => 'some_desc_class',
            ],
        ] ] );

        $this->assertStringContainsString( 'Description', $html );
        $this->assertStringContainsString( 'class="some_desc_class"', $html );

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
			/**
			 * Testo un eventuale multicheck
			 */
			[ null, 'key1', null, [ 'key1' => 'Value1', 'key2' => 'Value2' ] ],
			[ [ 'key1', 'key2' ], null, null, [ 'key1' => 'Value1', 'key2' => 'Value2' ] ],
		];
	}

	private function get_checkbox( $default, $value, $instance_val, $options = null ) {
		$sut = $this->getInstance();
		$attr[ 'type' ] = 'checkbox';

		$id = 'checkbox_ID';
		$attr['id'] = $id;
		$attr['default'] = $default;
		$attr['value'] = $value;
		if ( $options ) {
			$attr['options'] = (array) $options;
		}
		$instance[ $id ] = $instance_val;
		return $sut->render( $attr, $instance );
	}

	/**
	 * @test
	 * it_should_be_checkbox_checked
	 * @dataProvider  checkbox_val_provider
	 */
	public function it_should_be_checkbox_checked( $default, $value, $instance_val, $options = null ) {
		$needle = 'checked';

		if ( '' === $instance_val || ( is_null( $default ) && is_null( $value ) && is_null( $instance_val ) ) ) {
			$this->assertStringNotContainsString( $needle, $this->get_checkbox( $default, $value, $instance_val, $options ) );
		} else {
			$this->assertStringContainsString( $needle, $this->get_checkbox( $default, $value, $instance_val, $options ) );
		}
	}

	/**
	 * @return array
	 */
	public function radio_val_provider() {
		/**
		 * default|value|instance
		 */
		return [
			/**
			 * Settato solo il valore di default
			 * la radio è spuntata
			 */
			[ 'key', null, null ],
			/**
			 * Settato solo il valore di value
			 * la radio è spuntata
			 */
			[ null, 'key', null ],
			/**
			 * Settato solo instance
			 * la radio è spuntata
			 */
			[ null, null, 'key' ],
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
			[ 'key', null, '' ],
			[ null, 'key', '' ],
			[ 'key2', 'key2', '' ],
			[ 'key2', 'key2', 'key1' ],
		];
	}

	private function get_radio( $default, $value, $instance_val ) {
		$sut = $this->getInstance();
		$attr[ 'type' ] = 'radio';

		$id = 'radio_ID';
		$attr['id'] = $id;
		$attr['default'] = $default;
		$attr['value'] = $value;
		$attr['options'] = [
			'key'   => 'value',
			'key1'   => 'value1',
			'key2'   => 'value2',
			'key3'   => 'value3',
		];
		$instance[ $id ] = $instance_val;
		return $sut->render( $attr, $instance );
	}

	/**
	 * @test
	 * it_should_be_checkbox_checked
	 * @dataProvider  radio_val_provider
	 */
	public function it_should_be_radio_checked( $default, $value, $instance_val ) {
		$needle = 'checked';

		if ( '' === $instance_val || ( is_null( $default ) && is_null( $value ) && is_null( $instance_val ) ) ) {
			$this->assertStringNotContainsString( $needle, $this->get_radio( $default, $value, $instance_val ) );
		} else {
			$this->assertStringContainsString( $needle, $this->get_radio( $default, $value, $instance_val ) );
		}
	}

	/**
	 * @test
	 * it_should_be_type_select
	 */
	public function it_should_be_type_select() {

		$sut = $this->getInstance();
		$attr = [
			'type'		=> 'select',
			'id'		=> 'foo',
			'options'	=> [
				'value'	=> 'Title',
			],
		];
		$html = $sut->render( $attr );

		$this->assertStringContainsString( '<select', $html );
		$this->assertStringContainsString( 'id="foo"', $html );
		$this->assertStringContainsString( 'name="foo"', $html );
		$this->assertStringContainsString( '<option value="value"', $html );
		$this->assertStringContainsString( 'Title</option>', $html );
	}

	public function selected()
	{
		/**
		 * multiple|value|instance_val
		 */
		return  [
			[ true, null, null ],

			[ true, '', null ],
			[ true, 'key0', null ],
			[ true, ['key0', 'key1'], null ],

			[ true, 'key3', '' ],
			[ true, 'key3', 'key0' ],
			[ true, 'key3', ['key0', 'key1'] ],
			[ true, ['key2','key3'], ['key0', 'key1'] ],

			[ true, null, '' ],
			[ true, null, 'key0' ],
			[ true, null, ['key0', 'key1'] ],

			[ false, null, null ],
			[ false, '', null ],
			[ false, 'key0', null ],

			[ false, 'key3', '' ],
			[ false, 'key3', 'key0' ],

			[ false, null, '' ],
			[ false, null, 'key0' ],
//			[ false, '', ['key0', 'key1'] ], // Test da valutare
		];
	}

	/**
	 * @test
	 * @dataProvider selected
	 */
	public function it_should_be_type_select_selected( $multiple, $value, $instance_val )
	{
		$id = uniqid();
		$sut = $this->getInstance();
		$attr = [
			'type'		=> 'select',
			'id'		=> $id,
			'value'		=> $value,
			'multiple'	=> $multiple, // Se false o null non verrà stampato
			'show_option_none' => true,
			'options'	=> [
				'key0'		=> 'value0',
				'key1'		=> 'value1',
				'key2'		=> 'value2',
				'key3'		=> 'value3',
			],
		];
		$instance[ $id ] = $instance_val;
		$html = $sut->render( $attr, $instance );

		/**
		 * Il valore di instance ha la precedenza su tutto
		 * Ovvero instance è il valore da DB che sovrascrive
		 * un eventuale valore di default
		 */
		if ( isset( $instance_val ) ) {
			$value = $instance_val;
		}

		$this->assertStringContainsString( 'None</option>', $html );

		/**
		 * Multiple true
		 * selected da default o instance
		 *
		 * Multiple false
		 * selected da default o instance
		 */
		if ( $multiple ) {
			$this->assertStringContainsString( 'multiple', $html );
			if ( is_array( $value ) ) {
				foreach ( $value as $k => $item ) {
					$this->assertStringContainsString( '<option value="' . $item . '" selected="selected">', $html );
				}
			} elseif ( is_string( $value ) && ! empty( $value ) ) {
				$this->assertStringContainsString( '<option value="' . $value . '" selected="selected">', $html );
			} else {
				$this->assertStringNotContainsString( '" selected="selected">', $html );
			}

		} else {
			$this->assertStringNotContainsString( 'multiple', $html );
			if ( empty( $value ) ) {
				$this->assertStringNotContainsString( '" selected="selected">', $html );
			} else {
				$this->assertStringContainsString( '<option value="' . $value . '" selected="selected">', $html );
			}
		}
	}

	/**
	 * @test
	 */
	public function it_should_be_taxonomy_selected()
	{
		$taxonomy = 'category';
		$terms_qta = rand( 10, 20 );
		$terms = $this->factory()->term->create_many( $terms_qta, [ 'taxonomy' => $taxonomy ] );
		$posts = $this->factory()->post->create_many( 5 );

		foreach ( $posts as $key => $post )  {
			wp_set_object_terms( $post, $terms, $taxonomy );
		}

		$min = min( $terms );
		$max = max( $terms );

		$instance_val = rand( $min, $max );

		$id = uniqid();
		$sut = $this->getInstance();
		$attr = [
			'type'		=> 'taxonomy_select',
			'id'		=> $id,
//			'value'		=> $value,
//			'value'		=> '1',
//			'multiple'	=> $multiple, // Se false o null non verrà stampato
//			'multiple'	=> true, // Se false o null non verrà stampato
//			'taxonomy'	=> $taxonomy,
//			'show_option_none' => true,
		];
		$instance[ $id ] = $instance_val;
		$html = $sut->render( $attr, $instance );

		/**
		 * Multiple deve essere presente
		 */
		$this->assertStringContainsString( 'type="select"', $html );
		$this->assertStringContainsString( 'multiple', $html );
		$this->assertStringContainsString( 'value="' . $instance_val . '" selected="selected"', $html );


		/**
		 * Qui randomizzo i valori che dovranno essere select
		 * in base ai terms creati
		 */
		$instance_val = array_rand( array_flip( $terms ), rand( $min, count( $terms ) ) );
		$instance[ $id ] = $instance_val;
		$html = $sut->render( $attr, $instance );

		foreach ( $instance[ $id ] as $k => $v ) {
			$this->assertStringContainsString( 'value="' . $v . '" selected="selected"', $html );
		}
	}

    /**
     * Get fields_type output
     */
    public function get_fields_input_output( $type = 'text', $tag = 'input' ) {

        $sut = $this->getInstance();

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
        $sut = $this->getInstance();

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
