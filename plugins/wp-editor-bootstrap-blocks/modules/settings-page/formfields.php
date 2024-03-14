<?php
if ( ! defined( 'ABSPATH' ) || ! class_exists( 'GtbBootstrapSettingsPage', false ) ) exit;



function print_start_table_form()
{
   ?><table class="form-table"><tbody><?php
}

function print_end_table_form()
{
   ?></tbody></table><?php 
}

function print_checkbox($options,$id,$title,$label='',$message='')
{
   $checked = isset( $options[$id]) && $options[$id] =='1' ?true:false;

   if ($checked) {$add_check = 'checked="checked"';}else {$add_check='';};
   print '<tr><th>'.$title.'</th>';
   print '<td><label><input type="checkbox" name="gtbbootstrap_options['.$id.']" value="1" '.$add_check.' />&nbsp;'.$label.'</label>';
   print '<p class="description">'.$message.'</p>';
   print '</td></tr>';
}

function print_radiobuttons($options,$id,$title,$fields,$message='')
{

   print '<tr><th>'.$title.'</th>';
   print '<td>';
   foreach($fields as $val=>$label):
      $checked = isset( $options[$id]) && $options[$id] == $val?'checked="checked"':'';

      print '<label><input type="radio" name="gtbbootstrap_options['.$id.']" value="'.$val.'" '.$checked.' />&nbsp;'.$label.'</label><br/>';
   endforeach;
   print '<p class="description">'.$message.'</p>';
   print '</td></tr>';
}

function print_select($options,$id,$title,$fields,$message='')
{

   print '<tr id="gtbbootstrap_'.$id.'"><th>'.$title.'</th>';
   print '<td><select name="gtbbootstrap_options['.$id.']">';
   foreach($fields as $val=>$label):
      $selected = isset( $options[$id]) && $options[$id] == $val?'selected="selected"':'';

      print '<option value="'.$val.'" '.$selected.'>'.$label.'</option>';
   endforeach;
   print '</select><p class="description">'.$message.'</p>';
   print '</td></tr>';
}


function print_input_number($options,$id,$title,$label='',$message='')
{
   $input = isset( $options[$id]) && is_numeric($options[$id])?intval($options[$id]):12;
   $disabled = !defined('GTBBOOTSTRAP_DESIGN_LC')?' disabled="disabled"':'';
   $name = defined('GTBBOOTSTRAP_DESIGN_LC')?' name="gtbbootstrap_options['.$id.']"':'';

   print '<tr><th>'.$title.'</th>';
   print '<td><label><input type="number" min="2" max="14"'.$name.' value="'.$input.'"'.$disabled.' />&nbsp;'.$label.'</label>';
   print '<p class="description">'.$message.'</p>';
   print '</td></tr>';
}


function print_input_url($options,$id,$title,$label='',$message='',$placeholder='https://')
{
   $input = !empty( $options[$id])?$options[$id]:'';
   $disabled = !defined('GTBBOOTSTRAP_DESIGN_LC')?' disabled="disabled"':'';
   $name = defined('GTBBOOTSTRAP_DESIGN_LC')?' name="gtbbootstrap_options['.$id.']"':'';

   print '<tr><th>'.$title.'</th>';
   print '<td><label><input type="url" style="width:100%;max-width:600px" placeholder="'.$placeholder.'"'.$name.' value="'.$input.'"'.$disabled.' />'.$label.'</label>';
   print '<p class="description">'.$message.'</p>';
   print '</td></tr>';
}


function print_all_colorfields($options)
{
   $label = array( '','color-primary</code>, <code>bg-primary', 'color-secondary</code>, <code>bg-secondary', 'color-success</code>, <code>bg-success', 'color-danger</code>, <code>bg-danger', 'color-warning</code>, <code>bg-warning', 'color-info</code>, <code>bg-info','color-white</code>, <code>bg-white','color-black</code>, <code>bg-black');
   for ($i=1;$i<=8;$i++):
      $input = !empty( $options['bootstrap_color'.$i])?$options['bootstrap_color'.$i]:'#000000';
      print '<div><div class="display-inline-block"><input type="text" class="color-field" name="gtbbootstrap_options[bootstrap_color'.$i.']" value="'.$input.'" /></div> <code>'.$label[$i].'</code></div>';
   endfor;
}