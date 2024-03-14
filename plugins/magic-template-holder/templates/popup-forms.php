<div id="mth-popup-background" class="mth-popup-background"></div>
<div id="mth-popup-wrapper" class="mth-popup-wrapper"></div>

<script id="mth-insert-popup-template" type="text/template">
	<div id="mth-insert-popup-box-wrapper" class="mth-popup-box">

		<h3 id="title-for-insert-popup-box" class="title-for-popup-box"><?php _e( 'Select a Template', 'magic-template-holder' ); ?></h3>

		<h4><?php _e( 'Template Filter ( Group )', 'magic-template-holder' ); ?></h4>
		<p><small><?php _e( 'Displays also Drafts with "Draft" but number doesn\'t include drafts.', 'magic-template-holder' ); ?></small></p>

		<div id="insert-group-checks" class="group-checks-wrapper" style="margin-bottom:20px;">
			<% for( var key in templateGroups ) { %>
				<p>
					<label for="mth-template-group[<%- templateGroups[ key ].term_id %>]">
						<%- mthLocalizedData.mthGroupCount.replace( '%1$s', templateGroups[ key ].name ).replace( '%2$d', templateGroups[ key ].count ) %>
					</label>
					<input type="checkbox"
						id="mth-template-group-<%- templateGroups[ key ].term_id %>"
						class="mth-template-group-checkbox"
						name="mth-template-group[<%- templateGroups[ key ].term_id %>]"
						value="<%- templateGroups[ key ].term_id %>" 
					/>
				</p>
			<% } %>
		</div>

		<h4><?php _e( 'Select a Template', 'magic-template-holder' ); ?></h4>
		<p><small><strong><?php _e( ' * Please press "Insert" after the text of the selected template is displayed.', 'magic-template-holder' ); ?></strong></small></p>

		<div id="mth-insert-list" class="mth-templates-list-wrapper">
			<select id="mth-templates-list" name="mth-templates-list">
				<option value="none"><?php _e( 'Select a Template', 'magic-template-holder' ); ?></option>
					<% for( var key in templateObjects ) { %>

						<option 
							id="<%- templateObjects[ key ].ID %>"
							class="mth-template-group<%- ( templateObjects[ key ].group_classes !== '' ? ' ' + templateObjects[ key ].group_classes : '' ) %>"
							value="<%- templateObjects[ key ].post_content %>"
						>
							<%- templateObjects[ key ].post_title + ( templateObjects[ key ].post_status == 'draft' ? ' ' + mthLocalizedData.draftSuffix : '' ) %>
						</option>

					<% } %>
			</select><span id="mth-template-list-popup-notification" class="mth-template-list-popup-notification"></span>

		</div>
		
		<div id="mth-insert-content-display-wrapper" class="mth-content-display">
			<textarea id="mth-insert-content-display"></textarea>
		</div>
		
	</div>
</script>

<script id="mth-make-popup-template" type="text/template">
	<div id="mth-make-popup-box-wrapper" class="mth-popup-box">

		<h3 id="title-for-make-popup-box" class="title-for-popup-box"><?php _e( 'Make a new Template', 'magic-template-holder' ); ?></h3>

		<div class="mth-make-template-title-wrapper">
			<label for="mth-make-template-title"><?php _e( 'Title ( Required ) : ', 'magic-template-holder' ); ?></label><br/>
			<input id="mth-make-template-title" name="mth-make-template-title" type="text" placeholder="<?php _e( 'Title for this Template', 'magic-template-holder' ); ?>" style="width:100%;"/>
		</div>
		
		<div class="mth-make-template-group-wrapper">
			<label for="mth-make-template-group"><?php _e( 'Group ( Optional ) : ', 'magic-template-holder' ); ?></label><br/>
			<input id="mth-make-template-group" name="mth-make-template-group" type="text" placeholder="<?php _e( 'Set plural separating by commas', 'magic-template-holder' ); ?>" style="width:100%;"/>
		</div>

		<div id="mth-make-tepmlate-display-wrapper" class="mth-tepmlate-display">
			<label for="mth-make-tepmlate-display"><?php _e( 'Text of Template to save ( You can edit now )', 'magic-template-holder' ); ?></label><br>
			<textarea id="mth-make-tepmlate-display"><%- mthCapturedText %></textarea>
		</div>

		<span id="mth-popup-notification" class="mth-popup-notification"></span>

	</div>
</script>


<script id="mth-insert-popup-template-buttons" type="text/template">
	<p>
		<a id="mth-insert-button" class="button button-primary" href="javascript:void(0);"><?php _e( 'Insert a Template', 'magic-template-holder' ); ?></a>
		<a id="mth-insert-cancel-button" class="button" href="javascript:void(0);"><?php _e( 'Cancel', 'magic-template-holder' ); ?></a>
	</p>
</script>

<script id="mth-make-popup-template-bottons" type="text/template">
	<p>
		<a id="mth-make-button" class="button button-primary" href="javascript:void(0);"><?php _e( 'Make a new Template', 'magic-template-holder' ); ?></a>
		<a id="mth-make-cancel-button" class="button" href="javascript:void(0);"><?php _e( 'Cancel', 'magic-template-holder' ); ?></a>
	</p>
</script>