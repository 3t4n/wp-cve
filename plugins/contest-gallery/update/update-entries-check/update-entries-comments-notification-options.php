<?php

    if(empty($nextIDgallery)){
        $nextIDgallery = $galeryNR;
    }

    $CommNoteAddressor = get_option('blogname');
    $CommNoteReply = get_option('admin_email');
    $CommNoteSubject = 'A new comment has been entered';
    $CommNoteContent = 'Dear Sir or Madam<br/>a new comment has been entered<br/><br/>$comment$';
    $CommNoteContent = htmlentities($CommNoteContent, ENT_QUOTES);

    $wpdb->query($wpdb->prepare(
        "
                            INSERT INTO $tablename_comments_notification_options
                            ( 
                             id, GalleryID,
                             CommNoteAddressor, CommNoteAdminMail,
                            CommNoteCC,CommNoteBCC,CommNoteReply,
                            CommNoteSubject,CommNoteContent
                            )
                            VALUES ( %s,%d,
                                    %s,%s,
                            %s,%s,%s,
                            %s,%s)
                        ",
        '',$nextIDgallery,
        $CommNoteAddressor,'',
        '','',$CommNoteReply,
        $CommNoteSubject,$CommNoteContent
    ));

?>