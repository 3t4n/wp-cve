<?php

/**
 * Help Page Functions
 *
 * @author 		MojofyWP
 * @package 	includes
 *
 */
/* ------------------------------------------------------------------------------- */

if ( !function_exists( 'wrsl_add_help_page' ) ) {
    /**
     * Add Help page
     *
     */
    function wrsl_add_help_page()
    {
        add_submenu_page(
            'wrsl-builder',
            // parent slug
            __( 'WoorouSell - Help & Support', WRSL_SLUG ),
            // page_title
            __( 'Help', WRSL_SLUG ),
            // menu_title
            'manage_options',
            // capability
            'wrsl-help',
            // menu_slug
            'wrsl_render_help_page'
        );
    }
    
    add_action( 'admin_menu', 'wrsl_add_help_page', 20 );
}

/* ------------------------------------------------------------------------------- */
if ( !function_exists( 'wrsl_render_help_page' ) ) {
    /**
     * Render shortcode generator page
     *
     * @return string
     */
    function wrsl_render_help_page()
    {
        global  $woorousell_fs ;
        $version = WRSL_VERSION;
        $active_tab = 'tutorials';
        // get current tab
        if ( isset( $_GET['tab'] ) ) {
            switch ( $_GET['tab'] ) {
                case 'resources':
                    $active_tab = 'resources';
                    break;
            }
        }
        ob_start();
        ?>
<div id="wrsl-help-page" class="wrap about-wrap">

	<h1 class="wrsl-help-header">Welcome to WoorouSell <?php 
        echo  $version ;
        echo  ( $woorousell_fs->can_use_premium_code() ? ' <small>PRO Version</small>' : '' ) ;
        ?></h1>

	<div class="about-text">
		Congratulations! Now you can present your woocommerce products in a beautiful and responsive carousel format.
	</div>

	<div class="wp-badge wrsl-help-logo">Version <?php 
        echo  $version ;
        ?></div>

	<h2 class="nav-tab-wrapper">
		<a href="<?php 
        echo  esc_url( admin_url( 'admin.php' ) . '?page=wrsl-builder&page=wrsl-help' ) ;
        ?>" class="nav-tab<?php 
        echo  ( $active_tab == 'tutorials' ? ' nav-tab-active' : '' ) ;
        ?>">Tutorials</a>
		<a href="<?php 
        echo  esc_url( admin_url( 'admin.php?page=wrsl-builder-contact' ) ) ;
        ?>" class="nav-tab">Help & Support</a>
		<a href="<?php 
        echo  esc_url( admin_url( 'admin.php' ) . '?page=wrsl-builder&page=wrsl-help&tab=resources' ) ;
        ?>" class="nav-tab<?php 
        echo  ( $active_tab == 'resources' ? ' nav-tab-active' : '' ) ;
        ?>">Resources</a>
	</h2>

	<?php 
        switch ( $active_tab ) {
            case 'resources':
                ?>
			<div class="wrsl-help-tab">

				<h3>Resources</h3>

				<p>Here are a few resources available that we believe would help you to get around this plugin:</p>

				<ul>
					<li><a href="https://www.mojofywp.com/" target="_blank" rel="nofollow">Official Website</a></li>
					<li><a href="https://www.mojofywp.com/woorousell" target="_blank" rel="nofollow">About the plugin</a></li>
					<li><a href="https://www.mojofywp.com/woorousell/demo" target="_blank" rel="nofollow">Plugin demo Page</a></li>
					<li><a href="<?php 
                echo  esc_url( admin_url( 'admin.php?page=wrsl-builder-contact' ) ) ;
                ?>">Help & Support</a></li>
				</ul>
				
			</div><!-- .wrsl-help-tab -->
			<?php 
                break;
            case 'tutorials':
            default:
                ?>
			<div class="wrsl-help-tab">

				<h3>Tutorials</h3>

				<?php 
                // load tutorial
                try {
                    require_once wrsl()->plugin_path( 'includes/help/free_version.php' );
                } catch ( Exception $e ) {
                    echo  '<br><br>View Form Error' ;
                }
                ?>

			</div><!-- .wrsl-help-tab -->
			<?php 
                break;
        }
        ?>

</div><!-- .wrsl-about-page -->
<?php 
        $html = ob_get_clean();
        echo  apply_filters( 'wrsl_render_help_page', ( !empty($html) ? $html : '' ) ) ;
    }

}