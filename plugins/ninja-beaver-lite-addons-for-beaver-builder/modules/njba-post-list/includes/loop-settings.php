<?php
FLBuilderModel::default_settings( $settings, array(
	'post_type'            => 'post',
	'order_by'             => 'date',
	'order'                => 'DESC',
	'offset'               => 0,
	'no_results_message'   => __( 'No result found.', 'bb-njba' ),
	'users'                => '',
	'show_author'          => '1',
	'show_date'            => '1',
	'date_format'          => 'default',
	'show_post_taxonomies' => '1',
	'post_taxonomies'      => 'category',
	'meta_separator'       => ' / ',
	'title_margin'         => array(
		'top'    => '0',
		'bottom' => '0'
	)
) );
?>
<div id="fl-builder-settings-section-general" class="fl-loop-builder fl-builder-settings-section">
    <table class="fl-form-table">
		<?php
		// Post type
		FLBuilder::render_settings_field( 'post_type', array(
			'type'  => 'post-type',
			'label' => __( 'Post Type', 'bb-njba' ),
		), $settings );
		// Order by
		FLBuilder::render_settings_field( 'order_by', array(
			'type'    => 'select',
			'label'   => __( 'Order By', 'bb-njba' ),
			'options' => array(
				'ID'            => __( 'ID', 'bb-njba' ),
				'date'          => __( 'Date', 'bb-njba' ),
				'modified'      => __( 'Date Last Modified', 'bb-njba' ),
				'title'         => __( 'Title', 'bb-njba' ),
				'author'        => __( 'Author', 'bb-njba' ),
				'comment_count' => __( 'Comment Count', 'bb-njba' ),
				'menu_order'    => __( 'Menu Order', 'bb-njba' ),
				'rand'          => __( 'Random', 'bb-njba' ),
			)
		), $settings );
		// Order
		FLBuilder::render_settings_field( 'order', array(
			'type'    => 'select',
			'label'   => __( 'Order', 'bb-njba' ),
			'options' => array(
				'DESC' => __( 'Descending', 'bb-njba' ),
				'ASC'  => __( 'Ascending', 'bb-njba' ),
			)
		), $settings );
		// Offset
		FLBuilder::render_settings_field( 'offset', array(
			'type'    => 'text',
			'label'   => _x( 'Offset', 'How many posts to skip.', 'bb-njba' ),
			'default' => '0',
			'size'    => '4',
			'help'    => __( 'Skip this many posts that match the specified criteria.', 'bb-njba' )
		), $settings );
		// No results message
		FLBuilder::render_settings_field( 'no_results_message', array(
			'type'    => 'text',
			'label'   => __( 'No Results Message', 'bb-njba' ),
			'default' => __( 'No Posts Found.', 'bb-njba' )
		), $settings );
		?>
    </table>
</div>
<div id="fl-builder-settings-section-filter" class="fl-builder-settings-section">
    <h3 class="fl-builder-settings-title"><?php _e( 'Filter', 'bb-njba' ); ?></h3>
	<?php foreach ( FLBuilderLoop::post_types() as $slug => $type ) : ?>
        <table class="fl-form-table fl-loop-builder-filter fl-loop-builder-<?php echo $slug; ?>-filter njba-filter-deactive" <?php if ( $slug === $settings->post_type ) {
			echo 'style="display:table;"';
		} ?>>
			<?php
			// Posts
			FLBuilder::render_settings_field( 'posts_' . $slug, array(
				'type'   => 'suggest',
				'action' => 'fl_as_posts',
				'data'   => $slug,
				'label'  => $type->label,
				'help'   => sprintf( __( 'Enter a list of %s. Only these %s will be shown.', 'bb-njba' ), $type->label, $type->label )
			), $settings );
			// Taxonomies
			$taxonomies = FLBuilderLoop::taxonomies( $slug );
			foreach ( $taxonomies as $tax_slug => $tax ) {
				FLBuilder::render_settings_field( 'tax_' . $slug . '_' . $tax_slug, array(
					'type'   => 'suggest',
					'action' => 'fl_as_terms',
					'data'   => $tax_slug,
					'label'  => $tax->label,
					'help'   => sprintf( __( 'Enter a list of %s. Only posts with these %s will be shown.', 'bb-njba' ), $tax->label, $tax->label )
				), $settings );
			}
			?>
        </table>
	<?php endforeach; ?>
    <table class="fl-form-table">
		<?php
		// Author
		FLBuilder::render_settings_field( 'users', array(
			'type'   => 'suggest',
			'action' => 'fl_as_users',
			'label'  => __( 'Authors', 'bb-njba' ),
			'help'   => __( 'Enter a list of authors user names. Only posts with these authors will be shown.', 'bb-njba' )
		), $settings );
		?>
    </table>
