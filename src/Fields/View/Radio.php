<?php

namespace ItalyStrap\Fields\View;

use ItalyStrap\HTML;

/**
 * Class Radio
 *
 * @package ItalyStrap\Fields\View
 */
class Radio extends Abstract_View {

	/**
	 * @param array $attr
	 *
	 * @return string
	 */
	protected function maybe_render( array $attr ) {

		if ( ! isset( $attr['options'] ) ) {
			$attr['options'] = [ __( 'No options available', 'italystrap' ) ];
		}

//		d( $this->render_options( $attr ) );

//		unset( $attr['options'] );

//		return sprintf(
//			'<input%s/>%s%s',
//			HTML\get_attr( 'input', $attr ),
//			$this->label(),
//			$this->description()
//		);

		return sprintf(
			'%s%s%s',
			$this->label(),
			$this->render_options( $attr ),
			$this->description()
		);
	}

	protected function render_options( array $attr ) {

		$html = '';

		foreach ( (array) $attr['options'] as $value => $option ) {

			$label = sprintf(
				'<label for="%s">%s</label>',
				$attr['id'] . '_' . $value,
				esc_html( $option )
			);

			$html .= sprintf(
				'<div><input%s>%s</div>',
				HTML\get_attr( 'option',
					[
						'type'		=> $attr['type'],
						'id'		=> $attr['id'] . '_' . $value,
						'name'		=> $option,
						'value'		=> $value,
						'checked'	=> $this->is_checked( $value, $attr['value'], $attr ),
					]
				),
				$label
			);
		}

		return $html;
	}

	/**
	 * @param int|string       $needle
	 * @param int|string|array $haystack
	 * @param array            $attr
	 *
	 * @return bool|string
	 */
	protected function is_checked( $needle, $haystack, array $attr ) {

		return $needle == $haystack;

//		if ( $needle == $haystack ) {
//			return 'checked';
//		}
//
//		return false;
	}
}
