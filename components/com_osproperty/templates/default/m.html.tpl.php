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
if(($configClass['show_searchform']== 1) and ($lists['show_filterform'] == 1)){
?>

<table  width="100%" style="border:0px !important;">
	<tr>
		<td width="100%" valign="top" style="padding:0px;font-weight:bold;">
			<?php
			if(OSPHelper::useBootstrapSlide()){
			?>
			<div class="accordion" id="accordionsearch">
			<?php
			}else{
				echo JHtml::_('sliders.start', 'slide_pane');
			}
			?>
				<?php
				$showcat  = 1;
				$showtype = 1;
				$task = JRequest::getVar('task');
				$view = JRequest::getVar('view');
				if($view == "ltype"){
					$showcat  = 0;
					$showtype = 0;
				}
				if($task == "category_details"){
					$showcat = 0;
				}
				
					if(($showcat == 1) or ($showtype== 1) ){		
					?>
					<?php
					if(!OSPHelper::useBootstrapSlide()){
						//$slider = & JPane::getInstance('Sliders');
						echo JHtml::_('sliders.panel', JText::_('OS_CATEGORY'), 'category');
					}else{
					?>
					 <div class="accordion-group">
					    <div class="accordion-heading">
					      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionsearch" href="#collapse1">
					         <i class="icon-play"></i> <?php echo JText::_('OS_CATEGORY');?> & <?php echo JText::_('OS_PROPERTY_TYPE');?>
					      </a>
					    </div>
					    <div id="collapse1" class="accordion-body collapse">
					        <div class="accordion-inner">
								<?php
					}
					?>
					<div style="font-size:12px;">
					<?php
								if($showcat == 1){
								?>
								<?php echo JText::_('OS_CATEGORY')?><BR /> <?php echo $lists['category'];?>
								<BR />
								<?php
								}
								if($showtype == 1){
								?>
								<?php echo JText::_('OS_PROPERTY_TYPE')?><BR /> <?php echo $lists['type'];?>
								<?php
								}
								?>
					</div>
								<?php
								if(OSPHelper::useBootstrapSlide()){
								?>
							</div>
						</div>
					</div>
					<?php
								}
					}
					?>
					<?php
					if($showkeyword == 1){
					?>
					<?php
					if(!OSPHelper::useBootstrapSlide()){
						//$slider = & JPane::getInstance('Sliders');
						echo JHtml::_('sliders.panel', JText::_('OS_KEYWORD'), 'keyword');
					}else{
					?>
					 <div class="accordion-group">
					    <div class="accordion-heading">
					      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionsearch" href="#collapse2">
					        <i class="icon-play"></i> <?php echo JText::_('OS_KEYWORD');?>
					      </a>
					    </div>
					    <div id="collapse2" class="accordion-body collapse">
					        <div class="accordion-inner">
					        <?php
					}
					        ?>
					<div style="font-size:12px;">
							<?php
								echo JText::_('OS_KEYWORD')?><BR /> <input type="text" class="input-small" size="20" name="keyword" id="keyword" value="<?php echo $lists['keyword']?>" />
					</div>
								<?php
								if(OSPHelper::useBootstrapSlide()){
								?>
							</div>
						</div>
					</div>
					<?php
								}
					}
					?>
					<?php
					if(!OSPHelper::useBootstrapSlide()){
						//$slider = & JPane::getInstance('Sliders');
						echo JHtml::_('sliders.panel', JText::_('OS_LOCATION'), 'location');
					}else{
					?>
					<div class="accordion-group">
					    <div class="accordion-heading">
					      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionsearch" href="#collapse3">
					        <i class="icon-play"></i> <?php echo JText::_('OS_LOCATION');?>
					      </a>
					    </div>
					    <div id="collapse3" class="accordion-body collapse">
					        <div class="accordion-inner">
								<?php
					}
					?>
					<div style="font-size:12px;">
					<?php
								if(HelperOspropertyCommon::checkCountry()){
								?>
								<?php echo JText::_('OS_COUNTRY')?><BR /> <?php echo $lists['country'];?>
								<?php
								}else{
									 echo $lists['country'];
								}
								?>
								<BR />
								<div id="div_state" style="display:inline;">
									<?php echo JText::_('OS_STATE')?><BR /> <?php echo $lists['state'];?>
								</div>
								<BR />
								<?php echo JText::_('OS_CITY')?><BR /> <div id="city_div" style="display:inline;"><?php echo $lists['city'];?></div>
							</div>
								<?php
								if(OSPHelper::useBootstrapSlide()){
								?>
							</div>
						</div>
					</div>
					<?php
								}
					$showprice = 0;
					if($showprice == 1){
					?>
					<?php
					if(!OSPHelper::useBootstrapSlide()){
						//$slider = & JPane::getInstance('Sliders');
						echo JHtml::_('sliders.panel', JText::_('OS_PRICE'), 'price');
					}else{
					?>
					<div class="accordion-group">
					    <div class="accordion-heading">
					      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionsearch" href="#collapse4">
					       <i class="icon-play"></i> <?php echo JText::_('OS_PRICE');?>
					      </a>
					    </div>
					    <div id="collapse4" class="accordion-body collapse">
					        <div class="accordion-inner">
								<?php
					}
					?>
					<div style="font-size:12px;">
					<?php
								if($showprice==1){
								?>
								<?php echo JText::_('OS_MAXIUM_PRICE')?><BR /> <?php echo $lists['price'];?>
								<?php
								}
								?>
					</div>
								<?php
								if(OSPHelper::useBootstrapSlide()){
								?>
							</div>
						</div>
					</div>
					<?php
					}
				}
				?>
				<?php
					if(!OSPHelper::useBootstrapSlide()){
						//$slider = & JPane::getInstance('Sliders');
						echo JHtml::_('sliders.panel', JText::_('OS_SORT_BY'), 'sortby');
					}else{
					?>
				<div class="accordion-group">
				    <div class="accordion-heading">
				      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionsearch" href="#collapse5">
				        <i class="icon-play"></i> <?php echo JText::_('OS_SORT_BY');?>
				      </a>
				    </div>
				    <div id="collapse5" class="accordion-body collapse">
				        <div class="accordion-inner">
				        <?php
					}
					?>
					<div style="font-size:12px;">
					<?php
				        ?>
				        
							<?php echo JText::_('OS_SORT_BY')?><BR /><?php echo $lists['sortby'];?>
							<BR />
							<?php echo JText::_('OS_ORDER_BY')?><BR /> <?php echo $lists['ordertype'];?>
							&nbsp;
							</div>
							<?php
								if(OSPHelper::useBootstrapSlide()){
								?>
						</div>
					</div>
				</div>
			</div><?php
								}else{
									echo JHtml::_('sliders.end');
								}
			?>
			<div class="clr"></div>
			<div style="float:left;">
				<input type="submit" class="btn btn-info" value="<?php echo JText::_('OS_FILTER')?>" />
				<input type="reset" class="btn btn-warning" value="<?php echo JText::_('OS_RESET')?>" />
				&nbsp;
			</div>
		</td>
	</tr>
</table>
<?php
}
?>

