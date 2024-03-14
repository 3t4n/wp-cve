<?php

use WunderAuto\Types\Internal\ReTriggerState;

assert(isset($this) && $this instanceof WunderAuto\Admin);
$settings = $this->getSettingsForView('automation-retrigger');
assert($settings instanceof ReTriggerState);

$wunderAuto   = wa_wa();
$filters      = $wunderAuto->getObjects('filter');
$filterGroups = $wunderAuto->getGroups($filters);

$data = json_encode(
    (object)[
        'steps'        => $settings->steps,
        'filters'      => $filters,
        'filterGroups' => $filterGroups,
    ]
);
?>

<div id="stepsmetabox"></div>

<template id="steps-component">

    <div :set="currentFilters=steps[0].filterGroups"></div>
    <div :set="stepKey = 0"></div>
    <?php include __DIR__ . '/components/filter.php'?>
</template>

<div id="steps-data" style="display:none;">
    <?php echo $data !== false ? esc_attr($data) : '' ?>
</div>
