<?php

class Widget_Stockdio_Market_News extends WP_Widget {
 
  public function __construct() {
      $widget_ops = array('classname' => 
		'Widget_Stockdio_Market_News', 
		'description' => 'A WordPress plugin for displaying a list of Stock Market News, available in several languages.' );
      parent::__construct('Widget_Stockdio_Market_News', 'Stock Market News', $widget_ops);
  }
    
  function widget($args, $instance) {
    // PART 1: Extracting the arguments + getting the values
    extract($args, EXTR_SKIP);
    $title = !isset($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
	$exchange= !isset($instance['exchange']) ? '' : $instance['exchange'];
    $symbol = !isset($instance['symbol']) ? '' : $instance['symbol'];	
	$maxdescriptionsize= !isset($instance['maxdescriptionsize']) ? '' : $instance['maxdescriptionsize'];	
	$maxitems= !isset($instance['maxitems']) ? '' : $instance['maxitems'];	
	
	$width = !isset($instance['width']) ? '' : $instance['width'];
	$height = !isset($instance['height']) ? '' : $instance['height'];	
	//$includeimage= (isset($instance['includeimage']) AND $instance['includeimage'] == 1) ? "true" : "false";	
	//$includedescription= (isset($instance['includedescription']) AND $instance['includedescription'] == 1) ? "true" : "false";
	//$includerelated= (isset($instance['includerelated']) AND $instance['includerelated'] == 1) ? "true" : "false";
	$includeimage= ((isset($instance['includeimage']) AND $instance['includeimage'] == 1) OR ($instance['includeimage'] != "0"  )) ? "true" : "false";	
	$includedescription= ((isset($instance['includedescription']) AND $instance['includedescription'] == 1) OR ($instance['includedescription'] != "0" )) ? "true" : "false";
	$includerelated= ((isset($instance['includerelated']) AND $instance['includerelated'] == 1) OR ($instance['includerelated'] != "0"  )) ? "true" : "false";	
   
   $imagewidth= !isset($instance['imagewidth']) ? '' : $instance['imagewidth'];	
   $imageheight= !isset($instance['imageheight']) ? '' : $instance['imageheight'];	
   $culture= !isset($instance['culture']) ? '' : $instance['culture'];	
    // Before widget code, if any
    echo (isset($before_widget)?$before_widget:'');

	//echo $output;	
	echo stockdio_news_board_func( array( 
									'title' => $title , 
									'stockexchange' => $exchange, 
									'symbol' => $symbol,
									'maxdescriptionsize' => $maxdescriptionsize,
									'maxitems' => $maxitems,
									'width' => $width, 
									'includeimage' => $includeimage,
									'height' => $height,
									'imagewidth' => $imagewidth,
									'imageheight' => $imageheight,
									'culture' => $culture,
									'includedescription' => $includedescription,
									'includerelated' => $includerelated
								));
   
    // After widget code, if any  
    echo (isset($after_widget)?$after_widget:'');
  }

  public function form( $instance ) {
   
	$stockdio_market_news_options = get_option( 'stockdio_news_board_options' );
     // PART 1: Extract the data from the instance variable
     $instance = wp_parse_args( (array) $instance, array( 
		 'title' => array_key_exists('default_title',$stockdio_market_news_options)?$stockdio_market_news_options ['default_title']:'',
		 'exchange' => array_key_exists('default_exchange',$stockdio_market_news_options)?$stockdio_market_news_options ['default_exchange']:'',
		 'symbol' => array_key_exists('default_symbol',$stockdio_market_news_options)?$stockdio_market_news_options ['default_symbol']:'',
		 'maxdescriptionsize' => array_key_exists('default_maxdescriptionsize',$stockdio_market_news_options)?$stockdio_market_news_options ['default_maxdescriptionsize']:'',
		 'maxitems' => array_key_exists('default_maxitems',$stockdio_market_news_options)?$stockdio_market_news_options ['default_maxitems']:'',
		 'width' => array_key_exists('default_width',$stockdio_market_news_options)?$stockdio_market_news_options ['default_width']:'',
		 'height' => array_key_exists('default_height',$stockdio_market_news_options)?$stockdio_market_news_options ['default_height']:'',
		 'includeimage' => array_key_exists('default_includeimage',$stockdio_market_news_options)?$stockdio_market_news_options ['default_includeimage']:'',
		 'includedescription' => array_key_exists('default_includedescription',$stockdio_market_news_options)?$stockdio_market_news_options ['default_includedescription']:'',
		 'includerelated' => array_key_exists('default_includerelated',$stockdio_market_news_options)?$stockdio_market_news_options ['default_includerelated']:'',
		 'imagewidth' => array_key_exists('default_imagewidth',$stockdio_market_news_options)?$stockdio_market_news_options ['default_imagewidth']:'',
		 'imageheight' => array_key_exists('default_imageheight',$stockdio_market_news_options)?$stockdio_market_news_options ['default_imageheight']:'',
		 'culture' => array_key_exists('default_culture',$stockdio_market_news_options)?$stockdio_market_news_options ['default_culture']:''
		 
	 ) );
	 
	 extract($instance);
   
     // PART 2-3: Display the fields
     ?>
	 
	<p>
		<label for="<?php echo $this->get_field_id('exchange'); ?>">Exchange:</label>
		 <select name="<?php echo $this->get_field_name('exchange'); ?>" id="<?php echo $this->get_field_id('exchange'); ?>">		
			<option value="" <?php if ( $exchange == '' ) echo 'selected="selected"'; ?>>None</option> 
			<option value="Forex" <?php if ( $exchange == 'Forex' ) echo 'selected="selected"'; ?>>Currencies Trading</option>
			<option value="Commodities" <?php if ( $exchange == 'Commodities' ) echo 'selected="selected"'; ?>>Commodities Trading</option>
			<option value="USA" <?php if ( $exchange == 'USA' ) echo 'selected="selected"'; ?>>USA Equities and ETFs</option>
			<option value="OTCMKTS" <?php if ( $exchange == 'OTCMKTS' ) echo 'selected="selected"'; ?>>USA OTC Markets</option>
			<option value="OTCBB" <?php if ( $exchange == 'OTCBB' ) echo 'selected="selected"'; ?>>USA OTC Bulletin Board</option>
			<option value="LSE" <?php if ( $exchange == 'LSE' ) echo 'selected="selected"'; ?>>London Stock Exchange</option>
			<option value="TSE" <?php if ( $exchange == 'TSE' ) echo 'selected="selected"'; ?>>Tokyo Stock Exchange</option>
			<option value="HKSE" <?php if ( $exchange == 'HKSE' ) echo 'selected="selected"'; ?>>Hong Kong Stock Exchange</option>
			<option value="SSE" <?php if ( $exchange == 'SSE' ) echo 'selected="selected"'; ?>>Shanghai Stock Exchange</option>
			<option value="SZSE" <?php if ( $exchange == 'SZSE' ) echo 'selected="selected"'; ?>>Shenzhen Stock Exchange</option>
			<option value="FWB" <?php if ( $exchange == 'FWB' ) echo 'selected="selected"'; ?>>Deutsche Börse Frankfurt</option>
			<option value="XETRA" <?php if ( $exchange == 'XETRA' ) echo 'selected="selected"'; ?>>XETRA</option>
			<option value="AEX" <?php if ( $exchange == 'AEX' ) echo 'selected="selected"'; ?>>Euronext Amsterdam</option>
			<option value="BEX" <?php if ( $exchange == 'BEX' ) echo 'selected="selected"'; ?>>Euronext Brussels</option>
			<option value="PEX" <?php if ( $exchange == 'PEX' ) echo 'selected="selected"'; ?>>Euronext Paris</option>
			<option value="LEX" <?php if ( $exchange == 'LEX' ) echo 'selected="selected"'; ?>>Euronext Lisbon</option>
			<option value="CHIX" <?php if ( $exchange == 'CHIX' ) echo 'selected="selected"'; ?>>Australia Chi-X</option>
			<option value="TSX" <?php if ( $exchange == 'TSX' ) echo 'selected="selected"'; ?>>Toronto Stock Exchange</option>
			<option value="TSXV" <?php if ( $exchange == 'TSXV' ) echo 'selected="selected"'; ?>>TSX Venture Exchange</option>
			<option value="CSE" <?php if ( $exchange == 'CSE' ) echo 'selected="selected"'; ?>>Canadian Securities Exchange</option>
			<option value="SIX" <?php if ( $exchange == 'SIX' ) echo 'selected="selected"'; ?>>SIX Swiss Exchange</option>
			<option value="KRX" <?php if ( $exchange == 'KRX' ) echo 'selected="selected"'; ?>>Korean Stock Exchange</option>
			<option value="Kosdaq" <?php if ( $exchange == 'Kosdaq' ) echo 'selected="selected"'; ?>>Kosdaq Stock Exchange</option>
			<option value="OMXS" <?php if ( $exchange == 'OMXS' ) echo 'selected="selected"'; ?>>NASDAQ OMX Stockholm</option>
			<option value="OMXC" <?php if ( $exchange == 'OMXC' ) echo 'selected="selected"'; ?>>NASDAQ OMX Copenhagen</option>
			<option value="OMXH" <?php if ( $exchange == 'OMXH' ) echo 'selected="selected"'; ?>>NASDAQ OMX Helsinky</option>
			<option value="OMXI" <?php if ( $exchange == 'OMXI' ) echo 'selected="selected"'; ?>>NASDAQ OMX Iceland</option>
			<option value="BSE" <?php if ( $exchange == 'BSE' ) echo 'selected="selected"'; ?>>Bombay Stock Exchange</option>
			<option value="NSE" <?php if ( $exchange == 'NSE' ) echo 'selected="selected"'; ?>>India NSE</option>
			<option value="BME" <?php if ( $exchange == 'BME' ) echo 'selected="selected"'; ?>>Bolsa de Madrid</option>
			<option value="JSE" <?php if ( $exchange == 'JSE' ) echo 'selected="selected"'; ?>>Johannesburg Stock Exchange</option>	
			<option value="TWSE" <?php if ( $exchange == 'TWSE' ) echo 'selected="selected"'; ?>>Taiwan Stock Exchange</option>
			<option value="BIT" <?php if ( $exchange == 'BIT' ) echo 'selected="selected"'; ?>>Borsa Italiana</option>
			<option value="MOEX" <?php if ( $exchange == 'MOEX' ) echo 'selected="selected"'; ?>>Moscow Exchange</option>
			<option value="Bovespa" <?php if ( $exchange == 'Bovespa' ) echo 'selected="selected"'; ?>>Bovespa Sao Paulo Stock Exchange</option>
			<option value="NZX" <?php if ( $exchange == 'NZX' ) echo 'selected="selected"'; ?>>New Zealand Exchange</option>	
			<option value="ISE" <?php if ( $exchange == 'ISE' ) echo 'selected="selected"'; ?>>Irish Stock Exchange</option>
			<option value="SGX" <?php if ( $exchange == 'SGX' ) echo 'selected="selected"'; ?>>Singapore Exchange</option>	
			<option value="TADAWUL" <?php if ( $exchange == 'TADAWUL' ) echo 'selected="selected"'; ?>>Tadawul Saudi Stock Exchange</option>
			<option value="TASE" <?php if ( $exchange == 'TASE' ) echo 'selected="selected"'; ?>>Tel Aviv Stock Exchange</option>			
			<option value="KLSE" <?php if ( $exchange == 'KLSE' ) echo 'selected="selected"'; ?>>Bursa Malaysia</option>	
			<option value="IDX" <?php if ( $exchange == 'IDX' ) echo 'selected="selected"'; ?>>Indonesia Stock Exchange</option>		
			<option value="BMV" <?php if ( $exchange == 'BMV' ) echo 'selected="selected"'; ?>>Bolsa Mexicana de Valores</option>
			<option value="OSE" <?php if ( $exchange == 'OSE' ) echo 'selected="selected"'; ?>>Oslo Stock Exchange</option>		
			<option value="BCBA" <?php if ( $exchange == 'BCBA' ) echo 'selected="selected"'; ?>>Bolsa de Comercio de Buenos Aires</option>			
			<option value="SET" <?php if ( $exchange == 'SET' ) echo 'selected="selected"'; ?>>Stock Exchange of Thailand</option>		
			<option value="VSE" <?php if ( $exchange == 'VSE' ) echo 'selected="selected"'; ?>>Vienna Stock Exchange</option>		
			<option value="BCS" <?php if ( $exchange == 'BCS' ) echo 'selected="selected"'; ?>>Bolsa de Comercio de Santigo</option>		
			<option value="BIST" <?php if ( $exchange == 'BIST' ) echo 'selected="selected"'; ?>>Borsa Istanbul</option>	
			<option value="OMXT" <?php if ( $exchange == 'OMXT' ) echo 'selected="selected"'; ?>>NASDAQ OMX Tallinn</option>	
			<option value="OMXR" <?php if ( $exchange == 'OMXR' ) echo 'selected="selected"'; ?>>NASDAQ OMX Riga</option>	
			<option value="OMXV" <?php if ( $exchange == 'OMXV' ) echo 'selected="selected"'; ?>>NASDAQ OMX Vilnius</option>	
			<option value="PSE" <?php if ( $exchange == 'PSE' ) echo 'selected="selected"'; ?>>Philippine Stock Exchange</option>
			<option value="ADX" <?php if ( $exchange == 'ADX' ) echo 'selected="selected"'; ?>>Abu Dhabi Securities Exchange</option>
			<option value="DFM" <?php if ( $exchange == 'DFM' ) echo 'selected="selected"'; ?>>Dubai Financial Market</option>
			<option value="BVC" <?php if ( $exchange == 'BVC' ) echo 'selected="selected"'; ?>>Bolsa de Valores de Colombia</option>
			<option value="NGSE" <?php if ( $exchange == 'NGSE' ) echo 'selected="selected"'; ?>>Nigerian Stock Exchange</option>				
			<option value="QSE" <?php if ( $exchange == 'QSE' ) echo 'selected="selected"'; ?>>Qatar Stock Exchange</option>	
			<option value="TPEX" <?php if ( $exchange == 'TPEX' ) echo 'selected="selected"'; ?>>Taipei Exchange</option>	
			<option value="BVL" <?php if ( $exchange == 'BVL' ) echo 'selected="selected"'; ?>>Bolsa de Valores de Lima</option>	
			<option value="EGX" <?php if ( $exchange == 'EGX' ) echo 'selected="selected"'; ?>>The Egyptian Exchange</option>	
			<option value="ASE" <?php if ( $exchange == 'ASE' ) echo 'selected="selected"'; ?>>Athens Stock Exchange</option>	
			<option value="NASE" <?php if ( $exchange == 'NASE' ) echo 'selected="selected"'; ?>>Nairobi Securities Exchange</option>	
			<option value="HNX" <?php if ( $exchange == 'HNX' ) echo 'selected="selected"'; ?>>Hanoi Stock Exchange</option>	
			<option value="HOSE" <?php if ( $exchange == 'HOSE' ) echo 'selected="selected"'; ?>>Hochiminh Stock Exchange</option>	
			<option value="BCPP" <?php if ( $exchange == 'BCPP' ) echo 'selected="selected"'; ?>>Prague Stock Exchange</option>					
			<option value="AMSE" <?php if ( $exchange == 'AMSE' ) echo 'selected="selected"'; ?>>Amman Stock Exchange</option>		
		 </select>
	</p>
	  
	 <!-- PART 3: Widget symbol field START -->
     <p>
      <label for="<?php echo $this->get_field_id('symbol'); ?>">Symbol: </label>
        <input class="widefat" id="<?php echo $this->get_field_id('symbol'); ?>" 
               name="<?php echo $this->get_field_name('symbol'); ?>" type="text" 
               value="<?php echo esc_attr($symbol); ?>" />
      
      </p>
      <!-- Widget symbol field END -->
	  
	  
	 <!-- PART 3: Widget Width field START -->
     <p>
      <label for="<?php echo $this->get_field_id('width'); ?>">Width: </label>
        <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" 
               name="<?php echo $this->get_field_name('width'); ?>" type="text" 
               value="<?php echo esc_attr($width); ?>" />      
      </p>
      <!-- Widget Width field END -->
	  
	<!-- PART 3: Widget Height field START -->
     <p>
      <label for="<?php echo $this->get_field_id('height'); ?>">Height: </label>
        <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" 
               name="<?php echo $this->get_field_name('height'); ?>" type="text" 
               value="<?php echo esc_attr($height); ?>" />      
      </p>
      <!-- Widget Height field END -->
	  
     <!-- PART 2: Widget Title field START -->
     <p>
      <label for="<?php echo $this->get_field_id('title'); ?>">Title: </label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
               name="<?php echo $this->get_field_name('title'); ?>" type="text" 
               value="<?php echo esc_attr($title); ?>" />
      
      </p>
      <!-- Widget Title field END -->
   
	<!-- PART 3: Widget Culture Width field START -->
	<p>
		<label for="<?php echo $this->get_field_id('culture'); ?>">Culture:</label>
		 <select name="<?php echo $this->get_field_name('culture'); ?>" id="<?php echo $this->get_field_id('culture'); ?>">		
			<option value="" <?php if ( $culture== '' ) echo 'selected="selected"'; ?>>None</option> 		
			<option value="English-US" <?php if ( $culture== 'English-US' ) echo 'selected="selected"'; ?>>English-US</option> 
			<option value="English-UK" <?php if ( $culture== 'English-UK' ) echo 'selected="selected"'; ?>>English-UK</option> 
			<option value="English-Canada" <?php if ( $culture== 'English-Canada"' ) echo 'selected="selected"'; ?>>English-Canada</option> 
			<option value="English-Australia" <?php if ( $culture == 'English-Australia' ) echo 'selected="selected"'; ?>>English-Australia</option> 
			<option value="Spanish-Spain" <?php if ( $culture == 'Spanish-Spain' ) echo 'selected="selected"'; ?>>Spanish-Spain</option> 
			<option value="Spanish-Mexico" <?php if ( $culture == 'Spanish-Mexico' ) echo 'selected="selected"'; ?>>Spanish-Mexico</option> 
			<option value="Spanish-LatinAmerica" <?php if ( $culture == 'Spanish-LatinAmerica' ) echo 'selected="selected"'; ?>>Spanish-LatinAmerica</option> 
			<option value="French-France" <?php if ( $culture == 'French-France' ) echo 'selected="selected"'; ?>>French-France</option> 
			<option value="French-Canada" <?php if ( $culture == 'French-Canada' ) echo 'selected="selected"'; ?>>French-Canada</option> 
			<option value="French-Belgium" <?php if ( $culture == 'French-Belgium' ) echo 'selected="selected"'; ?>>French-Belgium</option> 
			<option value="French-Switzerland" <?php if ( $culture == 'French-Switzerland' ) echo 'selected="selected"'; ?>>French-Switzerland</option> 
			<option value="Italian-Italy" <?php if ( $culture == 'Italian-Italy' ) echo 'selected="selected"'; ?>>Italian-Italy</option> 
			<option value="Italian-Switzerland" <?php if ( $culture == 'Italian-Switzerland' ) echo 'selected="selected"'; ?>>Italian-Switzerland</option> 
			<option value="German-Germany" <?php if ( $culture == 'German-Germany' ) echo 'selected="selected"'; ?>>German-Germany</option> 
			<option value="German-Switzerland" <?php if ( $culture == 'German-Switzerland' ) echo 'selected="selected"'; ?>>German-Switzerland</option> 
			<option value="Portuguese-Brasil" <?php if ( $culture == 'Portuguese-Brasil' ) echo 'selected="selected"'; ?>>Portuguese-Brasil</option> 
			<option value="Portuguese-Portugal" <?php if ( $culture == 'Portuguese-Portugal' ) echo 'selected="selected"'; ?>>Portuguese-Portugal</option> 				
		 </select>
	</p>
	<!-- Widget Logo Culture field END -->  	   

     <!-- PART 2: Widget Include Image field START -->
     <p>      
        <input id="<?php echo $this->get_field_id('includeimage'); ?>" 
               name="<?php echo $this->get_field_name('includeimage'); ?>" type="checkbox" 
               value="1" 
			   <?php if($includeimage) echo ' checked="checked"'; ?> />
		<label for="<?php echo $this->get_field_id('includeimage'); ?>">Include Image: </label>      
      </p>
      <!-- Widget Include Image field END -->   

	  <!-- PART 3: Widget Image Width field START -->
     <p>
      <label for="<?php echo $this->get_field_id('imagewidth'); ?>">Image Width </label>
        <input class="widefat" id="<?php echo $this->get_field_id('imagewidth'); ?>" 
               name="<?php echo $this->get_field_name('imagewidth'); ?>" type="text" 
               value="<?php echo esc_attr($imagewidth); ?>" />      
      </p>
      <!-- Widget Image Width field END -->	  
	  
	<!-- PART 3: Widget Image Height field START -->
     <p>
      <label for="<?php echo $this->get_field_id('imageheight'); ?>">Image Height: </label>
        <input class="widefat" id="<?php echo $this->get_field_id('imageheight'); ?>" 
               name="<?php echo $this->get_field_name('imageheight'); ?>" type="text" 
               value="<?php echo esc_attr($imageheight); ?>" />      
      </p>
      <!-- Widget Image Height field END -->

     <!-- PART 2: Widget Include Description field START -->
     <p>      
        <input id="<?php echo $this->get_field_id('includedescription'); ?>" 
               name="<?php echo $this->get_field_name('includedescription'); ?>" type="checkbox" 
               value="1" 
			   <?php if($includedescription) echo ' checked="checked"'; ?> />
		<label for="<?php echo $this->get_field_id('includedescription'); ?>">Include Description: </label>      
      </p>
      <!-- Widget Include Description field END -->   	  
	  
	  
	<!-- PART 3: Widget Max Description Size field START -->
     <p>
      <label for="<?php echo $this->get_field_id('maxdescriptionsize'); ?>">Max Description Size: </label>
        <input class="widefat" id="<?php echo $this->get_field_id('maxdescriptionsize'); ?>" 
               name="<?php echo $this->get_field_name('maxdescriptionsize'); ?>" type="text" 
               value="<?php echo esc_attr($maxdescriptionsize); ?>" />
      
      </p>
      <!-- Widget Max Description Size field END -->

     <!-- PART 2: Widget Include Related field START -->
     <p>      
        <input id="<?php echo $this->get_field_id('includerelated'); ?>" 
               name="<?php echo $this->get_field_name('includerelated'); ?>" type="checkbox" 
               value="1" 
			   <?php if($includerelated) echo ' checked="checked"'; ?> />
		<label for="<?php echo $this->get_field_id('includerelated'); ?>">Include Related: </label>      
      </p>
      <!-- Widget Include Related field END -->   	  
	 
	 <!-- PART 3: Widget Max Items field START -->
     <p>
      <label for="<?php echo $this->get_field_id('maxitems'); ?>">Max Items: </label>
        <input class="widefat" id="<?php echo $this->get_field_id('maxitems'); ?>" 
               name="<?php echo $this->get_field_name('maxitems'); ?>" type="text" 
               value="<?php echo esc_attr($maxitems); ?>" />
      
      </p>
      <!-- Widget Max Items field END -->
	  
     <?php
   
  }
 
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
	$instance['exchange'] = $new_instance['exchange'];
    $instance['symbol'] = $new_instance['symbol'];
	$instance['width'] = $new_instance['width'];
	$instance['height'] = $new_instance['height'];
	$instance['includeimage'] = (isset($new_instance['includeimage']) AND $new_instance['includeimage'] == 1) ? 1 : 0;
	$instance['culture'] = $new_instance['culture'];
	
	$instance['maxdescriptionsize'] = $new_instance['maxdescriptionsize'];
	$instance['maxitems'] = $new_instance['maxitems'];
	
	$instance['imagewidth'] = $new_instance['imagewidth'];
	$instance['imageheight'] = $new_instance['imageheight'];
	
	$instance['includedescription'] = (isset($new_instance['includedescription']) AND $new_instance['includedescription'] == 1) ? 1 : 0;
	$instance['includerelated'] = (isset($new_instance['includerelated']) AND $new_instance['includerelated'] == 1) ? 1 : 0;
	
	
    return $instance;
  }
  
 
}

//add_action( 'widgets_init', create_function('', 'return register_widget("Widget_Stockdio_Market_News");') );
add_action( 'widgets_init', function() {
	return register_widget("Widget_Stockdio_Market_News");
});

add_action('admin_print_styles', 'stockdio_market_news_widget_admin_styles');

function stockdio_market_news_widget_admin_styles() {
  ?>
  <style>
	#available-widgets-list [class*=widget_stockdio_market_news] .widget-title:before{
	  content: "\61" !important;
	  font-family: "stockdio-font" !important;
	}
  </style>
  <?php
}

?>