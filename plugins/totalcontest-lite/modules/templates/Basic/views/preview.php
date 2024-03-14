<div class="totalcontest-header">
    <div class="totalcontest-menu">
        <a class="totalcontest-menu-item" ng-class="{'totalcontest-menu-item-active': $preview.isScreen('home')}" ng-click="$preview.setScreen('home')" ng-if="$root.settings.pages.home.content.trim()">{{$root.settings.pages.home.title}}</a>
        <a class="totalcontest-menu-item" ng-class="{'totalcontest-menu-item-active': $preview.isScreen('participate')}" ng-click="$preview.setScreen('participate')">Participate</a>
        <a class="totalcontest-menu-item" ng-class="{'totalcontest-menu-item-active': $preview.isScreen('submission') || $preview.isScreen('submissions')}" ng-click="$preview.setScreen('submissions')">Submissions</a>
        <a class="totalcontest-menu-item" ng-repeat="page in $root.settings.pages.other" ng-class="{'totalcontest-menu-item-active': $preview.isScreen('page:' + page.id)}" ng-click="$preview.setScreen('page:' + page.id)">{{page.title}}</a>
    </div>
</div>
<div class="totalcontest-body">
    <div ng-if="$root.isCurrentTab('editor>design') && $preview.isScreen('home')" class="totalcontest-page-content" ng-bind-html="$preview.escape($root.settings.pages.home.content)"></div>
    <div ng-if="$root.isCurrentTab('editor>design') && $preview.isScreen('participate')" class="totalcontest-participate">
        <div class="totalcontest-form totalcontest-participate-form">
            <div class="totalcontest-form-custom-fields">
                <div class="totalcontest-form-page">
                    <div class="totalcontest-form-field {{$preview.getFieldClass(field)}} totalcontest-column-full"
                         ng-repeat="field in $root.settings.contest.form.fields">
                        <div class="totalcontest-form-field-wrapper" ng-include="'preview-form-field-type-' + $preview.getFieldType(field)"></div>
                    </div>
                </div>
            </div>
            <div class="totalcontest-buttons">
                <button type="button" ng-click="$preview.setScreen('submissions')" class="totalcontest-button totalcontest-button-primary totalcontest-button-submit">Submit</button>
            </div>
        </div>
    </div>
    <div ng-if="$root.isCurrentTab('editor>design') && $preview.isScreen('submissions')" class="totalcontest-submissions" ng-init="$preview.generateSubmissions(3)">

        <div class="totalcontest-submissions-toolbar">
            <div class="totalcontest-submissions-toolbar-items">

                <div class="totalcontest-submissions-toolbar-item totalcontest-submissions-toolbar-sort">
                    <span class="totalcontest-submissions-toolbar-title">Sort by</span>
                    <select class="totalcontest-submissions-toolbar-select">
                        <option selected="selected">Date
                        </option>
                        <option>Views
                        </option>
                        <option>Votes
                        </option>
                    </select>
                </div>

                <div class="totalcontest-submissions-toolbar-item totalcontest-submissions-toolbar-sort-direction">
                    <select class="totalcontest-submissions-toolbar-select">
                        <option>Ascending</option>
                        <option selected="selected">Descending</option>
                    </select>
                </div>
                <div class="totalcontest-submissions-toolbar-item totalcontest-submissions-toolbar-toggle" ng-class="{'totalcontest-submissions-toolbar-active': $preview.isLayout('grid')}" ng-click="$preview.setLayout('grid')">
                    <svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z"></path>
                        <path d="M0 0h24v24H0z" fill="none"></path>
                    </svg>
                </div>
                <div class="totalcontest-submissions-toolbar-item totalcontest-submissions-toolbar-toggle " ng-class="{'totalcontest-submissions-toolbar-active': $preview.isLayout('list')}" ng-click="$preview.setLayout('list')">
                    <svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="totalcontest-submissions-items" ng-class="{'totalcontest-submissions-items-layout-grid': $preview.isLayout('grid'), 'totalcontest-submissions-items-layout-list': $preview.isLayout('list')}">
            <div class="totalcontest-submissions-row">

                <div class="totalcontest-submissions-item" ng-repeat="submission in $preview.getSubmissions() track by $index" ng-style="{width: $preview.getSubmissionWidth()}">
                    <a ng-click="$preview.setScreen('submission')" class="totalcontest-submissions-item-link">
                        <div class="totalcontest-submissions-item-preview">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAADIBAMAAABfdrOtAAAAG1BMVEXMzMyWlpbFxcWqqqqcnJyjo6OxsbG3t7e+vr6pf3+GAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABTElEQVR4nO3UT07CQByG4Q8phSUtRV2WyAFK4gFaMyYuhRO0N6CJiVu4uTNTgw5h1XZh4vssfsnMl7TzXwIAAAAAAAAAAAAAAACA/+Pu+TWXPrLjd7l4MgfdDHqIsipVnFQPXflRmSS/GfTQFPFKm5dF1pUL29sUt4I+9rkytUftTr5IbaG2lmapJmUQDGDswrg/nWtf7Po9uj5FpaJlEAzwKTuTStoUvkiLdex2YH50M/kdDDJfuaFv3n2x7X1bdoFduCAYokntXOywfbHtc9Id2DjJw2CAaXYKBzxdd8HuTWPN5K46KFz6qDuv23WusfZktpI/x23ti220lavaF9dBf637VnAdzDaVPw7XQX92mAoudnwfu+/P3J/GuvGVMSZ4orb2duRuZ4wpx3q7Eit4bJvaL+HE9i/HeoUBAAAAAAAAAAAAAAAA/AFffMdIP+g1B4cAAAAASUVORK5CYII=">
                        </div>
                        <div class="totalcontest-submissions-item-details">
                            <div class="totalcontest-submissions-item-title">{{submission.title}}</div>
                            <div class="totalcontest-submissions-item-meta">
                                <div class="totalcontest-submissions-item-meta-content">{{submission.subtitle}}</div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>

        <div class="totalcontest-pagination">
            <span class="totalcontest-pagination-item totalcontest-pagination-item-disabled totalcontest-pagination-previous">Previous</span>
            <span class="totalcontest-pagination-item totalcontest-pagination-item-active">1</span>
            <a class="totalcontest-pagination-item" href="#">2</a>
            <a class="totalcontest-pagination-item totalcontest-pagination-next" href="#">Next</a>
        </div>

    </div>
    <div ng-if="$root.isCurrentTab('editor>design') && $preview.isScreen('submission')" class="totalcontest-submission is-full-width">
        <div class="totalcontest-submission-main">
            <div class="totalcontest-submission-content is-embed"><img src="//placehold.it/1920x1080"></div>
        </div>
        <div class="totalcontest-submission-sidebar">
            <div class="totalcontest-submission-stats">
                <div class="totalcontest-submission-stats-item" ng-if="$root.settings.vote.type === 'count'">
                    <div class="totalcontest-submission-stats-item-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                            <path d="M0 22h1v-5h4v5h2v-10h4v10h2v-15h4v15h2v-21h4v21h1v1h-24v-1zm4-4h-2v4h2v-4zm6-5h-2v9h2v-9zm6-5h-2v14h2v-14zm6-6h-2v20h2v-20z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="totalcontest-submission-stats-item-value">5</div>
                        <div class="totalcontest-submission-stats-item-title">Votes</div>
                    </div>
                </div>
                <div class="totalcontest-submission-stats-item">
                    <div class="totalcontest-submission-stats-item-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                            <path d="M12.01 20c-5.065 0-9.586-4.211-12.01-8.424 2.418-4.103 6.943-7.576 12.01-7.576 5.135 0 9.635 3.453 11.999 7.564-2.241 4.43-6.726 8.436-11.999 8.436zm-10.842-8.416c.843 1.331 5.018 7.416 10.842 7.416 6.305 0 10.112-6.103 10.851-7.405-.772-1.198-4.606-6.595-10.851-6.595-6.116 0-10.025 5.355-10.842 6.584zm10.832-4.584c2.76 0 5 2.24 5 5s-2.24 5-5 5-5-2.24-5-5 2.24-5 5-5zm0 1c2.208 0 4 1.792 4 4s-1.792 4-4 4-4-1.792-4-4 1.792-4 4-4z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="totalcontest-submission-stats-item-value">69</div>
                        <div class="totalcontest-submission-stats-item-title">Views</div>
                    </div>
                </div>
                <div class="totalcontest-submission-stats-item">
                    <div class="totalcontest-submission-stats-item-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                            <path d="M24 23h-24v-19h4v-3h4v3h8v-3h4v3h4v19zm-1-15h-22v14h22v-14zm-16.501 8.794l1.032-.128c.201.93.693 1.538 1.644 1.538.957 0 1.731-.686 1.731-1.634 0-.989-.849-1.789-2.373-1.415l.115-.843c.91.09 1.88-.348 1.88-1.298 0-.674-.528-1.224-1.376-1.224-.791 0-1.364.459-1.518 1.41l-1.032-.171c.258-1.319 1.227-2.029 2.527-2.029 1.411 0 2.459.893 2.459 2.035 0 .646-.363 1.245-1.158 1.586.993.213 1.57.914 1.57 1.928 0 1.46-1.294 2.451-2.831 2.451-1.531 0-2.537-.945-2.67-2.206zm9.501 2.206h-1.031v-6.265c-.519.461-1.354.947-1.969 1.159v-.929c1.316-.576 2.036-1.402 2.336-1.965h.664v8zm7-14h-22v2h22v-2zm-16-3h-2v2h2v-2zm12 0h-2v2h2v-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="totalcontest-submission-stats-item-value" title="March 7, 2019 8:53 pm">
                            20 Days
                        </div>
                        <div class="totalcontest-submission-stats-item-title">Since posted</div>
                    </div>
                </div>
            </div>

            <div class="totalcontest-submission-stats" ng-if="$root.settings.vote.type === 'rate'">
                <div class="totalcontest-submission-stats-item">
                    <div class="totalcontest-submission-stats-item-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                            <path d="M15.668 8.626l8.332 1.159-6.065 5.874 1.48 8.341-7.416-3.997-7.416 3.997 1.481-8.341-6.064-5.874 8.331-1.159 3.668-7.626 3.669 7.626zm-6.67.925l-6.818.948 4.963 4.807-1.212 6.825 6.068-3.271 6.069 3.271-1.212-6.826 4.964-4.806-6.819-.948-3.002-6.241-3.001 6.241z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="totalcontest-submission-stats-item-value">{{$root.settings.vote.scale}}</div>
                        <div class="totalcontest-submission-stats-item-title">Average rate</div>
                    </div>
                </div>
                <div class="totalcontest-submission-stats-item" ng-repeat="criterion in $root.settings.vote.criteria">
                    <div class="totalcontest-submission-stats-item-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                            <path d="M19 22h-19v-19h19v2h-1v-1h-17v17h17v-9.502h1v10.502zm5-19.315l-14.966 15.872-5.558-6.557.762-.648 4.833 5.707 14.201-15.059.728.685z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="totalcontest-submission-stats-item-value">{{$root.settings.vote.scale}}</div>
                        <div class="totalcontest-submission-stats-item-title">{{criterion.name}}</div>
                    </div>
                </div>
            </div>

            <div ng-if="$root.settings.vote.type === 'rate'" class="totalcontest-form totalcontest-form-rate">
                <div class="totalcontest-form-page">
                    <div class="totalcontest-form-field totalcontest-form-field-type-radio totalcontest-column-full" ng-repeat="criterion in ($root.settings.vote.criteria.length ? $root.settings.vote.criteria : [{name: 'Overall'}])">
                        <div class="totalcontest-form-field-wrapper"><label for="criterion-0-field" class="totalcontest-form-field-label">{{criterion.name}}</label>
                            <div class="totalcontest-form-field-radio">
                                <input type="radio" id="criterion-{{$index}}-radio-5" value="5" class="totalcontest-form-field-input option-5">
                                <label for="criterion-{{$index}}-radio-5" class="totalcontest-form-field-label">5</label>
                                <input type="radio" id="criterion-{{$index}}-radio-4" value="4" class="totalcontest-form-field-input option-4">
                                <label for="criterion-{{$index}}-radio-4" class="totalcontest-form-field-label">4</label>
                                <input type="radio" id="criterion-{{$index}}-radio-3" value="3" class="totalcontest-form-field-input option-3">
                                <label for="criterion-{{$index}}-radio-3" class="totalcontest-form-field-label">3</label>
                                <input type="radio" id="criterion-{{$index}}-radio-2" value="2" class="totalcontest-form-field-input option-2">
                                <label for="criterion-{{$index}}-radio-2" class="totalcontest-form-field-label">2</label>
                                <input type="radio" id="criterion-{{$index}}-radio-1" value="1" class="totalcontest-form-field-input option-1">
                                <label for="criterion-{{$index}}-radio-1" class="totalcontest-form-field-label">1</label>
                            </div>
                            <div class="totalcontest-form-field-errors"></div>
                        </div>
                    </div>

                </div>
                <button type="button" class="totalcontest-button totalcontest-button-primary totalcontest-button-rate">Rate</button>
            </div>

            <div ng-if="$root.settings.vote.type === 'count'" class="totalcontest-form totalcontest-form-vote" ng-if="$root.settings.vote.type === 'count'">
                <div class="totalcontest-form-page"></div>
                <button type="button" class="totalcontest-button totalcontest-button-primary totalcontest-button-vote">Vote</button>
            </div>
        </div>
    </div>
    <div ng-repeat="page in $root.settings.pages.other" ng-if="$root.isCurrentTab('editor>design') && $preview.isScreen('page:' + page.id)" class="totalcontest-page-content" ng-bind-html="$preview.escape(page.content)">

    </div>
