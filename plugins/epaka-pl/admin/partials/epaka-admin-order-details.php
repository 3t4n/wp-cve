<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php if(is_array($epakaOrder)){ ?>
<div class="epaka-order-details-container">
    <div class="epaka-card">
        <div class="epaka-card-container">
            <h4><b>Szczegóły Zamówienia</b></h4>
            <p>
                <span>Identyfikator: <?php echo (!empty($epakaOrder['id']) ? "<a href='".EPAKA_DOMAIN."zamowienie/szczegoly/".$epakaOrder['id']."' target='_blank'>".$epakaOrder['id']."</a>":"")?></span><br/>
                <span>Numer listu: <?php echo (!empty($epakaOrder['labelNumber']) ? "<a href='".EPAKA_DOMAIN."sledzenie-przesylek/".$epakaOrder['labelNumber']."' target='_blank'>>".$epakaOrder['labelNumber']."</a>" : "brak")?></span><br/>
                <span>Status: <?php echo (!empty($epakaOrder['orderStatus']) ? $epakaOrder['orderStatus'] : "")?></span><br/>
                <span>Zawartość: <?php echo (!empty($epakaOrder['content']) ? $epakaOrder['content'] : "")?></span><br/>
            </p>
            <h4><b>Odbiorca</b></h4> 
            <p>
                <?php if(!empty($epakaOrder['receiverName']) || !empty($epakaOrder['receiverLastName'])){ ?>
                    <span><?php echo (!empty($epakaOrder['receiverName']) ? $epakaOrder['receiverName']: "")?> <?php echo (!empty($epakaOrder['receiverLastName']) ? $epakaOrder['receiverLastName'] : "")?></span><br/>
                <?php } ?>
                <?php if(!empty($epakaOrder['receiverCompany'])){ ?>
                    <span><?php echo $epakaOrder['receiverCompany']?></span><br/>
                <?php } ?>
                <?php if(!empty($epakaOrder['receiverStreet']) || !empty($epakaOrder['receiverHouseNumber']) || !empty($epakaOrder['receiverFlatNumber'])){ ?>
                    <span><?php echo (!empty($epakaOrder['receiverStreet']) ? $epakaOrder['receiverStreet'] : "")?> <?php echo (!empty($epakaOrder['receiverHouseNumber']) ? $epakaOrder['receiverHouseNumber'] : "")?><?php echo (!empty($epakaOrder['receiverFlatNumber'])) ? "/".$epakaOrder['receiverFlatNumber'] : ""?></span><br/>
                <?php } ?>
                <?php if(!empty($epakaOrder['receiverPostCode']) || !empty($epakaOrder['receiverCity']) || !empty($epakaOrder['receiverCountry'])){ ?>
                    <span><?php echo (!empty($epakaOrder['receiverPostCode']) ? $epakaOrder['receiverPostCode'] : "")?> <?php echo (!empty($epakaOrder['receiverCity']) ? $epakaOrder['receiverCity'] : "")?> <?php echo (!empty($epakaOrder['receiverCountry']) ? $epakaOrder['receiverCountry'] : "")?></span><br/>
                <?php } ?>
                <?php if(!empty($epakaOrder['receiverMachineDescription'])) {?><span><?php echo $epakaOrder['receiverMachineDescription']?></span><br/><?php } ?>
                <br/>
                <span>tel. <?php echo (!empty($epakaOrder['receiverPhone']) ? "<a href='tel:".$epakaOrder['receiverPhone']."'>".$epakaOrder['receiverPhone']."</a>" : "")?></span><br/>
                <span><?php echo (!empty($epakaOrder['receiverEmail']) ? "<a href='mailto:".$epakaOrder['receiverEmail']."'>".$epakaOrder['receiverEmail']."</a>" : "")?></span><br/>
            </p> 
        </div>
    </div>
    <div class="epaka-card">
        <div class="epaka-card-container">
            <center><h4><b>Tracking <span><?php echo (!empty($epakaOrder['courier']) ? $epakaOrder['courier'] : "")?></span><br/></b></h4></center>
            <table class="epaka-order-tracking">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Godzina</th>
                        <th>Status</th>
                        <th>Terminal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="epaka-tracking-loading" style="display: none;">
                        <td colspan="4">
                            <center>
                                <div class="lds-roller-container">
                                    <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                                </div>
                            </center>
                        </td>
                    </tr>
                </tbody>
            </table> 
        </div>
    </div>
    <div class="epaka-card">
        <div class="epaka-card-container">
            <div class="epaka-order-options">
                <?php if($epakaOrder['orderStatus'] == "oczekuje na płatność"){ ?><a class="epaka-box-button" onclick="window.openPaymentForOrder('<?php echo (!empty($epakaPayment->paymentUrl) ? $epakaPayment->paymentUrl->__toString() : '')?>')"><i class="epaka-icons icon-wallet-1"></i><span>Zapłać</span></a><?php } ?>
                <?php if($epakaOrder['labelAvailable'] == "1"){ ?><a class="epaka-box-button" onclick="window.getEpakaOrderLabel('<?php echo $epakaOrder['id']?>')"><i class="epaka-icons icon-tag"></i><span>Etykieta</span></a><?php } ?>
                <?php if($epakaOrder['labelZebraAvailable'] == "1"){ ?><a class="epaka-box-button" onclick="window.getEpakaOrderLabelZebra('<?php echo $epakaOrder['id']?>')"><i class="epaka-icons icon-tag"></i><span>Zebra</span></a><?php } ?>
                <?php if($epakaOrder['protocolAvailable'] == "1"){ ?><a class="epaka-box-button" onclick="window.getEpakaOrderProtocol('<?php echo $epakaOrder['id']?>')"><i class="epaka-icons icon-download"></i><span>Protokół</span></a><?php } ?>
                <?php if($epakaOrder['proformaAvailable'] == "1"){ ?><a class="epaka-box-button" onclick="window.getEpakaOrderProforma('<?php echo $epakaOrder['id']?>')"><i class="epaka-icons icon-download"></i><span>Proforma</span></a><?php } ?>
                <?php if($epakaOrder['authorizationDocumentAvailable'] == "1"){ ?><a class="epaka-box-button" onclick="window.getEpakaOrderAuthorizationDocument('<?php echo $epakaOrder['id']?>')"><i class="epaka-icons icon-download"></i><span>Upoważnienie</span></a><?php } ?>
                <?php if($epakaOrder['orderStatus'] == "zakończone"){?><a class="epaka-box-button" onclick="window.cancelEpakaOrder('<?php echo $epakaOrder['id']?>')"><i class="epaka-icons icon-delete"></i><span>Anuluj</span></a><?php } ?>
                <a class="epaka-box-button" onclick="window.unlinkEpakaOrderFromWooOrder('<?php echo $post->ID?>')"><i class="epaka-icons icon-courier"></i><span>Nowe Zamówienie</span></a>
            </div>
        </div>
    </div>
</div>

<script>
    window.setParcelNumberToTrack('<?php echo (!empty($epakaOrder['labelNumber']) ? $epakaOrder['labelNumber'] : "")?>');
</script>
<?php }else{ ?>
    <center><span>Nie udało się załadować zamówienia</span></center>
    <a class="epaka-box-button" onclick="window.unlinkEpakaOrderFromWooOrder('<?php echo $post->ID?>')"><i class="epaka-icons icon-courier"></i><span>Nowe Zamówienie</span></a>
<?php } ?>