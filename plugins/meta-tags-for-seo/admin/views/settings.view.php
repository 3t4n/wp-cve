<div class="wrap container-fluid pmt-container">

    <?php 
include 'inc/top.view.php';
?>

    <div class="row">

        <div id="pmt-app" class="col-xs-8 col-main">

            <form method="post" class="pmt-form">

                <?php 
if ( function_exists( 'wp_nonce_field' ) ) {
    wp_nonce_field( 'pmt__settings', 'pmt__nonce' );
}
?>

                <?php 
echo  $progress_bar ;
?>

                <div id="meta_app">

                <div class="pmt-segment">

                    <h2><?php 
echo  __( 'About Meta Tags', "meta-tags-for-seo" ) ;
?></h2>

                    <p v-if="hideDesc">This plugin allows to deploy illimited &amp; customized META tags (mainly META Keywords) everywhere on your website or on specific pages/posts/custom post types/product pages depending on your content (for SEO purpose). Meta Keywords are a specific type of meta tag that appear in the HTML code of a Web page and help tell search engines what the topic of the page is. <a href="#" @click.prevent="showDesc" style="font-weight: 700;">See more...</a></p>

                    <p v-else>This plugin allows to deploy illimited &amp; customized META tags (mainly META Keywords) everywhere on your website or on specific pages/posts/custom post types/product pages depending on your content (for SEO purpose). Meta Keywords are a specific type of meta tag that appear in the HTML code of a Web page and help tell search engines what the topic of the page is. Meta keywords are distinguished from regular keywords because they appear &ldquo;behind the scenes,&rdquo; in the source code of your page. It is important here to ensure that their amount stays within reason and the keywords correspond to the actual page content, because meta keywords should not give any notion of spam or keyword stuffing to search engines. As a general rule, no more than ten meta keywords should be recorded.<br /><br />Note: this plugin is mainly dedicated to deploy custom META Keywords (for SEO) based on your content. However, you can use the "Custom tags" (PRO version) area to add any other custom tag.<br /><br />1. Click on "Add new META"<br />2. Select the value (Keyword) - (or "Copyright", "Author" with PRO version)<br />3. Enter any word/content as META Keyword (not required IF using "Focus Keyword", "Add post title" or "Add site Title" feature)<br />4. Select where to apply this META tag (everywhere, pages, posts, product pages, custom post types, ...)<br />5. You can choose either to use the Yoast Focus Keyword feature OR the Rank Math Focus keyword feature as META Keyword (please note that if using this feature, each META Keyword will be different depending on the "Focus keyword" used on each page, post, product page, ...)<br />6. Add post title as META Keyword&nbsp; (please note that if using this feature, each META Keyword will be different depending on the "post title" used on each page, post, product page, ...)<br />7. Add title as META Keyword&nbsp;<br /><br />You can now preview how your META Keyword looks once deployed.&nbsp;<br /><br />Once done, you can:<br /><br />- Duplicate this META keyword and select another "Post type" (for example, "posts" instead of "page" if selected previously)<br />- OR click on "Add NEW META" and create a brand new one.<br /><br />Once done. Click on SAVE.<br /><br />Note: if required, when using PRO version, you can disable locally META TAGS for SEO (if you want to control which META tag is deployed on a specific page). <a href="#" @click.prevent="showDesc" style="font-weight: 700;">Hide...</a></p>

                    </div>

                    <?php 
?>
                    <div class="pmt-alert pmt-note" style="padding: 15px 20px; font-size: 16px">
                    <span class="closebtn">&times;</span> 
                    <?php 
echo  $get_pro . " " . __( 'Meta Tags for SEO on Woocommerce Products', "meta-tags-for-seo" ) ;
?>
                    </div>
                    <?php 
?>

                    <div class="pmt-segment" style="background-color: #eee; cursor: pointer" @click.prevent="addMeta()" v-if="!fields.length">
                        <p style="font-size: 18px; font-weight: 700; margin: 0; padding: 0; color: #666;">Click on button below to start adding some meta tags</p>
                    </div>
                    
                    <div v-if="fields">
                        <div class="pmt-segment pmt-repeater" v-for="(field, index) in fields" :key="index">

                            <div class="pmt-top-btns">
                                <span class="pmt-addedon">This meta tag will be deployed {{ (field.post_type == "everywhere") ? '"everywhere"' : 'to "' + field.post_type + 's"' }}</span>
                                <div class="pmt-tooltip pmt-navtip">
                                    <button @click.prevent="duplicateMeta(index)" class="pmt-btn"><span class="dashicons dashicons-admin-page"></span></button>
                                    <span class="pmt-tooltiptext">
                                        <?php 
