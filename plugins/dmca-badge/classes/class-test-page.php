<?php

class DMCA_Badge_Test_Page {

	const DATE_FORMAT = "F j, Y, g:i a";
	/**
	 * Slug of the test page
	 * @var string
	 */
	var $page_slug = 'dmca-badge-reset';
	/**
	 * Option key for the settings backup
	 * @var string
	 */
	var $backup_option = 'dmca_badge_backups';
	/**
	 * Option key of the DMCA Badge plugin
	 * @var string
	 */
	var $option = 'dmca_badge_settings';
	/**
	 * Notices to be displayed in admin
	 * @var array
	 */
	var $notices = array();

	function __construct() {
        $error_path = plugin_dir_url(__FILE__) ;
	    try {
			
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
        }
        catch (Exception $e) 
        {  
          echo 'Exception Message: ' .$e->getMessage();  
          if ($e->getSeverity() === E_ERROR) {
              echo("E_ERROR triggered.\n");
          } else if ($e->getSeverity() === E_WARNING) {
              echo("E_WARNING triggered.\n");
          }
          echo "<br> $error_path";
        }  
        catch (ErrorException  $er)
        {  
          echo 'ErrorException Message: ' .$er->getMessage();  
          echo "<br> $error_path";
        }  
        catch ( Throwable $th){
          echo 'ErrorException Message: ' .$th->getMessage();
          echo "<br> $error_path";
        }
	}

	function admin_menu() {
        $error_path = plugin_dir_url(__FILE__) ;
	    try {
			
			add_management_page( __( 'DMCA Badge Reset', 'dmca-badge' ), __( 'DMCA Badge Reset', 'dmca-badge' ), 'manage_options', $this->page_slug, array(
				$this,
				'show_page'
			) );
        }
        catch (Exception $e) 
        {  
          echo 'Exception Message: ' .$e->getMessage();  
          if ($e->getSeverity() === E_ERROR) {
              echo("E_ERROR triggered.\n");
          } else if ($e->getSeverity() === E_WARNING) {
              echo("E_WARNING triggered.\n");
          }
          echo "<br> $error_path";
        }  
        catch (ErrorException  $er)
        {  
          echo 'ErrorException Message: ' .$er->getMessage();  
          echo "<br> $error_path";
        }  
        catch ( Throwable $th){
          echo 'ErrorException Message: ' .$th->getMessage();
          echo "<br> $error_path";
        }
	}

