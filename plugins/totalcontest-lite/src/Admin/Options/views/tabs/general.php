<div class="totalcontest-tabs-container">
    <div class="totalcontest-tab-content active">
        <div class="totalcontest-settings-item">
            <div class="totalcontest-settings-field">
                <label>
                    <input type="checkbox" name="" ng-model="$ctrl.options.general.showCredits.enabled">
				    <?php  esc_html_e( 'Spread the love by adding "Powered by TotalContest" underneath the contests.', 'totalcontest' ); ?>
                </label>
            </div>
        </div>

        <div class="totalcontest-settings-item">
            <div class="totalcontest-settings-field">
                <label>
                    <input type="checkbox" name="" disabled>
					<?php  esc_html_e( 'Structured Data', 'totalcontest' ); ?>
                    <?php TotalContest( 'upgrade-to-pro' ); ?>
                </label>

                <p class="totalcontest-feature-tip">
                    <?php echo wp_kses( __('Improve your appearance in search engine through <a href="https://developers.google.com/search/docs/guides/intro-structured-data" target="_blank">Structured Data</a> implementation..', 'totalcontest' ), ['a' => ['href' => [], 'target' => []]]); ?></p>
            </div>
        </div>
    </div>
</div>
