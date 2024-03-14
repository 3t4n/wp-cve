<?php

/**
 * WC_QuickPay_API_Transaction class
 *
 * Used for common methods shared between payments and subscriptions
 *
 * @class        WC_QuickPay_Countries
 * @since        4.6.0
 * @package        Woocommerce_QuickPay/Classes
 * @category    Class
 * @author        Thanks to Sameer Shelavale
 */

class WC_QuickPay_Countries {
	public static $countries = [
		"AF" => [ 'alpha2' => 'AF', 'alpha3' => 'AFG', 'num' => '004', 'isd' => '93', "name" => "Afghanistan", "continent" => "Asia", ],
		"AX" => [ 'alpha2' => 'AX', 'alpha3' => 'ALA', 'num' => '248', 'isd' => '358', "name" => "Åland Islands", "continent" => "Europe" ],
		"AL" => [ 'alpha2' => 'AL', 'alpha3' => 'ALB', 'num' => '008', 'isd' => '355', "name" => "Albania", "continent" => "Europe" ],
		"DZ" => [ 'alpha2' => 'DZ', 'alpha3' => 'DZA', 'num' => '012', 'isd' => '213', "name" => "Algeria", "continent" => "Africa" ],
		"AS" => [ 'alpha2' => 'AS', 'alpha3' => 'ASM', 'num' => '016', 'isd' => '1684', "name" => "American Samoa", "continent" => "Oceania" ],
		"AD" => [ 'alpha2' => 'AD', 'alpha3' => 'AND', 'num' => '020', 'isd' => '376', "name" => "Andorra", "continent" => "Europe" ],
		"AO" => [ 'alpha2' => 'AO', 'alpha3' => 'AGO', 'num' => '024', 'isd' => '244', "name" => "Angola", "continent" => "Africa" ],
		"AI" => [ 'alpha2' => 'AI', 'alpha3' => 'AIA', 'num' => '660', 'isd' => '1264', "name" => "Anguilla", "continent" => "North America" ],
		"AQ" => [ 'alpha2' => 'AQ', 'alpha3' => 'ATA', 'num' => '010', 'isd' => '672', "name" => "Antarctica", "continent" => "Antarctica" ],
		"AG" => [ 'alpha2' => 'AG', 'alpha3' => 'ATG', 'num' => '028', 'isd' => '1268', "name" => "Antigua and Barbuda", "continent" => "North America" ],
		"AR" => [ 'alpha2' => 'AR', 'alpha3' => 'ARG', 'num' => '032', 'isd' => '54', "name" => "Argentina", "continent" => "South America" ],
		"AM" => [ 'alpha2' => 'AM', 'alpha3' => 'ARM', 'num' => '051', 'isd' => '374', "name" => "Armenia", "continent" => "Asia" ],
		"AW" => [ 'alpha2' => 'AW', 'alpha3' => 'ABW', 'num' => '533', 'isd' => '297', "name" => "Aruba", "continent" => "North America" ],
		"AU" => [ 'alpha2' => 'AU', 'alpha3' => 'AUS', 'num' => '036', 'isd' => '61', "name" => "Australia", "continent" => "Oceania" ],
		"AT" => [ 'alpha2' => 'AT', 'alpha3' => 'AUT', 'num' => '040', 'isd' => '43', "name" => "Austria", "continent" => "Europe" ],
		"AZ" => [ 'alpha2' => 'AZ', 'alpha3' => 'AZE', 'num' => '031', 'isd' => '994', "name" => "Azerbaijan", "continent" => "Asia" ],
		"BS" => [ 'alpha2' => 'BS', 'alpha3' => 'BHS', 'num' => '044', 'isd' => '1242', "name" => "Bahamas", "continent" => "North America" ],
		"BH" => [ 'alpha2' => 'BH', 'alpha3' => 'BHR', 'num' => '048', 'isd' => '973', "name" => "Bahrain", "continent" => "Asia" ],
		"BD" => [ 'alpha2' => 'BD', 'alpha3' => 'BGD', 'num' => '050', 'isd' => '880', "name" => "Bangladesh", "continent" => "Asia" ],
		"BB" => [ 'alpha2' => 'BB', 'alpha3' => 'BRB', 'num' => '052', 'isd' => '1246', "name" => "Barbados", "continent" => "North America" ],
		"BY" => [ 'alpha2' => 'BY', 'alpha3' => 'BLR', 'num' => '112', 'isd' => '375', "name" => "Belarus", "continent" => "Europe" ],
		"BE" => [ 'alpha2' => 'BE', 'alpha3' => 'BEL', 'num' => '056', 'isd' => '32', "name" => "Belgium", "continent" => "Europe" ],
		"BZ" => [ 'alpha2' => 'BZ', 'alpha3' => 'BLZ', 'num' => '084', 'isd' => '501', "name" => "Belize", "continent" => "North America" ],
		"BJ" => [ 'alpha2' => 'BJ', 'alpha3' => 'BEN', 'num' => '204', 'isd' => '229', "name" => "Benin", "continent" => "Africa" ],
		"BM" => [ 'alpha2' => 'BM', 'alpha3' => 'BMU', 'num' => '060', 'isd' => '1441', "name" => "Bermuda", "continent" => "North America" ],
		"BT" => [ 'alpha2' => 'BT', 'alpha3' => 'BTN', 'num' => '064', 'isd' => '975', "name" => "Bhutan", "continent" => "Asia" ],
		"BO" => [ 'alpha2' => 'BO', 'alpha3' => 'BOL', 'num' => '068', 'isd' => '591', "name" => "Bolivia", "continent" => "South America" ],
		"BA" => [ 'alpha2' => 'BA', 'alpha3' => 'BIH', 'num' => '070', 'isd' => '387', "name" => "Bosnia and Herzegovina", "continent" => "Europe" ],
		"BW" => [ 'alpha2' => 'BW', 'alpha3' => 'BWA', 'num' => '072', 'isd' => '267', "name" => "Botswana", "continent" => "Africa" ],
		"BV" => [ 'alpha2' => 'BV', 'alpha3' => 'BVT', 'num' => '074', 'isd' => '61', "name" => "Bouvet Island", "continent" => "Antarctica" ],
		"BR" => [ 'alpha2' => 'BR', 'alpha3' => 'BRA', 'num' => '076', 'isd' => '55', "name" => "Brazil", "continent" => "South America" ],
		"IO" => [ 'alpha2' => 'IO', 'alpha3' => 'IOT', 'num' => '086', 'isd' => '246', "name" => "British Indian Ocean Territory", "continent" => "Asia" ],
		"BN" => [ 'alpha2' => 'BN', 'alpha3' => 'BRN', 'num' => '096', 'isd' => '672', "name" => "Brunei Darussalam", "continent" => "Asia" ],
		"BG" => [ 'alpha2' => 'BG', 'alpha3' => 'BGR', 'num' => '100', 'isd' => '359', "name" => "Bulgaria", "continent" => "Europe" ],
		"BF" => [ 'alpha2' => 'BF', 'alpha3' => 'BFA', 'num' => '854', 'isd' => '226', "name" => "Burkina Faso", "continent" => "Africa" ],
		"BI" => [ 'alpha2' => 'BI', 'alpha3' => 'BDI', 'num' => '108', 'isd' => '257', "name" => "Burundi", "continent" => "Africa" ],
		"KH" => [ 'alpha2' => 'KH', 'alpha3' => 'KHM', 'num' => '116', 'isd' => '855', "name" => "Cambodia", "continent" => "Asia" ],
		"CM" => [ 'alpha2' => 'CM', 'alpha3' => 'CMR', 'num' => '120', 'isd' => '231', "name" => "Cameroon", "continent" => "Africa" ],
		"CA" => [ 'alpha2' => 'CA', 'alpha3' => 'CAN', 'num' => '124', 'isd' => '1', "name" => "Canada", "continent" => "North America" ],
		"CV" => [ 'alpha2' => 'CV', 'alpha3' => 'CPV', 'num' => '132', 'isd' => '238', "name" => "Cape Verde", "continent" => "Africa" ],
		"KY" => [ 'alpha2' => 'KY', 'alpha3' => 'CYM', 'num' => '136', 'isd' => '1345', "name" => "Cayman Islands", "continent" => "North America" ],
		"CF" => [ 'alpha2' => 'CF', 'alpha3' => 'CAF', 'num' => '140', 'isd' => '236', "name" => "Central African Republic", "continent" => "Africa" ],
		"TD" => [ 'alpha2' => 'TD', 'alpha3' => 'TCD', 'num' => '148', 'isd' => '235', "name" => "Chad", "continent" => "Africa" ],
		"CL" => [ 'alpha2' => 'CL', 'alpha3' => 'CHL', 'num' => '152', 'isd' => '56', "name" => "Chile", "continent" => "South America" ],
		"CN" => [ 'alpha2' => 'CN', 'alpha3' => 'CHN', 'num' => '156', 'isd' => '86', "name" => "China", "continent" => "Asia" ],
		"CX" => [ 'alpha2' => 'CX', 'alpha3' => 'CXR', 'num' => '162', 'isd' => '61', "name" => "Christmas Island", "continent" => "Asia" ],
		"CC" => [ 'alpha2' => 'CC', 'alpha3' => 'CCK', 'num' => '166', 'isd' => '891', "name" => "Cocos (Keeling) Islands", "continent" => "Asia" ],
		"CO" => [ 'alpha2' => 'CO', 'alpha3' => 'COL', 'num' => '170', 'isd' => '57', "name" => "Colombia", "continent" => "South America" ],
		"KM" => [ 'alpha2' => 'KM', 'alpha3' => 'COM', 'num' => '174', 'isd' => '269', "name" => "Comoros", "continent" => "Africa" ],
		"CG" => [ 'alpha2' => 'CG', 'alpha3' => 'COG', 'num' => '178', 'isd' => '242', "name" => "Congo", "continent" => "Africa" ],
		"CD" => [ 'alpha2' => 'CD', 'alpha3' => 'COD', 'num' => '180', 'isd' => '243', "name" => "The Democratic Republic of The Congo", "continent" => "Africa" ],
		"CK" => [ 'alpha2' => 'CK', 'alpha3' => 'COK', 'num' => '184', 'isd' => '682', "name" => "Cook Islands", "continent" => "Oceania" ],
		"CR" => [ 'alpha2' => 'CR', 'alpha3' => 'CRI', 'num' => '188', 'isd' => '506', "name" => "Costa Rica", "continent" => "North America" ],
		"CI" => [ 'alpha2' => 'CI', 'alpha3' => 'CIV', 'num' => '384', 'isd' => '225', "name" => "Cote D'ivoire", "continent" => "Africa" ],
		"HR" => [ 'alpha2' => 'HR', 'alpha3' => 'HRV', 'num' => '191', 'isd' => '385', "name" => "Croatia", "continent" => "Europe" ],
		"CU" => [ 'alpha2' => 'CU', 'alpha3' => 'CUB', 'num' => '192', 'isd' => '53', "name" => "Cuba", "continent" => "North America" ],
		"CY" => [ 'alpha2' => 'CY', 'alpha3' => 'CYP', 'num' => '196', 'isd' => '357', "name" => "Cyprus", "continent" => "Asia" ],
		"CZ" => [ 'alpha2' => 'CZ', 'alpha3' => 'CZE', 'num' => '203', 'isd' => '420', "name" => "Czech Republic", "continent" => "Europe" ],
		"DK" => [ 'alpha2' => 'DK', 'alpha3' => 'DNK', 'num' => '208', 'isd' => '45', "name" => "Denmark", "continent" => "Europe" ],
		"DJ" => [ 'alpha2' => 'DJ', 'alpha3' => 'DJI', 'num' => '262', 'isd' => '253', "name" => "Djibouti", "continent" => "Africa" ],
		"DM" => [ 'alpha2' => 'DM', 'alpha3' => 'DMA', 'num' => '212', 'isd' => '1767', "name" => "Dominica", "continent" => "North America" ],
		"DO" => [ 'alpha2' => 'DO', 'alpha3' => 'DOM', 'num' => '214', 'isd' => '1809', "name" => "Dominican Republic", "continent" => "North America" ],
		"EC" => [ 'alpha2' => 'EC', 'alpha3' => 'ECU', 'num' => '218', 'isd' => '593', "name" => "Ecuador", "continent" => "South America" ],
		"EG" => [ 'alpha2' => 'EG', 'alpha3' => 'EGY', 'num' => '818', 'isd' => '20', "name" => "Egypt", "continent" => "Africa" ],
		"SV" => [ 'alpha2' => 'SV', 'alpha3' => 'SLV', 'num' => '222', 'isd' => '503', "name" => "El Salvador", "continent" => "North America" ],
		"GQ" => [ 'alpha2' => 'GQ', 'alpha3' => 'GNQ', 'num' => '226', 'isd' => '240', "name" => "Equatorial Guinea", "continent" => "Africa" ],
		"ER" => [ 'alpha2' => 'ER', 'alpha3' => 'ERI', 'num' => '232', 'isd' => '291', "name" => "Eritrea", "continent" => "Africa" ],
		"EE" => [ 'alpha2' => 'EE', 'alpha3' => 'EST', 'num' => '233', 'isd' => '372', "name" => "Estonia", "continent" => "Europe" ],
		"ET" => [ 'alpha2' => 'ET', 'alpha3' => 'ETH', 'num' => '231', 'isd' => '251', "name" => "Ethiopia", "continent" => "Africa" ],
		"FK" => [ 'alpha2' => 'FK', 'alpha3' => 'FLK', 'num' => '238', 'isd' => '500', "name" => "Falkland Islands (Malvinas)", "continent" => "South America" ],
		"FO" => [ 'alpha2' => 'FO', 'alpha3' => 'FRO', 'num' => '234', 'isd' => '298', "name" => "Faroe Islands", "continent" => "Europe" ],
		"FJ" => [ 'alpha2' => 'FJ', 'alpha3' => 'FJI', 'num' => '243', 'isd' => '679', "name" => "Fiji", "continent" => "Oceania" ],
		"FI" => [ 'alpha2' => 'FI', 'alpha3' => 'FIN', 'num' => '246', 'isd' => '238', "name" => "Finland", "continent" => "Europe" ],
		"FR" => [ 'alpha2' => 'FR', 'alpha3' => 'FRA', 'num' => '250', 'isd' => '33', "name" => "France", "continent" => "Europe" ],
		"GF" => [ 'alpha2' => 'GF', 'alpha3' => 'GUF', 'num' => '254', 'isd' => '594', "name" => "French Guiana", "continent" => "South America" ],
		"PF" => [ 'alpha2' => 'PF', 'alpha3' => 'PYF', 'num' => '258', 'isd' => '689', "name" => "French Polynesia", "continent" => "Oceania" ],
		"TF" => [ 'alpha2' => 'TF', 'alpha3' => 'ATF', 'num' => '260', 'isd' => '262', "name" => "French Southern Territories", "continent" => "Antarctica" ],
		"GA" => [ 'alpha2' => 'GA', 'alpha3' => 'GAB', 'num' => '266', 'isd' => '241', "name" => "Gabon", "continent" => "Africa" ],
		"GM" => [ 'alpha2' => 'GM', 'alpha3' => 'GMB', 'num' => '270', 'isd' => '220', "name" => "Gambia", "continent" => "Africa" ],
		"GE" => [ 'alpha2' => 'GE', 'alpha3' => 'GEO', 'num' => '268', 'isd' => '995', "name" => "Georgia", "continent" => "Asia" ],
		"DE" => [ 'alpha2' => 'DE', 'alpha3' => 'DEU', 'num' => '276', 'isd' => '49', "name" => "Germany", "continent" => "Europe" ],
		"GH" => [ 'alpha2' => 'GH', 'alpha3' => 'GHA', 'num' => '288', 'isd' => '233', "name" => "Ghana", "continent" => "Africa" ],
		"GI" => [ 'alpha2' => 'GI', 'alpha3' => 'GIB', 'num' => '292', 'isd' => '350', "name" => "Gibraltar", "continent" => "Europe" ],
		"GR" => [ 'alpha2' => 'GR', 'alpha3' => 'GRC', 'num' => '300', 'isd' => '30', "name" => "Greece", "continent" => "Europe" ],
		"GL" => [ 'alpha2' => 'GL', 'alpha3' => 'GRL', 'num' => '304', 'isd' => '299', "name" => "Greenland", "continent" => "North America" ],
		"GD" => [ 'alpha2' => 'GD', 'alpha3' => 'GRD', 'num' => '308', 'isd' => '1473', "name" => "Grenada", "continent" => "North America" ],
		"GP" => [ 'alpha2' => 'GP', 'alpha3' => 'GLP', 'num' => '312', 'isd' => '590', "name" => "Guadeloupe", "continent" => "North America" ],
		"GU" => [ 'alpha2' => 'GU', 'alpha3' => 'GUM', 'num' => '316', 'isd' => '1871', "name" => "Guam", "continent" => "Oceania" ],
		"GT" => [ 'alpha2' => 'GT', 'alpha3' => 'GTM', 'num' => '320', 'isd' => '502', "name" => "Guatemala", "continent" => "North America" ],
		"GG" => [ 'alpha2' => 'GG', 'alpha3' => 'GGY', 'num' => '831', 'isd' => '44', "name" => "Guernsey", "continent" => "Europe" ],
		"GN" => [ 'alpha2' => 'GN', 'alpha3' => 'GIN', 'num' => '324', 'isd' => '224', "name" => "Guinea", "continent" => "Africa" ],
		"GW" => [ 'alpha2' => 'GW', 'alpha3' => 'GNB', 'num' => '624', 'isd' => '245', "name" => "Guinea-bissau", "continent" => "Africa" ],
		"GY" => [ 'alpha2' => 'GY', 'alpha3' => 'GUY', 'num' => '328', 'isd' => '592', "name" => "Guyana", "continent" => "South America" ],
		"HT" => [ 'alpha2' => 'HT', 'alpha3' => 'HTI', 'num' => '332', 'isd' => '509', "name" => "Haiti", "continent" => "North America" ],
		"HM" => [ 'alpha2' => 'HM', 'alpha3' => 'HMD', 'num' => '334', 'isd' => '672', "name" => "Heard Island and Mcdonald Islands", "continent" => "Antarctica" ],
		"VA" => [ 'alpha2' => 'VA', 'alpha3' => 'VAT', 'num' => '336', 'isd' => '379', "name" => "Holy See (Vatican City State)", "continent" => "Europe" ],
		"HN" => [ 'alpha2' => 'HN', 'alpha3' => 'HND', 'num' => '340', 'isd' => '504', "name" => "Honduras", "continent" => "North America" ],
		"HK" => [ 'alpha2' => 'HK', 'alpha3' => 'HKG', 'num' => '344', 'isd' => '852', "name" => "Hong Kong", "continent" => "Asia" ],
		"HU" => [ 'alpha2' => 'HU', 'alpha3' => 'HUN', 'num' => '348', 'isd' => '36', "name" => "Hungary", "continent" => "Europe" ],
		"IS" => [ 'alpha2' => 'IS', 'alpha3' => 'ISL', 'num' => '352', 'isd' => '354', "name" => "Iceland", "continent" => "Europe" ],
		"IN" => [ 'alpha2' => 'IN', 'alpha3' => 'IND', 'num' => '356', 'isd' => '91', "name" => "India", "continent" => "Asia" ],
		"ID" => [ 'alpha2' => 'ID', 'alpha3' => 'IDN', 'num' => '360', 'isd' => '62', "name" => "Indonesia", "continent" => "Asia" ],
		"IR" => [ 'alpha2' => 'IR', 'alpha3' => 'IRN', 'num' => '364', 'isd' => '98', "name" => "Iran", "continent" => "Asia" ],
		"IQ" => [ 'alpha2' => 'IQ', 'alpha3' => 'IRQ', 'num' => '368', 'isd' => '964', "name" => "Iraq", "continent" => "Asia" ],
		"IE" => [ 'alpha2' => 'IE', 'alpha3' => 'IRL', 'num' => '372', 'isd' => '353', "name" => "Ireland", "continent" => "Europe" ],
		"IM" => [ 'alpha2' => 'IM', 'alpha3' => 'IMN', 'num' => '833', 'isd' => '44', "name" => "Isle of Man", "continent" => "Europe" ],
		"IL" => [ 'alpha2' => 'IL', 'alpha3' => 'ISR', 'num' => '376', 'isd' => '972', "name" => "Israel", "continent" => "Asia" ],
		"IT" => [ 'alpha2' => 'IT', 'alpha3' => 'ITA', 'num' => '380', 'isd' => '39', "name" => "Italy", "continent" => "Europe" ],
		"JM" => [ 'alpha2' => 'JM', 'alpha3' => 'JAM', 'num' => '388', 'isd' => '1876', "name" => "Jamaica", "continent" => "North America" ],
		"JP" => [ 'alpha2' => 'JP', 'alpha3' => 'JPN', 'num' => '392', 'isd' => '81', "name" => "Japan", "continent" => "Asia" ],
		"JE" => [ 'alpha2' => 'JE', 'alpha3' => 'JEY', 'num' => '832', 'isd' => '44', "name" => "Jersey", "continent" => "Europe" ],
		"JO" => [ 'alpha2' => 'JO', 'alpha3' => 'JOR', 'num' => '400', 'isd' => '962', "name" => "Jordan", "continent" => "Asia" ],
		"KZ" => [ 'alpha2' => 'KZ', 'alpha3' => 'KAZ', 'num' => '398', 'isd' => '7', "name" => "Kazakhstan", "continent" => "Asia" ],
		"KE" => [ 'alpha2' => 'KE', 'alpha3' => 'KEN', 'num' => '404', 'isd' => '254', "name" => "Kenya", "continent" => "Africa" ],
		"KI" => [ 'alpha2' => 'KI', 'alpha3' => 'KIR', 'num' => '296', 'isd' => '686', "name" => "Kiribati", "continent" => "Oceania" ],
		"KP" => [ 'alpha2' => 'KP', 'alpha3' => 'PRK', 'num' => '408', 'isd' => '850', "name" => "Democratic People's Republic of Korea", "continent" => "Asia" ],
		"KR" => [ 'alpha2' => 'KR', 'alpha3' => 'KOR', 'num' => '410', 'isd' => '82', "name" => "Republic of Korea", "continent" => "Asia" ],
		"KW" => [ 'alpha2' => 'KW', 'alpha3' => 'KWT', 'num' => '414', 'isd' => '965', "name" => "Kuwait", "continent" => "Asia" ],
		"KG" => [ 'alpha2' => 'KG', 'alpha3' => 'KGZ', 'num' => '417', 'isd' => '996', "name" => "Kyrgyzstan", "continent" => "Asia" ],
		"LA" => [ 'alpha2' => 'LA', 'alpha3' => 'LAO', 'num' => '418', 'isd' => '856', "name" => "Lao People's Democratic Republic", "continent" => "Asia" ],
		"LV" => [ 'alpha2' => 'LV', 'alpha3' => 'LVA', 'num' => '428', 'isd' => '371', "name" => "Latvia", "continent" => "Europe" ],
		"LB" => [ 'alpha2' => 'LB', 'alpha3' => 'LBN', 'num' => '422', 'isd' => '961', "name" => "Lebanon", "continent" => "Asia" ],
		"LS" => [ 'alpha2' => 'LS', 'alpha3' => 'LSO', 'num' => '426', 'isd' => '266', "name" => "Lesotho", "continent" => "Africa" ],
		"LR" => [ 'alpha2' => 'LR', 'alpha3' => 'LBR', 'num' => '430', 'isd' => '231', "name" => "Liberia", "continent" => "Africa" ],
		"LY" => [ 'alpha2' => 'LY', 'alpha3' => 'LBY', 'num' => '434', 'isd' => '218', "name" => "Libya", "continent" => "Africa" ],
		"LI" => [ 'alpha2' => 'LI', 'alpha3' => 'LIE', 'num' => '438', 'isd' => '423', "name" => "Liechtenstein", "continent" => "Europe" ],
		"LT" => [ 'alpha2' => 'LT', 'alpha3' => 'LTU', 'num' => '440', 'isd' => '370', "name" => "Lithuania", "continent" => "Europe" ],
		"LU" => [ 'alpha2' => 'LU', 'alpha3' => 'LUX', 'num' => '442', 'isd' => '352', "name" => "Luxembourg", "continent" => "Europe" ],
		"MO" => [ 'alpha2' => 'MO', 'alpha3' => 'MAC', 'num' => '446', 'isd' => '853', "name" => "Macao", "continent" => "Asia" ],
		"MK" => [ 'alpha2' => 'MK', 'alpha3' => 'MKD', 'num' => '807', 'isd' => '389', "name" => "Macedonia", "continent" => "Europe" ],
		"MG" => [ 'alpha2' => 'MG', 'alpha3' => 'MDG', 'num' => '450', 'isd' => '261', "name" => "Madagascar", "continent" => "Africa" ],
		"MW" => [ 'alpha2' => 'MW', 'alpha3' => 'MWI', 'num' => '454', 'isd' => '265', "name" => "Malawi", "continent" => "Africa" ],
		"MY" => [ 'alpha2' => 'MY', 'alpha3' => 'MYS', 'num' => '458', 'isd' => '60', "name" => "Malaysia", "continent" => "Asia" ],
		"MV" => [ 'alpha2' => 'MV', 'alpha3' => 'MDV', 'num' => '462', 'isd' => '960', "name" => "Maldives", "continent" => "Asia" ],
		"ML" => [ 'alpha2' => 'ML', 'alpha3' => 'MLI', 'num' => '466', 'isd' => '223', "name" => "Mali", "continent" => "Africa" ],
		"MT" => [ 'alpha2' => 'MT', 'alpha3' => 'MLT', 'num' => '470', 'isd' => '356', "name" => "Malta", "continent" => "Europe" ],
		"MH" => [ 'alpha2' => 'MH', 'alpha3' => 'MHL', 'num' => '584', 'isd' => '692', "name" => "Marshall Islands", "continent" => "Oceania" ],
		"MQ" => [ 'alpha2' => 'MQ', 'alpha3' => 'MTQ', 'num' => '474', 'isd' => '596', "name" => "Martinique", "continent" => "North America" ],
		"MR" => [ 'alpha2' => 'MR', 'alpha3' => 'MRT', 'num' => '478', 'isd' => '222', "name" => "Mauritania", "continent" => "Africa" ],
		"MU" => [ 'alpha2' => 'MU', 'alpha3' => 'MUS', 'num' => '480', 'isd' => '230', "name" => "Mauritius", "continent" => "Africa" ],
		"YT" => [ 'alpha2' => 'YT', 'alpha3' => 'MYT', 'num' => '175', 'isd' => '262', "name" => "Mayotte", "continent" => "Africa" ],
		"MX" => [ 'alpha2' => 'MX', 'alpha3' => 'MEX', 'num' => '484', 'isd' => '52', "name" => "Mexico", "continent" => "North America" ],
		"FM" => [ 'alpha2' => 'FM', 'alpha3' => 'FSM', 'num' => '583', 'isd' => '691', "name" => "Micronesia", "continent" => "Oceania" ],
		"MD" => [ 'alpha2' => 'MD', 'alpha3' => 'MDA', 'num' => '498', 'isd' => '373', "name" => "Moldova", "continent" => "Europe" ],
		"MC" => [ 'alpha2' => 'MC', 'alpha3' => 'MCO', 'num' => '492', 'isd' => '377', "name" => "Monaco", "continent" => "Europe" ],
		"MN" => [ 'alpha2' => 'MN', 'alpha3' => 'MNG', 'num' => '496', 'isd' => '976', "name" => "Mongolia", "continent" => "Asia" ],
		"ME" => [ 'alpha2' => 'ME', 'alpha3' => 'MNE', 'num' => '499', 'isd' => '382', "name" => "Montenegro", "continent" => "Europe" ],
		"MS" => [ 'alpha2' => 'MS', 'alpha3' => 'MSR', 'num' => '500', 'isd' => '1664', "name" => "Montserrat", "continent" => "North America" ],
		"MA" => [ 'alpha2' => 'MA', 'alpha3' => 'MAR', 'num' => '504', 'isd' => '212', "name" => "Morocco", "continent" => "Africa" ],
		"MZ" => [ 'alpha2' => 'MZ', 'alpha3' => 'MOZ', 'num' => '508', 'isd' => '258', "name" => "Mozambique", "continent" => "Africa" ],
		"MM" => [ 'alpha2' => 'MM', 'alpha3' => 'MMR', 'num' => '104', 'isd' => '95', "name" => "Myanmar", "continent" => "Asia" ],
		"NA" => [ 'alpha2' => 'NA', 'alpha3' => 'NAM', 'num' => '516', 'isd' => '264', "name" => "Namibia", "continent" => "Africa" ],
		"NR" => [ 'alpha2' => 'NR', 'alpha3' => 'NRU', 'num' => '520', 'isd' => '674', "name" => "Nauru", "continent" => "Oceania" ],
		"NP" => [ 'alpha2' => 'NP', 'alpha3' => 'NPL', 'num' => '524', 'isd' => '977', "name" => "Nepal", "continent" => "Asia" ],
		"NL" => [ 'alpha2' => 'NL', 'alpha3' => 'NLD', 'num' => '528', 'isd' => '31', "name" => "Netherlands", "continent" => "Europe" ],
		"AN" => [ 'alpha2' => 'AN', 'alpha3' => 'ANT', 'num' => '530', 'isd' => '599', "name" => "Netherlands Antilles", "continent" => "North America" ],
		"NC" => [ 'alpha2' => 'NC', 'alpha3' => 'NCL', 'num' => '540', 'isd' => '687', "name" => "New Caledonia", "continent" => "Oceania" ],
		"NZ" => [ 'alpha2' => 'NZ', 'alpha3' => 'NZL', 'num' => '554', 'isd' => '64', "name" => "New Zealand", "continent" => "Oceania" ],
		"NI" => [ 'alpha2' => 'NI', 'alpha3' => 'NIC', 'num' => '558', 'isd' => '505', "name" => "Nicaragua", "continent" => "North America" ],
		"NE" => [ 'alpha2' => 'NE', 'alpha3' => 'NER', 'num' => '562', 'isd' => '227', "name" => "Niger", "continent" => "Africa" ],
		"NG" => [ 'alpha2' => 'NG', 'alpha3' => 'NGA', 'num' => '566', 'isd' => '234', "name" => "Nigeria", "continent" => "Africa" ],
		"NU" => [ 'alpha2' => 'NU', 'alpha3' => 'NIU', 'num' => '570', 'isd' => '683', "name" => "Niue", "continent" => "Oceania" ],
		"NF" => [ 'alpha2' => 'NF', 'alpha3' => 'NFK', 'num' => '574', 'isd' => '672', "name" => "Norfolk Island", "continent" => "Oceania" ],
		"MP" => [ 'alpha2' => 'MP', 'alpha3' => 'MNP', 'num' => '580', 'isd' => '1670', "name" => "Northern Mariana Islands", "continent" => "Oceania" ],
		"NO" => [ 'alpha2' => 'NO', 'alpha3' => 'NOR', 'num' => '578', 'isd' => '47', "name" => "Norway", "continent" => "Europe" ],
		"OM" => [ 'alpha2' => 'OM', 'alpha3' => 'OMN', 'num' => '512', 'isd' => '968', "name" => "Oman", "continent" => "Asia" ],
		"PK" => [ 'alpha2' => 'PK', 'alpha3' => 'PAK', 'num' => '586', 'isd' => '92', "name" => "Pakistan", "continent" => "Asia" ],
		"PW" => [ 'alpha2' => 'PW', 'alpha3' => 'PLW', 'num' => '585', 'isd' => '680', "name" => "Palau", "continent" => "Oceania" ],
		"PS" => [ 'alpha2' => 'PS', 'alpha3' => 'PSE', 'num' => '275', 'isd' => '970', "name" => "Palestinia", "continent" => "Asia" ],
		"PA" => [ 'alpha2' => 'PA', 'alpha3' => 'PAN', 'num' => '591', 'isd' => '507', "name" => "Panama", "continent" => "North America" ],
		"PG" => [ 'alpha2' => 'PG', 'alpha3' => 'PNG', 'num' => '598', 'isd' => '675', "name" => "Papua New Guinea", "continent" => "Oceania" ],
		"PY" => [ 'alpha2' => 'PY', 'alpha3' => 'PRY', 'num' => '600', 'isd' => '595', "name" => "Paraguay", "continent" => "South America" ],
		"PE" => [ 'alpha2' => 'PE', 'alpha3' => 'PER', 'num' => '604', 'isd' => '51', "name" => "Peru", "continent" => "South America" ],
		"PH" => [ 'alpha2' => 'PH', 'alpha3' => 'PHL', 'num' => '608', 'isd' => '63', "name" => "Philippines", "continent" => "Asia" ],
		"PN" => [ 'alpha2' => 'PN', 'alpha3' => 'PCN', 'num' => '612', 'isd' => '870', "name" => "Pitcairn", "continent" => "Oceania" ],
		"PL" => [ 'alpha2' => 'PL', 'alpha3' => 'POL', 'num' => '616', 'isd' => '48', "name" => "Poland", "continent" => "Europe" ],
		"PT" => [ 'alpha2' => 'PT', 'alpha3' => 'PRT', 'num' => '620', 'isd' => '351', "name" => "Portugal", "continent" => "Europe" ],
		"PR" => [ 'alpha2' => 'PR', 'alpha3' => 'PRI', 'num' => '630', 'isd' => '1', "name" => "Puerto Rico", "continent" => "North America" ],
		"QA" => [ 'alpha2' => 'QA', 'alpha3' => 'QAT', 'num' => '634', 'isd' => '974', "name" => "Qatar", "continent" => "Asia" ],
		"RE" => [ 'alpha2' => 'RE', 'alpha3' => 'REU', 'num' => '638', 'isd' => '262', "name" => "Reunion", "continent" => "Africa" ],
		"RO" => [ 'alpha2' => 'RO', 'alpha3' => 'ROU', 'num' => '642', 'isd' => '40', "name" => "Romania", "continent" => "Europe" ],
		"RU" => [ 'alpha2' => 'RU', 'alpha3' => 'RUS', 'num' => '643', 'isd' => '7', "name" => "Russian Federation", "continent" => "Europe" ],
		"RW" => [ 'alpha2' => 'RW', 'alpha3' => 'RWA', 'num' => '646', 'isd' => '250', "name" => "Rwanda", "continent" => "Africa" ],
		"SH" => [ 'alpha2' => 'SH', 'alpha3' => 'SHN', 'num' => '654', 'isd' => '290', "name" => "Saint Helena", "continent" => "Africa" ],
		"KN" => [ 'alpha2' => 'KN', 'alpha3' => 'KNA', 'num' => '659', 'isd' => '1869', "name" => "Saint Kitts and Nevis", "continent" => "North America" ],
		"LC" => [ 'alpha2' => 'LC', 'alpha3' => 'LCA', 'num' => '662', 'isd' => '1758', "name" => "Saint Lucia", "continent" => "North America" ],
		"PM" => [ 'alpha2' => 'PM', 'alpha3' => 'SPM', 'num' => '666', 'isd' => '508', "name" => "Saint Pierre and Miquelon", "continent" => "North America" ],
		"VC" => [ 'alpha2' => 'VC', 'alpha3' => 'VCT', 'num' => '670', 'isd' => '1784', "name" => "Saint Vincent and The Grenadines", "continent" => "North America" ],
		"WS" => [ 'alpha2' => 'WS', 'alpha3' => 'WSM', 'num' => '882', 'isd' => '685', "name" => "Samoa", "continent" => "Oceania" ],
		"SM" => [ 'alpha2' => 'SM', 'alpha3' => 'SMR', 'num' => '674', 'isd' => '378', "name" => "San Marino", "continent" => "Europe" ],
		"ST" => [ 'alpha2' => 'ST', 'alpha3' => 'STP', 'num' => '678', 'isd' => '239', "name" => "Sao Tome and Principe", "continent" => "Africa" ],
		"SA" => [ 'alpha2' => 'SA', 'alpha3' => 'SAU', 'num' => '682', 'isd' => '966', "name" => "Saudi Arabia", "continent" => "Asia" ],
		"SN" => [ 'alpha2' => 'SN', 'alpha3' => 'SEN', 'num' => '686', 'isd' => '221', "name" => "Senegal", "continent" => "Africa" ],
		"RS" => [ 'alpha2' => 'RS', 'alpha3' => 'SRB', 'num' => '688', 'isd' => '381', "name" => "Serbia", "continent" => "Europe" ],
		"SC" => [ 'alpha2' => 'SC', 'alpha3' => 'SYC', 'num' => '690', 'isd' => '248', "name" => "Seychelles", "continent" => "Africa" ],
		"SL" => [ 'alpha2' => 'SL', 'alpha3' => 'SLE', 'num' => '694', 'isd' => '232', "name" => "Sierra Leone", "continent" => "Africa" ],
		"SG" => [ 'alpha2' => 'SG', 'alpha3' => 'SGP', 'num' => '702', 'isd' => '65', "name" => "Singapore", "continent" => "Asia" ],
		"SK" => [ 'alpha2' => 'SK', 'alpha3' => 'SVK', 'num' => '703', 'isd' => '421', "name" => "Slovakia", "continent" => "Europe" ],
		"SI" => [ 'alpha2' => 'SI', 'alpha3' => 'SVN', 'num' => '705', 'isd' => '386', "name" => "Slovenia", "continent" => "Europe" ],
		"SB" => [ 'alpha2' => 'SB', 'alpha3' => 'SLB', 'num' => '090', 'isd' => '677', "name" => "Solomon Islands", "continent" => "Oceania" ],
		"SO" => [ 'alpha2' => 'SO', 'alpha3' => 'SOM', 'num' => '706', 'isd' => '252', "name" => "Somalia", "continent" => "Africa" ],
		"ZA" => [ 'alpha2' => 'ZA', 'alpha3' => 'ZAF', 'num' => '729', 'isd' => '27', "name" => "South Africa", "continent" => "Africa" ],
		"SS" => [ 'alpha2' => 'SS', 'alpha3' => 'SSD', 'num' => '710', 'isd' => '211', "name" => "South Sudan", "continent" => "Africa" ],
		"GS" => [ 'alpha2' => 'GS', 'alpha3' => 'SGS', 'num' => '239', 'isd' => '500', "name" => "South Georgia and The South Sandwich Islands", "continent" => "Antarctica" ],
		"ES" => [ 'alpha2' => 'ES', 'alpha3' => 'ESP', 'num' => '724', 'isd' => '34', "name" => "Spain", "continent" => "Europe" ],
		"LK" => [ 'alpha2' => 'LK', 'alpha3' => 'LKA', 'num' => '144', 'isd' => '94', "name" => "Sri Lanka", "continent" => "Asia" ],
		"SD" => [ 'alpha2' => 'SD', 'alpha3' => 'SDN', 'num' => '736', 'isd' => '249', "name" => "Sudan", "continent" => "Africa" ],
		"SR" => [ 'alpha2' => 'SR', 'alpha3' => 'SUR', 'num' => '740', 'isd' => '597', "name" => "Suriname", "continent" => "South America" ],
		"SJ" => [ 'alpha2' => 'SJ', 'alpha3' => 'SJM', 'num' => '744', 'isd' => '47', "name" => "Svalbard and Jan Mayen", "continent" => "Europe" ],
		"SZ" => [ 'alpha2' => 'SZ', 'alpha3' => 'SWZ', 'num' => '748', 'isd' => '268', "name" => "Swaziland", "continent" => "Africa" ],
		"SE" => [ 'alpha2' => 'SE', 'alpha3' => 'SWE', 'num' => '752', 'isd' => '46', "name" => "Sweden", "continent" => "Europe" ],
		"CH" => [ 'alpha2' => 'CH', 'alpha3' => 'CHE', 'num' => '756', 'isd' => '41', "name" => "Switzerland", "continent" => "Europe" ],
		"SY" => [ 'alpha2' => 'SY', 'alpha3' => 'SYR', 'num' => '760', 'isd' => '963', "name" => "Syrian Arab Republic", "continent" => "Asia" ],
		"TW" => [ 'alpha2' => 'TW', 'alpha3' => 'TWN', 'num' => '158', 'isd' => '886', "name" => "Taiwan, Province of China", "continent" => "Asia" ],
		"TJ" => [ 'alpha2' => 'TJ', 'alpha3' => 'TJK', 'num' => '762', 'isd' => '992', "name" => "Tajikistan", "continent" => "Asia" ],
		"TZ" => [ 'alpha2' => 'TZ', 'alpha3' => 'TZA', 'num' => '834', 'isd' => '255', "name" => "Tanzania, United Republic of", "continent" => "Africa" ],
		"TH" => [ 'alpha2' => 'TH', 'alpha3' => 'THA', 'num' => '764', 'isd' => '66', "name" => "Thailand", "continent" => "Asia" ],
		"TL" => [ 'alpha2' => 'TL', 'alpha3' => 'TLS', 'num' => '626', 'isd' => '670', "name" => "Timor-leste", "continent" => "Asia" ],
		"TG" => [ 'alpha2' => 'TG', 'alpha3' => 'TGO', 'num' => '768', 'isd' => '228', "name" => "Togo", "continent" => "Africa" ],
		"TK" => [ 'alpha2' => 'TK', 'alpha3' => 'TKL', 'num' => '772', 'isd' => '690', "name" => "Tokelau", "continent" => "Oceania" ],
		"TO" => [ 'alpha2' => 'TO', 'alpha3' => 'TON', 'num' => '776', 'isd' => '676', "name" => "Tonga", "continent" => "Oceania" ],
		"TT" => [ 'alpha2' => 'TT', 'alpha3' => 'TTO', 'num' => '780', 'isd' => '1868', "name" => "Trinidad and Tobago", "continent" => "North America" ],
		"TN" => [ 'alpha2' => 'TN', 'alpha3' => 'TUN', 'num' => '788', 'isd' => '216', "name" => "Tunisia", "continent" => "Africa" ],
		"TR" => [ 'alpha2' => 'TR', 'alpha3' => 'TUR', 'num' => '792', 'isd' => '90', "name" => "Turkey", "continent" => "Asia" ],
		"TM" => [ 'alpha2' => 'TM', 'alpha3' => 'TKM', 'num' => '795', 'isd' => '993', "name" => "Turkmenistan", "continent" => "Asia" ],
		"TC" => [ 'alpha2' => 'TC', 'alpha3' => 'TCA', 'num' => '796', 'isd' => '1649', "name" => "Turks and Caicos Islands", "continent" => "North America" ],
		"TV" => [ 'alpha2' => 'TV', 'alpha3' => 'TUV', 'num' => '798', 'isd' => '688', "name" => "Tuvalu", "continent" => "Oceania" ],
		"UG" => [ 'alpha2' => 'UG', 'alpha3' => 'UGA', 'num' => '800', 'isd' => '256', "name" => "Uganda", "continent" => "Africa" ],
		"UA" => [ 'alpha2' => 'UA', 'alpha3' => 'UKR', 'num' => '804', 'isd' => '380', "name" => "Ukraine", "continent" => "Europe" ],
		"AE" => [ 'alpha2' => 'AE', 'alpha3' => 'ARE', 'num' => '784', 'isd' => '971', "name" => "United Arab Emirates", "continent" => "Asia" ],
		"GB" => [ 'alpha2' => 'GB', 'alpha3' => 'GBR', 'num' => '826', 'isd' => '44', "name" => "United Kingdom", "continent" => "Europe" ],
		"US" => [ 'alpha2' => 'US', 'alpha3' => 'USA', 'num' => '840', 'isd' => '1', "name" => "United States", "continent" => "North America" ],
		"UM" => [ 'alpha2' => 'UM', 'alpha3' => 'UMI', 'num' => '581', 'isd' => '1', "name" => "United States Minor Outlying Islands", "continent" => "Oceania" ],
		"UY" => [ 'alpha2' => 'UY', 'alpha3' => 'URY', 'num' => '858', 'isd' => '598', "name" => "Uruguay", "continent" => "South America" ],
		"UZ" => [ 'alpha2' => 'UZ', 'alpha3' => 'UZB', 'num' => '860', 'isd' => '998', "name" => "Uzbekistan", "continent" => "Asia" ],
		"VU" => [ 'alpha2' => 'VU', 'alpha3' => 'VUT', 'num' => '548', 'isd' => '678', "name" => "Vanuatu", "continent" => "Oceania" ],
		"VE" => [ 'alpha2' => 'VE', 'alpha3' => 'VEN', 'num' => '862', 'isd' => '58', "name" => "Venezuela", "continent" => "South America" ],
		"VN" => [ 'alpha2' => 'VN', 'alpha3' => 'VNM', 'num' => '704', 'isd' => '84', "name" => "Vietnam", "continent" => "Asia" ],
		"VG" => [ 'alpha2' => 'VG', 'alpha3' => 'VGB', 'num' => '092', 'isd' => '1284', "name" => "Virgin Islands, British", "continent" => "North America" ],
		"VI" => [ 'alpha2' => 'VI', 'alpha3' => 'VIR', 'num' => '850', 'isd' => '1430', "name" => "Virgin Islands, U.S.", "continent" => "North America" ],
		"WF" => [ 'alpha2' => 'WF', 'alpha3' => 'WLF', 'num' => '876', 'isd' => '681', "name" => "Wallis and Futuna", "continent" => "Oceania" ],
		"EH" => [ 'alpha2' => 'EH', 'alpha3' => 'ESH', 'num' => '732', 'isd' => '212', "name" => "Western Sahara", "continent" => "Africa" ],
		"YE" => [ 'alpha2' => 'YE', 'alpha3' => 'YEM', 'num' => '887', 'isd' => '967', "name" => "Yemen", "continent" => "Asia" ],
		"ZM" => [ 'alpha2' => 'ZM', 'alpha3' => 'ZMB', 'num' => '894', 'isd' => '260', "name" => "Zambia", "continent" => "Africa" ],
		"ZW" => [ 'alpha2' => 'ZW', 'alpha3' => 'ZWE', 'num' => '716', 'isd' => '263', "name" => "Zimbabwe", "continent" => "Africa" ]
	];

