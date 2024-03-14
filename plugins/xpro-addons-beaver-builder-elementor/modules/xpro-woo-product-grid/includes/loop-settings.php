<?php
/**
 *  xpro Woo Products Module loop settings file
 *
 *  @package xpro Woo Products Module
 */

FLBuilderModel::default_settings(
	$settings,
	array(
		'data_source' => 'custom_query',
		'post_type'   => 'product',
		'order_by'    => 'date',
		'order'       => 'DESC',
		'offset'      => 0,
		'users'       => '',
	)
);

$settings = apply_filters( 'fl_builder_loop_settings', $settings );  // Allow extension of default Values.

do_action( 'xpro_woo_products_loop_settings_before_form', $settings ); // e.g Add custom FLBuilder::render_settings_field().

?>
<div id="fl-builder-settings-section-source" class="fl-loop-data-source-select fl-builder-settings-section">
	<table class="fl-form-table">
		<?php

		// Data Source.
		FLBuilder::render_settings_field(
			'data_source',
			array(
				'type'    => 'select',
				'label'   => __( 'Source', 'xpro' ),
				'default' => 'custom_query',
				'options' => array(
					'custom_query' => __( 'Custom Query', 'xpro' ),
					'main_query'   => __( 'Main Query', 'xpro' ),
				),
				'toggle'  => array(
					'custom_query' => array(
						'fields' => array( 'grid_products' ),
					),
				),
			),
			$settings
		);

		?>
	</table>
</div>
<div class="fl-custom-query fl-loop-data-source" data-source="custom_query">
	<div id="fl-builder-settings-section-filter" class="fl-builder-settings-section">
		<h3 class="fl-builder-settings-title">
			<span class="fl-builder-settings-title-text-wrap"><?php esc_attr_e( 'Custom Query', 'xpro' ); ?></span>
		</h3>
		<?php foreach ( FLBuilderLoop::post_types() as $slug => $type ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
			<table class="fl-form-table fl-custom-query-filter fl-custom-query-<?php echo esc_attr( $slug ); ?>-filter"
																						<?php
																						if ( $slug === $settings->post_type ) {
																							echo 'style="display:table;"';}
																						?>
			>
			<?php

			// Posts.
			FLBuilder::render_settings_field(
				'posts_' . $slug,
				array(
					'type'     => 'suggest',
					'action'   => 'fl_as_posts',
					'data'     => $slug,
					'label'    => $type->label,
					'help'     => sprintf( /* translators: %s: search term */ __( 'Enter a list of %1$s.', 'xpro' ), $type->label ),
					'matching' => true,
				),
				$settings
			);

			// Taxonomies.
			$taxonomies = FLBuilderLoop::taxonomies( $slug );

			foreach ( $taxonomies as $tax_slug => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

				FLBuilder::render_settings_field(
					'tax_' . $slug . '_' . $tax_slug,
					array(
						'type'     => 'suggest',
						'action'   => 'fl_as_terms',
						'data'     => $tax_slug,
						'label'    => $tax->label,
						'help'     => sprintf( /* translators: %s: search term */ __( 'Enter a list of %1$s.', 'xpro' ), $tax->label ),
						'matching' => true,
					),
					$settings
				);
			}

			?>
			</table>
		<?php endforeach; ?>
		<table class="fl-form-table">
		<?php

		// Author.
		FLBuilder::render_settings_field(
			'users',
			array(
				'type'     => 'suggest',
				'action'   => 'fl_as_users',
				'label'    => __( 'Authors', 'xpro' ),
				'help'     => __( 'Enter a list of authors usernames.', 'xpro' ),
				'matching' => true,
			),
			$settings
		);

		?>
		</table>
	</div>
	<div id="fl-builder-settings-section-general" class="fl-builder-settings-section">
		<h3 class="fl-builder-settings-title">
			<span class="fl-builder-settings-title-text-wrap"><?php esc_attr_e( 'Filter', 'xpro' ); ?></span>
		</h3>
		<table class="fl-form-table">
		<?php

		// Order.
		FLBuilder::render_settings_field(
			'filter_by',
			array(
				'type'    => 'select',
				'label'   => __( 'Filter By', 'xpro' ),
				'options' => array(
					''         => __( 'None', 'xpro' ),
					'sale'     => __( 'Sale', 'xpro' ),
					'featured' => __( 'Featured', 'xpro' ),
				),
			),
			$settings
		);

		// Order.
		FLBuilder::render_settings_field(
			'order',
			array(
				'type'    => 'select',
				'label'   => __( 'Order', 'xpro' ),
				'options' => array(
					'DESC' => __( 'Descending', 'xpro' ),
					'ASC'  => __( 'Ascending', 'xpro' ),
				),
			),
			$settings
		);

		// Order by.
		FLBuilder::render_settings_field(
			'order_by',
			array(
				'type'    => 'select',
				'label'   => __( 'Order By', 'xpro' ),
				'options' => array(
					'author'         => __( 'Author', 'xpro' ),
					'comment_count'  => __( 'Comment Count', 'xpro' ),
					'date'           => __( 'Date', 'xpro' ),
					'modified'       => __( 'Date Last Modified', 'xpro' ),
					'ID'             => __( 'ID', 'xpro' ),
					'menu_order'     => __( 'Menu Order', 'xpro' ),
					'meta_value'     => __( 'Meta Value (Alphabetical)', 'xpro' ),
					'meta_value_num' => __( 'Meta Value (Numeric)', 'xpro' ),
					'rand'           => __( 'Random', 'xpro' ),
					'title'          => __( 'Title', 'xpro' ),
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
				'label' => __( 'Meta Key', 'xpro' ),
			),
			$settings
		);

		// Offset.
		FLBuilder::render_settings_field(
			'offset',
			array(
				'type'    => 'unit',
				'label'   => _x( 'Exclude Product', 'How many products to skip.', 'xpro' ),
				'default' => '0',
				'size'    => '4',
				'help'    => __( 'Skip this many products that match the specified criteria.', 'xpro' ),
			),
			$settings
		);

		FLBuilder::render_settings_field(
			'exclude_self',
			array(
				'type'    => 'select',
				'label'   => __( 'Exclude Current Product', 'xpro' ),
				'default' => 'no',
				'help'    => __( 'Exclude the current product from the query.', 'xpro' ),
				'options' => array(
					'yes' => __( 'Yes', 'xpro' ),
					'no'  => __( 'No', 'xpro' ),
				),
			),
			$settings
		);

		?>
		</table>
	</div>
</div>
<?php
do_action( 'xpro_woo_products_loop_settings_after_form', $settings ); // e.g Add custom FLBuilder::render_settings_field().
