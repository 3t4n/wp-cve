<?php
$system = $this->core->getSystem() ;

$cronChangeCatalog = get_option( 'bdroppy-cron-change-catalog-last-run',0);
$cronUpdateCatalog = get_option( 'bdroppy-cron-update-catalog-last-run',0);
$cronUpdateProduct = get_option( 'bdroppy-cron-update-product-last-run',0);
$cronQueues = get_option( 'bdroppy-cron-queue-last-run',0);
$cronCheckOrders = get_option( 'bdroppy-cron-sync-order-last-run',0);

?>
<div class="bdroppy_base" >
    <div class="container">
        <?php require __DIR__ . "/../adminBase.php" ?>

        <div class="bd-content">
            <div class="row information">
                <div class="col-sm-8">
                    <div class="card-box queues-card">
                        <div class="d-flex card-box-header"><span>Queues</span> <small class="ml-1" ></small><a class="ml-auto btn reload-queues" >reload</a> </div>

                        <div class="card-body px-0">
                            <div class="queues-items">

                            </div>
                            <div class="queues-actions">
                                <a class="loadingMorePage" style="display: none" data-target="1" href="javascript:;">Load More Item ...</a>
                            </div>
                        </div>
                        <div class="loading text-center p-5">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading ...
                        </div>

                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card-box system-info">
                        <div class="card-box-header">Cron Status</div>
                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td class="bold">Change Catalog</td>
                                    <td> <?= ($cronChangeCatalog == 0 ? 'not set ' : bdroppy_ago_time('@'.$cronChangeCatalog))  ?> </td>
                                </tr>
                                <tr>
                                    <td class="bold">Update Catalog</td>
                                    <td> <?= ($cronUpdateCatalog == 0 ? 'not set ' : bdroppy_ago_time('@'.$cronUpdateCatalog))  ?> </td>
                                </tr>
                                <tr>
                                    <td class="bold">Update Product</td>
                                    <td> <?= ($cronUpdateProduct == 0 ? 'not set ' : bdroppy_ago_time('@'.$cronUpdateProduct))  ?> </td>
                                </tr>
                                <tr>
                                    <td class="bold">Process Queues</td>
                                    <td> <?= ($cronQueues == 0 ? 'not set ' : bdroppy_ago_time('@'.$cronQueues))  ?> </td>
                                </tr>
                                <tr>
                                    <td class="bold">Check Orders</td>
                                    <td> <?= ($cronCheckOrders == 0 ? 'not set ' : bdroppy_ago_time('@'.$cronCheckOrders))  ?> </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-box system-info">
                        <div class="card-box-header">System Information</div>
                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td class="bold">OS</td>
                                    <td> <?= $system->info->getOS() ?> </td>
                                </tr>
                                <?php if($system->info->getCPULoadAvarage() != null) { ?>
                                <tr>
                                    <td class="bold">CPU Load average</td>
                                    <td> <?= $system->info->getCPULoadAvarage() ?> </td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td class="bold">Memory limit</td>
                                    <td> <?= $system->info->getMemoryLimit() ?> </td>
                                </tr>
                                <tr>
                                    <td class="bold">Max execution time</td>
                                    <td> <?= $system->info->getMaxExecutionTime() ?> </td>
                                </tr>
                                <tr>
                                    <td class="bold">Upload Max File size</td>
                                    <td> <?= $system->info->getUploadMaxFileSize() ?> </td>
                                </tr>
                                <tr>
                                    <td class="bold">Php Version</td>
                                    <td> <?= $system->info->getPhpVersion() ?> </td>
                                </tr>
                                <tr>
                                    <td class="bold">WordPress Version</td>
                                    <td> <?= $system->info->getWpVersion() ?> </td>
                                </tr>
                                <tr>
                                    <td class="bold">WooCommerce Version</td>
                                    <td> <?= $system->info->getWcVersion() ?> </td>
                                </tr>
<!--                                <tr>-->
<!--                                    <td class="bold">WPML <small>( For Multi Languages )</small></td>-->
<!--                                    <td> --><!-- </td>-->
<!--                                </tr>-->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
