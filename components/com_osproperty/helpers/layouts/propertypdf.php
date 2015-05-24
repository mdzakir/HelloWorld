<style>
fieldset label, fieldset span.faux-label {
    clear: right;
}
table.admintable td.key, table.admintable td.paramlist_key {
    background-color: #F6F6F6;
    border-bottom: 1px solid #E9E9E9;
    border-right: 1px solid #E9E9E9;
    color: #666666;
    font-weight: bold;
    text-align: right;
    width: 140px;
    font-size:12px;
    padding-right:10px;
	font-family:"Times New Roman", Times, serif;
}

table.admintable th, table.admintable td {
    font-size: 12px;
	font-family:"Times New Roman", Times, serif;
}

table.admintable td {
    padding: 3px;
    font-size:12px;
    font-family:"Times New Roman", Times, serif;
}

legend {
    color: #146295;
    font-size: 12px;
    font-weight: bold;
}

div.width-20 fieldset, div.width-30 fieldset, div.width-35 fieldset, div.width-40 fieldset, div.width-45 fieldset, div.width-50 fieldset, div.width-55 fieldset, div.width-60 fieldset, div.width-65 fieldset, div.width-70 fieldset, div.width-80 fieldset, div.width-100 fieldset {
    background-color: #FFFFFF;
    padding: 5px 17px 17px;
}
fieldset {
    border: 1px solid #CCCCCC;
    margin-bottom: 10px;
    padding: 5px;
    text-align: left;
	font-family:"Times New Roman", Times, serif;
}
</style>
<page style="font-family: freeserif;">
	<table width="100%">
		<tr>
			<td width="100%" align="center" style="padding:10px;text-align:center;">
				<strong><big>
					<font size="18" style='font-size:22px;font-weight:bold;'>
					
						<?php
						if($row->ref != ""){
							?>
							<?php echo $row->ref?>, 
							<?php 
						}	
						?>
						<?php 
						
						echo strtoupper(OSPHelper::getLanguageFieldValue($row,'pro_name'));?>
						&nbsp;&nbsp;&nbsp;
						<?php 
						if($rproperty->price_call == 1){
							echo JText::_('OS_CALL_FOR_PRICE');
						}else{
							//echo OSPHelper::generatePrice($row->curr,$row->price);
							echo HelperOspropertyCommon::showPrice($row->price)." ";
							$db = JFactory::getDbo();
							$db->setQuery("Select currency_code from #__osrs_currencies where id = '$row->curr'");
							echo $db->loadResult();
							if($row->rent_time != ""){
								echo " /".JText::_($row->rent_time);
							}
						}
						?>
						
					</font></big>
				</strong>
			</td>
		</tr>
	</table>
	<?php 
	if(count($row->photo) > 0){
	$photos = $row->photo;
	$j = 0;
	?>
	<table width="700">
		<tr>
			<td align="center" width="700" colspan="4">
				<img src="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $row->id;?>/<?php echo $photos[0]->image?>" width="700" style="width:600px; !important;"/>
			</td>
		</tr>
		<tr>
		<?php
		for($i=0;$i<count($photos);$i++){
			$j++;
			$photo = $photos[$i];
			?>
			<td width="175" style="margin:10px;" ALIGN="CENTER">
		
				<?php
				if($photo->image != ""){
					$photo->image = str_replace(".JPG",".jpg",$photo->image);
					if(file_exists(JPATH_ROOT.DS."images/osproperty/properties/".$row->id."/thumb/".$photo->image)){
						?>
						<img src="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $row->id;?>/thumb/<?php echo $photo->image?>" width="170" />
						<?php
					}
				}
				?>
			</td>
			<?php
			if($j == 4){
				echo '</tr><tr>';
				$j = 0;
			}
		}
		?>
		</tr>
	</table>
	<?php
}
	?>
	
	<table cellpadding="1" cellspacing="1" width="100%" border="0">
		<tr>
			<td colspan="2" width="100%" BGCOLOR="#F9D192">
				<strong>
					<?php echo strtoupper(JText::_('OS_PROPERTY_INFORMATION'));?>:
				</strong>
			</td>
		</tr>
		<?php
			if($row->show_address == 1){
			?>
			<tr>
				<td width="30%">
					<?php echo JText::_('OS_ADDRESS')?>
				</td>
				<td>:&nbsp;<?php echo OSPHelper::generateAddress($row);?></td>
			</tr>
			<?php
			}
		?>
		<tr>
			<td class="key" width="30%" BGCOLOR="#efefef"><?php echo JText::_('OS_CATEGORY')?></td>
			<td BGCOLOR="#efefef" width="70%">:&nbsp;<?php echo OSPHelper::getCategoryNamesOfProperty($row->id); ?></td>
		</tr>
		<tr>
			<td class="key" width="30%"><?php echo JText::_('OS_PROPERTY_TYPE')?></td>
			<td >:&nbsp;<?php echo $lists['type']; ?></td>
		</tr>
		<?php 
		if($row->isFeatured == 1){
		?>
		<tr>
			<td class="key" width="30%" BGCOLOR="#efefef"><?php echo JText::_('OS_IS_FEATURE')?></td>
			<td BGCOLOR="#efefef">:&nbsp;<?php echo $row->isFeatured? JText::_('OS_YES'):JText::_('OS_NO'); ?></td>
		</tr>
		<?php } ?>
		<?php 
		if($row->isSold == 1){
		?>
		<tr>
			<td class="key" width="30%"><?php echo JText::_('OS_SOLD')?></td>
			<td >:&nbsp;<?php echo $row->soldOn;?></td>
		</tr>
		<?php } ?>
	
		<?php
		if($configClass['show_feature_group'] == 1){
	
			if($configClass['use_rooms'] == 1){
			?>
			<?php if($row->rooms > 0){?>
			<tr>
				<td class="key" width="30%" BGCOLOR="#efefef">
					<?php echo JText::_('OS_NUMBER_ROOMS')?>
				</td>
				<td BGCOLOR="#efefef">
					:&nbsp;<?php echo $row->rooms;?>
				</td>
			</tr>
			<?php } ?>
		<?php } ?>
		<?php
		if($configClass['use_bathrooms'] == 1){
		?>
			<?php if($row->bath_room > 0){?>
			<tr>
				<td class="key" width="30%">
					<?php echo JText::_('OS_NUMBER_BATHROOMS')?>
				</td>
				<td>
					:&nbsp;<?php echo OSPHelper::showBath($row->bath_room); ?>
				</td>
			</tr>
			<?php } ?>
		<?php } ?>
		
		<?php
		if($configClass['use_bedrooms'] == 1){
		?>
			<?php if($row->bed_room > 0){?>
			<tr>
				<td class="key" width="30%" BGCOLOR="#efefef">
					<?php echo JText::_('OS_NUMBER_BEDROOMS')?>
				</td>
				<td BGCOLOR="#efefef">
					:&nbsp;<?php echo $row->bed_room;?>
				</td>
			</tr>
			<?php } ?>
		<?php } ?>
		<?php
		if($configClass['use_parking'] == 1){
		?>
		<?php if($row->parking != ""){?>
		<tr>
			<td class="key" width="30%">
				<?php echo JText::_('OS_PARKING')?>
			</td>
			<td>
				:&nbsp;<?php echo $row->parking?>
			</td>
		</tr>
		<?php } ?>
		<?php } ?>
		<?php
		if(($configClass['use_squarefeet'] == 1) and ($row->square_feet > 0)){
		?>
		<tr>
			<td class="key" width="30%" BGCOLOR="#efefef">
				<?php echo OSPHelper::showSquareLabels();?>
			</td>
			<td BGCOLOR="#efefef">
				:&nbsp;<?php echo $row->square_feet?>
			</td>
		</tr>
		<?php } ?>
		<?php
		if(($configClass['use_squarefeet'] == 1) and ($row->lot_size > 0)){
		?>
		<tr>
			<td class="key" width="30%">
				<?php echo JText::_('OS_LOT_SIZE')?>
			</td>
			<td>
				:&nbsp;<?php echo OSPHelper::showLotsize($row->lot_size);?>
				<?php echo OSPHelper::showSquareSymbol();?>
			</td>
		</tr>
		<?php } ?>
		<?php
		if($configClass['use_nfloors'] == 1){
		?>
			<?php if($row->number_of_floors > 0){?>
			<tr>
				<td class="key" width="30%" BGCOLOR="#efefef">
					<?php echo JText::_('OS_NUMBER_OF_FLOORS')?>
				</td>
				<td BGCOLOR="#efefef">
					:&nbsp;<?php echo $row->number_of_floors?>
				</td>
			</tr>
			<?php } ?>
		<?php }
		}?>
	</table>
	
	<BR />
	<?php
	//}
	
	$small_desc = OSPHelper::getLanguageFieldValue($row,'pro_small_desc');
	
	if($small_desc != ""){
	?>
	<table  width="100%" border="0">
		<tr>
			<td width="100%" BGCOLOR="#F9D192">
				<strong>
					<?php echo strtoupper(JText::_('OS_PROPERTY_INFORMATION'));?>:
				</strong>
			</td>
		</tr>
		<tr>
			<td width="100%" BGCOLOR="#B4DAFE">
				<?php echo $small_desc;?>
				<?php if(OSPHelper::getLanguageFieldValue($row,'pro_full_desc') != ""){?>
				<BR />
					<?php echo strip_tags(OSPHelper::getLanguageFieldValue($row,'pro_full_desc')); 
				}
				?>
			</td>
		</tr>
	</table>
	<?php } ?>
	
	
	<?php 
	if(count($groups) > 0){
		for($i=0;$i<count($groups);$i++){
			$group = $groups[$i];
			
			$fields = $group->fields;
			//if(count($fields) > 0){
			if(HelperOspropertyFields::checkFieldData($row->id,$group->id) == 1){
				?>
				<table  width="100%" border="0">
					<tr>
						<td width="100%" BGCOLOR="#F9D192" colspan="2">
							<strong><?php echo strtoupper(OSPHelper::getLanguageFieldValue($group,'group_name'));?></strong>
						</td>
					</tr>
					<?php
					$fields = HelperOspropertyFields::getFieldsData($row->id,$group->id);
					for($j=0;$j<count($fields);$j++){
						$field = $fields[$j];
						if(HelperOspropertyFieldsPrint::showField($field,$row->id) != ""){
							if($j % 2==0){
								$bgcolor = "BGCOLOR='#efefef'";
							}else{
								$bgcolor = "BGCOLOR='#ffffff'";
							}
							?>
							<tr>
								<td width="30%" <?php echo $bgcolor;?>>
									<?php echo $field->field_label?>
								</td>
								<td width="70%" <?php echo $bgcolor;?>>:&nbsp;
									<?php
									echo $field->value;
									?>
								</td>
							</tr>
							<?php
						}
					}
					?>
							
				</table>
				<?php
			}
		}
	}
	?>
	
	<BR />
		
