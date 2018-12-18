<?php
/**
 * Fields API: Fields Class
 *
 * Idea: https://github.com/Chrico/wp-fields
 *
 * @version 1.0.0
 * @package ItalyStrap
 */

namespace ItalyStrap\Fields;

use ItalyStrap\HTML;
use InvalidArgumentException;

/**
 * Class for make field type
 */
class Fields implements Fields_Interface {

	private $types = [
        'button'			=> '\ItalyStrap\Fields\View\Input',
        'color'				=> '\ItalyStrap\Fields\View\Input',
        'date'				=> '\ItalyStrap\Fields\View\Input',
        'datetime'			=> '\ItalyStrap\Fields\View\Input',
        'datetime-local'	=> '\ItalyStrap\Fields\View\Input',
        'email'				=> '\ItalyStrap\Fields\View\Input',
        'file'				=> '\ItalyStrap\Fields\View\Input',
        'hidden'			=> '\ItalyStrap\Fields\View\Input',
        'image'				=> '\ItalyStrap\Fields\View\Input',
        'month'				=> '\ItalyStrap\Fields\View\Input',
        'number'			=> '\ItalyStrap\Fields\View\Input',
        'password'			=> '\ItalyStrap\Fields\View\Input',
        'range'				=> '\ItalyStrap\Fields\View\Input',
        'search'			=> '\ItalyStrap\Fields\View\Input',
        'submit'			=> '\ItalyStrap\Fields\View\Input',
        'tel'				=> '\ItalyStrap\Fields\View\Input',
        'text'				=> '\ItalyStrap\Fields\View\Input',
        'time'				=> '\ItalyStrap\Fields\View\Input',
        'url'				=> '\ItalyStrap\Fields\View\Input',
        'week'				=> '\ItalyStrap\Fields\View\Input',

		'checkbox'			=> '\ItalyStrap\Fields\View\Checkbox',

		'radio'			    => '\ItalyStrap\Fields\View\Radio',

		'editor'			=> '\ItalyStrap\Fields\View\Editor',
		'textarea'			=> '\ItalyStrap\Fields\View\Textarea',

		'select'			=> '\ItalyStrap\Fields\View\Select',
		'multiple_select'   => '\ItalyStrap\Fields\View\Select',

		'taxonomy_select'   => '\ItalyStrap\Fields\View\Taxonomy_Select',
		'taxonomy_multiple_select'   => '\ItalyStrap\Fields\View\Taxonomy_Select',
	];

    /**
     * Get all types
     *
     * @return array [description]
     */
	public function get_all_types() {
		return (array) $this->types;
	}

