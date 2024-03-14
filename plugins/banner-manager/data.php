<?php
/**
 * Datubasearekin egiten diren transakzio guztiak class onetatik
 * pasatzen dira.
 *
 * @author karrikas
 */
class data
{
    /**
     * Gordetzeko kontsultak hemendik pasa behar dira
     */
    static private function query( $sql )
    {
        global $wpdb;

        //echo "$sql<br>";

        return $wpdb->query( $sql );
    }

    /**
     * Banner guztiak eskuratzen ditu administratzaileko zerrendan
     * ikusteko.
     *
     * @return array
     */
    static public function get_banners( $category = null, $active = null)
    {
        global $wpdb;

        // kategoriarekin filtratu
        $where = '';
        if($category)
        {
            $where .= sprintf(' AND id_category = "%d"', $category);
        }

        // activatuak filtratu
        if($active=='0' || $active=='1')
        {
            $where .= sprintf(' AND active = "%d"', $active);
        }

        $sql = "SELECT
                b.*,
                c.groups,
                SUM(s.views) as views,
                SUM(s.clicks) as clicks
            FROM
            " . BM_TABLE_BANNERS ." b
            LEFT JOIN
                " . BM_TABLE_GROUPS . " c
                ON (c.id=b.id_category)
            LEFT JOIN
                " . BM_TABLE_STATS . " s
                ON (s.id_banner=b.id)
            WHERE 1=1
            $where
            GROUP BY b.id
            ORDER BY id DESC";

        $result = $wpdb->get_results( $sql );

            return $result;
    }

    static public function get_banners_by_group( $id )
    {
        global $wpdb;

        $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . BM_TABLE_BANNERS ." WHERE id_category='%d'", $id ) );

        return $result;
    }

    /**
     * Randon banner bat atera kategoriaren ida zehaztuz.
     *
     * @param intenger Id kategoria
     * @return array Banner
     */
    static public function get_banners_by_group_rand( $id )
    {
        global $wpdb;

        $result = $wpdb->get_results( $wpdb->prepare(
            "SELECT b.id as id_banner,b.*,c.* FROM
                " . BM_TABLE_BANNERS ." b
            LEFT JOIN " . BM_TABLE_GROUPS . " c
            ON (c.id=b.id_category)
            WHERE b.id_category='%d'
            AND b.active='1'
            ORDER BY RAND()
            ", $id ) );

        return $result;
    }

    static public function get_banner( $id )
    {
        global $wpdb;

        $result = $wpdb->get_row( $wpdb->prepare( "SELECT b.* FROM " . BM_TABLE_BANNERS ." b WHERE b.id='%d' LIMIT 1", $id ) );

        return $result;
    }

    static public function new_banner( $category, $title, $src, $link, $blank, $active, $groupkey )
    {
        global $wpdb;

        $sql = $wpdb->prepare( "INSERT INTO " . BM_TABLE_BANNERS ." ( id_category, title, src, link, blank, active, groupkey ) VALUES ( '%d', '%s', '%s', '%s', '%d', '%d', '%s' )", $category, $title, $src, $link, $blank, $active, $groupkey );
        return self::query( $sql );
    }

    static public function update_banner( $id, $category, $title, $src, $link, $blank, $active, $groupkey )
    {
        global $wpdb;

        $sql = $wpdb->prepare( "UPDATE " . BM_TABLE_BANNERS ." SET id_category='%d', title='%s', src='%s', link='%s', blank='%d', active='%d', groupkey='%s' WHERE id='%d'", $category, $title, $src, $link, $blank, $active, $groupkey, $id );
        return self::query( $sql );
    }

    static public function del_banner( $id )
    {
        global $wpdb;

        $sql = $wpdb->prepare( "DELETE FROM " . BM_TABLE_BANNERS ." WHERE id='%d'", $id );
        return self::query( $sql );
    }

    // CATEGORIES
    static public function get_categories()
    {
        global $wpdb;

        $result = $wpdb->get_results( "SELECT * FROM " . BM_TABLE_GROUPS . " ORDER BY groups" );

        return $result;
    }

    static public function get_category( $id )
    {
        global $wpdb;

        $result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . BM_TABLE_GROUPS . " WHERE id = '%d' LIMIT 1", $id ) );

        return $result;
    }

    static public function new_category( $category, $width, $height )
    {
        global $wpdb;

        $sql = $wpdb->prepare( "INSERT INTO " . BM_TABLE_GROUPS . " ( groups, width, height ) VALUES ( %s, %d, %d )", $category, $width, $height );
        return self::query( $sql );
    }

    static public function update_category( $id, $category, $width, $height )
    {
        global $wpdb;

        $sql = $wpdb->prepare( "UPDATE " . BM_TABLE_GROUPS . " SET groups = '%s', width = '%d', height = '%d' WHERE id='%d'", $category, $width, $height, $id );
        return self::query( $sql );
    }

    static public function del_category( $id )
    {
        global $wpdb;

        $sql = $wpdb->prepare( "DELETE FROM " . BM_TABLE_GROUPS . " WHERE id='%d'", $id );
        return self::query( $sql );
    }

    // STATS
    static public function add_stat_view( $id_banner )
    {
        // erregistro berria sortu
        self::today_stat_exist( $id_banner );

        global $wpdb;

        $sql = $wpdb->prepare( "UPDATE " . BM_TABLE_STATS . " SET views = views + 1 WHERE id_banner = '%d' AND day = '%s'", $id_banner, self::get_today() );
        return self::query( $sql );
    }

    static public function add_stat_clic( $id_banner )
    {
        // erregistro berria sortu
        self::today_stat_exist( $id_banner );

        global $wpdb;

        $sql = $wpdb->prepare( "UPDATE " . BM_TABLE_STATS . " SET clicks = clicks + 1 WHERE id_banner = '%d' AND day = '%s'", $id_banner, self::get_today() );
        return self::query( $sql );
    }


    static private function new_stat( $id_banner )
    {
        global $wpdb;

        $sql = $wpdb->prepare( "INSERT INTO " . BM_TABLE_STATS . " (id_banner, day, views, clicks) VALUES (%d, '%s', 0, 0)", $id_banner, self::get_today() );
        return self::query( $sql );
    }

    static private function today_stat_exist( $id_banner )
    {
        global $wpdb;

        $result = $wpdb->get_col( $wpdb->prepare( "SELECT COUNT(id) FROM " . BM_TABLE_STATS . " WHERE id_banner = '%d' AND day='%s'", $id_banner, self::get_today() ) );

        if($result[0]=='0')
        {
            self::new_stat( $id_banner );
        }
    }

    static $today;
    static private function get_today()
    {
        if(!isset(self::$today))
        {
            self::$today = date('Y-m-d', current_time('timestamp',0));
        }

        return self::$today;
    }

}
