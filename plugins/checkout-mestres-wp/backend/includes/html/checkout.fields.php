<?php
	$menu_nonce = wp_create_nonce('menu_nonce');
    if (isset($_GET['page']) && $_GET['page'] === 'cwmp_admin_menu') {
        // Nonce válido, execute as ações necessárias
        if (isset($_GET['_wpnonce']) && wp_verify_nonce(sanitize_key($_GET['_wpnonce']), 'menu_nonce')) {
            echo '<div class="notice notice-success is-dismissible"><p>Ação realizada com sucesso!</p></div>';
        } else {
            // Nonce inválido, redirecionar ou lidar com a situação de não autorizado
            echo '<div class="notice notice-error is-dismissible"><p>Erro: Nonce inválido ou ausente.</p></div>';
            return;
        }
    }
	global $wpdb;
if (isset($_GET['action'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cwmp_fields';

    switch ($_GET['action']) {
        case "add":
            $add_bump = $wpdb->insert($table_name, array(
                'type' => $_POST['type'],
                'name' => $_POST['name'],
                'label' => $_POST['label'],
                'placeholder' => $_POST['placeholder'],
                'default_value' => $_POST['default_value'],
                'after' => $_POST['after'],
                'required' => $_POST['required']
            ));
            break;

        case "edit":
            $add_bump = $wpdb->update($table_name, array(
                'type' => $_POST['type'],
                'name' => $_POST['name'],
                'label' => $_POST['label'],
                'placeholder' => $_POST['placeholder'],
                'default_value' => $_POST['default_value'],
                'after' => $_POST['after'],
                'required' => $_POST['required']
            ), array('id' => $_POST['id']));
            break;

        case "delete":
            $add_bump = $wpdb->delete($table_name, array('id' => $_GET['id']));
            break;

        default:
            // Lógica padrão caso a ação não seja reconhecida
            break;
    }
}

			$args = array(
				'box'=>array(
					'title'=>__( 'Fields', 'checkout-mestres-wp'),
					'description'=>__( 'Create a custom field for your checkout', 'checkout-mestres-wp'),
					'button'=>array(
						'label'=>__( 'Create Field', 'checkout-mestres-wp'),
						'url'=>'admin.php?page=cwmp_admin_checkout&type=checkout.fields-add',
					),
					'help'=>'https://docs.mestresdowp.com.br',
					'patch'=>'admin.php?page=cwmp_admin_checkout&type=checkout.fields-',
					'bd'=>array(
						'name'=> 'cwmp_fields',
						'lines'=> array(
							'0'=>array(
								'type'=>'text',
								'value'=>'name',
							),
							'1'=>array(
								'type'=>'text',
								'value'=>'name',
							),
							'2'=>array(
								'type'=>'text',
								'value'=>'name',
							),
						),
						'order'=>array(
							'by'=>'ASC',
							'value'=>'name'
						)
					)
				),
			);
			echo cwmpAdminCreateLists($args);
			include "config.copyright.php";
