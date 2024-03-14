<div id="nsio-redirect">
    <div>
        <img src="<?= $image_url ?>" alt="redirecting...">

        <p>Redirecting to Nextsale...</p>
        <p class='small'>
            <a href="<?= $redirect_url ?>">Click here</a> if you're not redirected to the Nextsale within 5 seconds.
        </p>
    </div>
</div>

<script>
    window.location.href = "<?= $redirect_url ?>"
</script>

<noscript>
    <meta http-equiv="refresh" content="0;url=<?= $redirect_url ?>" />
</noscript>