
	<div class="ranking">

		<div class="celling_ranking">

			<div class="ranking_tab_2">

				<div class="ranking_filter_button_mobile" onclick="$('.ranking_filters').toggle();"><i class="fas fa-align-left"></i>&nbsp;&nbsp;<?=_LANG_RANKING_FILTERS;?></div>

				<div class="ranking_filters">

					<div class="ranking_filter"><?=_LANG_RANKING_FILTERS_ORDER_BY;?></div>

					<div class="ranking_filter_options">
						<div class="ranking_filter_op ranking_filter_op_selected" data-type="order" data-id="1">
							<div class="ranking_filter_check"></div>
							<div class="ranking_filter_value"><?=_LANG_RANKING_FILTERS_BEST_SCORE;?></div>
						</div>
						<div class="ranking_filter_op" data-type="order" data-id="2">
							<div class="ranking_filter_check"></div>
							<div class="ranking_filter_value"><?=_LANG_RANKING_FILTERS_NR_RATINGS;?></div>
						</div>
						<div class="ranking_filter_op" data-type="order" data-id="3">
							<div class="ranking_filter_check"></div>
							<div class="ranking_filter_value"><?=_LANG_RANKING_FILTERS_NR_VIEWS;?></div>
						</div>
					</div>

					<!-- TNSB_EDIT_FOR_CUSTOMIZATION_STARTS_HERE -->
					<div class="ranking_filter margin_top_35">Rating Type</div>
					<div class="ranking_filter_options">
						<div class="ranking_filter_op ranking_filter_op_selected" data-type="rating_type" data-id="hot_not">
								<div class="ranking_filter_check"></div>
								<div class="ranking_filter_value">Hot or Not</div>
						</div>
						<div class="ranking_filter_op " data-type="rating_type" data-id="five_star">
								<div class="ranking_filter_check"></div>
								<div class="ranking_filter_value">Five Star</div>
						</div>
					</div>
					<!-- TNSB_EDIT_FOR_CUSTOMIZATION_ENDS_HERE -->

					<div class="ranking_filter margin_top_35 <?=($settings['category_required'] == '0' && (isset($settings['content_category']) && $settings['content_category'] == '0') ? 'hide':'');?>"><?=_LANG_RANKING_FILTERS_CATEGORY;?></div>

					<div class="ranking_filter_options <?=($settings['category_required'] == '0' && (isset($settings['content_category']) && $settings['content_category'] == '0') ? 'hide':'');?>">
						<?php foreach(get_categories('home') as $k=>$v) { ?>
						<div class="ranking_filter_op <?=($v['id'] == '-1' ? 'ranking_filter_op_selected':'');?>" data-type="category" data-id="<?=$v['id'];?>">
							<div class="ranking_filter_check"></div>
							<div class="ranking_filter_value"><?=$v['name'];?></div>
						</div>
						<?php } ?>
					</div>

					<?php if($multi_contest == '2') { ?>
					<div class="ranking_filter margin_top_35">Contest</div>

					<div class="ranking_filter_options">
						<?php $ss=0; $sql_s = mysqli_query($db,"SELECT * FROM `contest` WHERE `active` = '1' ORDER BY `end` DESC"); while($v=mysqli_fetch_array($sql_s)) { ?>
						<div class="ranking_filter_op <?=($ss == '0' ? 'ranking_filter_op_selected':'');?>" data-type="contest" data-id="<?=$v['id'];?>">
							<div class="ranking_filter_check"></div>
							<div class="ranking_filter_value"><?=$v['title'];?></div>
						</div>
						<?php $ss++; } ?>
					</div>
					<?php } ?>

				</div>

			</div>

			<div class="ranking_tab_1">

				<div class="ranking_no_photos"><?=_LANG_RANKING_NO_LISTING;?></div>

				<div class="ranking_items" data-page="0" data-stop="0"></div>

				<div class="ranking_loading">
					<i class="fas fa-spinner fa-spin"></i>
				</div>

			</div>

			<div class="clear"></div>

		</div>

	</div>
