<?php
/**
 * Dashboard Settings
 */

// Call the function to generate the obfuscated class name
$pluginNameClass = pluginNameClass();

if (class_exists($pluginNameClass)) {

?>

    <div class="element-page">
        <div class="wrapper-box">
            <div class="sidebar">
                <div class="logo">
                    <img src="<?php echo plugins_url('assets/admin/logo.png', dirname(__FILE__)); ?>" alt="Plugin Logo">
                </div>
                <ul class="nav nav-tabs tab-btn-style-one" role="tablist">
                  
					<li class="nav-item">
                        <a class="nav-link active" id="tab-one-area" data-bs-toggle="tab" href="#tab-one" role="tab" aria-controls="tab-one" aria-selected="true">
                            <h4><span class="dashicons dashicons-screenoptions"></span> <?php esc_html_e('Dashboard', 'wpsection'); ?></h4>
                        </a>
                    </li>
            
                  
                    <li class="nav-item">
                        <a class="nav-link" id="tab-three-four" data-bs-toggle="tab" href="#tab-four" role="tab" aria-controls="tab-four" aria-selected="false">
                            <h4><span class="dashicons dashicons-welcome-learn-more"></span><?php esc_html_e('Free Vs Pro', 'wpsection'); ?></h4>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-three-five" data-bs-toggle="tab" href="#tab-five" role="tab" aria-controls="tab-five" aria-selected="false">
                            <h4><span class="dashicons dashicons-database-export"></span><?php esc_html_e('Donate', 'wpsection'); ?></h4>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="content-box">
                <div class="tab-content wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1200ms">
                    
					
					<!-- Area One Dashboard -->
                    <div class="tab-pane fadeInUp animated active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                       <?php  include_once(WPSECTION_PLUGIN_DIR . 'plugin/adminboard/dash.php'); ?>
                    </div>
                    <!-- Area Two for Template Library -->
                    <div class="tab-pane fadeInUp animated" id="tab-two" role="tabpanel" aria-labelledby="tab-two">
                        <?php  include_once(WPSECTION_PLUGIN_DIR . 'plugin/adminboard/widget.php'); ?>
                    </div>
                    <!-- Area Three for Template Settings -->
                    <div class="tab-pane fadeInUp animated " id="tab-three" role="tabpanel" aria-labelledby="tab-three">
                           <?php  include_once(WPSECTION_PLUGIN_DIR . 'plugin/adminboard/settings.php'); ?>
                    </div>                    <!-- Area Four Free Vs Pro -->
                    <div class="tab-pane fadeInUp animated" id="tab-four" role="tabpanel" aria-labelledby="tab-four">
                         <?php  include_once(WPSECTION_PLUGIN_DIR . 'plugin/adminboard/compare.php'); ?>
						 
                    </div>
					 <!-- Area Four for Donate -->
					 <div class="tab-pane fadeInUp animated" id="tab-five" role="tabpanel" aria-labelledby="tab-five">
                        <?php  include_once(WPSECTION_PLUGIN_DIR . 'plugin/adminboard/donate.php'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
   

<?php
}