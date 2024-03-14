<?php
if (isset($title) && !empty($title)) {
    echo $before_title . esc_html($title) . $after_title;
}
?>
<form class="ke-widget-form" accept-charset="UTF-8" method="POST" action="<?php echo esc_attr($get_url); ?>">
    <input class="ke-widget-input" style="margin-top: 10px" name="email" placeholder="Email" type="email" required/>
    <?php if (isset($with_name) && $with_name) { ?>
        <input class="ke-widget-input" style="margin-top: 10px" name="full_name" placeholder="Name" type="text"/>
    <?php } ?>
    <button class="ke-widget-input" style="margin-top: 10px" name="submit" type="submit">Ok</button>
</form>
