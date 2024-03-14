<?php namespace MSMoMDP\Std\Components;

use MSMoMDP\Std\Html\Element;
use MSMoMDP\Std\Html\Attr;
use MSMoMDP\Std\Core\Special;


class Tooltip {


	private $config      = array();
	public $tooltip      = null;
	public $scriptConfig = array();
	/**
	 * @method __construct
	 * @param array $cancelers Objects and their events, that hide tooltip
	 * @example $cancelers' =>[
	 *   'val' => '.canceler-x .canceler-y #canceler-z',
	 *   'on' => 'scroll'
	 * ]
	 */
	public function __construct( string $id, $refObjects, string $boundariesElementId, array $cancelers = array() ) {
		$this->config = array(
			'id'                  => $id,
			'refObjects'          => ( is_array( $refObjects ) ) ? implode( ',', $refObjects ) : $refObjects,
			'boundariesElementId' => $boundariesElementId,
			'cancelers'           => $cancelers,
		);
		$this->init_tooltip_html();
	}

	public static function insert_data_attr_to_ref( array &$attributes, string $title, string $content = '', string $imgUrl = '' ) {
		$attributes['data-tooltip-title']   = $title;
		$attributes['data-tooltip-content'] = $content;
		$attributes['data-tooltip-img-url'] = $imgUrl;
	}

	public function get_script_config() {
		return $this->config;
	}

	public function render() {
		if ( $this->tooltipWrapper ) {
			$this->tooltipWrapper->render();
		}
	}

	private function init_tooltip_html() {
		$tooltipId = $this->config['id'];

		$this->tooltipWrapper = new Element(
			'div',
			'g-tooltip',
			new Attr(
				array(
					'class' => 'popper',
					'id'    => $tooltipId,
				)
			),
			array(
				new Element(
					'div',
					'arrow',
					new Attr(
						array(
							'class'   => array( 'popper__arrow', 'x-arrow' ),
							'x-arrow' => '',
							'id'      => $tooltipId . '__arrow',
						)
					)
				),
				new Element(
					'div',
					'body',
					new Attr( array( 'id' => $tooltipId . '__body' ) ),
					array(
						new Element(
							'div',
							'img-wrap',
							null,
							new Element( 'img', 'img', new Attr( array( 'id' => $tooltipId . '__img' ) ), null, array(), false )
						),
						new Element(
							'div',
							'text-wrap',
							null,
							array(
								new Element(
									'div',
									'text',
									null,
									array(
										new Element( 'h5', 'title', new Attr( array( 'id' => $tooltipId . '__title' ) ), 'G-PopperTooltip title' ),
										new Element( 'p', 'content', new Attr( array( 'id' => $tooltipId . '__content' ) ), 'G-PopperTooltip content' ),
									)
								),
							)
						),
					)
				),
			)
		);
	}


}
