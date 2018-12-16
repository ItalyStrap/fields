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

		$attr['type'] = 'select';

		if ( isset( $attr['show_option_none'] ) ) {
			$none = is_string( $attr['show_option_none'] ) ? $attr['show_option_none'] : __( 'None', 'italystrap' ) ;
			// $attr['options'] = array_merge( array( 'none' => $none ), $attr['options'] );
//			$html .= '<option value="0"> ' . esc_html( $none ) . '</option>';
			// $html .= '<option  disabled selected> ' . esc_html( $none ) . '</option>';
			$attr['options'] = [ $none ] + $attr['options'];
		}

		if ( strpos( $attr['type'], 'multiple' ) !== false ) {
			$count = count( $attr['options'] );
			$attr['size'] = isset( $attr['size'] )
				? $attr['size']
				: $count >= 1 && $count <= 6 ? $count : 6;
			$attr['multiple'] = true;
		}

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

		$html = '';

		foreach ( (array) $attr['options'] as $value => $option ) {

			$html .= sprintf(
				'<option%s>%s</option>',
				HTML\get_attr( 'option',
					[
						'value'		=> $value,
						'selected'	=> $this->is_selected( $value, $attr['value'], $attr ),
					]
				),
				esc_html( $option )
			);
		}

		return $html;
	}

	/**
	 * @param $needle
	 * @param $haystack
	 * @param $attr
	 * @return bool|string
	 */
	/**
	 * @param int|string       $needle
	 * @param int|string|array $haystack
	 * @param array            $attr
	 *
	 * @return bool|string
	 */
	protected function is_selected( $needle, $haystack, array $attr ) {
//		codecept_debug( $needle );
//		codecept_debug( $haystack );

//		d( $needle, $haystack );

		if (
//			$this->is_multiple( $attr )
//			&&
			is_array( $haystack )
			&& in_array( $needle, $haystack, true )
//			|| is_string( $haystack ) && $needle == $haystack // Problema con gli int
			|| $needle == $haystack // Problema con gli int
		) {
			return 'selected';
		}

		return false;
	}

	protected function is_multiple(array $attr)
	{
		return isset( $attr['multiple'] ) || isset( $attr['attributes']['multiple'] );
	}
}
