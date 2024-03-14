<?php
/**
 * Render the Loop Settings for Creative Blog module.
 *
 * @package Creative Blog Module
 */

FLBuilderModel::default_settings(
	$settings,
	array(
		'post_type' => 'post',
		'order_by'  => 'date',
		'order'     => 'DESC',
		'users'     => '',
	)
);

$settings = apply_filters( 'fl_builder_loop_settings', $settings );
// Allow extension of default Values.
do_action( 'tnit_loop_settings_before_form', $settings );
// e.g Add custom FLBuilder::render_settings_field().
?>

<div id="fl-builder-settings-section-source" class="fl-loop-data-source-select fl-builder-settings-section">
	<table class="fl-form-table">
	<?php

	// Data Source.
	FLBuilder::render_settings_field(
		'data_source',
		array(
			'type'    => 'select',
			'label'   => __( 'Source', 'xpro-bb-addons' ),
			'default' => 'custom_query',
			'options' => array(
				'custom_query' => __( 'Custom Query', 'xpro-bb-addons' ),
				'main_query'   => __( 'Main Query', 'xpro-bb-addons' ),
			),
			'toggle'  => array(
				'custom_query' => array(
					'fields' => array( 'posts_per_page' ),
				),
			),
		),
		$settings
	);

	?>
	</table>
