<?php
/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

class GJMAA_Model_Auctions extends GJMAA_Model
{

    protected $tableName = 'gjmaa_auctions';

    protected $oldTable = 'gj_auctions_item';

    protected $defaultPK = 'id';

    protected $model = 'auctions';

    protected $mapColumns = [
        'auction_id'          => 'auction_id',
        'auction_settings_id' => 'auction_profile_id',
        'auction_name'        => 'auction_name',
        'auction_price'       => 'auction_price',
        'auction_bid'         => 'auction_bid_price',
        'auction_image'       => 'auction_images',
        'auction_user'        => 'auction_seller'
    ];

    protected $columns = [
        'id'                                => [
            'schema' => [
                'INT',
                'AUTO_INCREMENT',
                'NOT NULL'
            ],
            'format' => '%d'
        ],
        'auction_id'                        => [
            'schema' => [
                'BIGINT',
                'NOT NULL'
            ],
            'format' => '%f'
        ],
        'auction_profile_id'                => [
            'schema' => [
                'INT',
                'NOT NULL'
            ],
            'format' => '%d'
        ],
        'auction_name'                      => [
            'schema' => [
                'VARCHAR (255)',
                'NOT NULL'
            ],
            'format' => '%s'
        ],
        'auction_details'                   => [
            'schema' => [
                'LONGTEXT',
                'NULL'
            ],
            'format' => '%s'
        ],
        'auction_price'                     => [
            'schema' => [
                'FLOAT (10,2)',
                'NULL'
            ],
            'format' => '%s'
        ],
        'auction_bid_price'                 => [
            'schema' => [
                'FLOAT (10,2)',
                'NULL'
            ],
            'format' => '%s'
        ],
        'auction_images'                    => [
            'schema' => [
                'TEXT',
                'NULL'
            ],
            'format' => '%s'
        ],
        'auction_seller'                    => [
            'schema' => [
                'BIGINT',
                'NULL'
            ],
            'format' => '%f'
        ],
        'auction_attributes'                => [
            'schema' => [
                'TEXT',
                'NULL'
            ],
            'format' => '%s'
        ],
        'auction_categories'                => [
            'schema' => [
                'TEXT',
                'NULL'
            ],
            'format' => '%s'
        ],
        'auction_time'                      => [
            'schema' => [
                'DATETIME',
                'NULL'
            ],
            'format' => '%s'
        ],
        'auction_quantity'                  => [
            'schema' => [
                'INT',
                'NULL'
            ],
            'format' => '%d'
        ],
        'auction_clicks'                    => [
            'schema' => [
                'BIGINT',
                'NULL',
                'DEFAULT 0'
            ],
            'format' => '%f'
        ],
        'auction_visits'                    => [
            'schema' => [
                'BIGINT',
                'NULL',
                'DEFAULT 0'
            ],
            'format' => '%f'
        ],
        'auction_sort_order'                => [
            'schema' => [
                'INT',
                'NULL'
            ],
            'format' => '%d'
        ],
        'auction_updated_at'                => [
            'schema' => [
                'DATETIME',
                'NULL'
            ],
            'format' => '%s'
        ],
        'auction_status'                    => [
            'schema' => [
                'VARCHAR(30)',
                'NULL',
                'DEFAULT "ACTIVE"'
            ],
            'format' => '%s'
        ],
        'auction_in_woocommerce'            => [
            'schema' => [
                'INT',
                'NULL',
                'DEFAULT 0'
            ],
            'format' => '%d'
        ],
        'auction_woocommerce_id'            => [
            'schema' => [
                'INT',
                'NULL',
                'DEFAULT 0'
            ],
            'format' => '%d'
        ],
        'auction_update_woocommerce_stock'  => [
            'schema' => [
                'INT (2)',
                'NULL',
                'DEFAULT 1'
            ],
            'format' => '%d'
        ],
        'auction_update_woocommerce_price'  => [
            'schema' => [
                'INT (2)',
                'NULL',
                'DEFAULT 1'
            ],
            'format' => '%d'
        ],
        'auction_update_woocommerce_fields' => [
            'schema' => [
                'INT (2)',
                'NULL',
                'DEFAULT 1'
            ],
            'format' => '%d'
        ],
	    'auction_external_id' => [
		    'schema' => [
			    'VARCHAR (255)',
			    'NULL'
		    ],
		    'format' => '%s'
	    ]
    ];

