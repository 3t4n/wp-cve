<ul role="list" class="list w-list-unstyled">
  <?php $hiddenCount = count($categories_tree) - 5; ?>
  <?php foreach(array_slice($categories_tree, 0, 5) as $category) { ?>
    <li class='widget-card'>
      <div class='widget-card-title'><?php echo esc_html($category['name']); ?></div>
      <?php if(count($category['children']) > 0) {?>
        <div> +<?php echo count($category['children']); ?> children </div>
      <?php } ?>
      <?php
      // Recursive version
      //  if($category['children'] && count($category['children']) > 0) {
      //   echo $this->render('_category_tree_node', array("categories_tree" => $category['children'], "depth" => $depth + 1));
      // }
      ?>
    </li>
  <?php } ?>
  <?php if($hiddenCount > 0) {?>
    <li class="widget-card">...</li>
  <?php } ?>
</ul>
