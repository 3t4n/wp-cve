<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-02 17:19:27
 */
if (!defined("ABSPATH")) {
    die('We\'re sorry, but you can not directly access this file.');
}
if (isset($_GET["page"]) && $_GET["page"] == "recaptcha_for_all_admin_page") {
    if (
        isset($_POST["process"]) &&
        $_POST["process"] == "recaptcha_for_all_admin_page_background"
    ) {
        if (
            isset($_POST["recaptcha_my_plugin_nonce"]) &&
            wp_verify_nonce(
                $_POST["recaptcha_my_plugin_nonce"],
                "recaptcha_my_plugin_nonce"
            )
        ) {
        } else {
            die("Invalid Nonce");
        }
        $recaptcha_for_all_updated = false;
        // Capture the selected box position
        if (isset($_POST["recaptcha_for_all_box_position"])) {
            $recaptcha_for_all_box_position = sanitize_text_field($_POST["recaptcha_for_all_box_position"]);
            if (!empty($recaptcha_for_all_box_position)) {
                update_option(
                    "recaptcha_for_all_box_position",
                    $recaptcha_for_all_box_position
                );
                $recaptcha_for_all_updated = true;
            }
        }
        // Capture the selected foreground text color
        if (isset($_POST["recaptcha_foreground"])) {
            $recaptcha_for_all_foreground_color = sanitize_text_field(
                $_POST["recaptcha_foreground"]
            );
            if (!empty($recaptcha_for_all_foreground_color)) {
                update_option(
                    "recaptcha_for_all_foreground_color",
                    $recaptcha_for_all_foreground_color
                );
                $recaptcha_for_all_updated = true;
            }
        }
        // Capture the selected background color
        if (isset($_POST["recaptcha_background"])) {
            $recaptcha_for_all_background_color = sanitize_text_field(
                $_POST["recaptcha_background"]
            );
            if (!empty($recaptcha_for_all_background_color)) {
                update_option(
                    "recaptcha_for_all_background_color",
                    $recaptcha_for_all_background_color
                );
                $recaptcha_for_all_updated = true;
            }
        }
        // Capture the selected button text color
        if (isset($_POST["recaptcha_btn_foreground"])) {
            $recaptcha_for_all_btn_foreground_color = sanitize_text_field(
                $_POST["recaptcha_btn_foreground"]
            );
            if (!empty($recaptcha_for_all_btn_foreground_color)) {
                update_option(
                    "recaptcha_for_all_btn_foreground_color",
                    $recaptcha_for_all_btn_foreground_color
                );
                $recaptcha_for_all_updated = true;
            }
        }
        // Capture the selected button background color
        if (isset($_POST["recaptcha_btn_background"])) {
            $recaptcha_for_all_btn_background_color = sanitize_text_field(
                $_POST["recaptcha_btn_background"]
            );
            if (!empty($recaptcha_for_all_btn_background_color)) {
                update_option(
                    "recaptcha_for_all_btn_background_color",
                    $recaptcha_for_all_btn_background_color
                );
                $recaptcha_for_all_updated = true;
            }
        }
        // Capture the selected background image
        if (isset($_POST["recaptcha_for_all_image_background"])) {
            $recaptcha_for_all_image_background = sanitize_text_field(
                $_POST["recaptcha_for_all_image_background"]
            );
            if (!empty($recaptcha_for_all_image_background)) {
                update_option(
                    "recaptcha_for_all_image_background",
                    $recaptcha_for_all_image_background
                );
                $recaptcha_for_all_updated = true;
            }
        }
        //capture recaptcha_for_all_image_option (customized)
        if (isset($_POST["recaptcha_for_all_image_option"])) {
            $recaptcha_for_all_image_option = sanitize_text_field(
                $_POST["recaptcha_for_all_image_option"]
            );
            if (!empty($recaptcha_for_all_image_option)) {
                update_option(
                    "recaptcha_for_all_image_option",
                    $recaptcha_for_all_image_option
                );
                $recaptcha_for_all_updated = true;
            }
        }
        if ($recaptcha_for_all_updated) {
            recaptcha_for_all_updated_message();
        }
    }
}
$recaptcha_for_all_box_position = get_option(
    "recaptcha_for_all_box_position",
    "top"
);
$recaptcha_for_all_foreground_color = get_option(
    "recaptcha_for_all_foreground_color",
    "#ffffff"
);
$recaptcha_for_all_background_color = get_option(
    "recaptcha_for_all_background_color",
    "#000000"
);
$recaptcha_for_all_btn_foreground_color = get_option(
    "recaptcha_for_all_btn_foreground_color",
    "#ffffff"
);
$recaptcha_for_all_btn_background_color = get_option(
    "recaptcha_for_all_btn_background_color",
    "#9E9E9E"
);
$recaptcha_for_all_image_background = get_option(
    "recaptcha_for_all_image_background",
    ""
);
if (empty($recaptcha_for_all_image_background)) {
    //// $recaptcha_for_all_image_background = $background_images[0];
    $recaptcha_for_all_image_background =  RECAPTCHA_FOR_ALLURL.'images/background-plugin2.jpg';
}
$recaptcha_for_all_image_option = get_option(
    "recaptcha_for_all_image_option",
    "default"
);

