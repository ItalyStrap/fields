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

	private $types;

	/**
	 * View_Factory constructor.
	 */
	public function __construct() {
		$this->types = require __DIR__ . '/../config/types.php' ;
	}

	/**
	 * Render View
	 *
	 * @param string $type
	 * @return RenderableElementInterface
	 */
	public function make( $type = 'text' ): RenderableElementInterface {

		$search = \strtolower( \strval( $type ) );

		if ( isset( $this->types[ $search ] ) ) {
			return new $this->types[ $search ];
		} elseif ( \class_exists( $type ) ) {
			$class = new $type();
			return $class;
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
	 * Get all types
	 *
	 * @return array Return all fields type
	 */
	public function getTypes(): array {
		return (array) $this->types;
	}
}
