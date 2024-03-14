<?php

namespace GSBEH;

class Shortcode {
    
    public function __construct() {
        add_shortcode( 'gs_behance_widget', [ $this, 'render' ] );
		add_shortcode( 'gs_behance', [ $this, 'shortcode' ] );
    }

    /**
     * Renders the shortcode.
     * 
     * @since  2.0.12
     * @return HTML $output The shortcode output html.
     */
    public function render( $atts ) {

		$gs_beh_user         = plugin()->helpers->getOption( 'gs_beh_user', 'gs_behance_settings', '' );
		$gs_beh_tot_projects = plugin()->helpers->getOption( 'gs_beh_tot_projects', 'gs_behance_settings', '' );
		$linkTarget          = plugin()->helpers->getOption( 'gs_beh_link_tar', 'gs_behance_settings', '_blank' );

		$atts = shortcode_atts( array(
			'userid' => $gs_beh_user,
			'count'  => $gs_beh_tot_projects,
		), $atts );

		global $wpdb;
		$table_name       = plugin()->db->get_data_table();
		$gs_behance_shots = plugin()->data->get_shots( $atts['userid'], $atts['count'], 'id', 'asc' );

		$output = '';
		$output .= '<div class="beh-widget-area">';

		if ( is_array( $gs_behance_shots ) ) {
			foreach ( $gs_behance_shots as $gs_beh_single_shot ) {
				$output .= '<div class="beh-widget-projects">';
				$output .= '<div class="beh-img-tit-cat">';
				$output .= '<img src="' . $gs_beh_single_shot['thum_image'] . '"/>';

				$output .= '<div class="beh-tit-cat">';
				$output .= '<span class="beh-proj-tit">' . $gs_beh_single_shot['name'] . '</span>';
				$output .= '<a class="beh_hover" href="' . $gs_beh_single_shot['url'] . '" target="' . $linkTarget . '">';
				$output .= '<i class="fa fa-paper-plane-o"></i>';
				$output .= '</a>';
				$output .= '</div>'; // end beh-tit-cat
				$output .= '</div>'; // end beh-img-tit-cat

				$output .= '<ul class="beh-stat">';
				$output .= '<li class="beh-app"><i class="fa fa-thumbs-o-up"></i><span class="number">' . number_format_i18n( $gs_beh_single_shot['bview'] ) . '</span></li>';
				$output .= '<li class="beh-views"><i class="fa fa-eye"></i><span class="number ">' . number_format_i18n( $gs_beh_single_shot['blike'] ) . '</span></li>';
				$output .= '<li class="beh-comments"><i class="fa fa-comment-o"></i><span class="number">' . number_format_i18n( $gs_beh_single_shot['bcomment'] ) . '</span></li>';
				$output .= '</ul>';

				$output .= '</div>'; // end beh-widget-projects

			}
		}

		do_action( 'gs_behance_custom_css' );

		$output .= '</div>';

		return $output;

	}

