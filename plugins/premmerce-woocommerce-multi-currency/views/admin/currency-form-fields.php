<?php

defined( 'WPINC' ) || die;
$currencyDataCountriesType = ( isset( $currencyData['countries_type'] ) ? $currencyData['countries_type'] : 'include' );
$currencyDataCountriesList = ( isset( $currencyData['countries'] ) ? $currencyData['countries'] : [] );
$currencyDataUpdater = ( isset( $currencyData['updater'] ) ? $currencyData['updater'] : '' );
$isMainCurrency = ( isset( $currencyData['id'] ) ? $currencyData['id'] == $mainCurrencyId : '' );
$disabled = ( isset( $currencyData ) ? 'disabled title="' . __( 'Main currency rate must be equal to 1 and cannot be changed', 'premmerce-woocommerce-multicurrency' ) . '"' : '' );
$required = ( isset( $currencyData ) ? 'required' : '' );
$displayOnFront = ( (bool) isset( $currencyData ) ? $currencyData['display_on_front'] : true );
?>

<div class="form-field form-required">
    <label for="currency-name"><?php 
_e( 'Currency name', 'premmerce-woocommerce-multicurrency' );
?></label>
    <input name="currency-name" id="currency-name" type="text"
           value="<?php 
echo  ( isset( $currencyData ) ? $currencyData['currency_name'] : '' ) ;
?>" size="40" required="true"/>
    <p><?php 
_e( 'Will be displayed in currency selector at the site frontend.', 'premmerce-woocommerce-multicurrency' );
?></p>
</div>


<?php 

if ( 'add-currency' === $page ) {
    ?>
    <div class="form-field form-required">
        <label for="currency-code"><?php 
    _e( 'International currency code', 'premmerce-woocommerce-multicurrency' );
    ?></label>
        <select id="currency-code" name="currency-code" required="true">
            <?php 
    foreach ( get_woocommerce_currencies() as $currencyCode => $currencyName ) {
        ?>
                <option value="<?php 
        echo  $currencyCode ;
        ?>"<?php 
        ?>><?php 
        echo  $currencyCode . ' (' . $currencyName . ')' ;
        ?></option>
            <?php 
    }
    ?>
        </select>
    </div>
<?php 
}

?>

<?php 
?>


<div class="form-field form-required">
    <label for="currency-symbol"><?php 
_e( 'International currency symbol', 'premmerce-woocommerce-multicurrency' );
?></label>
    <input name="currency-symbol" id="currency-symbol" type="text"
           value="<?php 
echo  ( isset( $currencyData ) ? $currencyData['symbol'] : '' ) ;
?>" size="5" required="true"/>
</div>

<div class="form-field form-required">
    <label for="currency-position"><?php 
_e( 'Currency position', 'premmerce-woocommerce-multicurrency' );
?></label>
    <select id="currency-position" name="currency-position">
        <?php 
$position = ( isset( $currencyData ) ? $currencyData['position'] : '' );
?>
        <option value="left" <?php 
selected( $position, 'left' );
?>> <?php 
_e( 'Left', 'premmerce-woocommerce-multicurrency' );
?> </option>
        <option value="right" <?php 
selected( $position, 'right' );
?> > <?php 
_e( 'Right', 'premmerce-woocommerce-multicurrency' );
?> </option>
        <option value="left_space" <?php 
selected( $position, 'left_space' );
?>> <?php 
_e( 'Left with space', 'premmerce-woocommerce-multicurrency' );
?> </option>
        <option value="right_space" <?php 
selected( $position, 'right_space' );
?>> <?php 
_e( 'Right with space', 'premmerce-woocommerce-multicurrency' );
?> </option>
    </select>
    <p><?php 
_e( 'This controls the position of the currency symbol.', 'premmerce-woocommerce-multicurrency' );
?></p>
</div>

<div class="form-field form-required">
    <label for="currency-decimal-separator"><?php 
_e( 'Decimal separator', 'premmerce-woocommerce-multicurrency' );
?></label>
    <input name="currency-decimal-separator" id="currency-decimal-separator" type="text"
           value="<?php 
echo  ( isset( $currencyData ) ? $currencyData['decimal_separator'] : '.' ) ;
?>" required="required">
</div>

<div class="form-field form-required">
    <label for="currency-thousand-separator"><?php 
_e( 'Thousand separator', 'premmerce-woocommerce-multicurrency' );
?></label>
    <input name="currency-thousand-separator" id="currency-thousand-separator" type="text"
           value="<?php 
echo  ( isset( $currencyData ) ? $currencyData['thousand_separator'] : '' ) ;
?>">
</div>

<div class="form-field form-required">
    <label for="currency-decimals-num"><?php 
_e( 'Number of decimals', 'premmerce-woocommerce-multicurrency' );
?></label>
    <input name="currency-decimals-num" id="currency-decimals-num" type="number" min="0"
           value="<?php 
echo  ( isset( $currencyData ) ? $currencyData['decimals_num'] : '2' ) ;
?>">
</div>



    <div class="form-field form-required">
        <label for="currency-rate"><?php 
_e( 'Exchange rate', 'premmerce-woocommerce-multicurrency' );
?></label>
        <?php 
if ( !$isMainCurrency ) {
    ?> 1 <span
                class="premmerce-multicurrency-edited-currency-code"> </span> = <?php 
}
?>
        <input class="premmerce-multicurrency-short-field" name="currency-rate" id="currency-rate" type="number"
               value="<?php 
echo  ( isset( $currencyData ) ? $currencyData['rate'] : '' ) ;
?>" size="5" step="any"
               min=0 <?php 
echo  ( $isMainCurrency ? $disabled : $required ) ;
?> />
        <?php 

if ( !$isMainCurrency ) {
    ?> <span
                class="premmerce-multicurrency-main-currency-code"> <?php 
    echo  $mainCurrencyCode ;
    ?> </span> <?php 
}

?>
        <p><?php 
_e( 'Currency exchange rate relatively to main shop currency.', 'premmerce-woocommerce-multicurrency' );
?></p>
    </div>

    <?php 

if ( !$isMainCurrency ) {
    ?>

        <div class="form-field">
            <label for="currency-rate-inv"><?php 
    _e( 'Inverted exchange rate', 'premmerce-woocommerce-multicurrency' );
    ?></label>
            1 <span class="premmerce-multicurrency-main-currency-code"><?php 
    echo  $mainCurrencyCode ;
    ?> </span> =
            <input class="premmerce-multicurrency-short-field" name="currency-rate-inv" id="currency-rate-inv" type="number"
                   value="" size="5" step="any" min=0 />
            <span class="premmerce-multicurrency-edited-currency-code"> </span>
            <p><?php 
    _e( 'Inverted currency exchange rate relatively to main shop currency.', 'premmerce-woocommerce-multicurrency' );
    ?></p>
        </div>


        <?php 
    ?>

<?php 
}

/* ! $isMainCurrency */
?>




<div class="form-field">
    <label><input name="currency-display-on-front" id="currency-display-on-front" type="checkbox" value="1"
            <?php 
checked( $displayOnFront );
?>/> <?php 
_e( 'Available for user on frontend', 'premmerce-woocommerce-multicurrency' );
?></label>
</div>
