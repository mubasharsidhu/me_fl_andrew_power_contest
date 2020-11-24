	
	<div class="padding_20">

		<div class="commentss">
			<a href="index.php?page=comments">
				<div class="photos_menu <?=(!isset($_GET['approval']) ? 'photos_menu_selected':'');?>"><i class="fas fa-check"></i>&nbsp;&nbsp;Approved (<span id="count_photos_1"><?=$fetch_count_comments['total_comments'];?></span>)</div>
			</a>
			<a href="index.php?page=comments&approval=1">
				<div class="photos_menu <?=(isset($_GET['approval']) ? 'photos_menu_selected':'');?>"><i class="fas fa-eye-slash"></i>&nbsp;&nbsp;Pending (<span id="count_photos_2"><?=$fetch_count_notapproved_comments['total_notapproved'];?></span>)</div>
			</a>
		</div>

		<div class="clear_photos_x"></div>

		<div class="users_dash_cap">
			<div class="users_dash_col center comments_dash_col_1">Content</div>
			<div class="users_dash_col comments_dash_col_2 comments_dash_top">Comment</div>
			<div class="users_dash_col comments_dash_col_3">Date</div>
			<div class="users_dash_col center comments_dash_col_4">Options</div>
		</div>

		<div class="no_results">
			<div class="no_results_icon"><i class="fas fa-exclamation-triangle"></i></div>
			<div class="no_results_text">No results</div>
		</div>

		<div class="comments_results" data-page="0" data-stop="0"></div>

		<div class="cloading"><i class="fas fa-spinner fa-spin"></i></div>

	</div>