<div class="totalcontest-integration-steps" ng-controller="SidebarIntegrationCtrl as sidebarIntegrationCtrl">
    <div class="totalcontest-integration-steps-item">
        <div class="totalcontest-integration-steps-item-number">
            <div class="totalcontest-integration-steps-item-number-circle">1</div>
        </div>
        <div class="totalcontest-integration-steps-item-content">
            <h3 class="totalcontest-h3">
                <?php  esc_html_e( 'Add it to sidebar', 'totalcontest' ); ?>
            </h3>
            <p>
                <?php  esc_html_e( 'Start by adding this contest to one of available sidebars:', 'totalcontest' ); ?>
            </p>
            <div class="totalcontest-integration-steps-item-copy">
                <select name="" ng-model="sidebarIntegrationCtrl.sidebar" ng-options="sidebar as sidebar.name for sidebar in sidebarIntegrationCtrl.sidebars">
                    <option value=""><?php  esc_html_e('Select a sidebar', 'totalcontest'); ?></option>
                </select>
                <button type="button" class="button button-primary button-large" ng-disabled="!sidebarIntegrationCtrl.sidebar || sidebarIntegrationCtrl.sidebar.inserted"
                        ng-click="sidebarIntegrationCtrl.addWidgetToSidebar()">
                    <span ng-if="!sidebarIntegrationCtrl.sidebar.inserted"><?php  esc_html_e( 'Insert', 'totalcontest' ); ?></span>
                    <span ng-if="sidebarIntegrationCtrl.sidebar.inserted"><?php  esc_html_e( 'Inserted', 'totalcontest' ); ?></span>
                </button>
            </div>
        </div>
    </div>
    <div class="totalcontest-integration-steps-item">
        <div class="totalcontest-integration-steps-item-number">
            <div class="totalcontest-integration-steps-item-number-circle">2</div>
        </div>
        <div class="totalcontest-integration-steps-item-content">
            <h3 class="totalcontest-h3">
                <?php  esc_html_e( 'Preview', 'totalcontest' ); ?>
            </h3>
            <p>
                <?php  esc_html_e( 'Open the page which you have the same sidebar and test contest functionality.', 'totalcontest' ); ?>
            </p>
        </div>
    </div>
</div>
