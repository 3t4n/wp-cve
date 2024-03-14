<?php


class AalLink
{
	var $id;
    var $link;
    var $keywords;
    var $medium;
    var $meta;
    var $hooks = array();


	function __construct($id,$link,$keywords,$medium,$meta,$stats) {
		
		$this->id = $id;
		$this->link = $link;
		$this->keywords = $keywords;
		$this->medium = $medium;
		$this->meta = $meta;
		$this->stats = $stats;

	}
	
	static function showAll($medium = '') {
			global $wpdb;
			$table_name = $wpdb->prefix . "automated_links";	
			
			//Order-sort
			$orderby = strtolower(filter_input(INPUT_GET, 'aalorder', FILTER_SANITIZE_SPECIAL_CHARS)); // $_GET['aalorder'];
			$sortby = strtoupper(filter_input(INPUT_GET, 'aalsort', FILTER_SANITIZE_SPECIAL_CHARS)); // $_GET['aalsort'];
		$aallinksperpage = strtolower(filter_input(INPUT_GET, 'lp', FILTER_SANITIZE_SPECIAL_CHARS));
		$aallp = ''; 
		if(isset($aallinksperpage) && $aallinksperpage && is_numeric($aallinksperpage) && $aallinksperpage>0 && $aallinksperpage<10000) $aallp = '&lp=' . $aallinksperpage;			
			
			
			if($orderby != 'keywords' && $orderby != 'id' && $orderby != 'url') $orderby = '';
			if($sortby != 'ASC' && $sortby != 'DESC') $sortby = '';
			$ordersql = '';
			if($orderby) {
				if($orderby == 'url') $ordersql = " ORDER BY link"; 	
					else $ordersql = " ORDER BY ". $orderby; 	
					
			}
			if($sortby) $ordersql .= " ". $sortby;
			else 	$ordersql = " ORDER BY id"; 
			//end order-sort
			
			//pagination
			
			$linkspage = 1;
			$linkspage = (int)$linkspage;
			$linkspage = filter_input(INPUT_GET, 'linkspage', FILTER_SANITIZE_SPECIAL_CHARS); // $_GET['linkspage'];
			if(!isset($linkspage) || !$linkspage || !is_numeric($linkspage)) $linkspage = 1;
			$rowsonpage = 100;
			if(isset($aallinksperpage) && $aallinksperpage && is_numeric($aallinksperpage) && $aallinksperpage>0 && $aallinksperpage<10000) $rowsonpage = $aallinksperpage;
			$paginationsql = '';
			$paginationhtml = '';
			$offset = 0;
			$num_links = $wpdb->get_var('SELECT COUNT(*) FROM '.  $table_name);
			if($num_links>$rowsonpage) {
				$offset = ($linkspage - 1) * $rowsonpage; 
				$paginationsql = ' LIMIT '. $rowsonpage .' OFFSET '. $offset;
				
				$numberpages = ceil($num_links / $rowsonpage);
				for($ip=1;$ip<=$numberpages;$ip++) {
					if($ip == $linkspage) {
						$paginationhtml .= '&nbsp;&nbsp;'. $ip .'&nbsp;&nbsp;';
					}
					else {
						$keeporder = '';
						if(isset($orderby) && $orderby) $keeporder .= '&aalorder='. $orderby;
						if(isset($sortby) && $sortby) $keeporder .= '&aalsort='. $sortby;
						$paginationhtml .= '&nbsp;&nbsp;<a href="?page=aal_topmenu'. $keeporder . $aallp .'&linkspage='. $ip .'" >'. $ip .'</a>&nbsp;&nbsp;';
					}				
				
				}
				
			}
			else {
				$paginationsql = '';
				$paginationhtml = '';
			}
		
			
			//end pagination
			
			
			
			
			$myrows = $wpdb->get_results( "SELECT * FROM ". $table_name . $ordersql . $paginationsql);

			if($myrows) {
        	 foreach($myrows as $row) {
				
				$link = new AalLink($row->id,$row->link,$row->keywords,$row->medium,$row->meta,$row->stats);
				$link->display();
            
             } 	

				//pagination display
				
				echo $paginationhtml;
				//end pagination display             
             
             
            }
          else {
          
          	echo '<div>Add some links using the form above</div>';
          
          }
		
	}	
	
	

