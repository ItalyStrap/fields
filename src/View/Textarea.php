<?php

namespace ItalyStrap\Fields\View;

use ItalyStrap\HTML;

/**
 * Class Input
 *
 * @package ItalyStrap\Fields\View
 */
class Textarea extends Abstract_View {

	/**
	 * @param array $attr
	 *
	 * @return string
	 */
	protected function maybe_render( array $attr ) {

		$default = [
			'cols'  => '60',
			'rows'  => '10',
		];

		$attr = array_merge( $default, $attr );

		$value = $attr['value'];
		unset( $attr['value'] );

		return sprintf(
			'%s<textarea%s/>%s</textarea>%s',
			$this->label(),
			HTML\get_attr( 'input', $attr ),
			esc_textarea( $value ),
			$this->description()
		);
	}
}
