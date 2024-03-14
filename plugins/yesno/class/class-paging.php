<?php
/** 
 *	Paging
 */

class YESNO_Paging  {
	public $limit       = null;
	public $offset      = null;
	public $recordmax   = null;
	public $currentpage = null;
	public $pagemax     = null;
	public $currentdate = null;

	public function get_current_page(){
		$this->offset = 0;
		$this->currentpage = 1;
		$this->currentdate = date('Y-m-d', current_time('timestamp') );

		if ( isset( $_SERVER['QUERY_STRING'] ) ) {

			parse_str( $_SERVER['QUERY_STRING'], $qs );
			// Current page
			if ( isset( $qs['tp'] ) ){
				$page = intval( $qs['tp'] );
				if ( $page>0 && $page<= $this->pagemax ) {
					$this->currentpage = $page;
					$this->offset = ( $this->currentpage - 1 ) * $this->limit;
				}
			}
			// Date
			if ( isset( $qs['date'] ) ){
				if ( preg_match('/^([2-9][0-9]{3})-(0[1-9]{1}|1[0-2]{1})-(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $qs['date'] ) ) {
					$this->currentdate = $qs['date'];
				}
			}
		}
	}

	// Top/Last Page
	public function get_limit_page_link( $atts ){
		extract(
			$atts = shortcode_atts(
				array(
					'type' 			=> 'top',	// top or last
					'linktext'		=> '',		// リンク文字列
					'query_string' 	=> '',		// URLのQUERY_STRING
					'baseurl'		=> ''		// リンク先URL
				),
				$atts
			)
		);

		$nextpage = 0;
		// top
		if ( $type == 'top') {
			if ( $this->currentpage != 1 ) {
				$nextpage = 1;
			}
		}
		// last
		else {
			if( $this->currentpage != $this->pagemax ){
				$nextpage = $this->pagemax;
			}
		}

		$html = '';
		if ( $nextpage ) {
			parse_str( $query_string, $next );
			$next['tp'] = $nextpage;
			$nextq = http_build_query( $next );
			$html .= sprintf('<span class="valid_link"><a href="%s?%s">%s</a></span>', $baseurl, $nextq, $linktext ); 
		}
		else {
			$html .= sprintf('<span class="invalid_link">%s</span>', $linktext );
		}
		return $html;
	}

	// Next Page
	public function get_next_page_link( $atts ){
		extract(
			$atts = shortcode_atts(
				array(
					'inc' 			=> 1,		// 増減値
					'linktext'		=> '',		// リンク文字列
					'query_string' 	=> '',		// URLのQUERY_STRING
					'baseurl'		=> ''		// リンク先URL
				),
				$atts
			)
		);
		$nextpage = 0;
		// prev
		if ( $inc < 0 ){
			if ( $this->currentpage > 1 ) {
				$nextpage = $this->currentpage + $inc;
			}
		}
		// next
		else {
			if ( $this->currentpage < $this->pagemax ){
				$nextpage = $this->currentpage + $inc;
			}
		}

		$html = '';
		if ( $nextpage ) {
			parse_str( $query_string, $next );
			$next['tp'] = $nextpage;
			$nextq = http_build_query( $next );
			$html .= sprintf('<a href="%s?%s">%s</a>', $baseurl, $nextq, $linktext ); 
		}
		else {
			$html .= sprintf('<span class="invalid_link">%s</span>', $linktext );
		}
		return $html;
	}

	// Prev Page
	public function get_prev_page_link( $atts ){
		extract(
			$atts = shortcode_atts(
				array(
					'inc' 			=> -1,		// 増減値
					'linktext'		=> '',		// リンク文字列
					'query_string' 	=> '',		// URLのQUERY_STRING
					'baseurl'		=> ''		// リンク先URL
				),
				$atts
			)
		);
		return $this->get_next_page_link( $atts );
	}

	// Next Date
	public static function get_next_date_link( $atts ){
		extract(
			$atts = shortcode_atts(
				array(
					'currentdate'   => '',		// 基準日
					'inc' 			=> 1,		// 増減値
					'linktext'		=> '',		// リンク文字列
					'query_string' 	=> '',		// URLのQUERY_STRING
					'baseurl'		=> ''		// リンク先URL
				),
				$atts
			)
		);

		// prev / next
		$nextdate = strtotime( $currentdate ) + $inc*60*60*24;

		$html = '';
		parse_str( $query_string, $next );
		$next['date'] = date('Y-m-d', $nextdate );
		$nextq = http_build_query( $next );
		$html .= sprintf('<a href="%s?%s">%s</a>', $baseurl, $nextq, $linktext ); 
		return $html;
	}

	// Prev Date
	public static function get_prev_date_link( $atts ){
		extract(
			$atts = shortcode_atts(
				array(
					'currentdate'   => '',		// 基準日
					'inc' 			=> -1,		// 増減値
					'linktext'		=> '',		// リンク文字列
					'query_string' 	=> '',		// URLのQUERY_STRING
					'baseurl'		=> ''		// リンク先URL
				),
				$atts
			)
		);
		return self::get_next_date_link( $atts );
	}

	// Page Navi
	public function page_navi( $obj ) {

		$format = <<<EOD
<div id="list_pagenavi" class="list_pagenavi">
<div id="prev_page" class="prev_page">%PREV_PAGE%</div>
<div id="next_page" class="next_page">%NEXT_PAGE%</div>
</div>
EOD;
		$search = array(
			'%PREV_PAGE%',
			'%NEXT_PAGE%',
		);
		$atts = array(
			'query_string' 	=> $_SERVER['QUERY_STRING'],
		);
		$atts['linktext'] = __('&laquo;', 'yesno');
		$prev_link = $obj->get_prev_page_link( $atts );
		$atts['linktext'] = __('&raquo;', 'yesno');
		$next_link = $obj->get_next_page_link( $atts );
		$replace = array(
			$prev_link,
			$next_link,
			);
		return str_replace( $search, $replace, $format );
	}

	// Page Navi
	public function page_navi_full( $obj ) {

		$format = <<<EOD
<div id="list_pagenavi-tpcnl" class="list_pagenavi-tpcnl">
<div id="first_page" class="first_page">%FIRST_PAGE%</div>
<div id="prev_page" class="prev_page">%PREV_PAGE%</div>
<div id="current_page" class="current_page">%CURRENT_PAGE%</div>
<div id="next_page" class="next_page">%NEXT_PAGE%</div>
<div id="last_page" class="last_page">%LAST_PAGE%</div>
</div>
EOD;
		$search = array(
			'%FIRST_PAGE%',
			'%PREV_PAGE%',
			'%CURRENT_PAGE%',
			'%NEXT_PAGE%',
			'%LAST_PAGE%',
		);
		$atts = array(
			'query_string' 	=> $_SERVER['QUERY_STRING'],
		);
		$atts['type'] = 'top';
		$atts['linktext'] = __('«', 'yesno');
		$top_link = $obj->get_limit_page_link( $atts );

		$atts['linktext'] = __('‹', 'yesno');
		$prev_link = $obj->get_prev_page_link( $atts );
		
		$atts['linktext'] = __('›', 'yesno');
		$next_link = $obj->get_next_page_link( $atts );
		
		$atts['type'] = 'last';
		$atts['linktext'] = __('»', 'yesno');
		$last_link = $obj->get_limit_page_link( $atts );

		$replace = array(
			$top_link,
			$prev_link,
			sprintf('<span>%d / %d</span>', $obj->currentpage, $obj->pagemax ),
			$next_link,
			$last_link
			);
		return str_replace( $search, $replace, $format );
	}

}

?>
