<?php
/**
 * @package   Easy_Related_Posts_Templates_Main
 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link      http://erp.xdark.eu
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
?>
<table class="lay-opt-table">
    <tr>
	<td>
	    <label for="position">Possition: </label>
	</td>
	<td>
	    <select class="" id="position" name="position">
		<option value="top" <?php echo selected($position, 'top'); ?>>Top</option>
		<option value="bottom" <?php echo selected($position, 'bottom'); ?>>Bottom</option>
	    </select>
	</td>
    </tr>
    <tr>
	<td>
	    <label for="numOfPostsPerRow">Number of posts per row: </label>
	</td>
	<td>
            <input class="erp-opttxt" id="numOfPostsPerRow" name="numOfPostsPerRow" type="text"value="<?php echo $numOfPostsPerRow; ?>" readonly="readonly"/>
            <div id="numOfPostsPerRowSlider"></div>
	</td>
    </tr>
    <tr>
	<td>
	    <label for="backgroundColor">Background color: </label>
	</td>
	<td>
	    <input class="erp-opttxt" id="backgroundColor" name="backgroundColor" type="text" value="<?php echo $backgroundColor; ?>"/>
	</td>
    </tr>
    <tr>
	<td>
	    <label for="backgroundTransparency">Background transparency: </label>
	</td>
	<td>
            <input class="erp-opttxt" id="backgroundTransparency" max="1.00" min="0.00" step="0.01" name="backgroundTransparency" type="number" value="<?php echo $backgroundTransparency; ?>"/>
            <div id="backgroundTransparencySlider"></div>
        </td>
    </tr>
    <tr>
	<td>
	    <label for="triggerAfter">Trigger after: </label>
	</td>
	<td>
	    <input class="erp-opttxt" id="triggerAfter" name="triggerAfter" max="0.90" min="0.10" step="0.01"  type="number" value="<?php echo $triggerAfter; ?>"/>
            <div id="triggerAfterSlider"></div>
	</td>
    </tr>
    <tr>
	<td>
	    <label for="thumbCaption">Use thumbnail captions: </label>
	</td>
	<td>
            <input class="erp-optchbx" id="thumbCaption" name="thumbCaption" type="checkbox" <?php checked((bool) $thumbCaption); ?> />
        </td>
    </tr>
</table>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        var valMap = [1,2,3,4,6,12];
        $("#numOfPostsPerRowSlider").slider({
            value: valMap.indexOf(<?php echo $numOfPostsPerRow; ?>),
            min: 0,
            max: valMap.length - 1,
            slide: function(event, ui) {
                $("#numOfPostsPerRow").val(valMap[ui.value]);
            }
        });
        
        jQuery('#backgroundColor').wpColorPicker();
        
        $("#backgroundTransparencySlider").slider({
            value: <?php echo $backgroundTransparency; ?>,
            min: 0,
            max: 1,
            step: 0.01,
            slide: function(event, ui) {
                $("#backgroundTransparency").val(ui.value);
            }
        });
        
        $("#triggerAfterSlider").slider({
            value: <?php echo $triggerAfter; ?>,
            min: 0.1,
            max: 0.9,
            step: 0.01,
            slide: function(event, ui) {
                $("#triggerAfter").val(ui.value);
            }
        });
    });
</script>