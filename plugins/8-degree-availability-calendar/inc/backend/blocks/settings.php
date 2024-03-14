<?php
defined('ABSPATH') or die("No script kiddies please!");
    $edac_settings = $this->edac_settings;
    $edac_settings = (empty($edac_settings))?array('edac_unavailable_color'=>'#009b86','edac_optons'=>''):$edac_settings;
?>
<div class="edac-setting-body">
    <div class="edac-layout-wrapper">
        <div class="edac-backend-title"><?php _e('Calendar Layouts','edac-plugin');?></div>
        <div class="edac-layout-inner-wrap">
            <div class="edac-layout-first">
                <label>
                    <input type="radio" name="edac_layout" class="edac-layout" id="edac-first-layout" value="1" <?php if($edac_settings['edac_layout']==1)echo 'checked="checked"';?> />
                    <?php _e('Layout 1','edac-plugin');?>
                </label>
                <div id="edac-first-layout" class="edac-layout-demo"></div>
            </div>
            <div class="edac-unavailable-color-wrapper edac-uv-color">
                <label><?php _e('Unavailable color','edac-plugin');?></label>
                <input type="text" id="edac_unavailable_color" name="edac_unavailable_color" value="<?php echo esc_attr($edac_settings['edac_unavailable_color']);?>" />
            </div>
            <div class="edac-layout-second">
                <label>
                    <input type="radio" name="edac_layout" class="edac-layout" id="edac-second-layout" value="2" <?php if($edac_settings['edac_layout']==2)echo 'checked="checked"';?> />
                    <?php _e('Layout 2','edac-plugin');?>
                </label>
                <div id="edac-second-layout" class="edac-layout-demo"></div>
            </div>
        </div>
    </div><!-- End of layout wrapper -->
    
    <div class="edac-legend-wrap">
        <div class="edac-backend-title"><?php _e('Legend Options','edac-plugin');?></div>
        <label><?php _e('Show/Hide','edac-plugin');?>
            <input type="checkbox" name="edac_legend" class="edac-legend" value="1" <?php if($edac_settings['edac_legend']==1)echo 'checked="checked"';?> />
        </label>
        <div class="edac-legend-field">
            <label><?php _e('Legend Text','edac-plugin');?></label>
            <input type="text" id="edac-legend-text" name="edac_legend_text" value="<?php echo esc_attr($edac_settings['edac_legend_text']);?>" />
        </div>
    </div>
    <div class="edac-legend-wrap">
        <div class="edac-backend-title"><?php _e('Calendar Language','edac-plugin');?></div>
        <div class="edac-from-wrap">
           	<div class="edac-fz-wrap">
               <label><?php _e('Select Language','edac-plugin');?></label>
                <select name="edac_language">
            		<option value="" <?php if($edac_settings['edac_language']=='')echo 'selected="selected"';?>>Default</option>
                    <option value="af" <?php if($edac_settings['edac_language']=='af')echo 'selected="selected"';?>>Afrikaans</option>
            		<option value="sq" <?php if($edac_settings['edac_language']=='sq')echo 'selected="selected"';?>>Albanian (Gjuha shqipe)</option>
            		<option value="ar-DZ" <?php if($edac_settings['edac_language']=='ar-DZ')echo 'selected="selected"';?>>Algerian Arabic</option>
            		<option value="ar" <?php if($edac_settings['edac_language']=='ar')echo 'selected="selected"';?>>Arabic (&#8235;(&#1604;&#1593;&#1585;&#1576;&#1610;</option>
            		<option value="hy" <?php if($edac_settings['edac_language']=='hy')echo 'selected="selected"';?>>Armenian (&#1344;&#1377;&#1397;&#1381;&#1408;&#1381;&#1398;)</option>
            		<option value="az" <?php if($edac_settings['edac_language']=='az')echo 'selected="selected"';?>>Azerbaijani (Az&#601;rbaycan dili)</option>
            		<option value="eu" <?php if($edac_settings['edac_language']=='eu')echo 'selected="selected"';?>>Basque (Euskara)</option>
            		<option value="bs" <?php if($edac_settings['edac_language']=='bs')echo 'selected="selected"';?>>Bosnian (Bosanski)</option>
            		<option value="bg" <?php if($edac_settings['edac_language']=='bg')echo 'selected="selected"';?>>Bulgarian (&#1073;&#1098;&#1083;&#1075;&#1072;&#1088;&#1089;&#1082;&#1080; &#1077;&#1079;&#1080;&#1082;)</option>
            		<option value="ca" <?php if($edac_settings['edac_language']=='ca')echo 'selected="selected"';?>>Catalan (Catal&agrave;)</option>
            		<option value="zh-HK" <?php if($edac_settings['edac_language']=='zh-HK')echo 'selected="selected"';?>>Chinese Hong Kong (&#32321;&#39636;&#20013;&#25991;)</option>
            		<option value="zh-CN" <?php if($edac_settings['edac_language']=='zh-CN')echo 'selected="selected"';?>>Chinese Simplified (&#31616;&#20307;&#20013;&#25991;)</option>
            		<option value="zh-TW" <?php if($edac_settings['edac_language']=='zh-TW')echo 'selected="selected"';?>>Chinese Traditional (&#32321;&#39636;&#20013;&#25991;)</option>
            		<option value="hr" <?php if($edac_settings['edac_language']=='hr')echo 'selected="selected"';?>>Croatian (Hrvatski jezik)</option>
            		<option value="cs" <?php if($edac_settings['edac_language']=='cs')echo 'selected="selected"';?>>Czech (&#269;e&#353;tina)</option>
            		<option value="da" <?php if($edac_settings['edac_language']=='da')echo 'selected="selected"';?>>Danish (Dansk)</option>
            		<option value="nl" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Dutch (Nederlands)</option>
            		<option value="en-AU" <?php if($edac_settings['edac_language']=='en-AU')echo 'selected="selected"';?>>English/Australia</option>
            		<option value="en-NZ" <?php if($edac_settings['edac_language']=='en-NZ')echo 'selected="selected"';?>>English/New Zealand</option>
            		<option value="en-GB" <?php if($edac_settings['edac_language']=='en-GB')echo 'selected="selected"';?>>English/UK</option>
            		<option value="eo" <?php if($edac_settings['edac_language']=='eo')echo 'selected="selected"';?>>Esperanto</option>
            		<option value="et" <?php if($edac_settings['edac_language']=='et')echo 'selected="selected"';?>>Estonian (eesti keel)</option>
            		<option value="fo" <?php if($edac_settings['edac_language']=='fo')echo 'selected="selected"';?>>Faroese (f&oslash;royskt)</option>
            		<option value="fa" <?php if($edac_settings['edac_language']=='fa')echo 'selected="selected"';?>>Farsi/Persian (&#8235;(&#1601;&#1575;&#1585;&#1587;&#1740;</option>
            		<option value="fi" <?php if($edac_settings['edac_language']=='fi')echo 'selected="selected"';?>>Finnish (suomi)</option>
            		<option value="fr" <?php if($edac_settings['edac_language']=='fr')echo 'selected="selected"';?>>French (Fran&ccedil;ais)</option>
            		<option value="fr-CH" <?php if($edac_settings['edac_language']=='fr-CH')echo 'selected="selected"';?>>French/Swiss (Fran&ccedil;ais de Suisse)</option>
            		<option value="gl" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Galician</option>
            		<option value="de" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>German (Deutsch)</option>
            		<option value="el" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Greek (&#917;&#955;&#955;&#951;&#957;&#953;&#954;&#940;)</option>
            		<option value="he" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Hebrew (&#8235;(&#1506;&#1489;&#1512;&#1497;&#1514;</option>
            		<option value="hu" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Hungarian (Magyar)</option>
            		<option value="is" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Icelandic (&Otilde;slenska)</option>
            		<option value="id" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Indonesian (Bahasa Indonesia)</option>
            		<option value="it" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Italian (Italiano)</option>
            		<option value="ja" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Japanese (&#26085;&#26412;&#35486;)</option>
            		<option value="ko" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Korean (&#54620;&#44397;&#50612;)</option>
            		<option value="kz" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Kazakhstan (Kazakh)</option>
            		<option value="lv" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Latvian (Latvie&ouml;u Valoda)</option>
            		<option value="lt" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Lithuanian (lietuviu kalba)</option>
            		<option value="ml" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Malayalam</option>
            		<option value="ms" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Malaysian (Bahasa Malaysia)</option>
            		<option value="no" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Norwegian (Norsk)</option>
            		<option value="pl" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Polish (Polski)</option>
            		<option value="pt" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Portuguese (Portugu&ecirc;s)</option>
            		<option value="pt-BR" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Portuguese/Brazilian (Portugu&ecirc;s)</option>
            		<option value="rm" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Rhaeto-Romanic (Romansh)</option>
            		<option value="ro" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Romanian (Rom&acirc;n&#259;)</option>
            		<option value="ru" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Russian (&#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081;)</option>
            		<option value="sr" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Serbian (&#1089;&#1088;&#1087;&#1089;&#1082;&#1080; &#1112;&#1077;&#1079;&#1080;&#1082;)</option>
            		<option value="sr-SR" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Serbian (srpski jezik)</option>
            		<option value="sk" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Slovak (Slovencina)</option>
            		<option value="sl" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Slovenian (Slovenski Jezik)</option>
            		<option value="es" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Spanish (Espa&ntilde;ol)</option>
            		<option value="sv" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Swedish (Svenska)</option>
            		<option value="ta" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Tamil (&#2980;&#2990;&#3007;&#2996;&#3021;)</option>
            		<option value="th" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Thai (&#3616;&#3634;&#3625;&#3634;&#3652;&#3607;&#3618;)</option>
            		<option value="tj" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Tajikistan</option>
            		<option value="tr" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Turkish (T&uuml;rk&ccedil;e)</option>
            		<option value="uk" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Ukranian (&#1059;&#1082;&#1088;&#1072;&#1111;&#1085;&#1089;&#1100;&#1082;&#1072;)</option>
            		<option value="vi" <?php if($edac_settings['edac_language']=='nl')echo 'selected="selected"';?>>Vietnamese (Ti&#7871;ng Vi&#7879;t)</option>
            	</select>
            </div>
        </div>
    </div>
    <div class="edac-year-availability-wrapper">
        <div class="edac-backend-title"><?php _e('Year Availability','edac-plugin');?></div>
        <div class="edac-year-availability-inner-wrap">
            
            <div class="edac-from-wrap">
               	<div class="edac-fz-wrap">
                   <label><?php _e('From','edac-plugin');?></label>
                    <select class="edac-select-drop" id="edac-from" onchange="document.getElementById('edac-fromdisplayValue').value=this.options[this.selectedIndex].value;">
                		<?php
                            $start_year = date('Y');
                            for ($x = 0; $x <= 50; $x++)
                            {
                                ?>
                                    <option value="<?php echo $start_year;?>" <?php if($edac_settings['edac_from']==$start_year)echo 'selected="selected"';?>><?php echo $start_year;?></option>
                                <?php
                                $start_year++;
                            }
                        ?>
                	</select>
                	<input type="text" name="edac_from" value="<?php echo esc_attr($edac_settings['edac_from']);?>" id="edac-fromdisplayValue" class="edac-dis-value" onfocus="this.select()" />
                    <input type="hidden" class="edac-from-date" data-from-date="<?php echo esc_attr($edac_settings['edac_from']);?>" />
                </div>
            </div>
            <div class="edac-to-wrap">
                <div class="edac-fz-wrap">
                   <label><?php _e('To','edac-plugin');?></label>
                    <select class="edac-select-drop" id="edac-to" onchange="document.getElementById('edac-todisplayValue').value=this.options[this.selectedIndex].value;">
                		<?php
                            $start_year = date('Y');
                            for ($x = 0; $x <= 50; $x++)
                            {
                                ?>
                                    <option value="<?php echo $start_year;?>" <?php if($edac_settings['edac_to']==$start_year)echo 'selected="selected"';?>><?php echo $start_year;?></option>
                                <?php
                                $start_year++;
                            }
                        ?>
                	</select>
                	<input type="text" name="edac_to" value="<?php echo esc_attr($edac_settings['edac_to']);?>" id="edac-todisplayValue" class="edac-dis-value" onfocus="this.select()" />
                    <input type="hidden" class="edac-to-date" data-to-date="<?php echo esc_attr($edac_settings['edac_to']);?>" />
                </div>
            </div>
        </div>
    </div><!-- End of year availability wrapper -->
    
</div>