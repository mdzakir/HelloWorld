<?php
/*------------------------------------------------------------------------
# direction.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class HTML_OspropertyDirection{
	/**
	 * Get Direction
	 *
	 * @param unknown_type $option
	 * @param unknown_type $property
	 */
	static function getDirectionForm($option,$property,$lists,$address,$pro_address){
		global $mainframe,$configClass,$ismobile;
		?>
		<script language="javascript">
		function submitForm(){
			var address = document.getElementById('address');
			if(address.value == ""){
				alert("<?php echo JText::_('OS_PLEASE_ENTER_ADDRESS')?>");
			}else{
				document.ftForm.submit();
			}
		}
		</script>
		<form method="POST" action="<?php echo JRoute::_('index.php?option=com_osproperty&task=direction_map&id='.$property->id.'&Itemid='.JRequest::getInt('Itemid',0))?>" name="ftForm" id="ftForm">
		
		<div class="componentheading">
			<?php
				echo JText::_('OS_GET_DIRECTIONS');
				echo " ";
				echo JText::_('OS_TO');
				echo " ";
				echo OSPHelper::getLanguageFieldValue($property,'pro_name');
			?>
		</div>
		<div class="row-fluid">
			<div class="span11" style="margin-left:0px;">
					<strong>
						<?php
							echo JText::_('OS_ENTER_YOUR_ADDRESS');
						?>
					</strong>
					<BR />
					<input type="text" class="input-large" name="address" id="address" size="30" value="<?php echo $address;?>" />
					&nbsp;&nbsp;&nbsp;
					<strong>
						<?php
							echo JText::_('OS_ROUTE_STYLE');
						?>
					</strong>
					
					<?php echo $lists['routeStyle'];?>
					<input type="button" class="btn  btn-info" value="<?php echo JText::_('OS_GET_DIRECTIONS')?>" onclick="javascript:submitForm();" />
			</div>
			<div class="cleafix"></div>
			<div class="span10">
				<?php
				if($ismobile){
					$width = 100;
				}else{
					$width = 400;
				}
				if($address != ""){
					$param = new stdClass;
					$param->api_key = $configClass['goole_aip_key'];
					$param->width =  400;
					$param->height =  400;
					$param->zoom =  15;
					$param->dir_width = 320;
					$param->header_map = '';
					$param->header_dir = '';
					$param->map_on_right = 1;
					$row->text = '{googleDir width='.$param->width.' height='.$param->height.' '.$mode.'dir_width=275 from="'.$address.'" to="'.$pro_address.'"}' ;
					$plugin = new Plugin_googleDirections($row, $param, $is_mod);
					echo $row->text;
				}
				?>
			</div>
		</div>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="direction_map" />
		<input type="hidden" name="id" id="id" value="<?php echo $property->id?>" />
		<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid')?>" />
		</form>
		<?php
	}
}
?>