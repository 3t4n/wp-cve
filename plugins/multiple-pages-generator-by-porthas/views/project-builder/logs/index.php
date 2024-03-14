<div class="tab-pane main-tabpane" id="logs" role="tabpanel" aria-labelledby="logs-tab">
     <div class='main-inner-content shadowed'>

         <div class="logs-page">
             <div class="logs-tab-top">
                 <h2><?php _e('Logs', 'mpg'); ?></h2>
                 <button id="mpg_clear_log_by_project_id" class="btn btn-danger"><?php _e("Clear project's log", 'mpg');?></button>
             </div>

             
             <table id="mpg_logs_table" class="display"></table>

         </div>
     </div>
     <div class="sidebar-container">
         <?php require_once('sidebar.php') ?>
     </div>
 </div>