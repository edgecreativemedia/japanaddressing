<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
date_default_timezone_set('UTC');

ini_set('display_errors', 1);
error_reporting(~0);

//settings
$config = getSettings();
$config['dir_path'] = realpath(__DIR__ . '');
$config['repository_path'] = realpath(__DIR__ . '/../').'/resources';
$config['source_path'] = realpath(__DIR__ . '').'/data/source';
//$config['repository_path']['postcode'] = realpath(__DIR__ . '').'/resources/postcode/';

//Display Title

echo "\n";
echo "************************************************ \n"; 
echo "*                                              * \n";
echo "*  JAPAN ADDRESSING REPOSITORY UPDATE UTILITY  * \n";
echo "*                                              * \n";  
echo "************************************************";  
echo "\n";
echo "\n";

//check for option argument.
if(!isset($argv[1])){$option = "";}else{$option = $argv[1];};

switch ($option) {

  //CHECK Option
  case "-c":

	foreach($config['settings']['csv_primary'] as $item => $value){
		if($value == "TRUE") {

    		$localDirFile = $config['dir_path'].$config['settings']['csv_local_url'][$item];
    		$remoteDirFile = $config['settings']['csv_remote_url'][$item];
			$remoteFileDate = GetRemoteLastModified($remoteDirFile);
			$localFileDate = GetLocalLastModified($localDirFile);    		
 
 			//Compare dates. If remote file is newer then display.
 			$updateText = "";
			if(compareDates($remoteFileDate, $localFileDate) == TRUE ) {
				$updateText = "  <=== NEW UPDATE AVALIBLE!!";
			}
 
       		$text  = "CHECKING REMOTE SERVER FOR NEW CSV SOURCE FILES \n\n";
  			$text .= "Filename: ".$remoteDirFile."\n";
			$text .= "Remote Version: ".formatDate($remoteFileDate).$updateText."\n";
			$text .= "Local Version:  ".formatDate($localFileDate)."\n\n";		
		}
	}

	foreach($config['settings']['csv_secondary'] as $item => $value){
		if($value == "TRUE") {

    		$localDirFile = $config['dir_path'].$config['settings']['csv_local_url'][$item];
    		$remoteDirFile = $config['settings']['csv_remote_url'][$item];
			$remoteFileDate = GetRemoteLastModified($remoteDirFile);
			$localFileDate = GetLocalLastModified($localDirFile);    
 
 			//Compare dates. If remote file is newer then display.
 			$updateText = ""; 			
			if(compareDates($remoteFileDate, $localFileDate) == TRUE ) {
				$updateText = "  <=== NEW UPDATE AVALIBLE!!";
			}			
 					
  			$text .= "Filename: ".$remoteDirFile."\n";
			$text .= "Remote Version: ".formatDate($remoteFileDate).$updateText."\n";
			$text .= "Local Version:  ".formatDate($localFileDate)."\n\n";		
		}
	}
  		
	echo $text;
  break;

  //DOWNLOAD Option  
  case "-d":
  	//TODO
	$text  = "Download new updates.\n\n";
	$text .= "\n\n";
	$text .= "TODO. \n\n";
	echo $text;
  break;

  //HELP Option
  case "-h":
	help();
  break;

  //SETTINGS Option
  case "-s":
  
	foreach($config['settings']['csv_primary'] as $item => $value){
		if($value == "TRUE") {
			$primary_csv_file = $config['settings']['csv_local_url'][$item];
		}  	
  	}
	foreach($config['settings']['csv_secondary'] as $item => $value){
		if($value == "TRUE") {
			$secondary_csv_file = $config['settings']['csv_local_url'][$item];
		}  	
  	}  	
    $text  = "SETTINGS \n";
    $text .= "-------- \n\n";   
	$text .= "Country Code:          ".$config['country_code']."\n";
	$text .= "Locale:                ".$config['locale']."\n";
	$text .= "Repository Path:       ".$config['repository_path']."\n";
	$text .= "Source Path:           ".$config['source_path']."\n";
	$text .= "Primary Source File:   ".$config['dir_path'].$primary_csv_file."\n";	
	$text .= "Secondary Source File: ".$config['dir_path'].$secondary_csv_file."\n";
	$text .= "\n\n"; 
	echo $text;

  break;

  //UPDATE Option
  case "-u":
    $text  = "UPDATING REPOSITORY \n";
    $text .= "------------------- \n\n";     
	echo $text;
	
	//TODO:
	//Add check for new updates and download if required. 
	
	//generate files
	generateAllFiles($config);
	
  break;

  //DEFAULT Option  	  
  default:
  	help();
  break;
}

/**
  * General help.
  */
function help() {

echo "Usage: php update-repository.php [-o]\n";

echo "\n";
echo "  -c    Check for lastest CSV source zip files. \n";
echo "  -d    Download lastest CSV source zip files. \n";
echo "  -h    This help. \n";
echo "  -s    Settings. \n";
echo "  -u    Updates the repository. \n";
echo "\n";
echo "\n";
}

/**
  * Generates All Json files from raw data.
  *
  * @return.
  */
