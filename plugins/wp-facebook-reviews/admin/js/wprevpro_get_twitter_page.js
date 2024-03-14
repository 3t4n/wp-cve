
		
(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 * $( document ).ready(function() same as
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	//document ready
	$(function(){
		
		var getrevformtempid='';

		//help button clicked
		$( "#wprevpro_helpicon_posts" ).click(function() {
		  openpopup("Tips", '<p>This page will let you search for and download twitter posts. </p>', "");
		});
		
		//display alert if changing to 30 day search and not have keys
		
		$( "#wprevpro_endpoint" ).on( "change", function(event) {
			var selval = $( this ).val();
			var wprevpro_twitter_api_key = $( '#wprevpro_twitter_api_key' ).val();
			var wprevpro_twitter_api_key_secret = $( '#wprevpro_twitter_api_key_secret' ).val();
			var wprevpro_twitter_api_token = $( '#wprevpro_twitter_api_token' ).val();
			var wprevpro_twitter_api_token_secret = $( '#wprevpro_twitter_api_token_secret' ).val();
			if(selval!='7'){
				if(wprevpro_twitter_api_key=="" || wprevpro_twitter_api_key_secret=="" || wprevpro_twitter_api_token=="" || wprevpro_twitter_api_token_secret==""){
					alert('You must enter your Twitter API Keys in order to use the 30 day or Full archive search. Click the "Enter/Modify API Keys" button above.');
				}
			}
		});
		
		
		
		//launch pop-up windows code--------
		function openpopup(title, body, body2){
			//set text
			jQuery( "#popup_titletext").html(title);
			jQuery( "#popup_bobytext1").html(body);
			jQuery( "#popup_bobytext2").html(body2);
			
			var popup = jQuery('#popup_review_list').popup({
				width: 400,
				offsetX: -100,
				offsetY: 0,
			});
			
			popup.open();
			//set height
			var bodyheight = Number(jQuery( ".popup-content").height()) + 10;
			jQuery( "#popup_review_list").height(bodyheight);

		}
		
		//for actuall saving the tweet to the db
		//tw_rtc="0" tw_rc="0" tw_fc="0" tw_time="Fri Jul 05 22:11:46 +0000 2019" tw_id="1147266850419544065" tw_sname="HowellsMead" tw_name="Mark Howells-Mead" tw_text="Maybe it’s because it’s late on a Friday and I’m tired. But I just spent 20 mins migrating a local #WordPress dev project from VVV to @LocalbyFlywheel and I get the impression that it’s waaay faster. I guess because of the single Docker container per site?" tw_img="https://pbs.twimg.com/profile_images/1138433818728304640/0Eobr89P_normal.jpg"
		$( "#selecttweets" ).on( "click", ".tweetsavebtn", function(event) {
			var parentobj = $( this ).parent();
			$(this).hide();
			var tw_text = parentobj.attr( "tw_text" );
			var tw_rtc = parentobj.attr( "tw_rtc" );
			var tw_rc = parentobj.attr( "tw_rc" );
			var tw_fc = parentobj.attr( "tw_fc" );
			var tw_time = parentobj.attr( "tw_time" );
			var tw_id = parentobj.attr( "tw_id" );
			var tw_name = parentobj.attr( "tw_name" );
			var tw_sname = parentobj.attr( "tw_sname" );
			var tw_img = parentobj.attr( "tw_img" );
			var tw_lang = parentobj.attr( "tw_lang" );
			
			//ajax to save in db
			var senddata = {
				action: 'wprp_twitter_savetweet',	//required
				wpfb_nonce: adminjs_script_vars.wpfb_nonce,
				tw_text: tw_text,
				tw_rtc: tw_rtc,
				tw_rc: tw_rc,
				tw_fc: tw_fc,
				tw_time: tw_time,
				tw_id: tw_id,
				tw_sname: tw_sname,
				tw_name: tw_name,
				tw_img: tw_img,
				tw_lang: tw_lang,
				title:ftitle,
				saveordel:'save',
				cats:fcats,
				posts:fposts,
				fid:formid,
				limage:localimage,
				};
				
			var jqxhr = jQuery.post(ajaxurl, senddata, function (response){
				console.log(response);
				var formobject = JSON.parse(response);
				if (!$.trim(response)){
					
				} else {
					if(typeof formobject =='object')
					{
						var insertnum = formobject.insertnum;
						console.log(formobject);
					  // It is JSON, safe to continue here
						if(insertnum>0){
						  //hide this button and show the delete button
						  parentobj.find('.tweetdelbtn').show();
						  //add the yellow background
						  parentobj.parent().addClass('w3-yellow');
						} else {
							alert( "Error using ajax to save tweet, please try again or contact support." );
							parentobj.find('.tweetsavebtn').show();
						}
					}
					else
					{
						console.log(response);
						parentobj.find('.tweetsavebtn').show();
						alert( "Error using ajax to save tweet, please try again or contact support." );
					}
				}
			});
			jqxhr.fail(function() {
			  alert( "Error using ajax to save tweet, please try again or contact support." );
			  parentobj.find('.tweetsavebtn').show();
			});
			
		});
		//======
		//for deleting the tweet from the db
		$( "#selecttweets" ).on( "click", ".tweetdelbtn", function(event) {
			var parentobj = $( this ).parent();
			var tw_id = parentobj.attr( "tw_id" );
			$(this).hide();
			var senddata = {
				action: 'wprp_twitter_deltweet',	//required
				wpfb_nonce: adminjs_script_vars.wpfb_nonce,
				fid:formid,
				tw_id: tw_id,
				saveordel:'del'
			};
			console.log(senddata);
			var jqxhr = jQuery.post(ajaxurl, senddata, function (response){
				console.log(response);
				var formobject = JSON.parse(response);
				console.log(formobject);
				if (!$.trim(response)){
					alert( "Error using ajax to delete tweet, please try again or contact support." );
					parentobj.find('.tweetdelbtn').show();
				} else {
					if(typeof formobject =='object')
					{
						var deletenum = formobject.deletenum;
						console.log(formobject);
					  // It is JSON, safe to continue here
						if(deletenum>0){
						  //hide this button and show the delete button
						  parentobj.find('.tweetsavebtn').show();
						   parentobj.parent().removeClass('w3-yellow');
						} else {
							alert( "Error using ajax to delete tweet, please try again or contact support." );
							parentobj.find('.tweetdelbtn').show();
						}
					}
					else
					{
						console.log(response);
						parentobj.find('.tweetdelbtn').show();
						alert( "Error using ajax to delete tweet, please try again or contact support." );
					}
				}
			});
			
		});
		//=====



		var searchterms='';
		var searchendpoint='';
		var ftitle='';
		var formid='';
		var fcats='';
		var fposts='';
		var localimage = '';
		//======retrieve reviews button clicked=======
		$( ".retreviewsbtn" ).click(function(event) {
			event.preventDefault();
			//get id and badge type
			getrevformtempid = $( this ).parent().attr( "templateid" );
			var url = "#TB_inline?inlineId=retreivewspopupdiv";
			tb_show("Download Tweets", url);
			$( "#TB_window" ).css({ "height":"auto !important","width":"730px","margin-top":"-400px","margin-left":"-365px" });
			$( "#TB_ajaxContent" ).css({ "max-height":"700px" });
			$( "#TB_ajaxContent" ).css({ "width":"auto" });
			$( "#TB_ajaxContent" ).css({ "height":"700px" });
			$( "#getrevsbtnpopup" ).attr("tabindex",-1).focus();
			//call ajax to scrape the reviews.
			$( ".ajaxmessagediv" ).html('');
			searchterms= $( this ).parent().attr( "squery" );
			searchendpoint= $( this ).parent().attr( "epoint" );
			ftitle= $( this ).parent().attr( "ftitle" );
			formid= $( this ).parent().parent().attr( "id" );
			fcats= $( this ).parent().attr( "fcats" );
			fposts= $( this ).parent().attr( "fposts" );
			localimage = $( this ).parent().attr( "limage" );
			//fill out form on pop-up so we can edit it
			
			//console.log(fcats);
			
			$( "#tb_content_query_input" ).val(searchterms);
			ajaxgettweets(searchterms,searchendpoint);
			
		});
		//when updating search------
		$( ".updatequery" ).click(function(event) {
			event.preventDefault();
			searchterms=$( this ).prev('#tb_content_query_input').val();
			updatetdqueryval(searchterms);
			ajaxgettweets(searchterms,searchendpoint);
		});
		//catch enter key input
		 $('#tb_content_query_input').keydown( function(e) {
			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
			if(key == 13) {
				e.preventDefault();
				searchterms=$( this ).val();
				updatetdqueryval(searchterms);
				ajaxgettweets(searchterms,searchendpoint);;
			}
		});
		function updatetdqueryval(newval){
			$('#'+formid).find('.tdquery').html(newval);
		}
		
		//for searching and displaying tweets
		function ajaxgettweets(searchterms,searchendpoint){
			$( "#selecttweets" ).html('');
			var spinnerdiv = $( ".downloadrevsbtnspinner" );
			spinnerdiv.addClass('loadingspinner');
			//make ajax call here to look for tweets
			var senddata = {
				action: 'wprp_twitter_gettweets',	//required
				wpfb_nonce: adminjs_script_vars.wpfb_nonce,
				query: searchterms,
				endpoint: searchendpoint,
				fid:formid
				};
			//send to ajax to update db
			//console.log(senddata);
			var jqxhr = jQuery.post(ajaxurl, senddata, function (response){
				
			//testing
			//var response = '{"searchquery":"LocalbyFlywheel -from:LocalbyFlywheel -RT","searchendpoint":"30","ack":"success","msg":"","statuses":{"results":[{"created_at":"Mon Jul 22 16:06:53 +0000 2019","id":1153335614483206144,"id_str":"1153335614483206144","text":"you must know abour @LocalbyFlywheel work on wordpress on your local machine in a local pub.","source":"","truncated":false,"in_reply_to_status_id":null,"in_reply_to_status_id_str":null,"in_reply_to_user_id":null,"in_reply_to_user_id_str":null,"in_reply_to_screen_name":null,"user":{"id":381780750,"id_str":"381780750","name":"bayareamade","screen_name":"Bayareamade","location":"San Francisco Bay Area","url":null,"description":"Real Localization, is what is happening in Oakland\/San Francisco Bay. If you make it sell it ..here. Still in Beta since 2012","translator_type":"none","protected":false,"verified":false,"followers_count":708,"friends_count":2052,"listed_count":61,"favourites_count":179,"statuses_count":3874,"created_at":"Wed Sep 28 22:50:39 +0000 2011","utc_offset":null,"time_zone":null,"geo_enabled":true,"lang":null,"contributors_enabled":false,"is_translator":false,"profile_background_color":"000305","profile_background_image_url":"http:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png","profile_background_image_url_https":"https:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png","profile_background_tile":true,"profile_link_color":"E81C4F","profile_sidebar_border_color":"C0DEED","profile_sidebar_fill_color":"DDEEF6","profile_text_color":"333333","profile_use_background_image":true,"profile_image_url":"http:\/\/pbs.twimg.com\/profile_images\/3060270615\/a7204b223d7c0f59464bdb655a5e0fd5_normal.png","profile_image_url_https":"https:\/\/pbs.twimg.com\/profile_images\/3060270615\/a7204b223d7c0f59464bdb655a5e0fd5_normal.png","profile_banner_url":"https:\/\/pbs.twimg.com\/profile_banners\/381780750\/1348795736","default_profile":false,"default_profile_image":false,"following":null,"follow_request_sent":null,"notifications":null},"geo":null,"coordinates":null,"place":null,"contributors":null,"is_quote_status":false,"quote_count":0,"reply_count":0,"retweet_count":0,"favorite_count":0,"entities":{"hashtags":[],"urls":[],"user_mentions":[{"screen_name":"LocalbyFlywheel","name":"Local by Flywheel","id":1006207152187428865,"id_str":"1006207152187428865","indices":[20,36]}],"symbols":[]},"favorited":false,"retweeted":false,"filter_level":"low","lang":"en","matching_rules":[{"tag":null}]},{"created_at":"Mon Jul 22 13:04:45 +0000 2019","id":1153289782287568896,"id_str":"1153289782287568896","text":"If you do @WordPress development work on a local environment check out @LocalbyFlywheel. I used Xampp before, but t\u2026 https:\/\/t.co\/QgWfwhmg7p","source":"","truncated":true,"in_reply_to_status_id":null,"in_reply_to_status_id_str":null,"in_reply_to_user_id":null,"in_reply_to_user_id_str":null,"in_reply_to_screen_name":null,"user":{"id":157672650,"id_str":"157672650","name":"Sjoerd Koelewijn","screen_name":"SjoerdKoelewijn","location":"Amsterdam","url":null,"description":"Digital Marketer. UX\/UI Designer. Frontend Developer. Likes to take photos.","translator_type":"none","protected":false,"verified":false,"followers_count":83,"friends_count":369,"listed_count":17,"favourites_count":451,"statuses_count":321,"created_at":"Sun Jun 20 14:23:56 +0000 2010","utc_offset":null,"time_zone":null,"geo_enabled":false,"lang":null,"contributors_enabled":false,"is_translator":false,"profile_background_color":"FFFFFF","profile_background_image_url":"http:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png","profile_background_image_url_https":"https:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png","profile_background_tile":false,"profile_link_color":"58B89D","profile_sidebar_border_color":"CCCCCC","profile_sidebar_fill_color":"EFEFEF","profile_text_color":"333333","profile_use_background_image":false,"profile_image_url":"http:\/\/pbs.twimg.com\/profile_images\/1149961177960763393\/zmjtIKRv_normal.jpg","profile_image_url_https":"https:\/\/pbs.twimg.com\/profile_images\/1149961177960763393\/zmjtIKRv_normal.jpg","profile_banner_url":"https:\/\/pbs.twimg.com\/profile_banners\/157672650\/1521818865","default_profile":false,"default_profile_image":false,"following":null,"follow_request_sent":null,"notifications":null},"geo":null,"coordinates":null,"place":null,"contributors":null,"is_quote_status":false,"extended_tweet":{"full_text":"If you do @WordPress development work on a local environment check out @LocalbyFlywheel. ","display_text_range":[0,188],"entities":{"hashtags":[],"urls":[],"user_mentions":[{"screen_name":"WordPress","name":"WordPress","id":685513,"id_str":"685513","indices":[10,20]},{"screen_name":"LocalbyFlywheel","name":"Local by Flywheel","id":1006207152187428865,"id_str":"1006207152187428865","indices":[71,87]},{"screen_name":"jasonbahl","name":"Jason Bahl","id":111455256,"id_str":"111455256","indices":[146,156]},{"screen_name":"syntaxfm","name":"Syntax","id":733722018596687872,"id_str":"733722018596687872","indices":[178,187]}],"symbols":[]}},"quote_count":0,"reply_count":0,"retweet_count":0,"favorite_count":1,"entities":{"hashtags":[],"urls":[{"url":"https:\/\/t.co\/QgWfwhmg7p","expanded_url":"https:\/\/twitter.com\/i\/web\/status\/1153289782287568896","display_url":"twitter.com\/i\/web\/status\/1\u2026","indices":[117,140]}],"user_mentions":[{"screen_name":"WordPress","name":"WordPress","id":685513,"id_str":"685513","indices":[10,20]},{"screen_name":"LocalbyFlywheel","name":"Local by Flywheel","id":1006207152187428865,"id_str":"1006207152187428865","indices":[71,87]}],"symbols":[]},"favorited":false,"retweeted":false,"filter_level":"low","lang":"en","matching_rules":[{"tag":null}]},{"created_at":"Sun Jul 21 22:51:22 +0000 2019","id":1153075020920266752,"id_str":"1153075020920266752","text":"I think I\'ve finally gotten rid of @mamp_en just installed @LocalbyFlywheel and it\'s working incredible. Exactly ho\u2026 https:\/\/t.co\/VTPD2eVWm8","source":"","truncated":true,"in_reply_to_status_id":null,"in_reply_to_status_id_str":null,"in_reply_to_user_id":null,"in_reply_to_user_id_str":null,"in_reply_to_screen_name":null,"user":{"id":1039000814763659264,"id_str":"1039000814763659264","name":"Brendan Foster","screen_name":"brendanrfoster","location":"Dunsborough, Western Australia","url":null,"description":"I am going to buy my parents a home.","translator_type":"none","protected":false,"verified":false,"followers_count":59,"friends_count":99,"listed_count":0,"favourites_count":1299,"statuses_count":683,"created_at":"Mon Sep 10 04:01:12 +0000 2018","utc_offset":null,"time_zone":null,"geo_enabled":false,"lang":null,"contributors_enabled":false,"is_translator":false,"profile_background_color":"F5F8FA","profile_background_image_url":"","profile_background_image_url_https":"","profile_background_tile":false,"profile_link_color":"1DA1F2","profile_sidebar_border_color":"C0DEED","profile_sidebar_fill_color":"DDEEF6","profile_text_color":"333333","profile_use_background_image":true,"profile_image_url":"http:\/\/pbs.twimg.com\/profile_images\/1039002347882442752\/LqiOAJqn_normal.jpg","profile_image_url_https":"https:\/\/pbs.twimg.com\/profile_images\/1039002347882442752\/LqiOAJqn_normal.jpg","default_profile":true,"default_profile_image":false,"following":null,"follow_request_sent":null,"notifications":null},"geo":null,"coordinates":null,"place":null,"contributors":null,"is_quote_status":false,"extended_tweet":{"full_text":"I think I\'ve finally gotten rid of @mamp_en just installed @LocalbyFlywheel and it\'s working incredible. Exactly how @mamp_en should. Back to speedy coding.","display_text_range":[0,156],"entities":{"hashtags":[],"urls":[],"user_mentions":[{"screen_name":"mamp_en","name":"MAMP","id":69245216,"id_str":"69245216","indices":[35,43]},{"screen_name":"LocalbyFlywheel","name":"Local by Flywheel","id":1006207152187428865,"id_str":"1006207152187428865","indices":[59,75]},{"screen_name":"mamp_en","name":"MAMP","id":69245216,"id_str":"69245216","indices":[117,125]}],"symbols":[]}},"quote_count":0,"reply_count":0,"retweet_count":0,"favorite_count":0,"entities":{"hashtags":[],"urls":[{"url":"https:\/\/t.co\/VTPD2eVWm8","expanded_url":"https:\/\/twitter.com\/i\/web\/status\/1153075020920266752","display_url":"twitter.com\/i\/web\/status\/1\u2026","indices":[117,140]}],"user_mentions":[{"screen_name":"mamp_en","name":"MAMP","id":69245216,"id_str":"69245216","indices":[35,43]},{"screen_name":"LocalbyFlywheel","name":"Local by Flywheel","id":1006207152187428865,"id_str":"1006207152187428865","indices":[59,75]}],"symbols":[]},"favorited":false,"retweeted":false,"filter_level":"low","lang":"en","matching_rules":[{"tag":null}]}],"next":"eyJhdXRoZW50aWNpdHkiOiI4ZjZhNWMyYzYxNzY3ZWRiOTgzZTI3NjNjNjI5MDVhODZjMmRlNWIzYWI2MDZhZjFiNjg4OTE3YzI5MGJiZTZmIiwiZnJvbURhdGUiOiIyMDE5MDYyMjAwMDAiLCJ0b0RhdGUiOiIyMDE5MDcyMjE4NDAiLCJuZXh0IjoiMjAxOTA3MjIxODQwMTEtMTE1MDkzNTgzODA3MjU1MzQ3Mi0wIn0=","requestParameters":{"maxResults":10,"fromDate":"201906220000","toDate":"201907221840"}}}';
				
				spinnerdiv.removeClass('loadingspinner');
				console.log(response);
				//$( ".ajaxmessagediv" ).html(response);
				if (!$.trim(response)){
					//alert("Error returning reviews for this url, please contact support.");
					$( ".ajaxmessagediv" ).html("Error returning tweets for this query, please try again or contact support.".response);
				} else {
					var formobject = JSON.parse(response);
					var tablehtml='';
					var i;
					var tweettext;
					var statusid="";
					var spanbtnhtml ='';
					var spanbtntrbg ='';
					var savedtweets = formobject.savedreviews;
					if(typeof formobject =='object')
					{
						console.log(formobject);
						var tweetsarray = formobject.statuses.results;
					  // It is JSON, safe to continue here
						if(tweetsarray && tweetsarray.length>0){
							//====build table here and display it.
							for (i = 0; i < tweetsarray.length; i++) { 
								//use extended text if exists
								if(tweetsarray[i].truncated && tweetsarray[i].extended_tweet){
									tweettext = tweetsarray[i].extended_tweet.full_text;
								} else {
									tweettext = tweetsarray[i].text;
								}
								statusid = tweetsarray[i].id_str;
								
								//find out if we should show save button or delete button
								if(savedtweets.includes(statusid)){
									//already have this one saved
									spanbtnhtml = '<span style="display:none;" class="dashicons dashicons-plus-alt tweetsavebtn text_green" alt="download tweet" title="download tweet"></span><span class="dashicons dashicons-trash tweetdelbtn" alt="remove tweet from Plugin" title="remove tweet from Plugin"></span>';
									spanbtntrbg = 'w3-yellow';
								} else {
									spanbtnhtml = '<span class="dashicons dashicons-plus-alt tweetsavebtn text_green" alt="download tweet" title="download tweet"></span><span style="display:none;" class="dashicons dashicons-trash tweetdelbtn" alt="remove tweet from Plugin" title="remove tweet from Plugin"></span>';
									spanbtntrbg = '';
								}
							
							var replycount = 0;
							if(tweetsarray[i].reply_count){
								replycount = tweetsarray[i].reply_count;
							}
						
							  tablehtml += '<tr class="'+spanbtntrbg+'"><td tw_lang="'+tweetsarray[i].lang+'" tw_rtc="'+tweetsarray[i].retweet_count+'" tw_rc="'+replycount+'" tw_fc="'+tweetsarray[i].favorite_count+'" tw_time="'+tweetsarray[i].created_at+'" tw_id="'+statusid+'" tw_sname="'+tweetsarray[i].user.screen_name+'" tw_name="'+tweetsarray[i].user.name+'" tw_text="'+tweettext+'" tw_img="'+tweetsarray[i].user.profile_image_url_https+'">'+spanbtnhtml+'</td><td><img src="'+tweetsarray[i].user.profile_image_url_https+'" alt="Avatar"></td><td>'+tweetsarray[i].user.name+'<br><a href="https://twitter.com/'+tweetsarray[i].user.screen_name+'" target="_blank">'+tweetsarray[i].user.screen_name+'</a></td><td>'+tweettext+'<a href="https://twitter.com/'+tweetsarray[i].user.screen_name+'/status/'+statusid+'" target="_blank"><span class="dashicons dashicons-share-alt2"></span></a></td><td>'+tweetsarray[i].created_at+'</td><td>Likes:'+tweetsarray[i].favorite_count+'<br>Replies:'+replycount+'<br>Retweets:'+tweetsarray[i].retweet_count+'</td></tr>';
							}
							$( "#selecttweets" ).html(tablehtml);
							

						} else {
							alert("Nothing found.".formobject.msg);
							
						}
					}
					else
					{
						$( ".ajaxmessagediv" ).html("Error returning json object: " +response);
						console.log(response);
					}
				}
			});
			jqxhr.fail(function() {
			  alert( "Error using ajax to return tweets, please try again or contact support." );
			});
		}
		

		//hide show api key form
		$( "#wprevpro_addnewapikey" ).click(function() {
		  jQuery("#apikeyformdiv").toggle("slow");
		});	
		
		//hide or show edit template form ----------
		var checkedittemplate = getParameterByName('taction'); // "lorem"
		if(checkedittemplate=="edit"){
			jQuery("#wprevpro_new_template").show("slow");
		} else {
			jQuery("#wprevpro_new_template").hide();
		}
		
		$( "#wprevpro_addnewtemplate" ).click(function() {

				jQuery("#wprevpro_new_template").toggle("slow");
		
			
		});	
		$( "#wprevpro_addnewtemplate_cancel" ).click(function() {
		  jQuery("#wprevpro_new_template").hide("slow");
		  //reload page without taction and tid
		  setTimeout(function(){ 
			window.location.href = "?page=wpfb-get_twitter"; 
		  }, 500);
		  
		});	
		
		//-------------------------------
		//form validation 
		$("#wprevpro_submittemplatebtn").click(function(){
			if(jQuery( "#wprevpro_template_title").val()==""){
				alert("Please enter a Place Name.");
				//$( "#wprevpro_template_title" ).focus();
				return false;
			}
			//loop through title to see if it's been used yet. only if not editing
			var uniquename=true;
			if($("#edittid").val()==''){
				$( ".titlespan" ).each(function() {
				  var temptitle = $( this ).text();
				  if(jQuery( "#wprevpro_template_title").val()==temptitle){
					  uniquename=false;
				  }
				});
				if(uniquename==false){
					alert("Please enter a unique Place Name.");
					return false;
				}
			}
			return true;
		});
		
		function ValidURL(str) {
            var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|'+ // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))'+ // ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ //port
            '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
            '(\\#[-a-z\\d_]*)?$','i');
		  if(!pattern.test(str)) {
			return false;
		  } else {
			return true;
		  }
		}
		
		function timeConverter(UNIX_timestamp){
		  var a = new Date(UNIX_timestamp * 1000);
		  var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
		  var year = a.getFullYear();
		  var month = months[a.getMonth()];
		  var date = a.getDate();
		  var hour = a.getHours();
		  var min = a.getMinutes();
		  //var sec = a.getSeconds();
		  var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min ;
		  return time;
		}

		//update the cache avatars
		function updateavatars(){
				var senddata = {
					action: 'wpfb_update_avatars',	//required
					wpfb_nonce: adminjs_script_vars.wpfb_nonce,
					};
				jQuery.post(ajaxurl, senddata, function (response){});
		}
		

		//get the url parameter-----------
		function getParameterByName(name, url) {
			if (!url) {
			  url = window.location.href;
			}
			name = name.replace(/[\[\]]/g, "\\$&");
			var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
				results = regex.exec(url);
			if (!results) return null;
			if (!results[2]) return '';
			return decodeURIComponent(results[2].replace(/\+/g, " "));
		}
		//---------------------------------
		
	});

})( jQuery );

