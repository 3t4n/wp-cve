<?php

	function bvlt_init_admin() {
        
        wp_register_style('bvlt_styles', plugins_url('../../assets/css/bv-styles.css',__FILE__ ));
        wp_enqueue_style('bvlt_styles');
        
        
        if (class_exists( 'WooCommerce' )) {
            $uri = 'https://auth.bookvault.app/api/WooAuth?storeUrl='.get_site_url();
            $response = wp_remote_get( $uri,
            array(
                'timeout'     => 120,
                'httpversion' => '1.1',
            ));
            $response = json_decode(wp_remote_retrieve_body($response), true);
            if (array_key_exists("Token", $response)) {
				update_option("bvlt_token", $response["Token"]);
				update_option("bvlt_storeid", $response["StoreID"]);
				update_option("bvlt_auth", $response["Authenticated"]);
                if ($response["Authenticated"] === true) {
					?>
                    <center>
                        <img class="imgLogo" src="<?php echo esc_attr(plugins_url( '../../assets/img/bv-logo.png', __FILE__ )); ?>" alt="Bookvault Logo">
                        <br><br> 
                         <h2>Great News! You're all setup!</h2>
                        <p>You can now push products from your Bookvault account and have them automatically printed and dispatched!</p>
                        <br>
                        <a class="btn btn-danger"  target="_blank" href="https://portal.bookvault.app/apps">Add Products</a>
            			<a class="btn btn-danger"  target="_blank" href="https://portal.bookvault.app/orders">View Orders</a>
                        
                    </center> 
                    <?php
                } else {
                    ?>
                    <center>
                        <img class="imgLogo" src="<?php echo esc_attr(plugins_url( '../../assets/img/bv-logo.png', __FILE__ )); ?>" alt="Bookvault Logo">
                    <h2>Welcome to the bookvault WooCommerce app!</h2>
                <br />
                <iframe title="vimeo-player" src="https://player.vimeo.com/video/692864260?h=44f6862599" width="640" height="360" frameborder="0" allowfullscreen></iframe>
                <br>
                <a class="btn btn-danger" id="btnCreate" runat="server" href="https://auth.bookvault.app/authorize?action=register&Store=<?php echo esc_attr(get_site_url()); ?>/">Create An Account</a>
                <a class="btn btn-danger" id="btnLogn" runat="server" href="https://auth.bookvault.app/authorize?Store=<?php echo esc_attr(get_site_url()); ?>/">Log In</a>
                    
                    </center>
                    <?php
                }
            } else {
                ?><h1>An Error Has Occured</h1><?php
            }
    	} else {
        	?>
        		<div class="wrap">
        			<center>
        			    <br><br>
        				 <img class="imgLogo" src="<?php echo esc_attr(plugins_url( '../../assets/img/bv-logo.png', __FILE__ )) ?>" alt="Bookvault Logo">
        				<br>
        				<h1>Oops! It doesn't look like you have the WooCommerce app installed & active. You must do this to use the Bookvault app</h1>
        				<p>Installing WooCommerce is simple, <a href="https://woocommerce.com/document/start-with-woocommerce-in-5-steps/">Find out more</a></p>
        			</center>
        		</div>
           <?php
    	}
	}

?>
