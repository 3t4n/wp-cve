jQuery('body').append('<div id="fb-root"></div>');

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=636262096435499";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

jQuery(document).ready(function() {
	
	if (window.sessionStorage){
		sessionStorage.removeItem('tab');
	}
	if(mg_promo.show_promo === 'on') {	
  jQuery('.tablenav.bottom').after('<div style="clear:both"></div>\n\
<div id="mg-gallery-ad" class="large-12">\n\
    <div class="mg-promo">\n\
		<a id="add-close-btn">x</a>\n\
		<p class="mg-promo-template-title"><a target="_blank" href="' + mg_promo.addon_link + '">If you are looking for other Layouts we offer the following</a></p>\n\
		<ul>\n\
			<div class="medium-12 large-12 columns">\n\
			<li class="small-2 medium-2 large-2 columns temp-ad-height">\n\
				<a href="' + mg_promo.slick_slider_link + '" target="_blank" >\n\
				  <img class="mg-ad-image anime" width="99" height="89" src="' + mg_promo.pluginurl +'/images/templates/slick-slider-1.png" />\n\
				</a>\n\
				<h5>\n\
					<a href="' + mg_promo.slick_slider_link + '" target="_blank" >SLICK SLIDER</a>\n\
				</h5>\n\
			</li><!--temp-ad-height-->\n\
			<li class="small-2 medium-2 large-2 columns temp-ad-height">\n\
        <a href="' + mg_promo.carousel_link + '" target="_blank">\n\
				  <img class="mg-ad-image anime" width="99" height="89" src="' + mg_promo.pluginurl +'/images/templates/image-carousel-1.png" />\n\
				</a>\n\
				<h5>\n\
					<a href="' + mg_promo.carousel_link + '" target="_blank" >IMAGE CAROUSE</a>\n\
				</h5>\n\
			</li>\n\
			<li class="small-2 medium-2 large-2 columns temp-ad-height">\n\
				<a href="' + mg_promo.image_slider_link + '" target="_blank" >\n\
				  <img class="mg-ad-image anime" width="99" height="89" src="' + mg_promo.pluginurl +'/images/templates/image-slider-1.png" />\n\
				</a>\n\
				<h5>\n\
					<a href="' + mg_promo.image_slider_link + '" target="_blank" >IMAGE SLIDER</a>\n\
				</h5>\n\
			</li>\n\
			<li class="small-2 medium-2 large-2 columns temp-ad-height">\n\
				<a href="' + mg_promo.image_showcase_link + '" target="_blank" >\n\
				  <img class="mg-ad-image anime" width="99" height="89" src="' + mg_promo.pluginurl +'/images/templates/image-showcase-1.png" />\n\
				</a>\n\
				<h5>\n\
					<a href="' + mg_promo.image_showcase_link + '" target="_blank" >IMAGE SHOWCASE</a>\n\
				</h5>\n\
			</li>\n\
			<li class="small-2 medium-2 large-2 columns temp-ad-height">\n\
				<a href="' + mg_promo.masonry_link + '" target="_blank" >\n\
				  <img class="mg-ad-image anime" width="99" height="89" src="' + mg_promo.pluginurl +'/images/templates/masonry-1.png" />\n\
				</a>\n\
				<h5>\n\
					<a href="' + mg_promo.masonry_link + '" target="_blank" >MASONRY</a>\n\
				</h5>\n\
			</li><!--temp-ad-height-->\n\
			<li class="small-2 medium-2 large-2 columns temp-ad-height">\n\
				<a href="' + mg_promo.video_showcase_link + '" target="_blank" >\n\
				  <img class="mg-ad-image anime" width="99" height="89" src="' + mg_promo.pluginurl +'/images/templates/video-showcase-1.png" />\n\
				</a>\n\
				<h5>\n\
					<a href="' + mg_promo.video_showcase_link + '" target="_blank" >VIDEO SHOWCASE</a>\n\
				</h5>\n\
			</li><!--temp-ad-height-->\n\
			<li class="small-2 medium-2 large-2 columns temp-ad-height">\n\
				<a href="' + mg_promo.albums_link + '" target="_blank" >\n\
				  <img class="mg-ad-image anime" width="99" height="89" src="' + mg_promo.pluginurl +'/images/templates/albums-1.png" />\n\
				</a>\n\
				<h5>\n\
					<a href="' + mg_promo.albums_link + '" target="_blank" >ALBUMS</a>\n\
				</h5>\n\
			</li><!--temp-ad-height-->\n\
			<li class="small-2 medium-2 large-2 columns temp-ad-height">\n\
				<a href="' + mg_promo.fwgrid_link + '" target="_blank" >\n\
				  <img class="mg-ad-image anime" width="99" height="89" src="' + mg_promo.pluginurl +'/images/templates/fw-grid.png" />\n\
				</a>\n\
				<h5>\n\
					<a href="' + mg_promo.fwgrid_link + '" target="_blank" >FULL WIDTH GRID</a>\n\
				</h5>\n\
			</li><!--temp-ad-height-->\n\
			<li class="small-2 medium-2 large-2 columns temp-ad-height">\n\
				<a href="' + mg_promo.hero_link + '" target="_blank" >\n\
				  <img class="mg-ad-image anime" width="99" height="89" src="' + mg_promo.pluginurl +'/images/templates/hero-slider.png" />\n\
				</a>\n\
				<h5>\n\
					<a href="' + mg_promo.hero_link + '" target="_blank" >HERO SLIDER</a>\n\
				</h5>\n\
			</li><!--temp-ad-height-->\n\
    </ul><!--row-->\n\
		<p class="mg-promo-template-title"><a target="_blank" href="' + mg_promo.addon_link + '">We offer the following Media Source Addons</a></p>\n\
		<ul>\n\
				<li class="small-3 medium-3 columns temp-ad-height">\n\
					<a href="' + mg_promo.facebook_link + '" target="_blank" >\n\
						<img class="mg-ad-image anime" width="99" height="89" src="' + mg_promo.pluginurl +'/images/templates/facebook-1.png" />\n\
					</a>\n\
					<h5>\n\
						<a href="' + mg_promo.facebook_link + '" target="_blank" ">FACEBOOK</a>\n\
					</h5>\n\
				</li>\n\
				<li class="small-3 medium-3 columns temp-ad-height">\n\
					<a href="' + mg_promo.vimeo_link + '" target="_blank" >\n\
						<img class="mg-ad-image anime" width="99" height="89" src="' + mg_promo.pluginurl +'/images/templates/vimeo-1.png" />\n\
					</a>\n\
					<h5>\n\
						<a href="' + mg_promo.vimeo_link + '" target="_blank" >VIMEO</a>\n\
					</h5>\n\
				</li>\n\
				<li class="small-3 medium-3 columns temp-ad-height">\n\
					<a href="' + mg_promo.instgram_link + '" target="_blank">\n\
						<img class="mg-ad-image anime" width="99" height="89" src="' + mg_promo.pluginurl +'/images/templates/instagram-1.png" />\n\
					</a>\n\
					<h5>\n\
						<a href="' + mg_promo.instgram_link + '" target="_blank">INSTAGRAM</a>\n\
					</h5>\n\
				</li>\n\
				<li class="small-3 medium-3 columns temp-ad-height">\n\
					<a href="' + mg_promo.flickr_link + '" target="_blank" >\n\
						<img class="mg-ad-image anime" width="99" height="89" src="' + mg_promo.pluginurl +'/images/templates/flickr-1.png" />\n\
					</a>\n\
					<h5>\n\
						<a href="' + mg_promo.flickr_link + '" target="_blank" >FLICKR</a>\n\
					</h5>\n\
				</li>\n\
		</ul><!--row-->\n\
   </div><!--mg-promo-->\n\
  </div>');
	}	
  jQuery('.wrap h2').prepend(
    '<div class="mg-logo"><div><a href="http://maxfoundry.com" target="_blank"><img src="' +
    mg_promo.pluginurl +
    '/images/max-foundry.png" alt="Max Foundry" /></a><br><a href="http://maxbuttons.com/?ref=mbpro" target="_blank">MaxButtons</a> and <a href="https://maxgalleria.com/downloads/media-library-plus-pro/?ref=mlppro" target="_blank">Media Library Folders Pro</a></div>\n\
    </div>'
  );
  jQuery('.wrap h1').prepend(
    '<div class="mg-logo"><div><a href="http://maxfoundry.com" target="_blank"><img src="' +
    mg_promo.pluginurl +
    '/images/max-foundry.png" alt="Max Foundry" /></a><br><a href="http://maxbuttons.com/?ref=mbpro" target="_blank">MaxButtons</a> and <a href="https://maxgalleria.com/downloads/media-library-plus-pro/?ref=mlppro" target="_blank">Media Library Folders Pro</a></div>\n\
    </div>'
		);
	jQuery('.wrap h1.wp-heading-inline').css('display','inline');
	
  !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs')	
	
	//jQuery("#add-close-btn").click(function() {
  jQuery(document).on("click", "#add-close-btn", function() {
		jQuery.ajax({
			type: "POST",
			async: true,
			data: { action: "mg_hide_gallery_ad",  nonce: mg_promo.nonce },
			url: mg_promo.admin_url,
			dataType: "html",
			success: function (data) {
				jQuery("#mg-gallery-ad").hide();          
			},
			error: function (err)
				{ alert(err.responseText);}
		});

	});		
	
});