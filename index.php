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

function fields_example() {

	$fields = new \ItalyStrap\Fields\Fields();

	// $attrs = [
	//     'id'        => 'id',
	//     'class'     => 'class',
	// ];

	// $exclude = [
	//     'class',
	// ];

	// d( $fields->concat_attrs( $attrs ) );
	// d( $fields->concat_attrs( $attrs, $exclude ) );

	$text = array(
		'name'			=> __( 'Widget Class', 'italystrap' ),
		'desc'			=> __( 'Enter the widget class name.', 'italystrap' ),
		'id'			=> 'widget_class',
		'_id'			=> 'widget_class',
		'_name'			=> 'widget_class',
		'type'			=> 'text',
		'class'			=> 'widefat widget_class',
		'placeholder'	=> 'widefat widget_class',
		'default'		=> true,
		'value'			=> 'general',
		'size'			=> '',
	);

	d( $fields->text( $text ) );

	$text = array(
		'name'			=> __( 'Widget Class', 'italystrap' ),
		'desc'			=> __( 'Enter the widget class name.', 'italystrap' ),
		'id'			=> 'widget_class',
		// '_id'			=> 'widget_class',
		// '_name'			=> 'widget_class',
		'type'			=> 'text',
		'class'			=> 'widefat widget_class',
		'placeholder'	=> 'widefat widget_class',
		'default'		=> true,
		'value'			=> 'general',
		'size'			=> '',
	);

	d( $fields->get_field_type( $text, [] ) );
}

add_action( 'wp_footer', 'fields_example' );
