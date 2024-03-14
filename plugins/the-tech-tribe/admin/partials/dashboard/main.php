<div class="wrap">
    <div class="bootstrap-iso">
        <h1>The Tech Tribe</h1>
        <div class="alert alert-warning" role="alert">
            WARNING: The log file for this plugin contains your web servers IP Address. </br>
            By default, Wordpress stores all Plugin Log Files in the /uploads/ folder which could be publicly accessible depending on your webserver configuration. </br>
            If you don't want this log file to be publicly accessible, please check your webservers configuration and update your permissions if necessary.</br>
        </div>
        <div class="form form-dashboard-user col-md-8">
            <div class="dashboard-alert">
                
                <?php 
                    if($retUpdate) {
                        \TheTribalPlugin\ShowAlert::get_instance()->show($alertArgs); 
                    }
                ?>
            </div>

            <ul class="nav nav-tabs" id="tttUserDashboard" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="api-key-tab" data-bs-toggle="tab" data-bs-target="#api-key" type="button" role="tab" aria-controls="home" aria-selected="true">API Key</button>
                </li>
                <?php if(tttIsKeyActive()) : ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link " id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="home" aria-selected="true">Settings</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="import-tab" data-bs-toggle="tab" data-bs-target="#import" type="button" role="tab" aria-controls="contact" aria-selected="false">Import Status</button>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fadex show active" id="api-key" role="tabpanel" aria-labelledby="api-key-tab">
                    <div class="wrap">
                        <?php 
                            if ( is_file( $partTemplateApi ) ) {
                                require_once $partTemplateApi;
                            }
                        ?>
                    </div>
                </div>
                <?php if(tttIsKeyActive()) : ?>
                    <div class="tab-pane fadex " id="settings" role="tabpanel" aria-labelledby="settings-tab">
                        <div class="wrap">
                            <?php 
                                if ( is_file( $partTemplateSettings ) ) {
                                    require_once $partTemplateSettings;
                                }
                            ?>
                        </div>
                    </div>
                    <div class="tab-pane fadex" id="import" role="tabpanel" aria-labelledby="import-tab">
                        
                        <div class="wrap">
                             <?php 
                                if ( is_file( $partTemplateImport ) ) {
                                    require_once $partTemplateImport;
                                }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>