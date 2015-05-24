<?php
/*------------------------------------------------------------------------
# agent.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class HTML_OspropertyAgent{
	/**
	 * Agent layout
	 *
	 * @param unknown_type $option
	 */
	static function agentLayout($option,$rows,$pageNav,$alphabet,$rows1,$lists){
		global $mainframe,$configClass,$ismobile;
		$db = JFactory::getDbo();
		JHTML::_('behavior.modal');
		$page = JRequest::getVar('page','');
		if(($page == "") or ($page == "alb")){
			$class1 = "class = 'active'";
			$class2 = "";
			$div1   = " active";
			$div2   = "";
		}else{
			$class2 = "class = 'active'";
			$class1 = "";
			$div2   = " active";
			$div1   = "";
		}
		?>
		<div class="row-fluid">
			<div class="span12" >
			<?php 
			OSPHelper::generateHeading(2,JText::_('OS_LIST_AGENTS'));
			?>
			<div class="clearfix"></div>
			<div class="span12" style="margin-left:0px;">
				<div class="row-fluid">
					<ul class="nav nav-tabs">
						<li <?php echo $class1?>><a href="#panel1" data-toggle="tab"><?php echo Jtext::_('OS_ALPHABIC');?></a></li>
                        <?php if($configClass['show_agent_search_tab'] == 1){ ?>
						    <li <?php echo $class2?>><a href="#panel2" data-toggle="tab"><?php echo Jtext::_('OS_SEARCH');?></a></li>
                        <?php } ?>
					</ul>
				<div class="tab-content">	
				<div class="tab-pane<?php echo $div1;?>" id="panel1">
				<form method="POST" action="<?php echo JRoute::_('index.php?option=com_osproperty&task=agent_layout&Itemid='.JRequest::getInt('Itemid',0))?>" name="ftForm" id="ftForm">
					<?php
					jimport('joomla.filesystem.file');
					if(JFile::exists(JPATH_ROOT.'/templates/'.$mainframe->getTemplate().'/html/com_osproperty/layouts/agentslist.php')){
						$tpl = new OspropertyTemplate(JPATH_ROOT.'/templates/'.$mainframe->getTemplate().'/html/com_osproperty/layouts/');
					}else{
						$tpl = new OspropertyTemplate(JPATH_COMPONENT.'/helpers/layouts/');
					}
					$tpl->set('mainframe',$mainframe);
					$tpl->set('lists',$lists);
					$tpl->set('option',$option);
					$tpl->set('configClass',$configClass);
					$tpl->set('rows',$rows);
					$tpl->set('pageNav',$pageNav);
					$tpl->set('alphabet',$alphabet);
					$body = $tpl->fetch("agentslist.php");
					echo $body;
					?>
				<input type="hidden" name="option" value="com_osproperty" />
				<input type="hidden" name="task" value="agent_layout" />
				<input type="hidden" name="alphabet" id="alphabet" value="" />
				<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid',0)?>" />
				<input type="hidden" name="page" value="alb" />
				<input type="hidden" name="usertype" id="usertype" value="<?php echo $lists['agenttype']?>" />
				</form>
				</div>
                <?php if($configClass['show_agent_search_tab'] == 1){ ?>
                    <div class="tab-pane<?php echo $div2; ?>" id="panel2">
                        <?php
                        if ($ismobile) {
                            $sp = "<BR />";
                        } else {
                            $sp = '</td><td width="80%" align="left" style="padding:3px;">';
                        }
                        $db = JFactory::getDbo();
                        ?>
                        <form method="POST"
                              action="<?php echo JRoute::_('index.php?option=com_osproperty&view=lagents&Itemid=' . JRequest::getInt('Itemid', 0)) ?>"
                              name="ftForm1" id="ftForm1">

                            <div class="block_caption">
                                <strong><?php echo JText::_('OS_ADV_SEARCH') ?></strong>
                            </div>

                            <div class="row-fluid">
                                <div class="span12">
                                    <div align="clearfix"></div>
                                    <?php
                                    if (HelperOspropertyCommon::checkCountry()) {
                                        ?>
                                        <div class="span4">
                                            <?php echo JText::_('OS_COUNTRY') ?>:
                                        </div>
                                        <div class="span4">
                                            <?php echo $lists['country']; ?>
                                        </div>
                                    <?php
                                    } else {
                                        echo $lists['country'];
                                    }
                                    ?>
                                    <div class="clearfix"></div>
                                    <?php
                                    if (OSPHelper::userOneState()) {
                                        echo $lists['state'];
                                    } else {
                                        ?>
                                        <div class="span4">
                                            <?php echo JText::_('OS_STATE') ?>:
                                        </div>
                                        <div class="span7">
                                            <div id="country_state">
                                                <?php echo $lists['state']; ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="clearfix"></div>
                                    <div class="span4">
                                        <?php echo JText::_('OS_CITY'); ?>:
                                    </div>
                                    <div class="span7">
                                        <div id="city_div">
                                            <?php echo $lists['city']; ?>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="span4">
                                        <?php echo JText::_('OS_ADDRESS'); ?>:
                                    </div>
                                    <div class="span7">
                                        <input type="text" name="address" id="address"
                                               value="<?php echo OSPHelper::getStringRequest('address', '') ?>"
                                               class="input-large" size="30"/>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="span4">
                                        <?php echo JText::_('OS_RADIUS') ?>
                                    </div>
                                    <div class="span7">
                                        <?php echo $lists['radius'] ?>
                                    </div>
                                </div>
                            </div>

                            <input class="btn btn-info" value="<?php echo JText::_('OS_SEARCH') ?>" type="submit"
                                   id="submit"/>
                            <BR/>
                            <?php
                            if (count($rows1) > 0) {
                                ?>
                                <BR/>
                                <div class="block_caption">
                                    <strong><?php echo JText::_('OS_SEARCH_RESULT') ?></strong>
                                    &nbsp;&nbsp;
                                    <?php
                                    echo JText::_('OS_RESULTS_AGENTS_FOR');
                                    if (JRequest::getVar('address', '') != "") {
                                        echo " " . OSPHelper::getStringRequest('address', '', '');
                                    }
                                    if (JRequest::getInt('city', 0) > 0) {
                                        echo " " . HelperOspropertyCommon::loadCityName(JRequest::getInt('city', 0));
                                    }
                                    if (JRequest::getInt('state_id', 0) > 0) {
                                        $db->setQuery("Select state_name from #__osrs_states where id = '" . JRequest::getInt('state_id', 0) . "'");
                                        echo ", " . $db->loadResult();
                                    }

                                    if (!HelperOspropertyCommon::checkCountry()) {
                                        $db->setQuery("Select country_name from #__osrs_countries where id = '" . $configClass['show_country_id'] . "'");
                                        echo ", " . $db->loadResult();
                                    } elseif (JRequest::getInt('country_id', 0) > 0) {
                                        $db->setQuery("Select country_name from #__osrs_countries where id = '" . JRequest::getInt('country_id', 0) . "'");
                                        echo ", " . $db->loadResult();
                                    }
                                    if (OSPHelper::getStringRequest('address', '', '') != "") {
                                        echo " <strong>" . JText::_('OS_DISTANCE') . ": </strong>";
                                        echo JRequest::getVar('distance', '') . " " . JText::_('OS_MILES');
                                    }
                                    ?>
                                </div>
                                <?php
                                if ($lists['show_over'] == 1) {
                                    ?>
                                    <div style="padding-bottom:5px;color:gray;">
                                        <?php
                                        printf(JText::_('OS_OVER_RESULTS'), 24);
                                        ?>
                                    </div>
                                <?php
                                }
                                ?>
                                <div class="clearfix"></div>
                                <div class="row-fluid">
                                    <div class="span7">
                                        <table style="border:0px !important;" width="100%" class="admintable">
                                            <?php
                                            $link = JURI::root() . "components/com_osproperty/images/assets/";
                                            for ($i = 0; $i < count($rows1); $i++) {
                                                $row = $rows1[$i];
                                                ?>
                                                <tr>
                                                    <td width="100%"
                                                        style="padding-top:5px;padding-bottom:5px;border:0px;border-bottom:1px dotted #CCC !important;">
                                                        <table width="100%">
                                                            <tr>
                                                                <td align="left" width="80%" valign="top">
                                                                    <div>
                                                                        <div style="float:left;width:38px;height:40px;">
                                                                            <img
                                                                                src="<?php echo JURI::root() ?>components/com_osproperty/images/assets/mapicon/i<?php echo $i + 1 ?>.png"/>
                                                                        </div>
                                                                        <strong>
                                                                            <u><?php echo $row->name ?></u>
                                                                        </strong>
                                                                        <?php
                                                                        if ($configClass['show_agent_address'] == 1) {
                                                                            ?>
                                                                            <BR/>
                                                                            <font style="color:gray;">
                                                                                <?php
                                                                                echo OSPHelper::generateAddress($row);
                                                                                ?>
                                                                            </font>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                    <?php
                                                                    if ($configClass['show_agent_email'] == 1) {
                                                                        ?>
                                                                        <img src="<?php echo $link ?>mail.png"/>
                                                                        <?php
                                                                        echo "&nbsp;";
                                                                        echo "&nbsp;";
                                                                        echo "<a href='mailto:$row->email'>$row->email</a>";
                                                                        echo "<BR />";
                                                                    }
                                                                    if (($configClass['show_agent_phone'] == 1) and ($row->phone != "")) {
                                                                        ?>
                                                                        <img src="<?php echo $link ?>phone16.png"
                                                                             width="16"/>
                                                                        <?php
                                                                        echo "&nbsp;";
                                                                        echo "&nbsp;";
                                                                        echo $row->phone;
                                                                        echo "<BR />";
                                                                    }
                                                                    if (($configClass['show_agent_mobile'] == 1) and ($row->mobile != "")) {
                                                                        ?>
                                                                        <img src="<?php echo $link ?>mobile16.png"
                                                                             width="16"/>
                                                                        <?php
                                                                        echo "&nbsp;";
                                                                        echo "&nbsp;";
                                                                        echo $row->mobile;
                                                                        echo "<BR />";
                                                                    }
                                                                    ?>

                                                                    <BR/>
                                                                    <a href="index.php?option=com_osproperty&task=agent_info&id=<?php echo $row->id ?>&Itemid=<?php echo JRequest::getInt('Itemid', 0) ?>">
                                                                        <?php echo JText::_('OS_LISTING') ?>
                                                                        (<?php echo $row->countlisting ?>)
                                                                    </a>
                                                                    &nbsp;&nbsp;|&nbsp;&nbsp;
                                                                    <a href="index.php?option=com_osproperty&task=agent_info&id=<?php echo $row->id ?>&Itemid=<?php echo JRequest::getInt('Itemid', 0) ?>">
                                                                        <?php echo JText::_('OS_VIEW_AGENCY_PROFILE') ?>
                                                                    </a>
                                                                </td>
                                                                <td align="right" valign="top" class="hidden-phone">
                                                                    <div class="agent_photo"
                                                                         style="width: 90px; margin-bottom: 10px;">
                                                                        <?php
                                                                        if ($row->photo != "") {
                                                                            ?>
                                                                            <img
                                                                                src='<?php echo JURI::root() ?>images/osproperty/agent/thumbnail/<?php echo $row->photo ?>'
                                                                                border="0" width="90"/>
                                                                        <?php
                                                                        } else {
                                                                            ?>
                                                                            <img
                                                                                src='<?php echo JURI::root() ?>components/com_osproperty/images/assets/noimage.jpg'
                                                                                border="0" width="90"/>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </table>
                                    </div>
                                    <div class="span5">
                                        <!-- Google map -->
                                        <div id="mapCont">
                                            <div id="mapHeader">
                                                <div class="expandMap"><?php echo JText::_('OS_MAP') ?></div>
                                                <div class="clearer"></div>
                                            </div>
                                            <div id="mapWrapper">
                                                <?php
                                                $geocode = array();
                                                for ($j = 0; $j < count($rows1); $j++) {
                                                    $geocode[$j]->text = $rows1[$j]->name;
                                                    $geocode[$j]->lat = $rows1[$j]->lat;
                                                    $geocode[$j]->long = $rows1[$j]->long;
                                                }
                                                HelperOspropertyGoogleMap::loadGoogleMap($geocode, "map_canvas", "agent");
                                                ?>
                                                <body onload="initialize();">
                                                <div id="map_canvas" style="width: 100%; height: 250px"></div>
                                                </body>
                                            </div>
                                            <div id="mapFooter">
                                                <div class="legendWrapper">
                                                    <img src="<?php echo $link ?>workoffice.png" width="25"/>
                                                    <?php echo JText::_("OS_AGENT_OFFICE") ?>
                                                </div>
                                                <div class="corner"></div>
                                                <div class="corner rightCorner"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            <input type="hidden" name="option" value="com_osproperty"/>
                            <input type="hidden" name="task" value="agent_layout"/>
                            <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid', 0) ?>"/>
                            <input type="hidden" name="page" value="adv"/>
                            <input type="hidden" name="usertype" id="usertype"
                                   value="<?php echo $lists['agenttype'] ?>"/>
                        </form>
                        </div>
                    <?php
                    }
				    ?>
                    </div>
				</div>
			</div>
		</div>
		</div>
		<script language="javascript">
		function change_country_company(country_id,state_id,city_id){
			var live_site = '<?php echo JURI::root()?>';
			loadLocationInfoStateCityLocator(country_id,state_id,city_id,'country','state_id',live_site);
		}
		function change_state(state_id,city_id){
			var live_site = '<?php echo JURI::root()?>';
			loadLocationInfoCity(state_id,city_id,'state_id',live_site);
		}
		</script>
		<?php
	}
	
	
	function showMostViewProperties($option,$rows){
		global $mainframe;
		?>
		<table  width="100%">
			<tr>
				<td width="50%">
				
				</td>
				<td width="50%" class="header_mostview">
					<?php
					echo JText::_('OS_MOST_VIEW');
					?>
				</td>
			</tr>
			<tr>
				<td width="100%" colspan="2" style="border-top:1px solid #CCC;">
					<table  width="100%">
						<tr>
							<td width="70%" class="header_td">
								<?php
									echo JText::_('OS_PROPERTIES');
								?>
							</td>
							<td width="30%" class="header_td">
								<?php
									echo JText::_('OS_HITS');
								?>
							</td>
						</tr>
						<?php
						for($i=0;$i<count($rows);$i++){
							$row = $rows[$i];
							$needs = array();
							$needs[] = "property_details";
							$needs[] = $row->id;
							$itemid = OSPRoute::getItemid($needs);
							
							$link = JRoute::_('index.php?option=com_osproperty&task=property_details&id='.$row->id.'&Itemid='.$itemid);
							?>
							<tr>
								<td style="padding:3px;padding-left:10px;text-align:left;border-bottom:1px dotted #CCC !important;">
									<a href="<?php echo $link?>" title="<?php echo JText::_('OS_PROPERTY_DETAILS')?>">
										<?php
										if($row->ref != ""){
											?>
											(<?php
											echo $row->ref;
											?>)
											<?php
										}
										?>
										<?php
										echo OSPHelper::getLanguageFieldValue($row,'pro_name');
										?>
									</a>
									
								</td>
								<td style="text-align:center;border-bottom:1px dotted #CCC !important;">
									<?php
									echo $row->hits;
									?>
								</td>
							</tr>
							<?php
						}
						?>
					</table>
				</td>
			</tr>
		</table>
		<?php
	}
	
	static function showMostRatedProperties($option,$rows){
		global $mainframe;
		?>
		<table  width="100%">
			<tr>
				
				<td width="50%" class="header_mostrated">
					<?php
					echo JText::_('OS_MOST_RATED')
					?>
				</td>
				<td width="50%">
				
				</td>
			</tr>
			<tr>
				<td width="100%" colspan="2" style="border-top:1px solid #CCC;">
					<table  width="100%">
						<tr>
							<td width="60%" class="header_td">
								<?php
									echo JText::_('OS_PROPERTIES');
								?>
							</td>
							<td width="40%" class="header_td">
								<?php
									echo JText::_('OS_RATED');
								?>
							</td>
						</tr>
						<?php
						for($j=0;$j<count($rows);$j++){
							$row = $rows[$j];
							$needs = array();
							$needs[] = "property_details";
							$needs[] = $row->id;
							$itemid = OSPRoute::getItemid($needs);
							$link = JRoute::_('index.php?option=com_osproperty&task=property_details&id='.$row->id.'&Itemid='.$itemid);
							?>
							<tr>
								<td style="padding:3px;padding-left:10px;text-align:left;border-bottom:1px dotted #CCC !important;">
									<a href="<?php echo $link?>" title="<?php echo JText::_('OS_PROPERTY_DETAILS')?>">
									<?php
									echo OSPHelper::getLanguageFieldValue($row,'pro_name');
									?>
									</a>
									<?php
									if($row->ref != ""){
										?>
										(<?php
										echo $row->ref;
										?>)
										<?php
									}
									?>
								</td>
								<td style="text-align:center;border-bottom:1px dotted #CCC !important;">
									<?php
									$points = round($row->rated);
									for($i=1;$i<=$points;$i++){
										?>
										<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/star1.jpg" />
										<?php
									}
									for($i=$points+1;$i<=5;$i++){
										?>
										<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/star2.jpg" />
										<?php
									}
									?>
								
									<?php
									echo " <strong>(".$points."/5)</strong>";
									?>
								</td>
							</tr>
							<?php
						}
						?>
					</table>
				</td>
			</tr>
		</table>
		<?php
	}
	/**
	 * Edit profile
	 *
	 * @param unknown_type $option
	 * @param unknown_type $agent
	 */
	static function editProfile($option,$agent,$lists,$rows,$pageNav,$configs){
		global $mainframe,$configClass,$ismobile;
		JHTML::_('behavior.modal','a.osmodal');
		JHTML::_('behavior.tooltip');
		//jimport('joomla.html.pane');
		//$panetab =& JPane::getInstance('Tabs');
		?>
		<script language="javascript">
		function submitAgentForm(form_name){
	
		var form = document.getElementById(form_name);
			var temp1,temp2;
			var cansubmit = 1;
			var require_field = document.getElementById('require_field_' + form_name);
			require_field = require_field.value;
			var require_label = document.getElementById('require_label_' + form_name);
			require_label = require_label.value;
			var require_fieldArr = require_field.split(",");
			var require_labelArr = require_label.split(",");
			for(i=0;i<require_fieldArr.length;i++){
				temp1 = require_fieldArr[i];
				temp2 = form[temp1]; // hungvd repair
				//temp2 = document.getElementById(temp1);
				if(temp2 != null){
					if((temp2.value == "") && (cansubmit == 1)){
						alert(require_labelArr[i] + " <?php echo JText::_('OS_IS_MANDATORY_FIELD')?>");
						temp2.focus();
						cansubmit = 0;
					}
				}
			}
			
			// hungvd modify
			if ((form_name == 'profileForm') && (cansubmit = 1)){
				password 	= form['password'];
				password2 	= form['password2'];
				if (password.value != '' && password.value != password2.value){
					alert("<?php echo JText::_('OS_NEW_PASSWORD_IS_NOT_CORRECT')?>");
					cansubmit = 0;
				}
			}
			
			
			if(cansubmit == 1){
				form.submit();
			}
		}
		function loadState(country_id,state_id,city_id){
			var live_site = '<?php echo JURI::root()?>';
			loadLocationInfoStateCity(country_id,state_id,city_id,'country','state',live_site);
		}
		function loadCity(state_id,city_id){
			var live_site = '<?php echo JURI::root()?>';
			loadLocationInfoCityAddProperty(state_id,city_id,'state',live_site);
		}
		function savePassword(){
			var form = document.passwordForm;
			new_password = form.new_password;
			new_password1 = form.new_password1;
			if((new_password1.value == "")&&(new_password.value!="")){
				alert("<?php echo JText::_('Please re-enter new password')?>");
				new_password1.focus();
			}else if(new_password1.value != new_password.value){
				alert("<?php echo JText::_('OS_NEW_PASSWORD_IS_NOT_CORRECT')?>");
			}else{
				form.submit();
			}
			
		}
		function submitForm(t){
			var total = 0;
			var temp;
			total = <?php echo count($rows)?>;
            if(t == "new"){
                document.ftForm.task.value = "property_new";
                document.ftForm.submit();
                return false;
            }
			if(total > 0){
				var check = 0;
				for(i=0;i<total;i++){
					temp = document.getElementById('cb' + i);
					if(temp != null){
						if(temp.checked == true){
							check = 1;
						}
					}
				}
				if(check == 0){
					alert("<?php echo JText::_("OS_PLEASE_ITEM")?>");
				}else{
					if(t == "deleteproperties"){
						var answer = confirm("<?php echo JText::_('OS_DO_YOU_WANT_TO_REMOVE_ITEMS')?>");
						if(answer == 1){
							document.ftForm.task.value = "agent_deleteproperties";
							document.ftForm.submit();
						}
					}else{
						if(t != "property_upgrade"){
							document.ftForm.task.value = "agent_" + t;
							document.ftForm.submit();
						}else{
							document.ftForm.task.value = t;
							document.ftForm.submit();
						}
					}
				}
			}
		}
		
		function openDiv(id){
			var atag = document.getElementById('a' + id);
			var divtag = document.getElementById('div' + id);
			if(atag.innerHTML == "[+]"){
				atag.innerHTML = "[-]";
				divtag.style.display = "block";
			}else{
				atag.innerHTML = "[+]"
				divtag.style.display = "none";
			}
		}

		function unfeaturedproperty(pro_id){
			var answer = confirm("<?php echo JText::_('OS_ARE_YOU_SURE_YOU_WANT_TO_UNFEATURED_PROPERTY');?>");
			if(answer == 1){
				location.href = "<?php echo JUri::root()?>index.php?option=com_osproperty&task=property_unfeatured&id=" + pro_id + "&Itemid=<?php echo JRequest::getInt('Itemid',0);?>";
			}
		}
		</script>
		<div class="row-fluid">
			<div class="span12">
				<div class="componentheading">
					<?php echo JText::_('OS_MY_PROFILE')?>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12 hidden-phone">
			<?php
			if((count($lists['mostview']) > 0) or (count($lists['mostrate']) > 0)){
				if(($configClass['agent_mostrated'] == 1) and ($configClass['agent_mostviewed'] == 1)){
					$width = "50";
				}else{
					$width = "100";
				}
				?>
				<!-- show the most view -->
				<div class="row-fluid">
					<div class="span12">
						<div class="span6">
							<?php
							HTML_OspropertyAgent::showMostViewProperties($option,$lists['mostview']);
							?>
						</div>
						<div class="span6">
							<?php
							HTML_OspropertyAgent::showMostRatedProperties($option,$lists['mostrate']);
							?>
						</div>
					</div>
				</div>
				<?php
			}
			?>
			</div>
			<div class="clearfix"></div>
			<div class="row-fluid">
				<ul class="nav nav-tabs">
				
					<li class="active" ><a href="#panel3" data-toggle="tab"><?php echo Jtext::_('OS_YOUR_PROPERTIES');?></a></li>
					<?php
					if($configClass['integrate_membership'] == 1){
					?>
					<li  ><a href="#panel3a" data-toggle="tab"><?php echo Jtext::_('OS_YOUR_MEMBERSHIP');?></a></li>
					<?php
					}
					?>
					<li  ><a href="#panel1" data-toggle="tab"><?php echo Jtext::_('OS_PROFILE_INFO');?></a></li>
					<li  ><a href="#panel2" data-toggle="tab"><?php echo Jtext::_('OS_ACCOUNT_INFO');?></a></li>
				</ul>
				<div class="tab-content">	
					<div class="tab-pane active" id="panel3">
						<?php
						//echo $panetab->startPane('agent');
						//echo $panetab->startPanel(JText::_( 'OS_YOUR_PROPERTIES' ), 'panel3');
						?>
						<form method="POST" action="<?php echo JRoute::_('index.php?option=com_osproperty&view=aeditdetails');?>" name="ftForm" id="ftForm" class="form-horizontal">
						<div class="row-fluid">
							<div class="span12">
								<div class="block_caption">
									<strong><?php echo JText::_('OS_YOUR_PROPERTIES')?></strong>
								</div>
								<div class="clearfix"></div>
								<div id="filter-bar" class="btn-toolbar">
						            <div class="filter-search btn-group pull-left">
						                <input type="text" name="filter_search" class="input-medium search-query" id="filter_search" value="<?php echo OSPHelper::getStringRequest('filter_search','','')?>" title="<?php echo JText::_('OS_SEARCH');?>" />
						            </div>
						            <div class="btn-group pull-left hidden-phone">
						                <button class="btn hasTooltip" type="submit" title="<?php echo JText::_('OS_SEARCH');?>"><i class="osicon-search"></i></button>
						                <button class="btn hasTooltip" type="button" onclick="javascript:document.getElementById('filter_search').value='';document.getElementById('ftForm').submit();" rel="tooltip" title="<?php echo JText::_('OS_CLEAR');?>"><i class="osicon-remove"></i></button>
						            </div>
						            <div class="btn-group pull-right hidden-phone">
						                <?php echo $lists['orderby'];?>
						            </div>
						            <div class="btn-group pull-right">
						            	<?php echo $lists['sortby'];?>
						            </div>
						        </div>
						        <div class="clearfix"> </div>
						        
						        <div id="filter-bar" class="btn-toolbar">
						            <div class="btn-group pull-right">
						                <?php echo $lists['status'];?>
						            </div>
                                    <div class="btn-group pull-right">
                                        <?php echo $lists['featured'];?>
                                    </div>
                                    <div class="btn-group pull-right">
                                        <?php echo $lists['approved'];?>
                                    </div>
						            <div class="btn-group pull-right">
						                <?php echo $lists['type'];?>
						            </div>
						            <div class="btn-group pull-right">
						                <?php echo $lists['category'];?>
						            </div>
						        </div>
								<div class="clearfix"></div>
								<div class="btn-toolbar hidden-phone">
						            <div class="btn-group">
						            	<?php
										if($configClass['integrate_membership'] == 0){
										?>
										<button type="button" class="btn hasTooltip btn-success" title="<?php echo JText::_('OS_UPGRADE_FEATURE');?>" onclick="javascript:submitForm('property_upgrade');">
						                    <i class="osicon-featured"></i> <?php echo JText::_('OS_UPGRADE_FEATURE');?>
						                </button>
										<?php
										}
										if($configClass['general_agent_listings'] == 1){
										?>
						                <button type="button" class="btn hasTooltip btn-info" title="<?php echo JText::_('OS_ADD');?>" onclick="javascript:submitForm('new');">
						                    <i class="osicon-new"></i> <?php echo JText::_('OS_ADD');?>
						                </button>
						                <?php } ?>
						                <button type="button" class="btn hasTooltip" title="<?php echo JText::_('OS_REQUEST_APPROVAL');?>" onclick="javascript:submitForm('requestapproval');">
						                    <i class="osicon-support"></i> <?php echo JText::_('OS_REQUEST_APPROVAL');?>
						                </button>
						                <button type="button" class="btn hasTooltip btn-info" title="<?php echo JText::_('OS_EDIT');?>" onclick="javascript:submitForm('editproperty');">
						                    <i class="osicon-edit"></i> <?php echo JText::_('OS_EDIT');?>
						                </button>
						                <button type="button" class="btn hasTooltip btn-success" title="<?php echo JText::_('OS_PUBLISHED');?>" onclick="javascript:submitForm('publishproperties');">
						                    <i class="osicon-publish"></i> <?php echo JText::_('OS_PUBLISHED');?>
						                </button>
						                <button type="button" class="btn hasTooltip btn-warning" title="<?php echo JText::_('OS_UNPUBLISHED');?>" onclick="javascript:submitForm('unpublishproperties');">
						                    <i class="osicon-unpublish"></i> <?php echo JText::_('OS_UNPUBLISHED');?>
						                </button>
						                
						                <button type="button" class="btn hasTooltip btn-danger" title="Delete" onclick="javascript:submitForm('deleteproperties');">
						                    <i class="osicon-trash"></i> <?php echo JText::_('OS_REMOVE');?>
						                </button>
						            </div>
						        </div>
								<table class="ptable table table-striped tablelistproperties" id="propertyList">
									<thead>
										<tr>
											<th class="nowrap hidden-phone" width="1%">
												ID
											</th>
											<th class="nowrap hidden-phone" width="2%">
												<input type="checkbox" name="checkall" id="checkall" value="0" onclick="javascript:allCheck('checkall')" />
											</th>
											<th width="35%" class="nowrap">
						                       <?php echo JText::_('OS_LOCATION').' / '.JText::_('OS_TITLE').' / '.JText::_('Ref #').' / '.JText::_('OS_PRICE');?>
						                    </th>
											<th class="nowrap hidden-phone center" width="10%">
												<?php echo JText::_('OS_FEATURED')?>
											</th>
											<th class="nowrap hidden-phone center" width="10%">
												<?php echo JText::_('OS_APPROVED')?>
											</th>
											<th class="nowrap hidden-phone center" width="5%">
												<?php echo JText::_('OS_PUBLISH')?>
											</th>
											
											<?php
											if($configs[14]->fieldvalue == 1){
											?>
											<th class="nowrap hidden-phone center" width="15%">
												<?php echo JText::_('OS_EXPIRED')?>
											</th>
											<?php
											}
											?>
											<th class="nowrap hidden-phone center" width="5%">
												<?php echo JText::_('OS_PRINT')?>
											</th>
										
											<?php
											if(file_exists(JPATH_ROOT.DS."components".DS."com_oscalendar".DS."oscalendar.php")){
												if($configClass['integrate_oscalendar'] == 1){
													?>
													<th class="nowrap hidden-phone center" width="5%">
														<?php echo JText::_('OS_MANAGE_ROOMS');?>
													</th>
													<?php
												}
											}
											?>
										</tr>
									</thead>
									<?php
									if(count($rows) > 0){
										$k = 0;
										for($i=0;$i<count($rows);$i++){
											$row = $rows[$i];
											$link = JRoute::_("index.php?option=com_osproperty&task=property_edit&id=".$row->id);
											?>
											<tr class="row<?php echo $k;?>">
												<td class="small hidden-phone center">
													<?php
														echo $row->id;
													?>
												</td>
												<td class="small hidden-phone center">
													<input type="checkbox" name="cid[]" value="<?php echo $row->id?>" id="cb<?php echo $i?>" /> 
												</td>
												<td class="has-context">
													<div class="pull-left">
														<span class="hasTip" title="&lt;img src=&quot;<?php echo $row->photo;?>&quot; alt=&quot;<?php echo str_replace("'","",$row->pro_name);?>&quot; width=&quot;100&quot; /&gt;"><i class="osicon-camera"></i></span> | 
														 <a href="<?php echo $link;?>" title="<?php echo JText::_('OS_EDIT_PROPERTY');?>">
				                                         <?php echo OSPHelper::getLanguageFieldValue($row,'pro_name');?>
				                                         <?php
				                                         if($row->show_address == 1){
				                                         ?>
				                                         , <?php echo $row->city;?> - <?php echo $row->state_name;?>
				                                         <?php
				                                         }
				                                         ?>
				                                         </a>
				                                         <?php
				                                         if($row->isFeatured == 1){
				                                         	?>
				                                         	<span title="<?php echo JText::_('OS_FEATURED_PROPERTY');?>">
				                                         		<i class="osicon-star icon-red"></i>
				                                         	</span>
				                                         	<?php
				                                         }
				                                         ?>
				                                         <?php
				                                         if($row->show_address == 1){
				                                         ?>
				                                         <br /><span class="small"><?php echo $row->address?> </span>
				                                         <?php
				                                         }
				                                         if($row->ref != ""){
				                                         ?>
				                                         <br /><strong>Ref #:</strong> <?php echo $row->ref;?>
				                                         <?php
				                                         }
				                                         ?>
				                                         <br />
				                                         <?php
				                                         if($row->price_call == 0){
				                                         ?>
				                                         	<strong><?php echo JText::_('OS_PRICE')?>:</strong> 
				                                         	<?php echo OSPHelper::generatePrice($row->curr,$row->price)?>
					                                         <?php
					                                         if($row->rent_time != ""){
					                                         	echo "/".Jtext::_($row->rent_time);
					                                         }
					                                         ?>
				                                         <?php
				                                         }else{
				                                         ?>
				                                         <strong>
				                                         	<?php echo JText::_('OS_CALL_FOR_PRICE');?>
				                                         </strong>
				                                         <?php
				                                         }
				                                         ?>
				                                         <BR />
				                                         <strong>
				                                         	<?php echo JText::_('OS_CATEGORY')?>:
				                                         </strong>
				                                         <?php echo OSPHelper::getCategoryNamesOfProperty($row->id);//echo $row->category_name;?>
				                                         <BR />
				                                         <strong>
				                                         	<?php echo JText::_('OS_PROPERTY_TYPE')?>:
				                                         </strong>
				                                         <?php echo $row->type_name;?>
				                                         <BR />
				                                         <strong>
				                                         <?php echo JText::_('OS_REQUEST_INFO')?>:
				                                         </strong>
														 <?php
														 if($row->total_request_info){
															echo $row->total_request_info;
														 }else{
															echo JText::_('OS_NOT_SET');
														 }
														 ?>
														 &nbsp;|&nbsp;
														 <strong>
															<?php echo JText::_('OS_HITS')?>:
														 </strong>
														 <?php
														 echo intval($row->hits);
														 ?>
														 &nbsp;|&nbsp;
														 <strong>
														 <?php echo JText::_('OS_RATING')?>:
														 </strong>
														<?php
														if($row->number_votes > 0){
															$points = round($row->total_points/$row->number_votes);
															?>
															<table  width="100%">
																<tr>
																	<td width="40%">
																		<?php
																		for($i2=1;$i2<=$points;$i2++){
																			?>
																			<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/star1.png">
																			<?php
																		}
																		for($i2=$points+1;$i2<=5;$i2++){
																			?>
																			<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/star2.png">
																			<?php
																		}
																		?>
																	</td>
																	<td align="left" width="60%">
																		<?php
																		echo " <strong>(".$points."/5)</strong>";
																		?>
																	</td>
																</tr>
															</table>
															<?php
	
	
														}else{
															for($i2=1;$i2<=5;$i2++){
																?>
																<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/star2.png">
																<?php
															}
														}
														?>
													</div>
												</td>
												
												<td class="small hidden-phone center">
													<?php
													$tooltip = HelperOspropertyCommon::loadFeatureInfo($row->id);
													if($row->isFeatured == 1){
														if($configClass['general_use_expiration_management']  == 1){
															?>
															<span class="hasTip" title="<?php echo JText::_('OS_FEATURED')?>::<?php echo $tooltip; ?>">
															<?php
														}
														?>
														<a class="btn btn-micro active btn-info" href="javascript:unfeaturedproperty(<?php echo $row->id?>);" title="<?php echo JText::_('OS_CLICK_HERE_TO_UNFEATURED_THIS_PROPERTY');?>">
															<i class="osicon-star"></i>
														</a>
														<?php
														if($configClass['general_use_expiration_management']  == 1){
															echo "</span>";
														}
													}else{
														?>
														<a class="btn btn-micro active btn-warning disabled" title="<?php echo JText::_('OS_UNFEATURED')?>">
															<i class="osicon-star osicon-white"></i>
														</a>
														<?php
														if($configClass['integrate_membership'] == 0){
														?>
														<BR>
														<a href="<?php echo JUri::root()?>index.php?option=com_osproperty&task=property_upgrade&cid[]=<?php echo $row->id?>" style="font-size:11px;color:gray;"><?php echo Jtext::_('OS_UPGRADE_FEATURED');?></a>
														<?php
														}
													}
													//$title = JText::_('OS_FEATURE_INFO').' ['.$row->pro_name.']';
													//$tooltip = HelperOspropertyCommon::loadFeatureInfo($row->id);
													//echo JHTML::_('tooltip', $tooltip, $title);
													?>
												</td>
												<td class="small hidden-phone center">
													<?php
													$tooltip = HelperOspropertyCommon::loadApprovalInfo($row->id);
													if($row->approved == 1){
														if($configClass['general_use_expiration_management']  == 1){
														?>
															<span class="hasTip" title="<?php echo JText::_('OS_APPROVAL')?>::<?php echo $tooltip; ?>">
															<?php
														}
														?>
														<a class="btn btn-micro active btn-info disabled" title="<?php echo JText::_('OS_APPROVAL')?>">
														<i class="osicon-ok"></i>
														</a>
														<?php
														if($configClass['general_use_expiration_management']  == 1){
															echo "</span>";
														}
													}else{
														?>
														<a class="btn btn-micro active btn-warning disabled" title="<?php echo JText::_('OS_UNAPPROVAL')?>">
															<i class="osicon-cancel osicon-white"></i>
														</a>
														<?php
													}
													?>
												</td>
												<td class="small hidden-phone center">
													<?php
													if($row->published == 1){
														?>
														<a class="btn btn-micro active btn-success" title="<?php echo JText::_('OS_UNPUBLISH')?> <?php echo JText::_('OS_ITEM');?>" href="<?php echo JURI::root()?>index.php?option=com_osproperty&task=agent_unpublishproperties&cid[]=<?php echo $row->id?>">
															<i class="osicon-publish"></i>
														</a>
														<?php
													}else{
														?>
														<a class="btn btn-micro active btn-danger" title="<?php echo JText::_('OS_PUBLISH')?> <?php echo JText::_('OS_ITEM');?>" href="<?php echo JURI::root()?>index.php?option=com_osproperty&task=agent_publishproperties&cid[]=<?php echo $row->id?>">
															<i class="osicon-unpublish"></i>
														</a>
														<?php
													}
													?>
												</td>
												<?php
												if($configClass['general_use_expiration_management']==1){
												?>
												<td class="hidden-phone center">
													<?php
													if(($row->expired_time != "0000-00-00 00:00:00") and ($row->expired_time != "")){
														echo HelperOspropertyCommon::loadTime($row->expired_time,2)	;											
													}
													if($row->isFeatured == 1){
														echo "<BR />";
														if(($row->expired_feature_time != "0000-00-00 00:00:00") and ($row->expired_feature_time != "")){
															echo "<font style='font-size:11px;color:red;'>".JText::_('OS_EXPIRED_FEATURED_TIME')."</font>";
															echo HelperOspropertyCommon::loadTime($row->expired_feature_time,2);
														}
													}
													?>
												</td>
												
												<?php
												}
												?>
												
												<td class="small hidden-phone center">
													<?php $open_print = "window.open ('".JURI::root()."index.php?option=com_osproperty&tmpl=component&task=property_print&id=". $row->id ."', 'mywindow','menubar=0,status=0,location=0,status=0,scrollbars=1,resizable=0,toolbar=0,directories=0, width=1000,height=700')";?>
													<a href="javascript:void(0);" onclick="javascript:<?php echo $open_print?>;" class="btn" title="<?php echo JText::_('OS_PRINT')?>">
														<i class="osicon-print"></i>
													</a>
												</td>
												
												<?php
												if(file_exists(JPATH_ROOT.DS."components".DS."com_oscalendar".DS."oscalendar.php")){
													if($configClass['integrate_oscalendar'] == 1){
														?>
														<td align="center" class="data_td hidden-phone" style="background-color:<?php echo $bgcolor?>;text-align:center;">
														<a href="<?php echo JRoute::_("index.php?option=com_oscalendar&task=room_manage&pid=".$row->id);?>" target="_blank" title="<?php echo JText::_('OS_MANAGE_ROOMS')?>" class="btn" />
															<i class="osicon-calendar"></i>
														</a>
														</td>
														<?php	
													}
												}
												?>
											</tr>
											<?php
										}
										?>
										<tfoot>
											<tr>
												<td width="100%" align="center" colspan="11" style="padding:5px;text-align:center;">
													<?php
														echo $pageNav->getListFooter();
													?>
												</td>
											</tr>
										</tfoot>
										<?php
									}
									?>
								</table>
								
								<div class="clearfix"></div>
								<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/tick.png" /> 
								<?php echo JText::_('OS_PUBLISH_APPROVAL_FEATURE')?>
								&nbsp;&nbsp;|&nbsp;&nbsp;
								<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/publish_x.png" />
								<?php echo JText::_('OS_UNPUBLISH_UNAPPROVAL_NOT_FEATURE')?>
								<BR />
								<?php echo JText::_('OS_CLICK_ICON_TO_TOGGLE')?>
							</div>	
						</div>
						
						<input type="hidden" name="option" value="com_osproperty" />
						<input type="hidden" name="task" value="agent_default" />
						<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid',0)?>" />
						<input type="hidden" name="view" value="aeditdetails" />
						</form>
						<script language="javascript">
						function allCheck(id){
							var temp = document.getElementById(id);
							var count = "<?php echo count($rows)?>";
							if(temp.value == 0){
								temp.value = 1;
								for(i=0;i<count;i++){
									cb = document.getElementById('cb'+ i);
									if(cb != null){
										cb.checked = true;
									}
								}
							}else{
								temp.value = 0;
								for(i=0;i<count;i++){
									cb = document.getElementById('cb'+ i);
									if(cb != null){
										cb.checked = false;
									}
								}
							}
						}
						</script>
					</div>
						<?php
						
						//echo $panetab->endPanel();
						if($configClass['integrate_membership'] == 1){
							//echo $panetab->startPanel(JText::_( 'OS_YOUR_MEMBERSHIP' ), 'panel3a');
							?>
							<div class="tab-pane" id="panel3a">
							<?php
							$db = JFactory::getDbo();
							if(file_exists(JPATH_ROOT.DS."components".DS."com_osmembership".DS."helper".DS."helper.php")){
								include_once(JPATH_ROOT.DS."components".DS."com_osmembership".DS."helper".DS."helper.php");
							}
							?>
							<table  width="100%" class="sTable">
								<tr>
									<td width="100%" style="border:0px !important;">
										<div class="block_caption">
											<strong><?php echo JText::_('OS_YOUR_MEMBERSHIP')?></strong>
										</div>
										<table  width="100%" class="admintable">
											<tr>
												<td width="100%">
													<?php
													$plans = HelperOspropertyCommon::returnAccountValue();
													if(count($plans) > 0){
														?>
														<table  width="100%">
															<tr>
																<td width="25%" class="header_td">
																	<?php
																	echo JText::_(OS_PLAN_NAME);
																	?>
																</td>
																<td width="15%" class="header_td"> 
																	<?php
																	echo JText::_(OS_ACCOUNT_REMAINING);
																	?>
																</td>
																<td width="15%" class="header_td"> 
																	<?php
																	echo JText::_(OS_FROM);
																	?>
																</td>
																<td width="15%" class="header_td">
																	<?php
																	echo JText::_(OS_TO);
																	?>
																</td>
															</tr>
															<?php
															$user = JFactory::getUser();
															for($i=0;$i<count($plans);$i++){
																$plan = $plans[$i];
																$plan_name = $plan->title;
																if($plan_name != ""){
																?>
																<tr>
																	<td width="25%" class="data_td" style="text-align:center;border-bottom:1px dotted #CCC !important;">
																		<?php
																			echo $plan_name;
																		?>
																	</td>
																	<td width="25%" class="data_td" style="text-align:center;border-bottom:1px dotted #CCC !important;">
																		<?php
																			echo $plan->nproperties;
																		?>
																	</td>
																	<td width="25%" class="data_td" style="text-align:center;border-bottom:1px dotted #CCC !important;">
																		<?php
																			echo OSPHelper::returnDateformat(strtotime($plan->from_date));
																		?>
																	</td>
																	<td width="25%" class="data_td" style="text-align:center;border-bottom:1px dotted #CCC !important;">
																		<?php
																			echo OSPHelper::returnDateformat(strtotime($plan->to_date));
																		?>
																	</td>
																</tr>
																<?php
																}
															}
															?>
														</table>
														<?php
													}else{
														echo  JText::_('OS_NO_ACTIVE_PLAN');
													}
													?>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>	
							</div>
							<?php
							//echo $panetab->endPanel();
						}
						?>
						<div class="tab-pane" id="panel1">
						<?php
						//echo $panetab->startPanel(JText::_( 'OS_PROFILE_INFO' ), 'panel1');
						$user = JFactory::getUser();
						?>
						<form method="POST" action="<?php echo JRoute::_('index.php?option=com_osproperty&task=agent_saveprofile&Itemid='.JRequest::getInt('Itemid',0))?>" name="profileForm" id="profileForm" class="form-horizontal">
						<div class="row-fluid">
							<div class="span12" style="margin-left:0px;">
								<div class="block_caption">
									<strong><?php echo JText::_('OS_PROFILE_INFO')?></strong>
								</div>
								<div class="clearfix"></div>
								<div class="blue_middle"><?php echo Jtext::_('OS_FIELDS_MARKED')?> <span class="red">*</span> <?php echo JText::_('OS_ARE_REQUIRED')?></div>
								<div class="control-group">
									<label class="control-label" ><?php echo JText::_('OS_NAME')?> *</label>
									<div class="controls">
										<input type="text" name="name" id="name" size="30" value="<?php echo $user->name?>" class="input-medium" placeholder="<?php echo JText::_('OS_NAME')?>"/>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" ><?php echo JText::_('OS_LOGIN_NAME')?> *</label>
									<div class="controls">
										<input type="text" name="username" id="username" size="30" value="<?php echo $user->username?>" class="input-small" placeholder="<?php echo JText::_('OS_LOGIN_NAME')?>"/>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label"><?php echo JText::_('OS_PASSWORD')?>  *</label>
									<div class="controls">
										<input type="password" name="password" id="password" size="30" class="input-small" autocomplete="off" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label"><?php echo JText::_('OS_CONFIRM_PASSWORD')?> *</label>
									<div class="controls">
										<input type="password" name="password2" id="password2" size="30" class="input-small" autocomplete="off" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" ><?php echo JText::_('OS_EMAIL')?> *</label>
									<div class="controls">
										<input type="text" name="email" id="email" size="30" value="<?php echo $user->email?>" class="input-large" placeholder="<?php echo JText::_('OS_EMAIL')?>"/>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="span12" style="margin-left:0px;">
									<input type="button" class="btn btn-info" value="<?php echo JText::_("OS_SAVE")?>" onclick="javascript:submitAgentForm('profileForm')" />
									<input type="reset" class="btn btn-danger" value="<?php echo JText::_("OS_CLEAR")?>" />
								</div>
							</div>
						</div>
						<input type="hidden" name="option" value="com_osproperty" />
						<input type="hidden" name="task" value="agent_saveprofile" />
						<input type="hidden" name="require_field_profileForm" id="require_field_profileForm" value="name,username,email" />
						<input type="hidden" name="require_label_profileForm" id="require_label_profileForm" value="<?php echo JText::_("Name")?>,<?php echo JText::_('OS_LOGIN_NAME')?>,<?php echo JText::_("OS_EMAIL")?>" />
						<input type="hidden" name="MAX_FILE_SIZE" value="9000000000" />
						<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid',0)?>" />
						</form>
						</div>
						<div class="tab-pane" id="panel2">
						<form method="POST" action="index.php" name="accountForm" id="accountForm" enctype="multipart/form-data" class="form-horizontal">
						<div class="row-fluid">
							<div class="span12" style="margin-left:0px;">
								<div class="block_caption">
									<strong><?php echo JText::_('OS_ACCOUNT_INFO')?></strong>
								</div>
								<div class="clearfix"></div>
								<div class="blue_middle"><?php echo Jtext::_('OS_FIELDS_MARKED')?> <span class="red">*</span> <?php echo JText::_('OS_ARE_REQUIRED')?></div>
								<?php
								if($configClass['show_agent_image'] == 1){
								?>
								<div class="control-group">
									<label class="control-label" ><?php echo JText::_('OS_PHOTO')?></label>
									<div class="controls">
										<?php
										if($agent->photo != ""){
											?>
											<img src="<?php echo JURI::root()?>images/osproperty/agent/<?php echo $agent->photo?>" width="100" />
											<BR />
											
											<input type="checkbox" name="remove_photo" id="remove_photo" onclick="javascript:changeValue('remove_photo')" value="0" /> <?php echo JText::_('OS_REMOVE_PHOTO');?>
											<div class="clearfix"></div>
										<?php
										}
										?>
										<span id="photodiv">
										<input type="file" name="photo" id="photo" size="20" class="input-small" onchange="javascript:checkUploadPhotoFiles('photo')" /> 
										<div class="clearfix"></div>
										<?php echo JText::_('OS_ONLY_SUPPORT_JPG_IMAGES');?>
										</span>
									</div>
								</div>
								<?php
								}
								?>
								<div class="control-group">
									<label class="control-label" ><?php echo JText::_('OS_AGENT_NAME')?> *</label>
									<div class="controls">
										<input type="text" name="name" id="name" size="30" value="<?php echo htmlspecialchars($agent->name);?>" class="input-large" placeholder="<?php echo JText::_('OS_AGENT_NAME')?>"/>
										<input type="hidden" name="alias" value="<?php echo htmlspecialchars($agent->alias);?>" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" ><?php echo JText::_('OS_AGENT_EMAIL')?> *</label>
									<div class="controls">
										<input type="text" name="email" id="email" size="30" value="<?php echo $user->email?>" class="input-large" placeholder="<?php echo JText::_('OS_AGENT_EMAIL')?>"/>
									</div>
								</div>	
								<div class="control-group">
									<label class="control-label" ><?php echo JText::_('OS_COMPANY');?></label>
									<div class="controls">
										<?php echo $lists['company'];?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" ><?php echo JText::_('OS_ADDRESS')?></label>
									<div class="controls">
										<input type="text" name="address" id="address" size="30" value="<?php echo htmlspecialchars($agent->address);?>" class="input-large" placeholder="<?php echo JText::_('OS_ADDRESS')?>"/>
									</div>
								</div>	
								<?php
								if(HelperOspropertyCommon::checkCountry()){
								?>
								<div class="control-group">
									<label class="control-label" ><?php echo JText::_('OS_COUNTRY')?> *</label>
									<div class="controls">
										<?php echo $lists['country']?>
									</div>
								</div>	
								<?php
								}else{
									echo $lists['country'];
								}
								if(OSPHelper::userOneState()){
									echo $lists['state'];
								}else{
								?>
								<div class="control-group">
									<label class="control-label" ><?php echo JText::_('OS_STATE')?> *</label>
									<div class="controls" id="country_state">
										<?php
										//if($agent->state != ""){
										echo $lists['state'];
										
										//}
										?>
										<?php
										//if($configClass['require_state'] == 1){
											//echo '<span class="red">(*)</span>';
										//}
										?>
									</div>
								</div>
								<?php } ?>
								<div class="control-group">
									<label class="control-label" ><?php echo JText::_('OS_CITY')?> *</label>
									<div class="controls" id="city_div">
										<?php
										echo $lists['city'];
										?>
									</div>
								</div>	
								<?php
								if($configClass['show_agent_phone'] == 1){
								?>
									<div class="control-group">
										<label class="control-label"><?php echo JText::_('OS_PHONE')?></label>
										<div class="controls">
											<input type="text" name="phone" id="phone" size="30" value="<?php echo htmlspecialchars($agent->phone);?>" class="input-small" placeholder="<?php echo JText::_('OS_PHONE')?>"/>
										</div>
									</div>
								<?php
								}
								if($configClass['show_agent_mobile'] == 1){
								?>
									<div class="control-group">
										<label class="control-label"><?php echo JText::_('OS_MOBILE')?></label>
										<div class="controls">
											<input type="text" name="mobile" id="mobile" size="30" value="<?php echo htmlspecialchars($agent->mobile);?>" class="input-small" placeholder="<?php echo JText::_('OS_MOBILE')?>"/>
										</div>
									</div>
								<?php
								}
								if($configClass['show_agent_fax'] == 1){
								?>
									<div class="control-group">
										<label class="control-label"><?php echo JText::_('OS_FAX')?></label>
										<div class="controls">
											<input type="text" name="fax" id="fax" size="30" value="<?php echo htmlspecialchars($agent->phone);?>" class="input-small" placeholder="<?php echo JText::_('OS_FAX')?>"/>
										</div>
									</div>
								<?php
								}
								if($configClass['show_agent_yahoo'] == 1){
								?>
									<div class="control-group">
										<label class="control-label"><?php echo JText::_('Yahoo')?></label>
										<div class="controls">
											<input type="text" name="yahoo" id="yahoo" size="30" value="<?php echo htmlspecialchars($agent->yahoo);?>" class="input-small" placeholder="<?php echo JText::_('Yahoo')?>"/>
										</div>
									</div>
								<?php
								}
								if($configClass['show_agent_skype'] == 1){
								?>
									<div class="control-group">
										<label class="control-label"><?php echo JText::_('Skype')?></label>
										<div class="controls">
											<input type="text" name="skype" id="skype" size="30" value="<?php echo htmlspecialchars($agent->skype);?>" class="input-small" placeholder="<?php echo JText::_('Skype')?>"/>
										</div>
									</div>
								<?php
								}
								if($configClass['show_agent_gtalk'] == 1){
								?>
									<div class="control-group">
										<label class="control-label"><?php echo JText::_('Gtalk')?></label>
										<div class="controls">
											<input type="text" name="gtalk" id="gtalk" size="30" value="<?php echo htmlspecialchars($agent->gtalk);?>" class="input-small" placeholder="<?php echo JText::_('Gtalk')?>"/>
										</div>
									</div>
								<?php
								}
								if($configClass['show_agent_msn'] == 1){
								?>
									<div class="control-group">
										<label class="control-label"><?php echo JText::_('Msn')?></label>
										<div class="controls">
											<input type="text" name="msn" id="msn" size="30" value="<?php echo htmlspecialchars($agent->msn);?>" class="input-small" placeholder="<?php echo JText::_('Msn')?>"/>
										</div>
									</div>
								<?php
								}
								if($configClass['show_agent_facebook'] == 1){
								?>
									<div class="control-group">
										<label class="control-label"><?php echo JText::_('Facebook')?></label>
										<div class="controls">
											<input type="text" name="facebook" id="facebook" size="30" value="<?php echo htmlspecialchars($agent->facebook);?>" class="input-medium" placeholder="<?php echo JText::_('Facebook')?>"/>
										</div>
									</div>
								<?php
								}
								if($configClass['show_license'] == 1){
								?>
									<div class="control-group">
										<label class="control-label"><?php echo JText::_('OS_LICENSE')?></label>
										<div class="controls">
											<input type="text" name="license" id="license" size="30" value="<?php echo htmlspecialchars($agent->license);?>" class="input-medium" placeholder="<?php echo JText::_('OS_LICENSE')?>"/>
										</div>
									</div>
								<?php
								}
								?>
								<div class="control-group">
									<label class="control-label"><?php echo JText::_('OS_BIO')?></label>
									<div class="controls">
										<?php
										$editor = JFactory::getEditor();
										echo $editor->display( 'bio',  htmlspecialchars($agent->bio, ENT_QUOTES), '250', '200', '60', '20',false ) ;
										?>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="span12" style="margin-left:0px;">
									<input type="button" class="btn btn-info" value="<?php echo JText::_("OS_SAVE")?>" onclick="javascript:submitAgentForm('accountForm')" />
									<input type="reset" class="btn btn-danger" value="<?php echo JText::_("OS_RESET")?>" />
								</div>
							</div>
						</div>
						<input type="hidden" name="option" value="com_osproperty" />
						<input type="hidden" name="task" value="agent_saveaccount" />
						<?php
						$require_fields = "name,email,country,";
						$require_labels = JText::_("OS_NAME").",".JText::_("OS_EMAIL").",".JText::_("OS_COUNTRY").",";
						if($configClass['require_state']==1){
							$require_fields .= "state,";
							$require_labels .= JText::_("OS_STATE").",";
						}
						if($configClass['require_city']==1){
							$require_fields .= "city,";
							$require_labels .= JText::_("OS_CITY").",";
						}
						$require_fields .= "phone";
						$require_labels .= JText::_("OS_PHONE");
						
						?>
						<input type="hidden" name="require_field_accountForm" id="require_field_accountForm" value="<?php echo $require_fields;?>" />
						<input type="hidden" name="require_label_accountForm" id="require_label_accountForm" value="<?php echo $require_labels;?>" />
						<input type="hidden" name="MAX_FILE_SIZE" value="9000000000" />
						<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid',0)?>" />
					</form>
					</div>
				</div>
				<?php
				//echo $panetab->endPanel();
				//echo $panetab->endPane();
				?>
			</div>
		</div>
		<?php
	}
	
	
	/**
	 * Agent Listing
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $lists
	 */
	static function agentListing($option,$rows,$pageNav,$lists){
		global $mainframe;
		?>
		<form method="POST" action="<?php echo JRoute::_('index.php?option=com_osproperty&task=agent_listing&Itemid='.JRequest::getInt('Itemid',0))?>" name="ftForm">
		<table  width="100%" class="sTable">
			<tr>
				<td style="border:0px !important;padding:10px;">
					<font style="font-size:18px;font-weight:bold;">
						<?php echo JText::_('My Listings')?>
					</font>
				</td>
			</tr>
			<tr>
				<td style="border:0px !important;padding-right:10px;text-align:right;">
					<?php echo JText::_('Sort listings by:');?>
					&nbsp;&nbsp;
					<?php
					echo $lists['orderby'];
					?>
					&nbsp;&nbsp;
					<?php
					echo $lists['ordertype'];
					?>
				</td>
			</tr>
			<tr>
				<td style="border:0px !important;padding-top:10px;text-align:right;">
					<table  width="100%">
						<?php
						if(count($rows) > 0){
							for($i=0;$i<count($rows);$i++){
								$row = $rows[$i];
								if($i % 2 == 0){
									$bgcolor = "white";
								}else{
									$bgcolor = "#efefef";
								}
								?>
								<tr>
									<td width="100%" style="padding:5px;background-color:<?php echo $bgcolor?>;">
										<table  width="100%">
											<tr>
												<td width="120" valign="middle">
													<img src="<?php echo $row->photo?>" width="120" />
												</td>
												<td width="70%" valign="top" align="left" style="padding:5px;">
													<strong>
														<?php
														echo OSPHelper::getLanguageFieldValue($row,'pro_name');
														if($row->ref != ""){
															echo " ($row->ref)";
														}
														?>
													</strong>
													<BR />
													<?php
														echo $row->address;
													?>
													<BR />
													<?php
														echo HelperOspropertyCommon::loadCityName($row->city);
													?>
													<BR />
													<?php
													echo $row->postcode
													?>
													<BR />
													<?php
													echo $row->state_name.", ".$row->country;
													if($row->region != ""){
														echo ", ".$row->region;
													}
													?>
												</td>
												<td width="20%" valign="top" style="padding:5px;" align="left">
													<table width="100%" class="sTable" cellpadding="0" cellspacing="">
														<tr>
														<td align="right"><span class="field"><small><?php echo JText::_('Listing for')?>:</small></span></td>
														<td style="width: 90px;" align="left">
														<span class="value"><small><strong><?php echo $row->type_name?></strong></small></span>
														</td>
													</tr>
													<tr>
														<td align="right"><span class="field"><small><?php echo JText::_('OS_CATEGORY')?>:</small></span></td>
														<td align="left">
															<?php echo OSPHelper::getCategoryNamesOfPropertyWithLinks($row->id);//echo $row->category_name?>
														</td>
													</tr>
													<tr>
														<td align="right"><span class="field"><small><?php echo JText::_('Created on')?>:</small></span></td>
														<td align="left">
															<span><small>
																<?php
																$created_on = strtotime($row->created);
																echo date("d-m-Y",$created_on);
																?>
																</small>
															</span>
														</td>
													</tr>
													<tr>
														<td align="right"><span class="field"><small><?php echo JText::_('Status')?>:</small></span></td>
														<td align="left">
															<span style="font-size:11px;">
																<?php
																if($row->published == 1){
																	echo "<font color='green'><strong>".JText::_('Active')."</strong></font>";
																}else{
																	echo "<font color='red'><strong>".JText::_('Unactive')."</strong></font>";
																}
																?>
															</span>
														</td>
													</tr>
													<tr>
														<td align="right"><span class="field"><small><?php echo JText::_('Approved')?>:</small></span></td>
														<td align="left">
															<span style="font-size:11px;">
																<?php
																if($row->approved == 1){
																	echo "<font color='green'><strong>".JText::_('Yes')."</strong></font>";
																}else{
																	echo "<font color='red'><strong>".JText::_('No')."</strong></font>";
																}
																?>
															</span>
														</td>
													</tr>
													<tr>
														<td align="right"><span class="field"><small><?php echo JText::_('Featured')?>:</small></span></td>
														<td align="left">
															<span style="font-size:11px;font-weight:bold;">
																<?php
																if($row->isFeatured == 1){
																	echo JText::_('OS_YES');
																}else{
																	echo JText::_('OS_NO');
																}
																?>
															</span>
														</td>
													</tr>
													<tr>
														<td align="right"><span class="field"><small><?php echo JText::_('Expired date')?>:</small></span></td>
														<td align="left">
															<span style="font-size:11px;font-weight:bold;">
																<?php
																if(($row->publish_down != "") and ($row->publish_down != "0000-00-00")){
																	?>
																	<span title="<?php echo JText::_("Expiry date")?>">
																		<?php echo $row->publish_down?>
																	</span>
																	<?php
																}else{
																	echo JText::_("Never");
																}
																?>
															</span>
														</td>
													</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<?php
							}
							?>
							<tr>
								<td width="100%" align="center" style="padding:5px;">
									<?php
										echo $pageNav->getListFooter();
									?>
								</td>
							</tr>
							<?php
						}else{
							?>
							<tr>
								<td width="100%" align="center" style="padding:5px;">
									<?php
										echo JText::_('OS_NO_LISTING');
									?>
								</td>
							</tr>
							<?php
						}
						?>
					</table>
				</td>
			</tr>
		</table>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="agent_listing" />
		<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid',0)?>" />
		</form>
		<?php
	}
	
	/**
	 * Agent infor
	 *
	 * @param unknown_type $option
	 * @param unknown_type $agent
	 */
	static function agentInfoForm($option,$agent,$lists){
		global $mainframe,$configClass,$languages,$lang_suffix;
		$document = JFactory::getDocument();
		$document->addScript(JURI::root()."components/com_osproperty/js/counter.js");
		?>
		<div class="row-fluid">
			<div class="span12">
				<?php 
				jimport('joomla.filesystem.file');
				if(JFile::exists(JPATH_ROOT.'/templates/'.$mainframe->getTemplate().'/html/com_osproperty/layouts/agentdetails.php')){
					$tpl = new OspropertyTemplate(JPATH_ROOT.'/templates/'.$mainframe->getTemplate().'/html/com_osproperty/layouts/');
				}else{
					$tpl = new OspropertyTemplate(JPATH_COMPONENT.'/helpers/layouts/');
				}
				$tpl->set('mainframe',$mainframe);
				$tpl->set('lists',$lists);
				$tpl->set('option',$option);
				$tpl->set('configClass',$configClass);
				$tpl->set('agent',$agent);
				$body = $tpl->fetch("agentdetails.php");
				echo $body;
				?>	
				<div class="row-fluid">
					<div class="span12">
						<div class="row-fluid">
							<ul class="nav nav-tabs">
								<?php
								if(($configClass['general_agent_listings'] == 1) and ($configClass['show_agent_properties'] == 1)){
								?>
								<li class="active" ><a href="#panel1" data-toggle="tab"><?php echo Jtext::_('OS_PROPERTIES');?></a></li>
									<?php
									if($configClass['show_agent_contact'] == 1){
									?>
										<li><a href="#panel2" data-toggle="tab" ><?php echo Jtext::_('OS_CONTACT');?></a></li>
									<?php } 
								$class1 = "active";
								$class2 = "";
								}else{
								?>
									<?php if($configClass['show_agent_contact'] == 1){ ?>
										<li class="active" ><a href="#panel2" data-toggle="tab"><?php echo Jtext::_('OS_CONTACT');?></a></li>
									<?php } ?>
								<?php
								$class1 = "";
								$class2 = "active";
								}
								?>
							</ul>
							<div class="tab-content">
								<?php
                                if(($configClass['general_agent_listings'] == 1) and ($configClass['show_agent_properties'] == 1)){
								?>
								<div class="tab-pane <?php echo $class1;?>" id="panel1">
									<form method="POST" action="<?php echo JRoute::_('index.php?Itemid='.JRequest::getInt('Itemid',0))?>" name="ftForm">
									<div class="block_caption">
										<strong><?php echo JText::_('OS_AGENT_PROPERTIES')?></strong>
									</div>
									<?php
									$filterParams = array();
									//show cat
									$filterParams[0] = 1;
									//agent
									$filterParams[1] = 0;
									//keyword
									$filterParams[2] = 1;
									//bed
									$filterParams[3] = 1;
									//bath
									$filterParams[4] = 1;
									//rooms
									$filterParams[5] = 1;
									//price
									$filterParams[6] = 1;
									
									$category_id 	= JRequest::getVar('category_id',null, array());
									$property_type	= JRequest::getInt('property_type',0);
									$keyword		= OSPHelper::getStringRequest('keyword','','');
									$nbed			= JRequest::getInt('nbed','');
									$nbath			= JRequest::getInt('nbath','');
									$isfeatured		= JRequest::getInt('isfeatured','');
									$nrooms			= JRequest::getInt('nrooms','');
									$orderby		= JRequest::getVar('orderby','a.id');
									$ordertype		= JRequest::getVar('ordertype','desc');
									$limitstart		= JRequest::getInt('limitstart',0);
									$limit			= JRequest::getInt('limit',$configClass['general_number_properties_per_page']);
									$favorites		= JRequest::getInt('favorites',0);
									$price			= JRequest::getInt('price',0);
									$city_id		= JRequest::getInt('city',0);
									$state_id		= JRequest::getInt('state_id',0);
									$country_id		= JRequest::getInt('country_id',HelperOspropertyCommon::getDefaultCountry());
									OspropertyListing::listProperties($option,'',null,$agent->id,$property_type,$keyword,$nbed,$nbath,0,0,$nrooms,$orderby,$ordertype,$limitstart,$limit,'',$price,$filterParams,$city_id,$state_id,$country_id);
									?>
									<input type="hidden" name="option" value="com_osproperty" />
									<input type="hidden" name="task" value="agent_info" />
									<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid',0)?>" />
									<input type="hidden" name="id" value="<?php echo $agent->id?>" />
									
									</form>
									<?php
									//echo $panetab->endPanel();
									echo "</div>";
								}
								?>
								<?php
								if($configClass['show_agent_contact'] == 1){
								?>
								<div class="tab-pane <?php echo $class2;?>" id="panel2">
									<div class="block_caption" id="comment_form_caption">
										<strong><?php echo JText::_('OS_CONTACT')?></strong>
									</div>
									<BR />
									<form method="POST" action="<?php echo JRoute::_('index.php?option=com_osproperty')?>" name="contactForm" id="contactForm">
										<?php
										HelperOspropertyCommon::contactForm('contactForm');
										?>
										<input type="hidden" name="option" value="com_osproperty" />
										<input type="hidden" name="task" value="agent_submitcontact" />
										<input type="hidden" name="id" value="<?php echo $agent->id?>" />
										<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid',0)?>" />
									</form>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
	}
	
	/**
	 * Agent register form
	 *
	 * @param unknown_type $option
	 * @param unknown_type $user
	 */
	static function agentRegisterForm($option,$user,$lists,$companies){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		OSPHelper::generateHeading(2,JText::_('OS_AGENT_REGISTER'));
		jimport('joomla.filesystem.file');
		if(JFile::exists(JPATH_ROOT.'/templates/'.$mainframe->getTemplate().'/html/com_osproperty/layouts/agentregistration.php')){
			$tpl = new OspropertyTemplate(JPATH_ROOT.'/templates/'.$mainframe->getTemplate().'/html/com_osproperty/layouts/');
		}else{
			$tpl = new OspropertyTemplate(JPATH_COMPONENT.'/helpers/layouts/');
		}
		$tpl->set('user',$user);
		$tpl->set('companies',$companies);
		$tpl->set('lists',$lists);
		$tpl->set('configClass',$configClass);	
		$body = $tpl->fetch("agentregistration.php");	
		echo $body;	
	}
}

?>