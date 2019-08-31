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
		<link rel="stylesheet" type="text/css" href="styles.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	</head>
	<body>
		<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark" style="background-color: rgba(124,180,276,0.1);">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarTogglerDemo01">
				<a class="navbar-brand" href="/Movie_Ticket">Bookr</a>
				<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
				</ul>
				<?php
				if ($_SESSION["username"])
				{
					$currentname = $_SESSION["username"];
					echo "<span class=\"name\">Hi, $currentname</span>";
				}
				?>
			</div>
		</nav>
		<div class="back">
		</div>
		<div class="container4">
			<?php
			$id = $_GET['id'];
			$url = "https://api.themoviedb.org/3/movie/$id?api_key=$myAPI&language=en-US";
			$data = json_decode(file_get_contents($url), true);
			$string =  $data["poster_path"];
			$image_url = "image.tmdb.org/t/p/w185/$string";
			$movie_name = $data["title"];
			if(strlen($data["title"])>25){
				$name = preg_split("/[-||–]/", $movie_name);
				$name = $name[0];
			}
			else{
				// echo $movie_name;
				$name = $movie_name;
			}
			$overview = $data["overview"];
			$average_vote = $data["vote_average"];
			$genres_arr = array();
			$genres = $data["genres"];
			for($x=0;$x<sizeof($genres);$x++) {
			array_push($genres_arr, $genres[$x]["name"]);
			}
			$release_date = $data["release_date"];
			$time = $data["runtime"];
			$summary = $data["overview"];
			echo "<img class=\"img-fluid img-thumbnail \" src=https://$image_url />";
			echo "<div class=\"content\">";
						echo "<h1 class=\"item\">$name</h1>";
						for($x=0;$x<sizeof($genres_arr);$x++)
						echo "<span class=\"gen\">$genres_arr[$x]</span>";
						echo "<div class = \"row\">";
									echo "<h5 class=\"item\"><img class=\"icon\" src=\"https://image.flaticon.com/icons/svg/4/4430.svg\" alt=\"calender\">$release_date</h5>";
									echo "<h5 class=\"item\"><img class=\"icon\" src=\"https://image.flaticon.com/icons/svg/66/66403.svg\" alt=\"clock\">$time minutes</h5>";
									echo "<h5 class=\"item\"><img class=\"icon\" src=\"https://image.flaticon.com/icons/svg/61/61101.svg\" alt=\"rating\">$average_vote</h5>";
						echo "</div>";
						echo "<button type=\"button\" class=\"btn btn-primary\" data-toggle=\"modal\" data-target=\"#exampleModalCenter\">Book Tickets</button>";
			echo "</div>";
			echo "<div class=\"container\">";
							echo "<h3>Summary :</h3>";
								echo "<p class=\"item\">$summary</p>";
				echo "</div>";
			?>
		</div>
		<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalCenterTitle">Select the time slot</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<button type="button" class="time btn btn-outline-primary" data-dismiss="modal" data-toggle="modal" data-target="#numberOfSeats" data-time="6">6:00 PM</button>
						<button type="button" class="time btn btn-outline-primary" data-dismiss="modal" data-toggle="modal" data-target="#numberOfSeats" data-time="9">9:00 PM</button>
					</div>
				</div>
			</div>
		</div>
		<?php
			$servername = "localhost";
			$username = "root";
			$password = "123456789";
			$db_name = "movies";
			// Create connection
			$conn = new mysqli($servername, $username, $password, $db_name);
			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			// connected
			$table1 = "movie_".(string)$id."_6";
			$table2 = "movie_".(string)$id."_9";
			//echo $table1;
			//echo "<br>";
			//echo $table2;
			$sql = "CREATE TABLE $table1 (
					seat_number INT(6) PRIMARY KEY,
					status varchar(10)
			)";
			if ($conn->query($sql) === TRUE) {
				//echo "Table $table1 created successfully <br>";
				for($i=1 ; $i<=60; $i++) {
							$sql = "INSERT INTO $table1 VALUES ($i , 'unbooked')";
							if ($conn->query($sql) === TRUE) {
							} else {
							}
						}
			} else {
				//echo "Error creating table: " . $conn->error;
			}

			$sql = "CREATE TABLE $table2 (
					seat_number INT(6) PRIMARY KEY,
					status varchar(10)
			)";
			if ($conn->query($sql) === TRUE) {
				//echo "Table $table1 created successfully <br>";
				for($i=1 ; $i<=60; $i++) {
							$sql = "INSERT INTO $table2 VALUES ($i , 'unbooked')";
							if ($conn->query($sql) === TRUE) {
							} else {
							}
						}
			} else {
				//echo "Error creating table: " . $conn->error;
			}

		?>
		<div class="modal fade" id="numberOfSeats" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">How many seats do you want to book?</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<button type="button" class="seats btn btn-outline-primary" data-seat="1">1</button>
						<button type="button" class="seats btn btn-outline-primary" data-seat="2">2</button>
						<button type="button" class="seats btn btn-outline-primary" data-seat="3">3</button>
						<button type="button" class="seats btn btn-outline-primary" data-seat="4">4</button>
						<button type="button" class="seats btn btn-outline-primary" data-seat="5">5</button>
						<button type="button" class="seats btn btn-outline-primary" data-seat="6">6</button>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary">Proceed</button>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			let URL = "/Movie_Ticket/book.php"+window.location.search;
			function getTime() {
				URL+=`&time=${this.dataset.time}`;
				// console.log(this.dataset.time);
				// console.log(URL);
			}
			function getSeats() {
				URL+=`&seat=${this.dataset.seat}`;
				// console.log(this.dataset.seat);
				window.location.href = URL;
				console.log(URL)
			}
			function loadPage(){
			}
			times = document.querySelectorAll('.time');
			seats = document.querySelectorAll('.seats');
			times.forEach(time => time.addEventListener('click', getTime));
			seats.forEach(seat => seat.addEventListener('click', getSeats));
		</script>
	</body>
</html>
