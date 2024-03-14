( function( blocks, editor, i18n, element, components, _ ) {
	var el = element.createElement;
	var RichText = editor.RichText;
	var MediaUpload = editor.MediaUpload;
	var currentdate = new Date();
	var pro_feature_text="If you want to use this feature upgrade to Like Box Pro";
	
	var icon_iamge = el( 'img', {
      width: 24,
      height: 24,
      src: window['wpda_likebox_gutenberg']["other_data"]["icon_src"],
	  className: "wpda_likebox_gutenberg_icon"
    } );
	
	blocks.registerBlockType( 'wpdevart-likebox/likebox', {
		title: 'WpDevArt Like box',
		icon: icon_iamge ,
		category: 'embed',
		keywords:['like','facebook','box','social'],
		attributes: {			
			open_or_close: {
				type: 'boolean',
				value: true,
				default: true
			},
			version: {
				type: 'string',
				value: "",
				default: ""
			},
			
			like_box_profile_id:{
				type: 'string',
				value: "",
				default: "",
			},
			animation_efect:{
				type: 'string',
				value: "",
				default: "none",
			},
			show_border:{
				type: 'string',
				value: "",
				default: "show",
			},
			border_color:{
				type: 'string',
				value: "",
				default: "#ffffff",
			},
			stream:{
				type: 'string',
				value: "",
				default: "hide",
			},
			connections:{
				type: 'string',
				value: "",
				default: "show",
			},
			width:{
				type: 'string',
				value: "",
				default: "300",
			},
			height:{
				type: 'string',
				value: "",
				default: "550",
			},
			header:{
				type: 'string',
				value: "",
				default: "small",
			},
			cover_photo:{
				type: 'string',
				value: "",
				default: "show",
			},
			locale:{
				type: 'string',
				value: "",
				default: "en_US",
			},
		},
		edit: function( props ) {
			props.setAttributes({version:'1.0'})// begin is genereting save function;			
			return el( 'span', { },create_open_hide_block()
					 
					 );			
			
			function create_open_hide_block(){
				var open_or_close_class="";
				if(props.attributes.open_or_close===false){
					open_or_close_class=" closed_params";
				}
				return el("div",{className:"wpdevart_likebox_main_collapsible_element"+open_or_close_class},create_head(),create_content())
			}
			
			function create_head(){
				return el("div",{className:"head_block",onClick:function(value){open_close_element(value)}},
						  el("span",{className:"title_image"},
									 el("img",{src:wpda_likebox_gutenberg['other_data']['content_icon']})
						  ),
						  el("span",{className:"wpdevar_likebox_head_title"},"WpDevArt Like box"

						  ),
						  el("span",{className:"open_or_closed"},

						  ),
					   );
			}
			
			function create_content(){
				var wpda_likebox_fields=new Array();				
				var font_familis={"Arial,Helvetica Neue,Helvetica,sans-serif":"Arial *","Arial Black,Arial Bold,Arial,sans-serif":"Arial Black *","Arial Narrow,Arial,Helvetica Neue,Helvetica,sans-serif":"Arial Narrow *","Courier,Verdana,sans-serif":"Courier *","Georgia,Times New Roman,Times,serif":"Georgia *","Times New Roman,Times,Georgia,serif":"Times New Roman *","Trebuchet MS,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Arial,sans-serif":"Trebuchet MS *","Verdana,sans-serif":"Verdana *","American Typewriter,Georgia,serif":"American Typewriter","Andale Mono,Consolas,Monaco,Courier,Courier New,Verdana,sans-serif":"Andale Mono","Baskerville,Times New Roman,Times,serif":"Baskerville","Bookman Old Style,Georgia,Times New Roman,Times,serif":"Bookman Old Style","Calibri,Helvetica Neue,Helvetica,Arial,Verdana,sans-serif":"Calibri","Cambria,Georgia,Times New Roman,Times,serif":"Cambria","Candara,Verdana,sans-serif":"Candara","Century Gothic,Apple Gothic,Verdana,sans-serif":"Century Gothic","Century Schoolbook,Georgia,Times New Roman,Times,serif":"Century Schoolbook","Consolas,Andale Mono,Monaco,Courier,Courier New,Verdana,sans-serif":"Consolas","Constantia,Georgia,Times New Roman,Times,serif":"Constantia","Corbel,Lucida Grande,Lucida Sans Unicode,Arial,sans-serif":"Corbel","Franklin Gothic Medium,Arial,sans-serif":"Franklin Gothic Medium","Garamond,Hoefler Text,Times New Roman,Times,serif":"Garamond","Gill Sans MT,Gill Sans,Calibri,Trebuchet MS,sans-serif":"Gill Sans MT","Helvetica Neue,Helvetica,Arial,sans-serif":"Helvetica Neue","Hoefler Text,Garamond,Times New Roman,Times,sans-serif":"Hoefler Text","Lucida Bright,Cambria,Georgia,Times New Roman,Times,serif":"Lucida Bright","Lucida Grande,Lucida Sans,Lucida Sans Unicode,sans-serif":"Lucida Grande","monospace":"monospace","Palatino Linotype,Palatino,Georgia,Times New Roman,Times,serif":"Palatino Linotype","Tahoma,Geneva,Verdana,sans-serif":"Tahoma","Rockwell, Arial Black, Arial Bold, Arial, sans-serif":"Rockwell"};
				var animation_efects={"none":"none","random":"random","bounce":"bounce","flash":"flash","pulse":"pulse","rubberBand":"rubberBand","shake":"shake","swing":"swing","tada":"tada","wobble":"wobble","bounceIn":"bounceIn","bounceInDown":"bounceInDown","bounceInLeft":"bounceInLeft","bounceInRight":"bounceInRight","bounceInUp":"bounceInUp","fadeIn":"fadeIn","fadeInDown":"fadeInDown","fadeInDownBig":"fadeInDownBig","fadeInLeft":"fadeInLeft","fadeInLeftBig":"fadeInLeftBig","fadeInRight":"fadeInRight","fadeInRightBig":"fadeInRightBig","fadeInUp":"fadeInUp","fadeInUpBig":"fadeInUpBig","flip":"flip","flipInX":"flipInX","flipInY":"flipInY","lightSpeedIn":"lightSpeedIn","rotateIn":"rotateIn","rotateInDownLeft":"rotateInDownLeft","rotateInDownRight":"rotateInDownRight","rotateInUpLeft":"rotateInUpLeft","rotateInUpRight":"rotateInUpRight","rollIn":"rollIn","zoomIn":"zoomIn","zoomInDown":"zoomInDown","zoomInLeft":"zoomInLeft","zoomInRight":"zoomInRight","zoomInUp":"zoomInUp"};

				
				wpda_likebox_fields.push(wpda_likebox_lb_simple_input("like_box_profile_id","Page ID:","Type here your Facebook like box page ID or URL(without https://www.facebook.com/, if your Facebook page URL is https://www.facebook.com/uefacom then type here just uefacom)."));
				wpda_likebox_fields.push(wpda_likebox_lb_simple_select("animation_efect",animation_efects,"Like box Animation:","Select the animation type of the Facebook like box.",true));
				wpda_likebox_fields.push(wpda_likebox_lb_simple_select("show_border",{"show":"Show","hide":"Hide"},"Show/Hide Like box border:","Show/Hide Facebook like box border.",true));
				wpda_likebox_fields.push(wpda_likebox_lb_color_input("border_color","Like box Border color:","Type the Border Color of your Facebook Like box.",true));
				wpda_likebox_fields.push(wpda_likebox_lb_simple_select("stream",{"show":"Show","hide":"Hide"},"Show/Hide Page Posts:","Show/Hide the Page Posts from the Facebook Like box."));
				wpda_likebox_fields.push(wpda_likebox_lb_simple_select("connections",{"show":"Show","hide":"Hide"},"Select to Show/Hide Friends Faces:","Select to Show/Hide Friends Faces"));
				wpda_likebox_fields.push(wpda_likebox_lb_simple_input("width","Like box Width:","Type Facebook Like box width"));
				wpda_likebox_fields.push(wpda_likebox_lb_simple_input("height","Like box Height:","Type Facebook Like box height"));
				wpda_likebox_fields.push(wpda_likebox_lb_simple_select("header",{"small":"Small","big":"Big"},"Select Like box Header size","Select Like box Header size"));
				wpda_likebox_fields.push(wpda_likebox_lb_simple_select("cover_photo",{"show":"Show","hide":"Hide"},"Like box cover photo","Select to show/hide Like box cover photo"));
				wpda_likebox_fields.push(wpda_likebox_lb_simple_input_with_small("locale","Like box language:","Type the Facebook Like box language code.",'(en_US, de_DE...)'));

				var table=el("tabel",{className:"wpdevart_likebox_content_block"},wpda_likebox_fields)
				return el("div",{className:"wpdevart_likebox_content_block"},table)
			}
			
			function open_close_element(colapsible_element){
				var target=colapsible_element.target;
				var head_element;
				if(target.parentNode.classList[0]=="wpdevart_likebox_main_collapsible_element"){
				   head_element=target.parentNode;
				}
				if(target.parentNode.parentNode.classList[0]=="wpdevart_likebox_main_collapsible_element"){
				   head_element=target.parentNode.parentNode;
				}
				if(target.parentNode.parentNode.parentNode.classList[0]=="wpdevart_likebox_main_collapsible_element"){
				   head_element=target.parentNode.parentNode.parentNode;
				}
				if(target.parentNode.parentNode.parentNode.parentNode.classList[0]=="wpdevart_likebox_main_collapsible_element"){
				   head_element=target.parentNode.parentNode.parentNode.parentNode;
				}
				if(typeof(head_element.classList[1])=="undefined"){
					props.setAttributes( { open_or_close:false } );
					head_element.classList.add("closed_params");
				}else{
					props.setAttributes( { open_or_close:true } );
					head_element.classList.remove("closed_params");
				}
			}
			
			function wpda_likebox_lb_simple_input(element_name,element_title,element_description,pro_feature=false,aditional_css={},aditional_classes=""){
				return el('tr',{className:"wpda_simple_input_tr "+"wpda_likebox_"+element_name+" "+aditional_classes,style:aditional_css},
						  wpda_likebox_title_and_description(element_title,element_description,pro_feature),
						  el('td',{className:"wpda_simple_input_td"},
							el('input',{type:"text",Value:props.attributes[element_name],onMouseDown:function(){if(pro_feature){alert(pro_feature_text); return false;}},className:'wpda_simple_input',onChange: function( value ) {var select=value.target; var params={}; params[element_name]=select.value;  props.setAttributes(params)}})
						  )
						
						);			 	
					 	 
			}
			
			function wpda_likebox_lb_simple_input_with_small(element_name,element_title,element_description,pro_feature=false,small_text,aditional_css={},aditional_classes=""){
				return el('tr',{adgsdfghs:"dfghdfhjsghsfdg",className:"wpda_simple_input_tr "+"wpda_likebox_"+element_name+" "+aditional_classes,style:aditional_css},
						  wpda_likebox_title_and_description(element_title,element_description,pro_feature),
						  el('td',{className:"wpda_simple_input_td"},
							el('input',{type:"text",Value:props.attributes[element_name],onMouseDown:function(){if(pro_feature){alert(pro_feature_text); return false;}},className:'wpda_simple_input',onChange: function( value ) {var select=value.target; var params={}; params[element_name]=select.value;  props.setAttributes(params)}}),
							el('small',{className:'wpda_likebox_small_text'},small_text)
						  )
						
						);			 	
					 	 
			}
			
			function wpda_likebox_lb_simple_textarea(element_name,element_title,element_description,pro_feature=false,aditional_css={},aditional_classes=""){
				return el('tr',{className:"wpda_simple_input_tr "+"wpda_likebox_"+element_name+" "+aditional_classes,style:aditional_css},
						  wpda_likebox_title_and_description(element_title,element_description,pro_feature),
						  el('td',{className:"wpda_simple_input_td"},
							el('textarea',{type:"text",className:'wpda_simple_input',onMouseDown:function(){if(pro_feature){alert(pro_feature_text); return false;}},onChange: function( value ) {var select=value.target; var params={}; params[element_name]=select.value; props.setAttributes(params)}},props.attributes[element_name])
						  )
						
						);			 	
					 	 
			}
			
			function wpda_likebox_lb_simple_select(element_name,options_list,element_title,element_description,pro_feature=false,aditional_css={},aditional_classes=""){
				var created_options=new Array();
				for(var key in options_list) {
					selected_option=false;
					if(props.attributes[element_name]==key){
						selected_option=true;
					}
					created_options.push(el('option',{value:''+key+'',selected:selected_option},options_list[key]))
				}
				return el('tr',{className:"wpda_simple_input_tr "+"wpda_likebox_"+element_name+" "+aditional_classes, style:aditional_css},
						  wpda_likebox_title_and_description(element_title,element_description,pro_feature),
						  el('td',{className:"wpda_simple_input_td"},
							el( 'select', { className: "wpda_likebox_select",onMouseDown:function(){if(pro_feature){alert(pro_feature_text); return false;}},onChange: function( value ) {var select=value.target; var params={};  params[element_name]=select.options[select.selectedIndex].value;  props.setAttributes( params)}},created_options),
						  )						
						);			 	
					 	 
			}
			
			function wpda_likebox_lb_select_open_hide_params(element_name,options_list,open_closed_ids,element_title,element_description,pro_feature=false,aditional_css={},aditional_classes=""){
				var created_options=new Array();
				
				for(var key in options_list) {
					selected_option=false;
					if(props.attributes[element_name]==key){
						selected_option=true;
					}
					created_options.push(el('option',{value:''+key+'',selected:selected_option},options_list[key]))
				}
				return el('tr',{className:"wpda_simple_input_tr "+"wpda_likebox_"+element_name},
						  wpda_likebox_title_and_description(element_title,element_description,pro_feature),
						  el('td',{className:"wpda_simple_input_td"},
							el( 'select', { className: "wpda_likebox_select",onMouseDown:function(){if(pro_feature){alert(pro_feature_text); return false;}},onChange: function( value ) {
									var select=value.target;
									var curent_element_parent_div=select.parentNode.parentNode.parentNode;
									var params={};
									params[element_name]=select.options[select.selectedIndex].value;
								
									for(var i=0;i<open_closed_ids.length;i++){
										for(var j=0;j<curent_element_parent_div.getElementsByClassName("wpda_likebox_"+open_closed_ids[i]).length;j++){
											curent_element_parent_div.getElementsByClassName("wpda_likebox_"+open_closed_ids[i])[j].style.display="none";
										}
										
									}
									for(i=0;i<curent_element_parent_div.getElementsByClassName("wpda_likebox_"+open_closed_ids[select.selectedIndex]).length;i++){
										curent_element_parent_div.getElementsByClassName("wpda_likebox_"+open_closed_ids[select.selectedIndex])[i].style.display="initial";
									}									
									props.setAttributes( params );
								}
							},created_options),
						  )						
						);			 	
					 	 
			}
			
			function wpda_likebox_lb_days_hourse_minutes(element_day_name,element_hour_name,element_minut_name,element_title,element_description,pro_feature=false,aditional_css={},aditional_classes=""){
				return el('tr',{className:"wpda_simple_input_tr "+"wpda_likebox_"+element_day_name+" "+aditional_classes, style:aditional_css},
						  wpda_likebox_title_and_description(element_title,element_description,pro_feature),
						  el('td',{className:"wpda_simple_input_td"},
							 el('span',{className:'wpda_simple_span_time'},
									el('input',{seze:"3",type:"text",Value:props.attributes[element_day_name],onMouseDown:function(){if(pro_feature){alert(pro_feature_text); return false;}},className:'wpda_simple_input_time',onChange: function( value ) {var select=value.target; var params={}; params[element_day_name]=select.value;  props.setAttributes(params)}}),
									el("small",{className:"wpda_simple_input_time_small"},"Day")
							   ),
							 el('span',{className:'wpda_simple_span_time'},
									el('input',{seze:"3",type:"text",Value:props.attributes[element_hour_name],onMouseDown:function(){if(pro_feature){alert(pro_feature_text); return false;}},className:'wpda_simple_input_time',onChange: function( value ) {var select=value.target; var params={}; params[element_hour_name]=select.value;  props.setAttributes(params)}}),
									el("small",{className:"wpda_simple_input_time_small"},"Hour")
							   ),
							 el('span',{className:'wpda_simple_span_time'},
									el('input',{seze:"3",type:"text",Value:props.attributes[element_minut_name],onMouseDown:function(){if(pro_feature){alert(pro_feature_text); return false;}},className:'wpda_simple_input_time',onChange: function( value ) {var select=value.target; var params={}; params[element_minut_name]=select.value;  props.setAttributes(params)}}),
									el("small",{className:"wpda_simple_input_time_small"},"Minut")
							   )
						  )
						
						);		 	
					 	 
			}
			
			function wpda_likebox_lb_calendar_input(element_name,element_title,element_description,pro_feature=false,aditional_css={},aditional_classes=""){
				if(props.attributes[element_name]===""){
					var date=currentdate.getFullYear()+"-"+(currentdate.getMonth()+1)+"-"+currentdate.getDate() + "T"+((currentdate.getHours() < 10)?"0":"") + currentdate.getHours() +":"+ ((currentdate.getMinutes() < 10)?"0":"") + currentdate.getMinutes() +":"+ ((currentdate.getSeconds() < 10)?"0":"") + currentdate.getSeconds();
					var params={};
					params[element_name]=date;  
					props.setAttributes(params);
				}
				return el('tr',{className:"wpda_simple_input_tr "+"wpda_likebox_"+element_name+" "+aditional_classes, style:aditional_css},
						  wpda_likebox_title_and_description(element_title,element_description,pro_feature),
						  el('td',{className:"wpda_simple_input_td"},
							el(components.DateTimePicker,{type:"input",currentDate:props.attributes[element_name], onChange: function( value ) { var params={}; params[element_name]=value;  props.setAttributes(params)}})
						  )
						
						);			 	
					 	 
			}
			
			function wpda_likebox_lb_color_input(element_name,element_title,element_description,pro_feature=false,aditional_css={},aditional_classes=""){
				return el('tr',{className:"wpda_simple_input_tr "+"wpda_likebox_"+element_name+" "+aditional_classes,style:aditional_css},
						  wpda_likebox_title_and_description(element_title,element_description,pro_feature),
						  el('td',{className:"wpda_color_input_td"},
							el('input',{type:"color",Value:props.attributes[element_name],onMouseDown:function(){if(pro_feature){alert(pro_feature_text); return false;}},className:'wpda_simple_input',onChange: function( value ) {var select=value.target; var params={}; params[element_name]=select.value;  props.setAttributes(params)}})
						  )
						
						);			 	
					 	 
			}
			
			function wpda_likebox_title_and_description(element_title,element_description,pro_feature=false){
				if(pro_feature){
					var pro_element=el("span",{className:"pro_feature"}," (pro)");
				}else{
					var pro_element="";
				}
				return el('td',{className:"wpda_title_description_td"},
						   el('span',{className:"wpda_likebox_element_title"},element_title

						   ),  
						   pro_element,
						  
						   el('span',{className:"wpda_likebox_element_description",title:element_description},"?"
						   )							
					  )
			}			
		},
		
		save: function( props ) {	
			var shortcode_atributes="";		
			
			shortcode_atributes = shortcode_atributes + ' profile_id="' + props.attributes.like_box_profile_id + '"';
			shortcode_atributes = shortcode_atributes + ' animation_efect="' + props.attributes.animation_efect + '"';
			shortcode_atributes = shortcode_atributes + ' show_border="' + props.attributes.show_border + '"';
			shortcode_atributes = shortcode_atributes + ' border_color="' + props.attributes.border_color + '"';
			shortcode_atributes = shortcode_atributes + ' stream="' + props.attributes.stream + '"';
			shortcode_atributes = shortcode_atributes + ' connections="' + props.attributes.connections + '"';
			shortcode_atributes = shortcode_atributes + ' width="' + props.attributes.width + '"';
			shortcode_atributes = shortcode_atributes + ' height="' + props.attributes.height + '"';
			shortcode_atributes = shortcode_atributes + ' header="' + props.attributes.header + '"';
			shortcode_atributes = shortcode_atributes + ' show_cover_photo="' + props.attributes.cover_photo + '"';
			shortcode_atributes = shortcode_atributes + ' locale="' + props.attributes.locale + '"';					
			return "[wpdevart_like_box " + shortcode_atributes + "]";
		}

	} )
} )(
	window.wp.blocks,
	window.wp.editor,
	window.wp.i18n,
	window.wp.element,
	window.wp.components,
	window._,
);

