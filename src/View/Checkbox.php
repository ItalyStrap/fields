<?php
declare(strict_types=1);

namespace ItalyStrap\Fields\View;

use ItalyStrap\HTML;

/**
 * Class Input
 *
 * @package ItalyStrap\Fields\View
 */
class Checkbox extends AbstractView {

	/**
	 * @param array $attr
	 *
	 * @return string
	 */
	protected function maybeRender( array $attr ) {

		$checkboxes = $this->renderOptions( $attr );

		if ( $checkboxes ) {
			return \sprintf(
				'%s%s',
				$checkboxes,
				$this->description()
			);
		}

		if ( ! empty( $attr['value'] ) ) {
			$attr['checked'] = true;
		}

		/**
		 * In tests I see that if only value as array is passed make sure
		 * it is converted to string before inject to the get_attr()
		 */
		$attr['value'] = \is_array( $attr['value'] ) ? $attr['value'][0] : $attr['value'];

		unset( $attr['options'] );

		return sprintf(
			'<input%s/>%s%s',
			HTML\get_attr( 'input', $attr ),
			$this->label(),
			$this->description()
		);
	}

	protected function renderOptions( array $attr ) {

		if ( ! isset( $attr['options'] ) ) {
			$attr['options'] = [];
		}

		$attr['name'] = $attr['name'] . '[]';

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

		if (
			\is_array( $haystack )
			&& \in_array( $needle, $haystack, true )
			|| $needle == $haystack
		) {
			return 'checked';
		}

		return false;
	}
}
