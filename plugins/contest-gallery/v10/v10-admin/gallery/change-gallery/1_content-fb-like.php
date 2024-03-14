<?php

    $fbcontent = (isset($_POST['fbcontent']) ? $_POST['fbcontent'] : '');

    if(!empty($fbcontent)){

        foreach ($fbcontent as $imageId => $fbContentArray){

            if(file_exists($fbcontent[$imageId]['baseUrlForFacebook'])){

            //    var_dump($imageId);

                $handle = fopen($fbcontent[$imageId]['baseUrlForFacebook'], "r");

                if ($handle) {

                    $newFileContent = '';
                    $isTitlePassed = false;
                    $isTitleHeaderPassed = false;
                    $isDescriptionPassed = false;
                    $isFbVersionReplaced= false;

                    while (($line = fgets($handle)) !== false) {

                        if($isTitlePassed){
                            if($isTitleHeaderPassed){
                                if($isDescriptionPassed){
                                    if(!$isFbVersionReplaced){
                                        if(strpos($line,'version=v2.8')!==false){
                                            $isFbVersionReplaced = true;
                                            $line = str_replace('version=v2.8','version=v3.0',$line);
                                        }
                                    }
                                }
                            }
                        }


                        if($isTitlePassed){
                            if($isTitleHeaderPassed){
                                if(!$isDescriptionPassed){
                                    $line = '<meta property="og:description"   content="'.$fbcontent[$imageId]['description'].'" />'."\n";
                                    $isDescriptionPassed = true;
                                }
                            }
                        }

                        if($isTitleHeaderPassed){
                            if(!$isTitlePassed){
                                if(strpos($line,'property="og:title"')!==false){
                                    $isTitlePassed = true;
                                    $line = '<meta property="og:title"   content="'.$fbcontent[$imageId]['title'].'" />'."\n";
                                }
                            }
                        }

                        if(!$isTitleHeaderPassed){
                            if(strpos($line,'<title>')!==false){
                                $isTitleHeaderPassed = true;
                                $line = '<title>'.$fbcontent[$imageId]['title'].'</title>'."\n";
                            }
                        }

                        $newFileContent .= $line;

                    }

                    fclose($handle);

                    $fp = fopen($fbcontent[$imageId]['baseUrlForFacebook'], 'w');
                    fwrite($fp, $newFileContent);
                    fclose($fp);

                }
            }



        }


    }

