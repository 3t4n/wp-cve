
<div class="bdroppy_base" >
    <div class="container">
        <div class="login_card">
            <div class="card_header"></div>
            <div class="card_body">
                <div class="left_side">
                    <div class="info_title">
                        Thousands of designer fashion products, a unique dashboard for your dropshipping business
                    </div>
                    <ul class="descriptions">
                        <li>Dropshipping service</li>
                        <li>Over 100 fashion brands in the catalogue</li>
                        <li>Integrations with Amazon and eBay</li>
                        <li>Plugins for Prestashop, Woocommerce and Shopify</li>
                        <li>Turnkey e-commerce website solution</li>
                    </ul>
                </div>
                <div class="right_side">
                    <div class="form_title">Login
                        <div style="font-weight: bold; font-size: 13px; padding: 10px 0px;">Not yet registered? <a href="https://dash.bdroppy.com/register" style="color: rgb(110, 243, 202); cursor: pointer;">Sign up</a> to BDroppy</div></div>
                        <input class="form_control" type="hidden" id="api-base-url" name="api-base-url" value="https://prod.bdroppy.com">
                    <?php
                    if(isset($_GET['lwt']) && $_GET['lwt'] == 1) {
                    ?>
                        <div class="from_group">
                            <label class="form_label" for="input-token">Token</label>
                            <input class="form_control" type="text" id="input-token" name="api-token">
                        </div>
                    <?php }else{ ?>
                    <div class="from_group">
                        <label class="form_label" for="input-email">Email</label>
                        <input class="form_control" type="email" id="input-email" name="api-email">
                    </div>
                    <div class="from_group">
                        <label class="form_label" for="input-password">Password</label>
                        <input class="form_control" type="password" id="input-password" name="api-password">
                    </div>
                    <div class="from_group">
                        <?php } ?>

                        <button type="button" class="btn btn-block btn-primary btn-login"  data-loading-text="sss" >Login</button>
<!--                        <button class="form_buttom fb_color1" type="submit" >Login</button>-->
                        <a href="https://dash.bdroppy.com/register" class="form_buttom fb_link" type="submit">Forgot Password?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>