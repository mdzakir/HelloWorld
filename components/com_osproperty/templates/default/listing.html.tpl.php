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

$show_kml_export = $params->get('show_kml_export',1);
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
HelperOspropertyCommon::filterForm($lists);
?>
<div id="listings">
	<?php
	if(count($rows) > 0){
	?>
	<div class="row-fluid">
	    <div class="span12">
	        <div class="span6 pull-left">
                <a href="javascript:updateView(3)" title="<?php echo JText::_('OS_CHANGE_TO_GRID_VIEW');?>">
					<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/gridview.png" style="border:1px solid #CCC !important;padding:1px;" />
				</a>
				<a href="javascript:updateView(2)" title="<?php echo JText::_('OS_CHANGE_TO_MAP_VIEW');?>">
					<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/mapview.png" style="border:1px solid #CCC !important;padding:1px;" />
				</a>
				<?php
                if($show_kml_export == 1){
                    ?>
                    <a href="javascript:updateView(4)" title="<?php echo JText::_('OS_CHANGE_TO_GOOGLE_EARTH_KML');?>">
                        <img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/kml.png" style="border:1px solid #CCC !important;padding:1px;" />
                    </a>
                <?php
                }
                ?>
                <input type="hidden" name="listviewtype" id="listviewtype" value="<?php echo JRequest::getVar('listviewtype',$_COOKIE['viewtypecookie']); ?>"/>
                <script language="javascript">
                function updateView(view){
                    var listviewtype = document.getElementById('listviewtype');
                    listviewtype.value = view;
                    document.ftForm.submit();
                }
                </script>
            </div>
            <div class="span6 pull-right" style="text-align:right;">
                <?php
                echo JText::_('OS_RESULTS');
                echo " ";
                echo $pageNav->limitstart." - ";
                if($pageNav->total < $pageNav->limit){
                    echo $pageNav->total." ";
                }else{
                    echo $pageNav->limitstart + $pageNav->limit." ";
                }
                echo JText::_('OS_OF');
                echo " ".$pageNav->total;
                ?>
            </div>
        </div>
    </div>
	<?php
	
		$db = JFactory::getDbo();
		$db->setQuery("Select id as value, currency_code as text from #__osrs_currencies where id <> '$row->curr' order by currency_code");
		$currencies   = $db->loadObjectList();
		$currenyArr[] = JHTML::_('select.option','',JText::_('OS_SELECT'));
		$currenyArr   = array_merge($currenyArr,$currencies);
		?>
		<input type="hidden" name="currency_item" id="currency_item" value="" />
		<input type="hidden" name="live_site" id="live_site" value="<?php echo JURI::root()?>" />
		<div class="latestproperties latestproperties_right">
			<ul class="display" style="padding:0px;">
			<?php
			for($i=0;$i<count($rows);$i++){
				$row = $rows[$i];
				$needs = array();
				$needs[] = "property_details";
				$needs[] = $row->id;
				$itemid = OSPRoute::getItemid($needs);
				
				$lists['curr'] = JHTML::_('select.genericlist',$currenyArr,'curr'.$i,'onChange="javascript:updateCurrency('.$i.','.$row->id.',this.value)" class="input-small"','value','text');
				?>
				<li class="featured">
				<?php
				if($row->isFeatured == 1){
				?>
		       	 	<div class="featured_strip"><?php echo JText::_('OS_FEATURED')?></div>
		        <?php 
				}
				?>	
				
				<?php
				$width = $configClass['listing_photo_width_size'];
				if(intval($width) == 0){
					$width = 120;
				}
				?>
				<style>
				.photos_count{
					width:<?php echo $width?>px !important;
				}
				</style>
	       		<div class="row-fluid content_block">
					<div class="span4" style="margin-left:0px;">
                    	<div class="item-photo">
						<a href="<?php echo JRoute::_("index.php?option=com_osproperty&task=property_details&id=".$row->id."&Itemid=".$itemid)?>">
							<img alt="<?php echo $row->pro_name?>" title="<?php echo $row->pro_name?>" src="<?php echo $row->photo?>" class="img-polaroid" id="picture_<?php echo $row->id;?>" />
						</a>
						<?php
						if($row->count_photo > 1){
						?>
	                        <i class="property_details_photo_prev" id="property_details_photo_prev_<?php echo $row->id?>"></i>
	                        <i class="property_details_photo_next" id="property_details_photo_next_<?php echo $row->id?>"></i>
	                        <div class="property_details_photo_count">
	                            <span class="current_number" id="current_number_<?php echo $row->id?>">1</span>/<span class="total_number"><?php echo $row->count_photo;?></span>
	                        </div>
	                        <input type="hidden" name="current_picture_<?php echo $row->id?>" id="current_picture_<?php echo $row->id?>" value="1" />
	                    <?php } ?>
						<div class="clearfix"></div>
						<div style="text-align:center;margin-top:10px;margin-left:0px;" class="span12">
							<?php
								if(($configClass['show_getdirection'] == 1) and ($row->show_address== 1)){
								?>
								
									<a href="<?php echo JRoute::_("index.php?option=com_osproperty&task=direction_map&id=".$row->id)?>" title="<?php echo JText::_('OS_GET_DIRECTIONS')?>">
									<img class="png" title="<?php echo JText::_('OS_GET_DIRECTIONS')?>" src="<?php echo JURI::root()?>components/com_osproperty/images/assets/direction24.png" /></a>
								
								<?php
								}
								$user = JFactory::getUser();
								$db   = JFactory::getDBO();
								//print_r($configClass);
								if($configClass['show_compare_task'] == 1){
								?>
									
										<?php
										$msg = JText::_('OS_DO_YOU_WANT_TO_ADD_PROPERTY_TO_COMPARE_LIST');
										$msg = str_replace("'","\'",$msg);
										?>
										<a onclick="javascript:osConfirm('<?php echo $msg;?>','ajax_addCompare','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" title="<?php echo JText::_('OS_COMPARE_LISTINGS');?>">
										<img class="png" title="<?php echo JText::_('OS_ADD_TO_COMPARE_LIST')?>" alt="<?php echo JText::_('OS_ADD_TO_COMPARE_LIST')?>" src="<?php echo JURI::root()?>components/com_osproperty/images/assets/compare24.png" /></a>
									
								<?php
								}
								if(intval($user->id) > 0){
									if($configClass['property_save_to_favories'] == 1){
										if($task != "property_favorites"){
										$db->setQuery("Select count(id) from #__osrs_favorites where user_id = '$user->id' and pro_id = '$row->id'");
										$count = $db->loadResult();
										if($count == 0){
											?>
											
												<?php
												$msg = JText::_('OS_DO_YOU_WANT_TO_ADD_PROPERTY_TO_YOUR_FAVORITE_LISTS');	
												$msg = str_replace("'","\'",$msg);
												?>
												<a onclick="javascript:osConfirm('<?php echo $msg; ?>','ajax_addFavorites','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" title="<?php echo JText::_('OS_ADD_TO_FAVORITES');?>">
												<img title="<?php echo JText::_('OS_ADD_TO_FAVORITES')?>" alt="<?php echo JText::_('OS_ADD_TO_FAVORITES')?>" src="<?php echo JURI::root()?>components/com_osproperty/images/assets/save24.png" /></a>
											
											<?php
											}
										}
										if($count > 0){
											?>
											
												<?php
												$msg = JText::_('OS_DO_YOU_WANT_TO_REMOVE_PROPERTY_OUT_OF_YOUR_FAVORITE_LISTS');	
												$msg = str_replace("'","\'",$msg);
												?>
												<a onclick="javascript:osConfirm('<?php echo $msg;?>','ajax_removeFavorites','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)" title="<?php echo JText::_('OS_REMOVE_FAVORITES');?>">
												<img title="<?php echo JText::_('OS_REMOVE_PROPERTY_OUT_OF_FAVORITES_LIST')?>" alt="<?php echo JText::_('OS_REMOVE_PROPERTY_OUT_OF_FAVORITES_LIST')?>" src="<?php echo JURI::root()?>components/com_osproperty/images/assets/remove24.png" /></a>
											
											<?php
										}
									}
									if(HelperOspropertyCommon::isAgent()){
										$my_agent_id = HelperOspropertyCommon::getAgentID();
										
										if($my_agent_id == $row->agent_id){
											$link = JURI::root()."index.php?option=com_osproperty&task=property_edit&id=".$row->id;
											?>
											
												<a href="<?php echo $link?>" title="<?php echo JText::_('OS_EDIT_PROPERTY')?>">
													<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/edit24.png" border="0" width="22" />
												</a>
											
											<?php
										}
									}
								}
								?>
						</div>
                        </div>
					</div>
					<div class="content span8" style="margin-left:0px;">
						<h3 class="clearfix">
							<div class="row-fluid">
								<div class="span12" style="margin-left:0px;">
									<a href="<?php echo JRoute::_("index.php?option=com_osproperty&task=property_details&id=".$row->id."&Itemid=".$itemid)?>">
										<?php
										if($row->ref!=""){
											?>
											<?php echo $row->ref?>,
											<?php
										}
										?>
								       <?php echo $row->pro_name?>
		 						    </a>
		 						    <?php
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
								</div>
							</div>
						</h3>
						<?php
						if($configClass['listing_show_address'] == 1){
							if($row->show_address == 1){
							?>
							<p class="address">
							<?php
							echo OSPHelper::generateAddress($row);
							?>
							</p> 
							<?php
							}
						}
						if($configClass['listing_show_agent'] == 0){
							$width1 = "100%";
							$width2 = "0%";
							$align  = "center";
						}else{
							$width1 = "70%";
							$width2 = "30%";
							$align  = "center";
						}
						?>
						<div class="property_detail" style="width:<?php echo $width1?> !important;">
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
							<strong><div style="font-size:15px;padding-bottom:10px;border-bottom:1px dotted #CCC;">
							<?php
							if($configClass['listing_show_price'] == 1){
							?><?php 
								if($row->price_call == 0){
									if($row->price > 0){
										?>
										<div id="currency_div<?php echo $i?>">
											<?php
											//echo JText::_('OS_PRICE');
											//echo ": ";
											//echo HelperOspropertyCommon::loadCurrency($row->curr)." ".HelperOspropertyCommon::showPrice($row->price)." ";
											echo OSPHelper::generatePrice($row->curr,$row->price);
											//convert currency
											if($configClass['show_convert_currency'] == 1){
											?>
											<BR />
											<span style="font-size:11px;">
											<?php echo JText::_('OS_CONVERT_CURRENCY')?>: <?php echo $lists['curr']?>
											</span>
											<?php } ?>
										</div>
										<?php
									}
								}else{
									echo JText::_('OS_CALL_FOR_PRICE');
								}
							}
							?>
							</div>
							</strong> 
							
							<div class="row-fluid min_height_20">
								<div class="info4 min_height_20" style="margin-left:0px;">
									<?php echo JText::_('OS_PROPERTY_TYPE')?>
								</div>
								<div class="info8 min_height_20" style="margin-left:0px;">
									:&nbsp;<?php echo $row->type_name; ?>
								</div>
							</div>
							<div class="clearfix"></div>
							
							<div class="row-fluid min_height_20">
								<div class="info4 min_height_20" style="margin-left:0px;">
									<?php echo JText::_('OS_CATEGORY')?>
								</div>
								<div class="info8 min_height_20" style="margin-left:0px;">
									:&nbsp;<?php echo $row->category_name; ?>
								</div>
							</div>
							<div class="clearfix"></div>
							
							<!-- show custom field data -->
							<?php
							$fieldarr = $row->fieldarr;
							if(count($fieldarr) > 0){
								for($f=0;$f<count($fieldarr);$f++){
									$field = $fieldarr[$f];
									?>
									<div class="row-fluid min_height_20">
										<div class="info4 min_height_20" style="margin-left:0px;">
											<?php
											//if($field->label != ""){
											echo $field->label;
											?>
										</div>
										<div class="info8 min_height_20" style="margin-left:0px;">
											<?php
											//}
											?>
											<?php echo $field->fieldvalue;?>
										</div>
									</div>
									<div class="clearfix"></div>
									<?php
								}
							}
							?>
							
							<?php
							if(($configClass['listing_show_nbathrooms'] == 1) and ($row->bath_room > 0)){
							?>
                            <div class="row-fluid min_height_20">
								<div class="info4 min_height_20" style="margin-left:0px;">
									<?php echo JText::_('OS_BATHROOMS')?>
								</div>
								<div class="info8 min_height_20" style="margin-left:0px;">
									:&nbsp;<?php echo OSPHelper::showBath($row->bath_room); ?>
								</div>
							</div>
							<div class="clearfix"></div> 
                            <?php
							}
                            ?>
                            <?php
							if(($configClass['listing_show_nbedrooms'] == 1) and ($row->bed_room > 0)){
							?>
                            
                            <div class="row-fluid min_height_20">
								<div class="info4 min_height_20" style="margin-left:0px;">
									<?php echo JText::_('OS_BEDROOMS')?>
								</div>
								<div class="info8 min_height_20" style="margin-left:0px;">
									:&nbsp;<?php echo $row->bed_room; ?>
								</div>
							</div>
							<div class="clearfix"></div>  
                            <?php
							}
                            ?>
                            <?php
							if(($configClass['listing_show_nrooms'] == 1) and ($row->rooms > 0)){
							?>
                            
                            <div class="row-fluid min_height_20">
								<div class="info4 min_height_20" style="margin-left:0px;">
									<?php echo JText::_('OS_ROOMS')?>
								</div>
								<div class="info8 min_height_20" style="margin-left:0px;">
									:&nbsp;<?php echo $row->rooms; ?>
								</div>
							</div>
							<div class="clearfix"></div>  
                            <?php
							}
                            ?>
                            
                            <?php if($configClass['use_open_house'] == 1){
                        		?>
                        		<div class="clearfix"></div>
                        		<div class="row-fluid img-polaroid">
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
                        		<?php 
							}?>
                        	
                        </div>
                        <div class="property_detail hidden-phone" style="vertical-align:center;text-align:<?php echo $align?>;width:<?php echo $width2?> !important;">
                        	<?php
							if($configClass['listing_show_agent'] == 1){
							?>
                        	<?php
                        	if($row->agent_photo != ""){
                        		if(file_exists(JPATH_ROOT.DS."images".DS."osproperty".DS."agent".DS."thumbnail".DS.$row->agent_photo)){
	                        		?>
	                        		<img src="<?php echo JURI::root()?>images/osproperty/agent/thumbnail/<?php echo $row->agent_photo?>" style="border:1px solid #efefef;padding:3px;height:100px;" />
	                        		<?php
								}else{
									?>
									<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/noimage.png" style="border:1px solid #efefef;padding:3px;height:100px;" />
									<?php
								}
                        	}else{
                        		?>
                        		<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/noimage.png" style="border:1px solid #efefef;padding:3px;height:100px;" />
                        		<?php
                        	}
                        	?>
                        	
                        	<BR />
                            <a title="<?php echo $row->agent_name?>" href="<?php echo JRoute::_('index.php?option=com_osproperty&task=agent_info&id='.$row->agent_id.'&Itemid='.JRequest::getInt('Itemid',0));?>">
								<?php echo $row->agent_name?>
							</a>
							<?php
							}
							?>
                        </div>
	                    <p class="propertylistinglinks">
	                    	<?php
							echo $row->other_information;
							?>
                        <span>
                            <a href="<?php echo JRoute::_("index.php?option=com_osproperty&task=property_details&id=".$row->id."&Itemid=".$itemid)?>"><?php echo JText::_('OS_VIEW_MORE_DETAILS')?> »</a>
                        </span>
	                    </p> 
						</div>
					</div>
				</li>
				<?php
			}
			?>
			</ul>
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
<script language="javascript">
<?php
foreach ($rows as $row){
	if($row->count_photo > 1){
		?>
		var pictureArr<?php echo $row->id?> = new Array();
		<?php
		$photoArr = $row->photoArr;
		$j = 0;
		foreach ($photoArr as $photo){
			?>
			pictureArr<?php echo $row->id?>[<?php echo $j?>] = "<?php echo $photo;?>";
			<?php
			$j++;
		}
		?>
		jQuery( "#property_details_photo_prev_" + <?php echo $row->id?> ).click(function() {
			var current_item = document.getElementById("current_picture_" + <?php echo $row->id?>).value;
			if(current_item <= 1){
				current_item = <?php echo $row->count_photo;?>;
			}else{
				current_item--;
			}
		
			jQuery( "#picture_" + <?php echo $row->id?> ).attr("src","<?php echo JURI::root()?>images/osproperty/properties/<?php echo $row->id?>/medium/" + pictureArr<?php echo $row->id?>[current_item - 1]);
			document.getElementById("current_picture_" +  <?php echo $row->id?>).value    = current_item;
			document.getElementById("current_number_" +  <?php echo $row->id?>).innerHTML = current_item;
		});
		
		jQuery( "#property_details_photo_next_" + <?php echo $row->id?> ).click(function() {
			var current_item = document.getElementById("current_picture_" + <?php echo $row->id?>).value;
			if(current_item >= <?php echo $row->count_photo;?>){
				current_item = 1;
			}else{
				current_item++;
			}
		
			jQuery( "#picture_" + <?php echo $row->id?> ).attr("src","<?php echo JURI::root()?>images/osproperty/properties/<?php echo $row->id?>/medium/" + pictureArr<?php echo $row->id?>[current_item - 1]);
			document.getElementById("current_picture_" +  <?php echo $row->id?>).value    = current_item;
			document.getElementById("current_number_" +  <?php echo $row->id?>).innerHTML = current_item;
		});
		<?php
	}
}
?>
</script>