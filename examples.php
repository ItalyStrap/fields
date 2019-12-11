<?php

//if ( is_admin() ) {
//	return;
//}

//wp_enqueue_media();

function fields_example() {

//	d( _wp_add_global_attributes( [] ) );
//
//	$array_ = [
//		'id' => rand(),
//	];
//	$allowed = [
//		'id',
//		'class',
//	];
//
//	d( $array_ );
//	d( $allowed );
//	d( array_flip( $allowed ) );
//	d( array_intersect_key( array_flip( $allowed ), $array_  ) );

//	_wp_add_global_attributes( $value )

	echo '<form action="" style="padding-bottom: 5rem;margin-left: 20rem;">';

	$fields = new \ItalyStrap\Fields\Fields();

	$without_container = [
		'type'	=> 'text',
        'label' => 'Without Container element',
		'container'	=> [
			'tag'	=> null,
		],
    ];

    echo $fields->render( $without_container );

	$with_container = [
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

    echo $fields->render( $with_container );

	$with_label = [
		'type'	=> 'text',
        'label' => 'With Label Title',
    ];

    echo $fields->render( $with_label );

	$with_label = [
		'type'	=> 'text',
		'label' => [
			'content'     => 'With Label Title and attr',
			'attributes' => [
				'class'    => 'css_class',
			],
		],
	];
    echo $fields->render( $with_label );

	$with_description = [
		'type'	=> 'text',
		'desc' => 'With Description',
	];
    echo $fields->render( $with_description );

	$with_description = [
		'type'	=> 'text',
		'desc' => [
			'content'   => 'With Description and attr',
			'attributes' => [
				'id'    => 'uniqueDescID',
				'class'    => 'css_desc_class',
			],
		],
	];
    echo $fields->render( $with_description );

	$attr = array(
		'type'			=> 'text',
		'label' 		=> 'With Default text',
		'value'			=> 'With Default text',
	);

	echo $fields->render( $attr );

	$attr = array(
		'type'			=> 'text',
		'label'			=> 'With placeholder',
		'placeholder'	=> 'With placeholder',
	);

	echo $fields->render( $attr );


	$attr = array(
		'type'			=> 'text',
		'show_on_cb'	=> false, // bool|string|callback
		// 'show_on_cb'	=> 'false',
	);

	echo $fields->render( $attr );

	$attr = array(
		'type'			=> 'textarea',
		'label' 		=> 'Textarea',
	);

	print $fields->render( $attr );

	$attr = array(
		'type'			=> 'textarea',
		'label' 		=> 'Textarea With Default text',
		'value'		    => 'Default value',
	);

	echo $fields->render( $attr );

	$attr = array(
		'type'			=> 'editor',
		'label' 		=> 'WP Editor',
		'value'		    => 'Default value',
	);

	print $fields->render( $attr );

	$attr = [
		'type'  => 'checkbox',
		'label' => 'Checkbox label with random ID',
	];

    echo $fields->render( $attr );

	$attr = [
		'type'  => 'checkbox',
		'label' => 'Checkbox checked',
		'id'    => 'checkbox_ID',
	];

	$instance['checkbox_ID'] = 'on';

    echo $fields->render( $attr, $instance );

	$attr = [
		'type'  => 'checkbox',
		'label' => 'Checkbox checked by default',
		'id'    => 'checkbox_only_value',
		'value' => 'on',
	];

    echo $fields->render( $attr );

	/**
	 * Multicheckbox
	 */
	$attr = [
		'type'  => 'checkbox',
		'label' => 'Checkbox checked by default',
		'id'    => 'checkbox_with_options',
		'value' => 'key1',
		'options'		=> [
			'key'   => 'value',
			'key1'   => 'value1',
			'key2'   => 'value2',
			'key3'   => 'value3',
		],
	];

    echo $fields->render( $attr );

    echo 'Multicheck width multivalue';
	/**
	 * Multicheckbox with multivalue
	 */
	$attr = [
		'type'  => 'checkbox',
		'label' => 'Checkbox checked by default',
		'id'    => 'checkbox_with_options',
		'value' => ['key1', 'key3'],
		'options'		=> [
			'key'   => 'value',
			'key1'   => 'value1',
			'key2'   => 'value2',
			'key3'   => 'value3',
		],
	];

    echo $fields->render( $attr );


	$attr = array(
		'type'			=> 'radio',
		'id'            => 'radio',
		'legend'		=> 'Radio with no options',
		// "No options available" will be displayed
	);

	echo $fields->render( $attr );

	$attr = array(
		'type'			=> 'radio',
		'legend'		=> 'Radio with options',
		'id'            => 'radio1',
		'value'			=> 'key2', // checked by default
		'options'		=> [
			'key'   => 'value',
			'key1'   => 'value1',
			'key2'   => 'value2',
			'key3'   => 'value3',
		],
	);

	echo $fields->render( $attr );

	$attr = array(
		'type'			=> 'radio',
		'legend'		=> [
			'content'	=> 'Radio with legend hidden',
			'attributes'	=> [
				'class'	=> 'screen-reader-text',
			],
		],
		'show_option_none'	=> true,
		'id'            => 'radio2',
		'value'			=> 'key2', // checked by default
		'options'		=> [
			'key'   => 'value',
			'key1'   => 'value1',
			'key2'   => 'value2',
			'key3'   => 'value3',
		],
	);

	echo $fields->render( $attr );


	$attr = array(
		'type'			=> 'select',
		'label'			=> 'Select',
		'id'            => 'selectID',
		'value'         => 'key1',
		'options'		=> [
			'key'   => 'value',
			'key1'   => 'value',
			'key2'   => 'value',
			'key3'   => 'value',
		],
	);

	echo $fields->render( $attr );

	$attr = array(
		'type'			=> 'select',
		'label'			=> 'Select with option None',
		'id'            => 'selectID',
		'value'         => 'key1',
		'show_option_none' => true,
		'options'		=> [
			'key'   => 'value',
			'key1'   => 'value',
			'key2'   => 'value',
			'key3'   => 'value',
		],
	);

	echo $fields->render( $attr );

	$attr = array(
		'type'			=> 'select',
		'label'			=> '\Select with value from database',
		'id'            => 'select',
		'show_option_none' => true,
		'options'		=> [
			'key'   => 'value',
			'key1'   => 'value',
			'key2'   => 'value',
			'key3'   => 'value',
		],
	);

	$instance['select'] = 'key2';

	echo $fields->render( $attr, $instance );

	$attr = array(
		'type'			=> 'select',
		'label'			=> 'Multiple select',
		'id'            => 'selectID',
		'value'         => 'key1',
		'size'          => '6',
		'multiple'      => true,
		'show_option_none' => true,
		'options'		=> [
			'key'   => 'value',
			'key1'   => 'value',
			'key2'   => 'value',
			'key3'   => 'value',
		],
	);

	$instance['selectID'] = ['key1', 'key3'];

	echo $fields->render( $attr, $instance );

	$attr = array(
		'type'			=> 'multiple_select',
		'label'			=> 'Multiple select',
		'id'            => 'jnjkk',
		'value'         => ['key','key1'],
//		'size'          => '6',  // default is 6 or the number of the options if are less then 6
//		'multiple'      => true, // No need of this settings
		'show_option_none' => true,
		'options'		=> [
			'key'   => 'value',
			'key1'   => 'value',
			'key2'   => 'value',
			'key3'   => 'value',
		],
	);

	echo $fields->render( $attr );

	$attr = array(
		'type'			=> 'taxonomy_select',
		'label'			=> 'Taxonomy select',
		'id'            => 'taxonomyID',
		'value'         => 1,
		'taxonomy'		=> 'category',
		'show_option_none' => true,
	);

	echo $fields->render( $attr );

	$attr = array(
		'type'			=> 'media',
		'label'			=> 'Media',
		'id'            => 'mediaID',
//		'value'         => 1,
		'show_option_none' => true,
	);

	echo $fields->render( $attr );

	echo '</form>';
}

add_action( 'wp_footer', 'fields_example' );
add_action( 'admin_footer', 'fields_example' );
