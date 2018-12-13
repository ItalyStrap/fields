<?php

namespace ItalyStrap\Fields\View;

use ItalyStrap\HTML;

/**
 * Class Input
 *
 * @package ItalyStrap\Fields\View
 */
class Input extends Abstract_View {

	/**
	 * @param ElementInterface $attr
	 *
	 * @return string
	 */
	public function render( array $attr ) {

		$this->elements = array_merge( $this->elements, $attr );

		return sprintf(
			'%s<input%s/>%s',
			$this->label(),
			HTML\get_attr( 'input', $attr ),
			$this->description()
		);
	}
}
