<?php

namespace ItalyStrap\Fields\View;

use ItalyStrap\HTML;

/**
 * Class Input
 *
 * @package ItalyStrap\Fields\View
 */
class Input {

	/**
	 * Function description
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function __construct( $element = '' ) {
	
		$this->element = $element;
	
	}

    /**
     * @param ElementInterface $element
     *
     * @return string
     */
    public function render( $element ) {
	
		$this->element = $element;
    	// $this->label( $key['name'], $key['id'] )
        // $attributes = $element->attributes();
// d( $element );
        return sprintf(
            '<input%s/>',
            HTML\get_attr( 'input', $this->element )
            // $this->attributesToString($attributes)
        );
    }
}
