
	<div class="padding_20 boxn12">
		<div>Language</div>
		<div class="pgtitle">Translate everything</div>
	</div>

	<div class="padding_20">

		<?php if(isset($success_msg) && $success_msg) { ?>
		<div class="success_box_new show">
			<div class="scb_icon"><i class="fas fa-check"></i></div>
			<div class="scb_text"><?=$success_msg;?></div>
		</div>
		<br><br>
		<?php } ?>

		<form action="index.php?page=language" method="post">
		<?php
		$plus = 0;
		$contents = file_get_contents('../_language/english.php');
		$contents = str_replace('<?php','',$contents);
		$contents = str_replace('?>','',$contents);
		$contents_new = explode("\n",$contents);
		foreach($contents_new as $content) {
			if(substr($content,0,14) == 'define("_LANG_') {
				preg_match("/define\(\"_LANG_(.*?)\",\s\"(.*?)\"\);/i", $content, $data_s);
				if(isset($data_s[1]) && isset($data_s[2]) && $data_s[1] != '' && $data_s[2] != '') {
					echo '
					<div class="boxn14">
						<div class="str_left">'.str_replace('_',' ',$data_s[1]).'</div>
						<div class="str_right">
							<input type="text" class="str_right_inp" value="'.$data_s[2].'" name="'.$data_s[1].'" />
						</div>
					</div>';
				}
			}
		}
		?>

		<div class="boxn13">
			<div class="str_left">&nbsp;</div>
			<div class="str_right">
				<button type="submit" name="submit" class="submit_but">
					<i class="fas fa-check"></i>&nbsp;&nbsp;Save changes
				</button>
			</div>
		</form>

	</div>