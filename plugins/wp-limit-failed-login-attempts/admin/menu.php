<?php
if( ! class_exists( 'WPLFLA_menu' ) ) {
    class WPLFLA_menu
    {
        public function __construct()
        {
            add_action('admin_footer', array($this, 'my_style'));
        }

        public function menu()
        {
            ?>
            <ul class="WPLFLA_horizontal">
                <li><a <?php if ($_GET['page'] == 'WPLFLARANGEIP'){ ?>class="active"<?php } ?>
                       href="<?php echo admin_url(); ?>admin.php?page=WPLFLARANGEIP"><?php echo __('IP Blocking','codepressFailed_pro');?></a></li>
                <li><a <?php if ($_GET['page'] == 'WPLFLACOUNTRIES'){ ?>class="active"<?php } ?>
                       href="<?php echo admin_url(); ?>admin.php?page=WPLFLACOUNTRIES"><?php echo __('Countries Blocking <b style="color:red">(PRO)</b>','codepressFailed_pro');?></a></li>
                <li><a <?php if ($_GET['page'] == 'WPLFLALOG'){ ?>class="active"<?php } ?>
                       href="<?php echo admin_url(); ?>admin.php?page=WPLFLALOG"><?php echo __('Attempts log','codepressFailed_pro');?></a></li>
                <li><a <?php if ($_GET['page'] == 'logblockip'){ ?>class="active"<?php } ?>
                       href="<?php echo admin_url(); ?>admin.php?page=logblockip"><?php echo __('Lockout Log','codepressFailed_pro');?></a></li>
                <!--logapp-->
            </ul>
            <?php
        }

        public function my_style()
        {
            ?>
            <style>
                ul.WPLFLA_horizontal {
                    list-style-type: none;
                    margin: 50px 15px 0px 0px;
                    padding: 0;
                    overflow: hidden;
                    background-color: #333;
                }

                ul.WPLFLA_horizontal li {
                    float: left;
                    margin-bottom: 0px !important;
                }

                ul.WPLFLA_horizontal li a.active {
                    background-color: #4CAF50;
                }

                ul.WPLFLA_horizontal li a {
                    display: inline-block;
                    color: white;
                    text-align: center;
                    padding: 14px 16px;
                    text-decoration: none;
                }
            </style>

            <?php
        }
        public function get_the_user_ip() {
            if(!empty($_SERVER['HTTP_CLIENT_IP'])){
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }else{
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            return apply_filters( 'wpb_get_ip', $ip );
        }

        public function send_mail(){
            $options = get_option( 'WPLFLA_options' ,array());
            update_option( 'Block_hash_code', md5(rand(500, 15000)) );
            $hash = get_option( 'Block_hash_code');
            $to = $options['WPLFLA_email']?$options['WPLFLA_email']:get_option('admin_email');
            $email = $to;
            $subject = __('IMPORTANT - Security alert for your website ','codepressFailed_pro') .' '. get_site_url();
            $message = '<br><body style="background-color:#F8F9FA; padding:30px; font-size:15px"><center><h1 style="color:white; background-color:#C42032; padding:10px; width:100%">';
            $message .= __('Security Alert','codepressFailed_pro').'</h1></center><br><br>';
            $message .= __('Dear Admin,','codepressFailed_pro').'<br><br>';
            $message .=  __('Someone tried to access to your site dashboard using the following info:','codepressFailed_pro').'<br><br>';
            $message .= '- '.__('<b>Date/Time:</b> ','codepressFailed_pro').date("Y-m-d H:i:s",time()).'<br>';
            $message .= '- '.__('<b>Country:</b> ','codepressFailed_pro').'<img width="18px" src="'.esc_url(WPLFLA_PLUGIN_URL.'/assets/images/flags/'.strtolower($the_country_code).'.png').'" >&nbsp;'.$the_country.'<br>';
            $message .= '- '.__('<b>IP Address:</b> ','codepressFailed_pro').'<a href="https://db-ip.com/'.$this->get_the_user_ip().'" target="_blank">'.$this->get_the_user_ip().'</a><br>';
            $message .= '- '.__('<b>Device Name:</b> ','codepressFailed_pro').$this->detectDevice().'<br><br>';
            $message .= '- '.__('<b>Username:</b> ','codepressFailed_pro').$username.'<br>';
            $message .= '- '.__('<b style="color:red">Password:</b> ','codepressFailed_pro').'<a target="_blank"  href="https://www.wp-buy.com/product/wp-limit-failed-login-attempts-pro/">Upgrade to PRO</a>'.'<br><br>';
            $message .= __('If this was you, ','codepressFailed_pro').'<a href="'.esc_url( wp_login_url() ).'?Block_hash_code='.$hash.'"><b>'.__('Please click here to unlock', 'codepressFailed_pro').'</b></a><br><br>';
            $message .= '<p>'.__('This email was sent from your website by the', 'codepressFailed_pro').' (<a href="https://wordpress.org/plugins/wp-limit-failed-login-attempts/#description" target="_blank">'.__('WP limit failed login attempts', 'codepressFailed_pro').'</a>)'.__('  plugin','codepressFailed_pro').'</p>';
            $message .= __('</body>','codepressFailed_pro');

            $header = 'From: '.get_option('blogname').' <'.$to.'>'.PHP_EOL;
            $header .= 'Reply-To: '.$email.PHP_EOL;
            $header .= 'Content-Type: text/html; charset=UTF-8';

            wp_mail($to, $subject, $message, $header);

        }
        public function send_mail_block_range_ip($from='',$to=''){
            $options = get_option( 'WPLFLA_options' ,array());
            update_option( 'Block_hash_code', md5(rand(500, 15000)) );
            $hash = get_option( 'Block_hash_code');
            $to = $options['WPLFLA_email']?$options['WPLFLA_email']:get_option('admin_email');
            $email = $to;
            $subject = __('IMPORTANT - Security alert for your website ','codepressFailed_pro') .' '. get_site_url();
            $message = '<br><body style="background-color:#F8F9FA; padding:30px; font-size:15px"><center><h1 style="color:white; background-color:#C42032; padding:10px; width:100%">';
            $message .= __('Security Alert','codepressFailed_pro').'</h1></center><br><br>';
            $message .= __('Dear Admin,','codepressFailed_pro').'<br><br>';
            $message .=  __('Someone tried to access to your site dashboard using the following info:','codepressFailed_pro').'<br><br>';
            $message .= '- '.__('<b>Date/Time:</b> ','codepressFailed_pro').date("Y-m-d H:i:s",time()).'<br>';
            $message .= '- '.__('<b>IP Address:</b> ','codepressFailed_pro').'<a href="https://db-ip.com/'.$this->get_the_user_ip().'" target="_blank">'.$this->get_the_user_ip().'</a><br>';
            $message .= __('If this was you, ','codepressFailed_pro').'<a href="'.esc_url( wp_login_url() ).'?Block_hash_code='.$hash.'"><b>'.__('Please click here to unlock', 'codepressFailed_pro').'</b></a><br><br>';
            $message .= '<p>'.__('This email was sent from your website by the', 'codepressFailed_pro').' (<a href="https://wordpress.org/plugins/wp-limit-failed-login-attempts/#description" target="_blank">'.__('WP limit failed login attempts', 'codepressFailed_pro').'</a>)'.__('  plugin','codepressFailed_pro').'</p>';
            $message .= __('</body>','codepressFailed_pro');

            $header = 'From: '.get_option('blogname').' <'.$to.'>'.PHP_EOL;
            $header .= 'Reply-To: '.$email.PHP_EOL;
            $header .= 'Content-Type: text/html; charset=UTF-8';

            wp_mail($to, $subject, $message, $header);

        }

    }
}