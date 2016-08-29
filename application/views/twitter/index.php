<!DOCTYPE html>
<html>
<head>
<!--
<meta http-equiv="refresh" content="10; URL=index.php">
-->
<title>TWITTER</title>
<script type="text/javascript">
	window.onload = function() {
		console.log(window.location);
		var screen_name = document.getElementById('screen_name');
		screen_name.addEventListener('change', function() {
			var url = window.location.pathname;
			url += '?screen_name='+ this.value;

			window.location = url;
		});
	}
</script>
<style>

    table,th,td{

        border:1px solid #000;

        font-size:20px;

    }

</style>


</head>
<body>
<table>
  <tr>
		<th>Id_Tweet</th>
		<th>Portal Berita</th>
		<th>Tanggal</th>
		<th>Tweet</th>
		<th>URL</th>
		<th>Link</th>
		<th>Title</th>
		<th>Content</th>
		<th>tambahan</th>
		
 </tr>
<?php
	echo form_dropdown('screen_name', $list_username, $screen_name, 'id="screen_name"').'<hr />';


	if ($status == true) {
		echo '<h3>@'.$screen_name.'</h3><hr />';
		$no=1;
		foreach ($data_twitter as $row) {
			$user = $row['portal_berita'];
			$tweet = $row['tweet_berita'];
			$date = $row['date_tweet'];
			
			$link = $row['url_berita'];
			
			$source = $row['source'];
			$source = $row->source;
	?>

	
   <tr>
		
			<td><?php echo $no;?></td>
			<td><?php echo $user;?></td>
			<td><?php echo $date;?></td>
			<td><?php echo $tweet;?></td>
			<td><?php echo $link;?></td>
			<td><?php echo $source;?></td>
			<td><?php echo $title;?></td>
			<td><?php echo $content;?></td>
			<td><?php echo $tes;?></td>
			
		</tr>
	
	<?php
	 $no++;

		}
	} else {
		echo '</strong>Silahkan pilih akun</strong><br><br>';
	}
	
	
		
?>
</table>		
</body>
</html>

