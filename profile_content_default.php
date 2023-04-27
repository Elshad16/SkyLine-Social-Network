<div style="min-height: 700px;flex:2.5;padding: 20px;padding-right: 0px;">
	<link rel="stylesheet" href="style.css">

	<?php if (!($group_data['group_type'] == 'public' && !group_access($_SESSION['mybook_userid'], $group_data, 'member'))): ?>
		<div class="main-mid">
			<form class="creatPost" method="post" enctype="multipart/form-data">
				<div class="text-post">
					<input name="post" type="text" placeholder="whast's on your mind?" class="creatPost">
					<input type="file" name="file" value="Select a file">
				</div>

				<div>
					<input type="submit" value="post" class="btn btn-primary">




				</div>
			</form>
		</div>
	<?php endif; ?>

	<!--posts-->
	<div id="post_bar">

		<?php

		if ($posts) {

			foreach ($posts as $ROW) {
				# code...
		
				$user = new User();
				$ROW_USER = $user->get_user($ROW['userid']);

				include("post.php");
			}
		}

		//get current url
		$pg = pagination_link();
		?>

		<a href="<?= $pg['next_page'] ?>">
			<input id="post_button" type="button" value="Next Page" style="float: right;width:150px;">
		</a>
		<a href="<?= $pg['prev_page'] ?>">
			<input id="post_button" type="button" value="Prev Page" style="float: left;width:150px;">
		</a>
	</div>

</div>

<!--posts area-->