    public function update($currentVersion)
    {
        if (version_compare($currentVersion, '2.0.0') < 0) {
            $this->migerateFromOldToNew();
        }

        if (version_compare($currentVersion, '2.0.2') < 0) {
            $this->install();
        }

        if (version_compare($currentVersion, '2.0.20') < 0) {
            $this->addSortOrderColumn();
        }

        if (version_compare($currentVersion, '2.2.0') < 0) {
            $this->addAuctionStatus();
        }

        if (version_compare($currentVersion, '2.2.4') < 0) {
            $this->modifyAuctionInWooCommerceColumn();
        }

        if (version_compare($currentVersion, '2.3.1') < 0) {
            $this->addColumnAuctionWooCommerceId();
        }

        if (version_compare($currentVersion, '2.7.0') < 0) {
            $this->removeAllAuctionsWithAuctionIdEqualZero();
        }

        if (version_compare($currentVersion, '2.8.0') < 0) {
            $this->addToWooCommerceUpdateFlag();
        }

        if (version_compare($currentVersion, '2.9.0') < 0) {
            $this->addToWooCommerceUpdateFieldsFlag();
        }

        if (version_compare($currentVersion, '3.0.1') < 0) {
            $this->addToWooCommerceAuctionDetailsField();
        }

	    if (version_compare($currentVersion, '3.5.0') < 0) {
		    $this->addExternalAuctionId();
	    }

        return $this;
    }

    public function migerateFromOldToNew()
    {
        $this->install();
        $mainTable       = $this->tableName;
        $this->tableName = $this->oldTable;
        $allEntries      = $this->getAll();
        $this->tableName = $mainTable;

        foreach ($allEntries as $auction) {
            $this->unsetData();
            foreach ($this->mapColumns as $oldColumnName => $newColumnName) {
                if ($oldColumnName == 'auction_image') {
                    $images                    = [];
                    $images[0]                 = new stdClass();
                    $images[0]->url            = $auction[ $oldColumnName ];
                    $auction[ $oldColumnName ] = json_encode($images);
                }
                $this->setData($newColumnName, $auction[ $oldColumnName ]);
            }
            $this->migrate();
        }

        return true;
    }

    public function getCountOfTotalAuctionClicks()
    {
        return $this->getWpdb()->get_var('SELECT SUM(IFNULL(auction_clicks,0)) FROM ' . $this->getTable()) ?: 0;
    }

    public function getCountOfTotalAuctionVisits()
    {
        return $this->getWpdb()->get_var('SELECT SUM(IFNULL(auction_visits,0)) FROM ' . $this->getTable()) ?: 0;
    }

    public function getRowBySearch($filters, $limit = 25)
    {
        $querySelect = "SELECT * FROM " . $this->getTable();
        $where       = '';
        foreach ($filters as $schema => $value) {
            $where .= $schema . " " . $value . " ";
        }

        $querySelect .= " " . $where;

        $row = $this->getWpdb()->get_row($querySelect, ARRAY_A);

        return $this->setData($row);
    }

    public function getAllBySearch($filters, $limit = 25)
    {
        $querySelect = "SELECT * FROM " . $this->getTable();
        $where       = '';
        foreach ($filters as $schema => $value) {
            $where .= $schema . " " . $value . " ";
        }

        $querySelect .= " " . $where;

        $rows = $this->getWpdb()->get_results($querySelect, ARRAY_A);

        return $rows;
    }

    public function collect($auctionId, $profileId, $type = 'clicks')
    {
        $column = 'auction_' . $type;
        $set    = $column . ' = ' . $column . ' + 1';
        $where  = sprintf('auction_id = %s AND auction_profile_id = %d', $auctionId, $profileId);

        $this->getWpdb()->query(sprintf("UPDATE %s SET %s WHERE %s", $this->getTable(), $set, $where));
    }

