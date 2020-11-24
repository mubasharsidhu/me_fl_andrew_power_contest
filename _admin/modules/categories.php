
	<div class="pop_ncat" data-id="0">

		<div class="pop_ncat_box">

			<div class="pop_ncat_head">New category</div>

			<div class="padding_15">
				<input type="text" id="ncat_i" placeholder="My new category ..." />
			</div>

			<div class="pop_ncat_op">
				<div class="pop_ncat_op_cancel">Cancel</div>
				<div class="pop_ncat_op_submit"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</div>
			</div>
			
		</div>

	</div>

	<div class="padding_20 boxn12">
		<div class="left">
			<div>Categories</div>
			<div class="pgtitle"><span class="bold"><?=$fetch_count['total_categories'];?></span> total categories</div>
		</div>
		<div class="pgbutton">
			<div class="rallr_s">+&nbsp;&nbsp;New category</div>
		</div>
	</div>

	<div class="padding_20">

		<div class="no_results">
			<div class="no_results_icon"><i class="fas fa-exclamation-triangle"></i></div>
			<div class="no_results_text">No results</div>
		</div>

		<div class="categories_results extra_old2"></div>

		<div class="cloading"><i class="fas fa-spinner fa-spin"></i></div>

	</div>