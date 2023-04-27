<?php

include("classes/autoload.php");

$login = new Login();
$_SESSION['mybook_userid'] = isset($_SESSION['mybook_userid']) ? $_SESSION['mybook_userid'] : 0;

$user_data = $login->check_login($_SESSION['mybook_userid'], false);

$USER = $user_data;

if (isset($URL[1]) && is_numeric($URL[1])) {

	$profile = new Profile();
	$profile_data = $profile->get_profile($URL[1]);

	if (is_array($profile_data)) {
		$user_data = $profile_data[0];
	}

}

//posting starts here
if ($_SERVER['REQUEST_METHOD'] == "POST") {

	include("change_image.php");

	if (isset($_POST['first_name'])) {

		$settings_class = new Settings();
		$settings_class->save_settings($_POST, $_SESSION['mybook_userid']);

	} elseif (isset($_POST['post'])) {

		$post = new Post();
		$id = $_SESSION['mybook_userid'];
		$result = $post->create_post($id, $_POST, $_FILES);

		if ($result == "") {
			header("Location: " . ROOT . "profile");
			die;
		} else {

			echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
			echo "<br>The following errors occured:<br><br>";
			echo $result;
			echo "</div>";
		}
	}

}

//collect posts
$post = new Post();
$id = $user_data['userid'];

$posts = $post->get_posts($id);

//collect friends
$user = new User();

$friends = $user->get_following($user_data['userid'], "user");

$image_class = new Image();

