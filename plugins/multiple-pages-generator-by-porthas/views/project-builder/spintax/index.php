 <div class="tab-pane main-tabpane" id="spintax" role="tabpanel" aria-labelledby="spintax-tab">
     <div class='main-inner-content shadowed'>

         <div class="spintax-page">
             <div class="spintax-tab-top">
                 <h2><?php _e('Spintax', 'mpg'); ?></h2>
             </div>

             <p class="mpg-subtitle"><?php _e('Enter Spintax conditions here, and click to Spin button to get results', 'mpg'); ?></p>

             <div class="textarea-block">
                 <textarea id="mpg_spintax_input_textarea">{a|the|one} {quick|fast} {brown|silver} {fox|dog|wolf}</textarea>
             </div>
             <div class="save-changes-block">
                 <div class="mpg-spin-btn">
                     <input type="button" id="mpg_spin" class="btn btn-primary" value="<?php _e('Spin!', 'mpg'); ?>" />
                    <span class="spinner"></span>
                 </div>

                 <input type="button" class="copy-spintax-output btn btn-outline-primary" value="<?php _e('Copy expression', 'mpg'); ?>" />
             </div>

             <p class="mpg-subtitle"><?php _e('This is example of results string, that will we shown instead of [mpg_spintax] shortcode', 'mpg'); ?></p>

             <div class="textarea-block">
                 <div style="height: auto;border: 1px solid #bbb;margin: 30px; border-radius: 5px; background: #f3f3f3; padding: 15px; width: 100%;" id="mpg_spintax_output_textarea"><?php _e('Click Spin button to see result', 'mpg'); ?></div>
             </div>


             <h4 class="subtitle" style="padding-left: 25px;"><?php _e('Cache', 'mpg'); ?></h4>


             <p style="padding-left: 25px; padding-right: 25px;"><?php _e('When users go to one of generated URL at first time, MPG generate and save in cache (i.e. database) some string, according to provided Spintax expression.', 'mpg'); ?></p>
             <p style="padding-left: 25px; padding-right: 25px;"><?php _e('Each time, when users visit the same URL again, Spintax string is retrieved from the cache, and will not be generated again until you clear cache.', 'mpg'); ?></p>

             <div class="cache-info">
                 <button class="btn btn-danger"><?php _e('Flush cache', 'mpg') ?></button>

                 <div class="cache-records">
                     <span> <?php _e('Records in cache for current project:', 'mpg'); ?> <span class="num-rows"></span></span>
                 </div>
             </div>
         </div>
     </div>
     <!--.col-md-6 -->
     <div class="sidebar-container">
         <?php require_once('sidebar.php') ?>
     </div>
 </div>