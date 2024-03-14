<?php
/* all redirect  opitons class */

class sfmRedirectActions
{
    public $sfm_ActivateRedirectUrl="https://api.follow.it/wordpress/redirect_plugin_setup";
    public $SFM_REDIRECTION_TABLE ="sfm_redirects";
    public $sfm_UpdateFeedsUrl="https://api.follow.it/wordpress/updatepermalink";
    public $SFM_CONNECT_LINK="https://api.follow.it/?";
    public $SFM_SETUP_URL='https://api.follow.it/rssegtcrons/download_rssmorefeed_data_single/';
    
    function __construct()
    {
        /* process activate redirection  */
		add_action('wp_ajax_ActRedirect',array(&$this,'sfmActivateRedirect'));
		
		/* reverse redirection */
		add_action('wp_ajax_sfmReverseRedirect',array(&$this,'sfmReverseRedirect'));
		
		/* process the feed messages action  */
		add_action('wp_ajax_sfmProcessFeeds',array(&$this,'sfmProcessFeeds'));
		
		/* update feed urls on permalink update */
		add_action( 'admin_init' , array(&$this,'sfmUpdateRedirectedUrls'));
		
		/* activate feed redirection */
		add_action('template_redirect', array(&$this,'sfm_feed_redirect'),10);
		
		/* delete authors feed if user get delete */
		add_action( 'delete_user',array(&$this,'sfmDeleteFeed') );
		
		/* delete category feed if a category get deleted */
		add_action( 'delete_term_taxonomy',array(&$this,'sfmDeleteCatFeed') );
		
		/* Load Header meta */
		add_action( 'wp_head',array(&$this,'sfmHeaderMeta') );    
    }
   
    /* List all RSS links */
    public function sfmListActiveRss()
    {
        /* get the comment feed url */
        $return_data=array();
        
       	$comments_link = get_bloginfo('comments_rss2_url');
       	$return_data['comment_url'] = $comments_link;
        
		/* get categoires feed url */
        $cat_argu=array( 
		  	'type' => 'post',
            'orderby' => 'name',
            'order'   => 'ASC',   
        );
        
        $wp_categoires = get_categories($cat_argu);
        $return_data['categoires'] = $wp_categoires;
        
		    /* get the authors */
        global $wpdb;
        $wp_authors = $wpdb->get_results('select DISTINCT p.post_author,u.user_login from '.$wpdb->prefix.'posts p LEFT JOIN '.$wpdb->prefix.'users u on p.post_author=u.ID where p.post_status="publish" and p.post_type="post"',ARRAY_A);
        $return_data['authors'] = $wp_authors;
        
		    /* get the all custom active feeds */
		
        return $return_data;
    }
    
    /* return all active rss links */
    public function sfmCheckActiveMainRss()
    {
		global $wpdb;
		$get_feed = $wpdb->get_results('SELECT DISTINCT feed_type,feed_subUrl,sf_feedid,feed_url  from '.$wpdb->prefix.$this->SFM_REDIRECTION_TABLE.' WHERE redirect_status=1',ARRAY_A);
		return $get_feed; exit;
    }
	
    /* return all active rss links */
    public function sfmGetRssDetail($input_data)
    {
		global $wpdb;
		if(!empty($input_data[1]))
		{
			$qr=" and id_on_blog='".$input_data[1]."'" ;   
		}
		else
		{
		   	$qr=''; 
		}
		
		$get_feed=$wpdb->get_row('SELECT *  from '.$wpdb->prefix.$this->SFM_REDIRECTION_TABLE." where feed_type='".$input_data[0]."' AND redirect_status=1 ".$qr);
		return $get_feed; exit;
    }
    
	/* return custom links */
    public function sfmGetCustomLink($input_data)
    {
		global $wpdb;
		$get_feed = $wpdb->get_row('SELECT *  from '.$wpdb->prefix.$this->SFM_REDIRECTION_TABLE." where blog_rss='".$input_data[0]."' and feed_type='custom_rss' AND redirect_status=1 ");
		return $get_feed; exit;
    }
    
	/* return all active rss links by SF feedId */
    public function sfmGetRssDetailByFeed($input_data)
    {
		global $wpdb;
		$get_feed = $wpdb->get_row('SELECT *  from '.$wpdb->prefix.$this->SFM_REDIRECTION_TABLE." where sf_feedid='".$input_data[0]."' AND redirect_status=1");
		return $get_feed; exit;
    }
	
