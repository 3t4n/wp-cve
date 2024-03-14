<?php
/**
 * Author: Vitaly Kukin
 * Date: 24.09.2018
 * Time: 14:19
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="modal fade" id="reportModal" style="display:none" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<input type="hidden" id="report-id" name="report-id">
				<div class="form-group">
					<label for="report-message" class="control-label">
						<?php _e( 'Send a complaint to DropshipMe team about incorrect product information or inappropriate content.', 'dm' ) ?>
					</label>
                </div>
                <div class="form-group">
                    <select name="report-type" id="report-type" class="form-control">
                        <option value="0"><?php _e('What is wrong with this product?','dm')?></option>
                        <option value="1"><?php _e('A mistake in product title','dm')?></option>
                        <option value="2"><?php _e('Incorrect pricing','dm')?></option>
                        <option value="3"><?php _e('A problem with images','dm')?></option>
                        <option value="4"><?php _e('A mistake in product description','dm')?></option>
                        <option value="5"><?php _e('Prohibited or offensive product','dm')?></option>
                        <option value="6"><?php _e('Something else','dm')?></option>
                    </select>
                </div>
                <div class="form-group">
					<textarea
						class="form-control"
						id="report-message"
						name="report-message"
						placeholder="<?php _e( 'Please enter a comment', 'dm' ) ?>"></textarea>
				</div>
				<div class="form-group">
					<button type="button" class="btn btn-green ads-no"><?php _e( 'Send Report', 'dm' ) ?></button>
				</div>
			</div>
		</div>
	</div>
</div>
