<div class="totalcontest-tabs-container">
    <div class="totalcontest-tabs-content">
        <div class="totalcontest-tab-content active">
            <div ng-controller="ContestPagesCtrl as pagesCtrl">
                <div class="totalcontest-containable totalcontest-content-pages">
                    <div class="totalcontest-containable-list-container">
                        <div class="totalcontest-containable-list">
                            <!-- Home -->
                            <div class="totalcontest-containable-list-item" ng-class="{active: !pagesCtrl.isCollapsed('landing')}">
                                <div class="totalcontest-containable-list-item-toolbar" ng-click="pagesCtrl.toggle('landing', $event)">
                                    <div class="totalcontest-containable-list-item-toolbar-collapse" ng-class="{active: !pagesCtrl.isCollapsed('landing')}">
                                        <span class="totalcontest-containable-list-item-toolbar-collapse-text">1</span>
                                        <span class="dashicons dashicons-arrow-up" ng-if="!pagesCtrl.isCollapsed('landing')"></span>
                                        <span class="dashicons dashicons-arrow-down" ng-if="pagesCtrl.isCollapsed('landing')"></span>
                                    </div>
                                    <div class="totalcontest-containable-list-item-toolbar-preview">
                                        <span class="totalcontest-containable-list-item-toolbar-preview-text"><?php  esc_html_e( 'Home', 'totalcontest' ); ?></span>
                                    </div>

                                    <button type="button" class="button button-primary button-small" ng-if="editor.settings.pages.default == 'landing'"><?php  esc_html_e( 'Default', 'totalcontest' ); ?></button>
                                    <button type="button" class="button button-default button-small" ng-if="editor.settings.pages.default != 'landing'" ng-click="editor.settings.pages.default = 'landing'; $event.stopPropagation()"><?php  esc_html_e( 'Set as default', 'totalcontest' ); ?></button>
                                </div>
                                <div class="totalcontest-containable-list-item-editor" ng-class="{active: !pagesCtrl.isCollapsed('landing')}">
                                    <div class="totalcontest-settings-item">
                                        <div class="totalcontest-settings-field">
                                            <label class="totalcontest-settings-field-label" for="page-landing-title"><?php  esc_html_e( 'Title', 'totalcontest' ); ?></label>
                                            <input id="page-landing-title" class="widefat text-field" type="text" placeholder="<?php esc_attr_e( 'Home', 'totalcontest' ); ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="totalcontest-settings-item">
                                        <div class="totalcontest-settings-field">
                                            <label class="totalcontest-settings-field-label">
												<?php  esc_html_e( 'Content', 'totalcontest' ); ?>
                                            </label>
                                            <progressive-textarea ng-model="editor.settings.pages.landing.content" uid="landing"></progressive-textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Participate -->
                            <div class="totalcontest-containable-list-item" ng-class="{active: !pagesCtrl.isCollapsed('participate')}">
                                <div class="totalcontest-containable-list-item-toolbar" ng-click="pagesCtrl.toggle('participate', $event)">
                                    <div class="totalcontest-containable-list-item-toolbar-collapse" ng-class="{active: !pagesCtrl.isCollapsed('participate')}">
                                        <span class="totalcontest-containable-list-item-toolbar-collapse-text">1</span>
                                        <span class="dashicons dashicons-arrow-up" ng-if="!pagesCtrl.isCollapsed('participate')"></span>
                                        <span class="dashicons dashicons-arrow-down" ng-if="pagesCtrl.isCollapsed('participate')"></span>
                                    </div>
                                    <div class="totalcontest-containable-list-item-toolbar-preview">
                                        <span class="totalcontest-containable-list-item-toolbar-preview-text"><?php  esc_html_e( 'Participate', 'totalcontest' ); ?></span>
                                    </div>
                                    <button type="button" class="button button-primary button-small" ng-if="editor.settings.pages.default == 'participate'"><?php  esc_html_e( 'Default', 'totalcontest' ); ?></button>
                                    <button type="button" class="button button-default button-small" ng-if="editor.settings.pages.default != 'participate'" ng-click="editor.settings.pages.default = 'participate'; $event.stopPropagation()"><?php  esc_html_e( 'Set as default', 'totalcontest' ); ?></button>
                                </div>
                                <div class="totalcontest-containable-list-item-editor" ng-class="{active: !pagesCtrl.isCollapsed('participate')}">
                                    <div class="totalcontest-settings-item">
                                        <div class="totalcontest-settings-field">
                                            <label class="totalcontest-settings-field-label" for="page-participate-title"><?php  esc_html_e( 'Title', 'totalcontest' ); ?></label>
                                            <input id="page-participate-title" class="widefat text-field" type="text" placeholder="<?php esc_attr_e( 'Participate', 'totalcontest' ); ?>" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Submissions -->
                            <div class="totalcontest-containable-list-item" ng-class="{active: !pagesCtrl.isCollapsed('submissions')}">
                                <div class="totalcontest-containable-list-item-toolbar" ng-click="pagesCtrl.toggle('submissions', $event)">
                                    <div class="totalcontest-containable-list-item-toolbar-collapse" ng-class="{active: !pagesCtrl.isCollapsed('submissions')}">
                                        <span class="totalcontest-containable-list-item-toolbar-collapse-text">1</span>
                                        <span class="dashicons dashicons-arrow-up" ng-if="!pagesCtrl.isCollapsed('submissions')"></span>
                                        <span class="dashicons dashicons-arrow-down" ng-if="pagesCtrl.isCollapsed('submissions')"></span>
                                    </div>
                                    <div class="totalcontest-containable-list-item-toolbar-preview">
                                        <span class="totalcontest-containable-list-item-toolbar-preview-text"><?php  esc_html_e( 'Submissions', 'totalcontest' ); ?></span>
                                    </div>
                                    <button type="button" class="button button-primary button-small" ng-if="editor.settings.pages.default == 'submissions'"><?php  esc_html_e( 'Default', 'totalcontest' ); ?></button>
                                    <button type="button" class="button button-default button-small" ng-if="editor.settings.pages.default != 'submissions'" ng-click="editor.settings.pages.default = 'submissions'; $event.stopPropagation()"><?php  esc_html_e( 'Set as default', 'totalcontest' ); ?></button>
                                </div>
                                <div class="totalcontest-containable-list-item-editor" ng-class="{active: !pagesCtrl.isCollapsed('submissions')}">
                                    <div class="totalcontest-settings-item">
                                        <div class="totalcontest-settings-field">
                                            <label class="totalcontest-settings-field-label" for="page-submissions-title"><?php  esc_html_e( 'Title', 'totalcontest' ); ?></label>
                                            <input id="page-submissions-title" class="widefat text-field" type="text" placeholder="<?php esc_attr_e( 'Submissions', 'totalcontest' ); ?>" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Thank you -->
                            <div class="totalcontest-containable-list-item" ng-class="{active: !pagesCtrl.isCollapsed('thankyou')}">
                                <div class="totalcontest-containable-list-item-toolbar" ng-click="pagesCtrl.toggle('thankyou', $event)">
                                    <div class="totalcontest-containable-list-item-toolbar-collapse" ng-class="{active: !pagesCtrl.isCollapsed('thankyou')}">
                                        <span class="totalcontest-containable-list-item-toolbar-collapse-text">1</span>
                                        <span class="dashicons dashicons-arrow-up" ng-if="!pagesCtrl.isCollapsed('thankyou')"></span>
                                        <span class="dashicons dashicons-arrow-down" ng-if="pagesCtrl.isCollapsed('thankyou')"></span>
                                    </div>
                                    <div class="totalcontest-containable-list-item-toolbar-preview">
                                        <span class="totalcontest-containable-list-item-toolbar-preview-text"><?php  esc_html_e( 'Thank you', 'totalcontest' ); ?></span>
                                    </div>
                                </div>
                                <div class="totalcontest-containable-list-item-editor" ng-class="{active: !pagesCtrl.isCollapsed('thankyou')}">
                                    <div class="totalcontest-settings-item">
                                        <div class="totalcontest-settings-field">
                                            <label class="totalcontest-settings-field-label">
												<?php  esc_html_e( 'After submission', 'totalcontest' ); ?>
                                            </label>
                                            <progressive-textarea ng-model="editor.settings.pages.thankyou.submission.content" uid="after-submission"></progressive-textarea>
                                        </div>
                                    </div>
                                    <div class="totalcontest-settings-item">
                                        <div class="totalcontest-settings-field">
                                            <label class="totalcontest-settings-field-label">
												<?php  esc_html_e( 'After voting', 'totalcontest' ); ?>
                                            </label>
                                            <progressive-textarea ng-model="editor.settings.pages.thankyou.voting.content" uid="after-voting"></progressive-textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Other -->
                            <div class="totalcontest-containable-list-item" ng-repeat="page in editor.settings.pages.other" ng-class="{active: !pagesCtrl.isCollapsed('other-' +$index)}">
                                <div class="totalcontest-containable-list-item-toolbar totalcontest-containable-handle ui-sortable-handle" ng-click="pagesCtrl.toggle('other-' + $index, $event)">
                                    <div class="totalcontest-containable-list-item-toolbar-collapse" ng-click="pagesCtrl.toggle('other-' + $index, $event)">
                                        <span class="totalcontest-containable-list-item-toolbar-collapse-text">{{ $index + 1 }}</span>
                                        <span class="dashicons dashicons-arrow-up" ng-if="!pagesCtrl.isCollapsed('other-' + $index)"></span>
                                        <span class="dashicons dashicons-arrow-down" ng-if="pagesCtrl.isCollapsed('other-' + $index)"></span>
                                    </div>
                                    <div class="totalcontest-containable-list-item-toolbar-preview">
                                        <span class="totalcontest-containable-list-item-toolbar-preview-text">{{page.title}}</span>
                                        <bdi class="totalcontest-containable-list-item-toolbar-preview-type"><span><?php  esc_html_e( 'Custom', 'totalcontest' ); ?></span></bdi>
                                    </div>
                                    <div class="totalcontest-containable-list-item-toolbar-delete">
                                        <button class="button button-danger button-small" type="button" ng-click="pagesCtrl.deletePage($index, true, $event)"><?php  esc_html_e( 'Delete', 'totalcontest' ); ?></button>
                                    </div>
                                </div>
                                <div class="totalcontest-containable-list-item-editor" ng-class="{active: !pagesCtrl.isCollapsed('other-' + $index)}">
                                    <div class="totalcontest-settings-item">
                                        <div class="totalcontest-settings-field">
                                            <label class="totalcontest-settings-field-label" for="page-{{$index}}-title"><?php  esc_html_e( 'Title', 'totalcontest' ); ?></label>
                                            <input id="page-{{$index}}-title" class="widefat text-field" type="text" placeholder="<?php esc_attr_e( 'Page title', 'totalcontest' ); ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="totalcontest-settings-item">
                                        <div class="totalcontest-settings-field">
                                            <label class="totalcontest-settings-field-label" for="page-{{$index}}-content"><?php  esc_html_e( 'Content', 'totalcontest' ); ?></label>
                                            <progressive-textarea ng-model="page.content" uid="page-{{$index}}"></progressive-textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="totalcontest-buttons-horizontal">
                        <div class="totalcontest-buttons-horizontal-item" ng-click="pagesCtrl.insertPage({id: 'terms', title: 'Terms'})">
                            <div class="dashicons dashicons-clipboard"></div>
                            <div class="totalcontest-buttons-horizontal-item-title"><?php  esc_html_e( 'Terms', 'totalcontest' ); ?></div>
                        </div>
                        <div class="totalcontest-buttons-horizontal-item" ng-click="pagesCtrl.insertPage({id: 'prizes', title: 'Prizes'})">
                            <div class="dashicons dashicons-awards"></div>
                            <div class="totalcontest-buttons-horizontal-item-title"><?php  esc_html_e( 'Prizes', 'totalcontest' ); ?></div>
                        </div>
                        <div class="totalcontest-buttons-horizontal-item" ng-click="pagesCtrl.insertPage({id: 'untitled', title: 'Untitled'})">
                            <div class="dashicons dashicons-welcome-add-page"></div>
                            <div class="totalcontest-buttons-horizontal-item-title"><?php  esc_html_e( 'Blank', 'totalcontest' ); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
