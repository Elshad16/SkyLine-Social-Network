<style>
	#profile-card {
		position: relative;
		padding: 25px;
		border-radius: 3px;
		background: linear-gradient(to right bottom,
				rgba(255, 255, 255, 0.6),
				rgba(255, 255, 255, 0.2));
	}

	.profile-image {
		height: 100px;
		width: 100px;
		margin: auto;
		margin-bottom: 5px;
		position: relative;
	}

	.profile-image img {
		height: 100%;
		width: 100%;
		border-radius: 50%;
		object-fit: cover;
		border: 3px solid rgba(255, 255, 255, 0.4);
	}



	h3.profile-name {
		text-align: center;
		color: #25283a;
	}

	p.profession {
		text-align: center;
		color: #25283a;
		font-size: 13px;
		opacity: 0.6;
		font-weight: 500;
	}

	.social {
		margin: 20px 0px;
		text-align: center;
	}

	.social a {
		background: white;
		color: #25283a;
		height: 35px;
		width: 35px;
		border-radius: 50%;
		display: inline-flex;
		justify-content: center;
		align-items: center;
		text-decoration: none;
		margin: 0px 5px;
		transition: 0.3s;
		box-shadow: 2px 2px 5px rgb(28 115 214 / 20%);
	}

	.facebook:hover {
		color: white;
		background: #3b5998;
	}

	.instagram:hover {
		color: white;
		background: linear-gradient(to top right, #feda77, #f58529, #dd217b, #8134af, #515bd4);
	}

	.twitter:hover {
		color: white;
		background: #1DA1F2;
	}

	.telegram:hover {
		color: white;
		background: #0088cc;
	}

	.contact {
		text-align: center;
		margin-bottom: 20px;
	}

	.contact a {
		text-decoration: none;
		background: white;
		padding: 7px 15px;
		display: inline-block;
		border-radius: 3px;
		color: #25283a;
		font-weight: 500;
		box-shadow: 2px 2px 5px rgb(28 115 214 / 20%);
		transition: 0.3s;
	}

	a.message {
		color: white;
		background: #1b62c3;
		margin-right: 10px;
	}

	a.message:hover {
		background: #1843a3;
	}

	a.subscribe:hover {
		background: #c4302b;
		color: white;
	}

	.achivement {
		display: flex;
		justify-content: space-between;
	}

	.react,
	.share,
	.comment {
		text-align: center;
		color: #25283a;
		font-size: 13px;
		cursor: pointer;
	}

	.three-dots {
		position: absolute;
		display: inline-block;
		top: 0;
		right: 0;
		padding: 15px;
		cursor: pointer;
	}

	.three-dots i {
		color: #25283a;
	}

	.arrow {
		position: absolute;
		display: inline-block;
		top: 0;
		left: 0;
		padding: 15px;
		cursor: pointer;
	}

	.arrow i {
		color: #25283a;
	}
</style>
<div id="profile-card">
	<div class="profile-image">
		<?php

		$image = "images/user_male.jpg";
		if ($FRIEND_ROW['gender'] == "Female") {
			$image = "images/user_female.jpg";
		}

		if (file_exists($FRIEND_ROW['profile_image'])) {
			$image = $image_class->get_thumb_profile($FRIEND_ROW['profile_image']);
		}


		?>

		<a href="<?= ROOT ?>profile/<?php echo $FRIEND_ROW['userid']; ?>">
			<img id="friends_img" src="<?php echo ROOT . $image ?>">

	</div>
	<h3 class="profile-name">
		<?php echo $FRIEND_ROW['first_name'] . " " . $FRIEND_ROW['last_name'] ?>
	</h3>
	<p class="profession">
		<?= $member['role'] ?>
	</p>
	<p class="profession">
		<?php

		$online = "Last seen: <br> Unknown";
		if ($FRIEND_ROW['online'] > 0) {
			$online = $FRIEND_ROW['online'];

			$current_time = time();
			$threshold = 60 * 2; //2 minutes
		
			if (($current_time - $online) < $threshold) {
				$online = "<span style='color:green;'>Online</span>";
			} else {
				$online = "Last seen: <br>" . Time::get_time(date("Y-m-d H:i:s", $online));
			}
		}
		?>
		<?php echo $online ?>
	</p>
</div>