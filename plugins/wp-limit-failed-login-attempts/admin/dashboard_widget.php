<?php
if( ! class_exists( 'dashboard_widget_PRO' ) ) {
class dashboard_widget_PRO{

    public function __construct(){
        add_action('wp_dashboard_setup', array($this,'dashboard_widgets'));
    }

    public function dashboard_widgets() {
        global $wp_meta_boxes;
        wp_add_dashboard_widget('Top_IPs_Blocked', __('Top 10 Blocked IPs','codepressFailed_pro'), array($this,'Top_IPs_Blocked'));
        wp_add_dashboard_widget('Top_Failed_Logins', __('Top 10 Failed Logins','codepressFailed_pro'), array($this,'Top_Failed_Logins'));
        wp_add_dashboard_widget('Top_Countries_Blocked', __('Top 10 Blocked Countries','codepressFailed_pro'), array($this,'Top_Countries_Blocked'));
        wp_add_dashboard_widget('Recently_Blocked_Attacks', __('Recently Blocked Attacks','codepressFailed_pro'), array($this,'Recently_Blocked_Attacks'));
    }


    public function Top_IPs_Blocked() {
        global $wpdb;
        wp_enqueue_style( 'failed_admin-pro-css' , WPLFLA_PLUGIN_URL.'/assets/css/admin-css.css?re=1.2');
        $login_failed_option = $wpdb->get_results("SELECT ip , country, country_code, count(*) as count FROM ".$wpdb->prefix."WPLFLA_log_block_ip GROUP BY ip ORDER BY 4 DESC limit 10;");

        ?>
        <table class="failed_login_rep" style="max-width: 100% !important;width: 100% !important;">
            <?php
            if(!empty($login_failed_option)){
                ?>
                <tr>
                    <th><?php _e( 'IP', 'codepressFailed_pro' );?></th>
                    <th><?php _e( 'Country', 'codepressFailed_pro' );?></th>
                    <th><?php _e( 'Count', 'codepressFailed_pro' );?></th>
                </tr>
                <?php
                foreach($login_failed_option as $log){
					
					if(isset($log->country_code) && $log->country_code!='' && strlen($log->country_code) <=3)
					{
                      $country_code = isset($log->country_code) ? esc_html($log->country_code) : 'defalut';
                    ?>
                    <tr>
                        <td><a href="https://db-ip.com/<?php esc_html_e($log->ip);?>" target="_blank"><?php esc_html_e($log->ip);?></a></td>
                        <td>
						<img width="18px" src="<?php echo esc_url(WPLFLA_PLUGIN_URL.'/assets/images/flags/'.strtolower($country_code));?>.png" >&nbsp;<?php echo esc_html($log->country);?></td>
                        <td><?php esc_html_e($log->count);?></td>
                    </tr>
                    <?php
					}
                }
            }else{
                ?>
                <tr>
                    <td class="no-data-found"><?php _e( 'No Data Found', 'codepressFailed_pro' );?></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }

    public function Top_Failed_Logins() {
        global $wpdb;
        wp_enqueue_style( 'failed_admin-pro-css' , WPLFLA_PLUGIN_URL.'/assets/css/admin-css.css?re=1.2');

        $login_failed_option = $wpdb->get_results("SELECT ip , username, password, country, country_code, count(*) as count FROM ".$wpdb->prefix."WPLFLA_login_failed GROUP BY username ORDER BY 6 DESC limit 10;");
		
        ?>
        <table class="failed_login_rep" style="max-width: 100% !important;width: 100% !important;">
            <?php
            if(!empty($login_failed_option)){
                ?>
                <tr>
                    <th><?php _e( 'IP', 'codepressFailed_pro' );?></th>
                    <th><?php _e( 'Username/Password', 'codepressFailed_pro' );?></th>


                    <th><?php _e( 'Count', 'codepressFailed_pro' );?></th>
                </tr>
                <?php
                foreach($login_failed_option as $logs){
					
					
                    if(isset($logs->country_code) && $logs->country_code !='' && strlen($logs->country_code) <=3 && $logs->username !='')
					{
						$country_code = isset($logs->country_code) ? esc_html($logs->country_code) : 'defalut';
                       
                    ?>
                    <tr>
                       <td><a href="https://db-ip.com/<?php esc_html_e($logs->ip);?>" target="_blank"><?php esc_html_e($logs->ip);?></a><br /><small><img width="18px" title="<?php esc_html_e($logs->country);?>" src="<?php echo esc_url(WPLFLA_PLUGIN_URL.'/assets/images/flags/'.strtolower($country_code));?>.png" >&nbsp;<?php esc_html_e($logs->country);?></small></td>
                        <td><?php esc_html_e($logs->username.'/');?><a target="_blank"  href="https://www.wp-buy.com/product/wp-limit-failed-login-attempts-pro/"><?php esc_html_e('Upgrade to PRO');?></a> <br /><small><?php
                            $user_info = get_user_by( 'login', $logs->username);
                            if($user_info){
                                ?><span class="<?php if( user_can( $user_info, "administrator" )){echo 'color_red';};?>"><?php
                                echo implode(", ",$user_info->roles );
                                ?></span><?php
                            }else{
								echo 'N/A';
							}
                            ?></small></td>


                        <td><?php esc_html_e($logs->count);?></td>
                    </tr>
                    <?php
					}
                }
            }else{
                ?>
                <tr>
                    <td class="no-data-found"><?php _e( 'No Data Found', 'codepressFailed_pro' );?></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }
    public function Top_Countries_Blocked() {
        global $wpdb;
        wp_enqueue_style( 'failed_admin-pro-css' , WPLFLA_PLUGIN_URL.'/assets/css/admin-css.css?re=1.2');

		
			
        $login_failed_option = $wpdb->get_results("SELECT country,country_code, (SELECT count(1) from ".$wpdb->prefix."WPLFLA_log_block_ip as blockip WHERE blockip.country_code = log.country_code) as block_count,(SELECT count(1) from ".$wpdb->prefix."WPLFLA_login_failed as logip WHERE logip.country_code = log.country_code) as log_count FROM ".$wpdb->prefix."WPLFLA_login_failed as log GROUP BY country_code order by  block_count desc limit 10");

        ?>
        <table class="failed_login_rep" style="max-width: 100% !important;width: 100% !important;">
            <?php
            if(!empty($login_failed_option)){
                ?>
                <tr>

                    <th><?php _e( 'Country', 'codepressFailed_pro' );?></th>
                    <th><?php _e( 'Blocked', 'codepressFailed_pro' );?></th>
					<th><?php _e( 'Attempts', 'codepressFailed_pro' );?></th>
                </tr>
                <?php
                foreach($login_failed_option as $log){
                    if(isset($log->country_code) && $log->country_code!='' && strlen($log->country_code) <=3)
					{
                       $country_code = isset($log->country_code) ? esc_html($log->country_code) : 'defalut';
                    ?>
                    <tr>

                        <td><img width="18px" title="<?php esc_html_e($log->country);?>" src="<?php echo esc_url(WPLFLA_PLUGIN_URL.'/assets/images/flags/'.strtolower($country_code));?>.png" >&nbsp;<?php esc_html_e($log->country);?></td>
                        <td><?php esc_html_e($log->block_count);?></td>
						<td><?php esc_html_e($log->log_count);?></td>
                    </tr>
                    <?php
					}
                }
            }else{
                ?>
                <tr>
                    <td class="no-data-found"><?php _e( 'No Data Found', 'codepressFailed_pro' );?></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }

    public function Recently_Blocked_Attacks() {
        global $wpdb;
        wp_enqueue_style( 'failed_admin-pro-css' , WPLFLA_PLUGIN_URL.'/assets/css/admin-css.css?re=1.2');

        $login_failed_option = $wpdb->get_results("SELECT ip , country,username, password, country_code ,date FROM ".$wpdb->prefix."WPLFLA_log_block_ip  ORDER BY id DESC limit 10 ");

        ?>
        <table class="failed_login_rep" style="max-width: 100% !important;width: 100% !important;">
            <?php
            if(!empty($login_failed_option)){
                ?>
                <tr>
                    <th><?php _e( 'IP', 'codepressFailed_pro' );?></th>
                    <th><?php _e( 'Username/Password', 'codepressFailed_pro' );?></th>


                    <th><?php _e( 'Date', 'codepressFailed_pro' );?></th>
                </tr>
                <?php
                foreach($login_failed_option as $log){
                    if(isset($log->country_code) && $log->country_code!='' && strlen($log->country_code) <=3)
					{
                       
                     $country_code = isset($log->country_code) ? esc_html($log->country_code) : 'defalut';
                    ?>
                    <tr>
                        <td><a href="https://db-ip.com/<?php esc_html_e($log->ip);?>" target="_blank"><?php esc_html_e($log->ip);?></a><br /><small><img width="18px" title="<?php esc_html_e($log->country);?>" src="<?php echo esc_url(WPLFLA_PLUGIN_URL.'/assets/images/flags/'.strtolower($country_code));?>.png" >&nbsp;<?php esc_html_e($log->country);?></small></td>
                        <td><?php esc_html_e($log->username);?>/<a target="_blank"  href="https://www.wp-buy.com/product/wp-limit-failed-login-attempts-pro/"><?php esc_html_e('Upgrade to PRO');?></a><br /><small><?php
                            $user_info = get_user_by( 'login', $log->username);
                            if($user_info){
                                ?><span class="<?php if( user_can( $user_info, "administrator" )){echo 'color_red';};?>"><?php
                                echo implode(", ",$user_info->roles );
                                ?></span><?php
                            }else{
								echo 'N/A';
							}
                            ?></small></td>


                        <td><?php esc_html_e($log->date);?></td>
                    </tr>
                    <?php
					}
                }
            }else{
                ?>
                <tr>
                    <td class="no-data-found"><?php _e( 'No Data Found', 'codepressFailed_pro' );?></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }
}
new dashboard_widget_PRO();
}