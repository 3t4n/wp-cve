<style type="text/css">
<?php watu_resp_table_css(800);?>

tr.watu-sortable-question {
	cursor:move;
}
</style>

<div class="wrap">
	<h2><?php printf(__("Manage Questions in %s", 'watu'), $exam_name); ?></h2>
	
		<div class="postbox-container" style="min-width:60%;max-width:75%;margin-right:2%;">
		
		<p><a href="admin.php?page=watu_exams"><?php printf(__('Back to %s', 'watu'), WATU_QUIZ_WORD_PLURAL)?></a> 
		&nbsp;|&nbsp; <a href="admin.php?page=watu_exam&quiz=<?php echo intval($_GET['quiz'])?>&action=edit"><?php printf(__('Edit this %s', 'watu'), WATU_QUIZ_WORD)?></a> 
		&nbsp;|&nbsp; <a href="admin.php?page=watu_grades&quiz_id=<?php echo intval($_GET['quiz'])?>"><?php _e('Manage Grades / Results', 'watu')?></a>
		&nbsp;|&nbsp; <a href="admin.php?page=watu_import_questions&quiz_id=<?php echo intval($_GET['quiz'])?>"><?php _e('Import Questions', 'watu');?></a></p>
		
		<?php
		wp_enqueue_script( 'listman' );
		wp_print_scripts();
		?>
		
		<p style="color:green;"><?php printf(__('To add this %s to your blog, insert the code ', 'watu'), WATU_QUIZ_WORD); ?> <input type="text" readonly size="8" onclick="this.select();" value="[WATU <?php echo intval($_REQUEST['quiz']) ?>]"> <?php _e('into any post or page.', 'watu') ?></p>
		
		<form method="get" action="admin.php">
		<input type="hidden" name="page" value="watu_questions">
		<input type="hidden" name="quiz" value="<?php echo intval($_GET['quiz'])?>">
		<p><?php _e('Show questions of type:', 'watu');?>
		<select name="filter_answer_type">
			<option value=""><?php _e('All types', 'watu');?></option>
			<option value="radio" <?php if(!empty($_GET['filter_answer_type']) and $_GET['filter_answer_type'] == 'radio') echo 'selected'?>><?php _e('Single choice', 'watu');?></option>
			<option value="textarea" <?php if(!empty($_GET['filter_answer_type']) and $_GET['filter_answer_type'] == 'textarea') echo 'selected'?>><?php _e('Open end (essay)', 'watu');?></option>
			<option value="checkbox" <?php if(!empty($_GET['filter_answer_type']) and $_GET['filter_answer_type'] == 'checkbox') echo 'selected'?>><?php _e('Multiple choice', 'watu');?></option>
		</select>	
		&nbsp;
		<?php _e('from category', 'watu');?>
		
		<select name="filter_cat">
			<option value=""><?php _e('All categories', 'watu');?></option>
			<?php foreach($cats as $cat):?>
				<option value="<?php echo $cat->ID;?>" <?php if(!empty($_GET['filter_cat']) and $_GET['filter_cat'] == $cat->ID) echo 'selected';?>><?php echo stripslashes($cat->name);?></option>
			<?php endforeach;?>
			<option value="-1" <?php if(!empty($_GET['filter_cat']) and $_GET['filter_cat'] == -1) echo 'selected';?>><?php _e('Uncategorized', 'watu');?></option>
		</select>		
			
		&nbsp;
		<?php _e('with ID (you can separate multiple IDs with comma):', 'watu')?> <input type="text" name="filter_id" value="<?php echo empty($_GET['filter_id']) ? 0 : intval($_GET['filter_id'])?>" size="6"> 
		&nbsp;<br>
		<?php _e('and question contains (phrase):', 'watu');?> <input type="text" name="filter_contents" value="<?php echo empty($_GET['filter_contents']) ? '' : esc_attr($_GET['filter_contents'])?>">
		
		<input type="submit" value="<?php _e('Filter questions', 'watu')?>" class="button-primary">
		<input type="button" class="button" value="<?php _e('Clear filters', 'watu');?>" onclick="window.location='admin.php?page=watu_questions&quiz=<?php echo intval($_GET['quiz'])?>';"></p>	
		
		<p><?php _e('Questions per page:', 'watu');?> <select name="page_limit" onchange="this.form.submit();">		
			<option value="10" <?php if($page_limit == 10) echo 'selected';?>>10</option>
			<option value="20" <?php if($page_limit == 20) echo 'selected';?>>20</option>
			<option value="50" <?php if($page_limit == 50) echo 'selected';?>>50</option>
			<option value="100" <?php if($page_limit == 100) echo 'selected';?>>100</option>
			<option value="200" <?php if($page_limit == 200) echo 'selected';?>>200</option>
			<option value="500" <?php if($page_limit == 500) echo 'selected';?>>500</option>
		</select></p>
	</form>
		
		<form method="post">
		<table class="widefat watu-table">
			<thead>
			<tr>			
				<th><input type="checkbox" onclick="WatuSelectAll(this);"></th>	
				<th scope="col"><div style="text-align: center;"><a href="admin.php?page=watu_questions&quiz=<?php echo intval($_GET['quiz'])?>&fix_sort_order=1" title="<?php _e('Click here to automatically fix the order of questions.', 'watu')?>">#</a></div></th>
				<th scope="col"><?php _e('ID', 'watu') ?></th>
				<th scope="col"><?php _e('Question', 'watu') ?></th>				
				<th scope="col"><?php _e('Question type', 'watu') ?></th>
				<th scope="col"><?php _e('Category', 'watu') ?></th>
				<th scope="col"><?php _e('Required?', 'watu') ?></th>
				<th scope="col"><?php _e('Number Of Answers', 'watu') ?></th>
				<th scope="col" colspan="3"><?php _e('Action', 'watu') ?></th>
			</tr>
			</thead>
		
			<tbody id="watuQuestionsList">
		<?php
									
		
		if (count($all_question)) {
			$bgcolor = '';			
			$question_count = 0;
			foreach($all_question as $question) {
				if(empty($class)) $class = 'alternate';
				else $class = '';
				$question_count++;
				print "<tr id='question-{$question->ID}' class='$class watu-sortable-question'>\n";
				?>
				<td><input type="checkbox" name="qids[]" value="<?php echo $question->ID?>" class="qids" onclick="toggleMassDelete();"></td>
				<td style="text-align: center;">
				<div style='float:left;<?php if(!empty($_POST['filter_cat_id'])) echo 'display:none;'?>'>
				
			</div>							
				<?php echo $question_count ?></td>
				<td><?php echo $question->ID;?></td>
				<td><?php echo stripslashes($question->question) ?></td>
				<td><?php switch($question->answer_type):
					case 'radio': _e('Single choice', 'watu'); break;
					case 'checkbox': _e('Multiple choice', 'watu'); break;
					case 'textarea': _e('Open end (essay)', 'watu'); break;
				endswitch;
				if($question->is_inactive) echo "&nbsp;<span style='color:red'>".__('Inactive', 'watu')."</span>&nbsp;";?></td>
				<td><?php echo $question->cat_id ? stripslashes($question->cat) : __('Uncategorized', 'watu');?></td>
				<td><?php echo $question->is_required ? __('Yes', 'watu') : __('No', 'watu');?></td>
				<td><?php echo $question->answer_count ?></td>
				<td><a href='admin.php?page=watu_question&amp;question=<?php echo $question->ID?>&amp;action=edit&amp;quiz=<?php echo intval($_REQUEST['quiz'])?>' class='edit'><?php _e('Edit', 'watu'); ?></a></td>
				<td><a href="<?php echo wp_nonce_url('admin.php?page=watu_questions&amp;action=delete&amp;question='.$question->ID.'&amp;quiz='.intval($_REQUEST['quiz']), 'watu_questions');?>" class='delete' onclick="return confirm('<?php echo addslashes(__("You are about to delete this question. This will delete the answers to this question. Press 'OK' to delete and 'Cancel' to stop.", 'watu'))?>');"><?php _e('Delete', 'watu')?></a></td>
				</tr>
		<?php
				}
			} else {
		?>
			<tr style='background-color: <?php echo @$bgcolor; ?>;'>
				<td colspan="4"><?php _e('No questions found.', 'watu') ?></td>
			</tr>
		<?php
		}
		?>
			</tbody>
		</table>
		
		<a href="admin.php?page=watu_question&amp;action=new&amp;quiz=<?php echo intval($_REQUEST['quiz']) ?>"><?php _e('Create New Question', 'watu')?></a>
		
		<p align="center"><?php if($offset>0):?><a href="admin.php?page=watu_questions&quiz=<?php echo $quiz_id;?>&offset=<?php echo ($offset-$page_limit)?><?php echo $filter_params?>"><?php _e('Previous page', 'watu')?></a><?php endif;?>
		&nbsp;
		<?php if($offset + $page_limit < $count):?> <a href="admin.php?page=watu_questions&quiz=<?php echo $quiz_id;?>&offset=<?php echo ($offset+$page_limit)?><?php echo $filter_params?>"><?php _e('Next page', 'watu')?></a> <?php endif;?></p>
		
		<div align="center" style="display:none;" id="massDeleteQuesions"><p align="center">
			<input type="submit" name="mass_delete" onclick="if(!confirm('<?php _e('Are you sure?', 'watu')?>')) return false;" value="<?php _e('Delete Selected', 'watu')?>" class="button"><p>
			 <h3 align="center"><?php _e('Other Mass Changes:', 'watu');?></h3>
		  <p><?php _e('Change properties of selected questions:', 'watu');?> <select name="is_required">
		     <option value="-1"><?php _e("Don't change required status", 'watu');?></option>
		     <option value="1"><?php _e('Change all to required', 'watu');?></option>
		     <option value="0"><?php _e('Change all to not required', 'watu');?></option>
		  </select> &nbsp;
		 
		  
		  <input type="submit" name="mass_update" value="<?php _e('Update Selected', 'watu');?>" class="button-primary"></p>
			<?php wp_nonce_field('watu_questions');?>
		</div>
		
		<p style="display:none;color:red;text-align:center;font-weight:bold;" id="watuSaveOrderBtn">
			<?php _e('When done with reordering press the button to save:', 'watu');?>
			&nbsp;
			<input type="button" class="button button-primary" value="<?php _e('Save New Questions Order', 'watu');?>" onclick="WatuReorderQuestions();">
			</p>
			<p style="display:none;color:green;text-align:center;font-weight:bold;" id="watuSaveOrderBtnMsg">
			<?php _e('Questions order saved.', 'watu');?>
		</p>		
		
		</form>
		</div>
		<div id="watu-sidebar">
				<?php include(WATU_PATH."/views/sidebar.php");?>
		</div>
	</div>	
	
