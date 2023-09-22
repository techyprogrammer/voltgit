<?php
/**
 * @package     VikRentCar
 * @subpackage  com_vikrentcar
 * @author      Alessio Gaggii - e4j - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2018 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

defined('ABSPATH') or die('No script kiddies please!');

$row = $this->row;
$all_cars = $this->all_cars;
$car = $this->car;
$busy = $this->busy;
$locations = $this->locations;
$customer = $this->customer;

$dbo = JFactory::getDbo();
$vrc_app = new VrcApplication();
$vrc_app->loadSelect2();
$pgoto = VikRequest::getString('goto', '', 'request');
$currencysymb = VikRentCar::getCurrencySymb(true);
$nowdf = VikRentCar::getDateFormat(true);
$nowtf = VikRentCar::getTimeFormat(true);
if ($nowdf == "%d/%m/%Y") {
	$rit = date('d/m/Y', $row['ritiro']);
	$con = date('d/m/Y', $row['consegna']);
	$df = 'd/m/Y';
} elseif ($nowdf == "%m/%d/%Y") {
	$rit = date('m/d/Y', $row['ritiro']);
	$con = date('m/d/Y', $row['consegna']);
	$df = 'm/d/Y';
} else {
	$rit = date('Y/m/d', $row['ritiro']);
	$con = date('Y/m/d', $row['consegna']);
	$df = 'Y/m/d';
}
$arit = getdate($row['ritiro']);
$acon = getdate($row['consegna']);
$ritho = '';
$conho = '';
$ritmi = '';
$conmi = '';
for ($i=0; $i < 24; $i++) {
	$ritho .= "<option value=\"".$i."\"".($arit['hours']==$i ? " selected=\"selected\"" : "").">".($i < 10 ? "0".$i : $i)."</option>\n";
	$conho .= "<option value=\"".$i."\"".($acon['hours']==$i ? " selected=\"selected\"" : "").">".($i < 10 ? "0".$i : $i)."</option>\n";
}
for ($i=0; $i < 60; $i++) {
	$ritmi .= "<option value=\"".$i."\"".($arit['minutes']==$i ? " selected=\"selected\"" : "").">".($i < 10 ? "0".$i : $i)."</option>\n";
	$conmi .= "<option value=\"".$i."\"".($acon['minutes']==$i ? " selected=\"selected\"" : "").">".($i < 10 ? "0".$i : $i)."</option>\n";
}
if ($row['hourly'] == 1) {
	$secdiff = $row['consegna'] - $row['ritiro'];
	$daysdiff = $secdiff / 86400;
	if (is_int($daysdiff)) {
		if ($daysdiff < 1) {
			$daysdiff = 1;
		}
	} else {
		if ($daysdiff < 1) {
			$daysdiff = 1;
			$checkhourly = true;
			$ophours = $secdiff / 3600;
			$hoursdiff = intval(round($ophours));
			if ($hoursdiff < 1) {
				$hoursdiff = 1;
			}
		}
	}
}
if (is_array($row)) {
	$checkhourscharges = 0;
	$ppickup = $row['ritiro'];
	$prelease = $row['consegna'];
	$secdiff = $prelease - $ppickup;
	$daysdiff = $secdiff / 86400;
	if (is_int($daysdiff)) {
		if ($daysdiff < 1) {
			$daysdiff = 1;
		}
	} else {
		if ($daysdiff < 1) {
			$daysdiff = 1;
		} else {
			$sum = floor($daysdiff) * 86400;
			$newdiff = $secdiff - $sum;
			$maxhmore = VikRentCar::getHoursMoreRb() * 3600;
			if ($maxhmore >= $newdiff) {
				$daysdiff = floor($daysdiff);
			} else {
				$daysdiff = ceil($daysdiff);
				//vikrentcar 1.6
				$ehours = intval(round(($newdiff - $maxhmore) / 3600));
				$checkhourscharges = $ehours;
				if ($checkhourscharges > 0) {
					$aehourschbasp = VikRentCar::applyExtraHoursChargesBasp();
				}
				//
			}
		}
	}
}
$is_cust_cost = (!empty($row['cust_cost']) && $row['cust_cost'] > 0.00);
$pickup_place = '';
$dropoff_place = '';
$ivas = array();
$wiva = "";
$jstaxopts = '<option value=\"\">'.JText::translate('VRNEWOPTFOUR').'</option>';
$q = "SELECT * FROM `#__vikrentcar_iva`;";
$dbo->setQuery($q);
$dbo->execute();
if ($dbo->getNumRows() > 0) {
	$ivas = $dbo->loadAssocList();
	$wiva = "<select name=\"aliq\"><option value=\"\">".JText::translate('VRNEWOPTFOUR')."</option>\n";
	foreach ($ivas as $iv) {
		$wiva .= "<option value=\"".$iv['id']."\" data-aliqid=\"".$iv['id']."\">".(empty($iv['name']) ? $iv['aliq']."%" : $iv['name']." - ".$iv['aliq']."%")."</option>\n";
		$jstaxopts .= '<option value=\"'.$iv['id'].'\">'.(empty($iv['name']) ? $iv['aliq']."%" : addslashes($iv['name'])." - ".$iv['aliq']."%").'</option>';
	}
	$wiva .= "</select>\n";
}

if ($row['status'] == "confirmed") {
	$saystaus = '<span class="label label-success">'.JText::translate('VRCONFIRMED').'</span>';
} elseif ($row['status']=="standby") {
	$saystaus = '<span class="label label-warning">'.JText::translate('VRSTANDBY').'</span>';
} else {
	$saystaus = '<span class="label label-error" style="background-color: #d9534f;">'.JText::translate('VRCANCELLED').'</span>';
}
//Switch car
$switching = false;
$switcher = '';
if (is_array($row) && (!empty($row['idtar']) || $is_cust_cost)) {
	$switching = true;
	// @wponly lite - car switching not supported
}
//
?>
<script type="text/javascript">
Joomla.submitbutton = function(task) {
	if ( task == 'removebusy' ) {
		if (confirm('<?php echo addslashes(JText::translate('VRJSDELBUSY')); ?>')) {
			Joomla.submitform(task, document.adminForm);
		} else {
			return false;
		}
	} else if ( task == 'updatebusy' ) {
		var ord_tot = document.getElementById('order_total').value;
		var orig_ord_tot = document.getElementById('order_total').getAttribute('placeholder');
		if (ord_tot.length) {
			Joomla.submitform(task, document.adminForm);
			return true;
		}
		if (confirm('<?php echo addslashes(JText::translate('VRCRECALCORDTOTCONF')); ?>')) {
			Joomla.submitform(task, document.adminForm);
		} else {
			document.getElementById('order_total').value = orig_ord_tot;
			Joomla.submitform(task, document.adminForm);
		}
	} else {
		Joomla.submitform(task, document.adminForm);
	}
}
function vrcSwitchCar(newcarid) {
	var curcarid = '<?php echo $car['id']; ?>';
	if (parseInt(curcarid) != parseInt(newcarid) && newcarid.length) {
		jQuery('#vrcsetnewcar').text('<?php echo addslashes(JText::translate('VRPEDITBUSYSETCARCHANGE')); ?>').fadeIn();
	} else {
		jQuery('#vrcsetnewcar').text('').fadeOut();
	}
}
var vrcMessages = {
	"jscurrency": "<?php echo $currencysymb; ?>",
	"extracnameph": "<?php echo addslashes(JText::translate('VRPEDITBUSYEXTRACNAME')); ?>",
	"taxoptions" : "<?php echo $jstaxopts; ?>"
};
jQuery(document).ready(function() {
	jQuery(".vrc-cswitcher-select").select2({placeholder: '<?php echo addslashes(JText::translate('VRSWITCHCWITH')); ?>'});
	jQuery(".vrc-locations-select").select2();
});
</script>
<script type="text/javascript">
/* custom extra services for the order */
function vrcAddExtraCost() {
	var telem = jQuery("#vrc-ebusy-extracosts");
	if (telem.length > 0) {
		var extracostcont = "<div class=\"vrc-editbooking-car-extracost\">"+"\n"+
			"<div class=\"vrc-ebusy-extracosts-cellname\"><input type=\"text\" name=\"extracn[]\" value=\"\" placeholder=\""+vrcMessages.extracnameph+"\" size=\"25\" /></div>"+"\n"+
			"<div class=\"vrc-ebusy-extracosts-cellcost\"><span class=\"vrc-ebusy-extracosts-currency\">"+vrcMessages.jscurrency+"</span> <input type=\"number\" step=\"any\" name=\"extracc[]\" value=\"0.00\" size=\"5\" /></div>"+"\n"+
			"<div class=\"vrc-ebusy-extracosts-celltax\"><select name=\"extractx[]\">"+vrcMessages.taxoptions+"</select></div>"+"\n"+
			"<div class=\"vrc-ebusy-extracosts-cellrm\"><button class=\"btn btn-danger\" type=\"button\" onclick=\"vrcRemoveExtraCost(this);\">X</button></div>"+"\n"+
		"</div>";
		telem.find(".vrc-editbooking-car-extracosts-wrap").append(extracostcont);
	}
}
function vrcRemoveExtraCost(elem) {
	var parel = jQuery(elem).closest(".vrc-editbooking-car-extracost");
	if (parel.length > 0) {
		parel.remove();
	}
}
</script>

