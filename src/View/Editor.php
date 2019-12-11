<?php

namespace ItalyStrap\Fields\View;

use ItalyStrap\HTML;

/**
 * Class Input
 *
 * @package ItalyStrap\Fields\View
 */
class Editor extends Abstract_View {

	/**
	 * @param array $attr
	 *
	 * @return string
	 */
	protected function maybe_render( array $attr ) {

		$settings = [
			'textarea_name' => $attr['name'],
			// 'media_buttons' => false,
			// 'textarea_rows' => 5,
			// 'editor_css'    => '<style>#wp-italy_cookie_choices_text-wrap{max-width:520px}</style>',
			'teeny' => true,
		];

		ob_start();

		wp_editor(
			$attr['value'], // Content
			$attr['id'],	// Editor ID
			$settings		// Settings
		);

		$output = ob_get_clean();

		return sprintf(
			'%s%s%s',
			$this->label(),
			$output,
			$this->description()
		);
	}
}
