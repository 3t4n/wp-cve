<?php
if (! defined ( 'ABSPATH' ))
    exit (); // Exit if accessed directly

require_once 'abstract-xh-paging-model.php';

class WShop_Paging_Model_WP extends WShop_Abstract_Paging_Model {
    private $the_query;
    private $url_call =null;
    private $params = array();
    
	public function __construct($atts = array(),$the_query=null) {
	    if(!$atts||!is_array($atts)){
	        $atts = array();
	    }
	    if(!$the_query){
	        global $wp_query;
	        $the_query = $wp_query;
	    }
	    
	    if(!isset($atts['page_size'])){
	        $page_size =absint( get_option( 'posts_per_page' ));
	    }else{
	        $page_size = absint($atts['page_size']);
	    }
	    
	    if(!isset($atts['page_index'])){
	        $page_index = absint( get_query_var( 'paged' ) );
	    }else{
	        $page_index = absint($atts['page_index']);
	    }
	    
	    if(!isset($atts['url_call'])){
	        $this->url_call ='get_pagenum_link';
	    }else{
	        $this->url_call =$atts['url_call'];
	    }
	    
	    $this->the_query = $the_query;
	    $total_count = $this->the_query->found_posts;
	    
	    $this->params[0]=null;
	    $args_qty = func_num_args();
	    if($args_qty>2){
	        for ($i=2;$i<$args_qty;$i++){
	            $this->params[]=func_get_arg($i);
	        }
	    }
	    
		parent::__construct ( $page_index, $page_size, $total_count );
	}
	
	protected function url($page_index) {
	    if(!$this->url_call){return null;}
	    
	    $this->params[0]=$page_index;
	    
	    return call_user_func_array($this->url_call, $this->params);
	}

	public function bootstrap($class = 'xh-pagination xh-pagination-sm') {
	    if(is_array($class)){
	        $class= join(' ', $class);
	    }
		if ($this->page_count <= 0) {
			return '';
		}
		$output = '<ul class="' . $class . '">';
		
		if (! $this->is_first_page) {
			$output .= '<li class="first"><a href="' . $this->url ( $this->page_index - 1 ) . '"><<</a></li>';
		} else {
			$output .= '<li class="first disabled"><span><<</span></li>';
		}
		
		if ($this->start_page_index > 1) {
			$output .= '<li><a href="' . $this->url ( 1 ) . '">1</a></li>';
			if ($this->start_page_index > 2) {
				$output .= '<li><span>...</span></li> ';
			}
		}
		
		for($i = $this->start_page_index; $i <= $this->end_page_index; $i ++) {
			$output .= '<li ' . ($i == $this->page_index ? 'class="page active"' : 'class="page"') . '><a href="' . $this->url ( $i ) . '">' . $i . '</a></li>';
		}
		
		if ($this->end_page_index < $this->page_count) {
			if ($this->end_page_index < $this->page_count - 1) {
				$output .= ' <li><span>...</span></li>';
			}
			$output .= '<li ><a href="' . $this->url ( $this->page_count ) . '">' . $this->page_count . '</a></li>';
		}
		
		if ($this->is_last_page) {
			$output .= '<li class="last disabled"><span>>></span></li>';
		} else {
			$output .= ' <li class="last"><a href="' . $this->url ( $this->page_index + 1 ) . '">>></a></li>';
		}
		
		$output .= "</ul>";
		return $output;
	}
}