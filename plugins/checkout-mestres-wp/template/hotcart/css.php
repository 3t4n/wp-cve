<?php
echo "<style type='text/css'>";
if(is_checkout()){
	if(get_option('cwmp_activate_checkout')=="S"){
		echo "
			:root {
			  --bg-color: ".get_option('cwmp_checkout_background').";
			  --bg-box-color: ".get_option('cwmp_checkout_box_background').";		  
			  --color-primary: ".get_option('cwmp_checkout_primary_color').";
			  --color-secundary: ".get_option('cwmp_checkout_secundary_color').";
			  --color-secundary-contrast: ".get_option('cwmp_checkout_secundary_color_contrast').";
			  --background-input: ".get_option('cwmp_checkout_input_background').";
			  --color-input: ".get_option('cwmp_checkout_input_color').";
			  --background-input-hover: ".get_option('cwmp_checkout_input_hover_background').";
			  --color-input-hover: ".get_option('cwmp_checkout_input_hover_color').";
			  --background-button: ".get_option('cwmp_checkout_button_background').";
			  --color-button: ".get_option('cwmp_checkout_button_color').";
			  --background-button-hover: ".get_option('cwmp_checkout_button_hover_background').";
			  --color-button-hover: ".get_option('cwmp_checkout_button_hover_color').";
			  --background-success: ".get_option('cwmp_checkout_success_background').";
			  --color-success: ".get_option('cwmp_checkout_success_color').";
			}
			".get_option('cwmp_checkout_css_personalizado')."
		";
		if(get_option('cwmp_pmwp_active')=="S"){
		echo "
			:root {
			--bump-bg-color: ".get_option('cwmp_box_bump_background').";
			--bump-color-primary: ".get_option('cwmp_box_bump_primary').";
			--bump-color-secundary: ".get_option('cwmp_box_bump_secundary').";
			--bump-color-button: ".get_option('cwmp_box_bump_button_color').";
			}
		";
		}
	}
}
if(!is_checkout()){
	if(get_option('cwmp_pmwp_active')=="S"){
		echo "
			:root {
				--price-align-items: ".get_option('parcelas_mwp_price_regular_align').";
				--price-align-position: ".get_option('parcelas_mwp_price_regular_position').";
				--price-regular-size: ".get_option('parcelas_mwp_price_regular_size')."px;
				--price-regular-color: ".get_option('parcelas_mwp_price_regular_color').";
				--price-regular-weight: ".get_option('parcelas_mwp_price_regular_weight').";
				--price-regular-decoration: ".get_option('parcelas_mwp_price_regular_decoration').";
				--price-sale-size: ".get_option('parcelas_mwp_price_sale_size')."px;
				--price-sale-color: ".get_option('parcelas_mwp_price_sale_color').";
				--price-sale-weight: ".get_option('parcelas_mwp_price_sale_weight').";
				--price-list-size: ".get_option('parcelas_mwp_list_size_text')."px;
				--price-list-color: ".get_option('parcelas_mwp_list_color_text').";
				--price-box-size: ".get_option('parcelas_mwp_box_size_text')."px;
				--price-box-color: ".get_option('parcelas_mwp_box_color_text').";
				--price-catalog-align: ".get_option('parcelas_mwp_price_catalog_align').";
				--price-product-align: ".get_option('parcelas_mwp_price_product_align').";
			}
			".get_option('cwmp_parcelas_css_personalizado')."
		";
	}
}


echo "</style>";


