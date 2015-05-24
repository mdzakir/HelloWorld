<?php
/**
 * @version 1.5.0 2011-11-11
 * @package Joomla
 * @subpackage OS-Property
 * @copyright (C)  2011 the Ossolution
 * @license see LICENSE.php
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class modOspropertyOspropertyStatesHelper
{
	static function osGetStates($params) {
		global $mainframe;
		$lang_suffix = OSPHelper::getFieldSuffix();
        $db     = JFactory::getDBO();
        $list_type = $params->get('list_type',0);
        $country_id = $params->get('country',0);
        $hasproperties = $params->get('hasproperties',0);
        $stateIds = $params->get('stateIds','');
        
        if($list_type == 0){
	        if($country_id > 0){
	        	$sql = "";
	        	if($hasproperties == 1){
	        		$sql = "and id in (Select state from #__osrs_properties where approved = '1' and published = '1')";
	        	}
	        	$db->setQuery("Select id,state_name$lang_suffix as name from #__osrs_states where country_id = '$country_id' $sql order by state_name");
	        	$states = $db->loadObjectList();
	        	if(count($states) > 0){
	        		for($i=0;$i<count($states);$i++){
						$state = $states[$i];
						$db->setQuery("Select count(id) from #__osrs_properties where approved = '1' and published = '1' and state= '$state->id'");
						$states[$i]->nproperties = intval($db->loadResult());
	        		}
	        	}
	        	return $states;
	        }
        }else{
        	if($stateIds != ""){
        		//$stateIds = explode(",",$stateIds);
	        	$sql = "";
	        	if($hasproperties == 1){
	        		$sql = "and id in (Select city from #__osrs_properties where approved = '1' and published = '1')";
	        	}
	        	$db->setQuery("Select id,city$lang_suffix as name from #__osrs_cities where country_id = '$country_id' $sql order by city");
	        	$cities = $db->loadObjectList();
	        	if(count($cities) > 0){
	        		for($i=0;$i<count($cities);$i++){
						$city = $cities[$i];
						$db->setQuery("Select count(id) from #__osrs_properties where approved = '1' and published = '1' and city= '$city->id'");
						$cities[$i]->nproperties = intval($db->loadResult());
	        		}
	        	}
	        	return $cities;
	        }
        }
	}
}

?>