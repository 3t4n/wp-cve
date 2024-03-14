<?php
class showView {
    private $viewBlock;
    public function __construct() {
        add_filter( 'the_content', array($this, 'showViewBlock') );
    }
    
    public function showViewBlock($content) {
        
        global $wpdb, $post;
        
        $view = new generateView();
        
        if($view->postType != '') {
            
            $table_name = $wpdb->prefix . VC_TABLENAME;
            
            $total_view_count = 0;
            $total_view_count = $wpdb->get_var( "SELECT COUNT(nHistoryID) FROM $table_name WHERE nPostID='".$post->ID."'" );

            $user_view_count = 0;
            if ( is_user_logged_in() ) {
                $user = wp_get_current_user();
                
                $user_view_count = $wpdb->get_var( "SELECT COUNT(nHistoryID) FROM $table_name WHERE nPostID='".$post->ID."' AND nUserID='".$user->ID."'" );
                
            }
            
            $this->generateViewBlock($total_view_count, $user_view_count);
            
            return $content.$this->viewBlock;
            
        } else {
            return $content;
        }
    }
    
    private function generateViewBlock($total_view=0, $user_view=0) {
        
        $str = '<style>.stats_block {
	background: #E8E8E8;
	border: 1px solid #DCDCDC;
	font-size: 15px;  padding: 10px 5px; margin: 10px 0px;
}</style>';
        $str .= '<div class="stats_block">';
        if($total_view > 0) {
            $str.='Total Views: '.$total_view.' , ';
        }
        
        if($user_view > 0) {
            $str.='Your Views: '.$user_view;
        }
        $str .= '</div>';
        $this->viewBlock = trim(trim($str),',');
    }
    
}

new showView();