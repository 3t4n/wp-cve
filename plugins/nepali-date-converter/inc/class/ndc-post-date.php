<?php
/**
 * NDC Post Date Setting
 *
 * @since NDC 2.0.0
 */
if ( ! class_exists( 'NDC_Post_Date' ) ){

	class NDC_Post_Date {

		/**
		 * Variable to hold options
		 *
		 * @var array
		 * @access protected
		 * @since 2.0.0
		 *
		 */
		protected $options;

		/**
		 * Variable to hold default options
		 *
		 * @var array
		 * @access protected
		 * @since 2.0.0
		 *
		 */
		protected $defaults = array(
			'active' => array(
				'date' => false,
				'time' => false,
				'modified_date' => false,
				'modified_time' => false
			),
			'date_format' => array(
				'selected' => 'F j, Y',
				'custom' => '',
			),
			'force_format' => false,
			'human_time_diff' => array(
				'front' => false,
				'home' => false,
				'archive' => false,
				'single' => false,
				'suffix' => 'अगाडि',
			),
			'post_types' => array('post'),
		);

		/**
		 * Construct
		 * Nothing here
		 *
		 * @since 2.0.0
		 *
		 */
		public function __construct() {}

		/**
		 * Gets an instance of this object.
		 * Prevents duplicate instances which avoid artefacts and improves performance.
		 *
		 * @static
		 * @access public
		 * @since 2.0.0
		 * @return object
		 */
		public static function get_instance() {

			// Store the instance locally to avoid private static replication
			static $instance = null;

			// Only run these methods if they haven't been ran previously
			if ( null === $instance ) {
				$instance = new self();
			}

			// Always return the instance
			return $instance;
		}

		/**
		 * Run this Class Module
		 *
		 * @access public
		 * @since 2.0.0
		 * @return void
		 */
		public function run(){
			add_action('admin_init', array($this, 'ndc_setting_options'));
			add_action('admin_head', array($this, 'options_general_add_js'));
			add_action('wp_ajax_ndc_date_format', array($this, 'get_date_format'));
			add_action('init', array($this, 'init_ndc_post_date'));
			add_action('plugin_action_links_'.NEPALI_DATE_CONVERTER_NAME, array($this, 'action_link'));
		}

		/**
		 * Callback function of admin_init
		 * Admin =>Setting => General
		 * @param $links array
         *
		 * @since 2.0.1
		 *
		 */
		public function action_link($links){
			$settings_link = '<a href="options-general.php#ndc-post-date-start">' . __( 'Settings', 'nepali-date-converter' ) . '</a>';
			array_push( $links, $settings_link );
			return $links;
        }

		/**
		 * Callback function of admin_init
		 * Admin =>Setting => General
		 *
		 * @since 2.0.0
		 *
		 */
		public function ndc_setting_options() {

			/*register a new section in the "general" page*/
			add_settings_section(
				'ndc_post_date_section',
				__( 'Nepali Post Date - Nepali Date Converter', 'nepali-date-converter'),
				array($this,'settings_section_msg'),
				'general'
			);
			/*Adding setting field*/
			add_settings_field(
				'ndc_post_date_options[active]',
				__( 'Activate Nepali Post Date On', 'nepali-date-converter'),
				array($this, 'activate_callback' ),
				'general',
				'ndc_post_date_section'
			);
			add_settings_field(
				'ndc_post_date_options[date_format]',
				__( 'Date Format', 'nepali-date-converter'),
				array($this, 'date_format_callback' ),
				'general',
				'ndc_post_date_section'
			);
			add_settings_field(
				'ndc_post_date_options[force_format]',
				__( 'Force Format', 'nepali-date-converter'),
				array($this, 'force_format_callback' ),
				'general',
				'ndc_post_date_section'
			);
			add_settings_field(
				'ndc_post_date_options[human_time_diff]',
				__( 'Activate Nepali Human Time Diff On', 'nepali-date-converter'),
				array($this, 'human_time_diff_callback' ),
				'general',
				'ndc_post_date_section'
			);
			add_settings_field(
				'ndc_post_date_options[post_types]',
				__( 'Post Types Support', 'nepali-date-converter'),
				array($this, 'post_types_callback' ),
				'general',
				'ndc_post_date_section'
			);
			/*Register Setting*/
			register_setting(
				'general',
				'ndc_post_date_options',
				array(
					'type'              => 'array',
					'description'       =>  __( 'All Options related to NDC Post Date', 'nepali-date-converter'),
					'sanitize_callback' => array($this, 'sanitize_options' ),
					'default' => $this->defaults,
				)
			);
		}

		/**
		 * Callback function of add_settings_section
		 * Initialize from ndc_setting_options
		 *
		 * @since 2.0.0
		 */
		public function settings_section_msg() {
			echo "<p id='ndc-post-date-start'>";
			esc_html_e( 'All Options related to NDC Post Date.', 'nepali-date-converter');
			esc_html_e( " Does not work on following date format : 'S'", 'nepali-date-converter');
			echo "</p>";
			echo '<hr/>';
		}

		/**
		 * Callback function of add_settings_section
		 * Initialize from add_settings_field
		 *
		 * @since 2.0.0
		 */
		public function activate_callback() {
			$active = $this->options['active'];
			?>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e( 'Date', 'nepali-date-converter' ); ?></span></legend>
                <label for="ndc_post_date_options[active][date]">
                    <input name="ndc_post_date_options[active][date]" type="checkbox" id="ndc_post_date_options[active][date]" value="1" <?php checked( '1', $active['date'] ); ?> />
                    <div class="date-time-text">
	                    <?php esc_html_e( 'Date', 'nepali-date-converter' ); ?>
                    </div>
                    <code><?php esc_html_e( 'It use get_the_date filter hook', 'nepali-date-converter' ); ?></code>
                </label>
            </fieldset>

            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e( 'Time', 'nepali-date-converter' ); ?></span></legend>
                <label for="ndc_post_date_options[active][time]">
                    <input name="ndc_post_date_options[active][time]" type="checkbox" id="ndc_post_date_options[active][time]" value="1" <?php checked( '1', $active['time'] ); ?> />
                    <div class="date-time-text">
	                    <?php esc_html_e( 'Time', 'nepali-date-converter' ); ?>
                    </div>
                    <code><?php esc_html_e( 'It use get_the_time filter hook', 'nepali-date-converter' ); ?></code>
                </label>
            </fieldset>

            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e( 'Date (Modified)', 'nepali-date-converter' ); ?></span></legend>
                <label for="ndc_post_date_options[active][modified_date]">
                    <input name="ndc_post_date_options[active][modified_date]" type="checkbox" id="ndc_post_date_options[active][modified_date]" value="1" <?php checked( '1', $active['modified_date'] ); ?> />
                    <div class="date-time-text">
	                    <?php esc_html_e( 'Date (Modified)', 'nepali-date-converter' ); ?>
                    </div>
                    <code><?php esc_html_e( 'It use get_the_modified_date filter hook', 'nepali-date-converter' ); ?></code>
                </label>
            </fieldset>

            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e( 'Time (Modified)', 'nepali-date-converter' ); ?></span></legend>
                <label for="ndc_post_date_options[active][modified_time]">
                    <input name="ndc_post_date_options[active][modified_time]" type="checkbox" id="ndc_post_date_options[active][modified_time]" value="1" <?php checked( '1', $active['modified_time'] ); ?> />
                    <div class="date-time-text">
	                    <?php esc_html_e( 'Time (Modified)', 'nepali-date-converter' ); ?>
                    </div>
                    <code><?php esc_html_e( 'It use get_the_modified_time filter hook', 'nepali-date-converter' ); ?></code>
                </label>
            </fieldset>
			<?php
		}

		/**
		 * Callback function of add_settings_field
		 * Initialize from ndc_setting_options
		 *
		 * @since 2.0.0
		 */
		public function date_format_callback(){

			$date_formats = array_unique( apply_filters( 'ndc_date_formats', array( __( 'F j, Y' ), 'Y-m-d', 'm/d/Y', 'd/m/Y' ) ) );
			?>
            <fieldset>
                <legend class="screen-reader-text">
                    <span><?php esc_html_e( 'Date Format', 'nepali-date-converter' ); ?></span>
                </legend>
				<?php
				$front_date_converter = ndc_frontend();
				foreach ( $date_formats as $format ) {
					echo "\t<label><input type='radio' name='ndc_post_date_options[date_format][selected]' value='" . esc_attr( $format ) . "'";
					checked( $format, $this->options['date_format']['selected'] );
					echo ' /> <div class="date-time-text format-i18n">';
					$front_date_converter->today_date(array(
						'result_format'=> $format,
						'disable_today_eng_date'=> true,
					));
					echo '</div><code>' . esc_html( $format ) . "</code></label><br />\n";
				}
				?>
                <label>
                    <input type="radio" id="ndc_post_date_options_custom_radio" name="ndc_post_date_options[date_format][selected]" value="ndc-date-format-custom" <?php checked( 'ndc-date-format-custom', $this->options['date_format']['selected'] ); ?>>
                    <span class="date-time-text date-time-custom-text">
                    <?php esc_html_e( 'Custom:', 'nepali-date-converter' ); ?>
                    <span class="screen-reader-text"> <?php esc_html_e( 'enter a custom date format in the following field', 'nepali-date-converter' ); ?></span>
                </span>
                </label>
                <label for="ndc_post_date_options[date_format][custom]" class="screen-reader-text">
					<?php esc_html_e( 'Custom date format:', 'nepali-date-converter' ); ?>
                </label>
                <input type="text" name="ndc_post_date_options[date_format][custom]" id="ndc_post_date_options[date_format][custom]" value="<?php echo $this->options['date_format']['custom'];?>" class="small-text">
                <br>
                <strong><?php esc_html_e( 'Preview:', 'nepali-date-converter' ); ?></strong>
                <div class="example">
					<?php
                    echo $this->get_current_nep_date($this->options['date_format']['selected'] !='ndc-date-format-custom'?$this->options['date_format']['selected']:$this->options['date_format']['custom'])['result'];
					?>
                </div>
                <span class="spinner"></span>
            </fieldset>
			<?php
		}

		/**
		 * Callback function of add_settings_field
		 * Initialize from ndc_setting_options
		 *
		 * @since 2.0.0
		 */
		public function force_format_callback(){
		    ?>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e( 'Force Format', 'nepali-date-converter' ); ?></span></legend>
                <label for="ndc_post_date_options[force_format]">
                    <input name="ndc_post_date_options[force_format]" type="checkbox" id="ndc_post_date_options[force_format]" value="1" <?php checked( '1', $this->options['force_format'] ); ?> />
                    <div class="date-time-text">
						<?php esc_html_e( 'Force Format', 'nepali-date-converter' ); ?>
                    </div>
	                <?php esc_html_e( 'When you check it, hard coded format will be ignored. eg:', 'nepali-date-converter' ); ?><code>get_the_date('F j, Y ')</code>
                </label>
            </fieldset>
            <?php
		}

		/**
		 * Callback function of add_settings_section
		 * Initialize from add_settings_field
         *
		 * @since 2.0.1
		 */
		public function human_time_diff_callback() {
			$human_time_diff = $this->options['human_time_diff'];
			?>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e( 'Front Page', 'nepali-date-converter' ); ?></span></legend>
                <label for="ndc_post_date_options[human_time_diff][front]">
                    <input name="ndc_post_date_options[human_time_diff][front]" type="checkbox" id="ndc_post_date_options[human_time_diff][front]" value="1" <?php checked( '1', $human_time_diff['front'] ); ?> />
                    <div class="date-time-text">
						<?php esc_html_e( 'Front Page', 'nepali-date-converter' ); ?>
                    </div>
                </label>
            </fieldset>

            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e( 'Home - Blog Home', 'nepali-date-converter' ); ?></span></legend>
                <label for="ndc_post_date_options[human_time_diff][home]">
                    <input name="ndc_post_date_options[human_time_diff][home]" type="checkbox" id="ndc_post_date_options[human_time_diff][home]" value="1" <?php checked( '1', $human_time_diff['home'] ); ?> />
                    <div class="date-time-text">
						<?php esc_html_e( 'Home ( Blog Home )', 'nepali-date-converter' ); ?>
                    </div>
                </label>
            </fieldset>

            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e( 'Archive', 'nepali-date-converter' ); ?></span></legend>
                <label for="ndc_post_date_options[human_time_diff][archive]">
                    <input name="ndc_post_date_options[human_time_diff][archive]" type="checkbox" id="ndc_post_date_options[human_time_diff][archive]" value="1" <?php checked( '1', $human_time_diff['archive'] ); ?> />
                    <div class="date-time-text">
						<?php esc_html_e( 'Archive', 'nepali-date-converter' ); ?>
                    </div>
                </label>
            </fieldset>

            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e( 'Single', 'nepali-date-converter' ); ?></span></legend>
                <label for="ndc_post_date_options[human_time_diff][single]">
                    <input name="ndc_post_date_options[human_time_diff][single]" type="checkbox" id="ndc_post_date_options[human_time_diff][single]" value="1" <?php checked( '1', $human_time_diff['single'] ); ?> />
                    <div class="date-time-text">
						<?php esc_html_e( 'Single', 'nepali-date-converter' ); ?>
                    </div>
                </label>
            </fieldset>

            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e( 'Suffix', 'nepali-date-converter' ); ?></span></legend>
                <label for="ndc_post_date_options[human_time_diff][suffix]">
                    <div class="date-time-text">
		                <?php esc_html_e( 'Suffix', 'nepali-date-converter' ); ?>
                    </div>
                    <input name="ndc_post_date_options[human_time_diff][suffix]" type="text" id="ndc_post_date_options[human_time_diff][suffix]" value="<?php echo esc_attr($human_time_diff['suffix'] );?>" />
                </label>
	            <?php esc_html_e( 'Example', 'nepali-date-converter' ); ?><code>अगाडि, अघि</code>
            </fieldset>
            <br />
            <strong><?php esc_html_e( 'Preview:', 'nepali-date-converter' ); ?></strong>
            <div id="ndc-htd-example">
                ३ सेकेन्ड <span><?php echo esc_html($human_time_diff['suffix'] );?></span>
            </div>
			<?php
		}

		/**
		 * Callback function of add_settings_field
		 * Initialize from ndc_setting_options
		 *
		 * @since 2.0.3
		 */
		public function post_types_callback(){
			$post_types = ndc_get_post_types();
			$saved_post_types = $this->options['post_types'];
			if( is_array( $post_types) && !empty( $post_types )){
			    foreach ( $post_types as $post_type){
			        ?>
                    <fieldset>
                        <legend class="screen-reader-text">
                            <span><?php echo esc_html( $post_type['value']) ?></span>
                        </legend>
                        <label for="ndc_post_date_options[post_types][<?php echo esc_attr( $post_type['value']) ?>]">
                            <input name="ndc_post_date_options[post_types][]" type="checkbox" id="ndc_post_date_options[human_time_diff][<?php echo esc_attr( $post_type['value']) ?>]" value="<?php echo esc_attr( $post_type['value']) ?>" <?php checked( true, in_array($post_type['value'], $saved_post_types)) ?> />
                            <div class="date-time-text">
	                            <?php echo esc_html( $post_type['label']) ?>
                            </div>
                        </label>
                    </fieldset>
                    <?php
			    }
			}

			?>

			<?php
		}

		/**
		 * Callback function of register_setting
		 * Initialize from ndc_setting_options
		 *
		 * @since 2.0.0
		 */
		public function sanitize_options( $options ) {

			/*Active Setting Radio*/
			$options['active']['date'] = (isset( $options['active']['date'] ) );
			$options['active']['time'] = (isset( $options['active']['time'] ) );
			$options['active']['modified_date'] = (isset( $options['active']['modified_date'] ) );
			$options['active']['modified_time'] = (isset( $options['active']['modified_time'] ) );

			/*Date Format*/
			$options['date_format']['selected'] = isset( $options['date_format']['selected'] )?sanitize_text_field($options['date_format']['selected']):'2';
			$options['date_format']['custom'] = isset( $options['date_format']['custom'] )?sanitize_text_field($options['date_format']['custom']):'';

			/*Force Format*/
			$options['force_format'] = (isset( $options['force_format'] ) );

			/*Human time different*/
			$options['human_time_diff']['front'] = (isset( $options['human_time_diff']['front'] ) );
			$options['human_time_diff']['home'] = (isset( $options['human_time_diff']['home'] ) );
			$options['human_time_diff']['archive'] = (isset( $options['human_time_diff']['archive'] ) );
			$options['human_time_diff']['single'] = (isset( $options['human_time_diff']['single'] ) );
			$options['human_time_diff']['suffix'] = sanitize_text_field($options['human_time_diff']['suffix']);

			/*Post Type Support*/
			$options['post_types'] = isset( $options['post_types'] )?array_map("sanitize_key",$options['post_types']):array();

			return $options;
		}

		/**
		 * Callback function of register_setting
		 * Initialize from ndc_setting_options
		 * Copied and modified from wp-admin\includes\options.php function options_general_add_js
		 *
		 * @since 2.0.0
		 */
		function options_general_add_js() {
			$currentScreen = get_current_screen();
			if( $currentScreen->id !== "options-general" ) {
				return;
			}
			?>
            <script type="text/javascript">
                jQuery(document).ready(function($){
                    $("input[name='ndc_post_date_options[date_format][selected]']").click(function(){
                        if ( "ndc_post_date_options_custom_radio" !== $(this).attr("id") ){
                            $( 'input[name="ndc_post_date_options[date_format][custom]"]' ).val( $( this ).val() ).closest( 'fieldset' ).find( '.example' ).text( $( this ).parent( 'label' ).children( '.format-i18n' ).text() );
                        }
                    });
                    $( 'input[name="ndc_post_date_options[date_format][custom]"]' ).on( 'click input', function() {
                        $( '#ndc_post_date_options_custom_radio' ).prop( 'checked', true );

                        var format = $( this ),
                            fieldset = format.closest( 'fieldset' ),
                            example = fieldset.find( '.example' ),
                            spinner = fieldset.find( '.spinner' );

                        // Debounce the event callback while users are typing.
                        clearTimeout( $.data( this, 'timer' ) );
                        $( this ).data( 'timer', setTimeout( function() {
                            // If custom date is not empty.
                            if ( format.val() ) {
                                spinner.addClass( 'is-active' );
                                $.post( ajaxurl, {
                                    action: 'ndc_date_format',
                                    date 	: format.val()
                                }, function( d ) { spinner.removeClass( 'is-active' ); example.html( d ); } );
                            }
                        }, 500 ) );
                    });

                    $( 'input[name="ndc_post_date_options[human_time_diff][suffix]"]' ).on( 'click input', function() {
                        $('#ndc-htd-example').children('span').text($( this ).val())
                    });
                });
            </script>
			<?php
		}

		/**
		 * Callback function of wp_ajax_ndc_date_format
		 * @param  string $format
         *
		 * @since 2.0.0
		 */
		function get_current_nep_date($format){
			$timezone = new DateTimeZone( 'Asia/Kathmandu' );
			$datetime = new DateTime( 'now', $timezone );

			$post_date_ymd = $datetime->format( 'Y-m-d-H-i-s' );
			$post_date_ymds = explode('-', $post_date_ymd );

			$today_nepali_date_data = ndc_eng_to_nep_date(array(
				'year' => (int) $post_date_ymds[0],
				'month' => (int) $post_date_ymds[1],
				'day' => (int) $post_date_ymds[2],
				'hour' => (int) $post_date_ymds[3],
				'min' => (int) $post_date_ymds[4],
				'sec' => (int) $post_date_ymds[5]
			),'nep_char',$format);
			return $today_nepali_date_data;
		}

		function get_date_format(){
		    echo  $this->get_current_nep_date(wp_unslash( $_POST['date'] ))['result'];
			exit;
		}

		/**
		 * Callback function of init
         * For Frontend
		 * $this->options is for both frontend and backend
         *
		 * @since 2.0.0
		 */
		function init_ndc_post_date(){
			/*Use both admin and frontend*/
            $db_options = get_option('ndc_post_date_options', $this->defaults);
            $db_options = array_merge($this->defaults, $db_options);
			$this->options = apply_filters( 'ndc_post_date_options', $db_options);

			/*return if in admin*/
			if( is_admin()){
				return;
			}
			/*Preparing Filter list*/
			$filter_list = array();

			if ($this->options['active']['date']):
				$filter_list []= 'get_the_date';
			endif;

			if ($this->options['active']['time']) :
				$filter_list []= 'get_the_time';
			endif;

			if ( $this->options['active']['modified_date'] ) :
				$filter_list []= 'get_the_modified_date';
			endif;

			if ( $this->options['active']['modified_time'] ) :
				$filter_list []= 'get_the_modified_time';
			endif;

			$post_date_filters = apply_filters(
				'ndc_post_date_filters',
				$filter_list
			);
			if( !$post_date_filters){
				return;
			}
			foreach ( $post_date_filters as $filter ) :
				add_filter( $filter, array( $this, 'post_date_to_nepali' ), 10, 3);
			endforeach;
		}

		/**
		 * Callback function from $filter
		 * For Frontend Only
         *
		 * Initialize from init_ndc_post_date
         * from various filters follows
         * get_the_date
         * get_the_time
         * get_the_modified_date
         * get_the_modified_time
		 *
		 * @since 2.0.0
		 */

		public function post_date_to_nepali(  $the_date, $format, $post  ){

			if( $format && !$this->options['force_format'] ){
				$result_format = $format;
			}
			else{
				$result_format = $this->options['date_format']['selected'] !='ndc-date-format-custom'?$this->options['date_format']['selected']:$this->options['date_format']['custom'];
			}

			/*Post Type Check*/
            if( !in_array($post->post_type, $this->options['post_types'])){
                return $the_date;
            }

			/*for format U
			just change num to nepali and return*/
            if( $result_format == 'U' || $format == 'U'){
	            return strtr( $the_date, ndc_nepali_calendar()->eng_nep_num );
            }

			/*Human time different*/
            $ndc_post_date = strtotime( $post->post_date );
			if ((is_front_page() && $this->options['human_time_diff']['front']) ||
                (is_home() && $this->options['human_time_diff']['home']) ||
                (is_archive() && $this->options['human_time_diff']['archive']) ||
                (is_single() && $this->options['human_time_diff']['single'])
            ){
				return ndc_human_time_diff( $ndc_post_date, current_time('timestamp') ) .' '.esc_html($this->options['human_time_diff']['suffix']);
			}

			/*change to YMD*/
			$post_date_ymd = date('Y-m-d-H-i-s', $ndc_post_date);
			$post_date_ymds = explode('-', $post_date_ymd );

			/*define characters*/
			$nepali_date_lang = 'nep_char';

			$today_nepali_date_data = ndc_eng_to_nep_date(array(
				'year' => (int) $post_date_ymds[0],
				'month' => (int) $post_date_ymds[1],
				'day' => (int) $post_date_ymds[2],
				'hour' => (int) $post_date_ymds[3],
				'min' => (int) $post_date_ymds[4],
				'sec' => (int) $post_date_ymds[5]
            ),$nepali_date_lang,$result_format );

			return $today_nepali_date_data['result'];
		}
	}
}

/**
 * Begins execution of the module
 * NDC_Post_Date
 *
 * @since    2.0.0
 */
if( !function_exists( 'ndc_post_date')){

	function ndc_post_date() {

		return NDC_Post_Date::get_instance();
	}
	ndc_post_date()->run();
}