<?php

function pzat_zoom_options_input($args){
  $options = pzat_zoom_options();
  $id = esc_attr( $args['label_for'] );
  $custom_data = esc_attr( $args['zoom_custom_data'] );
  $value = $options->get($id);

  return "<input id='$id' class='w-50' data-custom='$custom_data' name='zoom_options[$id]' value='$value'></input>";
}


function pzat_zoom_options_textarea($args){
    $options = pzat_zoom_options();
    $id = esc_attr( $args['label_for'] );
    $custom_data = esc_attr( $args['zoom_custom_data'] );
    $value = $options->get($id);
    $placeholder = $args['placeholder'];

    return "<textarea id='$id' class='w-50' data-custom='$custom_data' name='zoom_options[$id]' placeholder='$placeholder'>$value</textarea>";
}


function pzat_blocklist_details() {
  return <<<MARKUP
<details>
  <summary>ℹ️  Block the widget by URL parts...</summary>
<p><code>news</code> ignores all pages which URLs contain the word "news".</p>
<p><code>.pdf</code> ignores all pages with pdf files.</p>
<p>Multiple entries are separated by newline.</p>
</details>
MARKUP;
}


function pzat_strict_blocklist_details() {
  return <<<MARKUP
<details>
  <summary>ℹ️  Specific URLs can be blocked...</summary>
  <p>The match needs to be exact. For instance:</p>
  <ul>
    <li><code>https://your-website.com/homepage.html</code></li>
  </ul>
</details>
MARKUP;
}


function pzat_content_deselector_details() {
  $youtube_image = PZAT_ASSETS_URL . 'admin/youtube-icon.png';
  return <<<MARKUP
<details>
  <summary>ℹ️  Exclude page parts from the zoom...</summary>
  <p>Content deselectors are CSS selectors that exclude
  parts of the page from being zoomed. For instance:
  <ul>
    <li><code>.main-menu-top a</code></li>
    <li><code>section footer p.date</code></li>
  </ul>
  Check out this <a href='https://www.youtube.com/watch?v=X5vRU3G5CcY' target='_blank'>Video<img src='$youtube_image' alt='Youtube Logo' style='transform: translateY(7px)' /></a>, how it works!
  </p>
</details>
MARKUP;
}


function pzat_position_details() {
  return <<<MARKUP
<details>
  <summary>ℹ️  Change the widget position...</summary>
  <p>
Set the default widget position on the left side.
Soon you will be also able to place it on the right side of the screen.
  </p>
</details>
MARKUP;
};


function pzat_logo_url_details() {
  $media_url = admin_url('/upload.php');
  $youtube_image = PZAT_ASSETS_URL . 'admin/youtube-icon.png';
  return <<<MARKUP
<details>
  <summary>ℹ️  Use your own logo...</summary>
  <p>
Choose your own logo, which will be visible on top of the widget.
Therefor you need to insert the logs's URL from your
<a href="$media_url" target="_blank">WordPress Media</a>.
  </p>
  <p>
The image's dimmension should be at least <b>35x35px</b>.
If you don't want a logo at all, <b>empty</b> this field.
  </p>
  <p>
Check out this <a href='https://www.youtube.com/watch?v=SNypgjpYCoc' target='_blank'>Video<img src='$youtube_image' alt='Youtube Logo' style='transform: translateY(7px)' /></a>, how it works!
  </p>
</details>
MARKUP;
}


function pzat_logo_title_details() {
  return <<<MARKUP
<details>
  <summary>ℹ️  Use a logo tooltip...</summary>
  <p>
The Logo Title is used as tooltip a user sees when hovering the logo with the cursor.
  </p>
</details>
MARKUP;
}


function pzat_logo_link_details() {
  return <<<MARKUP
<details>
  <summary>ℹ️  Use a linked logo...</summary>
  <p>
The logo can also be a link to a certain page. Just insert the URL here.
  </p>
  <ul>
    <li><b>Example Links:</b></li>
    <li><code>https://your-site.com</code></li>
    <li><code>http://your-site.com</code></li>
    <li><code>//your-site.com</code></li>
  </ul>
</details>
MARKUP;
}

function pzat_preconfigure_notice() {
  return <<<MARKUP
<p>
  <strong><span style='color:red;'>Important</span>: Please note that users can individually change your pre-configured default design to match their own preferred style.<br/>
New users will see your configured default design first.</strong><br>
To preview design changes, click this link
<a href='#' onclick='localStorage.clear()'>Clear Local Storage</a>
to clear your local storage.</p>
MARKUP;
}

?>
