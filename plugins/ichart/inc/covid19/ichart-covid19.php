<?php
error_reporting(0);
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'IchartCovid19' ) ) {
	class IchartCovid19 {

		function __construct() {
			define( 'QCICHART_COVID19_VER', '1.0.1' );
			if ( ! defined( 'QCICHART_COVID19_URL' ) ) {
				define( 'QCICHART_COVID19_URL', plugin_dir_url( __FILE__ ) );
			}
			if ( ! defined( 'QCICHART_COVID19_PATH' ) ) {
				define( 'QCICHART_COVID19_PATH', plugin_dir_path( __FILE__ ) );
			}
			
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ) );
			add_action( 'admin_menu', array( $this, 'register_custom_menu_page' ) );
			$this->wp_parse_args();
			//$this->qcldichartcovidDL();
			add_action( 'init', array( $this, 'register_assets' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'qcldichartcovid_enqueues' ) );
			add_shortcode( 'QCLDCOVID19-WIDGET', array($this, 'qcldichartcovid_shortcode') );
			add_shortcode( 'QCLDCOVID19-TICKER', array($this, 'qcldichartcovid_short_ticker') );

		}

		function register_custom_menu_page(){
			
			add_submenu_page( 'qcopd_ichart_info_page', esc_attr__( 'Covid-19 Options', 'qc-ichart' ), esc_attr__( 'Covid-19 Options', 'qc-ichart' ), 'manage_options' ,'qcld-ichart-covid19-options', array($this, 'true_option_page') );
			
		}
		
		function register_assets() {
			$qcldichartcovidAll = get_option('qcldichartcovidAL');
			$qcldichartcovidGC = get_option('qcldichartcovidCC');
			$qcldichartcovidGS = get_option('qcldichartcovidUS');
			$qcldichartcovidGH = get_option('qcldichartcovidCH');
			wp_register_style( 'qcldcovid', QCICHART_COVID19_URL . 'assets/css/styles.css', array(), QCICHART_COVID19_VER );
			wp_register_script( 'jquery.datatables', QCICHART_COVID19_URL . 'assets/js/jquery.dataTables.min.js', array( 'jquery' ), QCICHART_COVID19_VER, true );
			wp_register_script( 'graph', QCICHART_COVID19_URL . 'assets/js/ichart-graph.js', array( 'jquery' ), QCICHART_COVID19_VER, true );			
			wp_register_script( 'qcldcovid', QCICHART_COVID19_URL . 'assets/js/scripts.js', array( 'jquery' ), QCICHART_COVID19_VER, true );
			$translation_array = array(
				'all' => $qcldichartcovidAll,
				'countries' => $qcldichartcovidGC,
				'story' => $qcldichartcovidGH
			);
			wp_localize_script( 'qcldcovid', 'covid', $translation_array );
		}

		public function admin_enqueue_assets() {
			wp_enqueue_script( 'qcldcovid-admin', QCICHART_COVID19_URL . 'assets/js/admin-script.js', array( 'jquery' ), QCICHART_COVID19_VER, true );
			wp_enqueue_style( 'qcldcovid-admin', QCICHART_COVID19_URL . 'assets/admin-style.css', array(), QCICHART_COVID19_VER );
		}
		
		function wp_parse_args(){
			add_filter( 'cron_schedules', array( $this, 'add_wp_cron_schedule' ) );
			if ( ! wp_next_scheduled( 'wp_schedule_event' ) ) {
				$next_timestamp = wp_next_scheduled( 'wp_schedule_event' );
				if ( $next_timestamp ) {
					wp_unschedule_event( $next_timestamp, 'wp_schedule_event' );
				}
				wp_schedule_event( time(), 'every_10minute', 'wp_schedule_event' );
			}
			add_action( 'wp_schedule_event', array($this,'qcldicpGetA') );
		}
		
		function add_wp_cron_schedule( $schedules ) {
			$schedules['every_10minute'] = array(
				'interval' => 10*60,
				'display'  => esc_attr__( '10 min', 'qc-ichart' ),
			);
			return $schedules;
		}
		
		function qcldicpGetA() {
			$all = $this->qcldicpGen(false);
			$countries = $this->qcldicpGen(true);
			$story = $this->qcldicpGen(false, true);
			$qcldichartcovidAll = get_option('qcldichartcovidAL');
			$qcldichartcovidGC = get_option('qcldichartcovidCC');
			$qcldichartcovidGH = get_option('qcldichartcovidCH');

			if ($qcldichartcovidAll) {
				update_option( 'qcldichartcovidAL', $all );
			} else {
				add_option('qcldichartcovidAL', $all);
			}
			if ($qcldichartcovidGC) {
				update_option( 'qcldichartcovidCC', $countries );
			} else {
				add_option('qcldichartcovidCC', $countries);
			}
			if ($qcldichartcovidGH) {
				update_option( 'qcldichartcovidCH', $story );
			} else {
				add_option('qcldichartcovidCH', $story);
			}
		}
		
		function qcldichartcovidDL(){
			$qcldichartcovidAll = get_option('qcldichartcovidAL');
			$qcldichartcovidGC = get_option('qcldichartcovidCC');
			$qcldichartcovidGH = get_option('qcldichartcovidCH');
			if (!$qcldichartcovidGC) {
				$countries = $this->qcldicpGen(true);
				update_option( 'qcldichartcovidCC', $countries );
			}
			if (!$qcldichartcovidAll) {
				$all = $this->qcldicpGen(false);
				update_option( 'qcldichartcovidAL', $all );
			}
			if (!$qcldichartcovidGH) {
				$story = $this->qcldicpGen(false, true);
				update_option( 'qcldichartcovidCH', $story );
			}
		}

		
		// ISO 3166-1 UN Geoscheme regional codes
		// https://github.com/lukes/ISO-3166-Countries-with-Regional-Codes
		public $lands = array(
			'NorthAmerica' => 'AIAATGABWBHSBRBBLZBMUBESVGBCANCYMCRICUBCUWDMADOMSLVGRLGRDGLPGTMHTIHNDJAMMTQMEXSPMMSRANTKNANICPANPRIBESBESSXMKNALCASPMVCTTTOTCAUSAVIR','SouthAmerica' => 'ARGBOLBRACHLCOLECUFLKGUFGUYPRYPERSURURYVEN','Africa' => 'DZAAGOSHNBENBWABFABDICMRCPVCAFTCDCOMCOGCODDJIEGYGNQERISWZETHGABGMBGHAGINGNBCIVKENLSOLBRLBYMDGMWIMLIMRTMUSMYTMARMOZNAMNERNGASTPREURWASTPSENSYCSLESOMZAFSSDSHNSDNSWZTZATGOTUNUGACODZMBTZAZWE','Asia' => 'AFGARMAZEBHRBGDBTNBRNKHMCHNCXRCCKIOTGEOHKGINDIDNIRNIRQISRJPNJORKAZKWTKGZLAOLBNMACMYSMDVMNGMMRNPLPRKOMNPAKPSEPHLQATSAUSGPKORLKASYRTWNTJKTHATURTKMAREUZBVNMYEM','Europe' => 'ALBANDAUTBLRBELBIHBGRHRVCYPCZEDNKESTFROFINFRADEUGIBGRCHUNISLIRLIMNITAXKXLVALIELTULUXMKDMLTMDAMCOMNENLDNORPOLPRTROURUSSMRSRBSVKSVNESPSWECHEUKRGBRVATRSB','Oceania' => 'ASMAUSNZLCOKTLSFSMFJIPYFGUMKIRMNPMHLUMINRUNCLNZLNIUNFKPLWPNGMNPWSMSLBTKLTONTUVVUTUMIWLF'
		);
		
		function qcldicpGen($countries=false,$story=false){
			//$qcldicpURI 	= 'https://disease.sh/';
			$qcldicpURI 	= 'https://api.caw.sh/';
			$qcldicpTrack = 'v2/all';
			
			if ($story) {
				$qcldicpTrack = 'v2/historical/all';
			}

			if ($countries && !$story) {
				$qcldicpTrack = 'v2/countries/?sort=cases';
			} else if ($story && $countries) {
				$qcldicpTrack = 'v2/historical/'.$countries.'?lastdays=60';
			}

			$qcldicpURI = $qcldicpURI.$qcldicpTrack;
			$args = array(
				'timeout' => 60
			); 
			$request = wp_remote_get($qcldicpURI, $args);
			$body = wp_remote_retrieve_body( $request );
			$data = json_decode( $body );

			$qcldicpGen = current_time('timestamp');
			if (get_option('setUpd')) {
				update_option( 'setUpd', $qcldicpGen);
			} else {
				add_option( 'setUpd', $qcldicpGen );
			}

			return $data;
		}
		
		function qcldichartcovid_shortcode( $atts ){
			$params = shortcode_atts( array(
				'title_widget' => esc_attr__( 'Worldwide', 'qc-ichart' ),
				'country' => null,
				'land' => '',
				'confirmed_title' => esc_attr__( 'Cases', 'qc-ichart' ),
				'today_cases' => esc_attr__( '24h', 'qc-ichart' ),
				'deaths_title' => esc_attr__( 'Deaths', 'qc-ichart' ),
				'today_deaths' => esc_attr__( '24h', 'qc-ichart' ),
				'recovered_title' => esc_attr__( 'Recovered', 'qc-ichart' ),
				'active_title' => esc_attr__( 'Active', 'qc-ichart' ),
				'total_title' => esc_attr__( 'Total', 'qc-ichart' ),
				'format' => 'default'
			), $atts );

			if ($params['format'] === 'full') {
				$params['format'] = true;
			}

			$data = get_option('qcldichartcovidAL');
			if ($params['country'] || $params['format'] == 'card' ) {
				$data = get_option('qcldichartcovidCC');
				if ($params['country'] && $params['format'] !== 'card' ) {
					$new_array = array_filter($data, function($obj) use($params) {
						if ($obj->country === $params['country']) {
							return true;
						}
						return false;
					});
					if ($new_array) {
						$data = reset($new_array);
					}
				}
			}
			
			if ($params['land']) {
				$countries = $this->lands[$params['land']];
				$countries = str_split($countries, 3);
				
				
				
				$new_array = array_filter($data, function($obj) use($countries) {
					if (in_array($obj->countryInfo->iso3, $countries)) {
						return true;
					}
					return false;
				});
				
				

				if ($new_array) {
					$data = $new_array;
				}
			}
			
			ob_start();
			if ($params['format'] == 'full') {
				echo $this->render_card($params, $data);
			} else {
				echo $this->render_widget($params, $data);
			}
			return ob_get_clean();
		}
		
		
		
		function qcldichartcovid_short_ticker( $atts ){
			$params = shortcode_atts( array(
				'country' => null,
				'confirmed_title' => esc_attr__( 'Cases', 'qc-ichart' ),
				'deaths_title' => esc_attr__( 'Deaths', 'qc-ichart' ),
				'recovered_title' => esc_attr__( 'Recovered', 'qc-ichart' ),
				'ticker_title' => esc_attr__( 'World', 'qc-ichart' ),
				'style' => 'vertical'
			), $atts );
			$data = get_option('qcldichartcovidAL');
			if ($params['country']) {
				$data = get_option('qcldichartcovidCC');
				if ($params['country']) {
					$new_array = array_filter($data, function($obj) use($params) {
						if ($obj->country === $params['country']) {
							return true;
						}
						return false;
					});
					if ($new_array) {
						$data = reset($new_array);
					}
				}
			}
		
			if ($params['style'] === 'vertical') {
				$params['style'] = 'vertical';
			} else {
				$params['style'] = 'horizontal';
			}

			ob_start();
			echo $this->render_ticker($params, $data);
			return ob_get_clean();
		}
		

		
		function render_card($params, $data){
			ob_start();
			include( QCICHART_COVID19_PATH .'includes/render_card.php');
			return ob_get_clean();
		}

		function render_widget($params, $data){
			wp_enqueue_style( 'qcldcovid' );
			$all_options = get_option( 'qcldcovid19_options' );
			ob_start();
			?>
			<div class="qcichartcovid19-card  <?php echo $all_options['cov_theme'];?> <?php if($all_options['cov_rtl']==!$checked) echo 'rtl_enable'; ?>" style="font-family:<?php echo $all_options['cov_font'];?>">
				<h4 class="qcichartcovid19-title-big"><?php echo esc_html(isset($params['title_widget']) ? $params['title_widget'] : ''); ?></h4>
				<div class="qcichartcovid19-row">
					<div class="qcichartcovid19-col qcichartcovid19-confirmed">
						<div class="qcichartcovid19-num"><?php echo number_format($data->cases); ?></div>
						<div class="qcichartcovid19-title"><?php echo esc_html($params['confirmed_title']); ?></div>
					</div>
					<div class="qcichartcovid19-col qcichartcovid19-deaths">
						<div class="qcichartcovid19-num"><?php echo number_format($data->deaths); ?></div>
						<div class="qcichartcovid19-title"><?php echo esc_html($params['deaths_title']); ?></div>
					</div>
					<div class="qcichartcovid19-col qcichartcovid19-recovered">
						<div class="qcichartcovid19-num"><?php echo number_format($data->recovered); ?></div>
						<div class="qcichartcovid19-title"><?php echo esc_html($params['recovered_title']); ?></div>
					</div>
				</div>
			</div>
			<?php
			return ob_get_clean();
		}

		
		function render_ticker($params, $data){
			wp_enqueue_style( 'qcldcovid' );
			$dataAll = get_option('qcldichartcovidAL');
			$all_options = get_option( 'qcldcovid19_options' );
			ob_start();
			?>
			<div class="qcichartcovid19-ticker qcichartcovid19-ticker-style-<?php echo esc_attr($params['style'] ? $params['style'] : 'vertical'); ?> <?php echo $all_options['cov_theme'];?> <?php if($all_options['cov_rtl']==!$checked) echo 'rtl_enable'; ?>" style="font-family:<?php echo $all_options['cov_font'];?>">
				<span><?php echo esc_html($params['ticker_title']); ?></span>
				<ul>
					<li><?php echo esc_html($params['confirmed_title']); ?>: <?php echo number_format($data->cases); ?></li>
					<li><?php echo esc_html($params['deaths_title']); ?>: <?php echo number_format($data->deaths); ?></li>
					<li><?php echo esc_html($params['recovered_title']); ?>: <?php echo number_format($data->recovered); ?></li>
				</ul>
				
				
			</div>
			<?php
			return ob_get_clean();
		}

		
		

		/**
		 * Callback
		 */ 
		function true_option_page(){
			global $true_page;
			?><div id="qcldicp-admin-container">
				<div class="grid-x grid-container grid-padding-y admin-settings">
				<div class="cell small-12">
				<div class="callout">
					<h2><?php echo esc_html__( 'iChart COVID-19 Options', 'qc-ichart' );?></h2>
					<p><?php echo esc_html__( 'iChart COVID-19 allows adding statistics via shortcode to inform site visitors about changes in the situation about Coronavirus pandemic.', 'qc-ichart' );?></p>
				</div>
				<div class="tabs-content grid-x" data-tabs-content="setting-tabs">
				<div class="tabs-panel is-active" id="options" role="tabpanel" aria-labelledby="options-label">
				<!--<div class="notify"></div>-->
				<div class="grid-x display-required callout" style="opacity: 1; pointer-events: inherit;">
					<form method="post" enctype="multipart/form-data" action="options.php">
						<?php 
						settings_fields('qcldcovid19_options');
						do_settings_sections($true_page);
						?>
						<p class="submit">  
							<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />  
						</p>
					</form>
				</div>
						
		<?php $data = get_option('qcldichartcovidCC');?>
		
		
	
		
		<div class="grid-x display-required callout" style="opacity: 1; pointer-events: inherit;">
			<div class="small-12 cell">
				<h1><a href="https://www.quantumcloud.com/products/iChart/" target="_blank"><?php esc_html_e('😃 Upgrade to Pro', 'qc-ichart'); ?></a></h1>
			</div>
			
			<div class="small-12 cell">
				<h1><a href="https://dev.quantumcloud.com/ichart/covid19/" target="_blank"><?php esc_html_e('🤜 Pro Version Demo for COVID Charts', 'qc-ichart'); ?></a></h1>
			</div>
			
			<div class="small-12 cell" style="color:red">
				<h2><?php esc_html_e('➡️ Pro Features', 'qc-ichart'); ?></h2>
				<ol>
					<li>Map of Countries</li>
					<li>Map of the USA</li>
					<li>List of Countries</li>
					<li>Graph</li>
					<li>Table of Countries</li>
					<li>Inline Text data</li>
				</ol>
			</div>

			

		</div>

		
		
		
		</div>
		</div>
		</div>
		</div><!--settings -->
		<div style="float:right;width: 25rem;margin-top: 15px;">
			<div class="display-required callout primary" style="opacity: 1; pointer-events: inherit;">
				<div class="small-12 cell">
					<h3><?php esc_html_e('What do the terms mean?', 'qc-ichart'); ?></h3>
				</div>
				<p><b><?php esc_html_e('Confirmed', 'qc-ichart'); ?></b> — <?php esc_html_e('The number of confirmed (recorded) cases', 'qc-ichart'); ?>.</p>
				<p><b><?php esc_html_e('Active', 'qc-ichart'); ?></b> — <?php esc_html_e('The number of confirmed cases that are still infected (Active = Confirmed - Deaths - Recovered)', 'qc-ichart'); ?>.</p>
				<p><b><?php esc_html_e('Deaths', 'qc-ichart'); ?></b> — <?php esc_html_e('The number of confirmed cases that have died', 'qc-ichart'); ?>.</p>
				<p><b><?php esc_html_e('Recovered', 'qc-ichart'); ?></b> — <?php esc_html_e('The number of confirmed cases that have recovered', 'qc-ichart'); ?>.</p>
				<hr>
				<div class="small-12 cell">
					<h3><?php esc_html_e('What do the columns in the table mean?', 'qc-ichart'); ?></h3>
				</div>
				<p><b><?php esc_html_e('24h', 'qc-ichart'); ?></b> — <?php esc_html_e('The amount of new data in last 24 hours', 'qc-ichart'); ?>.</p>
				<p><b><?php esc_html_e('%', 'qc-ichart'); ?></b> — <?php esc_html_e('Percentage of Deaths or Recovered or Active in Confirmed Cases', 'qc-ichart'); ?>.</p>
				<p><b><?php esc_html_e('-', 'qc-ichart'); ?></b> — <?php esc_html_e('If there is no such data or 0, returns the empty string', 'qc-ichart'); ?>.</p>
				<hr>
				<div class="small-12 cell">
					<h3><?php esc_html_e('Data Sources', 'qc-ichart'); ?></h3>
				</div>
				<p><?php esc_html_e('WHO, CDC, ECDC, NHC, JHU CSSE, DXY & QQ', 'qc-ichart'); ?>.</p>
			</div>
		</div>
		
		</div>
			<?php
		}
		
		function qcldichartcovid_enqueues(){
			$qcldcovid19_options = get_option('qcldcovid19_options');
			wp_enqueue_style('qcldichartcovid_style', QCICHART_COVID19_URL . 'assets/style.css', array(), QCICHART_COVID19_VER );
			$qcldichartcovid_custom_css = "{$qcldcovid19_options['cov_css']}";
			wp_add_inline_style('qcldichartcovid_style', $qcldichartcovid_custom_css);
		}
	}
		new IchartCovid19();			
}
	
		
	
		function qcld_ichart_covid_option_settings() {
			global $true_page;
			// ( qcld_ichart_covid19_option_validate_settings() )
			register_setting( 'qcldcovid19_options', 'qcldcovid19_options', 'qcld_ichart_covid19_option_validate_settings' );
		 
			// Add section
			add_settings_section( 'true_section_1', esc_html__( 'Customization', 'qc-ichart' ), '', $true_page );


			$true_field_params = array(
				'type'      => 'select',
				'id'        => 'cov_theme',
				'desc'      => '',
				'vals'		=> array( 'dark_theme' => esc_html__( 'Dark', 'qc-ichart' ), 'light_theme' => esc_html__( 'Light', 'qc-ichart' )),
				'label_for' => 'cov_theme'
			);
			add_settings_field( 'cov_theme_field', esc_html__( 'Theme', 'qc-ichart' ), 'qcld_ichart_true_option_display_settings', $true_page, 'true_section_1', $true_field_params );
			
			$true_field_params = array(
				'type'      => 'select',
				'id'        => 'cov_font',
				'desc'      => '',
				'label_for' => 'cov_font',
				'vals'		=> array( '-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Ubuntu,Helvetica Neue,sans-serif' => 'Default', 'inherit' => 'As on the website', 'Arial,Helvetica,sans-serif' => 'Arial, Helvetica', 'Tahoma,Geneva,sans-serif' => 'Tahoma, Geneva', 'Trebuchet MS, Helvetica,sans-serif' => 'Trebuchet MS, Helvetica', 'Verdana,Geneva,sans-serif' => 'Verdana, Geneva', 'Georgia,sans-serif' => 'Georgia', 'Palatino,sans-serif' => 'Palatino', 'Times New Roman,sans-serif' => 'Times New Roman')
			);
			add_settings_field( 'cov_font_field', esc_html__( 'Font', 'qc-ichart' ), 'qcld_ichart_true_option_display_settings', $true_page, 'true_section_1', $true_field_params );
		 
			$true_field_params = array(
				'type'      => 'textarea',
				'id'        => 'cov_css',
				'default'	=> null,
				'desc'      => esc_html__( 'Without &lt;style&gt; tags', 'qc-ichart' ),
				'label_for' => 'cov_css'
			);
			add_settings_field( 'cov_css_field', esc_html__( 'Custom CSS', 'qc-ichart' ), 'qcld_ichart_true_option_display_settings', $true_page, 'true_section_1', $true_field_params );
			
			$true_field_params = array(
				'type'      => 'checkbox',
				'id'        => 'cov_rtl',
				'desc'      => esc_html__( 'Enable', 'qc-ichart' ),
				'label_for' => 'cov_rtl'
			);
			add_settings_field( 'cov_rtl_field', esc_html__( 'Right-to-Left support', 'qc-ichart' ), 'qcld_ichart_true_option_display_settings', $true_page, 'true_section_1', $true_field_params );
			
		}
		add_action( 'admin_init', 'qcld_ichart_covid_option_settings' );
		 
		/*
		 * Show fields
		 */
		function qcld_ichart_true_option_display_settings($args) {
			extract( $args );
		 
			$option_name = 'qcldcovid19_options';
		 
			$o = get_option( $option_name );
		 
			switch ( $type ) {
				case 'text':  
					$o[$id] = esc_attr( stripslashes($o[$id]) );
					echo "<input class='regular-text' type='text' id='$id' placeholder='$placeholder' name='" . $option_name . "[$id]' value='$o[$id]' />";  
					echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";  
				break;
				case 'textarea':  
					$o[$id] = esc_attr( stripslashes($o[$id]) );
					echo "<textarea class='code regular-text' cols='12' rows='3' type='text' id='$id' name='" . $option_name . "[$id]'>$o[$id]</textarea>";    
					echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";  
				break;
				case 'checkbox':
					$checked = ($o[$id] == 'on') ? " checked='checked'" :  '';  
					echo "<label><input type='checkbox' id='$id' name='" . $option_name . "[$id]' $checked /> ";  
					echo ($desc != '') ? $desc : "";
					echo "</label>";  
				break;
				case 'select':
					echo "<select id='$id' name='" . $option_name . "[$id]'>";
					foreach($vals as $v=>$l){
						$selected = ($o[$id] == $v) ? "selected='selected'" : '';  
						echo "<option value='$v' $selected>$l</option>";
					}
					echo ($desc != '') ? $desc : "";
					echo "</select>";  
				break;
				case 'radio':
					echo "<fieldset>";
					foreach($vals as $v=>$l){
						$checked = ($o[$id] == $v) ? "checked='checked'" : '';  
						echo "<label><input type='radio' name='" . $option_name . "[$id]' value='$v' $checked />$l</label><br />";
					}
					echo "</fieldset>";  
				break; 
			}
		}
		 
		/*
		 * Check fields
		 */
		function qcld_ichart_covid19_option_validate_settings($input) {
			foreach($input as $k => $v) {
				$valid_input[$k] = trim($v);
			}
			return $valid_input;
		}

		function qcld_ichart_covid_insert_jquery(){
			wp_enqueue_script('jquery', false, array(), false, false);
		}
		add_filter('wp_enqueue_scripts','qcld_ichart_covid_insert_jquery',1);
		