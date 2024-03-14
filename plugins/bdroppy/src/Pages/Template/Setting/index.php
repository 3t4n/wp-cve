<?php
$system = $this->core->getSystem() ;
?>
<div class="bdroppy_base" >
    <div class="container">
        <?php require __DIR__ . "/../adminBase.php" ?>
        <div class="bd-content">

            <div class="setting">
                <form id="settingForm" class="tabs">
                    <h2 class="title">Dev Setting</h2>
                    <div class="form-group">
                        <span for="check-publish-product" class="col-sm-4 label">Orders error notification</span>
                        <div class="col-sm">
                            <textarea name="order_error_notification" style="width: 100%;height: 150px;"><?= $this->config->setting->get('order_error_notification') ?></textarea>
                            <p  class="discription">Enter recipients (comma separated) email to receive orders error notifications</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <span for="check-logger" class="col-sm-4 label">Debug mode</span>
                        <div class="col-sm">
                            <label class=" <?= $this->config->setting->get('logger')? "checked":'' ?>" for="check-logger">
                                <input type="checkbox" <?= $this->config->setting->get('logger')? "checked":'' ?> name="logger" id="check-logger">
                                Log Error Messages
                            </label>
                            <p class="discription">when enabled, Bdroppy activities logs will be saved to wp-content/uploads/bdroppy/logs</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-6 label ">Cpu Load Average Limitation</label>
                        <div class="col-sm">
                            <input type="number" style="width: 100%;margin-bottom: 5px" name="cpu-load-average-limitation" id="cpu-load-average-limitation" value="<?= $this->config->setting->get('cpu-load-average-limitation',0) ?>" />
                            <p class="discription">0 is disable</p>
                        </div>
                    </div>

                    <div class="form-group col-sm-4">
                        <button type="button" class=" btn btn-primary btn-change-setting"  >Save</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>