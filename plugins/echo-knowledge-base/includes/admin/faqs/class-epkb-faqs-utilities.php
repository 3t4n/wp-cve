<?php

/**
 * Various utilities for the FAQs
 */
class EPKB_FAQs_Utilities {

	/**
	 * Display the FAQs shortcode and Module
	 * @param $kb_config
	 * @param $faq_groups
	 * @param $faq_title
	 * @param bool $is_faq_schema
	 * @return false|string
	 */
	public static function display_faqs( $kb_config, $faq_groups, $faq_title, $is_faq_schema=false ) {

		// Set Icon Type from User settings
		switch ( $kb_config['faq_icon_type'] ) {
			case 'none':
				$open_icon      = '';
				$closed_icon    = '';
				break;
			case 'icon_plus_box':
				$open_icon      = 'epkbfa-minus-square';
				$closed_icon    = 'epkbfa-plus-square';
				break;
			case 'icon_plus_circle':
				$open_icon      = 'epkbfa-minus-circle';
				$closed_icon    = 'epkbfa-plus-circle';
				break;
			case 'icon_plus':
				$open_icon      = 'epkbfa-minus';
				$closed_icon    = 'epkbfa-plus';
				break;
			case 'icon_arrow_angle':
				$open_icon      = 'epkbfa-angle-down';
				$closed_icon    = 'epkbfa-angle-right';
				break;
			case 'icon_arrow_caret':
			default:
				$open_icon      = 'epkbfa-caret-down';
				$closed_icon    = 'epkbfa-caret-right';
				break;
		}

		$container_classes = self::get_faq_classes( $kb_config );

		ob_start(); ?>
		<style>	    <?php
			echo self::get_faq_styles( $kb_config ); ?>
		</style>

		<div id="epkb-ml-faqs-<?php echo strtolower( $kb_config['kb_main_page_layout'] ); ?>-layout" class="epkb-faqs-container <?php echo esc_html( implode(' ', $container_classes ) ) ?>"> <?php

			// Display the FAQs Title set in the FAQ Module or shortcode Parameter (could be empty)
			// TODO Do we need to have h2 adjustable? will user need to use H3,H4,H5,H6?
			if ( $kb_config['ml_faqs_title_location'] != 'none' && ! empty( $faq_title ) ) {    ?>
				<h2 class="epkb-faqs-title"><?php echo esc_html( $faq_title ); ?></h2>		<?php
			}

			if ( empty( $faq_groups ) ) {
				self::display_no_faqs_found_message( $kb_config );
			}

			// Loop through each Group and display its contents
			$faq_schema_json = [
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => [],
			]; ?>

			<div class="epkb-faqs-cat-content-container">				<?php 
				foreach ( $faq_groups as $faq_group_id => $faq_value ) {      ?>

					<div class="epkb-faqs-cat-container" id="epkb-faqs-cat-<?php echo esc_attr( $faq_group_id ); ?>"> <?php

						// Display FAQ Group titles if more than one group
						if ( count( $faq_groups ) > 1 ) {
							$faq_group_title = empty( $faq_value['title'] ) ? '' : $faq_value['title'];
							if ( ! empty( $faq_group_title ) ) { ?>
								<div class="epkb-faqs__cat-header">
									<h3 class="epkb-faqs__cat-header__title"><?php echo esc_html( $faq_group_title ); ?></h3>
								</div> <?php
							}
						}

						// Display this groups questions.
						foreach ( $faq_value['faqs'] as $one_faq ) {

							// add article title and content to the FAQ schema
							if ( $is_faq_schema ) {
								$faq_schema_json['mainEntity'][] = array(
									'@type' => 'Question',
									'name' => $one_faq->post_title,
									'acceptedAnswer' => array(
										'@type' => 'Answer',
										'text' => wp_strip_all_tags( $one_faq->post_content ),
									)
								);
							}   ?>
							<div class="epkb-faqs__item-container" id="epkb-faqs-article-<?php echo esc_attr( $one_faq->ID ); ?>">

								<div class="epkb-faqs__item__question">     <?php
									if ( $kb_config['faq_icon_location'] != 'no_icons' && ( $kb_config['faq_open_mode'] == 'accordion_mode' || $kb_config['faq_open_mode'] == 'toggle_mode' ) ) { ?>
										<div class="epkb-faqs__item__question__icon epkb-faqs__item__question__icon-closed epkbfa <?php echo $closed_icon; ?>"></div>
										<div class="epkb-faqs__item__question__icon epkb-faqs__item__question__icon-open epkbfa <?php echo $open_icon; ?>"></div>    <?php
									} ?>
									<div class="epkb-faqs__item__question__text"><?php echo esc_html( $one_faq->post_title ); ?></div>
								</div>

								<div class="epkb-faqs__item__answer">
									<div class="epkbs-faqs__item__answer__text">    <?php
										echo str_replace( ']]>', ']]&gt;', $one_faq->post_content );
										// TODO only for full post editors: $content = apply_filters( 'the_content', $post_content ); ?>
									</div>
								</div>

							</div>  <?php
						}

						if ( empty( $faq_value['faqs'] ) ) {    ?>
							<div class="epkb-faqs-article-coming-soon"><?php echo esc_html( $kb_config['faq_empty_msg'] ); ?></div>	<?php
						}   ?>

					</div>  <?php
				} ?>
			</div>			<?php 

			if ( $is_faq_schema ) {			?>
				<!--suppress JSUnresolvedVariable -->
				<script type="application/ld+json"><?php echo wp_json_encode( $faq_schema_json ); ?></script>   <?php
			}   ?>

		</div>	<?php

		return ob_get_clean();
	}

