<?php
if(class_exists('CountdownTimer')){ return; }
class CountdownTimer{	
	
	public function __construct() {	
		global $wpdb, $post;												
		add_action('wp_enqueue_scripts', array($this,'add_media_upload_scripts'));						

		# Register shortcodes	
		add_filter( 'the_content', 'do_shortcode');	
		add_shortcode( 'countdown', array($this, 'ct_countdown_timer') );
        add_action('woocommerce_before_add_to_cart_button', array($this, 'beforeWcAddCnt'), 50);
		# Settings Page
		add_action( 'admin_menu', array($this, 'timer_settings') );
		add_action( 'admin_init', array($this,'register_ct_setting') );
    }
	
	/**
	 * install
	 * Do the things
	 */
	static function install() {
		//check if option is already present		
		$option_name = 'ct_settings';
		if(!get_option($option_name)) {
			//not present, so add
			$op = array(
				'ct-dtext-by-tomorrow' => '{clock-icon} Get it {strong}Tomorrow, {delivery-day} {/strong}, Order in next {timer}',
				'ct-dtext-before-cut-off' => '{clock-icon} Want it {strong}{delivery-day} {/strong}, Order in next {timer}',
				'ct-dtext-beyond-cut-off' => '{clock-icon} Get it {strong}{delivery-day} {/strong}, Order in next {timer}',
				'ct-delivery-time' => '20',
				'ct-cut-off-time' => '13',
				'ct-days' => '7, 1, 2, 3, 4, 5, 6',
				'ct-show-timer-before-cartbtn' => 1
			);
			add_option($option_name, $op);
		}
	}
	// Uninstall default settings
	static function uninstall() {
		$option_name = 'ct_settings';
		//check if option is already present
		if(get_option($option_name)) {
			delete_option( $option_name );
		}
	}
	// Add Timer before Add to Cart Button	
    public function beforeWcAddCnt(){
		$opts = get_option('ct_settings'); 	
		if($opts['ct-show-timer-before-cartbtn'] == 1){ // If enable show it before cart button
        	echo  do_shortcode("[countdown cdn_class='cdn_before_adt_cart' cdn_timer_id='ad_crt_tmr_cdn' ]");
		}
    }
	
	public function add_media_upload_scripts() {    
		// If Jquery not included already add it now		
		if( !wp_script_is('jquery', 'enqueued') ){
			wp_enqueue_script('jquery');
		}
		wp_register_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css' );
		wp_enqueue_style( 'font-awesome' );
		// Load countdown files
		wp_register_style( 'countdown', CT_PLUGIN_ASSETS_URL.'/css/countdown.css' );
		wp_enqueue_style( 'countdown' );				
		wp_register_script('jqs_countdown', CT_PLUGIN_ASSETS_URL."/js/jquery.countdownTimer.min.js", '', '2.1.0');
		wp_enqueue_script('jqs_countdown');        
		wp_localize_script('ctscript', 'ctvars', array( 'adminurl' => get_admin_url() ) );
	}
	
	# Settings Page
	public function timer_settings(){		
		add_submenu_page( 'options-general.php', 'Countdown Timer', 'Countdown Timer', 'manage_options', 'countdown-timer', array( $this, 'countdown_timer_callback') );
	}
	
	public function register_ct_setting() {
		register_setting( 'ct_options', 'ct_settings', array($this,'ct_settings_options') ); 
	} 
		
	public function ct_settings_options($options){	
		$options['ct-dtext-by-tomorrow'] = sanitize_text_field( (isset($_POST['ct-dtext-by-tomorrow'])) ? $_POST['ct-dtext-by-tomorrow'] : '' );
		$options['ct-dtext-before-cut-off'] = sanitize_text_field( (isset($_POST['ct-dtext-before-cut-off'])) ? $_POST['ct-dtext-before-cut-off'] : '' );
		$options['ct-dtext-beyond-cut-off'] = sanitize_text_field( (isset($_POST['ct-dtext-beyond-cut-off'])) ? $_POST['ct-dtext-beyond-cut-off'] : '' );
		$options['ct-delivery-time'] = sanitize_text_field( (isset($_POST['ct-delivery-time'])) ? $_POST['ct-delivery-time'] : '' );
		$options['ct-cut-off-time'] = sanitize_text_field( (isset($_POST['ct-cut-off-time'])) ? $_POST['ct-cut-off-time'] : '' );			
		$options['ct-days'] = sanitize_text_field( (isset($_POST['ct-days'])) ? implode(", ", $_POST['ct-days']) : '' ); 
		$options['ct-show-timer-before-cartbtn'] = sanitize_text_field( (isset($_POST['ct-show-timer-before-cartbtn'])) ? $_POST['ct-show-timer-before-cartbtn'] : '' ); 						
		return $options;		
	}
	
