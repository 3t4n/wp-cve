<?php

/*
 * this class should be used to work with the public side of wordpress
 */

class daexthefu_Public {

	protected static $instance = null;
	private $shared = null;

	private static $shortcode_counter = 0;

	private function __construct() {

		//assign an instance of the plugin info
		$this->shared = daexthefu_Shared::get_instance();

		//Add the feedback form at the end of the post content
		add_filter( 'the_content', array( $this, 'add_content_after' ) );

		//helpful shortcode
		add_shortcode('helpful', array($this, 'get_helpful_shortcode'));

		//Load public css
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		//Load public js
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Change the post type arguments to includes the "custom-fields" support
		add_action('register_post_type_args', array( $this, 'modify_post_type_args' ), 99, 2);

	}

	/*
	 * create an instance of this class
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	public function enqueue_styles() {

		//Adds the Google Fonts if they are defined in the "Google Font URL" option.
		if ( strlen( trim( get_option( $this->shared->get( 'slug' ) . "_google_font_url" ) ) ) > 0 ) {
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-google-font',
				esc_url( get_option( $this->shared->get( 'slug' ) . '_google_font_url' ) ), false );
		}

		//Enqueue the main stylesheet
		wp_enqueue_style( $this->shared->get( 'slug' ) . '-general',
			$this->shared->get( 'url' ) . 'public/assets/css/general.css', array(), $this->shared->get( 'ver' ) );

		//Enqueue the custom CSS file based on the plugin options
		$upload_dir_data = wp_upload_dir();
		wp_enqueue_style( $this->shared->get( 'slug' ) . '-custom',
			$upload_dir_data['baseurl'] . '/daexthefu_uploads/custom-' . get_current_blog_id() . '.css', array(),
			$this->shared->get( 'ver' ) );

	}

	public function enqueue_scripts() {

		if ( intval( get_option( $this->shared->get( 'slug' ) . '_assets_mode' ), 10 ) === 0 ) {

			//Development
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-utility',
				$this->shared->get( 'url' ) . 'public/assets/js/dev/utility.js', array(),
				$this->shared->get( 'ver' ), true );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-general',
				$this->shared->get( 'url' ) . 'public/assets/js/dev/general.js',
				array( 'jquery', $this->shared->get( 'slug' ) . '-utility' ),
				$this->shared->get( 'ver' ), true );

			$partial_script_handle = 'general';

		} else {

			//Production
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-main',
				$this->shared->get( 'url' ) . 'public/assets/js/main.js',
				array( 'jquery' ), $this->shared->get( 'ver' ), true );

			$partial_script_handle = 'main';

		}

		//Store the JavaScript parameters in the window.DAEXTHEFU_PARAMETERS object
		wp_add_inline_script(
			$this->shared->get( 'slug' ) . '-' . $partial_script_handle,
			'window.DAEXTHEFU_PHPDATA = ' . json_encode( array(
				'ajaxUrl'                       => admin_url( 'admin-ajax.php' ),
				'nonce'                         => wp_create_nonce( "daexthefu" ),
				'uniqueSubmission'              => intval( get_option( $this->shared->get( 'slug' ) . '_unique_submission' ),
					10 ),
				'cookieExpiration'              => $this->shared->get_cookie_expiration_seconds( get_option( $this->shared->get( 'slug' ) . '_cookie_expiration' ) ),
				'commentForm'                   => intval( get_option( $this->shared->get( 'slug' ) . '_comment_form' ),
					10 ),
				'textareaCharacters'            => intval( get_option( $this->shared->get( 'slug' ) . '_textarea_characters' ),
					10 ),
				'textareaLabelPositiveFeedback' => get_option( $this->shared->get( 'slug' ) . '_comment_form_textarea_label_positive_feedback' ),
				'textareaLabelNegativeFeedback' => get_option( $this->shared->get( 'slug' ) . '_comment_form_textarea_label_negative_feedback' ),
			) ),
			'before'
		);

	}

	public function add_content_after( $content ) {

		$form = '';

        //Do not proceed if the helpful form is present in the content
        if ( strpos( $content, '[helpful' ) !== false ) {
            return $content;
        }

		//Get the list of post types where the form should be applied.
		$post_types_a = maybe_unserialize( get_option( $this->shared->get( 'slug' ) . '_post_types' ) );

		//Verify the post type
		if ( is_array( $post_types_a ) and in_array( get_post_type(), $post_types_a ) ) {

            $form = $this->get_form_html();

		}

		return $content . $form;

	}

	/*
     * [helpful] shortcode callback.
     */
    public function get_helpful_shortcode(){

	    self::$shortcode_counter++;

        if( self::$shortcode_counter > 1){
	        return '<p>' . esc_html__("You can't add multiple feedback forms in the same post.", 'daext-helpful') . '</p>';
        }else{
	        return $this->get_form_html();
        }

    }

