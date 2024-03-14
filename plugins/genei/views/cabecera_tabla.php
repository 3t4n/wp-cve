<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly <div class="wrap">    
?>
<?php
echo('<script>var iva = "'.$iva.'";var iva_exento = "'.$iva_exento.'";</script>');
?>

<label id="label_switch_iva_parent" class="switch"><input type="checkbox" id="switch_iva" class="switch-input" checked=""><span class="switch-label" id="label_switch_iva" data-on="<?=__('sin IVA');?>" data-off="<?=__('con IVA');?>"></span><span class="switch-handle"></span></label>
<table class="table table-striped">
    <thead class="thead-dark">
        <tr>
            <th></th>
            <th><?=__('Nombre agencia');?></th>
            <th></th>
            <th></th>
            <th><?=__('Plazo entrega');?></th>
            <th><?=__('Importe');?></th>
            <th></th>
    </thead>
    <tbody>