<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_shortcode( 'FAG', 'FlickerAlbumGalleryShortCode' );
function FlickerAlbumGalleryShortCode( $Id ) {
	ob_start();
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'masonry' );
	wp_enqueue_script( 'wpfrank-fag-bootstrap-frontend-js', plugins_url( 'js/bootstrap.min.js', __FILE__ ), array( 'jquery' ), false, true );
	wp_enqueue_script( 'wpfrank-fag-jquery-blueimp-gallery-min-js', plugins_url( 'js/jquery.blueimp-gallery.min.js', __FILE__ ), array( 'jquery' ), false, true );
	wp_enqueue_script( 'wpfrank-fag-bootstrap-image-gallery-min-js', plugins_url( 'js/bootstrap-image-gallery.min.js', __FILE__ ), array( 'jquery' ), false, true );

	// CSS
	wp_enqueue_style( 'wpfrank-fag-bootstrap-frontend-css', FAG_PLUGIN_URL . 'css/bootstrap-frontend.css' );
	wp_enqueue_style( 'wpfrank-fag-blueimp-gallery-min-css', FAG_PLUGIN_URL . 'css/blueimp-gallery.min.css' );
	wp_enqueue_style( 'wpfrank-fag-site-css', FAG_PLUGIN_URL . 'css/fag-shortcode-style.css' );

	if ( isset( $Id['id'] ) ) {
		/**
		 * Load All Flickr Album Gallery Custom Post Type
		 */
			$FAG_CPT_Name = 'fa_gallery';
			$AllGalleries = array(
				'p'          => $Id['id'],
				'post_type'  => $FAG_CPT_Name,
				'orderby'    => 'ASC',
				'post_staus' => 'publish',
			);
			$loop         = new WP_Query( $AllGalleries );

			while ( $loop->have_posts() ) :
				$loop->the_post();
				/**
				 * Get All Photos from Gallery Details Post Meta
				 */
				$ID = get_the_ID();

				// old gallery fix
				$fag_gallery_age = '';
				if ( isset( $Id['gallery'] ) ) {
					$fag_gallery_age = $Id['gallery'];
				}
				if ( $fag_gallery_age == 'old' ) {
					$FAG_Albums = unserialize( get_post_meta( $ID, 'fag_settings', true ) );
				} else {
					$FAG_Albums = get_post_meta( $ID, 'fag_settings', true );
				}

				if ( is_array( $FAG_Albums ) ) {
					foreach ( $FAG_Albums as $FAG_Album ) {
						$FAG_API_KEY    = $FAG_Album['fag_api_key'];
						$FAG_Album_ID   = $FAG_Album['fag_album_id'];
						$FAG_Show_Title = isset( $FAG_Album['fag_show_title'] ) ? $FAG_Album['fag_show_title'] : '';
						$FAG_Col_Layout = isset( $FAG_Album['fag_col_layout'] ) ? $FAG_Album['fag_col_layout'] : 'col-md-3';
						$FAG_Custom_CSS = isset( $FAG_Album['fag_custom_css'] ) ? $FAG_Album['fag_custom_css'] : '';
						?>
							<style>
						<?php echo esc_html( $FAG_Custom_CSS ); ?>
							.flickr-img-responsive {
								width:100% !important;
								height:auto !important;
								display:block !important;
							}
							.LoadingImg img {
								max-width: 45px;
								max-height: 45px;
								box-shadow:  none;
							}
							.wpfrank-flickr-div{
								padding:15px;
							}
							@media (max-width: 786px){
								.col-md-3 {
									width:49.9%;
									float:left;
								}
							}
							.play-pause {
								display: none !important;
							}
							.gallery<?php echo esc_html( $ID ); ?> {
								overflow:hidden;
								clear: both;
							}
							.fnf{
								background-color: #a92929;
								border-radius: 5px;
								color: #fff;
								font-family: initial;
								text-align: center;
								padding:12px;
							}
							</style>
							<script type="text/javascript">
							jQuery(function() {
								jQuery('.gallery<?php echo esc_js( $ID ); ?>').flickr({
									apiKey: '<?php echo esc_js( $FAG_API_KEY ); ?>',
									photosetId: '<?php echo esc_js( $FAG_Album_ID ); ?>'
								});
							});

							;(function ($, window, document, undefined) {
								'use strict';
								var pluginName = "flickr",
									defaults = {
										apiKey: "",
										photosetId: "",
										errorText: "<div class='fnf'><i class='far fa-times-circle'></i> Error generating gallery.</div>",
										loadingSpeed: 38,
										photosLimit: 200
									},
									apiUrl = 'https://api.flickr.com/services/rest/',
									photos = [];

								// The actual plugin constructor
								function Plugin(element, options) {
									this.element = $(element);
									this.settings = $.extend({}, defaults, options);
									this._defaults = defaults;
									this._name = pluginName;

									this._hideSpinner = function() {
										this.element.find('.spinner-wrapper').hide().find('*').hide();
									};

									this._printError = function() {
										 //this.element.find('.gallery-container').append($("<div></div>", { "class": "col-lg-12 col-lg-offset-1" })
										this.element.find('.gallery-container').append($("<div></div>", { "class": "col-lg-12" })
											.append($("<div></div>", { "class": "error-wrapper" })
												.append($("<span></span>", { "class": "label label-danger error" })
													.html(this.settings.errorText))));
									};

									this._flickrAnimate = function() {
										this.element.find('.gallery-container img').each($.proxy(function(index, el) {
											var image = el;
											setTimeout(function() {
												$(image).parent().fadeIn();
											}, this.settings.loadingSpeed * index);
										}, this));
									};

									this._printGallery = function(photos) {
										var element = this.element.find('.gallery-container');
										$.each(photos, function(key, photo) {
											var img = $('<img>', { 'class': 'thumb img-thumbnail flickr-img-responsive', src: photo.thumbnail, 'alt': photo.title });
											element.append($('<div></div>', { 'class': ' <?php echo esc_js( $FAG_Col_Layout ); ?> col-sm-4 col-center wpfrank-flickr-div' })
												.append($('<a></a>', { 'class': '', href: photo.href, 'data-gallery': '', 'title': photo.title }).hide()
													.append(img)));
										});

										element.imagesLoaded()
											.done($.proxy(this._flickrAnimate, this))
											.always($.proxy(this._hideSpinner, this));
									};

									this._flickrPhotoset = function(photoset) {
										var _this = this;
										
										photos[photoset.id] = [];
										$.each(photoset.photo, function(key, photo) {
											// Limit number of photos.
											if(key >= _this.settings.photosLimit) {
												return false;
											}

											photos[photoset.id][key] = {
												thumbnail: 'https://farm' + photo.farm + '.static.flickr.com/' + photo.server + '/' + photo.id + '_' + photo.secret + '_q.jpg',
												href: 'https://farm' + photo.farm + '.static.flickr.com/' + photo.server + '/' + photo.id + '_' + photo.secret + '_b.jpg',
												title: photo.title
											};
										});

										this._printGallery(photos[photoset.id]);
									};

									this._onFlickrResponse = function(response) {
										if(response.stat === "ok") {
											 this._flickrPhotoset(response.photoset);
										}
										else {
											this._hideSpinner();
											this._printError();
										}
									};

									this._flickrRequest = function(method, data) {
										var url = apiUrl + "?format=json&jsoncallback=?&method=" + method + "&api_key=" + this.settings.apiKey;

										$.each(data, function(key, value) {
											url += "&" + key + "=" + value;
										});

										$.ajax({
											dataType: "json",
											url: url,
											context: this,
											success: this._onFlickrResponse
										});
									};

									this._flickrInit = function () {
										this._flickrRequest('flickr.photosets.getPhotos', {
											photoset_id: this.settings.photosetId
										});
									};

									// Init
									this.init();
								}

								Plugin.prototype = {
									init: function () {
										this._flickrInit();
									}
								};

								// Wrapper
								$.fn[pluginName] = function (options) {
									this.each(function () {
										if (!$.data(this, "plugin_" + pluginName)) {
											$.data(this, "plugin_" + pluginName, new Plugin(this, options));
										}
									});

									// Chain
									return this;
								};
							})(jQuery, window, document);
							</script>
							<div class="gallery<?php echo esc_attr( $ID ); ?>">
								<!-- Gallery Thumbnails -->
						<?php if ( $FAG_Show_Title == 'yes' ) { ?>
								<h3><?php echo esc_html_e( get_the_title( $ID ) ); ?></h3>
								<?php } ?>
								<div class="row">
									<div class="col-xs-12 spinner-wrapper">
										<div class="LoadingImg"><img src="<?php echo esc_url( FAG_PLUGIN_URL . 'img/loading.gif' ); ?>" /></div>
									</div>
									<div align="center" class="gallery-container"></div>
								</div>
							</div>
						<?php
					}// end of foreach
				}//end of is_array
			endwhile;
			?>
			
			<!-- Blueimp gallery -->
			<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
				<div class="slides"></div>
				<h3 class="title"></h3>
				<a class="prev">‹</a>
				<a class="next">›</a>
				<a class="close">×</a>
				<a class="play-pause"></a>
				<ol class="indicator"></ol>
				<div class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" aria-hidden="true">&times;</button>
								<h4 class="modal-title"></h4>
							</div>
							<div class="modal-body next"></div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default pull-left prev">
									<i class="glyphicon glyphicon-chevron-left"></i>
									<?php esc_html_e( 'Previous', 'flickr-album-gallery' ); ?>
								</button>
								<button type="button" class="btn btn-primary next">
									<?php esc_html_e( 'Next', 'flickr-album-gallery' ); ?>
									<i class="glyphicon glyphicon-chevron-right"></i>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<script type="text/javascript">
			jQuery(function() {
				// Set blueimp gallery options
				jQuery.extend(blueimp.Gallery.prototype.options, {
					useBootstrapModal: false,
					hidePageScrollbars: false
				});
			});
			</script>
			<?php
	} else {
		$fag_allowed_shortcode_msg = array(
			'div' => array(
				'align' => array(),
				'class' => array(),
			),
		);
		echo wp_kses( "<div align='center' class='alert alert-danger'>" . __( 'Sorry! Invalid Flickr Album Shortcode Embedded', 'flickr-album-gallery' ) . '</div>', $fag_allowed_shortcode_msg );
	}
	//wp_reset_query();
	wp_reset_postdata();
	return ob_get_clean();
} //end of shortcode function
?>
