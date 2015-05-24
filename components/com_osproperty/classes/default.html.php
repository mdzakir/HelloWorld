<?php
/*------------------------------------------------------------------------
# default.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class HTML_OspropertyDefault{
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $option
	 * @param unknown_type $property
	 * @param unknown_type $photos
	 * @param unknown_type $info
	 * @param unknown_type $lists
	 * @param unknown_type $configs
	 */
	static function defaultLayout($option,$property,$lists,$configs){
		global $mainframe,$configClass,$symbol,$languages;
		$document = &JFactory::getDocument();
		//#CCCFFA
		//#A5B2E1
		
		$needs = array();
		$needs[] = "property_details";
		$needs[] = $properties[0]->id;
		$itemid = OSPRoute::getItemid($needs);
		
		?>
		<?php OSPHelper::generateHeading(2,$configClass['general_bussiness_name']);?>
		
		<div class="lblock_no_design">
			<?php echo $configs[94]->fieldvalue?>
		</div>
		<div class="row-fluid">
			<?php
			$tab1 = "";
			$tab2 = "";
			if($configClass['show_frontpage_box']==1){
				$tab1 = "active";
			}elseif($configClass['show_quick_search']==1){
				$tab2 = "active";
			}
			if(($configClass['show_frontpage_box']==1) or ($configClass['show_quick_search']==1)){
			?>
			<div class="span8">
				<div class="tabbable">
					<ul class="nav nav-tabs">
						<?php
						if($configClass['show_frontpage_box']==1){
						?>
						<li class="<?php echo $tab1?>"><a href="#homepagetab" data-toggle="tab"><i class="osicon-home"></i>&nbsp;<?php echo JText::_('OS_HOMEPAGE');?></a></li>
						<?php
						}
						if($configClass['show_quick_search']==1){
						?>
						<li class="<?php echo $tab2?>"><a href="#quicksearchtab" data-toggle="tab"><i class="osicon-search"></i>&nbsp;<?php echo JText::_('OS_QUICK_SEARCH');?></a></li>
						<?php
						}
						?>
					</ul>
				</div>
				<div class="tab-content">
					<?php
					if($configClass['show_frontpage_box']==1){
					?>
					<div class="tab-pane <?php echo $tab1?>" id="homepagetab">
					<table  width="100%" style="border:0px !important;display:block;">
						<tr>
							<td width="100%" style="padding:0px;color:#504E4D;">
								<BR />
								<?php
									//echo $configClass['introtext'];
									$translatable = JLanguageMultilang::isEnabled() && count($languages);
									if($translatable){
										$default_language = OSPHelper::getDefaultLanguage();
										$language = JFactory::getLanguage();
										$current_language = $language->getTag();
										if($current_language == $default_language){
											echo $configClass['introtext'];
										}else{
											$current_language = explode("-",$current_language);
											$current_language = $current_language[0];
											echo $configClass['introtext_'.$current_language];
										}
									}else{
										echo $configClass['introtext'];
									}
								?>
								<BR /><BR />
								<div style="text-align:center;font-weight:bold;font-size:11px;color:gray;">
								
								<?php
								$user = JFactory::getUser();
								if((intval($user->id)==0) and ($configClass['allow_agent_registration'])){
									?>
									<BR />
									<?php echo JText::_('OS_REGISTER_NEW_AGENT_ACCOUNT')?>
									<BR /><BR /><BR />
									<?php
									$needs = array();
									$needs[] = "agent_register";
									$needs[] = "aagentregistration";
									$itemid2  = OSPRoute::getItemid($needs);
									$itemid2  = OSPRoute::confirmItemid($itemid2,'aagentregistration');
									if($itemid2 == 0){
										$itemid2  = OSPRoute::confirmItemid($itemid2,'agent_register');	
									}
									?>
									<a href="<?php echo JRoute::_('index.php?option=com_osproperty&view=aagentregistration&Itemid='.$itemid2);?>" title="<?php echo JText::_('OS_BECOME_AGENT')?>" class="btn"><i class="osicon-user"></i><?php echo JText::_('OS_BECOME_AGENT')?></a>
									<?php
								}elseif($configClass['allow_agent_registration']==1){
									$needs = array();
									$needs[] = "property_new";
									$needs[] = "aaddproperty";
									$itemid  = OSPRoute::getItemid($needs);
									?>
									<BR />
									<?php echo JText::_('OS_PUBLISH_YOUR_PROPERTIES_IN_THE_SITE_BY_CLICK_ON_THE_BELLOW_BUTTON');?>
									<BR /><BR /><BR />
									<a href="<?php echo JRoute::_("index.php?option=com_osproperty&task=property_new&Itemid=".$itemid);?>" title="<?php echo JText::_('OS_ADD_NEW_PROPERTY')?>" class="btn"><i class="osicon-edit"></i><?php echo JText::_('OS_ADD_NEW_PROPERTY')?></a>
									<?php
								}
								?>
								<BR /><BR />
								</div>
							</td>
						</tr>
					</table>
					</div>
					<?php
					}
					if($configClass['show_quick_search']==1){
					?>
					<div class="tab-pane <?php echo $tab2?>" id="quicksearchtab">
						<?php
						$needs = array();
						$needs[] = "ladvsearch";
						$needs[] = "property_advsearch";
						$itemid1 = OSPRoute::getItemid($needs);
						?>
						<form action="<?php echo JRoute::_('index.php?option=com_osproperty&view=ladvsearch&Itemid='.$itemid1)?>" name="home_form" method="post">
                            <div class="row-fluid">
                                <div class="span12" id="quicksearchform">
                                    <?php if(HelperOspropertyCommon::checkCountry()){?>
                                    <div class="control-group">
                                        <label class="control-label" ><?php echo JText::_('OS_COUNTRY')?></label>
                                        <div class="controls">
                                            <?php echo $lists['country'];?>
                                        </div>
                                    </div>
                                    <?php }else{
                                        echo $lists['country'];
                                    }
                                    if(!OSPHelper::userOneState()){
                                        ?>
                                    <div class="control-group">
                                        <label class="control-label" ><?php echo JText::_('OS_STATE')?></label>
                                        <div id="country_state" class="controls"><?php echo $lists['state'];?></div>
                                    </div>
                                    <?php }else{ echo $lists['state']; }
                                    if ($configs['search_show_city']){
                                    ?>
                                        <div class="control-group">
                                            <label class="control-label" ><?php echo JText::_(OS_CITY); ?></label>
                                            <div id="city_div" class="controls">
                                                <?php echo $lists['city']; ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="control-group">
                                        <label class="control-label" ><?php echo JText::_('OS_PROPERTY_TYPE')?></label>
                                        <div class="controls">
                                            <?php echo $lists['type'];?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" ><?php echo JText::_('OS_PRICE')?></label>
                                        <div class="controls">
                                            <?php OSPHelper::showPriceFilter('','','',0,'','adv');?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <input class="btn btn-info" type="submit" name="submitform" value="<?php echo JText::_(OS_SEARCH)?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="option" value="<?php echo $option;?>" />
                            <input type="hidden" name="task" value="property_advsearch" />
                            <input type="hidden" name="default_search" value="1" />
                            <input type="hidden" name="Itemid" value="<?php echo $itemid;?>" />
						</form>
					</div>
				</div>
				<?php
				}
				?>
			</div>
			<?php
			}
			
			if(($configClass['show_random_feature']==1) and ($property->id > 0)){

			?>
			<div class="span4">
					<div id="div_dom">
						<a id="a_dom"  href="<?php echo JRoute::_('index.php?option=com_osproperty&task=property_details&id='.$property->id.'&Itemid='.$itemid)?>">
						<?php
						if(file_exists(JPATH_ROOT.'/images/osproperty/properties/'.$property->id.'/thumb/'.$photos[0]->image)){
						?>
							<img id="SlideShow" name="SlideShow" alt="<?php echo OSPHelper::getLanguageFieldValue($property,'pro_name');?>" title="<?php echo OSPHelper::getLanguageFieldValue($property,'pro_name');?>" src="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $property->id?>/medium/<?php echo $photos[0]->image?>" class="img-polaroid img-rounded"/>
						<?php
						}else{
						?>
							<img id="SlideShow" name="SlideShow" alt="<?php echo OSPHelper::getLanguageFieldValue($property,'pro_name');?>" title="<?php echo OSPHelper::getLanguageFieldValue($property,'pro_name');?>" src="<?php echo JURI::root()?>components/com_osproperty/images/assets/nopropertyphoto.png" class="img-polaroid img-rounded"/>
						<?php
						}
						?>
						</a>
					</div>
				<?php
				$photo_str = "";
				$photos = $property->photos;
				if(count($photos) > 0){
					for($i=0;$i<count($photos);$i++){
						if(file_exists(JPATH_ROOT.'/images/osproperty/properties/'.$property->id.'/medium/'.$photos[$i]->image)){						
							$photo_str .= '"'.JURI::root().'/images/osproperty/properties/'.$property->id.'/medium/'.$photos[$i]->image.'",';
						}else{
							$photo_str .= '"'.JURI::root().'components/com_osproperty/images/assets/nopropertyphoto.png",';
						}
					}
					$photo_str = substr($photo_str,0,strlen($photo_str)-1);
					?>
					<script type="text/javascript">
					     var slideShowSpeed = 5000; // 10 seconds per slide
					     // Duration of crossfade (seconds)
					     var crossFadeDuration = 3;
					     // Specify the image files
					     var Pic = new Array(<?php echo $photo_str?>);
					     var t;
					     var p = Pic.length;
					     var preLoad = new Array();
					     for (i = 0; i < p; i++) {
					         preLoad[i] = new Image();
					         preLoad[i].src = Pic[i];
					     }
					     var j = 0;
					     function runSlideShow() {
					         if (document.all) {
					            document.images.SlideShow.style.filter = "blendTrans(duration=2)";
					            document.images.SlideShow.style.filter = "blendTrans(duration=crossFadeDuration)";
					            document.images.SlideShow.filters.blendTrans.Apply();
					         }
					        if (j > (p - 1)) j = 0;
					        document.images.SlideShow.src = preLoad[j].src;
					         if (document.all) {
					            document.images.SlideShow.filters.blendTrans.Play();
					         }
					        j = j + 1;
					        if (j > (p - 1)) j = 0;
					         t = setTimeout('runSlideShow()', slideShowSpeed);
					     }
					     runSlideShow();
					</script>
				<?php } ?>
				<div>
					<table id="listings" style="margin: 5px 10px 0px 10px;border:0px !important;">
					<tr>
						<td valign="top" style="width:30%">
							<span class="field"><?php echo JText::_(OS_LISTING_FOR)?>:</span>
						</td>
						<td style="width: 3px;"></td>
						<td valign="top" width="70%">
							<span class="value"><strong><?php echo $property->type_name?></strong></span>
						</td>
					</tr>
					<tr>
						<td valign="top" style="width:30%">
							<span class="field"><?php echo JText::_(OS_COST)?>:</span>
						</td>
						<td style="width: 3px;"></td>
						<td valign="top" width="70%">
							<span class="value"><strong>
							<?php
							if($property->price > 0){
							?>
							<?php echo OSPHelper::generatePrice($property->curr,$property->price);
							
							}elseif($property->price_call == 1){
								echo JText::_(OS_CALL_FOR_PRICE);
							}
							?>
							</strong></span>
						</td>
					</tr>
					<?php
					if($property->ref != ""){
						?>
						<tr id="sf_field_1_headline">
							<td valign="top">
								<div class="field"><?php echo JText::_("Ref #")?>:</div>
							</td>
							<td style="width: 3px;"></td>
							<td valign="top">
								<div class="value">
									<?php echo $property->ref;?>
								</div>
							</td>
						</tr>
						<?php
					}
					?>
					<tr id="sf_field_1_headline">
						<td valign="top">
							<div class="field"><?php echo JText::_(OS_HEADLINE)?>:</div>
						</td>
						<td style="width: 3px;"></td>
						<td valign="top">
							<div class="value">
								<?php echo OSPHelper::getLanguageFieldValue($property,'pro_name');?>
							</div>
						</td>
					</tr>
					<?php
					if($property->show_address == 1){
					?>
					<tr id="sf_field_1_address">
						<td valign="top">
							<div class="field"><?php echo JText::_(OS_ADDRESS)?>:</div>
						</td>
						<td style="width: 3px;"></td>
						<td valign="top">
							<div class="value">
								 <?php echo OSPHelper::generateAddress($property)?> 
							</div>
						</td>
					</tr>
					<?php
					}
					?>
					<tr>
						<td valign="top">
							<div class="field"><?php echo JText::_(OS_CATEGORY)?>:</div>
						</td>
						<td style="width: 3px;"></td>
						<td valign="top">
							<div class="value">
								
								<?php echo OSPHelper::getCategoryNamesOfPropertyWithLinks($property->id);//echo $property->category_name?>
							</div>
						</td>
					</tr>
					</table>
				</div>
			</div>
			<?php
			}
			?>
		</div>
		<!-- show state, category lists -->
		<script language="javascript">
		function changeColorTab(id,ctab){
			var temp = document.getElementById(id);
			var active_menu = document.getElementById(ctab);
			active_menu_value = active_menu.value;
			
			var temp1 = document.getElementById(active_menu_value);
			temp1.style.backgroundColor = "<?php echo $configClass['homepage_background2']?>";
			var temp2 = document.getElementById("span_" + active_menu_value);
			temp1.style.color = "black";
			var div = document.getElementById('div_' + active_menu_value);
			if(div != null){
				div.style.display = "none";
			}
			
			active_menu.value = id;
			temp.style.backgroundColor = "<?php echo $configClass['homepage_background1']?>";
			var temp2 = document.getElementById("span_" + id);
			temp.style.color = "white";
			var div = document.getElementById('div_' + id);
			if(div != null){
				div.style.display = "block";
			}
		}
		</script>
		<!-- end show state lists -->
		
		<script type="text/javascript">
			var live_site = '<?php echo JURI::root()?>';
			function change_country_state(country_id){
				var url = 'index.php?option=com_osproperty&no_html=1&tmpl=component&task=default_getstate';
				xmlHttp=GetXmlHttpObject();	
				xmlHttp.onreadystatechange = function() {
					if ( xmlHttp.readyState == 4 ) {
						var response = xmlHttp.responseText;
						document.getElementById("country_state").innerHTML = response;					
					}else{
						document.getElementById("country_state").innerHTML = '<img src="' + live_site + 'components/com_osproperty/images/assets/wait.gif"';
						
					}
				}
				xmlHttp.open( "POST", url, true );
				xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');			
				xmlHttp.send('country_id=' + country_id);
			}
			
			function loadCity(state_id,city_id){
				var live_site = '<?php echo JURI::root()?>';
				loadLocationInfoCity(state_id,city_id,'state',live_site);
			}
		</script>
	
		<?php
	}
}

?>
