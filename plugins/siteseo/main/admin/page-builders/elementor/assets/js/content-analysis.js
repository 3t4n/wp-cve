contentAnalysisToggle();

var contentAnalysisView = elementor.modules.controls.BaseData.extend({
    onReady: function () {
        if (
            siteseoFiltersElementor.resize_panel &&
            siteseoFiltersElementor.resize_panel === "1"
        ) {
            elementor.panel.storage.size.width = "495px";
            elementor.panel.setSize();
        }

        contentAnalysis();
        jQuery(document).on("click", "#siteseo_launch_analysis", function () {
            contentAnalysis();
        });
    },
});

elementor.addControlView("siteseo-content-analysis", contentAnalysisView);
