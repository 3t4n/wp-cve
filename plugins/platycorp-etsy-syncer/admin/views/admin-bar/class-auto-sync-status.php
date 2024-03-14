<?php
namespace platy\etsy\admin;

use platy\etsy\EtsyStockSyncer;
use platy\etsy\orders\EtsyOrdersSyncer;
use platy\etsy\EtsyDataService;

class AutoSyncStatus {
    
	public function load_admin_bar_status_menu() {
		global $wp_admin_bar;
		
        $data = EtsyDataService::get_instance();
		$shops = $data->get_shops();
		$shops_w_stock = [];
        $issue_exists = false;
		foreach($shops as $shop) {
			$shop_id = $shop['id'];
			if(EtsyStockSyncer::is_auto_stock_managed($shop_id)) {
				$shops_w_stock[] = $shop;
			}
		}
		$shops_w_orders = [];
		foreach($shops as $shop) {
			$shop_id = $shop['id'];
			if(EtsyOrdersSyncer::is_auto_orders_managed($shop_id)) {
				$shops_w_orders[] = $shop;
			}
		}

		if(empty($shops_w_orders) && empty($shops_w_stock)) {
			return;
		}

		foreach($shops_w_stock as $shop){
			$shop_name = $shop['name'];
			$cron_stock_status = EtsyStockSyncer::is_cron_status_ok($shop['id']);
            $issue_exists = $issue_exists || !$cron_stock_status;
			$color = $cron_stock_status ? "green" : "red";
			ob_start();
			?>
				<span>
					<span style="height: 15px; width: 15px; margin-right: 7px;
						background-color: <?php echo $color; ?>; border-radius: 50%; display: inline-block;"></span>
					<span><?php echo $shop_name; ?> Stock Sync</span>
				</span>
			<?php
			$html = ob_get_clean();
	
			$wp_admin_bar->add_menu( array(
				'id' => 'platy-syncer-etsy-stock-status-bar',
				'title' => "$html",
				'parent' => 'platy-syncer-etsy-status-bar',
				'meta'   => array(
					'target'   => '_self'
				),
		
			));
	
		}

		foreach($shops_w_orders as $shop){
			$shop_name = $shop['name'];
			$cron_orders_status = EtsyOrdersSyncer::is_cron_status_ok($shop['id']);
			$color = $cron_orders_status ? "green" : "red";
            $issue_exists = $issue_exists || !$cron_orders_status;
			ob_start();
			?>
				<span>
					<span style="height: 15px; width: 15px; margin-right: 7px;
						background-color: <?php echo $color; ?>; border-radius: 50%; display: inline-block;"></span>
					<span><?php echo $shop_name; ?> Orders Sync</span>
				</span>
			<?php
			$html = ob_get_clean();
	
            
			$wp_admin_bar->add_menu( array(
				'id' => 'platy-syncer-etsy-orders-status-bar',
				'title' => "$html",
				'parent' => 'platy-syncer-etsy-status-bar',
				'meta'   => array(
					'target'   => '_self'
				),
		
			));
	
		}

        $color = !$issue_exists ? "green" : "red";
        ob_start();
			?>
				<span>
					<span style="height: 15px; width: 15px; margin-right: 7px;
						background-color: <?php echo $color; ?>; border-radius: 50%; display: inline-block;"></span>
					<span> Etsy Status: <?php echo $issue_exists ? "Issue detected" : "Ok"; ?></span>
				</span>
			<?php
			$html = ob_get_clean();
		
        $wp_admin_bar->add_menu( array(
            'id' => 'platy-syncer-etsy-status-bar',
            'title' => "$html",
            'meta'   => array(
                'target'   => '_self'
            ),
    
        ));

	}

}