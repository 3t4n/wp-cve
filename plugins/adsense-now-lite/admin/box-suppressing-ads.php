<?php
$keys = array('kill_feed', 'kill_page', 'kill_home', 'kill_front_page', 'kill_attachment', 'kill_category', 'kill_search', 'kill_sticky', 'kill_tag', 'kill_archive');
openBox(__("Suppressing Ad Blocks", 'easy-ads'));
?>
<p>Ads can be suppressed on various kinds of pages on your blog, as you specify below.</p>
<table class="table">
  <tbody>
    <?php
    $break = false;
    foreach ($keys as $pk) {
      if (!$break) {
        echo "<tr>";
      }
      echo EzGA::renderOptionCell($pk, $ezOptions[$pk]);
      if ($break) {
        echo "</tr>";
      }
      $break = !$break;
    }
    ?>
  </tbody>
  <tfoot><tr><td colspan="4"></td></tr></tfoot>
</table>
<p>Suppress Ads (from this <strong>Option Set</strong>) on following categories, posts and pages as well.</p>
<table class="table">
  <tbody>
    <tr>
      <td>
        <strong>Select Categories/Posts/Pages</strong>
      </td>
      <td class="center-text">
        <a href='#' data-verb='deny' class='btn-sm btn-primary categories' data-toggle='popover' data-trigger='hover' data-placement="top"  title='Select Categories' data-content='If you want to suppress ads from this <strong>Option Set</strong> appearing on some categories, you can do so here. Click to bring up a category selection dialog.'>Categories</a>
      </td>
      <td class="center-text">
        <a href='#' data-verb='deny' class='btn-sm btn-primary posts' data-toggle='popover' data-trigger='hover' data-placement="top"  title='Select Posts' data-content='You can specify that the ads from this <strong>Option Set</strong> do not appear on some posts. Click to bring up a post selection dialog. This dialog can potentially be very long.'>Posts</a>
      </td>
      <td class="center-text">
        <a href='#' data-verb='deny' class='btn-sm btn-primary pages' data-toggle='popover' data-trigger='hover' data-placement="top"  title='Select Pages' data-content='You can specify that the ads from this <strong>Option Set</strong> do not appear on certain pages. Click to bring up a page selection dialog.'>Pages</a>
      </td>
    </tr>
  </tbody>
  <tfoot><tr><td colspan="4"></td></tr></tfoot>
</table>
<?php
closeBox();
