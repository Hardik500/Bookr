<!DOCTYPE html>
<?php
	session_start();
	error_reporting(E_ERROR | E_PARSE);
	$myAPIfile = fopen("api_keys.txt", "r") or die("API file not found! Please generate apis from MovieDB and use it");
	$myAPI = fgets($myAPIfile);
	fclose($myAPIfile);
 ?>
<html>
	<head>
		<title>Index Page</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="styles.css">
	</head>
	<body>
	<?php
	if(isset($_POST["login"])){
				$Loginemail=$_POST['loginEmail'];
				$Loginpassword=$_POST['loginPassword'];

				$servername = "localhost";
				$username = "root";
				$password = "123456789";
				$db_name = "movies";

				$conn = new mysqli($servername, $username, $password, $db_name);
				// Check connection
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}
				// echo "$Loginemail $Loginpassword";
				$sql = "select * from users where email_address=\"$Loginemail\" and password=\"$Loginpassword\"";
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					// echo "user found";
					$_SESSION["username"] = $Loginemail;
				}
				else{
					$_SESSION["wrongDetails"] = 1;
				}
			}
	 ?>
	 <?php
			if(isset($_POST["register"])){
				$registerEmail=$_POST['registerEmail'];
				$registerPassword=$_POST['registerPassword'];

				$servername = "localhost";
				$username = "root";
				$password = "123456789";
				$db_name = "movies";

				$conn = new mysqli($servername, $username, $password, $db_name);
				// Check connection
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}

				$sql = "CREATE TABLE users (
					email_address VARCHAR(40) PRIMARY KEY,
					password varchar(30)
				)";

				if ($conn->query($sql) === TRUE) {
					// echo "table created";
				}
				else{
					// echo "user duplicate";
				}

				$sql = "INSERT INTO users values(\"$registerEmail\",\"$registerPassword\")";
				if ($conn->query($sql) === TRUE) {
					// echo "user created";
				}
				else{
					$_SESSION["alreadyExists"] = 1;
				}

			}
		?>
	<?php
	if($_SESSION["wrongDetails"]){
	echo "<div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">
	  Either your username or password is incorrect.
	  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
	    <span aria-hidden=\"true\">&times;</span>
	  </button>
	</div>";
	}
	else if($_SESSION["alreadyExists"]){
	echo "<div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">
	  <strong>User already exists</strong> Change your email address.
	  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
	    <span aria-hidden=\"true\">&times;</span>
	  </button>
	</div>";
	}
	$_SESSION["wrongDetails"] = 0;
	$_SESSION["alreadyExists"]=0;
	?>
		<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: rgba(124,180,276,0.1);">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarTogglerDemo01">
				<a class="navbar-brand" href="index.php"><h2>Bookr</h2></a>
				<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
					<li class="nav-item active">
						<a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
					</li>

				</ul>

				<?php
				if ($_SESSION["username"])
				{
					$currentname = $_SESSION["username"];
					echo "<span class=\"name\">Hi, $currentname</span>";
					echo "<button class=\"btn btn-outline-light logout \" href=#>Logout</button>";
				}
				else{
					echo "<button type=\"button\" class=\"btn btn-outline-light mainBtn\" data-toggle=\"modal\" data-target=\"#loginModal\"><h5>
						Login</h5>
						</button>
						<button type=\"button\" class=\"btn btn-outline-light mainBtn\" data-toggle=\"modal\" data-target=\"#registerModal\"><h5>
						Register</h5>
						</button>";
				}

				?>
			</div>
		</nav>
		<div class="header">
			<h1 class="header-text">WELCOME
			</h1>
		</div>
		<div class="container1" id="bookTickets">
			<h1 style="font-size: 40px;">BOOK TICKETS ONLINE</h1>
		</div>
		<div class="container1" style="padding-top: 2rem">
			<button type="button" class=" tablinks btn btn-outline-dark categories" id="defaultOpen" onclick="getMovies(event, 'popular')">Popular </button>
			<button type="button" class=" tablinks btn btn-outline-dark categories" onclick="getMovies(event, 'topRated')">Top Rated</button>
			<button type="button" class=" tablinks btn btn-outline-dark categories" onclick="getMovies(event, 'upComing')">Coming Soon</button>
		</div>
		<div class="container1 tabcontent" id="popular">
			<?php
			$url = "https://api.themoviedb.org/3/movie/popular?api_key=$myAPI&language=en-US&page=1";
			$data = json_decode(file_get_contents($url), true);
			for ($i=0; $i < 10; $i++) {
			$string =  $data["results"][$i]["poster_path"];
			$id =  $data["results"][$i]["id"];
			$title = $data["results"][$i]["title"];
				$title = strtolower($title);
				//Make alphanumeric (removes all other characters)
				$title = preg_replace("/[^a-z0-9_\s-]/", "", $title);
				//Clean up multiple dashes or whitespaces
				$title = preg_replace("/[\s-]+/", " ", $title);
				//Convert whitespaces and underscore to dash
				$MovieTitle = $title;
				$title = preg_replace("/[\s_]/", "-", $title);
			$image_url = "image.tmdb.org/t/p/w185/$string";
			echo "<div class=\"col-lg-3 col-md-4 col-sm-6 col-xs-12\">
    				<div class=\"hovereffect\">
        				<img class=\"img-fluid img-responsive\" src=\"https://$image_url\" alt=\"\">
        				<div class=\"overlay\">
           					<h2>$MovieTitle</h2>
           					<a class=\"info\" href=\"movie.php?id=$id\">Book</a>
        				</div>
    				</div>
				</div>";
			}
			echo '<br>';
			?>
		</div>
		<div class="container1 tabcontent" id="topRated">
			<?php
			$url = "https://api.themoviedb.org/3/movie/top_rated?api_key=$myAPI&language=en-US&page=1";
			$data = json_decode(file_get_contents($url), true);
			for ($i=0; $i < 10; $i++) {
			$string =  $data["results"][$i]["poster_path"];
			$id =  $data["results"][$i]["id"];
			$title = $data["results"][$i]["title"];
				$title = strtolower($title);
				//Make alphanumeric (removes all other characters)
				$title = preg_replace("/[^a-z0-9_\s-]/", "", $title);
				//Clean up multiple dashes or whitespaces
				$title = preg_replace("/[\s-]+/", " ", $title);
				$MovieTitle = $title;
				//Convert whitespaces and underscore to dash
				$title = preg_replace("/[\s_]/", "-", $title);
			$image_url = "image.tmdb.org/t/p/w185/$string";
			echo "<div class=\"col-lg-3 col-md-4 col-sm-6 col-xs-12\">
    				<div class=\"hovereffect\">
        				<img class=\"img-fluid img-responsive\" src=\"https://$image_url\" alt=\"\">
        				<div class=\"overlay\">
           					<h2>$MovieTitle</h2>
           					<a class=\"info\" href=\"movie.php?id=$id\">Book</a>
        				</div>
    				</div>
				</div>";
			}
			echo '<br>';
			?>
		</div>
		<div class="container1 tabcontent" id="upComing">
			<?php
			$url = "https://api.themoviedb.org/3/movie/upcoming?api_key=$myAPI&language=en-US&page=1";
			$data = json_decode(file_get_contents($url), true);
			for ($i=0; $i < 10; $i++) {
			$string =  $data["results"][$i]["poster_path"];
			$id =  $data["results"][$i]["id"];
			$title = $data["results"][$i]["title"];
				$title = strtolower($title);
				//Make alphanumeric (removes all other characters)
				$title = preg_replace("/[^a-z0-9_\s-]/", "", $title);
				//Clean up multiple dashes or whitespaces
				$title = preg_replace("/[\s-]+/", " ", $title);
				$MovieTitle = $title;
				//Convert whitespaces and underscore to dash
				$title = preg_replace("/[\s_]/", "-", $title);
			$image_url = "image.tmdb.org/t/p/w185/$string";
			echo "<div class=\"col-lg-3 col-md-4 col-sm-6 col-xs-12\">
    				<div class=\"hovereffect\">
        				<img class=\"img-fluid img-responsive\" src=\"https://$image_url\" alt=\"\">
        				<div class=\"overlay\">
           					<h2>$MovieTitle</h2>
           					<a class=\"info\" disabled>Coming Soon</a>
        				</div>
    				</div>
				</div>";
			}
			echo '<br>';
			?>
		</div>
		<div class="container1 news">
			<h1 style="margin-top: 35px; font-size:50px;">NEWSLETTER</h1>
			<p style="font-size:25px;">Enter your email address to recieve all news and updates from us.</p>
			<div class="container1">
				<form class="container1">
					<div class="container1">
						<input type="email" class="form-control form-input" id="inputPassword2" placeholder="Your Email ...">
						<button type="submit" class="btn btn-primary mb-2">Subscribe</button>
					</div>
				</form>
			</div>
		</div>
		<div class="container3">
			<p>Made with ❤ by Hardik Khandelwal</p>
		</div>
		<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Login</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
							<div class="form-group">
								<label for="exampleInputEmail1">Email address</label>
								<input name="loginEmail" type="email" class="form-control" aria-describedby="emailHelp" placeholder="Enter email">
								<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
							</div>
							<div class="form-group">
								<label for="exampleInputPassword1">Password</label>
								<input name="loginPassword" type="password" class="form-control" placeholder="Password">
							</div>
						<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button name="login" type="submit" class="btn btn-primary" >Login</button>
						</div>
					</form>
				</div>
			</div>
			</div>
			</div>
		<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form method="post">
							<div class="form-group">
								<label for="exampleInputEmail1" >Email address</label>
								<input name="registerEmail" type="email" class="form-control" aria-describedby="emailHelp" placeholder="Enter email">
								<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
							</div>
							<div class="form-group">
								<label for="exampleInputPassword1">Password</label>
								<input name="registerPassword" type="password" class="form-control" placeholder="Password">
							</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button name="register" type="submit" class="btn btn-primary">Register</button>
					</div>
					</form>
				</div>
			</div>
			</div>
			</div>

		<script>
			document.getElementById("defaultOpen").click();
			function getMovies(evt, movieCat) {
			// Declare all variables
			var i, tabcontent, tablinks;
			// Get all elements with class="tabcontent" and hide them
			tabcontent = document.getElementsByClassName("tabcontent");
			for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
			}
			// Get all elements with class="tablinks" and remove the class "active"
			tablinks = document.getElementsByClassName("tablinks");
			for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" active", "");
			}
			// Show the current tab, and add an "active" class to the button that opened the tab
			document.getElementById(movieCat).style.display = "flex";
			document.getElementById(movieCat).style.flexWrap = "wrap";
			document.getElementById(movieCat).style.justifyContent = "center";
				evt.currentTarget.className += " active";
			}

			function logout() {
        	document.location = 'logout.php';
    		}
    		if(document.querySelector(".logout")){
    			const LogoutButton = document.querySelector(".logout");
    			LogoutButton.addEventListener('click', logout, false);
    		}
		</script>
	</body>
</html>