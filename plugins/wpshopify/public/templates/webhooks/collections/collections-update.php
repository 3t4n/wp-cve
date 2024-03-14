<?php

$CPT_Model = ShopWP\Factories\CPT_Model_Factory::build();

$post_id = $CPT_Model->insert_or_update_collection_post($data);