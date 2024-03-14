<!-- Form to handle the upload - The enctype value here is very important -->


<div class="Main lpagery-container-with-sidebar" data-title="Welcome to LPagery" style="text-align: center">


    <div class=" license-needed" license="extended">


        <div style="margin-bottom: 15px">
            <nav class="lpagery-create-update-tabs">
                <div class="lpagery-create-update-tabs-selector"></div>
                <a href="#create" class="active" id="lpagery_anchor_create"><i class="fa-solid fa-plus"></i>Create</a>
                <a href="#update" id="lpagery_anchor_update"><i class="fa-solid fa-pen"></i></i>Update</a>
            </nav>
            <img style="width: 40px; top: -100px; margin-bottom: 20px" class="img-pro"
                 src="<?php 
echo  plugin_dir_url( dirname( __FILE__ ) ) . '/../assets/img/pro.svg' ;
?>">
        </div>

    </div>

    <div style="text-align: left;">

        <h2><span id="create_update_title">Create</span> Landing Pages</h2>
        <form method="post" id="lpagery_form" enctype="multipart/form-data">
            <div id="lpagery_template-section">
                <div class="template-path-section">

                    <label for="lpagery_template_path" class="select-label">1. Select Template Page*
                        <div class="tooltip">?
                            <span class="tooltiptext">Need help to set up your Template Page? <a
                                        href="https://lpagery.io/docs/create-a-template-page/">Click here</a></span>
                        </div>
                    </label>


                    <select class="js-example-basic-single page-select" name="template_path" id="lpagery_template_path"
                            style="margin-bottom: 20px" required>
                        <option value="">
                            <?php 
echo  esc_attr( __( 'Select page' ) ) ;
?></option>
                    </select><br>
                    <span id="lpagery_template_error"></span>
                </div>
            </div>
            <div id="lpagery_process-select-section">
                <label for="lpagery_dashboard_process_select" class="select-label">1.1 Select Pages to be Updated
                    <div class="tooltip">?
                        <span class="tooltiptext">Select the page creation history entry which you want to update</span>
                    </div>
                </label>


                <select class="js-example-basic-single page-select" name="lpagery_dashboard_process_select"
                        id="lpagery_dashboard_process_select"
                        style="margin-bottom: 20px" required>

                </select><br>
                <span id="lpagery_process_error"></span>
            </div>
            <div id="lpagery_parent-section" class="license-needed" license="extended">
                <label for="lpagery_parent_path" class="select-label">2. Select Parent Page <span class="optional">(optional)</span>
                    <div class="tooltip">?
                        <span class="tooltiptext">This will add a parent page to the created pages and also will add the URI of the parent page to the URI of the created pages.</span>
                    </div>
                </label>


                <select class="js-example-basic-single page-select" name="parent_path" id="lpagery_parent_path"
                        style="margin-bottom: 20px">
                    <option value="">
                        <?php 
echo  esc_attr( __( 'Select page' ) ) ;
?></option>

                </select><br>
            </div>

            <div license="extended" id="lpagery_slug-section" class="license-needed">
                <label for="lpagery_slug" class="select-label">3. Set URI slug*
                    <div class="tooltip">?
                        <span class="tooltiptext">Slashes will be added according to your permalink settings. For more on this check out our tutorial on slugs: <a
                                    href="https://lpagery.io/docs/edit-the-permalink-structure-of-the-generated-pages/">Click here</a></span>
                    </div>
                </label>

                <input type="text" class="labels" id="lpagery_slug" name="slug" size="25"
                       placeholder="e.g. my-cool-page-in-{city}" required>
                <span id="lpagery_slug_disabled"
                >Permalink structure is set to "plain". Slug will be ignored</span>
                <span id="lpagery_slug_preview"></span>
            </div>


            <div license="extended" id="lpagery_category-section" class="license-needed">
                <label for="lpagery_categories" class="select-label">4. Select Categories <span
                            class="optional">(optional)</span>

                </label>


                <select class="js-example-basic-multiple" name="categories" id="lpagery_categories"
                        style="margin-bottom: 20px" multiple="multiple">
                    <?php 
$categories = get_categories( array(
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
) );
$allowed_html = array(
    'option' => array(
    'value' => array(),
),
);
foreach ( $categories as $category ) {
    $option = '<option value="' . esc_html( $category->term_id ) . '">';
    $option .= esc_html( $category->name );
    $option .= '</option>';
    echo  wp_kses( $option, $allowed_html ) ;
}
?>
                </select><br>

            </div>

            <div license="extended" id="lpagery_tags-section" class="license-needed">
                <label for="lpagery_tags" class="select-label">5. Select Tags <span class="optional">(optional)</span>

                </label>


                <select class="js-example-basic-multiple" name="tags" id="lpagery_tags"
                        style="margin-bottom: 20px" multiple="multiple">
                    <?php 
