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

//		'size="6" multiple> '

		$options = $attr;
		unset( $attr['options'] );
		unset( $attr['value'] );
		unset( $attr['show_option_none'] );

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
//			$html .= '<option value="0"> ' . esc_html( $none ) . '</option>';
			// $html .= '<option  disabled selected> ' . esc_html( $none ) . '</option>';
			$attr['options'] = [ $none ] + $attr['options'];
		}

		foreach ( (array) $attr['options'] as $value => $option ) {

//			if ( in_array( $value, (array) $attr['value'], true ) ) {
//				$out = ' selected="selected" ';
//			}

			d( $value, $attr['value'], $this->is_selected( $value, $attr['value'], $attr ) );

			$html .= sprintf(
				'<option%s>%s</option>',
				HTML\get_attr( 'option',
					[
						'value'		=> $value,
//						'selected'	=> $attr['value'] === $value ? 'selected' : false,
						'selected'	=> $this->is_selected( $value, $attr['value'], $attr ),
					]
				),
				esc_html( $option )
			);
		}

		return $html;
	}

	protected function is_selected( $needle, $haystack, $attr ) {

		if (
			$this->is_multiple( $attr )
			&& is_array( $haystack )
			&& in_array( $needle, $haystack, true )
		) {
			return 'selected';
		} elseif ( is_string( $haystack ) && $needle === $haystack ) {
			return 'selected';
		}

//		if (
//			is_array( $haystack ) && in_array( $needle, $haystack, true )
//			|| is_string( $haystack ) && $needle === $haystack
//		) {
//			return 'selected';
//		}

		return false;
	}

	protected function is_multiple(array $attr)
	{
		return isset( $attr['multiple'] ) || isset( $attr['attributes']['multiple'] );
	}
}
