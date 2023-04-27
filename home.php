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


//posting starts here
if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$post = new Post();
	$id = $_SESSION['mybook_userid'];
	$result = $post->create_post($id, $_POST, $_FILES);

	if ($result == "") {
		header("Location:" . ROOT . "home");
		die;
	} else {


		echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
		echo "<br>The following errors occured:<br><br>";
		echo $result;
		echo "</div>";

	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Timeline | SkyLine</title>
</head>
<link rel="stylesheet" href="style.css">



<body>
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
			<!--cover area-->

			<!--posts area-->
			<div class="main-mid">


				<form class="creatPost" method="post" enctype="multipart/form-data">
					<div class="text-post">
						<input name="post" type="text" placeholder="whast's on your mind?" class="creatPost">
						<input type="file" name="file" value="Select a file">
					</div>

					<div>
						<input type="submit" value="post" class="btn btn-primary">

					</div>
				</form>
				<div class=" feeds">
					<?php

					$page_number = isset($_GET['page']) ? (int) $_GET['page'] : 1;
					$page_number = ($page_number < 1) ? 1 : $page_number;


					$limit = 10;
					$offset = ($page_number - 1) * $limit;

					$DB = new Database();
					$user_class = new User();
					$image_class = new Image();

					$followers = $user_class->get_following($_SESSION['mybook_userid'], "user");

					$follower_ids = false;
					if (is_array($followers)) {

						$follower_ids = array_column($followers, "userid");
						$follower_ids = implode("','", $follower_ids);

					}

					if ($follower_ids) {
						$myuserid = $_SESSION['mybook_userid'];
						$sql = "select * from posts where parent = 0 and owner = 0 and (userid = '$myuserid' || userid in('" . $follower_ids . "')) order by id desc limit $limit offset $offset";
						$posts = $DB->read($sql);
					}

					if (isset($posts) && $posts) {

						foreach ($posts as $ROW) {
							# code...
					
							$user = new User();
							$ROW_USER = $user->get_user($ROW['userid']);

							include("post.php");
						}
					}

					//get current url
					$pg = pagination_link();

					?>
					<a href="<?= $pg['next_page'] ?>">
						<input id="post_button" type="button" value="Next Page" style="float: right;width:150px;">
					</a>
					<a href="<?= $pg['prev_page'] ?>">
						<input id="post_button" type="button" value="Prev Page" style="float: left;width:150px;">
					</a>
				</div>
			</div>


		</div>

	</main>








</body>
<script>
	$('.input-file input[type=file]').on('change', function () {
		let file = this.files[0];
		$(this).next().html(file.name);
	});
</script>

</html>