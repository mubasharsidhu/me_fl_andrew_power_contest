(function ($) {

    "use strict";

    var is_typing = 0;

    function fix_footer() {

        $('.main').css('padding-bottom', (parseInt($('.footer').height()) + 1) + 'px');

    }

    fix_footer();

    $(document).ready(function () {

        if (this_page == 'photo' && mobile == '1') {

            var order_2 = $('.photo_order_2').html();
            $('.photo_order_2').remove();

            $('.photo_page_info').prepend(order_2);

        }

    });

    $(window).resize(function () {
        fix_footer();
    });

    $(document).on('keypress', '.add_comment_input', function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            $('.add_comment').click();
        }
    });

    $(document).on('click', '.open_my_photos', function () {

        $('.pop_my_photos').show();
        load_my_photos();

    });

    $(document).on('click', '.click_join_contest', function () {

        $('.pop_my_photos').hide();

        var myphotos = [];
        $('.clmp_check').each(function () {
            myphotos[myphotos.length] = $(this).data('id');
        });

        $.post('_core/request.php', {
            reason: 'join_contest',
            contest_id: contest_id,
            photos: JSON.stringify(myphotos)
        }, function (get) {
            window.location.reload();
        });

        $('.load_my_photos').stop().html('');

    });

    $(document).on('click', '.cancel_contest_join', function () {

        $('.pop_my_photos').hide();
        $('.load_my_photos').stop().html('');

    });

    $(document).on('click', '.clmp', function () {

        if ($(this).hasClass('clmp_check')) {
            $(this).removeClass('clmp_check');
        } else {
            $(this).addClass('clmp_check');
        }

    });

    $(document).on('click', '.remove_contest_photo', function () {

        if (confirm(lang_remove_contest_photo)) {

            var photo_id = $(this).data('id');

            $.post('_core/request.php', {reason: 'remove_contest_photo', photo_id: photo_id}, function (get) {
                window.location.reload();
            }, 'json');

        }

        return false;

    });

    function load_my_photos() {

        $.post('_core/request.php', {reason: 'load_my_photos'}, function (get) {

            if (get['my_photos'].length) {

                var i = 0;
                for (i = 0; i <= get['my_photos'].length - 1; i++) {

                    var clmp_class = '';
                    var clmp_filed = '<div class="clmp_filed"><i class="fas fa-check"></i></div>';

                    if (i == '0') {
                        clmp_class = 'clmp_check';
                    }

                    var photo = '' +
                        '<div class="clmp ' + clmp_class + '" data-id="' + get['my_photos'][i].id + '">' +
                        clmp_filed +
                        '<img src="' + site_url + '_uploads/_photos/' + get['my_photos'][i].photo + '_400.jpg" style="width:100%;" />' +
                        '</div>';

                    $('.load_my_photos').append(photo);

                }

            } else {
                $('.load_my_photos').stop().html('<div style="padding:10px;font-size:14px;font-weight:600;">' + lang_no_photos_uploaded + '</div>');
            }

        }, 'json');

    }

    $(document).on('click', '.add_comment', function () {

        if (logged_id == '0') {
            $('.open_pop[data-id="login"]').click();
            return false;
        }

        var comment = $('.add_comment_input').val();

        if (comment.length) {

            $.post('_core/request.php', {reason: 'add_comment', comment: comment, photo_id, photo_id}, function (get) {

                $('.add_comment_input').stop().val('');

                if (get.error == '0') {
                    if (comments_review == '1') {
                        $('.photo_comment_pending').stop().show();
                    } else {
                        $('.photo_comments_loading').stop().show();
                        $('.photo_comments').stop().html('');
                        get_comments(photo_id);
                    }
                } else {
                    if (get.error == '1') {
                        // same
                    }
                    if (get.error == '2') {
                        // approval error
                    }
                }

            }, 'json');

        } else {
            //
        }

    });

    function get_comments(photo_id) {

        $.post('_core/request.php', {reason: 'comments', photo_id: photo_id}, function (get) {

            $('.photo_comments_loading').stop().hide();
            $('.total_comments').stop().text('(' + get['comments'].length + ')');

            if (get['comments'].length) {

                var i = 0;
                for (i = 0; i <= get['comments'].length - 1; i++) {

                    var pic = '<div style="border-radius:30px;width:30px;height:30px;background:#ccc;"></div>';
                    if (get['comments'][i].picture) {
                        pic = '<img src="' + site_url + '_uploads/_profile_pictures/' + get['comments'][i].picture + '.jpg" style="width:30px;height:30px;border-radius:30px;" />';
                    }

                    var comment = '' +
                        '<div class="photo_comment ' + (i == 0 ? 'no_border' : '') + '">' +
                        '<div class="photo_comment_left">' +
                        '<a href="' + site_url + get['comments'][i].user + '">' + pic + '</a>' +
                        '</div>' +
                        '<div class="photo_comment_right">' +
                        '<div class="photo_comment_name"><a href="' + site_url + get['comments'][i].user + '">' + get['comments'][i].name + '</a></div>' +
                        '<div class="photo_comment_text">' + get['comments'][i].comment + '</div>' +
                        '<div class="photo_comment_date">' + get['comments'][i].date + '</div>' +
                        '</div>' +
                        '</div>';

                    $('.photo_comments').append(comment);

                }

            } else {
                $('.photo_comments').stop().html('<div class="photo_no_comments">' + lang_no_comments + '</div>');
            }

        }, 'json');

    }

    if (this_page == 'photo' && photo_comments == '1') {

        get_comments(photo_id);

    }

    function run_search() {

        $('.header_search_suggestions').stop().show().html('<div style="padding:20px;text-align:center;color:#222;font-size:18px;"><i class="fas fa-spinner fa-spin"></i></div>');

        var term = $('.header_search_input').val();

        $.post('_core/request.php', {reason: 'search', term: term}, function (get) {

            $('.header_search_suggestions').stop().html('');

            if (get['list'].length) {

                var i = 0;
                for (i = 0; i <= get['list'].length - 1; i++) {

                    if (get['list'][i].picture.length) {
                        var pic = '<img src="' + site_url + '_uploads/_profile_pictures/' + get['list'][i].picture + '.jpg" style="width:35px;height:35px;border-radius:35px;display:block;" />';
                    } else {
                        var pic = '<div style="width:35px;height:35px;border-radius:35px;background:#ccc;"></div>';
                    }

                    var suggestion = '' +
                        '<a href="' + get['list'][i].user + '">' +
                        '<div class="search_box_suggestion ' + (i == '0' ? 'no_border' : '') + '">' +
                        '<div class="search_box_suggestion_left">' +
                        pic +
                        '</div>' +
                        '<div class="search_box_suggestion_right">' +
                        '<div class="search_box_suggestion_name">' + get['list'][i].name + '</div>' +
                        '<div class="search_box_suggestion_user">@' + get['list'][i].user + '</div>' +
                        '</div>' +
                        '</div>' +
                        '</a>';

                    $('.header_search_suggestions').append(suggestion);

                }

            } else {
                $('.header_search_suggestions').stop().html('<div style="padding:20px;text-align:center;color:#565858;">No results</div>');
            }

        }, 'json');

    }

    $(document).on('keyup', '.header_search_input', function () {

        var term = $('.header_search_input').val();

        setTimeout(function () {
            if (term == $('.header_search_input').val()) {

                if ($('.header_search_input').val().length > 1) {
                    run_search();
                } else {
                    $('.header_search_suggestions').stop().html('').hide();
                }

            }
        }, 300);

    });

    if (preloader_show == '1') {

        var preload_nr = 0;
        var preload = setInterval(function () {

            ++preload_nr;

            var full_star = '' +
                '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" class="star1" viewBox="0 0 20 20" data-inline="false" style="transform: rotate(360deg);font-size:40px;">' +
                '<path d="M10 1l3 6l6 .75l-4.12 4.62L16 19l-6-3l-6 3l1.13-6.63L1 7.75L7 7z" fill="currentColor"></path>' +
                '</svg>';

            if (preload_nr < 6) {
                $('.star_pr_' + preload_nr).stop().html(full_star);
            } else {
                clearInterval(preload);
                $('.preload').stop().remove();
                $('body').stop().css('overflow', 'auto');
            }

        }, 350);

    }

    if (this_page == 'contests' && $('.new_timer')[0]) {

        $('.new_timer').each(function () {

            var time_s = $(this).data('time');
            var id = $(this).data('id');
            var countDownDate = new Date(time_s).getTime();

            setInterval(function (id) {

                var now = new Date().getTime();

                var distance = countDownDate - now;

                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                if (parseInt(days) < 0 || parseInt(hours) < 0 || parseInt(minutes) < 0 || parseInt(seconds) < 0 || isNaN(days)) {
                    $('.new_timer[data-id="' + id + '"] .contests_timer_1').stop().text('0');
                    $('.new_timer[data-id="' + id + '"] .contests_timer_2').stop().text('0');
                    $('.new_timer[data-id="' + id + '"] .contests_timer_3').stop().text('0');
                    $('.new_timer[data-id="' + id + '"] .contests_timer_4').stop().text('0');
                } else {
                    $('.new_timer[data-id="' + id + '"] .contests_timer_1').stop().text(days);
                    $('.new_timer[data-id="' + id + '"] .contests_timer_2').stop().text(hours);
                    $('.new_timer[data-id="' + id + '"] .contests_timer_3').stop().text(minutes);
                    $('.new_timer[data-id="' + id + '"] .contests_timer_4').stop().text(seconds);
                }

            }, 1000, id);

        });

    }

    if (this_page == 'contest' && active_contest == '1') {

        var countDownDate = new Date(contest_end).getTime();

        var x = setInterval(function () {

            var now = new Date().getTime();

            var distance = countDownDate - now;

            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            if (parseInt(days) < 0 || parseInt(hours) < 0 || parseInt(minutes) < 0 || parseInt(seconds) < 0 || isNaN(days)) {
                $('.ct_1, .ct_2, .ct_3, .ct_4').stop().text('0');
            } else {
                $('.ct_1').stop().text(days);
                $('.ct_2').stop().text(hours);
                $('.ct_3').stop().text(minutes);
                $('.ct_4').stop().text(seconds);
            }

            if (distance < 0) {
                clearInterval(x);
            }

        }, 1000);

    }

    $(document).on('click', '.ranking_filter_op', function () {

        if (!$(this).hasClass('ranking_filter_op_selected')) {

            var type = $(this).data('type');
            $('.ranking_filter_op_selected[data-type="' + type + '"]').stop().removeClass('ranking_filter_op_selected');
            $(this).stop().addClass('ranking_filter_op_selected');

            $('.ranking_items').stop().data('page', '0');

            load_rankings(0);

        }

        if ($(window).width() < 800) {
            $('.ranking_filters').toggle();
        }

    });

    function load_rankings(page) {

        $('.ranking_no_photos').stop().hide();

        var order = $('.ranking_filter_op_selected[data-type="order"]').data('id');
        var category = $('.ranking_filter_op_selected[data-type="category"]').data('id');

        var contest = '';
        if ($('.ranking_filter_op_selected[data-type="contest"]')[0]) {
            contest = $('.ranking_filter_op_selected[data-type="contest"]').data('id');
        }

        var rating_type = '';
        if ($('.ranking_filter_op_selected[data-type="rating_type"]')[0]) {
            rating_type = $('.ranking_filter_op_selected[data-type="rating_type"]').data('id');
        }


        if (page == '0') {
            $('.ranking_items').stop().html('');
        }

        get_rankings(order, category, contest, rating_type);

    }

    function get_rankings(order, category, contest, rating_type) {

        $('.ranking_loading').stop().show();

        var page_nr = $('.ranking_items').data('page');
        $('.ranking_items').stop().data('page', parseInt(page_nr) + 1);

        $.post('_core/request.php', {
            reason: 'rankings',
            contest: contest,
            page_nr: page_nr,
            order: order,
            category: category,
            rating_type: rating_type
        }, function (get) {

            $('.ranking_loading').stop().hide();

            is_loading = 0;

            if (get.error == 1) {
                $('.ranking_items').stop().data('stop', 1);
                if (!$('.ranking_item')[0]) {
                    $('.ranking_no_photos').stop().show();
                }
            } else {

                var i = 0;
                for (i = 0; i <= get['list'].length - 1; i++) {

                    if (get['list'][i].type == '0') {
                        var thumb_picture = site_url + '_uploads/_photos/' + get['list'][i].photo + '_400.jpg';
                    }

                    if (get['list'][i].type == '1') {
                        if (get['list'][i].cover.length > 5) {
                            var thumb_picture = site_url + '_uploads/_content_cover/' + get['list'][i].cover + '_400.jpg';
                        } else {
                            var thumb_picture = site_url + '_img/no_thumb_music.jpg';
                        }
                    }

                    if (get['list'][i].type == '2') {
                        if (get['list'][i].cover.length > 5) {
                            var thumb_picture = site_url + '_uploads/_content_cover/' + get['list'][i].cover + '_400.jpg';
                        } else {
                            var thumb_picture = site_url + '_img/no_thumb_video.jpg';
                        }
                    }

                    var ranking_item = '' +
                        '<div class="ranking_item ' + (large_ranking == '1' ? 'ranking_item_large' : '') + '">' +
                        '<div class="ranking_item_rank">' +
                        get['list'][i].rank + '.' +
                        '</div>' +
                        '<div class="ranking_item_photo">' +
                        '<a href="' + site_url + 'photo-' + get['list'][i].id + '">' +
                        '<img src="' + thumb_picture + '" />' +
                        '</a>' +
                        '</div>' +
                        '<div class="ranking_item_name">' +
                        '<a href="' + site_url + 'photo-' + get['list'][i].id + '" class="text_dec_none">' +
                        '<span>' + get['list'][i].name + '</span><br>' +
                        '<span style="font-size:12px;color:#777;font-weight:400;">@' + get['list'][i].user + '</span>' +
                        '</a>' +
                        '</div>' +
                        '<div class="ranking_item_rate">' + get['list'][i].rating_score + '</div>' +
                        '<div class="ranking_item_votes">' +
                        '<span style="color:#222;font-weight:700;">' + get['list'][i].nr_ratings + '</span> ' + lang_ranking_list_ratings +
                        '</div>' +

                        '<div class="ranking_item_views">' +
                        '<span style="color:#222;font-weight:700;">' + get['list'][i].views + '</span> ' + lang_ranking_list_views +
                        '</div>' +
                        '</div>';

                    $('.ranking_items').append(ranking_item);

                }

            }

            fix_footer();

        }, 'json');

    }

    $(document).on('click', '.remove_profile_picture', function () {

        if (confirm(lang_remove_profile_picture)) {

            $.post('_core/request.php', {reason: 'remove_profile_picture'});
            $('.remove_profile_picture').stop().hide();
            $('.profile_picture_box').stop().remove();

            fix_footer();

        }

    });

    $('.edit_profile_slogan').on('keydown paste', function (event) {

        if (event.keyCode == 13) {
            event.preventDefault();
        }

        is_typing = 1;
        var text_type = $('.edit_profile_slogan').text();
        if ($(this).text().length === 30 && event.keyCode != 8) {
            event.preventDefault();
        }

        setTimeout(function () {
            if (text_type == $('.edit_profile_slogan').text()) {
                $.post('_core/request.php', {reason: 'update_slogan', slogan: $('.edit_profile_slogan').text()});
            }
        }, 500);

    });

    function resize_pop(data) {
        $('#' + data + '_pop .pop_content').stop().css('height', $('#' + data + '_pop').find('.pop_inner').height() + 'px');
    }

    $(document).on('click', '.click_my_profile', function () {

        window.location = site_url + logged_user;

    });

    $(document).on('click', '.click_settings', function () {

        window.location = site_url + 'index.php?settings=1';

    });

    $(document).on('click', '.click_add_photos', function () {

        if (logged_id != '0') {
            if (content_category == '1') {
                if (mobile == '1') {
                    $('.close_menu').click();
                }
                $('#content_category').stop().addClass('show');
                if (mobile == '1') {
                    $('#content_category .pop_content').stop().css('height', '400px');
                    $('#content_category .overflow').stop().css('overflow', 'auto').css('height', '350px');
                } else {
                    $('#content_category .pop_content').stop().css('height', $('#content_category .pop_content .pop_inner').height() + 'px');
                }
            } else {
                $('#_uploader').click();
            }
        } else {
            $('.open_pop[data-id="login"]').click();
        }
    });

    $(document).on('click', '.choose_upload_type', function () {

        var id = $(this).data('id');

        $('#content_category').stop().removeClass('show');
        $('#content_category_id').stop().val(id);
        $('#_uploader').click();

    });

    $(document).on('click', '.vote_button_click', function () {

        if (logged_id == '0' && visitors_rate == '0') {
            $('.open_pop[data-id="login"]').click();
        } else {

            $('.vote_button_click').stop().hide();
            $('.vote_loader').stop().show();

            $.post('_core/request.php', {reason: 'rate', rate: 1, photo_id: photo_id}, function (get) {

                if (get.error == '0') {

                    $('.photo_rank_update').stop().text('#' + get['new_rank']);
                    $('.nr_rating_update').stop().text(get.new_rating);
                    $('.nr_ratings_update').stop().text(get.nr_ratings);

                    $('.photo_rate_message').stop().fadeIn(100).delay(1500).fadeOut(150);

                    if (random_photo == '1') {

                        $.post('_core/request.php', {reason: 'next_random'}, function (get) {

                            if (get.error == '0') {
                                window.location = site_url + 'photo-' + get.random;
                            }

                        }, 'json');

                    } else {
                        $('.vote_loader').stop().hide();
                        $('.vote_button_clicked').stop().removeClass('hide');
                    }


                }

            }, 'json');

        }

    });

    $(document).on('click', '.star_click', function () {

        var star = $(this).data('star');

        if ($('.profile_rating_left2').hasClass('rate_active')) {

            $('.rate_active').stop().removeClass('rate_active');

            if (logged_id == '0' && visitors_rate == '0') {
                $('.open_pop[data-id="login"]').click();
            } else {

                $('.profile_rating_left2').stop().html('<i class="fas fa-spinner fa-spin"></i><br>');

                $.post('_core/request.php', {reason: 'rate', rate: star, photo_id: photo_id}, function (get) {

                    if (get.error == '0') {

                        $('.photo_rank_update').stop().text('#' + get['new_rank']);
                        $('.nr_rating_update').stop().text(get.new_rating);
                        $('.nr_ratings_update').stop().text(get.nr_ratings);

                        $('.photo_rate_message').stop().fadeIn(100).delay(1500).fadeOut(150);

                        if (random_photo == '1') {

                            $.post('_core/request.php', {reason: 'next_random'}, function (get) {

                                if (get.error == '0') {
                                    window.location = site_url + 'photo-' + get.random;
                                }

                            }, 'json');

                        } else {
                            $('.profile_rating_left2').stop().html(rating_bar(get.real_rate, 2.4));
                            $('.profile_rating_left2 svg').each(function () {
                                $(this).stop().addClass('svg83');
                            });
                        }


                    }

                }, 'json');
            }

        }

    });

    $(document).on('mouseover', '.rate_active .star1', function () {
        if ($('.profile_rating_left2').hasClass('rate_active')) {
            $('.star1').find('path').stop().attr('d', 'M10 1l3 6l6 .75l-4.12 4.62L16 19l-6-3l-6 3l1.13-6.63L1 7.75L7 7z');
        }
    }).on('mouseout', '.rate_active .star1', function () {
        if ($('.profile_rating_left2').hasClass('rate_active')) {
            $('.star1').find('path').stop().attr('d', 'M10 1L7 7l-6 .75l4.13 4.62L4 19l6-3l6 3l-1.12-6.63L19 7.75L13 7zm0 2.24l2.34 4.69l4.65.58l-3.18 3.56l.87 5.15L10 14.88l-4.68 2.34l.87-5.15l-3.18-3.56l4.65-.58z');
        }
    });

    $(document).on('mouseover', '.rate_active .star2', function () {
        if ($('.profile_rating_left2').hasClass('rate_active')) {
            $('.star1, .star2').find('path').stop().attr('d', 'M10 1l3 6l6 .75l-4.12 4.62L16 19l-6-3l-6 3l1.13-6.63L1 7.75L7 7z');
        }
    }).on('mouseout', '.rate_active .star2', function () {
        if ($('.profile_rating_left2').hasClass('rate_active')) {
            $('.star1, .star2').find('path').stop().attr('d', 'M10 1L7 7l-6 .75l4.13 4.62L4 19l6-3l6 3l-1.12-6.63L19 7.75L13 7zm0 2.24l2.34 4.69l4.65.58l-3.18 3.56l.87 5.15L10 14.88l-4.68 2.34l.87-5.15l-3.18-3.56l4.65-.58z');
        }
    });

    $(document).on('mouseover', '.rate_active .star3', function () {
        if ($('.profile_rating_left2').hasClass('rate_active')) {
            $('.star1, .star2, .star3').find('path').stop().attr('d', 'M10 1l3 6l6 .75l-4.12 4.62L16 19l-6-3l-6 3l1.13-6.63L1 7.75L7 7z');
        }
    }).on('mouseout', '.rate_active .star3', function () {
        if ($('.profile_rating_left2').hasClass('rate_active')) {
            $('.star1, .star2, .star3').find('path').stop().attr('d', 'M10 1L7 7l-6 .75l4.13 4.62L4 19l6-3l6 3l-1.12-6.63L19 7.75L13 7zm0 2.24l2.34 4.69l4.65.58l-3.18 3.56l.87 5.15L10 14.88l-4.68 2.34l.87-5.15l-3.18-3.56l4.65-.58z');
        }
    });

    $(document).on('mouseover', '.rate_active .star4', function () {
        if ($('.profile_rating_left2').hasClass('rate_active')) {
            $('.star1, .star2, .star3, .star4').find('path').stop().attr('d', 'M10 1l3 6l6 .75l-4.12 4.62L16 19l-6-3l-6 3l1.13-6.63L1 7.75L7 7z');
        }
    }).on('mouseout', '.rate_active .star4', function () {
        if ($('.profile_rating_left2').hasClass('rate_active')) {
            $('.star1, .star2, .star3, .star4').find('path').stop().attr('d', 'M10 1L7 7l-6 .75l4.13 4.62L4 19l6-3l6 3l-1.12-6.63L19 7.75L13 7zm0 2.24l2.34 4.69l4.65.58l-3.18 3.56l.87 5.15L10 14.88l-4.68 2.34l.87-5.15l-3.18-3.56l4.65-.58z');
        }
    });

    $(document).on('mouseover', '.rate_active .star5', function () {
        if ($('.profile_rating_left2').hasClass('rate_active')) {
            $('.star1, .star2, .star3, .star4, .star5').find('path').stop().attr('d', 'M10 1l3 6l6 .75l-4.12 4.62L16 19l-6-3l-6 3l1.13-6.63L1 7.75L7 7z');
        }
    }).on('mouseout', '.rate_active .star5', function () {
        if ($('.profile_rating_left2').hasClass('rate_active')) {
            $('.star1, .star2, .star3, .star4, .star5').find('path').stop().attr('d', 'M10 1L7 7l-6 .75l4.13 4.62L4 19l6-3l6 3l-1.12-6.63L19 7.75L13 7zm0 2.24l2.34 4.69l4.65.58l-3.18 3.56l.87 5.15L10 14.88l-4.68 2.34l.87-5.15l-3.18-3.56l4.65-.58z');
        }
    });

    $('.rate_bar').each(function () {

        var rate = $(this).data('rate');
        $(this).stop().html(rating_bar(rate, 1.1));

    });

    function rating_bar(rate, scale = 1) {

        var full = 'M10 1l3 6l6 .75l-4.12 4.62L16 19l-6-3l-6 3l1.13-6.63L1 7.75L7 7z';
        var empty = 'M10 1L7 7l-6 .75l4.13 4.62L4 19l6-3l6 3l-1.12-6.63L19 7.75L13 7zm0 2.24l2.34 4.69l4.65.58l-3.18 3.56l.87 5.15L10 14.88l-4.68 2.34l.87-5.15l-3.18-3.56l4.65-.58z';
        var half = 'M10 1L7 7l-6 .75l4.13 4.62L4 19l6-3l6 3l-1.12-6.63L19 7.75L13 7zm0 2.24l2.34 4.69l4.65.58l-3.18 3.56l.87 5.15L10 14.88V3.24z';

        var new_scale = 'transform:scale(' + scale + ');';

        var star_full = '<svg width="1em" height="1em" ' + (scale == '2.4' ? 'style="margin:5px;"' : '') + '><path d="' + full + '" fill="currentColor" style="' + new_scale + '"></path></svg>';
        var star_empty = '<svg width="1em" height="1em" ' + (scale == '2.4' ? 'style="margin:5px;"' : '') + '><path d="' + empty + '" fill="currentColor" style="' + new_scale + '"></path></svg>';
        var star_half = '<svg width="1em" height="1em" ' + (scale == '2.4' ? 'style="margin:5px;"' : '') + '><path d="' + half + '" fill="currentColor" style="' + new_scale + '"></path></svg>';

        var star = '';

        if (rate == '0') {
            star = star_empty + star_empty + star_empty + star_empty + star_empty;
        }

        if (rate == '0.5') {
            star = star_half + star_empty + star_empty + star_empty + star_empty;
        }

        if (rate == '1') {
            star = star_full + star_empty + star_empty + star_empty + star_empty;
        }

        if (rate == '1.5') {
            star = star_full + star_half + star_empty + star_empty + star_empty;
        }

        if (rate == '2') {
            star = star_full + star_full + star_empty + star_empty + star_empty;
        }

        if (rate == '2.5') {
            star = star_full + star_full + star_half + star_empty + star_empty;
        }

        if (rate == '3') {
            star = star_full + star_full + star_full + star_empty + star_empty;
        }

        if (rate == '3.5') {
            star = star_full + star_full + star_full + star_half + star_empty;
        }

        if (rate == '4') {
            star = star_full + star_full + star_full + star_full + star_empty;
        }

        if (rate == '4.5') {
            star = star_full + star_full + star_full + star_full + star_half;
        }

        if (rate == '5') {
            star = star_full + star_full + star_full + star_full + star_full;
        }

        return star;

    }

    $(window).scroll(function () {
        if ($(window).scrollTop() > (parseInt($(document).height() - 450) - $(window).height())) {
            if (is_loading == '0') {

                is_loading = 1;

                if (this_page == 'home' && $('.home_photos').data('stop') == '0') {
                    var id = $('.home_category_selected').data('id');
                    $('.loading_home_photos').stop().show();
                    get_photos('home', 0, id);
                }

                if (this_page == 'profile' && $('.profile_photos').data('stop') == '0') {
                    get_photos('profile', profile_id, -1);
                }

                if (this_page == 'ranking' && $('.ranking_items').data('stop') == '0') {
                    var order = $('.ranking_filter_op_selected[data-type="order"]').data('id');
                    var category = $('.ranking_filter_op_selected[data-type="category"]').data('id');
                    load_rankings(order, category);
                }

            }
        }
    });

    function onImagesLoaded(container, event) {

        var images = container.getElementsByTagName("img");
        var loaded = images.length;
        for (var i = 0; i < images.length; i++) {
            if (images[i].complete) {
                loaded--;
            } else {
                images[i].addEventListener("load", function () {
                    loaded--;
                    if (loaded == 0) {
                        event();
                    }
                });
                images[i].addEventListener("error", function () {
                    loaded--;
                    if (loaded == 0) {
                        event();
                    }
                });
            }
            if (loaded == 0) {
                event();
                fix_footer();
            }
        }

    }

    function get_photos(page, id2, category = -1) {

        $('.loading_' + page + '_photos').stop().show();

        var page_nr = $('#' + page + '_photos').data('page');
        $('#' + page + '_photos').stop().data('page', parseInt(page_nr) + 1);

        $.post('_core/request.php', {
            reason: 'photos',
            page_nr: page_nr,
            id2: id2,
            type: page,
            category: category
        }, function (ret) {

            var i = 0;
            for (i = 0; i <= ret['files'].length - 1; i++) {

                if (display_thumb_rate == '1') {
                    var extra = '' +
                        '<div class="thumb_extra_rate">' +
                        '<div class="thumb_extra_rate_star">' +
                        '<svg width="1em" height="1em"><path d="M10 1l3 6l6 .75l-4.12 4.62L16 19l-6-3l-6 3l1.13-6.63L1 7.75L7 7z" fill="currentColor" style="transform:scale(1);"></path></svg>' +
                        '</div>' +
                        '<div class="thumb_extra_rate_val">' + ret['files'][i].rate_real + '</div>' +
                        '</div>';
                } else {
                    var extra = '';
                }

                if (page == 'profile' && this_is_my_profile == '1') {
                    var extra = '';
                    var thumb_options = '' +
                        '<div class="thumb_options">' +
                        '<div class="thumb_rotate"><i class="fas fa-undo"></i></div>' +
                        '<div class="thumb_edit"><i class="fas fa-pencil-alt"></i></div>' +
                        '<div class="thumb_trash"><i class="fas fa-trash"></i></div>' +
                        '</div>';
                } else {
                    var thumb_options = '';
                }

                if (ret['files'][i].type == '0') {
                    var thumb_picture = site_url + '_uploads/_photos/' + ret['files'][i].photo + '_400.jpg';
                }

                if (ret['files'][i].type == '1') {
                    if (ret['files'][i].cover.length > 5) {
                        var thumb_picture = site_url + '_uploads/_content_cover/' + ret['files'][i].cover + '_400.jpg';
                    } else {
                        var thumb_picture = site_url + '_img/no_thumb_music.jpg';
                    }
                }

                if (ret['files'][i].type == '2') {
                    if (ret['files'][i].cover.length > 5) {
                        var thumb_picture = site_url + '_uploads/_content_cover/' + ret['files'][i].cover + '_400.jpg';
                    } else {
                        var thumb_picture = site_url + '_img/no_thumb_video.jpg';
                    }
                }

                var thumb = '' +
                    '<div class="thumb" data-id="' + ret['files'][i].id + '" style="position:relative;display:none;">' +
                    '<a href="photo-' + ret['files'][i].id + '" style="text-decoration:none;color:inherit;">' + thumb_options + extra +
                    '<img src="' + thumb_picture + '" class="cover_for_' + ret['files'][i].id + '" />' +
                    '</a>' +
                    '</div>';

                $('.' + page + '_photos').append(thumb);

                var container = document.getElementById(page + '_photos');

                onImagesLoaded(container, function () {

                    $('.loading_' + page + '_photos, .' + page + '_no_photos').stop().hide();
                    $('.thumb').each(function () {
                        $(this).stop().show();
                    });

                    is_loading = 0;

                });

            }

            if (ret['files'].length == '0') {

                $('.' + page + '_photos').stop().data('stop', 1);

                $('.loading_' + page + '_photos').stop().hide();
                if (!$('.thumb')[0]) {
                    $('.' + page + '_no_photos').stop().show();
                }

            }

        }, 'json');

    }

    $(document).on('click', '.home_category', function () {

        if (!$(this).hasClass('home_category_selected')) {

            $('.home_category_selected').stop().removeClass('home_category_selected');
            $(this).stop().addClass('home_category_selected');
            var id = $(this).data('id');
            $('.home_photos').stop().html('');
            $('.home_no_photos').stop().hide();
            $('.loading_home_photos').stop().show();
            $('#home_photos').stop().data('page', '0');
            get_photos('home', 0, id);

        }

    });

    $(document).ready(function () {

        if (this_page == 'home') {
            get_photos('home', 0, -1);
        }

        if (this_page == 'profile') {
            get_photos('profile', profile_id, -1);
        }

        if (this_page == 'ranking') {
            load_rankings(0);
        }

    });

    $('#_uploader').on('change', function () {
        $('#upload_photos').submit();
    });

    $('#_uploader_profile').on('change', function () {
        $('#upload_profile_picture').submit();
    });

    $('#_uploader_cover').on('change', function () {
        $('#upload_cover_form').submit();
    });

    $(document).on('click', '.upload_new_profile', function () {
        $('#_uploader_profile').click();
    });

    $(document).on('click', '.thumb_rotate', function () {

        if (confirm(lang_rotate_photo)) {

            var id = $(this).parent().parent().parent().data('id');
            $.post('_core/request.php', {reason: 'rotate', image_id: id}, function (get) {

                if (get.error == '0') {
                    $('.thumb[data-id="' + id + '"]').find('img').stop().attr('src', site_url + '_uploads/_photos/' + get.file + '_400.jpg');
                }

            }, 'json');

        }

    });

    $(document).on('click', '.thumb_edit', function () {

        var id = $(this).parent().parent().parent().data('id');

        $('#edit_photo').stop().data('id', id).show();
        $('#edit_photo_description').stop().val('');

        $('#main_id').stop().val(id);

        $.post('_core/request.php', {reason: 'get_description', photo_id: id}, function (get) {

            if (get.error == '0') {

                if (get.type == '0') {
                    $('.pop_content_editor_left').stop().hide();
                    $('.pop_content_editor_right').stop().addClass('pop_content_editor_full');
                } else {
                    $('.pop_content_editor_left').stop().show();
                    $('.pop_content_editor_right').stop().removeClass('pop_content_editor_full');
                }

                $('#edit_photo_description').stop().val(get.description);

            }

        }, 'json');

    });

    $(document).on('click', '.click_save_photo', function () {

        var id = $('#edit_photo').data('id');
        var description = $('#edit_photo_description').val();

        $.post('_core/request.php', {reason: 'update_description', description: description, photo_id: id});
        $('#edit_photo').stop().hide();

    });

    $(document).on('click', '.thumb_trash', function () {

        if (confirm(lang_remove_photo)) {

            var id = $(this).parent().parent().parent().data('id');
            $('.thumb[data-id="' + id + '"]').stop().remove();
            $.post('_core/request.php', {reason: 'remove_photo', id: id});

            var old_count = parseInt($('.my_profile_count_photos').text());
            var new_count = old_count - 1;
            if (new_count < 0) {
                new_count = 0;
            }
            $('.my_profile_count_photos').stop().text(new_count);

            if (!$('.thumb')[0]) {
                $('.profile_no_photos').stop().show();
            }
        }

    });

    $(document).on('click', '.change_photo_cover', function () {

        $('#_uploader_cover').click();

    });

    $('#upload_cover_form').ajaxForm({
        dataType: 'json',
        beforeSend: function () {

            if ($('#_uploader_cover')[0].files.length) {
                $('#_loading').stop().show();
                $('.loading_procent').stop().text('0%');
            } else {
                return false;
            }
        },
        uploadProgress: function (event, position, total, percentComplete) {
            $('.loading_procent').stop().text(percentComplete + '%');
        },
        complete: function (xhr) {

            var ret = xhr.responseJSON;

            $('#_loading').stop().hide();

            if (!ret) {
                alert(lang_error_default);
                return false;
            }

            if (ret['file'].length && ret['ok'] == '1') {
                $('.preview_content_cover').stop().attr('src', site_url + '_uploads/_content_cover/' + ret['file'] + '_400.jpg');
                $('.cover_for_' + $('#main_id').val()).stop().attr('src', site_url + '_uploads/_content_cover/' + ret['file'] + '_400.jpg');
            }

        }
    });

    $('#upload_profile_picture').ajaxForm({
        dataType: 'json',
        beforeSend: function () {

            if ($('#_uploader_profile')[0].files.length) {
                $('#_loading').stop().show();
                $('.loading_procent').stop().text('0%');
            } else {
                return false;
            }
        },
        uploadProgress: function (event, position, total, percentComplete) {
            $('.loading_procent').stop().text(percentComplete + '%');
        },
        complete: function (xhr) {

            var ret = xhr.responseJSON;

            $('#_loading').stop().hide();

            if (!ret) {
                alert(lang_error_default);
                return false;
            }

            if (ret['file'].length) {
                $('.remove_profile_picture').stop().css('display', 'inline-block');
                $('.profile_picture_box').stop().html('<img src="' + site_url + '_uploads/_profile_pictures/' + ret['file'] + '.jpg" />');
            }

        }
    });

    function uploader_error(type) {

        $('#_loading').stop().hide();

        $('.upload_error').stop().show();

        $('.upload_error_1, .upload_error_2, .upload_error_3').stop().hide();
        $('.upload_error_' + type).stop().show();

        setTimeout(function () {
            $('.upload_error').stop().hide();
        }, 1500);

        return false;

    }

    $('#upload_photos').ajaxForm({
        dataType: 'json',
        beforeSerialize: function () {

            if ($('#_uploader')[0].files.length) {
                if ($('#_uploader')[0].files.length > max_files) {
                    uploader_error(1);
                    return false;
                } else {

                    var overlimit = 0;
                    var allfiles = document.getElementById('_uploader');
                    for (var i = 0; i < allfiles.files.length; i++) {
                        var file = allfiles.files[i];
                        if (file.size > max_uploadsize) {
                            overlimit = 1;
                        }
                    }

                    if (overlimit == '1') {
                        uploader_error(2);
                        return false;
                    } else {
                        $('#_loading').stop().show();
                        $('.loading_procent').stop().text('0%');
                    }

                }
            } else {
                return false;
            }

        },
        uploadProgress: function (event, position, total, percentComplete) {
            $('.loading_procent').stop().text(percentComplete + '%');
        },
        clearForm: true,
        resetForm: true,
        complete: function (xhr) {

            var ret = xhr.responseJSON;

            if (!ret) {
                uploader_error(3);
                return false;
            }

            $('#_loading').stop().hide();

            if (ret['ok'] == '1' && this_is_my_profile == '0' && photo_approval == '1') {
                window.location = site_url + logged_user;
            }

            if (ret['files'].length && this_is_my_profile == '0') {

                if (ret['files'].length == '1') {
                    window.location = site_url + 'photo-' + ret['files'][0].id;
                } else {
                    window.location = site_url + logged_user;
                }

            }

            if (ret['ok'] == '1' && photo_approval == '1' && this_is_my_profile == '1') {
                $('.profile_photos_pending').stop().show();
            }

            if (ret['files'].length && this_is_my_profile == '1') {

                var i = 0;
                for (i = 0; i <= ret['files'].length - 1; i++) {

                    if (this_page == 'profile') {
                        var thumb_options = '' +
                            '<div class="thumb_options">' +
                            '<div class="thumb_rotate"><i class="fas fa-undo"></i></div>' +
                            '<div class="thumb_edit"><i class="fas fa-pencil-alt"></i></div>' +
                            '<div class="thumb_trash"><i class="fas fa-trash"></i></div>' +
                            '</div>';
                    } else {
                        var thumb_options = '';
                    }

                    var thumb = '' +
                        '<div class="thumb" data-id="' + ret['files'][i].id + '" style="position:relative;display:none;">' +
                        '<a href="photo-' + ret['files'][i].id + '" style="text-decoration:none;color:inherit;">' + thumb_options +
                        '<img src="' + site_url + '_uploads/_photos/' + ret['files'][i].photo + '_400.jpg" />' +
                        '</a>' +
                        '</div>';

                    $('.' + this_page + '_photos').prepend(thumb);

                    var container = document.getElementById(this_page + '_photos');

                    onImagesLoaded(container, function () {

                        $('.loading_' + this_page + '_photos, .' + this_page + '_no_photos').stop().hide();
                        $('.thumb').each(function () {
                            $(this).stop().show();
                        });

                    });

                }

                var old_count = parseInt($('.my_profile_count_photos').text());
                var new_count = old_count + ret['files'].length;
                $('.my_profile_count_photos').stop().text(new_count);

            }

        }
    });

    $(document).on('click', '.click_logout', function () {

        $.post('_core/request.php', {reason: 'logout'}, function () {
            $('.button3').click();
            window.location.reload(true);
        });

    });

    $(document).on('click', '.button3s', function () {

        if (!$('.button3_menu').is(':visible')) {
            $('#d_menu_open').stop().html('<i class="fas fa-chevron-up"></i>');
            $('.button3_menu').stop().show();
        } else {
            $('#d_menu_open').stop().html('<i class="fas fa-chevron-down"></i>');
            $('.button3_menu').stop().hide();
        }

    });

    $(document).on('click', '.open_menu', function () {

        $('.main').stop().css('margin-left', '-100%');
        $('.menu_mobile').stop().css('left', '0');

    });

    $(document).on('click', '.close_menu', function () {

        $('.main').stop().css('margin-left', '0');
        $('.menu_mobile').stop().css('left', '100%');

    });

    function info_er(data, tip, text, expr = 2000) {

        $('.pop_error, .pop_succes').stop().hide();

        if (tip == '1') {
            var er_tip = 'succes';
        } else {
            var er_tip = 'error';
        }

        $('#' + data + '_pop .pop_' + er_tip).stop().show().text(text);
        resize_pop(data);

        setTimeout(function () {
            $('#' + data + '_pop .pop_' + er_tip).stop().hide();
            resize_pop(data);
        }, 2000);

    }

    $(document).on('keypress', '#login_email, #login_password', function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            $('.click_login').click();
        }
    });

    $(document).on('keypress', '#register_email, #register_name, #register_password, #register_repeat_password', function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            $('.click_register').click();
        }
    });

    $(document).on('click', '.click_forgot', function () {

        var email = $('#email_forgot').val();
        if (email.length < 2) {
            info_er('forgot', 2, lang_forgot_not_found, 10000);
            $('#email_forgot').stop().val('');
        } else {

            $.post('_core/request.php', {reason: 'forgot', email: email}, function (get) {

                if (get.error == '1') {
                    info_er('forgot', 2, lang_forgot_not_found);
                } else {
                    info_er('forgot', 1, lang_forgot_found);
                }

            }, 'json');

        }

    });

    $(document).on('click', '.click_login', function () {

        var email = $('#login_email').val();
        var password = $('#login_password').val();

        if (email.length < 4 || password.length < 6) {
            info_er('login', 2, lang_login_error);
        } else {
            $.post('_core/request.php', {email: email, password: password, reason: 'login'}, function (get) {

                if (get.error == '0') {
                    $('#login_pop .pop_inner').stop().css('opacity', '0.8');
                    $('.click_login').stop().css('background', '#00cc00').html('<i class="fas fa-spinner fa-spin"></i>');
                    setTimeout(function () {
                        window.location.reload(true);
                    }, 700);
                } else {
                    $('#login_password').stop().val('');
                    info_er('login', 2, get.error_text);
                }

            }, 'json');
        }

    });

    $(document).on('click', '.click_notlogged', function () {

        $('.open_pop[data-id="login"]').click();
        return false;

    });

    $(document).on('click', '.click_register', function () {

        var email = $('#register_email').val();
        var name = $('#register_name').val();
        var password = $('#register_password').val();
        var repeat_password = $('#register_repeat_password').val();

        if (category_required == '1') {
            var category = $('#register_category').val();
        } else {
            var category = 0;
        }

        $.post('_core/request.php', {
            email: email,
            name: name,
            password: password,
            repeat_password: repeat_password,
            category: category,
            reason: 'register'
        }, function (get) {

            if (get.error == '0') {
                $('#login_pop .pop_inner').stop().css('opacity', '0.8');
                $('.click_register').stop().css('background', '#00cc00').html('<i class="fas fa-spinner fa-spin"></i>');
                setTimeout(function () {
                    window.location.reload(true);
                }, 700);
            } else {
                info_er('register', 2, get.error_text);
            }

        }, 'json');

    });

    $(document).on('click', '.close_pop', function () {

        $('.pop').stop().fadeOut(150);

    });

    $(document).on('click', '.open_pop', function () {

        $('.pop').stop().hide();
        $('.close_menu').click();

        var data = $(this).data('id');
        $('#' + data + '_pop').stop().show();

        resize_pop(data);

    });

    $(document).on('click', '.thumb_options', function () {
        return false;
    });

})(jQuery);