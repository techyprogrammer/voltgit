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

$rows = $this->rows;
$searchorder = $this->searchorder;
$islogged = $this->islogged;
$pagelinks = $this->pagelinks;

$nowdf = VikRentCar::getDateFormat();
$nowtf = VikRentCar::getTimeFormat();
if ($nowdf=="%d/%m/%Y") {
	$df='d/m/Y';
} elseif ($nowdf=="%m/%d/%Y") {
	$df='m/d/Y';
} else {
	$df='Y/m/d';
}

$pitemid = VikRequest::getString('Itemid', '', 'request');

if ($searchorder == 1) {
	?>
	<div class="vrcsearchconfnumb">
		<form action="<?php echo JRoute::rewrite('index.php?option=com_vikrentcar&view=userorders'.(!empty($pitemid) ? '&Itemid='.$pitemid : '')); ?>" method="post">
			<input type="hidden" name="option" value="com_vikrentcar"/>
			<input type="hidden" name="view" value="userorders"/>
			<input type="hidden" name="searchorder" value="1"/>
			<div class="vrcconfnumbinp">
				<label for="vrcconfnum"><?php echo JText::translate('VRCCONFNUMBERLBL'); ?></label>
				<input type="text" name="confirmnum" value="" size="25" id="vrcconfnum"/>
			</div>
			<div class="vrcconfnumbsubm">
				<input type="submit" name="searchconfnum" class="btn vrc-pref-color-btn" value="<?php echo JText::translate('VRCCONFNUMBERSEARCHBTN'); ?>"/>
			</div>
		</form>
	</div>
	<?php
}

if ($islogged == 1) {
	?>
	<h4><?php echo JText::translate('VRCYOURRESERVATIONS'); ?></h4>
	<?php
} else {
	?>
	<p><a href="<?php echo JRoute::rewrite('index.php?option=com_vikrentcar&view=loginregister'.(!empty($pitemid) ? '&Itemid='.$pitemid : '')); ?>"><?php echo JText::translate('VRCRESERVATIONSLOGIN'); ?></a></p>
	<?php
}

if (is_array($rows) && count($rows) > 0) {
	?>
	<table class="vrcuserorderstable">
		<tr class="vrcuserorderstablerow">
			<td><?php echo JText::translate('VRCUSERRESDATE'); ?></td>
			<td><?php echo JText::translate('VRPICKUP'); ?></td>
			<td><?php echo JText::translate('VRRETURN'); ?></td>
			<td><?php echo JText::translate('VRCUSERRESSTATUS'); ?></td>
		</tr>
	<?php
	foreach ($rows as $ord) {
		$status_lbl = '';
		if ($ord['status'] == 'confirmed') {
			$status_lbl = '<span class="badge badge-success">'.JText::translate('VRCONFIRMED').'</span>';
		} elseif ($ord['status'] == 'standby') {
			$status_lbl = '<span class="badge badge-warning">'.JText::translate('VRSTANDBY').'</span>';
		} elseif ($ord['status'] == 'cancelled') {
			$status_lbl = '<span class="badge badge-danger">'.JText::translate('VRCANCELLED').'</span>';
		}
		?>
		<tr>
			<td>
				<a href="<?php echo JRoute::rewrite('index.php?option=com_vikrentcar&view=order&sid='.$ord['sid'].'&ts='.$ord['ts'].(!empty($pitemid) ? '&Itemid='.$pitemid : '')); ?>"><?php echo date($df . ' ' . $nowtf, $ord['ts']); ?></a>
			</td>
			<td><?php echo date($df . ' ' . $nowtf, $ord['ritiro']); ?></td>
			<td><?php echo date($df . ' ' . $nowtf, $ord['consegna']); ?></td>
			<td><?php echo $status_lbl; ?></td>
		</tr>
		<?php
	}
	?>
	</table>
	<?php
} else {
	?>
	<p class="vrcuserordersparag"><?php echo JText::translate('VRCNOUSERRESFOUND'); ?></p>
	<?php
}
?>
<div class="vrc-pagination-footer"><?php echo $pagelinks; ?></div>
