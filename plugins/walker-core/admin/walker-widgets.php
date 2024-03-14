<?php
/**
 * Social media icons widgets for walkerwp themes
 *
 * @package walker_core
 * @since version 1.0.0
 */
if ( wc_fs()->can_use_premium_code() ) {
	$theme = wp_get_theme();
	if ( 'Gridchamp' == $theme->name || 'Gridchamp' == $theme->parent_theme  ):
		add_action( 'widgets_init', 'walker_core_pricing_table_dynamic_sidebar' );
		if( ! function_exists('walker_core_pricing_table_dynamic_sidebar')) :
			function walker_core_pricing_table_dynamic_sidebar() {
			 register_sidebar( array(
				    'name'          => esc_html__( 'Pricing Table Home Section', 'pager' ),
				    'id'            => 'pricing-table-section',
				    'description'   => esc_html__( 'Add Pricing Table To be shown on Pricing Table Section of Homepage.', 'pager' ),
				    'before_widget' => '<section id="%1$s" class="widget %2$s">',
				    'after_widget'  => '</section>',
				    'before_title'  => '<h2 class="widget-title">',
				    'after_title'   => '</h2>',
				  ) );
			} 
		endif;
		class WalkerCore_Social_Media_Widget extends WP_Widget {
			public function __construct() {
			    parent::__construct(
			      'walker_core_social_media_widget', 
			      __( 'WalkerWP Social Media', 'walker-core' ), 
			      array( 'description' => __( 'Social Media Widgets for Site', 'walker-core' ), ) // Args
			    );
			}
			
			/**
			   * Outputs the content of the widget
			   *
			   * @param array $args
			   * @param array $instance
			   */
			public function widget( $args, $instance ) {
				echo $args['before_widget'];
				if ( ! empty( $instance['title'] ) ) {
					echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
				}

				ob_start();
				require WALKER_CORE_PATH . 'admin/partials/social-media.php';
				$strrr= ob_get_clean();
				echo $strrr;
				echo $args['after_widget'];
				
			}
			
			/**
				* Outputs the options form on admin
				*
				* @param array $instance The widget options
				*/
			public function form( $instance ) {
				$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Follow us ', 'walker-core' );
				?>
				<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'walker-core' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
				</p>
				<?php 
			}
			
			/**
				* Processing widget options on save
				*
				* @param array $new_instance The new options
				* @param array $old_instance The previous options
				*/
			public function update( $new_instance, $old_instance ) {
				$instance = array();
				$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
				return $instance;
			}
			
		}
		function register_walker_core_social_media_widget() {
		    register_widget( 'WalkerCore_Social_Media_Widget' );
		}
		add_action( 'widgets_init', 'register_walker_core_social_media_widget' );




class Walker_Core_Pricing_Table_Widget extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  function __construct() {
    parent::__construct(
      'walker_core_pricing_table', // Base ID
      __('WalkerWP Pricing Table', 'walker-core'), // Widget Name
      array( 'description' => __( 'Pricing Table Widget for WalkerWP Themes', 'walker-core' ), )
    );
    add_action( 'admin_enqueue_scripts', array( $this, 'walker_core_pricing_table_widget_scripts' ) );
  }
public function walker_core_pricing_table_widget_scripts(){
	wp_enqueue_script( 'media-upload' );
    wp_enqueue_media();
    wp_enqueue_script('walker-core-media-upload', WALKER_CORE_URL .'/admin/js/walker-core-media-upload.js', false, '1.0.0', true);
}
  
  // array $instance Saved values from database.
  public function widget( $args, $instance ) {
    $title = apply_filters( 'widget_title', $instance['title'] ); ?>

   
            <div class="walkerwp-pricing-table">
	            <?php $target_condition = $instance['features_link_url_target'];
	             if($target_condition == 1){
	             $link_target= "blank";

	             }else{
	              $link_target= "self";
	             }
	             $image_uri = ! empty( $instance['image_uri'] ) ? $instance['image_uri'] : '';
	             $decimal_digit = ! empty( $instance['decimal_digit'] ) ? $instance['decimal_digit'] : '';
	             $badge_text = ! empty( $instance['badge_text'] ) ? $instance['badge_text'] : '';
	             $price_currency = ! empty( $instance['price_currency'] ) ? $instance['price_currency'] : '';
	             $package_price = ! empty( $instance['package_price'] ) ? $instance['package_price'] : '';
	             $cycle_period = ! empty( $instance['cycle_period'] ) ? $instance['cycle_period'] : '';
	             $features_text = ! empty( $instance['features_text'] ) ? $instance['features_text'] : '';
	             $features_link_url = ! empty( $instance['features_link_url'] ) ? $instance['features_link_url'] : '';
	             $button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : '';
	            ?>
	            <?php if($badge_text){?>
	            	<span class="badge-text"><?php echo $badge_text;?></span>
	            <?php } ?>
	            <div class="package-header">
	            	
	            	
	            	<?php if($image_uri){?>
	            		<span class="pricing-table-img"><img src='<?php echo esc_url($image_uri); ?>' /></span>
	            	<?php	} ?>
	            	<?php if($title){?>
	            		<h4 class="package_title"><?php echo $title;?></h4>
	            	<?php } ?>
	            	
	            	
	            </div>
	            <div class="package-pricing">
	            	<?php if($price_currency){?>
	            		<span class="currency_symbol"><?php echo $price_currency;?></span>
	            	<?php }?>
	            	
	            	<?php if($package_price){?>
	            		<span class="package_price"><?php echo $package_price;?></span>
	            	<?php }?>
	            	<?php if($decimal_digit){?>
	            		<span class="price_decimal"><?php echo $decimal_digit;?></span>
	            	<?php }?>
	            	<?php if($cycle_period){?>
	            		<span class="packege_cyple_perios">/ <?php echo $cycle_period;?></span>
	            	<?php }?>
	            	
	             	
	            </div>
	            
	            <?php if($features_text){?>
	            	<div class="features-list">
			            <ul>
			            	<?php echo $features_text;?>
			            </ul>
			         </div>
		        <?php } ?>
		       
	            
            	<?php if($features_link_url){?>
            		<div class="package-footer">
	            		<a href="<?php echo esc_url($features_link_url);?>" target="_<?php echo $link_target; ?>">
	            			<?php if($button_text){?>
			            		<span class="button-text"><?php echo $button_text;?></span>
			            	<?php } ?>
			            </a>
		             </div>
            	<?php } ?>
		           
		       
            </div>
 <?php    
 
  }

  /**
   * Back-end widget form.
   *
   * @see WP_Widget::form()
   *
   * @param array $instance Previously saved values from database.
   */
  public function form( $instance ) {
    
    // if text contain a value, save it to $text
   
    // if title contain a value
    if ( isset( $instance[ 'title' ] ) ) {
      $title = $instance[ 'title' ];
    }
    else {
      $title = '';
    }
    if ( isset( $instance[ 'features_text' ] ) ) {
      $features_text = $instance[ 'features_text' ];
    }
    else {
      $features_text = '';
    }
    if ( isset( $instance[ 'price_currency' ] ) ) {
      $price_currency = $instance[ 'price_currency' ];
    }
    else {
      $price_currency = '';
    }
    if ( isset( $instance[ 'package_price' ] ) ) {
      $package_price = $instance[ 'package_price' ];
    }
    else {
      $package_price = '';
    }
    if ( isset( $instance[ 'decimal_digit' ] ) ) {
      $decimal_digit = $instance[ 'decimal_digit' ];
    }
    else {
      $decimal_digit = '';
    }
    if ( isset( $instance[ 'cycle_period' ] ) ) {
      $cycle_period = $instance[ 'cycle_period' ];
    }
    else {
      $cycle_period = '';
    }
    
    if(isset($instance['features_link_url'])){
      $features_link_url = $instance['features_link_url'];
    }else {
      $features_link_url = '';
    }
    $features_link_url_target = isset( $instance['features_link_url_target'] ) ? (bool) $instance['features_link_url_target'] : false;
    if(isset($instance['image_uri']))
        {
            $image_uri = $instance['image_uri'];
        } else{
          $image_uri = '';
        }
    if ( isset( $instance[ 'button_text' ] ) ) {
      $button_text = $instance[ 'button_text' ];
    }
    else {
      $button_text = '';
    }
    if ( isset( $instance[ 'badge_text' ] ) ) {
      $badge_text = $instance[ 'badge_text' ];
    }
    else {
      $badge_text = '';
    }
    ?>
    <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Package Title', 'walker-core' ); ?></label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <p>
    
    <label for="<?php echo $this->get_field_id( 'price_currency' ); ?>"><?php _e( 'Currency Symbol: ($, Rs. etc)', 'walker-core' ); ?> </label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'price_currency' ); ?>" name="<?php echo $this->get_field_name( 'price_currency' ); ?>" type="text" value="<?php echo esc_attr( $price_currency ); ?>">
    </p>
    <p>
    
    <label for="<?php echo $this->get_field_id( 'package_price' ); ?>"><?php _e( 'Price:', 'walker-core' ); ?> </label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'package_price' ); ?>" name="<?php echo $this->get_field_name( 'package_price' ); ?>" type="text" value="<?php echo esc_attr( $package_price ); ?>">
    </p>

    <p>
    
    <label for="<?php echo $this->get_field_id( 'decimal_digit' ); ?>"><?php _e( 'Decimal Price Digit:', 'walker-core' ); ?> </label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'decimal_digit' ); ?>" name="<?php echo $this->get_field_name( 'decimal_digit' ); ?>" type="text" value="<?php echo esc_attr( $decimal_digit ); ?>">
    </p>
    <p>
    <label for="<?php echo $this->get_field_id( 'cycle_period' ); ?>"><?php _e( 'Cycle Period: (Monthly,Yearly etc.)', 'walker-core' ); ?> </label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'cycle_period' ); ?>" name="<?php echo $this->get_field_name( 'cycle_period' ); ?>" type="text" value="<?php echo esc_attr( $cycle_period ); ?>">
    </p>
    <p>
     <label for="<?php echo $this->get_field_id( 'features_text' ); ?>"><?php _e( 'Features Lists:', 'walker-core' ); ?></label> 
    <textarea  style="height: 100px;" row ="50" class="widefat" id="<?php echo $this->get_field_id( 'features_text' ); ?>" name="<?php echo $this->get_field_name( 'features_text' ); ?>"><?php echo wp_kses_post( $features_text ); ?> </textarea>
    </p>
    
    
    <p>
        <label for="<?= $this->get_field_id( 'image' ); ?>"><?php _e( 'Upload Image', 'walker-core' ); ?></label>
        <img class="<?= $this->id ?>_img" src="<?= (!empty($instance['image_uri'])) ? $instance['image_uri'] : ''; ?>" style="margin:0;padding:0;max-width:100%;display:block"/>
        <input type="text" class="image_link widefat <?= $this->id ?>_url" name="<?php echo $this->get_field_name( 'image_uri' ); ?>" value="<?php echo esc_url($image_uri); ?>" style="margin-top:5px;" />
        <input type="button" id="<?= $this->id ?>" class="button button-primary walker_core_upload_media" value="Upload Image" style="margin-top:5px;" />
    </p>
        </p>
        <p>
    
    <label for="<?php echo $this->get_field_id( 'badge_text' ); ?>"><?php _e( 'Badge Text:', 'walker-core' ); ?> </label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'badge_text' ); ?>" name="<?php echo $this->get_field_name( 'badge_text' ); ?>" type="text" value="<?php echo esc_attr( $badge_text ); ?>">
    </p>
    
    <label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php _e( 'Button Text:', 'walker-core' ); ?> </label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>">
    </p>
      <p>
    
    <label for="<?php echo $this->get_field_id( 'features_link_url' ); ?>"><?php _e( 'Button Link:', 'walker-core' ); ?> </label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'features_link_url' ); ?>" name="<?php echo $this->get_field_name( 'features_link_url' ); ?>" type="text" value="<?php echo esc_url( $features_link_url ); ?>">
    </p>
    <p>
    <input class="checkbox" type="checkbox" <?php checked( $features_link_url_target ); ?> id="<?php echo $this->get_field_id( 'features_link_url_target' ); ?>" name="<?php echo $this->get_field_name( 'features_link_url_target' ); ?>" />
    <label for="<?php echo $this->get_field_id( 'features_link_url_target' ); ?>"><?php _e( 'Open in New Tab', 'walker-core' ); ?>
    </label>

  
    
