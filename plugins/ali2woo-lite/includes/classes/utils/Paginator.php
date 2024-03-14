<?php

/**
 * Description of Paginator
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class Paginator {
    public static function build($total, $per_page=20, $links=4, $request_param = 'cur_page'){
        $page = isset($_REQUEST[$request_param]) && intval($_REQUEST[$request_param]) ? intval($_REQUEST[$request_param]) : 1;

        $pages_list = array();
        
        $last = ceil($total / $per_page);
        
        if($page<1){
            $page=1;
        }
        
        if($page>$last){
            $page = $last;
        }
        
        $start = ( ( $page - $links ) > 0 ) ? $page - $links : 1;
        $end = ( ( $page + $links ) < $last ) ? $page + $links : $last;
        if ($start > 1) {
            $pages_list[] = 1;
            $pages_list[] = '';
        }
        for ($i = $start; $i <= $end; $i++) {
            $pages_list[] = $i;
        }
        if ($end < $last) {
            $pages_list[] = '';
            $pages_list[] = $last;
        }
        
        return array('total_pages'=>$last, 'cur_page'=>$page, 'per_page'=>$per_page, 'pages_list'=>$pages_list);
    }
}

