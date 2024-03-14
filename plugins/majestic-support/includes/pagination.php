<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_pagination {

    private static $_limit;
    private static $_offset;

    static function MJTC_setLimit($limit){
        if(is_numeric($limit))
            self::$_limit = $limit;
    }

    static function MJTC_getLimit(){
        return (int) self::$_limit;
    }

    static function MJTC_setOffset($offset){
        if(is_numeric($offset))
            self::$_offset = $offset;
    }

    static function MJTC_getOffset(){
        return (int) self::$_offset;
    }

    static function MJTC_getPagination($total,$layout=null) {
        if(!is_numeric($total)) return false;
        $pagenum = isset($_GET['pagenum']) ? majesticsupport::MJTC_sanitizeData(absint($_GET['pagenum'])) : 1;// MJTC_sanitizeData() function uses wordpress santize functions
        if(!self::MJTC_getLimit()){
            self::MJTC_setLimit(majesticsupport::$_config['pagination_default_page_size']); // number of rows in page
        }
        $offset = ( $pagenum - 1 ) * self::$_limit;
        self::MJTC_setOffset($offset);
        $num_of_pages = ceil($total / self::$_limit);
        $num_of_pages = ($num_of_pages > 0) ? ceil($num_of_pages) : floor($num_of_pages);
        $layargs = add_query_arg('pagenum', '%#%');
        if($layout != null && get_option( 'permalink_structure' ) != ""){
            $layargs = add_query_arg(array('pagenum'=>'%#%' , 'mjtcslay'=>$layout));
        }
        $result = paginate_links(array(
            'base' => $layargs,
            'format' => '',
            'prev_next' => true,
            'prev_text' => esc_html(__('Previous', 'majestic-support')),
            'next_text' => esc_html(__('Next', 'majestic-support')),
            'total' => $num_of_pages,
            'current' => $pagenum,
            'add_args' => false,
        ));
        return $result;
    }

    static function MJTC_isLastOrdering($total, $pagenum) {
        if(!is_numeric($total)) return false;
        if(!is_numeric($pagenum)) return false;
        $maxrecord = $pagenum * majesticsupport::$_config['pagination_default_page_size'];
        if ($maxrecord >= $total)
            return false;
        else
            return true;
    }

}

?>