function generateAllFiles($config) {

  $countryCode = $config['country_code'];
  $langLocale = $config['locale'];
  $jsonPathPostalAddress = $config['repository_path'].'/postal_address';
  $jsonPathSubdivision = $config['repository_path'].'/subdivision';
  

  echo "\n"; 
  echo "Loading CSV file..."; 
  echo "\n";

  //Load primary and secondary source files if True in config/settings file. 
  foreach($config['settings']['csv_primary'] as $item => $value){
	if($value == "TRUE") {
	  $primary_csv_file = $config['dir_path'].$config['settings']['csv_local_url'][$item];
	  $primarySourceData = _loadRawData($primary_csv_file, $item);			
	}  	
  }
  	
  foreach($config['settings']['csv_secondary'] as $item => $value){
	if($value == "TRUE") {
	  $secondary_csv_file = $config['dir_path'].$config['settings']['csv_local_url'][$item];
	  $secondarySourceData = _loadRawData($secondary_csv_file, $item);			
	}  	
  } 
  
  if(isset($secondarySourceData)) {
    $allSourceData = array_merge($primarySourceData,$secondarySourceData);
    echo "Opening Primary & Secondary Source Data.\n";
    }else{
    $allSourceData = $primarySourceData;
    echo "Opening Primary Source Data Only.\n";
  }
  
  //remove duplicate entries and key by postcode
  $postalData = array();
  foreach($allSourceData as $item) {
	$postcode = $item['postcode'];
	if (!array_key_exists($postcode, $postalData)) {
    	$postalData[$postcode] = $item; 	  
	}	 
  }	
  
  ksort($postalData);	

  if($postalData !== FALSE) {
  
	//Array of translationss for Prefecture & city using current data if ALL_KEN_ROMA = TRUE in Setting/config file.
	if($config['settings']['csv_primary']["KEN_ALL_ROME"] == "TRUE") {
    
		$translationsList = array();  
		krsort($postalData);      	
    	foreach($postalData as $transItem) {

			if(isset($transItem['translations']['ja'])) {    				
    				$prefecture_code = $transItem['translations']['ja']['prefecture_name'];
    				$city_code = $transItem['translations']['ja']['city_name'];
    				$ward_code = $transItem['translations']['ja']['ward_name'];
    				$cityward_code = $city_code.''.$ward_code;
    				
    				$translationsList[$prefecture_code][$cityward_code]['prefecture_name'] = $transItem['prefecture_name'];
    				$translationsList[$prefecture_code][$cityward_code]['city_name'] = $transItem['city_name'];
    				$translationsList[$prefecture_code][$cityward_code]['ward_name'] = $transItem['ward_name'];
    				$translationsList[$prefecture_code][$cityward_code]['town_name'] = $transItem['town_name'];
    				$translationsList[$prefecture_code][$cityward_code]['translations']['ja']['prefecture_name'] = $transItem['translations']['ja']['prefecture_name'];
    				$translationsList[$prefecture_code][$cityward_code]['translations']['ja']['city_name'] = $transItem['translations']['ja']['city_name'];
    				$translationsList[$prefecture_code][$cityward_code]['translations']['ja']['ward_name'] = $transItem['translations']['ja']['ward_name'];
    				$translationsList[$prefecture_code][$cityward_code]['translations']['ja']['town_name'] = $transItem['translations']['ja']['town_name'];			
			}		
		}	
    }
    
    //Add header array variables
	$fullData = array();
	$cityRepository = array();
	$prefecturesList = getPrefecturesList($config);

    foreach($postalData as $item) {
	  $parent_id = substr($item['postcode'],0,3);
	  $id = $item['postcode'];
	  $fullData[$parent_id]['country_code'] = $countryCode;
	  $fullData[$parent_id]['parent_id'] = $parent_id;
	  $fullData[$parent_id]['locale'] = $langLocale;
	  $fullData[$parent_id]['address'][$id] = $item;
	  $fullData[$parent_id]['address'][$id]['postcode'] = _clean_postcode_string($item['postcode']);
	  
	  
	  //add region, prefecture and city codes.	  
	  if(!isset($fullData[$parent_id]['address'][$id]['translations']['ja'])) {
	    $item_prefecture_code = $fullData[$parent_id]['address'][$id]['prefecture_name'];
	  	$fullData[$parent_id]['address'][$id]['region_code'] = $prefecturesList[$item_prefecture_code]['region_code'];
	  	$fullData[$parent_id]['address'][$id]['prefecture_code'] = $fullData[$parent_id]['address'][$id]['prefecture_name'];
	  	$fullData[$parent_id]['address'][$id]['city_code'] = $fullData[$parent_id]['address'][$id]['city_name'];	  	
	  } else {
	    $item_prefecture_code = $fullData[$parent_id]['address'][$id]['translations']['ja']['prefecture_name'];
	    $fullData[$parent_id]['address'][$id]['region_code'] = $prefecturesList[$item_prefecture_code]['region_code'];
	  	$fullData[$parent_id]['address'][$id]['prefecture_code'] = $fullData[$parent_id]['address'][$id]['translations']['ja']['prefecture_name'];
	  	$fullData[$parent_id]['address'][$id]['city_code'] = $fullData[$parent_id]['address'][$id]['translations']['ja']['city_name'];	  
	  }
	  
	  	
	  //check for translations data, if no data then use translationsList to get translations. 
	  //Translates prefecture, city, ward. Town is not translated for all. Building and organization is not translated. 
	  if($config['settings']['csv_primary']["KEN_ALL_ROME"] == "TRUE") {			
	    //only add for missing translations
		if(!isset($fullData[$parent_id]['address'][$id]['translations']['ja'])) {
				
		  $current_prefecture_name = $fullData[$parent_id]['address'][$id]['prefecture_name'];
		  $current_city_name = $fullData[$parent_id]['address'][$id]['city_name'];
		  $cityward_code =  $translationsList[$current_prefecture_name][$current_city_name]['translations']['ja']['city_name'].$translationsList[$current_prefecture_name][$current_city_name]['translations']['ja']['ward_name'];

		  if($current_city_name == $cityward_code) {

			$fullData[$parent_id]['address'][$id]['translations']['ja']['prefecture_name'] = $current_prefecture_name;
			$fullData[$parent_id]['address'][$id]['translations']['ja']['city_name'] = $translationsList[$current_prefecture_name][$current_city_name]['translations']['ja']['city_name'];
			$fullData[$parent_id]['address'][$id]['translations']['ja']['ward_name'] = $translationsList[$current_prefecture_name][$current_city_name]['translations']['ja']['ward_name'];
			$fullData[$parent_id]['address'][$id]['translations']['ja']['town_name'] = $fullData[$parent_id]['address'][$id]['town_name'];
			$fullData[$parent_id]['address'][$id]['translations']['ja']['building_name'] = $fullData[$parent_id]['address'][$id]['building_name'];
			$fullData[$parent_id]['address'][$id]['translations']['ja']['organization_name'] = $fullData[$parent_id]['address'][$id]['organization_name'];
			$fullData[$parent_id]['address'][$id]['prefecture_name'] = $translationsList[$current_prefecture_name][$current_city_name]['prefecture_name'];
			$fullData[$parent_id]['address'][$id]['city_name'] = $translationsList[$current_prefecture_name][$current_city_name]['city_name'];
			$fullData[$parent_id]['address'][$id]['ward_name'] = $translationsList[$current_prefecture_name][$current_city_name]['ward_name'];
			if($fullData[$parent_id]['address'][$id]['town_name'] == $translationsList[$current_prefecture_name][$current_city_name]['translations']['ja']['town_name']) {
			  $fullData[$parent_id]['address'][$id]['town_name'] = $translationsList[$current_prefecture_name][$current_city_name]['town_name'];
			}
			//update city codes. Due to translations of city/ward code.
	  		$fullData[$parent_id]['address'][$id]['city_code'] = $translationsList[$current_prefecture_name][$current_city_name]['translations']['ja']['city_name'];				
			
		  }			
		}
		//create cityData array for generating japancity.json repository
		
		$itemCityCode = $fullData[$parent_id]['address'][$id]['city_code'];
		
		$cityRepository['subdivision'][$itemCityCode]['region_code'] = $fullData[$parent_id]['address'][$id]['region_code'];
		$cityRepository['subdivision'][$itemCityCode]['prefecture_code'] = $fullData[$parent_id]['address'][$id]['prefecture_code'];
		$cityRepository['subdivision'][$itemCityCode]['code'] = $fullData[$parent_id]['address'][$id]['city_code'];
		$cityRepository['subdivision'][$itemCityCode]['lname'] = clean_city_repository_string($fullData[$parent_id]['address'][$id]['city_name'], 'en-lname');
		$cityRepository['subdivision'][$itemCityCode]['sname'] = clean_city_repository_string($fullData[$parent_id]['address'][$id]['city_name'], 'en-sname');
		$cityRepository['subdivision'][$itemCityCode]['kanji'] = $fullData[$parent_id]['address'][$id]['translations']['ja']['city_name'];
		$cityRepository['subdivision'][$itemCityCode]['hiragana'] = "";
		$cityRepository['subdivision'][$itemCityCode]['romaji'] = $fullData[$parent_id]['address'][$id]['city_name'];
		$cityRepository['subdivision'][$itemCityCode]['translations']['ja']['lname'] = clean_city_repository_string($fullData[$parent_id]['address'][$id]['translations']['ja']['city_name'], 'ja-lname');
		$cityRepository['subdivision'][$itemCityCode]['translations']['ja']['sname'] = clean_city_repository_string($fullData[$parent_id]['address'][$id]['translations']['ja']['city_name'], 'ja-sname');
	  }		       
	}

//print_r($fullData);

	createCityRepository($config, $cityRepository);

	//Create new json file for each postcode (001-XXXX) array (e.g. JP-001.json).
    foreach($fullData as $fileItem) {		
	  $jsonFilename = $jsonPathPostalAddress.'/'.$fileItem['country_code'].'-'. $fileItem['parent_id'] .'.json';
	  // Create json file for each item.
	  _create_json_file($jsonFilename, $fileItem);
	  echo "File Created: ".$jsonFilename."\n";		       
	}
		
	echo "\n";
	echo "UPDATE COMPLETE.\n";
	echo "\n\n";
	
 } else {  
	echo "\n";
	echo "ERROR: The CSV file had no data.";
	echo "\n";
 }
}



