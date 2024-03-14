<?php
/*
Plugin Name: FV Antispam
Plugin URI: http://foliovision.com/seo-tools/wordpress/plugins/fv-antispam
Description: Powerful and simple antispam plugin. Puts all the spambot comments directly into trash and let's other plugins (Akismet) deal with the rest.
Author: Foliovision
Version: 2.7
Author URI: http://www.foliovision.com
*/


$fv_antispam_ver = '2.7';
$FV_Antispam_iFilledInCount = 0;
$FV_Antispam_bMathJS = false;


if (!function_exists ('is_admin')) {
  header('Status: 403 Forbidden');
  header('HTTP/1.1 403 Forbidden');
  exit();
}


require_once( dirname(__FILE__) . '/include/fp-api.php' );


class FV_Antispam extends FV_Antispam_Plugin {

  var $aDefaultOptions;
  var $basename;
  var $protect;
  var $locale;  
  var $func__protect;
  
  public function __construct() {
    $this->basename = plugin_basename(__FILE__);
    $this->func__protect = 'a'.substr(md5(get_bloginfo('url')), 0, 10).'';
    $this->locale = get_locale();
    $this->aDefaultOptions = array( 'trash_banned' => true, 'protect_filledin' => true, 'spam_registrations' => true, 'cronjob_enable' => true, 'questions' => "What color is orange?,orange\nWhat color is a lemon?,yellow" );
    
    global $fv_antispam_ver;
    if( strcmp(get_option('fv_antispam_ver'),$fv_antispam_ver) !== 0 ) {        
      if( !$options = get_option( 'fv_antispam' ) ) {
        $options = array();
      }
      $options = array_merge( $this->aDefaultOptions, $options );
      update_option( 'fv_antispam', $options );
      update_option( 'fv_antispam_ver', $fv_antispam_ver );
    }
    
    if (is_admin()) {
      
      add_action( 'admin_menu', array( $this, 'admin__admin_menu' ) );

      if ($this->admin__is_current_page('home')) {
        add_action( 'admin_head', array( $this, 'admin__show_plugin_head' ) );
      } else if ($this->admin__is_current_page('index')) {
        if ($this->util__is_min_wp('2.7') && ( $this->func__get_plugin_option('dashboard_count') || 1>0 ) ) { //  always ON
          add_action( 'right_now_table_end', array( $this, 'admin__show_dashboard_count' ) );
        }
      } else if ($this->admin__is_current_page('plugins')) {
        
        if ($this->util__is_min_wp('2.8')) {
          add_filter( 'plugin_row_meta', array( $this, 'admin__init_row_meta' ), 10, 2 );
        } else {
          add_filter( 'plugin_action_links', array( $this, 'admin__init_action_links' ), 10, 2 );
        }
      }
            
      if( $this->func__get_plugin_option('comment_status_links') ) {
        $this->admin__comments_list_show_comments();
        add_action( 'comment_status_links', array( $this, 'admin__comment_status_links' ) );
      }      
         
      add_action( 'init', array( $this, 'admin__init' ) );

      add_action( 'deactivate_' .$this->basename, array( $this, 'cron__clear_scheduled_hook' ) );      
      add_action( 'init', array( $this, 'cron__init' ) );      
      
      add_filter( 'get_comment_text', array( $this, 'admin__get_comment_text' ) );
      add_filter( 'the_comments', array( $this, 'util__cache_comment_meta' ) );
      
      add_filter('plugin_action_links',array($this, 'fv_antispam_plugin_action_links'), 10, 2);
      
		  $this->readme_URL = 'http://plugins.trac.wordpress.org/browser/fv-antispam/trunk/readme.txt?format=txt';    
		  if( !has_action( 'in_plugin_update_message-fv-antispam/fv-antispam.php' ) ) {
	   		add_action( 'in_plugin_update_message-fv-antispam/fv-antispam.php', array( $this, 'plugin_update_message' ) );
	   	}
          
        
      add_filter( 'get_user_option_closedpostboxes_fv_antispam_settings', array( $this, 'util__closed_meta_boxes' ) );
            
      $this->pointer_boxes = array();

      if( 1<0 && !$this->func__get_plugin_option('notice_custom_questions') && $this->util__is_filled_in() ) {
        $this->pointer_boxes['fv_antispam_notice_custom_questions'] = array(
          'id' => '#wpadminbar',
          'heading' => __('FV Antispam Custom Questions', 'fv_flowplayer'),
          'content' => __('Antispam for Filled in forms has changed! Please test your forms! <br /><br />Visit Settings &raquo; <a href="'.admin_url('options-general.php?page=fv-antispam/fv-antispam.php').'">FV Antispam</a> to put in your own custom questions. Use "Skip custom question on forms" option to select which form should use hidden JavaScript antispam.', 'fv_flowplayer'),
          'position' => array( 'edge' => 'top', 'align' => 'center' ),
          'button1' => __('I understand', 'fv_flowplayer'),
          'button2' => __('I\'ll check this later', 'fv_flowplayer'),
        );
      }
      
      add_action( 'wp_ajax_fv_foliopress_ajax_pointers', array( $this, 'util__pointers_ajax' ) );

      parent::__construct();

    } else {
    
      add_action( 'comment_post', array( $this, 'func__trash_comment' ), 1000 ); //  all you need
      add_action( 'init', array( $this, 'func__precheck_comment_request' ), 0 );
      add_action( 'preprocess_comment', array( $this, 'func__verify_comment_request' ), 1 );
      
      //add_action( 'preprocess_comment', array( $this, 'func__check_math' ), 1 );        
      
      if( $GLOBALS['pagenow'] == 'wp-login.php' && $this->func__get_plugin_option('spam_registrations') && !$this->func__check_s2member() ) {
				add_action( 'login_head', array( $this, 'disp__login_form_js' ) );
				add_action( 'login_enqueue_scripts', array( $this, 'func__login_scripts') );
        add_action( 'login_form', array( $this, 'disp__login_form_notice' ) );
        add_action( 'register_form', array( $this, 'disp__login_form_notice' ) );
        add_action( 'init', array( $this, 'func__spam_registrations_check' ), 0 );
      }
  
  	  add_filter( 'the_content', array( $this, 'func__faq_tastic_spam_protection' ) );
	    add_action( 'init', array( $this, 'func__faq_tastic_spam_check' ) );       
	    
	    if( $this->func__get_plugin_option('protect_filledin') ) {
  		  add_filter( 'the_content', array( $this, 'disp__the_content' ), 999 );
  		  add_filter( 'the_excerpt', array( $this, 'disp__the_content' ), 999 );  		  
  		  add_filter( 'widget_text', array( $this, 'disp__the_content' ), 999 );  		  
  		}
  		
      if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset ($_POST['filled_in_form']) && $this->func__get_plugin_option('protect_filledin') ) {
        add_filter( 'plugins_loaded', array( $this, 'func__filled_in_check' ), 9 );
  		}  		
	    
      add_action( 'template_redirect', array( $this, 'func__replace_comment_field' ) );	    
      
      add_action( 'wp_print_footer_scripts', array( $this, 'disp__footer_scripts' ) );
      add_action( 'wp_head', array( $this, 'disp__head_css' ) );
      
    }
    