	function admin_init() {
        $error_path = plugin_dir_url(__FILE__) ;
	    try {
			
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_REQUEST['page'] ) && $_REQUEST['page'] === $this->page_slug ) {
				$this->process_request();
			}
        }
        catch (Exception $e) 
        {  
          echo 'Exception Message: ' .$e->getMessage();  
          if ($e->getSeverity() === E_ERROR) {
              echo("E_ERROR triggered.\n");
          } else if ($e->getSeverity() === E_WARNING) {
              echo("E_WARNING triggered.\n");
          }
          echo "<br> $error_path";
        }  
        catch (ErrorException  $er)
        {  
          echo 'ErrorException Message: ' .$er->getMessage();  
          echo "<br> $error_path";
        }  
        catch ( Throwable $th){
          echo 'ErrorException Message: ' .$th->getMessage();
          echo "<br> $error_path";
        }
	}

	/**
	 * @return array
	 */
	function process_request() {
        $error_path = plugin_dir_url(__FILE__) ;
	    try {
			

			if ( isset( $_POST['submit'] ) ) {
				switch ( $_POST['submit'] ) {
					case 'Restore':
						if ( isset( $_POST['backups'] ) && $_POST['backups'] ) {
							$this->restore_backup( $_POST['backups'] );
						} else {
							$this->notices[] = new WP_Error( 'error', 'You have to select a backup to restore.' );
						}
						break;
					case 'Delete Backups':
						$this->delete_backups();
						break;

				}
			}

			if ( isset( $_POST['backup'] ) && $_POST['backup'] === 'on' ) {
				$this->backup_settings();
			}

			if ( isset( $_POST['delete'] ) && $_POST['delete'] === 'on' ) {
				$this->delete_settings();
			}
        }
        catch (Exception $e) 
        {  
          echo 'Exception Message: ' .$e->getMessage();  
          if ($e->getSeverity() === E_ERROR) {
              echo("E_ERROR triggered.\n");
          } else if ($e->getSeverity() === E_WARNING) {
              echo("E_WARNING triggered.\n");
          }
          echo "<br> $error_path";
        }  
        catch (ErrorException  $er)
        {  
          echo 'ErrorException Message: ' .$er->getMessage();  
          echo "<br> $error_path";
        }  
        catch ( Throwable $th){
          echo 'ErrorException Message: ' .$th->getMessage();
          echo "<br> $error_path";
        }

	}

	/**
	 * Restore backup from given timestamp
	 *
	 * @param string $timestamp
	 */
	function restore_backup( $timestamp ) {
        $error_path = plugin_dir_url(__FILE__) ;
	    try {
			
			$backups = get_option( $this->backup_option );
			$date    = date( self::DATE_FORMAT, $timestamp );
			if ( isset( $backups[ $timestamp ] ) ) {
				update_option( $this->option, $backups[ $timestamp ] );
				$this->notices[] = sprintf( __( "Restored backup from %s.", "dmca-badge" ), $date );
			} else {
				$this->notices[] = new WP_Error( "error", sprintf( __( "Backup from %s doesn't exist.", "dmca-bage" ), $date ) );
			}
        }
        catch (Exception $e) 
        {  
          echo 'Exception Message: ' .$e->getMessage();  
          if ($e->getSeverity() === E_ERROR) {
              echo("E_ERROR triggered.\n");
          } else if ($e->getSeverity() === E_WARNING) {
              echo("E_WARNING triggered.\n");
          }
          echo "<br> $error_path";
        }  
        catch (ErrorException  $er)
        {  
          echo 'ErrorException Message: ' .$er->getMessage();  
          echo "<br> $error_path";
        }  
        catch ( Throwable $th){
          echo 'ErrorException Message: ' .$th->getMessage();
          echo "<br> $error_path";
        }
	}

	function delete_backups() {
        $error_path = plugin_dir_url(__FILE__) ;
	    try {
			
			delete_option( $this->backup_option );
			$this->notices[] = "Backups deleted";
        }
        catch (Exception $e) 
        {  
          echo 'Exception Message: ' .$e->getMessage();  
          if ($e->getSeverity() === E_ERROR) {
              echo("E_ERROR triggered.\n");
          } else if ($e->getSeverity() === E_WARNING) {
              echo("E_WARNING triggered.\n");
          }
          echo "<br> $error_path";
        }  
        catch (ErrorException  $er)
        {  
          echo 'ErrorException Message: ' .$er->getMessage();  
          echo "<br> $error_path";
        }  
        catch ( Throwable $th){
          echo 'ErrorException Message: ' .$th->getMessage();
          echo "<br> $error_path";
        }
	}

	/**
	 * Add current settings to backups.
	 * @return string|void|WP_Error
	 */
	function backup_settings() {
        $error_path = plugin_dir_url(__FILE__) ;
	    try {
			
			$msg      = null;
			$settings = get_option( $this->option );
			if ( $settings ) {
				$backups           = get_option( $this->backup_option, array() );
				$backups[ time() ] = get_option( $this->option );
				update_option( $this->backup_option, $backups );
				$msg = __( 'Backup created.', 'dmca-badge' );
			} else {
				$msg = new WP_Error( "Backup failed because settings are empty." );
			}
			$this->notices[] = $msg;
        }
        catch (Exception $e) 
        {  
          echo 'Exception Message: ' .$e->getMessage();  
          if ($e->getSeverity() === E_ERROR) {
              echo("E_ERROR triggered.\n");
          } else if ($e->getSeverity() === E_WARNING) {
              echo("E_WARNING triggered.\n");
          }
          echo "<br> $error_path";
        }  
        catch (ErrorException  $er)
        {  
          echo 'ErrorException Message: ' .$er->getMessage();  
          echo "<br> $error_path";
        }  
        catch ( Throwable $th){
          echo 'ErrorException Message: ' .$th->getMessage();
          echo "<br> $error_path";
        }
	}

	/**
	 * Delete option where settings are stored
	 * @return string|void
	 */
	function delete_settings() {
        $error_path = plugin_dir_url(__FILE__) ;
	    try {
			
			deactivate_plugins( plugin_basename( DMCA_BADGE_DIR . "/dmca-badge.php" ) );
			delete_option( $this->option );
			wp_redirect( admin_url( "plugins.php?deactivate=true" ) );
        }
        catch (Exception $e) 
        {  
          echo 'Exception Message: ' .$e->getMessage();  
          if ($e->getSeverity() === E_ERROR) {
              echo("E_ERROR triggered.\n");
          } else if ($e->getSeverity() === E_WARNING) {
              echo("E_WARNING triggered.\n");
          }
          echo "<br> $error_path";
        }  
        catch (ErrorException  $er)
        {  
          echo 'ErrorException Message: ' .$er->getMessage();  
          echo "<br> $error_path";
        }  
        catch ( Throwable $th){
          echo 'ErrorException Message: ' .$th->getMessage();
          echo "<br> $error_path";
        }
	}

	/**
	 * Callback for admin_notices action
	 * Show notices
	 */
	function admin_notices() {
        $error_path = plugin_dir_url(__FILE__) ;
	    try {
			
			foreach ( $this->notices as $notice ) {
				if ( is_wp_error( $notice ) ) {
					$class = "error";
					$msg   = $notice->get_error_message();
				} else {
					$class = "updated";
					$msg   = $notice;
				}
				echo "<div class='{$class}'><p>$msg</p></div>";
			}
        }
        catch (Exception $e) 
        {  
          echo 'Exception Message: ' .$e->getMessage();  
          if ($e->getSeverity() === E_ERROR) {
              echo("E_ERROR triggered.\n");
          } else if ($e->getSeverity() === E_WARNING) {
              echo("E_WARNING triggered.\n");
          }
          echo "<br> $error_path";
        }  
        catch (ErrorException  $er)
        {  
          echo 'ErrorException Message: ' .$er->getMessage();  
          echo "<br> $error_path";
        }  
        catch ( Throwable $th){
          echo 'ErrorException Message: ' .$th->getMessage();
          echo "<br> $error_path";
        }
	}

	/**
	 * Out the test page html to the screen and process form if necessary.
	 */
	function show_page() {
        $error_path = plugin_dir_url(__FILE__) ;
	    try {
					

				$action = admin_url( "tools.php?page={$this->page_slug}" );

					echo <<<HTML
				<div class="wrap">
				<div id="icon-tools" class="icon32"><br></div>
				<h2>DMCA Badge Test Page</h2>
				<form action="{$action}" method="POST">
					<h3>Settings</h3>
					<p>
					<input name="backup" id="backup" type="checkbox"> <label for="backup">Backup</label><br>
					<input name="delete" id="delete" type="checkbox"> <label for="delete">Delete - DMCA Badge plugin will be deactivated and you'll be redirected to plugins page.</label>
					</p>
					<input type="submit" class="button-primary"/>
				</form>
				</div>
			HTML;

					$backup_options = $this->get_backup_options();

					$backups_html = <<<HTML
				<div class="wrap">
					<form action="{$action}" method="POST">
						<h3>Backups</h3>
						{$backup_options}
						<p>
							<input type="submit" class="button-primary" name="submit" value="Restore" />
							<input type="submit" class="button-secondary" name="submit" value="Delete Backups" />
						</p>
					</form>
				</div>
			HTML;

					if ( $backup_options ) {
						echo $backups_html;
					}
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}

	}

	/**
	 * Return HTML of available backup options
	 * @return string
	 */
	function get_backup_options() {
        $error_path = plugin_dir_url(__FILE__) ;
	    try {
			
			$options = array();
			$backups = get_option( $this->backup_option );
			if ( is_array( $backups ) && count( $backups ) > 0 ) {
				foreach ( $backups as $timestamp => $backup ) {
					$label     = date( self::DATE_FORMAT, $timestamp );
					$options[] = "<input type='radio' name='backups' id='{$timestamp}' value='{$timestamp}' /> <label for='{$timestamp}'>{$label}</label><br>";
				}
			}

			return join( "\n", $options );
        }
        catch (Exception $e) 
        {  
          echo 'Exception Message: ' .$e->getMessage();  
          if ($e->getSeverity() === E_ERROR) {
              echo("E_ERROR triggered.\n");
          } else if ($e->getSeverity() === E_WARNING) {
              echo("E_WARNING triggered.\n");
          }
          echo "<br> $error_path";
        }  
        catch (ErrorException  $er)
        {  
          echo 'ErrorException Message: ' .$er->getMessage();  
          echo "<br> $error_path";
        }  
        catch ( Throwable $th){
          echo 'ErrorException Message: ' .$th->getMessage();
          echo "<br> $error_path";
        }
	}
}
new DMCA_Badge_Test_Page();