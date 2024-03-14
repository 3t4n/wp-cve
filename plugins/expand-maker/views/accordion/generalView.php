<input type="hidden" name="yrm-type" value="accordion">
<input type="hidden" name="yrm-post-id" value="<?php echo esc_attr($this->getId()); ?>">
<div class="row form-group" style="margin-top: 15px;">
    <div class="col-md-9">
        <div class="col-md-12">
            <?php require_once(dirname(__FILE__).'/generalSettings.php'); ?>
        </div>
        <div class="col-md-12">
            <?php require_once(dirname(__FILE__).'/settings.php'); ?>
        </div>
        <div class="col-md-12">
            <?php require_once(dirname(__FILE__).'/advancedOptions.php'); ?>
        </div>
        <div class="col-md-12">
            <?php require_once(dirname(__FILE__).'/customFunctionality.php'); ?>
        </div>
    </div>
    <div class="col-md-3">
        <?php require_once(dirname(__FILE__)."/AccordionUpgreade.php")?>
    </div>
</div>