/**
  * Loads the raw csv data.
  *
  * @return array of raw data.
  */
function _loadRawData($filePath, $type) {
 
  if (!file_exists($filePath)) {  
    echo '\n';
    echo '******************************* /n'; 
    echo '* ERROR: Cannot find the file * /n';
    echo '******************************* /n';  
    echo '\n';  
    echo 'Missing file: '.$filePath;
    echo '\n'; 
    return FALSE;
  }

  $f = fopen($filePath, 'r');
  if (!$f) {    
    echo '\n';
    echo '******************************* /n'; 
    echo '* ERROR: Cannot open the file * /n';
    echo '******************************* /n';  
    echo '\n';  
    echo 'File: '.$filePath;
    echo '\n';    
    return FALSE;
  }

  $postalData = array();
		
  while ($row = fgetcsv($f)) {
  	
  	//convert to UTF-8
  	$row = array_map( "convertShiftJis", $row );
  
  	switch ($type) {
		case "KEN_ALL_ROME":
		$id = $row[0];
		break;
		
		case "KEN_ALL":
		$id = $row[2];
		break;
		
		case "JIGYOSYO":

		
		//Fix import error on JIGYOSYO.CSV.
		//Sometimes row array is offset due to unsual or extra characters when decoding.
		//So we remove the first 2 columns of the array that are not required and cause the problems.
		array_shift($row);
		array_shift($row);
		// error caused in encoding missing " character, thus messing up column order (e.g. メガバンク機構",宮城県"	).	
		
		//repair if problem.	
		$brace_index = mb_strpos($row[0], '",');  	    
  	    if ($brace_index !== FALSE) {
	    	$new_col = explode('",', $row[0]);
			//remove problem column
			array_shift($row);		
			//add new col
			array_unshift($row, $new_col[0], $new_col[1]);
  	  	}
  	  		
		$brace_index = mb_strpos($row[1], '",');  	    
  	    if ($brace_index !== FALSE) {
	    	$new_col = explode('",', $row[1]);
			
			$row[8] = $row[7];
			$row[7] = $row[6];
			$row[6] = $row[5];
			$row[5] = $row[4];
			$row[4] = $row[3];
			$row[3] = $row[2];
			$row[2] = $new_col[1];
			$row[1] = $new_col[0];
			$row[0] = $row[0];
  	  	}
  	  	//remove unwanted " in string	  		
  	  	$row[1] = str_replace('"', '', $row[1]);
  	  	
		$brace_index = mb_strpos($row[2], '",');  	    
  	    if ($brace_index !== FALSE) {
	    	$new_col = explode('",', $row[2]);
			
			$row[8] = $row[7];
			$row[7] = $row[6];
			$row[6] = $row[5];
			$row[5] = $row[4];
			$row[4] = $row[3];
			$row[3] = $new_col[1];
			$row[2] = $new_col[0];
			$row[1] = $row[1];
			$row[0] = $row[0];
  	  	}  	  		
		$brace_index = mb_strpos($row[3], '",');  	    
  	    if ($brace_index !== FALSE) {
	    	$new_col = explode('",', $row[3]);

			$row[8] = $row[7];
			$row[7] = $row[6];
			$row[6] = $row[5];
			$row[5] = $row[4];
			$row[4] = $new_col[1];
			$row[3] = $new_col[0];
			$row[2] = $row[2];
			$row[1] = $row[1];
			$row[0] = $row[0];
  	  	} 
		
		$id = $row[5];
		
		break;
	}	

    

    //check that post code is full 7 character lenght.
    $postcode_count = strlen($id);
    if($postcode_count == 7) {
    	//$postalData[$id] = _convert_csv_row($row, $type);
    	$postalData[] = _convert_csv_row($row, $type);
    	
    } else {
      echo "ERROR with csv row convert. The post code is only the following: ".$id."\n";
      echo "Due to the error, the item has been skipped on import.\n";  
      echo "\n";  
      echo "\n";  
         	
    }	
  }

  fclose($f);
  return $postalData;
}