    public function getMostPopularAuctions($count)
    {
        $filters = [
            'ORDER BY' => 'auction_clicks DESC, auction_visits DESC',
            'LIMIT'    => $count
        ];

        return $this->getAllBySearch($filters);
    }

    public function getNewestAuctions($count)
    {
        $filters = [
            'WHERE'    => 'auction_time IS NOT NULL AND auction_time > NOW()',
            'ORDER BY' => 'auction_time DESC',
            'LIMIT'    => $count
        ];

        return $this->getAllBySearch($filters);
    }

    public function getLastMinuteAuctions($count)
    {
        $filters = [
            'WHERE'    => 'auction_time IS NOT NULL AND auction_time > NOW()',
            'ORDER BY' => 'auction_time ASC',
            'LIMIT'    => $count
        ];

        return $this->getAllBySearch($filters);
    }

    public function getLowStockAuctions($count)
    {
        $filters = [
            'WHERE'    => 'auction_quantity IS NOT NULL AND auction_quantity BETWEEN 1 AND 10 AND auction_time > NOW()',
            'ORDER BY' => 'auction_quantity ASC',
            'LIMIT'    => $count
        ];

        return $this->getAllBySearch($filters);
    }

    public function updateData($id, $data)
    {
        $data['auction_updated_at'] = date('Y-m-d H:i:s');

        return parent::updateData($id, $data);
    }

    public function deleteByProfileId($profileId)
    {
        $where = 'auction_profile_id = ' . $profileId;
        $this->getWpdb()->query(sprintf("DELETE FROM %s WHERE %s", $this->getTable(), $where));
    }

    public function addSortOrderColumn()
    {
        $column = 'auction_sort_order';
        $schema = [
            'INT',
            'NULL'
        ];
        $after  = 'auction_visits';

        $this->addColumn($this->getTable(), $column, $schema, $after);
    }

    public function removeAuctionsByIds($auctionIds = null)
    {
        if (is_null($auctionIds)) {
            return;
        }

        if ( ! is_array($auctionIds)) {
            $auctionIds = [
                $auctionIds
            ];
        }

        $where = sprintf('auction_id IN(%s)', implode(',', $auctionIds));

        $this->getWpdb()->query(sprintf("DELETE FROM %s WHERE %s", $this->getTable(), $where));
    }

    public function addAuctionStatus()
    {
        $column = 'auction_status';
        $schema = [
            'VARCHAR(30)',
            'NULL',
            'DEFAULT "ACTIVE"'
        ];
        $after  = 'auction_updated_at';

        $this->addColumn($this->getTable(), $column, $schema, $after);
    }

    public function modifyAuctionInWooCommerceColumn()
    {
        $column = 'auction_in_woocommerce';
        $change = 'DEFAULT 0';
        $schema = [
            'INT',
            'NULL',
            'DEFAULT 0'
        ];

        $this->modifyColumn($this->getTable(), $column, $schema);
    }

    public function addColumnAuctionWooCommerceId()
    {
        $column = 'auction_woocommerce_id';
        $schema = [
            'INT',
            'NULL',
            'DEFAULT 0'
        ];
        $after  = 'auction_in_woocommerce';

        $this->addColumn($this->getTable(), $column, $schema, $after);
    }

    public function addToWooCommerceUpdateFlag()
    {
        $columns = [
            'auction_update_woocommerce_stock',
            'auction_update_woocommerce_price'
        ];

        $schema = [
            'INT (2)',
            'NULL',
            'DEFAULT 1'
        ];

        foreach ($columns as $column) {
            $this->addColumn($this->getTable(), $column, $schema);
        }
    }

    public function addToWooCommerceUpdateFieldsFlag()
    {
        $this->addColumn($this->getTable(), 'auction_description', [
            'MEDIUMTEXT',
            'NULL'
        ]);

        $this->addColumn($this->getTable(), 'auction_update_woocommerce_fields', [
            'INT (2)',
            'NULL',
            'DEFAULT 1'
        ]);
    }

    public function addToWooCommerceAuctionDetailsField()
    {
        $this->removeColumn($this->getTable(), 'auction_description');
        $this->addColumn($this->getTable(), 'auction_details', [
            'LONGTEXT',
            'NULL'
        ]);
    }

