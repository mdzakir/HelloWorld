<?php
/*------------------------------------------------------------------------
# category.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');


class HTML_OspropertyCategories{
	/**
	 * List categories
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $lists
	 */
	function listCategories($option,$rows,$pageNav,$lists,$children){
		global $mainframe,$_jversion;
		JHTML::_('behavior.modal');
		JHtml::_('behavior.multiselect');
		JToolBarHelper::title(JText::_('OS_MANAGE_CATEGORIES'),"logo48.png");
		JToolBarHelper::addNew('categories_add');
		JToolBarHelper::editList('categories_edit');
		JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEM'),'categories_remove');
		JToolBarHelper::publish('categories_publish');
		JToolBarHelper::unpublish('categories_unpublish');
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		
		$db = JFactory::getDBO();
		?>
		<form method="POST" action="index.php?option=com_osproperty&task=categories_list" name="adminForm" id="adminForm">
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
        <?php
        if(count($rows) > 0) {
        ?>
		<table class="adminlist table table-striped" width="100%">
			<thead>
				<tr>
					<th width="2%" style="text-align:center;">
					</th>
					<th width="3%" style="text-align:center;">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th width="5%" style="text-align:center;">
						<?php echo Jtext::_('OS_PHOTO')?>
					</th>
					<th width="40%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_CATEGORY_NAME'), 'category_name', @$lists['order_Dir'], @$lists['order'] ,'categories_list'); ?>
					</th>
					<th width="15%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_ACCESS'), 'access', @$lists['order_Dir'], @$lists['order'] ,'categories_list'); ?>
					</th>
					<th width="10%" style="text-align:center;">
						Entries
					</th>
					<th width="15%" style="text-align:center;">
						
						<?php echo JHTML::_('grid.sort',   JText::_('OS_ORDERING'), 'ordering', @$lists['order_Dir'], @$lists['order'],'categories_list' ); ?>
						<?php echo JHTML::_('grid.order',  $rows ,"filesave.png","categories_saveorder"); ?>
					</th>
					<th width="10%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',  Jtext::_('OS_PUBLISH'), 'published', @$lists['order_Dir'], @$lists['order'] ,'categories_list'); ?>
					</th>
					<th width="5%" style="text-align:center;">
						<?php echo JHTML::_('grid.sort',   'ID', 'id', @$lists['order_Dir'], @$lists['order'] ,'categories_list'); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td width="100%" colspan="10" style="text-align:center;">
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
			for ($i=0, $n=count($rows); $i < $n; $i++) {
				$row = $rows[$i];
				$orderkey = array_search($row->id, $children[$row->parent_id]);
				$checked = JHtml::_('grid.id', $i, $row->id);
				$link 		= JRoute::_( 'index.php?option=com_osproperty&task=categories_edit&cid[]='. $row->id );
				$published 	= JHTML::_('jgrid.published', $row->published, $i , 'categories_');
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center" style="text-align:center;">
						<?php echo $pageNav->getRowOffset( $i ); ?>
					</td>
					<td align="center" style="text-align:center;">
						<?php echo $checked; ?>
					</td>
					<td align="center" style="text-align:center;">
						<?php
						if($row->category_image == ""){
							?>
							<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/noimage.png" style="height:50px;">
							<?php
						}else{
							?>
							<a href="<?php echo JURI::root()?>images/osproperty/category/<?php echo $row->category_image?>" class="modal">
								<img src="<?php echo JURI::root()?>images/osproperty/category/thumbnail/<?php echo $row->category_image?>" style="height:50px;" border="0">
							</a>
							<?php
						}
						?>
					</td>
					<td align="left">
						
						<a href="<?php echo $link?>">
							<?php echo $row->treename;?>
							<?php
							if($_jversion == "1.5"){
								echo $row->category_name;
							}
							?>
						</a>
						<BR />
						(Alias: <?php echo $row->category_alias;?>)
					</td>
					<td align="center" >
						<?php
						switch ($row->access){
							case "0":
								echo JText::_('OS_PUBLIC');
							break;
							case "1":
								echo JText::_('OS_REGISTERED');
							break;
							case "2":
								echo JText::_('OS_SPECIAL');
							break;
						}
						?>
					</td>
					<td align="center" style="text-align:center;">
						<?php
						$db->setQuery("Select count(id) from #__osrs_properties where category_id = '$row->id'");
						echo $db->loadResult();
						?>
					</td>
					<td align="center" class="order">
						<?php
						$ordering = "ordering";
				 		?>
			 			<span><?php echo $pageNav->orderUpIcon($i,$row->parent_id == 0 || $row->parent_id == @$rows[$i-1]->parent_id, 'categories_orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
						<span><?php echo $pageNav->orderDownIcon($i, $n,  $row->parent_id == 0 || $row->parent_id == @$rows[$i+1]->parent_id, 'categories_orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
						<input type="text" name="order[]"  value="<?php echo $row->ordering; ?>" class="text-area-order input-mini" style="text-align: center;width:40px;" />
					</td>
					<td align="center" style="text-align:center;">
						<?php echo $published?>
					</td>
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
        <?php
        }else{
        ?>
            <div class="alert alert-no-items"><?php echo Jtext::_('OS_NO_MATCHING_RESULTS');?></div>
        <?php
        }
        ?>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="categories_list" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
		</form>
		<?php
	}
	
	
	/**
	 * Edit Categories
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 * @param unknown_type $lists
	 */
	function editCategory($option,$row,$lists,$translatable){
		global $mainframe,$languages,$configClass;
		JHTML::_('behavior.modal');
		$db = JFactory::getDBO();
		if($row->id > 0){
			$edit = JText::_('OS_EDIT');
		}else{
			$edit = JText::_('OS_ADD');
		}
		JToolBarHelper::title(JText::_('OS_CATEGORY').JText::_(' ['.$edit.']'));
		JToolBarHelper::save('categories_save');
		JToolBarHelper::apply('categories_apply');
		JToolBarHelper::cancel('categories_gotolist');
		$editor = &JFactory::getEditor();
		?>
		<script language="javascript">
		Joomla.submitbutton = function(task) {
			var form = document.adminForm;
			category_name = form.category_name;
			if((task == "categories_save") || (task == "categories_apply")){
				if(category_name.value == ""){
					alert("<?php echo JText::_('OS_PLEASE_ENTER_CATEGORY_NAME')?>");
					category_name.focus();
				}else{
					Joomla.submitform(task);
				}
			}else{
				Joomla.submitform(task);
			}
		}
		</script>
		
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
		<table  width="100%" class="admintable" style="background-color:white;">
			<tr>
				<td class="key" width="23%">
					<?php echo JText::_('OS_CATEGORY_NAME')?>
				</td>
				<td width="77%">
					<input type="text" name="category_name" id="category_name" size="40" value="<?php echo $row->category_name?>" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php echo JText::_('OS_ALIAS')?>
				</td>
				<td>
					<input type="text" name="category_alias" id="category_alias" size="40" value="<?php echo $row->category_alias?>" />
				</td>
			</tr>
			<tr>
				<td class="key" valign="top">
					<?php echo JText::_('OS_PARENT_CAT')?>
				</td>
				<td>
					<?php echo $lists['parent']?>
				</td>
			</tr>
			<tr>
				<td class="key" valign="top">
					<?php echo JText::_('OS_PHOTO')?>
				</td>
				<td>
					<?php
					if($row->category_image){
						?>
						<a href="<?php echo JURI::root()?>images/osproperty/category/<?php echo $row->category_image?>" class="modal">
						<img src="<?php echo JURI::root()?>images/osproperty/category/thumbnail/<?php echo $row->category_image?>" border="0" />
						</a>
						<BR>
						<input type="checkbox" name="remove_photo" id="remove_photo" value="0" onclick="javascript:changeValue('remove_photo')" /> &nbsp;<b><?php echo JText::_('OS_REMOVE_PHOTO');?></b><BR />
						<?php
					}
					?>
					<input type="file" name="photo" id="photo" size="40" onchange="javascript:checkUploadPhotoFiles('photo')"> (Only allow: *.jpg, *.jpeg)
				</td>
			</tr>
			<tr>
				<td class="key" valign="top">
					<?php echo JText::_('OS_ACCESS')?>
				</td>
				<td>
					<?php echo $lists['access']?>
				</td>
			</tr>
			<tr>
				<td class="key" valign="top">
					<?php echo JText::_('OS_PUBLISH')?>
				</td>
				<td>
					<?php echo $lists['state']?>
				</td>
			</tr>
			<?php
			/*
			if(file_exists(JPATH_ROOT.DS."components".DS."com_oscalendar".DS."oscalendar.php")){
				if($configClass['integrate_oscalendar'] == 1){
					?>
					<tr>
						<td class="key" valign="top">
							<?php echo JText::_('OS_PRICE_TYPE')?>
						</td>
						<td>
							<?php echo $lists['price_type']?> 
							<BR />
							<?php echo JText::_('OS_PRICE_TYPE_EXPLAIN');?>
							<BR />
							<?php echo JText::_('OS_PRICE_TYPE_EXPLAIN1');?>
						</td>
					</tr>
					<?php
				}
			}
			*/
			?>
			<tr>
				<td class="key" valign="top">
					<?php echo JText::_('OS_DESCRIPTION')?>
				</td>
				<td>
					<?php echo $editor->display( 'category_description',  stripslashes($row->category_description) , '60%', '200', '55', '20' ) ; ?>
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
										<td class="key"><?php echo JText::_('OS_CATEGORY_NAME'); ?></td>
										<td >
											<input type="text" name="category_name_<?php echo $sef; ?>" id="category_name_<?php echo $sef; ?>" size="40" value="<?php echo $row->{'category_name_'.$sef}?>" />
										</td>
									</tr>
									<tr>
										<td class="key">
											<?php echo JText::_('OS_ALIAS')?>
										</td>
										<td>
											<input type="text" name="category_alias_<?php echo $sef; ?>" id="category_alias_<?php echo $sef; ?>" size="40" value="<?php echo $row->{'category_alias_'.$sef}?>" />
										</td>
									</tr>
									<tr>
										<td class="key" valign="top">
											<?php echo JText::_('OS_DESCRIPTION')?>
										</td>
										<td>
											<?php echo $editor->display( 'category_description_'.$sef,  stripslashes($row->{'category_description_'.$sef}) , '80%', '250', '75', '20' ) ; ?>
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
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="id" value="<?php echo $row->id?>" />
		<input type="hidden" name="MAX_FILE_SIZE" value="9000000000" />
		</form>
		<?php
	}
}
?>