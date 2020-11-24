
	<?php if(!isset($_GET['new_page'])) { ?>
	<div class="padding_20 boxn12">
		<div class="left">
			<div>Pages</div>
			<div class="pgtitle"><span class="bold"><?=$fetch_count['total_pages'];?></span> active pages</div>
		</div>
		<div class="pgbutton">
			<a href="index.php?page=pages&new_page=1" class="whitel12">
				<div class="rallr_sgr">+&nbsp;&nbsp;New page</div>
			</a>
		</div>
	</div>
	<?php } else { ?>
	<div class="padding_20 boxn12">
		<div class="left">
			<div><?=(isset($_GET['npid']) && is_numeric($_GET['npid']) ? 'Edit' : 'New');?> page</div>
		</div>
	</div>
	<?php } ?>

	<div class="padding_20">

		<?php if(!isset($_GET['new_page'])) { ?>

		<div class="users_dash_cap">
			<div class="users_dash_col pages_dash_col_1 bold">Page title</div>
			<div class="users_dash_col pages_dash_col_2 center bold">Visible in footer</div>
			<div class="users_dash_col pages_dash_col_3 center bold">Options</div>
		</div>

		<div class="no_results">
			<div class="no_results_icon"><i class="fas fa-exclamation-triangle"></i></div>
			<div class="no_results_text">No results</div>
		</div>

		<div class="pages_results" data-page="0" data-stop="0"></div>

		<div class="cloading"><i class="fas fa-spinner fa-spin"></i></div>

		<?php } else { ?>

		<?php if(isset($contest_error_msg) && $contest_error_msg) { ?><div class="error_box"><?=$contest_error_msg;?></div><?php } ?>

		<div class="settings_results">

			<form action="index.php?page=pages&new_page=1<?=(isset($_GET['npid']) && is_numeric($_GET['npid']) ? '&npid='.mysqli_real_escape_string($db,$_GET['npid']) : '');?>" method="post">

			<div class="d156">Page title</div>
			<div class="margin_top5">
				<input type="text" placeholder="Page title ..." class="large_inp156" value="<?=(isset($current_pages['title']) ? $current_pages['title'] : '');?>" name="pages_title" />
			</div>


			<div class="d157">Page content<br><span class="setting_explain">(HTML code is allowed)</span></div>
			<div class="margin_top5">
				<textarea rows="20" cols="100" placeholder="Page content goes here ..." class="full_textarea d157_text" name="pages_content"><?=(isset($current_pages['content']) ? $current_pages['content'] : '');?></textarea>
			</div>

			<div class="d158">

				<div class="left">
					<button type="submit" name="submit" class="submit_but">
						<i class="fas fa-check"></i>&nbsp;&nbsp;Publish
					</button>
				</div>
			
				<div class="d158_b">
					<label for="check_pages_footer">
						<input type="checkbox" id="check_pages_footer" name="pages_footer" <?=(isset($current_pages['footer']) && $current_pages['footer'] == '1' ? 'checked="checked"':'');?> />&nbsp;&nbsp;Show in footer
					</label>
				</div>

			</div>

			</form>

		</div>

		<?php } ?>

	</div>