<?php

$counters = array(
  'Honeypot'      => 'spam',
  'Cleantalk' => 'cleantalk',
  'Akismet'   => 'akismet',
);
?>

<table>
<?php
foreach( $counters as $label => $name ) {
  $counter      = $this->func__get_plugin_option( $name.'_count' );

  echo "<tr>";
  echo "<td><strong>$label:</strong></td>";
  echo "<td>".number_format_i18n( $counter )."</td>";
  echo "</tr>";
}
?>
</table>