	private static function get_faq_classes( $kb_config ) {

		$columns = empty( $kb_config['faq_nof_columns'] ) ? '1' : $kb_config['faq_nof_columns'];
		switch ( $columns ) {
			case '1':
			default:
				$col_class = 'epkb-faqs-col-1';
				break;
			case '2':
				$col_class = 'epkb-faqs-col-2';
				break;
		}

		switch ( $kb_config['faq_question_space_between'] ) {
			case 'space_none':
				if ( $kb_config['faq_border_mode'] == 'all_around' ) {
					$space_between_questions = 'epkb-faqs-border-all-space-none';
				} else {
					$space_between_questions = 'epkb-faqs-space-none';
				}
				break;
			case 'space_small':
				$space_between_questions = 'epkb-faqs-space-small';
				break;
			case 'space_medium':
				$space_between_questions = 'epkb-faqs-space-medium';
				break;
			default:
				$space_between_questions = 'epkb-faqs-space-medium';
		}

		switch ( $kb_config['faq_compact_mode'] ) {
			case 'compact_small':
				$compact_mode = 'epkb-faqs-compact-small';
				break;
			case 'compact_medium':
				$compact_mode = 'epkb-faqs-compact-medium';
				break;
			default:
				$compact_mode = 'epkb-faqs-compact-medium';
		}

		switch ( $kb_config['faq_open_mode'] ) {
			case 'accordion_mode':
				$show_mode = 'epkb-faqs-accordion-mode';
				break;
			case 'toggle_mode':
				$show_mode = 'epkb-faqs-toggle-mode';
				break;
			case 'show_all_mode':
				$show_mode = 'epkb-faqs-show-all-mode';
				break;
			default:
				$show_mode = 'epkb-faqs-accordion-mode';
		}

		switch ( $kb_config['faq_border_style'] ) {
			case 'sharp':
				$border_style = 'epkb-faqs-border-sharp';
				break;
			case 'rounded':
				$border_style = 'epkb-faqs-border-rounded';
				break;
			default:
				$border_style = 'epkb-faqs-border-rounded';
		}

		switch ( $kb_config['faq_border_mode'] ) {
			case 'none':
				$border_mode = 'epkb-faqs-border-none';
				break;
			case 'all_around':
				$border_mode = 'epkb-faqs-border-all';
				break;
			case 'separator':
				$border_mode = 'epkb-faqs-border-separator';
				break;
			default:
				$border_mode = 'epkb-faqs-border-all';
		}

		switch ( $kb_config['faq_icon_location'] ) {
			case 'no_icons':
				$icon_location = 'epkb-faqs-icon-none';
				break;
			case 'left':
				$icon_location = 'epkb-faqs-icon-left';
				break;
			case 'right':
				$icon_location = 'epkb-faqs-icon-right';
				break;
			default:
				$icon_location = 'epkb-faqs-icon-left';
		}

		$container_classes = array(
			$show_mode,
			$space_between_questions,
			$border_style,
			$border_mode,
			$icon_location,
			$compact_mode,
			$col_class
		);

		return $container_classes;
	}

