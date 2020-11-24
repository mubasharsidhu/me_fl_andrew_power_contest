
	<?php if($multi_contest == '2') { ?>

	<div class="contest_header">

		<div class="contest_header_sub">

			<div class="contest_header_box">
				<div class="contest_header_icon"><i class="fas fa-crown"></i></div>
				<div class="c cs2"><?=_LANG_ACTIVE_CONTESTS;?></div>
			</div>

		</div>

	</div>

	<div class="contest_ranking">

		<div class="contest_rankings_box">

			<?php
			$sql = mysqli_query($db,"SELECT * FROM `contest` ORDER BY `active`, `id` DESC");
			while($fetch = mysqli_fetch_array($sql)) {

				$total_join_sql = mysqli_query($db,"SELECT count(*) as 'total' FROM `content` WHERE `contest` = '".$fetch['id']."'");
				$fetch_joinsql = mysqli_fetch_array($total_join_sql);

				echo '
				<a href="index.php?contest='.$fetch['id'].'" class="contests_item_a">
					<div class="contests_item">
						<div class="contests_item_title">'.$fetch['title'].'</div>
						<div class="contests_item_timer new_timer" data-time="'.str_replace(' ','T',$fetch['end']).'" data-id="'.$fetch['id'].'">
							<div class="contests_timer_1">-</div>
							<div class="contests_timer_2">-</div>
							<div class="contests_timer_3">-</div>
							<div class="contests_timer_4">-</div>
						</div>
						<div class="contests_item_timer_tile">
							<div class="contests_item_timer_tile_1">'._LANG_DAYS.'</div>
							<div class="contests_item_timer_tile_1">'._LANG_HOURS.'</div>
							<div class="contests_item_timer_tile_1">'._LANG_MINUTES.'</div>
							<div class="contests_item_timer_tile_1">'._LANG_SECONDS.'</div>
						</div>
						<div class="contests_item_joined">
							<span class="bold">'.str_replace('%%total%%',$fetch_joinsql['total'],_LANG_TOTAL_JOINED).'</span>
						</div>
					</div>
				</a>';

			}
			?>

		</div>

	</div>

	<?php } ?>