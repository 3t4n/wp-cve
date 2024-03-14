<?php

// 1. Delete Felder in Entries, F_Input, F_Output
// 2. Swap Field_Order in Entries, F_Input, F_Output (bei post "done-upload" wird alles mitgegeben
// 3. Neue Felder hinzuf�gen in F_Input, Entries
// 4. // Auswahl zum Anzeigen gespeicherter Felder

// Empfangen von Galerie OptiOns ID

$GalleryID = absint($_GET['option_id']);

global $wpdb;

// Tabellennamen bestimmen

$tablename = $wpdb->prefix . "contest_gal1ery";
$tablenameoptions = $wpdb->prefix . "contest_gal1ery_options";
$tablenameentries = $wpdb->prefix . "contest_gal1ery_entries";
$tablename_create_user_form = $wpdb->prefix . "contest_gal1ery_create_user_form";
$tablename_create_user_entries = $wpdb->prefix . "contest_gal1ery_create_user_entries";
$tablename_form_output = $wpdb->prefix . "contest_gal1ery_f_output";
$tablename_options_visual = $wpdb->prefix . "contest_gal1ery_options_visual";
$wp_usermeta_table = $wpdb->prefix . "usermeta";

$cgOptions = $wpdb->get_row($wpdb->prepare( "SELECT GalleryName,Version FROM $tablenameoptions WHERE id = %d",[$GalleryID]));

$GalleryName = $cgOptions->GalleryName;
$galleryDbVersion = $cgOptions->Version;

if(intval($galleryDbVersion)>=14){
    $GalleryIDorGeneralIDstring = 'GeneralID';
    $GalleryIDorGeneralIDnumber = 1;
    $GeneralID = 1;
    $GeneralIDtoInsert = 0;
}else{
    $GalleryIDorGeneralIDstring = 'GalleryID';
    $GalleryIDorGeneralIDnumber = $GalleryID;
    $GeneralID = 0;
    $GeneralIDtoInsert = $GalleryID;
}

// Check if certain fieldnumber should be deleted

//
// Vorgehen: Zuerst Feld l�schen falls einz mitgeschickt wurde zum l�schen. Dann pr�fen welche IDs mitgeschickt wurden (beim erstellten Formular) und diese in f_output und f_entries eing�gen. Die alten
// die drin wahren durch die neuen ersetzten
// Dann pauschal existierendes f_input l�schen und die neuen mitgeschicktern werte komplett neu einf�gen "INSERT"

if(!isset($_POST['deleteFieldnumber'])){
    $_POST['deleteFieldnumber'] = false;
}

if(!isset($_POST['Necessary'])){
    $_POST['Necessary'] = [];
}

if(!empty($_POST['deleteFieldnumber'])){

    $deleteFieldnumber = intval($_POST['deleteFieldnumber']);

    if(intval($galleryDbVersion)>=14){

        // wpfn, wpln and profile image will be not deleted because named other way or stored in other table
        $wpdb->query($wpdb->prepare(
            "
				DELETE FROM $wp_usermeta_table WHERE meta_key = %s
			",
            'cg_custom_field_id_'.$deleteFieldnumber
        ));

        $wpdb->query( $wpdb->prepare(
            "
				DELETE FROM $tablename_create_user_form WHERE GeneralID = %d AND id = %d
			",
            1, $deleteFieldnumber
        ));

    }else{

        $wpdb->query( $wpdb->prepare(
            "
                DELETE FROM $tablename_create_user_form WHERE GalleryID = %d AND id = %d
            ",
                $GalleryID, $deleteFieldnumber
         ));

        $wpdb->query( $wpdb->prepare(
            "
                DELETE FROM $tablename_create_user_entries WHERE GalleryID = %d AND f_input_id = %d
            ",
                $GalleryID, $deleteFieldnumber
         ));

    }

}

// Check if certain fieldnumber should be deleted --- ENDE

// Abspeichern von gesendeten Daten

