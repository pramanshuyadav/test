<?php
/**
 * Interactive Map Builder Plugin.
 *
 * @author    Fabian Vellguth <info@meisterpixel.com>
 * @link      http://meisterpixel.com/
 * @copyright 2014 meisterpixel.com
 */
 
class Map_Regions {
	// Border resolution FLAGS
	const C = 1; // Countries
	const P = 2; // Provinces
	const M = 4; // Metros
	const K = 8; // Continents
	const S = 16; // Subcontinents
	
	public static function get_tree($translation_slug="") {
		$c = self::C;
		$p = self::P;
		$m = self::M;
		$k = self::K;
		$s = self::S;
		
		$tree = array();
		$slug = $translation_slug;
		// 1: parent   2: id   3: name   4: border resolution
		
		$tree[] = array(null, "world", __("World", $slug),   $c | $k | $s);
		
		// Continents
		$parent = "world";
		$tree[] = array($parent, "002", __("Africa", $slug),   $c | $k | $s);
		$tree[] = array($parent, "019", __("Americas", $slug), $c | $k | $s);
		$tree[] = array($parent, "142", __("Asia", $slug),     $c | $k | $s);
		$tree[] = array($parent, "150", __("Europe", $slug),   $c | $k | $s);
		$tree[] = array($parent, "009", __("Oceania", $slug),  $c | $k | $s);
		
		// Subcontinents
		//  - Africa
		$parent = "002";
		$tree[] = array($parent, "015", __("Northern Africa", $slug), $c | $s);
		$tree[] = array($parent, "011", __("Western Africa", $slug),  $c | $s);
		$tree[] = array($parent, "017", __("Middle Africa", $slug),   $c | $s);
		$tree[] = array($parent, "014", __("Eastern Africa", $slug),  $c | $s);
		$tree[] = array($parent, "018", __("Southern Africa", $slug), $c | $s);
		
		//  - Americas
		$parent = "019";
		$tree[] = array($parent, "021", __("Northern America", $slug), $c | $s);
		$tree[] = array($parent, "029", __("Caribbean", $slug),        $c | $s);
		$tree[] = array($parent, "013", __("Central America", $slug),  $c | $s);
		$tree[] = array($parent, "005", __("South America", $slug),    $c | $s);
		
		// - Asia
		$parent = "142";
		$tree[] = array($parent, "143", __("Central Asia", $slug),       $c | $s);
		$tree[] = array($parent, "030", __("Eastern Asia", $slug),       $c | $s);
		$tree[] = array($parent, "034", __("Southern Asia", $slug),      $c | $s);
		$tree[] = array($parent, "035", __("South-Eastern Asia", $slug), $c | $s);
		$tree[] = array($parent, "145", __("Western Asia", $slug),       $c | $s);
		
		// - Europe
		$parent = "150";
		$tree[] = array($parent, "154", __("Northern Europe", $slug), $c | $s);
		$tree[] = array($parent, "155", __("Western Europe", $slug),  $c | $s);
		$tree[] = array($parent, "151", __("Eastern Europe", $slug),  $c | $s);
		$tree[] = array($parent, "039", __("Southern Europe", $slug), $c | $s);
		
		// - Oceania
		$parent = "009";
		$tree[] = array($parent, "053", __("Australia and New Zealand", $slug), $c | $s);
		$tree[] = array($parent, "054", __("Melanesia", $slug),                 $c | $s);
		$tree[] = array($parent, "057", __("Micronesia", $slug),                $c | $s);
		$tree[] = array($parent, "061", __("Polynesia", $slug),                 $c | $s);
		
		// - North Africa
		$parent = "015";
		$tree[] = array($parent, "DZ", __("Algeria", $slug), $c | $p);
		$tree[] = array($parent, "EG", __("Egypt", $slug), $c | $p);
		$tree[] = array($parent, "EH", __("Western Sahara", $slug), $c);
		$tree[] = array($parent, "LY", __("Libya", $slug), $c | $p);
		$tree[] = array($parent, "MA", __("Morocco", $slug),$c |  $p);
		$tree[] = array($parent, "SD", __("Sudan", $slug), $c | $p);
		$tree[] = array($parent, "TN", __("Tunisia", $slug), $c | $p);
		
		// - Western Africa
		$parent = "011";
		$tree[] = array($parent, "BF", __("Burkina Faso", $slug), $c | $p);
		$tree[] = array($parent, "BJ", __("Benin", $slug), $c | $p);
		$tree[] = array($parent, "CI", __("Côte d'Ivoire", $slug), $c | $p);
		$tree[] = array($parent, "CV", __("Cape Verde", $slug), $c | $p);
		$tree[] = array($parent, "GH", __("Ghana", $slug), $c | $p);
		$tree[] = array($parent, "GM", __("Gambia", $slug), $c | $p);
		$tree[] = array($parent, "GN", __("Guinea", $slug), $c | $p);
		$tree[] = array($parent, "GW", __("Guinea-Bissau", $slug), $c | $p);
		$tree[] = array($parent, "LR", __("Liberia", $slug), $c | $p);
		$tree[] = array($parent, "ML", __("Mali", $slug), $c | $p);
		$tree[] = array($parent, "MR", __("Mauritania", $slug), $c | $p);
		$tree[] = array($parent, "NE", __("Niger", $slug), $c | $p);
		$tree[] = array($parent, "NG", __("Nigeria", $slug), $c | $p);
		$tree[] = array($parent, "SH", __("Saint Helena, Ascension and Tristan da Cunha", $slug), $c | $p);
		$tree[] = array($parent, "SL", __("Sierra Leone", $slug), $c | $p);
		$tree[] = array($parent, "SN", __("Senegal", $slug), $c | $p);
		$tree[] = array($parent, "TG", __("Togo", $slug), $c | $p);
		
		// - Middle Africa   
		$parent = "017";
		$tree[] = array($parent, "AO", __("Angola", $slug), $c | $p);
		$tree[] = array($parent, "CD", __("Congo, the Democratic Republic of the", $slug), $c | $p);
		//$tree[] = array($parent, "ZR", __("Zaire", $slug), $p);
		$tree[] = array($parent, "CF", __("Central African Republic", $slug), $c | $p);
		$tree[] = array($parent, "CG", __("Congo", $slug), $c | $p);
		$tree[] = array($parent, "CM", __("Cameroon", $slug), $c | $p);
		$tree[] = array($parent, "GA", __("Gabon", $slug), $c | $p);
		$tree[] = array($parent, "GQ", __("Equatorial Guinea", $slug), $c | $p);
		$tree[] = array($parent, "ST", __("Sao Tome and Principe", $slug), $c | $p);
		$tree[] = array($parent, "TD", __("Chad", $slug), $c | $p);
 
		// - Eastern Africa  
		$parent = "014";
		$tree[] = array($parent, "BI", __("Burundi", $slug), $c | $p);
		$tree[] = array($parent, "DJ", __("Djibouti", $slug), $c | $p);
		$tree[] = array($parent, "ER", __("Eritrea", $slug), $c | $p);
		$tree[] = array($parent, "ET", __("Ethiopia", $slug), $c | $p);
		$tree[] = array($parent, "KE", __("Kenya", $slug), $c);
		$tree[] = array($parent, "KM", __("Comoros", $slug), $c | $p);
		$tree[] = array($parent, "MG", __("Madagascar", $slug), $c | $p);
		$tree[] = array($parent, "MU", __("Mauritius", $slug), $c | $p);
		$tree[] = array($parent, "MW", __("Malawi", $slug), $c | $p);
		$tree[] = array($parent, "MZ", __("Mozambique", $slug), $c | $p);
		$tree[] = array($parent, "RE", __("Réunion", $slug), $c);
		$tree[] = array($parent, "RW", __("Rwanda", $slug), $c | $p);
		$tree[] = array($parent, "SC", __("Seychelles", $slug), $c | $p);
		$tree[] = array($parent, "SO", __("Somalia", $slug), $c | $p);
		$tree[] = array($parent, "TZ", __("Tanzania", $slug), $c | $p);
		$tree[] = array($parent, "UG", __("Uganda", $slug), $c | $p);
		$tree[] = array($parent, "YT", __("Mayotte", $slug), $c);
		$tree[] = array($parent, "ZM", __("Zambia", $slug), $c | $p);
		$tree[] = array($parent, "ZW", __("Zimbabwe", $slug), $c | $p);

		// - Southern Africa 
		$parent = "018";
		$tree[] = array($parent, "BW", __("Botswana", $slug), $c | $p);
		$tree[] = array($parent, "LS", __("Lesotho", $slug), $c | $p);
		$tree[] = array($parent, "NA", __("Namibia", $slug), $c | $p);
		$tree[] = array($parent, "SZ", __("Swaziland", $slug), $c | $p);
		$tree[] = array($parent, "ZA", __("South Africa", $slug), $c | $p);

		
		// - Northern America 
		$parent = "021";
		$tree[] = array($parent, "BM", __("Bermuda", $slug), $c);
		$tree[] = array($parent, "CA", __("Canada", $slug), $c | $p);
		$tree[] = array($parent, "GL", __("Greenland", $slug), $c | $p);
		$tree[] = array($parent, "PM", __("Saint Pierre and Miquelon", $slug), $c);
		$tree[] = array($parent, "US", __("United States", $slug), $c | $p | $m);

		// - Caribbean        
		$parent = "029";
		$tree[] = array($parent, "AG", __("Antigua and Barbuda", $slug), $c | $p);
		$tree[] = array($parent, "AI", __("Anguilla", $slug), $c);
		//$tree[] = array($parent, "AN", __("Netherlands Antilles", $slug), $p);
		$tree[] = array($parent, "AW", __("Aruba", $slug), $c);
		$tree[] = array($parent, "BB", __("Barbados", $slug), $c | $p);
		$tree[] = array($parent, "BL", __("Saint Barthélemy", $slug), $c);
		$tree[] = array($parent, "BS", __("Bahamas", $slug), $c | $p);
		$tree[] = array($parent, "CU", __("Cuba", $slug), $c | $p);
		$tree[] = array($parent, "DM", __("Dominica", $slug), $c | $p);
		$tree[] = array($parent, "DO", __("Dominican Republic", $slug), $c | $p);
		$tree[] = array($parent, "GD", __("Grenada", $slug), $c | $p);
		$tree[] = array($parent, "GP", __("Guadeloupe", $slug), $c);
		$tree[] = array($parent, "HT", __("Haiti", $slug), $c | $p);
		$tree[] = array($parent, "JM", __("Jamaica", $slug), $c | $p);
		$tree[] = array($parent, "KN", __("Saint Kitts and Nevis", $slug), $c | $p);
		$tree[] = array($parent, "KY", __("Cayman Islands", $slug), $c);
		$tree[] = array($parent, "LC", __("Saint Lucia", $slug), $c | $p);
		$tree[] = array($parent, "MF", __("Saint Martin (French part)", $slug), $c);
		$tree[] = array($parent, "MQ", __("Martinique", $slug), $c);
		$tree[] = array($parent, "MS", __("Montserrat", $slug), $c);
		$tree[] = array($parent, "PR", __("Puerto Rico", $slug), $c);
		$tree[] = array($parent, "TC", __("Turks and Caicos Islands", $slug), $c);
		$tree[] = array($parent, "TT", __("Trinidad and Tobago", $slug), $c | $p);
		$tree[] = array($parent, "VC", __("Saint Vincent and the Grenadines", $slug), $c | $p);
		$tree[] = array($parent, "VG", __("Virgin Islands, British", $slug), $c);
		$tree[] = array($parent, "VI", __("Virgin Islands, U.S.", $slug), $c);

		// - Central America  
		$parent = "013";
		$tree[] = array($parent, "BZ", __("Belize", $slug), $c | $p);
		$tree[] = array($parent, "CR", __("Costa Rica", $slug), $c | $p);
		$tree[] = array($parent, "GT", __("Guatemala", $slug), $c | $p);
		$tree[] = array($parent, "HN", __("Honduras", $slug), $c | $p);
		$tree[] = array($parent, "MX", __("Mexico", $slug), $c | $p);
		$tree[] = array($parent, "NI", __("Nicaragua", $slug), $c | $p);
		$tree[] = array($parent, "PA", __("Panama", $slug), $c | $p);
		$tree[] = array($parent, "SV", __("El Salvador", $slug), $c | $p);

		// - South America    
		$parent = "005";
		$tree[] = array($parent, "AR", __("Argentina", $slug), $c | $p);
		$tree[] = array($parent, "BO", __("Bolivia", $slug), $c | $p);
		$tree[] = array($parent, "BR", __("Brazil", $slug), $c | $p);
		$tree[] = array($parent, "CL", __("Chile", $slug), $c | $p);
		$tree[] = array($parent, "CO", __("Colombia", $slug), $c | $p);
		$tree[] = array($parent, "EC", __("Ecuador", $slug), $c | $p);
		$tree[] = array($parent, "FK", __("Falkland Islands (Malvinas)", $slug), $c);
		$tree[] = array($parent, "GF", __("French Guiana", $slug), $c);
		$tree[] = array($parent, "GY", __("Guyana", $slug), $c | $p);
		$tree[] = array($parent, "PE", __("Peru", $slug), $c | $p);
		$tree[] = array($parent, "PY", __("Paraguay", $slug), $c | $p);
		$tree[] = array($parent, "SR", __("Suriname", $slug), $c | $p);
		$tree[] = array($parent, "UY", __("Uruguay", $slug), $c | $p);
		$tree[] = array($parent, "VE", __("Venezuela", $slug), $c | $p);


		// - Central Asia       
		$parent = "143";
		$tree[] = array($parent, "TM", __("Turkmenistan", $slug), $c | $p);
		$tree[] = array($parent, "TJ", __("Tajikistan", $slug), $c | $p);
		$tree[] = array($parent, "KG", __("Kyrgyzstan", $slug), $c | $p);
		$tree[] = array($parent, "KZ", __("Kazakhstan", $slug), $c | $p);
		$tree[] = array($parent, "UZ", __("Uzbekistan", $slug), $c | $p);

		// - Eastern Asia       
		$parent = "030";
		$tree[] = array($parent, "CN", __("China", $slug), $c | $p);
		$tree[] = array($parent, "HK", __("Hong Kong", $slug), $c);
		$tree[] = array($parent, "JP", __("Japan", $slug), $c | $p);
		$tree[] = array($parent, "KP", __("North Korea", $slug), $c | $p);
		$tree[] = array($parent, "KR", __("South Korea", $slug), $c | $p);
		$tree[] = array($parent, "MN", __("Mongolia", $slug), $c | $p);
		$tree[] = array($parent, "MO", __("Macao", $slug), $c);
		$tree[] = array($parent, "TW", __("Taiwan", $slug), $c);

		// - Southern Asia      
		$parent = "034";
		$tree[] = array($parent, "AF", __("Afghanistan", $slug), $c |$p);
		$tree[] = array($parent, "BD", __("Bangladesh", $slug), $c |$p);
		$tree[] = array($parent, "BT", __("Bhutan", $slug), $c | $p);
		$tree[] = array($parent, "IN", __("India", $slug), $c | $p);
		$tree[] = array($parent, "IR", __("Iran", $slug), $c |$p);
		$tree[] = array($parent, "LK", __("Sri Lanka", $slug), $c |$p);
		$tree[] = array($parent, "MV", __("Maldives", $slug), $c |$p);
		$tree[] = array($parent, "NP", __("Nepal", $slug), $c | $p);
		$tree[] = array($parent, "PK", __("Pakistan", $slug), $c |$p);

		// - South-Eastern Asia 
		$parent = "035";
		$tree[] = array($parent, "BN", __("Brunei", $slug), $c | $p);
		$tree[] = array($parent, "ID", __("Indonesia", $slug), $c | $p);
		$tree[] = array($parent, "KH", __("Cambodia", $slug), $c | $p);
		$tree[] = array($parent, "LA", __("Laos", $slug), $c | $p);
		$tree[] = array($parent, "MM", __("Myanmar", $slug), $c | $p);
		//$tree[] = array($parent, "BU", __("Burma", $slug), $c | $p);
		$tree[] = array($parent, "MY", __("Malaysia", $slug), $c | $p);
		$tree[] = array($parent, "PH", __("Philippines", $slug), $c | $p);
		$tree[] = array($parent, "SG", __("Singapore", $slug), $c | $p);
		$tree[] = array($parent, "TH", __("Thailand", $slug), $c | $p);
		$tree[] = array($parent, "TL", __("Timor-Leste", $slug), $c | $p);
		//$tree[] = array($parent, "TP", __("East Timor", $slug), $c | $p);
		$tree[] = array($parent, "VN", __("Vietnam", $slug), $c | $p);

		// - Western Asia       
		$parent = "145";
		$tree[] = array($parent, "AE", __("United Arab Emirates", $slug), $c | $p);
		$tree[] = array($parent, "AM", __("Armenia", $slug), $c | $p);
		$tree[] = array($parent, "AZ", __("Azerbaijan", $slug), $c | $p);
		$tree[] = array($parent, "BH", __("Bahrain", $slug), $c | $p);
		$tree[] = array($parent, "CY", __("Cyprus", $slug), $c | $p);
		$tree[] = array($parent, "GE", _x("Georgia", "Country", $slug), $c | $p);
		$tree[] = array($parent, "IL", __("Israel", $slug), $c | $p);
		$tree[] = array($parent, "IQ", __("Iraq", $slug), $c | $p);
		$tree[] = array($parent, "JO", __("Jordan", $slug), $c | $p);
		$tree[] = array($parent, "KW", __("Kuwait", $slug), $c | $p);
		$tree[] = array($parent, "LB", __("Lebanon", $slug), $c | $p);
		$tree[] = array($parent, "OM", __("Oman", $slug), $c | $p);
		$tree[] = array($parent, "PS", __("Palestine, State of", $slug), $c);
		$tree[] = array($parent, "QA", __("Qatar", $slug), $c | $p);
		$tree[] = array($parent, "SA", __("Saudi Arabia", $slug), $c | $p);
		//$tree[] = array($parent, "NT", __("Neutral Zone", $slug), $p);
		$tree[] = array($parent, "SY", __("Syria", $slug), $c | $p);
		$tree[] = array($parent, "TR", __("Turkey", $slug), $c | $p);
		$tree[] = array($parent, "YE", __("Yemen", $slug), $c | $p);
		//$tree[] = array($parent, "YD", __("South Yemen", $slug), $p);
		
		// - Northern Europe 
		$parent = "154";
		$tree[] = array($parent, "GG", __("Guernsey", $slug), $c);
		$tree[] = array($parent, "JE", __("Jersey", $slug), $c);
		$tree[] = array($parent, "AX", __("Åland", $slug), $c);
		$tree[] = array($parent, "DK", __("Denmark", $slug), $c | $p);
		$tree[] = array($parent, "EE", __("Estonia", $slug), $c | $p);
		$tree[] = array($parent, "FI", __("Finland", $slug), $c | $p);
		$tree[] = array($parent, "FO", __("Faroe Islands", $slug), $c);
		$tree[] = array($parent, "GB", __("United Kingdom", $slug), $c | $p);
		$tree[] = array($parent, "IE", __("Ireland", $slug), $c | $p);
		$tree[] = array($parent, "IM", __("Isle of Man", $slug), $c);
		$tree[] = array($parent, "IS", __("Iceland", $slug), $c | $p);
		$tree[] = array($parent, "LT", __("Lithuania", $slug), $c | $p);
		$tree[] = array($parent, "LV", __("Latvia", $slug), $c | $p);
		$tree[] = array($parent, "NO", __("Norway", $slug), $c | $p);
		$tree[] = array($parent, "SE", __("Sweden", $slug), $c | $p);
		$tree[] = array($parent, "SJ", __("Svalbard and Jan Mayen", $slug), $c);

		// - Western Europe  
		$parent = "155";
		$tree[] = array($parent, "AT", __("Austria", $slug), $c | $p);
		$tree[] = array($parent, "BE", __("Belgium", $slug), $c | $p);
		$tree[] = array($parent, "CH", __("Switzerland", $slug), $c | $p);
		$tree[] = array($parent, "DE", __("Germany", $slug), $c | $p);
		//$tree[] = array($parent, "DD", __("German Democratic Republic", $slug), $p);
		$tree[] = array($parent, "FR", __("France", $slug), $c | $p);
		//$tree[] = array($parent, "FX", __("France, Metropolitan", $slug), $p);
		$tree[] = array($parent, "LI", __("Liechtenstein", $slug), $c | $p);
		$tree[] = array($parent, "LU", __("Luxembourg", $slug), $c | $p);
		$tree[] = array($parent, "MC", __("Monaco", $slug), $c);
		$tree[] = array($parent, "NL", __("Netherlands", $slug), $c | $p);

		// - Eastern Europe  
		$parent = "151";
		$tree[] = array($parent, "BG", __("Bulgaria", $slug), $c | $p);
		$tree[] = array($parent, "BY", __("Belarus", $slug), $c | $p);
		$tree[] = array($parent, "CZ", __("Czech Republic", $slug), $c | $p);
		$tree[] = array($parent, "HU", __("Hungary", $slug), $c | $p);
		$tree[] = array($parent, "MD", __("Moldova", $slug), $c | $p);
		$tree[] = array($parent, "PL", __("Poland", $slug), $c | $p);
		$tree[] = array($parent, "RO", __("Romania", $slug), $c | $p);
		$tree[] = array($parent, "RU", __("Russia", $slug), $c | $p);
		//$tree[] = array($parent, "SU", __("USSR", $slug), $p);
		$tree[] = array($parent, "SK", __("Slovakia", $slug), $c | $p);
		$tree[] = array($parent, "UA", __("Ukraine", $slug), $c | $p);

		// - Southern Europe 
		$parent = "039";
		$tree[] = array($parent, "AD", __("Andorra", $slug), $c | $p);
		$tree[] = array($parent, "AL", __("Albania", $slug), $c | $p);
		$tree[] = array($parent, "BA", __("Bosnia and Herzegovina", $slug), $c | $p);
		$tree[] = array($parent, "ES", __("Spain", $slug), $c | $p);
		$tree[] = array($parent, "GI", __("Gibraltar", $slug), $c);
		$tree[] = array($parent, "GR", __("Greece", $slug), $c | $p);
		$tree[] = array($parent, "HR", __("Croatia", $slug), $c | $p);
		$tree[] = array($parent, "IT", __("Italy", $slug), $c | $p);
		$tree[] = array($parent, "ME", __("Montenegro", $slug), $c | $p);
		$tree[] = array($parent, "MK", __("Macedonia", $slug), $c | $p);
		$tree[] = array($parent, "MT", __("Malta", $slug), $c);
		//$tree[] = array($parent, "CS", __("Serbia and Montenegro", $slug), $p);
		$tree[] = array($parent, "RS", __("Serbia", $slug), $c | $p);
		$tree[] = array($parent, "PT", __("Portugal", $slug), $c | $p);
		$tree[] = array($parent, "SI", __("Slovenia", $slug), $c | $p);
		$tree[] = array($parent, "SM", __("San Marino", $slug), $c | $p);
		$tree[] = array($parent, "VA", __("Holy See (Vatican City State)", $slug), $c);
		//$tree[] = array($parent, "YU", __("Yugoslavia", $slug), $p);
		
		// - Australia and New Zealand 
		$parent = "053";
		$tree[] = array($parent, "AU", __("Australia", $slug), $c | $p);
		$tree[] = array($parent, "NF", __("Norfolk Island", $slug), $c);
		$tree[] = array($parent, "NZ", __("New Zealand", $slug), $c | $p);

		// - Melanesia                 
		$parent = "054";
		$tree[] = array($parent, "FJ", __("Fiji", $slug), $c | $p);
		$tree[] = array($parent, "NC", __("New Caledonia", $slug), $c);
		$tree[] = array($parent, "PG", __("Papua New Guinea", $slug), $c | $p);
		$tree[] = array($parent, "SB", __("Solomon Islands", $slug), $c | $p);
		$tree[] = array($parent, "VU", __("Vanuatu", $slug), $c | $p);

		// - Micronesia                
		$parent = "057";
		$tree[] = array($parent, "FM", __("Micronesia, Federated States of", $slug), $c | $p);
		$tree[] = array($parent, "GU", __("Guam", $slug), $c);
		$tree[] = array($parent, "KI", __("Kiribati", $slug), $c | $p);
		$tree[] = array($parent, "MH", __("Marshall Islands", $slug), $c | $p);
		$tree[] = array($parent, "MP", __("Northern Mariana Islands", $slug), $c);
		$tree[] = array($parent, "NR", __("Nauru", $slug), $c | $p);
		$tree[] = array($parent, "PW", __("Palau", $slug), $c | $p);

		// - Polynesia                 
		$parent = "061";
		$tree[] = array($parent, "AS", __("American Samoa", $slug), $c);
		$tree[] = array($parent, "CK", __("Cook Islands", $slug), $c);
		$tree[] = array($parent, "NU", __("Niue", $slug), $c);
		$tree[] = array($parent, "PF", __("French Polynesia", $slug), $c);
		$tree[] = array($parent, "PN", __("Pitcairn", $slug), $c);
		$tree[] = array($parent, "TK", __("Tokelau", $slug), $c);
		$tree[] = array($parent, "TO", __("Tonga", $slug), $c | $p);
		$tree[] = array($parent, "TV", __("Tuvalu", $slug), $c | $p);
		$tree[] = array($parent, "WF", __("Wallis and Futuna", $slug), $c);
		$tree[] = array($parent, "WS", __("Samoa", $slug), $c);
		
		// - US-States
		$parent = "US";
		$tree[] = array($parent, "US-AL", __("Alabama", $slug), $p | $m);
		$tree[] = array($parent, "US-AK", __("Alaska", $slug), $p | $m);
		$tree[] = array($parent, "US-AZ", __("Arizona", $slug), $p | $m);
		$tree[] = array($parent, "US-AR", __("Arkansas", $slug), $p | $m);
		$tree[] = array($parent, "US-CA", __("California", $slug), $p | $m);
		$tree[] = array($parent, "US-CO", __("Colorado", $slug), $p | $m);
		$tree[] = array($parent, "US-CT", __("Connecticut", $slug), $p | $m);
		$tree[] = array($parent, "US-DE", __("Delaware", $slug), $p | $m);
		$tree[] = array($parent, "US-FL", __("Florida", $slug), $p | $m);
		$tree[] = array($parent, "US-GA", _x("Georgia", "US-State", $slug), $p | $m);
		$tree[] = array($parent, "US-HI", __("Hawaii", $slug), $p | $m);
		$tree[] = array($parent, "US-ID", __("Idaho", $slug), $p | $m);
		$tree[] = array($parent, "US-IL", __("Illinois", $slug), $p | $m);
		$tree[] = array($parent, "US-IN", __("Indiana", $slug), $p | $m);
		$tree[] = array($parent, "US-IA", __("Iowa", $slug), $p | $m);
		$tree[] = array($parent, "US-KS", __("Kansas", $slug), $p | $m);
		$tree[] = array($parent, "US-KY", __("Kentucky", $slug), $p | $m);
		$tree[] = array($parent, "US-LA", __("Louisiana", $slug), $p | $m);
		$tree[] = array($parent, "US-ME", __("Maine", $slug), $p | $m);
		$tree[] = array($parent, "US-MD", __("Maryland", $slug), $p | $m);
		$tree[] = array($parent, "US-MA", __("Massachusetts", $slug), $p | $m);
		$tree[] = array($parent, "US-MI", __("Michigan", $slug), $p | $m);
		$tree[] = array($parent, "US-MN", __("Minnesota", $slug), $p | $m);
		$tree[] = array($parent, "US-MS", __("Mississippi", $slug), $p | $m);
		$tree[] = array($parent, "US-MO", __("Missouri", $slug), $p | $m);
		$tree[] = array($parent, "US-MT", __("Montana", $slug), $p | $m);
		$tree[] = array($parent, "US-NE", __("Nebraska", $slug), $p | $m);
		$tree[] = array($parent, "US-NV", __("Nevada", $slug), $p | $m);
		$tree[] = array($parent, "US-NH", __("New Hampshire", $slug), $p | $m);
		$tree[] = array($parent, "US-NJ", __("New Jersey", $slug), $p | $m);
		$tree[] = array($parent, "US-NM", __("New Mexico", $slug), $p | $m);
		$tree[] = array($parent, "US-NY", __("New York", $slug), $p | $m);
		$tree[] = array($parent, "US-NC", __("North Carolina", $slug), $p | $m);
		$tree[] = array($parent, "US-ND", __("North Dakota", $slug), $p | $m);
		$tree[] = array($parent, "US-OH", __("Ohio", $slug), $p | $m);
		$tree[] = array($parent, "US-OK", __("Oklahoma", $slug), $p | $m);
		$tree[] = array($parent, "US-OR", __("Oregon", $slug), $p | $m);
		$tree[] = array($parent, "US-PA", __("Pennsylvania", $slug), $p | $m);
		$tree[] = array($parent, "US-RI", __("Rhode Island", $slug), $p | $m);
		$tree[] = array($parent, "US-SC", __("South Carolina", $slug), $p | $m);
		$tree[] = array($parent, "US-SD", __("South Dakota", $slug), $p | $m);
		$tree[] = array($parent, "US-TN", __("Tennessee", $slug), $p | $m);
		$tree[] = array($parent, "US-TX", __("Texas", $slug), $p | $m);
		$tree[] = array($parent, "US-UT", __("Utah", $slug), $p | $m);
		$tree[] = array($parent, "US-VT", __("Vermont", $slug), $p | $m);
		$tree[] = array($parent, "US-VA", __("Virginia", $slug), $p | $m);
		$tree[] = array($parent, "US-WA", __("Washington", $slug), $p | $m);
		$tree[] = array($parent, "US-WV", __("West Virginia", $slug), $p | $m);
		$tree[] = array($parent, "US-WI", __("Wisconsin", $slug), $p | $m);
		$tree[] = array($parent, "US-WY", __("Wyoming", $slug), $p | $m);
		$tree[] = array($parent, "US-DC", __("District of Columbia", $slug), $p | $m);
		//$tree[] = array($parent, "US-AS", __("American Samoa", $slug), $p | $m);
		//$tree[] = array($parent, "US-GU", __("Guam", $slug), $p | $m);
		//$tree[] = array($parent, "US-MP", __("Northern Mariana Islands", $slug), $p | $m);
		//$tree[] = array($parent, "US-PR", __("Puerto Rico", $slug), $p | $m);
		//$tree[] = array($parent, "US-UM", __("United States Minor Outlying Islands", $slug), $p | $m);
		//$tree[] = array($parent, "US-VI", __("Virgin Islands, U.S.", $slug), $p | $m);
		
		return $tree;
	}
	
}

?>