<?php
/*------------------------------------------------------------------------
# compare.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class HTML_OspropertyCompare{
	/**
	 * Show comparision Form
	 *
	 * @param unknown_type $option
	 * @param unknown_type $comparisionArr
	 */
	static function showCompareForm($option,$comparisionArr,$configs,$isPrint,$fields){
		global $mainframe,$configClass;
		//calculate percent width
		$c = count($comparisionArr);
		$per_column = round(80/$c);
		
		JHTML::_('behavior.modal','a.osmodal');
		?>
		<script language="javascript">
		function removeProperty(pid){
			var answer = confirm("<?php echo JText::_('OS_DO_YOU_WANT_TO_REMOVE_THE_PROPERTY_OUT_OF_THE_COMPARE_LIST')?>");
			if(answer == 1){
				location.href = "<?php echo JURI::root()?>index.php?option=com_osproperty&task=compare_remove&pid=" + pid + "&Itemid=<?php echo JRequest::getInt('Itemid',0)?>";
			}
		}
		function showPrint(){
			window.open ("<?php echo JURI::root()?>index.php?option=com_osproperty&tmpl=component&task=compare_listing&no_html=1&p=1", "mywindow","status=0,toolbar=0,menubar=0,location=0,width=800,height=600,scrollbars=1,resizable=1");
		}
		</script>
		<div class="row-fluid">
			<div class="span12">
				
					<?php 
					OSPHelper::generateHeading(2,JText::_('OS_COMPARE_LISTINGS'));
					?>
				
				<?php
				if($isPrint == 0){
				?>
				<div class="clearfix"></div>
				<div class="btn-toolbar hidden-phone pull-right">
		            <div class="btn-group">
		                <button type="button" class="btn hasTooltip" title="<?php echo JText::_('OS_PRINT_THIS_PAGE');?>" onclick="javascript:showPrint();">
		                    <i class="osicon-print"></i> <?php echo JText::_('OS_PRINT');?>
		                </button>
		            </div>
		        </div>
				<?php
				}
				?>
				<table  width="100%" class="table table-striped">
					<tr class="row0">
						<td class="compare_title" width="20%" style="background-color:#F8E6D3;">
							<?php 
							echo JText::_("Ref #");
							?>
						</td>
						<?php
						for($i=0;$i<count($comparisionArr);$i++){
							$p = $comparisionArr[$i];
							$property = $p->property;
							$link = "index.php?option=com_osproperty&task=property_details&id=".$property->id."&Itemid=".JRequest::getInt('Itemid',0);
							?>
							<td width="<?php echo $per_column?>%" style="text-align:left;padding-left:5px;background-color:#efefef;font-weight:bold;">
								<a href="<?php echo JRoute::_($link)?>" title="<?php echo OSPHelper::getLanguageFieldValue($property,'pro_name');?>">
								<?php
									echo $property->ref;
								?>
								</a>
								<a href="javascript:removeProperty('<?php echo $property->id?>')" title="<?php echo JText::_(OS_REMOVE_PROPERTY_OUT_OF_THE_COMPARE_LIST)?>">
									<i class="osicon-unpublish"></i>
								</a>
							</td>
							<?php
						}
						?>
					</tr>
					<tr class="row1">
						<td class="compare_title" width="20%" style="background-color:#F8E6D3;">
							<?php 
							echo JText::_('OS_PROPERTY_NAME');
							?>
						</td>
						<?php
						for($i=0;$i<count($comparisionArr);$i++){
							$p = $comparisionArr[$i];
							$property = $p->property;
							$link = JURI::root()."index.php?option=com_osproperty&task=property_details&id=".$property->id."&Itemid=".JRequest::getInt('Itemid',0);
							?>
							<td width="<?php echo $per_column?>%" style="text-align:left;padding-left:5px;background-color:#efefef;font-weight:bold;">
								<a href="<?php echo JRoute::_($link)?>" title="<?php echo $property->pro_name;?>">
								<?php
									echo OSPHelper::getLanguageFieldValue($property,'pro_name');
								?>
								</a>
								<a href="javascript:removeProperty('<?php echo $property->id?>')" title="<?php echo JText::_(OS_REMOVE_PROPERTY_OUT_OF_THE_COMPARE_LIST)?>">
									<i class="osicon-unpublish"></i>
								</a>
							</td>
							<?php
						}
						?>
					</tr>
					<tr class="row0">
						<td class="compare_title" width="20%" valign="top">
							<?php 
							echo JText::_('OS_PHOTO');
							?>
						</td>
						<?php
						for($i=0;$i<count($comparisionArr);$i++){
							$p = $comparisionArr[$i];
							$photo = $p->photo;
							$property = $p->property;
							?>
							<td width="<?php echo $per_column?>%" style="text-align:center;">
								<?php
								if($photo != ""){
								?>
									<?php
									if(file_exists(JPATH_ROOT.DS."images/osproperty/properties/".$property->id."/".$photo->image)){
									?>
										<a href="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $property->id;?>/<?php echo $photo->image?>" class="osmodal">
											<img src="<?php echo JURI::root()?>images/osproperty/properties/<?php echo $property->id;?>/thumb/<?php echo $photo->image?>" width="100" class="img-polaroid" />
										</a>
									<?php }else{ ?>
										<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/nopropertyphoto.png" width="100" class="img-polaroid" />
									<?php
									}
									?>
								<?php }else{ ?>
									<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/nopropertyphoto.png" width="100" class="img-polaroid" />
								<?php
								}
								?>
							</td>
							<?php
						}
						?>
					</tr>
					<tr class="row1">
						<td class="compare_title" width="20%" style="background-color:#F8E6D3;">
							<?php 
							echo JText::_('OS_STATE');
							?>
						</td>
						<?php
						for($i=0;$i<count($comparisionArr);$i++){
							$p = $comparisionArr[$i];
							
							?>
							<td width="<?php echo $per_column?>%" style="text-align:left;padding-left:5px;background-color:#efefef;">
								<?php
								if($p->show_address == 1){
									echo $p->state;
								}else{
									echo "N/A";
								}
								?>
							</td>
							<?php
						}
						?>
					</tr>
					<tr class="row0">
						<td class="compare_title" width="20%" style="background-color:#F8F2EB;">
							<?php 
							echo JText::_('OS_COUNTRY');
							?>
						</td>
						<?php
						for($i=0;$i<count($comparisionArr);$i++){
							$p = $comparisionArr[$i];
							
							?>
							<td width="<?php echo $per_column?>%" style="text-align:left;padding-left:5px;padding-left:5px;background-color:#fff;">
								<?php
								if($p->show_address == 1){
									echo $p->country;
								}else{
									echo "N/A";
								}
								?>
							</td>
							<?php
						}
						?>
					</tr>
					<tr class="row1">
						<td class="compare_title" width="20%" style="background-color:#F8E6D3;">
							<?php 
							echo JText::_('OS_CITY');
							?>
						</td>
						<?php
						for($i=0;$i<count($comparisionArr);$i++){
							$p = $comparisionArr[$i];
							$property = $p->property;
							?>
							<td width="<?php echo $per_column?>%" style="text-align:left;padding-left:5px;background-color:#efefef;">
								<?php
								if($p->show_address == 1){
									echo HelperOspropertyCommon::loadCityName($property->city);
								}else{
									echo "N/A";
								}
								?>
							</td>
							<?php
						}
						?>
					</tr>
					<tr class="row0">
						<td class="compare_title" width="20%" style="background-color:#F8F2EB;">
							<?php 
							echo JText::_('OS_ADDRESS');
							?>
						</td>
						<?php
						for($i=0;$i<count($comparisionArr);$i++){
							$p = $comparisionArr[$i];
							$property = $p->property;
							?>
							<td width="<?php echo $per_column?>%" style="text-align:left;padding-left:5px;background-color:#fff;">
								<?php
								if($p->show_address == 1){
									echo $property->address;
								}else{
									echo "N/A";
								}
								?>
							</td>
							<?php
						}
						?>
					</tr>
					<tr class="row1">
						<td class="compare_title" width="20%" style="background-color:#F8E6D3;">
							<?php 
							echo JText::_('OS_POSTCODE');
							?>
						</td>
						<?php
						for($i=0;$i<count($comparisionArr);$i++){
							$p = $comparisionArr[$i];
							$property = $p->property;
							?>
							<td width="<?php echo $per_column?>%" style="text-align:left;padding-left:5px;background-color:#efefef;">
								<?php
								if($p->show_address == 1){
									echo $property->postcode;
								}else{
									echo "N/A";
								}
								?>
							</td>
							<?php
						}
						?>
					</tr>
					<tr class="row0">
						<td class="compare_title" width="20%" style="background-color:#F8E6D3;">
							<?php 
							echo JText::_('OS_CATEGORY');
							?>
						</td>
						<?php
						for($i=0;$i<count($comparisionArr);$i++){
							$p = $comparisionArr[$i];
							
							?>
							<td width="<?php echo $per_column?>%" style="text-align:left;padding-left:5px;background-color:#efefef;">
								<?php
								echo OSPHelper::getLanguageFieldValue($p,'category_name');
								?>
							</td>
							<?php
						}
						?>
					</tr>
					<tr class="row1">
						<td class="compare_title" width="20%" style="background-color:#F8F2EB;">
							<?php 
							echo JText::_('OS_PROPERTY_TYPE');
							?>
						</td>
						<?php
						for($i=0;$i<count($comparisionArr);$i++){
							$p = $comparisionArr[$i];
							
							?>
							<td width="<?php echo $per_column?>%" style="text-align:left;padding-left:5px;background-color:#fff;">
								<?php
								echo OSPHelper::getLanguageFieldValue($p,'property_type');
								?>
							</td>
							<?php
						}
						?>
					</tr>
					<tr class="row0">
						<td class="compare_title" width="20%" style="background-color:#F8F2EB;">
							<?php 
							echo JText::_('OS_PRICE');
							?>
						</td>
						<?php
						for($i=0;$i<count($comparisionArr);$i++){
							$p = $comparisionArr[$i];
							$property = $p->property;
							?>
							<td width="<?php echo $per_column?>%" style="text-align:left;padding-left:5px;background-color:#fff;">
								<?php
								if($property->price > 0){
									echo OSPHelper::generatePrice($property->curr,$property->price);
									if($property->rent_time != ""){
										echo " /".JText::_($property->rent_time);
									}
								}else{
									echo JText::_(OS_CALL_FOR_PRICE);
								}
								?>
							</td>
							<?php
						}
						?>
					</tr>
					<?php
					if($configClass['use_bedrooms']){
					?>
					<tr class="row1">
						<td class="compare_title" width="20%" style="background-color:#F8E6D3;">
							<?php 
							echo JText::_('OS_BEDROOMS');
							?>
						</td>
						<?php
						for($i=0;$i<count($comparisionArr);$i++){
							$p = $comparisionArr[$i];
							$property = $p->property;
							?>
							<td width="<?php echo $per_column?>%" style="text-align:left;padding-left:5px;background-color:#efefef;">
								<?php
								echo $property->bed_room;
								?>
							</td>
							<?php
						}
						?>
					</tr>
					<?php
					}
					?>
					<?php
					if($configClass['use_bathrooms']){
					?>
					<tr class="row0">
						<td class="compare_title" width="20%" style="background-color:#F8F2EB;">
							<?php 
							echo JText::_('OS_BATHROOMS');
							?>
						</td>
						<?php
						for($i=0;$i<count($comparisionArr);$i++){
							$p = $comparisionArr[$i];
							$property = $p->property;
							?>
							<td width="<?php echo $per_column?>%" style="text-align:left;padding-left:5px;background-color:#fff;">
								<?php
								echo OSPHelper::showBath($property->bath_room);
								?>
							</td>
							<?php
						}
						?>
					</tr>
					<?php
					}
					?>
					<?php
					if($configClass['use_rooms']){
					?>
					<tr class="row1">
						<td class="compare_title" width="20%" style="background-color:#F8E6D3;">
							<?php 
							echo JText::_('OS_ROOMS');
							?>
						</td>
						<?php
						for($i=0;$i<count($comparisionArr);$i++){
							$p = $comparisionArr[$i];
							$property = $p->property;
							?>
							<td width="<?php echo $per_column?>%" style="text-align:left;padding-left:5px;background-color:#efefef;">
								<?php
								echo $property->rooms;
								?>
							</td>
							<?php
						}
						?>
					</tr>
					<?php
					}
					?>
					<?php
					if($configClass['use_squarefeet']){
					?>
					<tr class="row0">
						<td class="compare_title" width="20%" style="background-color:#F8F2EB;">
							<?php 
							echo JText::_('OS_SQUARE_FEET');
							?>
						</td>
						<?php
						for($i=0;$i<count($comparisionArr);$i++){
							$p = $comparisionArr[$i];
							$property = $p->property;
							?>
							<td width="<?php echo $per_column?>%" style="text-align:left;padding-left:5px;background-color:#fff;">
								<?php
								echo $property->square_feet ." m2";
								?>
							</td>
							<?php
						}
						?>
					</tr>
					<?php
					}
					?>
					<?php
					if($configClass['use_nfloors']){
					?>
					<tr class="row1">
						<td class="compare_title" width="20%" style="background-color:#F8E6D3;">
							<?php 
							echo JText::_('OS_FLOORS');
							?>
						</td>
						<?php
						for($i=0;$i<count($comparisionArr);$i++){
							$p = $comparisionArr[$i];
							$property = $p->property;
							?>
							<td width="<?php echo $per_column?>%" style="text-align:left;padding-left:5px;background-color:#efefef;">
								<?php
								echo $property->number_of_floors;
								?>
							</td>
							<?php
						}
						?>
					</tr>
					<?php
					}
					?>
					<tr class="row0">
						<td class="compare_title" width="20%" style="background-color:#F8F2EB;" valign="top">
							<?php 
							echo JText::_('OS_PROPERTY_FEATURE');
							?>
						</td>
						<?php
						for($i=0;$i<count($comparisionArr);$i++){
							$p = $comparisionArr[$i];
							$amenities = $p->amenities;
							?>
							<td width="<?php echo $per_column?>%" style="text-align:left;padding-left:5px;background-color:#fff;">
								<?php
								$tempstr = "";
								for($j=0;$j<count($amenities);$j++){
									$amen = $amenities[$j];
									$tempstr .= OSPHelper::getLanguageFieldValue($amen,'amenities').", ";
								}
								echo substr($tempstr,0,strlen($tempstr)-2);
								?>
							</td>
							<?php
						}
						?>
					</tr>
					<tr class="row1">
						<td class="compare_title" width="20%" style="background-color:#F8E6D3;" valign="top">
							<?php 
							echo JText::_('OS_DESCRIPTION');
							?>
						</td>
						<?php
						for($i=0;$i<count($comparisionArr);$i++){
							$p = $comparisionArr[$i];
							$property = $p->property;
							?>
							<td width="<?php echo $per_column?>%" style="text-align:left;padding-left:5px;background-color:#efefef;" valign="top">
								<?php
									echo OSPHelper::getLanguageFieldValue($property,'pro_small_desc');
								?>
							</td>
							<?php
						}
						?>
					</tr>
					<?php
					if($configClass['show_rating'] == 1){
					?>
					<tr class="row0"> 
						<td class="compare_title" width="20%" style="background-color:#F8E6D3;">
							<?php 
							echo JText::_('OS_RATING');
							?>
						</td>
						<?php
						for($i=0;$i<count($comparisionArr);$i++){
							$p = $comparisionArr[$i];
							$property = $p->property;
							?>
							<td width="<?php echo $per_column?>%" style="text-align:left;padding-left:5px;background-color:#efefef;">
								<?php
								if($property->number_votes > 0){
								$points = round($property->total_points/$property->number_votes);
								?>
								<table  width="100%" style="border:0px !important;">
									<tr>
										<td>
											<?php
											for($j=1;$j<=$points;$j++){
												?>
												<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/star1.png" />
												<?php
											}
											for($j=$points+1;$j<=5;$j++){
												?>
												<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/star2.png" />
												<?php
											}
											?>
										</td>
									</tr>
								</table>
								<?php
								
								
							}else{
								for($j=1;$j<=5;$j++){
									?>
									<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/star2.png" />
									<?php
								}
							}
								?>
							</td>
							<?php
						}
						?>
					</tr>
					<?php
					}
					if(count($fields) > 0){
						$k = 1;
						for($f=0;$f<count($fields);$f++){
							$field = $fields[$f];
							?>
							<tr class="row<?php echo $k?>">
								<td class="compare_title" width="20%" style="background-color:#F8E6D3;" valign="top">
									<?php 
									echo OSPHelper::getLanguageFieldValue($field,'field_label');
									?>
								</td>
								<?php
								for($i=0;$i<count($comparisionArr);$i++){
									$p = $comparisionArr[$i];
									$property = $p->fieldarr;
									?>
									<td width="<?php echo $per_column?>%" style="text-align:left;padding-left:5px;" valign="top">
										<?php
											echo $property[$f]->fieldvalue;
										?>
									</td>
									<?php
								}
								?>
							</tr>
							<?php
							$k = 1-$k;
						}
					}
					?>
				</table>
			</div>
		</div>
		<?php
		if($isPrint == 1){
			?>
			<script language="javascript">
			window.print();
			</script>
			<?php
		}
	}
}
?>