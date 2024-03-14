<?php

class WPPP_WPFeatures_Advanced extends WPPP_Admin_Renderer {

	private $dberror = null;
	private $dberrortype = null;
	private $dbstate = null;

	public function add_help_tab () {
		$screen = get_current_screen();
		$screen->add_help_tab( array(
			'id'	=> 'wppp_advanced_features',
			'title'	=>	__( 'Overview', 'wp-performance-pack' ),
			'content'	=> '<p>' . __( "Change or disable WordPress features.", 'wp-performance-pack' ) . '</p>',
		) );
	}

	public function render_options () {
	?>
		<style>.form-table th, .form-table td { padding : 10px 10px }</style>
		<h3 class="title"><?php _e( 'WordPress features', 'wp-performance-pack' );?></h3>

		<table class="form-table" style="clear:none">
			<tr>
				<th scope="row"><?php _e( 'Automatic scaling of big images', 'wp-performance-pack' ); ?></th>
				<td>
					<?php $this->e_switchButton( 'big_image_scaling' ); ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Comments and Pingbacks', 'wp-performance-pack' ); ?></th>
				<td>
					<?php $this->e_switchButton( 'comments' ); ?>
				</td>
			<tr>
				<th scope="row"><?php _e( 'Edit lock', 'wp-performance-pack' ); ?></th>
				<td>
					<?php $this->e_switchButton( 'editlock' ); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Emoji support', 'wp-performance-pack' ); ?></th>
				<td>
					<?php $this->e_switchButton( 'emojis' ); ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Heartbeat locations', 'wp-performance-pack' ); ?></th>
				<td><?php $this->select( '',
										'heartbeat_location',
										WP_Performance_Pack::$modinfo[ 'wpfeatures' ][ 'heartbeat_location' ],
										array(
											__( 'Default', 'wp-performance-pack' ),
											__( 'Disable everywhere', 'wp-performance-pack' ),
											__( 'Disable only for Dashboard', 'wp-performance-pack' ),
											__( 'Disable everywhere except when editing' ),
										) ); ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Heatbeat frequency', 'wp-performance-pack' ); ?></th>
				<td><?php $this->select( '',
										'heartbeat_frequency',
										WP_Performance_Pack::$modinfo[ 'wpfeatures' ][ 'heartbeat_frequency' ],
										array( __( 'Default', 'wp-performance-pack' ) ) ); ?>
				</td>
			</tr>
		</table>

		<hr/>

		<h3><?php _e( 'WordPress header elements', 'wp-performance-pack' ); ?></h3>
		<table class="form-table" style="clear:none">
			<tr>
				<th scope="row"><?php _e( 'Adjacent posts links' ); ?></th>
				<td><?php $this->e_switchButton( 'adjacent_posts_links' ); ?></td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Comments feed link' ); ?></th>
				<td><?php $this->e_switchButton( 'feed_links_extra' ); ?></td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'EditURI/<abbr title="Really Simple Discovery">RSD</abbr> link', 'wp-performance-pack' ); ?></th>
				<td><?php $this->e_switchButton( 'rsd_link' ); ?></td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Feed links' ); ?></th>
				<td><?php $this->e_switchButton( 'feed_links' ); ?></td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Generator name meta', 'wp-performance-pack' ); ?></th>
				<td><?php $this->e_switchButton( 'wp_generator' ); ?></td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Shortlink' ); ?></th>
				<td><?php $this->e_switchButton( 'wp_shortlink_wp_head' ); ?></td>
			</tr>
			<tr>
				<th scope="row"><?php _e( '<abbr title="Windows Live Writer">WLW</abbr> manifest link', 'wp-performance-pack' ); ?></th>
				<td><?php $this->e_switchButton( 'wlwmanifest_link' ); ?></td>
			</tr>
		</table>

		<hr/>

		<h3><?php _e( 'Javascript', 'wp-performance-pack' ); ?></h3>
		<table class="form-table" style="clear:none">
			<tr>
				<th scope="row"><?php _e( 'JQuery Migrate (frontend)' ); ?></th>
				<td><?php $this->e_switchButton( 'jquery_migrate' ); ?></td>
			</tr>
		</table>

