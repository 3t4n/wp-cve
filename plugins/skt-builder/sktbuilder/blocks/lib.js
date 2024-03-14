jQuery(document).ready(function() {
    jQuery('p').each(function() {
        var $this = jQuery(this);
        if($this.html().replace(/\s|<br>/g, '').length == 0) {
            $this.remove();
        }
    });
});