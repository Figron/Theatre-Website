<?php
session_start();
include_once 'conf/database.php';

$db_handle = new Database();
$conn = $db_handle->getConnection();

if(!empty($_GET["action"])) {
switch($_GET["action"]) {
	// case "add":
	// 	if(!empty($_POST["quantity"])) {
	// 		$productByCode = $db_handle->runQuery("SELECT * FROM dishes WHERE code='" . $_GET["Code"] . "'");
	// 		$itemArray = array($productByCode[0]["Code"]=>array('Name'=>$productByCode[0]["Name"], 'Code'=>$productByCode[0]["Code"], 'Quantity'=>$_POST["Quantity"], 'Price'=>$productByCode[0]["Price"], 'Image'=>$productByCode[0]["Image"]));
	//
	// 		if(!empty($_SESSION["cart_item"])) {
	// 			if(in_array($productByCode[0]["Code"],array_keys($_SESSION["cart_item"]))) {
	// 				foreach($_SESSION["cart_item"] as $k => $v) {
	// 						if($productByCode[0]["Code"] == $k) {
	// 							if(empty($_SESSION["cart_item"][$k]["Quantity"])) {
	// 								$_SESSION["cart_item"][$k]["Quantity"] = 0;
	// 							}
	// 							$_SESSION["cart_item"][$k]["Quantity"] += $_POST["Quantity"];
	// 						}
	// 				}
	// 			} else {
	// 				$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
	// 			}
	// 		} else {
	// 			$_SESSION["cart_item"] = $itemArray;
	// 		}
	// 	}
	// break;
	case "remove":
		if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
					if($_GET["Code"] == $k)
						unset($_SESSION["cart_item"][$k]);
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
		}
	break;
	case "empty":
		unset($_SESSION["cart_item"]);
	break;
}
}
?>
<!DOCTYPE HTML>
<html>

<head>
  <title>Your order</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <link href='https://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet'>
    <script src="conf/ajax.js" type="text/javascript"></script>
</head>

