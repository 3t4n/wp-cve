<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Adds widget: Rs Author Info Box
class Rs_Author_Info_Box_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'rs_info_box_widget',
			esc_html__( '[ RSWPTHEMES ] Rs Author Info Box', 'rs-author-info-box' )
		);
		add_action( 'admin_footer', array( $this, 'media_fields' ) );
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'media_fields' ) );
	}

	private $widget_fields = array(
		array(
			'label' => 'Author Name',
			'id' => 'author_name',
			'type' => 'text',
		),
		array(
			'label' => 'Author Profession',
			'id' => 'author_profession',
			'type' => 'text',
		),
		array(
			'label' => 'Short Description',
			'id' => 'short_description',
			'type' => 'textarea',
		),
		array(
			'label' => 'Banner Image',
			'id' => 'banner_image',
			'type' => 'media',
		),
		array(
			'label' => 'Author Image',
			'id' => 'author_image',
			'type' => 'media',
		),
		array(
			'label' => 'Social Links',
			'id' => 'separator',
			'type' => 'text',
		),
		array(
			'label' => 'Facebook',
			'id' => 'facebook',
			'type' => 'text',
		),
		array(
			'label' => 'Instagram',
			'id' => 'instagram',
			'type' => 'text',
		),
		array(
			'label' => 'Twitter',
			'id' => 'twitter',
			'type' => 'text',
		),
		array(
			'label' => 'Linkedin',
			'id' => 'linkedin',
			'type' => 'text',
		),
		array(
			'label' => 'Pinterest',
			'id' => 'pinterest',
			'type' => 'text',
		),
		array(
			'label' => 'GoodReads',
			'id' => 'goodreads',
			'type' => 'text',
		),
		array(
			'label' => 'Wattpad',
			'id' => 'wattpad',
			'type' => 'text',
		),
		array(
			'label' => 'Hide This Widget From Post Page',
			'id' => 'hide_from_post_page',
			'type' => 'checkbox',
		),
	);

	public function widget( $args, $instance ) {

		$displayWidget = true;
	    if (is_single()) :
	    	if ('1' === $instance['hide_from_post_page']) {
    			$displayWidget = false;
	    	}else{
	    		$displayWidget = true;
	    	}
    	else :
    		$displayWidget = true;
    	endif;


$getActivateTheme = get_stylesheet();

	if (true === $displayWidget) :
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
			$authorImage = (array_key_exists('author_image', $instance) ? $instance['author_image'] : '');
			$bannerImage = (array_key_exists('banner_image', $instance) ? $instance['banner_image'] : '');
			$authorName = (array_key_exists('author_name', $instance) ? $instance['author_name'] : '');
			$authorProfession = (array_key_exists('author_profession', $instance) ? $instance['author_profession'] : '');
			$authorDesc = (array_key_exists('short_description', $instance) ? $instance['short_description'] : '');
			?>
			<div class="rs-author-info-box_author-bio-widget">
				<div class="rs-author-info-box_author-bio-image-wrapper">
					<div class="rs-author-info-box_author-bio-image-inner<?php echo (empty($bannerImage)) ? ' no-banner-image' : '';?>">
						<?php
						if (!empty($bannerImage))  :
						?>
						<div class="banner-image">
							<img src="<?php echo esc_url($bannerImage); ?>" alt="<?php echo esc_attr($authorName);?>">
						</div>
						<?php endif;
						if(!empty($authorImage)) :
						?>
						<div class="profile-picture">
							<img src="<?php echo esc_url($authorImage);?>" alt="<?php echo esc_attr($authorName);?>">
						</div>
						<?php endif; ?>
					</div>
					<div class="rs-author-info-box_author-bio-content">
						<?php
						if (!empty($authorName)) :
						?>
						<div class="author-name">
							<h4><?php echo esc_html($authorName); ?></h4>
						</div>
						<?php endif;
						if (!empty($authorProfession)) :
						?>
						<div class="author-profession">
							<p><?php echo esc_html($authorProfession); ?></p>
						</div>
						<?php
						endif;
						if (!empty($authorDesc)) :
						?>
						<div class="author-description">
							<p><?php echo wp_kses_post($authorDesc); ?></p>
						</div>
						<?php
						endif; ?>
					</div>
					<div class="rs-author-info-box_social_link">
						<div class="social-link">
							<?php if(!empty($instance['facebook'])) : ?>
							<a href="<?php echo esc_url($instance['facebook']);?>" class="rswpthemes-icon icon-facebook"></a>
							<?php endif;
							if (!empty($instance['twitter'])) :
							?>
							<a href="<?php echo esc_url($instance['twitter']);?>" class="rswpthemes-icon icon-twitter"></a>
							<?php endif;
							if (!empty($instance['linkedin'])) :
							?>
							<a href="<?php echo esc_url($instance['linkedin']);?>" class="rswpthemes-icon icon-linkedin"></a>
							<?php endif;
							if (!empty($instance['instagram'])) :
							?>
							<a href="<?php echo esc_url($instance['instagram']);?>" class="rswpthemes-icon icon-instagram"></a>
							<?php endif;
							if (!empty($instance['pinterest'])) :
							?>
							<a href="<?php echo esc_url($instance['pinterest']);?>" class="rswpthemes-icon icon-pinterest"></a>
							<?php endif;
							if (!empty($instance['goodreads'])) :
							?>
							<a href="<?php echo esc_url($instance['goodreads']);?>" class="rswpthemes-icon icon-goodreads"></a>
							<?php endif;
							if (!empty($instance['wattpad'])) :
							?>
							<a href="<?php echo esc_url($instance['wattpad']);?>" class="rswpthemesicon icon-wattpad"></a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		<?php
		echo $args['after_widget'];
	endif;
	}

	public function media_fields() {
		?><script>
			jQuery(document).ready(function($){
				if ( typeof wp.media !== 'undefined' ) {
					var _custom_media = true,
					_orig_send_attachment = wp.media.editor.send.attachment;
					$(document).on('click','.custommedia',function(e) {
						var send_attachment_bkp = wp.media.editor.send.attachment;
						var button = $(this);
						var id = button.attr('id');
						_custom_media = true;
							wp.media.editor.send.attachment = function(props, attachment){
							if ( _custom_media ) {
								$('input#'+id).val(attachment.url);
								$('span#preview'+id).css('background-image', 'url('+attachment.url+')');
								$('input#'+id).trigger('change');
							} else {
								return _orig_send_attachment.apply( this, [props, attachment] );
							};
						}
						wp.media.editor.open(button);
						return false;
					});
					$('.add_media').on('click', function(){
						_custom_media = false;
					});
					$(document).on('click', '.remove-media', function() {
						var parent = $(this).parents('p');
						parent.find('input[type="media"]').val('').trigger('change');
						parent.find('span').css('background-image', 'url()');
					});
				}
			});
		</script><?php
	}

	public function field_generator( $instance ) {
		$output = '';
		foreach ( $this->widget_fields as $widget_field ) {

			$default = '';
			if ( isset($widget_field['default']) ) {
				$default = $widget_field['default'];
			}
			$widget_value = ! empty( $instance[$widget_field['id']] ) ? $instance[$widget_field['id']] : esc_html__( $default, 'rs-author-info-box' );
			switch ( $widget_field['type'] ) {
				case 'media':
					$media_url = '';
					if ($widget_value) {
						$media_url = $widget_value;
					}
						?>
						<p>
							<label for="<?php echo esc_attr( $this->get_field_id( $widget_field['id'] ) );?>"><?php echo esc_html( $widget_field['label']); ?></label>
							<input style="display:none;" class="widefat" id="<?php echo esc_attr( $this->get_field_id( $widget_field['id'] ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $widget_field['id'] ) ); ?>" type="<?php echo esc_attr($widget_field['type']); ?>" value="<?php echo esc_url($widget_value); ?>">
							<span id="preview<?php echo esc_attr( $this->get_field_id( $widget_field['id'] ) );?>" style="margin-right:10px;border:2px solid #eee;display:block;width: 100px;height:100px;background-image:url(<?php echo esc_url($media_url);?>);background-size:contain;background-repeat:no-repeat;"></span>
							<button id="<?php echo esc_attr($this->get_field_id( $widget_field['id'] ));?>" data-inputid="<?php echo esc_attr($this->get_field_id( $widget_field['id'] ));?>" class="button select-media custommedia"><?php esc_html_e('Add Media', 'rs-author-info-box'); ?></button>
							<input style="width: 19%;" class="button remove-media" id="buttonremove" name="buttonremove" type="button" value="<?php esc_attr_e('Clear', 'rs-author-info-box');?>" />
						</p>
						<?php
					break;
				case 'checkbox':
						?>
						<p>
							<input class="checkbox" type="checkbox" <?php echo esc_attr(checked( $widget_value, true, false )); ?> id="<?php echo esc_attr( $this->get_field_id( $widget_field['id'] ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $widget_field['id'] ) ); ?>" value="1">
							<label for="<?php echo esc_attr( $this->get_field_id( $widget_field['id'] ) ); ?>"><?php echo esc_attr( $widget_field['label'] ); ?></label>
						</p>
						<?php
					break;
				case 'textarea':
					?>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( $widget_field['id'] ) ); ?>"><?php echo esc_attr( $widget_field['label'] ); ?>:</label>
						<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( $widget_field['id'] ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $widget_field['id'] ) );?>" rows="6" cols="6"><?php echo wp_kses_post($widget_value);?></textarea>
					</p>
					<?php
					break;
				default:
				?>
				<p>
					<?php
					if ('separator' == $widget_field['id']) {
						?>
						<h2><?php echo esc_attr($widget_field['label']); ?></h2>
						<?php
					}else{
						?>
							<label for="<?php echo esc_attr( $this->get_field_id( $widget_field['id'] ) );?>"><?php echo esc_attr( $widget_field['label']);?>:</label>
						<?php
					}
					if ('separator' != $widget_field['id']):
						?>
						<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $widget_field['id'] ) );?>" name="<?php echo esc_attr( $this->get_field_name( $widget_field['id'] ) );?>" type="<?php echo esc_attr($widget_field['type']);?>" value="<?php echo esc_attr( $widget_value );?>">
						<?php
					endif;
					?>
				</p>
				<?php
			}
		}
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'rs-author-info-box' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'rs-author-info-box' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
		$this->field_generator( $instance );
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		foreach ( $this->widget_fields as $widget_field ) {
			switch ( $widget_field['type'] ) {
				case 'media':
					$instance[$widget_field['id']] = ( ! empty( $new_instance[$widget_field['id']] ) ) ? esc_url( $new_instance[$widget_field['id']] ) : '';
				break;
				case 'textarea':
					$instance[$widget_field['id']] = ( ! empty( $new_instance[$widget_field['id']] ) ) ? wp_kses_post( $new_instance[$widget_field['id']] ) : '';
				break;
				default:
					$instance[$widget_field['id']] = ( ! empty( $new_instance[$widget_field['id']] ) ) ? strip_tags( $new_instance[$widget_field['id']] ) : '';
			}
		}
		return $instance;
	}
}

function register_rs_info_box_widget() {
	register_widget( 'Rs_Author_Info_Box_Widget' );
}
add_action( 'widgets_init', 'register_rs_info_box_widget' );