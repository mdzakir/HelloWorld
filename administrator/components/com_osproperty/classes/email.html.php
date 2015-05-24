<?php
/*------------------------------------------------------------------------
# email.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');


class HTML_OspropertyEmail{
	/**
	 * Extra field list HTML
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $lists
	 */
	function email_list($option,$rows,$pageNav,$lists){
		global $mainframe,$_jversion;		
		JHtml::_('behavior.multiselect');
		JToolBarHelper::title(JText::_('OS_MANAGE_EMAIL_FORMS'),"logo48.png");
		JToolBarHelper::editList('email_edit');
		JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEM'),'email_remove');
		JToolBarHelper::publish('email_publish');
		JToolBarHelper::unpublish('email_unpublish');
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=com_osproperty&task=email_list" name="adminForm" id="adminForm">
		<table  width="100%">
			<tr>
				<td width="100%">
                    <DIV class="btn-wrapper input-append">
                        <input type="text" name="keyword" placeholder="<?php echo JText::_('OS_SEARCH');?>" value="<?php echo JRequest::getVar('keyword','')?>" class="input-medium" />
                        <button class="btn hasTooltip" title="" type="submit" data-original-title="<?php echo Jtext::_('OS_SEARCH');?>">
                            <i class="icon-search"></i>
                        </button>
                    </DIV>
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
						<?php echo JHTML::_('grid.sort',   JText::_('OS_SUBJECT'), 'email_title', @$lists['order_Dir'], @$lists['order'] ,'email_list'); ?>
					</th>
					<th >
						<?php echo JHTML::_('grid.sort',   JText::_('OS_KEY'), 'email_key', @$lists['order_Dir'], @$lists['order'] ,'email_list'); ?>
					</th>
					<th width="10%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_PUBLISH'), 'published', @$lists['order_Dir'], @$lists['order'],'email_list' ); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td width="100%" colspan="5" style="text-align:center;">
						<?php
							echo $pageNav->getListFooter();
						?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			$k = 0;
			for ($i=0, $n=count($rows); $i < $n; $i++) {
				$row = $rows[$i];
				$checked = JHtml::_('grid.id', $i, $row->id);
				$link 		= JRoute::_( 'index.php?option=com_osproperty&task=email_edit&cid[]='. $row->id );
				$published 	= JHTML::_('jgrid.published', $row->published, $i, 'email_');
				
				?>
				<tr class="<?php echo "row$k"; ?>">
				
					<td align="center"><?php echo $pageNav->getRowOffset( $i ); ?></td>
					
					<td align="center" style="text-align:center;"><?php echo $checked; ?></td>
					
					<td align="left"><a href="<?php echo $link; ?>"><?php echo $row->email_title; ?></a></td>
					
					<td align="left"><?php echo $row->email_key ?> </td>
					
					<td align="center" style="text-align:center;"><?php echo $published?></td>
				</tr>
			<?php
				$k = 1 - $k;	
			}
			?>
			</tbody>
		</table>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="email_list" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $lists['order'];?>"  />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir'];?>"  />
		</form>
		<?php
	}
	
	
	/**
	 * Edit Extra field
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 * @param unknown_type $lists
	 */
	function editHTML($option,$row,$lists,$translatable){
		global $mainframe,$_jversion,$languages;
		JRequest::setVar( 'hidemainmenu', 1 );
		JHtml::_('behavior.tooltip');
		if ($row->id){
			$title = ' ['.JText::_('OS_EDIT').']';
		}else{
			$title = ' ['.JText::_('OS_NEW').']';
		}
		JToolBarHelper::title(JText::_('Email').$title);
		JToolBarHelper::save('email_save');
		JToolBarHelper::apply('email_apply');
		JToolBarHelper::cancel('email_cancel');
		?>
		<form method="POST" action="index.php" name="adminForm" id="adminForm" enctype="multipart/form-data">
		
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
		<table width="100%" class="admintable" style="background-color:white;">
			<tr>
				<td class="key"><?php echo JText::_('OS_KEY')?></td>
				<td ><input type="text" name="email_key" id="email_key" disabled="disabled"  size="40" value="<?php echo $row->email_key?>" class="input-large"></td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('OS_SUBJECT'); ?></td>
				<td ><input type="text" name="email_title" id="email_title" size="40" value="<?php echo $row->email_title?>"></td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('OS_PUBLISHED'); ?></td>
				<td ><?php echo $lists['published'];?></td>
			</tr>
			
			<tr>
				<td class="key" valign="top"><?php echo JText::_('OS_CONTENT'); ?></td>
				<td >
					<?php
					$editor = &JFactory::getEditor();
					echo $editor->display( 'email_content',  stripslashes($row->email_content) , '95%', '250', '75', '20' ) ;
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
										<td class="key"><?php echo JText::_('OS_SUBJECT'); ?></td>
										<td ><input type="text" name="email_title_<?php echo $sef; ?>" id="email_title_<?php echo $sef; ?>" size="40" value="<?php echo $row->{'email_title_'.$sef}?>" class="input-large"></td>
									</tr>
									<tr>
										<td class="key" valign="top"><?php echo JText::_('OS_CONTENT'); ?></td>
										<td >
											<?php
											echo $editor->display( 'email_content_'.$sef,  stripslashes($row->{'email_content_'.$sef}) , '95%', '250', '75', '20' ) ;
											?>
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
		<input type="hidden" name="boxchecked" id="boxchecked" value="0" />
		</form>
		
		<script type="text/javascript">
		Joomla.submitbutton = function(pressbutton){
			var form = document.adminForm;
			if (pressbutton == 'email_cancel'){
				submitform( pressbutton );
				return;
			}else if (form.email_title.value == ''){
				alert('<?php echo JText::_('OS_PLEASE_ENTER_SUBJECT'); ?>');
				form.email_title.focus();
				return;
			}else{
				submitform( pressbutton );
				return;
			}
		}
		</script>
		<?php
	}
}
?>