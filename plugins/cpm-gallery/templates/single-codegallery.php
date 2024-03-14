<?php get_header();?>
	<?php $galleryimages = get_post_meta($post->ID,'code_gallery_images');
	//var_dump($galleryimages[0]);
			
	?>
	<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
			    <div class="slides"></div>
			    <h3 class="title"></h3>
			    <a class="prev">‹</a>
			    <a class="next">›</a>
			    <a class="close">×</a>
			    <a class="play-pause"></a>
			    <ol class="indicator"></ol>
			</div>
	<div class="container">
	<h2><?php the_title(); ?>
	
		<div class="imgdiv" id="links">

			<?php  if($galleryimages[0]!= NULL){foreach ($galleryimages[0] as $key=>$value ) {
				if($value != NULL){?>
					<div class="images"><a href="<?php echo $value;?>" >
						<img src="<?php echo $value;?>" >
					</a></div>
			<?php }}}?>
		</div>
	</div>
	<script>
		document.getElementById('links').onclick = function (event) {
		    event = event || window.event;
		    var target = event.target || event.srcElement,
		        link = target.src ? target.parentNode : target,
		        options = {index: link, event: event},
		        links = this.getElementsByTagName('a');
		    blueimp.Gallery(links, options);
		};
	</script>
<?php get_footer();?>
