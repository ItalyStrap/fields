<?php
declare(strict_types=1);

namespace ItalyStrap\Fields\View;

use ItalyStrap\HTML;

/**
 * Class Input
 *
 * @package ItalyStrap\Fields\View
 */
class Editor extends AbstractView {

	/**
	 * @param array $attr
	 *
	 * @return string
	 */
	protected function maybeRender( array $attr ) {

		$settings = [
			'textarea_name' => $attr['name'],
			// 'media_buttons' => false,
			// 'textarea_rows' => 5,
			// 'editor_css'    => '<style>#wp-italy_cookie_choices_text-wrap{max-width:520px}</style>',
			'teeny' => true,
		];

		/**
		 * @see do_shortcode()
		 */
		\preg_match( '@\[([^<>&/\[\]\x00-\x20=]++)@', $attr['id'], $matches );

		\ob_start();

		\wp_editor(
			$attr['value'], // Content
			$matches[1] ?? $attr['id'], //$attr['id'],	// Editor ID
			$settings		// Settings
		);

		$output = \ob_get_clean();

		return \sprintf(
			'%s%s%s',
			$this->label(),
			$output,
			$this->description()
		);
	}
}