/**
 * Generates the provided data into json and writes it to the disk.
 */
function _create_json_file($filename, $data) {

    $data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    // Indenting with tabs instead of 4 spaces gives us 20% smaller files.
    $data = str_replace('    ', "\t", $data);
    file_put_contents($filename, $data);
}



/**
 * Filter one row of Japan postal code csv data.
 *
 * @param array $row
 *   One row data of Japan postal code csv.
 *   Format is as following:
 *   - normal
 *     http://www.post.japanpost.jp/zipcode/dl/readme.html
 *     - 全国地方公共団体コード 半角数字
 *     - （旧）郵便番号（5桁）半角数字
 *     - 郵便番号（7桁） 半角数字
 *     - 都道府県名 半角カタカナ
 *     - 市区町村名 半角カタカナ
 *     - 町域名 半角カタカナ
 *     - 都道府県名 漢字
 *     - 市区町村名 漢字
 *     - 町域名 漢字
 *     - その他
 *   - special
 *     http://www.post.japanpost.jp/zipcode/dl/jigyosyo/readme.html
 *     - 大口事業所の所在地のJISコード（5バイト）
 *     - 大口事業所名（カナ）（100バイト）
 *     - 大口事業所名（漢字）（160バイト）
 *     - 都道府県名（漢字）（8バイト）
 *     - 市区町村名（漢字）（24バイト）
 *     - 町域名（漢字）（24バイト）
 *     - 小字名、丁目、番地等（漢字）（124バイト）
 *     - 大口事業所個別番号（7バイト）
 *     - 旧郵便番号（5バイト）.
 * @param string $type
 *   Address type. 'normal' or 'special'. Special is for special postal code
 *   organization.
 *
 * @return array
 *   Japan address data with:
 *   0: postal code
 *   1: prefecture
 *   2: city
 *   3: address under city
 *   4: special organization name if exists
 */
