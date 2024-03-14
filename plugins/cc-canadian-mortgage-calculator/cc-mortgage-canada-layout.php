<?php
function load_cc_mortgage_canada_calc($id, $title, $currency, $show_url = 0, $bg_color, $border_color, $text_color)
{
    if ($show_url == 1)
        load_ccmc_custom_colors($id, $bg_color, $border_color, $text_color);
?>

<input type="hidden" id="<?php echo $id; ?>-currency" value="<?php echo $currency; ?>"> 
<div class="CCMC-Widget CCMC-Widget-<?php echo $id; ?>">
	 	<div class="CCMC-WidgetTitle CCMC-WidgetTitle-<?php echo $id; ?>"><?php echo $title; ?></div>		   
        <div class="CCMC-rowdiv">
            <div class="CCMC-leftdiv">
                <label for="<?php echo $id; ?>-purchase-price">Purchase price <?php echo $currency; ?> :</label>
            </div>
			<div class="CCMC-rightdiv">
                <input id="<?php echo $id; ?>-purchase-price" class="ca-purchase-price" type="text" placeholder="purchase price">
			</div>
        </div>
        <div class="CCMC-rowdiv">
            <div class="CCMC-leftdiv">
                <label for="<?php echo $id; ?>-down-payment">Down payment <?php echo $currency; ?> :</label>
            </div>
			<div class="CCMC-rightdiv">
                <input id="<?php echo $id; ?>-down-payment" class="ca-down-payment" type="text" placeholder="enter down payment ">
			</div>
        </div>
        <div class="CCMC-rowdiv">
            <div class="CCMC-leftdiv">
                <label for="<?php echo $id; ?>-mortgage-term">Mortgage term (years) :</label>
            </div>
			<div class="CCMC-rightdiv">
                <input id="<?php echo $id; ?>-mortgage-term" class="ca-mortgage-term" type="text" placeholder="enter term">
			</div>
        </div>
        <div class="CCMC-rowdiv">
			<div class="CCMC-leftdiv">
                <label for="<?php echo $id; ?>-mortgage-rate">Interest rate % :</label>
			 </div>
			 <div class="CCMC-rightdiv">
                <input id="<?php echo $id; ?>-mortgage-rate" class="ca-mortgage-rate" type="text" placeholder="enter rate">
			 </div>
        </div>
        <div class="CCMC-rowdiv">
    		<div class="CCMC-WidgetLine CCMC-WidgetLine-<?php echo $id; ?>"></div>
		</div>
        <div class="CCMC-rowdiv">
			 <div class="CCMC-leftresultdiv">
                <label for="<?php echo $id; ?>-mortgage">Mortgage amount :</label>
			 </div>
			 <div class="CCMC-rightresultdiv">
                <span id="<?php echo $id; ?>-mortgage" class=""></span>
			 </div>
        </div>
        <div class="CCMC-rowdiv">
			 <div class="CCMC-leftresultdiv">
                <label for="<?php echo $id; ?>-monthlyPayment">Monthly payment :</label>
			 </div>
			 <div class="CCMC-rightresultdiv">
                <span id="<?php echo $id; ?>-monthlyPayment" class=""></span>
			 </div>
        </div>
        <?php if ($show_url) { ?>
    		<div class="CCMC-rowdiv" >
                <div class="CCMC-WidgetSignature CCMC-WidgetSignature-<?php echo $id; ?>">Provided by <a href="https://calculatorscanada.ca/mortgage-calculator/" target="_blank">CalculatorsCanada.ca</a></div>
		    </div>
        <?php } ?>
		
</div>

		
		<?php 
}


function load_ccmc_custom_colors($id, $bg_color, $border_color, $text_color)
{
?>
<style type="text/css">
.CCMC-Widget-<?php echo $id; ?>, .CCMC-WidgetTitle-<?php echo $id; ?> a, .CCMC-WidgetTitle-<?php echo $id; ?> a:visited, .CCMC-WidgetSignature-<?php echo $id; ?> a, .CCMC-WidgetSignature-<?php echo $id; ?> a:visited, .CCMC-WidgetLine-<?php echo $id; ?> {
    <?php echo (isset( $border_color) ? "border-color:" . $border_color . ";" : ""); ?>
    <?php echo (isset( $bg_color) ? "background-color:" . $bg_color . ";": ""); ?>
    <?php echo (isset( $text_color) ? "color:" . $text_color . " !important;": ""); ?>
}

.CCMC-Widget-<?php echo $id; ?> input[type=text] {
    <?php echo (isset( $border_color) ? "border-color:" . $border_color . ";": ""); ?>
    <?php echo (isset( $text_color) ? "color:" . $text_color . ";": ""); ?>
    <?php echo (isset( $input_bg_color) ? "background-color:" . $input_bg_color . ";": ""); ?>
} 
</style>
<?php 
}
?>