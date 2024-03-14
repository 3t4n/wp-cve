<script>
function nxtwm_optQuality_SliderChange(val)
{ document.getElementById('optQuality').value = val; 
  document.getElementById('optQualityId').innerHTML = val;
}

function nxtwm_Opac_SliderChange(val)
{ document.getElementById('optOpacWM').value = val; 
  document.getElementById('optOpacId').innerHTML = val+'%';
}

function nxtwm_optAlphaWM_SliderChange(val)
{ document.getElementById('optAlphaWM').value = val; 
  document.getElementById('optAlphaId').innerHTML = val;
}

function nxtwm_Degree_SliderChange(val)
{ document.getElementById('optDegreeWM').value = val; 
  document.getElementById('optDegreeId').innerHTML = val+'&deg;';
}

function nxtwm_DegreeImg_SliderChange(val)
{ document.getElementById('optDegreeImgWM').value = val; 
  document.getElementById('optDegreeImgId').innerHTML = val+'&deg;';
  
  if ((val) && (val != 0))
     { document.getElementById('Opacity').style.display = "none";
       document.getElementById("No-Orientation").style.display = "none";
     }
  else
     { document.getElementById("Opacity").style.display = "";
       document.getElementById("No-Orientation").style.display = "block";
     }
}

function nxtwm_LB_Mosaic_Change(val)
{ if (val == 'Custom')
     { document.getElementById("divCustom").style.display = "block";
     }
  else
     { document.getElementById("divCustom").style.display = "none";
     }
  
  if (val == 'None')
     { document.getElementById("divAlignment").style.display = "";
     }
  else
     { document.getElementById("divAlignment").style.display = "none";
     }
}

jQuery(document).ready(function($)
{ const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  const tmpPar = urlParams.get('tab');
 
  switch (tmpPar)
         { case 'wm_text':
                 var tmpMosaic = document.getElementById('optLB_Mosaic').value;
                 if (tmpMosaic == 'Custom')
                    { document.getElementById("divCustom").style.display = "block";
                    }
                 else
                    { document.getElementById("divCustom").style.display = "none";
                    }
                 if (tmpMosaic == 'None')
                    { document.getElementById("divAlignment").style.display = "";
                    }
                 else
                    { document.getElementById("divAlignment").style.display = "none";
                    }
     
           case 'wm_image':
                var tmpDeg = document.getElementById('optDegreeImgWM_slider').value;
                if ((tmpDeg) && (tmpDeg != 0))
                   { document.getElementById('Opacity').style.display = "none";
                     document.getElementById("No-Orientation").style.display = "none";
                   }
                else
                   { document.getElementById("Opacity").style.display = "";
                     document.getElementById("No-Orientation").style.display = "block";
                   }
          }
});
</script>

<?php
function nxtwm_RecurseDirectory($dirname,$maxdepth=10, $depth=0)
{ if ($depth >= $maxdepth) return false;
     
  $subdirectories = array();
  $files = array();
  if (is_dir($dirname) && is_readable($dirname))
     { $d = dir($dirname);
       while (false !== ($f = $d->read()))
             { $file = $d->path.'/'.$f;
               if (('.'==$f) || ('..'==$f)) continue;
               if (is_dir($dirname.'/'.$f))
                  { array_push($subdirectories,$dirname.'/'.$f);
                  }
               else
                  { array_push($files,$dirname.'/'.$f);
                  }
             }
       $d->close();
       foreach ($subdirectories as $subdirectory) 
               { $files = array_merge($files, nxtwm_RecurseDirectory($subdirectory, $maxdepth, $depth+1));
               }
    }
  return $files;
}

function nxtwm_LogFile($parMsg,$parNoticeType)
{ echo "<div class=\"notice notice-" . esc_attr($parNoticeType) . " is-dismissible\"><p>" . esc_attr($parMsg) . "</p></div>";
  
  $dir = plugin_dir_path( __DIR__ );
  $tmpPathLogFile = $dir . NXTWM_PLUGIN_SLUG . ".log";
  $handle = fopen($tmpPathLogFile,"a");
  if ($handle == false)
     {
     }
  else
     { fwrite ($handle , date("D j M Y H:i:s", time()) . " - " . $parMsg . PHP_EOL); 
       fclose ($handle);
     }
}

function nxtwm_RestoreBackupAction()
{ $opt_BackupAllWM = get_option('optBackupAllWM');

  $upload_dir = wp_upload_dir();   
  $UploadPathWP = $upload_dir['basedir'];
  $tmpPos = strpos($UploadPathWP,"/uploads");
  $tmpCommonPath = substr($UploadPathWP,0,$tmpPos);
  $tmpBackupPath = $tmpCommonPath . "/" . NXTWM_IMG_BACKUP_PATH;
 
  $NbBackupFiles = 0;
  $files = nxtwm_RecurseDirectory($tmpBackupPath);
  foreach ($files as $tmpSrcFile) 
          { $strPos = strpos($tmpSrcFile,NXTWM_IMG_BACKUP_PATH);
            $tmpDir = substr($tmpSrcFile,$strPos);
            $listDir = explode("/",$tmpDir);
            $tmpYear = ($listDir[3]?$listDir[1]."/":"");
            $tmpMonth = ($listDir[3]?$listDir[2]."/":"");
            $tmpImgFile = ($listDir[3]?$listDir[3]:$listDir[1]);
                           
            $tmpFileExist = $UploadPathWP . "/" . $tmpYear . $tmpMonth . $tmpImgFile;
            if (!file_exists($tmpFileExist))
               { if ($opt_BackupAllWM)
                    { if ($tmpYear)
                         { $tmpDestFile = $UploadPathWP . "/" . $tmpYear;
                           if (!is_dir($tmpDestFile)) mkdir($tmpDestFile,0755);
                         }
                      if ($tmpMonth)
                         { $tmpDestFile .= $tmpMonth;
                           if (!is_dir($tmpDestFile)) mkdir($tmpDestFile,0755);
                         }
                      if (copy($tmpSrcFile,$tmpFileExist)) $NbBackupFiles++;
                    }
               }
            else   
               { if (copy($tmpSrcFile,$tmpFileExist)) $NbBackupFiles++;
               }
          }
  $tmpMsg = $NbBackupFiles . " " . __('restored image file(s) to media library',NXTWM_DOMAIN);
  nxtwm_LogFile($tmpMsg,"info");
}  

$nwm_CurrentVersion = get_option('nwmCurrentVersion');
$nwm_CurrentType = get_option('nwmCurrentType');