function _convert_csv_row(array $row, $type) {
 
  switch ($type) {
    
	case 'JIGYOSYO':

	  $postcode = $row[5];
      $prefecture_en = $row[1];
      $city_en = $row[2];
      $ward_en = $row[2];
      $town_en = $row[3];
      $building_en = $row[4];
      $organization_en = $row[0];

    break;
/*
    case 'normal':
      $code = $row[2];
      $prefecture = _japan_postal_code_clean_japanese_string($row[6]);
      $city = _japan_postal_code_clean_japanese_string($row[7]);
      $address_under_city = _japan_postal_code_clean_japanese_string($row[8]);

      // Strip unnecessary parts of the address data.
      $address_under_city = _japan_postal_code_clean_address($address_under_city);
      $name = '';
      break;
	*/
    case 'KEN_ALL_ROME':
      $postcode = $row[0];
	  
	  // Get English (romaji) string data and clean.
     
      if(array_key_exists(4, $row)) {
      	$prefecture_en =  _clean_prefecture_string($row[4], "en");
      	} else {
      	$prefecture_en = null;
      }      
    
      if(array_key_exists(5, $row)) {
      	$city_en =  _clean_city_string($row[5], "en");
      	} else {
      	$city_en = null;
      }

      if(array_key_exists(5, $row)) {
      	$ward_en =  _clean_ward_string($row[5], "en");
      	} else {
      	$ward_en = null;
      }

      if(array_key_exists(6, $row)) {
      	$town_en =  _clean_town_string($row[6], "en");
      	} else {
      	$town_en = null;
      }

      if(array_key_exists(6, $row)) {
      	$building_en =  _clean_building_string($row[6], "en");
      	} else {
      	$building_en = null;
      }

      $organization_en = '';

	  
	  // Get Japanese (kanji) string data and clean.
	  
      if(array_key_exists(1, $row)) {
      	$prefecture_ja =  _clean_prefecture_string($row[1], "ja");
      	} else {
      	$prefecture_ja = null;
      }      
    
      if(array_key_exists(2, $row)) {
      	$city_ja =  _clean_city_string($row[2], "ja");
      	} else {
      	$city_ja = null;
      }

      if(array_key_exists(2, $row)) {
      	$ward_ja =  _clean_ward_string($row[2], "ja");
      	//$ward_ja = null;
      	} else {
      	$ward_ja = null;
      }

      if(array_key_exists(3, $row)) {
      	$town_ja =  _clean_town_string($row[3], "ja");
      	} else {
      	$town_ja = null;
      }

      if(array_key_exists(3, $row)) {
      	$building_ja =  _clean_building_string($row[3], "ja");
      	} else {
      	$building_ja = null;
      }	  
	  
      $organization_ja = '';

      break;

    default:
    break;
  }
  
  $convertedRow = array();
  $convertedRow['postcode'] = $postcode;
  $convertedRow['prefecture_name'] = $prefecture_en;
  $convertedRow['city_name'] = $city_en;
  $convertedRow['ward_name'] = $ward_en;
  $convertedRow['town_name'] = $town_en;
  $convertedRow['building_name'] = $building_en;
  $convertedRow['organization_name'] = $organization_en;
  if(isset($prefecture_ja)){
  	$convertedRow['translations']['ja']['prefecture_name'] = $prefecture_ja;
  	$convertedRow['translations']['ja']['city_name'] = $city_ja;
  	$convertedRow['translations']['ja']['ward_name'] = $ward_ja;
  	$convertedRow['translations']['ja']['town_name'] = $town_ja;
  	$convertedRow['translations']['ja']['building_name'] = $building_ja;
  	$convertedRow['translations']['ja']['organization_name'] = $organization_ja;
  }
  
  return $convertedRow;
}



