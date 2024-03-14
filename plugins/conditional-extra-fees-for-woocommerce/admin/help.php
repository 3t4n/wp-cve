<div id="weight_short_code_help" style="display:none;">
    <div class="bootstrap-wrapper">
    <p>This fees will be added in the main fees set at the top</p><p>You can use short code <kbd>[weight]</kbd><br>
    <kbd>[weight]</kbd> &#x21d2; will get replaced with the total weight of the cart<br><br>
    E.g: <kbd>2 * [weight]</kbd><br><br>
    E.g: <kbd>- 2 * [weight]</kbd><br><br>
    E.g: <kbd>2 * ([weight] - 10)</kbd>
    </p>
    </div>
</div>

<div id="subtotal_short_code_help"  style="display:none;">
    <div class="bootstrap-wrapper">
    <p>This fees will be added in the main fees set at the top</p><p>You can use short code <kbd>[subtotal]</kbd><br>
    <kbd>[subtotal]</kbd> &#x21d2; will get replaced with the subtotal of the cart<br><br>
    E.g: <kbd>2 * [subtotal]</kbd><br><br>
    E.g: <kbd>- 2 * [subtotal]</kbd><br><br>
    E.g: <kbd>2 * ([subtotal] - 10)</kbd>
    </p>
    </div>
</div>

<div id="shipping_charge_short_code_help"  style="display:none;">
    <div class="bootstrap-wrapper">
    <p class="description afrsm_dynamic_rules_tooltips">
								 You can enter fixed amount or make it dynamic using below parameters:<br>
									&nbsp;&nbsp;&nbsp;<kbd>[qty]</kbd> &#x21d2; total number of items in cart<br><br>
									&nbsp;&nbsp;&nbsp;<kbd>[fee percent="10" min_fee="20"]</kbd> &#x21d2; Percentage based fee<br><br>
									
									Below are some examples:<br>
									&nbsp;&nbsp;&nbsp;<strong>i.</strong> <kbd>10.00</kbd> &#x21d2; To add flat 10.00 shipping charge.<br><br>
									&nbsp;&nbsp;&nbsp;<strong>ii.</strong> <kbd>20.00 * [qty]</kbd> &#x21d2; To charge 20.00 per quantity in the cart. It will be 100.00 if the cart has 5 quantity.<br><br>
									&nbsp;&nbsp;&nbsp;<strong>iii.</strong> <kbd>[fee percent="10" min_fee="20"]</kbd> &#x21d2; This means charge 10 percent of cart subtotal, minimum 20 charge will be applicable. If the 10% is 10$ which is less then 20$ so this will apply a 20$ instead of $10<br><br>
									&nbsp;&nbsp;&nbsp;<strong>iv.</strong> <kbd>[fee percent="10" max_fee="20"]</kbd> &#x21d2; This means charge 10 percent of cart subtotal if the 10% is grater then 20 will be applied.<br><br>
								</p>
    </div>
</div>


<div id="shipping_class_charge_short_code_help"  style="display:none;">
    <div class="bootstrap-wrapper">
    <p class="description afrsm_dynamic_rules_tooltips">
								 You can enter fixed amount or make it dynamic using below parameters:<br>
									&nbsp;&nbsp;&nbsp;<kbd>[qty]</kbd> &#x21d2; total number of items from the particular <strong>shipping class</strong> in cart<br><br>
								
									
									Below are some examples:<br>
									&nbsp;&nbsp;&nbsp;<strong>i.</strong> <kbd>10.00</kbd> &#x21d2; To add flat 10.00 shipping charge.<br><br>
									&nbsp;&nbsp;&nbsp;<strong>ii.</strong> <kbd>20.00 * [qty]</kbd> &#x21d2; To charge 20.00 per quantity in the cart. It will be 100.00 if the cart has 5 quantity.<br><br>
                                    &nbsp;&nbsp;&nbsp;<strong>iii.</strong> <kbd>2 * ( [qty] - 1 )</kbd> &#x21d2; This will charge extra 2 for every extra quantity addedd from this shipping class after 1 unit (it wont add extra 2 charge when user only have 1 unit of product from this class, but for every extra unit after 1 will be charged extra 2$<br><br>
								</p>
    </div>
