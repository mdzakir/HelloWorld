<?php
/*------------------------------------------------------------------------
# cpanel.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class HTML_OspropertyCsvExport{
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $option
	 * @param unknown_type $lists
	 * @param unknown_type $countries
	 */
	function displayCsvForm($option,$lists,$select_form,$count_csv_forms){
		global $mainframe,$configClass;
		$document = JFactory::getDocument();
		$document->setTitle($configClass['general_bussiness_name']);
		JToolBarHelper::title(JText::_('OS_EXPORT_FORM'),"logo48.png");
		JToolBarHelper::custom('csvexport_proccess','download.png','download.png',JText::_('OS_EXPORTCSV'),false);
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=com_osproperty&task=csvexport_default" name="adminForm" id="adminForm">
			<?php
			if($count_csv_forms == 0){
				?>
				<div class="row-fluid">
					<div class="span12" style="text-align:center;">
						<div class="span3"></div>
						<div class="span6">
							<h3>
								<?php echo JText::_('OS_YOU_MUST_CREATE_CSV_FORM_IF_YOU_WANT_TO_EXPORT_PROPERTIES_TO_CSV');?>
							</h3>
							<div class="clearfix"></div>
							<div class="img-polaroid" style="text-align:left;">
								<?php echo JText::_('OS_YOU_MUST_CREATE_CSV_FORM_IF_YOU_WANT_TO_EXPORT_PROPERTIES_TO_CSV_EXPLAIN');?>
							</div>
							<div class="clearfix"></div>
							<br />
							<a href="index.php?option=com_osproperty&task=form_default" class="btn btn-info"><?php echo JText::_('OS_CREATE_CSV_FORM');?></a>
							<input type="button" onclick="javascript:returnControlPanel();" class="btn btn-warning" value="<?php echo JText::_('OS_I_WILL_CREATE_FORM_LATER');?>" />
						</div>
						<div class="span3"></div>
					</div>
				</div>
				<?php
			}else{
			?>
			<table width="100%" class="admintable">
				<tr>
					<td class="key">
						<?php echo JText::_("OS_EXPORT_SELECT_FORM");?>
					</td>
					<td>
						<?php echo $lists['select_form']?>
					</td>
				</tr>
				<?php if ($select_form && isset($lists['category_id'])):?>
				<tr>
					<td class="key">
						<?php echo JText::_("OS_SELECT_CATEGORY");?>
					</td>
					<td>
						<?php echo $lists['category_id']?>
					</td>
				</tr>
				<?php endif;?>
				<?php if ($select_form && isset($lists['pro_types'])):?>
				<tr>
					<td class="key">
						<?php echo JText::_("OS_SELECT_PROPERTY_TYPES");?>
					</td>
					<td>
						<?php echo $lists['pro_types']?>
					</td>
				</tr>
				<?php endif;?>
				<!--
				<?php if ($select_form && isset($lists['agent_id'])):?>
				<tr>
					<td class="key">
						<?php echo JText::_("OS_SELECT_AGENT");?>
					</td>
					<td>
						<?php echo $lists['agent_id']?>
					</td>
				</tr>
				<?php endif;?>
				-->
				<?php if ($select_form && isset($lists['country'])):?>
				<tr>
					<td class="key">
						<?php echo JText::_("OS_SELECT_COUNTRY");?>
					</td>
					<td>
						<?php echo $lists['country']?>
					</td>
				</tr>
				<?php endif;?>
				<?php if ($select_form && isset($lists['state'])):?>
				<tr>
					<td class="key">
						<?php echo JText::_("OS_SELECT_STATE");?>
					</td>
					<td>
						<?php echo $lists['state']?>
					</td>
				</tr>
				<?php endif;?>
				<?php if ($select_form && isset($lists['city'])):?>
				<tr>
					<td class="key">
						<?php echo JText::_("OS_EXPORT_CITY");?>
					</td>
					<td>
						<?php echo $lists['city']?>
					</td>
				</tr>
				<?php endif;?>
			</table>
			<?php
			}
			?>
			<input type="hidden" name="option" value="<?php echo $option?>" />
			<input type="hidden" name="task" value="csvexport_default" />
			<input type="hidden" name="boxchecked" value="0" />
		</form>
	<?php 
	}
	
	function exportSummary($option,$count_properties,$filecsv, $filename_zip){
		global $mainframe;
		global $mainframe,$configClass;
		$document = JFactory::getDocument();
		$document->setTitle($configClass['general_bussiness_name']);
		JToolBarHelper::title(JText::_('OS_EXPORT_FORM'),"logo48.png");
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=com_osproperty&task=csvexport_default" name="adminForm" id="adminForm">
		<div class="row-fluid">
			<div class="span12" style="text-align:center;">
				<h3>
					<?php echo JText::_('OS_CSV_EXPORT_SUMMARY');?>
				</h3>
				<BR />
				<center>
				<table width="60%" style="border:1px solid #CCC;width:60%;">
					<tr>
						<td width="30%" style="text-align:left;padding:10px;background-color:#efefef;border-bottom:1px solid #CCC;">
							<?php
							echo JText::_('OS_NUMBER_PROPERTIES_EXPORTED');
							?>
						</td>
						<td width="70%" style="text-align:center;padding:10px;border-left:1px solid #CCC;background-color:#efefef;border-bottom:1px solid #CCC;">
							<?php
							echo $count_properties;
							?>
						</td>
					</tr>
					<tr>
						<td width="30%" style="text-align:left;padding:10px;border-bottom:1px solid #CCC;">
							<?php
							echo JText::_('OS_CSV_FILE_URL');
							?>
						</td>
						<td width="70%" style="text-align:left;padding:10px;border-left:1px solid #CCC;border-bottom:1px solid #CCC;">
							<a href="<?php echo $filecsv?>" title="Download CSV file">
								<?php
								echo $filecsv;
								?>
							</a>
							&nbsp;&nbsp;
							[<a href="<?php echo $filecsv?>" title="Download Picture file">
								<i class="icon-download"></i>
							</a>]
						</td>
					</tr>
					<tr>
						<td width="30%" style="text-align:left;padding:10px;background-color:#efefef;">
							<?php
							echo JText::_('OS_PICTURES_EXPORTED_FILE_URL');
							?>
							
						</td>
						<td width="70%" style="text-align:left;padding:10px;border-left:1px solid #CCC;background-color:#efefef;">
							<a href="<?php echo $filename_zip?>" title="Download Picture file">
								<?php
									echo $filename_zip;
								?>
							</a>
							&nbsp;&nbsp;
							[<a href="<?php echo $filename_zip?>" title="Download Picture file">
								<i class="icon-download"></i>
							</a>]
						</td>
					</tr>
				</table>
				</center>
			</div>
		</div>
		<input type="hidden" name="option" value="<?php echo $option?>" />
		<input type="hidden" name="task" value="csvexport_default" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?php
	}
}
?>