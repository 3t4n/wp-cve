<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="table-responsive jsCalByMd">
<form role="form" method="post" lpformnum="1" action="<?php echo esc_url($lists["actionlink"]);?>">
    <!-- Matchday selectbox !-->
    <div class="searchMatchesDiv poolJSRight">
        <select name="filtersvar[mday]" id="matchDay" onchange="this.form.submit();">
          <?php
          
          if (count($lists['filters']['mday_list'])) {
              foreach ($lists['filters']['mday_list'] as $mday) {
                  echo '<option value="'.intval($mday->id).'" '.((isset($lists['filtersvar']->mday) && $lists['filtersvar']->mday == $mday->id) ? 'selected' : '').'>'.esc_html($mday->m_name).'</option>';
              }
          }
          ?>
        </select>
    </div>
    <!-- Matchday navigation !-->
    <div class="jscalMdayNav">
        <div class="jscalMdayPrev">
            <?php echo wp_kses_post($lists['prevlink']);?>
        </div>
        <div class="jscalMdayNext">
            <?php echo wp_kses_post($lists['nextlink']);?>
        </div>
    </div>
    <?php
    if(isset($rows[0])){
    $optionsPl = array("season_id" => $rows[0]->season_id, "group_id" => intval(classJsportRequest::get('group_id')));
    classJsportPlugins::get('addCalendarBeforeMatchList', $optionsPl);
    }
    ?>
    <div class="table-responsive">
        <?php
        echo jsHelper::getMatches($rows, $lists['pagination']);
        ?>
    </div>
</form>
    
</div>
