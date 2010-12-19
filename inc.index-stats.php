<div style="height: 16px;">
   <div style="float: left; text-align: center; background-color: #eceff6; margin: 0 0 0px 0; padding: 3px 5px 3px 5px; font-weight: bold; border-left: 1px solid #d4dae8; border-right: 1px solid #d4dae8; border-top: 1px solid #d4dae8;">
   Stats
   </div>
</div>

<div style="border: 1px solid #cccccc; margin: 4px 0 5px 0;">
<table border="0" width="100%">
   <tr>
      <td valign="top" style="padding: 5px;">
         <?php $fullplays = $db->getStats($user, 'fullplay'); ?>
         <?php $clicks = $db->getStats($user, 'start'); ?>         
         In your lifetime, your songs were played <b><?php echo $clicks; ?></b> times, but were only fully listened to <b><?php if ($fullplays > $clicks) echo $clicks; else echo $fullplays; ?></b> times.
         <br /><br />
         <?php $todayPlays = $db->getStats($user, 'fullplay', '-1 day'); ?>
         <?php $todayClicks = $db->getstats($user, 'start', '-1 day'); ?>
         <b>Today</b>: <?php echo $todayClicks; ?> Clicks, <?php echo $todayPlays; ?> Plays<br/ >
         <?php $pastWeekPlays = $db->getStats($user, 'fullplay', '-1 week'); ?>
         <?php $pastWeekClicks = $db->getstats($user, 'start', '-1 week'); ?>
         <b>This Week</b>: <?php echo $pastWeekClicks; ?> Clicks, <?php echo $pastWeekPlays; ?> Plays<br/ >
         <?php $pastMonthPlays = $db->getStats($user, 'fullplay', '-1 month'); ?>
         <?php $pastMonthClicks = $db->getstats($user, 'start', '-1 month'); ?>
         <b>This Month</b>: <?php echo $pastMonthClicks; ?> Clicks, <?php echo $pastMonthPlays; ?> Plays
      <td>  
   </tr>
</table>
</div>
