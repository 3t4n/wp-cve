<?php
if ( ! defined( 'ABSPATH' ) ) exit;
//Installation Status
class Eos_Cards_System_Report {
	public static function output() {
		echo '<div class="wrap eos">';
		self::get_wp_environment_box();
		self::get_server_environment_box();
		self::get_active_plugins_box();
		self::get_theme_box();
		self::add_user_agent_box();
		self::add_debug_report_box();
		echo '</div>';
	}
	public static function get_wp_environment_box(){
		?>
        <div class="eos-widget-full top">
            <div class="eos-widget settings-box">
                <p class="eos-label" style="font-size:30px;"><?php _e( 'WordPress Environment', 'oracle-cards' ); ?></p>
                <div class="eos-list">
                    <ul>
                        <li>
                            <p><?php _e( 'Home URL', 'oracle-cards' ); ?>: <strong><?php echo home_url(); ?></strong></p>
                        </li>
                        <li>
                            <p><?php _e( 'Site URL', 'oracle-cards' ); ?>: <strong><?php echo site_url(); ?></strong></p>
                        </li>
                        <li>
                            <p><?php _e( 'Theme Version', 'oracle-cards' ); ?>: <strong><?php echo wp_get_theme()->get( 'Version' ); ?></strong></p>
                        </li>
                        <li>
                            <p><?php _e( 'Wordpress Version', 'oracle-cards' ); ?>: <strong><?php bloginfo( 'version' ); ?></strong></p>
                        </li>
                        <li>
                            <p><?php _e( 'Wordpress Multisite', 'oracle-cards' ); ?>: <strong><?php if ( is_multisite() ) { echo '&#10004;'; } else { echo '&ndash;'; } ?></strong></p>
                        </li>
                        <li>
                            <p><?php _e( 'Wordpress Memory Limit', 'oracle-cards' ); ?>: <strong><?php
									$memory = self::eos_let_to_num( WP_MEMORY_LIMIT );
							if ( function_exists( 'memory_get_usage' ) ) {
								$system_memory = self::eos_let_to_num( @ini_get( 'memory_limit' ) );
								$memory        = max( $memory, $system_memory );
							}
							if ( $memory < 67108864 ) {
								echo '<mark class="error">' . sprintf( __( '%s - We recommend setting memory to at least 64MB. See: %s', 'oracle-cards' ), size_format( $memory ), '<a href="http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP" target="_blank">' . __( 'Increasing memory allocated to PHP', 'oracle-cards' ) . '</a>' ) . '</mark>';
							} else {
								echo '<mark class="yes">' . size_format( $memory ) . '</mark>';
							}
									?></strong></p>
                        </li>
                        <li>
                            <p><?php _e( 'Wordpress Debug Mode', 'oracle-cards' ); ?>: <strong><?php if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) : ?>
                                            <mark class="yes">&#10004;</mark><?php else : ?><mark class="no">&ndash;</mark><?php endif; ?></strong></p>
                        </li>
                        <li>
                            <p><?php _e( 'Wordpress Language', 'oracle-cards' ); ?>: <strong><?php echo get_locale(); ?></strong></p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
	}
	public static function get_server_environment_box() {
		global $wpdb;
		$host_name = function_exists( 'gethostname' ) ? gethostname() : false;
		?>
        <div class="eos-widget-full top">
            <div class="eos-widget settings-box">
                <p class="eos-label" style="font-size:30px;"><?php _e( 'Server Environment', 'oracle-cards' ); ?></p>
                <div class="eos-list">
                    <ul>
						<?php if( $host_name ){
							$hostA = explode( '.',$host_name );
							$host_url = count( $hostA ) > 1 ? esc_url( $hostA[count( $hostA ) - 2].'.'.$hostA[count( $hostA ) - 1] ) : '#';
						?>
                        <li>
                            <p><?php _e( 'Host', 'oracle-cards' ); ?>: <a href="<?php echo $host_url; ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $host_name ); ?></a></p>
                        </li>
						<?php } ?>
						<li>
                            <p><?php _e( 'Server Info', 'oracle-cards' ); ?>: <strong><?php echo esc_html( $_SERVER['SERVER_SOFTWARE'] ); ?></strong></p>
                        </li>
						<?php if( function_exists( 'eos_get_ip' ) && in_array( eos_get_ip(),array( '::1',':::1','127.0.0.1' ) ) ){ ?>
						<li>
                            <p><?php _e( 'Server location', 'oracle-cards' ); ?>: <strong><?php echo __( 'Local installation','oracle-cards' ); ?></strong></p>
                        </li>
                        <?php } ?>
						<li>
                            <p><?php _e( 'PHP Version', 'oracle-cards' ); ?>: <strong><?php
									// Check if phpversion function exists.
							if ( function_exists( 'phpversion' ) ) {
								$php_version = phpversion();
								if ( version_compare( $php_version, '5.3', '<' ) ) {
									echo '<mark class="error">' . sprintf( __( '%s - We recommend a minimum PHP version of 5.3.', 'oracle-cards' ), esc_html( $php_version ) ) . '</mark>';
								} else {
									echo '<mark class="yes">' . esc_html( $php_version ) . '</mark>';
								}
							} else {
								_e( "Couldn't determine PHP version because phpversion() doesn't exist.", 'oracle-cards' );
							}
									?></strong></p>
                        </li>
                        <li>
                            <p><?php _e( 'PHP Max Post Size', 'oracle-cards' ); ?>: <strong><?php echo size_format( self::eos_let_to_num( ini_get( 'post_max_size' ) ) ); ?></strong></p>
                        </li>
                        <li>
                            <p><?php _e( 'Max Upload Size', 'oracle-cards' ); ?>: <strong><?php echo size_format( wp_max_upload_size() ); ?></strong></p>
                        </li>
                        <li>
                            <p><?php _e( 'PHP Time Limit', 'oracle-cards' ); ?>: <strong><?php echo ini_get( 'max_execution_time' ); ?></strong></p>
                        </li>
                        <li>
                            <p><?php _e( 'PHP Max Input Vars', 'oracle-cards' ); ?>: <strong><?php echo ini_get( 'max_input_vars' ); ?></strong></p>
                        </li>
                        <li>
                            <p><?php _e( 'Default Timezone is UTC', 'oracle-cards' ); ?>: <strong><?php
									$default_timezone = date_default_timezone_get();
							if ( 'UTC' !== $default_timezone ) {
								echo '<mark class="error">&#10005; ' . sprintf( __( 'Default timezone is %s - it should be UTC', 'oracle-cards' ), $default_timezone ) . '</mark>';
							} else {
								echo '<mark class="yes">&#10004;</mark>';
							} ?></strong></p>
                        </li>
                        <li>
                            <p><?php _e( 'MySQL Version', 'oracle-cards' ); ?>: <strong><?php
							/** @global wpdb $wpdb */
							global $wpdb;
							echo $wpdb->db_version();
							?></strong></p>
                        </li>
						<li>
                            <p><?php _e( 'GZip', 'oracle-cards' ); ?>: <strong><?php
							if ( is_callable( 'gzopen' ) ) {
								echo '<mark class="yes">&#10004;</mark>';
							} else {
								echo '<mark class="no">&#10005;</mark>';
							} ?></strong></p>
                        </li>
                        <li>
                            <p><?php _e( 'DOMDocument', 'oracle-cards' ); ?>: <strong><?php
							if ( class_exists( 'DOMDocument' ) ) {
								echo '<mark class="yes">&#10004;</mark>';
							} else {
								echo '<mark class="no">&#10005;</mark>';
							} ?></strong></p>
                        </li>
                        <li>
                            <p><?php _e( 'SoapClient', 'oracle-cards' ); ?>: <strong><?php
							if ( class_exists( 'SoapClient' ) ) {
								echo '<mark class="yes">&#10004;</mark>';
							} else {
								echo '<mark class="no">&#10005;</mark>';
							} ?></strong></p>
                        </li>
                        <li>
                            <p><?php _e( 'fsockopen/cURL', 'oracle-cards' ); ?>: <strong><?php
							if ( function_exists( 'fsockopen' ) || function_exists( 'curl_init' ) ) {
								echo '<mark class="yes">&#10004;</mark>';
							} else {
								echo '<mark class="no">&#10005;</mark>';
							} ?></strong></p>
                        </li>
                        <li>
                            <p><?php _e( 'Multibyte String', 'oracle-cards' ); ?>: <strong><?php
							if ( extension_loaded( 'mbstring' ) ) {
								echo '<mark class="yes">&#10004;</mark>';
							} else {
								echo '<mark class="no">&#10005;</mark>';
							} ?></strong></p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
	}
	public static function get_active_plugins_box() {
		$mu_plugins = wp_get_mu_plugins();
		$drop_ins = get_dropins();
		$active_plugins = (array) get_option( 'active_plugins', array() );


		?>
		<div class="eos-widget-full top">
			<div class="eos-widget settings-box">
				<p class="eos-label" style="font-size:30px;"><?php _e( 'Must Use Plugins', 'oracle-cards' ); ?></p>
				<div class="eos-list">
					<ul>
		<?php
		foreach( $drop_ins as $drop_in => $arr) {
			?>
						<li>
							<p><strong><?php echo basename( $drop_in ); ?></strong></p>
						</li>
		<?php } ?>
					</ul>
                </div>
				<p class="eos-label" style="font-size:30px;"><?php _e( 'Drop-ins Plugins', 'oracle-cards' ); ?></p>
				<div class="eos-list">
					<ul>
		<?php
		foreach( $drop_ins as $drop_in => $arr) {
			?>
						<li>
							<p><strong><?php echo basename( $drop_in ); ?></strong></p>
						</li>
		<?php } ?>
					</ul>
                </div>
            </div>
        </div>
		<div class="eos-widget-full top">
			<div class="eos-widget settings-box">
				<p class="eos-label" style="font-size:30px;"><?php _e( 'Active Plugins', 'oracle-cards' ); ?></p>
				<div class="eos-list">
					<ul>
		<?php
		foreach( $active_plugins as $plugin ) {
			$plugin_data    = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
			$version_string = '';
			$network_string = '';
			if ( ! empty( $plugin_data['Name'] ) ) {
				$plugin_name = esc_html( $plugin_data['Name'] );
				if ( ! empty( $plugin_data['PluginURI'] )) {
					$plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . esc_attr__( 'Visit plugin homepage', 'oracle-cards' ) . '" target="_blank">' . $plugin_name . '</a>';
				}
			}
			if ( ! empty( $version_data['version'] ) && version_compare( $version_data['version'], $plugin_data['Version'], '>' ) ) {
				$version_string = ' &ndash; <strong style="color:red;">' . esc_html( sprintf( _x( '%s is available', 'Version info', 'oracle-cards' ), $version_data['version'] ) ) . '</strong>';
			}
			if ( $plugin_data['Network'] != false ) {
				$network_string = ' &ndash; <strong style="color:black;">' . __( 'Network enabled', 'oracle-cards' ) . '</strong>';
			}
			?>
						<li>
							<p><?php echo $plugin_name; ?>: <strong><?php echo sprintf( _x( 'by %s', 'by author', 'oracle-cards' ), $plugin_data['Author'] ) . ' &ndash; ' . esc_html( $plugin_data['Version'] ) . $version_string . $network_string; ?></strong></p>
						</li>
            <?php
		}
		?>
					</ul>
                </div>
            </div>
        </div>
		<?php
	}
	public static function get_theme_box() {
		include_once( ABSPATH . 'wp-admin/includes/theme-install.php' );
		$active_theme = wp_get_theme();
		// @codingStandardsIgnoreStart
		$theme_version = $active_theme->Version;
		$theme_template = $active_theme->Template;
		// @codingStandardsIgnoreEnd
		?>
        <div class="eos-widget-full top">
            <div class="eos-widget settings-box">
                <p class="eos-label" style="font-size:30px;"><?php _e( 'Current Theme', 'oracle-cards' ); ?></p>
                <div class="eos-list">
                    <ul>
                        <li>
                            <p><?php _e( 'Theme', 'oracle-cards' ); ?>: <strong><?php echo $active_theme; ?></strong></p>
                        </li>
                        <li>
                            <p><?php _e( 'Theme Version', 'oracle-cards' ); ?>: <strong><?php echo $theme_version; ?></strong></p>
                        </li>
                        <li>
                            <p><?php _e( 'Child Theme', 'oracle-cards' ); ?>: <strong><?php echo is_child_theme() ? '<mark class="yes">&#10004;</mark>' : '&#10005;'; ?></strong></p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
	}
	public static function add_user_agent_box() {
	?>
        <div class="eos-widget-full top">
            <div class="eos-widget settings-box">
                <p class="eos-label" style="font-size:30px;"><?php _e( 'User Agent', 'oracle-cards' ); ?></p>
                <div class="eos-list">
                    <ul>
                        <li>
                            <p><strong id="eos-user-agent"></strong></p>
                        </li>
					</ul>
				</div>
			</div>
		</div>
	<?php
	}
	public static function add_debug_report_box() {
		?>
        <div class="eos-widget-full top">
            <div class="eos-widget">
                <p class="eos-label" style="font-size:30px;"><?php _e( 'Copy System Report for Support', 'oracle-cards' ); ?></p>
                <p class="eos-description">
                    <div id="eos-debug-report">
                        <textarea style="width:100%" rows="20" readonly="readonly"></textarea>
                        <p class="submit"><button id="copy-for-support" class="button-primary" href="#" ><?php _e( 'Copy for Support', 'oracle-cards' ); ?></button></p>
                    </div>
                </p>
            </div>
        </div>
        <script>
            jQuery( document ).ready( function( $ ) {
				$('#eos-user-agent').text(navigator.userAgent);
                var $textArea = $( '#eos-debug-report' ).find( 'textarea' );
                $(".eos-widget.settings-box").each( function( index, element ) {
                    var title = $(this).find('.eos-label').text();
                    var val = $(this).find('li').text().replace(/  /g,'').replace(/\n\n/g,'\n');
                    $textArea.val($textArea.val() + title + '\n' + val + '\n\n');
                });
                $('#copy-for-support').on('click', function() {
                    $( '#eos-debug-report' ).find( 'textarea' ).select();
                    try {
                        if(!document.execCommand('copy')) throw 'Not allowed.';
                    } catch(e) {
                        copyElement.remove();
                        console.log("document.execCommand('copy'); is not supported");
                        var text = $( '#debug-report' ).find( 'textarea' ).val();
                        prompt('Copy the text below. (ctrl c, enter)', text);
                    }
                })
            });
        </script>
        <?php
	}
	public static function eos_let_to_num( $size ) {
		$l   = substr( $size, -1 );
		$ret = substr( $size, 0, -1 );
		switch ( strtoupper( $l ) ) {
			case 'P':
				$ret *= 1024;
			case 'T':
				$ret *= 1024;
			case 'G':
				$ret *= 1024;
			case 'M':
				$ret *= 1024;
			case 'K':
				$ret *= 1024;
		}
		return $ret;
	}
}
