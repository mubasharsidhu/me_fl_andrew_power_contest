
	<div class="pop_ncat" data-id="0">

		<div class="pop_ncat_box pop_ncat_boxad">

			<div class="pop_ncat_head">New ad</div>

			<div class="sat">
				<div class="sat_left">Ad name</div>
				<div class="sat_right">
					<input type="text" placeholder="My first ad ..." maxlength="100" id="ad_title" name="ad_title" />
				</div>
			</div>

			<div class="sat">
				<div class="sat_left">Ad code (HTML)</div>
				<div class="sat_right">
					<textarea rows="4" placeholder="Ad code here ..." maxlength="2000" id="ad_code" name="ad_code"></textarea>
				</div>
			</div>

			<div class="sat">
				<div class="sat_left">Position</div>
				<div class="sat_right">
					<select id="ad_position" name="ad_position">
						<?php foreach($ad_positions as $k=>$v) { ?>
						<option value="<?=$k;?>"><?=$v;?></option>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="sat">
				<div class="sat_left">Privacy</div>
				<div class="sat_right">
					<select id="ad_privacy" name="ad_privacy">
						<option value="1">Everyone</option>
						<option value="2">Only guests</option>
						<option value="3">Only registered</option>
					</select>
				</div>
			</div>

			<div class="pop_ncat_op margin_top12">
				<div class="pop_ncat_op_cancel">Cancel</div>
				<div class="pop_ncat_op_submit_sg"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</div>
			</div>
			
		</div>

	</div>

	<div class="padding_20 boxn12">
		<div class="left">
			<div>Ads</div>
			<div class="pgtitle"><span class="bold"><?=$fetch_count['total_ads'];?></span> active ads</div>
		</div>
		<div class="pgbutton">
			<div class="rallr_sg">+&nbsp;&nbsp;New ad</div>
		</div>
	</div>

	<div class="padding_20">

		<div class="users_dash_cap">
			<div class="users_dash_col bold ads_dash_col_1">Ad name</div>
			<div class="users_dash_col bold ads_dash_col_2">Device</div>
			<div class="users_dash_col bold ads_dash_col_3">Position</div>
			<div class="users_dash_col bold ads_dash_col_4">Privacy</div>
			<div class="center users_dash_col bold ads_dash_col_5">Options</div>
		</div>

		<?php if(!count($ads)) { ?>
		<div class="no_results show">
			<div class="no_results_icon"><i class="fas fa-exclamation-triangle"></i></div>
			<div class="no_results_text">No results</div>
		</div>
		<?php } else { ?>
		<div class="fads_results show" data-page="0" data-stop="0">

			<?php $is=0; foreach($ads as $ad) { ?>
			<div class="users_dash fad fad_<?=$ad['id'];?>">
				<div class="users_dash_col ads_dash_col_1"><?=htmlentities($ad['ad_title']);?></div>
				<div class="users_dash_col ads_dash_col_2">All devices</div>
				<div class="users_dash_col ads_dash_col_3"><?=(isset($ad_positions[$ad['ad_position']]) ? $ad_positions[$ad['ad_position']] : '-');?></div>
				<div class="users_dash_col ads_dash_col_4"><?=str_replace(array('0','1','2','3'),array('-','Everyone','Only guests','Only registered'),$ad['ad_privacy']);?></div>
				<div class="users_dash_col ads_dash_col_5 center">
					<div class="relative">
						<div class="open_pop_menu" data-id="<?=$ad['id'];?>"><i class="fas fa-chevron-down"></i></div>
						<div class="pop_menu" data-id="<?=$ad['id'];?>">
							<div class="pop_menu_item edit_fad overflow" data-id="<?=$ad['id'];?>">
								<div class="pop_menu_item_icon"><i class="fas fa-pencil-alt"></i></div>
								<div class="pop_menu_item_text">Edit ad</div>
							</div>
							<div class="pop_menu_item border_bottom0 remove_fad overflow" data-id="<?=$ad['id'];?>">
								<div class="pop_menu_item_icon red">&#10005;&nbsp;</div>
								<div class="pop_menu_item_text">Remove ad</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php $is++; } ?>

		</div>
		<?php } ?>

	</div>