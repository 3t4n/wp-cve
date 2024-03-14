<?php
	add_action('init', 'ysg_type_post_video');
	function ysg_type_post_video() { 
		$labels = array(
			'name' => __('V&iacute;deos', 'youtube-simple-gallery'),
			'singular_name' => __('V&iacute;deo', 'youtube-simple-gallery'),
			'add_new' => __('Adicionar Novo', 'youtube-simple-gallery'),
			'add_new_item' => __('Adicionar Novo V&iacute;deo', 'youtube-simple-gallery'),
			'edit_item' => __('Editar Item', 'youtube-simple-gallery'),
			'new_item' => __('Novo V&iacute;deo', 'youtube-simple-gallery'),
			'view_item' => __('Visualizar V&iacute;deo', 'youtube-simple-gallery'),
			'search_items' => __('Procurar V&iacute;deo', 'youtube-simple-gallery'),
			'not_found' =>  __('Nenhum registro encontrado', 'youtube-simple-gallery'),
			'not_found_in_trash' => __('Nenhum registro encontrado na lixeira', 'youtube-simple-gallery'),
			'parent_item_colon' => '',
			'menu_name' => __('V&iacute;deos', 'youtube-simple-gallery')
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'public_queryable' => true,
			'show_ui' => true,          
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'menu_icon' => '',
			'has_archive' => true,
			'hierarchical' => false,
			'menu_position' => 5,
			'register_meta_box_cb' => 'ysg_video_meta_box',      
			'supports' => array('title', 'thumbnail')
		);
	register_post_type( 'youtube-gallery' , $args );
	flush_rewrite_rules();
	}
	
	// Styling for the custom post type icon
	add_action( 'admin_head', 'ysg_chr_icon_UtubeGallery' );
	function ysg_chr_icon_UtubeGallery() {
	echo'
	    <style type="text/css" media="screen">
	        #icon-edit.icon32-posts-youtube-gallery {background: url("'.plugins_url( '/images/icon-youtube-32.png' , __FILE__ ).'") no-repeat;}
	    </style>';
	}
	
	function ysg_video_meta_box(){        
		add_meta_box('meta_box_video', __('Detalhes do V&iacute;deo', 'youtube-simple-gallery'), 'ysg_meta_box_meta_video', 'youtube-gallery', 'normal', 'high');
	}

	function ysg_meta_box_meta_video(){
		global $post;
		// Values Saved
		$metaBoxUrl = get_post_meta($post->ID, 'valor_url', true); 
		$metaBoxDesc = get_post_meta($post->ID, 'valor_desc', true);
		$meta_element_quality = get_post_meta($post->ID, 'custom_element_grid_quality_meta_box', true);
		$meta_element_similar = get_post_meta($post->ID, 'radio_similiar', true);
		$meta_element_controles = get_post_meta($post->ID, 'radio_controles', true);
		$meta_element_title = get_post_meta($post->ID, 'radio_title', true);
		$metaIdVideo = ysg_youtubeEmbedFromUrl($metaBoxUrl);
		// Verication Radio Button Nulled
		if(	$meta_element_similar == null || $meta_element_controles == null || $meta_element_title == null ){
			$nulled_vefication = 'checked="checked"';
		};
	?>
		<h4 class="title-ysg"><?php _e('Detalhes do V&iacute;deo', 'youtube-simple-gallery');?></h4>
		<ul>
			<li>
				<h4><label for="inputValorUrl" style="width:100%; display:block; font-weight: bold;"><?php _e('URL do v&iacute;deo:', 'youtube-simple-gallery');?></label></h4>
				<input style="width:100%; display:block;" type="text" name="valor_url" id="inputValorUrl" value="<?php echo $metaBoxUrl;?>" />
			</li>
			<li>
				<em style="padding: 5px 0; display: block; color: #666;">
					<b><?php _e('Exemplos de modelos poss&iacute;veis:', 'youtube-simple-gallery');?></b>
					<ul>
						<li>&bull; http://www.youtube.com/watch?v=UzifCbU_gJU</li>
						<li>&bull; http://www.youtube.com/watch?v=UzifCbU_gJU&feature=related</li>
						<li>&bull; http://youtu.be/UzifCbU_gJU</li>
					</ul>
				</em>
			</li>
			<?php if($metaIdVideo != null){ ?>
			<li>
				<h4><?php _e('Imagem Original do V&iacute;deo', 'youtube-simple-gallery') ;?>:</h4>
				<?php echo '<img title="'. get_the_title().'" alt="'. get_the_title().'" src="http://img.youtube.com/vi/' . $metaIdVideo .'/mqdefault.jpg" />';	?>				
				<br />
				<em style="padding: 5px 0; display: block; color: #666;"><?php echo __('&bull; Aten&ccedil;&atilde;o: Quando tiver uma imagem destacada essa ser&aacute; alterada pela mesma.', 'youtube-simple-gallery') ;?></em>
			</li>
			<?php }; ?>
			<li>
				<h4><?php _e('Qualidade do V&iacute;deo', 'youtube-simple-gallery') ;?>:</h4>
				<div class="ysg-select-style">
					<select name="custom_element_grid_quality" id="custom_element_grid_quality">
						<option value="default" <?php selected( $meta_element_quality, 'default' ); ?>><?php _e('Padr&atilde;o', 'youtube-simple-gallery');?></option>
						<option value="small" <?php selected( $meta_element_quality, 'small' ); ?>><?php _e('Pequena', 'youtube-simple-gallery');?></option>
						<option value="medium" <?php selected( $meta_element_quality, 'medium' ); ?>><?php _e('M&eacute;dia', 'youtube-simple-gallery');?></option>
						<option value="large" <?php selected( $meta_element_quality, 'large' ); ?>><?php _e('Grande', 'youtube-simple-gallery');?></option>
						<option value="hd720" <?php selected( $meta_element_quality, 'hd720' ); ?>><?php _e('HD 720', 'youtube-simple-gallery');?></option>
						<option value="hd1080" <?php selected( $meta_element_quality, 'hd1080' ); ?>><?php _e('HD 1080', 'youtube-simple-gallery');?></option>
						<option value="highres" <?php selected( $meta_element_quality, 'highres' ); ?>><?php _e('Alta Resolu&ccedil;&atilde;o', 'youtube-simple-gallery');?></option>
					</select>
				</div>
				<br />
			</li>
			<li>
				<h4><?php _e('Sugeriu v&iacute;deos quando o v&iacute;deo terminar', 'youtube-simple-gallery') ;?>:</h4>
				<ul>
					<li><input class="ysg-radio-button" type="radio" name="radio_similiar" id="show_similar" value="1" <?php echo $nulled_vefication . ($meta_element_similar == '1')? 'checked="checked"':''; ?>><label for="show_similar"><?php _e('Sim','youtube-simple-gallery');?></label></li>
					<li><input class="ysg-radio-button" type="radio" name="radio_similiar" id="hide_similar" value="0" <?php echo ($meta_element_similar == '0')? 'checked="checked"':''; ?>><label for="hide_similar"><?php _e('N&atilde;o','youtube-simple-gallery');?></label></li>
				</ul>
			</li>
			<li>
				<h4><?php _e('Mostrar controles de v&iacute;deo', 'youtube-simple-gallery') ;?>:</h4>
				<ul>
					<li><input class="ysg-radio-button" type="radio" name="radio_controles" id="show_controles" value="1" <?php echo $nulled_vefication . ($meta_element_controles == '1')? 'checked="checked"':''; ?>><label for="show_controles"><?php _e('Sim','youtube-simple-gallery');?></label></li>
					<li><input class="ysg-radio-button" type="radio" name="radio_controles" id="hide_controles" value="0" <?php echo ($meta_element_controles == '0')? 'checked="checked"':''; ?>><label for="hide_controles"><?php _e('N&atilde;o','youtube-simple-gallery');?></label></li>
				</ul>
			</li>
			<li>
				<h4><?php _e('Mostrar t&iacute;tulo de v&iacute;deo e a&ccedil;&otilde;es do v&iacute;deo', 'youtube-simple-gallery') ;?>:</h4>
				<ul>
					<li><input class="ysg-radio-button" type="radio" name="radio_title" id="show_title" value="1" <?php echo $nulled_vefication . ($meta_element_title == '1')? 'checked="checked"':''; ?>><label for="show_title"><?php _e('Sim','youtube-simple-gallery');?></label></li>
					<li><input class="ysg-radio-button" type="radio" name="radio_title" id="hide_title" value="0" <?php echo ($meta_element_title == '0')? 'checked="checked"':''; ?>><label for="hide_title"><?php _e('N&atilde;o','youtube-simple-gallery');?></label></li>
				</ul>
			</li>
			<li>
				<h4><label for="inputValorDesc" style="width:100%; display:block; font-weight: bold;"><?php _e('Descri&ccedil;&atilde;o:', 'youtube-simple-gallery');?></label></h4>
				<input style="width:100%; display:block;" type="text" name="valor_desc" id="inputValorDesc" value="<?php echo $metaBoxDesc;?>" />
			</li>
			<li>
				<em style="padding: 5px 0; display: block; color: #666;">
					<?php _e('Insir&aacute; um texto se desejar:', 'youtube-simple-gallery');?>
				</em>
			</li>
		</ul>
		<?php
	}
	add_action('save_post', 'ysg_save_video_post');

	function ysg_save_video_post(){
	    global $post;        
		update_post_meta($post->ID, 'valor_url', $_POST['valor_url']);
		if(isset($_POST["custom_element_grid_quality"])){
			$meta_element_quality = $_POST['custom_element_grid_quality'];
			update_post_meta($post->ID, 'custom_element_grid_quality_meta_box', $meta_element_quality);
		}
		update_post_meta($post->ID, 'radio_similiar', $_POST['radio_similiar']);
		update_post_meta($post->ID, 'radio_controles', $_POST['radio_controles']);
		update_post_meta($post->ID, 'radio_title', $_POST['radio_title']);
		update_post_meta($post->ID, 'valor_desc', $_POST['valor_desc']);
	}