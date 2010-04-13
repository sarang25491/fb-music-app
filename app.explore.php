<link type="text/css" rel="stylesheet" href="<?php echo $config['fb']['appcallbackurl']; ?>css/pagination.css" />
<?php
success('<fb:intl>Exploring New Music and Artists</fb:intl>','<fb:intl>This page contains all the artists that are using this application, search and enjoy! If you want to be listed here, simply create a Facebook page, add this application and upload your music.</fb:intl>');

$number_of_pages = $db->Raw("SELECT `data` FROM `system` WHERE `var`='number_of_pages'"); // grab number of pages

if ($page == NULL) $page = rand(1,$number_of_pages[0]['data']);
?>

<?php
$page_previous = $page-1;
$page_next = $page+1;
?>

<div align="right" style="padding: 15px;">
	<div style="float: left;">
		<?php echo 'page: <b>' . $page . '</b> out of <b>' . $number_of_pages[0]['data'] . '</b>'; ?>
	</div>

	<div style="float: right;">
		<?php
		if($page !== '1')
			echo '<a href="' . $config['fb']['fburl'] . 'index.php?tab=explore_music&page=' . $page_previous . '" class="pagination"><fb:intl>Previous</fb:intl></a>';

		if($page !== $number_of_pages[0]['data'])
			echo '<a href="' . $config['fb']['fburl'] . 'index.php?tab=explore_music&page=' . $page_next . '" class="pagination"><fb:intl>Next</fb:intl></a>';
		?>
	</div>
</div>

<?php
include_once('./statics/explore/' . $page . '.txt');
?>
