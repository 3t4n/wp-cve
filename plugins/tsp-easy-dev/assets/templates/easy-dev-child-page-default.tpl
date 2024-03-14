<div id="tsp_wrapper">
    <div id="tsp_header" class="row">
        <div id="tsp_logo" class="col-sm-4"></div>
        <div id="tsp_links" class="col-sm-8">
        	<h4>{$plugin_title}</h4>
        	<span>{$plugin_links}</span>
        </div>
    </div> <!-- tsp_title -->
    <div id="tsp_content" class="row">
        <div id="tsp_tabs_list">
          <ul>
            <li><a href="#tabs-1"><i class="fa fa-cogs"></i>Shortcode Defaults</a></li>
            <li><a href="#tabs-2"><i class="fa fa-code"></i>Shortcode Instructions</a></li>
          </ul>
        </div> <!-- tsp_tabs_list -->
        <div id="tsp_tabs_content">
            <div id="tsp_tabs_inner">
                <div id="tsp_tabs_controller">
                    <div id="tabs-1">
                     	<form method="post" action="admin.php?page={$plugin_name}.php" class="form-horizontal">
	                        <div id="tsp_top_bar" class="row">
						        <div id="tsp_bar_title" class="col-sm-8"><h2>Shortcode Defaults</h2></div>
						        <div id="tsp_bar_button" class="col-sm-4">
									  <div class="form-group">
										<button type="submit" class="btn btn-primary">Save Changes</button>
									  </div>
						        </div>
	                        </div>                       
	                    	<div role="alert" class="alert alert-success" {if !$form || $error != ""}style="display:none;"{/if}><p><strong>{$message}</strong></p></div>
	                    	<div role="alert" class="alert alert-danger" {if !$error}style="display:none;"{/if}><p><strong>{$error}</strong></p></div>
	                    	<fieldset>
	                    		{foreach $form_fields as $field}
	                    			{include file="$EASY_DEV_FORM_FIELDS" field=$field}
	                    		{/foreach}
                    		</fieldset>
                    		<input type="hidden" name="{$plugin_name}_form_submit" value="submit" />
	                        <div id="tsp_bottom_bar" class="row">
						        <div id="tsp_bar_title" class="col-sm-8"></div>
						        <div id="tsp_bar_button" class="col-sm-4">
									  <div class="form-group">
										<button type="submit" class="btn btn-primary">Save Changes</button>
									  </div>
						        </div>
	                        </div>                       
                    		{$nonce_name}
                    	</form>
                    </div> <!-- tabs-1 -->
                    <div id="tabs-2">
                        <h2>Shortcode Instructions</h2>
        				{include file="$EASY_DEV_SETTINGS_UI"}
                    </div> <!-- tabs-2 -->
               </div> <!-- tsp_tabs_controller -->
            </div> <!-- tsp_tabs_inner -->
        </div> <!-- tsp_tabs_content -->
    </div> <!-- tsp_content -->
</div> <!-- tsp_wrapper -->