<div id="listings">
	<?php
	if(count($rows) > 0){
	?>
	<table width="100%" style="font-size:11px;border-collapse:separate;" class="sTable">
		<tr>
			<td width="50%" align="left" style="border:0px;font-weight:bold;">
			<?php
			if(!$ismobile){
			?>
				<a href="javascript:updateView(3)" title="<?php echo JText::_('OS_CHANGE_TO_GRID_VIEW');?>">
					<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/gridview.png" style="border:1px solid #CCC !important;padding:1px;" />
				</a>
				<a href="javascript:updateView(2)" title="<?php echo JText::_('OS_CHANGE_TO_MAP_VIEW');?>">
					<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/mapview.png" style="border:1px solid #CCC !important;padding:1px;" />
				</a>
				<?php
				if($configClass['enable_google_xml'] == 1){
				?>
				<a href="javascript:updateView(4)" title="<?php echo JText::_('OS_CHANGE_TO_GOOGLE_EARTH_KML');?>">
					<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/kml.png" style="border:1px solid #CCC !important;padding:1px;" />
				</a>
				<?php
				}
				?>
				<input type="hidden" name="listviewtype" id="listviewtype" value="<?php echo $_COOKIE['viewtypecookie'];?>" />
				<script language="javascript">
				function updateView(view){
					var listviewtype = document.getElementById('listviewtype');
					listviewtype.value = view;
					document.ftForm.submit();
				}
				</script>
			<?php
			}
			?>
			</td>
			<td width="50%" align="right" style="border:0px;font-weight:bold;">
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
			</td>
		</tr>
	</table>
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
			<ul class="display" style="padding:0px;width:98% !important;">
			<?php
			for($i=0;$i<count($rows);$i++){
				$row = $rows[$i];
				$lists['curr'] = JHTML::_('select.genericlist',$currenyArr,'curr'.$i,'onChange="javascript:updateCurrency('.$i.','.$row->id.',this.value)" class="input-small"','value','text');
				?>
				<li class="featured" style="margin-bottom:5px !important;">
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
	       		<div class="content_block">
					<div class="product_image">
						<a href="<?php echo JRoute::_("index.php?option=com_osproperty&task=property_details&id=".$row->id."&Itemid=".JRequest::getVar('Itemid'))?>">
							<img alt="<?php echo $row->pro_name?>" title="<?php echo $row->pro_name?>" src="<?php echo $row->photo?>" style="width:<?php echo $width?>px"/>
						    <div class="photos_count"><strong><?php echo $row->count_photo?></strong> <?php echo JText::_('OS_PHOTOS')?></div>
						</a>
						
						<span style="width:<?php echo $width?>px;text-align:center;">
							<?php
								
								if(($configClass['show_getdirection'] == 1) and ($row->show_address== 1)){
								?>
								<span id="compare_1">
									<a href="<?php echo JRoute::_("index.php?option=com_osproperty&task=direction_map&id=".$row->id)?>" title="<?php echo JText::_('OS_GET_DIRECTIONS')?>">
									<img class="png" title="<?php echo JText::_('OS_GET_DIRECTIONS')?>" src="<?php echo JURI::root()?>components/com_osproperty/images/assets/direction24.png" width="16"/></a>
								</span>
								<?php
								}
								
								$user = JFactory::getUser();
								$db   = JFactory::getDBO();
								//print_r($configClass);
								if(intval($user->id) > 0){
									if(!$ismobile){
										if($configClass['show_compare_task'] == 1){
										?>
											<span id="compare_1">
												<a onclick="javascript:osConfirm('<?php echo JText::_('OS_DO_YOU_WANT_TO_ADD_PROPERTY_TO_COMPARE_LIST')?>','ajax_addCompare','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)">
												<img class="png" title="<?php echo JText::_('OS_ADD_TO_COMPARE_LIST')?>" alt="<?php echo JText::_('OS_ADD_TO_COMPARE_LIST')?>" src="<?php echo JURI::root()?>components/com_osproperty/images/assets/compare24.png" width="16" /></a>
											</span>
										<?php
										}
									}
									if($configClass['property_save_to_favories'] == 1){
										if($task != "property_favorites"){
										$db->setQuery("Select count(id) from #__osrs_favorites where user_id = '$user->id' and pro_id = '$row->id'");
										$count = $db->loadResult();
										if($count == 0){
											?>
											<span id="favorite_1">
												<a onclick="javascript:osConfirm('<?php echo JText::_('OS_DO_YOU_WANT_TO_ADD_PROPERTY_TO_YOUR_FAVORITE_LISTS')?>','ajax_addFavorites','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)">
												<img title="<?php echo JText::_('OS_ADD_TO_FAVORITES')?>" alt="<?php echo JText::_('OS_ADD_TO_FAVORITES')?>" src="<?php echo JURI::root()?>components/com_osproperty/images/assets/save24.png" width="16" /></a>
											</span>
											<?php
											}
										}
										if($count > 0){
											?>
											<span id="favorite_1">
												<a onclick="javascript:osConfirm('<?php echo JText::_('OS_DO_YOU_WANT_TO_REMOVE_PROPERTY_OUT_OF_YOUR_FAVORITE_LISTS')?>','ajax_removeFavorites','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)">
												<img title="<?php echo JText::_('OS_REMOVE_PROPERTY_OUT_OF_FAVORITES_LIST')?>" alt="<?php echo JText::_('OS_REMOVE_PROPERTY_OUT_OF_FAVORITES_LIST')?>" src="<?php echo JURI::root()?>components/com_osproperty/images/assets/remove24.png" width="16" /></a>
											</span>
											<?php
										}
									}
								}
								?>
						</span>
					</div>
					<div class="content">
						<h3 class="clearfix">
							<span class="propertyaddress">
								<a href="<?php echo JRoute::_("index.php?option=com_osproperty&task=property_details&id=".$row->id."&Itemid=".JRequest::getVar('Itemid'))?>">
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
	 						</span>
	 						<?php
	 						if($configClass['pdetails_layout'] == 1){
	 						?>
								<span class="price"><strong class="sale"> <?php echo $row->type_name?>  </strong> 
									<?php
									if($configClass['listing_show_price'] == 1){
									?><?php 
										if($row->price_call == 0){
											if($row->price > 0){
												?>
												<span id="currency_div<?php echo $i?>">
													<?php
													//echo JText::_('OS_PRICE');
													//echo ": ";
													echo HelperOspropertyCommon::loadCurrency($row->curr)." ".HelperOspropertyCommon::showPrice($row->price)." ";
													if($row->rent_time != ""){
														echo " /".$row->rent_time;
													}
													if(!$ismobile){
														if($configClass['show_convert_currency'] == 1){
														?>
														<BR />
														<span style="font-size:11px;">
														<?php echo JText::_('OS_CONVERT_CURRENCY')?>: <?php echo $lists['curr']?>
														</span>
														<?php
														}
													}
													?>
												</span>
												<?php
											}
										}else{
											echo JText::_('OS_CALL_FOR_PRICE');
										}
									}
									?>
								</span>
							<?php
	 						}
							?>
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
						
						if($configClass['pdetails_layout'] == 0){
							$width1 = "70%";
							$width2 = "30%";
							$align  = "center";
						}else{
							$width1 = "50%";
							$width2 = "50%";
							$align  = "center";
						}
						?>                                              					
						<div class="property_detail" style="width:<?php echo $width1?> !important;"> 
							<?php
	 						if($configClass['pdetails_layout'] == 0){
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
												echo HelperOspropertyCommon::loadCurrency($row->curr)." ".HelperOspropertyCommon::showPrice($row->price)." ";
												//convert currency
												if(!$ismobile){
													if($configClass['show_convert_currency'] == 1){
													?>
													<BR />
													<span style="font-size:11px;">
													<?php echo JText::_('OS_CONVERT_CURRENCY')?>: <?php echo $lists['curr']?>
													</span>
													<?php } 
												}
												?>
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
							<?php
	 						}
							?>
						</div>
					</div>
					<div style="clear:both;"></div>
					<div class="content" style="width:100% !important;;">
						<div style="padding-left:5px;">
							<span class="field"> <?php echo JText::_('OS_PROPERTY_TYPE')?> </span>
	 						<span>:   <?php echo $row->type_name; ?> </span>
	 						<BR />
							<span class="field"> <?php echo JText::_('OS_CATEGORY')?> </span> <span>:   <a href="index.php?option=com_osproperty&task=category_details&id=<?php echo $row->category_id?>&Itemid=<?php echo JRequest::getVar('Itemid')?>" title="<?php echo JText::_('OS_CATEGORY_DETAILS')?>"> 
							<?php echo $row->category_name?>
							</a> </span><BR />
							<?php
							if($configClass['listing_show_nbathrooms'] == 1){
							?>
                            <span class="field"> <?php echo JText::_('OS_BATHROOMS')?> </span> <span>:   <?php echo $row->bath_room; ?> </span><BR />
                            <?php
							}
                            ?>
                            <?php
							if($configClass['listing_show_nbedrooms'] == 1){
							?>
                            <span class="field"> <?php echo JText::_('OS_BEDROOMS')?> </span> <span>:   <?php echo $row->bed_room; ?> </span><BR />
                            <?php
							}
                            ?>
                            <?php
							if($configClass['listing_show_nrooms'] == 1){
							?>
                            <span class="field"> <?php echo JText::_('OS_ROOMS')?> </span> <span>:   <?php echo $row->rooms; ?> </span><BR />
                            <?php
							}
                            ?>
                            
                        </div>
                        <?php
                        if( !$ismobile){
                        ?>
                        <div class="property_detail" style="text-align:<?php echo $align?>;width:<?php echo $width2?> !important;">
                        	<?php
							if($configClass['listing_show_agent'] == 1){
							?>
                        	<?php
                        	if($row->agent_photo != ""){
                        		if(file_exists(JPATH_ROOT.DS."images".DS."osproperty".DS."agent".DS."thumbnail".DS.$row->agent_photo)){
	                        		?>
	                        		<img src="<?php echo JURI::root()?>images/osproperty/agent/thumbnail/<?php echo $row->agent_photo?>" height="70" style="border:1px solid #efefef;padding:3px;" />
	                        		<?php
								}else{
									?>
									<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/noimage.png" height="70" style="border:1px solid #efefef;padding:3px;" />
									<?php
								}
                        	}else{
                        		?>
                        		<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/noimage.png" height="70" style="border:1px solid #efefef;padding:3px;" />
                        		<?php
                        	}
                        	?>
                        	
                        	<BR />
                            <a title="<?php echo $row->agent_name?>" href="index.php?option=com_osproperty&task=agent_info&id=<?php echo $row->agent_id?>&Itemid=<?php echo JRequest::getVar('Itemid')?>">
								<?php echo $row->agent_name?>
							</a>
							<?php
							}
							?>
                        </div>
                        <?php } ?>
	                    <p class="propertylistinglinks">
	                    	<?php
							echo $row->other_information;
							?>
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
	if(count($rows) > 0){
		?>
		<div style="width:100%;text-align:center;padding:5px;">
			<?php
				echo $pageNav->getListFooter();
			?>
		</div>
		<?php
	}
	?>
</div>