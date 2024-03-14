<?php

$Templates = ShopWP\Factories\Render\Templates_Factory::build();

?>

<div
   data-wpshopify-component
   data-wpshopify-component-id="<?= sanitize_html_class($data->id); ?>"
   data-wpshopify-component-type="<?= sanitize_html_class($data->type); ?>"
   data-wpshopify-payload-settings="<?= $data->settings; ?>">

   <?php 

      if (!empty($data->skeleton)) {
         $Templates->set_and_get_template([
            'data' => $data,
            'full_path' => $data->skeleton
         ]);
      }
   
   ?>

</div>

<?php 

if (!empty($data->after)) {
   $Templates->set_and_get_template([
      'data' => $data,
      'full_path' => $data->after
   ]);
}

?>