<?php

if ( !class_exists( 'WPFA_Global_Dashboard' ) ) {
    class WPFA_Global_Dashboard
    {
        private static  $instance = false ;
        var  $current_site_id = null ;
        private function __construct()
        {
        }
        
        function init()
        {
            if ( !is_multisite() ) {
                return;
            }
        }
        
        function get_site_id_for_admin_content()
        {
            $blog_id = null;
            $user_belongs_to_blogs = null;
            if ( is_multisite() && $this->get_dashboard_site_id() ) {
                $blog_id = $this->get_current_site_id();
            }
            return apply_filters( 'wp_frontend_admin/site_id_for_admin_content', $blog_id, $user_belongs_to_blogs );
        }
        
        function switch_to_dashboard_site()
        {
            $out = false;
            $global_dashboard_id = $this->get_dashboard_site_id();
            
            if ( $global_dashboard_id && is_multisite() && get_current_blog_id() !== $global_dashboard_id ) {
                $original_blog_id = get_current_blog_id();
                switch_to_blog( $global_dashboard_id );
                $out = $original_blog_id;
            }
            
            return $out;
        }
        
        function restore_site( $site_id )
        {
            $global_dashboard_id = $this->get_dashboard_site_id();
            if ( $global_dashboard_id && $site_id && is_multisite() && get_current_blog_id() !== $site_id ) {
                restore_current_blog();
            }
        }
        
        function is_global_dashboard()
        {
            $out = false;
            $global_dashboard_id = (int) $this->get_dashboard_site_id();
            if ( $global_dashboard_id && is_multisite() && get_current_blog_id() === $global_dashboard_id ) {
                $out = true;
            }
            return $out;
        }
        
        function get_dashboard_site_id()
        {
            $id = VG_Admin_To_Frontend_Obj()->get_settings( 'global_dashboard_id' );
            
            if ( $id && !get_site( $id ) ) {
                // If we saved a global dashboard id but the site does not exist, unset it on the options page
                VG_Admin_To_Frontend_Obj()->update_option( 'global_dashboard_id', '' );
                $id = null;
            }
            
            return (int) $id;
        }
        
        function get_dashboard_url( $blog_id, $url = null )
        {
            $dashboard_site_id = $this->get_dashboard_site_id();
            if ( empty($url) ) {
                $url = VG_Admin_To_Frontend_Obj()->get_settings( 'redirect_to_frontend', get_site_url( $dashboard_site_id, '/' ) );
            }
            switch_to_blog( $dashboard_site_id );
            $url = add_query_arg( 'vgfa_site_id', $blog_id, $url );
            $url = esc_url( wp_nonce_url( $url, 'wpfa' ) );
            restore_current_blog();
            return $url;
        }
        
        function get_manageable_sites( $user_id = null )
        {
            if ( !$user_id ) {
                $user_id = get_current_user_id();
            }
            if ( !is_multisite() ) {
                return array();
            }
            $user_belongs_to_blogs = get_blogs_of_user( $user_id );
            
            if ( VG_Admin_To_Frontend_Obj()->is_master_user() ) {
                $network_sites = VG_Admin_To_Frontend_Obj()->get_network_sites();
                $allowed_sites = array_keys( $network_sites );
            } else {
                $allowed_sites = array();
                foreach ( $user_belongs_to_blogs as $user_blog ) {
                    switch_to_blog( $user_blog->userblog_id );
                    if ( user_can( $user_id, 'edit_posts' ) ) {
                        $allowed_sites[] = $user_blog->userblog_id;
                    }
                    restore_current_blog();
                }
            }
            
            return $allowed_sites;
        }
        
        function get_current_site_id( $user_id = null )
        {
            if ( !$user_id ) {
                $user_id = get_current_user_id();
            }
            $site_id = (int) get_user_meta( $user_id, 'wpfa_current_site_id', true );
            $allowed_site_ids = $this->get_manageable_sites( $user_id );
            $blog_id = null;
            if ( $allowed_site_ids ) {
                $blog_id = ( $site_id && in_array( $site_id, $allowed_site_ids, true ) ? $site_id : end( $allowed_site_ids ) );
            }
            return $blog_id;
        }
        
        function switch_site( $site_id )
        {
            if ( !$site_id ) {
                return;
            }
            $allowed_site_ids = $this->get_manageable_sites();
            if ( !$allowed_site_ids ) {
                return;
            }
            if ( !in_array( $site_id, $allowed_site_ids, true ) ) {
                return;
            }
            $this->current_site_id = (int) $site_id;
            update_user_meta( get_current_user_id(), 'wpfa_current_site_id', $site_id );
        }
        
        /**
         * Creates or returns an instance of this class.
         */
        static function get_instance()
        {
            
            if ( null == WPFA_Global_Dashboard::$instance ) {
                WPFA_Global_Dashboard::$instance = new WPFA_Global_Dashboard();
                WPFA_Global_Dashboard::$instance->init();
            }
            
            return WPFA_Global_Dashboard::$instance;
        }
        
        function __set( $name, $value )
        {
            $this->{$name} = $value;
        }
        
        function __get( $name )
        {
            return $this->{$name};
        }
    
    }
}
if ( !function_exists( 'WPFA_Global_Dashboard_Obj' ) ) {
    function WPFA_Global_Dashboard_Obj()
    {
        return WPFA_Global_Dashboard::get_instance();
    }

}
WPFA_Global_Dashboard_Obj();