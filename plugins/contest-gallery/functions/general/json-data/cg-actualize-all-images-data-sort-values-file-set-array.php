<?php

add_action('cg_actualize_all_images_data_sort_values_file_set_array','cg_actualize_all_images_data_sort_values_file_set_array');
if(!function_exists('cg_actualize_all_images_data_sort_values_file_set_array')){
    function cg_actualize_all_images_data_sort_values_file_set_array($allImagesArray,$imageDataArray,$imageId,$IsModernFiveStar = false, $isResetViaManipulationAddedVotesOneStar = false, $isResetViaManipulationAddedVotesMultipleStars = false){
        $allImagesArray[$imageId]['id'] = $imageId;
        $allImagesArray[$imageId]['Rating'] = (!empty($imageDataArray['Rating']) ? $imageDataArray['Rating'] : 0);
        $allImagesArray[$imageId]['CountC'] = (!empty($imageDataArray['CountC']) ? $imageDataArray['CountC'] : 0);
        $allImagesArray[$imageId]['CountCtoReview'] = (!empty($imageDataArray['CountCtoReview']) ? $imageDataArray['CountCtoReview'] : 0);
        $allImagesArray[$imageId]['CountR'] = (!empty($imageDataArray['CountR']) ? $imageDataArray['CountR'] : 0);
        $allImagesArray[$imageId]['CountS'] = (!empty($imageDataArray['CountS']) ? $imageDataArray['CountS'] : 0);
        $allImagesArray[$imageId]['addCountS'] = (!empty($imageDataArray['addCountS']) AND !$isResetViaManipulationAddedVotesOneStar) ? $imageDataArray['addCountS'] : 0;
        $allImagesArray[$imageId]['addCountR1'] = (!empty($imageDataArray['addCountR1']) AND !$isResetViaManipulationAddedVotesMultipleStars) ? $imageDataArray['addCountR1'] : 0;
        $allImagesArray[$imageId]['addCountR2'] = (!empty($imageDataArray['addCountR2']) AND !$isResetViaManipulationAddedVotesMultipleStars) ? $imageDataArray['addCountR2'] : 0;
        $allImagesArray[$imageId]['addCountR3'] = (!empty($imageDataArray['addCountR3']) AND !$isResetViaManipulationAddedVotesMultipleStars) ? $imageDataArray['addCountR3'] : 0;
        $allImagesArray[$imageId]['addCountR4'] = (!empty($imageDataArray['addCountR4']) AND !$isResetViaManipulationAddedVotesMultipleStars) ? $imageDataArray['addCountR4'] : 0;
        $allImagesArray[$imageId]['addCountR5'] = (!empty($imageDataArray['addCountR5']) AND !$isResetViaManipulationAddedVotesMultipleStars) ? $imageDataArray['addCountR5'] : 0;
        $allImagesArray[$imageId]['addCountR6'] = (!empty($imageDataArray['addCountR6']) AND !$isResetViaManipulationAddedVotesMultipleStars) ? $imageDataArray['addCountR6'] : 0;
        $allImagesArray[$imageId]['addCountR7'] = (!empty($imageDataArray['addCountR7']) AND !$isResetViaManipulationAddedVotesMultipleStars) ? $imageDataArray['addCountR7'] : 0;
        $allImagesArray[$imageId]['addCountR8'] = (!empty($imageDataArray['addCountR8']) AND !$isResetViaManipulationAddedVotesMultipleStars) ? $imageDataArray['addCountR8'] : 0;
        $allImagesArray[$imageId]['addCountR9'] = (!empty($imageDataArray['addCountR9']) AND !$isResetViaManipulationAddedVotesMultipleStars) ? $imageDataArray['addCountR9'] : 0;
        $allImagesArray[$imageId]['addCountR10'] = (!empty($imageDataArray['addCountR10']) AND !$isResetViaManipulationAddedVotesMultipleStars) ? $imageDataArray['addCountR10'] : 0;
        if($IsModernFiveStar){
            $allImagesArray[$imageId]['CountR1'] = (!empty($imageDataArray['CountR1']) ? $imageDataArray['CountR1'] : 0);
            $allImagesArray[$imageId]['CountR2'] = (!empty($imageDataArray['CountR2']) ? $imageDataArray['CountR2'] : 0);
            $allImagesArray[$imageId]['CountR3'] = (!empty($imageDataArray['CountR3']) ? $imageDataArray['CountR3'] : 0);
            $allImagesArray[$imageId]['CountR4'] = (!empty($imageDataArray['CountR4']) ? $imageDataArray['CountR4'] : 0);
            $allImagesArray[$imageId]['CountR5'] = (!empty($imageDataArray['CountR5']) ? $imageDataArray['CountR5'] : 0);
            $allImagesArray[$imageId]['CountR6'] = (!empty($imageDataArray['CountR6']) ? $imageDataArray['CountR6'] : 0);
            $allImagesArray[$imageId]['CountR7'] = (!empty($imageDataArray['CountR7']) ? $imageDataArray['CountR7'] : 0);
            $allImagesArray[$imageId]['CountR8'] = (!empty($imageDataArray['CountR8']) ? $imageDataArray['CountR8'] : 0);
            $allImagesArray[$imageId]['CountR9'] = (!empty($imageDataArray['CountR9']) ? $imageDataArray['CountR9'] : 0);
            $allImagesArray[$imageId]['CountR10'] = (!empty($imageDataArray['CountR10']) ? $imageDataArray['CountR10'] : 0);
        }else{
            $allImagesArray[$imageId]['CountR1'] = 0;
            $allImagesArray[$imageId]['CountR2'] = 0;
            $allImagesArray[$imageId]['CountR3'] = 0;
            $allImagesArray[$imageId]['CountR4'] = 0;
            $allImagesArray[$imageId]['CountR5'] = 0;
            $allImagesArray[$imageId]['CountR6'] = 0;
            $allImagesArray[$imageId]['CountR7'] = 0;
            $allImagesArray[$imageId]['CountR8'] = 0;
            $allImagesArray[$imageId]['CountR9'] = 0;
            $allImagesArray[$imageId]['CountR10'] = 0;
        }

        return $allImagesArray;


    }
}
