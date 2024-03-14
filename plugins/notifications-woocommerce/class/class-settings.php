<?php 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

require_once dirname(__FILE__) . '/../includes/acf/acf.php';

?>

<style>
.woo-wpp-notificacoes-card {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #c3c4c7;
    max-width: 96%;
    margin-top: 20px;
}
</style>

<div class="woo-wpp-notificacoes-card">   
    <?php acf_form_head(); ?>
    <div id="primary" class="content-area">
        <div id="content" class="site-content" role="main">
      
        <?php acf_form(array(
            'post_id'       => 9991274,
            'post_title'    => false,
            'post_content'  => false,
            'submit_value'  => __('Salvar configurações'),
            'html_updated_message'  => '<div id="message" class="updated"><p>Configurações salvas com sucesso!</p></div>',
        )); ?>
    
        </div>
    </div>
</div>