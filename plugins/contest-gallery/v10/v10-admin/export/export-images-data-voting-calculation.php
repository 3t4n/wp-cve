<?php
$allGalleryVotesArray = [];

foreach ($selectSQLall as $row){
    $allGalleryVotesArray[$row->id] = [];
    $allGalleryVotesArray[$row->id]['OneStarCount'] = 0;
    $allGalleryVotesArray[$row->id]['MultipleStarsOneStarCount'] = 0;
    $allGalleryVotesArray[$row->id]['MultipleStarsTwoStarsCount'] = 0;
    $allGalleryVotesArray[$row->id]['MultipleStarsThreeStarsCount'] = 0;
    $allGalleryVotesArray[$row->id]['MultipleStarsFourStarsCount'] = 0;
    $allGalleryVotesArray[$row->id]['MultipleStarsFiveStarsCount'] = 0;
    $allGalleryVotesArray[$row->id]['MultipleStarsSixStarsCount'] = 0;
    $allGalleryVotesArray[$row->id]['MultipleStarsSevenStarsCount'] = 0;
    $allGalleryVotesArray[$row->id]['MultipleStarsEightStarsCount'] = 0;
    $allGalleryVotesArray[$row->id]['MultipleStarsNineStarsCount'] = 0;
    $allGalleryVotesArray[$row->id]['MultipleStarsTenStarsCount'] = 0;
    $allGalleryVotesArray[$row->id]['MultipleStarsOneStarSum'] = 0;
    $allGalleryVotesArray[$row->id]['MultipleStarsTwoStarsSum'] = 0;
    $allGalleryVotesArray[$row->id]['MultipleStarsThreeStarsSum'] = 0;
    $allGalleryVotesArray[$row->id]['MultipleStarsFourStarsSum'] = 0;
    $allGalleryVotesArray[$row->id]['MultipleStarsFiveStarsSum'] = 0;
    $allGalleryVotesArray[$row->id]['MultipleStarsSixStarsSum'] = 0;
    $allGalleryVotesArray[$row->id]['MultipleStarsSevenStarsSum'] = 0;
    $allGalleryVotesArray[$row->id]['MultipleStarsEightStarsSum'] = 0;
    $allGalleryVotesArray[$row->id]['MultipleStarsNineStarsSum'] = 0;
    $allGalleryVotesArray[$row->id]['MultipleStarsTenStarsSum'] = 0;
    if($Manipulate==1){
        $allGalleryVotesArray[$row->id]['OneStarCount']  = intval($row->addCountS);
        $allGalleryVotesArray[$row->id]['MultipleStarsOneStarCount']  = intval($row->addCountR1);
        $allGalleryVotesArray[$row->id]['MultipleStarsTwoStarsCount']  = intval($row->addCountR2);
        $allGalleryVotesArray[$row->id]['MultipleStarsThreeStarsCount']  = intval($row->addCountR3);
        $allGalleryVotesArray[$row->id]['MultipleStarsFourStarsCount']  = intval($row->addCountR4);
        $allGalleryVotesArray[$row->id]['MultipleStarsFiveStarsCount']  = intval($row->addCountR5);
        $allGalleryVotesArray[$row->id]['MultipleStarsSixStarsCount']  = intval($row->addCountR6);
        $allGalleryVotesArray[$row->id]['MultipleStarsSevenStarsCount']  = intval($row->addCountR7);
        $allGalleryVotesArray[$row->id]['MultipleStarsEightStarsCount']  = intval($row->addCountR8);
        $allGalleryVotesArray[$row->id]['MultipleStarsNineStarsCount']  = intval($row->addCountR9);
        $allGalleryVotesArray[$row->id]['MultipleStarsTenStarsCount']  = intval($row->addCountR10);
        $allGalleryVotesArray[$row->id]['MultipleStarsOneStarSum']  = $allGalleryVotesArray[$row->id]['MultipleStarsOneStarCount'] * 1;
        $allGalleryVotesArray[$row->id]['MultipleStarsTwoStarsSum']  = $allGalleryVotesArray[$row->id]['MultipleStarsTwoStarsCount'] * 2;
        $allGalleryVotesArray[$row->id]['MultipleStarsThreeStarsSum']  = $allGalleryVotesArray[$row->id]['MultipleStarsThreeStarsCount'] * 3;
        $allGalleryVotesArray[$row->id]['MultipleStarsFourStarsSum']  = $allGalleryVotesArray[$row->id]['MultipleStarsFourStarsCount'] * 4;
        $allGalleryVotesArray[$row->id]['MultipleStarsFiveStarsSum']  = $allGalleryVotesArray[$row->id]['MultipleStarsFiveStarsCount'] * 5;
        $allGalleryVotesArray[$row->id]['MultipleStarsSixStarsSum']  = $allGalleryVotesArray[$row->id]['MultipleStarsSixStarsCount'] * 6;
        $allGalleryVotesArray[$row->id]['MultipleStarsSevenStarsSum']  = $allGalleryVotesArray[$row->id]['MultipleStarsSevenStarsCount'] *7;
        $allGalleryVotesArray[$row->id]['MultipleStarsEightStarsSum']  = $allGalleryVotesArray[$row->id]['MultipleStarsEightStarsCount'] * 8;
        $allGalleryVotesArray[$row->id]['MultipleStarsNineStarsSum']  = $allGalleryVotesArray[$row->id]['MultipleStarsNineStarsCount'] * 9;
        $allGalleryVotesArray[$row->id]['MultipleStarsTenStarsSum']  = $allGalleryVotesArray[$row->id]['MultipleStarsTenStarsCount'] * 10;
    }
}

