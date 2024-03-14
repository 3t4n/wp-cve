<?php
/**
 * Class to handle data migration
 *
 * @package SurferSEO
 */

namespace SurferSEO\Surfer\GSC;

/**
 * Class to handle data migration
 */
class Surfer_GSC_Posts_List {

	use Surfer_GSC_Common;

	/**
	 * Object construct.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Init function.
	 */
	public function init() {
		add_action( 'manage_posts_columns', array( $this, 'register_surfer_gsc_data_column' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'render_surfer_gsc_data_column' ) );
	}

	/**
	 * Adds column with GSC data to posts and pages list.
	 *
	 * @param array $columns Columns array.
	 * @return array
	 */
	public function register_surfer_gsc_data_column( $columns ) {
		$post_type = get_post_type();
		if ( ! in_array( $post_type, surfer_return_supported_post_types(), true ) ) {
			return $columns;
		}

		$icon = '<svg style="vertical-align: top; margin-right: 7px;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
			<path fill-rule="evenodd" clip-rule="evenodd" d="M5.81689 2.46973C3.65953 2.46973 1.91064 4.21862 1.91064 6.37598V11.1135H1.08496L2.68753 13.4372L4.29009 11.1135H3.47314V6.37598C3.47314 5.08156 4.52247 4.03223 5.81689 4.03223H13.9131C15.0828 4.03223 16.0309 4.98041 16.0309 6.15004H17.5934C17.5934 4.11746 15.9457 2.46973 13.9131 2.46973H5.81689ZM11.101 13.918H12.5032C12.7036 13.918 12.8237 13.7577 12.8237 13.5975V7.06703C12.8237 6.86671 12.6635 6.74652 12.5032 6.74652H11.101C10.9007 6.74652 10.7805 6.90678 10.7805 7.06703V13.5975C10.7805 13.7577 10.9407 13.918 11.101 13.918ZM6.93435 13.918H8.3366C8.53692 13.918 8.65711 13.7577 8.65711 13.5975V9.43081C8.65711 9.23049 8.49685 9.1103 8.3366 9.1103H6.93435C6.73403 9.1103 6.61384 9.27056 6.61384 9.43081V13.5574C6.61384 13.7577 6.7741 13.918 6.93435 13.918ZM15.2139 9.50595H16.0309V14.2435C16.0309 15.5379 14.9816 16.5872 13.6872 16.5872H5.59095C4.42131 16.5872 3.47312 15.639 3.47312 14.4694H1.91062C1.91062 16.502 3.55836 18.1497 5.59095 18.1497H13.6872C15.8445 18.1497 17.5934 16.4008 17.5934 14.2435V9.50595H18.419L16.8165 7.18223L15.2139 9.50595Z" fill="#1D2327"/>
		</svg>';

		$columns['surfer_gsc_traffic_data'] = $icon . __( 'Surfer', 'surferseo' );
		return $columns;
	}

	/**
	 * Renders column content for GSC data column.
	 *
	 * @param string $column_id Column ID.
	 */
	public function render_surfer_gsc_data_column( $column_id ) {
		if ( 'surfer_gsc_traffic_data' !== $column_id ) {
			return;
		}

		echo '<div class="surfer-layout">';
		$surfer_gsc_connection = Surfer()->get_surfer_settings()->get_option( 'content-importer', 'surfer_gsc_connection', false );
		if ( ! isset( $surfer_gsc_connection ) || 1 !== intval( $surfer_gsc_connection ) ) {
			echo '<a href="' . esc_attr( admin_url( 'admin.php?page=surfer' ) ) . '" class="surfer-button surfer-button--xsmall surfer-button--link">' . esc_html__( 'Add GSC', 'surferseo' ) . '</a>';
		} else {

			$post         = get_post();
			$post_traffic = surfer_get_last_post_traffic_by_id( $post->ID );

			if ( $post_traffic ) {
				$this->render_position_monitor_column_values( $post->ID );
			} elseif ( 'publish' !== $post->post_status ) {
				esc_html_e( 'Publish a post to see data from GSC.', 'surferseo' );
			} else {
				esc_html_e( 'Relax while we\'re gathering your data.', 'surferseo' );
			}
		}
		echo '</div>';
	}

		/**
		 * Renders position monitor column values.
		 *
		 * @param int $post_id Post ID.
		 */
	private function render_position_monitor_column_values( $post_id ) {

		$post_performance = surfer_get_last_post_traffic_by_id( $post_id );

		$last_update_date     = $this->return_period_based_on_gathering_date( $post_performance['data_gathering_date'] );
		$previous_update_date = $this->return_period_based_on_gathering_date( gmdate( 'Y-m-d', strtotime( 'previous monday', strtotime( $this->get_previous_period_date( $post_id ) ) ) ) );

		$draft_id       = get_post_meta( $post_id, 'surfer_draft_id', true );
		$scrape_status  = get_post_meta( $post_id, 'surfer_scrape_ready', true );
		$permalink_hash = get_post_meta( $post_id, 'surfer_permalink_hash', true );
		$content        = get_the_content( null, false, $post_id );

		$stats = array(
			'clicks'            => $post_performance['clicks'],
			'clicksPrev'        => $post_performance['clicks'] - $post_performance['clicks_change'],
			'position'          => $post_performance['position'],
			'positionPrev'      => $post_performance['position'] - $post_performance['position_change'],
			'impressions'       => $post_performance['impressions'],
			'impressionsPrev'   => $post_performance['impressions'] - $post_performance['impressions_change'],
			'positionWithSufix' => surfer_add_numerical_suffix( $post_performance['position'] ),
		);

		ob_start();
			require Surfer()->get_basedir() . '/templates/admin/posts-list-gsc-column.php';
		$html = ob_get_clean();

		$additional_allowed_html = array(
			'svg'  => array(
				'xmlns'        => array(),
				'fill'         => array(),
				'viewbox'      => array(),
				'role'         => array(),
				'aria-hidden'  => array(),
				'focusable'    => array(),
				'stroke-width' => array(),
				'stroke'       => array(),
				'class'        => array(),
			),
			'path' => array(
				'd'               => array(),
				'fill'            => array(),
				'stroke-linecap'  => array(),
				'stroke-linejoin' => array(),

			),
			'b'    => array(),
			'br'   => array(),
		);

		echo wp_kses( $html, array_merge( wp_kses_allowed_html( 'post' ), $additional_allowed_html ) );
	}
}
