
	<meta charset="utf-8">

	<title><?=(isset($metatags['title']) ? $metatags['title'] : '');?></title>
	<meta name="description" content="<?=(isset($metatags['description']) ? $metatags['description'] : '');?>" />
	<meta name="keywords" content="<?=(isset($metatags['keywords']) ? $metatags['keywords'] : '');?>" />

	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, maximum-scale=1.0, shrink-to-fit=no">
	
	<?php
	if(current_page() == 'photo' && isset($fetch_photo['photo'])) {
		if(file_exists('_uploads/_photos/'.$fetch_photo['photo'].'.jpg')) {
			list($img_width, $img_height) = getimagesize('_uploads/_photos/'.$fetch_photo['photo'].'.jpg');
			echo '
			<meta property="og:image" content="'.$settings['site_url'].'_uploads/_photos/'.$fetch_photo['photo'].'.jpg" />
			<meta property="og:image:width" content="'.$img_width.'" /> 
			<meta property="og:image:height" content="'.$img_height.'" />
			<meta property="og:image:type" content="image/jpeg" />';
		}
	}
	?>