</p>
    <?php 
  }

  /**
   * Sanitize widget form values as they are saved.
   *
   * @see WP_Widget::update()
   *
   * @param array $new_instance Values just sent to be saved.
   * @param array $old_instance Previously saved values from database.
   *
   * @return array Updated safe values to be saved.
   */
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    $instance['price_currency'] = ( ! empty( $new_instance['price_currency'] ) ) ? strip_tags( $new_instance['price_currency'] ) : '';
    $instance['package_price'] = ( ! empty( $new_instance['package_price'] ) ) ? strip_tags( $new_instance['package_price'] ) : '';
    $instance['decimal_digit'] = ( ! empty( $new_instance['decimal_digit'] ) ) ? strip_tags( $new_instance['decimal_digit'] ) : '';
    $instance['cycle_period'] = ( ! empty( $new_instance['cycle_period'] ) ) ? strip_tags( $new_instance['cycle_period'] ) : '';
    $instance['features_text'] = ( ! empty( $new_instance['features_text'] ) ) ? wp_kses_post( $new_instance['features_text'] ) : '';
    $instance['badge_text'] = ( ! empty( $new_instance['badge_text'] ) ) ? strip_tags( $new_instance['badge_text'] ) : '';
    $instance['button_text'] = ( ! empty( $new_instance['button_text'] ) ) ? strip_tags( $new_instance['button_text'] ) : '';
    $instance['features_link_url'] = ( ! empty( $new_instance['features_link_url'] ) ) ? strip_tags( $new_instance['features_link_url'] ) : '';
    $instance['features_link_url_target'] = isset( $new_instance['features_link_url_target'] ) ? (bool) $new_instance['features_link_url_target'] : false;
    $instance['image_uri'] = ( ! empty( $new_instance['image_uri'] ) ) ? strip_tags( $new_instance['image_uri'] ) : '';
    return $instance;
  }

} // class Foo_Widget

