<noscript>
	<div class="info">
		<i class="fa fa-info-circle"></i>
		<h1>You need to enable javascript to use this plugin</h1>
	</div>
</noscript>

<div class="info">
<i class="fa fa-info-circle"></i>
<p>
Choose which blog posts you want to include in your lead magnet.
</p>
</div>


	<div class="search">
		<form>
		<input type="text" class="filter" placeholder="search" />
		<span class="clear">x</span>
		<button type="submit">
		<i class="fa fa-search"></i>
		</button>
		</form>
	</div>
	

<div class="err error-no-posts">
Please select at least one post
</div>

<div class="col">


<?php $posts = $data['posts']; ?>

<?php if ($data['debug'] === true):  ?>
	<div class="info">
		<h2>Debug Info</h2>

		<dl>          
			<dt>Available RAM </dt>
			<dd><?php echo $data['mem']; ?>mb</dd>
					
			<dt>Low memory mode</dt>
			<dd><?php echo $data['low_mem_mode_display']; ?></dd>

			<dt>Posts shown</dt>
			<dd><?php echo count($posts); ?></dd>
						
			<dt>Total posts</dt>
			<dd><?php echo $data['total']; ?></dd>
						
			<dt>Max posts</dt>
			<dd><?php echo $data['post_limit']; ?></dd>
		</dl>
	</div>
<?php endif; ?>

<?php 
	if ($data['exit'])
	{
		die('Exiting');
	}
?>



<?php if (!$data['set_limit'] && ( $data['total'] != count($posts) )): ?>
	<!--div class="error">
		<h2>Oh dear! That is a lot of posts - Wordpress ran out of memory :(</h2>
		<p>
			Showing the most recent <?php echo count($posts); ?>
			of all <?php echo $data['total']; ?> posts and pages
		</p>
	</div-->
<?php endif; ?>

	<ul class="bea">
		<li></li>
	</ul>

	<script>
	var BeaconByPosts = <?php echo json_encode( $posts ); ?>;
	BN.totalPosts = <?php echo $data['total']; ?>;
	BN.perPage = <?php echo $data['per_page']; ?>;
	</script>
	<form action="<?php echo BEACONBY_CREATE_TARGET; ?>/api/ebook" method="post" target="_blank" class="select-posts">

	<input type="hidden" name="url" value="<?php echo get_site_url() ?>" />
	<input type="hidden" name="title" value="<?php echo get_bloginfo('name') ?>" />
	<input type="hidden" name="decription" value="<?php echo get_bloginfo('description') ?>" />




	<?php 
	if ( $posts ) :
		foreach ( $posts as $post ) :
			// if (!$data['low_mem_mode'])
			// {
				$cats = get_the_category( $post->ID );
				$post_cats = array();
				foreach  ($cats as $cat ) {
					$post_cats[] = $cat->cat_name;
				}
				$post->cats = implode( ',', $post_cats );
			// }
			// else
			// {
			// 	$post->cats = '';
			// }


			if (!$data['low_mem_mode'])
			{
				$tags = wp_get_post_tags( $post->ID );
				$post_tags = array();
				foreach  ($tags as $tag ) {
					$post_tags[] = $tag->name;
				}
				$post->tags = implode( ',', $post_tags );
				$post->main_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
			}
			else
			{
				$post->tags = '';
				$post->main_image = '';
			}

			$encoded = base64_encode ( serialize( $post ) );
	?>

	<div class="form-row type-<?php echo $post->post_type; ?>">
		<input type="checkbox" 
				class="post_toggle" 
				id="beacon_export_<?php echo $post->ID?>" />
		<input type="hidden" 
				class="post_data" 
				data-cats="<?php echo $post->cats; ?>" 
				data-tags="<?php echo $post->tags; ?>" 
				data-title="<?php echo $post->post_title; ?>"
				name="posts[<?php echo $post->id; ?>]" 
				value="<?php echo $encoded; ?>" />

		<label for="beacon_export_<?php echo $post->ID ?>">
		<b><?php echo $post->post_title; ?></b>
		<small><?php echo $post->cats; ?></small>
		<small><?php echo $post->tags; ?></small>
		</label>
	</div>

	<?php
		endforeach;
	endif;
	?>

</div>

<div class="col col2">


	<h3>Showing Post type</h3>
	<span class="checkbox">
		<input type="checkbox" id="toggle-post" name="show-post" checked /> 
		<label for="togglePost">Post</label>
	</span>
	<span class="checkbox">
		<input type="checkbox" id="toggle-page" name="show-page" checked />
		<label for="togglePage">Page</label>
	</span>

	<h3>Filter By Category</h3>
	<p>
	Click category to toggle
	</p>

	<span class="all-cat">Show All</span>
	<br>
	<?php 
	$categories = get_categories(); 
	foreach ( $categories as $cat ):
	?>
	<span class="toggle-cat"><?php echo $cat->name; ?></span>
	<?php endforeach; ?>


	<?php if (!$data['low_mem_mode']): ?>
	<h3>Filter By Tag</h3>
	<p>
	Click tag to toggle
	</p>
	<span class="all-tag">Show All</span>
	<br>
	<?php 
	$tags = get_tags(); 
	foreach ( $tags as $tag ):
	?>
	<span class="toggle-tag"><?php echo $tag->name; ?></span>
	<?php
	endforeach;
	?>
	<?php endif; ?>


</div>

<button class="button large create">Create</button>


<!--span class="fixed">
	<input type="checkbox" name="main_image" id="include-featured-images" checked />
	<?php if (!$data['low_mem_mode']): ?>
	<input type="hidden" name="show_main_image" value="1" />
	<label for="include-featured-images">Include featured images</label>
	<?php endif; ?>
</span-->


</form>

<div class="maxposts-warning">
	<p> <strong>Too many posts.</strong>  </p>
	<p>Please de-select some posts before creating your ebook.</p>
</div>

<script type="text/template" id="formRow">
	<div class="form-row type-{post_type}">
		<input type="checkbox" 
				class="post_toggle" 
				id="beacon_export_{ID}" />
		<input type="hidden" 
				class="post_data" 
				data-cats="{cats}" 
				data-tags="{tags}" 
				data-title="{post_title}"
				name="posts[{ID}]" 
				value="{encoded}" />

		<label for="beacon_export_{ID}">
		<b>{post_title}</b>
			<small>{cats}</small>
			<small>{tags}</small>
		</label>
	</div>
</script>
