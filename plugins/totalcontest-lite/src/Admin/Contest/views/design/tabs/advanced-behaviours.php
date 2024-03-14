<customizer-control
        type="checkbox"
        label="<?php  esc_html_e( 'AJAX', 'totalcontest' ); ?>"
        ng-model="$root.settings.design.behaviours.ajax"
        help="<?php  esc_html_e( 'Load contest in-place without reloading the whole page.', 'totalcontest' ); ?>"
></customizer-control>

<customizer-control type="checkbox"
                    label="<?php  esc_html_e( 'Scroll up after vote submission', 'totalcontest' ); ?>"
                    ng-model="$root.settings.design.behaviours.scrollUp"
                    help="<?php  esc_html_e( 'Scroll up to contest viewport after submitting a vote.', 'totalcontest' ); ?>">
</customizer-control>

<div class="totalcontest-pro-badge-container">
    <customizer-control type="checkbox"
                        ng-if="$root.settings.design.behaviours.ajax"
                        label="<?php  esc_html_e( 'View submissions in modal', 'totalcontest' ); ?>"
                        ng-model="$root.settings.design.behaviours.modal"
                        help="<?php  esc_html_e( 'Load the submission in a modal window.', 'totalcontest' ); ?>">
    </customizer-control>
    <?php TotalContest( 'upgrade-to-pro' ); ?>
</div>
