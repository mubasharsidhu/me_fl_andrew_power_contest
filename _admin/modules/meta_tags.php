
	<div class="padding_20 boxn12">
		<div>Meta tags</div>
		<div class="pgtitle">Control your site <span class="bold">meta tags</span>: title, description, keywords for search engine visibility</div>

		<div class="clear overflow margin_top15">
			<?php foreach($meta_pages as $k=>$v) { ?>
			<div class="rallr_but<?=($v == '1' ? ' rallr_but_sel':'');?>" data-id="<?=$k;?>"><?=ucwords($k);?></div>
			<?php } ?>
		</div>

	</div>

	<div class="padding_20">

		<?php if(isset($success_msg) && $success_msg) { ?>
		<div class="success_box_new show">
			<div class="scb_icon"><i class="fas fa-check"></i></div>
			<div class="scb_text"><?=$success_msg;?></div>
		</div>
		<?php } ?>

		<div class="settings_results">

			<form action="index.php?page=meta_tags" method="post">

			<?php foreach($meta_pages as $k=>$v) { ?>

			<div class="box_selection box_selection_<?=$k;?> <?=($v == '0' ? 'hide':'');?>">

				<?php if($k == 'profile') { ?>
				<div class="meta_explain">You can use <strong>%name%</strong> or <strong>%user%</strong> or <strong>%slogan%</strong></div>
				<?php } ?>

				<?php if($k == 'post') { ?>
				<div class="meta_explain">You can use <strong>%name%</strong> or <strong>%rank%</strong> or <strong>%rating%</strong> or <strong>%votes%</strong></div>
				<?php } ?>

				<div class="clear_space"></div>
				<div class="slash"></div>

				<div class="setting">
					<div class="bgs_left">Title</div>
					<div class="bgs_right">
						<input type="text" placeholder="PHP Contest Platform" value="<?=(isset($site_meta[$k]['title']) ? $site_meta[$k]['title'] : 'PHP Contest Platform');?>" maxlength="100" name="<?=$k;?>_title" />
					</div>
				</div>

				<div class="setting">
					<div class="bgs_left">Description</div>
					<div class="bgs_right">
						<input type="text" placeholder="PHP Contest Platform" value="<?=(isset($site_meta[$k]['description']) ? $site_meta[$k]['description'] : 'PHP Contest Platform');?>" maxlength="100" name="<?=$k;?>_description" />
					</div>
				</div>

				<div class="setting">
					<div class="bgs_left">Keywords</div>
					<div class="bgs_right">
						<input type="text" placeholder="PHP Contest Platform" value="<?=(isset($site_meta[$k]['keywords']) ? $site_meta[$k]['keywords'] : 'PHP Contest Platform');?>" maxlength="100" name="<?=$k;?>_keywords" />
					</div>
				</div>

			</div>

			<?php } ?>

			<div class="setting">
				<div class="bgs_left">&nbsp;</div>
				<div class="bgs_right">
					<button type="submit" name="submit" class="submit_but">
						<i class="fas fa-check"></i>&nbsp;&nbsp;Save changes
					</button>
				</div>
			</div>

			</form>

		</div>

	</div>