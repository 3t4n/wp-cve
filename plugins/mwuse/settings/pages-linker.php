<?php

function mtw_register_page_linker(){
	wp_enqueue_script(' jquery ' );
	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_enqueue_script( 'jquery-ui-dialog ' );
 	wp_enqueue_script( 'jquery-form' );
	 	
	wp_enqueue_style( 'ui-tabs', plugins_url( 'inc/jquery-ui.css', dirname(__FILE__) ) );
}


function mtw_delete_project()
{
	function ttr_link_delete_path($path)
    {
        if (is_dir($path) === true)
        {
            $files = array_diff(scandir($path), array('.', '..'));

            foreach ($files as $file)
            {
                ttr_link_delete_path(realpath($path) . '/' . $file);
            }

            return rmdir($path);
        }

        else if (is_file($path) === true)
        {
            return unlink($path);
        }

        return false;
    }
	if( current_user_can( 'administrator' ) && sanitize_text_field( $_POST['project_dir'] ) )
	{
		ttr_link_delete_path( sanitize_text_field( $_POST['project_dir'] ) );
		die();
	}
	else
	{
		die( 'mtw_delete_project_error' );
	}
}
add_action( 'wp_ajax_mtw_delete_project' , 'mtw_delete_project' );


function ttr_page_linker(){
	
	global $html;

	echo '<h2>'.__('Your Projects', 'mwuse' ).'</h2>';

	?>

	<script type="text/javascript">

	jQuery(document).ready(function($) {
		$('.delete-project').on('click', function(event) {
			event.preventDefault();
			if ( confirm("<?php echo __('Are you sure to delete this project', 'mwuse') ?>") == true ) 
			{
			    jQuery.post(
			        ajaxurl, 
			        {
			            'action': 'mtw_delete_project',
			            'project_dir':   $(this).data('dir')
			        }, 
			        function(response){
			            location.reload();
			        }
			    );
			} 
		});
	});
	

	</script>

	<style type="text/css">
	#ttr-muse-to-wordpress-tabs .ui-state-default 
	{
		padding-right: 30px;
	}

	.bt-delete-project
	{
		padding: 7px;
		background: none !important;
	}

	</style>

	
	<div>

	<script>
		

		jQuery(document).ready(function($) {
			
			$.post(
			    ajaxurl, 
			    {
			        'action': 'mtw_get_current_tab',
			        'data':   { id : "ttr-muse-to-wordpress-tabs" }
			    }, 
			    function(response){
			    	try {
				    	var mtw_get_current_tab = jQuery.parseJSON( response );
				        var tab = mtw_get_current_tab.current_tab;

				        $( "#ttr-muse-to-wordpress-tabs" ).tabs({
						  active: tab
						});
					}
					catch (e) {
						$( "#ttr-muse-to-wordpress-tabs" ).tabs();
					}
			    	
			    }
			);

    		$( "#ttr-muse-to-wordpress-tabs" ).on("tabsactivate", function( event, ui ) {
				  	$.post(
					    ajaxurl, 
					    {
					        'action': 'mtw_update_current_tab',
					        'data':   { id : $(this).attr('id'), count: ui.newTab.data('count') }
					    }, 
					    function(response){
					    	/*console.log("reponse mtw_update_current_tab")
					        console.log( jQuery.parseJSON( response ) );*/
					    }
					);
			})
  		});
	</script>
	<?php 
		$ttrProjects = ttr_get_muse_projects();
	?>
	<?php
		$countTab = 0;
	?>
	<div id="ttr-muse-to-wordpress-tabs">
	  	<ul>
	  		<?php foreach ($ttrProjects as $projectKey => $project) { ?>
	  		<li data-count="<?php echo $countTab; ?>"><a href="#<?php echo $projectKey; ?>"><?php echo $projectKey; ?><span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'mwuse' ) ?></span></a></li>
	  		<?php $countTab++; ?>
	  		<?php } ?>
	  	</ul>



	<?php
	//project tabs
	foreach ($ttrProjects as $projectKey => $project) {
		?>
		<div id="<?php echo $projectKey; ?>">
			<a href="#" class="delete-project" data-dir="<?php echo TTR_MW_TEMPLATES_PATH . $projectKey ?>" ><?php _e( 'Delete this project' , 'mwuse' ) ?></a><br/><br/>
			<?php

			$links = array();
			

			$projectFolder = $projectKey;
			$MuseProject = new MuseProject;
			$MuseProject->init($projectFolder);
		
			echo $MuseProject->table_project();
			
			?>
		</div><!-- end tab -->
	<?php
	}//end foreach project list
	?>
	</div><!-- end tabs -->

	<?php
}



?>