// register Foo_Widget widget
function Register_Walker_Core_Pricing_Table_Widget() {
    register_widget( 'Walker_Core_Pricing_Table_Widget' );
}
add_action( 'widgets_init', 'Register_Walker_Core_Pricing_Table_Widget' );
	endif;






if ( 'WalkerMag' == $theme->name || 'WalkerMag' == $theme->parent_theme  ):
	class WalkerMag_Social_Media_Widget extends WP_Widget {
		public function __construct() {
		    parent::__construct(
		      'walkermag_social_media_widget', 
		      __( 'WalkerWP Social Media', 'walker-core' ), 
		      array( 'description' => __( 'Social Media Widgets for Site', 'walker-core' ), ) // Args
		    );
		}
		
		/**
		   * Outputs the content of the widget
		   *
		   * @param array $args
		   * @param array $instance
		   */
		public function widget( $args, $instance ) {
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}

			ob_start();
			require WALKER_CORE_PATH . 'admin/partials/walkermag-social-media.php';
			$strrr= ob_get_clean();
			echo $strrr;
			echo $args['after_widget'];
			
		}
		
		/**
			* Outputs the options form on admin
			*
			* @param array $instance The widget options
			*/
		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Follow us ', 'walker-core' );
			?>
			<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'walker-core' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<?php 
		}
		
		/**
			* Processing widget options on save
			*
			* @param array $new_instance The new options
			* @param array $old_instance The previous options
			*/
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			return $instance;
		}
		
	}
	function register_walkermar_social_media_widget() {
	    register_widget( 'WalkerMag_Social_Media_Widget' );
	}
	add_action( 'widgets_init', 'register_walkermar_social_media_widget' );
