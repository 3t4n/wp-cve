<?php
use WPSocialReviews\Framework\Support\Arr;
?>
<span class="wpsr-fb-feed-time">
  <?php
      $created_time = Arr::get($feed, 'created_time');
      echo esc_html($created_time);
  ?>
</span>