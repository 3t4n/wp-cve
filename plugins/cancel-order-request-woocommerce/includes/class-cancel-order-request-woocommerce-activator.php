<?php

class Cancel_Order_Request_Woocommerce_Activator {

	public static function activate() {
		add_option('pi_cord_do_activation_redirect', true);
	}

}
