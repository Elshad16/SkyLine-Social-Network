<?php

include("classes/autoload.php");

$login = new Login();
$user_data = $login->check_login($_SESSION['mybook_userid']);

$USER = $user_data;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {

	$profile = new Profile();
	$profile_data = $profile->get_profile($_GET['id']);

	if (is_array($profile_data)) {
		$user_data = $profile_data[0];
	}

}

$Post = new Post();
$User = new User();
$image_class = new Image();

?>

<!DOCTYPE html>
<html>

<head>
	<title>Notifications | SkyLine</title>
</head>

<style type="text/css">
	#blue_bar {

		height: 50px;
		background-color: #405d9b;
		color: #d9dfeb;

	}

	#search_box {

		width: 400px;
		height: 20px;
		border-radius: 5px;
		border: none;
		padding: 4px;
		font-size: 14px;
		background-image: url(search.png);
		background-repeat: no-repeat;
		background-position: right;

	}

	#profile_pic {

		width: 150px;
		border-radius: 50%;
		border: solid 2px white;
	}

	#menu_buttons {

		width: 100px;
		display: inline-block;
		margin: 2px;
	}

	#friends_img {

		width: 75px;
		float: left;
		margin: 8px;

	}

	#friends_bar {

		min-height: 400px;
		margin-top: 20px;
		padding: 8px;
		text-align: center;
		font-size: 20px;
		color: #405d9b;
	}

	#friends {

		clear: both;
		font-size: 12px;
		font-weight: bold;
		color: #405d9b;
	}

	textarea {

		width: 100%;
		border: none;
		font-family: tahoma;
		font-size: 14px;
		height: 60px;

	}

	#post_button {

		float: right;
		background-color: #405d9b;
		border: none;
		color: white;
		padding: 4px;
		font-size: 14px;
		border-radius: 2px;
		width: 50px;
	}

	#post_bar {

		margin-top: 20px;
		background-color: white;
		padding: 10px;
	}

	#post {

		padding: 4px;
		font-size: 13px;
		display: flex;
		margin-bottom: 20px;
	}

	#notification {

		height: 40px;
		background-color: #eee;
		color: #666;
		border: 1px solid #aaa;
		margin: 6px;

	}
</style>
<link rel="stylesheet" href="style.css">

<body style="font-family: tahoma; background-color: #d0d8e4;">

	<br>
	<?php include("header.php"); ?>
	<main>
		<div class="container main-container">
			<!--=======MAIN LEFT======== -->
			<div class="main-left">


				<div class="profile">
					<div class="profile-phots">
						<?php if (isset($USER)) ?>
						<a href="<?= ROOT ?>profile">
							<img src="<?php echo ROOT . $corner_image ?>">
						</a>
					</div>
					<div class="hendel">
						<h4><a href="<?= ROOT ?>profile/<?php echo $user_data['userid'] ?>">
								<?php echo $user_data['first_name'] . " " . $user_data['last_name'] ?></h4>
						<p class="text-gry">@
							<?= $user_data['tag_name'] ?>
						</p>
					</div>
				</div>
				<div class="side-bar">

					<a class="menu-item active" href="<?= ROOT ?>home">
						<span><img src="<?= ROOT ?>icon/house-door.svg" class="icon1"></span>
						<h3>home</h3>
					</a>


					<a href="<?= ROOT ?>profile/<?php echo $user_data['userid'] ?>/about" class="menu-item ">
						<span><img src="<?= ROOT ?>icon/info.png" class="icon1"></span>
						<h3>about</h3>
					</a>
					<a href="<?= ROOT ?>notifications" class="menu-item" id="notifice">
						<span>
							<small class="count">


								<?php
								$notif = check_notifications();
								?>
							</small> <img src="<?= ROOT ?>icon/bell.svg" class="icon1">
						</span>
						<?php if ($notif > 0): ?>

							<?= $notif ?>
						<?php endif; ?>
						<h3>notifications</h3>
						<!-- ....................notification............................ -->
					</a>
					<a href="<?= ROOT ?>messages" class="menu-item" id="message">
						<span> <small class="count">

								<?php
								$notif = check_messages();
								?>

							</small> <img src="<?= ROOT ?>icon/chat-left-dots.svg" class="icon1"></span>
						<?php if ($notif > 0): ?>
							<?= $notif ?>
						<?php endif; ?>
						<h3>message</h3>
					</a>
					<a href="<?= ROOT ?>profile/<?php echo $user_data['userid'] ?>/followers" class="menu-item ">
						<span><img src="<?= ROOT ?>icon/add-friend.png" class="icon1"></span>
						<h3>followers</h3>
					</a>

					<a href="<?= ROOT ?>profile/<?php echo $user_data['userid'] ?>/following" class="menu-item ">
						<span><img src="<?= ROOT ?>icon/followers.png" class="icon1"></span>
						<h3>following</h3>
					</a>
					<a href="<?= ROOT ?>profile/<?php echo $user_data['userid'] ?>/photos" class="menu-item ">
						<span><img src="<?= ROOT ?>icon/gallery.png" class="icon1"></span>
						<h3>photos</h3>
					</a>


					<?php
					if ($user_data['userid'] == $_SESSION['mybook_userid']) {

						echo '<a href="' . ROOT . 'profile/' . $user_data['userid'] . '/groups" class="menu-item" ><span><img src="' . ROOT . 'icon/people.png" class="icon1"></span><h3>groups</h3></a>';
						echo '<a href="' . ROOT . 'profile/' . $user_data['userid'] . '/settings"class="menu-item g"><span><img src="' . ROOT . 'icon/gear.svg" class="icon1"></span><h3>setings</h3></a>';
					}
					?>


				</div>
			</div>

			<div class="main-mid">
				<div style="width: 600px;margin:auto;min-height: 400px;">
					<!--below cover area-->
					<div style="display: flex;">

						<!--posts area-->
						<div style="min-height: 400px;flex:2.5;padding: 20px;padding-right: 0px;">

							<div style="border:solid thin #aaa; padding: 10px;background-color: white;">

								<?php

								$DB = new Database();
								$id = esc($_SESSION['mybook_userid']);
								$follow = array();

								//check content i follow
								$sql = "select * from content_i_follow where disabled = 0 && userid = '$id' limit 100";
								$i_follow = $DB->read($sql);
								if (is_array($i_follow)) {
									$follow = array_column($i_follow, "contentid");
								}

								if (count($follow) > 0) {

									$str = "'" . implode("','", $follow) . "'";
									$query = "select * from notifications where (userid != '$id' && content_owner = '$id') || (contentid in ($str)) order by id desc limit 30";
								} else {

									$query = "select * from notifications where userid != '$id' && content_owner = '$id' order by id desc limit 30";
								}

								$data = $DB->read($query);
								?>

								<?php if (is_array($data)): ?>

									<?php foreach ($data as $notif_row):

										include("single_notification.php");
									endforeach; ?>
								<?php else: ?>
									No notifications were found
								<?php endif; ?>

							</div>


						</div>
					</div>

				</div>
			</div>
		</div>
	</main>
	<!--cover area-->




</body>

</html>