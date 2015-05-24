<?php
/*------------------------------------------------------------------------
# results.html.tpl.php - Ossolution Property
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
$document->addScript(JURI::root().'components/com_osproperty/templates/'.$themename.'/js/script.js');
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
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
if(!JFolder::exists(JPATH_CACHE.'/osproperty_theme2')){
	JFolder::create(JPATH_CACHE.'/osproperty_theme2');
}
$show_google_map = $params->get('show_map',1);
?>
<div id="listings">
	<?php
	if(!file_exists(JPATH_ROOT.DS.'cache/osproperty_theme2'.DS.'nopropertyphoto.png')){
		JFile::copy(JPATH_ROOT.'/components/com_osproperty/images/assets/nopropertyphoto.png',JPATH_ROOT.DS.'cache/osproperty_theme2'.DS.'nopropertyphoto.png');
		$size 	= getimagesize(JPATH_ROOT.'/cache/osproperty_theme2/nopropertyphoto.png');
		$width 	= $size[0];
		$height = $size[1];
		if($height > 150){
			$tmpimage = new Image(JPATH_ROOT.DS.'/cache/osproperty_theme2'.DS.'nopropertyphoto.png');
			$tmpimage->crop(0,round($height/2-75),$width,round($height/2+75));
			$tmpimage->save(JPATH_ROOT.DS.'/cache/osproperty_theme2'.DS.'nopropertyphoto.png',$configClass['images_quality']);
		}
	}
	
	if(count($rows) > 0){
		jimport('joomla.filesystem.file');
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
			<ul class="listing-nav">
				<li><?php echo JText::_('OS_VIEW_MODE');?>: </li>
				<li><a href="javascript:changeView('listing-grid');" id="listing-grid" class="listing-grid listing-active">Default View</a></li>
				<li><a href="javascript:changeView('listing-full');" class="listing-full" id="listing-full">Compare</a></li>
			</ul>
			<ul class="listing" style="padding-left:0px !important;margin-left:0px !important;">
			<?php
			for($i=0;$i<count($rows);$i++){
				$row = $rows[$i];
				$needs = array();
				$needs[] = "property_details";
				$needs[] = $row->id;
				$itemid = OSPRoute::getItemid($needs);
				$lists['curr'] = JHTML::_('select.genericlist',$currenyArr,'curr'.$i,'onChange="javascript:updateCurrency('.$i.','.$row->id.',this.value)" class="input-small"','value','text');
				
				if($i % 2 == 1){
					$suffix = " last";
				}else{
					$suffix = "";
				}
				
				$db->setQuery("select count(id) from #__osrs_photos where pro_id = '$row->id'");
				$count = $db->loadResult();
				if($count > 0){
					$row->count_photo = $count;
					$db->setQuery("select image from #__osrs_photos where pro_id = '$row->id' order by ordering limit 1");	
					$picture = $db->loadResult();
					
					$row->photo = JURI::root().'cache/osproperty_theme2/'.$picture;
					
					if($picture != ""){
						if(file_exists(JPATH_ROOT.'/images/osproperty/properties/'.$row->id.'/medium/'.$picture)){
							
							//$row->photo = JURI::root().'images/osproperty/properties/'.$row->id.'/medium/'.$picture;
							if(!file_exists(JPATH_ROOT.DS.'cache/osproperty_theme2'.DS.$picture)){
								JFile::copy(JPATH_ROOT.'/images/osproperty/properties/'.$row->id.'/medium/'.$picture,JPATH_ROOT.DS.'cache/osproperty_theme2'.DS.$picture);
								$size 	= getimagesize(JPATH_ROOT.DS.'cache/osproperty_theme2'.DS.$picture);
								$width 	= $configClass['images_large_width'];
								$height = $size[1];
								if($height > 150){
									$tmpimage = new Image(JPATH_ROOT.DS.'cache/osproperty_theme2'.DS.$picture);
									$tmpimage->crop(0,round($height/2-75),$width,round($height/2+75));
									$tmpimage->save(JPATH_ROOT.DS.'cache/osproperty_theme2'.DS.$picture,$configClass['images_quality']);
								}
							}
							$row->photo = JURI::root().'cache/osproperty_theme2/'.$picture;
						}else{
							$row->photo = JURI::root()."cache/osproperty_theme2/nopropertyphoto.png";
						}
					}else{
						$row->photo = JURI::root()."cache/osproperty_theme2/nopropertyphoto.png";
					}
				}else{
					$row->photo = $row->photo = JURI::root()."cache/osproperty_theme2/nopropertyphoto.png";
				}//end photo
				?>
				
				<li class="one_half<?php echo $suffix?>">
					<a title="<?php echo JText::_('OS_PROPERTY_DETAILS');?>" href="<?php echo JRoute::_("index.php?option=com_osproperty&task=property_details&id=".$row->id."&Itemid=".$itemid)?>" style="text-decoration:none;">
							<img alt="<?php echo $row->pro_name?>" title="<?php echo $row->pro_name?>" src="<?php echo $row->photo?>" class="ospitem-imgborder" />
						<h3><span><?php 
						$pro_name = $row->pro_name;
						$pro_name_arr = explode(" ",$pro_name);
						if(count($pro_name_arr) > 5){
							for($j=0;$j<5;$j++)	{
								echo $pro_name_arr[$j]." ";
							}
							echo "...";
						}else{
							echo $row->pro_name;
						}?></span>
						</h3>
						<?php
						if($row->isFeatured == 1){
						?>
						<span class="featured"><?php echo JText::_('OS_FEATURED')?></span>
						<?php
						}
						echo '<span class="price" id="pricevalue" style="display:block;">';
						if($row->price_call == 0){
						?>
						<span class="char1"><?php echo HelperOspropertyCommon::loadCurrency($row->curr);?></span>
						<?php
						$price = HelperOspropertyCommon::showPrice($row->price);
						if(strlen($price) > 0){
							for($j=0;$j<=strlen($price);$j++){
								if(substr($price,$j,1) != " "){
								?>
									<span class="char<?php echo $j + 2?>">
										<?php echo substr($price,$j,1);?>
									</span>
								<?php
								}
							}
							if($row->rent_time != ""){
								echo " /".JText::_($row->rent_time);
							}
						}
						?>
						<?php
						}else{
							echo JText::_('OS_CALL_FOR_PRICE');
						}
						?>
						</span>
						<ul class="listing-info">
							<?php
							if($configClass['listing_show_nbedrooms'] == 1){
								if($row->bed_room > 0){
									//$addInfo[] = $row->bed_room." ".JText::_('OS_BEDROOMS');
									?>
									<li class="listing-info-beds"><?php echo $row->bed_room." ".JText::_('OS_BEDROOMS');?></li>
									<?php
								}
							}
							if($configClass['listing_show_nbathrooms'] == 1){
								if($row->bath_room > 0){
									//$addInfo[] = $row->bath_room." ".JText::_('OS_BATHROOMS');
									?>
									<li class="listing-info-baths"><?php echo $row->bath_room." ".JText::_('OS_BATHROOMS');?></li>
									<?php
								}
							}
							if($configClass['listing_show_nrooms'] == 1){
								if($row->rooms > 0){
									//$addInfo[] = $row->rooms." ".JText::_('OS_ROOMS');
									?>
									<li class="listing-info-area"><?php echo $row->rooms." ".JText::_('OS_ROOMS');?></li>
									<?php
								}
							}
							?>
						</ul>
						<p>
						<?php
						echo strip_tags($row->pro_small_desc);
						?>
						</p>
					</a>
				</li>
			<?php
			}
			?>	
			</ul><!-- /listing -->
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
<script language="javascript">
function changeView(view){
	// Listings
	var view1 = "listing-grid";
	var view2 = "listing-full";
	if(view == view1){
		jQuery("#listing-grid").addClass("listing-active");
		jQuery("#listing-full").removeClass("listing-active");
		jQuery(".listing").removeClass("listing-full");
	}else{
		jQuery("#listing-full").addClass("listing-active");
		jQuery("#listing-grid").removeClass("listing-active");
		jQuery(".listing").addClass("listing-full");
	}
}
</script>