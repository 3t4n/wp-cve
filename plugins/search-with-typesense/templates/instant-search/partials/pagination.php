<?php

use Codemanas\Typesense\Main\TypesenseAPI;

$post_type = $args['post_type'] ?? '';
?>
<div class="cmswt-Pagination cmswt-Pagination-<?php echo esc_html( TypesenseAPI::getInstance()->getCollectionNameFromSchema( $post_type ) ); ?>"></div>