<body style="height:1500px; font-family:Ubuntu;">
  <div class="jumbotron text-center" style="margin-bottom:0">
    <div class="container">
      <h1> Це ваше замовлення </h1>
    </div>
  </div>
	<nav class="navbar navbar-expand-sm bg-dark navbar-dark sticky-top">
    <a class="navbar-brand" mr-auto href="Theatre.php">Домашня сторінка</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="Spectacles.php">Подивитися вистави</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="About_Us.php">Про нас</a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item" style="<?php
        if(isset($_SESSION['logged'])){
          if($_SESSION['logged']==true){
            echo "display:none;";
          }else echo "display:inline;";
        }
         ?>
        ">
          <a class="nav-link" href="#" data-toggle="modal" data-target="#myModal">
            <?php if(isset($_SESSION['logged'])){
              if($_SESSION['logged']==false){
              echo "Реєстрація/Логін";
            }
            }
          else {
            $_SESSION['logged'] = false;
            echo "Реєстрація/Логін";
          }?>
        </a>
        </li>
        <li class="nav-item dropdown" style="<?php
        if(isset($_SESSION['logged'])){
          if($_SESSION['logged']==false){
            echo "display:none;";
          }else echo "display:block;";
        }

        ?>">
          <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">Вітаємо, <?php echo $_SESSION['user_login'];?></a>

        <div class="dropdown-menu">
          <?php
          if(isset($_SESSION['role'])&&$_SESSION['role'] == 'admin'){
            echo "<a class = 'dropdown-item' href='Orders.php'>Подивитися замовлення</a>";
						echo "<a class = 'dropdown-item' href='Statistics.php'>Подивитися рекомендації</a>";
            echo "<a class = 'dropdown-item' href='Report.php'>Подивитися звіт</a>";
          }
           ?>
          <a class="dropdown-item" href="/User/logout.php">Вийти</a>
        </div>
      </li>
        <li class="nav-item">
          <a class="nav-link" href="Shopping_Cart.php">Кошик</a>
        </li>
      </ul>
    </div>
  </nav>
  <div id="shopping-cart">
  <h3 class="text-center my-4">Кошик</h3>
	<?php
	//Checking if there are empty seats
	function check_empty(){
		include_once 'conf/database.php';

		$db_handle = new Database();
		$conn = $db_handle->getConnection();

		foreach ($_SESSION["cart_item"] as $item){
			$code = $item["Code"];
			$quantity = $item["Quantity"];
			// $query = "SELECT sp.Bought + ? FROM theatre.spectacles AS sp WHERE Code = ?";
			$query = "SELECT sp.Bought + $quantity FROM theatre.spectacles AS sp WHERE sp.Code = '{$code}'";
			$stmt = $conn->query($query);
			// $stmt = $conn->prepare($query);
			// $stmt->bind_param('is', $quantity, $code);
			//
			// $quantity = $item["Quantity"];
			// $code = $item["Code"];

			// echo "<p>$query</p>";

			// $result = $stmt->execute();
			while ($row = $stmt->fetch_assoc()){
				foreach ($row as $booked) {
					// echo "<p>$booked</p>";
					$query = "SELECT Capacity FROM theatre.stages WHERE idStages = (SELECT idStages FROM theatre.spectacles WHERE Code = '{$code}')";
					$stmt1 = $conn->query($query);

					while ($row1 = $stmt1->fetch_assoc()){
						foreach ($row1 as $avaliable) {
							// echo "<p>$avaliable</p>";
							if($avaliable - $booked < 0){
								return false;
							}else{
								$query = "UPDATE theatre.spectacles SET Bought = $booked WHERE Code = '{$code}'";
								// echo "<p>$query</p>";
								$stmt2 = $conn->query($query);
							}
						}
					}
				}
			}
			// $temp = $stmt->fetch_assoc();

			// echo "<p>'$temp[0]'</p>";


			// $query = "SELECT sp.Bought + '$item['Quantity']' FROM theatre.spectacles AS sp WHERE Code = '$item['Code']'"
			// $stmt = $db_handle->conn->query($query);
			// echo "<p>'mysql_fetch_row($stmt)'</p>"
		}
		return true;
	}
	//Adding order to database
	function send_Order(){
		include_once 'conf/database.php';

		$db_handle = new Database();
		$conn = $db_handle->getConnection();
		$ulogin = $_SESSION["user_login"];
		//Create new order assigned to user
		$query = "INSERT INTO orders (idOrders, idStatus, idUser) VALUES ($conn->insert_id, 1, (SELECT idUser FROM users WHERE Login='$ulogin'))"; //Status 1 is started
		$stmt = $db_handle->conn->query($query);
		//Save current order id
		$query = "SELECT idOrders from orders WHERE idUser=(SELECT idUser FROM users WHERE Login='$ulogin')";
		$stmt = $db_handle->conn->query($query);
		$row = $stmt->fetch_assoc();
		$id_orders = $row['idOrders'];
		//Fill order with dishes
		foreach ($_SESSION["cart_item"] as $item){
			$code = $item['Code'];
			$quantity = $item['Quantity'];
			$query = "INSERT INTO order_has_spectacle(idorder_has_spectacle, idOrder, idSpectacles, Quantity) VALUES ($conn->insert_id, $id_orders, (SELECT idSpectacles FROM spectacles WHERE Code='$code'), $quantity)";
			$stmt = $db_handle->conn->query($query);
			}
			echo "<div class='alert alert-success'>Your order was successfully sent!</div>";
	}
	//Function to process submit click
	if(array_key_exists('submit_order',$_POST)){
		if(check_empty()){
			send_Order();
		}
		else{
			echo "<div class='alert alert-danger'>You have booked seats that are not avaliable!</div>";
		}
	}
	?>

  <?php
  if(isset($_SESSION["cart_item"])){
      $total_quantity = 0;
      $total_price = 0;
  ?>
	<div class="table-responsive-sm">
  <table class="table">
  <tbody>
  <tr>
  <th style="text-align:left;">Name</th>
  <th style="text-align:left;">Code</th>
  <th style="text-align:right;" width="5%">Quantity</th>
  <th style="text-align:right;" width="10%">Unit Price</th>
  <th style="text-align:right;" width="10%">Price</th>
  <th style="text-align:center;" width="5%">Remove</th>
  </tr>
  <?php
      foreach ($_SESSION["cart_item"] as $item){
          $item_price = $item["Quantity"]*$item["Price"];
  		?>
  				<tr>
  				<td><img src="<?php echo $item["Image"]; ?>"  class="mr-3 mt-3" style="width:255px" /><?php echo $item["Name"]; ?></td>
  				<td><?php echo $item["Code"]; ?></td>
  				<td style="text-align:right;"><?php echo $item["Quantity"]; ?></td>
  				<td  style="text-align:right;"><?php echo $item["Price"]." uan"; ?></td>
  				<td  style="text-align:right;"><?php echo number_format($item_price,2)." uan"; ?></td>
  				<td style="text-align:center;"><a href="Shopping_Cart.php?action=remove&Code=<?php echo $item["Code"]; ?>" class="btnRemoveAction"><img src="objects/icon-delete.png" alt="Remove Item" /></a></td>
  				</tr>
  				<?php
  				$total_quantity += $item["Quantity"];
  				$total_price += ($item["Price"]*$item["Quantity"]);
  		}
  		?>

  <tr>
  <td colspan="2" align="right">Total:</td>
  <td align="right"><?php echo $total_quantity; ?></td>
  <td align="right" colspan="2"><strong><?php echo number_format($total_price, 2)." uan"; ?></strong></td>
  <td></td>
  </tr>
  </tbody>
  </table>
</div>
	<a class="btn btn-danger ml-1" id="btnEmpty" href="Shopping_Cart.php?action=empty">Очистити кошик</a>
	<?php
	if(isset($_SESSION['logged'])&&$_SESSION['logged']==true){
		echo
		"<form method='post'>
			<input type='submit' class='float-right btn btn-info mr-1' name='submit_order' id='submit_order' value='Відправити замовлення'>
		</form>";
	}

	?>
    <?php
  } else {
  ?>
  <div class="container">Ваш кошик пустий, поки що...</div>
  <?php
  }
  ?>
  </div>

	<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <ul class="nav nav-tabs nav-fill" style="width:100%">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#login_tab">Логін</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#signup_tab">Реєстрація</a>
            </li>
          </ul>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <div class="tab-content">
            <div class="tab-pane container active" id="login_tab">
              <form class="needs-validation" novalidate method="get"  id="login_form">
                <!-- onsubmit="sendAjaxForm('result_response','login_form','/User/login.php')" -->
                <div class="form-group" id="login_response">
                </div>
                <div class="form-group">
                  <label for="uname">Логін:</label>
                  <input type="text" class="form-control" id="ulog" placeholder="Введіть логін" name="ulog" required>
                  <div class="valid-feedback">Valid</div>
                  <div class="invalid-feedback">Будь ласка заповніть це поле.</div>
                </div>
                <div class="form-group">
                  <label for="pwd">Пароль:</label>
                  <input type="password" class="form-control" id="upass" placeholder="Введіть пароль" name="upass" required>
                  <div class="valid-feedback">Valid.</div>
                  <div class="invalid-feedback">Будь ласка заповніть це поле.</div>
                </div>
                <button type="button" onclick="sendAjaxLogin('result_response','login_form','/User/login.php')"class="btn btn-primary" id="submit_login">Увійти</button>
                <!-- <div class="form-group">

                </div> -->
            </div>
            </form>
            <div class="tab-pane container fade" id="signup_tab">
              <form class="needs-validation" novalidate method="post" id="signup_form">
                <div class="form-group" id="signup_response">
                </div>
                <div class="form-group">
                  <label for="uname">Логін:</label>
                  <input type="text" class="form-control" id="uslog" placeholder="Введіть логін" name="ulog" required>
                  <div class="valid-feedback">Valid</div>
                  <div class="invalid-feedback">Будь ласка заповніть це поле.</div>
                </div>
                <div class="form-group">
                  <label for="pwd">Пароль:</label>
                  <input type="password" class="form-control" id="uspass" placeholder="Введіть пароль" name="upass" required>
                  <div class="valid-feedback">Valid.</div>
                  <div class="invalid-feedback">Будь ласка заповніть це поле.</div>
                </div>
                <div class="form-group">
                  <label for="phone">Телефон:</label>
                  <input type="text" class="form-control" id=usphone placeholder="Введіть телефон" name="uphone" required>
                  <div class="valid-feedback">Valid.</div>
                  <div class="invalid-feedback">Будь ласка заповніть це поле.</div>
                </div>
                <div class="form-group form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="remember" required> Я погоджуюся на обробку моїх персональних даних.
                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Відмідьте це поле для продовження.</div>
                  </label>
                </div>
                <button type="button" onclick="sendAjaxSignup('signup_response','signup_form','/User/signup.php')" class="btn btn-primary" id="submit_login">Зареєструватися</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</body>
</html>
