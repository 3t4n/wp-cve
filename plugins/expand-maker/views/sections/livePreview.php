<div class="panel panel-default">
	<div class="panel-heading">
		<?php _e('Live preview', YRM_LANG); ?>
		<span class="yrm-tab-triangle glyphicon glyphicon-triangle-top"></span>
	</div>
	<div class="panel-body">
        <div class="row form-group">
            <div class="col-md-12">
                <?php require_once(YRM_VIEWS."livePreview/buttonPreview.php");?>
            </div>
        </div>
	</div>
</div>
<?php
require_once dirname(__FILE__).'/info.php';
?>
<?php $typeObj->includeOptionsBlock($dataObj); ?>