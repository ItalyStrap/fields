<?php
declare(strict_types=1);

namespace ItalyStrap\Fields\View;

use ItalyStrap\HTML;

/**
 * Class Input
 *
 * @package ItalyStrap\Fields\View
 */
class SelectGroup extends AbstractView {

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

//		$out .= $this->label( $key['name'], $key['_id'] );
//
//		$out .= '<select id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" ';
//
//		if ( isset( $key['class'] ) ) {
//			$out .= 'class="' . esc_attr( $key['class'] ) . '" ';
//		}
//
//		$out .= '> ';
//
//		$selected = isset( $key['value'] ) ? $key['value'] : $key['default'];
//
//		if ( isset( $key['show_option_none'] ) ) {
//			$none = ( is_string( $key['show_option_none'] ) ) ? $key['show_option_none'] : __( 'None', 'italystrap' ) ;
//			// $key['options'] = array_merge( array( 'none' => $none ),$key['options'] );
//			$out .= '<option value="0"> ' . esc_html( $none ) . '</option>';
//			// $out .= '<option  disabled selected> ' . esc_html( $none ) . '</option>';
//		}
//
//		foreach ( (array) $key['options'] as $group => $options ) {
//			$out .= '<optgroup label="' . $group . '">';
//
//			foreach ( $options as $field => $option ) {
//				$out .= '<option value="' . esc_attr( $field ) . '" ';
//
//				if ( esc_attr( $selected ) === $field ) {
//					$out .= ' selected="selected" ';
//				}
//
//				$out .= '> ' . esc_html( $option ) . '</option>';
//			}
//
//			$out .= '</optgroup>';
//		}
//
//		$out .= '</select>';
//
//		if ( isset( $key['desc'] ) ) {
//			$out .= $this->description( $key['desc'] );
//		}
//
//		return $out;
//
//		return sprintf(
//			'%1$s %2$s',
//			$this->label( $key['name'], $key['_id'] ),
//			$out
//		);
	}
}