<script type="text/javascript">
function WatuSelectAll(chk) {
	if(chk.checked) {
		jQuery(".qids").attr('checked',true);
	}
	else {
		jQuery(".qids").removeAttr('checked');
	}
	
	toggleMassDelete();
}

// shows or hides the mass delete button
function toggleMassDelete() {
	var len = jQuery(".qids:checked").length;
	
	if(len) jQuery('#massDeleteQuesions').show();
	else jQuery('#massDeleteQuesions').hide();
}

jQuery(function(){
	jQuery('#watuQuestionsList').sortable({								
		stop: function(event, ui) { 
			jQuery('#watuSaveOrderBtn').show();
			jQuery('#watuSaveOrderBtnMsg').hide();
		}				    	
	});
});

function WatuReorderQuestions() {
	// to avoid sending many unnecessary queries to the server
	var questions = jQuery('#watuQuestionsList').sortable("toArray");
		
	data = {"action":'watu_reorder_questions',  'exam_id' : <?php echo intval($_GET['quiz']);?>, 'questions' : questions };		
		
	jQuery.post("<?php echo admin_url( 'admin-ajax.php' ); ?>", data, function(msg){
		jQuery('#watuSaveOrderBtn').hide();	
		jQuery('#watuSaveOrderBtnMsg').show();
	});
}

<?php watu_resp_table_js();?>
</script>		