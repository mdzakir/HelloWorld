<?php
/*------------------------------------------------------------------------
# xml.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class OspropertyXml
{
    /**
     * Default form
     *
     * @param unknown_type $option
     * @param unknown_type $task
     */
    public static function display($option, $task)
    {
        global $mainframe;
        $cid = JRequest::getVar('cid', array(0));
        $id = JRequest::getVar('id', 0);
        JArrayHelper::toInteger($cid, array(0));
        switch ($task) {
            case "xml_default":
                self::xmlExportForm($option);
                break;
            case "xml_export":
                self::xmlExport();
                break;
            case "xml_import":
                self::xmlImport();
                break;
            case "xml_doimportxml":
                self::doimportxml();
                break;
            case "xml_defaultimport":
                self::xmlImportForm($option);
                break;
            case "xml_importdelay":
                self::importDelay();
                break;
        }
    }

    /**
     * Import Delay
     */
    public static function importDelay(){
        global $mainframe;
        $id = Jrequest::getInt('id');
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
        <meta http-equiv="refresh" content="0;URL=<?php echo JURI::base()."index.php?option=com_osproperty&task=xml_doimportxml&id=".$id;?>" />
        <?php
    }

    /**
     * @param $option
     */
    public static function xmlImportForm($option){
        global $mainframe ;
        $optionArr = array();
        $optionArr[] = JHtml::_('select.option',1,JText::_('OS_YES'));
        $optionArr[] = JHtml::_('select.option',0,JText::_('OS_NO'));
        $lists['optionArr'] = $optionArr;
        HTML_OspropertyXml::xmlImportForm($option, $lists);
    }

    public static function xmlImport(){
        global $mainframe;
        $db = JFactory::getDbo();
        if(is_uploaded_file($_FILES['xml_file']['tmp_name'])){
            $filename = "upload_".$_FILES['xml_file']['name'];
            move_uploaded_file($_FILES['xml_file']['tmp_name'],JPATH_ROOT.'/tmp/'.$filename);
            $xml = simplexml_load_file(JPATH_ROOT.'/tmp/'.$filename);
            $array = json_decode(json_encode($xml));
			$publish_properties = JRequest::getInt('publish_properties',0);
            $listing = $array->listings->listing;
			//echo count($listing);
			//echo count($listings);
            //$listing  = $listings['listing'];
			//$temp = json_encode($listing);
			//print_r(json_decode($temp));
			//print_r($listing);
			//die();
            if(count($listing) > 0){
				$db->setQuery("Insert into #__osrs_xml (id,publish_properties,filename) values (NULL,'$publish_properties','$filename')");
				$db->query();
				$xml_id = $db->insertid();
				if(count($listing) == 1){
					//foreach($listing as $obj){
					$obj_content = json_encode($listing);
					$row = &JTable::getInstance('Xml','OspropertyTable');
					$row->id = 0;
					$row->xml_id = $xml_id;
					$row->obj_content = $obj_content;
					$row->imported =  0;
					$row->store();
				}else{
					$array = json_decode(json_encode($xml),true);
					$listing = $array['listings']['listing'];
					for ($i=0;$i<count($listing);$i++){
						$obj = $listing[$i];
						$obj_content = json_encode($obj);
						$row = &JTable::getInstance('Xml','OspropertyTable');
						$row->id = 0;
						$row->xml_id = $xml_id;
						$row->obj_content = $obj_content;
						$row->imported =  0;
						$row->store();
					}
				}
            }
            $mainframe->redirect("index.php?option=com_osproperty&task=xml_doimportxml&id=$xml_id");
        }
    }

	public function getDefaultLangCode(){
		$default_language = OSPHelper::getDefaultLanguage();
		$default_language  = explode("-",$default_language);
		return $default_language[0];
	}
    /**
     * Process import picture
     */
    public static function doimportxml(){
        global $mainframe,$languages;
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
        $id = JRequest::getInt('id',0);
        $db = JFactory::getDbo();
        $db->setQuery("Select publish_properties from #__osrs_xml where id = '$id'");
        $publish_properties = $db->loadResult();
        $db = JFactory::getDbo();
        $db->setQuery("Select * from #__osrs_xml_details where xml_id = '$id' and imported = '0' limit 5");
        $rows = $db->loadObjectList();
        if(count($rows) > 0){
			$languages = OSPHelper::getAllLanguages();
			$default_language = OSPHelper::getDefaultLanguage();
			$prefixarray = array();
			foreach($languages as $language){
				$lang_code = explode("-",$language->lang_code);
				$lang_code = $lang_code[0];
				if($default_language == $language->lang_code){
					$prefixarray[] = "";
				}else{
					$prefixarray[] = "_".$lang_code;
				}
				$prefixlabel[] = $lang_code;
			}
			//not multiple language
			if(! $translatable){
				$prefixlabel = array();
				$prefixarray = array();
				$prefixarray[] = "";
				$default_language  = explode("-",$default_language);
				$prefixlabel[] = $default_language[0];
			}
            foreach($rows as $row){
                $row->obj_content = str_replace("@","AAA",$row->obj_content);
                $listing =  json_decode($row->obj_content, TRUE) ;//json_decode($row->obj_content);
				//print_r($listing);
                //import each property
                self::doimportxmlproperty($row->id,$listing,$publish_properties,$prefixlabel,$prefixarray);
            }
            $mainframe->redirect("index.php?option=com_osproperty&task=xml_importdelay&id=".$id);
        }else{
            $db->setQuery("Update #__osrs_xml set imported = '1' where id = '$id'");
            $db->query();
            $mainframe->redirect("index.php?option=com_osproperty&task=xml_defaultimport",JText::_('OS_IMPORT_COMPLETED'));
        }
    }

    /**
     * @param $objid
     * @param $property
     * @param $prefixlabel
     * @param $prefixarray
     */
    public static function doimportxmlproperty($objid,$property,$publish_properties,$prefixlabel,$prefixarray){
        global $mainframe,$configClass;
        $db = JFactory::getDbo();
        $row = &JTable::getInstance('Property','OspropertyTable');
        //find ID of property
        $pid = $property['AAAattributes']['id'];
        //ref
        $ref = $property['ref'];
        $row->ref = $ref;

        $db->setQuery("Select count(id) from #__osrs_properties where id = '$pid'");
        $count = $db->loadResult();
		
        if($count == 0){
            $db->setQuery("Select count(id) from #__osrs_properties where ref like '$ref'");
            $count = $db->loadResult();
            if($count == 0){
                //new
                $isNew = 1;
                $row->id = 0;
            }else{
                $isNew = 0;
                $db->setQuery("Select id from #__osrs_properties where ref like '$ref'");
                $pid = $db->loadResult();
				$row->id = $pid;
            }
        }else{
            //update
            $isNew = 0;
            $row->id = $pid;
        }

		//title
		$title = $property['title'];
		for($i=0;$i<count($prefixlabel);$i++){
			$row->{'pro_name'.$prefixarray[$i]} = $title[$prefixlabel[$i]];
		}
		
		//property type
		$property_type = $property['type'];
		$type_id = $property_type['AAAattributes']['id'];
		if($type_id > 0){
			$db->setQuery("Select count(id) from #__osrs_types where id = '$type_id'");
			$count = $db->loadResult();
			if($count > 0){
				$row->pro_type = $type_id;
				$new_type = 0;
			}else{
				$new_type = 1;
			}
		}else{
			$new_type = 1;
		}
		
		if($new_type == 1){
			$row->pro_type = self::saveNewType($property_type,$prefixlabel,$prefixarray);
		}
		
		$access = $property['access'];
        $row->access = $access;
		$price_call = $property['price_call'];
        $row->price_call = $price_call;
		$price = $property['price'];
        $row->price = $price;
		$curr = $property['curr'];
        $row->curr = self::saveCurr($curr);
		$price_for = $property['price_for']['AAAattributes']['id'];
        $row->rent_time = $price_for;
		$featured = $property['featured'];
        $row->isFeatured = $featured;
		$sold = $property['sold'];
        $row->isSold = $sold;
		$soldOn = $property['soldOn'];
        $row->soldOn = $soldOn;
		$address = $property['address'];
        $row->address = $address;

		$country = $property['country'];
		$country_id = $country['AAAattributes']['id'];
        $country_name = $country['value'];
		if($country_id > 0){
			$db->setQuery("Select count(id) from #__osrs_countries where id = '$country_id'");
			$count = $db->loadResult();
			if($count > 0){
				$row->country = $country_id;
				$new_country = 0;
			}else{
				$new_country = 1;
			}
		}else{
			$new_country = 1;
		}
        if($new_country == 1){
            $row->country = self::saveCountry($country_name);
        }
        $state = $property['state'];
        $state_id = $state['AAAattributes']['id'];
        $state_name = $state['value'];
        if($state_id > 0){
            $db->setQuery("Select count(id) from #__osrs_states where id = '$state_id'");
            $count = $db->loadResult();
            if($count > 0){
                $row->state = $state_id;
                $new_state = 0;
            }else{
                $new_state = 1;
            }
        }else{
            $new_state = 1;
        }
        if($new_state == 1){
            $row->state = self::saveState($state_name,$country_id);
        }
        $city = $property['city'];
        $city_id = $city['AAAattributes']['id'];
        $city_name = $city['value'];
        if($city_id > 0){
            $db->setQuery("Select count(id) from #__osrs_cities where id = '$city_id'");
            $count = $db->loadResult();
            if($count > 0){
                $row->city = $city_id;
                $new_city = 0;
            }else{
                $new_city = 1;
            }
        }else{
            $new_city = 1;
        }
        if($new_city == 1){
            $row->city = self::saveCity($city_name,$row->state,$country_id);
        }
		$show_address = $property['show_address'];
		$row->show_address = $show_address;
		$lat_address = $property['lat_address'];
		$row->lat_add = $lat_address;
		$long_address = $property['long_address'];
		$row->long_add = $long_address;
		$bath = $property['bath'];
		$row->bath_room = $bath;
		$bed = $property['bed'];
		$row->bed_room = $bed;
		$floor = $property['floor'];
		$row->number_of_floors = $floor;
		$rooms = $property['rooms'];
		$row->rooms = $rooms;
		$parking = $property['parking'];
		$row->parking = $parking;
		$bulding_size = $property['bulding_size'];
		$row->square_feet = $bulding_size;
		$lot_size = $property['lot_size'];
		$row->lot_size = $lot_size;
		$unit = $property['unit'];
		$energy = $property['energy'];
		$row->energy = $energy;
		$co2 = $property['co2'];
		$row->climate = $co2;
		$document = $property['document'];
		$row->pro_pdf = $document;
		$document_url = $property['document_url'];
		$row->pro_pdf_file = $document_url;
		$small_desc = $property['small_desc'];
		for($i=0;$i<count($prefixlabel);$i++){
			$row->{'pro_small_desc'.$prefixarray[$i]} = $small_desc[$prefixlabel[$i]];
		}
		$full_desc = $property['full_desc'];
		for($i=0;$i<count($prefixlabel);$i++){
			$row->{'pro_full_desc'.$prefixarray[$i]} = $full_desc[$prefixlabel[$i]];
		}
        $meta = $property['meta'];
        for($i=0;$i<count($prefixlabel);$i++){
            $row->{'metadesc'.$prefixarray[$i]} = $meta[$prefixlabel[$i]];
        }
        if($publish_properties == 1){
            $row->published = 1;
            $row->approved = 1;
        }else{
            $row->published = 0;
            $row->approved = 0;
        }
        if($row->store()) {
            if($isNew == 1) {
                $pid = $db->insertID();
            }
            $db->setQuery("Update #__osrs_xml_details set imported = '1' where id = '$objid'");
            $db->query();

            $db->setQuery("Update #__osrs_xml set imported = imported + 1 where id = '$objid'");
            $db->query();

            if($publish_properties == 1) {
                OspropertyProperties::setexpired('com_osproperty', $pid);
            }

            //update sef
            $pro_alias = OSPHelper::generateAlias('property', $pid, '');
            $db->setQuery("Update #__osrs_properties set pro_alias  = " . $db->quote($pro_alias) . " where id = '$pid'");
            $db->query();

            //update categories
            $db->setQuery("Delete from #__osrs_property_categories where pid = '$pid'");
            $db->query();
            if(!is_array($property['categories']['category'][0])){
                $category = $property['categories']['category'];
                $cat_id = $category['AAAattributes']['id'];
                $new_cat = 0;
                if (intval($cat_id) == 0) {
                    $new_cat = 1;
                } else {
                    $db->setQuery("Select count(id) from #__osrs_categories where id = '$cat_id'");
                    $count = $db->loadResult();
                    if ($count == 0) {
                        $new_cat = 1;
                    }
                }
                if ($new_cat == 1) {
                    $cat_id = self::saveCategory($category, $prefixlabel, $prefixarray);
                }
                $db->setQuery("Select count(id) from #__osrs_property_categories where pid = '$pid' and category_id = '$cat_id'");
                $count = $db->loadResult();
                if ($count == 0) {
                    $db->setQuery("Insert into #__osrs_property_categories (id,pid,category_id) values (NULL,'$pid','$cat_id')");
                    $db->query();
                }
            }else {
                $categories = $property['categories']['category'];
                if (count($categories) > 0) {
                    foreach ($categories as $category) {
                        $cat_id = $category['AAAattributes']['id'];
                        $new_cat = 0;
                        if (intval($cat_id) == 0) {
                            $new_cat = 1;
                        } else {
                            $db->setQuery("Select count(id) from #__osrs_categories where id = '$cat_id'");
                            $count = $db->loadResult();
                            if ($count == 0) {
                                $new_cat = 1;
                            }
                        }
                        if ($new_cat == 1) {
                            $cat_id = self::saveCategory($category, $prefixlabel, $prefixarray);
                        }
                        $db->setQuery("Select count(id) from #__osrs_property_categories where pid = '$pid' and category_id = '$cat_id'");
                        $count = $db->loadResult();
                        if ($count == 0) {
                            $db->setQuery("Insert into #__osrs_property_categories (id,pid,category_id) values (NULL,'$pid','$cat_id')");
                            $db->query();
                        }
                    }
                }
            }
            //end categories

            //amenities

            $db->setQuery("Delete from #__osrs_property_amenities where pro_id = '$pid'");
            $db->query();
            if(!is_array($property['amenities']['amenity'][0])){
                $amenity = $property['amenities']['amenity'];
                $amenity_id = $amenity['AAAattributes']['id'];
                $new_amenity = 0;
                if (intval($amenity_id) == 0) {
                    $new_amenity = 1;
                } else {
                    $db->setQuery("Select count(id) from #__osrs_amenities where id = '$amenity_id'");
                    $count = $db->loadResult();
                    if ($count == 0) {
                        $new_amenity = 1;
                    }
                }
                if ($new_amenity == 1) {
                    $amenity_id = self::saveAmenity($amenity, $prefixlabel, $prefixarray);
                }
                if($amenity_id > 0) {
                    $db->setQuery("Select count(id) from #__osrs_property_amenities where pro_id = '$pid' and amen_id = '$amenity_id'");
                    $count = $db->loadResult();
                    if ($count == 0) {
                        $db->setQuery("Insert into #__osrs_property_amenities (id,pro_id,amen_id) values (NULL,'$pid','$amenity_id')");
                        $db->query();
                    }
                }
            }else {
                $amenities = $property['amenities']['amenity'];
                if (count($amenities) > 0) {
                    foreach ($amenities as $amenity) {
                        $amenity_id = $amenity['AAAattributes']['id'];
                        $new_amenity = 0;
                        if (intval($amenity_id) == 0) {
                            $new_amenity = 1;
                        } else {
                            $db->setQuery("Select count(id) from #__osrs_amenities where id = '$amenity_id'");
                            $count = $db->loadResult();
                            if ($count == 0) {
                                $new_amenity = 1;
                            }
                        }
                        if ($new_amenity == 1) {
                            $amenity_id = self::saveAmenity($amenity, $prefixlabel, $prefixarray);
                        }
                        if($amenity_id > 0) {
                            $db->setQuery("Select count(id) from #__osrs_property_amenities where pro_id = '$pid' and amen_id = '$amenity_id'");
                            $count = $db->loadResult();
                            if ($count == 0) {
                                $db->setQuery("Insert into #__osrs_property_amenities (id,pro_id,amen_id) values (NULL,'$pid','$amenity_id')");
                                $db->query();
                            }
                        }
                    }
                }
            }
            //end amenities

            //photos
            /*
            $db->setQuery("Select * from #__osrs_photos where pro_id = '$pid'");
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
            */
            $db->setQuery("Delete from #__osrs_photos where pro_id = '$pid' ");
            $db->query();
            if(!is_array($property['photos']['photo'][0])){
                $photo = $property['photos']['photo'];
                $photo_url = $photo['url'];
                $photo_desc = $photo['desc'];
                $photo_ordering = $photo['ordering'];
                if (!JFolder::exists(JPATH_ROOT . DS . "images" . DS . "osproperty" . DS . "properties" . DS . $pid)) {
                    JFolder::create(JPATH_ROOT . DS . "images" . DS . "osproperty" . DS . "properties" . DS . $pid);
                    JFolder::create(JPATH_ROOT . DS . "images" . DS . "osproperty" . DS . "properties" . DS . $pid . DS . "thumb");
                    JFolder::create(JPATH_ROOT . DS . "images" . DS . "osproperty" . DS . "properties" . DS . $pid . DS . "medium");
                    JFile::copy(JPATH_COMPONENT_ADMINISTRATOR . '/index.html', JPATH_ROOT . DS . "images" . DS . "osproperty" . DS . "properties" . DS . $pid . DS . 'index.html');
                    JFile::copy(JPATH_COMPONENT_ADMINISTRATOR . '/index.html', JPATH_ROOT . DS . "images" . DS . "osproperty" . DS . "properties" . DS . $pid . DS . "thumb" . DS . 'index.html');
                    JFile::copy(JPATH_COMPONENT_ADMINISTRATOR . '/index.html', JPATH_ROOT . DS . "images" . DS . "osproperty" . DS . "properties" . DS . $pid . DS . "medium" . DS . 'index.html');
                }
                $real_path_picture = JPATH_ROOT . DS . "images" . DS . "osproperty" . DS . "properties" . DS . $pid . DS;
                //import photos

                //get file
                $photo_name = trim(pathinfo($photo_url, PATHINFO_BASENAME));
                $image_available = 0;
                $lfile = fopen($real_path_picture . $photo_name, "x");
                if (is_callable('curl_init')) {
                    $picObj = self::getImageFromUrl($photo_url);
                    fwrite($lfile, $picObj);
                    $image_available = 1;
                    fclose($lfile);
                } else {
                    $content = file_get_contents($photo_url);
                    $fp = fopen($lfile, "w");
                    fwrite($fp, $content);
                    $image_available = 1;
                    fclose($fp);
                }

                if ($image_available == 1) {
                    JFile::copy($real_path_picture . $photo_name, $real_path_picture . '/medium/' . $photo_name);
                    JFile::copy($real_path_picture . $photo_name, $real_path_picture . '/thumb/' . $photo_name);
                    //resize pictures
                    $medium_width = $configClass['images_large_width'];
                    $medium_height = $configClass['images_large_height'];
                    // copy($original_image_link.DS.$photo['image'],$medium_image_link.DS.$photo['image']);
                    OSPHelper::resizePhoto($real_path_picture . '/medium/' . $photo_name, $medium_width, $medium_height);
                    $thumb_width = $configClass['images_thumbnail_width'];
                    $thumb_height = $configClass['images_thumbnail_height'];
                    //copy($original_image_link.DS.$photo['image'],$thumb_image_link.DS.$photo['image']);
                    OSPHelper::resizePhoto($real_path_picture . '/thumb/' . $photo_name, $thumb_width, $thumb_height);

                    $photorecord = &JTable::getInstance('Photo', 'OspropertyTable');
                    $photorecord->id = 0;
                    $photorecord->pro_id = $pid;
                    $photorecord->image = $photo_name;
                    $photorecord->ordering = $photo_ordering;
                    $photorecord->image_desc = $photo_desc;
                    $photorecord->store();
                    //$photo_id = $db->insertID();
                }

                //Update watermark
                OSPHelper::generateWaterMark($pid);
            }else {
                $photos = $property['photos']['photo'];
                if (count($photos) > 0) {
                    foreach ($photos as $photo) {
                        $photo_url = $photo['url'];
                        $photo_desc = $photo['desc'];
                        $photo_ordering = $photo['ordering'];
                        if (!JFolder::exists(JPATH_ROOT . DS . "images" . DS . "osproperty" . DS . "properties" . DS . $pid)) {
                            JFolder::create(JPATH_ROOT . DS . "images" . DS . "osproperty" . DS . "properties" . DS . $pid);
                            JFolder::create(JPATH_ROOT . DS . "images" . DS . "osproperty" . DS . "properties" . DS . $pid . DS . "thumb");
                            JFolder::create(JPATH_ROOT . DS . "images" . DS . "osproperty" . DS . "properties" . DS . $pid . DS . "medium");
                            JFile::copy(JPATH_COMPONENT_ADMINISTRATOR . '/index.html', JPATH_ROOT . DS . "images" . DS . "osproperty" . DS . "properties" . DS . $pid . DS . 'index.html');
                            JFile::copy(JPATH_COMPONENT_ADMINISTRATOR . '/index.html', JPATH_ROOT . DS . "images" . DS . "osproperty" . DS . "properties" . DS . $pid . DS . "thumb" . DS . 'index.html');
                            JFile::copy(JPATH_COMPONENT_ADMINISTRATOR . '/index.html', JPATH_ROOT . DS . "images" . DS . "osproperty" . DS . "properties" . DS . $pid . DS . "medium" . DS . 'index.html');
                        }
                        $real_path_picture = JPATH_ROOT . DS . "images" . DS . "osproperty" . DS . "properties" . DS . $pid . DS;
                        //import photos

                        //get file
                        $photo_name = trim(pathinfo($photo_url, PATHINFO_BASENAME));
                        $image_available = 0;
                        $lfile = fopen($real_path_picture . $photo_name, "x");
                        if (is_callable('curl_init')) {
                            $picObj = self::getImageFromUrl($photo_url);
                            fwrite($lfile, $picObj);
                            $image_available = 1;
                            fclose($lfile);
                        } else {
                            $content = file_get_contents($photo_url);
                            $fp = fopen($lfile, "w");
                            fwrite($fp, $content);
                            $image_available = 1;
                            fclose($fp);
                        }

                        if ($image_available == 1) {
                            JFile::copy($real_path_picture . $photo_name, $real_path_picture . '/medium/' . $photo_name);
                            JFile::copy($real_path_picture . $photo_name, $real_path_picture . '/thumb/' . $photo_name);
                            //resize pictures
                            $medium_width = $configClass['images_large_width'];
                            $medium_height = $configClass['images_large_height'];
                            // copy($original_image_link.DS.$photo['image'],$medium_image_link.DS.$photo['image']);
                            OSPHelper::resizePhoto($real_path_picture . '/medium/' . $photo_name, $medium_width, $medium_height);
                            $thumb_width = $configClass['images_thumbnail_width'];
                            $thumb_height = $configClass['images_thumbnail_height'];
                            //copy($original_image_link.DS.$photo['image'],$thumb_image_link.DS.$photo['image']);
                            OSPHelper::resizePhoto($real_path_picture . '/thumb/' . $photo_name, $thumb_width, $thumb_height);

                            $photorecord = &JTable::getInstance('Photo', 'OspropertyTable');
                            $photorecord->id = 0;
                            $photorecord->pro_id = $pid;
                            $photorecord->image = $photo_name;
                            $photorecord->ordering = $photo_ordering;
                            $photorecord->image_desc = $photo_desc;
                            $photorecord->store();
                            //$photo_id = $db->insertID();
                        }

                        //Update watermark
                        OSPHelper::generateWaterMark($pid);
                    }
                }
            }
            //end photos

            //extra fields groups and extra fields
            $db->setQuery("Delete from #__osrs_property_field_value where pro_id = '$pid'");
            $db->query();
            $db->setQuery("Delete from #__osrs_property_field_opt_value where pid = '$pid'");
            $db->query();
            $groups = $property['groups']['extrafield_group'];
            if(!is_array($groups[0])){
                $group = $property['groups']['extrafield_group'];
                $group_id = $group['AAAattributes']['id'];
                $new_group = 0;
                if (intval($group_id) == 0) {
                    $new_group = 1;
                } else {
                    $db->setQuery("Select count(id) from #__osrs_fieldgroups where id = '$group_id'");
                    $count = $db->loadResult();
                    if ($count == 0) {
                        $new_group = 1;
                    }
                }
                if ($new_group == 1) {
                    $group_id = self::saveGroup($group);
                }
                $fields = $group['fields'];
                if(is_array($fields[0])){
                    foreach ($fields as $field) {
                        $field = $field['field'];
                        //store field
                        self::saveField($field, $pid, $type_id, $group_id, $prefixlabel, $prefixarray);
                    }
                }else{
                    $field = $group['fields']['field'];
                    self::saveField($field, $pid, $type_id, $group_id, $prefixlabel, $prefixarray);
                }
            }else {
                $groups = $property['groups']['extrafield_group'];
                if (count($groups) > 0) {
                    foreach ($groups as $group) {
                        $group_id = $group['AAAattributes']['id'];
                        $new_group = 0;
                        if (intval($group_id) == 0) {
                            $new_group = 1;
                        } else {
                            $db->setQuery("Select count(id) from #__osrs_fieldgroups where id = '$group_id'");
                            $count = $db->loadResult();
                            if ($count == 0) {
                                $new_group = 1;
                            }
                        }
                        if ($new_group == 1) {
                            $group_id = self::saveGroup($group);
                        }
                        $fields = $group['fields'];
                        if(is_array($fields[0])){
                            foreach ($fields as $field) {
                                $field = $field['field'];
                                //store field
                                self::saveField($field, $pid, $type_id, $group_id, $prefixlabel, $prefixarray);
                            }
                        }else{
                            $field = $group['fields']['field'];
                            self::saveField($field, $pid, $type_id, $group_id, $prefixlabel, $prefixarray);
                        }
                    }
                }
            }
            //end extra fields
            //tax

            $db->setQuery("Delete from #__osrs_property_history_tax where pid = '$pid'");
            $db->query();

            if(!is_array($property['taxes']['tax'][0])) {
                $tax = $property['taxes']['tax'];
                $tax_year = $tax['tax_year'];
                $property_tax = $tax['property_tax'];
                $tax_change = $tax['tax_change'];
                $tax_assessment = $tax['tax_assessment'];
                $tax_assessment_change = $tax['tax_assessment_change'];
                $db->setQuery("Insert into #__osrs_property_history_tax (id,pid,tax_year,property_tax,tax_change,tax_assessment,tax_assessment_change) values (NULL,'$pid','$tax_year','$property_tax','$tax_change','$tax_assessment','$tax_assessment_change')");
                $db->query();
            }else {
                $taxes = $property['taxes']['tax'];
                if (count($taxes) > 0) {
                    foreach ($taxes as $tax) {
                        $tax_year = $tax['tax_year'];
                        $property_tax = $tax['property_tax'];
                        $tax_change = $tax['tax_change'];
                        $tax_assessment = $tax['tax_assessment'];
                        $tax_assessment_change = $tax['tax_assessment_change'];
                        $db->setQuery("Insert into #__osrs_property_history_tax (id,pid,tax_year,property_tax,tax_change,tax_assessment,tax_assessment_change) values (NULL,'$pid','$tax_year','$property_tax','$tax_change','$tax_assessment','$tax_assessment_change')");
                        $db->query();
                    }
                }
            }
            //end tax

            //history prices
            $db->setQuery("Delete from #__osrs_property_price_history where pid = '$pid'");
            $db->query();
            if(!is_array($property['history_prices']['history_price'][0])) {
                $history_price = $property['history_prices']['history_price'];
                $date = $history_price['date'];
                $event = $history_price['event'];
                $price = $history_price['price'];
                $source = $history_price['source'];
                $db->setQuery("Insert into #__osrs_property_price_history (id,pid,`date`,`event`,`price`,`source`) values (NULL,'$pid','$date','$event','$price','$source')");
                $db->query();
            }else {
                $history_prices = $property['history_prices']['history_price'];
                if (count($history_prices) > 0) {
                    foreach ($history_prices as $history_price) {
                        $date = $history_price['date'];
                        $event = $history_price['event'];
                        $price = $history_price['price'];
                        $source = $history_price['source'];
                        $db->setQuery("Insert into #__osrs_property_price_history (id,pid,`date`,`event`,`price`,`source`) values (NULL,'$pid','$date','$event','$price','$source')");
                        $db->query();
                    }
                }
            }
            //end history prices

            //tag
            $db->setQuery("Delete from #__osrs_tag_xref where pid = '$pid'");
            $db->query();
            if(!is_array($property['tags']['tag'][0])) {
                $tag = $property['tags']['tag'];
                $tag_id = $tag['AAAattributes']['id'];
                $new_tag = 0;
                if (intval($tag_id) == 0) {
                    $new_tag = 1;
                } else {
                    $db->setQuery("Select count(id) from #__osrs_tags where id = '$tag_id'");
                    $count = $db->loadResult();
                    if ($count == 0) {
                        $new_tag = 1;
                    }
                }
                if ($new_tag == 1) {
                    $tag_id = self::saveTag($tag, $prefixlabel, $prefixarray);
                }
                $db->setQuery("Insert into #__osrs_tag_xref (id, tag_id, pid) values (NULL,'$tag_id','$pid')");
                $db->query();
            }else {
                $tags = $property['tags']['tag'];
                if (count($tags) > 0) {
                    foreach ($tags as $tag) {
                        $tag_id = $tag['AAAattributes']['id'];
                        $new_tag = 0;
                        if (intval($tag_id) == 0) {
                            $new_tag = 1;
                        } else {
                            $db->setQuery("Select count(id) from #__osrs_tags where id = '$tag_id'");
                            $count = $db->loadResult();
                            if ($count == 0) {
                                $new_tag = 1;
                            }
                        }
                        if ($new_tag == 1) {
                            $tag_id = self::saveTag($tag, $prefixlabel, $prefixarray);
                        }
                        $db->setQuery("Insert into #__osrs_tag_xref (id, tag_id, pid) values (NULL,'$tag_id','$pid')");
                        $db->query();
                    }
                }
            }
            //end tag

            //save agent
            $agent = $property['agent'];
            $agent_id = $agent['AAAattributes']['id'];
            $agent_id = intval($agent_id);
            $new_agent = 0;
            if($agent_id > 0){
                $db->setQuery("Select count(id) from #__osrs_agents where id = '$agent_id'");
                $count = $db->loadResult();
                if($count == 0){
                    $new_agent = 1;
                }
            }else{
                $new_agent = 1;
            }

            $company = $agent['company'];
            if(count($company) > 0){
                $company_id = $company['AAAattributes']['id'];
                $new_company = 0;
                if(intval($company_id) == 0){
                    $new_company = 1;
                }else{
                    $db->setQuery("Select count(id) from #__osrs_companies where id = '$company_id'");
                    $count = $db->loadResult();
                    if($count == 0){
                        $new_company = 1;
                    }
                }
                if($new_company == 1){
                    $company_id = self::saveCompany($company);
                }
            }else{
                $company_id = 0;
            }
            if($new_agent == 1){
                $agent_id = self::saveAgent($agent, $company_id);
            }
            //end save agent

            //store agent and company information for properties
            $db->setQuery("Update #__osrs_properties set agent_id = '$agent_id' where id = '$pid'");
            $db->query();
            //end storing properties
        }
    }


    /**
     * Save agent
     * @param $agent
     */
    public function saveAgent($agent,$company_id){
        global $configClass;
        $db = JFactory::getDbo();
        $agent_type = $agent['agent_type'];
        $agent_name = $agent['agent_name'];
        $agent_email = $agent['agent_email'];
        $agent_phone = $agent['agent_phone'];
        $agent_fax = $agent['agent_fax'];
        $agent_address = $agent['agent_address'];
        $agent_city = $agent['agent_city'];
        $agent_state = $agent['agent_state'];
        $agent_country = $agent['agent_country'];
        $agent_yahoo = $agent['agent_yahoo'];
        $agent_skype = $agent['agent_skype'];
        $agent_aim = $agent['agent_aim'];
        $agent_msn = $agent['agent_msn'];
        $agent_gtalk = $agent['agent_gtalk'];
        $agent_facebook = $agent['agent_facebook'];
        $agent_bio = $agent['agent_bio'];
        $agent_photo = $agent['agent_photo'];
        $featured = $agent['featured'];

        $row = &JTable::getInstance('Agent','OspropertyTable');
        $db->setQuery("Select count(id) from #__osrs_agents where name like '$agent_name'");
        $count = $db->loadResult();
        if($count > 0){
            $db->setQuery("Select id from #__osrs_agents where name like '$agent_name'");
            $agent_id = $db->loadResult();
            $row->id = $agent_id;
            $new_agent = 0;
        }else{
            $row->id = 0;
            $new_agent = 1;
        }
        $country_id = $agent_country['AAAattributes']['id'];
        $country_name = $agent_country['value'];
        if($country_id > 0){
            $db->setQuery("Select count(id) from #__osrs_countries where id = '$country_id'");
            $count = $db->loadResult();
            if($count > 0){
                $row->country = $country_id;
                $new_country = 0;
            }else{
                $new_country = 1;
            }
        }else{
            $new_country = 1;
        }
        if($new_country == 1){
            $row->country = self::saveCountry($country_name);
        }

        $state_id = $agent_state['AAAattributes']['id'];
        $state_name = $agent_state['value'];
        if($state_id > 0){
            $db->setQuery("Select count(id) from #__osrs_states where id = '$state_id'");
            $count = $db->loadResult();
            if($count > 0){
                $row->state = $state_id;
                $new_state = 0;
            }else{
                $new_state = 1;
            }
        }else{
            $new_state = 1;
        }
        if($new_state == 1){
            $row->state = self::saveState($state_name,$country_id);
        }

        $city_id = $agent_city['AAAattributes']['id'];
        $city_name = $agent_city['value'];
        if($city_id > 0){
            $db->setQuery("Select count(id) from #__osrs_cities where id = '$city_id'");
            $count = $db->loadResult();
            if($count > 0){
                $row->city = $city_id;
                $new_city = 0;
            }else{
                $new_city = 1;
            }
        }else{
            $new_city = 1;
        }
        if($new_city == 1){
            $row->city = self::saveCity($city_name,$row->state,$country_id);
        }

        $row->agent_type = $agent_type;
        $row->name = $agent_name;
        $row->company_id = $company_id;
        $row->email = str_replace("AAA","@",$agent_email);
        $row->phone = $agent_phone;
        $row->fax = $agent_fax;
        $row->address = $agent_address;
        $row->yahoo = $agent_yahoo;
        $row->skype = $agent_skype;
        $row->aim = $agent_aim;
        $row->msn = $agent_msn;
        $row->gtalk = $agent_gtalk;
        $row->facebook = $agent_facebook;
        $row->bio = $agent_bio;
        $row->featured = $featured;
        $row->published = 1;
        $row->store();
        if($new_agent == 1) {
            $agent_id = $db->insertid();
        }

        if($agent_photo != ""){
            //get file
            $real_path_picture = JPATH_ROOT.'/images/osproperty/agent/';
            $photo_name = trim(pathinfo($agent_photo,PATHINFO_BASENAME));
            $image_available = 0;
            $lfile = fopen($real_path_picture . $photo_name, "x");
            if(is_callable('curl_init')){
                $picObj = self::getImageFromUrl($agent_photo);
                fwrite($lfile, $picObj);
                $image_available = 1;
                fclose($lfile);
            }else{
                $content = file_get_contents($agent_photo);
                $fp = fopen($lfile, "w");
                fwrite($fp, $content);
                $image_available = 1;
                fclose($fp);
            }

            JFile::copy($real_path_picture . $photo_name,$real_path_picture.'/thumbnail/'.$photo_name);
            $thumb_width = $configClass['images_thumbnail_width'];
            $thumb_height = $configClass['images_thumbnail_height'];
            //copy($original_image_link.DS.$photo['image'],$thumb_image_link.DS.$photo['image']);
            OSPHelper::resizePhoto($real_path_picture.'/thumbnail/'.$photo_name,$thumb_width,$thumb_height);

            $db->setQuery("Update #__osrs_agents set photo = '$photo_name' where id = '$agent_id'");
            $db->query();
        }

        $agent_alias = OSPHelper::generateAlias('agent',$agent_id,'');
        $db->setQuery("Update #__osrs_agents set alias = '$agent_alias' where id = '$agent_id'");
        $db->query();

        return $agent_id;

    }

    /**
     * Save company information
     * @param $company
     */
    public function saveCompany($company){
        global $configClass;
        $db = JFactory::getDbo();
        $company_name = $company['company_name'];
        $address = $company['address'];
        $company_city = $company['company_city'];
        $company_state = $company['company_state'];
        $company_country = $company['company_country'];
        $row = &JTable::getInstance('Companies','OspropertyTable');
        $db->setQuery("Select count(id) from #__osrs_companies where company_name like '$company_name'");
        $count = $db->loadResult();
        if($count > 0){
            $db->setQuery("Select id from #__osrs_companies where company_name like '$company_name'");
            $company_id = $db->loadResult();
            $row->id = $company_id;
            $new_company = 0;
        }else{
            $row->id = 0;
            $new_company = 1;
        }

        $row->company_name = $company_name;
        $row->address = $address;
        $country_id = $company_country['AAAattributes']['id'];
        $country_name = $company_country['value'];
        if($country_id > 0){
            $db->setQuery("Select count(id) from #__osrs_countries where id = '$country_id'");
            $count = $db->loadResult();
            if($count > 0){
                $row->country = $country_id;
                $new_country = 0;
            }else{
                $new_country = 1;
            }
        }else{
            $new_country = 1;
        }
        if($new_country == 1){
            $row->country = self::saveCountry($country_name);
        }

        $state_id = $company_state['AAAattributes']['id'];
        $state_name = $company_state['value'];
        if($state_id > 0){
            $db->setQuery("Select count(id) from #__osrs_states where id = '$state_id'");
            $count = $db->loadResult();
            if($count > 0){
                $row->state = $state_id;
                $new_state = 0;
            }else{
                $new_state = 1;
            }
        }else{
            $new_state = 1;
        }
        if($new_state == 1){
            $row->state = self::saveState($state_name,$country_id);
        }

        $city_id = $company_city['AAAattributes']['id'];
        $city_name = $company_city['value'];
        if($city_id > 0){
            $db->setQuery("Select count(id) from #__osrs_cities where id = '$city_id'");
            $count = $db->loadResult();
            if($count > 0){
                $row->city = $city_id;
                $new_city = 0;
            }else{
                $new_city = 1;
            }
        }else{
            $new_city = 1;
        }
        if($new_city == 1){
            $row->city = self::saveCity($city_name,$row->state,$country_id);
        }

        $row->phone = $company['phone'];
        $row->fax = $company['fax'];
        $row->email = str_replace("AAA","@",$company['email']);
        $row->website = $company['website'];
        $row->company_description = $company['company_description'];

        $row->store();
        if($new_company == 1) {
            $company_id = $db->insertid();
        }
        $company_logo = $company['logo'];
        //update company logo
        if($company_logo != ""){
            //get file
            $real_path_picture = JPATH_ROOT.'/images/osproperty/company/';
            $photo_name = trim(pathinfo($company_logo,PATHINFO_BASENAME));
            $image_available = 0;
            $lfile = fopen($real_path_picture . $photo_name, "x");
            if(is_callable('curl_init')){
                $picObj = self::getImageFromUrl($company_logo);
                fwrite($lfile, $picObj);
                $image_available = 1;
                fclose($lfile);
            }else{
                $content = file_get_contents($company_logo);
                $fp = fopen($lfile, "w");
                fwrite($fp, $content);
                $image_available = 1;
                fclose($fp);
            }

            JFile::copy($real_path_picture . $photo_name,$real_path_picture.'/thumbnail/'.$photo_name);
            $thumb_width = $configClass['images_thumbnail_width'];
            $thumb_height = $configClass['images_thumbnail_height'];
            //copy($original_image_link.DS.$photo['image'],$thumb_image_link.DS.$photo['image']);
            OSPHelper::resizePhoto($real_path_picture.'/thumbnail/'.$photo_name,$thumb_width,$thumb_height);

            $db->setQuery("Update #__osrs_companies set photo = '$photo_name' where id = '$company_id'");
            $db->query();
        }

        $company_alias = OSPHelper::generateAlias('company',$company_id,'');
        $db->setQuery("Update #__osrs_companies set company_alias = '$company_alias' where id = '$company_id'");
        $db->query();

        return $company_id;
    }

    public function saveTag($tag,$prefixlabel,$prefixarray){
        $db = JFactory::getDbo();
        $default_lang_code = self::getDefaultLangCode();
        $default_tag_name = $tag[$default_lang_code];
        $db->setQuery("Select count(id) from #__osrs_tags where keyword like '$default_tag_name'");
        $count = $db->loadResult();
        if($count > 0){
            $db->setQuery("Select id from #__osrs_tags where keyword like '$default_tag_name'");
            $tag_id = $db->loadResult();
            return $tag_id;
        }else{ //add new tag
            $row = &JTable::getInstance('Tag','OspropertyTable');
            $row->id = 0;
            $row->published = 1;
            for($i=0;$i<count($prefixlabel);$i++){
                $row->{'keyword'.$prefixarray[$i]} = $tag[$prefixlabel[$i]];
            }
            $row->store();
            $tag_id = $db->insertid();
            return $tag_id;
        }
    }

    public function saveField($field,$pid,$type_id,$group_id,$prefixlabel,$prefixarray){
        $db = JFactory::getDbo();
        $fieldtype = $field['AAAattributes']['fieldtype'];
        switch($fieldtype){
            case "textarea":
                self::saveFieldTextarea($field,$pid,$type_id,$group_id,$prefixlabel,$prefixarray);
                break;
            case "textbox":
                self::saveFieldTextBox($field,$pid,$type_id,$group_id,$prefixlabel,$prefixarray);
                break;
            case "date":
                self::saveFieldDate($field,$pid,$type_id,$group_id);
                break;
            case "checkbox":
                self::saveFieldCheckBoxes($field,$pid,$type_id,$group_id,$prefixlabel,$prefixarray);
                break;
            case "singleselect":
                self::saveFieldSingleSelect($field,$pid,$type_id,$group_id,$prefixlabel,$prefixarray);
                break;
            case "radio":
                self::saveFieldRadio($field,$pid,$type_id,$group_id,$prefixlabel,$prefixarray);
                break;
            case "multipleselect":
                self::saveFieldMultipleSelect($field,$pid,$type_id,$group_id,$prefixlabel,$prefixarray);
                break;
        }
    }

    public function saveFieldTextarea($field,$pid,$type_id,$group_id,$prefixlabel,$prefixarray){
        $db = JFactory::getDbo();
        $field_id = $field['AAAattributes']['id'];
        $fieldlabel = $field['AAAattributes']['fieldlabel'];
        $fieldname = $field['AAAattributes']['fieldname'];
        $fieldname = strtolower(str_replace(" ","_",$fieldname));
        $value_type = $field['AAAattributes']['value_type'];
        if(intval($field_id) == 0){
            $new_field = 1;
        }else{
            $db->setQuery("Select count(id) from #__osrs_extra_fields where id = '$field_id'");
            $count = $db->loadResult();
            if($count == 0){
                $new_field = 1;
            }else{
                $db->setQuery("Select count(id) from #__osrs_extra_fields where id = '$field_id' and field_name like '$fieldname' and group_id = '$group_id'");
                $count = $db->loadResult();
                if($count == 0){
                    $db->setQuery("Select count(id) from #__osrs_extra_fields where field_name like '$fieldname' and field_type like 'textarea'");
                    $count = $db->loadResult();
                    if($count > 0){
                        $db->setQuery("Select id from #__osrs_extra_fields where field_name like '$fieldname' and field_type like 'textarea'");
                        $field_id = $db->loadResult();
                    }else{
                        $new_field = 1;
                    }
                }
            }
        }

        if($new_field == 1){
            //store new field
            $row = &JTable::getInstance('Extrafield','OspropertyTable');
            $row->id = 0;
            $row->group_id = $group_id;
            $row->field_type = "textarea";
            $row->field_name = $fieldname;
            $row->field_label = $fieldlabel;
            $row->value_type = $value_type;
            $row->readonly = 0;
            $row->displaytitle = 1;
            $row->published = 1;
            $row->store();
            $field_id = $db->insertid();
            $db->setQuery("Insert into #__osrs_extra_field_types (id,fid,type_id) values (NULL,'$field_id','$type_id')");
            $db->query();
        }else{
            $db->setQuery("Select id from #__osrs_extra_fields where id = '$field_id' and field_name like '$fieldname' and group_id = '$group_id'");
            $field_id = $db->loadResult();
        }
        //store
        $fieldValue = &JTable::getInstance('Fieldvalue','OspropertyTable');
        $fieldValue->id = 0;
        $fieldValue->field_id = $field_id;
        $fieldValue->pro_id = $pid;
        for($i=0;$i<count($prefixlabel);$i++){
            $fieldValue->{'value'.$prefixarray[$i]} = $field[$prefixlabel[$i]];
        }
        $fieldValue->store();
    }

    public function saveFieldTextBox($field,$pid,$type_id,$group_id,$prefixlabel,$prefixarray){
        $db = JFactory::getDbo();
        $field_id = $field['AAAattributes']['id'];
        $fieldlabel = $field['AAAattributes']['fieldlabel'];
        $fieldname = $field['AAAattributes']['fieldname'];
        $fieldname = strtolower(str_replace(" ","_",$fieldname));
        $value_type = $field['AAAattributes']['value_type'];
        if(intval($field_id) == 0){
            $new_field = 1;
        }else{
            $db->setQuery("Select count(id) from #__osrs_extra_fields where id = '$field_id'");
            $count = $db->loadResult();
            if($count == 0){
                $new_field = 1;
            }else{
                $db->setQuery("Select count(id) from #__osrs_extra_fields where field_name like '$fieldname' and field_type like 'text'");
                $count = $db->loadResult();
                if($count > 0){
                    $db->setQuery("Select id from #__osrs_extra_fields where field_name like '$fieldname' and field_type like 'text'");
                    $field_id = $db->loadResult();
                }else{
                    $new_field = 1;
                }
            }
        }

        if($new_field == 1){
            //store new field
            $row = &JTable::getInstance('Extrafield','OspropertyTable');
            $row->id = 0;
            $row->group_id = $group_id;
            $row->field_type = "text";
            $row->field_name = $fieldname;
            $row->field_label = $fieldlabel;
            $row->value_type = $value_type;
            $row->readonly = 0;
            $row->displaytitle = 1;
            $row->published = 1;
            $row->store();
            $field_id = $db->insertid();
            $db->setQuery("Insert into #__osrs_extra_field_types (id,fid,type_id) values (NULL,'$field_id','$type_id')");
            $db->query();
        }else{
            $db->setQuery("Select id from #__osrs_extra_fields where id = '$field_id' and field_name like '$fieldname' and group_id = '$group_id'");
            $field_id = $db->loadResult();
        }
        //store
        switch($value_type){
            case "0":
                $fieldValue = &JTable::getInstance('Fieldvalue','OspropertyTable');
                $fieldValue->id = 0;
                $fieldValue->field_id = $field_id;
                $fieldValue->pro_id = $pid;
                for($i=0;$i<count($prefixlabel);$i++){
                    $fieldValue->{'value'.$prefixarray[$i]} = $field[$prefixlabel[$i]];
                }
                $fieldValue->store();
                break;
            case "1":
                $fieldValue = &JTable::getInstance('Fieldvalue','OspropertyTable');
                $fieldValue->id = 0;
                $fieldValue->field_id = $field_id;
                $fieldValue->pro_id = $pid;
                $fieldValue->value_integer = $field['value'];
                $fieldValue->store();
                break;
            case "2":
                $fieldValue = &JTable::getInstance('Fieldvalue','OspropertyTable');
                $fieldValue->id = 0;
                $fieldValue->field_id = $field_id;
                $fieldValue->pro_id = $pid;
                $fieldValue->value_decimal = $field['value'];
                $fieldValue->store();
                break;
        }
    }

    /**
     * @param $field
     * @param $pid
     * @param $type_id
     * @param $group_id
     */
    public function saveFieldDate($field,$pid,$type_id,$group_id){
        $db = JFactory::getDbo();
        $field_id = $field['AAAattributes']['id'];
        $fieldlabel = $field['AAAattributes']['fieldlabel'];
        $fieldname = $field['AAAattributes']['fieldname'];
        $fieldname = strtolower(str_replace(" ","_",$fieldname));
        $value_type = $field['AAAattributes']['value_type'];
        if(intval($field_id) == 0){
            $new_field = 1;
        }else{
            $db->setQuery("Select count(id) from #__osrs_extra_fields where id = '$field_id'");
            $count = $db->loadResult();
            if($count == 0){
                $new_field = 1;
            }else{
                $db->setQuery("Select count(id) from #__osrs_extra_fields where field_name like '$fieldname' and field_type like 'date'");
                $count = $db->loadResult();
                if($count > 0){
                    $db->setQuery("Select id from #__osrs_extra_fields where field_name like '$fieldname' and field_type like 'date'");
                    $field_id = $db->loadResult();
                }else{
                    $new_field = 1;
                }
            }
        }

        if($new_field == 1){
            //store new field
            $row = &JTable::getInstance('Extrafield','OspropertyTable');
            $row->id = 0;
            $row->group_id = $group_id;
            $row->field_type = "date";
            $row->field_name = $fieldname;
            $row->field_label = $fieldlabel;
            $row->value_type = $value_type;
            $row->readonly = 0;
            $row->displaytitle = 1;
            $row->published = 1;
            $row->store();
            $field_id = $db->insertid();
            $db->setQuery("Insert into #__osrs_extra_field_types (id,fid,type_id) values (NULL,'$field_id','$type_id')");
            $db->query();
        }else{
            $db->setQuery("Select id from #__osrs_extra_fields where id = '$field_id' and field_name like '$fieldname' and group_id = '$group_id'");
            $field_id = $db->loadResult();
        }

        $fieldValue = &JTable::getInstance('Fieldvalue','OspropertyTable');
        $fieldValue->id = 0;
        $fieldValue->field_id = $field_id;
        $fieldValue->pro_id = $pid;
        $fieldValue->value_date = $field['value'];
        $fieldValue->store();
    }

    public function saveFieldMultipleSelect($field,$pid,$type_id,$group_id,$prefixlabel,$prefixarray){
        $db = JFactory::getDbo();
        $field_id = $field['AAAattributes']['id'];
        $fieldlabel = $field['AAAattributes']['fieldlabel'];
        $fieldname = $field['AAAattributes']['fieldname'];
        $fieldname = strtolower(str_replace(" ","_",$fieldname));
        if(intval($field_id) == 0){
            $new_field = 1;
        }else{
            $db->setQuery("Select count(id) from #__osrs_extra_fields where id = '$field_id'");
            $count = $db->loadResult();
            if($count == 0){
                $new_field = 1;
            }else{
                $db->setQuery("Select count(id) from #__osrs_extra_fields where field_name like '$fieldname' and field_type like 'multipleselect'");
                $count = $db->loadResult();
                if($count > 0){
                    $db->setQuery("Select id from #__osrs_extra_fields where field_name like '$fieldname' and field_type like 'multipleselect'");
                    $field_id = $db->loadResult();
                }else{
                    $new_field = 1;
                }
            }
        }

        if($new_field == 1){
            //store new field
            $row = &JTable::getInstance('Extrafield','OspropertyTable');
            $row->id = 0;
            $row->group_id = $group_id;
            $row->field_type = "multipleselect";
            $row->field_name = $fieldname;
            $row->field_label = $fieldlabel;
            $row->readonly = 0;
            $row->displaytitle = 1;
            $row->published = 1;
            $row->store();
            $field_id = $db->insertid();
            $db->setQuery("Insert into #__osrs_extra_field_types (id,fid,type_id) values (NULL,'$field_id','$type_id')");
            $db->query();
        }else{
            $db->setQuery("Select id from #__osrs_extra_fields where id = '$field_id' and field_name like '$fieldname' and group_id = '$group_id'");
            $field_id = $db->loadResult();
        }

        //check options
        $db->setQuery("Delete from #__osrs_property_field_opt_value where pid = '$pid' and fid = '$field_id'");
        $db->query();
        $options = $field['option'];

        if(is_array($options[0])){
            if(count($options) > 0){
                //check each option
                foreach($options as $fieldoption){
                    $fieldoption_id = $fieldoption['AAAattributes']['id'];
                    if($fieldoption_id == 0){
                        $new_option = 1;
                    }else{
                        $db->setQuery("Select count(id) from #__osrs_extra_field_options where field_id = '$field_id' and id = '$fieldoption_id'");
                        $count = $db->loadResult();
                        if($count == 0){
                            $new_option = 1;
                        }else{
                            $new_option = 0;
                        }
                    }

                    if($new_option == 1){
                        $fieldoption_id = self::saveOption($fieldoption,$field_id,$prefixlabel,$prefixarray);
                    }

                    //add into the property relation table
                    $db->setQuery("Insert into #__osrs_property_field_opt_value (id,pid,fid,oid) values (NULL ,$pid,$field_id,$fieldoption_id)");
                    $db->query();
                }
            }
        }else{
            $fieldoption_id = $options['AAAattributes']['id'];
            if($fieldoption_id == 0){
                $new_option = 1;
            }else{
                $db->setQuery("Select count(id) from #__osrs_extra_field_options where field_id = '$field_id' and id = '$fieldoption_id'");
                $count = $db->loadResult();
                if($count == 0){
                    $new_option = 1;
                }else{
                    $new_option = 0;
                }
            }

            if($new_option == 1){
                $fieldoption_id = self::saveOption($options,$field_id,$prefixlabel,$prefixarray);
            }

            //add into the property relation table
            $db->setQuery("Insert into #__osrs_property_field_opt_value (id,pid,fid,oid) values (NULL ,$pid,$field_id,$fieldoption_id)");
            $db->query();
        }
    }

    public function saveFieldCheckBoxes($field,$pid,$type_id,$group_id,$prefixlabel,$prefixarray){
        $db = JFactory::getDbo();
        $field_id = $field['AAAattributes']['id'];
        $fieldlabel = $field['AAAattributes']['fieldlabel'];
        $fieldname = $field['AAAattributes']['fieldname'];
        $fieldname = strtolower(str_replace(" ","_",$fieldname));
        if(intval($field_id) == 0){
            $new_field = 1;
        }else{
            $db->setQuery("Select count(id) from #__osrs_extra_fields where id = '$field_id'");
            $count = $db->loadResult();
            if($count == 0){
                $new_field = 1;
            }else{
                $db->setQuery("Select count(id) from #__osrs_extra_fields where field_name like '$fieldname' and field_type like 'checkbox'");
                $count = $db->loadResult();
                if($count > 0){
                    $db->setQuery("Select id from #__osrs_extra_fields where field_name like '$fieldname' and field_type like 'checkbox'");
                    $field_id = $db->loadResult();
                }else{
                    $new_field = 1;
                }
            }
        }

        if($new_field == 1){
            //store new field
            $row = &JTable::getInstance('Extrafield','OspropertyTable');
            $row->id = 0;
            $row->group_id = $group_id;
            $row->field_type = "checkbox";
            $row->field_name = $fieldname;
            $row->field_label = $fieldlabel;
            $row->readonly = 0;
            $row->displaytitle = 1;
            $row->published = 1;
            $row->store();
            $field_id = $db->insertid();
            $db->setQuery("Insert into #__osrs_extra_field_types (id,fid,type_id) values (NULL,'$field_id','$type_id')");
            $db->query();
        }else{
            $db->setQuery("Select id from #__osrs_extra_fields where id = '$field_id' and field_name like '$fieldname' and group_id = '$group_id'");
            $field_id = $db->loadResult();
        }

        //check options
        $db->setQuery("Delete from #__osrs_property_field_opt_value where pid = '$pid' and fid = '$field_id'");
        $db->query();
        $options = $field['option'];
        if(is_array($options[0])){
            if(count($options) > 0){
                //check each option
                foreach($options as $fieldoption){
                    $fieldoption_id = $fieldoption['AAAattributes']['id'];
                    if($fieldoption_id == 0){
                        $new_option = 1;
                    }else{
                        $db->setQuery("Select count(id) from #__osrs_extra_field_options where field_id = '$field_id' and id = '$fieldoption_id'");
                        $count = $db->loadResult();
                        if($count == 0){
                            $new_option = 1;
                        }else{
                            $new_option = 0;
                        }
                    }

                    if($new_option == 1){
                        $fieldoption_id = self::saveOption($fieldoption,$field_id,$prefixlabel,$prefixarray);
                    }

                    //add into the property relation table
                    $db->setQuery("Insert into #__osrs_property_field_opt_value (id,pid,fid,oid) values (NULL ,$pid,$field_id,$fieldoption_id)");
                    $db->query();
                }
            }
        }else{
            $fieldoption_id = $options['AAAattributes']['id'];
            if($fieldoption_id == 0){
                $new_option = 1;
            }else{
                $db->setQuery("Select count(id) from #__osrs_extra_field_options where field_id = '$field_id' and id = '$fieldoption_id'");
                $count = $db->loadResult();
                if($count == 0){
                    $new_option = 1;
                }else{
                    $new_option = 0;
                }
            }

            if($new_option == 1){
                $fieldoption_id = self::saveOption($options,$field_id,$prefixlabel,$prefixarray);
            }

            //add into the property relation table
            $db->setQuery("Insert into #__osrs_property_field_opt_value (id,pid,fid,oid) values (NULL ,$pid,$field_id,$fieldoption_id)");
            $db->query();
        }
    }

    public function saveFieldSingleSelect($field,$pid,$type_id,$group_id,$prefixlabel,$prefixarray){
        $db = JFactory::getDbo();
        $field_id = $field['AAAattributes']['id'];
        $fieldlabel = $field['AAAattributes']['fieldlabel'];
        $fieldname = $field['AAAattributes']['fieldname'];
        $fieldname = strtolower(str_replace(" ","_",$fieldname));
        if(intval($field_id) == 0){
            $new_field = 1;
        }else{
            $db->setQuery("Select count(id) from #__osrs_extra_fields where id = '$field_id'");
            $count = $db->loadResult();
            if($count == 0){
                $new_field = 1;
            }else{
                $db->setQuery("Select count(id) from #__osrs_extra_fields where field_name like '$fieldname' and field_type like 'singleselect'");
                $count = $db->loadResult();
                if($count > 0){
                    $db->setQuery("Select id from #__osrs_extra_fields where field_name like '$fieldname' and field_type like 'singleselect'");
                    $field_id = $db->loadResult();
                }else{
                    $new_field = 1;
                }
            }
        }

        if($new_field == 1){
            //store new field
            $row = &JTable::getInstance('Extrafield','OspropertyTable');
            $row->id = 0;
            $row->group_id = $group_id;
            $row->field_type = "checkbox";
            $row->field_name = $fieldname;
            $row->field_label = $fieldlabel;
            $row->readonly = 0;
            $row->displaytitle = 1;
            $row->published = 1;
            $row->store();
            $field_id = $db->insertid();
            $db->setQuery("Insert into #__osrs_extra_field_types (id,fid,type_id) values (NULL,'$field_id','$type_id')");
            $db->query();
        }else{
            $db->setQuery("Select id from #__osrs_extra_fields where id = '$field_id' and field_name like '$fieldname' and group_id = '$group_id'");
            $field_id = $db->loadResult();
        }

        //check options
        $db->setQuery("Delete from #__osrs_property_field_opt_value where pid = '$pid' and fid = '$field_id'");
        $db->query();
        $options = $field['option'];
        if(is_array($options[0])){
            if(count($options) > 0){
                //check each option
                foreach($options as $fieldoption){
                    $fieldoption_id = $fieldoption['AAAattributes']['id'];
                    if($fieldoption_id == 0){
                        $new_option = 1;
                    }else{
                        $db->setQuery("Select count(id) from #__osrs_extra_field_options where field_id = '$field_id' and id = '$fieldoption_id'");
                        $count = $db->loadResult();
                        if($count == 0){
                            $new_option = 1;
                        }else{
                            $new_option = 0;
                        }
                    }

                    if($new_option == 1){
                        $fieldoption_id = self::saveOption($fieldoption,$field_id,$prefixlabel,$prefixarray);
                    }

                    //add into the property relation table
                    $db->setQuery("Insert into #__osrs_property_field_opt_value (id,pid,fid,oid) values (NULL ,$pid,$field_id,$fieldoption_id)");
                    $db->query();
                }
            }
        }else{
            $fieldoption_id = $options['AAAattributes']['id'];
            if($fieldoption_id == 0){
                $new_option = 1;
            }else{
                $db->setQuery("Select count(id) from #__osrs_extra_field_options where field_id = '$field_id' and id = '$fieldoption_id'");
                $count = $db->loadResult();
                if($count == 0){
                    $new_option = 1;
                }else{
                    $new_option = 0;
                }
            }

            if($new_option == 1){
                $fieldoption_id = self::saveOption($options,$field_id,$prefixlabel,$prefixarray);
            }

            //add into the property relation table
            $db->setQuery("Insert into #__osrs_property_field_opt_value (id,pid,fid,oid) values (NULL ,$pid,$field_id,$fieldoption_id)");
            $db->query();
        }
    }

    function saveFieldRadio($field,$pid,$type_id,$group_id,$prefixlabel,$prefixarray){
        $db = JFactory::getDbo();
        $field_id = $field['AAAattributes']['id'];
        $fieldlabel = $field['AAAattributes']['fieldlabel'];
        $fieldname = $field['AAAattributes']['fieldname'];
        $fieldname = strtolower(str_replace(" ","_",$fieldname));
        if(intval($field_id) == 0){
            $new_field = 1;
        }else{
            $db->setQuery("Select count(id) from #__osrs_extra_fields where id = '$field_id'");
            $count = $db->loadResult();
            if($count == 0){
                $new_field = 1;
            }else{
                $db->setQuery("Select count(id) from #__osrs_extra_fields where field_name like '$fieldname' and field_type like 'radio'");
                $count = $db->loadResult();
                if($count > 0){
                    $db->setQuery("Select id from #__osrs_extra_fields where field_name like '$fieldname' and field_type like 'radio'");
                    $field_id = $db->loadResult();
                }else{
                    $new_field = 1;
                }
            }
        }

        if($new_field == 1){
            //store new field
            $row = &JTable::getInstance('Extrafield','OspropertyTable');
            $row->id = 0;
            $row->group_id = $group_id;
            $row->field_type = "radio";
            $row->field_name = $fieldname;
            $row->field_label = $fieldlabel;
            $row->readonly = 0;
            $row->displaytitle = 1;
            $row->published = 1;
            $row->store();
            $field_id = $db->insertid();
            $db->setQuery("Insert into #__osrs_extra_field_types (id,fid,type_id) values (NULL,'$field_id','$type_id')");
            $db->query();
        }else{
            $db->setQuery("Select id from #__osrs_extra_fields where id = '$field_id' and field_name like '$fieldname' and group_id = '$group_id'");
            $field_id = $db->loadResult();
        }

        //check options
        $db->setQuery("Delete from #__osrs_property_field_opt_value where pid = '$pid' and fid = '$field_id'");
        $db->query();
        $options = $field['option'];
        if(is_array($options[0])){
            if(count($options) > 0){
                //check each option
                foreach($options as $fieldoption){
                    $fieldoption_id = $fieldoption['AAAattributes']['id'];
                    if($fieldoption_id == 0){
                        $new_option = 1;
                    }else{
                        $db->setQuery("Select count(id) from #__osrs_extra_field_options where field_id = '$field_id' and id = '$fieldoption_id'");
                        $count = $db->loadResult();
                        if($count == 0){
                            $new_option = 1;
                        }else{
                            $new_option = 0;
                        }
                    }

                    if($new_option == 1){
                        $fieldoption_id = self::saveOption($fieldoption,$field_id,$prefixlabel,$prefixarray);
                    }

                    //add into the property relation table
                    $db->setQuery("Insert into #__osrs_property_field_opt_value (id,pid,fid,oid) values (NULL ,$pid,$field_id,$fieldoption_id)");
                    $db->query();
                }
            }
        }else{
            $fieldoption_id = $options['AAAattributes']['id'];
            if($fieldoption_id == 0){
                $new_option = 1;
            }else{
                $db->setQuery("Select count(id) from #__osrs_extra_field_options where field_id = '$field_id' and id = '$fieldoption_id'");
                $count = $db->loadResult();
                if($count == 0){
                    $new_option = 1;
                }else{
                    $new_option = 0;
                }
            }

            if($new_option == 1){
                $fieldoption_id = self::saveOption($options,$field_id,$prefixlabel,$prefixarray);
            }

            //add into the property relation table
            $db->setQuery("Insert into #__osrs_property_field_opt_value (id,pid,fid,oid) values (NULL ,$pid,$field_id,$fieldoption_id)");
            $db->query();
        }
    }

    public static function saveOption($fieldoption,$field_id,$prefixlabel,$prefixarray){
        $db = JFactory::getDbo();
        $default_lang_code = self::getDefaultLangCode();
        $default_option_name = $fieldoption[$default_lang_code];
        $db->setQuery("Select count(id) from #__osrs_extra_field_options where field_id = '$field_id' and field_option like '$default_option_name'");
        $count = $db->loadResult();
        if($count > 0){
            $db->setQuery("Select id from #__osrs_extra_field_options where field_id = '$field_id' and field_option like '$default_option_name'");
            $fieldoption_id = $db->loadResult();
            return $fieldoption_id;
        }else{ //add new category
            $row = &JTable::getInstance('Fieldoption','OspropertyTable');
            $row->id = 0;
            $row->field_id = $field_id;
            for($i=0;$i<count($prefixlabel);$i++){
                $row->{'field_option'.$prefixarray[$i]} = $fieldoption[$prefixlabel[$i]];
            }
            $row->store();
            $fieldoption_id = $db->insertid();
            return $fieldoption_id;
        }

    }
    /**
     * Save Group name
     * @param $name
     */
    public static function saveGroup($group){
        $db = JFactory::getDbo();
        $default_group_name = $group['name'];
        $db->setQuery("Select count(id) from #__osrs_fieldgroups where group_name like '$default_group_name'");
        $count = $db->loadResult();
        if($count > 0){
            $db->setQuery("Select id from #__osrs_fieldgroups where group_name like '$default_group_name'");
            $group_id = $db->loadResult();
            return $group_id;
        }else{ //add new category
            $row = &JTable::getInstance('Fieldgroup','OspropertyTable');
            $row->id = 0;
            $row->published = 1;
            $row->group_name = $group['name'];
            $row->store();
            $group_id = $db->insertid();
            return $group_id;
        }

    }
    /**
     * Save Amenity
     * @param $category
     * @param $prefixlabel
     * @param $prefixarray
     * @return mixed
     */
    public static function saveAmenity($amenity,$prefixlabel,$prefixarray){
        $db = JFactory::getDbo();
        $default_lang_code = self::getDefaultLangCode();
        $default_amenity_name = $amenity[$default_lang_code];
        if($default_amenity_name != "") {
            $db->setQuery("Select count(id) from #__osrs_amenities where amenities like '$default_amenity_name'");
            $count = $db->loadResult();
            if ($count > 0) {
                $db->setQuery("Select id from #__osrs_amenities where amenities like '$default_amenity_name'");
                $amenity_id = $db->loadResult();
                return $amenity_id;
            } else { //add new category
                $row = &JTable::getInstance('Amenities', 'OspropertyTable');
                $row->id = 0;
                $row->published = 1;
                for ($i = 0; $i < count($prefixlabel); $i++) {
                    $row->{'amenities' . $prefixarray[$i]} = $amenity[$prefixlabel[$i]];
                }
                $row->store();
                $amenity_id = $db->insertid();
                return $amenity_id;
            }
        }else{
            return 0;
        }
    }

    /**
     * Save Country
     * @param $country
     * @return mixed
     */
    public static function saveCountry($country){
        $db = JFactory::getDbo();
        $db->setQuery("Select count(id) from #__osrs_countries where country_name like '".$country."'");
        $count = $db->loadResult();
        if($count == 0){
            $db->setQuery("Insert into #__osrs_states (id,country_name,country_code,published) values (NULL,'$country','$country')");
            $db->query();
            return $db->insertid();
        }else{
            $db->setQuery("Select count(id) from #__osrs_countries where country_name like '".$country."'");
            $country_id = $db->loadResult();
            return $country_id;
        }
    }

    /**
     * Save state
     * @param $state
     * @param $country
     * @return mixed
     */
    public static function saveState($state,$country){
        $db = JFactory::getDbo();
        $db->setQuery("Select count(id) from #__osrs_states where state_name like '".$state."' and country_id = '$country'");
        $count = $db->loadResult();
        if($count == 0){
            $db->setQuery("Insert into #__osrs_states (id,country_id,state_name,state_code,published) values (NULL,'$country','$state','$state',1)");
            $db->query();
            return $db->insertid();
        }else{
            $db->setQuery("Select id from #__osrs_states where state_name like '".$state."' and country_id = '$country'");
            $state_id = $db->loadResult();
            return $state_id;
        }
    }

    /**
     * Save City
     * @param $city
     * @param $state
     * @param $country
     * @return mixed
     */
    public static function saveCity($city,$state,$country){
        $db = JFactory::getDbo();
        $db->setQuery("Select count(id) from #__osrs_cities where city like '".$city."' and country_id = '$country' and state_id = '$state'");
        $count = $db->loadResult();
        if($count == 0){
            $db->setQuery("Insert into #__osrs_cities (id,city,country_id,state_id,published) values (NULL,'$city','$country','$state',1)");
            $db->query();
            return $db->insertid();
        }else{
            $db->setQuery("Select id from #__osrs_cities where city like '".$city."' and country_id = '$country' and state_id = '$state'");
            $city_id = $db->loadResult();
            return $city_id;
        }
    }

    public static function saveCurr($currency_code){
        $db = JFactory::getDbo();
        $db->setQuery("Select count(id) from #__osrs_currencies where currency_code like '".strtoupper($currency_code)."'");
        $count = $db->loadResult();
        if($count == 0){
            $db->setQuery("Insert into #__osrs_currencies (id,currency_name,currency_code,currency_symbol) values (NULL,'$currency_code','$currency_code','$currency_code')");
            $db->query();
            return $db->insertid();
        }else{
            $db->setQuery("Select id from #__osrs_currencies where currency_code like '".strtoupper($currency_code)."'");
            $currency_id = $db->loadResult();
            return $currency_id;
        }
    }

    public static function saveCategory($category,$prefixlabel,$prefixarray){
        $db = JFactory::getDbo();
        $default_lang_code = self::getDefaultLangCode();
        $default_cat_name = $category[$default_lang_code];

        $db->setQuery("Select count(id) from #__osrs_categories where category_name like '$default_cat_name'");
        $count = $db->loadResult();
        if($count > 0){
            $db->setQuery("Select id from #__osrs_categories where category_name like '$default_cat_name'");
            $cat_id = $db->loadResult();
            return $cat_id;
        }else{ //add new category
            $row = &JTable::getInstance('Category','OspropertyTable');
            $row->id = 0;
            $row->parent_id = 0;
            $row->ordering = 1;
            $row->access = 0;
            $row->published = 1;
            for($i=0;$i<count($prefixlabel);$i++){
                $row->{'category_name'.$prefixarray[$i]} = $category[$prefixlabel[$i]];
            }
            $row->store();

            $cat_id = $db->insertid();
            $cat_alias = OSPHelper::generateAlias('category',$cat_id,'');
            $db->setQuery("Update #__osrs_categories set category_alias = '$cat_alias' where id = '$cat_id'");
            $db->query();

            return $cat_id;
        }
    }
	
	/**
	*This function is used to check/store new property type
	**/
	public static function saveNewType($property_type,$prefixlabel,$prefixarray){
        $db = JFactory::getDbo();
		$default_lang_code = self::getDefaultLangCode();
		$default_type_name = $property_type[$default_lang_code];
        $db->setQuery("Select count(id) from #__osrs_types where type_name like '$default_type_name'");
        $count = $db->loadResult();
        if($count > 0){
            $db->setQuery("Select id from #__osrs_types where type_name like '$default_type_name'");
            $type_id = $db->loadResult();
            return $type_id;
        }else{ //add new property type
            $row = &JTable::getInstance('Type','OspropertyTable');
            $row->id = 0;
            $row->published = 1;
            for($i=0;$i<count($prefixlabel);$i++){
                $row->{'type_name'.$prefixarray[$i]} = $property_type[$prefixlabel[$i]];
            }
            $row->store();

            $type_id = $db->insertid();
            $type_alias = OSPHelper::generateAlias('type',$type_id,'');
            $db->setQuery("Update #__osrs_types set type_alias = '$type_alias' where id = '$type_id'");
            $db->query();

            return $type_id;
        }
	}

    /**
     * Get Image from Url
     *
     * @param unknown_type $link
     * @return unknown
     */
    public static function getImageFromUrl($link){
        $ch = curl_init ($link);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $result=curl_exec($ch);
        curl_close($ch);
        return $result;
    }


    /**
     * @param $option
     */
    public static function xmlExportForm($option)
    {
        global $mainframe ;

        $db = Jfactory::getDbo();
        $typeArr[] = JHTML::_('select.option', '', JText::_('OS_ALL_PROPERTY_TYPES'));
        $db->setQuery("Select id as value,type_name as text from #__osrs_types where published = '1' order by type_name");
        $protypes = $db->loadObjectList();
        $typeArr = array_merge($typeArr, $protypes);
        $lists['type'] = JHTML::_('select.genericlist', $typeArr, 'pro_type', 'class="input-large"', 'value', 'text');

        $lists['category'] = OspropertyProperties::listFilterCategories('', '');

        //Company
        $companyArr[] = JHTML::_('select.option', '', JText::_('OS_ALL_COMPANIES'));
        $db->setQuery("Select id as value,company_name as text from #__osrs_companies where published = '1' order by company_name");
        $companies = $db->loadObjectList();
        $companyArr = array_merge($companyArr, $companies);
        $lists['company'] = JHTML::_('select.genericlist', $companyArr, 'company_id', 'class="input-medium" onChange="javascript:document.adminForm.submit();"', 'value', 'text', $company_id);

        //agent
        $agentArr[] = JHTML::_('select.option', '', JText::_('OS_ALL_AGENTS'));
        $query = "Select a.id as value,a.name as text from #__osrs_agents as a inner join #__users as b on b.id = a.user_id where a.published = '1' ";
        if ($company_id != "") {
            $query .= " and a.company_id = '$company_id'";
        }
        $query .= " order by a.name";
        $db->setQuery($query);
        $agents = $db->loadObjectList();
        $agentArr = array_merge($agentArr, $agents);
        $lists['agent'] = JHTML::_('select.genericlist', $agentArr, 'agent_id', 'class="input-medium"', 'value', 'text', $agent_id);

        $lists['country'] = HelperOspropertyCommon::makeCountryList('','country','onChange="javascript:loadState(this.value,\''.$row->state.'\',\''.$row->city.'\')"','','');

        if(OSPHelper::userOneState()){
            $lists['states'] = OSPHelper::returnDefaultStateName()."<input type='hidden' name='state' id='state' value='".OSPHelper::returnDefaultState()."'/>";
        }else{
            $lists['states'] = HelperOspropertyCommon::makeStateList($row->country,$row->state,'state','onChange="javascript:loadCity(this.value,\''.$row->city.'\')"',JText::_('OS_SELECT_STATE'),'class="input-large"');
        }


        $default_state = 0;
        if(OSPHelper::userOneState()){
            $default_state = OSPHelper::returnDefaultState();
        }
        $lists['city'] = HelperOspropertyCommon::loadCity($option,$default_state,0);

        $optionArr = array();
        $optionArr[] = Jhtml::_('select.option',1,JText::_('OS_YES'));
        $optionArr[] = Jhtml::_('select.option',0,JText::_('OS_NO'));
        $lists['optionArr'] = $optionArr;
        HTML_OspropertyXml::xmlExportForm($option, $lists);
    }

    /**
     * Export XML
     *
     */
    public static function xmlExport()
    {
        global $mainframe;
        include_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/array2xml.php';
        jimport('joomla.filesystem.file');
        $db = Jfactory::getDbo();


        $query = "Select a.*,b.country_name from #__osrs_properties as a inner join #__osrs_countries as b on b.id = a.country where a.published = '1'";
        $category_id = JRequest::getInt('category_id',0);
        $pro_type = JRequest::getInt('pro_type',0);

        $agent_id = JRequest::getInt('agent_id',0);
        $company_id = JRequest::getInt('company_id',0);
        $country = JRequest::getInt('country',0);
        $state = JRequest::getInt('state',0);
        $city = JRequest::getInt('city',0);

        if($category_id > 0){
            $query .= " and a.id in (Select pid from #__osrs_property_categories where category_id = '$category_id')";
        }
        if($pro_type > 0){
            $query .= " and a.pro_type = '$pro_type'";
        }
        if($agent_id > 0){
            $query .= " and a.agent_id = '$agent_id'";
        }
        if($company_id > 0){
            $query .= " and a.agent_id in (Select company_id from #__osrs_agents where company_id = '$company_id')";
        }
        if($country > 0){
            $query .= " and a.country = '$country'";
        }
        if($state > 0){
            $query .= " and a.state = '$state'";
        }
        if($city > 0){
            $query .= " and a.city = '$city'";
        }
        $db->setQuery($query);
        $properties = $db->loadObjectList();
        $xmlarray = array();
        $xmlarray['@attributes'] = array(
            'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
            'lastUpdated' => date('c')  // dynamic values
        );

        $languages = OSPHelper::getAllLanguages();
        $default_language = OSPHelper::getDefaultLanguage();
        $prefixarray = array();
        foreach($languages as $language){
            $lang_code = explode("-",$language->lang_code);
            $lang_code = $lang_code[0];
            if($default_language == $language->lang_code){
                $prefixarray[] = "";
            }else{
                $prefixarray[] = "_".$lang_code;
            }
            $prefixlabel[] = $lang_code;
        }

        $filename = 'export'.time().'.xml';
        if (count($properties) > 0) {
            $xmlarray['listings'] = array();
            foreach ($properties as $property) {
                $db->setQuery("Select * from #__osrs_photos where pro_id = '$property->id'");
                $photos = $db->loadObjectList();

                $titleArr = array();
                for($i=0;$i<count($prefixlabel);$i++){
                    $titleArr[$prefixlabel[$i]] = $property->{'pro_name'.$prefixarray[$i]};
                }
                $db->setQuery("Select a.* from #__osrs_categories as a inner join #__osrs_property_categories as b on b.category_id = a.id where b.pid = '$property->id'");
                $categories = $db->loadObjectList();
                $categoryArr = array();
                for($j=0;$j<count($categories);$j++) {
                    $category = $categories[$j];
                    $tempArr = array();
                    $categoryArr['category'][$j] = array(
                        '@attributes' => array(
                            'id' => $category->id
                        )
                    );
                    for ($i = 0; $i < count($prefixlabel); $i++) {
                        $categoryArr['category'][$j][$prefixlabel[$i]] =  $category->{'category_name'.$prefixarray[$i]};
                    }

                }
                $db->setQuery("Select * from #__osrs_types where id = '$property->pro_type'");
                $type = $db->loadObject();
                $typeArr = array(
                    '@attributes' => array(
                        'id' => $type->id
                    ));
                for($i=0;$i<count($prefixlabel);$i++){
                    $typeArr[$prefixlabel[$i]] = $type->{'type_name'.$prefixarray[$i]};
                }

                $db->setQuery("Select currency_code from #__osrs_currencies where id = '$property->curr'");
                $curr = $db->loadResult();

                if($property->rent_time == ""){
                    $property->rent_time = 'OS_NOT_APPLICABLE';
                }
                $priceFor = array(
                    '@attributes' => array(
                        'id' => $property->rent_time
                    ),
                    'value' => JText::_($property->rent_time)
                );

                //city
                $db->setQuery("Select * from #__osrs_cities where id = '$property->city'");
                $city = $db->loadObject();
                $cityArr = array();
                $cityArr[] = array(
                    '@attributes' => array(
                        'id' => $property->city
                    ),
                    'value' => $city->city
                );


                //state
                $db->setQuery("Select * from #__osrs_states where id = '$property->state'");
                $state = $db->loadObject();
                $stateArr = array();
                $stateArr[] = array(
                    '@attributes' => array(
                        'id' => $property->state
                    ),
                    'value' => $state->state_name
                );


                //intro text
                $introArr = array();
                for($i=0;$i<count($prefixlabel);$i++){
                    $introArr[$prefixlabel[$i]] = $property->{'pro_small_desc'.$prefixarray[$i]};
                }

                //full text
                $fullArr = array();
                for($i=0;$i<count($prefixlabel);$i++){
                    $fullArr[$prefixlabel[$i]] = $property->{'pro_full_desc'.$prefixarray[$i]};
                }

                $db->setQuery("Select a.* from #__osrs_amenities as a inner join #__osrs_property_amenities as b on b.amen_id = a.id where b.pro_id = '$property->id'");
                $amenities = $db->loadObjectList();

                $amenitiesArr = array();
                for($j=0;$j<count($amenities);$j++) {
                    $amenity = $amenities[$j];
                    $amenitiesArr['amenity'][$j] = array(
                        '@attributes' => array(
                            'id' => $amenity->id
                        ));
                    for ($i = 0; $i < count($prefixlabel); $i++) {
                        $amenitiesArr['amenity'][$j][$prefixlabel[$i]] = $amenity->{'amenities'.$prefixarray[$i]};
                    }
                }

                $groupArr = array();
                $db->setQuery("Select * from #__osrs_fieldgroups where published = '1'");
                $groups = $db->loadObjectList();
                for($g=0;$g<count($groups);$g++){
                    $group = $groups[$g];
                    $extraSql = " and id in (Select fid from #__osrs_extra_field_types where type_id = '$property->pro_type') ";
                    $db->setQuery("Select * from #__osrs_extra_fields where published = '1' and group_id = '$group->id' $extraSql order by ordering");
                    $fields = $db->loadObjectList();

                    $fieldArr = array();
                    if(count($fields) > 0) {
                        for ($k = 0; $k < count($fields); $k++) {
                            $field = $fields[$k];
                            $fieldValue = self::showXMLField($field,$property->id,$prefixlabel,$prefixarray);
                            if(count($fieldValue) > 0) {
                                $fieldArr[] = $fieldValue;
                            }
                        }
                        $groupArr['extrafield_group'][$g] = array(
                            '@attributes' => array(
                                'id' => $group->id
                            ),
                            'name' => $group->group_name,
                            'fields' => $fieldArr
                        );
                    }
                }

                //meta description text
                $metaArr = array();
                for($i=0;$i<count($prefixlabel);$i++){
                    $metaArr[$prefixlabel[$i]] = $property->{'metadesc'.$prefixarray[$i]};
                }

                if(count($photos) > 0){
                    $photoArr = array();
                    foreach($photos as $photo){
                        $image = $photo->image;
                        if(($image != "") and (file_exists(JPATH_ROOT.'/images/osproperty/properties/'.$property->id.'/'.$image))){
                            $photoArr['photo'][] = array(
                                'url' => JUri::root().'images/osproperty/properties/'.$property->id.'/'.$image,
                                'desc' => $photo->image_desc,
                                'ordering' => $photo->ordering
                            );
                        }
                    }
                }

                //taxes
                $db->setQuery("Select * from #__osrs_property_history_tax where pid = '$property->id'");
                $taxes = $db->loadObjectList();

                $taxItems = array();
                if(count($taxes) > 0){
                    foreach($taxes as $tax){
                        $taxItems['tax'][] = array(
                            'tax_year' => $tax->tax_year,
                            'property_tax' => $tax->property_tax,
                            'tax_change' => $tax->tax_change,
                            'tax_assessment' => $tax->tax_assessment,
                            'tax_assessment_change' => $tax->tax_assessment_change
                        );
                    }
                }

                //history
                $db->setQuery("Select * from #__osrs_property_price_history where pid = '$property->id'");
                $histories = $db->loadObjectList();

                $historyItems = array();
                if(count($histories) > 0){
                    foreach($histories as $history){
                        $historyItems['history_price'][] = array(
                            'date' => $history->date,
                            'event' => $history->event,
                            'price' => $history->price,
                            'source' => $history->source
                        );
                    }
                }

                //taxes
                $db->setQuery("Select * from #__osrs_tags as a inner join #__osrs_tag_xref as b on b.tag_id = a.id where b.pid = '$property->id'");
                $tags = $db->loadObjectList();
                $tagItems = array();
                if(count($tags) > 0){
                    for($j=0;$j<count($tags);$j++){
                        $tag = $tags[$j];
                        $tagItems['tag'][$j] = array(
                            '@attributes' => array(
                                'id' => $tag->id
                            )
                        );
                        for ($i = 0; $i < count($prefixlabel); $i++) {
                            $tagItems['tag'][$j][$prefixlabel[$i]] = $tag->{'keyword'.$prefixarray[$i]};
                        }
                    }
                }

                //agent
                $db->setQuery("Select * from #__osrs_agents where id = '$property->agent_id'");
                $agent = $db->loadObject();
                $db->setQuery("Select * from #__osrs_companies where id = '$agent->company_id'");
                $company = $db->loadObject();
                $companyArr = array();
                if($company->id > 0){
                    $db->setQuery("Select state_name from #__osrs_states where id = '$company->state'");
                    $state_name = $db->loadResult();
                    if($state_name == ""){
                        $state_name = "N/A";
                    }

                    $db->setQuery("Select city from #__osrs_cities where id = '$company->city'");
                    $city_name = $db->loadResult();
                    if($city_name == ""){
                        $city_name = "N/A";
                    }

                    $db->setQuery("Select country_name from #__osrs_countries where id = '$company->country'");
                    $country_name = $db->loadResult();

                    if(($company->company_photo != "") and (file_exists(JPATH_ROOT.'/images/osproperty/company/'.$company->company_photo))){
                        $logo = JUri::root().'images/osproperty/company/'.$company->company_photo;
                    }
                    $include_cids = JRequest::getInt('include_cids',0);
                    if($include_cids == 0){
                        $company->id = 0;
                    }
                    $companyArr = array(
                        '@attributes' => array(
                            'id' => $company->id
                        ),
                        'company_name' => $company->company_name,
                        'address' => $company->address,
                        'company_city' => array(
                            '@attributes' => array('id' => $company->city ),
                            'value' => $city_name
                        ),
                        'company_state' => array(
                            '@attributes' => array('id' => $company->state),
                            'value' => $state_name
                        ),
                        'company_country' => array(
                            '@attributes' => array('id' => $company->country),
                            'value' => $country_name
                        ),
                        'phone' => $company->phone,
                        'fax' => $company->fax,
                        'email' => $company->email,
                        'website' => $company->website,
                        'desc' => $company->company_description,
                        'logo' => $logo
                    );
                }

                $db->setQuery("Select state_name from #__osrs_states where id = '$agent->state'");
                $state_name = $db->loadResult();
                if($state_name == ""){
                    $state_name = "N/A";
                }

                $db->setQuery("Select city from #__osrs_cities where id = '$agent->city'");
                $city_name = $db->loadResult();
                if($city_name == ""){
                    $city_name = "N/A";
                }

                $db->setQuery("Select country_name from #__osrs_countries where id = '$agent->country'");
                $country_name = $db->loadResult();

                if(($agent->photo != "") and (file_exists(JPATH_ROOT.'/images/osproperty/agent/'.$agent->photo))){
                    $logo = JUri::root().'images/osproperty/agent/'.$company->photo;
                }
                $include_aids = JRequest::getInt('include_aids',0);
                if($include_aids == 0){
                    $agent->id = 0;
                }
                $agentArr = array(
                    '@attributes' => array(
                        'id' => $agent->id
                    ),
                    'agent_type' => $agent->agent_type,
                    'agent_name' => $agent->name,
                    'agent_email' => $agent->email,
                    'agent_phone' => $agent->phone,
                    'agent_fax' => $agent->fax,
                    'agent_address' => $agent->address,
                    'agent_city' => array(
                        '@attributes' => array('id' => $agent->city),
                        'value' => $city_name
                    ),
                    'agent_state' => array(
                        '@attributes' => array('id' => $agent->state),
                        'value' => $state_name
                    ),
                    'agent_country' => array(
                        '@attributes' => array('id' => $agent->country),
                        'value' => $country_name
                    ),
                    'agent_yahoo' => $agent->yahoo,
                    'agent_skype' => $agent->skype,
                    'agent_aim' => $agent->aim,
                    'agent_msn' => $agent->msn,
                    'agent_gtalk' => $agent->gtalk,
                    'agent_facebook' => $agent->facebook,
                    'agent_bio' => $agent->bio,
                    'agent_photo' => $logo,
                    'featured' => $agent->featured,
                    'company' => $companyArr
                );
                if($property->pro_pdf_file != ""){
                    $property->pro_pdf_file = JUri::root().'/components/com_osproperty/document/'.$property->pro_pdf_file;
                }
                $include_pids = JRequest::getInt('include_pids',0);
                if($include_pids == 0){
                    $property->id = 0;
                }
                $xmlarray['listings']['listing'][] = array(
                    '@attributes' => array(
                        'id' => $property->id
                    ),
                    'ref' => $property->ref,
                    'access' => $property->access,
                    'title' => $titleArr,
                    'categories' => $categoryArr,
                    'type' => $typeArr,
                    'price_call' => $property->price_call,
                    'price' => $property->price,
                    'curr' => $curr,
                    'price_for' => $priceFor,
                    'featured' => $property->isFeatured,
                    'sold' => $property->isSold,
                    'soldOn' => $property->soldOn,
                    'address' => $property->address,
                    'city' => $cityArr,
                    'state' => $stateArr,
                    'country' => array(
                        '@attributes' => array(
                            'id' => $property->country
                        ),
                        'value' => $property->country_name),
                    'show_address' => $property->show_address,
                    'lat_address' => $property->lat_add,
                    'long_address' => $property->long_add,
                    'bath' => $property->bath_room,
                    'bed' => $property->bed_room,
                    'floor' => $property->number_of_floors,
                    'rooms' => $property->rooms,
                    'parking' => $property->parking,
                    'bulding_size' => $property->square_feet,
                    'lot_size' => $property->lot_size,
                    'unit' => OSPHelper::showSquareSymbol(),
                    'energy' => $property->energy,
                    'co2' => $property->climate,
                    'document' => $property->pro_pdf,
                    'document_url' => $property->pro_pdf_file,
                    'small_desc' => $introArr,
                    'full_desc' => $fullArr,
                    'amenities' => $amenitiesArr,
                    'groups' => $groupArr,
                    'meta' => $metaArr,
                    'photos' => $photoArr,
                    'taxes' => $taxItems,
                    'history_prices' => $historyItems,
                    'tags' => $tagItems,
                    'agent' => $agentArr
                );
                $xml = Array2XML::createXML('xmlarray', $xmlarray);
                JFile::write(JPATH_ROOT.'/tmp/'.$filename,$xml->saveXML());
            }
        }else{
            $xmlarray['listing'] = array();
            $xml = Array2XML::createXML('xmlarray', $xmlarray);
            JFile::write(JPATH_ROOT.'/tmp/'.$filename,$xml->saveXML());
        }
        HelperOspropertyCommon::downloadxmlfile(JPATH_ROOT.'/tmp/'.$filename);
    }

    public function showXMLField($field,$pid,$prefixlabel,$prefixarray){
        global $mainframe;
        $db = JFactory::getDBO();

        switch ($field->field_type){
            case "text":
                return self::showField_Text($field,$pid,$prefixlabel,$prefixarray);
                break;
            case "date":
                return self::showField_Date($field,$pid,$prefixlabel,$prefixarray);
                break;
            case "textarea":
                return self::showField_Textarea($field,$pid,$prefixlabel,$prefixarray);
                break;
            case "radio":
                return self::showField_Radio($field,$pid,$prefixlabel,$prefixarray);
                break;
            case "checkbox":
                return self::showField_Checkbox($field,$pid,$prefixlabel,$prefixarray);
                break;
            case "singleselect":
                return self::showField_Singleselect($field,$pid,$prefixlabel,$prefixarray);
                break;
            case "multipleselect":
                return self::showField_Multipleselect($field,$pid,$prefixlabel,$prefixarray);
                break;
        }
    }

    public static function showField_Text($field,$pid,$prefixlabel,$prefixarray){
        $db = JFactory::getDbo();
        $db->setQuery("Select * from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$field->id'");
        $obj = $db->loadObject();
        $tempArr = array();
        if($obj->id > 0){
            if($field->value_type == 0){
                $tempArr['field'] = array(
                    '@attributes' => array(
                        'id' => $field->id,
                        'fieldtype' => 'textbox',
                        'fieldname' => $field->field_name,
                        'fieldlabel' => $field->field_label,
                        'value_type' => $field->value_type
                    )
                );
                for ($i = 0; $i < count($prefixlabel); $i++) {
                    $value = $obj->{'value' . $prefixarray[$i]};
                    if ($value != "") {
                        $tempArr['field'][$prefixlabel[$i]] =  $value;
                    }
                }
            }elseif($field->value_type == 1){
                $value = $obj->value_integer;
                if($value > 0){
                    $tempArr['field'][] = array(
                        '@attributes' => array(
                            'id' => $field->id,
                            'fieldtype' => 'textbox',
                            'fieldname' => $field->field_name,
                            'fieldlabel' => $field->field_label,
                            'value_type' => $field->value_type
                        ),
                        'value' => $value
                    );
                }
            }else{
                $value = $obj->value_decimal;
                if($value > 0){
                    $tempArr['field'][] = array(
                        '@attributes' => array(
                            'id' => $field->id,
                            'fieldtype' => 'textbox',
                            'fieldname' => $field->field_name,
                            'fieldlabel' => $field->field_label,
                            'value_type' => $field->value_type
                        ),
                        'value' => $value
                    );
                }
            }
        }
        return $tempArr;
    }
    public static function showField_Date($field,$pid,$prefixlabel,$prefixarray){
        $db = JFactory::getDbo();
        $db->setQuery("Select * from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$field->id'");
        $obj = $db->loadObject();
        $tempArr = array();
        if($obj->id > 0){
            $value = $obj->value_date;
            if($value != ""){
                $tempArr['field'][] = array(
                    '@attributes' => array(
                        'id' => $field->id,
                        'fieldtype' => 'date',
                        'fieldlabel' => $field->field_label,
                        'fieldname' => $field->field_name
                    ),
                    'value' => $value
                );
            }
        }
        return $tempArr;
    }
    public static function showField_Textarea($field,$pid,$prefixlabel,$prefixarray){
        $db = JFactory::getDbo();
        $db->setQuery("Select * from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$field->id'");
        $obj = $db->loadObject();
        $tempArr = array();
        if($obj->id > 0){
            $tempArr['field'] = array(
                '@attributes' => array(
                    'id' => $field->id,
                    'fieldtype' => 'textarea',
                    'fieldlabel' => $field->field_label,
                    'fieldname' => $field->field_name
                )
            );
            for ($i = 0; $i < count($prefixlabel); $i++) {
                $value = $obj->{'value'.$prefixarray[$i]};
                if($value != ""){
                    $tempArr['field'][$prefixlabel[$i]] =  $value;
                }
            }
        }
        return $tempArr;
    }
    public static function showField_Radio($field,$pid,$prefixlabel,$prefixarray){
        $db = JFactory::getDbo();
        $db->setQuery("Select `oid` from #__osrs_property_field_opt_value where pid = '$pid' and fid = '$field->id'");
        $value = $db->loadResult();
        if($value != ""){
            $db->setQuery("Select * from #__osrs_extra_field_options where id = '$value'");
            $obj = $db->loadObject();
        }
        $tempArr = array();
        if($obj->id > 0) {
            for ($i = 0; $i < count($prefixlabel); $i++) {
                $value = $obj->{'field_option' . $prefixarray[$i]};
                if ($value != "") {
                    $tempArr[] = array(
                        '@attributes' => array(
                            'id' => $obj->id
                        ),
                        $prefixlabel[$i] => $value
                    );
                }
            }

            $fieldArr['field'][] = array(
                '@attributes' => array(
                    'id' => $field->id,
                    'fieldtype' => 'radio',
                    'fieldlabel' => $field->field_label,
                    'fieldname' => $field->field_name
                ),
                'option' => $tempArr
            );
        }
        return $fieldArr;
    }
    public static function showField_Checkbox($field,$pid,$prefixlabel,$prefixarray){
        $db = JFactory::getDbo();
        $db->setQuery("Select `oid` from #__osrs_property_field_opt_value where pid = '$pid' and fid = '$field->id'");
        $value = $db->loadColumn(0);
        if(count($value) > 0){
            $db->setQuery("Select * from #__osrs_extra_field_options where id in (".implode(",",$value).")");
            $objs = $db->loadObjectList();
        }

        $tempArr = array();
        $fieldArr = array();
        $optionArr = array();
        if(count($objs) > 0){
            for($j=0;$j<count($objs);$j++){
                $obj = $objs[$j];
                $optionArr[$j] = array(
                    '@attributes' => array(
                        'id' => $obj->id
                    )
                );

                for ($i = 0; $i < count($prefixlabel); $i++) {
                    $value = $obj->{'field_option' . $prefixarray[$i]};
                    if ($value != "") {
                        $optionArr[$j][$prefixlabel[$i]] =  $value;
                    }
                }
            }
            $fieldArr['field'][] = array(
                '@attributes' => array(
                    'id' => $field->id,
                    'fieldtype' => 'checkbox',
                    'fieldlabel' => $field->field_label,
                    'fieldname' => $field->field_name
                ),
                'option' => $optionArr
            );
        }
        return $fieldArr;
    }
    public static function showField_Singleselect($field,$pid,$prefixlabel,$prefixarray){
        $db = JFactory::getDbo();
        $db->setQuery("Select `oid` from #__osrs_property_field_opt_value where pid = '$pid' and fid = '$field->id'");
        $value = $db->loadResult();
        if($value != ""){
            $db->setQuery("Select * from #__osrs_extra_field_options where id = '$value'");
            $obj = $db->loadObject();
        }
        $tempArr = array();
        $fieldArr = array();
        if($obj->id > 0) {
            for ($i = 0; $i < count($prefixlabel); $i++) {
                $value = $obj->{'field_option' . $prefixarray[$i]};
                if ($value != "") {
                    $tempArr[] = array(
                        '@attributes' => array(
                            'id' => $obj->id
                        ),
                        $prefixlabel[$i] => $value
                    );
                }
            }

            $fieldArr['field'][] = array(
                '@attributes' => array(
                    'id' => $field->id,
                    'fieldtype' => 'singleselect',
                    'fieldlabel' => $field->field_label,
                    'fieldname' => $field->field_name
                ),
                'option' => $tempArr
            );
        }
        return $fieldArr;
    }
    public static function showField_Multipleselect($field,$pid,$prefixlabel,$prefixarray){
        $db = JFactory::getDbo();
        $db->setQuery("Select `oid` from #__osrs_property_field_opt_value where pid = '$pid' and fid = '$field->id'");
        $value = $db->loadColumn(0);
        if(count($value) > 0){
            $db->setQuery("Select * from #__osrs_extra_field_options where id in (".implode(",",$value).")");
            $objs = $db->loadObjectList();
        }
        $tempArr = array();
        $fieldArr = array();
        $count = count($fieldArr);
        $optionArr = array();
        if(count($objs) > 0){
            for($j=0;$j<count($objs);$j++){
                $obj = $objs[$j];
                $optionArr[$j] = array(
                    '@attributes' => array(
                        'id' => $obj->id
                    )
                );

                for ($i = 0; $i < count($prefixlabel); $i++) {
                    $value = $obj->{'field_option' . $prefixarray[$i]};
                    if ($value != "") {
                        $optionArr[$j][$prefixlabel[$i]] =  $value;
                    }
                }
            }
            $fieldArr['field'][] = array(
                '@attributes' => array(
                    'id' => $field->id,
                    'fieldtype' => 'multipleselect',
                    'fieldlabel' => $field->field_label,
                    'fieldname' => $field->field_name
                ),
                'option' => $optionArr
            );
        }
        return $fieldArr;
    }
}
?>