</div>
<div class="totalcontest-footer">
</div>

<script type="text/ng-template" id="preview-form-field-type-file">
    <div class="totalcontest-form-field-placeholder-wrapper">
        <div class="totalcontest-form-field-placeholder">{{field.label}}</div>
        <label for="image-field" class="totalcontest-form-field-label"></label>
    </div>
    <div class="totalcontest-form-field-errors"></div>
</script>
<script type="text/ng-template" id="preview-form-field-type-text">
    <label for="text-{{field.uid}}" class="totalcontest-form-field-label">{{field.label}}</label>
    <input id="text-{{field.uid}}" placeholder="Placeholder" type="text" class="totalcontest-form-field-input">
    <div class="totalcontest-form-field-errors"></div>
</script>
<script type="text/ng-template" id="preview-form-field-type-select">
    <label for="select-{{field.uid}}" class="totalcontest-form-field-label">{{field.label}}</label>
    <select id="select-{{field.uid}}" placeholder="" class="totalcontest-form-field-input" ng-init="$preview.parseOptionsOf(field)">
        <option ng-repeat="option in $preview.getOptionsOf(field) track by $index">{{option.label}}</option>
    </select>
</script>
<script type="text/ng-template" id="preview-form-field-type-textarea">
    <label for="textarea-{{field.uid}}" class="totalcontest-form-field-label">{{field.label}}</label>
    <textarea id="textarea-{{field.uid}}" placeholder="" class="totalcontest-form-field-input"></textarea>
    <div class="totalcontest-form-field-errors"></div>
