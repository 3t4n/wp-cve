<script type="text/ng-template" id="modules-manager-component-template">
    <div class="totalcontest-modules-gallery"
         ng-class="{'totalcontest-processing': $ctrl.modules === null}"
         ng-init="$root.$manager = $ctrl">
        <table class="wp-list-table widefat plugins">
            <thead>
            <tr style="position: sticky;top: 32px;background: white;z-index: 1;box-shadow: 0 3px 3px rgb(0 0 0 / 5%);">
                <th scope="col" colspan="2" id="name"
                    class="manage-column column-name column-primary"><?php esc_html_e( 'Extension',
				                                                                       'totalcontest' ); ?></th>
                <th scope="col" id="description"
                    class="manage-column column-description"><?php esc_html_e( 'Description', 'totalcontest' ); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat-start="module in $ctrl.getModules()"
                ng-class="{'active': module.isActivated(), 'inactive': !module.isActivated(), 'update': $ctrl.getError(module)}"
                ng-if="($root.isCurrentTab('modules>installed') && module.isInstalled()) || ($root.isCurrentTab('modules>store') && !module.isInstalled())">
                <td style="width: 48px">
                    <img style="background:#eee;" width="44" height="44"
                         ng-src="{{module.getImage('icon') ? module.getImage('icon') : 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAAtJREFUGFdjYAACAAAFAAGq1chRAAAAAElFTkSuQmCC'}}"
                         onerror="this.src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAAtJREFUGFdjYAACAAAFAAGq1chRAAAAAElFTkSuQmCC'">
                </td>
                <td class="plugin-title column-primary"
                    ng-class="{'totalcontest-processing': $ctrl.isProcessing(module), 'totalcontest-successful': $ctrl.isSuccessful(module)}">
                    <strong>{{module.getName()}}</strong>
                    <div class="row-actions visible">
                        <span class="activate" ng-if="module.isInstalled() && !module.isActivated()">
                            <a style="cursor: pointer" class="edit" ng-click="$ctrl.applyAction('activate', module)">
                                <?php esc_html_e( 'Activate', 'totalcontest' ); ?>
                            </a> | </span>
                        <span class="activate" ng-if="!module.isInstalled()">
                            <a style="cursor: pointer" class="edit" ng-click="$ctrl.applyAction('activate', module)">
                                <?php esc_html_e( 'Get it', 'totalcontest' ); ?>
                            </a> | </span>

                        <span class="delete">
                            <a style="cursor: pointer" class="delete" ng-click="$ctrl.applyAction('uninstall', module)"
                               ng-if="module.isInstalled() && !module.isActivated()">
                                <?php esc_html_e( 'Uninstall', 'totalcontest' ); ?>
                            </a>

                            <a style="cursor: pointer" class="delete"
                               ng-if="module.getId() !== 'basic-template' && module.isInstalled() && module.isActivated()"
                               ng-click="$ctrl.applyAction('deactivate', module)">
                                <?php esc_html_e( 'Deactivate', 'totalcontest' ); ?>
                            </a>
                        </span>
                    </div>
                    <button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span>
                    </button>
                </td>
                <td class="column-description desc">
                    <div class="plugin-description"><p ng-bind-html="module.get('description')"></p></div>
                    <small style="opacity: 0.75;">
						<?php esc_html_e( 'Version', 'totalcontest' ); ?> {{module.getVersion()}} |
						<?php esc_html_e( 'By', 'totalcontest' ); ?>
                        <a ng-href="{{module.getAuthorUrl()}}" target="_blank">{{module.getAuthorName()}}</a> |
                        <a ng-href="{{module.getPermalink()}}" target="_blank"><?php esc_html_e( 'View',
						                                                                         'totalcontest' ); ?></a>
                    </small>
                </td>
            </tr>
            <tr ng-repeat-end class="plugin-update-tr" ng-if="$ctrl.getError(module)"
                ng-click="$ctrl.dismissError(module)">
                <td colspan="3" class="plugin-update colspanchange">
                    <div class="update-message notice inline notice-error notice-alt">
                        <p>{{$ctrl.getError(module)}}</p>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</script>
