<?php if (!defined("ABSPATH")) die("go away!"); ?>

<div id="leadster">
    <div class="padding">
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
            <input name="leadster_nonce" type="hidden" value="<?php echo wp_create_nonce('leadster-nonce'); ?>" />
            <input name="action" type="hidden" value="leadster_script_code">
            <img
                src="<?php echo esc_url(LEADSTER_DIR_URL . 'assets/images/leadster.gif') ?>"
                alt="Leadster"
                title="Leadster"
            />

            <h1><?php esc_html_e("Marketing Conversacional: O Futuro da geração de Leads", "leadster"); ?></h1>

            <div class="form-area">
                <p>
                    <?php
                    wp_kses(
                        _e("Acesse o <a href=\"https://app.leadster.com.br/implementation\" target=\"_blank\">Painel de Implementação</a><br/>Onde você vai encontrar o identificador único para seu script na Leadster", "leadster"),
                        array(
                            'a' => array(
                                'class'  => array(),
                                'href'   => array(),
                                'target' => array(),
                            ),
                        )
                    );
                    ?>
                </p>

                <p><?php esc_html_e("Com isso basta inserir o código no campo abaixo e salvar. Muito fácil :)", "leadster"); ?></p>

                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="leadster-script-code"><?php esc_html_e("Identificador único Leadster", "leadster"); ?></label>
                            </th>
                            <td>
                                <input
                                        type="text"
                                        name="leadster-script-code"
                                        value="<?php echo esc_attr(get_option('leadster-script-code')); ?>"
                                        autocomplete="off"
                                />
                                <p>(<?php esc_html_e("Deixe em branco para desabilitar", "leadster"); ?>)</p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <?php submit_button(esc_html_e("Salvar e ativar", "leadster")); ?>

                <hr/>
                <p class="warning"><strong><?php esc_html_e("Importante!", "leadster"); ?></strong></p>
                <p><strong><?php esc_html_e("Caso seu site tenha algum mecanismo de cache, é necessário reiniciar o cache.", "leadster"); ?></strong></p>
                <p><strong><?php esc_html_e("Assim o chatbot já deve funcionar.", "leadster"); ?></strong></p>
                <hr>
                <p><?php esc_html_e("Versão:", "leadster"); ?> 1.2.1</p>
            </div>
        </form>
    </div>
</div>


