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

	/**
	 * Set _id _name attributes
	 *
	 * @todo Creare codice per settare _id e _name in caso non siano forniti
	 *       come chiave -> valore con l'array $key.
	 *       Attualmente sono generati dalle classi che gestiscono widget e admin
	 *       Vedere: Widget::field_type();
	 *       Vedere: Admin::get_field_type();
	 *       Valutare se passare per referenza il valore
	 *
	 * @param  array $key The array with field arguments.
	 * @return array      The new array with _id and _name set.
	 */
	public function set_attr_id_name( array &$key ) {

		//italystrap_settings
		if ( empty( $this->args['options_name'] ) ) {
			$this->args['options_name'] = 'italystrap_settings';
		}

		/**
		 * Set field id and name
		 */
		$key['_id'] = $key['_name'] = $this->args['options_name'] . '[' . $key['id'] . ']';

		return $key;
	
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
	public function get_field_type( array $key, array $instance ) {

		$default = [
			'type'	=> 'text',
		];

		if ( ! isset( $key['_id'] ) ) {
			$key['_id'] = $key['id'];
		}

		if ( ! isset( $key['_name'] ) ) {
			$key['_name'] = trim( strtolower( str_replace( ' ', '', $key['name'] ) ) );
		}

		/**
		 * If field is requesting to be conditionally shown
		 */
		if ( ! $this->should_show( $key ) ) {
			return '';
		}

		/**
		 * Set field type
		 */
		if ( ! isset( $key['type'] ) ) {
			$key['type'] = 'text';
		}

		/**
		 * Prefix method
		 *
		 * @var string
		 */
		// $field_method = 'field_type_' . str_replace( '-', '_', $key['type'] );
		$field_method = str_replace( '-', '_', $key['type'] );

		/**
		 * Set Defaults
		 */
		$key['default'] = isset( $key['default'] ) ? ( (string) $key['default'] ) : '';

		if ( isset( $instance[ $key['id'] ] ) ) {
			/**
			 * Non ricordo perché ho fatto la if else sotto ad ogni modo il valore è già escaped quando è stampato dal metodo dedicato quindi non serve ma lo tengo per fare ulteriori test in futuro.
			 * Con la text area il valore deve essere passato senza nessuna validazione se no non stampa l'html.
			 */
			$key['value'] = $instance[ $key['id'] ];

			// if ( is_array( $instance[ $key['id'] ] ) ) {
			// 	$key['value'] = $instance[ $key['id'] ];

			// } else {
			// 	$key['value'] = strip_tags( $instance[ $key['id'] ] );
			// }
		} else {
			$key['value'] = null;
		}

		/**
		 * CSS class for <p>
		 *
		 * @var string
		 */
		$p_class = isset( $key['class-p'] ) ? ' class="' . $key['class-p'] . '"' : '';

		/**
		 * The field html
		 *
		 * @var string
		 */
		$output = '';

		/**
		 * Run method
		 */
		$output = sprintf(
			'<p%1$s>%2$s</p>',
			$p_class,
			method_exists( $this, $field_method ) ? $this->$field_method( $key ) : $this->text( $key )
		);

		return $output;
	}

	/**
	 * Create the field label
	 *
	 * @param  string $label The labels name.
	 * @param  string $id    The labels ID.
	 * @param  bool   $br    The labels ID.
	 *
	 * @return string       Return the labels
	 */
	public function label( $label = '', $id = '', $br = true ) {

		if ( empty( $label ) ) {
			return '';
		}

		return sprintf(
			'<label%s>%s</label>%s',
			HTML\get_attr( $id, [ 'for' => $id ] ),
			esc_html( $label ),
			$br ? '<br/>' : ''
		);
	}

	/**
	 * Create the Field Text
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 *
	 * @return string      Return the HTML Field Text
	 */
	public function text( array $key ) {

		$attr = [];

		return $this->label( $key['name'], $key['id'] ) . $this->input( $attr, $key );
	}

	/**
	 * Handles outputting an 'input' element
	 *
	 * @link http://html5doctor.com/html5-forms-input-types/
	 *
	 * @since  2.0.0
	 * @param  array $attr Override arguments.
	 * @param  array $key Override arguments.
	 *
	 * @return string     Form input element
	 */
	public function input( array $attr = array(), array $key = array() ) {

		if ( isset( $key['attributes'] ) ) {
			$attr = wp_parse_args( $attr, (array) $key['attributes'] );
		}

		$a = wp_parse_args( $attr, array(
			'type'				=> 'text',
			'class'				=> esc_attr( isset( $key['class'] ) ? $key['class'] : 'none' ),
			'name'				=> esc_attr( $key['_name'] ),
			'id'				=> esc_attr( $key['_id'] ),
			'value'				=> isset( $key['value'] ) ? esc_attr( $key['value'] ) : ( isset( $key['default'] ) ? esc_attr( $key['default'] ) : '' ),
			'desc'				=> $this->description( $key['desc'] ),
			'js_dependencies'	=> array(),
		) );

		if ( isset( $key['size'] ) ) {
			$a['size'] = esc_attr( $key['size'] );
		}

		if ( isset( $key['placeholder'] ) ) {
			$a['placeholder'] = esc_attr( $key['placeholder'] );
		}

		// if ( ! empty( $a['js_dependencies'] ) ) {
		// 	CMB2_JS::add_dependencies( $a['js_dependencies'] );
		// }

		return sprintf( '<input%s/>%s', $this->concat_attrs( $a, array( 'desc', 'js_dependencies' ) ), $a['desc'] );
	}

	/**
	 * Handles outputting an 'textarea' element
	 *
	 * @since  2.0.0
	 * @param  array $attr Override arguments.
	 * @param  array $key Override arguments.
	 *
	 * @return string      Form textarea element
	 */
	// public function textarea( array $attr = array(), array $key = array() ) {
	// 	$a = wp_parse_args( $attr, array(
	// 		'class' => esc_attr( isset( $key['class'] ) ? $key['class'] : '' ),
	// 		'name'  => esc_attr( $key['_name'] ),
	// 		'id'    => esc_attr( $key['_id'] ),
	// 		'cols'  => '60',
	// 		'rows'  => '10',
	// 		'value' => esc_attr( isset( $key['value'] ) ? $key['value'] : ( isset( $key['default'] ) ? $key['default'] : '' ) ),
	// 		'desc'  => $this->field_type_description( $key['desc'] ),
	// 	) );
	// 	return sprintf( '<textarea%s>%s</textarea>%s', $this->concat_attrs( $a, array( 'desc', 'value' ) ), $a['value'], $a['desc'] );
	// }

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
	 * Create the Field Text
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Text
	 */
	// public function text( array $key, $out = '' ) {

	// 	$attr = array();

	// 	return $this->label( $key['name'], $key['_id'] ) . $this->input( $attr, $key );
	// }

	/**
	 * Create the Field Text
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Text
	 */
	public function hidden( array $key, $out = '' ) {

		$attr = array(
			'type'	=> 'hidden',
			'desc'	=> '',
		);

		return $this->label( $key['name'], $key['_id'] ) . $this->input( $attr, $key );
	}

	/**
	 * Create the field number
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML field number
	 */
	public function number( array $key, $out = '' ) {

		$attr = array(
			'type'	=> 'number',
		);

		return $this->label( $key['name'], $key['_id'] ) . $this->input( $attr, $key );
	}

	/**
	 * Create the field email
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML field email
	 */
	public function email( array $key, $out = '' ) {

		$attr = array(
			'type'	=> 'email',
		);

		return $this->label( $key['name'], $key['_id'] ) . $this->input( $attr, $key );
	}

	/**
	 * Create the field url
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML field url
	 */
	public function url( array $key, $out = '' ) {

		$attr = array(
			'type'	=> 'url',
		);

		return $this->label( $key['name'], $key['_id'] ) . $this->input( $attr, $key );
	}

	/**
	 * Create the field tel
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML field tel
	 */
	public function tel( array $key, $out = '' ) {

		$attr = array(
			'type'	=> 'tel',
		);

		return $this->label( $key['name'], $key['_id'] ) . $this->input( $attr, $key );
	}

	/**
	 * Create the field file
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML field file
	 */
	public function file( array $key, $out = '' ) {

		$attr = array(
			'type'	=> 'file',
		);

		return $this->label( $key['name'], $key['_id'] ) . $this->input( $attr, $key );
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
	 * Create the Field Media List OLD
	 * Tenere solo nel caso ci siano problemi con le altre due
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Text
	 */
	public function media_list_old( array $key, $out = '' ) {

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

						$attr = array(
							'data-id'	=> $id,
						);
						$output = wp_get_attachment_image( $id , 'thumbnail', false, $attr );

						if ( '' === $output ) {
							$id = (int) get_post_thumbnail_id( $id );
							$output = wp_get_attachment_image( $id , 'thumbnail', false, $attr );
						}

						if ( $output ) :
					?>
				
						<li class="carousel-image ui-state-default">
							<div>
								<i class="dashicons dashicons-no"></i>
								<?php echo $output; // XSS ok. ?>
							</div>
						</li>
				
					<?php
						endif;
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
	 * Create the Field Textarea
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Textarea
	 */
	public function textarea( array $key, $out = '' ) {
		$out .= $this->label( $key['name'], $key['_id'] );

		$out .= '<textarea ';

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		if ( isset( $key['rows'] ) ) {
			$out .= 'rows="' . esc_attr( $key['rows'] ) . '" '; }

		if ( isset( $key['cols'] ) ) {
			$out .= 'cols="' . esc_attr( $key['cols'] ) . '" '; }

		if ( isset( $key['placeholder'] ) ) {
			$out .= 'placeholder="' . esc_attr( $key['placeholder'] ) . '" ';
		}

		$value = isset( $key['value'] ) ? $key['value'] : $key['default'];

		$out .= 'id="'. esc_attr( $key['_id'] ) .'" name="' . esc_attr( $key['_name'] ) . '">' . esc_textarea( $value );

		$out .= '</textarea>';

		if ( isset( $key['desc'] ) ) {
			$out .= $this->description( $key['desc'] ); }

		return $out;
	}

	/**
	 * Create the Field Checkbox
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Checkbox
	 */
	public function checkbox( array $key, $out = '' ) {

		$out .= ' <input type="checkbox" ';

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" ';
		}

		$out .= 'id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" value="1" ';

		// if ( ( isset( $key['value'] ) && '1' === $key['value'] ) || ( ! isset( $key['value'] ) && 1 === $key['default'] ) ) {
		// 	$out .= ' checked="checked" ';
		// }

		if ( ( ! isset( $key['value'] ) && ! empty( $key['default'] ) ) || ( isset( $key['value']  ) && ! empty( $key['value'] ) ) ) {
			$out .= ' checked="checked" ';
		}

		/**
		 * Da vedere se utilizzabile per fare il controllo sulle checkbox.
		 * if ( isset( $key['value'] ) && 'true' === $key['value'] ) {
		 * 	$key['value'] = true;
		 * 	} else $key['value'] = false;
		 *
		 * $out .= checked( $key['value'], true );
		 */

		$out .= ' /> ';

		$out .= $this->label( $key['name'], $key['_id'], false );

		if ( isset( $key['desc'] ) ) {
			$out .= $this->description( $key['desc'] );
		}

		return $out;
	}

	/**
	 * Create the Field Select
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Select
	 */
	public function select( array $key, $out = '' ) {

		$out .= $this->label( $key['name'], $key['_id'] );

		$out .= '<select id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" ';

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		$out .= '> ';

		$selected = isset( $key['value'] ) ? $key['value'] : $key['default'];

		if ( ! isset( $key['options'] ) ) {
			$key['options'] = array();
		}

		if ( isset( $key['show_option_none'] ) ) {
			$none = is_string( $key['show_option_none'] ) ? $key['show_option_none'] : __( 'None', 'italystrap' ) ;
			// $key['options'] = array_merge( array( 'none' => $none ), $key['options'] );
			$out .= '<option value="0"> ' . esc_html( $none ) . '</option>';
			// $out .= '<option  disabled selected> ' . esc_html( $none ) . '</option>';
		}

		foreach ( (array) $key['options'] as $field => $option ) {

			$out .= '<option value="' . esc_attr( $field ) . '" ';

			if ( $selected === $field ) {
				$out .= ' selected="selected" '; }

			$out .= '> ' . esc_html( $option ) . '</option>';

		}

		$out .= ' </select> ';

		if ( isset( $key['desc'] ) ) {
			$out .= $this->description( $key['desc'] ); }

		return $out;
	}

	/**
	 * Create the Field Multiple Select
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Select
	 */
	public function multiple_select( array $key, $out = '' ) {

		$out .= $this->label( $key['name'], $key['_id'] );

		// $out .= '<select id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" ';
		$out .= '<select id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '[]" ';

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		$out .= 'size="6" multiple> ';

		$default = empty( $key['default'] ) ? array() : array( $key['default'] );
		$selected = ! empty( $key['value'] ) ? $key['value'] : $default;

		if ( isset( $key['show_option_none'] ) ) {
			$none = ( is_string( $key['show_option_none'] ) ) ? $key['show_option_none'] : __( 'None', 'italystrap' ) ;
			// $key['options'] = array_merge( array( 'none' => $none ), $key['options'] );
			$out .= '<option value="0"> ' . esc_html( $none ) . '</option>';
			// $out .= '<option  disabled selected> ' . esc_html( $none ) . '</option>';
		}

		foreach ( (array) $key['options'] as $field => $option ) {

			$out .= '<option value="' . esc_attr( $field ) . '" ';

			if ( in_array( $field, (array) $selected, true ) ) {
				$out .= ' selected="selected" ';
			}

			$out .= '> ' . esc_html( $option ) . '</option>';

		}

		$out .= ' </select> ';

		if ( isset( $key['desc'] ) ) {
			$out .= $this->description( $key['desc'] ); }

		return $out;
	}

	/**
	 * Create the Field Multiple Select
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Select
	 */
	public function taxonomy_multiple_select( array $key, $out = '' ) {

		$out .= $this->label( $key['name'], $key['_id'] );

		$out .= '<select id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '[]" ';

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		$out .= 'size="6" multiple> ';

		$selected = ! empty( $key['value'] ) ? $key['value'] : array();

		if ( isset( $key['show_option_none'] ) ) {
			$none = ( is_string( $key['show_option_none'] ) ) ? $key['show_option_none'] : __( 'None', 'italystrap' ) ;
			$out .= '<option value="0"> ' . esc_html( $none ) . '</option>';
		}

		$tax_arrays = get_terms( $key['taxonomy'] );

// var_dump( wp_list_categories( array( 'taxonomy' => $key['taxonomy'], 'echo' => false ) ) );
		foreach ( (array) $tax_arrays as $tax_obj ) {

			if ( ! is_object( $tax_obj ) ) {
				continue;
			}

			$out .= '<option value="' . esc_attr( $tax_obj->term_id ) . '" ';

			if ( in_array( $tax_obj->term_id, (array) $selected ) ) {
				$out .= ' selected="selected" ';
			}

			$out .= '> ' . esc_html( $tax_obj->name ) . '</option>';

		}

		$out .= ' </select> ';

		if ( isset( $key['desc'] ) ) {
			$out .= $this->description( $key['desc'] ); }

		return $out;
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
	 * Create the Field Editor
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Editor
	 */
	public function editor( array $key, $out = '' ) {

		$attr = array();

		ob_start();

		wp_editor(
			$key['value'],
			$key['_id'],
			array(
				'textarea_name' => $key['_name'],
				// 'media_buttons' => false,
				// 'textarea_rows' => 5,
				// 'editor_css'    => '<style>#wp-italy_cookie_choices_text-wrap{max-width:520px}</style>',
				'teeny' => true
			)
		);

		$output = ob_get_contents();
		ob_end_clean();

		return $this->label( $key['name'], $key['_id'] ) . $output;
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
			'<br/><small class="description">%s</small>',
			wp_kses_post( $desc )
		);

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
	 * Combines attributes into a string for a form element
	 *
	 * @since  2.0.0
	 * @param  array $attrs        Attributes to concatenate.
	 * @param  array $attr_exclude Attributes that should NOT be concatenated.
	 *
	 * @return string               String of attributes for form element
	 */
	public function concat_attrs( $attrs, $attr_exclude = [] ) {

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

		$attrs = array_diff_key( $attrs, array_flip( $attr_exclude ) );

		return HTML\get_attr( $context, $attrs );
	}

	/**
	 * Determine whether this field should show, based on the 'show_on_cb' callback.
	 * Forked from CMB2
	 * @see CMB2_Field.php
	 *
	 * @since 2.0.0
	 *
	 * @param  array $key      The array with field arguments.
	 *
	 * @return bool Whether the field should be shown.
	 */
	public function should_show( $key ) {
		
		/**
		 * Default. Show the field
		 *
		 * @var bool
		 */
		$show = true;

		if ( ! isset( $key[ 'show_on_cb' ] ) ) {
			return $show;
		}

		/**
		 * Use the callback to determine showing the field, if it exists
		 */
		if ( is_callable( $key[ 'show_on_cb' ] ) ) {
			return (bool) call_user_func( $key[ 'show_on_cb' ], $this );
		}

		return (bool) $show;
	}
}
