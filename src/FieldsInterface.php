<?php
/**
 * Interface for Fields
 *
 * This is the interface for fields class
 */
declare(strict_types=1);

namespace ItalyStrap\Fields;

/**
 * Interface FieldsInterface
 * @package ItalyStrap\Fields
 */
interface FieldsInterface {

	/**
	 * Render the field type
	 *
	 * @param  array $attr     The array with field arguments.
	 * @param  array $instance This is the $instance variable of widget
	 *                         or the options variable of the plugin.
	 *
	 * @return string          Return the html field
	 */
	public function render( array $attr, array $instance = [] );
}
