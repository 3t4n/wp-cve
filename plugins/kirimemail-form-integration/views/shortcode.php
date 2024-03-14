<?php if (isset($title)) { ?>
    <h2 class="ke-sc-title"><?php echo esc_html($title); ?></h2>
<?php } ?>
<form class="ke-sc-form" accept-charset="UTF-8" method="POST" action="<?php echo esc_attr($url); ?>">
    <input class="ke-sc-input" style="margin-top: 10px" name="email" placeholder="Email" type="email" required/>
    <?php if (isset($with_name) && $with_name) { ?>
        <input class="ke-sc-input" style="margin-top: 10px" name="full_name" placeholder="Name" type="text"/>
    <?php } ?>
    <input class="ke-sc-input" style="margin-top: 10px" name="submit" value="OK" type="submit">
</form>
