<?php
/**
 * This class handles building pretty report graphs
 *
 * @since       1.0.2
 * @package     EverAccounting
 */

namespace EverAccounting;

defined( 'ABSPATH' ) || exit();

/**
 * EverAccounting_Graph Class
 *
 * @since 1.0.2
 */
class Chart {
	/**
	 * Chart id.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $id;

	/**
	 * Char datasets.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $datasets = array();

	/**
	 * Chart labels.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $labels = array();

	/**
	 * Chart container.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $container = '';

	/**
	 *  Chart Options.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $options = array();

	/**
	 * Chart type.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $type = '';

	/**
	 * Chart loader color.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $loader_color = '#22292F';

	/**
	 * Chart height.
	 *
	 * @since 1.0.0
	 * @var int
	 */
	public $height = 400;

	/**
	 * Chart width.
	 *
	 * @since 1.0.0
	 * @var null
	 */
	public $width = null;

	/**
	 * Chart constructor.
	 */
	public function __construct() {
		$this->id = md5( wp_rand() );
	}


	/**
	 * Set the chart type.
	 *
	 * @param string $type Chart type.
	 *
	 * @return object
	 */
	public function type( $type ) {
		$this->type = $type;

		return $this;
	}

	/**
	 * Set the chart height.
	 *
	 * @param int $height Chart height.
	 *
	 * @return object
	 */
	public function height( $height ) {
		$this->height = $height;

		return $this;
	}

	/**
	 * Set the chart width.
	 *
	 * @param int $width    Chart width.
	 *
	 * @return object
	 */
	public function width( $width ) {
		$this->width = $width;

		return $this;
	}

	/**
	 * Set the chart options.
	 *
	 * @param array $options Chart options.
	 * @param bool  $overwrite  Overwrite existing options.
	 *
	 * @return object
	 */
	public function options( $options, $overwrite = false ) {
		if ( $overwrite ) {
			$this->options = $options;
		} else {
			$this->options = array_replace_recursive( $this->options, $options );
		}

		return $this;
	}

	/**
	 * Set the chart labels.
	 *
	 * @param array $labels Chart labels.
	 *
	 * @return object
	 */
	public function labels( $labels ) {

		$this->labels = $labels;

		return $this;
	}

	/**
	 * Adds a new dataset to the chart.
	 *
	 * @param array $dataset Chart dataset.
	 *
	 * @return object
	 */
	public function dataset( $dataset ) {
		$dataset = wp_parse_args(
			$dataset,
			array(
				'label'           => '',
				'data'            => array(),
				'color'           => '',
				'backgroundColor' => '',
				'options'         => array(),
				'fill'            => false,
			)
		);
		array_push( $this->datasets, $dataset );

		return $this;
	}

	/**
	 * Set line chart options.
	 *
	 * @since 1.0.2
	 *
	 * @return object
	 */
	public function set_line_options() {
		$this->options(
			array(
				'tooltips'   => array(
					'backgroundColor' => '#000000',
					'titleFontColor'  => '#ffffff',
					'bodyFontColor'   => '#e5e5e5',
					'bodySpacing'     => 4,
					'YrPadding'       => 12,
					'mode'            => 'nearest',
					'intersect'       => 0,
					'position'        => 'nearest',
				),
				'responsive' => true,
				'scales'     => array(
					'yAxes' => array(
						array(
							'barPercentage' => 1.6,
							'ticks'         => array(
								'padding'   => 10,
								'fontColor' => '#9e9e9e',
							),
							'gridLines'     => array(
								'drawBorder'       => false,
								'color'            => 'rgba(29,140,248,0.1)',
								'zeroLineColor'    => 'transparent',
								'borderDash'       => array( 2 ),
								'borderDashOffset' => array( 2 ),
							),
						),
					),
					'xAxes' => array(
						array(
							'barPercentage' => 1.6,
							'ticks'         => array(
								'suggestedMin' => 60,
								'suggestedMax' => 125,
								'padding'      => 20,
								'fontColor'    => '#9e9e9e',
							),
							'gridLines'     => array(
								'drawBorder'    => false,
								'color'         => 'rgba(29,140,248,0.0)',
								'zeroLineColor' => 'transparent',
							),
						),
					),
				),
			)
		);

		return $this;
	}

	/**
	 * Set donut options.
	 *
	 * @since 1.0.2
	 *
	 * @param array $colors Chart colors.
	 *
	 * @return object
	 */
	public function set_donut_options( $colors ) {
		$this->options(
			array(
				'color'            => array_values( $colors ),
				'cutoutPercentage' => 50,
				'legend'           => array(
					'position' => 'right',
				),
				'tooltips'         => array(
					'backgroundColor' => '#000000',
					'titleFontColor'  => '#ffffff',
					'bodyFontColor'   => '#e5e5e5',
					'bodySpacing'     => 4,
					'xPadding'        => 12,
					'mode'            => 'nearest',
					'intersect'       => 0,
					'position'        => 'nearest',
				),
				'scales'           => array(
					'yAxes' => array(
						'display' => false,
					),
					'xAxes' => array(
						'display' => false,
					),
				),
			)
		);

		return $this;
	}

	/**
	 * Render the chart.
	 *
	 * @since 1.0.2
	 */
	public function render() {
		$chart = wp_json_encode(
			array(
				'type'    => $this->type,
				'data'    => array(
					'labels'   => $this->labels,
					'datasets' => $this->datasets,
				),
				'options' => $this->options,
			)
		);

		eaccounting_enqueue_js( "new Chart(document.getElementById('ea-chart-$this->id'),$chart);" );
		echo sprintf(
			'<canvas id="ea-chart-%s" height="%s" width="%s">',
			esc_attr( $this->id ),
			esc_attr( $this->height ),
			esc_attr( $this->width )
		);
	}
}
