<?php

/**
 * Custom Post Types (Press_release)
 *
**/

# Exit if accessed directly
if(!defined("PRWIREPRO_EXEC")){
	die();
}


 /**
  * Dispaly front-end for custom post press_release
  * 
  * @package Press Release Distribution
  * @author PR Wire Pro
  * @version 1.1
  * @access public
  * 
  */
class PressRelease_TheContent{

	/**
	 * Option Plugin
	 * @access private
	 **/
	private $options;

	/**
	 * Instance of a class
	 * 
	 * @access public
	 * @return void
	 **/
	public function __construct(){
		$this->options = get_option("press_release_distribution_plugins"); // get current option

		// TODO: EDIT SINGLE POST INIT
		/**
		* Single Post Variable
		*/

		// get post id
		$this->postID = get_the_ID();
		// get current post
		$post = get_post();
		// element post
		$this->ID = $post->ID;
		$this->post_author = $post->post_author;
		$this->post_date = $post->post_date;
		$this->post_date_gmt = $post->post_date_gmt;
		$this->post_content = $post->post_content;
		$this->post_title = $post->post_title;
		$this->post_excerpt = $post->post_excerpt;
		$this->post_status = $post->post_status;
		$this->comment_status = $post->comment_status;
		$this->ping_status = $post->ping_status;
		$this->post_password = $post->post_password;
		$this->post_name = $post->post_name;
		$this->to_ping = $post->to_ping;
		$this->pinged = $post->pinged;
		$this->post_modified = $post->post_modified;
		$this->post_modified_gmt = $post->post_modified_gmt;
		$this->post_content_filtered = $post->post_content_filtered;
		$this->post_parent = $post->post_parent;
		$this->guid = $post->guid;
		$this->menu_order = $post->menu_order;
		$this->post_type = $post->post_type;
		$this->post_mime_type = $post->post_mime_type;
		$this->comment_count = $post->comment_count;
		$this->filter = $post->filter;
		// get post meta

		// get attachment
		$this->thumbnail_id = get_post_thumbnail_id($this->postID); //get attachment id 

		// get thumbnail
		$this->image_thumbnail = get_the_post_thumbnail(); //with html
		$this->image_thumbnail_src = wp_get_attachment_image_src($this->thumbnail_id);  
		$this->image_large_src = wp_get_attachment_image_src($this->thumbnail_id,"large");  
		$this->image_medium_src = wp_get_attachment_image_src($this->thumbnail_id,"medium"); 
		$this->image_full_src = wp_get_attachment_image_src($this->thumbnail_id,"full");


	}
	/**
	 * Create front-end
	 * 
	 * @param mixed $content 
	 * @access public
	 * @return $string $content
	 **/
	public function Markup($content){
		$new_content = null ;
		//Display file path
		if(PRWIREPRO_DEBUG==true){
			$file_info = null; 
			$file_info .= "<p>You can edit the file below to fix the layout</p>" ; 
			$file_info .= "<div>" ; 
			$file_info .= "<pre style=\"color:rgba(255,0,0,1);padding:3px;margin:0px;background:rgba(255,0,0,0.1);border:1px solid rgba(255,0,0,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">%s:%s</pre>" ; 
			$file_info .= "</div>" ; 
			printf($file_info,__FILE__,__LINE__);
		}

		// TODO: EDIT SINGLE POST LAYOUT

		$new_content .= "<div class=\"prwirepro-row\">" ; 
		$new_content .= "<div class=\"prwirepro-col-md-4\">" ; 
		$new_content .= "</div><!-- .//prwirepro-col-md-4 -->" ; 
		$new_content .= "<div class=\"prwirepro-col-md-8\">" ; 
		$new_content .= "<dl class=\"prwirepro-dl-horizontal\">" ; 
		$new_content .= "<dl>" ; 
		$new_content .= "</div><!-- .//prwirepro-col-md-8 -->" ; 
		$new_content .= "</div><!-- .//prwirepro-row -->" ; 

		// TODO: VIEW ALL VARIABLE
		// start ~ please remove for distribution
		if(PRWIREPRO_DEBUG==true){

			$new_content .= "<table style=\"font-size:12px;color:green;border-collapse: collapse;\">" ; 
			$new_content .= "<tr><th>Type</th><th>Code</th><th>Current Value</th></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->postID</pre></td><td>".$this->postID."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->ID</pre></td><td>".print_r($this->ID,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->post_author</pre></td><td>".print_r($this->post_author,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->post_date</pre></td><td>".print_r($this->post_date,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->post_date_gmt</pre></td><td>".print_r($this->post_date_gmt,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->post_content</pre></td><td>".print_r($this->post_content,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->post_title</pre></td><td>".print_r($this->post_title,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->post_excerpt</pre></td><td>".print_r($this->post_excerpt,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->post_status</pre></td><td>".print_r($this->post_status,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->comment_status</pre></td><td>".print_r($this->comment_status,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->ping_status</pre></td><td>".print_r($this->ping_status,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->post_password</pre></td><td>".print_r($this->post_password,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->post_name</pre></td><td>".print_r($this->post_name,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->to_ping</pre></td><td>".print_r($this->to_ping,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->pinged</pre></td><td>".print_r($this->pinged,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->post_modified</pre></td><td>".print_r($this->post_modified,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->post_modified_gmt</pre></td><td>".print_r($this->post_modified_gmt,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->post_content_filtered</pre></td><td>".print_r($this->post_content_filtered,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->post_parent</pre></td><td>".print_r($this->post_parent,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->guid</pre></td><td>".print_r($this->guid,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->menu_order</pre></td><td>".print_r($this->menu_order,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->post_type</pre></td><td>".print_r($this->post_type,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->post_mime_type</pre></td><td>".print_r($this->post_mime_type,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->comment_count</pre></td><td>".print_r($this->comment_count,true)."</td></tr>";
			$new_content .= "<tr><td>post</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->filter</pre></td><td>".print_r($this->filter,true)."</td></tr>";
			$new_content .= "<tr><td>thumbnail</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->thumbnail_id</pre></td><td>".$this->thumbnail_id."</td></tr>";
			$new_content .= "<tr><td>thumbnail</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->image_thumbnail</pre></td><td>".$this->image_thumbnail."</td></tr>";
			$new_content .= "<tr><td>thumbnail</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->image_thumbnail_src</pre></td><td>".$this->image_thumbnail_src."</td></tr>";
			$new_content .= "<tr><td>thumbnail</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->image_large_src</pre></td><td>".$this->image_large_src."</td></tr>";
			$new_content .= "<tr><td>thumbnail</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->image_medium_src</pre></td><td>".$this->image_medium_src."</td></tr>";
			$new_content .= "<tr><td>thumbnail</td><td><pre style=\"color:rgba(0,0,255,1);padding:3px;margin:0px;background:rgba(0,0,255,0.1);border:1px solid rgba(0,0,255,0.5);font-size:11px;font-family:monospace;white-space:pre-wrap;\">\$this->image_full_src</pre></td><td>".$this->image_full_src."</td></tr>";
			$new_content .= "</table>" ; 
			// end
		}
		$new_content .= "<div class=\"prwirepro-panel prwirepro-panel-default\">" ;
		$new_content .= "<div class=\"prwirepro-panel-body\">" ;
		$new_content .= $content ;
		$new_content .= "</div>" ;
		$new_content .= "</div>" ;
		return $new_content;
	}
}
