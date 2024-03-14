<?php

namespace TotalContestVendors\TotalCore\Helpers;

/**
 * Class Html
 * @package TotalContestVendors\TotalCore\Helpers
 */
class Html {
	/**
	 * @var string Void tags.
	 * @access protected
	 * @since  1.0.0
	 */
	protected static $void = [
		'area'   => true,
		'base'   => true,
		'br'     => true,
		'col'    => true,
		'embed'  => true,
		'hr'     => true,
		'img'    => true,
		'input'  => true,
		'keygen' => true,
		'link'   => true,
		'meta'   => true,
		'param'  => true,
		'source' => true,
		'track'  => true,
		'wbr'    => true,
	];
	/**
	 * @var string Tag.
	 * @access protected
	 * @since  1.0.0
	 */
	protected $tag;
	/**
	 * @var array Attributes.
	 * @access protected
	 * @since  1.0.0
	 */
	protected $attributes = [];
	/**
	 * @var array Inner content.
	 * @access protected
	 * @since  1.0.0
	 */
	protected $inner = [];
	/**
	 * @var string Cached HTML.
	 * @access protected
	 * @since  1.0.0
	 */
	protected $cache = '';

	/**
	 * HTML constructor.
	 *
	 * @param null $tag
	 * @param null $attributes
	 * @param null $inner
	 *
	 * @since 1.0.0
	 */
	public function __construct( $tag = null, $attributes = null, $inner = null ) {
		if ( ! empty( $tag ) ):
			$this->setTag( $tag );
		endif;

		if ( ! empty( $attributes ) ):
			foreach ( (array) $attributes as $key => $value ):
				$this->setAttribute( (string) $key, $value );
			endforeach;
		endif;

		if ( ! empty( $inner ) ):
			$this->setInner( $inner );
		endif;
	}

	/**
	 * Set attribute.
	 *
	 * @param bool|string $key       Attribute key.
	 * @param bool|mixed  $value     Attribute value.
	 * @param string      $separator Values separator.
	 *
	 * @since 1.0.0
	 * @return self $this Object instance
	 */
	public function setAttribute( $key, $value = false, $separator = ' ' ) {
		if ( ! empty( $key ) && $value !== false ):
			$this->cache              = '';
			$this->attributes[ $key ] = [ 'separator' => $separator, 'values' => [] ];

			foreach ( (array) $value as $item ):
				$this->attributes[ $key ]['values'][] = $item;
			endforeach;
		endif;

		return $this;
	}

	/**
	 * Get element tag.
	 *
	 * @return string Element tag
	 */
	public function getTag() {
		return $this->tag;
	}

	/**
	 * Set element tag.
	 *
	 * @param $tag
	 *
	 * @return self $this Self.
	 */
	public function setTag( $tag ) {
		$this->tag = tag_escape( strtolower( $tag ) );

		return $this;
	}

	/**
	 * Append to attribute.
	 *
	 * @param string $key   Attribute key
	 * @param string $value Attribute value
	 *
	 * @since 1.0.0
	 * @return self $this
	 */
	public function appendToAttribute( $key, $value ) {
		if ( ! isset( $this->attributes[ $key ] ) ):
			$this->setAttribute( $key, $value );
		else:
			foreach ( (array) $value as $item ):
				$this->attributes[ $key ]['values'][] = $item;
			endforeach;
		endif;

		return $this;
	}

	/**
	 * Get attribute.
	 *
	 * @param string $key     Attribute key.
	 * @param null   $default Default value.
	 *
	 *
	 * @return null|string attribute value
	 * @since 1.0.0
	 */
	public function getAttribute( $key, $default = null ) {
		return isset( $this->attributes[ $key ]['values'] ) ? implode( $this->attributes[ $key ]['separator'], $this->attributes[ $key ]['values'] ) : $default;
	}

	/**
	 * Prepend content to element (inner content).
	 *
	 * @param string $content Content
	 *
	 * @since 1.0.0
	 * @return $this Object instance.
	 */
	public function prependToInner( $content ) {
		$this->cache = '';
		array_unshift( $this->inner, $content );

		return $this;
	}

	/**
	 * Append content to element (inner content).
	 *
	 * @param string $content Content
	 *
	 * @since 1.0.0
	 * @return $this Object instance.
	 */
	public function appendToInner( $content ) {
		$this->cache   = '';
		$this->inner[] = $content;

		return $this;
	}

	/**
	 * Get inner content.
	 *
	 * @since 1.0.0
	 * @return array Inner contents
	 */
	public function getInner() {
		return $this->inner;
	}

	/**
	 * Set content to element (inner content).
	 *
	 * @param string $content Content
	 *
	 * @since 1.0.0
	 * @return $this Object instance.
	 */
	public function setInner( $content ) {
		$this->cache = '';
		$this->inner = is_array( $content ) ? $content : [ $content ];

		return $this;
	}

	/**
	 * To string.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function __toString() {
		return $this->render();
	}

	/**
	 * Render element.
	 *
	 * @since 1.0.0
	 * @return string Element rendered.
	 */
	public function render() {
		if ( empty( $this->tag ) ):
			$this->setTag( 'div' );
		endif;

		if ( empty( $this->cache ) ):
			$this->cache = $this->getOpenTag() . implode( '', $this->inner ) . $this->getCloseTag();
		endif;

		return $this->cache;
	}

	/**
	 * Get element open tag.
	 *
	 * @return string Element tag
	 */
	public function getOpenTag() {
		if ( empty( $this->tag ) ):
			$this->setTag( 'div' );
		endif;

		$openTag = "<{$this->tag}";

		$openTag .= static::attributesToHtml( $this->attributes );

		$openTag .= '>';

		return $openTag;
	}

	/**
	 * Get element close tag.
	 *
	 * @return string Element tag
	 */
	public function getCloseTag() {
		if ( isset( self::$void[ $this->tag ] ) ):
			return null;
		endif;

		return "</{$this->tag}>";
	}

	/**
	 * Generate HTML attributes from array.
	 *
	 * @param array  $attributes
	 * @param string $defaultSeparator
	 *
	 * @return string
	 */
	public static function attributesToHtml( $attributes, $defaultSeparator = ' ' ) {
		if ( ! is_array( $attributes ) ):
			return '';
		endif;

		$html = '';
		foreach ( $attributes as $key => $attribute ):
			// Normalize separator
			$separator = $defaultSeparator;
			if ( ! empty( $attribute['separator'] ) ):
				$separator = (string) $attribute['separator'];
			endif;

			// Normalize values
			if ( ! is_array( $attribute ) ):
				$values = [ (string) $attribute ];
			elseif ( ! isset( $attribute['values'] ) ):
				$values = $attribute;
			else:
				$values = $attribute['values'];
			endif;

			$attribute = [ 'separator' => $separator, 'values' => $values ];

			// Generate HTML
			$html .= sprintf(
				' %s="%s"', esc_attr( $key ),
				esc_attr( implode( $attribute['separator'], (array) $attribute['values'] ) )
			);
		endforeach;

		return $html;
	}
}