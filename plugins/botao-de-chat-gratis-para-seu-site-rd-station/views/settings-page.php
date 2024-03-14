<?php

$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'initial_config';
$options = get_option('rd_button_chat_options');
$setup_options = get_option('rd_button_chat_setup_options');
$setup_email = $setup_options['rd_button_chat_setup_email'];

function rd_button_chat_conversion() {
    $options = get_option('rd_button_chat_options');
    $options_backup = get_option("rd_button_chat_backup_options");

    $email = isset($options_backup['rd_button_chat_email']) ? $options_backup['rd_button_chat_email'] : '';
    $phone = isset($options_backup['rd_button_chat_phone']) ? $options_backup['rd_button_chat_phone'] : '';
    $message = isset($options_backup['rd_button_chat_message']) ? $options_backup['rd_button_chat_message'] : '';
    $site = home_url();
    $active = $options['rd_button_chat_show_button'] ? "on" : "off";

    $url = 'https://hooks.zapier.com/hooks/catch/13395002/361xtmr/';

    $args = array(
        'body' => array(
            'email' => $email,
            'phone' => $phone,
            'message' => $message,
            'site' => $site,
            'active' => $active,
        ),
    );

    if (!empty($options_backup)) {
        $response = wp_remote_post($url, $args);
    }
}

?>

<?php if (!isset($setup_email) || empty($setup_email)) : ?>

<main class="rdwpp_setup-content">
  <div class="rdwpp_setup-container">
    <h2>Seu plugin ”Botão de Chat Grátis│ RD Station” foi instalado com Sucesso</h2>
    <p>Adicione o seu e-mail e vamos começar!</p>
    <form class="rdwpp_setup-form" action="options.php" method="post">
      <?php
                settings_fields('rd_button_chat_setup');
                do_settings_sections('rd_button_chat_page_setup');
                submit_button(esc_html__('Configurar seu botão', 'rd-button-chat'));
                ?>
    </form>
  </div>
</main>

<?php endif; ?>

<?php if (isset($setup_email) && !empty($setup_email)) : ?>

<?php rd_button_chat_conversion(); ?>

<div class="wrap rdwpp_wrap">
  <div class="rdwpp_header">
    <img src="<?php echo RD_BUTTON_CHAT_URL . '/assets/img/symbol.svg' ?>"></img>
    <h1>Botão de Chat Grátis para Seu Site │ RD Station</h1>
    <a href="https://www.rdstation.com/" target="_blank">Obter a versao pro</a>
  </div>

  <h2 class="nav-tab-wrapper rdwpp_nav">

    <a href="?page=rd_button_chat_admin&tab=initial_config" class="nav-tab  <?php echo $active_tab == 'initial_config' ?  'nav-tab-active' : ''; ?> "><?php esc_html_e('Configurações Iniciais', 'rd-button-chat'); ?></a>

    <a href="?page=rd_button_chat_admin&tab=visibility" class="nav-tab <?php echo $active_tab == 'visibility' ?  'nav-tab-active' : ''; ?> "><?php esc_html_e('Visibilidade', 'rd-button-chat'); ?></a>

    <a href="?page=rd_button_chat_admin&tab=appearance" class="nav-tab <?php echo $active_tab == 'appearance' ?  'nav-tab-active' : ''; ?> "><?php esc_html_e('Aparência', 'rd-button-chat'); ?></a>
  </h2>

  <main class="rdwpp_content">
    <div class="rdwpp_container">
      <?php if ($active_tab == 'initial_config') : ?>
      <div class="rdwpp_tab-initial-config">
        <h4>Configurações iniciais</h4>
        <span class="dashicons dashicons-info">
          <p>Defina o endereço das páginas que o seu botão de chat será exibido para o visitante.</p>
        </span>
      </div>
      <?php endif; ?>

      <?php if ($active_tab == 'visibility') : ?>
      <div class="rdwpp_tab-visibility">
        <h4>Páginas que o botão de chat será exibido</h4>
        <span class="dashicons dashicons-info">
          <!-- <p>Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.</p> -->
        </span>
      </div>
      <p>Para que o botão de chat seja exibido nos endereços descritos, é preciso que o código de
        monitoramento esteja instalado na página correspondente. Saiba mais</p>
      <?php endif; ?>

      <?php if ($active_tab == 'appearance') : ?>
      <div class="rdwpp_tab-appearance">
        <h4>Aparência</h4>
        <span class="dashicons dashicons-info">
          <p>Esta funcionalidade está disponível no plano Light do RD Station Marketing . Faça um teste grátis
            agora e saiba mais link para o teste.</p>
        </span>
        <span>Upgrade</span>
      </div>
      <p>Esta funcionalidade está disponível no plano Light do RD Station Marketing . Faça um teste grátis agora e
        saiba mais link para o teste.</p>
      <?php endif; ?>


      <form class="<?php $active_tab == 'appearance' ? "rdwpp_form-appearance" : "" ?>" action="options.php" method="post">
        <?php
                    switch ($active_tab) {
                        case 'visibility':
                            require(RD_BUTTON_CHAT_PATH . 'views/form-visibility.php');
                            break;
                        case 'appearance':
                            require(RD_BUTTON_CHAT_PATH . 'views/form-appearance.php');
                            break;
                        default:
                            require(RD_BUTTON_CHAT_PATH . 'views/form-config.php');
                            break;
                    }
                    if ($active_tab == 'initial_config') {
                        submit_button(esc_html__('Salvar alterações', 'rd-button-chat'));
                    }
                    if ($active_tab == 'visibility') { ?>
        <input type="submit" id="submit" value="Salvar alterações" disabled>
        <?php } ?>
      </form>
    </div>
    <div class="rdwpp_aside">
      <h2>Quer fazer um teste do RD Station Marketing?</h2>
      <p>Gratuito e sem compromisso. Experimente agora a ferramenta para automação de Marketing Digital tudo-em-um
        ideal para negócios B2B.</p>

      <a href="https://app.rdstation.com.br/signup?trial_origin=wp_plugin_whatsapp" target="_blank" rel="noreferrer noopener">Teste grátis</a>

    </div>
  </main>
</div>

<?php endif; ?>