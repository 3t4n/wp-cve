<?php
/**
 * KBoard 위젯 게시글, 댓글 리스트
 * @link http://www.cosmosfarm.com
 * @copyright Copyright 2017 Cosmosfarm. All rights reserved.
 */
class KBoardWidgetList {
	
	public function getListResults($value, $limit, $exclude, $with_notice){
		global $wpdb;
		
		$where = array();
		$results = array();
		
		if($value == 'comment' || $value == 'my_comment'){
			$select = "`comment`.`uid`";
			$from[] = "`{$wpdb->prefix}kboard_comments` AS `comment`";
			$from[] = "INNER JOIN `{$wpdb->prefix}kboard_board_content` AS `content` on `content`.`uid` = `comment`.`content_uid`";
			// 제외할 게시판 아이디 (댓글)
			$exclude != '' ? $where[] = "`content`.`board_id` NOT IN ({$exclude})":'';
			// 휴지통에 없는 게시글만 불러온다. (댓글)
			$where[] = "(`content`.`status`='' OR `content`.`status` IS NULL OR `content`.`status`='pending_approval')";
			$where[] = '1';
			$orderby = "`created` DESC";
		}
		else{
			$select = "`{$wpdb->prefix}kboard_board_content`.`uid`";
			$from[] = "`{$wpdb->prefix}kboard_board_content`";
			// 제외할 게시판 아이디
			$exclude != ''?$where[] = "`board_id` NOT IN ({$exclude})":'';
			// 휴지통에 없는 게시글만 불러온다.
			$where[] = "(`status`='' OR `status` IS NULL OR `status`='pending_approval')";
			// 공지사항 포함 여부
			if($value != 'notice' && !$with_notice){
				$where[] = "(`notice`='' OR `notice` is NULL)";
			}
			$orderby = "`date` DESC";
		}
		
		switch($value){
			case 'latest': break;
			case 'vote': $orderby = "`vote` DESC, `date`";
			break;
			case 'view': $orderby = "`view` DESC, `date`";
			break;
			case 'notice':	$where[] = "`notice`= 'true'";
			break;
			case 'my_post':
				if(is_user_logged_in()){
					$where[] = "`member_uid`=" . get_current_user_id();
				}
				else{
					return array();
				}
				break;
			case 'comment': break;
			case 'my_comment':
				if(is_user_logged_in()){
					$where[] = "`user_uid`=" . get_current_user_id();
				}
				else{
					return array();
				}
				break;
			default: break;
		}
		
		$select = apply_filters('kboard_widget_list_select', $select, $value, $limit, $exclude, $with_notice);
		$from = apply_filters('kboard_widget_list_from', implode(' ', $from), $value, $limit, $exclude, $with_notice);
		$where = apply_filters('kboard_widget_list_where', implode(' AND ', $where), $value, $limit, $exclude, $with_notice);
		$orderby = apply_filters('kboard_widget_list_orderby', $orderby, $value, $limit, $exclude, $with_notice);
		$limit =  apply_filters('kboard_widget_list_limit', $limit, $value, $exclude, $with_notice);
		
		$results = $wpdb->get_results("SELECT {$select} FROM {$from} WHERE {$where} ORDER BY {$orderby} LIMIT {$limit}");
		
		if($results){
			foreach($results as $key=>$row){
				$url = new KBUrl();
				
				if($value == 'comment' || $value == 'my_comment'){
					$comment = new KBcomment();
					$comment->initWithUID($row->uid);
					$comment->url = $url->getDocumentRedirect($comment->content_uid);
					
					// 최신댓글 View에서 게시물이 비밀글인지
					$content = new KBContent();
					$content->initWithUID($comment->content_uid);
					$comment->secret = $content->secret;
					
					$notify_time = apply_filters('kboard_widget_new_comment_notify_time()', kboard_new_document_notify_time(), $content, $comment);
					if((current_time('timestamp')-strtotime($comment->created)) <= $notify_time && $notify_time != '1'){
						$comment->is_new = true;
					}
					else{
						$comment->is_new = false;
					}
					
					if(date('Ymd', current_time('timestamp')) == date('Ymd', strtotime($comment->created))){
						$comment->created = date('H:i', strtotime($comment->created));
					}
					else{
						$comment->created = date('Y.m.d', strtotime($comment->created));
					}
					
					$comment->row_type = 'comment';
					$results[$key] = $comment;
				}
				else{
					$content = new KBContent();
					$content->initWithUID($row->uid);
					$content->url = $url->getDocumentRedirect($content->uid);
					$content->row_type = 'content';
					$results[$key] = $content;
				}
			}
			
			return $results;
		}
		
		return '';
	} // end function
	
} // end class
?>