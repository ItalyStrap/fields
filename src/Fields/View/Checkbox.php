<?php

namespace ItalyStrap\Fields\View;

use ItalyStrap\HTML;

/**
 * Class Input
 *
 * @package ItalyStrap\Fields\View
 */
class Checkbox extends Abstract_View {

	/**
	 * @param array $attr
	 *
	 * @return string
	 */
	protected function maybe_render( array $attr ) {

		if ( ! empty( $attr['value'] ) ) {
//			$attr['checked'] = 'checked';
			$attr['checked'] = true;
		}

		return sprintf(
			'<input%s/>%s%s',
			HTML\get_attr( 'input', $attr ),
			$this->label(),
			$this->description()
		);
	}
}
