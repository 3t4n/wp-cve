<div id="{$field_prefix}_box" class="postbox">
	<h3 class='handle'><span>{$title}</h3>
	<div class="inside">
		{foreach $shortcode_fields as $field}
			{include file="$EASY_DEV_FORM_FIELDS" field=$field}
		{/foreach}
	</div>
</div>
