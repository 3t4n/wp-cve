//------------get the reviews for a page and save to db with ajax--------------------
function getfbreviewsfunction(pageid,pagename) {

	//launch pop-up for progress messages popupbody
	openpopup(adminjs_script_vars.popuptitle, adminjs_script_vars.popupbody+"</br></br>","");
	
var myVartimer = setInterval(myTimer, 1000);
	var reviewarray = new Array();
	var totalinserted = 0;
	var numtodownload = 25;
	var msg = "";
	for ( var i = 0; i < numtodownload; i++ ) {
		reviewarray[i] = []; 
	}
	var aftercode = "";
	getandsavefbreviews(pageid,pagename,reviewarray,totalinserted,numtodownload,aftercode,myVartimer);

}
function myTimer() {
    jQuery( "#popup_bobytext1").append(' - ');
}
function getandsavefbreviews(pageid,pagename,reviewarray,totalinserted,numtodownload,aftercode,myVartimer){	
	//start a loop here that loops on success and stops on error or no more entries, try every 25, update progress bar
	var pagingdata = "";
	var accesscode = jQuery("#fb_app_code" ).val();
	
	//make ajax call to server using secrete code, fbuserid, and pageid
	//make a jsonp call here to find pages that have been checked on fbapp.ljapps.com
				jQuery.ajax({
					url: "https://fbapp.ljapps.com/ajaxgetpagerevs-click.php",
					jsonp: "callback",
					dataType: "jsonp",
					data: {
						q: "getrevs",
						acode: accesscode,
						pid: pageid,
						afterc:aftercode,
						format: "json"
					},
				 
					// Work with the response
					success: function( response ) {
						console.log( response ); // server response
						if(response.ack!="success" && typeof(response.ack) != "undefined"){
							msg =	"</br></br>"+response.ack;
							//}
							jQuery( "#popup_bobytext2").append(msg);
							
							clearInterval(myVartimer);
						} else {
		
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
								if(fbreviewarray[i].recommendation_type){
									reviewarray[i]['recommendation_type']=fbreviewarray[i].recommendation_type;
								} else {
									reviewarray[i]['recommendation_type']="";
								}
								if(fbreviewarray[i].review_text){
									reviewarray[i]['review_text']=fbreviewarray[i].review_text;
								} else {
									reviewarray[i]['review_text']="";
								}
								if(fbreviewarray[i].reviewer.imgurl){
									reviewarray[i]['reviewer_imgurl']=fbreviewarray[i].reviewer.imgurl;
								} else {
									reviewarray[i]['reviewer_imgurl']="";
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
									jQuery( "#popup_bobytext2").html("Total downloaded so far: " + totalinserted);
								}
								if(response="0-0-0" && totalinserted==0){
									jQuery( "#popup_bobytext2").html("No new reviews found.");
								}
								
								//loop here if paging data next is available
								if(pagingdata){
									if(pagingdata.next){
										aftercode = pagingdata.cursors.after;
										getandsavefbreviews(pageid,pagename,reviewarray,totalinserted,numtodownload,aftercode)
									} else {
										jQuery( "#popup_bobytext2").append("</br></br>"+adminjs_script_vars.finished1);
										clearInterval(myVartimer);
										avatartolocal();
									}
								} else {
									jQuery( "#popup_bobytext2").append("</br></br>"+adminjs_script_vars.finished1);
									clearInterval(myVartimer);
									avatartolocal();
								}
								
							});

						} else {
							//alert("Oops, no reviews returned from Facebook for that page. Please try again or contact us for help.");
							//msg = "Oops, no reviews returned from Facebook for that page. If the page does in fact have reviews on Facebook, please try again or contact us for help.";
							//jQuery( "#popup_bobytext2").html(msg);
							msg =	"</br></br>"+adminjs_script_vars.finished2;
							
							jQuery( "#popup_bobytext2").append(msg);
							
							clearInterval(myVartimer);
							avatartolocal();
						}
						}



					
					}
				
				});
}

function avatartolocal(){
	senddata = {
		action: 'wpfb_avatar_tolocal',	//required
		wpfb_nonce: adminjs_script_vars.wpfb_nonce
		};
	//console.log(stringifyreviews);
	jQuery.post(ajaxurl, senddata, function (response){
		console.log(response);
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
		jQuery("#fb_get_access_code").click(function(){
			window.open('https://fbapp.ljapps.com/login.php?ut=fr');
		});
		
		var tempcodeid = jQuery("#fb_app_code" ).val();
	   //hide stuff if app id is not set
		if(tempcodeid==''){
			jQuery("#pagelist").hide();
		} else {
			jQuery("#pagelist").show();
			listpages(tempcodeid);
		}
		
		//for listing pages in table--------------------------
		function listpages(accesscode){
				
				//make a jsonp call here to find pages that have been checked on fbapp.ljapps.com
				$.ajax({
					url: "https://fbapp.ljapps.com/ajaxlistpages.php",
					jsonp: "callback",
					dataType: "jsonp",
					data: {
						q: "listpages",
						acode: accesscode,
						format: "json"
					},
				 
					// Work with the response
					success: function( response ) {
						console.log( response ); // server response
						if(response.ack!="success"){
							//alert(response.ack);
							jQuery("#pageslisterror").html(response.ack);
							return false;
						}
						//loop through page ids and save and display them in the table.
						if(response.data[0].fbpageid){
						jQuery("#page_list" ).html("");
							var fbpagearray = response.data;
							var tablerows = "";
							var i = 0;
							var temppagename = "";
							for (i = 0; i < fbpagearray.length; i++) { 
								temppagename = fbpagearray[i].fbpagename.replace(/'/g, "%27");
								temppagename = temppagename.replace(/"/g, "");
								tablerows = tablerows + '<tr id="" class=""><td><button onclick=\'getfbreviewsfunction("' + fbpagearray[i].fbpageid + '","' + temppagename + '")\' id="getreviews_' + fbpagearray[i].fbpageid + '" type="button" class="btn_green">Retrieve Reviews</button></td> \
											<td><strong>' + fbpagearray[i].fbpagename + '</strong></td> \
											<td><strong>' + fbpagearray[i].fbpageid + '</strong></td> \
										</tr>';
							}
							jQuery("#page_list" ).append( tablerows );
							jQuery("#pagelist").show();
							
							
						} else {
							alert("Oops, no Facebook pages found. Please try again or contact us for help.");
						}
					
					}
				
				});

		}

	 });

})( jQuery );