/**
* Split postcode.
*
* @return array The postcode split into array.
*/
function _clean_postcode_string($postcode) {
    $splitPostCode = array();
	$splitPostCode[0] = mb_substr($postcode, 0, 3);
	$splitPostCode[1] = mb_substr($postcode, 3, 4);
    $PostCode = $splitPostCode[0].'-'.$splitPostCode[1];    
    return $PostCode; 
}

/**
 * Convert raw Japan post office prefecture data into proper format.
 *
 * @param string $string
 *   Detailed raw prefecture string.
 *
 * @param string $lang
 *   Detailed raw prefecture string language.
 *
 * @return string
 *   Cleaned address without unnecessary data.
 */
function _clean_prefecture_string($string, $lang) {
  
  $cleanString = '';   
  //$string = mb_convert_encoding($string, 'UTF-8', 'SHIFT_JIS');
  
  switch ($lang) {    
	case 'en':
	
	   $countString = mb_substr_count($string, ' ');
	   if($countString == 1) {
	     $stringArray = explode(' ', $string);
	     $string = $stringArray[0].'-'.$stringArray[1];
		
         //format string, e.g. 'ODORINISHI' to 'Odorinishi'. 
	     $cleanString = ucfirst(strtolower($string));
	     return $cleanString;
	   }	
	   	   
       //format string, e.g. 'ODORINISHI' to 'Odorinishi'. 
	   $cleanString = ucfirst(strtolower($string));  
	    
    break;

	case 'ja':
      $cleanString = $string;
    break;
	
    default:
      $cleanString = $string;
	break;
  }
	

return $cleanString;
}


/**
 * Convert raw Japan post office city data into proper format.
 *
 * @param string $string
 *   Detailed raw city string.
 *
 * @param string $lang
 *   Detailed raw city string language.
 *
 * @return string
 *   Cleaned address without unnecessary data.
 */
function _clean_city_string($string, $lang) {

  $cleanString = '';
  //$string = mb_convert_encoding($string, 'UTF-8', 'SHIFT_JIS');
  
  switch ($lang) {    
	case 'en':
	
	  //$countString = mb_substr_count($string, ' ');
	  $brace_index = mb_strpos($string, ' ');
  	  if ($brace_index !== FALSE) {
	    $stringArray = explode(' ', $string);
	    $string = $stringArray[0].'-'.$stringArray[1];    	
  	  }

      //format string, e.g. 'ODORINISHI' to 'Odorinishi'. 
	  $cleanString = ucfirst(strtolower($string));        
    break;

	case 'ja':
      
      //break city & ward string, e.g. '札幌市　中央区'. 
	  $brace_index = mb_strpos($string, '　');
  	  if ($brace_index !== FALSE) {
    	$cleanString = mb_substr($string, 0, $brace_index);
    	return $cleanString;
  	  }
  	  $cleanString = $string;      
    break;
	
    default:
/*
      $brace_index_shi = mb_strpos($string, '市');
      $brace_index_double_shi = mb_strpos($string, '市市');
      $brace_index_gun = mb_strpos($string, '郡');
      $brace_index_gun_shi = mb_strpos($string, '郡市');
  	  
  	  if($brace_index_shi) {
  	  	
  	  	if($brace_index_double_shi) {

	    	$stringArray = explode('市市', $string);
	    	$string = $stringArray[0].'市市';
  	  	
  	  	}else {
	    	$stringArray = explode('市', $string);
	    	$string = $stringArray[0].'市';
	    }    		    	  
  	  }



  	  if($brace_index_gun) {

  	  	if($brace_index_gun_shi) {

	    	$stringArray = explode('郡市', $string);
	    	$string = $stringArray[0].'郡市';
  	  	
  	  	}else {
	    	$stringArray = explode('郡', $string);
	    	$string = $stringArray[0].'郡';
	    }  
	    	  
  	  }
  	  
  	  

  	  if(!$brace_index_shi && !$brace_index_gun) {
		
		//fix for island addresses
		switch ($string) {
    		case '八丈島八丈町':
			  $string = '八丈島';		  
			break;
			case '三宅島三宅村':
			  $string = '三宅島';
			break;
			
			default:
			  $string = $string;
			break;
		}
  	  }
  	  
  	  */
      $cleanString = $string; 
      
	break;
  }
	
return $cleanString;
}


/**
 * Convert raw Japan post office ward data into proper format.
 *
 * @param string $string
 *   Detailed raw ward string.
 *
 * @param string $lang
 *   Detailed raw ward string language.
 *
 * @return string
 *   Cleaned address without unnecessary data.
 */
