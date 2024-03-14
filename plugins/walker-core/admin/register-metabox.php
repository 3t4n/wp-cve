<?php

add_action("admin_init", "walker_core_metabox_init");
function walker_core_metabox_init(){
    add_meta_box( 'reviewer_position',  esc_html__( 'Position', 'walker-core' ), 'walker_client_position', 'wcr_testimonials',
   'side', 'high',
    array(
        '__back_compat_meta_box' => false,
    )
   );

     add_meta_box( 'reviewer_compnay',  esc_html__( 'Company', 'walker-core' ), 'walker_client_company', 'wcr_testimonials',
   'side', 'high',
    array(
        '__back_compat_meta_box' => false,
    )
   );


    add_meta_box( 'team_compnay',  esc_html__( 'Company', 'walker-core' ), 'walker_team_company', 'wcr_teams',
   'side', 'high',
    array(
        '__back_compat_meta_box' => false,
    )
   );
    add_meta_box( 'team_position',  esc_html__( 'Position', 'walker-core' ), 'walker_team_position', 'wcr_teams',
   'side', 'high',
    array(
        '__back_compat_meta_box' => false,
    )
   );
    add_meta_box( 'team_facebook',  esc_html__( 'Facebook Profile', 'walker-core' ), 'walker_team_facebook', 'wcr_teams','side', 'default');
    add_meta_box( 'team_twitter',  esc_html__( 'Twitter Profile', 'walker-core' ), 'walker_team_twitter', 'wcr_teams','side', 'default');
    add_meta_box( 'team_linkedin',  esc_html__( 'Linkedin Profile', 'walker-core' ), 'walker_team_linkedin', 'wcr_teams','side', 'default');
    add_meta_box( 'team_instagram',  esc_html__( 'Instagram Profile', 'walker-core' ), 'walker_team_instagram', 'wcr_teams','side', 'default');
    add_meta_box( 'team_github',  esc_html__( 'Github Profile', 'walker-core' ), 'walker_team_github', 'wcr_teams','side', 'default');

    add_meta_box( 'primary_button',  esc_html__( 'Primary Button', 'walker-core' ), 'walker_slider_primary_button', 'wcr_slider','side', 'default');
    add_meta_box( 'primary_button_url',  esc_html__( 'Primary Button Link', 'walker-core' ), 'walker_slider_primary_button_link', 'wcr_slider','side', 'default');

    add_meta_box( 'secondary_button',  esc_html__( 'Secondary Button', 'walker-core' ), 'walker_slider_secondary_button', 'wcr_slider','side', 'default');
    add_meta_box( 'secondary_button_url',  esc_html__( 'Secondary Button Link', 'walker-core' ), 'walker_slider_secondary_button_link', 'wcr_slider','side', 'default');
}
// Position metabox
function walker_client_position() {
    global $post;
    $custom_meta = get_post_custom(get_the_ID());
    if (!empty($custom_meta)){
        if(isset($custom_meta['walker_client_position'])){
            $select_position = $custom_meta['walker_client_position'][0];
        }
    }
    ?>
    <fieldset class="fieldset related_pages">
        <div class = "walkercore_metabox">
            <input type="text" name="walker_client_position" value="<?php if(isset($select_position)) echo $select_position;?>"  style="width: 100%;">
        </div>
    </fieldset>
    <?php
}

function walker_client_company() {
    global $post;
    $custom_compnay = get_post_custom(get_the_ID());
    if (!empty($custom_compnay)){
        if(isset($custom_compnay['walker_client_company'])){
            $select_compnay = $custom_compnay['walker_client_company'][0];
        }
    }
    ?>
    <fieldset class="fieldset related_pages">
        <div class = "walkercore_metabox">
            <input type="text" name="walker_client_company" value="<?php if(isset($select_compnay)) echo $select_compnay;?>"  style="width: 100%;">
        </div>
    </fieldset>
    <?php
}

function walker_team_company() {
    global $post;
    $team_compnay = get_post_custom(get_the_ID());
    if (!empty($team_compnay)){
        if(isset($team_compnay['walker_team_company'])){
            $select_team_compnay = $team_compnay['walker_team_company'][0];
        }
    }
    ?>
    <fieldset class="fieldset related_pages">
        <div class = "walkercore_metabox">
            <input type="text" name="walker_team_company" value="<?php if(isset($select_team_compnay)) echo $select_team_compnay;?>"  style="width: 100%;">
        </div>
    </fieldset>
    <?php
}

