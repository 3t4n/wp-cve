<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSpagination {

    static $_limit;
    static $_offset;
    static $_currentpage;

    static function getPagination($total,$layout=null) {
        $pagenum = isset($_GET['pagenum']) ? absint(sanitize_key($_GET['pagenum'])) : 1;
        self::$_limit = jsjobs::$_configuration['pagination_default_page_size']; // number of rows in page
        self::$_offset = ( $pagenum - 1 ) * self::$_limit;
        self::$_currentpage = $pagenum;
        $num_of_pages = ceil($total / self::$_limit);
        $layargs = '';
        if($layout != null && get_option( 'permalink_structure' ) != ""){
            $layargs = add_query_arg(array('pagenum'=>'%#%' , 'jsjobslt'=>$layout));
        }
        $result = '';
        if(is_admin()){
            $result = paginate_links(array(
                'base' => add_query_arg('pagenum', '%#%'),
                // $layargs,
                'format' => '',
                'prev_next' => true,
                'prev_text' => __('Previous', 'js-jobs'),
                'next_text' => __('Next', 'js-jobs'),
                'total' => $num_of_pages,
                'current' => $pagenum,
                'add_args' => false,
            ));
        }else{
            if(jsjobs::$theme_chk != 0){
                $links = paginate_links( array(
                    'type' => 'array',
                    'base' => $layargs,
                    // add_query_arg('pagenum', '%#%'),
                    'format' => '',
                    'prev_next' => true,
                    'prev_text' => '&laquo;',
                    'total' => $num_of_pages,
                    'current' => $pagenum,
                    'next_text' => '&raquo;',
                    'add_args' => false,
                ));
                if(!empty($links) && is_array($links)){
                    $result = '<ul class="pagination pagination-lg">';
                    foreach($links AS $link){
                        if(jsjobslib::jsjobs_strstr($link, 'current')){
                            $result .= '<li class="active">'.$link.'</li>';
                        }else{
                            $result .= '<li>'.$link.'</li>';
                        }
                    }
                    $result .= '</ul>';
                }
            }else{
                $result = paginate_links(array(
                            'base' => $layargs, 
                            // add_query_arg('pagenum', '%#%'),
                            'format' => '',
                            'prev_next' => true,
                            'prev_text' => __('Previous', 'js-jobs'),
                            'next_text' => __('Next', 'js-jobs'),
                            'total' => $num_of_pages,
                            'current' => $pagenum,
                            'add_args' => false,
                        ));
           }

        }
        return $result;
    }

    static function isLastOrdering($total, $pagenum) {
        $maxrecord = $pagenum * jsjobs::$_configuration['pagination_default_page_size'];
        if ($maxrecord >= $total)
            return false;
        else
            return true;
    }

}

?>
