//------------get the reviews for a page and save to db with ajax--------------------
function getfbreviewsfunction(pageid,pagetoken,pagename) {

	//launch pop-up for progress messages
	openpopup("Downloading Reviews", "Retrieving Facebook reviews from your <b>"+pagename+"</b> page and saving them to your Wordpress database...</br></br>","");

	var reviewarray = new Array();
	var totalinserted = 0;
	var numtodownload = 25;
	var msg = "";
	for ( var i = 0; i < numtodownload; i++ ) {
		reviewarray[i] = []; 
	}
	var aftercode = "";
	getandsavefbreviews(pageid,pagetoken,pagename,reviewarray,totalinserted,numtodownload,aftercode);

}

function backupfbscrape(pageid,pagename){
					senddata = {
					action: 'wpfb_fb_backup_reviews',	//required
					wpfb_nonce: adminjs_script_vars.wpfb_nonce,
					pid: pageid,
					pname: pagename
					};
				
				jQuery.post(ajaxurl, senddata, function (response){
					console.log(response);
					jQuery( "#popup_bobytext2").append(response);
				});
}

function getandsavefbreviews(pageid,pagetoken,pagename,reviewarray,totalinserted,numtodownload,aftercode){	
	//start a loop here that loops on success and stops on error or no more entries, try every 25, update progress bar
	var pagingdata = "";
	FB.api(pageid+'/ratings', {
		access_token : pagetoken,
		pretty:0,
		limit:numtodownload,
		after:aftercode
		}, function(response) {
		console.log(response);
		//console.log(response.data.length);
		//console.log(response.data[0].reviewer);
		if (typeof(response.data) == 'undefined') {
			console.log(response);
			msg = "It appears Facebook is still temporarily blocking access to parts of their Pages API. Check the javascript console for more details. Now attempting alternative method to retrieve your 10 most helpful reviews. This shouldn't take more than a few seconds. ";
				jQuery( "#popup_bobytext2").html(msg);
				//try to scrape fb reviews page using the page id
				backupfbscrape(pageid,pagename);
				
				//------------------
				//when api starts working again we need to remove all 
				//------------------
		} else if(response.data.length < 1) {
			console.log(response);
			msg = "It appears Facebook is still temporarily blocking access to parts of their Pages API. Response is empty. Now attempting alternative method to retrieve your 10 most helpful reviews. This shouldn't take more than a few seconds. ";
				jQuery( "#popup_bobytext2").html(msg);
				//try to scrape fb reviews page using the page id
				backupfbscrape(pageid,pagename);
			
		} else if(typeof response.data[0].reviewer == 'undefined') {
			console.log(response);
			msg = "It appears Facebook is still temporarily blocking access to parts of their Pages API. Unable to retrieve the name of the reviewer. Check the javascript console for more details. Now attempting alternative method to retrieve your 10 most helpful reviews. This shouldn't take more than a few seconds. ";
				jQuery( "#popup_bobytext2").html(msg);
				//try to scrape fb reviews page using the page id
				backupfbscrape(pageid,pagename);
			
		}else {
			if(response.data.length > 0){
				var fbreviewarray = response.data;
				pagingdata = response.paging;
				for (i = 0; i < fbreviewarray.length; i++) {
					if(fbreviewarray[i].reviewer){
					reviewarray[i] = {};
					reviewarray[i]['pageid']=pageid;
					reviewarray[i]['pagename']=pagename;
					reviewarray[i]['created_time']=fbreviewarray[i].created_time;
					reviewarray[i]['reviewer_name']=fbreviewarray[i].reviewer.name;
					reviewarray[i]['reviewer_id']=fbreviewarray[i].reviewer.id;
					reviewarray[i]['rating']=fbreviewarray[i].rating;
					if(fbreviewarray[i].review_text){
						reviewarray[i]['review_text']=fbreviewarray[i].review_text;
					} else {
						reviewarray[i]['review_text']="";
					}
					reviewarray[i]['type']="Facebook";
					}

				}
		// take response and format array based on what we need only
		//send array via ajax to php function to insert to db.
		// use nonce to make sure this is not hijacked
				//post to server
				var stringifyreviews = JSON.stringify(reviewarray);
				senddata = {
					action: 'wpfb_get_results',	//required
					wpfb_nonce: adminjs_script_vars.wpfb_nonce,
					postreviewarray: reviewarray
					};
				//console.log(stringifyreviews);

				jQuery.post(ajaxurl, senddata, function (response){
					var res = response.split("-");
					totalinserted = Number(totalinserted) + Number(res[2]);
					if(totalinserted>0){
						jQuery( "#popup_bobytext2").html("Total Downloaded: " + totalinserted);
					}
					if(response="0-0-0" && totalinserted==0){
						jQuery( "#popup_bobytext2").html("No new reviews found.");
					}
					
					if(!pagingdata.next){
						jQuery( "#popup_bobytext2").append("</br></br>Finished!");
					}
					
					//loop here if paging data next is available
					if(pagingdata.next){
						aftercode = pagingdata.cursors.after;
						getandsavefbreviews(pageid,pagetoken,pagename,reviewarray,totalinserted,numtodownload,aftercode)
					}
					
				});

			} else {
				//alert("Oops, no reviews returned from Facebook for that page. Please try again or contact us for help.");
				msg = "Oops, no reviews returned from Facebook for that page. If the page does in fact have reviews on Facebook, please try again or contact us for help.";
				jQuery( "#popup_bobytext2").html(msg);
			}
		}
	});

	
}

