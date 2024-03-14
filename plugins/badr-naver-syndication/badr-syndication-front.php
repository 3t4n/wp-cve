<?php
require_once (trailingslashit ( dirname ( __FILE__ ) ) . 'badr-syndication-class.php');
class badrSyndicationFront extends badrSyndication {
	private $page = null; 
	private $post_id = null;
	private $indexed = null;
	private $message = null;
	private $updateTime = null;
	
	function init() {
		//ChromePhp::log($_SERVER);
		$this->setVars ();
		add_filter ( 'template_redirect', array ( &$this, 'dispSyndicationList' ), 1, 0 );
	}
	function setVars() {
		if (empty ( $this->_aOptions['key'] ) || empty ( $this->_aOptions['name'] ) || empty ( $this->_aOptions['email'] ))
			$this->dispErrorMessage ( 1 );
			
		preg_match ( '/^([p||l][aegiost]{3})-([0-9]+)?\.xml$/i', $_GET ['syndication_feeds'], $matches );
		if (empty ( $matches[0] ))	$this->dispErrorMessage ( 2 );
		if ($matches [1] == 'post') {
			$this->post_id = $matches [2];
		} elseif($matches [1] == 'page') {
			$this->page = $matches [2];
		} else {
			$this->indexed =  $matches [2];
		}
		
		/* 봇방문시 문서의 update date를 현재시각으로 통일한다. @since 0.8*/
		$this->updateTime = $this->getLastUpdatedTime();
		
	}
	/**
	 * 네이버 검색봇 Yeti의 방문기록 저장
	 */
	function insertLog(){
		if( empty($_SERVER['HTTP_USER_AGENT']) || strpos($_SERVER['HTTP_USER_AGENT'], 'Yeti') === false ) return;
		//$this->_procDB('insertLog', array('url' => $_SERVER['REQUEST_URI']));
		update_option('_syndication_yeti', current_time('Y-m-d H:i:s') );
	}
	function dispErrorMessage($nNum) {
		$this->message = $nNum;
		$this->dispTemplate ();
	}
	function dispTemplate() {
		if ($this->message ) {
			$this->message = $this->message;
			$this->tplFile = $this->setTemplateFile ( 'xml_error' );
		}
		header ( 'Content-Type: application/xml; charset=utf-8' );
		require ($this->tplFile);
		$this->insertLog();
		exit ();
	}
	function dispSyndicationList() {
		global $wp_query;
		$wp_query->is_404 = false;
		$wp_query->is_feed = false;
		$this->getSyndicationList ();
		// header('Content-Type: application/xml; charset=utf-8');
		$this->dispTemplate ();
	}
	function getSyndicationList() {
		$this->id = $this->getId ();
		$this->title = htmlspecialchars ( get_option ( 'blogname' ) );
		$this->author->name = $this->_aOptions['name'];
		$this->author->email = $this->_aOptions['email'];
		$this->updated = $this->updateTime;
		$this->link->href = $this->_baseUrl;
		$this->link->title = $this->title;
		$this->tplFile = $this->setTemplateFile ( 'feeds' );
		$this->getEntries ();
	}
	function getEntries() {
		if (! is_null ( $this->indexed )) return $this->getIndexedList();
		if (! is_null ( $this->post_id )) {
			$arg = 'p=' . $this->post_id;
		} else {
			$arg = $this->_getPostsQuery();
			$arg['paged'] = $this->page;
		}
		
		$query = new WP_Query ( $arg );
		$total_page = $query->max_num_pages;
		if( $this->page > $total_page ) $this->dispErrorMessage ( 2 );
		if ($query->have_posts ()) {
			$bUseParameter = isset($this->_aOptions['use_ex_parameter']) && $this->_aOptions['use_ex_parameter'] == '1';
			$i = 0;
			foreach ( $query->posts as $oPost ) {
				$sMeta = get_post_meta ( $oPost->ID, '_syndication', true );
				/* meta 값이 없을경우는 플러그인 설치 이전 발행문서이기때문에 meta값을 업데이트해줘야한다. */
				$this->entries[$i]->syndication = ($sMeta === "") ? (int) date('YmdHis', current_time('timestamp')) : (int) $sMeta;
				$oCategory = $this->getUniqCategory ( $oPost->ID );
				$this->entries[$i]->id = htmlspecialchars( $oPost->guid.( $bUseParameter && $this->entries[$i]->syndication ? '&s='.abs($this->entries[$i]->syndication) : '') );
				$this->entries[$i]->title = htmlspecialchars ( $oPost->post_title );
				// $this->entries[$i]->summary = get_the_excerpt();
				$this->entries[$i]->content = htmlspecialchars ( $oPost->post_content );
				$this->entries[$i]->updated = $this->updateTime; //$this->setDate ( $oPost->post_modified_gmt ); 0.8이후
				$this->entries[$i]->regdate = $this->setDate ( $oPost->post_date_gmt );
				$this->entries[$i]->via_href = $this->_baseUrl . '/?cat=' . $oCategory->cat_ID;
				$this->entries[$i]->via_title = htmlspecialchars ( urldecode ( $oCategory->name ) );
				$this->entries[$i]->mobile_href = $this->entries[$i]->id;
				$this->entries[$i]->nick_name = htmlspecialchars ( get_the_author_meta ( 'display_name', $oPost->post_author ) );
				$this->entries[$i]->category_term = $oCategory->cat_ID;
				$this->entries[$i]->category_label = htmlspecialchars ( $this->entries[$i]->via_title );
				unset($sMeta);
				$i++;
			}
		} else {
			return $this->getDeleted ();
		}
		wp_reset_postdata ();
	}
	function getDeleted() {
		$this->entries[0]->syndication = false;
		$this->entries[0]->id = $this->_baseUrl. '/?p=' . $this->post_id;
		$this->entries[0]->regdate = $this->setDate ( date ( 'Y-m-d H:i:s' ) );
	}
	/**
	 * api를 통해 네이버에 색인된 문서목록을 가져온다.
	 * 
	 */
	function getIndexedList() {
		$aResult = $this->_procDB('getIndexedLog',array('start' => $this->indexed));
		$i = 0;
		foreach( $aResult as $list){
			$this->entries[$i]->syndication = false;
			$this->entries[$i]->id = 'http://'.htmlentities($list['link']);
			$this->entries[$i]->regdate = $this->setDate ( date ( 'Y-m-d H:i:s' ) );
			$i++;
		}
	}
	function getID() {
		return sprintf ( '%s/?%s', $this->_baseUrl, $_SERVER['QUERY_STRING'] );
	}
	function getUniqCategory($post_id) {
		$aCategory = get_the_category ( $post_id );
		foreach ( $aCategory as $oCategory ) {
			if (in_array ( $oCategory->cat_ID, $this->_aCategory ))
				return $oCategory;
		}
	}
	/**
	 * 0.8 봇이 방문시 현재 시각을 리턴하는 것으로 수정
	 * @param string $category_id
	 */
	function getLastUpdatedTime($category_id = null) {
		return $this->setDate(); //0.8이후
		global $wpdb;
		if (is_null ( $category_id ))
			$category_id = $this->_sCategory;
		$query_string = "
			SELECT P.post_modified_gmt FROM " . $wpdb->posts . "
			AS P, " . $wpdb->term_relationships . " AS R WHERE P.ID = R.object_id
			AND R.term_taxonomy_id in (" . $category_id . ") and P.post_status='publish'
			AND P.post_type = 'post' AND P.post_password = '' ORDER BY P.post_date DESC LIMIT 1";
		return $this->setDate ( $wpdb->get_var ( $query_string ) );
	}
	
	/*
	 * 0.7.3 getLastUpdatedTime()에서 get_var()이 null을 리턴하는 경우
	 */
	function setDate($time = null) {
		if (is_null ( $time ))
			$time = date ( 'Y-m-d H:i:s' );
		return mysql2date ( 'Y-m-d\TH:i:s\Z', $time, false );
	}
	function setTemplateFile($file_name) {
		return plugin_dir_path ( __FILE__ ) . 'tpl/' . $file_name . '.php';
	}
}