if (!empty($_POST['submit'])) {
/*
  echo "<pre>";
    print_r($_POST);
    echo "</pre>";*/

    check_admin_referer( 'cg_admin');


// Neue Formularfelder werden eingef�gt


$get_Field_Id = (!empty($_POST['Field_Id'])) ? $_POST['Field_Id'] : [];
$get_Field_Type = (!empty($_POST['Field_Type'])) ? $_POST['Field_Type'] : [];
$get_Field_Name = (!empty($_POST['Field_Name'])) ? $_POST['Field_Name'] : [];
$get_ReCaKey = (!empty($_POST['ReCaKey'])) ? sanitize_text_field($_POST['ReCaKey']) : '';
$get_ReCaLang = (!empty($_POST['ReCaLang'])) ? sanitize_text_field($_POST['ReCaLang']) : '';
$get_Field_Content = (!empty($_POST['Field_Content'])) ? $_POST['Field_Content'] : [];
$get_Min_Char = (!empty($_POST['Min_Char'])) ? $_POST['Min_Char'] : [];
$get_Max_Char = (!empty($_POST['Max_Char'])) ? $_POST['Max_Char'] : [];
$get_Necessary = (!empty($_POST['Necessary'])) ? $_POST['Necessary'] : [];
$get_Hide = (!empty($_POST['Hide'])) ? $_POST['Hide'] : [];


// Dient zur Orientierung zum Abarbeiten
$i=1;

    $fieldOrder = 0;


foreach($get_Field_Type as $key => $value){

		// Das gel�schte Feld soll nicht nochmal kreiert werden. Unbedingt auf true pr�fen! Ansonsten bei zwei leeren Werten ist die Bedingung auch erf�llt.
		if(!empty($_POST['deleteFieldnumber']) && !empty($get_Field_Id[$i]) && $_POST['deleteFieldnumber']==$get_Field_Id[$i]){continue;}

        if($value=="profile-image"){

            $fieldOrder++;

            $Active = 1;
            if(!empty($get_Hide[$i])){
                $Active = 0;
            }

            if(!isset($get_Necessary[$i])){
                $get_Necessary[$i] = false;
            }

            if($get_Necessary[$i]=='on'){$update_Necessary=1;}
            else{$update_Necessary=0;}

            $get_Field_Name[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Name[$i]);

            if(isset($get_Field_Id[$i])){
                $wpdb->update(
                    "$tablename_create_user_form",
                    array('Field_Order' => $i, 'Field_Name' => $get_Field_Name[$i],
                        'Field_Content' => '','Min_Char' => '','Max_Char' => '', 'Required' => $update_Necessary, 'Active' => $Active),
                    array('id' => $get_Field_Id[$i]),
                    array('%d','%s',
                        '%s','%s','%s','%d','%d'),
                    array('%d')
                );
            }
            else{

                $wpdb->query( $wpdb->prepare(
                    "
                                INSERT INTO $tablename_create_user_form
                                ( id, GalleryID, Field_Type, Field_Order,
                                Field_Name,Field_Content,Min_Char,Max_Char,
                                Required,Active,GeneralID)
                                VALUES ( %s,%d,%s,%d,
                                %s,%s,%d,%d,
                                %d,%d,%d)
                            ",
                    '',$GeneralIDtoInsert,'profile-image',$i,
                    $get_Field_Name[$i],'','','',
                    $update_Necessary,$Active,$GeneralID
                ) );

            }

        }

        if($value=="wpfn"){

            $fieldOrder++;
            $Active = 1;
            if(!empty($get_Hide[$i])){
                $Active = 0;
            }

            if(!isset($get_Necessary[$i])){
                $get_Necessary[$i] = false;
            }

            if($get_Necessary[$i]=='on'){$update_Necessary=1;}
            else{$update_Necessary=0;}

            $get_Field_Name[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Name[$i]);
            $get_Field_Content[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Content[$i]);

            if(isset($get_Field_Id[$i])){
                $wpdb->update(
                    "$tablename_create_user_form",
                    array('Field_Order' => $i, 'Field_Name' => $get_Field_Name[$i],
                        'Field_Content' => $get_Field_Content[$i],'Min_Char' => $get_Min_Char[$i],'Max_Char' => $get_Max_Char[$i], 'Required' => $update_Necessary, 'Active' => $Active),
                    array('id' => $get_Field_Id[$i]),
                    array('%d','%s',
                        '%s','%d','%d','%d','%d'),
                    array('%d')
                );
            }
            else{

                $wpdb->query( $wpdb->prepare(
                    "
                                INSERT INTO $tablename_create_user_form
                                ( id, GalleryID, Field_Type, Field_Order,
                                Field_Name,Field_Content,Min_Char,Max_Char,
                                Required,Active,GeneralID)
                                VALUES ( %s,%d,%s,%d,
                                %s,%s,%d,%d,
                                %d,%d,%d)
                            ",
                    '',$GeneralIDtoInsert,'wpfn',$i,
                    $get_Field_Name[$i],$get_Field_Content[$i],$get_Min_Char[$i],$get_Max_Char[$i],
                    $update_Necessary,$Active,$GeneralID
                ) );

            }

        }

        if($value=="wpln"){

            $fieldOrder++;
            $Active = 1;
            if(!empty($get_Hide[$i])){
                $Active = 0;
            }

            if(!isset($get_Necessary[$i])){
                $get_Necessary[$i] = false;
            }

            if($get_Necessary[$i]=='on'){$update_Necessary=1;}
            else{$update_Necessary=0;}

            $get_Field_Name[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Name[$i]);
            $get_Field_Content[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Content[$i]);

            if(isset($get_Field_Id[$i])){
                $wpdb->update(
                    "$tablename_create_user_form",
                    array('Field_Order' => $i, 'Field_Name' => $get_Field_Name[$i],
                        'Field_Content' => $get_Field_Content[$i],'Min_Char' => $get_Min_Char[$i],'Max_Char' => $get_Max_Char[$i], 'Required' => $update_Necessary, 'Active' => $Active),
                    array('id' => $get_Field_Id[$i]),
                    array('%d','%s',
                        '%s','%d','%d','%d','%d'),
                    array('%d')
                );
            }
            else{

                $wpdb->query( $wpdb->prepare(
                    "
                                INSERT INTO $tablename_create_user_form
                                ( id,GalleryID , Field_Type, Field_Order,
                                Field_Name,Field_Content,Min_Char,Max_Char,
                                Required,Active,GeneralID)
                                VALUES ( %s,%d,%s,%d,
                                %s,%s,%d,%d,
                                %d,%d,%d)
                            ",
                    '',$GeneralIDtoInsert,'wpln',$i,
                    $get_Field_Name[$i],$get_Field_Content[$i],$get_Min_Char[$i],$get_Max_Char[$i],
                    $update_Necessary,$Active,$GeneralID
                ) );

            }

        }

        if($value=="user-text-field"){

            $fieldOrder++;
            $Active = 1;
            if(!empty($get_Hide[$i])){
                $Active = 0;
            }

            if(!isset($get_Necessary[$i])){
                $get_Necessary[$i] = false;
            }

            if($get_Necessary[$i]=='on'){$update_Necessary=1;}
            else{$update_Necessary=0;}

            $get_Field_Name[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Name[$i]);
            $get_Field_Content[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Content[$i]);

            if(isset($get_Field_Id[$i])){
                $wpdb->update(
                    "$tablename_create_user_form",
                    array('Field_Order' => $i, 'Field_Name' => $get_Field_Name[$i],
                        'Field_Content' => $get_Field_Content[$i],'Min_Char' => $get_Min_Char[$i],'Max_Char' => $get_Max_Char[$i], 'Required' => $update_Necessary, 'Active' => $Active),
                    array('id' => $get_Field_Id[$i]),
                    array('%d','%s',
                        '%s','%d','%d','%d','%d'),
                    array('%d')
                );
            }
            else{

                $wpdb->query( $wpdb->prepare(
                    "
                                INSERT INTO $tablename_create_user_form
                                ( id, GalleryID, Field_Type, Field_Order,
                                Field_Name,Field_Content,Min_Char,Max_Char,
                                Required,Active,GeneralID)
                                VALUES ( %s,%d,%s,%d,
                                %s,%s,%d,%d,
                                %d,%d,%d)
                            ",
                    '',$GeneralIDtoInsert,'user-text-field',$i,
                    $get_Field_Name[$i],$get_Field_Content[$i],$get_Min_Char[$i],$get_Max_Char[$i],
                    $update_Necessary,$Active,$GeneralID
                ) );

            }

        }
		
		if($value=="user-check-agreement-field"){

            $fieldOrder++;
            $Active = 1;
            if(!empty($get_Hide[$i])){
                $Active = 0;
            }

            $update_Necessary=1;// so far check agreement is always required

            $get_Field_Name[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Name[$i]);
            $get_Field_Content[$i] = contest_gal1ery_htmlentities_and_preg_replace($get_Field_Content[$i]);

            if(!isset($get_Field_Content[$i])){
                $get_Field_Content[$i] = '';
            }

            if(isset($get_Field_Id[$i])){
						$wpdb->update(
						"$tablename_create_user_form",
						array('Field_Order' => $i, 'Field_Name' => $get_Field_Name[$i],
						'Field_Content' => $get_Field_Content[$i],'Min_Char' => 0,'Max_Char' => 0, 'Required' => $update_Necessary, 'Active' => $Active),
						array('id' => $get_Field_Id[$i]),
						array('%d','%s',
						'%s','%s','%s','%d','%d'),
						array('%d')
						);					
					}
					else{						
						$wpdb->query( $wpdb->prepare(
						"
							INSERT INTO $tablename_create_user_form
							( id, GalleryID, Field_Type, Field_Order,
							Field_Name,Field_Content,Min_Char,Max_Char,
							Required,Active,GeneralID)
							VALUES ( %s,%d,%s,%d,
							%s,%s,%d,%d,
							%d,%d,%d)
						",
							'',$GeneralIDtoInsert,'user-check-agreement-field',$i,
							$get_Field_Name[$i],$get_Field_Content[$i],'','',
                            $update_Necessary,$Active,$GeneralID
						) );						
					}			

		}


		if($value=="user-robot-field"){

                    $fieldOrder++;
            $Active = 1;
            if(!empty($get_Hide[$i])){
                $Active = 0;
            }


					$get_Field_Name[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Name[$i]);

					if(isset($get_Field_Id[$i])){


						$wpdb->update(
                            "$tablename_create_user_form",
                            array('Field_Order' => $i, 'Field_Name' => $get_Field_Name[$i],
                            'Field_Content' => '','Min_Char' => '','Max_Char' => '', 'Active' => $Active),
                            array('id' => $get_Field_Id[$i]),
                            array('%d','%s',
                            '%s','%s','%s','%d'),
                            array('%d')
						);
					}
					else{

						$wpdb->query( $wpdb->prepare(
						"
							INSERT INTO $tablename_create_user_form
							( id, GalleryID, Field_Type, Field_Order,
							Field_Name,Field_Content,Min_Char,Max_Char,
							Required,Active,GeneralID)
							VALUES ( %s,%d,%s,%d,
							%s,%s,%d,%d,
							%d,%d,%d)
						",
							'',$GeneralIDtoInsert,'user-robot-field',$i,
							$get_Field_Name[$i],'','','',
							1,$Active,$GeneralID
						) );
					}

		}

		if($value=="user-robot-recaptcha-field"){

                    $fieldOrder++;
            $Active = 1;
            if(!empty($get_Hide[$i])){
                $Active = 0;
            }

					$get_Field_Name[$i]=contest_gal1ery_htmlentities_and_preg_replace((isset($get_Field_Name[$i])) ? $get_Field_Name[$i] : '');

					if(isset($get_Field_Id[$i])){
						$wpdb->update(
                            "$tablename_create_user_form",
                            array('Field_Order' => $i, 'Field_Name' => $get_Field_Name[$i],
                            'Field_Content' => '','Min_Char' => '','Max_Char' => '', 'Active' => $Active, 'ReCaKey' => $get_ReCaKey, 'ReCaLang' => $get_ReCaLang),
                            array('id' => $get_Field_Id[$i]),
                            array('%d','%s',
                            '%s','%s','%s','%d','%s','%s'),
                            array('%d')
						);
					}
					else{

						$wpdb->query( $wpdb->prepare(
						"
							INSERT INTO $tablename_create_user_form
							( id, GalleryID, Field_Type, Field_Order,
							Field_Name,Field_Content,Min_Char,Max_Char,
							Required,Active,ReCaKey,ReCaLang,GeneralID)
							VALUES ( %s,%d,%s,%d,
							%s,%s,%d,%d,
							%d,%d,%s,%s,%d)
						",
							'',$GeneralIDtoInsert,'user-robot-recaptcha-field',$i,
							$get_Field_Name[$i],'','','',
							1,$Active,$get_ReCaKey,$get_ReCaLang,$GeneralID
						) );
					}

		}
	
			if($value=="user-comment-field"){

                $fieldOrder++;
                $Active = 1;
                if(!empty($get_Hide[$i])){
                    $Active = 0;
                }
				
                $get_Field_Name[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Name[$i]);
                $get_Field_Content[$i]=contest_gal1ery_htmlentities_and_preg_replace_textarea($get_Field_Content[$i]);


                if(!isset($get_Necessary[$i])){
                    $get_Necessary[$i] = false;
                }

				if($get_Necessary[$i]=='on'){$update_Necessary=1;}
				else{$update_Necessary=0;}
			
					if(isset($get_Field_Id[$i])){
						$wpdb->update(
						"$tablename_create_user_form",
						array('Field_Order' => $i, 'Field_Name' => $get_Field_Name[$i],
						'Field_Content' => $get_Field_Content[$i],'Min_Char' => $get_Min_Char[$i],'Max_Char' => $get_Max_Char[$i], 'Required' => $update_Necessary, 'Active' => $Active),
						array('id' => $get_Field_Id[$i]),
						array('%d','%s',
						'%s','%d','%d','%d','%d'),
						array('%d')
						);							
					}
					else{					
						$wpdb->query( $wpdb->prepare(
						"
							INSERT INTO $tablename_create_user_form
							( id, GalleryID, Field_Type, Field_Order,
							Field_Name,Field_Content,Min_Char,Max_Char,
							Required,Active,GeneralID)
							VALUES ( %s,%d,%s,%d,
							%s,%s,%d,%d,
							%d,%d,%d)
						",
							'',$GeneralIDtoInsert,'user-comment-field',$i,
							$get_Field_Name[$i],$get_Field_Content[$i],$get_Min_Char[$i],$get_Max_Char[$i],
							$update_Necessary,$Active,$GeneralID
						) );						
					}
		
		}
	


	if($value=="user-select-field"){

        $fieldOrder++;
        $Active = 1;
        if(!empty($get_Hide[$i])){
            $Active = 0;
        }

        if(!isset($get_Necessary[$i])){
            $get_Necessary[$i] = false;
        }

        if(!isset($get_Necessary[$i])){
            $get_Necessary[$i] = false;
        }

				if($get_Necessary[$i]=='on'){$update_Necessary=1;}
				else{$update_Necessary=0;}

				    // to go sure, to avoid eventually error 05 December 2021
                    $get_Field_Name[$i] = (!empty($get_Field_Name[$i])) ? $get_Field_Name[$i] : '';

					$get_Field_Name[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Name[$i]);
					$get_Field_Content[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Content[$i]);

					if(isset($get_Field_Id[$i])){
						$wpdb->update(
						"$tablename_create_user_form",
						array('Field_Order' => $i, 'Field_Name' => $get_Field_Name[$i],
						'Field_Content' => $get_Field_Content[$i],'Min_Char' => '','Max_Char' => '', 'Required' => $update_Necessary, 'Active' => $Active),
						array('id' => $get_Field_Id[$i]),
						array('%d','%s',
						'%s','%s','%s','%d','%d'),
						array('%d')
						);
					}
					else{
						$wpdb->query( $wpdb->prepare(
						"
							INSERT INTO $tablename_create_user_form
							( id, GalleryID, Field_Type, Field_Order,
							Field_Name,Field_Content,Min_Char,Max_Char,
							Required,Active,GeneralID)
							VALUES ( %s,%d,%s,%d,
							%s,%s,%s,%s,
							%d,%d,%d)
						",
							'',$GeneralIDtoInsert,'user-select-field',$i,
							$get_Field_Name[$i],$get_Field_Content[$i],'','',
							$update_Necessary,$Active,$GeneralID
						) );

					}


	}

	if($value=="main-user-name"){
		
					$get_Field_Name[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Name[$i]);
					$get_Field_Content[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Content[$i]);

						if($get_Field_Id[$i]){
							$wpdb->update(
							"$tablename_create_user_form",
							array('Field_Order' => $i, 'Field_Name' => $get_Field_Name[$i],
							'Field_Content' => $get_Field_Content[$i],'Min_Char' => $get_Min_Char[$i],'Max_Char' => $get_Max_Char[$i]),
							array('id' => $get_Field_Id[$i]),
							array('%d','%s',
							'%s','%s','%s'),
							array('%d')
							);
						}
						
		}

	if($value=="main-nick-name"){

					$get_Field_Name[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Name[$i]);
					$get_Field_Content[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Content[$i]);

						if($get_Field_Id[$i]){
							$wpdb->update(
							"$tablename_create_user_form",
							array('Field_Order' => $i, 'Field_Name' => $get_Field_Name[$i],
							'Field_Content' => $get_Field_Content[$i],'Min_Char' => $get_Min_Char[$i],'Max_Char' => $get_Max_Char[$i]),
							array('id' => $get_Field_Id[$i]),
							array('%d','%s',
							'%s','%s','%s'),
							array('%d')
							);
						}

	}
	
	if($value=="main-mail"){
		
            $get_Field_Name[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Name[$i]);
            $get_Field_Content[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Content[$i]);


        if(!isset($get_Field_Content[$i])){
            $get_Field_Content[$i] = '';
        }

        if($get_Field_Id[$i]){
                $wpdb->update(
                "$tablename_create_user_form",
                array('Field_Order' => $i, 'Field_Name' => $get_Field_Name[$i],
                'Field_Content' => $get_Field_Content[$i],'Min_Char' => 0,'Max_Char' => 0),
                array('id' => $get_Field_Id[$i]),
                array('%d','%s',
                '%s','%s','%s'),
                array('%d')
                );
        }
		
						
	}
	
	if($value=="password"){
		
					$get_Field_Name[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Name[$i]);
					$get_Field_Content[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Content[$i]);
		
					if($get_Field_Id[$i]){
						
						$wpdb->update(
							"$tablename_create_user_form",
							array('Field_Order' => $i, 'Field_Name' => $get_Field_Name[$i],
							'Field_Content' => $get_Field_Content[$i],'Min_Char' => $get_Min_Char[$i],'Max_Char' => $get_Max_Char[$i]),
							array('id' => $get_Field_Id[$i]),
							array('%d','%s',
							'%s','%s','%s'),
							array('%d')
						);

					}
					
		}
	    if($value=="password-confirm"){

					$get_Field_Name[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Name[$i]);
					$get_Field_Content[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Content[$i]);
		
					if($get_Field_Id[$i]){
						$wpdb->update(
						"$tablename_create_user_form",
						array('Field_Order' => $i, 'Field_Name' => $get_Field_Name[$i],
						'Field_Content' => $get_Field_Content[$i],'Min_Char' => $get_Min_Char[$i],'Max_Char' => $get_Max_Char[$i]),
						array('id' => $get_Field_Id[$i]),
						array('%d','%s',
						'%s','%s','%s'),
						array('%d')
						);							
					}
		}

	if($value=="user-html-field" && $cgProVersion){

        $fieldOrder++;
        $Active = 1;
        if(!empty($get_Hide[$i])){
            $Active = 0;
        }

        $get_Field_Name[$i]=contest_gal1ery_htmlentities_and_preg_replace($get_Field_Name[$i]);
                    $get_Field_Content[$i] = contest_gal1ery_htmlentities_and_preg_replace($get_Field_Content[$i]);


        if(!empty($get_Field_Id[$i])){

						$wpdb->update(
						"$tablename_create_user_form",
						array('Field_Order' => $i, 'Field_Name' => $get_Field_Name[$i],
						'Field_Content' => $get_Field_Content[$i],'Min_Char' => '','Max_Char' => '', 'Required' => '', 'Active' => $Active),
						array('id' => $get_Field_Id[$i]),
						array('%d','%s',
						'%s','%s','%s','%d','%d'),
						array('%d')
						);
						
					}
					else{			
						$wpdb->query( $wpdb->prepare(
						"
							INSERT INTO $tablename_create_user_form
							( id, GalleryID, Field_Type, Field_Order,
							Field_Name,Field_Content,Min_Char,Max_Char,
							Required,Active,GeneralID)
							VALUES ( %s,%d,%s,%d,
							%s,%s,%d,%d,
							%d,%d,%d)
						",
							'',$GeneralIDtoInsert,'user-html-field',$i,
							$get_Field_Name[$i],$get_Field_Content[$i],'','',
							'',$Active,$GeneralID
						) );						
					}

		}

// Dient zur Orientierung zum Abarbeiten
$i++;
}

}