</div>

<div id="product_quantity_charge_short_code_help"  style="display:none;">
    <div class="bootstrap-wrapper">
    <p>This fees will be added in the main fees set at the top</p><p>You can use short code <kbd>[qty]</kbd><br>
    <kbd>[qty]</kbd> &#x21d2; will get replaced with the qty of the particular product in the cart<br><br>
    E.g: <kbd>2 * [qty]</kbd><br><br>
    E.g: <kbd>- 2 * [qty]</kbd><br><br>
    E.g: <kbd>2 * ([qty] - 10)</kbd>
    </p>
    </div>
</div>

<div id="category_quantity_charge_short_code_help"  style="display:none;">
    <div class="bootstrap-wrapper">
    <p>This fees will be added in the main fees set at the top</p><p>You can use short code <kbd>[qty]</kbd><br>
    <kbd>[qty]</kbd> &#x21d2; will get replaced with the qty of the product from this category in the cart<br><br>
    E.g: <kbd>2 * [qty]</kbd><br><br>
    E.g: <kbd>- 2 * [qty]</kbd><br><br>
    E.g: <kbd>2 * ([qty] - 10)</kbd>
    </p>
    </div>
</div>


<div id="cart_weight_short_code_help"  style="display:none;">
    <div class="bootstrap-wrapper">
    <p>This fees will be added in the main fees set at the top</p><p>You can use short code <kbd>[qty]</kbd><br>
    <kbd>[qty]</kbd> &#x21d2; will get replaced with the qty of the product in the cart<br><br>
    E.g: <kbd>2 * [qty]</kbd><br><br>
    E.g: <kbd>- 2 * [qty]</kbd><br><br>
    E.g: <kbd>2 * ([qty] - 10)</kbd>
    </p>
    </div>
</div>

<div id="shippingclass_quantity_charge_short_code_help"  style="display:none;">
    <div class="bootstrap-wrapper">
    <p>This fees will be added in the main fees set at the top</p><p>You can use short code <kbd>[qty]</kbd><br>
    <kbd>[qty]</kbd> &#x21d2; will get replaced with the qty of the product from this shipping class in the cart<br><br>
    E.g: <kbd>2 * [qty]</kbd><br><br>
    E.g: <kbd>- 2 * [qty]</kbd><br><br>
    E.g: <kbd>2 * ([qty] - 10)</kbd>
    </p>
    </div>
</div>

<div id="product_subtotal_charge_short_code_help"  style="display:none;">
    <div class="bootstrap-wrapper">
    <p>This fees will be added in the main fees set at the top</p<p>You can use short code <kbd>[subtotal]</kbd><br>
    <kbd>[subtotal]</kbd> &#x21d2; will get replaced with the subtotal of the particular product in the cart<br><br>
    E.g: <kbd>2 * [subtotal]</kbd><br><br>
    E.g: <kbd>- 2 * [subtotal]</kbd><br><br>
    E.g: <kbd>2 * ([subtotal] - 10)</kbd>
    </p>
    </div>
</div>

<div id="category_subtotal_charge_short_code_help"  style="display:none;">
    <div class="bootstrap-wrapper">
    <p>This fees will be added in the main fees set at the top</p><p>You can use short code <kbd>[subtotal]</kbd><br>
    <kbd>[subtotal]</kbd> &#x21d2; will get replaced with the subtotal of the product from this category in the cart<br><br>
    E.g: <kbd>2 * [subtotal]</kbd><br><br>
    E.g: <kbd>- 2 * [subtotal]</kbd><br><br>
    E.g: <kbd>2 * ([subtotal] - 10)</kbd>
    </p>
    </div>
</div>


<div id="shippingclass_subtotal_charge_short_code_help"  style="display:none;">
    <div class="bootstrap-wrapper">
    <p>This fees will be added in the main fees set at the top</p><p>You can use short code <kbd>[subtotal]</kbd><br>
    <kbd>[subtotal]</kbd> &#x21d2; will get replaced with the subtotal of the product from this shipping class in the cart<br><br>
    E.g: <kbd>2 * [subtotal]</kbd><br><br>
    E.g: <kbd>- 2 * [subtotal]</kbd><br><br>
    E.g: <kbd>2 * ([subtotal] - 10)</kbd>
    </p>
    </div>
