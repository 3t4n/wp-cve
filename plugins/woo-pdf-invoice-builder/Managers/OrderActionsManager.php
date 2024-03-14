<?php

namespace rnwcinv\Managers;

use RednaoWooCommercePDFInvoice;

class OrderActionsManager
{
    private static $OrderActionsCache=null;
    public static function GetOrderActions(){
        if(OrderActionsManager::$OrderActionsCache==null)
        {
            OrderActionsManager::$OrderActionsCache=[];
            global $wpdb;
            $results=$wpdb->get_results('select invoice_id InvoiceId,name Name,order_actions OrderActions from '.RednaoWooCommercePDFInvoice::$INVOICE_TABLE.' where order_actions is not null');
            foreach($results as $currentRow)
            {
                $decoded=json_decode($currentRow->OrderActions);
                if($decoded==null||!is_array($decoded))
                    continue;

                foreach($decoded as $orderActionItem)
                {
                    if(is_object($orderActionItem)&&isset($orderActionItem->Id)&&isset($orderActionItem->Icon))
                    {
                        $orderActionItem->Name=$currentRow->Name;
                        $orderActionItem->InvoiceId=$currentRow->InvoiceId;
                        OrderActionsManager::$OrderActionsCache[]=$orderActionItem;
                    }
                }

            }

        }

        return OrderActionsManager::$OrderActionsCache;
    }

}