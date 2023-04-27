<link rel="stylesheet" href="style.css">
<div class="main-right">

	<div class="messages" id="message-box">
		<!-- messagecategory -->
		<!-- messgae -->
		<div class="message">
			<div class="profile-phots">
				<?php

				$image = $image_class->get_thumb_profile("images/cover_image.jpg");

				if (file_exists($FRIEND_ROW['cover_image'])) {
					$image = $image_class->get_thumb_profile($FRIEND_ROW['cover_image']);
				}


				?>

				<a href="<?= ROOT ?>group/<?php echo $FRIEND_ROW['userid']; ?>">
					<img src="<?php echo ROOT . $image ?>">
			</div>
			<div class="messgae-body">
				<h5>
					<?php echo $FRIEND_ROW['first_name'] ?>
				</h5>
				<p class="text-gry">
					<?php echo $FRIEND_ROW['group_type'] ?>
				</p>
			</div>
		</div>

	</div>
</div>