<?php

defined( 'WPINC' ) || die;
$activeTab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : 'currencies' );
?>
<div class="wrap">
    <h1>Premmerce Multi-Currency for Woocommerce</h1>

    <?php 
settings_errors();
?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=<?php 
echo  $pageSlug ;
?>&tab=currencies"
           class="nav-tab <?php 
echo  ( 'currencies' === $activeTab ? 'nav-tab-active' : '' ) ;
?>">
            <?php 
_e( 'Currencies', 'premmerce-woocommerce-multicurrency' );
?></a>

        <a href="?page=<?php 
echo  $pageSlug ;
?>&tab=settings"
           class="nav-tab <?php 
echo  ( 'settings' === $activeTab ? 'nav-tab-active' : '' ) ;
?>">
            <?php 
_e( 'Settings', 'premmerce-woocommerce-multicurrency' );
?></a>
<?php 
?>

        <?php 

if ( 'advanced_settings' === $activeTab ) {
    ?>
        <a href="?page=<?php 
    echo  $pageSlug ;
    ?>&tab=advanced_settings"
           class="nav-tab <?php 
    echo  ( 'advanced_settings' === $activeTab ? 'nav-tab-active' : '' ) ;
    ?>">
            <?php 
    _e( 'Advanced Settings', 'premmerce-woocommerce-multicurrency' );
    ?></a>
        <?php 
}

?>


    </h2>

<?php 
switch ( $activeTab ) {
    case 'currencies':
        include 'tabs/currencies.php';
        break;
    case 'settings':
        include 'tabs/settings.php';
        break;
    case 'advanced_settings':
        include 'tabs/advanced-settings.php';
        break;
    default:
        $updatersTable->prepare_items();
        $updatersTable->display();
}
?>
</div>