    /* return all custom feed links */
    public function sfmGetCustomFeeds()
    {
		global $wpdb;
		$get_feeds = $wpdb->get_results('SELECT *  from '.$wpdb->prefix.$this->SFM_REDIRECTION_TABLE." where feed_type='custom_rss' AND redirect_status=1");
		return $get_feeds; exit;
    }
	
    /* fetch the feed url from follow.it */ 
    public function sfmActivateRedirect()
    {
    	if ( !wp_verify_nonce( $_POST['nonce'], "ActRedirect")) {
	      echo  json_encode(array("wrong_nonce")); exit;
	   	}
	   	if(!current_user_can('manage_options')){ echo json_encode(array('res'=>'not allowed'));die(); }
		global $wpdb;

				// $table = $wpdb->prefix.'sfm_redirects';
				// $respons = $wpdb->get_row(
				// 	"truncate table $table"
				// );
				
		/* check for the feed type */
		$url = $this->sfm_ActivateRedirectUrl;
		if(!empty($_POST['rtype']))
		{
			$data = array('subscriber_type'=>'RWP','web_url'=>get_bloginfo('url'));
			$blog_url = '';
			
			switch(sanitize_text_field($_POST['rtype']))
			{
				case "main_rss"    :
					$existence = $this->checkFeedExist("main_rss");
					$web_url = html_entity_decode(sfm_get_bloginfo('rss2_url'));
					$web_url = (strpos($web_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$web_url):$web_url;
					$data['feed_url'] = $web_url;
					$existence = $this->checkFeedExist("main_rss", $web_url);
				break;		     
				case "comment_rss" :
					$existence = $this->checkFeedExist("comment_rss");
					$web_url = html_entity_decode(get_bloginfo('comments_rss2_url'));
                    
					$web_url = (strpos($web_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$web_url):$web_url;
                    // var_dump( $web_url);
					$data['feed_url']=$web_url;
					$existence = $this->checkFeedExist("comment_rss", $web_url);
                    //var_dump($data['feed_url']);
				break;    
				case "custom_rss"  :
					$web_url = (isset($_POST['curl']) && !empty($_POST['curl'])) ? trim(sanitize_text_field($_POST['curl'])) :'';
					$web_url = (strpos($web_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$web_url):$web_url;

					if(!filter_var($web_url, FILTER_VALIDATE_URL))
					{
						echo json_encode(array('response'=>"invaild_url")); exit;
					}
					// var_dump($web_url,$_SERVER["SERVER_NAME"],site_url());
					
					if(strpos($web_url,site_url())<0)
					{
						 echo json_encode(array('response'=>"diff_url")); exit;
					}
					$data['feed_url'] = $web_url;
					$blog_url = $web_url;
					
					//Check existence
					$existence = $this->checkFeedExist("custom_rss", $web_url);
				break;
				
				case "category_rss" :
					$web_url = html_entity_decode(get_category_feed_link(trim(sanitize_text_field($_POST['record_id']) )));
					$web_url = (strpos($web_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$web_url):$web_url;
					$cat_data = get_category(trim(sanitize_text_field($_POST['record_id']) ));	
					$data['feed_url'] = $web_url;
					$data['category'] = trim($cat_data->slug);
					//var_dump($data['feed_url']);
					//Check existence
					$existence = $this->checkFeedExist("category_rss", $web_url);
                    //var_dump($existence);
				break;
				
				case "author_rss"   :
					$web_url = html_entity_decode(get_author_feed_link( trim(sanitize_text_field($_POST['record_id']) )));
					$web_url = (strpos($web_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$web_url):$web_url;
					$user_details = get_userdata (trim(sanitize_text_field($_POST['record_id']) ));
					$user_name = $user_details->user_login;
					$data['feed_url'] = $web_url;
					$data['author'] = trim($user_name);
					
					//Check existence
					$existence = $this->checkFeedExist("author_rss", $web_url);
				break;			
			}
            //var_dump($data['feed_url']);
			/* check for a valid feedurl and avoid the local servers*/
			/*if($_SERVER['SERVER_NAME']!='localhost') : 	
			$check_data=$this->sfmValidateFeed($data['feed_url']);
			if(!$check_data)
			{
				echo json_encode(array('response'=>"wrong_feedUrl")); exit;
			}
			endif;*/
			 
			$data['feed_url'] = add_query_arg( array('bypass' => 'sfm'), $data['feed_url'] );
			//var_dump(array("request_body"=>$data));
			/* check for feedburner form */
			if(sanitize_text_field($_POST['rtype'])=="main_rss")
			{
				$isFeedBurner = $this->sfm_CheckFeedBurner(); 
			}
			else
			{
				$isFeedBurner= 0; 
			}
			
			if($existence)
			{
				global $wpdb;
				$table = $wpdb->prefix.'sfm_redirects';
				$respons = $wpdb->get_row(
					"SELECT rid, sf_feedid as feed_id, feed_url, feed_subUrl as redirect_url, verification_code as code, redirect_status FROM $table where rid=$existence"
				);
				
				if(isset($respons->redirect_status) && $respons->redirect_status == 0)
				{
					$respons->respons = "exist";
					$respons->connect_string = $this->SFM_CONNECT_LINK.base64_encode(
						"userprofile=wordpress&feed_id=".$respons->feed_id
					);
					$record_id = (isset($_POST['record_id']) && !empty($_POST['record_id']))? sanitize_text_field($_POST['record_id']) : '';
					$data = $wpdb->query("UPDATE $table SET redirect_status=1 where rid=$existence");
					$request_data = array(
						'rq_type'	=>	sanitize_text_field($_POST['rtype']),
						'record_id'	=>	$record_id,
						'isfeed'	=>	$isFeedBurner
					);
					echo json_encode(array('response'=>"success",'res_data'=>$respons, 'request_data'=>$request_data));
                    
                    exit;
				}
				else
				{
					echo json_encode(array('response'=>"exists_url")); exit;
				}
			}
			else
			{
				/* send request to speficifeeds.com */
				$respons = $this->sfm_ProcessRequest($url,$data);
				//var_dump(array("request_body"=>$data,"Response_body"=>$respons));
			}
			
			/* update database on the base of response */
			if($respons->response=="success" || $respons->response=="exist")
			{
            //var_dump($web_url);
				$respons->connect_string = $this->SFM_CONNECT_LINK.base64_encode("userprofile=wordpress&feed_id=".$respons->feed_id);
				$record_id = (isset($_POST['record_id']) && !empty($_POST['record_id']))? sanitize_text_field($_POST['record_id']) : '';
				$re_data=array(
					'feedSetup_url'	=>  $web_url,
					'sf_feedid'		=>	$respons->feed_id,
					'id_on_blog'	=>	$record_id,
					'blog_rss'		=>	$blog_url,
					'feed_type'		=>	sanitize_text_field($_POST['rtype']),
					'feed_url'		=>	((strpos($respons->feed_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$respons->feed_url):$respons->feed_url),
					'feed_subUrl'	=>	$respons->redirect_url,
					'verification_code'	=>	$respons->code,
					'redirect_status'	=>	1
				);
				$format = array('%s','%s','%d','%s','%s','%s','%s','%s','%d');
				$wpdb->insert($wpdb->prefix.$this->SFM_REDIRECTION_TABLE, $re_data, $format );
				$respons->rid = $wpdb->insert_id;
				
				$request_data = array(
					'rq_type'	=>	sanitize_text_field($_POST['rtype']),
					'record_id'	=>	$record_id,
					'isfeed'	=>	$isFeedBurner
				);
				echo json_encode(array('response'=>"success",'res_data'=>$respons,'request_data'=>$request_data)); exit;
			}
			else
			{
				echo json_encode(array('response'=>"sf_error")); exit;
			}
			/* end of respons condition */
		}
		else
		{
			echo json_encode(array('response'=>"error")); exit;
		}
		/* end of post condition */
	
    }
	/* end sfmActivateRedirect() */
    
    /* reverse the redirection of feeds */
    public function sfmReverseRedirect()
	{
		if ( !wp_verify_nonce( $_POST['nonce'], "sfmReverseRedirect")) {
	      echo  json_encode(array("wrong_nonce")); exit;
	   	}
	   	if(!current_user_can('manage_options')){ echo json_encode(array('res'=>'not allowed'));die(); }
		global $wpdb;
		if(isset($_POST['feed_id']) && !empty($_POST['feed_id']))
		{
			switch(sanitize_text_field($_POST['feed_type']))
			{
				case "main_rss" :
					$reverse_url=html_entity_decode(sfm_get_bloginfo('rss2_url'));
				break;		     
				case "comment_rss" :
					$reverse_url = html_entity_decode(get_bloginfo('comments_rss2_url'));
				break;    
				case "custom_rss" :
					$reverse_url='';
				break;
				case "category_rss" :
					$fdata= $this->sfmGetRssDetailByFeed(array(sanitize_text_field($_POST['feed_id'])));
					$reverse_url=html_entity_decode(get_category_feed_link(trim($fdata->id_on_blog)));
				break;
				case "author_rss"   :
					$fdata = $this->sfmGetRssDetailByFeed(array(sanitize_text_field($_POST['feed_id'])) );
					$reverse_url = html_entity_decode(get_author_feed_link(trim($fdata->id_on_blog)));
				break;			
			}
			$reverse_url = ((strpos($reverse_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$reverse_url):$reverse_url);
			$wpdb->query('UPDATE '.$wpdb->prefix.$this->SFM_REDIRECTION_TABLE.' SET redirect_status=0 WHERE sf_feedid="'.sanitize_text_field($_POST['feed_id']).'"');
			echo json_encode(array('response'=>"success",'feed_url'=>$reverse_url)); exit;
		}
		else
		{
			echo json_encode(array('response'=>"error")); exit;
		}
	}
	/* end sfmReverseRedirect() */
    
    /* process the all request to outer server */
    private function sfm_ProcessRequest($url,$data)
    {
		//$curl = curl_init();  
        //curl_setopt_array($curl, array(
		//  CURLOPT_RETURNTRANSFER => 1,
		//    CURLOPT_URL => $url,
		//    CURLOPT_USERAGENT => 'sf rss activation request',
		//    CURLOPT_POST => 1,
		//    CURLOPT_POSTFIELDS => $data
		//));
		//curl_setopt($curl, CURLOPT_FAILONERROR, true);
		/* Send the request & save response to $resp */
        //$resp = curl_exec($curl);
        //if (curl_errno($curl)) {
        //    var_dump(curl_error($curl));
        //}
		$response = wp_remote_post($url,array("body"=>$data));
		//var_dump($response);
        $resp=json_decode(wp_remote_retrieve_body( $response ),false);
        //curl_close($curl);
        return $resp;exit;
    
    }
	/* end sfm_ProcessRequest() */
    
    /* check if the feedburner form is exists in text widget or not */
    public function sfm_CheckFeedBurner()
	{
		global $wpdb;
		$isFound="";
		$textWidgetDatas=get_option('widget_text');
		
		if(!empty($textWidgetDatas))
		{
			foreach($textWidgetDatas as $widget_data)
			{
				if(
          isset($widget_data['text']) && 
					(strpos($widget_data['text'],'http://feedburner.google.com/fb/a/mailverify') > 0 || strpos($widget_data['text'],'https://feedburner.google.com/fb/a/mailverify') > 0)
				)
				{
					return $isFound=1; exit;
				}
				else
				{
					$isFound=0; 
				}
			}
		}
		return $isFound;
	}
	/* end sfm_CheckFeedBurner() */
     
    /* update feed data if permalink of website get changed */
    public function sfmUpdateRedirectedUrls()
    {
		global $wpdb;
		if(get_option('sfm_permalink_structure') != get_option('permalink_structure'))
		{
			$getFeedsData=$wpdb->get_results('SELECT *  from '.$wpdb->prefix.$this->SFM_REDIRECTION_TABLE." where feed_type!='custom_rss'");
			foreach($getFeedsData AS $stored_feed)
			{
				$data_array=array();
				switch($stored_feed->feed_type)
				{
					case "main_rss" :
						$reverse_url=sfm_get_bloginfo('rss2_url');
						$data_array['feed_id']=$stored_feed->sf_feedid;
						$data_array['feed_url']= $reverse_url;   
					break;		     
					case "comment_rss" :
						$reverse_url=get_bloginfo('comments_rss2_url');
						$data_array['feed_id']=$stored_feed->sf_feedid;
						$data_array['feed_url']= $reverse_url;   
								
					break;    
					case "category_rss" :
						$reverse_url=get_category_feed_link(trim($stored_feed->id_on_blog));
						$data_array['feed_id']=$stored_feed->sf_feedid;
						$data_array['feed_url']= $reverse_url; 
					break;
					case "author_rss"   :
						$reverse_url=get_category_feed_link(trim($stored_feed->id_on_blog));
						$data_array['feed_id']=$stored_feed->sf_feedid;
						$data_array['feed_url']= $reverse_url;
					break; 			   
				}
				$data_array['feed_url'] = ((strpos($reverse_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$reverse_url):$reverse_url);
				$data_array['feed_url'] = add_query_arg( array('bypass' => 'sfm'), $data_array['feed_url'] );
				$response=$this->sfm_ProcessRequest($this->sfm_UpdateFeedsUrl,$data_array);
			}
			add_action('admin_notices', array(&$this,'SFMPermaUpdateCustomMsg'));
			update_option('sfm_permalink_structure', get_option('permalink_structure'));
		}
	
     }
	 /* end sfmUpdateRedirectedUrls() */
     public function SFMPermaUpdateCustomMsg()
     {
	 	echo "<div class=\"update-nag\" >" . "<p ><b>There may be some issue comes with your custom Redirect feed under \"follow.it Feedmaster \" plugin, Please <a href='admin.php?page=sfm-options-page'>Re-activate</a> the redirection for custom links. </b></p></div>";
     }
     
    /* check for the valid url */
    public function sfmValidateFeed( $rssFeedURL )
	{
		$rssValidator = 'http://feedvalidator.org/check.cgi?url=';
	     
		if( $rssValidationResponse = file_get_contents($rssValidator . urlencode($rssFeedURL)) )
		{
			if( stristr( $rssValidationResponse , 'This is a valid RSS feed' ) !== false )
			{
	    		return true;
			}
			else
			{
	    		return false;
			}
		}
		else
		{
			return false;
		}
    }
	/* end sfmValidateFeed() */
  
    /* redirect to SF */
    function sfm_feed_redirect()
	{
    	global $wp, $wp_query,$feed;
			
		/* check for feed page */
		if(is_feed() &&  strpos($_SERVER['HTTP_USER_AGENT'], "Specificfeeds- http://www.specificfeeds.com" )<=0) :
			$feed_type="custom";
			
			if($this->sfmgetCurrentURL()== sfm_get_bloginfo('rss2_url')) :
				$feed_type="main";
			endif;
			
			if(stripos($_SERVER['HTTP_USER_AGENT'], "Specificfeeds- http://www.specificfeeds.com" )!== false)
				$feed_type="custom";

			if(isset($wp_query->query['withcomments']) || $wp_query->query['feed']=="comments-rss2" ) : 
				$withcomments=1;
				$feed_type="comment";
			endif;
			
			if(
				isset($wp_query->query['category_name']) ||
				isset($wp_query->query['cat']) ||
				(isset($wp_query->query_vars['cat']) && !empty($wp_query->query_vars['cat']))
			) : 
				$category_id=$wp_query->query_vars['cat'];
				$feed_type="category";
			endif;
			
			if(isset($wp_query->query['author_name']) || isset($wp_query->query['author'])) : 
				$author_id=$wp_query->query_vars['author'];
				$feed_type="author";
			endif;
		
			$cus_data=$this->sfmGetCustomLink(array($this->sfmgetCurrentURL()));
			if( !empty( $cus_data ) ) {
				$feed_type="custom";
			}
			
			//echo $feed_type;
			switch ( $feed_type ) {
				case "main":
					$sfm_data=$this->sfmGetRssDetail( array( 'main_rss' ) );
				break;
				case 'comment':
					$sfm_data=$this->sfmGetRssDetail( array( 'comment_rss' ) );
				break;
				case 'category':
					$sfm_data=$this->sfmGetRssDetail( array( 'category_rss', $category_id ) );
				break;
				case 'author':
					$sfm_data=$this->sfmGetRssDetail( array( 'author_rss', $author_id ) );
				break;
				case 'custom':
					$sfm_data = $this->sfmGetCustomLink( array( $this->sfmgetCurrentURL() ) );
				break;
			}
			
			$sfmurl = $sfm_data ? $sfm_data->feed_url : null;

			if( $sfmurl && function_exists( 'status_header' ) ) {
				$sfmurl = ((strpos($sfmurl,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$sfmurl):$sfmurl);

				header("Location:" . $sfmurl);
				header("HTTP/1.1 302 Temporary Redirect");
				exit();
			}
		endif;

	}/* end sfm_feed_redirect() */
	    
	public function sfmgetCurrentURL()
	{
		// $currentURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
		// $currentURL .= $_SERVER["SERVER_NAME"];
		 
		// if($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443")
		// {
		// 	$currentURL .= ":".$_SERVER["SERVER_PORT"];
		// }
		$currentURL = site_url( '/' );

		$currentURL .= trailingslashit( basename( $_SERVER["REQUEST_URI"] ) );
		
		return $currentURL;
	}
	/* end sfmgetCurrentURL() */
   
	/* delete record on user deletetion */ 
	public function sfmDeleteFeed($user_id)
	{
		global $wpdb;
		$wpdb->query('DELETE FROM '.$wpdb->prefix.$this->SFM_REDIRECTION_TABLE.' WHERE feed_type="author_rss" AND id_on_blog="'.$user_id.'"');
	}
	/* end sfmDeleteFeed() */
	
	/* delete record on category deletetion */ 
	public function sfmDeleteCatFeed($catId)
	{
		global $wpdb;		
		$wpdb->query('DELETE FROM '.$wpdb->prefix.$this->SFM_REDIRECTION_TABLE.' WHERE feed_type="category_rss" AND id_on_blog="'.$catId.'"');
	}
	/* end sfmDeleteCatFeed() */
	
	/* fetch messages  */
	public function sfmProcessFeeds()
	{
		if ( !wp_verify_nonce( $_POST['nonce'], "sfmProcessFeeds")) {
	      echo  json_encode(array("wrong_nonce")); exit;
	   	}
	   	if(!current_user_can('manage_options')){ echo json_encode(array('res'=>'not allowed'));die(); }
		if(isset($_POST['feed_id']))
		{
			// $curl = curl_init();  
			// curl_setopt_array($curl, array(
			// 	CURLOPT_RETURNTRANSFER => 1,
			// 	CURLOPT_URL => $this->SFM_SETUP_URL.sanitize_text_field($_POST['feed_id'])."/Y",
			// 	CURLOPT_USERAGENT => 'sf rss request',
			// 	CURLOPT_POST => 0      
			// ));
			// $resp = curl_exec($curl);
			// curl_close($curl);
			// echo "done"; exit;
			
			$response = wp_remote_get($this->SFM_SETUP_URL.sanitize_text_field($_POST['feed_id'])."/Y");
			$resp = json_decode(wp_remote_retrieve_body($response),false);
			echo "done"; exit;

		}
		else
		{
			echo  "wrong feedid"; exit;
		}
	}
	
	public function sfmHeaderMeta()
	{
		global $wpdb;
		$getFeedsData = $wpdb->get_results('SELECT *  from '.$wpdb->prefix.$this->SFM_REDIRECTION_TABLE." where redirect_status=1",ARRAY_A);
		if(!empty($getFeedsData))
		{
			foreach($getFeedsData as $fData)
			{
				if(!empty($fData['sf_feedid']) && !empty($fData['verification_code']))
				{
					//$fData['sf_feedid'] = base64_decode($fData['sf_feedid']);
					echo ' <meta name="follow.it-verification-code-'.$fData['sf_feedid'].'" content="'.$fData['verification_code'].'"/>';
				}
			}
		}
	}
	
	/*Check Feed type in our table*/
	public function checkFeedExist($feedType, $webUrl=1)
	{
		global $wpdb;
		$table = $wpdb->prefix.'sfm_redirects';
		if($webUrl == 1)
		{
			$data = $wpdb->get_row("select rid from $table where feed_type='$feedType'");
		}
		else
		{
			$data = $wpdb->get_row("select rid from $table where feed_type='$feedType' AND feedSetup_url='$webUrl'");
		}
		//var_dump(array("web_url"=>$data));
		if(!empty($data) && !empty($data->rid))
		{
			return $data->rid;
		}
		else
		{
			return false;
		}
	}
}
/* end of Class */