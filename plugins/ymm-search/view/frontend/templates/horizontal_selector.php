<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="widget ymm-selector ymm-horizontal" id="ymm_<?php echo $this->getWidgetId(); ?>">
  <?php if ($this->getTitle()): ?>
    <div class="ymm-title">
      <span class="widget-title"><?php echo $this->getTitle(); ?></span>
    </div>                		              
  <?php endif; ?>   
  <div class="ymm-title-right">
    <?php if ($this->getGarageEnabled()): ?>
      <span class="ymm-garage" <?php echo $this->getGarageHasVehicles() ? '' : 'style="display:none"';?>>          
        <span class="ymm-garage-text"><?php echo $this->getFilterCategoryPage() && is_product_category() ? __('From your garage for this category', 'ymm-search') : __('Your Garage', 'ymm-search'); ?>:</span>&nbsp;
        <select name="ymm_garage_select" class="ymm-garage-select">
          <option value=""><?php echo __('-- select vehicle --', 'ymm-search'); ?></option>      
        <?php foreach($this->getGarageVehicles() as $item): ?>      
          <option value="<?php echo htmlspecialchars($item['value']); ?>" <?php echo $item['value'] == $this->getGarageVehicle() ? 'selected="selected"' : ''; ?>><?php echo htmlspecialchars($item['title']); ?></option>
        <?php endforeach; ?>        
        </select>
        <?php if ($this->getRemoveFromGarageEnabled()): ?>      
          &nbsp;<a href="#" class="ymm-remove-from-garage"><?php echo __('Remove from garage', 'ymm-search') ?></a>
        <?php endif; ?>
      </span>              
    <?php endif; ?>             
  </div>                  		          
	<div class="ymm-clear">&nbsp;</div> 	  
  <div class="block-content">  
    <?php foreach($this->getLevels() as $levelData): ?>
      <div class="level">    
      <?php echo $this->getLevelSelectHtml($levelData); ?>
      </div>      
    <?php endforeach; ?> 
    <div class="ymm-clear">&nbsp;</div>   				      
    <?php if ($this->getCategorySearchEnabled() || $this->getWordSearchEnabled()): ?>      	
      <div class="ymm-extra" <?php if (!$this->isResultsPage()): ?> style="display:none" <?php endif; ?> >
      
        <?php if ($this->getCategorySearchEnabled()): ?>            
          <div class="ymm-category-container">
		        <div class="ymm-clear">&nbsp;</div>                              
          </div>
        <?php endif; ?>	

        <?php if ($this->getWordSearchEnabled()): ?> 		          	           
          <div class="ymm-search">
          <?php if ($this->getCategorySearchEnabled()): ?> 
            <div class="ymm-or-search">
            <?php echo __('Or search', 'ymm-search'); ?>:
            </div>            		          
          <?php endif; ?>          
            <form action="#">
               <table width="100%">
                 <tr>
                  <td class="ymm-td-input">                  
                    <input class="input-text ymm-search-field" type="text" name="s" value="<?php echo isset($_GET['s']) ? htmlspecialchars(stripslashes($_GET['s'])) : ''; ?>"/>
                  </td>                        
                  <td class="ymm-td-button">                       
                    <button type="submit" title="<?php echo __('Search', 'ymm-search') ?>" class="button"><span><span><?php echo __('Search', 'ymm-search') ?></span></span></button>		      		            	          		                                                                       
                  </td>                        
                 </tr>                       
               </table> 
            </form>                   
          </div>
        <?php endif; ?>
      </div>          
    <?php endif; ?>  
    <button type="button" title="<?php echo $this->getFilterCategoryPage() && is_product_category() ? __('Filter', 'ymm-search') : __('Search', 'ymm-search') ?>" class="button ymm-submit-any-selection" <?php echo $this->isResultsPage() && $this->getWordSearchEnabled() ? 'style="display:none;"' : ''; ?>><?php echo $this->getFilterCategoryPage() && is_product_category() ? __('Filter', 'ymm-search') : __('Search', 'ymm-search') ?></button>    
    <?php if ($this->getFilterCategoryPage() && is_product_category()): ?>
     <span class="ymm-filter-links">
     &nbsp;&nbsp;<a href="#" class="ymm-search-all-link"><?php echo __('Search All', 'ymm-search') ?></a>    
     <?php if ($this->getFilterIsActive()): ?>
      &nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH); ?>" class="ymm-clear-filter"><?php echo __('Clear filter', 'ymm-search') ?></a> 
     <?php endif; ?>
     </span>  
    <?php endif; ?> 
  </div>  
</div>
<script>
  jQuery(function($){
    $('#ymm_<?php echo $this->getWidgetId(); ?>').ymm({
      ajaxUrl                  : "<?php echo $this->getAjaxUrl(); ?>",
      ajaxShortUrl             : "<?php echo $this->getAjaxShortUrl(); ?>",      
      submitUrl                : "<?php echo $this->getSubmitUrl(); ?>",             
      levelParameterNames      : <?php echo $this->getLevelParameterNames(); ?>,
      isCategoryPage           : <?php echo is_product_category() ? 1 : 0; ?>,
      filterCategoryPage       : <?php echo $this->getFilterCategoryPage() ? 1 : 0; ?>,      
      categorySearchEnabled    : <?php echo $this->getCategorySearchEnabled() ? 1 : 0; ?>,
      wordSearchEnabled        : <?php echo $this->getWordSearchEnabled() ? 1 : 0; ?>,
      garageEnabled            : <?php echo $this->getGarageEnabled() ? 1 : 0; ?>,      
      canShowExtra             : <?php echo $this->canShowExtra() ? 1 : 0; ?>,
      categoryId               : <?php echo $this->getCategoryId(); ?>,      
      categoryDefOptionTitle   : "<?php echo $this->getCategoryDefaultOptionTitle(); ?>",
      ymmCookieName            : "ymm_selected",
      isHorizontal             : true
  <?php if ($this->getFilterCategoryPage() && is_product_category()): ?>   
      , submitSearchUrl        : "<?php echo $this->getSubmitSearchUrl(); ?>",           
      searchTitle              : "<?php echo $this->getSearchTitle(); ?>", 
      garageText               : "<?php echo __('Your Garage', 'ymm-search'); ?>",
      searchButtonText         : "<?php echo __('Search', 'ymm-search') ?>", 
      firstLevelOptions        : <?php echo $this->getFirstLevelOptionsJson(); ?>
  <?php endif; ?>                           
    });
  });
</script>