</div>
<div id="fl-builder-settings-section-meta" class="fl-builder-settings-section">
    <h3 class="fl-builder-settings-title"><?php esc_html_e( 'Meta', 'bb-njba' ); ?></h3>
    <table class="fl-form-table">
		<?php
		// Show Author
		FLBuilder::render_settings_field( 'show_author', array(
			'type'    => 'select',
			'label'   => __( 'Display Author', 'bb-njba' ),
			'default' => '1',
			'options' => array(
				'1' => __( 'Yes', 'bb-njba' ),
				'0' => __( 'No', 'bb-njba' )
			)
		), $settings );
		// Show Date
		FLBuilder::render_settings_field( 'show_date', array(
			'type'    => 'select',
			'label'   => __( 'Display Date', 'bb-njba' ),
			'default' => '1',
			'options' => array(
				'1' => __( 'Yes', 'bb-njba' ),
				'0' => __( 'No', 'bb-njba' )
			),
			'toggle'  => array(
				'1' => array(
					'fields' => array( 'date_format' )
				)
			)
		), $settings );
		// Date format
		FLBuilder::render_settings_field( 'date_format', array(
			'type'    => 'select',
			'label'   => __( 'Date Format', 'bb-njba' ),
			'default' => 'default',
			'options' => array(
				'default' => __( 'Default', 'bb-njba' ),
				'M j, Y'  => date( 'M j, Y' ),
				'F j, Y'  => date( 'F j, Y' ),
				'm/d/Y'   => date( 'm/d/Y' ),
				'm-d-Y'   => date( 'm-d-Y' ),
				'd M Y'   => date( 'd M Y' ),
				'd F Y'   => date( 'd F Y' ),
				'Y-m-d'   => date( 'Y-m-d' ),
				'Y/m/d'   => date( 'Y/m/d' ),
			)
		), $settings );
		// Show taxonomy
		FLBuilder::render_settings_field( 'show_post_taxonomies', array(
			'type'    => 'select',
			'label'   => __( 'Display Taxonomy', 'bb-njba' ),
			'default' => 'show',
			'options' => array(
				'1' => __( 'Yes', 'bb-njba' ),
				'0' => __( 'No', 'bb-njba' )
			),
			'toggle'  => array(
				'1' => array(
					'fields' => array( 'post_taxonomies' )
				)
			)
		), $settings );
		// Show taxonomy
		FLBuilder::render_settings_field( 'post_taxonomies', array(
			'type'    => 'select',
			'label'   => __( 'Taxonomy', 'bb-njba' ),
			'default' => 'none',
			'options' => array(
				'none' => __( 'None', 'bb-njba' )
			)
		), $settings );

		?>
    </table>
</div>
<script type="text/javascript">
    ;(function ($) {
        $('.fl-builder-njba-post-list-settings select[name="post_type"]').on('change', function () {
            const post_type_slug = $(this).val();
            const post_taxonomies = $('.fl-builder-njba-post-list-settings select[name="post_taxonomies"]');
            const selected_taxonomy = '<?php echo $settings->post_taxonomies; ?>';
            $.ajax({
                type: 'post',
                data: { action: 'ct_get_post_tax', post_type_slug: post_type_slug },
                url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                success: function (res) {
                    if (res !== 'undefined' || res !== '') {
                        post_taxonomies.html(res);
                        post_taxonomies.find('option[value="' + selected_taxonomy + '"]').attr('selected', 'selected');
                    }
                }
            });
        });
        $(document).ready(function () {
            $(".njba-filter-deactive").hide();
            $('.fl-builder-njba-post-list-settings select[name=post_type]').on('click', function () {
                const selected_value = $(this).find(':selected').val();
                $(".njba-filter-deactive").hide();
                $('.fl-builder-njba-post-list-settings table.fl-form-table.fl-loop-builder-filter.fl-loop-builder-' + selected_value + '-filter').show();
            });
        });
    })(jQuery);
</script>
