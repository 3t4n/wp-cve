<?php
	$menu_nonce = wp_create_nonce('menu_nonce');
    if (isset($_GET['page']) && $_GET['page'] === 'cwmp_admin_menu') {
        if (isset($_GET['_wpnonce']) && wp_verify_nonce(sanitize_key($_GET['_wpnonce']), 'menu_nonce')) {
            echo '<div class="notice notice-success is-dismissible"><p>Ação realizada com sucesso!</p></div>';
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>Erro: Nonce inválido ou ausente.</p></div>';
            return;
        }
    }
	if(isset($_GET['action'])){
		if($_GET['action']=="add"){
			global $wpdb;
			$table_name = $wpdb->prefix . 'cwmp_discounts';
			
			$label = ""; if(isset($_POST['label'])){ $label = $_POST['label']; }
			$tipo = $_POST['tipo'];
			if($tipo==1){
				$metodo = $_POST['metodoPayment'];
			}elseif($tipo==2){
				$metodo = $_POST['metodoShipping'];
			}elseif($tipo==3){
				$metodo = $_POST['product'];
			}else{
				$metodo = "";
			}
			$discoutValue = ""; if(isset($_POST['discoutValue'])){ $discoutValue = $_POST['discoutValue']; }
			$discoutType = ""; if(isset($_POST['discoutType'])){ $discoutType = $_POST['discoutType']; }
			$min = ""; if(isset($_POST['minQtd'])){ $min = $_POST['minQtd']; }
			$max = ""; if(isset($_POST['maxQtd'])){ $max = $_POST['maxQtd']; }
			$max = ""; if(isset($_POST['valueMax'])){ $max = $_POST['valueMax']; }
			$category = ""; if(isset($_POST['category'])){ $category = $_POST['category']; }
			$add_bump = $wpdb->insert($table_name, array(
				'label' => $label,
				'tipo' => $tipo,
				'metodo' => $metodo,
				'discoutValue' => $discoutValue,
				'discoutType' => $discoutType,
				'minQtd' => $min,
				'maxQtd' => $_POST['maxQtd'],
				'valueMax' => $max,
				'category' => $category,
			));
			
		}
		if($_GET['action']=="edit"){
			global $wpdb;
			$table_name = $wpdb->prefix . 'cwmp_discounts';
		
			$label = ""; if(isset($_POST['label'])){ $label = $_POST['label']; }
			$tipo = $_POST['tipo'];
			if($tipo==1){
				$metodo = $_POST['metodoPayment'];
			}elseif($tipo==2){
				$metodo = $_POST['metodoShipping'];
			}elseif($tipo==3){
				$metodo = $_POST['product'];
			}else{
				$metodo = "";
			}
			$discoutValue = ""; if(isset($_POST['discoutValue'])){ $discoutValue = $_POST['discoutValue']; }
			$discoutType = ""; if(isset($_POST['discoutType'])){ $discoutType = $_POST['discoutType']; }
			$min = ""; if(isset($_POST['minQtd'])){ $min = $_POST['minQtd']; }
			$max = ""; if(isset($_POST['maxQtd'])){ $max = $_POST['maxQtd']; }
			$max = ""; if(isset($_POST['valueMax'])){ $max = $_POST['valueMax']; }
			$category = ""; if(isset($_POST['category'])){ $category = $_POST['category']; }
			
			$add_bump = $wpdb->update($table_name, array(
				'label' => $label,
				'tipo' => $tipo,
				'metodo' => $metodo,
				'discoutValue' => $discoutValue,
				'discoutType' => $discoutType,
				'minQtd' => $min,
				'maxQtd' => $_POST['maxQtd'],
				'valueMax' => $max,
				'category' => $category,
			),array('id'=>$_POST['id']));
			
		}
		if($_GET['action']=="delete"){
			global $wpdb;
			$table_name = $wpdb->prefix . 'cwmp_discounts';
			$add_bump = $wpdb->delete($table_name, array('id'=>$_GET['id']));
		}
	}
	?>
			<?php
			$args = array(
				'box'=>array(
					'title'=>__('Discounts', 'checkout-mestres-wp'),
					'description'=>__('Create discounts to increase your sales', 'checkout-mestres-wp'),
					'button'=>array(
						'label'=>__('Create Discount', 'checkout-mestres-wp'),
						'url'=>'admin.php?page=cwmp_admin_parcelamento&type=pagamentos.discounts-method-payment-add',
					),
					'help'=>'https://docs.mestresdowp.com.br',
					'patch'=>'admin.php?page=cwmp_admin_parcelamento&type=pagamentos.discounts-method-payment-',
					'bd'=>array(
						'name'=> 'cwmp_discounts',
						'lines'=> array(
							'0'=>array(
								'type'=>'text',
								'value'=>'label',
							),
							'1'=>array(
								'type'=>'text',
								'value'=>'tipo',
							),

						),
						'order'=>array(
							'by'=>'ASC',
							'value'=>'metodo'
						)
					)
				),
			);
			echo cwmpAdminCreateLists($args);
			include "config.copyright.php";
