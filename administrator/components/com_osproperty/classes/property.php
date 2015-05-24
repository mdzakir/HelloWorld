<?php
/*------------------------------------------------------------------------
# property.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');


class OspropertyProperties{
	function display($option,$task){
		global $mainframe;
		$db = JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->update("#__osrs_properties")->set("published = '0'")->where("publish_down != '0000-00-00' and publish_down < '".date("Y-m-d",time())."'");
		$db->setQuery($query);
		$db->execute();
		
		$document = JFactory::getDocument();
		$document->addScript(JURI::root()."components/com_osproperty/js/lib.js");
		$cid = JRequest::getVar('cid');
		switch ($task){
			case "properties_uploadzipfile":
				OspropertyProperties::uploadZipfile();
			break;
			case "properties_list":
				OspropertyProperties::properties_list($option);
			break;
			case "properties_add":
				OspropertyProperties::properties_edit($option,0);
			break;
			case "properties_edit":
				OspropertyProperties::properties_edit($option,$cid[0]);
			break;
			case "properties_showphotosinzipfile":
				OspropertyProperties::showphotosinzipfile($option);
			break;
			case "properties_save_photos":
				OspropertyProperties::savephotosinzipfile($option);
			break;
			case "properties_loadState":
				OspropertyProperties::loadStates($option);
			break;
			case "properties_gotolist":
				OspropertyProperties::gotolist($option);
			break;
			case "properties_save":
				OspropertyProperties::save($option,1);
			break;
			case "properties_apply":
				OspropertyProperties::save($option,0);
			break;
			case "properties_changeType":
				OspropertyProperties::changeType($option,$cid[0]);
			break;
			case "properties_approval":
				OspropertyProperties::approval($option,$cid);
			break;
			case "properties_unapproval":
				OspropertyProperties::unapproval($option,$cid);
			break;
			case "properties_publish":
				OspropertyProperties::changState($option,$cid,1);
			break;
			case "properties_unpublish":
				OspropertyProperties::changState($option,$cid,0);
			break;
			case "properties_remove":
				OspropertyProperties::remove($option,$cid);
			break;
			case "properties_print":
				OspropertyProperties::properties_print($option,$cid[0]);
			break;
			case "properties_backup":
				OspropertyProperties::backup($option);
			break;
			case "properties_dobackup":
				OspropertyProperties::dobackup($option);
			break;
			case "properties_restore":
				OspropertyProperties::restore($option);
			break;
			case "properties_dorestore":
				OspropertyProperties::dorestore($option);
			break;
			case "properties_prepareinstallsample":
				OspropertyProperties::properties_prepareinstallsample($option);
			break;
			case "properties_installdata":
				OspropertyProperties::installSampleData($option);
			break;
			case "properties_uploadphotopackages":
				OspropertyProperties::uloadPhotoPackages($option);
			break;
			case "properties_douploadphotopackages":
				OspropertyProperties::doUploadPhotoPackages($option);
			break;
			case "properties_listcity":
				OspropertyProperties::listCities($option);
			break;
			case "properties_setupfolder":
				OspropertyProperties::setup($option);
			break;
			case "properties_copy":
				OspropertyProperties::copy($option,$cid[0]);
			break;
			case "properties_updatelocation":
				OspropertyProperties::updateLocationForm($option);
			break;
			case "properties_doimportlanguage":
				OspropertyProperties::importLanguages($option);
			break;
			case "properties_exportlocation":
				OspropertyProperties::exportlocation($option);
			break;
			case "properties_changeLocation":
				OspropertyProperties::changeLocator($option);
			break;
			case "properties_generatephoto":
				OspropertyProperties::generatePhoto($option);
			break;
			case "properties_savephoto":
				OspropertyProperties::savingPhoto($option);
			break;
			case "properties_completesaving":
				OspropertyProperties::skipgeneratePhoto($option);
			break;
			case "properties_sefoptimize":
				OspropertyProperties::optimizesef($option);
			break;
			case "properties_doOptimizeSefUrls":
				OspropertyProperties::doOptimizeSefUrls($option);
			break;
			case "properties_syncdatabase":
				OspropertyProperties::syncdatabase($option);
			break;
			case "properties_doSyncdatabase":
				OspropertyProperties::doSyncdatabase($option);
			break;
			case "properties_reGeneratePictures":
				OspropertyProperties::reGeneratePictures($option);
			break;
			case "properties_doReGeneratePictures":
				OspropertyProperties::doReGeneratePictures($option);
			break;
            case "properties_delayReGeneratePictures":
                OspropertyProperties::delayReGeneratePictures($option);
            break;
			case "properties_exportlocationdata":
				OspropertyProperties::exportlocationdata($option);
			break;
		}
	}
	/*
	function exportlocationdata($option){
		global $mainframe;
		$db = JFactory::getDbo();
		$db->setQuery("Select pk_i_id,s_name from t_region");
		$regions = $db->loadObjectList();
		$temp_str = "";
		for($i=0;$i<count($regions);$i++){
			$region = $regions[$i];
			$temp_str .= str_replace("'","\'",$region->s_name).":";
			$db->setQuery("Select s_name from t_city where fk_i_region_id = '$region->pk_i_id'");
			$city_names = $db->loadObjectList();
			for($j=0;$j<count($city_names);$j++){
				$city = $city_names[$j];
				$temp_str .= str_replace("'","\'",$city->s_name).",";
			}
			$temp_str = substr($temp_str,0,strlen($temp_str)-1).PHP_EOL;
		}
		$file = fopen(JPATH_ROOT."/tmp/mo_morocco.txt","w");
		@fwrite($file,$temp_str);
		fclose($file);
	}
	*/
	function uploadZipfile(){
		global $mainframe;
		if(is_uploaded_file($_FILES['zipfile'][tmp_name])){
			move_uploaded_file($_FILES['zipfile'][tmp_name],JPATH_ROOT.'/tmp/'.$_FILES['zipfile'][tmp_name]);
		}
	}
	
	function exportlocation($option){
		global $mainframe;
		$db = JFactory::getDbo();
		$country_code = "de";
		$db->setQuery("Select * from t_region where fk_c_country_code = '$country_code'");
		$regions = $db->loadObjectList();
		$exportArr = array();
		for($i=0;$i<count($regions);$i++){
			$export = "";
			$region = $regions[$i];
			$db->setQuery("Select * from t_city where fk_c_country_code = '$country_code' and fk_i_region_id = '$region->pk_i_id'");
			$cities = $db->loadObjectList();
			$cityArr = array();
			for($j=0;$j<count($cities);$j++){
				$city = $cities[$j];
				$cityArr[] = $city->s_name;
			}
			$city_value = implode(",",$cityArr);
			$export = $region->s_name.":".$city_value;
			$exportArr[] = $export;
		}
		$location = implode("\n",$exportArr);
		$filename = "de_germany.txt";
		$fh = fopen(JPATH_ROOT.DS."tmp".DS.$filename, 'w');
		@fwrite($fh,$location);
		fclose($fh);
	}
	
	/**
	 * Update location form
	 *
	 * @param unknown_type $option
	 */
	function updateLocationForm($option){
		global $mainframe,$langArr;
		$db = JFactory::getDbo();
		$country_id = JRequest::getVar('country_id');
		$db->setQuery("Select * from #__osrs_countries where id = '$country_id'");
		$country = $db->loadObject();
		$db->setQuery("Select count(id) from #__osrs_states where country_id = '$country_id'");
		$country->nstates = $db->loadResult();
		$db->setQuery("Select count(id) from #__osrs_cities where country_id = '$country_id'");
		$country->ncities = $db->loadResult();
		
		$optionArr = array();
		$optionArr[] = JHtml::_('select.option','1',Jtext::_('OS_PUBLISHED'));
		$optionArr[] = JHtml::_('select.option','0',Jtext::_('OS_UNPUBLISHED'));
		$lists['state'] = JHtml::_('select.genericlist',$optionArr,'state','class="input-medium"','value','text');
		
		HTML_OspropertyProperties::updateLocationForm($option,$country,$lists);
	}
	
	/**
	 * Import languages
	 *
	 * @param unknown_type $option
	 */
	function importLanguages($option){
		global $mainframe;
		$db = JFactory::getDbo();
		$state = JRequest::getInt('state',0);
		$country_id = JRequest::getVar('country_id',0);
		if(is_uploaded_file($_FILES['filename']['tmp_name'])){
			$filename = $_FILES['filename']['name'];
			move_uploaded_file($_FILES['filename']['tmp_name'],JPATH_ROOT.DS."tmp".DS.$filename);
			$fh = fopen(JPATH_ROOT.DS."tmp".DS.$filename, 'r');
			$filesize = filesize(JPATH_ROOT.DS."tmp".DS.$filename);
			$location = fread($fh, $filesize);
			fclose($fh);
			//echo $location;
			$locationArr = explode("\n",$location);
			if(count($locationArr) > 0){
				for($i=0;$i<count($locationArr);$i++){
					$location = $locationArr[$i];
					$values = explode(":",$location);
					$state_name = $values[0];
					$cities = $values[1];
					if($state_name != ""){
						$db->setQuery("Select count(id) from #__osrs_states where country_id = '$country_id' and state_name like '$state_name'");
						$count = $db->loadResult();
						if($count == 0){
							$db->setQuery("INSERT INTO #__osrs_states (id,country_id,state_name,state_code,published) VALUES (NULL,'$country_id','$state_name','$state_name','$state')");
							$db->query();
							$state_id = $db->insertid();
						}else{
							$db->setQuery("Select id from #__osrs_states where country_id = '$country_id' and state_name like '$state_name'");
							$state_id = $db->loadResult();
						}
						
						//check the cities now
						$cities = explode(",",$cities);
						if(count($cities) > 0){
							$db->setQuery("Delete from #__osrs_cities where state_id = '$state_id' and country_id = '$country_id'");
							$db->query();
							$cityArr = array();
							for($j=0;$j<count($cities);$j++){
								$city = $cities[$j];
								if($city != ""){									
									$cityArr[] = "(NULL,'$city','$country_id','$state_id','$state')";
								}
							}
							$city_sql = implode(",",$cityArr);
							if($city_sql != ""){
								$sql = "INSERT INTO #__osrs_cities (id,city,country_id,state_id,published) VALUES $city_sql;";
								$db->setQuery($sql);
								$db->query();
							}
						}
					}
				}
			}
		}
		//redirect to the home page
		$msg = JText::_('OS_LOCATION_DB_HAVE_BEEN_IMPORTED_SUCCESSFULLY');
		$mainframe->redirect("index.php?option=com_osproperty",$msg);
	}
	
	function setup($option){
		global $mainframe;
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		//check to see if the images folder are existing
		$htmlfile = JPATH_ROOT.DS."components".DS."com_osproperty".DS."index.html";
		if(!JFolder::exists(JPATH_ROOT.DS."images".DS."osproperty")){
			JFolder::create(JPATH_ROOT.DS."images".DS."osproperty");
			JFile::copy($htmlfile,JPATH_ROOT.DS."images".DS."osproperty".DS."index.html");
		}
		if(!JFolder::exists(JPATH_ROOT.DS."images".DS."osproperty".DS."agent")){
			JFolder::create(JPATH_ROOT.DS."images".DS."osproperty".DS."agent");
			JFile::copy($htmlfile,JPATH_ROOT.DS."images".DS."osproperty".DS."agent".DS."index.html");
		}
		if(!JFolder::exists(JPATH_ROOT.DS."images".DS."osproperty".DS."agent".DS."thumbnail")){
			JFolder::create(JPATH_ROOT.DS."images".DS."osproperty".DS."agent".DS."thumbnail");
			JFile::copy($htmlfile,JPATH_ROOT.DS."images".DS."osproperty".DS."agent".DS."thumbnail".DS."index.html");
		}
		if(!JFolder::exists(JPATH_ROOT.DS."images".DS."osproperty".DS."company")){
			JFolder::create(JPATH_ROOT.DS."images".DS."osproperty".DS."company");
			JFile::copy($htmlfile,JPATH_ROOT.DS."images".DS."osproperty".DS."company".DS."index.html");
		}
		if(!JFolder::exists(JPATH_ROOT.DS."images".DS."osproperty".DS."company".DS."thumbnail")){
			JFolder::create(JPATH_ROOT.DS."images".DS."osproperty".DS."company".DS."thumbnail");
			JFile::copy($htmlfile,JPATH_ROOT.DS."images".DS."osproperty".DS."company".DS."thumbnail".DS."index.html");
		}
		if(!JFolder::exists(JPATH_ROOT.DS."images".DS."osproperty".DS."properties")){
			JFolder::create(JPATH_ROOT.DS."images".DS."osproperty".DS."properties");
			JFile::copy($htmlfile,JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS."index.html");
		}
		if(!JFolder::exists(JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS."thumb")){
			JFolder::create(JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS."thumb");
			JFile::copy($htmlfile,JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS."thumb".DS."index.html");
		}
		if(!JFolder::exists(JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS."medium")){
			JFolder::create(JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS."medium");
			JFile::copy($htmlfile,JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS."medium".DS."index.html");
		}
		if(!JFolder::exists(JPATH_ROOT.DS."images".DS."osproperty".DS."category")){
			JFolder::create(JPATH_ROOT.DS."images".DS."osproperty".DS."category");
			JFile::copy($htmlfile,JPATH_ROOT.DS."images".DS."osproperty".DS."category".DS."index.html");
		}
		if(!JFolder::exists(JPATH_ROOT.DS."images".DS."osproperty".DS."category".DS."thumbnail")){
			JFolder::create(JPATH_ROOT.DS."images".DS."osproperty".DS."category".DS."thumbnail");
			JFile::copy($htmlfile,JPATH_ROOT.DS."images".DS."osproperty".DS."category".DS."thumbnail".DS."index.html");
		}
	}

	
	/**
	 * List cities
	 *
	 * @param unknown_type $option
	 */
	function listCities($option){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$limitstart = JRequest::getVar('limitstart',0);
		$limit = JRequest::getVar('limit',20);
		$keyword = JRequest::getVar('keyword','');
		$country_id = JRequest::getVar('country_id',$configClass['show_country_id']);
		$state_id = JRequest::getVar('state_id',0);
		$query = "Select count(a.id) from #__osrs_cities as a inner join #__osrs_states as b on b.id = a.state_id inner join #__osrs_countries as c on c.id = a.country_id where a.published = '1' ";
		if($keyword != ""){
			$query .= " and a.city like '%$keyword%'";
		}
		if($state_id > 0){
			$query .= " and a.state_id = '$state_id'";
		}
		if($country_id > 0){
			$query .= " and a.country_id = '$country_id'";
		}
		$db->setQuery($query);
		$total = $db->loadResult();
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total,$limitstart,$limit);
		$query = "Select a.*,b.state_name,c.country_name from #__osrs_cities as a inner join #__osrs_states as b on b.id = a.state_id inner join #__osrs_countries as c on c.id = a.country_id where a.published = '1' ";
		if($keyword != ""){
			$query .= " and a.city like '%$keyword%'";
		}
		if($state_id > 0){
			$query .= " and a.state_id = '$state_id'";
		}
		if($country_id > 0){
			$query .= " and a.country_id = '$country_id'";
		}
		$query .= " order by c.country_name,b.state_name,a.city ";
		$db->setQuery($query,$pageNav->limitstart,$pageNav->limit);
	
		$rows = $db->loadObjectList();
		
		
		//country
		$countryArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_COUNTRY'));
		$db->setQuery("Select id as value, country_name as text from #__osrs_countries order by country_name");
		$countries = $db->loadObjectList();
		$countryArr = array_merge($countryArr,$countries);
		$lists['country'] = JHTML::_('select.genericlist',$countryArr,'country_id','class="input-medium" onChange="javascript:document.adminForm.submit();"','value','text',$country_id);
		
		//state
		$stateArr = array();
		$stateArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_STATE'));
		$query  = "Select id as value,state_name as text from #__osrs_states where 1=1 ";
		if($country_id != ""){
			$query .= " and country_id = '$country_id'";
			$disabled = "";
		}else{
			$disabled = "disabled";
		}
		$query .= " order by state_name";
		$db->setQuery($query);
		$states = $db->loadObjectList();
		$stateArr   = array_merge($stateArr,$states);
		$lists['states'] = JHTML::_('select.genericlist',$stateArr,'state_id','class="input-medium" onChange="javascript:document.adminForm.submit();" '.$disabled,'value','text',$state_id);
		
		HTML_OspropertyProperties::listCities($option,$rows,$pageNav,$lists);
	}
	
	/**
	 * Show the form to inform about the term and condition when install sample data
	 *
	 * @param unknown_type $option
	 */
	function properties_prepareinstallsample($option){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$lists['ccompany'] = 0;
		$lists['cuser'] = 0;
		$lists['cagent'] = 0;
		$lists['auser'] = 0;
		$db->setQuery("Select count(id) from #__users where username like 'company'");
		$lists['cuser'] = intval($db->loadResult());
		if($lists['cuser'] == 1){
			$db->setQuery("Select id from #__users where username like 'company'");
			$cuser = $db->loadResult();
			$db->setQuery("Select count(id) from #__osrs_companies where user_id = '$cuser'");
			$lists['ccompany'] = intval($db->loadResult());
		}
		
		$db->setQuery("Select count(id) from #__users where username like 'agent'");
		$lists['auser'] = intval($db->loadResult());
		if($lists['auser'] == 1){
			$db->setQuery("Select id from #__users where username like 'agent'");
			$auser = $db->loadResult();
			$db->setQuery("Select count(id) from #__osrs_agents where user_id = '$auser'");
			$lists['cagent'] = intval($db->loadResult());
		}
		
		//select sample location
		$country_id = HelperOspropertyCommon::getDefaultCountry();
		$lists['country'] = HelperOspropertyCommon::makeCountryList($country_id,'country','onchange="change_country_agent(this.value,0,0);"',JText::_('OS_SELECT_COUNTRY'),'');
		
		$lists['states'] = HelperOspropertyCommon::makeStateList($country_id,0,'state','onchange="change_state(this.value,0)" class="input-medium"',JText::_('OS_SELECT_STATE'),'');
		
		$lists['city'] = HelperOspropertyCommon::loadCity($option,0,0);
		
		HTML_OspropertyProperties::prepareInstallSampleForm($option,$lists);
	}
	
	/**
	 * Upload photo packages
	 *
	 * @param unknown_type $option
	 */
	function uloadPhotoPackages($option){
		global $mainframe;
		$db = JFactory::getDbo();
		HTML_OspropertyProperties::uploadPhotoPackages($option);
	}
	
	/**
	 * Process upload photo packages
	 *
	 * @param unknown_type $option
	 */
	function doUploadPhotoPackages($option){
		global $mainframe;
		$db = JFactory::getDbo();
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.archive');
		 
		if(!HelperOspropertyCommon::checkIsArchiveFileUploaded('photopackage')){
			//return to previous page
			?>
			<script language="javascript">
			window.history(-1);
			</script>
			<?php
		}else{
			if(is_uploaded_file($_FILES['photopackage']['tmp_name'])){
				$filename = time().$_FILES['photopackage']['name'];
				move_uploaded_file($_FILES['photopackage']['tmp_name'],JPATH_ROOT.DS."tmp".DS.$filename);
				JArchive::extract(JPATH_ROOT.DS."tmp".DS.$filename,JPATH_ROOT.DS."tmp".DS."osphotos");
				//move the files to the correct folder
				//category folder
				$category_link = JPATH_ROOT.DS."tmp".DS."osphotos".DS."photos".DS."category";
				$category_thumb_link = JPATH_ROOT.DS."tmp".DS."osphotos".DS."photos".DS."category".DS."thumbnail";
				
				if($handle = opendir($category_link)){
					while (false !== ($entry = readdir($handle))) {
						if(($entry != ".") AND ($entry != "..")){
				        	$entryArr = explode(".",$entry);
				        	$ext = $entryArr[1];
				        	if(strtolower($ext) == "jpg"){
				        		JFile::copy($category_link.DS.$entry,JPATH_ROOT.DS."images".DS."osproperty".DS."category".DS.$entry);
				        	}
						}
				    }
				    closedir($handle);
				}
				
				if($handle = opendir($category_thumb_link)){
					while (false !== ($entry = readdir($handle))) {
						if(($entry != ".") AND ($entry != "..")){
				        	$entryArr = explode(".",$entry);
				        	$ext = $entryArr[1];
				        	if(strtolower($ext) == "jpg"){
				        		JFile::copy($category_thumb_link.DS.$entry,JPATH_ROOT.DS."images".DS."osproperty".DS."category".DS."thumbnail".DS.$entry);
				        	}
						}
				    }
				    closedir($handle);
				}
	
				$property_link = JPATH_ROOT.DS."tmp".DS."osphotos".DS."photos".DS."property";
				$property_thumb_link = JPATH_ROOT.DS."tmp".DS."osphotos".DS."photos".DS."property".DS."thumb";
				$property_medium_link = JPATH_ROOT.DS."tmp".DS."osphotos".DS."photos".DS."property".DS."medium";
				
				if($handle = opendir($property_link)){
					while (false !== ($entry = readdir($handle))) {
						if(($entry != ".") AND ($entry != "..")){
				        	$entryArr = explode(".",$entry);
				        	$ext = $entryArr[1];
				        	if(strtolower($ext) == "jpg"){
				        		JFile::copy($property_link.DS.$entry,JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$entry);
				        	}
						}
				    }
				    closedir($handle);
				}
				
				if($handle = opendir($property_thumb_link)){
					while (false !== ($entry = readdir($handle))) {
						if(($entry != ".") AND ($entry != "..")){
				        	$entryArr = explode(".",$entry);
				        	$ext = $entryArr[1];
				        	if(strtolower($ext) == "jpg"){
				        		JFile::copy($property_thumb_link.DS.$entry,JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS."thumb".DS.$entry);
				        	}
						}
				    }
				    closedir($handle);
				}
				
				
				if($handle = opendir($property_medium_link)){
					while (false !== ($entry = readdir($handle))) {
						if(($entry != ".") AND ($entry != "..")){
				        	$entryArr = explode(".",$entry);
				        	$ext = $entryArr[1];
				        	if(strtolower($ext) == "jpg"){
				        		JFile::copy($property_medium_link.DS.$entry,JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS."medium".DS.$entry);
				        	}
						}
				    }
				    closedir($handle);
				}
				
				//create the image folder for each properties
			    $db->setQuery("Select id from #__osrs_properties");
			    $pids = $db->loadOBjectList();
			    if(count($pids) > 0){
			    	include_once(JPATH_ROOT.'/components/com_osproperty/helpers/helper.php');
			    	for($i=0;$i<count($pids);$i++){
			    		$pid = $pids[$i];
			    		OSPHelper::createPhotoDirectory($pid->id);
			    		OSPHelper::movingPhotoSampleData($pid->id);
			    	}
			    }
				
				JFile::delete(JPATH_ROOT.DS."tmp".DS.$filename);
				JFolder::delete(JPATH_ROOT.DS."tmp".DS."osphotos");
			}
		}
		
		$mainframe->redirect("index.php?option=com_osproperty",JText::_('OS_COMPLETE_INSTALL_SAMPLE_DATA'));
	}
	/**
	 * Installing sameple data
	 *
	 * @param unknown_type $option
	 */
	function installSampleData($option){
		global $mainframe;
		$db = JFactory::getDbo();
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder') ;
		//create company 
		//user : company - Test company
		
		$country = JRequest::getVar('country');
		$state   = JRequest::getVar('state');
		$city    = JRequest::getVar('city');
		
		$db->setQuery("Select count(id) from #__users where username like 'company'");
		$count = $db->loadResult();
		if($count == 0){
			//insert new user company
			$uid = HelperOspropertyCommon::createUser('company','Sample Company');
			//create company Item
			$row = & JTable::getInstance('Companies','OspropertyTable');
			$row->id = 0;
			$row->company_name = "Sample Company";
			$row->user_id = $uid;
			$row->address = "5 Sinnamon Beach Way ";
			$row->state = $state;
			$row->country = $country;
			$row->city = $city;
			$row->postcode = "";
			$row->email = "company@osproperty.com";
			$row->website = "http://osproperty.ext4joomla.com";
			$row->photo = "companylogo.png";
			$row->company_description ="Sample company";
			$row->published = 1;
			$row->store();
			
			JFile::copy(JPATH_ROOT.DS."components".DS."com_osproperty".DS."images".DS."assets".DS."companylogo.png",JPATH_ROOT.DS."images".DS."osproperty".DS."company".DS."companylogo.png");
			JFile::copy(JPATH_ROOT.DS."components".DS."com_osproperty".DS."images".DS."assets".DS."companylogo.png",JPATH_ROOT.DS."images".DS."osproperty".DS."company".DS."thumbnail".DS."companylogo.png");
			$company_id = $db->insertid();
		}else{
			$db->setQuery("Select id from #__users where username like 'company'");
			$uid = $db->loadResult();
			$db->setQuery("Select count(id) from #__osrs_companies where user_id = '$uid'");
			$company_id = $db->loadResult();
			if(intval($company_id) == 0){ // user is already exists but company is not 
				//create company Item
				$row = & JTable::getInstance('Companies','OspropertyTable');
				$row->id = 0;
				$row->company_name = "Sample Company";
				$row->user_id = $uid;
				$row->address = "5 Sinnamon Beach Way ";
				$row->state = $state;
				$row->country = $country;
				$row->city = $city;
				$row->postcode = "100000";
				$row->email = "company@osproperty.com";
				$row->website = "http://osproperty.ext4joomla.com";
				$row->photo = "companylogo.png";
				$row->company_description ="Sample company";
				$row->published = 1;
				$row->store();
				$company_id = $db->insertID();
			}
		}
		
		//create agent
		
		$db->setQuery("Select count(id) from #__users where username like 'agent'");
		$count = $db->loadResult();
		if($count == 0){
			//insert new user agent
			$uid = HelperOspropertyCommon::createUser('agent','Sample Agent');
			//create agent Item
			$row = & JTable::getInstance('Agent','OspropertyTable');
			$row->id = 0;
			$row->company_id = $company_id;
			$row->name = "Sample Agent";
			$row->user_id = $uid;
			$row->phone = "123456789";
			$row->mobile = "987654321";
			$row->address = "5 Sinnamon Beach Way ";
			$row->state = $state;
			$row->country = $country;
			$row->city = $city;
			$row->email = "agent@osproperty.com";
			$row->photo = "user.png";
			$row->license ="Sample agent license";
			$row->yahoo = "thucdam84";
			$row->skype = "thucdam84";
			$row->facebook = "http://facebook.com";
			$row->published = 1;
			$row->request_to_approval = 0;
			$row->store();
			$agent_id = $row->id;
			
			JFile::copy(JPATH_ROOT.DS."components".DS."com_osproperty".DS."images".DS."assets".DS."user.png",JPATH_ROOT.DS."images".DS."osproperty".DS."agent".DS."user.png");
			JFile::copy(JPATH_ROOT.DS."components".DS."com_osproperty".DS."images".DS."assets".DS."user.png",JPATH_ROOT.DS."images".DS."osproperty".DS."agent".DS."thumbnail".DS."user.png");
			
			$db->setQuery("INSERT INTO #__osrs_company_agents (id,company_id,agent_id) VALUES (NULL,'$company_id','$agent_id')");
			$db->query();
		}else{
			$db->setQuery("Select id from #__users where username like 'agent'");
			$uid = $db->loadResult();
			$db->setQuery("Select id from #__osrs_agents where user_id = '$uid'");
			$agent_id = $db->loadResult();
			if(intval($agent_id) == 0){
				//create agent Item
				$row = & JTable::getInstance('Agent','OspropertyTable');
				$row->id = 0;
				$row->company_id = $company_id;
				$row->name = "Sample Agent";
				$row->user_id = $uid;
				$row->phone = "123456789";
				$row->mobile = "987654321";
				$row->address = "5 Sinnamon Beach Way ";
				$row->state = $state;
				$row->country = $country;
				$row->city = $city;
				$row->email = "agent@osproperty.com";
				$row->photo = "user.png";
				$row->license ="Sample agent license";
				$row->yahoo = "thucdam84";
				$row->skype = "thucdam84";
				$row->facebook = "http://facebook.com";
				$row->published = 1;
				$row->request_to_approval = 0;
				$row->store();
				$agent_id = $row->id;
				
				$db->setQuery("INSERT INTO #__osrs_company_agents (id,company_id,agent_id) VALUES (NULL,'$company_id','$agent_id')");
				$db->query();
			}
		}
		
		//create categories
		$db->setQuery("SELECT COUNT(id) FROM #__osrs_categories");
		$count = $db->loadResult();
		if($count > 0){
			$db->setQuery("DELETE FROM #__osrs_categories");
			$db->query();
		}
		$db->setQuery("INSERT INTO `#__osrs_categories` (`id`, `parent_id`, `category_name`, `category_image`, `category_description`, `access`, `ordering`, `published`) VALUES
(1, 0, 'Additions & Remodels', 'categoryimage.jpg', '<p>Additions &amp; Remodels category</p>', 0, 1, 1),
(3, 0, 'Roofing, Siding & Gutters', 'categoryimage.jpg', 'Roofing, Siding &amp; Gutters category description', 0, 2,1),
(4, 0, 'Flooring', 'categoryimage.jpg', 'Flooring category description', 0, 2,  1),
(5, 0, 'Home Construction', 'categoryimage.jpg', 'Home Construction category description', 0, 3, 1),
(6, 0, 'Advertising listing', 'categoryimage.jpg', 'Advertising listing category', 0, 1,  1),
(7, 0, 'Landscape, Decks & Fences', 'categoryimage.jpg', 'Landscape, Decks &amp; Fences category description', 0, 4, 1),
(8, 0, 'Roofing, Siding & Gutters', 'categoryimage.jpg', 'Roofing, Siding &amp; Gutters category description', 0, 5, 1),
(9, 0, 'Walls & CeilingsWalls & Ceilings', 'categoryimage.jpg', 'Walls &amp; Ceilings category description', 0, 7, 1),
(10, 0, 'Builders, Architects & Designers', 'categoryimage.jpg', 'Builders, Architects &amp; Designers category description', 0, 6,  1),
(11, 0, 'Expert Advice', 'categoryimage.jpg', 'Expert Advice category description', 0, 8, 1);");
		$db->query();
		//property types
		$db->setQuery("SELECT COUNT(id) FROM #__osrs_types");
		$count = $db->loadResult();
		if($count > 0){
			$db->setQuery("DELETE FROM #__osrs_types");
			$db->query();
		}
		$db->setQuery("INSERT INTO `#__osrs_types` (`id`, `type_name`, `type_alias`, `type_description`, `published`) VALUES
						(1, 'for sale', 'for-sale', '', 1),
						(2, 'For lease', 'for-lease', '', 1),
						(3, 'For rent', 'for-rent', '', 1),
						(4, 'Pending', '', '', 1),
						(5, 'Sold', '', '', 1),
						(6, 'For sale or lease', '', '', 1);");
		$db->query();
		
		//amenities
		$db->setQuery("SELECT COUNT(id) FROM #__osrs_amenities");
		$count = $db->loadResult();
		if($count > 0){
			$db->setQuery("DELETE FROM #__osrs_amenities");
			$db->query();
		}
		$db->setQuery("INSERT INTO `#__osrs_amenities` (`id`, `category_id`, `amenities`, `ordering`, `published`) VALUES
					(1, 4, 'Gas Hot Water', 1, 1),
					(2, 6, 'Central Air', 1, 1),
					(3, 0, 'Cable Internet', 1, 1),
					(4, 0, 'Cable TV', 2, 1),
					(5, 0, 'Electric Hot Water', 3, 1),
					(6, 2, 'Freezer', 1, 1),
					(7, 3, 'Swimming Pool', 1, 1),
					(8, 0, 'Skylights', 4, 1),
					(9, 2, 'Microwave', 2, 1),
					(10, 0, 'Sprinkler System', 5, 1),
					(11, 0, 'Wood Stove', 6, 1),
					(12, 5, 'Fruit Trees', 1, 1),
					(13, 7, 'Skylights', 1, 1),
					(14, 2, 'Washer/Dryer', 3, 1),
					(15, 2, 'Dishwasher', 3, 1),
					(16, 7, 'Landscaping', 2, 1),
					(17, 5, 'Boat Slip', 2, 1),
					(18, 8, 'Burglar Alarm', 1, 1),
					(19, 6, 'Carpet Throughout', 2, 1),
					(20, 6, 'Central Vac', 3, 1),
					(21, 5, 'Covered Patio', 3, 1),
					(22, 5, 'Exterior Lighting', 4, 1),
					(23, 5, 'Fence', 5, 1),
					(24, 4, 'Fireplace', 2, 1),
					(25, 5, 'Garage', 6, 1),
					(26, 2, 'Garbage Disposal', 4, 1),
					(27, 4, 'Gas Fireplace', 3, 1),
					(28, 4, 'Gas Stove', 4, 1),
					(29, 5, 'Gazebo', 7, 1),
					(30, 2, 'Grill Top', 5, 1),
					(31, 1, 'Handicap Facilities', 1, 1),
					(32, 6, 'Jacuzi Tub', 4, 1),
					(33, 7, 'Lawn', 3, 1),
					(34, 5, 'Open Deck', 8, 1),
					(35, 5, 'Pasture', 9, 1),
					(36, 4, 'Pellet Stove', 5, 1),
					(37, 4, 'Propane Hot Water', 6, 1),
					(38, 2, 'Range/Oven', 6, 1),
					(39, 2, 'Refrigerator', 7, 1),
					(40, 2, 'RO Combo Gas/Electric', 8, 1),
					(41, 5, 'RV Parking', 10, 1),
					(42, 0, 'Satellite Dish', 7, 1),
					(43, 5, 'Spa/Hot Tub', 11, 1),
					(44, 8, 'Sprinkler System', 2, 1),
					(45, 3, 'Tennis Court', 2, 1),
					(46, 3, 'Football ground', 3, 1),
					(47, 2, 'Trash Compactor', 9, 1),
					(48, 0, 'Water Softener', 8, 1),
					(49, 1, 'Wheelchair Ramp', 2, 1),
					(50, 4, 'Wood Stove', 7, 1);");
		$db->query();
		
		//groups
		$db->setQuery("SELECT COUNT(id) FROM #__osrs_fieldgroups");
		$count = $db->loadResult();
		if($count > 0){
			$db->setQuery("DELETE FROM #__osrs_fieldgroups");
			$db->query();
		}
		$db->setQuery("INSERT INTO `#__osrs_fieldgroups` (`id`, `group_name`, `access`, `ordering`, `published`) VALUES
						(1, 'Property area details', 1, 1, 0),
						(2, 'Sale additional information', 0, 2, 0),
						(3, 'Other features information', 0, 3, 0),
						(4, 'Facts', 0, 4, 1),
						(5, 'Construction', 0, 5, 1),
						(6, 'Other info', 0, 6, 1);");
		$db->query();
		//extra fields
		$db->setQuery("SELECT COUNT(id) FROM #__osrs_extra_fields");
		$count = $db->loadResult();
		if($count > 0){
			$db->setQuery("DELETE FROM #__osrs_extra_fields");
			$db->query();
		}
		$db->setQuery("SELECT COUNT(id) FROM #__osrs_extra_field_options");
		$count = $db->loadResult();
		if($count > 0){
			$db->setQuery("DELETE FROM #__osrs_extra_field_options");
			$db->query();
		}
		$db->setQuery("SELECT COUNT(id) FROM #__osrs_extra_field_types");
		$count = $db->loadResult();
		if($count > 0){
			$db->setQuery("DELETE FROM #__osrs_extra_field_types");
			$db->query();
		}
		$db->setQuery("SELECT COUNT(id) FROM `#__osrs_property_open`");
		$count = $db->loadResult();
		if($count > 0){
			$db->setQuery("DELETE FROM `#__osrs_property_open`");
			$db->query();
		}
		$db->setQuery("SELECT COUNT(id) FROM #__osrs_neighborhood");
		$count = $db->loadResult();
		if($count > 0){
			$db->setQuery("DELETE FROM #__osrs_neighborhood");
			$db->query();
		}
		$db->setQuery("SELECT COUNT(id) FROM `#__osrs_property_price_history`");
		$count = $db->loadResult();
		if($count > 0){
			$db->setQuery("DELETE FROM `#__osrs_property_price_history`");
			$db->query();
		}
		$extrafieldsSql = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_osproperty'.DS.'sql'.DS.'extrafields.osproperty.sql' ;
    	$sql = JFile::read($extrafieldsSql) ;
		$queries = $db->splitSql($sql);
		if (count($queries)) {
			foreach ($queries as $query) {
			$query = trim($query);
			if ($query != '' && $query{0} != '#') {
					$db->setQuery($query);
					$db->query();						
				}	
			}
		}
		//properties
		$db->setQuery("SELECT COUNT(id) FROM #__osrs_properties");
		$count = $db->loadResult();
		if($count > 0){
			$db->setQuery("DELETE FROM #__osrs_properties");
			$db->query();
		}
		$propertiesSql = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_osproperty'.DS.'sql'.DS.'properties.osproperty.sql' ;
    	$sql = JFile::read($propertiesSql) ;
    	$sql = str_replace("@",$agent_id,$sql);
    	$sql = str_replace("city_id",$city,$sql);
    	$sql = str_replace("state_id",$state,$sql);
    	$sql = str_replace("country_id",$country,$sql);
		$queries = $db->splitSql($sql);
		if (count($queries)) {
			foreach ($queries as $query) {
			$query = trim($query);
			if ($query != '' && $query{0} != '#') {
					$db->setQuery($query);
					$db->query();						
				}	
			}
		}
		
		//properties - amenities
		$db->setQuery("SELECT COUNT(id) FROM #__osrs_property_amenities");
		$count = $db->loadResult();
		if($count > 0){
			$db->setQuery("DELETE FROM #__osrs_property_amenities");
			$db->query();
		}
		$propertyAmenitiesSql = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_osproperty'.DS.'sql'.DS.'propertyamenities.osproperty.sql' ;
    	$sql = JFile::read($propertyAmenitiesSql) ;
		$queries = $db->splitSql($sql);
		if (count($queries)) {
			foreach ($queries as $query) {
			$query = trim($query);
			if ($query != '' && $query{0} != '#') {
					$db->setQuery($query);
					$db->query();						
				}	
			}
		}
		//properties - field values
		$db->setQuery("SELECT COUNT(id) FROM #__osrs_property_field_value");
		$count = $db->loadResult();
		if($count > 0){
			$db->setQuery("DELETE FROM #__osrs_property_field_value");
			$db->query();
		}
		$db->setQuery("SELECT COUNT(id) FROM #__osrs_property_field_opt_value");
		$count = $db->loadResult();
		if($count > 0){
			$db->setQuery("DELETE FROM #__osrs_property_field_opt_value");
			$db->query();
		}
		$fieldvalueSql = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_osproperty'.DS.'sql'.DS.'fieldvalue.osproperty.sql' ;
    	$sql = JFile::read($fieldvalueSql) ;
		$queries = $db->splitSql($sql);
		if (count($queries)) {
			foreach ($queries as $query) {
			$query = trim($query);
			if ($query != '' && $query{0} != '#') {
					$db->setQuery($query);
					$db->query();						
				}	
			}
		}
		//propertoes - photos
		$db->setQuery("SELECT COUNT(id) FROM #__osrs_photos");
		$count = $db->loadResult();
		if($count > 0){
			$db->setQuery("DELETE FROM #__osrs_photos");
			$db->query();
		}
		$photoSql = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_osproperty'.DS.'sql'.DS.'photos.osproperty.sql' ;
    	$sql = JFile::read($photoSql) ;
		$queries = $db->splitSql($sql);
		if (count($queries)) {
			foreach ($queries as $query) {
			$query = trim($query);
			if ($query != '' && $query{0} != '#') {
					$db->setQuery($query);
					$db->query();						
				}	
			}
		}
		
		$mainframe->redirect("index.php?option=com_osproperty&task=properties_uploadphotopackages");
	}
	
	/**
	 * List properties
	 *
	 * @param unknown_type $option
	 */
	function properties_list($option){
		global $mainframe,$configClass;
		$db = JFactory::getDBO();
		
		//check the relation between properties and categories
		$db->setQuery("Select id,category_id from #__osrs_properties where id not in (Select pid from #__osrs_property_categories)");
		$ids = $db->loadObjectList();
		if(count($ids) > 0){
			foreach ($ids as $pid){
				$property_id = $pid->id;
				$category_id = $pid->category_id;
				$db->setQuery("Insert into #__osrs_property_categories (id,pid,category_id) values (NULL,'$property_id','$category_id') ");
				$db->query();
			}
		}
		
		$limitstart						= JRequest::getVar('limitstart');
		if(!isset($limitstart)){
			$limitstart					= $mainframe->getUserStateFromRequest('pro_list.filter.limitstart','limit_start',0);
		}
		$mainframe->setUserState('pro_list.filter.limitstart',$limitstart);
		$limit							= JRequest::getVar('limit',0);
		if($limit ==  0){
			$limit     	  	 			= $mainframe->getUserStateFromRequest('pro_list.filter.limit','limit',20);
		}
		
		$agentType 						= JRequest::getInt('agentType',-1);
		$keyword    	  				= $mainframe->getUserStateFromRequest('pro_list.filter.keyword','keyword','');
		$mainframe->setUserState('pro_list.filter.keyword',$keyword);
		$agent_id  	   	  				= $mainframe->getUserStateFromRequest('pro_list.filter.agent_id','agent_id','');
		$mainframe->setUserState('pro_list.filter.agent_id',$agent_id);
		$pro_type  	      				= $mainframe->getUserStateFromRequest('pro_list.filter.pro_type','pro_type','');
		$mainframe->setUserState('pro_list.filter.pro_type',$pro_type);
		$category_id	  				= $mainframe->getUserStateFromRequest('pro_list.filter.category_id','category_id','');
		$mainframe->setUserState('pro_list.filter.category_id',$category_id);
		$country_id 	  				= $mainframe->getUserStateFromRequest('pro_list.filter.country_id','country_id',HelperOspropertyCommon::getDefaultCountry());
		$mainframe->setUserState('pro_list.filter.country_id',$country_id);
		$state_id		  				= $mainframe->getUserStateFromRequest('pro_list.filter.state_id','state_id','');
		$mainframe->setUserState('pro_list.filter.state_id',$state_id);
		$company_id 	  				= $mainframe->getUserStateFromRequest('pro_list.filter.company_id','company_id','');
		$mainframe->setUserState('pro_list.filter.company_id',$company_id);
		$nbath			  				= $mainframe->getUserStateFromRequest('pro_list.filter.nbath','nbath','');
		$mainframe->setUserState('pro_list.filter.nbath',$nbath);
		$nbed       	  				= $mainframe->getUserStateFromRequest('pro_list.filter.nbed','nbed','');
		$mainframe->setUserState('pro_list.filter.nbed',$nbed);
		$nrooms      	  				= $mainframe->getUserStateFromRequest('pro_list.filter.nrooms','nrooms','');
		$mainframe->setUserState('pro_list.filter.nrooms',$nrooms);
		$request 		  				= $mainframe->getUserStateFromRequest('pro_list.filter.request','request','');
		$mainframe->setUserState('pro_list.filter.request',$request);
		$state 			  				= $mainframe->getUserStateFromRequest('pro_list.filter.state','state','');
		$mainframe->setUserState('pro_list.filter.state',$state);
		$state_approval		  			= $mainframe->getUserStateFromRequest('pro_list.filter.state_approval','state_approval','');
		$mainframe->setUserState('pro_list.filter.state_approval',$state_approval);
		$request_to_approval 			= JRequest::getVar('request_to_approval','');
		$mainframe->setUserState('pro_list.filter.request_to_approval',$request_to_approval);
		$from							= JRequest::getVar('from','');
		$property_in_types = "";
		$id_in_types = "";
		$lists['from'] = $from;
		if($from == "oscalendar"){
			$show_date_search_in = $configClass['show_date_search_in'];
			if($show_date_search_in != ""){
				$property_in_types = " and a.pro_type in (".str_replace("|",",",$show_date_search_in).")";
				$id_in_types = " and id in (".str_replace("|",",",$show_date_search_in).")";
			}
		}

        $show_form = 0;

		$lists['request_to_approval']   = $request_to_approval;
			
		$filter_order 	  = JRequest::getVar('filter_order','a.id desc');
		if($filter_order == ""){
			$filter_order = "a.id desc";
		}
		$filter_order_Dir = JRequest::getVar('filter_order_Dir','');
		
		$lists['order'] 	= $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		
		$isfeature		  = JRequest::getVar('isfeature','');
		
		$query = "Select count(a.id) from #__osrs_properties as a"
				." INNER JOIN #__osrs_agents as c on c.id = a.agent_id"
				." LEFT JOIN #__osrs_types as d on d.id = a.pro_type"
				." LEFT JOIN #__osrs_countries as e on e.id = a.country"
				." WHERE 1=1";
		if($keyword != ""){
			$query .= " AND (";
			$query .= " a.pro_name like '%$keyword%' OR";
			$query .= " a.pro_alias like '%$keyword%' OR";
			$query .= " a.pro_small_desc like '%$keyword%' OR";
			$query .= " a.pro_full_desc like '%$keyword%' OR";
			$query .= " a.address like '%$keyword%' OR";
			$query .= " a.state like '%$keyword%' OR";
			$query .= " a.region like '%$keyword%' OR";
			$query .= " a.postcode like '%$keyword%' OR";
			$query .= " a.bed_room like '%$keyword%' OR";
			$query .= " a.bath_room like '%$keyword%' OR";
			$query .= " a.ref like '%$keyword%'";
			$query .= " )";
		}
		//oscalendar
		$query .= $property_in_types;
		if($category_id != ""){
            $show_form = 1;
			$query .= " AND a.id in (Select pid from #__osrs_property_categories where category_id = '$category_id')";
		}
		if($agent_id != ""){
            $show_form = 1;
			$query .= " AND a.agent_id = '$agent_id'";
		}
		if($country_id != ""){
            if(HelperOspropertyCommon::checkCountry()) {
                $show_form = 1;
            }
			$query .= " AND a.country = '$country_id'";
		}
		if($state_id != ""){
            $show_form = 1;
			$query .= " AND a.state = '$state_id'";
		}
		if($pro_type != ""){
            $show_form = 1;
			$query .= " AND a.pro_type = '$pro_type'";
		}
		if($company_id != ""){
            $show_form = 1;
			$query .= " AND c.company_id = '$company_id'";
		}
		if($nbath != ""){
            $show_form = 1;
			$query .= " AND a.bath_room > '$nbath'";
		}
		if($nbed != ""){
            $show_form = 1;
			$query .= " AND a.bed_room > '$nbed'";
		}
		if($nrooms != ""){
            $show_form = 1;
			$query .= " AND a.bath_room > '$nrooms'";
		}
		if($isfeature != ""){
            $show_form = 1;
			$query .= " AND a.isFeatured = '$isfeature'";
		}
		if ($request != ''){
            $show_form = 1;
			$query .= " AND a.request_to_approval = '$request'";
		}
		if ($state != ''){
            $show_form = 1;
			$query .= " AND a.published = '$state'";
		}
		if ($state_approval != ''){
            $show_form = 1;
			$query .= " AND a.approved = '$state_approval'";
		}
		if ($request_to_approval != ""){
            $show_form = 1;
			$query .= " AND a.request_to_approval = '$request_to_approval'";
		}
		if($agentType >= 0){
            $show_form = 1;
			$query .= " AND c.agent_type = '$agentType'";
		}

        $lists['show_form'] = $show_form;

		$db->setQuery($query); 
		$total = $db->loadResult();
		
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total,$limitstart,$limit);
		
		$query = "Select a.*,c.name as agent_name,d.type_name,e.country_name,g.state_name,h.city as city_name from #__osrs_properties as a"
				." INNER JOIN #__osrs_agents as c on c.id = a.agent_id"
				." LEFT JOIN #__osrs_types as d on d.id = a.pro_type"
				." LEFT JOIN #__osrs_countries as e on e.id = a.country"
				." LEFT JOIN #__osrs_states as g on g.id = a.state"
				." LEFT JOIN #__osrs_cities as h on h.id = a.city"
				." WHERE 1=1";
		if($keyword != ""){
			$query .= " AND (";
			$query .= " a.pro_name like '%$keyword%' OR";
			$query .= " a.pro_alias like '%$keyword%' OR";
			$query .= " a.pro_small_desc like '%$keyword%' OR";
			$query .= " a.pro_full_desc like '%$keyword%' OR";
			$query .= " a.address like '%$keyword%' OR";
			$query .= " a.city like '%$keyword%' OR";
			$query .= " a.state like '%$keyword%' OR";
			$query .= " a.region like '%$keyword%' OR";
			$query .= " a.postcode like '%$keyword%' OR";
			$query .= " a.bed_room like '%$keyword%' OR";
			$query .= " a.bath_room like '%$keyword%' OR";
			$query .= " a.ref like '%$keyword%'";
			$query .= " )";
		}
		//oscalendar
		$query .= $property_in_types;
		if($category_id != ""){
			$query .= " AND a.id in (Select pid from #__osrs_property_categories where category_id = '$category_id')";
		}
		if($agent_id != ""){
			$query .= " AND a.agent_id = '$agent_id'";
		}
		if($country_id != ""){
			$query .= " AND a.country = '$country_id'";
		}
		if($state_id != ""){
			$query .= " AND a.state = '$state_id'";
		}
		if($pro_type != ""){
			$query .= " AND a.pro_type = '$pro_type'";
		}
		if($company_id != ""){
			$query .= " AND c.company_id = '$company_id'";
		}
		if($nbath != ""){
			$query .= " AND a.bath_room > '$nbath'";
		}
		if($nbed != ""){
			$query .= " AND a.bed_room > '$nbed'";
		}
		if($nrooms != ""){
			$query .= " AND a.bath_room > '$nrooms'";
		}
		if($isfeature != ""){
			$query .= " AND a.isFeatured = '$isfeature'";
		}
		if ($request != ''){
			$query .= " AND a.request_to_approval = '$request'";
		}
		if ($state_approval != ''){
			$query .= " AND a.approved = '$state_approval'";
		}
		if ($state != ''){
			$query .= " AND a.published = '$state'";
		}
		if ($request_to_approval != ""){
			$query .= " AND a.request_to_approval = '$request_to_approval'";
		}
		if($agentType >= 0){
			$query .= " AND c.agent_type = '$agentType'";
		}
		$query .= " ORDER BY $filter_order $filter_order_Dir";
		$db->setQuery($query,$pageNav->limitstart,$pageNav->limit);
		//echo $db->getQuery();
		$rows = $db->loadObjectList();
		
		if(count($rows) > 0){
			for($i=0;$i<count($rows);$i++){
				$row = $rows[$i];
				$alias = $row->pro_alias;
				if($alias == ""){
					$alias = OSPHelper::generateAlias('property',$row->id);
					$db->setQuery("Update #__osrs_properties set pro_alias = '$alias' where id = '$row->id'");
					$db->query();
					$row->pro_alias = $alias;
				}
				
				//$row->category_name = OSPHelper::getCategoryNamesOfProperty($row->id);
			}
		}
		
		//Company
		$companyArr[] = JHTML::_('select.option','',JText::_('OS_ALL_COMPANIES'));
		$db->setQuery("Select id as value,company_name as text from #__osrs_companies where published = '1' order by company_name");
		$companies = $db->loadObjectList();
		$companyArr = array_merge($companyArr,$companies);
		$lists['company'] = JHTML::_('select.genericlist',$companyArr,'company_id','class="chosen input-medium" onChange="javascript:document.adminForm.submit();"','value','text',$company_id);
		
		//agent
		$agentArr[] = JHTML::_('select.option','',JText::_('OS_ALL_AGENTS'));
		$query  = "Select a.id as value,a.name as text from #__osrs_agents as a inner join #__users as b on b.id = a.user_id where a.published = '1' ";
		if($company_id != ""){
			$query .= " and a.company_id = '$company_id'";
		}
		$query .= " order by a.name";
		$db->setQuery($query);
		$agents = $db->loadObjectList();
		$agentArr   = array_merge($agentArr,$agents);
		$lists['agent'] = JHTML::_('select.genericlist',$agentArr,'agent_id','class="chosen input-medium" onChange="javascript:document.adminForm.submit();"','value','text',$agent_id);
		
		//property types
		$typeArr[] = JHTML::_('select.option','',JText::_('OS_ALL_PROPERTY_TYPES'));
		$db->setQuery("Select id as value,type_name as text from #__osrs_types where published = '1' $id_in_types order by type_name");
		$protypes = $db->loadObjectList();
		$typeArr   = array_merge($typeArr,$protypes);
		$lists['type'] = JHTML::_('select.genericlist',$typeArr,'pro_type','class="chosen input-large" onChange="javascript:document.adminForm.submit();"','value','text',$pro_type);
		
		//categories
		$lists['category'] = OspropertyProperties::listFilterCategories($category_id,'onChange="javascript:document.adminForm.submit();"');
		//country	
		$lists['country'] = HelperOspropertyCommon::makeCountryList($country_id,'country_id','onChange="javascript:document.adminForm.submit();"',JText::_('OS_ALL_COUNTRIES'),'class="chosen input-medium"');
		
		//state
		$lists['states'] = HelperOspropertyCommon::makeStateList($country_id,$state_id,'state_id','onChange="javascript:document.adminForm.submit();"',JText::_('OS_ALL_STATES'),'class="chosen input-medium"');
		
		//number bed rooms
		$bedArr[] = JHTML::_('select.option','',JText::_('OS_MIN_BEDS'));
		for($i=1;$i<=20;$i++){
			$bedArr[] = JHTML::_('select.option',$i,$i);
		}
		$lists['nbed'] = JHTML::_('select.genericlist',$bedArr,'nbed','style="width:130px;"  class="chosen input-mini" onChange="javascript:document.adminForm.submit();"','value','text',$nbed);
		
		//number bath rooms
		$bathArr[] = JHTML::_('select.option','',JText::_('OS_MIN_BATHS'));
		for($i=1;$i<=20;$i++){
			$bathArr[] = JHTML::_('select.option',$i,$i);
		}
		$lists['nbath'] = JHTML::_('select.genericlist',$bathArr,'nbath','style="width:130px;"  class="chosen input-mini" onChange="javascript:document.adminForm.submit();"','value','text',$nbath);
		
		//number rooms
		$roomsArr[] = JHTML::_('select.option','',JText::_('OS_MIN_ROOMS'));
		for($i=1;$i<=20;$i++){
			$roomsArr[] = JHTML::_('select.option',$i,$i);
		}
		$lists['nrooms'] = JHTML::_('select.genericlist',$roomsArr,'nrooms','style="width:130px;" class="chosen input-mini" onChange="javascript:document.adminForm.submit();"','value','text',$nrooms);
		
		//feature
		$featureArr	   = array();
		$featureArr[]  = JHTML::_('select.option','',JText::_('OS_FEATURED_PROPERTIES'));
		$featureArr[]  = JHTML::_('select.option','0',JText::_('OS_NO'));
		$featureArr[]  = JHTML::_('select.option','1',JText::_('OS_YES'));
		$lists['isfeature'] = JHTML::_('select.genericlist',$featureArr,'isfeature','class="chosen input-medium" onChange="javascript:document.adminForm.submit();"','value','text',$isfeature);
		
		//feature
		$requestApprovalArr	   = array();
		$requestApprovalArr[]  = JHTML::_('select.option','',JText::_('OS_REQUEST_TO_APPROVAL'));
		$requestApprovalArr[]  = JHTML::_('select.option','0',JText::_('OS_NO'));
		$requestApprovalArr[]  = JHTML::_('select.option','1',JText::_('OS_YES'));
		$lists['request_to_approval'] = JHTML::_('select.genericlist',$requestApprovalArr,'request_to_approval','class="chosen input-medium" onChange="javascript:document.adminForm.submit();"','value','text',$request_to_approval);
		
		//published
		$stateArr = array();
		$stateArr[]  = JHTML::_('select.option','',JText::_('OS_PUBLISH_STATE'));
		$stateArr[]  = JHTML::_('select.option','1',JText::_('OS_PUBLISHED'));
		$stateArr[]  = JHTML::_('select.option','0',JText::_('OS_UNPUBLISHED'));
		$lists['state'] = JHTML::_('select.genericlist',$stateArr,'state','style="width:120px;" class="chosen input-medium" onChange="javascript:document.adminForm.submit();"','value','text',$state);
		
		// Request
		$option_request = array();
		$option_request[] = JHTML::_('select.option','',' - '.JText::_('OS_STATE_APPROVAL').' - ');
		$option_request[] = JHTML::_('select.option',1,JText::_('OS_APPROVAL'));
		$option_request[] = JHTML::_('select.option',0,JText::_('OS_UNAPPROVAL'));
		$lists['state_approval'] = JHTML::_('select.genericlist',$option_request,'state_approval','style="width:120px;" class="chosen input-small" onchange="document.adminForm.submit();"','value','text',$state_approval);
		
		//agent type
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option','-1',JText::_('OS_SELECT_USER_TYPE'));
		$optionArr[] = JHTML::_('select.option','0',JText::_('OS_AGENT'));
		$optionArr[] = JHTML::_('select.option','1',JText::_('OS_OWNER'));
		$lists['agentType'] = JHTML::_('select.genericlist',$optionArr,'agentType','class="chosen input-medium" onChange="javascript:document.adminForm.submit();"','value','text',$agentType);
			
		HTML_OspropertyProperties::listProperties($option,$rows,$pageNav,$lists);
	}
	
	/**
	 * Build the select list for parent menu item
	 */
	function listCategories($category_id,$onChangeScript){
		global $mainframe;
		$parentArr = OspropertyProperties::loadCategoryOptions($category_id,$onChangeScript);
		$output = JHTML::_('select.genericlist', $parentArr, 'category_id', 'class="input-medium" '.$onChangeScript, 'value', 'text', $category_id );
		
		return $output;
	}
	/**
	 * Build the select list for parent menu item
	 */
	function listFilterCategories($category_id,$onChangeScript){
		global $mainframe;
		$parentArr = OspropertyProperties::loadCategoryFilterOptions($category_id,$onChangeScript);
		$output = JHTML::_('select.genericlist', $parentArr, 'category_id', 'class="chosen input-large" '.$onChangeScript, 'value', 'text', $category_id );
		return $output;
	}
	
    function loadCategoryFilterOptions($category_id,$onChangeScript){
		global $mainframe;
		$db =& JFactory::getDBO();
		// get a list of the menu items
		// excluding the current cat item and its child elements
		$query = 'SELECT *, id as value,category_name AS title,parent_id as parent ' .
				 ' FROM #__osrs_categories ' .
				 ' WHERE published = 1' .
			 	 ' ORDER BY parent_id, ordering';
		$db->setQuery( $query );
		$mitems = $db->loadObjectList();

		// establish the hierarchy of the menu
		$children = array();

		if ( $mitems )
		{
			// first pass - collect children
			foreach ( $mitems as $v )
			{
				$pt 	= $v->parent_id;
				$list 	= @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}

		// second pass - get an indent list of the items
		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );
		// assemble menu items to the array
		$parentArr 	= array();
		$parentArr[] 	= JHTML::_('select.option',  '', JText::_( 'OS_ALL_CATEGORIES' ) );
		
		foreach ( $list as $item ) {
			if($item->treename != ""){
				//$item->treename = str_replace("&nbsp;","",$item->treename);
			}
			$var = explode("-",$item->treename);
			$treename = "";
			for($i=0;$i<count($var)-1;$i++){
				$treename .= " - ";
			}
			$text = $item->treename;
			$parentArr[] = JHTML::_('select.option',  $item->id,$text);
		}
		return $parentArr;
	}
	
	function loadCategoryOptions($category_id,$onChangeScript){
		global $mainframe;
		$db =& JFactory::getDBO();
		// get a list of the menu items
		// excluding the current cat item and its child elements
//		$query = 'SELECT *' .
		$query = 'SELECT *, id as value,category_name AS title,parent_id as parent ' .
				 ' FROM #__osrs_categories ' .
				 ' WHERE published = 1' .
			 	 ' ORDER BY parent_id, ordering';
		$db->setQuery( $query );
		$mitems = $db->loadObjectList();

		// establish the hierarchy of the menu
		$children = array();

		if ( $mitems )
		{
			// first pass - collect children
			foreach ( $mitems as $v )
			{
				$pt 	= $v->parent_id;
				$list 	= @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}

		// second pass - get an indent list of the items
		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );
		// assemble menu items to the array
		$parentArr 	= array();
		//$parentArr[] 	= JHTML::_('select.option',  '', JText::_( 'OS_ALL_CATEGORIES' ) );
		
		foreach ( $list as $item ) {
			if($item->treename != ""){
				//$item->treename = str_replace("&nbsp;","",$item->treename);
			}
			$var = explode("-",$item->treename);
			$treename = "";
			for($i=0;$i<count($var)-1;$i++){
				$treename .= " - ";
			}
			$text = $item->treename;
			$parentArr[] = JHTML::_('select.option',  $item->id,$text);
		}
		return $parentArr;
	}
	
	/**
	 * Build the multiple select list for parent menu item
	 */
	function listCategoriesCheckboxes($categoryArr){
		global $mainframe;
		$db = JFactory::getDbo();
		$db->setQuery("Select count(id) from #__osrs_categories where published = '1'");
		$count_categories = $db->loadResult();
		$parentArr = OspropertyProperties::loadCategoryBoxes($categoryArr);
	 	ob_start();
	 	?>
	 	<input type="checkbox" name="check_all_cats" id="check_all_cats" value="1" checked onclick="javascript:checkCats()" />&nbsp;&nbsp;<strong><?php echo JText::_('OS_CATEGORIES')?></strong>
	 	<input type="hidden" name="count_categories" id="count_categories" value="<?php echo $count_categories?>" />
	 	<BR />
	 	<?php
	 	for($i=0;$i<count($parentArr);$i++){
	 		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$parentArr[$i];
	 		echo "<BR />";
	 	}
	 	$output = ob_get_contents();
	 	ob_end_clean();
		return $output;
	}
	
	function loadCategoryBoxes($categoryArr){
		global $mainframe;
		$db =& JFactory::getDBO();
		// get a list of the menu items
		// excluding the current cat item and its child elements
//		$query = 'SELECT *' .
		$query = 'SELECT *, id as value,category_name AS title,parent_id as parent ' .
				 ' FROM #__osrs_categories ' .
				 ' WHERE published = 1' .
			 	 ' ORDER BY parent_id, ordering';
		$db->setQuery( $query );
		$mitems = $db->loadObjectList();

		// establish the hierarchy of the menu
		$children = array();

		if ( $mitems )
		{
			// first pass - collect children
			foreach ( $mitems as $v )
			{
				$pt 	= $v->parent_id;
				$list 	= @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}

		// second pass - get an indent list of the items
		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );
		// assemble menu items to the array
		$parentArr 	= array();
		
		foreach ( $list as $item ) {
			if($item->treename != ""){
				$item->treename = str_replace("&nbsp;","",$item->treename);
			}
			$var = explode("-",$item->treename);
			$treename = "";
			for($i=0;$i<count($var)-1;$i++){
				$treename .= "- -";
			}
			$text = $treename.$item->category_name;
			if(in_array($item->value,$categoryArr)){
				$checked = "checked";
			}elseif(count($categoryArr) == 0){
				$checked = "checked";
			}else{
				$checked = "";
			}
			$parentArr[] = '<input type="checkbox" id="all_categories'.$item->value.'" name="categoryArr[]" '.$checked.' value="'.$item->value.'" />&nbsp;&nbsp;'.$text .'';
		}
		return $parentArr;
	}
	
	/**
	 * Property Edit/Add
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function properties_edit($option,$id){
		global $mainframe,$configClass,$languages;
		$db = JFactory::getDBO();
		jimport('joomla.filesystem.folder');
		$document = JFactory::getDocument();
		$row = &JTable::getInstance('Property','OspropertyTable');
		if($id > 0){
			//remove temp folder in tmp
			if(JFolder::exists(JPATH_ROOT.'/tmp/osphoto_'.$id)){
				JFolder::delete(JPATH_ROOT.'/tmp/osphoto_'.$id);
			}
			
			$row->load((int)$id);
			$db->setQuery("Select amen_id from #__osrs_property_amenities where pro_id = '$row->id'");
			$amenitylists = $db->loadOBjectList();
			$amenitylists1 = array();
			if(count($amenitylists) > 0){
				for($i=0;$i<count($amenitylists);$i++){
					$amenitylists1[$i] = $amenitylists[$i]->amen_id;
				}
				$amenitylists = array();
				$amenitylists = $amenitylists1;
			}
			
			$db->setQuery("Select * from #__osrs_photos where pro_id = '$id' order by ordering");
			$photos = $db->loadobjectList();
			$row->photo = $photos;
			
			OSPHelper::createPhotoDirectory($id);
			OSPHelper::movingPhoto($id);
		}else{
			$row->published = 1;
			$row->access = 0;
            $row->isSold = 0;
			$row->isFeatured = 0;
		}
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
		$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
		
		$lists['state'] = JHTML::_('select.genericlist',$optionArr,'published','class="input-mini"','value','text',$row->published);
		$lists['featured'] = JHTML::_('select.genericlist',$optionArr,'isFeatured','class="input-mini"','value','text',$row->isFeatured);
		$lists['approved'] = JHTML::_('select.genericlist',$optionArr,'approved','class="input-mini"','value','text',$row->approved);
		$lists['show_address'] = JHTML::_('select.genericlist',$optionArr,'show_address','class="input-mini"','value','text',$row->show_address);
		$lists['price_call'] = JHTML::_('select.genericlist',$optionArr,'price_call','class="input-mini" onChange="javascript:showPriceFields()"','value','text',$row->price_call);
		
		$lists['property_sold'] = JHTML::_('select.genericlist',$optionArr,'isSold','class="input-mini"','value','text',$row->isSold);
		
		//agent
		$agentArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_AGENT'));
		$query  = "Select a.id as value,a.name as text from #__osrs_agents as a inner join #__users as b on b.id = a.user_id where published = '1' ";
		$query .= " order by a.name";
		$db->setQuery($query);
		$agents = $db->loadObjectList();
		$agentArr   = array_merge($agentArr,$agents);
		$lists['agent'] = JHTML::_('select.genericlist',$agentArr,'agent_id','class="input-small"','value','text',$row->agent_id);
		
		//property types
		$typeArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_PROPERTY_TYPE'));
		$db->setQuery("Select id as value,type_name as text from #__osrs_types where published = '1' order by type_name");
		$protypes = $db->loadObjectList();
		$typeArr   = array_merge($typeArr,$protypes);
		$lists['type'] = JHTML::_('select.genericlist',$typeArr,'pro_type','class="input-large"','value','text',$row->pro_type);
		
		//categories
		//$lists['category'] = OspropertyProperties::listCategories($row->category_id,'');
		$categoryIds = OSPHelper::getCategoryIdsOfProperty($row->id);
		$lists['category'] = OSPHelper::dropdownCategory('categoryIds[]',$categoryIds,'input-large chosen');
		
		$lists['country'] = HelperOspropertyCommon::makeCountryList($row->country,'country','onChange="javascript:loadStateBackend(this.value,\''.$row->state.'\',\''.$row->city.'\')"','','');
		
		if(OSPHelper::userOneState()){
			$lists['states'] = OSPHelper::returnDefaultStateName()."<input type='hidden' name='state' id='state' value='".OSPHelper::returnDefaultState()."'/>";
		}else{
			$lists['states'] = HelperOspropertyCommon::makeStateList($row->country,$row->state,'state','onChange="javascript:loadCityBackend(this.value,\''.$row->city.'\')"',JText::_('OS_SELECT_STATE'),'');
		}
		
		if($id > 0){
			if(intval($row->state) == 0){
				$row->state = OSPHelper::returnDefaultState();
			}
			$lists['city'] = HelperOspropertyCommon::loadCity($option,$row->state,$row->city);
		}else{
			$default_state = 0;
			if(OSPHelper::userOneState()){
				$default_state = OSPHelper::returnDefaultState();
			}
			$lists['city'] = HelperOspropertyCommon::loadCity($option,$default_state,0);
		}
		
		//number bed rooms
		$lists['nbed'] = OSPHelper::dropdropBed('bed_room',$row->bed_room,'input-small chosen','','OS_BED');
		//number bath rooms
		$lists['nbath'] = OSPHelper::dropdropBath('bath_room',$row->bath_room,'input-small chosen','','OS_BATH');
		//number rooms
		$lists['nrooms'] = OSPHelper::dropdropRoom('rooms',$row->rooms,'input-small chosen','','OS_ROOMS');
        //number floors
        $lists['nfloors'] = OSPHelper::dropdropRoom('number_of_floors',$row->number_of_floors,'input-small chosen','','OS_FLOORS');
		
		$accessArr[] = JHTML::_('select.option','0',JText::_('OS_PUBLIC'));
		$accessArr[] = JHTML::_('select.option','1',JText::_('OS_REGISTERED'));
		$accessArr[] = JHTML::_('select.option','2',JText::_('OS_SPECIAL'));
		$lists['access'] = JHTML::_('select.genericlist',$accessArr,'access','class="input-small" size="3"','value','text',$row->access);
		
		$db->setQuery("select * from #__osrs_amenities where published = '1' order by ordering");
		$amenities = $db->loadObjectList();
		
		
		$db->setQuery("Select * from #__osrs_fieldgroups where id in (Select group_id from #__osrs_extra_fields) and published = '1' order by ordering");
		$groups = $db->loadObjectList();
		if(count($groups) > 0){
			for($i=0;$i<count($groups);$i++){
				$group = $groups[$i];
				$extraSql = "";
				if($id > 0){
					$extraSql = " and id in (Select fid from #__osrs_extra_field_types where type_id = '$row->pro_type') ";
				}
				$db->setQuery("Select * from #__osrs_extra_fields where published = '1' and group_id = '$group->id' $extraSql order by ordering");
				$fields = $db->loadObjectList();
				$groups[$i]->fields = $fields;
			}
		}
		
		$timeArr[] = JHTML::_('select.option','',JText::_('OS_NOT_APPLICABLE'));
		$timeArr[] = JHTML::_('select.option','OS_PER_NIGHT',JText::_('OS_PER_NIGHT'));
		$timeArr[] = JHTML::_('select.option','OS_PER_WEEK',JText::_('OS_PER_WEEK'));
		$timeArr[] = JHTML::_('select.option','OS_PER_MONTH',JText::_('OS_PER_MONTH'));
		$timeArr[] = JHTML::_('select.option','OS_PER_SQUARE_FEET',JText::_('OS_PER_SQUARE_FEET'));
		$timeArr[] = JHTML::_('select.option','OS_PER_SQUARE_METRE',JText::_('OS_PER_SQUARE_METRE'));
		$lists['time'] = JHTML::_('select.genericlist',$timeArr,'rent_time','class="input-medium"','value','text',$row->rent_time);
		
		$db->setQuery("Select * from #__osrs_neighborhoodname");
		$neighborhoods = $db->loadObjectList();
		
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
        
        if($row->id){
        	$query = "Select a.keyword from #__osrs_tags as a inner join #__osrs_tag_xref as b on b.tag_id = a.id where b.pid = '$row->id'";
        	$db->setQuery($query);
        	$row->tags = $db->loadColumn();
        }
        
        $db->setQuery("Select id from #__osrs_types order by ordering");
        $types = $db->loadObjectList();
        if(count($types) > 0){
        	foreach ($types as $type){
        		$db->setQuery("Select fid from #__osrs_extra_field_types where type_id = '$type->id'");
        		$type->fields = $db->loadColumn(0);
        	}
        }
		//print_r($types);
		$lists['types'] = $types;
		if($row->id > 0){
			$query = $db->getQuery(true);
			$query->select("*")->from("#__osrs_property_price_history")->where("pid = '$row->id'");
			$db->setQuery($query);
			$lists['history'] = $db->loadObjectList();
			
			$query = $db->getQuery(true);
			$db->setQuery("Select a.* from #__osrs_tags as a inner join #__osrs_tag_xref as b on a.id = b.tag_id where b.pid = '$row->id'");
			//$db->setQuery($query);
			$lists['tags'] = $db->loadObjectList();
			
			$query = $db->getQuery(true);
			$query->select("*")->from("#__osrs_property_history_tax")->where("pid = '$row->id'");
			$db->setQuery($query);
			$lists['tax'] = $db->loadObjectList();
			
			$query = $db->getQuery(true);
			$query->select("*")->from("#__osrs_property_open")->where("pid = '$row->id'")->order("start_from desc");
			$db->setQuery($query);
			$lists['open'] = $db->loadObjectList();
		}
		HTML_OspropertyProperties::editProperty($option,$row,$lists,$amenities,$amenitylists,$groups,$neighborhoods,$translatable);
	}
	
	function getUserInput($agent_id){
		// Initialize variables.
		$html = array();
		//$groups = $this->getGroups();
		//$excluded = $this->getExcluded();
		$link = 'index.php?option=com_osproperty&amp;task=agent_list&amp;&amp;tmpl=component&amp;field=agent_id';

		// Initialize some field attributes.
		$attr = ' class="inputbox"';
		//$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

		// Initialize JavaScript field attributes.
		//$onchange = (string) $this->element['onchange'];

		// Load the modal behavior script.
		JHtml::_('behavior.modal');
		JHtml::_('behavior.modal', 'a.modal_user_id');

		// Build the script.
		$script = array();
		$script[] = '	function jSelectUser_agent_id(id, title) {';
		$script[] = '		var old_id = document.getElementById("agent_id").value;';
		$script[] = '		if (old_id != id) {';
		$script[] = '			document.getElementById("agent_id").value = id;';
		$script[] = '			document.getElementById("agent_id_name").value = title;';
		$script[] = '			' . $onchange;
		$script[] = '		}';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Load the current username if available.
		$table = JTable::getInstance('Agent','OspropertyTable');
		
		if ($agent_id)
		{
			$table->load($agent_id);
		}
		else
		{
			$table->username = JText::_('OS_SELECT_AGENT');
		}

		// Create a dummy text field with the user name.
		$html[] = '<span class="input-append">';
		$html[] = '<input type="text" class="input-medium" id="agent_id_name" value="'.htmlspecialchars($table->name, ENT_COMPAT, 'UTF-8') .'" disabled="disabled" size="35" /><a class="modal btn" title="'.JText::_('JLIB_FORM_CHANGE_USER').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="icon-file"></i> '.JText::_('JLIB_FORM_CHANGE_USER').'</a>';
		$html[] = '</span>';

		// Create the real field, hidden, that stored the user id.
		$html[] = '<input type="hidden" id="agent_id" name="agent_id" value="'.$agent_id.'" />';

		return implode("\n", $html);
	}
	
	/**
	 * Load States
	 *
	 * @param unknown_type $option
	 */
	function loadStates($option){
		global $mainframe;
		$db = JFactory::getDBO();
		$country_id = JRequest::getVar('country_id','');
		$stateArr = array();
		$stateArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_STATE'));
		$query  = "Select id as value,state_name as text from #__osrs_states where 1=1 ";
		if($country_id != ""){
			$query .= " and country_id = '$country_id'";
		}
		$query .= " order by state_name";
		$db->setQuery($query);
		$states = $db->loadObjectList();
		$stateArr   = array_merge($stateArr,$states);
		echo JHTML::_('select.genericlist',$stateArr,'state','class="input-small"','value','text');
		echo '<span class="required">(*)</span>';
	}
	
	/**
	 * Go to list
	 *
	 * @param unknown_type $option
	 */
	function gotolist($option){
		global $mainframe;
		$mainframe->redirect("index.php?option=com_osproperty&task=properties_list");
	}
	
	/**
	 * Copy properties
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function copy($option,$id){
		global $mainframe,$languages,$lang_suffix;
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_properties where id = '$id'");
		$property = $db->loadObject();
		
		$row = &JTable::getInstance('Property','OspropertyTable');
		$row->id = 0;
		$row->pro_name = "Copy: ".$property->pro_name;
		$row->agent_id = $property->agent_id;
		$row->category_id = $property->category_id;
		$row->price = $property->price;
		$row->price_original = $property->price_original;
		$row->curr = $property->curr;
		$row->pro_small_desc = $property->pro_small_desc;
		$row->pro_full_desc = $property->pro_full_desc;
		$row->pro_type = $property->pro_type;
		$row->isFeatured 	 = $property->isFeatured 	;
		$row->note = $property->note;
		$row->lat_add = $property->lat_add;
		$row->long_add = $property->long_add;
		$row->gbase_address = $property->gbase_address;
		$row->price_call = $property->price_call;
		$row->pro_video = $property->pro_video;
		$row->address = $property->address;
		$row->city = $property->city;
		$row->state = $property->state;
		$row->region = $property->region;
		$row->country = $property->country;
		$row->province = $property->province;
		$row->postcode = $property->postcode;
		$row->show_address = $property->show_address;
		$row->metakey = $property->metakey;
		$row->created = date("Y-m-d",time());
		$row->created_by = $property->created_by;
		$row->access 	 = $property->access ;
		$row->publish_up 	 = $property->publish_up ;
		$row->publish_down 	 = $property->publish_down ;
		$row->published 	 = $property->published ;
		$row->approved 	 = $property->approved ;
		$row->pro_pdf 	 = $property->pro_pdf ;
		$row->pro_pdf_file 	 = $property->pro_pdf_file ;
		$row->bed_room 	 = $property->bed_room ;
		$row->bath_room  = $property->bath_room 	 ;
		$row->rooms 	 = $property->rooms ;
		$row->parking 	 = $property->parking ;
		$row->square_feet 	 	 = $property->square_feet 	 ;
		$row->lot_size	 = $property->lot_size;
		$row->number_of_floors 	 = $property->number_of_floors ;
		$row->number_votes 	 = $property->number_votes ;
		$row->total_points 	 = $property->total_points ;
		$row->request_to_approval 	 = $property->request_to_approval ;
		$row->request_featured 	 = $property->request_featured ;
        $row->energy = $property->energy;
        $row->climate = $property->climate;
		$row->hits = 0;
		$row->isSold = $property->isSold;
		$row->soldOn = $property->soldOn;
		$row->store();
		
		$newId = $db->insertid();
		//add into #__osrs_new_properties
        OSPHelper::addPropertyToQueue($newId);

		//multiple language?
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		if($translatable){
			foreach ($languages as $language) {						
				$sef = $language->sef;
				$pro_name_language 			= $property->{'pro_name_'.$sef};
				$pro_alias_language 		= $property->{'pro_alias_'.$sef};
				$pro_small_desc_language 	= $property->{'pro_small_desc_'.$sef};
				$pro_full_desc_language 	= $property->{'pro_full_desc_'.$sef};
				if($pro_name_language != ""){
					$new_property 						= &JTable::getInstance('Property','OspropertyTable');
					$new_property->id 					= $newId;
					$new_property->category_id 			= $property->category_id;
					$new_property->access 				= $property->access;
					$new_property->{'pro_name_'.$sef} 	= $pro_name_language;
					$new_property->store();
				}
				if($pro_alias_language != ""){
					$new_property 						= &JTable::getInstance('Property','OspropertyTable');
					$new_property->id 					= $newId;
					$new_property->category_id 			= $property->category_id;
					$new_property->access 				= $property->access;
					$new_property->{'pro_alias_'.$sef} 	= $pro_alias_language;
					$new_property->store();
				}
				if($pro_small_desc_language != ""){
					$new_property 							= &JTable::getInstance('Property','OspropertyTable');
					$new_property->id 						= $newId;
					$new_property->category_id 				= $property->category_id;
					$new_property->access 					= $property->access;
					$new_property->{'pro_small_desc_'.$sef} = $pro_small_desc_language;
					$new_property->store();
				}
				if($pro_full_desc_language != ""){
					$new_property 							= &JTable::getInstance('Property','OspropertyTable');
					$new_property->id 						= $newId;
					$new_property->category_id 				= $property->category_id;
					$new_property->access 					= $row->access;
					$new_property->{'pro_full_desc_'.$sef} 	= $pro_full_desc_language;
					$new_property->store();
				}
			}
		}
		
		$alias = $property->pro_alias;
		$alias = $alias."_".$newId;
		$db->setQuery("Update #__osrs_properties set pro_alias = '$alias' where id = '$newId'");
		$db->query();
		
		//update extra photos
		jimport('joomla.filesystem.file');
		$db->setQuery("Select * from #__osrs_photos where pro_id = '$id'");
		$photos = $db->loadObjectList();
		
		//prepare copy photos
		//create photo folder
		if(!JFolder::exists(JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$newId)){
			JFolder::create(JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$newId);
			JFolder::create(JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$newId.DS."medium");
			JFolder::create(JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$newId.DS."thumb");
		}
		if(count($photos) > 0){
			for($i=0;$i<count($photos);$i++){
				$photo = $photos[$i];
				$image = $photo->image;
				$image_desc = $photo->image_desc;
				$ordering = $photo->ordering;
				$newimage = "copy".$image;
				$imagepath = JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$id;
				$newimagepath = JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$newId;
				if(JFile::exists($imagepath.DS.$image)){
					JFile::copy($imagepath.DS.$image,$newimagepath.DS.$newimage);
					JFile::copy($imagepath.DS."thumb".DS.$image,$newimagepath.DS."thumb".DS.$newimage);
					JFile::copy($imagepath.DS."medium".DS.$image,$newimagepath.DS."medium".DS.$newimage);
					$image_desc = addslashes($image_desc);
					$db->setQuery("INSERT INTO #__osrs_photos (id,pro_id,image,image_desc,ordering) VALUES (NULL,'$newId','$newimage','$image_desc','$ordering')");
					$db->query();
				}
			}
		}
		
		//amenities
		$db->setQuery("Select * from #__osrs_property_amenities where pro_id = '$id'");
		$amens = $db->loadObjectList();
		if(count($amens) > 0){
			for($i=0;$i<count($amens);$i++){
				$amen = $amens[$i];
				$amen_id = $amen->amen_id;
				$db->setQuery("INSERT INTO #__osrs_property_amenities (id,pro_id,amen_id) VALUES (NULL,'$newId','$amen_id')");
				$db->query();
			}
		}
		
		$db->setQuery("Select * from #__osrs_property_field_value where pro_id = '$id'");
		$fieldvalues = $db->loadOBjectList();
		if(count($fieldvalues) > 0){
			for($i=0;$i<count($fieldvalues);$i++){
				$field_id = $fieldvalues[$i]->field_id;
				$value = $fieldvalues[$i]->value;
				$db->setQuery("INSERT INTO #__osrs_property_field_value (id,pro_id,field_id,`value`) VALUES (NULL,'$newId','$field_id','$value')");
				$db->query();
			}
		}

        $db->setQuery("Select * from #__osrs_property_field_opt_value where pid = '$id'");
        $fieldvalues = $db->loadOBjectList();
        if(count($fieldvalues) > 0){
            for($i=0;$i<count($fieldvalues);$i++){
                $fid = $fieldvalues[$i]->fid;
                $oid = $fieldvalues[$i]->oid;
                $db->setQuery("INSERT INTO #__osrs_property_field_opt_value (id,pid,fid,`oid`) VALUES (NULL,'$newId','$fid','$oid')");
                $db->query();
            }
        }
		
		//copies categories
		$db->setQuery("Select category_id from #__osrs_property_categories where pid = '$id'");
		$category_ids = $db->loadColumn(0);
		if(count($category_ids) > 0){
			foreach ($category_ids as $category_id){
				$db->setQuery("INSERT INTO #__osrs_property_categories (id,pid,category_id) VALUES (NULL,'$newId','$category_id')");
				$db->query();
			}
		}
		
		$db->setQuery("Select * from #__osrs_property_open where pid = '$id'");
		$opens = $db->loadOBjectList();
		if(count($opens) > 0){
			for($i=0;$i<count($opens);$i++){
				$open = $opens[$i];
				$start_from = $open->start_from;
				$end_to = $open->end_to;
				
				$db->setQuery("INSERT INTO #__osrs_property_open (id,pid,start_from,end_to) VALUES (NULL,'$newId','$start_from','$end_to')");
				$db->query();
			}
		}
		
		$db->setQuery("Select * from #__osrs_property_history_tax where pid = '$id'");
		$taxes = $db->loadOBjectList();
		if(count($taxes) > 0){
			for($i=0;$i<count($taxes);$i++){
				$tax = $taxes[$i];
				$tax_year = $tax->tax_year;
				$property_tax = $tax->property_tax;
				$tax_change  = $tax->tax_change;
				$tax_assessment = $tax->tax_assessment;
				$tax_assessment_change = $tax->tax_assessment_change;
				
				$db->setQuery("INSERT INTO #__osrs_property_history_tax (id,pid,tax_year,property_tax,tax_change,tax_assessment,tax_assessment_change) VALUES (NULL,'$newId','$tax_year','$property_tax','$tax_change','$tax_assessment','$tax_assessment_change')");
				$db->query();
			}
		}
		
		$db->setQuery("Select * from #__osrs_property_price_history where pid = '$id'");
		$prices = $db->loadOBjectList();
		if(count($prices) > 0){
			for($i=0;$i<count($prices);$i++){
				$price = $prices[$i];
				$date = $price->date;
				$event = $price->event;
				$price  = $price->price;
				$source  = $price->source;
				
				$db->setQuery("INSERT INTO #__osrs_property_price_history (id,pid,date,event,`price`,`source`) VALUES (NULL,'$newId','$date','$event','$price','$source')");
				$db->query();
			}
		}
		
		$db->setQuery("Select * from #__osrs_neighborhood where pid = '$id'");
		$neighborhoods = $db->loadOBjectList();
		if(count($neighborhoods) > 0){
			for($i=0;$i<count($neighborhoods);$i++){
				$neighborhood = $neighborhoods[$i];
				$neighbor_id = $neighborhood->neighbor_id;
				$mins = $neighborhood->mins;
				$traffic_type = $neighborhood->traffic_type;
				
				$db->setQuery("INSERT INTO #__osrs_neighborhood (id,pid,neighbor_id,mins,traffic_type) VALUES (NULL,'$newId','$neighbor_id','$mins','$traffic_type')");
				$db->query();
			}
		}
		
		$db->setQuery("Select * from #__osrs_tag_xref where pid = '$id'");
		$tags = $db->loadObjectList();
		if(count($tags) > 0){
			foreach($tags as $tag){
				$db->setQuery("INSERT INTO #__osrs_tag_xref (id,tag_id,pid) VALUES (NULL,'$tag->tag_id','$newId')");
				$db->query();
			}
		}
		
		$db->setQuery("Select * from #__osrs_expired where pid = '$id'");
		$expired = $db->loadObject();
		$inform_time = $expired->inform_time;
		$expired_time = $expired->expired_time;
		$send_inform = $expired->send_inform;
		$send_expired = $expired->send_expired;
		$expired_feature_time = $expired->expired_feature_time;
		$send_featured = $expired->send_featured;
		$remove_from_database = $expired->remove_from_database;
		$db->setQuery("Insert into #__osrs_expired (id,pid,inform_time,send_inform,send_expired,expired_time,remove_from_database,send_featured,expired_feature_time) values (NULL,$newId,'$inform_time','$send_inform','$send_expired','$expired_time','$remove_from_database','$send_featured','$expired_feature_time')");
		$db->query();
		
		$msg = JText::_('OS_COPY_COMPLETED');
		$mainframe->redirect("index.php?option=com_osproperty&task=properties_list",$msg);
	}
	
	
	/**
	 * Save
	 * Step 1: Save Information
	 * Step 2: Save image and extra fields
	 *
	 * @param unknown_type $option
	 * @param unknown_type $save
	 */
	function save($option,$save){
		global $mainframe,$configClass,$languages;
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		$limit_photo = $configClass['limit_upload_photos'];
		if(intval($limit_photo) == 0){
			$limit_photo = 24;
		}
		$db = JFactory::getDBO();
		jimport('joomla.filesystem.file');
		//check to see if user uploaded new state
		$country = JRequest::getVar('country',$configClass['show_country_id']);
		$nstate = JRequest::getVar('nstate','');
		if($nstate != ""){
			//insert into state table
			$db->setQuery("Insert into #__osrs_states (id,country_id,state_name,state_code) values (NULL,'$country','$nstate','$nstate')");
			$db->query();
			$state = $db->insertID();
			JRequest::setVar('state',$state);
		}
		
		$db->setQuery("Select * from #__osrs_configuration");
		$configs = $db->loadObjectList();
		
		
		$row = &JTable::getInstance('Property','OspropertyTable');
		$post = JRequest::get('post');
		$row->bind($post);
		$id = JRequest::getVar('id',0);
		if($id > 0){
			$isNew 		= 0;
			$db->setQuery("Select * from #__osrs_properties where id = '$id'");
			$property 	= $db->loadObject();
			$approved 	= $property->approved;
			$isFeatured = $property->isFeatured;
		}else{
			$isNew 		= 1;
			$approved 	= 0;
		}
		
		$remove_pdf = JRequest::getVar('remove_pdf',0);
		if($id > 0){
			$db->setQuery("Select pro_pdf_file from #__osrs_properties where id = '$id'");
			$document_file = $db->loadResult();
		}
		if(is_uploaded_file($_FILES['pro_pdf_file']['tmp_name'])){
			if(!HelperOspropertyCommon::checkIsDocumentFileUploaded('pro_pdf_file')){
				//return to previous page
				?>
				<script language="javascript">
				window.history(-1);
				</script>
				<?php
			}else{
				if(is_uploaded_file($_FILES['pro_pdf_file']['tmp_name'])){
					if($document_file != ""){ // remove old file
						@unlink(JPATH_ROOT.DS."components".DS."com_osproperty".DS."document".DS.$document_file);
					}
					$pro_pdf_file = time()."_".$_FILES['pro_pdf_file']['name'];
					move_uploaded_file($_FILES['pro_pdf_file']['tmp_name'],JPATH_ROOT.DS."components".DS."com_osproperty".DS."document".DS.$pro_pdf_file);
					$row->pro_pdf_file = $pro_pdf_file;
				}
			}
		}
		if($remove_pdf == 1){
			if($document_file != ""){ // remove old file
				@unlink(JPATH_ROOT.DS."components".DS."com_osproperty".DS."document".DS.$document_file);
			}
			$pro_pdf_file = "";
			$row->pro_pdf_file = "";
		}
		
		$pro_video = $_POST['pro_video'];
		$row->pro_video = $pro_video;
		$pro_small_desc = $_POST['pro_small_desc'];
		$row->pro_small_desc = $pro_small_desc;
		$pro_full_desc = $_POST['pro_full_desc'];
		$row->pro_full_desc = $pro_full_desc;
		$note = $_POST['note'];
		$row->note = $note;
		$metakey = $_POST['metakey'];
		$row->metakey = $metakey;
		$metadesc = $_POST['metadesc'];
		$row->metadesc = $metadesc;
		
		$user = JFactory::getUser();
		if($id == 0){
			$row->created = date("Y-m-d",time());
			$row->created_by = $user->id;
			$row->hits =  0;
			$row->modified = $row->created;
		}else{
			$row->modified = date("Y-m-d",time());
			$row->modified_by = $user->id;
		}
		
		$lat_add = JRequest::getVar('lat_add','');
		$long_add = JRequest::getVar('long_add','');
		if(($lat_add == "") or ($long_add == "")){
			include_once(JPATH_SITE.DS."components".DS."com_osproperty".DS."helpers".DS."googlemap.lib.php");
			$city = JRequest::getVar('city',0);
			if($city > 0){
				$db->setQuery("Select city from #__osrs_cities where id = '$city'");
				$city_name = $db->loadResult();
			}
			$address_search = JRequest::getVar('address','').", ".$city_name;
			$state = JRequest::getVar('state','');
			$db->setQuery("Select state_name from #__osrs_states where id = '$state'");
			$sname = $db->loadResult();
			$address_search .= ", ".$sname;
			$country = JRequest::getVar('country','');
			$db->setQuery("Select country_name from #__osrs_countries where id = '$country'");
			$cname = $db->loadResult();
			$address_search .= ", ".$cname;
//			echo $address_search;
			//$q = urlencode($address_search);
			$return = HelperOspropertyGoogleMap::findAddress($option,'',$address_search,1);
			//print_r($return);
			if($return[2] == "OK"){
				$lat_add = $return[0];
				$long_add = $return[1];
			}
		}
		
		$row->lat_add = $lat_add;
		$row->long_add = $long_add;
		//store into database
		if (!$row->store()) {
			JError::raiseError(500, $row->getError() );
		}
		//print_r($row);
		//die();
		//get id
		if($id == 0){
			$id = $db->insertID();
			$isNew = 1;
            //version 2.7.5
            //add ID of property into New Properties Table
            //$db->setQuery("Insert into #__osrs_new_properties (id,pid) values (NULL,'$id')");
            //$db->query();
            OSPHelper::addPropertyToQueue($id);

			JFolder::create(JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$id);
			JFolder::create(JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$id.DS."thumb");
			JFolder::create(JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$id.DS."medium");
			if($row->approved == 1){
				//setup the expired time
				OspropertyProperties::updateStatus($option,"approved",1,$id);
			}
			if($row->isFeatured == 1){
				//setup the expired time for feature
				OspropertyProperties::updateStatus($option,"isFeatured",1,$id);
			}
		}else{
			$isNew = 0;
			if(($row->approved == 1) and ($approved == 0)){
				//set up expired time
				OspropertyProperties::updateStatus($option,"approved",1,$id);
			}
			if(($row->isFeatured == 1) and ($isFeatured == 0)){
				//set up expired time for feature
				OspropertyProperties::updateStatus($option,"isFeatured",1,$id);
			}
		}
		
		
		//Update into Property table
		$categoryIds = Jrequest::getVar('categoryIds',null,array());
		$db->setQuery("Delete from #__osrs_property_categories where pid = '$id'");
		$db->query();
		if(count($categoryIds) > 0){
			foreach ($categoryIds as $catid){
				$db->setQuery("Insert into #__osrs_property_categories (id,pid,category_id) values (NULL,'$id','$catid')");
				$db->query();
			}
		}
		
		//alias
		$pro_alias = JRequest::getVar('pro_alias','');
		if($pro_alias == ""){
			$pro_alias = OSPHelper::generateAlias('property',$id,'');
		}else{
			$pro_alias = OSPHelper::generateAlias('property',$id,$pro_alias);
		}
		$db->setQuery("Update #__osrs_properties set pro_alias = '$pro_alias' where id = '$id'");
		$db->query();
		
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		if($translatable){
			foreach ($languages as $language){	
				$sef = $language->sef;
				
				$pro_name_language 			= JRequest::getVar('pro_name_'.$sef,'');
				$pro_small_desc_language 	= $_POST['pro_small_desc_'.$sef];
				$pro_full_desc_language 	= $_POST['pro_full_desc_'.$sef];
				
				$metakey_language			= $_POST['metakey_'.$sef];
				$metadesc_language			= $_POST['metadesc_'.$sef];
				
				if($pro_name_language == ""){
					$pro_name_language = $row->pro_name;
					$property = &JTable::getInstance('Property','OspropertyTable');
					$property->id = $id;
					$property->{'pro_name_'.$sef} = $pro_name_language;
					$property->store();
				}
				
				if($pro_small_desc_language == ""){
					$pro_small_desc_language = $row->pro_small_desc;
				}
				if($pro_small_desc_language != ""){
					$property = &JTable::getInstance('Property','OspropertyTable');
					$property->id = $id;
					$property->access = $row->access;
					$property->category_id = $row->category_id;
					$property->{'pro_small_desc_'.$sef} = $pro_small_desc_language;
					$property->store();
				}
				
				if($pro_full_desc_language == ""){
					$pro_full_desc_language = $row->pro_full_desc;
				}
				if($pro_full_desc_language != ""){
					$property = &JTable::getInstance('Property','OspropertyTable');
					$property->id = $id;
					$property->access = $row->access;
					$property->category_id = $row->category_id;
					$property->{'pro_full_desc_'.$sef} = $pro_full_desc_language;
					$property->store();
				}
				
				if($metadesc_language == ""){
					$metadesc_language = $metadesc;
				}
				if($metadesc_language != ""){
					$db->setQuery("Update #__osrs_properties set `metadesc_".$sef."` = ".$db->quote($metadesc_language)." where  id = '$id'");
					$db->query();
				}
				
				if($metakey_language == ""){
					$metakey_language = $metakey;
				}
				if($metakey_language != ""){
					$db->setQuery("Update #__osrs_properties set `metakey_".$sef."` = ".$db->quote($metakey_language)." where  id = '$id'");
					$db->query();
				}
				
				$pro_alias = JRequest::getVar('pro_alias_'.$sef);
				$pro_alias = OSPHelper::generateAliasMultipleLanguages('property',$id,$pro_alias,$sef);
				$db->setQuery("Update #__osrs_properties set pro_alias_".$sef." = '$pro_alias' where id = '$id'");
				$db->query();
			}
		}
		
		/*
		$tags = JRequest::getVar('tags');
		$tagArr = json_decode($tags);
		$db->setQuery("Delete from #__osrs_tag_xref where pid = '$id'");
		$db->query();
		if(count($tagArr) > 0){
			for($i=0;$i<count($tagArr);$i++){
				$tag = $tagArr[$i];
				$tag = htmlspecialchars($tag);
				$db->setQuery("Select count(id) from #__osrs_tags where keyword like '$tag'");
				$count = $db->loadResult();
				if($count == 0){
					$tagobj = &Jtable::getInstance('Tag','OspropertyTable');
					$tagobj->id = 0;
					$tagobj->keyword = $tag;
					$tagobj->published = 1;
					$tagobj->store();
					$tagid = $db->insertID();
				}else{ 
					$db->setQuery("Select id from #__osrs_tags where keyword like '$tag'");
					$tagid = $db->loadResult();
				}
				$db->setQuery("Insert into #__osrs_tag_xref (id,pid,tag_id) values (NULL,'$id','$tagid')");
				$db->query();
			}
		}
		*/
		
		//collect the id of the photos
		$photoIds = array();
		
		//save photos and extra fields
		if($isNew == 0){
			$db->setQuery("Select * from #__osrs_photos where pro_id = '$id' order by ordering");
			$photos = $db->loadObjectList();
			if(count($photos) > 0){
				for($i=0;$i<count($photos);$i++){
					$j = $i + 1;
					$photo = $photos[$i];
					$remove_name   = "remove_".$photo->id;
					$photo_name    = "photo_".$j;
					$desc_name     = "photodesc_".$j;
					$ordering_name = "ordering_".$j;
					$remove        = JRequest::getVar($remove_name,0);
					$photorecord   = &JTable::getInstance('Photo','OspropertyTable');
					$photorecord->id = $photo->id;
					$description   = $_POST[$desc_name];
					$photorecord->image_desc = $description;
					$photorecord->ordering   = JRequest::getVar($ordering_name,0);
					
					if(is_uploaded_file($_FILES[$photo_name]['tmp_name'])){
						if(!HelperOspropertyCommon::checkIsPhotoFileUploaded($photo_name)){
							//return to previous page
							?>
							<script language="javascript">
							window.history(-1);
							</script>
							<?php
						}else{
							$image_name = md5($_FILES[$photo_name]['name']);
							$image_name = OSPHelper::processImageName($id.time().str_replace(" ","",$image_name).".jpg");
							$original_image_link = JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$id.DS.$image_name;
							move_uploaded_file($_FILES[$photo_name]['tmp_name'],$original_image_link);
							//check to resize the max (width or height) size
							HelperOspropertyCommon::returnMaxsize($original_image_link);
							//copy and resize
							//thumb
							$thumb_width = $configClass['images_thumbnail_width'];
							$thumb_height = $configClass['images_thumbnail_height'];
							$thumb_image_link = JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$id.DS."thumb".DS.$image_name;
							JFile::copy($original_image_link,$thumb_image_link);
							OSPHelper::resizePhoto($thumb_image_link,$thumb_width,$thumb_height);
							
							//medium
						    $medium_width = $configClass['images_large_width'];
						    $medium_height = $configClass['images_large_height'];
						    $medium_image_link = JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$id.DS."medium".DS.$image_name;
						    JFile::copy($original_image_link,$medium_image_link);
						    OSPHelper::resizePhoto($medium_image_link,$medium_width,$medium_height);
						    
						    $photorecord->image = $image_name;
						    
						    //add into the array
						    $photoIds[] = $photo->id;
						}
					}
					
					if($remove == 1){
						$db->setQuery("Select image from #__osrs_photos where id = '$photo->id'");
						$image_link = $db->loadResult();
						$original_image = JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$id.DS.$image_link;
						$medium_image = JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$id.DS."medium".DS.$image_link;
						$thumb_image = JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$id.DS."thumb".DS.$image_link;
						@unlink($original_image);
						@unlink($medium_image);
						@unlink($thumb_image);
						
						$db->setQuery("Delete from #__osrs_photos where id = '$photo->id'");
						$db->query();
						$db->setQuery("Delete from #__osrs_watermark where pid = '$id' and image like '$image_link'");
						$db->query();
					}else{
						//save the image
						$photorecord->store();
					}
				}
			}
		}//end edit photos of property
		
		//with new photos of the property
		$newphoto = JRequest::getVar('newphoto',0);
		for($i=$newphoto;$i<=$limit_photo;$i++){
			$j = $i + 1;
			$photo_name    = "photo_".$j;
			$desc_name     = "photodesc_".$j;
			$ordering_name = "ordering_".$j;
			$photorecord   = &JTable::getInstance('Photo','OspropertyTable');
			$photorecord->id = 0;
			$description   = $_POST[$desc_name];
			$photorecord->image_desc = $description;
			$photorecord->pro_id     = $id;
			$db->setQuery("Select ordering from #__osrs_photos where pro_id = '$id' order by ordering desc limit 1");
			$ordering = $db->loadResult();
			$photorecord->ordering   = $ordering +1;
			
			if(is_uploaded_file($_FILES[$photo_name]['tmp_name'])){
				if(!HelperOspropertyCommon::checkIsPhotoFileUploaded($photo_name)){
					//return to previous page
					?>
					<script language="javascript">
					window.history(-1);
					</script>
					<?php
				}else{
					$image_name = md5($_FILES[$photo_name]['name']);
					$image_name = OSPHelper::processImageName($id.time().str_replace(" ","",$image_name).".jpg");
					$original_image_link = JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$id.DS.$image_name;
					move_uploaded_file($_FILES[$photo_name]['tmp_name'],$original_image_link);
					
					HelperOspropertyCommon::returnMaxsize($original_image_link);
					//copy and resize
					//thumb
					$thumb_width = $configClass['images_thumbnail_width'];
					$thumb_height = $configClass['images_thumbnail_height'];
					$thumb_image_link = JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$id.DS."thumb".DS.$image_name;
					JFile::copy($original_image_link,$thumb_image_link);
					OSPHelper::resizePhoto($thumb_image_link,$thumb_width,$thumb_height);
					
					//medium
				    $medium_width = $configClass['images_large_width'];
				    $medium_height = $configClass['images_large_height'];
				    $medium_image_link = JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$id.DS."medium".DS.$image_name;
				    JFile::copy($original_image_link,$medium_image_link);
				    OSPHelper::resizePhoto($medium_image_link,$medium_width,$medium_height);
					
				    $photorecord->image = $image_name;
				    //save the image
					$photorecord->store();
					
					$new_photo_id = $db->insertID();
					$photoIds[] = $new_photo_id;
				}
			}
		}
		//end new photos of the property

		//let save extra fields
		
		$db->setQuery("Select * from #__osrs_extra_fields where published = '1'");
		$fields = $db->loadObjectList();
		if(count($fields) > 0){
			//delete all data from value table
			$db->setQuery("Delete from #__osrs_property_field_value where pro_id = '$id'");
			$db->query();
			for($i=0;$i<count($fields);$i++){
				$field = $fields[$i];
				HelperOspropertyFields::saveField($field,$id);
			}
		}
		//end save extra field
		
		//save convenience
		$amenities = JRequest::getVar('amenities');
		$db->setQuery("Delete from #__osrs_property_amenities where pro_id = '$id'");
		$db->query();
		if(count($amenities) > 0){
			for($i=0;$i<count($amenities);$i++){
				$amen_id = $amenities[$i];
				$db->setQuery("Insert into #__osrs_property_amenities (id,pro_id,amen_id) values (NULL,'$id','$amen_id')");
				$db->query();
			}
		}
		
		//save neighborhood
		$db->setQuery("Delete from #__osrs_neighborhood where pid = '$id'");
		$db->query();
		$db->setQuery("Select id from #__osrs_neighborhoodname");
		$neighborhoods = $db->loadObjectList();
		if(count($neighborhoods) > 0){
			for($i=0;$i<count($neighborhoods);$i++){
				$neighborhood = $neighborhoods[$i];
				$neighborhood_checkbox_name = "nei_".$neighborhood->id;
				$neighborhood_checkbox_value = JRequest::getVar($neighborhood_checkbox_name,0);
				if($neighborhood_checkbox_value == 1){
					$mins_name = "mins_nei_".$neighborhood->id;
					$mins = JRequest::getVar($mins_name,'');
					$traffic_name = "traffic_type_".$neighborhood->id;
					$traffic_type = JRequest::getVar($traffic_name,'0');
					$db->setQuery("Insert into #__osrs_neighborhood (id,pid,neighbor_id,mins,traffic_type) values (NULL,'$id','$neighborhood->id','$mins','$traffic_type')");
					$db->query();
				}
			}
		}
		
		//upload Zip file
		$zipfile = $_FILES['zip_file']['name'];
		if ($zipfile != '')
		{
			jimport('joomla.filesystem.file');
			jimport('joomla.filesystem.folder');
			$pathExtrart = JPATH_ROOT.DS."tmp".DS."osphotos_".$id;
			if (JFolder::exists($pathExtrart)) JFolder::delete($pathExtrart);
			self::upload_zipfile($id);
		}
		
		//update tags
		$query = $db->getQuery(true);
		$query->delete("#__osrs_tag_xref")->where("pid = '$id'");
		$db->setQuery($query);
		$db->execute();
		
		if($translatable){
			$keyword = JRequest::getVar('keyword');
			if(count($keyword) > 0){
				for($i=0;$i<count($keyword);$i++){
					$tag = htmlspecialchars($keyword[$i]);
					if($tag != ""){
						$sql = "Select count(id) from #__osrs_tags where keyword like '$tag'";
						foreach ($languages as $language){	
							$sef = $language->sef;
							$temp_keyword = JRequest::getVar('keyword_'.$sef);
							$temp_keyword = htmlspecialchars($temp_keyword[$i]);
							$sql .= " and keyword_".$sef." like '$temp_keyword'";
						}
						$db->setQuery($sql);
						$count = $db->loadResult();
						if($count == 0){
							$tagobj = &Jtable::getInstance('Tag','OspropertyTable');
							$tagobj->id = 0;
							$tagobj->keyword = $tag;
							$tagobj->published = 1;
							foreach ($languages as $language){	
								$sef = $language->sef;
								$temp_keyword = JRequest::getVar('keyword_'.$sef);
								$temp_keyword = htmlspecialchars($temp_keyword[$i]);
								$tagobj->{'keyword_'.$sef} = $temp_keyword;
							}
							$tagobj->store();
							$tagid = $db->insertID();
						}else{ 
							$sql = "Select id from #__osrs_tags where keyword like '$tag'";
							foreach ($languages as $language){	
								$sef = $language->sef;
								$temp_keyword = JRequest::getVar('keyword_'.$sef);
								$temp_keyword = htmlspecialchars($temp_keyword[$i]);
								$sql .= " and keyword_".$sef." like '$temp_keyword'";
							}
							$db->setQuery($sql);
							$tagid = $db->loadResult();
						}
						$db->setQuery("Insert into #__osrs_tag_xref (id,pid,tag_id) values (NULL,'$id','$tagid')");
						$db->query();
					}
				}
			}
		}else{
			$keyword = JRequest::getVar('keyword');
			if(count($keyword) > 0){
				foreach ($keyword as $tag){
					$tag = htmlspecialchars($tag);
					if($tag != ""){
						$db->setQuery("Select count(id) from #__osrs_tags where keyword like '$tag'");
						$count = $db->loadResult();
						if($count == 0){
							$tagobj = &Jtable::getInstance('Tag','OspropertyTable');
							$tagobj->id = 0;
							$tagobj->keyword = $tag;
							$tagobj->published = 1;
							$tagobj->store();
							$tagid = $db->insertID();
						}else{ 
							$db->setQuery("Select id from #__osrs_tags where keyword like '$tag'");
							$tagid = $db->loadResult();
						}
						$db->setQuery("Insert into #__osrs_tag_xref (id,pid,tag_id) values (NULL,'$id','$tagid')");
						$db->query();
					}
				}
			}
		}
		
		//update property history
		if($configClass['use_property_history']== 1){
			$query = $db->getQuery(true);
			$query->delete("#__osrs_property_price_history")->where("pid = '$id'");
			$db->setQuery($query);
			$db->execute();
			
			$history_date   = JRequest::getVar('history_date',NULL,array());
			$history_event  = JRequest::getVar('history_event',NULL,array());
			$history_price  = JRequest::getVar('history_price',NULL,array());
			$history_source = JRequest::getVar('history_source',NULL,array());
			if(count($history_date) > 0){
				for($i=0;$i<count($history_date);$i++){
					if(($history_date[$i] != "") and ($history_event[$i] != "") and ($history_price [$i] != "")){
						$query = $db->getQuery(true);
						$columns = array('id','pid','date','event','price','source');
						$query->insert("#__osrs_property_price_history")->columns($columns)->values('NULL,'.$id.','.$db->quote($history_date[$i]).','.$db->quote($history_event[$i]).','.$db->quote($history_price [$i]).','.$db->quote($history_source[$i]).'');
						$db->setQuery($query);
						$db->execute();
					}
				}
			}
			
			$query = $db->getQuery(true);
			$query->delete("#__osrs_property_history_tax")->where("pid = '$id'");
			$db->setQuery($query);
			$db->execute();
			
			$tax_year   			= JRequest::getVar('tax_year',NULL,array());
			$tax_value  			= JRequest::getVar('tax_value',NULL,array());
			$tax_change  			= JRequest::getVar('tax_change',NULL,array());
			$tax_assessment 		= JRequest::getVar('tax_assessment',NULL,array());
			$tax_assessment_change 	= JRequest::getVar('tax_assessment_change',NULL,array());
			if(count($tax_year) > 0){
				for($i=0;$i<count($tax_year);$i++){
					if(($tax_year[$i] != "") and ($tax_value[$i] != "")){
						if($tax_assessment [$i] == ""){
							$tax_assessment [$i] = 0;
						}
						if($tax_assessment_change [$i] == ""){
							$tax_assessment_change [$i] = 0;
						}
						$query = $db->getQuery(true);
						$columns = array('id','pid','tax_year','property_tax','tax_change','tax_assessment','tax_assessment_change');
						$query->insert("#__osrs_property_history_tax")->columns($columns)->values('NULL,'.$id.','.$db->quote($tax_year[$i]).','.$db->quote($tax_value[$i]).','.$db->quote($tax_change[$i]).','.$db->quote($tax_assessment[$i]).','.$tax_assessment_change[$i].'');
						$db->setQuery($query);
						$db->execute();
					}
				}
			}
		}
		
		if($configClass['use_open_house'] == 1){
			$query = $db->getQuery(true);
			$query->delete("#__osrs_property_open")->where("pid = '$id'");
			$db->setQuery($query);
			$db->execute();
			
			$start_from = JRequest::getVar('start_from',null,array());
			$end_to     = JRequest::getVar('end_to',null,array());
			
			if(count($start_from) > 0){
				for($i=0;$i<count($start_from);$i++){
					if(($start_from[$i] != "") and ($end_to[$i]!= "")){
						$query = $db->getQuery(true);
						$columns = array('id','pid','start_from','end_to');
						$query->insert('#__osrs_property_open')->columns($columns)->values('NULL,'.$id.','.$db->quote($start_from[$i]).','.$db->quote($end_to[$i]).'');
						$db->setQuery($query);
						$db->execute();
					}
				}
			}
		}
		
		//OSPHelper::postPropertyToFacebook($property,$isNew);

		//check to see if the system use expired mode
		//in that case, check if the property has been published automatically
		//we should insert the expired date into the database
		//check to see if this property has been saved in expired table
		$db->setQuery("Select count(id) from #__osrs_expired where pid = '$id'");
		$count = $db->loadResult();
		if($count == 0){
			OspropertyProperties::setexpired($option,$id);
		}
		
		//Complete saving;
		if($zipfile != ""){
			$mainframe->redirect("index.php?option=com_osproperty&task=properties_showphotosinzipfile&pid=$id&save=$save");
		}elseif(count($photoIds) == 0){
			//generate water maker image
			OSPHelper::generateWaterMark($id);
			$msg = JText::_('OS_ITEM_SAVED');
			if($save == 1){
				$mainframe->redirect("index.php?option=com_osproperty&task=properties_list",$msg);
			}else{
				$mainframe->redirect("index.php?option=com_osproperty&task=properties_edit&cid[]=$id",$msg);
			}
		}elseif($configClass['custom_thumbnail_photo']==1){
			$mainframe->redirect("index.php?option=com_osproperty&task=properties_generatephoto&pid=$id&save=$save&photoIds=".implode(",",$photoIds));
		}else{
			//generate water maker image
			OSPHelper::generateWaterMark($id);
			$msg = JText::_('OS_ITEM_SAVED');
			if($save == 1){
				$mainframe->redirect("index.php?option=com_osproperty&task=properties_list",$msg);
			}else{
				$mainframe->redirect("index.php?option=com_osproperty&task=properties_edit&cid[]=$id",$msg);
			}
		}
	}
	
	/**
	 * Process Quick upload photo property
	 *
	 * @param unknown_type $option
	 */
	function upload_zipfile($id)
	{
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.archive');
		$pathExtrart = JPATH_ROOT.DS."tmp".DS."osphotos_".$id;
		if (!JFolder::exists($pathExtrart)) JFolder::create($pathExtrart,0777);
		if(is_uploaded_file($_FILES['zip_file']['tmp_name']))
		{
			$filename = time().$_FILES['zip_file']['name'];
			@move_uploaded_file($_FILES['zip_file']['tmp_name'],JPATH_ROOT.DS."tmp".DS.$filename);
			JArchive::extract(JPATH_ROOT."/tmp/".$filename,$pathExtrart);
			JFile::delete(JPATH_ROOT."/tmp/".$filename);
			$folder = JPATH_ROOT."/tmp/osphotos_".$id;
			$images = OSPHelper::getImages($folder);
			foreach ($images as $image)
			{
				OSPHelper::checkImage($folder.DS.$image->name);
			}
		}	
	}
	
	/**
	 * Show photos in the zip file, allow administrator to select to import photos
	 *
	 * @param unknown_type $option
	 */
	function showphotosinzipfile($option){
		global $mainframe;
		$id = JRequest::getInt('pid',0);
		$save = JRequest::getVar('save',0);
		$folder = JPATH_ROOT."/tmp/osphotos_".$id;
		$images = OSPHelper::getImages($folder);
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_properties where id = '$id'");
		$property = $db->loadObject();
		HTML_OspropertyProperties::showPhotoinZipFile($property,$images);
	}
	
	/**
	 * Save photos in zip file
	 *
	 * @param unknown_type $option
	 */
	function savephotosinzipfile($option){
		global $configClass, $mainframe;
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		jimport('joomla.filesystem.file') ;
		jimport('joomla.filesystem.folder') ;
		$newPhotos = JRequest::getVar('newphotos');
		$id = JRequest::getInt('id');
		$save = JRequest::getVar('save',0);
		$path 		= JPATH_SITE.'/images/osproperty/properties/'.$id;
		$thumbPath 	= JPATH_SITE.'/images/osproperty/properties/'.$id.'/thumb';
		$mediumPath = JPATH_SITE.'/images/osproperty/properties/'.$id.'/medium';
		if (!JFolder::exists($path)) JFolder::create($path,777);
		if (!JFolder::exists($thumbPath)) JFolder::create($thumbPath,777);
		if (!JFolder::exists($mediumPath)) JFolder::create($mediumPath,777);
		for ($i = 0; $i < count($newPhotos); $i++)
		{
			
			$srcNewPhotos 	= $newPhotos[$i];
			$descNewPhotos  = uniqid().$srcNewPhotos;
			$src 		= JPATH_SITE.'/tmp/osphotos_'.$id.'/'.$srcNewPhotos;
			$desc 		= $path.'/'.strtolower(str_replace(" ","",$descNewPhotos));
			$descThumb	= $thumbPath.'/'.strtolower(str_replace(" ","",$descNewPhotos));
			$descMedium = $mediumPath.'/'.strtolower(str_replace(" ","",$descNewPhotos));

			
			$max_width_allowed = $configClass['max_width_size'];
			$max_height_allowed = $configClass['max_height_size'];
			JFile::copy($src,$desc);
			//check to resize the max (width or height) size
			HelperOspropertyCommon::returnMaxsize($desc);
			
			//copy and resize
			//thumb
			$thumb_width = $configClass['images_thumbnail_width'];
			$thumb_height = $configClass['images_thumbnail_height'];
			JFile::copy($src,$descThumb);
			OSPHelper::resizePhoto($descThumb,$thumb_width,$thumb_height);
			
			//medium
		    $medium_width = $configClass['images_large_width'];
		    $medium_height = $configClass['images_large_height'];
		    JFile::copy($src,$descMedium);
		    OSPHelper::resizePhoto($descMedium,$medium_width,$medium_height);
		    
		    $photorecord   = &JTable::getInstance('Photo','OspropertyTable');
			$photorecord->id = 0;
			$description   = $_POST['photodesc_'.$i];
			$photorecord->image_desc = $description;
			$photorecord->pro_id     = $id;
			$query->clear();
			$query->select('ordering')
				  ->from('#__osrs_photos')
				  ->where('pro_id='.(int)$id)
				  ->order('ordering DESC LIMIT 1')
			;
			$db->setQuery($query);
			$ordering = $db->loadResult();
			$photorecord->ordering   = $ordering +1;
			$photorecord->image = strtolower(str_replace(" ","",$descNewPhotos));
			$photorecord->store();
			$new_photo_id = $db->insertID();
			$photoIds[] = $new_photo_id;
		}
		JFolder::delete(JPATH_SITE.'/tmp/osphotos_'.$id);
		//Complete saving;
		if(count($photoIds) == 0)
		{
			//generate water maker image
			OSPHelper::generateWaterMark($id);
			$msg = JText::_('OS_ITEM_SAVED');
			$mainframe->redirect("index.php?option=$option&task=properties_edit&cid[]=$id",$msg);
		}
		elseif($configClass['custom_thumbnail_photo']==1)
		{
			$mainframe->redirect("index.php?option=$option&task=properties_generatephoto&pid=$id&save=$save&photoIds=".implode(",",$photoIds));
		}
		else
		{
			//generate water maker image
			OSPHelper::generateWaterMark($id);
			$msg = JText::_('OS_ITEM_SAVED');
			if($save == 1)
			{
				$mainframe->redirect("index.php?option=$option&task=properties_list",$msg);
			}
			else
			{
				$mainframe->redirect("index.php?option=$option&task=properties_edit&cid[]=$id",$msg);
			}
		}
	}
	
	/**
	 * Set expired time for properties
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function setexpired($option,$id){
		global $mainframe,$configs,$configClass;
		$db = JFactory::getDbo();
		$db->setQuery("Select count(id) from #__osrs_expired where pid = '$id'");
		$count = $db->loadResult();
		$use_expired = $configClass['general_use_expiration_management'];
		if($use_expired == 1){
			if($count == 0){
				//check and calculate the expired and clean db time
				$unpublish_time = intval($configClass['general_time_in_days']);
				$remove_time	= intval($configClass['general_unpublished_days']);
				$send_appro		= $configClass['send_approximates'];
				$appro_days		= $configClass['approximates_days'];
				
				$current_time 	= time();
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
				//insert into #__osrs_expired
				$db->setQuery("Insert into #__osrs_expired (id,pid,inform_time,expired_time,remove_from_database) values (NULL,$id,'$inform_time','$unpublish_time','$remove_time')");
				$db->query();
				//update start publishing today
				OspropertyProperties::updateStartPublishing($id);
				
			}else{//in the case this property is already in the expired table
				//check and calculate the expired and clean db time
				$unpublish_time = intval($configClass['general_time_in_days']);
				$remove_time	= intval($configClass['general_unpublished_days']);
				$send_appro		= $configClass['send_approximates'];
				$appro_days		= $configClass['approximates_days'];
				
				$current_time 	= time();
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
				//insert into #__osrs_expired
				$db->setQuery("UPDATE #__osrs_expired SET inform_time = '$inform_time',expired_time='$unpublish_time',remove_from_database='$remove_time' WHERE pid = '$id'");
				$db->query();
				//update start publishing today
				OspropertyProperties::updateStartPublishing($id);
			}
		}
	}	
	
	/**
	 * Crop photos
	 *
	 * @param unknown_type $option
	 */
	function generatePhoto($option){
		global $mainframe;
		$db = JFactory::getDbo();
		$document = JFactory::getDocument();
		$document->addScript(JURI::root()."components/com_osproperty/js/yui/script/yuiloader-min.js");
		$document->addScript(JURI::root()."components/com_osproperty/js/yui/script/yahoo-dom-event.js");
		$document->addScript(JURI::root()."components/com_osproperty/js/yui/script/element-min.js");
		$document->addScript(JURI::root()."components/com_osproperty/js/yui/script/dragdrop-min.js");
		$document->addScript(JURI::root()."components/com_osproperty/js/yui/script/imagecropper-min.js");
		$document->addScript(JURI::root()."components/com_osproperty/js/yui/script/resize-min.js");
		$document->addScript(JURI::root()."components/com_osproperty/js/yui/script/event-min.js");
		$document->addScript(JURI::root()."components/com_osproperty/js/yui/script/dom-min.js");
		
		$document->addStyleSheet(JURI::root()."components/com_osproperty/js/yui/style/fonts-min.css");
		$document->addStyleSheet(JURI::root()."components/com_osproperty/js/yui/style/imagecropper.css");
		$document->addStyleSheet(JURI::root()."components/com_osproperty/js/yui/style/resize.css");
		$document->addStyleSheet(JURI::root()."components/com_osproperty/js/yui/style/yui.css");
		$document->addStyleSheet(JURI::root()."components/com_osproperty/js/yui/style/dpSyntaxHighlighter.css");
		
		$id = JRequest::getVar('pid');
		$db->setQuery("Select pro_name from #__osrs_properties where id = '$id'");
		$pro_name = $db->loadResult();
		$photoIds = JRequest::getVar('photoIds','');
		$save = JRequest::getVar('save',0);
		
		HTML_OspropertyProperties::generatePhotoCrop($option,$id,$photoIds,$save,$pro_name);
	}
	
	
	function skipgeneratePhoto($option){
		global $mainframe;
		$save = JRequest::getVar('save',0);
		$pid = JRequest::getVar('pid');
		//generate water maker image
		OSPHelper::generateWaterMark($pid);
		$msg = JText::_('OS_ITEM_SAVED');
		if($save == 1){
			$mainframe->redirect("index.php?option=com_osproperty&task=properties_list",$msg);
		}else{
			$mainframe->redirect("index.php?option=com_osproperty&task=properties_edit&cid[]=$pid",$msg);
		}
	}
	/**
	 * saving photo
	 *
	 * @param unknown_type $option
	 */
	function savingPhoto($option){
		global $mainframe;
		$db = JFactory::getDbo();
		$pid = JRequest::getVar('pid');
		$photoIds = JRequest::getVar('photoIds','');
		$save = JRequest::getVar('save',0);
		if($photoIds != ""){
			$photoArr = explode(",",$photoIds);
			if(count($photoArr) > 0){
				for($i=0;$i<count($photoArr);$i++){
					$photo_id = $photoArr[$i];
					$db->setQuery("Select image from #__osrs_photos where id = '$photo_id'");
					$photo_name = $db->loadResult();
					//thumbnail first
					$tb = JRequest::getVar('tb_'.$photo_id,0);
					if($tb == 1){
						$top    = JRequest::getVar('tb_t_'.$photo_id,0);
						$left   = JRequest::getVar('tb_l_'.$photo_id,0);
						$height = JRequest::getVar('tb_h_'.$photo_id,0);
						$width  = JRequest::getVar('tb_w_'.$photo_id,0);
						HelperOspropertyCommon::create_photo($top,$left,$height,$width,$photo_name,0,$pid);
					}
					
					$me = JRequest::getVar('me_'.$photo_id,0);
					if($me == 1){
						$top    = JRequest::getVar('me_t_'.$photo_id,0);
						$left   = JRequest::getVar('me_l_'.$photo_id,0);
						$height = JRequest::getVar('me_h_'.$photo_id,0);
						$width  = JRequest::getVar('me_w_'.$photo_id,0);
						HelperOspropertyCommon::create_photo($top,$left,$height,$width,$photo_name,1,$pid);
					}
				}
			}
		}
		
		//generate water maker image
		OSPHelper::generateWaterMark($pid);
		
		$msg = JText::_('OS_ITEM_SAVED');
		if($save == 1){
			$mainframe->redirect("index.php?option=com_osproperty&task=properties_list",$msg);
		}else{
			$mainframe->redirect("index.php?option=com_osproperty&task=properties_edit&cid[]=$pid",$msg);
		}
	}
	
	/**
	 * Approval the list of properties
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function approval($option,$cid){
		global $mainframe;
		$db = JFactory::getDbo();
		if(count($cid) > 0){
			for($i=0;$i<count($cid);$i++){
				$id = $cid[$i];
				$db->setQuery("Update #__osrs_properties set approved = '1', `request_to_approval` = '0' where id = '$id'");
				$db->query();
				OspropertyProperties::updateStatus($option,'approved',1,$id);
			}
		}
		$msg = JText::_('OS_PROPERTY_STATUS_HAVE_BEEN_CHANGED');
		$limit = JRequest::getVar('limit',20);
		$limitstart = JRequest::getVar('limitstart',0);
		$mainframe->redirect("index.php?option=com_osproperty&task=properties_list&limitstart=$limitstart&limit=$limit",$msg);
	}
	
	/**
	 * UnApproval the list of properties
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function unapproval($option,$cid){
		global $mainframe;
		$db = JFactory::getDbo();
		if(count($cid) > 0){
			for($i=0;$i<count($cid);$i++){
				$id = $cid[$i];
				$db->setQuery("Update #__osrs_properties set approved = '0', `request_to_approval` = '0' where id = '$id'");
				$db->query();
				OspropertyProperties::updateStatus($option,'approved',0,$id);
			}
		}
		
		$msg = JText::_('OS_PROPERTY_STATUS_HAVE_BEEN_CHANGED');
		$limit = JRequest::getVar('limit',20);
		$limitstart = JRequest::getVar('limitstart',0);
		$mainframe->redirect("index.php?option=com_osproperty&task=properties_list&limitstart=$limitstart&limit=$limit",$msg);
	}
	
	/**
	 * Change other information type
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function changeType($option,$id){
		global $mainframe,$configClass;
		$db = JFactory::getDBO();
		$type  = JRequest::getVar('type','');
		$value = JRequest::getVar('v','');
		$db->setQuery("Update #__osrs_properties set $type = '$value', `request_to_approval` = '0' where id = '$id'");
		$db->query();
		
		$db->setQuery("Select * from #__osrs_configuration");
		$configs = $db->loadObjectList();
		
		//in the case the property has been approved
		if(($type == "approved") or ($type == "isFeatured")){
			OspropertyProperties::updateStatus($option,$type,$value,$id);
		}
		/*
		if($type == "approved"){
			//check to see if this is the first time admin approves the property
			$db->setQuery("Select * from #__osrs_configuration");
			$configs = $db->loadObjectList();
				
			$db->setQuery("Select * from #__osrs_expired where pid = '$id'");
			$count = $db->loadResult();
			
			//check to see if active email has been sent
			$db->setQuery("Select count(id) from #__osrs_email_log where pid = '$id' and email_id = '6'");
			$sended = ($db->loadResult > 0? 1:0);
			
			if($sended == 0){
				//send email and update expired time
				$db->setQuery("Select b.name,b.email from #__osrs_properties as a inner join #__osrs_agents as b on b.id = a.agent_id where a.id = '$id'");
				$agent = $db->loadObject();
				$emailopt['agentname'] = $agent->name;
				$emailopt['agentemail'] = $agent->email;
				$emailopt['link'] = "<a href='".JURI::root()."index.php?option=com_osproperty&task=property_details&id=$id'>".JURI::root()."index.php?option=com_osproperty&task=property_details&id=$id</a>";
				OspropertyEmail::sendActivedEmail($option,$emailopt);
				
				//after send, update into db
				$db->setQuery("INSERT INTO #__osrs_email_log (id,pid,email_id) values (id,'$id','6')");
				$db->query();
			}
				
			if($count == 0){	
				$use_expired = $configs[14]->fieldvalue;
				if($use_expired == 1) {
					//check and calculate the expired and clean db time
					$unpublish_time = $configs[15]->fieldvalue;
					$remove_time	= $configs[17]->fieldvalue;
					$send_appro		= $configs[91]->fieldvalue;
					$appro_days		= $configs[92]->fieldvalue;
					
					$current_time 	= time();
					$unpublish_time = $current_time + $unpublish_time*24*3600;
					if($send_appro == 1){
						$inform_time = $unpublish_time - $appro_days*24*3600;
					}else{
						$inform_time = "";
					}
					$unpublish_time	= date("Y-m-d H:i:s",$unpublish_time);
					$remove_time    = $current_time + $unpublish_time*24*3600 + $remove_time*24*3600;
					$remove_time	= date("Y-m-d H:i:s",$remove_time);
					//insert into #__osrs_expired
					$db->setQuery("Insert into #__osrs_expired (id,pid,inform_time,expired_time,remove_from_database) values (NULL,$id,'$inform_time','$unpublish_time','$remove_time')");
					$db->query();
				}
			}
		}
		*/
		
		$first_letter = substr($type,0,1);
		$remain_letters = substr($type,1);
		$type = strtoupper($first_letter).$remain_letters;
		$msg = $type." ".JText::_('OS_STATUS_CHANGED');
		$limit = JRequest::getVar('limit',20);
		$limitstart = JRequest::getVar('limitstart',0);
		//print_r($_REQUEST);
		$mainframe->redirect("index.php?option=com_osproperty&task=properties_list&limitstart=$limitstart&limit=$limit",$msg);
	}
	
	/**
	 * Update status
	 *
	 * @param unknown_type $option
	 * @param unknown_type $type
	 * @param unknown_type $state
	 * @param unknown_type $id
	 */
	function updateStatus($option,$type,$state,$id){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$db->setQuery("Select a.pro_name,b.* from #__osrs_properties as a inner join #__osrs_agents as b on b.id = a.agent_id where a.id = '$id'");
		$agent = $db->loadObject();
		$emailopt['agentname'] = $agent->name;
		$emailopt['agentemail'] = $agent->email;
		$emailopt['property'] = $agent->pro_name;
		$link = JURI::root()."index.php?option=com_osproperty&task=property_details&id=$id";
		$emailopt['link'] = "<a href='$link'>".$link."</a>";
		switch ($type){
			case "approved":
				if($state == 0){
					//send unactived email
					OspropertyEmail::sendActivedEmail($option,$id,'listing_deactivated',$emailopt);
				}else{
					//update expired table
					if($configClass['general_use_expiration_management'] == 1){ //allow to update expired ?
						$unpublish_time = $configClass['general_time_in_days'];
						$remove_time	= $configClass['general_unpublished_days'];
						$send_appro		= $configClass['send_approximates'];
						$appro_days		= $configClass['approximates_days'];
						
						$current_time 	= time();
						$unpublish_time = $current_time + $unpublish_time*24*3600;
						$remove_time    = $unpublish_time + $remove_time*24*3600;
						if($send_appro == 1){
							$inform_time = $unpublish_time - $appro_days*24*3600;
							$inform_time = date("Y-m-d H:i:s",$inform_time);
						}else{
							$inform_time = "";
						}
						$unpublish_time = date("Y-m-d H:i:s",$unpublish_time);
						$remove_time    = date("Y-m-d H:i:s",$remove_time);
						$db->setQuery("Select count(id) from #__osrs_expired where pid = '$id'");
						$count = $db->loadResult();
						if($count == 0){
							$db->setQuery("INSERT INTO #__osrs_expired (id, pid, inform_time,expired_time,remove_from_database) VALUES (NULL,'$id','$inform_time','$unpublish_time','$remove_time')");
							$db->query();
						}else{
							$db->setQuery("UPDATE #__osrs_expired SET inform_time = '$inform_time',send_inform='0',expired_time='$unpublish_time',send_expired = '0',remove_from_database = '$remove_time' WHERE pid = '$id'");
							$db->query();
						}
					}
					//send actived email
					OspropertyEmail::sendActivedEmail($option,$id,'listing_activated',$emailopt);
					//update start publishing today
					OspropertyProperties::updateStartPublishing($id);
				}
			break;
			case "isFeatured":
				if($state == 0){
					//send unactived email
					OspropertyEmail::sendActivedEmail($option,$id,'featured_listing_deactivated',$emailopt);
				}else{
					//update expired table
					$db->setQuery("Select count(id) from #__osrs_expired where pid = '$id'");
					$count = $db->loadResult();
					$use_expired = $configClass['general_use_expiration_management'];
					if($use_expired == 1){
						if($count == 0){
							//$general_approval = $configs[12]->fieldvalue;
							//check and calculate the expired and clean db time
							$unpublish_time  = intval($configClass['general_time_in_days']);
							$remove_time	 = intval($configClass['general_unpublished_days']);
							$feature_time    = intval($configClass['general_time_in_days_featured']);
							$send_appro	     = $configClass['send_approximates'];
							$appro_days		 = $configClass['approximates_days'];
							
							$current_time 	 = time();
							$unpublish_time  = $current_time + $unpublish_time*24*3600;
							$feature_time    = $current_time + $feature_time*24*3600;
							$remove_time     = $unpublish_time + $remove_time*24*3600;
							//allow to send the approximates expired day
							if($send_appro == 1){
								$inform_time = $unpublish_time - $appro_days*24*3600;
								$inform_time = date("Y-m-d H:i:s",$inform_time);
							}else{
								$inform_time = "";
							}
							$unpublish_time	 = date("Y-m-d H:i:s",$unpublish_time);
							$remove_time	 = date("Y-m-d H:i:s",$remove_time);
							$feature_time	 = date("Y-m-d H:i:s",$feature_time);
							//insert into #__osrs_expired
							$db->setQuery("Insert into #__osrs_expired (id,pid,inform_time,expired_time,expired_feature_time,remove_from_database) values (NULL,$id,'$inform_time','$unpublish_time','$feature_time','$remove_time')");
							$db->query();
							//update start publishing today
							OspropertyProperties::updateStartPublishing($id);
						}else{
							//$general_approval = $configs[12]->fieldvalue;
							//check and calculate the expired and clean db time
							$db->setQuery("Select * from #__osrs_expired where pid = '$id'");
							$row = $db->loadObject();
							$expired_time    = $row->expired_time;
							$unpublish_time  = strtotime($expired_time);
							
							$remove_time	 = intval($configClass['general_unpublished_days']);
							$feature_time    = intval($configClass['general_time_in_days_featured']);
							$send_appro	     = $configClass['send_approximates'];
							$appro_days		 = $configClass['approximates_days'];
							
							$current_time 	 = time();
							$feature_time    = $current_time + $feature_time*24*3600;
							if($current_time > $unpublish_time){
								$new = 1;
								$unpublish_time = intval($configClass['general_time_in_days']);
								$unpublish_time  = $current_time + $unpublish_time*24*3600;
							}else{
								$new = 0;
								if($feature_time > $unpublish_time){
									$unpublish_time = $feature_time;
								}
							}
							$remove_time     = $unpublish_time + $remove_time*24*3600;
							$remove_time	 = date("Y-m-d H:i:s",$remove_time);
							//allow to send the approximates expired day
							if($send_appro == 1){
								$inform_time = $unpublish_time - $appro_days*24*3600;
								$inform_time = date("Y-m-d H:i:s",$inform_time);
							}else{
								$inform_time = "";
							}
							$unpublish_time	 = date("Y-m-d H:i:s",$unpublish_time);
							$feature_time	 = date("Y-m-d H:i:s",$feature_time);
							//insert into #__osrs_expired
							$db->setQuery("UPDATE #__osrs_expired SET inform_time = '$inform_time',expired_time='$unpublish_time',expired_feature_time= '$feature_time',remove_from_database='$remove_time' WHERE pid = '$id'");
							$db->query();
							
							//update start publishing today
							$db->setQuery("Select publish_up from #__osrs_properties where id = '$id'");
							$publish_up = $db->loadResult();
							if(($publish_up == "0000-00-00") or ($new == 1)){
								OspropertyProperties::updateStartPublishing($id);
							}
						}
					}
					//send actived email
					OspropertyEmail::sendActivedEmail($option,$id,'featured_listing_activated',$emailopt);
				}
			break;
		}
	}
	
	/**
	 * Update start publishing
	 *
	 * @param unknown_type $id
	 */
	function updateStartPublishing($id){
		global $mainframe;
		$db = JFactory::getDbo();
		$time = date("Y-m-d",time());
		$db->setQuery("Update #__osrs_properties set publish_up = '$time' where id = '$id'");
		$db->query();
	}
	
	
	/**
	 * Change status of the extra field(s)
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 * @param unknown_type $state
	 */
	function changState($option,$cid,$state){
		global $mainframe;
		$db = JFactory::getDBO();
		if($cid){
			$cids = implode(",",$cid);
			$db->setQuery("Update #__osrs_properties set published = '$state' where id in ($cids)");
			$db->query();
		}
		$msg = JText::_("OS_ITEM_STATUS_HAS_BEEN_CHANGED");
		$mainframe->redirect("index.php?option=com_osproperty&task=properties_list",$msg);
	}
	
	/**
	 * Remove function
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
 	 function remove($option,$cid){
		global $mainframe;
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		$db = JFactory::getDBO();
		if($cid){
			$cids = implode(",",$cid);
			//remove from properties table
			$db->setQuery("Delete from #__osrs_properties where id in ($cids)");
			$db->query();
			//remove from amenities table
			$db->setQuery("Delete from #__osrs_property_amenities where pro_id in ($cids)");
			$db->query();
			//remove from extra field table
			$db->setQuery("Delete from #__osrs_property_field_value where pro_id in ($cids)");
			$db->query();
			//remove from expired table
			$db->setQuery("Delete from #__osrs_expired where pid in ($cids)");
			$db->query();
			//remove from queue table
			$db->setQuery("Delete from #__osrs_queue where pid in ($cids)");
			$db->query();
			
			//remove images
			$db->setQuery("Select * from #__osrs_photos where pro_id in ($cids)");
			$photos = $db->loadObjectList();
			if(count($photos) > 0){
				for($i=0;$i<count($photos);$i++){
					$photo = $photos[$i];
					$image = $photo->image;
					$image_link = JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$photo->pro_id;
					//@unlink($image_link.DS.$image);
					//@unlink($image_link.DS."thumb".DS.$image);
					//@unlink($image_link.DS."medium".DS.$image);
					JFile::delete($image_link.DS.$image);
					JFile::delete($image_link.DS."thumb".DS.$image);
					JFile::delete($image_link.DS."medium".DS.$image);
				}
			}
			$db->setQuery("Delete from #__osrs_photos where pro_id in ($cids)");
			$db->query();
			foreach ($cid as $id){
				JFolder::delete(JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$id);
			}
		}
		
		$msg = JText::_('OS_ITEM_HAS_BEEN_DELETED');
		$mainframe->redirect("index.php?option=com_osproperty&task=properties_list",$msg);
	}
	
	/**
	 * Print function
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function properties_print($option,$id){
		global $mainframe;
		$db = JFactory::getDBO();
		$row = &JTable::getInstance('Property','OspropertyTable');
		if($id > 0){
			$row->load((int)$id);
			$db->setQuery("Select amen_id from #__osrs_property_amenities where pro_id = '$row->id'");
			$amenitylists = $db->loadOBjectList();
			$amenitylists1 = array();
			if(count($amenitylists) > 0){
				for($i=0;$i<count($amenitylists);$i++){
					$amenitylists1[$i] = $amenitylists[$i]->amen_id;
				}
				$amenitylists = array();
				$amenitylists = $amenitylists1;
			}
			
			$db->setQuery("Select * from #__osrs_photos where pro_id = '$id' order by ordering");
			$photos = $db->loadobjectList();
			$row->photo = $photos;
		}else{
			$row->published = 1;
			$row->access = 0;
		}
		
		$lists['show_address'] = JHTML::_('select.booleanlist', 'show_address', '', $row->show_address);
		$lists['price_call'] = JHTML::_('select.booleanlist', 'price_call', '', $row->price_call);
		
		//agent
		$db->setQuery("SELECT * FROM #__osrs_agents WHERE `published` = '1' AND `id` = '$row->agent_id'");
		$agent = $db->loadObject();
		//agent country;
		$db->setQuery("select country_name from #__osrs_countries where id = '$agent->country'");
		$agent->country_name = $db->loadResult();
		$row->agent = $agent;
		
		//agent state;
		$db->setQuery("select state_name from #__osrs_states where id = '$agent->state'");
		$agent->state_name = $db->loadResult();
		
		//property types
		$db->setQuery("SELECT type_name FROM #__osrs_types WHERE `published` = '1' AND `id` = '$row->pro_type'");
		$lists['type'] = $db->loadResult();
		
		//categories
		//$db->setQuery("SELECT `category_name` FROM #__osrs_categories WHERE `id` = '$row->category_id'");
		$lists['category'] = OSPHelper::getCategoryNamesOfProperty($row->id);
		
		//country
		$db->setQuery("SELECT country_name FROM #__osrs_countries WHERE `id` = '$row->country'");
		$lists['country'] = $db->loadResult();
		
		//states
		$db->setQuery("SELECT state_name FROM #__osrs_states WHERE `id` = '$row->state'");
		$lists['states'] = $db->loadResult();
		
		// access
			$lists['access'][0] = JText::_('Public');
			$lists['access'][1] = JText::_('Registered');
			$lists['access'][2] = JText::_('Special');
		
		$db->setQuery("SELECT * FROM #__osrs_amenities WHERE published = '1' order by amenities");
		$amenities = $db->loadObjectList();
		
		$db->setQuery("Select * from #__osrs_fieldgroups where published = '1' order by ordering");
		$groups = $db->loadObjectList();
		if(count($groups) > 0){
			for($i=0;$i<count($groups);$i++){
				$group = $groups[$i];
				$db->setQuery("Select * from #__osrs_extra_fields where published = '1' and group_id = '$group->id' order by ordering");
				$fields = $db->loadObjectList();
				$groups[$i]->fields = $fields;
			}
		}
		
		HTML_OspropertyProperties::printProperties($option,$row,$lists,$amenities,$amenitylists,$groups);
	}
	
	/**
	 * Backup
	 *
	 * @param unknown_type $option
	 */
	function backup($option){
		global $mainframe;
		HTML_OspropertyProperties::backupForm($option);
	}
	
	/**
	 * Do backup
	 *
	 * @param unknown_type $option
	 */
	function dobackup($option){
        global $mainframe;
        
		jimport('joomla.filesystem.file');
	        
        $database 		= &JFactory::getDBO();
        
        $mailfrom	    = $mainframe->getCfg( 'mailfrom' );
        $fromname	    = $mainframe->getCfg( 'fromname' );
        $livesite	    = $mainframe->getCfg( 'live_site' );
        $host		    = $mainframe->getCfg( 'host' );
        $user		    = $mainframe->getCfg( 'user' );
        $password	    = $mainframe->getCfg( 'password' );
        $db			    = $mainframe->getCfg( 'db' );
        $dp				= $mainframe->getCfg( 'dbprefix' );
        
        $pluginParams   = '';
        $test		= false;

        $backupPath		= JPATH_ROOT.DS.'components'.DS.'com_osproperty'.DS.'backup';
        $checkfileName	= 'ip_checkfile_';
        $current_date 	= date("Y-m-d");
        $dateCheckFile	= $checkfileName.$current_date;
        $continue	= true;

        if (is_writable($backupPath) )  
        {
            if (!$test)
            {
                if (!touch($backupPath.DS.$dateCheckFile)) 
                {
                    $mainframe->setError(sprintf(JText::_('OS_CHECK_FILE_NOT_CREATED'), $backupPath));
                    $continue = false;
                    return false;
                }
            }
        }else{
            $mainframe->setError(sprintf(JText::_('OS_BACKUP_NOT_CREATED'), $backupPath));
            $continue = false;
            return false;
        }

        if ($continue)
        {
            JFile::delete($backupPath.DS.$dateCheckFile);
            $deletefile		= false;
            $compress		= 1;
            $backuppath		= 0;
            $verbose		= 1;

            $objBackup 	= new mysqlBackup();
            //$dp             =& $database->_table_prefix;
            $objBackup->tablesToInclude = array(
                    $dp.'osrs_agents',
                    $dp.'osrs_agent_account',
                    $dp.'osrs_amenities',
                    $dp.'osrs_categories',
                    $dp.'osrs_cities',
                    $dp.'osrs_comments',
                    $dp.'osrs_companies',
                    $dp.'osrs_company_agents',
                    $dp.'osrs_configuration',
                    $dp.'osrs_countries',
                    $dp.'osrs_coupon',
                    $dp.'osrs_csv_forms',
                    $dp.'osrs_emails',
                    $dp.'osrs_email_log',
                    $dp.'osrs_expired',
                    $dp.'osrs_extra_fields',
                    $dp.'osrs_extra_field_options',
                    $dp.'osrs_extra_field_types',
                    $dp.'osrs_photos',
                    $dp.'osrs_favorites',
                    $dp.'osrs_fieldgroups',
                    $dp.'osrs_form_fields',
                    $dp.'osrs_lastcron',
                    $dp.'osrs_order_details',
                    $dp.'osrs_pricegroups',
                    $dp.'osrs_properties',
                    $dp.'osrs_property_amenities',
                    $dp.'osrs_property_categories',
                    $dp.'osrs_property_field_opt_value',
                    $dp.'osrs_property_history_tax',
                    $dp.'osrs_property_open',
                    $dp.'osrs_property_price_history',
                    $dp.'osrs_property_field_value',
                    $dp.'osrs_themes',
                    $dp.'osrs_tags',
                    $dp.'osrs_tag_xref',
                    $dp.'osrs_queue',
                    $dp.'osrs_states',
                    $dp.'osrs_tag_cloud',
                    $dp.'osrs_types',
                    $dp.'osrs_user_coupons',
                    $dp.'osrs_neighborhood',
					$dp.'extra_field_options',
                    $dp.'osrs_user_list',
                    $dp.'osrs_user_list_details',
                    $dp.'osrs_neighborhoodname'
                    );

            //print_r($objBackup->tablesToInclude);
            $result		       = OspropertyProperties::ipBackup($objBackup,$host,$user,$password,$db,$pluginParams,$backupPath,$fromname,$compress,$backuppath);
            $backupFile		   = $objBackup->ip_file_name;

            if($deletefile == "1" && !empty($backupFile) )
            {
                if ($test){
                    echo "Deleting backup file $backupFile";
                    unlink($backupFile);
                }
            }else if($test){
                echo "Not deleting backup file $backupFile";
            }
            $msg = JText::_( 'OS_BACKUP_FILE_READY_TO_DOWNLOAD' ).' - '.$objBackup->ip_file_name;
            $mainframe->redirect("index.php?option=com_osproperty",$msg);
        }else{
            $msg = JText::_( 'OS_BACKUP_FILE_IS_NOT_COMPLETED' );
            $mainframe->redirect("index.php?option=com_osproperty",$msg);
        }        
    }

    function ipBackup(&$objBackup,$host,$user,$password,$db,$pluginParams,$backupPath,$fromname,$compress,$backuppath)
    {
    	global $mainframe;
        $sqlContent			= 'Mysql backup from'.$fromname;
        $drop_tables 		= 0;
        $create_tables 		= 0;
        $struct_only 		= 0;
        $locks 				= 1;
        $comments 			= 1;

        if(!empty($backuppath) && is_dir($backuppath) && @is_writable($backuppath)){
            $backup_dir = $backuppath;
        }else{
            $backup_dir = $backupPath;
        }

        $objBackup->server 	= $host;
        $objBackup->port 		= 3306;
        $objBackup->username 	= $user;
        $objBackup->password 	= $password;
        $objBackup->database 	= $db;
        $objBackup->tables = array();
        $objBackup->drop_tables 	= $drop_tables;
        $objBackup->create_tables 	= $create_tables;
        $objBackup->struct_only 	= $struct_only;
        $objBackup->locks 			= $locks;
        $objBackup->comments 		= $comments;
        $objBackup->backup_dir 	= $backup_dir.DS;
        $objBackup->fname_format 	= 'm_d_Y__H_i_s';
        $objBackup->null_values 	= array( );
        $savetask = MSX_SAVE;
        $filename = '';
        $result_bk = $objBackup->Execute($savetask, $filename, $compress);
        if (!$result_bk)
        {
            $output = $objBackup->error;
        }else{
            $output = $sqlContent.': ' . strftime('%A %d %B %Y  - %T ') . ' ';
        }
        return array('result'=>$result_bk,'output'=>$output);
    }
    
    
    /**
     * Restore database
     *
     * @param unknown_type $option
     */
    function restore($option){
    	global $mainframe;
    	jimport('joomla.filesystem.folder');
    	$filelist      = JFolder::files(JPATH_SITE.DS.'components'.DS.'com_osproperty'.DS.'backup', '.sql.gz');
		$filelistArr   = array();
		$i                 = 1;
		foreach ($filelist as $file):
			$filelistArr[] = JHTML::_('select.option', $i, $file);
			$i++;
		endforeach;
		
		$lists = array();
		$lists['bkfiles'] = JHTML::_('select.genericlist', $filelistArr, 'bkfile', 'size="10" class="input-small" style="width: 300px;"', 'text', 'text');
		HTML_OspropertyProperties::restoreForm($option,$lists);
    }
    
    /**
     * Restore database
     *
     * @param unknown_type $option
     */
    function dorestore($option){
    	global $mainframe;
    	jimport('joomla.filesystem.files');
    	jimport('joomla.filesystem.archive');
        if(!JRequest::getVar('bkfile')){
            $msg = JText::_('OS_NO_FILE_SELECTED');
            $type = 'notice';
            $mainframe->redirect('index.php?option=com_osproperty&task=properties_restore', $msg, $type);
        }
		
		$bak_file      = JPATH_SITE.DS.'components'.DS.'com_osproperty'.DS.'backup'.DS.JRequest::getVar('bkfile');
		
       //echo $bak_file;
       //die();
       //echo JPATH_SITE.DS.'components'.DS.'com_osproperty'.DS.'backup';
       //die();
        if(!JArchive::extract($bak_file, JPATH_SITE.DS.'components'.DS.'com_osproperty'.DS.'backup')){
            $msg = sprintf(JText::_('OS_COULD_NOT_EXTRACT_FILE'), $bak_file);
            $type = 'notice';
            $mainframe->redirect('index.php?option=com_osproperty&task=properties_restore', $msg, $type);
        }
		$text_bak_file = substr($bak_file, 0, strlen($bak_file)-3);
       
        if(!$bquery = JFile::read($text_bak_file)){
            $msg = sprintf(JText::_('OS_COULD_NOT_READ_BACKUP'), $text_bak_file);
            $type = 'notice';
            $mainframe->redirect('index.php?option=com_osproperty&task=properties_restore', $msg, $type);
        }
        
		JFile::delete($text_bak_file);

		$database        = & JFactory::getDBO();
	    $emptying_query  = '';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_agents;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_agent_account;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_amenities;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_categories;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_cities;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_comments;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_companies;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_company_agents;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_configuration;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_countries;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_coupon;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_csv_forms;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_emails;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_email_log;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_expired;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_extra_fields;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_extra_field_options;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_extra_field_types;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_photos;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_favorites;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_fieldgroups;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_form_fields;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_lastcron;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_order_details;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_pricegroups;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_properties;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_property_amenities;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_property_categories;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_property_field_opt_value;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_property_history_tax;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_property_open;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_property_price_history;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_property_field_value;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_themes;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_tags;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_tag_xref;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_queue;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_states;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_tag_cloud;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_types;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_user_coupons;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_neighborhood;';
	    $emptying_query .= 'TRUNCATE TABLE #__osrs_extra_field_options;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_user_list;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_user_list_details;';
        $emptying_query .= 'TRUNCATE TABLE #__osrs_neighborhoodname;';
        
		$jversion = new JVersion();
        if(version_compare($jversion->getShortVersion(), '3.0', 'ge')){
        	$queries = $database->splitSql($emptying_query);
	        foreach($queries as $query){
		        $database->setQuery($query);
		        if(!$database->Query()){
		        	$msg = JText::_('OS_QUERY_EXE_FAILED').' Error1 - '.$database->stderr();
		            $type = 'notice';
		            $mainframe->redirect('index.php?option=com_osproperty&task=properties_restore', $msg, $type);
		        }
	        }
        }else{
        	$database->setQuery ($emptying_query);
	        if(!$database->QueryBatch()) {
	            $msg = JText::_('OS_QUERY_EXE_FAILED').' Error1 - '.$database->stderr();
	            $type = 'notice';
	            $mainframe->redirect('index.php?option=com_osproperty&task=properties_restore', $msg, $type);
	        }
        }
		
		
		$database->setQuery("SET CHARACTER SET 'utf8'");
		if(!$database->Query()){
            $msg = JText::_('OS_QUERY_EXE_FAILED').' Error2 - '.$database->stderr();
            $type = 'notice';
            $mainframe->redirect('index.php?option=com_osproperty&task=properties_restore', $msg, $type);
        }

    	$database->setQuery("SET NAMES 'utf8'");
    	if(!$database->Query()){
            $msg = JText::_('OS_QUERY_EXE_FAILED').' Error3 - '.$database->stderr();
            $type = 'notice';
            $mainframe->redirect('index.php?option=com_osproperty&task=properties_restore', $msg, $type);
        }

        $tmp = array();
        if(version_compare($jversion->getShortVersion(), '3.0', 'ge')){
        	$queries = $database->splitSql($bquery);
	        foreach($queries as $query){
	        	$query = trim($query);
	        	if($query != ""){
			        $database->setQuery(utf8_decode($query));
			        if(!$database->Query()){
			        	$msg = JText::_('OS_QUERY_EXE_FAILED').' Error4 - '.$database->stderr();
			            $type = 'notice';
			            $mainframe->redirect('index.php?option=com_osproperty&task=properties_restore', $msg, $type);
			        }
	        	}
	        }
        }else{
        	$database->setQuery(utf8_decode($bquery));
			if(!$database->QueryBatch()) {
	            $msg = JText::_('OS_QUERY_EXE_FAILED').' Error4 - '.$database->stderr();
	            $type = 'notice';
	            $mainframe->redirect('index.php?option=com_osproperty&task=properties_restore', $msg, $type);
			}
        }
        
		
		$msg = JText::_('OS_QUERY_EXED_SUCCESSFULLY');
		$mainframe->redirect('index.php?option=com_osproperty', $msg, $type);
    	
    }
    
    /**
     * Update state of location
     *
     * @param unknown_type $option
     */
    function changeLocator($option){
    	global $mainframe;
		$country_id = JRequest::getVar('country_id');
		$s = JRequest::getVar('s','0');
		$db = JFactory::getDbo();
		$db->setQuery("UPDATE #__osrs_states set published = '$s' WHERE country_id = '$country_id'");
		$db->query();
		$db->setQuery("UPDATE #__osrs_cities set published = '$s' WHERE country_id = '$country_id'");
		$db->query();
		$mainframe->redirect("index.php?option=com_osproperty","Location state has been saved");
    }
    
    /**
     * Optimize sef urls form
     *
     * @param unknown_type $option
     */
    function optimizesef($option){
    	global $mainframe;
    	
    	HTML_OspropertyProperties::optimizeSefForm($option);
    }
    
    /**
     * Do Optimize sef urls
     *
     * @param unknown_type $option
     */
    function doOptimizeSefUrls($option){
    	global $mainframe;
    	$db = JFactory::getDbo();
    	$db->setQuery("Delete from #__osrs_urls");
    	$db->query();
    	$mainframe->redirect("index.php?option=com_osproperty&task=cpanel_list",JText::_('OS_SEF_URLS_OPTIMIZATION_HAS_BEEN_COMPLETED'));
    }
    
    
    function syncdatabase($option){
    	HTML_OspropertyProperties::syncdatabaseForm($option);
    }
    
    function doSyncdatabase($option){
    	global $mainframe,$lang_suffix, $languages;
    	$db = JFactory::getDbo();
    	//osrs_types
		$db->setQuery("Select * from #__osrs_types");
		$types = $db->loadObjectList();
		//osrs_categories
		$db->setQuery("Select * from #__osrs_categories");
		$categories = $db->loadObjectList();
		//osrs_amenities
		$db->setQuery("Select * from #__osrs_amenities");
		$amenities = $db->loadObjectList();
		//osrs_fieldgroups
		$db->setQuery("Select * from #__osrs_fieldgroups");
		$fieldgroups = $db->loadOBjectList();
		//osrs_extra_fields
		$db->setQuery("Select * from #__osrs_extra_fields");
		$extra_fields = $db->loadObjectList();
		//osrs_field_options
		$db->setQuery("Select * from #__osrs_extra_field_options");
		$field_options = $db->loadObjectList();
		//osrs_property_field_value
		$db->setQuery("Select * from #__osrs_property_field_value");
		$field_values = $db->loadObjectList();
		//osrs_properties
		$db->setQuery("Select * from #__osrs_properties");
		$properties = $db->loadObjectList();
		//osrs_agents
		$db->setQuery("Select * from #__osrs_agents");
		$agents = $db->loadObjectList();
		//osrs_companies
		$db->setQuery("Select * from #__osrs_companies");
		$companies = $db->loadObjectList();
		
    	foreach ($languages as $language) {						
			$sef = $language->sef;
			
			//osrs_types
			for($i=0;$i<count($types);$i++){
				$row = $types[$i];
				$type_name_language = $row->{'type_name_'.$sef};
				if($type_name_language == ""){
					$type_name_language = $row->type_name;
					$type = &JTable::getInstance('Type','OspropertyTable');
					$type->id = $row->id;
					$type->{'type_name_'.$sef} = $type_name_language;
					$type->store();
				}
				$type_alias_language = $row->{'type_alias_'.$sef};
				if($type_alias_language == ""){
					$type_alias_language = $row->type_alias;
					$type = &JTable::getInstance('Type','OspropertyTable');
					$type->id = $row->id;
					$type->{'type_alias_'.$sef} = $type_alias_language;
					$type->store();
				}
			}
			
			//osrs_categories
			for($i=0;$i<count($categories);$i++){
				$row = $categories[$i];
				$category_name_language = $row->{'category_name_'.$sef};
				$category_alias_language = $row->{'category_alias_'.$sef};
				$category_description_language = $row->{'category_description_'.$sef};
				if($category_name_language == ""){
					$category_name_language = $row->category_name;
					$category = &JTable::getInstance('Category','OspropertyTable');
					$category->id = $row->id;
					$category->access = $row->access;
					$category->{'category_name_'.$sef} = $category_name_language;
					$category->store();
				}
				if($category_alias_language == ""){
					$category_alias_language = $row->category_alias;
					$category = &JTable::getInstance('Category','OspropertyTable');
					$category->id = $row->id;
					$category->access = $row->access;
					$category->{'category_alias_'.$sef} = $category_alias_language;
					$category->store();
				}
				if($category_description_language == ""){
					$category_description_language = $row->category_description;
					if($category_description_language != ""){
						$category = &JTable::getInstance('Category','OspropertyTable');
						$category->id = $row->id;
						$category->access = $row->access;
						$category->{'category_description_'.$sef} = $category_description_language;
						$category->store();
					}
				}
			}
			
			//osrs_amenities
			for($i=0;$i<count($amenities);$i++){
				$row = $amenities[$i];
				$amenities_language = $row->{'amenities_'.$sef};
				if($amenities_language == ""){
					$amenities_language = $row->amenities;
					$amenity = &JTable::getInstance('Amenities','OspropertyTable');
					$amenity->id = $row->id;
					$amenity->{'amenities_'.$sef} = $amenities_language;
					$amenity->store();
				}
			}
			
			//osrs_fieldgroups
			for($i=0;$i<count($fieldgroups);$i++){
				$row = $fieldgroups[$i];
				$group_name_language = $row->{'group_name_'.$sef};
				if($group_name_language == ""){
					$group_name_language = $row->group_name;
					$group = &JTable::getInstance('Fieldgroup','OspropertyTable');
					$group->id = $row->id;
					$group->{'group_name_'.$sef} = $group_name_language;
					$group->store();
				}
			}
			
			//osrs_extra_fields
			for($i=0;$i<count($extra_fields);$i++){
				$row = $extra_fields[$i];
				$field_label_language = $row->{'field_label_'.$sef};
				$field_description_language = $row->{'field_description_'.$sef};
				if($field_label_language == ""){
					$field_label_language = $row->field_label;
					$field = &JTable::getInstance('Extrafield','OspropertyTable');
					$field->id = $row->id;
					$field->access = $row->access;
					$field->{'field_label_'.$sef} = $field_label_language;
					$field->store();
				}
				
				if($field_description_language == ""){
					$field_description_language 		= $row->field_description;
					if($field_description_language != ""){
						$field 								= &JTable::getInstance('Extrafield','OspropertyTable');
						$field->id 							= $row->id;
						$field->{'field_description_'.$sef} = $field_description_language;
						$field->store();
					}
				}
			}
			
			//osrs_extra_fields
			for($i=0;$i<count($properties);$i++){
				$row = $properties[$i];
				
				$pro_name_language 			= $row->{'pro_name_'.$sef};
				$pro_alias_language 		= $row->{'pro_alias_'.$sef};
				$pro_small_desc_language 	= $row->{'pro_small_desc_'.$sef};
				$pro_full_desc_language 	= $row->{'pro_full_desc_'.$sef};
				if($pro_name_language == ""){
					$pro_name_language = $row->pro_name;
					$property = &JTable::getInstance('Property','OspropertyTable');
					$property->id = $row->id;
					$property->category_id = $row->category_id;
					$property->access = $row->access;
					$property->{'pro_name_'.$sef} = $pro_name_language;
					$property->store();
				}
				
				if($pro_alias_language == ""){
					$pro_alias_language = $row->pro_alias;
					$property = &JTable::getInstance('Property','OspropertyTable');
					$property->id = $row->id;
					$property->category_id = $row->category_id;
					$property->access = $row->access;
					$property->{'pro_alias_'.$sef} = $pro_alias_language;
					$property->store();
				}
				
				if($pro_small_desc_language == ""){
					$pro_small_desc_language = $row->pro_small_desc;
					$property = &JTable::getInstance('Property','OspropertyTable');
					$property->id = $row->id;
					$property->category_id = $row->category_id;
					$property->access = $row->access;
					$property->{'pro_small_desc_'.$sef} = $pro_small_desc_language;
					$property->store();
				}
				
				if($pro_full_desc_language == ""){
					$pro_full_desc_language = $row->pro_full_desc;
				}
				if($pro_full_desc_language != ""){
					$property = &JTable::getInstance('Property','OspropertyTable');
					$property->id = $row->id;
					$property->category_id = $row->category_id;
					$property->access = $row->access;
					$property->{'pro_full_desc_'.$sef} = $pro_full_desc_language;
					$property->store();
				}
			}
			
			//osrs_agents
			for($i=0;$i<count($agents);$i++){
				$row = $agents[$i];
				$bio_language = $row->{'bio_'.$sef};
				if($bio_language == ""){
					$bio_language 		= $row->bio;
					if($bio_language != ""){
						$agent 								= &JTable::getInstance('Agent','OspropertyTable');
						$agent->id 							= $row->id;
						$agent->{'bio_'.$sef} 				= $bio_language;
						$agent->store();
					}
				}
			}
			
			//osrs_companies
			for($i=0;$i<count($companies);$i++){
				$row = $agents[$i];
				$company_description_language = $row->{'company_description_'.$sef};
				if($company_description_language == ""){
					$company_description_language 		= $row->company_description;
					if($company_description_language != ""){
						$company 								= &JTable::getInstance('Company','OspropertyTable');
						$company->id 							= $row->id;
						$company->user_id 						= $row->user_id;
						$company->{'company_description_'.$sef} = $company_description_language;
						$company->store();
					}
				}
			}
			
			//osrs_extra_field_options
			for($i=0;$i<count($field_options);$i++){
				$row = $field_options[$i];
				$field_option_language = $row->{'field_option_'.$sef};
				if($field_option_language == ""){
					$field_option_language = $row->field_option;
					$fieldOption = &JTable::getInstance('Fieldoption','OspropertyTable');
					$fieldOption->id = $row->id;
					$fieldOption->{'field_option_'.$sef} = $field_option_language;
					$fieldOption->store();
				}
			}
			
			//osrs_extra_field_options
			for($i=0;$i<count($field_values);$i++){
				$row = $field_values[$i];
				$value_language = $row->{'value_'.$sef};
				if($value_language == ""){
					$value_language = $row->value;
					$fieldValue = &JTable::getInstance('Fieldvalue','OspropertyTable');
					$fieldValue->id = $row->id;
					$fieldValue->{'value_'.$sef} = $value_language;
					$fieldValue->store();
				}
			}
    	}
    	$mainframe->redirect("index.php?option=com_osproperty",JText::_('OS_DATABASE_SYNCHRONOUS_COMPLETED'));
    }
    
    /**
     * Re-generate the pictures of properties
     *
     * @param unknown_type $option
     */
    function reGeneratePictures($option){
    	global $mainframe;
    	HTML_OspropertyProperties::reGeneratePicturesForm($option);
    }
    
    /**
     * do generate pictures
     *
     * @param unknown_type $option
     */
    function doReGeneratePictures($option){
    	global $mainframe,$configClass;
    	ini_set('memory_limit','999M');
    	ini_set('max_execution_time','3000');
    	jimport('joomla.filesystem.file');
    	jimport('joomla.fileststem.folder');
    	$db = JFactory::getDbo();
        $last_id = JRequest::getInt('last_id',0);
    	$db->setQuery("Select id from #__osrs_properties order by id limit $last_id,5");
    	$properties = $db->loadObjectList();
    	$nphotos = 0;
    	if(count($properties) > 0){
    		for($i=0;$i<count($properties);$i++){
    			$property = $properties[$i];
    			if(! JFolder::exists(JPATH_ROOT.'/images/osproperty/properties/'.$property->id.'/medium')){
					JFolder::create(JPATH_ROOT.'/images/osproperty/properties/'.$property->id.'/medium');
					JFile::copy(JPATH_ROOT.'/images/osproperty/properties/index.html',JPATH_ROOT.'/images/osproperty/properties/'.$property->id.'/medium/index.html');
				}
				if(! JFolder::exists(JPATH_ROOT.'/images/osproperty/properties/'.$property->id.'/thumb')){
					JFolder::create(JPATH_ROOT.'/images/osproperty/properties/'.$property->id.'/thumb');
					JFile::copy(JPATH_ROOT.'/images/osproperty/properties/index.html',JPATH_ROOT.'/images/osproperty/properties/'.$property->id.'/thumb/index.html');
				}
    			$db->setQuery("Select * from #__osrs_photos where pro_id = '$property->id'");
    			$photos = $db->loadObjectList();
    			if(count($photos) > 0){
    				for($j = 0;$j<count($photos);$j++){
    					$nphotos++;
    					$photo = $photos[$j];
    					$db->setQuery("Delete from #__osrs_watermark where image like '$photo->image' and pid = '$property->id'");
    					$db->query();
    					$originalurl = JPATH_ROOT.'/images/osproperty/properties/'.$property->id.'/'.$photo->image;
    					$mediumurl = JPATH_ROOT.'/images/osproperty/properties/'.$property->id.'/medium/'.$photo->image;
	    				$thumburl  = JPATH_ROOT.'/images/osproperty/properties/'.$property->id.'/thumb/'.$photo->image;
    					if(JFile::exists($originalurl)){
	    					if(JFile::exists($mediumurl)){
	    						JFile::delete($mediumurl);
	    					}
	    					JFile::copy($originalurl,$mediumurl);
	    					$medium_width = $configClass['images_large_width'];
						    $medium_height = $configClass['images_large_height'];
						    OSPHelper::resizePhoto($mediumurl,$medium_width,$medium_height);
						    
						    if(JFile::exists($thumburl)){
	    						JFile::delete($thumburl);
	    					}
	    					JFile::copy($originalurl,$thumburl);
	    					$thumb_width = $configClass['images_thumbnail_width'];
							$thumb_height = $configClass['images_thumbnail_height'];
							OSPHelper::resizePhoto($thumburl,$thumb_width,$thumb_height);
    					}
    				}
    			}
    			//generate water maker image
				OSPHelper::generateWaterMark($property->id);
    		}
            $msg = JText::_('OS_GENERATE_PICTURES')." ".$nphotos." ".JText::_('OS_PICTURES')." ".JText::_('OS_OF')." ".count($properties)." ".JText::_('OS_PROPERTIES');
            $mainframe->redirect("index.php?option=com_osproperty&task=properties_delayReGeneratePictures&last_id=".$property->id,$msg);
    	}else{
            $msg = JText::_('OS_GENERATE_PICTURES')." ".JText::_('OS_COMPLETED');
            $mainframe->redirect("index.php?option=com_osproperty&task=cpanel_list",$msg);
        }
    }

    /**
     * Delay Re-Generate Pictures
     */
    function delayReGeneratePictures($option){
        global $mainframe;
        $last_id = Jrequest::getInt('last_id');
        ?>
        <div class="row-fluid">
            <div class="span12">
                <center>
                    <h2>
                        <?php echo JText::_('OS_PROCESSING_PLEASE_DO_NOT_CLOSE_BROWSER_UNTIL_COMPLETE');?>...
                    </h2>
                </center>
            </div>
        </div>
        <meta http-equiv="refresh" content="0;URL=<?php echo JURI::base()."index.php?option=com_osproperty&task=properties_doReGeneratePictures&last_id=".$last_id;?>" />
    <?php
    }
}
?>