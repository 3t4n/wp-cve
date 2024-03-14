<div>
    <cx-vui-list-table
        :is-empty="! attributeList.length"
        empty-message="<?php _e( 'No attributes found', 'lastudio-kit' ); ?>"
    >
        <cx-vui-list-table-heading
            :slots="[ 'attribute', 'control_type' ]"
            class-name="cols-2"
            slot="heading"
        >
            <span slot="attribute"><?php _e( 'Attribute', 'lastudio-kit' ); ?></span>
            <span slot="control_type"><?php _e( 'Control Type', 'lastudio-kit' ); ?></span>
        </cx-vui-list-table-heading>
        <cx-vui-list-table-item
            :slots="[ 'attribute' ]"
            class-name="cols-1"
            slot="items"
            v-for="(attribute, index) in attributeList"
            :key="attribute._id"
        >
            <div slot="attribute">
                <cx-vui-collapse
                    :collapsed="true"
                >
                    <div class="cx-vui-subtitle" slot="title">
                        <div class="attr-name">{{ attribute.name }}</div>
                        <div class="attr-name">{{ getTypeLabel(attribute.type) }}</div>
                    </div>
                    <div class="cx-vui-panel" slot="content">
                        <cx-vui-select
                            :name="'lakit_swatch_data[' + attribute._id + '][type]'"
                            :label="'<?php _e( 'Type', 'lastudio-kit' ); ?>'"
                            :wrapper-css="[ 'fixedwidth' ]"
                            :size="'fullwidth'"
                            :options-list="getTypeOptions('type', attribute.is_custom)"
                            v-model="attribute.type"
                        >
                        </cx-vui-select>
                        <cx-vui-select
                            :name="'lakit_swatch_data[' + attribute._id + '][swatch_size]'"
                            :label="'<?php _e( 'Swatches and Photos Size', 'lastudio-kit' ); ?>'"
                            :wrapper-css="[ 'fixedwidth' ]"
                            :size="'fullwidth'"
                            :options-list="getTypeOptions('swatch_size', false)"
                            v-model="attribute.swatch_size"
                            v-if="attribute.type === 'term_options' || attribute.type === 'product_custom'"
                        >
                        </cx-vui-select>
                        <cx-vui-select
                            :name="'lakit_swatch_data[' + attribute._id + '][layout]'"
                            :label="'<?php _e( 'Layout', 'lastudio-kit' ); ?>'"
                            :wrapper-css="[ 'fixedwidth' ]"
                            :size="'fullwidth'"
                            :options-list="getTypeOptions('layout', false)"
                            v-model="attribute.layout"
                            v-if="attribute.type === 'term_options' || attribute.type === 'product_custom'"
                        >
                        </cx-vui-select>
                        <cx-vui-select
                            :name="'lakit_swatch_data[' + attribute._id + '][style]'"
                            :label="'<?php _e( 'Style', 'lastudio-kit' ); ?>'"
                            :wrapper-css="[ 'fixedwidth' ]"
                            :size="'fullwidth'"
                            :options-list="getTypeOptions('style', false)"
                            v-model="attribute.style"
                            v-if="attribute.type === 'term_options' || attribute.type === 'product_custom'"
                        >
                        </cx-vui-select>
                        <div
                            class="cx-vui-component cx-vui-component--fixedwidth"
                            v-if="attribute.type === 'product_custom'"
                        >
                            <div class="cx-vui-component__meta">
                                <label class="cx-vui-component__label"><?php _e('Attribute Configuration', 'lastudio-kit'); ?></label>
                            </div>
                            <div class="cx-vui-component__control flex-grow-1">
                                <div class="cx-vue-list-table">
                                    <div class="cx-vue-list-table__heading">
                                        <div class="list-table-heading cols-3">
                                            <div class="list-table-heading__cell cell--preview"><span><?php _e('Preview', 'lasutdio-kit'); ?></span></div>
                                            <div class="list-table-heading__cell cell--attribute"><span><?php _e('Name', 'lastudio-kit'); ?></span></div>
                                            <div class="list-table-heading__cell cell--type"><span><?php _e('Swatch Type', 'lastudio-kit'); ?></span></div>
                                        </div>
                                    </div>
                                    <div
                                        class="list-table-item cols-1"
                                        v-for="(term, _idx) in attribute.attributes"
                                        :key="term._id"
                                    >
                                        <div class="list-table-item__cell">
                                            <cx-vui-collapse
                                                :collapsed="true"
                                            >
                                                <div class="cx-vui-subtitle" slot="title">
                                                    <div class="attr-name" v-html="renderPreview(term)"></div>
                                                    <div class="attr-name">{{ term.name }}</div>
                                                    <div class="attr-name text-capitalize">{{ term.type }}</div>
                                                </div>
                                                <div class="cx-vui-panel" slot="content">
                                                    <cx-vui-select
                                                        :name="'lakit_swatch_data[' + attribute._id + '][attributes]['+term._id+'][type]'"
                                                        :label="'<?php _e( 'Swatch Type', 'lastudio-kit' ); ?>'"
                                                        :wrapper-css="[ 'fixedwidth' ]"
                                                        :size="'fullwidth'"
                                                        :options-list="getTypeOptions('subtype', false)"
                                                        v-model="term.type"
                                                    >
                                                    </cx-vui-select>
                                                    <cx-vui-colorpicker
                                                        :name="'lakit_swatch_data[' + attribute._id + '][attributes]['+term._id+'][color]'"
                                                        :label="'<?php _e( 'Color', 'lastudio-kit' ); ?>'"
                                                        :wrapper-css="[ 'fixedwidth' ]"
                                                        :size="'fullwidth'"
                                                        v-if="term.type === 'color'"
                                                        v-model="term.color"
                                                        @on-change="updatePreview( $event, 'color', term )"
                                                    >
                                                    </cx-vui-colorpicker>
                                                    <cx-vui-colorpicker
                                                        :name="'lakit_swatch_data[' + attribute._id + '][attributes]['+term._id+'][color2]'"
                                                        :label="'<?php _e( 'Color 2', 'lastudio-kit' ); ?>'"
                                                        :wrapper-css="[ 'fixedwidth' ]"
                                                        :size="'fullwidth'"
                                                        v-if="term.type === 'color'"
                                                        v-model="term.color2"
                                                        @on-change="updatePreview( $event, 'color2', term )"
                                                    >
                                                    </cx-vui-colorpicker>
                                                    <cx-vui-wp-media
                                                        :name="'lakit_swatch_data[' + attribute._id + '][attributes]['+term._id+'][photo]'"
                                                        :label="'<?php _e( 'Photo', 'lastudio-kit' ); ?>'"
                                                        :wrapper-css="[ 'fixedwidth' ]"
                                                        :size="'fullwidth'"
                                                        :multiple="false"
                                                        v-model="term.photo"
                                                        v-if="term.type === 'photo'"
                                                        @on-change="updatePreview( $event, 'photo', term )"
                                                    ></cx-vui-wp-media>
                                                </div>
                                            </cx-vui-collapse>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </cx-vui-collapse>
            </div>
        </cx-vui-list-table-item>
    </cx-vui-list-table>
</div>