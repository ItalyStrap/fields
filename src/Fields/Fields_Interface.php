<?php
/**
 * Interface for Fields
 *
 * This is the interface for fields class
 *
 * @link [URL]
 * @since 2.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Fields;

interface Fields_Interface {

	/**
	 * Render the field type
	 *
	 * @param  array $attr     The array with field arguments.
	 * @param  array $instance This is the $instance variable of widget
	 *                         or the options variable of the plugin.
	 *
	 * @return string           Return the html field
	 */
	public function render( array $attr, array $instance = [] );
}
