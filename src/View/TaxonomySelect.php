<?php
declare(strict_types=1);

namespace ItalyStrap\Fields\View;

use ItalyStrap\HTML;

/**
 * Class Input
 *
 * @package ItalyStrap\Fields\View
 */
class TaxonomySelect extends Select {

	protected function renderOptions( array $attr ) {

		if ( ! isset( $attr['taxonomy'] ) ) {
			$attr['taxonomy'] = 'category';
		}

		$tax_arrays = \get_terms( $attr['taxonomy'] );

		if ( \is_wp_error( $tax_arrays ) ) {
			throw new \RuntimeException( sprintf(
				__( 'The given taxonomy %s not found. %s', 'italystrap' ),
				$attr['taxonomy'],
				$tax_arrays->get_error_message()
			) );
		}

		$attr['options'] = [];
		foreach ( (array) $tax_arrays as $tax_obj ) {
			if ( ! \is_object( $tax_obj ) ) {
				continue;
			}

			$attr['options'][ $tax_obj->term_id ] = $tax_obj->name;
		}

		return parent::renderOptions( $attr );
	}
}
