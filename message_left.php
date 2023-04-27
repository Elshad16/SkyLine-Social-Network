<style>
	.chat .messages-chat {
		padding: 0 35px;
	}

	.chat .messages-chat .message {
		display: flex;
		align-items: center;
		margin-bottom: 8px;
	}


	.chat .messages-chat .text {
		margin: 0 35px;
		background-color: #f6f6f6;
		padding: 15px;
		border-radius: 12px;
	}



	.time {
		font-size: 10px;
		color: blueviolet;
		margin-bottom: 10px;
		margin-left: 85px;
	}

	.response-time {
		float: right;
		margin-right: 0px;
		margin-left: 80%;
	}

	.response {
		float: right;
		margin-right: 0px !important;
		margin-left: 85%;
		/* flexbox alignment rule */
	}

	.response .text {
		background-color: #e3effd !important;
	}

	.input-file {
		position: relative;
		display: inline-block;
	}

	.input-file-btn {
		position: relative;
		display: inline-block;
		cursor: pointer;
		outline: none;
		text-decoration: none;
		font-size: 14px;
		vertical-align: middle;
		color: rgb(255 255 255);
		text-align: center;
		border-radius: 4px;
		background-color: #419152;
		line-height: 22px;
		height: 40px;
		padding: 10px 20px;
		box-sizing: border-box;
		border: none;
		margin: 0;
		transition: background-color 0.2s;
	}

	.input-file-text {
		padding: 0 10px;
		line-height: 40px;
		display: inline-block;
	}

	.input-file input[type=file] {
		position: absolute;
		z-index: -1;
		opacity: 0;
		display: block;
		width: 0;
		height: 0;
	}

	/* Focus */
	.input-file input[type=file]:focus+.input-file-btn {
		box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
	}

	/* Hover/active */
	.input-file:hover .input-file-btn {
		background-color: #59be6e;
	}

	.input-file:active .input-file-btn {
		background-color: #2E703A;
	}

	/* Disabled */
	.input-file input[type=file]:disabled+.input-file-btn {
		background-color: #eee;
	}
</style>
<div id="message_left">
	<div class="row">
		<section class="chat">
			<div class="messages-chat">
				<div class="message">
					<div class="profile-phots">
						<?php

						$image = "images/user_male.jpg";
						if ($ROW_USER['gender'] == "Female") {
							$image = "images/user_female.jpg";
						}

						if (file_exists($ROW_USER['profile_image'])) {
							$image = $image_class->get_thumb_profile($ROW_USER['profile_image']);
						}

						?>

						<img src="<?php echo ROOT . $image ?>">
					</div>
				</div>
				<div class="message text-only">
					<p class="text">
						<?php echo check_tags($MESSAGE['message']) ?>

						<?php

						if (file_exists($MESSAGE['file'])) {

							$post_image = ROOT . $image_class->get_thumb_post($MESSAGE['file']);

							echo "<img src='$post_image' style='width:30%;' />";
						}

						?>
					<p>
						<?php

						$post = new Post();

						echo "<a href='" . ROOT . "delete/msg/$MESSAGE[id]' >";
						echo '<svg fill="red" width="24" height="24" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm4.151 17.943l-4.143-4.102-4.117 4.159-1.833-1.833 4.104-4.157-4.162-4.119 1.833-1.833 4.155 4.102 4.106-4.16 1.849 1.849-4.1 4.141 4.157 4.104-1.849 1.849z"/></svg>';
						echo "</a>";

						?>

					</p>
					</p>
				</div>
				<p class="time">
					<?php echo Time::get_time($MESSAGE['date']) ?>
				</p>
			</div>


			<script>

				$('.input-file input[type=file]').on('change', function () {
					let file = this.files[0];
					$(this).closest('.input-file').find('.input-file-text').html(file.name);
				});
			</script>