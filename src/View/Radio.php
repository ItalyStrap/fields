<?php
declare(strict_types=1);

namespace ItalyStrap\Fields\View;

use ItalyStrap\HTML;

/**
 * Class Radio
 *
 * @package ItalyStrap\Fields\View
 */
class Radio extends AbstractView {

	/**
	 * @param array $attr
	 *
	 * @return string
	 */
	protected function maybeRender( array $attr ) {

		if ( isset( $attr['legend'] ) ) {
			$this->set( 'legend', $attr['legend'] );
			unset( $attr['legend'] );
		}

		return \sprintf(
			'<fieldset>%s%s%s</fieldset>',
			$this->legend(),
			$this->renderOptions( $attr ),
			$this->description()
		);
	}

	protected function renderOptions( array $attr ) {

		if ( ! isset( $attr['options'] ) ) {
			$attr['options'] = [];
		}

		if ( isset( $attr['show_option_none'] ) ) {
			$none = \is_string( $attr['show_option_none'] ) ? $attr['show_option_none'] : __( 'None', 'italystrap' ) ;
			$attr['options'] = [ $none ] + $attr['options'];
		}

		$html = '';

		foreach ( (array) $attr['options'] as $value => $option ) {
			$new_attr = \array_merge(
				$attr,
				[
					'id'		=> $attr['id'] . '_' . $value,
					'value'		=> $value,
					'checked'	=> $this->isChecked( $value, $attr['value'], $attr ),
				]
			);

			$this->set( 'label', $option );
			$this->set( 'id', $new_attr['id'] );

			unset( $new_attr['options'] );

			$html .= \sprintf(
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
	protected function isChecked( $needle, $haystack, array $attr ) {

		return $needle == $haystack;

//		if ( $needle == $haystack ) {
//			return 'checked';
//		}
//
//		return false;
	}
}
