<?php if(!defined('ABSPATH')) die('no access'); ?>


<div class="wrap">

	<h2><?php _e('Kama WP Smiles Settings','kama-wp-smile') ?></h2>

	<form class="kwsform" method="post" action="">
		<?php wp_nonce_field('kwps_options_up', 'kwps_nonce'); ?>

		<div class="select_sm_wrap <?= $this->get_opt('sm_pack') ?>">

			<h3><?php _e('Selected (sort by drag and drop)','kama-wp-smile'); ?></h3>

			<div class="select_smiles"><?php $this->dir_smiles_img(); ?></div>

			<?php
			$smiles = [];
			foreach( (array) $this->get_opt( 'used_sm' ) as $smile ){
				$smiles[] = $smile;
			}
			?>
			<input type="hidden" name="used_sm" class="used_sm" value="<?= implode( ',', $smiles ) ?>">
		</div>

		<div class="kws-wrapper kws_ex_wrap">
			<script>
			let $wrap = document.querySelector( '.kws_ex_wrap' );
			$wrap.addEventListener( 'change', function( ev ){

				if( ev.target.name !== 'smlist_pos' )
					return;

				let $smiles = $wrap.querySelector( '.sm_list' );
				$smiles.classList.remove( 'topright' )
				$smiles.classList.remove( 'bottomright' )
				$smiles.classList.add( ev.target.value )
			} )
			</script>

			<div class="ex" style=""><?php _e('example','kama-wp-smile') ?></div>

			<textarea id="testtx" style="display:block; width:100%; height:70px; background:none;"></textarea>

			<input type="radio" name="smlist_pos" value="topright" <?php checked('topright', $this->get_opt('smlist_pos') ); ?>  style="top:1em;right:0.5em;"  />
			<input type="radio" name="smlist_pos" value="bottomright" <?php checked('bottomright', $this->get_opt('smlist_pos') ); ?>  style="bottom:.8em;right:0.5em;"  />
			<input type="radio" name="smlist_pos" value="" <?php checked('', $this->get_opt('smlist_pos') ); ?>  style="bottom:.8em; left:.8em;"  />

			<?= $this->get_all_smile_html('testtx'); ?>
		</div>


		<table class="kwstable">

			<tr>
				<td><input type="text" name="textarea_id" value="<?= $this->get_opt('textarea_id')?>" /></td>
				<td>
					<b><?php _e('Comment field ID', 'kama-wp-smile'); ?></b><br>
					<span class="desc">
						<?php printf( __('ID attribute of textarea tag. Leave the field empty, in order ask the plugin to NOT automatically insert smiles block. And use this code: %s in your comment form. This code print list of available smiles (see just after heading of this page).','kama-wp-smile'), '<code>&lt;?php echo kws_get_smiles_html( $textarea_id ) ?&gt;</code>' ) ; ?>
					<span>
				</td>
			</tr>

			<tr>
				<td><input type="text" name="spec_tags" value="<?= implode(',', (array) $this->get_opt('spec_tags'))?>" /></td>
				<td>
					<b><?php _e('HTML tags exceptions.', 'kama-wp-smile'); ?></b><br>
					<span class="desc"><?php _e('Specify HTML tags (comma separated) content of which the plugin must skip from process of finding smiles. Example:', 'kama-wp-smile'); ?> <code>code,pre</code>.</span>
				</td>
			</tr>

			<tr>
				<td>
					<?php
					echo '<textarea name="hard_sm" style="height:150px;">';
					foreach( (array) $this->get_opt('hard_sm') as $k => $v ) echo $k .' >>> '. $v ."\n";
					echo '</textarea>';
					?>
				</td>
				<td>
					<b><?php _e('Mark (name) for special smiles.', 'kama-wp-smile'); ?></b>
					<br>
					<span class="desc"><?php _e('Specify smile code that will be used in the text and the name of the corresponding smiley. The smiley name see above (when you hover any smiley).', 'kama-wp-smile'); ?></span>
				</td>
			</tr>

			<tr>
				<td>
					<input type="text" name="sm_start" placeholder="(:" value="<?= $this->get_opt('sm_start'); ?>" style="width:60px;" /> smile
					<input type="text" name="sm_end" placeholder=":)" value="<?= $this->get_opt('sm_end'); ?>" style="width:60px;" />
				</td>
				<td>
					<b><?php _e('Open and close tags.', 'kama-wp-smile'); ?></b><br>
					<span class="desc"><?php _e('Wrapper tags for smiley name in content. Default:', 'kama-wp-smile'); ?> <code>(:</code> smile <code>:)</code></span>
				</td>
			</tr>

			<tr>
				<td>
					<?php
					$packs = [];
					if( is_dir( $path = untrailingslashit( KWS_PLUGIN_PATH ) . '-packs' ) ){
						foreach( glob( "$path/*" ) as $dir ){
							is_dir( $dir ) && $packs[ basename( $dir ) ] = true;
						}
					}

					foreach( glob( KWS_PLUGIN_PATH . 'packs/*' ) as $dir ){
						is_dir( $dir ) && $packs[ sanitize_key( basename( $dir ) ) ] = true;
					}

					$packs = array_filter( array_keys($packs) );
					sort( $packs );

					$option = array();
					foreach( $packs as $pack ){
						$option[] = '<option '. selected($pack, $this->get_opt('sm_pack'), 0) .'>'. $pack .'</option>';
					}

					echo '
					<select name="sm_pack">
						<option value="qip">- not selected -</option>
						'. implode("\n", $option ) .'
					</select>';
					?>
				</td>
				<td>
					<b><?php _e('Smiley complect (pack).', 'kama-wp-smile'); ?></b><br>
					<span class="desc">
						<?php printf(  __('To add your own smiley pack, create folder %s in WP plugins folder (next to the plugin folder) and insert in it your folder (pack) with smiles','kama-wp-smile'), '<code>'. basename( KWS_PLUGIN_PATH ) .'-packs</code>' ); ?><br>
						<?php _e('Download smiles packs:', 'kama-wp-smile'); ?>
						<a href="https://wp-kama.ru/wp-content/uploads/2010/12/qip_all.zip">qip_all</a>,
						<a href="https://wp-kama.ru/wp-content/uploads/2010/12/qip_dark_all.zip">qip_dark_all</a>,
						<a href="https://wp-kama.ru/wp-content/uploads/2010/12/skype.zip">skype</a>,
						<a href="https://wp-kama.ru/wp-content/uploads/2010/12/skype_big.zip">skype_big</a>
					<span>
				</td>
			</tr>

			<tr>
				<td colspan="2">
					<b><?php _e('Additional CSS styles.', 'kama-wp-smile'); ?></b><br>
					<span class="desc"><?php _e('Add here your css styles to change output. This styles will be added after default one.', 'kama-wp-smile'); ?></span>
					<textarea readonly style="width:100%; height:50px;"><?= @ $this->main_css() ?></textarea>

					<textarea name="additional_css" style="width:100%; height:70px;"><?= esc_textarea( $this->get_opt('additional_css') )?></textarea>
				</td>
			</tr>
		</table>


		<input type="submit" name="kama_sm_submit" class="button-primary" value="<?php _e('Save changes', 'kama-wp-smile'); ?>" />
		<input type="submit" name="kama_sm_reset" class="button" value="<?php _e('Reset options to default', 'kama-wp-smile'); ?>" onclick='return confirm("<?php _e('Are you sure?', 'kama-wp-smile'); ?>")' style="float:right;" />
	</form>


