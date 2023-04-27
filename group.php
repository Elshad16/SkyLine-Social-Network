<?php

include("classes/autoload.php");

$login = new Login();
$_SESSION['mybook_userid'] = isset($_SESSION['mybook_userid']) ? $_SESSION['mybook_userid'] : 0;

$USER = $login->check_login($_SESSION['mybook_userid'], false);

$group_data = array();

if (isset($URL[1]) && is_numeric($URL[1])) {

	$group = new Group();
	$g_data = $group->get_group($URL[1]);

	if (is_array($g_data)) {
		$group_data = $g_data[0];
	}

}

//posting starts here
if ($_SERVER['REQUEST_METHOD'] == "POST") {

	include("change_image.php");

	if (isset($_POST['first_name'])) {

		if (group_access($_SESSION['mybook_userid'], $group_data, 'admin')) {
			$settings_class = new Settings();
			$settings_class->save_settings($_POST, $group_data['userid']);
		}

		header("Location: " . ROOT . "group/" . $group_data['userid'] . "/settings");
		die;

	} elseif (isset($_POST['post'])) {

		$post = new Post();
		$id = $_SESSION['mybook_userid'];
		$owner = $group_data['userid'];
		$result = $post->create_post($id, $_POST, $_FILES, $owner);

		if ($result == "") {

			header("Location: " . ROOT . "group/" . $group_data['userid']);
			die;
		} else {

			echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
			echo "<br>The following errors occured:<br><br>";
			echo $result;
			echo "</div>";
		}
	}

}

if (count($group_data) > 0) {

	//collect posts
	$post = new Post();
	$id = $group_data['userid'];

	$posts = $post->get_posts($id, 'group');

	//collect friends
	$user = new User();

	$friends = $user->get_following($group_data['userid'], "user");

	$image_class = new Image();

	//check if this is from a notification
	if (isset($URL[3])) {
		notification_seen($URL[3]);
	}

}



?>

<!DOCTYPE html>
<html>

<head>
	<title>Group | SkyLine</title>
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
		background: #d0d8e4;
		overflow-x: hidden;
	}

	/* ............CUSTOM STYLE........ */
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

	/* ...........notification..end......... */


	/* ...........mid..end......... */


	/* ...........form..start......... */
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