	// Admin callback settings
	public function countdown_timer_callback(){
		$opts = get_option('ct_settings'); 	
		$days = explode(",", $opts['ct-days']);			
		?>
		<div class="wrap">
		<h2><?php _e('Countdown Timer Settings', 'countdown-timer'); ?></h2>        		
        <form action="<?php echo admin_url(); ?>options.php" method="post" >
        	<input type="hidden" value="testing" name="wlecome" />
			<?php settings_fields('ct_options');?>
            <table style="margin-left:30px;" class="form-table">
				<tbody>
                <tr>
                	<th scope="row"><label for="">Delivery Text By Tomorrow : </label></th>
					<td>
                    	<textarea type="text" name="ct-dtext-by-tomorrow" rows="3" cols="50"><?php echo esc_html($opts['ct-dtext-by-tomorrow']); ?></textarea>
                    	<br /><span class="description"><strong>Avaiable Tags:</strong> {clock-icon}, {strong}, {/strong}, {delivery-time}, {delivery-day}, {timer}</span>    
                    </td>
                </tr>
                <tr>
                	<th scope="row"><label for="">Delivery Text Before Cut Off Time : </label></th>
					<td>
                    	<textarea type="text" name="ct-dtext-before-cut-off" rows="3" cols="50"><?php echo esc_html($opts['ct-dtext-before-cut-off']); ?></textarea>
                    	<br /><span class="description"><strong>Avaiable Tags:</strong> {clock-icon}, {strong}, {/strong}, {delivery-time}, {delivery-day}, {timer}</span> 
                    </td>
                </tr>
                <tr>
                	<th scope="row"><label for="">Delivery Text Beyond Cut Off Time: </label></th>
					<td>
                    	<textarea type="text" name="ct-dtext-beyond-cut-off" rows="3" cols="50"><?php echo esc_html($opts['ct-dtext-beyond-cut-off']); ?></textarea>
                    	<br /><span class="description"><strong>Avaiable Tags:</strong> {clock-icon}, {strong}, {/strong}, {delivery-time}, {delivery-day}, {timer}</span> 
                    </td>
                </tr>
                <tr>
                	<th scope="row"><label for="">Delivery Time : </label></th>
					<td><select name="ct-delivery-time">
						<option value="">-- Select Time --</option>
                        <option value="1" <?php echo (esc_html($opts['ct-delivery-time']) == 1)? 'selected="selected"':''; ?>>1 AM</option>
                        <option value="2" <?php echo (esc_html($opts['ct-delivery-time']) == 2)? 'selected="selected"':''; ?>>2 AM</option>
                        <option value="3" <?php echo (esc_html($opts['ct-delivery-time']) == 3)? 'selected="selected"':''; ?>>3 AM</option>
                        <option value="4" <?php echo (esc_html($opts['ct-delivery-time']) == 4)? 'selected="selected"':''; ?>>4 AM</option>
                        <option value="5" <?php echo (esc_html($opts['ct-delivery-time']) == 5)? 'selected="selected"':''; ?>>5 AM</option>
                        <option value="6" <?php echo (esc_html($opts['ct-delivery-time']) == 6)? 'selected="selected"':''; ?>>6 AM</option>
                        <option value="7" <?php echo (esc_html($opts['ct-delivery-time']) == 7)? 'selected="selected"':''; ?>>7 AM</option>
                        <option value="8" <?php echo (esc_html($opts['ct-delivery-time']) == 8)? 'selected="selected"':''; ?>>8 AM</option>
                        <option value="9" <?php echo (esc_html($opts['ct-delivery-time']) == 9)? 'selected="selected"':''; ?>>9 AM</option>
                        <option value="10" <?php echo (esc_html($opts['ct-delivery-time']) == 10)? 'selected="selected"':''; ?>>10 AM</option>
                        <option value="11" <?php echo (esc_html($opts['ct-delivery-time']) == 11)? 'selected="selected"':''; ?>>11 AM</option>
                        <option value="12" <?php echo (esc_html($opts['ct-delivery-time']) == 12)? 'selected="selected"':''; ?>>12 AM</option>
                        <option value="13" <?php echo (esc_html($opts['ct-delivery-time']) == 13)? 'selected="selected"':''; ?>>1 PM</option>
                        <option value="14" <?php echo (esc_html($opts['ct-delivery-time']) == 14)? 'selected="selected"':''; ?>>2 PM</option>
                        <option value="15" <?php echo (esc_html($opts['ct-delivery-time']) == 15)? 'selected="selected"':''; ?>>3 PM</option>
                        <option value="16" <?php echo (esc_html($opts['ct-delivery-time']) == 16)? 'selected="selected"':''; ?>>4 PM</option>
                        <option value="17" <?php echo (esc_html($opts['ct-delivery-time']) == 17)? 'selected="selected"':''; ?>>5 PM</option>
                        <option value="18" <?php echo (esc_html($opts['ct-delivery-time']) == 18)? 'selected="selected"':''; ?>>6 PM</option>
                        <option value="19" <?php echo (esc_html($opts['ct-delivery-time']) == 19)? 'selected="selected"':''; ?>>7 PM</option>
                        <option value="20" <?php echo (esc_html($opts['ct-delivery-time']) == 20)? 'selected="selected"':''; ?>>8 PM</option>
                        <option value="21" <?php echo (esc_html($opts['ct-delivery-time']) == 21)? 'selected="selected"':''; ?>>9 PM</option>
                        <option value="22" <?php echo (esc_html($opts['ct-delivery-time']) == 22)? 'selected="selected"':''; ?>>10 PM</option>
                        <option value="23" <?php echo (esc_html($opts['ct-delivery-time']) == 23)? 'selected="selected"':''; ?>>11 PM</option>
                        <option value="24" <?php echo (esc_html($opts['ct-delivery-time']) == 24)? 'selected="selected"':''; ?>>12 PM</option>					
                    </select></td>
                </tr>
                <tr>
                	<th scope="row"><label for="">Cut Off Time : </label></th>
					<td><select name="ct-cut-off-time">
						<option value="">-- Select Time --</option>
                        <option value="1" <?php echo (esc_html($opts['ct-cut-off-time']) == 1)? 'selected="selected"':''; ?>>1 AM</option>
                        <option value="2" <?php echo (esc_html($opts['ct-cut-off-time']) == 2)? 'selected="selected"':''; ?>>2 AM</option>
                        <option value="3" <?php echo (esc_html($opts['ct-cut-off-time']) == 3)? 'selected="selected"':''; ?>>3 AM</option>
                        <option value="4" <?php echo (esc_html($opts['ct-cut-off-time']) == 4)? 'selected="selected"':''; ?>>4 AM</option>
                        <option value="5" <?php echo (esc_html($opts['ct-cut-off-time']) == 5)? 'selected="selected"':''; ?>>5 AM</option>
                        <option value="6" <?php echo (esc_html($opts['ct-cut-off-time']) == 6)? 'selected="selected"':''; ?>>6 AM</option>
                        <option value="7" <?php echo (esc_html($opts['ct-cut-off-time']) == 7)? 'selected="selected"':''; ?>>7 AM</option>
                        <option value="8" <?php echo (esc_html($opts['ct-cut-off-time']) == 8)? 'selected="selected"':''; ?>>8 AM</option>
                        <option value="9" <?php echo (esc_html($opts['ct-cut-off-time']) == 9)? 'selected="selected"':''; ?>>9 AM</option>
                        <option value="10" <?php echo (esc_html($opts['ct-cut-off-time']) == 10)? 'selected="selected"':''; ?>>10 AM</option>
                        <option value="11" <?php echo (esc_html($opts['ct-cut-off-time']) == 11)? 'selected="selected"':''; ?>>11 AM</option>
                        <option value="12" <?php echo (esc_html($opts['ct-cut-off-time']) == 12)? 'selected="selected"':''; ?>>12 AM</option>
                        <option value="13" <?php echo (esc_html($opts['ct-cut-off-time']) == 13)? 'selected="selected"':''; ?>>1 PM</option>
                        <option value="14" <?php echo (esc_html($opts['ct-cut-off-time']) == 14)? 'selected="selected"':''; ?>>2 PM</option>
                        <option value="15" <?php echo (esc_html($opts['ct-cut-off-time']) == 15)? 'selected="selected"':''; ?>>3 PM</option>
                        <option value="16" <?php echo (esc_html($opts['ct-cut-off-time']) == 16)? 'selected="selected"':''; ?>>4 PM</option>
                        <option value="17" <?php echo (esc_html($opts['ct-cut-off-time']) == 17)? 'selected="selected"':''; ?>>5 PM</option>
                        <option value="18" <?php echo (esc_html($opts['ct-cut-off-time']) == 18)? 'selected="selected"':''; ?>>6 PM</option>
                        <option value="19" <?php echo (esc_html($opts['ct-cut-off-time']) == 19)? 'selected="selected"':''; ?>>7 PM</option>
                        <option value="20" <?php echo (esc_html($opts['ct-cut-off-time']) == 20)? 'selected="selected"':''; ?>>8 PM</option>
                        <option value="21" <?php echo (esc_html($opts['ct-cut-off-time']) == 21)? 'selected="selected"':''; ?>>9 PM</option>
                        <option value="22" <?php echo (esc_html($opts['ct-cut-off-time']) == 22)? 'selected="selected"':''; ?>>10 PM</option>
                        <option value="23" <?php echo (esc_html($opts['ct-cut-off-time']) == 23)? 'selected="selected"':''; ?>>11 PM</option>
                        <option value="24" <?php echo (esc_html($opts['ct-cut-off-time']) == 24)? 'selected="selected"':''; ?>>12 PM</option>					
                    </select></td>
                </tr>
                <tr>
					<th scope="row"><label for="">Nextday Delivery available days : </label></th>
					<td>
						<input type="checkbox" value="7" name="ct-days[]" <?php echo in_array(7, $days)? 'checked="checked"':''; ?>>
                        <label for="sunday">Sunday</label><br />
                        <input type="checkbox" value="1" name="ct-days[]" <?php echo in_array(1, $days)? 'checked="checked"':''; ?>>
                        <label for="monday">Monday</label><br />
                        <input type="checkbox" value="2" name="ct-days[]" <?php echo in_array(2, $days)? 'checked="checked"':''; ?>>
                        <label for="tuesday">Tuesday</label><br />
                        <input type="checkbox" value="3" name="ct-days[]" <?php echo in_array(3, $days)? 'checked="checked"':''; ?>>
                        <label for="wednesday">Wednesday</label><br />
                        <input type="checkbox" value="4" name="ct-days[]" <?php echo in_array(4, $days)? 'checked="checked"':''; ?>>
                        <label for="thursday">Thursday</label><br />
                        <input type="checkbox" value="5" name="ct-days[]" <?php echo in_array(5, $days)? 'checked="checked"':''; ?>>
                        <label for="friday">Friday</label><br />
                        <input type="checkbox" value="6" name="ct-days[]" <?php echo in_array(6, $days)? 'checked="checked"':''; ?>>
                        <label for="saturday">Saturday</label><br />
					</td>				
				</tr> 
                <tr>
                	<th scope="row"><label for="">Show Timer before Add to Cart Button: </label></th>
					<td><input type="checkbox" value="1" name="ct-show-timer-before-cartbtn" <?php checked( $opts['ct-show-timer-before-cartbtn'], 1 ); ?>></td>
                </tr>  
                <tr>
                	<td colspan="2">
                    	<p class="submit"><input type="submit" class="button-primary" name="ct_admin_options" value="<?php esc_attr_e('Save Changes') ?>" /></p>
                    </td>
                </tr>         				               				
				</tbody>
            </table>
        </form>
		</div>
        <?php	
	}

