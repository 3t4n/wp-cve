<?php
/**
 * Get all receivable tax ledger
 *
 * @return array
 */
function wphr_ac_update_1_1_9_get_tax_receivable_ledger() {
    $all_tax_id = array_keys( wphr_ac_get_tax_dropdown() );

    $receivables = WPHR\HR_MANAGER\Accounting\Model\Ledger::whereIn( 'tax', $all_tax_id )->with(['charts' => function( $q ) {
                return $q->where( 'class_id', '=', 1 );
            }])->get()->toArray();

    foreach ( $receivables as $key => $receivable ) {
        if ( ! count( $receivable['charts'] ) ) {
            unset( $receivables[$key] );
        }
    }

    return $receivables;
}


$receivable_tax = wphr_ac_update_1_1_9_get_tax_receivable_ledger();
$receivable_taxs_id =  wp_list_pluck( $receivable_tax, 'id' );
\WPHR\HR_MANAGER\Accounting\Model\Ledger::whereIn( 'id', $receivable_taxs_id )->update( ['type_id' => 12] );
