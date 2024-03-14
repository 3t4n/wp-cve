<?php
   class stmsitemap
   {
      function __construct()
      {
         global $wpdb;
         $this->db_prefix = $wpdb->prefix;
         $this->starttime = $this->get_microtime();

      }


      function build()
      {
         set_time_limit(0);  
         $mem_start = $this->memory_usage();
         
         $output = '<?xml version="1.0" encoding="UTF-8"?>
         <urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
         <url>
         <loc>'.get_settings('home').'</loc>
         <lastmod>'.date('c').'</lastmod>
         <changefreq>daily</changefreq>
         <priority>'.get_option('qHomepage').'</priority>
         </url>';                     
         $sql = mysql_query("select ID,post_modified from ".$this->db_prefix."posts where post_status = 'publish' and post_type = 'post' or post_type = 'page' order by post_date desc");
         while($array = mysql_fetch_assoc($sql)){
            $output .= '<url>
            <loc>'.get_permalink($array['ID']).'</loc>
            <lastmod>'.date('c',strtotime($array['post_modified'])).'</lastmod>
            <changefreq>monthly</changefreq>';
            switch($array['post_type']){
               case 'post';$output .= '<priority>'.get_option('qPosts').'</priority>';break;
               case 'page';$output .= '<priority>'.get_option('qPages').'</priority>';break;

            }
            $output .= '</url>';
         }
         $categories = get_categories(array());
         foreach($categories as $category){
            $output .= '<url>
            <loc>'.get_category_link($category->term_id).'</loc>		
            <lastmod>'.date('c').'</lastmod>
            <changefreq>daily</changefreq>
            <priority>'.get_option('qHomepage').'</priority>
            </url>';
         }
         $tags = get_tags(array());
         foreach($tags as $tag){
            $output .= '<url>
            <loc>'.get_tag_link($tag->term_id).'</loc>		
            <lastmod>'.date('c').'</lastmod>
            <changefreq>daily</changefreq>
            <priority>'.get_option('qTags').'</priority>
            </url>';
         }        
         $output .= '</urlset>';
         $mem_end = $this->memory_usage();
         settings_fields( 'stm_sitemap-settings-group' );
         $filename = 'sitemap';
         file_put_contents(ABSPATH.'/'.$filename.'.xml',$output);
         if(get_option('qzip')=='on'){
            $this->gzip_sitemap($filename,$output);
            $filename = $filename.'.xml.gz';
         }else $filename = $filename.'.xml';
         $filename = get_option('siteurl').'/'.$filename;
         if(get_option('qgoogle')=='on')$this->ping_google($filename);
         if(get_option('qask')=='on')$this->ping_ask($filename);
         if(get_option('qbing')=='on')$this->ping_bing($filename);
         if(get_option('qyandex')=='on')$this->yandex_ping($filename);
         
         return '<p>Memory usage:'.($mem_end - $mem_start).' Mbyte<br />
         Build time:'.($this->get_microtime()-$this->starttime).' sec
         </p>';
      }

      function gzip_sitemap($filename,$data)
      {
         file_put_contents(ABSPATH.'/'.$filename.'.xml.gz',gzdeflate($data));
      }

      function ping_google($url)
      {
         $url = 'http://www.google.com/webmasters/sitemaps/ping?sitemap='.urlencode($url);
         $this->open_url($url);
      } 
      function ping_ask($url)
      {
         $url = 'http://submissions.ask.com/ping?sitemap='.urlencode($url);
         $this->open_url($url);
      }
      function ping_bing($url)
      {
         $url = 'http://www.bing.com/webmaster/ping.aspx?siteMap='.urlencode($url);
         $this->open_url($url);
      }   
      function yandex_ping() {
         $url = 'http://blogs.yandex.ru/pings/?status=success&url='.urlencode($url);
         $this->open_url($url);         
      }                                                             

      function open_url($url)
      {
         if(!function_exists('curl_init')){
            file_get_contents($url);
            return;
         }
         #elinditjuk a  cURL sessiont
         $ch = curl_init();
         #megadjuk a beallitasokat
         #az megnyitni kivant url
         curl_setopt($ch, CURLOPT_URL, $utl);
         #a valaszt nem akarjuk kozvetlenul output-olni
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         #vegrehajtjuk a kerest
         $result= curl_exec($ch);
         #lezarjuk a session-t hogy felszabaditsuk a rendszer-eroforrasokat.
         curl_close($ch);
      }

      function memory_usage() {
         if(function_exists("memory_get_peak_usage")) {
            return round(memory_get_peak_usage(true) / 1024 / 1024,2);
         } else if(function_exists("memory_get_usage")) {
               return round(memory_get_usage(true) / 1024 / 1024,2);
            }
      }

      function get_microtime()
      {
         list($usec, $sec) = explode(" ", microtime());
         return ((float)$usec + (float)$sec);
      }   
      /**/
   }
?>