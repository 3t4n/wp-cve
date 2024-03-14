<?php
/**
 * KBoard 위젯
 * @link http://www.cosmosfarm.com
 * @copyright Copyright 2017 Cosmosfarm. All rights reserved.
 */
final class KBoardWidget extends WP_Widget {
	
	public function __construct(){
		parent::__construct('kboard_widget', 'KBoard 위젯', array(
			'classname' => 'kboard_widget',
			'description' => '최신글, 최신댓글, 추천글, 인기글, 공지사항, 내가쓴글, 내가쓴댓글로 구성된 탭 위젯입니다.',
		));
	}
	
	public function widget($args, $instance){
		
		echo $args['before_widget'];
		
		if(isset($instance['title'])){
			echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
		}
		
		$tab = (isset($instance['tab']))?strip_tags($instance['tab']):'';
		$tab_sep = $tab ? explode(',' , $tab) : array();
		
		$skin_style = (isset($instance['skin']))?strip_tags($instance['skin']):'';
		$limit = (isset($instance['limit'])&&intval($instance['limit'])>0)?intval($instance['limit']):5;
		$exclude = (isset($instance['exclude']))?esc_sql($instance['exclude']) : '';
		$with_notice = (isset($instance['with_notice']))?strip_tags($instance['with_notice']):'';
		
		$list = new KBoardWidgetList();
		
		wp_enqueue_script('kboard-widget-script', KBOARD_WIDGET_URL . "/skin/{$skin_style}/script.js", array(), KBOARD_WIDGET_VERSION);
		wp_enqueue_style('kboard-widget-style', KBOARD_WIDGET_URL . "/skin/{$skin_style}/style.css", array(), KBOARD_WIDGET_VERSION);
		
		$skin_path = KBOARD_WIDGET_URL . "/skin/{$skin_style}";
		
		if(file_exists(KBOARD_WIDGET_DIR_PATH . "/skin/{$skin_style}/list.php")){
			include KBOARD_WIDGET_DIR_PATH . "/skin/{$skin_style}/list.php";
		}
		
		echo $args['after_widget'];
	}
	
	public function form($instance){
		$tab_list = apply_filters('kboard_widget_tab_list', array('latest', 'comment', 'vote', 'view', 'notice', 'my_post', 'my_comment'));
		
		$title = (isset($instance['title']))?$instance['title']:'';
		$limit = (isset($instance['limit']))&&$instance['limit']>0?$instance['limit']:'5';
		$exclude = (isset($instance['exclude']))?$instance['exclude']:'';
		$tab = (isset($instance['tab']))?$instance['tab']:'';
		$tab_sep = $tab ? explode(',' , $tab) : array();
		$skin_style = (isset($instance['skin']))?$instance['skin']:'';
		$with_notice = (isset($instance['with_notice']))?$instance['with_notice']:'0';
		
		$dir = KBOARD_WIDGET_DIR_PATH . '/skin';
		if($dh = @opendir($dir)){
			while(($name = readdir($dh)) !== false){
				if($name == "." || $name == ".." || $name == "readme.txt") continue;
				$skin = new stdClass();
				$skin->name = $name;
				$skin->dir = KBOARD_WIDGET_DIR_PATH . "/skin/{$name}";
				$skin->url = KBOARD_WIDGET_URL . "/skin/{$name}";
				$list[$name] = $skin;
			}
		}
		closedir($dh);
		
		$list = apply_filters('kboard_widget_skin_list', $list);
		
		wp_enqueue_script('kboard-widget-admin-script', KBOARD_WIDGET_URL . "/admin/admin.js", array(), KBOARD_WIDGET_VERSION);
		wp_enqueue_style('kboard-widget-admin-style', KBOARD_WIDGET_URL . "/admin/admin.css", array(), KBOARD_WIDGET_VERSION);
		
		include KBOARD_WIDGET_DIR_PATH . '/admin/setting.php';
	}
	
	public function update($new_instance, $old_instance){
		$instance = array();
		$instance['title'] = (isset($new_instance['title']))?strip_tags($new_instance['title']):'';
		$instance['limit'] = (isset($new_instance['limit']))?intval($new_instance['limit']):'';
		$instance['exclude'] = (isset($new_instance['exclude']))?strip_tags($new_instance['exclude']):'';
		$instance['tab'] = (isset($new_instance['tab']))?strip_tags($new_instance['tab']):'';
		$instance['skin'] = (isset($new_instance['skin']))?strip_tags($new_instance['skin']):'';
		$instance['with_notice'] = (isset($new_instance['with_notice']))?intval($new_instance['with_notice']):'';
		
		return $instance;
	}
}
?>