{function name=getField level=0}          {* short-hand *}
	{if $field.type == 'INPUT'}
	   <input class="{$class} form-control" id="{$field.id}" name="{$field.name}" value="{$field.value}" />
    {elseif $field.type == 'BUTTON' && 'api_endpoint'|array_key_exists:$field}
        <button type="button" {if !$field.enabled}disabled{/if} class="{$class} {$field.class} form-control" id="{$field.id}" name="{$field.name}">{$field.value}</button>
        <script>
            jQuery('#{$field.id}').on('click', function() {
                // Disable all buttons in this section once the button is clicked
                jQuery(".{$field_prefix}_form_element button[type=button]").prop("disabled", true);
                // Add a spinner icon to the button that was clicked
                jQuery("#{$field.id}").html("Busy <i class='fa fa-spinner fa-spin'></i>");

                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {

                    if (this.readyState === 4 && this.status === 200) {
                        var response = JSON.parse(this.responseText);

                        setTimeout(function () {
                            // Enable all buttons in this section once the button is clicked
                            jQuery(".{$field_prefix}_form_element button[type=button]").prop("disabled", false);
                            // Remove the spinner icon from the button that was clicked
                            jQuery("#{$field.id}").html("{$field.value}");

                            if (response.success) {
                                // @TODO: Display pretty alert box
                                alert('Update completed successfully.');
                            }
                            else {
                                // @TODO: Display pretty alert box
                                alert('Update did NOT complete successfully. Contact administrator.');
                            }
                        }, 2000);
                    }
                };

                var method =  ('{$field.api_method}' === '') ? 'GET' : '{$field.api_method}';

                xhttp.open(method, '{$field.api_endpoint}', true);
                xhttp.setRequestHeader('Content-type','application/json; charset=utf-8');
                xhttp.send();
            });
        </script>
	{elseif $field.type == 'TEXTAREA'}
	   <textarea class="{$class} form-control" id="{$field.id}" name="{$field.name}">{$field.value}</textarea>
    {elseif $field.type == 'BLANK'}
        &nbsp;
    {elseif $field.type == 'HEADER'}
        <h5>{$field.label}</h5>
        <p>{$field.value}</p>
	{elseif $field.type == 'CHECKBOX'}
		{foreach $field.options as $okey => $ovalue}
            <fieldset style="margin-top: 0px;">
                <label for="{$field.id}">
                    <input type="checkbox" {if !$field.enabled}disabled{/if} class="level-0 form-control" id="{$field.id}[]" name="{$field.name}[]" value="{$ovalue}" {if (!$field.value|is_array && $field.value == $ovalue) || ($field.value|is_array && $ovalue|in_array:$field.value)}checked{/if}>
                    {$okey}
                </label>
            </fieldset>
		{/foreach}
	{elseif $field.type == 'SELECT'}
	   <select class="{$class} form-control" id="{$field.id}" name="{$field.name}" >
	   		{foreach $field.options as $okey => $ovalue}
	   			<option class="level-0" value="{$ovalue}" {if $field.value == $ovalue}selected='selected'{/if}>{$okey}</option>
	   		{/foreach}
	   </select>
	{elseif $field.type == 'SELECT_PAGES'}
	   <div id="{$field.name}-box">
	   <select class="{$class} form-control" id="{$field.id}" name="{$field.name}{if $field.multiple}[]{/if}" {if $field.size}size="{$field.size}"{/if} {if $field.multiple}multiple{/if} {if $field.other}{$field.other}{/if}>
	   		{foreach $field.options as $okey => $ovalue}
			    {if $field.multiple && $field.value|is_array}
	   			<option class="level-0" value="{$okey}" {if $okey|in_array:$field.value}selected='selected'{/if}>{$ovalue}</option>
				{else}
	   			<option class="level-0" value="{$okey}" {if $field.value == {$okey}}selected='selected'{/if}>{$ovalue}</option>
				{/if}
				
	   		{/foreach}
	   </select>
	   </div>
	{elseif $field.type == 'IMAGE'}
		<input type="hidden" id="{$field.id}" name="{$field.name}" value="{$field.value}" />
		<input type="hidden" id="{$field.id}_prefix" name="{$field.name}_prefix" value="{$field_prefix}_{$field.name}" />
    	
    	<div id="{$field_prefix}_{$field.name}_image_info" name="{$field_prefix}_{$field.name}_image_info" class="{$field_prefix}_image_info">
	    	<div id="{$field_prefix}_{$field.name}_selected_image" name="{$field_prefix}_{$field.name}_selected_image" class="{$field_prefix}_selected_image">
	      		{if $field.value != ''}<img src="{$field.value}" /><br/>{/if}
	    	</div>
	    	<div name="{$field_prefix}_{$field.name}_url_display" id="{$field_prefix}_{$field.name}_url_display" class="{$field_prefix}_url_display">
	      		{if $field.value != ''}{$field.value}{else}No image selected{/if}
	    	</div>
	    	
    		<div id="{$field_prefix}_{$field.name}_image_funcs" name="{$field_prefix}_{$field.name}_image_funcs" class="{$field_prefix}_image_funcs">
		        <img src="images/media-button-image.gif" alt="Add photos from your media" /> 
				
				<a href="#" onclick="tspedev_show_media_window()" class="thickbox" title="Add an Image"> <strong>Click here to add/change your image</strong></a><br />
				<small>Note: To choose image click the "insert into post" button in the media uploader</small><br />
				
				<img src="images/media-button-image.gif" alt="Remove existing image" /> 
				<a href="#" onclick="tspedev_remove_image_url('{$field.id}', 'No image selected')"><strong>Click here to remove the existing image</strong></a><br />
    		</div>
    	</div>
    	
   		<script>
			{literal}
			jQuery(document).ready(function() {
			 
				window.send_to_editor = function(html) {
			{/literal}
                                var field_id = "{$field.id}";
			{literal}
                                tspedev_save_image_url(html, field_id);
				tb_remove();
			   }
			});
			{/literal}
		</script>
	{/if}
{/function}
<div class="row form-group {$field_prefix}_form_element" id="{$field.name}_container_div" style="">
	{if 'reverse_view'|array_key_exists:$field}
		<div class="col-sm-1">
            {getField field=$field}
	    </div>
        {if $field.type != 'HEADER'}
            <label for="{$field.id}" class="col-sm-11 control-label">{$field.label}{if 'tag'|array_key_exists:$field}&nbsp;<span class="{$field.tag_class}">{$field.tag}</span>{/if}</label>
        {/if}
    {elseif 'button_view'|array_key_exists:$field}
        <div class="col-sm-2">
            {getField field=$field}
        </div>
        {if $field.type != 'HEADER'}
            <label for="{$field.id}" class="col-sm-10 control-label">{$field.label}{if 'tag'|array_key_exists:$field}&nbsp;<span class="{$field.tag_class}">{$field.tag}</span>{/if}</label>
        {/if}
	{else}
        {if $field.type != 'HEADER'}
            <label for="{$field.id}" class="col-sm-3 control-label">{$field.label}{if 'tag'|array_key_exists:$field}&nbsp;<span class="{$field.tag_class}">{$field.tag}</span>{/if}</label>
        {/if}
        <div class="col-sm-9">
            {getField field=$field}
        </div>
	{/if}
	<div class="clear"></div>
	<div id="error-message-name"></div>
</div>