<div class="vrc-bookingdet-topcontainer vrc-editbooking-topcontainer">
	<form name="adminForm" id="adminForm" action="index.php" method="post">
		
		<div class="vrc-bookdet-container">
			<div class="vrc-bookdet-wrap">
				<div class="vrc-bookdet-head">
					<span>ID</span>
				</div>
				<div class="vrc-bookdet-foot">
					<span><?php echo $row['id']; ?></span>
				</div>
			</div>
			<div class="vrc-bookdet-wrap">
				<div class="vrc-bookdet-head">
					<span><?php echo JText::translate('VREDITORDERONE'); ?></span>
				</div>
				<div class="vrc-bookdet-foot">
					<span><?php echo date($df.' '.$nowtf, $row['ts']); ?></span>
				</div>
			</div>
		<?php
		if (count($customer)) {
		?>
			<div class="vrc-bookdet-wrap">
				<div class="vrc-bookdet-head">
					<span><?php echo JText::translate('VRCDRIVERNOMINATIVE'); ?></span>
				</div>
				<div class="vrc-bookdet-foot">
					<!-- @wponly lite - customer editing not supported -->
					<?php echo (isset($customer['country_img']) ? $customer['country_img'].' ' : '').'<span>'.ltrim($customer['first_name'].' '.$customer['last_name']).'</span>'; ?>
				</div>
			</div>
		<?php
		} elseif (!empty($row['nominative'])) {
		?>
			<div class="vrc-bookdet-wrap">
				<div class="vrc-bookdet-head">
					<span><?php echo JText::translate('VRCDRIVERNOMINATIVE'); ?></span>
				</div>
				<div class="vrc-bookdet-foot">
					<?php echo $row['nominative']; ?>
				</div>
			</div>
		<?php
		}
		?>
			<div class="vrc-bookdet-wrap">
				<div class="vrc-bookdet-head">
					<span><?php echo JText::translate('VREDITORDERFOUR'); ?></span>
				</div>
				<div class="vrc-bookdet-foot">
					<?php echo $row['days']; ?>
				</div>
			</div>
			<div class="vrc-bookdet-wrap">
				<div class="vrc-bookdet-head">
					<span><?php echo JText::translate('VREDITORDERFIVE'); ?></span>
				</div>
				<div class="vrc-bookdet-foot">
				<?php
				$ritiro_info = getdate($row['ritiro']);
				$short_wday = JText::translate('VR'.strtoupper(substr($ritiro_info['weekday'], 0, 3)));
				?>
					<?php echo $short_wday.', '.date($df.' '.$nowtf, $row['ritiro']); ?>
				</div>
			</div>
			<div class="vrc-bookdet-wrap">
				<div class="vrc-bookdet-head">
					<span><?php echo JText::translate('VREDITORDERSIX'); ?></span>
				</div>
				<div class="vrc-bookdet-foot">
				<?php
				$consegna_info = getdate($row['consegna']);
				$short_wday = JText::translate('VR'.strtoupper(substr($consegna_info['weekday'], 0, 3)));
				?>
					<?php echo $short_wday.', '.date($df.' '.$nowtf, $row['consegna']); ?>
				</div>
			</div>
		<?php
		if (!empty($row['idplace'])) {
			$pickup_place = VikRentCar::getPlaceName($row['idplace']);
			?>
			<div class="vrc-bookdet-wrap">
				<div class="vrc-bookdet-head">
					<span><?php echo JText::translate('VRRITIROCAR'); ?></span>
				</div>
				<div class="vrc-bookdet-foot">
					<?php echo $pickup_place; ?>
				</div>
			</div>
			<?php
		}
		if (!empty($row['idreturnplace'])) {
			$dropoff_place = VikRentCar::getPlaceName($row['idreturnplace']);
			?>
			<div class="vrc-bookdet-wrap">
				<div class="vrc-bookdet-head">
					<span><?php echo JText::translate('VRRETURNCARORD'); ?></span>
				</div>
				<div class="vrc-bookdet-foot">
					<?php echo $dropoff_place; ?>
				</div>
			</div>
			<?php
		}
		?>
			<div class="vrc-bookdet-wrap">
				<div class="vrc-bookdet-head">
					<span><?php echo JText::translate('VRSTATUS'); ?></span>
				</div>
				<div class="vrc-bookdet-foot">
					<span><?php echo $saystaus; ?></span>
				</div>
			</div>
		</div>

		<div class="vrc-bookingdet-innertop">
			<div class="vrc-bookingdet-tabs">
				<div class="vrc-bookingdet-tab vrc-bookingdet-tab-active" data-vrctab="vrc-tab-details"><?php echo JText::translate('VRMODRES'); ?></div>
			</div>
		</div>

		<div class="vrc-bookingdet-tab-cont" id="vrc-tab-details" style="display: block;">
			<div class="vrc-bookingdet-innercontainer">
				<div class="vrc-bookingdet-customer">
					<div class="vrc-bookingdet-detcont<?php echo $row['closure'] > 0 ? ' vrc-bookingdet-closure' : ''; ?>">
						<div class="vrc-editbooking-custarea-lbl">
							<?php echo JText::translate('VREDITORDERTWO'); ?>
						</div>
						<div class="vrc-editbooking-custarea">
							<textarea name="custdata"><?php echo htmlspecialchars($row['custdata']); ?></textarea>
						<?php
						if ($row['closure'] > 0) {
							?>
							<p><span class="label vrc-stopsales-sp"><?php echo JText::translate('VRCSTOPRENTALS'); ?></span></p>
							<?php
						}
						?>
						</div>
					</div>
					<div class="vrc-bookingdet-detcont">
						<div class="vrc-bookingdet-checkdt">
							<label for="pickupdate"><?php echo JText::translate('VRPEDITBUSYFOUR'); ?></label>
							<?php echo $vrc_app->getCalendar($rit, 'pickupdate', 'pickupdate', $nowdf, array('class'=>'', 'size'=>'10', 'maxlength'=>'19', 'todayBtn' => 'true')); ?>
							<span class="vrc-time-selects">
								<select name="pickuph"><?php echo $ritho; ?></select>
								<span class="vrc-time-selects-divider">:</span>
								<select name="pickupm"><?php echo $ritmi; ?></select>
							</span>
						</div>
						<div class="vrc-bookingdet-checkdt">
							<label for="releasedate"><?php echo JText::translate('VRPEDITBUSYSIX'); ?></label>
							<?php echo $vrc_app->getCalendar($con, 'releasedate', 'releasedate', $nowdf, array('class'=>'', 'size'=>'10', 'maxlength'=>'19', 'todayBtn' => 'true')); ?>
							<span class="vrc-time-selects">
								<select name="releaseh"><?php echo $conho; ?></select>
								<span class="vrc-time-selects-divider">:</span>
								<select name="releasem"><?php echo $conmi; ?></select>
							</span>
						</div>
					</div>
				</div>
				<div class="vrc-editbooking-summary">
			<?php
			if (is_array($row) && (!empty($row['idtar']) || $is_cust_cost)) {
				//order from front end or correctly saved - start
				$wselplace = '<select class="vrc-locations-select" name="idplace" id="idplace"><option value=""> ----- </option>'."\n";
				foreach ($locations as $lk => $lv) {
					$wselplace .= '<option value="'.$lv['id'].'"'.($lv['id'] == $row['idplace'] ? ' selected="selected"' : '').'>'.$lv['name'].'</option>'."\n";
				}
				$wselplace .= '</select>'."\n";
				$wselreturnplace = '<select class="vrc-locations-select" name="idreturnplace" id="idreturnplace"><option value=""> ----- </option>'."\n";
				foreach ($locations as $lk => $lv) {
					$wselreturnplace .= '<option value="'.$lv['id'].'"'.($lv['id'] == $row['idreturnplace'] ? ' selected="selected"' : '').'>'.$lv['name'].'</option>'."\n";
				}
				$wselreturnplace .= '</select>'."\n";
				if ($row['hourly'] == 1) {
					$q = "SELECT * FROM `#__vikrentcar_dispcosthours` WHERE `hours`=".(int)$hoursdiff." AND `idcar`=".(int)$row['idcar']." ORDER BY `#__vikrentcar_dispcosthours`.`cost` ASC;";
				} else {
					$q = "SELECT * FROM `#__vikrentcar_dispcost` WHERE `days`=".(int)$row['days']." AND `idcar`=".(int)$row['idcar']." ORDER BY `#__vikrentcar_dispcost`.`cost` ASC;";
				}
				$dbo->setQuery($q);
				$dbo->execute();
				$tottars = $dbo->getNumRows();
				$proceedtars = false;
				if ($tottars == 0) {
					if ($row['hourly'] == 1) {
						//there are no hourly prices
						$q = "SELECT * FROM `#__vikrentcar_dispcost` WHERE `days`=".(int)$row['days']." AND `idcar`=".(int)$row['idcar']." ORDER BY `#__vikrentcar_dispcost`.`cost` ASC;";
						$dbo->setQuery($q);
						$dbo->execute();
						if ($dbo->getNumRows() > 0) {
							$proceedtars = true;
						}
					}
				} else {
					$proceedtars = true;
				}
				if ($proceedtars) {
					$tars = $dbo->loadAssocList();
					if ($row['hourly'] == 1) {
						foreach ($tars as $kt => $vt) {
							$tars[$kt]['days'] = 1;
						}
					}
					if ($checkhourscharges > 0 && $aehourschbasp == true) {
						$ret = VikRentCar::applyExtraHoursChargesCar($tars, $row['idcar'], $checkhourscharges, $daysdiff, false, true, true);
						$tars = $ret['return'];
						$calcdays = $ret['days'];
					}
					if ($checkhourscharges > 0 && $aehourschbasp == false) {
						$tars = VikRentCar::extraHoursSetPreviousFareCar($tars, $row['idcar'], $checkhourscharges, $daysdiff, true);
						$tars = VikRentCar::applySeasonsCar($tars, $row['ritiro'], $row['consegna'], $row['idplace']);
						$ret = VikRentCar::applyExtraHoursChargesCar($tars, $row['idcar'], $checkhourscharges, $daysdiff, true, true, true);
						$tars = $ret['return'];
						$calcdays = $ret['days'];
					} else {
						$tars = VikRentCar::applySeasonsCar($tars, $row['ritiro'], $row['consegna'], $row['idplace']);
					}
					?>
					<input type="hidden" name="areprices" value="yes"/>
					<div class="vrc-editbooking-tbl">
					
						<div class="vrc-bookingdet-summary-car vrc-editbooking-summary-car">
							<div class="vrc-editbooking-summary-car-head">
								<div class="vrc-bookingdet-summary-carnum"><?php VikRentCarIcons::e('car'); ?> <?php echo $car['name']; ?></div>
							<?php
							if ($switching) {
								?>
								<div class="vrc-editbooking-car-switch">
									<?php echo $switcher; ?>
									<span id="vrcsetnewcar" style="display: none;"></span>
								</div>
								<?php
							}
							?>
							</div>
							<div class="vrc-editbooking-car-traveler">
								<h4><?php echo JText::translate('VRPEDITBUSYLOCATIONS'); ?></h4>
								<div class="vrc-editbooking-car-traveler-guestsinfo">
									<div class="vrc-editbooking-car-traveler-name">
										<label for="idplace"><?php echo JText::translate('VRPEDITBUSYPICKPLACE'); ?></label>
										<?php echo $wselplace; ?>
									</div>
									<div class="vrc-editbooking-car-traveler-name">
										<label for="idreturnplace"><?php echo JText::translate('VRPEDITBUSYDROPPLACE'); ?></label>
										<?php echo $wselreturnplace; ?>
									</div>
								</div>
							</div>
							<div class="vrc-editbooking-car-pricetypes">
								<h4><?php echo JText::translate('VRPEDITBUSYSEVEN'); ?></h4>
								<div class="vrc-editbooking-car-pricetypes-wrap">
							<?php
							if ($is_cust_cost) {
								//custom rate
								?>
									<div class="vrc-editbooking-car-pricetype vrc-editbooking-car-pricetype-active">
										<div class="vrc-editbooking-car-pricetype-inner">
											<label for="pid" class="hasTooltip" title="<?php echo JText::translate('VRCRENTCUSTRATETAXHELP'); ?>">
												<?php echo JText::translate('VRCRENTCUSTRATEPLAN'); ?>
											</label>
											<div class="vrc-editbooking-car-pricetype-cost">
												<?php echo $currencysymb; ?> <input type="number" step="any" name="cust_cost" value="<?php echo (float)$row['cust_cost']; ?>" size="4" onchange="if (this.value.length) {document.getElementById('pid').checked = true; jQuery('#pid').trigger('change');}"/>
												<div class="vrc-editbooking-car-pricetype-seltax" id="tax" style="display: block;">
													<?php echo (!empty($wiva) ? str_replace('data-aliqid="'.(int)$row['cust_idiva'].'"', 'selected="selected"', $wiva) : ''); ?>
												</div>
											</div>
										</div>
										<div class="vrc-editbooking-car-pricetype-check">
											<input class="vrc-pricetype-radio" type="radio" name="priceid" id="pid" value="" checked="checked" />
										</div>
									</div>
								<?php
								//print the standard rates anyway
								foreach ($tars as $k => $t) {
									?>
									<div class="vrc-editbooking-car-pricetype">
										<div class="vrc-editbooking-car-pricetype-inner">
											<label for="pid<?php echo $t['idprice']; ?>"><?php echo VikRentCar::getPriceName($t['idprice']).(strlen($t['attrdata']) ? " - ".VikRentCar::getPriceAttr($t['idprice']).": ".$t['attrdata'] : ""); ?></label>
											<div class="vrc-editbooking-car-pricetype-cost">
												<?php echo $currencysymb." ".VikRentCar::numberFormat(VikRentCar::sayCostPlusIva($t['cost'], $t['idprice'], $row)); ?>
											</div>
										</div>
										<div class="vrc-editbooking-car-pricetype-check">
											<input class="vrc-pricetype-radio" type="radio" name="priceid" id="pid<?php echo (int)$t['idprice']; ?>" value="<?php echo (int)$t['idprice']; ?>" />
										</div>
									</div>
								<?php
								}
							} else {
								$sel_rate_changed = false;
								foreach ($tars as $k => $t) {
									$cur_cost = VikRentCar::sayCostPlusIva($t['cost'], $t['idprice'], $row);
									$sel_rate_changed = $t['id'] == $row['idtar'] ? $cur_cost : $sel_rate_changed;
									?>
									<div class="vrc-editbooking-car-pricetype<?php echo $t['id'] == $row['idtar'] ? ' vrc-editbooking-car-pricetype-active' : ''; ?>">
										<div class="vrc-editbooking-car-pricetype-inner">
											<label for="pid<?php echo $t['idprice']; ?>"><?php echo VikRentCar::getPriceName($t['idprice']).(strlen($t['attrdata']) ? " - ".VikRentCar::getPriceAttr($t['idprice']).": ".$t['attrdata'] : ""); ?></label>
											<div class="vrc-editbooking-car-pricetype-cost">
												<?php echo $currencysymb." ".VikRentCar::numberFormat($cur_cost); ?>
											</div>
										</div>
										<div class="vrc-editbooking-car-pricetype-check">
											<input class="vrc-pricetype-radio" type="radio" name="priceid" id="pid<?php echo (int)$t['idprice']; ?>" value="<?php echo (int)$t['idprice']; ?>"<?php echo ($t['id'] == $row['idtar'] ? " checked=\"checked\"" : ""); ?>/>
										</div>
									</div>
									<?php
								}
								//print the set custom rate anyway
								?>
									<div class="vrc-editbooking-car-pricetype">
										<div class="vrc-editbooking-car-pricetype-inner">
											<label for="cust_cost" class="vrc-custrate-lbl-add hasTooltip" title="<?php echo JText::translate('VRCRENTCUSTRATETAXHELP'); ?>"><?php echo JText::translate('VRCRENTCUSTRATEPLANADD'); ?></label>
											<div class="vrc-editbooking-car-pricetype-cost">
												<?php echo $currencysymb; ?> <input type="number" step="any" name="cust_cost" id="cust_cost" value="" placeholder="<?php echo VikRentCar::numberFormat(($sel_rate_changed !== false ? $sel_rate_changed : 0)); ?>" size="4" onchange="if (this.value.length) {document.getElementById('priceid').checked = true; jQuery('#priceid').trigger('change');document.getElementById('tax').style.display = 'block';}" />
												<div class="vrc-editbooking-car-pricetype-seltax" id="tax" style="display: none;">
													<?php echo (!empty($wiva) ? $wiva : ''); ?>
												</div>
											</div>
										</div>
										<div class="vrc-editbooking-car-pricetype-check">
											<input class="vrc-pricetype-radio" type="radio" name="priceid" id="priceid" value="" onclick="document.getElementById('tax').style.display = 'block';" />
										</div>
									</div>
								<?php
							}
							?>
								</div>
							</div>
						<?php
						// @wponly lite - cars options not supported
						$optionals = '';
						$arropt = array();
						//Car Options Start
						//Car Options End

						//custom extra services for the order Start
						// @wponly lite - extra fees not supported
						//custom extra services for the order End
						?>
						</div>

						<div class="vrc-bookingdet-summary-car vrc-editbooking-summary-car vrc-editbooking-summary-totpaid">
							<div class="vrc-editbooking-summary-car-head">
								<div class="vrc-editbooking-ordtot">
									<label for="order_total"><?php echo JText::translate('VREDITORDERNINE'); ?></label>
									<?php echo $currencysymb; ?> <input type="number" min="0" step="any" id="order_total" name="order_total" value="" placeholder="<?php echo JHtml::fetch('esc_attr', $row['order_total']); ?>" style="margin: 0; width: 80px !important;"/>
								</div>
								<div class="vrc-editbooking-totpaid">
									<label for="totpaid"><?php echo JText::translate('VRCAMOUNTPAID'); ?></label>
									<?php echo $currencysymb; ?> <input type="number" min="0" step="any" id="totpaid" name="totpaid" value="<?php echo !is_null($row['totpaid']) ? (float)$row['totpaid'] : ''; ?>" style="margin: 0; width: 80px !important;"/>
								</div>
							</div>
						</div>
					</div>
					<?php
				} else {
					?>
					<p class="err"><?php echo JText::translate('VRPEDITBUSYERRNOFARES'); ?></p>
					<input type="hidden" id="order_total" name="order_total" placeholder="<?php echo $row['order_total']; ?>" value=""/>
					<?php
				}
				//order from front end or correctly saved - end
			} elseif (is_array($row) && empty($row['idtar'])) {
				//order is a quick reservation from administrator - start
				$proceedtars = false;
				if ($row['hourly'] == 1) {
					$q = "SELECT * FROM `#__vikrentcar_dispcosthours` WHERE `hours`=".(int)$hoursdiff." AND `idcar`=".(int)$row['idcar']." ORDER BY `#__vikrentcar_dispcosthours`.`cost` ASC;";
				} else {
					$q = "SELECT * FROM `#__vikrentcar_dispcost` WHERE `days`=".(int)$row['days']." AND `idcar`=".(int)$row['idcar']." ORDER BY `#__vikrentcar_dispcost`.`cost` ASC;";
				}
				$dbo->setQuery($q);
				$dbo->execute();
				$tottars = $dbo->getNumRows();
				if ($tottars == 0) {
					if ($row['hourly'] == 1) {
						$q = "SELECT * FROM `#__vikrentcar_dispcost` WHERE `days`=".(int)$row['days']." AND `idcar`=".(int)$row['idcar']." ORDER BY `#__vikrentcar_dispcost`.`cost` ASC;";
						$dbo->setQuery($q);
						$dbo->execute();
						if ($dbo->getNumRows() > 0) {
							$proceedtars = true;
						}
					}
				} else {
					$proceedtars = true;
				}
				if ($proceedtars) {
					$tars = $dbo->loadAssocList();
					?>
					<input type="hidden" name="areprices" value="quick"/>
					<div class="vrc-editbooking-tbl">
					
						<div class="vrc-bookingdet-summary-car vrc-editbooking-summary-car">
							<div class="vrc-editbooking-summary-car-head">
								<div class="vrc-bookingdet-summary-carnum"><?php VikRentCarIcons::e('car'); ?> <?php echo $car['name']; ?></div>
							</div>
							<div class="vrc-editbooking-car-pricetypes">
								<h4><?php echo JText::translate('VRPEDITBUSYSEVEN'); ?><?php echo $row['closure'] < 1 && $row['status'] != 'cancelled' ? '&nbsp;&nbsp; '.$vrc_app->createPopover(array('title' => JText::translate('VRPEDITBUSYSEVEN'), 'content' => JText::translate('VRCMISSPRTYPECARH'))) : ''; ?></h4>
								<div class="vrc-editbooking-car-pricetypes-wrap">
								<?php
								//print the standard rates
								foreach ($tars as $k => $t) {
									?>
									<div class="vrc-editbooking-car-pricetype">
										<div class="vrc-editbooking-car-pricetype-inner">
											<label for="pid<?php echo $t['idprice']; ?>"><?php echo VikRentCar::getPriceName($t['idprice']).(strlen($t['attrdata']) ? " - ".VikRentCar::getPriceAttr($t['idprice']).": ".$t['attrdata'] : ""); ?></label>
											<div class="vrc-editbooking-car-pricetype-cost">
												<?php echo $currencysymb." ".VikRentCar::numberFormat(VikRentCar::sayCostPlusIva($t['cost'], $t['idprice'], $row)); ?>
											</div>
										</div>
										<div class="vrc-editbooking-car-pricetype-check">
											<input class="vrc-pricetype-radio" type="radio" name="priceid" id="pid<?php echo (int)$t['idprice']; ?>" value="<?php echo (int)$t['idprice']; ?>" />
										</div>
									</div>
									<?php
								}
								//print the custom cost
								?>
									<div class="vrc-editbooking-car-pricetype">
										<div class="vrc-editbooking-car-pricetype-inner">
											<label for="cust_cost" class="vrc-custrate-lbl-add hasTooltip" title="<?php echo JText::translate('VRCRENTCUSTRATETAXHELP'); ?>"><?php echo JText::translate('VRCRENTCUSTRATEPLANADD'); ?></label>
											<div class="vrc-editbooking-car-pricetype-cost">
												<?php echo $currencysymb; ?> <input type="number" step="any" name="cust_cost" id="cust_cost" value="" placeholder="<?php echo VikRentCar::numberFormat(0); ?>" size="4" onchange="if (this.value.length) {document.getElementById('priceid').checked = true; jQuery('#priceid').trigger('change'); document.getElementById('tax').style.display = 'block';}" />
												<div class="vrc-editbooking-car-pricetype-seltax" id="tax" style="display: none;"><?php echo (!empty($wiva) ? $wiva : ''); ?></div>
											</div>
										</div>
										<div class="vrc-editbooking-car-pricetype-check">
											<input class="vrc-pricetype-radio" type="radio" name="priceid" id="priceid" value="" onclick="document.getElementById('tax').style.display = 'block';" />
										</div>
									</div>
								<?php
								//
								?>
								</div>
							</div>
						<?php
						$optionals = empty($car['idopt']) ? '' : VikRentCar::getCarOptionals($car['idopt']);
						//Car Options Start
						if (is_array($optionals)) {
							?>
							<div class="vrc-editbooking-car-options">
								<h4><?php echo JText::translate('VRPEDITBUSYEIGHT'); ?></h4>
								<div class="vrc-editbooking-car-options-wrap">
								<?php
								foreach ($optionals as $k => $o) {
									?>
									<div class="vrc-editbooking-car-option">
										<div class="vrc-editbooking-car-option-inner">
											<label for="optid<?php echo $o['id']; ?>"><?php echo $o['name']; ?></label>
											<div class="vrc-editbooking-car-option-check">
												<?php echo (intval($o['hmany'])==1 ? "<input type=\"number\" name=\"optid".$o['id']."\" id=\"optid".$o['id']."\" value=\"\" min=\"0\" size=\"4\" style=\"width: 50px !important;\"/>" : "<input type=\"checkbox\" name=\"optid".$o['id']."\" id=\"optid".$o['id']."\" value=\"1\" />"); ?>
											</div>
										</div>
									</div>
									<?php
								}
								?>
								</div>
							</div>
							<?php
						}
						//Car Options End
						?>
						</div>
						
						<div class="vrc-bookingdet-summary-car vrc-editbooking-summary-car vrc-editbooking-summary-totpaid">
							<div class="vrc-editbooking-summary-car-head">
								<div class="vrc-editbooking-ordtot">
									<label for="order_total"><?php echo JText::translate('VREDITORDERNINE'); ?></label>
									<?php echo $currencysymb; ?> <input type="number" min="0" step="any" id="order_total" name="order_total" value="<?php echo (float)$row['order_total']; ?>" placeholder="<?php echo JHtml::fetch('esc_attr', $row['order_total']); ?>" style="margin: 0; width: 80px !important;"/>
								</div>
								<div class="vrc-editbooking-totpaid">
									<label for="totpaid"><?php echo JText::translate('VRCAMOUNTPAID'); ?></label>
									<?php echo $currencysymb; ?> <input type="number" min="0" step="any" id="totpaid" name="totpaid" value="<?php echo !is_null($row['totpaid']) ? (float)$row['totpaid'] : ''; ?>" style="margin: 0; width: 80px !important;"/>
								</div>
							</div>
						</div>
					</div>
					<?php
				} else {
					?>
					<p class="err"><?php echo JText::translate('VRPEDITBUSYERRNOFARES'); ?></p>
					<input type="hidden" id="order_total" name="order_total" placeholder="<?php echo JHtml::fetch('esc_attr', $row['order_total']); ?>" value=""/>
					<?php
				}
				//order is a quick reservation from administrator - end
			}
			?>
				</div>
			</div>
		</div>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="idcar" value="<?php echo (int)$row['idcar']; ?>">
		<input type="hidden" name="idbusy" value="<?php echo (int)$busy['id']; ?>">
		<input type="hidden" name="idorder" value="<?php echo (int)$row['id']; ?>">
		<input type="hidden" name="option" value="com_vikrentcar" />
		<?php
		echo !empty($pgoto) ? '<input type="hidden" name="goto" value="' . JHtml::fetch('esc_attr', $pgoto) . '">' : '';
		$preturn = VikRequest::getString('return', '', 'request');
		echo !empty($preturn) ? '<input type="hidden" name="return" value="' . JHtml::fetch('esc_attr', $preturn) . '">' : '';
		?>
	</form>
</div>

<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#pickupdate').val('<?php echo $rit; ?>').attr('data-alt-value', '<?php echo $rit; ?>');
	jQuery('#releasedate').val('<?php echo $con; ?>').attr('data-alt-value', '<?php echo $con; ?>');
	jQuery('.vrc-pricetype-radio').change(function() {
		jQuery(this).closest('.vrc-editbooking-car-pricetypes').find('.vrc-editbooking-car-pricetype.vrc-editbooking-car-pricetype-active').removeClass('vrc-editbooking-car-pricetype-active');
		jQuery(this).closest('.vrc-editbooking-car-pricetype').addClass('vrc-editbooking-car-pricetype-active');
	});
});
if (jQuery.isFunction(jQuery.fn.tooltip)) {
	jQuery(".hasTooltip").tooltip();
}
</script>