function walker_team_position() {
    global $post;
    $team_position = get_post_custom(get_the_ID());
    if (!empty($team_position)){
        if(isset($team_position['walker_team_position'])){
            $select_team_position = $team_position['walker_team_position'][0];
        }
    }
    ?>
    <fieldset class="fieldset related_pages">
        <div class = "walkercore_metabox">
            <input type="text" name="walker_team_position" value="<?php if(isset($select_team_position)) echo $select_team_position;?>"  style="width: 100%;">
        </div>
    </fieldset>
    <?php
}
function walker_team_facebook() {
    global $post;
    $team_facebook = get_post_custom(get_the_ID());
    if (!empty($team_facebook)){
        if(isset($team_facebook['walker_team_facebook'])){
            $select_team_facebook = $team_facebook['walker_team_facebook'][0];
        }
    }
    ?>
    <fieldset class="fieldset related_pages">
        <div class = "walkercore_metabox">
            <input type="url" name="walker_team_facebook" value="<?php if(isset($select_team_facebook)) echo $select_team_facebook;?>"  style="width: 100%;">
        </div>
    </fieldset>
    <?php
}
function walker_team_twitter() {
    global $post;
    $team_twitter = get_post_custom(get_the_ID());
    if (!empty($team_twitter)){
        if(isset($team_twitter['walker_team_twitter'])){
            $select_team_twitter = $team_twitter['walker_team_twitter'][0];
        }
    }
    ?>
    <fieldset class="fieldset related_pages">
        <div class = "walkercore_metabox">
            <input type="url" name="walker_team_twitter" value="<?php if(isset($select_team_twitter)) echo $select_team_twitter;?>"  style="width: 100%;">
        </div>
    </fieldset>
    <?php
}
function walker_team_linkedin() {
    global $post;
    $team_linkedin = get_post_custom(get_the_ID());
    if (!empty($team_linkedin)){
        if(isset($team_linkedin['walker_team_linkedin'])){
            $select_team_linkedin = $team_linkedin['walker_team_linkedin'][0];
        }
    }
    ?>
    <fieldset class="fieldset related_pages">
        <div class = "walkercore_metabox">
            <input type="url" name="walker_team_linkedin" value="<?php if(isset($select_team_linkedin)) echo $select_team_linkedin;?>"  style="width: 100%;">
        </div>
    </fieldset>
    <?php
}
function walker_team_instagram() {
    global $post;
    $team_instagram = get_post_custom(get_the_ID());
    if (!empty($team_instagram)){
        if(isset($team_instagram['walker_team_instagram'])){
            $select_team_instagram = $team_instagram['walker_team_instagram'][0];
        }
    }
    ?>
    <fieldset class="fieldset related_pages">
        <div class = "walkercore_metabox">
            <input type="url" name="walker_team_instagram" value="<?php if(isset($select_team_instagram)) echo $select_team_instagram;?>"  style="width: 100%;">
        </div>
    </fieldset>
    <?php
}
function walker_team_github() {
    global $post;
    $team_github = get_post_custom(get_the_ID());
    if (!empty($team_github)){
        if(isset($team_github['walker_team_github'])){
            $select_team_github = $team_github['walker_team_github'][0];
        }
    }
    ?>
    <fieldset class="fieldset related_pages">
        <div class = "walkercore_metabox">
            <input type="url" name="walker_team_github" value="<?php if(isset($select_team_github)) echo $select_team_github;?>"  style="width: 100%;">
        </div>
    </fieldset>
    <?php
}

function walker_slider_primary_button() {
    global $post;
    $slide_primary_button = get_post_custom(get_the_ID());
    if (!empty($slide_primary_button)){
        if(isset($slide_primary_button['walker_slider_primary_button'])){
            $select_primary_button = $slide_primary_button['walker_slider_primary_button'][0];
        }
    }
    ?>
    <fieldset class="fieldset related_pages">
        <div class = "walkercore_metabox">
            <input type="text" name="walker_slider_primary_button" value="<?php if(isset($select_primary_button)) echo $select_primary_button;?>"  style="width: 100%;">
        </div>
    </fieldset>
    <?php
}