echo  __( 'Duplicate', "meta-tags-for-seo" ) ;
?>
                                    </span>
                                </div>
                                <div class="pmt-tooltip pmt-navtip">
                                    <button @click.prevent="removeMeta(index)" class="pmt-btn"><span class="dashicons dashicons-no"></span></button>
                                    <span class="pmt-tooltiptext">
                                        <?php 
echo  __( 'Delete', "meta-tags-for-seo" ) ;
?>
                                    </span>
                                </div>
                            </div>
                        
                            <div class="row">
                        
                                <div class="col-xs-12 col-sm-3">
                                <label>Tag type</label>
                                    <select class="pmt-input" :name="'meta_tags['+index+'][type]'" v-model="field.type">
                                        <option value="name">name</option>
                                        <option value="http-equiv" <?php 
echo  'disabled="disabled"' ;
?> >http-equiv <?php 
echo  '(PRO version only)' ;
?></option>
                                    </select>
                                </div>
                        
                                <div class="col-xs-12 col-sm-3">
                                <label>Value</label>
                                    <div class="pmt-tooltip">
                                        <span class="dashicons dashicons-editor-help"></span>
                                        <span class="pmt-tooltiptext">
                                            <?php 
echo  __( 'Meta Keywords are a specific type of meta tag that help tell search engines what the topic of the page is. META Author is used to specify the name of the author of the content (The author tag is now used as a Facebook meta tag). META Copyright records information of who copyright ownership belongs to.', "meta-tags-for-seo" ) ;
?>
                                        </span>
                                    </div>
                                    <select class="pmt-input" :name="'meta_tags['+index+'][value]'" v-model="field.value">
                                        <option value="keywords">keywords</option>
                                        
                                        <option value="author" <?php 
echo  'disabled="disabled"' ;
?> >author <?php 
echo  '(PRO version only)' ;
?></option>
                                        
                                        <option value="copyright" <?php 
echo  'disabled="disabled"' ;
?> >copyright <?php 
echo  '(PRO version only)' ;
?></option>
                                    </select>
                                    
                                </div>
                        
                                <div class="col-xs-12 col-sm-6">
                                    <label>Content</label>
                                    <div class="pmt-tooltip">
                                        <span class="dashicons dashicons-editor-help"></span>
                                        <span class="pmt-tooltiptext">
                                            <?php 
echo  __( 'Add any word or group of words related to the page/post/... or the post type selected - For example, if you are a photographer, a consultant, a web designer, ... make sure to mention it here', "meta-tags-for-seo" ) ;
?>
                                        </span>
                                    </div>
                                    <input class="pmt-input" type="text" :name="'meta_tags['+index+'][content]'" v-model="field.content" :placeholder="'Add ' + field.value">
                                </div>
                                
                            </div>
                        
                            <div class="row">
                            
                                <div class="col-xs-12 col-sm-6">
                        
                                    <label>Choose Post Type</label>
                                    <div class="pmt-tooltip">
                                        <span class="dashicons dashicons-editor-help"></span>
                                        <span class="pmt-tooltiptext">
                                            <?php 
echo  __( 'Select where to deploy these META keywords. (Note: In free version, Woocommerce Products will not work with "Everywhere" option)', "meta-tags-for-seo" ) ;
?>
                                        </span>
                                    </div>
                                    <select class="pmt-input" :name="'meta_tags['+index+'][post_type]'" v-model="field.post_type">
                                    <option value="everywhere">Everywhere <?php 
echo  "(except WooCommerce products)" ;
?></option>   
                                    <?php 
foreach ( $post_types as $post_type ) {
    $labels = get_post_type_labels( $post_type );
    ?>
                                    <option value="<?php 
    echo  esc_attr( $post_type->name ) ;
    ?>"
                                    <?php 
    if ( !pmt__fs()->can_use_premium_code__premium_only() && $post_type->name == 'product' ) {
        echo  'disabled' ;
    }
    ?>
                                    >
                                        <?php 
    echo  esc_html( $labels->name ) . (( !pmt__fs()->can_use_premium_code__premium_only() && $post_type->name == 'product' ? " (PRO version only)" : '' )) ;
    ?>
                                    </option>
                                    <?php 
}
?>
                                    </select>
                        
                                </div>
                        
                                <div class="col-xs-12 col-sm-6">
                                
                                <label>Focus Keyword</label>
                                    <div class="pmt-tooltip">
                                        <span class="dashicons dashicons-editor-help"></span>
                                        <span class="pmt-tooltiptext">
                                            <?php 
