<?php
/**
 * Created by PhpStorm.
 * User: fisso
 * Date: 18/12/2018
 * Time: 16:28
 */
declare(strict_types=1);

namespace ItalyStrap\Fields;

use ItalyStrap\Fields\View\RenderableElementInterface;

class ViewFactory {

	protected static $types = [
		'button'					=> View\Input::class,
		'color'						=> View\Input::class,
		'date'						=> View\Input::class,
		'datetime'					=> View\Input::class,
		'datetime-local'			=> View\Input::class,
		'email'						=> View\Input::class,
		'file'						=> View\Input::class,
		'hidden'					=> View\Input::class,
		'image'						=> View\Input::class,
		'month'						=> View\Input::class,
		'number'					=> View\Input::class,
		'password'					=> View\Input::class,
		'range'						=> View\Input::class,
		'search'					=> View\Input::class,
		'submit'					=> View\Input::class,
		'tel'						=> View\Input::class,
		'text'						=> View\Input::class,
		'time'						=> View\Input::class,
		'url'						=> View\Input::class,
		'week'						=> View\Input::class,

		'checkbox'					=> View\Checkbox::class,

		'radio'						=> View\Radio::class,

		'editor'					=> View\Editor::class,
		'textarea'					=> View\Textarea::class,

		'select'					=> View\Select::class,
		'multiple_select'			=> View\Select::class,

		'taxonomy_select'			=> View\TaxonomySelect::class,
		'taxonomy_multiple_select'	=> View\TaxonomySelect::class,

		'media'						=> View\Media::class,
		'media_list'				=> View\Media::class,
	];

	/**
	 * Render View
	 *
	 * @param string $type
	 * @return RenderableElementInterface
	 */
	public static function make( $type = 'text' ): RenderableElementInterface {

		$search = \strtolower( \strval( $type ) );

		if ( isset( self::$types[ $search ] ) ) {
			return new self::$types[ $search ];
		} elseif ( \class_exists( $type ) ) {
			$class = new $type();
			return $class;
		}

		return new self::$types['text'];
	}

	/**
	 * Get all types
	 *
	 * @return array Return all fields type
	 */
	public static function getTypes(): array {
		return (array) self::$types;
	}
}
