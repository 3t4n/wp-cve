<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly <div class="wrap">    
?>
<div class="wrap">
    <form method="post" >
        <?php
        $myListTable->prepare_items();
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Max-Age: 1000');
        if (isset($_POST['s'])) {
            $myListTable->prepare_items(sanitize_text_field($_POST['s']));
        } else {
            $myListTable->prepare_items();
        }
        $myListTable->search_box(__('Buscar pedidos'), 'search_id');
        $myListTable->display();

        ?>
    </form>
</div>