</div>

<?php wp_enqueue_script('jquery-ui-sortable'); ?>
<script type='text/javascript'>
// jQuery
jQuery(document).ready(function($){
	// выбор смайликов
	var $allSm   = $('.select_smiles'),
		$used_sm = $('input[name="used_sm"]'),
		$elUsed  = $('<div class="used-smiles">');

	$allSm.before( $elUsed )/*.before('<hr>')*/;

	$allSm.find('> *').click(function(){
		if( $(this).hasClass('checked') ){
			$(this).prependTo( $allSm ).removeClass('checked');
		} else {
			$(this).appendTo( $elUsed ).addClass('checked');
		}

		collectToInput();
	});

	// собираем по порядку при первой загрузке
	var array = $used_sm.val().replace(/\r/, '').split(/,/);
	$.each( array, function(){
		this.replace(/^\s+/,'').replace(/\s+$/,'');
		if( this != '' ){
			$allSm.find('#'+ this).appendTo( $elUsed );
		}
	});

	// обновляет input name="used_sm"
	var collectToInput = function(){
		var newSmIds = [];
		$elUsed.find('> *').each(function(){
			newSmIds.push( $(this).attr('id') );
		});
		$used_sm.val( newSmIds.join(',') );
	};

	// сортировка смайликов
	$('.used-smiles').sortable({
		stop: function( event, ui ) { collectToInput(); }
	});

});
</script>
