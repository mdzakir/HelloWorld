<div class="componentheading">
	<?php echo $agent->name?>
</div>
<div class="clearfix"></div>
<div class="row-fluid">
	<div class="span12">
		<div class="clearfix"></div>
			<div class="span2">
				<?php
				if(($agent->photo != "") and ($configClass['show_agent_image'] == 1)){
					?>
					<img src='<?php echo JURI::root()?>images/osproperty/agent/<?php echo $agent->photo?>' border="0" />
					<?php
				}else{
					?>
					<img src='<?php echo JURI::root()?>components/com_osproperty/images/assets/noimage.jpg' border="0" />
					<?php
				}
				?>
			</div>
			<div class="span5">
				<strong>
					<?php echo $agent->name?>
					<?php
					if($configClass['enable_report'] == 1){
						JHTML::_('behavior.modal','a.osmodal');
						$translatable = JLanguageMultilang::isEnabled() && count($languages);
						if($translatable){
							$language = Jfactory::getLanguage();
							$language = $language->getTag();
							$language = explode("-",$language);
							$langfolder = $language[0];
							if(file_exists(JPATH_ROOT."/components/com_osproperty/images/assets/".$langfolder."/report.png")){
								$report_image = JURI::root()."components/com_osproperty/images/assets/".$langfolder."/report.png";
							}else{
								$report_image = JURI::root()."components/com_osproperty/images/assets/report.png";
							}
						}else{
							$report_image = JURI::root()."components/com_osproperty/images/assets/report.png";
						}
						?>
						<a href="<?php echo JURI::root()?>index.php?option=com_osproperty&tmpl=component&task=property_reportForm&item_type=1&id=<?php echo $agent->id?>" class="osmodal" rel="{handler: 'iframe', size: {x: 300, y: 370}}" title="<?php echo JText::_('OS_REPORT_AGENT');?>">
				        	<img src="<?php echo $report_image?>" border="0">
				        </a>
						<?php
					}
					?>
				</strong>
				<BR />
				<?php
				if($agent->company_name != ""){
				?>
				<?php echo JText::_('OS_COMPANY');?>: &nbsp;
				<a href="<?php echo JRoute::_('index.php?option=com_osproperty&task=company_info&id='.$agent->company_id.'&Itemid='.JRequest::getInt('Itemid',0))?>">
					<?php echo $agent->company_name;?>
				</a>
				<BR />
				<?php
				}
				?>
				<BR />
				<?php
				if(($configClass['show_agent_phone'] == 1) and ($agent->phone != "")){
				?>
					<div class="agent_phone">
						<strong><?php echo JText::_('OS_PHONE')?>:</strong> <?php echo $agent->phone;?>
					</div>
					<BR />
				<?php
				}
				?>
				<?php
				if(($configClass['show_agent_mobile'] == 1) and ($agent->mobile != "")){
				?>
					<div class="agent_mobile">
						<strong><?php echo JText::_('OS_MOBILE')?>:</strong> <?php echo $agent->mobile;?>
					</div>
					<BR />
				<?php
				}
				?>
				<?php
				if(($configClass['show_agent_fax'] == 1) and ($agent->fax != "")){
				?>
					<div class="agent_fax">
						<strong><?php echo JText::_('OS_FAX')?>:</strong> <?php echo $agent->fax;?></a>
					</div>
					<BR />
				<?php
				}
				?>
				<?php
				if(($configClass['show_agent_email'] == 1) and ($agent->email != "")){
				?>
					<div class="agent_email">
						<strong><?php echo JText::_('OS_EMAIL')?>:</strong> <a href="mailto:<?php echo $agent->email;?>"><?php echo $agent->email;?></a>
					</div>
					<BR />
				<?php
				}
				?>
				<?php
				if(($configClass['show_agent_yahoo'] == 1) and ($agent->yahoo != "")){
				?>
					<div class="agent_yahoo">
						<strong><?php echo JText::_('Yahoo')?>:</strong> <?php echo $agent->yahoo;?>
					</div>
					
					<BR />
				<?php
				}
				?>
				<?php
				if(($configClass['show_agent_skype'] == 1) and ($agent->skype != "")){
				?>
					<div class="agent_skype">
						<strong><?php echo JText::_('Skype')?>:</strong> <?php echo $agent->skype;?>
					</div>
					<BR />
				<?php
				}
				?>
				<?php
				if(($configClass['show_agent_msn'] == 1) and ($agent->msn != "")){
				?>
					<div class="agent_msn">
						<strong><?php echo JText::_('MSN')?>:</strong> <?php echo $agent->msn;?>
					</div>
					<BR />
				<?php
				}
				?>
				<?php
				if(($configClass['show_agent_gtalk'] == 1) and ($agent->gtalk != "")){
				?>
					<div class="agent_gtalk">
						<strong><?php echo JText::_('GTalk')?>:</strong> <?php echo $agent->gtalk;?>
					</div>
					<BR />
				<?php
				}
				?>
				<?php
				if(($configClass['show_agent_facebook'] == 1) and ($agent->facebook != "")){
				?>
					<div class="agent_facebook">
						<strong><?php echo JText::_('Facebook')?>:</strong> <a href="<?php echo $agent->facebook;?>" target="_blank"><?php echo $agent->facebook;?></a>
					</div>
					<BR />
				<?php
				}
				?>
			</div>
			<div class="span4">
				<?php
				if($configClass['show_agent_address'] == 1){
					$address = OSPHelper::generateAddress($agent);
					if($address != ""){
						echo "<font style='font-size:11px;'>".JText::_('OS_ADDRESS').": ".$address."</font>";
					}
				}
				echo "<BR /><BR />";
				if($configClass['show_agent_contact'] == 1){
					?>
					<!--<a href=""><?php echo JText::_('OS_CONTACT_AGENT')?></a> -->
					<?php
				}
				
				if(($configClass['show_license'] == 1) and ($agent->license != "")){
					
					?>
					<div>
						<?php
						echo "<strong>".JText::_('OS_LICENSE').": </strong>";
						echo $agent->license;
						?>
					</div>
					<?php
				}
				?>
				<?php
				if(($configClass['show_company_details'] == 1) and ($agent->company_id > 0)){
				?>
					<div>
						<?php
						echo "<strong>".JText::_('OS_COMPANY').": </strong>";
						$link = JRoute::_('index.php?option=com_osproperty&task=company_info&id='.$agent->company_id);
						?>
						<span class="hasTip" title="&lt;img src=&quot;<?php echo $agent->company_photo;?>&quot; alt=&quot;<?php echo str_replace("'","",$agent->company_name);?>&quot; width=&quot;100&quot; /&gt;">
							<i class="osicon-camera"></i>
						</span>
						&nbsp;|&nbsp;
						<?php
						echo "<a href='".$link."' title='".$agent->company_name."'>".$agent->company_name."</a>";
						?>
					</div>
				<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<?php
$bio = OSPHelper::getLanguageFieldValue($agent,'bio');
if($bio != ""){
?>

<div class="row-fluid">
	<div class="span12">
		<?php
		echo stripslashes($bio);
		?>
	</div>						
</div>
<div class="clearfix"></div>
<?php } ?>