endif;



if ( 'Walker Charity' == $theme->name || 'Walker Charity' == $theme->parent_theme  ):
	class Walker_Charity_Social_Media_Widget extends WP_Widget {
		public function __construct() {
		    parent::__construct(
		      'walker_charity_social_media_widget', 
		      __( 'WalkerWP Social Media', 'walker-core' ), 
		      array( 'description' => __( 'Social Media Widgets for Site', 'walker-core' ), ) // Args
		    );
		}
		
		/**
		   * Outputs the content of the widget
		   *
		   * @param array $args
		   * @param array $instance
		   */
		public function widget( $args, $instance ) {
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}

			ob_start();
			require WALKER_CORE_PATH . 'admin/partials/walker-charity-social-media.php';
			$strrr= ob_get_clean();
			echo $strrr;
			echo $args['after_widget'];
			
		}
		
		/**
			* Outputs the options form on admin
			*
			* @param array $instance The widget options
			*/
		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Follow us ', 'walker-core' );
			?>
			<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'walker-core' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<?php 
		}
		
		/**
			* Processing widget options on save
			*
			* @param array $new_instance The new options
			* @param array $old_instance The previous options
			*/
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			return $instance;
		}
		
	}
	function register_walker_charity_social_media_widget() {
	    register_widget( 'Walker_Charity_Social_Media_Widget' );
	}
	add_action( 'widgets_init', 'register_walker_charity_social_media_widget' );
