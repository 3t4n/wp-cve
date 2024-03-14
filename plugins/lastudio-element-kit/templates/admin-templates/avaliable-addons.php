<div
	class="lastudio-kit-settings-page lastudio-kit-settings-page__avaliable-addons"
>
	<div class="lastudio-kit-settings-page__avaliable-controls">
		<div class="cx-vui-title cx-vui-title--divider"><?php _e( 'Available Widgets', 'lastudio-kit' ); ?></div>
        <div class="cx-vui-panel cx-vui-panel--flex">
		<div
			class="lastudio-kit-settings-page__avaliable-control"
			v-for="(option, index) in pageOptions.avaliable_widgets.options"
		>
			<cx-vui-switcher
				:key="index"
				:name="`avaliable-widget-${option.value}`"
				:label="option.label"
				:wrapper-css="[ 'equalwidth' ]"
				return-true="true"
				return-false="false"
				v-model="pageOptions.avaliable_widgets.value[option.value]"
			>
			</cx-vui-switcher>

		</div>
        </div>
	</div>

	<div class="lastudio-kit-settings-page__avaliable-controls">
		<div class="cx-vui-title cx-vui-title--divider"><?php _e( 'Available Extensions', 'lastudio-kit' ); ?></div>
        <div class="cx-vui-panel cx-vui-panel--flex">
		<div
			class="lastudio-kit-settings-page__avaliable-control"
			v-for="(option, index) in pageOptions.avaliable_extensions.options">
			<cx-vui-switcher
				:key="index"
				:name="`avaliable-extension-${option.value}`"
				:label="option.label"
				:wrapper-css="[ 'equalwidth' ]"
                :value="pageOptions.avaliable_extensions.value[option.value]"
				return-true="true"
				return-false="false"
                @input="updateSetting( $event, option.value, 'avaliable_extensions', pageOptions.avaliable_extensions.value )"
			>
			</cx-vui-switcher>
		</div>
		</div>
	</div>
</div>

