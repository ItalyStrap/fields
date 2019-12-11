<?php

namespace ItalyStrap\Fields\View;

use ItalyStrap\HTML;

/**
 * Class Input
 *
 * @package ItalyStrap\Fields\View
 */
class Group extends Abstract_View {

	/**
	 * @param array $attr
	 *
	 * @return string
	 */
	protected function maybe_render( array $attr ) {

		return sprintf(
			'%s<input%s/>%s',
			$this->label(),
			HTML\get_attr( 'input', $attr ),
			$this->description()
		);

		foreach ( $key['group_field'] as $field ) {
			$this->set_attr_id_name( $field );

			/**
			 * Prefix method
			 *
			 * @var string
			 */
			$field_method = '' . str_replace( '-', '_', $field['type'] );

			$attr['type'] = $field['type'];

			// $out .= method_exists( $this, $field_method ) ? $this->$field_method( $field ) : $this->text( $field );
			$out .= method_exists( $this, $field_method ) ? $this->$field_method( $field ) : $this->text( $field );
		}

		return sprintf(
			'%1$s %2$s',
			$this->label( $key['name'], $key['_id'] ),
			$out
		);
	}
}
