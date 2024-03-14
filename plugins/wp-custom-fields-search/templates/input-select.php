<select type='text' name="<?php echo htmlspecialchars($html_name)?>" id='<?php echo $html_id;?>'>

<?php foreach($options['options'] as $option) {
?>
	<option value="<?php echo htmlspecialchars($option['value']); ?>"
		 <?php if(array_key_exists($html_name, $query) && ($option['value']==$query[$html_name])) { ?> selected='selected'<?php } ?> >
		<?php echo htmlspecialchars($option['label']);?>
	</option>
<?php } ?>
</select>
