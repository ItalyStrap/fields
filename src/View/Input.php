<?php
declare(strict_types=1);

namespace ItalyStrap\Fields\View;

use ItalyStrap\HTML;

/**
 * Class Input
 *
 * @package ItalyStrap\Fields\View
 */
class Input extends AbstractView {

	/**
	 * @param array $attr
	 *
	 * @return string
	 */
	protected function maybeRender( array $attr ) {

		return \sprintf(
			'%s<input%s/>%s',
			$this->label(),
			HTML\get_attr( 'input', $attr ),
			$this->description()
		);
	}
}
