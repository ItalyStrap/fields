<?php
/**
 * Fields API: Fields Class
 *
 * This is similar to mine but only for PHP 7 >=
 * https://github.com/Chrico/wp-fields
 *
 * Accessible form:
 * https://webaim.org/techniques/forms/controls
 *
 * @version 2.0.0
 * @package ItalyStrap
 */

namespace ItalyStrap\Fields;

use ItalyStrap\HTML;

/**
 * Class for make field type
 */
class Fields implements Fields_Interface {

	private $field_container = '';

	/**
	 * Render the field type
	 *
	 * @param  array $attr     The array with field arguments.
	 * @param  array $instance This is the $instance variable of widget
	 *                         or the options variable of the plugin.
	 *                         Il funzionamento è semplice:
	 *                         $instance[ ID ] mi restituisce il valore preso dal DB
	 *                         Che può essere '' o valorizzato
	 *
	 * @return string           Return the html field
	 */
	public function render( array $attr, array $instance = [] ) {

		/**
		 * If field is requesting to be conditionally shown
		 */
		if ( ! $this->should_show( $attr ) ) {
			return '';
		}

//		$deprecated = [
//			'class-p',
//        ];

//		foreach ( $deprecated as $val ) {
//			if ( isset( $attr[ $val ] ) ) {
//				_deprecated_argument(
//					__FUNCTION__,
//					'1.0',
//					sprintf(
//						'"$s" attribute is deprecated.',
//                        $val
//                    )
//				);
//			}
//        }

		/**
		 * Questo lo setto con il default perché
		 * i widget per esempio settano il name con [id]
		 * così non devo fare nessun check e durante il merge
		 * verrà sovrascritto.
		 *
		 * Nei setting ID e name sono così:
		 * id="italystrap_settings[show-ids]"
		 * name="italystrap_settings[show-ids]"
		 *
		 * Nei widget così;
		 * id="widget-italystrap-posts-6-add_permalink_wrapper"
		 * name="widget-italystrap-posts[6][add_permalink_wrapper]"
		 *
		 * L'attributo for delle label è sempre associato all'ID
		 * della input.
		 */
		$defaul_ID = \uniqid();
		$default = [
			'type'		=> 'text',
			'id'		=> $defaul_ID,
			'name'		=> $defaul_ID,
//			'default'	=> '', // Deprecated
			'class-p'	=> '', // Deprecated
			'label'	    => '',
			'desc'	    => '',
			'container'	=> [
				'tag'	=> 'div',
				'attr'	=> [],
			],
		];

		/**
		 * Before setting the value merge $attr wit $default
		 */
		$attr = array_replace_recursive( $default, $attr );
		$attr['value'] = $this->set_value( $attr, $instance );

		/**
		 * Compat for widget and settings
		 * Set after the value is setted.
		 */
		$keys = [
			'_id'   => 'id',
			'_name' => 'name',
		];

		foreach ( $keys as $old => $new ) {
			$old = trim( $old );
			$new = trim( $new );
			if ( isset( $attr[ $old ] ) ) {
				$attr[ $new ] = $attr[ $old ];
				unset( $attr[ $old ] );
			}
		}

		/**
		 * Before to render the field make sure
		 * the 'name' attr is not the same as the default
		 */
		if ( $default['name'] === $attr['name'] ) {
			$attr['name'] = $attr['id'];
		}

		$excluded = [
			'label',
			'desc',
			'default', // Deprecated
			'class-p', // Deprecated
			'validate',
			'sanitize',
			'section',
			'container',
		];

		return $this->withContainer(
			$attr['container']['tag'],
			array_replace_recursive( [ 'class' => $attr['class-p'] ], $attr['container']['attr'] ),
			( new View_Factory() )
				->make( $attr['type'] )
				->with( 'label', $attr['label'] )
				->with( 'desc', $attr['desc'] )
				->render( $this->exclude_attrs( $attr, $excluded ) )
		);
	}

