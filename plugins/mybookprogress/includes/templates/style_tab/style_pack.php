<div class="mbp-stylepack" data-stylepack-id="{{- this.model.id }}" data-stylepack-desc="{{- this.model.get('desc') }}">
	<div class="mbp-stylepack-preview">{{= this.model.get('preview') ? '<img src="'+this.model.get('preview')+'">' : '' }}</div>
	<span class="mbp-stylepack-activate"><?php _e('Activate', 'mybookprogress'); ?></span>
	<span class="mbp-stylepack-active"><?php _e('Active', 'mybookprogress'); ?></span>
	<div class="mbp-stylepack-details">
		<div class="mbp-stylepack-name">{{- this.model.get('name') }}</div>
		<div class="mbp-stylepack-author">{{= this.model.get('author_uri') ? '<a href="'+this.model.get('author_uri')+'" target="_blank">' : '' }}{{- this.model.get('author') }}{{= this.model.get('author_uri') ? '</a>' : '' }}</div>
	</div>
</div>