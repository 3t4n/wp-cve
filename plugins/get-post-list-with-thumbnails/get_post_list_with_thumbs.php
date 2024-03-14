<?php
/*
Plugin Name: Get Post List With Thumbnails
Plugin URI: http://www.wpworking.com/
Description:Displays a post list and custom size thumbnails(for the post 1st attached image), linked to each post permalink. Use as multiples widget or shortcode.
Version: 10.0.2
Author: Alvaro Neto
Author URI: http://wpworking.com/
License: GPL2
*/
/*  Copyright 2013  Alvaro Neto  (email : wpworking@wpworking.com
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
//
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//wp_enqueue_script('gplt_ajaxhandler', '/wp-content/plugins/get-post-list-with-thumbnails/ajaxhandler.js');
function wpb_track_post_views ($post_id) {
    if ( !is_single() ) return;
    if ( empty ( $post_id) ) {
        global $post;
        $post_id = $post->ID;    
    }
    wpb_set_post_views($post_id);
}
add_action( 'wp_head', 'wpb_track_post_views');
function wpb_set_post_views($postID) {
    $count_key = 'wpb_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
//To keep the count accurate, lets get rid of prefetching
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
function load_scripts_gplwt() {  
?> 
<script src="/wp-content/plugins/get-post-list-with-thumbnails/ajaxhandlergplwt.js" type="text/javascript"></script>
<script type="text/javascript"> 
	function goprocess_gplwt(elemid,goval){
		consulta_gplwt(elemid,1+'&v='+goval,'/wp-content/plugins/get-post-list-with-thumbnails/ajaxgplwt.php');	
	}
</script> 
<?php  
} 
add_action( is_admin() ? 'admin_head' : 'wp_head', 'load_scripts_gplwt' ); 
//
//
//add_action('plugins_loaded', 'goprocess_javascript');
//function goprocess_javascript() {
//}
//
add_shortcode('gplt','gplt_short');
//
function gplt_short($atts, $content = null){
   extract(shortcode_atts(array(
        'nopsts' =>'',
        'nocats' =>'',
        'ptype' => 'post',
        'ttpos' => 'b',
		'dexcp' => false,
		'orient' => 'v',
        'imgo' => false,
        'ttgo' => false,
        'dtgo' => false,
        'dtfrm' => 1,
        'categ' => '',
        'postnr' => 20,
        'linn' => 3,		
		'divwid' => 300,
        'tbwid' => 40,
        'tbhig' => 40,
		'cp' => 4,
		'cs' => 4,
		'lwt' => 1,
		'tte' => '',
		'sptb' => false,
		'tgtb' => false,
		'ordm' => 'DESC',
		'ordf' => 'ID',
		'metk' => '',
		'mett' => 'n',
		'pgin' => '',
		'pgnm' => 1,
		'pgmx' => '',
		'gptb' => false,
		'ech' => false		
    ), $atts));
	ob_start();			
    //echo $nocats;
    //exit;
	$gplwtres=getPostListThumbs($nopsts,$nocats,$ptype,$ttpos,$dexcp,$orient,$imgo,$ttgo,$dtgo,$dtfrm,$categ,$postnr,$linn,$divwid,$tbwid,$tbhig,$cp,$cs,$lwt,$tte,$sptb,$tgtb,$ordm,$ordf,$metk,$mett,$pgin,$gptb,$pgnm,$pgmx,$ech);
	echo $gplwtres;
	$gplwt_string = ob_get_contents();
	ob_end_clean();
	return $gplwt_string;
}
//
/**
 * Add FAQ and support information
 */
