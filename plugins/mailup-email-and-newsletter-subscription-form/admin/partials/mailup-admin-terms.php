 <h2><?php _e('Terms and Conditions', 'mailup'); ?>
 </h2>
 <?php

    $terms_title = ['',
        // translators: Parameter "Term Index Included"
        __('%s - Usually required and used for general terms of service', 'mailup'),
        // translators: Parameter "Term Index Included"
        __('%s - Usually used for marketing communication', 'mailup'),
        // translators: Parameter "Term Index Included"
        __('%s - Usually used for data profilation', 'mailup'),
    ];
?>

 <span class="info"><?php _e('Here you can set your terms and conditions acceptances.', 'mailup'); ?></span>

 <?php

if (!$terms) {
    $terms = array_fill(
        0,
        3,
        (object) [
            'show' => false,
            'required' => false,
            'text' => '',
        ]
    );
}
array_unshift($terms, new stdClass());
?>

 <!-- TERMS -->
 <?php for ($i = 1; $i < 4; ++$i) { ?>
 <h4><?php echo sprintf($terms_title[$i], $i); ?>
 </h4>
 <table class="form-table terms-and-condition" id="<?php echo sprintf('terms-and-condition-%s', $i); ?>">
     <tbody>
         <tr>
             <td>
                 <label><?php _e('Include', 'mailup'); ?>:</label><input
                     name="<?php echo sprintf('terms-show[%s]', $i); ?>"
                     <?php echo ($terms[$i]->show) ? 'checked' : ''; ?> id="<?php echo sprintf('terms-show-%s', $i); ?>"
                     class="chk-show" type="checkbox">
                 <label><?php _e('Required', 'mailup'); ?>:</label><input
                     name="<?php echo sprintf('terms-required[%s]', $i); ?>"
                     <?php echo ($terms[$i]->required) ? 'checked' : ''; ?>
                     id="<?php echo sprintf('terms-required-%s', $i); ?>" class="chk-required" type="checkbox">
             </td>
         </tr>
         <tr>
             <td class="editor">
                 <?php wp_editor($terms[$i]->text, sprintf('terms-and-condition-text-%s', $i), $settings = ['textarea_name' => ''.sprintf('terms-text-%s', $i).'', 'media_buttons' => 0, 'wpautop' => 0]); ?>
             </td>
         </tr>
     </tbody>
 </table>
 <span
     class="info"><?php
    // translators: First Parameter "Group Name" - Second Parameter "Term Index Included"
    echo sprintf(__('whoever accepts, will be included in the group <b><span>%1$s</span> Terms and Conditions %2$s</b>', 'mailup'), $form_mup->group, $i); ?>
 </span>
 <div class="separator-with-border"></div>
        <?php
 }
?>
 <!-- END TERMS -->