<?php
/*------------------------------------------------------------------------
# compare.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class OspropertyCompare{
	/**
	 * Comparision
	 *
	 * @param unknown_type $option
	 * @param unknown_type $task
	 */
	static function display($option,$task){
		global $mainframe,$configClass;
		$p = JRequest::getInt('p',0);
		//$array = "1,7,3";
		//setcookie('comparelist',$array,time()+3600);
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root()."components/com_osproperty/templates/default/style/style.css");
		//echo HelperOspropertyCommon::buildToolbar('property');
		switch ($task){
			case "compare_remove":
				OspropertyCompare::remove($option);
			break;
			default:
				$show_top_menus_in = $configClass['show_top_menus_in'];
				$show_top_menus_in = explode("|",$show_top_menus_in);
				if((in_array('compare',$show_top_menus_in)) and ($p == 0)){
					echo HelperOspropertyCommon::buildToolbar('compare');
				}
				OspropertyCompare::compare($option,$task);
			break;
			
		}
	}
	
	
	/**
	 * Compare function
	 *
	 * @param unknown_type $option
	 * @param unknown_type $task
	 */
	
	static function compare($option,$task){
		global $mainframe,$configs,$configClass,$lang_suffix;
		$db = JFactory::getDBO();
		$document = JFactory::getDocument();
		$document->setTitle($configClass['general_bussiness_name']." - ".JText::_('OS_COMPARE_PROPERTIES'));
		$session = JFactory::getSession();
		$comparelist_ids = $session->get('comparelist');
		$comparelist = explode(",",trim($comparelist_ids));
		
		if(trim($comparelist_ids) == ""){ //no property for comparision
			?>
			<div class="componentheading">
				<h1 style="border:0px;">
					<?php echo JText::_('OS_NO_ITEMS_TO_COMPARE');?>
				</h1>
				<?php
				printf(JText::_('CLICK_HERE_TO_GO_BACK'),"<a href='javascript:history.go(-1)'>","</a>");
				?>
			</div>
			<?php
		}else{
			$comparisionArr = array();
			$j = 0;
			$fields = HelperOspropertyCommon::getExtrafieldInList();
			for($i=0;$i<count($comparelist);$i++){
				$pid = $comparelist[$i];
				if($pid > 0){
					$db->setQuery("Select * from #__osrs_properties where id = '$pid'");
					$property = $db->loadObject();
					$comparisionArr[$j]->property = $property;
					$comparisionArr[$j]->show_address = $property->show_address;
					$db->setQuery("Select * from #__osrs_photos where pro_id = '$pid'");
					$photo = $db->loadOBject();
					$comparisionArr[$j]->photo = $photo;
					$query = "Select b.* from #__osrs_property_amenities as a"
							." inner join #__osrs_amenities as b on b.id = a.amen_id"
							." where a.pro_id = '$pid' order by b.amenities";
					$db->setQuery($query);
					$amenities = $db->loadObjectList();
					$comparisionArr[$j]->amenities = $amenities;
					//$db->setQuery("Select id,category_name$lang_suffix as category_name from #__osrs_categories where id = '$property->category_id'");
					//$rs = $db->loadObject();
					$comparisionArr[$j]->category_name = OSPHelper::getCategoryNamesOfProperty($property->id);//$rs->category_name;
					
					$db->setQuery("Select id,type_name$lang_suffix as type_name from #__osrs_types where id = '$property->pro_type'");
					$rs = $db->loadObject();
					$comparisionArr[$j]->property_type = $rs->type_name;
					
					$db->setQuery("Select state_name from #__osrs_states where id = '$property->state'");
					$comparisionArr[$j]->state = $db->loadResult();
					
					$db->setQuery("Select country_name from #__osrs_countries where id = '$property->country'");
					$comparisionArr[$j]->country = $db->loadResult();
					
					$fieldArr = array();
					if(count($fields) > 0){
						
						$k 		  = 0;
						for($l=0;$l<count($fields);$l++){
							$field = $fields[$l];
							$value = "";
							$value = HelperOspropertyFieldsPrint::showField($field,$property->id);
							//if($value != ""){
							$fieldArr[$l]->fieldvalue = $value;
							//}
							//print_r($fieldArr);
						}
						$comparisionArr[$j]->fieldarr = $fieldArr;
					}
					
					$j++;
					
				}
			}
			$isPrint = JRequest::getInt('p',0);
			HTML_OspropertyCompare::showCompareForm($option,$comparisionArr,$configs,$isPrint,$fields);
		}
	}
	
	/**
	 * Remove property out of the compare list
	 *
	 * @param unknown_type $option
	 */
	static function remove($option){
		global $mainframe;
		$session = JFactory::getSession();
		$needs = array();
		$needs[] = "rcompare";
		$needs[] = "compare_properties";
		$itemid = OSPRoute::getItemid($needs);
		//$itemid = OSPRoute::confirmItemid($itemid,'compare_properties');
		$pid = JRequest::getInt('pid',0);
		$comparelist = $session->get('comparelist');
		$comparelist = explode(",",$comparelist);
		$newcomparelist = array();
		$j = 0 ;
		for($i=0;$i<count($comparelist);$i++){
			if($comparelist[$i] != $pid){
				$newcomparelist[$j] = $comparelist[$i];
				$j++;
			}
		}
		$comparelist = $newcomparelist;
		$comparelist = implode($comparelist,",");
		$session->set('comparelist',$comparelist);
		if($comparelist != ""){
			$mainframe->redirect(JRoute::_("index.php?option=com_osproperty&task=compare_properties&Itemid=".$itemid));
		}else{
			$mainframe->redirect(JURI::root(),JText::_('OS_NOTHING_TO_COMPARE'));
		}
	}
}
?>