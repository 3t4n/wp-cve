<form id="nsio-auth" class="nsio-form" action="admin.php?page=nextsale" method="POST">
    <input type="hidden" name="nsio-authorize" value="true">

    <div class="whitebox">
        <div class="logos">
            <div class="logo">
                <img src="<?= plugin_dir_url(dirname(__FILE__, 1)) . 'assets/nextsale-icon.png' ?>">
            </div>
            <div class="icon">
                <img src="<?= plugin_dir_url(dirname(__FILE__, 1)) . 'assets/install-icon.png' ?>">
            </div>
            <div class="logo">
                <img src="<?= plugin_dir_url(dirname(__FILE__, 1)) . 'assets/wordpress-icon.png' ?>">
            </div>
        </div>

        <div class="desc">
            <p>
                Let’s connect your website with Nextsale to start boosting
                conversion rates and increasing revenue to the next level.
            </p>

            <p class="small">
                By authorizing you acknowledge that you have read, understand and agree with Nextsale’s
                <a href="https://nextsale.io/terms" target="_blank">Terms of Service</a> &
                <a href="https://nextsale.io/privacy-policy" target="_blank">Privacy Policy</a>
                and give permission to this plugin to make information exchange between
                <a href="https://nextsale.io" target="_blank">Nextsale</a> and your website.
            </p>
        </div>


        <input type="submit" value="Authorize" class="submit-btn">
    </div>
</form>