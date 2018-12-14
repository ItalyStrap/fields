<?php
/*
Plugin Name: Fields
Description: Classes and functions for handling fields
Plugin URI: https://italystrap.com
Author: Enea Overclokk
Author URI: https://italystrap.com
Version: 1.0
License: GPL2
Text Domain: Text Domain
Domain Path: Domain Path
*/

/*

	Copyright (C) Year  Enea Overclokk  Email

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require( __DIR__ . '/vendor/autoload.php' );

if ( is_admin() ) {
	return;
}

echo '<form action="">';

function fields_example() {

	$fields = new \ItalyStrap\Fields\Fields();

	$attr = array(
		'type'			=> 'editor',
		'value'		    => 'Default value',
	);

	print $fields->render( $attr );
	d( $fields->render( $attr ) );

	$attr = array(
		'type'			=> 'textarea',
//		'default'		=> 'Default value',
	);

//	print $fields->render( $attr );
//	d( $fields->render( $attr ) );

	$attr = array(
		'type'			=> 'textarea',
		'value'		    => 'Default value',
	);

//	print $fields->render( $attr );
//	d( $fields->render( $attr ) );

	// $fields->add_type( 'closure', function () {} );
	// $fields->add_type( 'closure', function () {} );
//d( $fields->get_all_types() );
//$types = array_map( function ( $class ) {
//
//    d( mb_strpos( $class, 'Inputs' ) );
//
//    d( $class );
//        return [ $class ];
//    }, (array) $fields->get_all_types() );
//d( $types );
	// $attrs = [
	//     'id'        => 'id',
	//     'class'     => 'class',
	// ];

	// $exclude = [
	//     'class',
	// ];

	// d( $fields->concat_attrs( $attrs ) );
	// d( $fields->concat_attrs( $attrs, $exclude ) );

	// $text = array(
	// 	'id'			=> 'widget_class',
	// 	'type'			=> 'closure',
	// );

	// d( $fields->render( $text, [] ) );

//	$text = array(
//		// 'id'			=> 'widget_class',
//		'type'			=> 'not_callable',
//	);

	// d( $fields->render( $text ) );

//	$text = array(
//		// 'id'			=> 'widget_class',
//		'type'			=> 'text',
//		// 'show_on_cb'	=> false,
//		// 'show_on_cb'	=> 'false',
//	);

	// d( $fields->render( $text ) );

	$attr = array(
		'type'			=> 'text',
		'default'		=> 'Default value',
	);

//	print $fields->render( $attr );
//	d( $fields->render( $attr ) );

    $attr = [
            'type'  => 'checkbox',
            'label' => 'Label Title',
    ];

//    print $fields->render( $attr );
//    d( $fields->render( $attr ) );

    $attr = [
            'type'      => 'checkbox',
            'label'     => 'Label Title',
            'default'   => 2,
    ];

//    print $fields->render( $attr );
//    d( $fields->render( $attr ) );

    $attr = [
            'type'  => 'checkbox',
            'label' => 'Label Title',
            'id'    => 'checkbox_ID',
    ];

    $instance['checkbox_ID'] = 'on';

//    print $fields->render( $attr, $instance );
//    d( $fields->render( $attr, $instance ) );

    $attr = [
            'type'  => 'checkbox',
            'label' => 'Label Title',
            'id'    => 'checkbox_only_value',
            'value' => 'on',
    ];


//    print $fields->render( $attr );
//    d( $attr['id'], $fields->render( $attr ) );

//    $label = [
//            'label' => 'Label Title',
//    ];
//
//    print $fields->render( $label );
//    d( $fields->render( $label ) );

    $label = [
            'label' => [
                    'content'     => 'Label Title',
                    'attributes' => [
                            'class'    => 'css_class',
                    ],
            ],
    ];
//    print $fields->render( $label );
//    d( $fields->render( $label ) );

    $description = [
            'desc' => 'Description',
    ];
//    print $fields->render( $description );
//    d( $fields->render( $description ) );

    $description = [
            'desc' => [
                    'content'   => 'Description',
                    'attributes' => [
                            'id'    => 'uniqueDescID',
                            'class'    => 'css_desc_class',
                    ],
            ],
    ];
//    print $fields->render( $description );
//    d( $fields->render( $description ) );

	$text = [
		'label'			=> __( 'Widget Class', 'italystrap' ),
		'desc'			=> __( 'Enter the widget class name.', 'italystrap' ),
		'name'			=> __( 'Widget Class', 'italystrap' ),
		'id'			=> 'widget_class',
		// '_id'			=> 'widget_class',
		// '_name'			=> 'widget_class',
		'type'			=> 'text',
		'class'			=> 'widefat widget_class',
		'placeholder'	=> 'placeholder',
		'default'		=> true,
		'value'			=> 'general',
		'size'			=> '',
    ];

	// d( $fields->text( $text ) );

	$text = [
		'label'			=> __( 'Widget Class', 'italystrap' ),
		'name'			=> __( 'Widget Class', 'italystrap' ),
		'desc'			=> __( 'Enter the widget class name.', 'italystrap' ),
		'id'			=> 'widget_class',
		// '_id'			=> 'widget_class',
		// '_name'			=> 'widget_class',
		'type'			=> 'text',
		'class'			=> 'widefat widget_class',
		'placeholder'	=> 'placeholder',
		'default'		=> true,
		'value'			=> 'value',
		'size'			=> '',
    ];

	// d( $fields->render( $text, [] ) );

    echo '</form>';
}

add_action( 'wp_footer', 'fields_example' );
