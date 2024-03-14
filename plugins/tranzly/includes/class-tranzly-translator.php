<?php

/**
 * The translator.
 * @link       https://tranzly.io
 * @since      1.0.0
 * @package    Tranzly
 * @subpackage Tranzly/includes
 */
/**
 * The translator class.
 */
class Tranzly_Translator
{
    /**
     * DeepL API key.
     */
    private  $deepl_api_key ;
    /**
     * Language of the text to be translated.
     */
    protected  $source_lang ;
    /**
     * The language into which the text should be translated.
     */
    protected  $target_lang ;
    /**
     * DeepL supported language codes.
     */
    protected  $supported_languages ;
    /**
     * Translate attributes.
     */
    protected  $translate_attributes = true ;
    /**
     * Translate post slug.
     */
    protected  $translate_slug = false ;
    /**
     * Translate post seo.
     */
    protected  $translate_seo = false ;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->deepl_api_key = tranzly_get_deepl_api_key();
        $this->supported_languages = array_keys( tranzly_supported_languages() );
    }
    
    /**
     * Gets the DeepL API key.
     */
    public function get_deepl_api_key()
    {
        return $this->deepl_api_key;
    }
    
    /**
     * Sets the source language.
     */
    public function set_source_lang( $lang )
    {
        $this->source_lang = $lang;
    }
    
    /**
     * Gets the source language.
     */
    public function get_source_lang()
    {
        return $this->source_lang;
    }
    
    /**
     * Sets the target language.
     */
    public function set_target_lang( $lang )
    {
        $this->target_lang = $lang;
    }
    
    /**
     * Gets the target language.
     */
    public function get_target_lang()
    {
        return $this->target_lang;
    }
    
    /**
     * Gets the supported languages.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function get_supported_languages()
    {
        return $this->supported_languages;
    }
    
    /**
     * Sets the translate_attributes value.
     */
    public function set_translate_attributes( $translate_attributes )
    {
        $this->translate_attributes = $translate_attributes;
    }
    
    /**
     * Gets the translate_attributes value.
     */
    public function get_translate_attributes()
    {
        return $this->translate_attributes;
    }
    
    /**
     * Sets the translate_slug value.
     */
    public function set_translate_slug( $translate_slug )
    {
        $this->translate_slug = $translate_slug;
    }
    
    /**
     * Gets the translate_slug value.
     */
    public function get_translate_slug()
    {
        return $this->translate_slug;
    }
    
    /**
     * Sets the translate_seo value.
     */
    public function set_translate_seo( $translate_seo )
    {
        $this->translate_seo = $translate_seo;
    }
    
    /**
     * Gets the translate_seo value.
     */
    public function get_translate_seo()
    {
        return $this->translate_seo;
    }
    
    /**
     * Check if the given languages are supported.
     * This method might throw an exception so you should wrap it in a try-catch-block.
     */
    protected function check_languages( $source_lang, $target_lang )
    {
        if ( 'auto' !== $source_lang && !in_array( $source_lang, $this->get_supported_languages(), true ) ) {
            throw new Exception( sprintf(
                /* translators: language name */
                esc_html__( 'The language "%s" is not supported as source language.', 'tranzly' ),
                $source_lang
            ) );
        }
        if ( '' == $source_lang ) {
            sprintf(
                /* translators: language name */
                esc_html__( 'Select a source language.', 'tranzly' ),
                $source_lang
            );
        }
        if ( !in_array( $target_lang, $this->get_supported_languages(), true ) ) {
            throw new Exception( sprintf(
                /* translators: language name */
                esc_html__( 'The language "%s" is not supported as target language.', 'tranzly' ),
                $target_lang
            ) );
        }
        return true;
    }
    
    /**
     * DeepL HTTP error codes.
     */
    private function deepl_error_codes()
    {
        return array(
            400 => esc_html__( 'Wrong request, please check error message and your parameters.', 'tranzly' ),
            403 => esc_html__( 'Authorization failed. Please supply a valid auth_key parameter.', 'tranzly' ),
            413 => esc_html__( 'Request Entity Too Large. The request size exceeds the current limit.', 'tranzly' ),
            429 => esc_html__( 'Too many requests. Please wait and send your request once again.', 'tranzly' ),
            456 => esc_html__( 'Quota exceeded. The character limit has been reached.', 'tranzly' ),
        );
    }
    
    /**
     * Gets DeepL error message.
     */
    public function get_deepl_error_message( $code )
    {
        $error_messages = $this->deepl_error_codes();
        return $error_messages[$code];
    }
    
    /**
     * Gets the DeepL arguments.
     */
    public function get_deepl_query_args( $content )
    {
        $deepl_args = array(
            'auth_key'            => $this->get_deepl_api_key(),
            'text'                => rawurlencode( $content ),
            'source_lang'         => $this->get_source_lang(),
            'target_lang'         => $this->get_target_lang(),
            'tag_handling'        => 'xml',
            'split_sentences'     => 1,
            'preserve_formatting' => 1,
        );
        if ( 'auto' === $this->get_source_lang() ) {
            unset( $deepl_args['source_lang'] );
        }
        return apply_filters( 'tranzly_deepl_query_args', $deepl_args );
    }
    
    /**
     * Gets the translated content.
     */
    public function get_translated( $content )
    {
        $deepl_args = $this->get_deepl_query_args( $content );
        $endpoint = tranzly_get_deepl_endpoint();
        $deepl_url = add_query_arg( $deepl_args, $endpoint );
        $response = wp_remote_get( $deepl_url );
        if ( is_wp_error( $response ) ) {
            throw new Exception( $response->get_error_message() );
        }
        $code = $response['response']['code'];
        
        if ( in_array( $code, array_keys( $this->deepl_error_codes() ), true ) ) {
            throw new Exception( $this->get_deepl_error_message( $code ) );
        } elseif ( 200 !== $code ) {
            throw new Exception( esc_html__( 'DeepL Internal error.', 'tranzly' ) );
        }
        
        $body = wp_remote_retrieve_body( $response );
        $decoded = json_decode( $body, true );
        $translated_content = $decoded['translations']['0']['text'];
        return $translated_content;
    }
    
    /**
     * Gets the translated attributes.
     * This method might throw an exception so you should wrap it in a try-catch-block.
     */
    public function get_translated_attributes( $content )
    {
        if ( !strlen( $content ) ) {
            return;
        }
        $dom = new DOMDocument();
        $dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );
        $translatable_tags = tranzly_tags_n_atts_to_be_translated();
        foreach ( $translatable_tags as $translatable_tag => $attributes ) {
            $tags_in_content = $dom->getElementsByTagName( $translatable_tag );
            foreach ( $tags_in_content as $tag ) {
                foreach ( $attributes as $attribute ) {
                    $old_value = $tag->getAttribute( $attribute );
                    if ( !$old_value ) {
                        continue;
                    }
                    $before_translated_tag = $dom->saveHTML( $tag );
                    $new_value = $this->get_translated( $old_value );
                    $tag->setAttribute( $attribute, $new_value );
                    $translated_tag = $dom->saveHTML( $tag );
                    $content = str_replace( $before_translated_tag, $translated_tag, $content );
                }
            }
        }
        return $content;
    }
    
    /**
     * Translate content.
     */
    public function translate( $content )
    {
        $this->check_languages( $this->get_source_lang(), $this->get_target_lang() );
        $translated_content = $this->get_translated( $content );
        if ( $this->get_translate_attributes() ) {
            $translated_content = $this->get_translated_attributes( $translated_content );
        }
        $translated_content = apply_filters( 'tranzly_after_translate', $translated_content, $this );
        return $translated_content;
    }
    
    /**
     * Gets the translated post.
     * This method might throw an exception so you should wrap it in a try-catch-block.
     */
    public function get_translated_post( $post_id )
    {
        $post = get_post( $post_id );
        $post_type = $post->post_type;
        $post_title = $post->post_title;
        $post_name = $post->post_name;
        $post_content = $post->post_content;
        $post_data_to_be_translated = array( 'post_title', 'post_content', 'post_name' );
        
        if ( !$this->get_translate_slug() ) {
            $key = array_search( 'post_name', $post_data_to_be_translated, true );
            if ( false !== $key ) {
                unset( $post_data_to_be_translated[$key] );
            }
        }
        
        $post_data_to_be_translated = apply_filters(
            'tranzly_post_data_to_be_translated',
            $post_data_to_be_translated,
            $post_type,
            $post_id,
            $this
        );
        $post_meta_to_be_translated = array();
        $post_meta_to_be_translated = apply_filters(
            'tranzly_post_meta_to_be_translated',
            $post_meta_to_be_translated,
            $post_type,
            $post_id,
            $this
        );
        $postarr = array(
            'ID' => $post_id,
        );
        $new_post_content = '';
        foreach ( $post_data_to_be_translated as $key ) {
            
            if ( 'post_content' === $key ) {
                $post_content_array = tranzly_split_long_string( $post_content );
                foreach ( $post_content_array as $content ) {
                    $new_post_content .= $this->translate( $content );
                }
                $translated = $new_post_content;
            } else {
                $property = ${$key};
                $translated = $this->translate( $property );
                if ( 'post_name' === $key ) {
                    $translated = sanitize_title( $translated );
                }
            }
            
            $postarr[$key] = $translated;
        }
        foreach ( $post_meta_to_be_translated as $key ) {
            $original = get_post_meta( $post_id, $key, true );
            if ( !$original ) {
                continue;
            }
            $translated = $this->translate( $original );
            $postarr['meta_data'][$key] = $translated;
        }
        return apply_filters( 'tranzly_translated_postarr', $postarr, $this );
    }
    
    /**
     * Translates the post.
     * This method might throw an exception so you should wrap it in a try-catch-block.
     */
    public function translate_post( $post_id )
    {
        $postarr = $this->get_translated_post( $post_id );
        $post_metas = ( isset( $postarr['meta_data'] ) ? $postarr['meta_data'] : array() );
        unset( $postarr['meta_data'] );
        $updated_post_id = wp_update_post( $postarr, true );
        if ( is_wp_error( $updated_post_id ) ) {
            throw new Exception( $updated_post_id->get_error_message() );
        }
        foreach ( $post_metas as $key => $meta_value ) {
            update_post_meta( $post_id, $key, $meta_value );
        }
        do_action( 'tranzly_after_translate_post', $updated_post_id, $this );
        return $updated_post_id;
    }
    
    public function get_generate_post( $post_id, $Status )
    {
        $post = get_post( $post_id );
        $post_type = $post->post_type;
        $post_title = $post->post_title;
        $post_name = $post->post_name;
        $post_content = $post->post_content;
        $post_excerpt = $post->post_excerpt;
        $post_data_to_be_translated = array( 'post_title', 'post_content' );
        
        if ( !$this->get_translate_slug() ) {
            $key = array_search( 'post_name', $post_data_to_be_translated, true );
            if ( false !== $key ) {
                unset( $post_data_to_be_translated[$key] );
            }
        }
        
        $post_data_to_be_translated = apply_filters(
            'tranzly_post_data_to_be_translated',
            $post_data_to_be_translated,
            $post_type,
            $post_id,
            $this
        );
        $post_meta_to_be_translated = array();
        $post_meta_to_be_translated = apply_filters(
            'tranzly_post_meta_to_be_translated',
            $post_meta_to_be_translated,
            $post_type,
            $post_id,
            $this
        );
        $postarr = array(
            'post_status' => $Status,
            'post_type'   => $post_type,
        );
        $new_post_content = '';
        foreach ( $post_data_to_be_translated as $key ) {
            
            if ( 'post_content' === $key ) {
                $post_content_array = tranzly_split_long_string( $post_content );
                foreach ( $post_content_array as $content ) {
                    $new_post_content .= $this->translate( $content );
                }
                $translated = $new_post_content;
            } else {
                $property = ${$key};
                $translated = $this->translate( $property );
                if ( 'post_name' === $key ) {
                    $translated = sanitize_title( $translated );
                }
            }
            
            $postarr[$key] = $translated;
        }
        foreach ( $post_meta_to_be_translated as $key ) {
            $original = get_post_meta( $post_id, $key, true );
            if ( !$original ) {
                continue;
            }
            $translated = $this->translate( $original );
            $postarr['meta_data'][$key] = $translated;
        }
        return apply_filters( 'tranzly_translated_postarr', $postarr, $this );
    }
    
    public function generate_post( $post_id, $Status )
    {
        $postarr = $this->get_generate_post( $post_id, $Status );
        $post_metas = ( isset( $postarr['meta_data'] ) ? $postarr['meta_data'] : array() );
        unset( $postarr['meta_data'] );
        $updated_post_id = wp_insert_post( $postarr, true );
        //$updated_post_id = wp_update_post( $postarr, true );
        if ( is_wp_error( $updated_post_id ) ) {
            throw new Exception( $updated_post_id->get_error_message() );
        }
        global  $wpdb ;
        /*	if(\Elementor\Plugin::$instance->db->is_built_with_elementor($post_id)){ */
        $custom_fields = get_post_custom( $post_id );
        foreach ( $custom_fields as $key => $value ) {
            if ( is_array( $value ) && count( $value ) > 0 ) {
                foreach ( $value as $i => $v ) {
                    $result = $wpdb->insert( $wpdb->prefix . 'postmeta', array(
                        'post_id'    => $updated_post_id,
                        'meta_key'   => $key,
                        'meta_value' => $v,
                    ) );
                }
            }
        }
        /*}*/
        $tranzly_post = get_post( $post_id );
        $tranzly_post_type = $tranzly_post->post_type;
        foreach ( $post_metas as $key => $meta_value ) {
            update_post_meta( $updated_post_id, $key, $meta_value );
        }
        do_action( 'tranzly_after_translate_post', $updated_post_id, $this );
        return $updated_post_id;
    }

}