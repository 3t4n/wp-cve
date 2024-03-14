<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function seometatag_xmlsitemap() {
    echo "<h2>" . __( 'XML Sitemap', 'menu-seometatags' ) . "</h2>";
     if(!empty($_POST) and isset($_POST['save'])){
         $priority = array();
         $excl_array = array('option_page','action','_wpnonce','_wp_http_referer'); 
         foreach($_POST as $i => $v){
            if(!in_array($i,$excl_array))$priority[$i] = $v;
         }
         settings_fields( 'stmsitemap-settings-group' );
         update_option('qzip','off');
         update_option('qgoogle','off');
         update_option('qask','off');
         update_option('qbing','off');
         update_option('qyandex','off');
         foreach($priority as $setting => $value){
            update_option( $setting, $value );
            //echo "<br>$setting => $value";            
         }         
      }  
   ?>
   <div class="wrap">
      <div class="postbox" style="width:100%;">
         <h3 class="hndle" style="padding: 5px;">Actions</h3>
         <div class="inside">
            <?php
               wp_nonce_field('update-options');

               if(isset($_POST['build'])){
                  set_time_limit(0);
                  require_once(dirname(__FILE__).'/stmsitemap.php');
                  $sitemap = new stmsitemap();
                  echo $sitemap->build();
                  flush();
               }else{
                  $filename = 'sitemap';
                  if(file_exists(ABSPATH.'/'.$filename.'.xml')){
                     $lastbuild = filemtime(ABSPATH.'/'.$filename.'.xml');
                     $link = '<a href="'.get_option('siteurl').'/'.$filename.'.xml" target="_blank">  view</a>';

                     echo '<p><strong>Your sitemap has built: </strong>'.date_i18n('Y.m.d h:i:s',$lastbuild).$link.'</p>';
                  }
               }
            ?>
            <form action="" method="post">
               <p class="submit">
                  <input type="submit" class="button-primary" value="<?php _e('Build Sitemap') ?>" name="build"/>
               </p>
            </form>
         </div>
      </div>

      <form method="post" action="">
         <?php settings_fields( 'stmsitemap-settings-group' ); ?>

         <div class="postbox" style="width:100%;">
            <h3 class="hndle" style="padding: 5px;">Settings</h3>
            <div class="inside">
               <ul>
                  <li><label>Gzip sitemap? <input type="checkbox" name="qzip"
                           <?php if(get_option('qzip')=='on')echo ' checked="checked" ' ?>
                           /></label></li>
                  <li><label>Ping Google? <input type="checkbox" name="qgoogle"
                           <?php if(get_option('qgoogle')=='on')echo ' checked="checked" ' ?>
                           /></label></li>
                  <li><label>Ping Ask.com? <input type="checkbox" name="qask"
                           <?php if(get_option('qask')=='on')echo ' checked="checked" ' ?>
                           /></label></li>
                  <li><label>Ping Bing? <input type="checkbox" name="qbing"
                           <?php if(get_option('qbing')=='on')echo ' checked="checked" ' ?>
                           /></label></li>
                  <li><label>Ping Yandex? <input type="checkbox" name="qyandex"
                           <?php if(get_option('qyandex')=='on')echo ' checked="checked" ' ?>
                           /></label></li>
                  
               </ul>
            </div>
         </div>

         <div class="postbox" style="width:100%;">
            <h3 class="hndle" style="padding: 5px;">Priority</h3>
            <div class="inside">
               <ul>
                  <?php
                     $a = (float)0.0;

                     $val = get_option('qHomepage');
                     echo "<li><label><select name=\"qHomepage\">";
                     for($a=0.0; $a<=1.0; $a+=0.1) {$ov = number_format($a,1,".","");echo '<option value="'.$ov.'"';if($ov == $val)echo ' selected="selected" '; echo '>'.$ov.'</option>';}
                     echo "</select>Homepage</label></li>";

                     $val = get_option('qPosts');
                     echo "<li><label><select name=\"qPosts\">";
                     for($a=0.0; $a<=1.0; $a+=0.1) {$ov = number_format($a,1,".","");echo '<option value="'.$ov.'"';if($ov == $val)echo ' selected="selected" '; echo '>'.$ov.'</option>';}
                     echo "</select>Posts</label></li>";

                     $val = get_option('qPages');
                     echo "<li><label><select name=\"qPages\">";
                     for($a=0.0; $a<=1.0; $a+=0.1) {$ov = number_format($a,1,".","");echo '<option value="'.$ov.'"';if($ov == $val)echo ' selected="selected" '; echo '>'.$ov.'</option>';}
                     echo "</select>Pages</label></li>";

                     $val = get_option('qCategories');
                     echo "<li><label><select name=\"qCategories\">";
                     for($a=0.0; $a<=1.0; $a+=0.1) {$ov = number_format($a,1,".","");echo '<option value="'.$ov.'"';if($ov == $val)echo ' selected="selected" '; echo '>'.$ov.'</option>';}
                     echo "</select>Categories</label></li>";

                     $val = get_option('qArchives');
                     echo "<li><label><select name=\"qArchives\">";
                     for($a=0.0; $a<=1.0; $a+=0.1) {$ov = number_format($a,1,".","");echo '<option value="'.$ov.'"';if($ov == $val)echo ' selected="selected" '; echo '>'.$ov.'</option>';}
                     echo "</select>Archives</label></li>";

                     $val = get_option('qTags');
                     echo "<li><label><select name=\"qTags\">";
                     for($a=0.0; $a<=1.0; $a+=0.1) {$ov = number_format($a,1,".","");echo '<option value="'.$ov.'"';if($ov == $val)echo ' selected="selected" '; echo '>'.$ov.'</option>';}
                     echo "</select>Tags</label></li>";

                     $val = get_option('qAuthor');
                     echo "<li><label><select name=\"qAuthor\">";
                     for($a=0.0; $a<=1.0; $a+=0.1) {$ov = number_format($a,1,".","");echo '<option value="'.$ov.'"';if($ov == $val)echo ' selected="selected" '; echo '>'.$ov.'</option>';}
                     echo "</select>Author</label></li>";                  
                  ?>
               </ul>
            </div>
         </div>

         <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" name="save"/>
         </p>

      </form>
   </div>
<?php } 

register_activation_hook(__FILE__, 'register_csmsettings');
register_deactivation_hook(__FILE__, 'stm_deactivation');

function register_csmsettings() {	
      $settings = array(
      'qHomepage' => '1.0','qPosts' => '0.8','qPages' => '0.8','qCategories' => '0.6','qArchives' => '0.8','qTags' => '0.6','qAuthor' => '0.3',
      'qfilename' => 'sitemap',
      'qzip' => 'on',
      'qgoogle' => 'on',
      'qask' => 'on',
      'qbing' => 'on'
      );
      foreach($settings as $setting => $value){
         register_setting( 'stmsitemap-settings-group', $setting );
         if(!get_option($setting)){
            update_option( $setting, $value );
         }
      }                                                                                    
      wp_schedule_event(mktime(0,0,0,date('m'),$day,date('Y')), 'daily', 'stm_cron');        
   }

   function stm_deactivation() {
      wp_clear_scheduled_hook('stm_cron');
   }
   
    function stm_sitemap()
   {
      set_time_limit(0);
      require_once(dirname(__FILE__).'/stmsitemap.php');
      $s = new stmsitemap;
      $s->build();
      flush();
   }    
   add_action('stm_cron', 'stm_sitemap');
   
   add_action('${new_status}_$post->post_type','qbuild',100,1);
   add_action('csm_build_cron', 'qbuild',100,1);
   
    

