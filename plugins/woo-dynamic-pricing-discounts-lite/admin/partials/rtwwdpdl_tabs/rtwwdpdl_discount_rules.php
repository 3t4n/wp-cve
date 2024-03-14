<div class="clear"></div>
<div class="rtwwdpdl_rules">
	<aside>
		<ul class="subsubsub">
			<?php
			$rtwwdpdl_product_rules_active 		= '';
			$rtwwdpdl_category_rules_active 	= '';
			$rtwwdpdl_cart_rules_active 		= '';
			$rtwwdpdl_bogo_rules_active 		= '';
			$rtwwdpdl_variation_rules_active 	= '';
			$rtwwdpdl_tiered_rules_active 		= '';
			$rtwwdpdl_payment_method_active		= '';
			$rtwwdpdl_shipping_method_active	= '';
			$rtwwdpdl_attribute_active			= '';
			$rtwwdpdl_prod_tag_active			= '';
			$rtwwdpdl_next_buy_bonus				= '';
			$rtwwdpdl_nth_order_active 			= '';
			$rtwwdpdl_bogo_least 				= '';

			if( isset( $_GET[ 'rtwwdpdl_sub' ] ) )
			{
				if( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_prod_rules" )
				{
					$rtwwdpdl_product_rules_active = "current";
				}
				elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_cat_rules" )
				{
					$rtwwdpdl_category_rules_active = "current";
				}
				elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_cart_rules" )
				{
					$rtwwdpdl_cart_rules_active = "current";
				}
				elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_bogo_rules" )
				{
					$rtwwdpdl_bogo_rules_active = "current";
				}
				elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_variation_rules" )
				{
					$rtwwdpdl_variation_rules_active = "current";
				}
				elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_tiered_rules" )
				{
					$rtwwdpdl_tiered_rules_active = "current";
				}
				elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_payment_method" )
				{
					$rtwwdpdl_payment_method_active = "current";
				}
				elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_shipping_method" )
				{
					$rtwwdpdl_shipping_method_active = "current";
				}
				elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_attribute" )
				{
					$rtwwdpdl_attribute_active = "current";
				}
				elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_prod_tags" )
				{
					$rtwwdpdl_prod_tag_active = "current";
				}
				elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_next_buy_bonus" )
				{
					$rtwwdpdl_next_buy_bonus = "current";
				}
				elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_nth_order_active" )
				{
					$rtwwdpdl_nth_order_active = "current";
				}
				elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_bogo_least_active" )
				{
					$rtwwdpdl_bogo_least = "current";
				}
			}
			else{
				$rtwwdpdl_product_rules_active = "current";
			}

			echo 	"<li>";
			echo 		'<a href="'.esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_prod_rules').'" class="'.esc_attr( $rtwwdpdl_product_rules_active ).'" ><span class="rtw_rules">' . esc_html__( 'Product ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) . '</span>'. esc_html__( 'Rules', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) .'</a>';
			echo 	"</li>";

			echo 	"<li>";
			echo 		'<a href="'.esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_cat_rules').'" class="'.esc_attr( $rtwwdpdl_category_rules_active ).'" ><span class="rtw_rules">' . esc_html__( 'Category ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) . '</span>'. esc_html__( 'Rules', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) .'</a>';
			echo 	"</li>";

			echo 	"<li>";
			echo 		'<a href="'.esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_cart_rules').'" class="'.esc_attr( $rtwwdpdl_cart_rules_active ).'" ><span class="rtw_rules">' . esc_html__( 'Cart ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) . '</span>'. esc_html__( 'Rules', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) .'</a>';
			echo 	"</li>";

			echo 	"<li>";
			echo 		'<a href="'.esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_bogo_rules').'" class="'.esc_attr( $rtwwdpdl_bogo_rules_active ).'" ><span class="rtw_rules">' . esc_html__( 'BOGO ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) . '</span>'. esc_html__( 'Rules', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) .'</a>';
			echo 	"</li>";

			echo 	"<li>";
			echo 		'<a href="'.esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_variation_rules').'" class="'.esc_attr( $rtwwdpdl_variation_rules_active ).'" ><span class="rtw_rules">' . esc_html__( 'Variation\'s ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) . '</span>'. esc_html__( 'Rules', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) .'</a>';
			echo 	"</li>";

			echo 	"<li>";
			echo 		'<a href="'.esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_tiered_rules').'" class="'.esc_attr( $rtwwdpdl_tiered_rules_active ).'" ><span class="rtw_rules">' . esc_html__( 'Tiered ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) . '</span>'. esc_html__( 'Rules', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) .'</a>';
			echo 	"</li>";

			echo 	"<li>";
			echo 		'<a href="'.esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_payment_method').'" class="'.esc_attr( $rtwwdpdl_payment_method_active ).'" ><span class="rtw_rules">' . esc_html__( 'Payment ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) . '</span>'. esc_html__( 'Rules', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) .'</a>';
			echo 	"</li>";

			echo 	"<li>";
			echo 		'<a href="'.esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_attribute').'" class="'.esc_attr( $rtwwdpdl_attribute_active ).'" ><span class="rtw_rules">' . esc_html__( 'Attribute ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) . '</span>'. esc_html__( 'Rules', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) .'</a>';
			echo 	"</li>";

			echo 	"<li>";
			echo 		'<a href="'.esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_prod_tags').'" class="'.esc_attr( $rtwwdpdl_prod_tag_active ).'" ><span class="rtw_rules">' . esc_html__( 'Product Tag ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) . '</span>'. esc_html__( 'Rules', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) .'</a>';
			echo 	"</li>";

			echo 	"<li>";
			echo 		'<a href="'.esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_shipping_method').'" class="'.esc_attr( $rtwwdpdl_shipping_method_active ).'" ><span class="rtw_rules">' . esc_html__( 'Shipping ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) . '</span>'. esc_html__( 'Rules', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) .'</a>';
			echo 	"</li>";

			echo 	"<li>";
			echo 		'<a href="'.esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_next_buy_bonus').'" class="'.esc_attr( $rtwwdpdl_next_buy_bonus ).'" ><span class="rtw_rules">' . esc_html__( 'Next Buy ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) . '</span>'. esc_html__( 'Bonus', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) .'</a>';
			echo 	"</li>";

			echo 	"<li>";
			echo 		'<a href="'.esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_nth_order_active').'" class="'.esc_attr( $rtwwdpdl_nth_order_active ).'" ><span class="rtw_rules">' . esc_html__( 'Nth Order ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) . '</span>'. esc_html__( 'Discount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) .'</a>';
			echo 	"</li>";

			echo 	"<li>";
			echo 		'<a href="'.esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules&rtwwdpdl_sub=rtwwdpdl_bogo_least_active').'" class="'.esc_attr( $rtwwdpdl_bogo_least ).'" ><span class="rtw_rules">' . esc_html__( 'Least Amount ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) . '</span>'. esc_html__( 'Product Free', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) .'</a>';
			echo 	"</li>";
			?>
		</ul>
	</aside>

	<?php
	if( isset( $_GET[ 'rtwwdpdl_sub' ] ) )
	{
		if( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_prod_rules" )
		{
			include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_prod_rule.php' );
		}
		elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_cat_rules" )
		{
			include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_cate_rule.php' );
		}
		elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_cart_rules" )
		{
			include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_cart_rule.php' );
		}
		elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_bogo_rules" )
		{
			include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_bogo_rule.php' );
		}
		elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_variation_rules" )
		{
			include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_variation_rule.php' );
		}
		elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_tiered_rules" )
		{
			include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_tiered_rule.php' );
		}
		elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_payment_method" )
		{
			include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_payment_method.php' );
		}
		elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_shipping_method" )
		{
			include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_shipping_method.php' );
		}
		elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_attribute" )
		{
			include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_attribute.php' );
		}
		elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_prod_tags" )
		{
			include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_prod_tag.php' );
		}
		elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_next_buy_bonus" )
		{
			include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_next_buy_bonus.php' );
		}
		elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_nth_order_active" )
		{
			include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_nth_order.php' );
		}
		elseif( $_GET[ 'rtwwdpdl_sub' ] == "rtwwdpdl_bogo_least_active" )
		{
			include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_least_amt_pro.php' );
		}
	}
	else{
		include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_subtabs/rtwwdpdl_prod_rule.php' );
	}
	?>
</div>