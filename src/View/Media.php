<?php
declare(strict_types=1);

namespace ItalyStrap\Fields\View;

use ItalyStrap\HTML;

/**
 * Class Input
 *
 * @internal This class is not ready for production
 *
 * @package ItalyStrap\Fields\View
 */
class Media extends Input {

	/**
	 * @param array $attr
	 *
	 * @return string
	 */
	protected function maybeRender( array $attr ) {
		$this->once();

		$is_list = 'media_list' === (string) $attr[ 'type' ];

		$attr[ 'type' ] = 'text';

		$html = parent::maybeRender( $attr );

		$values = \explode( ',', (string) $attr[ 'value' ] );

		\ob_start();

		?>
		<hr>
		<div class="media_carousel_sortable">
			<ul id="sortable" class="carousel_images" style="text-align:center">
				<?php echo $this->listElements( $values ); ?>
			</ul>
		</div>
		<span style="clear:both;"></span>
		<?php

		$html .= \ob_get_clean();

		$class = \sprintf(
			'%s button button-primary',
			$is_list ? 'upload_carousel_image_button' : 'upload_single_image_button'
		);

		$button_attr = [
			'type' => 'button',
			'class' => $class,
			'value' => $is_list ? __( 'Add images', 'italystrap' ) : __( 'Add image', 'italystrap' ),
			'style' => 'margin-bottom:1rem',
		];

		unset( $this->elements[ 'label' ], $this->elements[ 'desc' ] );
		return $this->style() . $html . parent::maybeRender( $button_attr );
	}

	/**
	 * Get element with image for media fields
	 *
	 * @param  array $ids The ID of the image.
	 *
	 * @return string        The HTML of the element with image
	 */
	protected function listElements( array $ids ) {

		$html = '';
		foreach ( $ids as $id ) {
			$html .= $this->getListElement( $id );
		}

		return $html;
	}

	/**
	 * Get element with image for media fields
	 *
	 * @param  int $id The ID of the image.
	 *
	 * @return string        The HTML of the element with image
	 */
	protected function getListElement( $id ) {

		if ( empty( $id ) ) {
			return '';
		}

		$attr = array(
			'data-id'	=> \absint( $id ),
		);

		$html = \wp_get_attachment_image( $id, 'thumbnail', false, $attr );

		if ( '' === $html ) {
			$id = (int) \get_post_thumbnail_id( $id );
			$html = \wp_get_attachment_image( $id, 'thumbnail', false, $attr );
		}

		if ( empty( $html ) ) {
			return '';
		}

		return '<li class="carousel-image ui-state-default">
			<div><i class="dashicons dashicons-no"></i>' . $html . '</div></li>'; // XSS ok.
	}

	/**
	 *
	 */
	private function once() {

		if ( \did_action( 'media_fields_loaded' ) ) {
			return;
		}

		\wp_enqueue_media();
		\wp_enqueue_script( 'jquery-ui-sortable' );

//		$js_file = ( WP_DEBUG ) ? 'media.js' : 'media.min.js';

		$file = \file_get_contents( __DIR__ . '/../../assets/js/media.js' );

		$script = \sprintf(
			'<script id="italystrap_media_js">%s</script>',
			$file
		);

		\add_action( 'admin_footer', function () use ( $script ) {
			echo $script;
		}, PHP_INT_MAX );

		\do_action( 'media_fields_loaded' );
	}

	private function style() {

		if ( \did_action( 'CSS_media_fields_loaded' ) ) {
			return '';
		}

		$file = \file_get_contents( __DIR__ . '/../../assets/css/media.css' );

		$style = \sprintf(
			'<style scoped>%s</style>',
			$file
		);

		\do_action( 'CSS_media_fields_loaded' );

		return $style;
	}
}
