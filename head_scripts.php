
	<link rel="stylesheet" media="all" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
	<link rel="stylesheet" media="all" href="_css/style.css?v=<?=rand(1,99999999);?>" />

	<?php if(isset($settings['site_theme']) && $settings['site_theme']) { ?>
	<link rel="stylesheet" media="all" href="_css/<?=$settings['site_theme'];?>.css?v=<?=rand(1,999999);?>" />
	<?php } ?>
