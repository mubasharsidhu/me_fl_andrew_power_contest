<?php

if ( isset($_POST['isAjax']) && $_POST['isAjax'] == 'hot_not_contest' && isset($_POST['photoId']) && $_POST['photoId'] != "") {

	$rating     = new Rating($_POST['eloScore'], $_POST['compareEloScore'], Rating::WIN, Rating::LOST);
	$results    = $rating->getNewRatings();

	$r1         = round( $results['a'] - $_POST['eloScore'], 2 );
	$r2         = round( $results['b'] - $_POST['compareEloScore'], 2);

	$sql_save1   = "INSERT INTO ratings (iduser, rate, ip, photo_id, shown_with_photo_id, rating_type)
									VALUES (" . $_POST['userId'] . ",'" . $r1 . "', '" . my_ip() . "', " . $_POST['photoId'] . ", " . $_POST['comparePhotoId'] . ", 'hot_not')";
	$sql_save2   = "INSERT INTO ratings (iduser, rate, ip, photo_id, shown_with_photo_id, rating_type)
									VALUES (" . $_POST['userId'] . ",'" . $r2 . "', '" . my_ip() . "', " . $_POST['comparePhotoId'] . ", " . $_POST['photoId'] . ", 'hot_not')";
	mysqli_query($db, $sql_save1);
	mysqli_query($db, $sql_save2);

	$r_tot_1                = round( $results['a'], 2 );
	$r_tot_2                = round( $results['b'], 2 );
	$sql_update             = "UPDATE content SET elo_score = '" . $r_tot_1 . "', elo_nr_ratings=elo_nr_ratings+1 WHERE id = '" . $_POST['photoId'] . "'";
	$sql_ComparePhotoUpdate = "UPDATE content SET elo_score = '" . $r_tot_2 . "', elo_nr_ratings=elo_nr_ratings+1 WHERE id = '" . $_POST['comparePhotoId'] . "'";
	mysqli_query($db, $sql_update);
	mysqli_query($db, $sql_ComparePhotoUpdate);

	$result 	= tnsb_display_contest( $db, $contest, $userId );
	echo tnsb_get_contest_html( $db, $result, $settings );
	exit;

}


function tnsb_display_contest($db, $contest, $iduser="") {

	$result 	= array();

	if ("" != $iduser) {
		$sql_get_ids 		= "SELECT c.id
											FROM content c
											WHERE c.contest = " . $contest . "
											ORDER BY c.id ASC";
		$query_get_ids 	= mysqli_query($db, $sql_get_ids);

		if ( !$query_get_ids || mysqli_num_rows($query_get_ids) == '0' || mysqli_num_rows($query_get_ids) == '1') {
			return $result;
		}
		else {

			$res_ids       = mysqli_fetch_all($query_get_ids);
			$arr_ids       = tnsb_convert_to_single_array($res_ids);
			$all_pairs     = get_unique_pairs_combinations($iduser, $arr_ids);

			$sql_get_rating_ids 	= "SELECT CONCAT(r.iduser, '_', r.photo_id, '_', r.shown_with_photo_id)
																FROM ratings r
																WHERE r.iduser = " . $iduser;
			$query_get_rating_ids = mysqli_query($db, $sql_get_rating_ids);
			if ( $query_get_rating_ids ) {
				$res_photos_ids = mysqli_fetch_all($query_get_rating_ids);
				$prev_pairs     = tnsb_convert_to_single_array($res_photos_ids);
				$remaining_pair = array_diff($all_pairs, $prev_pairs);
			}
			else {
				$remaining_pair = $all_pairs;
			}

			if ( empty( $remaining_pair ) ) {
				return $result;
			}

			$rand_pair_key = array_rand($remaining_pair, 1);
			$random_pair   = explode('_', $remaining_pair[ $rand_pair_key ]);
			unset($random_pair[0]); // remove iduser from value

			$sql_hot_not 	= "SELECT c.id, c.photo, c.type, c.elo_score, u.name, u.email, u.user, u.profile_picture
											FROM content c
											INNER JOIN users u ON u.id = c.iduser
											INNER JOIN contest cst ON cst.id = c.contest
											WHERE
												1 = 1
												AND c.id IN (" . implode(',', $random_pair) . ")
											ORDER BY RAND()";
			$result = mysqli_query($db, $sql_hot_not);
		}
	}
	else {
		$sql_hot_not 	= "SELECT c.id, c.photo, c.type, c.elo_score, u.name, u.email, u.user, u.profile_picture
											FROM content c
											INNER JOIN users u ON u.id = c.iduser
											INNER JOIN contest cst ON cst.id = c.contest
											WHERE
												1 = 1
												AND c.contest=" . $contest . "
											ORDER BY RAND()";
		$result = mysqli_query($db, $sql_hot_not);
	}

	return $result;

}