foreach($selectAllGalleryVotes as $row){

    if($row->RatingS==1){
        $allGalleryVotesArray[$row->pid]['OneStarCount']  = $allGalleryVotesArray[$row->pid]['OneStarCount']  + $row->RatingS;
    }
    else if($row->Rating==1){
        $allGalleryVotesArray[$row->pid]['MultipleStarsOneStarSum']  = $allGalleryVotesArray[$row->pid]['MultipleStarsOneStarSum']  + 1;
        $allGalleryVotesArray[$row->pid]['MultipleStarsOneStarCount']++;
    }
    else if($row->Rating==2){
        $allGalleryVotesArray[$row->pid]['MultipleStarsTwoStarsSum']  = $allGalleryVotesArray[$row->pid]['MultipleStarsTwoStarsSum']  + 2;
        $allGalleryVotesArray[$row->pid]['MultipleStarsTwoStarsCount']++;
    }
    else if($row->Rating==3){
        $allGalleryVotesArray[$row->pid]['MultipleStarsThreeStarsSum']  = $allGalleryVotesArray[$row->pid]['MultipleStarsThreeStarsSum']  + 3;
        $allGalleryVotesArray[$row->pid]['MultipleStarsThreeStarsCount']++;
    }
    else if($row->Rating==4){
        $allGalleryVotesArray[$row->pid]['MultipleStarsFourStarsSum']  = $allGalleryVotesArray[$row->pid]['MultipleStarsFourStarsSum']  + 4;
        $allGalleryVotesArray[$row->pid]['MultipleStarsFourStarsCount']++;

    }
    else if($row->Rating==5){
        $allGalleryVotesArray[$row->pid]['MultipleStarsFiveStarsSum']  = $allGalleryVotesArray[$row->pid]['MultipleStarsFiveStarsSum']  + 5;
        $allGalleryVotesArray[$row->pid]['MultipleStarsFiveStarsCount']++;

    }
    else if($row->Rating==6){
        $allGalleryVotesArray[$row->pid]['MultipleStarsSixStarsSum']  = $allGalleryVotesArray[$row->pid]['MultipleStarsSixStarsSum']  + 6;
        $allGalleryVotesArray[$row->pid]['MultipleStarsSixStarsCount']++;

    }
    else if($row->Rating==7){
        $allGalleryVotesArray[$row->pid]['MultipleStarsSevenStarsSum']  = $allGalleryVotesArray[$row->pid]['MultipleStarsSevenStarsSum']  + 7;
        $allGalleryVotesArray[$row->pid]['MultipleStarsSevenStarsCount']++;

    }
    else if($row->Rating==8){
        $allGalleryVotesArray[$row->pid]['MultipleStarsEightStarsSum']  = $allGalleryVotesArray[$row->pid]['MultipleStarsEightStarsSum']  + 8;
        $allGalleryVotesArray[$row->pid]['MultipleStarsEightStarsCount']++;

    }
    else if($row->Rating==9){
        $allGalleryVotesArray[$row->pid]['MultipleStarsNineStarsSum']  = $allGalleryVotesArray[$row->pid]['MultipleStarsNineStarsSum']  + 9;
        $allGalleryVotesArray[$row->pid]['MultipleStarsNineStarsCount']++;

    }
    else if($row->Rating==10){
        $allGalleryVotesArray[$row->pid]['MultipleStarsTenStarsSum']  = $allGalleryVotesArray[$row->pid]['MultipleStarsTenStarsSum']  + 10;
        $allGalleryVotesArray[$row->pid]['MultipleStarsTenStarsCount']++;
    }
}

