<?php

defined('WPINC') || die;


?>
<select class="premmerce-multicurrency">

    <?php
    foreach ($currencies as $currency):
        ?>
        <option value="<?php echo esc_attr($currency['id']); ?>" <?php selected($currency['id'],
            $usersCurrency); ?> > <?php echo esc_html($currency['currency_name']); ?></option>
    <?php
    endforeach;
    ?>

</select>