</script>
<script type="text/ng-template" id="preview-form-field-type-category">
    <label for="category-{{field.uid}}" class="totalcontest-form-field-label">{{field.label}}</label>
    <select id="category-{{field.uid}}" placeholder="" class="totalcontest-form-field-input">
        <option class="option-4">Option 1</option>
        <option class="option-5">Option 2</option>
        <option class="option-3">Option 3</option>
    </select>
</script>
<script type="text/ng-template" id="preview-form-field-type-checkbox">
    <div class="totalcontest-form-field-checkbox-item">
        <label for="checkbox-field" class="totalcontest-form-field-label">{{field.label}}</label>
        <div class="totalcontest-form-field-checkbox" ng-init="$preview.parseOptionsOf(field)">
            <input ng-repeat-start="option in $preview.getOptionsOf(field) track by $index" type="checkbox" id="option-{{$index}}-{{field.uid}}" class="totalcontest-form-field-input option-option-{{$index}}">
            <label ng-repeat-end for="option-{{$index}}-{{field.uid}}" class="totalcontest-form-field-label">{{option.label}}</label>
        </div>
        <div class="totalcontest-form-field-errors"></div>
    </div>
</script>
<script type="text/ng-template" id="preview-form-field-type-radio">
    <label for="radio-field" class="totalcontest-form-field-label">{{field.label}}</label>
    <div class="totalcontest-form-field-radio" ng-init="$preview.parseOptionsOf(field)">
        <input ng-repeat-start="option in $preview.getOptionsOf(field) track by $index" type="radio" id="option-{{$index}}-{{field.uid}}" name="option-{{field.uid}}" class="totalcontest-form-field-input option-option-{{$index}}">
        <label ng-repeat-end for="option-{{$index}}-{{field.uid}}" class="totalcontest-form-field-label">{{option.label}}</label>
    </div>
    <div class="totalcontest-form-field-errors"></div>
</script>