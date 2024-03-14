<?php
if (!defined('NXTWM_KEY_DONATE'))
   { define('NXTWM_KEY_DONATE','DRNEFMLP7CU5Q');
   }
 if (!defined('NXTWM_PLUGIN_NAME'))
   { define('NXTWM_PLUGIN_NAME','Next Watermark');
   }
if (!defined('NXTWM_PLUGIN_SLUG'))
   { define('NXTWM_PLUGIN_SLUG','next-watermark');
   }
if (!defined('NXTWM_DOMAIN'))
   { define('NXTWM_DOMAIN','next-watermark');
   }
if (!defined('NXTWM_VERSION'))
   { define('NXTWM_VERSION','1.7');
   }
if (!defined('NXTWM_TYPE'))
   { define('NXTWM_TYPE','Free');
   }
if (!defined('NXTWM_FULL_SIZE'))
   { define('NXTWM_FULL_SIZE','full-size');
   }
if (!defined('NXTWM_PLUGIN_PAGE'))
   { define('NXTWM_PLUGIN_PAGE','nwm-acp');
   }
if (!defined('NXTWM_PLUGIN_TAB_IMG'))  
   { define('NXTWM_PLUGIN_TAB_IMG','wm_image');
   }
if (!defined('NXTWM_IMG_BACKUP_PATH'))  
   { define('NXTWM_IMG_BACKUP_PATH','NWM');
   }
   
function nxtwm_GetRomanYear($parYear)
{ $A_Roman = array("M","CM","D","CD","C","XC","L","XL","X","IX","V","IV","I");
  $A_Arab = array(1000,900,500,400,100,90,50,40,10,9,5,4,1);
    
  if ($parYear <= 0) $parYear = 1;
  if ($parYear > 4999) $parYear = 4999;
  $parYear = (int) $parYear;
  
  $RomanYear = "";
  $i = 0;
  while($parYear > 0)
       { if($parYear >= $A_Arab[$i]) 
           { $parYear = $parYear - $A_Arab[$i];
             $RomanYear = $RomanYear.$A_Roman[$i];
           }
          else
           { $i++;
           }
       }
  return $RomanYear;
}

add_action('admin_enqueue_scripts', 'nwm_Styles');
function nwm_Styles()
{ $tmpStr = plugins_url('/',__FILE__);
  if (substr($tmpStr,-1) == "/")
     $tmpPos = strrpos($tmpStr,'/',-2);
  else   
     $tmpPos = strrpos($tmpStr,'/',-1);
  $tmpStr = substr($tmpStr,0,$tmpPos);
  $tmpPathCSS = $tmpStr . '/css/style.css';
  wp_enqueue_style('nwm_Styles', $tmpPathCSS);
}

add_action('plugins_loaded', 'nwm_checkVersion');
function nwm_CheckVersion()
{ $tmpCurVersion = get_option('nwmCurrentVersion');
  $tmpCurType = get_option('nwmCurrentType');

  if((version_compare($tmpCurVersion, NXTWM_VERSION, '<')) or (NXTWM_TYPE !== $tmpCurType))
    { nwm_PluginActivation();
    }
}

function nwm_PluginActivation()
{ update_option('nwmCurrentVersion', NXTWM_VERSION);
  update_option('nwmCurrentType', NXTWM_TYPE);
  
  return NXTWM_VERSION;
}
register_activation_hook(__FILE__, 'nwm_PluginActivation');

add_action( 'admin_menu','nwm_Add_Menu');
function nwm_Add_Menu()
{ add_menu_page(
      'Set watermark',
      NXTWM_PLUGIN_NAME,
      'manage_options',
      
      'nwm-acp',
      'nwm_acp_callback',
      plugins_url(NXTWM_PLUGIN_SLUG . '/images/icon.png')
  );
   
  add_submenu_page('nwm-acp', __('Set Watermarks',NXTWM_DOMAIN), __('General Settings',NXTWM_DOMAIN), 'manage_options', 'nwm-acp&tab=wm_settings', 'render_generic_settings_page');
  add_submenu_page('nwm-acp', __('Set Watermark Text',NXTWM_DOMAIN), __('Watermark Text',NXTWM_DOMAIN), 'manage_options', 'nwm-acp&tab=wm_text', 'render_generic_settings_page');
  add_submenu_page('nwm-acp', __('Set Watermark Image',NXTWM_DOMAIN), __('Watermark Image',NXTWM_DOMAIN), 'manage_options', 'nwm-acp&tab=wm_image', 'render_generic_settings_page');
  add_submenu_page('nwm-acp', __('Set Backup/Restore options',NXTWM_DOMAIN), __('Backup/Restore',NXTWM_DOMAIN), 'manage_options', 'nwm-acp&tab=wm_media', 'render_generic_settings_page');

	add_action('admin_init','register_nwm_settings');  
}

