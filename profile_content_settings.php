<style>
	input {
		width: 100%;
		margin: 10px 0;
		padding: 10px;
	}

	.type-1 {
		border-radius: 10px;
		border: 1px solid #eee;
		transition: .3s border-color;
	}

	.type-1:hover {
		border: 1px solid #aaa;
	}

	textarea {
		font-size: 20px;
		line-height: 24px;
		color: black;
		padding: 20px;
		padding-left: 25px;
		min-height: 200px;
		resize: none;
		overflow: hidden;
		border: none;
		position: relative;
		z-index: 3;
		background-color: transparent;
	}

	textarea:focus {
		outline: none;
	}

	#lines {
		padding-left: 10px;
		display: flex;
		flex-direction: column;
		width: 100%;
		position: relative;
		z-index: 1;
	}

	.line {
		height: 24px;
		box-sizing: border-box;
		width: 100%;
		border-bottom: 1px solid purple;
	}

	#contanier {
		position: relative;
		overflow-y: scroll;
		overflow-x: hidden;
		height: 200px;
		outline: 2px solid black;
		width: fit-content;
	}

	#contanier:focus-within {
		outline: 2px solid purple;
	}

	#contanier::-webkit-scrollbar {
		width: 3px;
	}

	#contanier::-webkit-scrollbar-thumb {
		background-color: purple;
	}
</style>
<div style="min-height: 400px;width:100%;background-color: white;text-align: center;">
	<div style="padding: 20px;max-width:350px;display: inline-block;">
		<form method="post" enctype="multipart/form-data">

			<input type="text" />
			<?php

			$settings_class = new Settings();

			$settings = $settings_class->get_settings($_SESSION['mybook_userid']);

			if (is_array($settings)) {

				echo "<input type='text' class='type-1' name='first_name' value='" . htmlspecialchars($settings['first_name']) . "' placeholder='First name' />";
				echo "<input type='text' class='type-1' name='last_name' value='" . htmlspecialchars($settings['last_name']) . "' placeholder='Last name' />";

				echo "<select class='type-1' name='email' style='height:30px;'>

							<option>" . htmlspecialchars($settings['gender']) . "</option>
							<option>Male</option>
							<option>Female</option>
						</select>";

				echo "<input type='text' class='type-1' name='email'  value='" . htmlspecialchars($settings['email']) . "' placeholder='Email'/>";
				echo "<input type='password' class='type-1' name='password'  value='" . htmlspecialchars($settings['password']) . "' placeholder='Password'/>";
				echo "<input type='password' class='type-1' name='password2'  value='" . htmlspecialchars($settings['password']) . "' placeholder='Password'/>";

				echo "<br>About me:<br>
				<div id='contanier'>
     <textarea name='about' placeholder='About'>" . htmlspecialchars($settings['about']) . "</textarea>
     <div id='lines'></div>
</div>
						
						";

				echo '<input id="post_button" type="submit" value="Save">';
			}

			?>

		</form>
	</div>
</div>
<script>const textarea = document.querySelector('textarea');

	textarea.oninput = function (event) {
		const lines = document.querySelector('#lines');
		event.target.style.height = "0px";
		event.target.style.height = (event.target.scrollHeight) + "px";
		lines.style.marginTop = (-event.target.scrollHeight + 18) + "px";
		let str = '';
		event.target.value.split('\n').forEach(() => {
			str += '<div class="line"></div>';
		})
		console.log(str);
		lines.innerHTML = str;
	}</script>