
	<div class="main_home">

		<?php if($settings['category_required'] == '1' || (isset($settings['content_category']) && $settings['content_category'] == '1')) { ?>
		<div class="home_categories_scroll1">
			<div class="home_categories dragscroll">
				<?php $i=0; foreach(get_categories('home') as $k=>$v) { ?>
				<div class="home_category <?=($i==0 ? 'home_category_selected':'');?>" data-id="<?=$v['id'];?>"><?=$v['name'];?></div>
				<?php $i++; } ?>
			</div>
		</div>
		<?php } ?>
			
		<div class="home_no_photos"><?=_LANG_HOME_NO_PHOTOS;?></div>

		<div id="home_photos" data-page="0" data-stop="0" class="home_photos"></div>

		<div class="loading_home_photos">
			<i class="fas fa-spinner fa-spin"></i>
		</div>

	</div>

	<div class="clear_home"></div>