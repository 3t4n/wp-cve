<?php

add_action('admin_menu', 'sendle_shipping_debug_submenu');

function sendle_shipping_debug_submenu() {
    add_submenu_page( 'woocommerce', 'Sendle Debug', 'Sendle Debug', 'manage_options', 'sendle_debug', 'sendle_debug_page' );
    add_action( 'admin_init', 'register_sendle_shipping_debug' );
}

function register_sendle_shipping_debug() {

}

function sendle_debug_page() {

    if(file_exists(ERROR_FILE)){
        $error = file_get_contents(ERROR_FILE);
    }else{
        $error = "";
    }
    $error = htmlentities($error);
?>
<div class="wrap">
    <h1>Sendle Debug</h1>
    <form>
        <textarea style="width:100%;height:300px;"><?php echo $error; ?></textarea>
    </form>
</div>
<?php
}