add_action('init','nwm_load_textdomain');
function nwm_load_textdomain()
{ load_plugin_textdomain(NXTWM_DOMAIN,false,NXTWM_PLUGIN_SLUG . '/languages/'); 
}

function register_nwm_settings()
{ register_setting('nwm-settings-group','nwmCurrentVersion');
  register_setting('nwm-settings-group','nwmCurrentType');

  register_setting('nwm-settings-group','optTextWM');
	register_setting('nwm-settings-group','optFontWM');
	register_setting('nwm-settings-group','optSizeWM');
	register_setting('nwm-settings-group','optAlphaWM');
	register_setting('nwm-settings-group','optColorWM');
	register_setting('nwm-settings-group','optLB_Mosaic');
	register_setting('nwm-settings-group','optCustom_X');
	register_setting('nwm-settings-group','optCustom_Y');
  register_setting('nwm-settings-group','optMosaicCross');
	register_setting('nwm-settings-group','optAlignWM_X');
	register_setting('nwm-settings-group','optAlignWM_Y');
	register_setting('nwm-settings-group','optDegreeWM');

  register_setting('nwm-image-group','optImageWM');
  register_setting('nwm-image-group','optUploadWM');
  register_setting('nwm-image-group','optOpacWM');
	register_setting('nwm-image-group','optGreyscaleWM');
	register_setting('nwm-image-group','optNegateWM');
	register_setting('nwm-image-group','optPosImgWM');
	register_setting('nwm-image-group','optAlignImgWM_X');
	register_setting('nwm-image-group','optAlignImgWM_Y');
	register_setting('nwm-image-group','optFitWM_Width');
	register_setting('nwm-image-group','optFitWM_Height');
	register_setting('nwm-image-group','optKeepRatio');
	register_setting('nwm-image-group','optMargin_X');
	register_setting('nwm-image-group','optMargin_Y');
	register_setting('nwm-image-group','optDegreeImgWM');
	
	register_setting('nwm-general-group','optAutoWM');
	register_setting('nwm-general-group','optActiveTxtWM');
	register_setting('nwm-general-group','optActiveImgWM');
	register_setting('nwm-general-group','optQuality');
	register_setting('nwm-general-group','optTypeGIF');
	register_setting('nwm-general-group','optTypeJPEG');
	register_setting('nwm-general-group','optTypePNG');
	register_setting('nwm-general-group','optTypeWEBP');
	register_setting('nwm-general-group','optDisableCopy');

	register_setting('nwm-media-group','optBackupImgWM');
  register_setting('nwm-media-group','optBackupAllWM');
  
	global $NbMediaSizes;
  global $A_SizeName;
  global $A_SizeWidth;
  global $A_SizeHeight;
	
  $wp_additional_image_sizes = wp_get_additional_image_sizes();
  $sizes = array();
  $get_intermediate_image_sizes = get_intermediate_image_sizes();
  foreach ($get_intermediate_image_sizes as $_size)
          { if (in_array($_size, array( 'thumbnail', 'medium', 'large'))) 
               { $sizes[$_size]['width'] = get_option($_size . '_size_w');
                 $sizes[$_size]['height'] = get_option($_size . '_size_h');
                 $sizes[$_size]['crop'] = (bool) get_option($_size . '_crop');
               } elseif (isset( $wp_additional_image_sizes[$_size])) 
                        { $sizes[$_size] = array(
                                  'width' => $wp_additional_image_sizes[ $_size ]['width'],
                                  'height' => $wp_additional_image_sizes[ $_size ]['height'],
                                  'crop' =>  $wp_additional_image_sizes[ $_size ]['crop']);
                        }
          }
  $c = 1;
  foreach ($sizes as $key => $image_size)
          { $A_SizeName[$c] = $key;
            $A_SizeWidth[$c] = $image_size['width'];
            $A_SizeHeight[$c] = $image_size['height'];
            $c++;
          }
  $A_SizeName[$c] = NXTWM_FULL_SIZE;
  $A_SizeWidth[$c] = 0;
  $A_SizeHeight[$c] = 0;
  $NbMediaSizes = count($A_SizeName);          

  for ($i=1;$i<=$NbMediaSizes;$i++)
      { $tmpOptMS = 'optMS'.$i;
        register_setting('nwm-general-group',$tmpOptMS);
      }
  
  global $NbPostTypes;
  global $A_PostTypeName;
  $args = array(
   'public'   => true,
   );
  $output = 'names';
  $operator = 'and';
  $post_types = get_post_types($args,$output,$operator);
  $d = 0;
  if ($post_types)
     { foreach ($post_types as $post_type)
               { $d++;
                 $A_PostTypeName[$d] = $post_type;
               }
     }
  $NbPostTypes = $d;
  
  for ($i=1;$i<=$NbPostTypes;$i++)
      { $tmpOptPT = 'optPT'.$i;
        register_setting('nwm-general-group',$tmpOptPT);
      }
}

