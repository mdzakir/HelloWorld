<?php
/*------------------------------------------------------------------------
# extrafields.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// no direct access
defined('_JEXEC') or die;

class HelperOspropertyFields{
	/**
	 * Show field information for searching
	 *
	 * @param unknown_type $field
	 */
	function showFieldinSearchModule($field){
		global $mainframe;
		switch ($field->field_type){
			case "textarea":
			case "text":
				HelperOspropertyFields::moduleSearchTextField($field,$inputbox_width_site);
				break;
			case "date":
				HelperOspropertyFields::moduleSearchDateField($field,$inputbox_width_site);
				break;
			case "radio":
				HelperOspropertyFields::moduleSearchRadioField($field,$inputbox_width_site);
				break;
			case "checkbox":
				HelperOspropertyFields::moduleSearchCheckboxField($field,$inputbox_width_site);
				break;
			case "singleselect":
				HelperOspropertyFields::moduleSearchSelectField($field,$inputbox_width_site);
				break;
			case "multipleselect":
				HelperOspropertyFields::moduleSearchMselectField($field,$inputbox_width_site);
				break;
		}
	}

	/**
	 * Show the text field for searching
	 *
	 * @param unknown_type $field
	 */
	function moduleSearchTextField($field,$inputbox_width_site){
		global $mainframe;
		echo "<tr><td width='30%' align='left' style='padding:3px;'>";
		echo OSPHelper::getLanguageFieldValue($field,'field_label').": ";
		echo "</td><td width='70%' style='padding:3px;'>";
		$value = JRequest::getVar($field->field_name,'');
		echo "<input type='text' class='input-small' style='width:".$inputbox_width_site."px' size='20' value='".htmlentities($value)."' name='$field->field_name'>";
		echo "</td></tr>";
	}

	/**
	 * Show date field for searching
	 *
	 * @param unknown_type $field
	 */
	function moduleSearchDateField($field){
		global $mainframe;
		echo "<tr><td width='30%' align='left' style='padding:3px;'>";
		echo OSPHelper::getLanguageFieldValue($field,'field_label').": ";
		echo "</td><td width='70%' style='padding:3px;'>";
		$value = JRequest::getVar($field->field_name,'');
		echo JHTML::_('calendar', $value, $field->field_name, $field->field_name, '%Y-%m-%d', array('class'=>'input-small', 'size'=>$inputbox_width_site));
		echo "</td></tr>";
	}



	function moduleSearchRadioField($field,$inputbox_width_site){
		echo "<tr><td width='30%' align='left' style='padding:3px;' valign='top'>";
		echo OSPHelper::getLanguageFieldValue($field,'field_label').": ";
		echo "</td><td width='70%' style='padding:3px;'>";
		//$options = $field->options;
		//$options = $field->options;
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_extra_field_options where field_id = '$field->id' order by ordering");
		$optionArr = $db->loadObjectList();
		if(count($optionArr) > 0){
			//$optionArr = explode("\n",$options);
			//remove white space in begin and end of the options of this array
			//$optionArr = HelperOspropertyCommon::stripSpaceArrayOptions($optionArr);
			//if(count($optionArr)){
			?>
			<table  width="100%">
				<?php
				$j = 0;
				$value = JRequest::getVar($field->field_name,'');
				for($i=0;$i<count($optionArr);$i++){
					echo "<tr>";
					$opt = $optionArr[$i];
					$j++;
					if($value == $opt->id){
						$checked = "checked";
					}else{
						$checked = "";
					}
					?>
					<td width="33%" align="left" style="padding:5px;">
						<input type="radio" value="<?php echo $opt->id;?>" name="<?php echo $field->field_name?>" id="<?php echo $field->field_name.$i?>" <?php echo $checked?>>&nbsp; <?php echo OSPHelper::getLanguageFieldValue($opt,'field_option');?>
					</td>
					<?php
					echo "</tr>";
				}
				?>
			</table>
			<?php
			//}
		}
		echo "</td></tr>";
	}

	function moduleSearchSelectField($field,$inputbox_width_site){
		if($field->size == 0){
			$field->size = 180;
		}
		echo "<tr><td width='30%' align='left' style='padding:3px;'  valign='top'>";
		echo OSPHelper::getLanguageFieldValue($field,'field_label').": ";
		echo "</td><td width='70%' style='padding:3px;'>";
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_extra_field_options where field_id = '$field->id' order by ordering");
		$optionArr = $db->loadObjectList();
		if(count($optionArr) > 0){
		?>
		<select name="<?php echo $field->field_name?>" id="<?php echo $field->field_name?>" style="width:<?php echo $inputbox_width_site?>px;" class="input-small" >
			<option value=""><?php echo JText::_('OS_ANY')?></option>
			<?php
			$value = JRequest::getVar($field->field_name,'');
			for($i=0;$i<count($optionArr);$i++){
				$opt = $optionArr[$i];
				if($value == $opt->id){
					$selected = "selected";
				}else{
					$selected = "";
				}
				?>
				<option value="<?php echo $opt->id?>" <?php echo $selected?>><?php echo OSPHelper::getLanguageFieldValue($opt,'field_option');?></option>
				<?php
			}
			?>
		</select>
		<?php
		}
		?>
		</td></tr>
		<?php
	}

	function moduleSearchMselectField($field,$inputbox_width_site){
		echo "<tr><td width='30%' align='left' style='padding:3px;' >";
		echo OSPHelper::getLanguageFieldValue($field,'field_label').": ";
		echo "</td><td width='70%' style='padding:3px;'>";
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_extra_field_options where field_id = '$field->id' order by ordering");
		$optionArr = $db->loadObjectList();
		if(count($optionArr) > 0){
			$value = JRequest::getVar($field->field_name);
			if($field->size >  0){
				$size = "width:".$field->size."px;";
			}else{
				$size = "";
			}
			?>
			<select name="<?php echo $field->field_name?>[]" id="<?php echo $field->field_name?>" style="<?php  echo $size?>" class="input-large" multiple>
			
			<?php

			for($i=0;$i<count($optionArr);$i++){
				$opt = $optionArr[$i];
				if(in_array($opt->id,$value)){
					$selected = "selected";
				}else{
					$selected = "";
				}
				?>
				<option value="<?php echo $opt->id?>" <?php echo $selected?>><?php echo OSPHelper::getLanguageFieldValue($opt,'field_option');?></option>
				<?php
			}
			echo "</select>";
		}
		echo "</td></tr>";
	}

	function moduleSearchCheckboxField($field,$inputbox_width_site){
		echo "<tr><td width='30%' align='left' style='padding:3px;'  valign='top'>";
		echo OSPHelper::getLanguageFieldValue($field,'field_label').": ";
		echo "</td><td width='70%' style='padding:3px;'>";
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_extra_field_options where field_id = '$field->id' order by ordering");
		$optionArr = $db->loadObjectList();
		if(count($optionArr) > 0){
			?>
			<table  width="100%">
				<?php
				$j = 0;
				$value = JRequest::getVar($field->field_name);
				for($i=0;$i<count($optionArr);$i++){
					echo "<tr>";
					$opt = $optionArr[$i];
					$j++;
					if(in_array($opt->id,$value)){
						$checked = "checked";
					}else{
						$checked = "";
					}
					?>
					<td width="33%" align="left" style="padding:5px;">
						<input type="checkbox" value="<?php echo $opt->id?>" name="<?php echo $field->field_name?>[]" id="<?php echo $field->field_name.$i?>" <?php echo $checked?>>&nbsp; <?php echo OSPHelper::getLanguageFieldValue($opt,'field_option');?>
					</td>
					<?php
					echo "</tr>";
				}
				?>
			</table>
			<?php
		}
		echo "</td></tr>";
	}
	/**
	 * check to see if the group field has the field that have data
	 *
	 * @param unknown_type $pid
	 * @param unknown_type $gid
	 * @param unknown_type $access_sql
	 */
	function checkFieldData($pid, $gid){
		global $mainframe;
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		if(intval($user->id) > 0){
			$special = HelperOspropertyCommon::checkSpecial();
			if($special){
				$access_sql = " and b.`access` in (0,1,2) ";
			}else{
				$access_sql = " and b.`access` in (0,1) ";
			}
		}else{
			$access_sql = " and b.`access` = '0' ";
		}

		$query = "Select b.id,b.field_type from #__osrs_extra_fields as b"
		." WHERE b.published = '1' AND b.group_id = '$gid' $access_sql order by b.ordering";
		$db->setQuery($query);
		$fields = $db->loadObjectList();
		$return = 0;
		if(count($fields) > 0){
			for($i=0;$i<count($fields);$i++){
				$field = $fields[$i];
				$field_type = $field->field_type;
				switch ($field_type){
					case "textarea":
					case "text":
						if(HelperOspropertyFields::checkTextFieldValue($pid,$field->id)){
							$return = 1;
						}
						break;
					case "date":
						if(HelperOspropertyFields::checkDateFieldValue($pid,$field->id)){
							$return = 1;
						}
						break;
					case "radio":
					case "singleselect":
					case "checkbox":
					case "multipleselect":
						if(HelperOspropertyFields::checkOptionsFieldValue($pid,$field->id)){
							$return = 1;
						}
						break;
				}
			}
		}else{
			$return = 0;
		}
		return $return;
	}
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $pid
	 * @param unknown_type $gid
	 */
	function getFieldsData($pid,$gid){
		global $mainframe;
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		if(intval($user->id) > 0){
			$special = HelperOspropertyCommon::checkSpecial();
			if($special){
				$access_sql = " and b.`access` in (0,1,2) ";
			}else{
				$access_sql = " and b.`access` in (0,1) ";
			}
		}else{
			$access_sql = " and b.`access` = '0' ";
		}
		$query = "Select b.* from #__osrs_extra_fields as b"
		." WHERE b.published = '1' AND b.group_id = '$gid' $access_sql order by b.ordering";
		$db->setQuery($query);
		$returnArr = array();
		$fields = $db->loadObjectList();
		if(count($fields) > 0){
			for($i=0;$i<count($fields);$i++){
				$field = $fields[$i];
				$field_type = $field->field_type;
				switch ($field_type){
					case "date":
						if(HelperOspropertyFields::checkDateFieldValue($pid,$field->id)){
							$value = HelperOspropertyFields::getDateFieldValue($pid,$field->id);
							$count = count($returnArr);
							$returnArr[$count]->id = $field->id;
							$returnArr[$count]->field_label = OSPHelper::getLanguageFieldValue($field,'field_label');
							$returnArr[$count]->displaytitle = $field->displaytitle;
							$returnArr[$count]->field_type = $field->field_type;
							$returnArr[$count]->value = $value;
							$returnArr[$count]->field_description = $field->field_description;
						}
						break;
					case "textarea":
						if(HelperOspropertyFields::checkTextareaFieldValue($pid,$field->id)){
							$value = HelperOspropertyFields::getTextFieldValue($pid,$field->id);
							$count = count($returnArr);
							$returnArr[$count]->id = $field->id;
							$returnArr[$count]->field_label = OSPHelper::getLanguageFieldValue($field,'field_label');
							$returnArr[$count]->displaytitle = $field->displaytitle;
							$returnArr[$count]->field_type = $field->field_type;
							$returnArr[$count]->value = $value;
							$returnArr[$count]->field_description = $field->field_description;
						}
						break;
					case "text":
						if(HelperOspropertyFields::checkTextFieldValue($pid,$field->id)){
							$value = HelperOspropertyFields::getTextFieldValue($pid,$field->id);
							$count = count($returnArr);
							$returnArr[$count]->id = $field->id;
							$returnArr[$count]->field_label = OSPHelper::getLanguageFieldValue($field,'field_label');
							$returnArr[$count]->displaytitle = $field->displaytitle;
							$returnArr[$count]->field_type = $field->field_type;
							$returnArr[$count]->value = $value;
							$returnArr[$count]->field_description = $field->field_description;
						}
						break;
					case "radio":
					case "singleselect":
					case "checkbox":
					case "multipleselect":
						if(HelperOspropertyFields::checkOptionsFieldValue($pid,$field->id)){
							$value = HelperOspropertyFields::getOptionsFieldValue($pid,$field->id);
							$count = count($returnArr);
							$returnArr[$count]->id = $field->id;
							$returnArr[$count]->field_label = OSPHelper::getLanguageFieldValue($field,'field_label');
							$returnArr[$count]->displaytitle = $field->displaytitle;
							$returnArr[$count]->field_type = $field->field_type;
							$returnArr[$count]->value = $value;
							$returnArr[$count]->field_description = $field->field_description;
						}
						break;
				}
			}
		}
		//print_r($returnArr);
		
		return $returnArr;
	}

	function checkDateFieldValue($pid,$fid){
		$db = JFactory::getDbo();
		$db->setQuery("Select `value_date` from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$fid'");
		$value = $db->loadResult();
		if(trim($value) == ""){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * Check field
	 *
	 * @param unknown_type $pid
	 * @param unknown_type $fid
	 * @return unknown
	 */
	function checkTextareaFieldValue($pid,$fid)	{
		$db = JFactory::getDbo();
		$db->setQuery("Select `value` from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$fid'");
		$value = $db->loadResult();
		if(trim($value) == ""){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * Check field
	 *
	 * @param unknown_type $pid
	 * @param unknown_type $fid
	 * @return unknown
	 */
	function checkTextFieldValue($pid,$fid)	{
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_extra_fields where id = '$fid'");
		$field = $db->loadObject();

		if($field->value_type == 0){
			$db->setQuery("Select `value` from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$fid'");
			$value = $db->loadResult();
			if(trim($value) == ""){
				return false;
			}else{
				return true;
			}
		}elseif($field->value_type == 1){
			$db->setQuery("Select `value_integer` from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$fid'");
			$value = $db->loadResult();
			if($value > 0){
				return true;
			}else{
				return false;
			}
		}elseif($field->value_type == 2){
			$db->setQuery("Select `value_decimal` from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$fid'");
			$value = $db->loadResult();
			if($value > 0){
				return true;
			}else{
				return false;
			}
		}
	}

	/**
	 * Check 'options' field
	 *
	 * @param unknown_type $pid
	 * @param unknown_type $fid
	 * @return unknown
	 */
	function checkOptionsFieldValue($pid,$fid){
		$db = JFactory::getDbo();
		$db->setQuery("Select count(id) from #__osrs_property_field_opt_value where pid = '$pid' and fid = '$fid'");
		$count = $db->loadResult();
		if($count > 0){
			return true;
		}else{
			return false;
		}
	}


	function getDateFieldValue($pid,$fid){
		$db = JFactory::getDbo();
		$db->setQuery("Select `value_date` from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$fid'");
		$value = $db->loadResult();
		return $value;
	}

	/**
	 * Get text field
	 *
	 * @param unknown_type $pid
	 * @param unknown_type $fid
	 * @return unknown
	 */
	function getTextFieldValue($pid,$fid){
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_extra_fields where id = '$fid'");
		$field = $db->loadObject();
		$db->setQuery("Select * from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$fid'");
		$value = $db->loadObject();
		if($value->id > 0){
			if($field->value_type == 0){
				return trim(OSPHelper::getLanguageFieldValue($value,'value'));
			}elseif($field->value_type == 1){
				return $value->value_integer;
			}elseif($field->value_type == 2){
				return $value->value_decimal;
			}
		}
	}

	/**
	 * Get options field
	 *
	 * @param unknown_type $pid
	 * @param unknown_type $fid
	 * @return unknown
	 */
	function getOptionsFieldValue($pid,$fid){
		$db = JFactory::getDbo();
		$query = "Select a.* from #__osrs_extra_field_options as a inner join #__osrs_property_field_opt_value as b on b.oid = a.id where b.pid = '$pid' and b.fid = '$fid' order by a.ordering";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if(count($rows) > 0){
			$return = array();
			for($i=0;$i<count($rows);$i++){
				$return[$i] = OSPHelper::getLanguageFieldValue($rows[$i],'field_option');
			}
			$return = implode(",",$return);
			return $return;
		}
	}


	function setFieldValue($list_detail){
		global $mainframe;
		$db = JFactory::getDbo();
		if($list_detail->field_type == 1){
			$db->setQuery("Select * from #__osrs_extra_fields where id = '$list_detail->field_id'");
			$field = $db->loadObject();
			switch ($field->field_type){
				case "date":
				case "text":
					JRequest::setVar('type_'.$field->field_name,$list_detail->search_type);
					JRequest::setVar($field->field_name,$list_detail->search_param);

					break;
				case "textarea":
				case "radio":
				case "singleselect":
					JRequest::setVar($field->field_name,$list_detail->search_param);
					break;
				case "checkbox":
				case "multipleselect":
					$search_param = explode(",",$list_detail->search_param);
					JRequest::setVar($field->field_name,$search_param);
					break;
			}
		}
	}
	/**
	 * Get field param
	 *
	 * @param unknown_type $field
	 */
	function getFieldParam($field){
		switch ($field->field_type){
			case "date":
			case "text":
				$value = JRequest::getVar($field->field_name,'');
				$field_type = $_POST['type_'.$field->field_name];
				if($field_type == ""){
					$field_type = $_GET['type_'.$field->field_name];
				}
				return $field->id.":".$field_type.":".$value;
			break;
			case "textarea":
			case "radio":
			case "singleselect":
				$value = JRequest::getVar($field->field_name,'');
				return $field->id.":".$value;
			break;
			case "checkbox":
			case "multipleselect":
				$value = JRequest::getVar($field->field_name);
				$value = implode(",",$value);
				return $field->id.":"."".":".$value;
			break;
		}
	}
	/**
	 * Build query
	 *
	 * @param unknown_type $field
	 * @return unknown
	 */
	function buildQuery($field){
		global $mainframe,$languages,$lang_suffix;
		$translatable = JLanguageMultilang::isEnabled() && count($languages);

		switch ($field->field_type){
			case "textarea":
				$value = OSPHelper::getStringRequest($field->field_name,'',''); // JRequest::getVar($field->field_name,'');
				$type = $_POST['type_'.$field->field_name];
				if($type == ""){
					$type = $_GET['type_'.$field->field_name];
				}
				$sql = "";
				if($value != ""){
					switch ($type){
						case "LIKE":
						case "NOT LIKE":
							$type_sql = "$type '$value'";
							break;
						case "LIKE %...%":
							$type_sql = str_replace("%...%","'%".$value."%'",$type);
							break;
						case "IN (...)":
						case "NOT IN (...)":
							$type_sql = str_replace("...",$value,$type);
							break;
					}
					if($type != ""){
						$sql = " a.id in (Select pro_id from #__osrs_property_field_value where field_id = '$field->id' and `value".$lang_suffix."` $type_sql')";
					}
				}
				return $sql;
				break;
			case "text":
				$type = $_POST['type_'.$field->field_name];
				if($type == ""){
					$type = $_GET['type_'.$field->field_name];
				}
				if($field->value_type == 0){
					$value = OSPHelper::getStringRequest($field->field_name,'','');
					if($value != ""){
						switch ($type){
							case "LIKE":
							case "NOT LIKE":
								$type_sql = "`value".$lang_suffix."` ".$type." '".$value."'";
								break;
							case "LIKE %...%":
								$type_sql = "`value".$lang_suffix."` ".str_replace("%...%","'%".$value."%'",$type);
								break;
							case "IN (...)":
							case "NOT IN (...)":
								$type_sql = "`value".$lang_suffix."` ".str_replace("...",$value,$type);
								break;
						}
					}
				}elseif($field->value_type == 1){
					$value = JRequest::getInt($field->field_name,-1);
					if($value >= 0){
						$type_sql = " `value_integer` ".$type." '".$value."'";
					}
				}elseif($field->value_type == 2){
					$value = JRequest::getFloat($field->field_name,-1);
					if($value >= 0){
						$type_sql = " `value_decimal` ".$type." '".$value."'";
					}
				}
				if($type != ""){
					$sql = " a.id in (Select pro_id from #__osrs_property_field_value where field_id = '$field->id' and $type_sql)";
				}
				return $sql;
			case "date":
				$type = $_POST['type_'.$field->field_name];
				if($type == ""){
					$type = $_GET['type_'.$field->field_name];
				}
				$value = OSPHelper::getStringRequest($field->field_name,'',''); //
				if($type != ""){
					$sql = " a.id in (Select pro_id from #__osrs_property_field_value where field_id = '$field->id' and `value_date` $type '$value')";
				}
				return $sql;
				break;
			case "radio":
			case "singleselect":
				$value = JRequest::getVar($field->field_name,'');
				$sql = " a.id in (Select pid from #__osrs_property_field_opt_value where fid = '$field->id' and `oid` = '$value')";
				return $sql;
				break;
			case "checkbox":
			case "multipleselect":
				$value = JRequest::getVar($field->field_name);
				if(count($value) > 0){
					$extraArr = array();
					for($i=0;$i<count($value);$i++){
						$value[$i] = "'".$value[$i]."'";
					}
					$valueSql = implode(",",$value);
					$sql = " a.id in (Select pid from #__osrs_property_field_opt_value where fid = '$field->id' and `oid` in ($valueSql) group by pid having count(pid) = '".count($value)."')";
				}
				return $sql;
				break;
		}
	}
	/**
	 * Check field for submitting data
	 *
	 * @param unknown_type $field
	 * @return unknown
	 */
	function checkField($field){
		global $mainframe;
		switch ($field->field_type){
			case "text":
			case "date":
			case "textarea":
				$value = OSPHelper::getStringRequest($field->field_name,'','');
				if($value != ""){
					return true;
				}else{
					return false;
				}
				break;
			case "radio":
			case "singleselect":
				$value = JRequest::getVar($field->field_name,'');
				if($value != ""){
					return true;
				}else{
					return false;
				}
				break;
			case "checkbox":
			case "multipleselect":
				$value = JRequest::getVar($field->field_name);
				if(count($value) > 0){
					return true;
				}else{
					return false;
				}
				break;
		}
	}

	public static function searchTypeDropdownString($fieldname){
		$optionArr = array();
		ob_start();
		$optionArr = array('LIKE','NOT LIKE','LIKE %...%','IN (...)','NOT IN (...)');
		$textArr   = array(JText::_('OS_LIKE'),JText::_('OS_NOT_LIKE'),JText::_('OS_LIKE').' %...%',JText::_('OS_IN').' (...)',JText::_('OS_NOT_IN').' (...)')
		?>
		<select name="type_<?php echo $fieldname?>" class="input-small" id="type_<?php echo $fieldname?>">
			<?php
			$type = JRequest::getVar('type_'.$fieldname,'');
			for($i=0;$i<count($optionArr);$i++){
				$op = $optionArr[$i];
				if($op == $type){
					$selected = "selected";
				}else{
					$selected = "";
				}
				?>
				<option value="<?php echo $op?>" <?php echo $selected?>><?php echo $textArr[$i]?></option>
				<?php
			}
			?>
		</select>
		<?php
		$body = ob_get_contents();
		ob_end_clean();
		return $body;
	}

	public static function searchTypeDropdownNumber($fieldname){
		$optionArr = array();
		ob_start();
		$optionArr = array('=','>','>=','<','<=','!=');
		?>
		<select name="type_<?php echo $fieldname?>" class="input-mini" id="type_<?php echo $fieldname?>">
			<?php
			$type = $_POST['type_'.$fieldname];
			if($type == ""){
				$type = $_GET['type_'.$fieldname];	
			}
			for($i=0;$i<count($optionArr);$i++){
				$op = $optionArr[$i];
				if($op == $type){
					$selected = "selected";
				}else{
					$selected = "";
				}
				?>
				<option value="<?php echo $op?>" <?php echo $selected?>><?php echo $op?></option>
				<?php
			}
			?>
		</select>
		<?php
		$body = ob_get_contents();
		ob_end_clean();
		return $body;
	}
	/**
	 * Show field information for searching
	 *
	 * @param unknown_type $field
	 */
	public static function showFieldinAdvSearch($field,$advancedpage=0){
		global $mainframe;
		switch ($field->field_type){
			case "textarea":
			case "text":
				HelperOspropertyFields::advSearchTextField($field);
				break;
			case "date":
				HelperOspropertyFields::advSearchDateField($field,$advancedpage);
				break;
			case "radio":
				HelperOspropertyFields::advSearchRadioField($field);
				break;
			case "checkbox":
				HelperOspropertyFields::advSearchCheckboxField($field,$advancedpage);
				break;
			case "singleselect":
				HelperOspropertyFields::advSearchSelectField($field);
				break;
			case "multipleselect":
				HelperOspropertyFields::advSearchMselectField($field);
				break;
		}
	}

	/**
	 * Show the text field for searching
	 *
	 * @param unknown_type $field
	 */
	function advSearchTextField($field){
		global $mainframe;
		echo "<div style='float:left;min-width:200px;'>";
		echo "<strong>".OSPHelper::getLanguageFieldValue($field,'field_label')."</strong>: ";
		echo "</div><div style='float:left;padding-bottom:10px;'>";
		if($field->value_type == 0){
			$value = OSPHelper::getStringRequest($field->field_name,'','');
			echo self::searchTypeDropdownString($field->field_name);
			echo "<input type='text' class='input-medium query-search search-query' size='20' value='".htmlentities($value)."' name='$field->field_name' id='$field->field_name'>";
		}elseif($field->value_type == 1){
			$value = JRequest::getVar($field->field_name,'');
			if($value != "" ){
				$value = (int)$value;
			}else{
				$value = "";
			}
			echo self::searchTypeDropdownNumber($field->field_name);
			echo "<input type='text' class='input-small query-search search-query' size='20' value='".$value."' name='$field->field_name' id='$field->field_name'>";
		}elseif($field->value_type == 2){
			$value = JRequest::getVar($field->field_name,'');
			if($value != "" ){
				$value = (float)$value;
			}else{
				$value = "";
			}
			echo self::searchTypeDropdownNumber($field->field_name);
			echo "<input type='text' class='input-small query-search search-query' size='20' value='".$value."' name='$field->field_name' id='$field->field_name'>";
		}

		echo "</div>";
	}

	/**
	 * Show date field for searching
	 *
	 * @param unknown_type $field
	 */
	function advSearchDateField($field,$advSearchDateField){
		global $mainframe;
		echo "<div style='float:left;min-width:200px;'>";
		echo "<strong>".OSPHelper::getLanguageFieldValue($field,'field_label')."</strong>: ";
		echo "</div><div style='float:left;'>";
		$value = JRequest::getVar($field->field_name,'');
		echo self::searchTypeDropdownNumber($field->field_name);
		echo JHTML::_('calendar', $value, $field->field_name, $field->field_name.$advSearchDateField, '%Y-%m-%d', array('class'=>'input-small', 'size'=>$field->size));
		echo "</div>";
	}



	function advSearchRadioField($field){
		echo "<div style='float:left;min-width:200px;'>";
		echo "<strong>".OSPHelper::getLanguageFieldValue($field,'field_label')."</strong>: ";
		echo "</div><div style='float:left;'>";
		//$options = $field->options;
		//$options = $field->options;
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_extra_field_options where field_id = '$field->id' order by ordering");
		$optionArr = $db->loadObjectList();
		if(count($optionArr) > 0){
			?>
			
			<select name="<?php echo $field->field_name?>" id="<?php echo $field->field_name?>" style="width:<?php echo $field->size?>px;" class="input-small" >
				<option value=""><?php echo JText::_('OS_ANY')?></option>
				<?php
				$value = JRequest::getVar($field->field_name,'');
				for($i=0;$i<count($optionArr);$i++){
					$opt = $optionArr[$i];
					$j++;
					if($value == $opt->id){
						$selected = "selected";
					}else{
						$selected = "";
					}
					?>
					<option value="<?php echo $opt->id?>" <?php echo $selected?>><?php echo OSPHelper::getLanguageFieldValue($opt,'field_option');?></option>
					<?php
				}
				?>
			</select>
			<?php
		}
		echo "</div>";
	}

	function advSearchSelectField($field){
		if($field->size == 0){
			$field->size = 180;
		}
		echo "<div style='float:left;min-width:200px;'>";
		echo "<strong>".OSPHelper::getLanguageFieldValue($field,'field_label')."</strong>: ";
		echo "</div><div style='float:left;'>";
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_extra_field_options where field_id = '$field->id' order by ordering");
		$optionArr = $db->loadObjectList();
		if(count($optionArr) > 0){
		?>
		<select name="<?php echo $field->field_name?>" id="<?php echo $field->field_name?>" style="width:<?php echo $field->size?>px;" class="input-small" >
			<option value=""><?php echo JText::_('OS_ANY')?></option>
			<?php
			$value = JRequest::getVar($field->field_name,'');
			for($i=0;$i<count($optionArr);$i++){
				$opt = $optionArr[$i];
				if($value == $opt->id){
					$selected = "selected";
				}else{
					$selected = "";
				}
				?>
				<option value="<?php echo $opt->id?>" <?php echo $selected?>><?php echo OSPHelper::getLanguageFieldValue($opt,'field_option');?></option>
				<?php
			}
			?>
		</select>
		<?php
		}
		echo "</div>";
	}

	function advSearchMselectField($field){
		echo "<div style='float:left;min-width:200px;'>";
		echo "<strong>".OSPHelper::getLanguageFieldValue($field,'field_label')."</strong>: ";
		echo "</div><div style='float:left;'>";
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_extra_field_options where field_id = '$field->id' order by ordering");
		$optionArr = $db->loadObjectList();
		if(count($optionArr) > 0){
			$value = JRequest::getVar($field->field_name);
			if($field->size >  0){
				$size = "width:".$field->size."px;";
			}else{
				$size = "";
			}
			?>
			<select name="<?php echo $field->field_name?>[]" id="<?php echo $field->field_name?>" style="<?php  echo $size?>" class="input-large" multiple>
			
			<?php

			for($i=0;$i<count($optionArr);$i++){
				$opt = $optionArr[$i];
				if(in_array($opt->id,$value)){
					$selected = "selected";
				}else{
					$selected = "";
				}
				?>
				<option value="<?php echo $opt->id?>" <?php echo $selected?>><?php echo OSPHelper::getLanguageFieldValue($opt,'field_option');?></option>
				<?php
			}
			echo "</select>";
		}
		echo "</div>";
	}

	function advSearchCheckboxField($field,$advancedsearchpage){
		echo "<div style='float:left;' class='span2'>";
		echo "<strong>".OSPHelper::getLanguageFieldValue($field,'field_label')."</strong>: ";
		if($advancedsearchpage == 1){
			//$width = "min-width:400px;";
		}else{
			//$width = "";
		}
		echo "</div><div style='float:left;".$width."' class='span9'>";
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_extra_field_options where field_id = '$field->id' order by ordering");
		$optionArr = $db->loadObjectList();
		if(count($optionArr) > 0){
			?>
			<div class="row-fluid">
				
					<?php
					$j = 0;
					$value = JRequest::getVar($field->field_name);
					for($i=0;$i<count($optionArr);$i++){
						$opt = $optionArr[$i];
						$j++;
						if(in_array($opt->id,$value)){
							$checked = "checked";
						}else{
							$checked = "";
						}
						if($advancedsearchpage == 1){
						?>
							<div class="span3" style="float:left;padding-right:10px;margin-left:0px;">
						<?php
						}else{
						?>
							<div class="row-fluid">
						<?php
						}
						?>
							<input type="checkbox" value="<?php echo $opt->id?>" name="<?php echo $field->field_name?>[]" id="<?php echo $field->field_name.$i?>" <?php echo $checked?>>&nbsp; <?php echo OSPHelper::getLanguageFieldValue($opt,'field_option');?>
						</div>
						<?php
					}
					?>
				
			</div>
			<?php
		}
		echo "</div>";
	}

	/**
	 * Show fields
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function showField($field,$pid){
		global $mainframe;
		$db = JFactory::getDBO();

		switch ($field->field_type){
			case "text":
				HelperOspropertyFields::showField_Text($field,$pid);
				break;
			case "date":
				HelperOspropertyFields::showField_Date($field,$pid);
				break;
			case "textarea":
				HelperOspropertyFields::showField_Textarea($field,$pid);
				break;
			case "radio":
				HelperOspropertyFields::showField_Radio($field,$pid);
				break;
			case "checkbox":
				HelperOspropertyFields::showField_Checkbox($field,$pid);
				break;
			case "singleselect":
				HelperOspropertyFields::showField_Singleselect($field,$pid);
				break;
			case "multipleselect":
				HelperOspropertyFields::showField_Multipleselect($field,$pid);
				break;
		}
	}

	/**
	 * Show TEXT field
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function showField_Text($field,$pid){
		global $mainframe,$languages;
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		$db = JFactory::getDBO();
		$value = "";
		if($pid > 0){
			$db->setQuery("Select * from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$field->id'");
			$obj = $db->loadObject();
		}
		if(($field->size == "0") or ($field->size == "")){
			$field->size = 20;
		}
		if(($value == "") and ($field->default_value!="")){
			$value = $field->default_value;
		}
		if($field->readonly == 1){
			$readonly = "readonly";
		}else{
			$readonly = "";
		}
		if(($field->maxlength == "0") or ($field->maxlength == "")){
			$field->maxlength = 255;
		}
		$value = stripslashes($value);

		$default_language = OSPHelper::getDefaultLanguage();
		$default_language = substr($default_language,0,2);
		if(($translatable) and ($field->value_type == 0)){
		?>
		<img src="<?php echo JURI::root(); ?>media/com_osproperty/flags/<?php echo $default_language.'.png'; ?>" />
		<?php } 
		if($field->value_type == 0){
			$class = "medium";
		}else{
			$class = "mini";
		}
		if($field->value_type == 0){
			$value = $obj->value;
		}elseif($field->value_type == 1){
			$value = $obj->value_integer;
		}elseif($field->value_type == 2){
			$value = $obj->value_decimal;
		}
		?>
		<input type="text" class="input-<?php echo $class;?>" size="<?php echo $field->size?>" value="<?php echo $value;?>" name="<?php echo $field->field_name?>" id="<?php echo $field->field_name?>" maxlength = "<?php echo $field->maxlength?>" <?php echo $readonly ?> />
		<BR />
		<?php
		if(($translatable) and ($field->value_type == 0)){
			$i = 0;
			//print_r($languages);
			//die();
			foreach ($languages as $language)
			{
				$i++;
				$sef = $language->sef;
				?>
				<img src="<?php echo JURI::root(); ?>media/com_osproperty/flags/<?php echo $sef.'.png'; ?>" />
				<input type="text" class="input-medium" size="<?php echo $field->size?>" value="<?php echo OSPHelper::getLanguageFieldValueBackend($obj,'value','_'.$sef);?>" name="<?php echo $field->field_name?>_<?php echo $sef?>" id="<?php echo $field->field_name?>_<?php echo $sef?>" maxlength = "<?php echo $field->maxlength?>" <?php echo $readonly ?> />
				<BR />
				<?php
			}
		}
		if($field->required == 1){
			echo "<span class='required'>(*)</span>";
		}
	}

	/**
	 * Show field date
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function showField_Date($field,$pid){
		global $mainframe;
		$db = JFactory::getDBO();
		$value = "";
		if($pid > 0){
			$db->setQuery("Select `value_date` from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$field->id'");
			$value = $db->loadResult();
		}
		if(($field->size == "0") or ($field->size == "")){
			$field->size = 20;
		}
		if(($value == "") and ($field->default_value!="")){
			$value = $field->default_value;
		}
		if($field->readonly == 1){
			$readonly = "readonly";
		}else{
			$readonly = "";
		}
		if(($field->maxlength == "0") or ($field->maxlength == "")){
			$field->maxlength = 19;
		}
		echo JHTML::_('calendar', $value, $field->field_name, $field->field_name, '%Y-%m-%d', array('class'=>'input-small', 'size'=>$field->size,  'maxlength'=>$field->maxlength));
		if($field->required == 1){
			echo "<span class='required'>(*)</span>";
		}
	}


	/**
	 * Show Textarea
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function showField_Textarea($field,$pid){
		global $mainframe,$languages;
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		$db = JFactory::getDBO();
		if($pid > 0){
			$db->setQuery("Select * from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$field->id'");
			//$value = $db->loadResult();
			$obj = $db->loadObject();
		}
		if(($field->ncols == "0") or ($field->ncols == "")){
			$field->ncols = 50;
		}
		if(($field->nrows == "0") or ($field->nrows == "")){
			$field->nrows = 50;
		}
		if(($value == "") and ($field->default_value!="")){
			$value = $field->default_value;
		}
		if($field->readonly == 1){
			$readonly = "readonly";
		}else{
			$readonly = "";
		}
		//$value = stripslashes($value);

		$default_language = OSPHelper::getDefaultLanguage();
		$default_language = substr($default_language,0,2);
		if($translatable){
		?>
		<img src="<?php echo JURI::root(); ?>media/com_osproperty/flags/<?php echo $default_language.'.png'; ?>" />
		<?php } ?>
		<BR />
		<!--  <textarea name="<?php echo $field->field_name?>" id="<?php echo $field->field_name?>" cols="<?php echo $field->ncols?>" rows="<?php echo $field->nrows?>" class="input-large" <?php echo $readonly?>><?php echo $obj->value?></textarea>-->
		<?php 
		$editor = JFactory::getEditor();
		echo $editor->display( $field->field_name,  OSPHelper::getLanguageFieldValueBackend($obj,'value','') , '95%', '100', '75', '10',false ) ;
		?>
		<BR /><BR />
		<?php
		if($translatable){
			$i = 0;
			foreach ($languages as $language)
			{
				$sef = $language->sef;
				?>
				<img src="<?php echo JURI::root(); ?>media/com_osproperty/flags/<?php echo $sef.'.png'; ?>" />
				<BR />
				<!--  <textarea name="<?php echo $field->field_name?>_<?php echo $sef?>" id="<?php echo $field->field_name?>_<?php echo $sef?>" cols="<?php echo $field->ncols?>" rows="<?php echo $field->nrows?>" class="input-large" <?php echo $readonly?>><?php echo OSPHelper::getLanguageFieldValueBackend($obj,'value','_'.$sef);?></textarea>-->
				<?php 
				echo $editor->display( $field->{'field_name_'.$sef},  OSPHelper::getLanguageFieldValueBackend($obj,'value','_'.$sef) , '95%', '100', '75', '10' ,false) ;
				?>
				<BR /><BR />
				<?php
			}
		}

		if($field->required == 1){
			echo "<span class='required'>(*)</span>";
		}
	}

	/**
	 * Radio button
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function showField_Radio($field,$pid){
		global $mainframe;
		$db = JFactory::getDBO();
		if($pid > 0){
			$db->setQuery("Select `oid` from #__osrs_property_field_opt_value where pid = '$pid' and fid = '$field->id'");
			$oid = $db->loadResult();
		}
		//$options = $field->options;
		$db->setQuery("Select * from #__osrs_extra_field_options where field_id = '$field->id' order by ordering");
		$options = $db->loadObjectList();
		if(count($options) > 0){
			//$optionArr = explode("\n",$options);
			//remove white space in begin and end of the options of this array
			//$optionArr = HelperOspropertyCommon::stripSpaceArrayOptions($optionArr);
			?>
			<table  width="100%">
				<tr>
					<?php
					$j = 0;
					for($i=0;$i<count($options);$i++){
						$j++;
						$opt = $options[$i];
						if($oid == $opt->id){
							$checked = "checked";
						}else{
							$checked = "";
						}
						?>
						<td width="50%" align="left" style="padding:5px;">
							<input type="radio" value="<?php echo $opt->id?>" name="<?php echo $field->field_name?>" id="<?php echo $field->field_name.$i?>" <?php echo $checked?>>&nbsp; <?php echo OSPHelper::getLanguageFieldValue($opt,'field_option');?>
						</td>
						<?php
						if($j == 2){
							echo "</tr><tr>";
							$j = 0;
						}
					}
					?>
				</tr>
			</table>
			<?php
		}
	}


	/**
	 * Checkboxes fields
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function showField_Checkbox($field,$pid){
		global $mainframe;
		$db = JFactory::getDBO();
		$valueArr = array();
		if($pid > 0){
			$db->setQuery("Select `oid` from #__osrs_property_field_opt_value where pid = '$pid' and fid = '$field->id'");
			$oids = $db->loadObjectList();
			if(count($oids) > 0){
				for($i=0;$i<count($oids);$i++){
					$count = count($valueArr);
					$valueArr[$count] = $oids[$i]->oid;
				}
			}
		}

		//$options = $field->options;
		$db->setQuery("Select * from #__osrs_extra_field_options where field_id = '$field->id' order by ordering");
		$options = $db->loadObjectList();
		if(count($options) > 0){
			//$optionArr = explode("\n",$options);
			//remove white space in begin and end of the options of this array
			//$optionArr = HelperOspropertyCommon::stripSpaceArrayOptions($optionArr);
			?>
			<table  width="100%">
				<tr>
					<?php
					$j = 0;
					for($i=0;$i<count($options);$i++){
						$j++;
						$opt = $options[$i];
						if(in_array($opt->id,$valueArr)){
							$checked = "checked";
						}else{
							$checked = "";
						}
						?>
						<td width="50%" align="left" style="padding:5px;">
							<input type="checkbox" name="<?php echo $field->field_name?>[]" id="<?php echo $field->field_name.$i?>" <?php echo $checked?> value="<?php echo $opt->id?>">&nbsp; <?php echo OSPHelper::getLanguageFieldValue($opt,'field_option');?>
						</td>
						<?php
						if($j == 2){
							echo "</tr><tr>";
							$j = 0;
						}
					}
					?>
				</tr>
			</table>
			<?php
		}
	}

	/**
	 * Single select
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function showField_Singleselect($field,$pid){
		global $mainframe;
		$db = JFactory::getDBO();
		$value = "";
		$valueArr = array();
		if($pid > 0){
			$db->setQuery("Select `oid` from #__osrs_property_field_opt_value where pid = '$pid' and fid = '$field->id'");
			$value = $db->loadResult();
		}
		if($value == ""){
			//$value = $field->default_value;
			$default_value = $field->default_value;
			$db->setQuery("Select id from #__osrs_extra_field_options where field_id = '$field->id' and field_option like '$default_value'");
			$value = $db->loadResult();
		}
		if(($field->size == "0") or ($field->size == "")){
			$field->size = 180;
		}
		//$options = $field->options;
		$db->setQuery("Select * from #__osrs_extra_field_options where field_id = '$field->id' order by ordering");
		$options = $db->loadObjectList();

		if(count($options) > 0){
			//$optionArr = explode("\n",$options);
			//remove white space in begin and end of the options of this array
			//$optionArr = HelperOspropertyCommon::stripSpaceArrayOptions($optionArr);
			//if(count($optionArr)){
			?>
			<select name="<?php echo $field->field_name?>" id="<?php echo $field->field_name?>" style="width:<?php echo $field->size?>px;" class="input-small" >
			<option value=""><?php echo JText::_('OS_SELECT_VALUE')?></option>
			<?php
			for($i=0;$i<count($options);$i++){
				$opt = $options[$i];
				if($value == $opt->id){
					$selected = "selected";
				}else{
					$selected = "";
				}
				?>
				<option value="<?php echo $opt->id?>" <?php echo $selected?>><?php echo $opt->field_option;?></option>
				<?php
			}
			echo "</select>";
			if($field->required == 1) {
                echo "<span class='required'>(*)</span>";
            }
		}
	}

	/**
	 * Multple select
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function showField_Multipleselect($field,$pid){
		global $mainframe;
		$db = JFactory::getDBO();
		$valueArr = array();
		if($pid > 0){
			$db->setQuery("Select `oid` from #__osrs_property_field_opt_value where pid = '$pid' and fid = '$field->id'");
			$oids = $db->loadObjectList();
			if(count($oids) > 0){
				for($i=0;$i<count($oids);$i++){
					$count = count($valueArr);
					$valueArr[$count] = $oids[$i]->oid;
				}
			}
		}

		if(($field->size == "0") or ($field->size == "")){
			$field->size = 180;
		}

		$db->setQuery("Select * from #__osrs_extra_field_options where field_id = '$field->id' order by ordering");
		$options = $db->loadObjectList();
		if(count($options) > 0){
			?>
			<select name="<?php echo $field->field_name?>[]" id="<?php echo $field->field_name?>" style="height:<?php echo $field->size?>px;" class="input-large" multiple>
			
			<?php
			for($i=0;$i<count($options);$i++){
				$opt = $options[$i];
				if(in_array($opt->id,$valueArr)){
					$selected = "selected";
				}else{
					$selected = "";
				}
				?>
				<option value="<?php echo $opt->id;?>" <?php echo $selected?>><?php echo $opt->field_option?></option>
				<?php
			}
			echo "</select>";
		}
	}



	/**
	 * Save the value of the extra fields
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function saveField($field,$pid){
		global $mainframe;
		switch ($field->field_type){
			case "radio":
			case "singleselect":
				HelperOspropertyFields::saveField_SingleSelect($field,$pid);
			case "date":
				HelperOspropertyFields::saveField_Date($field,$pid);
				break;
			case "text":
				HelperOspropertyFields::saveField_Text($field,$pid);
				break;
			case "textarea":
				HelperOspropertyFields::saveField_Textarea($field,$pid);
				break;
			case "multipleselect":
			case "checkbox":
				HelperOspropertyFields::saveField_Checkbox($field,$pid);
				break;
		}
	}

	/**
	 * Save field 
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function saveField_Text($field,$pid){
		global $mainframe,$languages;
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		$db = JFactory::getDBO();
		$row = &JTable::getInstance('Fieldvalue','OspropertyTable');

		if($field->value_type == 0){
			$value = OSPHelper::getStringRequest($field->field_name,'','');

			if($value != ""){
				$db->setQuery("Select count(id) from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$field->id'");
				$count = $db->loadResult();
				if($count == 0){
					$row->id = 0;
					$row->field_id = $field->id;
					$row->pro_id = $pid;
					$row->value = $value;
					if($translatable){
						foreach ($languages as $language)
						{
							$sef = $language->sef;
							$row->{'value_'.$sef} = JRequest::getVar($field->field_name.'_'.$sef,'');;
						}
					}
					$row->store();
				}else{
					$db->setQuery("Select id from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$field->id'");
					$value_id = $db->loadResult();
					$row->id = $value_id;
					$row->value = $value;
					if($translatable){
						foreach ($languages as $language)
						{
							$sef = $language->sef;
							$row->{'value_'.$sef} = JRequest::getVar($field->field_name.'_'.$sef,'');;
						}
					}
					$row->store();

				}
			}

		}elseif($field->value_type == 1){
			$value = JRequest::getInt($field->field_name,0);
			$db->setQuery("Select count(id) from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$field->id'");
			$count = $db->loadResult();
			if($count == 0){
				$row->id = 0;
				$row->field_id = $field->id;
				$row->pro_id = $pid;
				$row->value_integer = $value;

				$row->store();
			}else{
				$db->setQuery("Select id from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$field->id'");
				$value_id = $db->loadResult();
				$row->id = $value_id;
				$row->value_integer = $value;
				$row->store();
			}

		}elseif($field->value_type == 2){
			$value = JRequest::getFloat($field->field_name,0);
			$db->setQuery("Select count(id) from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$field->id'");
			$count = $db->loadResult();
			if($count == 0){
				$row->id = 0;
				$row->field_id = $field->id;
				$row->pro_id = $pid;
				$row->value_decimal = $value;

				$row->store();
			}else{
				$db->setQuery("Select id from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$field->id'");
				$value_id = $db->loadResult();
				$row->id = $value_id;
				$row->value_decimal = $value;
				$row->store();
			}
		}
	}

	/**
	 * Save field 
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function saveField_Date($field,$pid){
		global $mainframe,$languages;
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		$db = JFactory::getDBO();
		$row = &JTable::getInstance('Fieldvalue','OspropertyTable');
		$value = OSPHelper::getStringRequest($field->field_name,'','');
		if($value != ""){
			$db->setQuery("Select count(id) from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$field->id'");
			$count = $db->loadResult();
			if($count == 0){
				$row->id = 0;
				$row->field_id = $field->id;
				$row->pro_id = $pid;
				$row->value_date = $value;
				$row->store();
			}else{
				$db->setQuery("Select id from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$field->id'");
				$value_id = $db->loadResult();
				$row->id = $value_id;
				$row->value_date = $value;
				$row->store();

			}
		}
	}

	/**
	 * Save Single select field
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function saveField_SingleSelect($field,$pid){
		global $mainframe;
		$db = JFactory::getDBO();
		$value = JRequest::getVar($field->field_name,'');
		$db->setQuery("DELETE FROM #__osrs_property_field_opt_value WHERE pid = '$pid' AND fid = '$field->id'");
		$db->query();
		if($value != ""){
			$db->setQuery("INSERT INTO #__osrs_property_field_opt_value (id, pid,fid,oid) VALUES (NULL,'$pid','$field->id','$value')");
			$db->query();
		}
	}
	/**
	 * Save value of textarea field
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function saveField_Textarea($field,$pid){
		global $mainframe,$languages;
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		$db = JFactory::getDBO();
		$row = &JTable::getInstance('Fieldvalue','OspropertyTable');
		$value = $_POST[$field->field_name];
		if($value != ""){
			$db->setQuery("Select count(id) from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$field->id'");
			$count = $db->loadResult();
			if($count == 0){
				$row->id = 0;
				$row->field_id = $field->id;
				$row->pro_id = $pid;
				$row->value = $value;
				if($translatable){
					foreach ($languages as $language)
					{
						$sef = $language->sef;
						$row->{'value_'.$sef} = JRequest::getVar($field->field_name.'_'.$sef,'');;
					}
				}
				$row->store();
			}else{
				$db->setQuery("Select id from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$field->id'");
				$value_id = $db->loadResult();
				$row->id = $value_id;
				$row->value = $value;
				if($translatable){
					foreach ($languages as $language)
					{
						$sef = $language->sef;
						$row->{'value_'.$sef} = JRequest::getVar($field->field_name.'_'.$sef,'');;
					}
				}
				$row->store();
			}
		}
	}


	/**
	 * Save checkbox
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function saveField_Checkbox($field,$pid){
		global $mainframe;
		$db = JFactory::getDBO();
		$valueArr = JRequest::getVar($field->field_name);
		$db->setQuery("DELETE FROM #__osrs_property_field_opt_value WHERE pid = '$pid' AND fid = '$field->id'");
		$db->query();
		if(count($valueArr) > 0){
			for($i=0;$i<count($valueArr);$i++){
				$oid = $valueArr[$i];
				$db->setQuery("INSERT INTO #__osrs_property_field_opt_value (id, pid,fid,oid) VALUES (NULL,'$pid','$field->id','$oid')");
				$db->query();
			}
		}
	}


	/**
	 * Manage field options in the backend
	 *
	 * @param unknown_type $fid
	 */
	function manageFieldOptions($fid,$div_name,$type){
		global $mainframe,$languages;
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_extra_field_options where field_id = '$fid' order by ordering");
		$fields = $db->loadObjectList();
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		$default_language = OSPHelper::getDefaultLanguage();
		$default_language = substr($default_language,0,2);
		?>
		<table  width="100%" class="admintable" style="border:1px solid #CCC !important;">
			<tr>
				<td width="100%" colspan="2" align="left">
					<b>
						<?php echo JText::_('OS_ADD_NEW_OPT');?>
					</b>
				</td>
			</tr>
			<tr>
				<td class="key" width="30%" valign="top">
					<?php echo JText::_('OS_FIELD_OPTION')?>
				</td>
				<td width="70%">
					<?php
					if($translatable){
					?>
					<img src="<?php echo JURI::root(); ?>media/com_osproperty/flags/<?php echo $default_language.'.png'; ?>" />&nbsp;
					<?php
					}
					?>
					<input type="text" class="input-small" name="option_name_<?php echo $default_language.$type?>" id="option_name_<?php echo $default_language.$type?>" size="30"><BR />
					<?php
					$str = $default_language."|";
					if($translatable){
						$i = 0;
						foreach ($languages as $language) {
							$sef = $language->sef;
							$str .= $sef."|";
							?>
							<img src="<?php echo JURI::root(); ?>media/com_osproperty/flags/<?php echo $sef.'.png'; ?>" />&nbsp;
							<input type="text" class="input-small" name="option_name_<?php echo $sef?><?php echo $type?>" id="option_name_<?php echo $sef?><?php echo $type?>" size="30" />
							<BR />
							<?php
							$i++;
						}
					}
					$str = substr($str,0,strlen($str)-1);
					?>
					<input type="hidden" name="languages" id="languages" value="<?php echo $str?>" />
					<input type="button" class="btn btn-info" value="<?php echo JText::_('Save')?>" onclick="javascript:saveOption(<?php echo $fid?>,'<?php echo $div_name?>','<?php echo $type;?>');">
				</td>
			</tr>
		</table>
		<table  width="100%" class="adminlist">
			<thead>
				<th width="5%">
					#
				</th>
				<th width="20%">
					<?php echo JText::_('OS_OPTIONS')?> 
					<?php
					if($translatable){
					?>
					<img src="<?php echo JURI::root(); ?>media/com_osproperty/flags/<?php echo $default_language.'.png'; ?>" />
					<?php
					}
					?>
				</th>
				<?php
				if($translatable){
					$i = 0;
					foreach ($languages as $language) {
						$sef = $language->sef;
						?>
						<th width="20%">
							<?php echo JText::_('OS_OPTIONS')?> <img src="<?php echo JURI::root(); ?>media/com_osproperty/flags/<?php echo $sef.'.png'; ?>" />
						</th>
						<?php
					}
				}
				?>
				<th width="15%">
					<?php echo JText::_('OS_ORDERING')?>
				</th>
				<th width="15%">
					<?php echo JText::_('OS_SAVE_CHANGE')?>
				</th>
				<th width="10%">
					<?php echo JText::_('OS_REMOVE')?>
				</th>
			</thead>
			<tbody>
				<?php
				$k=0;
				for($i=0;$i<count($fields);$i++){
					$field = $fields[$i];
					?>
					<tr class="rows<?php echo $k?>">
						<td width="5%" align="center">
							<?php
							echo $i + 1;
							?>
						</td>
						<td align="center">
							<input type="text" class="input-small" name="option_<?php echo $default_language?><?php echo $field->id?><?php echo $type?>" id="option_<?php echo $default_language?><?php echo $field->id?><?php echo $type?>" value="<?php echo $field->field_option?>" size="30">
						</td>
						<?php
						if($translatable){
							foreach ($languages as $language) {
								$sef = $language->sef;
								?>
								<td align="center">
									<input type="text" class="input-small" name="option_<?php echo $sef?><?php echo $field->id?><?php echo $type?>" id="option_<?php echo $sef?><?php echo $field->id?><?php echo $type?>" value="<?php echo $field->{'field_option_'.$sef}?>" size="30" />
								</td>
								<?php
							}
						}
						?>
						<td align="center">
							<input type="text" class="input-mini" name="ordering_<?php echo $field->id?><?php echo $type?>" id="ordering_<?php echo $field->id?><?php echo $type?>" value="<?php echo $field->ordering?>" size="5">
						</td>
						<td align="center">
							<a href="javascript:saveChange(<?php echo $field->id?>,<?php echo $fid?>,'<?php echo $div_name?>','<?php echo $type?>');">
								<img src="<?php echo JURI::base()?>templates/hathor/images/menu/icon-16-checkin.png" border="0">
							</a>
						</td>
						<td align="center">
							<a href="javascript:removeOption(<?php echo $field->id?>,<?php echo $fid?>,'<?php echo $div_name?>','<?php echo $type?>');">
								<img src="<?php echo JURI::base()?>templates/hathor/images/menu/icon-16-delete.png" border="0">
							</a>
						</td>
					</tr>
					<?php
					$k = 1-$k;
				}
				?>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Save new option
	 *
	 * @param unknown_type $options
	 * @param unknown_type $fid
	 */
	function saveNewOption($options,$fid){
		global $mainframe;
		$db = JFactory::getDbo();
		$optionArr = explode("\n",$options);
		if(count($optionArr) > 0){
			for($i=0;$i<count($optionArr);$i++){
				$opt = $optionArr[$i];
				$opt = addslashes($opt);
				$db->setQuery("Select ordering from #__osrs_extra_field_options where field_id = '$fid' order by ordering limit 1");
				$ordering = $db->loadResult();
				$ordering = intval($ordering) + 1;
				$db->setQuery("INSERT INTO #__osrs_extra_field_options (id, field_id,field_option,ordering) VALUES (NULL,'$fid','$opt','$ordering')");
				$db->query();
			}
		}
	}
}



/**
 * class print by hungvd
 * Show the fields in print page
 *
 */
class HelperOspropertyFieldsPrint{
	/**
	 * Show fields
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function showField($field,$pid){
		global $mainframe;
		ob_start();
		//echo $field->field_type;
		switch ($field->field_type){
			case "text":
				HelperOspropertyFieldsPrint::showField_Text($field,$pid);
				break;
			case "date":
				HelperOspropertyFieldsPrint::showField_Date($field,$pid);
				break;
			case "textarea":
				HelperOspropertyFieldsPrint::showField_Textarea($field,$pid);
				break;
			case "radio":
				HelperOspropertyFieldsPrint::showField_Radio($field,$pid);
				break;
			case "checkbox":
				HelperOspropertyFieldsPrint::showField_Checkbox($field,$pid);
				break;
			case "singleselect":
				HelperOspropertyFieldsPrint::showField_Singleselect($field,$pid);
				break;
			case "multipleselect":
				HelperOspropertyFieldsPrint::showField_Multipleselect($field,$pid);
				break;
		}

		$field_value = ob_get_contents();
		ob_end_clean();
		//echo $field_value;
		return $field_value;
	}

	/**
	 * Show TEXT field
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function showField_Text($field,$pid){
		global $mainframe,$languages;
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		$db = JFactory::getDBO();
		if($pid > 0){
			$db->setQuery("Select * from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$field->id'");
			$value = $db->loadObject();
		}
		if($field->value_type == 0){
			$value = OSPHelper::getLanguageFieldValue($value,'value');
		}elseif($field->value_type == 1){
			$value = $value->value_integer;
		}elseif($field->value_type == 2){
			$value = $value->value_decimal;
		}
		echo $value;
	}

	/**
	 * Show field date
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function showField_Date($field,$pid){
		global $mainframe;
		$db = JFactory::getDBO();
		if($pid > 0){
			$db->setQuery("Select `value_date` from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$field->id'");
			$value = $db->loadResult();
		}
		if(($value == "") and ($field->default_value!="")){
			$value = $field->default_value;
		}
		echo $value;
	}


	/**
	 * Show Textarea
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function showField_Textarea($field,$pid){
		global $mainframe,$languages;
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		$db = JFactory::getDBO();
		if($pid > 0){
			$db->setQuery("Select * from #__osrs_property_field_value where pro_id = '$pid' and field_id = '$field->id'");
			$value = $db->loadObject();
		}
		$value = OSPHelper::getLanguageFieldValue($value,'value');
		echo $value;
	}

	/**
	 * Radio button
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function showField_Radio($field,$pid){
		global $mainframe;
		$db = JFactory::getDBO();
		if($pid > 0){
			$db->setQuery("Select a.* from #__osrs_extra_field_options as a inner join #__osrs_property_field_opt_value as b on b.oid = a.id where b.pid = '$pid' and b.fid = '$field->id'");
			$value = $db->loadObject();
			$value = OSPHelper::getLanguageFieldValue($value,'field_option');
		}
		echo $value;
	}


	/**
	 * Checkboxes fields
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function showField_Checkbox($field,$pid){
		global $mainframe;
		$db = JFactory::getDBO();
		$valueArr = array();
		if($pid > 0){
			$returnArr = array();
			$db->setQuery("Select a.* from #__osrs_extra_field_options as a inner join #__osrs_property_field_opt_value as b on b.oid = a.id where b.pid = '$pid' and b.fid = '$field->id'");
			$rows = $db->loadObjectList();
			if(count($rows) > 0){
				for($i=0;$i<count($rows);$i++){
					$row = $rows[$i];
					//$returnArr[$i] = $row->field_option;
					$returnArr[$i] = OSPHelper::getLanguageFieldValue($row,'field_option');
				}
			}
		}
		echo implode(",",$returnArr);
	}

	/**
	 * Single select
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function showField_Singleselect($field,$pid){
		global $mainframe;
		$db = JFactory::getDBO();
		if($pid > 0){
			$db->setQuery("Select a.* from #__osrs_extra_field_options as a inner join #__osrs_property_field_opt_value as b on b.oid = a.id where b.pid = '$pid' and b.fid = '$field->id'");
			//echo $db->getQuery();
			$value = $db->loadObject();
			
			$value = OSPHelper::getLanguageFieldValue($value,'field_option');
		}
		echo $value;
	}

	/**
	 * Multple select
	 *
	 * @param unknown_type $field
	 * @param unknown_type $pid
	 */
	function showField_Multipleselect($field,$pid){
		global $mainframe;
		$db = JFactory::getDBO();
		$valueArr = array();
		if($pid > 0){
			$returnArr = array();
			$db->setQuery("Select a.* from #__osrs_extra_field_options as a inner join #__osrs_property_field_opt_value as b on b.oid = a.id where b.pid = '$pid' and b.fid = '$field->id'");
			$rows = $db->loadObjectList();
			if(count($rows) > 0){
				for($i=0;$i<count($rows);$i++){
					$row = $rows[$i];
					$returnArr[$i] = OSPHelper::getLanguageFieldValue($row,'field_option');
				}
			}
		}
		echo implode(",",$returnArr);
	}

	/**
	 * Check field data is existing or not ?
	 *
	 * @param unknown_type $group_id
	 * @param unknown_type $pid
	 */
	function checkFieldDataExisting($group_id,$pid){
		global $mainframe;
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_extra_fields where group_id = '$group_id'");
		$fields = $db->loadObjectList();
		$hasValue = 0;
		if(count($fields) > 0){
			for($i=0;$i<count($fields);$i++){
				$field = $fields[$i];
				$db->setQuery("Select * from #__osrs_property_field_value where pro_id = '$pid' and `value` <> '' and field_id = '$field->id'");
				$value = $db->loadObject();
				if($field->value_type == 0){
					if($value != ""){
						$hasValue = 1;
					}
				}elseif($field->value_type == 1){
					if($value >= 0){
						$hasValue = 1;
					}
				}elseif($field->value_type == 2){
					if($value > 0){
						$hasValue = 1;
					}
				}
			}
		}
		if($hasValue == 1){
			return true;
		}else{
			return false;
		}
	}

}
?>