<style>
	.discussions {
		width: 100%;

		box-shadow: 0px 8px 10px rgba(0, 0, 0, 0.20);
		overflow: hidden;
		display: inline-block;
	}

	.discussions .discussion {
		width: 100%;
		height: 90px;
		background-color: #FAFAFA;
		border-bottom: solid 1px #E0E0E0;
		display: flex;
		align-items: center;
		cursor: pointer;
	}

	.desc-contact {
		height: 43px;
		width: 50%;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.discussions .discussion .name {
		margin: 0 0 0 20px;
		font-family: 'Montserrat', sans-serif;
		font-size: 11pt;
		color: #515151;
	}

	.discussions .discussion .message {
		margin: 6px 0 0 20px;
		font-family: 'Montserrat', sans-serif;
		font-size: 9pt;
		color: #515151;
	}

	.timer {
		margin-left: 15%;
		font-family: 'Open Sans', sans-serif;
		font-size: 11px;
		padding: 3px 8px;
		color: #BBB;
		background-color: #FFF;
		border: 1px solid #E5E5E5;
		border-radius: 15px;
	}
</style>

<div class="container">
	<div class="row">
		<section class="discussions">
			<div class="discussion">

				<div class="photo">
					<?php

					$image = "images/user_male.jpg";
					if ($ROW_USER['gender'] == "Female") {
						$image = "images/user_female.jpg";
					}

					if (file_exists($ROW_USER['profile_image'])) {
						$image = $image_class->get_thumb_profile($ROW_USER['profile_image']);
					}

					?><img src="<?php echo ROOT . $image ?>" style="margin-left: 20px;
					display: block;
					width: 45px;
					height: 45px;
					background: #E6E7ED;
					-moz-border-radius: 50px;
					-webkit-border-radius: 50px;
					background-position: center;
					background-size: cover;
					background-repeat: no-repeat;">
				</div>
				<div class="desc-contact">
					<p class="name">
						<?php
						echo "<a href='" . ROOT . "profile/$MESSAGE[msgid]'>";
						echo htmlspecialchars($ROW_USER['first_name']) . " " . htmlspecialchars($ROW_USER['last_name']);
						echo "</a>";


						?>
					</p>
					<p class="message">
						<?php echo check_tags($MESSAGE['message']) ?>

						<?php

						if (file_exists($MESSAGE['file'])) {

							$post_image = ROOT . $image_class->get_thumb_post($MESSAGE['file']);

							echo "<img src='$post_image' style='width:60px;' />";
						}

						?>
					</p>
				</div>
				<div class="timer">
					<?php echo Time::get_time($MESSAGE['date']) ?>
				</div>
			</div>
		</section>
	</div>
</div>