function tnsb_get_contest_html( $db, $hot_not_contest, $settings ) {

	$html 	= '';
	if ( !$hot_not_contest || mysqli_num_rows($hot_not_contest) == '0' || mysqli_num_rows($hot_not_contest) == '1') {
		$html	.= '<div class="contest_no_photos show">' . _LANG_CONTEST_NO_PHOTOS . '</div>';
	}
	else {

		$allRows 	= mysqli_fetch_all($hot_not_contest, MYSQLI_ASSOC);

		$sql_update_views     = "UPDATE content SET elo_views = elo_views+1 WHERE id IN ( '" . $allRows[0]['id'] . "', '" . $allRows[1]['id'] . "' ) ";
		mysqli_query($db, $sql_update_views);

		$html 	.= '
		<div class="elo-main-half" onclick="eloRating('.$allRows[0]['id'].','.$allRows[0]['elo_score'].','.$allRows[1]['id'].','.$allRows[1]['elo_score'].')">
			<div class="contest_thumb photo_tab_1 photo_order_1 overflow photo_tab_type_0">
				<a href="javascript:void(0);">
					' . tnsb_get_content( $settings['site_url'], $allRows[0] ) . '
				</a>
			</div>
			' . tnsb_get_user_info($allRows[0], $settings['site_url']) . '
		</div>
		<div class="elo-main-half" onclick="eloRating('.$allRows[1]['id'].','.$allRows[1]['elo_score'].','.$allRows[0]['id'].','.$allRows[0]['elo_score'].')">
			<div class="contest_thumb photo_tab_1  photo_order_1 overflow photo_tab_type_0">
				<a href="javascript:void(0);">
					' . tnsb_get_content( $settings['site_url'], $allRows[1] ) . '
				</a>
			</div>
			' . tnsb_get_user_info($allRows[1], $settings['site_url']) . '
		</div>';

	}

	return $html;

}


function tnsb_convert_to_single_array( $res_ids ) {
	$arr_ids 		= array();
	if ( !empty( $res_ids ) ) {
		foreach( $res_ids as $id ) {
			$arr_ids[] 	= $id[0];
		}
	}
	return $arr_ids;
}


function get_unique_pairs_combinations( $iduser, $arr_ids=array() ) {
	$result = array();
	if ( !empty( $arr_ids ) ) {
		$ids_count 	= count($arr_ids);
		foreach ($arr_ids as $k => $id) {
			for ( $i=$k; $i<$ids_count; $i++ ) {
				if ( $id == $arr_ids[ $i ] ) {
					continue;
				}
				$result[] 	= $iduser . '_' . $id . '_' . $arr_ids[ $i ];
			}
		}
	}
	return $result;
}


function tnsb_get_content( $site_url, $element ) {

	$html 	= '';

	if($element['type'] == '1') {
		$html 	= '
		<audio controls>
				<source src="' . $site_url . '_uploads/_music/' . $element['photo'] . '.mp3" type="audio/mpeg">
		</audio>';
	} elseif($element['type'] == '2') {
		$html 	= '
		<video width="100%" height="400" controls>
				<source src="' . $site_url . '_uploads/_videos/' . $element['photo'] . '.mp4" type="video/mp4">
			</video>';
	} elseif($element['type'] == '0') {
		$html 	= '<img src="' . $site_url . '_uploads/_photos/' . $element['photo'] . '.jpg" />';
	}

	return $html;

}


