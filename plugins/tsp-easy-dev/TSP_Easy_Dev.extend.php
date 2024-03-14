<?php				
    if ( !class_exists( 'TSP_Easy_Dev_Options_Easy_Dev' ) )
    {
        /**
         * TSP_Easy_Dev_Options_Easy_Dev - Extends the TSP_Easy_Dev_Options Class
         * @package TSP_Easy_Dev
         * @author sharrondenice, letaprodoit
         * @author Sharron Denice, Let A Pro Do IT!
         * @copyright 2021 Let A Pro Do IT!
         * @license APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
         */

        class TSP_Easy_Dev_Options_Easy_Dev extends TSP_Easy_Dev_Options
        {
            /**
             * Implements the settings_page to display settings specific to this plugin
             *
             * @since 1.1.0
             *
             * @param void
             *
             * @return void - output to screen
             */
            function display_plugin_options_page()
            {
                global $easy_dev;

                // Display settings to screen
                $smarty = new TSP_Easy_Dev_Smarty( $this->get_value('smarty_template_dirs'),
                    $this->get_value('smarty_cache_dir'),
                    $this->get_value('smarty_compiled_dir'), true );

                $smarty->assign( 'plugin_title',			TSP_EASY_DEV_TITLE);
                $smarty->assign( 'plugin_links',			implode(' | ', $easy_dev->get_meta_links()));

                $smarty->assign( 'plugin_name',				$this->get_value('name'));
                $smarty->assign( 'nonce_name',				wp_nonce_field( $this->get_value('name'), $this->get_value('name').'_nonce_name' ));

                $smarty->display( 'easy-dev-child-page.tpl');
            }

        }//end TSP_Easy_Dev_Options_Easy_Dev
    }//end if