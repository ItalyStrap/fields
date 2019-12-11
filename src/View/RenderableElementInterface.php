<?php

namespace ItalyStrap\Fields\View;

interface RenderableElementInterface {

	/**
	 * @param ElementInterface $attr
	 *
	 * @return string
	 */
	public function render( array $attr );
}
