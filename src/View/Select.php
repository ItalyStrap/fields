<?php
declare(strict_types=1);

namespace ItalyStrap\Fields\View;

use ItalyStrap\HTML;

/**
 * Class Input
 *
 * @package ItalyStrap\Fields\View
 */
class Select extends AbstractView {

	/**
	 * @param array $attr
	 *
	 * @return string
	 */
	protected function maybeRender( array $attr ) {

		if ( ! isset( $attr['options'] ) ) {
			$attr['options'] = [];
		}

		if ( ! isset( $attr['type'] ) ) {
			$attr['type'] = '';
		}

		if ( empty( $attr['name'] ) ) {
			throw new \InvalidArgumentException( '"name" value must not be empty' );
		}

		/**
		 * @todo Check provvisorio
		 */
		if ( \strpos( $attr['type'], 'multiple' ) !== false || \strpos( $attr['type'], 'taxonomy' ) !== false ) {
			$count = \count( $attr['options'] );
			$attr['size'] = isset( $attr['size'] ) ? $attr['size'] : ( $count >= 1 && $count <= 6 ? $count : 6 );
			$attr['multiple'] = true;

			$attr['name'] .= '[]';
		}

		$attr['type'] = 'select';

		$options = $attr;
		unset( $attr['options'] );
		unset( $attr['value'] );
		unset( $attr['show_option_none'] );

		return \sprintf(
			'%s<select%s>%s</select>%s',
			$this->label(),
			HTML\get_attr( 'input', $attr ),
			$this->renderOptions( $options ),
			$this->description()
		);
	}

	protected function renderOptions( array $attr ) {

		if ( isset( $attr['show_option_none'] ) ) {
			$none = \is_string( $attr['show_option_none'] ) ? $attr['show_option_none'] : __( 'None', 'italystrap' ) ;
			$attr['options'] = [ $none ] + $attr['options'];
		}

		$html = '';

		foreach ( (array) $attr['options'] as $value => $option ) {
			$html .= \sprintf(
				'<option%s>%s</option>',
				HTML\get_attr(
					'option',
					[
						'value'		=> $value,
						'selected'	=> $this->isSelected( $value, $attr['value'], $attr ),
					 ]
				),
				\esc_html( $option )
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
	protected function isSelected( $needle, $haystack, array $attr ) {

		if (
			\is_array( $haystack )
			&& \in_array( $needle, $haystack, true )
			|| $needle == $haystack
		) {
			return 'selected';
		}

		return false;
	}

	protected function isMultiple( array $attr) {
		return isset( $attr['multiple'] ) || isset( $attr['attributes']['multiple'] );
	}

	/**
	 * Create the Field Select with Options Group
	 *
	 * @TODO
	 *
	 * @access public
	 * @param  array $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Select with Options Group
	 */
	private function selectGroup( array $key, $out = '' ) {

//		$out .= $this->label( $key['name'], $key['_id'] );
		$out .= $this->label();

		$out .= '<select id="' . \esc_attr( $key['_id'] ) . '" name="' . \esc_attr( $key['_name'] ) . '" ';

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . \esc_attr( $key['class'] ) . '" ';
		}

		$out .= '> ';

		$selected = isset( $key['value'] ) ? $key['value'] : $key['default'];

		if ( isset( $key['show_option_none'] ) ) {
			$none = ( \is_string( $key['show_option_none'] ) ) ? $key['show_option_none'] : __( 'None', 'italystrap' ) ;
			// $key['options'] = array_merge( array( 'none' => $none ),$key['options'] );
			$out .= '<option value="0"> ' . \esc_html( $none ) . '</option>';
			// $out .= '<option  disabled selected> ' . esc_html( $none ) . '</option>';
		}

		foreach ( (array) $key['options'] as $group => $options ) {
			$out .= '<optgroup label="' . $group . '">';

			foreach ( $options as $field => $option ) {
				$out .= '<option value="' . \esc_attr( $field ) . '" ';

				if ( \esc_attr( $selected ) === $field ) {
					$out .= ' selected="selected" ';
				}

				$out .= '> ' . \esc_html( $option ) . '</option>';
			}

			$out .= '</optgroup>';
		}

		$out .= '</select>';

		if ( isset( $key['desc'] ) ) {
//			$out .= $this->description( $key['desc'] );
			$out .= $this->description();
		}

		return $out;
	}
}
