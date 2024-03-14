<div class="element-ready-dash-portfolio-header">
   <?php foreach($this->links as $menu): ?>
        <a href="<?php echo esc_url(admin_url( $menu['url'])); ?>"><?php echo esc_html( $menu['label'] ) ?> </a>
   <?php endforeach; ?>
</div>