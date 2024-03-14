
( function() {
	//alert("test");
	if (typeof tinymce.PluginManager.get('stockdio_charts_button') != 'undefined')
		return;
	var stockdio_editor_button;
	var stockdio_pluginSelector;
    tinymce.PluginManager.add( 'stockdio_charts_button', function( editor, url ) {
		var single_chart_type='';
		var js_active_editor= editor;
		var stockdio_save_settings;
		var chart_types = function() {
			var types = null;
			var typesValues = [];
			var i=0;
			if (typeof stockdio_historical_charts_settings != 'undefined' ){
				single_chart_type = 'Historical Chart';
				stockdio_save_settings = stockdio_historical_charts_settings;
				typesValues.push({ value: 'Historical Chart', text: 'Historical Chart' });
				i++;
			}
			
			if (typeof stockdio_quotes_board != 'undefined'){
				single_chart_type = 'Quotes List';
				stockdio_save_settings = stockdio_quotes_board_settings;
				typesValues.push({ value: 'Quotes List', text: 'Quotes List' });
				i++;
			}
			
			if (typeof stockdio_market_overview != 'undefined'){
				single_chart_type = 'Stock Market Overview';
				stockdio_save_settings = stockdio_market_overview_settings;
				typesValues.push({ value: 'Stock Market Overview', text: 'Stock Market Overview' });
				i++;
			}
			
			if (typeof stockdio_marker_news != 'undefined'){
				single_chart_type = 'Stock Market News';
				stockdio_save_settings = stockdio_news_board_settings;
				typesValues.push({ value: 'Stock Market News', text: 'Stock Market News' });
				i++;
			}
			
			if (typeof stockdio_ticker_settings != 'undefined'){
				single_chart_type = 'Stock Market Ticker';
				stockdio_save_settings = stockdio_ticker_settings;
				typesValues.push({ value: 'Stock Market Ticker', text: 'Stock Market Ticker' });
				i++;
			}
			
			if (i>1){
				types = {
							type   : 'combobox',
							name   : 'chart_type',
							label  : 'Widget Type',
							classes:'f_chart_type',
							values : typesValues,

							onselect: function(){
								OnChangeStockdioType(this.value());		
							}
						};
			}
			
			return types;
		};
		var m_types = chart_types();
        // Add a button that opens a window
        editor.addButton( 'stockdio_charts_button', {

            tooltip: 'Insert Stockdio shortcode',
            icon: 'icon-stockdio',
            onclick: function() {
                // Open window
                stockdio_editor_button= editor.windowManager.open( {
                    title: 'Stockdio Financial Widgets',
					onpostrender: function (e){						
						setTimeout(function(){
							var windowJQ = jQuery(tinyMCE.activeEditor.windowManager.windows[0].getEl(0));
							var jqForm = windowJQ.find(".mce-reset");
							var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
							if (h < windowJQ.height()){
								windowJQ.css("height",h);
								windowJQ.css("width",windowJQ.width() + 20);
								windowJQ.attr("test123","red");
								jqForm.css("height","100%");
								jqForm.css("overflow-y","auto");
								jqForm.css("overflow-x","hidden");
							}
						}, 100);
					},
                    body: [
						m_types,						
						{
							type   : 'combobox',
							name   : 'exchange',
							label  : 'Stock Exchange',
							classes:'f_s f_exchange s_h_c s_q_b s_m_o s_m_n s_t',
							onPostRender: function(e){
								setTimeout( function(){
									var windowJQ = jQuery(tinyMCE.activeEditor.windowManager.windows[0].getEl(0));
									var items = stockdio_editor_button.settings.body;
									var v = items[0]!=null? items[0].values[0].value:single_chart_type;
									windowJQ.find(".mce-f_chart_type input").val(v);
									OnChangeStockdioType(v);	
								}, 50);
							},							
							value: stockdio_save_settings.default_exchange,
							values : [
								{ value: 'Forex', text: 'Currencies Trading' },
								{ value: 'Commodities', text: 'Commodities Trading' },
								{ value: 'NYSENasdaq', text: 'NYSE-Nasdaq-NYSE MKT' },
								{ value: 'OTCMKTS', text: 'OTC Markets' },
								{ value: 'OTCBB', text: 'OTC Bulletin Board' },
								{ value: 'LSE', text: 'London Stock Exchange', },
								{ value: 'TSE', text: 'Tokyo Stock Exchange' },
								{ value: 'HKSE', text: 'Hong Kong Stock Exchange' },
								{ value: 'SSE', text: 'Shanghai Stock Exchange' },
								{ value: 'SZSE', text: 'Shenzhen Stock Exchange' },
								{ value: 'FWB', text: 'Deutsche Börse Frankfurt' },
								{ value: 'XETRA', text: 'XETRA' },
								{ value: 'AEX', text: 'Euronext Amsterdam' },
								{ value: 'BEX', text: 'Euronext Brussels' },
								{ value: 'PEX', text: 'Euronext Paris' },
								{ value: 'LEX', text: 'Euronext Lisbon' },
								{ value: 'CHIX', text: 'Australian Securities Exchange' },
								{ value: 'TSX', text: 'Toronto Stock Exchange' },
								{ value: 'TSXV', text: 'TSX Venture Exchange' },
								{ value: 'CSE', text: 'Canadian Securities Exchange' },
								{ value: 'SIX', text: 'SIX Swiss Exchange' },
								{ value: 'KRX', text: 'Korean Stock Exchange' },
								{ value: 'Kosdaq', text: 'Kosdaq Stock Exchange' },
								{ value: 'OMXS', text: 'NASDAQ OMX Stockholm' },
								{ value: 'OMXC', text: 'NASDAQ OMX Copenhagen' },
								{ value: 'OMXH', text: 'NASDAQ OMX Helsinky' },
								{ value: 'OMXI', text: 'NASDAQ OMX Iceland' },
								{ value: 'BSE', text: 'Bombay Stock Exchange' },
								{ value: 'NSE', text: 'India NSE' },
								{ value: 'BME', text: 'Bolsa de Madrid' },
								{ value: 'JSE', text: 'Johannesburg Stock Exchange' },
								
								
								{ value: 'TWSE', text: 'Taiwan Stock Exchange' },
								{ value: 'BIT', text: 'Borsa Italiana' },
								{ value: 'MOEX', text: 'Moscow Exchange' },
								{ value: 'Bovespa', text: 'Bovespa Sao Paulo Stock Exchange' },
								{ value: 'NZX', text: 'New Zealand Exchange' },
								{ value: 'ISE', text: 'Irish Stock Exchange' },
								{ value: 'SGX', text: 'Singapore Exchange' },
								
								{ value: 'TADAWUL', text: 'Tadawul Saudi Stock Exchange' },	
								{ value: 'WSE', text: 'Warsaw Stock Exchange' },
								
								{ value: 'TASE', text: 'Tel Aviv Stock Exchange' },	
								
								
								{ value: 'KLSE', text: 'Bursa Malaysia' },
								{ value: 'IDX', text: 'Indonesia Stock Exchange' },
								{ value: 'BMV', text: 'Bolsa Mexicana de Valores' },
								{ value: 'OSE', text: 'Oslo Stock Exchange' },
								
								{ value: 'BCBA', text: 'Bolsa de Comercio de Buenos Aires' },
								{ value: 'SET', text: 'Stock Exchange of Thailand' },
								{ value: 'VSE', text: 'Vienna Stock Exchange' },
								{ value: 'BCS', text: 'Bolsa de Comercio de Santigo' },
								{ value: 'BIST', text: 'Borsa Istanbul' },
								{ value: 'OMXT', text: 'NASDAQ OMX Tallinn' },
								{ value: 'OMXR', text: 'NASDAQ OMX Riga' },
								{ value: 'OMXV', text: 'NASDAQ OMX Vilnius' },		
								{ value: 'PSE', text: 'Philippine Stock Exchang' },		
								{ value: 'ADX', text: 'Abu Dhabi Securities Exchange' },		
								{ value: 'DFM', text: 'Dubai Financial Market' },		
								{ value: 'BVC', text: 'Bolsa de Valores de Colombia' },		
								
								{ value: 'NGSE', text: 'Nigerian Stock Exchange' },
								{ value: 'QSE', text: 'Qatar Stock Exchange' },
								{ value: 'TPEX', text: 'Taipei Exchange' },
								{ value: 'BVL', text: 'Bolsa de Valores de Lima' },
								{ value: 'EGX', text: 'The Egyptian Exchange' },
								
								{ value: 'ASE', text: 'Athens Stock Exchange' },								
								{ value: 'NASE', text: 'Nairobi Securities Exchange' },
								{ value: 'HNX', text: 'Hanoi Stock Exchange' },
								{ value: 'HOSE', text: 'Hochiminh Stock Exchange' },
								{ value: 'BCPP', text: 'Prague Stock Exchange' },
								{ value: 'AMSE', text: 'Amman Stock Exchange' }
								
							]
						},
						{type: 'textbox', name: 'symbol', label: 'Symbol', classes:'f_s f_symbol s_h_c s_m_n', value:stockdio_save_settings.default_symbol},
						{type: 'textbox', name: 'compare', label: 'Compare', classes:'f_s f_compare s_h_c', value:stockdio_save_settings.default_compare},						
						{type: 'textbox', name: 'symbols', label: 'Symbols', classes:'f_s f_symbols s_q_b s_t', value:stockdio_save_settings.default_symbols},
						
						{type: 'textbox', name: 'equities', label: 'Equities', classes:'f_s f_symbols s_m_o', value:stockdio_save_settings.default_equities},
						{type: 'textbox', name: 'indices', label: 'Indices', classes:'f_s f_symbols s_m_o', value:stockdio_save_settings.default_indices},
						{type: 'textbox', name: 'commodities', label: 'Commodities', classes:'f_s f_symbols s_m_o', value:stockdio_save_settings.default_commodities},
						{type: 'textbox', name: 'currencies', label: 'Currencies', classes:'f_s f_symbols s_m_o', value:stockdio_save_settings.default_currencies},
						
						{type: 'radio', name: 'scroll', label: 'Scroll', classes:'f_s f_symbols s_t', value:stockdio_save_settings.default_scroll},
						
						{
							type   : 'combobox',
							name   : 'speed',
							label  : 'Speed',
							classes:'f_s f_speed s_t',
							value: stockdio_save_settings.default_displayPrices,
							values : [								
								{ value: 'slowest', text: 'Slowest' },
								{ value: 'slower', text: 'Slower' },
								{ value: 'slow', text: 'Slow' },
								{ value: 'normal', text: 'Normal' },
								{ value: 'fast', text: 'Fast' },
								{ value: 'faster', text: 'Faster' }
							]
						},
						
						{type: 'textbox', name: 'width', label: 'Width', classes:'f_s f_width s_h_c s_q_b s_m_o s_m_n s_t', value:stockdio_save_settings.default_width},
						{type: 'textbox', name: 'height', label: 'Height', classes:'f_s f_height s_h_c s_q_b s_m_o s_m_n', value:stockdio_save_settings.default_height},
						{type: 'textbox', name: 'title', label: 'Title', classes:'f_s f_title s_q_b s_m_o s_m_n', value:stockdio_save_settings.default_title},
						{type: 'checkbox', name: 'includeChart', label: 'Include Chart', classes:'f_s f_includeChart s_q_b s_m_o', checked:stockdio_save_settings.default_includeChart=="1"?true:false},
						
						{type: 'checkbox', name: 'allowSort', label: 'Allow Sort', classes:'f_s f_allowSort s_q_b s_m_o', checked:stockdio_save_settings.default_allowSort=="1"?true:false},
						
						{type: 'textbox', name: 'logoMaxHeight', label: 'Logo Maximum Height', classes:'f_s f_logoMaxHeight s_q_b s_m_o', value:stockdio_save_settings.default_logoMaxHeight},
						{type: 'textbox', name: 'logoMaxWidth', label: 'Logo Maximum Width', classes:'f_s f_logoMaxWidth s_q_b s_m_o', value:stockdio_save_settings.default_logoMaxWidth},
						

						{
							type   : 'combobox',
							name   : 'displayPrices',
							label  : 'Display Prices',
							classes:'f_s f_displayPrices s_h_c',
							value: stockdio_save_settings.default_displayPrices,
							values : [
								{ value: 'OHLC', text: 'OHLC' },
								{ value: 'HLC', text: 'HLC' },
								{ value: 'Candlestick', text: 'Candlestick' },
								{ value: 'Lines', text: 'Lines' },
								{ value: 'Area', text: 'Area' }
							]
						},
						

						
						{type: 'checkbox', name: 'performance', label: 'Performance', classes:'f_s f_performance s_h_c', checked:stockdio_save_settings.default_performance=="1"?true:false},
						
						{type: 'textbox', name: 'from', label: 'From (yyyy-mm-dd)',classes:'f_s f_from s_h_c', value:stockdio_save_settings.default_symbol},
						{type: 'textbox', name: 'to', label: 'To (yyyy-mm-dd)',classes:'f_s f_to s_h_c', value:stockdio_save_settings.default_from},
						{type: 'textbox', name: 'days', label: 'Days',classes:'f_s f_days s_h_c', value:stockdio_save_settings.default_days},
						{type: 'checkbox', name: 'allowPeriodChange', label: 'Allow Period Change',classes:'f_s f_allowPeriodChange s_h_c', checked:stockdio_save_settings.default_allowPeriodChange=="1"?true:false},
						
						{
							type   : 'combobox',
							name   : 'culture',
							label  : 'Culture',
							classes:'f_s f_culture s_h_c s_q_b s_m_o s_m_n',
							value: stockdio_save_settings.default_culture,
							values : [
								{ value: 'English-US', text: 'English-US', classes:"stockdio_class_c s_h_c s_q_b s_m_o s_m_n" },
								{ value: 'English-UK', text: 'English-UK' , classes:"stockdio_class_c s_h_c s_q_b s_m_o s_m_n"},
								{ value: 'English-Canada', text: 'English-Canada' , classes:"stockdio_class_c s_h_c s_q_b s_m_o s_m_n"},
								{ value: 'English-Australia', text: 'English-Australia', classes:"stockdio_class_c s_h_c s_q_b s_m_o s_m_n" },
								{ value: 'Spanish-Spain', text: 'Spanish-Spain', classes:"stockdio_class_c s_h_c s_q_b s_m_o s_m_n" },
								{ value: 'Spanish-Mexico', text: 'Spanish-Mexico', classes:"stockdio_class_c s_h_c s_q_b s_m_o s_m_n" },
								{ value: 'Spanish-LatinAmerica', text: 'Spanish-LatinAmerica', classes:"stockdio_class_c s_h_c s_q_b s_m_o s_m_n" },
								{ value: 'French-France', text: 'French-France', classes:"stockdio_class_c s_h_c s_q_b s_m_o s_m_n" },
								{ value: 'French-Canada', text: 'French-Canada', classes:"stockdio_class_c s_h_c s_q_b s_m_o s_m_n" },
								{ value: 'French-Belgium', text: 'French-Belgium', classes:"stockdio_class_c s_h_c s_q_b s_m_o s_m_n" },
								{ value: 'French-Switzerland', text: 'French-Switzerland', classes:"stockdio_class_c s_h_c s_q_b s_m_o s_m_n" },
								{ value: 'Italian-Italy', text: 'Italian-Italy', classes:"stockdio_class_c s_h_c s_q_b s_m_o s_m_n" },
								{ value: 'Italian-Switzerland', text: 'Italian-Switzerland', classes:"stockdio_class_c s_h_c s_q_b s_m_o s_m_n" },
								{ value: 'German-Germany', text: 'German-Germany', classes:"stockdio_class_c s_h_c s_q_b s_m_o s_m_n" },
								{ value: 'German-Switzerland', text: 'German-Switzerland', classes:"stockdio_class_c s_h_c s_q_b s_m_o s_m_n" },
								{ value: 'Portuguese-Brasil', text: 'Portuguese-Brasil', classes:"stockdio_class_c s_h_c s_q_b s_m_o s_m_n" },
								{ value: 'Portuguese-Portugal', text: 'Portuguese-Portugal', classes:"stockdio_class_c s_h_c s_q_b s_m_o s_m_n" },
								{ value: 'Dutch-Netherlands', text: 'Dutch-Netherlands', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'Dutch-Belgium', text: 'Dutch-Belgium', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'SimplifiedChinese-China', text: 'SimplifiedChinese-China', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'SimplifiedChinese-HongKong', text: 'SimplifiedChinese-HongKong', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'TraditionalChinese-HongKong', text: 'TraditionalChinese-HongKong', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'Japanese', text: 'Japanese', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'Korean', text: 'Korean', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'Russian', text: 'Russian', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'Polish', text: 'Polish', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },	
								{ value: 'Turkish', text: 'Turkish', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'Arabic', text: 'Arabic', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'Hebrew', text: 'Hebrew', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'Swedish', text: 'Swedish', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'Danish', text: 'Danish', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'Finnish', text: 'Finnish', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'Norwegian', text: 'Norwegian', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'Icelandic', text: 'Icelandic', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'Greek', text: 'Greek', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'Czech', text: 'Czech', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'Thai', text: 'Thai', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'Vietnamese', text: 'Vietnamese', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'Hindi', text: 'Hindi', classes:"stockdio_class_c s_h_c s_q_b s_m_o" },
								{ value: 'Indonesian', text: 'Indonesian', classes:"stockdio_class_c s_h_c s_q_b s_m_o" }
							],
							onclick:function(){
								jQuery(".mce-stockdio_class_c").hide();
								jQuery(".mce-stockdio_class_c" + stockdio_pluginSelector).show();
							},
							onpostrender: function(){

							}
						},
						
						{type: 'checkbox', name: 'includeImage', label: 'Include Image', classes:'f_s f_includeImage s_m_n', checked:stockdio_save_settings.default_includeImage=="1"?true:false},
						{type: 'textbox', name: 'imageWidth', label: 'Image Width', classes:'f_s f_imageWidth s_m_n', value:stockdio_save_settings.default_imageWidth},
						{type: 'textbox', name: 'imageHeight', label: 'Image Height', classes:'f_s f_imageHeight s_m_n', value:stockdio_save_settings.default_imageHeight},
						{type: 'checkbox', name: 'includeDescription', label: 'Include Description', classes:'f_s f_includeDescription s_m_n', checked:stockdio_save_settings.default_includeDescription=="1"?true:false},
						{type: 'textbox', name: 'maxDescriptionSize', label: 'Max Description Size', classes:'f_s f_maxDescriptionSize s_m_n', value:stockdio_save_settings.default_maxDescriptionSize},
						{type: 'checkbox', name: 'includeRelated', label: 'Include Related', classes:'f_s f_includeRelated s_m_n', checked:stockdio_save_settings.default_includeRelated=="1"?true:false},
						{type: 'textbox', name: 'maxItems', label: 'Max Items', classes:'f_s f_maxItems s_m_n', value:stockdio_save_settings.default_maxItems}									
						
                    ],
                    onsubmit: function( e ) {
                        // Insert content when the window form is 
						var params = "";
						params += AddShortcodeParam(e.data.exchange, "stockExchange" );						
						params += AddShortcodeParam(e.data.width, "width" );						
						if (e.data.chart_type =="Historical Chart" || single_chart_type =="Historical Chart"){
							params += AddShortcodeParam(e.data.symbol, "symbol" );
							params += AddShortcodeParam(e.data.compare, "compare" );
							params += AddShortcodeParam(e.data.displayPrices, "displayPrices" );
							params += AddShortcodeParam(e.data.performance, "performance" );
							params += AddShortcodeParam(e.data.from, "from" );
							params += AddShortcodeParam(e.data.to, "to" );
							params += AddShortcodeParam(e.data.days, "days" );
							params += AddShortcodeParam(e.data.allowPeriodChange?"true":"false", "allowPeriodChange" );
							params += AddShortcodeParam(e.data.height, "height" );
							params += AddShortcodeParam(e.data.culture, "culture" );				
								
						}
						else {
							if (e.data.chart_type =="Quotes List"|| single_chart_type =="Quotes List"){
								params += AddShortcodeParam(e.data.symbols, "symbols" );								
								params += AddShortcodeParam(e.data.title, "title" );	
								params += AddShortcodeParam(e.data.allowSort, "allowSort" );									
								params += AddShortcodeParam(e.data.includeChart, "includeChart" );				
								params += AddShortcodeParam(e.data.logoMaxHeight, "logoMaxHeight" );
								params += AddShortcodeParam(e.data.logoMaxWidth, "logoMaxWidth" );
								params += AddShortcodeParam(e.data.height, "height" );
								params += AddShortcodeParam(e.data.culture, "culture" );					
							}
							else{
								if (e.data.chart_type =="Stock Market Overview"|| single_chart_type =="Stock Market Overview"){
									params += AddShortcodeParam(e.data.title, "title" );	
									
									params += AddShortcodeParam(e.data.equities, "equities" );		
									params += AddShortcodeParam(e.data.indices, "indices" );		
									params += AddShortcodeParam(e.data.commodities, "commodities" );		
									params += AddShortcodeParam(e.data.currencies, "currencies" );		
									
									params += AddShortcodeParam(e.data.allowSort, "allowSort" );
									params += AddShortcodeParam(e.data.includeChart, "includeChart" );				
									params += AddShortcodeParam(e.data.logoMaxHeight, "logoMaxHeight" );
									params += AddShortcodeParam(e.data.logoMaxWidth, "logoMaxWidth" );
									params += AddShortcodeParam(e.data.height, "height" );
									params += AddShortcodeParam(e.data.culture, "culture" );					
								}
								else{
									if (e.data.chart_type =="Stock Market News"|| single_chart_type =="Stock Market News"){
										params += AddShortcodeParam(e.data.symbol, "symbol" );
										params += AddShortcodeParam(e.data.title, "title" );		
										params += AddShortcodeParam(e.data.includeImage, "includeImage" );				
										params += AddShortcodeParam(e.data.imageWidth, "imageWidth" );
										params += AddShortcodeParam(e.data.imageHeight, "imageHeight" );
										params += AddShortcodeParam(e.data.includeDescription, "includeDescription" );
										params += AddShortcodeParam(e.data.maxDescriptionSize, "maxDescriptionSize" );
										params += AddShortcodeParam(e.data.maxItems, "maxItems" );
										params += AddShortcodeParam(e.data.height, "height" );
										params += AddShortcodeParam(e.data.culture, "culture" );					
										params += AddShortcodeParam(e.data.includeRelated, "includeRelated" );	
									}								
									else{
										if (e.data.chart_type =="Stock Market Ticker"|| single_chart_type =="Stock Market Ticker"){
											params += AddShortcodeParam(e.data.symbols, "symbols" );
											params += AddShortcodeParam(e.data.scroll, "scroll" );
											params += AddShortcodeParam(e.data.speed, "speed" );
										}
									}
								}
							}
						}
						var pluginShortCodeName;
						if (typeof e.data.chart_type != 'undefined')
							pluginShortCodeName = GetPluginShortCodeName(e.data.chart_type);
						else
							pluginShortCodeName = GetPluginShortCodeName(single_chart_type);
						editor.insertContent( '[' + pluginShortCodeName + ' ' + params + ']');
                    }

		
                } );

				
				
			}

        } );
		
		function AddShortcodeParam(p,paramName){
			if (typeof p != 'undefined' && p.length !== 0){
				return ' ' + paramName + '="' + p + '"';
			}
			return '';
		}
		function GetPluginShortCodeName(chartType){
			switch (chartType) {
				case "Historical Chart":
					return "stockdio-historical-chart";
				case "Quotes List":
					return "stock-quotes-list";
				case "Stock Market Overview":
					return "stock-market-overview";
				case "Stock Market News":
					return "stock-market-news";
					case "Stock Market Ticker":
					return "stock-market-ticker";
				default:
					return "";
			}			
		}

		function OnChangeStockdioType(type){
			var windowJQ = jQuery(tinyMCE.activeEditor.windowManager.windows[0].getEl(0));
			
			switch (type) {
				case "Historical Chart":
					stockdio_save_settings = stockdio_historical_charts_settings;
					stockdio_pluginSelector=".mce-s_h_c";
					break;
				case "Quotes List":
					stockdio_save_settings = stockdio_quotes_board_settings;
					stockdio_pluginSelector=".mce-s_q_b";
					break;					
				case "Stock Market Overview":
					stockdio_save_settings = stockdio_market_overview_settings;
					stockdio_pluginSelector=".mce-s_m_o";
					break;
				case "Stock Market News":
					stockdio_save_settings = stockdio_news_board_settings;
					stockdio_pluginSelector=".mce-s_m_n";
					break;					
				case "Stock Market Ticker":
					stockdio_save_settings = stockdio_ticker_settings;
					stockdio_pluginSelector=".mce-s_t";
					break;			
				default:
					stockdio_save_settings = stockdio_historical_charts_settings;
					stockdio_pluginSelector=".mce-s_h_c";
			}			
			
			windowJQ.find(".mce-f_s").each(function(){
				jQuery(this).parent().parent().hide();
			});
			windowJQ.find(stockdio_pluginSelector).each(function(){
				jQuery(this).parent().parent().show();
			});
			var jqForm = windowJQ.find(".mce-form");
			var initTop = 20;
			jqForm.find(".mce-formitem:visible").each(function(){				
				jQuery(this).css("top",initTop);
				initTop +=40;
			});
			jqForm.closest(".mce-container-body").css("height",initTop+10);
			jqForm.closest(".mce-panel").css("height",initTop+60);
			
			if (type == "Quotes List") {
				var arrayIni = ["includeLogo","includeSymbol","includeCompany","includePrice","includeChange","includePercentChange","includeTrend","showHeader"];
				var i;			
				for (i=0; i< arrayIni.length; i++){
					var jqField = windowJQ.find(".mce-f_" + arrayIni[i]);
					if (jqField.attr("aria-checked") != "true")
						jqField.click();
				}
			}
			
			var i;
			var items = stockdio_editor_button.settings.body;
			var item;
			for (i=0; i < items.length; i++) {
				item = items[i];
				if (item==null || item.name=="chart_type")
					continue;
				var jqField = windowJQ.find(".mce-f_" + item.name);
				if (typeof stockdio_save_settings["default_" +item.name] != 'undefined'){					
					if (jqField.hasClass("mce-checkbox")){
						if (stockdio_save_settings["default_" +item.name] == "1" && jqField.attr("aria-checked") != "true")
							jqField.click();
						if (stockdio_save_settings["default_" +item.name] != "1" && jqField.attr("aria-checked") == "true")
							jqField.click();
					}						
					else{	
						if (jqField.hasClass("mce-combobox")){
							windowJQ.find(".mce-f_" + item.name).find("input").val(stockdio_save_settings["default_" +item.name]);
						}
						else {
							windowJQ.find(".mce-f_" + item.name).val(stockdio_save_settings["default_" +item.name]);
						}
					}
				}
				else {
					if (jqField.hasClass("mce-checkbox")){
							if (jqField.attr("aria-checked") == "true")
								jqField.click();
					}
					else{
						if (jqField.hasClass("mce-combobox")){
							windowJQ.find(".mce-f_" + item.name).find("input").val("");
						}
						else
							windowJQ.find(".mce-f_" + item.name).val("");
					}
						
				}
			}
			
		}
    } );

} )();