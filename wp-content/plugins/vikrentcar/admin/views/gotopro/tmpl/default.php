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

$lic_key = $this->lic_key;
$lic_date = $this->lic_date;
$is_pro = $this->is_pro;

$nowdf = VikRentCar::getDateFormat();
if ($nowdf == "%d/%m/%Y") {
	$df = 'd/m/Y';
} elseif ($nowdf == "%m/%d/%Y") {
	$df = 'm/d/Y';
} else {
	$df = 'Y/m/d';
}

?>
<div class="viwppro-cnt">
	<div class="vikwp-alreadypro"><?php echo JText::translate('VRCPROALREADYHAVEPRO'); ?></div>
	<div class="vikwppro-header">
		<div class="vikwppro-header-inner">
			<div class="vikwppro-header-text">
				<h2><?php echo JText::translate('VRCPROINCREASEORDERS'); ?></h2>
				<h3><?php echo JText::translate('VRCPROCREATEOWNRENTSYS'); ?></h3>
				<h4><?php echo JText::translate('VRCPROMOSTTRUSTED'); ?></h4>
				<ul>
					<li><?php VikRentCarIcons::e('check'); ?> <?php echo JText::translate('VRCPROEASYANYONE'); ?></li>
					<li><?php VikRentCarIcons::e('check'); ?> <?php echo JText::translate('VRCPROFULLRESPONSIVE'); ?></li>
					<li><?php VikRentCarIcons::e('check'); ?> <?php echo JText::translate('VRCPROPOWERPRICING'); ?></li>
				</ul>
				<a href="https://vikwp.com/plugin/vikrentcar?utm_source=free_version&utm_medium=vrc&utm_campaign=gotopro" target="_blank" id="vikwpgotoget" class="vikwp-btn-link"><?php VikRentCarIcons::e('rocket'); ?> <?php echo JText::translate('VRCGOTOPROBTN'); ?></a>
			</div>
			<div class="vikwppro-header-img">
				<img src="<?php echo VRC_SITE_URI . 'resources/images/main.png' ?>" alt="Vik Rent Car Pro" />
			</div>
		</div>
	</div>
	<div class="viwppro-feats-cnt">
		<div class="viwppro-feats-row vikwppro-even viwppro-row-heightsmall">
			<div class="viwppro-feats-img">
				<img src="<?php echo VRC_SITE_URI . 'resources/images/fares_overview.gif' ?>" alt="Full Rates Management" />
			</div>
			<div class="viwppro-feats-text">
				<h4><?php echo JText::translate('VRCPROSEASONSONECLICK'); ?></h4>
				<p><?php echo JText::translate('VRCPROWHYRATESDESC'); ?></p>
			</div>
		</div>
		
		<div class="viwppro-feats-row vikwppro-odd">
			<div class="viwppro-feats-text">
				<h4><?php echo JText::translate('VRCPROCONFIGOPTIONS'); ?></h4>
				<p><?php echo JText::translate('VRCPROWHYOPTIONSDESC'); ?></p>
			</div>
			<div class="viwppro-feats-img">
				<img src="<?php echo VRC_SITE_URI . 'resources/images/options_extras.png' ?>" alt="Options and Extra Services" />
			</div>
		</div>

		<div class="viwppro-feats-row vikwppro-even">
			<div class="viwppro-feats-img">
				<img src="<?php echo VRC_SITE_URI . 'resources/images/order-editing.jpg' ?>" alt="Orders Management" />
			</div>
			<div class="viwppro-feats-text">
				<h4><?php echo JText::translate('VRCPROWHYBOOKINGS'); ?></h4>
				<p><?php echo JText::translate('VRCPROWHYBOOKINGSDESC'); ?></p>
			</div>
		</div>
		<div class="viwppro-feats-row vikwppro-odd viwppro-row-heightsmall">
			<div class="viwppro-feats-text">
				<h4><?php echo JText::translate('VRCPROOCCUPREPORT'); ?></h4>
				<p><?php echo JText::translate('VRCPROOCCUPREPORTDESC'); ?></p>
			</div>
			<div class="viwppro-feats-img">
				<img src="<?php echo VRC_SITE_URI . 'resources/images/occupancy-report.jpg' ?>" alt="Occupancy Ranking report to analyse every detail" />
			</div>
		</div>
	</div>
	<div class="viwppro-extra">
		<h3><?php echo JText::translate('VRCPROWHYUNLOCKF'); ?></h3>
		<div class="viwppro-extra-inner">
			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<?php VikRentCarIcons::e('users'); ?>
						<h4><?php echo JText::translate('VRCPROWHYCUSTOMERS'); ?></h4>
						<p><?php echo JText::translate('VRCPROWHYCUSTOMERSDESC'); ?></p>
					</div>
				</div>
			</div>
			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<?php VikRentCarIcons::e('credit-card'); ?>
						<h4><?php echo JText::translate('VRCPROWHYPAYMENTS'); ?></h4>
						<p><?php echo JText::translate('VRCPROWHYPAYMENTSDESC'); ?></p>
					</div>
				</div>
			</div>
			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<?php VikRentCarIcons::e('certificate'); ?>
						<h4><?php echo JText::translate('VRCPROPROMOCOUPONS'); ?></h4>
						<p><?php echo JText::translate('VRCPROPROMOCOUPONSDESC'); ?></p>
					</div>
				</div>
			</div>
			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<?php VikRentCarIcons::e('chart-line'); ?>
						<h4><?php echo JText::translate('VRCPROPMSREPORTS'); ?></h4>
						<p><?php echo JText::translate('VRCPROPMSREPORTSDESC'); ?></p>
					</div>
				</div>
			</div>
			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<?php VikRentCarIcons::e('file-text'); ?>
						<h4><?php echo JText::translate('VRCPROWHYINVOICES'); ?></h4>
						<p><?php echo JText::translate('VRCPROWHYINVOICESDESC'); ?></p>
					</div>
				</div>
			</div>
			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<?php VikRentCarIcons::e('calendar-check'); ?>
						<h4><?php echo JText::translate('VRCPROWHYCHECKIN'); ?></h4>
						<p><?php echo JText::translate('VRCPROWHYCHECKINDESC'); ?></p>
					</div>
				</div>
			</div>
			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<?php VikRentCarIcons::e('pie-chart'); ?>
						<h4><?php echo JText::translate('VRCPROWHYGRAPHS'); ?></h4>
						<p><?php echo JText::translate('VRCPROWHYGRAPHSDESC'); ?></p>
					</div>
				</div>
			</div>
			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<?php VikRentCarIcons::e('dollar-sign'); ?>
						<h4><?php echo JText::translate('VRCPROWHYLOCOOHFEES'); ?></h4>
						<p><?php echo JText::translate('VRCPROWHYLOCOOHFEESDESC'); ?></p>
					</div>
				</div>
			</div>
		</div>
		<div class="vikwp-extra-more"><?php echo JText::translate('VRCPROWHYMOREEXTRA'); ?></div>
		<a name="upgrade"></a>
	</div>
	<div class="vikwppro-licencecnt">
		<div class="col col-md-6 col-sm-12 vikwppro-licencetext">
			<div>
				<h3><?php echo JText::translate('VRCPROREADYINCREASE'); ?></h3>
			<?php
			if ($lic_date > 0) {
				$valid_until = date($df, $lic_date);
				?>
				<h4 class="vikwppro-lickey-expired"><?php echo JText::sprintf('VRCLICKEYEXPIREDON', $valid_until); ?></h4>
				<?php
			}
			?>
				<h4 class="vikwppro-licencecnt-get"><?php echo JText::translate('VRCPROREADYINCREASEDESC'); ?></h4>
				<a href="https://vikwp.com/plugin/vikrentcar?utm_source=free_version&utm_medium=vrc&utm_campaign=gotopro" target="_blank" class="vikwp-btn-link" target="_blank"><?php VikRentCarIcons::e('rocket'); ?> <?php echo JText::translate('VRCGOTOPROBTN'); ?></a>
			</div>
			<span class="icon-background"><?php VikRentCarIcons::e('rocket'); ?></span>
		</div>
		<div class="col col-md-6 col-sm-12 vikwppro-licenceform">
			<form>
				<div class="vikwppro-licenceform-inner">
					<h4><?php echo JText::translate('VRCPROALREADYHAVEKEY'); ?></h4>
					<span class="vikwppro-inputspan"><?php VikRentCarIcons::e('key'); ?><input type="text" name="key" id="lickey" value="" class="licence-input" autocomplete="off" /></span>
					<button type="button" class="btn vikwp-btn-green" id="vikwpvalidate" onclick="vikWpValidateLicenseKey();"><?php echo JText::translate('VRCPROVALNINST'); ?></button>
				</div>
			</form>
		</div>
	</div>
	<div class="vikwppro-block-reviews">
		<h3 class="vikwppro-block-review-title"><?php echo JText::translate('VRCPROWHATCLIENTSSAY'); ?>
			<span><?php echo JText::translate('VRCPROWHATCLIENTSSAYDESC'); ?></span>
		</h3>
		<div class="vikwppro-block-reviews-inner">
			<div class="vikwppro-block-review">
				<div class="vikwppro-block-review-inner">
					<div class="vikwppro-block-review-stars">
						<?php VikRentCarIcons::e('star'); ?>
						<?php VikRentCarIcons::e('star'); ?>
						<?php VikRentCarIcons::e('star'); ?>
						<?php VikRentCarIcons::e('star'); ?>
						<?php VikRentCarIcons::e('star'); ?>
					</div>
					<div class="vikwppro-block-review-text">
						<h3>Great Team and product</h3>
						<p>Started using this plugin, and have found it to be really well thought out. No other plugin in this category has the features that this plugin has.</p><p>It can be used by "real" companies, and is definitely worth the pro version. I have been impressed with the support team. <br />They respond promptly, and are there to help sort out any issues. <br />Well done to VikWp - I really would recommend anyone to use this plugin!</p>
					</div>
					<div class="vikwppro-block-review-author">
						<h4>— Anthony</h4>
						<span>Car Rental Agency</span>
					</div>
				</div>
			</div>
			<div class="vikwppro-block-review">
				<div class="vikwppro-block-review-inner">
					<div class="vikwppro-block-review-stars">
						<?php VikRentCarIcons::e('star'); ?>
						<?php VikRentCarIcons::e('star'); ?>
						<?php VikRentCarIcons::e('star'); ?>
						<?php VikRentCarIcons::e('star'); ?>
						<?php VikRentCarIcons::e('star'); ?>
					</div>
					<div class="vikwppro-block-review-text">
						<h3>Best Car Rental Software</h3>
						<p>I use it for several independant Car Rental Agency's under license of the biggest names in the Car Rental Industry.</p>