echo  __( 'Select (or not) the kind of Focus Keyword to be used with your META Keywords (Yoast or Rank Math). META Tags for SEO will search these Focus Keywords and deploy them strategically as configured.', "meta-tags-for-seo" ) ;
?>
                                        </span>
                                    </div>
                                    <select class="pmt-input" :name="'meta_tags['+index+'][focus_keyword]'" v-model="field.focus_keyword">
                                        <option value="">Choose SEO Plugin</option>   
                                        <option value="yoast_focus_keyword" >
                                            Yoast Focus Keyword
                                        </option>
                                        <option value="rankmath_focus_keyword" >
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
                                            <?php 
echo  __( 'Select (or not) to deploy post titles as META Keywords', "meta-tags-for-seo" ) ;
?>
                                        </span>
                                    </div>
                                        <div>
                                            <label class="pmt-toggle">
                                                <input id="post_title" :name="'meta_tags['+index+'][post_title]'" v-model="field.post_title" type="checkbox" value="post_title" />
                                                <span class='pmt-toggle-slider pmt-toggle-round'></span>
                                            </label>
                                        </div>
                                </div>
                                
                                <div class="col-xs-12 col-sm-2">
                                    <label>Add Site Title</label>
                                    <div class="pmt-tooltip">
                                        <span class="dashicons dashicons-editor-help"></span>
                                        <span class="pmt-tooltiptext">
                                            <?php 
echo  __( 'Select (or not) to deploy your site title as META Keywords', "meta-tags-for-seo" ) ;
?>
                                        </span>
                                    </div>
                                        <div>
                                            <label class="pmt-toggle">
                                                <input id="site_title" :name="'meta_tags['+index+'][site_title]'" v-model="field.site_title" type="checkbox" value="site_title" />
                                                <span class='pmt-toggle-slider pmt-toggle-round'></span>
                                            </label>
                                        </div>
                                </div>

                                <div class="col-xs-12 col-sm-2">
                                    <label>Product SKU</label>
                                    <div class="pmt-tooltip">
                                        <span class="dashicons dashicons-editor-help"></span>
                                        <span class="pmt-tooltiptext">
                                            <?php 
echo  __( 'Select (or not) to deploy product SKU as META Keywords (it will only work on single products when SKU is set)', "meta-tags-for-seo" ) ;
?>
                                        </span>
                                    </div>
                                        <div>
                                            <label class="pmt-toggle" <?php 
echo  ( !pmt__fs()->can_use_premium_code__premium_only() ? '@click="pro_only"' : '' ) ;
?>>
                                            <?php 
?>
                                                <input type="checkbox" disabled />
                                                <span class='pmt-toggle-slider disabled pmt-toggle-round'></span>
                                            <?php 
?>
                                                
                                            </label>
                                        </div>
                                </div>

                                <div class="col-xs-12 col-sm-3">
                                    <label>Product Categories</label>
                                    <div class="pmt-tooltip">
                                        <span class="dashicons dashicons-editor-help"></span>
                                        <span class="pmt-tooltiptext">
                                            <?php 
echo  __( 'Select (or not) to deploy product categories as META Keywords (it will only work on single products when Categories are set)', "meta-tags-for-seo" ) ;
?>
                                        </span>
                                    </div>
                                        <div>
                                            <label class="pmt-toggle" <?php 
echo  ( !pmt__fs()->can_use_premium_code__premium_only() ? '@click="pro_only"' : '' ) ;
?>>
                                            <?php 
?>
                                                <input type="checkbox" disabled    />
                                                <span class='pmt-toggle-slider disabled pmt-toggle-round'></span>
                                            <?php 
?>   
                                            </label>
                                        </div>
                                </div>

                                <div class="col-xs-12 col-sm-3">
                                    <label>Product Tags</label>
                                    <div class="pmt-tooltip">
                                        <span class="dashicons dashicons-editor-help"></span>
                                        <span class="pmt-tooltiptext">
                                            <?php 
echo  __( 'Select (or not) to deploy product tags as META Keywords (it will only work on single products when tags are set)', "meta-tags-for-seo" ) ;
?>
                                        </span>
                                    </div>
                                        <div>
                                            <label class="pmt-toggle" <?php 