//launch pop-up windows code--------
function openpopup(title, body, body2){

	//set text
	jQuery( "#popup_titletext").html(title);
	jQuery( "#popup_bobytext1").html(body);
	jQuery( "#popup_bobytext2").html(body2);
	
	var popup = jQuery('#popup').popup({
		width: 400,
		height: 320,
		offsetX: -100,
		offsetY: 0,
	});
	
	popup.open();
}
//--------------------------------


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
		//add links to buttons
		jQuery("#fb_create_app").click(function(){
			window.open('https://developers.facebook.com/apps');
		});

		//hide page list pagelist
		//jQuery("#pagelist").hide();
		jQuery("#fb_user_token_btn").closest('.wpfbr_row').hide();
		
	   var tempappid = jQuery("#fb_app_ID" ).val();
	   //hide stuff if app id is not set
		if(tempappid==''){
			//jQuery(".form-table").hide();
			jQuery("#fb_user_token_btn").closest('.wpfbr_row').hide();
			jQuery("#fb_user_token_field_display").closest('.wpfbr_row').hide();
			jQuery("#pagelist").hide();
		} else {
			jQuery("#fb_user_token_field_display").closest('.wpfbr_row').hide();
		}

		jQuery.ajaxSetup({ cache: true });
		jQuery.getScript('//connect.facebook.net/en_US/sdk.js', function(){
			FB.init({
			  appId: tempappid,
			  version: 'v2.7' // or v2.1, v2.2, v2.3, ...
			}); 
			if(tempappid!=''){
				listpages("","");
			}
		});
		
		jQuery("#fb_user_token_btn" ).click(function() {
			 listpages("","");
		});

		//for paging
		jQuery("#btnpagenext" ).click(function() {
			var aftercode = jQuery(btnpagenext).attr( "pcode");
			var beforecode = "";
			listpages(beforecode,aftercode);
		});
		jQuery("#btnpageprev" ).click(function() {
			var beforecode = jQuery(btnpageprev).attr( "pcode");
			var aftercode = "";
			 listpages(beforecode,aftercode);
		});		
		//--------------------------
		function listpages(beforecode,aftercode){
			if(tempappid==''){
				alert('Please enter your Facebook App ID above and click the Save Settings button at the bottom of this page.');
				return false;
			}
			var pagingstring ="";
			if(beforecode){
				pagingstring = pagingstring + '&before='+beforecode;
			}
			if(aftercode){
				pagingstring = pagingstring + '&after='+aftercode;
			}
			
			//request manage_pages scope
			FB.login(function(){}, {scope: 'manage_pages'});
			
			FB.getLoginStatus(function(response) {
			  if (response.status === 'connected') {
			  var accesstoken = response.authResponse.accessToken;
			  //save access token in hidden field
			  jQuery("#fb_user_token_field_display" ).val(accesstoken);
			  
				//console.log(response.authResponse.accessToken);
				
				var graphurlstring = '/me/accounts?limit=25'+pagingstring;
				//console.log(graphurlstring);
				FB.api(graphurlstring, function(response){
				//console.log(response);
				
					//loop through page access tokens and save and display them in the table.
					if(response.data[0].access_token){
					jQuery("#page_list" ).html("");
						var fbpagearray = response.data;
						var tablerows = "";
						var i = 0;
						var temppagename = "";
						for (i = 0; i < fbpagearray.length; i++) { 
							temppagename = fbpagearray[i].name.replace(/'/g, "%27");
							temppagename = temppagename.replace(/"/g, "");
							tablerows = tablerows + '<tr id="" class=""><td><button onclick=\'getfbreviewsfunction("' + fbpagearray[i].id + '", "' + fbpagearray[i].access_token + '", "' + temppagename + '")\' id="getreviews_' + fbpagearray[i].id + '" type="button" class="btn_green">Retrieve Reviews</button></td> \
										<td><strong>' + fbpagearray[i].name + '</strong></td> \
										<td><strong>' + fbpagearray[i].id + '</strong></td> \
										<td><strong>' + fbpagearray[i].category + '</strong></td> \
									</tr>';
						}
						jQuery("#page_list" ).append( tablerows );
						jQuery("#pagelist").show();
						
						//get paging info here
						var nextpage;
						var prevpage;
						if(response.paging.cursors.before && response.paging.previous){
							prevpage=response.paging.cursors.before;
							//show before button
							jQuery("#btnpageprev" ).show("");
							jQuery("#wpfb_page_list_pagination_bar" ).show("");
							
							//set pcode
							jQuery("#btnpageprev" ).attr( "pcode",prevpage );
							jQuery("#wpfb_page_list_pagination_bar" ).show("");

						} else {
							jQuery("#btnpageprev" ).hide("");
						}
						if(response.paging.cursors.after && response.paging.next){
							nextpage=response.paging.cursors.after;
							jQuery("#btnpagenext" ).show("");
							jQuery("#wpfb_page_list_pagination_bar" ).show("");
							jQuery("#btnpagenext" ).attr( "pcode",nextpage );
						} else {
							jQuery("#btnpagenext" ).hide("");
						}
						
						
					} else {
						alert("Oops, no Facebook pages found. Please try again or contact us for help.");
					}
				
				});
				
			  }
			});
			

			//call the graph api to get a page access token and put it in the text field
		
		}
		
	 });

})( jQuery );