$opt_AutoWM = get_option('optAutoWM');
$opt_UploadWM = get_option('optUploadWM');
$opt_ActiveTxtWM = get_option('optActiveTxtWM');
$opt_ActiveImgWM = get_option('optActiveImgWM');
$opt_Quality = get_option('optQuality'); if ($opt_Quality == "") $opt_Quality = -1;
$opt_TypeGIF = get_option('optTypeGIF');
$opt_TypeJPEG = get_option('optTypeJPEG');
$opt_TypePNG = get_option('optTypePNG');
$opt_TypeWEBP = get_option('optTypeWEBP');
$opt_DisableCopy = get_option('optDisableCopy');

global $NbMediaSizes;
global $A_SizeName;
global $A_SizeWidth;
global $A_SizeHeight;
for ($i=1;$i<=$NbMediaSizes;$i++)
    { $tmpOptMS = 'optMS'.$i;
      ${'opt_MS'.$i} = get_option($tmpOptMS);
    }

global $NbPostTypes;
global $A_PostTypeName;
for ($i=1;$i<=$NbPostTypes;$i++)
    { $tmpOptPT = 'optPT'.$i;
      ${'opt_PT'.$i} = get_option($tmpOptPT);
    }
    
$opt_TextWM = get_option('optTextWM');
$opt_FontWM = get_option('optFontWM');
$opt_SizeWM = get_option('optSizeWM'); if ($opt_SizeWM == "") $opt_SizeWM = "22";
$opt_AlphaWM = get_option('optAlphaWM'); if ($opt_AlphaWM == "") $opt_AlphaWM = "80";
$opt_ColorWM = get_option('optColorWM'); if ($opt_ColorWM == "") $opt_ColorWM = "#ffffff";
$opt_LB_Mosaic = get_option('optLB_Mosaic'); if ($opt_LB_Mosaic == "") $opt_LB_Mosaic = "None";
$opt_Custom_X = get_option('optCustom_X'); if ($opt_Custom_X == "") $opt_Custom_X = "3";
$opt_Custom_Y = get_option('optCustom_Y'); if ($opt_Custom_Y == "") $opt_Custom_Y = "3";
$opt_MosaicCross = get_option('optMosaicCross'); if (!isset($opt_MosaicCross)) $opt_MosaicCross = "1";
$opt_AlignWM_X = get_option('optAlignWM_X'); if ($opt_AlignWM_X == "") $opt_AlignWM_X = "20";
$opt_AlignWM_Y = get_option('optAlignWM_Y'); if ($opt_AlignWM_Y == "") $opt_AlignWM_Y = "20";
$opt_DegreeWM = get_option('optDegreeWM'); if ($opt_DegreeWM == "") $opt_DegreeWM = "0";
$opt_ImageWM = get_option('optImageWM');
$opt_OpacWM = get_option('optOpacWM'); if ($opt_OpacWM == "") $opt_OpacWM = "75";
$opt_GreyscaleWM = get_option('optGreyscaleWM');
$opt_NegateWM = get_option('optNegateWM');
$opt_PosImgWM = get_option('optPosImgWM');
$opt_AlignImgWM_X = get_option('optAlignImgWM_X'); if ($opt_AlignImgWM_X == "") $opt_AlignImgWM_X = "20";
$opt_AlignImgWM_Y = get_option('optAlignImgWM_Y'); if ($opt_AlignImgWM_Y == "") $opt_AlignImgWM_Y = "20";
$opt_FitWM_Width = get_option('optFitWM_Width');
$opt_FitWM_Height = get_option('optFitWM_Height');
$opt_KeepRatio = get_option('optKeepRatio'); if (!isset($opt_KeepRatio)) $opt_KeepRatio = "1";
$opt_Margin_X = get_option('optMargin_X'); if ($opt_Margin_X == "") $opt_Margin_X = "0";
$opt_Margin_Y = get_option('optMargin_Y'); if ($opt_Margin_Y == "") $opt_Margin_Y = "0";
$opt_DegreeImgWM = get_option('optDegreeImgWM'); if ($opt_DegreeImgWM == "") $opt_DegreeImgWM = "0";
$opt_BackupAllWM = get_option('optBackupAllWM'); if (!isset($opt_BackupAllWM)) $opt_BackupAllWM = "1";
$opt_BackupImgWM = get_option('optBackupImgWM'); if (!isset($opt_BackupImgWM)) $opt_BackupImgWM = "1";

$tmpVersionGD = "";
if (function_exists('gd_info'))
   { $A_InfoGD = gd_info();
     $tmpVersionGD = (empty($A_InfoGD['GD Version'])?"Not found!":$A_InfoGD['GD Version']);
   }
echo '<div align="right">' . esc_attr($nwm_CurrentType) . ' Version v.' . esc_attr($nwm_CurrentVersion) . ' - (GD Version: ' . esc_attr($tmpVersionGD)  . ')</div>';

