<?php

$sCollisionCheck = '';
$problems = get_option( 'fv_antispam_filledin_conflict' );
if( $problems ) {
$sCollisionCheck = $this->admin__filled_in_collision_check( true );
}
else if( $problems === false ) {
$sCollisionCheck= $this->admin__filled_in_collision_check( true );
} else {
$sCollisionCheck = " (No conflicts with Filled In detected with your current field name)!";
}

if( $this->util__is_filled_in() ) :
?>
<div class="inside">
  <table class="form-table">
    <tr>
      <td>
        <h3>Basic antispam</h3>
        <?php if( function_exists('akismet_get_key') && akismet_get_key() ) : ?>
          <br /><span class="description"><?php _e('Akismet detected - FV Antispam will use it to protect the forms.', 'antispam_bee') ?></span>
        <?php else : ?>
          <br /><span class="description"><?php _e('We recommend that you install Akismet for increased protection.', 'antispam_bee') ?></span>
        <?php endif; ?>

      </td>
    </tr>
    <tr>
      <td>
        <label for="protect_filledin">
          <input type="checkbox" name="protect_filledin" id="protect_filledin" value="1" <?php checked($this->func__get_plugin_option('protect_filledin'), 1) ?> />
          <?php _e('Protect Filled in forms', 'antispam_bee') ?>
        </label>
      </td>
    </tr>
    <tr>
      <td>
        Enter fake field name<br />
        <label for="protect_filledin_field">
          <input type="text" class="regular-text" name="protect_filledin_field" id="protect_filledin_field" value="<?php if( function_exists( 'esc_attr' ) ) echo esc_attr( $this->func__get_plugin_option('protect_filledin_field') ); else echo ( $this->func__get_plugin_option('protect_filledin_field') ); ?>" />
          <span class="description"><?php _e('Leave empty if you want to use the default'.$sCollisionCheck, 'antispam_bee') ?> <?php $this->admin__show_help_link('protect_filledin_field') ?></span>
        </label>
      </td>
    </tr>
    <tr>
      <td>
        Enter spam message<br />
        <label for="protect_filledin_notice">
          <input type="text" class="regular-text" name="protect_filledin_notice" id="protect_filledin_notice" value="<?php if( function_exists( 'esc_attr' ) ) echo esc_attr( $this->func__get_plugin_option('protect_filledin_notice') ); else echo ( $this->func__get_plugin_option('protect_filledin_notice') ); ?>" />
          <span class="description"><?php _e('This is a failsafe if a real person is caught as spam', 'antispam_bee') ?> <?php $this->admin__show_help_link('protect_filledin_notice') ?></span>
        </label>
      </td>
    </tr>
    <tr>
      <td>
        <label for="protect_filledin_disable_notice">
          <input type="checkbox" name="protect_filledin_disable_notice" id="protect_filledin_disable_notice" value="1" <?php checked($this->func__get_plugin_option('protect_filledin_disable_notice'), 1) ?> />
          <?php _e('Disable protection notice', 'antispam_bee') ?> <span class="description"><?php _e('(Logged in administrators normally see a notice that FV Antispam is protecting a Filled in form)', 'antispam_bee') ?></span>
        </label>
      </td>
    </tr>

    <tr><td><h3>Custom questions</h3><br ><span class="description"><?php _e('If some forms still have problem with spam, customize and enable the custom questions.', 'antispam_bee') ?></span></td></tr>
    <tr>
      <td>
        Use custom question on forms:
        <?php

        global $wpdb;
        $sTableName = $wpdb->prefix.'filled_in_forms';
        if( $wpdb->get_var("SHOW TABLES LIKE '$sTableName'") == $sTableName && $aForms = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}filled_in_forms ORDER BY name ASC" ) ) {

        $sFilledInJs = $this->func__get_plugin_option('filled_in_custom');
        $aSelectedForms = array();
        if( $sFilledInJs && strlen($sFilledInJs) > 0 && $aFilledInJs = explode( ';', $sFilledInJs ) ) {
          if( count($aFilledInJs) > 0 ) {
            foreach( $aFilledInJs AS $sFilledInJs ) {
              $aFilledInJs = explode( ',', $sFilledInJs );
              if( $aFilledInJs && count( $aFilledInJs ) ) {
                foreach( $aForms AS $key => $aForm ) {
                  if( $aForm->id == $aFilledInJs[1] ) {
                    $aForms[$key]->selected = true;
                    $aSelectedForms[] = '<code>'.$aForm->name.'</code>';
                  }
                }
              }
            }
          }
        }

        $iSelectedForms = count( $aSelectedForms );
        $sSelectedForms = implode( ' ', $aSelectedForms );

        $sFilledInSpecials = $this->func__get_plugin_option('filled_in_specials');
        $aSelectedFormsSpecials = array();
        if( $sFilledInSpecials && strlen($sFilledInSpecials) > 0 && $aFilledInSpecials = explode( ';', $sFilledInSpecials ) ) {
          if( count($aFilledInSpecials) > 0 ) {
            foreach( $aFilledInSpecials AS $sFilledInSpecial ) {
              $aFilledInSpecial = explode( ',', $sFilledInSpecial );
              if( $aFilledInSpecial && count( $aFilledInSpecial ) ) {
                foreach( $aForms AS $key => $aForm ) {
                  if( $aForm->id == $aFilledInSpecial[1] ) {
                    $aForms[$key]->selectedSpecial = true;
                    $aSelectedFormsSpecials[] = $aForm->name;
                  }
                }
              }
            }
          }
        }

        if( $iSelectedForms > 0 ) {
          echo $sSelectedForms;
        } else {
          echo '(none selected)';
        }
        echo ' (<a href="#" onclick="jQuery(\'.fv-antispam-list-forms-2\').toggle(); return false">toggle</a>) ';
        ?>
        <table class="fv-antispam-list-forms-2 wp-list-table widefat fixed posts" style="display: none; margin-top: 10px">
          <thead>
          <tr>
            <th class="manage-column column-title sortable"><a>Form</a></th><th class="manage-column column-title sortable"><a>Use custom question</a></th><th class="manage-column column-title sortable"><a>Show in popup</a></th>
          </tr>
          </thead>
          <tbody id="the-list">
          <?php
          $iCount = 0;
          foreach( $aForms AS $key => $aForm ) {
            $class = ($iCount % 2 == 0 ) ? ' class="alt"' : '';
            $iCount++;
            echo '<tr'.$class.'>';
            echo '<td><label for="filled_in_custom-'.$key.'">'.$aForm->name.'</label> <small>(<a style="text-decoration: none; " target="_blank" href="'.site_url().'/wp-admin/tools.php?page=filled_in.php&edit='.$aForm->id.'">edit</a>)</small></td>';
            echo '<td><input id="filled_in_custom-'.$key.'" name="filled_in_custom[]" value="'.$aForm->name.','.$aForm->id.'" type="checkbox" '.( isset($aForm->selected) && $aForm->selected ? ' checked="checked" ' : '' ).'/></td>';
            echo '<td><input id="filled_in_specials-'.$key.'" name="filled_in_specials[]" value="'.$aForm->name.','.$aForm->id.'" type="checkbox" '.( isset($aForm->selectedSpecial) && $aForm->selectedSpecial ? ' checked="checked" ' : '' ).'/></td>';
            echo '</tr>'."\n";
          }
          echo '</table>'."\n";
          } else {
            echo 'Strange, no Filled in database tables found!';
          } ?>
          </tbody>
        </table>
      </td>
    </tr>


    <tr>
      <td>
        Custom questions <a id="fv-antispam-question-add"><small>Add more</small></a><br />
        <div id="fv-antispam-questions">
          <?php
          $aQuestions = explode( "\n", $this->func__get_plugin_option('questions') );

          foreach( $aQuestions AS $aQuestion ) {
            list( $sAnswer, $sQuestion ) = explode( ",", strrev($aQuestion), 2 );
            $sQuestion = strrev($sQuestion);
            $sAnswer = strrev($sAnswer);
            ?>
            <p><input type="text" value="<?php echo esc_attr($sQuestion); ?>" name="question[]" class="regular-text" /> <input type="text" value="<?php echo esc_attr($sAnswer); ?>" name="answer[]" /> <input type="button" value="Remove" class="button fv-antispam-question-remove" /></p>
            <?php
          }
          ?>
          <p class="template"><input type="text" value="" name="question[]" class="regular-text" /> <input type="text" value="" name="answer[]" /> <input type="button" value="Remove" class="button fv-antispam-question-remove" /></p>
        </div>
        <style>
          #fv-antispam-questions .button { display: none; }
          #fv-antispam-questions .template { display: none; }
        </style>
        <script>
          function fv_antispam_settings_js() {
            jQuery('#fv-antispam-questions p').hover(
              function() {
                jQuery(this).find('.button').show();
              }, function() {
                jQuery(this).find('.button').hide();
              }
            );
            jQuery('.fv-antispam-question-remove').click( function() {
              jQuery(this).parent().remove();
            } );
          }
          jQuery(document).ready( function() {
            fv_antispam_settings_js();
            jQuery('#fv-antispam-question-add').click( function() {
              jQuery('#fv-antispam-questions').append( '<p>'+jQuery('#fv-antispam-questions .template').html()+'</p>' );
              fv_antispam_settings_js();
            } );

          } );
        </script>
      </td>
    </tr>

  </table>
</div>
<?php else : ?>
  <p>Filled in not installed.</p>
<?php endif;