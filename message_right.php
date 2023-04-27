<div id="message_right">

	<div style="width: 100%; text-align: right;">
		<div class="row">
			<section class="chat">
				<div class="messages-chat">
					<div class="message text-only">
						<div class="response">
							<p class="text">
								<?php echo check_tags($MESSAGE['message']) ?>

								<?php

								if (file_exists($MESSAGE['file'])) {

									$post_image = ROOT . $image_class->get_thumb_post($MESSAGE['file']);

									echo "<img src='$post_image' style='width:30%;' />";
								}

								?>

							</p>

						</div>

					</div>
					<p class="response-time time">
						<?php echo Time::get_time($MESSAGE['date']) ?>
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
			</section>
		</div>
	</div>