</div>

<div id="product_weight_charge_short_code_help"  style="display:none;">
    <div class="bootstrap-wrapper">
    <p>This fees will be added in the main fees set at the top</p><p>You can use short code <kbd>[weight]</kbd><br>
    <kbd>[weight]</kbd> &#x21d2; will get replaced with the weight of the particular product in the cart<br><br>
    E.g: <kbd>2 * [weight]</kbd><br><br>
    E.g: <kbd>- 2 * [weight]</kbd><br><br>
    E.g: <kbd>2 * ([weight] - 10)</kbd>
    </p>
    </div>
</div>

<div id="category_weight_charge_short_code_help"  style="display:none;">
    <div class="bootstrap-wrapper">
    <p>This fees will be added in the main fees set at the top</p><p>You can use short code <kbd>[weight]</kbd><br>
    <kbd>[weight]</kbd> &#x21d2; will get replaced with the weight of the product from this category in the cart<br><br>
    E.g: <kbd>2 * [weight]</kbd><br><br>
    E.g: <kbd>- 2 * [weight]</kbd><br><br>
    E.g: <kbd>2 * ([weight] - 10)</kbd>
    </p>
    </div>
</div>

<div id="shippingclass_weight_charge_short_code_help"  style="display:none;">
    <div class="bootstrap-wrapper">
    <p>This fees will be added in the main fees set at the top</p><p>You can use short code <kbd>[weight]</kbd><br>
    <kbd>[weight]</kbd> &#x21d2; will get replaced with the weight of the product from this shipping class in the cart<br><br>
    E.g: <kbd>2 * [weight]</kbd><br><br>
    E.g: <kbd>- 2 * [weight]</kbd><br><br>
    E.g: <kbd>2 * ([weight] - 10)</kbd>
    </p>
    </div>
</div>

<div id="selection_rule_help"  style="display:none;">
    <div class="bootstrap-wrapper">
    This rules decide if this fees will be applied or not
    </p>
    </div>
</div>

<div id="inc_dec_fees_help"  style="display:none;">
    <div class="bootstrap-wrapper">
    Using this you can adjust the amount of fees that will be applied when the fees is active
    <br><br>E.g: say you wat to apply a fees of $10 if customer is buying $100 worth of product from your site, and $12 if the customer is buying $101 to $500 worth of product from your site. you can do that using this Increment decrement fees setting
    </p>
    </div>
</div>

<div id="fee_charge_short_code_help"  style="display:none;">
    <div class="bootstrap-wrapper">
    <h2>Available short codes</h2>
    <p>
    E.g: You configured a category based rule (product from category A) and now cart is like this:<br><br>

    Product A x 2 unit  (belong to category A)<br><br> 

    Product B x 3 unit (belong to category A)<br><br>

    product C x 1 unit (belong to category C)<br><br>

    <kbd>[selected_product_count]</kbd> will result in 2  (product A and B are from category A)<br><br>

    <kbd>[selected_product_qty]</kbd> will result in 5 (2+3)<br><br>

    <kbd>[qty]</kbd> will result in 6 (2+3+1)
    </p>
    <hr>
    <p><kbd>[selected_product_qty]</kbd> in the fee this will get replaced with the quantity of product that are selected based on the product selection rule set in the fee  Selection rule</p>

    <p>so if you have made the fee to be applied when product from category A are present and you have made the fee as  <kbd>5 * [selected_product_qty]</kbd> then if 3 product from category A are present the fees will be <kbd>5 * 3 = 15</kbd></p>

    <p>If you use short code [selected_product_qty] and you have not used any product selection rule in the fee then this will lead to 0 so no fee will be applied</p>

    <p>Product selection rule are: Cart has product, Cart has product of category, Shipping class</p>

    <hr>

    <p><kbd>[qty]</kbd>, this short code will get replaced with the quantity of the product that are present in the cart, this is not dependent on the product selection rule</p>

    <hr>
    <p><kbd>[selected_product_count]</kbd> if you want to get the fees based on the presence of different product from selection rule then use this short code,<br>
    </p>
    <hr>
    
    </div>
</div>