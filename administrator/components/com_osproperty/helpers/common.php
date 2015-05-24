<?php
/*------------------------------------------------------------------------
# common.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;

class HelperOspropertyCommon{
    /**
     * Download pdf file
     *
     * @param unknown_type $filelink
     */
    function downloadxmlfile($filelink){
        while (@ob_end_clean());
        define('ALLOWED_REFERRER', '');
        // MUST end with slash (i.e. "/" )
        define('BASE_DIR',JPATH_ROOT.DS."tmp");

        // log downloads? true/false
        define('LOG_DOWNLOADS',false);

        // log file name
        define('LOG_FILE','downloads.log');

        // Allowed extensions list in format 'extension' => 'mime type'
        // If myme type is set to empty string then script will try to detect mime type
        // itself, which would only work if you have Mimetype or Fileinfo extensions
        // installed on server.
        $allowed_ext = array (
            'xml' => 'application/xml'
        );

        ################################################## ##################
        ### DO NOT CHANGE BELOW
        ################################################## ##################

        // If hotlinking not allowed then make hackers think there are some server problems
        if (ALLOWED_REFERRER !== ''
            && (!isset($_SERVER['HTTP_REFERER']) || strpos(strtoupper($_SERVER['HTTP_REFERER']),strtoupper(ALLOWED_REFERRER)) === false)
        ) {
            die(JText::_("Internal server error. Please contact system administrator."));
        }

        // Make sure program execution doesn't time out
        // Set maximum script execution time in seconds (0 means no limit)
        //set_time_limit(0);

        if (!isset($filelink)) {
            die(JText::_("Please specify file name for download."));
        }

        // Get real file name.
        // Remove any path info to avoid hacking by adding relative path, etc.
        $fname = basename($filelink);

        // Check if the file exists
        // Check in subfolders too
        function find_file ($dirname, $fname, &$file_path) {
            $dir = opendir($dirname);
            while ($file = readdir($dir)) {
                if (empty($file_path) && $file != '.' && $file != '..') {
                    if (is_dir($dirname.'/'.$file)) {
                        find_file($dirname.'/'.$file, $fname, $file_path);
                    }
                    else {
                        if (file_exists($dirname.'/'.$fname)) {
                            $file_path = $dirname.'/'.$fname;
                            return;
                        }
                    }
                }
            }//end while

        } // find_file

        // get full file path (including subfolders)
        $file_path = '';
        find_file(BASE_DIR, $fname, $file_path);

        if (!is_file($file_path)) {
            die(JText::_("File does not exist. Make sure you specified correct file name."));
        }

        // file size in bytes
        $fsize = filesize($file_path);

        // file extension
        $fext = strtolower(substr(strrchr($fname,"."),1));

        // check if allowed extension
        if (!array_key_exists($fext, $allowed_ext)) {
            die(JText::_("Not allowed file type."));
        }

        // get mime type
        if ($allowed_ext[$fext] == '') {
            $mtype = '';
            // mime type is not set, get from server settings
            if (function_exists('mime_content_type')) {
                $mtype = mime_content_type($file_path);
            }
            else if (function_exists('finfo_file')) {
                $finfo = finfo_open(FILEINFO_MIME); // return mime type
                $mtype = finfo_file($finfo, $file_path);
                finfo_close($finfo);
            }
            if ($mtype == '') {
                $mtype = "application/force-download";
            }
        }
        else {
            // get mime type defined by admin
            $mtype = $allowed_ext[$fext];
        }

        // Browser will try to save file with this filename, regardless original filename.
        // You can override it if needed.


        $asfname = $fname;

        // set headers
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Type: $mtype");
        header("Content-Disposition: attachment; filename=\"$asfname\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . $fsize);


        if( ! ini_get('safe_mode') ) { // set_time_limit doesn't work in safe mode
            @set_time_limit(0);
        }

        HelperOspropertyCommon::readfile_chunked($file_path);
        exit();
    }
	/**
     * Download pdf file
     *
     * @param unknown_type $filelink
     */
    function downloadfile($filelink){
    	while (@ob_end_clean());
    	define('ALLOWED_REFERRER', '');
		// MUST end with slash (i.e. "/" )
		define('BASE_DIR',JPATH_ROOT.DS."components".DS."com_osproperty".DS."images".DS."csvform");
		
		// log downloads? true/false
		define('LOG_DOWNLOADS',false);
		
		// log file name
		define('LOG_FILE','downloads.log');
		
		// Allowed extensions list in format 'extension' => 'mime type'
		// If myme type is set to empty string then script will try to detect mime type
		// itself, which would only work if you have Mimetype or Fileinfo extensions
		// installed on server.
		$allowed_ext = array (
			// archives
			'zip' => 'application/zip',
			// documents
			'pdf' => 'application/pdf',
			'doc' => 'application/msword',
			'xls' => 'application/vnd.ms-excel',
			'ppt' => 'application/vnd.ms-powerpoint',
			// executables
			'exe' => 'application/octet-stream',
			// images
			'gif' => 'image/gif',
			'png' => 'image/png',
			'jpg' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			// audio
			'mp3' => 'audio/mpeg',
			'wav' => 'audio/x-wav',
			// video
			'mpeg' => 'video/mpeg',
			'mpg' => 'video/mpeg',
			'mpe' => 'video/mpeg',
			'mov' => 'video/quicktime',
			'avi' => 'video/x-msvideo',
			'csv' => 'application/octet-stream'
		);
		
		################################################## ##################
		### DO NOT CHANGE BELOW
		################################################## ##################
		
		// If hotlinking not allowed then make hackers think there are some server problems
		if (ALLOWED_REFERRER !== ''
		&& (!isset($_SERVER['HTTP_REFERER']) || strpos(strtoupper($_SERVER['HTTP_REFERER']),strtoupper(ALLOWED_REFERRER)) === false)
		) {
			die(JText::_("Internal server error. Please contact system administrator."));
		}
		
		// Make sure program execution doesn't time out
		// Set maximum script execution time in seconds (0 means no limit)
		//set_time_limit(0);
		
		if (!isset($filelink)) {
			die(JText::_("Please specify file name for download."));
		}
		
		// Get real file name.
		// Remove any path info to avoid hacking by adding relative path, etc.
		$fname = basename($filelink);
		
		// Check if the file exists
		// Check in subfolders too
		function find_file ($dirname, $fname, &$file_path) {
			$dir = opendir($dirname);
			while ($file = readdir($dir)) {
				if (empty($file_path) && $file != '.' && $file != '..') {
					if (is_dir($dirname.'/'.$file)) {
						find_file($dirname.'/'.$file, $fname, $file_path);
					}
					else {
						if (file_exists($dirname.'/'.$fname)) {
							$file_path = $dirname.'/'.$fname;
							return;
						}
					}	
				}
			}//end while
		
		} // find_file
		
		// get full file path (including subfolders)
		$file_path = '';
		find_file(BASE_DIR, $fname, $file_path);

		if (!is_file($file_path)) {
			die(JText::_("File does not exist. Make sure you specified correct file name."));
		}
		
		// file size in bytes
		$fsize = filesize($file_path);
		
		// file extension
		$fext = strtolower(substr(strrchr($fname,"."),1));
		
		// check if allowed extension
		if (!array_key_exists($fext, $allowed_ext)) {
			die(JText::_("Not allowed file type."));
		}
		
		// get mime type
		if ($allowed_ext[$fext] == '') {
			$mtype = '';
			// mime type is not set, get from server settings
			if (function_exists('mime_content_type')) {
				$mtype = mime_content_type($file_path);
			}
			else if (function_exists('finfo_file')) {
				$finfo = finfo_open(FILEINFO_MIME); // return mime type
				$mtype = finfo_file($finfo, $file_path);
				finfo_close($finfo);
			}
			if ($mtype == '') {
				$mtype = "application/force-download";
			}
		}
		else {
			// get mime type defined by admin
			$mtype = $allowed_ext[$fext];
		}
		
		// Browser will try to save file with this filename, regardless original filename.
		// You can override it if needed.
		
		
		$asfname = $fname;
		
		// set headers
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Type: $mtype");
		header("Content-Disposition: attachment; filename=\"$asfname\"");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: " . $fsize);
		
		
		if( ! ini_get('safe_mode') ) { // set_time_limit doesn't work in safe mode
		    @set_time_limit(0);
	    }
		
		HelperOspropertyCommon::readfile_chunked($file_path);
		exit();
    }
    
    function downloadfile2($file_path,$id){
    	while (@ob_end_clean());
    	$len = @ filesize($file_path);
		$cont_dis ='attachment';

		// required for IE, otherwise Content-disposition is ignored
		if(ini_get('zlib.output_compression'))  {
			ini_set('zlib.output_compression', 'Off');
		}
	
	    header("Pragma: public");
	    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	    header("Expires: 0");
	
	    header("Content-Transfer-Encoding: binary");
		header('Content-Disposition:' . $cont_dis .';'
			. ' filename="csv' .$id . '.csv";'
			. ' size=' . $len .';'
			); //RFC2183
	    header("Content-Length: "  . $len);
	    if( ! ini_get('safe_mode') ) { // set_time_limit doesn't work in safe mode
		    @set_time_limit(0);
	    }
	    HelperOspropertyCommon::readfile_chunked($file_path);
		exit();
    }
    
    
    function readfile_chunked($filename,$retbytes=true){
		$chunksize = 1*(1024*1024); // how many bytes per chunk
		$buffer = '';
		$cnt =0;
		$handle = fopen($filename, 'rb');
		if ($handle === false) {
   			return false;
		}
		while (!feof($handle)) {
	   		$buffer = fread($handle, $chunksize);
	   		echo $buffer;
			@ob_flush();
			flush();
	   		if ($retbytes) {
	       		$cnt += strlen($buffer);
	   		}
		}
   		$status = fclose($handle);
	    if ($retbytes && $status) {
   			return $cnt; // return num. bytes delivered like readfile() does.
		}
		return $status;
	}
	/**
	 * Create New User
	 *
	 * @param unknown_type $username
	 * @param unknown_type $name
	 */
	function createUser($username,$name){
		global $mainframe,$_jversion;
		$db = JFactory::getDbo();
		$authorize	=& JFactory::getACL();
		$user 		= clone(JFactory::getUser());
		
		// Get the form data.
		$config = JFactory::getConfig();
		$app	= JFactory::getApplication();
		$componentParams = JComponentHelper::getParams('com_users');
		$new_usertype = $componentParams->get('new_usertype', '2');

		// Initialise the table with JUser.
		$user = new JUser;
	
		// Prepare the data for the user object.
		$data['username']	= $username;
		$data['email']		= $username."@osproperty.com";
		$data['email2']		= $username."@osproperty.com";
		$data['password']	= $username;
		$data['password2']	= $username;
		$data['name']		= $name;
		$groups[0]			= $new_usertype;
		$data['groups']	 	= $groups;
		$useractivation = 0; //auto approval
		// Bind the data.
		if (!$user->bind($data)) {
			return false;
		}
		$user->sendEmail = 0;
		// Store the data.
		if (!$user->save()) {
			return false;
		}
		
		return $user->id;
	}
	/**
	 * remove white space in begin and end of the option in one array
	 *
	 * @param unknown_type $a
	 */
	function stripSpaceArrayOptions($a){
		global $mainframe;
		if(count($a) > 0){
			for($i=0;$i<count($a);$i++){
				$a[$i] = trim($a[$i]);
			}
		}
		return $a;
	}
	
	function loadFooter($option){
		global $mainframe,$configClass;
		?>
		<div style="clear:both;">
		</div>
		<div style="width:100%;text-align:center;font-size:10px;margin:15px;">
			<?php echo $configClass['footer_content'];?>
		</div>
		<?php
	}
	
	/**
	 * Show price 
	 * get value of record 21 from #__osrs_configuration
	 *
	 * @param unknown_type $price
	 * @return unknown
	 */
	function showPrice($price){
		$db = JFactory::getDBO();
		$db->setQuery("Select fieldvalue from #__osrs_configuration where id = '21'");
		$money_format = $db->loadResult();
		switch ($money_format){
			case "1":
				return number_format($price,2,',','.');
			break;
			case "2":
				return number_format($price,2,',',' ');
			break;
			default:
				return number_format($price,2,'.',',');
			break;
		}
	}
	
	
	/**
	 * Load city
	 *
	 * @param unknown_type $option
	 * @param unknown_type $state_id
	 * @param unknown_type $city_id
	 * @return unknown
	 */
	function loadCity($option,$state_id,$city_id){
		global $mainframe;
		$db = JFactory::getDBO();
		$cityArr = array();
		$cityArr[]= JHTML::_('select.option','0',' - '.JText::_('OS_SELECT_CITY').' - ');
		if($state_id > 0){
			$db->setQuery("Select id as value, city as text from #__osrs_cities where state_id = '$state_id' order by city");
			$cities = $db->loadObjectList();
			//$cityArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_CITY'));
			$cityArr   = array_merge($cityArr,$cities);
			$disabled  = "";
		}else{
			$disabled  = "disabled";
		}
		return JHTML::_('select.genericlist',$cityArr,'city','class="input-medium chosen" '.$disabled,'value','text',$city_id);
	}
	
	
	function loadCityName($city){
		global $mainframe;
		$db = JFactory::getDBO();
		$db->setQuery("Select city from #__osrs_cities where id = '$city'");
		$city_name = $db->loadResult();
		return $city_name;
	}
	
	
	function loadNeighborHood($pid){
		$db = JFactory::getDbo();
		$query = "Select a.*,b.neighborhood from #__osrs_neighborhood as a"
				." inner join #__osrs_neighborhoodname as b on b.id = a.neighbor_id"
				." where a.pid = '$pid'";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if(count($rows) > 0){
			?>
			<table width="100%">
				<?php
				for($i=0;$i<count($rows);$i++){
					$row = $rows[$i];
					?>
					<tr>
						<td width="30%" style="text-align:left;padding:5px;">
						<b><?php echo JText::_($row->neighborhood)?> </b></td> 
						<td width="70%" style="text-align:left;padding:5px;">
						<?php echo $row->mins?> <?php echo JText::_('OS_MINS')?> <?php echo JText::_('OS_BY')?> &nbsp;
						<b>
						<?php
						switch ($row->traffic_type){
							case "1":
								echo JText::_('OS_WALK');
							break;
							case "2":
								echo JText::_('OS_CAR');
							break;
							case "3":
								echo JText::_('OS_TRAIN');
							break;
						}
						?>
						</b>
						</td>
					</tr>
					<?php
				}
				?>
			</table>
			<?php
		}
	}
	
	/**
	 * Show access value
	 *
	 * @param unknown_type $option
	 */
	function loadAccessName($access){
		switch ($access){
			case "0":
				return JText::_('OS_PUBLIC');
			break;
			case "1":
				return JText::_('OS_REGISTERED');
			break;
			case "2":
				return JText::_('OS_SPECIAL');
			brea;
			default:
				return "";
			break;
		}
	}
	
	
	/**
	 * Show the currency Select list
	 *
	 * @param unknown_type $curr
	 */
	function showCurrencySelectList($curr){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$db->setQuery("Select id as value, concat(currency_name,' - ',currency_code,' - ',currency_symbol) as text from #__osrs_currencies order by currency_name");
		$currencies = $db->loadObjectList();
		if(intval($curr) == 0){
			$curr = $configClass['general_currency_default'];
		}
		echo JHtml::_('select.genericlist',$currencies,'curr','class="input-large"','value','text',$curr);
	}
	
	/**
	 * Load currency
	 *
	 * @param unknown_type $curr
	 */
	function loadCurrency($curr){
		global $mainframe,$configClass;
		if(intval($curr) == 0){
			$curr = $configClass['general_currency_default'];
		}
		$db = JFactory::getDbo();
		$db->setQuery("Select currency_symbol from #__osrs_currencies where id = '$curr'");
		$curr = $db->loadResult();
		return $curr;
	}
	
	/**
	 * Get the country_id in the filter page or edit item details page
	 *
	 * @return unknown
	 */
	function getDefaultCountry(){
		global $configClass;
		if($configClass['show_country_id'] != ""){
			$countryArr = explode(",",$configClass['show_country_id']);
			if(count($countryArr) == 1){
				return $countryArr[0];
			}
		}
		return 0;
	}
	
	/**
	 * Check default country
	 * 
	 * @return boolean
	 * false : Use for one country
	 * true  : use for multiple countries
	 * 
	 */
	function checkCountry(){
		global $configClass;
		if($configClass['show_country_id'] != ""){
			$countryArr = explode(",",$configClass['show_country_id']);
			if(count($countryArr) == 1){
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Make the country list
	 *
	 * @param unknown_type $req_country_id
	 * @param unknown_type $name
	 * @param unknown_type $onChange
	 */
	public static function makeCountryList($req_country_id,$name,$onChange,$firstOption,$style){
		global $configClass;
		$db = JFactory::getDbo();
		if($configClass['show_country_id'] != ""){
			if(HelperOspropertyCommon::checkCountry()){
				
				$db->setQuery("Select id as value, country_name as text from #__osrs_countries where 1=1 and id in (".$configClass['show_country_id'].") order by country_name");
				$countries = $db->loadObjectList();
				
				if($firstOption != ""){
					$countryArr[] = JHTML::_('select.option','',$firstOption);
					$countryArr = array_merge($countryArr,$countries);
				}else{
					$countryArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_COUNTRY'));
					$countryArr = array_merge($countryArr,$countries);
				}
				
				return  JHTML::_('select.genericlist',$countryArr,$name,'class="chosen input-medium" '.$onChange.' '.$style,'value','text',$req_country_id);
				
			}else{
				return "<input type='hidden' name='$name' value='".$configClass['show_country_id']."' id='$name'>";
			}
		}else{
			$db->setQuery("Select id as value, country_name as text from #__osrs_countries where 1=1 order by country_name");
			$countries = $db->loadObjectList();
			
			if($firstOption != ""){
				$countryArr[] = JHTML::_('select.option','',$firstOption);
				$countryArr = array_merge($countryArr,$countries);
			}else{
				$countryArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_COUNTRY'));
				$countryArr = array_merge($countryArr,$countries);
			}
			return  JHTML::_('select.genericlist',$countryArr,$name,'class="chosen input-medium" '.$onChange.' '.$style,'value','text',$req_country_id);
		}
	}
	
	/**
	 * Make the state list
	 *
	 * @param unknown_type $req_country_id
	 * @param unknown_type $req_state_id
	 * @param unknown_type $name
	 * @param unknown_type $onChange
	 * @param unknown_type $firstOption
	 * @return unknown
	 */
	function makeStateList($req_country_id,$req_state_id,$name,$onChange,$firstOption,$style){
		global $configClass;
		$db = JFactory::getDbo();
		$stateArr = array();
		if((!HelperOspropertyCommon::checkCountry()) or ($req_country_id > 0)){
			
			$query  = "Select id as value,state_name as text from #__osrs_states where published = 1 ";
			if($req_country_id > 0){
				$query .= " and country_id = '$req_country_id'";
			}else{
				$query .= " and country_id = '".$configClass['show_country_id']."'";
			}
			$query .= " order by state_name";
			$db->setQuery($query);
			$states = $db->loadObjectList();
			if($firstOption != ""){
				$stateArr[] = JHTML::_('select.option','',$firstOption);
				$stateArr   = array_merge($stateArr,$states);
			}else{
				$stateArr   = $states;
			}
			return JHTML::_('select.genericlist',$stateArr,$name,' '.$onChange.' '.$style,'value','text',$req_state_id);
			
		}else{
			$stateArr[] = JHTML::_('select.option','',$firstOption);
			return JHTML::_('select.genericlist',$stateArr,$name,$style.' disabled','value','text');
		}
	}
	
	/**
	 * Check is Photo file
	 * Return false : if it is not the JPEG photo
	 * Return true  : if it is JPEG photo
	 */
	function checkIsPhotoFileUploaded($element_name){
		$file = $_FILES[$element_name];
		$fname = $file['name'];
		$ftype = end(explode('.', strtolower($fname)));
		$ftype = strtolower($ftype);
		$allowtype = array('jpg','jpeg');
		if(!in_array($ftype,$allowtype)){
			return false;
		}else{
			//return true;
			$imageinfo = getimagesize($_FILES[$element_name]['tmp_name']);
			if(strtolower($imageinfo['mime']) != 'image/jpeg'&& strtolower($imageinfo['mime']) != 'image/jpg') {
			    return false;
			}else{
				return true;
			}
		}
	}
	
	/**
	 * Check is Document file
	 * Return false : if it is not Doc or PDF file
	 * Return true  : if it is Doc or PDF file
	 */
	function checkIsDocumentFileUploaded($element_name){
		$file = $_FILES[$element_name];
		$fname = $file['name'];
		$ftype = end(explode('.', strtolower($fname)));
		$ftype = strtolower($ftype);
		$allowtype = array('pdf','doc','docx');
		if(!in_array($ftype,$allowtype)){
			return false;
		}else{
			$type = strtolower($_FILES[$element_name]['type']);
			if (($type == "application/msword") || ($type == "application/pdf")){  
				return true;
			}else{
				return false;
			}
		}
	}
	
	/**
	 * Check is Zip file
	 * Return false : if it is not zip
	 * Return true  : if it is zip file
	 */
	function checkIsArchiveFileUploaded($element_name){
		$file = $_FILES[$element_name];
		$fname = $file['name'];
		$ftype = end(explode('.', strtolower($fname)));
		$ftype = strtolower($ftype);
		$allowtype = array('zip');
		if(!in_array($ftype,$allowtype)){
			return false;
		}else{
			return true;
		}
	}
	
	/**
	 * Check is Zip file
	 * Return false : if it is not csv file
	 * Return true  : if it is csv file
	 */
	function checkIsCsvFileUploaded($element_name){
		$file = $_FILES[$element_name];
		$fname = $file['name'];
		$ftype = end(explode('.', strtolower($fname)));
		$ftype = strtolower($ftype);
		$allowtype = array('csv');
		if(!in_array($ftype,$allowtype)){
			return false;
		}else{
			return true;
		}
	}
	
	/**
	 * Check to see if my server has curl
	 *
	 * @return unknown
	 */
	function _iscurlinstalled() {
		if  (in_array  ('curl', get_loaded_extensions())) {
			return true;
		}
		else{
			return false;
		}
	}
	
	/**
	 * Create the photo from main photo
	 *
	 * @param unknown_type $t
	 * @param unknown_type $l
	 * @param unknown_type $h
	 * @param unknown_type $w
	 * @param unknown_type $wall_image
	 */
	function create_photo($t,$l,$h,$w,$photo_name,$type,$pid){
		global $configClass;
		$ext = $ext[count($ext)-1];
		$path = JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$pid;
		$srcImg  = imagecreatefromjpeg($path.DS.$photo_name);
		$newImg  = imagecreatetruecolor($w, $h);
		imagecopyresampled($newImg, $srcImg, 0, 0, $l, $t, $w, $h, $w, $h);
		if($type == 0){
			imagejpeg($newImg,$path.DS."thumb".DS.$photo_name);
			//resize if the photo has big size
			$images_thumbnail_width = $configClass['images_thumbnail_width'];
			$images_thumbnail_height = $configClass['images_thumbnail_height'];
			$info = getimagesize($path.DS."thumb".DS.$photo_name);
			$width = $info[0];
			$height = $info[1];
			if($width > $images_thumbnail_width){
				//resize image to the original thumb width
				$image = new SimpleImage();
			    $image->load($path.DS."thumb".DS.$photo_name);
			    $image->resize($images_thumbnail_width,$images_thumbnail_height);
			    $image->save($path.DS."thumb".DS.$photo_name,$configClass['images_quality']);
			}
		}else{
			imagejpeg($newImg,$path.DS."medium".DS.$photo_name);
			//resize if the photo has big size
			$images_large_width = $configClass['images_large_width'];
			$images_large_height = $configClass['images_large_height'];
			$info = getimagesize($path.DS."medium".DS.$photo_name);
			$width = $info[0];
			$height = $info[1];
			if($width > $images_large_width){
				//resize image to the original thumb width
				$image = new SimpleImage();
			    $image->load($path.DS."medium".DS.$photo_name);
			    $image->resize($images_large_width,$images_large_height);
			    $image->save($path.DS."medium".DS.$photo_name,$configClass['images_quality']);
			}
		}
	}
	
	/**
	 * Check max size of the image
	 *
	 * @param unknown_type $image_path
	 */
	function returnMaxsize($image_path){
		global $mainframe,$configClass;
		$info = getimagesize($image_path);
		$width = $info[0];
		$height = $info[1];
		$max_width_allowed = $configClass['max_width_size'];
		$max_height_allowed = $configClass['max_height_size'];
		
		if(($height > $max_height_allowed) and ($width > $max_width_allowed)){
			$resize = 1;
			//resize to both
			/*
			$return = HelperOspropertyCommon::calResizePhoto($width,$height,$max_width_allowed,$max_height_allowed,$resize);
			//resize image
			$image = new SimpleImage();
		    $image->load($image_path);
		    $image->resize($return[0],$return[1]);
		    $image->save($image_path,100);
		    */
			OSPHelper::resizePhoto($image_path,$max_width_allowed,$max_height_allowed);
		}elseif(($height > $max_height_allowed) and ($width <= $max_width_allowed)){
			$resize = 2;
			//resize to height
			/*
			$return = HelperOspropertyCommon::calResizePhoto($width,$height,$max_width_allowed,$max_height_allowed,$resize);
			//resize image
			$image = new SimpleImage();
		    $image->load($image_path);
		    $image->resize($return[0],$return[1]);
		    $image->save($image_path,100);
		    */
			OSPHelper::resizePhoto($image_path,$width,$max_height_allowed);
		}elseif(($height <= $max_height_allowed) and ($width > $max_width_allowed)){
			$resize = 3;
			//resize to width
			/*
			$return = HelperOspropertyCommon::calResizePhoto($width,$height,$max_width_allowed,$max_height_allowed,$resize);
			//resize image
			$image = new SimpleImage();
		    $image->load($image_path);
		    $image->resize($return[0],$return[1]);
		    $image->save($image_path,100);
		    */
			OSPHelper::resizePhoto($image_path,$max_width_allowed,$height);
		}else{
			//do nothing
		}
	}
	
	
	function calResizePhoto($width,$height, $maxwidth,$maxheight,$resize){
		global $mainframe;
		switch ($resize){
			case "1":
				$return 	= HelperOspropertyCommon::calResizeWidth($width,$height,$maxwidth,$maxheight);
				$newwidth 	= $return[0];
				$newheight 	= $return[1];
				if($newheight > $maxheight){
					$return 	= HelperOspropertyCommon::calResizeHeight($width,$height,$maxwidth,$maxheight);
				}
			break;
			case "2":
				$return 	= HelperOspropertyCommon::calResizeHeight($width,$height,$maxwidth,$maxheight);
			break;
			case "3":
				$return 	= HelperOspropertyCommon::calResizeWidth($width,$height,$maxwidth,$maxheight);
			break;
		}
		return $return;
	}
	
	function calResizeWidth($width,$height,$maxwidth,$maxheight){
		$return = array();
		if($width > $maxwidth){
			$newwidth  = $maxwidth;
			$newheight = round($height*$maxwidth/$width);
			$return[0] = $newwidth;
			$return[1] = $newheight;
		}else{
			$return[0] = $width;
			$return[1] = $height;
		}
		return $return;
	}
	
	function calResizeHeight($width,$height,$maxwidth,$maxheight){
		$return = array();
		if($height > $maxheight){
			$newheight = $maxheight;
			$newwidth  = round($width*$maxheight/$height);
			$return[0] = $newwidth;
			$return[1] = $newheight;
		}else{
			$return[0] = $width;
			$return[1] = $height;
		}
		return $return;
	}
	
	function checkSpecial(){
		global $mainframe;$_jversion;
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		$specialArr = array("Super Users","Super Administrator","Administrator","Manager");
		if($_jversion == "1.5"){
			if(in_array($user->usertype,$specialArr)){
				return true;
			}else{
				return false;
			}
		}else{
			$db->setQuery("Select b.title from #__user_usergroup_map as a inner join #__usergroups as b on b.id = a.group_id where a.user_id = '$user->id'");
			$usertype = $db->loadResult();
			if(in_array($usertype,$specialArr)){
				return true;
			}else{
				return false;
			}
		}
	}
	
	/**
	 * Set Expired
	 *
	 * @param unknown_type $id
	 * @param unknown_type $type
	 * @param unknown_type $isNew
	 */
	public static function setExpiredTime($id,$type,$isNew){
		global $mainframe,$configs,$configClass;
		$db = JFactory::getDbo();
		$current_time 	= self::getRealTime();
		$db->setQuery("Select count(id) from #__osrs_expired where pid = '$id'");
		$count = $db->loadResult();

		if($count == 0){
			//check and calculate the expired and clean db time
			$unpublish_time = intval($configClass['general_time_in_days']);
			$remove_time	= intval($configClass['general_unpublished_days']);
			$feature_time	= intval($configClass['general_time_in_days_featured']);
			if($type == "f"){
				$unpublish_time = $feature_time;
				//calculate the unfeature time
				$feature_time    = $current_time + $feature_time*24*3600;
			}
			$send_appro		= $configClass['send_approximates'];
			$appro_days		= $configClass['approximates_days'];

			$unpublish_time = $current_time + $unpublish_time*24*3600;
			//calculate remove time
			$remove_time    = $unpublish_time + $remove_time*24*3600;

			//allow to send the approximates expired day
			if($send_appro == 1){
				$inform_time = $unpublish_time - $appro_days*24*3600;
				$inform_time = date("Y-m-d H:i:s",$inform_time);
			}else{
				$inform_time = "";
			}
			//change to time stamp
			$unpublish_time	= date("Y-m-d H:i:s",$unpublish_time);
			$remove_time	= date("Y-m-d H:i:s",$remove_time);
			$feature_time   = date("Y-m-d H:i:s",$feature_time);
			//insert into #__osrs_expired
			$db->setQuery("Insert into #__osrs_expired (id,pid,inform_time,expired_time,expired_feature_time,remove_from_database) values (NULL,$id,'$inform_time','$unpublish_time','$feature_time','$remove_time')");
			$db->query();
			//update start publishing today
			OspropertyListing::updateStartPublishing($id);

		}else{//in the case this property is already in the expired table
			//check and calculate the expired and clean db time
			$unpublish_time = intval($configClass['general_time_in_days']);
			$remove_time	= intval($configClass['general_unpublished_days']);
			$feature_time	= intval($configClass['general_time_in_days_featured']);
			$send_appro		= $configClass['send_approximates'];
			$appro_days		= $configClass['approximates_days'];

			$db->setQuery("Select * from #__osrs_expired where pid = '$id'");
			$expired = $db->loadObject();
			$expired_time = $expired->expired_time;
			$expired_feature_time = $expired->expired_feature_time;
			$expired_time_int = strtotime($expired_time);
			$expired_feature_int = strtotime($expired_feature_time);

			if($type == "f"){
				if($expired_feature_int > $current_time){
					$current_time = $expired_feature_int;
				}
				$unpublish_time = $feature_time;
				//calculate the unfeature time
				$feature_time    = $current_time + $feature_time*24*3600;
			}

			if($type == "n"){
				if($expired_time_int > $current_time){
					$current_time = $expired_time_int;
				}
			}

			$unpublish_time = $current_time + $unpublish_time*24*3600;
			if($unpublish_time < $expired_time_int){
				$unpublish_time = $expired_time_int;
			}
			//calculate remove time
			$remove_time    = $unpublish_time + $remove_time*24*3600;
			//allow to send the approximates expired day
			if($send_appro == 1){
				$inform_time = $unpublish_time - $appro_days*24*3600;
				$inform_time = date("Y-m-d H:i:s",$inform_time);
			}else{
				$inform_time = "";
			}
			//change to time stamp
			$unpublish_time	= date("Y-m-d H:i:s",$unpublish_time);
			$remove_time	= date("Y-m-d H:i:s",$remove_time);
			$feature_time   = date("Y-m-d H:i:s",$feature_time);
			//insert into #__osrs_expired
			$db->setQuery("UPDATE #__osrs_expired SET inform_time = '$inform_time',expired_time='$unpublish_time',expired_feature_time = '$feature_time',remove_from_database='$remove_time' WHERE pid = '$id'");
			$db->query();
			//update start publishing today
			OspropertyListing::updateStartPublishing($id);
		}
	}
	
	public static function getRealTime(){
		$config = new JConfig();
		$offset = $config->offset;
		return strtotime(JFactory::getDate('now',$offset));
	}
	
	/**
	 *
	 * Function to add dropdown menu
	 * @param string $vName        	
	 */
	public static function renderSubmenu($vName = 'dashboard')
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__osrs_menus')
			->where('published = 1')
			->where('parent_id = 0')
			->order('ordering');
		$db->setQuery($query);
		$menus = $db->loadObjectList();
		$html = '';
		$html .= '<div id="submenu-box"><div class="m"><ul class="nav nav-pills">';
		for ($i = 0; $n = count($menus), $i < $n; $i++)
		{
			$menu = $menus[$i];
			$query->clear();
			$query->select('*')
				->from('#__osrs_menus')
				->where('published = 1')
				->where('parent_id = ' . intval($menu->id))
				->order('ordering');
			$db->setQuery($query);
			$subMenus = $db->loadObjectList();
			if (!count($subMenus))
			{
				$class = '';
				if ($menu->menu_task == $vName)
				{
					$class = ' class="active"';
				}				
				$html .= '<li' . $class . '>' ;
				$html .= '<a href="index.php?option=com_osproperty&task=' . $menu->menu_task . '">';
				if($menu->menu_icon != ""){
					$html .= '<i class="'.$menu->menu_icon.'"></i>';
				}
				$html .= JText::_($menu->menu_name) . '</a></li>';
			}
			else
			{
				$class = ' class="dropdown"';
				for ($j = 0; $m = count($subMenus), $j < $m; $j++)
				{
					$subMenu = $subMenus[$j];
					$lName = JRequest::getVar('layout');
					if ( $vName == $subMenu->menu_task )
					{
						$class = ' class="dropdown active"';
						break;
					}
				}
				$html .= '<li' . $class . '>';
				$html .= '<a id="drop_' . $menu->id . '" href="#" data-toggle="dropdown" role="button" class="dropdown-toggle">' ;
				if($menu->menu_icon != ""){
					$html .= '<i class="'.$menu->menu_icon.'"></i>';
				}
				$html .= JText::_($menu->menu_name) . ' <b class="caret"></b></a>';
				$html .= '<ul aria-labelledby="drop_' . $menu->id . '" role="menu" class="dropdown-menu" id="menu_' . $menu->id . '">';
				for ($j = 0; $m = count($subMenus), $j < $m; $j++)
				{
					$subMenu = $subMenus[$j];
					$layoutLink = '';
					$class = '';
					$lName = JRequest::getVar('layout');
					if ((!$subMenu->menu_layout && $vName == $subMenu->menu_task ) || ($lName != '' && $lName == $subMenu->menu_layout))
					{
						$class = ' class="active"';
					}
					$html .= '<li' . $class . '><a href="index.php?option=com_osproperty&task=' .
						 $subMenu->menu_task . $layoutLink . '" tabindex="-1">' . JText::_($subMenu->menu_name) . '</a></li>';
				}
				$html .= '</ul>';
				$html .= '</li>';
			}
		}
		$html .= '</ul></div></div>';
		if (version_compare(JVERSION, '3.0', 'le'))
		{
			JFactory::getDocument()->setBuffer($html, array('type' => 'modules', 'name' => 'submenu'));
		}
		else
		{
			echo $html;
		}
	}
	
	public static function getAvailableTags($pid){
		$db = JFactory::getDBO();
		$query = "SELECT * FROM #__osrs_tags as tags";
		if (!is_null($itemID))
			$query .= " WHERE tags.id NOT IN (SELECT tagID FROM #__osrs_tag_xref WHERE pid=".(int)$pid.")";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}
	
	public static function getCurrentTags($pid){
		$db = JFactory::getDBO();
		$itemID = (int)$itemID;
		$query = "SELECT tags.*
			        FROM #__osrs_tags AS tags 
			        JOIN #__osrs_tag_xref AS xref ON tags.id = xref.tag_id 
			        WHERE xref.pid = ".(int)$pid." ORDER BY xref.id ASC";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}
}
?>