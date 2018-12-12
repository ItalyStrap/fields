<?php

namespace ItalyStrap\Fields\View;

use ItalyStrap\HTML;

/**
 * Class Input
 *
 * @package ItalyStrap\Fields\View
 */
class Checkbox extends Abstract_View {

    /**
     * @param ElementInterface $attr
     *
     * @return string
     */
    public function render( array $attr ) {

        $this->elements = array_merge( $this->elements, $attr );

        return sprintf(
            '<input%s/>%s%s',
            HTML\get_attr( 'input', $attr ),
            $this->label(),
            $this->description()
        );
//
//        $out .= ' <input type="checkbox" ';
//
//        if ( isset( $key['class'] ) ) {
//            $out .= 'class="' . esc_attr( $key['class'] ) . '" ';
//        }

//        $out .= 'id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" value="1" ';

        // if ( ( isset( $key['value'] ) && '1' === $key['value'] ) || ( ! isset( $key['value'] ) && 1 === $key['default'] ) ) {
        // 	$out .= ' checked="checked" ';
        // }

//        if ( ( ! isset( $key['value'] ) && ! empty( $key['default'] ) ) || ( isset( $key['value']  ) && ! empty( $key['value'] ) ) ) {
//            $out .= ' checked="checked" ';
//        }

        /**
         * Da vedere se utilizzabile per fare il controllo sulle checkbox.
         * if ( isset( $key['value'] ) && 'true' === $key['value'] ) {
         * 	$key['value'] = true;
         * 	} else $key['value'] = false;
         *
         * $out .= checked( $key['value'], true );
         */

//        $out .= ' /> ';
//
//        $out .= $this->label( $key['name'], $key['_id'], false );
//
//        if ( isset( $key['desc'] ) ) {
//            $out .= $this->description( $key['desc'] );
//        }
//
//        return $out;
    }
}
