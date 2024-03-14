<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

use \MaxButtons\maxField as maxField;
use \MaxButtons\maxBlocks as maxBlocks;
use \MaxButtons\maxButton as maxButton;

$collectionBlock["preview"] = array('class' => "previewBlock",
								  'order' => 20);

class previewBlock extends block
{

  protected $blockname = "preview";

  public function admin()
  {
    $admin = MBSocial()->admin();
    ?>
    <div class='options option-container style' id='previewBlock'  >
			<div class='title'><?php _e('Preview', 'mbsocial'); ?> 		<button type="button" class="button-primary button-save mb-ajax-submit" data-action='save_collection'><?php _e('Save', 'maxbuttons') ?></button>

			</div>
			<div class='inside'>


    <?php
      $args = array('preview' => true,
              'load_type' => 'inline',
              'compile' => true,
             );
     $style = $this->collection->getStyle();  // set by the set data set of this block
     $style_name = $style->class;

     ?>
       <div  id='style_preview'>
         <?php
           //$this->preview_style = $style->class;
           $this->collection->display($args);
         ?>
       </div>

		 </div>
	 </div> <!-- container -->
<?php
    }

} // class