endif;

$current_theme = wp_get_theme();
if ( 'WalkerMag' == $current_theme->name || 'WalkerMag' == $current_theme->parent_theme || 'Gridchamp' == $current_theme->name || 'Gridchamp' == $current_theme->parent_theme || 'Walker Charity' == $current_theme->name || 'Walker Charity' == $current_theme->parent_theme ):
	/**
	*
	*Widgets for recent post
	*/
	class Walker_Core_Recent_Blog extends WP_Widget {
	public function __construct() {
		parent::__construct(
				'walker_core_recent_blog', // Base ID
				__( 'WalkerWP Recent Blog', 'walker-core' ), // Name
				array( 'description' => __( 'Recent blog Widets for the site.', 'walker-core' ), ) // Args
		);
	}
	/**
		* Outputs the content of the widget
		*
		* @param array $args
		* @param array $instance
		*/
	public function widget( $args, $instance ) {
		extract( $args );
		$title    = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		echo $before_widget;
	   echo '<div class="widget-text walker_core_post_widget_title">';
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
		echo '</div>';

		$date_status =  ($instance['date_status'] )? $instance['date_status'] : false;
		$thumbnail_status = ($instance['thumbnail_status'] ) ? $instance['thumbnail_status'] : false;
		$post_per_page    = isset( $instance['post_per_page'] ) ? apply_filters( 'post_per_page', $instance['post_per_page'] ) : '';
		ob_start();

		set_query_var( 'date_status', $date_status );
		set_query_var( 'thumbnail_status', $thumbnail_status );
		set_query_var('post_per_page',$post_per_page);
		require WALKER_CORE_PATH . 'admin/partials/recent-blogs.php';

		$strrr= ob_get_clean();
		echo $strrr;
		echo $after_widget;
	}
	/**
	* Outputs the options form on admin
	*
	* @param array $instance The widget options
	*/
		public function form( $instance ) {
		// Set widget defaults
			$defaults = array(
				'title'    => '',
				'date_status' => false,
				'thumbnail_status' => false,
				'post_per_page' => 3,
			);
		// Parse current settings with defaults
		extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

			<?php // Widget Title ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Widget Title', 'walker-core' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<?php // Date Checkbox ?>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'date_status' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'date_status' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $date_status ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'date_status' ) ); ?>"><?php _e( 'Show Published Date', 'walker-core' ); ?></label>
			</p>

			<?php // Thumbnail Checkbox ?>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'thumbnail_status' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumbnail_status' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $thumbnail_status ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'thumbnail_status' ) ); ?>"><?php _e( 'Show Thumbnail', 'walker-core' ); ?></label>
			</p>

			<?php //post per page ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'post_per_page' ) ); ?>"><?php _e( 'Post Per Page', 'walker-core' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_per_page' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_per_page' ) ); ?>" type="number" value="<?php echo esc_attr( $post_per_page ); ?>" />
			</p>

			<?php }
			/**
			* Widget options on save
			*
			* @param array $new_instance The new options
			* @param array $old_instance The previous options
			*/
			public function update( $new_instance, $old_instance ) {
				$instance = $old_instance;
				$instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
				$instance['date_status'] = isset( $new_instance['date_status'] ) ? 1 : false;
				$instance['thumbnail_status'] = isset( $new_instance['thumbnail_status'] ) ? 1 : false;
				$instance['post_per_page']    = isset( $new_instance['post_per_page'] ) ? wp_strip_all_tags( $new_instance['post_per_page'] ) : '3';
				return $instance;
			}
		}


	function walker_core_register_recent_blog_widget() {
			register_widget( 'Walker_Core_Recent_Blog' );
	}
	add_action( 'widgets_init', 'walker_core_register_recent_blog_widget' );