$recaptcha_for_all_background = get_option("recaptcha_for_all_background", "");


echo '<div class="wrap-recaptcha">' . "\n";
echo '<h2 class="title">' .
    esc_attr__("Design", "recaptcha-for-all") .
    "</h2>" .
    "\n";
echo '<p class="description">' .
    esc_attr__("Options to match your site design.", "recaptcha-for-all") .
    "<br> </p>";
?>
<big>
    <b> <?php esc_attr_e("Info about template:", "recaptcha-for-all"); ?></b>
    <br>
     <?php esc_attr_e(
         "You can also edit by hand the file template.php on plugin root:",
         "recaptcha-for-all"
     ); ?><br>
    <?php
    echo esc_attr(RECAPTCHA_FOR_ALLPATH . "template.php ");

    //$recaptcha_for_all_background = get_option("recaptcha_for_all_background", "");

    if ($recaptcha_for_all_background == "yes") {
        $radio_active = true;
    } else {
        $radio_active = false;
    }
    $recaptcha_my_plugin_nonce = wp_create_nonce("recaptcha_my_plugin_nonce");
    ?>
    <form class="recaptcha_for_all-form" method="post" action="admin.php?page=recaptcha_for_all_admin_page&tab=design">
       <? echo '<input type="hidden" name="recaptcha_my_plugin_nonce" value="' . esc_attr( $recaptcha_my_plugin_nonce ) . '" />'; ?>
       <input type="hidden" name="process" value="recaptcha_for_all_admin_page_background" />
       <br><br>
       <div style="border: 1px solid #999; border-radius: 5px; padding: 0px 10px 10px 10px; margin-bottom: 20px;">
            <h3><?php esc_attr_e("Box Position", "recaptcha-for-all"); ?></h3>
            <!-- Radio buttons for position selection -->
            <label>
                <input type="radio" name="recaptcha_for_all_box_position" value="top" <?php echo $recaptcha_for_all_box_position === "top" ? "checked" : ""; ?>>
                <?php esc_attr_e("Top", "recaptcha-for-all"); ?>
            </label>
            <label>
                <input type="radio" name="recaptcha_for_all_box_position" value="footer" <?php echo $recaptcha_for_all_box_position === "footer" ? "checked" : ""; ?>>
                <?php esc_attr_e("Footer", "recaptcha-for-all"); ?>
            </label>
            <label>
                <input type="radio" name="recaptcha_for_all_box_position" value="center" <?php echo $recaptcha_for_all_box_position === "center" ? "checked" : ""; ?>>
                <?php esc_attr_e("Center", "recaptcha-for-all"); ?>
            </label>
        </div>
        <br>
        <div style="border: 1px solid #999; border-radius: 5px; padding: 0px 10px 10px 10px; margin-bottom: 20px;">
        <h3>Colors Customization</h3>
        <?php esc_attr_e("Foreground Text Color:", "recaptcha-for-all"); ?>
        <input type="color" id="recaptcha_foreground" name="recaptcha_foreground" value="<?php esc_attr_e(
            $recaptcha_for_all_foreground_color
        ); ?>">
        <br><br>
        <?php esc_attr_e("Background Color:", "recaptcha-for-all"); ?>
        <input type="color" id="recaptcha_background" name="recaptcha_background" value="<?php esc_attr_e(
            $recaptcha_for_all_background_color
        ); ?>">
        <br><br>
        <?php esc_attr_e("Button Text Color:", "recaptcha-for-all"); ?>
        <input type="color" id="recaptcha_btn_foreground" name="recaptcha_btn_foreground" value="<?php esc_attr_e(
            $recaptcha_for_all_btn_foreground_color
        ); ?>">
        <br><br>
        <?php esc_attr_e("Button Background Color:", "recaptcha-for-all"); ?>
        <input type="color" id="recaptcha_btn_background" name="recaptcha_btn_background" value="<?php esc_attr_e(
            $recaptcha_for_all_btn_background_color
        ); ?>">
