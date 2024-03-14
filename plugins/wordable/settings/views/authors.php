<?php $authors = $this->authors(); ?>

<div data-w-id="e1b1a63b-9f69-c803-dfca-5b4e0b8d1e87" class="div-block-4">
  <h1>Authors</h1>
  <div style="max-height: 40vh; overflow-y: auto">
    <ul role="list" class="list w-list-unstyled">
      <?php $hiddenCount = count($authors) - 5; ?>
      <?php foreach (array_slice($authors, 0, 5) as $author) { ?>
        <li class='widget-card'>
          <div class="widget-card-title">
            <?php
              if($author->display_name) {
                echo esc_html($author->display_name);
              } else {
                echo esc_html($author->user_email);
              }
            ?>
          </div>
          <a href='mailto:<?php echo $author->user_email ?>' class='author-email w-inline-block'>
            <?php echo esc_html($author->user_email) ?>
          </a>
        </li>
      <?php } ?>
      <?php if($hiddenCount > 0) {?>
        <li class="widget-card">...</li>
      <?php } ?>
    </ul>
  </div>
</div>
