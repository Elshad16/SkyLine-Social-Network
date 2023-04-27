<link rel="stylesheet" href="style.css">
<div class="main-right">

	<div class="messages" id="message-box">
		<!-- messagecategory -->
		<!-- messgae -->
		<div class="message">
			<div class="profile-phots">
				<?php

				$image = "images/user_male.jpg";
				if ($FRIEND_ROW['gender'] == "Female") {
					$image = "images/user_female.jpg";
				}

				if (file_exists($FRIEND_ROW['profile_image'])) {
					$image = $image_class->get_thumb_profile($FRIEND_ROW['profile_image']);
				}


				?>

				<a href="<?= ROOT ?><?php echo $FRIEND_ROW['type']; ?>/<?php echo $FRIEND_ROW['userid']; ?>">
					<img src="<?php echo ROOT . $image ?>"></a>



			</div>
			<div class="messgae-body">
				<h5>
					<?php echo $FRIEND_ROW['first_name'] . " " . $FRIEND_ROW['last_name'] ?>
				</h5>
				<p class="text-gry">
					<?php

					$online = "Unknown";
					if ($FRIEND_ROW['online'] > 0) {
						$online = $FRIEND_ROW['online'];

						$current_time = time();
						$threshold = 60 * 2; //2 minutes
					
						if (($current_time - $online) < $threshold) {
							$online = "<span style='color:green;'>Online</span>";
						} else {
							$online = "" . Time::get_time(date("Y-m-d H:i:s", $online));
						}
					}
					?>
					<?php echo $online ?>
				</p>
			</div>
		</div>

	</div>
</div>

<!--change profile image area-->