    private function getHtml($file = '', $data = array()){
        $htmlData = '';

        if(!empty($file)){
            if(file_exists(CT_PLUGIN_DIR.DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.$file.'.php')){
                ob_start();
                $data = $data;
                include CT_PLUGIN_DIR.DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.$file.'.php';
                //$htmlData = ob_get_contents();
                $htmlData = ob_get_clean();
                //ob_end_clean();
            }
        }

        return $htmlData;
    }

	// For Shown the timer		
	public function ct_countdown_timer($atts){		
		$str = '';
		$opts = get_option('ct_settings');             
		$pdata = extract(shortcode_atts(array( 'cdn_timer_id' => '', 'cdn_class' => '' ), $atts));				
        $str .= $this->getHtml('timer', array('cdn_class'=>@$cdn_class, 'cdn_timer_id'=>@$cdn_timer_id));        
		return $str;           
	}
	
	// For next day calculation
	public function nextdayCalculation($id, $availdays, $opts){
		
		$days = array( '7' => 'Sunday', '1'=> 'Monday', '2'=> 'Tuesday', '3'=> 'Wednesday', '4'=> 'Thursday', '5'=> 'Friday', '6'=> 'Saturday' );						
		$day = date('N');
		$hour = date('H');
		$cutoff_time = $opts['ct-cut-off-time'];
		$count = count($availdays);
		$cur_place = array_search($day,$availdays);
		$startdate = date("Y-m-d");	
		if( $opts['ct-delivery-time'] < 12 ){ 
			$delivery_time = $opts['ct-delivery-time']."AM";
		}else{ 
			$delivery_time = ($opts['ct-delivery-time'] - 12)."PM";	
		}
		if( $hour < $cutoff_time ){  //before cut off limit				
			
			if( $count > ($cur_place+1) ){  						
				$end = intval($availdays[intval($cur_place+1)]);				
				$enddate = date("Y-m-d", strtotime("next ".$days[$end]));
				$days_gap = $this->daysCount( $startdate, $enddate );
				
			}else if( $count == ($cur_place+1) ){  				
				$end = intval($availdays[0]);				
				$enddate = date("Y-m-d", strtotime("next ".$days[$end]));
				$days_gap = $this->daysCount( $startdate, $enddate );
			}
			if( $days_gap > 1 ){
				$delivery_day = date('l, M d', strtotime('+'.($days_gap).' days'));
				$text = str_replace( array('{clock-icon}', '{strong}','{/strong}','{delivery-time}', '{delivery-day}', '{timer}'),array('<i class="fa fa-clock-o"></i>', '<strong>', '</strong>', $delivery_time, $delivery_day, '<span id="'.$id.'"></span>'),$opts['ct-dtext-before-cut-off']);
			}else{
				$delivery_day = date('M d', strtotime('+'.($days_gap).' days'));				
				$text = str_replace( array('{clock-icon}', '{strong}','{/strong}','{delivery-time}', '{delivery-day}', '{timer}'),array('<i class="fa fa-clock-o"></i>', '<strong>', '</strong>', $delivery_time, $delivery_day, '<span id="'.$id.'"></span>'),$opts['ct-dtext-by-tomorrow']);
			}
		}else{  // Beyond the cut off limit			
			
			if( $count > ($cur_place+1) ){ 
				// If the time crossed our desired time
				if( array_key_exists( ($cur_place+2), $availdays ) ){ // If current place has more than two values    
					$end = intval($availdays[intval($cur_place+2)]);					
					$enddate = date("Y-m-d", strtotime("next ".$days[$end]));
					$days_gap = $this->daysCount( $startdate, $enddate );
									
				}else{  
					if( array_key_exists( ($cur_place+1), $availdays ) ){ // If current place has more than one values 
						$end = intval($availdays[0]);						
						$enddate = date("Y-m-d", strtotime("next ".$days[$end]));
						$days_gap = $this->daysCount( $startdate, $enddate );												
					}					
				}								
			}else if( $count == ($cur_place+1) ){  			
				if( $count == 1 ){ 
					$end = intval($availdays[0]);
					$enddate = date("Y-m-d", strtotime("+2 weeks ".$days[$end]));
				}else{  
					$end = intval($availdays[1]);					
					$enddate = date("Y-m-d", strtotime("next ".$days[$end]));
				}
				$days_gap = $this->daysCount( $startdate, $enddate );													
			}
			
			if( $days_gap > 1 ){
				$delivery_day = date('l, M d', strtotime('+'.($days_gap).' days'));
				$text = str_replace( array('{clock-icon}', '{strong}','{/strong}','{delivery-time}', '{delivery-day}', '{timer}'),array('<i class="fa fa-clock-o"></i>', '<strong>', '</strong>', $delivery_time, $delivery_day, '<span id="'.$id.'"></span>'),$opts['ct-dtext-beyond-cut-off']);
			}else{
				$delivery_day = date('M d', strtotime('+'.($days_gap).' days'));				
				$text = str_replace( array('{clock-icon}', '{strong}','{/strong}','{delivery-time}', '{delivery-day}', '{timer}'),array('<i class="fa fa-clock-o"></i>', '<strong>', '</strong>', $delivery_time, $delivery_day, '<span id="'.$id.'"></span>'),$opts['ct-dtext-by-tomorrow']);
			}
		}	
		return $text;
	}
	
	// Count number of days between two dates
	public function daysCount( $start, $end ){		
		$datediff = abs(strtotime($end) - strtotime($start));
		return intval($datediff/86400);
	}
	
	

}
?>