function _clean_ward_string($string, $lang) {
  
  $cleanString = '';
  //$string = mb_convert_encoding($string, 'UTF-8', 'SHIFT_JIS');
  
  switch ($lang) {    
	case 'en':
	
	   $countString = mb_substr_count($string, ' ');
	   if($countString == 3) {
	     $stringArray = explode(' ', $string);
	     $string = $stringArray[2].'-'.$stringArray[3];
		
         //format string, e.g. 'ODORINISHI' to 'Odorinishi'. 
	     $cleanString = ucfirst(strtolower($string));
	   }        
    break;

	case 'ja':
      
      //break city & ward string, e.g. '札幌市　中央区'. 
	  $brace_index = mb_strpos($string, '　');
  	  if ($brace_index !== FALSE) {
  	    $brace_index = $brace_index += 3;
    	$cleanString = mb_substr($string, $brace_index);
    	return $cleanString;
  	  }
  	  $cleanString = '';       
    break;
	
    default:
/*
      $brace_index_shi = mb_strpos($string, '市');
      $brace_index_double_shi = mb_strpos($string, '市市');
      $brace_index_gun = mb_strpos($string, '郡');
      $brace_index_gun_shi = mb_strpos($string, '郡市');
  	  
  	  if($brace_index_shi) {
  	  	
  	  	if($brace_index_double_shi) {

	    	$stringArray = explode('市市', $string);
	    	if(isset($stringArray[1])){
	    		$string = $stringArray[1];
	    	}else{
	    		$string = null;
	    	}
  	  	
  	  	}else {
	    	$stringArray = explode('市', $string);
	    	if(isset($stringArray[1])){
	    		$string = $stringArray[1];
	    	}else{
	    		$string = null;
	    	}
	    }    		    	  
  	  }
  	  

  	  if($brace_index_gun) {
  	  	
  	  	if($brace_index_gun_shi) {

	    	$stringArray = explode('郡市', $string);
	    	if(isset($stringArray[1])){
	    		$string = $stringArray[1];
	    	}else{
	    		$string = null;
	    	}
  	  	
  	  	}else {
	    	$stringArray = explode('郡', $string);
	    	if(isset($stringArray[1])){
	    		$string = $stringArray[1];
	    	}else{
	    		$string = null;
	    	}
	    }    		    	  
  	  }


  	  if(!$brace_index_shi && !$brace_index_gun) {
		
		//fix for island address
		switch ($string) {
    		case '八丈島八丈町':
			  $string = '八丈町';			  
			break;
			case '三宅島三宅村':
			  $string = '三宅村';
			break;
			
			default:
			  $string = null;
			break;
		}
  	  }
      $cleanString = $string; 
*/
	break;
  }
	
return $cleanString;
}


/**
 * Convert raw Japan post office town data into proper format.
 *
 * @param string $string
 *   Detailed raw town string.
 *
 * @param string $lang
 *   Detailed raw town string language.
 *
 * @return string
 *   Cleaned address without unnecessary data.
 */
function _clean_town_string($string, $lang) {

  $cleanString = '';
  //$string = mb_convert_encoding($string, 'UTF-8', 'SHIFT_JIS');
  
  switch ($lang) {    
	case 'en':
	  //remove unnecessary string & return. 
      if ($string === 'IKANIKEISAIGANAIBAAI') {
        $cleanString = '';
		return $cleanString;
  	  }        

	  //Check for building data.
	  $countString = mb_substr_count($string, ' ');
	  if($countString = 1) {
	    $stringArray = explode(' ', $string);
	    $string = $stringArray[0];
	  }
	  
	  //remove unnecessary extra string, e.g. '(1-19-CHOME)'. 
	  $brace_index = mb_strpos($string, '(');
  	  if ($brace_index !== FALSE) {
    	$string = mb_substr($string, 0, $brace_index);
  	  }
	  
	  //format string, e.g. 'ODORINISHI' to 'Odorinishi'. 
	  $cleanString = ucfirst(strtolower($string));
    break;

	case 'ja':
	  //remove unnecessary string & return.
      if ($string === '以下に掲載がない場合') {
        $cleanString = '';
		return $cleanString;
  	  }

	  //Check for building data.
	  $countString = mb_substr_count($string, '　');
	  if($countString = 1) {
	    $stringArray = explode('　', $string);
	    $string = $stringArray[0];
	  }
	  	  
	  //remove unnecessary extra string, e.g. '（１〜１９丁目）'. 
	  $brace_index = mb_strpos($string, '（');
  	  if ($brace_index !== FALSE) {
    	$cleanString = mb_substr($string, 0, $brace_index);
    	return $cleanString;
  	  }
  	  $cleanString = $string;  
    break;
	
    default:
      $cleanString = '';
	break;
  }
	
  return $cleanString;
}


/**
 * Convert raw Japan post office building data into proper format.
 *
 * @param string $string
 *   Detailed raw building from city string.
 *
 * @param string $lang
 *   Detailed raw building string language.
 *
 * @return string
 *   Cleaned address without unnecessary data.
 */
