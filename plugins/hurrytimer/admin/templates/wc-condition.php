<?php
$conditions = hurryt_wc_conditions();
$selected   = isset($selected) && !empty($selected) ? $selected: $conditions['stock_status'];
$conditionId = uniqid('condition_');
?>
<div class="hurryt-wc-condition hurryt-flex hurryt-items-center hurryt-mb-2" data-condition-id="<?php echo $conditionId ?>" >
    <select name="wc_conditions[<?php echo $groupId ?>][<?php echo $conditionId ?>][key]" class="hurryt-w-1/4 hurryt-mr-2 hurryt-wc-condition-key">
        <?php foreach ($conditions as $condition): ?>
            <option value="<?php echo $condition['key'] ?>" <?php echo selected($selected['key'], $condition['key']) ?>
            ><?php echo $condition['name'] ?></option>
        <?php endforeach;    ?>
    </select>
    <select  name="wc_conditions[<?php echo $groupId ?>][<?php echo $conditionId ?>][operator]" class="hurryt-w-1/4  hurryt-mr-2 hurryt-wc-condition-operator">
    <?php if(in_array( '==', $selected['operators'])): ?>
        <option value="==" <?php echo isset($active) && $active['key'] === '==' ? 'selected': '' ?>> is equal
            to
        </option>
<?php endif; ?>
<?php if(in_array( '!=', $selected['operators'])): ?>
        <option value="!=" <?php echo isset($active) && $active['operator'] === '!=' ? 'selected': '' ?> >is not
            equal to
        </option>
<?php endif; ?>
    <?php if(in_array( '<', $selected['operators'])): ?>
            <option value="<" <?php echo isset($active) && $active['operator'] === '<' ? 'selected': '' ?>>is less
                than
            </option>
    <?php endif; ?>
    <?php if(in_array( '>', $selected['operators'])): ?>
        <option value=">" <?php echo isset($active) && $active['operator'] === '>' ? 'selected': '' ?>>is greater
            than
        </option>
<?php endif; ?>
    </select>
    <?php $values = $selected['values']; ?>
    <?php
    if ( ! empty($values)):
        ?>
        <select name="wc_conditions[<?php echo $groupId ?>][<?php echo $conditionId ?>][value]" class="hurryt-w-1/4  hurryt-mr-2 hurryt-wc-condition-value">
            <?php foreach ($values as $value): ?>
                <option value="<?php echo $value['id'] ?>" <?php echo isset($active) && $active['value'] == $value['id'] ? 'selected': '' ?>
                ><?php echo $value['name'] ?></option>
            <?php endforeach; ?>
        </select>
    <?php
    else:
        ?>
        <?php
        if ($selected['type'] === 'number'):
            ?>
            <input type="number"  value="<?php echo isset($active) ? $active['value'] : '' ?>" name="wc_conditions[<?php echo $groupId ?>][<?php echo $conditionId ?>][value]"  class="hurryt-w-1/4  hurryt-mr-2 hurryt-wc-condition-value" />
        <?php
        endif;
    endif;
  ?>
    <button type="button" class="button button-default hurryt-mr-5 hurryt-add-wc-condition">And</button>
    <span class="hurryt-flex-grow text-right hurryt-flex hurryt-justify-center hurryt-delete-wc-condition"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 357 357" class="hurryt-fill-current hurryt-h-3 hurryt-w-3">
            <polygon points="357,35.7 321.3,0 178.5,142.8 35.7,0 0,35.7 142.8,178.5 0,321.3 35.7,357 178.5,214.2 321.3,357 357,321.3
			214.2,178.5 		"/>
        </svg></span>
</div>
