<?php
/*------------------------------------------------------------------------
# listing.html.tpl.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// No direct access.
defined('_JEXEC') or die;
echo JHTML::_('behavior.tooltip');
?>
<script language="javascript">
function loadStateInListPage(){
	var country_id = document.getElementById('country_id');
	loadStateInListPageAjax(country_id.value,"<?php echo JURI::root()?>");
}
function changeCity(state_id,city_id){
	var live_site = '<?php echo JURI::root()?>';
	loadLocationInfoCity(state_id,city_id,'state_id',live_site);
}
</script>
<div id="notice" style="display:none;">
	
</div>

<?php
$show_google_map = $params->get('show_map',1);
HelperOspropertyCommon::filterForm($lists);
?>
<div id="listings">
	<?php
	if(count($rows) > 0){
		$db = JFactory::getDbo();
		$db->setQuery("Select id as value, currency_code as text from #__osrs_currencies where id <> '$row->curr' order by currency_code");
		$currencies   = $db->loadObjectList();
		$currenyArr[] = JHTML::_('select.option','',JText::_('OS_SELECT'));
		$currenyArr   = array_merge($currenyArr,$currencies);
		?>
		<input type="hidden" name="currency_item" id="currency_item" value="" />
		<input type="hidden" name="live_site" id="live_site" value="<?php echo JURI::root()?>" />
		<div class="clearfix"></div>
		<?php
        if ($show_google_map == 1) {
			if(HelperOspropertyGoogleMap::loadMapInListing($rows)) {
                ?>
                <div id="map_canvas" style="position:relative; width: 100%; height: 300px"></div>
            <?php
            }
        }
		?>
		<div class="latestproperties latestproperties_right">
			<?php
			for($i=0;$i<count($rows);$i++){
				$row = $rows[$i];
				$needs = array();
				$needs[] = "property_details";
				$needs[] = $row->id;
				$itemid = OSPRoute::getItemid($needs);
				$lists['curr'] = JHTML::_('select.genericlist',$currenyArr,'curr'.$i,'onChange="javascript:updateCurrency('.$i.','.$row->id.',this.value)" class="input-small"','value','text');
				?>
				<div class="row-fluid">
			    	<div class="row-fluid ospitem-separator">
						<div class="span12">
							<div class="row-fluid">
								<div class="span4">
									<div id="ospitem-watermark_box">
										<a title="<?php echo JText::_('OS_PROPERTY_DETAILS');?>" href="<?php echo JRoute::_("index.php?option=com_osproperty&task=property_details&id=".$row->id."&Itemid=".$itemid)?>" class="ositem-hrefphoto">
											<img alt="<?php echo $row->pro_name?>" title="<?php echo $row->pro_name?>" src="<?php echo $row->photo?>" class="ospitem-imgborder" />
											<?php
											if($row->isFeatured == 1){
											?>
												<img alt="<?php echo JText::_('OS_FEATURED');?>" class="spotlight_watermark" src="<?php echo JURI::root()?>components/com_osproperty/images/assets/featured_medium.png">
											 <?php 
											}
											?>
										</a>
										<div class="ospitem-watermark_category">
                                        	<?php echo $row->category_name_short;?>
                                        </div>
                                        <div class="ospitem-watermark_types">
                                        	<?php echo $row->type_name;?>
	                 					</div>
									</div>
								</div>
								<div class="span8 ospitem-leftpad">
									<div class="ospitem-leftpad">
										<div class="row-fluid ospitem-toppad">
											<div class="span12">
												<span class="ospitem-propertyprice title-blue">
													<?php
													if($row->ref!=""){
														?>
														<?php echo $row->ref?>,
														<?php
													}
													?>
											       <?php echo $row->pro_name?>
											       <?php
											       if(($row->show_address == 1) and ($row->lat_add != "") and ($row->long_add != "") and ($show_google_map == 1)){
											       ?>
											       <a href="#map_canvas" onclick="javascript:openMarker(<?php echo $i;?>);"><img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/map_magnify.png" border="0" title="<?php echo JText::_('OS_CHECK_ON_THE_MAP')?>"></a>
											        <?php
											       }
						 						    $created_on = $row->created;
						 						    $modified_on = $row->modified;
						 						    $created_on = strtotime($created_on);
						 						    $modified_on = strtotime($modified_on);
						 						    if($created_on > time() - 3*24*3600){ //new
						 						    	if($configClass['show_just_add_icon'] == 1){
							 						    	?>
							 						    	<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/justadd.png" />
							 						    	<?php
						 						    	}
						 						    }elseif($modified_on > time() - 2*24*3600){
						 						    	if($configClass['show_just_update_icon'] == 1){
							 						    	?>
							 						    	<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/justupdate.png" />
							 						    	<?php
						 						    	}
						 						    }
						 						    ?>
												</span>
												<span>
												<?php 
												$sold_property_types = $configClass['sold_property_types'];
												$show_sold = 0;
												if($sold_property_types != ""){
													$sold_property_typesArr = explode("|",$sold_property_types);
													if(in_array($row->pro_type, $sold_property_typesArr)){
														$show_sold = 1;
													}
												}
												?>
												<?php if(($configClass['use_sold'] == 1) and ($row->isSold == 1) and ($show_sold == 1)){
													?>
													<span class="badge badge-warning"><strong><?php echo JText::_('OS_SOLD')?></strong></span> <?php echo JText::_('OS_ON');?>: <?php echo $row->soldOn;?>
													<?php
												}
												?>
												</span>
											</div>
										</div>
										<div class="row-fluid ospitem-toppad">
											<?php 
											if($configClass['use_open_house'] == 1){
												$span = "span6";
											}else{
												$span = "span12";
											}
											?>
											<div class="<?php echo $span;?>">
												<span class="ospitem-propertytitle">
													<span id="currency_div<?php echo $i?>">
														<?php
														if($row->price_call == 0){
															echo OSPHelper::generatePrice($row->curr,$row->price);
															if($row->rent_time != ""){
																echo " /".JText::_($row->rent_time);
															}
															if($configClass['show_convert_currency'] == 1){
															?>
															<BR />
															<span style="font-size:11px;">
															<?php echo JText::_('OS_CONVERT_CURRENCY')?>: <?php echo $lists['curr']?>
															</span>
															<?php
															}
														}else{
															echo " ".JText::_('OS_CALL_FOR_DETAILS_PRICE');
														}
														?>
													</span>
												</span>
											</div>
											<?php if($configClass['use_open_house'] == 1){
				                        	?>
				                        	<div class="span6">
				                        		<div class="clearfix"></div>
				                        		<div class="row-fluid img-polaroid inspectiontimes img-rounded">
				                        		<strong><?php echo Jtext::_('OS_OPEN_FOR_INSPECTION_TIMES')?></strong>
				                        		<div class="clearfix"></div>
				                        		<div class="span12" style="margin-left:0px;font-size:x-small;">
					                        		<?php 
					                        		if(count($row->openInformation) > 0){
					                        			foreach ($row->openInformation as $info){
						                        			?>
						                        			<?php echo JText::_('OS_FROM')?>: <?php echo date($configClass['general_date_format'],strtotime($info->start_from));?>
						                        			-
						                        			<?php echo JText::_('OS_TO')?>: <?php echo date($configClass['general_date_format'],strtotime($info->end_to));?>
						                        			<div class="clearfix"></div>
						                        			<?php
					                        			} 
					                        		}else{
					                        			echo JText::_('OS_NO_INSPECTIONS_ARE_CURRENTLY_SCHEDULED');
					                        		}
					                        		?>
				                        		</div>
				                        		</div>
				                        	</div>
				                        	<?php 
											}?>
											
										</div>
										<?php
										$addInfo = array();
										if($configClass['listing_show_nbedrooms'] == 1){
											if($row->bed_room > 0){
												$addInfo[] = $row->bed_room." ".JText::_('OS_BEDROOMS');
											}
										}
										if($configClass['listing_show_nbathrooms'] == 1){
											if($row->bath_room > 0){
												$addInfo[] = OSPHelper::showBath($row->bath_room)." ".JText::_('OS_BATHROOMS');
											}
										}
										if($configClass['listing_show_nrooms'] == 1){
											if($row->rooms > 0){
												$addInfo[] = $row->rooms." ".JText::_('OS_ROOMS');
											}
										}
										if(count($addInfo) > 0){
										?>
										<div class="row-fluid">
											<div class="span12">
												<div class="ospitem-iconbkgr">
													<span class="ospitem-leftpad"> 
														<?php
														echo implode(" | ",$addInfo);
														?>
													</span>
												</div>
											</div>
										</div>
										<?php } ?>
										<div class="row-fluid ospitem-bopad">
											<div class="span12">
												<?php 
												echo $row->pro_small_desc;
												?>
											</div>
										</div>
										<?php
										$fieldarr = $row->fieldarr;
										if(count($fieldarr) > 0){
											?>
											<div class="row-fluid ospitem-bopad">
												<div class="span12">
												<?php
												for($f=0;$f<count($fieldarr);$f++){
													$field = $fieldarr[$f];
													?>
													<p><span class="field">
													<?php
													if($field->label != ""){
														echo $field->label;
														?>
														</span> <span>:
														<?php
													}
													?>
													<?php echo $field->fieldvalue;?>
													</span></p> 
													<?php
												}
												?>
												</div>
											</div>
											<?php
										}
										?>
										<div class="row-fluid">
											<div class="span12">
												<?php
												$user = JFactory::getUser();
												$db   = JFactory::getDBO();
													
												if($configClass['show_compare_task'] == 1){
												
												$msg = JText::_('OS_DO_YOU_WANT_TO_ADD_PROPERTY_TO_COMPARE_LIST');
												$msg = str_replace("'","\'",$msg);
												?>
													
												<a onclick="javascript:osConfirm('<?php echo $msg;?>','ajax_addCompare','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" class="btn btn-warning btn-small">
													<i class="osicon-bookmark osicon-white"></i> <?php echo JText::_('OS_ADD_TO_COMPARE_LIST');?>
												</a>
													
												<?php
												}
												if(intval($user->id) > 0){
													if($configClass['property_save_to_favories'] == 1){
														//if($task != "property_favorites"){
														$db->setQuery("Select count(id) from #__osrs_favorites where user_id = '$user->id' and pro_id = '$row->id'");
														$count = $db->loadResult();
														if($count == 0){
															$msg = JText::_('OS_DO_YOU_WANT_TO_ADD_PROPERTY_TO_YOUR_FAVORITE_LISTS');	
															$msg = str_replace("'","\'",$msg);
															?>
															<a onclick="javascript:osConfirm('<?php echo $msg; ?>','ajax_addFavorites','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" class="btn btn-success btn-small">
																<i class="osicon-ok osicon-white"></i> <?php echo JText::_('OS_ADD_TO_FAVORITES');?>
															</a>
															<?php
														}
														if($count > 0){
															
															$msg = JText::_('OS_DO_YOU_WANT_TO_REMOVE_PROPERTY_OUT_OF_YOUR_FAVORITE_LISTS');	
															$msg = str_replace("'","\'",$msg);
															?>
															<a onclick="javascript:osConfirm('<?php echo $msg;?>','ajax_removeFavorites','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" class="btn btn-success btn-small">
																<i class="osicon-remove osicon-white"></i> <?php echo JText::_('OS_REMOVE_FAVORITES');?>
															</a>
															<?php
														}
													}
													if(HelperOspropertyCommon::isAgent()){
														$my_agent_id = HelperOspropertyCommon::getAgentID();
														
														if($my_agent_id == $row->agent_id){
															$link = JURI::root()."index.php?option=com_osproperty&task=property_edit&id=".$row->id;
															?>
															<span id="favorite_1">
																<a href="<?php echo $link?>" title="<?php echo JText::_('OS_EDIT_PROPERTY')?>" class="btn btn-danger btn-small">
																<i class="osicon-edit osicon-white"></i> <?php echo JText::_('OS_EDIT_PROPERTY');?>
																	
																</a>
															</span>
															<?php
														}
													}
												}
												?>
												<a title="<?php echo JText::_('OS_PROPERTY_DETAILS');?>" class="btn btn-info btn-small" href="<?php echo JRoute::_("index.php?option=com_osproperty&task=property_details&id=".$row->id."&Itemid=".$itemid)?>">
													<i class="osicon-file osicon-white"></i> <?php echo JText::_('OS_DETAILS');?>											</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php
			}
		?>	
		</div>
		<div>
            <?php
            if((count($rows) > 0) and ($pageNav->total > $pageNav->limit)){
                ?>
                <div class="pageNavdiv">
					<?php
						echo $pageNav->getListFooter();
					?>
				</div>
				<?php
			}
			?>
		</div>
	<?php
	}
	?>
</div>
