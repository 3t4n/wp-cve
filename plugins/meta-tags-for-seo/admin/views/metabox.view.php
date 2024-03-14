<div class="misc-pub-section misc-pub-section-last pmt-container"><span id="timestamp">

    <div id="meta_app">

        <div class="row">

            <div class="col-xs-2">
                <label class="pmt-label" for="pmt_disable_tags">
                    <strong>
                        <?php echo __('Disable Meta Tags?', "meta-tags-for-seo"); ?>
                    </strong>
                </label>
            </div>

            <div class="col-xs-2">
                <label class="pmt-toggle"><input id="pmt_disable_tags" type="checkbox" name="pmt_disable_tags"
                        v-model="disable_tags" value="pmt_disable_tags" />
                    <span class='pmt-toggle-slider pmt-toggle-round'></span></label>
            </div>

        </div>

        <div v-if="!disable_tags">
            <div class="row">
            
                <div class="col-xs-2">
                    <label class="pmt-label" for="pmt_custom_tags">
                        <strong>
                            <?php echo __('Use Custom Meta Tags', "meta-tags-for-seo"); ?>
                        </strong>
                    </label>
                </div>
            
                <div class="col-xs-2">
                    <label class="pmt-toggle"><input id="pmt_custom_tags" type="checkbox" name="pmt_custom_tags"
                            v-model="custom_tags" value="pmt_custom_tags" />
                        <span class='pmt-toggle-slider pmt-toggle-round'></span></label>
                </div>
            
            </div>
            
            
            <div v-if="custom_tags">
                <div class="pmt-segment pmt-repeater" v-for="(field, index) in fields" :key="index">
            
                    <div class="pmt-top-btns">
                        <div class="pmt-tooltip pmt-navtip">
                            <button @click.prevent="duplicateMeta(index)" class="pmt-btn"><span
                                    class="dashicons dashicons-admin-page"></span></button>
                            <span class="pmt-tooltiptext">
                                <?php echo  __( 'Duplicate', "meta-tags-for-seo" ); ?>
                            </span>
                        </div>
                        <div class="pmt-tooltip pmt-navtip">
                            <button @click.prevent="removeMeta(index)" class="pmt-btn"><span
                                    class="dashicons dashicons-no"></span></button>
                            <span class="pmt-tooltiptext">
                                <?php echo  __( 'Delete', "meta-tags-for-seo" ); ?>
                            </span>
                        </div>
                    </div>
            
                    <div class="row">
            
                        <div class="col-xs-12 col-sm-2">
                            <label>Tag type</label>
                            <select class="pmt-input" :name="'pmt_meta_tags['+index+'][type]'" v-model="field.type">
                                <option value="name">name</option>
                                <option value="http-equiv">http-equiv</option>
                            </select>
                        </div>
            
                        <div class="col-xs-12 col-sm-2">
                            <label>Value</label>
                            <div class="pmt-tooltip">
                                <span class="dashicons dashicons-editor-help"></span>
                                <span class="pmt-tooltiptext">
                                    <?php echo  __( 'Meta Keywords are a specific type of meta tag that help tell search engines what the topic of the page is. META Author is used to specify the name of the author of the content (The author tag is now used as a Facebook meta tag). META Copyright records information of who copyright ownership belongs to.', "meta-tags-for-seo" ); ?>
                                </span>
                            </div>
                            <select class="pmt-input" :name="'pmt_meta_tags['+index+'][value]'" v-model="field.value">
                                <option value="keywords">keywords</option>
                                <option value="author">author</option>
                                <option value="copyright">copyright</option>
                            </select>
            
                        </div>
            
                        <div class="col-xs-12 col-sm-4">
                            <label>Content</label>
                            <div class="pmt-tooltip">
                                <span class="dashicons dashicons-editor-help"></span>
                                <span class="pmt-tooltiptext">
                                    <?php echo  __( 'Add any word or group of words related to the page/post/... or the post type selected - For example, if you are a photographer, a consultant, a web designer, ... make sure to mention it here', "meta-tags-for-seo" ); ?>
                                </span>
                            </div>
                            <input class="pmt-input" type="text" :name="'pmt_meta_tags['+index+'][content]'"
                            v-model="field.content" :placeholder="'Add ' + field.value">
                        </div>

                        <div class="col-xs-12 col-sm-4">
            
                            <label>Focus Keyword</label>
                            <div class="pmt-tooltip">
                                <span class="dashicons dashicons-editor-help"></span>
                                <span class="pmt-tooltiptext">
                                    <?php echo  __( 'Select (or not) the kind of Focus Keyword to be used with your META Keywords (Yoast or Rank Math). META Tags for SEO will search these Focus Keywords and deploy them strategically as configured.', "meta-tags-for-seo" ); ?>
                                </span>
                            </div>
                            <select class="pmt-input" :name="'pmt_meta_tags['+index+'][focus_keyword]'"
                                v-model="field.focus_keyword">
                                <option value="">Choose SEO Plugin</option>
                                <option value="yoast_focus_keyword">
                                    Yoast Focus Keyword
                                </option>
                                <option value="rankmath_focus_keyword">
                                    RankMath Focus Keyword
                                </option>
                            </select>
                        </div>
            
                    </div>
            
                    <div class="row">
                    <div class="col-xs-12 col-sm-2">
                            <label>Add Post Title</label>
                            <div class="pmt-tooltip">
                                <span class="dashicons dashicons-editor-help"></span>
                                <span class="pmt-tooltiptext">
                                    <?php echo  __( 'Select (or not) to deploy post titles as META Keywords', "meta-tags-for-seo" ); ?>
                                </span>
                            </div>
                            <div>
                                <label class="pmt-toggle">
                                    <input id="post_title" :name="'pmt_meta_tags['+index+'][post_title]'"
                                        v-model="field.post_title" type="checkbox" value="post_title" />
                                    <span class='pmt-toggle-slider pmt-toggle-round'></span>
                                </label>
                            </div>
                        </div>
            
                        <div class="col-xs-12 col-sm-2">
                            <label>Add Site Title</label>
                            <div class="pmt-tooltip">
                                <span class="dashicons dashicons-editor-help"></span>
                                <span class="pmt-tooltiptext">
                                    <?php echo  __( 'Select (or not) to deploy your site title as META Keywords', "meta-tags-for-seo" ); ?>
                                </span>
                            </div>
                            <div>
                                <label class="pmt-toggle">
                                    <input id="site_title" :name="'pmt_meta_tags['+index+'][site_title]'"
                                        v-model="field.site_title" type="checkbox" value="site_title" />
                                    <span class='pmt-toggle-slider pmt-toggle-round'></span>
                                </label>
                            </div>
                        </div>

                        <?php $screen = get_current_screen(); if ( ( $screen->base == 'post' ) && ( $screen->post_type == "product" ) ) { ?>
            
                        <div class="col-xs-12 col-sm-2">
                            <label>Product SKU</label>
                            <div class="pmt-tooltip">
                                <span class="dashicons dashicons-editor-help"></span>
                                <span class="pmt-tooltiptext">
                                    <?php echo  __( 'Select (or not) to deploy product SKU as META Keywords (it will only work on single products when SKU is set)', "meta-tags-for-seo" ); ?>
                                </span>
                            </div>
                                <div>
                                    <label class="pmt-toggle">
                                        <input id="product_sku" :name="'pmt_meta_tags['+index+'][product_sku]'" v-model="field.product_sku" type="checkbox" value="product_sku" />
                                        <span class='pmt-toggle-slider pmt-toggle-round'></span>
                                    </label>
                                </div>
                        </div>

                        <div class="col-xs-12 col-sm-3">
                            <label>Product Categories</label>
                            <div class="pmt-tooltip">
                                <span class="dashicons dashicons-editor-help"></span>
                                <span class="pmt-tooltiptext">
                                    <?php echo  __( 'Select (or not) to deploy product categories as META Keywords (it will only work on single products when Categories are set)', "meta-tags-for-seo" ); ?>
                                </span>
                            </div>
                                <div>
                                    <label class="pmt-toggle">
                                        <input id="product_cats" :name="'pmt_meta_tags['+index+'][product_cats]'" v-model="field.product_cats" type="checkbox" value="product_cats" />
                                        <span class='pmt-toggle-slider pmt-toggle-round'></span>
                                    </label>
                                </div>
                        </div>

                        <div class="col-xs-12 col-sm-3">
                            <label>Product Tags</label>
                            <div class="pmt-tooltip">
                                <span class="dashicons dashicons-editor-help"></span>
                                <span class="pmt-tooltiptext">
                                    <?php echo  __( 'Select (or not) to deploy product tags as META Keywords (it will only work on single products when tags are set)', "meta-tags-for-seo" ); ?>
                                </span>
                            </div>
                                <div>
                                    <label class="pmt-toggle">
                                        <input id="product_tags" :name="'pmt_meta_tags['+index+'][product_tags]'" v-model="field.product_tags" type="checkbox" value="product_tags" />
                                        <span class='pmt-toggle-slider pmt-toggle-round'></span>
                                    </label>
                                </div>
                        </div>
                        <?php } ?>
                        
                    </div>
            
                    <div class="pmt-tag" v-if="field.type"><strong>Preview your META Tag:</strong> &lt;meta {{ field.type }}="{{ field.value }}"
                        content="{{ field.content }}<span style='color: red'>{{ field.focus_keyword ? ', focus keyword' : '' }}{{ field.post_title ? ', <?php echo $title ?>' : '' }}{{ field.product_sku ? ', product sku' : ''}}{{ field.product_cats ? ', product categories' : ''}}{{ field.product_tags ? ', product tags' : ''}}</span>{{ field.site_title ? ', <?php echo $site_title ?>' : '' }}"&gt;
                    </div>
            
                </div>
            
                <button @click.prevent="addMeta" class="pmt-btn pmt-meta" style="width: 100%"><span
                    class="dashicons dashicons-pressthis"></span> Add New Meta</button>
            </div>
        </div>

    </div>

</div>