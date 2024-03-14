<?php 
defined('ABSPATH') or die("No direct script access!");

if(!function_exists('ilist_modal_fa')){
function ilist_modal_fa() {
	
	$data = file(plugin_dir_path( __FILE__ ).'assets/data/fa-data.txt');//file in to an array
	
	//$data = file_get_contents( QCOPD_ASSETS_URL1 . '/data/fa-data.txt' );
	
	//$data = explode("\n",$data);
	$icons = array();
	foreach($data as $key=>$val){
		$val = explode('=>',$val);
		$title = $val[0];
		$class = explode(',',$val[1]);
		foreach($class as $v=>$k){
			if(strlen($k)>2){
				$icons[$title][] = trim($k);
			}
		}
	}
        
?>
<div class="fa-field-modal" id="fa-field-modal" style="display:none">
  <div class="fa-field-modal-close">&times;</div>
  <h1 class="fa-field-modal-title"><?php esc_html_e( 'Select Font Awesome Icon', 'iList' ); ?> (<span style="color:red"><?php esc_html_e( 'Pro Version only', 'iList' ); ?></span>)</h1>

  <div class="fa-field-modal-icons">
		<form action="#">
			<fieldset>
				<input type="search" name="search" value="" id="id_search" /> <span class="loading"><?php echo esc_html( 'Loading...' , 'iList' ); ?></span>
			</fieldset>
		</form>
	<?php if ( $icons ) : ?>

	  <?php foreach ( $icons as $head=>$iconlist ) : ?>
		<div class="qcld_ilist_fa_section" style="display:block;overflow: hidden;"><h2><?php echo esc_html($head, 'iList'); ?></h2>
		<?php foreach ( $iconlist as $s=>$cls ) : ?>
		<a href="<?php echo esc_url( 'https://www.quantumcloud.com/products/infographic-maker-ilist/' , 'iList' ); ?>" target="_blank"><div class="fa-field-modal-icon-holder" data-icon="<?php echo esc_attr($cls); ?>">
		  <div class="icon">
			<i class="fa <?php echo esc_attr($cls); ?>"></i>
		  </div>
		  <div class="label">
			<?php echo esc_html($cls); ?>
		  </div>
		</div></a>

	  <?php endforeach; ?>
	  </div>
	  <?php endforeach; ?>

	<?php endif; ?>
  </div>
</div>

<?php
}
}
add_action( 'admin_footer', 'ilist_modal_fa');