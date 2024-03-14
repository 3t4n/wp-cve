<script>
	var w2dc_order_date_metabox_attrs = <?php echo json_encode(
		array(
			'isRTL' => (function_exists('is_rtl') && is_rtl()) ? 1 : 0,
			'dateFormat' => $dateformat,
			'firstDay' => intval(get_option('start_of_week')),
			'lang_code' => (w2dc_getDatePickerLangCode(get_locale())) ? w2dc_getDatePickerLangCode(get_locale()) : 0,
			'order_date_formatted' => date('d/m/Y', intval($listing->order_date)),
		)
	); ?>;
</script>

<p><?php _e("Manually set new sorting date and time of the listing.<br />Sorting date will be automatically changed when listing activated, raised up or listing level was changed.", 'W2DC'); ?></p>

<div class="w2dc-content">
	<div class="w2dc-field w2dc-form-group w2dc-form-horizontal">
		<label class="w2dc-col-md-1 w2dc-control-label">
			<?php _e('Date:', 'W2DC'); ?>
		</label>
		<div class="w2dc-col-md-3">
			<div class="w2dc-has-feedback">
				<input type="text" id="order_date" class="w2dc-form-control" />
				<span class="w2dc-glyphicon w2dc-glyphicon-calendar w2dc-form-control-feedback"></span>
			</div>
		</div>
		<label class="w2dc-col-md-1 w2dc-control-label"><?php _e('Time:', 'W2DC'); ?></label>
		<div class="w2dc-col-md-3">
			<?php $hour = date('H', intval($listing->order_date)); ?>
			<?php $minute = date('i', intval($listing->order_date)); ?>
			<input type="hidden" name="order_date_tmstmp" value="<?php echo esc_attr(intval($listing->order_date) - ($hour*3600) - ($minute*60)); ?>"/>
			<select name="order_date_hour" class="w2dc-form-control">
				<option value="00" <?php if ($hour == '00') echo 'selected'; ?>>00</option>
				<option value="01" <?php if ($hour == '01') echo 'selected'; ?>>01</option>
				<option value="02" <?php if ($hour == '02') echo 'selected'; ?>>02</option>
				<option value="03" <?php if ($hour == '03') echo 'selected'; ?>>03</option>
				<option value="04" <?php if ($hour == '04') echo 'selected'; ?>>04</option>
				<option value="05" <?php if ($hour == '05') echo 'selected'; ?>>05</option>
				<option value="06" <?php if ($hour == '06') echo 'selected'; ?>>06</option>
				<option value="07" <?php if ($hour == '07') echo 'selected'; ?>>07</option>
				<option value="08" <?php if ($hour == '08') echo 'selected'; ?>>08</option>
				<option value="09" <?php if ($hour == '09') echo 'selected'; ?>>09</option>
				<option value="10" <?php if ($hour == '10') echo 'selected'; ?>>10</option>
				<option value="11" <?php if ($hour == '11') echo 'selected'; ?>>11</option>
				<option value="12" <?php if ($hour == '12') echo 'selected'; ?>>12</option>
				<option value="13" <?php if ($hour == '13') echo 'selected'; ?>>13</option>
				<option value="14" <?php if ($hour == '14') echo 'selected'; ?>>14</option>
				<option value="15" <?php if ($hour == '15') echo 'selected'; ?>>15</option>
				<option value="16" <?php if ($hour == '16') echo 'selected'; ?>>16</option>
				<option value="17" <?php if ($hour == '17') echo 'selected'; ?>>17</option>
				<option value="18" <?php if ($hour == '18') echo 'selected'; ?>>18</option>
				<option value="19" <?php if ($hour == '19') echo 'selected'; ?>>19</option>
				<option value="20" <?php if ($hour == '20') echo 'selected'; ?>>20</option>
				<option value="21" <?php if ($hour == '21') echo 'selected'; ?>>21</option>
				<option value="22" <?php if ($hour == '22') echo 'selected'; ?>>22</option>
				<option value="23" <?php if ($hour == '23') echo 'selected'; ?>>23</option>
			</select>
		</div>
		<div class="w2dc-col-md-3">
			<select name="order_date_minute" class="w2dc-form-control">
				<option value="00" <?php if ($minute == '00') echo 'selected'; ?>>00</option>
				<option value="01" <?php if ($minute == '01') echo 'selected'; ?>>01</option>
				<option value="02" <?php if ($minute == '02') echo 'selected'; ?>>02</option>
				<option value="03" <?php if ($minute == '03') echo 'selected'; ?>>03</option>
				<option value="04" <?php if ($minute == '04') echo 'selected'; ?>>04</option>
				<option value="05" <?php if ($minute == '05') echo 'selected'; ?>>05</option>
				<option value="06" <?php if ($minute == '06') echo 'selected'; ?>>06</option>
				<option value="07" <?php if ($minute == '07') echo 'selected'; ?>>07</option>
				<option value="08" <?php if ($minute == '08') echo 'selected'; ?>>08</option>
				<option value="09" <?php if ($minute == '09') echo 'selected'; ?>>09</option>
				<option value="10" <?php if ($minute == '10') echo 'selected'; ?>>10</option>
				<option value="11" <?php if ($minute == '11') echo 'selected'; ?>>11</option>
				<option value="12" <?php if ($minute == '12') echo 'selected'; ?>>12</option>
				<option value="13" <?php if ($minute == '13') echo 'selected'; ?>>13</option>
				<option value="14" <?php if ($minute == '14') echo 'selected'; ?>>14</option>
				<option value="15" <?php if ($minute == '15') echo 'selected'; ?>>15</option>
				<option value="16" <?php if ($minute == '16') echo 'selected'; ?>>16</option>
				<option value="17" <?php if ($minute == '17') echo 'selected'; ?>>17</option>
				<option value="18" <?php if ($minute == '18') echo 'selected'; ?>>18</option>
				<option value="19" <?php if ($minute == '19') echo 'selected'; ?>>19</option>
				<option value="20" <?php if ($minute == '20') echo 'selected'; ?>>20</option>
				<option value="21" <?php if ($minute == '21') echo 'selected'; ?>>21</option>
				<option value="22" <?php if ($minute == '22') echo 'selected'; ?>>22</option>
				<option value="23" <?php if ($minute == '23') echo 'selected'; ?>>23</option>
				<option value="24" <?php if ($minute == '24') echo 'selected'; ?>>24</option>
				<option value="25" <?php if ($minute == '25') echo 'selected'; ?>>25</option>
				<option value="26" <?php if ($minute == '26') echo 'selected'; ?>>26</option>
				<option value="27" <?php if ($minute == '27') echo 'selected'; ?>>27</option>
				<option value="28" <?php if ($minute == '28') echo 'selected'; ?>>28</option>
				<option value="29" <?php if ($minute == '29') echo 'selected'; ?>>29</option>
				<option value="30" <?php if ($minute == '30') echo 'selected'; ?>>30</option>
				<option value="31" <?php if ($minute == '31') echo 'selected'; ?>>31</option>
				<option value="32" <?php if ($minute == '32') echo 'selected'; ?>>32</option>
				<option value="33" <?php if ($minute == '33') echo 'selected'; ?>>33</option>
				<option value="34" <?php if ($minute == '34') echo 'selected'; ?>>34</option>
				<option value="35" <?php if ($minute == '35') echo 'selected'; ?>>35</option>
				<option value="36" <?php if ($minute == '36') echo 'selected'; ?>>36</option>
				<option value="37" <?php if ($minute == '37') echo 'selected'; ?>>37</option>
				<option value="38" <?php if ($minute == '38') echo 'selected'; ?>>38</option>
				<option value="39" <?php if ($minute == '39') echo 'selected'; ?>>39</option>
				<option value="40" <?php if ($minute == '40') echo 'selected'; ?>>40</option>
				<option value="41" <?php if ($minute == '41') echo 'selected'; ?>>41</option>
				<option value="42" <?php if ($minute == '42') echo 'selected'; ?>>42</option>
				<option value="43" <?php if ($minute == '43') echo 'selected'; ?>>43</option>
				<option value="44" <?php if ($minute == '44') echo 'selected'; ?>>44</option>
				<option value="45" <?php if ($minute == '45') echo 'selected'; ?>>45</option>
				<option value="46" <?php if ($minute == '46') echo 'selected'; ?>>46</option>
				<option value="47" <?php if ($minute == '47') echo 'selected'; ?>>47</option>
				<option value="48" <?php if ($minute == '48') echo 'selected'; ?>>48</option>
				<option value="49" <?php if ($minute == '49') echo 'selected'; ?>>49</option>
				<option value="50" <?php if ($minute == '50') echo 'selected'; ?>>50</option>
				<option value="51" <?php if ($minute == '51') echo 'selected'; ?>>51</option>
				<option value="52" <?php if ($minute == '52') echo 'selected'; ?>>52</option>
				<option value="53" <?php if ($minute == '53') echo 'selected'; ?>>53</option>
				<option value="54" <?php if ($minute == '54') echo 'selected'; ?>>54</option>
				<option value="55" <?php if ($minute == '55') echo 'selected'; ?>>55</option>
				<option value="56" <?php if ($minute == '56') echo 'selected'; ?>>56</option>
				<option value="57" <?php if ($minute == '57') echo 'selected'; ?>>57</option>
				<option value="58" <?php if ($minute == '58') echo 'selected'; ?>>58</option>
				<option value="59" <?php if ($minute == '59') echo 'selected'; ?>>59</option>
			</select>
		</div>
	</div>
</div>
	
<?php do_action('w2dc_changedate_html', $listing); ?>