function nwm_acp_callback()
{ global $title;

  if (!current_user_can('administrator'))
     { wp_die(__('You do not have sufficient permissions to access this page.',NXTWM_DOMAIN));
	   }
	
  print '<div class="wrap">';
  print "<h1 class=\"stabilo\">$title</h1><hr>";
  $file = plugin_dir_path( __FILE__ ) . "nwm-acp-page.php";
  if (file_exists($file))
      require $file;

  echo "<p><em><b>" . esc_html__('You like this plugin?',NXTWM_DOMAIN) . " <a target=\"_blank\" href=\"https://www.paypal.com/donate/?hosted_button_id=" . NXTWM_KEY_DONATE . "\" style=\"color:#FE5500;font-weight:bold;font-size:1.2em\">" . esc_html__('Offer me a coffee!',NXTWM_DOMAIN) . "</a></b></em>";
  $CoffeePath = plugin_dir_url( dirname( __FILE__ ) )  . '/images/coffee-donate.gif';
  echo '&nbsp;<img src="' . $CoffeePath . '"></p>';
  print '</div>';
}

add_action("admin_enqueue_scripts", "nwm_add_script_upload");
function nwm_add_script_upload()
{	wp_enqueue_media();
  wp_register_script('nwm_upload', plugins_url('/',__DIR__).'js/upload.js', array('jquery'), '1', true );
  wp_enqueue_script('nwm_upload');
}

add_action('admin_notices','nwm_notice_DataSaved');
function nwm_notice_DataSaved()
{ $screen = get_current_screen();
  $tmpPluginPage = $_GET['page'];
  $tmpPluginPage = sanitize_text_field($tmpPluginPage);
  if ($screen->id !== 'toplevel_page_'.$tmpPluginPage) return;
  
  if (isset($_GET['settings-updated']))
     { $tmp_settings_updated = $_GET['settings-updated'];
       $tmp_settings_updated = sanitize_text_field($tmp_settings_updated);
       if ($tmp_settings_updated === "true") 
          { ?>
            <div class="notice notice-success is-dismissible">
                 <p><?php esc_html_e('Data have been saved successfully!',NXTWM_DOMAIN) ?></p>
            </div>
            <?php 
          }
       else 
          { ?>
            <div class="notice notice-warning is-dismissible">
                 <p><?php esc_html_e('Sorry, cannot do this...',NXTWM_DOMAIN) ?></p>
            </div>
            <?php
          }
     }
}

add_action('admin_notices','nwm_warning_NoWatermark');
function nwm_warning_NoWatermark()
{ $screen = get_current_screen();
  $tmpPluginPage = $_GET['page'];
  $tmpPluginPage = sanitize_text_field($tmpPluginPage);
  if ($screen->id !== 'toplevel_page_'.$tmpPluginPage) return;
    
  $opt_ActiveTxtWM = get_option('optActiveTxtWM');
  $opt_ActiveImgWM = get_option('optActiveImgWM');
  if (!$opt_ActiveTxtWM and !$opt_ActiveImgWM)
     { ?>
       <div class="notice notice-warning is-dismissible">
            <p><?php esc_html_e('Both image and text watermarks are disabled!',NXTWM_DOMAIN) ?></p>
       </div>
       <?php 
     }
  
  $opt_FitWM_Width = get_option('optFitWM_Width');
  $opt_FitWM_Height = get_option('optFitWM_Height');
  $opt_KeepRatio = get_option('optKeepRatio');
  if ($opt_KeepRatio and $opt_FitWM_Width and $opt_FitWM_Height)
     { ?>
       <div class="notice notice-warning is-dismissible">
            <p><?php esc_html_e('Cannot keep aspect ratio when both \'Fit\' options are selected!',NXTWM_DOMAIN) ?></p>
       </div>
       <?php 
     }
}

