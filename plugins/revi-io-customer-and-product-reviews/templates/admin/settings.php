<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div id="revi_back" class="container">

    <div class="row">
        <div class="col-12">

            <img src="<?php echo plugins_url('../assets/img/logo-256x256.png', dirname(__FILE__)) ?>" /><br />

            <form method="post">
                <div class="form-group row" style="margin-top:40px;">
                    <label class="col-sm-2 right">API KEY</label>
                    <div class="col-sm-10 left">
                        <input type="text" size="30" name="REVI_API_KEY" value="<?= get_option('REVI_API_KEY') ?>" placeholder="API KEY" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 right">Order Status</label>
                    <div class="col-sm-10 left">
                        <select name="status[]" multiple>
                            <?php foreach ($order_status as $key => $value) : ?>
                                <option value="<?= $key ?>" <?= ($status_selected[$key]) ? 'selected' : '' ?>>
                                    <?= $value ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 right">Revi Stores </label>
                    <div class="col-sm-10 left">
                        <select name="stores">
                            <?php foreach ($stores as $store) : ?>
                                <option value="<?= $store->id_store ?>" <?= ($store->id_store == $selected_store) ? 'selected' : '' ?>><?= $store->domain_url ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 right"> Default Language </label>
                    <div class="col-sm-10 left">
                        <select name="languages">
                            <?php foreach ($active_languages as $active_lang) : ?>
                                <option value="<?= $active_lang ?>" <?= ($active_lang  == $selected_language) ? 'selected' : '' ?>><?= $active_lang ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 right"> Product page reviews position </label>
                    <div class="col-sm-10 left">
                        <select name="tab_reviews">
                            <option value="0" <?= ($tab_reviews == 0) ? 'selected' : '' ?>> Footer Reviews</option>
                            <option value="1" <?= ($tab_reviews) ? 'selected' : '' ?>> Tab Reviews</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 right"> Product page stars position </label>
                    <div class="col-sm-10 left">
                        <select name="tab_product_stars">
                            <option value="0" <?= ($tab_product_stars) ? 'selected' : '' ?>> Single product summary</option>
                            <option value="1" <?= ($tab_product_stars == 1) ? 'selected' : '' ?>> Before add to card</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 right"> Display Floating widget </label>
                    <div class="col-sm-10 left">
                        <select name="display_widget_floating">
                            <option value="0" <?= ($display_widget_floating == 0) ? 'selected' : '' ?>> No</option>
                            <option value="1" <?= ($display_widget_floating) ? 'selected' : '' ?>> Yes </option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 right"> Display native woocommerce reviews </label>
                    <div class="col-sm-10 left">
                        <select name="woocommerce_reviews">
                            <option value="0" <?= ($woocommerce_reviews == 0) ? 'selected' : '' ?>> No </option>
                            <option value="1" <?= ($woocommerce_reviews) ? 'selected' : '' ?>> Yes </option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 right"> Category JSON Reviews meta data </label>
                    <div class="col-sm-10 left">
                        <select name="REVI_CATEGORY_JSON_META">
                            <option value="0" <?= ($REVI_CATEGORY_JSON_META == 0) ? 'selected' : '' ?>> No </option>
                            <option value="1" <?= ($REVI_CATEGORY_JSON_META) ? 'selected' : '' ?>> Yes </option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                        <input class="btn btn-primary" type="submit" name="submitConfiguration" value="<?php esc_html_e('save', 'revi-io-customer-and-product-reviews') ?>" class="revi_button revi_button_small" />
                    </div>
                </div>


            </form>

            <div class="row" style="margin-top:30px;">
                <div class="col-12">
                    <p class="green"><?php esc_html_e('Congratulations! You are now logged in, This is your current subscription', 'revi-io-customer-and-product-reviews') ?> <?= $subscription ?>.</p>
                    <p><?php esc_html_e('Now you can configure your settings at', 'revi-io-customer-and-product-reviews') ?> <a class="green" target="_blank" href="https://revi.io/en/">revi.io</a></p>
                </div>
            </div>

            <?php if ($message) : ?>
                <div class="row">
                    <div class="col-12">
                        <div class="alert <?= ($result_update) ? 'alert-success' : 'alert-danger' ?>" role="alert"><?= $message ?></div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>



</div>