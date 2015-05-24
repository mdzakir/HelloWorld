<div class="row-fluid">
	<div class="span12">
		<HR />
		<div class="row-fluid">
			<div style="margin-left:0px;text-align:left;" class="span6"> 
				<strong><?php echo Jtext::_('OS_FILTER');?>:</strong>
				<input type="text" class="input-medium search-query" name="keyword" id="keyword" value="<?php echo OSPHelper::getStringRequest('keyword','')?>" />					
				<input type="submit" value="<?php echo JText::_('OS_SUBMIT')?>" class="btn btn-info" />
			</div>
			<div class="span6 pull-right" style="text-align:right;">
				<?php
				$ordertype = JRequest::getVar('ordertype','asc');
				if($ordertype == "asc"){
					$class1 = "btn btn-info";
					$class2 = "btn btn-warning";
				}else{
					$class2 = "btn btn-info";
					$class1 = "btn btn-warning";
				}
				?>
				<strong><?php echo JText::_('OS_SORT_BY')?>: </strong>
				<a href="javascript:updateOrderType('asc');" class="<?php echo $class1;?>" title="<?php echo Jtext::_('OS_ASC')?>">
					<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/order_down.png">
				</a>
				<a href="javascript:updateOrderType('desc');" class="<?php echo $class2;?>" title="<?php echo Jtext::_('OS_DESC')?>">
					<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/order_up.png">
				</a>
				<input type="hidden" name="ordertype" id="ordertype" value="<?php echo $ordertype?>" />
			</div>
		</div>
		<HR />
		<?php
		if(count($rows) > 0){
		?>
		<div class="latestproperties latestproperties_right" >
			<?php
			for($i=0;$i<count($rows);$i++){
				$row = $rows[$i];
				?>
				<div class="row-fluid">
			    	<div class="row-fluid ospitem-separator">
						<div class="span12">
							<div class="row-fluid">
								<div class="span3" style="margin-left:0px !important;">
									<div id="ospitem-watermark_box">
										<?php
										if($row->photo != ""){
											if(file_exists(JPATH_ROOT.'/images/osproperty/company/'.$row->photo)){
											?>
												<img src='<?php echo JURI::root()?>images/osproperty/company/<?php echo $row->photo?>' border="0"  />
											<?php
											}else{
												?>
												<img src='<?php echo JURI::root()?>components/com_osproperty/images/assets/noimage.png' border="0"  />
												<?php
											}
										}else{
											?>
											<img src='<?php echo JURI::root()?>components/com_osproperty/images/assets/noimage.png' border="0"  />
											<?php
										}
										?>
									</div>
								</div>
								<div class="span9 ospitem-leftpad" style="margin-left:0px !important;">
									<div class="ospitem-leftpad">
										<div class="row-fluid ospitem-toppad">
											<div class="span12">
												<span class="ospitem-propertyprice title-blue">
													<strong><?php echo $row->company_name?></strong>
												</span>
											</div>
										</div>
										
										<?php
										//echo JText::_('OS_EMAIL');
										if($row->email != ""){
											?>
											<i class="osicon-mail"></i>
											<?php 
											echo "&nbsp;";
											echo "&nbsp;";
											echo "<a href='mailto:$row->email'>$row->email</a>";
										}
										if($row->phone != ""){
											?>
											<BR />
											<i class="icon-phone"></i>
											<?php
											
											echo "&nbsp;";
											echo "&nbsp;";
											echo $row->phone;
										}
										
										if($row->fax != ""){
										?>
											<BR />
											<i class="osicon-mobile"></i>
											<?php
											
											echo "&nbsp;";
											echo "&nbsp;";
											echo $row->fax;
										}
										
										if($row->website != ""){
											?>
											<BR />
											<i class="osicon-broadcast"></i>
											<?php
											echo "&nbsp;";
											echo "&nbsp;";
											$website = $row->website; 
											if(substr($website,0,4) == "http"){
												
											}else{
												$website = "http://".$website;
											}
											echo "<a href='$website' target='_blank'>";
											echo $row->website;
											echo "</a>";
											echo "<BR />";
										}
										?>
										<div>
										<i class="osicon-address"></i>
										<?php
										echo OSPHelper::generateAddress($row);
										?>
										</div>
										<BR />
										<?php
										$desc = strip_tags($row->{'company_description'.$lang_suffix},"<BR><a><B>");
										
										if($desc  != ""){
											$descArr = explode(" ",$desc);
											if(count($descArr) > 50){
												for($j=0;$j<=50;$j++){
													echo $descArr[$j]." ";
												}
												echo "...";
											}else{
												echo $desc;
											}
											?>
											<BR /><BR />
										<?php } ?>
										<a href="index.php?option=com_osproperty&task=company_listproperties&id=<?php echo $row->id?>&Itemid=<?php echo JRequest::getInt('Itemid',0)?>" title="<?php echo JText::_('Company properties')?>">
										<?php echo JText::_('OS_LISTING')?> (<?php echo $row->countlisting?>)
										</a>
										&nbsp;&nbsp;|&nbsp;&nbsp;
										<a href="index.php?option=com_osproperty&task=company_info&id=<?php echo $row->id?>&Itemid=<?php echo JRequest::getInt('Itemid',0)?>">
										<?php echo JText::_('OS_COMPANY_INFO')?>
										</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				</div>
				<?php
			}

            if($pageNav->total > $pageNav->limit){
			?>
			<div class="pageNavdiv">
					<?php echo $pageNav->getListFooter();?>
			</div>
            <?php } ?>
		</div>
		<?php
		}
		?>
	</div>
</div>	