	/**
     * Renders the shortcode.
     * 
     * @since  2.0.12
     * @return HTML $output The shortcode output html.
     */
    public function shortcode($atts) {

        if ( ! is_array($atts) ) $atts = [];

        if ( empty($atts['id']) ) {
			return __( 'No shortcode ID found', 'gs-behance' );
		}
	
		$is_preview         = ! empty($atts['preview']);
        $shortcode_settings = plugin()->builder->get_shortcode_settings( $atts['id'], $is_preview );
            
        // Check for missing information
        if ( empty($shortcode_settings['userid']) ) {
            return '<div class="gs_beh_error">User ID is required.</div>';
        }

        // By default force mode
		$force_asset_load = true;

        if ( ! $is_preview ) {
			
			// For Asset Generator
			$main_post_id = gsBehanceAssetGenerator()->get_current_page_id();

			$asset_data   = gsBehanceAssetGenerator()->get_assets_data( $main_post_id );
	
			if ( empty($asset_data) ) {

				// Saved assets not found
				// Force load the assets for first time load
				// Generate the assets for later use
				gsBehanceAssetGenerator()->generate( $main_post_id, $shortcode_settings );

			} 
            else {

				// Saved assets found
				// Stop force loading the assets
				// Leave the job for Asset Loader
				$force_asset_load = false;
			}
		}

        $user_id = $shortcode_settings['userid'];
        $count = (int) $shortcode_settings['count'];

        $be_meta          = (array) get_option( 'be_meta', [] );
        $gs_behance_shots = plugin()->data->get_shots( $user_id, (int) $count );

        // fetch data when empty
        if ( empty( $gs_behance_shots ) ) {
            plugin()->data->maybe_save_user_id( $user_id, $count );
            plugin()->data->update_data( $user_id );
            $gs_behance_shots = plugin()->data->get_shots( $user_id, (int) $count );
        }

        $columnClasses  = plugin()->helpers->get_column_classes( $shortcode_settings['columns'], $shortcode_settings['columns_tablet'], $shortcode_settings['columns_mobile_portrait'], $shortcode_settings['columns_mobile'] );

        $output  = '';
        $output .= sprintf( "<div class='gs_beh_area %s gs-behance-wrap-%d' data-carousel-settings='%s'>", $shortcode_settings['theme'], $shortcode_settings['id'], json_encode($shortcode_settings) );

        ob_start();

        if ('gs_beh_theme1' === $shortcode_settings['theme']) {
            $template = 'gs_behance_structure_one.php';
        }

        if (wbp_fs()->is_paying_or_trial()) {

            if ('gs_beh_theme2' === $shortcode_settings['theme']) {
                $template = 'gs_behance_stats_style_1.php';
            }

            if ('gs_beh_theme2_hover' === $shortcode_settings['theme']) {
                $template = 'gs_behance_stats_style_2.php';
            }

            if ('gs_beh_theme3' === $shortcode_settings['theme']) {
                $template = 'gs_behance_hover_style_1.php';
            }

            if ('gs_beh_theme3_style2' === $shortcode_settings['theme']) {
                $template = 'gs_behance_hover_style_2.php';
            }

            if ('gs_beh_theme3_style3' === $shortcode_settings['theme']) {
                $template = 'gs_behance_hover_style_3.php';
            }

            if ('gs_beh_theme3_style4' === $shortcode_settings['theme']) {
                $template = 'gs_behance_hover_style_4.php';
            }

            if ('gs_popup_style_1' === $shortcode_settings['theme']) {
                $template = 'gs_behance_popup_style_1_and_2.php';
            }

            if ('gs_popup_style_2' === $shortcode_settings['theme']) {
                $template = 'gs_behance_popup_style_1_and_2.php';
            }

            if ('gs_beh_theme5' === $shortcode_settings['theme']) {
                $template = 'gs_behance_structure_slider_1.php';
            }

            if ('gs_beh_theme6' === $shortcode_settings['theme']) {
                $template = 'gs_behance_structure_six_profile.php';
            }

            if ('gs_beh_theme7' === $shortcode_settings['theme']) {
                $template = 'gs_behance_structure_seven_filter.php';
            }

        } else {
            $template = 'gs_behance_structure_one.php';
        }
        
        include TemplateLoader::locate_template( $template );
        $output .= ob_get_clean();

        // Fire force asset load when needed
		if ( plugin()->integrations->is_builder_preview() || $force_asset_load ) {

			gsBehanceAssetGenerator()->force_enqueue_assets( $shortcode_settings );
			wp_add_inline_script( 'gs-behance-public', "jQuery(document).trigger( 'gsbeh:scripts:reprocess' );jQuery(function() { jQuery(document).trigger( 'gsbeh:scripts:reprocess' ) })" );

			// Shortcode Custom CSS
			$css = gsBehanceAssetGenerator()->get_shortcode_custom_css( $shortcode_settings );
			if ( !empty($css) ) printf( "<style>%s</style>" , minimize_css_simple($css) );
			
			// Prefs Custom CSS
			$css = gsBehanceAssetGenerator()->get_prefs_custom_css();
			if ( !empty($css) ) printf( "<style>%s</style>" , minimize_css_simple($css) );

		}

        $output .= '</div>';

        return $output;
    }
}