if(intval($galleryDbVersion)>=14){
    $selectFormInput = $wpdb->get_results("SELECT * FROM $tablename_create_user_form WHERE GeneralID = 1 ORDER BY Field_Order ASC");
}else{// then old form
    $selectFormInput = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $tablename_create_user_form WHERE GalleryID = %d ORDER BY Field_Order ASC",[$GalleryID]));
}

$checkDataFormOutput = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $tablename_create_user_form WHERE GalleryID = %d and (Field_Type = 'comment-f' or Field_Type = 'text-f' or Field_Type = 'email-f')",[$GalleryID]));

//print_r($checkDataFormOutput);

$rowVisualOptions = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $tablename_options_visual WHERE GalleryID = %d",[$GalleryID]));

$Field1IdGalleryView = $rowVisualOptions->Field1IdGalleryView;


// Pr�fen ob es ein Feld gibt welches als Images URL genutzt werden soll
//$Use_as_URL = $wpdb->get_var("SELECT Use_as_URL FROM $tablename_create_user_form WHERE GalleryID = '$GalleryID' AND Use_as_URL = '1'");
//$Use_as_URL_id = $wpdb->get_var("SELECT id FROM $tablename_create_user_form WHERE GalleryID = '$GalleryID' AND Use_as_URL = '1'");




?>