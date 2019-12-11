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

		if ( isset( $attr['legend'] ) ) {
			$this->set( 'legend', $attr['legend'] );
			unset( $attr['legend'] );
		}

		return sprintf(
			'<fieldset>%s%s%s</fieldset>',
			$this->legend(),
			$this->render_options( $attr ),
			$this->description()
		);
	}

	protected function render_options( array $attr ) {

		if ( ! isset( $attr['options'] ) ) {
			$attr['options'] = [];
		}

		if ( isset( $attr['show_option_none'] ) ) {
			$none = is_string( $attr['show_option_none'] ) ? $attr['show_option_none'] : __( 'None', 'italystrap' ) ;
			$attr['options'] = [ $none ] + $attr['options'];
		}

		$html = '';

		foreach ( (array) $attr['options'] as $value => $option ) {
			$new_attr = array_merge(
				$attr,
				[
					'id'		=> $attr['id'] . '_' . $value,
					'value'		=> $value,
					'checked'	=> $this->is_checked( $value, $attr['value'], $attr ),
				]
			);

			$this->set( 'label', $option );
			$this->set( 'id', $new_attr['id'] );

			unset( $new_attr['options'] );

			$html .= sprintf(
				'<p><input%s/>%s</p>',
				HTML\get_attr( 'input', $new_attr ),
				$this->label()
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
