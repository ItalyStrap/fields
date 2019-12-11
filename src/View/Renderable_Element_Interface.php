<?php

namespace ItalyStrap\Fields\View;

interface Renderable_Element_Interface {

	/**
	 * @param ElementInterface $attr
	 *
	 * @return string
	 */
	public function render( array $attr );
}
