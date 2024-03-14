(function () {
	let $ = jQuery	
	var js_stockdio_popular_array = null
	var js_stockdio_form;
	var js_stockdio_popular_array_special = null
	var js_default_exchange_id = "#default_exchange"
	var js_default_symbol_id = "#default_symbols"
	var js_from_blocks = false;
	var js_exchange_modal= false;
	var js_setAttributes = null;
	var js_current_type = '';
	var js_p_valdiate_text = '';
	var js_filter_param = '';
	var js_is_market_overview = false;
	var js_is_single_symbol= false;

	initAll = function(){
		js_from_blocks = false;
		js_exchange_modal= false;
		js_setAttributes = null;
		js_current_type = '';
		js_p_valdiate_text = '';
		js_filter_param = '';
		js_is_market_overview = false;
		js_is_single_symbol= false;
	}
	
	stockdio_open_exchange_modal =  function(e, setAttributes){
		initAll();
		var button = $(e);		
		js_exchange_modal = true;
		if (setAttributes){
			js_from_blocks = true;
			js_setAttributes = setAttributes
			js_default_exchange_id = "#select-exchange"
			js_default_symbol_id = "#default_symbols"
			js_stockdio_form = $('body');	
		}
		else {
			js_stockdio_form = button.closest('form');		
		}
		if (!js_stockdio_popular_array){
			js_stockdio_popular_array =  stockdio_get_popular_array();
		}
		else{
			s_s_init();
		}		
	}
	stockdio_open_search_single_modal_from_block=  function(setAttributes, type){
		initAll();
		js_default_symbol_id = "#default_symbol";
		js_is_single_symbol=true;
		js_exchange_modal = false;
		js_from_blocks = true;		
		js_setAttributes = setAttributes
		js_stockdio_form = $('body');		
		if (type){
			js_current_type = type;
		}
		if (!js_stockdio_popular_array){
			js_stockdio_popular_array =  stockdio_get_popular_array();
		}
		else{
			s_s_init();
		}		
	}
	stockdio_open_search_modal_from_block =  function(setAttributes, type){
		initAll();
		js_exchange_modal = false;
		js_default_exchange_id = "#select-exchange"
		js_default_symbol_id = "#default_symbols"
		js_from_blocks = true;		
		js_setAttributes = setAttributes
		js_stockdio_form = $('body');		
		if (type){
			js_current_type = type;
		}
		if (!js_stockdio_popular_array){
			js_stockdio_popular_array =  stockdio_get_popular_array();
		}
		else{
			s_s_init();
		}
	}

	stockdio_open_search_symbol_modal  =  function(e, type){
		initAll();
		js_default_symbol_id = "#default_symbol";
		js_is_single_symbol=true;
		stockdio_open_search_modal_global(e,type);
	}

	stockdio_open_search_modal =  function(e, type){
		initAll();
		stockdio_open_search_modal_global(e,type);
	}

	stockdio_open_search_modal_global =  function(e, type){
		js_exchange_modal = false;
		var button = $(e);
		js_current_type = type;
		js_stockdio_form = button.closest('form');		
		if ($(".widget-content").length>0 && $("div.form").length>0 ){
			js_stockdio_form = button.closest('div');	
		}
		if (!js_stockdio_popular_array){
			js_stockdio_popular_array =  stockdio_get_popular_array();
		}
		else{
			s_s_init();
		}
	}

	s_s_init =  function(){		
		if($(js_default_exchange_id).length <= 0){
			js_default_exchange_id = ".default_exchange";
		}
		if($(js_default_symbol_id).length <= 0){
			js_default_symbol_id = ".default_symbols";
		}			
		var modal = js_stockdio_form.find('.stockdio_search_modal')
		var default_ExchangeObj = js_stockdio_form.find(js_default_exchange_id);
		var default_SymbolsObj = js_stockdio_form.find(js_default_symbol_id);

		if (js_exchange_modal){
			if (modal.length <= 0){
				modal = $(stockdio_create_search_modal('Select Exchange', "s_s_modal_exchange"))
				var symbols = default_SymbolsObj.val()
				var exchange = default_ExchangeObj.val()		
				var modalBody = $(stockdio_get_modal_exchange_body(exchange))			
				modal.find(".stockdio_modal_body").append(modalBody);
				js_stockdio_form.append(modal);
				if (js_stockdio_popular_array)
					stockdio_fill_popular_elemets(modal);
				for(var i=0; i< js_stockdio_popular_array.length; i++){
					if (js_stockdio_popular_array[i][1] === exchange && js_stockdio_popular_array[i][3] == 2){
						$(`.s_s_modal_exchange .s_s_li_${js_stockdio_popular_array[i][5]} a`).click();
						break;
					}
				}							
			}
		}
		else{
			if (modal.length <= 0){
				var sMainClass = s_s_get_main_class();
				modal = $(stockdio_create_search_modal('Select data to display',sMainClass))
				var symbols = s_s_get_default_symbols(default_SymbolsObj);
				var exchange = default_ExchangeObj.val()
				var modalBody = $(stockdio_get_modal_body(exchange))			
				modal.find(".stockdio_modal_body").append(modalBody);
				js_stockdio_form.append(modal);
				$(".stockdio_search_loading").show();
				js_stockdio_form.find(".stockdio_close_modal").click(function(){
					modal.remove();
				})
				modal.find(".stockdio_search_input_text").val(symbols);	

				modal.find(".stockdio_search_symbols_div .stockdio_search_inner_div").show();
				var js_totalRowHeight = $(".s_s_h_td1").outerHeight()

				if (js_stockdio_popular_array)
					stockdio_fill_popular_elemets(modal);

					//<p class="s_s_p_validate_text">Select your default Stock Exchange and type or paste the list of symbols, separated by semi-colon (;)</p> js_current_type
				if (js_current_type)
					modal.find(".s_s_p_validate_text").text(`Type or paste the list of ${js_current_type}, separated by semi-colon (;)`);
				//adjust size:
				var outerHeight = 0;
				$('.s_s_h').each(function() {
					outerHeight += $(this).outerHeight();
				});
				
				var maxHeight = js_totalRowHeight - outerHeight - 120;
				$(".stockdio_search_elements_list").css("min-height",maxHeight)
				//$(".stockdio_search_inner_div2").width($(".stockdio_search_inner_div2").parent().width() - 2);
				if (symbols && symbols.trim() !== ""){
					$(".stockdio_search_popular_div").addClass("s_s_long_tile");				
					$(".s_s_h_tr_own_symbols").show();				
					$(".s_s_choose_tile, .s_s_p_choose, .s_s_i_forward, .s_s_back").hide();
					$(".s_s_h_tr_bottom_back, .s_s_i_ok").show();	
				}
				else{
					$(".stockdio_search_loading").hide();
				}

				$(".s_s_stock_market_overview .s_s_h_tr_own_symbols").show();		
				$(".s_s_stock_market_overview .s_s_i_forward, .s_s_stock_market_overview .s_s_back").hide();
				$(".s_s_stock_market_overview .s_s_i_ok").show();	
				$(".s_s_stock_market_overview .s_s_h_tr_bottom_back").show();	
				if (js_is_single_symbol){
					$(".s_s_h_tr_own_symbols").show();
					$(".s_s_i_forward, .s_s_back").hide();
					$(".s_s_i_ok").show();	
					$(".s_s_h_tr_bottom_back").show();	
				}
			}
			stockdio_processSymbolsString(false,false);
		}
	}

	s_s_get_default_symbols = function(symbolsObj){
		if (js_current_type){
			return $("#default_" + js_current_type).val();
		}
		else
			return symbolsObj.val();
	}
	

	s_s_get_main_class= function(){
		

		if (js_is_single_symbol){
			return 's_s_single_symbol';
		}
		if (js_current_type === "indices" || js_current_type === "commodities" || js_current_type === "equities" ||js_current_type === "currencies"){
			js_is_market_overview = true;
			if (js_current_type === "indices" || js_current_type === "commodities")
				js_filter_param=js_current_type.toUpperCase();
			else{
				if (js_current_type === "currencies")
					js_filter_param='FOREX';
				else
					js_filter_param=$(js_default_exchange_id).val();
			}
			return 's_s_stock_market_overview';
		}
		return '';
	}

	stockdio_fill_popular_elemets = function(modal){
		var item; var exchangeDetails = []
		var categories = {}
		js_stockdio_popular_array_special = [];
		for (var i=0; i<js_stockdio_popular_array.length;i++){
			item = {}
			item["isGroup"] = js_stockdio_popular_array[i][4]
			item["name"] = js_stockdio_popular_array[i][0]
			item["dExch"] = js_stockdio_popular_array[i][1]
			item["symbols"] = js_stockdio_popular_array[i][2]
			item["category"] = js_stockdio_popular_array[i][3]
			item["country"] = js_stockdio_popular_array[i][5]
			item["dExchName"] = js_stockdio_popular_array[i][6]
			item["categoryName"] = js_stockdio_popular_array[i][7]
			item["countryName"] = js_stockdio_popular_array[i][8]
			item["inputPlaceholder"] = js_stockdio_popular_array[i][9]
			//item["categoryName"] = item["category"]==1?"Global Overview":item["category"]==2?"Market Overview for a specefic country":"Stocks by Industry"

			if (item["category"] == -1){
				js_stockdio_popular_array_special.push(item);
			}

			if (!categories[item["category"]])
				 categories[item["category"]] = []

			categories[item["category"]].push(item);
		}
		s_s_processPopularCategories(categories, modal)
		//console.log("____categories",categories)
	}

	s_s_processPopularCategories = function(categories, modal){
		var items;
		for (var i=1; i<6;i++){
			if (!categories[i])
				continue;				
			items = categories[i]
			if (items.length <= 0)
				continue;
			s_s_processPopularSingleCategory(items, modal)
		}
	}

	s_s_open_popular_cats= function(e){
	}

	s_group_by_country = function(items){
		var group = {};
		var item
		for(var i=0; i< items.length; i++){
			if (!group[items[i].countryName])
				group[items[i].countryName] = [];
			item = {};
			item.dExch = items[i].dExch;
			item.dExchName = items[i].dExchName;
			item.symbols = items[i].symbols;
			item.name = items[i].name;
			item.country = items[i].country;
			item.countryName = items[i].countryName;
			group[items[i].countryName].push(item);
		}
		return Object.entries(group).sort().reduce( (o,[k,v]) => (o[k]=v,o), {} );
		//return group;
	}

	s_s_processPopularSingleCategory= function(items, modal){
		var catName = items[0].categoryName
		var button
		var exchName = items[0].name;
		var exch = items[0].dExch;
		var symbols = items[0].symbols;
		var keyCountry="";
		var divList = ""
		var placeholder= items[0].inputPlaceholder;
		if (true){
			var flag = `<i class="s_s_i_separator"></i>`, rightCol = "", sClass=""; leftCol="";
			var groupItems = null
			if (items[0].isGroup){
				keyCountry= items[0].countryName;
				sClass="s_s_details_list_big"
				groupItems = s_group_by_country(items);
				exchName = "NYSE-Nasdaq-NYSE MKT";
				symbols = "AAPL;MSFT;GOOG;FB;ORCL;^SPX;^IXIC;^DJI;FOREX:EUR/USD;FOREX:GBP/USD;BONDS:US10YBY";
				exch = "NYSENASDAQ";
				flag = `<i class="stockdio_flag_icon stockdio_icon_mini" style="background-image: url(https://services.stockdio.com/assets/flags/4x3/us.svg)"></i>`;
				rightCol = `<div class="s_s_details_list_inner_r">
						<ul class="s_s_ul_country_exchanges">							
						</ul>
					</div>`
				var restOfElements = ``;
				//<li><a onclick="stockdio_onclick_popular_li(this,'AAPL','COMMODITIES','','Commodities','')"><span class="s_s_list_link_text">Commodities</span></a></li>
				var sSelected;
				for (var k =0; k<js_stockdio_popular_array_special.length; k++){					
					restOfElements += `<li><a onmouseover="s_s_rest_of_elements_hover(true)" onclick="stockdio_onclick_popular_li(this,'','${js_stockdio_popular_array_special[k].dExch}','','${js_stockdio_popular_array_special[k].name}','')">
					<span class="s_s_list_link_text">${js_stockdio_popular_array_special[k].name}</span></a></li>`
				}
				leftCol = `<div class="s_s_details_list_inner_l1">
					<ul class="s_s_ul_exchanges_cats">	
						<li><a onmouseover="s_s_rest_of_elements_hover(false)"  onclick="s_s_show_exchanges()"><span class="s_s_list_link_text">Exchanges</span></a></li>
						${restOfElements}
					</ul>
				</div>`			

			}


			button = `<div type="button" class="stockdio_s_exchanges_details_button" onclick="stockdio_open_exchanges_details(this)">
			<span class="stockdio_s_pull_left">
			${flag}
			${exchName}</span>&nbsp;<span class="caret"></span>
			</div>`;
			 divList = `<div class="stockdio_s_details_list ${sClass}"  tabindex="0" >
					 <div class="s_s_details_list_header">					 	
						 <div class="s_s_right" onclick="stockdio_hide_exchanges_details()"><svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="-2 -2 24 24" role="img" aria-hidden="true" focusable="false"><path d="M14.95 6.46L11.41 10l3.54 3.54-1.41 1.41L10 11.42l-3.53 3.53-1.42-1.42L8.58 10 5.05 6.47l1.42-1.42L10 8.58l3.54-3.53z"></path></svg></div>					 						 
						 <div class="s_s_left"><input class="s_s_search_input" type="text" placeholder="${placeholder}"></input></div>
					</div>
					 <div class="s_s_details_list_inner">
					 	${leftCol}
					 	<div class="s_s_details_list_inner_l">
							<ul class="stockdio_s_exchanges_details_ul">																																																														
							</ul>
						</div>
						${rightCol}
					</div>
				</div>`
				var divListObj = $(divList);
				if (groupItems){
					for (var key in groupItems) {
						if (groupItems.hasOwnProperty(key)) {

							var li =  `<li class='s_s_li_${groupItems[key][0].country}'><a onclick="stockdio_onclick_popular_li_country(this, false)">
							<span class="stockdio_flag_icon stockdio_icon_mini" style="background-image: url(https://services.stockdio.com/assets/flags/4x3/${groupItems[key][0].country}.svg)"></span>
							<span class="s_s_list_link_text">${key}</span><span class="fa fa-check check-mark"></span></a></li>`
							var liObj = $(li);
							liObj.data("items",groupItems[key])
							liObj.find("a").hover(function(){
								stockdio_onclick_popular_li_country(this, true)
							})
							divListObj.find("ul.stockdio_s_exchanges_details_ul").append(liObj);
						}
					}
				}
				else{
					for (var i=0; i< items.length;i++){
						sSelected = items[i].name === exchName?"class='s_selected'":"";
						divListObj.find("ul.stockdio_s_exchanges_details_ul").append(`<li ${sSelected}><a onclick="stockdio_onclick_popular_li(this,'${items[i].symbols}','${items[i].dExch}','${items[i].country}','${items[i].name}','${items[i].countryName}')">						
						<span class="s_s_list_link_text">${items[i].name}</span><span class="fa fa-check check-mark"></span></a></li>`)			
					}	
				}
	
			
		}


		
		if (items[0].isGroup){
			//create exchange
			if ($(".s_s_new_search_exchange div").length <=0 ){
				var default_ExchangeObj = js_stockdio_form.find(js_default_exchange_id);
				var butonObj = $(button)
				
				var exchangeInfo = s_s_search_exchange_info(default_ExchangeObj.val());
				butonObj.html(`<span class="stockdio_s_pull_left"><i class="stockdio_flag_icon stockdio_icon_mini" style="background-image: url(https://services.stockdio.com/assets/flags/4x3/${exchangeInfo.country}.svg)"></i>
				${exchangeInfo.exchName}</span>`)					
				butonObj.data("exchange",exchangeInfo.exch);
				butonObj.data("keyCountry",exchangeInfo.country);
				//tileObj.find(".stockdio_s_exchanges_details_button").data("keyCountry",keyCountry);					
				//console.log("butonObj",butonObj.clone()[0].outerHTML)
				divListObj.clone(true).appendTo($(".s_s_h_tr_custom td"))
				butonObj.clone(true).appendTo($(".s_s_new_search_exchange"))

				//console.log("s_s_new_search_exchange",$(".s_s_new_search_exchange").html())
				$(".s_s_h_tr_custom").find(".s_s_details_list_header input").keyup(function(){
					s_filter_values(this, true);
				});

				s_s_set_exchange_value(exchangeInfo.exch);
				//$(".s_s_new_search_exchange").find(".stockdio_s_exchanges_details_button").data("exchange",exchangeInfo.exch);


			}
			//modal.find(".stockdio_search_exchange_select").val(default_ExchangeObj.val());
		}

		var iconName = `PopularTickers${items[0].category}.svg`
		var tile = `<div class="stockdio_s_tile s_s_small_tile">
						<div><i class="s_tile_icon" style="background-image: url(https://services.stockdio.com/assets/images/${iconName})"></i></div>
		
			<span class="s_s_tile_title">${catName}</span>
			${button}
			<div class="s_s_tile_button_continue" onclick="s_s_onchange_select_popular(this)"><span>Select</span></div>		
		</div>`

		var tileObj = $(tile);

		if (!js_exchange_modal) {
			tileObj.append(divListObj);	
			modal.find(".s_s_div_popular_tiles").append(tileObj);
		}
		else{
			modal.find(".s_s_exchange_selector_div").append(divListObj);
			modal.find(".s_s_exchange_selector_div").append(button);
			modal.find(".stockdio_s_exchanges_details_button").hide();			
			modal.find(".stockdio_s_exchanges_details_button").data("exchange",$(js_default_exchange_id).val());
		}

		tileObj.find(".stockdio_s_exchanges_details_button").data("exchange",exch);
		tileObj.find(".stockdio_s_exchanges_details_button").data("symbols",symbols);
		tileObj.find(".stockdio_s_exchanges_details_button").data("keyCountry",keyCountry);
		tileObj.find(".s_s_details_list_header input").keyup(function(){
			s_filter_values(this, false);
		});
		
	}
	
	s_s_rest_of_elements_hover = function(b){
		if (b){
			$(".s_s_h_tr_custom .stockdio_s_exchanges_details_ul,.s_s_h_tr_custom .s_s_ul_country_exchanges").hide();
		}
		else{
			$(".s_s_h_tr_custom .stockdio_s_exchanges_details_ul,.s_s_h_tr_custom .s_s_ul_country_exchanges").show();
		}
	}

	s_filter_values = function(t, forExchanges){
		var v = t.value? t.value.trim().toLowerCase():"";
			var obj = $(t);
			var list = obj.closest(".stockdio_s_details_list")
			var scrollObj = null;
			list.find(".stockdio_s_exchanges_details_ul .s_s_list_link_text").each(function(i,e) {
				if ($(e).text().toLowerCase().startsWith(v)) {
					$(e).closest("li").show();
					if (v!=="")
						scrollObj = $(e).closest("li").get(0)
						
				}
				else {
					$(e).closest("li").hide();
				}
			});
			if (scrollObj) scrollObj.scrollIntoView({block:'center'});
	}
	s_s_search_exchange_info= function(exchName, catName){
		if (!exchName){
			return { country:"us", exchName:"NYSE-Nasdaq-NYSE MKT", exch:"NYSENASDAQ", countryName:"United States"}
		}
		
		for (var i=0; i<js_stockdio_popular_array.length;i++){
			item = {}
			item["isGroup"] = js_stockdio_popular_array[i][4];
			item["dExch"] = js_stockdio_popular_array[i][1];
			item["country"] = js_stockdio_popular_array[i][5];
			item["dExchName"] = js_stockdio_popular_array[i][6];
			item["countryName"] = js_stockdio_popular_array[i][8]			
			if (!item["dExch"]) continue;
			if (exchName.toLowerCase().trim() === item["dExch"].toLowerCase().trim() && 
				(!catName || catName === js_stockdio_popular_array[i][7])){
				var country = item["country"];
				var countryName = item["countryName"];
				if (item["dExch"] === "NYSENASDAQ") {
					country = "us";
					countryName  = "United States"
				}
				return { country, exchName:item["dExchName"], exch:item["dExch"], countryName}
			}
		}
		if (exchName.toLowerCase() === "commodities") {
			return { country:"", exchName:"Commodities", exch:"COMMODITIES"}
		}
		else {
			return { country:"us", exchName:"NYSE-Nasdaq-NYSE MKT", exch:"NYSENASDAQ", countryName:"United States"}
		}
	}

	stockdio_open_exchanges_details = function(e){
		
		$(".stockdio_s_details_list").hide();
		var parent = $(e).closest(".s_s_small_tile");
		var isMainExchange = false;
		if (parent.length <= 0){
			parent = $(e).closest("td");	
			isMainExchange = true;
		}

		var list = parent.find(".stockdio_s_details_list");
		if (list.css("display") === "none"){
			list.show();
		}
		else
			list.hide();

		//if (isMainExchange)
		//	return;

		//var keyCountry = parent.find(".stockdio_s_exchanges_details_button").data("keyCountry");
		var exchange = parent.find(".stockdio_s_exchanges_details_button").data("exchange");
		var catName =parent.find(".s_s_tile_title").text().trim();
		var exchangeInfo = s_s_search_exchange_info(exchange, catName);
		var keyCountry = exchangeInfo.countryName

		parent.find(".stockdio_s_exchanges_details_ul li").removeClass("s_s_list_link_text");
		if (keyCountry){
			parent.find(".stockdio_s_exchanges_details_ul .s_s_list_link_text").each(function(i,el){
				var liTextObj = $(el);
				if (liTextObj.text() === keyCountry){
					//liTextObj.closest("a").click();
					stockdio_onclick_popular_li_country(liTextObj.closest("a")[0], true, true)
					//$(".s_s_details_list_inner").scrollTo(liTextObj);
					liTextObj.get(0).scrollIntoView({block:'center'});
					return;
				}

			})
		}


	}

	s_s_show_exchanges= function(){
		
	}

	stockdio_hide_exchanges_details = function(){
		$(".stockdio_s_details_list").hide();
	}
	stockdio_onclick_popular_li_country = function(e,isHover,init){
		var obj = $(e);		
		var isMainExchange = false
		var parent = obj.closest(".s_s_small_tile");
		if (parent.length <= 0){
			parent = $(e).closest("td");	
			isMainExchange = true;
		}		
		if(!isHover || init) {
			var li = obj.closest("li");
			parent.find(".stockdio_s_exchanges_details_ul li").removeClass("s_selected");
			li.addClass("s_selected");
			if (js_exchange_modal){
				li.get(0).scrollIntoView({block:'center'});
			}			
		}
		
		var ul = parent.find(".s_s_ul_country_exchanges")		
		ul.html("");
		var items = obj.closest("li").data("items")
		//console.log("items for " + key, items)
		var curentExchange = parent.find(".stockdio_s_exchanges_details_button").data("exchange");
		for(var i = 0; i< items.length; i++){
			ul.append(`<li ${curentExchange===items[i].dExch?"class='s_selected'":""}><a onclick="stockdio_onclick_popular_li(this,'${items[i].symbols}','${items[i].dExch}','${items[i].country}','${items[i].dExchName}','${items[i].countryName}')">						
			<span class="s_s_list_link_text">${items[i].dExchName}</span><span class="fa fa-check check-mark"></span></a></li>`)		
		}
		if (!isHover){
			ul.find("li").first().find("a").click();
		}

	}
	stockdio_onclick_popular_li = function(e,symbols, exchange, country, exchName, countryName){
		var parent = $(e).closest(".s_s_small_tile");
		var isMainExchange = false
		if (parent.length <= 0){
			parent = $(e).closest("td");	
			isMainExchange = true;
		}	
		
		parent.find(".stockdio_s_exchanges_details_button").data("exchange",exchange);
		parent.find(".stockdio_s_exchanges_details_button").data("symbols",symbols);

		var li = $(e).closest("li");
		if (!js_exchange_modal)
			parent.find(".stockdio_s_exchanges_details_ul li").removeClass("s_selected");
		parent.find(".s_s_ul_country_exchanges li").removeClass("s_selected");
		
		li.addClass("s_selected");

		//parent.find(".stockdio_s_exchanges_details_button").data("keyCountry",countryName);
		if (js_exchange_modal) return;

		if (country)
			parent.find(".stockdio_s_exchanges_details_button").html(`
				<span class="stockdio_s_pull_left"><i class="stockdio_flag_icon stockdio_icon_mini" style="background-image: url(https://services.stockdio.com/assets/flags/4x3/${country}.svg)"></i>
				${exchName}</span>		
				`)
		else{
			parent.find(".stockdio_s_exchanges_details_button").html(`
			<i class="s_s_i_separator"></i><span class="stockdio_s_pull_left">
			${exchName}</span>		
			`)		
		}
		
		stockdio_hide_exchanges_details();
	}
	s_s_onchange_select_popular = function(e){
		$(".stockdio_search_loading").show();
		var obj = $(e);
		var parent = obj.closest(".s_s_small_tile");		
		var exchange, symbols;
		var exchange = parent.find(".stockdio_s_exchanges_details_button").data("exchange");
		var symbols = parent.find(".stockdio_s_exchanges_details_button").data("symbols");

		var modal = $(".stockdio_modal_body");
		var matchingValue = s_s_get_matching_value(exchange);  
		s_s_set_exchange_value(matchingValue);
		modal.find(".stockdio_search_input_text").val(symbols);
		
		stockdio_processSymbolsString(true, false);	
	}

	stockdio_get_popular_array = function(){
		$.ajax({
			url: `https://api.stockdio.com/freedata/financial/info/v1/getpopulartickers`,
			success: function (data, textStatus, xhr) {	
				if (data.status && data.status.code == 0){
					js_stockdio_popular_array = data.data.tickers.values	
					var modal = $(".stockdio_modal_body");
					stockdio_fill_popular_elemets(modal);
					s_s_init();				
				}			
			},
			error: function (xhr, status, error) { console.log(xhr, status, error); }
		});
	}
	
	stockdio_on_change_customname = function(e, symbol, exchange){
		//fix string
		console.log("stockdio_on_change_customname", symbol, exchange, e)
		stockdio_fix_string_from_elements();
	}
	stockdio_search_delete_element = function(e){
		//delete element and fix string
		$(e).closest(".stockdio_search_list_element").remove();
		stockdio_fix_string_from_elements();
		stockdio_processSymbolsString(false,false);
	}
	stockdio_fix_string_from_elements = function(){
		var modal = $(".stockdio_modal_body");
		var dExchange =s_s_get_echange_value();
		if (dExchange) dExchange=dExchange.toLowerCase();
		var s=""
		modal.find(".stockdio_search_elements_list .stockdio_search_list_element").each(function(i,e){
			if(s!="") s+=";";
			var div = $(e);
			div.find(".stockdio_search_element_index").text(i+1);
			var exchange = div.find(".stockdio_search_element_exchange").text(); 
			var symbol = div.find(".stockdio_search_element_symbol").text();
			var custom = div.find(".stockdio_search_element_custom_name").val();
			var real = div.find(".stockdio_search_element_custom_name").data("real");
			if (custom && custom.trim()!==real.trim()) 
				custom = `(${custom})`;
			else
				custom = '';
			if (dExchange == exchange.toLowerCase() || !exchange || exchange === "Index" ||exchange === "INDEX" || exchange ==="null" || exchange ==="?" || js_is_market_overview)
				s += `${symbol}${custom}`
			else
				s += `${exchange}:${symbol}${custom}`

		})
		$(".stockdio_modal_body .stockdio_search_input_text").val(s);
		
		if (s && s.trim()!==""){
			$(".stockdio_search_customsearch_p").css('visibility','visible');
		}
		else{
			$(".stockdio_search_customsearch_p").css('visibility','hidden');
		}
		
	}
	
	s_s_symbol_edit_onclick= function(e){
		
		var parent = $(e).closest(".stockdio_search_list_element");
		parent.find(".stockdio_search_element_custom_name").show();		
		parent.find(".s_s_apply_parent").css("display","inline-block")
		parent.find(".w3-rest .s_s_s_name, .s_s_edit_i").hide();
	}
	s_s_symbol_apply_onclick= function(e, a){		
		if (e) e.preventDefault();
		var parent = $(a).closest(".stockdio_search_list_element");
		var t =parent.find(".stockdio_search_element_custom_name").val()
		var actual = parent.find(".stockdio_search_element_custom_name").text()
		var real = parent.find(".stockdio_search_element_custom_name").data("real")
		if ((t && t.trim()))
			parent.find(".s_s_s_name").text(t)
		if (!t &&  actual !== real)			
			parent.find(".s_s_s_name").text(real)
		parent.find(".stockdio_search_element_custom_name").hide();
		parent.find(".w3-rest .s_s_s_name, .s_s_edit_i").show();
		parent.find(".s_s_apply_parent").css("display","none")
	}

	stockdio_processSymbolsString = function(hidePopular,scrollToBottom, validate){
		var modal = $(".stockdio_modal_body");
		var exchange =s_s_get_echange_value();
		var symbols = encodeURIComponent(modal.find(".stockdio_search_input_text").val());//AAPL;LSE:VOD;MSFT;GOOG;COMMODITIES:GC(Oro);^SPX
		if (js_is_market_overview){
			exchange = js_filter_param;
		}
		if (!symbols){
			$(".stockdio_search_loading").hide();
			modal.find(".stockdio_search_elements_list").html("");
			$(".stockdio_search_inputs span.s_error").css("color","black");
			$(".stockdio_search_inputs span.s_error").text(`Please enter a symbol`);
			$(".s_s_edit_symbols_div").hide();
			stockdio_fix_string_from_elements();
			$(".stockdio_search_loading").hide();
			return;
		}
		$.ajax({
			url: `https://api.stockdio.com/freedata/financial/info/v1/validateSymbols/?exchange=${exchange}&symbols=${symbols}`,
			success: function (data, textStatus, xhr) {				
				$(".stockdio_search_loading").hide();
				//console.log("stockdio_processSymbolsString data:",data);
				if (data.status.code == 0){
					var array = data.data.Symbols.values
					var newDefaultExchange = array[0][2]

					if (!validate) {
						var matchingValue = s_s_get_matching_value(newDefaultExchange);    
						if(!js_is_market_overview) 	s_s_set_exchange_value(matchingValue);

						if(js_is_market_overview) matchingValue = $(js_default_exchange_id).val();

						var url = js_url.replace("%exchange%",matchingValue).replace("%appkey%",s_s_get_app_key())

						if (js_filter_param){
							url = url.replace('%filter%', `&includeExchanges=${js_filter_param}`)
							url = url.replace('&includeExchangeValue=true', ``)
							url = url.replace('&includeExchange=true', ``)
						}
						url = url.replace('%filter%', ``)
						if ($("#stockdio_iframe").attr("src") !== url)
							$("#stockdio_iframe").attr("src",url);
					}

					var symbol, exchange, customName, realName, countryCode, flagHtml;
					var elements  = modal.find(".stockdio_search_elements_list");
					elements.html("");
					var errorsSymbols = []; var isError; var eCount = 0; var sEditInput
					for( var i=1; i< array.length; i++){
						isError = false
						symbol = array[i][0]
						realName = array[i][1]
						exchange = array[i][2]
						customName = array[i][3]
						countryCode = array[i][4]
						if (!customName || customName ==null || customName ==="null")
							customName="";
						
						if ((!exchange || exchange ==null || exchange ==="null") && symbol && symbol[0] ==='^'){
							exchange = "INDEX";
						}
						if (!realName || realName === "null"){
							isError = true;
							realName = "Symbol was not Found";
						}
						if (exchange ==="null" || exchange ==null ){
							isError = true;
							exchange = "?"
							errorsSymbols.push(symbol)
						}

						if (isError){ 
							sEditInput="";
							eCount++;
						}
						else{
							sEditInput=`<i title="Change Display Name" class="s_s_edit_i" onclick="s_s_symbol_edit_onclick(this)" style="background-image: url(https://services.stockdio.com/assets/images/EditIcon.svg)"></i>`;
						}
						flagHtml = "";
						if (countryCode){
							flagHtml = `<span class="stockdio_flag_icon" style="background-image: url(https://services.stockdio.com/assets/flags/4x3/${countryCode}.svg)"></span>`
						}
						else{
							flagHtml = '<span class="stockdio_flag_icon"></span>';
						}
						elements.append(`<div class="stockdio_search_list_element w3-row">	
						<span class="stockdio_search_element_index">${i}</span>
						<div class="stockdio_search_list_element_inner"	>
							<div class="w3-col s_s_firstscols">
								<span class="s_s_drag">
									<i style="background-image: url(https://services.stockdio.com/assets/images/DragLines.svg)"></i>
								</span>
								<span class="s_s_line_sep s_s_first">
									<svg width="1" height="30" viewBox="0 0 1 30" fill="none" xmlns="http://www.w3.org/2000/svg">
										<line x1="0.5" y1="2.18557e-08" x2="0.499998" y2="30" stroke="#28292D"/>
									</svg>
								</span>
								
								${flagHtml}						
								<span class="stockdio_search_element_exchange" style="${isError?'color:#EA0020':''}">${exchange}</span>
								<span class="s_s_line_sep">
									<svg width="1" height="30" viewBox="0 0 1 30" fill="none" xmlns="http://www.w3.org/2000/svg">
										<line x1="0.5" y1="2.18557e-08" x2="0.499998" y2="30" stroke="#28292D"/>
									</svg>
								</span>						
								<span class="stockdio_search_element_symbol" style="${isError?'color:#EA0020':''}">${symbol}</span>	
								<span class="s_s_line_sep">
									<svg width="1" height="30" viewBox="0 0 1 30" fill="none" xmlns="http://www.w3.org/2000/svg">
										<line x1="0.5" y1="2.18557e-08" x2="0.499998" y2="30" stroke="#28292D"/>
									</svg>
								</span>	
							</div>				
							<div class="w3-col w3-right s_s_rightcol">
								<div>								
									<i onclick="stockdio_search_delete_element(this)" class="stockdio_search_list_element_delete_i"  style="background-image: url(https://services.stockdio.com/assets/images/WizardDelete.svg)"></i>
								</div>
							</div>
							<div class="w3-rest">
								<span class="s_s_s_name" style="${isError?'color:#EA0020':''}" >${customName?customName:realName}</span>							
								<input data-real="${realName}" type="text" class="stockdio_search_element_custom_name" value="${customName || realName}" onchange="stockdio_on_change_customname(this,'${symbol}', '${exchange}')"></input>
								${sEditInput}
								<div class="s_s_apply_parent"><a class="s_s_apply_a" onclick="s_s_symbol_apply_onclick(event, this)">OK</a></div>							
							</div>
						</div>
						
						</div>`);
					}
					modal.find('.stockdio_search_element_custom_name, .stockdio_search_input_text').keypress(function(event) {
						if (event.keyCode == 13) {
							event.preventDefault();
						}
					});
					//sortable(".stockdio_search_elements_list");
					if (!validate) {
						stockdio_fix_string_from_elements();
					}

					var el = document.getElementById('stockdio_search_elements_list');
					new Sortable(el, {
						handle:'.s_s_firstscols,.s_s_s_name',
						//draggable: ".stockdio_search_list_element",
						ghostClass: "sortable-ghost",
						onUpdate: function (evt){
							stockdio_fix_string_from_elements();
						}
					});
					
					if (hidePopular) stockdio_hide_popular(true);
					if (scrollToBottom){
						$(".stockdio_search_elements_list").each( function() {
							var scrollHeight = Math.max(this.scrollHeight, this.clientHeight);
							this.scrollTop = scrollHeight - this.clientHeight;
							});
					}
					if (validate){
						if (errorsSymbols.length>0){
							////if (errors!="") errors+=", ";
							//errors += symbol;
							var errors = "";
							for(var i=0; i< errorsSymbols.length; i++){
								if (errors!="") { 
									if (i == errorsSymbols.length-1)
										errors+=" and ";
									else
										errors+=", ";
								}
								errors += errorsSymbols[i];
							}
							$(".stockdio_search_inputs span.s_error").css("color","red");
							//var sTextError = "The following symbols are not valid for the exchange you have selected:";
							var sTextError = "";
							if (eCount>=10)
								sTextError="There are several symbols not valid for the exchange you have selected.";
							else{
								if (eCount>1)
									sTextError+=`${errors} are not valid symbols.`
								else
									sTextError+=`${errors} is not a valid symbol.`
							}

							sTextError += " Review the Stock Exchange and/or symbols you have entered.";
							$(".stockdio_search_inputs span.s_error").text(sTextError);
						}
						else{
							$(".stockdio_search_inputs span.s_error").css("color","black");
							$(".stockdio_search_inputs span.s_error").text(`No errors found`)
						}
						$(".s_s_edit_symbols_div").show();
						$(".stockdio_search_loading").hide();
					}
				}
				else{
					//display an error
					$(".stockdio_search_loading").hide();
				}
			},
			error: function (xhr, status, error) { $(".stockdio_search_loading").hide();console.log(xhr, status, error); }
		});
	}
	stockdio_search_onclose= function(){
		var v = $(".stockdio_search_modal").parent().parent();
		if (v.length>0)
			v.remove();
	}

	
	stockdio_search_onclose_and_save = function(){
		var modal = $(".stockdio_modal_body");
		var exchange =s_s_get_echange_value();
		var exchangeName =s_s_get_exchange_text();
		if (js_exchange_modal) {
			exchange = $(".stockdio_s_exchanges_details_button").data("exchange") 
		}
		var symbols = modal.find(".stockdio_search_input_text").val();//AAPL;LSE:VOD;MSFT;GOOG;COMMODITIES:GC(Oro);^SPX
		if (!exchange) return;

		if(js_stockdio_form.find(js_default_exchange_id).length <= 0){
			js_default_exchange_id = ".default_exchange";
		}
		if(js_stockdio_form.find(js_default_symbol_id).length <= 0){
			js_default_symbol_id = ".default_symbols";
		}		
		
		var mValue = js_stockdio_form.find( js_default_exchange_id +  ' option').filter(function () { 
			return this.value.toLowerCase() === exchange.toLowerCase(); 
		} ).attr('value');  		

		if(js_stockdio_form.find(js_default_exchange_id).length > 0){
			js_stockdio_form.find(js_default_exchange_id).val(mValue);
		}

		var exchName = js_stockdio_form.find(js_default_exchange_id + " option:selected").text();

		if (!mValue){
			mValue = exchange;
			exchName = exchangeName
		}

		if (js_exchange_modal) {
			js_stockdio_form.find(js_default_exchange_id).val(mValue);
			if (js_stockdio_form.find('.s_b_exch_name_widget').length >0)
			js_stockdio_form.find('.s_b_exch_name_widget').text(`${exchName} (${mValue})`);			
			if (!js_from_blocks){
				$('#default_exchange_label').text(`${exchName} (Exchange Code: ${mValue})`);				
				
				$(".stockdio_modal_body .stockdio_close_modal").click();
				$("#submit").click();
			}
			else{
				js_stockdio_form.find('.s_b_exch_name').text(exchName);
				js_stockdio_form.find('.s_b_exch_code').text(mValue);
				js_setAttributes( { exchange: mValue} );
				$(".stockdio_modal_body .stockdio_close_modal").click();				
			}
		}
		else{
			if (!js_from_blocks){
				if (!js_current_type){
					js_stockdio_form.find(js_default_exchange_id).val(mValue);
					js_stockdio_form.find(js_default_symbol_id).val(symbols);
					$('#default_exchange_label').text(mValue);
					$('#default_symbols_label').text(symbols);
				}
				else{
					$('#default_' + js_current_type + '_label').text(symbols);
					$('#default_' + js_current_type).val(symbols);
				}
				$(".stockdio_modal_body .stockdio_close_modal").click();
				$("#submit").click();
			}
			else{
				//$(js_default_exchange_id).trigger('change');
				//$(js_default_symbol_id).trigger('change');			
				if (!js_current_type){
					$(js_default_exchange_id).val(mValue);
					$(js_default_symbol_id).val(symbols);
					$('.s_b_exch_name').text(exchName);
					$('.s_b_exch_code').text(mValue);
					js_setAttributes( { exchange: mValue} );
					$('.default_symbols_p').text(symbols);	
					js_setAttributes( { symbol: symbols} );								
					js_setAttributes( { symbols: symbols} );			
				}
				else{
					var o = {};
					o[js_current_type] = symbols
					js_setAttributes(o);	
				}
				
				$(".stockdio_modal_body .stockdio_close_modal").click();
			}
			if (js_stockdio_form.find('.s_b_exch_name_widget').length >0 || js_stockdio_form.find('.s_b_exch_name').length >0) {
				js_stockdio_form.find('.s_b_exch_name_widget').text(`${exchName} (${mValue})`);
				js_stockdio_form.find('.s_b_exch_name').text(exchName);
				js_stockdio_form.find('.s_b_exch_code').text(mValue);
				$('.default_symbols_p').text(symbols);	
			}
		}
		if (js_stockdio_form.find(".default_symbols").length>0){
			js_stockdio_form.find(".default_symbols").change();
		}
		
	}

	stockdio_create_search_modal = function(headerTitle, parentClass){
		return `
			<div tabindex="-1">
				<div class="components-modal__screen-overlay">
					<div class="components-modal__frame stockdio_search_modal ${parentClass}" role="dialog" aria-labelledby="components-modal-header-search" tabindex="-1">
						<div class="components-modal__content stockdio_modal_body" tabindex="0" role="document">
						   <div class="components-modal__header">
								<div class="components-modal__header-heading-container">
									<h1 id="components-modal-header-search" class="components-modal__header-heading s_s_modal_header_h1">${headerTitle}</h1>
								</div>								 
								<button type="button" class="components-button has-icon stockdio_close_modal" aria-label="Close dialogue" onclick="stockdio_search_onclose()"><svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="-2 -2 24 24" role="img" aria-hidden="true" focusable="false"><path d="M14.95 6.46L11.41 10l3.54 3.54-1.41 1.41L10 11.42l-3.53 3.53-1.42-1.42L8.58 10 5.05 6.47l1.42-1.42L10 8.58l3.54-3.53z"></path></svg></button>						
						   </div>
						</div>
					</div>
				</div>
			</div>
		`;
	}
	
	stockdio_processSelection = function(exchange,symbol){
		var modal = $(".stockdio_modal_body");
		var dExchange = s_s_get_echange_value();
		var stringToAdd = modal.find(".stockdio_search_input_text").val();
		var isEmpty = stringToAdd.trim().length <= 0;
		if (stringToAdd.trim().length > 0)
			stringToAdd +=  ";";
		if ((dExchange && (dExchange.toLowerCase() == exchange.toLowerCase() )) || exchange ==='{exchangevalue}')
			stringToAdd = js_is_single_symbol? `${symbol}`: `${stringToAdd}${symbol}`;
		else
			stringToAdd = js_is_single_symbol? `${exchange}:${symbol}`: `${stringToAdd}${exchange}:${symbol}`;
		modal.find(".stockdio_search_input_text").val(stringToAdd);
		stockdio_processSymbolsString(false,true);
	}


	var js_screen_open="";
	var js_back_screen="";
	var js_old_intut_text="";
	var js_old_exchange="";
	var js_old_exchange_edit="";	
	var js_old_intut_text_edit="";	
	
	s_s_back_onclose=function(){
		if (js_back_screen!=="" && js_screen_open===""){
			switch(js_back_screen){
				case "popular":
					stockdio_onclick_div_popular(true);
					break;
				case "symbols":
					stockdio_onclick_div_own_symbols(true);
					break;
				case "custom":
					stockdio_onclick_bottom_section(null,true);
					break;											
			}
		}
		else{
			//edit_symbols
			switch(js_screen_open){
				case "popular":
					stockdio_hide_popular(false);
					break;
				case "symbols":
					stockdio_hide_symbols(false)
					break;
				case "custom":
					stockdio_hide_bottom(false)
					break;						
				case "edit_symbols":
					s_s_edit_symbols_back()
					break;														
			}			
		}
	}
	s_check_old_string  = function(fromEdit){
		var symbols =  $(".stockdio_search_input_text").val()
		var exchange =  s_s_get_echange_value()
		if (fromEdit){
			if (symbols!==js_old_intut_text_edit || exchange != js_old_exchange_edit){
				$(".stockdio_search_input_text").val(js_old_intut_text_edit)
				s_s_set_exchange_value(js_old_exchange)			
				stockdio_processSymbolsString(false,false, true);
			}	
		}
		else{
			if (symbols!==js_old_intut_text || exchange != js_old_exchange){
				$(".stockdio_search_input_text").val(js_old_intut_text)
				s_s_set_exchange_value(js_old_exchange)			
				stockdio_processSymbolsString(false,false);
			}	
		}	
	}


	stockdio_hide_popular=function(fromContinue){
		js_back_screen = "popular"
		$(".s_s_h_tr_popular").hide();		
		s_hide_common(fromContinue);
	}
	stockdio_hide_symbols=function(fromContinue){
		js_back_screen = "symbols"		
		$(".s_s_p_choose, .s_s_back").hide();				
		s_hide_common(fromContinue);
	}

	stockdio_hide_bottom=function(fromContinue){		
		js_back_screen = "custom"		
		$(".s_s_h_tr_custom").hide();
		$(".s_s_h").show();	
		s_hide_common(fromContinue);
	}
	s_hide_common = function(fromContinue){
		js_screen_open = ""
		s_s_small_list()
		if (!$(".s_s_i_ok").hasClass("s_s_pull_right")){
			$(".s_s_i_ok").addClass("s_s_pull_right")
		}
		
		if(fromContinue){
			$(".stockdio_search_loading").hide();
			$(".s_s_h_tr_bottom").hide();
			$(".s_s_back").show();			
			s_s_big_list()
			$(".s_s_i_ok").removeClass("s_s_pull_right")
		}
		else{
			s_check_old_string();
			$(".s_s_h_tr_bottom").show();
			$(".s_s_back").hide();
		}
		$(".s_s_i_ok").css("display","inline-block")
		$(".s_s_h_tr_options").show();
		$("#stockdio_iframe").removeClass("s_s_stockdio_iframe_2")
		var symbols = $(".stockdio_search_input_text").val();	
		if (symbols && symbols.trim()!=="") {
			$(".s_s_h_tr_own_symbols,.s_s_i_ok").show();
			$(".s_s_i_forward, .s_s_choose_tile, .s_s_p_choose").hide();
			if (!$(".stockdio_search_popular_div").hasClass("s_s_long_tile")){
				$(".stockdio_search_popular_div").addClass("s_s_long_tile");									
			}
		}
		else{						
			$(".s_s_h_tr_own_symbols, .s_s_h_tr_bottom_back").hide();
			if (js_is_market_overview)
				$(".s_s_h_tr_own_symbols").show();
			$(".s_s_h_tr_options, .s_s_p_choose, .s_s_choose_tile").show();
			$(".stockdio_search_popular_div").removeClass("s_s_long_tile");	
		}
		if(fromContinue){
			$(".s_s_h_tr_options").hide();
			//s_s_elements_big_height
		}
		stockdio_hide_exchanges_details();
		
	}
	stockdio_onclick_bottom_section=function(e, fromBack){
		s_s_big_list()
		if (e) e.preventDefault();
		//set current exchagne:
		var currentExchange = s_s_get_echange_value();
		if (currentExchange !==""){
			var exchangeInfo = s_s_search_exchange_info(currentExchange);
			var butonObj = $(".s_s_h_tr_custom .stockdio_s_exchanges_details_button")
			butonObj.html(`<span class="stockdio_s_pull_left"><i class="stockdio_flag_icon stockdio_icon_mini" style="background-image: url(https://services.stockdio.com/assets/flags/4x3/${exchangeInfo.country}.svg)"></i>
			${exchangeInfo.exchName}</span>`)		
		}
		js_screen_open = "custom"
		if (!fromBack){
			js_old_intut_text = $(".stockdio_search_input_text").val()
			js_old_exchange=s_s_get_echange_value();
		}
		$(".s_s_h_tr_options, .s_s_h_tr_bottom, .s_s_i_forward").hide();
		$(".s_s_h_tr_custom, .s_s_h_tr_bottom_back, .s_s_i_ok, .s_s_back").show();	
		$(".s_s_h_tr_own_symbols").hide();	
		$(".s_s_edit_symbols_div").hide();
		$(".stockdio_search_inputs span.s_error").text("");
		$("#stockdio_iframe").removeClass("s_s_stockdio_iframe_2")
		$(".s_s_i_ok").removeClass("s_s_pull_right")
	}

	stockdio_onclick_div_popular = function(fromBack){
		s_s_big_list();
		js_screen_open = "popular"
		if (!fromBack){
			js_old_intut_text = $(".stockdio_search_input_text").val()
			js_old_exchange=s_s_get_echange_value();
		}
		$(".s_s_h_tr_options, .s_s_h_tr_bottom, .s_s_i_ok, .s_s_p_choose").hide();
		$(".s_s_h_tr_popular, .s_s_h_tr_bottom_back, .s_s_i_forward, .s_s_back").show();
		$(".s_s_h_tr_own_symbols").hide();
	}
	stockdio_onclick_div_own_symbols=function(fromBack){
		s_s_big_list()
		js_screen_open = "symbols"
		if (!fromBack){
			js_old_intut_text = $(".stockdio_search_input_text").val()
			js_old_exchange=s_s_get_echange_value();
		}
		$(".s_s_h_tr_options, .s_s_h_tr_bottom, .s_s_i_forward").hide();
		$(".s_s_h_tr_own_symbols, .s_s_h_tr_bottom_back, .s_s_i_ok, .s_s_back").show();
		$(".s_s_i_ok").removeClass("s_s_pull_right")
	}

	s_s_small_list = function(){
		if (!$(".stockdio_search_elements_list").hasClass("s_s_elements_small_height"))
			$(".stockdio_search_elements_list").addClass("s_s_elements_small_height");
	}
	s_s_big_list = function(){
		$(".stockdio_search_elements_list").removeClass("s_s_elements_small_height");
	}

	s_s_validate_symbols_click= function(e){
		if (e) e.preventDefault();
		stockdio_processSymbolsString(false,false, true)		
	}
	s_s_edit_symbols_back= function(){
		js_screen_open = "custom"
		s_check_old_string(true);
		$(".s_s_h_tr_own_symbols").hide();
		$(".s_s_h_tr_custom").show();	
	}
	s_s_edit_symbols_click= function(e){
		js_screen_open = "edit_symbols"		
		js_old_intut_text_edit = $(".stockdio_search_input_text").val()
		js_old_exchange_edit=s_s_get_echange_value();
		
		if (e) e.preventDefault();
		$(".s_s_h_tr_own_symbols").show();
		$(".s_s_h, .s_s_h_tr_custom").hide();		
		if (!$("#stockdio_iframe").hasClass("s_s_stockdio_iframe_2"))
			$("#stockdio_iframe").addClass("s_s_stockdio_iframe_2")
	}
	s_s_get_echange_value= function(){
		//return $(".stockdio_search_exchange_select").val();
		var v = $(".s_s_new_search_exchange .stockdio_s_exchanges_details_button").data("exchange") 
		return v;
	}

	s_s_get_exchange_text=function(){
		//return $(".stockdio_search_exchange_select").val();
		var v = $(".s_s_new_search_exchange .stockdio_s_exchanges_details_button").text().trim();
		return v;
	}

	s_s_set_exchange_value= function(v){
		//$(".stockdio_search_exchange_select").val(v);
		$(".s_s_new_search_exchange .stockdio_s_exchanges_details_button").data("exchange",v);
	}
	s_s_get_matching_value=function(exchange){
		/*
		return $('.stockdio_search_exchange_select option').filter(function () { 
			return this.value.toLowerCase() === exchange.toLowerCase(); 
		} ).attr('value');  
		*/
		return exchange;
	}
	s_s_exchange_onchange=function(){
		s_s_validate_symbols_click()
	}

	s_s_get_app_key=function(){
		if (typeof stockdio_economic_news_board_settings !== 'undefined') return stockdio_economic_news_board_settings.api_key;
		if (typeof stockdio_ticker_settings !== 'undefined' ) return stockdio_ticker_settings.api_key;		
		if (typeof stockdio_news_board_settings !== 'undefined') return stockdio_news_board_settings.api_key;
		if (typeof stockdio_market_overview_settings !== 'undefined') return stockdio_market_overview_settings.api_key;
		if (typeof stockdio_quotes_board_settings !== 'undefined') return stockdio_quotes_board_settings.api_key;
		if (typeof stockdio_historical_charts_settings !== 'undefined') return stockdio_historical_charts_settings.api_key;
		if (typeof optionsObj !== 'undefined') return  optionsObj.appKey;
		return "";
		
	}

	var js_url = `https://api.stockdio.com/visualization/financial/charts/v1/SymbolSearch?app-key=%appkey%&palette=financial-light&limit=8&height=60&width=100%&linkUrl=javascript:stockdio_processSelection(%22{exchangevalue}%22,%22{symbol}%22)&linkTarget=self&template=c117d249-212c-45ed-878b-1df46897ad54&OnLoad=stockdio_iframe&transparent=true&includeExchangeValue=true&stockExchange=%exchange%%filter%`;
//<button type="button" aria-expanded="false" aria-label="Close dialogue" class="components-button is-link" onclick="stockdio_search_onclose_and_save()">Apply Changes and Close</button>
	stockdio_get_modal_body = function(exch){
		var url = js_url.replace("%exchange%",exch).replace("%appkey%",s_s_get_app_key())
		if (js_filter_param){
			url = url.replace('%filter%', `&includeExchanges=${js_filter_param}`)
			url = url.replace('&includeExchangeValue=true', ``)
			url = url.replace('&includeExchange=true', ``)
		}
		url = url.replace('%filter%', ``)
		return `
			<div class="stockdio_search_modal_body">
			<table class="stockdio_search_table"> 
				<tr class="s_s_h_tr_popular"> 
					<td valign="top" >					
						<p>Select one of our pre-made common lists from one of the categories below:</p>
						<div class="s_s_div_popular_tiles"></div>
					</td>
				</tr>
				<tr class="s_s_h_tr_custom" style="display:none"> 
					<td valign="top" >					
						<div class="stockdio_search_inner_div2 ">
							<div class="stockdio_search_inner_div_inner">								
								<div class="stockdio_search_inputs">
										<p class="s_s_p_validate_text">Select your default Stock Exchange and type or paste the list of symbols, separated by semi-colon (;)</p>						
										<div class="s_s_new_search_exchange"></div>
										<input type="text" class="stockdio_search_input_text" />										
									<div class="s_s_validate_div"><a class="s_s_validate_a" onclick="s_s_validate_symbols_click(event)" href="#">Validate symbols</a></div>
									<span class="s_error"></span>
									<div class="s_s_edit_symbols_div" style="display:none"><a onclick="s_s_edit_symbols_click(event)" href="#" >Edit symbols</a></div>
								</div>
							</div>	
						</div>	
					</td>
				</tr>						
				<tr class="s_s_h_tr_own_symbols" style="display:none"> 
					<td valign="top" >					
						<div class="stockdio_search_div_section stockdio_search_symbols_div s_s_section " >
							<p class="s_s_h" >You can adjust the list below by adding elements using the search bar, deleting symbols or changing their order:</p>
							<iframe referrerpolicy="no-referrer-when-downgrade" class="stockdio-search-block" id="stockdio_iframe" frameborder="0" scrolling="no" width="100%" height="60"  src="${url}"></iframe>	
							<div class="stockdio_search_customsearch stockdio_search_inner_div">											
								<div class="stockdio_search_elements_list s_s_elements_small_height" id="stockdio_search_elements_list"></div>
								<p class="stockdio_search_customsearch_p">You can change the order of the stocks by draging them on the handle bars next to the location number</p>
								<div class="s_s_elemets_fade_top"></div>
								<div class="s_s_elemets_fade_bottom"></div>
							</div>
						</div>
					</td>
				</tr>				
					
				<tr class="s_s_h_tr_options"> 					
					<td valign="top" class="s_s_h_td2">
						<p class="s_s_p_choose">Choose the option that best suits your needs.</p>
						<div onclick="stockdio_onclick_div_popular()" class="stockdio_search_div_section stockdio_search_popular_div s_s_section stockdio_s_tile stockdio_s_tile_main">
							<div class="s_tile_div_icon"><i class="s_tile_icon" style="background-image: url(https://services.stockdio.com/assets/images/PopularTickers.svg)"></i></div>
							<div class="s_s_tile_titles">
								<span class="s_s_tile_title s_s_tile_title1">Select from our pre-made common lists</span>
								<span class="s_s_tile_title s_s_tile_title2" >Or select from our pre-made common lists</span>
								<span class="s_s_tile_subtitle s_s_easiest">(easiest and fastest)</span>
							</div>
							<div class="s_s_tile_button_continue"><span>Continue</span></div>
						</div>

						<div onclick="stockdio_onclick_div_own_symbols(this)" class="s_s_section s_s_choose_tile stockdio_s_tile stockdio_s_tile_main">
							<div><i class="s_tile_icon" style="background-image: url(https://services.stockdio.com/assets/images/ChooseOwnData.svg)"></i></div>
							<span class="s_s_tile_title">Choose your data</span>
							<span class="s_s_tile_subtitle">(select your custom data)</span>
							<div class="s_s_tile_button_continue" ><span>Continue</span></div>
						</div>


					</td>
				</tr> 		
				
								
				<tr class="s_s_h_tr_bottom" > 
					<td valign="top">
						<div class="stockdio_search_div_section stockdio_search_custom_div">	
						<p >If you already have the preferred stock exchange and the list of symbols you want, you can enter them <a onclick="stockdio_onclick_bottom_section(event)" href="#">here</a>.</p>
				</div>												
					</td> 
				</tr> 

				<tr class="s_s_h_tr_bottom_back" > 
					<td valign="bottom">		
						<div class="s_s_back_buttons">
							<div class="s_s_div_back_button s_s_back" onclick="s_s_back_onclose()">
								<i class="s_s_icon_back_buttons" style="background-image: url(https://services.stockdio.com/assets/images/WizardBack.svg)"></i>
							</div>

							<i class="s_s_icon_back_buttons s_s_i_forward" style="background-image: url(https://services.stockdio.com/assets/images/WizardForwardSelected.svg)"></i>
							<div class="s_s_div_back_button s_s_i_ok s_s_pull_right" onclick="stockdio_search_onclose_and_save()" >
								<i class="s_s_icon_back_buttons" style="background-image: url(https://services.stockdio.com/assets/images/WizardOK.svg);position:relative">
								</i>
							</div>						
						</div>
					</td>
				</tr>					
			</table> 			
			
				

				<div class="stockdio_search_loading">
					<div class="s_s_dots">
						<div class="s_s_dot s_s_dot1"></div>
						<div class="s_s_dot s_s_dot2"></div>
						<div class="s_s_dot s_s_dot3"></div>
						<div class="s_s_dot s_s_dot4"></div>
						<div class="s_s_dot s_s_dot5"></div>
					</div>
				</div>
			</div>
		`;
	}

	stockdio_get_modal_exchange_body = function(exch){
		return `
			<div class="stockdio_search_modal_body s_s_exchange_modal">
			<table class="stockdio_search_table"> 
				<tr class="s_s_h_tr_popular"> 
					<td valign="top" >					
						<p>Select the exchange:</p>
						<div class="s_s_exchange_selector_div"></div>
					</td>
				</tr>		

				<tr class="s_s_h_tr_bottom_back" > 
					<td valign="bottom">		
						<div class="s_s_back_buttons">
							<div class="s_s_div_back_button s_s_i_ok s_s_pull_right" onclick="stockdio_search_onclose_and_save()" >
								<i class="s_s_icon_back_buttons" style="background-image: url(https://services.stockdio.com/assets/images/WizardOK.svg);position:relative">
								</i>
							</div>						
						</div>
					</td>
				</tr>					
			</table> 			
			
				

				<div class="stockdio_search_loading">
					<div class="s_s_dots">
						<div class="s_s_dot s_s_dot1"></div>
						<div class="s_s_dot s_s_dot2"></div>
						<div class="s_s_dot s_s_dot3"></div>
						<div class="s_s_dot s_s_dot4"></div>
						<div class="s_s_dot s_s_dot5"></div>
					</div>
				</div>
			</div>
		`;
	}

	////  TICKER LAYOUT:
	s_s_open_ticker_layout = function(e){
		var modal = stockdio_get_modal_ticker_body();
		js_stockdio_form = $(e).closest('div');	
		js_stockdio_form.append(modal);
		var value = js_stockdio_form.find("input.s_s_layout").val();
		$(".stockdio_layout_type_button").removeClass("stockdio_selected");
		$(`.stockdio_layout_type_button[data-ticker_value="${value}"]`).addClass("stockdio_selected");

		$(".stockdio_layout_type_button").click(function(){
			$(".stockdio_layout_type_button").removeClass("stockdio_selected");
			$(this).addClass("stockdio_selected");
			js_stockdio_form.find("input.s_s_layout").val($(this).data("ticker_value"));
			js_stockdio_form.find("strong.s_s_layout").text($(this).data("ticker_value"));
			js_stockdio_form.find("input.s_s_layout").change();
		});
	}
	s_s_onlayout_close = function(){
		if (js_stockdio_form){
			js_stockdio_form.find(".s_s_layout_modal").remove();
		}
	}
	stockdio_get_modal_ticker_body = function(){
		return `
		<div tabindex="-1" class="s_s_layout_modal"><div><div class="components-modal__screen-overlay"><div class="components-modal__frame" role="dialog" aria-labelledby="components-modal-header-6" tabindex="-1"><div class="components-modal__content" role="document"><div class="components-modal__header"><div class="components-modal__header-heading-container"><h1 id="components-modal-header-6" class="components-modal__header-heading">Ticker Layouts</h1></div><button onclick="s_s_onlayout_close()" type="button" class="components-button has-icon" aria-label="Close dialog"><svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false"><path d="M13 11.8l6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z"></path></svg></button></div><div class="stockdio_cc_note"><p> NOTE: scroll down to see other ticker layouts.</p></div><div class="stockdio_cc_container"><div class="stockdio_layout_type_button" data-ticker_value="1"><span class="stockdio_layout_type_span">Ticker 1</span><img class="stockdio_layout_type_image" src="${stockdio_root_folder}/layout/ticker1-400.png"></div><div class="stockdio_layout_type_button " data-ticker_value="2"><span class="stockdio_layout_type_span">Ticker 2</span><img class="stockdio_layout_type_image" src="${stockdio_root_folder}/layout/ticker2-400.png"></div><div class="stockdio_layout_type_button " data-ticker_value="3"><span class="stockdio_layout_type_span">Ticker 3</span><img class="stockdio_layout_type_image" src="${stockdio_root_folder}/layout/ticker3-400.png"></div><div class="stockdio_layout_type_button " data-ticker_value="4"><span class="stockdio_layout_type_span">Ticker 4</span><img class="stockdio_layout_type_image" src="${stockdio_root_folder}/layout/ticker4-400.png"></div><div class="stockdio_layout_type_button " data-ticker_value="5"><span class="stockdio_layout_type_span">Ticker 5</span><img class="stockdio_layout_type_image" src="${stockdio_root_folder}/layout/ticker5-400.png"></div><div class="stockdio_layout_type_button " data-ticker_value="6"><span class="stockdio_layout_type_span">Ticker 6</span><img class="stockdio_layout_type_image" src="${stockdio_root_folder}/layout/ticker6-400.png"></div><div class="stockdio_layout_type_button " data-ticker_value="7"><span class="stockdio_layout_type_span">Ticker 7</span><img class="stockdio_layout_type_image" src="${stockdio_root_folder}/layout/ticker7-400.png"></div><div class="stockdio_layout_type_button " data-ticker_value="8"><span class="stockdio_layout_type_span">Ticker 8</span><img class="stockdio_layout_type_image" src="${stockdio_root_folder}/layout/ticker8-400.png"></div><div class="stockdio_layout_type_button " data-ticker_value="9"><span class="stockdio_layout_type_span">Ticker 9</span><img class="stockdio_layout_type_image" src="${stockdio_root_folder}/layout/ticker9-400.png"></div><div class="stockdio_layout_type_button " data-ticker_value="10"><span class="stockdio_layout_type_span">Ticker 10</span><img class="stockdio_layout_type_image" src="${stockdio_root_folder}/layout/ticker10-400.png"></div><div class="stockdio_layout_type_button " data-ticker_value="11"><span class="stockdio_layout_type_span">Ticker 11</span><img class="stockdio_layout_type_image" src="${stockdio_root_folder}/layout/ticker11-400.png"></div><div class="stockdio_layout_type_button " data-ticker_value="12"><span class="stockdio_layout_type_span">Ticker 12</span><img class="stockdio_layout_type_image" src="${stockdio_root_folder}/layout/ticker12-400.png"></div><div class="stockdio_layout_type_button " data-ticker_value="13"><span class="stockdio_layout_type_span" >Ticker 13</span><img class="stockdio_layout_type_image" src="${stockdio_root_folder}/layout/ticker13-400.png"></div><div class="stockdio_layout_type_button " data-ticker_value="14"><span class="stockdio_layout_type_span">Ticker 14</span><img class="stockdio_layout_type_image" src="${stockdio_root_folder}/layout/ticker14-400.png"></div><div class="stockdio_layout_type_button " data-ticker_value="15"><span class="stockdio_layout_type_span">Ticker 15</span><img class="stockdio_layout_type_image" src="${stockdio_root_folder}/layout/ticker15-400.png"></div><div class="stockdio_layout_type_button " data-ticker_value="16"><span class="stockdio_layout_type_span">Ticker 16</span><img class="stockdio_layout_type_image" src="${stockdio_root_folder}/layout/ticker16-400.png"></div></div></div></div></div></div></div>
		`;
	}


	/////// COLORS://///////
	s_s_open_colors_modal = function(e){
		
		$(".stockdio_widget_p input[type='text']").each(function(){
			$(this).replaceWith(jQuery(this).clone());
		});

		
		if (typeof ColorPicker !== 'function') { 
			let colorPickerComp=new Object;function ColorPicker(t,e){this.element=t,t.colorPickerObj=this,t.setAttribute("data-color",e),t.style.background=e,t.addEventListener("click",(function(){colorPickerComp.instance=this.colorPickerObj,colorPickerComp.pickerOpen=!0;const t=document.getElementById("color_picker");t.style.display="block";let e=this.getBoundingClientRect().top,o=this.getBoundingClientRect().left;if(e=e+t.offsetHeight>window.innerHeight?e-t.offsetHeight-2:e+this.offsetHeight+2,o+t.offsetWidth>window.innerWidth-20){o=o-(o+t.offsetWidth-window.innerWidth)-20}t.style.top=e+"px",t.style.left=o+"px",colorPickerComp.updateColorDisplays(this.getAttribute("data-color")),document.getElementById("color_text_values").focus()}))}!function(){colorPickerComp.pickerOpen=!1,colorPickerComp.instance=null,colorPickerComp.boxStatus=!1,colorPickerComp.boxStatusTouch=!1,colorPickerComp.sliderStatus=!1,colorPickerComp.sliderStatusTouch=!1,colorPickerComp.opacityStatus=!1,colorPickerComp.opacityStatusTouch=!1,colorPickerComp.colorTypeStatus="HEXA",colorPickerComp.hue=0,colorPickerComp.saturation=100,colorPickerComp.lightness=50,colorPickerComp.alpha=1,colorPickerComp.contextMenuElem=null,colorPickerComp.doubleTapTime=0,colorPickerComp.LSCustomColors={0:[]};const t=document.createElement("ASIDE");if(t.id="color_picker",t.innerHTML='\n\t\t<svg id="color_box" width="263" height="130">\n\t\t\t<defs>\n\t\t\t\t<linearGradient id="saturation" x1="0%" y1="0%" x2="100%" y2="0%">\n\t\t\t\t\t<stop offset="0%" stop-color="#fff"></stop>\n\t\t\t\t\t<stop offset="100%" stop-color="hsl(0,100%,50%)"></stop>\n\t\t\t\t</linearGradient>\n\t\t\t\t<linearGradient id="brightness" x1="0%" y1="0%" x2="0%" y2="100%">\n\t\t\t\t\t<stop offset="0%" stop-color="rgba(0,0,0,0)"></stop>\n\t\t\t\t\t<stop offset="100%" stop-color="#000"></stop>\n\t\t\t\t</linearGradient>\n\t\t\t\t<pattern id="pattern_config" width="100%" height="100%">\n\t\t\t\t\t<rect x="0" y="0" width="100%" height="100%" fill="url(#saturation)"></rect> }\n\t\t\t\t\t<rect x="0" y="0" width="100%" height="100%" fill="url(#brightness)"></rect>\n\t\t\t\t</pattern>\n\t\t\t</defs>\n\t\t\t<rect rx="5" ry="5" x="1" y="1" width="263" height="130" stroke="#fff" stroke-width="2" fill="url(#pattern_config)"></rect>\n\t\t\t<svg id="box_dragger" x="336" y="14" style="overflow: visible;">\n\t\t\t\t<circle r="9" fill="none" stroke="#000" stroke-width="2"></circle>\n\t\t\t\t<circle r="7" fill="none" stroke="#fff" stroke-width="2"></circle>\n\t\t\t</svg>\n\t\t</svg>\n\t\t<br>\n\t\t<div id="sliders">\n\t\t\t<svg id="color_slider" width="263" height="20">\n\t\t\t\t<defs>\n\t\t\t\t\t<linearGradient id="hue" x1="100%" y1="0%" x2="0%" y2="0%">\n\t\t\t\t\t\t<stop offset="0%" stop-color="#f00"></stop>\n\t\t\t\t\t\t<stop offset="16.666%" stop-color="#ff0"></stop>\n\t\t\t\t\t\t<stop offset="33.333%" stop-color="#0f0"></stop>\n\t\t\t\t\t\t<stop offset="50%" stop-color="#0ff"></stop>\n\t\t\t\t\t\t<stop offset="66.666%" stop-color="#00f"></stop>\n\t\t\t\t\t\t<stop offset="83.333%" stop-color="#f0f"></stop>\n\t\t\t\t\t\t<stop offset="100%" stop-color="#f00"></stop>\n\t\t\t\t\t</linearGradient>\n\t\t\t\t</defs>\n\t\t\t\t<rect rx="5" ry="5" x="1" y="1" width="263" height="20" stroke="#fff" stroke-width="2" fill="url(#hue)"></rect>\n\t\t\t\t<svg id="color_slider_dragger" x="277" y="11" style="overflow: visible;">\n\t\t\t\t\t<circle r="7" fill="none" stroke="#000" stroke-width="2"></circle>\n\t\t\t\t\t<circle r="5" fill="none" stroke="#fff" stroke-width="2"></circle>\n\t\t\t\t</svg>\n\t\t\t</svg>\n\t\t\t<svg id="opacity_slider" width="263" height="20">\n\t\t\t\t<defs>\n\t\t\t\t\t<linearGradient id="opacity" x1="100%" y1="0%" x2="0%" y2="0%">\n\t\t\t\t\t\t<stop offset="0%" stop-color="#000"></stop>\n\t\t\t\t\t\t<stop offset="100%" stop-color="#fff"></stop>\n\t\t\t\t\t</linearGradient>\n\t\t\t\t</defs>\n\t\t\t\t<rect rx="5" ry="5" x="1" y="6" width="263" height="10" stroke="#fff" stroke-width="2" fill="url(#opacity)"></rect>\n\t\t\t\t<svg id="opacity_slider_dragger" x="277" y="11" style="overflow: visible;">\n\t\t\t\t\t<circle r="7" fill="none" stroke="#000" stroke-width="2"></circle>\n\t\t\t\t\t<circle r="5" fill="none" stroke="#fff" stroke-width="2"></circle>\n\t\t\t\t</svg>\n\t\t\t</svg>\n\t\t</div>\n\t\t<div id="color_text_values" tabindex="0">\n\t\t\t<div id="hexa">\n\t\t\t\t<input id="hex_input" name="hex_input" type="text" maxlength="9" spellcheck="false" />\n\t\t\t\t<br>\n\t\t\t\t<label for="hex_input" class="label_text">HEX</label>\n\t\t\t</div>\n\t\t\t<div id="rgba" style="display: none;">\n\t\t\t\t<div class="rgba_divider">\n\t\t\t\t\t<input class="rgba_input" name="r" type="number" min="0" max="255" />\n\t\t\t\t\t<br>\n\t\t\t\t\t<label for="r" class="label_text">R</label>\n\t\t\t\t</div>\n\t\t\t\t<div class="rgba_divider">\n\t\t\t\t\t<input class="rgba_input" name="g" type="number" min="0" max="255" />\n\t\t\t\t\t<br>\n\t\t\t\t\t<label for="g" class="label_text">G</label>\n\t\t\t\t</div>\n\t\t\t\t<div class="rgba_divider">\n\t\t\t\t\t<input class="rgba_input" name="b" type="number" min="0" max="255" />\n\t\t\t\t\t<br>\n\t\t\t\t\t<label for="b" class="label_text">B</label>\n\t\t\t\t</div>\n\t\t\t\t<div class="rgba_divider">\n\t\t\t\t\t<input class="rgba_input" name="a" type="number" step="0.1" min="0" max="1" />\n\t\t\t\t\t<br>\n\t\t\t\t\t<label for="a" class="label_text">A</label>\n\t\t\t\t</div>\n\t\t\t</div>\n\t\t\t<div id="hsla" style="display: none;">\n\t\t\t\t<div class="hsla_divider">\n\t\t\t\t\t<input class="hsla_input" name="h" type="number" min="0" max="359" />\n\t\t\t\t\t<br>\n\t\t\t\t\t<label for="h" class="label_text">H</label>\n\t\t\t\t</div>\n\t\t\t\t<div class="hsla_divider">\n\t\t\t\t\t<input class="hsla_input" name="s" type="number" min="0" max="100" />\n\t\t\t\t\t<br>\n\t\t\t\t\t<label for="s" class="label_text">S%</label>\n\t\t\t\t</div>\n\t\t\t\t<div class="hsla_divider">\n\t\t\t\t\t<input class="hsla_input" name="l" type="number" min="0" max="100" />\n\t\t\t\t\t<br>\n\t\t\t\t\t<label for="l" class="label_text">L%</label>\n\t\t\t\t</div>\n\t\t\t\t<div class="rgba_divider">\n\t\t\t\t\t<input class="hsla_input" name="a" type="number" step="0.1" min="0" max="1" />\n\t\t\t\t\t<br>\n\t\t\t\t\t<label for="a" class="label_text">A</label>\n\t\t\t\t</div>\n\t\t\t</div>\n\t\t\t<button id="switch_color_type" class="remove_outline" name="switch-color-type">\n\t\t\t\t<svg viewBox="0 -2 24 24" width="20" height="20">\n\t\t\t\t\t<path fill="#555" d="M6 11v-4l-6 5 6 5v-4h12v4l6-5-6-5v4z"/>\n\t\t\t\t</svg>\n\t\t\t</button>\n\t\t</div>\n\t\t<div id="custom_colors">\n\t\t\t<div id="custom_colors_header">\n\t\t\t\t<svg id="custom_colors_pallet_icon" viewBox="0 0 24 24" width="15" height="18">\n\t\t\t\t\t<path fill="#555" d="M4 21.832c4.587.38 2.944-4.493 7.188-4.538l1.838 1.534c.458 5.538-6.315 6.773-9.026 3.004zm14.065-7.115c1.427-2.239 5.847-9.749 5.847-9.749.352-.623-.43-1.273-.976-.813 0 0-6.572 5.714-8.511 7.525-1.532 1.432-1.539 2.086-2.035 4.447l1.68 1.4c2.227-.915 2.868-1.039 3.995-2.81zm-11.999 3.876c.666-1.134 1.748-2.977 4.447-3.262.434-2.087.607-3.3 2.547-5.112 1.373-1.282 4.938-4.409 7.021-6.229-1-2.208-4.141-4.023-8.178-3.99-6.624.055-11.956 5.465-11.903 12.092.023 2.911 1.081 5.571 2.82 7.635 1.618.429 2.376.348 3.246-1.134zm6.952-15.835c1.102-.006 2.005.881 2.016 1.983.004 1.103-.882 2.009-1.986 2.016-1.105.009-2.008-.88-2.014-1.984-.013-1.106.876-2.006 1.984-2.015zm-5.997 2.001c1.102-.01 2.008.877 2.012 1.983.012 1.106-.88 2.005-1.98 2.016-1.106.007-2.009-.881-2.016-1.988-.009-1.103.877-2.004 1.984-2.011zm-2.003 5.998c1.106-.007 2.01.882 2.016 1.985.01 1.104-.88 2.008-1.986 2.015-1.105.008-2.005-.88-2.011-1.985-.011-1.105.879-2.004 1.981-2.015zm10.031 8.532c.021 2.239-.882 3.718-1.682 4.587l-.046.044c5.255-.591 9.062-4.304 6.266-7.889-1.373 2.047-2.534 2.442-4.538 3.258z"/>\n\t\t\t\t</svg>\n\t\t\t\t<button id="custom_colors_add" class="remove_outline" name="add-a-custom-color">\n\t\t\t\t\t<svg viewBox="0 -2 24 24" width="14" height="16">\n\t\t\t\t\t\t<path fill="#555" d="M24 10h-10v-10h-4v10h-10v4h10v10h4v-10h10z"/>\n\t\t\t\t\t</svg>\n\t\t\t\t</button>\n\t\t\t</div>\n\t\t\t<div id="custom_colors_box">\n\t\t\t</div>\n\t\t</div>\n\t\t<div id="color_context_menu" class="color_ctx_menu">\n\t\t\t<button id="color_clear_single" class="color_ctx_menu" name="remove-single-color">Remove</button>\n\t\t\t<button id="color_clear_all" class="color_ctx_menu" name="remove-all-colors">Remove All</button>\n\t\t</div>\n\t',document.getElementsByTagName("BODY")[0].appendChild(t),null===localStorage.getItem("custom_colors"))localStorage.setItem("custom_colors",'{"0": []}');else{colorPickerComp.LSCustomColors=JSON.parse(localStorage.getItem("custom_colors"));for(let t=colorPickerComp.LSCustomColors[0].length-1;t>=0;t--){let e=document.createElement("BUTTON");e.className="custom_colors_preview",e.style.background=colorPickerComp.LSCustomColors[0][t],e.setAttribute("data-custom-color",colorPickerComp.LSCustomColors[0][t]),document.getElementById("custom_colors_box").appendChild(e),19==t&&(document.getElementById("custom_colors_add").style.display="none")}28==colorPickerComp.LSCustomColors[0].length&&(document.getElementById("custom_colors_add").style.display="none")}}(),colorPickerComp.keyShortcuts=function(t){for(let t in document.getElementsByTagName("INPUT"))if(!isNaN(t)&&document.getElementsByTagName("INPUT")[t]===document.activeElement)return;for(let t in document.getElementsByTagName("TEXTAREA"))if(!isNaN(t)&&document.getElementsByTagName("TEXTAREA")[t]===document.activeElement)return;switch(t.keyCode){case 46:"custom_colors_preview"==document.activeElement.className&&colorPickerComp.clearSingleCustomColor(document.activeElement);break;case 27:colorPickerComp.pickerOpen&&closePicker();break;case 9:let t=document.getElementsByClassName("remove_outline");for(;t.length>0;)t[0].classList.add("add_outline"),t[0].classList.remove("remove_outline"),t=document.getElementsByClassName("remove_outline")}},document.addEventListener("keydown",colorPickerComp.keyShortcuts.bind(event)),document.addEventListener("mousedown",(function(){let t=document.getElementsByClassName("add_outline");for(;t.length>0;)t[0].classList.add("remove_outline"),t[0].classList.remove("add_outline"),t=document.getElementsByClassName("add_outline")})),document.addEventListener("mousedown",(function(){"color_context_menu"!=event.target.id&&(document.getElementById("color_context_menu").style.display="none")}));let closePicker=function(){colorPickerComp.pickerOpen=!1,document.getElementById("color_picker").style.display="none","undefined"!=colorPickerComp.instance.element.getAttribute("data-color")&&updatePicker()},updatePicker=function(){colorPickerComp.colorChange({h:colorPickerComp.hue,s:colorPickerComp.saturation,l:colorPickerComp.lightness,a:colorPickerComp.alpha})};document.addEventListener("mousedown",(function(){let t=event.target;if(colorPickerComp.pickerOpen)for(;t!=document.getElementById("color_picker");){if("HTML"==t.tagName){closePicker();break}t=t.parentNode}})),document.addEventListener("scroll",(function(){colorPickerComp.pickerOpen&&closePicker()})),window.addEventListener("resize",(function(){colorPickerComp.pickerOpen&&closePicker()})),colorPickerComp.colorChange=function(t,e){"string"==typeof t&&(t=colorPickerComp.hexAToRGBA(t,!0));const o=colorPickerComp.HSLAToRGBA(t.h,t.s,t.l,t.a),l=colorPickerComp.HSLAToRGBA(t.h,t.s,t.l,t.a,!0),c=new CustomEvent("colorChange",{detail:{color:{hsl:`hsla(${t.h}, ${t.s}%, ${t.l}%)`,rgb:`rgba(${o.r}, ${o.g}, ${o.b})`,hex:l,hsla:`hsla(${t.h}, ${t.s}%, ${t.l}%, ${t.a})`,rgba:`rgba(${o.r}, ${o.g}, ${o.b}, ${o.a})`,hexa:l}}}),r=void 0===e?colorPickerComp.instance.element:e;r.setAttribute("data-color",l),r.style.background=l,r.dispatchEvent(c)},colorPickerComp.HSLAToRGBA=function(t,e,o,l,c){e/=100,o/=100;let r=(1-Math.abs(2*o-1))*e,n=r*(1-Math.abs(t/60%2-1)),i=o-r/2,s=0,a=0,u=0;return 0<=t&&t<60?(s=r,a=n,u=0):60<=t&&t<120?(s=n,a=r,u=0):120<=t&&t<180?(s=0,a=r,u=n):180<=t&&t<240?(s=0,a=n,u=r):240<=t&&t<300?(s=n,a=0,u=r):300<=t&&t<360&&(s=r,a=0,u=n),s=Math.round(255*(s+i)),a=Math.round(255*(a+i)),u=Math.round(255*(u+i)),!0===c?colorPickerComp.RGBAToHexA(s,a,u,l):{r:s,g:a,b:u,a:l}},colorPickerComp.RGBAToHSLA=function(t,e,o,l){t/=255,e/=255,o/=255,l=null==l?1:l;let c=Math.min(t,e,o),r=Math.max(t,e,o),n=r-c,i=0,s=0,a=0;return i=0==n?0:r==t?(e-o)/n%6:r==e?(o-t)/n+2:(t-e)/n+4,i=Math.round(60*i),i<0&&(i+=360),a=(r+c)/2,s=0==n?0:n/(1-Math.abs(2*a-1)),s=+(100*s).toFixed(1),a=+(100*a).toFixed(1),{h:i,s:s,l:a,a:l}},colorPickerComp.RGBAToHexA=function(t,e,o,l){return t=t.toString(16),e=e.toString(16),o=o.toString(16),l=Math.round(255*l).toString(16),1==t.length&&(t="0"+t),1==e.length&&(e="0"+e),1==o.length&&(o="0"+o),1==l.length&&(l="0"+l),"ff"==l?"#"+t+e+o:"#"+t+e+o+l},colorPickerComp.hexAToRGBA=function(t,e){7==t.length?t+="ff":4==t.length&&(t+=t.substring(1,4)+"ff");let o=0,l=0,c=0,r=1;return 5==t.length?(o="0x"+t[1]+t[1],l="0x"+t[2]+t[2],c="0x"+t[3]+t[3],r="0x"+t[4]+t[4]):9==t.length&&(o="0x"+t[1]+t[2],l="0x"+t[3]+t[4],c="0x"+t[5]+t[6],r="0x"+t[7]+t[8]),r=+(r/255).toFixed(3),!0===e?colorPickerComp.RGBAToHSLA(+o,+l,+c,r):"rgba("+ +o+","+ +l+","+ +c+","+r+")"},colorPickerComp.switchColorType=function(){if("HEXA"==colorPickerComp.colorTypeStatus){colorPickerComp.colorTypeStatus="RGBA",document.getElementById("hexa").style.display="none",document.getElementById("rgba").style.display="block";const t=colorPickerComp.HSLAToRGBA(colorPickerComp.hue,colorPickerComp.saturation,colorPickerComp.lightness,colorPickerComp.alpha);document.getElementsByClassName("rgba_input")[0].value=t.r,document.getElementsByClassName("rgba_input")[1].value=t.g,document.getElementsByClassName("rgba_input")[2].value=t.b,document.getElementsByClassName("rgba_input")[3].value=t.a}else if("RGBA"==colorPickerComp.colorTypeStatus)colorPickerComp.colorTypeStatus="HSLA",document.getElementById("rgba").style.display="none",document.getElementById("hsla").style.display="block",document.getElementsByClassName("hsla_input")[0].value=colorPickerComp.hue,document.getElementsByClassName("hsla_input")[1].value=colorPickerComp.saturation,document.getElementsByClassName("hsla_input")[2].value=colorPickerComp.lightness,document.getElementsByClassName("hsla_input")[3].value=colorPickerComp.alpha;else if("HSLA"==colorPickerComp.colorTypeStatus){colorPickerComp.colorTypeStatus="HEXA",document.getElementById("hsla").style.display="none",document.getElementById("hexa").style.display="block";const t=colorPickerComp.HSLAToRGBA(colorPickerComp.hue,colorPickerComp.saturation,colorPickerComp.lightness,colorPickerComp.alpha,!0);document.getElementById("hex_input").value=t}},document.getElementById("switch_color_type").addEventListener("click",(function(){colorPickerComp.switchColorType()})),document.getElementById("hex_input").addEventListener("blur",(function(){const t=this.value;t.match(/^#[0-9a-f]{3}([0-9a-f]{3})?([0-9a-f]{2})?$/)&&(colorPickerComp.updateColorDisplays(t),updatePicker())})),document.querySelectorAll(".rgba_input").forEach(t=>{t.addEventListener("change",(function(){const t=document.querySelectorAll(".rgba_input");if(t[0].value>255)throw"Value must be below 256";if(t[1].value>255)throw"Value must be below 256";if(t[2].value>255)throw"Value must be below 256";if(t[3].value>1)throw"Value must be equal to or below 1";colorPickerComp.updateColorDisplays(`rgba(${t[0].value}, ${t[1].value}, ${t[2].value}, ${t[3].value})`),updatePicker()}))}),document.querySelectorAll(".hsla_input").forEach(t=>{t.addEventListener("change",(function(){const t=document.querySelectorAll(".hsla_input");if(t[0].value>359)throw"Value must be below 360";if(t[1].value>100)throw"Value must be below 100";if(t[2].value>100)throw"Value must be below 100";if(t[3].value>1)throw"Value must be equal to or below 1";colorPickerComp.updateColorDisplays(`hsla(${t[0].value}, ${t[1].value}%, ${t[2].value}%, ${t[3].value})`),updatePicker()}))}),colorPickerComp.getCustomColors=function(){return colorPickerComp.LSCustomColors()},document.getElementById("custom_colors_box").addEventListener("click",(function(t){if("custom_colors_preview"==t.target.className){const e=t.target.getAttribute("data-custom-color");colorPickerComp.updateColorDisplays(e),updatePicker()}})),colorPickerComp.addCustomColor=function(){19==colorPickerComp.LSCustomColors[0].length&&(document.getElementById("custom_colors_add").style.display="none");const t=`hsla(${colorPickerComp.hue}, ${colorPickerComp.saturation}%, ${colorPickerComp.lightness}%, ${colorPickerComp.alpha})`;let e=document.createElement("BUTTON");e.className="custom_colors_preview",e.style.background=t,e.setAttribute("data-custom-color",t),document.getElementById("custom_colors_box").appendChild(e),colorPickerComp.LSCustomColors[0].unshift(t),localStorage.setItem("custom_colors",JSON.stringify(colorPickerComp.LSCustomColors))},document.getElementById("custom_colors_add").addEventListener("click",(function(){colorPickerComp.addCustomColor()})),document.getElementById("custom_colors_box").addEventListener("contextmenu",(function(t){if("custom_colors_preview"==t.target.className){t.preventDefault();const e=document.getElementById("color_context_menu");e.style.display="block",e.style.top=t.target.getBoundingClientRect().top+25+"px",e.style.left=t.target.getBoundingClientRect().left+"px",colorPickerComp.contextMenuElem=t.target}})),colorPickerComp.clearSingleCustomColor=function(t){const e=void 0===t?colorPickerComp.contextMenuElem:t;document.getElementById("custom_colors_box").removeChild(e),colorPickerComp.LSCustomColors={0:[]};for(let t in document.getElementsByClassName("custom_colors_preview"))!0!==isNaN(t)&&colorPickerComp.LSCustomColors[0].push(document.getElementsByClassName("custom_colors_preview")[t].getAttribute("data-custom-color"));localStorage.setItem("custom_colors",JSON.stringify(colorPickerComp.LSCustomColors)),document.getElementById("custom_colors_add").style.display="inline-block"},document.getElementById("color_clear_single").addEventListener("mousedown",(function(){colorPickerComp.clearSingleCustomColor()})),colorPickerComp.clearAllCustomColors=function(){for(colorPickerComp.LSCustomColors={0:[]};document.getElementsByClassName("custom_colors_preview").length>0;)document.getElementById("custom_colors_box").removeChild(document.getElementsByClassName("custom_colors_preview")[0]);localStorage.setItem("custom_colors",JSON.stringify(colorPickerComp.LSCustomColors)),document.getElementById("custom_colors_add").style.display="inline-block"},document.getElementById("color_clear_all").addEventListener("mousedown",(function(){colorPickerComp.clearAllCustomColors()})),colorPickerComp.colorSliderHandler=function(t){const e=document.getElementById("color_slider"),o=document.getElementById("color_slider_dragger");let l=t-e.getBoundingClientRect().left;l<11&&(l=11),l>255&&(l=255),o.attributes.x.nodeValue=l;const c=(l-11)/244*100,r=Math.round(359-3.59*c);colorPickerComp.hue=r,document.getElementById("saturation").children[1].setAttribute("stop-color",`hsla(${r}, 100%, 50%, ${colorPickerComp.alpha})`),colorPickerComp.updateColorValueInput(),colorPickerComp.instance.element.setAttribute("data-color","color"),updatePicker()},document.getElementById("color_slider").addEventListener("mousedown",(function(t){colorPickerComp.sliderStatus=!0,colorPickerComp.colorSliderHandler(t.pageX)})),document.addEventListener("mousemove",(function(t){!0===colorPickerComp.sliderStatus&&colorPickerComp.colorSliderHandler(t.pageX)})),document.addEventListener("mouseup",(function(){!0===colorPickerComp.sliderStatus&&(colorPickerComp.sliderStatus=!1)})),document.getElementById("color_slider").addEventListener("touchstart",(function(t){colorPickerComp.sliderStatusTouch=!0,colorPickerComp.colorSliderHandler(t.changedTouches[0].clientX)}),{passive:!0}),document.addEventListener("touchmove",(function(){!0===colorPickerComp.sliderStatusTouch&&(event.preventDefault(),colorPickerComp.colorSliderHandler(event.changedTouches[0].clientX))}),{passive:!1}),document.addEventListener("touchend",(function(){!0===colorPickerComp.sliderStatusTouch&&(colorPickerComp.sliderStatusTouch=!1)})),colorPickerComp.opacitySliderHandler=function(t){const e=document.getElementById("opacity_slider"),o=document.getElementById("opacity_slider_dragger");let l=t-e.getBoundingClientRect().left;l<11&&(l=11),l>255&&(l=255),o.attributes.x.nodeValue=l;let c=.01*((l-11)/244*100);c=Number(Math.round(c+"e2")+"e-2"),colorPickerComp.alpha=c,colorPickerComp.updateColorValueInput(),colorPickerComp.instance.element.setAttribute("data-color","color"),updatePicker()},document.getElementById("opacity_slider").addEventListener("mousedown",(function(t){colorPickerComp.opacityStatus=!0,colorPickerComp.opacitySliderHandler(t.pageX)})),document.addEventListener("mousemove",(function(t){!0===colorPickerComp.opacityStatus&&colorPickerComp.opacitySliderHandler(t.pageX)})),document.addEventListener("mouseup",(function(){!0===colorPickerComp.opacityStatus&&(colorPickerComp.opacityStatus=!1)})),document.getElementById("opacity_slider").addEventListener("touchstart",(function(t){colorPickerComp.opacityStatusTouch=!0,colorPickerComp.opacitySliderHandler(t.changedTouches[0].clientX)}),{passive:!0}),document.addEventListener("touchmove",(function(){!0===colorPickerComp.opacityStatusTouch&&(event.preventDefault(),colorPickerComp.opacitySliderHandler(event.changedTouches[0].clientX))}),{passive:!1}),document.addEventListener("touchend",(function(){!0===colorPickerComp.opacityStatusTouch&&(colorPickerComp.opacityStatusTouch=!1)})),colorPickerComp.colorBoxHandler=function(t,e,o){const l=document.getElementById("color_box"),c=document.getElementById("box_dragger");let r=t-l.getBoundingClientRect().left,n=!0===o?e-l.getBoundingClientRect().top:e-l.getBoundingClientRect().top-document.getElementsByTagName("HTML")[0].scrollTop;r<14&&(r=14),r>252&&(r=252),n<14&&(n=14),n>119&&(n=119),c.attributes.y.nodeValue=n,c.attributes.x.nodeValue=r;const i=Math.round((r-15)/238*100),s=100-i/2,a=100-(n-15)/105*100,u=Math.floor(a/100*s);colorPickerComp.saturation=i,colorPickerComp.lightness=u,colorPickerComp.updateColorValueInput(),colorPickerComp.instance.element.setAttribute("data-color","color"),updatePicker()},document.getElementById("color_box").addEventListener("mousedown",(function(t){colorPickerComp.boxStatus=!0,colorPickerComp.colorBoxHandler(t.pageX,t.pageY)})),document.addEventListener("mousemove",(function(t){!0===colorPickerComp.boxStatus&&colorPickerComp.colorBoxHandler(t.pageX,t.pageY)})),document.addEventListener("mouseup",(function(t){!0===colorPickerComp.boxStatus&&(colorPickerComp.boxStatus=!1)})),document.getElementById("color_box").addEventListener("touchstart",(function(t){colorPickerComp.boxStatusTouch=!0,colorPickerComp.colorBoxHandler(t.changedTouches[0].clientX,t.changedTouches[0].clientY,!0)}),{passive:!0}),document.addEventListener("touchmove",(function(){!0===colorPickerComp.boxStatusTouch&&(event.preventDefault(),colorPickerComp.colorBoxHandler(event.changedTouches[0].clientX,event.changedTouches[0].clientY,!0))}),{passive:!1}),document.addEventListener("touchend",(function(){!0===colorPickerComp.boxStatusTouch&&(colorPickerComp.boxStatusTouch=!1)})),colorPickerComp.updateColorDisplays=function(t){if("undefined"==t||t==="")t={h:0,s:100,l:50,a:1};else if("#"==t.substring(0,1))t=colorPickerComp.hexAToRGBA(t,!0);else if("r"==t.substring(0,1)){const e=t.match(/[.?\d]+/g);e[3]=null==e[3]?1:e[3],t=colorPickerComp.RGBAToHSLA(e[0],e[1],e[2],e[3])}else{const e=t.match(/[.?\d]+/g);e[3]=null==e[3]?1:e[3],t={h:e[0],s:e[1],l:e[2],a:e[3]}}colorPickerComp.hue=t.h,colorPickerComp.saturation=t.s,colorPickerComp.lightness=t.l,colorPickerComp.alpha=t.a,colorPickerComp.updateColorValueInput(),document.getElementById("saturation").children[1].setAttribute("stop-color",`hsl(${t.h}, 100%, 50%)`);const e=document.getElementById("box_dragger");let o=2.38*t.s+14;let l=1.05*(100-t.l/(100-t.s/2)*100)+14;o<14&&(o=14),o>252&&(o=252),l<14&&(l=14),l>119&&(l=119),e.attributes.x.nodeValue=o,e.attributes.y.nodeValue=l;const c=document.getElementById("color_slider_dragger");let r=2.44*(100-t.h/359*100)+11;c.attributes.x.nodeValue=r;const n=document.getElementById("opacity_slider_dragger");let i=100*t.a*2.44+11;n.attributes.x.nodeValue=i},colorPickerComp.updateColorValueInput=function(){if("HEXA"==colorPickerComp.colorTypeStatus){const t=colorPickerComp.HSLAToRGBA(colorPickerComp.hue,colorPickerComp.saturation,colorPickerComp.lightness,colorPickerComp.alpha,!0);document.getElementById("hex_input").value=t}else if("RGBA"==colorPickerComp.colorTypeStatus){const t=colorPickerComp.HSLAToRGBA(colorPickerComp.hue,colorPickerComp.saturation,colorPickerComp.lightness,colorPickerComp.alpha);document.getElementsByClassName("rgba_input")[0].value=t.r,document.getElementsByClassName("rgba_input")[1].value=t.g,document.getElementsByClassName("rgba_input")[2].value=t.b,document.getElementsByClassName("rgba_input")[3].value=t.a}else document.getElementsByClassName("hsla_input")[0].value=colorPickerComp.hue,document.getElementsByClassName("hsla_input")[1].value=colorPickerComp.saturation,document.getElementsByClassName("hsla_input")[2].value=colorPickerComp.lightness,document.getElementsByClassName("hsla_input")[3].value=colorPickerComp.alpha};			
	}
			var modal = stockdio_get_modal_colors_body();
		js_stockdio_form = $(e).closest('div');	
		js_stockdio_form.append(modal);
		


		js_stockdio_form.find(".stockdio_input_field_color").each(function(){
			var obj = $(this);
			var label = obj.data("label");
			var name = obj.data("name");
			var colorTest = $(stockdio_get_modal_single_color(label,name));
			js_stockdio_form.find(".stockdio_cc_container").append(colorTest);

			var picker = colorTest.find(".component-color-indicator")[0];
			let stockdio_colorObj = new ColorPicker(picker,$(this).val() );
			picker.addEventListener("colorChange", function (event) {
				const color = event.detail.color.hexa;		
				$(picker).closest(".stockdio_color_component").find("input[type='text']").val(color);
			});
			colorTest.find(".stockdio_colorpicker").click(function(){
				$(picker).click();
			})
			$(picker).closest(".stockdio_color_component").find("input[type='text']").val($(this).val() );
		});


		

	}

	stockdio_get_modal_colors_body = function(){
		return `
		<div tabindex="-1" class="s_s_colors_modal"><div><div class="components-modal__screen-overlay"><div class="components-modal__frame stockdio_modal_colors" role="dialog" aria-labelledby="components-modal-header-6" tabindex="-1"><div class="components-modal__content" role="document"><div class="components-modal__header"><div class="components-modal__header-heading-container"><h1 id="components-modal-header-6" class="components-modal__header-heading">Ticker Layouts</h1></div><button onclick="stockdio_get_modal_single_color_close()" type="button" class="components-button has-icon" aria-label="Close dialog"><svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false"><path d="M13 11.8l6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z"></path></svg></button></div><div class="stockdio_cc_note"><p> NOTE: scroll down to see other ticker layouts.</p></div><div class="stockdio_cc_container"></div><div class="stockdio_cc_note"><p> NOTE: colors will be applied when this dialog is closed.</p></div></div></div></div></div></div>
		`;
	}

	stockdio_get_modal_single_color = function(label,name){
		return `
		<div class="stockdio_color_component s_c_p_${name}"><label class="stockdio_color_component_label">${label}</label><div class="stockdio_div_custom_color"><div class="stockdio_div_inline_comp stockdio_color_indicator_parent"><span class="component-color-indicator"></span></div><div class="stockdio_div_inline_comp stockdio_color_component_input_parent"><div class="components-base-control"><div class="components-base-control__field"><input data-name="${name}" class="components-text-control__input s_s_color_input" type="text"  value=""></div></div></div><div class="stockdio_div_inline_comp stockdio_cpalette"><div class="components-dropdown components-color-palette__item-wrapper components-color-palette__custom-color"><button type="button" aria-expanded="false" class="components-color-palette__item stockdio_colorpicker" aria-label="${label} Picker"><span class="components-color-palette__custom-color-gradient"></span></button></div></div></div></div>
		`;
	}

	stockdio_get_modal_single_color_close = function(){
		$(".stockdio_color_component").find("input[type='text']").each(function(){
			var name = $(this).data("name");
			js_stockdio_form.find(`.s_${name}`).val($(this).val())
		});
		if (js_stockdio_form.find(".default_symbols").length>0){
			js_stockdio_form.find(".default_symbols").change();
		}
		if (js_stockdio_form){
			js_stockdio_form.find(".s_s_colors_modal").remove();
		}
	}

	stockdio_widget_init = function(){
		if ($(".default_exchange").length>0){
				$(".default_exchange option:selected").each(function(){
				var exhObj1=$(this);
				if ($("div.widget").length>0){
			    	exhObj1.closest("div.widget").find(".s_b_exch_name").text(exhObj1.text());
					exhObj1.closest("div.widget").find(".s_b_exch_code").text( exhObj1.val());
				}
				else{
					exhObj1.closest("form").find(".s_b_exch_name").text(exhObj1.text());
					exhObj1.closest("form").find(".s_b_exch_code").text( exhObj1.val());
				}
			});
			
		}
		
	}
	stockdio_copy_clipboard = function(e){
		var text = $(e).parent().find("input").val();
		/* Get the text field */
		const el = document.createElement('textarea');
		el.value = text;
		document.body.appendChild(el);
		el.select();
		document.execCommand('copy');
		document.body.removeChild(el);
  }

} ());