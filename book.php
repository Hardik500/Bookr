<!DOCTYPE html>
<?php
	setcookie("seatsBooked", "", time() - 3600);
	$cookie_name = "seatsBooked";
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
		<div class="container7">
			<table>
				<tr>
					<th colspan="10" style="border: 0px;">PLATINUM - Rs 200</th>
				</tr>
				<tr>
					<td>1</td>
					<td>2</td>
					<td>3</td>
					<td>4</td>
					<td>5</td>
					<td>6</td>
					<td>7</td>
					<td>8</td>
					<td>9</td>
					<td>10</td>
				</tr>
				<tr>
					<td>11</td>
					<td>12</td>
					<td>13</td>
					<td>14</td>
					<td>15</td>
					<td>16</td>
					<td>17</td>
					<td>18</td>
					<td>19</td>
					<td>20</td>
				</tr>
				<tr>
					<th colspan="10" style="border: 0px;">GOLD - Rs 150</th>
				</tr>
				<tr>
					<td>21</td>
					<td>22</td>
					<td>23</td>
					<td>24</td>
					<td>25</td>
					<td>26</td>
					<td>27</td>
					<td>28</td>
					<td>29</td>
					<td>30</td>
				</tr>
				<tr>
					<td>31</td>
					<td>32</td>
					<td>33</td>
					<td>34</td>
					<td>35</td>
					<td>36</td>
					<td>37</td>
					<td>38</td>
					<td>39</td>
					<td>40</td>
				</tr>
				<tr>
					<th colspan="10" style="border: 0px;">SILVER - Rs 100</th>
				</tr>
				<tr>
					<td>41</td>
					<td>42</td>
					<td>43</td>
					<td>44</td>
					<td>45</td>
					<td>46</td>
					<td>47</td>
					<td>48</td>
					<td>49</td>
					<td>50</td>
				</tr>
				<tr>
					<td>51</td>
					<td>52</td>
					<td>53</td>
					<td>54</td>
					<td>55</td>
					<td>56</td>
					<td>57</td>
					<td>58</td>
					<td>59</td>
					<td>60</td>
				</tr>
			</table>
			<div class="ticket-section">
				<hr>
				<p>All eyes this way please!</p>
			</div>
		<!-- </div>
		<div> -->
			<div class="container">
				<h3 class="price">Total: ₹<span class="amount">0</span></h3>
				<?php
					if ($_SESSION["username"]){
						echo "<button class=\"btn btn-primary book-btn\" type=\"button\" data-toggle=\"modal\" data-target=\"#book_ticket\" id=\"book-btn\" onClick=\"bookTickets()\" disabled>Book Tickets</button>";
					}
					else{
						echo "<div class=\"alert alert-danger danger-alert\" role=\"alert\">
  							Please <a href=\"index.php\" class=\"alert-link\">login</a> to continue
		</div>";
					}
				 ?>
			</div>
		</div>
		<div class="modal fade" id="book_ticket" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<?php
						$id = $_GET['id'];
						$seats = $_GET['seat'];
						$url = "https://api.themoviedb.org/3/movie/$id?api_key=$myAPI&language=en-US";
						$data = json_decode(file_get_contents($url), true);
						$movie_name = $data["title"];
						$t = time();
						$final_ref = base_convert($t,10,16);
						echo "<h5 class=\"modal-title\" id=\"exampleModalLongTitle\">Your Ticket! (Ref. No. $final_ref)</h5>"
						?>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body ticket" id='printableArea'>
						<?php
						$t = time();
						$usernameOfUser = $_SESSION['username'];
						echo "<img src=\"https://api.qrserver.com/v1/create-qr-code/?data=$t&amp;size=120x120\" alt=\"\" title=\"\" style=\"float: left;\" />";
						echo "<div class='container'>";
							echo "<h5>Email : $usernameOfUser</h5>";
							echo "<h5>Movie Name : $movie_name</h5>";
							echo "<h5>Seats Booked : <span class='seatsBooked'></span></h5>";
							echo "<h5>Price : ₹<span class='MoviePrice'></span></h5>";
						echo "</div>";
						?>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal" onClick=closeFn()>Close</button>
						<button type="button" onclick="printDiv('printableArea')" class="btn btn-primary">Print Receipt</button>
					</div>
				</div>
			</div>
		</div>
		<?php
		error_reporting(E_ERROR | E_PARSE);

		$id = htmlspecialchars($_GET["id"]);
		$seat = htmlspecialchars($_GET["seat"]);
		$time = htmlspecialchars($_GET["time"]);
		$table_name = "movie_".(string)$id."_".(string)$time;
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

		if(!isset($_COOKIE[$cookie_name])) {
			// echo "Cookie named '" . $cookie_name . "' is not set!";
		} else {
			// echo "Cookie '" . $cookie_name . "' is set!<br>";
			$seatsBooked  = explode(",",$_COOKIE[$cookie_name]);
		}
		for($i=0;$i<count($seatsBooked);$i++){
			$sql = "update $table_name set status=\"booked\" where seat_number=$seatsBooked[$i]";
			$result = $conn->query($sql);
		}



		$sql = "SELECT seat_number,status FROM $table_name where status='booked'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			$seats=array();
			while($row = $result->fetch_assoc()) {
				// echo $row['seat_number'];
				array_push($seats,$row['seat_number']);
			}
			for($i=0;$i<count($seats);$i++){
				echo "<p class=\"hiddenRows d-none\">$seats[$i]</p>";
			}
		} else {
			echo "0 results";
		}
		$seatsBooked = "";

		?>
		<script type="text/javascript">
			seats = document.querySelectorAll("td");
			const url_string = window.location;
			const url = new URL(url_string);
			const total_seat = parseInt(url.searchParams.get("seat"));
			const amount = document.querySelector(".amount");
			let price = 0;
			let seatArr = []
			let bookedSeats = []
			let count = document.querySelectorAll(".checked");

		let fetchedSeats = document.querySelectorAll(".hiddenRows");
		fetchedSeats.forEach(e => bookedSeats.push(e.textContent));
		for(var i=0;i<bookedSeats.length;i++){
			seats.forEach(seat => {
				if(seat.textContent == bookedSeats[i]){
					seat.classList.add("disabled");
				}
			});
		}
			seats.forEach(seat => seat.addEventListener("click", function() {
				if (!seat.classList.contains("checked") && count.length == total_seat) {
				} else {
					if (!seat.classList.contains("checked")) {
						let seatNumber = seat.textContent;
						seatArr.push(seatNumber);
						if (1 <= seatNumber && seatNumber <= 20) {
							price += 200;
						} else if (21 <= seatNumber && seatNumber <= 40) {
							price += 150;
						} else {
							price += 100;
						}
					} else {
						let seatNumber = seat.textContent;
						seatArr = seatArr.filter(item => item!==seatNumber);
						if (1 <= seatNumber && seatNumber <= 20) {
							price -= 200;
						} else if (21 <= seatNumber && seatNumber <= 40) {
							price -= 150;
						} else {
							price -= 100;
						}
					}
					document.querySelector('.seatsBooked').textContent = seatArr.toLocaleString()
					document.querySelector('.MoviePrice').textContent = price
					seat.classList.toggle("checked");
					count = document.querySelectorAll(".checked");
					amount.innerHTML = price;
					if (count.length == total_seat) {
						document.getElementById("book-btn").disabled = false;
						//print(count)
					} else {
						document.getElementById("book-btn").disabled = true;
					}
				}
			}))
			function bookTickets(){
				document.cookie = `seatsBooked=${seatArr}; expires=Thu, 18 Dec 2019 12:00:00 UTC;`;
			}
			$('#book_ticket').on('hidden.bs.modal', function (e) {
					window.location.href="http://localhost/Movie_Ticket/";
					// document.location.reload();
			})

			function printDiv(divName) {
 			    var printContents = document.getElementById(divName).innerHTML;
     			var originalContents = document.body.innerHTML;

     			document.body.innerHTML = printContents;

     			window.print();

     			document.body.innerHTML = originalContents;
			}

			function closeFn() {
				window.location.href="http://localhost/Movie_Ticket/";
			}
		</script>
	</body>
</html>