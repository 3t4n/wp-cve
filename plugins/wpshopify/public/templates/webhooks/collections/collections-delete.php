<?php

$DB_Collections = ShopWP\Factories\DB\Collections_Factory::build();
$DB_Posts = ShopWP\Factories\DB\Posts_Factory::build();

$post_id = $DB_Collections->get_post_id_from_collection($data);

if (!empty($post_id)) {
    $delete_result = \wp_delete_post($post_id, true);
}