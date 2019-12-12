<?php
declare(strict_types=1);

namespace ItalyStrap\Fields\View;

interface RenderableElementInterface {

	/**
	 * @param array $attr
	 *
	 * @return string
	 */
	public function render( array $attr );

	/**
	 * @param string $key
	 * @param string $value
	 * @return $this
	 */
	public function with( $key, $value );
}
