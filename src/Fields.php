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
declare(strict_types=1);

namespace ItalyStrap\Fields;

use ItalyStrap\HTML;

/**
 * Class for make field type
 */
class Fields implements FieldsInterface {

	private $field_container = '';

	/**
	 * @inheritDoc
	 */
	public function render( array $attr, array $instance = [] ): string {

		/**
		 * If field is requesting to be conditionally shown
		 */
		if ( ! $this->shouldShow( $attr ) ) {
			return '';
		}

		list( $default, $attr ) = $this->parseWithDefault( $attr );

		$attr['value'] = $this->setValue( $attr, $instance );

		$attr = $this->assertCompatWithWidget( $attr );

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
			(array) \array_replace_recursive( [ 'class' => $attr['class-p'] ], $attr['container']['attr'] ),
			ViewFactory::make( $attr['type'] )
				->with( 'label', $attr['label'] )
				->with( 'desc', $attr['desc'] )
				->render( $this->excludeAttrs( $attr, $excluded ) )
		);
	}

	/**
	 * @param array $attr
	 * @return array
	 */
	private function parseWithDefault( array $attr ): array {

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
		$default_ID = \uniqid();
		$default = [
			'type' => 'text',
			'id' => $default_ID,
			'name' => $default_ID,
//			'default'	=> '', // Deprecated
			'class-p' => '', // Deprecated
			'label' => '',
			'desc' => '',
			'container' => [
				'tag' => 'div',
				'attr' => [],
			],
		];

		/**
		 * Filter $attr and remove empty value
		 * Before setting the value merge $attr with $default
		 */
		$attr = (array)\array_replace_recursive( $default, \array_filter( $attr ) );

		return [ $default, $attr ];
	}

	/**
	 * @param array $attr
	 * @return array
	 */
	private function assertCompatWithWidget( array $attr ): array {
		/**
		 * Compat for widget and settings
		 * Set after the value is setted.
		 */
		$keys = [
			'_id' => 'id',
			'_name' => 'name',
		];

		foreach ($keys as $old => $new) {
			$old = \trim( $old );
			$new = \trim( $new );
			if ( isset( $attr[ $old ] ) ) {
				$attr[ $new ] = $attr[ $old ];
				unset( $attr[ $old ] );
			}
		}
		return $attr;
	}

	/**
	 * @param string $tag
	 * @param array $attr
	 * @param string $content
	 * @return string
	 */
	private function withContainer( $tag = '', array $attr = [], string $content = '' ): string {
		if ( empty( $tag ) ) {
			return $content;
		}

		$context = \strval( isset( $attr['id'] ) ?? $tag );

		return \sprintf(
			'<%1$s%2$s>%3$s</%1$s>',
			\esc_html( \strval( $tag ) ),
			HTML\get_attr( $context, $attr ),
			$content
		);
	}

	/**
	 * Get value of the field
	 *
	 * If this class is used in Settings API or Widget API maybe
	 * the $attr['id'] could be like this string "option-name[someKey]"
	 * in this case `isset( $instance[ $attr['id'] ] )` fail
	 * A better fallback is to set the expected value in `$attr['value']`
	 *
	 * @param  array $attr
	 * @param  array $instance
	 * @return string|int|bool
	 */
	private function setValue( array $attr, array $instance = [] ) {

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
	 * @return array               String of attributes for form element
	 */
	private function excludeAttrs( array $attrs, array $attr_exclude = [] ) {
		return \array_diff_key( $attrs, \array_flip( $attr_exclude ) );
	}

	/**
	 * Determine whether this field should show, based on the 'show_on_cb' callback.
	 * Forked from CMB2
	 * @see CMB2_Field.php
	 *
	 * @since 2.0.0
	 *
	 * @param array $attr
	 * @return bool Whether the field should be shown.
	 */
	private function shouldShow( $attr ) {

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
		if ( \is_callable( $attr[ 'show_on_cb' ] ) ) {
			return (bool) \call_user_func( $attr[ 'show_on_cb' ], $this );
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
	 * @param string $fun
	 * @param mixed $params
	 * @return array|string|void
	 */
	public function __call( $fun, $params ) {

		switch ( $fun ) {
			case 'get_all_types':
				\_deprecated_function( 'get_all_types', '2.0', 'View_Factory::getTypes()' );
				return ( new ViewFactory() )->getTypes();
			case 'get_field_type':
				\_deprecated_function( __FUNCTION__, '2.0', \sprintf( '%s::render()', __CLASS__ ) );
				return \call_user_func_array( array( $this, 'render' ), $params );
			default:
				// Call to undefined method ItalyStrap\Fields\Fields::test()
				throw new \BadMethodCallException( \sprintf( 'Call to undefined method  %s::%s()', __CLASS__, $fun ) );
		}
	}
}