function tnsb_get_user_info($single_row, $site_url) {

	$htm    = '
	<div class="overflow s100 lr565 social-row">
			<div class="photo_user_box">
					<div class="photo_user_profile_pic">
							<a href=" ' . $site_url . $single_row['user'] . '" class="inherit_desc_none">';

									if($single_row['profile_picture']) {
											$htm    .= '<img src="' . $site_url . '_uploads/_profile_pictures/' . $single_row['profile_picture'] . '.jpg" />';
									}
									else {
											$htm    .= '<i class="fas fa-user-circle fa-3x"></i>';
									}

									$htm    .= '
							</a>
					</div>
					<div class="photo_user_infobox">
							<div class="photo_user_box_name">
									<a href="' . $site_url . $single_row['user'] . '" class="inherit_desc_none">' . $single_row['name'] . '</a>
							</div>
							<div class="photo_user_box_user">
									<a href="' . $site_url . $single_row['user'] . '" class="inherit_desc_none">@' . $single_row['user'] . '</a>
							</div>
					</div>

			</div>

			<div class="photo_page_share">

					<div class="photo_sharebox_button">
							<div class="fb-share-button" data-size="large" data-href="' . $site_url . 'photo-' . $single_row['photo']  . '" data-layout="button"></div>
					</div>

					<div class="photo_sharebox_button twitter">
							<a class="twitter-share-button" href="' . $site_url . 'photo-' . $single_row['photo'] . '" data-size="large">Tweet</a>
					</div>

			</div>

	</div>';

	return $htm;

}


/* function tnsb_get_sql_content_to_display( $db, $userId, $contest, $parsed_ids=array() ) {

	$sql_prev_rating    = "";
	if ( 1==2 && '' != $userId ) {
		$sql_prev_rating    	= "SELECT r.photo_id, r.shown_with_photo_id
															FROM ratings r
															INNER JOIN content c_sub ON c_sub.id = r.photo_id
															WHERE
																	1=1
																	AND r.iduser = '" . $userId . "'
																	AND r.rating_type = 'hot_not'
																	AND c_sub.contest = " . $contest;

		$res_sql_prev_rating = mysqli_query($db, $sql_prev_rating);

		if ( $res_sql_prev_rating ) {
			$rows_prev_rating 	= mysqli_fetch_all( $res_sql_prev_rating, MYSQLI_ASSOC );

			$sql_hot_not 	= "SELECT c.id
											FROM content c
											WHERE
													1 = 1
													AND c.contest = " . $contest . "
											ORDER BY RAND()
											limit 0,2";

			$res_hot_not 	= mysqli_query($db, $sql_hot_not);
			if ( $res_hot_not && mysqli_num_rows($res_hot_not) > 0 ) {

				$rows_hot_not 	= mysqli_fetch_all( $res_hot_not, MYSQLI_ASSOC );
				$cur_photo_ids 	= array(
					'photo_id'            => $rows_hot_not[0]['id'],
					'shown_with_photo_id' => $rows_hot_not[1]['id'],
				);

				$flag 	= tnsb_validate_cur_photos_with_prev( $cur_photo_ids, $rows_prev_rating );

				if ( $flag ) {
					// It means these pictures are already been shown together, for vote, to this user before
					$parsed_ids[] 	= $cur_photo_ids;
					// So Let's Try again
					return tnsb_get_sql_content_to_display( $db, $userId, $contest, $parsed_ids );
				}
				else {

				}
				echo '<pre>';
				print_r($cur_photo_ids);
				print_r($parsed_ids);
				print_r($rows_prev_rating);
				exit( '11111111' );

				exit('22222222');

			}

		}

	}

	$sql_hot_not = "SELECT c.photo, c.id, c.elo_score, u.name, u.email, u.user, u.profile_picture
									FROM content c
									INNER JOIN users u ON u.id = c.iduser
									INNER JOIN contest cst ON cst.id = c.contest
									WHERE
											1 = 1
											AND c.contest = " . $contest . "
									ORDER BY RAND()
									limit 0,2";


	return $sql_hot_not;
} */


/* function tnsb_validate_cur_photos_with_prev( $cur_photo_ids, $rows_prev_rating ) {

	foreach ( $rows_prev_rating as $single_rating ) {
		if (
			$cur_photo_ids['photo_id'] == $single_rating['photo_id'] &&
			$cur_photo_ids['shown_with_photo_id'] == $single_rating['shown_with_photo_id']
		) {
			return true;
		}
	}

	return false;

} */