<?php
if($configClass['show_amenity_group'] == 1){
	if(count($amenities) > 0){
	?>
		<table  width="100%" border="0">
			<tr>
				<td width="100%" BGCOLOR="#F9D192" colspan="3">
					<strong><?php echo strtoupper(JText :: _('OS_CONVENIENCE')); ?>:</strong>
				</td>
			</tr>
			<?php
			$optionArr = array();
			$optionArr[] = JText::_('OS_GENERAL_AMENITIES');
			$optionArr[] = JText::_('OS_ACCESSIBILITY_AMENITIES');
			$optionArr[] = JText::_('OS_APPLIANCE_AMENITIES');
			$optionArr[] = JText::_('OS_COMMUNITY_AMENITIES');
			$optionArr[] = JText::_('OS_ENERGY_SAVINGS_AMENITIES');
			$optionArr[] = JText::_('OS_EXTERIOR_AMENITIES');
			$optionArr[] = JText::_('OS_INTERIOR_AMENITIES');
			$optionArr[] = JText::_('OS_LANDSCAPE_AMENITIES');
			$optionArr[] = JText::_('OS_SECURITY_AMENITIES');
			
			for($l=0;$l<count($optionArr);$l++){
				$j = 0;
				$db->setQuery("Select * from #__osrs_amenities where category_id = '".$l."'");
				$amenities = $db->loadObjectList();
				if(count($amenities) > 0){
					$j = 0;
					$k = 0;
					echo "<tr><td colspan='3'>";
					?>
					<strong><?php echo $optionArr[$l];?>:</strong>
					<?php 
					echo "</td></tr><tr>";
					for($i=0;$i<count($amenities);$i++){
						if(count($amenitylists) > 0){
							if(in_array($amenities[$i]->id,$amenitylists)){
								$j++;
								$k++;
						?>
							<td align="left" width="33%">
								<?php echo $k?>/ <?php echo OSPHelper::getLanguageFieldValue($amenities[$i],'amenities');?>
							</td>
						<?php
							}
						}else{
						?><td width="33%">&nbsp;</td>
						<?php 	break;
						}
						
						if($j==3){
							echo "</tr><tr>";
							$j = 0;
						}
					}
					echo "</tr>";
				}
			}
			?>
		</table>
		<?php
	}
}