<p>It has many options, but you can use what you need. I think it is the best on the market for independent car rental agencies in Europe (and further away).<br />
It has the best support you could wish! Fast response! They listen to their users wishes for each future update.</p>
					</div>
					<div class="vikwppro-block-review-author">
						<h4>— Kriss</h4>
						<span>Web Agency for Avis Limburg</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
var vikwp_running = false;
function vikWpValidateLicenseKey() {
	if (vikwp_running) {
		// prevent double submission until request is over
		return;
	}
	// start running
	vikWpStartValidation();

	// request
	var lickey = document.getElementById('lickey').value;
	jQuery.ajax({
		type: "POST",
		url: "admin.php",
		data: { option: "com_vikrentcar", task: "license.validate", key: lickey }
	}).done(function(res) {
		if (res.indexOf('e4j.error') >= 0) {
			// stop the request
			vikWpStopValidation();
			alert(res.replace("e4j.error.", ""));
			return;
		}
		var obj_res = JSON.parse(res);
		document.location.href = 'admin.php?option=com_vikrentcar&view=getpro';
	}).fail(function() {
		// stop the request
		vikWpStopValidation();
		alert("Request Failed");
	});

}
function vikWpStartValidation() {
	vikwp_running = true;
	jQuery('#vikwpvalidate').prepend('<?php VikRentCarIcons::e('refresh', 'fa-spin'); ?>');
}
function vikWpStopValidation() {
	vikwp_running = false;
	jQuery('#vikwpvalidate').find('i').remove();
}
jQuery(document).ready(function() {
	jQuery('.vikwp-alreadypro a').click(function(e) {
		e.preventDefault();
		jQuery('html,body').animate({ scrollTop: (jQuery('.vikwppro-licencecnt').offset().top - 50) }, { duration: 'fast' });
	});
	jQuery('#lickey').keyup(function() {
		jQuery(this).val(jQuery(this).val().trim());
	});
	jQuery('#lickey').keypress(function(e) {
		if (e.which == 13) {
			// enter key code pressed, run the validation
			vikWpValidateLicenseKey();
			return false;
		}
	});
});
</script>
