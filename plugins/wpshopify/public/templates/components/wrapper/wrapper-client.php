<?php

$Root = ShopWP\Factories\Render\Root_Factory::build();

$encoded_options = $Root->encode_component_data($data->data);
$component_id = $Root->generate_component_id($encoded_options);

$Root->render_root_component([
    'type' => $data->type,
    'id' => $component_id,
    'settings' => $encoded_options,
    'skeleton' => empty($data->skeleton) ? false : $data->skeleton,
    'after' => empty($data->after) ? false : $data->after
]);