	public static function get_faq_styles( $kb_config ) {
		$style = '';

		if ( $kb_config['faq_border_mode'] == 'separator' ) {
			$style .= '
				.epkb-faqs-border-separator .epkb-faqs__item__question {
				    border-color: ' .esc_attr( $kb_config['faq_border_color'] ). '!important;
				  }
				  .epkb-faqs-border-separator .epkb-faqs__item-container--active .epkb-faqs__item__question {
				    border-color: #000000 !important; 
				  }
				';
		}

		$faq_question_background_color = empty( $kb_config['faq_question_background_color'] ) ? 'transparent' : esc_attr( $kb_config['faq_question_background_color'] );
		$faq_answer_background_color   = empty( $kb_config['faq_answer_background_color'] ) ? 'transparent' : esc_attr( $kb_config['faq_answer_background_color'] );

		$style .= '
			.epkb-faqs__item__question { color: ' . esc_attr( $kb_config['faq_question_text_color'] ) . '!important; }
			
			.epkb-faqs__item-container { border-color: ' . esc_attr( $kb_config['faq_border_color'] ) . '!important; }
			.epkb-faqs__item-container--active .epkb-faqs__item__question { border-color: ' . esc_attr( $kb_config['faq_border_color'] ) . '!important; }
			
			.epkb-faqs__item__question { background-color: ' . $faq_question_background_color . '; }
			.epkb-faqs__item__answer { background-color: ' . $faq_answer_background_color . '; }
			';

		// Display the FAQs Title set in the FAQ Module or shortcode Parameter (could be empty)
		if ( $kb_config['ml_faqs_title_location'] != 'none' && ! empty( $kb_config['ml_faqs_title_text'] ) ) {
			$style .= '.epkb-faqs-title { text-align: ' . esc_attr( $kb_config['ml_faqs_title_location'] ) . '!important; }';
		}

		return $style;
	}

	public static function get_design_settings( $design_name ) {

		$defaults = array(
			'faq_border_style'              => 'rounded',
			'faq_border_mode'               => 'all_around',
			'faq_open_mode'                 => 'accordion_mode',
			'faq_question_space_between'    => 'space_medium',
			'faq_compact_mode'              => 'compact_medium',
			'faq_border_color'              => '#e8e8e8',
			'faq_icon_location'             => 'left',
			'faq_icon_type'                 => 'icon_plus',
			'faq_nof_columns'               => '1',
			'faq_question_background_color' => '#FFFFFF',
			'faq_answer_background_color'   => '#FFFFFF',
			'faq_question_text_color'       => '#000000',
		);

		switch ( $design_name ) {
			case '1':
			default:
				$design_settings = array();
				break;
			case '2':
				$design_settings = array(
					'faq_compact_mode'              => 'compact_small',
					'faq_question_space_between'    => 'space_small',
				);
				break;
			case '3':
				$design_settings = array(
					'faq_border_style'              => 'sharp',
					'faq_icon_location'             => 'no_icons',
					'faq_question_space_between'    => 'space_none',
				);
				break;
			case '4':
				$design_settings = array(
					'faq_border_color'              => '#e8e8e8',
					'faq_icon_location'             => 'right',
				);
				break;
			case '5':
				$design_settings = array(
					'faq_border_mode'               => 'none',
					'faq_icon_type'                 => 'icon_arrow_angle',
					'faq_question_background_color' => '#e9e9e9',
					'faq_answer_background_color'   => '',
					'faq_open_mode'                 => 'show_all_mode',
				);
				break;
			case '6':
				$design_settings = array(
					'faq_border_style'              => 'sharp',
					'faq_icon_type'                 => 'icon_plus_circle',
					'faq_question_background_color' => '#e8e8e8',
					'faq_question_space_between'    => 'space_none',
				);
				break;
			case '7':
				$design_settings = array(
					'faq_border_style'              => 'sharp',
					'faq_border_mode'               => 'separator',
					'faq_icon_location'             => 'right',
					'faq_icon_type'                 => 'icon_plus_circle',
					'faq_question_background_color' => '',
					'faq_answer_background_color'   => '',
				);
				break;
			case '8':
				$design_settings = array(
					'faq_border_style'              => 'sharp',
					'faq_nof_columns'               => '2',
				);
				break;
		}

		// Overwrite defaults with design settings
		$design_settings = array_merge( $defaults, $design_settings );

		return $design_settings;
	}

	public static function get_faq_groups( $group_ids=[], $order_by='name' ) {

		$groups = get_terms( [
			'taxonomy'      => EPKB_FAQs_CPT_Setup::FAQ_CATEGORY,
			'include'       => $group_ids,
			'fields'        => 'id=>name',
			'hide_empty'    => false,
			'orderby'       => $order_by,
		] );

		return $groups;
	}

