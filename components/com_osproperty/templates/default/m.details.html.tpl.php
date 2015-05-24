<?php
/*------------------------------------------------------------------------
# small.details.html.tpl.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// No direct access.
defined('_JEXEC') or die;
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."components/com_osproperty/templates/default/style/small.style.css");
?>

<style>
#main ul{
	margin:0px;
}
.accordion-inner{
	padding-left:5px !important;
	padding-right:5px !important;
}
</style>
<script language="javascript">
function showhideDiv(id){
	var temp1 = document.getElementById('fs_' + id);
	var temp2 = document.getElementById('fsb_' + id);
	if(temp1.style.display == "block"){
		temp1.style.display = "none";
		temp2.innerHTML = "[+]";
	}else{
		temp1.style.display = "block";
		temp2.innerHTML = "[-]";
	}
}
</script>
<div id="notice" style="display:none;">
	
</div>
<div style="width:100%;">
	<h1 style="border:0px;width:100%;">
		<div style="float:left;width:100%;font-size:18px;">
			<?php
			if($row->ref != ""){
				echo $row->ref.", ";
			}
			?>
			<?php echo $row->pro_name?>
			<?php
			if($row->isFeatured == 1){
				?>
				<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/isfeatured.png" />
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
		</div>
	</h1>
</div>
<div style="float:right;width:auto !important;">
	<?php echo $row->price?>
</div>

<div class="clearfix"></div>
<div class="jwts_tabber" id="jwts_tab">
	<!-- Listing -->
	<!--<div class="jwts_tabbertab" title="<?php echo JText::_(OS_LISTING)?>" >
		<h2><a href="javascript:void(null);" name="advtab" ><?php echo JText::_(OS_LISTING)?></a></h2> -->
		<?php
		if(!OSPHelper::useBootstrapSlide()){
			//$slider = & JPane::getInstance('Sliders');
			echo JHtml::_('sliders.start', 'slide_pane');
			echo JHtml::_('sliders.panel', JText::_('OS_LISTING'), 'listing');
		}else{
		?>
		<div class="accordion" id="accordionlisting">
			<div class="accordion-group clearfix">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionlisting" href="#collapselisting">
						<i class="icon-play"></i><?php echo JText::_('OS_LISTING');?>
					</a>
				</div>
			<div id="collapselisting" class="accordion-body collapse in">
			<div class="accordion-inner">
		<?php
		}
		?>
				<!-- Listing details -->
				<div style="padding-top:10px;font-size:12px;">
				
				<?php
				JHTML::_('behavior.modal');
				$mapwidth = $configClass['property_map_width'];
				if(intval($mapwidth) == 0){
					$mapwidth = 500;
				}
				$iphonecss = "";
				if($ismobile){
					$mapcss  = "max-width:100%;width:auto;height: ".$configClass['property_map_height']."px;min-width:150px;min-height:100px;";
				}else{
					$mapcss  = "height: ".$configClass['property_map_height']."px; width: ".$mapwidth."px;";
				}
				?>
					<div style="width:auto  !important;padding:5px;">
							<!-- top bar -->
							<div class="property-details-main-div">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#tabphoto" id="aphoto" data-toggle="tab"><?php echo JText::_('OS_PHOTO');?></a></li>
									<?php
									if($row->show_address == 1){
									?>
										<li><a href="#tabgoogle" data-toggle="tab"  id="agooglemap"><?php echo JText::_('OS_MAP');?></a></li>
										<?php
										if($configClass['show_streetview'] == 1){
										?>
										<li><a href="#tabstreet" data-toggle="tab" id="astreetview"><?php echo JText::_('OS_STREET');?></a></li>
										<?php
										}
									}
									?>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="tabphoto">
									  <?php
									  HelperOspropertyCommon::propertyGalleryMobile($row->id,$photos);
									  ?>
									</div>
									<div class="tab-pane" id="tabgoogle">
										<div id="map_canvas" style="<?php echo $mapcss;?>"></div>
									</div>
									<?php
									
									if($configClass['show_streetview'] == 1){
									?>
									<div class="tab-pane" id="tabstreet">
										<div id="pano" style="<?php echo $mapcss;?>"></div>
									</div>
									<?php
									}
									?>
								</div>
							</div>
							<!-- end top bar -->
					</div>
					<?php
					if($row->show_address == 1){
					?>
					<!-- Location -->
					<div style="width:auto !important;margin:5px;">
						<div class="width-100 fltlft">
							<fieldset class="adminform" style="padding:0px !important; ">
								<legend><?php echo JText::_(OS_LOCATION)?> <a href="javascript:showhideDiv('location');" id="fsb_location">[-]</a></legend>
								<div id="fs_location" style="margin: 0 7px;display:block;">
								<?php echo $row->location?>
							</fieldset>
						</div>
					</div>
					
					<!-- End location -->
					<?php
					} //end show address ?
					?>
					
					<!-- Information -->
					<div style="width:auto !important;margin:5px;">
						<div class="width-100 fltlft">
							<fieldset class="adminform" style="padding:0px !important; ">
								<legend><?php echo JText::_(OS_PROPERTY_INFORMATION)?> <a href="javascript:showhideDiv('information');" id="fsb_information">[-]</a></legend>
								<div id="fs_information" style="margin: 0 7px;display:block;">
								<?php echo $row->info?>
							</fieldset>
						</div>
					</div>
					<!-- End infomration -->
					<div style="width:auto !important;margin:5px;">
						<div class="width-100 fltlft">
							<fieldset class="adminform" style="padding:0px !important; ">
								<legend><?php echo JText::_(OS_PROPERTY_FEATURE)?> <a href="javascript:showhideDiv('feature');" id="fsb_feature">[-]</a></legend>
								<div id="fs_feature" style="margin: 0 7px;display:block;">
								<?php echo $row->featured?>
							</fieldset>
						</div>
					</div>
								<!-- End Propety feature -->
					<div style="width:auto !important;margin:5px;">
						<div class="width-100 fltlft">
							<fieldset class="adminform" style="padding:0px !important; ">
								<legend><?php echo JText::_(OS_AMENITIES)?> <a href="javascript:showhideDiv('amenity');" id="fsb_amenity">[-]</a></legend>
								<div id="fs_amenity" style="margin: 0 7px;display:block;">
								<?php echo $row->amens_str?>
							</fieldset>
						</div>
					</div>
					<?php
					if((trim($row->neighborhood) != "") and ($configClass['show_neighborhood_group'] == 1)){
					?>
					<div style="width:auto !important;margin:5px;">
						<div class="width-100 fltlft">
							<fieldset class="adminform" style="padding:0px !important; ">
								<legend><?php echo JText::_(OS_NEIGHBORHOOD)?> <a href="javascript:showhideDiv('neighborhood');" id="fsb_neighborhood">[-]</a></legend>
								<div id="fs_neighborhood" style="margin: 0 7px;display:block;">
								<?php
									echo $row->neighborhood;
								?>
							</fieldset>
						</div>
					</div>
					<?php
					}
					
					if(count($row->extra_field_groups) > 0){
						$extra_field_groups = $row->extra_field_groups;
						for($i=0;$i<count($extra_field_groups);$i++){
						
							$group = $extra_field_groups[$i];
							$group_name = $group->group_name;
							$fields = $group->fields;
							if(count($fields)> 0){
							?>
							<div style="width:auto !important;margin:5px;">
								<div class="width-100 fltlft">
									<fieldset class="adminform" style="padding:0px !important; ">
										<legend><?php echo $group_name;?> <a href="javascript:showhideDiv('<?php echo $i?>');" id="fsb_<?php echo $i?>">[-]</a></legend>
										<div id="fs_<?php echo $i?>" style="margin: 0 7px;display:block;">
										<?php
										
										for($j=0;$j<count($fields);$j++){
											$field = $fields[$j];
											
											if($field->displaytitle == 1){
												?>
												<div class="div_labels">
													<?php
													if($field->field_description != ""){
													?>
														<span class="editlinktip hasTip" title="<?php echo $field->field_label;?>::<?php echo $field->field_description?>">
															<?php echo $field->field_label;?>
														</span>
													<?php
													}else{
													?>
														<?php echo $field->field_label;?>
													<?php
													}
													?>
												</div>
												<div class="div_value">
													<?php echo $field->value;?>
												</div>
												<div class="clearfix"></div>
												<?php
											}else{
												?>
												<div class="div_labels">
														<?php echo $field->value;?>
													</div>
												<div class="clearfix"></div>
												<?php
											}
										}
										?>
									</fieldset>
								</div>
							</div>
							<?php
							}
						}
					}
					?>
					</div>
					<!-- Propety other information -->
					<?php
					if(OSPHelper::useBootstrapSlide()){
					?>
				</div>
			</div>
		</div>
		<?php
					}
		?>
	<!-- end listing -->
	<?php
	if($configClass['show_walkscore'] == 1){
		if($configClass['ws_id'] != ""){
	?>
			<!--<div class="jwts_tabbertab" title="<?php echo JText::_('OS_WALK_SCORE')?>">
				<h2><a href="javascript:void(null);" name="advtab"><?php echo JText::_('OS_WALK_SCORE')?></a></h2>  -->
			<?php
			if(!OSPHelper::useBootstrapSlide()){
				echo JHtml::_('sliders.panel', JText::_('OS_WALK_SCORE'), 'walkscore');		
			}else{
			?>
			<div class="accordion-group clearfix">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionlisting" href="#collapsewalkscore">
						<i class="icon-play"></i><?php echo JText::_('OS_WALK_SCORE');?>
					</a>
				</div>
				<div id="collapsewalkscore" class="accordion-body collapse">
					<div class="accordion-inner" style="padding:0px !important; margin:0px !important;">
			<?php
			}
			?>
						<div style="margin: 0px;">
							<?php
							echo $row->ws;
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
	}
	
	if($configClass['show_agent_details'] == 1){
	?>
	<!--<div class="jwts_tabbertab" title="<?php echo JText::_(OS_AGENT_INFO)?>">
		<h2><a href="javascript:void(null);" name="advtab"><?php echo JText::_(OS_AGENT_INFO)?></a></h2>
		 -->
	<?php
	if(!OSPHelper::useBootstrapSlide()){
		echo JHtml::_('sliders.panel', JText::_('OS_AGENT_INFO'), 'agentinfo');		
	}else{
	?>
	<div class="accordion-group">
		<div class="accordion-heading">
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionlisting" href="#collapseagent">
				<i class="icon-play"></i><?php echo JText::_('OS_AGENT_INFO');?>
			</a>
		</div>
		<div id="collapseagent" class="accordion-body collapse">
			<div class="accordion-inner">
			<?php
	}
			?>
				<div class="block_caption">
					<strong><?php echo JText::_(OS_AGENT_INFORMATION)?></strong>
				</div>
				<div style="font-size:12px;">
				<?php
				echo $row->agent;
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
	if($configs[56]->fieldvalue == 1){
	?>
	<?php
	if(!OSPHelper::useBootstrapSlide()){
		echo JHtml::_('sliders.panel', JText::_('OS_SHARING'), 'sharing');		
	}else{
	?>
	<div class="accordion-group">
		<div class="accordion-heading">
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionlisting" href="#collapsesharing">
				<i class="icon-play"></i><?php echo JText::_('OS_SHARING');?>
			</a>
		</div>
		<div id="collapsesharing" class="accordion-body collapse">
			<div class="accordion-inner">
		<?php
	}
		?>
				<div class="block_caption">
					<strong><?php echo JText::_(OS_TELL_A_FRIEND_FORM)?></strong>
				</div>	
				<div style="margin: 5px 5px 0 5px;font-size:12px;">
					<div class="blue_middle"><?php echo JText::_(OS_FIELDS_MARKED);?> <span class="red">*</span> <?php echo JText::_(OS_ARE_REQUIRED);?>
					</div>
				
					<form method="POST" action="index.php" name="tellfriend_form" id="tellfriend_form">
					<div class="div_labels">
						<span class="grey_small"><?php echo JText::_(OS_FRIEND_NAME);?> <span class="red">*</span></span>
					</div>
					<div class="clearfix"></div>
					<div class="div_value">
					<input class="input-large" type="text" id="friend_name" name="friend_name" maxlength="30"  />
					</div>
					<div class="clearfix"></div>
					<div class="div_labels">
							<span class="grey_small"><?php echo JText::_(OS_FRIEND_EMAIL);?> <span class="red">*</span></span>
						</div>
						<div class="clearfix"></div>
						<div class="div_value">
							<input class="input-large" type="text" id="friend_email" name="friend_email" maxlength="30"  />
						</div>
					<div class="clearfix"></div>
					<div class="div_labels">
							<span class="grey_small"><?php echo JText::_(OS_YOUR_NAME);?></span>
						</div>
						<div class="clearfix"></div>
						<div class="div_value">
							<input class="input-large" type="text" id="your_name" name="your_name" maxlength="30" />
						</div>
					<div class="clearfix"></div>
					<div class="div_labels">
							<span class="grey_small"><?php echo JText::_(OS_YOUR_EMAIL);?></span>
						</div>
						<div class="clearfix"></div>
						<div class="div_value">
							<input class="input-large" type="text" id="your_email" name="your_email" maxlength="30" />
						</div>
					<div class="clearfix"></div>
					<div class="div_labels">
							<span class="grey_small"><?php echo JText::_(OS_MESSAGE);?></span>
						</div>
						<div class="clearfix"></div>
						<div class="div_value">
						<textarea class="inputbox" id="message" name="message" rows="6" cols="40" class="inputbox"></textarea>
						</div>
					<div class="clearfix"></div>
					<div class="div_labels">
						<span class="grey_small"><?php echo JText::_(OS_SECURITY_CODE)?> <span class="red">*</span></span>
					</div>
					<div class="clearfix"></div>
					<div class="div_value">
						<div class="div_labels">
							<img src="<?php echo JURI::root()?>index.php?option=com_osproperty&no_html=1&task=property_captcha&ResultStr=<?php echo $row->ResultStr?>" /> 
							
						</div>
						<div class="div_value">
							<span class="grey_small" style="line-height:16px;"><?php echo JText::_(OS_PLEASE_INSERT_THE_SYMBOL_FROM_THE_INAGE_TO_FIELD_BELOW)?></span><br />
							<input type="text" class="input-mini" id="sharing_security_code" name="sharing_security_code" maxlength="5" style="width: 50px; margin: 0;" />
						</div>
						<div class="clearfix"></div>		
					</div>
					<div class="clearfix"></div>		
					<div style="width:auto !important;">
						<input class="btn btn-primary" type="button" name="finish" value="<?php echo JText::_('OS_SEND');?>" onclick="javascript:submitForm('tellfriend_form');"/>
						<span class="reg_loading" id="tf_loading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
					</div>
					<input type="hidden" name="option" value="com_osproperty" />
					<input type="hidden" name="task" value="property_submittellfriend" />
					<input type="hidden" name="id" value="<?php echo $row->id?>" />
					<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid')?>" />
					<input type="hidden" name="require_field" id="require_field" value="friend_name,friend_email,sharing_security_code" />
					<input type="hidden" name="require_label" id="require_label" value="<?php echo JText::_(OS_FRIEND_NAME);?>,<?php echo JText::_(OS_FRIEND_EMAIL);?>,<?php echo JText::_(OS_SECURITY_CODE)?>" />
					</form>
					<BR />
					<?php
					if($configClass['social_sharing']== 1){
					?>
					<div class="block_caption">
						<strong><?php echo JText::_(OS_SHARE_PROPERTY_TO_SOCIAL)?></strong>
					</div>
					<div style="margin: 10px 10px 0 10px">
						<?php
						echo $row->share;
						?>
					</div>
					<div class="grey_line" style="margin-top: 10px;"></div>
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
	$user = JFactory::getUser();
	if($configClass['comment_active_comment'] == 1){
		?>
		<?php
	if(!OSPHelper::useBootstrapSlide()){
		echo JHtml::_('sliders.panel', JText::_('OS_COMMENTS'), 'comment');		
	}else{
	?>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionlisting" href="#collapsecomment">
						<i class="icon-play"></i><?php echo JText::_('OS_COMMENTS');?>
					</a>
				</div>
				<div id="collapsecomment" class="accordion-body collapse">
					<div class="accordion-inner">
					<?php
	}
					?>
					<div style="font-size:12px;">
					<?php
					echo $row->comments;
					?>
					</div>
					<div style="font-size:12px;margin:5px 5px 0 5px;">
					<?php
					if(($owner == 0) and ($can_add_cmt == 0)){
					?>
					<div class="block_caption" id="comment_form_caption">
						<strong><?php echo JText::_(OS_ADD_COMMENT)?></strong>
					</div>	
					<div class="blue_middle"><?php echo JText::_(OS_FIELDS_MARKED);?> <span class="red">*</span> <?php echo JText::_(OS_ARE_REQUIRED);?></div>
					<form method="POST" action="index.php" name="commentForm" id="commentForm">
						<?php
						if($configClass['show_rating'] == 1){
						?>
						<div class="div_labels">
								<span class="grey_small"><?php echo JText::_(OS_RATING);?>
						</div>
						<div class="clearfix"></div>
						<div class="div_value">
								<i>Worst
								&nbsp;
								<?php
								for($i=1;$i<=5;$i++){
									if($i==3){
										$checked = "checked";
									}else{
										$checked = "";
									}
									?>
									<input type="radio" name="rating" id="rating<?php echo $i?>" value="<?php echo $i?>" <?php echo $checked?> />
									<?php
								}
								?>
								&nbsp;&nbsp;Best</i>
						</div>
						<div class="clearfix"></div>
						<?php
						}
						?>
						<div class="div_labels">
								<span class="grey_small"><?php echo JText::_(OS_AUTHOR);?> <span class="red">*</span></span>
						</div>
						<div class="clearfix"></div>
						<div class="div_value">
								<input class="input-large" type="text" id="comment_author" name="comment_author" maxlength="30"  />
						</div>
						<div class="clearfix"></div>
						<div class="div_labels">
								<span class="grey_small"><?php echo JText::_(OS_TITLE);?> <span class="red">*</span></span>
						</div>
						<div class="clearfix"></div>
						<div class="div_value">
								<input class="input-large" type="text" id="comment_title" name="comment_title" size="40" />
						</div>
						<div class="clearfix"></div>
						<div class="div_labels">
								<span class="grey_small"><?php echo JText::_(OS_MESSAGE);?> <span class="red">*</span></span>
						</div>
						<div class="clearfix"></div>
						<div class="div_value">
								<textarea class="inputbox" id="comment_message" name="comment_message" rows="6" cols="50" class="inputbox"></textarea>
						</div>
						<div class="clearfix"></div>
						<div class="div_labels">
								<input id="comment_message_counter" class="counter" type="text" readonly="" size="3" maxlength="3" style="position:relative !important;"/>
								<span style="float:left;"><?php echo JText::_(OS_CHARACTERS_LEFT)?></span>
						</div>
						<div class="clearfix"></div>
						<div class="div_labels">
								<span class="grey_small"><?php echo JText::_(OS_SECURITY_CODE)?> <span class="red">*</span></span>
						</div>
						<div class="clearfix"></div>
						<div class="div_labels">
							<div class="div_labels">
								<img src="<?php echo JURI::root()?>index.php?option=com_osproperty&no_html=1&task=property_captcha&ResultStr=<?php echo $row->ResultStr?>" /> 
								
							</div>
							<div class="div_value">
								<span class="grey_small" style="line-height:16px;"><?php echo JText::_(OS_PLEASE_INSERT_THE_SYMBOL_FROM_THE_INAGE_TO_FIELD_BELOW)?></span><br />
								<input type="text" class="input-mini" id="comment_security_code" name="comment_security_code" maxlength="5" style="width: 70px; margin: 0;" />
							</div>
						</div>
						<div class="clearfix"></div>
							<input onclick="javascript:submitForm('commentForm')" style="margin: 0; width: 100px;" class="btn btn-warning" type="button" name="finish" value="<?php echo JText::_(OS_ADD)?>" />
							<span id="comment_loading" class="reg_loading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
					</div>
					<?php
					}
					?>
					<input type="hidden" name="captcha_str" id="captcha_str" value="<?php echo $row->ResultStr?>" />
					<input type="hidden" name="option" value="com_osproperty" />
					<input type="hidden" name="task" value="property_submitcomment" />
					<input type="hidden" name="id" value="<?php echo $row->id?>" />
					<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid')?>" />
					<input type="hidden" name="require_field" id="require_field" value="comment_author,comment_title,comment_message,comment_security_code" />
					<input type="hidden" name="require_label" id="require_label" value="<?php echo JText::_(OS_AUTHOR);?>,<?php echo JText::_(OS_TITLE);?>,<?php echo JText::_(OS_MESSAGE);?>,<?php echo JText::_(OS_SECURITY_CODE)?>" />
					<script type="text/javascript">
						var comment_textcounter = new textcounter({
							textarea: 'comment_message',
							min: 0,
							max: <?php echo $configClass['max_character'];?>
						});
						comment_textcounter.init();
					</script>
					<br />
					</form>
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
	if($row->pro_video != ""){
	?>
	<?php
	if(!OSPHelper::useBootstrapSlide()){
		echo JHtml::_('sliders.panel', JText::_('OS_VIDEO'), 'video');		
	}else{
	?>
	<div class="accordion-group">
		<div class="accordion-heading">
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionlisting" href="#collapsevideo">
				<i class="icon-play"></i><?php echo JText::_('OS_VIDEO');?>
			</a>
		</div>
		<div id="collapsevideo" class="accordion-body collapse">
			<div class="accordion-inner">
				<?php
	}
				echo stripslashes($row->pro_video);
				?>
				<br />
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
		echo JHtml::_('sliders.panel', JText::_('OS_REQUEST_MORE_INFOR'), 'request');		
	}else{
	?>
	<div class="accordion-group">
		<div class="accordion-heading">
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionlisting" href="#collapserequest">
				<i class="icon-play"></i><?php 
			if(!$ismobile){
				echo JText::_('OS_REQUEST_MORE_INFOR');
			}else{
				echo JText::_('OS_MOBILE_REQUEST_MORE_INFOR');
			}
			?>
			</a>
		</div>
		<div id="collapserequest" class="accordion-body collapse">
			<div class="accordion-inner">
				<?php
	}
				$user = JFactory::getUser();
				?>	
				<div class="block_caption">
					<strong><?php if(!$ismobile){
						echo JText::_('OS_REQUEST_MORE_INFOR');
					}else{
						echo JText::_('OS_MOBILE_REQUEST_MORE_INFOR');
					}?></strong>
				</div>	
				<div style="margin: 5px 5px 0 5px;font-size:12px;">
				<div class="blue_middle"><?php echo JText::_(OS_FIELDS_MARKED);?> <span class="red">*</span> <?php echo JText::_(OS_ARE_REQUIRED);?></div>
				<form method="POST" action="index.php" name="requestdetails_form" id="requestdetails_form">
				
				<div class="div_labels">
						<span class="grey_small"><?php echo JText::_('OS_SUBJECT');?> <span class="red">*</span></span>
				</div>
				<div class="clearfix"></div>
				<div class="div_value">
						<select name='subject' id='subject' class='input-large' onchange="javascript:updateRequestForm(this.value)">
							<option value='1'><?php echo JText::_('OS_REQUEST_1')?></option>
							<option value='2'><?php echo JText::_('OS_REQUEST_2')?></option>
							<option value='3'><?php echo JText::_('OS_REQUEST_3')?></option>
							<option value='4'><?php echo JText::_('OS_REQUEST_4')?></option>
							<option value='5'><?php echo JText::_('OS_REQUEST_5')?></option>
							<option value='6'><?php echo JText::_('OS_REQUEST_6')?></option>
						</select>
					</div>
				<div class="clearfix"></div>
				<div class="div_labels">
						<span class="grey_small"><?php echo JText::_(OS_MESSAGE);?> <span class="red">*</span></span> 
					</div>
				<div class="clearfix"></div>
				<div class="div_value">
						<textarea class="input-large" id="requestmessage" name="requestmessage" rows="6" cols="40" ><?php echo JText::_('OS_REQUEST_MSG1')?> <?php echo ($row->ref != "")? $row->ref.", ":""?><?php echo $row->pro_name?></textarea>
				</div>
				<div class="clearfix"></div>
				<div class="div_labels">
						<span class="grey_small"><?php echo JText::_(OS_YOUR_NAME);?> <span class="red">*</span></span>
					</div>
				<div class="clearfix"></div>
				<div class="div_value">
						<input class="input-large" type="text" id="requestyour_name" name="requestyour_name" size="30" maxlength="30" value="<?php echo $user->name?>" />
					</div>
				<div class="clearfix"></div>
				<div class="div_labels">
						<span class="grey_small"><?php echo JText::_(OS_YOUR_EMAIL);?> <span class="red">*</span></span>
					</div>
				<div class="clearfix"></div>
				<div class="div_value">
						<input class="input-large" type="text" id="requestyour_email" name="requestyour_email" size="30" maxlength="30" value="<?php echo $user->email;?>"/>
					</div>
				<div class="clearfix"></div>
				<div class="div_labels">
						<span class="grey_small"><?php echo JText::_('OS_PHONE');?></span>
					</div>
				<div class="clearfix"></div>
				<div class="div_value">
						<input class="input-large" type="text" id="your_phone" name="your_phone" maxlength="30"  />
					</div>
				<div class="clearfix"></div>
				<div class="div_labels">
						<span class="grey_small"><?php echo JText::_(OS_SECURITY_CODE)?> <span class="red">*</span></span>
				</div>
				<div class="clearfix"></div>
				<div class="div_value">
					<div class="div_labels">
							<img src="<?php echo JURI::root()?>index.php?option=com_osproperty&no_html=1&task=property_captcha&ResultStr=<?php echo $row->ResultStr?>" /> 
							
				</div>
				<div class="div_value">
					<span class="grey_small" style="line-height:16px;"><?php echo JText::_(OS_PLEASE_INSERT_THE_SYMBOL_FROM_THE_INAGE_TO_FIELD_BELOW)?></span><br />
						<input type="text" class="input-mini" id="request_security_code" name="request_security_code" maxlength="5" style="width: 50px; margin: 0;"  />
				</div></div>
		<div class="clearfix"></div>		
		
		
				<input class="btn btn-info" type="button" id="requestbutton" name="requestbutton" value="<?php echo JText::_("OS_REQUEST_BUTTON1")?>" onclick="javascript:submitForm('requestdetails_form');"/>
				<span class="reg_loading" id="tf_loading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
				<input type="hidden" name="captcha_str" id="captcha_str" value="<?php echo $row->ResultStr?>" />
				<input type="hidden" name="option" value="com_osproperty" />
				<input type="hidden" name="task" value="property_requestmoredetails" />
				<input type="hidden" name="id" value="<?php echo $row->id?>" />
				<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid')?>" />
				<input type="hidden" name="require_field" id="require_field" value="requestmessage,requestyour_name,requestyour_email,request_security_code" />
				<input type="hidden" name="require_label" id="require_label" value="<?php echo JText::_('OS_REQUEST_DETAILS');?>,<?php echo JText::_('OS_YOUR_NAME');?>,<?php echo JText::_('OS_YOUR_EMAIL');?>,<?php echo JText::_(OS_SECURITY_CODE)?>" />
				</form>
				</div>
				<script language="javascript">
				function updateRequestForm(subject){
					var message = document.getElementById('requestmessage');
					var requestbutton = document.getElementById('requestbutton');
					if(subject == 1){
						message.value = "<?php echo JText::_('OS_REQUEST_MSG1')?> <?php echo $row->pro_name?>";
						requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON1')?>";
					}else if(subject == 2){
						message.value = "<?php printf(JText::_('OS_REQUEST_MSG2'),$row->pro_name);?>";
						requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON2')?>";
					}else if(subject == 3){
						message.value = "<?php echo JText::_('OS_REQUEST_MSG3')?> <?php echo $row->pro_name?>";
						requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON3')?>";
					}else if(subject == 4){
						message.value = "<?php echo JText::_('OS_REQUEST_MSG4')?> <?php echo $row->pro_name?>";
						requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON4')?>";
					}else if(subject == 5){
						message.value = "<?php echo JText::_('OS_REQUEST_MSG5')?> <?php echo $row->pro_name?>";
						requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON5')?>";
					}else if(subject == 6){
						message.value = "<?php echo JText::_('OS_REQUEST_MSG6')?> <?php echo $row->pro_name?>";
						requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON6')?>";
					}
				}
				</script>
				<div class="grey_line" style="margin-top: 10px;"></div>
				<?php
					if(OSPHelper::useBootstrapSlide()){
					?>
			</div>
		</div>
	</div>
	
	<?php
	}
	if(file_exists(JPATH_ROOT.DS."components".DS."com_oscalendar".DS."oscalendar.php")){
		if($configClass['integrate_oscalendar'] == 1){
			require_once(JPATH_ROOT.DS."components".DS."com_oscalendar".DS."classes".DS."default.php");
			require_once(JPATH_ROOT.DS."components".DS."com_oscalendar".DS."classes".DS."default.html.php");
			$otherlanguage =& JFactory::getLanguage();
			$otherlanguage->load( 'com_oscalendar', JPATH_SITE );
			OsCalendarDefault::calendarForm($row->id);
		}
	}
	if(!OSPHelper::useBootstrapSlide()){
		echo JHtml::_('sliders.end');
	}else{
	?>
	</div>
</div>
<?php
	}
?>
<div class="jwts_clr"></div><br />
