<script type="text/javascript">
    window.bdroppy || (window.bdroppy = []), window.bdroppy.q = [], mth = ["identify", "track", "event", "pageview", "purchase", "debug", "atr"], sk = function(e) {
        return function() {
            a = Array.prototype.slice.call(arguments);
            a.unshift(e);
            window.bdroppy.q.push(a)
        }
    };

    window.bdroppy.baseURL = "<?php echo $this->baseURL;  ?>";
    window.bdroppy.user_id = "<?php echo $this->user_id;  ?>";
    for (var i = 0; mth.length > i; i++) {
        window.bdroppy[mth[i]] = sk(mth[i])
    }
    window.bdroppy.load = function()
    {
        var t = document,
            n = t.getElementsByTagName("script")[0];
            r = t.createElement("script");
        r.type = "text/javascript";
        r.async = true;
        r.src = "<?php echo BDROPPY_JS . "tracking-sync.js?v=" . time() ?>";
        n.parentNode.insertBefore(r, n)
    };
    window.bdroppy.ensure_cbuid = "<?php echo $this->cbuid ?>";
    <?php if($this->accept_tracking): ?>
    bdroppy.load();
    <?php endif ?>
</script>