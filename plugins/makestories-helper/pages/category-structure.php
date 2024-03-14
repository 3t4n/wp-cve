<?php
function ms_option_page() {

    if(isset($_POST['post_slug'])){
        if(check_ajax_referer(MS_NONCE_REFERRER, false, false)){
            ms_set_options();
        }else{ //Token not verified so will not save the details to DB ?>
            <div class="notice notice-error is-dismissible" style="margin-top: 20px;margin-left: 0;">
                <p>Page has expired! Please try refreshing the page</p>
            </div>
        <?php }
    }
    
    // Check if the form is submitted 
    if ( isset( $_POST['category'] ) ) {
        $category = sanitize_text_field($_POST['category']);
        
        wp_insert_term(
            $category,   
            MS_TAXONOMY
        );
    }
    
    $options = ms_get_options();

    global $wp_roles;
    if ( ! isset( $wp_roles ) ) $wp_roles = new WP_Roles();

    $roleNames = $wp_roles->get_names();
    ?>
    <!-- Catgeory-wrap start -->
    <div class="category-wrap">
        <h2>MakeStories Settings</h2>
        <form method="POST" action="" class="category-allow-form">
            <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( MS_NONCE_REFERRER ) ?>">
        <table class="form-table" role="presentation">

            <tbody>
            <tr>
                <th scope="row"><label for="slug">Slug</label></th>
                <td>
                    <input id="slug" type="text" name="post_slug" value="<?php if($options['post_slug']){ echo esc_html($options['post_slug']); } ?>" class="regular-text">
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="categories">Categories</label></th>
                <td>
                    <input id="categories" type="checkbox" <?php if($options['categories_enabled']){ echo "checked"; } ?> name="categories_enabled" value="true" class="category">
                    Add category to stories
                    <p class="description" id="tagline-description">Keeping this on will allow you to assign categories to stories and also add that as a part of slug. <a href="<?php echo admin_url("edit-tags.php?taxonomy=ms_story_category"); ?>">Manage Categories</a></p>
                </td>
            </tr>
            <tr class="category-form-wrap trb <?php echo ms_is_categories_enabled() ? "block" : ""; ?>">
                <th scope="row"><label for="categories">Default Category</label></th>
                <td>
                    <select class="form-control" name="default_category" id="default_category">
                        <option value="Uncategorized">Uncategorized</option>
                        <?php
                        $selected = ms_get_default_category();
                        $categories = get_terms([
                            'taxonomy' => MS_TAXONOMY,
                            'hide_empty' => false,
                            'posts_per_page' => -1,
                        ]);
                        foreach($categories as $category) { ?>
                            <option <?php if($selected === $category->name){ echo "selected"; } ?> value="<?php echo esc_attr($category->name); ?>"><?php echo esc_html($category->name); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr/>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="categories">Roles allowed access to MakeStories</label></th>
                <td>
                    <fieldset>
                        <?php
                        foreach($roleNames as $role => $roleName) { ?>
                            <label for="role_input_<?php echo esc_html($role); ?>" <?php if($role === "administrator"){ echo "disabled"; } ?>>
                                <input name="roles[]" type="checkbox" <?php if($role === "administrator"){ echo "disabled"; } ?> id="role_input_<?php echo esc_html($role); ?>" value="<?php echo esc_html($role); ?>"  <?php if(in_array($role, $options['roles'])){ echo "checked"; } ?>>
                                <?php echo esc_html($roleName); ?></label>
                            <br>
                            <?php
                        }
                        ?>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th scope="row"><label></label></th>
                <td>
                    <p class="submit">
                        <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"/>
                    </p>
                </td>
            </tr>
        </table>
        </form>
<!--        <table class="form-table" role="presentation">-->
<!---->
<!--            <tbody>-->
<!--            <tr>-->
<!--                <td colspan="2">-->
<!--                    <hr/>-->
<!--                </td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <th scope="row"><label>Update Story Media</label></th>-->
<!--                <td>-->
<!--                    <p class="description">If you think that your story media is not coming from your own domain, click this button. If all the things are already set, this will not affect you.</p>-->
<!--                    <p id="summary"></p>-->
<!--                    <p class="submit">-->
<!--                        <input type="submit" id="run-media-updates" name="submit" class="button button-primary" value="Run Updates"/>-->
<!--                    </p>-->
<!--                </td>-->
<!--            </tr>-->
<!--        </table>-->
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <td colspan="2">
                    <hr/>
                </td>
            </tr>
            <tr>
                <th scope="row"><label>Republish all stories</label></th>
                <td>
                    <p class="description">This button re-publishes all the stories on your wordpress site. Helpful for bulk changes to thinks like analytics and Monetization.</p>
                    <p id="summary-republish"></p>
                    <p class="submit">
                        <input type="submit" id="run-story-updates" name="submit" class="button button-primary" value="Republish All"/>
                    </p>
                </td>
            </tr>
        </table>

    </div>
    <script>
        $(document).ready(function(){

            let baseApiUrl = "<?php echo admin_url('admin-ajax.php') ?>";
            let nonce = "<?php echo wp_create_nonce( MS_NONCE_REFERRER ) ?>";
            let alreadyRunning = false;
            $("#clear-meta").on("change", function(e){
                if($(this).prop("checked") && !confirm("Are you sure you want to force upload your media while publishing?. This will not affect the existing media but some media items may end up repeating themselves")){
                    $(this).prop("checked", false);
                }
            });
            $("#run-media-updates").on("click", function(){
                if(alreadyRunning){
                    return false;
                }
                alreadyRunning = true;
                let button = $(this);
                let summary = $("#summary");
                $.ajax({
                    url: baseApiUrl,
                    data: {
                        _wpnonce: nonce,
                        action: "ms_get_published_posts_all",
                    },
                    success: function(data){
                        if(data && data.posts){
                            let numOfPosts = Object.keys(data.posts).length;
                            let summaryPrepend = `Found ${numOfPosts} posts. Now fetching media details and updating each post.`;
                            summary.html(summaryPrepend);
                            let chain = Promise.resolve();
                            let index = 1;
                            Object.values(data.posts).map(({ post_id }) => {
                                chain = chain.then(() => updateMediaForStory(post_id, summaryPrepend+`<br>Processing story ${index++} out of ${numOfPosts}`));
                            });
                            chain.then(() => {
                                summary.html("All media updated. Thank you!")
                            });
                        }
                    },
                    error: function(){
                        button.html("Run Updates");
                        summary.html("Some error occurred while fetching story details. Please try again in some time!");
                    }
                });

                button.html("Running Updates...");
                summary.html("Fetching story details. Please do not leave the page");


                function updateMediaForStory(post_id, summaryPrepend){
                    return new Promise(resolve => {
                        $.ajax({
                            url: baseApiUrl,
                            data: {
                                _wpnonce: nonce,
                                post_id,
                                action: "ms_verify_media_in_story",
                            },
                            success: function(media){
                                if(media && Array.isArray(media) && media.length){
                                    let chain = Promise.resolve();
                                    let index = 1;
                                    media.map((url) => {
                                        chain = chain.then(() => {
                                            summary.html(`${summaryPrepend}.<br/>Downloading media: ${index++} out of ${media.length}`);
                                            return replaceMediaInStory(url, post_id)
                                        });
                                    });
                                    chain.then(resolve);
                                }else{
                                    resolve();
                                }
                            },
                            error: resolve,
                        });
                    });
                }

                function replaceMediaInStory(imageurl, post_id){
                    return new Promise(resolve => {
                        $.ajax({
                            url: baseApiUrl,
                            data: {
                                _wpnonce: nonce,
                                action: "ms_upload_image_to_media_library",
                                post_id,
                                imageurl,
                            },
                            success: resolve,
                            error: resolve,
                        });
                    })
                }

            });

            $("#run-story-updates").on("click", function(){
                if(alreadyRunning){
                    return false;
                }
                alreadyRunning = true;
                let button = $(this);
                let summary = $("#summary-republish");
                $.ajax({
                    url: baseApiUrl,
                    data: {
                        _wpnonce: nonce,
                        action: "ms_get_published_posts_all",
                    },
                    success: function(data){
                        if(data && data.posts){
                            let numOfPosts = Object.keys(data.posts).length;
                            let summaryPrepend = `Found ${numOfPosts} posts. Now fetching details and updating each post.`;
                            summary.html(summaryPrepend);
                            let chain = Promise.resolve();
                            let index = 1;
                            Object.values(data.posts).map(({ post_id, story_id, title }) => {
                                chain = chain.then(() => republishStory(post_id, story_id, title, summaryPrepend+`<br>Publishing story ${index++} out of ${numOfPosts}`));
                            });
                            chain.then(() => {
                                summary.html("All stories updated. Thank you!")
                            });
                        }
                    },
                    error: function(){
                        button.html("Run Updates");
                        summary.html("Some error occurred while fetching story details. Please try again in some time!");
                    }
                });

                button.html("Running Updates...");
                summary.html("Fetching story details. Please do not leave the page");


                function republishStory(post_id, story, slug, summaryPrepend){
                    return new Promise(resolve => {
                        $.ajax({
                            url: baseApiUrl,
                            data: {
                                _wpnonce: nonce,
                                post_id,
                                story,
                                slug,
                                is_republish: true,
                                action: "ms_publish_post",
                            },
                            success: function(response){
                                const { media } = response || {};
                                if(media && Array.isArray(media) && media.length){
                                    let chain = Promise.resolve();
                                    let index = 1;
                                    media.map((url) => {
                                        chain = chain.then(() => {
                                            summary.html(`${summaryPrepend}.<br/>Downloading media: ${index++} out of ${media.length}`);
                                            return replaceMediaInStory(url.imageurl, post_id)
                                        });
                                    });
                                    chain.then(resolve);
                                }else{
                                    resolve();
                                }
                            },
                            error: (err) => {
                                resolve();
                            },
                        });
                    });
                }

                function replaceMediaInStory(imageurl, post_id){
                    return new Promise(resolve => {
                        $.ajax({
                            url: baseApiUrl,
                            data: {
                                _wpnonce: nonce,
                                action: "ms_upload_image_to_media_library",
                                post_id,
                                imageurl,
                                is_republish: true,
                            },
                            success: resolve,
                            error: (err) => {
                                resolve();
                            }
                        });
                    })
                }

            });
        })
    </script>
    <!-- Category-wrap end -->
<?php }