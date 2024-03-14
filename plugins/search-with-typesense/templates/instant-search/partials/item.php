<?php

use Codemanas\Typesense\Main\TypesenseAPI;

$post_type = $args['post_type'] ?? '';
?>
<div class="cmswt-Result-hits cmswt-Result-hits_<?php echo esc_html( TypesenseAPI::getInstance()->getCollectionNameFromSchema( $post_type ) ) ?>"></div>