//check if this is from a notification
if (isset($URL[2])) {
	notification_seen($URL[2]);
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>Profile | SkyLine</title>
</head>
<style type="text/css">
	*,
	*::after,
	*::before {
		margin: 0;
		padding: 0;
		box-sizing: border-box;
		text-transform: capitalize;
		font-family: Verdana, Geneva, Tahoma, sans-serif;
		list-style: none;
		text-decoration: none;
		border: none;
		outline: none;
	}

	:root {
		--color-white: hsl(252, 30%, 100%);
		--color-light: hsl(252, 30%, 95%);
		--color-gry: rgb(160, 151, 190);
		--color-primary: hsl(252, 75%, 60%);
		--color-success: hsl(120, 95%, 65%);
		--color-danger: hsl(0, 95%, 65%);
		--color-dark: hsl(252, 30%, 17%);
		--color-black: hsl(252, 30%, 10%);


		--btn-padding: .6rem 2rem;
		--border-radius: 0;
		--card-border-radius: 0;
		--search-padding: .6rem 2rem;
		--card-padding: 1rem;

		--stk-top-left: 5.4rem;
		--stk-top-right: -18rem;
	}

	body {
		color: var(--color-dark);
		background: var(--color-light);
		overflow-x: hidden;
	}

	/* ............CUSTOM STYLE........ */

	.container {
		width: 80%;
		margin: 0 auto;
	}

	.profile-phots {
		width: 2.7rem;
		border-radius: 50%;
		aspect-ratio: 1/1;
		overflow: hidden;
	}

	img {
		width: 100%;
	}

	.icon1 {
		height: 1.4rem;
		width: 1.4rem;
	}

	.icon2 {
		height: 1.2rem;
		width: 1.2rem;
	}

	.btn {
		padding: var(--btn-padding);
		display: inline-block;
		font-weight: 500;
		font-size: .9rem;
		border-radius: var(--border-radius);
		cursor: pointer;
		transition: all .3s ease;
	}

	.btn:hover {
		opacity: .8;
	}

	.btn-primary {
		background: var(--color-primary);
		color: white;
	}

	.btn-danger {
		background: var(--color-danger);
		color: white;
	}

	.text-blod {
		font-weight: bold;
	}

	.text-gry {
		color: var(--color-gry);
	}

	/* ............................NAV START.................... */
	nav {
		position: fixed;
		width: 100%;
		padding: .7rem 0;
		background: var(--color-white);
		z-index: 10;
		top: 0;
	}

	.nav-container {
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	nav span {
		color: var(--color-primary);
	}

	nav .logo {
		color: var(--color-danger);
	}

	.search-bar {
		background: var(--color-light);
		border-radius: var(--border-radius);
		padding: var(--search-padding);
		display: flex;
		align-items: center;
		gap: 1rem;
	}

	.search-bar input[type="search"] {
		width: 30vw;
		margin-left: 1rem;
		font-size: .9rem;
		color: var(--color-dark);
		background: transparent;
		text-transform: lowercase;
	}

	.search-bar input[type="search"]::placeholder {
		color: var(--color-gry);
	}

	.create {
		display: flex;
		align-items: center;
		gap: 2rem;
	}

	/* ............................NAV END.................... */


	/* ............................START MAINE .................... */

	main {
		position: relative;
		top: 5.4rem;
	}

	.main-container {
		position: relative;
		display: grid;
		grid-template-columns: 18vw auto 20vw;
		gap: 2rem;
	}

	/*.... main left... */
	.main-container .main-left {
		position: sticky;
		height: max-content;
		top: var(--stk-top-left);
	}

	.main-left .profile {
		padding: var(--card-padding);
		background: var(--color-white);
		border-radius: var(--card-border-radius);
		display: flex;
		column-gap: 1rem;
		align-items: center;
		width: 100%;
	}

	.main-left p {
		text-transform: lowercase;
		font-size: .9rem;
	}

	/* +++++++++++  SIDEBAR  +++++++++++++++++ */
	.main-left .side-bar {
		background: var(--color-white);
		margin-top: 1rem;
		border-radius: var(--card-border-radius);
	}

	.menu-item {
		position: relative;
		display: flex;
		align-items: center;
		cursor: pointer;
		transition: .3s all ease;
		height: 4rem;
	}

	.menu-item:hover {
		background: var(--color-light);
	}

	.menu-item span img {
		margin-left: 2rem;
		position: relative;
	}

	.menu-item .count {
		background: var(--color-danger);
		padding: .1rem .4rem;
		font-size: .6rem;
		height: fit-content;
		color: white;
		border-radius: .8rem;
		position: absolute;
		top: .5rem;
		left: 2.8rem;
	}

	.menu-item h3 {
		font-size: 1rem;
		margin-left: 1.5rem;
	}

	.active h3 {
		color: var(--color-primary);
	}

	.active {
		background: var(--color-light);
	}

	.menu-item.active::before {
		content: '';
		display: block;
		position: absolute;
		height: 100%;
		width: .5rem;
		background: var(--color-primary);
	}

	.menu-item:first-child.active {
		border-top-left-radius: var(--card-border-radius);
		overflow: hidden;
	}

	.g {
		border-bottom-left-radius: var(--card-border-radius);
		overflow: hidden;
	}

	.side-bar .btn {
		margin-top: 1rem;
		width: 100%;
		text-align: center;
		padding: 1rem 0;
	}

	/* +++++++++++  SIDEBAR  +END++++++++++++++++ */

	/* ...........notification..start......... */
	.notification {
		position: absolute;
		background: var(--color-white);
		width: 30rem;
		border-radius: var(--card-border-radius);
		top: 0;
		left: 110%;
		padding: var(--card-padding);
		box-shadow: 0 0 2rem hsl(var(--color-primary)75% 60% 25%);
		z-index: 8;
		display: none;
	}

	.notification::before {
		content: '';
		height: 1.2rem;
		width: 1.2rem;
		background: var(--color-white);
		display: block;
		position: absolute;
		transform: rotate(45deg);
		left: -.6rem;
	}

	.notification .NP {
		display: flex;
		align-items: start;
		gap: 1rem;
		margin: 1rem;
	}

	.notification .NP small {
		display: block;
	}

	.creatPost {
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: space-between;

		border-radius: var(--border-radius);
		padding: .4rem var(--card-padding);
		background: var(--color-white);

	}

	.creatPost .text-post {
		background: transparent;
		justify-self: start;
		width: 100%;
		padding-left: 1rem;
		margin-right: 1rem;

	}

	.creatPost input[type="text"] {
		color: var(--color-dark);
	}

	/* ...........form..end......... */

	/* ...................feed start............ */
	.feeds .feed {
		background: var(--color-white);
		font-size: .8rem;
		line-height: 1.6;
		padding: var(--card-padding);
		border-radius: var(--card-border-radius);
		margin: 1rem 0;
	}

	.feeds .feed .head {
		display: flex;
		justify-content: space-between;
	}

	.feeds .feed .user {
		display: flex;
		gap: 1rem;
	}

	.feeds .feed-phots {
		overflow: hidden;
		border-radius: var(--card-border-radius);
		margin: .7rem 0;
	}

	.feeds .action-buttons {
		display: flex;
		justify-content: space-between;
		margin: .6rem 0;
		align-items: center;
	}

	.feeds .inter-action-button {
		display: flex;
		gap: 1rem;
		align-items: center;
	}

	.liked-by {
		display: flex;
		margin-top: .8rem;
	}

	.liked-by span {
		height: 1.4rem;
		width: 1.4rem;
		border: 2px solid var(--color-white);
		border-radius: 50%;
		display: block;
		overflow: hidden;
		margin-left: -.6rem;
	}

	.liked-by span:nth-child(1) {
		margin: 0;
	}

	.liked-by p {
		margin-left: .6rem;
	}

	.hash-teg {
		color: var(--color-primary);
	}

	/* ...................feed end............ */


	/* ..................maine right....................start......... */
	/* ...message... */
	.main-right {
		height: max-content;
		position: sticky;
		top: var(--stk-top-right);
		bottom: 0;
	}

	.main-right .messages {
		background: var(--color-white);
		border-radius: var(--card-border-radius);
		padding: var(--card-padding);
	}

	.main-right .message-heading {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 1rem;
	}

	.main-right .messages .search-bar {
		margin-bottom: 1rem;
		display: flex;
		overflow: hidden;
	}

	.main-right .messages .category {
		display: flex;
		justify-content: space-between;
		/* align-items: center; */
		margin-bottom: 1rem;
	}

	.main-right .messages .category h6 {
		font-size: .8rem;
		text-align: center;
		border-bottom: 4px solid var(--color-light);
		padding: .5rem;
		width: 100%;
	}

	.main-right .messages .category .active {
		border-color: var(--color-dark);
	}

	.main-right .message {
		display: inline-block;
		display: flex;
		margin-bottom: 1rem;
		gap: 1rem;
		align-items: start;
	}

	.main-right .message:last-child {
		margin: 0;
	}

	.main-right .message p {
		font-size: .8rem;
	}

	.ac {
		height: .9rem;
		width: .9rem;
		background: var(--color-success);
		position: absolute;
		border-radius: 50%;
		border: var(--color-white) 2px solid;
		bottom: 0;
		right: 0;

	}

	.message .profile-phots {
		position: relative;
		overflow: visible;
	}

	.message .profile-phots img {
		border-radius: 50%;
		height: 100%;
		width: 100%;
		border: 2px solid var(--color-primary);
	}

	.pr-requst {
		color: var(--color-primary);
	}

	/* firend requste */
	.firend-requests {
		margin-top: 1rem;
	}

	.firend-requests h4 {
		color: var(--color-gry);
		margin: .7rem 0;
	}

	.firend-requests .request {
		background: var(--color-white);
		padding: var(--card-padding);
		border-radius: var(--card-border-radius);
		margin-top: 1rem;
	}

	.firend-requests .info {
		display: flex;
		gap: 1rem;
		margin-bottom: 1rem;
	}

	.firend-requests .info .request-body h5 {
		color: var(--color-dark);
	}

	.firend-requests .info .action {
		display: flex;
		gap: 1rem;
	}

	/* ..................maine right....................end......... */

	/* ............................MAINE END.................... */


	/*......................THEME CUSTOMIZE..START................... */
	.theme {
		background: rgba(0, 0, 0, 0.508);
		height: 100vh;
		width: 100%;
		position: fixed;
		top: 0;
		left: 0;
		transform: translate(0, 0);
		text-align: center;
		display: grid;
		place-items: center;
		z-index: 100;
		font-size: .9rem;
		display: none;
	}

	.card {
		background: var(--color-white);
		padding: var(--card-padding);
		border-radius: var(--card-border-radius);
		width: 50%;
	}

	.font-siz {
		margin-top: 3rem;
	}

	.font-siz>div {
		display: flex;
		justify-content: space-between;
		background: var(--color-light);
		border-radius: var(--card-border-radius);
		padding: var(--search-padding);
		align-items: center;
	}

	.choose-font-size {
		width: 100%;
		height: .2rem;
		background: var(--color-dark);
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin: auto .5rem;
	}

	.choose-font-size span {
		height: 1rem;
		width: 1rem;
		background: var(--color-danger);
		cursor: pointer;
		border-radius: 50%;
	}

	.choose-font-size .active {
		background: var(--color-primary);
	}

	.choose-font-size .font1 {
		height: .9rem;
		width: .9rem;
	}

	.choose-font-size .font2 {
		height: 1rem;
		width: 1rem;
	}

	.choose-font-size .font3 {
		height: 1.2rem;
		width: 1.2rem;
	}

	.choose-font-size .font4 {
		height: 1.3rem;
		width: 1.3rem;
	}

	.choose-font-size .font5 {
		height: 1.5rem;
		width: 1.5rem;
	}

	/* ..............color.......... */
	.color {
		margin-top: 2rem;
	}

	.choose-color {
		display: flex;
		align-items: center;
		justify-content: space-around;
		padding: var(--search-padding);
		border-radius: var(--card-border-radius);
		background: var(--color-light);
		margin: 1rem auto;
	}

	.choose-color span {
		height: 2.5rem;
		width: 2.5rem;
		border-radius: 50%;
	}

	.choose-color span:nth-child(1) {
		background: hsl(0, 95%, 65%);
	}

	.choose-color span:nth-child(2) {
		background: hsl(120, 95%, 65%);
	}

	.choose-color span:nth-child(3) {
		background: hsl(252, 75%, 60%);
	}

	.choose-color span:nth-child(4) {
		background: hsl(252, 30%, 17%);
	}

	.choose-color span:nth-child(5) {
		background: hsl(252, 30%, 10%);
	}

	.choose-color .active {
		border: 5px solid var(--color-gry);
	}

	/* ................backgournd............ */
	.background {
		margin-top: 2rem;
	}

	.chosse-backgorund {
		display: flex;
		justify-content: space-between;
		width: 60%;
		margin: 1rem auto;
		padding: var(--search-padding);
		border-radius: var(--card-border-radius);
		align-items: center;
	}

	.chosse-backgorund>div {
		height: 4rem;
		width: 6rem;
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: var(--card-border-radius);
		color: var(--color-white);
		gap: .5rem;
	}

	.chosse-backgorund>div span {
		height: 1.5rem;
		width: 1.5rem;
		border-radius: 50%;
		border: 2px solid var(--color-gry);
	}

	.chosse-backgorund .active {
		border: 3px solid var(--color-primary);
	}

	.chosse-backgorund>div:nth-child(1) {
		background: hsl(252, 30%, 95%);
		color: var(--color-black);
	}

	.chosse-backgorund>div:nth-child(2) {
		background: hsl(252, 30%, 17%);
	}

	.chosse-backgorund>div:nth-child(3) {
		background: hsl(0, 0%, 0%);
	}

	#profile_pic {

		width: 150px;
		margin-top: -300px;
		border-radius: 50%;
		border: solid 2px white;
	}

	#menu_buttons {

		width: 100px;
		display: inline-block;
		margin: 2px;
	}

	#post_button {
		background: var(--color-primary);
		color: #fff;
		border: none;
		padding: 10px 25px;
		border-radius: 4px;
		cursor: pointer;
		margin-top: 20px;
	}

	#post_button:hover {
		opacity: 0.8;
	}

	#about {
		justify-content: space-between;
		position: relative;
	}

	#about li {
		display: flex;
		flex-direction: column;
		color: #818181;
		font-size: 0.9rem;
	}

	#about li span {
		color: #1d1d1d;
		font-weight: 600;
	}

	#about:after {
		position: absolute;
		content: "";
		bottom: -16px;
		display: block;
		background: #cccccc;
		height: 1px;
		width: 100%;
	}

	/*......................THEME CUSTOMIZE..END................... */





	/*............................................ MEDA QUERY..START......................................... */
	/*............................................ MEDA QUERY..START......................................... */

	/* LEPTOP */
	@media (max-width:1220px) {
		.container {
			width: 96%;
		}

		.main-container {
			grid-template-columns: 5rem auto 30vw;
			gap: 1rem;
		}

		.main-left {
			width: 5rem;
			z-index: 5;
		}

		.main-left .profile {
			display: none;
		}

		.main-left .menu-item h3 {
			display: none;
		}

		.side-bar label {
			display: none;
		}

		.card {
			width: 80%;
		}

	}

	/* TABLET  and PHONE*/
	@media (max-width:991px) {
		.container {
			gap: 0;
		}

		nav .search-bar {
			display: none;
		}

		.main-container {
			grid-template-columns: 0 auto 5rem;
			gap: 1rem;
		}

		.main-left {
			grid-column: 3/4;
			position: fixed;
			bottom: 0;
			right: 0;
		}

		/* notifiation */
		.notification {
			position: absolute;
			left: -21rem;
			width: 20rem;
		}

		.notification::before {
			display: none;
		}

		/* mid */
		.main-mid {
			grid-column: 1/3;
			margin-top: -34rem;
		}

		.main-right {
			display: none;
		}

		.chosse-backgorund {
			width: 80%;
		}

		.card {
			width: 90%;
		}
	}

	@media (max-width:573px) {
		.storys {
			gap: .4rem;
		}
	}

	/*............................................ MEDA QUERY..END......................................... */
	/*............................................ MEDA QUERY..END......................................... */



	/* ...........EXTRA JS CLASS............ */
	/* MESSAGE CLASS */
	.box-sh {
		box-shadow: 0 0 1rem var(--color-primary);
	}