<body background-color: #d0d8e4;">

	<br>
	<?php include("header.php"); ?>

	<?php if (count($group_data) > 0): ?>

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
						<a class="menu-item" href="<?= ROOT ?>group/<?php echo $group_data['userid'] ?>" class="menu-item ">
							<span><img src="<?= ROOT ?>icon/message.png" class="icon1"></span>
							<h3>discussion</h3>

							<a href="<?= ROOT ?>group/<?php echo $group_data['userid'] ?>/about" class="menu-item ">
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
							<a href="<?= ROOT ?>group/<?php echo $group_data['userid'] ?>/members" class="menu-item ">
								<span><img src="<?= ROOT ?>icon/people.png" class="icon1"></span>
								<h3>members
									<?= $members_str ?>
								</h3>
							</a>
							<a href="<?= ROOT ?>group/<?php echo $group_data['userid'] ?>/photos" class="menu-item ">
								<span><img src="<?= ROOT ?>icon/gallery.png" class="icon1"></span>
								<h3>photos</h3>
							</a>
							<?php if (group_access($_SESSION['mybook_userid'], $group_data, 'moderator')): ?>




								<a href="<?= ROOT ?>group/<?php echo $group_data['userid'] ?>/requests" class="menu-item ">
									<span><small class="count">
											<?php
											$requests_count = $group->get_requests($group_data['userid']);
											$requests_str = "";
											if (is_array($requests_count)) {
												$requests_str = "" . count($requests_count) . "";
											}
											?>

											<?= $requests_str ?>
										</small>
										<img src="<?= ROOT ?>icon/add-friend.png" class="icon1"></span>
									<h3>requests

									</h3>
								</a>
							<?php endif; ?>
							<?php
							if (group_access($_SESSION['mybook_userid'], $group_data, 'admin')) {

								echo '<a href="' . ROOT . 'group/' . $group_data['userid'] . '/settings"class="menu-item"><span><img src="' . ROOT . 'icon/gear.svg" class="icon1"></span><h3>setings</h3></a>';
							}
							?>



					</div>

				</div>

				<div class="main-mid">

					<div id="change_cover_image"
						style="display:none;position:absolute;width: 100%;height: 100%;background-color: #000000aa;">
						<div style="max-width:600px;margin:auto;min-height: 400px;flex:2.5;padding: 20px;padding-right: 0px;">

							<form method="post" action="<?= ROOT ?>group/<?= $group_data['userid'] ?>/cover"
								enctype="multipart/form-data">
								<div style="border:solid thin #aaa; padding: 10px;background-color: white;">

									<input type="file" name="file"><br>
									<input id="post_button" type="submit" style="width:120px;" value="Change">
									<br>
									<div style="text-align: center;">
										<br><br>
										<?php

										echo "<img src='" . ROOT . "$group_data[cover_image]' >";

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
							if (file_exists($group_data['cover_image'])) {
								$image = $image_class->get_thumb_cover($group_data['cover_image']);
							}
							?>

							<img src="<?php echo ROOT . $image ?>" style="width:100%;">


							<span style="font-size: 12px;">

								<?php if (i_own_content($group_data)): ?>

									<a onclick="show_change_cover_image(event)" style="text-decoration: none;color:#f0f;"
										href="<?= ROOT ?>change_profile_image/cover">Change Cover</a>

								<?php endif; ?>

							</span>
							<br>
							<div style="font-size: 20px;color: black;">
								<a href="<?= ROOT ?>group/<?php echo $group_data['userid'] ?>">
									<?php echo $group_data['first_name'] ?><br>
									<span style="font-size: 12px;">[
										<?php echo $group_data['group_type'] ?> Group]
									</span>
								</a>

								<br>
								<?php if (!group_access($_SESSION['mybook_userid'], $group_data, 'member')): ?>
									<?php if (!group_access($_SESSION['mybook_userid'], $group_data, 'request')): ?>

										<a href="<?= ROOT ?>join/<?= $group_data['userid'] ?>">
											<input id="post_button" type="button" value="Join Group"
												style="margin-right:10px;background-color: #821b91;width:auto;">
										</a>
									<?php else: ?>

										<input id="post_button" type="button" value="Request sent"
											style="margin-right:10px;background-color: #821b91;width:auto;">
									<?php endif; ?>
								<?php endif; ?>

								<?php if (group_access($_SESSION['mybook_userid'], $group_data, 'member')): ?>
									<a href="<?= ROOT ?>group/<?php echo $group_data['userid'] ?>/invite">
										<input id="post_button" type="button" value="Invite"
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

						if ($group_data['group_type'] == 'private' && !group_access($_SESSION['mybook_userid'], $group_data, 'member')) {
							$section = "denied";
						}

						if ($section == "default" || $section == "cover") {

							include("group_content_default.php");

						} elseif ($section == "requests") {

							include("group_content_requests.php");
						} elseif ($section == "invite") {

							include("group_content_invite.php");
						} elseif ($section == "invited") {

							include("group_content_invited.php");

						} elseif ($section == "members") {

							include("group_content_members.php");

						} elseif ($section == "about") {

							include("group_content_about.php");

						} elseif ($section == "settings") {

							include("group_content_settings.php");

						} elseif ($section == "photos") {

							include("group_content_photos.php");
						} elseif ($section == "groups") {

							include("group_content_groups.php");
						} elseif ($section == "denied") {

							include("group_content_denied.php");
						}



						?>
					<?php else: ?>

						<div style="background-color: grey;color: white;padding: 1em;text-align: center;margin:1em;">That group
							was
							not
							found!
							<br><br>
							<a href="<?= ROOT ?>profile/<?= $_SESSION['mybook_userid'] ?>/groups">
								Go to groups
							</a>
						</div>

					<?php endif; ?>
				</div>
			</div>

		</div>
	</main>

	</div>
	</main>


	<!--change cover image area-->



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