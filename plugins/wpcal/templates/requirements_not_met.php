
<?php $rr = $requirement_result;?>
<h1>WPCal.io</h1>
<table width="300" border="0" cellspacing="5"><tbody>
  <tr><td class="wpmerge_b" colspan="3"><span class="error-box">Requirements not met!</span><br><br></td></tr>
<tr><td></td><td align="left"><strong>Required</strong></td><td align="left"><strong>Current</strong></td></tr>
<tr><td>PHP</td><td>v<?php echo $rr['required']['php']['version'] ?></td> <td>v<?php echo $rr['installed']['php']['version'] ?></td></tr>
<tr><td>MySQL</td><td>v<?php echo $rr['required']['mysql']['version'] ?></td> <td>v<?php echo $rr['installed']['mysql']['version'] ?></td></tr>
<tr><td>WP</td><td>v<?php echo $rr['required']['wp']['version'] ?></td> <td>v<?php echo $rr['installed']['wp']['version'] ?></td></tr>
</tbody></table>