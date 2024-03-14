<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    ABR
 * @subpackage ABR/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    ABR
 * @subpackage ABR/admin
 */
class ABR_Admin {

	/**
	 * The ID of this plugin.

	 * @access private
	 * @var string $abr The ID of this plugin.
	 */
	private $abr;

	/**
	 * The version of this plugin.

	 * @access private
	 * @var string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $abr     The name of this plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $abr, $version ) {
		$this->abr     = $abr;
		$this->version = $version;
	}

	/**
	 * Register admin page
	 *
	 * @since 1.0.0
	 */
	public function register_options_page() {
		add_options_page( esc_html__( 'Reviews', 'absolute-reviews' ), esc_html__( 'Reviews', 'absolute-reviews' ), 'manage_options', 'absolute-reviews', array( $this, 'build_options_page' ) );
	}

	/**
	 * Build admin page
	 *
	 * @since 1.0.0
	 */
	public function build_options_page() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient rights to view this page.', 'absolute-reviews' ) );
		}

		$indicators = abr_default_indicators();

		$this->save_options_page();
		?>

			<div class="wrap abr-wrap">
				<h2><?php esc_html_e( 'Reviews Settings', 'absolute-reviews' ); ?></h2>

				<div class="abr-settings">
					<form method="post">
						<h3><?php esc_html_e( 'Indicators of the progress bar', 'absolute-reviews' ); ?></h3>
						<table class="form-table">
							<tbody>
								<?php
								for ( $index = 1; $index <= 10; $index++ ) {
									?>
									<tr>
										<th scope="row">
											<label for="abr_review_indicator_label_<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Indicator', 'absolute-reviews' ); ?> <?php echo esc_attr( $index ); ?></label>
											<p class="description"><?php esc_html_e( 'Default:', 'absolute-reviews' ); ?> <?php echo esc_html( $indicators[ $index ]['name'] ); ?></p>
										</th>
										<td>
											<input class="regular-text" id="abr_review_indicator_label_<?php echo esc_attr( $index ); ?>" placeholder="<?php esc_html_e( 'Label', 'absolute-reviews' ); ?>" name="abr_review_indicator_label_<?php echo esc_attr( $index ); ?>" type="text" value="<?php echo esc_attr( get_option( "abr_review_indicator_label_{$index}", $indicators[ $index ]['name'] ) ); ?>"><br>
										</td>
									</tr>
								<?php } ?>
								<tr>
									<th scope="row">
										<label for="abr_review_disable_indicators"><?php esc_html_e( 'Disable all indicators on the front end', 'absolute-reviews' ); ?></label>
									</th>
									<td>
										<input class="regular-text" id="abr_review_disable_indicators" name="abr_review_disable_indicators[]" type="checkbox" value="true" <?php checked( (bool) get_option( 'abr_review_disable_indicators', false ) ); ?>>
									</td>
								</tr>
							</tbody>
						</table>

						<h3><?php esc_html_e( 'Supported Post Types', 'absolute-reviews' ); ?></h3>

						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row">
										<label for="abr_review_post_types"><?php esc_html_e( 'Enable reviews for the following post types', 'absolute-reviews' ); ?></label>
									</th>
									<td>
										<?php
										$post_types = get_post_types(
											array(
												'publicly_queryable' => 1,
												'_builtin' => false,
											)
										);

										unset( $post_types['attachment'] );

										// Merge post types.
										$post_types = array_merge( abr_default_post_types(), $post_types );

										// Get types of option.
										$option_types = get_option( 'abr_review_post_types', abr_default_post_types() );

										foreach ( $post_types as $post_type ) {
											?>
											<p>
												<label><input class="regular-text" id="abr_review_post_types" name="abr_review_post_types[]" type="checkbox" value="<?php echo esc_attr( $post_type ); ?>" <?php echo in_array( $post_type, $option_types, true ) ? 'checked' : ''; ?>> <?php echo esc_html( $post_type ); ?></label>
											</p>
											<?php
										}
										?>
									</td>
								</tr>
							</tbody>
						</table>

						<?php wp_nonce_field(); ?>

						<p class="submit"><input class="button button-primary" name="save_settings" type="submit" value="<?php esc_html_e( 'Save changes', 'absolute-reviews' ); ?>" /></p>
					</form>
				</div>
			</div>
		<?php
	}

	/**
	 * Settings save
	 *
	 * @since 1.0.0
	 */
	protected function save_options_page() {
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'] ) ) { // Input var ok; sanitization ok.
			return;
		}

		if ( isset( $_POST['save_settings'] ) ) { // Input var ok.
			for ( $index = 1; $index <= 10; $index++ ) {
				if ( isset( $_POST[ 'abr_review_indicator_label_' . $index ] ) ) { // Input var ok.
					update_option( "abr_review_indicator_label_{$index}", sanitize_text_field( wp_unslash( $_POST[ 'abr_review_indicator_label_' . $index ] ) ) ); // Input var ok.
				}
			}

			if ( isset( $_POST['abr_review_post_types'] ) ) { // Input var ok; sanitization ok.

				$post_types = is_array( $_POST['abr_review_post_types'] ) ? $_POST['abr_review_post_types'] : array(); // Input var ok; sanitization ok.

				update_option( 'abr_review_post_types', array_map( 'sanitize_text_field', $post_types ) );
			} else {
				update_option( 'abr_review_post_types', array() );
			}

			if ( isset( $_POST['abr_review_disable_indicators'] ) ) { // Input var ok.
				update_option( 'abr_review_disable_indicators', true );
			} else {
				update_option( 'abr_review_disable_indicators', false );
			}

			printf( '<div id="message" class="updated fade"><p><strong>%s</strong></p></div>', esc_html__( 'Settings saved.', 'absolute-reviews' ) );
		}
	}

	/**
	 * Addd new Meta Box for Review.
	 */
	public function metabox_review_register() {
		// Get types of option.
		$option_types = get_option( 'abr_review_post_types', abr_default_post_types() );

		if ( $option_types ) {
			add_meta_box( 'abr_review_metabox', esc_html__( 'Review Options', 'absolute-reviews' ), array( $this, 'metabox_review_callback' ), $option_types, 'normal', 'high', null );
		}
	}

	/**
	 * Callback for Review Meta Box.
	 *
	 * @param object $post Object of post.
	 */
	public function metabox_review_callback( $post ) {

		$review_settings             = abr_get_post_metadata( $post->ID, '_abr_review_settings', true );
		$review_heading              = abr_get_post_metadata( $post->ID, '_abr_review_heading', true );
		$review_desc                 = abr_get_post_metadata( $post->ID, '_abr_review_desc', true );
		$review_legend               = abr_get_post_metadata( $post->ID, '_abr_review_legend', true );
		$review_type                 = abr_get_post_metadata( $post->ID, '_abr_review_type', true, 'percentage' );
		$review_items                = abr_get_post_metadata( $post->ID, '_abr_review_items', true, array() );
		$review_main_scale           = abr_get_post_metadata( $post->ID, '_abr_review_main_scale', true, true );
		$review_auto_score           = abr_get_post_metadata( $post->ID, '_abr_review_auto_score', true, true );
		$review_total_score          = abr_get_post_metadata( $post->ID, '_abr_review_total_score', true );
		$review_pros_heading         = abr_get_post_metadata( $post->ID, '_abr_review_pros_heading', true );
		$review_pros_items           = abr_get_post_metadata( $post->ID, '_abr_review_pros_items', true, array() );
		$review_cons_heading         = abr_get_post_metadata( $post->ID, '_abr_review_cons_heading', true );
		$review_cons_items           = abr_get_post_metadata( $post->ID, '_abr_review_cons_items', true, array() );
		$review_schema_heading       = abr_get_post_metadata( $post->ID, '_abr_review_schema_heading', true );
		$review_schema_desc          = abr_get_post_metadata( $post->ID, '_abr_review_schema_desc', true );
		$review_schema_author        = abr_get_post_metadata( $post->ID, '_abr_review_schema_author', true );
		$review_schema_author_custom = abr_get_post_metadata( $post->ID, '_abr_review_schema_author_custom', true );
		?>
			<div class="abr-metabox-wrap review-wrap">
				<input type="hidden" name="abr_review_action" value="1">

				<?php wp_nonce_field( 'abr_review_meta_nonce', 'abr_review_meta_nonce' ); ?>

				<div class="abr-metabox-switcher">
					<input class="abr-metabox-checkbox" type="checkbox" name="abr_review_settings" value="1" <?php checked( $review_settings ); ?>>

					<div class="abr-metabox-switch">
						<span class="abr-metabox-switch-on"></span>
						<span class="abr-metabox-switch-off"></span>
						<span class="abr-metabox-switch-slider"></span>
					</div>

					<?php esc_html_e( 'Enable Review', 'absolute-reviews' ); ?>
				</div>

				<div class="abr-metabox-tabs" <?php checked( $review_settings ); ?>>
					<ul class="abr-metabox-tabs-navigation">
						<li><a href="#review-tab-general"><?php esc_html_e( 'General', 'absolute-reviews' ); ?></a></li>
						<li><a href="#review-tab-criteria"><?php esc_html_e( 'Criteria', 'absolute-reviews' ); ?></a></li>
						<li><a href="#review-tab-pros-cons"><?php esc_html_e( 'Pros / Cons', 'absolute-reviews' ); ?></a></li>
						<li><a href="#review-tab-scheme"><?php esc_html_e( 'Schema Attributes', 'absolute-reviews' ); ?></a></li>
					</ul>
					<div class="abr-metabox-tabs-content">
						<div id="review-tab-general">
							<div class="abr-metabox-field">
								<div class="abr-metabox-label">
									<label for="abr_review_heading"><?php esc_html_e( 'Heading', 'absolute-reviews' ); ?></label>
								</div>
								<div class="abr-metabox-input">
									<input type="text" id="abr_review_heading" name="abr_review_heading" value="<?php echo esc_attr( $review_heading ); ?>" />
								</div>
							</div>
							<div class="abr-metabox-field">
								<div class="abr-metabox-label">
									<label for="abr_review_desc"><?php esc_html_e( 'Description', 'absolute-reviews' ); ?></label>
								</div>
								<div class="abr-metabox-input">
									<textarea id="abr_review_desc" name="abr_review_desc" rows="6"><?php echo esc_html( $review_desc ); ?></textarea>
								</div>
							</div>
							<div class="abr-metabox-field">
								<div class="abr-metabox-label">
									<label for="abr_review_legend"><?php esc_html_e( 'Legend', 'absolute-reviews' ); ?></label>
								</div>
								<div class="abr-metabox-input">
									<textarea id="abr_review_legend" name="abr_review_legend" rows="3"><?php echo wp_kses_post( $review_legend ); ?></textarea>
								</div>
							</div>
							<div class="abr-metabox-field">
								<div class="abr-metabox-label">
									<label for="abr_review_type"><?php esc_html_e( 'Type', 'absolute-reviews' ); ?></label>
								</div>
								<div class="abr-metabox-input">
									<select id="abr_review_type" name="abr_review_type">
										<option value="percentage" <?php selected( $review_type, 'percentage' ); ?>><?php esc_html_e( 'Percentage (1-100%)', 'absolute-reviews' ); ?></option>
										<option value="point-5" <?php selected( $review_type, 'point-5' ); ?>><?php esc_html_e( 'Points (1-5)', 'absolute-reviews' ); ?></option>
										<option value="point-10" <?php selected( $review_type, 'point-10' ); ?>><?php esc_html_e( 'Points (1-10)', 'absolute-reviews' ); ?></option>
										<option value="star" <?php selected( $review_type, 'star' ); ?>><?php esc_html_e( 'Stars (1-5)', 'absolute-reviews' ); ?></option>
									</select>
								</div>
							</div>
							<div class="abr-metabox-field">
								<div class="abr-metabox-label">
									<label for="abr_review_main_scale"><?php esc_html_e( 'Main Scale', 'absolute-reviews' ); ?></label>
								</div>
								<div class="abr-metabox-input">
									<label><input type="checkbox" id="abr_review_main_scale" name="abr_review_main_scale" value="1" <?php checked( $review_main_scale ); ?>></label>
								</div>
							</div>
							<div class="abr-metabox-field review-field-auto-score">
								<div class="abr-metabox-label">
									<label for="abr_review_auto_score"><?php esc_html_e( 'Auto Сalculate Total Score', 'absolute-reviews' ); ?></label>
								</div>
								<div class="abr-metabox-input">
									<label><input type="checkbox" id="abr_review_auto_score" name="abr_review_auto_score" value="1" <?php checked( $review_auto_score ); ?>></label>
								</div>
							</div>
							<div class="abr-metabox-field review-field-total-score" <?php checked( $review_auto_score ); ?>>
								<div class="abr-metabox-label">
									<label for="abr_review_total_score"><?php esc_html_e( 'Total Score', 'absolute-reviews' ); ?></label>
								</div>
								<div class="abr-metabox-input">
									<input type="text" id="abr_review_total_score" name="abr_review_total_score" value="<?php echo esc_attr( $review_total_score ); ?>" />
								</div>
							</div>
						</div>
						<div id="review-tab-criteria">
							<div class="abr-metabox-repeater">
								<table class="abr-metabox-repeater-table">
									<tbody>
										<tr class="row hidden">
											<td>
												<div class="row-content">
													<div class="row-topbar">
														<a href="#" class="btn-remove-row delete"><?php esc_html_e( 'Remove', 'absolute-reviews' ); ?></a>
														<div class="handlediv" title="<?php esc_html_e( 'Click to toggle', 'absolute-reviews' ); ?>"></div>
														<strong class="signature"><span><?php echo esc_html__( 'Criterion', 'absolute-reviews' ); ?></span></strong>
													</div>

													<div class="row-fields">
														<div class="row-body review-field-grid">
															<p class="review-field-criterion-name">
																<label><?php esc_html_e( 'Name', 'absolute-reviews' ); ?>:<br>
																<input disabled type="text" class="attribute-name" name="abr_review_items[name][]" placeholder="<?php esc_html_e( 'Name', 'absolute-reviews' ); ?>" data-label="<?php esc_html_e( 'Criterion', 'absolute-reviews' ); ?>"></label>
															</p>

															<p class="review-field-criterion-number">
																<label><?php esc_html_e( 'Value', 'absolute-reviews' ); ?>:<br>
																<input disabled type="number" name="abr_review_items[val][]" min="0" step="1"></label>
															</p>

															<p class="review-field-criterion-desc">
																<label><?php esc_html_e( 'Description', 'absolute-reviews' ); ?>:<br>
																<textarea disabled name="abr_review_items[desc][]" placeholder="<?php esc_html_e( 'Description', 'absolute-reviews' ); ?>" row="4"></textarea>
																</label>
															</p>
														</div>
													</div>
												</div>
											</td>
										</tr>

										<?php
										if ( isset( $review_items['name'] ) && $review_items['name'] ) {
											foreach ( $review_items['name'] as $key => $name ) {
												?>
												<tr class="row">
													<td>
														<div class="row-content">
															<div class="row-topbar closed">
																<a href="#" class="btn-remove-row delete"><?php esc_html_e( 'Remove', 'absolute-reviews' ); ?></a>
																<div class="handlediv" title="<?php esc_html_e( 'Click to toggle', 'absolute-reviews' ); ?>"></div>
																<strong class="signature"><?php echo (string) $review_items['name'][ $key ] ? esc_html( $review_items['name'][ $key ] ) : '<span>' . esc_html__( 'Criterion', 'absolute-reviews' ) . '</span>'; ?></strong>
															</div>

															<div class="row-fields" style="display:none">
																<div class="row-body review-field-grid">
																	<p class="review-field-criterion-name">
																		<label><?php esc_html_e( 'Name', 'absolute-reviews' ); ?>:<br>
																		<input type="text" class="attribute-name" name="abr_review_items[name][]" placeholder="<?php esc_html_e( 'Name', 'absolute-reviews' ); ?>" data-label="<?php esc_html_e( 'Criterion', 'absolute-reviews' ); ?>" value="<?php echo esc_attr( $review_items['name'][ $key ] ); ?>"></label>
																	</p>

																	<p class="review-field-criterion-number">
																		<label><?php esc_html_e( 'Value', 'absolute-reviews' ); ?>:<br>
																		<input type="number" name="abr_review_items[val][]" value="<?php echo esc_attr( $review_items['val'][ $key ] ); ?>" min="0" step="1"></label>
																	</p>

																	<p class="review-field-criterion-desc">
																		<label><?php esc_html_e( 'Description', 'absolute-reviews' ); ?>:<br>
																		<textarea name="abr_review_items[desc][]" placeholder="<?php esc_html_e( 'Description', 'absolute-reviews' ); ?>" row="4"><?php echo esc_attr( $review_items['desc'][ $key ] ); ?></textarea>
																		</label>
																	</p>
																</div>
															</div>
														</div>
													</td>
												</tr>
												<?php
											}
										}
										?>
									</tbody>
								</table>
								<a class="btn-add-row button button-primary" href="#" data-event="btn-add-row"><?php esc_html_e( 'Add new criterion', 'absolute-reviews' ); ?></a>
							</div>
						</div>
						<div id="review-tab-pros-cons">
							<div class="abr-metabox-field">
								<div class="abr-metabox-label">
									<label for="abr_review_pros_heading"><?php esc_html_e( 'Pros Heading', 'absolute-reviews' ); ?></label>
								</div>
								<div class="abr-metabox-input">
									<input type="text" id="abr_review_pros_heading" name="abr_review_pros_heading" placeholder="<?php esc_html_e( 'The Good', 'absolute-reviews' ); ?>" value="<?php echo esc_attr( $review_pros_heading ); ?>" />
								</div>
							</div>
							<div class="abr-metabox-field">
								<div class="abr-metabox-label">
									<label for="abr_review_pros_heading"><?php esc_html_e( 'Pros List', 'absolute-reviews' ); ?></label>
								</div>
								<div class="abr-metabox-input">
									<div class="abr-metabox-repeater">
										<table class="abr-metabox-repeater-table review-repeater-simple">
											<tbody>
												<tr class="row hidden">
													<td>
														<div class="row-content">
															<div class="row-fields">
																<div class="row-body">
																	<div class="row-handle">
																		<span class="dashicons dashicons-menu-alt"></span>
																	</div>

																	<input disabled type="text" class="attribute-name" name="abr_review_pros_items[name][]" placeholder="<?php esc_html_e( 'Name', 'absolute-reviews' ); ?>" data-label="<?php esc_html_e( 'Item', 'absolute-reviews' ); ?>">

																	<a href="#" class="btn-remove-row delete">
																		<span class="dashicons dashicons-no-alt"></span>
																	</a>
																</div>
															</div>
														</div>
													</td>
												</tr>

												<?php
												if ( isset( $review_pros_items['name'] ) && $review_pros_items['name'] ) {
													foreach ( $review_pros_items['name'] as $key => $name ) {
														?>
														<tr class="row">
															<td>
																<div class="row-content">
																	<div class="row-fields">
																		<div class="row-body">
																			<div class="row-handle">
																				<span class="dashicons dashicons-menu-alt"></span>
																			</div>

																			<input type="text" class="attribute-name" name="abr_review_pros_items[name][]" placeholder="<?php esc_html_e( 'Name', 'absolute-reviews' ); ?>" data-label="<?php esc_html_e( 'Item', 'absolute-reviews' ); ?>" value="<?php echo esc_attr( $review_pros_items['name'][ $key ] ); ?>"></label>

																			<a href="#" class="btn-remove-row delete">
																				<span class="dashicons dashicons-no-alt"></span>
																			</a>
																		</div>
																	</div>
																</div>
															</td>
														</tr>
														<?php
													}
												}
												?>
											</tbody>
										</table>
										<a class="btn-add-row button button-primary" href="#" data-event="btn-add-row"><?php esc_html_e( 'Add new pros item', 'absolute-reviews' ); ?></a>
									</div>
								</div>
							</div>
							<br><hr><br>
							<div class="abr-metabox-field">
								<div class="abr-metabox-label">
									<label for="abr_review_cons_heading"><?php esc_html_e( 'Cons Heading', 'absolute-reviews' ); ?></label>
								</div>
								<div class="abr-metabox-input">
									<input type="text" id="abr_review_cons_heading" name="abr_review_cons_heading" placeholder="<?php esc_html_e( 'The Bad', 'absolute-reviews' ); ?>" value="<?php echo esc_attr( $review_cons_heading ); ?>" />
								</div>
							</div>
							<div class="abr-metabox-field">
								<div class="abr-metabox-label">
									<label for="abr_review_cons_heading"><?php esc_html_e( 'Cons List', 'absolute-reviews' ); ?></label>
								</div>
								<div class="abr-metabox-input">
									<div class="abr-metabox-repeater">
										<table class="abr-metabox-repeater-table review-repeater-simple">
											<tbody>
												<tr class="row hidden">
													<td>
														<div class="row-content">
															<div class="row-fields">
																<div class="row-body">
																	<div class="row-handle">
																		<span class="dashicons dashicons-menu-alt"></span>
																	</div>

																	<input disabled type="text" class="attribute-name" name="abr_review_cons_items[name][]" placeholder="<?php esc_html_e( 'Name', 'absolute-reviews' ); ?>" data-label="<?php esc_html_e( 'Item', 'absolute-reviews' ); ?>">

																	<a href="#" class="btn-remove-row delete">
																		<span class="dashicons dashicons-no-alt"></span>
																	</a>
																</div>
															</div>
														</div>
													</td>
												</tr>

												<?php
												if ( isset( $review_cons_items['name'] ) && $review_cons_items['name'] ) {
													foreach ( $review_cons_items['name'] as $key => $name ) {
														?>
														<tr class="row">
															<td>
																<div class="row-content">
																	<div class="row-fields">
																		<div class="row-body">
																			<div class="row-handle">
																				<span class="dashicons dashicons-menu-alt"></span>
																			</div>

																			<input type="text" class="attribute-name" name="abr_review_cons_items[name][]" placeholder="<?php esc_html_e( 'Name', 'absolute-reviews' ); ?>" data-label="<?php esc_html_e( 'Item', 'absolute-reviews' ); ?>" value="<?php echo esc_attr( $review_cons_items['name'][ $key ] ); ?>"></label>

																			<a href="#" class="btn-remove-row delete">
																				<span class="dashicons dashicons-no-alt"></span>
																			</a>
																		</div>
																	</div>
																</div>
															</td>
														</tr>
														<?php
													}
												}
												?>
											</tbody>
										</table>
										<a class="btn-add-row button button-primary" href="#" data-event="btn-add-row"><?php esc_html_e( 'Add new cons item', 'absolute-reviews' ); ?></a>
									</div>
								</div>
							</div>
						</div>
						<div id="review-tab-scheme">
							<div class="abr-metabox-field">
								<div class="abr-metabox-label">
									<label for="abr_review_schema_heading"><?php esc_html_e( 'Item Reviewed', 'absolute-reviews' ); ?></label>
									<p class="description"><?php esc_html_e( 'Heading will be used if blank.', 'absolute-reviews' ); ?></p>
								</div>
								<div class="abr-metabox-input">
									<input type="text" id="abr_review_schema_heading" name="abr_review_schema_heading" value="<?php echo esc_attr( $review_schema_heading ); ?>" />
								</div>
							</div>
							<div class="abr-metabox-field">
								<div class="abr-metabox-label">
									<label for="abr_review_schema_desc"><?php esc_html_e( 'Review Text', 'absolute-reviews' ); ?></label>
									<p class="description"><?php esc_html_e( 'Description will be used if blank.', 'absolute-reviews' ); ?></p>
								</div>
								<div class="abr-metabox-input">
									<textarea id="abr_review_schema_desc" name="abr_review_schema_desc" rows="6"><?php echo esc_html( $review_schema_desc ); ?></textarea>
								</div>
							</div>
							<div class="abr-metabox-field">
								<div class="abr-metabox-label">
									<label for="abr_review_schema_author"><?php esc_html_e( 'Review Author', 'absolute-reviews' ); ?></label>
								</div>
								<div class="abr-metabox-input">
									<select id="abr_review_schema_author" name="abr_review_schema_author">
										<?php
										$user = get_userdata( $post->post_author );

										if ( $user->user_login ) {
											?>
											<option value="<?php echo esc_attr( $user->user_login ); ?>" <?php selected( $review_schema_author, $user->user_login ); ?>>
												<?php echo esc_html( $user->user_login ); ?>
											</option>
											<?php
										}

										if ( $user->first_name ) {
											?>
											<option value="<?php echo esc_attr( $user->first_name ); ?>" <?php selected( $review_schema_author, $user->first_name ); ?>>
												<?php echo esc_html( $user->first_name ); ?>
											</option>
											<?php
										}

										if ( $user->last_name ) {
											?>
											<option value="<?php echo esc_attr( $user->last_name ); ?>" <?php selected( $review_schema_author, $user->last_name ); ?>>
												<?php echo esc_html( $user->last_name ); ?>
											</option>
											<?php
										}

										if ( $user->first_name && $user->last_name ) {
											$user_var_once   = $user->first_name . ' ' . $user->last_name;
											$user_var_second = $user->last_name . ' ' . $user->first_name;
											?>
											<option value="<?php echo esc_attr( $user_var_once ); ?>" <?php selected( $review_schema_author, $user_var_once ); ?>>
												<?php echo esc_html( $user_var_once ); ?>
											</option>
											<option value="<?php echo esc_attr( $user_var_second ); ?>" <?php selected( $review_schema_author, $user_var_second ); ?>>
												<?php echo esc_html( $user_var_second ); ?>
											</option>
											<?php
										}
										?>
										<option value="custom" <?php selected( $review_schema_author, 'custom' ); ?>><?php esc_html_e( 'Custom', 'absolute-reviews' ); ?></option>
									</select>
								</div>
							</div>
							<div class="abr-metabox-field review-field-schema-author-custom" <?php checked( $review_schema_author, 'custom' ); ?>">
								<div class="abr-metabox-label">
									<label for="abr_review_schema_author_custom"><?php esc_html_e( 'Custom Author', 'absolute-reviews' ); ?></label>
								</div>
								<div class="abr-metabox-input">
									<input type="text" id="abr_review_schema_author_custom" name="abr_review_schema_author_custom" value="<?php echo esc_attr( $review_schema_author_custom ); ?>" />
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		<?php
	}

	/**
	 * Save meta tags by post
	 *
	 * @param int    $post_id Post ID.
	 * @param object $post    Post Object.
	 */
	public function metabox_review_save( $post_id, $post ) {

		// Break if doing autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Break if current user can't edit this post.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Break if this post revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( ! isset( $_POST['abr_review_meta_nonce'] ) || ! wp_verify_nonce( $_POST['abr_review_meta_nonce'], 'abr_review_meta_nonce' ) ) { // Input var ok; sanitization ok.
			return;
		}

		if ( ! isset( $_POST['abr_review_action'] ) || 1 !== (int) $_POST['abr_review_action'] ) { // Input var ok; sanitization ok.
			return;
		}

		if ( isset( $_POST['abr_review_settings'] ) ) { // Input var ok; sanitization ok.
			update_post_meta( $post_id, '_abr_review_settings', 1 );
		} else {
			update_post_meta( $post_id, '_abr_review_settings', '' );
		}

		// Check Switch Metabox.
		if ( isset( $_POST['abr_review_settings'] ) ) { // Input var ok; sanitization ok.

			$review_type  = null;
			$review_items = null;

			if ( isset( $_POST['abr_review_heading'] ) ) {
				$review_heading = sanitize_text_field( $_POST['abr_review_heading'] ); // Input var ok; sanitization ok.

				update_post_meta( $post_id, '_abr_review_heading', $review_heading );
			}

			if ( isset( $_POST['abr_review_type'] ) ) {
				$review_type = sanitize_text_field( $_POST['abr_review_type'] ); // Input var ok; sanitization ok.

				update_post_meta( $post_id, '_abr_review_type', $review_type );
			}

			if ( isset( $_POST['abr_review_items'] ) ) { // Input var ok; sanitization ok.
				$review_items = map_deep( $_POST['abr_review_items'], 'sanitize_text_field' ); // Input var ok; sanitization ok.

				if ( isset( $_POST['abr_review_type'] ) && isset( $review_items['val'] ) ) { // Input var ok; sanitization ok.
					switch ( $_POST['abr_review_type'] ) { // Input var ok; sanitization ok.
						case 'percentage':
							$max = '100';
							break;
						case 'point-5':
							$max = '5';
							break;
						case 'point-10':
							$max = '10';
							break;
						case 'star':
							$max = '5';
							break;
					}

					if ( $review_items['val'] ) {
						foreach ( $review_items['val'] as $key => $value ) {
							if ( $value > $max ) {
								$review_items['val'][ $key ] = $max;
							}
						}
					}
				}

				update_post_meta( $post_id, '_abr_review_items', $review_items );
			} else {
				update_post_meta( $post_id, '_abr_review_items', array() );
			}

			if ( isset( $_POST['abr_review_main_scale'] ) ) { // Input var ok; sanitization ok.
				update_post_meta( $post_id, '_abr_review_main_scale', 1 );
			} else {
				update_post_meta( $post_id, '_abr_review_main_scale', '' );
			}

			if ( isset( $_POST['abr_review_auto_score'] ) ) { // Input var ok; sanitization ok.
				update_post_meta( $post_id, '_abr_review_auto_score', 1 );
			} else {
				update_post_meta( $post_id, '_abr_review_auto_score', '' );
			}

			if ( isset( $_POST['abr_review_total_score'] ) ) {
				$review_total_score = sanitize_text_field( $_POST['abr_review_total_score'] ); // Input var ok; sanitization ok.

				update_post_meta( $post_id, '_abr_review_total_score', $review_total_score );
			}

			if ( isset( $_POST['abr_review_desc'] ) ) { // Input var ok; sanitization ok.
				$review_desc = sanitize_text_field( $_POST['abr_review_desc'] ); // Input var ok; sanitization ok.

				update_post_meta( $post_id, '_abr_review_desc', $review_desc );
			}

			if ( isset( $_POST['abr_review_pros_heading'] ) ) {
				$review_pros_heading = sanitize_text_field( $_POST['abr_review_pros_heading'] ); // Input var ok; sanitization ok.

				update_post_meta( $post_id, '_abr_review_pros_heading', $review_pros_heading );
			}

			if ( isset( $_POST['abr_review_pros_items'] ) ) { // Input var ok; sanitization ok.
				$review_pros_items = map_deep( $_POST['abr_review_pros_items'], 'sanitize_text_field' ); // Input var ok; sanitization ok.

				update_post_meta( $post_id, '_abr_review_pros_items', $review_pros_items );
			} else {
				update_post_meta( $post_id, '_abr_review_pros_items', array() );
			}

			if ( isset( $_POST['abr_review_cons_heading'] ) ) {
				$review_cons_heading = sanitize_text_field( $_POST['abr_review_cons_heading'] ); // Input var ok; sanitization ok.

				update_post_meta( $post_id, '_abr_review_cons_heading', $review_cons_heading );
			}

			if ( isset( $_POST['abr_review_cons_items'] ) ) { // Input var ok; sanitization ok.
				$review_cons_items = map_deep( $_POST['abr_review_cons_items'], 'sanitize_text_field' ); // Input var ok; sanitization ok.

				update_post_meta( $post_id, '_abr_review_cons_items', $review_cons_items );
			} else {
				update_post_meta( $post_id, '_abr_review_cons_items', array() );
			}

			if ( isset( $_POST['abr_review_legend'] ) ) { // Input var ok; sanitization ok.
				$review_legend = sanitize_text_field( $_POST['abr_review_legend'] ); // Input var ok; sanitization ok.

				update_post_meta( $post_id, '_abr_review_legend', $review_legend );
			}

			if ( isset( $_POST['abr_review_schema_heading'] ) ) {
				$review_schema_heading = sanitize_text_field( $_POST['abr_review_schema_heading'] ); // Input var ok; sanitization ok.

				update_post_meta( $post_id, '_abr_review_schema_heading', $review_schema_heading );
			}

			if ( isset( $_POST['abr_review_schema_desc'] ) ) {
				$review_schema_desc = sanitize_text_field( $_POST['abr_review_schema_desc'] ); // Input var ok; sanitization ok.

				update_post_meta( $post_id, '_abr_review_schema_desc', $review_schema_desc );
			}

			if ( isset( $_POST['abr_review_schema_author'] ) ) {
				$review_schema_author = sanitize_text_field( $_POST['abr_review_schema_author'] ); // Input var ok; sanitization ok.

				update_post_meta( $post_id, '_abr_review_schema_author', $review_schema_author );
			}

			if ( isset( $_POST['abr_review_schema_author_custom'] ) ) {
				$review_schema_author_custom = sanitize_text_field( $_POST['abr_review_schema_author_custom'] ); // Input var ok; sanitization ok.

				update_post_meta( $post_id, '_abr_review_schema_author_custom', $review_schema_author_custom );
			}

			// Review total score.
			$total_score = (float) $this->calc_review_total_score( $post_id, $review_type, $review_items );

			/* ------------------------ */

			// Save total score number.
			$review_type = abr_get_post_metadata( $post_id, '_abr_review_type', true, 'percentage' );

			$review_auto_score = abr_get_post_metadata( $post_id, '_abr_review_auto_score', true, true );

			if ( empty( $review_auto_score ) ) {
				$total_score = (float) abr_get_post_metadata( $post_id, '_abr_review_total_score', true );
			}

			// Vars.
			switch ( $review_type ) {
				case 'percentage':
					$max = 100;
					break;
				case 'point-5':
					$max = 5;
					break;
				case 'point-10':
					$max = 10;
					break;
				case 'star':
					$max = 5;
					break;
			}

			// Validate value.
			switch ( $review_type ) {
				case 'percentage':
				case 'point-5':
				case 'point-10':
					$total_score = ( $total_score <= $max ) ? round( $total_score ) : $max;
					break;
				case 'star':
					$total_score = ( $total_score <= $max ) ? round( $total_score, 1 ) : $max;
					break;
			}

			update_post_meta( $post_id, '_abr_review_total_score_number', (float) $total_score );
		}
	}

	/**
	 * Calc Total Score
	 *
	 * @param int    $post_id      Post ID.
	 * @param string $review_type  Type of review.
	 * @param array  $review_items Array list items.
	 */
	public function calc_review_total_score( $post_id, $review_type, $review_items ) {

		/* Review Type */
		if ( $review_type ) {

			/* Set Score */
			switch ( $review_type ) {
				case 'star':
					$stars_count = 5;
					break;
				case 'point-5':
					$points_count = 5;
					break;
				case 'point-10':
					$points_count = 10;
					break;
			}

			if ( isset( $review_items['name'] ) && $review_items['name'] ) {
				$average = 0;

				$review_items_count = 0;

				foreach ( $review_items['name'] as $key => $item_name ) {

					if ( ! $item_name ) {
						continue;
					}

					$item_value = floatval( $review_items['val'][ $key ] );

					/* Get Item Value */
					switch ( $review_type ) {
						case 'star':
							$item_value = ( $item_value <= $stars_count ) ? $item_value : $stars_count;
							break;
						case 'point-5':
						case 'point-10':
							$item_value = ( $item_value <= $points_count ) ? $item_value : $points_count;
							break;
					}

					$review_items_count++;

					$average += floatval( $item_value );
				}

				if ( $average > 0 ) {
					$average = $average / $review_items_count;

					$average = round( $average, 1 );
				}

				return $average;
			}
		}
	}

	/**
	 * Register the stylesheets and JavaScript for the admin area.
	 *
	 * @param string $page Current page.
	 */
	public function admin_enqueue_scripts( $page ) {

		// Styles.
		wp_enqueue_style( $this->abr, abr_style( plugin_dir_url( __FILE__ ) . 'css/absolute-reviews-admin.css' ), array(), $this->version, 'all' );

		if ( in_array( $page, array( 'post.php', 'post-new.php' ), true ) ) {

			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-ui-tabs' );

			// Scripts.
			wp_enqueue_script( $this->abr, plugin_dir_url( __FILE__ ) . 'js/absolute-reviews-admin.js', array( 'jquery', 'jquery-ui-tabs', 'jquery-ui-sortable' ), $this->version, false );
		}
	}
}
