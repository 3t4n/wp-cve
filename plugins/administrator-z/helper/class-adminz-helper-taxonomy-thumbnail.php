<?php 
namespace Adminz\Helper;
class ADMINZ_Helper_Taxonomy_Thumbnail{	
	static $options_group = 'adminz_taxonomy_thumbnail';	
	static $metakey = "thumbnail_id";
	static $called = false;	
	function __construct($tax = []){		
		if(empty($tax)) return;
		if(!$tax) return;

		if(!self::$called){
			foreach ($tax as $t) {
				add_action($t.'_add_form_fields',[$this,'thumb_in_add_tax']);
				add_action($t.'_edit_form_fields',[$this,'thumb_in_edit_tax']);					
		    }		
		    add_action('edit_term',[$this,'categoryimagesave']);
			add_action('create_term',[$this,'categoryimagesave']);
			self::$called = true;			
		}		
	}
	static function get_input_image_field($taxonomy){
		$default = '<a href="#" class="button adminz-upl">Upload image</a>
	      	<a href="#" class="button adminz-rmv" style="display:none">Remove image</a>
	      	<input type="hidden" name="tag-image" id="tag-image" value="">';
		if(isset($taxonomy->term_id)){			
			$image_id = get_term_meta($taxonomy->term_id,self::$metakey,true);
			$image = wp_get_attachment_image_src( $image_id );
			if($image){
				return 
				'<a href="#" class="button adminz-upl"><img src="' . $image[0] . '" /></a>
		      	<a href="#" class="button adminz-rmv">Remove image</a>
		      	<input type="hidden" name="tag-image" id="tag-image" value="' . $image_id . '">'
		      	;
		      }
		}
		return $default;
		
	}
	static function thumb_in_add_tax($taxonomy){ 

		?>
	    <div class="form-field">
			Thumbnail
			<?php echo self::get_input_image_field($taxonomy); ?>
		</div>
		<?php 		
	}
	static function thumb_in_edit_tax($taxonomy){ 

		?>
		<tr class="form-field">
			<th scope="row" valign="top">Thumbnail</th>
			<td>
				<?php echo self::get_input_image_field($taxonomy); ?>
			</td>
		</tr>		         
		<?php 		
	}
	static function categoryimagesave($term_id){
		//$termslug = get_term($term_id)->taxonomy;
	    if(isset($_POST['tag-image'])){
	        update_term_meta($term_id, self::$metakey, sanitize_text_field($_POST['tag-image']));
	    }
	}

}