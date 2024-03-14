<?php

/**
 * Translated & modified version of the tree_stem Python stemmer found here:
 * https://github.com/amakukha/stemmers_ukrainian/
 * Original Stemmer:
 * Author: Andrii Makukha, 2020
 * BSD 2-Clause license
 **/
class Wpil_Stemmer {

    static $stem_cache = array();
    static $alphabet_index = array(
        " " => 0, "а" => 1, "б" => 2, "в" => 3, "г" => 4, 
        "ґ" => 5, "д" => 6, "е" => 7, "є" => 8, "ж" => 9, 
        "з" => 10, "и" => 11, "і" => 12, "ї" => 13, "й" => 14, 
        "к" => 15, "л" => 16, "м" => 17, "н" => 18, "о" => 19, 
        "п" => 20, "р" => 21, "с" => 22, "т" => 23, "у" => 24, 
        "ф" => 25, "х" => 26, "ц" => 27, "ч" => 28, "ш" => 29, 
        "щ" => 30, "ь" => 31, "ю" => 32, "я" => 33, "'" => 34,
        "-" => 35);

    public static function decision_tree($f1 = 0, $f2 = 0, $f3 = 0, $f4 = 0, $f5 = 0, $f6 = 0, $f7 = 0, $f8 = 0, $f9 = 0, $f10 = 0) {
        if ($f2 <= 21) {
            if ($f1 <= 12) {
                if ($f2 <= 17) {
                    if ($f2 <= 16) {
                        if ($f2 <= 3) {
                            if ($f1 <= 11) {
                                if ($f3 <= 3) {
                                    if ($f4 <= 23) {
                                        if ($f4 <= 1) {
                                            if ($f3 <= 2) {
                                                return 0;
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else if ($f1 <= 3) {
                                        if ($f3 <= 2) {
                                            return 1;
                                        }
                                        else if ($f5 <= 27) {
                                            if ($f5 <= 9) {
                                                return 3;
                                            }
                                            else if ($f4 <= 28) {
                                                if ($f5 <= 12) {
                                                    if ($f6 <= 10) {
                                                        return 3;
                                                    }
                                                    else if ($f6 <= 20) {
                                                        return 4;
                                                    }
                                                    else {
                                                        return 3;
                                                    }
                                                }
                                                else {
                                                    return 3;
                                                }
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else if ($f2 <= 2) {
                                    if ($f1 <= 3) {
                                        if ($f1 <= 2) {
                                            if ($f2 <= 1) {
                                                return 0;
                                            }
                                            else if ($f3 <= 12) {
                                                return 0;
                                            }
                                            else {
                                                return 1;
                                            }
                                        }
                                        else if ($f3 <= 27) {
                                            if ($f3 <= 21) {
                                                if ($f3 <= 15) {
                                                    return 1;
                                                }
                                                else if ($f3 <= 16) {
                                                    if ($f4 <= 19) {
                                                        return 1;
                                                    }
                                                    else {
                                                        return 0;
                                                    }
                                                }
                                                else {
                                                    return 1;
                                                }
                                            }
                                            else if ($f4 <= 20) {
                                                if ($f4 <= 12) {
                                                    return 1;
                                                }
                                                else {
                                                    return 3;
                                                }
                                            }
                                            else {
                                                return 1;
                                            }
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else if ($f1 <= 6) {
                                        if ($f1 <= 5) {
                                            return 1;
                                        }
                                        else {
                                            return 0;
                                        }
                                    }
                                    else if ($f1 <= 8) {
                                        if ($f1 <= 7) {
                                            if ($f3 <= 9) {
                                                return 3;
                                            }
                                            else {
                                                return 1;
                                            }
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else if ($f1 <= 10) {
                                        return 0;
                                    }
                                    else if ($f3 <= 8) {
                                        return 3;
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else if ($f10 <= 0) {
                                    if ($f3 <= 8) {
                                        return 1;
                                    }
                                    else if ($f3 <= 16) {
                                        if ($f1 <= 6) {
                                            if ($f3 <= 11) {
                                                return 1;
                                            }
                                            else if ($f3 <= 12) {
                                                return 0;
                                            }
                                            else {
                                                return 1;
                                            }
                                        }
                                        else if ($f3 <= 11) {
                                            if ($f4 <= 9) {
                                                return 2;
                                            }
                                            else {
                                                return 1;
                                            }
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else if ($f3 <= 20) {
                                        if ($f1 <= 8) {
                                            return 1;
                                        }
                                        else if ($f1 <= 10) {
                                            return 0;
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else if ($f8 <= 0) {
                                    return 9;
                                }
                                else if ($f3 <= 20) {
                                    if ($f7 <= 0) {
                                        return 9;
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else {
                                    return 1;
                                }
                            }
                            else if ($f3 <= 20) {
                                if ($f3 <= 18) {
                                    if ($f3 <= 8) {
                                        if ($f4 <= 27) {
                                            if ($f4 <= 26) {
                                                if ($f3 <= 6) {
                                                    return 1;
                                                }
                                                else if ($f4 <= 16) {
                                                    return 3;
                                                }
                                                else if ($f4 <= 19) {
                                                    if ($f5 <= 1) {
                                                        return 3;
                                                    }
                                                    else if ($f5 <= 6) {
                                                        return 4;
                                                    }
                                                    else if ($f5 <= 8) {
                                                        return 3;
                                                    }
                                                    else {
                                                        return 4;
                                                    }
                                                }
                                                else if ($f4 <= 22) {
                                                    return 3;
                                                }
                                                else {
                                                    return 1;
                                                }
                                            }
                                            else if ($f5 <= 30) {
                                                if ($f5 <= 14) {
                                                    if ($f5 <= 10) {
                                                        return 4;
                                                    }
                                                    else if ($f5 <= 11) {
                                                        return 1;
                                                    }
                                                    else {
                                                        return 5;
                                                    }
                                                }
                                                else {
                                                    return 4;
                                                }
                                            }
                                            else {
                                                return 5;
                                            }
                                        }
                                        else if ($f3 <= 6) {
                                            return 1;
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else if ($f4 <= 15) {
                                    if ($f5 <= 13) {
                                        if ($f5 <= 10) {
                                            if ($f4 <= 11) {
                                                return 3;
                                            }
                                            else if ($f5 <= 1) {
                                                return 3;
                                            }
                                            else if ($f5 <= 6) {
                                                return 4;
                                            }
                                            else if ($f5 <= 8) {
                                                return 3;
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else if ($f4 <= 12) {
                                        if ($f4 <= 5) {
                                            return 3;
                                        }
                                        else if ($f5 <= 20) {
                                            if ($f4 <= 9) {
                                                if ($f5 <= 18) {
                                                    return 3;
                                                }
                                                else {
                                                    return 5;
                                                }
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else if ($f5 <= 30) {
                                        if ($f5 <= 27) {
                                            if ($f6 <= 10) {
                                                if ($f6 <= 1) {
                                                    return 4;
                                                }
                                                else {
                                                    return 3;
                                                }
                                            }
                                            else if ($f5 <= 23) {
                                                if ($f5 <= 22) {
                                                    if ($f5 <= 18) {
                                                        return 4;
                                                    }
                                                    else if ($f5 <= 19) {
                                                        return 3;
                                                    }
                                                    else {
                                                        return 4;
                                                    }
                                                }
                                                else {
                                                    return 4;
                                                }
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f4 <= 27) {
                                    if ($f5 <= 19) {
                                        if ($f5 <= 10) {
                                            return 3;
                                        }
                                        else if ($f2 <= 2) {
                                            return 1;
                                        }
                                        else if ($f6 <= 30) {
                                            if ($f7 <= 32) {
                                                if ($f5 <= 18) {
                                                    if ($f7 <= 1) {
                                                        if ($f8 <= 27) {
                                                            return 3;
                                                        }
                                                        else {
                                                            return 5;
                                                        }
                                                    }
                                                    else {
                                                        return 3;
                                                    }
                                                }
                                                else {
                                                    return 3;
                                                }
                                            }
                                            else if ($f6 <= 19) {
                                                if ($f6 <= 17) {
                                                    return 3;
                                                }
                                                else {
                                                    return 5;
                                                }
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else if ($f4 <= 16) {
                                        if ($f5 <= 23) {
                                            return 1;
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else {
                                    return 1;
                                }
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f2 <= 15) {
                            if ($f3 <= 30) {
                                if ($f2 <= 6) {
                                    if ($f2 <= 5) {
                                        if ($f8 <= 0) {
                                            if ($f1 <= 10) {
                                                return 2;
                                            }
                                            else if ($f1 <= 11) {
                                                return 2;
                                            }
                                            else {
                                                return 1;
                                            }
                                        }
                                        else if ($f3 <= 17) {
                                            return 1;
                                        }
                                        else if ($f3 <= 20) {
                                            return 1;
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else if ($f3 <= 18) {
                                        return 1;
                                    }
                                    else if ($f3 <= 19) {
                                        if ($f4 <= 24) {
                                            if ($f4 <= 3) {
                                                if ($f5 <= 1) {
                                                    return 1;
                                                }
                                                else {
                                                    return 3;
                                                }
                                            }
                                            else {
                                                return 1;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else if ($f2 <= 8) {
                                    if ($f1 <= 7) {
                                        return 0;
                                    }
                                    else if ($f1 <= 8) {
                                        return 2;
                                    }
                                    else {
                                        return 0;
                                    }
                                }
                                else if ($f1 <= 3) {
                                    if ($f1 <= 2) {
                                        if ($f4 <= 13) {
                                            if ($f2 <= 11) {
                                                return 1;
                                            }
                                            else if ($f4 <= 10) {
                                                if ($f4 <= 1) {
                                                    return 2;
                                                }
                                                else if ($f3 <= 19) {
                                                    return 1;
                                                }
                                                else {
                                                    return 2;
                                                }
                                            }
                                            else if ($f3 <= 3) {
                                                return 2;
                                            }
                                            else if ($f3 <= 15) {
                                                if ($f6 <= 21) {
                                                    return 2;
                                                }
                                                else {
                                                    return 1;
                                                }
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else if ($f4 <= 18) {
                                            if ($f3 <= 11) {
                                                if ($f3 <= 9) {
                                                    if ($f2 <= 10) {
                                                        return 1;
                                                    }
                                                    else {
                                                        return 2;
                                                    }
                                                }
                                                else {
                                                    return 1;
                                                }
                                            }
                                            else if ($f2 <= 11) {
                                                return 1;
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else if ($f2 <= 11) {
                                            return 1;
                                        }
                                        else if ($f3 <= 13) {
                                            if ($f3 <= 10) {
                                                if ($f3 <= 1) {
                                                    return 1;
                                                }
                                                else {
                                                    return 2;
                                                }
                                            }
                                            else if ($f4 <= 26) {
                                                if ($f10 <= 0) {
                                                    if ($f3 <= 11) {
                                                        return 1;
                                                    }
                                                    else {
                                                        return 0;
                                                    }
                                                }
                                                else {
                                                    return 2;
                                                }
                                            }
                                            else {
                                                return 1;
                                            }
                                        }
                                        else if ($f3 <= 27) {
                                            if ($f3 <= 23) {
                                                if ($f4 <= 32) {
                                                    if ($f3 <= 22) {
                                                        if ($f3 <= 18) {
                                                            return 2;
                                                        }
                                                        else if ($f3 <= 19) {
                                                            return 1;
                                                        }
                                                        else {
                                                            return 2;
                                                        }
                                                    }
                                                    else {
                                                        return 2;
                                                    }
                                                }
                                                else {
                                                    return 2;
                                                }
                                            }
                                            else {
                                                return 1;
                                            }
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else if ($f4 <= 13) {
                                        if ($f4 <= 10) {
                                            if ($f4 <= 1) {
                                                if ($f2 <= 11) {
                                                    if ($f3 <= 21) {
                                                        return 2;
                                                    }
                                                    else if ($f3 <= 24) {
                                                        return 3;
                                                    }
                                                    else {
                                                        return 2;
                                                    }
                                                }
                                                else {
                                                    return 2;
                                                }
                                            }
                                            else if ($f4 <= 6) {
                                                if ($f3 <= 26) {
                                                    if ($f3 <= 13) {
                                                        return 2;
                                                    }
                                                    else if ($f3 <= 15) {
                                                        return 3;
                                                    }
                                                    else if ($f3 <= 20) {
                                                        if ($f2 <= 11) {
                                                            return 2;
                                                        }
                                                        else {
                                                            return 3;
                                                        }
                                                    }
                                                    else {
                                                        return 2;
                                                    }
                                                }
                                                else if ($f5 <= 11) {
                                                    return 3;
                                                }
                                                else if ($f5 <= 15) {
                                                    return 2;
                                                }
                                                else {
                                                    return 3;
                                                }
                                            }
                                            else if ($f4 <= 8) {
                                                return 2;
                                            }
                                            else if ($f3 <= 13) {
                                                return 2;
                                            }
                                            else if ($f3 <= 15) {
                                                return 3;
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else if ($f2 <= 11) {
                                            if ($f3 <= 21) {
                                                return 2;
                                            }
                                            else if ($f3 <= 24) {
                                                return 3;
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else if ($f3 <= 9) {
                                        if ($f2 <= 11) {
                                            return 2;
                                        }
                                        else if ($f4 <= 19) {
                                            if ($f4 <= 18) {
                                                return 2;
                                            }
                                            else if ($f3 <= 5) {
                                                if ($f10 <= 25) {
                                                    return 2;
                                                }
                                                else {
                                                    return 11;
                                                }
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else if ($f3 <= 15) {
                                        if ($f2 <= 12) {
                                            if ($f5 <= 10) {
                                                return 2;
                                            }
                                            else if ($f4 <= 30) {
                                                if ($f4 <= 27) {
                                                    if ($f4 <= 23) {
                                                        if ($f4 <= 22) {
                                                            if ($f4 <= 18) {
                                                                return 3;
                                                            }
                                                            else if ($f4 <= 19) {
                                                                if ($f2 <= 11) {
                                                                    return 3;
                                                                }
                                                                else {
                                                                    return 2;
                                                                }
                                                            }
                                                            else {
                                                                return 2;
                                                            }
                                                        }
                                                        else {
                                                            return 3;
                                                        }
                                                    }
                                                    else {
                                                        return 2;
                                                    }
                                                }
                                                else if ($f4 <= 28) {
                                                    return 3;
                                                }
                                                else {
                                                    return 2;
                                                }
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else if ($f3 <= 21) {
                                        return 2;
                                    }
                                    else if ($f3 <= 27) {
                                        if ($f2 <= 11) {
                                            if ($f4 <= 21) {
                                                if ($f4 <= 15) {
                                                    return 0;
                                                }
                                                else {
                                                    return 3;
                                                }
                                            }
                                            else if ($f4 <= 23) {
                                                return 4;
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else if ($f3 <= 26) {
                                            if ($f5 <= 14) {
                                                return 2;
                                            }
                                            else if ($f5 <= 15) {
                                                return 3;
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else if ($f4 <= 14) {
                                            return 4;
                                        }
                                        else if ($f4 <= 30) {
                                            if ($f5 <= 18) {
                                                if ($f5 <= 9) {
                                                    return 3;
                                                }
                                                else {
                                                    return 2;
                                                }
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else if ($f2 <= 14) {
                                    if ($f1 <= 6) {
                                        if ($f1 <= 5) {
                                            if ($f2 <= 11) {
                                                return 1;
                                            }
                                            else if ($f3 <= 11) {
                                                return 0;
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else if ($f2 <= 11) {
                                            return 0;
                                        }
                                        else if ($f2 <= 12) {
                                            return 2;
                                        }
                                        else {
                                            return 0;
                                        }
                                    }
                                    else if ($f1 <= 8) {
                                        return 1;
                                    }
                                    else if ($f1 <= 10) {
                                        if ($f3 <= 3) {
                                            return 2;
                                        }
                                        else {
                                            return 0;
                                        }
                                    }
                                    else if ($f3 <= 20) {
                                        if ($f10 <= 0) {
                                            return 1;
                                        }
                                        else if ($f7 <= 0) {
                                            return 9;
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else if ($f1 <= 11) {
                                        return 1;
                                    }
                                    else if ($f2 <= 9) {
                                        return 1;
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else if ($f4 <= 13) {
                                    if ($f1 <= 11) {
                                        if ($f1 <= 9) {
                                            return 1;
                                        }
                                        else if ($f4 <= 10) {
                                            if ($f4 <= 1) {
                                                return 2;
                                            }
                                            else if ($f3 <= 19) {
                                                return 1;
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else if ($f4 <= 18) {
                                    if ($f3 <= 11) {
                                        return 1;
                                    }
                                    else if ($f4 <= 15) {
                                        return 1;
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else if ($f3 <= 26) {
                                    if ($f1 <= 11) {
                                        if ($f1 <= 9) {
                                            return 1;
                                        }
                                        else if ($f3 <= 15) {
                                            if ($f3 <= 10) {
                                                if ($f3 <= 1) {
                                                    if ($f5 <= 34) {
                                                        return 1;
                                                    }
                                                    else {
                                                        return 0;
                                                    }
                                                }
                                                else {
                                                    return 2;
                                                }
                                            }
                                            else if ($f4 <= 26) {
                                                if ($f10 <= 0) {
                                                    return 1;
                                                }
                                                else {
                                                    return 2;
                                                }
                                            }
                                            else {
                                                return 1;
                                            }
                                        }
                                        else if ($f3 <= 23) {
                                            return 2;
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f4 <= 21) {
                                if ($f1 <= 11) {
                                    if ($f1 <= 10) {
                                        if ($f1 <= 6) {
                                            if ($f2 <= 5) {
                                                return 2;
                                            }
                                            else if ($f2 <= 11) {
                                                return 1;
                                            }
                                            else if ($f5 <= 18) {
                                                if ($f3 <= 32) {
                                                    return 1;
                                                }
                                                else {
                                                    return 2;
                                                }
                                            }
                                            else if ($f4 <= 10) {
                                                return 1;
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else if ($f3 <= 31) {
                                        return 2;
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else if ($f2 <= 12) {
                                    if ($f2 <= 9) {
                                        return 1;
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else {
                                    return 1;
                                }
                            }
                            else if ($f3 <= 31) {
                                if ($f1 <= 9) {
                                    return 1;
                                }
                                else if ($f1 <= 11) {
                                    if ($f10 <= 0) {
                                        return 1;
                                    }
                                    else {
                                        return 0;
                                    }
                                }
                                else {
                                    return 1;
                                }
                            }
                            else if ($f4 <= 34) {
                                return 1;
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f3 <= 1) {
                            if ($f4 <= 3) {
                                if ($f5 <= 22) {
                                    if ($f5 <= 10) {
                                        if ($f4 <= 2) {
                                            return 2;
                                        }
                                        else if ($f5 <= 1) {
                                            return 4;
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else if ($f5 <= 13) {
                                        if ($f1 <= 11) {
                                            return 2;
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else if ($f6 <= 21) {
                                        return 1;
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else if ($f4 <= 2) {
                                    return 2;
                                }
                                else if ($f6 <= 27) {
                                    if ($f6 <= 9) {
                                        if ($f10 <= 0) {
                                            return 4;
                                        }
                                        else if ($f6 <= 2) {
                                            return 4;
                                        }
                                        else if ($f6 <= 3) {
                                            if ($f7 <= 11) {
                                                return 5;
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else if ($f5 <= 25) {
                                        if ($f6 <= 12) {
                                            if ($f7 <= 10) {
                                                return 4;
                                            }
                                            else if ($f7 <= 20) {
                                                return 5;
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else if ($f6 <= 16) {
                                            if ($f8 <= 24) {
                                                return 4;
                                            }
                                            else if ($f8 <= 25) {
                                                return 5;
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else if ($f6 <= 23) {
                                        if ($f6 <= 17) {
                                            return 4;
                                        }
                                        else if ($f5 <= 30) {
                                            return 1;
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else {
                                    return 4;
                                }
                            }
                            else if ($f1 <= 11) {
                                if ($f4 <= 27) {
                                    if ($f4 <= 21) {
                                        if ($f4 <= 15) {
                                            if ($f4 <= 9) {
                                                if ($f4 <= 6) {
                                                    if ($f7 <= 22) {
                                                        return 2;
                                                    }
                                                    else if ($f5 <= 23) {
                                                        return 2;
                                                    }
                                                    else {
                                                        return 4;
                                                    }
                                                }
                                                else {
                                                    return 2;
                                                }
                                            }
                                            else if ($f4 <= 13) {
                                                if ($f4 <= 11) {
                                                    if ($f5 <= 2) {
                                                        return 4;
                                                    }
                                                    else if ($f6 <= 32) {
                                                        return 2;
                                                    }
                                                    else {
                                                        return 4;
                                                    }
                                                }
                                                else {
                                                    return 1;
                                                }
                                            }
                                            else if ($f5 <= 11) {
                                                return 2;
                                            }
                                            else if ($f5 <= 22) {
                                                return 2;
                                            }
                                            else if ($f5 <= 23) {
                                                return 4;
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else if ($f5 <= 9) {
                                            if ($f4 <= 20) {
                                                if ($f4 <= 19) {
                                                    if ($f4 <= 17) {
                                                        return 2;
                                                    }
                                                    else if ($f5 <= 5) {
                                                        return 1;
                                                    }
                                                    else {
                                                        return 2;
                                                    }
                                                }
                                                else {
                                                    return 2;
                                                }
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else if ($f5 <= 11) {
                                            return 2;
                                        }
                                        else if ($f4 <= 16) {
                                            return 2;
                                        }
                                        else if ($f5 <= 23) {
                                            if ($f4 <= 19) {
                                                if ($f4 <= 17) {
                                                    return 2;
                                                }
                                                else {
                                                    return 1;
                                                }
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else if ($f5 <= 20) {
                                        if ($f5 <= 12) {
                                            if ($f5 <= 1) {
                                                return 2;
                                            }
                                            else if ($f5 <= 10) {
                                                if ($f6 <= 19) {
                                                    return 2;
                                                }
                                                else {
                                                    return 4;
                                                }
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else if ($f4 <= 25) {
                                            if ($f8 <= 0) {
                                                return 2;
                                            }
                                            else if ($f4 <= 22) {
                                                return 2;
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else if ($f5 <= 15) {
                                            return 4;
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else if ($f4 <= 28) {
                                    if ($f6 <= 23) {
                                        if ($f6 <= 11) {
                                            if ($f5 <= 14) {
                                                return 2;
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f1 <= 11) {
                            if ($f3 <= 11) {
                                if ($f3 <= 10) {
                                    if ($f3 <= 9) {
                                        if ($f3 <= 6) {
                                            if ($f5 <= 21) {
                                                if ($f5 <= 15) {
                                                    if ($f5 <= 2) {
                                                        return 3;
                                                    }
                                                    else {
                                                        return 2;
                                                    }
                                                }
                                                else if ($f6 <= 21) {
                                                    return 4;
                                                }
                                                else {
                                                    return 3;
                                                }
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else if ($f5 <= 5) {
                                        return 4;
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else if ($f4 <= 21) {
                                    if ($f8 <= 0) {
                                        return 3;
                                    }
                                    else if ($f4 <= 13) {
                                        if ($f4 <= 9) {
                                            if ($f7 <= 33) {
                                                return 3;
                                            }
                                            else {
                                                return 5;
                                            }
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else if ($f4 <= 20) {
                                        return 3;
                                    }
                                    else if ($f5 <= 16) {
                                        if ($f5 <= 13) {
                                            return 3;
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f4 <= 24) {
                                    if ($f5 <= 21) {
                                        return 4;
                                    }
                                    else if ($f5 <= 22) {
                                        return 5;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f4 <= 27) {
                                    return 1;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f3 <= 32) {
                                if ($f3 <= 23) {
                                    if ($f3 <= 13) {
                                        if ($f4 <= 22) {
                                            if ($f3 <= 12) {
                                                if ($f1 <= 9) {
                                                    if ($f1 <= 4) {
                                                        return 2;
                                                    }
                                                    else {
                                                        return 1;
                                                    }
                                                }
                                                else {
                                                    return 2;
                                                }
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else if ($f4 <= 23) {
                                            if ($f5 <= 3) {
                                                return 2;
                                            }
                                            else if ($f5 <= 21) {
                                                return 4;
                                            }
                                            else if ($f5 <= 23) {
                                                return 5;
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else if ($f4 <= 27) {
                                            if ($f5 <= 20) {
                                                return 1;
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else if ($f3 <= 19) {
                                        if ($f3 <= 17) {
                                            if ($f3 <= 15) {
                                                if ($f3 <= 14) {
                                                    return 1;
                                                }
                                                else if ($f4 <= 20) {
                                                    if ($f5 <= 19) {
                                                        if ($f6 <= 18) {
                                                            return 2;
                                                        }
                                                        else if ($f5 <= 18) {
                                                            return 4;
                                                        }
                                                        else {
                                                            return 3;
                                                        }
                                                    }
                                                    else if ($f4 <= 8) {
                                                        return 4;
                                                    }
                                                    else {
                                                        return 1;
                                                    }
                                                }
                                                else {
                                                    return 1;
                                                }
                                            }
                                            else {
                                                return 1;
                                            }
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else if ($f5 <= 16) {
                                        if ($f10 <= 0) {
                                            return 2;
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else if ($f4 <= 8) {
                                        if ($f4 <= 5) {
                                            return 2;
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else if ($f4 <= 18) {
                                    if ($f4 <= 17) {
                                        if ($f3 <= 28) {
                                            return 1;
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f3 <= 24) {
                                    return 1;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f4 <= 14) {
                                return 3;
                            }
                            else if ($f4 <= 22) {
                                if ($f4 <= 17) {
                                    return 2;
                                }
                                else if ($f4 <= 20) {
                                    if ($f4 <= 18) {
                                        return 2;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f4 <= 33) {
                                return 2;
                            }
                            else if ($f5 <= 10) {
                                return 1;
                            }
                            else {
                                return 4;
                            }
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f1 <= 10) {
                        if ($f4 <= 22) {
                            return 1;
                        }
                        else if ($f6 <= 3) {
                            if ($f7 <= 23) {
                                if ($f7 <= 10) {
                                    if ($f8 <= 3) {
                                        return 1;
                                    }
                                    else if ($f5 <= 6) {
                                        return 6;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f5 <= 10) {
                                    return 4;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else if ($f5 <= 6) {
                                if ($f8 <= 9) {
                                    return 6;
                                }
                                else if ($f8 <= 11) {
                                    return 7;
                                }
                                else {
                                    return 6;
                                }
                            }
                            else {
                                return 5;
                            }
                        }
                        else if ($f5 <= 1) {
                            if ($f3 <= 11) {
                                if ($f6 <= 27) {
                                    if ($f6 <= 22) {
                                        if ($f4 <= 24) {
                                            return 4;
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else if ($f7 <= 20) {
                                        if ($f7 <= 12) {
                                            return 4;
                                        }
                                        else {
                                            return 6;
                                        }
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else {
                                    return 4;
                                }
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f5 <= 31) {
                            if ($f5 <= 11) {
                                if ($f1 <= 4) {
                                    return 1;
                                }
                                else if ($f6 <= 21) {
                                    if ($f6 <= 10) {
                                        if ($f6 <= 9) {
                                            return 5;
                                        }
                                        else {
                                            return 6;
                                        }
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else if ($f6 <= 23) {
                                    return 6;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else if ($f5 <= 13) {
                                if ($f6 <= 22) {
                                    return 4;
                                }
                                else {
                                    return 6;
                                }
                            }
                            else if ($f1 <= 4) {
                                if ($f6 <= 32) {
                                    return 1;
                                }
                                else {
                                    return 4;
                                }
                            }
                            else {
                                return 6;
                            }
                        }
                        else if ($f1 <= 4) {
                            return 3;
                        }
                        else if ($f6 <= 15) {
                            return 5;
                        }
                        else {
                            return 4;
                        }
                    }
                    else if ($f1 <= 11) {
                        if ($f3 <= 2) {
                            if ($f4 <= 15) {
                                if ($f4 <= 14) {
                                    if ($f4 <= 5) {
                                        if ($f4 <= 3) {
                                            return 3;
                                        }
                                        else if ($f5 <= 22) {
                                            if ($f10 <= 0) {
                                                return 4;
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else if ($f5 <= 18) {
                                        return 3;
                                    }
                                    else if ($f5 <= 20) {
                                        if ($f6 <= 25) {
                                            return 3;
                                        }
                                        else {
                                            return 5;
                                        }
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f5 <= 13) {
                                    if ($f5 <= 10) {
                                        if ($f5 <= 1) {
                                            return 3;
                                        }
                                        else if ($f5 <= 6) {
                                            return 4;
                                        }
                                        else if ($f5 <= 8) {
                                            return 3;
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f5 <= 30) {
                                    if ($f5 <= 27) {
                                        if ($f5 <= 23) {
                                            if ($f6 <= 10) {
                                                if ($f6 <= 1) {
                                                    return 4;
                                                }
                                                else if ($f5 <= 19) {
                                                    if ($f5 <= 17) {
                                                        return 4;
                                                    }
                                                    else {
                                                        return 3;
                                                    }
                                                }
                                                else {
                                                    return 4;
                                                }
                                            }
                                            else if ($f5 <= 22) {
                                                if ($f5 <= 18) {
                                                    return 4;
                                                }
                                                else if ($f5 <= 19) {
                                                    return 3;
                                                }
                                                else {
                                                    return 4;
                                                }
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f6 <= 21) {
                                    if ($f5 <= 31) {
                                        return 4;
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f5 <= 6) {
                                if ($f5 <= 1) {
                                    if ($f6 <= 27) {
                                        return 3;
                                    }
                                    else if ($f4 <= 22) {
                                        return 3;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f4 <= 19) {
                                    if ($f4 <= 17) {
                                        return 3;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f6 <= 18) {
                                    return 3;
                                }
                                else {
                                    return 1;
                                }
                            }
                            else if ($f5 <= 19) {
                                if ($f5 <= 18) {
                                    if ($f4 <= 25) {
                                        return 3;
                                    }
                                    else if ($f4 <= 26) {
                                        return 4;
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f6 <= 30) {
                                    return 3;
                                }
                                else {
                                    return 6;
                                }
                            }
                            else if ($f5 <= 32) {
                                if ($f4 <= 25) {
                                    if ($f4 <= 22) {
                                        if ($f5 <= 23) {
                                            if ($f4 <= 16) {
                                                return 4;
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f4 <= 26) {
                                    return 4;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f4 <= 22) {
                                return 3;
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f3 <= 12) {
                            if ($f3 <= 10) {
                                return 1;
                            }
                            else if ($f4 <= 3) {
                                return 3;
                            }
                            else if ($f9 <= 0) {
                                return 3;
                            }
                            else if ($f3 <= 11) {
                                if ($f6 <= 34) {
                                    return 3;
                                }
                                else {
                                    return 6;
                                }
                            }
                            else {
                                return 3;
                            }
                        }
                        else if ($f3 <= 32) {
                            return 1;
                        }
                        else if ($f4 <= 17) {
                            return 3;
                        }
                        else if ($f5 <= 13) {
                            if ($f5 <= 10) {
                                if ($f5 <= 1) {
                                    return 3;
                                }
                                else if ($f6 <= 11) {
                                    return 4;
                                }
                                else if ($f6 <= 17) {
                                    return 3;
                                }
                                else if ($f5 <= 6) {
                                    return 4;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else {
                                return 3;
                            }
                        }
                        else if ($f5 <= 30) {
                            if ($f5 <= 14) {
                                return 5;
                            }
                            else if ($f4 <= 18) {
                                return 4;
                            }
                            else if ($f4 <= 26) {
                                if ($f6 <= 18) {
                                    return 3;
                                }
                                else if ($f6 <= 19) {
                                    return 6;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f5 <= 31) {
                            return 5;
                        }
                        else {
                            return 3;
                        }
                    }
                    else {
                        return 1;
                    }
                }
                else if ($f2 <= 18) {
                    if ($f4 <= 13) {
                        if ($f3 <= 2) {
                            if ($f9 <= 0) {
                                return 1;
                            }
                            else if ($f7 <= 0) {
                                return 9;
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f3 <= 4) {
                            if ($f4 <= 11) {
                                return 1;
                            }
                            else if ($f4 <= 12) {
                                if ($f6 <= 20) {
                                    if ($f8 <= 0) {
                                        return 1;
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else if ($f6 <= 32) {
                                    return 1;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f4 <= 8) {
                            if ($f1 <= 11) {
                                if ($f1 <= 6) {
                                    if ($f1 <= 1) {
                                        return 1;
                                    }
                                    else {
                                        return 0;
                                    }
                                }
                                else {
                                    return 1;
                                }
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f8 <= 0) {
                            return 1;
                        }
                        else if ($f3 <= 26) {
                            if ($f3 <= 14) {
                                return 1;
                            }
                            else if ($f1 <= 6) {
                                if ($f1 <= 3) {
                                    return 1;
                                }
                                else {
                                    return 0;
                                }
                            }
                            else {
                                return 1;
                            }
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f4 <= 18) {
                        if ($f5 <= 32) {
                            if ($f1 <= 6) {
                                if ($f1 <= 2) {
                                    if ($f4 <= 17) {
                                        if ($f7 <= 0) {
                                            if ($f10 <= 1) {
                                                return 1;
                                            }
                                            else {
                                                return 8;
                                            }
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else if ($f5 <= 1) {
                                        if ($f6 <= 27) {
                                            return 1;
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else {
                                    return 0;
                                }
                            }
                            else if ($f3 <= 9) {
                                return 1;
                            }
                            else if ($f3 <= 11) {
                                if ($f5 <= 1) {
                                    if ($f10 <= 0) {
                                        return 1;
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else {
                                    return 1;
                                }
                            }
                            else if ($f9 <= 0) {
                                return 1;
                            }
                            else if ($f7 <= 0) {
                                return 9;
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f4 <= 17) {
                            return 1;
                        }
                        else if ($f3 <= 9) {
                            return 1;
                        }
                        else {
                            return 3;
                        }
                    }
                    else if ($f1 <= 6) {
                        if ($f1 <= 1) {
                            return 1;
                        }
                        else {
                            return 0;
                        }
                    }
                    else if ($f4 <= 32) {
                        return 1;
                    }
                    else if ($f3 <= 5) {
                        return 2;
                    }
                    else {
                        return 1;
                    }
                }
                else if ($f2 <= 19) {
                    return 0;
                }
                else if ($f10 <= 0) {
                    if ($f1 <= 6) {
                        if ($f1 <= 1) {
                            return 1;
                        }
                        else {
                            return 0;
                        }
                    }
                    else if ($f3 <= 19) {
                        if ($f3 <= 18) {
                            return 1;
                        }
                        else if ($f4 <= 3) {
                            return 3;
                        }
                        else {
                            return 1;
                        }
                    }
                    else {
                        return 1;
                    }
                }
                else if ($f5 <= 0) {
                    if ($f6 <= 12) {
                        return 6;
                    }
                    else {
                        return 7;
                    }
                }
                else if ($f9 <= 34) {
                    if ($f4 <= 3) {
                        return 1;
                    }
                    else if ($f6 <= 2) {
                        if ($f6 <= 0) {
                            return 9;
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f4 <= 27) {
                        if ($f10 <= 34) {
                            if ($f7 <= 0) {
                                return 9;
                            }
                            else {
                                return 1;
                            }
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f4 <= 28) {
                        return 3;
                    }
                    else {
                        return 1;
                    }
                }
                else {
                    return 1;
                }
            }
            else if ($f1 <= 17) {
                if ($f2 <= 7) {
                    if ($f1 <= 16) {
                        if ($f1 <= 14) {
                            if ($f2 <= 5) {
                                if ($f4 <= 1) {
                                    if ($f3 <= 3) {
                                        return 3;
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else {
                                    return 1;
                                }
                            }
                            else if ($f1 <= 13) {
                                return 1;
                            }
                            else if ($f4 <= 21) {
                                return 2;
                            }
                            else if ($f3 <= 22) {
                                return 2;
                            }
                            else {
                                return 5;
                            }
                        }
                        else {
                            return 0;
                        }
                    }
                    else if ($f2 <= 2) {
                        if ($f3 <= 15) {
                            if ($f3 <= 14) {
                                if ($f3 <= 5) {
                                    if ($f3 <= 3) {
                                        return 2;
                                    }
                                    else if ($f4 <= 22) {
                                        if ($f9 <= 0) {
                                            return 3;
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f4 <= 18) {
                                    return 2;
                                }
                                else if ($f4 <= 20) {
                                    if ($f5 <= 25) {
                                        return 2;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f4 <= 13) {
                                if ($f4 <= 10) {
                                    if ($f4 <= 1) {
                                        return 2;
                                    }
                                    else if ($f4 <= 6) {
                                        return 3;
                                    }
                                    else if ($f4 <= 8) {
                                        return 2;
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f4 <= 30) {
                                if ($f4 <= 27) {
                                    if ($f4 <= 23) {
                                        if ($f5 <= 10) {
                                            if ($f5 <= 1) {
                                                return 3;
                                            }
                                            else if ($f4 <= 19) {
                                                if ($f4 <= 17) {
                                                    return 3;
                                                }
                                                else {
                                                    return 2;
                                                }
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else if ($f4 <= 22) {
                                            if ($f4 <= 18) {
                                                return 3;
                                            }
                                            else if ($f4 <= 19) {
                                                return 2;
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f5 <= 21) {
                                if ($f4 <= 31) {
                                    return 3;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f4 <= 6) {
                            if ($f4 <= 1) {
                                if ($f5 <= 27) {
                                    return 2;
                                }
                                else if ($f3 <= 22) {
                                    return 2;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f3 <= 19) {
                                if ($f3 <= 17) {
                                    return 2;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f5 <= 18) {
                                return 2;
                            }
                            else {
                                return 0;
                            }
                        }
                        else if ($f4 <= 19) {
                            if ($f4 <= 18) {
                                if ($f3 <= 25) {
                                    return 2;
                                }
                                else if ($f3 <= 26) {
                                    return 3;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f5 <= 30) {
                                return 2;
                            }
                            else {
                                return 5;
                            }
                        }
                        else if ($f3 <= 25) {
                            if ($f4 <= 32) {
                                if ($f3 <= 21) {
                                    if ($f4 <= 23) {
                                        if ($f3 <= 16) {
                                            return 3;
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f3 <= 22) {
                                return 2;
                            }
                            else {
                                return 3;
                            }
                        }
                        else if ($f3 <= 26) {
                            return 3;
                        }
                        else {
                            return 2;
                        }
                    }
                    else if ($f5 <= 22) {
                        if ($f3 <= 27) {
                            if ($f3 <= 22) {
                                if ($f3 <= 8) {
                                    if ($f5 <= 20) {
                                        return 3;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f6 <= 0) {
                                    if ($f7 <= 8) {
                                        return 2;
                                    }
                                    else {
                                        return 8;
                                    }
                                }
                                else if ($f4 <= 2) {
                                    return 2;
                                }
                                else if ($f3 <= 16) {
                                    return 2;
                                }
                                else if ($f4 <= 9) {
                                    if ($f4 <= 6) {
                                        return 2;
                                    }
                                    else if ($f4 <= 8) {
                                        return 4;
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f3 <= 17) {
                                    return 3;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f4 <= 30) {
                                if ($f3 <= 26) {
                                    if ($f4 <= 9) {
                                        return 4;
                                    }
                                    else {
                                        return 0;
                                    }
                                }
                                else if ($f4 <= 14) {
                                    if ($f4 <= 13) {
                                        return 3;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else {
                                    return 3;
                                }
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f5 <= 3) {
                            return 2;
                        }
                        else if ($f4 <= 11) {
                            if ($f4 <= 1) {
                                return 2;
                            }
                            else if ($f4 <= 9) {
                                return 3;
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f3 <= 28) {
                            if ($f4 <= 20) {
                                return 3;
                            }
                            else {
                                return 2;
                            }
                        }
                        else {
                            return 2;
                        }
                    }
                    else if ($f8 <= 23) {
                        if ($f3 <= 17) {
                            if ($f3 <= 16) {
                                if ($f4 <= 12) {
                                    return 2;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f6 <= 3) {
                                if ($f7 <= 27) {
                                    if ($f7 <= 22) {
                                        if ($f8 <= 2) {
                                            if ($f7 <= 3) {
                                                return 7;
                                            }
                                            else {
                                                return 5;
                                            }
                                        }
                                        else {
                                            return 5;
                                        }
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else {
                                    return 5;
                                }
                            }
                            else if ($f6 <= 11) {
                                if ($f7 <= 21) {
                                    return 6;
                                }
                                else if ($f7 <= 24) {
                                    if ($f8 <= 21) {
                                        return 7;
                                    }
                                    else {
                                        return 8;
                                    }
                                }
                                else {
                                    return 6;
                                }
                            }
                            else if ($f6 <= 25) {
                                if ($f7 <= 22) {
                                    if ($f6 <= 12) {
                                        return 5;
                                    }
                                    else {
                                        return 6;
                                    }
                                }
                                else {
                                    return 7;
                                }
                            }
                            else if ($f7 <= 14) {
                                return 6;
                            }
                            else {
                                return 5;
                            }
                        }
                        else {
                            return 2;
                        }
                    }
                    else if ($f7 <= 3) {
                        if ($f6 <= 2) {
                            if ($f9 <= 9) {
                                return 7;
                            }
                            else if ($f9 <= 11) {
                                return 8;
                            }
                            else {
                                return 7;
                            }
                        }
                        else {
                            return 6;
                        }
                    }
                    else if ($f6 <= 2) {
                        return 5;
                    }
                    else if ($f6 <= 11) {
                        if ($f4 <= 9) {
                            return 2;
                        }
                        else {
                            return 6;
                        }
                    }
                    else if ($f3 <= 17) {
                        return 5;
                    }
                    else {
                        return 2;
                    }
                }
                else if ($f1 <= 14) {
                    if ($f2 <= 11) {
                        if ($f4 <= 8) {
                            if ($f4 <= 1) {
                                if ($f6 <= 22) {
                                    if ($f5 <= 3) {
                                        if ($f7 <= 29) {
                                            if ($f6 <= 15) {
                                                return 2;
                                            }
                                            else if ($f6 <= 20) {
                                                if ($f7 <= 14) {
                                                    if ($f7 <= 12) {
                                                        return 6;
                                                    }
                                                    else {
                                                        return 7;
                                                    }
                                                }
                                                else {
                                                    return 6;
                                                }
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else if ($f5 <= 2) {
                                            return 3;
                                        }
                                        else {
                                            return 7;
                                        }
                                    }
                                    else if ($f3 <= 19) {
                                        if ($f3 <= 17) {
                                            return 2;
                                        }
                                        else if ($f7 <= 13) {
                                            if ($f7 <= 11) {
                                                return 3;
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else if ($f3 <= 19) {
                                    if ($f5 <= 3) {
                                        if ($f5 <= 2) {
                                            return 3;
                                        }
                                        else {
                                            return 5;
                                        }
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f4 <= 6) {
                                return 2;
                            }
                            else if ($f3 <= 17) {
                                return 2;
                            }
                            else if ($f6 <= 6) {
                                if ($f6 <= 1) {
                                    if ($f5 <= 25) {
                                        if ($f5 <= 7) {
                                            return 5;
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else if ($f5 <= 17) {
                                    return 5;
                                }
                                else {
                                    return 4;
                                }
                            }
                            else if ($f6 <= 8) {
                                if ($f5 <= 29) {
                                    return 6;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else if ($f5 <= 26) {
                                if ($f5 <= 16) {
                                    if ($f8 <= 13) {
                                        return 4;
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else {
                                    return 4;
                                }
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f3 <= 18) {
                            if ($f4 <= 12) {
                                if ($f4 <= 11) {
                                    return 2;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f4 <= 32) {
                                if ($f10 <= 0) {
                                    if ($f3 <= 17) {
                                        if ($f3 <= 15) {
                                            if ($f1 <= 13) {
                                                return 3;
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f4 <= 23) {
                            if ($f10 <= 0) {
                                if ($f4 <= 11) {
                                    return 2;
                                }
                                else if ($f4 <= 21) {
                                    if ($f3 <= 24) {
                                        if ($f3 <= 22) {
                                            return 2;
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f3 <= 24) {
                            if ($f5 <= 17) {
                                return 2;
                            }
                            else if ($f5 <= 25) {
                                return 4;
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f5 <= 1) {
                            return 4;
                        }
                        else {
                            return 2;
                        }
                    }
                    else if ($f3 <= 18) {
                        if ($f3 <= 11) {
                            if ($f3 <= 3) {
                                if ($f3 <= 2) {
                                    return 2;
                                }
                                else if ($f4 <= 20) {
                                    if ($f4 <= 10) {
                                        return 2;
                                    }
                                    else if ($f4 <= 11) {
                                        return 2;
                                    }
                                    else if ($f4 <= 18) {
                                        return 4;
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else if ($f4 <= 32) {
                                    return 1;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f2 <= 12) {
                                if ($f5 <= 17) {
                                    if ($f5 <= 14) {
                                        if ($f5 <= 1) {
                                            return 1;
                                        }
                                        else if ($f3 <= 9) {
                                            return 2;
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else if ($f4 <= 18) {
                                    return 1;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f1 <= 13) {
                                return 2;
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f9 <= 0) {
                            if ($f3 <= 17) {
                                if ($f3 <= 15) {
                                    return 2;
                                }
                                else if ($f2 <= 15) {
                                    if ($f3 <= 16) {
                                        return 2;
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f2 <= 15) {
                                if ($f4 <= 19) {
                                    if ($f4 <= 18) {
                                        return 2;
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f4 <= 26) {
                            if ($f3 <= 17) {
                                if ($f3 <= 16) {
                                    return 2;
                                }
                                else if ($f2 <= 15) {
                                    return 1;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f4 <= 31) {
                            return 2;
                        }
                        else if ($f3 <= 15) {
                            return 5;
                        }
                        else {
                            return 2;
                        }
                    }
                    else if ($f3 <= 27) {
                        if ($f3 <= 24) {
                            if ($f3 <= 22) {
                                if ($f2 <= 16) {
                                    if ($f6 <= 18) {
                                        return 1;
                                    }
                                    else if ($f3 <= 20) {
                                        return 1;
                                    }
                                    else if ($f9 <= 1) {
                                        return 1;
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else if ($f1 <= 13) {
                                    return 2;
                                }
                                else {
                                    return 1;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f4 <= 9) {
                            return 1;
                        }
                        else if ($f3 <= 26) {
                            if ($f3 <= 25) {
                                return 1;
                            }
                            else {
                                return 2;
                            }
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f3 <= 30) {
                        return 2;
                    }
                    else {
                        return 3;
                    }
                }
                else if ($f1 <= 16) {
                    if ($f2 <= 18) {
                        return 0;
                    }
                    else if ($f1 <= 15) {
                        if ($f2 <= 19) {
                            if ($f5 <= 0) {
                                return 0;
                            }
                            else {
                                return 2;
                            }
                        }
                        else {
                            return 0;
                        }
                    }
                    else {
                        return 0;
                    }
                }
                else if ($f3 <= 14) {
                    if ($f3 <= 10) {
                        if ($f3 <= 3) {
                            if ($f3 <= 2) {
                                if ($f2 <= 9) {
                                    return 2;
                                }
                                else if ($f3 <= 1) {
                                    return 0;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f4 <= 20) {
                                if ($f2 <= 16) {
                                    return 2;
                                }
                                else if ($f4 <= 6) {
                                    return 2;
                                }
                                else {
                                    return 4;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f2 <= 20) {
                            if ($f4 <= 6) {
                                return 2;
                            }
                            else if ($f4 <= 19) {
                                if ($f5 <= 3) {
                                    if ($f5 <= 2) {
                                        return 2;
                                    }
                                    else if ($f2 <= 11) {
                                        if ($f3 <= 9) {
                                            return 2;
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else {
                            return 0;
                        }
                    }
                    else if ($f2 <= 8) {
                        return 2;
                    }
                    else {
                        return 0;
                    }
                }
                else if ($f3 <= 18) {
                    if ($f2 <= 15) {
                        if ($f10 <= 0) {
                            if ($f2 <= 11) {
                                if ($f2 <= 9) {
                                    return 3;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f4 <= 32) {
                            if ($f4 <= 0) {
                                return 7;
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f3 <= 15) {
                            return 4;
                        }
                        else {
                            return 2;
                        }
                    }
                    else if ($f3 <= 15) {
                        if ($f4 <= 13) {
                            if ($f4 <= 10) {
                                if ($f4 <= 1) {
                                    return 2;
                                }
                                else if ($f4 <= 6) {
                                    return 3;
                                }
                                else if ($f4 <= 8) {
                                    return 2;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f4 <= 30) {
                            if ($f4 <= 22) {
                                if ($f5 <= 10) {
                                    if ($f5 <= 1) {
                                        return 3;
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else if ($f4 <= 18) {
                                    return 3;
                                }
                                else if ($f4 <= 19) {
                                    return 2;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f4 <= 23) {
                                return 3;
                            }
                            else if ($f4 <= 24) {
                                return 2;
                            }
                            else {
                                return 3;
                            }
                        }
                        else {
                            return 2;
                        }
                    }
                    else if ($f6 <= 32) {
                        if ($f6 <= 1) {
                            if ($f7 <= 27) {
                                return 2;
                            }
                            else {
                                return 4;
                            }
                        }
                        else {
                            return 2;
                        }
                    }
                    else if ($f5 <= 18) {
                        if ($f5 <= 17) {
                            return 2;
                        }
                        else {
                            return 4;
                        }
                    }
                    else {
                        return 2;
                    }
                }
                else if ($f2 <= 13) {
                    if ($f3 <= 24) {
                        if ($f2 <= 9) {
                            if ($f4 <= 27) {
                                if ($f4 <= 9) {
                                    return 2;
                                }
                                else if ($f4 <= 12) {
                                    if ($f10 <= 0) {
                                        return 2;
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f4 <= 21) {
                            if ($f4 <= 11) {
                                if ($f10 <= 0) {
                                    if ($f4 <= 6) {
                                        return 2;
                                    }
                                    else if ($f4 <= 8) {
                                        if ($f3 <= 21) {
                                            return 2;
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f3 <= 21) {
                                return 2;
                            }
                            else if ($f4 <= 20) {
                                if ($f10 <= 1) {
                                    return 3;
                                }
                                else if ($f3 <= 22) {
                                    return 2;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f4 <= 22) {
                            if ($f10 <= 0) {
                                if ($f5 <= 11) {
                                    return 2;
                                }
                                else {
                                    return 4;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else {
                            return 2;
                        }
                    }
                    else if ($f3 <= 33) {
                        if ($f3 <= 28) {
                            if ($f2 <= 11) {
                                return 2;
                            }
                            else if ($f4 <= 20) {
                                if ($f4 <= 11) {
                                    if ($f4 <= 0) {
                                        return 7;
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else {
                                    return 3;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f4 <= 11) {
                            if ($f2 <= 11) {
                                return 2;
                            }
                            else if ($f4 <= 6) {
                                return 2;
                            }
                            else {
                                return 3;
                            }
                        }
                        else {
                            return 2;
                        }
                    }
                    else {
                        return 3;
                    }
                }
                else if ($f4 <= 18) {
                    if ($f10 <= 30) {
                        if ($f3 <= 19) {
                            return 0;
                        }
                        else {
                            return 2;
                        }
                    }
                    else {
                        return 2;
                    }
                }
                else if ($f4 <= 19) {
                    return 2;
                }
                else if ($f2 <= 20) {
                    if ($f3 <= 25) {
                        if ($f5 <= 18) {
                            if ($f5 <= 0) {
                                return 9;
                            }
                            else {
                                return 2;
                            }
                        }
                        else {
                            return 2;
                        }
                    }
                    else {
                        return 2;
                    }
                }
                else {
                    return 0;
                }
            }
            else if ($f1 <= 25) {
                if ($f1 <= 23) {
                    if ($f3 <= 7) {
                        if ($f3 <= 6) {
                            if ($f4 <= 3) {
                                if ($f5 <= 23) {
                                    if ($f2 <= 17) {
                                        if ($f2 <= 14) {
                                            if ($f3 <= 1) {
                                                return 1;
                                            }
                                            else {
                                                return 0;
                                            }
                                        }
                                        else if ($f5 <= 1) {
                                            return 4;
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else if ($f5 <= 18) {
                                        if ($f4 <= 1) {
                                            return 0;
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else if ($f6 <= 30) {
                                        if ($f5 <= 19) {
                                            if ($f6 <= 14) {
                                                if ($f6 <= 12) {
                                                    return 5;
                                                }
                                                else {
                                                    return 6;
                                                }
                                            }
                                            else {
                                                return 5;
                                            }
                                        }
                                        else {
                                            return 0;
                                        }
                                    }
                                    else if ($f4 <= 2) {
                                        return 2;
                                    }
                                    else {
                                        return 6;
                                    }
                                }
                                else if ($f4 <= 2) {
                                    if ($f2 <= 14) {
                                        return 0;
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else if ($f2 <= 17) {
                                    if ($f6 <= 27) {
                                        if ($f6 <= 9) {
                                            return 4;
                                        }
                                        else if ($f5 <= 31) {
                                            if ($f6 <= 12) {
                                                if ($f7 <= 10) {
                                                    return 4;
                                                }
                                                else if ($f7 <= 20) {
                                                    return 5;
                                                }
                                                else {
                                                    return 4;
                                                }
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else {
                                    return 4;
                                }
                            }
                            else if ($f1 <= 19) {
                                if ($f2 <= 14) {
                                    if ($f1 <= 18) {
                                        if ($f4 <= 13) {
                                            if ($f4 <= 11) {
                                                return 0;
                                            }
                                            else if ($f3 <= 3) {
                                                return 2;
                                            }
                                            else {
                                                return 0;
                                            }
                                        }
                                        else {
                                            return 0;
                                        }
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else if ($f2 <= 16) {
                                    if ($f2 <= 15) {
                                        return 2;
                                    }
                                    else if ($f4 <= 27) {
                                        if ($f4 <= 21) {
                                            if ($f3 <= 1) {
                                                return 2;
                                            }
                                            else if ($f4 <= 7) {
                                                return 4;
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else if ($f5 <= 20) {
                                            if ($f5 <= 12) {
                                                return 2;
                                            }
                                            else if ($f4 <= 23) {
                                                return 4;
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else if ($f3 <= 1) {
                                    if ($f2 <= 18) {
                                        if ($f2 <= 17) {
                                            if ($f4 <= 7) {
                                                return 2;
                                            }
                                            else {
                                                return 1;
                                            }
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else if ($f3 <= 3) {
                                    if ($f4 <= 11) {
                                        if ($f2 <= 17) {
                                            return 2;
                                        }
                                        else {
                                            return 0;
                                        }
                                    }
                                    else if ($f4 <= 13) {
                                        if ($f3 <= 2) {
                                            return 0;
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else if ($f2 <= 17) {
                                        return 2;
                                    }
                                    else {
                                        return 0;
                                    }
                                }
                                else if ($f2 <= 20) {
                                    return 0;
                                }
                                else {
                                    return 1;
                                }
                            }
                            else {
                                return 0;
                            }
                        }
                        else if ($f6 <= 22) {
                            if ($f1 <= 19) {
                                if ($f2 <= 16) {
                                    if ($f2 <= 15) {
                                        if ($f2 <= 11) {
                                            return 1;
                                        }
                                        else if ($f2 <= 14) {
                                            return 0;
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f2 <= 18) {
                                    if ($f4 <= 27) {
                                        if ($f4 <= 17) {
                                            if ($f5 <= 6) {
                                                if ($f5 <= 1) {
                                                    if ($f4 <= 9) {
                                                        return 4;
                                                    }
                                                    else {
                                                        return 3;
                                                    }
                                                }
                                                else {
                                                    return 4;
                                                }
                                            }
                                            else if ($f5 <= 8) {
                                                return 5;
                                            }
                                            else if ($f5 <= 27) {
                                                if ($f2 <= 17) {
                                                    return 4;
                                                }
                                                else {
                                                    return 3;
                                                }
                                            }
                                            else {
                                                return 5;
                                            }
                                        }
                                        else if ($f4 <= 21) {
                                            return 3;
                                        }
                                        else if ($f5 <= 9) {
                                            if ($f5 <= 5) {
                                                return 3;
                                            }
                                            else {
                                                return 5;
                                            }
                                        }
                                        else if ($f4 <= 22) {
                                            return 3;
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f1 <= 18) {
                                    return 0;
                                }
                                else {
                                    return 1;
                                }
                            }
                            else {
                                return 0;
                            }
                        }
                        else if ($f9 <= 23) {
                            if ($f6 <= 23) {
                                if ($f7 <= 1) {
                                    if ($f5 <= 11) {
                                        if ($f5 <= 9) {
                                            return 1;
                                        }
                                        else if ($f8 <= 27) {
                                            if ($f8 <= 22) {
                                                if ($f9 <= 2) {
                                                    if ($f8 <= 3) {
                                                        return 8;
                                                    }
                                                    else {
                                                        return 6;
                                                    }
                                                }
                                                else {
                                                    return 6;
                                                }
                                            }
                                            else {
                                                return 6;
                                            }
                                        }
                                        else {
                                            return 6;
                                        }
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f7 <= 31) {
                                    if ($f7 <= 11) {
                                        if ($f10 <= 0) {
                                            return 3;
                                        }
                                        else if ($f8 <= 21) {
                                            if ($f7 <= 10) {
                                                return 6;
                                            }
                                            else {
                                                return 7;
                                            }
                                        }
                                        else if ($f8 <= 23) {
                                            if ($f9 <= 21) {
                                                return 8;
                                            }
                                            else {
                                                return 9;
                                            }
                                        }
                                        else {
                                            return 7;
                                        }
                                    }
                                    else if ($f2 <= 17) {
                                        if ($f4 <= 17) {
                                            if ($f8 <= 22) {
                                                if ($f7 <= 12) {
                                                    return 6;
                                                }
                                                else {
                                                    return 7;
                                                }
                                            }
                                            else {
                                                return 8;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f8 <= 14) {
                                    return 7;
                                }
                                else {
                                    return 6;
                                }
                            }
                            else if ($f1 <= 20) {
                                if ($f2 <= 19) {
                                    if ($f2 <= 16) {
                                        return 1;
                                    }
                                    else if ($f5 <= 8) {
                                        return 4;
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else {
                                    return 1;
                                }
                            }
                            else {
                                return 0;
                            }
                        }
                        else if ($f8 <= 3) {
                            if ($f7 <= 6) {
                                if ($f10 <= 9) {
                                    return 8;
                                }
                                else if ($f10 <= 11) {
                                    return 9;
                                }
                                else {
                                    return 8;
                                }
                            }
                            else {
                                return 7;
                            }
                        }
                        else if ($f7 <= 4) {
                            return 6;
                        }
                        else if ($f7 <= 11) {
                            return 7;
                        }
                        else if ($f6 <= 23) {
                            return 6;
                        }
                        else {
                            return 3;
                        }
                    }
                    else if ($f3 <= 19) {
                        if ($f3 <= 18) {
                            if ($f2 <= 15) {
                                if ($f2 <= 14) {
                                    if ($f2 <= 10) {
                                        if ($f1 <= 19) {
                                            if ($f1 <= 18) {
                                                return 0;
                                            }
                                            else if ($f2 <= 3) {
                                                if ($f7 <= 1) {
                                                    return 1;
                                                }
                                                else if ($f4 <= 17) {
                                                    if ($f4 <= 14) {
                                                        return 1;
                                                    }
                                                    else {
                                                        return 0;
                                                    }
                                                }
                                                else {
                                                    return 1;
                                                }
                                            }
                                            else if ($f2 <= 5) {
                                                return 2;
                                            }
                                            else {
                                                return 1;
                                            }
                                        }
                                        else {
                                            return 0;
                                        }
                                    }
                                    else if ($f4 <= 32) {
                                        return 0;
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else if ($f4 <= 8) {
                                    if ($f4 <= 6) {
                                        if ($f6 <= 0) {
                                            if ($f8 <= 1) {
                                                return 2;
                                            }
                                            else {
                                                return 7;
                                            }
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else if ($f3 <= 17) {
                                        return 2;
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else if ($f1 <= 19) {
                                    return 2;
                                }
                                else {
                                    return 0;
                                }
                            }
                            else if ($f2 <= 17) {
                                if ($f4 <= 21) {
                                    if ($f2 <= 16) {
                                        if ($f3 <= 11) {
                                            if ($f3 <= 10) {
                                                return 2;
                                            }
                                            else if ($f4 <= 12) {
                                                if ($f4 <= 9) {
                                                    return 3;
                                                }
                                                else {
                                                    return 4;
                                                }
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else if ($f3 <= 12) {
                                            return 2;
                                        }
                                        else if ($f3 <= 13) {
                                            return 3;
                                        }
                                        else if ($f3 <= 14) {
                                            return 1;
                                        }
                                        else if ($f3 <= 15) {
                                            return 4;
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else if ($f4 <= 2) {
                                        if ($f5 <= 15) {
                                            if ($f6 <= 1) {
                                                if ($f5 <= 3) {
                                                    return 5;
                                                }
                                                else {
                                                    return 3;
                                                }
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else if ($f3 <= 11) {
                                        if ($f3 <= 10) {
                                            if ($f3 <= 8) {
                                                if ($f4 <= 14) {
                                                    return 3;
                                                }
                                                else {
                                                    return 4;
                                                }
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else if ($f4 <= 12) {
                                            if ($f4 <= 9) {
                                                return 3;
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else if ($f4 <= 11) {
                                        if ($f5 <= 15) {
                                            if ($f5 <= 10) {
                                                if ($f5 <= 6) {
                                                    return 4;
                                                }
                                                else {
                                                    return 5;
                                                }
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else if ($f3 <= 14) {
                                        if ($f3 <= 13) {
                                            if ($f5 <= 9) {
                                                if ($f5 <= 6) {
                                                    if ($f6 <= 32) {
                                                        return 3;
                                                    }
                                                    else {
                                                        return 4;
                                                    }
                                                }
                                                else {
                                                    return 3;
                                                }
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else if ($f4 <= 23) {
                                    if ($f5 <= 21) {
                                        if ($f2 <= 16) {
                                            if ($f3 <= 11) {
                                                return 4;
                                            }
                                            else if ($f5 <= 3) {
                                                return 2;
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else if ($f5 <= 22) {
                                        if ($f4 <= 22) {
                                            return 3;
                                        }
                                        else {
                                            return 5;
                                        }
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f4 <= 24) {
                                    if ($f5 <= 27) {
                                        if ($f5 <= 9) {
                                            return 3;
                                        }
                                        else if ($f5 <= 12) {
                                            if ($f6 <= 5) {
                                                return 3;
                                            }
                                            else if ($f6 <= 20) {
                                                return 4;
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else if ($f2 <= 16) {
                                            return 3;
                                        }
                                        else if ($f5 <= 16) {
                                            if ($f7 <= 24) {
                                                return 3;
                                            }
                                            else if ($f7 <= 25) {
                                                return 4;
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f2 <= 16) {
                                    if ($f3 <= 11) {
                                        return 3;
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else if ($f4 <= 31) {
                                    if ($f3 <= 11) {
                                        return 3;
                                    }
                                    else if ($f5 <= 20) {
                                        return 4;
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f4 <= 33) {
                                    if ($f5 <= 22) {
                                        return 3;
                                    }
                                    else if ($f5 <= 23) {
                                        return 2;
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else {
                                    return 4;
                                }
                            }
                            else if ($f3 <= 13) {
                                if ($f3 <= 10) {
                                    if ($f3 <= 8) {
                                        return 3;
                                    }
                                    else {
                                        return 0;
                                    }
                                }
                                else if ($f2 <= 18) {
                                    if ($f1 <= 20) {
                                        return 1;
                                    }
                                    else {
                                        return 0;
                                    }
                                }
                                else if ($f2 <= 19) {
                                    return 0;
                                }
                                else if ($f1 <= 19) {
                                    return 1;
                                }
                                else {
                                    return 0;
                                }
                            }
                            else if ($f2 <= 19) {
                                return 0;
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f2 <= 5) {
                            if ($f2 <= 3) {
                                return 1;
                            }
                            else if ($f4 <= 30) {
                                if ($f4 <= 3) {
                                    return 3;
                                }
                                else if ($f8 <= 0) {
                                    return 3;
                                }
                                else if ($f5 <= 34) {
                                    if ($f4 <= 14) {
                                        if ($f4 <= 12) {
                                            return 3;
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else if ($f6 <= 34) {
                                        return 3;
                                    }
                                    else {
                                        return 6;
                                    }
                                }
                                else {
                                    return 5;
                                }
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f1 <= 19) {
                            if ($f2 <= 16) {
                                if ($f2 <= 11) {
                                    return 1;
                                }
                                else if ($f2 <= 15) {
                                    return 0;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f1 <= 18) {
                                return 0;
                            }
                            else {
                                return 1;
                            }
                        }
                        else {
                            return 0;
                        }
                    }
                    else if ($f2 <= 14) {
                        if ($f2 <= 6) {
                            if ($f2 <= 1) {
                                if ($f3 <= 27) {
                                    return 0;
                                }
                                else if ($f1 <= 22) {
                                    return 0;
                                }
                                else {
                                    return 1;
                                }
                            }
                            else if ($f2 <= 3) {
                                return 1;
                            }
                            else if ($f2 <= 5) {
                                return 2;
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f2 <= 11) {
                            if ($f2 <= 10) {
                                if ($f2 <= 9) {
                                    return 0;
                                }
                                else {
                                    return 1;
                                }
                            }
                            else {
                                return 0;
                            }
                        }
                        else {
                            return 0;
                        }
                    }
                    else if ($f2 <= 17) {
                        if ($f2 <= 15) {
                            if ($f3 <= 30) {
                                if ($f1 <= 19) {
                                    return 2;
                                }
                                else {
                                    return 0;
                                }
                            }
                            else if ($f4 <= 21) {
                                if ($f5 <= 18) {
                                    if ($f4 <= 17) {
                                        return 2;
                                    }
                                    else {
                                        return 0;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f3 <= 32) {
                            if ($f3 <= 23) {
                                if ($f2 <= 16) {
                                    if ($f5 <= 16) {
                                        return 2;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f4 <= 18) {
                                if ($f4 <= 15) {
                                    if ($f3 <= 30) {
                                        return 2;
                                    }
                                    else if ($f4 <= 8) {
                                        return 3;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f3 <= 30) {
                                if ($f3 <= 25) {
                                    return 1;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f5 <= 21) {
                                return 4;
                            }
                            else if ($f5 <= 22) {
                                return 5;
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f4 <= 14) {
                            return 3;
                        }
                        else if ($f4 <= 22) {
                            return 2;
                        }
                        else {
                            return 4;
                        }
                    }
                    else if ($f2 <= 19) {
                        if ($f3 <= 32) {
                            return 0;
                        }
                        else if ($f4 <= 14) {
                            return 3;
                        }
                        else {
                            return 2;
                        }
                    }
                    else if ($f1 <= 19) {
                        return 1;
                    }
                    else {
                        return 0;
                    }
                }
                else if ($f3 <= 18) {
                    if ($f4 <= 22) {
                        if ($f2 <= 15) {
                            if ($f4 <= 14) {
                                if ($f2 <= 14) {
                                    if ($f2 <= 3) {
                                        return 1;
                                    }
                                    else if ($f3 <= 6) {
                                        if ($f3 <= 5) {
                                            if ($f2 <= 4) {
                                                return 2;
                                            }
                                            else {
                                                return 1;
                                            }
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else if ($f3 <= 7) {
                                        return 1;
                                    }
                                    else if ($f1 <= 24) {
                                        return 1;
                                    }
                                    else {
                                        return 0;
                                    }
                                }
                                else if ($f4 <= 10) {
                                    if ($f4 <= 1) {
                                        return 2;
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else if ($f3 <= 3) {
                                    return 2;
                                }
                                else if ($f3 <= 15) {
                                    if ($f6 <= 21) {
                                        return 2;
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f4 <= 18) {
                                if ($f3 <= 11) {
                                    return 1;
                                }
                                else if ($f2 <= 13) {
                                    return 1;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f2 <= 8) {
                                if ($f2 <= 3) {
                                    return 1;
                                }
                                else if ($f2 <= 5) {
                                    return 2;
                                }
                                else {
                                    return 1;
                                }
                            }
                            else if ($f4 <= 19) {
                                return 2;
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f3 <= 1) {
                            if ($f7 <= 0) {
                                if ($f10 <= 9) {
                                    return 1;
                                }
                                else {
                                    return 10;
                                }
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f3 <= 5) {
                            if ($f4 <= 11) {
                                return 1;
                            }
                            else if ($f3 <= 2) {
                                return 1;
                            }
                            else if ($f4 <= 14) {
                                return 2;
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f5 <= 31) {
                            if ($f8 <= 0) {
                                return 1;
                            }
                            else if ($f5 <= 1) {
                                return 1;
                            }
                            else if ($f6 <= 0) {
                                return 9;
                            }
                            else if ($f7 <= 0) {
                                return 8;
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f4 <= 17) {
                            return 1;
                        }
                        else if ($f4 <= 18) {
                            return 3;
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f4 <= 23) {
                        if ($f7 <= 23) {
                            if ($f2 <= 16) {
                                if ($f2 <= 13) {
                                    return 1;
                                }
                                else if ($f2 <= 15) {
                                    return 2;
                                }
                                else {
                                    return 1;
                                }
                            }
                            else if ($f2 <= 17) {
                                if ($f5 <= 3) {
                                    if ($f6 <= 26) {
                                        if ($f6 <= 22) {
                                            if ($f7 <= 2) {
                                                if ($f6 <= 3) {
                                                    return 6;
                                                }
                                                else {
                                                    return 4;
                                                }
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f5 <= 11) {
                                    if ($f6 <= 21) {
                                        return 5;
                                    }
                                    else if ($f6 <= 24) {
                                        if ($f7 <= 21) {
                                            return 6;
                                        }
                                        else {
                                            return 7;
                                        }
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else if ($f5 <= 31) {
                                    if ($f3 <= 9) {
                                        return 1;
                                    }
                                    else if ($f6 <= 22) {
                                        if ($f5 <= 12) {
                                            return 4;
                                        }
                                        else {
                                            return 5;
                                        }
                                    }
                                    else {
                                        return 6;
                                    }
                                }
                                else if ($f6 <= 14) {
                                    return 5;
                                }
                                else {
                                    return 4;
                                }
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f6 <= 3) {
                            if ($f5 <= 4) {
                                if ($f8 <= 9) {
                                    return 6;
                                }
                                else if ($f8 <= 11) {
                                    return 7;
                                }
                                else {
                                    return 6;
                                }
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f5 <= 2) {
                            if ($f2 <= 17) {
                                return 4;
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f5 <= 11) {
                            if ($f5 <= 9) {
                                return 1;
                            }
                            else {
                                return 5;
                            }
                        }
                        else if ($f2 <= 17) {
                            if ($f2 <= 16) {
                                return 1;
                            }
                            else {
                                return 4;
                            }
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f2 <= 15) {
                        if ($f2 <= 3) {
                            return 1;
                        }
                        else if ($f4 <= 31) {
                            if ($f4 <= 24) {
                                if ($f2 <= 8) {
                                    return 1;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 1;
                            }
                        }
                        else {
                            return 2;
                        }
                    }
                    else if ($f4 <= 32) {
                        if ($f2 <= 18) {
                            return 1;
                        }
                        else if ($f2 <= 19) {
                            return 0;
                        }
                        else {
                            return 1;
                        }
                    }
                    else {
                        return 1;
                    }
                }
                else if ($f3 <= 19) {
                    if ($f2 <= 16) {
                        if ($f2 <= 3) {
                            return 1;
                        }
                        else if ($f10 <= 0) {
                            return 1;
                        }
                        else if ($f7 <= 0) {
                            return 10;
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f2 <= 17) {
                        if ($f4 <= 30) {
                            if ($f4 <= 3) {
                                return 3;
                            }
                            else if ($f5 <= 30) {
                                if ($f8 <= 0) {
                                    if ($f5 <= 0) {
                                        return 8;
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f7 <= 13) {
                                if ($f6 <= 0) {
                                    return 9;
                                }
                                else if ($f5 <= 34) {
                                    return 3;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else if ($f5 <= 34) {
                                return 3;
                            }
                            else {
                                return 5;
                            }
                        }
                        else {
                            return 4;
                        }
                    }
                    else if ($f1 <= 24) {
                        return 1;
                    }
                    else {
                        return 0;
                    }
                }
                else if ($f2 <= 15) {
                    if ($f3 <= 30) {
                        if ($f2 <= 13) {
                            if ($f2 <= 1) {
                                return 0;
                            }
                            else if ($f2 <= 3) {
                                return 1;
                            }
                            else if ($f2 <= 5) {
                                return 2;
                            }
                            else if ($f1 <= 24) {
                                return 1;
                            }
                            else {
                                return 0;
                            }
                        }
                        else if ($f3 <= 27) {
                            if ($f3 <= 23) {
                                return 2;
                            }
                            else {
                                return 1;
                            }
                        }
                        else {
                            return 2;
                        }
                    }
                    else if ($f4 <= 21) {
                        if ($f2 <= 3) {
                            return 1;
                        }
                        else if ($f5 <= 16) {
                            if ($f2 <= 5) {
                                return 2;
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f4 <= 10) {
                            return 1;
                        }
                        else if ($f3 <= 31) {
                            return 2;
                        }
                        else {
                            return 1;
                        }
                    }
                    else {
                        return 1;
                    }
                }
                else if ($f8 <= 0) {
                    return 1;
                }
                else if ($f4 <= 0) {
                    return 9;
                }
                else if ($f1 <= 24) {
                    return 1;
                }
                else {
                    return 0;
                }
            }
            else if ($f1 <= 30) {
                if ($f1 <= 26) {
                    if ($f2 <= 1) {
                        if ($f3 <= 15) {
                            if ($f3 <= 14) {
                                if ($f3 <= 5) {
                                    if ($f3 <= 3) {
                                        if ($f10 <= 25) {
                                            return 2;
                                        }
                                        else {
                                            return 11;
                                        }
                                    }
                                    else if ($f4 <= 22) {
                                        if ($f9 <= 0) {
                                            return 3;
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f4 <= 18) {
                                    return 2;
                                }
                                else if ($f4 <= 20) {
                                    if ($f5 <= 25) {
                                        return 2;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f4 <= 13) {
                                if ($f4 <= 10) {
                                    if ($f4 <= 1) {
                                        return 2;
                                    }
                                    else if ($f4 <= 6) {
                                        return 3;
                                    }
                                    else if ($f4 <= 8) {
                                        return 2;
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f4 <= 30) {
                                if ($f4 <= 27) {
                                    if ($f4 <= 23) {
                                        if ($f5 <= 10) {
                                            if ($f5 <= 1) {
                                                return 3;
                                            }
                                            else if ($f4 <= 19) {
                                                if ($f4 <= 17) {
                                                    return 3;
                                                }
                                                else {
                                                    return 2;
                                                }
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else if ($f4 <= 22) {
                                            if ($f4 <= 18) {
                                                return 3;
                                            }
                                            else if ($f4 <= 19) {
                                                return 2;
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f5 <= 21) {
                                if ($f4 <= 31) {
                                    return 3;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f4 <= 6) {
                            if ($f4 <= 1) {
                                if ($f5 <= 27) {
                                    return 2;
                                }
                                else if ($f3 <= 22) {
                                    return 2;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f3 <= 19) {
                                if ($f3 <= 17) {
                                    return 2;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f4 <= 19) {
                            if ($f4 <= 18) {
                                if ($f3 <= 25) {
                                    return 2;
                                }
                                else if ($f3 <= 26) {
                                    return 3;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f5 <= 30) {
                                return 2;
                            }
                            else {
                                return 5;
                            }
                        }
                        else if ($f4 <= 32) {
                            if ($f3 <= 25) {
                                if ($f3 <= 21) {
                                    if ($f4 <= 23) {
                                        if ($f3 <= 16) {
                                            return 3;
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f3 <= 26) {
                                return 3;
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f3 <= 22) {
                            return 2;
                        }
                        else {
                            return 3;
                        }
                    }
                    else if ($f3 <= 14) {
                        if ($f2 <= 11) {
                            if ($f7 <= 0) {
                                return 2;
                            }
                            else if ($f4 <= 10) {
                                return 2;
                            }
                            else if ($f4 <= 11) {
                                return 2;
                            }
                            else if ($f4 <= 17) {
                                return 4;
                            }
                            else {
                                return 2;
                            }
                        }
                        else {
                            return 0;
                        }
                    }
                    else if ($f2 <= 12) {
                        if ($f8 <= 0) {
                            if ($f5 <= 0) {
                                return 0;
                            }
                            else if ($f2 <= 9) {
                                return 0;
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f5 <= 34) {
                            if ($f2 <= 9) {
                                return 0;
                            }
                            else {
                                return 2;
                            }
                        }
                        else {
                            return 5;
                        }
                    }
                    else {
                        return 0;
                    }
                }
                else if ($f2 <= 7) {
                    if ($f5 <= 22) {
                        if ($f2 <= 6) {
                            return 0;
                        }
                        else if ($f3 <= 26) {
                            if ($f3 <= 17) {
                                if ($f4 <= 2) {
                                    return 3;
                                }
                                else if ($f4 <= 8) {
                                    if ($f4 <= 6) {
                                        return 2;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f4 <= 27) {
                                    return 3;
                                }
                                else {
                                    return 4;
                                }
                            }
                            else if ($f3 <= 19) {
                                return 2;
                            }
                            else if ($f1 <= 28) {
                                return 0;
                            }
                            else if ($f4 <= 9) {
                                return 4;
                            }
                            else {
                                return 2;
                            }
                        }
                        else {
                            return 3;
                        }
                    }
                    else if ($f7 <= 3) {
                        if ($f8 <= 23) {
                            if ($f9 <= 0) {
                                if ($f2 <= 6) {
                                    return 0;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f6 <= 4) {
                                if ($f8 <= 5) {
                                    return 7;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else if ($f6 <= 11) {
                                return 6;
                            }
                            else {
                                return 5;
                            }
                        }
                        else if ($f6 <= 6) {
                            if ($f9 <= 9) {
                                return 7;
                            }
                            else if ($f9 <= 11) {
                                return 8;
                            }
                            else {
                                return 7;
                            }
                        }
                        else {
                            return 6;
                        }
                    }
                    else if ($f3 <= 17) {
                        if ($f6 <= 1) {
                            return 5;
                        }
                        else if ($f6 <= 32) {
                            if ($f3 <= 15) {
                                if ($f2 <= 4) {
                                    return 0;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f6 <= 11) {
                                if ($f7 <= 21) {
                                    if ($f7 <= 10) {
                                        if ($f7 <= 9) {
                                            return 6;
                                        }
                                        else {
                                            return 7;
                                        }
                                    }
                                    else {
                                        return 6;
                                    }
                                }
                                else if ($f7 <= 23) {
                                    return 7;
                                }
                                else {
                                    return 6;
                                }
                            }
                            else if ($f7 <= 22) {
                                if ($f6 <= 12) {
                                    return 5;
                                }
                                else {
                                    return 7;
                                }
                            }
                            else {
                                return 7;
                            }
                        }
                        else if ($f7 <= 14) {
                            return 6;
                        }
                        else {
                            return 5;
                        }
                    }
                    else if ($f3 <= 18) {
                        if ($f4 <= 12) {
                            return 3;
                        }
                        else {
                            return 2;
                        }
                    }
                    else {
                        return 3;
                    }
                }
                else if ($f1 <= 28) {
                    return 0;
                }
                else if ($f1 <= 29) {
                    if ($f3 <= 21) {
                        if ($f2 <= 11) {
                            if ($f3 <= 8) {
                                return 2;
                            }
                            else if ($f3 <= 10) {
                                if ($f3 <= 9) {
                                    if ($f5 <= 2) {
                                        return 3;
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else {
                                    return 3;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f2 <= 13) {
                            if ($f2 <= 12) {
                                return 0;
                            }
                            else {
                                return 2;
                            }
                        }
                        else {
                            return 0;
                        }
                    }
                    else if ($f3 <= 23) {
                        if ($f4 <= 21) {
                            return 3;
                        }
                        else if ($f4 <= 23) {
                            return 4;
                        }
                        else {
                            return 3;
                        }
                    }
                    else if ($f3 <= 24) {
                        if ($f4 <= 27) {
                            if ($f4 <= 9) {
                                return 2;
                            }
                            else if ($f4 <= 12) {
                                if ($f10 <= 0) {
                                    return 2;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else {
                            return 2;
                        }
                    }
                    else if ($f3 <= 33) {
                        if ($f2 <= 11) {
                            return 2;
                        }
                        else {
                            return 0;
                        }
                    }
                    else {
                        return 3;
                    }
                }
                else {
                    return 0;
                }
            }
            else if ($f2 <= 18) {
                if ($f2 <= 8) {
                    if ($f1 <= 32) {
                        if ($f2 <= 6) {
                            if ($f2 <= 3) {
                                return 1;
                            }
                            else if ($f4 <= 2) {
                                if ($f7 <= 22) {
                                    if ($f5 <= 32) {
                                        return 1;
                                    }
                                    else {
                                        return 7;
                                    }
                                }
                                else {
                                    return 9;
                                }
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f2 <= 7) {
                            if ($f3 <= 26) {
                                if ($f4 <= 30) {
                                    if ($f6 <= 1) {
                                        return 2;
                                    }
                                    else if ($f3 <= 19) {
                                        if ($f3 <= 17) {
                                            return 2;
                                        }
                                        else if ($f4 <= 19) {
                                            return 2;
                                        }
                                        else if ($f4 <= 21) {
                                            return 3;
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else {
                                    return 4;
                                }
                            }
                            else if ($f9 <= 31) {
                                return 2;
                            }
                            else {
                                return 10;
                            }
                        }
                        else {
                            return 2;
                        }
                    }
                    else if ($f2 <= 2) {
                        return 2;
                    }
                    else {
                        return 1;
                    }
                }
                else if ($f2 <= 13) {
                    return 1;
                }
                else if ($f1 <= 31) {
                    if ($f3 <= 6) {
                        return 1;
                    }
                    else if ($f3 <= 12) {
                        if ($f3 <= 9) {
                            if ($f2 <= 17) {
                                return 1;
                            }
                            else if ($f4 <= 8) {
                                return 3;
                            }
                            else if ($f5 <= 1) {
                                return 3;
                            }
                            else {
                                return 1;
                            }
                        }
                        else {
                            return 1;
                        }
                    }
                    else {
                        return 1;
                    }
                }
                else if ($f4 <= 13) {
                    if ($f2 <= 17) {
                        if ($f1 <= 32) {
                            if ($f4 <= 1) {
                                return 2;
                            }
                            else if ($f4 <= 6) {
                                return 1;
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f3 <= 5) {
                            if ($f4 <= 11) {
                                return 0;
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f3 <= 16) {
                            return 1;
                        }
                        else {
                            return 0;
                        }
                    }
                    else if ($f5 <= 4) {
                        if ($f6 <= 21) {
                            if ($f3 <= 19) {
                                return 1;
                            }
                            else {
                                return 2;
                            }
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f3 <= 17) {
                        return 1;
                    }
                    else if ($f3 <= 19) {
                        return 1;
                    }
                    else if ($f3 <= 22) {
                        return 2;
                    }
                    else {
                        return 1;
                    }
                }
                else if ($f1 <= 32) {
                    if ($f4 <= 17) {
                        if ($f3 <= 28) {
                            return 1;
                        }
                        else {
                            return 3;
                        }
                    }
                    else if ($f4 <= 19) {
                        if ($f2 <= 17) {
                            return 2;
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f4 <= 20) {
                        return 1;
                    }
                    else if ($f4 <= 23) {
                        return 1;
                    }
                    else if ($f4 <= 24) {
                        return 2;
                    }
                    else {
                        return 1;
                    }
                }
                else if ($f3 <= 30) {
                    if ($f3 <= 17) {
                        if ($f3 <= 6) {
                            if ($f3 <= 1) {
                                return 1;
                            }
                            else if ($f2 <= 17) {
                                return 0;
                            }
                            else {
                                return 2;
                            }
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f2 <= 17) {
                        if ($f3 <= 19) {
                            return 1;
                        }
                        else {
                            return 0;
                        }
                    }
                    else {
                        return 1;
                    }
                }
                else {
                    return 3;
                }
            }
            else if ($f3 <= 15) {
                if ($f4 <= 30) {
                    if ($f3 <= 13) {
                        if ($f2 <= 20) {
                            if ($f3 <= 3) {
                                return 2;
                            }
                            else if ($f3 <= 5) {
                                return 3;
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f1 <= 32) {
                            return 1;
                        }
                        else if ($f3 <= 1) {
                            return 1;
                        }
                        else {
                            return 0;
                        }
                    }
                    else if ($f7 <= 0) {
                        if ($f8 <= 16) {
                            return 3;
                        }
                        else {
                            return 9;
                        }
                    }
                    else if ($f4 <= 3) {
                        return 3;
                    }
                    else if ($f4 <= 26) {
                        if ($f4 <= 18) {
                            return 3;
                        }
                        else if ($f4 <= 19) {
                            return 2;
                        }
                        else {
                            return 3;
                        }
                    }
                    else {
                        return 3;
                    }
                }
                else if ($f5 <= 21) {
                    if ($f3 <= 3) {
                        return 2;
                    }
                    else if ($f6 <= 18) {
                        if ($f4 <= 31) {
                            if ($f7 <= 22) {
                                return 2;
                            }
                            else {
                                return 3;
                            }
                        }
                        else {
                            return 3;
                        }
                    }
                    else if ($f5 <= 10) {
                        return 2;
                    }
                    else {
                        return 3;
                    }
                }
                else if ($f4 <= 31) {
                    return 2;
                }
                else {
                    return 3;
                }
            }
            else if ($f2 <= 20) {
                if ($f3 <= 30) {
                    if ($f3 <= 25) {
                        if ($f4 <= 3) {
                            if ($f4 <= 2) {
                                if ($f1 <= 32) {
                                    return 2;
                                }
                                else {
                                    return 1;
                                }
                            }
                            else if ($f5 <= 11) {
                                return 2;
                            }
                            else if ($f5 <= 16) {
                                return 3;
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f8 <= 0) {
                            if ($f10 <= 9) {
                                return 2;
                            }
                            else {
                                return 10;
                            }
                        }
                        else if ($f1 <= 32) {
                            if ($f6 <= 0) {
                                return 8;
                            }
                            else {
                                return 2;
                            }
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f3 <= 26) {
                        return 3;
                    }
                    else {
                        return 2;
                    }
                }
                else {
                    return 3;
                }
            }
            else if ($f5 <= 0) {
                if ($f6 <= 18) {
                    return 7;
                }
                else {
                    return 8;
                }
            }
            else if ($f1 <= 32) {
                return 1;
            }
            else {
                return 0;
            }
        }
        if ($f2 > 21) {
            if ($f2 <= 22) {
                if ($f5 <= 1) {
                    if ($f6 <= 3) {
                        if ($f7 <= 23) {
                            if ($f7 <= 2) {
                                if ($f8 <= 3) {
                                    if ($f1 <= 13) {
                                        return 1;
                                    }
                                    else if ($f1 <= 23) {
                                        return 0;
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else if ($f6 <= 2) {
                                    return 3;
                                }
                                else if ($f9 <= 0) {
                                    return 3;
                                }
                                else {
                                    return 6;
                                }
                            }
                            else if ($f7 <= 20) {
                                if ($f7 <= 10) {
                                    if ($f6 <= 2) {
                                        return 4;
                                    }
                                    else if ($f10 <= 0) {
                                        return 6;
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else if ($f7 <= 13) {
                                    return 4;
                                }
                                else if ($f1 <= 27) {
                                    return 1;
                                }
                                else {
                                    return 4;
                                }
                            }
                            else if ($f6 <= 2) {
                                return 4;
                            }
                            else if ($f7 <= 21) {
                                return 5;
                            }
                            else {
                                return 3;
                            }
                        }
                        else if ($f6 <= 2) {
                            return 4;
                        }
                        else if ($f10 <= 0) {
                            return 4;
                        }
                        else if ($f4 <= 12) {
                            return 4;
                        }
                        else if ($f9 <= 11) {
                            if ($f8 <= 7) {
                                return 6;
                            }
                            else if ($f8 <= 21) {
                                return 6;
                            }
                            else if ($f8 <= 22) {
                                if ($f10 <= 13) {
                                    return 4;
                                }
                                else {
                                    return 6;
                                }
                            }
                            else {
                                return 6;
                            }
                        }
                        else {
                            return 6;
                        }
                    }
                    else if ($f1 <= 27) {
                        if ($f1 <= 13) {
                            return 1;
                        }
                        else if ($f1 <= 23) {
                            return 0;
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f3 <= 30) {
                        if ($f6 <= 27) {
                            if ($f6 <= 9) {
                                if ($f6 <= 7) {
                                    return 4;
                                }
                                else if ($f7 <= 6) {
                                    return 4;
                                }
                                else if ($f7 <= 10) {
                                    return 5;
                                }
                                else {
                                    return 4;
                                }
                            }
                            else if ($f6 <= 12) {
                                if ($f7 <= 1) {
                                    return 6;
                                }
                                else if ($f7 <= 6) {
                                    return 4;
                                }
                                else if ($f8 <= 15) {
                                    return 4;
                                }
                                else {
                                    return 6;
                                }
                            }
                            else if ($f6 <= 21) {
                                if ($f7 <= 6) {
                                    if ($f6 <= 20) {
                                        if ($f7 <= 3) {
                                            if ($f9 <= 19) {
                                                return 4;
                                            }
                                            else if ($f7 <= 1) {
                                                return 6;
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else if ($f7 <= 5) {
                                            return 7;
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else if ($f7 <= 1) {
                                        return 4;
                                    }
                                    else if ($f8 <= 11) {
                                        return 6;
                                    }
                                    else if ($f8 <= 15) {
                                        return 8;
                                    }
                                    else {
                                        return 6;
                                    }
                                }
                                else if ($f6 <= 19) {
                                    if ($f7 <= 21) {
                                        return 4;
                                    }
                                    else if ($f7 <= 22) {
                                        if ($f6 <= 15) {
                                            if ($f9 <= 17) {
                                                if ($f8 <= 20) {
                                                    return 7;
                                                }
                                                else {
                                                    return 4;
                                                }
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else if ($f6 <= 16) {
                                            return 6;
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f7 <= 22) {
                                    if ($f7 <= 21) {
                                        if ($f7 <= 19) {
                                            return 4;
                                        }
                                        else if ($f6 <= 20) {
                                            return 4;
                                        }
                                        else {
                                            return 6;
                                        }
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else if ($f4 <= 28) {
                                    return 4;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f7 <= 20) {
                                if ($f7 <= 6) {
                                    if ($f8 <= 1) {
                                        return 1;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f7 <= 8) {
                                    return 6;
                                }
                                else if ($f8 <= 20) {
                                    if ($f7 <= 12) {
                                        if ($f6 <= 22) {
                                            return 6;
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else if ($f6 <= 24) {
                                        if ($f9 <= 6) {
                                            return 4;
                                        }
                                        else {
                                            return 6;
                                        }
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f8 <= 33) {
                                    return 4;
                                }
                                else {
                                    return 6;
                                }
                            }
                            else if ($f6 <= 22) {
                                if ($f7 <= 23) {
                                    if ($f7 <= 21) {
                                        return 4;
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else {
                                    return 4;
                                }
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f4 <= 25) {
                            return 4;
                        }
                        else {
                            return 5;
                        }
                    }
                    else if ($f4 <= 22) {
                        return 3;
                    }
                    else if ($f4 <= 27) {
                        return 5;
                    }
                    else {
                        return 3;
                    }
                }
                else if ($f9 <= 1) {
                    if ($f8 <= 22) {
                        if ($f1 <= 28) {
                            if ($f1 <= 13) {
                                return 1;
                            }
                            else if ($f1 <= 23) {
                                if ($f1 <= 19) {
                                    if ($f1 <= 18) {
                                        return 0;
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else {
                                    return 0;
                                }
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f10 <= 1) {
                            if ($f4 <= 6) {
                                if ($f3 <= 31) {
                                    if ($f3 <= 18) {
                                        if ($f3 <= 12) {
                                            if ($f3 <= 2) {
                                                return 2;
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else if ($f4 <= 1) {
                                        return 2;
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f3 <= 31) {
                                if ($f4 <= 14) {
                                    if ($f3 <= 16) {
                                        if ($f3 <= 6) {
                                            if ($f5 <= 21) {
                                                return 4;
                                            }
                                            else if ($f5 <= 23) {
                                                return 5;
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else if ($f3 <= 10) {
                                            return 2;
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else if ($f3 <= 19) {
                                        return 4;
                                    }
                                    else if ($f3 <= 28) {
                                        if ($f3 <= 23) {
                                            return 2;
                                        }
                                        else if ($f5 <= 6) {
                                            return 4;
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f4 <= 23) {
                                    if ($f4 <= 22) {
                                        if ($f4 <= 17) {
                                            if ($f5 <= 16) {
                                                if ($f5 <= 6) {
                                                    return 4;
                                                }
                                                else if ($f4 <= 16) {
                                                    if ($f8 <= 1) {
                                                        return 5;
                                                    }
                                                    else if ($f5 <= 14) {
                                                        return 5;
                                                    }
                                                    else {
                                                        return 6;
                                                    }
                                                }
                                                else {
                                                    return 5;
                                                }
                                            }
                                            else if ($f5 <= 19) {
                                                return 3;
                                            }
                                            else if ($f5 <= 22) {
                                                return 4;
                                            }
                                            else if ($f5 <= 30) {
                                                if ($f6 <= 18) {
                                                    if ($f6 <= 13) {
                                                        return 4;
                                                    }
                                                    else {
                                                        return 5;
                                                    }
                                                }
                                                else {
                                                    return 4;
                                                }
                                            }
                                            else {
                                                return 5;
                                            }
                                        }
                                        else if ($f4 <= 18) {
                                            return 3;
                                        }
                                        else if ($f5 <= 7) {
                                            if ($f5 <= 6) {
                                                return 3;
                                            }
                                            else {
                                                return 5;
                                            }
                                        }
                                        else if ($f3 <= 5) {
                                            return 2;
                                        }
                                        else if ($f3 <= 15) {
                                            if ($f4 <= 21) {
                                                return 3;
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else if ($f3 <= 27) {
                                        if ($f5 <= 16) {
                                            if ($f5 <= 5) {
                                                return 4;
                                            }
                                            else {
                                                return 5;
                                            }
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else if ($f3 <= 12) {
                                    if ($f5 <= 30) {
                                        if ($f3 <= 10) {
                                            if ($f3 <= 3) {
                                                if ($f4 <= 32) {
                                                    return 4;
                                                }
                                                else if ($f5 <= 14) {
                                                    return 4;
                                                }
                                                else {
                                                    return 3;
                                                }
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else if ($f5 <= 10) {
                                            return 5;
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else if ($f3 <= 15) {
                                    return 3;
                                }
                                else if ($f3 <= 23) {
                                    return 2;
                                }
                                else if ($f3 <= 26) {
                                    return 4;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f5 <= 3) {
                                return 4;
                            }
                            else if ($f4 <= 20) {
                                return 3;
                            }
                            else if ($f4 <= 33) {
                                return 3;
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f4 <= 14) {
                            if ($f4 <= 6) {
                                if ($f5 <= 3) {
                                    if ($f6 <= 22) {
                                        return 3;
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f5 <= 23) {
                                if ($f5 <= 21) {
                                    return 4;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else if ($f4 <= 7) {
                                return 5;
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f4 <= 23) {
                            if ($f6 <= 23) {
                                if ($f6 <= 21) {
                                    if ($f5 <= 32) {
                                        if ($f5 <= 6) {
                                            return 4;
                                        }
                                        else if ($f5 <= 7) {
                                            if ($f6 <= 12) {
                                                return 6;
                                            }
                                            else {
                                                return 5;
                                            }
                                        }
                                        else if ($f3 <= 31) {
                                            if ($f5 <= 11) {
                                                if ($f5 <= 10) {
                                                    if ($f5 <= 8) {
                                                        return 5;
                                                    }
                                                    else {
                                                        return 4;
                                                    }
                                                }
                                                else if ($f6 <= 13) {
                                                    if ($f6 <= 9) {
                                                        return 5;
                                                    }
                                                    else {
                                                        return 6;
                                                    }
                                                }
                                                else {
                                                    return 5;
                                                }
                                            }
                                            else if ($f6 <= 2) {
                                                return 5;
                                            }
                                            else if ($f4 <= 16) {
                                                if ($f5 <= 12) {
                                                    return 4;
                                                }
                                                else {
                                                    return 5;
                                                }
                                            }
                                            else {
                                                return 5;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else if ($f3 <= 25) {
                                        if ($f6 <= 14) {
                                            return 5;
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else if ($f8 <= 3) {
                                    if ($f7 <= 4) {
                                        return 8;
                                    }
                                    else {
                                        return 6;
                                    }
                                }
                                else if ($f7 <= 11) {
                                    if ($f7 <= 4) {
                                        if ($f6 <= 22) {
                                            return 5;
                                        }
                                        else {
                                            return 6;
                                        }
                                    }
                                    else if ($f8 <= 21) {
                                        return 7;
                                    }
                                    else {
                                        return 6;
                                    }
                                }
                                else {
                                    return 6;
                                }
                            }
                            else if ($f5 <= 7) {
                                return 6;
                            }
                            else if ($f5 <= 14) {
                                return 5;
                            }
                            else if ($f5 <= 30) {
                                return 4;
                            }
                            else if ($f3 <= 25) {
                                return 4;
                            }
                            else {
                                return 5;
                            }
                        }
                        else if ($f3 <= 12) {
                            if ($f3 <= 7) {
                                if ($f4 <= 28) {
                                    return 4;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f5 <= 5) {
                                return 5;
                            }
                            else if ($f5 <= 28) {
                                return 4;
                            }
                            else {
                                return 5;
                            }
                        }
                        else {
                            return 3;
                        }
                    }
                    else if ($f10 <= 3) {
                        if ($f10 <= 1) {
                            if ($f1 <= 27) {
                                return 1;
                            }
                            else if ($f4 <= 1) {
                                return 3;
                            }
                            else if ($f4 <= 17) {
                                if ($f4 <= 12) {
                                    return 4;
                                }
                                else if ($f5 <= 16) {
                                    if ($f5 <= 5) {
                                        return 4;
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else {
                                    return 4;
                                }
                            }
                            else if ($f4 <= 22) {
                                return 3;
                            }
                            else if ($f4 <= 23) {
                                return 5;
                            }
                            else if ($f3 <= 12) {
                                if ($f4 <= 26) {
                                    return 4;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else {
                                return 3;
                            }
                        }
                        else if ($f10 <= 2) {
                            if ($f6 <= 19) {
                                return 8;
                            }
                            else {
                                return 5;
                            }
                        }
                        else if ($f7 <= 6) {
                            return 6;
                        }
                        else {
                            return 10;
                        }
                    }
                    else if ($f7 <= 11) {
                        if ($f8 <= 23) {
                            if ($f6 <= 17) {
                                if ($f4 <= 14) {
                                    return 4;
                                }
                                else {
                                    return 8;
                                }
                            }
                            else if ($f6 <= 22) {
                                return 4;
                            }
                            else {
                                return 5;
                            }
                        }
                        else if ($f5 <= 13) {
                            if ($f6 <= 20) {
                                if ($f5 <= 10) {
                                    return 7;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else {
                                return 6;
                            }
                        }
                        else {
                            return 4;
                        }
                    }
                    else if ($f5 <= 14) {
                        if ($f4 <= 23) {
                            return 5;
                        }
                        else if ($f4 <= 28) {
                            return 3;
                        }
                        else {
                            return 6;
                        }
                    }
                    else if ($f5 <= 23) {
                        if ($f1 <= 27) {
                            return 1;
                        }
                        else {
                            return 3;
                        }
                    }
                    else if ($f4 <= 16) {
                        return 4;
                    }
                    else {
                        return 5;
                    }
                }
                else if ($f5 <= 14) {
                    if ($f5 <= 7) {
                        if ($f4 <= 6) {
                            if ($f6 <= 23) {
                                if ($f1 <= 27) {
                                    return 1;
                                }
                                else if ($f6 <= 5) {
                                    if ($f5 <= 3) {
                                        return 5;
                                    }
                                    else if ($f4 <= 3) {
                                        return 3;
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else if ($f5 <= 6) {
                                    return 3;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else if ($f3 <= 7) {
                                if ($f5 <= 3) {
                                    if ($f5 <= 2) {
                                        return 3;
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else {
                                    return 3;
                                }
                            }
                            else {
                                return 3;
                            }
                        }
                        else if ($f4 <= 28) {
                            if ($f5 <= 6) {
                                if ($f4 <= 23) {
                                    if ($f1 <= 27) {
                                        return 1;
                                    }
                                    else if ($f4 <= 11) {
                                        if ($f4 <= 8) {
                                            if ($f5 <= 4) {
                                                return 6;
                                            }
                                            else {
                                                return 5;
                                            }
                                        }
                                        else if ($f6 <= 10) {
                                            if ($f6 <= 8) {
                                                return 4;
                                            }
                                            else {
                                                return 6;
                                            }
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else if ($f3 <= 20) {
                                        if ($f7 <= 21) {
                                            if ($f7 <= 16) {
                                                return 4;
                                            }
                                            else if ($f8 <= 20) {
                                                if ($f5 <= 5) {
                                                    if ($f5 <= 3) {
                                                        if ($f5 <= 2) {
                                                            if ($f6 <= 9) {
                                                                return 6;
                                                            }
                                                            else {
                                                                return 4;
                                                            }
                                                        }
                                                        else {
                                                            return 4;
                                                        }
                                                    }
                                                    else {
                                                        return 6;
                                                    }
                                                }
                                                else {
                                                    return 4;
                                                }
                                            }
                                            else {
                                                return 5;
                                            }
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else if ($f4 <= 17) {
                                        if ($f4 <= 14) {
                                            return 3;
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f8 <= 22) {
                                if ($f6 <= 27) {
                                    if ($f1 <= 27) {
                                        return 1;
                                    }
                                    else if ($f6 <= 9) {
                                        if ($f6 <= 4) {
                                            if ($f8 <= 20) {
                                                if ($f7 <= 11) {
                                                    return 6;
                                                }
                                                else if ($f7 <= 20) {
                                                    return 7;
                                                }
                                                else {
                                                    return 5;
                                                }
                                            }
                                            else {
                                                return 7;
                                            }
                                        }
                                        else if ($f7 <= 6) {
                                            return 6;
                                        }
                                        else if ($f7 <= 28) {
                                            if ($f7 <= 8) {
                                                return 7;
                                            }
                                            else if ($f7 <= 18) {
                                                return 6;
                                            }
                                            else if ($f7 <= 20) {
                                                return 7;
                                            }
                                            else {
                                                return 6;
                                            }
                                        }
                                        else {
                                            return 7;
                                        }
                                    }
                                    else if ($f6 <= 19) {
                                        if ($f6 <= 17) {
                                            if ($f6 <= 16) {
                                                if ($f9 <= 21) {
                                                    return 5;
                                                }
                                                else {
                                                    return 6;
                                                }
                                            }
                                            else if ($f7 <= 20) {
                                                return 6;
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else if ($f7 <= 8) {
                                            if ($f7 <= 6) {
                                                return 5;
                                            }
                                            else {
                                                return 8;
                                            }
                                        }
                                        else {
                                            return 5;
                                        }
                                    }
                                    else if ($f7 <= 19) {
                                        if ($f4 <= 16) {
                                            if ($f6 <= 21) {
                                                return 4;
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else if ($f6 <= 21) {
                                            if ($f7 <= 17) {
                                                if ($f8 <= 6) {
                                                    return 7;
                                                }
                                                else {
                                                    return 3;
                                                }
                                            }
                                            else {
                                                return 5;
                                            }
                                        }
                                        else {
                                            return 7;
                                        }
                                    }
                                    else if ($f6 <= 21) {
                                        return 6;
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else if ($f10 <= 3) {
                                    if ($f10 <= 2) {
                                        return 6;
                                    }
                                    else {
                                        return 7;
                                    }
                                }
                                else {
                                    return 6;
                                }
                            }
                            else if ($f9 <= 31) {
                                if ($f6 <= 17) {
                                    if ($f8 <= 23) {
                                        if ($f10 <= 21) {
                                            if ($f9 <= 11) {
                                                if ($f9 <= 10) {
                                                    return 10;
                                                }
                                                else if ($f10 <= 13) {
                                                    if ($f10 <= 9) {
                                                        return 9;
                                                    }
                                                    else {
                                                        return 10;
                                                    }
                                                }
                                                else {
                                                    return 9;
                                                }
                                            }
                                            else {
                                                return 9;
                                            }
                                        }
                                        else if ($f10 <= 23) {
                                            return 10;
                                        }
                                        else {
                                            return 9;
                                        }
                                    }
                                    else {
                                        return 6;
                                    }
                                }
                                else if ($f7 <= 8) {
                                    if ($f4 <= 16) {
                                        return 5;
                                    }
                                    else {
                                        return 6;
                                    }
                                }
                                else if ($f6 <= 19) {
                                    return 5;
                                }
                                else {
                                    return 6;
                                }
                            }
                            else if ($f10 <= 14) {
                                return 9;
                            }
                            else {
                                return 8;
                            }
                        }
                        else if ($f6 <= 1) {
                            if ($f7 <= 3) {
                                if ($f8 <= 22) {
                                    if ($f8 <= 2) {
                                        return 7;
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else if ($f7 <= 2) {
                                    return 5;
                                }
                                else {
                                    return 7;
                                }
                            }
                            else {
                                return 5;
                            }
                        }
                        else if ($f6 <= 26) {
                            if ($f5 <= 5) {
                                if ($f7 <= 21) {
                                    if ($f6 <= 11) {
                                        if ($f7 <= 13) {
                                            if ($f7 <= 9) {
                                                return 6;
                                            }
                                            else {
                                                return 7;
                                            }
                                        }
                                        else {
                                            return 6;
                                        }
                                    }
                                    else {
                                        return 6;
                                    }
                                }
                                else if ($f7 <= 23) {
                                    if ($f8 <= 21) {
                                        return 7;
                                    }
                                    else if ($f8 <= 23) {
                                        return 8;
                                    }
                                    else {
                                        return 7;
                                    }
                                }
                                else if ($f6 <= 11) {
                                    return 6;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f7 <= 14) {
                            if ($f5 <= 3) {
                                return 6;
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f7 <= 21) {
                            return 5;
                        }
                        else {
                            return 7;
                        }
                    }
                    else if ($f7 <= 1) {
                        if ($f6 <= 21) {
                            if ($f4 <= 23) {
                                if ($f5 <= 11) {
                                    if ($f5 <= 10) {
                                        return 4;
                                    }
                                    else if ($f6 <= 13) {
                                        if ($f6 <= 9) {
                                            return 5;
                                        }
                                        else {
                                            return 6;
                                        }
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else if ($f4 <= 16) {
                                    return 4;
                                }
                                else if ($f6 <= 13) {
                                    return 6;
                                }
                                else if ($f3 <= 15) {
                                    return 4;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else {
                                return 3;
                            }
                        }
                        else if ($f9 <= 23) {
                            if ($f6 <= 24) {
                                return 6;
                            }
                            else {
                                return 5;
                            }
                        }
                        else if ($f8 <= 3) {
                            if ($f3 <= 21) {
                                if ($f8 <= 2) {
                                    return 5;
                                }
                                else {
                                    return 6;
                                }
                            }
                            else if ($f8 <= 2) {
                                return 6;
                            }
                            else {
                                return 8;
                            }
                        }
                        else if ($f6 <= 25) {
                            return 6;
                        }
                        else {
                            return 5;
                        }
                    }
                    else if ($f4 <= 23) {
                        if ($f5 <= 8) {
                            if ($f6 <= 33) {
                                if ($f6 <= 23) {
                                    if ($f6 <= 14) {
                                        return 5;
                                    }
                                    else {
                                        return 6;
                                    }
                                }
                                else {
                                    return 5;
                                }
                            }
                            else if ($f9 <= 16) {
                                return 8;
                            }
                            else {
                                return 6;
                            }
                        }
                        else if ($f5 <= 12) {
                            if ($f6 <= 21) {
                                if ($f5 <= 10) {
                                    if ($f4 <= 6) {
                                        return 3;
                                    }
                                    else if ($f4 <= 17) {
                                        if ($f4 <= 9) {
                                            return 5;
                                        }
                                        else if ($f5 <= 9) {
                                            return 4;
                                        }
                                        else if ($f4 <= 14) {
                                            return 5;
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else if ($f4 <= 21) {
                                        if ($f1 <= 27) {
                                            return 1;
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f5 <= 11) {
                                    if ($f4 <= 13) {
                                        if ($f1 <= 27) {
                                            return 1;
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else if ($f6 <= 12) {
                                        if ($f6 <= 9) {
                                            if ($f7 <= 10) {
                                                if ($f7 <= 8) {
                                                    if ($f10 <= 0) {
                                                        return 6;
                                                    }
                                                    else {
                                                        return 5;
                                                    }
                                                }
                                                else if ($f10 <= 0) {
                                                    return 6;
                                                }
                                                else {
                                                    return 7;
                                                }
                                            }
                                            else if ($f1 <= 27) {
                                                return 0;
                                            }
                                            else {
                                                return 5;
                                            }
                                        }
                                        else {
                                            return 6;
                                        }
                                    }
                                    else if ($f10 <= 0) {
                                        return 5;
                                    }
                                    else if ($f1 <= 27) {
                                        return 1;
                                    }
                                    else if ($f3 <= 31) {
                                        return 5;
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f4 <= 16) {
                                    if ($f6 <= 15) {
                                        if ($f6 <= 4) {
                                            return 4;
                                        }
                                        else {
                                            return 5;
                                        }
                                    }
                                    else if ($f1 <= 27) {
                                        return 1;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f3 <= 15) {
                                    if ($f1 <= 27) {
                                        return 1;
                                    }
                                    else if ($f4 <= 21) {
                                        return 3;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f1 <= 27) {
                                    return 1;
                                }
                                else if ($f6 <= 12) {
                                    if ($f7 <= 10) {
                                        return 7;
                                    }
                                    else if ($f6 <= 7) {
                                        return 5;
                                    }
                                    else if ($f8 <= 29) {
                                        return 5;
                                    }
                                    else {
                                        return 6;
                                    }
                                }
                                else if ($f3 <= 31) {
                                    if ($f8 <= 32) {
                                        if ($f7 <= 9) {
                                            if ($f7 <= 6) {
                                                return 5;
                                            }
                                            else if ($f8 <= 16) {
                                                return 8;
                                            }
                                            else {
                                                return 5;
                                            }
                                        }
                                        else {
                                            return 5;
                                        }
                                    }
                                    else if ($f7 <= 7) {
                                        return 6;
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f6 <= 23) {
                                if ($f7 <= 23) {
                                    if ($f7 <= 21) {
                                        if ($f7 <= 11) {
                                            if ($f3 <= 21) {
                                                if ($f5 <= 11) {
                                                    return 6;
                                                }
                                                else if ($f7 <= 3) {
                                                    return 4;
                                                }
                                                else {
                                                    return 5;
                                                }
                                            }
                                            else if ($f3 <= 26) {
                                                if ($f8 <= 21) {
                                                    return 7;
                                                }
                                                else if ($f8 <= 23) {
                                                    return 8;
                                                }
                                                else {
                                                    return 7;
                                                }
                                            }
                                            else {
                                                return 7;
                                            }
                                        }
                                        else if ($f1 <= 27) {
                                            return 1;
                                        }
                                        else if ($f4 <= 16) {
                                            return 6;
                                        }
                                        else if ($f4 <= 20) {
                                            if ($f3 <= 21) {
                                                return 6;
                                            }
                                            else {
                                                return 7;
                                            }
                                        }
                                        else {
                                            return 6;
                                        }
                                    }
                                    else if ($f9 <= 29) {
                                        if ($f1 <= 27) {
                                            return 1;
                                        }
                                        else {
                                            return 7;
                                        }
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else {
                                    return 6;
                                }
                            }
                            else if ($f5 <= 11) {
                                if ($f5 <= 10) {
                                    if ($f5 <= 9) {
                                        if ($f6 <= 32) {
                                            return 4;
                                        }
                                        else if ($f7 <= 28) {
                                            return 6;
                                        }
                                        else {
                                            return 5;
                                        }
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else if ($f6 <= 27) {
                                    return 3;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else if ($f7 <= 20) {
                                if ($f6 <= 27) {
                                    return 3;
                                }
                                else {
                                    return 6;
                                }
                            }
                            else {
                                return 5;
                            }
                        }
                        else if ($f6 <= 21) {
                            if ($f6 <= 4) {
                                if ($f8 <= 1) {
                                    if ($f7 <= 3) {
                                        return 7;
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else if ($f1 <= 27) {
                                    return 3;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else if ($f7 <= 2) {
                                return 6;
                            }
                            else if ($f6 <= 11) {
                                if ($f6 <= 9) {
                                    return 5;
                                }
                                else if ($f7 <= 20) {
                                    return 6;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else {
                                return 5;
                            }
                        }
                        else if ($f6 <= 33) {
                            return 5;
                        }
                        else {
                            return 4;
                        }
                    }
                    else if ($f4 <= 25) {
                        return 3;
                    }
                    else if ($f1 <= 27) {
                        return 2;
                    }
                    else {
                        return 4;
                    }
                }
                else if ($f5 <= 21) {
                    if ($f7 <= 22) {
                        if ($f1 <= 27) {
                            if ($f1 <= 13) {
                                return 1;
                            }
                            else if ($f1 <= 23) {
                                return 0;
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f4 <= 31) {
                            if ($f4 <= 6) {
                                return 3;
                            }
                            else if ($f4 <= 17) {
                                if ($f5 <= 15) {
                                    if ($f6 <= 5) {
                                        return 5;
                                    }
                                    else if ($f7 <= 21) {
                                        return 6;
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else if ($f4 <= 11) {
                                    if ($f4 <= 9) {
                                        if ($f5 <= 18) {
                                            if ($f5 <= 17) {
                                                return 5;
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else if ($f4 <= 8) {
                                            return 6;
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else if ($f3 <= 31) {
                                        return 4;
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f3 <= 14) {
                                    if ($f7 <= 18) {
                                        return 3;
                                    }
                                    else if ($f4 <= 14) {
                                        return 4;
                                    }
                                    else if ($f5 <= 19) {
                                        return 5;
                                    }
                                    else {
                                        return 6;
                                    }
                                }
                                else if ($f3 <= 20) {
                                    return 4;
                                }
                                else if ($f4 <= 12) {
                                    if ($f3 <= 30) {
                                        return 1;
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else {
                                    return 4;
                                }
                            }
                            else if ($f3 <= 10) {
                                return 4;
                            }
                            else if ($f4 <= 26) {
                                if ($f3 <= 12) {
                                    if ($f4 <= 21) {
                                        return 3;
                                    }
                                    else if ($f5 <= 16) {
                                        return 6;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f5 <= 16) {
                                return 6;
                            }
                            else if ($f8 <= 3) {
                                return 5;
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f4 <= 33) {
                            return 3;
                        }
                        else {
                            return 4;
                        }
                    }
                    else if ($f10 <= 23) {
                        if ($f4 <= 8) {
                            if ($f4 <= 6) {
                                if ($f1 <= 27) {
                                    return 1;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f8 <= 3) {
                                if ($f10 <= 2) {
                                    if ($f9 <= 3) {
                                        return 9;
                                    }
                                    else {
                                        return 7;
                                    }
                                }
                                else {
                                    return 7;
                                }
                            }
                            else if ($f8 <= 28) {
                                if ($f5 <= 17) {
                                    if ($f9 <= 21) {
                                        return 8;
                                    }
                                    else if ($f9 <= 23) {
                                        if ($f10 <= 21) {
                                            return 9;
                                        }
                                        else {
                                            return 10;
                                        }
                                    }
                                    else {
                                        return 8;
                                    }
                                }
                                else if ($f6 <= 5) {
                                    return 5;
                                }
                                else {
                                    return 4;
                                }
                            }
                            else {
                                return 7;
                            }
                        }
                        else if ($f4 <= 17) {
                            if ($f1 <= 27) {
                                return 1;
                            }
                            else if ($f4 <= 11) {
                                return 4;
                            }
                            else if ($f6 <= 8) {
                                if ($f6 <= 6) {
                                    if ($f7 <= 32) {
                                        return 3;
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else {
                                    return 6;
                                }
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f1 <= 27) {
                            return 1;
                        }
                        else if ($f4 <= 31) {
                            if ($f3 <= 9) {
                                return 4;
                            }
                            else if ($f4 <= 26) {
                                if ($f3 <= 12) {
                                    if ($f4 <= 22) {
                                        return 3;
                                    }
                                    else {
                                        return 6;
                                    }
                                }
                                else {
                                    return 3;
                                }
                            }
                            else {
                                return 4;
                            }
                        }
                        else {
                            return 3;
                        }
                    }
                    else if ($f9 <= 3) {
                        if ($f9 <= 2) {
                            return 7;
                        }
                        else {
                            return 9;
                        }
                    }
                    else if ($f8 <= 4) {
                        return 7;
                    }
                    else if ($f8 <= 11) {
                        if ($f6 <= 11) {
                            return 8;
                        }
                        else {
                            return 3;
                        }
                    }
                    else if ($f5 <= 17) {
                        return 7;
                    }
                    else {
                        return 4;
                    }
                }
                else if ($f4 <= 22) {
                    if ($f4 <= 6) {
                        if ($f1 <= 27) {
                            return 1;
                        }
                        else if ($f5 <= 26) {
                            if ($f6 <= 20) {
                                if ($f6 <= 5) {
                                    if ($f4 <= 4) {
                                        return 3;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else {
                                    return 3;
                                }
                            }
                            else {
                                return 3;
                            }
                        }
                        else {
                            return 3;
                        }
                    }
                    else if ($f4 <= 17) {
                        if ($f4 <= 8) {
                            if ($f4 <= 7) {
                                if ($f5 <= 27) {
                                    if ($f1 <= 28) {
                                        return 1;
                                    }
                                    else {
                                        return 6;
                                    }
                                }
                                else {
                                    return 5;
                                }
                            }
                            else if ($f5 <= 33) {
                                return 4;
                            }
                            else {
                                return 5;
                            }
                        }
                        else if ($f5 <= 24) {
                            if ($f6 <= 19) {
                                if ($f6 <= 16) {
                                    if ($f4 <= 13) {
                                        if ($f5 <= 23) {
                                            return 5;
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else if ($f1 <= 27) {
                                        return 1;
                                    }
                                    else if ($f6 <= 6) {
                                        return 4;
                                    }
                                    else if ($f6 <= 8) {
                                        return 6;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f5 <= 23) {
                                    if ($f1 <= 27) {
                                        return 1;
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else {
                                    return 5;
                                }
                            }
                            else if ($f6 <= 22) {
                                if ($f6 <= 21) {
                                    if ($f1 <= 27) {
                                        return 1;
                                    }
                                    else if ($f4 <= 9) {
                                        return 3;
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else if ($f3 <= 2) {
                                    return 1;
                                }
                                else {
                                    return 6;
                                }
                            }
                            else if ($f4 <= 13) {
                                if ($f4 <= 10) {
                                    return 4;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f4 <= 16) {
                            if ($f6 <= 15) {
                                if ($f4 <= 11) {
                                    return 4;
                                }
                                else if ($f1 <= 27) {
                                    return 1;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else if ($f6 <= 33) {
                                if ($f1 <= 27) {
                                    return 1;
                                }
                                else if ($f6 <= 17) {
                                    return 4;
                                }
                                else if ($f4 <= 11) {
                                    if ($f5 <= 31) {
                                        return 4;
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else {
                                    return 4;
                                }
                            }
                            else if ($f4 <= 15) {
                                return 4;
                            }
                            else {
                                return 6;
                            }
                        }
                        else if ($f5 <= 30) {
                            return 4;
                        }
                        else if ($f6 <= 20) {
                            if ($f1 <= 27) {
                                return 1;
                            }
                            else {
                                return 5;
                            }
                        }
                        else if ($f7 <= 21) {
                            return 6;
                        }
                        else {
                            return 7;
                        }
                    }
                    else if ($f1 <= 27) {
                        return 1;
                    }
                    else if ($f3 <= 7) {
                        return 5;
                    }
                    else {
                        return 3;
                    }
                }
                else if ($f5 <= 30) {
                    if ($f4 <= 23) {
                        if ($f8 <= 22) {
                            if ($f5 <= 25) {
                                if ($f3 <= 17) {
                                    if ($f6 <= 20) {
                                        if ($f6 <= 17) {
                                            if ($f5 <= 23) {
                                                if ($f6 <= 4) {
                                                    return 5;
                                                }
                                                else {
                                                    return 6;
                                                }
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else {
                                            return 5;
                                        }
                                    }
                                    else if ($f5 <= 23) {
                                        return 5;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f6 <= 26) {
                                    if ($f6 <= 9) {
                                        if ($f7 <= 1) {
                                            return 6;
                                        }
                                        else {
                                            return 7;
                                        }
                                    }
                                    else if ($f6 <= 19) {
                                        return 5;
                                    }
                                    else {
                                        return 7;
                                    }
                                }
                                else {
                                    return 6;
                                }
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f9 <= 31) {
                            if ($f6 <= 17) {
                                if ($f6 <= 14) {
                                    return 4;
                                }
                                else {
                                    return 9;
                                }
                            }
                            else if ($f6 <= 19) {
                                if ($f7 <= 5) {
                                    return 6;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else {
                                return 4;
                            }
                        }
                        else {
                            return 8;
                        }
                    }
                    else if ($f4 <= 26) {
                        if ($f1 <= 27) {
                            return 1;
                        }
                        else {
                            return 3;
                        }
                    }
                    else if ($f4 <= 31) {
                        return 4;
                    }
                    else {
                        return 3;
                    }
                }
                else if ($f5 <= 32) {
                    if ($f5 <= 31) {
                        if ($f6 <= 20) {
                            if ($f3 <= 9) {
                                return 5;
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f7 <= 21) {
                            return 6;
                        }
                        else {
                            return 7;
                        }
                    }
                    else if ($f6 <= 33) {
                        if ($f6 <= 18) {
                            if ($f6 <= 14) {
                                if ($f6 <= 3) {
                                    return 5;
                                }
                                else if ($f6 <= 8) {
                                    return 3;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else {
                                return 6;
                            }
                        }
                        else {
                            return 5;
                        }
                    }
                    else {
                        return 6;
                    }
                }
                else if ($f3 <= 29) {
                    if ($f4 <= 26) {
                        if ($f6 <= 14) {
                            return 5;
                        }
                        else {
                            return 4;
                        }
                    }
                    else if ($f3 <= 12) {
                        return 5;
                    }
                    else {
                        return 3;
                    }
                }
                else if ($f6 <= 22) {
                    if ($f6 <= 16) {
                        if ($f6 <= 8) {
                            return 5;
                        }
                        else if ($f7 <= 1) {
                            return 5;
                        }
                        else if ($f7 <= 3) {
                            return 6;
                        }
                        else if ($f7 <= 15) {
                            return 5;
                        }
                        else {
                            return 6;
                        }
                    }
                    else if ($f6 <= 21) {
                        return 5;
                    }
                    else {
                        return 6;
                    }
                }
                else if ($f7 <= 21) {
                    return 6;
                }
                else if ($f7 <= 22) {
                    return 7;
                }
                else {
                    return 6;
                }
            }
            else if ($f2 <= 23) {
                if ($f6 <= 22) {
                    if ($f3 <= 5) {
                        if ($f4 <= 3) {
                            if ($f5 <= 23) {
                                if ($f5 <= 1) {
                                    if ($f4 <= 2) {
                                        return 1;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f5 <= 16) {
                                    if ($f5 <= 10) {
                                        if ($f5 <= 9) {
                                            return 2;
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else if ($f5 <= 20) {
                                    return 1;
                                }
                                else if ($f4 <= 2) {
                                    return 2;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f1 <= 9) {
                                return 1;
                            }
                            else if ($f1 <= 27) {
                                if ($f1 <= 11) {
                                    if ($f4 <= 2) {
                                        return 2;
                                    }
                                    else if ($f5 <= 31) {
                                        if ($f6 <= 9) {
                                            return 4;
                                        }
                                        else if ($f6 <= 13) {
                                            if ($f7 <= 10) {
                                                return 4;
                                            }
                                            else if ($f7 <= 20) {
                                                return 5;
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f1 <= 21) {
                                    if ($f1 <= 15) {
                                        return 1;
                                    }
                                    else {
                                        return 0;
                                    }
                                }
                                else {
                                    return 1;
                                }
                            }
                            else if ($f4 <= 2) {
                                return 2;
                            }
                            else if ($f5 <= 25) {
                                if ($f6 <= 9) {
                                    return 4;
                                }
                                else if ($f6 <= 12) {
                                    if ($f7 <= 10) {
                                        return 4;
                                    }
                                    else if ($f7 <= 20) {
                                        return 5;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else {
                                    return 4;
                                }
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f1 <= 29) {
                            if ($f1 <= 11) {
                                if ($f1 <= 9) {
                                    if ($f3 <= 1) {
                                        return 1;
                                    }
                                    else if ($f1 <= 4) {
                                        return 1;
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else if ($f4 <= 27) {
                                    if ($f4 <= 22) {
                                        if ($f4 <= 15) {
                                            if ($f3 <= 1) {
                                                return 2;
                                            }
                                            else if ($f4 <= 9) {
                                                return 4;
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else if ($f4 <= 18) {
                                            return 2;
                                        }
                                        else if ($f4 <= 20) {
                                            if ($f4 <= 19) {
                                                return 4;
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else if ($f5 <= 20) {
                                        if ($f5 <= 12) {
                                            return 2;
                                        }
                                        else if ($f4 <= 23) {
                                            return 4;
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f4 <= 27) {
                            if ($f4 <= 9) {
                                if ($f4 <= 7) {
                                    return 2;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f5 <= 20) {
                                if ($f4 <= 21) {
                                    return 2;
                                }
                                else if ($f5 <= 12) {
                                    return 2;
                                }
                                else if ($f4 <= 23) {
                                    return 4;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f5 <= 13) {
                            if ($f5 <= 11) {
                                return 3;
                            }
                            else {
                                return 2;
                            }
                        }
                        else {
                            return 3;
                        }
                    }
                    else if ($f1 <= 30) {
                        if ($f3 <= 14) {
                            if ($f1 <= 11) {
                                if ($f1 <= 5) {
                                    return 1;
                                }
                                else if ($f4 <= 21) {
                                    if ($f4 <= 1) {
                                        if ($f6 <= 11) {
                                            if ($f6 <= 1) {
                                                if ($f5 <= 3) {
                                                    return 5;
                                                }
                                                else {
                                                    return 3;
                                                }
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else if ($f3 <= 11) {
                                        if ($f3 <= 7) {
                                            if ($f1 <= 7) {
                                                if ($f4 <= 17) {
                                                    if ($f4 <= 9) {
                                                        if ($f4 <= 5) {
                                                            if ($f6 <= 20) {
                                                                return 3;
                                                            }
                                                            else {
                                                                return 5;
                                                            }
                                                        }
                                                        else {
                                                            return 4;
                                                        }
                                                    }
                                                    else {
                                                        return 1;
                                                    }
                                                }
                                                else {
                                                    return 3;
                                                }
                                            }
                                            else {
                                                return 1;
                                            }
                                        }
                                        else if ($f3 <= 10) {
                                            if ($f3 <= 8) {
                                                if ($f4 <= 14) {
                                                    return 3;
                                                }
                                                else {
                                                    return 4;
                                                }
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else if ($f4 <= 15) {
                                            if ($f4 <= 9) {
                                                return 3;
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else if ($f3 <= 12) {
                                        if ($f1 <= 9) {
                                            return 1;
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f4 <= 23) {
                                    if ($f5 <= 21) {
                                        if ($f3 <= 9) {
                                            if ($f5 <= 7) {
                                                return 5;
                                            }
                                            else {
                                                return 1;
                                            }
                                        }
                                        else if ($f3 <= 11) {
                                            return 4;
                                        }
                                        else if ($f5 <= 3) {
                                            return 2;
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else if ($f5 <= 23) {
                                        if ($f3 <= 9) {
                                            return 3;
                                        }
                                        else {
                                            return 5;
                                        }
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f3 <= 7) {
                                    if ($f4 <= 27) {
                                        return 1;
                                    }
                                    else if ($f1 <= 9) {
                                        return 4;
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else if ($f4 <= 24) {
                                    if ($f5 <= 9) {
                                        return 3;
                                    }
                                    else if ($f5 <= 25) {
                                        if ($f5 <= 12) {
                                            if ($f6 <= 10) {
                                                return 3;
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else if ($f5 <= 16) {
                                            if ($f7 <= 24) {
                                                return 3;
                                            }
                                            else if ($f7 <= 25) {
                                                return 4;
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f4 <= 27) {
                                    return 1;
                                }
                                else if ($f4 <= 33) {
                                    if ($f1 <= 9) {
                                        return 3;
                                    }
                                    else if ($f3 <= 11) {
                                        return 3;
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else {
                                    return 4;
                                }
                            }
                            else if ($f1 <= 15) {
                                return 1;
                            }
                            else if ($f1 <= 23) {
                                if ($f1 <= 19) {
                                    if ($f3 <= 9) {
                                        return 1;
                                    }
                                    else if ($f4 <= 20) {
                                        return 1;
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else {
                                    return 0;
                                }
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f4 <= 18) {
                            if ($f3 <= 23) {
                                if ($f10 <= 0) {
                                    if ($f3 <= 21) {
                                        if ($f4 <= 14) {
                                            if ($f3 <= 19) {
                                                return 1;
                                            }
                                            else if ($f1 <= 9) {
                                                if ($f1 <= 5) {
                                                    return 1;
                                                }
                                                else {
                                                    return 2;
                                                }
                                            }
                                            else {
                                                return 1;
                                            }
                                        }
                                        else if ($f4 <= 15) {
                                            if ($f1 <= 11) {
                                                if ($f1 <= 9) {
                                                    return 3;
                                                }
                                                else {
                                                    return 2;
                                                }
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else if ($f1 <= 11) {
                                        if ($f1 <= 9) {
                                            return 1;
                                        }
                                        else if ($f4 <= 7) {
                                            if ($f4 <= 1) {
                                                return 3;
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else if ($f1 <= 22) {
                                        if ($f1 <= 14) {
                                            return 1;
                                        }
                                        else {
                                            return 0;
                                        }
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else if ($f4 <= 13) {
                                    if ($f8 <= 32) {
                                        if ($f1 <= 15) {
                                            return 1;
                                        }
                                        else if ($f1 <= 22) {
                                            return 0;
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else if ($f4 <= 15) {
                                    return 2;
                                }
                                else if ($f3 <= 20) {
                                    return 1;
                                }
                                else {
                                    return 0;
                                }
                            }
                            else if ($f3 <= 32) {
                                if ($f1 <= 11) {
                                    if ($f1 <= 5) {
                                        return 1;
                                    }
                                    else if ($f4 <= 15) {
                                        if ($f3 <= 30) {
                                            if ($f3 <= 27) {
                                                return 1;
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else if ($f4 <= 8) {
                                            if ($f6 <= 2) {
                                                return 4;
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else if ($f1 <= 9) {
                                        if ($f3 <= 30) {
                                            if ($f3 <= 26) {
                                                return 1;
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else if ($f4 <= 17) {
                                        return 1;
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f1 <= 23) {
                                    if ($f1 <= 14) {
                                        return 1;
                                    }
                                    else if ($f4 <= 17) {
                                        return 1;
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else {
                                    return 1;
                                }
                            }
                            else if ($f4 <= 15) {
                                if ($f1 <= 11) {
                                    if ($f1 <= 9) {
                                        return 1;
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else {
                                    return 1;
                                }
                            }
                            else if ($f1 <= 15) {
                                if ($f1 <= 9) {
                                    if ($f5 <= 9) {
                                        return 2;
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f1 <= 21) {
                                return 2;
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f4 <= 19) {
                            if ($f3 <= 21) {
                                if ($f3 <= 17) {
                                    return 2;
                                }
                                else {
                                    return 1;
                                }
                            }
                            else if ($f3 <= 22) {
                                if ($f10 <= 0) {
                                    if ($f1 <= 5) {
                                        return 1;
                                    }
                                    else if ($f1 <= 15) {
                                        return 4;
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else if ($f1 <= 15) {
                                    if ($f1 <= 4) {
                                        return 1;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else {
                                    return 1;
                                }
                            }
                            else if ($f3 <= 25) {
                                return 1;
                            }
                            else if ($f3 <= 28) {
                                return 2;
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f3 <= 27) {
                            if ($f4 <= 30) {
                                if ($f1 <= 5) {
                                    if ($f1 <= 2) {
                                        return 1;
                                    }
                                    else {
                                        return 0;
                                    }
                                }
                                else {
                                    return 1;
                                }
                            }
                            else if ($f4 <= 31) {
                                return 4;
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f3 <= 31) {
                            if ($f3 <= 30) {
                                return 2;
                            }
                            else if ($f5 <= 21) {
                                return 4;
                            }
                            else {
                                return 5;
                            }
                        }
                        else if ($f4 <= 21) {
                            return 2;
                        }
                        else if ($f3 <= 34) {
                            return 1;
                        }
                        else {
                            return 0;
                        }
                    }
                    else if ($f1 <= 31) {
                        if ($f3 <= 29) {
                            if ($f3 <= 21) {
                                if ($f4 <= 21) {
                                    if ($f3 <= 11) {
                                        if ($f4 <= 11) {
                                            if ($f4 <= 9) {
                                                return 3;
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else if ($f5 <= 10) {
                                        if ($f5 <= 6) {
                                            return 3;
                                        }
                                        else if ($f4 <= 13) {
                                            return 5;
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f4 <= 23) {
                                    if ($f5 <= 21) {
                                        if ($f5 <= 11) {
                                            if ($f3 <= 11) {
                                                return 4;
                                            }
                                            else if ($f5 <= 5) {
                                                return 4;
                                            }
                                            else {
                                                return 5;
                                            }
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else if ($f5 <= 23) {
                                        if ($f4 <= 22) {
                                            return 3;
                                        }
                                        else {
                                            return 5;
                                        }
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f3 <= 11) {
                                    return 3;
                                }
                                else if ($f5 <= 20) {
                                    if ($f4 <= 27) {
                                        return 3;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f3 <= 23) {
                                if ($f4 <= 11) {
                                    return 3;
                                }
                                else if ($f4 <= 12) {
                                    return 4;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f4 <= 25) {
                                if ($f4 <= 17) {
                                    if ($f5 <= 2) {
                                        return 4;
                                    }
                                    else if ($f5 <= 8) {
                                        if ($f5 <= 6) {
                                            return 3;
                                        }
                                        else {
                                            return 5;
                                        }
                                    }
                                    else if ($f5 <= 32) {
                                        return 4;
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else if ($f4 <= 19) {
                                    return 3;
                                }
                                else if ($f5 <= 9) {
                                    return 5;
                                }
                                else {
                                    return 4;
                                }
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f3 <= 32) {
                            if ($f4 <= 14) {
                                return 3;
                            }
                            else if ($f4 <= 28) {
                                if ($f5 <= 9) {
                                    if ($f4 <= 23) {
                                        return 5;
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f5 <= 26) {
                                    if ($f5 <= 12) {
                                        if ($f6 <= 10) {
                                            return 3;
                                        }
                                        else {
                                            return 4;
                                        }
                                    }
                                    else {
                                        return 3;
                                    }
                                }
                                else if ($f4 <= 20) {
                                    return 5;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f4 <= 33) {
                                return 3;
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f4 <= 21) {
                            if ($f4 <= 17) {
                                if ($f4 <= 8) {
                                    return 3;
                                }
                                else if ($f5 <= 20) {
                                    if ($f5 <= 1) {
                                        if ($f4 <= 13) {
                                            return 4;
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else if ($f5 <= 3) {
                                        return 4;
                                    }
                                    else if ($f5 <= 16) {
                                        return 3;
                                    }
                                    else if ($f5 <= 19) {
                                        if ($f5 <= 17) {
                                            return 4;
                                        }
                                        else if ($f4 <= 11) {
                                            return 4;
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f4 <= 20) {
                                if ($f4 <= 18) {
                                    return 3;
                                }
                                else {
                                    return 4;
                                }
                            }
                            else {
                                return 3;
                            }
                        }
                        else if ($f5 <= 21) {
                            if ($f4 <= 23) {
                                return 4;
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f5 <= 23) {
                            return 5;
                        }
                        else {
                            return 4;
                        }
                    }
                    else if ($f3 <= 22) {
                        if ($f4 <= 11) {
                            return 1;
                        }
                        else if ($f4 <= 12) {
                            return 4;
                        }
                        else {
                            return 1;
                        }
                    }
                    else {
                        return 1;
                    }
                }
                else if ($f5 <= 11) {
                    if ($f5 <= 10) {
                        if ($f4 <= 1) {
                            if ($f8 <= 22) {
                                if ($f3 <= 15) {
                                    return 3;
                                }
                                else if ($f1 <= 27) {
                                    return 1;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f8 <= 23) {
                                return 5;
                            }
                            else {
                                return 3;
                            }
                        }
                        else if ($f1 <= 27) {
                            if ($f3 <= 32) {
                                if ($f1 <= 11) {
                                    if ($f1 <= 5) {
                                        return 1;
                                    }
                                    else if ($f3 <= 7) {
                                        if ($f1 <= 9) {
                                            if ($f3 <= 6) {
                                                return 1;
                                            }
                                            else {
                                                return 4;
                                            }
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else if ($f3 <= 14) {
                                        if ($f1 <= 9) {
                                            if ($f4 <= 22) {
                                                return 3;
                                            }
                                            else if ($f7 <= 2) {
                                                return 4;
                                            }
                                            else {
                                                return 3;
                                            }
                                        }
                                        else if ($f3 <= 11) {
                                            return 3;
                                        }
                                        else {
                                            return 2;
                                        }
                                    }
                                    else if ($f4 <= 17) {
                                        return 1;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else {
                                    return 1;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f3 <= 9) {
                            if ($f6 <= 23) {
                                return 4;
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f1 <= 31) {
                            if ($f3 <= 11) {
                                return 3;
                            }
                            else if ($f3 <= 28) {
                                if ($f3 <= 12) {
                                    return 3;
                                }
                                else {
                                    return 4;
                                }
                            }
                            else if ($f3 <= 32) {
                                return 3;
                            }
                            else if ($f5 <= 3) {
                                if ($f5 <= 1) {
                                    return 3;
                                }
                                else {
                                    return 4;
                                }
                            }
                            else {
                                return 3;
                            }
                        }
                        else {
                            return 4;
                        }
                    }
                    else if ($f8 <= 3) {
                        if ($f9 <= 23) {
                            if ($f10 <= 0) {
                                if ($f3 <= 4) {
                                    return 2;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f7 <= 2) {
                                if ($f9 <= 5) {
                                    return 8;
                                }
                                else {
                                    return 6;
                                }
                            }
                            else if ($f7 <= 11) {
                                return 7;
                            }
                            else if ($f7 <= 12) {
                                return 6;
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f7 <= 6) {
                            if ($f10 <= 9) {
                                return 8;
                            }
                            else if ($f10 <= 11) {
                                return 9;
                            }
                            else if ($f10 <= 27) {
                                if ($f9 <= 28) {
                                    if ($f8 <= 2) {
                                        return 6;
                                    }
                                    else {
                                        return 8;
                                    }
                                }
                                else {
                                    return 8;
                                }
                            }
                            else {
                                return 8;
                            }
                        }
                        else {
                            return 7;
                        }
                    }
                    else if ($f7 <= 1) {
                        if ($f9 <= 0) {
                            return 2;
                        }
                        else if ($f8 <= 27) {
                            if ($f8 <= 22) {
                                if ($f3 <= 4) {
                                    return 2;
                                }
                                else if ($f6 <= 24) {
                                    return 6;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f9 <= 20) {
                                if ($f9 <= 12) {
                                    return 6;
                                }
                                else {
                                    return 8;
                                }
                            }
                            else {
                                return 6;
                            }
                        }
                        else {
                            return 6;
                        }
                    }
                    else if ($f7 <= 32) {
                        if ($f3 <= 4) {
                            return 2;
                        }
                        else if ($f7 <= 11) {
                            if ($f6 <= 24) {
                                if ($f8 <= 21) {
                                    if ($f8 <= 10) {
                                        if ($f8 <= 9) {
                                            if ($f9 <= 10) {
                                                if ($f9 <= 9) {
                                                    return 7;
                                                }
                                                else {
                                                    return 9;
                                                }
                                            }
                                            else {
                                                return 7;
                                            }
                                        }
                                        else {
                                            return 8;
                                        }
                                    }
                                    else if ($f4 <= 17) {
                                        return 7;
                                    }
                                    else {
                                        return 4;
                                    }
                                }
                                else if ($f8 <= 23) {
                                    if ($f9 <= 21) {
                                        return 8;
                                    }
                                    else if ($f9 <= 23) {
                                        return 9;
                                    }
                                    else {
                                        return 8;
                                    }
                                }
                                else {
                                    return 7;
                                }
                            }
                            else {
                                return 3;
                            }
                        }
                        else if ($f7 <= 12) {
                            if ($f8 <= 21) {
                                return 6;
                            }
                            else {
                                return 8;
                            }
                        }
                        else if ($f4 <= 17) {
                            if ($f4 <= 16) {
                                return 3;
                            }
                            else {
                                return 8;
                            }
                        }
                        else if ($f8 <= 18) {
                            return 1;
                        }
                        else {
                            return 4;
                        }
                    }
                    else if ($f8 <= 21) {
                        if ($f8 <= 14) {
                            return 7;
                        }
                        else {
                            return 6;
                        }
                    }
                    else {
                        return 1;
                    }
                }
                else if ($f3 <= 3) {
                    if ($f4 <= 3) {
                        if ($f6 <= 27) {
                            if ($f1 <= 9) {
                                return 1;
                            }
                            else if ($f5 <= 22) {
                                return 2;
                            }
                            else if ($f1 <= 11) {
                                return 4;
                            }
                            else if ($f1 <= 27) {
                                return 1;
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f4 <= 2) {
                            return 2;
                        }
                        else if ($f1 <= 9) {
                            return 1;
                        }
                        else {
                            return 4;
                        }
                    }
                    else if ($f1 <= 27) {
                        if ($f1 <= 11) {
                            if ($f1 <= 9) {
                                return 1;
                            }
                            else if ($f4 <= 27) {
                                if ($f6 <= 33) {
                                    if ($f9 <= 22) {
                                        return 2;
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else {
                                    return 4;
                                }
                            }
                            else {
                                return 3;
                            }
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f4 <= 27) {
                        if ($f4 <= 9) {
                            if ($f4 <= 7) {
                                return 2;
                            }
                            else {
                                return 3;
                            }
                        }
                        else if ($f6 <= 33) {
                            return 2;
                        }
                        else {
                            return 4;
                        }
                    }
                    else {
                        return 3;
                    }
                }
                else if ($f3 <= 14) {
                    if ($f4 <= 22) {
                        if ($f4 <= 1) {
                            if ($f3 <= 7) {
                                return 1;
                            }
                            else {
                                return 3;
                            }
                        }
                        else if ($f3 <= 7) {
                            if ($f1 <= 7) {
                                if ($f4 <= 17) {
                                    return 1;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f4 <= 16) {
                                return 1;
                            }
                            else {
                                return 0;
                            }
                        }
                        else if ($f3 <= 11) {
                            if ($f5 <= 21) {
                                if ($f1 <= 4) {
                                    return 1;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else {
                                return 3;
                            }
                        }
                        else if ($f1 <= 27) {
                            if ($f3 <= 12) {
                                if ($f5 <= 20) {
                                    return 1;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 3;
                            }
                        }
                        else {
                            return 3;
                        }
                    }
                    else if ($f4 <= 23) {
                        if ($f6 <= 24) {
                            if ($f5 <= 21) {
                                return 4;
                            }
                            else {
                                return 5;
                            }
                        }
                        else {
                            return 4;
                        }
                    }
                    else if ($f1 <= 9) {
                        if ($f5 <= 27) {
                            if ($f4 <= 32) {
                                if ($f1 <= 5) {
                                    return 1;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else {
                                return 3;
                            }
                        }
                        else {
                            return 3;
                        }
                    }
                    else if ($f4 <= 27) {
                        return 1;
                    }
                    else {
                        return 3;
                    }
                }
                else if ($f3 <= 23) {
                    if ($f4 <= 11) {
                        return 1;
                    }
                    else if ($f3 <= 21) {
                        return 1;
                    }
                    else if ($f4 <= 20) {
                        if ($f3 <= 22) {
                            if ($f9 <= 0) {
                                return 4;
                            }
                            else if ($f1 <= 4) {
                                return 1;
                            }
                            else if ($f1 <= 27) {
                                if ($f4 <= 16) {
                                    return 1;
                                }
                                else {
                                    return 4;
                                }
                            }
                            else {
                                return 4;
                            }
                        }
                        else {
                            return 2;
                        }
                    }
                    else {
                        return 1;
                    }
                }
                else if ($f1 <= 27) {
                    if ($f3 <= 26) {
                        if ($f1 <= 9) {
                            return 1;
                        }
                        else if ($f1 <= 11) {
                            return 3;
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f3 <= 32) {
                        if ($f3 <= 30) {
                            return 2;
                        }
                        else {
                            return 3;
                        }
                    }
                    else {
                        return 2;
                    }
                }
                else if ($f4 <= 4) {
                    return 3;
                }
                else if ($f3 <= 32) {
                    return 3;
                }
                else if ($f4 <= 22) {
                    if ($f4 <= 17) {
                        if ($f7 <= 13) {
                            return 3;
                        }
                        else {
                            return 4;
                        }
                    }
                    else {
                        return 3;
                    }
                }
                else {
                    return 4;
                }
            }
            else if ($f1 <= 11) {
                if ($f1 <= 10) {
                    if ($f2 <= 26) {
                        if ($f1 <= 6) {
                            if ($f1 <= 2) {
                                if ($f2 <= 25) {
                                    if ($f2 <= 24) {
                                        return 0;
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f1 <= 3) {
                                if ($f3 <= 17) {
                                    return 1;
                                }
                                else if ($f3 <= 18) {
                                    return 2;
                                }
                                else {
                                    return 1;
                                }
                            }
                            else if ($f1 <= 5) {
                                return 1;
                            }
                            else {
                                return 0;
                            }
                        }
                        else if ($f1 <= 8) {
                            if ($f3 <= 9) {
                                return 1;
                            }
                            else if ($f3 <= 25) {
                                if ($f1 <= 7) {
                                    return 1;
                                }
                                else if ($f3 <= 12) {
                                    if ($f9 <= 0) {
                                        return 1;
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else {
                                    return 1;
                                }
                            }
                            else {
                                return 1;
                            }
                        }
                        else {
                            return 0;
                        }
                    }
                    else if ($f1 <= 2) {
                        if ($f10 <= 0) {
                            if ($f1 <= 1) {
                                return 1;
                            }
                            else {
                                return 0;
                            }
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f10 <= 0) {
                        if ($f3 <= 8) {
                            if ($f3 <= 6) {
                                return 1;
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f3 <= 18) {
                            if ($f1 <= 6) {
                                if ($f1 <= 5) {
                                    if ($f3 <= 14) {
                                        return 2;
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else {
                                    return 0;
                                }
                            }
                            else if ($f1 <= 8) {
                                return 1;
                            }
                            else {
                                return 0;
                            }
                        }
                        else if ($f2 <= 28) {
                            if ($f3 <= 20) {
                                return 2;
                            }
                            else if ($f2 <= 27) {
                                return 3;
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f1 <= 8) {
                            if ($f1 <= 6) {
                                if ($f1 <= 3) {
                                    return 1;
                                }
                                else {
                                    return 0;
                                }
                            }
                            else {
                                return 1;
                            }
                        }
                        else {
                            return 0;
                        }
                    }
                    else if ($f7 <= 0) {
                        return 8;
                    }
                    else if ($f3 <= 18) {
                        return 1;
                    }
                    else if ($f3 <= 19) {
                        return 2;
                    }
                    else {
                        return 1;
                    }
                }
                else if ($f4 <= 1) {
                    if ($f5 <= 3) {
                        if ($f6 <= 22) {
                            if ($f6 <= 2) {
                                if ($f5 <= 2) {
                                    return 3;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else if ($f6 <= 20) {
                                return 3;
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f3 <= 12) {
                            if ($f5 <= 2) {
                                return 3;
                            }
                            else if ($f7 <= 27) {
                                if ($f7 <= 9) {
                                    return 5;
                                }
                                else if ($f6 <= 28) {
                                    if ($f7 <= 12) {
                                        if ($f8 <= 10) {
                                            return 5;
                                        }
                                        else if ($f8 <= 20) {
                                            return 6;
                                        }
                                        else {
                                            return 5;
                                        }
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else {
                                    return 5;
                                }
                            }
                            else {
                                return 5;
                            }
                        }
                        else {
                            return 3;
                        }
                    }
                    else if ($f3 <= 3) {
                        if ($f5 <= 27) {
                            if ($f5 <= 21) {
                                return 3;
                            }
                            else if ($f6 <= 20) {
                                if ($f6 <= 12) {
                                    return 3;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else {
                                return 3;
                            }
                        }
                        else {
                            return 3;
                        }
                    }
                    else if ($f3 <= 29) {
                        return 1;
                    }
                    else {
                        return 3;
                    }
                }
                else if ($f3 <= 31) {
                    if ($f3 <= 3) {
                        if ($f4 <= 11) {
                            if ($f5 <= 21) {
                                if ($f3 <= 2) {
                                    return 3;
                                }
                                else if ($f9 <= 0) {
                                    return 4;
                                }
                                else if ($f5 <= 13) {
                                    if ($f5 <= 9) {
                                        return 4;
                                    }
                                    else {
                                        return 5;
                                    }
                                }
                                else {
                                    return 4;
                                }
                            }
                            else if ($f5 <= 23) {
                                if ($f6 <= 21) {
                                    return 5;
                                }
                                else if ($f6 <= 23) {
                                    return 6;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else if ($f3 <= 2) {
                                return 3;
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f2 <= 28) {
                            if ($f4 <= 27) {
                                return 1;
                            }
                            else {
                                return 3;
                            }
                        }
                        else if ($f5 <= 16) {
                            if ($f5 <= 15) {
                                if ($f4 <= 12) {
                                    return 3;
                                }
                                else if ($f3 <= 2) {
                                    return 2;
                                }
                                else {
                                    return 4;
                                }
                            }
                            else {
                                return 3;
                            }
                        }
                        else if ($f4 <= 28) {
                            if ($f4 <= 12) {
                                if ($f5 <= 22) {
                                    return 3;
                                }
                                else if ($f6 <= 3) {
                                    return 3;
                                }
                                else if ($f6 <= 21) {
                                    return 5;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f5 <= 22) {
                                return 4;
                            }
                            else {
                                return 3;
                            }
                        }
                        else if ($f5 <= 22) {
                            return 3;
                        }
                        else {
                            return 5;
                        }
                    }
                    else if ($f2 <= 27) {
                        if ($f2 <= 25) {
                            return 1;
                        }
                        else if ($f2 <= 26) {
                            return 2;
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f3 <= 20) {
                        if ($f4 <= 13) {
                            if ($f4 <= 11) {
                                if ($f6 <= 22) {
                                    return 2;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f2 <= 28) {
                                return 1;
                            }
                            else {
                                return 4;
                            }
                        }
                        else if ($f3 <= 10) {
                            return 2;
                        }
                        else if ($f4 <= 15) {
                            return 2;
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f2 <= 28) {
                        if ($f3 <= 23) {
                            return 1;
                        }
                        else {
                            return 4;
                        }
                    }
                    else if ($f3 <= 22) {
                        if ($f5 <= 16) {
                            return 2;
                        }
                        else {
                            return 4;
                        }
                    }
                    else if ($f3 <= 25) {
                        return 1;
                    }
                    else {
                        return 2;
                    }
                }
                else if ($f3 <= 32) {
                    if ($f2 <= 27) {
                        return 2;
                    }
                    else if ($f4 <= 28) {
                        if ($f5 <= 9) {
                            return 3;
                        }
                        else if ($f5 <= 25) {
                            if ($f5 <= 10) {
                                return 4;
                            }
                            else {
                                return 3;
                            }
                        }
                        else {
                            return 3;
                        }
                    }
                    else {
                        return 3;
                    }
                }
                else if ($f4 <= 21) {
                    if ($f4 <= 17) {
                        if ($f4 <= 6) {
                            return 3;
                        }
                        else {
                            return 4;
                        }
                    }
                    else {
                        return 3;
                    }
                }
                else if ($f5 <= 21) {
                    return 4;
                }
                else {
                    return 5;
                }
            }
            else if ($f2 <= 24) {
                if ($f1 <= 31) {
                    if ($f1 <= 14) {
                        if ($f3 <= 27) {
                            if ($f3 <= 9) {
                                return 1;
                            }
                            else if ($f3 <= 12) {
                                if ($f9 <= 0) {
                                    return 1;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 1;
                            }
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f1 <= 23) {
                        return 0;
                    }
                    else if ($f1 <= 26) {
                        return 1;
                    }
                    else {
                        return 0;
                    }
                }
                else if ($f10 <= 0) {
                    if ($f3 <= 19) {
                        if ($f3 <= 17) {
                            if ($f3 <= 3) {
                                if ($f3 <= 2) {
                                    return 1;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f3 <= 10) {
                                return 1;
                            }
                            else if ($f4 <= 30) {
                                if ($f3 <= 15) {
                                    if ($f3 <= 13) {
                                        return 2;
                                    }
                                    else {
                                        return 1;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else {
                            return 2;
                        }
                    }
                    else if ($f3 <= 20) {
                        return 1;
                    }
                    else if ($f3 <= 24) {
                        return 2;
                    }
                    else if ($f4 <= 31) {
                        return 1;
                    }
                    else {
                        return 2;
                    }
                }
                else if ($f3 <= 10) {
                    if ($f4 <= 6) {
                        if ($f3 <= 7) {
                            return 2;
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f5 <= 18) {
                        return 2;
                    }
                    else if ($f4 <= 20) {
                        return 2;
                    }
                    else {
                        return 1;
                    }
                }
                else {
                    return 2;
                }
            }
            else if ($f2 <= 32) {
                if ($f2 <= 27) {
                    if ($f1 <= 19) {
                        if ($f3 <= 12) {
                            if ($f3 <= 10) {
                                if ($f2 <= 25) {
                                    return 1;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f4 <= 19) {
                                if ($f3 <= 11) {
                                    if ($f1 <= 16) {
                                        if ($f7 <= 0) {
                                            if ($f8 <= 6) {
                                                return 1;
                                            }
                                            else {
                                                return 9;
                                            }
                                        }
                                        else if ($f4 <= 13) {
                                            if ($f4 <= 9) {
                                                return 1;
                                            }
                                            else {
                                                return 2;
                                            }
                                        }
                                        else {
                                            return 1;
                                        }
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else if ($f10 <= 0) {
                                if ($f1 <= 14) {
                                    return 1;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f2 <= 25) {
                            return 1;
                        }
                        else if ($f3 <= 14) {
                            return 2;
                        }
                        else if ($f3 <= 30) {
                            if ($f3 <= 27) {
                                if ($f6 <= 0) {
                                    if ($f9 <= 1) {
                                        return 2;
                                    }
                                    else {
                                        return 8;
                                    }
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else {
                            return 2;
                        }
                    }
                    else if ($f3 <= 10) {
                        if ($f1 <= 31) {
                            if ($f1 <= 30) {
                                if ($f1 <= 23) {
                                    return 0;
                                }
                                else {
                                    return 1;
                                }
                            }
                            else {
                                return 3;
                            }
                        }
                        else if ($f4 <= 14) {
                            return 2;
                        }
                        else if ($f3 <= 6) {
                            if ($f3 <= 1) {
                                return 1;
                            }
                            else {
                                return 2;
                            }
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f3 <= 12) {
                        if ($f2 <= 26) {
                            if ($f2 <= 25) {
                                return 1;
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f10 <= 30) {
                            return 1;
                        }
                        else if ($f7 <= 0) {
                            return 8;
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f1 <= 31) {
                        if ($f2 <= 25) {
                            return 1;
                        }
                        else if ($f2 <= 26) {
                            return 2;
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f3 <= 30) {
                        if ($f3 <= 14) {
                            return 3;
                        }
                        else if ($f6 <= 34) {
                            return 2;
                        }
                        else {
                            return 7;
                        }
                    }
                    else if ($f4 <= 17) {
                        return 3;
                    }
                    else {
                        return 1;
                    }
                }
                else if ($f1 <= 23) {
                    if ($f1 <= 14) {
                        return 1;
                    }
                    else {
                        return 0;
                    }
                }
                else if ($f3 <= 16) {
                    if ($f10 <= 0) {
                        if ($f1 <= 24) {
                            if ($f3 <= 8) {
                                if ($f3 <= 6) {
                                    return 1;
                                }
                                else {
                                    return 2;
                                }
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f1 <= 31) {
                            return 0;
                        }
                        else {
                            return 1;
                        }
                    }
                    else {
                        return 1;
                    }
                }
                else if ($f3 <= 20) {
                    if ($f4 <= 13) {
                        return 1;
                    }
                    else {
                        return 2;
                    }
                }
                else if ($f3 <= 30) {
                    if ($f3 <= 21) {
                        return 1;
                    }
                    else if ($f3 <= 25) {
                        return 1;
                    }
                    else {
                        return 2;
                    }
                }
                else {
                    return 1;
                }
            }
            else if ($f1 <= 16) {
                if ($f1 <= 14) {
                    return 1;
                }
                else {
                    return 0;
                }
            }
            else if ($f1 <= 26) {
                if ($f3 <= 17) {
                    if ($f3 <= 13) {
                        if ($f3 <= 11) {
                            if ($f1 <= 17) {
                                return 2;
                            }
                            else if ($f1 <= 24) {
                                return 0;
                            }
                            else {
                                return 2;
                            }
                        }
                        else {
                            return 2;
                        }
                    }
                    else if ($f9 <= 1) {
                        if ($f1 <= 17) {
                            return 2;
                        }
                        else if ($f1 <= 24) {
                            return 0;
                        }
                        else {
                            return 2;
                        }
                    }
                    else {
                        return 2;
                    }
                }
                else if ($f4 <= 30) {
                    if ($f1 <= 17) {
                        if ($f4 <= 19) {
                            if ($f4 <= 10) {
                                if ($f4 <= 6) {
                                    if ($f4 <= 1) {
                                        return 2;
                                    }
                                    else if ($f3 <= 30) {
                                        if ($f5 <= 11) {
                                            return 3;
                                        }
                                        else if ($f5 <= 17) {
                                            return 2;
                                        }
                                        else {
                                            return 3;
                                        }
                                    }
                                    else {
                                        return 2;
                                    }
                                }
                                else if ($f4 <= 8) {
                                    return 1;
                                }
                                else {
                                    return 3;
                                }
                            }
                            else if ($f4 <= 14) {
                                if ($f4 <= 13) {
                                    return 2;
                                }
                                else {
                                    return 4;
                                }
                            }
                            else if ($f3 <= 20) {
                                return 2;
                            }
                            else if ($f5 <= 10) {
                                return 3;
                            }
                            else if ($f5 <= 11) {
                                return 2;
                            }
                            else {
                                return 3;
                            }
                        }
                        else if ($f3 <= 20) {
                            return 3;
                        }
                        else if ($f3 <= 26) {
                            if ($f4 <= 22) {
                                if ($f5 <= 16) {
                                    return 2;
                                }
                                else {
                                    return 5;
                                }
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f3 <= 27) {
                            return 3;
                        }
                        else {
                            return 2;
                        }
                    }
                    else if ($f4 <= 14) {
                        if ($f1 <= 24) {
                            if ($f1 <= 22) {
                                return 0;
                            }
                            else {
                                return 1;
                            }
                        }
                        else if ($f4 <= 10) {
                            if ($f4 <= 1) {
                                return 2;
                            }
                            else if ($f5 <= 11) {
                                return 3;
                            }
                            else if ($f5 <= 17) {
                                return 2;
                            }
                            else if ($f4 <= 6) {
                                return 3;
                            }
                            else {
                                return 2;
                            }
                        }
                        else if ($f4 <= 13) {
                            return 2;
                        }
                        else {
                            return 4;
                        }
                    }
                    else if ($f1 <= 24) {
                        if ($f1 <= 22) {
                            return 0;
                        }
                        else {
                            return 1;
                        }
                    }
                    else if ($f3 <= 18) {
                        return 3;
                    }
                    else if ($f3 <= 26) {
                        if ($f5 <= 18) {
                            return 2;
                        }
                        else if ($f5 <= 19) {
                            return 5;
                        }
                        else {
                            return 2;
                        }
                    }
                    else {
                        return 3;
                    }
                }
                else if ($f4 <= 31) {
                    return 4;
                }
                else {
                    return 2;
                }
            }
            else if ($f1 <= 32) {
                if ($f1 <= 31) {
                    return 0;
                }
                else {
                    return 1;
                }
            }
            else if ($f2 <= 33) {
                return 2;
            }
            else {
                return 1;
            }
        }
    }
    public static function word_to_vec($word) {
        if(empty($word) || !is_string($word)){
            return $word;
        }

        $word = array_reverse(mb_str_split($word));
//        "Translate Ukrainian alphabet into integers";
        $vec = array();
        foreach ($word as $letter) {
            if(!empty($letter) && isset(self::$alphabet_index[$letter])){
                $vec[] = self::$alphabet_index[$letter];
            }
        }

        return $vec;
    }
    public static function stem_word($word, $deaccent = false) {
    //    Stem one word;
        $vec = self::word_to_vec($word);
        $f1 = isset($vec[0]) ? $vec[0]: 0;
        $f2 = isset($vec[1]) ? $vec[1]: 0;
        $f3 = isset($vec[2]) ? $vec[2]: 0;
        $f4 = isset($vec[3]) ? $vec[3]: 0;
        $f5 = isset($vec[4]) ? $vec[4]: 0;
        $f6 = isset($vec[5]) ? $vec[5]: 0;
        $f7 = isset($vec[6]) ? $vec[6]: 0;
        $f8 = isset($vec[7]) ? $vec[7]: 0;
        $f9 = isset($vec[8]) ? $vec[8]: 0;
        $f10 = isset($vec[9]) ? $vec[9]: 0;

        $cut = self::decision_tree($f1, $f2, $f3, $f4, $f5, $f6, $f7, $f8, $f9, $f10);
        if(empty($cut) || empty($word)) {
            return $word;
        }
        if ($cut >= mb_strlen($word)) {
            return $word;
        }

        return implode('', array_slice(mb_str_split($word), 0, -$cut));
    }

    public static function Stem($word){
        if(empty($word)){
            return $word;
        }

        $lowercased = mb_strtolower($word, 'utf-8');


        if(empty($lowercased)){
            return $word;
        }

        // check the cache to see if we've already stemmed the word
        $cached = self::get_cached_stem($lowercased);
        if(!empty($cached)){
            return $cached;
        }else{
            $stemmed_word = self::stem_word($lowercased);

            self::update_cached_stem($lowercased, $stemmed_word);

            return $stemmed_word;
        }
    }

    /**
     * Checks to see if the word was previously stemmed and is in the stem cache.
     * If it is in the cache, it returns the cached word so we don't have to run through the process again.
     * Returns false if the word hasn't been stemmed yet, or the "word" isn't a word
     **/
    public static function get_cached_stem($word = ''){
        if(empty($word) || !isset(self::$stem_cache[$word]) || !is_string($word)){
            return false;
        }

        return self::$stem_cache[$word];
    }

    /**
     * Updates the stemmed word cache when we come across a word that we haven't stemmed yet.
     * Also does some housekeeping to make sure the cache doesn't grow too big
     **/
    public static function update_cached_stem($word, $stemmed_word){
        if(empty($word) || empty($stemmed_word) || isset(self::$stem_cache[$word]) || !is_string($word)){
            return false;
        }

        self::$stem_cache[$word] = $stemmed_word;

        if(count(self::$stem_cache) > 25000){
            $ind = key(self::$stem_cache);
            unset(self::$stem_cache[$ind]);
        }
    }
}