</style>


<body style="background-color: #d0d8e4;">

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

				<div id="change_profile_image"
					style=" display:none;position:absolute; width: 100%;height: 100%;background-color: #000000aa;">
					<div style="max-width:200px;margin:auto;min-height: 400px;flex:2.5;padding: 20px;padding-right: 0px;">

						<form method="post" action="<?= ROOT ?>profile/profile" enctype="multipart/form-data">
							<div style="border:solid thin #aaa; padding: 10px;background-color: white;">

								<input type="file" name="file"><br>
								<input id="post_button" type="submit" style="width:120px;" value="Change">
								<br>
								<div style="text-align: center;">
									<br><br>
									<?php

									echo "<img src='" . ROOT . "$user_data[profile_image]' style='max-width:500px;' >";

									?>
								</div>
							</div>
						</form>

					</div>
				</div>

				<!--change cover image area-->
				<div id="change_cover_image"
					style="display:none;position:absolute;width: 100%;height: 100%;background-color: #000000aa;">
					<div style="max-width:600px;margin:auto;min-height: 400px;flex:2.5;padding: 20px;padding-right: 0px;">

						<form method="post" action="<?= ROOT ?>profile/cover" enctype="multipart/form-data">
							<div style="border:solid thin #aaa; padding: 10px;background-color: white;">

								<input type="file" name="file"><br>
								<input id="post_button" type="submit" style="width:120px;" value="Change">
								<br>
								<div style="text-align: center;">
									<br><br>
									<?php

									echo "<img src='" . ROOT . "$user_data[cover_image]' style='max-width:500px;' >";

									?>
								</div>
							</div>
						</form>

					</div>
				</div>

				<!--cover area-->
				<div style="width: 600px;margin:auto;min-height: 400px;">

					<div style="background-color: white;text-align: center;color: #405d9b">

						<?php

						$image = "images/cover_image.jpg";
						if (file_exists($user_data['cover_image'])) {
							$image = $image_class->get_thumb_cover($user_data['cover_image']);
						}
						?>

						<img src="<?php echo ROOT . $image ?>" style="width:100%;">


						<span style="font-size: 12px;">
							<?php

							$image = "images/user_male.jpg";
							if ($user_data['gender'] == "Female") {
								$image = "images/user_female.jpg";
							}
							if (file_exists($user_data['profile_image'])) {
								$image = $image_class->get_thumb_profile($user_data['profile_image']);
							}
							?>

							<img id="profile_pic" src="<?php echo ROOT . $image ?>"><br />

							<?php if (i_own_content($user_data)): ?>

								<a onclick="show_change_profile_image(event)" style="text-decoration: none;color:#f0f;"
									href="<?= ROOT ?>change_profile_image/profile">Change Profile Image</a> |
								<a onclick="show_change_cover_image(event)" style="text-decoration: none;color:#f0f;"
									href="<?= ROOT ?>change_profile_image/cover">Change Cover</a>

							<?php endif; ?>

						</span>

						<div style="font-size: 20px;color: black;">
							<a href="<?= ROOT ?>profile/<?php echo $user_data['userid'] ?>">
								<?php echo $user_data['first_name'] . " " . $user_data['last_name'] ?>
								<br><span style="font-size:12px;">@
									<?= $user_data['tag_name'] ?>
								</span>
							</a>

							<?php
							$mylikes = "";
							if ($user_data['likes'] > 0) {

								$mylikes = "" . $user_data['likes'] . " ";
							}
							?>
							<ul id="about">
								<li><span>
										<?php echo $mylikes ?>
									</span>Followers</li>

							</ul>
							<a href="<?= ROOT ?>like/user/<?php echo $user_data['userid'] ?>">
								<button id="post_button">Follow</button>

							</a>


							<?php if ($user_data['userid'] == $_SESSION['mybook_userid']): ?>
								<a href="<?= ROOT ?>messages">
									<input id="post_button" type="button" value="Messages"
										style="margin-right:10px;background-color: #1b9186;width:auto;">
								</a>
							<?php else: ?>
								<a href="<?= ROOT ?>messages/new/<?= $user_data['userid'] ?>">
									<input id="post_button" type="button" value="Message"
										style="margin-right:10px;background-color: #1b9186;width:auto;">
								</a>
							<?php endif; ?>


						</div>
						<br>
						<br>








					</div>

					<!--below cover area-->

					<?php

					$section = "default";

					if (isset($URL[2])) {

						$section = $URL[2];
					}

					if ($section == "default") {

						include("profile_content_default.php");

					} elseif ($section == "following") {

						include("profile_content_following.php");

					} elseif ($section == "followers") {

						include("profile_content_followers.php");

					} elseif ($section == "about") {

						include("profile_content_about.php");

					} elseif ($section == "settings") {

						include("profile_content_settings.php");

					} elseif ($section == "photos") {

						include("profile_content_photos.php");
					} elseif ($section == "groups") {

						include("profile_content_groups.php");
					}



					?>

				</div>
			</div>
			<div class="main-right">
				<div class="messages" id="message-box">
					<div class="message-heading">
						<h4>following</h4>
					</div>
				</div>
				<?php

				if ($friends) {

					foreach ($friends as $friend) {
						# code...
				
						$FRIEND_ROW = $user->get_user($friend['userid']);
						include("user.php");
					}
				}


				?>

				<!--change profile image area-->
			</div>
	</main>

</body>

</html>

<script type="text/javascript">

	function show_change_profile_image(event) {

		event.preventDefault();
		var profile_image = document.getElementById("change_profile_image");
		profile_image.style.display = "block";
	}


	function hide_change_profile_image() {

		var profile_image = document.getElementById("change_profile_image");
		profile_image.style.display = "none";
	}


	function show_change_cover_image(event) {

		event.preventDefault();
		var cover_image = document.getElementById("change_cover_image");
		cover_image.style.display = "block";
	}


	function hide_change_cover_image() {

		var cover_image = document.getElementById("change_cover_image");
		cover_image.style.display = "none";
	}


	window.onkeydown = function (key) {

		if (key.keyCode == 27) {

			//esc key was pressed
			hide_change_profile_image();
			hide_change_cover_image();
		}
	}


</script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>