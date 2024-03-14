;(function () {
	/**
	 * Run function when customizer is ready.
	 */
	wp.customize.bind('ready', function () {

        var condition, 
        archives_condition,
        singular_condition,
        woocommerce_condition,
        singular_page_condition,
        singular_post_condition,
        singular_product_condition;


        wp.customize.control('wpbottommenu_condition', function (control) {

            const toggleControl = (value) => {
                condition = value;
                wp.customize.control('wpbottommenu_archives_condition').toggle(false);
                wp.customize.control('wpbottommenu_woocommerce_condition').toggle(false);
                wp.customize.control('wpbottommenu_singular_condition').toggle(false);
                //wp.customize.control('wpbottommenu_singular_page_condition').toggle(false);

                switch (value){
                    case 'archives':
                        wp.customize.control('wpbottommenu_archives_condition').toggle(true);
                        wp.customize.control('wpbottommenu_singular_page_condition').toggle(false);
                        wp.customize.control('wpbottommenu_singular_post_condition').toggle(false);
                        //wp.customize.control('wpbottommenu_singular_product_condition').toggle(false);
                        break;
                    case 'woocommerce':
                        wp.customize.control('wpbottommenu_woocommerce_condition').toggle(true);
                        wp.customize.control('wpbottommenu_singular_page_condition').toggle(false);
                        wp.customize.control('wpbottommenu_singular_post_condition').toggle(false);
                        break;
                    case 'singular':
                        wp.customize.control('wpbottommenu_singular_condition').toggle(true);
                        wp.customize.control('wpbottommenu_singular_product_condition').toggle(false);
                        break;
                }
        

            };
        
            toggleControl(control.setting.get());
            control.setting.bind(toggleControl);
        });
  
        wp.customize.control('wpbottommenu_singular_condition', function (control) {
            
            const toggleControl = (value) => {
                singular_condition = value;
                wp.customize.control('wpbottommenu_singular_page_condition').toggle(false);
                wp.customize.control('wpbottommenu_singular_post_condition').toggle(false);

                switch (value){
                    case 'pages':
                        wp.customize.control('wpbottommenu_singular_page_condition').toggle(true);
                        break;
                    case 'post':
                        wp.customize.control('wpbottommenu_singular_post_condition').toggle(true);
                        break;
                }
        

            };
        
            toggleControl(control.setting.get());
            control.setting.bind(toggleControl);
        });

        wp.customize.control('wpbottommenu_woocommerce_condition', function (control) {
            
            const toggleControl = (value) => {
                woocommerce_condition = value;
                wp.customize.control('wpbottommenu_singular_product_condition').toggle(false);
                wp.customize.control('wpbottommenu_singular_page_condition').toggle(false);
                wp.customize.control('wpbottommenu_singular_post_condition').toggle(false);

                switch (value){
                    case 'product':
                        wp.customize.control('wpbottommenu_singular_product_condition').toggle(true);
                        break;
                }
        
            };
        
            toggleControl(control.setting.get());
            control.setting.bind(toggleControl);
        });

	});
})();