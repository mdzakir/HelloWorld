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
}

table.admintable th, table.admintable td {
    font-size: 12px;
}

table.admintable td {
    padding: 3px;
    font-size:12px;
    
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
}
</style>
<?php $db = JFactory::getDbo();?>
<table width="100%" style="border:0px !important;font-family:Arial;font-size:12px;">
	<tr style="border:0px !important;">
		<td width="50%" valign="bottom" style="text-align:left !important;padding-bottom:10px !important;padding-right: 10px;border:0px !important;border-bottom:1px solid #efefef !important;">
			<?php
			if($configClass['logo'] != ""){
				?>
				<img src="<?php echo JURI::root()?><?php echo $configClass['logo']?>" style="height:70px;" />
				<?php
			}
			?>
		</td>
		<td width="50%" valign="bottom" style="text-align:right !important;padding-bottom:10px !important;padding-right: 10px;border:0px !important;border-bottom:1px solid #efefef !important;">
			<?php
			if($configClass['general_bussiness_name'] != ""){
			?>
				<strong><?php echo $configClass['general_bussiness_name'];?></strong>
			<?php
			}
			?>
			<br />
			<?php
			if($configClass['general_bussiness_address'] != ""){
				?>
				<strong><?php echo JText::_('OS_ADDRESS');?>: </strong><?php echo $configClass['general_bussiness_address']; ?>
				<?php
			}
			?>
			&nbsp;
			<?php
			if($configClass['general_bussiness_phone'] != ""){
				?>
				<strong><?php echo JText::_('OS_PHONE');?>: </strong><?php echo $configClass['general_bussiness_phone']; ?>
				<?php
			}
			?>
			<br />
			<?php
			if($configClass['general_bussiness_email'] != ""){
				?>
				<strong><?php echo JText::_('OS_EMAIL');?>: </strong><?php echo $configClass['general_bussiness_email']; ?>
				<?php
			}
			?>
		</td>
	</tr>
	<tr style="border:0px !important;">
		<td width="100%" colspan="2" valign="middle" style="text-align:center !important;padding-bottom:10px !important;padding-top: 10px;border:0px !important;">
            <h1>
                <?php
                if($row->ref != ""){
                    ?>
                    <?php echo $row->ref?>,&nbsp;
                <?php
                }
                ?>
                <?php echo OSPHelper::getLanguageFieldValue($row,'pro_name');?>
            </h1>
			<span style="font-weight:bold;font-size:18px;text-align:center;">
				<?php
				if($row->price_call == 0){
					//echo "<BR />";
					echo OSPHelper::generatePrice($row->curr,$row->price);
					if($row->rent_time != ""){
						echo "/".JText::_($row->rent_time);
					}
				}else{
					//echo "<BR />";
					echo JText::_('OS_CALL_FOR_PRICE');
				}
				?>
			</span>
		</td>
	</tr>
	
	<tr style="border:0px !important;">
		<td width="100%" colspan="2" valign="middle" style="text-align:center !important;padding-bottom:10px !important;padding-top: 10px;border:0px !important;border-bottom:1px solid #efefef !important;">
			<?php
			if(count($row->photo) > 0){
				$photos = $row->photo;
				$j = 0;
				?>
				<table  width="100%" style="border:0px !important;">
                    <tr style="border:0px !important;">
                        <td style="width:100%;text-align:center;">
                            <?php
                            $photo = $photos[0];
                            OSPHelper::showPropertyPhoto($photo->image,'',$row->id,'max-width: 1500px;','img-rounded img-polaroid','');
                            ?>
                        </td>
                    </tr>
					<tr style="border:0px !important;">
                        <td style="width:100%;text-align:center;padding-top:10px;">
					<?php
					for($i=1;$i<count($photos);$i++){
						$j++;
						$photo = $photos[$i];
                        if($photo->image != ""){
                            OSPHelper::showPropertyPhoto($photo->image,'medium',$row->id,'width: 200px;','img-rounded img-polaroid','');
                        }
						if($j == 5){
							echo "<BR />";
							$j = 0;
						}
					}
					?>
                        </td>
					</tr>
				</table>
				<?php
			}
			?>
		</td>
	</tr>
	<tr style="border:0px !important;">
		<td width="100%" colspan="2" valign="middle" style="text-align:left !important;padding-bottom:10px !important;padding-top: 10px;border:0px !important;">
			<table width="100%">
				<tr>
					<td width="60%" valign="top">
						<table width="100%">
							<?php
							if($row->show_address == 1){
							?>
							<tr>
								<td style="border:1px solid #CCC !important;background-color:#A1F9F2;padding-top:10px;padding-bottom:10px;" colspan="2">
									<font style="font-size:16px;font-weight:bold;">
										<?php 
										if($row->address != ""){
											echo $row->address.", ";
										}
										if($row->postcode != ""){
											echo $row->postcode.", ";
										}
										?>
										<?php 
										if(HelperOspropertyCommon::loadCityName($row->city) != ""){
											//echo $row->city;
											echo HelperOspropertyCommon::loadCityName($row->city).", ";
										}
										?>
										<?php
										if($lists['states'] != ""){
											echo $lists['states'].", ";
										}
										echo $lists['country'];
										?>
									</font>
								</td>
							</tr>
							<?php } ?>
							<tr>
								<td style="border-right:1px solid #CCC !important;border-left:1px solid #CCC !important;border-top:1px solid #CCC !important;border-bottom:1px solid #CCC !important;font-size:16px;font-weight:bold;color:white;background-color:#1ED322;padding-top:10px;padding-bottom:10px;text-align:center;" width="50%">
									<?php echo OSPHelper::getCategoryNamesOfProperty($row->id); ?>
								</td>
								<td style="border-right:1px solid #CCC !important;border-top:1px solid #CCC !important;border-bottom:1px solid #CCC !important;font-size:16px;font-weight:bold;background-color:#1E55D3;color:white;padding-top:10px;padding-bottom:10px;text-align:center;" width="50%">
									<?php echo $lists['type']; ?>
								</td>
							</tr>
							<?php
							if($row->isFeatured == 1){
							?>
							<tr>
								<td style="border-right:1px solid #CCC !important;font-size:16px;font-weight:bold;background-color:#F60219;color:white;padding-top:10px;padding-bottom:10px;text-align:center;" colspan="2">
									<?php
									echo JText::_('OS_FEATURED');
									?>
								</td>
							</tr>
							<?php
							}
							?>
							<?php
							if($row->isSold == 1){
							?>
							<tr>
								<td style="border-right:1px solid #CCC !important;font-size:16px;font-weight:bold;background-color:orange;color:white;padding-top:10px;padding-bottom:10px;text-align:center;" colspan="2">
									<?php
									echo JText::_('OS_SOLD');
									echo " ".$row->soldOn;
									?>
								</td>
							</tr>
							<?php
							}
							?>
							<tr>
								<td style="font-size:12px;padding:10px;" colspan="2">
									<?php
									echo $row->pro_small_desc;
									?>
									<BR />
									<?php
									echo $row->pro_full_desc;
									
									if($configClass['show_neighborhood_group'] == 1){
										$query = "Select count(a.id) from #__osrs_neighborhood as a"
												." inner join #__osrs_neighborhoodname as b on b.id = a.neighbor_id"
												." where a.pid = '$row->id'";
										$db->setQuery($query);
										$count = $db->loadResult();
										if($count > 0){
											?>
											<BR />
											<i class="osicon-ok"></i>&nbsp;<strong><?php echo JText::_('OS_NEIGHBORHOOD')?>: </strong>
											<BR />
											<?php
											$query = "Select a.*,b.neighborhood from #__osrs_neighborhood as a"
													." inner join #__osrs_neighborhoodname as b on b.id = a.neighbor_id"
													." where a.pid = '$row->id'";
											$db->setQuery($query);
											$neighbodhoods = $db->loadObjectList();
											if(count($neighbodhoods) > 0){
												for($i=0;$i<count($neighbodhoods);$i++){
													$neighborhood = $neighbodhoods[$i];
													?>
													<?php echo JText::_($neighborhood->neighborhood)?>:
													<?php echo $neighborhood->mins?> <?php echo JText::_('OS_MINS')?> <?php echo JText::_('OS_BY')?> &nbsp;
													<?php
													switch ($neighborhood->traffic_type){
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
													echo "<BR/> ";
												}
											}
										}
									}
									if($configClass['show_agent_details'] == 1){
										if(($configClass['show_agent_image'] == 1) and ($row->agent->photo != "")){
											?>
											<BR /><BR />
											<img style="width: 70px;border:1px solid #CCC;margin:3px;" src="<?php echo JURI::root()?>images/osproperty/agent/thumbnail/<?php echo $row->agent->photo?>" />
											<?php
											if(($configClass['show_agent_email'] == 1) and ($row->agent->email != "")){
											?>
												<BR />
												<font style="font-weight:bold;color:gray;"><?php echo $row->agent->email;?></font>
											<?php
											}
											?>
											<?php
											if(($row->agent->phone != "") and ($configClass['show_agent_phone'] == 1)){
											?>
												<BR />
												<font style="font-weight:bold;color:black;font-size:18px;"><?php echo $row->agent->phone;?></font>
											<?php
											}
										}
									}
									?>
								</td>
							</tr>
						</table>
					</td>
					<td width="40%" valign="top" style="background-color:#64164A;color:white;padding:20px;">
						<center>
							<font style="color:#C8A065;font-size:16px;font-weight:bold;">
								<?php
								echo JText::_('OS_FEATURES');
								?>
							</font>
						</center>
						<?php
						if($configClass['show_feature_group'] == 1){
							if($configClass['use_rooms']){
								if($row->rooms > 0){?>
									<br />
									<i class="osicon-ok"></i>&nbsp;<?php echo JText::_('OS_NUMBER_ROOMS')?>: <?php echo $row->rooms;?>
								<?php 
								} 
							}
							
							if($configClass['use_bathrooms']){
								?>
								<?php if($row->bath_room > 0){?>
									<br />
									<i class="osicon-ok"></i>&nbsp;<?php echo JText::_('OS_NUMBER_BATHROOMS')?>: <?php echo OSPHelper::showBath($row->bath_room); ?>
								<?php
								}
							} 
							
							if($configClass['use_bedrooms']){
							?>
								<?php if($row->bed_room > 0){?>
									<BR />
									<i class="osicon-ok"></i>&nbsp;<?php echo JText::_('OS_NUMBER_BEDROOMS')?>: <?php echo $row->bed_room;?>
								<?php 
								} 
							}
								
							if($configClass['use_parking']){
								if($row->parking != ""){?>
									<BR />		
									<i class="osicon-ok"></i>&nbsp;<?php echo JText::_('OS_PARKING')?>: <?php echo $row->parking?>
								<?php 
								} 
							}
								
							if(($configClass['use_squarefeet']) and ($row->square_feet > 0)){
								if($row->square_feet != ""){?>
								<br />
								<i class="osicon-ok"></i>&nbsp;<?php echo OSPHelper::showSquareLabels();?>: <?php echo $row->square_feet?>
								<?php 
								} 
							}
							
						    if(($configClass['use_squarefeet']) and ($row->lot_size > 0)){
								if($row->square_feet != ""){?>
								<br />
								<i class="osicon-ok"></i>&nbsp;<?php echo JText::_('OS_LOT_SIZE');?>: <?php echo OSPHelper::showLotsize($row->lot_size);?> <?php echo OSPHelper::showSquareSymbol();?>
								<?php 
								} 
							}
								
							if($configClass['use_nfloors']){
								if($row->number_of_floors > 0){?>
								<BR />
								<i class="osicon-ok"></i>&nbsp;<?php echo JText::_('OS_NUMBER_OF_FLOORS')?>: <?php echo $row->number_of_floors?>
								<?php 
								} 
							}
						}
						
						if($configClass['show_amenity_group'] == 1){
							if(count($amenitylists) > 0){
								echo "<BR />";
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
								for($k=0;$k<count($optionArr);$k++){
									$j = 0;
									$db->setQuery("Select * from #__osrs_amenities where category_id = '".$k."'");
									$amenities = $db->loadObjectList();
									if(count($amenities) > 0){
										?>
										<BR />
										<i class="osicon-ok"></i>&nbsp;<strong><?php echo $optionArr[$k];?>:</strong>
										<BR />
										<?php 
										for($i=0;$i<count($amenities);$i++){
											if(count($amenitylists) > 0){
												if(in_array($amenities[$i]->id,$amenitylists)){
													$j++;
													echo OSPHelper::getLanguageFieldValue($amenities[$i],'amenities').", ";
												}
											}
										}
									}
								}
							}
						}
						
						if(count($groups) > 0){
							for($i=0;$i<count($groups);$i++){
								$group = $groups[$i];
								$fields = HelperOspropertyFields::getFieldsData($row->id, $group->id);
								if(count($fields) > 0){?>
									<BR /><BR />
									<center>
										<font style="color:#C8A065;font-size:16px;font-weight:bold;">
											<?php echo OSPHelper::getLanguageFieldValue($group,'group_name');?>
										</font>
									</center>
									<BR />
									<?php
									for($j=0;$j<count($fields);$j++){
										$field = $fields[$j];
										echo $field->field_label.": ".$field->value."<br />";
									}
								}
							}
						}
						
						$query = $db->getQuery(true);
						$query->select("*")->from("#__osrs_property_price_history")->where("pid = '$row->id'")->order("`date` desc");
						$db->setQuery($query);
						$prices = $db->loadObjectList();
						if(($configClass['use_property_history'] == 1) and (count($prices) > 0)){ ?>
						<!-- History -->
						<BR /><BR />
						<center>
							<font style="color:#C8A065;font-size:16px;font-weight:bold;">
								<?php
								echo JText::_('OS_PROPERTY_HISTORY');
								?>
							</font>
						</center>
						
						<table class="table">
							<thead>
								<tr>
									<th>
										<?php echo JText::_('OS_DATE');?>
									</th>
									<th>
										<?php echo JText::_('OS_EVENT');?>
									</th>
									<th>
										<?php echo JText::_('OS_PRICE');?>
									</th>
									<th>
										<?php echo JText::_('OS_SOURCE');?>
									</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								foreach ($prices as $price){
									?>
									<tr>
										<td>
											<?php echo $price->date;?>
										</td>
										<td>
											<?php echo $price->event;?>
										</td>
										<td>
											<?php echo OSPHelper::generatePrice('',$price->price);?>
										</td>
										<td>
											<?php echo $price->source;?>
										</td>
									</tr>
									<?php 
								}
								?>
							</tbody>
						</table>
						<?php } 
						?>
						<!-- End History -->
						<?php 
						
						$query = $db->getQuery(true);
						$query->select("*")->from("#__osrs_property_history_tax")->where("pid = '$row->id'")->order("`tax_year` desc");
						$db->setQuery($query);
						$taxes = $db->loadObjectList();
						if(($configClass['use_property_history'] == 1) and (count($taxes) > 0)){ ?>
						<!-- tax -->
						<BR /><BR />
						<center>
							<font style="color:#C8A065;font-size:16px;font-weight:bold;">
								<?php
								echo JText::_('OS_PROPERTY_TAX');
								?>
							</font>
						</center>
						
						<table class="table">
							<thead>
								<tr>
									<th>
										<?php echo JText::_('OS_YEAR');?>
									</th>
									<th>
										<?php echo JText::_('OS_TAX');?>
									</th>
									<th>
										<?php echo JText::_('OS_CHANGE');?>
									</th>
									<th>
										<?php echo JText::_('OS_TAX_ASSESSMENT');?>
									</th>
									<th>
										<?php echo JText::_('OS_TAX_ASSESSMENT_CHANGE');?>
									</th>
								</tr>
							</thead>
							<tbody>
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
							</tbody>
						</table>
						<?php }
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<script language="javascript">
window.print();
</script>