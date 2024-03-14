<?php
defined( 'ABSPATH' ) || exit;

global $post;
$arrow = $admin_bar_stt ? 'left' : 'right';
?>
<br class="clear"/>
<div>
    <div id="emtmpl-email-editor-container">
        <div id="emtmpl-control-panel">
            <div class="emtmpl-control-panel-fixed">
                <div class="vi-ui three item stackable tabs menu">
                    <a class="active item" data-tab="components"><?php esc_html_e( 'Components', '9mail-wp-email-templates-designer' ); ?></a>
                    <a class="item" data-tab="editor"><?php esc_html_e( 'Editor', '9mail-wp-email-templates-designer' ); ?></a>
                    <a class="item" data-tab="custom_css"><?php esc_html_e( 'Custom CSS', '9mail-wp-email-templates-designer' ); ?></a>
                </div>
                <div class="emtmpl-scroll">
                    <div class="vi-ui bottom attached active tab" data-tab="components">
                        <div id="emtmpl-element-search">
                            <i class="dashicons dashicons-search"></i>
                            <input type="text" class="emtmpl-search" placeholder="<?php esc_html_e( 'Search element', '9mail-wp-email-templates-designer' ); ?>">
                        </div>

                        <div id="emtmpl-components-list">

                        </div>
                    </div>

                    <div class="vi-ui bottom attached tab " data-tab="editor">
                        <div id="emtmpl-attributes-list">

                        </div>
                    </div>

                    <div class="vi-ui bottom attached tab " data-tab="custom_css">
                        <div id="emtmpl-custom-css">
                            <textarea rows="10" name="emtmpl_custom_css" value=""><?php echo esc_html( $custom_css ) ?></textarea>
                        </div>
                    </div>

                </div>

                <div id="emtmpl-main-actions">
                    <div class="emtmpl-main-actions-inner">
                        <div class="emtmpl-actions-front">

                            <a class="emtmpl-exit-to-dashboard " href="<?php echo esc_url( admin_url( 'edit.php?post_type=wp_email_tmpl' ) ) ?>">
                                <i class="dashicons dashicons-arrow-left"
                                   title=" <?php esc_html_e( 'Exit to DashBoard', '9mail-wp-email-templates-designer' ); ?>">
                                </i>
                            </a>

                            <i class="dashicons dashicons-arrow-<?php echo esc_attr( $arrow ) ?> emtmpl-toggle-admin-bar"
                               title="<?php esc_attr_e( 'Toggle admin menu', '9mail-wp-email-templates-designer' ) ?>">
                            </i>

                            <a class="emtmpl-add-new" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=wp_email_tmpl' ) ) ?>">
                                <i class="dashicons dashicons-plus" title="<?php esc_attr_e( 'Add new', '9mail-wp-email-templates-designer' ); ?>">
                                </i>
                            </a>
                            <a class="emtmpl-duplicate-post"
                               href="<?php echo esc_url( admin_url( 'post.php?action=emtmpl_duplicate&id=' ) . get_the_ID() ) ?>">
                                <i class="dashicons dashicons-admin-page"
                                   title=" <?php esc_html_e( 'Copy to draft', '9mail-wp-email-templates-designer' ); ?>">
                                </i>

                            </a>
							<?php
							if ( current_user_can( 'delete_post', $post->ID ) ) {
								echo sprintf( "<a class='emtmpl-trash-post' href='%1s' title='%2s'><i class='dashicons dashicons-trash'> </i></a>",
									esc_attr( get_delete_post_link( $post->ID ) ), esc_attr__( 'Move to trash', '9mail-wp-email-templates-designer' ) );
							}
							?>
                            <div id="emtmpl-publishing-action">
								<?php
								if ( ! in_array( $post->post_status, array( 'publish', 'future', 'private' ) ) || 0 == $post->ID ) {
									?>
                                    <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Publish' ); ?>"/>
                                    <button type="submit" name="publish" id="publish" value="Publish"
                                            class=""><?php esc_attr_e( 'Publish' ); ?></button>
									<?php
								} else {
									?>
                                    <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Update' ); ?>"/>
                                    <button type="submit" name="save" id="publish" value="Update" class=""><?php esc_attr_e( 'Update' ); ?></button>
									<?php
								}
								?>
                                <button type="button" class="emtmpl-show-sub-actions">
                                    <i class="dashicons dashicons-arrow-up"></i>
                                </button>
                            </div>
                        </div>

                        <div class="emtmpl-actions-back">
                            <button type="submit" name="save" id="save-post" class="emtmpl-save-draft"
                                    value="save_draft"><?php esc_html_e( 'Save Draft' ); ?></button>
                        </div>
                        <input type="hidden" name="post_status" value="publish">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="emtmpl-email-editor-wrapper" style="background-size: cover;background-position: center top; background-repeat: no-repeat;">
        <div class="emtmpl-edit-bgcolor-btn">
            <span class="vi-ui button mini">
                <i class="dashicons dashicons-edit"></i>
                <span><?php esc_html_e( 'Background', '9mail-wp-email-templates-designer' ); ?></span>
            </span>
        </div>
        <div id="emtmpl-email-editor-content" class="emtmpl-sortable emtmpl-direction-<?php echo esc_attr( $direction ) ?>">
        </div>
        <div id="emtmpl-quick-add-layout">
            <div class="dashicons dashicons-plus emtmpl-quick-add-layout-btn"
                 title="<?php esc_html_e( 'Select layout', '9mail-wp-email-templates-designer' ); ?>"></div>
            <div class="emtmpl-layout-list"></div>
        </div>
    </div>

    <div id="emtmpl-notice-box">
    </div>