    /**
     * Get value of the field
     *
     * @param  array $attr
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

		if ( isset( $attr['class-p'] ) ) {
		    _deprecated_argument(
		            __FUNCTION__,
                    '1.0',
                    'This attributes is deprecated on . Use wrapper attributes instead'
            );
        }

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
		$defaul_ID = isset( $attr['id'] ) ? $attr['id'] : uniqid();
		$default = [
			'type'		=> 'text',
			'id'		=> $defaul_ID,
			'name'		=> $defaul_ID,
//			'default'	=> '', // Deprecated
			'class-p'	=> '', // Deprecated
			'label'	    => '',
			'desc'	    => '',
		];

		$attr = array_merge( $default, $attr );

        $attr['value'] = $this->set_value( $attr, $instance );

		$excluded = [
			'label',
			'desc',
			'default', // Deprecated
			'class-p', // Deprecated
			'validate',
			'sanitize',
			'section',
		];

		$wrapper = '<div%1$s>%2$s</div>';

		/**
		 * Run method
		 */
		return sprintf(
			$wrapper,
			HTML\get_attr( $attr['id'], [ 'class' => $attr['class-p'] ] ),
			$this->get_view( $attr )
                ->with( 'label', $attr['label'] )
                ->with( 'desc', $attr['desc'] )
                ->render( $this->exclude_attrs( $attr, $excluded ) )
		);
	}

	/**
	 * Render View
	 *
	 * @param  string $type
	 * @return string
	 */
	private function get_view( $attr ) {

        $type = (string) $attr['type'];
        $search = strtolower($type);

        if ( isset( $this->types[ $search ] ) ) {

			return new $this->types[ $search ];

        } elseif ( class_exists( $type ) ) {

			$class = new $type();
            // if ( $class instanceof RenderableElementInterface ) {
				return $class;
            // }
        }

        return new $this->types['text'];

        // throw new \Exception\UnknownTypeException(
        //     sprintf(
        //         'The given type "%s" is not an instance of "%s".',
        //         $type,
        //         'RenderableElementInterface::class'
        //     )
        // );
	}

	/**
	 * Create the Field Select with Options Group
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Select with Options Group
	 */
	public function select_group( array $key, $out = '' ) {

		$out .= $this->label( $key['name'], $key['_id'] );

		$out .= '<select id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" ';

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		$out .= '> ';

		$selected = isset( $key['value'] ) ? $key['value'] : $key['default'];

		if ( isset( $key['show_option_none'] ) ) {
			$none = ( is_string( $key['show_option_none'] ) ) ? $key['show_option_none'] : __( 'None', 'italystrap' ) ;
			// $key['options'] = array_merge( array( 'none' => $none ),$key['options'] );
			$out .= '<option value="0"> ' . esc_html( $none ) . '</option>';
			// $out .= '<option  disabled selected> ' . esc_html( $none ) . '</option>';
		}

		foreach ( (array) $key['options'] as $group => $options ) {

			$out .= '<optgroup label="' . $group . '">';

			foreach ( $options as $field => $option ) {

				$out .= '<option value="' . esc_attr( $field ) . '" ';

				if ( esc_attr( $selected ) === $field ) {
					$out .= ' selected="selected" '; }

				$out .= '> ' . esc_html( $option ) . '</option>';
			}

			$out .= '</optgroup>';

		}

		$out .= '</select>';

		if ( isset( $key['desc'] ) ) {
			$out .= $this->description( $key['desc'] ); }

		return $out;
	}

	/**
	 * Create the field image_size
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 *
	 * @return string      Return the HTML field image_size
	 */
	public function group( array $key, $out = '' ) {

		foreach ( $key['group_field'] as $field ) {

			$this->set_attr_id_name( $field );

			/**
			 * Prefix method
			 *
			 * @var string
			 */
			$field_method = '' . str_replace( '-', '_', $field['type'] );

			$attr['type'] = $field['type'];

			// $out .= method_exists( $this, $field_method ) ? $this->$field_method( $field ) : $this->text( $field );
			$out .= method_exists( $this, $field_method ) ? $this->$field_method( $field ) : $this->text( $field );
		}

		return sprintf(
			'%1$s %2$s',
			$this->label( $key['name'], $key['_id'] ),
			$out
		);
	}

	/**
	 * Create the field label
	 *
	 * @param  string $label The labels name.
	 * @param  string $for   The labels ID.
	 *
	 * @return string       Return the labels
	 */
	public function label( $label = '', $for = '' ) {

		if ( empty( $label ) ) {
			return '';
		}

		return sprintf(
			'<label%s>%s</label>',
			HTML\get_attr( $for, [ 'for' => $for ] ),
			esc_html( $label )
		);
	}

	/**
	 * Create the field description
	 *
	 * @access public
	 * @param  string $desc The description.
	 *
	 * @return string       Return the description
	 */
	public function description( $desc ) {

		if ( empty( $desc ) ) {
			return '';
		}

		return  sprintf(
			'<div><small class="description">%s</small></div>',
			wp_kses_post( $desc )
		);

	}

	/**
	 * Get element with image for media fields
	 *
	 * @param  int $id The ID of the image.
	 * @param  string $text The text.
	 * @return string        The HTML of the element with image
	 */
	public function get_el_media_field( $id ) {

		$attr = array(
			'data-id'	=> $id,
		);
		$output = wp_get_attachment_image( $id , 'thumbnail', false, $attr );

		if ( '' === $output ) {
			$id = (int) get_post_thumbnail_id( $id );
			$output = wp_get_attachment_image( $id , 'thumbnail', false, $attr );
		}

		if ( $output ) {
			echo '<li class="carousel-image ui-state-default"><div><i class="dashicons dashicons-no"></i>' . $output . '</div></li>';// XSS ok.
		}
	}

	/**
	 * Create the Field Media
	 * This field add a single image
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Text
	 */
	public function media( array $key, $out = '' ) {

		$attr = array(
			'type'	=> 'text',
		);

		$out = $this->label( $key['name'], $key['_id'] ) . $this->input( $attr, $key );

		$value = isset( $key['value'] ) ? esc_attr( $key['value'] ) : '';

		ob_start();

		?>
        <h5><?php echo $key['desc']; ?></h5>
        <hr>
        <div class="media_carousel_sortable">
            <ul class="carousel_images" style="text-align:center">
				<?php
				if ( ! empty( $value ) ) {
					$this->get_el_media_field( absint( $value ) );
				} ?>
            </ul>
        </div>
        <span style="clear:both;"></span>
        <input class="upload_single_image_button button button-primary widefat" type="button" value="<?php esc_attr_e( 'Add image', 'italystrap' ); ?>" />
        <hr>
		<?php

		$out .= ob_get_contents();
		ob_end_clean();

		return $out;
	}

	/**
	 * Create the Field Media List
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Text
	 */
	public function media_list( array $key, $out = '' ) {

		$attr = array(
			'type'	=> 'text',
		);

		$out = $this->label( $key['name'], $key['_id'] ) . $this->input( $attr, $key );

		$value = isset( $key['value'] ) ? esc_attr( $key['value'] ) : '';

		ob_start();

		?>
        <h5><?php esc_attr_e( 'Add your images', 'italystrap' ); ?></h5>
        <hr>
        <div class="media_carousel_sortable">
            <ul id="sortable" class="carousel_images">
				<?php if ( ! empty( $value ) ) : ?>
					<?php
					$ids = explode( ',', $value );

					foreach ( $ids as $id ) :
						$this->get_el_media_field( $id );
					endforeach; ?>
				<?php endif; ?>
            </ul>
        </div>
        <span style="clear:both;"></span>
        <input class="upload_carousel_image_button button button-primary widefat" type="button" value="<?php esc_attr_e( 'Add images', 'italystrap' ); ?>" />
        <hr>
		<?php

		$out .= ob_get_contents();
		ob_end_clean();

		return $out;
	}

	/**
	 * Upload the Javascripts for the media uploader in widget config
	 *
	 * @todo Sistemare gli script da caricare per i vari widget nel pannello admin
	 *
	 * @param string $hook The name of the page.
	 */
	public function upload_scripts( $hook ) {

		if ( 'widgets.php' !== $hook ) {
			return;
		}

		if ( function_exists( 'wp_enqueue_media' ) ) {

			wp_enqueue_media();

		} else {

			if ( ! wp_script_is( 'thickbox', 'enqueued' ) ) {

				wp_enqueue_style( 'thickbox' );
				wp_enqueue_script( 'thickbox' );

			}

			if ( ! wp_script_is( 'media-upload', 'enqueued' ) ) {
				wp_enqueue_script( 'media-upload' ); }
		}

		wp_enqueue_script( 'jquery-ui-sortable' );

		$js_file = ( WP_DEBUG ) ? 'admin/js/src/widget.js' : 'admin/js/widget.min.js';

		if ( ! wp_script_is( 'italystrap-widget' ) ) {

			wp_enqueue_style( 'italystrap-widget', ITALYSTRAP_PLUGIN_URL . 'admin/css/widget.css' );

			wp_enqueue_script(
				'italystrap-widget',
				ITALYSTRAP_PLUGIN_URL . $js_file,
				array( 'jquery', 'jquery-ui-sortable' )
			);

		}

	}

	/**
	 * Get the field type
	 *
	 * @param  array $key      The array with field arguments.
	 * @param  array $instance This is the $instance variable of widget
	 *                         or the options variable of the plugin.
	 *
	 * @return string           Return the field html
	 */
	public function get_field_type( array $attr, array $instance ) {
		return $this->render( $attr, $instance );
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

		/**
		 * Use the callback to determine showing the field, if it exists
		 */
		if ( is_callable( $attr[ 'show_on_cb' ] ) ) {
			return (bool) call_user_func( $attr[ 'show_on_cb' ], $this );
		}

		if ( 'false' === $attr[ 'show_on_cb' ] ) {
			return false;
		}

		return (bool) $attr[ 'show_on_cb' ];
	}

	/**
	 * is_callable
	 *
	 * @param  mixed $mixed
	 * @return bool
	 */
	private function is_callable( $mixed ) {

		switch ( $mixed ) {
			case is_array( $mixed ):
				return is_callable( $mixed );
				// break;
			case is_string( $mixed ):
				return is_callable( [ $mixed, 'render' ] );
				// break;
			
			default:
				return is_callable( $mixed );
				// break;
		}
	}

	/**
	 * add type
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	private function add_type( $type, $content = null ) {
		$this->types[ $type ] = $content;
	}

	/**
	 * add type
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	private function get_type( $type ) {
		return $this->types[ $type ];
	}
}
