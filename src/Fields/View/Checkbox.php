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
	 * @param ElementInterface $attr
	 *
	 * @return string
	 */
	public function render( array $attr ) {

		$this->elements = array_merge( $this->elements, $attr );

        if ( isset( $attr['value']  ) && ! empty( $attr['value'] ) ) {
			$attr['checked'] = 'checked';
		}

		return sprintf(
			'<input%s/>%s%s',
			HTML\get_attr( 'input', $attr ),
			$this->label(),
			$this->description()
		);
	}
}