$tab = isset($_GET['tab']) ? $_GET['tab'] : 'wm_settings';
$tab = sanitize_text_field($tab);
?>
<div class="wrap">
    <nav class="nav-tab-wrapper">
         <a href="?page=nwm-acp&tab=wm_settings" class="nav-tab <?php if($tab==='wm_settings'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e('General Settings',NXTWM_DOMAIN); ?></a>
         <a href="?page=nwm-acp&tab=wm_text" class="nav-tab <?php if($tab==='wm_text'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e('Watermark Text',NXTWM_DOMAIN); ?></a>
         <a href="?page=nwm-acp&tab=wm_image" class="nav-tab <?php if($tab==='wm_image'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e('Watermark Image',NXTWM_DOMAIN); ?></a>
         <a href="?page=nwm-acp&tab=wm_media" class="nav-tab <?php if($tab==='wm_media'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e('Backup/Restore',NXTWM_DOMAIN); ?></a>         
    </nav>

    <div class="tab-content">
    <?php switch($tab)
               { case 'wm_text': ?> 
    <form method="post" action="options.php">
    <?php settings_fields('nwm-settings-group'); ?>
    <?php do_settings_sections('nwm-settings-group'); ?>

    <table class="form-table">
    <tr valign="top">
    <td>
        <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Watermark text',NXTWM_DOMAIN); ?></th>
        <td><input type="text" id="WMtext" name="optTextWM" value="<?php echo esc_attr($opt_TextWM) ?>" class="xxx" /> <em><font color="#808080"><?php esc_html_e('E.g.: &copy; Your name',NXTWM_DOMAIN); ?></font></em><br>
            <a href="#" onclick="document.getElementById('WMtext').value='RomanYear';">&copy;<?php echo esc_attr(nxtwm_GetRomanYear(date("Y"))); ?></a> |
            <a href="#" onclick="document.getElementById('WMtext').value='Year';">&copy;<?php echo esc_attr(date("Y")); ?></a> |
            <a href="#" onclick="document.getElementById('WMtext').value='Date(M/D/Y)';"><?php echo date(esc_html__('m/d/Y',NXTWM_DOMAIN)); ?></a> | 
            <?php $tmpSiteURL = get_site_url(); $ret = strpos($tmpSiteURL,"://"); $retURL = substr($tmpSiteURL,$ret+3);?>
            <a href="#" onclick="document.getElementById('WMtext').value='&copy;<?php echo esc_attr($retURL); ?>';">&copy;<?php echo esc_attr($retURL);?></a> |
            <a href="#" onclick="document.getElementById('WMtext').value='';">None</a>
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Font',NXTWM_DOMAIN); ?></th>
        <td><select name="optFontWM">
            <?php
            $dir = plugin_dir_path( __DIR__ );
            $tmpPathFontDir = $dir . "fonts/";
            $objDir = dir($tmpPathFontDir);
            while(false !== ($entry = $objDir->read()))
                 { if($entry!='.' && $entry!='..' && substr($entry,-4) == ".ttf")
                     { $entryFont = substr($entry,0,-4);
                       echo "<option " . ($entry==$opt_FontWM?"selected ":"") . "value=\"" . esc_attr($entry) . "\">" . esc_attr($entryFont) . "</option>";
                     }
                 }
            $objDir->close();
            ?>
            </select></td>
        </tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Font size',NXTWM_DOMAIN); ?></th>
        <td><input type="number" size="4" name="optSizeWM" min="1" max="500" value="<?php echo esc_attr($opt_SizeWM) ?>"></td>
        </tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Font color',NXTWM_DOMAIN); ?></th>
        <td><input type="color" name="optColorWM" size=7 maxlength="7" value="<?php echo esc_attr($opt_ColorWM) ?>" class="xxx" /></td>
        </tr> 
                
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Transparency',NXTWM_DOMAIN); ?></th>
        <td><?php esc_html_e('Set the font transparency',NXTWM_DOMAIN); ?><br>
            <div style="display: inline-block; color:#0250BB;" align="center" id="optAlphaId"><em><?php echo esc_attr($opt_AlphaWM) ?></em></div>
            <input type="range" oninput="nxtwm_optAlphaWM_SliderChange(this.value);" id="optAlphaWM_slider" name="optAlphaWM_slider" style="width:50%;margin-bottom:0px;" min="0" max="127" value="<?php echo esc_attr($opt_AlphaWM) ?>" />
            <input type="hidden" id="optAlphaWM" name="optAlphaWM" pattern="[0-9]{1,3}" size=3 min="0" max="127" maxlength="3" value="<?php echo esc_attr($opt_AlphaWM) ?>"/>
            <br><font color="#808080"><em><?php esc_html_e('From 0 (completely opaque) to 127 (completely transparent)',NXTWM_DOMAIN); ?></em></font>
        </td></tr>

        <tr valign="top">
        <th scope="row"><?php esc_html_e('Orientation',NXTWM_DOMAIN); ?></th>
        <td><div style="display: inline-block; color:#0250BB;" align="center" id="optDegreeId"><em><?php echo esc_attr($opt_DegreeWM) ?>&deg; </em></div>
            <input type="range" oninput="nxtwm_Degree_SliderChange(this.value);" id="optDegreeWM_slider" name="optDegreeWM_slider" style="width:50%;margin-bottom:00px;" min="0" max="360" value="<?php echo esc_attr($opt_DegreeWM) ?>" />
            <input type="hidden" id="optDegreeWM" name="optDegreeWM" pattern="[0-9]{1,3}" size=3 min="0" max="360" maxlength="3" value="<?php echo esc_attr($opt_DegreeWM) ?>"/>
            <br><font color="#808080"><em><?php esc_html_e('From 0&deg; to 360&deg;',NXTWM_DOMAIN); ?></em></font>
        </td></tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Mosaic',NXTWM_DOMAIN); ?></th>
        <td><?php esc_html_e('Repeat the watermark text',NXTWM_DOMAIN); ?><br>
            <div name="divMosaicSize" id="divMosaicSize">
            <?php esc_html_e('Mosaic size',NXTWM_DOMAIN); ?>
            <select name="optLB_Mosaic" id="optLB_Mosaic" onload="nxtwm_LB_Mosaic_Change(this.value);" oninput="nxtwm_LB_Mosaic_Change(this.value);" class="xxx" />
                    <option <?php echo ($opt_LB_Mosaic=='None'?'selected ':''); ?> value="None"><?php esc_html_e('None',NXTWM_DOMAIN); ?></option>
                    <option <?php echo ($opt_LB_Mosaic=='2x2'?'selected ':''); ?> value="2x2">2x2</option>
                    <option <?php echo ($opt_LB_Mosaic=='3x3'?'selected ':''); ?> value="3x3">3x3</option>
                    <option <?php echo ($opt_LB_Mosaic=='4x4'?'selected ':''); ?> value="4x4">4x4</option>
                    <option <?php echo ($opt_LB_Mosaic=='5x5'?'selected ':''); ?> value="5x5">5x5</option>
                    <option <?php echo ($opt_LB_Mosaic=='6x6'?'selected ':''); ?> value="6x6">6x6</option>
                    <option <?php echo ($opt_LB_Mosaic=='7x7'?'selected ':''); ?> value="7x7">7x7</option>
                    <option <?php echo ($opt_LB_Mosaic=='Custom'?'selected':''); ?> value="Custom"><?php esc_html_e('Custom',NXTWM_DOMAIN); ?></option>
            </select><br>
            </div>
            <div name="divCustom" id="divCustom">
                 <?php esc_html_e('Custom Mosaic size',NXTWM_DOMAIN); ?> <input type="number" name="optCustom_X" size=2 min=2 max=9 value="<?php echo esc_attr($opt_Custom_X) ?>" class="xxx" />
                 x<input type="number" name="optCustom_Y" size=2 min=2 max=9 value="<?php echo esc_attr($opt_Custom_Y) ?>" class="xxx" />
            </div>
            <input type="checkbox" name="optMosaicCross" value=1 <?php echo($opt_MosaicCross==1?"checked ":"");?>class="wppd-ui-toggle" /><?php esc_html_e('Shift watermarks',NXTWM_DOMAIN); ?>
        </td></tr> 
        
        <tr valign="top" id="divAlignment" name="divAlignment">
        <th scope="row"><?php esc_html_e('Alignment',NXTWM_DOMAIN); ?></th>
        <td><?php esc_html_e('Offset X',NXTWM_DOMAIN); ?> <input type="number" name="optAlignWM_X" size=5 value="<?php echo esc_attr($opt_AlignWM_X) ?>" class="xxx" />px<br>
            <font color="#808080"><em><?php esc_html_e('Negative X value relative to right side',NXTWM_DOMAIN); ?></em></font><br>
            <?php esc_html_e('Offset Y',NXTWM_DOMAIN); ?> <input type="number" name="optAlignWM_Y" size=5 value="<?php echo esc_attr($opt_AlignWM_Y) ?>" class="xxx" />px<br>
            <font color="#808080"><em><?php esc_html_e('Negative Y value relative to bottom side',NXTWM_DOMAIN); ?></em></font>
        </td></tr>
        </table>
    </td>
    <th valign="top" align="top" colspan="7">
<?php
$tmpSample = "images/sample.jpg";
if ($opt_TextWM == "")
   { $SourceFileURL = plugins_url('/',__DIR__) . $tmpSample;  
     echo "<img src=\"" . esc_url($SourceFileURL) . "\">";
   }
else
   { $FontPath = plugin_dir_path( __DIR__ ) . "fonts/";
     $SourceFile = plugin_dir_path( __DIR__ ) . $tmpSample;            
     list($width, $height, $orig_type) = @getimagesize($SourceFile);
     $image_p = imagecreatetruecolor($width, $height);
     switch ($orig_type)
            { case IMAGETYPE_GIF:
                   $image = imagecreatefromgif($SourceFile);
                   break;
              case IMAGETYPE_JPEG:
                   $image = imagecreatefromjpeg($SourceFile);
                   break;
              case IMAGETYPE_PNG:
                   $image = imagecreatefrompng($SourceFile);
                   break;
              case IMAGETYPE_WEBP:
                   $image = imagecreatefromwebp($SourceFile);
                   break;
              default:
                   $image = imagecreatefromjpeg($SourceFile);
            }
     imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);
     list($red, $green, $blue) = sscanf($opt_ColorWM, "#%02x%02x%02x");
     $wm_color_txt = imagecolorallocatealpha($image_p, $red, $green, $blue, $opt_AlphaWM);
     
     switch ($opt_TextWM)
            { case "Year":
                   $opt_TextWM = "©" . date("Y");
                   break;
              case "RomanYear":
                   $opt_TextWM = "©" . nxtwm_GetRomanYear(date("Y"));
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
            
     if ($opt_AlignWM_X < 0) $opt_AlignWM_X = $width + $opt_AlignWM_X;
     if ($opt_AlignWM_Y < 0) $opt_AlignWM_Y = $height + $opt_AlignWM_Y;
     
     if ($opt_LB_Mosaic == "None")
        $ret = imagettftext($image_p, $opt_SizeWM, $opt_DegreeWM, $opt_AlignWM_X, $opt_AlignWM_Y, $wm_color_txt, $FontPath . $opt_FontWM, $opt_TextWM);
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
          $tmpMosaicStep_H = $width / ($tmpMosaicSize_H); 
          $tmpMosaicStep_V = $height / ($tmpMosaicSize_V);

          $c=0;
          if ($opt_MosaicCross)
             $A_MosaicPos_H[$c] = ($tmpMosaicStep_H/2) * $opt_MosaicCross * (-1);
          else
             $A_MosaicPos_H[$c] = 0;

          while($A_MosaicPos_H[$c] + $tmpMosaicStep_H <= $width)
               { $c++;
                 $A_MosaicPos_H[$c] = $A_MosaicPos_H[$c-1] + $tmpMosaicStep_H;
               }
              
          $d=0;
          $A_MosaicPos_V[$d] = ($tmpMosaicStep_V/2) + ($opt_SizeWM/2);
          while($A_MosaicPos_V[$d] + $tmpMosaicStep_V <= $height)
               { $d++; 
                 $A_MosaicPos_V[$d] = $A_MosaicPos_V[$d-1] + $tmpMosaicStep_V;
               }

          $tmpMosaicCrossChecked = ($opt_MosaicCross?1:0);
          for ($i=0;$i<=$c;$i++)
              { for ($j=0;$j<=$d;$j++)
                    { $tmpVal_X = $A_MosaicPos_H[$i] + (($j % 2) == 0) * ($tmpMosaicCrossChecked * ($tmpMosaicStep_H / 2));
                      $ret = imagettftext($image_p, $opt_SizeWM, $opt_DegreeWM, $tmpVal_X, $A_MosaicPos_V[$j], $wm_color_txt, $FontPath . $opt_FontWM, $opt_TextWM);
                    }
              }
        }
        
     if (!$ret)
        { _e('Error applying watermark!',NXTWM_DOMAIN);
          echo "<br>";
        }
        
     ob_start();
     switch ($orig_type)
            { case IMAGETYPE_GIF:
                   imagegif($image_p,NULL,100);
                   break;
              case IMAGETYPE_JPEG:
                   imagejpeg($image_p,NULL,100 );
                   break;
              case IMAGETYPE_PNG:
                   imagejpeg($image_p,NULL,100 );
                   break;
              case IMAGETYPE_WEBP:
                   imagewebp($image_p,NULL,100);
                   break;
              default:
                   imagejpeg($image_p,null,100);
            }
     $img = ob_get_clean();
     echo "<img src='data:image/gif;base64," . base64_encode($img) . "'>";
     imagedestroy($image);
     imagedestroy($image_p);
   }
