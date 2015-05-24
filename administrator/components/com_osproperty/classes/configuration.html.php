<?php
/*------------------------------------------------------------------------
# configuration.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class HTML_OspropertyConfiguration{
	function configurationHTML($option,$configs){
		global $mainframe,$_jversion,$configClass;
	    JHtml::_('behavior.multiselect');
		JToolBarHelper::title(JText::_('OS_CONFIGURATION'),"logo48.png");
		JToolBarHelper::save('configuration_save');
		JToolBarHelper::apply('configuration_apply');
		JToolBarHelper::cancel('configuration_cancel');
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		JHTML::_('behavior.tooltip');
		?>
		<style>
			div.current fieldset {
				border: 1px solid #CCCCCC;
			}
			fieldset label, fieldset span.faux-label {
			    clear: right;
			}
			div.current label, div.current span.faux-label {
			    clear: none;
			    display: block;
			    float: left;
			    margin-top: 1px;
			    min-width: 30px;
			}
		</style>
		<?php
		if (!isset($configs['goole_map_resolution']) || !is_numeric($configs['goole_map_resolution'])){
			$themapres 	= "10";
		} else {
			$themapres 	= $configs['goole_map_resolution'];
		}
		
		$thedeclat 		= $configClass['goole_default_lat'];
		$thedeclong 	= $configClass['goole_default_long'];
		
		if (isset($configs['goole_map_latitude']) && is_float($configs['goole_map_latitude'])){
			$thedeclat = $configs['goole_map_latitude'];
		}
		
		if (isset($configs['goole_map_longitude']) && is_float($configs['goole_map_longitude'])){
			$thedeclong = $configs['goole_map_longitude'];
		} 
		?>
		<form method="POST" action="index.php?option=com_osproperty&task=configuration_list" name="adminForm" id="adminForm" enctype="multipart/form-data">
		<div class="row-fluid">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#general-page" data-toggle="tab"><?php echo JTextOs::_('GENERAL_SETTING');?></a></li>
				<li><a href="#properties" data-toggle="tab"><?php echo JTextOs::_('PROPERTIES');?></a></li>
                <li><a href="#homepage" data-toggle="tab"><?php echo JText::_('OS_LAYOUTS');?></a></li>
				<li><a href="#company" data-toggle="tab"><?php echo JTextOs::_('COMPANY');?></a></li>
				<li><a href="#agent" data-toggle="tab"><?php echo JTextOs::_('AGENT');?></a></li>
				<li><a href="#images" data-toggle="tab"><?php echo JTextOs::_('IMAGES');?></a></li>
				<li><a href="#locator" data-toggle="tab"><?php echo JTextOs::_('SEARCH');?></a></li>
				<?php
				jimport('joomla.filesystem.folder');
	        	if(JFolder::exists(JPATH_ROOT.DS."components".DS."com_osmembership")){
	        		?>
	        		<li><a href="#membership" data-toggle="tab"><?php echo JText::_('MEMBERSHIP');?></a></li>
	        		<?php
	        	}
	        	if(JFolder::exists(JPATH_ROOT.DS."components".DS."com_oscalendar")){
	        		?>
	        		<li><a href="#oscalendar" data-toggle="tab"><?php echo JText::_('OSCALENDAR');?></a></li>
	        		<?php
	        	}
				?>
			</ul>
			<div class="tab-content">	
				<div class="tab-pane active" id="general-page">
					<table  width="100%">
						<tr>
							<td width="50%" valign="top">
								<!--  Business setting -->

								<?php require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'configuration'.DS.'general'.DS.'business.php');?>
                                <?php require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'configuration'.DS.'general'.DS.'offering_paid.php');?>
                                <?php require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'configuration'.DS.'general'.DS.'spam.php');?>
							</td>
							<td valign="top">
                                <?php require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'configuration'.DS.'general'.DS.'layout_of_site.php');?>
								<!--  Currency Setting	-->
								<?php require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'configuration'.DS.'general'.DS.'currency.php');?>
								<!--  End Currency Setting	-->
								<!--  Offering Paid Listings -->
								<!--  Offering Paid Listings -->
                                <?php require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'configuration'.DS.'general'.DS.'management.php');?>
								<!--  Top menu -->
								<?php //require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'configuration'.DS.'general'.DS.'top_menu.php');?>
                                <?php require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'configuration'.DS.'general'.DS.'cron_task.php'); ?>
                                <?php
                                if(file_exists(JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'configuration'.DS.'google_maps'.DS.'google_map.php')) {
                                    require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'configuration' . DS . 'google_maps' . DS . 'google_map.php');
                                }
                                ?>
							</td>
						</tr>
					</table>
				</div>

		       	<div class="tab-pane" id="properties">
                    <?php require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'configuration'.DS.'properties'.DS.'property.php');?>
	       		</div>
                <div class="tab-pane" id="homepage">
                    <?php require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'configuration'.DS.'general'.DS.'homepage.php');?>
                </div>
	       		<div class="tab-pane" id="company">
		       		<table  width="100%">
		       			<tr>
		       				<td>
		       					<!-- 	Fieldset Agent Settings  -->
		       					<?php require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'configuration'.DS.'company'.DS.'company.php');?>
		       					<!-- end Fieldset Agent Settings  -->
		       				</td>
		       			</tr>
		       		</table>
		       	</div>
		       	<div class="tab-pane" id="agent">
		       		<table  width="100%">
		       			<tr>
		       				<td>
		       					<!-- 	Fieldset Agent Settings  -->
		       					<?php require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'configuration'.DS.'agents'.DS.'agent.php');?>
		       					<!-- end Fieldset Agent Settings  -->
		       				</td>
		       			</tr>
		       		</table>
		       	</div>
		       	<div class="tab-pane" id="images">
		       		<table  width="100%">
		       			<tr>
		       				<td>
		       					<!-- 	Fieldset Properties Settings  -->
		       					<?php require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'configuration'.DS.'images'.DS.'image.php');?>
		       					<!-- end Fieldset Agent Settings  -->
		       				</td>
		       			</tr>
		       		</table>
		       	</div>
	       		<div class="tab-pane" id="locator">
	       		<?php
	        	if(file_exists(JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'configuration'.DS.'locator'.DS.'locator.php')) {
                    require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'configuration' . DS . 'locator' . DS . 'locator.php');
                }
	        	?>
	        	</div>
	        	<?php
	        
	        
		        jimport('joomla.filesystem.folder');
		        if(JFolder::exists(JPATH_ROOT.DS."components".DS."com_osmembership")){
			        //echo $pane->startPanel( JTextOs::_('MEMBERSHIP'), 'membership' );
			       	?>
			       	<div class="tab-pane" id="membership">
			       		<table  width="100%">
			       			<tr>
			       				<td>
			       					<!-- 	Fieldset Properties Settings  -->
			       					<?php 
			       					require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'configuration'.DS.'membership'.DS.'membership.php');?>
			       					<!-- end Fieldset Agent Settings  -->
			       				</td>
			       			</tr>
			       		</table>
			       	</div>
			       	<?php 
			       // echo $pane->endPanel();
		        }else{
					$db = Jfactory::getDBO();
					$db->setQuery("Update #__osrs_configuration set fieldvalue = '0' where fieldname like 'integrate_membership'");
					$db->query();
				}
	        
	        
		        if(JFolder::exists(JPATH_ROOT.DS."components".DS."com_oscalendar")){
			        //echo $pane->startPanel( JTextOs::_('OSCALENDAR'), 'oscalendar' );
			       	?>
			       	<div class="tab-pane" id="oscalendar">
			       		<table  width="100%">
			       			<tr>
			       				<td>
			       					<!-- 	Fieldset Properties Settings  -->
			       					<?php require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'configuration'.DS.'calendar'.DS.'calendar.php');?>
			       					<!-- end Fieldset Agent Settings  -->
			       				</td>
			       			</tr>
			       		</table>
			       	</div>
			       	<?php 
			        //echo $pane->endPanel();
		        }
	        //echo $pane->endPane();
	        ?>
	        
        </div>
        </div>
        <input type="hidden" name="option" value="<?php echo $option?>" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="MAX_UPLOAD_SIZE" value="9000000" />
        </form>
        <?php 
	}
}
?>