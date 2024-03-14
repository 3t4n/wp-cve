<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */
class Quform_Zapier_Integration_Repository
{
    /**
     * @var Quform_Repository
     */
    protected $repository;

    /**
     * @param Quform_Repository $repository
     */
    public function __construct(Quform_Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get the name of the forms table with the WP prefix added
     *
     * @return string
     */
    public function getIntegrationsTableName()
    {
        global $wpdb;

        return $wpdb->prefix . 'quform_zapier_integrations';
    }

    /**
     * Get integration rows
     *
     * @param   array  $args  The query args
     * @return  array
     */
    public function getIntegrations(array $args = array())
    {
        global $wpdb;

        $args = wp_parse_args($args, array(
            'active' => null,
            'orderby' => 'updated_at',
            'order' => 'DESC',
            'trashed' => false,
            'offset' => 0,
            'limit' => 20,
            'search' => ''
        ));

        $sql = "SELECT SQL_CALC_FOUND_ROWS i.id, i.name, f.name as form_name, i.active, i.trashed, i.updated_at
                FROM " . $this->getIntegrationsTableName() . " i
                LEFT JOIN " . $this->repository->getFormsTableName() . " f
                ON i.form_id = f.id";

        $where = array($wpdb->prepare('i.trashed = %d', $args['trashed'] ? 1 : 0));

        if ($args['active'] !== null) {
            $where[] = $wpdb->prepare('i.active = %d', $args['active'] ? 1 : 0);
        }

        if (Quform::isNonEmptyString($args['search'])) {
            $search = '%' . $wpdb->esc_like($args['search']) . '%';
            $where[] = $wpdb->prepare("(i.name LIKE '%s' OR f.name LIKE '%s')", array($search, $search));
        }

        $sql .= " WHERE " . join(' AND ', $where);

        // Sanitise order/limit
        $args['orderby'] = in_array($args['orderby'], array('id', 'name', 'form_name', 'active', 'created_at', 'updated_at')) ? $args['orderby'] : 'updated_at';
        $args['order'] = strtoupper($args['order']);
        $args['order'] = in_array($args['order'], array('ASC', 'DESC')) ? $args['order'] : 'DESC';
        $args['limit'] = (int) $args['limit'];
        $args['offset'] = (int) $args['offset'];

        $sql .= " ORDER BY `{$args['orderby']}` {$args['order']} LIMIT {$args['limit']} OFFSET {$args['offset']}";

        return $wpdb->get_results($sql, ARRAY_A);
    }

    public function activate()
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $this->createIntegrationsTable();
    }

    protected function createIntegrationsTable()
    {
        global $wpdb;

        $sql = "CREATE TABLE " . $this->getIntegrationsTableName() . " (
            id int UNSIGNED NOT NULL AUTO_INCREMENT,
            name varchar(64) NOT NULL,
            form_id int UNSIGNED NULL DEFAULT NULL,
            config longtext NOT NULL,
            active boolean NOT NULL DEFAULT 1,
            trashed boolean NOT NULL DEFAULT 0,
            created_at datetime NOT NULL,
            updated_at datetime NOT NULL,
            PRIMARY KEY  (id),
            KEY form_id (form_id),
            KEY active (active),
            KEY trashed (trashed)
        ) " . $wpdb->get_charset_collate() . ";";

