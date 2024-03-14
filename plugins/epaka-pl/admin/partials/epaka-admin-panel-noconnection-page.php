<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php echo (!empty($center)) ? "<center>" : ""?>
    <a href="/wp-admin/admin.php?page=epaka_admin_panel_login_page" style="cursor: pointer;">
        <div>
            <img width="100" height="auto" src="<?php echo plugins_url("assets/img/epaka.png",str_replace("admin/","",dirname(__FILE__))) ?>"></img>
            </br>
            <b>Brak połączenia z API Epaka.pl</b>
        </div>
    </a>
<?php echo (!empty($center)) ? "</center>" : ""?>