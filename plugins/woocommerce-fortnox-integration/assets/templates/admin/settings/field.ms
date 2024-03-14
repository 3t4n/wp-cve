{{# text }}
	<input type="text" name="{{ name }}" value="{{ selected }}" class="{{ class }}" autocomplete="off" placeholder="{{ placeholder }}">
{{/ text }}

{{# password }}
	<input type="password" name="{{ name }}" value="{{ selected }}" class="{{ class }}" autocomplete="off" placeholder="{{ placeholder }}">
{{/ password }}

{{# dropdown }}
	<select name="{{ name }}" class="{{ class }}">
		{{# options }}
			<option value="{{ value }}" {{# selected }}selected="selected"{{/ selected }}>{{ label }}</option>
		{{/ options }}
	</select>
{{/ dropdown }}

{{# radio }}
	{{# options }}
		<p>
			<label>
				<input type="radio" name="{{ name }}" value="{{ value }}" {{# selected }}checked="checked"{{/ selected }} class="{{ class }}"> {{{ label }}}
			</label>
		</p>
	{{/ options }}
{{/ radio }}

{{# checkbox }}
	<p>
		<label>
			<input type="checkbox" name="{{ name }}" value="{{ value }}" {{# selected }}checked="checked"{{/ selected }} class="{{ class }}"> {{{ label }}}
		</label>
	</p>
{{/ checkbox }}

{{# checkboxes }}
	<fieldset>
		<legend class="screen-reader-text">
			<span>{{ title }}</span>
		</legend>
		
		{{# options }}
			<label>
				<input type="checkbox" name="{{ name }}[]" value="{{ value }}" {{# selected }}checked="checked"{{/ selected }} class="{{ class }}"> {{{ label }}}
			</label>
			<p class="description">{{ description }}</p>
		{{/ options }}
	</fieldset>
{{/ checkboxes }}

{{# table }}
	<table class="wp-list-table widefat fixed striped posts {{ class }}">
		<thead>
			<tr>
				{{# list.columns }}
				<th class="column-{{ name }}">{{{ title }}}</th>
				{{/ list.columns }}
			</tr>
		</thead>
		<tbody {{# id }}id="{{ . }}"{{/ id }}>
			{{# list.rows }}
				{{> admin/settings/field/list-row }}
			{{/ list.rows }}
		</tbody>
	</table>
	
	{{# list.addRowButton }}
	<p><a href="#" class="button {{ list.addRowButtonClass }}">Add row</a></p>
	{{/ list.addRowButton }}
{{/ table }}

{{# description }}
	<p class="description">{{{ . }}}</p>
{{/ description }}