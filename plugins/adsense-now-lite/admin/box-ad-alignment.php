<?php
$keys = array($top, $middle, $bottom);
openBox(__("Ad Alignment", 'easy-ads'));
?>
<p>Ads can be placed in three different slots on each page or post - Top (Lead-in), Middle (Mid-Post) and Bottom (Lead-out). Each slot will be aligned (or suppressed) as you specify below.</p>
<table class="table borderless">
  <thead>
    <tr>
      <th></th>
      <th class="center-text">Left</th>
      <th class="center-text">Left<br>(Wrap)</th>
      <th class="center-text">Center</th>
      <th class="center-text">Right</th>
      <th class="center-text">Right<br>(Wrap)</th>
      <th class="center-text">Suppress</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($keys as $pk) {
      echo EzGA::renderOptionCell($pk, $ezOptions[$pk]);
    }
    ?>
  </tbody>
</table>
<?php
closeBox();