	<?php
	}

	public function render_post_settings_elements() {
	?>
		<hr/>

		<h3><?php _e( 'Persistent data base connection', 'wp-performance-pack' ); ?></h3>
		
		<?php if ( $this->dberror !== null ) : ?>
			<div class="ui-state-error ui-corner-all" style="padding:.5em"><span class="ui-icon ui-icon-alert" style="float:left; margin-right:.3em;"></span>
				<strong><?php
					if ( $this->dberrortype === 'inst' )
						_e( 'Error installing persistent db!', 'wp-performance-pack' );
					elseif ( $this->dberrortype === 'uninst' )
						_e( 'Error uninstalling persistent db!', 'wp-performance-pack' );
					?>
				</strong>
				<br/>
				<?php
				if ( $this->dberrortype === 'inst' )
					printf( __( 'Copying db.php to WordPress content dir failed: "%s"', 'wp-performance-pack' ), $this->dberror['message'] );
				elseif ( $this->dberrortype === 'uninst' )
					printf( __( 'Deleting db.php from WordPress content dir failed: "%s"', 'wp-performance-pack' ), $this->dberror['message'] );
				?>
			</div>
		<?php endif; ?>

		<p class="description"><?php _e( 'Use persistent MySQL connection to improve performance. Subsequent calls to WordPress don\'t need to open a new database connection each time but instead reuse the same connection. Beware that this can cause isses e.g. when table locks and/or transactions are used and a script fails to release/commit those.', 'wp-performance-pack' ); ?></p>

		<?php if ( $this->dbstate !== null ) : ?>
			<div class="ui-state-highlight ui-corner-all" style="padding:.5em;"><span class="ui-icon ui-icon-info" style="float:left; margin-top:.2ex; margin-right:.5ex;"></span>
				<?php
				if ( $this->dbstate === 'installed' )
					_e( 'Persistent DB installed successfully.', 'wp-performance-pack' );
				elseif ( $this->dbstate === 'uninstalled' )
					_e( 'Persistent DB uninstalled successfully.', 'wp-performance-pack' );
				?>
			</div>
		<?php elseif ( defined( 'WPPP_PERSISTENT_DB' ) ) : ?>
			<div class="ui-state-highlight ui-corner-all" style="padding:.5em; background: #fff; border: thin solid #7ad03a;"><span class="ui-icon ui-icon-check" style="float:left; margin-top:.2ex; margin-right:.5ex;"></span>
				<?php _e( 'Persistent DB installed and loaded.', 'wp-performance-pack' ); ?>
			</div>
		<?php endif; ?>

		<p><form action="" method="post">
		<?php if ( ( defined( 'WPPP_PERSISTENT_DB' ) && ( $this->dbstate !== 'uninstalled' ) ) || ( $this->dbstate === 'installed' ) ) : ?>
				<input type="hidden" name="action" value="wppp_uninst_db" />
				<input type="submit" class="button button-primary" value="Uninstall" />
		<?php else : ?>
				<input type="hidden" name="action" value="wppp_inst_db" />
				<input type="submit" class="button button-primary" value="Install" />
		<?php endif; ?>
		</form></p>

		<hr/>
		<br/>
	<?php
	}

	public function render_page( $formaction ) {
		if ( isset( $_POST[ 'action' ] ) ) {
			if ( $_POST[ 'action' ] === 'wppp_inst_db' ) {
				if ( !@copy( dirname( __FILE__ ) . '/db.php', WP_CONTENT_DIR . '/db.php' ) ) {
					$this->dberror = error_get_last();
					$this->dberrortype = 'inst';
				} else {
					$this->dbstate = 'installed';
				}
			} elseif ( defined( 'WPPP_PERSISTENT_DB' ) && ( $_POST[ 'action' ] === 'wppp_uninst_db' ) ) {
				if ( !@unlink( WP_CONTENT_DIR . '/db.php' ) ) {
					$this->dberror = error_get_last();
					$this->dberrortype = 'uninst';
				} else {
					$this->dbstate = 'uninstalled';
				}
			}
		}
		parent::render_page( $formaction );
	}
}