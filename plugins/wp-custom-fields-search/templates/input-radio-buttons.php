<ul>
<?php foreach($options['options'] as $option) {
?>
    <li>
	<input type='radio' 
        name="<?php echo htmlspecialchars($html_name)?>" 
        id="<?php echo htmlspecialchars($html_name)?>-<?php echo $option['value']?>" 
        value="<?php echo htmlspecialchars($option['value']); ?>" <?php if($option['value']==$query[$html_name]) { ?> checked='checked'<?php } ?> >
        <label for="<?php echo htmlspecialchars($html_name)?>-<?php echo htmlspecialchars($option['value'])?>">
		<?php echo htmlspecialchars($option['label']);?>
        </label>
	</li>
<?php } ?>
</ul>
