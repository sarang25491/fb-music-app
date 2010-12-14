<?php

$date_begin = date("Y-m-01");
$date_end = strtotime("+1 month");
$date_end = date("Y-m-01", $date_end);

$donations_month = $db->Raw("SELECT *,SUM(`amount`) FROM `userdb_transactions` WHERE date(`time`) > '$date_begin' AND date(`time`) < '$date_end' AND `user` > 0");

$goal = 400;

$percentage = round(($donations_month[0]['SUM(`amount`)']/$goal)*100);
//$percentage = 100;

?>

<div style="border-bottom: 1px solid #d4dae8; padding: 3px; font-weight: bold;">
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="14px">
<tr>
   <?php if ($percentage >= 100) { ?>
      <td style="text-align: center; background-color: #d4dae8;">
         Goal reached for <?php echo date("M Y"); ?>! Thank you all for your support!
      </td>
   <?php } else { ?>
      <td width="<?php echo $percentage; ?>" style="text-align: right; background-color: #d4dae8; padding-right: 5px; border-right: 2px solid #000000">
         <?php if ($percentage > 80) { ?>
            <?php echo $percentage; ?>% of $<?php echo $goal; ?> goal reached for <?php echo date("M Y"); ?>.
         <?php } ?>
      </td>

      <td width="<?php echo 100-$percentage; ?>" style="padding-left: 5px;">
         <?php if ($percentage < 80) { ?>
            <?php echo $percentage; ?>% of $<?php echo $goal; ?> goal reached for <?php echo date("M Y"); ?>.
         <?php } ?>
      </td>
   <?php } ?>
</tr>
</table>
</div>

<div style="">
   <div style="float: right; padding: 2px 5px 2px 5px; text-align: center; font-weight: bold; border-left: 1px solid #d4dae8; border-bottom: 1px solid #d4dae8; background-color: #eceff6;">
      Keep this app alive while getting <u>slots & benefits</u> (25% off); <a href="<?php echo $config['fb']['fburl']; ?>app.paypal.php">please consider <u>donating</u></a>!
   </div>
</div>