endif;
if ( 'WalkerMag' == $theme->name || 'WalkerMag' == $theme->parent_theme  ):
/**
	*
	*Widgets for Category Post
	*/
	class Walker_Core_Category_Post extends WP_Widget {
	public function __construct() {
		parent::__construct(
				'walker_core_category_blog', // Base ID
				__( 'WalkerWP Category Post', 'walker-core' ), // Name
				array( 'description' => __( 'Recent Post List by Category for Theme', 'walker-core' ), ) // Args
		);
	}
	/**
		* Outputs the content of the widget
		*
		* @param array $args
		* @param array $instance
		*/
	public function widget( $args, $instance ) {
		extract( $args );
		$title    = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		echo $before_widget;
	   echo '<div class="widget-text walker_core_post_widget_title">';
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
		echo '</div>';

		$select_category    = isset( $instance['select_category'] ) ? apply_filters( 'select_category', $instance['select_category'] ) : '';
		$date_status =  ($instance['date_status'] )? $instance['date_status'] : false;
		$thumbnail_status = ($instance['thumbnail_status'] ) ? $instance['thumbnail_status'] : false;
		$post_per_page    = isset( $instance['post_per_page'] ) ? apply_filters( 'post_per_page', $instance['post_per_page'] ) : '';
		ob_start();

		set_query_var( 'date_status', $date_status );
		set_query_var( 'thumbnail_status', $thumbnail_status );
		set_query_var('post_per_page',$post_per_page);
		set_query_var('select_category',$select_category);
		require WALKER_CORE_PATH . 'admin/partials/category-posts.php';
		// require WALKER_CORE_PATH . 'admin/partials/popular-posts.php';
		$strrr= ob_get_clean();
		echo $strrr;
		echo $after_widget;
	}
	/**
	* Outputs the options form on admin
	*
	* @param array $instance The widget options
	*/
		public function form( $instance ) {
		// Set widget defaults
			$defaults = array(
				'title'    => '',
				'select_category' =>'Select Category',
				'date_status' => false,
				'thumbnail_status' => false,
				'post_per_page' => 3,
			);
		// Parse current settings with defaults
		extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

			<?php // Widget Title ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Widget Title', 'walker-core' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>

			<?php // Widget Category ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'select_category' ) ); ?>"><?php _e( 'Select Categroy', 'walker-core' ); ?></label>
				<?php wp_dropdown_categories( array( 'name' => $this->get_field_name("select_category"), 'selected' => isset( $instance['select_category'] ) ? $instance['select_category'] : false ) ); ?>
			</p>
			<?php // Date Checkbox ?>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'date_status' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'date_status' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $date_status ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'date_status' ) ); ?>"><?php _e( 'Show Published Date', 'walker-core' ); ?></label>
			</p>

			<?php // Thumbnail Checkbox ?>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'thumbnail_status' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumbnail_status' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $thumbnail_status ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'thumbnail_status' ) ); ?>"><?php _e( 'Show Thumbnail', 'walker-core' ); ?></label>
			</p>

			<?php //post per page ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'post_per_page' ) ); ?>"><?php _e( 'Post Per Page', 'walker-core' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_per_page' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_per_page' ) ); ?>" type="number" value="<?php echo esc_attr( $post_per_page ); ?>" />
			</p>

			<?php }
			/**
			* Widget options on save
			*
			* @param array $new_instance The new options
			* @param array $old_instance The previous options
			*/
			public function update( $new_instance, $old_instance ) {
				$instance = $old_instance;
				$instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
				$instance['select_category']    = isset( $new_instance['select_category'] ) ? wp_strip_all_tags( $new_instance['select_category'] ) : '';
				$instance['date_status'] = isset( $new_instance['date_status'] ) ? 1 : false;
				$instance['thumbnail_status'] = isset( $new_instance['thumbnail_status'] ) ? 1 : false;
				$instance['post_per_page']    = isset( $new_instance['post_per_page'] ) ? wp_strip_all_tags( $new_instance['post_per_page'] ) : '3';
				return $instance;
			}
		}
	function walker_core_register_category_blog_widget() {
			register_widget( 'Walker_Core_Category_Post' );
	}
	add_action( 'widgets_init', 'walker_core_register_category_blog_widget' );



