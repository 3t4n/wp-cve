<?php

class PMLC_Rule_Record extends PMLC_Model_Record {
	/**
	 * Initialize model instance
	 * @param array[optional] $data Array of record data to initialize object with
	 */
	public function __construct($data = array()) {
		parent::__construct($data);
		$this->setTable(PMLC_Plugin::getInstance()->getTablePrefix() . 'rules');
	}
	
	/**
	 * Get single destination using weight rules defined
	 * @return PMLC_Destination_Record
	 */
	public function getDestination() {
		$r = mt_rand(1, 10000) / 100; $weight = 0;
		foreach ($this->getRelated('PMLC_Destination_List') as $destination) {
			$weight += $destination->weight;
			if ($r <= $weight) {
				return $destination;
			}
		}
		return NULL; // nothing found
	}
	/**
	 * Output html which is used to represent destination set
	 * @param string[optional] $url URL to output into <input /> box, applies only if destination_mode is set to `simple`
	 */
	public function render($url = NULL) {
		?>
		<?php if ('advanced' == PMLC_Plugin::getInstance()->getOption('destination_mode')): ?>
			<input type="hidden" name="destination_url[<?php echo $this->type ?>][]" value="<?php echo $this->getRelated('PMLC_Destination_Record')->getUrl() ?>" />
			<?php if (empty($this->id)): ?>
				<a href="admin.php?page=pmlc-admin-edit&action=destination&link_id=<?php echo $this->link_id ?>&type=<?php echo $this->type ?>" class="button destination-set empty"><span><?php _e('Destination Set', 'pmlc_plugin') ?></span></a>
			<?php else: ?>
				<a title="
					<?php foreach ($this->getRelated('PMLC_Destination_List') as $destination): ?>
						<div><?php echo $destination->url ?> - <?php echo $destination->weight ?>%</div>
					<?php endforeach ?>
					" href="admin.php?page=pmlc-admin-edit&action=destination&link_id=<?php echo $this->link_id ?>&type=<?php echo $this->type ?>&id=<?php echo $this->id ?>" class="button destination-set"><span><?php _e('Destination Set', 'pmlc_plugin') ?></span></a>
			<?php endif ?>
		<?php else: $url = ! is_null($url) ? $url : $this->getRelated('PMLC_Destination_Record')->getUrl() ?>
			<input type="text" class="regular-text" name="destination_url[<?php echo $this->type ?>][]" value="<?php echo esc_attr('' == $url ? 'http://' : $url) ?>" />
		<?php endif ?>
		<?php
	}
	
	/**
	 * Creates a copy of current rule in database and assigns it to link_id specified
	 * @param int $link_id
	 * @return PMLC_Rule_Record
	 * @chainable
	 */
	public function copyToLink($link_id) {
		$destinations = $this->getRelated('PMLC_Destination_List');
		unset($this->id); $this->link_id = $link_id; $this->insert();
		foreach ($destinations as $d) {
			unset($d->id); $d->rule_id = $this->id; $d->insert();
		}
		return $this;
	}
	
	/**
	 * see parent::delete()
	 */
	public function delete() {
		// cascade deletion
		foreach ($this->getRelated('PMLC_Destination_List') as $destination) {
			$destination->delete();
		}
		return parent::delete();
	}
	
}