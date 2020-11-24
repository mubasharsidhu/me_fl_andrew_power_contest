<?php
//global $db;
require_once '_core/_config.php';
require_once('_core/_functions.php');
require 'includes/elo-ratings/Rating.php';

$user_logged = get_user_logged();
$settings    = get_settings();
$contest     = isset($_POST['contest']) ? $_POST['contest'] : $_GET['contest'];
$userId      = isset($_POST['userId']) ? $_POST['userId'] : $user_logged['id'];

$inner_sub_query    = "";
if ( '' != $userId ) {
    $inner_sub_query    = "AND c.id NOT IN (
        SELECT
        r.photo_id
        FROM
        ratings r
        INNER JOIN
            content c_sub ON c_sub.id = r.photo_id
        WHERE
            1=1
            AND r.iduser = '" . $userId . "'
            AND r.rating_type = 'hot_not'
            AND c_sub.contest = " . $contest . "
    )";
}

$sql_hot_not = "SELECT
    c.photo, c.id, c.elo_score, u.name, u.email, u.user, u.profile_picture
FROM
    content c
        INNER JOIN
    users u ON u.id = c.iduser
        INNER JOIN
    contest cst ON cst.id = c.contest
WHERE
    1 = 1
    " . $inner_sub_query . "
    AND c.contest = " . $contest . "

ORDER BY RAND()
limit 0,2";

//ALTER TABLE `ratings` CHANGE `rate` `rate` FLOAT(3) NOT NULL DEFAULT '0.0';
if (isset($_POST['photoId']) && $_POST['photoId'] != "") {

    $rating     = new Rating($_POST['eloScore'], $_POST['compareEloScore'], Rating::WIN, Rating::LOST);
    $results    = $rating->getNewRatings();

    $r1         = round( $results['a'] - $_POST['eloScore'], 2 );
    $r2         = round( $results['b'] - $_POST['compareEloScore'], 2);

    $sql_save1   = "INSERT INTO ratings (iduser, rate, ip, photo_id, rating_type)
                    VALUES (" . $_POST['userId'] . ",'" . $r1 . "', '" . my_ip() . "', " . $_POST['photoId'] . ", 'hot_not')";
    $sql_save2   = "INSERT INTO ratings (iduser, rate, ip, photo_id, rating_type)
                    VALUES (" . $_POST['userId'] . ",'" . $r2 . "', '" . my_ip() . "', " . $_POST['comparePhotoId'] . ", 'hot_not')";
    mysqli_query($db, $sql_save1);
    mysqli_query($db, $sql_save2);

    $r_tot_1                = round( $results['a'], 2 );
    $r_tot_2                = round( $results['b'], 2 );
    $sql_update             = "UPDATE content SET elo_score = '" . $r_tot_1 . "', elo_nr_ratings=elo_nr_ratings+1 WHERE id = '" . $_POST['photoId'] . "'";
    $sql_ComparePhotoUpdate = "UPDATE content SET elo_score = '" . $r_tot_2 . "', elo_nr_ratings=elo_nr_ratings+1 WHERE id = '" . $_POST['comparePhotoId'] . "'";
    mysqli_query($db, $sql_update);
    mysqli_query($db, $sql_ComparePhotoUpdate);

    $sql_hot_not_update     = $sql_hot_not;

    $hot_not_contest_after_post = mysqli_query($db, $sql_hot_not_update);

    $htmlResponse   = '';
        if ( !$hot_not_contest_after_post || mysqli_num_rows($hot_not_contest_after_post) == '0' || mysqli_num_rows($hot_not_contest_after_post) == '1' ) {
            $htmlResponse .= '<div class="contest_no_photos show">' . _LANG_CONTEST_NO_PHOTOS . '</div>';
        } else {
            $allRowsUpdate = mysqli_fetch_all($hot_not_contest_after_post, MYSQLI_ASSOC);

            $sql_update_views     = "UPDATE content SET elo_views = elo_views+1 WHERE id IN ( '" . $allRowsUpdate[0]['id'] . "', '" . $allRowsUpdate[1]['id'] . "' ) ";
            mysqli_query($db, $sql_update_views);

            $htmlResponse .= '
                    <div class="contest_thumb">
                        <a href="javascript:void(0);" onclick="eloRating(' . $allRowsUpdate[0]['id'] . ',' . $allRowsUpdate[0]['elo_score'] . ',' . $allRowsUpdate[1]['id'] . ',' . $allRowsUpdate[1]['elo_score'] . ')">
                            <img src="' . $settings['site_url'] . '_uploads/_photos/' . $allRowsUpdate[0]['photo'] . '_400.jpg" />
                        </a>
                        ' . tnsb_get_user_info($allRowsUpdate[0], $settings['site_url']) . '
                    </div>
                    <div class="contest_thumb">
                        <a href="javascript:void(0);" onclick="eloRating(' . $allRowsUpdate[1]['id'] . ',' . $allRowsUpdate[1]['elo_score'] . ',' . $allRowsUpdate[0]['id'] . ',' . $allRowsUpdate[0]['elo_score'] . ')">
                            <img src="' . $settings['site_url'] . '_uploads/_photos/' . $allRowsUpdate[1]['photo'] . '_400.jpg" />
                        </a>
                        ' . tnsb_get_user_info($allRowsUpdate[1], $settings['site_url']) . '
                    </div>';
        }

    echo $htmlResponse;
    exit;

}

require_once 'hot_not_contest_data.php';


function tnsb_get_user_info($single_row, $site_url) {

    $htm    = '
    <div class="overflow s100 lr565">
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

?>


<script>
    //jQuery(document).ready(function(){
        tnsb_esecute_js();
    //});

    function tnsb_esecute_js() {
        (function(d, s, id) {
            console.warn(d);
            console.warn(s);
            console.warn(id);
    		var js, fjs = d.getElementsByTagName(s)[0];
    		if (d.getElementById(id)) return;
    		js = d.createElement(s); js.id = id;
    		js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
    		fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        window.twttr = (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0], t = window.twttr || {};
            if (d.getElementById(id)) return t;
            js = d.createElement(s);
            js.id = id;
            js.src = "https://platform.twitter.com/widgets.js";
            fjs.parentNode.insertBefore(js, fjs);
            t._e = [];
            t.ready = function(f) { t._e.push(f); };
            return t;
        }(document, "script", "twitter-wjs"));
    }


    function eloRating(photoId, eloScore, comparePhotoId, compareEloScore) {

        var user_id     = <?php echo isset($_SESSION['_logged_id']) ? $_SESSION['_logged_id'] : '0'; ?>;
        var contest_id  = <?php echo isset($_GET['contest']) ? $_GET['contest'] : '0'; ?>;

        if ( user_id == '0' ) {
            $('#login_pop .pop_content').css('height', '351.4px');
            $('#login_pop').show();
            return;
        }

        $('#elo-rating-div').hide( "fast" );
        $('.contest_ranking .loading_profile_photos').css('display', 'block');

        $.post('hot_not_contest.php', {
            contest        : contest_id,
            photoId        : photoId,
            userId         : user_id,
            eloScore       : eloScore,
            comparePhotoId : comparePhotoId,
            compareEloScore: compareEloScore,
        }, function (response) {
            $('#elo-rating-div').html(response);
            $('#elo-rating-div').show('fast');
            $('.contest_ranking .loading_profile_photos').css('display', 'none');
            FB.XFBML.parse();
            twttr.widgets.load();
        });
    }
</script>
