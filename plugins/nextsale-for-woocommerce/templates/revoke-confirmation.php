<form id="nsio-revoke" class="nsio-form" action="admin.php?page=nextsale&action=revoke" method="POST">
    <input type="hidden" name="nsio-revoke" value="true">

    <div class="whitebox">
        <div class="logos">
            <div class="logo">
                <img src="<?= plugin_dir_url(dirname(__FILE__, 1)) . 'assets/nextsale-icon.png' ?>">
            </div>
        </div>

        <div class="desc">
            <p>
                Do you really want to remove access?
            </p>

            <p class="small">
                Nextsale will no longer have access to your website. This means
                that Nextsale will not be able to do information exchange with
                your site. You’ll need to grant access if you want to use
                Nextsale services again.
            </p>

            <p class="small">
                Contact with us directly at <a href="mailto:support@nextsale.io">support@nextsale.io</a>
                if you have any question.
            </p>
        </div>

        <input type="submit" value="Remove access" class="submit-btn nsio-alert">
    </div>
</form>