	/**
	 * @param bool|string $tag
	 * @param array $attr
	 * @param string $content
	 * @return string
	 */
	private function withContainer( $tag = '', array $attr = [], string $content = '' ): string {
		if ( empty( $tag ) ) {
			return $content;
		}

		return sprintf(
			'<%1$s%2$s>%3$s</%1$s>',
			\esc_html( $tag ),
			HTML\get_attr( isset( $attr['id'] ) ?? $tag, $attr ),
			$content
		);
	}

	/**
	 * Get value of the field
	 *
	 * @param  array $attr
	 * @param  array $instance
	 * @return string|int|bool
	 */
	private function set_value( array $attr, array $instance = [] ) {

		if ( isset( $instance[ $attr['id'] ] ) ) {
			return $instance[ $attr['id'] ];
		}

		if ( isset( $attr['value'] ) ) {
			return $attr['value'];
		}

		if ( isset( $attr['default'] ) ) {
			return $attr['default'];
		}

		return '';
	}

	/**
	 * Combines attributes into a string for a form element
	 *
	 * @since  2.0.0
	 * @param  array $attrs Attributes to concatenate.
	 * @param  array $attr_exclude Attributes that should NOT be concatenated.
	 *
	 * @return string               String of attributes for form element
	 */
	private function exclude_attrs( array $attrs, array $attr_exclude = [] ) {
		return array_diff_key( $attrs, array_flip( $attr_exclude ) );
	}

	/**
	 * Combines attributes into a string for a form element
	 *
	 * @since  2.0.0
	 * @param  array $attrs Attributes to concatenate.
	 * @param  array $attr_exclude Attributes that should NOT be concatenated.
	 *
	 * @return string               String of attributes for form element
	 */
	private function concat_attrs($attrs, $attr_exclude = [] ) {

		$context = isset( $attrs['id'] ) ? $attrs['id'] : '';

		// $attributes = '';
		// foreach ( $attrs as $attr => $val ) {
		// 	$excluded = in_array( $attr, (array) $attr_exclude, true );
		// 	$empty    = false === $val && 'value' !== $attr;
		// 	if ( ! $excluded && ! $empty ) {
		// 		// If data attribute, use single quote wraps, else double.
		// 		$quotes = stripos( $attr, 'data-' ) !== false ? "'" : '"';
		// 		$attributes .= sprintf( ' %1$s=%3$s%2$s%3$s', $attr, $val, $quotes );
		// 	}
		// }
		// return $attributes;

		return HTML\get_attr( $context, $this->exclude_attrs($attrs, $attr_exclude) );
	}

	/**
	 * Determine whether this field should show, based on the 'show_on_cb' callback.
	 * Forked from CMB2
	 * @see CMB2_Field.php
	 *
	 * @since 2.0.0
	 *
	 * @param $attr
	 * @return bool Whether the field should be shown.
	 */
	private function should_show($attr) {

		/**
		 * Default. Show the field
		 *
		 * @var bool
		 */
		$show = true;

		if ( ! isset( $attr[ 'show_on_cb' ] ) ) {
			return $show;
		}

//		if ( 'false' === $attr[ 'show_on_cb' ] ) {
//			$attr[ 'show_on_cb' ] = false;
//		}

		/**
		 * Use the callback to determine showing the field, if it exists
		 */
		if ( is_callable( $attr[ 'show_on_cb' ] ) ) {
			return (bool) call_user_func( $attr[ 'show_on_cb' ], $this );
		}

		/**
		 * Example:
		 * 'load_on'		=> false,
		 * 'load_on'		=> true,
		 * 'load_on'		=> is_my_function\return_bool(),
		 */
		return (bool) $attr[ 'show_on_cb' ];
	}

	/**
	 * Get all types
	 *
	 * @return array [description]
	 */
	public function get_all_types() {
		_deprecated_function( __FUNCTION__, '2.0', '( new View_Factory() )->getTypes()' );
		return ( new View_Factory() )->getTypes();
	}

	/**
	 * Get the field type
	 *
	 * @param  array $attr
	 * @param  array $instance This is the $instance variable of widget
	 *                         or the options variable of the plugin.
	 *
	 * @return string           Return the field html
	 */
	public function get_field_type( array $attr, array $instance ) {
		_deprecated_function( __FUNCTION__, '2.0', '( new Fields() )->render()' );
		return $this->render( $attr, $instance );
	}
}
