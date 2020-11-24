<?php 
	header("Content-type: text/xml");

	require_once('_core/_functions.php');

	$settings = get_settings();

	$sitemap = '';
 	$output = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

	$sql_photos = mysqli_query($db,"SELECT * FROM `photos`");
	while($fetch_photo = mysqli_fetch_array($sql_photos)) {

		$sitemap.= '
		<url>
    			<loc>'.$settings['site_url'].'photo-'.$fetch_photo['id'].'</loc>
    			<lastmod>'.explode(' ',$fetch_photo['date'])[0].'</lastmod>
  		</url>';

	}
	
	echo $output;
?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"> 
  	<url>
    		<loc><?=$settings['site_url'];?></loc>
    		<lastmod><?=date('Y-m-d');?></lastmod>
  	</url>
	<?=$sitemap;?>
</urlset>