	/*
	 * function get()
	 * @param $key - key field for the array of countries, set it to null if you want array without named indices
	 * @param $requestedField - name of the field to be fetched in value part of array
	 * @returns array contained key=>value pairs of the requested key and field
	 *
	 */
	public static function get( $keyField = 'alpha2', $requestedField = 'name' ) {
		$supportedFields = [ 'alpha2', 'alpha3', 'num', 'isd', 'name', 'continent' ];
		//check if field to be used as array key is passed
		if ( ! in_array( $keyField, $supportedFields ) ) {
			$keyField = null;
		}
		//check if the $requestedField is supported or not
		if ( ! in_array( $requestedField, $supportedFields ) ) {
			$requestedField = 'name'; //return country name if invalid/unsupported field name is request
		}
		$result = [];
		//copy each requested field from the countries array
		foreach ( self::$countries as $k => $country ) {
			if ( $keyField ) {
				$result[ $country[ $keyField ] ] = $country[ $requestedField ];
			} else {
				$result[] = $country[ $requestedField ];
			}
		}

		return $result;
	}

	public static function getAlpha3FromAlpha2( $alpha2 ) {
		$countries = self::get( 'alpha2', 'alpha3' );

		return $countries[ $alpha2 ] ?? null;
	}

	public static function getAlpha2FromAlpha3( $alpha3 ) {
		$countries = self::get( 'alpha3', 'alpha2' );

		return $countries[ $alpha3 ] ?? null;
	}
}
