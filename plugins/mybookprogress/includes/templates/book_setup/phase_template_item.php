<input type="text" class="mbp-template-name" value="{{- this.model.get('name') }}" {{- this.model.get('default') ? ' readonly="true"' : '' }}>
{{ if(!this.model.get('default')) { }}
<div class="mbp-modal-button mbp-small-button mbp-template-delete"><?php _e('Delete', 'mybookprogress'); ?></div>
{{ } }}