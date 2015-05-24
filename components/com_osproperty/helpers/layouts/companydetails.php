<div class="span12">
	<div class="componentheading">
		<?php echo $company->company_name?>
		<?php
		if($configClass['enable_report'] == 1){
			JHTML::_('behavior.modal','a.osmodal');
			$translatable = JLanguageMultilang::isEnabled() && count($languages);
			if($translatable){
				//$langfolder = str_replace("_","",$lang_suffix);
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
			<a href="<?php echo JURI::root()?>index.php?option=com_osproperty&tmpl=component&task=property_reportForm&item_type=2&id=<?php echo $company->id?>" class="osmodal" rel="{handler: 'iframe', size: {x: 300, y: 370}}" title="<?php echo JText::_('OS_REPORT_COMPANY');?>">
	        	<img src="<?php echo $report_image?>" border="0">
	        </a>
			<?php
		}
		?>
	</div>
	<BR />
	<BR />
	<div class="span3">
		<?php
		if($company->photo != ""){
			if(file_exists(JPATH_ROOT.'/images/osproperty/company/'.$company->photo)){
			?>
				<img src='<?php echo JURI::root()?>images/osproperty/company/<?php echo $company->photo?>' border="0"  />
			<?php
			}else{
				?>
				<img src='<?php echo JURI::root()?>components/com_osproperty/images/assets/noimage.png' border="0"  />
				<?php
			}
		}else{
			?>
			<img src='<?php echo JURI::root()?>components/com_osproperty/images/assets/noimage.png' class="img-polaroid"/>
			<?php
		}
		?>
	</div>
	<?php
	//}
	?>
	<div class="span5">
		<?php
		if($company->phone != ""){
		?>
		<div class="agent_phone">
			</strong> <?php echo $company->phone;?>
		</div>
		<BR />
		<?php
		}
		if($company->fax != ""){
		?>
		<div class="agent_fax">
			<?php echo $company->fax;?></a>
		</div>
		<BR />
		<?php
		}
		if($company->email != ""){
		?>
		<div class="agent_email">
			<a href="mailto:<?php echo $company->email;?>"><?php echo $company->email;?></a>
		</div>
		<BR />
		<?php
		}
		if($company->website != ""){
		?>
		
		<div class="agent_website">
			<a href="<?php echo $company->website;?>" target="_blank"><?php echo $company->website;?></a>
		</div>
		<?php } ?>
	</div>
	<div class="span3">
		<?php echo OSPHelper::generateAddress($company);?>
		</div>
	</div>
<div class="clearfix"></div>
<?php
if($company->company_description != ""){
	?>
	<div class="clearfix"></div>
	<?php
	//echo JHtml::_('content.prepare',stripslashes(OSPHelper::getLanguageFieldValue($cat,'category_description')));
	echo JHtml::_('content.prepare',stripslashes($company->{'company_description'.$lang_suffix}));
	?>
<?php
}
?>
<div class="clearfix"></div>