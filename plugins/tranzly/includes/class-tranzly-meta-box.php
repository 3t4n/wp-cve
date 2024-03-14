<?php

/**
 * The file is responsible to add meta box.
 * @link       https://tranzly.io
 * @since      1.0.0
 * @package    Tranzly
 * @subpackage Tranzly/includes
 */
/**
 * Tranzly_Meta_Box class.
 */
class Tranzly_Meta_Box
{
    /**
     * Instantiate the class.
     */
    public static function get_instance()
    {
        static  $instance = null ;
        
        if ( null === $instance ) {
            $instance = new Tranzly_Meta_Box();
            $instance->setup_actions();
        }
        
        return $instance;
    }
    
    /**
     * Set up the default hooks and actions.
     */
    public function setup_actions()
    {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'wp_ajax_tranzly_translate', array( $this, 'process_translation_via_ajax' ) );
        add_action( 'wp_ajax_nopriv_tranzly_translate', array( $this, 'process_translation_via_ajax' ) );
        add_action( 'wp_ajax_tranzly_generate', array( $this, 'process_generate_via_ajax' ) );
        add_action( 'wp_ajax_nopriv_tranzly_generate', array( $this, 'process_generate_via_ajax' ) );
        add_action( 'wp_ajax_tranzly_dpl_translated', array( $this, 'dpl_translated_via_ajax' ) );
    }
    
    /**
     * Adds the meta box.
     */
    public function add_meta_box()
    {
        add_meta_box(
            'tranzly_box_id',
            esc_html__( 'Tranzly Translation', 'tranzly' ),
            array( $this, 'meta_box_html' ),
            tranzly_get_meta_box_screens(),
            'side',
            'high'
        );
        add_meta_box(
            'tranzly_tranzly_box_id',
            esc_html__( 'Tranzly Translator', 'tranzly' ),
            array( $this, 'meta_tranzly_box_html' ),
            tranzly_get_meta_box_screens(),
            'side',
            'high'
        );
    }
    
    /**
     * Meta box html.
     */
    public function meta_tranzly_box_html()
    {
        ?>
		<div class="tranzly-translator-meta-cn-box">
			<?php 
        $post_statusArr = array(
            'publish' => 'Published',
            'draft'   => 'Draft',
        );
        $post_id = get_the_ID();
        $deepl_translated = get_post_meta( $post_id, 'deepl_translated', true );
        $tranzly_post_translated_to = get_post_meta( $post_id, 'tranzly_post_translated_to', true );
        $tranzly_post_translated_to_from = get_post_meta( $post_id, 'tranzly_post_translated_to_from', true );
        wp_nonce_field( 'tranzly_meta_tranzly_box', 'tranzly_meta_tranzly_box_nonce' );
        ?>
		
		
			<p>
				<input type="hidden" name="tranzly_post_id" value="<?php 
        echo  esc_attr( $post_id ) ;
        ?>">
				<?php 
        
        if ( !empty($tranzly_post_translated_to) ) {
            ?>
					<label for="tranzly_post_translated_to"><?php 
            esc_html_e( 'Translated in', 'tranzly' );
            ?></label>
					<br>
					<?php 
            foreach ( $tranzly_post_translated_to as $translated_to ) {
                $tranzly_child_post_id = $translated_to['tranzly_child_post_id'];
                foreach ( tranzly_supported_languages() as $code => $name ) {
                    
                    if ( $code == $translated_to['translated_to'] ) {
                        $tranzly_child_post = get_post( $tranzly_child_post_id );
                        
                        if ( $tranzly_child_post->post_status == 'publish' or $tranzly_child_post->post_status == 'draft' ) {
                            ?>
									<li>
										<a target="_blank" href="<?php 
                            echo  esc_url(admin_url( 'post.php' ) . '?post=' . $tranzly_child_post_id );
                            ?>&action=edit">
											<span><?php 
                            echo  esc_html( $name ) ;
                            ?></span>
										</a> -  <?php 
                            echo  esc_html($post_statusArr[$tranzly_child_post->post_status]) ;
                            ?>
									</li>
							<?php 
                        }   
                    
                    }
                    
                    ?>
					<?php 
                }
            }
            ?>
			</p>
				<?php 
        }
        
        ?>
			<p>
				<?php 
        
        if ( $tranzly_post_translated_to_from ) {
            ?>
					<label for="tranzly_post_translated_to_from"><strong><?php 
            esc_html_e( 'Translated to', 'tranzly' );
            ?></strong></label>
					<br>
					<?php 
            foreach ( $tranzly_post_translated_to_from as $translated_to_from ) {
                
                if ( $translated_to_from['translated_from'] ) {
                    $tranzly_parent_post_id = $translated_to_from['tranzly_parent_post_id'];
                    foreach ( tranzly_supported_languages() as $code => $name ) {
                        
                        if ( $code == $translated_to_from['translated_from'] ) {
                            $tranzly_parent_post = get_post( $tranzly_parent_post_id );
                            
                            if ( $tranzly_parent_post->post_status == 'publish' ) {
                                ?>
						 			<a target="_blank" href="<?php 
                                echo  esc_url(admin_url( 'post.php' ) . '?post=' . $tranzly_parent_post_id );
                                ?>&action=edit"><span><?php 
                                echo  esc_html( $name ) ;
                                ?></span></a>
							 	<?php 
                            } else {
                                ?>
							 		<span><?php 
                                echo  esc_html( $name ) ;
                                ?></span>
							 	<?php 
                            }
                        
                        }
                        
                        ?>
						 	
						<?php 
                    }
                }
                
                if ( $translated_to_from['translated_to'] ) {
                    foreach ( tranzly_supported_languages() as $code => $name ) {
                        
                        if ( $code == $translated_to_from['translated_to'] ) {
                            ?>
						 		<span><?php 
                            esc_html_e( 'to', 'tranzly' );
                            ?><</span>
						 		<span><?php 
                            echo  esc_html( $name ) ;
                            ?></span>
						 	<?php 
                        }
                        
                        ?>
						<?php 
                    }
                }
            }
        }
        
        ?>
				</p>
			<p>
			
			
				<input type="checkbox" name="deepl_translated" cn="<?php 
        echo  esc_attr($deepl_translated );
        ?>" id="deepl_translated" <?php 
        if ( $deepl_translated == 2 ) {
            echo  'checked' ;
        }
        ?>  value="<?php 
        
        if ( $deepl_translated == 1 ) {
            echo  2 ;
        } else {
            echo  1 ;
        }
        
        ?>">
				<label for="deepl_translated"><?php 
        esc_html_e( 'Tranzly Translated', 'tranzly' );
        ?></label>
			</p>
			<p>
				<button
					class="button button-primary tranzly-cn-save-btn"
				><?php 
        esc_html_e( 'Save', 'tranzly' );
        ?></button>
			</p>
			<div class="tranzly_spinner_div">
					<span class="tranzly_spinner tranzly-spinner"></span>
				</div>
		
		
		</div>
		<?php 
    }
    
    public function meta_box_html()
    {
        $post_id = get_the_ID();
        $post = get_post( $post_id );
        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'tranzly_meta_box', 'tranzly_meta_box_nonce' );
        ?>
		<?php 
        //check if post type is product
        $post_type = $post->post_type;
        
        if ( $post_type != 'product' ) {
            ?>
		
		<div class="tranzly-translator-meta-box">
			
		 
			<p>
				<label for="source_lang"><?php 
            esc_html_e( 'Translate From', 'tranzly' );
            ?>:</label>
				<select name="source_lang" id="source_lang">
					<option value=""><?php 
            esc_html_e( 'Please select', 'tranzly' );
            ?></option>
					<?php 
            foreach ( tranzly_supported_languages() as $code => $name ) {
                ?>
						<option value="<?php 
                echo  esc_attr( $code ) ;
                ?>"><?php 
                echo  esc_html( $name ) ;
                ?></option>
					<?php 
            }
            ?>
				</select>
			</p>
			<p>
				<label for="target_lang"><?php 
            esc_html_e( 'Translate to', 'tranzly' );
            ?>:</label>
				<select name="target_lang[]" id="target_lang" multiple="multiple" class="tranzly_select2" >
					<?php 
            foreach ( tranzly_supported_languages() as $code => $name ) {
                ?>
						<option value="<?php 
                echo  esc_attr( $code ) ;
                ?>"><?php 
                echo  esc_html( $name ) ;
                ?></option>
					<?php 
            }
            ?>
				</select>
			</p>
			
			<?php 
            ?>
			<p>
				<input type="checkbox" name="tranzly_saveas" id="tranzly_saveas">
				<label for="tranzly_saveas">
					<?php 
            esc_html_e( 'Save as draft', 'tranzly' );
            ?>
				</label>
			</p>
			<!-- <p>
				<input type="checkbox" name="manual_translate" id="manual_translate">
				<label for="tranzly_saveas">
					<?php 
            //esc_html_e( 'Manual Translate ', 'tranzly' );
            ?>
				</label>
			</p> -->
			<p class="text-center">
				<input type="hidden" name="post_id" value="<?php 
            echo  esc_attr( $post_id ) ;
            ?>">
				<input type="hidden" name="gutenberg_active" value="<?php 
            echo  esc_attr( tranzly_is_gutenberg_active() ) ;
            ?>">
				<button class="text-left button button-primary tranzly-translate-btn"
				><?php 
            esc_html_e( 'Translate', 'tranzly' );
            ?></button>
				<span><?php 
            esc_html_e( 'Or', 'tranzly' );
            ?></span>
				<button class="text-right button button-primary tranzly-generate-new-btn"
				><?php 
            esc_html_e( 'Generate new', 'tranzly' );
            ?></button><br>
				<br>
				<button class="text-right button button-primary tranzly-generate-manual-btn"
				><?php 
            esc_html_e( 'Manual Translate', 'tranzly' );
            ?></button><br>
				<br>
				<br>
				<div class="tranzly_spinner_div">
					<span class="tranzly_spinner tranzly-spinner"></span>
					<span class="tranzly_spinner_text"><?php 
            esc_html_e( 'AI Translating...', 'tranzly' );
            ?></span>
				</div>
				
			</p>
			<?php 
            tranzly_get_error_message_wrapper();
            ?>
		
		

		
		</div>
		<?php 
        } else {
            ?>
		
		
			<span class="trnoticed"><?php 
            esc_html_e( 'You can translate products by using Tranzly Pro', 'tranzly' );
            ?>
       </span>
			<?php 
            echo  '<a class="button-primary side savesettings" target="_blank" href="https://checkout.freemius.com/mode/dialog/plugin/6843/plan/11459/">' . esc_html(__( 'Upgrade Now!', 'tranzly' )) . '</a>' ;
        }
    
    }
    
    /**
     * Process the translation.
     */
    public function process_translation_via_ajax()
    {
        $wptranzlynonce = isset( $_REQUEST['tranzly_nonce'] ) ? wp_verify_nonce( sanitize_key( $_REQUEST['tranzly_nonce'] ), 'tranzly_nonce' ) : false;
        //check_ajax_referer( 'tranzly_meta_box', 'tranzly_nonce' );
        $success = false;
        $error = '';
        $translated = '';
        $post_id = ( isset( $_POST['post_id'] ) ? sanitize_text_field( $_POST['post_id']) : '' );
		
        $source_lang = ( isset( $_POST['source_lang'] ) ? sanitize_text_field( $_POST['source_lang'] ) : '' );
        $target_lang = ( isset( $_POST['target_lang'] ) ? sanitize_text_field( $_POST['target_lang'] ) : '' );
        $translate_atts = ( isset( $_POST['translate_atts'] ) ? sanitize_text_field( $_POST['translate_atts'] ) : '' );
        $translate_slug = ( isset( $_POST['translate_slug'] ) ? sanitize_text_field( $_POST['translate_slug'] ) : '' );
        $translate_seo = ( isset( $_POST['translate_seo'] ) ? sanitize_text_field( $_POST['translate_seo'] ) : '' );
        $gutenberg_active = true;
        //isset( $_POST['gutenberg_active'] ) ? sanitize_text_field( $_POST['gutenberg_active'] ) : '';
        $translate_atts = ( 'true' === $translate_atts ? true : false );
        $translate_slug = ( 'true' === $translate_slug ? true : false );
		$target_lang = ( isset( $_POST['target_lang'][0] ) ? sanitize_text_field( $_POST['target_lang'][0] ) : '' );
		
        try {
            $translator = new Tranzly_Translator();
            $translator->set_source_lang( $source_lang );
            $translator->set_target_lang( $target_lang );
            $translator->set_translate_attributes( $translate_atts );
            $translator->set_translate_slug( $translate_slug );
            $translator->set_translate_seo( $translate_seo );
            
            if ( apply_filters( 'tranzly_should_save_translated_post_if_gutenberg_active', $gutenberg_active ) ) {
                // Translate and save the post.
                $translated = $translator->translate_post( $post_id );
                update_post_meta( $post_id, 'translated_to', $target_lang );
                update_post_meta( $post_id, 'translated_from', $source_lang );
                update_post_meta( $post_id, 'tranzly_mylang', $target_lang );
            } else {
                // Only show the translation, user needs to update the post manually.
                $translated = $translator->get_translated_post( $post_id );
            }
            
            $translated = apply_filters( 'tranzly_translated_data_after_finish_translation_via_ajax', $translated, $post_id );
            do_action( 'tranzly_after_finish_translation_via_ajax', $post_id );
            $success = true;
        } catch ( Exception $e ) {
            $success = false;
            $error = $e->getMessage();
        }
        wp_send_json( array(
            'success'    => $success,
            'error'      => $error,
            'translated' => $translated,
        ) );
    }
    
    public function process_generate_via_ajax()
    {
        $wptranzlynonce = isset( $_REQUEST['tranzly_nonce'] ) ? wp_verify_nonce( sanitize_key( $_REQUEST['tranzly_nonce'] ), 'tranzly_nonce' ) : false;
        $success = false;
        $error = '';
        $translated = '';
        $post_id = ( isset( $_POST['post_id'] ) ? sanitize_text_field( $_POST['post_id']) : '' );
        $source_lang = ( isset( $_POST['source_lang'] ) ? sanitize_text_field( $_POST['source_lang'] ) : '' );
		$target_lang = ( isset( $target_lang ) ? sanitize_text_field( $target_lang ) : '' );
        // $target_langAll      = isset( $_POST['target_lang'] ) ? sanitize_text_field( $_POST['target_lang'] ) : '';
        $translate_atts = ( isset( $_POST['translate_atts'] ) ? sanitize_text_field( $_POST['translate_atts'] ) : '' );
        $translate_slug = ( isset( $_POST['translate_slug'] ) ? sanitize_text_field( $_POST['translate_slug'] ) : '' );
        $translate_seo = ( isset( $_POST['translate_seo'] ) ? sanitize_text_field( $_POST['translate_seo'] ) : '' );
        $gutenberg_active = ( isset( $_POST['gutenberg_active'] ) ? sanitize_text_field( $_POST['gutenberg_active'] ) : '' );
        $tranzly_post_status = ( isset( $_POST['tranzly_post_status'] ) ? sanitize_text_field( $_POST['tranzly_post_status'] ) : '' );
        $tranzly_manual_translate = ( isset( $_POST['tranzly_manual_translate'] ) ? sanitize_text_field( $_POST['tranzly_manual_translate'] ) : '' );
        $translate_atts = ( 'true' === $translate_atts ? true : false );
        $translate_slug = ( 'true' === $translate_slug ? true : false );
        try {
            $translator = new Tranzly_Translator();
            
            if ( $_POST['target_lang'] ) {
                foreach ( $_POST['target_lang'] as $target_lang ) {
					$target_lang = ( isset( $target_lang ) ? sanitize_text_field( $target_lang ) : '' );
					
                    $translator->set_source_lang( $source_lang );
                    $translator->set_target_lang( $target_lang );
                    $translator->set_translate_attributes( $translate_atts );
                    $translator->set_translate_slug( $translate_slug );
                    $translator->set_translate_seo( $translate_seo );
                    
                    if ( $tranzly_manual_translate ) {
                        $content_post = get_post( $post_id );
                        $content = $content_post->post_content;
                        $translated = wp_insert_post( array(
                            'post_title'   => 'enter your text',
                            'post_status'  => 'draft',
                            'post_type'    => 'page',
                            'post_content' => $content,
                        ) );
                    } else {
                        $translated = $translator->generate_post( $post_id, $tranzly_post_status );
                    }
                    
                    $tranzly_target_language = get_post_meta( $post_id, 'tranzly_post_translated_to', true );
                    
                    if ( $tranzly_target_language ) {
                        $newArr[] = array(
                            'translated_to'    => $target_lang,
                            'translated_from'  => $source_lang,
                            'tranzly_child_post_id' => $translated,
                        );
                        $translated_to = array_merge( $tranzly_target_language, $newArr );
                        foreach ( $translated_to as $tranzly_translated ) {
                            $alltranslated_to[] = $tranzly_translated['translated_to'];
                        }
                        $Match = 0;
                        foreach ( tranzly_supported_languages() as $code => $name ) {
                            if ( in_array( $code, $alltranslated_to ) ) {
                                $Match++;
                            }
                        }
                        if ( $Match >= 8 ) {
                            update_post_meta( $post_id, 'deepl_translated', 2 );
                        }
                    } else {
                        $translated_to[] = array(
                            'translated_to'    => $target_lang,
                            'translated_from'  => $source_lang,
                            'tranzly_child_post_id' => $translated,
                        );
                    }
                    
                    $tranzly_post_translated[] = array(
                        'translated_from'   => $source_lang,
                        'translated_to'     => $target_lang,
                        'tranzly_parent_post_id' => $post_id,
                    );
                    global  $wpdb ;
                    $custom_fields = get_post_custom( $post_id );
                    foreach ( $custom_fields as $key => $value ) {
                        if ( is_array( $value ) && count( $value ) > 0 ) {
                            foreach ( $value as $i => $v ) {
                                $result = $wpdb->insert( $wpdb->prefix . 'postmeta', array(
                                    'post_id'    => $translated,
                                    'meta_key'   => $key,
                                    'meta_value' => $v,
                                ) );
                            }
                        }
                    }
                    update_post_meta( $translated, 'tranzly_post_translated_to_from', $tranzly_post_translated );
                    update_post_meta( $post_id, 'tranzly_post_translated_to', $translated_to );
                    update_post_meta( $translated, 'translated_from', $target_lang );
                    update_post_meta( $post_id, 'translated_to', $source_lang );
                    update_post_meta( $translated, 'tranzly_mylang', $target_lang );
                    update_post_meta( $post_id, 'tranzly_mylang', $source_lang );
                    update_post_meta( $translated, 'deepl_translated', 2 );
                    $translated = apply_filters( 'tranzly_translated_data_after_finish_translation_via_ajax', $translated, $post_id );
                    do_action( 'tranzly_after_finish_translation_via_ajax', $post_id );
                    unset( $translated_to );
                    unset( $tranzly_post_translated );
                    unset( $newArr );
                    unset( $alltranslated_to );
                    $success = true;
                }
            } else {
                $success = false;
                $error = 'Select at least one target language';
            }
        
        } catch ( Exception $e ) {
            $success = false;
            $error = $e->getMessage();
        }
        wp_send_json( array(
            'success'            => $success,
            'error'              => $error,
            'translated'         => $translated,
            'translated_to'      => $translated_to,
            'tranzly_post_translated' => $tranzly_post_translated,
            'saveas'             => $tranzly_post_status,
        ) );
    }
    
    public function dpl_translated_via_ajax()
    {
        $wptranzlynonce = isset( $_REQUEST['tranzly_nonce'] ) ? wp_verify_nonce( sanitize_key( $_REQUEST['tranzly_nonce'] ), 'tranzly_nonce' ) : false;
        //check_ajax_referer( 'tranzly_meta_tranzly_box', 'tranzly_nonce' );
        $success = false;
        $error = '';
        $post_id = ( isset( $_POST['post_id'] ) ? sanitize_text_field( $_POST['post_id']) : '' );
        $deepl_translated = ( isset( $_POST['deepl_translated'] ) ? sanitize_text_field( $_POST['deepl_translated']) : '' );
        try {
            if ( update_post_meta( $post_id, 'deepl_translated', $deepl_translated ) ) {
                $success = true;
            }
        } catch ( Exception $e ) {
            $success = false;
            $error = $e->getMessage();
        }
        wp_send_json( array(
            'success'          => $success,
            'error'            => $error,
            'deepl_translated' => $deepl_translated,
        ) );
    }

}
Tranzly_Meta_Box::get_instance();