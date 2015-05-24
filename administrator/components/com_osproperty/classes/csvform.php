<?php
/*------------------------------------------------------------------------
# csvform.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class OspropertyCsvform{
	/**
	 * Default form
	 *
	 * @param unknown_type $option
	 * @param unknown_type $task
	 */
	function display($option,$task){
		global $mainframe;
		$cid = JRequest::getVar( 'cid', array(0));
		$id  = JRequest::getVar('id',0);
		JArrayHelper::toInteger($cid, array(0));
		switch ($task){
			case "form_default":
				OspropertyCsvform::defaultList($option);
			break;
			case "form_add":
				OspropertyCsvform::editForm($option,0);
			break;
			case "form_edit":
				OspropertyCsvform::editForm($option,$cid[0]);
			break;
			case "form_cancel":
				$mainframe->redirect("index.php?option=com_osproperty&task=form_default");
			break;
			case "form_save":
				OspropertyCsvform::save($option,1);
			break;
			case "form_apply":
				OspropertyCsvform::save($option,0);
			break;
			case "form_remove":
				OspropertyCsvform::removeForms($option,$cid);
			break;
			case "form_unpublish":
				OspropertyCsvform::form_change_publish($option,$cid,0);	
			break;
			case "form_publish":
				OspropertyCsvform::form_change_publish($option,$cid,1);
			break;
			case "form_downloadcsv":
				OspropertyCsvform::downloadCsv($option,$cid[0]);
			break;
			case "form_importcsv":
				OspropertyCsvform::importCsvForm($option,$cid[0]);
			break;
			case "form_doimportcsv":
				OspropertyCsvform::doImportCSV($option);
			break;
			case "form_importphotoform":
				OspropertyCsvform::photoForm($option);
			break;
			case "form_doimportphoto":
				OspropertyCsvform::doimportPhoto($option,$id);
			break;
			case "form_updateOtherInfor":
				OspropertyCsvform::updateOtherInfor($option,$id);
			break;
			case "form_saveotherinformation":
				OspropertyCsvform::saveotherinformation($option);
			break;
			case "form_completeimport":
				OspropertyCsvform::completeImportCsv($option);
			break;
		}
	}
	
	/**
	 * Complete import CSV
	 *
	 * @param unknown_type $option
	 */
	function completeImportCsv($option){
		global $mainframe;
		$db = JFactory::getDbo();
		$id = JRequest::getVar('id',0);
		$db->setQuery("SELECT * FROM #__osrs_importlog WHERE form_id = '$id'");
		$log = $db->loadObject();
		HTML_OspropertyCsvform::completeImportCsv($option,$log);
	}
	
	/**
	 * Photo form
	 *
	 * @param unknown_type $option
	 */
	function photoForm($option){
		global $mainframe;
		$db = JFactory::getDbo();
		$id = JRequest::getVar('id');
		HTML_OspropertyCsvform::importPhotoForm($option,$id);
	}
	
	
	/**
	 * Do import photo
	 *
	 * @param unknown_type $option
	 */
	function doimportPhoto($option,$id){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_importlog_properties where form_id = '$id'");
		$properties = $db->loadObjectList();
		
		jimport('joomla.filesystem.archive');
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		$db = JFactory::getDbo();
		if(is_uploaded_file($_FILES['photopack']['tmp_name'])){
			if(!HelperOspropertyCommon::checkIsArchiveFileUploaded('photopack')){
				//return to previous page
				?>
				<script language="javascript">
				window.history(-1);
				</script>
				<?php
			}else{
				$filename = time().$_FILES['photopack']['name'];
				move_uploaded_file($_FILES['photopack']['tmp_name'],JPATH_ROOT.DS."tmp".DS.$filename);
				//extract file
				$folder = time();
				JArchive::extract(JPATH_ROOT.DS."tmp".DS.$filename,JPATH_ROOT.DS."tmp".DS.$folder);
				self::photoImportProcessing($properties,$folder);
			}//end is upload
		}else{ //check if import package file from directory
			$photodirectory = Jrequest::getVar('photodirectory','');
			if($photodirectory != ""){
				$photodirectoryArr = explode(".",$photodirectory);
				$ext = strtolower($photodirectoryArr[count($photodirectoryArr)-1]);
				if($ext != "zip"){
					$id = JRequest::getVar('id',0);
					$msg = JText::_('OS_PLEASE_ENTER_ZIP_FILE');
					$mainframe->redirect("index.php?option=com_osproperty&task=form_importphotoform&id=$id",$msg);
				}else{
					$folder = time();
					JArchive::extract($photodirectory,JPATH_ROOT.DS."tmp".DS.$folder);
					self::photoImportProcessing($properties,$folder);
				}
			}
		}
		$id = JRequest::getVar('id',0);
		$msg = JText::_('OS_IMPORT_COMPLETE');
		$mainframe->redirect("index.php?option=com_osproperty&task=form_completeimport&id=$id",$msg);
	}
	
	/**
	 * Import photos
	 *
	 */
	public static function photoImportProcessing($properties,$folder){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		for($i=0;$i<count($properties);$i++){
			$property = $properties[$i];
			$pid 	  = $property->pid;
			if(!JFolder::exists(JPATH_ROOT.'/images/osproperty/properties/'.$pid)){
				JFolder::create(JPATH_ROOT.'/images/osproperty/properties/'.$pid);
				JFolder::create(JPATH_ROOT.'/images/osproperty/properties/'.$pid.'/medium');
				JFolder::create(JPATH_ROOT.'/images/osproperty/properties/'.$pid.'/thumb');
				JFile::copy(JPATH_ROOT.'/images/osproperty/properties/index.html',JPATH_ROOT.'/images/osproperty/properties/'.$pid.'/index.html');
				JFile::copy(JPATH_ROOT.'/images/osproperty/properties/index.html',JPATH_ROOT.'/images/osproperty/properties/'.$pid.'/medium/index.html');
				JFile::copy(JPATH_ROOT.'/images/osproperty/properties/index.html',JPATH_ROOT.'/images/osproperty/properties/'.$pid.'/thumb/index.html');
			}
			$db->setQuery("Select * from #__osrs_photos where pro_id = '$property->pid'");
			//echo $db->getQuery();
			$photos = $db->loadObjectList();
			$pid = $property->pid;
			if(count($photos) > 0){
				for($j=0;$j<count($photos);$j++){
					$photo = $photos[$j];
					$entry = $photo->image;
					$photo_id = $photo->id;
					$newentry = time().rand(1000,9999).$photo->image;
					$property_tmp_link = JPATH_ROOT.DS."tmp".DS.$folder.DS.$photo->image;
					//echo $property_tmp_link;
					//die();
					if(JFile::exists($property_tmp_link)){
						
		        		$property_image_link = JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$pid.DS.$newentry;
		        		$property_medium_link = JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$pid.DS."medium".DS.$newentry;
		        		$property_thumb_link = JPATH_ROOT.DS."images".DS."osproperty".DS."properties".DS.$pid.DS."thumb".DS.$newentry;
		        		JFile::copy($property_tmp_link,$property_image_link);
		        		JFile::copy($property_image_link,$property_medium_link);
		        		JFile::copy($property_image_link,$property_thumb_link);
		        		
		        		//thumb
						$thumb_width = $configClass['images_thumbnail_width'];
						$thumb_height = $configClass['images_thumbnail_height'];
						
						OSPHelper::resizePhoto($property_thumb_link,$thumb_width,$thumb_height);
					    
					    //medium
					    $medium_width = $configClass['images_large_width'];
					    $medium_height = $configClass['images_large_height'];
					    
					    OSPHelper::resizePhoto($property_medium_link,$medium_width,$medium_height);
					    
					    //Update the photo after rename
					    $db->setQuery("UPDATE #__osrs_photos SET image = '$newentry' WHERE id = '$photo_id'");
					    $db->query();
					}
				}
			}
			//update watermark for property
			OSPHelper::generateWaterMark($property->pid);
		}//end for
	}
	
	/**
	 * Update Other Information
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function updateOtherInfor($option,$id){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$db->setQuery("Select a.* from #__osrs_properties as a inner join #__osrs_importlog_properties as b on b.pid = a.id where b.form_id = '$id'");
		$properties = $db->loadObjectList();
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_csv_forms where id = '$id'");
		$row = $db->loadObject();
		$db->setQuery("Select id as value, name as text from #__osrs_agents where published = '1' order by name");
		$agents = $db->loadObjectList();
		$agentArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_AGENT'));
		$agentArr   = array_merge($agentArr,$agents);
		$lists['agentArr'] = $agentArr;
		
		$countryArr = array();
		$stateArr = array();
		$cityArr = array();
		if($configClass['show_country_id'] == ""){
			//country
			$countryArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_COUNTRY'));
			$db->setQuery("Select id as value, country_name as text from #__osrs_countries order by country_name");
			$countries = $db->loadObjectList();
			$countryArr = array_merge($countryArr,$countries);
			$stateArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_STATE'));
		}else{
			
			if(HelperOspropertyCommon::checkCountry()){
				$stateArr = array();
				$countryArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_COUNTRY'));
				$db->setQuery("Select id as value, country_name as text from #__osrs_countries where id in (".$configClass['show_country_id'].") order by country_name");
				$countries = $db->loadObjectList();
				$countryArr = array_merge($countryArr,$countries);
				$stateArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_STATE'));
				$query  = "Select id as value,state_name as text from #__osrs_states where 1=1 ";
				$query .= " order by state_name";
				$db->setQuery($query);
				$states = $db->loadObjectList();
				$stateArr   = array_merge($stateArr,$states);
			}else{
				$stateArr = array();
				$stateArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_STATE'));
				$query  = "Select id as value,state_name as text from #__osrs_states where 1=1 ";
				$query .= " and country_id = ".$configClass['show_country_id'];				
				$query .= " order by state_name";
				$db->setQuery($query);
				$states = $db->loadObjectList();
				$stateArr   = array_merge($stateArr,$states);
			}
		}
		
		$lists['country'] = $countryArr;
		$lists['state']	= $stateArr;
		$cityArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_CITY'));
		$lists['city'] = $cityArr;
		
		$typeArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_PROPERTY_TYPE'));
		$db->setQuery("Select id as value,type_name as text from #__osrs_types where published = '1' order by type_name");
		$protypes = $db->loadObjectList();
		$typeArr   = array_merge($typeArr,$protypes);
		$lists['type'] = $typeArr;
		
		HTML_OspropertyCsvform::updateOtherInformationForm($option,$properties,$row,$lists);
	}
	
	/**
	 * Save Other Information
	 *
	 * @param unknown_type $option
	 */
	function saveotherinformation($option){
		global $mainframe;
		include_once(JPATH_ROOT.DS."components".DS."com_osproperty".DS."helpers".DS."googlemap.lib.php");
		$db = JFactory::getDbo();
		$properties_str = JRequest::getVar('property_str','');
		$propertiesArr = explode(",",$properties_str);
		if(count($propertiesArr) > 0){
			for($i=0;$i<count($propertiesArr);$i++){
				$property_id = $propertiesArr[$i];
				$agent_id_name = "agent_id".$property_id;
				$agent_id	   = JRequest::getVar($agent_id_name,'');
				
				$agent_id_value_name = "agent_id_value".$property_id;
				$agent_id_value		 = JRequest::getVar($agent_id_value_name,'');
				
				if($agent_id == ""){
					$agent_id = $agent_id_value;
				}
				
				$country = JRequest::getVar('country'.$property_id,'');
				$state = JRequest::getVar('state'.$property_id,'');
				$city = JRequest::getVar('city'.$property_id,'');
				$category_id = JRequest::getVar('category_id'.$property_id,'');
				$pro_type = JRequest::getVar('pro_type'.$property_id,'');
				
				$db->setQuery("UPDATE #__osrs_properties SET category_id = '$category_id',pro_type='$pro_type',agent_id = '$agent_id',country = '$country',state='$state',city = '$city' where id = '$property_id'");
				$db->query();
				
				//find lat long address
				$db->setQuery("Select * from #__osrs_properties where id = '$property_id'");
				$property = $db->loadObject();
				$address = $property->address;
				if($property->postcode != ""){
					$address .= ", ".$property->postcode;
				}
				$db->setQuery("select city from #__osrs_cities where id = '$city'");
				$city = $db->loadResult();
				if($city != ""){
					$address .= ", ".$city;
				}
				
				$db->setQuery("select state_name from #__osrs_states where id = '$state'");
				$state = $db->loadResult();
				if($state != ""){
					$address .= ", ".$state;
				}
				
				$db->setQuery("select country_name from #__osrs_countries where id = '$country'");
				$country = $db->loadResult();
				if($country != ""){
					$address .= ", ".$country;
				}
				
				if($address != ""){
					$return = HelperOspropertyGoogleMap::findAddress($option,'',$address,1);
					if($return[2] == "OK"){
						$db->setQuery("UPDATE #__osrs_properties SET lat_add = '".$return[0]."', long_add = '".$return[1]."'  WHERE id = '$property_id'");
						$db->query();
					}
				}
			    
			}
			$db->setQuery("DELETE FROM #__osrs_importlog_properties WHERE pid IN ($properties_str)");
			$db->query();
		}
		$mainframe->redirect("index.php?option=com_osproperty&task=form_completeimport&id=".JRequest::getVar('id'));
	}
	
	/**
	 * Import CSV Form
	 *
	 * @param unknown_type $option
	 */
	function importCsvForm($option,$id){
		global $mainframe;
		$db = JFactory::getDbo();
		$row = &JTable::getInstance('Csvform','OspropertyTable');
		$row->load((int)$id);
		
		$db->setQuery("DELETE FROM #__osrs_importlog_properties WHERE form_id = '$id'");
		$db->query();
		
		$db->setQuery("Select id as value, name as text from #__osrs_agents where published = '1' order by name");
		$agents = $db->loadObjectList();
		$agentArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_DEFAULT_AGENT'));
		$agentArr   = array_merge($agentArr,$agents);
		$lists['agent'] = JHTML::_('select.genericlist',$agentArr,'agent_id','class="input-small"','value','text');
		
		$photoArr[] = JHTML::_('select.option','0',JText::_('OS_PHOTO_FROM_DIFFERENT_HOST'));
		$photoArr[] = JHTML::_('select.option','1',JText::_('OS_PHOTO_FROM_YOUR_COMPUTER'));
		$lists['photo'] = JHTML::_('select.genericlist',$photoArr,'photofrom','class="inputbox"','value','text');
		
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option','0',JText::_('OS_NO'));
		$optionArr[] = JHTML::_('select.option','1',JText::_('OS_YES'));
		$lists['utf'] = JHtml::_('select.genericlist',$optionArr,'import_utf','class="input-small"','value','text');
		
		$lists['removeproperties'] = JHtml::_('select.genericlist',$optionArr,'removeproperties','class="input-small"','value','text');
		HTML_OspropertyCsvform::importCsvForm($option,$row,$lists);
	}
	
	/**
	 * Do import
	 *
	 * @param unknown_type $option
	 */
	function doImportCSV($option){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$isImport = array();
		$log1 = array();
		$log2 = array();
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		$id = JRequest::getInt('id',0);
		$form_id = $id;
		$row = &JTable::getInstance('Csvform','OspropertyTable');
		$row->load((int)$id);
		$max_file_size = $row->max_file_size;
		$max_file_size_in_bytes = $max_file_size*1024*1024;
		
		$csv_folder = JPATH_ROOT.DS."tmp".DS."csv";
		if(!JFolder::exists($csv_folder)){
			JFolder::create($csv_folder);
		}
		
		if(!HelperOspropertyCommon::checkIsCsvFileUploaded('csv_file')){
			//return to previous page
			?>
			<script language="javascript">
			window.history(-1);
			</script>
			<?php
		}else{
			if(is_uploaded_file($_FILES['csv_file']['tmp_name'])){
				$filesize = filesize($_FILES['csv_file']['tmp_name']);
				if($filesize > $max_file_size_in_bytes){
					$mainframe->redirect("index.php?option=com_osproperty&task=form_importcsv&cid[]=$id",JText::_('OS_YOUR_FILE_IS_LARGER_THAN_LIMIT'));
				}else{
					//remove all properties
					$removeproperties = JRequest::getInt('removeproperties',0);
					if($removeproperties == 1){
						$db->setQuery("Select id from #__osrs_properties");
						$cid = $db->loadColumn(0);
						
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
					}
					
					$filename = time().str_replace(" ","_",$_FILES['csv_file']['name']);
					move_uploaded_file($_FILES['csv_file']['tmp_name'],$csv_folder.DS.$filename);
					
					//do import data
					include_once(JPATH_ROOT.DS."components".DS."com_osproperty".DS."classes".DS."listing.php");
					include(JPATH_ROOT.DS."components".DS."com_osproperty".DS."helpers".DS."csv".DS."FileReader.php");
					include(JPATH_ROOT.DS."components".DS."com_osproperty".DS."helpers".DS."csv".DS."CSVReader.php");
					$reader =& new CSVReader( new FileReader($csv_folder.DS.$filename));
					$reader->setSeparator( $configClass['csv_seperator'] );
					$rs = 0;
					$j = 0;
					$import_utf = JRequest::getInt('import_utf',0);
					while( false != ( $cell = $reader->next() ) ){
						if($rs > 0){
							$isImport = OspropertyCsvform::importCell($option,$row,$cell,$import_utf);
							if($isImport[0]->isInsert == 0){
								$log1[] = $isImport[0]->error;
							}else{
								$log2[] = $isImport[0]->error;
							}
						}
						$rs++;
					}
				}
			}
		}
		//update into form
		$current_time = date("Y-m-d H:i:s",time());
		$db->setQuery("UPDATE #__osrs_csv_forms SET last_import = '$current_time' WHERE id = '$form_id'");
		$db->query();
		
		//insert into log table
		if(count($isImport) > 0){
			$log1 = implode("<BR>",$log1);
			$log2 = implode("<BR>",$log2);
		}
		$db->setQuery("DELETE FROM #__osrs_importlog WHERE form_id = '$form_id'");
		$db->query();
		$db->setQuery("INSERT INTO #__osrs_importlog (id,form_id,log1,log2) values (NULL,'$form_id',".$db->quote($log1).",".$db->quote($log2).")");
		$db->query();
		
		$db->setQuery("Select count(id) from #__osrs_importlog_properties where form_id = '$form_id'");
		$count = $db->loadResult();
		if($count > 0){
			$msg = JText::_('OS_IMPORTPHOTO');
			$mainframe->redirect("index.php?option=com_osproperty&task=form_importphotoform&id=$form_id",$msg);
		}else{
			$mainframe->redirect("index.php?option=com_osproperty&task=form_completeimport&id=$form_id");
		}
	}
	
	/**
	 * Import on Cell of CSV file
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 * @param unknown_type $cell
	 */
	function importCell($option,$row,$cell,$import_utf){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'ref'");
		$col_ref = $db->loadResult();
		$col_ref--;
		$property_ref = $cell[$col_ref];
		//check property name
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'pro_name'");
		$col_property_name = $db->loadResult();
		$col_property_name--;
		$property_name = $cell[$col_property_name];
		//$property_name = mb_convert_encoding($property_name, 'utf8', 'unicode');
		//$property_name = iconv('UTF-8', 'ASCII//TRANSLIT', $property_name);
		//echo $property_name;
		//die();
		
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'alias'");
		$col_alias = $db->loadResult();
		$col_alias--;
		$alias = $cell[$col_alias];
		//category
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'category_id'");
		$col_category = $db->loadResult();
		$col_category--;
		$category = trim($cell[$col_category]);
		//in case category == 0
		$catIds = array();
		if($category != ""){
			$categoryArr = explode(",",$category);
			foreach ($categoryArr as $category){
				$db->setQuery('Select id from #__osrs_categories where category_name like '.$db->quote($category));
				$catid = $db->loadResult();
				//echo $catid;
				if(intval($catid) == 0){//can't find the category
					if($row->fcategory == 0){
						$catInstance = & JTable::getInstance('Category','OspropertyTable');
						$catInstance->id = 0;
						if($import_utf == 1){
							$catInstance->category_name = utf8_encode($category);
						}else{
							$catInstance->category_name = $category;
						}
						$catInstance->parent_id = 0;
						$catInstance->access = 0;
						$db->setQuery("Select ordering from #__osrs_categories order by ordering");
						$ordering = $db->loadResult();
						$ordering++;
						$catInstance->ordering = $ordering;
						$catInstance->published = 1;
						$catInstance->store();
						$catid = $db->insertID();
						$catIds[] = $catid;
					}else{
						$catid = $row->category_id;
						$catIds[] = $catid;
					}
				}else{
					$catIds[] = $catid;
				}
			}
		}else{
			if($row->fcategory == 1){
				$catid = $row->category_id;
				$catIds[] = $catid;
			}
		}
		$category = array();
		$category = $catIds;
		//print_r($category);
		//die();
		//property type
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'pro_type'");
		$col_type = $db->loadResult();
		$col_type--;
		$property_type = trim($cell[$col_type]);
		//in case type == 0
		if($property_type != ""){
			$db->setQuery('Select id from #__osrs_types where type_name like '.$db->quote($property_type).'');
			$typeid = $db->loadResult();
			if(intval($typeid) == 0){//can't find the type
				if($row->ftype == 0){
					$typeInstance = & JTable::getInstance('Type','OspropertyTable');
					$typeInstance->id = 0;
					//$typeInstance->type_name = utf8_encode($property_type);
					if($import_utf == 1){
						$typeInstance->type_name = utf8_encode($property_type);
					}else{
						$typeInstance->type_name = $property_type;
					}
					$typeInstance->published = 1;
					$typeInstance->store();
					$typeid = $db->insertID();
				}else{
					$typeid = $row->type_id;
				}
			}
		}else{
			if($row->ftype == 1){
				$typeid = $row->type_id;
			}
		}
		$property_type = $typeid;
		//agent
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'agent_id'");
		$col_agent = $db->loadResult();
		$col_agent--;
		$agent = trim($cell[$col_agent]);
		//in case agent == 0
		if($agent != ""){
			$db->setQuery("Select a.id from #__osrs_agents as a inner join #__users as b on a.user_id = b.id where a.name like '$agent' or b.username like '$agent' or b.email like '%$agent%'");
			$agentid = $db->loadResult();
			if(intval($agentid) == 0){//can't find the type
				$agentid = $row->agent_id;
			}
		}else{
			$agentid = $row->agent_id;
		}
		$agent = $agentid;
		//country
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'country'");
		$col_country = $db->loadResult();
		$col_country--;
		$country = trim($cell[$col_country]);
		//in case country == 0
		if($country != ""){
			$db->setQuery("Select id from #__osrs_countries where `country_name` like ".$db->quote($country)." or country_code like ".$db->quote($country));
			$countryid = $db->loadResult();
			if(intval($countryid) == 0){//can't find the type
				$countryid = $row->country;
			}
		}else{
			$countryid = $row->country;
		}
		$country = $countryid;
		//state
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'state'");
		$col_state = $db->loadResult();
		$col_state--;
		$state = trim($cell[$col_state]);
		//in case state == 0
		if($state != ""){
			$db->setQuery("Select id from #__osrs_states where (state_name like ".$db->quote($state)." or state_code like '$state') and `country_id` = '$country'");
			$stateid = $db->loadResult();
			if(intval($stateid) == 0){//can't find the type
				if($row->fstate == 0){
					$stateInstance = & JTable::getInstance('State','OspropertyTable');
					$stateInstance->id = 0;
					$stateInstance->country_id = $country;
					$stateInstance->state_name = $state;
					$stateInstance->state_code = $state;
					$stateInstance->published = 1;
					$stateInstance->store();
					$stateid = $db->insertID();
				}else{
					$stateid = $row->state;
				}
			}
		}else{
			if($row->fstate == 1){
				$stateid = $row->state;
			}
		}
		$state = $stateid;
		//city
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'city'");
		$col_city = $db->loadResult();
		$col_city--;
		$city = trim($cell[$col_city]);
		//in case state == 0
		if($city != ""){
			$db->setQuery("Select id from #__osrs_cities where `state_id` = '$state' and `country_id` = '$country' and `city` like ".$db->quote($city));
			$cityid = $db->loadResult();
			if(intval($cityid) == 0){//can't find the type
				if($row->fcity == 0){
					$cityInstance = & JTable::getInstance('City','OspropertyTable');
					$cityInstance->id = 0;
					$cityInstance->country_id = $country;
					$cityInstance->state_id = $state;
					$cityInstance->city = $city;
					$cityInstance->published = 1;
					$cityInstance->store();
					$cityid = $db->insertID();
				}else{
					$cityid = $row->city;
				}
			}
		}else{
			if($row->fcity == 1){
				$cityid = $row->city;
			}
		}
		$city = $cityid;
		//price
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'price'");
		$col_price = $db->loadResult();
		$col_price--;
		$price = trim($cell[$col_price]);
		//small description
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'pro_small_desc'");
		$col_small_desc = $db->loadResult();
		$col_small_desc--;
		$small_desc = $cell[$col_small_desc];
		//full desc
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'pro_full_desc'");
		$col_full_desc = $db->loadResult();
		$col_full_desc--;
		$full_desc = $cell[$col_full_desc];
		//price_original
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'price_original'");
		$col_price_original = $db->loadResult();
		$col_price_original--;
		$price_original = trim($cell[$col_price_original]);
		//currency
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'curr'");
		$col_curr = $db->loadResult();
		$col_curr--;
		$curr = trim($cell[$col_curr]);
		if($curr != ""){
			$db->setQuery("Select id from #__osrs_currencies where currency_name like '$curr' or currency_code like '$curr' or currency_symbol like '$curr'");
			$curr = $db->loadResult();
		}else{
			$curr = $configClass['general_currency_default'];
		}
		//note
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'note'");
		$col_note = $db->loadResult();
		$col_note--;
		$note = $cell[$col_note];
		//price_call
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'price_call'");
		$col_price_call = $db->loadResult();
		$col_price_call--;
		$price_call = $cell[$col_price_call];
		if($price_call != ""){
			if(trim(strtolower($price_call)) == trim(strtolower($row->yes_value))){
				$price_call = 1;
			}elseif(trim(strtolower($price_call)) == trim(strtolower($row->no_value))){
				$price_call = 0;
			}
		}else{
			$price_call = 0;
		}
		
		//address
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'address'");
		$col_address = $db->loadResult();
		$col_address--;
		$address = $cell[$col_address];
		//region
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'region'");
		$col_region = $db->loadResult();
		$col_region--;
		$region = $cell[$col_region];
		//postcode
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'postcode'");
		$col_postcode = $db->loadResult();
		$col_postcode--;
		$postcode = $cell[$col_postcode];
		
		//price_call
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'show_address'");
		$col_show_address = $db->loadResult();
		$col_show_address--;
		$show_address = trim($cell[$col_show_address]);
		if($show_address != ""){
			if(trim(strtolower($show_address)) == trim(strtolower($row->yes_value))){
				$show_address = 1;
			}elseif(trim(strtolower($show_address)) == trim(strtolower($row->no_value))){
				$show_address = 0;
			}
		}else{
			$show_address = 0;
		}
		
		//bed_room
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'bed_room'");
		$col_bed_room = $db->loadResult();
		$col_bed_room--;
		$bed_room = $cell[$col_bed_room];
		//bath_room
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'bath_room'");
		$col_bath_room = $db->loadResult();
		$col_bath_room--;
		$bath_room = $cell[$col_bath_room];
		//rooms
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'rooms'");
		$col_rooms = $db->loadResult();
		$col_rooms--;
		$rooms = $cell[$col_rooms];
		//floors
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'number_of_floors'");
		$col_number_of_floors = $db->loadResult();
		$col_number_of_floors--;
		$col_number_of_floors = $cell[$col_number_of_floors];

		//square_feet
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'square_feet'");
		$col_square_feet = $db->loadResult();
		$col_square_feet--;
		$square_feet = $cell[$col_square_feet];
		//photo
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'photo'");
		$col_photo = $db->loadResult();
		$col_photo--;
		$photo = $cell[$col_photo];
		//convenience
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'convenience'");
		$col_convenience = $db->loadResult();
		$col_convenience--;
		$convenience = $cell[$col_convenience];
		
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'lat_add'");
		$col_lat = $db->loadResult();
		$col_lat--;
		$lat_add = $cell[$col_lat];
		
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'long_add'");
		$col_long = $db->loadResult();
		$col_long--;
		$long_add = $cell[$col_long];
		
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'rent_time'");
		$col_renttime = $db->loadResult();
		$col_renttime--;
		$rent_time = $cell[$col_renttime];
		
		
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'lot_size'");
		$col_lotsize = $db->loadResult();
		$col_lotsize--;
		$lot_size = $cell[$col_lotsize];
		
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'parking'");
		$col_parking = $db->loadResult();
		$col_parking--;
		$parking = $cell[$col_parking];
		
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'published'");
		$col_published = $db->loadResult();
		$col_published--;
		$published = $cell[$col_published];
		
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'energy'");
		$col_energy = $db->loadResult();
		$col_energy--;
		$energy = $cell[$col_energy];
		
		$db->setQuery("Select column_number from #__osrs_form_fields where form_id = '$row->id' and `field` like 'climate'");
		$col_climate = $db->loadResult();
		$col_climate--;
		$climate = $cell[$col_climate];
		
		//create the data for property first 
		$property = &JTable::getInstance('Property','OspropertyTable');
		$property->id = 0;
		$property->ref = $property_ref;
		if($import_utf == 1){
			$property->pro_name = utf8_encode($property_name);
			$property->pro_small_desc = utf8_encode($small_desc);
			$property->pro_full_desc = utf8_encode($full_desc);
			$property->address = utf8_encode($address);
		}else{
			$property->pro_name = $property_name;
			$property->pro_small_desc = $small_desc;
			$property->pro_full_desc = $full_desc;
			$property->address = $address;
		}
		$property->agent_id = $agent;
		$property->category_id = $category;
		$property->pro_type = $property_type;
		$property->price = $price;
		$property->price_original = $price_original;
		$property->rent_time = $rent_time;
		
		$property->city = $city;
		$property->show_address = $show_address;
		$property->price_call = $price_call;
		$property->region = $region;
		$property->province= $province;
		$property->postcode = $postcode;
		$property->created = date("Y-m-d",time());
		$property->approved = 1;
		$property->published = $published;
		$property->bed_room = $bed_room;
		$property->bath_room = $bath_room;
		$property->rooms = $rooms;
		$property->number_of_floors = $col_number_of_floors;
		$property->parking = $parking;
		$property->square_feet = $square_feet;
		$property->lot_size = $lot_size;
		$property->request_to_approval = 0;
		$property->request_featured = 0;
		$property->note = $note;
		$property->state = $state;
		$property->country = $country;
		$property->isFeatured = 0;
		$property->hits = 0;
		$property->access = 0;
		$property->curr = $curr;
		$property->lat_add = $lat_add;
		$property->long_add = $long_add;
		$property->energy = $energy;
		$property->climate = $climate;
		$admin = JFactory::getUser();
		$property->created_by = $admin->id;
		
		if(!$property->store()){
			
			$error = JText::_('OS_ERROR_IMPORT');
			$error .= " - ".$property_name." - ";
			$isInsert = 0;
		}else{
			//echo $db->getQuery();
			//die();
			$pid = $db->insertid();
			//die();
			//update alias
			$alias = OSPHelper::generateAlias('property',$pid,$alias);
			$db->setQuery("Update #__osrs_properties set pro_alias = '$alias' where id = '$pid'");
			$db->query();
			
			$error = "<b><font color=\"#665A0C\">".$property_name."</font> - ID: <font color=\"red\">".$pid."</font></B><BR>";
			$error .= "<b>".JText::_('OS_ADDRESS')."</b>: ".$property->address;
			$isInsert = 1;
			
			//category
			if(count($catIds) > 0){
				foreach ($catIds as $catid){
					$db->setQuery("Insert into #__osrs_property_categories (id,pid,category_id) values (NULL,'$pid','$catid')");
					$db->query();
				}
			}
			
			//photo
			$photofrom = JRequest::getVar('photofrom',0);
			OspropertyCsvform::importPhoto($photo,$pid,$photofrom);
			//convenience
			if($convenience != ""){
				OspropertyCsvform::importConvenience($convenience,$pid);
			}
			
			//extra fields
			$db->setQuery("Select * from #__osrs_extra_fields where published = '1'");
			$fields = $db->loadObjectList();
			if(count($fields) > 0){
				$array1 = array('text','textarea','date');
				$array2 = array('singleselect','radio');
				$array3 = array('multipleselect','checkbox');
				for($i=0;$i<count($fields);$i++){
					$field = $fields[$i];
					$field_id	= $field->id;
					//check field type
					$field_type = $field->field_type;
					
					$field_name = $field->field_name;
					$db->setQuery("SELECT column_number FROM #__osrs_form_fields WHERE form_id = '$row->id' AND `field` like '$field_name'");
					$column_number = $db->loadResult();
					$column_number = $column_number - 1;
					$field_value = $cell[$column_number];
					$field_value = htmlentities($field_value);
					if($field_value != ""){
						if(in_array($field_type,$array1)){
							$db->setQuery("INSERT INTO #__osrs_property_field_value (id,pro_id,field_id,`value`) VALUES (NULL,'$pid','$field_id','".utf8_encode($field_value)."')");
							$db->query();
						}elseif(in_array($field_type,$array2)){
							$db->setQuery("Select `id`  from #__osrs_extra_field_options where field_id = '$field_id' and field_option like '%$field_value%'");
							$option_id = $db->loadResult();
							//update into database
							if($option_id > 0){
								$db->setQuery("INSERT INTO #__osrs_property_field_opt_value (id,pid,fid,oid) values (NULL,'$pid','$field_id','$option_id')");
								$db->query();
							}
						}elseif(in_array($field_type,$array3)){
							$field_value_array = explode("|",$field_value);
							for($j=0;$j<count($field_value_array);$j++){
								$field_value = $field_value_array[$j];
								$db->setQuery("Select `id`  from #__osrs_extra_field_options where field_id = '$field_id' and field_option like '%$field_value%'");
								$option_id = $db->loadResult();
								//update into database
								if($option_id > 0){
									$db->setQuery("INSERT INTO #__osrs_property_field_opt_value (id,pid,fid,oid) values (NULL,'$pid','$field_id','$option_id')");
									$db->query();
								}
							}
						}
					}
				}
			}
			//add into expired table
			if($configClass['general_use_expiration_management']==1){
				HelperOspropertyCommon::setExpiredTime($pid,'n',1);
			}
		}
		
		//insert into import log property table
		$db->setQuery("INSERT INTO #__osrs_importlog_properties (id,form_id,pid) VALUES (NULL,'$row->id','$pid')");
		$db->query();
		
		$return = array();
		$return[0]->error = $error;
		$return[0]->isInsert = $isInsert;
		
		return $return;
	}
	
	/**
	 * Import convenience
	 *
	 * @param unknown_type $convenience
	 * @param unknown_type $pid
	 */
	function importConvenience($convenience,$pid){
		global $mainframe;
		$db = JFactory::getDbo();
		if($convenience != ""){
			$convenienceArr = explode("|",$convenience);
			if(count($convenienceArr) > 0){
				for($i=0;$i<count($convenienceArr);$i++){
					$con = $convenienceArr[$i];
					$con = trim($con);
					if($con != ""){
						$db->setQuery("SELECT COUNT(id) FROM #__osrs_amenities WHERE amenities LIKE '".$con."'");
						$count_convenience = $db->loadResult();
						if(intval($count_convenience) == 0){
							$db->setQuery("INSERT INTO #__osrs_amenities (id, amenities, published ) VALUES (NULL,'$con','1')");
							$db->query();
							$amen_id = $db->insertid();
						}else{
							$db->setQuery("SELECT id FROM #__osrs_amenities WHERE amenities LIKE '$con'");
							$amen_id = $db->loadResult();
						}
						//insert into #__osrs_property_amenities
						$db->setQuery("INSERT INTO #__osrs_property_amenities (id,pro_id,amen_id) VALUES (NULL,'$pid','$amen_id')");
						$db->query();
					}
				}
			}
		}
	}
	
	/**
	 * Import photo
	 *
	 * @param unknown_type $photo
	 */
	function importPhoto($photo,$pid,$from){
		global $mainframe;
		$db = JFactory::getDbo();
		if($photo != ""){
			$photoArr = explode("|",$photo);
			if(count($photoArr) > 0)		{
				for($i=0;$i<count($photoArr);$i++){
					$photo = trim($photoArr[$i]);
					//insert into photos table
					$db->setQuery("INSERT INTO #__osrs_photos (id,pro_id,image,ordering) VALUES (NULL,'$pid','$photo','$i')");
					$db->query();
				}
			}
		}
	}
	
	/**
	 * Save photo
	 *
	 * @param unknown_type $photolink
	 */
	function savePhoto($photolink){
		global $configClass;
		if(OspropertyCsvform::_iscurlinstalled()){
			$ch = curl_init();
		    curl_setopt ($ch, CURLOPT_URL, $photolink);
		    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 0);
		    $fileContents = curl_exec($ch);
		    curl_close($ch);
		}else{
			$fileContents = file_get_contents($photolink);
		}
	    $newImg = imagecreatefromstring($fileContents);
	    $photolinkArr = explode("/",$photolink);
	    $photoname = $photolinkArr[count($photolinkArr)-1];
	    @imagejpeg($newImg,JPATH_ROOT.DS."components".DS."com_osproperty".DS."images".DS."properties".DS.$photoname);
	    //resize image
	    $original_image_link = JPATH_ROOT.DS."components".DS."com_osproperty".DS."images".DS."properties".DS.$photoname;
		//copy and resize
		//thumb
		$thumb_width = $configClass['images_thumbnail_width'];
		$thumb_height = $configClass['images_thumbnail_height'];
		
		$thumb_image_link = JPATH_ROOT.DS."components".DS."com_osproperty".DS."images".DS."properties".DS."thumb".DS.$photoname;
		@copy($original_image_link,$thumb_image_link);
		
		$thumb_image = getimagesize($thumb_image_link);
		$original_thumb_height = $thumb_image[1];
		$original_thumb_width = $thumb_image[0];
		
		if(($original_thumb_width > $thumb_width) and ($original_thumb_height > $thumb_height)){
			$resize_width = $thumb_width;
			$resize_height = $thumb_height;
		}else if(($original_thumb_width > $thumb_width) and ($original_thumb_height < $thumb_height)){
			$resize_width = $thumb_width;
			$resize_height = $original_thumb_height;
		}else if(($original_thumb_width < $thumb_width) and ($original_thumb_height > $thumb_height)){
			$resize_width = $original_thumb_width;
			$resize_height = $thumb_height;
		}else{
			$resize_width = $original_thumb_width;
			$resize_height = $original_thumb_height;
		}
		
		$image = new SimpleImage();
	    $image->load($thumb_image_link);
	    $image->resize($resize_width,$resize_height);
	    $image->save($thumb_image_link);
	    
	    //medium
	    $medium_width = $configClass['images_large_width'];
	    $medium_height = $configClass['images_large_height'];
	    
	    $medium_image_link = JPATH_ROOT.DS."components".DS."com_osproperty".DS."images".DS."properties".DS."medium".DS.$photoname;
	    @copy($original_image_link,$medium_image_link);
	    $medium_image = getimagesize($medium_image_link);
	    $original_medium_width = $medium_image[0];
	    $original_medium_height = $medium_image[1];
	    
	    if(($original_medium_width > $medium_width) and ($original_medium_height > $medium_height)){
			$resize_width = $medium_width;
			$resize_height = $medium_height;
		}else if(($original_medium_width > $medium_width) and ($original_medium_height < $medium_height)){
			$resize_width = $medium_width;
			$resize_height = $original_medium_height;
		}else if(($original_medium_width < $medium_width) and ($original_medium_height > $medium_height)){
			$resize_width = $original_medium_width;
			$resize_height = $medium_height;
		}else{
			$resize_width = $original_medium_width;
			$resize_height = $original_medium_height;
		}
	    
		$image = new SimpleImage();
	    $image->load($medium_image_link);
	    $image->resize($resize_width,$resize_height);
	    $image->save($medium_image_link);

	    
	    return $photoname;
	}
	
	/**
	 * Check curl existing
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
	 * Download CSV
	 *
	 * @param unknown_type $option
	 */
	function downloadCsv($option,$id){
		global $mainframe;
		jimport('joomla.filesystem.file');
		$db = JFactory::getDbo();
		$csv_absoluted_link = JPATH_ROOT.DS."images".DS."osproperty".DS."csv".$id.".csv";
		
		if(JFile::exists($csv_absoluted_link)){
			HelperOspropertyCommon::downloadfile2($csv_absoluted_link,$id);
		}
	}
	
	/**
	 * Default CSV form lists
	 *
	 * @param unknown_type $option
	 */
	function defaultList($option){
		global $mainframe;
		$db = JFactory::getDbo();
		$limit = JRequest::getVar('limit',20);
		$limitstart = JRequest::getVar('limitstart',0);
		
		$query = "Select count(id) from #__osrs_csv_forms";
		$db->setQuery($query);
		$count = $db->loadResult();
		
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($count,$limitstart,$limit);
		
		$query = "Select * from #__osrs_csv_forms";
		$db->setQuery($query,$pageNav->limitstart,$pageNav->limit);
		$rows = $db->loadObjectList();
		HTML_OspropertyCsvform::defaultList($option,$rows,$pageNav);
	}
	
	/**
	 * Edit form
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function editForm($option,$id){
		global $mainframe;
		$db = JFactory::getDbo();
		$row = &JTable::getInstance('Csvform','OspropertyTable');
		if($id > 0){
			$row->load((int)$id);
		}else{
			$row->published = 1;
		}
		// creat published
		//$lists['published'] = JHTML::_('select.booleanlist', 'published', '', $row->published);
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option','1',JText::_('OS_PUBLISH'));
		$optionArr[] = JHTML::_('select.option','0',JText::_('OS_UNPUBLISH'));
		$lists['published'] = JHTML::_('select.genericlist',$optionArr ,'published', 'class="input-small"','value','text', $row->published);
		
		$fields = array('ref','pro_name','pro_alias','agent_id','pro_type','category_id','country','state','city','region','price','rent_time','curr','price_call','show_address','pro_small_desc','pro_full_desc','note','address','postcode','lat_add','long_add','bed_room','bath_room','rooms','square_feet','number_of_floors','photo','convenience','parking','lot_size','published','energy','climate');
		
		$labels = array();
		$labels[] = JText::_('Ref #');
		$labels[] = JText::_('OS_PROPERTY_NAME');
		$labels[] = JText::_('OS_ALIAS');
		$labels[] = JText::_('OS_AGENT');
		$labels[] = JText::_('OS_PROPERTY_TYPE');
		$labels[] = JText::_('OS_CATEGORY');
		$labels[] = JText::_('OS_COUNTRY');
		$labels[] = JText::_('OS_STATE');
		$labels[] = JText::_('OS_CITY');
		$labels[] = JText::_('OS_REGION');
		$labels[] = JText::_('OS_MARKET_PRICE');
		$labels[] = JText::_('OS_RENT_TIME_FRAME');
		$labels[] = JText::_('OS_CURRENCY');
		$labels[] = JText::_('OS_CALL_FOR_PRICE');
		$labels[] = JText::_('OS_SHOW_ADDRESS');
		$labels[] = JText::_('OS_SMALL_DESCRIPTION');
		$labels[] = JText::_('OS_FULL_DESCRIPTION');
		$labels[] = JText::_('OS_AGENT_NOTE');
		$labels[] = JText::_('OS_ADDRESS');
		$labels[] = JText::_('OS_POSTCODE');
		$labels[] = JText::_('OS_LATITUDE');
		$labels[] = JText::_('OS_LONGTITUDE');
		$labels[] = JText::_('OS_NUMBER_BEDROOMS');
		$labels[] = JText::_('OS_NUMBER_BATHROOMS');
		$labels[] = JText::_('OS_NUMBER_ROOMS');
		$labels[] = JText::_('OS_SQUARE_FEET');
		$labels[] = JText::_('OS_NUMBER_OF_FLOORS');
		$labels[] = JText::_('OS_PHOTOS');
		$labels[] = JText::_('OS_CONVENIENCE');
		$labels[] = JText::_('OS_PARKING');
		$labels[] = JText::_('OS_LOT_SIZE');
		$labels[] = JText::_('OS_PUBLISHED');
		$labels[] = JText::_('OS_ENERGY');
		$labels[] = JText::_('OS_CLIMATE');
		
		$db->setQuery("Select a.field_name, a.field_label from #__osrs_extra_fields as a inner join #__osrs_fieldgroups as b on b.id = a.group_id where a.published = '1' and b.published = 1 order by a.group_id, a.ordering");
		$rows = $db->loadObjectList();
		if(count($rows) > 0){
			for($i=0;$i<count($rows);$i++){
				$rs = $rows[$i];
				$fields[] = $rs->field_name;
				$labels[] = $rs->field_label;
			}
		}
		$requirefieldArr = array();
		$requirelabelArr = array();
		$temp = array();
		
		$required_fields = array(1,2,3,4,5,6,7);
		
		for($i=0;$i<count($required_fields);$i++){
			$requirefieldArr[] = $fields[$required_fields[$i]];
			$requirelabelArr[] = $labels[$required_fields[$i]];
		}
		
		$lists['requirefields'] = $requirefieldArr;
		$lists['requirelabels'] = $requirelabelArr;
		$lists['requireid']     = $required_fields;
		$lists['fields'] 		= $fields;
		$lists['labels']		= $labels;
		
		$typeArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_PROPERTY_TYPE'));
		$db->setQuery("Select id as value,type_name as text from #__osrs_types where published = '1' order by type_name");
		$protypes = $db->loadObjectList();
		$typeArr   = array_merge($typeArr,$protypes);
		$lists['type'] = JHTML::_('select.genericlist',$typeArr,'type_id','class="input-large"','value','text',$row->type_id);
		//categories
		$lists['category'] = OspropertyProperties::listCategories($row->category_id,'');
		//agent
		$agentArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_AGENT'));
		$query  = "Select a.id as value,a.name as text from #__osrs_agents as a inner join #__users as b on b.id = a.user_id where published = '1' ";
		$query .= " order by a.name";
		$db->setQuery($query);
		$agents = $db->loadObjectList();
		$agentArr   = array_merge($agentArr,$agents);
		$lists['agent'] = JHTML::_('select.genericlist',$agentArr,'agent_id','class="input-large"','value','text',$row->agent_id);
		
		$lists['country'] = HelperOspropertyCommon::makeCountryList($row->country,'country','','','');
		HTML_OspropertyCsvform::editHTML($option,$row,$lists);
	}
	
	/**
	 * Get state input
	 *
	 * @param unknown_type $state
	 * @return unknown
	 */
	function getStateInput($state){
		// Initialize variables.
		$html = array();
		//$groups = $this->getGroups();
		//$excluded = $this->getExcluded();
		$link = 'index.php?option=com_osproperty&amp;task=state_list&amp;tmpl=component&amp;modal=1';

		// Initialize some field attributes.
		$attr = ' class="input-small"';

		// Initialize JavaScript field attributes.
		//$onchange = (string) $this->element['onchange'];

		// Load the modal behavior script.
		JHtml::_('behavior.modal');

		// Build the script.
		$script = array();
		$script[] = '	function jSelectState_state(id, title) {';
		$script[] = '		var old_id = document.getElementById("state").value;';
		$script[] = '		if (old_id != id) {';
		$script[] = '			document.getElementById("state").value = id;';
		$script[] = '			document.getElementById("state_name").value = title;';
		$script[] = '			' . $onchange;
		$script[] = '		}';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Load the current username if available.
		$table = JTable::getInstance('State','OspropertyTable');
		
		if ($state)
		{
			$table->load($state);
		}
		else
		{
			$table->username = JText::_('OS_SELECT_STATE');
		}

		// Create a dummy text field with the user name.
		$html[] = '<span class="input-append">';
		$html[] = '<input type="text" class="input-small" id="state_name" value="'.htmlspecialchars($table->state_name, ENT_COMPAT, 'UTF-8') .'" disabled="disabled" size="35" /><a class="modal btn" title="'.JText::_('OS_CHANGE_STATE').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="icon-file"></i> '.JText::_('OS_CHANGE_STATE').'</a>';
		$html[] = '</span>';

		// Create the real field, hidden, that stored the user id.
		$html[] = '<input type="hidden" id="state" name="state" value="'.$state.'" />';

		return implode("\n", $html);
	}
	
	/**
	 * Get state input
	 *
	 * @param unknown_type $city
	 * @return unknown
	 */
	function getCityInput($city){
		// Initialize variables.
		$html = array();
		//$groups = $this->getGroups();
		//$excluded = $this->getExcluded();
		$link = 'index.php?option=com_osproperty&amp;task=city_list&amp;tmpl=component&amp;modal=1';

		// Initialize some field attributes.
		$attr = ' class="input-small"';

		// Initialize JavaScript field attributes.
		//$onchange = (string) $this->element['onchange'];

		// Load the modal behavior script.
		JHtml::_('behavior.modal');

		// Build the script.
		$script = array();
		$script[] = '	function jSelectCity_city(id, title) {';
		$script[] = '		var old_id = document.getElementById("city").value;';
		$script[] = '		if (old_id != id) {';
		$script[] = '			document.getElementById("city").value = id;';
		$script[] = '			document.getElementById("city_name").value = title;';
		$script[] = '			' . $onchange;
		$script[] = '		}';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Load the current username if available.
		$table = JTable::getInstance('City','OspropertyTable');
		
		if ($city)
		{
			$table->load($city);
		}
		else
		{
			$table->username = JText::_('OS_SELECT_CITY');
		}

		// Create a dummy text field with the user name.
		$html[] = '<span class="input-append">';
		$html[] = '<input type="text" class="input-small" id="city_name" value="'.htmlspecialchars($table->city, ENT_COMPAT, 'UTF-8') .'" disabled="disabled" size="35" /><a class="modal btn" title="'.JText::_('OS_CHANGE_CITY').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="icon-file"></i> '.JText::_('OS_CHANGE_CITY').'</a>';
		$html[] = '</span>';

		// Create the real field, hidden, that stored the user id.
		$html[] = '<input type="hidden" id="city" name="city" value="'.$city.'" />';

		return implode("\n", $html);
	}
	
	/**
	 * Save form
	 *
	 * @param unknown_type $option
	 * @param unknown_type $save
	 */
	function save($option,$save){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		jimport('joomla.filesystem.file');
		$fields = array('ref','pro_name','pro_alias','agent_id','pro_type','category_id','country','state','city','region','price','rent_time','curr','price_call','show_address','pro_small_desc','pro_full_desc','note','address','postcode','lat_add','long_add','bed_room','bath_room','rooms','square_feet','number_of_floors','photo','convenience','parking','lot_size','published','energy','climate');
		$labels = array();
		$labels[] = JText::_('Ref #');
		$labels[] = JText::_('OS_PROPERTY_NAME');
		$labels[] = JText::_('OS_ALIAS');
		$labels[] = JText::_('OS_AGENT');
		$labels[] = JText::_('OS_PROPERTY_TYPE');
		$labels[] = JText::_('OS_CATEGORY');
		$labels[] = JText::_('OS_COUNTRY');
		$labels[] = JText::_('OS_STATE');
		$labels[] = JText::_('OS_CITY');
		$labels[] = JText::_('OS_REGION');
		$labels[] = JText::_('OS_MARKET_PRICE');
		$labels[] = JText::_('OS_RENT_TIME_FRAME');
		$labels[] = JText::_('OS_CURRENCY');
		$labels[] = JText::_('OS_CALL_FOR_PRICE');
		$labels[] = JText::_('OS_SHOW_ADDRESS');
		$labels[] = JText::_('OS_SMALL_DESCRIPTION');
		$labels[] = JText::_('OS_FULL_DESCRIPTION');
		$labels[] = JText::_('OS_AGENT_NOTE');
		$labels[] = JText::_('OS_ADDRESS');
		$labels[] = JText::_('OS_POSTCODE');
		$labels[] = JText::_('OS_LATITUDE');
		$labels[] = JText::_('OS_LONGTITUDE');
		$labels[] = JText::_('OS_NUMBER_BEDROOMS');
		$labels[] = JText::_('OS_NUMBER_BATHROOMS');
		$labels[] = JText::_('OS_NUMBER_ROOMS');
		$labels[] = JText::_('OS_SQUARE_FEET');
		$labels[] = JText::_('OS_NUMBER_OF_FLOORS');
		$labels[] = JText::_('OS_PHOTOS');
		$labels[] = JText::_('OS_CONVENIENCE');
		$labels[] = JText::_('OS_PARKING');
		$labels[] = JText::_('OS_LOT_SIZE');
		$labels[] = JText::_('OS_PUBLISHED');
		$labels[] = JText::_('OS_ENERGY');
		$labels[] = JText::_('OS_CLIMATE');
		
		$row = &JTable::getInstance('Csvform','OspropertyTable');
		$id = JRequest::getVar('id',0);
		
		$post = JRequest::get('post');
		$row->bind($post);
		if($id == 0){
			$row->created_on = date("Y-m-d H:i:s",time());
		}
		$row->store();
		if($id == 0){
			$id = $db->insertID();
		}
		$db->setQuery("Delete from #__osrs_form_fields where form_id = '$id'");
		$db->query();
		
		$csv_content = '';
		for($i=1;$i<=50;$i++){
			$fieldname = "fields".$i;
			$fieldvalue = JRequest::getVar($fieldname,'');
			$headername = "header".$i;
			$headervalue = JRequest::getVar($headername,'');
			if($headervalue != ""){
				$field_type = "header";
				$csv_content .= '"'.$headervalue.'"'.$configClass['csv_seperator'];
			}elseif($fieldvalue != ""){
				if(in_array($fieldvalue,$fields)){
					$field_type = "property";
					$key = array_search($fieldvalue,$fields);
					$csv_content .= '"'.$labels[$key].'"'.$configClass['csv_seperator'];
				}else{
					$field_type = "extra";
					$db->setQuery("Select field_label from #__osrs_extra_fields where field_name like '$fieldvalue'");
					$field_label = $db->loadResult();
					$csv_content .= '"'.$field_label.'"'.$configClass['csv_seperator'];
				}
			}
			if(($fieldvalue != "") or ($headervalue != "")){
				$db->setQuery("INSERT INTO #__osrs_form_fields (id,form_id,column_number,`field`,header_text,field_type) VALUES (NULL,'$id','$i','$fieldvalue','$headervalue','$field_type')");
				$db->query();
			}
		}
		if($csv_content != ""){
			$csv_content = substr($csv_content,0,strlen($csv_content)-1);
		}
		$csv_content .= '';
		//echo $csv_content;
		//create the csv file
		$csv_absoluted_link = JPATH_ROOT.DS."images".DS."osproperty".DS."csv".$id.".csv";
		//create the content of csv
		$csvf = fopen($csv_absoluted_link,'w');
		@fwrite($csvf,$csv_content);
		@fclose($csvf);
		if($save == 1){
			$mainframe->redirect("index.php?option=com_osproperty&task=form_default",JText::_('OS_ITEM_SAVED'));
		}else{
			$mainframe->redirect("index.php?option=com_osproperty&task=form_default&task=form_edit&cid[]=$id",JText::_('OS_ITEM_SAVED'));
		}
	}
	
	/**
	 * Remove forms
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function removeForms($option,$cid){
		global $mainframe;
		jimport('joomla.filesystem.file');
		$db = JFactory::getDbo();
		$cids = implode(",",$cid);
		$db->setQuery("DELETE FROM #__osrs_csv_forms WHERE id IN ($cids)");
		$db->query();
		$db->setQuery("DELETE FROM #__osrs_form_fields WHERE form_id IN ($cids)");
		$db->query();
		
		for($i=0;$i<count($cid);$i++){
			$csv_absoluted_link = JPATH_ROOT.DS."images".DS."osproperty".DS."csv".$cid[$i].".csv";
			JFile::delete($csv_absoluted_link);
		}
		$mainframe->redirect("index.php?option=com_osproperty&task=form_default",JText::_('OS_ITEM_HAS_BEEN_DELETED'));
	}
	
	
	/**
	 * Change state
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 * @param unknown_type $state
	 */
	function form_change_publish($option,$cid,$state){
		global $mainframe;
		$db = JFactory::getDBO();
		if(count($cid)>0)	{
			$cids = implode(",",$cid);
			$db->setQuery("UPDATE #__osrs_csv_forms SET `published` = '$state' WHERE id IN ($cids)");
			$db->query();
			}
		$msg = JText::_("OS_ITEM_STATUS_HAS_BEEN_CHANGED");
		$mainframe->redirect("index.php?option=$option&task=form_default",$msg);
	}
	
	
	public static function checkCsvValue($value){
		return str_replace("#","",$value);
	}
}

?>
