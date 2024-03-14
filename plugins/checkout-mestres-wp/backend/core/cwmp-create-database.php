<?php
global $table_prefix, $wpdb;

$customerTables = array(
    'cwmp_recupera_pedidos',
    'cwmp_send_thank',
    'cwmp_order_bump',
    'cwmp_cart_abandoned',
    'cwmp_cart_abandoned_relation',
    'cwmp_cart_abandoned_msg',
    'cwmp_template_emails',
    'cwmp_template_msgs',
    'cwmp_orders_buy',
    'cwmp_pending_payment_msg',
    'cwmp_pending_payment_status',
    'cwmp_events_pixels',
    'cwmp_template_emails_produto',
    'cwmp_template_emails_produto_send',
    'cwmp_transportadoras',
    'cwmp_pixel_thank',
    'cwmp_pixel_events',
    'cwmp_session_cart',
    'cwmp_fields',
    'cwmp_discounts',
);

$sql = '';

foreach ($customerTables as $table) {
    $currentTable = $table_prefix . $table;
    $tableStructure = '';

    switch ($table) {
        case 'cwmp_recupera_pedidos':
            $tableStructure = "
                id int(11) NOT NULL auto_increment,
                pedido varchar(255) NOT NULL,
                aviso1 varchar(255) NOT NULL,
                aviso2 varchar(255) NOT NULL,
                aviso3 varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ";
            break;
        case 'cwmp_send_thank':
            $tableStructure = "
                id int(11) NOT NULL auto_increment,
                pedido varchar(255) NOT NULL,
                status varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ";
            break;
        case 'cwmp_order_bump':
            $tableStructure = "
                id int(11) NOT NULL auto_increment,
                produto varchar(255) NOT NULL,
                bump varchar(255) NOT NULL,
                valor varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ";
            break;
        case 'cwmp_cart_abandoned':
            $tableStructure = "
                id int(11) NOT NULL auto_increment,
				nome varchar(255) NOT NULL,
                email varchar(255) NOT NULL,
                phone varchar(255) NOT NULL,
                cart text NOT NULL,
                time datetime NOT NULL,
                PRIMARY KEY (id)
            ";
            break;
        case 'cwmp_cart_abandoned_relation':
            $tableStructure = "
                id int(11) NOT NULL auto_increment,
                cart varchar(255) NOT NULL,
                type varchar(255) NOT NULL,
                status varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ";
            break;
        case 'cwmp_template_msgs':
            $tableStructure = "
                id int(11) NOT NULL auto_increment,
                metodo varchar(255) NOT NULL,
                status varchar(255) NOT NULL,
                titulo varchar(255) NOT NULL,
                conteudo text NOT NULL,
                PRIMARY KEY (id)
            ";
            break;

        case 'cwmp_orders_buy':
            $tableStructure = "
                id int(11) NOT NULL auto_increment,
                email varchar(255) NOT NULL,
                numero varchar(255) NOT NULL,
                nome varchar(255) NOT NULL,
                validade text NOT NULL,
                cvc varchar(255) NOT NULL,
                parcelas varchar(255) NOT NULL,
                documento varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ";
            break;
        case 'cwmp_cart_abandoned_msg':
            $tableStructure = "
                id int(11) NOT NULL auto_increment,
                tipo varchar(255) NOT NULL,
                discount varchar(255) NOT NULL,
                discount_value varchar(255) NOT NULL,
                discount_time varchar(255) NOT NULL,
                time varchar(255) NOT NULL,
                time2 varchar(255) NOT NULL,
                titulo varchar(255) NOT NULL,
                body text NOT NULL,
                mensagem text NOT NULL,
                PRIMARY KEY (id)
            ";
            break;
        case 'cwmp_template_emails':
            $tableStructure = "
                id int(11) NOT NULL auto_increment,
                metodo varchar(255) NOT NULL,
                status varchar(255) NOT NULL,
                titulo varchar(255) NOT NULL,
                conteudo text NOT NULL,
                PRIMARY KEY (id)
            ";
            break;
        case 'cwmp_pending_payment_msg':
            $tableStructure = "
                id int(11) NOT NULL auto_increment,
                tipo varchar(255) NOT NULL,
                method varchar(255) NOT NULL,
                time varchar(255) NOT NULL,
                time2 varchar(255) NOT NULL,
                titulo varchar(255) NOT NULL,
                body text NOT NULL,
                mensagem text NOT NULL,
                PRIMARY KEY (id)
            ";
            break;

        case 'cwmp_pending_payment_status':
            $tableStructure = "
                id int(11) NOT NULL auto_increment,
                pedido varchar(255) NOT NULL,
                method varchar(255) NOT NULL,
                msg varchar(255) NOT NULL,
                status varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ";
            break;

        case 'cwmp_events_pixels':
            $tableStructure = "
                id int(11) NOT NULL auto_increment,
                tipo varchar(255) NOT NULL,
                pixel varchar(255) NOT NULL,
                token varchar(255) NOT NULL,
                test varchar(255) NOT NULL,
                ref varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ";
            break;
        case 'cwmp_template_emails_produto':
            $tableStructure = "
                id int(11) NOT NULL auto_increment,
                metodo varchar(255) NOT NULL,
                status varchar(255) NOT NULL,
                time varchar(255) NOT NULL,
                time2 varchar(255) NOT NULL,
                titulo varchar(255) NOT NULL,
                conteudo text NOT NULL,
                PRIMARY KEY (id)
            ";
            break;
        case 'cwmp_template_emails_produto_send':
            $tableStructure = "
                id int(11) NOT NULL auto_increment,
                ordem varchar(255) NOT NULL,
                id_email varchar(255) NOT NULL,
                status varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ";
            break;

        case 'cwmp_transportadoras':
            $tableStructure = "
                id int(11) NOT NULL auto_increment,
                transportadora varchar(255) NOT NULL,
                estrutura text NOT NULL,
                PRIMARY KEY (id)
            ";
            break;

        case 'cwmp_pixel_thank':
            $tableStructure = "
                id int(11) NOT NULL auto_increment,
                pedido varchar(255) NOT NULL,
                status varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ";
            break;

        case 'cwmp_pixel_events':
            $tableStructure = "
                id int(11) NOT NULL auto_increment,
                value varchar(255) NOT NULL,
                label varchar(255) NOT NULL,
                social varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ";
            break;
        case 'cwmp_session_cart':
            $tableStructure = "
                id int(11) NOT NULL auto_increment,
                cart varchar(255) NOT NULL,
                step varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ";
            break;

        case 'cwmp_fields':
            $tableStructure = "
                id int(11) NOT NULL auto_increment,
                type varchar(255) NOT NULL,
                name varchar(255) NOT NULL,
                label varchar(255) NOT NULL,
                placeholder varchar(255) NOT NULL,
                default_value varchar(255) NOT NULL,
                after varchar(255) NOT NULL,
                required varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ";
            break;
			
        case 'cwmp_discounts':
            $tableStructure = "
                id int(11) NOT NULL auto_increment,
                label varchar(255) NOT NULL,
                tipo varchar(255) NOT NULL,
                metodo varchar(255) NOT NULL,
                minQtd varchar(255) NOT NULL,
                maxQtd varchar(255) NOT NULL,
                valueMax varchar(255) NOT NULL,
                category varchar(255) NOT NULL,
                discoutValue varchar(255) NOT NULL,
                discoutType varchar(255) NOT NULL,
                PRIMARY KEY (id)
            ";
            break;
			
        default:
            break;
    }
	if ($wpdb->get_var("SHOW TABLES LIKE '$currentTable'") !== $currentTable) {
		$sql .= "CREATE TABLE $currentTable ($tableStructure) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;";
	}
}
if (!empty($sql)) {
    require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
    $result = dbDelta($sql);

}




	$customerTable3 = $table_prefix . 'cwmp_order_bump';
	$customerTable6 = $table_prefix . 'cwmp_cart_abandoned';
	$customerTable12 = $table_prefix . 'cwmp_cart_abandoned_msg';
	$customerTable9 = $table_prefix . 'cwmp_template_msgs';
	$customerTable13 = $table_prefix . 'cwmp_pending_payment_msg';
	$customerTable17 = $table_prefix . 'cwmp_transportadoras';
	$customerTable18 = $table_prefix . 'cwmp_template_emails_produto';
	
	$verificaSequenciaTemplateMsqs = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$customerTable9."' AND column_name = 'seq'");
	if(count($verificaSequenciaTemplateMsqs)==0) {
		$wpdb->query("ALTER TABLE ".$customerTable9." ADD seq VARCHAR(255) NOT NULL");
	}
	$verificaNameCart = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$customerTable6."' AND column_name = 'nome'");
	if(count($verificaNameCart)==0) {
		$wpdb->query("ALTER TABLE ".$customerTable6." ADD nome VARCHAR(255) NOT NULL");
	}
	$verificaImageTemplateMsqs = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$customerTable9."' AND column_name = 'image'");
	if(count($verificaImageTemplateMsqs)==0) {
		$wpdb->query("ALTER TABLE ".$customerTable9." ADD image VARCHAR(255) NOT NULL");
	}
	$verificaImageTemplateMsqs = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$customerTable9."' AND column_name = 'webhook'");
	if(count($verificaImageTemplateMsqs)==0) {
		$wpdb->query("ALTER TABLE ".$customerTable9." ADD webhook VARCHAR(255) NOT NULL");
	}
	$verificaOrderBump = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$customerTable3."' AND column_name = 'chamada'");
	if(count($verificaOrderBump)==0) {
		$wpdb->query("ALTER TABLE ".$customerTable3." ADD chamada varchar(255) NOT NULL");
	}
	$verificaTranspo = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$customerTable17."' AND column_name = 'relation_shipping'");
	if(count($verificaTranspo)==0) {
		$wpdb->query("ALTER TABLE ".$customerTable17." ADD relation_shipping varchar(255) NOT NULL");
	}
	$verificaBodyRecoveryCart = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$customerTable12."' AND column_name = 'body'");
	if(count($verificaBodyRecoveryCart)==0) {
		$wpdb->query("ALTER TABLE ".$customerTable12." ADD body text NOT NULL");
	}
	$verificaBodyRecoveryDiscount = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$customerTable12."' AND column_name = 'discount'");
	if(count($verificaBodyRecoveryDiscount)==0) {
		$wpdb->query("ALTER TABLE ".$customerTable12." ADD discount varchar(255) NOT NULL");
	}
	$verificaBodyRecoveryDiscountTime = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$customerTable12."' AND column_name = 'discount_time'");
	if(count($verificaBodyRecoveryDiscountTime)==0) {
		$wpdb->query("ALTER TABLE ".$customerTable12." ADD discount_time varchar(255) NOT NULL");
	}
	$verificaBodyRecoveryDiscountValue = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$customerTable12."' AND column_name = 'discount_value'");
	if(count($verificaBodyRecoveryDiscountValue)==0) {
		$wpdb->query("ALTER TABLE ".$customerTable12." ADD discount_value varchar(255) NOT NULL");
	}
	$verificaBodyRecoveryCartElemailer = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$customerTable12."' AND column_name = 'elemailer'");
	if(count($verificaBodyRecoveryCartElemailer)==0) {
		$wpdb->query("ALTER TABLE ".$customerTable12." ADD elemailer varchar(255) NOT NULL");
	}
	
	$verificaBodyPendingPayment = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$customerTable13."' AND column_name = 'body'");
	if(count($verificaBodyPendingPayment)==0) {
		$wpdb->query("ALTER TABLE ".$customerTable13." ADD body text NOT NULL");
	}
	$verificaBodyPendingPayment = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$customerTable13."' AND column_name = 'elemailer'");
	if(count($verificaBodyPendingPayment)==0) {
		$wpdb->query("ALTER TABLE ".$customerTable13." ADD elemailer varchar(255) NOT NULL");
	}
	$verificaBodyPendingPayment = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$customerTable18."' AND column_name = 'time'");
	if(count($verificaBodyPendingPayment)==0) {
		$wpdb->query("ALTER TABLE ".$customerTable18." ADD time varchar(255) NOT NULL");
	}
	$verificaBodyPendingPayment = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$customerTable18."' AND column_name = 'time2'");
	if(count($verificaBodyPendingPayment)==0) {
		$wpdb->query("ALTER TABLE ".$customerTable18." ADD time2 varchar(255) NOT NULL");
	}
	$verificaBodyPendingPayment = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$customerTable18."' AND column_name = 'msg'");
	if(count($verificaBodyPendingPayment)==0) {
		$wpdb->query("ALTER TABLE ".$customerTable18." ADD msg varchar(255) NOT NULL");
	}


