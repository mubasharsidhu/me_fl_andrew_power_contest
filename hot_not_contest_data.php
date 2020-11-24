 <div class="contest_ranking hot-not-contest" style="text-align:center; min-height:257px;">

    <div class="contest_rankings_box">

				<div class="c cs3 lr565 mphotos_title all_black">Elo Contest <p><sub>(Click to Vote)</sub></p></div>

				<div class="loading_profile_photos" style="display:none">
					<i class="fas fa-spinner fa-spin"></i>
				</div>

        <div id="elo-rating-div" class="contest_ranking_list">

					<?php
					$hot_not_contest = mysqli_query($db, $sql_hot_not);
					if ( !$hot_not_contest || mysqli_num_rows($hot_not_contest) == '0' || mysqli_num_rows($hot_not_contest) == '1') {
							echo '<div class="contest_no_photos show">' . _LANG_CONTEST_NO_PHOTOS . '</div>';
					}
					else {
						$allRows         = mysqli_fetch_all($hot_not_contest, MYSQLI_ASSOC);

						$sql_update_views     = "UPDATE content SET elo_views = elo_views+1 WHERE id IN ( '" . $allRows[0]['id'] . "', '" . $allRows[1]['id'] . "' ) ";
						mysqli_query($db, $sql_update_views);

						echo '
						<div class="contest_thumb">
							<a href="javascript:void(0);" onclick="eloRating('.$allRows[0]['id'].','.$allRows[0]['elo_score'].','.$allRows[1]['id'].','.$allRows[1]['elo_score'].')">
								<img src="' . $settings['site_url'] . '_uploads/_photos/'.$allRows[0]['photo'].'_400.jpg" />
							</a>
							' . tnsb_get_user_info($allRows[0], $settings['site_url']) . '
						</div>
						<div class="contest_thumb">
							<a href="javascript:void(0);" onclick="eloRating('.$allRows[1]['id'].','.$allRows[1]['elo_score'].','.$allRows[0]['id'].','.$allRows[0]['elo_score'].')">
								<img src="' . $settings['site_url'] . '_uploads/_photos/'.$allRows[1]['photo'].'_400.jpg" />
							</a>
							' . tnsb_get_user_info($allRows[1], $settings['site_url']) . '
						</div>';
					}
					?>

        </div>
    </div>


</div>
