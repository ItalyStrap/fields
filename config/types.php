<?php

namespace ItalyStrap\Fields;

/**
 * Type of classes for creating fields components
 */
return [
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

	'taxonomy_select'			=> View\Taxonomy_Select::class,
	'taxonomy_multiple_select'	=> View\Taxonomy_Select::class,

	'media'						=> View\Media::class,
	'media_list'				=> View\Media::class,
];