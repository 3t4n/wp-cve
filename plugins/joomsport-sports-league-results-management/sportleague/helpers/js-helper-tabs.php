<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class jsHelperTabs
{
    /*
     * $tabs array
     * $tabs['id'] - string
     * $tabs['title'] - string
     * $tabs['body'] - text
     */
    public static function draw($tabs, $rows)
    {
        if (count($tabs)) {
            $jscurtab = classJsportRequest::get('jscurtab');
            if ($jscurtab && substr($jscurtab, 0, 1) != '#') {
                $jscurtab = '#'.$jscurtab;
            }
            ?>
        <div class="tabs">    
            <?php
            if (count($tabs) > 1) {
                ?>
            
            <ul class="nav nav-tabs">
              <?php
              $is_isset_tab = false;
                for ($intA = 0; $intA < count($tabs); ++$intA) {
                    if ($jscurtab == '#'.$tabs[$intA]['id']) {
                        $is_isset_tab = true;
                    }
                }
                if (!$is_isset_tab) {
                    $jscurtab = '';
                }
                for ($intA = 0; $intA < count($tabs); ++$intA) {
                    $tab_ico = isset($tabs[$intA]['ico']) ? $tabs[$intA]['ico'] : tableS;
                    $tab_icoImg = isset($tabs[$intA]['icoImg']) ? $tabs[$intA]['icoImg'] : '';
                    
                    ?>
                <li class="nav-item">
                    <a data-toggle="tab" href="#<?php echo esc_attr($tabs[$intA]['id']);?>" class="navlink<?php echo (($intA == 0 && !$jscurtab) || ($jscurtab == '#'.$tabs[$intA]['id'])) ? ' active' : ''; ?>">
                        <i class="<?php echo esc_attr($tab_ico);?>" <?php if($tab_icoImg){echo ' style="background:url('.wp_get_attachment_image_url($tab_icoImg,array(24,24),false).'")';}?>></i> <span><?php echo wp_kses_post($tabs[$intA]['title']);
                    ?></span></a></li>
              <?php 
                }
                ?>
              
            </ul>
            <?php

            }
            ?>
            <div class="tab-content">
                <?php
                for ($intAi = 0; $intAi < count($tabs); ++$intAi) {
                    ?>
                    <div id="<?php echo esc_attr($tabs[$intAi]['id']);
                    ?>" class="tab-pane fade in<?php echo (($intAi == 0 && !$jscurtab) || ($jscurtab == '#'.$tabs[$intAi]['id'])) ? ' active' : '';
                    ?>">
                        <?php if ($tabs[$intAi]['text']) {
    ?>
                            <p><?php echo wp_kses($tabs[$intAi]['text'], jsHelperTabs::allowedInTabs());
    ?></p>
                        <?php 
} elseif (is_file(JOOMSPORT_PATH_VIEWS_ELEMENTS.$tabs[$intAi]['body'])) {
    ?>
                            <?php require JOOMSPORT_PATH_VIEWS_ELEMENTS.$tabs[$intAi]['body'];
    ?>
                        <?php 
}
                    ?>
                    </div>
                <?php 
                }
            ?>
                
            </div>
        </div>
        <?php

        }
    }

    public static function allowedInTabs(){
        $my_allowed = wp_kses_allowed_html( 'post' );
        // select
        $my_allowed['select'] = array(
            'class'  => array(),
            'id'     => array(),
            'name'   => array(),
            'style'  => array(),
            'onchange'   => array(),

        );
        // select options
        $my_allowed['option'] = array(
            'selected' => array(),
        );
        //form
        $my_allowed['form'] = array(
            'src' => array(),
        );
        return $my_allowed;
    }
}