	/**
     * Generate the HTML of the form.
     * 
	 * @return false|string
	 */
    public function get_form_html(){

        $form = '';

        $post_id = get_the_ID();

	    //Do not display the form if the test mode is enabled and the current user is not the administrator
	    if ( intval( get_option( $this->shared->get( 'slug' ) . '_test_mode' ), 10 ) === 1 and
	         ! current_user_can( 'manage_options' ) ) {
		    return '';
	    }

	    //Do not display the form if it's disabled in this post
	    if ( intval( get_post_meta( $post_id, '_helpful_status', true ), 10 ) === 0 ) {
		    return '';
	    }

	    //Verify if the user has already submitted this form in this post
	    if ( $this->shared->is_unique_submission( $post_id ) ) {

		    //Create a class name for the layout type
		    $layout_class = intval( get_option( $this->shared->get( 'slug' ) . '_layout' ),
			    10 ) === 0 ? 'daexthefu-layout-side-by-side' : 'daexthefu-layout-stacked';

		    //Create a class name for the alignment
		    switch ( intval( get_option( $this->shared->get( 'slug' ) . '_alignment' ), 10 ) ) {

			    case 0:
				    $alignment_class = 'daexthefu-alignment-left';
				    break;

			    case 1:
				    $alignment_class = 'daexthefu-alignment-center';
				    break;

			    case 2:
				    $alignment_class = 'daexthefu-alignment-right';
				    break;

		    }

		    //turn on output buffering
		    ob_start();

		    ?>

            <div id="daexthefu-container"
                 class="daexthefu-container <?php echo esc_attr( $layout_class ); ?> <?php echo esc_attr( $alignment_class ); ?>"
                 data-post-id="<?php echo esc_attr( get_the_ID() ); ?>">

                <div class="daexthefu-feedback">
                    <div class="daexthefu-text">
                        <h3 class="daexthefu-title"><?php echo esc_html( get_option( $this->shared->get( 'slug' ) . '_title' ) ); ?></h3>
                    </div>
                    <div class="daexthefu-buttons-container">
                        <div class="daexthefu-buttons">
						    <?php $this->shared->generated_button_html( true ); ?>
						    <?php $this->shared->generated_button_html( false ); ?>
                        </div>
                    </div>
                </div>

                <div class="daexthefu-comment">
                    <div class="daexthefu-comment-top-container">
                        <label id="daexthefu-comment-label" class="daexthefu-comment-label"></label>
					    <?php if ( intval( get_option( $this->shared->get( 'slug' ) . '_character_counter' ),
							    10 ) === 1 ) : ?>
                            <div class="daexthefu-comment-character-counter-container">
                                <div id="daexthefu-comment-character-counter-number"
                                     class="daexthefu-comment-character-counter-number"></div>
                                <div class="daexthefu-comment-character-counter-text"></div>
                            </div>
					    <?php endif; ?>
                    </div>
                    <textarea id="daexthefu-comment-textarea" class="daexthefu-comment-textarea"
                              placeholder="<?php echo esc_attr( get_option( $this->shared->get( 'slug' ) . '_comment_form_textarea_placeholder' ) ); ?>"
                              maxlength="<?php echo intval( get_option( $this->shared->get( 'slug' ) . '_textarea_characters' ),
					              10 ); ?>"></textarea>
                    <div class="daexthefu-comment-buttons-container">
                        <button class="daexthefu-comment-submit daexthefu-button"><?php echo esc_html( get_option( $this->shared->get( 'slug' ) . '_comment_form_button_submit_text' ) ); ?></button>
                        <button class="daexthefu-comment-cancel daexthefu-button"><?php echo esc_html( get_option( $this->shared->get( 'slug' ) . '_comment_form_button_cancel_text' ) ); ?></button>
                    </div>
                </div>

                <div class="daexthefu-successful-submission-text"><?php echo esc_html( get_option( $this->shared->get( 'slug' ) . '_successful_submission_text' ) ); ?></div>

            </div>

		    <?php

		    $form = ob_get_clean();

	    }

        return $form;

    }

	/**
	 * Change the post type arguments to includes the "custom-fields" support when it's not already included during the
	 * cpt registration.
	 *
	 * This is necessary because the plugin uses meta fields (the '_helpful_status' meta field) to store feedback form
	 * status (enabled/disabled).
	 */
	public function modify_post_type_args($args, $post_type) {

		$post_types_a = maybe_unserialize( get_option( $this->shared->get( 'slug' ) . '_post_types' ) );

		/**
		 * If this post type is in the list of post types where the feedback form should be displayed add support for
		 * "custom-fields" (if it's not already included).
		 */
		if(in_array($post_type, $post_types_a)){
			$args['supports'] = in_array( 'custom-fields', $args['supports'] ) ? $args['supports'] : array_merge( $args['supports'], array( 'custom-fields' ) );
		}

		return $args;

	}

}