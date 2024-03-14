<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists('Better_Messages_Emojis') ):

    class Better_Messages_Emojis
    {

        public $emoji_sets = [
            'apple'    => 'Apple Emojis',
            'facebook' => 'Facebook Emojis',
            'google'   => 'Google Emojis',
            'twitter'  => 'Twitter Emojis',
        ];

        public $set;

        public static function instance()
        {

            static $instance = null;

            if ( null === $instance ) {
                $instance = new Better_Messages_Emojis();
            }

            return $instance;
        }


        public function __construct()
        {
            $selected_set = Better_Messages()->settings['emojiSet'];
            $this->set = $selected_set;

            add_action( 'rest_api_init',  array( $this, 'rest_api_init' ) );
        }

        public function rest_api_init(){
            register_rest_route( 'better-messages/v1', '/getEmojiData', array(
                'methods' => 'GET',
                'callback' => array( $this, 'get_emoji_settings' ),
                'permission_callback' => '__return_true'
            ) );
        }

        public function getDataset(){
            $emoji_path = Better_Messages()->path . 'assets/emojies/' . $this->set . '.json';
            return json_decode(file_get_contents( $emoji_path ), true );
        }

        public function getSpriteUrl(){
            switch ( $this->set ){
                case 'facebook':
                    return 'https://cdn.jsdelivr.net/npm/emoji-datasource-facebook@14.0.0/img/facebook/sheets-256/64.png';
                case 'google':
                    return 'https://cdn.jsdelivr.net/npm/emoji-datasource-google@14.0.0/img/google/sheets-256/64.png';
                case 'twitter':
                    return 'https://cdn.jsdelivr.net/npm/emoji-datasource-twitter@14.0.0/img/twitter/sheets-256/64.png';
                case 'apple':
                default:
                    return 'https://cdn.jsdelivr.net/npm/emoji-datasource-apple@14.0.0/img/apple/sheets-256/64.png';
            }
        }

        public function get_emoji_settings(){
            $dataset = $this->getDataset();
            $emojis = get_option('bm-emoji-set-2');

            foreach( $dataset['categories'] as $category_index => $category ){
                $category = strtolower($category['id']);

                if( isset( $emojis[ $category ] ) ){
                    $emojis_overwrite = $emojis[$category];

                    if( count( $emojis_overwrite ) === 0 ){
                        unset( $dataset['categories'][ $category_index ] );
                    } else {
                        $dataset['categories'][ $category_index ]['emojis'] = array_values( $emojis_overwrite );
                    }
                }

            }

            return apply_filters('better_messages_get_emoji_dataset', $dataset);
        }
    }

endif;


function Better_Messages_Emojis()
{
    return Better_Messages_Emojis::instance();
}
