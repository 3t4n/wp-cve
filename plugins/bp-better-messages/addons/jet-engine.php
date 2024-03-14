<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists('Better_Messages_Jet_Engine') ):

    class Better_Messages_Jet_Engine
    {

        public static function instance()
        {

            static $instance = null;

            if ( null === $instance ) {
                $instance = new Better_Messages_Jet_Engine();
            }

            return $instance;
        }


        public function __construct()
        {
            add_filter( 'better_messages_get_member_id', array( $this, 'detect_user_id' ), 10, 1 );
            add_filter( 'better_messages_rest_user_item', array( $this, 'user_meta' ), 20, 3 );
        }

        public function user_meta( $item, $user_id, $include_personal ){
            $enable_avatar = Better_Messages()->settings['jetEngineAvatars'] === '1';

            if( $enable_avatar ) {
                if ( jet_engine()->modules->is_module_active('profile-builder') ) {
                    if( $enable_avatar ) {
                        $field = \Jet_Engine\Modules\Profile_Builder\Module::instance()->settings->get('user_page_seo_image', '');

                        if ($field) {
                            $avatar_meta = get_user_meta($user_id, $field, true);

                            if ($avatar_meta) {
                                $url = wp_get_attachment_image_url($avatar_meta);
                                if ($url) {
                                    $item['avatar'] = $url;
                                }
                            }
                        }
                    }
                }
            }

            return $item;
        }

        public function detect_user_id( $user_id ){
            if ( jet_engine()->modules->is_module_active( 'profile-builder' ) ) {
                $profile_builder = jet_engine()->modules->get_module( 'profile-builder' );
                $user_object     = $profile_builder->instance->query->get_queried_user();
                if( $user_object ) {
                    return $user_object->ID;
                }
            }

            return $user_id;
        }
    }

endif;
