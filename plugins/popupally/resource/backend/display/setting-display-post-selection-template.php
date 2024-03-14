<input readonly type="text" class="selected-num-status" update-num-trigger=".{{selection-type}}-post-{{id}}"><label> items selected</label>
<div class="{{selection-type}}-selection page-selection-scroll">
	<div class="selection-tree-container">
		<input type="checkbox" value="closed" class="checkbox-parent-expand" id="expand-{{selection-type}}-{{id}}-all-posts" />
		<label for="expand-{{selection-type}}-{{id}}-all-posts"></label>
		<input type="checkbox" c--all-posts--d class="{{selection-type}}-page-checkbox {{selection-type}}-post-{{id}}" id="{{selection-type}}-all-post-{{id}}" value="true" name="[{{id}}][{{selection-type}}][all-posts]">
		<label for="{{selection-type}}-all-post-{{id}}">All posts</label>
		<ul>
			{{post-selection}}
		</ul>
	</div>
</div>