        dbDelta($sql);
    }

    public function uninstall()
    {
        global $wpdb;

        // Remove the integration table
        $wpdb->query("DROP TABLE IF EXISTS " . $this->getIntegrationsTableName());

        // Remove the user options
        delete_metadata('user', 0, 'quform_zapier_integrations_per_page', '', true);
        delete_metadata('user', 0, 'quform_zapier_integrations_order_by', '', true);
        delete_metadata('user', 0, 'quform_zapier_integrations_order', '', true);
    }

    /**
     * Get the number of found rows from the last query
     *
     * @return int
     */
    public function getFoundRows()
    {
        global $wpdb;

        return (int) $wpdb->get_var("SELECT FOUND_ROWS()");
    }

    /**
     * Get the count of integrations
     *
     * @param   bool|null  $active   Select all (null), only active (true) or inactive (false) forms
     * @param   bool       $trashed  Select trashed forms
     * @return  int
     */
    public function count($active = null, $trashed = false)
    {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM " . $this->getIntegrationsTableName();

        $where = array($wpdb->prepare('trashed = %d', $trashed ? 1 : 0));

        if ($active !== null) {
            $where[] = $wpdb->prepare('active = %d', $active ? 1 : 0);
        }

        $sql .= " WHERE " . join(' AND ', $where);

        return (int) $wpdb->get_var($sql);
    }

    /**
     * Add the integration with the given config
     *
     * @param   array       $config  The integration config to add
     * @return  array|bool           The new integration config with new auto-generated ID or false on failure
     */
    public function add(array $config)
    {
        global $wpdb;

        // Temporarily save the config parts that are part of the table row and unset them
        $name = $config['name'];
        $formId = $config['formId'];
        $active = $config['active'];
        $trashed = $config['trashed'];
        unset($config['id'], $config['name'], $config['formId'], $config['active'], $config['trashed']);

        $currentTime = current_time('mysql', true);

        $result = $wpdb->insert($this->getIntegrationsTableName(), array(
            'name' => Quform::substr($name, 0, 64),
            'form_id' => is_numeric($formId) ? $formId : null,
            'config' => base64_encode(serialize($config)),
            'active' => $active,
            'trashed' => $trashed,
            'created_at' => $currentTime,
            'updated_at' => $currentTime
        ));

        if ($result === false) {
            return false;
        }

        $config['id'] = $wpdb->insert_id;

        // Restore the config parts that are part of the table row
        $config['name'] = $name;
        $config['formId'] = $formId;
        $config['active'] = $active;
        $config['trashed'] = $trashed;

        return $config;
    }

    /**
     * Find an integration by ID
     *
     * @param   int  $id
     * @return  array|null
     */
    public function find($id)
    {
        global $wpdb;

        $sql = "SELECT * FROM " . $this->getIntegrationsTableName() . " WHERE id = %d";

        return $wpdb->get_row($wpdb->prepare($sql, $id), ARRAY_A);
    }

    /**
     * Add the integration row data to the config array
     *
     * @param   array  $row
     * @param   array  $config
     * @return  array
     */
    protected function addRowDataToConfig(array $row, array $config)
    {
        $config['id'] = (int) $row['id'];
        $config['name'] = $row['name'];
        $config['formId'] = $row['form_id'] !== null ? $row['form_id'] : '';
        $config['active'] = $row['active'] == 1;
        $config['trashed'] = $row['trashed'] == 1;

        return $config;
    }

    /**
     * Get the config array for the integration with the given ID
     *
     * @param   int         $id  The integration ID
     * @return  array|null       The config array or null if the integration doesn't exist
     */
    public function getConfig($id)
    {
        $row = $this->find($id);

        if ($row === null) {
            return null;
        }

        $config = maybe_unserialize(base64_decode($row['config']));

        if (is_array($config)) {
            $config = $this->addRowDataToConfig($row, $config);
        } else {
            $config = null;
        }

        return $config;
    }

    /**
     * Save the integration with the given config
     *
     * @param array $config The integration config
     */
    public function save(array $config)
    {
        global $wpdb;

        // Temporarily save the config parts that are part of the table row and unset them
        $id = $config['id'];
        $name = $config['name'];
        $formId = $config['formId'];
        $active = $config['active'];
        $trashed = $config['trashed'];

        unset($config['id'], $config['name'], $config['formId'], $config['active'], $config['trashed']);

        $updateValues = array(
            'name' => Quform::substr($name, 0, 64),
            'form_id' => $formId,
            'config' => base64_encode(serialize($config)),
            'active' => $active,
            'trashed' => $trashed,
            'updated_at' => current_time('mysql', true)
        );

        $updateWhere = array(
            'id' => $id
        );

        $wpdb->update($this->getIntegrationsTableName(), $updateValues, $updateWhere);

        // Restore the config parts that are part of the table row
        $config['id'] = $id;
        $config['name'] = $name;
        $config['formId'] = $formId;
        $config['active'] = $active;
        $config['trashed'] = $trashed;
    }

    /**
     * Sanitize the array of IDs ensuring they are all positive integers
     *
     * @param   array   $ids  The array of IDs
     * @return  array         The array of sanitized IDs
     */
    protected function sanitizeIds(array $ids)
    {
        $sanitized = array();

        foreach ($ids as $id) {
            if ( ! is_numeric($id)) {
                continue;
            }

            $id = (int) $id;

            if ($id > 0) {
                $sanitized[] = $id;
            }
        }

        $sanitized = array_unique($sanitized);

        return $sanitized;
    }

    /**
     * Prepare an array of IDs for use in an IN clause
     *
     * @param   array   $ids  The array of IDs
     * @return  string        The sanitized string for the IN clause
     */
    protected function prepareIds(array $ids)
    {
        $ids = $this->sanitizeIds($ids);
        $ids = array_map('esc_sql', $ids);
        $ids = join(',', $ids);

        return $ids;
    }

    /**
     * Get the integrations for the given form ID (excluding trashed integrations)
     *
     * @param   int    $formId
     * @return  array
     */
    public function getIntegrationsByFormId($formId)
    {
        global $wpdb;

        $integrations = array();

        $query = $wpdb->prepare("SELECT * FROM " . $this->getIntegrationsTableName() . " WHERE form_id = %d AND trashed = 0", $formId);
        $results = $wpdb->get_results($query, ARRAY_A);

        foreach ($results as $row) {
            $config = maybe_unserialize(base64_decode($row['config']));

            if (is_array($config)) {
                $integrations[] = $this->addRowDataToConfig($row, $config);
            }
        }

        return $integrations;
    }

    /**
     * Activate integrations with the given IDs
     *
     * @param   array  $ids  The array of integration IDs
     * @return  int          The number of affected rows
     */
    public function activateIntegrations(array $ids)
    {
        global $wpdb;

        $ids = $this->prepareIds($ids);

        if ($ids == '') {
            return 0;
        }

        $sql = "UPDATE " . $this->getIntegrationsTableName() . " SET active = 1 WHERE id IN ($ids)";

        $affectedRows = (int) $wpdb->query($sql);

        return $affectedRows;
    }

    /**
     * Deactivate integrations with the given IDs
     *
     * @param   array  $ids  The array of integration IDs
     * @return  int          The number of affected rows
     */
    public function deactivateIntegrations(array $ids)
    {
        global $wpdb;

        $ids = $this->prepareIds($ids);

        if ($ids == '') {
            return 0;
        }

        $sql = "UPDATE " . $this->getIntegrationsTableName() . " SET active = 0 WHERE id IN ($ids)";

        $affectedRows = (int) $wpdb->query($sql);

        return $affectedRows;
    }

    /**
     * Duplicate the integrations with the IDs in the given array
     *
     * @param   array  $ids  The integration ID
     * @return  array        The array of new integration IDs
     */
    public function duplicateIntegrations(array $ids)
    {
        $ids = $this->sanitizeIds($ids);
        $newIds = array();

        foreach ($ids as $id) {
            $config = $this->getConfig($id);

            if ( ! is_array($config)) {
                continue;
            }

            $config['active'] = true;
            /* translators: %s: the original integration name */
            $config['name'] = sprintf(_x('%s duplicate', 'integration name duplicate', 'quform-zapier'), $config['name']);

            $config = $this->add($config);

            if (is_array($config)) {
                $newIds[] = $config['id'];
            }
        }

        return $newIds;
    }

    /**
     * Trash integrations with the given IDs
     *
     * @param   array  $ids  The array of integration IDs
     * @return  int          The number of affected rows
     */
    public function trashIntegrations(array $ids)
    {
        global $wpdb;

        $ids = $this->prepareIds($ids);

        if ($ids == '') {
            return 0;
        }

        $sql = "UPDATE " . $this->getIntegrationsTableName() . " SET trashed = 1 WHERE id IN ($ids)";

        $affectedRows = (int) $wpdb->query($sql);

        return $affectedRows;
    }

    /**
     * Untrash integrations with the given IDs
     *
     * @param   array  $ids  The array of integration IDs
     * @return  int          The number of affected rows
     */
    public function untrashIntegrations(array $ids)
    {
        global $wpdb;

        $ids = $this->prepareIds($ids);

        if ($ids == '') {
            return 0;
        }

        $sql = "UPDATE " . $this->getIntegrationsTableName() . " SET trashed = 0 WHERE id IN ($ids)";

        $affectedRows = (int) $wpdb->query($sql);

        return $affectedRows;
    }

    /**
     * Delete the integrations with the IDs in the given array
     *
     * @param   array  $ids  The array of integration IDs
     * @return  int          The number of deleted rows
     */
    public function deleteIntegrations(array $ids)
    {
        global $wpdb;

        $ids = $this->prepareIds($ids);

        if ($ids == '') {
            return 0;
        }

        $affectedRows = (int) $wpdb->query("DELETE FROM " . $this->getIntegrationsTableName() . " WHERE id IN ($ids)");

        return $affectedRows;
    }
}
