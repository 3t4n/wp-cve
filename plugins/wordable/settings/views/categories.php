<?php $categories_tree = $this->build_categories_tree(); ?>

<div data-w-id="05b1c780-f8c1-bf12-8bac-d13711da0515" class="div-block-4">
  <h1>Categories</h1>
  <div style="max-height: 40vh; overflow-y: auto">
    <?php echo $this->render('_category_tree_node', array("categories_tree" => $categories_tree, "depth" => 0)); ?>
  </div>
</div>
