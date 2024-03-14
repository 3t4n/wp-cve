<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
?>
<?php
if(isset($args['md_navigation']) && $args["md_navigation"] == 1){
    $options = array("season_id" => (int)$args["id"]);
    $mday_list = classJsportgetmdays::getMdays($options);
    ?>
    <div class="shrtMdNav clearfix">
        <div class="col-xs-12 col-sm-6 input-group pull-right">
            <div class="input-group-btn shrtMdPrev">
                <?php $mdId =  classJsportgetmdays::getPrev(intval($args["id"]), intval($args["matchday_id"]));?>
                <button data-md="<?php echo esc_attr($mdId)?>" class="btn btn-default btnMdPrev <?php echo $mdId?"mdBtnEnbled":"mdBtnDisabled";?>" <?php echo $mdId?"":" disabled='true'";?>>&lt;</button>
            </div>
            <select class="shrtMday form-control" name="shrt_mdayId">
                <?php
                if (count($mday_list)) {
                    foreach ($mday_list as $mday) {
                        echo '<option value="'.esc_attr($mday->id).'" '.($args["matchday_id"] == $mday->id?' selected':'').' >'.esc_html($mday->m_name).'</option>';
                    }
                }
                ?>
            </select>
            <div class="input-group-btn shrtMdNext">
                <?php $mdId =  classJsportgetmdays::getNext(intval($args["id"]), intval($args["matchday_id"]));?>
                <button data-md="<?php echo esc_attr($mdId)?>" class="btn btn-default btnMdNext <?php echo $mdId?"mdBtnEnbled":"mdBtnDisabled";?>" <?php echo $mdId?"":" disabled='true'";?>>&gt;</button>
            </div>
        </div>
        <input type="hidden" name="shrtAttrs" value='<?php echo esc_attr(json_encode($args));?>' />
    </div>
    <?php
}
$enbl_slider = false;
$groupbydate = true;
$classname = $enbl_slider ? "jsSliderContainer":"jsDefaultContainer";
$display_name = $args["teamname"];
$module_id = rand(0, 2000);
echo '<div class="shrtMdMatches">';
require 'matches.php';
echo '</div>';
?>
