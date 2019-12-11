<?php
declare(strict_types=1);

namespace ItalyStrap\Fields\View;

use ItalyStrap\HTML;

/**
 * Class Input
 *
 * @package ItalyStrap\Fields\View
 */
class Textarea extends AbstractView {

	/**
	 * @param array $attr
	 *
	 * @return string
	 */
	protected function maybeRender( array $attr ) {

		$default = [
			'cols'  => '60',
			'rows'  => '10',
		];

		$attr = \array_merge( $default, $attr );

		$value = $attr['value'];
		unset( $attr['value'] );

		return \sprintf(
			'%s<textarea%s/>%s</textarea>%s',
			$this->label(),
			HTML\get_attr( 'input', $attr ),
			\esc_textarea( $value ),
			$this->description()
		);
	}
}
