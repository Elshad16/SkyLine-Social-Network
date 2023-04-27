<!--top bar-->
<?php

$corner_image = "images/user_male.jpg";
if (isset($USER)) {

	if (file_exists($USER['profile_image'])) {
		$image_class = new Image();
		$corner_image = $image_class->get_thumb_profile($USER['profile_image']);
	} else {

		if ($USER['gender'] == "Female") {

			$corner_image = "images/user_female.jpg";
		}
	}
}
?>
<link rel="stylesheet" href="style.css">
<nav>
	<form method="get" action="<?= ROOT ?>search">

		<div class="container nav-container">
			<h2 class="logo">
				<a href="<?= ROOT ?>home">Sky<span>Line</span></a>
			</h2>
			<div class="search-bar">
				<img src="<?= ROOT ?>icon/search.svg" class="icon2">
				<input type="text" name="find" placeholder="Search for people" />

			</div>
			<div class="create">
				<a href="<?= ROOT ?>logout"><label for="creatPost" class="btn btn-primary">Logout</label></a>
				<?php if (isset($USER)): ?>
				<?php else: ?>
				</div>
			<?php endif; ?>
			<div class="profile-phots">
				<?php if (isset($USER)) ?>
				<a href="<?= ROOT ?>profile">
					<img src="<?php echo ROOT . $corner_image ?>">
				</a>
			</div>

		</div>
	</form>
</nav>