	public function addExternalAuctionId()
	{
		$this->addColumn($this->getTable(), 'auction_external_id', [
			'VARCHAR (255)',
			'NULL'
		]);
	}

    public function removeAllAuctionsWithAuctionIdEqualZero()
    {
        $this->getWpdb()->query(sprintf("DELETE FROM %s WHERE auction_id = 0", $this->getTable()));
    }

    public function updateAuctionToBeEnded($auctionId)
    {
        $this->getWpdb()->update($this->getTable(), [
            'auction_status'                   => 'ENDED',
            'auction_update_woocommerce_stock' => 1
        ], [
            'auction_id' => $auctionId
        ], [
            '%s',
            '%d'
        ], [
            '%f'
        ]);
    }

    public function updateAuctionToBeActive($auctionId)
    {
        $this->getWpdb()->update($this->getTable(), [
            'auction_status' => 'ACTIVE'
        ], [
            'auction_id' => $auctionId
        ], [
            '%s'
        ], [
            '%f'
        ]);
    }

    public function updateStockAuctionsForProfileToBeSynced($auctionIds, $profileId)
    {
        $update = 'UPDATE %s SET %s = 0 WHERE %s IN (%s) AND %s = %d';

        $query = sprintf($update, $this->getTable(), 'auction_update_woocommerce_stock', 'auction_id', implode(',', $auctionIds), 'auction_profile_id', $profileId);

        $this->getWpdb()->query($query);
    }

    public function updatePriceAuctionsForProfileToBeSynced($auctionIds, $profileId)
    {
        $update = 'UPDATE %s SET %s = 0 WHERE %s IN (%s) AND %s = %d';

        $query = sprintf($update, $this->getTable(), 'auction_update_woocommerce_price', 'auction_id', implode(',', $auctionIds), 'auction_profile_id', $profileId);

        $this->getWpdb()->query($query);
    }

    public function updateWooCommerceFieldsAuctionsForProfileToBeSynced($auctionIds, $profileId)
    {
        $update = 'UPDATE %s SET %s = 0 WHERE %s IN (%s) AND %s = %d';

        $query = sprintf($update, $this->getTable(), 'auction_update_woocommerce_fields', 'auction_id', implode(',', $auctionIds), 'auction_profile_id', $profileId);

        $this->getWpdb()->query($query);
    }

    public function isExistAuctionOnTheList($auctionId, $profileId = null)
    {
        $toFilter = ['auction_id' => $auctionId];
        if ($profileId) {
            $toFilter['auction_profile_id'] = $profileId;
        }

        $count = $this->getCountFilteredResult(null, null, $toFilter);

        return $count > 0;
    }

    public function updateStockAuctionsToUpdate($stockLevel, $auctionId)
    {
        $update = 'UPDATE %s SET %s = %d, %s = %d WHERE %s = %d';

        $query = sprintf($update, $this->getTable(), 'auction_quantity', $stockLevel, 'auction_update_woocommerce_stock', 1, 'auction_id', $auctionId);

        $this->getWpdb()->query($query);
    }

    public function updatePricesAuctionToUpdate($prices, $auctionId)
    {
        $update = 'UPDATE %s SET %s = %d, %s = %d, %s = %d WHERE %s = %d';

        $query = sprintf($update, $this->getTable(), 'auction_price', $prices['auction_price'], 'auction_bid_price', $prices['auction_bid_price'], 'auction_update_woocommerce_price', 1, 'auction_id', $auctionId);

        $this->getWpdb()->query($query);
    }

    public function updateWoocommerceFieldsAuctionsToUpdate($auctionName, $auctionDetails, $auctionId)
    {
        $update = "UPDATE %s SET %s = '%s', %s = '%s', %s = %d WHERE %s = %d";

        $query = sprintf($update, $this->getTable(), 'auction_name', $auctionName, 'auction_details', $this->getWpdb()->_real_escape($auctionDetails), 'auction_update_woocommerce_fields', 1, 'auction_id', $auctionId);

		$this->getWpdb()->query('SET NAMES utf8');
        $this->getWpdb()->query($query);
    }
}
