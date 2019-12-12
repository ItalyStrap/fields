<?php
declare(strict_types=1);

namespace ItalyStrap\Fields\View;

use ItalyStrap\HTML;

class AbstractView implements RenderableElementInterface {

	protected $elements = [];

	/**
	 * @param array $attr
	 *
	 * @return string
	 */
	public function render( array $attr ): string {
		$this->elements = \array_merge( $this->elements, $attr );
		return $this->maybeRender( $attr );
	}

	/**
	 * @param array $attr
	 * @return mixed
	 */
	protected function maybeRender( array $attr ) {
		return '';
	}

	/**
	 * @inheritDoc
	 */
	public function with( $key, $value ) {
		$this->set( $key, $value );
		return $this;
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	protected function set( $key, $value ) {
		$this->elements[ $key ] = $value;
	}

	/**
	 * @param string $key
	 * @param null   $default
	 *
	 * @return mixed|null
	 */
	protected function get( $key, $default = null ) {
		if ( ! isset( $this->elements[ $key ] ) ) {
			return $default;
		}

		return $this->elements[ $key ];
	}

	/**
	 * Create the field description
	 *
	 * @return string       Return the label
	 */
	protected function label() {
		return $this->renderTag( 'label', ['for' => (string) $this->get('id') ] );
	}

	/**
	 * Create the field description
	 *
	 * @return string       Return the description
	 */
	protected function description() {
		return $this->renderTag( 'desc', ['class' => 'description'] );
	}

	/**
	 * Create the field legend
	 *
	 * @return string       Return the description
	 */
	protected function legend() {
		return $this->renderTag( 'legend', ['class' => 'legend'] );
	}

	/**
	 * Render the HTML tag for label or description.
	 *
	 * @param string $tag          The name of the element to render (label|desc).
	 * @param array  $default_attr Default attributes for the element.
	 *
	 * @return string
	 */
	private function renderTag( $tag, $default_attr = [] ) {

		if ( empty( $this->elements[ $tag ] ) ) {
			return '';
		}

		if ( 'hidden' === $this->elements['type'] ) {
			return '';
		}

		$content = '';
		$attr = [];
//        $label = [
//            'label' => [
//                'content'     => 'Label Title',
//                'attributes' => [
//                    'class'    => 'css_class',
//                ],
//            ],
//        ];
		if ( \is_array( $this->elements[ $tag ] ) ) {
			$content = (string) $this->elements[ $tag ]['content'];
			$attr = (array) $this->elements[ $tag ]['attributes'];
		} else {
			$content = (string) $this->elements[ $tag ];
		}

		$format = [
			'label'		=> '<label%s>%s</label>',
			'legend'	=> '<legend%s>%s</legend>',
			'desc'		=> '<p%s>%s</p>',
		];

		return \sprintf(
			isset( $format[ $tag ] ) ? (string) $format[ $tag ] : '%s %s',
			HTML\get_attr( (string) $this->get( 'id' ), \array_merge( $default_attr, $attr ) ),
			\wp_kses_post( $content )
		);
	}
}
