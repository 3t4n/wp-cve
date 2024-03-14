<div class="lastudio-kit-settings-page lastudio-kit-settings-page__swatches">

    <div class="cx-vui-title cx-vui-title--divider"><?php _e('Swatches Setting', 'lastudio-kit'); ?></div>

    <cx-vui-switcher name="swatches__is_disable"
         label="<?php _e('Disable Swatches', 'lastudio-kit'); ?>"
         description="<?php _e('Disable LA-Studio Swatches', 'lastudio-kit'); ?>"
         :wrapper-css="[ 'equalwidth' ]"
         return-true="yes"
         return-false="no"
         v-model="pageOptions.swatches__is_disable.value">
    </cx-vui-switcher>

    <div
        v-if="'yes' != pageOptions.swatches__is_disable.value"
    >

    <cx-vui-input
        name="swatches_threshold"
        label="<?php _e('Ajax variation threshold', 'lastudio-kit'); ?>"
        description="<?php echo sprintf("Control the number of enable ajax variation threshold, If you set <code>1</code> all product variation will be load via ajax. Default value is <code>30</code>.<br/> <span style='color: red'>Note: Product variation loaded via ajax doesn't follow attribute behaviour. It's recommended to keep this number between 30 - 40.</span> ");?>"
        :wrapper-css="[ 'equalwidth' ]"
        :min="1"
        :max="100"
        :step="1"
        size="fullwidth"
        type="number"
        v-model="pageOptions.swatches_threshold.value"></cx-vui-input>

    <cx-vui-component-wrapper
        label="<?php _e('Swatches item size', 'lastudio-kit'); ?>"
        description="<?php _e('The default size for color swatches and photos.', 'lastudio-kit'); ?>"
        :wrapper-css="[ 'equalwidth' ]"
        size="fullwidth"
    >
        <div class="cx-vui-dimensions size-default swatches--dimensions">
            <div class="cx-vui-dimensions__inputs">
                <cx-vui-input
                    name="swatches_swatches_size_width"
                    size="fullwidth"
                    :wrapper-css="[ 'equalwidth' ]"
                    :prevent-wrap="true"
                    :min="10"
                    :max="100"
                    :step="1"
                    type="number"
                    placeholder="Width"
                    v-model="pageOptions.swatches_swatches_size_width.value"
                >
                </cx-vui-input>
                <div class="custom-label-unit">x</div>
                <cx-vui-input
                    name="swatches_swatches_size_height"
                    size="fullwidth"
                    :wrapper-css="[ 'equalwidth' ]"
                    :prevent-wrap="true"
                    :min="10"
                    :max="100"
                    :step="1"
                    type="number"
                    placeholder="Height"
                    v-model="pageOptions.swatches_swatches_size_height.value"
                >
                </cx-vui-input>
            </div>
        </div>
    </cx-vui-component-wrapper>

    <cx-vui-switcher name="swatches_swatches_variation_form"
         label="<?php _e('Show Variable Form', 'lastudio-kit'); ?>"
         description="<?php _e('Show Variable form instead of displaying individual attributes', 'lastudio-kit'); ?>"
         :wrapper-css="[ 'equalwidth' ]"
         return-true="enabled"
         return-false="disabled"
         v-model="pageOptions.swatches_swatches_variation_form.value">
    </cx-vui-switcher>
    
    <cx-vui-checkbox
        name="swatches_swatches_variation_form"
        label="<?php _e( 'Swatch Attributes Visibility', 'lastudio-kit' ); ?>"
        description="<?php _e('Swatch attributes visibility on items from the product listing page.', 'lastudio-kit'); ?>"
        return-type="array"
        :wrapper-css="[ 'equalwidth' ]"
        :options-list="pageOptions.swatches_swatches_attribute_in_list.options"
        v-model="pageOptions.swatches_swatches_attribute_in_list.value"
        v-if="'enabled' != pageOptions.swatches_swatches_variation_form.value"
    ></cx-vui-checkbox>
    <cx-vui-input
        label="<?php _e( 'Maximum items displayed', 'lastudio-kit' ); ?>"
        description="<?php _e('Maximum items displayed from the product listing page.', 'lastudio-kit'); ?>"
        name="swatches_swatches_max_item"
        size="fullwidth"
        :wrapper-css="[ 'equalwidth' ]"
        :min="1"
        :max="20"
        :step="1"
        type="number"
        placeholder="Max item display"
        v-model="pageOptions.swatches_swatches_max_item.value"
        v-if="'enabled' != pageOptions.swatches_swatches_variation_form.value"
    >
    </cx-vui-input>
    <cx-vui-input
        label="<?php _e( 'More text', 'lastudio-kit' ); ?>"
        name="swatches_swatches_more_text"
        size="fullwidth"
        :wrapper-css="[ 'equalwidth' ]"
        placeholder="More text"
        v-model="pageOptions.swatches_swatches_more_text.value"
        v-if="'enabled' != pageOptions.swatches_swatches_variation_form.value"
    >
    </cx-vui-input>

    </div>
</div>
