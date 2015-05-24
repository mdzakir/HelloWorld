<?php
/*------------------------------------------------------------------------
# city.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class HTML_OspropertyCity{
	/**
	 * Extra field list HTML
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $lists
	 */
	function city_list($option,$rows,$pageNav,$lists,$modal,$keyword){
		global $mainframe,$_jversion;
		JHtml::_('behavior.multiselect');
		JToolBarHelper::title(JText::_('OS_MANAGE_CITY'),"logo48.png");
		JToolBarHelper::addNew('city_add');
		JToolBarHelper::editList('city_edit');
		JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEM'),'city_remove');
		JToolBarHelper::publish('city_publish');
		JToolBarHelper::unpublish('city_unpublish');
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=com_osproperty&task=city_list" name="adminForm" id="adminForm">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="50%" align="left">
					<?php echo JText::_('OS_COUNTRY')?>: &nbsp;
					<?php echo $lists['country']?>
					&nbsp;&nbsp;&nbsp;
					<?php echo JText::_('OS_STATE')?>: &nbsp;
					<?php echo $lists['states']?>
				</td>
				<td width="50%" align="right">
					<input type="text" class="input-medium search-query" name="keyword" id="keyword" value="<?php echo $keyword;?>" placeholder="<?php echo JText::_('Keyword');?>"/>
					<input type="submit" class="btn btn-warning" value="<?php echo JText::_('OS_SEARCH')?>" />
				</td>
			</tr>
		</table>
		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th width="2%">
				
					</th>
					<th width="3%" style="text-align:center;">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th >
						<?php echo JHTML::_('grid.sort',   JText::_('OS_CITY'), 'a.city', @$lists['order_Dir'], @$lists['order'] ,'city_list'); ?>
					</th>
					<th >
						<?php echo JHTML::_('grid.sort',   JText::_('OS_COUNTRY'), 'b.country_name', @$lists['order_Dir'], @$lists['order'] ,'city_list'); ?>
					</th>
					<th >
						<?php echo JHTML::_('grid.sort',   JText::_('OS_STATE'), 'c.state_name', @$lists['order_Dir'], @$lists['order'] ,'city_list'); ?>
					</th>
					<?php
					if($modal == 0){
					?>
					<th width="10%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_PUBLISH'), 'published', @$lists['order_Dir'], @$lists['order'] ,'city_list'); ?>
					</th>
					<?php } ?>
					<th width="5%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   JText::_('ID'), 'id', @$lists['order_Dir'], @$lists['order'] ,'city_list'); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td width="100%" colspan="7" style="text-align:center;">
						<?php
							echo $pageNav->getListFooter();
						?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			$db = JFactory::getDBO();
			$k = 0;
			$function = JRequest::getVar('function','jSelectCity_city');
			for ($i=0, $n=count($rows); $i < $n; $i++) {
				$row = $rows[$i];
				$checked = JHtml::_('grid.id', $i, $row->id);
				$link 		= JRoute::_( 'index.php?option=com_osproperty&task=city_edit&cid[]='. $row->id );
				$published 	= JHTML::_('jgrid.published', $row->published, $i , 'city_');
				
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
						<?php echo $pageNav->getRowOffset( $i ); ?>
					</td>
					<td align="center" style="text-align:center;">
						<?php echo $checked; ?>
					</td>
					<td align="left" style="padding-left: 10px;">
						<?php
						if($modal == 0){
						?>
							<a href="<?php echo $link; ?>">
						<?php
						}else{
						?>
							<a class="pointer" onclick="if (window.parent) window.parent.<?php echo $function;?>('<?php echo $row->id?>', '<?php echo $row->city; ?>');">
						<?php
						}
						?>
							<?php echo $row->city; ?>
						</a>
					</td>
					<td align="left" style="padding-left: 10px;">
						<?php
						if($modal == 0){
						?>
							<a href="<?php echo $link; ?>">
						<?php
						}
						?>
							<?php echo $row->country_name; ?>
						</a>
					</td>
					<td align="left" style="padding-left: 10px;">
						<?php
						if($modal == 0){
						?>
							<a href="<?php echo $link; ?>">
						<?php
						}
						?>
							<?php echo $row->state_name; ?>
						</a>
					</td>
					<?php
					if($modal == 0){
					?>
					<td align="center" style="text-align:center;">
						<?php echo $published?>
					</td>
					<?php }  ?>
					<td align="center" style="text-align:center;">
						<?php echo $row->id?>
					</td>
				</tr>
			<?php
				$k = 1 - $k;	
			}
			?>
			</tbody>
		</table>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="city_list" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $lists['order'];?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir'];?>" />
		<input type="hidden" name="modal" value="<?php echo $modal?>" />
		<input type="hidden" name="function" value="<?php echo $function?>" />
		<?php
		if($modal == 1){
		?>
		<input type="hidden" name="tmpl" id="tmpl" value="component" />
		<?php
		}
		?>
		</form>
		<?php
	}
	
	/**
	 * Edit city
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 * @param unknown_type $lists
	 */
	function editHTML($option,$row,$lists,$translatable){
		global $mainframe,$languages,$configClass;
		JRequest::setVar( 'hidemainmenu', 1 );
		$db = JFactory::getDBO();
		JHtml::_('behavior.tooltip');
		if ($row->id){
			$title = ' ['.JText::_('OS_EDIT').']';
		}else{
			$title = ' ['.JText::_('OS_NEW').']';
		}
		JToolBarHelper::title(JText::_('OS_CITY').$title);
		JToolBarHelper::save('city_save');
		JToolBarHelper::apply('city_apply');
		JToolBarHelper::cancel('city_cancel');
		
		$editor =& JFactory::getEditor();
		?>
		<script language="javascript">
		var live_site = '<?php echo JURI::root()?>';
		function change_country_agent(country_id,agent_id){
			var url = 'index.php?option=com_osproperty&no_html=1&tmpl=component&task=agent_getstate';
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
			xmlHttp.send('country_id=' + country_id + '&agent_id=' + agent_id);
		}
		</script>
		<form method="POST" action="index.php" name="adminForm" id="adminForm">
		<?php 
		if ($translatable)
		{
		?>
			<ul class="nav nav-tabs">
				<li class="active"><a href="#general-page" data-toggle="tab"><?php echo JText::_('OS_GENERAL'); ?></a></li>
				<li><a href="#translation-page" data-toggle="tab"><?php echo JText::_('OS_TRANSLATION'); ?></a></li>									
			</ul>		
			<div class="tab-content">
				<div class="tab-pane active" id="general-page">			
		<?php	
		}
		?>
			<table cellpadding="0" cellspacing="0" width="100%" class="admintable" style="background-color:white;">
				<tr>
					<td class="key">
						<?php echo JText::_('OS_CITY'); ?>
					</td>
					<td>
						<input type="text" name="city" id="city" size="40" value="<?php echo $row->city?>">
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_('OS_COUNTRY'); ?>
					</td>
					<td>
						<?php echo $lists['country']?>
					</td>
				</tr>
				<tr>
					<td class="key" style="width:20%;">
						<?php echo JText::_('OS_STATE'); ?>
					</td>
					<td style="width:80%;">
						<div id="country_state" style="width:100%;">
							<?php echo $lists['state']?>
						</div>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_('OS_PUBLISHED')?>
					</td>
					<td>
						<?php
						echo $lists['publish'];
						?>
					</td>
				</tr>
			</table>
		<?php 
		if ($translatable)
		{
		?>
		</div>
			<div class="tab-pane" id="translation-page">
				<ul class="nav nav-tabs">
					<?php
						$i = 0;
						foreach ($languages as $language) {						
							$sef = $language->sef;
							?>
							<li <?php echo $i == 0 ? 'class="active"' : ''; ?>><a href="#translation-page-<?php echo $sef; ?>" data-toggle="tab"><?php echo $language->title; ?>
								<img src="<?php echo JURI::root(); ?>media/com_osproperty/flags/<?php echo $sef.'.png'; ?>" /></a></li>
							<?php
							$i++;	
						}
					?>			
				</ul>		
				<div class="tab-content">			
					<?php	
						$i = 0;
						foreach ($languages as $language)
						{												
							$sef = $language->sef;
						?>
							<div class="tab-pane<?php echo $i == 0 ? ' active' : ''; ?>" id="translation-page-<?php echo $sef; ?>">													
								<table width="100%" class="admintable" style="background-color:white;">
									<tr>
										<td class="key">
											<?php echo JText::_('OS_CITY'); ?>
										</td>
										<td>
											<input type="text" name="city_<?php echo $sef; ?>" id="city_<?php echo $sef; ?>" size="40" value="<?php echo $row->{'city_'.$sef}?>">
										</td>
									</tr>
								</table>
							</div>										
						<?php				
							$i++;		
						}
					?>
				</div>	
		</div>
		<?php				
		}
		?>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $row->id?>" />
		</form>
		<script type="text/javascript">
		Joomla.submitbutton = function(pressbutton)
			{
				form = document.adminForm;
				if (pressbutton == 'city_cancel'){
					submitform( pressbutton );
					return;
				}else if (form.city.value == ''){
					alert('<?php echo JText::_('OS_PLEASE_ENTER_CITY'); ?>');
					return;
				}else{
					submitform( pressbutton );
					return;
				}
			}
		</script>
		</form>
		<?php
	}
}

?>