    add_action( 'fv_clean_trash_hourly', array( $this, 'cron__clean_comments_trash' ) );
    add_action( 'wp_insert_comment', array( $this, 'func__akismet_auto_check_update_meta' ), 11, 2 );
  }  
  
  function admin__admin_menu() {
    add_options_page( 'FV Antispam', 'FV Antispam', ($this->util__is_min_wp('2.8') ? 'manage_options' : 9), 'fv-antispam', array( $this, 'admin__show_admin_menu' ) );
  }  
  
  
  
  
  
  /**
    * Change the request to show only comments, it no type specified
    * 
    * @global array $_GET, $_SERVER
    *                              
    */   
  function admin__comments_list_show_comments() {
    if( stripos( $_SERVER['REQUEST_URI'], 'edit-comments.php' ) !== FALSE ) {
      if( !isset($_GET['comment_type'] ) ) {
        $_GET['comment_type'] = 'comment';
      }
    }
  }
  
  
  
  
  /**
    * Enhance Wordpress Admin Comments section
    * 
    * @param string Comment status links HTML
    * 
    * @return string Updated comment status links HTML
    */ 
  function admin__comment_status_links( $content ) {
    if( is_admin() ) {
      $post_id = isset($_REQUEST['p']) ? (int) $_REQUEST['p'] : 0;
      
      //  count total comments per status and type
      global $wpdb;
      if ( $post_id > 0 ) {
  		  $where = $wpdb->prepare( "WHERE comment_post_ID = %d", $post_id );
      }
      $count = $wpdb->get_results( "SELECT comment_approved, comment_type, COUNT( * ) AS num_comments FROM {$wpdb->comments} {$where} GROUP BY comment_approved, comment_type" );
  
      $count_types = array();
      foreach( $count AS $count_item ) {
        if( $count_item->comment_type == '' || $count_item->comment_type == 'comment' ) $count_types[$count_item->comment_approved]['comments'] = $count_item->num_comments;
        else $count_types[$count_item->comment_approved]['pings'] += $count_item->num_comments;
      }

      if( $this->util__is_min_wp( '3.1' ) ) {
        foreach( $content AS $content_key => $content_item ) {
        	if( $content_key == 'moderated' ) {
        		$content_key_select = '0';
        	} else {
        		$content_key_select = $content_key;
        	}
          $new_count = number_format( intval( $count_types[$content_key_select]['comments'] ) ).'</span>/'.number_format( intval( $count_types[$content_key_select]['pings'] ) );
          $content[$content_key] = preg_replace( '@(<span class="count">\(<span class="\S+-count">)[\d,]+</span>@', '$1&shy;'.$new_count, $content[$content_key] );
        }
      } else {
        foreach( array( 'moderated' => 0, 'spam' => 'spam', 'trash' => 'trash' ) AS $key => $value ) {
          $new_count = number_format( intval( $count_types[$value]['comments'] ) ).'/'.number_format( intval( $count_types[$value]['pings'] ) );
          $content = preg_replace( '@(<li class=\''.$key.'\'>.*?<span class="count">\(<span class="\S+-count">)[\d,]+@', '$1&shy;'.$new_count, $content );  
        }
      }
    }
    $content['help'] = '<abbr title="The numbers show counts of comments/pings for each group.">(?)</abbr>';
    return $content; 
  }
  
  
  
  
  /**
    * Shows a warning of any of the Filled in form fields matches the fake field. First looks up the forms and then parses posts and pages.
    * 
    * @global object WPDB.               
    * 
    */    
  function admin__filled_in_collision_check( $force = false ) {
    if( stripos( $_SERVER['REQUEST_URI'], 'fv-antispam.php' ) && !$force ) return;

    $problems = get_option( 'fv_antispam_filledin_conflict' );
    if( $problems === false ) {
      global $wpdb;

      $table_exists = $wpdb->get_var( "SHOW TABLES WHERE `Tables_in_{$wpdb->dbname}` = '{$wpdb->prefix}filled_in_forms'" );

      $forms =  false;

      if( $table_exists ) {
        $forms = $wpdb->get_col( "SELECT name FROM {$wpdb->prefix}filled_in_forms" );
      }
      
      if( $forms ) {
        $where = array();
        foreach( $forms AS $forms_item ) {
          $where[] = '( post_content LIKE \'%id="'.$forms_item.'"%\' AND post_status = \'publish\' )';
        }
        $where = implode( ' OR ', $where );
        $posts = $wpdb->get_results( "SELECT ID,post_title,post_content FROM $wpdb->posts WHERE {$where} ORDER BY post_date DESC" );
        if( $posts ) {
          $problems = array();
          foreach( $posts AS $posts_item ) {
            foreach( $forms AS $forms_item ) {
              $res = preg_match_all( '@<form.*?id=[\'"]'.$forms_item.'[\'"].*?name=[\'"]'.$this->func__get_filled_in_fake_field_name().'[\'"].*?</form>@si', $posts_item->post_content, $matches );
              if( $res ) {
                $problems[] = array( 'post_id' => $posts_item->ID, 'post_title' => $posts_item->post_title, 'form_name' => $forms_item );
              } 
            }
          }
          if( $problems ) {
            update_option( 'fv_antispam_filledin_conflict', $problems );
          } else {
            update_option( 'fv_antispam_filledin_conflict', array( ) );
          }
           
        }
      }
    }
    if( $problems ) {
      $problematic_message = '';
      foreach( $problems AS $key=>$problems_item ) {
        $problematic_message .= ' <a title="Post \''.$problems_item['post_title'].'\' containing form \''.$problems_item['form_name'].'\'" href="'.get_bloginfo( 'url' ).'?p='.$problems_item['post_id'].'">'.$problems_item['post_id'].'</a>';
      }
      return '<div class="error fade"><p>FV Antispam detected that following posts contain Filled in forms that conflict with FV Antispam fake field name:'.$problematic_message.'. Please set a different fake field name <a href="'.get_bloginfo( 'wpurl' ).'/wp-admin/options-general.php?page=fv-antispam/fv-antispam.php">here</a>. <a href="http://foliovision.com/seo-tools/wordpress/plugins/fv-antispam/filled-in-protection">Read more</a> about this issue.</p></div>'; 

    }
  }  
  
  
  
  
  function admin__get_comment_text( $comment_text ) {
  	global $comment;
  	$is_fv_antispam_math = get_comment_meta( $comment->comment_ID, 'fv-antispam', true );
  	if( $is_fv_antispam_math == 'Math question failed' ) {
  		$comment_text .= "\n\n<strong>FV Antispam - Math question failed</strong>";
  	}
  	return $comment_text;
  }
  
  
  
  
  function fv_antispam_plugin_action_links($links, $file) {
  	$plugin_file = basename(__FILE__);
  	if (basename($file) == $plugin_file) {
      $settings_link =  '<a href="'.site_url('wp-admin/options-general.php?page=fv-antispam').'"> '.__('Settings', 'antispam_bee').'</a>';
  		array_unshift($links, $settings_link);
  	}
  	return $links;
  }
  
  
  
  
  function admin__init_action_links($links, $file) {
    if ($this->basename == $file) {
      return array_merge( array( sprintf( '<a href="options-general.php?page=%s">%s</a>', $this->basename, __('Settings') ) ), $links );
    }
    return $links;
  }  
  
  
  
  function admin__init() {
    if( !isset($_GET['page']) || $_GET['page'] != "fv-antispam" ) {
      return;    
    }
    
    wp_enqueue_script('common');
    wp_enqueue_script('wp-lists');
    wp_enqueue_script('postbox');			    
    
    if ($this->util__is_min_wp('2.7')) {
      load_plugin_textdomain( 'antispam_bee', false, 'fv-antispam/lang' );
    } else {
      if (!defined('PLUGINDIR')) {
        define('PLUGINDIR', 'wp-content/plugins');
      }
      load_plugin_textdomain( 'antispam_bee', sprintf( '%s/fv-antispam/lang', PLUGINDIR ) );
    }
  }   
  
  
  
  
  
  function admin__init_row_meta($links, $file) {
    if ($this->basename == $file) {
      return array_merge( $links, array( sprintf( '<a href="options-general.php?page=%s">%s</a>', $this->basename,  __('Settings') ) ) );
    }
    return $links;
  }
  
  
  
  
  function admin__is_current_page($page) {
    switch($page) {
      case 'home':
        return (isset($_REQUEST['page']) && $_REQUEST['page'] == $this->basename);
      case 'index':
      case 'plugins':
        return ($GLOBALS['pagenow'] == sprintf('%s.php', $page));
    }
    return false;
  }  
  
  

  
  function admin__show_admin_menu() {
	
    $this->util__check_user_can();
    if (!empty($_POST)) {
    
    check_admin_referer('fvantispam');
 
    $options = array(
      //'flag_spam'=> (isset($_POST['antispam_bee_flag_spam']) ? (int)$_POST['antispam_bee_flag_spam'] : 0),
      'spam_registrations'=> (isset($_POST['spam_registrations']) ? (int)$_POST['spam_registrations'] : 0),
      'ignore_pings'=> (isset($_POST['antispam_bee_ignore_pings']) ? (int)$_POST['antispam_bee_ignore_pings'] : 0),
      //'ignore_filter'=> (isset($_POST['antispam_bee_ignore_filter']) ? (int)$_POST['antispam_bee_ignore_filter'] : 0),
      //'ignore_type'=> (isset($_POST['antispam_bee_ignore_type']) ? (int)$_POST['antispam_bee_ignore_type'] : 0),
      //'no_notice'=> (isset($_POST['antispam_bee_no_notice']) ? (int)$_POST['antispam_bee_no_notice'] : 0),
      //'email_notify'=> (isset($_POST['antispam_bee_email_notify']) ? (int)$_POST['antispam_bee_email_notify'] : 0),
      'cronjob_enable'=> (isset($_POST['cronjob_enable']) ? (int)$_POST['cronjob_enable'] : 0),
      'cronjob_writeout'=> (isset($_POST['cronjob_writeout']) ? (int)$_POST['cronjob_writeout'] : 0),
      //'cronjob_interval'=> (isset($_POST['antispam_bee_cronjob_interval']) ? (int)$_POST['antispam_bee_cronjob_interval'] : 0),
      //'dashboard_count'=> (isset($_POST['antispam_bee_dashboard_count']) ? (int)$_POST['antispam_bee_dashboard_count'] : 0),
      //'already_commented'=> (isset($_POST['antispam_bee_already_commented']) ? (int)$_POST['antispam_bee_already_commented'] : 0),
      //'always_allowed'=> (isset($_POST['antispam_bee_always_allowed']) ? (int)$_POST['antispam_bee_always_allowed'] : 0),
      ///
      'my_own_styling'=> (isset($_POST['my_own_styling']) ? (int)$_POST['my_own_styling'] : 0),
      'trash_banned'=> (isset($_POST['trash_banned']) ? (int)$_POST['trash_banned'] : 0),
      'protect_filledin'=> (isset($_POST['protect_filledin']) ? (int)$_POST['protect_filledin'] : 0),
      'protect_filledin_disable_notice'=> (isset($_POST['protect_filledin_disable_notice']) ? (int)$_POST['protect_filledin_disable_notice'] : 0),
      'protect_filledin_field'=> $_POST['protect_filledin_field'],
      'protect_filledin_notice'=> stripslashes($_POST['protect_filledin_notice']),    
      'disable_pingback_notify'=> (isset($_POST['disable_pingback_notify']) ? (int)$_POST['disable_pingback_notify'] : 0),
      'pingback_notify_email'=> $_POST['pingback_notify_email'],
      'comment_status_links'=> (isset($_POST['comment_status_links']) ? (int)$_POST['comment_status_links'] : 0),
      'filled_in_specials' => ( isset($_POST['filled_in_specials']) ? implode( ';', $_POST['filled_in_specials'] ) : '' ),
      'filled_in_custom' => ( isset($_POST['filled_in_custom']) ? implode( ';', $_POST['filled_in_custom'] ) : '' )
      ///
    );
    
    if( isset($_POST['question']) && isset($_POST['answer']) && count($_POST['question']) > 0 && count($_POST['answer']) > 0 ) {
      $aQuestions = array();
      foreach( $_POST['question'] AS $key => $sQuestion ) {
        if( strlen( trim($sQuestion) ) > 0 ) {
          $aQuestions[] = stripslashes(trim($sQuestion)).','.stripslashes(trim($_POST['answer'][$key]));
        }
      }
      if( $aQuestions ) {
        $options['questions'] = implode( "\n", $aQuestions );
      }
    }
    
    $this->func__set_plugin_options($options); ?>
    <div id="message" class="updated fade">
    <p>
    <strong>
    <?php _e('Settings saved.') ?>
    </strong>
    </p>
    </div>
    <?php } ?>
    <div class="wrap">
      <div style="position: absolute; right: 20px; margin-top: 5px">
          <a href="http://foliovision.com/wordpress/plugins/fv-antispam" target="_blank" title="Documentation"><img alt="visit foliovision" src="http://foliovision.com/shared/fv-logo.png" /></a>
      </div>

    <?php if ($this->util__is_min_wp('2.7')) { ?>
    <div id="icon-options-general" class="icon32"><br /></div>
    <?php }
    
    ?>
    <h2>FV Antispam</h2>
    
  <form id="fv_antispam_options" method="post" action="">  
		<div id="dashboard-widgets" class="metabox-holder columns-1">
			<div id='postbox-container-1' class='postbox-container' style="width: 100%;">    
				<?php
        
        add_meta_box( 'fv_antispam_basic', 'Basic', array($this,'disp__admin_basic'), 'fv_antispam_settings', 'normal' );
        add_meta_box( 'fv_antispam_advanced', 'Advanced', array($this,'disp__admin_advanced'), 'fv_antispam_settings', 'normal' );
        add_meta_box( 'fv_antispam_filled_in', 'Filled In Support', array($this,'disp__admin_filled_in'), 'fv_antispam_settings', 'normal' );
    
				do_meta_boxes('fv_antispam_settings', 'normal', false );
				wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
				wp_nonce_field( 'meta-box-order-nonce', 'meta-box-order-nonce', false );
				?>
        <?php wp_nonce_field('fvantispam') ?>
        <input type="submit" name="fv_antispam_submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</div>
		</div>  
  </form>
  
  <script type="text/javascript">
    //<![CDATA[
    jQuery(document).ready( function($) {
      // close postboxes that should be closed
      $('.if-js-closed').removeClass('if-js-closed').addClass('closed');
      // postboxes setup
      postboxes.add_postbox_toggles('fv_antispam_settings');
    });
    //]]>
  </script>  
    

    </div>
  <?php
  }  
  
   
  
  
  function admin__show_dashboard_count() {
    if( $this->util__is_min_wp( '3.0' ) ) {
      echo sprintf(
      '<tr>
      <td class="b b-spam" style="font-size:18px">%s</td>
      <td class="last t">%s</td>
      </tr>',
      $this->func__get_spam_count(),
      __('Blocked (<abbr title="Number of spam comments blocked by FV Antispam">?</a>)', 'antispam_bee')
      );
    } else {
      echo sprintf(
      '<tr>
      <td class="first b b-tags"></td>
      <td class="t tags"></td>
      <td class="b b-spam" style="font-size:18px">%s</td>
      <td class="last t">%s</td>
      </tr>',
      $this->func__get_spam_count(),
      __('Blocked (<abbr title="Number of spam comments blocked by FV Antispam">?</a>)', 'antispam_bee')
      );
    }
  }
  
  
  
  
  function admin__show_help_link($anchor) {
  }  
  
  
  
  
  function admin__show_plugin_head() {
    wp_enqueue_script('jquery'); ?>
    <style type="text/css">
    <?php if ($this->util__is_min_wp('2.7')) { ?>
    div.less {
      background: none;
    }
    <?php } ?>
    select {
      margin: 0 0 -3px;
    }
    input.small-text {
      margin: -5px 0;
    }
    td.shift {
      padding-left: 30px;
    }
    </style>
    <script type="text/javascript">
    jQuery(document).ready(
      function($) {
        function manage_options() {
          var id = 'antispam_bee_flag_spam';
          $('#' + id).parents('.form-table').find('input[id!="' + id + '"]').attr('disabled', !$('#' + id).attr('checked'));
        }
        $('#antispam_bee_flag_spam').click(manage_options);
        manage_options();
      }
    );
    </script>
  <?php
  }  
  
  
  
  
  public function cron__clean_comments_trash() {
    global $wpdb;
    
    if( !$this->func__get_plugin_option('cronjob_enable') ) {
      return;
    }
    
    $bWriteOut = ( $this->func__get_plugin_option('cronjob_writeout') ) ? true : false;
      
    if( 1 ) { //  todo: option
      $date = $wpdb->get_var( "SELECT comment_date_gmt FROM $wpdb->comments WHERE comment_approved = 'trash' ORDER BY comment_date_gmt DESC LIMIT 20000,1" );
    } else {     
      $date = date('Y-m-d H:i:s' ,mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));
    }
    $comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_id NOT IN ( select comment_id from $wpdb->commentmeta where meta_key = '_wp_trash_meta_time' ) AND comment_date_gmt < '$date' AND comment_approved = 'trash' ORDER BY comment_date_gmt ASC LIMIT 5000");
    if( count($comments) ) {      
      $comments_imploded = '';

      $sWriteOut = 'FV Antispam Clean-up started at '.date('r')."\n--------\n";
      foreach($comments as $comment) {
        if( $bWriteOut ) {
          $sWriteOut .= var_export($comment,true)."\n---\n\n";
        } 
        $comments_imploded .= $comment->comment_ID . ',';      
      }
      $comments_imploded = substr($comments_imploded, 0, -1);
      if( $bWriteOut ) {
        @file_put_contents( dirname(__FILE__).'/spamlog-'.preg_replace( '~(\d\d\d\d-\d\d-\d\d).*$~', '$1', $comment->comment_date_gmt ).'.log', $sWriteOut, FILE_APPEND ); //  todo: write check and directory change
      }
      $wpdb->query("DELETE FROM $wpdb->commentmeta WHERE comment_id IN ($comments_imploded)");
      $wpdb->query("DELETE FROM $wpdb->comments WHERE comment_ID IN ($comments_imploded)");
    }

    if( $bWriteOut ) {
      $sDate = date( 'Y-m-d', strtotime( "-1 month" ) );
      $sFile = dirname(__FILE__).'/spamlog-'.$sDate.'.log';
      if( file_exists( $sFile ) ) {
        !@unlink( $sFile );
      }
    }
  }      
  
  
  
  
  function cron__clear_scheduled_hook() {
    if (function_exists('wp_schedule_event')) {
      if (wp_next_scheduled('fv_clean_trash_hourly')) {
        wp_clear_scheduled_hook('fv_clean_trash_hourly');
      }
    }
  }  
  
  
  
  
  public function cron__init() {
    if( !wp_next_scheduled( 'fv_clean_trash_hourly' ) ){
      wp_schedule_event( time(), 'hourly', 'fv_clean_trash_hourly' );
    }
  }
  
  
  
  
  function disp__admin_advanced() {
    ?>
      <div class="inside">
        <table class="form-table">
          <tr>
            <td>
              <label for="my_own_styling">
              <input type="checkbox" name="my_own_styling" id="my_own_styling" value="1" <?php checked($this->func__get_plugin_option('my_own_styling'), 1) ?> />
              <?php _e('I\'ll put in my own styling', 'antispam_bee') ?> <span class="description">(Make sure that <code>form .message-textarea</code> is hidden in your CSS!)</span>
              </label>
            </td>
          </tr>
          <tr>
            <td>
              <label for="antispam_bee_ignore_pings">
              <input type="checkbox" name="antispam_bee_ignore_pings" id="antispam_bee_ignore_pings" value="1" <?php checked($this->func__get_plugin_option('ignore_pings'), 1) ?> />
              <?php _e('Do not check trackbacks / pingbacks', 'antispam_bee') ?>
              </label>
            </td>
          </tr>          
          <tr>
            <td>
            Enter alternative email address for pingback and trackback notifications<br />
              <label for="pingback_notify_email">
              <input type="text" class="regular-text" name="pingback_notify_email" id="pingback_notify_email" value="<?php if( function_exists( 'esc_attr' ) ) echo esc_attr( $this->func__get_plugin_option('pingback_notify_email') ); else echo ( $this->func__get_plugin_option('pingback_notify_email') ); ?>" />
              <span class="description"><?php _e('Leave empty if you want to use the default address from General Settings', 'antispam_bee') ?> <?php $this->admin__show_help_link('disable_pingback_notify') ?></span>
              </label>
            </td>
          </tr>
          <tr>
            <td>
              <label for="disable_pingback_notify">
              <input type="checkbox" name="disable_pingback_notify" id="disable_pingback_notify" value="1" <?php checked($this->func__get_plugin_option('disable_pingback_notify'), 1) ?> />
              <?php _e('Disable notifications for pingbacks and trackbacks', 'antispam_bee') ?> 
              </label>
            </td>
          </tr>
          <tr>
            <td>
              <label for="comment_status_links">
              <input type="checkbox" name="comment_status_links" id="comment_status_links" value="1" <?php checked($this->func__get_plugin_option('comment_status_links'), 1) ?> />
              <?php _e('Enhance Wordpress Admin Comments section', 'antispam_bee') ?> <span class="description">Hides trackbacks and shows separate counts for comments and trackbacks</span>
              </label>
            </td>
          </tr>              
          <tr>
            <td>
              <label for="cronjob_writeout">
              <input type="checkbox" name="cronjob_writeout" id="cronjob_writeout" value="1" <?php checked($this->func__get_plugin_option('cronjob_writeout'), 1) ?> />
              Write out removed comments into files (<abbr title="For debug and troubleshooting">?</abbr>)
              </label>
            </td>
          </tr>                
        </table>
      </div>
    <?php
  }
  
  
  
  
  function disp__admin_basic() {
    ?>
      <div class="inside">
        <table class="form-table">

          <?php if ($this->util__is_min_wp('2.7')) { ?>
            <!--<tr>
              <td>
                <label for="antispam_bee_dashboard_count">
                <input type="checkbox" name="antispam_bee_dashboard_count" id="antispam_bee_dashboard_count" value="1" <?php checked($this->func__get_plugin_option('dashboard_count'), 1) ?> />
                <?php _e('Display blocked comments count on the dashboard', 'antispam_bee') ?> <?php $this->admin__show_help_link('dashboard_count') ?>
                </label>
              </td>
            </tr>-->
          <?php } ?>

          <!--<tr>
            <td>
              <label for="antispam_bee_already_commented">
              <input type="checkbox" name="antispam_bee_already_commented" id="antispam_bee_already_commented" value="1" <?php checked($this->func__get_plugin_option('already_commented'), 1) ?> />
              <?php _e('Do not check for spam if the author has already commented and approved', 'antispam_bee') ?> <?php $this->admin__show_help_link('already_commented') ?>
              </label>
            </td>
          </tr>-->
          <!--<tr>
            <td>
              <label for="antispam_bee_always_allowed">
              <input type="checkbox" name="antispam_bee_always_allowed" id="antispam_bee_always_allowed" value="1" <?php checked($this->func__get_plugin_option('always_allowed'), 1) ?> />
              <?php _e('Comments are also used outside of posts and pages', 'antispam_bee') ?> <?php $this->admin__show_help_link('always_allowed') ?>
              </label>
            </td>
          </tr>-->
          <tr>
            <td>
              <label for="trash_banned">
              <input type="checkbox" name="trash_banned" id="trash_banned" value="1" <?php checked($this->func__get_plugin_option('trash_banned'), 1) ?> />
              <?php _e('Trash banned (blacklisted) comments, don\'t just mark them as spam', 'antispam_bee') ?>
              </label>
            </td>
          </tr>
          <tr>
            <td>
              <label for="spam_registrations">
              <input <?php if( $this->func__check_s2member() ) : ?>onclick="return false"<?php endif; ?> type="checkbox" name="spam_registrations" id="spam_registrations" value="1" <?php checked($this->func__get_plugin_option('spam_registrations'), 1) ?> />
              <?php _e('Protect the registration form', 'antispam_bee') ?> <?php if( $this->func__check_s2member() ) : ?>(<abbr title="Not available for s2Member!">?</abbr>)<?php endif; ?>
              </label>
            </td>
          </tr>        
          <tr>
            <td>
              <label for="cronjob_enable">
              <input type="checkbox" name="cronjob_enable" id="cronjob_enable" value="1" <?php checked($this->func__get_plugin_option('cronjob_enable'), 1) ?> />
              Keep maximum of 20,000 trash spam comments (<abbr title="This will keep comments trashed by hand - having _wp_trash_meta_time meta">?</abbr>)
              </label>
            </td>
          </tr>
         
        </table>
      </div>
    <?php
  }  
  
  
  
  
  function disp__admin_filled_in() {
    
    $sCollisionCheck = '';
    $problems = get_option( 'fv_antispam_filledin_conflict' );
    if( $problems ) {
      $sCollisionCheck = $this->admin__filled_in_collision_check( true );
    }
    else if( $problems === false ) {
      $sCollisionCheck= $this->admin__filled_in_collision_check( true );
    } else {
      $sCollisionCheck = " (No conflicts with Filled In detected with your current field name)!";
    }
      
    if( $this->util__is_filled_in() ) :                    
    ?>
    <div class="inside">
                  <table class="form-table">
                    <tr>
                      <td>
                        <h3>Basic antispam</h3>
                        <?php if( function_exists('akismet_get_key') && akismet_get_key() ) : ?>                        
                          <br /><span class="description"><?php _e('Akismet detected - FV Antispam will use it to protect the forms.', 'antispam_bee') ?></span>
                        <?php else : ?>
                          <br /><span class="description"><?php _e('We recommend that you install Akismet for increased protection.', 'antispam_bee') ?></span>
                        <?php endif; ?>         
                       
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <label for="protect_filledin">
                        <input type="checkbox" name="protect_filledin" id="protect_filledin" value="1" <?php checked($this->func__get_plugin_option('protect_filledin'), 1) ?> />
                        <?php _e('Protect Filled in forms', 'antispam_bee') ?>                  
                        </label>
                      </td>
                    </tr>
                    <tr>
                      <td>
                      Enter fake field name<br />
                        <label for="protect_filledin_field">
                        <input type="text" class="regular-text" name="protect_filledin_field" id="protect_filledin_field" value="<?php if( function_exists( 'esc_attr' ) ) echo esc_attr( $this->func__get_plugin_option('protect_filledin_field') ); else echo ( $this->func__get_plugin_option('protect_filledin_field') ); ?>" />
                        <span class="description"><?php _e('Leave empty if you want to use the default'.$sCollisionCheck, 'antispam_bee') ?> <?php $this->admin__show_help_link('protect_filledin_field') ?></span>
                        </label>
                      </td>
                    </tr>
                    <tr>
                      <td>
                      Enter spam message<br />
                        <label for="protect_filledin_notice">
                        <input type="text" class="regular-text" name="protect_filledin_notice" id="protect_filledin_notice" value="<?php if( function_exists( 'esc_attr' ) ) echo esc_attr( $this->func__get_plugin_option('protect_filledin_notice') ); else echo ( $this->func__get_plugin_option('protect_filledin_notice') ); ?>" />
                        <span class="description"><?php _e('This is a failsafe if a real person is caught as spam', 'antispam_bee') ?> <?php $this->admin__show_help_link('protect_filledin_notice') ?></span>
                        </label>
                      </td>
                    </tr>                          
                    <tr>
                      <td>
                        <label for="protect_filledin_disable_notice">
                        <input type="checkbox" name="protect_filledin_disable_notice" id="protect_filledin_disable_notice" value="1" <?php checked($this->func__get_plugin_option('protect_filledin_disable_notice'), 1) ?> />
                        <?php _e('Disable protection notice', 'antispam_bee') ?> <span class="description"><?php _e('(Logged in administrators normally see a notice that FV Antispam is protecting a Filled in form)', 'antispam_bee') ?></span>
                        </label>
                      </td>
                    </tr>                     
                    
                    <tr><td><h3>Custom questions</h3><br ><span class="description"><?php _e('If some forms still have problem with spam, customize and enable the custom questions.', 'antispam_bee') ?></span></td></tr>
                    <tr>
                      <td>
                      Use custom question on forms:                        
<?php

global $wpdb;
$sTableName = $wpdb->prefix.'filled_in_forms';
if( $wpdb->get_var("SHOW TABLES LIKE '$sTableName'") == $sTableName && $aForms = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}filled_in_forms ORDER BY name ASC" ) ) {		

	$sFilledInJs = $this->func__get_plugin_option('filled_in_custom');
	$aSelectedForms = array();
	if( $sFilledInJs && strlen($sFilledInJs) > 0 && $aFilledInJs = explode( ';', $sFilledInJs ) ) {
		if( count($aFilledInJs) > 0 ) {
			foreach( $aFilledInJs AS $sFilledInJs ) {
				$aFilledInJs = explode( ',', $sFilledInJs );
				if( $aFilledInJs && count( $aFilledInJs ) ) {
					foreach( $aForms AS $key => $aForm ) {
						if( $aForm->id == $aFilledInJs[1] ) {
							$aForms[$key]->selected = true;
							$aSelectedForms[] = '<code>'.$aForm->name.'</code>';
						}
					}
				}
			}		
		}
	}
	
	$iSelectedForms = count( $aSelectedForms );
	$sSelectedForms = implode( ' ', $aSelectedForms );
  
	$sFilledInSpecials = $this->func__get_plugin_option('filled_in_specials');
	$aSelectedFormsSpecials = array();
	if( $sFilledInSpecials && strlen($sFilledInSpecials) > 0 && $aFilledInSpecials = explode( ';', $sFilledInSpecials ) ) {
		if( count($aFilledInSpecials) > 0 ) {
			foreach( $aFilledInSpecials AS $sFilledInSpecial ) {
				$aFilledInSpecial = explode( ',', $sFilledInSpecial );
				if( $aFilledInSpecial && count( $aFilledInSpecial ) ) {
					foreach( $aForms AS $key => $aForm ) {
						if( $aForm->id == $aFilledInSpecial[1] ) {
							$aForms[$key]->selectedSpecial = true;
							$aSelectedFormsSpecials[] = $aForm->name;
						}
					}
				}
			}		
		}
	}  
 
	if( $iSelectedForms > 0 ) {          
		echo $sSelectedForms;
	} else {
    echo '(none selected)';
  }
	echo ' (<a href="#" onclick="jQuery(\'.fv-antispam-list-forms-2\').toggle(); return false">toggle</a>) ';	
	?>
	<table class="fv-antispam-list-forms-2 wp-list-table widefat fixed posts" style="display: none; margin-top: 10px">
    <thead>
      <tr>
        <th class="manage-column column-title sortable"><a>Form</a></th><th class="manage-column column-title sortable"><a>Use custom question</a></th><th class="manage-column column-title sortable"><a>Show in popup</a></th>
      </tr>
    </thead>
    <tbody id="the-list">
	<?php
  $iCount = 0;
  foreach( $aForms AS $key => $aForm ) {
    $class = ($iCount % 2 == 0 ) ? ' class="alt"' : '';
    $iCount++;
    echo '<tr'.$class.'>';
		echo '<td><label for="filled_in_custom-'.$key.'">'.$aForm->name.'</label> <small>(<a style="text-decoration: none; " target="_blank" href="'.site_url().'/wp-admin/tools.php?page=filled_in.php&edit='.$aForm->id.'">edit</a>)</small></td>';
		echo '<td><input id="filled_in_custom-'.$key.'" name="filled_in_custom[]" value="'.$aForm->name.','.$aForm->id.'" type="checkbox" '.( isset($aForm->selected) && $aForm->selected ? ' checked="checked" ' : '' ).'/></td>';
    echo '<td><input id="filled_in_specials-'.$key.'" name="filled_in_specials[]" value="'.$aForm->name.','.$aForm->id.'" type="checkbox" '.( isset($aForm->selectedSpecial) && $aForm->selectedSpecial ? ' checked="checked" ' : '' ).'/></td>';
    echo '</tr>'."\n";
	}
	echo '</table>'."\n";
} else {
	echo 'Strange, no Filled in database tables found!';
} ?>
    </tbody>
  </table>
                      </td>
                    </tr>

                    
                    <tr>
                      <td>
                      	Custom questions <a id="fv-antispam-question-add"><small>Add more</small></a><br />
                        <div id="fv-antispam-questions">
                          <?php
                          $aQuestions = explode( "\n", $this->func__get_plugin_option('questions') );
                          
                          foreach( $aQuestions AS $aQuestion ) {
                            list( $sAnswer, $sQuestion ) = explode( ",", strrev($aQuestion), 2 );
                            $sQuestion = strrev($sQuestion);
                            $sAnswer = strrev($sAnswer);
                            ?>
                            <p><input type="text" value="<?php echo esc_attr($sQuestion); ?>" name="question[]" class="regular-text" /> <input type="text" value="<?php echo esc_attr($sAnswer); ?>" name="answer[]" /> <input type="button" value="Remove" class="button fv-antispam-question-remove" /></p>
                            <?php
                          }                 
                          ?>
                          <p class="template"><input type="text" value="" name="question[]" class="regular-text" /> <input type="text" value="" name="answer[]" /> <input type="button" value="Remove" class="button fv-antispam-question-remove" /></p>
                        </div>
                        <style>
                          #fv-antispam-questions .button { display: none; }
                          #fv-antispam-questions .template { display: none; }
                        </style>
                        <script>
                          function fv_antispam_settings_js() {
                            jQuery('#fv-antispam-questions p').hover(
                              function() {
                                jQuery(this).find('.button').show();
                              }, function() {
                                jQuery(this).find('.button').hide();
                              }
                            );
                            jQuery('.fv-antispam-question-remove').click( function() {
                              jQuery(this).parent().remove();
                            } );
                          }
                          jQuery(document).ready( function() {
                            fv_antispam_settings_js();
                            jQuery('#fv-antispam-question-add').click( function() {
                              jQuery('#fv-antispam-questions').append( '<p>'+jQuery('#fv-antispam-questions .template').html()+'</p>' );
                              fv_antispam_settings_js();
                            } );
                            
                          } );
                        </script>
                      </td>
                    </tr>
                    
                  </table>
                </div>
    <?php else : ?>
      <p>Filled in not installed.</p>
    <?php endif;
  }
  
  
  
  
  function disp__faq_tastic_js() {
  	global $post;
  	$protect = FV_Antispam::func__protect($post->ID);
    ?>
<script type="text/javascript">    
  jQuery(document).ready(function() {
    jQuery("textarea[name='faq_question']").hide();    
    var hash = '<?php echo $protect; ?>';        
    jQuery("textarea[name='faq_question']").parent("td").append("<textarea name='" + hash + "' value='' style='width: 95%' rows='5' cols='40'></textarea>");      
  })      
</script>
    <?php
  }
  
  
  
  
  function disp__footer_scripts() {	//	some templates (Elegant Themes Chameleon) mess up the input fields, so we also reset the #comment textarea here
    global $FV_Antispam_bMathJS;
    if( $FV_Antispam_bMathJS ) :
  	?>
<script type="text/javascript">
function fvaq() {
	if( document.cookie.indexOf("fvaq_nojs") == -1 ) {
		if( !document.getElementsByClassName ) {
			document.getElementsByClassName=function(search){var d=document,elements,pattern,i,results=[];if(d.querySelectorAll){return d.querySelectorAll("."+search)}if(d.evaluate){pattern=".//*[contains(concat(' ', @class, ' '), ' "+search+" ')]";elements=d.evaluate(pattern,d,null,0,null);while(i=elements.iterateNext()){results.push(i)}}else{elements=d.getElementsByTagName("*");pattern=new RegExp("(^|\s)"+search+"(\s|$)");for(i=0;i<elements.length;i++){if(pattern.test(elements[i].className)){results.push(elements[i])}}}return results}
		}
		var fvaa = document.getElementsByClassName('fvaa');
		var fvaq = document.getElementsByClassName('fvaq');
		var fval = document.getElementsByClassName('fval');
		
		for (var i = 0; i < fvaq.length; ++i) { 		
			if( fvaa[i] !== undefined && fval[i] !== undefined ) {
				fvaq[i].value = fvaa[i].value; fvaq[i].style.display = 'none'; fval[i].style.display = 'none';		
			}
		}
	}
	if( document.getElementById('comment') != null ) { document.getElementById('comment').value = ''; }
}
fvaq(); window.onload = function(e) { fvaq(); setTimeout('fvaq', 250); };
</script>
<?php
    endif;  //  $FV_Antispam_bMathJS

$bShowScripts = false;
$sScript = '';
$sFilledInSpecials = $this->func__get_plugin_option('filled_in_specials');
if( $sFilledInSpecials && strlen($sFilledInSpecials) > 0 && $aFilledInSpecials = explode( ';', $sFilledInSpecials ) ) {
	if( count($aFilledInSpecials) > 0 ) {
		foreach( $aFilledInSpecials AS $sFilledInSpecial ) {
			$aFilledInSpecial = explode( ',', $sFilledInSpecial );
			if( $aFilledInSpecial && count( $aFilledInSpecial ) == 2 ) {
				$sScript .= "fvacq('$aFilledInSpecial[0]','$aFilledInSpecial[1]'); ";
				$bShowScripts = true;
			}
		}
	}
}

if( $bShowScripts ) : ?>
<script type="text/javascript">
function fvacq( form_name, form_id ) {
	if( (form = document.getElementById(form_name)) != null ) {
		form.onsubmit = function() {
			if (document.getElementById("fvacq"+form_id).style.display == "none") {
				document.getElementById("fvacq"+form_id).style.display = 'block';
				return false;
			}
			return true;
		} 
	}
}
<?php echo $sScript; ?>
</script>
<?php endif; ?>

  	<?php
  }
  
  
  
  
  function disp__head_css() {
    if( !FV_Antispam::func__get_plugin_option('my_own_styling') ) {
      echo "<style>form .message-textarea {display: none !important; }</style>\n";
    }
  }
  
  
  
  
  function disp__login_form_notice() {
    $html = ob_get_clean();
    if (current_user_can('manage_options')) {      
      $protection_notice = '<p class="fv_antispam_filled_in_msg message"><small>(Note for WP Admins: Form Protected by <a href="' . site_url() . '/wp-admin/options-general.php?page=fv-antispam/fv-antispam.php">FV Antispam</a>)</small></p>';
      $html = preg_replace("~(<\/h1>)\s*?(.*?)\s*?(<form)~", '$1$2' . $protection_notice . '$3', $html);
    }
    
    if( isset($_GET['action']) && $_GET['action'] == 'register' ) {
    	$protect = $this->func__ip_protect();
    	$html .= $this->disp__math_question($protect);
    }
    
    echo $html;
  }   
  
  
  
  
  function disp__login_form_js() {
    $value = !empty($_POST[$this->func__ip_protect()]) ? $_POST[$this->func__ip_protect()] : '';
    ?>
<script type="text/javascript">    
  jQuery(document).ready(function() {
		jQuery( '#user_email').after(
			jQuery("#user_email").clone().attr('id', '<?php echo $this->func__ip_protect(); ?>').attr('name', '<?php echo $this->func__ip_protect(); ?>').attr('value', '<?php echo $value; ?>')
		);
    jQuery("#user_email").hide();    
  })      
</script>      
    <?php
  }  



  /*
   * Puts in custom question
   *
   * @param string $protect Used for field id and name
   * @param int $form_id Form ID
   * @param bool $bFilledInSpecial Wether the form uses JavaScript to show the custom question - for "small" or single line forms
   * $param string $sFormHTML Optional full form HTML. If specified, the function alters the form HTML directly.
   * @return string $html The custom question fields HTML or form HTML with custom question fields in it
   */
  function disp__custom_question($protect, $form_id, $bFilledInSpecial = false, $sFormHTML = false ) {

  	if( $sQuestions = $this->func__get_plugin_option('questions') ) {
      global $FV_Antispam_iFilledInCount;
      
  		$aQuestions = explode( "\n", $sQuestions);

  		if( !$aQuestions ) {
  			return false;
  		}
  		
  		$iRandom = rand(0,count($aQuestions)-1);
  		$sQuestion = $aQuestions[$iRandom];
  		list( $sAnswer, $sQuestion ) = explode( ",", strrev($sQuestion), 2 );
      $sQuestion = strrev( $sQuestion );

      if( $sFormHTML && !$bFilledInSpecial ) {
        $html = str_replace( '[fvquestion]', "<label class='fvacl' for='c_".$protect.$FV_Antispam_iFilledInCount."'>".__($sQuestion)."</label>", $sFormHTML );
        $html = str_replace( '[fvanswer]', "<input class='fvacq' type='text' id='c_".$protect.$FV_Antispam_iFilledInCount."' name='c_".$protect."' size='5' /><input class='fvaca' type='hidden' name='ca_".$protect."' value='".$iRandom."' />", $html );
      } else {
        $br = false;
        if( $bFilledInSpecial ) {
          $br = '<br />';
        }           
        $html = "\n<span><label class='fvacl' for='c_".$protect.$FV_Antispam_iFilledInCount."'>".$br.__($sQuestion)."</label></span> <input class='fvacq' type='text' id='c_".$protect.$FV_Antispam_iFilledInCount."' name='c_".$protect."' size='5' /><input class='fvaca' type='hidden' name='ca_".$protect."' value='".$iRandom."' />\n";    	
              
        if( $bFilledInSpecial ) {
          $html = "<div id='fvacq$form_id' class='fvacq_wrap' style='display: none'>$html</div>\n";	//	todo: inline style - not so good!
        }
      }
  	}		

  	return $html;
  }
  
  
  
  
  function disp__math_question($protect) {
    global $FV_Antispam_iFilledInCount, $FV_Antispam_bMathJS;
    $FV_Antispam_bMathJS = true;
    
		$iA = rand(1,5);	
		$iB = rand(1,5);
			
		$html = "\n<span><label class='fval' for='m_".$protect.$FV_Antispam_iFilledInCount."'><br />".__('How much is')." $iA + $iB?</label></span> <input class='fvaq' type='text' id='m_".$protect.$FV_Antispam_iFilledInCount."' name='m_".$protect."' size='2' /><input class='fvaa' type='hidden' name='ma_".$protect."' value='".($iA+$iB)."' />\n";  
  	return $html;
  }
  
  
  
  
  /**
    * Add protection for Filled in forms
    * 
    * @param string $content Post content.             
    * 
    * @return string Content with fake field added.
    */    
  function disp__the_content( $content ) {
    global $FV_Antispam_iFilledInCount;
    
    if( stripos( $content, '<input type="hidden" name="filled_in_form" ' ) === FALSE ) {  //  first check if there's any filled in form
      return $content;
    }
    preg_match_all( '~<form[\s\S]*?</form>~', $content, $forms );
    
    foreach( $forms[0] AS $form ) {
      $form_protected = $form;
      
      $FV_Antispam_iFilledInCount++;
      if( current_user_can('manage_options') && !$this->func__get_plugin_option('protect_filledin_disable_notice') ) {
        $protection_notice = '<p><small>(Note for WP Admins: Form Protected by <a href="http://foliovision.com/seo-tools/wordpress/plugins/fv-antispam/filled-in-protection">FV Antispam</a>, test logged out!)</small></p>';
      }      
      
      global $post;
      $protect = FV_Antispam::func__protect($post->ID);

			$form_id = false;
			preg_match( '~<input type="hidden" name="filled_in_form" value="(\d+)"/>~', $form, $form_id );
			if( intval($form_id) > 0 ) {
				$form_id = $form_id[1];
			}
      
      $sAntispamFields = false;
      if( !is_user_logged_in() || 1>0 ) { //  if we only do the change when logged out, the HTML with [fvquestion] and [fvanswer] stays!
        global $post; //  global variable which you can set to true to use the Math JS protection
        
        $bFilledInCustom = false;
        $sFilledInJs = $this->func__get_plugin_option('filled_in_custom');
        if( $sFilledInJs && strlen($sFilledInJs) > 0 && $aFilledInJs = explode( ';', $sFilledInJs ) ) {
          if( count($aFilledInJs) > 0 ) {
            foreach( $aFilledInJs AS $sFilledInJs ) {
              $aFilledInJs = explode( ',', $sFilledInJs );
              if( $aFilledInJs && count( $aFilledInJs ) == 2 && $aFilledInJs[1] == $form_id ) {
                $bFilledInCustom = true;
              }
            }
          }
        }
        
        $bFilledInSpecial = false;
        $sFilledInSpecials = $this->func__get_plugin_option('filled_in_specials');
        if( $sFilledInSpecials && strlen($sFilledInSpecials) > 0 && $aFilledInSpecials = explode( ';', $sFilledInSpecials ) ) {
          if( count($aFilledInSpecials) > 0 ) {
            foreach( $aFilledInSpecials AS $sFilledInSpecial ) {
              $aFilledInSpecial = explode( ',', $sFilledInSpecial );
              if( $aFilledInSpecial && count( $aFilledInSpecial ) == 2 && $aFilledInSpecial[1] == $form_id ) {
                $bFilledInSpecial = true;
              }
            }
          }
        }           
                

        if( $bFilledInCustom ) {  //  yes, we want custom questions
          if( !$bFilledInSpecial && stripos($form, '[fvquestion]') !== false && stripos($form, '[fvanswer]') !== false ) {  //  the form is not marked to use javascript popup and it's using shortcodes for question and answer
            $form_protected = $this->disp__custom_question($protect, $form_id, $bFilledInSpecial, $form_protected);          
          } else {  //  
            $form_protected = str_replace( array('[fvquestion]','[fvanswer]'), '', $form_protected );
            $sAntispamFields = $this->disp__custom_question($protect, $form_id, $bFilledInSpecial);          
          }          
        } else {
          $sAntispamFields = $this->disp__math_question($protect);
          $form_protected = str_replace( array('[fvquestion]','[fvanswer]'), '', $form_protected );          
        }
      }
    
      $sCount = ( $FV_Antispam_iFilledInCount == 1 && strcasecmp($this->func__get_filled_in_fake_field_name(),'comment') !== 0 ) ? '' : '-'.$FV_Antispam_iFilledInCount;     
           
      $form_protected = preg_replace( '~(<form[\s\S]*?)(<input type="hidden" name="filled_in_form" value="\d+"/>)([\s\S]*?)(<[^<]*?[\'"]submit[\'"])([\s\S]*?</form>)~', $protection_notice.'$1$2$3<textarea id="'.$this->func__get_filled_in_fake_field_name().$sCount.'" class="message-textarea" name="'.$this->func__get_filled_in_fake_field_name().'" rows="12" cols="40"></textarea>'."\n".$sAntispamFields.'$4$5', $form_protected );
      $content = str_replace( $form, $form_protected, $content );
    }
    
    if( is_user_logged_in() && 1<0 ) {
      if( stripos($form, '[fvquestion]') !== false && stripos($form, '[fvanswer]') !== false ) {
        $content = str_replace('[fvquestion]','',$content);
        $content = str_replace('[fvanswer]','',$content);
      }
    }
    
    return $content;
  }  
  
  
  
  
  function func__akismet_auto_check_update_meta( $id, $comment ) {
		global $akismet_last_comment;
		
		if( isset($akismet_last_comment['akismet_result']) && $akismet_last_comment['akismet_result'] == 'true' && ($comment->comment_type == 'pingback' || $comment->comment_type == 'trackback') ) {
			update_comment_meta( $comment->comment_ID, 'fv_antispam_debug', 'is Akismet spam, removing' );
			wp_trash_comment( $comment->comment_ID );		
		}
	}
	
	
	
	
	function func__check_math( $aComment ) {    
    
		if( is_user_logged_in() ) {
			return $aComment;
		}
	
		global $post;
		$protect = FV_Antispam::func__protect($post->ID);
	
		if( !$this->util__is_wp_touch() ) {
			if(
				!isset($_POST['m_'.$protect]) ||
				( isset($_POST['m_'.$protect]) && strlen($_POST['m_'.$protect]) == 0 )	//	some templates (Elegant Themes Chameleon) mess up the input fields, so perhaps there won't be a integer but a string, lets accept that
			) {
				setcookie( 'fvaq_nojs', 1, time()+120 );
				$this->func__log_comment( $aComment );
				wp_die( __('<strong>ERROR</strong>: please go back and fill in the security question.') );
			} else if( $_POST['m_'.$protect] != $_POST['ma_'.$protect] ) {
				setcookie( 'fvaq_nojs', 1, time()+120 );
				$this->func__log_comment( $aComment );				
				wp_die( __('<strong>ERROR</strong>: please go back and put in correct answer for the security question.') );
			}
		}
		
		setcookie( 'fvaq_nojs', NULL, -1 ); 
		
		return $aComment;
	}
	
  
  
  
  function func__check_s2member() {
    $bs2member = false;
    $aPlugins = get_option('active_plugins');
    foreach( $aPlugins AS $sPlugin ) {
      if( stripos($sPlugin,'s2member.php') !== false ) {
        $bs2member = true;
      }
    }
    return $bs2member;
  }
  
	
	
	
  function func__faq_tastic_spam_protection($content) {
  
  	if( strpos( $content, '<textarea name="faq_question"' ) !== false ) {      
  		global $post, $FV_Antispam_iFilledInCount;
      $FV_Antispam_iFilledInCount++;
  		$protect = FV_Antispam::func__protect($post->ID);
  		$content = preg_replace( '~(<form[^>]*?add-question.php[\s\S]*?)(<input[^>]*?type="submit)~', '$1'.$this->disp__math_question($protect).'$2', $content );
  		
  		add_action( 'wp_footer', array( $this, 'disp__faq_tastic_js' ) );
  	}

    return $content;
  }
  
  

  
  function func__faq_tastic_spam_check() {
    if (strpos($_SERVER['SCRIPT_NAME'], 'add-question.php') !== false) {      
    	global $post;
    	$protect = FV_Antispam::func__protect($post->ID);
    	
    	if( isset($_POST['m_'.$protect]) && isset($_POST['ma_'.$protect]) && $_POST['m_'.$protect] == $_POST['ma_'.$protect] ) {	//	todo
    		if( trim($_POST['faq_question'])  == '' ) {
    			$_POST['faq_question'] = $_POST[$protect];  
    		}
    	} else if ($_POST['faq_question'] != "" ) {
				$fv_antispam_faqtastic = get_option('fv_antispam_faqtastic');
				$fv_antispam_faqtastic = ( $fv_antispam_faqtastic ) ? $fv_antispam_faqtastic : array();      
				$fv_antispam_faqtastic[] = array( 'date' => date('r'), 'post' => $_POST );
				update_option( 'fv_antispam_faqtastic', $fv_antispam_faqtastic );
        wp_redirect($_POST['source']);
        exit();
      } else {
      	$_POST['faq_question'] = $_POST[$protect];      
      }
    }
  }	
	
	
	
	
  /**
    * Check if the fake field has been filled in POST and take action
    * 
    * @global array $_POST              
    * 
    */    
  function func__filled_in_check() {
  	$iForm_id = ( isset($_POST['filled_in_form']) ) ? intval($_POST['filled_in_form']) : -1;
    
    if( is_user_logged_in() ) {
      return;      
    }
  	
  	global $post;
  	$protect = FV_Antispam::func__protect($post->ID);
  	
  	$bCustomQuestion = 0;
		if( isset($_POST['ca_'.$protect]) && isset($_POST['c_'.$protect]) ) {	//	custom question		
			if( $sQuestions = $this->func__get_plugin_option('questions') ) {
  			$aQuestions = explode( "\n", $sQuestions);  			
  		}
      
      if( count($aQuestions) ) {   		
        $iAnswer = intval($_POST['ca_'.$protect]);
        
        $sQuestion = $aQuestions[$iAnswer];
        list( $sAnswer, $sQuestion ) = explode( ",", strrev($sQuestion), 2 );
        $sAnswer = strrev($sAnswer);

        $iLevenshtein = levenshtein( trim($sAnswer), trim($_POST['c_'.$protect]) );
        if( $iAnswer > count( $aQuestions ) ) {
          $bCustomQuestion = -1;  		  		
        } else if( $iLevenshtein >= 0 && $iLevenshtein <= floor(strlen(trim($sAnswer))/2) ) { //  ...so if there is 1 char answer, levenshtein will be merciless!
          $bCustomQuestion = 1;  //  this overrides any other check
        } else {
          $bCustomQuestion = -1;
        }
      }
  	}

    if( $bCustomQuestion == -1 ) {
      $this->func__set_filled_in_fail($iForm_id);
      return;
    }
    
    $bSkipJSCheck = false;  //  skip JS check on Ajax forms
    if( $iForm_id > 0 ) {
      global $wpdb;
      $aOptions = $wpdb->get_var( "SELECT options FROM {$wpdb->prefix}filled_in_forms WHERE id = '".esc_sql($iForm_id)."' LIMIT 1" );
      $aOptions = unserialize($aOptions);
      if( isset($aOptions['ajax']) && $aOptions['ajax'] == "true" ) {
        $bSkipJSCheck = true;
      }
    }

    if(
    	isset( $_POST[$this->func__get_filled_in_fake_field_name()] ) && strlen( $_POST[$this->func__get_filled_in_fake_field_name()] )
			&& ( isset($_POST['m_'.$protect]) && isset($_POST['ma_'.$protect]) && $_POST['m_'.$protect] != $_POST['ma_'.$protect] && $bCustomQuestion == 0 )
    ) {	//	fake field filled in, math question present and bad answer
			setcookie( 'fvaq_nojs', 1, time()+120 );
      $this->func__set_filled_in_fail($iForm_id );      
    } else if( 
			(
				!isset($_POST['m_'.$protect]) ||
				( isset($_POST['m_'.$protect]) && intval($_POST['m_'.$protect]) == 0 )
			) && !isset($_POST['c_'.$protect]) && $bCustomQuestion == 0 && !$bSkipJSCheck
		) {	//	no math question and no custom question
			setcookie( 'fvaq_nojs', 1, time()+120 ); 
			$this->func__set_filled_in_fail($iForm_id);
		} else if( !$bSkipJSCheck && $bCustomQuestion == 0 && $_POST['m_'.$protect] != $_POST['ma_'.$protect] ) {	//	bad math answer	
			setcookie( 'fvaq_nojs', 1, time()+120 );  
			$this->func__set_filled_in_fail($iForm_id);
		} else if( !$bSkipJSCheck && $bCustomQuestion == 0 && ( !isset($_POST['m_'.$protect]) || !isset($_POST['ma_'.$protect]) ) ) {	//	no custom question, but no math answer either!
			$this->func__set_filled_in_fail($iForm_id);
		} else if( function_exists('akismet_http_post') ) {
		
			$c = array();
			$c['user_ip']    = ( isset($_GET['ip']) ) ? $_GET['ip'] : $_SERVER['REMOTE_ADDR'];			
			$c['user_agent'] = ( isset($_GET['ua']) ) ? $_GET['ua'] : $_SERVER['HTTP_USER_AGENT'];
			$c['referrer']   = $_SERVER['HTTP_REFERER'];
			$c['blog']       = get_option('home');
			$c['blog_lang']  = get_locale();
			$c['blog_charset'] = get_option('blog_charset');
			$c['permalink']  = get_option('home');
			$c['is_test'] = 1;		
			$c['comment_type'] = 'form';
			
			$query_string = '';
			foreach ( $c as $key => $data ) {
				$query_string .= $key . '=' . urlencode( stripslashes($data) ) . '&';
			}
			
			global $wpdb;
			
			//	adjusted code from akismet/akismet.php akismet_init(), init was not triggered yet. todo: wpcom_api_key might not be there yet.
			global $wpcom_api_key;

      if ( $wpcom_api_key ) {
        $akismet_api_host = $wpcom_api_key . '.rest.akismet.com';
      } else {
       	$akismet_api_host = get_option('wordpress_api_key') . '.rest.akismet.com';
      }

      $akismet_api_port = 80;			
      
		
			//file_put_contents( dirname(__FILE__)."/filled-in-mv.log", date('r')." query:\n".var_export($c,true)."\n", FILE_APPEND );
		
			$response = akismet_http_post($query_string, $akismet_api_host, '/1.1/comment-check', $akismet_api_port);

			//file_put_contents( dirname(__FILE__)."/filled-in-mv.log", date('r')." response:\n".var_export($response,true)."\n\n", FILE_APPEND );								
			
			if( $response[1] == 'true' ) {
				//setcookie( 'fvaq_nojs', 1, time()+120 );
				$this->func__set_filled_in_fail($iForm_id, 'Akimset' );	
			}
			
		}			
  }	
  
  
  
  
  function func__flag_comment_request($comment, $is_ping = false) { ///  action part - moves to spam or deletes
    $this->func__update_spam_count();
    add_filter( 'pre_comment_approved', array( $this, 'func__i_am_spam' ) );
    return $comment;
  }  
  
  
  
  
  /**
    * Returns name of the fake field.          
    * 
    * @return string Fake field name.
    */    
  function func__get_filled_in_fake_field_name() {
    $name = $this->func__get_plugin_option('protect_filledin_field');
    if( !$name ) {
      $name = 'comment';
    }
    return $name;
  }  
  
  
  
  
  public static function func__get_filled_in_version(){
		if( is_admin() ){
			if( !is_a( $GLOBALS['admin_filled_in'], 'Filled_In_Admin' ) ) {
				return false;
			}
			global $admin_filled_in;
			$objFilled = $admin_filled_in;
		} else {
			if( !is_a( $GLOBALS['filled_in'], 'Filled_In' ) ) {
				return false;
			}
			global $filled_in;
			$objFilled = $filled_in;
		}
		
		if( !is_readable( $objFilled->plugin_base . '/filled_in.php' ) ) {
			return false;
		}
		
		$strFilled = file_get_contents( $objFilled->plugin_base . '/filled_in.php' );
		if( !preg_match( '~Version: ([0-9.]+)~ims', $strFilled, $aMatch ) ) {
			return false;
		}
		$strVersion = $aMatch[1];
		
		return $strVersion;
  }
  
  
  
  
  function func__get_plugin_option($field) {
    if (!$options = wp_cache_get('fv_antispam')) {
      $options = get_option('fv_antispam');
      wp_cache_set( 'fv_antispam', $options );
    }
    return @$options[$field];
  }  
  
  
  
  
  function func__get_spam_count() {
    return number_format_i18n(
      $this->func__get_plugin_option('spam_count')
    );
  }
  
  
  
  
  function func__i_am_spam($approved) { ///  moves to spam
    return 'spam';
  } 
  
  
  
  
  function func__ip_protect() {
    return 'r'.substr(md5($_SERVER['REMOTE_ADDR']), 0, 8); 
  }  
  
  
  
  
	public static function func__is_compatible_filled_in() {
		$strVersion = self::func__get_filled_in_version();
		
		if( !$strVersion ) {
			return false;
		} else {
			return version_compare( $strVersion, '1.8.4', '>=' );	
		}
	}  
  
  
  
  
  /*
  Copy of wp_new_comment from wp-includes/comment.php - inserets the comment, doesn't trigger any notifications etc.
  */
  function func__log_comment( $commentdata ) {
		$commentdata['comment_post_ID'] = (int) $commentdata['comment_post_ID'];
		if ( isset($commentdata['user_ID']) )
			$commentdata['user_id'] = $commentdata['user_ID'] = (int) $commentdata['user_ID'];
		elseif ( isset($commentdata['user_id']) )
			$commentdata['user_id'] = (int) $commentdata['user_id'];
	
		$commentdata['comment_parent'] = isset($commentdata['comment_parent']) ? absint($commentdata['comment_parent']) : 0;
		$parent_status = ( 0 < $commentdata['comment_parent'] ) ? wp_get_comment_status($commentdata['comment_parent']) : '';
		$commentdata['comment_parent'] = ( 'approved' == $parent_status || 'unapproved' == $parent_status ) ? $commentdata['comment_parent'] : 0;
	
		$commentdata['comment_author_IP'] = preg_replace( '/[^0-9a-fA-F:., ]/', '',$_SERVER['REMOTE_ADDR'] );
		$commentdata['comment_agent']     = substr($_SERVER['HTTP_USER_AGENT'], 0, 254);
	
		$commentdata['comment_date']     = current_time('mysql');
		$commentdata['comment_date_gmt'] = current_time('mysql', 1);
	
		$commentdata = wp_filter_comment($commentdata);
	
		$commentdata['comment_approved'] = 'trash';
	
		$comment_ID = wp_insert_comment($commentdata);  
  	update_comment_meta( $comment_ID, 'fv-antispam', 'Math question failed' );
  	update_comment_meta( $comment_ID, 'fv-antispam-post', $_POST );  	
  	update_comment_meta( $comment_ID, 'fv-antispam-server', $_SERVER );  	  	
  	
  	return true;
  }
  
  
  
  function func__login_scripts() {
		wp_enqueue_script( 'jquery' );		
	}
	
	
	
	
  function func__precheck_comment_request() { ///  detect spam here
    if( is_feed() || is_trackback() || FV_Antispam::util__is_wp_touch() || FV_Antispam::util__is_jetpack_comments() ) {
      return;
    }
    $request_url = @$_SERVER['REQUEST_URI'];

    if (empty($_POST) || empty($request_url) || strpos($request_url, 'wp-comments-post.php') === false) {
      return;
    }

    if ( empty($_POST['comment']) && !empty($_POST[$this->func__protect($_POST['comment_post_ID'])])) {
      $_POST['comment'] = $_POST[$this->func__protect($_POST['comment_post_ID'])];
      unset($_POST[$this->func__protect($_POST['comment_post_ID'])]);
    } else {
      $_POST['bee_spam'] = 1;
      add_filter( 'preprocess_comment', array($this, 'remove_akismet_hook'), 0);
    }
  }
  
	
	
	
  /**
    * Generate the unique hash key.
    * 
    * @param int $postID Current post ID.             
    * 
    * @return string Hash key.
    */    
  static function func__protect($postID) {
    $postID = 0;  //  some templates are not able to give us the post ID when submitting comment, so we turn this of for now
    return 'a'.substr(md5(get_bloginfo('url').$postID), 0, 8);
  }	
	
	
	
	
	/**
    * Adds fake field.
    * 
    * @global object Current post object.                
    * 
    */ 
  function func__replace_comment_field() {
    if( is_feed() || is_trackback() || $this->util__is_wp_touch() || $this->util__is_jetpack_comments() ) {
      return;
    }
    if( !is_singular() ) {
      //return;
    }
    global $post;
    ob_start('FV_Antispam::func__fake_textarea');
  }
  
  
  
  
  function func__registration_errors( $errors ) {
  	$message = ( $this->func__get_plugin_option('protect_filledin_notice') && trim($this->func__get_plugin_option('protect_filledin_notice')) ) ? $this->func__get_plugin_option('protect_filledin_notice') : 'Your form submission was detected as spam. Please contact us directly.';
  	$errors->add( 'fv_antispam', $message );
  	return $errors;
  }
  
  
  
  
  /**
    * Callback which adds fake field.
    * 
    * @param string Page content from OB.
    * @global object Current post object.                
    * 
    * @return string New page content.
    */    
  static function func__replace_textarea( $aMatch ) {
    global $post, $FV_Antispam_iFilledInCount;

    $sForm = $aMatch[0];
    $sTextarea = $aMatch[1];
  
    preg_match( '/class=[\"\'](.*?)[\"\']/', $sTextarea, $class );
    preg_match( '/id=[\"\'](.*?)[\"\']/', $sTextarea, $id );
    preg_match( '/name=[\"\'](.*?)[\"\']/', $sTextarea, $name );

    $sClass = !empty($class) ? $class[1] : false;
    $sID = $id[1];
    $sName = $name[1];

    $sTextarea = preg_replace('~<textarea([^\>]*>).*?</textarea>~', "<textarea$1</textarea>", $sTextarea ); // have to keep the hidden textarea empty
        
    $sProtect = FV_Antispam::func__protect($post->ID);
    
    $sTextareaNew = preg_replace( '/id=[\'"]'.$sID.'[\'"]/i', 'id="'.$sProtect.'"', $sTextarea );
    $sTextareaNew = preg_replace( '/name=[\'"]'.$sName.'[\'"]/i', 'name="'.$sProtect.'"', $sTextareaNew );
        
    if( !$sClass ) {
      $sTextarea = str_replace( 'id="comment"', 'id="comment" class="message-textarea"', $sTextarea );
      $sTextarea = str_replace( "id='comment'", "id='comment' class='message-textarea'", $sTextarea );
    } else {
      $sTextarea = str_replace( 'class="', 'class="message-textarea ', $sTextarea );
      $sTextarea = str_replace( "class='", "class='message-textarea ", $sTextarea );
    }    
    
    if( !is_user_logged_in() && 1<0 ) { //  no math question for comment submissions!
      $FV_Antispam_iFilledInCount++;
			$sTextareaNew .= FV_Antispam::disp__math_question($sProtect);
    }
    
    $sTextarea = str_replace( array('required="required"',"required='required'"), array('',''), $sTextarea ); // HTML5 form validation needs to be disabled
    
    $sForm = preg_replace('~<textarea([^\>]*name=[\'\"]comment[\'\"][^\>]*>).*?</textarea>~', $sTextarea, $sForm ); // put in the adjusted textarea
    $sForm = preg_replace('~(class=[\'"][^\'"]*?)required([^\'"]*?[\'"])~', "$1$2", $sForm);   // gotta get rid of class="required"
    
    $output = $sForm.'<!-- </form> -->'.$sTextareaNew;
    
    $output = preg_replace( '~onsubmit=[\'"]return checkForm\(this\);?[\'"]~i', '', $output );

    return $output;
  } 




  static function func__fake_textarea( $input ) {
    return preg_replace_callback("#<form[^>]*?wp-comments-post.php[^\"]*?\".*?(<textarea.*?name=['\"]comment['\"].*?</textarea>)#s", "FV_Antispam::func__replace_textarea" , $input);
  }  
  
  
  
  
	public function func__set_filled_in_fail( $idForm, $type = 'math' ){
	
		if( !self::func__is_compatible_filled_in() ){

		}
		
		$message = ( trim($this->func__get_plugin_option('protect_filledin_notice')) ) ? $this->func__get_plugin_option('protect_filledin_notice') : 'Your form submission was detected as spam. Please contact us directly.';
		
		if( class_exists( 'FI_Form' ) && method_exists( 'FI_Form', 'load_by_id' ) ) {
			require_once( dirname( __FILE__ ) . '/include/filled-in-filter.php' );
			
			$objFiForm = FI_Form::load_by_id( $idForm );
			
			$objExt = new FvFilledInAntispamFilter( array() );
			$objExt->form_id = $idForm;
			$objExt->position = (string) count( $objFiForm->extensions['filter'] );
			$objExt->name = $type;
			$objExt->type = 'FvFilledInAntispamFilter';
			$objExt->status = 'on';
			$objExt->config = array(
					'error' => $message
				);
			
			$objFiForm->extensions['filter'][] = $objExt;
		}
	}  
  
  
  
  
  function func__set_plugin_option($field, $value) {
    if (empty($field)) {
      return;
    }
    $this->func__set_plugin_options( array( $field => $value ) );
  }
  
  
  
  
  function func__set_plugin_options($data) {
    if (empty($data)) {
      return;
    }
    $options = array_merge( (array)get_option('fv_antispam'), $data );
    delete_option( 'fv_antispam_filledin_conflict' );
    update_option( 'fv_antispam', $options );
    wp_cache_set( 'fv_antispam', $options );
  }  
  
  
  
  
  function func__spam_log($file_name) {
    $fh = fopen(dirname(__FILE__) . '/' . $file_name, 'a');      
    $post = var_export($_POST, true);
    $server = var_export($_SERVER, true);
    fwrite($fh, '$_POST: ' . $post . ';' . "\n" . '$_SERVER: ' . $server . "\n\n");      
    fclose($fh);
  }
  
  
  
  
  function func__spam_registrations_check() {
  	$protect = $this->func__ip_protect();

  	if( isset($_POST['m_'.$protect]) && isset($_POST['ma_'.$protect]) && $_POST['m_'.$protect] == $_POST['ma_'.$protect] ) {
    	$_POST['user_email'] = ( $_POST['user_email'] ) ? $_POST['user_email'] : $_POST[$this->func__ip_protect()];
  	} else if( isset($_POST['user_email']) && trim($_POST['user_email']) != "" ) {
      $fv_antispam_registrations = get_option('fv_antispam_registrations');
      $fv_antispam_registrations = ( $fv_antispam_registrations ) ? $fv_antispam_registrations : array();      
      $fv_antispam_registrations[] = array( 'date' => date('r'), 'user_login' => $_POST['user_login'], 'user_email' => $_POST['user_email'] );
      update_option( 'fv_antispam_registrations', $fv_antispam_registrations );
      unset($_POST['user_email']);
      
      add_filter( 'registration_errors', array( $this, 'func__registration_errors' ) );
    } else if( isset($_POST[$this->func__ip_protect()]) ) {
    	$_POST['user_email'] = $_POST[$this->func__ip_protect()];                  
    }
  }
  
  
  
  
  /**
    * Sends comment to spam based on the antispam check and blacklist check
    * 
    * @param int Comment ID.  
    * 
    */ 
  function func__trash_comment( $id ) {
    $spam_remove = true;//!$this->func__get_plugin_option('flag_spam');
    $commentdata = get_comment( $id );
    
    $res = false;
    $res2 = false;
    if( $this->func__get_plugin_option('trash_banned') ) {
      $res = wp_check_comment_disallowed_list($commentdata->comment_author, $commentdata->comment_author_email, $commentdata->comment_author_url, $commentdata->comment_content, $commentdata->comment_author_IP, $commentdata->comment_agent);
    }
    if( $spam_remove ) {
      $res2 = isset($_POST['bee_spam']) ? $_POST['bee_spam'] : false;
    }
      
    if ( $res || $res2 == 1 ) {
      remove_action('edit_post', 'hyper_cache_invalidate_post', 0);
      wp_set_comment_status( $id, 'trash' );
    }
     
  }  
  
  
  
  
  function func__update_spam_count() {
    $this->func__set_plugin_option( 'spam_count', intval($this->func__get_plugin_option('spam_count') + 1) );
  }  
  
  
  
  
  function func__verify_comment_request($comment) { ///  detect spam here
    
    if( $this->util__is_jetpack_comments() ) {
      return $comment;
    }

    $request_url = @$_SERVER['REQUEST_URI'];
    $request_ip = @$_SERVER['REMOTE_ADDR'];
    if (empty($request_url) || empty($request_ip)) {
      return $this->func__flag_comment_request($comment);
    }
    $comment_type = @$comment['comment_type'];
    
    $comment_url = @$comment['comment_author_url'];
    $comment_body = @$comment['comment_content'];
    $comment_email = @$comment['comment_author_email'];
    $ping_types = array('pingback', 'trackback', 'pings');
    /// Global WP setting for closing ping overrides individual post setting in old WP
    if ($this->util__is_min_wp('2.7')) {
    }
    else if( in_array($comment_type, $ping_types) && get_option( 'default_ping_status' ) == 'closed' ) {
      die( '<response><error>1</error><message>Sorry, trackbacks are closed for this item.</message></response>' );
    }
    ///
    $ping_allowed = !$this->func__get_plugin_option('ignore_pings');
    if (!empty($comment_url)) {
      $comment_parse = @parse_url($comment_url);
      $comment_host = @$comment_parse['host'];
    }
    if (strpos($request_url, 'wp-comments-post.php') !== false && !empty($_POST)) {
      if ($this->func__get_plugin_option('already_commented') && 1<0 ) {  ///  if comment author has an approved comment, turned OFF
        if ($GLOBALS['wpdb']->get_var("SELECT COUNT(comment_ID) FROM `" .$GLOBALS['wpdb']->comments. "` WHERE `comment_author_email` = '" .$comment_email. "' AND `comment_approved` = '1' LIMIT 1")) {
          return $comment;
        }
      }
      if (!empty($_POST['bee_spam'])) { //  check the fake field
        return $this->func__flag_comment_request($comment);
      } else {
      	//$comment = $this->func__check_math( $comment );
      }

    } else if (!empty($comment_type) && in_array($comment_type, $ping_types) && $ping_allowed) {
      if (empty($comment_url) || empty($comment_body)) {
        return $this->func__flag_comment_request($comment, true);
      } else if (!empty($comment_host) && gethostbyname($comment_host) != $request_ip) {  //  check pingback sender site vs. IP
        return $this->func__flag_comment_request($comment, true);
      }
    }
    return $comment;
  }  
  
  
  
  
  function remove_akismet_hook($comment){
		remove_action( 'preprocess_comment', array( 'Akismet', 'auto_check_comment' ), 1 );
		return $comment;
  }
  
  
  
  
  function util__cache_comment_meta( $comments ) {
  	$ids = array();
  	foreach( $comments AS $comment ) {
  		$ids[] = $comment->comment_ID;
  	}
  	global $wpdb;

  	update_meta_cache( 'comment', $ids );

  	return $comments;
  }
  
  
  
  
  function util__check_user_can() {
    if (current_user_can('manage_options') === false || !is_user_logged_in()) {
      wp_die('You do not have permission to access!');
    }
  }
  
  
  
  
  function util__closed_meta_boxes( $closed ) {
    if ( false === $closed ) {
        $closed = array( 'fv_antispam_advanced', 'fv_antispam_filled_in' );
    }
    
    return $closed;
  }
  
  
  
  
  function util__is_filled_in() {
    if( stripos( implode(get_option('active_plugins')), 'filled-in' ) !== false ) {
      return true;
    }
    return false;
  }
  
  
  
  
  function util__is_jetpack_comments() {
    if( $aJetPack = get_option('jetpack_active_modules') ) {
      if( in_array( 'comments', $aJetPack ) ) {
        return true;
      }    
    }
    return false;
  }
    
    
    
    
  function util__is_min_wp($version) {
    return version_compare( $GLOBALS['wp_version'], $version. 'alpha', '>=' );
  } 
    
    
    
    
  function util__is_wp_touch() {
    return strpos(TEMPLATEPATH, 'wptouch');
  }    

  
  
  
  function util__pointers_ajax() {

    if( isset($_POST['key']) && $_POST['key'] == 'fv_antispam_notice_custom_questions' && isset($_POST['value']) ) {
      check_ajax_referer('fv_antispam_notice_custom_questions');
      $conf = get_option( 'fv_antispam' );

      if( $conf && $_POST['value'] == 'true' ) {
        $conf['notice_custom_questions'] = true;
        update_option( 'fv_antispam', $conf );
      }
      die();
    }

  }



}




