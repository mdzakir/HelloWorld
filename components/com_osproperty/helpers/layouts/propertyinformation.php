<div class="row-fluid">
	<div class="span12">
		<div class="componentheading">
		<?php
			printf(JText::_("OS_YOUR_PROPERTYS_HAS_BEEN_SAVED"),$property->pro_name);
		?>
		</div>
		<script language="javascript">
		function submitForm(t){
			if(t == "property_edit"){
				document.ftForm1.submit();
			}else if(t == "agent_editprofile"){
				document.ftForm2.submit();
			}
		}
		</script>
		<form method="POST" action="<?php echo JRoute::_('index.php?option=com_osproperty&task=property_edit&id='.$property->id);?>" name="ftForm1" id="ftForm1" class="form-horizontal">
			<input type="hidden" name="option" value="com_osproperty" />
			<input type="hidden" name="task" id="task" value="property_edit" />
			<input type="hidden" name="id" value="<?php echo $property->id?>" />
			<input type="hidden" name="cid[]" value="<?php echo $property->id?>" />
		</form>
		<?php
		$needs = array();
		$needs[] = "agent_editprofile";
		$needs[] = "agent_default";
		$needs[] = "aeditdetails";
		$itemid = OSPRoute::getItemid($needs);
		$itemid = OSPRoute::confirmItemidArr($itemid,$need);
		if(!OSPRoute::reCheckItemid($itemid,$needs)){
			$itemid = 9999;
		}
		?>
		<form method="POST" action="<?php echo JRoute::_('index.php?option=com_osproperty&task=agent_editprofile&Itemid='.$itemid);?>" name="ftForm2" id="ftForm2" class="form-horizontal">
			<input type="hidden" name="option" value="com_osproperty" />
			<input type="hidden" name="task" id="task" value="agent_editprofile" />
		</form>
		<div class="row-fluid">
			<div class="span12">
				<div class="btn-toolbar pull-right">
					<div class="btn-group">
						<button type="button" class="btn hasTooltip btn-info" title="<?php echo JText::_('OS_CONTINUE_EDIT_PROPERTY');?>" onclick="javascript:submitForm('property_edit');">
							<i class="osicon-edit"></i> <?php echo JText::_('OS_CONTINUE_EDIT_PROPERTY');?>
						</button>
						<button type="button" class="btn hasTooltip" title="<?php echo JText::_('OS_GOT0_MY_PROPERTIES');?>" onclick="javascript:submitForm('agent_editprofile');">
							<i class="osicon-home"></i> <?php echo JText::_('OS_GOT0_MY_PROPERTIES');?>
						</button>
					</div>
				</div>
			</div>
		</div>
			
		<table  width="100%" style="border:0px !important;">
			<tr>
				<td width="100%" style="">
					<!-- show information -->
					<table  width="100%" style="border:1px solid red;background-color:#FAEBDF; ">
						<tr>
							<td align="left" valign="top" style="padding:10px;line-height:20px;">
								<strong>
								<?php echo JText::_('OS_APPROVED')?>: 
								</strong>
								<?php
								echo ($property->approved==1)? JText::_('OS_YES'):JText::_('OS_NO');
								?>
								<BR />
								<strong>
								<?php echo JText::_('OS_PUBLSHED')?>: 
								</strong>
								<?php
								echo ($property->published==1)? JText::_('OS_YES'):JText::_('OS_NO');
								?>
								<?php
								if($property->approved == 1){
									?>
									<BR />
									<strong>
									<?php echo JText::_('OS_START_PUBLISHING')?>: 
									<?php echo $property->publish_up;?>
									</strong>
									<?php
									if($configClass['general_use_expiration_management']==1){
										?>
										<BR />
										<strong>
										<?php echo JText::_('OS_EXPIRED_TIME')?>: 
										<?php
										
										?>
										<?php echo HelperOspropertyCommon::loadTime($expired[0]->expired_time,'2');?>
										</strong>
										<?php
									}
								}
								
								if(($property->request_featured == 1) or ($property->request_featured == 2)){
									?>
									<BR />
									<strong>
										<?php 
										$link = "<a href='".JURI::root()."index.php?option=com_osproperty&task=property_upgrade&cid[]=$property->id&Itemid=".JRequest::getInt('Itemid',0)."'>".JText::_('here')."</a>";
										printf(JText::_('OS_YOU_HAVE_CHOOSE_PROPERTY_IS_FEATURE'),$link);?>
									</strong>
									<?php
								}
								?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="100%">
					<?php
					echo $pane->startPane( 'pane' );
					echo $pane->startPanel(JText::_('OS_PROPERTY_INFORMATION'),'panel1');
					?>
					<div class="row-fluid">
						<div class="span12">
							<table  width="100%" class="admintable">
								<?php
								if($property->ref != ""){
									?>
									<tr>
										<td class="key" width="30%"><?php echo JText::_('Ref #')?></td>
										<td class="thankyou_td"><?php echo $property->ref?></td>
									</tr>
									<?php
								}
								if(HelperOspropertyCommon::isCompanyAdmin()){
									?>
									<tr>
										<td class="key" width="30%"><?php echo JText::_('OS_AGENT')?></td>
										<td class="thankyou_td"><?php echo $property->agentname;?></td>
									</tr>
									<?php 
								}
								?>
								<tr>
									<td class="key" width="30%"><?php echo JText::_('OS_PROPERTY_TITLE')?></td>
									<td class="thankyou_td"><?php echo $property->pro_name?></td>
								</tr>
								<tr>
									<td class="key" width="30%"><?php echo JText::_('OS_CATEGORY')?></td>
									<td class="thankyou_td"><?php echo OSPHelper::getCategoryNamesOfProperty($property->id);//OSPHelper::getLanguageFieldValue($property,'category_name'); ?></td>
								</tr>
								<tr>
									<td class="key" width="30%"><?php echo JText::_('OS_PROPERTY_TYPE')?></td>
									<td class="thankyou_td"><?php echo OSPHelper::getLanguageFieldValue($property,'type_name'); ?></td>
								</tr>
								<?php
								if($property->isFeatured == 1){
								?>
								<tr>
									<td class="key" width="30%"><?php echo JText::_('OS_FEATURED')?></td>
									<td class="thankyou_td"><?php echo JText::_('Yes');?></td>
								</tr>
								<?php
								}
								if($property->rent_time != ""){
								?>
								<tr>
									<td class="key" width="30%"><?php echo JText::_('OS_RENT_TIME_FRAME')?></td>
									<td class="thankyou_td"><?php echo JText::_($property->rent_time);?></td>
								</tr>
								<?php
								}
								?>
								<tr>
									<td class="key" width="30%"><?php echo JText::_('OS_IS_FEATURE')?></td>
									<td class="thankyou_td"><?php echo $property->isFeatured? JText::_('OS_YES'):JText::_('OS_NO'); ?></td>
								</tr>
								<tr>
									<td class="key" width="30%"><?php echo JText::_('OS_PRICE')?></td>
									<td class="thankyou_td"><?php echo OSPHelper::generatePrice($property->curr,$property->price);
									?> 
									<?php
									if($property->rent_time != ""){
										echo " /".JText::_($property->rent_time);
									}
									?>
									</td>
								</tr>
								<?php
								/*
								if($property->price_original > 0){
								?>
								<tr>
									<td class="key" width="30%"><?php echo JText::_('OS_ORIGINAL_PRICE')?></td>
									<td class="thankyou_td"><?php echo HelperOspropertyCommon::loadCurrency($property->curr)?> <?php echo HelperOspropertyCommon::showPrice($property->price_original)?></td>
								</tr>
								<?php
								}
								*/
								if($property->price_call==1){
								?>
								<tr>
									<td class="key" width="30%"><?php echo JText::_('OS_CALL_FOR_PRICE')?></td>
									<td class="thankyou_td"><?php echo $property->price_call? JText::_('OS_YES'):JText::_('OS_NO'); ?></td>
								</tr>
								<?php
								}
								?>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_ADDRESS')?>
									</td>
									<td class="thankyou_td">
										<?php echo $property->address?>
									</td >
								</tr>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_POSTCODE')?>
									</td>
									<td class="thankyou_td">
										<?php echo $property->postcode?>
									</td>
								</tr>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_CITY')?>
									</td>
									<td class="thankyou_td">
										<?php //echo $property->city
										echo HelperOspropertyCommon::loadCityName($property->city);
										?>
									</td>
								</tr>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_COUNTRY')?>
									</td>
									<td class="thankyou_td">
										<?php echo $property->country_name; ?>
									</td>
								</tr>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_STATE')?>
									</td>
									<td class="thankyou_td">
										<div id="div_states">
										<?php echo $property->state_name; ?>
									</td>
								</tr>
								<?php
								if($property->region != ""){
								?>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_REGION')?>
									</td>
									<td class="thankyou_td">
										<?php echo $property->region?>
									</td>
								</tr>
								<?php } ?>
								<?php if($property->rooms > 0){?>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_NUMBER_ROOMS')?>
									</td>
									<td class="thankyou_td">
										<?php echo $property->rooms;?>
									</td>
								</tr>
								<?php } ?>
								<?php if($property->bath_room > 0){?>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_NUMBER_BATHROOMS')?>
									</td>
									<td class="thankyou_td">
										<?php echo OSPHelper::showBath($property->bath_room); ?>
									</td>
								</tr>
								<?php } ?>
								<?php if($property->bed_room > 0){?>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_NUMBER_BEDROOMS')?>
									</td>
									<td class="thankyou_td">
										<?php echo $property->bed_room;?>
									</td>
								</tr>
								<?php } ?>
								<?php if($property->parking != ""){?>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_PARKING')?>
									</td>
									<td class="thankyou_td">
										<?php echo $property->parking?>
									</td>
								</tr>
								<?php } ?>
								<?php if($property->square_feet != ""){?>
								<tr>
									<td class="key" width="30%">
										<?php echo OSPHelper::showSquareLabels();?>
									</td>
									<td class="thankyou_td">
										<?php echo $property->square_feet?>
									</td>
								</tr>
								<?php } ?>
								<?php if($property->lot_size != ""){?>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_LOT_SIZE')?>
									</td>
									<td class="thankyou_td">
										<?php echo OSPHelper::showLotsize($property->lot_size);?> <?php echo OSPHelper::showSquareSymbol();?>
									</td>
								</tr>
								<?php } ?>
								<?php if($property->number_of_floors > 0){?>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_NUMBER_OF_FLOORS')?>
									</td>
									<td class="thankyou_td">
										<?php echo $property->number_of_floors?>
									</td>
								</tr>
								<?php } ?>
								<tr>
									<td class="key" width="30%">
										<?php echo JText::_('OS_DESCRIPTION')?>
									</td>
									<td class="thankyou_td">
										<?php
										  echo OSPHelper::getLanguageFieldValue($property,'pro_small_desc');
										  ?>
										  <BR />
										  <?php
										  $property->pro_full_desc =  JHtml::_('content.prepare', OSPHelper::getLanguageFieldValue($property,'pro_full_desc'));
										  echo stripslashes(OSPHelper::getLanguageFieldValue($property,'pro_full_desc'));
										  ?>
									</td>
								</tr>
							</table>
						</div>
					</div>
					
					<?php
					echo $pane->endPanel();
					echo $pane->startPanel(JText::_('OS_OTHER_INFORMATION'),'panel2');
					?>
					<table  width="100%">
						<tr>
							<td width="100%">
								<?php
								if(($property->metakey != "") or ($property->metadesc != "")){
								?>
								<fieldset>
									<legend><strong><?php echo JText :: _('OS_META_INFORMATION')?></strong></legend>
									<table  width="100%" class="admintable">
										<?php
										if($property->metakey != ""){
										?>
										<tr>
											<td class="key" style="width: 110px" valign="top"> <?php echo JText::_('OS_META_KEYWORDS')?></td>
											<td> <?php echo $property->metakey?> </td>
										</tr>
										<?php
										}
										?>
										<?php
										if($property->metadesc != ""){
										?>
										<tr>
											<td class="key" style="width: 110px" valign="top"> <?php echo JText::_('OS_META_DESCRIPTION')?></td>
											<td> <?php echo $property->metadesc?> </td>
										</tr>
										<?php
										}
										?>
									</table>
								</fieldset>	
								<?php
								}								
								if(count($amenities) > 0){
								?>
								<fieldset>
									<legend><strong><?php echo JText :: _('OS_CONVENIENCE'); ?></strong></legend>
									<table  width="100%" style="border:1px solid #CCC !important;">
										<tr>
											<?php
											$j = 0;
											for($i=0;$i<count($amenities);$i++){
												if($amenities[$i]->amenities != ""){
												$j++;
												?>
												<td align="left" style="padding:5px;font-size:12px;border:1px solid #CCC !important;">
													<?php echo OSPHelper::getLanguageFieldValue($amenities[$i],'amenities');?>
												</td>
												<?php
												}
												if($j==3){
													echo "</tr><tr>";
													$j = 0;
												}
											}
											?>
										</tr>
									</table>
								</fieldset>
									<?php
								}
								if(count($groups) > 0){
									for($i=0;$i<count($groups);$i++){
										$group = $groups[$i];
										$fields = HelperOspropertyFields::getFieldsData($property->id, $group->id);
										if(count($fields) > 0){
										?>
											<fieldset>
												<legend><strong><?php echo OSPHelper::getLanguageFieldValue($group,'group_name');?></strong></legend>
												<table  width="100%" class="admintable">
												<?php
												for($j=0;$j<count($fields);$j++){
													$field = $fields[$j];
													//if(HelperOspropertyFieldsPrint::showField($field,$property->id) != ""){
													?>
													<tr>
														<td class="key" style="width: 110px">
															<?php echo $field->field_label?>
														</td>
														<td>
															<?php
															echo $field->value;
															?>
														</td>
													</tr>
													<?php
													//}
												}
												?>
												</table>
											</fieldset>
											<?php
										}
									}
								}
								?>
								</div>	
							</td>
						</tr>
					</table>
					<?php
					echo $pane->endPanel();
					echo $pane->startPanel(JText::_('OS_DOCUMENT_PHOTOS'),'panel3');
					?>
					<table  width="100%">
						<?php
						if(($property->pro_video != "") or ($property->pro_pdf != "")){
						?>
						<tr>
							<td width="100%" valign="top" style="padding:5px;">
								<strong>
									<?php echo JText::_('OS_DOCUMENT')?>
								</strong>
								<BR />
								<table  width="100%" class="admintable">
									<?php
									if($property->pro_video != ""){
									?>
									<tr>
										<td class="key" valign="top">
											<?php echo JText::_('OS_VIDEO_EMBED_CODE')?>
										</td>
										<td>
											<?php echo stripslashes($property->pro_video);?>
										</td>
									</tr>
									<?php
									}
									if($property->pro_pdf != ""){
									?>
									<tr>
										<td class="key" width="30%">
											<?php echo JText::_('OS_DOCUMENT_LINK')?>
										</td>
										<td>
											<?php echo $property->pro_pdf?>
										</td>
									</tr>
									<?php
									}
									?>
								</table>
							</td>
						</tr>
						<?php
						}
						?>
						<tr>
							<td width="100%" valign="top" style="padding:5px;">
								<strong>
									<?php echo JText::_("OS_PHOTOS")?>
								</strong>
								<?php
								if(count($photos) > 0){
									$j = 0;
									?>
									<table  width="100%" style="border:0px !important;">
										<tr style="border:0px !important;">
										<?php
										for($i=0;$i<count($photos);$i++){
											$j++;
											$photo = $photos[$i];
											?>
											<td width="25%" style="border:0px !important;" valign="top">
												<div style="display:block;padding:3px;border:0px;" id="div_<?php echo $i?>">
												<table class="admintable" style="border:0px !important;">
													<tr style="border:0px !important;">
														<td style="border:0px !important;">
															<?php
															if($photo->image != ""){
																?>
																<a href="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $property->id?>/<?php echo $photo->image?>" class="osmodal">
																<?php
																OSPHelper::showPropertyPhoto($photo->image,'thumb',$property->id,'width:120px;','img-rounded img-polaroid','');
																?>
																</a>
																<?php
															}
															?>
														</td>
													</tr>
													<tr style="border:0px !important;">
														<td style="background-color:#efefef;border:0px !important;"">
															<?php echo $photo->image_desc?>
														</td>
													</tr>
												</table>
												</div>
											</td>
											<?php
											if($j == 4){
												echo '</tr><tr style="border:0px !important;">';
												$j = 0;
											}
										}
										?>
										</tr>
									</table>
									<?php
								}
								?>
							</td>
						</tr>
					</table>
					<?php
					echo $pane->endPane();
					?>
				</td>
			</tr>
		</table>
	</div>
</div>