add_filter('plugin_row_meta', 'wp_gplwt_plugin_links', 10, 2);
function wp_gplwt_plugin_links ($links, $file)
{
    if ($file == plugin_basename(__FILE__)) {
        $links[] = '<a href="http://www.wpworking.com/shop/" style="color:#ff6600;font-weight:bold;display:block;padding:2px;background-color:#FAEDA4;" target="_blank"><span style="color:blue">Get colors</span> <span style="color:green">and formating</span>, <u>Get <i style="color:#861686">GPLWT</i> PRO!</u></a>';
    }
    return $links;
}
//
function gtpartrat($par,$def)
{
  // echo "par:".$par;
	if($par =='false'){       
        $par = false;
        //echo "teste:".$par;
    }
	if($par =='true'){       
        $par = true;
        //echo "teste:".$par;
    }
    if($par =='' || $par < 0){       
        $par = $def;
        //echo "teste:".$par;
    }
    //echo "teste2:".$par;
    return $par;
}
function gtpartrat2($par,$def)
{
  // echo "par:".$par;
	if($par =='false'){       
        $par = false;
        //echo "teste:".$par;
    }
	if($par =='true'){       
        $par = true;
        //echo "teste:".$par;
    }
    if($par ==''){       
        $par = $def;
        //echo "teste:".$par;
    }
    //echo "teste2:".$par;
    return $par;
}
// display a list of posts with custom size thumbnails, using the post first image
function getPostListThumbs($nopsts='',$nocats='',$ptype='post',$ttpos='b',$dexcp=false,$orient='v',$imgo=false,$ttgo=false,$dtgo=false,$dtfrm=1,$categ='',$postnr=20,$linn=3,$divwid=300,$tbwid=40,$tbhig=40,$cp=4,$cs=4,$lwt=1,$tte='',$sptb=false,$tgtb=false,$ordm='',$ordf='ID',$metk='',$mett='n',$pgin='',$gptb=false,$pgnm='',$pgmx='',$ech=true,$gplwtdiv=''){	
    //echo "testando";
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
    //echo $nocats;
    //exit;
	$nopsts = gtpartrat2($nopsts,'');
    $nocats = gtpartrat2($nocats,'');
    $ptype = gtpartrat($ptype,'post');	
	$dexcp = gtpartrat($dexcp,false);
    $ttpos = gtpartrat($ttpos,'b');
	$orient = gtpartrat($orient,'v');
    $imgo = gtpartrat($imgo,false);
	if($imgo==true):$ppimgo='true';else:$ppimgo='false';endif;
	$ttgo = gtpartrat($ttgo,false);
	if($ttgo==true):$ppttgo='true';else:$ppttgo='false';endif;
    $dtgo = gtpartrat($dtgo,false);
	if($dtgo==true):$ppdtgo='true';else:$ppdtgo='false';endif;
	$dtfrm = gtpartrat($dtfrm,1);
	$categ = gtpartrat($categ,'');
    $postnr = gtpartrat($postnr,20);
    $linn = gtpartrat($linn,3);
    $tbwid = gtpartrat($tbwid,40);
    $tbhig = gtpartrat($tbhig,40);
	$lwt = gtpartrat($lwt,1);
	$cp = gtpartrat($cp,4);
	$cs = gtpartrat($cs,4);
	$tte = gtpartrat($tte,'');
	if($tte==""):$pptte=0;endif;
	$sptb = gtpartrat($sptb,false);
	if($sptb==true):$ppsptb='true';else:$ppsptb='false';endif;
	$tgtb = gtpartrat($tgtb,false);
	if($tgtb==true):$pptgtb='true';else:$pptgtb='false';endif;
	$ordm = gtpartrat($ordm,'DESC');
	$ordf = gtpartrat($ordf,'ID');
	$metk = gtpartrat($metk,'');
	if($metk==""):$ppmetk=0;endif;
	$mett = gtpartrat($mett,'n');
	$pgin = gtpartrat($pgin,'');
	$pgnm = gtpartrat($pgnm,1);
	$pgmx = gtpartrat($pgmx,'');
	$gptb = gtpartrat($gptb,false);
	$ech = gtpartrat($ech,true);	
	$gplwtdiv = gtpartrat($gplwtdiv,'');
	/*
	$gplwtdiv
	$arrglpwt = array('orient' => $orient,
        'imgo' => $imgo,
        'ttgo' => $ttgo,
        'dtgo' => $dtgo,
        'dtfrm' => $dtfrm,
        'categ' => $categ,
        'postnr' => $postnr,
        'linn' => $linn,
        'tbwid' => $tbwid,
        'tbhig' => $tbhig,
		'cp' => $cp,
		'cs' => $cs,
		'lwt' => $lwt,
		'tte' => $tte,
		'sptb' => $sptb,
		'tgtb' => $tgtb,
		'ordm' => $ordm,
		'ordf' => $ordf,
		'metk' => $metk,
		'mett' => $mett,
		'ech' => $ech);*/
    // $categ = category name - not required
    // $postnr = number of posts you want to display - not required    
	/*$parflw  = "orient='".$orient."'&imgo=".$ppimgo."&ttgo=".$ppttgo."&dtgo=".$ppdtgo."&dtfrm=".$dtfrm."&categ='".$categ."'&";
	$parflw .= "postnr=".$postnr."&linn=".$linn."&tbwid=".$tbwid."&tbhig=".$tbhig."&lwt=".$lwt."&cp=".$cp."&";
	$parflw .= "cs=".$cs."&tte='".$pptte."'&sptb=".$ppsptb."&tgtb=".$pptgtb."&ordm='".$ordm."'&";
	$parflw .= "ordf='".$ordf."'&metk='".$ppmetk."'&mett='".$mett."'";*/
	$parflw  = $nopsts.",".$nocats.",".$ptype.",".$ttpos.",".$dexcp.",".$orient.",".$imgo.",".$ttgo.",".$dtgo.",".$dtfrm.",".$categ.",";
	$parflw .= $postnr.",".$linn.",".$divwid.",".$tbwid.",".$tbhig.",".$cp.",";
	$parflw .= $cs.",".$lwt.",".$tte.",".$sptb.",".$tgtb.",".$ordm.",";
	$parflw .= $ordf.",".$metk.",".$mett.",".$pgin;
	// $orient,$imgo,$ttgo,$dtgo,$dtfrm,$categ,$postnr,$linn,$divwid,$tbwid,$tbhig,$cp,$cs,$lwt,$tte,$sptb,$tgtb,$ordm,$ordf,$metk,$mett
	if($pgnm==1 && $gplwtdiv==""):
		$gplwtdiv = "gplwt_container_".uniqid();
	endif;
	$htmlcod = '<div id="'.$gplwtdiv.'">';
	$htmlcod .="<table id='div_postlist' width='".$divwid."' cellpadding='".$cp."' cellspacing='".$cs."'>"."\n";
	//<a href='#' onclick='goprocess('".$arrglpwt[ORIENT]."');'>Teste</a>
	if($tte!="" && $ttpos=="b"):
	$htmlcod .= "<tr><td colspan='".$linn."'><h3 class='widget-title'>".$tte."</h3></td></tr>"."\n";
	endif;
	$htmlcod .= "<tr>"."\n";
    //
	$nextflg = false;
	$prevflg = false;
    //if (have_posts()) :
        global $post;
        $strquery = "post_type=".$ptype;
		
        if($ordf=='meta_value_num'){
            $metk = "wpb_post_views_count";
        }
        //
        if($nopsts!=""){
            $nopstsarr = explode(",",$nopsts);
            //var_dump($nopstsarr);
        }
        if($nocats!=""){
            $strquery .= "&cat=".$nocats;
        }
        //echo $nocats;
        //exit;
        // here we check if the user has chosen a category        
        if($categ!=''){
            $strquery .= "&category_name=". $categ;
        }
        else{
              if($metk!=''){
				$strquery .= "&meta_key=". $metk;
			  }
        }
        //       
		if($metk!=''){
			if($mett!=''){
				if($mett=='n'){
					$strquery .=  "&orderby=meta_value_num";
				}
				else{
					$strquery .=  "&orderby=meta_value";
				}
			}
			if($ordm!=''){
				$strquery .=  "&order=".$ordm;
			}
		}
		else{
			if($ordf=='random'){
				$strquery .=  "&orderby=rand";
			}
			else{
				if($ordm!=''){
					$strquery .=  "&order=".$ordm;
				}
				//
				if($ordf!=''){
					$strquery .=  "&orderby=".$ordf;
				}
			}          
		}
		$gplwt_myposts=get_posts($strquery."&numberposts=".$postnr);
        // $ordf;
		//echo $strquery."&numberposts=".$postnr;
		//echo "teste".sizeof($gplwt_myposts);

       	if($pgin > 0){
            $strquery .= "&posts_per_page=".$pgin."&paged=". $pgnm;			
        }
	else{
	    $strquery .= "&posts_per_page=".$postnr;
	}
		$strquery .= "&post_status=publish";
		//echo $strquery;		
		//echo $strquery;
        // here we get the posts
        // if we want, we can display the number of registers found
        $ctxtr = 0;
		switch($dtfrm){
			case 1:
				$dtdis = 'd/m/y';
			break;
			case 2:
				$dtdis = 'm/d/y';
			break;
		}
		switch($lwt){
			case 1:
				$lwdis = false;
                $lctitw = false;
			break;
			case 2:
				$lwdis = true;
                $lctitw = false;
			break;
            case 3:
                $lwdis = true;
                $lctitw = false;
            break;
		}
		if($tgtb):
			$tgstr = "target='_blank'";
		else:
			$tgstr = "";
		endif;
        //query_posts($strquery);
		//echo $strquery;
		$gplwt_query = new WP_Query($strquery);
		//var_dump($gplwt_query);
		//echo $strquery;
         // here we go in the loop
		$cntgplwt = 0;
        //while (have_posts()) : the_post();
		$ipnr = 0;while ( $gplwt_query->have_posts() && $ipnr < $postnr) : $gplwt_query->the_post();
                    if(is_array($nopstsarr)){
                        if(in_array($post->ID,$nopstsarr)){
                            continue;
                        }                        
                    }
					$cntgplwt = $cntgplwt + 1;
                    // getting the first image of the post to make the thumb
                    $args = array(
                        'post_type' => 'attachment',
                        'numberposts' => -1,
                        'post_status' => null,
                        'post_parent' => $post->ID,
						
						'orderby' => 'ID',
						'order' => 'ASC'
                    );
                    $attachments = get_posts($args);
                    // here we set the variable for the attachment string
                    $imgsrc = "";
                    if ($attachments):
                        // here we take the first image and break the loop
                        foreach ($attachments as $attachment) {
                             // this brings the attachment array $imgobj = wp_get_attachment_image_src($attachment->ID);
                             // if you use the line obove, you can call $imgobj[0] to get the image source
                            // you can set the thumbnail dimensions, here we use 40 x 40
                            $imgsrc = wp_get_attachment_image($attachment->ID, array($tbwid,$tbhig), $icon = false);
                            break;
                        }
                    endif;
					
					if($gptb):
						$thumbgplwt = wp_get_attachment_image_src ( get_post_thumbnail_id ( get_the_ID() ), 'thumbnail' ) ;
						if($thumbgplwt[0]!=""):
							$imgsrc = "<img src=".$thumbgplwt[0]." width=".$tbwid." height=".$tbhig." alt=".get_the_title().">";
						endif;
					endif;
					
                    // here we start to build the return html code
					if($pgnm==1 && $pgin > 0):						
						 $pgmx = ceil(sizeof($gplwt_myposts)/$pgin);
					endif;
										
					if($cntgplwt==1 && $pgin > 0){	
						if($pgnm > 1):
							$pgplnm = $pgnm-1;
							//echo "pag".$pgnm . "de ".$pgmx;		
							//echo "teste".$pgplnm;
							//$pgnm = $pgnm - 1;		
							//$pgin = $pgmx/$pgnm;							
							$parflw_p  = $nopsts.",".$nocats.",".$ptype.",".$ttpos.",".$dexcp.",".$orient.",".$imgo.",".$ttgo.",".$dtgo.",".$dtfrm.",".$categ.",";
							$parflw_p .= $postnr.",".$linn.",".$divwid.",".$tbwid.",".$tbhig.",".$cp.",";
							$parflw_p .= $cs.",".$lwt.",".$tte.",".$sptb.",".$tgtb.",".$ordm.",";
							$parflw_p .= $ordf.",".$metk.",".$mett.",".$pgin.",".$pgplnm.",".$pgmx.",".$gptb.",".$ech.",".$gplwtdiv;
							//echo "teste".$pgplnm;
							$prevflg = true;
							//$htmlcod .= '<tr><td><a href="#" onclick="goprocess_gplwt(\''.$parflw.'\')">Previous</a></td></tr>';
						endif;
						if($pgnm < $pgmx):		
							//echo "pag".$pgnm . "de ".$pgmx;		
							//$pgnm = $pgnm + 1;	
							//$pgin = $pgin + $pgin;								
							$parflw_n  = $nopsts.",".$nocats.",".$ptype.",".$ttpos.",".$dexcp.",".$orient.",".$imgo.",".$ttgo.",".$dtgo.",".$dtfrm.",".$categ.",";
							$parflw_n .= $postnr.",".$linn.",".$divwid.",".$tbwid.",".$tbhig.",".$cp.",";
							$parflw_n .= $cs.",".$lwt.",".$tte.",".$sptb.",".$tgtb.",".$ordm.",";
							$parflw_n .= $ordf.",".$metk.",".$mett.",".$pgin.",".($pgnm+1).",".$pgmx.",".$gptb.",".$ech.",".$gplwtdiv;
							
							$nextflg = true;							
							//$htmlcod .= '<tr><td><a href="#" onclick="goprocess_gplwt(\''.$parflw.'\')">Next</a></td></tr>';
						endif;
					}
                    if($orient=="v"){     
                            if($ctxtr == 0){
                                $htmlcod .= "<tr>"."\n";
                            } 
							$ctxtr = $ctxtr + 1;
							// if the post has at least one image attached, we display it
                               if(!$sptb):
							    $htmlcod .= "<td valign='top'>"."\n";
									if($imgsrc!=""):                           
										$htmlcod .= "<a href='". get_permalink() ."' title='". get_the_title() ."' ".$tgstr.">"."\n";
										$htmlcod .= $imgsrc;
										$htmlcod .= "</a>"."\n";                           
									endif;
                                $htmlcod .= "</td>"."\n";
								endif;
                                /*$htmlcod .= "<td>"."\n";
                                $htmlcod .= "<a href='". get_permalink()."' title='". get_the_title() ."'>";
                                $htmlcod .= get_the_time('d/m/Y');
                                $htmlcod .= "</a>"."\n";
                                $htmlcod .= "</td>"."\n";   
                            $htmlcod .= "</tr>"."\n";     
                            $htmlcod .= "<tr>"."\n";  */                            
                            if(!$dtgo && !$ttgo && !$dexcp && !$imgo){
                                $rexgosw = "style='display:none;'";
                            }
							if($lwdis){
								$htmlcod .= "</tr><tr ".$rexgosw.">"."\n";  
							}
                            if(!$imgo){           
                                $htmlcod .= "<td valign='top'>"."\n";
								if($dtgo):
								$htmlcod .= "<p>".get_the_time($dtdis)."</p>";
								endif;
								if($ttgo):
                                $htmlcod .= "<a href='". get_permalink()."' title='". get_the_title() ."' ".$tgstr.">";
                                $htmlcod .= get_the_title();
                                $htmlcod .= "</a>"."\n";
								endif;
								
								if($dexcp):
								$htmlcod .= "<p class='gplwt_excp'>".get_the_excerpt()."</p>";
								endif;
								
                                $htmlcod .= "</td>"."\n";
                            }
                            $htmlcod .= "</tr>";
                            if($tte!="" && $ttpos=="a"):
                                $htmlcod .= "<tr><td colspan='".$linn."'><h3 class='widget-title'>".$tte."</h3></td></tr>"."\n";
                            endif;
                            $htmlcod .= "<tr>"."\n";  
                    }
                    else{
                            // linn
							if($ctxtr == 0){
                                $htmlcod .= "<tr>"."\n";
                            }      
							$ctxtr = $ctxtr + 1;                      
                            $htmlcod .= "<td valign='top'>"."\n";
                            $htmlcod .= "<table cellpadding='3' cellspacing='3' border='0' width='100%'>"."\n";
                            // if the post has at least one image attached, we display it
                                $htmlcod .= "<tr>"."\n";
                                if(!$sptb):
								$htmlcod .= "<td valign='top'>"."\n";
                            		if($imgsrc!=""):  								                           
										$htmlcod .= "<a href='". get_permalink() ."' title='". get_the_title() ."' ".$tgstr.">"."\n";
										$htmlcod .= $imgsrc;
										$htmlcod .= "</a>"."\n";    
										$htmlcod .= "</td>"."\n";                       
                            		endif;
								endif;
							if($lwdis){
								$htmlcod .= "</tr><tr>"."\n";  
							}
                                /*$htmlcod .= "<td>"."\n";
                                $htmlcod .= "<a href='". get_permalink()."' title='". get_the_title() ."'>";
                                $htmlcod .= get_the_time('d/m/Y');
                                $htmlcod .= "</a>"."\n";
                                $htmlcod .= "</td>"."\n";   
                            $htmlcod .= "</tr>"."\n";     
                            $htmlcod .= "<tr>"."\n";  */   
                            if(!$imgo){  
								$htmlcod .= "<td valign='top'>"."\n";             
                                if($dtgo):
								$htmlcod .= "<p>".get_the_time($dtdis)."</p>";
								endif;
								if($ttgo):
                                $htmlcod .= "<a href='". get_permalink()."' title='". get_the_title() ."' ".$tgstr.">";
                                $htmlcod .= get_the_title();
                                $htmlcod .= "</a>"."\n";
								endif;      
								if($dexcp):
								$htmlcod .= "<p class='gplwt_excp'>".get_the_excerpt()."</p>";
								endif;
								
								$htmlcod .= "</td>"."\n";                         
                            }
							$htmlcod .= "</td>"."\n";
                            $htmlcod .= "</tr>"."\n";
                            if($tte!="" && $ttpos=="a"):
                                $htmlcod .= "<tr><td colspan='".$linn."'><h3 class='widget-title'>".$tte."</h3></td></tr>"."\n";
                            endif;
                            $htmlcod .= "</table>"."\n";
                            $htmlcod .= "</td>"."\n";
                            if($ctxtr == $linn){
                                $htmlcod .= "</tr>"."\n";
                                $ctxtr = 0;
                            }
                    }
        $ipnr++;endwhile;
      /*  else:
            $htmlcod = "<tr>"."\n";
            $htmlcod = "<td>"."\n";
            $htmlcod .= "No registers found."."\n";
            $htmlcod .= "</td>"."\n";
            $htmlcod .= "</tr>"."\n";
        endif;*/
    //endif;
		$htmlcod .= '<tr><td><table cellspacing="0" cellpadding="0"><tr>';
		if($prevflg):
		$htmlcod .= '<td><a href="#'.$gplwtdiv.'" class="gplwt_pvlink" onclick="goprocess_gplwt(\''.$gplwtdiv.'\',\''.$parflw_p.'\')"><</a></td>';
        endif;
		if($nextflg):
		$htmlcod .= '<td><a href="#'.$gplwtdiv.'" class="gplwt_nxlink" onclick="goprocess_gplwt(\''.$gplwtdiv.'\',\''.$parflw_n.'\')">></a></td>';
        endif;
		$htmlcod .= '</tr></table></td></tr>';
		$htmlcod .= "</table></div>";
	if($ech):
    	echo $htmlcod;
	else:
		return $htmlcod;
	endif;
}
function widget_getPostListThumbs($args) {
  extract($args);
  $options = get_option("widget_getPostListThumbs");
  if (!is_array( $options ))
	{
	$options = array(
          'nopsts' => '',  
          'nocats' => '',  
		  'ptype' => 'post',
		  'ttpos' => 'b',
		  'dexcp' => false,
		  
		  'orient' => '',
		  'imgo' => '',
		  'ttgo' => '',
		  'dtgo' => '',
		  'dtfrm' => '',
		  'categ' => '',
		  'postnr' => '',
		  'linn' => '',
		  'divwid' => '',
		  'tbwid' => '',
		  'tbhig'  => '',
		  'cp' => '',
		  'cs' => '',
		  'lwt' => '',
		  'tte' => '',
		  'sptb' => '',
		  'tgtb' => false,
		  'ordm' => '',
		  'ordf' => '',
		  'metk' => '',
		  'mett' => '',
		  
		  'pgin' => '',
		  'gptb' => ''
		  );
  }
 //echo $before_widget;
    echo $before_title;
      echo $options['title'];
    echo $after_title;  
  echo $after_widget;
  //Our Widget Content
    getPostListThumbs($options['nopsts'],$options['nocats'],$options['ptype'],$options['ttpos'],$options['dexcp'],$options['orient'],$options['imgo'],$options['ttgo'],$options['dtgo'],$options['dtfrm'],$options['categ'],$options['postnr'],$options['linn'],$options['divwid'],$options['tbwid'],$options['tbhig'],$options['cp'],$options['cs'],$options['lwt'],$options['tte'],$options['sptb'],$options['tgtb'],$options['metk'],$options['mett'],$options['pgin'],$options['gptb']);
}
function getPostListThumbs_control()
{
  $options = get_option("widget_getPostListThumbs");
  if (!is_array( $options ))
	{
	$options = array(
          'nopsts' => '',
          'nocats' => '',
		  'ptype' => 'post',	
		  'ttpos' => 'b',	
		  'dexcp' => false,
		  
		  'orient' => '',
		  'imgo' => '',
		  'ttgo' => '',
		  'dtgo' => '',
		  'dtfrm' => '',
		  'categ' => '',
		  'postnr' => '',
		  'linn' => '',
		  'divwid' => '',
		  'tbwid' => '',
		  'tbhig' => '',
		  'cp' => '',
		  'cs' => '',
		  'lwt' => '',
		  'tte' => '',
		  'sptb' => '',
		  'tgtb' => false,
		  'ordm' => '',
		  'ordf' => '',
		  'metk' => '',
		  'mett' => '',
		  
		  'pgin' => '',
		  'gptb' => ''
		  );
  }
  if ($_POST['getPostListThumbs-Submit'])
  {
    $options['nopsts'] = $_POST['getPostListThumbs-WidgetNopsts'];
    $options['nocats'] = $_POST['getPostListThumbs-WidgetNocats'];
    $options['ptype'] = $_POST['getPostListThumbs-WidgetPtype'];
	$options['ttpos'] = $_POST['getPostListThumbs-WidgetTTpos'];
	$options['dexcp'] = $_POST['getPostListThumbs-WidgetDexcp'];
	
	
    $options['orient'] = $_POST['getPostListThumbs-WidgetOrient'];
    $options['imgo'] = $_POST['getPostListThumbs-WidgetImgo'];
	$options['ttgo'] = $_POST['getPostListThumbs-WidgetTtgo'];
	$options['dtgo'] = $_POST['getPostListThumbs-WidgetDtgo'];
	$options['dtfrm'] = $_POST['getPostListThumbs-WidgetDtfrm'];
    $options['categ'] = htmlspecialchars($_POST['getPostListThumbs-WidgetCateg']);
    $options['postnr'] = htmlspecialchars($_POST['getPostListThumbs-WidgetPostNr']);
    $options['linn'] = htmlspecialchars($_POST['getPostListThumbs-WidgetLinn']);
    $options['divwid'] = htmlspecialchars($_POST['getPostListThumbs-WidgetDivWid']);
    $options['tbwid'] = htmlspecialchars($_POST['getPostListThumbs-WidgetTbWid']);
    $options['tbhig'] = htmlspecialchars($_POST['getPostListThumbs-WidgetTbHig']);
	$options['cp'] = $_POST['getPostListThumbs-WidgetCp'];
	$options['cs'] = $_POST['getPostListThumbs-WidgetCs'];
	$options['lwt'] = $_POST['getPostListThumbs-WidgetLwt'];
	$options['tte'] = htmlspecialchars($_POST['getPostListThumbs-WidgetTte']);
	$options['sptb'] = htmlspecialchars($_POST['getPostListThumbs-WidgetSptb']);
	$options['tgtb'] = htmlspecialchars($_POST['getPostListThumbs-WidgetTgtb']);	
	$options['tgtb'] = htmlspecialchars($_POST['getPostListThumbs-WidgetTgtb']);
	$options['ordm'] = htmlspecialchars($_POST['getPostListThumbs-WidgetOrdm']);
	$options['ordf'] = htmlspecialchars($_POST['getPostListThumbs-WidgetOrdf']);
	$options['metk'] = $_POST['getPostListThumbs-WidgetMetK'];
	$options['mett'] = $_POST['getPostListThumbs-WidgetMetT'];
	$options['pgin'] = $_POST['getPostListThumbs-WidgetPgin'];
	$options['gptb'] = $_POST['getPostListThumbs-WidgetGptb'];
	
    update_option("widget_getPostListThumbs", $options);
  }
?>
  <p>
    <?php
        //          
        if($options['ttpos']=="b"){
            $ttposchk1 = "checked";
            $ttposchk2 = "";
        }
        else{
            $ttposchk1 = "";
            $ttposchk2 = "checked";
        }  
		//
        //          
        if($options['orient']=="h"){
            $vchk = "";
            $hchk = "checked";
        }
        else{
            $vchk = "checked";
            $hchk = "";
        }  
		//
        if($options['dexcp']){
            $dxchk = "checked";
        }
        else{
            $dxchk = "";
        }
		//
        if($options['dtfrm']==2){
            $d1chk = "";
            $d2chk = "checked";
        }
        else{
            $d1chk = "checked";
            $d2chk = "";
        }
       //
        if($options['imgo']){
            $ichk = "checked";
        }
        else{
            $ichk = "";
        }
		//
        if($options['ttgo']){
            $tchk = "checked";
        }
        else{
            $tchk = "";
        }
		//
        if($options['dtgo']){
            $dchk = "checked";
        }
        else{
            $dchk = "";
        }
		//
        if($options['lwt']==2){
            $l1chk = "";
            $l2chk = "checked";
            $l3chk = "";
        }
		 else{
            $l1chk = "checked";
            $l2chk = "";
            $l3chk = "";
        } 
        if($options['lwt']==3){
            $l1chk = "";
            $l2chk = "";
            $l3chk = "checked";
        }
		//
        if($options['sptb']){
            $schk = "checked";
        }
        else{
            $schk = "";
        }
		//
        if($options['gptb']){
            $gptbchk = "checked";
        }
        else{
            $gptbchk = "";
        }
		//
        if($options['tgtb']){
            $kchk = "checked";
        }
        else{
            $kchk = "";
        }
		//          
        if($options['ordm']=="ASC"){
            $omachk = "checked";
            $omdchk = "";
        }
        else{
            $omachk = "";
            $omdchk = "checked";
        }
		//
		$ordfi="";
		$ordfd="";
		$ordft="";
		$ordfr="";
        $ordfc="";
		if($options['ordf']=="ID"){
			$ordfi="selected";
		}
		if($options['ordf']=="post_date"){
			$ordfd="selected";
		}
		if($options['ordf']=="title"){
			$ordft="selected";
		}
		if($options['ordf']=="random"){
			$ordfr="selected";
		}
        if($options['ordf']=="comment_count"){
			$ordfc="selected";
		}
        if($options['ordf']=="meta_value_num"){
			$ordfn="selected";
		}
        //echo "testef ".$ordfr;
		//          
        if($options['mett']=="t"){
            $mschk = "checked";
            $mnchk = "";
        }
        else{
            $mschk = "";
            $mnchk = "checked";
        }
		//
    ?>
    <label for="getPostListThumbs-WidgetTte">Title: </label><br />
    <input type="text" id="getPostListThumbs-WidgetTte" name="getPostListThumbs-WidgetTte" value="<?php echo $options['tte'];?>" />
    <br /><br />
    <label for="getPostListThumbs-WidgetPtype">Display Posts or Pages(write post or page in the field bellow): </label><br />
    <input type="radio" id="getPostListThumbs-WidgetPtype" name="getPostListThumbs-WidgetPtype" value="post" <?php echo $pschk?> />Post 
     <input type="radio" id="getPostListThumbs-WidgetPtype" name="getPostListThumbs-WidgetPtype" value="page" <?php echo $pgchk?>/>Horizontal     
    <br /><br />
    <label for="getPostListThumbs-WidgetOrient">Orientation: </label><br />
    <input type="radio" id="getPostListThumbs-WidgetOrient" name="getPostListThumbs-WidgetOrient" value="v" <?php echo $vchk?> />Vertical 
     <input type="radio" id="getPostListThumbs-WidgetOrient" name="getPostListThumbs-WidgetOrient" value="h" <?php echo $hchk?>/>Horizontal     
    <br /><br />
    <label for="getPostListThumbs-WidgetSptb">Suppress Thumbnails: </label><br />
    <input type="checkbox" id="getPostListThumbs-WidgetSptb" name="getPostListThumbs-WidgetSptb"  <?php echo $schk?> />
    <br /><br />
    <label for="getPostListThumbs-WidgetGptb">Get featured image(instead of first image): </label><br />
    <input type="checkbox" id="getPostListThumbs-WidgetGptb" name="getPostListThumbs-WidgetGptb"  <?php echo $gptbchk?> />
    <br /><br />
    <label for="getPostListThumbs-WidgetImgo">Display only images(write post or page in the field bellow): </label><br />
    <input type="checkbox" id="getPostListThumbs-WidgetImgo" name="getPostListThumbs-WidgetImgo"  <?php echo $ichk?> />
    <br /><br />
    <label for="getPostListThumbs-WidgetDtgo">Display post date: </label><br />
    <input type="checkbox" id="getPostListThumbs-WidgetDtgo" name="getPostListThumbs-WidgetDtgo"  <?php echo $dchk?> />
    <br /><br />
    <label for="getPostListThumbs-WidgetDtfrm">Date Format: </label><br />
     <input type="radio" id="getPostListThumbs-WidgetDtfrm" name="getPostListThumbs-WidgetDtfrm" value="1" <?php echo $d1chk?>/>d/m/y
    <input type="radio" id="getPostListThumbs-WidgetDtfrm" name="getPostListThumbs-WidgetDtfrm" value="2" <?php echo $d2chk?> />m/d/y
    <br /><br />
    <label for="getPostListThumbs-WidgetTtgo">Display post title: </label><br />
    <input type="checkbox" id="getPostListThumbs-WidgetTtgo" name="getPostListThumbs-WidgetTtgo"  <?php echo $tchk?> />
    <br /><br />
    <label for="getPostListThumbs-WidgetDexcp">Display post excerpt: </label><br />
    <input type="checkbox" id="getPostListThumbs-WidgetDexcp" name="getPostListThumbs-WidgetDexcp"  <?php echo $dxchk?> />
    <br /><br />
    <label for="getPostListThumbs-WidgetCateg">Category Slug(blank for all categories): </label><br />
    <input type="text" id="getPostListThumbs-WidgetCateg" name="getPostListThumbs-WidgetCateg" value="<?php echo $options['categ'];?>" />
	<br /><br />
    <label for="getPostListThumbs-WidgetNocats">Categories(comma-separated, use negative values to exclude, e.g. -12,-10): </label><br />
    <input type="text" id="getPostListThumbs-WidgetNocats" name="getPostListThumbs-WidgetNocats" value="<?php echo $options['nocats'];?>" />
	<br /><br />
    <label for="getPostListThumbs-WidgetNopsts">Exclude Specific Posts(comma-separated,  e.g. 12,10): </label><br />
    <input type="text" id="getPostListThumbs-WidgetNopsts" name="getPostListThumbs-WidgetNopsts" value="<?php echo $options['nopsts'];?>" />
	<br /><br />
    <label for="getPostListThumbs-WidgetMetK">Custom Field Key: </label><br />
    <input type="text" id="getPostListThumbs-WidgetMetK" name="getPostListThumbs-WidgetMetK" value="<?php echo $options['metk'];?>" />
    <br /><br />
    <label for="getPostListThumbs-WidgetMetT">Custom Field Values Type: </label><br />
    <input type="radio" id="getPostListThumbs-WidgetMetT" name="getPostListThumbs-WidgetMetT" value="n" <?php echo $mnchk?>/>Numbers
	<input type="radio" id="getPostListThumbs-WidgetMetT" name="getPostListThumbs-WidgetMetT" value="t" <?php echo $mschk?> />Text 
	<br /><br />
    <label for="getPostListThumbs-WidgetPostNr">Number of Posts(default=20): </label><br />
    <input type="text" id="getPostListThumbs-WidgetPostNr" name="getPostListThumbs-WidgetPostNr" value="<?php echo $options['postnr'];?>" size="4"/>
	<br /><br />
	<label for="getPostListThumbs-WidgetPgin"><b>Warning:</b> Page navigation is a beta feature. It was tested and works, but it's still under development.</label>
    <label for="getPostListThumbs-WidgetPgin">Number of Rows per Page(leave it blank if you don't want to use page navigation): </label><br />
    <input type="text" id="getPostListThumbs-WidgetPgin" name="getPostListThumbs-WidgetPgin" value="<?php echo $options['pgin'];?>" size="4"/>
	<br /><br />
    <label for="getPostListThumbs-WidgetOrdm">Order: </label><br />
    <input type="radio" id="getPostListThumbs-WidgetOrdm" name="getPostListThumbs-WidgetOrdm" value="DESC" <?php echo $omdchk?>/>DESC
	<input type="radio" id="getPostListThumbs-WidgetOrdm" name="getPostListThumbs-WidgetOrdm" value="ASC" <?php echo $omachk?> />ASC 
	<br /><br />
    <label for="getPostListThumbs-WidgetOrdf">Order By: </label><br />
    <select id="getPostListThumbs-WidgetOrdf" name="getPostListThumbs-WidgetOrdf">
		<option value="ID" <?php echo $ordfi?>>ID</option>
		<option value="post_date" <?php echo $ordfd?>>Date</option>
		<option value="title" <?php echo $ordft?>>Title</option>
        <option value="random" <?php echo $ordfr?>>Random</option>
        <option value="comment_count" <?php echo $ordfc?>>Most  Commented</option>
        <option value="meta_value_num" <?php echo $ordfn?>>Most  Accessed</option> 
         
	</select>
    <br/><b>Important:</b> In order to make "Most Accessed" feature work, put <i>wpb_set_post_views(get_the_ID());</i> inside of your single.php file loop.
   <br /><br />
    <label for="getPostListThumbs-WidgetTgtb">Target links to a blank page/tab: </label><br />
    <input type="checkbox" id="getPostListThumbs-WidgetTgtb" name="getPostListThumbs-WidgetTgtb"  <?php echo $schk?> />
    <br /><br />
    <label for="getPostListThumbs-WidgetLinn">Number of registers per line(default=3 only for horizontal orientation): </label><br />
    <input type="text" id="getPostListThumbs-WidgetLinn" name="getPostListThumbs-WidgetLinn" value="<?php echo $options['linn'];?>" size="4"/>
   <br />
    <label for="getPostListThumbs-WidgetDivWid">Result Table Width(default=300): </label><br />
    <input type="text" id="getPostListThumbs-WidgetDivWid" name="getPostListThumbs-WidgetDivWid" value="<?php echo $options['divwid'];?>" size="4"/>
    <br />
    <br />
    <label for="getPostListThumbs-WidgetCp">Result Table Cellpadding(default=4): </label><br />
    <input type="text" id="getPostListThumbs-WidgetCp" name="getPostListThumbs-WidgetCp" value="<?php echo $options['cp'];?>" size="4"/>
    <br />
    <label for="getPostListThumbs-WidgetCs">Result Table Cellspacing(default=4): </label><br />
    <input type="text" id="getPostListThumbs-WidgetCs" name="getPostListThumbs-WidgetCs" value="<?php echo $options['cs'];?>" size="4"/>
    <br />
    <label for="getPostListThumbs-WidgetTbWid">Thumbnails Width(default=40): </label><br />
    <input type="text" id="getPostListThumbs-WidgetTbWid" name="getPostListThumbs-WidgetTbWid" value="<?php echo $options['tbwid'];?>" size="4"/>
    <br />
    <label for="getPostListThumbs-WidgetTbHig">Thumbnails Height(default=40): </label><br />
    <input type="text" id="getPostListThumbs-WidgetTbHig" name="getPostListThumbs-WidgetTbHig" value="<?php echo $options['tbhig'];?>" size="4"/>
    <br />
    <label for="getPostListThumbs-WidgetDtfrm">Column Layout: </label><br />
    <table cellpadding="0" cellspacing="3">
     <tr>
		<td valign="top"><input type="radio" id="getPostListThumbs-WidgetLwt" name="getPostListThumbs-WidgetLwt" value="1" <?php echo $l1chk?>/></td><td><table cellpadding="0" cellspacing="3" style="border:0px #666 solid"><tr><td style="border:1px #666 solid">Image</td><td style="border:1px #666 solid"><p>Date</p><p>Title</p><p>Excerpt</p></td></tr></table></td>
		<td valign="top"><input type="radio" id="getPostListThumbs-WidgetLwt" name="getPostListThumbs-WidgetLwt" value="2" <?php echo $l2chk?> /></td><td><table cellpadding="0" cellspacing="3" border="1" style="border:0px #666 solid"><tr><td style="border:1px #666 solid">Image</td></tr><tr><td style="border:1px #666 solid"><p>Date</p><p>Title</p><p>Excerpt</p></td></tr></table></td>
    </tr>
    </table>
    <input type="hidden" id="getPostListThumbs-Submit" name="getPostListThumbs-Submit" value="1" />
  </p>
<?php
}
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
function getPostListThumbs_init()
{
 // register_sidebar_widget(__('Get Post List With Thumbnails'), 'widget_getPostListThumbs'); 
 // register_widget_control(   'Get Post List With Thumbnails', 'getPostListThumbs_control', 300, 240 );
 //register_widget('GPLWT_Widget');
}
//add_action("plugins_loaded", "getPostListThumbs_init");
add_action('widgets_init', 'gplwt_register_widgets');
function gplwt_register_widgets(){
	// curl need to be installed
	register_widget('GPLWT_Widget');
}
///////////////////////////////////
class GPLWT_Widget extends WP_Widget {
	function GPLWT_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'gplwt', 'description' => 'This is the GPLWT Plugin Widget.' );
		/* Widget control settings. */
		$control_ops = array( 'width' => 700, 'height' => 500, 'id_base' => 'gplwt-widget' );
		/* Create the widget. */
		$this->WP_Widget( 'gplwt-widget', 'GPLWT Widget', $widget_ops, $control_ops );
	}
	function widget( $args, $instance ) {
		extract( $args );
		//$instance = get_option("widget_getPostListThumbs");
		if (!is_array( $options ))
		{
		$options = array(
			'nopsts' => '',
            'nocats' => '',
            'ptype' => 'post',
			'ttpos' => 'b',
			'dexcp' => false,			
			'orient' => '',
			'imgo' => '',
			'ttgo' => '',
			'dtgo' => '',
			'dtfrm' => '',
			'categ' => '',
			'postnr' => '',
			'linn' => '',
			'divwid' => '',
			'tbwid' => '',
			'tbhig'  => '',
			'cp' => '',
			'cs' => '',
			'lwt' => '',
			'tte' => '',
			'sptb' => '',
			'tgtb' => false,
			'ordm'=> '',
			'ordf'=>'ID',
			'metk' =>'',
			'mett' => '',			
			'pgin' => '',
			'gptb' => ''
			 );
		}
	 /*echo $before_widget;
		echo $before_title;
			  echo $options['title'];
			echo $after_title;  
		  echo $after_widget;*/
		  //Our Widget Content
			getPostListThumbs($instance['nopsts'],$instance['nocats'],$instance['ptype'],$instance['ttpos'],$instance['dexcp'],$instance['orient'],$instance['imgo'],$instance['ttgo'],$instance['dtgo'],$instance['dtfrm'],$instance['categ'],$instance['postnr'],$instance['linn'],$instance['divwid'],$instance['tbwid'],$instance['tbhig'],$instance['cp'],$instance['cs'],$instance['lwt'],$instance['tte'],$instance['sptb'],$instance['tgtb'],$instance['ordm'],$instance['ordf'],$instance['metk'],$instance['mett'],$instance['pgin'],$instance['gptb']);
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		/* Strip tags (if needed) and update the widget settings. */
		$instance['nopsts'] = $new_instance['nopsts'];
        $instance['nocats'] = $new_instance['nocats'];
        $instance['ptype'] = $new_instance['ptype'];
        $instance['ttpos'] = $new_instance['ttpos'];
		
		$instance['dexcp'] = $new_instance['dexcp'];
		
		$instance['orient'] = $new_instance['orient'];
		$instance['imgo'] = $new_instance['imgo'];
		$instance['ttgo'] = $new_instance['ttgo'];
		$instance['dtgo'] = $new_instance['dtgo'];
		$instance['dtfrm'] = $new_instance['dtfrm'];
		$instance['categ'] = htmlspecialchars($new_instance['categ']);
		$instance['postnr'] = htmlspecialchars($new_instance['postnr']);
		$instance['linn'] = htmlspecialchars($new_instance['linn']);
		$instance['divwid'] = htmlspecialchars($new_instance['divwid']);
		$instance['tbwid'] = htmlspecialchars($new_instance['tbwid']);
		$instance['tbhig'] = htmlspecialchars($new_instance['tbhig']);
		$instance['cp'] = $new_instance['cp'];
		$instance['cs'] = $new_instance['cs'];
		$instance['lwt'] = $new_instance['lwt'];
		$instance['tte'] = htmlspecialchars($new_instance['tte']);
		$instance['sptb'] = htmlspecialchars($new_instance['sptb']);
		$instance['tgtb'] = htmlspecialchars($new_instance['tgtb']);
		$instance['ordm'] = $new_instance['ordm'];
		$instance['ordf'] = $new_instance['ordf'];
		$instance['metk'] = $new_instance['metk'];
		$instance['mett'] = $new_instance['mett'];
		$instance['pgin'] = $new_instance['pgin'];
		$instance['gptb'] = htmlspecialchars($new_instance['gptb']);
		
		return $instance;
	}
	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array( 
		'nopsts' => '',
		'nocats' => '',
        'ptype' => 'post',
		'ttpos' => 'b',
		'dexcp' => false,		
		'orient' => 'v',
        'imgo' => false,
        'ttgo' => false,
        'dtgo' => false,
        'dtfrm' => 1,
        'categ' => '',
        'postnr' => 20,
        'linn' => 3,
        'tbwid' => 40,
        'tbhig' => 40,		
		'cp' => 4,
		'cs' => 4,
		'lwt' => 1,
		'tte' => '',
		'sptb' => false,
		'tgtb' => false,
		'ordm'=>'',
		'ordf'=>'ID',
		'metk'=>'',
		'mett'=>'n',
		
		'pgin'=>'',
		'gptb' => false
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
?>
        <p>
           <?php
                if($instance['ttpos']=="b"){
                    $ttposchk1 = "checked";
                    $ttposchk2 = "";
                }
                else{
                    $ttposchk1 = "";
                    $ttposchk2 = "checked";
                }   
             //          
                if($instance['orient']=="h"){
                    $vchk = "";
                    $hchk = "checked";
                }
                else{
                    $vchk = "checked";
                    $hchk = "";
                }            
                //
                if($instance['dtfrm']==2){
                    $d1chk = "";
                    $d2chk = "checked";
                }
                else{
                    $d1chk = "checked";
                    $d2chk = "";
                }
                //
                if($instance['imgo']){
                    $ichk = "checked";
                }
                else{
                    $ichk = "";
                }
                //
                if($instance['dexcp']){
                    $dxchk = "checked";
                }
                else{
                    $dxchk = "";
                }
                //
                if($instance['ttgo']){
                    $tchk = "checked";
                }
                else{
                    $tchk = "";
                }
                //
                if($instance['dtgo']){
                    $dchk = "checked";
                }
                else{
                    $dchk = "";
                }
                //
                if($instance['lwt']==2){
                    $l1chk = "";
                    $l2chk = "checked";
                }
                else{
                    $l1chk = "checked";
                    $l2chk = "";
                }
                //
                if($instance['sptb']){
                    $schk = "checked";
                }
                else{
                    $schk = "";
                }
                //
                if($instance['gptb']){
                    $gptbchk = "checked";
                }
                else{
                    $gptbchk = "";
                }
                //
                if($instance['tgtb']){
                    $kchk = "checked";
                }
                else{
                    $kchk = "";
                }
				//          
				if($instance['ordm']=="ASC"){
					$omachk = "checked";
					$omdchk = "";
				}
				else{
					$omachk = "";
					$omdchk = "checked";
				}
				//
				$ordfi="";
				$ordfd="";
				$ordft="";
				$ordfr="";
                $ordfc="";
				if($instance['ordf']=="ID"){
					$ordfi="selected";
				}
				if($instance['ordf']=="post_date"){
					$ordfd="selected";
				}
				if($instance['ordf']=="title"){
					$ordft="selected";
				}
				if($instance['ordf']=="random"){
					$ordfr="selected";
				}
                if($instance['ordf']=="comment_count"){
					$ordfc="selected";
				}
                 if($instance['ordf']=="meta_value_num"){
					$ordfn="selected";
				}
				//          
				if($instance['mett']=="t"){
					$mschk = "checked";
					$mnchk = "";
				}
				else{
					$mschk = "";
					$mnchk = "checked";
				}
				//
				//'orient' => 'v','imgo' => false,'ttgo' => false,'dtgo' => false,'dtfrm' => 1,'categ' => '','postnr' => 20,'linn' => 3,'tbwid' => 40,'tbhig' => 40,'cp' => 4,'cs' => 4,'lwt' => 1,'tte' => '','sptb' => false,'tgtb' => false
            ?>
            <label for="<?php echo $this->get_field_id( 'tte' ); ?>">Title: </label><br />
            <input type="text" id="<?php echo $this->get_field_id( 'tte' ); ?>" name="<?php echo $this->get_field_name( 'tte' ); ?>" value="<?php echo $instance['tte'];?>" />
            <br /><br />
            <label for="<?php echo $this->get_field_id( 'ttpos' ); ?>">Widget Title Position: </label><br />
            <input type="radio" id="<?php echo $this->get_field_id( 'ttpos' ); ?>" name="<?php echo $this->get_field_name( 'ttpos' ); ?>" value="b" <?php echo $ttposchk1?> />Before List 
            <input type="radio" id="<?php echo $this->get_field_id( 'ttpos' ); ?>" name="<?php echo $this->get_field_name( 'ttpos' ); ?>" value="a" <?php echo $ttposchk2?>/>After List    
            <br /><br />
            <label for="<?php echo $this->get_field_id( 'ptype' ); ?>">Display Posts or Pages: </label><br />
            <!--<input type="radio" id="<?php //echo $this->get_field_id( 'ptype' ); ?>" name="<?php //echo $this->get_field_name( 'ptype' ); ?>" value="post" <?php echo $pschk?> />Posts 
            <input type="radio" id="<?php //echo $this->get_field_id( 'ptype' ); ?>" name="<?php //echo $this->get_field_name( 'ptype' ); ?>" value="page" <?php echo $pgchk?>/>Pages     -->                      
             <?php
            // types will be a list of the post type names
            $types = get_post_types( '', 'objects' ); 
            //echo "testes";
            //var_dump($types);
            // get the registered data about each post type with get_post_type_object
            foreach( $types as $type )
            {                   
                    $brpc++;
                    //var_dump($type);
                    //var_dump($type->name);
                     //          
                    if($instance['ptype']==$type->name){
                        $pgchk = "checked";
                    }
                    else{
                        $pgchk = ""; 
                    }
                    ?>
                    <input type="radio" id="<?php echo $this->get_field_id( 'ptype' ); ?>" name="<?php echo $this->get_field_name( 'ptype' ); ?>" value="<?php echo $type->name;?>" <?php echo $pgchk?>/><?php echo $type->label;?>&nbsp;
                    <?php
                    if($brpc==4){
                        echo "<br/><br/>";
                        $brpc = 0;
                    }
            }
            ?>
            <br /><br/> 
            <label for="<?php echo $this->get_field_id( 'orient' ); ?>">Orientation: </label><br />
            <input type="radio" id="<?php echo $this->get_field_id( 'orient' ); ?>" name="<?php echo $this->get_field_name( 'orient' ); ?>" value="v" <?php echo $vchk?> />Vertical 
            <input type="radio" id="<?php echo $this->get_field_id( 'orient' ); ?>" name="<?php echo $this->get_field_name( 'orient' ); ?>" value="h" <?php echo $hchk?>/>Horizontal     
            <br /><br />
            <label for="<?php echo $this->get_field_id( 'sptb' ); ?>">Suppress Thumbnails: </label><br />
            <input type="checkbox" id="<?php echo $this->get_field_id( 'sptb' ); ?>" name="<?php echo $this->get_field_name( 'sptb' ); ?>"  <?php echo $schk?> />
            <br /><br />
            <label for="<?php echo $this->get_field_id( 'gptb' ); ?>">Get featured image(instead of first image): </label><br />
            <input type="checkbox" id="<?php echo $this->get_field_id( 'gptb' ); ?>" name="<?php echo $this->get_field_name( 'gptb' ); ?>"  <?php echo $gptbchk?> />
            <br /><br />
            <label for="<?php echo $this->get_field_id( 'imgo' ); ?>">Display only images: </label><br />
            <input type="checkbox" id="<?php echo $this->get_field_id( 'imgo' ); ?>" name="<?php echo $this->get_field_name( 'imgo' ); ?>"  <?php echo $ichk?> />
            <br /><br />
            <label for="<?php echo $this->get_field_id( 'dtgo' ); ?>">Display post date: </label><br />
            <input type="checkbox" id="<?php echo $this->get_field_id( 'dtgo' ); ?>" name="<?php echo $this->get_field_name( 'dtgo' ); ?>"  <?php echo $dchk?> />
            <br /><br />
            <label for="<?php echo $this->get_field_id( 'dtfrm' ); ?>">Date Format: </label><br />
            <input type="radio" id="<?php echo $this->get_field_id( 'dtfrm' ); ?>" name="<?php echo $this->get_field_name( 'dtfrm' ); ?>" value="1" <?php echo $d1chk?>/>d/m/y
            <input type="radio" id="<?php echo $this->get_field_id( 'dtfrm' ); ?>" name="<?php echo $this->get_field_name( 'dtfrm' ); ?>" value="2" <?php echo $d2chk?> />m/d/y
            <br /><br />
            <label for="<?php echo $this->get_field_id( 'dexcp' ); ?>">Display post excerpt: </label><br />
            <input type="checkbox" id="<?php echo $this->get_field_id( 'dexcp' ); ?>" name="<?php echo $this->get_field_name( 'dexcp' ); ?>"  <?php echo $dxchk?> />
            <br /><br />
            <label for="<?php echo $this->get_field_id( 'ttgo' ); ?>">Display post title: </label><br />
            <input type="checkbox" id="<?php echo $this->get_field_id( 'ttgo' ); ?>" name="<?php echo $this->get_field_name( 'ttgo' ); ?>"  <?php echo $tchk?> />
            <br /><br />
            <label for="<?php echo $this->get_field_id( 'categ' ); ?>">Category Name(blank for all categories): </label><br />
            <input type="text" id="<?php echo $this->get_field_id( 'categ' ); ?>" name="<?php echo $this->get_field_name( 'categ' ); ?>" value="<?php echo $instance['categ'];?>" />
            <br /><br />
            <label for="<?php echo $this->get_field_id( 'nocats' ); ?>">Categories(comma-separated, use negative values to exclude, e.g. -12,-10) </label><br />
            <input type="text" id="<?php echo $this->get_field_id( 'nocats' ); ?>" name="<?php echo $this->get_field_name( 'nocats' ); ?>" value="<?php echo $instance['nocats'];?>" />
            <br /><br />
            <label for="<?php echo $this->get_field_id( 'nopsts' ); ?>">Exclude Specific Posts(comma-separated, e.g. 12,10) </label><br />
            <input type="text" id="<?php echo $this->get_field_id( 'nopsts' ); ?>" name="<?php echo $this->get_field_name( 'nopsts' ); ?>" value="<?php echo $instance['nopsts'];?>" />
            <br /><br />
			<label for="<?php echo $this->get_field_id( 'metk' ); ?>">Custom Field Key: </label><br />
			<input type="text" id="<?php echo $this->get_field_id( 'metk' ); ?>" name="<?php echo $this->get_field_name( 'metk' ); ?>" value="<?php echo $instance['metk'];?>" />
			<br /><br />
			<label for="getPostListThumbs-WidgetMetT">Custom Field Values Type: </label><br />
			<input type="radio" id="<?php echo $this->get_field_id( 'mett' ); ?>" name="<?php echo $this->get_field_name( 'mett' ); ?>" value="n" <?php echo $mnchk?>/>Numbers
			<input type="radio" id="<?php echo $this->get_field_id( 'mett' ); ?>" name="<?php echo $this->get_field_name( 'mett' ); ?>" value="t" <?php echo $mschk?> />Text 
			<br /><br />
            <label for="<?php echo $this->get_field_id( 'postnr' ); ?>">Number of Posts(default=20): </label><br />
            <input type="text" id="<?php echo $this->get_field_id( 'postnr' ); ?>" name="<?php echo $this->get_field_name( 'postnr' ); ?>" value="<?php echo $instance['postnr'];?>" size="4"/>
            <br /><br />
			<label for="getPostListThumbs-WidgetPgin"><b>Warning:</b> Page navigation is a beta feature. It was tested and works, but it's still under development.</label>
            <br /><br /><label for="<?php echo $this->get_field_id( 'pgin' ); ?>">Number of Rows per Page(leave it blank if you don't want to use page navigation): </label><br />
            <input type="text" id="<?php echo $this->get_field_id( 'pgin' ); ?>" name="<?php echo $this->get_field_name( 'pgin' ); ?>" value="<?php echo $instance['pgin'];?>" size="4"/>
            <br /><br />
			<label for="<?php echo $this->get_field_id( 'ordm' ); ?>">Order: </label><br />
			<input type="radio" id="<?php echo $this->get_field_id( 'ordm' ); ?>" name="<?php echo $this->get_field_name( 'ordm' ); ?>" value="DESC" <?php echo $omdchk?>/>DESC
			<input type="radio" id="<?php echo $this->get_field_id( 'ordm' ); ?>" name="<?php echo $this->get_field_name( 'ordm' ); ?>" value="ASC" <?php echo $omachk?> />ASC 
			<br /><br />
			<label for="<?php echo $this->get_field_id( 'ordrf' ); ?>">Order By: </label><br />
			<select id="<?php echo $this->get_field_id( 'ordrf' ); ?>" name="<?php echo $this->get_field_name( 'ordf' ); ?>">
				<option value="ID" <?php echo $ordfi?>>ID</option>
				<option value="post_date" <?php echo $ordfd?>>Date</option>
				<option value="title" <?php echo $ordft?>>Title</option>
				<option value="random" <?php echo $ordfr?>>Random</option>
                <option value="comment_count" <?php echo $ordfc?>>Most Commented</option>
                <option value="meta_value_num" <?php echo $ordfn?>>Most Accessed</option>                
			</select>
			<br/> <b>Important:</b> In order to make "Most Accessed" feature work, put <i>wpb_set_post_views(get_the_ID());</i> inside of your single.php file loop.
		   <br /><br />
           <label for="<?php echo $this->get_field_id( 'tgtb' ); ?>">Target links to a blank page/tab: </label><br />
           <input type="checkbox" id="<?php echo $this->get_field_id( 'tgtb' ); ?>" name="<?php echo $this->get_field_name( 'tgtb' ); ?>"  <?php echo $schk?> />
           <br /><br />
           <label for="<?php echo $this->get_field_id( 'linn' ); ?>">Number of registers per line(default=3 only for horizontal orientation): </label><br />
           <input type="text" id="<?php echo $this->get_field_id( 'linn' ); ?>" name="<?php echo $this->get_field_name( 'linn' ); ?>" value="<?php echo $instance['linn'];?>" size="4"/>
           <br />
           <label for="<?php echo $this->get_field_id( 'divwid' ); ?>">Result Table Width(default=300): </label><br />
           <input type="text" id="<?php echo $this->get_field_id( 'divwid' ); ?>" name="<?php echo $this->get_field_name( 'divwid' ); ?>" value="<?php echo $instance['divwid'];?>" size="4"/>
           <br />
           <br />
           <label for="<?php echo $this->get_field_id( 'cp' ); ?>">Result Table Cellpadding(default=4): </label><br />
           <input type="text" id="<?php echo $this->get_field_id( 'cp' ); ?>" name="<?php echo $this->get_field_name( 'cp' ); ?>" value="<?php echo $instance['cp'];?>" size="4"/>
           <br />
           <label for="<?php echo $this->get_field_id( 'cs' ); ?>">Result Table Cellspacing(default=4): </label><br />
           <input type="text" id="<?php echo $this->get_field_id( 'cs' ); ?>" name="<?php echo $this->get_field_name( 'cs' ); ?>" value="<?php echo $instance['cs'];?>" size="4"/>
           <br />
           <label for="<?php echo $this->get_field_id( 'tbwid' ); ?>">Thumbnails Width(default=40): </label><br />
           <input type="text" id="<?php echo $this->get_field_id( 'tbwid' ); ?>" name="<?php echo $this->get_field_name( 'tbwid' ); ?>" value="<?php echo $instance['tbwid'];?>" size="4"/>
           <br />
           <label for="<?php echo $this->get_field_id( 'tbhig' ); ?>">Thumbnails Height(default=40): </label><br />
           <input type="text" id="<?php echo $this->get_field_id( 'tbhig' ); ?>" name="<?php echo $this->get_field_name( 'tbhig' ); ?>" value="<?php echo $instance['tbhig'];?>" size="4"/>
           <br />
           <label for="<?php echo $this->get_field_id( 'dtfrm' ); ?>">Column Layout: </label><br />
           <table cellpadding="0" cellspacing="3">
             <tr>
             <td valign="top"><input type="radio" id="<?php echo $this->get_field_id( 'lwt' ); ?>" name="<?php echo $this->get_field_name( 'lwt' ); ?>" value="1" <?php echo $l1chk?>/></td><td><table cellpadding="0" cellspacing="3" style="border:0px #666 solid"><tr><td style="border:1px #666 solid">Image</td><td style="border:1px #666 solid"><p>Date</p><p>Title</p><p>Excerpt</p></td></tr></table></td>
            <td valign="top"><input type="radio" id="<?php echo $this->get_field_id( 'lwt' ); ?>" name="<?php echo $this->get_field_name( 'lwt' ); ?>" value="2" <?php echo $l2chk?> /></td><td><table cellpadding="0" cellspacing="3" border="1" style="border:0px #666 solid"><tr><td style="border:1px #666 solid">Image</td></tr><tr><td style="border:1px #666 solid"><p>Date</p><p>Title</p><p>Excerpt</p></td></tr></table></td>
            </tr>
           </table>
            <input type="hidden" id="getPostListThumbs-Submit" name="getPostListThumbs-Submit" value="1" />
          </p>
        <?php        
    }
}
?>