<?php
/*
	Plugin Name: Stock Market News
	Plugin URI: http://www.stockdio.com/wordpress
	Description: A WordPress plugin for displaying a list of Stock Market News, available in several languages.
	Author: Stockdio
	Version: 1.9.12
	Author URI: http://www.stockdio.com
*/
//set up the admin area options page
define('stockdio_news_version','1.9.12');
define( 'stockdio_news_board__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
class StockdioNewsSettingsPage
{
		public static function get_page_url( $page = 'config' ) {

		$args = array( 'page' => 'stockdio-news-board-settings-config' );

		$url = add_query_arg( $args, class_exists( 'Jetpack' ) ? admin_url( 'admin.php' ) : admin_url( 'options-general.php' ) );

		return $url;
	}
	public static function view( $name) {
		$file = stockdio_news_board__PLUGIN_DIR . $name . '.php';
		include( $file );
	}
	
	public static function display_admin_alert() {
		self::view( 'stockdio_news_activate_plugin_admin' );
	}
	public static function display_settings_alert() {
		self::view( 'stockdio_news_activate_plugin_settings' );
	}
	
	public static function stockdio_news_board_display_notice() {
		global $hook_suffix;
		$stockdio_news_board_options = get_option( 'stockdio_news_board_options' );
		$api_key = $stockdio_news_board_options['api_key'];
		/*print $hook_suffix;*/
		if (($hook_suffix == 'plugins.php' || in_array( $hook_suffix, array( 'jetpack_page_stockdio-news-board-key-config', 'settings_page_stockdio-news-board-key-config', 'settings_page_stockdio-news-board-settings-config', 'jetpack_page_stockdio-news-board-settings-config' ))) && empty($api_key))
		{
			if ($hook_suffix == 'plugins.php')
				self::display_admin_alert();
			else
				self::display_settings_alert();
		}
		
	}
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $stockdio_news_board_options;

    /**
     * Start up
     */
    public function __construct()
    {
		
        add_action( 'admin_menu', array( $this, 'stockdio_news_board_add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'stockdio_news_board_page_init' ) );
		add_action( 'admin_notices', array( $this, 'stockdio_news_board_display_notice' ) );
		add_action('admin_head', 'stockdio_news_board_stockdio_js');
		add_action('admin_head', 'stockdio_news_board_charts_button');
    }
	
    /**
     * Add options page
     */
    public function stockdio_news_board_add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Stock Market News Settings', 
            'Stock Market News', 
            'manage_options', 
            'stockdio-news-board-settings-config', 
            array( $this, 'stockdio_news_board_create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function stockdio_news_board_create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'stockdio_news_board_options' );
        ?>
</link>

<div class="wrap">
  <h2>Stock Market News Settings</h2>
  <div class="stockdio_news_board_form">
    <form method="post" action="options.php">
      <?php
					// This prints out all hidden setting fields
					settings_fields( 'stockdio_news_board_option_group' );   
					do_settings_sections( 'stockdio-news-board-settings-config' );
					submit_button(); 
				?>
    </form>
  </div>
</div>
<?php
    }


    /**
     * Register and add settings
     */
    public function stockdio_news_board_page_init()
    {        
		$stockdio_news_board_options = get_option( 'stockdio_news_board_options' );
		$api_key = $stockdio_news_board_options['api_key'];
		//delete_option( 'stockdio_news_board_options'  );
		register_setting(
			'stockdio_news_board_option_group', // Option group
			'stockdio_news_board_options', // Option name
			array( $this, 'stockdio_news_board_sanitize' ) // stockdio_news_board_sanitize
		);
		
		if (empty($api_key)) {
			add_settings_section(
				'setting_section_id', // ID
				'', // Title
				array( $this, 'stockdio_news_board_print_section_empty_app_key_info' ), // Callback
				'stockdio-news-board-settings-config' // Page
			);  

			add_settings_field(
				'api_key', // ID
				'App-Key', // Title 
				array( $this, 'stockdio_news_board_api_key_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section        
			);  
		}
		else {
			add_settings_section(
				'setting_section_id', // ID
				'', // Title
				array( $this, 'stockdio_news_board_print_section_info' ), // Callback
				'stockdio-news-board-settings-config' // Page
			);  

			add_settings_field(
				'api_key', // ID
				'Api Key', // Title 
				array( $this, 'stockdio_news_board_api_key_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section        
			);  

			add_settings_field(
				'default_exchange', // ID
				'Exchange', // Title 
				array( $this, 'stockdio_news_board_exchange_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			);
			
			add_settings_field(
				'default_symbol', // ID
				'Symbol', // Title 
				array( $this, 'stockdio_news_board_symbol_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			);  			
			
			add_settings_field(
				'default_width', // ID
				'Width', // Title 
				array( $this, 'stockdio_news_board_width_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			);
			
			add_settings_field(
				'default_height', // ID
				'Height', // Title 
				array( $this, 'stockdio_news_board_height_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			);
			
			add_settings_field(
				'default_title', // ID
				'Title', // Title 
				array( $this, 'stockdio_news_board_title_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			);  
			
			add_settings_field(
				'default_culture', // ID
				'Culture', // Title 
				array( $this, 'stockdio_news_board_culture_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			);				
			
			add_settings_field(
				'default_includeImage', // ID
				'Include Image', // Title 
				array( $this, 'stockdio_news_board_includeImage_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			);

			add_settings_field(
				'default_imagewidth', // ID
				'Image Width', // Title 
				array( $this, 'stockdio_news_board_imageWidth_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			);
			
			add_settings_field(
				'default_imageHeight', // ID
				'Image Height', // Title 
				array( $this, 'stockdio_news_board_imageHeight_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			);		
			
			add_settings_field(
				'default_includeDescription', // ID
				'Include Description', // Title 
				array( $this, 'stockdio_news_board_includeDescription_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			);					
			
			add_settings_field(
				'default_maxDescriptionSize', // ID
				'Max Description Size', // Title 
				array( $this, 'stockdio_news_board_maxDescriptionSize_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			);		

			add_settings_field(
				'default_includeRelated', // ID
				'Include Related', // Title 
				array( $this, 'stockdio_news_board_includeRelated_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			);				

			add_settings_field(
				'default_maxItems', // ID
				'Maximum Items', // Title 
				array( $this, 'stockdio_news_board_maximumItems_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			);	

				
					
			add_settings_field(
				'default_motif', // ID
				'Motif', // Title 
				array( $this, 'stockdio_news_board_motif_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			);
			
			add_settings_field(
				'default_palette', // ID
				'Palette', // Title 
				array( $this, 'stockdio_news_board_palette_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			);		
			
			add_settings_field(
				'default_font', // ID
				'Font', // Title 
				array( $this, 'stockdio_news_board_font_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			);	
			
			add_settings_field(
				'default_filterSources', // ID
				'Filter Sources', // filterSources 
				array( $this, 'stockdio_news_board_filterSources_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			);	
			
			add_settings_field(
				'default_ignoreSources', // ID
				'Ignore Sources', // ignoreSources 
				array( $this, 'stockdio_news_board_ignoreSources_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			);	
			
			add_settings_field(
				'default_ignoreItems', // ID
				'Ignore Items', // ignoreItems 
				array( $this, 'stockdio_news_board_ignoreItems_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			);	
			
			add_settings_field(
				'booleanIniCheck', // ID
				'booleanIniCheck', // Title 
				array( $this, 'stockdio_news_board_booleanIniCheck_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			);	
			
			add_settings_field(
				'default_loadDataWhenVisible', // ID
				'Load data when visible', // Title 
				array( $this, 'stockdio_news_board_loadDataWhenVisible_callback' ), // Callback
				'stockdio-news-board-settings-config', // Page
				'setting_section_id' // Section           
			); 
			
		}
		
		$plugin_data = get_plugin_data( __FILE__ );
		$plugin_version = $plugin_data['Version'];
		$css_address=plugin_dir_url( __FILE__ )."assets/stockdio-wp.css";
		wp_register_script("customAdminCss",$css_address );
		wp_enqueue_style("customAdminCss", $css_address, array(), $plugin_version, false);
		
		$css_tinymce_button_address=plugin_dir_url( __FILE__ )."assets/stockdio-tinymce-button.css";
		wp_register_script("custom_tinymce_button_Css",$css_tinymce_button_address );
		wp_enqueue_style("custom_tinymce_button_Css", $css_tinymce_button_address, array(), $plugin_version, false);
		
		wp_enqueue_script('jquery');

		$version = stockdio_news_version;
		$js_sortable=plugin_dir_url( __FILE__ )."assets/Sortable.min.js";
		wp_register_script("StockdioSortableJS",$js_sortable, null, $version, false );
		wp_enqueue_script('StockdioSortableJS');

		$js_address=plugin_dir_url( __FILE__ )."assets/stockdio-wp.js";
		wp_register_script("customStockdioJs",$js_address, null, $version, false );
		wp_enqueue_script('customStockdioJs');
		
		$js_addressSearch=plugin_dir_url( __FILE__ )."assets/stockdio_search.js";
		$css_addressSearch=plugin_dir_url( __FILE__ ).'assets/stockdio_search.css?v='.$version;
		if (!function_exists( 'register_block_type')) {
			wp_register_script("customStockdioSearchJS",$js_addressSearch, array( ), $version, false );			
			wp_enqueue_style( 'customStockdioSearchStyles',$css_addressSearch , array() );

			$css_addressSearchOldVersion=plugin_dir_url( __FILE__ ).'assets/stockdio_search_old_version.css?v='.$version;
			wp_enqueue_style( 'customStockdioSearchStylesOldVersion',$css_addressSearchOldVersion , array() );
		}
		else{
			//wp_register_script("customStockdioSearchJS",$js_addressSearch, array( ), $version, false );	
			wp_enqueue_style( 'customStockdioSearchStyles',$css_addressSearch , array( 'wp-components' ) );	
			wp_register_script("customStockdioSearchJS",$js_addressSearch, array( 'wp-api', 'wp-i18n', 'wp-components', 'wp-element' ), $version, false );
		}
		wp_enqueue_script('customStockdioSearchJS');
    }

	public function stockdio_news_board_sanitize( $input )
    {
        $new_input = array();

        if( isset( $input['api_key'] ) )
            $new_input['api_key'] = esc_attr(sanitize_text_field($input['api_key'] ));
        if( isset( $input['default_symbol'] ) )
            $new_input['default_symbol'] = $input['default_symbol'] ;
		if( isset( $input['default_exchange'] ) )
            $new_input['default_exchange'] = esc_attr(sanitize_text_field($input['default_exchange'] ));
		
		if( isset( $input['default_loadDataWhenVisible'] ) )
            $new_input['default_loadDataWhenVisible'] = esc_attr(sanitize_text_field($input['default_loadDataWhenVisible'] ));
		
		if( isset( $input['default_culture'] ) )
            $new_input['default_culture'] = esc_attr(sanitize_text_field($input['default_culture'] ));
		if( isset( $input['default_width'] ) )
            $new_input['default_width'] = esc_attr(sanitize_text_field($input['default_width'] ));
		if( isset( $input['default_height'] ) )
            $new_input['default_height'] = esc_attr(sanitize_text_field($input['default_height'] ));
		if( isset( $input['default_font'] ) )
            $new_input['default_font'] = esc_attr(sanitize_text_field($input['default_font'] ));				
		if( isset( $input['default_motif'] ) )
            $new_input['default_motif'] = esc_attr(sanitize_text_field($input['default_motif'] ));
		if( isset( $input['default_palette'] ) )
            $new_input['default_palette'] = esc_attr(sanitize_text_field($input['default_palette'] ));
		
		if( isset( $input['default_title'] ) )
            $new_input['default_title'] = $input['default_title'] ;
		
		if( isset( $input['booleanIniCheck'] ) )
            $new_input['booleanIniCheck'] = esc_attr(sanitize_text_field($input['booleanIniCheck'] ));
		
		if( isset( $input['default_includeImage'] ) )
            $new_input['default_includeImage'] = esc_attr(sanitize_text_field($input['default_includeImage'] ));
		
		if( isset( $input['default_includeDescription'] ) )
            $new_input['default_includeDescription'] = esc_attr(sanitize_text_field($input['default_includeDescription'] ));
			
		if( isset( $input['default_imageWidth'] ) )
            $new_input['default_imageWidth'] = esc_attr(sanitize_text_field($input['default_imageWidth'] ));	
		if( isset( $input['default_imageHeight'] ) )
            $new_input['default_imageHeight'] = esc_attr(sanitize_text_field($input['default_imageHeight'] ));					
		
		if( isset( $input['default_maxItems'] ) )
            $new_input['default_maxItems'] = esc_attr(sanitize_text_field($input['default_maxItems'] ));	
		
		if( isset( $input['default_includeRelated'] ) )
            $new_input['default_includeRelated'] = esc_attr(sanitize_text_field($input['default_includeRelated'] ));	
		
		if( isset( $input['default_filterSources'] ) )
            $new_input['default_filterSources'] = esc_attr(sanitize_text_field($input['default_filterSources'] ));	
		
		if( isset( $input['default_ignoreSources'] ) )
            $new_input['default_ignoreSources'] = esc_attr(sanitize_text_field($input['default_ignoreSources'] ));	
		
		if( isset( $input['default_ignoreItems'] ) )
            $new_input['default_ignoreItems'] = esc_attr(sanitize_text_field($input['default_ignoreItems'] ));	
		
		if( isset( $input['default_maxDescriptionSize'] ) )
            $new_input['default_maxDescriptionSize'] = esc_attr(sanitize_text_field($input['default_maxDescriptionSize'] ));	

        return $new_input;
    }
	

	/**	
     * Print the Section text when app key is empty
     */
    public function stockdio_news_board_print_section_empty_app_key_info()
    {
        print '<br>
		Enter your app-key here. For more information go to <a href="http://www.stockdio.com/wordpress?wp=1" target="_blank">http://www.stockdio.com/wordpress</a>.
		</p>';
    }
	
    /** 
     * Print the Section text
     */
    public function stockdio_news_board_print_section_info()
    {
        print '<br/><i>For more information on this plugin, please visit <a href="http://www.stockdio.com/wordpress?wp=1" target="_blank">http://www.stockdio.com/wordpress</a>.</i>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
     public function stockdio_news_board_api_key_callback()
    {
        printf(
            '<input type="text" id="api_key" name="stockdio_news_board_options[api_key]" value="%s" />',
            isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key']) : ''
        );

    }

	public function stockdio_news_board_symbol_callback()
    {
    	if( empty( $this->options['default_symbol'] ) )
            $this->options['default_symbol'] = '' ;
        printf(
			'<label id="default_symbols_label" style="max-width: 1000px;display: block;overflow: hidden;overflow-wrap: break-word;font-weight:bold;margin-bottom: 10px">'.(isset( $this->options['default_symbol'] ) ? esc_attr( $this->options['default_symbol']) : '').'</label>
			<input style="display:none" type="text" id="default_symbol" name="stockdio_news_board_options[default_symbol]" value="%s" />		
			<a href="#" onclick="stockdio_open_search_symbol_modal(this)" value="Search">Click here to set the symbol</a>	
			<p class="description" id="tagline-description">The company\'s stock symbol (ex. <strong>AAPL</strong>). For a list of available market indices please visit <a href="http://www.stockdio.com/indices" target="_blank">http://www.stockdio.com/indices</a>.</p>
			',
            isset( $this->options['default_symbol'] ) ? esc_attr( $this->options['default_symbol']) : ''
        );
    }
	
	public function stockdio_news_board_title_callback()
    {
    	if( empty( $this->options['default_title'] ) )
            $this->options['default_title'] = '' ;
        printf(
            '<input type="text" id="default_title" name="stockdio_news_board_options[default_title]" value="%s" style="width:300px;" />		
			<p class="description" id="tagline-description">Allows to specify a title to the list, e.g. News (optional).</p>
			',
            isset( $this->options['default_title'] ) ? esc_attr( $this->options['default_title']) : ''
        );
    }
	
	public function stockdio_news_board_exchange_callback()
        {
		if( empty( $this->options['default_exchange'] ) )
            $this->options['default_exchange'] = '' ;
        printf(
			'<label id="default_exchange_label" style="font-weight:bold"></label>
			<select style="display:none"  name="stockdio_news_board_options[default_exchange]" id="default_exchange">		
			    <option value="" selected="selected">None</option> 
				<option value="Forex">Currencies Trading</option>
				<option value="Commodities">Commodities Trading</option>
				<option value="USA">USA Equities and ETFs</option>
				<option value="OTCMKTS" >USA OTC Markets</option>
				<option value="OTCBB" >USA OTC Bulletin Board</option>
				<option value="LSE" >London Stock Exchange</option>
				<option value="TSE" >Tokyo Stock Exchange</option>
				<option value="HKSE">Hong Kong Stock Exchange</option>
				<option value="SSE">Shanghai Stock Exchange</option>
				<option value="SZSE">Shenzhen Stock Exchange</option>
				<option value="FWB">Deutsche Börse Frankfurt</option>
				<option value="XETRA">XETRA</option>
				<option value="AEX">Euronext Amsterdam</option>
				<option value="BEX">Euronext Brussels</option>
				<option value="PEX">Euronext Paris</option>
				<option value="LEX">Euronext Lisbon</option>
				<option value="CHIX">Australia Chi-X</option>
				<option value="TSX">Toronto Stock Exchange</option>
				<option value="TSXV">TSX Venture Exchange</option>
				<option value="CSE">Canadian Securities Exchange</option>
				<option value="NEO">NEO Exchange</option>
				<option value="SIX">SIX Swiss Exchange</option>
				<option value="KRX">Korean Stock Exchange</option>
				<option value="Kosdaq">Kosdaq Stock Exchange</option>
				<option value="OMXS">NASDAQ OMX Stockholm</option>
				<option value="OMXC">NASDAQ OMX Copenhagen</option>
				<option value="OMXH">NASDAQ OMX Helsinky</option>
				<option value="OMXI">NASDAQ OMX Iceland</option>
				<option value="BSE">Bombay Stock Exchange</option>
				<option value="NSE">India NSE</option>
				<option value="BME">Bolsa de Madrid</option>
				<option value="JSE">Johannesburg Stock Exchange</option>	
				<option value="TWSE">Taiwan Stock Exchange</option>
				<option value="BIT">Borsa Italiana</option>
				<option value="MOEX">Moscow Exchange</option>
				<option value="Bovespa">Bovespa Sao Paulo Stock Exchange</option>
				<option value="NZX">New Zealand Exchange</option>	
				<option value="ISE">Irish Stock Exchange</option>
				<option value="SGX">Singapore Exchange</option>	
				<option value="TADAWUL">Tadawul Saudi Stock Exchange</option>	
				<option value="WSE">Warsaw Stock Exchange</option>	
				<option value="TASE">Tel Aviv Stock Exchange</option>			
				<option value="KLSE">Bursa Malaysia</option>	
				<option value="IDX">Indonesia Stock Exchange</option>		
				<option value="BMV">Bolsa Mexicana de Valores</option>
				<option value="OSE">Oslo Stock Exchange</option>		
				<option value="BCBA">Bolsa de Comercio de Buenos Aires</option>			
				<option value="SET">Stock Exchange of Thailand</option>		
				<option value="VSE">Vienna Stock Exchange</option>		
				<option value="BCS">Bolsa de Comercio de Santigo</option>		
				<option value="BIST">Borsa Istanbul</option>	
				<option value="OMXT">NASDAQ OMX Tallinn</option>	
				<option value="OMXR">NASDAQ OMX Riga</option>	
				<option value="OMXV">NASDAQ OMX Vilnius</option>	
				<option value="PSE">Philippine Stock Exchange</option>
				<option value="ADX">Abu Dhabi Securities Exchange</option>
				<option value="DFM">Dubai Financial Market</option>
				<option value="BVC">Bolsa de Valores de Colombia</option>
				<option value="NGSE">Nigerian Stock Exchange</option>				
				<option value="QSE">Qatar Stock Exchange</option>	
				<option value="TPEX">Taipei Exchange</option>	
				<option value="BVL">Bolsa de Valores de Lima</option>	
				<option value="EGX">The Egyptian Exchange</option>	
				<option value="ASE">Athens Stock Exchange</option>	
				<option value="NASE">Nairobi Securities Exchange</option>	
				<option value="HNX">Hanoi Stock Exchange</option>	
				<option value="HOSE">Hochiminh Stock Exchange</option>	
				<option value="BCPP">Prague Stock Exchange</option>					
				<option value="AMSE">Amman Stock Exchange</option>		
             </select>
			 <p class="description" id="tagline-description">The exchange market the symbol belongs to (optional). If not specified, NYSE/NASDAQ will be used by default. For a list of available exchanges please visit <a href="http://www.stockdio.com/exchanges?wp=1" target="_blank">http://www.stockdio.com/exchanges.</a></p>
				<script>document.getElementById("default_exchange").value = "'.$this->options['default_exchange'].'";
				jQuery("#default_exchange_label").text(jQuery("#default_exchange option:selected").text() + " (Exchange code: " + jQuery("#default_exchange").val() +  ")" );
				</script>
			 ',
    		'default_exchange'
    		);
    }

	public function stockdio_news_board_culture_callback()
        {
		if( empty( $this->options['default_culture'] ) )
            $this->options['default_culture'] = '' ;
        printf(
            '<select name="stockdio_news_board_options[default_culture]" id="default_culture">		
			    <option value="" selected="selected">None</option> 
				<option value="English-US">English-US</option> 
				<option value="English-UK">English-UK</option> 
				<option value="English-Canada">English-Canada</option> 
				<option value="English-Australia">English-Australia</option> 
				<option value="Spanish-Spain">Spanish-Spain</option> 
				<option value="Spanish-Mexico">Spanish-Mexico</option> 
				<option value="Spanish-LatinAmerica">Spanish-LatinAmerica</option> 
				<option value="French-France">French-France</option> 
				<option value="French-Canada">French-Canada</option> 
				<option value="French-Belgium">French-Belgium</option> 
				<option value="French-Switzerland">French-Switzerland</option> 
				<option value="Italian-Italy">Italian-Italy</option> 
				<option value="Italian-Switzerland">Italian-Switzerland</option> 
				<option value="German-Germany">German-Germany</option> 
				<option value="German-Switzerland">German-Switzerland</option> 
				<option value="Portuguese-Brasil">Portuguese-Brasil</option> 
				<option value="Portuguese-Portugal">Portuguese-Portugal</option> 				
             </select>
			 <p class="description" id="tagline-description">Allows to specify a combination of language and country settings, used to display texts and to format numbers and dates (e.g. Spanish-Spain). For a list of available culture combinations please visit <a href="http://www.stockdio.com/cultures?wp=1" target="_blank">http://www.stockdio.com/cultures.</p>
			 <script>document.getElementById("default_culture").value = "'.$this->options['default_culture'].'";</script>
			 ',
    		'default_culture'
    		);
    }
	
	
	public function stockdio_news_board_loadDataWhenVisible_callback()
    {	
        printf(
            '<input type="checkbox" id="default_loadDataWhenVisible" name="stockdio_news_board_options[default_loadDataWhenVisible]" value="%s" '. checked( isset($this->options['default_loadDataWhenVisible'])? $this->options['default_loadDataWhenVisible']: 0 ,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to fetch the data and display the visualization only when it becomes visible on the page, in order to avoid using calls (requests) when they are not needed. This is particularly useful when the visualization is not visible on the page by default, but it becomes visible as result of a user interaction (e.g. clicking on an element, etc.). It is also useful when using the same visualization multiple times on a page for different devices (e.g. using one instance of the plugin for mobile and another one for desktop). We recommend not using this by default but only on scenarios as those described above, as it may provide the end user with a small delay to display the visualization.</p>
			',
            isset( $this->options['default_loadDataWhenVisible'] ) && $this->options['default_loadDataWhenVisible'] != 0 ? $this->options['default_loadDataWhenVisible'] : 1
        );	
    }
	
	public function stockdio_news_board_width_callback()
    {
    	if( empty( $this->options['default_width'] ) )
            $this->options['default_width'] = '' ;
        printf(
            '<input type="text" id="default_width" name="stockdio_news_board_options[default_width]" value="%s" />
			<p class="description" id="tagline-description">Width of the list in either px or %% (default: 100%%).</p>
			',
            isset( $this->options['default_width'] ) ? esc_attr( $this->options['default_width']) : ''
        );
    }
	
    public function stockdio_news_board_height_callback()
    {
    	if( empty( $this->options['default_height'] ) )
            $this->options['default_height'] = '' ;
        printf(
            '<input type="text" id="default_height" name="stockdio_news_board_options[default_height]" value="%s" />
			<p class="description" id="tagline-description">Height of the list in pixels (default: none). If not specified, the list height will be calculated automatically.</p>
			',
            isset( $this->options['default_height'] ) ? esc_attr( $this->options['default_height']) : ''
        );
    }
	
	public function stockdio_news_board_booleanIniCheck_callback()
    {
		 printf('<input style="display:none" type="text" id="booleanIniCheck" name="stockdio_news_board_options[booleanIniCheck]" value="1" />');
		printf('<div class="stockdio_hidden_setting" style="display:none"></div><script>jQuery(function () {jQuery(".stockdio_hidden_setting").parent().parent().hide()});</script> ');
		$this->options['booleanIniCheck'] = "1";
    }
	
	public function stockdio_news_board_imageHeight_callback()
    {
    	if( empty( $this->options['default_imageHeight'] ) )
            $this->options['default_imageHeight'] = '' ;
        printf(
            '<input type="text" id="default_imageHeight" name="stockdio_news_board_options[default_imageHeight]" value="%s" />
			<p class="description" id="tagline-description">Height of the image in pixels (default: 100px)</p>
			',
            isset( $this->options['default_imageHeight'] ) ? esc_attr( $this->options['default_imageHeight']) : ''
        );
    }

	public function stockdio_news_board_imageWidth_callback()
    {
    	if( empty( $this->options['default_imageWidth'] ) )
            $this->options['default_imageWidth'] = '' ;
        printf(
            '<input type="text" id="default_imageWidth" name="stockdio_news_board_options[default_imageWidth]" value="%s" />
			<p class="description" id="tagline-description">Width of the image in pixels (default: 110px)</p>
			',
            isset( $this->options['default_imageWidth'] ) ? esc_attr( $this->options['default_imageWidth']) : ''
        );
    }	
	
	public function stockdio_news_board_includeImage_callback()
    {
		if( !isset( $this->options['booleanIniCheck'] ) ){
			 $this->options['default_includeImage']=1;
		}	
        printf(
            '<input type="checkbox" id="default_includeImage" name="stockdio_news_board_options[default_includeImage]" value="%s" '. checked( isset($this->options['default_includeImage'])?$this->options['default_includeImage']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include/exclude the news image, if available.</p>
			',
            isset( $this->options['default_includeImage'] ) ? $this->options['default_includeImage'] : 1
        );		
    }	

	public function stockdio_news_board_includeDescription_callback()
    {
		if( !isset( $this->options['booleanIniCheck'] ) ){
			 $this->options['default_includeDescription']=1;
		}	
        printf(
            '<input type="checkbox" id="default_includeDescription" name="stockdio_news_board_options[default_includeDescription]" value="%s" '. checked( isset($this->options['default_includeDescription'])?$this->options['default_includeDescription']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include/exclude the news description, if available.</p>
			',
            isset( $this->options['default_includeDescription'] ) ? $this->options['default_includeDescription'] : 1
        );
    }	

	public function stockdio_news_board_font_callback()
    {
    	if( empty( $this->options['default_font'] ) )
            $this->options['default_font'] = '' ;
        printf(
            '<input type="text" id="default_font" name="stockdio_news_board_options[default_font]" value="%s" />
			<p class="description" id="tagline-description">Allows to specify the font that will be used to render the chart. Multiple fonts may be specified separated by comma, e.g. Lato,Helvetica,Arial.</p>
			',
            isset( $this->options['default_font'] ) ? esc_attr( $this->options['default_font']) : ''
        );
    }
	
	public function stockdio_news_board_palette_callback()
        {
		if( empty( $this->options['default_palette'] ) )
            $this->options['default_palette'] = '' ;
        printf(
            '<select name="stockdio_news_board_options[default_palette]" id="default_palette">
			    <option value="" selected="selected">None</option>
				<option value="Aurora">Aurora</option>
				<option value="Block">Block</option>
				<option value="Brown-Sugar">Brown-Sugar</option>
				<option value="Eggplant">Eggplant</option>
				<option value="Excite-Bike">Excite-Bike</option>
				<option value="Financial-Light" >Financial-Light</option>
				<option value="Healthy">Healthy</option>
				<option value="High-Contrast">High-Contrast</option>
				<option value="Humanity">Humanity</option>
				<option value="Lilacs-in-Mist">Lilacs-in-Mist</option>
				<option value="Mesa">Mesa</option>
				<option value="Modern-Business">Modern-Business</option>
				<option value="Mint-Choc">Mint-Choc</option>
				<option value="Pastels">Pastels</option>
				<option value="Relief">Relief</option>
				<option value="Whitespace">Whitespace</option>			 
             </select>
			 <p class="description" id="tagline-description">Includes a set of consistent colors used for the visualization. Most palette colors can be overridden with specific colors for several features such as border, background, labels, etc. For more info, please visit <a href="http://www.stockdio.com/palettes?wp=1" target="_blank">http://www.stockdio.com/palettes</a> </p>
			 <script>document.getElementById("default_palette").value = "'.$this->options['default_palette'].'";</script>
			 ',
    		'default_palette'
    		);
    }

	public function stockdio_news_board_motif_callback()
        {
		if( empty( $this->options['default_motif'] ) )
            $this->options['default_motif'] = '' ;			
        printf(
            '<select name="stockdio_news_board_options[default_motif]" id="default_motif">			
				<option value="" selected="selected">None</option>
				<option value="Aurora">Aurora</option>
				<option value="Blinds">Blinds</option>
				<option value="Block">Block</option>
				<option value="Face">Face</option>
				<option value="Financial" >Financial</option>
				<option value="Glow">Glow</option>
				<option value="Healthy">Healthy</option>
				<option value="Hook">Hook</option>
				<option value="Lizard">Lizard</option>
				<option value="Material">Material</option>
				<option value="Relief">Relief</option>
				<option value="Semantic">Semantic</option>
				<option value="Topbar">Topbar</option>
				<option value="Tree">Tree</option>
				<option value="Whitespace">Whitespace</option>
				<option value="Wireframe">Wireframe</option>
             </select>
			 <p class="description" id="tagline-description">Design used to display the visualization with a specific aesthetics, including borders and styles, among other elements. For more info, please visit <a href="http://www.stockdio.com/motifs?wp=1" target="_blank">http://www.stockdio.com/motifs</a></p>
			 <script>document.getElementById("default_motif").value = "'.$this->options['default_motif'].'";</script>			 
			 ',
    		'default_motif'
    		);
    }
			

   	public function stockdio_news_board_maxDescriptionSize_callback()
    {
    	if( empty( $this->options['default_maxDescriptionSize'] ) )
            $this->options['default_maxDescriptionSize'] = '' ;
        printf(
            '<input type="text" id="default_maxDescriptionSize" name="stockdio_news_board_options[default_maxDescriptionSize]" value="%s" />
			<p class="description" id="tagline-description">Allows to set the maximum number of characters to display in the description, if available. By default, an estimate of the number of characters to display is calculated based on the image height and display width, but this may no be totally accurate, and a manual setting might be required.</p>
			',
            isset( $this->options['default_maxDescriptionSize'] ) ? esc_attr( $this->options['default_maxDescriptionSize']) : ''
        );
    }

   	public function stockdio_news_board_maximumItems_callback()
    {
    	if( empty( $this->options['default_maxItems'] ) )
            $this->options['default_maxItems'] = '' ;
        printf(
            '<input type="text" id="default_maxItems" name="stockdio_news_board_options[default_maxItems]" value="%s" />
			<p class="description" id="tagline-description">Allows to set the maximum number of news items to be displayed (optional, default: 10).</p>
			',
            isset( $this->options['default_maxItems'] ) ? esc_attr( $this->options['default_maxItems']) : ''
        );
    }	
	
	public function stockdio_news_board_includeRelated_callback()
    {
		if( !isset( $this->options['booleanIniCheck'] ) ){
			 $this->options['default_includeRelated']=1;
		}	
        printf(
            '<input type="checkbox" id="default_includeRelated" name="stockdio_news_board_options[default_includeRelated]" value="%s" '. checked( isset($this->options['default_includeRelated'])?$this->options['default_includeRelated']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include general market news in the list, not including the symbol\'s company, if available.</p>
			',
            isset( $this->options['default_includeRelated'] ) ? $this->options['default_includeRelated'] : 1
        );
    }	

	public function stockdio_news_board_filterSources_callback()
    {
    	if( empty( $this->options['default_filterSources'] ) )
            $this->options['default_filterSources'] = '' ;
        printf(
            '<input type="text" id="default_filterSources" name="stockdio_news_board_options[default_filterSources]" value="%s" />
			<p class="description" id="tagline-description">Allows to filter news from a list of sources, separated by colon (;). For example, setting the value to Seeking Alpha;Yahoo Finance will only display news that come from any of these sources.</p>
			',
            isset( $this->options['default_filterSources'] ) ? esc_attr( $this->options['default_filterSources']) : ''
        );
    }	

	public function stockdio_news_board_ignoreSources_callback()
    {
    	if( empty( $this->options['default_ignoreSources'] ) )
            $this->options['default_ignoreSources'] = '' ;
        printf(
            '<input type="text" id="default_ignoreSources" name="stockdio_news_board_options[default_ignoreSources]" value="%s" />
			<p class="description" id="tagline-description">Allows to ignore news coming from a list of sources, separated by colon (;). For example, setting the value to Seeking Alpha;Yahoo Finance will ignore news that come from any of these sources.</p>
			',
            isset( $this->options['default_ignoreSources'] ) ? esc_attr( $this->options['default_ignoreSources']) : ''
        );
    }	

	public function stockdio_news_board_ignoreItems_callback()
    {
    	if( empty( $this->options['default_ignoreItems'] ) )
            $this->options['default_ignoreItems'] = '' ;
        printf(
            '<input type="text" id="default_ignoreItems" name="stockdio_news_board_options[default_ignoreItems]" value="%s" />
			<p class="description" id="tagline-description">Allows to ignore news items that start or contain the text specified in a list, separated by colon (;). If the text in the list starts with *, the news item will be ignored if it contains the text anywhere inside its title; otherwise, the news item will be ignored if it starts with the specified text. For example, setting the value to canada;*share price, will ignore any news whose title starts with the word Canada or contains the phrase share price. It is not case sensitive.</p>
			',
            isset( $this->options['default_ignoreItems'] ) ? esc_attr( $this->options['default_ignoreItems']) : ''
        );
    }		
	
	
			
}

if( is_admin() )
    $stockdio_news_board_settings_page = new StockdioNewsSettingsPage();

add_action('wp_print_scripts', 'enqueueNewsAssets');

//Add the shortcode
add_shortcode( 'stock-market-news', 'stockdio_news_board_func' );

//widget
require_once( dirname(__FILE__) . "/stockdio_news_overview_widget.php"); 

/**
 * Block Initializer.
 */
if (function_exists( 'register_block_type')) {
	require_once(plugin_dir_path( __FILE__ ) . 'src/init.php');
}

remove_action( 'wp_head', 'stockdio_referrer_header_metadata', 0 );
add_action( 'wp_head', 'stockdio_referrer_header_metadata', 0 );
if ( ! function_exists( 'stockdio_referrer_header_metadata' ) ) {
	function stockdio_referrer_header_metadata() {	
	try {
		$useragent = isset($_SERVER['HTTP_USER_AGENT'])? $_SERVER['HTTP_USER_AGENT']: '';
		if (false || (!empty($useragent) && ( (strpos($useragent, "Safari") !== false && strpos($useragent, "Chrome") === false) ||strpos($useragent, "Opera Mini") !== false ))) {
	  ?>
		<meta name="referrer" content="no-referrer-when-downgrade">
	  <?php
	  
	}
		
	} catch (Exception $e) {
	}	
}
}

function enqueueNewsAssets()
{
	//$version = date_timestamp_get(date_create());
	$version = stockdio_news_version;
	$js_address=plugin_dir_url( __FILE__ )."assets/stockdio-wp.js";
	wp_register_script("customStockdioJs",$js_address, array(), $version, false );
	wp_enqueue_script('customStockdioJs');

}

//Execute the shortcode with $atts arguments
function stockdio_news_board_func( $atts ) {
	//make array of arguments and give these arguments to the shortcode
    $a = shortcode_atts( array(
        'symbol' => '',
		'title' => '',

		'stockexchange' => '',
		'exchange' => '',
		'culture' => '',

		'loaddatawhenvisible' => '',		
		'width'	=> '',
		'height'	=> '',			
		'font'	=> '',	
		'motif'	=> '',
		'palette'	=> '',
		'includeimage' => '',
		'includedescription' => '',
		'imageheight' => '',
		'imagewidth' => '',
		'maxdescriptionsize' => '',
		'maxitems' => '',		
		'filtersources' => '',
		'ignoresources' => '',
		'ignoreitems' => '',
		'bordercolor'	=> '',
		'backgroundcolor'	=> '',
		'captioncolor'	=> '',
		'titlecolor'	=> '',
		'newstitlecolor'	=> '',
		'newsdescriptioncolor'	=> '',
		'newsdatetimecolor'	=> '',
		'separatorcolor'	=> ''		
	), $atts );
	
    //create variables from arguments array
    extract($a);

	$width = esc_attr(sanitize_text_field($width));
	$height = esc_attr(sanitize_text_field($height));

	if (!empty($exchange) && empty($stockexchange)){
		$stockexchange = $exchange;
	}
	//assign settings values to $stockdio_news_board_options
  	$stockdio_news_board_options = get_option( 'stockdio_news_board_options' );
	
	 $api_key = '';
	if (isset($stockdio_news_board_options['api_key']))
		$api_key = $stockdio_news_board_options['api_key'];

	$extraSettings = '';
	$defaultSymbol = 'AAPL';
	stockdio_news_board_get_param_value('symbol', $symbol, 'string', $extraSettings, $stockdio_news_board_options, $defaultSymbol);
	if (strpos($extraSettings, $defaultSymbol) === false)
		stockdio_news_board_get_param_value('stockExchange', $stockexchange, 'string' , $extraSettings, $stockdio_news_board_options, '');
	stockdio_news_board_get_param_value('culture', $culture, 'string' , $extraSettings, $stockdio_news_board_options, '');
	stockdio_news_board_get_param_value('font', $font, 'string' , $extraSettings, $stockdio_news_board_options, '');
	stockdio_news_board_get_param_value('palette', $palette, 'string' , $extraSettings, $stockdio_news_board_options, '');
	stockdio_news_board_get_param_value('motif', $motif, 'string' , $extraSettings, $stockdio_news_board_options, '');
	stockdio_news_board_get_param_value('title', $title, 'string' , $extraSettings, $stockdio_news_board_options, '');	
	stockdio_news_board_get_param_value('includeImage', $includeimage, 'bool' , $extraSettings, $stockdio_news_board_options, '1');
	stockdio_news_board_get_param_value('imageHeight', $imageheight, 'string' , $extraSettings, $stockdio_news_board_options, '');
	stockdio_news_board_get_param_value('imageWidth', $imagewidth, 'string' , $extraSettings, $stockdio_news_board_options, '');
	stockdio_news_board_get_param_value('includeDescription', $includedescription, 'bool' , $extraSettings, $stockdio_news_board_options, '1');
	
	stockdio_news_board_get_param_value('includeRelated', $includerelated, 'bool' , $extraSettings, $stockdio_news_board_options, '1');
	
	stockdio_news_board_get_param_value('filterSources', $filtersources, 'string' , $extraSettings, $stockdio_news_board_options, '');
	stockdio_news_board_get_param_value('ignoreSources', $ignoresources, 'string' , $extraSettings, $stockdio_news_board_options, '');
	stockdio_news_board_get_param_value('ignoreItems', $ignoreitems, 'string' , $extraSettings, $stockdio_news_board_options, '');
	
	
	stockdio_news_board_get_param_value('maxDescriptionSize', $maxdescriptionsize, 'string' , $extraSettings, $stockdio_news_board_options, '');
	stockdio_news_board_get_param_value('maxItems', $maxitems, 'string' , $extraSettings, $stockdio_news_board_options, '');

	
	//colors:
	stockdio_news_board_get_param_value('borderColor', $bordercolor, 'color' , $extraSettings, $stockdio_news_board_options, '');
	stockdio_news_board_get_param_value('backgroundColor', $backgroundcolor, 'color' , $extraSettings, $stockdio_news_board_options, '');
	stockdio_news_board_get_param_value('captionColor', $captioncolor, 'color' , $extraSettings, $stockdio_news_board_options, '');
	stockdio_news_board_get_param_value('titleColor', $titlecolor, 'color' , $extraSettings, $stockdio_news_board_options, '');

	stockdio_news_board_get_param_value('newstitleColor', $newstitlecolor, 'color' , $extraSettings, $stockdio_news_board_options, '');
	stockdio_news_board_get_param_value('newsDescriptionColor', $newsdescriptioncolor, 'color' , $extraSettings, $stockdio_news_board_options, '');
	stockdio_news_board_get_param_value('newsDateTimeColor', $newsdatetimecolor, 'color' , $extraSettings, $stockdio_news_board_options, '');
	stockdio_news_board_get_param_value('separatorColor', $separatorcolor, 'color' , $extraSettings, $stockdio_news_board_options, '');
		
	$showChart = true;
	
	//$default_includeChart ='';
	//$initCheck = $stockdio_news_board_options['booleanIniCheck'] == '1';
	//if (isset($stockdio_news_board_options['default_includeImage']))
	//	$default_includeImage = $stockdio_news_board_options['default_includeImage'];	
	//if (empty($includeimage))
	//	$includeimage=$default_includeImage;
	
	$link = 'https://api.stockdio.com/visualization/financial/charts/v1/news';
	
	$default_width = '';
	if (isset($stockdio_news_board_options['default_width']))
		$default_width = $stockdio_news_board_options['default_width'];
	
	$default_height = '';
	if (isset($stockdio_news_board_options['default_height']))
		$default_height = $stockdio_news_board_options['default_height'];
	
	if (empty($width))
		$width =$default_width;
	if (empty($width))
		$width ='100%';
	if (strpos($width, 'px') !== FALSE && strpos($width, '%') !== FALSE) 
		$width =$width.'px';
	$extraSettings .= '&width='.urlencode('100%');	
		
	$iframeHeight = '';
	if (empty($height))
		$height =$default_height;
	if (strpos($height, 'px') !== FALSE && strpos($height, '%') !== FALSE) 
		$height =$height.'px';
	if (!empty($height)){
		$extraSettings .= '&height='.urlencode($height);
		$iframeHeight=' height="'.$height.'" ';
	}

	$iframe_id= str_replace("{","",strtolower(getSNGUID()));
	$iframe_id= str_replace("}","",$iframe_id);
	$extraSettings .= '&onload='.$iframe_id;
	
	  //make main html output  	
	$default_loadDataWhenVisible = "true";
	if (!array_key_exists('default_loadDataWhenVisible',$stockdio_news_board_options) || (array_key_exists('default_loadDataWhenVisible',$stockdio_news_board_options) && $stockdio_news_board_options['default_loadDataWhenVisible'] == 0) )
			$default_loadDataWhenVisible = "false";
	  
	 if (empty($loaddatawhenvisible))
		$loaddatawhenvisible=$default_loadDataWhenVisible;
	
	$src = 'src';
	if ($loaddatawhenvisible == "1" || $loaddatawhenvisible == "true") 
		$src = 'iframesrc';
	
	$output = '<iframe referrerpolicy="no-referrer-when-downgrade" id="'.$iframe_id.'" frameBorder="0" class="stockdio_news" scrolling="no" width="'.$width.'" '.$iframeHeight.' '.$src.'="'.$link.'?app-key='.$api_key.'&wp=1&addVolume=false'.$extraSettings.'"></iframe>';  		
  	//return completed string
  	return $output;

}	

	function getSNGUID(){
		if (function_exists('com_create_guid')){
			return com_create_guid();
		}
		else {
			//mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			$uuid = chr(123)// "{"
				.substr($charid, 0, 8).$hyphen
				.substr($charid, 8, 4).$hyphen
				.substr($charid,12, 4).$hyphen
				.substr($charid,16, 4).$hyphen
				.substr($charid,20,12)
				.chr(125);// "}"
			return $uuid;
		}
	}

	function stockdio_news_board_get_param_value($varname, $var, $type, &$extraSettings, $stockdio_news_board_options, $defaultvalue){

		$default ='';
		$defaultName ='default_'.$varname;
		$initCheck = array_key_exists('booleanIniCheck',$stockdio_news_board_options)? $stockdio_news_board_options['booleanIniCheck'] == '1' : false;
		if ($varname=="stockExchange")
			$defaultName='default_exchange';			
		if (isset($stockdio_news_board_options[$defaultName]))
			$default = $stockdio_news_board_options[$defaultName];
		if ($type == "string" || $type == "color"){
			if (empty($var))
				$var=$default;
			if (empty($var) && $defaultvalue!="")
				$var=$defaultvalue;
			if (empty($var) && $varname=="palette")
				$var='Financial-Light';				
			if (empty($var) && $varname=="motif")
				$var='Financial';					
			if (!empty($var))	{
				if ($varname=='logoMaxWidth' || $varname=='logoMaxHeight')
					$var =str_replace('px','',$var);
				$var = urlencode($var);
				if ($type == "color"){
					$var =str_replace('#','',$var);	
					$var =str_replace('%23','',$var);	
					$var =str_replace(' ','',$var);	
					$var =str_replace('+','',$var);	
				}
				$extraSettings .= '&'.$varname.'='.$var;			
			}
		}
		else {
			if ($type == "bool"){
				if (empty($var))
					$var=$default;

				if (!$initCheck && empty($var) && $defaultvalue!="")
					$var=$defaultvalue;
					
				if ($var=="1"||$var=="true") 
					$extraSettings .= '&'.$varname.'=true';		
				else
					$extraSettings .= '&'.$varname.'=false';						
			}
		}
	}

    /** 
     * ShortCode editor button
     */
	function stockdio_news_board_register_button( $buttons ) {
		if (!array_key_exists("stockdio_charts_button", $buttons)) {
			array_push( $buttons, "|", "stockdio_charts_button" );	   
		}
		return $buttons;
	}	 
	function stockdio_news_board_add_plugin( $plugin_array ) {
		if (!array_key_exists("stockdio_charts_button", $plugin_array)) {
			$plugin_data = get_plugin_data( __FILE__ );
			$plugin_version = $plugin_data['Version'];
			$plugin_array['stockdio_charts_button'] = plugin_dir_url( __FILE__ ).'assets/stockdio-charts-shortcode.js?ver='.$plugin_version;
			add_filter( 'mce_buttons', 'stockdio_news_board_register_button' );	  			
		}
	   return $plugin_array;
	}	
	function stockdio_news_board_charts_button() {
	   if ( current_user_can('edit_posts') && current_user_can('edit_pages') ) {
		  add_filter( 'mce_external_plugins', 'stockdio_news_board_add_plugin' );		  		  
	   }
	}	
    /**
     * Intialize global variables
     */
    function stockdio_news_board_stockdio_js(){ 
	$stockdio_news_board_options = get_option( 'stockdio_news_board_options' );
	$stockdio_news_root_folder = plugins_url('assets', __FILE__ );
	?>
		<script>
			var stockdio_news_root_folder = <?php echo json_encode( $stockdio_news_root_folder ); ?>;	
			var stockdio_news_board_settings = <?php echo json_encode( $stockdio_news_board_options ); ?>;	
			jQuery(function () {				
				setDefaultValue = function(o,n,v){
					if (typeof o == 'undefined' || o[n]==null || o[n]=='')
						o[n] = v;
				}				
				setDefaultValue(stockdio_news_board_settings,"default_height", '');
				setDefaultValue(stockdio_news_board_settings, "default_width", '');
				setDefaultValue(stockdio_news_board_settings, "default_includeImage", true);
				setDefaultValue(stockdio_news_board_settings, "default_includeDescription", true);
				
				if (pagenow == "settings_page_stockdio-news-board-settings-config") {
					jQuery("#a_show_appkey_input").click(function(e){ 
						e.preventDefault();
						jQuery(".stockdio_register_mode").hide();
						jQuery(".stockdio_news_board_form").show();
					});
					jQuery("#a_show_register_form").click(function(e){ 
						e.preventDefault();
						jQuery(".stockdio_register_mode").show();
						jQuery(".stockdio_news_board_form").hide();
					});				
					var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
					var eventer = window[eventMethod];
					var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

									
					if (jQuery("#api_key").val()== ""){					
						if (typeof stockdio_historical_charts_settings != 'undefined' && typeof stockdio_historical_charts_settings.api_key != 'undefined' && stockdio_historical_charts_settings.api_key != "") {
							jQuery("#api_key").val(stockdio_historical_charts_settings.api_key);
							jQuery("#a_show_appkey_input").click();
						}
						else{
							if (typeof stockdio_quotes_board_settings  != 'undefined' && typeof stockdio_quotes_board_settings.api_key != 'undefined' && stockdio_quotes_board_settings.api_key != "") {
								jQuery("#api_key").val(stockdio_quotes_board_settings.api_key);
								jQuery("#a_show_appkey_input").click();
							}
							else{
								if (typeof stockdio_ticker_settings != 'undefined' && typeof stockdio_ticker_settings.api_key != 'undefined' && stockdio_ticker_settings.api_key != "") {
									jQuery("#api_key").val(stockdio_ticker_settings.api_key);
									jQuery("#a_show_appkey_input").click();
								}
								else{
									if (typeof stockdio_market_overview_settings != 'undefined' && typeof stockdio_market_overview_settings.api_key != 'undefined' && stockdio_market_overview_settings.api_key != "") {
										jQuery("#api_key").val(stockdio_market_overview_settings.api_key);
										jQuery("#a_show_appkey_input").click();
									}
									else{
										
									}									
								}								
							}
						}
						if (jQuery("#default_exchange").length <= 0 && jQuery("#api_key").val()!= "" && jQuery("#api_key").val().length == 32) {
							jQuery("#submit").click();
						}
					}
				}
				
			});		
			var stockdio_marker_news=1;
			
		</script><?php
	}
	
	//register_activation_hook(__FILE__, 'stockdio_news_board_my_plugin_activate');
	//add_action('admin_init', 'stockdio_news_board_my_plugin_redirect');
	 
	function stockdio_news_board_my_plugin_activate() {
		add_option('stockdio_news_board_my_plugin_do_activation_redirect', true);
	}
	 
	function stockdio_news_board_my_plugin_redirect() {
		if (get_option('stockdio_news_board_my_plugin_do_activation_redirect', false)) {
			delete_option('stockdio_news_board_my_plugin_do_activation_redirect');
			if(!isset($_GET['activate-multi']))
			{
				wp_redirect("options-general.php?page=stockdio-news-board-settings-config");
			}
		}
	}
?>