	/**
	 * Returns array of FAQs sorted by 'faqs_order_sequence'
	 * @param $faq_group_id
	 * @return array
	 */
	public static function get_sorted_group_faqs( $faq_group_id ) {

		$faqs = get_posts( [
			'post_type'         => EPKB_FAQs_CPT_Setup::FAQS_POST_TYPE,
			'posts_per_page'    => -1,
			'tax_query'         => array(
				array(
					'taxonomy'  => EPKB_FAQs_CPT_Setup::FAQ_CATEGORY,
					'field'     => 'term_id',
					'terms'     => $faq_group_id,
				)
			),
		] );

		$faq_ids = array_column( $faqs, 'ID' );
		$faqs_order_sequence = get_term_meta( $faq_group_id, 'faqs_order_sequence', true );

		// set unsorted sequence if the current sequence is empty but the Group has assigned FAQs (normally there is always user's defined sequence); ensure sequence type
		if ( ( empty( $faqs_order_sequence ) && ! empty( $faq_ids ) ) || ! is_array( $faqs_order_sequence ) ) {
			$faqs_order_sequence = $faq_ids;
		}

		// set FAQ ID as key in FAQs array
		$faqs = array_combine( $faq_ids, $faqs );

		// sort FAQs for the current Group
		$sorted_faqs = [];
		foreach ( $faqs_order_sequence as $faq_id ) {
			if ( ! array_key_exists( $faq_id, $faqs ) ) {
				continue;
			}
			$sorted_faqs[] = $faqs[$faq_id];
			unset( $faqs[$faq_id] );
		}

		return $sorted_faqs;
	}

	// prepare all groups and their questions
	public static function get_faq_groups_questions( $faq_groups ) {
		$faq_groups_questions = [];
		foreach ( $faq_groups as $faq_group_id => $faq_group_name ) {

			/* $faq_group_status = get_term_meta( $faq_group_id, 'faq_group_status', true ) == 'publish' ? 'publish' : 'draft';
			if ( empty( $faq_group_status) || $faq_group_status != 'publish' ) {
				continue;
			} */

			$faqs = self::get_sorted_group_faqs( $faq_group_id );
			/* if ( empty( $faqs ) ) {
				unset( $faq_groups[$faq_group_id] );
				continue;
			} */

			$faq_groups_questions[$faq_group_id] = ['title' => $faq_group_name, 'faqs' => $faqs];
		}

		return $faq_groups_questions;
	}

	public static function display_error( $message ) {
		if ( current_user_can( 'manage_options' ) ) {
			return __( 'FAQs shortcode: No categories with articles found.', 'echo-knowledge-base' );
		}

		return '';
	}

	/**
	 * Display message for users with access to FAQs Module settings
	 */
	private static function display_no_faqs_found_message( $kb_config ) {

		// only users with at least Editor access can see the message
		if ( ! EPKB_Admin_UI_Access::is_user_access_to_context_allowed( 'admin_eckb_access_faqs_write' ) ) {
			return;
		} ?>

		<section id="eckb-kb-faqs-not-assigned">
			<h2 class="eckb-kb-faqs-not-assigned-title"><?php esc_html_e( 'You do not have any FAQ Groups defined.', 'echo-knowledge-base' ); ?></h2>
			<div class="eckb-kb-faqs-not-assigned-body">
				<p>
					<a class="eckb-kb-faqs-not-assigned-btn" href="<?php echo esc_url( admin_url( 'edit.php?post_type=epkb_post_type_' . $kb_config['id'] . '&page=epkb-faqs#faqs-groups' ) ); ?>">
						<?php esc_html_e( 'Create FAQ Group', 'echo-knowledge-base' ); ?></a>
				</p>
			</div>
			<div class="eckb-kb-faqs-not-assigned-footer">
				<p>
					<span><?php esc_html_e( 'If you need help, please contact us', 'echo-knowledge-base' ); ?></span>
					<a href="https://www.echoknowledgebase.com/technical-support/" target="_blank"> <?php esc_html_e( 'here', 'echo-knowledge-base' ); ?></a>
				</p>
			</div>
		</section>  <?php
	}

	public static function get_design_names() {
		return [
			'1'  => __( 'Design #1', 'echo-knowledge-base' ),
			'2'  => __( 'Design #2', 'echo-knowledge-base' ),
			'3'  => __( 'Design #3', 'echo-knowledge-base' ),
			'4'  => __( 'Design #4', 'echo-knowledge-base' ),
			'5'  => __( 'Design #5', 'echo-knowledge-base' ),
			'6'  => __( 'Design #6', 'echo-knowledge-base' ),
			'7'  => __( 'Design #7', 'echo-knowledge-base' ),
			'8'  => __( 'Design #8', 'echo-knowledge-base' ),
		];
	}
}