add_action("wp_footer", "nwm_copy_protect");
function nwm_copy_protect()
{ $opt_DisableCopy = get_option('optDisableCopy');
  if (!$opt_DisableCopy) return;

  ?><script type="text/javascript">
    jQuery(document).ready(function () {
        //Disable cut copy paste
        jQuery('body').bind('cut copy paste', function (e) {
            e.preventDefault();
        });
        //Disable mouse right click
        jQuery("body").on("contextmenu",function(e){
            return false;
        });
    });
    </script>
    <?php
}

function nwm_isWM_ajax_query_attachments_args($query)
{ $referer = parse_url(wp_get_referer());
  parse_str($referer['query'], $params);
  $listParams = explode('&',$referer['query']);
  $tmpPage = explode('=',$listParams[0]);
  $tmpTab = explode('=',$listParams[1]);
  if (($tmpPage[1] == NXTWM_PLUGIN_PAGE) and ($tmpTab[1] == NXTWM_PLUGIN_TAB_IMG))
     { $opt_UploadWM = "1";
       update_option('optUploadWM',$opt_UploadWM);
     }
  
  return $query;
}
add_filter('ajax_query_attachments_args','nwm_isWM_ajax_query_attachments_args',10,1);

add_filter('wp_generate_attachment_metadata','nxtwm_SetWatermark', 10, 2);
function nxtwm_SetWatermark ($meta, $id)
{ if(!isset($meta['sizes']))
    { return $meta;
    }

  $opt_AutoWM = get_option('optAutoWM');
  if (!$opt_AutoWM) return $meta;
  
  $opt_UploadWM = get_option('optUploadWM');
  if ($opt_UploadWM == "1")
     { $opt_UploadWM = "";
       update_option('optUploadWM',$opt_UploadWM);

       return $meta;
     }
  
    
  $opt_ActiveTxtWM = get_option('optActiveTxtWM');
  $opt_ActiveImgWM = get_option('optActiveImgWM');
  if (!$opt_ActiveTxtWM and !$opt_ActiveImgWM) return $meta;
  
  $opt_Quality = get_option('optQuality');
  
  $opt_TypeGIF = get_option('optTypeGIF');
  $opt_TypeJPEG = get_option('optTypeJPEG');
  $opt_TypePNG = get_option('optTypePNG');
  $opt_TypeWEBP = get_option('optTypeWEBP');
  
  $tmpExt = strtolower(strrchr($meta['file'], '.'));
  switch($tmpExt)
        { case '.gif':
               if (!$opt_TypeGIF) return $meta;
               break;
          case '.jpg':
          case '.jpeg':
               if (!$opt_TypeJPEG) return $meta;
               break;
          case '.png':
               if (!$opt_TypePNG) return $meta;
               break;
          case '.webp':
               if (!$opt_TypeWEBP) return $meta;
               break;
          default: 
               return $meta;
               break;
        }
        
  $tmpParentId = wp_get_post_parent_id($id);
  $curPostType = get_post_type($tmpParentId);

  $args = array(
   'public'   => true,
   );
  $output = 'names';
  $operator = 'and';
  $post_types = get_post_types($args,$output,$operator);
  $d = 0;
  if ($post_types)
     { foreach ($post_types as $post_type)
               { $d++;
                 $A_PostTypeName[$d] = $post_type;
               }
     }
  $NbPostTypes = $d;
 
  for($i=1;$i<=$NbPostTypes;$i++)
     { $tmpOptPT = 'optPT'.$i;
       ${'opt_PT'.$i} = get_option($tmpOptPT);
       if (($A_PostTypeName[$i] == $curPostType) and (!${'opt_PT'.$i}))
          return $meta;
     }

  $opt_TextWM = get_option('optTextWM');
  $opt_FontWM = get_option('optFontWM');
  $opt_SizeWM = get_option('optSizeWM');
  $opt_AlphaWM = get_option('optAlphaWM');
  $opt_ColorWM = get_option('optColorWM');
  $opt_LB_Mosaic = get_option('optLB_Mosaic');
  $opt_Custom_X = get_option('optCustom_X');
  $opt_Custom_Y = get_option('optCustom_Y');
  $opt_MosaicCross = get_option('optMosaicCross');
  $opt_AlignWM_X = get_option('optAlignWM_X');
  $opt_AlignWM_Y = get_option('optAlignWM_Y');
  $opt_DegreeWM = get_option('optDegreeWM');
  $opt_ImageWM = get_option('optImageWM');
  $opt_OpacWM = get_option('optOpacWM');
  $opt_GreyscaleWM = get_option('optGreyscaleWM');
  $opt_NegateWM = get_option('optNegateWM');
  $opt_PosImgWM = get_option('optPosImgWM');
  $opt_AlignImgWM_X = get_option('optAlignImgWM_X');
  $opt_AlignImgWM_Y = get_option('optAlignImgWM_Y');
  $opt_FitWM_Width = get_option('optFitWM_Width');
  $opt_FitWM_Height = get_option('optFitWM_Height');
  $opt_Margin_X = get_option('optMargin_X');
  $opt_Margin_Y = get_option('optMargin_Y');
  $opt_DegreeImgWM = get_option('optDegreeImgWM');

  $upload_path = wp_upload_dir();     
  $path = $upload_path['basedir'];
  if(isset($path))
    { $SourceFile = trailingslashit($upload_path['basedir'].'/').$meta['file'];
      $strUploadDir = $upload_path['subdir'];
    }
  else
    { $SourceFile = trailingslashit($upload_path['path']).$meta['file'];
    }
  list($orig_width, $orig_height, $orig_type) = @getimagesize($SourceFile);
  
  $opt_BackupImgWM = get_option('optBackupImgWM');
  if($opt_BackupImgWM)
    { $tmpPath = $upload_path['path'];
      $tmpPos = strpos($tmpPath,"/uploads");
      $tmpDir = substr($tmpPath,$tmpPos);
      $listDir = explode("/",$tmpDir);
      $strUploadDir =  "/" . $listDir[2] . "/" . $listDir[3]; 
      $tmpCommonPath = substr($tmpPath,0,$tmpPos);   
      $listDir = explode("/",$tmpDir);
      if (!is_dir($tmpCommonPath . "/" . NXTWM_IMG_BACKUP_PATH)) mkdir($tmpCommonPath . "/NWM",0755);
      if (!is_dir($tmpCommonPath . "/" . NXTWM_IMG_BACKUP_PATH . "/" . $listDir[2])) mkdir($tmpCommonPath . "/NWM/" . $listDir[2],0755);
      if (!is_dir($tmpCommonPath . "/" . NXTWM_IMG_BACKUP_PATH . "/" . $listDir[2] . "/" . $listDir[3])) mkdir($tmpCommonPath . "/NWM/" . $listDir[2] . "/" . $listDir[3],0755);
      $tmpDestPath = $tmpCommonPath . "/NWM/";
      copy($SourceFile, $tmpDestPath . $meta['file']);
    }

  $wp_additional_image_sizes = wp_get_additional_image_sizes();
  $sizes = array();
  $get_intermediate_image_sizes = get_intermediate_image_sizes();
  foreach ($get_intermediate_image_sizes as $_size)
          { if (in_array($_size, array( 'thumbnail', 'medium', 'large'))) 
               { $sizes[$_size]['width'] = get_option($_size . '_size_w');
                 $sizes[$_size]['height'] = get_option($_size . '_size_h');
                 $sizes[$_size]['crop'] = (bool) get_option($_size . '_crop');
               } elseif (isset( $wp_additional_image_sizes[$_size])) 
                        { $sizes[$_size] = array(
                                  'width' => $wp_additional_image_sizes[ $_size ]['width'],
                                  'height' => $wp_additional_image_sizes[ $_size ]['height'],
                                  'crop' =>  $wp_additional_image_sizes[ $_size ]['crop']);
                        }
          }
  $c = 1;
  foreach ($sizes as $key => $image_size)
          { $A_SizeName[$c] = $key;
            $A_SizeWidth[$c] = $image_size['width'];
            $A_SizeHeight[$c] = $image_size['height'];
            $c++;
          }
  $A_SizeName[$c] = NXTWM_FULL_SIZE;
  $A_SizeWidth[$c] = $orig_width;
  $A_SizeHeight[$c] = $orig_height;
  $NbMediaSizes = count($A_SizeName);
  
  for($i=1;$i<=$NbMediaSizes;$i++)
     { $tmpOptMS = 'optMS'.$i;
       ${'opt_MS'.$i} = get_option($tmpOptMS);
     }

  $upload_path = wp_upload_dir();     
  $path = $upload_path['basedir'];
  $FontPath = plugin_dir_path( __DIR__ ) . "fonts/";

  for ($i=1;$i<=$NbMediaSizes;$i++)
      { $tmpMediaSize = $A_SizeName[$i];
        if ((($meta["sizes"][$A_SizeName[$i]]["file"])or($tmpMediaSize == NXTWM_FULL_SIZE)) and (${'opt_MS'.$i} == 1))
           { if(isset($path))
               { if ($tmpMediaSize == NXTWM_FULL_SIZE)
                    $tmpSourceFile = trailingslashit($upload_path['basedir'].'/').$meta['file'];
                 else
                    $tmpSourceFile = trailingslashit($upload_path['basedir'].'/').$strUploadDir."/".$meta['sizes'][$tmpMediaSize]['file'];
               }
             else
               { if ($tmpMediaSize == NXTWM_FULL_SIZE)
                    $tmpSourceFile = trailingslashit($upload_path['path']).$meta['file'];
                 else   
                    $tmpSourceFile = trailingslashit($upload_path['path']).$meta['sizes'][$tmpMediaSize]['file'];
               }
             
             if ($tmpMediaSize == NXTWM_FULL_SIZE)
                { $tmpWidth = $orig_width;
                  $tmpHeight = $orig_height;
                }
             else
                { $tmpWidth = $meta['sizes'][$tmpMediaSize]['width'];
                  $tmpHeight = $meta['sizes'][$tmpMediaSize]['height'];
                }
             $image_new = imagecreatetruecolor($tmpWidth, $tmpHeight);
             
             switch ($orig_type)
                    { case IMAGETYPE_GIF:
                           $image = imagecreatefromgif($tmpSourceFile);
                           break;
                      case IMAGETYPE_JPEG:
                           $image = imagecreatefromjpeg($tmpSourceFile);
                           break;
                      case IMAGETYPE_PNG:
                           $image = imagecreatefrompng($tmpSourceFile);
                           break;
                      case IMAGETYPE_WEBP:
                           $image = imagecreatefromwebp($tmpSourceFile);
                           break;
                      default:
                           die(esc_html__('Image format not allowed!',NXTWM_DOMAIN));
                    }
             imagecopyresampled($image_new, $image, 0, 0, 0, 0, $tmpWidth, $tmpHeight, $tmpWidth, $tmpHeight);
             
             if ($opt_ActiveImgWM)
                { list($wm_width, $wm_height, $wm_type) = @getimagesize($opt_ImageWM); 

                  switch ($wm_type)
                         { case IMAGETYPE_GIF:
                                $watermark = imagecreatefromgif($opt_ImageWM);
                                break;
                           case IMAGETYPE_JPEG:
                                $watermark = imagecreatefromjpeg($opt_ImageWM);
                                break;
                           case IMAGETYPE_PNG:
                                $watermark = imagecreatefrompng($opt_ImageWM);
                                break;
                           default:
                                die(esc_html__('Watermark image format not allowed!',NXTWM_DOMAIN));
                         }
                         
                  if ($opt_DegreeImgWM != 0)
                     { imagealphablending($watermark, false);
                       imagesavealpha($watermark, true);
                       $pngTransparency = imagecolorallocatealpha($watermark , 0, 0, 0, 127);
                       $watermark = imagerotate($watermark, $opt_DegreeImgWM, $pngTransparency);
                       imagealphablending($watermark, false);
                       imagesavealpha($watermark, true);
                     }
                }

             if ($opt_ActiveTxtWM)
                { list($red, $green, $blue) = sscanf($opt_ColorWM, "#%02x%02x%02x");
                  $wm_color_txt = imagecolorallocatealpha($image_new, $red, $green, $blue, $opt_AlphaWM);
                }
                
             switch ($opt_TextWM)
                    { case "Year":
                           $opt_TextWM = "&copy;" . date("Y");
                           break;
                      case "RomanYear":
                           $opt_TextWM = "&copy;" . nxtwm_GetRomanYear(date("Y"));
                           break;
                      case "Date(D/M/Y)":
                           $opt_TextWM = date("d/m/Y");
                           break;     
                      case "Date(M/D/Y)":
                           $opt_TextWM = date("m/d/Y");
                           break;     
                      default:
                           break; 
                    }

             if ($opt_ActiveTxtWM)
                { $tmpAlignWM_X = $opt_AlignWM_X;
                  $tmpAlignWM_Y = $opt_AlignWM_Y;
                  if ($opt_AlignWM_X < 0) $tmpAlignWM_X = $tmpWidth + $opt_AlignWM_X;
                  if ($opt_AlignWM_Y < 0) $tmpAlignWM_Y = $tmpHeight + $opt_AlignWM_Y;
                  if ($opt_LB_Mosaic == "None")
                     { $ret = imagettftext($image_new, $opt_SizeWM, $opt_DegreeWM, $tmpAlignWM_X, $tmpAlignWM_Y, $wm_color_txt, $FontPath . $opt_FontWM, $opt_TextWM);
                     }
                  else
                     { if ($opt_LB_Mosaic == "Custom")
                          { $tmpMosaicSize_H = $opt_Custom_X;
                            $tmpMosaicSize_V = $opt_Custom_Y;
                          }
                       else
                          { $tmpMosaicSize = explode("x",$opt_LB_Mosaic);
                            $tmpMosaicSize_H = $tmpMosaicSize[0];
                            $tmpMosaicSize_V = $tmpMosaicSize[1];
                          }
                       $tmpMosaicStep_H = $tmpWidth / $tmpMosaicSize_H; 
                       $tmpMosaicStep_V = $tmpHeight / $tmpMosaicSize_V;

                       $c=0;
                       $tmpMosaicCrossChecked = ($opt_MosaicCross?1:0);
                       $A_MosaicPos_H[$c] = ($tmpMosaicStep_H/2) * $tmpMosaicCrossChecked * (-1);
                       while($A_MosaicPos_H[$c] + $tmpMosaicStep_H <= $tmpWidth)
                            { $c++;
                              $A_MosaicPos_H[$c] = $A_MosaicPos_H[$c-1] + $tmpMosaicStep_H;
                            }
              
                       $d=0;
                       $A_MosaicPos_V[$d] = ($tmpMosaicStep_V/2) + ($opt_SizeWM/2);
                       while($A_MosaicPos_V[$d] + $tmpMosaicStep_V <= $tmpHeight)
                            { $d++; 
                              $A_MosaicPos_V[$d] = $A_MosaicPos_V[$d-1] + $tmpMosaicStep_V;
                            }
              
                       $tmpMosaicCrossChecked = ($opt_MosaicCross?1:0);
                       for ($h=0;$h<=$c;$h++)
                           { for ($v=0;$v<=$d;$v++)
                                 { $tmpVal_X = $A_MosaicPos_H[$h] + (($v % 2) == 0) * ($tmpMosaicCrossChecked * ($tmpMosaicStep_H / 2));
                                   $ret = imagettftext($image_new, $opt_SizeWM, $opt_DegreeWM, $tmpVal_X, $A_MosaicPos_V[$v], $wm_color_txt, $FontPath . $opt_FontWM, $opt_TextWM);
                                 }
                           }
                     }
                  }
                
             if ($opt_ActiveImgWM)
                { imagealphablending($image, 1);

                  if ($opt_GreyscaleWM)
                     imagefilter($watermark, IMG_FILTER_GRAYSCALE);
                  if ($opt_NegateWM)
                     imagefilter($watermark, IMG_FILTER_NEGATE);
                
                  $wm_NewWidth = $wm_width;
                  $wm_NewHeight = $wm_height;
                  if ($opt_DegreeImgWM != 0)
                     { $wm_NewWidth = abs($wm_height * sin(deg2rad($opt_DegreeImgWM))) + abs($wm_width * cos(deg2rad($opt_DegreeImgWM)));
                       $wm_NewHeight = abs($wm_height * cos(deg2rad($opt_DegreeImgWM))) + abs($wm_width * sin(deg2rad($opt_DegreeImgWM)));
                     }
                     
                  if ($opt_PosImgWM != "")
                     { switch ($opt_PosImgWM)
                              { case "ul": $tmpAlignImgWM_X = 0;
                                           $tmpAlignImgWM_Y = 0;
                                           break;
                                case "uc": $tmpAlignImgWM_X = ($tmpWidth-$wm_NewWidth)/2;
                                           $tmpAlignImgWM_Y = 0;
                                           break;
                                case "ur": $tmpAlignImgWM_X = $tmpWidth-$wm_NewWidth;
                                           $tmpAlignImgWM_Y = 0;
                                           break;
                                case "ml": $tmpAlignImgWM_X = 0;
                                           $tmpAlignImgWM_Y = ($tmpHeight-$wm_NewHeight)/2;
                                           break;
                                case "mc": $tmpAlignImgWM_X = ($tmpWidth-$wm_NewWidth)/2;
                                           $tmpAlignImgWM_Y = ($tmpHeight-$wm_NewHeight)/2;
                                           break;
                                case "mr": $tmpAlignImgWM_X = $tmpWidth-$wm_NewWidth;
                                           $tmpAlignImgWM_Y = ($tmpHeight-$wm_NewHeight)/2;
                                           break;
                                case "bl": $tmpAlignImgWM_X = 0;
                                           $tmpAlignImgWM_Y = $tmpHeight-$wm_NewHeight;
                                           break;
                                case "bc": $tmpAlignImgWM_X = ($tmpWidth-$wm_NewWidth)/2;
                                           $tmpAlignImgWM_Y = $tmpHeight-$wm_NewHeight;
                                           break;
                                case "br": $tmpAlignImgWM_X = $tmpWidth-$wm_NewWidth;
                                           $tmpAlignImgWM_Y = $tmpHeight-$wm_NewHeight;
                                           break;
                                default:   $tmpAlignImgWM_X = 0;
                                           $tmpAlignImgWM_Y = 0;
                              }
                     }
                  else
                     { $tmpAlignImgWM_X = $opt_AlignImgWM_X;
                       $tmpAlignImgWM_Y = $opt_AlignImgWM_Y;
                       if ($opt_AlignImgWM_X < 0) $tmpAlignImgWM_X = $tmpWidth + $opt_AlignImgWM_X;
                       if ($opt_AlignImgWM_Y < 0) $tmpAlignImgWM_Y = $tmpHeight + $opt_AlignImgWM_Y;
                     }
          
                  if ($opt_DegreeImgWM != 0)
                     { imagecopy($image_new, $watermark, $tmpAlignImgWM_X, $tmpAlignImgWM_Y, 0, 0, $wm_NewWidth, $wm_NewHeight);
                     }
                  else
                     { $wmRatio = $wm_width / $wm_height;
                       $wm_NewWidth = $wm_width;
                       $wm_NewHeight = $wm_height;
                       $FitWidth = $wm_width;
                       $FitHeight = $wm_height;
                       
                       if ($opt_PosImgWM == "")
                          { $FitWidth = ($wm_NewWidth<$tmpWidth?$wm_NewWidth:$tmpWidth);
                            $FitHeight = ($wm_NewHeight<$tmpHeight?$wm_NewHeight:$tmpHeight);
                            if ($opt_FitWM_Width)
                               { $tmpAlignImgWM_X = $opt_Margin_X;
                                 $FitWidth = $tmpWidth - (2 * $opt_Margin_X);
                                 if ($FitWidth <= 0) $FitWidth = $tmpWidth;
                                 if ($opt_KeepRatio and !$opt_FitWM_Height) $FitHeight = $FitWidth / $wmRatio;
                                 if ($FitHeight < $tmpHeight)
                                    { if ($tmpAlignImgWM_Y + $FitHeight >= $tmpHeight) $tmpAlignImgWM_Y = $tmpHeight - $FitHeight;
                                      if ($tmpAlignImgWM_Y <= 0) $tmpAlignImgWM_Y = 0;
                                    }
                               }
                        
                            if ($opt_FitWM_Height)
                               { $tmpAlignImgWM_Y = $opt_Margin_Y;
                                 $FitHeight = $tmpHeight - (2 * $opt_Margin_Y);
                                 if ($FitHeight <= 0) $FitHeight = $tmpHeight;
                                 if ($opt_KeepRatio and !$opt_FitWM_Width) $FitWidth = $FitHeight * $wmRatio;
                                 if ($FitWidth < $tmpWidth)
                                    { if ($tmpAlignImgWM_X + $FitWidth >= $tmpWidth) $tmpAlignImgWM_X = $tmpWidth - $FitWidth;
                                      if ($tmpAlignImgWM_X <= 0) $tmpAlignImgWM_X = 0;
                                    }
                               }

                            $watermark = imagescale($watermark,$FitWidth,$FitHeight);
                          }
                                           
                       if (($wm_type == IMAGETYPE_GIF) or ($wm_type == IMAGETYPE_JPEG))
                          imagecopymerge($image_new, $watermark, $tmpAlignImgWM_X, $tmpAlignImgWM_Y, 0, 0, $FitWidth, $FitHeight,$opt_OpacWM);
                       else
                          imagecopy($image_new, $watermark, $tmpAlignImgWM_X, $tmpAlignImgWM_Y, 0, 0, $FitWidth, $FitHeight);
                      }
                }
    
             switch ($orig_type)
                    { case IMAGETYPE_GIF:
                           imagegif($image_new, $tmpSourceFile, $opt_Quality);
                           break;
                      case IMAGETYPE_JPEG:
                           imagejpeg($image_new, $tmpSourceFile, $opt_Quality);
                           break;
                      case IMAGETYPE_PNG:
                           imagejpeg($image_new, $tmpSourceFile, $opt_Quality);
                           break;
                      case IMAGETYPE_WEBP:
                           imagewebp($image_new, $tmpSourceFile, $opt_Quality);
                           break;
                      default:
                           die(esc_html__('Image format not allowed!',NXTWM_DOMAIN));
                    }
             imagedestroy($image);
             imagedestroy($image_new);
             if ($opt_ActiveImgWM) imagedestroy($watermark);
           }
      }
  wp_update_attachment_metadata($id, $meta);
  return $meta;
}
