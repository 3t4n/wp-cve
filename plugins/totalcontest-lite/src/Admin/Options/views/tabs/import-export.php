<div class="totalcontest-tabs-container">
    <div class="totalcontest-tab-content active">
        <div class="totalcontest-settings-item">
            <div class="totalcontest-settings-field">
                <div class="button-group">
                    <a href="<?php echo esc_attr( admin_url( 'import.php?import=wordpress' ) ); ?>" class="button">
						<?php  esc_html_e( 'Import data', 'totalcontest' ); ?>
                    </a>
                    <a href="<?php echo esc_attr( admin_url( 'export.php?content=contest&download' ) ); ?>" class="button">
						<?php  esc_html_e( 'Export data', 'totalcontest' ); ?>
                    </a>
                    <button type="button" class="button" ng-click="$ctrl.downloadSettings()">
						<?php  esc_html_e( 'Export settings', 'totalcontest' ); ?>
                    </button>
                </div>
                <p class="totalcontest-feature-tip">
					<?php  esc_html_e( 'TotalContest uses standard WordPress import/export mechanism.', 'totalcontest' ); ?>
                </p>
            </div>
        </div>
        <div class="totalcontest-settings-item">
            <div class="totalcontest-settings-field">
                <textarea class="widefat" name="" rows="10" placeholder="<?php esc_attr_e( 'Drag and drop settings file or copy then paste its content here.', 'totalcontest' ); ?>" ng-model="$ctrl.import.content" ng-disabled="$ctrl.isImporting()"></textarea>
            </div>
            <div class="totalcontest-settings-field">
                <button type="button" class="button" ng-click="$ctrl.importSettings()" ng-disabled="!$ctrl.isImportReady()">
                    <span ng-if="!$ctrl.isImporting() && !$ctrl.isImported()"><?php  esc_html_e( 'Import settings', 'totalcontest' ); ?></span>
                    <span ng-if="$ctrl.isImporting()"><?php  esc_html_e( 'Importing', 'totalcontest' ); ?></span>
                    <span ng-if="$ctrl.isImported()"><?php  esc_html_e( 'Imported', 'totalcontest' ); ?></span>
                </button>
            </div>
        </div>
    </div>
</div>