$GLOBALS['FV_Antispam'] = new FV_Antispam();




/*
Extra
*/
if ( !function_exists('wp_notify_moderator') && ( $GLOBALS['FV_Antispam']->func__get_plugin_option('disable_pingback_notify') || $GLOBALS['FV_Antispam']->func__get_plugin_option('pingback_notify_email') ) ) :
/**
 * wp_notify_moderator function modified to skip notifications for trackback and pingback type comments
 *
 * @param int $comment_id Comment ID
 * @return bool Always returns true
 */
 
  if( $GLOBALS['FV_Antispam']->util__is_min_wp('2.5') ) : 
   
  function wp_notify_moderator($comment_id) {
  	global $wpdb;
  
  	if( get_option( "moderation_notify" ) == 0 )
  		return true;
  
  	$comment = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_ID=%d LIMIT 1", $comment_id));
  	$post = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE ID=%d LIMIT 1", $comment->comment_post_ID));
  
  	$comment_author_domain = @gethostbyaddr($comment->comment_author_IP);
  	$comments_waiting = $wpdb->get_var("SELECT count(comment_ID) FROM $wpdb->comments WHERE comment_approved = '0'");
  
  	// The blogname option is escaped with esc_html on the way into the database in sanitize_option
  	// we want to reverse this for the plain text arena of emails.
  	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
  
  	switch ($comment->comment_type)
  	{
  		case 'trackback':
  		  if( $GLOBALS['FV_Antispam']->func__get_plugin_option('disable_pingback_notify') ) {
  		    return true;
  		  }
  			$notify_message  = sprintf( __('A new trackback on the post "%s" is waiting for your approval'), $post->post_title ) . "\r\n";
  			$notify_message .= get_permalink($comment->comment_post_ID) . "\r\n\r\n";
  			$notify_message .= sprintf( __('Website : %1$s (IP: %2$s , %3$s)'), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
  			$notify_message .= sprintf( __('URL    : %s'), $comment->comment_author_url ) . "\r\n";
  			$notify_message .= __('Trackback excerpt: ') . "\r\n" . $comment->comment_content . "\r\n\r\n";
  			break;
  		case 'pingback':
  		  if( $GLOBALS['FV_Antispam']->func__get_plugin_option('disable_pingback_notify') ) {
  		    return true;
  		  }
  			$notify_message  = sprintf( __('A new pingback on the post "%s" is waiting for your approval'), $post->post_title ) . "\r\n";
  			$notify_message .= get_permalink($comment->comment_post_ID) . "\r\n\r\n";
  			$notify_message .= sprintf( __('Website : %1$s (IP: %2$s , %3$s)'), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
  			$notify_message .= sprintf( __('URL    : %s'), $comment->comment_author_url ) . "\r\n";
  			$notify_message .= __('Pingback excerpt: ') . "\r\n" . $comment->comment_content . "\r\n\r\n";
  			break;
  		default: //Comments
  			$notify_message  = sprintf( __('A new comment on the post "%s" is waiting for your approval'), $post->post_title ) . "\r\n";
  			$notify_message .= get_permalink($comment->comment_post_ID) . "\r\n\r\n";
  			$notify_message .= sprintf( __('Author : %1$s (IP: %2$s , %3$s)'), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
  			$notify_message .= sprintf( __('E-mail : %s'), $comment->comment_author_email ) . "\r\n";
  			$notify_message .= sprintf( __('URL    : %s'), $comment->comment_author_url ) . "\r\n";
  			$notify_message .= sprintf( __('Whois  : http://ws.arin.net/cgi-bin/whois.pl?queryinput=%s'), $comment->comment_author_IP ) . "\r\n";
  			$notify_message .= __('Comment: ') . "\r\n" . $comment->comment_content . "\r\n\r\n";
  			break;
  	}
  
  	$notify_message .= sprintf( __('Approve it: %s'),  admin_url("comment.php?action=approve&c=$comment_id") ) . "\r\n";
  	if ( EMPTY_TRASH_DAYS )
  		$notify_message .= sprintf( __('Trash it: %s'), admin_url("comment.php?action=trash&c=$comment_id") ) . "\r\n";
  	else
  		$notify_message .= sprintf( __('Delete it: %s'), admin_url("comment.php?action=delete&c=$comment_id") ) . "\r\n";
  	$notify_message .= sprintf( __('Spam it: %s'), admin_url("comment.php?action=spam&c=$comment_id") ) . "\r\n";
  
  	$notify_message .= sprintf( _n('Currently %s comment is waiting for approval. Please visit the moderation panel:',
   		'Currently %s comments are waiting for approval. Please visit the moderation panel:', $comments_waiting), number_format_i18n($comments_waiting) ) . "\r\n";
  	$notify_message .= admin_url("edit-comments.php?comment_status=moderated") . "\r\n";
  
  	$subject = sprintf( __('[%1$s] Please moderate: "%2$s"'), $blogname, $post->post_title );
  	$admin_email = get_option('admin_email');
  	
  	if( $GLOBALS['FV_Antispam']->func__get_plugin_option('pingback_notify_email') && ( $comment->comment_type == 'trackback' || $comment->comment_type == 'pingback' ) ) {
  	   $admin_email = $GLOBALS['FV_Antispam']->func__get_plugin_option('pingback_notify_email');
    }
  	
  	$message_headers = '';
  
  	$notify_message = apply_filters('comment_moderation_text', $notify_message, $comment_id);
  	$subject = apply_filters('comment_moderation_subject', $subject, $comment_id);
  	$message_headers = apply_filters('comment_moderation_headers', $message_headers);
  
  	@wp_mail($admin_email, $subject, $notify_message, $message_headers);
  
  	return true;
  }
  
  else :
  /// This function is used for Wordpress < 2.5
  function wp_notify_moderator($comment_id) {
  	global $wpdb;
  
  	if( get_option( "moderation_notify" ) == 0 ) {
  		return true;
    }
    
    $comment = $wpdb->get_row("SELECT * FROM $wpdb->comments WHERE comment_ID='$comment_id' LIMIT 1");
  		
  	if( $comment->comment_type == 'pingback' || $comment->comment_type == 'trackback' ) {
  	  if( $GLOBALS['FV_Antispam']->func__get_plugin_option('disable_pingback_notify') ) {
		    return true;
		  }
  	}

  	$post = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID='$comment->comment_post_ID' LIMIT 1");
  
  	$comment_author_domain = @gethostbyaddr($comment->comment_author_IP);
  	$comments_waiting = $wpdb->get_var("SELECT count(comment_ID) FROM $wpdb->comments WHERE comment_approved = '0'");
  
  	$notify_message  = sprintf( __('A new comment on the post #%1$s "%2$s" is waiting for your approval'), $post->ID, $post->post_title ) . "\r\n";
  	$notify_message .= get_permalink($comment->comment_post_ID) . "\r\n\r\n";
  	$notify_message .= sprintf( __('Author : %1$s (IP: %2$s , %3$s)'), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
  	$notify_message .= sprintf( __('E-mail : %s'), $comment->comment_author_email ) . "\r\n";
  	$notify_message .= sprintf( __('URL    : %s'), $comment->comment_author_url ) . "\r\n";
  	$notify_message .= sprintf( __('Whois  : http://ws.arin.net/cgi-bin/whois.pl?queryinput=%s'), $comment->comment_author_IP ) . "\r\n";
  	$notify_message .= __('Comment: ') . "\r\n" . $comment->comment_content . "\r\n\r\n";
  	$notify_message .= sprintf( __('Approve it: %s'),  get_option('siteurl')."/wp-admin/comment.php?action=mac&c=$comment_id" ) . "\r\n";
  	$notify_message .= sprintf( __('Delete it: %s'), get_option('siteurl')."/wp-admin/comment.php?action=cdc&c=$comment_id" ) . "\r\n";
  	$notify_message .= sprintf( __('Spam it: %s'), get_option('siteurl')."/wp-admin/comment.php?action=cdc&dt=spam&c=$comment_id" ) . "\r\n";
  	$notify_message .= sprintf( __('Currently %s comments are waiting for approval. Please visit the moderation panel:'), $comments_waiting ) . "\r\n";
  	$notify_message .= get_option('siteurl') . "/wp-admin/moderation.php\r\n";
  
  	$subject = sprintf( __('[%1$s] Please moderate: "%2$s"'), get_option('blogname'), $post->post_title );
  	$admin_email = get_option('admin_email');
  	
  	if( $GLOBALS['FV_Antispam']->func__get_plugin_option('pingback_notify_email') && ( $comment->comment_type == 'trackback' || $comment->comment_type == 'pingback' ) ) {
  	   $admin_email = $GLOBALS['FV_Antispam']->func__get_plugin_option('pingback_notify_email');
    }
  
  	$notify_message = apply_filters('comment_moderation_text', $notify_message, $comment_id);
  	$subject = apply_filters('comment_moderation_subject', $subject, $comment_id);
  
  	@wp_mail($admin_email, $subject, $notify_message);
  
  	return true;
  }
  
  endif;

endif;