echo  ( !pmt__fs()->can_use_premium_code__premium_only() ? '@click="pro_only"' : '' ) ;
?>>
                                            <?php 
?>
                                                <input type="checkbox" disabled    />
                                                <span class='pmt-toggle-slider disabled pmt-toggle-round'></span>
                                            <?php 
?>
                                            </label>
                                        </div>
                                </div>

                            </div>
                        
                            <div class="pmt-tag" v-if="field.type"><strong>Preview your META Tag:</strong> &lt;meta {{ field.type }}="{{ field.value }}" content="{{ field.content }}<span style='color: red'>{{ field.focus_keyword ? ', focus keyword' : '' }}{{ field.post_title ? ', post title' : '' }}{{ field.product_sku ? ', product sku' : ''}}{{ field.product_cats ? ', product categories' : ''}}{{ field.product_tags ? ', product tags' : ''}}</span>{{ field.site_title ? ', <?php 
echo  $site_title ;
?>' : '' }}"&gt;</div>
                            
                        </div>
                    </div>

                    <button @click.prevent="addMeta" class="pmt-btn pmt-meta"><span class="dashicons dashicons-pressthis"></span> Add New Meta</button>

                    <div class="pmt-segment">
                        <label for="pmt_custom_tags_area">Add Custom Tags</label>
                        <div class="pmt-tooltip">
                            <span class="dashicons dashicons-editor-help"></span>
                            <span class="pmt-tooltiptext">
                                <?php 
echo  __( 'If you need to add other tags to your website, such as Facebook/Pinterest/Google/Norton verification tags (to verify your site ownership), please use this section.', "meta-tags-for-seo" ) ;
?>
                            </span>
                        </div>
                        <textarea id="pmt_custom_tags_area" name="pmt_custom_tags_area" class="pmt-textarea" v-model="custom_tags_area"
                        <?php 
echo  "disabled" ;
?>
                        placeholder='<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="google-site-verification" content="54986531549552sadas62sd8as1das9da2sas3" />
<meta name=viewport content="width=device-width, initial-scale=1">'>
                        </textarea>

                        <?php 
?>
                        <div class="pmt-alert pmt-note" style="padding: 15px 20px; font-size: 16px">
                            <span class="closebtn">&times;</span> 
                            <?php 
echo  $get_pro . " " . __( 'Custom Meta Tags text area.', "meta-tags-for-seo" ) ;
?>
                        </div>
                        <?php 
?>
                    </div>
                </div>

                <?php 
?>
                <div class="pmt-alert pmt-note" style="padding: 15px 20px; font-size: 16px; margin-top: 20px;">
                    <span class="closebtn">&times;</span> 
                    <?php 
echo  $get_pro . " " . sprintf( wp_kses( __( 'disable Meta Tags locally and custom Meta tags with a <a href="%s" target="_blank">META BOX feature</a>', "meta-tags-for-seo" ), array(
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
) ), esc_url( PMT_PLUGIN_DIR . '/admin/assets/metabox.png' ) ) ;
?>
                </div>
                <?php 
?>

                <div class="pmt-segment">

                    <div class="row">

                        <div class="col-xs-2">
                            <label class="pmt-label" for="remove_settings">
                                <strong>
                                    <?php 
echo  __( 'Remove Settings', "meta-tags-for-seo" ) ;
?>
                                </strong>
                            </label>
                        </div>

                        <div class="col-xs-2">
                            <label class="pmt-toggle"><input id="remove_settings" type="checkbox" name="remove_settings"
                                    value="remove_settings" <?php 
if ( $options::check( 'remove_settings' ) ) {
    echo  'checked' ;
}
?> />
                                <span class='pmt-toggle-slider pmt-toggle-round'></span></label>
                        </div>

                        <div class="col-xs-8 field">
                            <input type="submit" name="update" class="pmt-submit"
                                value="<?php 
echo  esc_html__( 'Save Changes', "meta-tags-for-seo" ) ;
?>" />
                        </div>

                    </div>

                </div>

                <div class="pmt-segment">

                    <p><?php 
echo  __( "<strong>Note:</strong> Make sure to clear your cache after saving changes.", "meta-tags-for-seo" ) ;
?>
                    </p>

                </div>

            </form>

        </div>

        <div class="col-xs-4 pmt-side">

            <?php 
include 'inc/side.view.php';
?>

        </div>