foreach($allGalleryVotesArray as $key => $row){

    $allGalleryVotesArray[$key]['MultipleStarsCountInCaseTwoStarsVotingIsActivated'] =
        $allGalleryVotesArray[$key]['MultipleStarsOneStarCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsTwoStarsCount'];

    $allGalleryVotesArray[$key]['MultipleStarsCountInCaseThreeStarsVotingIsActivated'] =
        $allGalleryVotesArray[$key]['MultipleStarsOneStarCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsTwoStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsThreeStarsCount'] ;

    $allGalleryVotesArray[$key]['MultipleStarsCountInCaseFourStarsVotingIsActivated'] =
        $allGalleryVotesArray[$key]['MultipleStarsOneStarCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsTwoStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsThreeStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsFourStarsCount'] ;

    $allGalleryVotesArray[$key]['MultipleStarsCountInCaseFiveStarsVotingIsActivated'] =
        $allGalleryVotesArray[$key]['MultipleStarsOneStarCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsTwoStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsThreeStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsFourStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsFiveStarsCount'] ;

    $allGalleryVotesArray[$key]['MultipleStarsCountInCaseSixStarsVotingIsActivated'] =
        $allGalleryVotesArray[$key]['MultipleStarsOneStarCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsTwoStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsThreeStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsFourStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsFiveStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsSixStarsCount'] ;

    $allGalleryVotesArray[$key]['MultipleStarsCountInCaseSevenStarsVotingIsActivated'] =
        $allGalleryVotesArray[$key]['MultipleStarsOneStarCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsTwoStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsThreeStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsFourStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsFiveStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsSixStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsSevenStarsCount'] ;

    $allGalleryVotesArray[$key]['MultipleStarsCountInCaseEightStarsVotingIsActivated'] =
        $allGalleryVotesArray[$key]['MultipleStarsOneStarCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsTwoStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsThreeStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsFourStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsFiveStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsSixStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsSevenStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsEightStarsCount'] ;

    $allGalleryVotesArray[$key]['MultipleStarsCountInCaseNineStarsVotingIsActivated'] =
        $allGalleryVotesArray[$key]['MultipleStarsOneStarCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsTwoStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsThreeStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsFourStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsFiveStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsSixStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsSevenStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsEightStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsNineStarsCount'] ;

    $allGalleryVotesArray[$key]['MultipleStarsCountInCaseTenStarsVotingIsActivated'] =
        $allGalleryVotesArray[$key]['MultipleStarsOneStarCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsTwoStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsThreeStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsFourStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsFiveStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsSixStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsSevenStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsEightStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsNineStarsCount'] +
        $allGalleryVotesArray[$key]['MultipleStarsTenStarsCount'] ;

    $allGalleryVotesArray[$key]['MultipleStarsSumInCaseTwoStarsVotingIsActivated'] =
        $allGalleryVotesArray[$key]['MultipleStarsOneStarSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsTwoStarsSum'];

    $allGalleryVotesArray[$key]['MultipleStarsSumInCaseThreeStarsVotingIsActivated'] =
        $allGalleryVotesArray[$key]['MultipleStarsOneStarSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsTwoStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsThreeStarsSum'] ;

    $allGalleryVotesArray[$key]['MultipleStarsSumInCaseFourStarsVotingIsActivated'] =
        $allGalleryVotesArray[$key]['MultipleStarsOneStarSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsTwoStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsThreeStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsFourStarsSum'] ;

    $allGalleryVotesArray[$key]['MultipleStarsSumInCaseFiveStarsVotingIsActivated'] =
        $allGalleryVotesArray[$key]['MultipleStarsOneStarSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsTwoStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsThreeStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsFourStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsFiveStarsSum'] ;

    $allGalleryVotesArray[$key]['MultipleStarsSumInCaseSixStarsVotingIsActivated'] =
        $allGalleryVotesArray[$key]['MultipleStarsOneStarSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsTwoStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsThreeStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsFourStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsFiveStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsSixStarsSum'] ;

    $allGalleryVotesArray[$key]['MultipleStarsSumInCaseSevenStarsVotingIsActivated'] =
        $allGalleryVotesArray[$key]['MultipleStarsOneStarSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsTwoStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsThreeStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsFourStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsFiveStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsSixStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsSevenStarsSum'] ;

    $allGalleryVotesArray[$key]['MultipleStarsSumInCaseEightStarsVotingIsActivated'] =
        $allGalleryVotesArray[$key]['MultipleStarsOneStarSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsTwoStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsThreeStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsFourStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsFiveStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsSixStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsSevenStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsEightStarsSum'] ;

    $allGalleryVotesArray[$key]['MultipleStarsSumInCaseNineStarsVotingIsActivated'] =
        $allGalleryVotesArray[$key]['MultipleStarsOneStarSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsTwoStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsThreeStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsFourStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsFiveStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsSixStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsSevenStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsEightStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsNineStarsSum'] ;

    $allGalleryVotesArray[$key]['MultipleStarsSumInCaseTenStarsVotingIsActivated'] =
        $allGalleryVotesArray[$key]['MultipleStarsOneStarSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsTwoStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsThreeStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsFourStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsFiveStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsSixStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsSevenStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsEightStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsNineStarsSum'] +
        $allGalleryVotesArray[$key]['MultipleStarsTenStarsSum'] ;

    $allGalleryVotesArray[$key]['MultipleStarsAverageInCaseTwoStarsVotingIsActivated'] = 0;
    $allGalleryVotesArray[$key]['MultipleStarsAverageInCaseThreeStarsVotingIsActivated'] = 0;
    $allGalleryVotesArray[$key]['MultipleStarsAverageInCaseFourStarsVotingIsActivated'] = 0;
    $allGalleryVotesArray[$key]['MultipleStarsAverageInCaseFiveStarsVotingIsActivated'] = 0;
    $allGalleryVotesArray[$key]['MultipleStarsAverageInCaseSixStarsVotingIsActivated'] = 0;
    $allGalleryVotesArray[$key]['MultipleStarsAverageInCaseSevenStarsVotingIsActivated'] = 0;
    $allGalleryVotesArray[$key]['MultipleStarsAverageInCaseEightStarsVotingIsActivated'] = 0;
    $allGalleryVotesArray[$key]['MultipleStarsAverageInCaseNineStarsVotingIsActivated'] = 0;
    $allGalleryVotesArray[$key]['MultipleStarsAverageInCaseTenStarsVotingIsActivated'] = 0;

    if($allGalleryVotesArray[$key]['MultipleStarsSumInCaseTwoStarsVotingIsActivated'] != 0) {
        $allGalleryVotesArray[$key]['MultipleStarsAverageInCaseTwoStarsVotingIsActivated'] =
            round($allGalleryVotesArray[$key]['MultipleStarsSumInCaseTwoStarsVotingIsActivated']
                /
                ($allGalleryVotesArray[$key]['MultipleStarsOneStarCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsTwoStarsCount']),2);
    }

    if($allGalleryVotesArray[$key]['MultipleStarsSumInCaseThreeStarsVotingIsActivated'] != 0) {
        $allGalleryVotesArray[$key]['MultipleStarsAverageInCaseThreeStarsVotingIsActivated'] =
            round($allGalleryVotesArray[$key]['MultipleStarsSumInCaseThreeStarsVotingIsActivated']
                /
                ($allGalleryVotesArray[$key]['MultipleStarsOneStarCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsTwoStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsThreeStarsCount']),2);
    }

    if($allGalleryVotesArray[$key]['MultipleStarsSumInCaseFourStarsVotingIsActivated'] != 0) {
        $allGalleryVotesArray[$key]['MultipleStarsAverageInCaseFourStarsVotingIsActivated'] =
            round($allGalleryVotesArray[$key]['MultipleStarsSumInCaseFourStarsVotingIsActivated']
                /
                ($allGalleryVotesArray[$key]['MultipleStarsOneStarCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsTwoStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsThreeStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsFourStarsCount']),2);
    }

    if($allGalleryVotesArray[$key]['MultipleStarsSumInCaseFiveStarsVotingIsActivated'] != 0) {
        $allGalleryVotesArray[$key]['MultipleStarsAverageInCaseFiveStarsVotingIsActivated'] =
            round($allGalleryVotesArray[$key]['MultipleStarsSumInCaseFiveStarsVotingIsActivated']
                /
                ($allGalleryVotesArray[$key]['MultipleStarsOneStarCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsTwoStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsThreeStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsFourStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsFiveStarsCount']),2);
    }

    if($allGalleryVotesArray[$key]['MultipleStarsSumInCaseSixStarsVotingIsActivated'] != 0) {
        $allGalleryVotesArray[$key]['MultipleStarsAverageInCaseSixStarsVotingIsActivated'] =
            round($allGalleryVotesArray[$key]['MultipleStarsSumInCaseSixStarsVotingIsActivated']
                /
                ($allGalleryVotesArray[$key]['MultipleStarsOneStarCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsTwoStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsThreeStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsFourStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsFiveStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsSixStarsCount']),2);
    }

    if($allGalleryVotesArray[$key]['MultipleStarsSumInCaseSevenStarsVotingIsActivated'] != 0) {
        $allGalleryVotesArray[$key]['MultipleStarsAverageInCaseSevenStarsVotingIsActivated'] =
            round($allGalleryVotesArray[$key]['MultipleStarsSumInCaseSevenStarsVotingIsActivated']
                /
                ($allGalleryVotesArray[$key]['MultipleStarsOneStarCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsTwoStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsThreeStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsFourStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsFiveStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsSixStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsSevenStarsCount']),2);
    }

    if($allGalleryVotesArray[$key]['MultipleStarsSumInCaseEightStarsVotingIsActivated'] != 0) {
        $allGalleryVotesArray[$key]['MultipleStarsAverageInCaseEightStarsVotingIsActivated'] =
            round($allGalleryVotesArray[$key]['MultipleStarsSumInCaseEightStarsVotingIsActivated']
                /
                ($allGalleryVotesArray[$key]['MultipleStarsOneStarCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsTwoStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsThreeStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsFourStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsFiveStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsSixStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsSevenStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsEightStarsCount']),2);
    }

    if($allGalleryVotesArray[$key]['MultipleStarsSumInCaseNineStarsVotingIsActivated'] != 0) {
        $allGalleryVotesArray[$key]['MultipleStarsAverageInCaseNineStarsVotingIsActivated'] =
            round($allGalleryVotesArray[$key]['MultipleStarsSumInCaseNineStarsVotingIsActivated']
                /
                ($allGalleryVotesArray[$key]['MultipleStarsOneStarCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsTwoStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsThreeStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsFourStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsFiveStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsSixStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsSevenStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsEightStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsNineStarsCount']),2);
    }

    if($allGalleryVotesArray[$key]['MultipleStarsSumInCaseTenStarsVotingIsActivated'] != 0) {
        $allGalleryVotesArray[$key]['MultipleStarsAverageInCaseTenStarsVotingIsActivated'] =
            round($allGalleryVotesArray[$key]['MultipleStarsSumInCaseTenStarsVotingIsActivated']
                /
                ($allGalleryVotesArray[$key]['MultipleStarsOneStarCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsTwoStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsThreeStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsFourStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsFiveStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsSixStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsSevenStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsEightStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsNineStarsCount'] +
                    $allGalleryVotesArray[$key]['MultipleStarsTenStarsCount']),2);
    }

}

?>