if($configClass['show_neighborhood_group'] == 1){

	$db = JFactory::getDbo();
	$db->setQuery("Select count(id) from #__osrs_neighborhood where pid = '$row->id'");
	$count = $db->loadResult();
	if($count > 0){
		$query = "Select a.*,b.neighborhood from #__osrs_neighborhood as a"
			." inner join #__osrs_neighborhoodname as b on b.id = a.neighbor_id"
			." where a.pid = '$row->id'";
		$db->setQuery($query);
		$neighbors = $db->loadObjectList();
	?>
		<table  width="100%" border="0">
			<tr>
				<td width="100%" BGCOLOR="#F9D192" colspan="2">
					<strong><?php echo strtoupper(JText :: _('OS_NEIGHBORHOOD')); ?>:</strong>
				</td>
			</tr>
			<?php
			for($i=0;$i<count($neighbors);$i++){
				$neighbor = $neighbors[$i];
				
				if($i % 2==0){
					$bgcolor = "BGCOLOR='#efefef'";
				}else{
					$bgcolor = "BGCOLOR='#ffffff'";
				}
				?>
				<tr>
					<td width="30%" <?php echo $bgcolor;?>>
						<?php
						echo JText::_($neighbor->neighborhood);
						?>
					</td>
					<td width="70%" <?php echo $bgcolor;?>>:&nbsp;
						<?php echo $neighbor->mins?> <?php echo JText::_('OS_MINS')?> <?php echo JText::_('OS_BY')?>
						&nbsp;
						<?php
						switch ($neighbor->traffic_type){
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
						
					</td>
				</tr>
				<?php
			}
			?>
					
		</table>
		<?php
	}
}


//end field groups

//photos

if($configClass['show_agent_details'] == 1){
?>
	<table width="700" border="0">
		<tr>
			<td width="100%" BGCOLOR="#F9D192" colspan="2">
				<strong>
					<?php 
					if($row->agent->agent_type == 0){
						echo strtoupper(JText::_('OS_AGENT'));
					}else{
						echo strtoupper(JText::_('OS_OWNER'));
					}
					?>:
				</strong>
			</td>
		</tr>
		<tr>
			<td width="150" VALIGN="TOP">
				<?php
				if($configs[40]->fieldvalue == 1){
					$agent_photo = $row->agent->photo;
					$agent_photo_arr = explode(".",$agent_photo);
					$ext = $agent_photo_arr[count($agent_photo_arr)-1];
					if(($row->agent->photo != "")  and (strtolower($ext)=="jpg")){
						?>
						<img src="<?php echo JURI::root()?>images/osproperty/agent/<?php echo $row->agent->photo?>" width="150"/>
						<?php
					}else{
						?>
						<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/user.jpg" style="border:1px solid #CCC;" width="150" />
						<?php
					}
				}
				?>
			</td>
			<td width="500" VALIGN="TOP">
				<table  width="100%" border="0">
					<tr>
						<td width="30%" bgcolor='#efefef'>
							<?php echo JText::_('OS_NAME')?>
						</td>
						<td bgcolor='#efefef'>
							<strong><?php echo $row->agent->name;?></strong>
						</td>
					</tr>
					<?php
					if($configClass['show_agent_address'] == 1){
					?>
					<tr>
						<td width="30%" >
							<?php echo JText::_('OS_ADDRESS')?>
						</td>
						<td>
							<?php 
							if($row->agent->address != ""){
								echo $row->agent->address;
								if($row->agent->city > 0){
									echo ", ";
								}
							}
							if($row->agent->city > 0){
								echo HelperOspropertyCommon::loadCityName($row->agent->city);
								if($row->agent->state_name != ""){
									echo ", ";
								}
							}
							if($row->agent->state_name != ""){
								echo $row->agent->state_name;
								if(HelperOspropertyCommon::checkCountry()){
									echo ", ";
								}
							}
							if(HelperOspropertyCommon::checkCountry()){
								echo $row->agent->country_name;
							}
							?>
						</td>
					</tr>
					<?php
					}
					
					if(($row->agent->phone != "") and ($configClass['show_agent_phone'] == 1)){
					?>
					<tr>
						<td width="30%" bgcolor='#efefef'>
							<?php echo JText::_('OS_PHONE')?>
						</td>
						<td bgcolor='#efefef'>
							<?php echo $row->agent->phone;?>
						</td>
					</tr>
					<?php
					}
					if(($row->agent->mobile != "")and ($configClass['show_agent_mobile'] == 1)){
					?>
					<tr>
						<td width="30%">
							<?php echo JText::_('OS_MOBILE')?>
						</td>
						<td>
							<?php echo $row->agent->mobile;?>
						</td>
					</tr>
					<?php
					}
					?>
				</table>
			</td>
		</tr>
	</table>
<?php
}
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select("*")->from("#__osrs_property_price_history")->where("pid = '$row->id'")->order("`date` desc");
$db->setQuery($query);
$prices = $db->loadObjectList();
if(($configClass['use_property_history'] == 1) and (count($prices) > 0)){ ?>
<!-- History -->
<table width="700" border="0">
	<tr>
		<td width="100%" BGCOLOR="#F9D192" colspan="4">
			<strong>
				<?php 
				echo JText::_('OS_PROPERTY_HISTORY');
				?>:
			</strong>
		</td>
	</tr>
	
	<tr>
		<th width="25%" bgcolor="#CCC">
			<?php echo JText::_('OS_DATE');?>
		</th>
		<th width="25%" bgcolor="#CCC">
			<?php echo JText::_('OS_EVENT');?>
		</th>
		<th width="25%" bgcolor="#CCC">
			<?php echo JText::_('OS_PRICE');?>
		</th>
		<th width="25%" bgcolor="#CCC">
			<?php echo JText::_('OS_SOURCE');?>
		</th>
	</tr>

	<?php 
	foreach ($prices as $price){
		?>
		<tr>
			<td width="25%">
				<?php echo $price->date;?>
			</td>
			<td width="25%">
				<?php echo $price->event;?>
			</td>
			<td width="25%">
				<?php echo OSPHelper::generatePrice('',$price->price);?>
			</td>
			<td width="25%">
				<?php echo $price->source;?>
			</td>
		</tr>
		<?php 
	}
	?>
</table>
<?php }

						
$query = $db->getQuery(true);
$query->select("*")->from("#__osrs_property_history_tax")->where("pid = '$row->id'")->order("`tax_year` desc");
$db->setQuery($query);
$taxes = $db->loadObjectList();
if(($configClass['use_property_history'] == 1) and (count($taxes) > 0)){ ?>
<!-- tax -->

<table width="700" border="0">
	<tr>
		<td width="100%" BGCOLOR="#F9D192" colspan="5">
			<strong>
				<?php 
				echo JText::_('OS_PROPERTY_TAX');
				?>:
			</strong>
		</td>
	</tr>
	
	<tr>
		<th width="10%" bgcolor="#CCC">
			<?php echo JText::_('OS_YEAR');?>
		</th>
		<th width="15%" bgcolor="#CCC">
			<?php echo JText::_('OS_TAX');?>
		</th>
		<th width="15%" bgcolor="#CCC">
			<?php echo JText::_('OS_CHANGE');?>
		</th>
		<th width="30%" bgcolor="#CCC">
			<?php echo JText::_('OS_TAX_ASSESSMENT');?>
		</th>
		<th width="30%" bgcolor="#CCC">
			<?php echo JText::_('OS_TAX_ASSESSMENT_CHANGE');?>
		</th>
	</tr>

	<?php 
	foreach ($taxes as $tax){
		?>
		<tr>
			<td>
				<?php echo $tax->tax_year;?>
			</td>
			<td>
				<?php echo OSPHelper::generatePrice('',$tax->property_tax);?>
			</td>
			<td>
				<?php 
				if($tax->tax_change != ""){
				?>
					<?php echo $tax->tax_change;?> %
				<?php }else { ?>
					--
				<?php } ?>
			</td>
			<td>
				<?php echo OSPHelper::generatePrice('',$tax->tax_assessment);?>
			</td>
			<td>
				<?php 
				if($tax->tax_assessment_change != ""){
				?>
					<?php echo $tax->tax_assessment_change;?> %
				<?php }else { ?>
					--
				<?php } ?>
			</td>
		</tr>
		<?php 
	}
	?>
</table>
<?php }
?>
</page>