<?php

namespace ItalyStrap\Fields\View;

use ItalyStrap\HTML;

/**
 * Class Input
 *
 * @package ItalyStrap\Fields\View
 */
class Select extends Abstract_View {

	/**
	 * @param array $attr
	 *
	 * @return string
	 */
	protected function maybe_render( array $attr ) {

		if ( ! isset( $attr['options'] ) ) {
			$attr['options'] = [];
		}

		$options = $attr;
		unset( $attr['options'] );

		return sprintf(
			'%s<select%s>%s</select>%s',
			$this->label(),
			HTML\get_attr( 'input', $attr ),
			$this->render_options( $options ),
			$this->description()
		);
	}

	protected function render_options( array $attr ) {

		if ( ! isset( $attr['options'] ) ) {
			$attr['options'] = [];
		}

		$html = '';
		if ( isset( $attr['show_option_none'] ) ) {
			$none = is_string( $attr['show_option_none'] ) ? $attr['show_option_none'] : __( 'None', 'italystrap' ) ;
			// $attr['options'] = array_merge( array( 'none' => $none ), $attr['options'] );
			$html .= '<option value="0"> ' . esc_html( $none ) . '</option>';
			// $html .= '<option  disabled selected> ' . esc_html( $none ) . '</option>';
		}

		foreach ( (array) $attr['options'] as $value => $option ) {
			d($attr['value'] === $value);
			$html .= sprintf(
				'<option%s>%s</option>',
				HTML\get_attr( 'option',
					[
						'value'		=> $value,
						'selected'	=> $attr['value'] === $value,
					]
				),
				esc_html( $option )
			);
		}

		return $html;
	}
}
