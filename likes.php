<?php

include("classes/autoload.php");

$login = new Login();
$user_data = $login->check_login($_SESSION['mybook_userid']);

$USER = $user_data;

if (isset($URL[2]) && is_numeric($URL[2])) {

	$profile = new Profile();
	$profile_data = $profile->get_profile($URL[2]);

	if (is_array($profile_data)) {
		$user_data = $profile_data[0];
	}

}


$Post = new Post();
$likes = false;

$ERROR = "";
if (isset($URL[2]) && isset($URL[1])) {

	$likes = $Post->get_likes($URL[2], $URL[1]);
} else {

	$ERROR = "No information post was found!";
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>People who like | Mybook</title>
</head>

<style type="text/css">
	ul {
		list-style-type: none;
		margin: 0;
		padding: 0;
		align-items: center;
	}

	a {
		text-decoration: none;
	}

	.header__wrapper header {
		width: 100%;
		background: url("https://proprikol.ru/wp-content/uploads/2019/07/prikolnye-kartinki-na-avu-59.jpg") no-repeat 50% 20% / cover;
		min-height: calc(100px + 15vw);
	}

	.header__wrapper .cols__container .left__col {
		padding: 25px 20px;
		text-align: center;
		max-width: 350px;
		position: relative;
		margin: 0 auto;
	}

	.header__wrapper .cols__container .left__col .img__container {
		position: absolute;
		top: -60px;
		left: 50%;
		transform: translatex(-50%);
	}

	.header__wrapper .cols__container .left__col .img__container img {
		width: 120px;
		height: 120px;
		object-fit: cover;
		border-radius: 50%;
		display: block;
		box-shadow: 1px 3px 12px rgba(0, 0, 0, 0.18);
	}

	.header__wrapper .cols__container .left__col .img__container span {
		position: absolute;
		background: #2afa6a;
		width: 16px;
		height: 16px;
		border-radius: 50%;
		bottom: 3px;
		right: 11px;
		border: 2px solid #fff;
	}

	.header__wrapper .cols__container .left__col h2 {
		margin-top: 60px;
		font-weight: 600;
		font-size: 22px;
		margin-bottom: 5px;
	}

	.header__wrapper .cols__container .left__col p {
		font-size: 0.9rem;
		color: #818181;
		margin: 0;
	}

	.header__wrapper .cols__container .left__col .about {
		justify-content: space-between;
		position: relative;
		margin: 35px 0;
	}

	.header__wrapper .cols__container .left__col .about li {
		display: flex;
		flex-direction: column;
		color: #818181;
		font-size: 0.9rem;
	}

	.header__wrapper .cols__container .left__col .about li span {
		color: #1d1d1d;
		font-weight: 600;
	}

	.header__wrapper .cols__container .left__col .about:after {
		position: absolute;
		content: "";
		bottom: -16px;
		display: block;
		background: #cccccc;
		height: 1px;
		width: 100%;
	}

	.header__wrapper .cols__container .content p {
		font-size: 1rem;
		color: #1d1d1d;
		line-height: 1.8em;
	}

	.header__wrapper .cols__container .content ul {
		gap: 30px;
		justify-content: center;
		align-items: center;
		margin-top: 25px;
	}

	.header__wrapper .cols__container .content ul li {
		display: flex;
	}

	.header__wrapper .cols__container .content ul i {
		font-size: 1.3rem;
	}

	.header__wrapper .cols__container .right__col nav {
		display: flex;
		align-items: center;
		padding: 30px 0;
		justify-content: space-between;
		flex-direction: column;
	}

	.header__wrapper .cols__container .right__col nav ul {
		display: flex;
		gap: 20px;
		flex-direction: column;
	}

	.header__wrapper .cols__container .right__col nav ul li a {
		text-transform: uppercase;
		color: #818181;
	}

	.header__wrapper .cols__container .right__col nav ul li:nth-child(1) a {
		color: #1d1d1d;
		font-weight: 600;
	}

	.header__wrapper .cols__container .right__col nav button {
		background: #0091ff;
		color: #fff;
		border: none;
		padding: 10px 25px;
		border-radius: 4px;
		cursor: pointer;
		margin-top: 20px;
	}

	.header__wrapper .cols__container .right__col nav button:hover {
		opacity: 0.8;
	}

	.header__wrapper .cols__container .right__col .photos {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
		gap: 20px;
	}

	.header__wrapper .cols__container .right__col .photos img {
		max-width: 100%;
		display: block;
		height: 100%;
		object-fit: cover;
	}

	/* Responsiveness */

	@media (min-width: 868px) {
		.header__wrapper .cols__container {
			max-width: 1200px;
			margin: 0 auto;
			width: 90%;
			justify-content: space-between;
			display: grid;
			grid-template-columns: 1fr 2fr;
			gap: 50px;
		}

		.header__wrapper .cols__container .left__col {
			padding: 25px 0px;
		}

		.header__wrapper .cols__container .right__col nav ul {
			flex-direction: row;
			gap: 30px;
		}

		.header__wrapper .cols__container .right__col .photos {
			height: 365px;
			overflow: auto;
			padding: 0 0 30px;
		}
	}

	@media (min-width: 1017px) {
		.header__wrapper .cols__container .left__col {
			margin: 0;
			margin-right: auto;
		}

		.header__wrapper .cols__container .right__col nav {
			flex-direction: row;
		}

		.header__wrapper .cols__container .right__col nav button {
			margin-top: 0;
		}
	}
</style>
<link rel="stylesheet" href="style.css">

<body style="font-family: tahoma; background-color: #d0d8e4;">

	<br>
	<?php include("header.php"); ?>

	<!--cover area-->
	<div style="width: 800px;margin:auto;min-height: 400px;">

		<!--below cover area-->
		<div style="display: flex;">

			<!--posts area-->
			<div style="min-height: 400px;flex:2.5;padding: 20px;padding-right: 0px;">

				<div style="border:solid thin #aaa; padding: 10px;background-color: white;">

					<?php

					$User = new User();
					$image_class = new Image();

					if (is_array($likes)) {

						foreach ($likes as $row) {
							# code...
							$FRIEND_ROW = $User->get_user($row['userid']);
							include("user.php");
						}
					}

					?>

					<br style="clear: both;">
				</div>


			</div>
		</div>

	</div>

</body>

</html>