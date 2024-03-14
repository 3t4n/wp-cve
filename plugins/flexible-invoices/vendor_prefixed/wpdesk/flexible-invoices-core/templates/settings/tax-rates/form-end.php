<?php

namespace WPDeskFIVendor;

/**
 * Scoper fix
 */
?>
</tbody>
<tfoot>
<tr>
	<th colspan="99">
		<a id="insert_tax" href="#" class="button plus insert"><?php 
\esc_attr_e('Insert', 'flexible-invoices');
?></a>
	</th>
</tr>
</tfoot>
</table>
<p class="submit"><input type="submit" value="<?php 
\esc_attr_e('Save changes', 'flexible-invoices');
?>" class="button button-primary" id="submit" name=""></p>
</form>
<script id="tax-rates-row" type="text/x-custom-template">
<tr>
<td class="sort ui-sortable-handle"><input type="hidden" class="row-num" value="11"></td>
<td class="forminp"><input type="text" name="tax-rates[tax][0][name]" id="name" class="tax-name hs-beacon-search" placeholder="20%" data-beacon_search="Tax Rates (WordPress Only)" value="np."></td>
<td class="forminp"><input type="text" name="tax-rates[tax][0][rate]" id="rate" class="tax-rate hs-beacon-search" placeholder="20" data-beacon_search="Tax Rates (WordPress Only)" value="0"></td>
<td class="delete"><a href="#" class="delete-item"><span class="dashicons dashicons-no-alt"></span></a></td>
</tr>
</script>
<?php 