/**
	*
	*Widgets for Popular Post
	*/
	class Walker_Core_Poupar_Post extends WP_Widget {
	public function __construct() {
		parent::__construct(
				'walker_core_popular_post', // Base ID
				__( 'WalkerWP Popular Post', 'walker-core' ), // Name
				array( 'description' => __( 'Popular Post lists for Theme', 'walker-core' ), ) // Args
		);
	}
	/**
		* Outputs the content of the widget
		*
		* @param array $args
		* @param array $instance
		*/
	public function widget( $args, $instance ) {
		extract( $args );
		$title    = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		echo $before_widget;
	   echo '<div class="widget-text walker_core_post_widget_title">';
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
		echo '</div>';

		
		$date_status =  ($instance['date_status'] )? $instance['date_status'] : false;
		$thumbnail_status = ($instance['thumbnail_status'] ) ? $instance['thumbnail_status'] : false;
		$post_per_page    = isset( $instance['post_per_page'] ) ? apply_filters( 'post_per_page', $instance['post_per_page'] ) : '';
		ob_start();

		set_query_var( 'date_status', $date_status );
		set_query_var( 'thumbnail_status', $thumbnail_status );
		set_query_var('post_per_page',$post_per_page);
		require WALKER_CORE_PATH . 'admin/partials/popular-posts.php';
		$strrr= ob_get_clean();
		echo $strrr;
		echo $after_widget;
	}
	/**
	* Outputs the options form on admin
	*
	* @param array $instance The widget options
	*/
		public function form( $instance ) {
		// Set widget defaults
			$defaults = array(
				'title'    => '',
				'date_status' => false,
				'thumbnail_status' => false,
				'post_per_page' => 3,
			);
		// Parse current settings with defaults
		extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

			<?php // Widget Title ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Widget Title', 'walker-core' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>

		
			<?php // Date Checkbox ?>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'date_status' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'date_status' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $date_status ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'date_status' ) ); ?>"><?php _e( 'Show Published Date', 'walker-core' ); ?></label>
			</p>

			<?php // Thumbnail Checkbox ?>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'thumbnail_status' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumbnail_status' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $thumbnail_status ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'thumbnail_status' ) ); ?>"><?php _e( 'Show Thumbnail', 'walker-core' ); ?></label>
			</p>

			<?php //post per page ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'post_per_page' ) ); ?>"><?php _e( 'Post Per Page', 'walker-core' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_per_page' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_per_page' ) ); ?>" type="number" value="<?php echo esc_attr( $post_per_page ); ?>" />
			</p>

			<?php }
			/**
			* Widget options on save
			*
			* @param array $new_instance The new options
			* @param array $old_instance The previous options
			*/
			public function update( $new_instance, $old_instance ) {
				$instance = $old_instance;
				$instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
				$instance['date_status'] = isset( $new_instance['date_status'] ) ? 1 : false;
				$instance['thumbnail_status'] = isset( $new_instance['thumbnail_status'] ) ? 1 : false;
				$instance['post_per_page']    = isset( $new_instance['post_per_page'] ) ? wp_strip_all_tags( $new_instance['post_per_page'] ) : '3';
				return $instance;
			}
		}
	function walker_core_register_popular_post_widget() {
			register_widget( 'Walker_Core_Poupar_Post' );
	}
	add_action( 'widgets_init', 'walker_core_register_popular_post_widget' );