</div>


<div id="emtmpl-templates">

    <script id="emtmpl-input-handle-outer" type="text/html">
        <div class="emtmpl-layout-handle-outer">
            <div class="left">
                <span class="dashicons dashicons-move emtmpl-move-row-btn" title="Move"></span>
                <span class="dashicons dashicons-edit emtmpl-edit-outer-row-btn" title="Edit row outer"></span>
                <span class="dashicons dashicons-admin-page emtmpl-duplicate-row-btn" title="Duplicate"></span>
            </div>
            <div class="right">
                <span class="dashicons dashicons-welcome-add-page emtmpl-copy-row-btn" title="Copy"></span>
                <span class="dashicons dashicons-arrow-down-alt emtmpl-paste-row-btn" title="Paste"></span>
                <span class="dashicons dashicons-no-alt emtmpl-delete-row-btn" title="Delete"></span>
            </div>
        </div>
    </script>

    <script id="emtmpl-input-handle-inner" type="text/html">
        <div class="emtmpl-layout-handle-inner">
            <!--            <span class="dashicons dashicons-edit emtmpl-edit-inner-row-btn" title="Edit row inner"></span>-->
        </div>
    </script>

    <script id="emtmpl-block" type="text/html">
        <div class="emtmpl-block">
            <div class="emtmpl-layout-row" data-type="{%=type%}" data-cols="{%=colsQty%}"
                 style="padding: 15px 35px; background-color: #ffffff; background-repeat: no-repeat; background-position:center top;background-size:cover;border-radius: 0;">
                <div class="emtmpl-flex emtmpl-layout-inner" data-type="{%=type%}">
                    {% for (let i = 0; i < colsQty; i++) {
                    let width = 100 / colsQty + '%'; %}
                    <div class="emtmpl-column emtmpl-column-placeholder" style="width:{%=width%};">
                        <div class="emtmpl-column-sortable">
                        </div>

                        <div class="emtmpl-column-control">
                            <span class="emtmpl-column-paste" title="<?php esc_html_e( 'Paste', '9mail-wp-email-templates-designer' ); ?>">
                                <i class="dashicons dashicons-arrow-down-alt"></i>
                            </span>
                            <span class="emtmpl-column-edit" title="<?php esc_html_e( 'Edit column', '9mail-wp-email-templates-designer' ); ?>">
                                <i class="dashicons dashicons-edit"></i>
                            </span>
                        </div>

                    </div>
                    {% } %}
                </div>
            </div>
        </div>
    </script>

    <script id="emtmpl-input-textinput" type="text/html">
        <div>
            <input name="{%=key%}" type="text" class="form-control" title="{% if(typeof title == 'string'){ %} {%=title%} {% } %}"
                   autocomplete="off"/>
            {% if(typeof shortcodeTool !=='undefined' && shortcodeTool){ %}
            <span class="emtmpl-quick-shortcode"><i class="dashicons dashicons-menu"></i></span>
            <ul class="emtmpl-quick-shortcode-list"></ul>
            {% } %}
        </div>
    </script>

    <script id="emtmpl-input-texteditorinput" type="text/html">
        <div>
            <textarea name="{%=key%}" class="form-control" id="emtmpl-text-editor" rows="7"/>
        </div>
    </script>

    <script id="emtmpl-input-radioinput" type="text/html">
        <div>
            {% for ( var i = 0; i < options.length; i++ ) { %}
            <label class="custom-control custom-radio  {% if (typeof inline !== 'undefined' && inline == true) { %}custom-control-inline{% } %}"
                   title="{%=options[i].title%}">
                <input name="{%=key%}" class="custom-control-input" type="radio" value="{%=options[i].value%}"
                       id="{%=key%}{%=i%}" {%if (options[i].checked) { %}checked="{%=options[i].checked%}" {% } %}>
                <label class="custom-control-label" for="{%=key%}{%=i%}">{%=options[i].text%}</label>
            </label>
            {% } %}
        </div>
    </script>

    <script id="emtmpl-input-radiobuttoninput" type="text/html">
        <div class="btn-group btn-group-toggle  {%if (extraClass) { %}{%=extraClass%}{% } %} clearfix" data-toggle="buttons">
            {% for ( var i = 0; i < options.length; i++ ) { %}
            <label class="{%if (options[i].checked) { %}active{% } %}  {%if (options[i].extraClass) { %}{%=options[i].extraClass%}{% } %}"
                   for="{%=key%}{%=i%} " title="{%=options[i].title%}">
                <input name="{%=key%}{%if (extraClass) { %}{%='-'+ extraClass%}{% } %}"
                       class="custom-control-input" type="radio" value="{%=options[i].value%}"
                       {%if (options[i].checked) { %}checked="{%=options[i].checked%}" {% } %}>
                {%if (options[i].icon) { %}<i class="{%=options[i].icon%}"></i>{% } %}
                {%=options[i].text%}
            </label>
            {% } %}
        </div>
    </script>

    <script id="emtmpl-input-header" type="text/html">
        <h6 class="header">{%=header%}</h6>
    </script>


    <script id="emtmpl-input-select" type="text/html">
        <div>
            <select class="form-control {% if(typeof classes !='undefined'){ %} {%=classes%} {% } %}">
                {% if(typeof options !=='undefined'){ %}
                {% for ( var i = 0; i < options.length; i++ ) { %}
                <option value="{%=options[i].id%}">{%=options[i].text%}</option>
                {% }} %}
            </select>
        </div>
    </script>

    <script id="emtmpl-input-select-group" type="text/html">
        <div>
            <select class="form-control {% if(typeof classes !='undefined'){ %} {%=classes%} {% } %}">
                {% for(var i in options){ %}
                {% if (Array.isArray(options[i])){ %}
                <optgroup label="{%=i.charAt(0).toUpperCase() + i.slice(1)%}">
                    {% for(let j of options[i]){ %}
                    <option value="{%=j.id%}">{%=j.text%}</option>
                    {% } %}
                </optgroup>
                {% }else{ %}
                <option value="{%=options[i].id%}">{%=options[i].text%}</option>
                {% }} %}
            </select>
        </div>
    </script>

    <script id="emtmpl-input-select2" type="text/html">
        {% let multipleCheck = typeof multiple !=='undefined' && multiple === true ? 'multiple' : ''; %}
        <div>
            <select {%=multipleCheck%} class="form-control {% if(typeof classes !='undefined'){ %} {%=classes%} {% } %}">
                {% if(typeof options !=='undefined'){ %}
                {% for ( var i = 0; i < options.length; i++ ) { %}
                <option value="{%=options[i].id%}">{%=options[i].text%}</option>
                {% }} %}
            </select>
        </div>
    </script>


    <script id="emtmpl-input-imageinput" type="text/html">
        <div>
            <input name="{%=key%}" type="text" class="form-control"/>
            <input name="file" type="file" class="form-control"/>
        </div>
    </script>

    <script id="emtmpl-input-colorinput" type="text/html">
        <div>
            <input name="{%=key%}" type="text" autocomplete="off" {% if (typeof value !== 'undefined' && value != false) { %}
            value="{%=value%}" {% } %} pattern="#[a-f0-9]{6}" class="form-control emtmpl-color-picker"/>
            <span class="emtmpl-clear dashicons dashicons-no-alt" title="<?php esc_html_e( 'Clear', '9mail-wp-email-templates-designer' ); ?>"></span>
        </div>
    </script>

    <script id="emtmpl-input-numberinput" type="text/html">
        <div>
            <input name="{%=key%}" type="number"
                   {% if (typeof value !== 'undefined' && value != false) { %} value="{%=value%}" {% } %}
            {% if (typeof min !== 'undefined' && min != false) { %}min="{%=min%}"{% }else{ %} min="0"{%} %}
            {% if (typeof max !== 'undefined' && max != false) { %}max="{%=max%}"{% } %}
            {% if (typeof step !== 'undefined' && step != false) { %}step="{%=step%}"{% } %}
            class="form-control"/>
        </div>
    </script>

    <script id="emtmpl-input-dateinput" type="text/html">
        <div>
            <input name="{%=key%}" type="date" {% if (typeof value !== 'undefined' && value != false) { %}
            value="{%=value%}" {% } %}
            {% if (typeof min !== 'undefined' && min != false) { %}min="{%=min%}"{% }else{ %} min="0"{%} %}
            {% if (typeof max !== 'undefined' && max != false) { %}max="{%=max%}"{% } %}
            {% if (typeof step !== 'undefined' && step != false) { %}step="{%=step%}"{% } %}
            class="form-control"/>
        </div>
    </script>

    <script id="emtmpl-input-checkboxinput" type="text/html">
        <div>
            <input name="{%=key%}" type="checkbox" value="1" class="form-control"/>
        </div>
    </script>

    <script id="emtmpl-input-bgimginput" type="text/html">
        <div>
            <button type="button" name="{%=key%}" class="{%=classes%} vi-ui button mini emtmpl-ctrl-btn">
                {%=text%}
            </button>
            <span class="emtmpl-clear dashicons dashicons-no-alt" title="<?php esc_html_e( 'Clear', '9mail-wp-email-templates-designer' ); ?>"></span>
        </div>
    </script>

    <script id="emtmpl-input-button" type="text/html">
        <div>
            <button class="vi-ui button mini emtmpl-ctrl-btn {% if(typeof classes !== 'undefined'){ %} {%=classes%} {% } %}" type="button">
                <i class="la  {% if (typeof icon !== 'undefined') { %} {%=icon%} {% } else { %} la-plus {% } %} la-lg"></i>
                {%=text%}
            </button>
        </div>
    </script>

    <script id="emtmpl-input-sectioninput" type="text/html">
        <div class="section">
            <div class="title active">
                <i class="dropdown icon"></i>
                {%=header%}
            </div>
            <div class="content active {%=key%}">

            </div>
        </div>
    </script>

    <script id="emtmpl-property" type="text/html">
        {% let formatCol = typeof col !== 'undefined' && col != false ? 'emtmpl-col-' + col + ' emtmpl-inline-block' : ''; %}
        {% let className = typeof classes !== 'undefined' ? classes : ''; %}
        {% if (typeof groupName !== 'undefined' && groupName != false) { %}
        <label class="emtmpl-group-name" for="input-model">{%=groupName%}</label>
        {% } %}
        <div class="{%=formatCol%} {%=className%}"
             data-key="{%=key%}" {% if (typeof group !=='undefined' && group !=null) { %}data-group="{%=group%}" {% } %}>
            <div class="emtmpl-form-group">
                {% if (typeof name !== 'undefined' && name != false) { %}
                <label class="emtmpl-control-label" for="input-model">{%=name%}</label>
                {% } %}
                <div class="input">
                </div>
            </div>
        </div>
    </script>

    <script id="emtmpl-recover-email-content" type="text/html">
        <p><?php esc_html_e( "Email content is displayed here. You can modify the attributes in paragraph section.", '9mail-wp-email-templates-designer' ); ?></p>
        <br/>
        <p><?php esc_html_e( "If ignore this template for an email, let's add this shortcode {{ignore_9mail}} to that email content.", '9mail-wp-email-templates-designer' ); ?></p>
        <br/>
        <p><?php esc_html_e( "You can modify some attributes for the links in email in the Link section:", '9mail-wp-email-templates-designer' ); ?></p>
        <a href="#" style="color: #278de7;line-height: 22px;"><?php echo esc_url( site_url() ); ?></a>
    </script>

    <script id="emtmpl-recover-email-heading" type="text/html">
        <div>
			<?php esc_html_e( 'Thank you for your order', '9mail-wp-email-templates-designer' ); ?>
            <span class="emtmpl-note"><?php esc_html_e( 'The heading of original email will be transferred here', '9mail-wp-email-templates-designer' ); ?></span>
        </div>
    </script>


</div>