$tags = get_tags( array(
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
) );
$allowed_html = array(
    'option' => array(
    'value' => array(),
),
);
foreach ( $tags as $tag ) {
    $option = '<option value="' . esc_html( $tag->name ) . '">';
    $option .= esc_html( $tag->name );
    $option .= '</option>';
    echo  wp_kses( $option, $allowed_html ) ;
}
?>
                </select><br>

            </div>

            <div license="extended" id="lpagery_status-section" class="license-needed">
                <label for="lpagery_post_status" class="select-label">6. Select Page Status
                    <div class="tooltip">?
                        <span class="tooltiptext">Specify which status the created pages should have</span>
                    </div>
                </label>


                <select name="post_status" id="lpagery_post_status" class="js-example-basic-single">
                    <option value="publish" selected>
                        Publish
                    </option>
                    <option value="draft">
                        Draft
                    </option>
                    <option value="private">
                        Private
                    </option>
                    <option value="future">
                        Future
                    </option>
                </select><br>
            </div>

            <div license="extended" id="lpagery_publish_date-section" class="license-needed" style="margin-top: 25px">
                <label for="lpagery_publish_date" class="select-label">Set Publish Date
                </label>

                <input type="datetime-local" class="labels" id="lpagery_publish_date" name="publish_date" size="25"
                       required>
                <span id="lpagery_publish_date_error"></span>
            </div>


            <div id="lpagery_update_existing_data">
                <div id="lpagery_update_existing_data_radio" license="standard"
                     class="lpagery_radio-buttons  license-needed">
                    <h3 id="lpagery_update_existing_data_heading">
                        Add new Input Data?
                        <img style="width: 40px; top: -100px;" class="img-pro"
                             src="<?php 
echo  plugin_dir_url( dirname( __FILE__ ) ) . '/../assets/img/pro.svg' ;
?>">

                    </h3>
                    <div class="input-type-wrapper">
                        <div id="lpagery_existing_input_container" class="lpagery_radio_margin">
                            <input type="radio" id="lpagery_existing_input" name="modeExistingInput" checked="checked"
                                   value="existing"/>Use existing
                        </div>
                        <div id="lpagery_new_input_container">
                            <input type="radio" id="lpagery_new_input" name="modeExistingInput" value="new"/>
                            Modify Input
                        </div>

                    </div>
                </div>
            </div>


            <div id="lpagery_modeRadio" style="min-height: 180px">
                <div id="lpagery_input-radio" license="standard" class="lpagery_radio-buttons license-needed">
                    <h3 id="lpagery_input-heading">
                        7. What input do you want to use?
                        <img style="width: 40px; top: -100px;" class="img-pro"
                             src="<?php 
echo  plugin_dir_url( dirname( __FILE__ ) ) . '/../assets/img/pro.svg' ;
?>">

                    </h3>
                    <div class="input-type-wrapper">
                        <div id="lpagery_csv" class="lpagery_radio_margin">
                            <input type="radio" id="lpagery_modeCsv" name="modeRadio" checked="checked"
                                   value="csv"/>CSV / XLSX
                        </div>
                        <div id="lpagery_radius" class="lpagery_radio_margin">
                            <input type="radio" id="lpagery_modeRadius" name="modeRadio" value="location"/>
                            Radius


                        </div>
                        <div id="lpagery_googlesheet" style=" display: inline-block; height: 50px">
                            <input type="radio" id="lpagery_modeGoogleSheet" name="modeRadio" value="googlesheet"/>
                            Google Sheet

                        </div>

                    </div>
                </div>


                <div id="lpagery_modecsv" class="desc">
                    <div class="row">
                        <div class="column-left">

                            <input class="csv_input" type='file' id="lpagery_upload_csv" name='upload_csv'></div>
                        <br>
                        <br>

                        <div id="lpagery_drop_zone" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);">
                            <p>Drag one or more files to this Drop Zone ...</p>
                        </div>
                    </div>
                </div>
                <?php 
?>
                <input type="hidden" id="lpagery_lat" name="lat" required><br>
                <input type="hidden" id="lpagery_lng" name="lng"><br>
                <input type="hidden" id="lpagery_substitution_data" name="substitution_data">
                <input type="hidden" id="lpagery_categories_txt" name="categories_txt">
            </div>

            <div id="lpagery-next-button">
                <button type="button" value="Next" class="lpagery-button" name="next" id="lpagery_next">
                    <span class="button__text" id="lpagery_next_button_text">Next</span>
                </button>
            </div>


            <div style="display: block">


                <span id="lpagery_error_span">An error occurred: <span id="lpagery_error_value"></span></span>
                <span id="lpagery_form_invalid">The provided data is invalid. Please make sure that all mandatory fields are filled</span>
                <span id="lpagery_input_not_unique">The provided data contains duplicated headers.<span
                            id="duplicated_headers_value"></span></span>
                <span id="lpagery_csv_invalid">The provided csv file is invalid: <br> <span
                            id="lpagery_csv_details"></span> <br> Please use a different input type (xlsx, google sheet) or a delimiter which is not occurring inside the replacement data. Valid delimiters are:

                Comma(,) <br>
                Semicolon(;) <br>
                Pipe(|) <br>
                Tab

            </div>
        </form>
    </div>


