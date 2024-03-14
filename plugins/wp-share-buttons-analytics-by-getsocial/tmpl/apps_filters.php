<div id="app-grid-filters">
    <div class="app-grid-filter-holder">
        <a href="javascript:void(0)" id="clear-filter" class="gs-button gs-error trans" style="display: none"><i class="fa fa-times-circle"></i> Clear filter</a>
      <?php if ($plan_is_free) { ?>
        <a href="javascript:void(0)" class="gs-button plan-one trans gs-tooltip" data-filter="one">Tools Plan<div>Apps that are only available in the <strong>Tools Plan</strong></div></a>
      <?php } ?>
        <a href="javascript:void(0)" id="app-filter" class="gs-button gs-primary trans"><i class="fa fa-filter"></i> Category</a>
        <div id="app-filter-dropdown">
            <a href="javascript:void(0)" class="gs-button gs-primary trans filter-btn js-app-one" data-filter="sharing">Sharing Apps</a>
            <a href="javascript:void(0)" class="gs-button gs-primary trans filter-btn js-app-one" data-filter="tracking">Tracking & Engagement Tools</a>
            <a href="javascript:void(0)" class="gs-button gs-primary trans filter-btn js-app-one" data-filter="follow">Follow Apps</a>
            <a href="javascript:void(0)" class="gs-button gs-primary trans filter-btn js-app-nocode" data-filter="integrations">Integrations</a>
        </div>
    </div>
</div>
