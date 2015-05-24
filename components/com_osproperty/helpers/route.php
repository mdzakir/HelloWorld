<?php
/*------------------------------------------------------------------------
# router.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die();
error_reporting(E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR);
class OSPRoute
{
	protected static $lookup;
	/**
	 * Check and return Itemid
	 *
	 * @param array $needs
	 */
	public static function getItemid($needs){
		global $mainframe,$configClass;
		
		$needs1 = array();
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$db->setQuery("Select fieldvalue from #__osrs_configuration where fieldname like 'default_itemid'");
		$default_itemid = $db->loadResult();
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');
		$component	= JComponentHelper::getComponent('com_osproperty');
		$items		= $menus->getItems('component_id', $component->id);
		foreach ($items as $item){
			self::$lookup[] = $item->id;
		}
		$lookup_sql = "";
		if(count(self::$lookup) > 0){
			$lookup_sql = " and id in (".implode(",",self::$lookup).")";
		}else{
			$lookup_sql = "";
		}
		$additional_sql = "";
		$language_sql = "";
		if (JLanguageMultilang::isEnabled()){
			$language = JFactory::getLanguage();
			$current_lag = $language->getTag();
			$language_sql = " and (`language` LIKE '$current_lag' or `language` LIKE '*' or `language` = '')";
		}
		$find_pro_type = 0;
		$find_category_id = array();
		$find_company_id = 0;
		$find_country = 0;
		$find_isFeatured = 0;
		$find_state_id = 0;
		
		if(count($needs) > 0){
			
			if($needs[0] == "property_details"){
				$pid = $needs[1];
				$find_lang = $needs[2];
				if($pid > 0){
					$db->setQuery("Select agent_id,pro_type,state,country,isFeatured from #__osrs_properties where id = '$pid'");
					$property = $db->loadObject();
					$pro_type = $property->pro_type;
					//$category_id = $property->category_id;
					$db->setQuery("Select category_id from #__osrs_property_categories where pid = '$pid'");
					$category_id = $db->loadColumn(0);
					$state = $property->state;
					$agent_id = $property->agent_id;
					$country = $property->country;
					$isFeatured = $property->isFeatured;
					$db->setQuery("Select company_id from #__osrs_company_agents where agent_id = '$agent_id'");
					$company_id = $db->loadResult();
					
					$needs    = array();
					$needs[]  = "property_type";
					$needs[]  = "ltype";
					$needs1[]  = "type_id=$pro_type";
					
					if (JLanguageMultilang::isEnabled()){
						if($find_lang != ""){
							$current_lag = $find_lang;
						}else{
							$language = JFactory::getLanguage();
							$current_lag = $language->getTag();
						}
						$language_sql = " and (`language` LIKE '$current_lag' or `language` LIKE '*' or `language` = '')";
					}

					$db->setQuery("Select * from #__menu where published = '1' and `home` = '0' and `link` like '%view=ltype%' $language_sql and `access` IN (". implode(',', $user->getAuthorisedViewLevels()) .")");
					$menus_found = $db->loadObjectList();
					
					$jmenu = JSite::getMenu();
					
					if(count($menus_found) > 0){
						$menuArr = array();
						for($i=0;$i<count($menus_found);$i++){
							$return = 0;
							$menu = $menus_found[$i];
							
							$mid = $menu->id;
    						$mobj = $jmenu->getItem( $mid );
							//print_r($mobj->query);
							$find_pro_type =  $mobj->query['type_id'];
							$find_category_id = $mobj->query['catIds'];
							$find_company_id = $mobj->query['company_id'];
							$find_country = $mobj->query['country_id'];
							//echo $find_country;
							$params = $menu->params;
							$params = json_decode($params);
							$find_isFeatured = $params->isFeatured;
							$find_state_id = $params->state_id;
							
							//$arr1 = array();
							//$arr2 = array();
							//find itemid now
							if($find_pro_type > 0){
								if($find_pro_type == $pro_type){ //ok
									$type = 1;
									$return++;
								}else{
									$type = 0;
								}
							}else{
								$type = 0;
							}
							if((count($find_category_id) > 0) and (count($category_id) > 0)){
								$show = 0;
								foreach($category_id as $cid){
									if(in_array($cid,$find_category_id)){
										$show = 1;
									}
								}
								if($show == 1){
									$cat = 1;
									$return++;
									
									if(count($find_category_id) == count($category_id)){
										$return++; //use for case: Parent menu contains several sub cats. And there is other link for one sub cat. The system must get Itemid of that sub cat. 
									}
								}
							}else{
								$cat = 0;
							}
							if($find_country > 0){
								if($find_country == $country){ //ok
									$c = 1;
									$return++;
								}else{
									$c = 0;
								}
							}else{
								$c = 0;
							}
							
							if($find_state_id > 0){
								if($find_state_id == $state){ //ok
									$s = 1;
									$return++;
								}else{
									$s = 0;
								}
							}else{
								$s = 0;
							}
							
							
							if($find_company_id > 0){
								if($find_company_id == $company_id){ //ok
									$company = 1;
									$return++;
								}else{
									$company = 0;
								}
							}else{
								$company = 0;
							}
							
							if($find_isFeatured > 0){
								if($find_isFeatured == $isFeatured){ //ok
									$featured = 1;
									if($return > 0){
										$return = $return + 2;
									}
								}else{
									$featured = 0;
								}
							}else{
								$featured = 0;
							}
							
							$count = count($menuArr);
							$menuArr[$count]->point = $return;
							$menuArr[$count]->menu_id = $menu->id;
							
						}//end for
						$max = 0;
						//$menus	= $app->getMenu('site');
						$menuid = $default_itemid;
						if($menuid == 0){
							$menuid = $default_itemid;
						}
						//print_r($menuArr);
						for($i=0;$i<count($menuArr);$i++){
							if($menuArr[$i]->point > $max){
								$max = $menuArr[$i]->point;
								$menuid = $menuArr[$i]->menu_id;
							}
						}
						if($max == 0){
							//$menu = $active->id;
							$menus	= $app->getMenu('site');
							$active = $menus->getActive();
							$db->setQuery("Select count(id) from #__menu where published = '1' and `home` = '0' and `link` like '%view=ltype%' $language_sql and id = '".intval($active->id)."'");
							$count = $db->loadResult();
							if($count > 0){
								$menuid = $active->id;
							}else{
								$db->setQuery("Select id from #__menu where published = '1' and `home` = '0' and `link` like '%view=ltype%' ".$language_sql);
								$menuid = $db->loadResult();
							}
						}
						//echo $menu;
						if($menuid > 0){
							return $menuid;
						}else{
							return 9999;
						}
					}//end menus_found
					else{ //checking in category
						$db->setQuery("Select * from #__menu where published = '1' and (`link` like 'view=lcategory' or `link` like 'task=category_listing') $language_sql");
						$menus_found = $db->loadObjectList();
						if(count($menus_found) > 0){
							$menuid = $menus_found[0]->id;
							return $menuid;
						}else{
							$menuid = $default_itemid;
							if($menuid == 0){
								//$active = $menus->getActive();
								$menuid = $default_itemid;
							}
							return $menuid;
						}
					}
				}else{
					$menuid = $default_itemid;
					if($menuid == 0){
						//$active = $menus->getActive();
						//if ($active && $active->component == 'com_osproperty') {
							$menuid = $default_itemid;
						///}
					}
					if($menuid > 0){
						return $menuid;
					}else{
						return 9999;
					}
				}
			}
			
			$tempArr = array();
			for($i=0;$i<count($needs);$i++){
				$item = $needs[$i];
				$tempArr[] = '  `link` LIKE "%'.$item.'%"';
			}
			if(count($tempArr) > 0){
				$additional_sql .=" and (";
				$additional_sql .= implode(" or ",$tempArr);
				$additional_sql .= " )";
			
				if(count($needs1) > 0){
					$additional_sql .=" and (`link` LIKE '%".$needs1[0]."%')";
				}
				
				$query = $db->getQuery(true);
				$query->select('id')
					->from('#__menu')
					->where('link LIKE "%index.php?option=com_osproperty%"'.$additional_sql )
					->where('published = 1 '.$lookup_sql . $language_sql)
					->where('`access` IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')')
					->order('access');
				$db->setQuery($query);
				
				$itemId = $db->loadResult();
				
				if (intval($itemId) == 0)
				{
					$itemId = $default_itemid;
					if($itemId == 0){
						$itemId = JRequest::getInt('Itemid',0);	
					}
				}
				return $itemId;
			}
		}else{
			$itemId = $default_itemid;
			if($itemId > 0){
				return $itemId;
			}else{				
				return $default_itemid;
			}
		}
	}
	
	public static function confirmItemid($itemid, $layout){
		global $mainframe;
		$db = JFactory::getDbo();
		$language_sql = "";
		if (JLanguageMultilang::isEnabled()){
			$language = JFactory::getLanguage();
			$current_lag = $language->getTag();
			$language_sql = " and (`language` LIKE '$current_lag' or `language` LIKE '*' or `language` = '')";
		}
		$db->setQuery("Select count(id) from #__menu where published = '1' and `link` like '%$layout%' $language_sql and id = '".$itemid."'");
		$count = $db->loadResult();
		if($count > 0){
			return $itemid;
		}else{
			//return 0;
			$db->setQuery("Select fieldvalue from #__osrs_configuration where fieldname like 'default_itemid'");
			$default_itemid = $db->loadResult();
			return intval($default_itemid);
		}
	}
	
	public static function confirmItemidArr($itemid, $layoutArr){
		global $mainframe;
		$db = JFactory::getDbo();
		$language_sql = "";
		if (JLanguageMultilang::isEnabled()){
			$language = JFactory::getLanguage();
			$current_lag = $language->getTag();
			$language_sql = " and (`language` LIKE '$current_lag' or `language` LIKE '*' or `language` = '')";
		}
		$layoutSql = "";
		if(count($layoutArr) > 0){
			$tempArr = array();
			foreach ($layoutArr as $layout){
				$tempArr[] = "`link` like '%$layout%'";
			}
			$layoutSql = " and (".implode(" or ",$tempArr).")";
		}
		$db->setQuery("Select count(id) from #__menu where published = '1' $layoutSql $language_sql and id = '".$itemid."'");
		$count = $db->loadResult();
		if($count > 0){
			return $itemid;
		}else{
			//return 0;
			$db->setQuery("Select fieldvalue from #__osrs_configuration where fieldname like 'default_itemid'");
			$default_itemid = $db->loadResult();
			return intval($default_itemid);
		}
	}
	
	public static function reCheckItemid($itemid, $check){
		$jmenu = JSite::getMenu();
		$menuObj = $jmenu->getItem($itemid);
		$menuQuery = $menuObj->query;
		$task = $menuQuery['task'];
		$view = $menuQuery['view'];
		$return = false;
		foreach($check as $ch){
			if(($ch == $task) or ($ch == $view)){
				$return = true;
			}
		}
		return $return;
	}
}
?>