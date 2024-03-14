<div class="wrap">
    <div class="pptitle"><span class="dashicons dashicons-lightbulb"></span>
        <?php _e('MW Font Changer - Feedback', 'mwfc'); ?>
    </div>
    <br/>
    <br/>
    <?php
if(isset($_POST['submit'])) {
if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['message'])  && !empty($_POST['subject'])) {
if($_POST['code'] == $_SESSION['rand_code']) {
// send email
$accept = __('Your message was successfully sent. Thanks', 'mwfc');
$to = "ghaemomidi@yahoo.com";
$subject = $_POST['subject'];
$name= $_POST['name'];
$from = $_POST['email'];
$user_message = $_POST['message'];
$body = "\n".
"Name: $name\n".
"Email: $from \n".
"Message: \n ".
"$user_message\n".
$headers = "From: $from \r\n";
$headers .= "Reply-To: $from \r\n";
mail($to, $subject, $body, $headers);
}
} else {
$error = __('Please fill all fields.', 'mwfc');
}
}
?>
        <div class="bodyparsi">
            <div id="mainparsi">
                <div class="contentparsi">
                    <h2>
                        <?php _e('If you want your favorite font to be used in plugin or you have comments or suggestions please let us know via this form:', 'mwfc') ?>
                    </h2>
                    <?php if(!empty($error)) echo '<div class="errorwppafe">'.$error.'</div>'; ?>
                    <?php if(!empty($accept)) echo '<div class="okwppafe">'.$accept.'</div>'; ?>
                    <p>
                        <div class="formsparsi">
                            <form action="" method="post">
                                <label for="username"><?php _e('Name', 'mwfc') ?></label>
                                <br/>
                                <input class="textare2" type="text" id="username" class="formparsi" value="" name="name">
                                <br/><br/>
                                <label for="email"><?php _e('Email', 'mwfc') ?></label>
                                <br/>
                                <input class="textare2" type="text" id="email" value="" class="form-ltr" name="email">
                                <br/><br/>
                                <label for="sub"><?php _e('Subject', 'mwfc') ?></label>
                                <br/>
                                <input class="textare2" type="text" id="sub" value="" class="formparsi" name="subject">
                                <br/><br/>
                                <label for="mess"><?php _e('Message', 'mwfc') ?></label>
                                <br/>
                                <textarea class="textare1" id="mess" rows="7" name="message"></textarea>
                                <br/><br/>
                                <input style="float:none !important;margin-right:auto;margin-left:auto;display:block;" class="submitbot" type="submit" name="submit" value="<?php _e('Send', 'mwfc') ?>">
                            </form>
                        </div>
                    </p>
                </div>
                <div class="clear"></div>
            </div>
        </div>
</div>