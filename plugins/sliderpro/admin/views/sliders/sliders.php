<div class="wrap sliderpro-admin">
	<h2><?php _e( 'All Sliders' ); ?></h2>
	
	<?php
		$hide_info = get_option( 'sliderpro_hide_getting_started_info' );

		if ( $hide_info != true ) {
	?>
	    <div class="inline-info getting-started-info">
			<div class="inline-info-section">
				<h3><?php _e( 'Getting started', 'sliderpro' ); ?></h3>
				<p><?php _e( 'If you want to reproduce one of the examples showcased online, you can easily import those examples into your own Slider Pro installation.', 'sliderpro' ); ?></p>
				<p><?php _e( 'The examples can be found in the <i>examples</i> folder, which is included in the plugin\'s folder, and can be imported using the <i>Import Slider</i> button below.', 'sliderpro' ); ?></p>
				<p><?php _e( 'For quick usage instructions, please see the video tutorials below. For more detailed instructions, please see the', 'sliderpro' ); ?> <a href="<?php echo admin_url('admin.php?page=sliderpro-documentation'); ?>"><?php _e( 'Documentation', 'sliderpro' ); ?></a> <?php _e( 'page.', 'sliderpro' ); ?></p>
				<ul class="video-tutorials-list">
					<li><a href="https://bqworks.net/slider-pro/screencasts/#simple-slider" target="_blank"><?php _e( '1. Create and publish sliders', 'sliderpro' ); ?></a></li>
					<li><a href="https://bqworks.net/slider-pro/screencasts/#slider-from-posts" target="_blank"><?php _e( '2. Create sliders from posts', 'sliderpro' ); ?></a></li>
					<li><a href="https://bqworks.net/slider-pro/screencasts/#slider-from-gallery" target="_blank"><?php _e( '3. Create sliders from galleries', 'sliderpro' ); ?></a></li>
					<li><a href="https://bqworks.net/slider-pro/screencasts/#adding-thumbnails" target="_blank"><?php _e( '4. Adding thumbnails', 'sliderpro' ); ?></a></li>
					<li><a href="https://bqworks.net/slider-pro/screencasts/#adding-layers" target="_blank"><?php _e( '5. Adding layers', 'sliderpro' ); ?></a></li>
					<li><a href="https://bqworks.net/slider-pro/screencasts/#working-with-breakpoints" target="_blank"><?php _e( '6. Working with breakpoints', 'sliderpro' ); ?></a></li>
					<li><a href="https://bqworks.net/slider-pro/screencasts/#import-export" target="_blank"><?php _e( '7. Import and Export sliders', 'sliderpro' ); ?></a></li>
				</ul>
			</div>
			<div class="inline-info-section">
				<h3><?php _e( 'Add-ons', 'sliderpro' ); ?></h3>
				<p><?php printf( __( 'You can extend the functionality of the plugin with the use of add-ons. By acquiring <a href="%s">premium add-ons</a>, you also support the development of the plugin.', 'sliderpro' ), admin_url('admin.php?page=sliderpro-add-ons') ); ?></p>
			</div>
			<div class="inline-info-section">
				<h3><?php _e( 'Support', 'sliderpro' ); ?></h3>
				<p><?php printf( __( 'If you need assistance with using the plugin, you can ask in the <a href="%s">plugin\'s public forum</a>. Private and priority support is available for users that have at least one valid add-on license.', 'sliderpro' ), 'https://wordpress.org/support/plugin/sliderpro/' ); ?></p>
			</div>
			<a href="#" class="getting-started-close">Close</a>
		</div>
	<?php
		}

		if ( ( get_option( 'sliderpro_is_custom_css') == true || get_option( 'sliderpro_is_custom_js') == true ) && get_option( 'sliderpro_hide_custom_css_js_warning' ) != true ) {
	?>
		<div class="custom-css-js-warning">
			<h3><?php _e( 'Custom CSS & JS', 'sliderpro' ); ?></h3>
			<p><?php _e( 'Your sliders contain custom CSS and/or JavaScript. The built-in CSS & JS editor was removed, but an add-on was created instead, with new features for working with custom code.', 'sliderpro' )?></p>
			<?php
			if ( get_option( 'sliderpro_is_custom_css') == true ) {
			?>
			<div class="custom-css-js-warning-code">
				<h4> <?php _e( 'Custom CSS', 'sliderpro' ); ?></h4>
				<textarea><?php echo stripslashes( get_option( 'sliderpro_custom_css' ) ); ?></textarea>
			</div>
			<?php
			}

			if ( get_option( 'sliderpro_is_custom_js') == true ) {
			?>
			<div class="custom-css-js-warning-code">
				<h4><?php _e( 'Custom JS', 'sliderpro' ); ?></h4>
				<textarea><?php echo stripslashes( get_option( 'sliderpro_custom_js' ) ); ?></textarea>
			</div>
			<?php
			}
			?>
			<a href="#" class="custom-css-js-warning-close"><?php _e( 'Delete the custom CSS & JS, and remove this note.', 'sliderpro' ); ?></a>
		</div>
	<?php
		}
	?>
	
	<table class="widefat sliders-list">
		<thead>
			<tr>
				<th><?php _e( 'ID', 'sliderpro' ); ?></th>
				<th><?php _e( 'Name', 'sliderpro' ); ?></th>
				<th><?php _e( 'Shortcode', 'sliderpro' ); ?></th>
				<th><?php _e( 'Created', 'sliderpro' ); ?></th>
				<th><?php _e( 'Modified', 'sliderpro' ); ?></th>
				<th><?php _e( 'Actions', 'sliderpro' ); ?></th>
			</tr>
		</thead>
		
		<tbody>
			
		<?php
			global $wpdb;
			$prefix = $wpdb->prefix;
			$max_sliders_on_page = get_option( 'sliderpro_max_sliders_on_page', 100 );
			$total_sliders = $wpdb->get_var( "SELECT COUNT(*) FROM " . $prefix . "slider_pro_sliders" );
			$total_pages = ceil( $total_sliders / $max_sliders_on_page );
			$current_page = isset( $_GET['sp_page'] ) ? max( 1, min( $total_pages, intval( $_GET['sp_page'] ) ) ) : 1;
			$offset_row = ( $current_page - 1 ) * $max_sliders_on_page ;

			$sliders = $wpdb->get_results( "SELECT * FROM " . $prefix . "slider_pro_sliders ORDER BY id LIMIT " . $offset_row . ", " . $max_sliders_on_page );
			
			if ( count( $sliders ) === 0 ) {
				echo '<tr class="no-slider-row">' .
						'<td colspan="100%">' . __( 'You don\'t have saved sliders.', 'sliderpro' ) . '</td>' .
					'</tr>';
			} else {
				foreach ( $sliders as $slider ) {
					$slider_id = $slider->id;
					$slider_name = stripslashes( $slider->name );
					$slider_created = $slider->created;
					$slider_modified = $slider->modified;

					include( 'sliders-row.php' );
				}
			}
		?>

		</tbody>
		
		<tfoot>
			<tr>
				<th><?php _e( 'ID', 'sliderpro' ); ?></th>
				<th><?php _e( 'Name', 'sliderpro' ); ?></th>
				<th><?php _e( 'Shortcode', 'sliderpro' ); ?></th>
				<th><?php _e( 'Created', 'sliderpro' ); ?></th>
				<th><?php _e( 'Modified', 'sliderpro' ); ?></th>
				<th><?php _e( 'Actions', 'sliderpro' ); ?></th>
			</tr>
		</tfoot>
	</table>
    
    <div class="new-slider-buttons">    
		<a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=sliderpro-new' ); ?>"><?php _e( 'Create New Slider', 'sliderpro' ); ?></a>
        <a class="button-secondary import-slider" href="<?php echo admin_url( 'admin.php?page=sliderpro&pages=' . $total_pages . '&sp_page=' . $current_page ); ?>"><?php _e( 'Import Slider', 'sliderpro' ); ?></a>
    </div>

	<?php
		if ( $max_sliders_on_page < $total_sliders ) {
			echo '<ul class="sliders-pagination">';

			if ( $current_page - 2 > 1 ) {
				echo '<li class="sliders-pagination-item"><a class="sliders-pagination-link" href="' . admin_url( 'admin.php?page=sliderpro&sp_page=1' ) . '">&Lt;</a></li>';
			}

			for ( $i = $current_page - 2; $i <= $current_page; $i++ ) {
				if ( $i >= 1 ) {
					echo '<li class="sliders-pagination-item' . ( $i === $current_page ? ' selected-page' : '' ) . '"><a class="sliders-pagination-link" href="' . admin_url( 'admin.php?page=sliderpro&sp_page=' ) . $i . '">' . $i . '</a></li>';
				}
			}

			$last_page = min( $total_pages, ( $current_page + 2 ) + max( 0, ( 3 - $current_page ) ) );

			for ( $i = $current_page + 1; $i <= $last_page ; $i++ ) {
				echo '<li class="sliders-pagination-item"><a class="sliders-pagination-link" href="' . admin_url( 'admin.php?page=sliderpro&sp_page=' ) . $i . '">' . $i . '</a></li>';
			}

			if ( $last_page < $total_pages ) {
				echo '<li class="sliders-pagination-item"><a class="sliders-pagination-link" href="' . admin_url( 'admin.php?page=sliderpro&sp_page=' . $total_pages ) . '">&Gt;</a></li>';
			}

			echo '</ul>';
		}
	?>

</div>