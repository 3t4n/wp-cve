<?php
    $nonce = wp_create_nonce('avecdo_activation_form');
    $version = get_option('avecdo_version');
?>
<div class="avecdo-nav-container">
    <div class="avecdo-nav-inner">
        <form method="POST" action="?page=avecdo" class="avecdo-nav-nobutton <?php if ($version != 1) echo 'nosel' ?>">
            <input type="hidden" name="_wpnonce" value="<?php echo $nonce ?>">
            <input type="hidden" name="version" value="1">
            <button type="submit" class="hide-button">v1</button>
        </form>
        <form method="POST" action="?page=avecdo" class="avecdo-nav-nobutton <?php if ($version != 2) echo 'nosel' ?>">
            <input type="hidden" name="_wpnonce" value="<?php echo $nonce ?>">
            <input type="hidden" name="version" value="2">
            <button type="submit" class="hide-button">v2</button>
        </form>
    </div>
</div>
