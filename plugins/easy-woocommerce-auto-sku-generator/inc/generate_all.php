<?php
add_action( 'wp_ajax_load_posts_by_ajax', 'load_posts_by_ajax_callback' );
function load_posts_by_ajax_callback() {

	$skuautoffxf_auto_number =  get_option( 'skuautoffxf_auto_number' );
	$skuautoffxf_auto_id             = get_option( 'skuautoffxf_auto_ID' );


	function shapeSpace_random_string($length) {

		if ( empty( get_option( 'skuautoffxf_letters_and_numbers' ) ) ) {
			$characters = '123456789';
		} elseif ( 'ffxf_numbers' === get_option( 'skuautoffxf_letters_and_numbers' ) ) {
			$characters = '123456789';
		} elseif ( 'ffxf_letters' === get_option( 'skuautoffxf_letters_and_numbers' ) ) {
			$characters = 'QWERTYUIOPASDFGHJKLZXCVBNM';
		} elseif ( 'ffxf_landnum' === get_option( 'skuautoffxf_letters_and_numbers' ) ) {
			$characters = '123456789QWERTYUIOPASDFGHJKLZXCVBNM123456789';
		}


		$strlength = strlen($characters);

		$random = '';

		for ($i = 0; $i < $length; $i++) {
			$random .= $characters[rand(0, $strlength - 1)];
		}
		return $random.get_option( 'skuautoffxf_suffix' ).$_GET['number_dop'];

	}

	global $paged;
	check_ajax_referer('load_more_posts', 'security');
	$paged = empty($_GET['paged']) ? 1 : (int) $_GET['paged'];
	$args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => '1',
		'paged' => $paged,
	);

	$my_posts = new WP_Query( $args );
	if ( $my_posts->have_posts() ) :
		?>
		<?php while ( $my_posts->have_posts() ) : $my_posts->the_post();
		if ( 'yes' === $skuautoffxf_auto_id ) {
			$skuautoffxf_id = get_the_id();
		} else {
			$skuautoffxf_id = '';
		}
		global $product;

		$skuautoffxf_auto_variant = get_option( 'skuautoffxf_auto_variant' );

		?>
		<div>
			<?php //echo get_post_meta( get_the_id(), '_sku', true  ); ?>
			<div>
				<p class="title_product">
					<?php

					$varioant_message_1  =  __( 'Variable product. Depending on the settings, SKU variations may be generated.', 'easy-woocommerce-auto-sku-generator' );
					$varioant_message_2  =  __( 'SKU already exists and has not been recreated', 'easy-woocommerce-auto-sku-generator' );
					$varioant_message_3  =  __( 'SKU was not detected but was recreated!', 'easy-woocommerce-auto-sku-generator' );
					$varioant_message_4  =  __( 'SKU has been recreated', 'easy-woocommerce-auto-sku-generator' );
					$varioant_message_5  =  __( 'Regular product', 'easy-woocommerce-auto-sku-generator' );
					$varioant_message_6  =  __( 'SKU not detected', 'easy-woocommerce-auto-sku-generator' );


					if ( $product->is_type( 'variable' ) && 'no' === $skuautoffxf_auto_variant ) {
						echo '<span data-tooltip-right data-tooltip="' . $varioant_message_1 . '"><i class="dashicons dashicons-tickets-alt"></i></span> ';
					}elseif( isset( $_GET['checked'] ) && $_GET['checked'] === '0' ){

						if ( get_post_meta( get_the_ID(), '_sku', true ) ){
							echo '<span data-tooltip-right data-tooltip="'. $varioant_message_2 . '"><i class="dashicons dashicons-migrate"></i></span>';
						}else{
							echo '<span data-tooltip-right data-tooltip="' . $varioant_message_3  . '"><i  style="color: #FF5722;" class="dashicons dashicons-dismiss"></i></span>';
						}

					}elseif( isset( $_GET['checked'] ) && $_GET['checked'] === '1' ){
						echo '<span data-tooltip-right data-tooltip="' . $varioant_message_4 . '"><i class="dashicons dashicons-admin-appearance"></i></span>  ';
					}else{
						echo '<span data-tooltip-right data-tooltip="' . $varioant_message_5  . '"><i class="dashicons dashicons-paperclip"></i></span>  ';
					}
					the_title();
					?>
				</p>
			</div>

			<div>
				<span><?php echo __( 'New SKU:', 'easy-woocommerce-auto-sku-generator' ); ?> </span>
				<span class="slr">
                        <?php
                        if ( 'ffxf_slug' === get_option( 'skuautoffxf_letters_and_numbers' ) ) {


	                        if ( isset( $_GET['checked'] ) && $_GET['checked'] === '1' ){

		                        $ffxf_slug = get_post_field( 'post_name', get_post() );
		                        update_post_meta( get_the_ID(), '_sku', $ffxf_slug );
		                        echo $ffxf_slug;

	                        }
	                        elseif ( isset( $_GET['checked'] ) && $_GET['checked'] === '0' ){

		                        if ( get_post_meta( get_the_ID(), '_sku', true ) ){
			                        echo get_post_meta( get_the_ID(), '_sku', true );
		                        }else{
			                        echo $varioant_message_6; // артикул не обнаружен
		                        }

	                        }
                        }else{

	                        if ( isset( $_GET['checked'] ) && $_GET['checked'] === '1' ){
		                        if ( '0' === $skuautoffxf_auto_number ) {
			                        $skuautoffxf_auto_number = '';
		                        }elseif ( empty( $skuautoffxf_auto_number ) ) {
			                        $skuautoffxf_auto_number = rand(4, 7);
		                        }
		                        $result_generate = shapeSpace_random_string( $skuautoffxf_auto_number );
		                        echo get_option( 'skuautoffxf_auto_prefix' ).$result_generate.$skuautoffxf_id;
		                        update_post_meta( get_the_ID(), '_sku', get_option( 'skuautoffxf_auto_prefix' ).$result_generate.$skuautoffxf_id );

	                        }
	                        elseif ( isset( $_GET['checked'] ) && $_GET['checked'] === '0' ){
		                        if ( '0' === $skuautoffxf_auto_number ) {
			                        $skuautoffxf_auto_number = rand(4, 7);
		                        }elseif ( empty( $skuautoffxf_auto_number ) ) {
			                        $skuautoffxf_auto_number = rand(4, 7);
		                        }
		                        if ( get_post_meta( get_the_ID(), '_sku', true ) ){
			                        echo get_post_meta( get_the_ID(), '_sku', true );
		                        }else{
			                        $result_generate = shapeSpace_random_string( $skuautoffxf_auto_number );
			                        echo get_option( 'skuautoffxf_auto_prefix' ).$result_generate.$skuautoffxf_id;
			                        update_post_meta( get_the_ID(), '_sku', get_option( 'skuautoffxf_auto_prefix' ).$result_generate.$skuautoffxf_id );
		                        }

	                        }



                        }

                        if ( $product->is_type( 'variable' ) && 'no' === $skuautoffxf_auto_variant ) {

                            // Include the file with the custom function.
                            require_once 'functions-plugin.php'; // Replace with the correct path to your functions.php file.

                            // Use the custom function to get the variation separator value.
                            $separator_value = get_variation_separator_value();

                            $parent_sku   = get_post_meta( get_the_ID(), '_sku', true );
                            $children_ids = $product->get_children();
                            $count        = 0;

                            // Loop through the variations Ids.
                            foreach ( $children_ids as $child_id ) {
                                $count++;

                                // Get an instance of the WC_Product_Variation object.
                                $variation = wc_get_product( $child_id );

                                // Set the prefix lenght based on variations count.
                                $prefix = sizeof( $children_ids ) < 100 ? sprintf( '%02d', $count) : sprintf( '%03d', $count);

                                // Geberate and set the sku.

                                try {

                                    $variation->set_sku( $parent_sku . $separator_value . $prefix );

                                } catch ( WC_Data_Exception $e ){


                                }

                                // Save variation.
                                $variation->save();
                            }
                        }
                        ?>
                    </span>
			</div>
		</div>
	<?php endwhile; ?>
	<?php

	endif;

	wp_die();
}