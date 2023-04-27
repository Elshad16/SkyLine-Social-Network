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


$group_name = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


	$group = new Group();
	$result = $group->evaluate($_POST);

	if ($result != "") {

		echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
		echo "<br>The following errors occured:<br><br>";
		echo $result;
		echo "</div>";
	} else {

		header("Location:" . ROOT . "profile/" . $_SESSION['mybook_userid'] . "/groups");
		die;
	}


	$group_name = $_POST['group_name'];


}




?>

<html>

<head>

	<title>SkyLine | Create Group</title>
</head>
<link rel="stylesheet" href="style.css">
<style>
	:root {
		--color-white: hsl(252, 30%, 100%);
		--color-light: hsl(252, 30%, 95%);
		--color-gry: rgb(160, 151, 190);
		--color-primary: hsl(252, 75%, 60%);
		--color-success: hsl(120, 95%, 65%);
		--color-danger: hsl(0, 95%, 65%);
		--color-dark: hsl(252, 30%, 17%);
		--color-black: hsl(252, 30%, 10%);
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

	input {

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



	#bar2 {

		background-color: white;
		width: 800px;
		margin: auto;
		margin-top: 50px;
		padding: 10px;
		padding-top: 50px;
		text-align: center;
		font-weight: bold;

	}
</style>

<body style="font-family: tahoma;background-color: #e9ebee;">

	<?php include("header.php"); ?>

	<div id="bar2">

		Create Group<br><br>

		<form method="post" action="">

			<input value="<?php echo $group_name ?>" name="group_name" type="text" class='type-1' placeholder="Group Name"
				autofocus required><br><br>

			<select class='type-1' name="group_type">
				<option>Public</option>
				<option>Private</option>
			</select><br>
			<br>
			<input type="submit" id="post_button" value="Create">
			<br><br><br>

		</form>

	</div>

</body>


</html>