endif;
$current_theme = wp_get_theme();
if ( 'WalkerMag' == $current_theme->name || 'WalkerMag' == $current_theme->parent_theme || 'Gridchamp' == $current_theme->name || 'Gridchamp' == $current_theme->parent_theme || 'Walker Charity' == $current_theme->name || 'Walker Charity' == $current_theme->parent_theme ):
	/**
	*
	*Walker Address Box Widget
	*/
	class Walker_Core_Address_Box extends WP_Widget {
	public function __construct() {
		parent::__construct(
				'walker_core_address_box', // Base ID
				__( 'WalkerWP Address Box', 'walker-core' ), // Name
				array( 'description' => __( 'Address Box Widets for the site.', 'walker-core' ), ) // Args
		);
	}
	/**
		* Outputs the content of the widget
		*
		* @param array $args
		* @param array $instance
		*/
	public function widget( $args, $instance ) {
		extract( $args );
		$title    = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		echo $before_widget;
	   echo '<div class="widget-text walker_core_address_box_title">';
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
		echo '</div><ul class="walker-address-box">';
		$current_walker_address    = isset( $instance['walker_address'] ) ? apply_filters( 'walker_address', $instance['walker_address'] ) : '';
		if($current_walker_address){
			$current_walker_address_link    = isset( $instance['current_walker_address_link'] ) ? apply_filters( 'current_walker_address_link', $instance['current_walker_address_link'] ) : '';
			if($current_walker_address_link){?>
			 <li><i class="fa fa-home" aria-hidden="true"></i> <strong><?php _e('Address','walker-core');?></strong><br />
			 	<a href="<?php echo esc_url($instance['walker_address_link']);?>" target="_blank"> 
			 	<?php echo $instance['walker_address'];?></a></li>
			<?php }else{?>
				<li><strong> <?php _e('Address','walker-core');?></strong><br />
			 	<i class="fa fa-home" aria-hidden="true"></i> 
			 	<?php echo $instance['walker_address'];?></li>
			<?php }

		}
		
		$current_walker_email    = isset( $instance['walker_email'] ) ? apply_filters( 'walker_email', $instance['walker_email'] ) : '';
		if($current_walker_email){?>
			<li><i class="fa fa-envelope" aria-hidden="true"></i><strong><?php _e('Email','walker-core');?></strong><br />
				<a href="mailto:<?php echo $instance['walker_email'];?>"><?php echo $instance['walker_email']?></a></li>
		<?php }
		$current_walker_phone    = isset( $instance['walker_phone'] ) ? apply_filters( 'walker_phone', $instance['walker_phone'] ) : '';
		if($current_walker_phone){?>
			<li><i class="fa fa-phone" aria-hidden="true"></i><strong><?php _e('Phone','walker-core');?></strong><br />
				<a href="tel:<?php echo $instance['walker_phone'];?>"><?php echo $instance['walker_phone']?></a></li>
		<?php }
		$current_walker_license    = isset( $instance['walker_license'] ) ? apply_filters( 'walker_license', $instance['walker_license'] ) : '';
		if($current_walker_license){?>
			<li><i class="fa fa-certificate" aria-hidden="true"></i><strong><?php _e('Certificate/License','walker-core');?></strong><br />
				<?php echo $instance['walker_license']?></li>
		<?php }
		echo '</ul>';
		echo $after_widget;
	}
	/**
	* Outputs the options form on admin
	*
	* @param array $instance The widget options
	*/
		public function form( $instance ) {
		// Set widget defaults
			$defaults = array(
				'title'    => '',
				'walker_address' => '',
				'walker_address_link' => '',
				'walker_email' => '',
				'walker_phone' => '',
				'walker_license' => '',
			);
		// Parse current settings with defaults
		extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

			<?php // Widget Title ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Widget Title', 'walker-core' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>

			<?php // Address ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'walker_address' ) ); ?>"><?php _e( 'Address', 'walker-core' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'walker_address' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'walker_address' ) ); ?>" type="text" value="<?php echo esc_attr( $walker_address ); ?>" />
			</p>

			<?php // Address link ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'walker_address_link' ) ); ?>"><?php _e( 'Address Link (This may be Google map link)', 'walker-core' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'walker_address_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'walker_address_link' ) ); ?>" type="url" value="<?php echo esc_attr( $walker_address_link ); ?>" />
			</p>
			<?php // Email ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'walker_email' ) ); ?>"><?php _e( 'Email', 'walker-core' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'walker_email' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'walker_email' ) ); ?>" type="email" value="<?php echo esc_attr( $walker_email ); ?>" />
			</p>
			<?php // Phone ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'walker_phone' ) ); ?>"><?php _e( 'Phone', 'walker-core' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'walker_phone' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'walker_phone' ) ); ?>" type="text" value="<?php echo esc_attr( $walker_phone ); ?>" />
			</p>
			<?php // License ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'walker_license' ) ); ?>"><?php _e( 'License', 'walker-core' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'walker_license' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'walker_license' ) ); ?>" type="text" value="<?php echo esc_attr( $walker_license ); ?>" />
			</p>
			

			<?php }
			/**
			* Widget options on save
			*
			* @param array $new_instance The new options
			* @param array $old_instance The previous options
			*/
			public function update( $new_instance, $old_instance ) {
				$instance = $old_instance;
				$instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
				$instance['walker_address']    = isset( $new_instance['walker_address'] ) ? wp_strip_all_tags( $new_instance['walker_address'] ) : '';
				$instance['walker_address_link']    = isset( $new_instance['walker_address_link'] ) ? wp_strip_all_tags( $new_instance['walker_address_link'] ) : '';
				$instance['walker_email']    = isset( $new_instance['walker_email'] ) ? wp_strip_all_tags( $new_instance['walker_email'] ) : '';
				$instance['walker_phone']    = isset( $new_instance['walker_phone'] ) ? wp_strip_all_tags( $new_instance['walker_phone'] ) : '';
				$instance['walker_license']    = isset( $new_instance['walker_license'] ) ? wp_strip_all_tags( $new_instance['walker_license'] ) : '';
				return $instance;
			}
		}
	function walker_core_register_address_box_widget() {
			register_widget( 'Walker_Core_Address_Box' );
	}
	add_action( 'widgets_init', 'walker_core_register_address_box_widget' );
endif;
}