?>
    </th>
    </tr>
    </table>    
    <?php submit_button(esc_html__('Apply text watermark',NXTWM_DOMAIN)); ?>
    </form>






             <?php break;
      case 'wm_image': ?> 
    <form name="ImgWM" method="post" action="options.php">
    <?php settings_fields('nwm-image-group'); ?>
    <?php do_settings_sections('nwm-image-group'); ?>

    <?php //list($wm_width, $wm_height, $wm_type) = @getimagesize($opt_ImageWM);
          if ($opt_ImageWM != "") 
             list($wm_width, $wm_height, $wm_type) = getimagesize($opt_ImageWM);
          else
             { $wm_width = 0; $wm_height = 0;
             }
    ?>

<table class="form-table">
<tr valign="top">
<td>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Watermark image',NXTWM_DOMAIN); ?><br><?php echo "(" . esc_attr($wm_width) . "x" . esc_attr($wm_height) . ")" ?></th>
        <td valign=center><div id="idsession">
        
        <?php
        if ($opt_ImageWM != "")
           { $pos = strrpos($opt_ImageWM,"/");
             $ImageFileWM = substr($opt_ImageWM,$pos+1);
             
             $pos = strpos($opt_ImageWM,"uploads");
             $pos = strpos($opt_ImageWM,"/",$pos+1);
             $ImagePathWM = substr($opt_ImageWM,$pos+1);
             
             $upload_path = wp_upload_dir(); $path = $upload_path['basedir'];
             $ImageFullPathWM = $path . '/' . $ImagePathWM;
             
             if (!file_exists($ImageFullPathWM))
                { echo "<font color=\"#ff0000\">";
                  esc_html_e('Watermark image not found!',NXTWM_DOMAIN);
                  echo "<br><em>" . esc_attr($ImagePathWM) . "</em></font><br>";
                  $opt_ImageWM = "";
                }
           }
          
        if ($opt_ImageWM != "")
           { echo "<div id=\"divImageWM\" name=\"divImageWM\">" . esc_attr($opt_ImageWM) . "</div><br>";
             echo "<input name=\"optImageWM\" id=\"optImageWM\" xtype=\"text\" type=\"hidden\" value=\"" . esc_attr($opt_ImageWM) . "\">";
             echo "<input name=\"upload-button\" id=\"upload-button\" type=\"button\" class=\"button\" value=\"" . esc_html__('Change Image watermark',NXTWM_DOMAIN) . "\"><br><em><font color=\"#808080\">" . esc_html__('Press Apply button to see changes.',NXTWM_DOMAIN) . "</font></em>";
           }
        else         
           { esc_html_e('Upload an image to use as watermark',NXTWM_DOMAIN); echo "<br>";
             echo "<input name=\"optImageWM\" id=\"optImageWM\" xtype=\"text\" type=\"hidden\">";
             echo "<input name=\"upload-button\" id=\"upload-button\" type=\"button\" class=\"button\" value=\"" . esc_html__('Upload Image watermark',NXTWM_DOMAIN) . "\"><br><em><font color=\"#808080\">" . esc_html__('Press Apply button to see changes.',NXTWM_DOMAIN) . "</font></em>";
           }
        ?>
        </div></td></tr>

        <tr valign="top">
        <th scope="row"><?php esc_html_e('Orientation',NXTWM_DOMAIN); ?></th>
        <td><?php esc_html_e('Set the orientation to 0&deg; to enable \'Opacity\' and \'Fit to image\' options',NXTWM_DOMAIN); ?><br>
            <div style="display: inline-block; color:#0250BB;" align="center" id="optDegreeImgId"><em><?php echo esc_attr($opt_DegreeImgWM) ?>&deg; </em></div>
            <input type="range" oninput="nxtwm_DegreeImg_SliderChange(this.value);" id="optDegreeImgWM_slider" name="optDegreeImgWM_slider" style="width:50%;margin-bottom:00px;" min="0" max="360" value="<?php echo esc_attr($opt_DegreeImgWM) ?>" />
            <input type="hidden" id="optDegreeImgWM" name="optDegreeImgWM" pattern="[0-9]{1,3}" size=3 min="0" max="360" maxlength="3" value="<?php echo esc_attr($opt_DegreeImgWM) ?>"/>
            <br><font color="#808080"><em><?php esc_html_e('From 0&deg; to 360&deg;',NXTWM_DOMAIN); ?></em></font>
        </td></tr>
        
        <tr valign="top" id="Opacity" name="Opacity">
        <th scope="row"><?php esc_html_e('Opacity',NXTWM_DOMAIN); ?></th>
        <td><?php esc_html_e('Set the opacity (gif & jpg only, with <b>no</b> orientation)',NXTWM_DOMAIN); ?><br>
            <div style="display: inline-block; color:#0250BB;" align="center" id="optOpacId"><em><?php echo esc_attr($opt_OpacWM) ?>%</em></div>
            <input type="range" oninput="nxtwm_Opac_SliderChange(this.value);" id="optOpacWM_slider" name="optOpacWM_slider" style="width:50%;margin-bottom:0px;" min="0" max="100" value="<?php echo esc_attr($opt_OpacWM) ?>"/>
            <input type="hidden" id="optOpacWM" name="optOpacWM" pattern="[0-9]{1,3}" size=3 min="0" max="100" maxlength="3" value="<?php echo esc_attr($opt_OpacWM) ?>"/>
            <br><font color="#808080"><em><?php esc_html_e('From 0% (fully transparent) to 100% (fully opaque)',NXTWM_DOMAIN); ?></em></font>
        </td></tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Special effects',NXTWM_DOMAIN); ?></th>
        <td><input type="checkbox" name="optGreyscaleWM" value=1 <?php echo($opt_GreyscaleWM==1?"checked ":"");?>class="wppd-ui-toggle" /><?php esc_html_e('Greyscale watermark',NXTWM_DOMAIN); ?><br>
            <input type="checkbox" name="optNegateWM" value=1 <?php echo($opt_NegateWM==1?"checked ":"");?>class="wppd-ui-toggle" /><?php esc_html_e('Negative watermark',NXTWM_DOMAIN); ?>
        </td></tr>

        <tr valign="top">
        <th scope="row"><?php esc_html_e('Position',NXTWM_DOMAIN); ?></th>
        <td><table border="0" style="border-collapse: collapse;">
            <tr><td style="padding: 1px;"colspan="3"><input type="radio" name="optPosImgWM" value="" <?php echo($opt_PosImgWM==""?"checked ":"");?> /><?php esc_html_e('None',NXTWM_DOMAIN); ?> <font color="#808080"><em><?php esc_html_e('(Use Aligment parameters below)',NXTWM_DOMAIN); ?></em></font></td>
            <tr><td style="padding: 1px;"><input type="radio" name="optPosImgWM" value="ul" <?php echo($opt_PosImgWM=="ul"?"checked ":"");?> /><?php esc_html_e('Upper left',NXTWM_DOMAIN); ?></td>
                <td style="padding: 1px;"><input type="radio" name="optPosImgWM" value="uc" <?php echo($opt_PosImgWM=="uc"?"checked ":"");?> /><?php esc_html_e('Upper center',NXTWM_DOMAIN); ?></td>
                <td style="padding: 1px;"><input type="radio" name="optPosImgWM" value="ur" <?php echo($opt_PosImgWM=="ur"?"checked ":"");?> /><?php esc_html_e('Upper right',NXTWM_DOMAIN); ?></td>
            <tr><td style="padding: 1px;"><input type="radio" name="optPosImgWM" value="ml" <?php echo($opt_PosImgWM=="ml"?"checked ":"");?> /><?php esc_html_e('Middle left',NXTWM_DOMAIN); ?></td>
                <td style="padding: 1px;"><input type="radio" name="optPosImgWM" value="mc" <?php echo($opt_PosImgWM=="mc"?"checked ":"");?> /><?php esc_html_e('Middle center',NXTWM_DOMAIN); ?></td>
                <td style="padding: 1px;"><input type="radio" name="optPosImgWM" value="mr" <?php echo($opt_PosImgWM=="mr"?"checked ":"");?> /><?php esc_html_e('Middle right',NXTWM_DOMAIN); ?></td>
            <tr><td style="padding: 1px;"><input type="radio" name="optPosImgWM" value="bl" <?php echo($opt_PosImgWM=="bl"?"checked ":"");?> /><?php esc_html_e('Bottom left',NXTWM_DOMAIN); ?></td>
                <td style="padding: 1px;"><input type="radio" name="optPosImgWM" value="bc" <?php echo($opt_PosImgWM=="bc"?"checked ":"");?> /><?php esc_html_e('Bottom center',NXTWM_DOMAIN); ?></td>
                <td style="padding: 1px;"><input type="radio" name="optPosImgWM" value="br" <?php echo($opt_PosImgWM=="br"?"checked ":"");?> /><?php esc_html_e('Bottom right',NXTWM_DOMAIN); ?></td>
            </table>               
        </td></tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Alignment',NXTWM_DOMAIN); ?></th>
        <td><?php esc_html_e('Offset X',NXTWM_DOMAIN); ?> <input type="number" name="optAlignImgWM_X" size=5 value="<?php echo esc_attr($opt_AlignImgWM_X) ?>" class="xxx" />px<br>
            <font color="#808080"><em><?php esc_html_e('Negative X value relative to right side',NXTWM_DOMAIN); ?></em></font><br>
            <?php esc_html_e('Offset Y',NXTWM_DOMAIN); ?> <input type="number" name="optAlignImgWM_Y" size=5 value="<?php echo esc_attr($opt_AlignImgWM_Y) ?>" class="xxx" />px<br>
            <font color="#808080"><em><?php esc_html_e('Negative Y value relative to bottom side',NXTWM_DOMAIN); ?></em></font><br>
            <div id="No-Orientation" name="No-Orientation"><hr><br>
            <input type="checkbox" name="optFitWM_Width" value=1 <?php echo($opt_FitWM_Width==1?"checked ":"");?>class="wppd-ui-toggle" /><?php esc_html_e('Fit watermark to image width',NXTWM_DOMAIN); ?><br>
            <font color="#808080"><em><?php esc_html_e('Do NOT use Offset X but Margin X parameter',NXTWM_DOMAIN); ?></em></font><br>
            <input type="checkbox" name="optFitWM_Height" value=1 <?php echo($opt_FitWM_Height==1?"checked ":"");?>class="wppd-ui-toggle" /><?php esc_html_e('Fit watermark to image height',NXTWM_DOMAIN); ?><br>
            <font color="#808080"><em><?php esc_html_e('Do NOT use Offset Y but Margin Y parameter',NXTWM_DOMAIN); ?></em></font><br>
            <input type="checkbox" name="optKeepRatio" value=1 <?php echo($opt_KeepRatio==1?"checked ":"");?>class="_wppd-ui-toggle" /><?php esc_html_e('Keep aspect ratio',NXTWM_DOMAIN); ?><br><br>
            <br>
            Margin X <input type="number" min="0" name="optMargin_X" size=5 value="<?php echo esc_attr($opt_Margin_X) ?>" class="xxx" />px<br><font color="#808080"><em><?php esc_html_e('(Left and right margins around watermark)',NXTWM_DOMAIN); ?></em></font><br>
            Margin Y <input type="number" min="0" name="optMargin_Y" size=5 value="<?php echo esc_attr($opt_Margin_Y) ?>" class="xxx" />px<br><font color="#808080"><em><?php esc_html_e('(Top and bottom margins around watermark)',NXTWM_DOMAIN); ?></em></font>
            </div>
        </td></tr>
    </table>