</div>
        <br>
        <div style="border: 1px solid #999; border-radius: 5px; padding: 0px 10px 10px 10px; margin-bottom: 20px;">
        <h3><?php esc_attr_e(
            "Choose an Existing Background Image: (Or upload a new one below)",
            "recaptcha-for-all"
        ); ?></h3>
<br>
<label><?php esc_attr_e("Select an image:", "recaptcha-for-all"); ?></label>
<br>
<div style="width: 100%; display: flex; justify-content: space-between;">
<?php
// Array of image URLs
$background_images = [
    RECAPTCHA_FOR_ALLURL.'images/background.jpg',
    RECAPTCHA_FOR_ALLURL.'images/background-cookie.jpg',
    RECAPTCHA_FOR_ALLURL.'images/background-browser.jpg',
    RECAPTCHA_FOR_ALLURL.'images/background-plugin.jpg',
    RECAPTCHA_FOR_ALLURL.'images/background-plugin2.jpg',
    RECAPTCHA_FOR_ALLURL.'images/background-cookie2.jpg',
];
// Loop through the images and display them with associated radio buttons
foreach ($background_images as $image): ?>
    <label style="display: inline-block; text-align: center; width: 16%;">
        <div>
            <img src="<?php echo esc_attr($image); ?>" style="width: 100%;" />
        </div>
        <div style="text-align: center;">
            <input type="radio" id="radio_<?php echo esc_attr($image); ?>" name="recaptcha_for_all_image_background" value="<?php echo $image; ?>" <?php echo $recaptcha_for_all_image_background === $image ? "checked" : ""; ?> style="width: 16px; height: 16px;">
        </div>
    </label>
<?php endforeach; ?>
?>
</div>
</div>
<br>
<?php
?>
<div style="border: 1px solid #999; border-radius: 5px; padding: 0px 10px 10px 10px; margin-bottom: 20px;">
    <h3><?php esc_attr_e("Image Options", "recaptcha-for-all"); ?></h3>
    <p><?php esc_attr_e("Choose how you want to display the background image", "recaptcha-for-all"); ?>:</p>
    <label>
    <input type="radio" name="recaptcha_for_all_image_option" value="default" <?php echo ($recaptcha_for_all_image_option === 'default') ? 'checked' : ''; ?>>
    <?php esc_attr_e("Use default images (above)", "recaptcha-for-all");?>
    </label>
    <br>
    <label>
        <input type="radio" name="recaptcha_for_all_image_option" value="custom" <?php echo ($recaptcha_for_all_image_option === 'custom') ? 'checked' : ''; ?>>
        <?php esc_attr_e("Use customized image uploaded (below)", "recaptcha-for-all");?>
    </label>
</div>
<br>
<div style="border: 1px solid #999; border-radius: 5px; padding: 0px 10px 10px 10px; margin-bottom: 20px;">
        <h3><?php esc_attr_e("Upload New Background Image if you choose to", "recaptcha-for-all");?></h3>
        <?php esc_attr_e(
            "You have the option to upload your own background image (recommended size: 1920px X 1080px).","recaptcha-for-all"
        ); ?>
        <br>
        <br>
        <?php
        $recaptcha_for_all_custom_image_background = trim(sanitize_url(get_option('recaptcha_for_all_custom_image_background', '')));
        if(!empty(trim($recaptcha_for_all_custom_image_background))) {
            echo '<br>';
            esc_attr_e(
                "Currently uploaded image displayed below.","recaptcha-for-all"); 
            echo '<br>';
            echo '<img id="recaptcha_for_all_custom_image_background" src="'. esc_url($recaptcha_for_all_custom_image_background).'" width=200px>';
        }
       ?>
        <br><br> 
        <button id="recaptcha-for-all-media-uploader-button" class="button"><?php esc_attr_e("Select New Image", "recaptcha-for-all");?></button>
        <br>
         </div>
<?php
echo '<input class="recaptcha_for_all-submit button-primary" type="submit" value="' .
    esc_attr__("Update Options", "recaptcha-for-all") .
    '" />';
echo "</form>" . "\n";
echo "</big></div>";
function recaptcha_for_all_updated_message()
{
    echo '<div class="notice notice-success is-dismissible">';
    echo "<br /><b>";
    esc_attr_e("Database Updated!", "recaptcha_for_all");
    echo "<br /><br /></div>";
}
