<?php  
    $active_tab = isset( $_GET[ 'tab' ] ) ? sanitize_key($_GET[ 'tab' ]) : 'settings'; 
?>  

<?php if ( @$_REQUEST['page'] == 'wpla-settings-categories' ) : ?>

    <h2><?php echo __( 'Categories', 'wp-lister-for-amazon' ) ?></h2>

<?php elseif ( @$_REQUEST['page'] == 'wpla-settings-accounts' ) : ?>

    <h2><?php echo __( 'My Account', 'wp-lister-for-amazon' ) ?></h2>

<?php elseif ( @$_REQUEST['page'] == 'wpla-settings-repricing' ) : ?>

    <h2><?php echo __( 'Repricing Tool', 'wp-lister-for-amazon' ) ?></h2>

<?php else : ?>

	<h2 class="nav-tab-wrapper">  

        <a href="<?php echo $wpl_settings_url; ?>&tab=settings"   class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>"><?php echo __( 'General Settings', 'wp-lister-for-amazon' ) ?></a>
        <a href="<?php echo $wpl_settings_url; ?>&tab=accounts"   class="nav-tab <?php echo $active_tab == 'accounts' ? 'nav-tab-active' : ''; ?>"><?php echo __( 'Accounts', 'wp-lister-for-amazon' ) ?></a>
        <a href="<?php echo $wpl_settings_url; ?>&tab=categories" class="nav-tab <?php echo $active_tab == 'categories' ? 'nav-tab-active' : ''; ?>"><?php echo __( 'Categories', 'wp-lister-for-amazon' ) ?></a>
        <a href="<?php echo $wpl_settings_url; ?>&tab=advanced"   class="nav-tab <?php echo $active_tab == 'advanced' ? 'nav-tab-active' : ''; ?>"><?php echo __( 'Advanced', 'wp-lister-for-amazon' ) ?></a>

        <?php if ( ! defined('WPLISTER_RESELLER_VERSION') || ( $active_tab == 'developer' ) ) : ?>
        <a href="<?php echo $wpl_settings_url; ?>&tab=developer"  class="nav-tab <?php echo $active_tab == 'developer' ? 'nav-tab-active' : ''; ?>"><?php echo __( 'Developer', 'wp-lister-for-amazon' ) ?></a>
        <?php endif; ?>


    </h2>  

<?php endif; ?>