function _clean_building_string($string, $lang) {

  $cleanString = '';
  //$string = mb_convert_encoding($string, 'UTF-8', 'SHIFT_JIS');
  
  switch ($lang) {    
	case 'en':
	   
	  //Check for building data.
	  $countString = mb_substr_count($string, ' ');
	  
	  if($countString == 1) {
	    $stringArray = explode(' ', $string);
	    $string = $stringArray[1];

	  	//remove unnecessary extra string, e.g. '(1-19-CHOME)'. 
	  	$brace_index = mb_strpos($string, '(');
  	  	if ($brace_index !== FALSE) {
    		$string = mb_substr($string, 0, $brace_index);
  	  	}
	  
	  	//format string, e.g. 'ODORINISHI' to 'Odorinishi'. 
	  	$cleanString = ucfirst(strtolower($string));
	  
	  	//replace building end string, e.g. '〜biri' to 'BLDG'.
	  	$building_index = mb_strpos($cleanString, 'biru');
  	  	if ($building_index !== FALSE) {
    		$cleanString = mb_substr($cleanString, 0, $building_index). ' BLDG';	
  	  	}		  	  
	  }
	  	  
    break;

	case 'ja':
	  //Check for building data.
	  $countString = mb_substr_count($string, '　');
	  
	  if($countString == 1) {
	    $stringArray = explode('　', $string);
	    $string = $stringArray[1];
	    
	    //remove unnecessary extra string, e.g. '（１〜１９丁目）'. 
	    $brace_index = mb_strpos($string, '（');
  	    if ($brace_index !== FALSE) {
    	  $cleanString = mb_substr($string, 0, $brace_index);
    	}
  	  }
    break;
	
    default:
      $cleanString = '';
	break;
  }
	
  return $cleanString;
}



/**
  * Get last modified date of remote file.
  */
function GetRemoteLastModified( $uri )
{
    // default
    $unixtime = 0;

    $fp = fopen( $uri, "r" );
    if( !$fp ) {return;}

    $MetaData = stream_get_meta_data( $fp );

    foreach( $MetaData['wrapper_data'] as $response )
    {
        // case: redirection
        if( substr( strtolower($response), 0, 10 ) == 'location: ' )
        {
            $newUri = substr( $response, 10 );
            fclose( $fp );
            return GetRemoteLastModified( $newUri );
        }
        // case: last-modified
        elseif( substr( strtolower($response), 0, 15 ) == 'last-modified: ' )
        {
            $unixtime = strtotime( substr($response, 15) );
            break;
        }
    }
    fclose( $fp );
    return $unixtime;
}

/**
  * Get last modified date of local file.
  */
function GetLocalLastModified($filename) {	
	$unixtime = filemtime($filename);	
  return $unixtime;
}

/**
  * Format the Date.
  */
function formatDate($unixtime) {	
	$formatdatestring = date('Y-m-d', $unixtime);		
  return $formatdatestring;
}



/**
  * Compare Dates.
  */
function compareDates($date1, $date2) {	
	if(formatDate($date1) == formatDate($date2)) {
	  return FALSE;
	  }else{
	  return TRUE;
	}
}


/**
 * Access settings json file.
 */
function getSettings() {
	$settingsFile = realpath(__DIR__ . '').'/data/settings.json';
	
	 if (file_exists($settingsFile)) {
	 	$settingsFileData = file_get_contents($settingsFile);	
		$settings = json_decode($settingsFileData, true);
		return $settings;
	 }  
}

/**
  * Converts SHIFT_JIS Files to UTF-8.
  *
  * @return string.
  */
function convertShiftJis($string) {
	return mb_convert_encoding($string, 'UTF-8', 'SHIFT_JIS');	
}

/**
 * Access prefectures json file.
 */
function getPrefecturesList($config) {
	$dataFile = $config['repository_path'].'/subdivision/japanprefecture.json';
	
	 if (file_exists($dataFile)) {
	 	$dataFileData = file_get_contents($dataFile);	
		$data = json_decode($dataFileData, true);
		
		foreach($data['subdivision'] as $pItem) {
			$prefecture_code = $pItem['code'];
			$allData[$prefecture_code] = $pItem;
		}
		
		return $allData;
	 }  
}


/**
 * createCityRepository json file.
 */
function createCityRepository($config, $cityRepository) {
  
  	$cityRepository['country_code'] = $config['country_code'];	
	$cityRepository['locale'] = $config['locale'];
	$jsonPathSubdivision = $config['repository_path'].'/subdivision';
	
	$jsonFilename = $jsonPathSubdivision.'/japancity.json';
	  // Create json file for each item.
	_create_json_file($jsonFilename, $cityRepository);
	echo "File Created: ".$jsonFilename."\n";	
  
}


/**
 * Clean string for city repository.
 */
function clean_city_repository_string($string, $case) {

  $cleanstring = '';
   
  switch ($case) {    
	case 'en-lname':
	  $brace_index = mb_strpos($string, '-');
  	  if ($brace_index !== FALSE) {
	    $stringArray = explode('-', $string);
	    $cleanstring = $stringArray[0].' City';    	
  	  }
    break;
    
	case 'en-sname':
	  $brace_index = mb_strpos($string, '-');
  	  if ($brace_index !== FALSE) {
	    $stringArray = explode('-', $string);
	    $cleanstring = $stringArray[0];    	
  	  }   
    break;
    
    case 'ja-lname':
  	   $cleanstring = $string;  
    break;

	case 'ja-sname':
		
		$cleanstring = substr_replace($string ,"",-3);  
    break;
        
    default:
   		 $cleanstring = $string;
    break;
  }
  return $cleanstring;
}
?>