</td>
<th valign="top" align="top" colspan="4">
<?php
$tmpSample = "images/sample.jpg";
if ($opt_ImageWM == "")
   { $SourceFileURL = plugins_url('/',__DIR__) . $tmpSample;  
     echo "<img src=\"" . esc_url($SourceFileURL) . "\">";
   }
else
   { $SourceFile = plugin_dir_path( __DIR__ ) . $tmpSample;            
     list($width, $height, $orig_type) = @getimagesize($SourceFile);
     $image_p2 = imagecreatetruecolor($width, $height);
     switch ($orig_type)
            { case IMAGETYPE_GIF:
                   $image2 = imagecreatefromgif($SourceFile);
                   break;
              case IMAGETYPE_JPEG:
                   $image2 = imagecreatefromjpeg($SourceFile);
                   break;
              case IMAGETYPE_PNG:
                   $image2 = imagecreatefrompng($SourceFile);
                   break;
              case IMAGETYPE_WEBP:
                   $image2 = imagecreatefromwebp($SourceFile);
                   break;
              default:
                   die("Image format not allowed!");
            }
     imagecopyresampled($image_p2, $image2, 0, 0, 0, 0, $width, $height, $width, $height);

     list($wm_width, $wm_height, $wm_type) = @getimagesize($opt_ImageWM); 

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
            }

    if ($opt_DegreeImgWM != 0)
       { imagealphablending($watermark, false);
         imagesavealpha($watermark, true);
         $pngTransparency = imagecolorallocatealpha($watermark , 0, 0, 0, 127);
         $watermark = imagerotate($watermark, $opt_DegreeImgWM, $pngTransparency);
         imagealphablending($watermark, false);
         imagesavealpha($watermark, true);
       }
       
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
        { $tmpWidth = $width;
          $tmpHeight = $height;
          switch ($opt_PosImgWM)
            { case "ul": $tmpAlignIMG_X = 0;
                         $tmpAlignIMG_Y = 0;
                         break;
              case "uc": $tmpAlignIMG_X = ($tmpWidth-$wm_NewWidth)/2;
                         $tmpAlignIMG_Y = 0;
                         break;
              case "ur": $tmpAlignIMG_X = $tmpWidth-$wm_NewWidth;
                         $tmpAlignIMG_Y = 0;
                         break;
              case "ml": $tmpAlignIMG_X = 0;
                         $tmpAlignIMG_Y = ($tmpHeight-$wm_NewHeight)/2;
                         break;
              case "mc": $tmpAlignIMG_X = ($tmpWidth-$wm_NewWidth)/2;
                         $tmpAlignIMG_Y = ($tmpHeight-$wm_NewHeight)/2;
                         break;
              case "mr": $tmpAlignIMG_X = $tmpWidth-$wm_NewWidth;
                         $tmpAlignIMG_Y = ($tmpHeight-$wm_NewHeight)/2;
                         break;
              case "bl": $tmpAlignIMG_X = 0;
                         $tmpAlignIMG_Y = $tmpHeight-$wm_NewHeight;
                         break;
              case "bc": $tmpAlignIMG_X = ($tmpWidth-$wm_NewWidth)/2;
                         $tmpAlignIMG_Y = $tmpHeight-$wm_NewHeight;
                         break;
              case "br": $tmpAlignIMG_X = $tmpWidth-$wm_NewWidth;
                         $tmpAlignIMG_Y = $tmpHeight-$wm_NewHeight;
                         break;
              default:   $tmpAlignIMG_X = 0;
                         $tmpAlignIMG_Y = 0;
            }
          $opt_AlignImgWM_X = $tmpAlignIMG_X;
          $opt_AlignImgWM_Y = $tmpAlignIMG_Y;
        }
     else
        { if ($opt_AlignImgWM_X < 0) $opt_AlignImgWM_X = $width + $opt_AlignImgWM_X;
          if ($opt_AlignImgWM_Y < 0) $opt_AlignImgWM_Y = $height + $opt_AlignImgWM_Y;
        }

     if ($opt_DegreeImgWM != 0)
        { imagecopy($image_p2, $watermark, $opt_AlignImgWM_X, $opt_AlignImgWM_Y, 0, 0, $wm_NewWidth, $wm_NewHeight);
        }
     else
        { $wmRatio = $wm_width / $wm_height;
          $wm_NewWidth = $wm_width;
          $wm_NewHeight = $wm_height;
          $FitWidth = $wm_width;
          $FitHeight = $wm_height;
          if ($opt_PosImgWM == "")
             { $FitWidth = ($wm_NewWidth<$width?$wm_NewWidth:$width);
               $FitHeight = ($wm_NewHeight<$height?$wm_NewHeight:$height);
          
               if ($opt_FitWM_Width)
                  { $opt_AlignImgWM_X = $opt_Margin_X;
                    $FitWidth = $width - (2 * $opt_Margin_X);
                    if ($FitWidth <= 0) $FitWidth = $width;
                    if ($opt_KeepRatio and !$opt_FitWM_Height) $FitHeight = $FitWidth / $wmRatio;
                    if ($FitHeight <= $height)
                       { if ($opt_AlignImgWM_Y + $FitHeight >= $height) $opt_AlignImgWM_Y = $height - $FitHeight;
                         if ($opt_AlignImgWM_Y + $FitHeight <= 0) $opt_AlignImgWM_Y =0;
                       }
                  }
             
               if ($opt_FitWM_Height)
                  { $opt_AlignImgWM_Y = $opt_Margin_Y;
                    $FitHeight = $height - (2 * $opt_Margin_Y);
                    if ($FitHeight <= 0) $FitHeight = $height;
                    if ($opt_KeepRatio and !$opt_FitWM_Width) $FitWidth = $FitHeight * $wmRatio;
                    if ($FitWidth <= $width)
                       { if ($opt_AlignImgWM_X + $FitWidth >= $width) $opt_AlignImgWM_X = $width - $FitWidth;
                         if ($opt_AlignImgWM_X + $FitWidth <= 0) $opt_AlignImgWM_X = 0;
                       }
                  }
               $watermark = imagescale($watermark,$FitWidth,$FitHeight);
             }
          
          if (($wm_type == IMAGETYPE_GIF) or ($wm_type == IMAGETYPE_JPEG))
             imagecopymerge($image_p2, $watermark, $opt_AlignImgWM_X, $opt_AlignImgWM_Y, 0, 0, $FitWidth, $FitHeight,$opt_OpacWM);
          else
             imagecopy($image_p2, $watermark, $opt_AlignImgWM_X, $opt_AlignImgWM_Y, 0, 0, $FitWidth, $FitHeight);
        }

     ob_start();
     switch ($orig_type)
            { case IMAGETYPE_GIF:
                   imagegif($image_p2,NULL,100);
                   break;
              case IMAGETYPE_JPEG:
                   imagejpeg($image_p2,NULL,100 );
                   break;
              case IMAGETYPE_PNG:
                   imagejpeg($image_p2,NULL,100 );
                   break;
              case IMAGETYPE_WEBP:
                   imagewebp($image_p2,NULL,100);
                   break;
              default:
                   die("Image format not allowed!");
            }
     $img2 = ob_get_clean();
     echo "<img src='data:image/gif;base64," . base64_encode($img2) . "'>";
     imagedestroy($image2);
     imagedestroy($image_p2);
     imagedestroy($watermark);
   }