	function display() {
		$meta = json_decode($this->meta);
		if(!is_object($meta)) {
			$meta = new StdClass();
			$meta->title = '';		
			$meta->samelink = '';
		
		}
		else {
			if(!isset($meta->samelink) || !is_numeric($meta->samelink) ) $meta->samelink = '';		
		}
		
		if($this->stats == 'disabled' ) $disabledcheck = 'CHECKED'; else $disabledcheck = '';
		
		if(isset($meta->disclosureoff) && $meta->disclosureoff == 'off' ) $disclosureoffcheck = 'CHECKED'; else $disclosureoffcheck = '';
		
		if(get_option('aal_iscloacked') ) {
					global $wp_rewrite; 
					$keys = explode(',',$this->keywords);
					$cloakurl = get_option('aal_cloakurl');
					if(!$cloakurl || !is_string($cloakurl)) $cloakurl = 'goto';
						if($wp_rewrite->permalink_structure) 
							$link = get_option( 'home' ) . "/". $cloakurl ."/" . $this->id . "/" . wpaal_generateSlug($keys[0]);
						else $link = get_option( 'home' ) . "/?". $cloakurl ."=" . $this->id;						
		
		
		}
		
		
 		?>
 		

 		
            <form name="edit-link-<?php echo $this->id; ?>" method="post">
                  <input value="<?php echo $this->id; ?>" name="edit_id" type="hidden" />
                  
                  <input type="hidden" name="aal_edit" value="ok" />
                                                
                  <?php
                  if (function_exists('wp_nonce_field')) wp_nonce_field('WP-auto-affiliate-links_edit_link');
                  ?>
                  <li style="" class="aal_links_box">
                  <input type="checkbox" name="aal_massids[]" value="<?php echo $this->id; ?>" />
                       Link: <input style="margin: 5px 10px;width: 32%;" type="text" name="aal_link" value="<?php echo $this->link; ?>" />
                       Keywords: <input style="margin: 5px 10px;width: 32%;" type="text" name="aal_keywords" value="<?php echo $this->keywords; ?>" />
							<a href="javascript:;" class="aal_edit_show_advanced" >Show advanced options</a>			
							<a href="javascript:;" class="aal_edit_hide_advanced" style="display: none;">Hide advanced options</a>					

                        <div class="aal_edit_advanced" id="edit_advanced_<?php echo $this->id; ?>" style="display:none; margin-left: 25px;">
                         ID: <span style="font-weight: bold; margin-right: 15px;"><?php echo $this->id; ?></span>
                         Disabled: <input type="checkbox" name="aal_disabled" <?php echo $disabledcheck; ?> />                       
                      	 Title: <input style="margin: 5px 10px;width: 10%;" type="text" name="aal_title" value="<?php echo $meta->title; ?>" />
                      	 Custom same link limit: <input style="margin: 5px 10px;width: 10%;" type="text" name="aal_samelinkmeta" value="<?php echo $meta->samelink; ?>" />
                      	 Don't show link disclosure <input type="checkbox" name="aal_disclosureoff" <?php echo $disclosureoffcheck; ?> value="off" />  
                      	 <span id="urlcheck_<?php echo $this->id; ?>" class="aal_urlvalid"></span>
                      	 <a href="javascript:;" class="aalCheckURL button-primary" style="margin: 5px 12px;" type="button" name="aal_checkurl"  />Check URL</a>
								<?php if(get_option('aal_iscloacked') ) { ?>								 
								 <br /><br />
								 Cloaked link: <input type="text" id="aal-cloak-<?php echo $this->id; ?>" style="width: 50%" readonly value="<?php echo $link; ?>" />								
								<?php } ?>
								              
                     </div>
                     
                   <?php /* <input  class="button-primary" style="margin: 5px 2px;" type="submit" name="ed" value="Update" /> --> */ ?>
                			<span class="spinner aal_spinner"></span>
                         <a href="#" id="<?php echo $this->id; ?>" class="aalUpdateLink button-primary">Update</a>
                        <a href="#" id="<?php echo $this->id; ?>" class="aalDeleteLink button-primary">Delete</a>
                        <?php if(get_option('aal_iscloacked') ) { ?>
                        	<a href="javascript:;" class="aal-copy-cloak button-primary" data-id="<?php echo $this->id; ?>" onclick="aalCopyCloak(this);">Copy link</a>
                        <?php } ?>
                      <div class="aal_clear"></div>
                      <hr /><hr /> 
                  </li>    
</form>

                                            
         <?php		
		
		
	}

}


function aalGetLink($id) {
	
		if(!$id) return false;	
		global $wpdb;
		$table_name = $wpdb->prefix . "automated_links";	
		$myrows = $wpdb->get_results( "SELECT * FROM ". $table_name ." WHERE id='". $id ."' ");
		
		$link = AalLink($id,$link,$keyword,$medium);
	
	
}

function aalGetLinkByUrl($url) {
		
		if(!$url) return false;
		global $wpdb;
		$table_name = $wpdb->prefix . "automated_links";	
		$myrows = $wpdb->get_results( "SELECT * FROM ". $table_name ." WHERE link='". $url ."' ");
		
		$link = AalLink($id,$link,$keyword,$medium);
	
	
}


?>