
<?php $fields = get_option( 'wphr-employee-fields');
$notesarray = array();
/*//$jobes1 = array();
//$jobes2 = array();*/
if(!empty($fields))
{
foreach ($fields as $key => $field)
 {
   if($field['tab']=='Notes' && $field['section']=='notes')
    {
   array_push($notesarray, $field);
   }
}
}
    ?>
<div class="note-tab-wrap wphr-grid-container">

    <h3><?php _e( 'Notes', 'wphr' ) ?></h3>

    <form action="" class="note-form row" method="post">
        <?php wphr_html_form_input( array(
            'name'        => 'note',
            'required'    => true,
            'placeholder' => __( 'Add a note...', 'wphr' ),
            'type'        => 'textarea',
            'custom_attr' => array( 'rows' => 3, 'cols' => 30 )
        ) ); ?>
        <h3><?php _e( 'Extra Field Notes', 'wphr' ) ?></h3>

        <?php 

foreach ($notesarray as $key => $value) {
    ?>
    <label><?php echo $value['label']; ?>:</label>
    <?php
    # code...
    $name = 'additional['.$value['name'].']';
   // $name='additional['.$value['name'].']';
           wphr_html_form_input( array(
            'name'        => $name,
            'required'    => true,
            'placeholder' => __( 'Add a extra note...', 'wphr' ),
            'type'        => 'textarea',
            'custom_attr' => array( 'rows' => 2, 'cols' => 30 )
        ) ); 
}

         ?>
        <?php submit_button( __( 'Add Note', 'wphr' ), 'primary' ); ?>
           <?php

            ?>
        
        <input type="hidden" name="user_id" value="<?php echo $employee->id; ?>">
        <input type="hidden" name="action" id="wphr-employee-action" value="wphr-hr-employee-new-note">
        <?php wp_nonce_field( 'wp-wphr-hr-employee-nonce' ); ?>       
        <span class="wphr-loader wphr-note-loader"></span>
<div> <ul><?php //do_action( 'wphr-hr-employee-single-notes', $employee ); ?></ul></div>
<?php
    $no_of_notes = 10;
    $total_notes = $employee->count_notes();
    $notes = $employee->get_notes( $no_of_notes );
    ?>
         <?php 
       if ( $notes ) 
            {?>
            <?php
             foreach( $notes as $num => $note )
                 {?>
  <form>
    <h3>Extra Field output</h3>
                 <?php 
                     foreach ($notesarray as $keyr => $valuex) 
                     {
                    ?>
                    <label style="display: inline;"><?php echo $valuex['label']; ?>:</label>
                <th>
                <?php
                $additional = null;
                if($note->additional)
                {
                 $additional = unserialize($note->additional);  
                }
                if($additional) {
                    foreach ($additional as $kxey => $value) {
                       if($kxey==$valuex['name'])
                       {
                        ?>
                        <label>
                        <?php
                        echo $value;?>
                        </label>
                        <?php
                       }
                    }
                }
                ?>
                </th></br>
                    <?php
                }

                ?>     
  </form>



    </form>
   
        <ul>
            <?php //echo wpautop( $note->additional ); ?>
        </ul>
        <ul class="wphr-list notes-list">
            <li>
                <div class="avatar-wrap">
                    <?php echo get_avatar( $note->user->user_email, 64 ); ?>
                </div>
                <div class="note-wrap">
                    <div class="by">
                        <a href="#" class="author"><?php echo $note->user->display_name; ?></a>
                        <span class="date"><?php echo wphr_format_date( $note->created_at, __( 'M j, Y \a\t g:i a', 'wphr' ) ); ?></span>
                    </div>

                    <div class="note-body">
                        <?php echo wpautop( $note->comment ); ?> </br>
                        <?php //echo 'Extra:'. wpautop( $note->additional ); ?> </br>
                        
                    </div>
                    <?php if( current_user_can( 'manage_options' ) OR (wp_get_current_user()->ID == $note->comment_by ) ) { ?>
                        <div class="row-action">
                            <span class="delete"><a href="#" class="delete_note" data-note_id="<?php echo $note->id; ?>"><?php _e( 'Delete', 'wphr' ); ?></a></span>
                        </div>
                    <?php } ?>
                </div>
            </li>
            <?php } ?>
        </ul>

    <?php } ?>
     <?php  $display_class =  ( $no_of_notes < $total_notes ) ? 'show':'hide' ; ?>
    <div class="wpwphr-load-more-btn <?php echo $display_class?>">
            <?php submit_button( 'Load More', false, 'wphr-load-notes', true, array( 'id' => 'wphr-load-notes', 'data-total_no' => $total_notes, 'data-offset_no' => $no_of_notes, 'data-user_id' => $employee->id ) ); ?>

    </div>


</div>