function walker_slider_primary_button_link() {
    global $post;
    $slider_primary_btn_url = get_post_custom(get_the_ID());
    if (!empty($slider_primary_btn_url)){
        if(isset($slider_primary_btn_url['walker_slider_primary_button_link'])){
            $select_primary_url = $slider_primary_btn_url['walker_slider_primary_button_link'][0];
        }
    }
    ?>
    <fieldset class="fieldset related_pages">
        <div class = "walkercore_metabox">
            <input type="url" name="walker_slider_primary_button_link" value="<?php if(isset($select_primary_url)) echo $select_primary_url;?>"  style="width: 100%;">
        </div>
    </fieldset>
    <?php
}
function walker_slider_secondary_button() {
    global $post;
    $slide_secondary_button = get_post_custom(get_the_ID());
    if (!empty($slide_secondary_button)){
        if(isset($slide_secondary_button['walker_slider_secondary_button'])){
            $select_secondary_button = $slide_secondary_button['walker_slider_secondary_button'][0];
        }
    }
    ?>
    <fieldset class="fieldset related_pages">
        <div class = "walkercore_metabox">
            <input type="text" name="walker_slider_secondary_button" value="<?php if(isset($select_secondary_button)) echo $select_secondary_button;?>"  style="width: 100%;">
        </div>
    </fieldset>
    <?php
}

function walker_slider_secondary_button_link() {
    global $post;
    $slider_secondary_btn_url = get_post_custom(get_the_ID());
    if (!empty($slider_secondary_btn_url)){
        if(isset($slider_secondary_btn_url['walker_slider_secondary_button_link'])){
            $select_secondary_url = $slider_secondary_btn_url['walker_slider_secondary_button_link'][0];
        }
    }
    ?>
    <fieldset class="fieldset related_pages">
        <div class = "walkercore_metabox">
            <input type="url" name="walker_slider_secondary_button_link" value="<?php if(isset($select_secondary_url)) echo $select_secondary_url;?>"  style="width: 100%;">
        </div>
    </fieldset>
    <?php
}

add_action('save_post', 'walker_core_metabox_fields');
function walker_core_metabox_fields() {
    global $post;
    if(isset($_POST["walker_client_position"])) {
        update_post_meta($post->ID, "walker_client_position", sanitize_text_field($_POST["walker_client_position"]) );
    }
    if(isset($_POST["walker_client_company"])) {
        update_post_meta($post->ID, "walker_client_company", sanitize_text_field($_POST["walker_client_company"]) );
    }
    if(isset($_POST["walker_team_company"])) {
        update_post_meta($post->ID, "walker_team_company", sanitize_text_field($_POST["walker_team_company"]) );
    }
    if(isset($_POST["walker_team_position"])) {
        update_post_meta($post->ID, "walker_team_position", sanitize_text_field($_POST["walker_team_position"]) );
    }
    if(isset($_POST["walker_team_facebook"])) {
        update_post_meta($post->ID, "walker_team_facebook", sanitize_text_field($_POST["walker_team_facebook"]) );
    }
    if(isset($_POST["walker_team_twitter"])) {
        update_post_meta($post->ID, "walker_team_twitter", sanitize_text_field($_POST["walker_team_twitter"]) );
    }
    if(isset($_POST["walker_team_linkedin"])) {
        update_post_meta($post->ID, "walker_team_linkedin", sanitize_text_field($_POST["walker_team_linkedin"]) );
    }
    if(isset($_POST["walker_team_instagram"])) {
        update_post_meta($post->ID, "walker_team_instagram", sanitize_text_field($_POST["walker_team_instagram"]) );
    }
    if(isset($_POST["walker_team_github"])) {
        update_post_meta($post->ID, "walker_team_github", sanitize_text_field($_POST["walker_team_github"]) );
    }
    if(isset($_POST["walker_slider_primary_button"])) {
        update_post_meta($post->ID, "walker_slider_primary_button", sanitize_text_field($_POST["walker_slider_primary_button"]) );
    }
    if(isset($_POST["walker_slider_primary_button_link"])) {
        update_post_meta($post->ID, "walker_slider_primary_button_link", sanitize_text_field($_POST["walker_slider_primary_button_link"]) );
    }
    if(isset($_POST["walker_slider_secondary_button"])) {
        update_post_meta($post->ID, "walker_slider_secondary_button", sanitize_text_field($_POST["walker_slider_secondary_button"]) );
    }
    if(isset($_POST["walker_slider_secondary_button_link"])) {
        update_post_meta($post->ID, "walker_slider_secondary_button_link", sanitize_text_field($_POST["walker_slider_secondary_button_link"]) );
    }

}