</div>
<div class="fl-custom-query fl-loop-data-source" data-source="custom_query">
	<div id="fl-builder-settings-section-general" class="fl-builder-settings-section">
		<div class="fl-builder-settings-section-header">
			<button class="fl-builder-settings-title">
				<svg class="fl-symbol">
					<use xlink:href="#fl-down-caret"></use>
				</svg>
				<?php esc_html_e( 'Custom Query', 'xpro-bb-addons' ); ?>
			</button>
		</div>
		<div class="fl-builder-settings-section-content">
			<table class="fl-form-table">
			<?php

			// Post type.
			FLBuilder::render_settings_field(
				'post_type',
				array(
					'type'  => 'post-type',
					'label' => __( 'Post Type', 'xpro-bb-addons' ),
					'help'  => __( 'Choose the post type to display in module.', 'xpro-bb-addons' ),
				),
				$settings
			);

			// Order by.
			FLBuilder::render_settings_field(
				'order_by',
				array(
					'type'    => 'select',
					'label'   => __( 'Sort By', 'xpro-bb-addons' ),
					'help'    => __( 'Choose the parameter to sort your posts.', 'xpro-bb-addons' ),
					'options' => array(
						'ID'             => __( 'ID', 'xpro-bb-addons' ),
						'date'           => __( 'Date', 'xpro-bb-addons' ),
						'modified'       => __( 'Date Last Modified', 'xpro-bb-addons' ),
						'title'          => __( 'Title', 'xpro-bb-addons' ),
						'author'         => __( 'Author', 'xpro-bb-addons' ),
						'comment_count'  => __( 'Comment Count', 'xpro-bb-addons' ),
						'menu_order'     => __( 'Menu Order', 'xpro-bb-addons' ),
						'meta_value'     => __( 'Meta Value (Alphabetical)', 'xpro-bb-addons' ),
						'meta_value_num' => __( 'Meta Value (Numeric)', 'xpro-bb-addons' ),
						'rand'           => __( 'Random', 'xpro-bb-addons' ),
						'post__in'       => __( 'Selection Order', 'xpro-bb-addons' ),
					),
					'toggle'  => array(
						'meta_value'     => array(
							'fields' => array( 'order_by_meta_key' ),
						),
						'meta_value_num' => array(
							'fields' => array( 'order_by_meta_key' ),
						),
					),
				),
				$settings
			);

			// Meta Key.
			FLBuilder::render_settings_field(
				'order_by_meta_key',
				array(
					'type'  => 'text',
					'label' => __( 'Meta Key', 'xpro-bb-addons' ),
				),
				$settings
			);

			// Order.
			FLBuilder::render_settings_field(
				'order',
				array(
					'type'    => 'select',
					'label'   => __( 'Order', 'xpro-bb-addons' ),
					'help'    => __( 'Choose the order to display your posts.', 'xpro-bb-addons' ),
					'default' => 'DESC',
					'options' => array(
						'DESC' => __( 'Descending', 'xpro-bb-addons' ),
						'ASC'  => __( 'Ascending', 'xpro-bb-addons' ),
					),
				),
				$settings
			);

			// Offset.
			FLBuilder::render_settings_field(
				'offset',
				array(
					'type'        => 'unit',
					'label'       => __( 'Offset', 'xpro-bb-addons' ),
					'help'        => __( 'Enter the total number of posts you want to skip.', 'xpro-bb-addons' ),
					'placeholder' => '0',
					'slider'      => true,
				),
				$settings
			);

			FLBuilder::render_settings_field(
				'exclude_self',
				array(
					'type'    => 'select',
					'label'   => __( 'Exclude Current Post', 'xpro-bb-addons' ),
					'default' => 'no',
					'help'    => __( 'Exclude the current post from the query.', 'xpro-bb-addons' ),
					'options' => array(
						'yes' => __( 'Yes', 'xpro-bb-addons' ),
						'no'  => __( 'No', 'xpro-bb-addons' ),
					),
				),
				$settings
			);

			?>
			</table>
		</div>
	</div>
	<div id="fl-builder-settings-section-filter" class="fl-builder-settings-section">
		<div class="fl-builder-settings-section-header">
			<button class="fl-builder-settings-title">
				<svg class="fl-symbol">
					<use xlink:href="#fl-down-caret"></use>
				</svg>
				<?php esc_html_e( 'Filter', 'xpro-bb-addons' ); ?>
			</button>
		</div>
		<div class="fl-builder-settings-section-content">
			<?php foreach ( FLBuilderLoop::post_types() as $slug => $type ) : ?>
				<table class="fl-form-table fl-loop-builder-filter fl-loop-builder-<?php echo esc_attr( $slug ); ?>-filter"
					<?php
					if ( $slug === $settings->post_type ) {
						echo 'style="display:table;"';
					}
					?>
				>
				<?php

				FLBuilder::render_settings_field(
					'posts_' . $slug . '_matching',
					array(
						'type'    => 'select',
						'label'   => $type->label,
						'options' => array(
							'1' => sprintf( /* translators: %s: search term */ __( 'Match these %s', '%s is an object like posts or taxonomies.', 'xpro-bb-addons' ), $type->label, $type->label ),
							'0' => sprintf( /* translators: %s: search term */ __( 'Do not match these %s', '%s is an object like posts or taxonomies.', 'xpro-bb-addons' ), $type->label, $type->label ),

						),
						'help'    => sprintf( /* translators: %1$s: search term, translators: %2$s: search term */ __( 'Enter a comma separated list of %1$s. Only these %2$s will be shown.', 'xpro-bb-addons' ), $type->label, $type->label ),
					),
					$settings
				);
				// Posts.
				FLBuilder::render_settings_field(
					'posts_' . $slug,
					array(
						'type'   => 'suggest',
						'action' => 'fl_as_posts',
						'data'   => $slug,
						'label'  => '&nbsp',
					),
					$settings
				);


				// Taxonomies.
				$taxonomies       = FLBuilderLoop::taxonomies( $slug );
				$taxonomies_array = array();

				foreach ( $taxonomies as $tax_slug => $tax ) {
					FLBuilder::render_settings_field(
						'tax_' . $slug . '_' . $tax_slug . '_matching',
						array(
							'type'    => 'select',
							'label'   => $tax->label,
							'help'    => sprintf( /* translators: %1$s: search term, translators: %2$s: search term */ __( 'Enter a comma separated list of %1$s. Only posts with these %2$s will be shown.', 'xpro-bb-addons' ), $tax->label, $tax->label ),
							'options' => array(
								'1' => sprintf( /* translators: %s: search term */ __( 'Match these %s', '%s is an object like posts or taxonomies.', 'xpro-bb-addons' ), $tax->label, $tax->label ),
								'0' => sprintf( /* translators: %s: search term */ __( 'Do not match these %s', '%s is an object like posts or taxonomies.', 'xpro-bb-addons' ), $tax->label, $tax->label ),
							),
							'help'    => sprintf( /* translators: %1$s: search term, translators: %2$s: search term */ __( 'Enter a comma separated list of %1$s. Only posts with these %2$s will be shown.', 'xpro-bb-addons' ), $tax->label, $tax->label ),
						),
						$settings
					);
					FLBuilder::render_settings_field(
						'tax_' . $slug . '_' . $tax_slug,
						array(
							'type'   => 'suggest',
							'action' => 'fl_as_terms',
							'data'   => $tax_slug,
							'label'  => '&nbsp',
						),
						$settings
					);
					$taxonomies_array[ $tax_slug ] = $tax->label;
				}

				?>
				</table>
			<?php endforeach; ?>
			<table class="fl-form-table">
			<?php

			// Author.
			FLBuilder::render_settings_field(
				'users_matching',
				array(
					'type'    => 'select',
					'label'   => __( 'Authors', 'xpro-bb-addons' ),
					'help'    => __( 'Enter a comma separated list of authors usernames. Only posts with these authors will be shown.', 'xpro-bb-addons' ),
					'options' => array(
						'1' => __( 'Match these Authors', 'xpro-bb-addons' ),
						'0' => __( 'Do not match these Authors', 'xpro-bb-addons' ),
					),
					'help'    => __( 'Enter a comma separated list of authors usernames. Only posts with these authors will be shown.', 'xpro-bb-addons' ),
				),
				$settings
			);

			FLBuilder::render_settings_field(
				'users',
				array(
					'type'   => 'suggest',
					'action' => 'fl_as_users',
					'label'  => __( '&nbsp', 'xpro-bb-addons' ),
				),
				$settings
			);

			?>
			</table>
		</div>
	</div>
</div>


<?php
do_action( 'tnit_loop_settings_after_form', $settings );
