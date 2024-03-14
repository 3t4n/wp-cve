<?php namespace Premmerce\Redirect;

class RedirectModel
{
    private $wpdb;

    /**
     * RedirectModel constructor.
     */
    public function __construct()
    {
        global $wpdb;

        $this->wpdb = $wpdb;
    }

    /**
     * Create plugin table
     */
    public function createTable()
    {
        $tableName = $this->wpdb->get_blog_prefix() . 'premmerce_redirects';
        if ($this->wpdb->get_var('SHOW TABLES LIKE "' . $tableName . '"') != $tableName) {
            $sql = '
				CREATE TABLE ' . $tableName . ' (
				  	id INT(11) NOT NULL AUTO_INCREMENT,
				  	old_url VARCHAR(255) NOT NULL,
				  	redirect_type VARCHAR(20) NOT NULL,
				  	redirect_content VARCHAR(255) NOT NULL,
				  	type INT(3) NOT NULL,
				  	PRIMARY KEY (id)
				) DEFAULT CHARACTER SET ' . $this->wpdb->charset . ' COLLATE ' . $this->wpdb->collate;
            $this->wpdb->query($sql);
        }
    }

    /**
     * Delete plugin table
     */
    public function deleteTable()
    {
        $sql = 'DROP TABLE IF EXISTS ' . $this->wpdb->get_blog_prefix() . 'premmerce_redirects';
        $this->wpdb->query($sql);
    }

    /**
     * Create redirect
     *
     * @param array $data
     */
    public function createRedirect($data)
    {
        $this->wpdb->insert(
            $this->wpdb->get_blog_prefix() . 'premmerce_redirects',
            array(
                'old_url'          => $this->fixUrl($data['old_url']),
                'redirect_type'    => $data['redirect_type'],
                'redirect_content' => $data['redirect_content'],
                'type'             => $data['redirect_method'],
            )
        );
    }

    /**
     * Update redirect
     *
     * @param array $data
     * @param int $id
     */
    public function updateRedirect($data, $id)
    {
        $this->wpdb->update(
            $this->wpdb->get_blog_prefix() . 'premmerce_redirects',
            array(
                'old_url'          => $this->fixUrl($data['old_url']),
                'redirect_type'    => $data['redirect_type'],
                'redirect_content' => $data['redirect_content'],
                'type'             => $data['redirect_method'],
            ),
            array(
                'id' => $id,
            )
        );
    }

    public function fixUrl($url)
    {
        return '/' . trim($url, ' /');
    }

    /**
     * Delete redirect
     *
     * @param array $conditions
     */
    public function deleteRedirect($conditions)
    {
        $this->wpdb->delete($this->wpdb->get_blog_prefix() . 'premmerce_redirects', $conditions);
    }

    /**
     * Get list of redirects
     *
     * @return array|null|object
     */
    public function getRedirects($woocommerceTypes = true)
    {
        $query = "SELECT * FROM " . $this->wpdb->get_blog_prefix() . "premmerce_redirects";

        if (!$woocommerceTypes) {
            $query .= " WHERE redirect_type NOT IN ('product', 'product_category')";
        }

        return $this->wpdb->get_results($query);
    }

    /**
     * Get one redirect by old url
     *
     * @param $oldUrl
     *
     * @return array|null|object|void
     */
    public function getOneRedirectByOldUrl($oldUrl)
    {
        $query = "SELECT * FROM " . $this->wpdb->get_blog_prefix() . "premmerce_redirects WHERE `old_url` = %s";

        return $this->wpdb->get_row($this->wpdb->prepare($query, $oldUrl));
    }

    /**
     * Get one redirect by id
     *
     * @param $id
     *
     * @return array|null|object|void
     */
    public function getOneRedirectById($id)
    {
        $query = "SELECT * FROM " . $this->wpdb->get_blog_prefix() . "premmerce_redirects WHERE `id` = %d";

        return $this->wpdb->get_row($this->wpdb->prepare($query, $id));
    }

    /**
     * Get redirect by old url where id != this object
     *
     * @param $oldUrl
     * @param $id
     *
     * @return array|null|object|void
     */
    public function getOneRedirectByOldUrlAndOtherId($oldUrl, $id)
    {
        $query = "SELECT * FROM " . $this->wpdb->get_blog_prefix() . "premmerce_redirects WHERE `old_url` = %s AND `id` != %d";

        return $this->wpdb->get_row($this->wpdb->prepare($query, array($oldUrl, $id)));
    }

    /**
     * Get data for selects in admin page
     *
     * @param $data
     *
     * @return array|int|\WP_Error
     */
    public function getPostsByString($data)
    {
        if (in_array($data['type'], array('product', 'post', 'page'))) {
            $objects = (new \WP_Query(array(
                's'           => isset($data['s'])? $data['s'] : '',
                'post_type'   => $data['type'],
                'numberposts' => 10,
            )))->posts;
        } else {
            $objects = get_terms(array(
                'hide_empty' => false,
                'search'     => isset($data['s'])? $data['s'] : '',
                'taxonomy'   => $data['type'],
            ));
        }

        return $objects;
    }
}
