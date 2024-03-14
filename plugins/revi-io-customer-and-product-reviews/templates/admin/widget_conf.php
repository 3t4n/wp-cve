<label> <?=_e('Widget Type')?> <?=$widget_type?></label>
<select id="<?php echo $this->get_field_id( 'widget_type' ); ?>" name="<?php echo $this->get_field_id( 'widget_type' ); ?>" >
    <option value = "vertical" <?=($widget_type == 'vertical' ? 'selected="selected"' : '')?>> Vertical </option>
    <option value = "wide" <?=($widget_type == 'wide' ? 'selected="selected"' : '')?>> Wide </option>
</select>