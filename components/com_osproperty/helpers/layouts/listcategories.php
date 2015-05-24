<form method="POST" action="<?php echo JRoute::_('index.php?option=com_osproperty&task=category_listing&Itemid='.JRequest::getInt('Itemid',0))?>" name="ftForm">
<div class="row-fluid">
	<div class="span12">
		<?php
		OSPHelper::generateHeading(2,JText::_('OS_LIST_CATEGORIES'));
		$number_column = $configClass['category_layout'];
		$widthcount	   = round(12/$number_column);
		?>
		<div class="row-fluid">
		<?php
		$j = 0;
		for($i=0;$i<count($rows);$i++){
			$j++;
			$row = $rows[$i];
			$link = JRoute::_('index.php?option=com_osproperty&task=category_details&id='.$row->id.'&Itemid='.JRequest::getInt('Itemid',0));
			$category_name = OSPHelper::getLanguageFieldValue($row,'category_name');
			$category_description = OSPHelper::getLanguageFieldValue($row,'category_description');
			?>
			<div class="span<?php echo $widthcount;?>" style="margin-left:0px !important;">
				<BR />
				<strong>
				<a href="<?php echo $link?>" title="<?php echo JText::_('OS_CATEGORY_DETAILS')?>">
					<?php echo $category_name?> (<?php echo $row->nlisting?>)
				</a>
				<?php
				if($configClass['active_rss'] == 1){
					?>
					<a href="<?php echo JURI::root()?>index.php?option=com_osproperty&task=property_exportrss&category_id=<?php echo $row->id?>&format=feed" target="_blank" title="<?php echo JText::_('OS_RSS_FEED_OF_THIS_CATEGORY');?>">
						<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/feed.png" width="12" border="0"/>
					</a>
					<?php
				}
				?>
				</strong>
				<?php
				if($configClass['categories_show_description'] == 1){
					//show description
					echo "<BR /><BR />";
					?>
					<div style="width:100%;">
						<div style="float:left;margin-right:5px;">
							<a href="<?php echo $link?>" title="<?php echo JText::_('OS_CATEGORY_DETAILS')?>">
							<?php
							if($row->category_image == ""){
								?>
								<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/noimage.png" style="height:80px;" class="img-rounded"/>
								<?php
							}else{
								?>
									<img src="<?php echo JURI::root()?>images/osproperty/category/thumbnail/<?php echo $row->category_image?>" style="height:80px;" class="img-rounded" />
								
								<?php
							}
							?>
							</a>
						</div>
						<?php
						$desc = strip_tags(stripslashes($category_description));
						$descArr = explode(" ",$desc);
						if(count($descArr) > 20){
							for($k=0;$k<20;$k++)	{
								echo $descArr[$k]." ";
							}
							echo "...";
						}else{
							echo $desc;
						}
						?>
					</div>
					<?php
				}
				?>
			</div>
			<?php
			if($j == $number_column){
				?>
				<div class="clearfix"></div>
				<?php 
				$j = 0;
			}
		}
		?>
		</div>
		<?php
		if($pageNav->total > $pageNav->limit){
		?>
		<div class="clearfix"></div>
		<div class="row-fluid">
			<div class="span12">
				<?php
					echo $pageNav->getListFooter();
				?>
			</div>
		</div>
		<?php
		}
		
		?>
	</div>
</div>
</form>