?>
</th>
</tr>
</table>    
<?php submit_button(esc_html__('Apply image watermark',NXTWM_DOMAIN)); ?>
</form>
        <?php break;





      case 'wm_media': ?> 
<?php 
  echo "<h2>" . esc_html__('Backup/Restore media images',NXTWM_DOMAIN) . "</h2>";

  if (isset($_POST['B_restore']) && check_admin_referer('B_restore_clicked'))
     { nxtwm_RestoreBackupAction();
     }

  echo "<form action=\"options.php?page=nwm-acp&tab=wm_media\" method=\"post\">";
  wp_nonce_field("B_restore_clicked");
  echo "<input type=\"hidden\" value=\"true\" name=\"B_restore\" />";
  submit_button(esc_html__('Restore',NXTWM_DOMAIN));
  echo "</form>";
?>
   
      <form method="post" action="options.php">
   <?php settings_fields('nwm-media-group'); ?>
   <?php do_settings_sections('nwm-media-group'); ?>

        

        <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Backup',NXTWM_DOMAIN); ?></th>
        <td><input type="checkbox" name="optBackupImgWM" value=1 <?php echo($opt_BackupImgWM==1?"checked ":"");?>class="wppd-ui-toggle" /> <?php esc_html_e('Backup images before watermark',NXTWM_DOMAIN); ?>
        <br><font color="#808080"><em><?php esc_html_e('Stored at',NXTWM_DOMAIN); ?> <?php echo esc_attr($_SERVER['SERVER_NAME']) . "/wp-content/" . NXTWM_IMG_BACKUP_PATH ."/";?></em></font>
        </td></tr>

        <tr valign="top">
        <th scope="row"><?php esc_html_e('Restore',NXTWM_DOMAIN); ?></th>
        <td><input type="radio" name="optBackupAllWM" value=0 <?php echo($opt_BackupAllWM==0?"checked ":"");?> /> <?php esc_html_e('Restore only current existing images',NXTWM_DOMAIN); ?><br>
            <input type="radio" name="optBackupAllWM" value=1 <?php echo($opt_BackupAllWM==1?"checked ":"");?> /> <?php esc_html_e('Restore all images from backup directory',NXTWM_DOMAIN); ?>
        </td></tr>
   </table>    
   <?php submit_button(esc_html__('Save',NXTWM_DOMAIN)); ?>
   </form>  
   <?php break;      
  
  
  
  
  
      case 'wm_settings':?>
   <form method="post" action="options.php">
   <?php settings_fields('nwm-general-group'); ?>
   <?php do_settings_sections('nwm-general-group'); ?>

    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Watermarking',NXTWM_DOMAIN); ?></th>
        <td><input type="checkbox" name="optAutoWM" value=1 <?php echo($opt_AutoWM==1?"checked ":"");?>class="wppd-ui-toggle" /> <?php esc_html_e('Watermark automatically images when uploading in the media gallery',NXTWM_DOMAIN); ?>
        </td></tr>

        <tr valign="top">
        <th scope="row"><?php esc_html_e('Watermark types',NXTWM_DOMAIN); ?></th>
        <td><?php esc_html_e('Select to activate text watermark, image watermark, or both',NXTWM_DOMAIN); ?><br>
            <input type="checkbox" name="optActiveTxtWM" value=1 <?php echo($opt_ActiveTxtWM==1?"checked ":"");?>class="wppd-ui-toggle" /> <?php esc_html_e('Activate text watermark',NXTWM_DOMAIN); ?><br>
            <input type="checkbox" name="optActiveImgWM" value=1 <?php echo($opt_ActiveImgWM==1?"checked ":"");?>class="wppd-ui-toggle" /> <?php esc_html_e('Activate image watermark',NXTWM_DOMAIN); ?>
        </td></tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Image quality',NXTWM_DOMAIN); ?></th>
        <td><?php esc_html_e('Set the image quality - Default value (-1) uses the default IJG quality value (about 75)',NXTWM_DOMAIN); ?><br>
            <div style="display: inline-block; color:#0250BB;" align="center" id="optQualityId"><em><?php echo esc_attr($opt_Quality) ?></em></div>
            <input type="range" oninput="nxtwm_optQuality_SliderChange(this.value);" id="optQuality_slider" name="optAlphaWM_slider" style="width:50%;margin-bottom:0px;" min="-1" max="100" value="<?php echo esc_attr($opt_Quality) ?>"/>
            <input type="hidden" id="optQuality" name="optQuality" pattern="[0-9]{1,3}" size=3 min="-1" max="100" maxlength="3" value="<?php echo esc_attr($opt_Quality) ?>"/>
            <br><font color="#808080"><em><?php esc_html_e('From 0 (worst quality, smaller file) to 100 (best quality, biggest file)',NXTWM_DOMAIN); ?></em></font>
        </td></tr>        

        <tr valign="top">
        <th scope="row"><?php esc_html_e('Image types',NXTWM_DOMAIN); ?></th>
        <td><?php esc_html_e('Select the image types to be watermarked',NXTWM_DOMAIN); ?><br>
            <input type="checkbox" name="optTypeGIF" value=1 <?php echo($opt_TypeGIF==1?"checked ":"");?> />.gif<br>
            <input type="checkbox" name="optTypeJPEG" value=1 <?php echo($opt_TypeJPEG==1?"checked ":"");?> />.jpg/.jpeg<br>
            <input type="checkbox" name="optTypePNG" value=1 <?php echo($opt_TypePNG==1?"checked ":"");?> />.png<br>
            <input type="checkbox" name="optTypeWEBP" value=1 <?php echo($opt_TypeWEBP==1?"checked ":"");?> />.webp
        </td></tr>

        <tr valign="top">
        <th scope="row"><?php esc_html_e('Image sizes',NXTWM_DOMAIN); ?></th>
        <td><?php esc_html_e('Select the image sizes to be watermarked',NXTWM_DOMAIN); ?><br>
        <?php
        for ($i=1;$i<=$NbMediaSizes;$i++)
            { if ($A_SizeName[$i] == NXTWM_FULL_SIZE)
                 { $tmpWidth = "...";
                   $tmpHeight = "...";
                 }
              else
                 { $tmpWidth = $A_SizeWidth[$i];
                   $tmpHeight = $A_SizeHeight[$i];
                 }
            
              if(${'opt_MS'.$i} == 1)
                { ?>
                  <input type="checkbox" name="optMS<?php echo $i; ?>" value=1 checked><?php echo "<b>" . esc_html__($A_SizeName[$i]) . "</b> (" . esc_attr($tmpWidth) . "x" . esc_attr($tmpHeight) . ")<br>";?>
                  <?php
                }
              else
                { ?>
                  <input type="checkbox" name="optMS<?php echo $i; ?>" value=1><?php echo esc_html__($A_SizeName[$i]) . " (" . esc_attr($tmpWidth) . "x" . esc_attr($tmpHeight) . ")<br>";?>
                  <?php
                }
            }
        ?>
        </td></tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Post types',NXTWM_DOMAIN); ?></th>
        <td><?php esc_html_e('Select the post types to be watermarked',NXTWM_DOMAIN); ?><br>        
        
        <?php 
        for ($i=1;$i<=$NbPostTypes;$i++)
            { if(${'opt_PT'.$i} == 1)
                { ?>
                  <input type="checkbox" name="optPT<?php echo $i; ?>" value=1 checked><?php echo "<b>" . esc_html__($A_PostTypeName[$i]) . "</b><br>";?>
                  <?php
                }
              else
                { ?>
                  <input type="checkbox" name="optPT<?php echo $i; ?>" value=1><?php echo esc_html__($A_PostTypeName[$i]) . "<br>";?>
                  <?php
                }
            }
        ?> 
        </td></tr>    
    </table>
        
    <h2 class="title"><?php esc_html_e('Content protection',NXTWM_DOMAIN); ?></h2>
       
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Copy protection',NXTWM_DOMAIN); ?></th>
        <td><input type="checkbox" name="optDisableCopy" value=1 <?php echo($opt_DisableCopy==1?"checked ":"");?>class="wppd-ui-toggle" /> <?php esc_html_e('Disable right click and copy content',NXTWM_DOMAIN); ?>
        </td></tr>
    </table>   
    <?php submit_button(esc_html__('Save',NXTWM_DOMAIN)); ?>
</form>

        <?php break;
        
        default:
    } ?>
    </div>
  </div>
  