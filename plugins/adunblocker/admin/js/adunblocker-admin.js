(function ($) {
    "use strict";

    document.addEventListener("DOMContentLoaded", function () {
        if ($("input.adunblocker-color-picker").length) {
            $("input.adunblocker-color-picker").alphaColorPicker();
        }

        if ($(".toggle").length) {
            $(".toggle").on("change", "input:checkbox", function () {
                $(this).prop("selected", $(this)[0].checked ? 1 : 0);
                $(this).val($(this)[0].checked ? 1 : 0);
            });
        }

        // Type/Delay field controls
        var $type = document.getElementById("adunblocker-type");

        if (elementExists($type)) {
            $type.addEventListener("change", toggle_delay);
            toggle_delay();
        }

        // Color picker functionality
        $(".adunblocker-color-picker").each(function () {
            var $this = $(this);
            var id = $this.attr("id");
            $("#" + id).wpColorPicker();
        });
    });

    function toggle_delay() {
        var $type = document.getElementById("adunblocker-type");
        var $delay = document.getElementById("adunblocker-delay");
        var $scope = document.getElementById("adunblocker-scope");
        var $tr = $delay.parentNode.parentNode;
        var $tr_scope = $scope.parentNode.parentNode;

        if ($type.value != "temp") {
            $tr.style.display = "none";
        } else {
            $tr.style.display = "table-row";
        }

        if (["dismissible", "temp"].includes($type.value)) {
            $tr_scope.style.display = "table-row";
        } else {
            $tr_scope.style.display = "none";
        }
    }

    function elementExists(element) {
        if (typeof element != "undefined" && element != null) {
            return true;
        }

        return false;
    }
})(jQuery);