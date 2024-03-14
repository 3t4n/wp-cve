<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class jsAchvHelper
{
    public static function getADF($ef, $suff = '')
    {
        $return = '';
        if (count($ef)) {
            foreach ($ef as $key => $value) {
                if ($value != null) {
                    $return .=  '<div class="jstable-row">';
                    $return .=  '<div class="jstable-cell"><strong>'.$key.':</strong></div>';
                    $return .=  '<div class="jstable-cell">'.$value.'</div>';
                    $return .=  '</div>';
                }
            }
        }
        if ($return) {
            $return = '<div class="jstable">'.$return.'</div>';
        }
        //$return .= '</div>';
        return $return;
    }

    public static function nameHTML($name, $home = 1, $class = '')
    {
        return '<div class="js_div_particName">'.$name.'</div>';
    }

    public static function JsHeader($options)
    {
        
        $kl = '';
        if (classJsportAchvRequest::get('tmpl') != 'component') {
            $kl .= '<div class="">';
            $kl .= '<nav class="navbar navbar-default navbar-static-top" role="navigation">';
            $kl .= '<div class="navbar-header navHeadFull">';

            $kl .= '<ul class="nav navbar-nav pull-right navSingle">';
                //calendar
            if (isset($options['calendar']) && $options['calendar']) {
                $link = classJsportAchvLink::calendar('', $options['calendar'], true);
                $kl .= '<a class="btn btn-default" href="'.$link.'" title=""><i class="date pull-left"></i>'.__('Stages','joomsport-achievements').'</a>';
            }
                //table
            if (isset($options['standings']) && $options['standings']) {
                $link = classJsportAchvLink::season('', $options['standings'], true);
                $kl .= '<a class="btn btn-default" href="'.$link.'" title=""><i class="tableS pull-left"></i>'.__('Standings','joomsport-achievements').'</a>';
            }
            
            if (isset($options['playerlist']) && $options['playerlist']) {
                $link = classJsportLink::playerlist($options['playerlist']);
                $kl .= '<a class="btn btn-default" href="'.$link.'" title=""><i class="fa fa-user"></i>'.__('Player list','joomsport-achievements').'</a>';
            }
            //$kl .= classJsportPlugins::get('addHeaderButton', null);
            $kl .= '</ul></div></nav></div>';
        }
        //$kl .= self::JsHistoryBox($options);
        $kl .= self::JsTitleBox($options);
        $kl .= "<div class='jsClear'></div>";

        return $kl;
    }

    public static function JsTitleBox($options)
    {
        $kl = '';
        $kl .= '<div class="heading col-xs-12 col-lg-12">
                    <div class="heading col-xs-6 col-lg-6">
                        <!--h2>
                           
                        </h2-->
                    </div>
                    <div class="selection col-xs-6 col-lg-6 pull-right">
                        <form method="post">
                            <div class="data">
                                '.(isset($options['tourn']) ? $options['tourn'] : '').'
                                <input type="hidden" name="jscurtab" value="" />    
                            </div>
                        </form>
                    </div>
                </div>';

        return $kl;
    }

    public static function JsHistoryBox($options)
    {
        $kl = '<div class="history col-xs-12 col-lg-12">
          <ol class="breadcrumb">
            <li><a href="javascript:void(0);" onclick="history.back(-1);" title="[Back]">
                <i class="fa fa-long-arrow-left"></i>[Back]
            </a></li>
          </ol>
          <div class="div_for_socbut">'.(isset($options['print']) ? '' : '').'<div class="jsClear"></div></div>
        </div>';

        return $kl;
    }

    
    public static function getStageVals($val)
    {
        global $wpdb;
        $selvals = $wpdb->get_var('SELECT sel_value FROM '.$wpdb->jsprtachv_stages_val.' WHERE id='.absint($val)) ;
        return $selvals;    
    }
    
    public static function getAllSeason()
    {
        $seasons = array();
        
        $args = array(
                'post_parent' => 0,
                'post_type'   => 'jsprt_achv_season', 
                'numberposts' => -1,
                'post_status' => 'published',
                'orderby' => 'menu_order title',
                'order'   => 'ASC',
        );
        $sRoot = get_children( $args );   
        for($intA=0;$intA<count($sRoot);$intA++){
            $seasons[$intA] = $sRoot;
            $args = array(
                    'post_parent' => $sRoot->ID,
                    'post_type'   => 'jsprt_achv_season', 
                    'numberposts' => -1,
                    'post_status' => 'published',
                    'orderby' => 'menu_order title',
                    'order'   => 'ASC',
            );
            $children = get_children( $args );  
            for($intB=0;$intB<count($children);$intB++){
                $seasons[$intA]->childs[] = $children[$intB];
            }
        }
        return $seasons;
    }

    public static function isMobile()
    {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER['HTTP_USER_AGENT']);
    }
}
