<?php
$relate_columns = $configClass['relate_columns'];
if($relate_columns == ""){
	$relate_columns = 2;
}
if (isset($relates) && count($relates)){
?>
<div class="row-fluid">
	<div class="block_caption span12">
		<strong><?php echo $title;?></strong>
	</div>
</div>
<div class="row-fluid">
<?php
$k = 0;
$span = 12/$relate_columns;
for($i=0;$i<count($relates);$i++){
	$k++;
	$rproperty = $relates[$i];
	?>
		<div class="span<?php echo $span; ?>">
			<div class="row-fluid">
				<div class="span5">
					<a  href="<?php echo JRoute::_("index.php?option=com_osproperty&task=property_details&id=".$rproperty->id."&Itemid=".JRequest::getInt('Itemid',0))?>" title="<?php echo ($rproperty->ref != "")? $rproperty->ref.", ":""?><?php echo OSPHelper::getLanguageFieldValue($rproperty,'pro_name');?>">
						<img alt="<?php echo ($rproperty->ref != "")? $rproperty->ref.", ":""?><?php echo OSPHelper::getLanguageFieldValue($rproperty,'pro_name');?>" title="<?php echo OSPHelper::getLanguageFieldValue($rproperty,'pro_name');?>" src="<?php echo $rproperty->photo?>" class="img-polaroid" />
					</a>
				</div>
				<div class="span7 relate_property">
					<a href="<?php echo JRoute::_("index.php?option=com_osproperty&task=property_details&id=".$rproperty->id."&Itemid=".JRequest::getInt('Itemid',0))?>" title="<?php echo ($rproperty->ref != "")? $rproperty->ref.", ":""?><?php echo OSPHelper::getLanguageFieldValue($rproperty,'pro_name');?>">
						<strong><?php echo ($rproperty->ref != "")? $rproperty->ref.", ":""?><?php echo OSPHelper::getLanguageFieldValue($rproperty,'pro_name');?></strong>
					</a>
					<div class="clearfix"></div>
					<div class="property_description">
						<span class="property_type_name">
							<?php echo OSPHelper::getLanguageFieldValue($rproperty,'type_name'); ?>
						</span>
						<span class="price">
							<?php
							if($rproperty->price_call == 1){ //do nothing
							}else{
								if($rproperty->price > 0){
									echo " <span class='market_price'>".OSPHelper::generatePrice($rproperty->curr,$rproperty->price);
									if($rproperty->rent_time != ""){
										echo "/".JText::_($rproperty->rent_time)."</span>";
									}
								}
							}
							?>
						</span>
						<div class="clearfix"></div>
						<?php 
						if($rproperty->show_address == 1){
							?>
							<div class="property_address">
								<?php 
								echo  OSPHelper::generateAddress($rproperty);
								?>
							</div>
							<?php 
						}
					?>
					</div>
				</div>
			</div>
		</div>
	<?php
		if($k % $relate_columns == 0){
			$k = 0;
			?>
			</div>
			<div class="clearfix" style="height:20px;"></div>
			<div class="row-fluid">
			<?php 
		}
	}	
	?>
</div>
<?php 
 }
?>
<div class="clearfix" style="height:20px;"></div>