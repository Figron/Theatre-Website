<?php
session_start();

include_once 'conf/database.php';

$db_handle = new Database();
$conn = $db_handle->getConnection();

if(!empty($_GET["action"])) {

switch($_GET["action"]) {
	case "add":
		if(!empty($_POST["Quantity"])) {

			$productByCode = $db_handle->runQuery("SELECT * FROM spectacles WHERE Code='" . $_GET["Code"] . "'");
			$itemArray = array($productByCode[0]["Code"]=>array('Name'=>$productByCode[0]["Name"], 'Code'=>$productByCode[0]["Code"], 'Quantity'=>$_POST["Quantity"], 'Price'=>$productByCode[0]["Price"], 'Image'=>$productByCode[0]["Image"]));

			if(!empty($_SESSION["cart_item"])) {
				if(in_array($productByCode[0]["Code"],array_keys($_SESSION["cart_item"]))) {
					foreach($_SESSION["cart_item"] as $k => $v) {
							if($productByCode[0]["Code"] == $k) {
								if(empty($_SESSION["cart_item"][$k]["Quantity"])) {
									$_SESSION["cart_item"][$k]["Quantity"] = 0;
								}
								$_SESSION["cart_item"][$k]["Quantity"] += $_POST["Quantity"];
							}
					}
				} else {
					$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
				}
			} else {
				$_SESSION["cart_item"] = $itemArray;
			}
		}
	break;
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
  <title>Spectacles</title>
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
      <h1> Насолоджуйтесь нашими виставами!</h1>
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
    <div class="container" style="margin-top:30px">
<h4> Тут ви можете подивитися на наші вистави! </h4>
    </div>
    <div class="container" id="product-grid">
    	<div class="txt-heading">Вистави:</div>
    	<?php
    	$product_array = $db_handle->runQuery("SELECT sp.Name, Date, Price, Code, Image, Capacity, Bought, Category FROM spectacles AS sp JOIN stages AS st ON sp.idStages = st.idStages JOIN genres AS g ON sp.idGenres = g.idGenres ORDER BY idSpectacles ASC");
    	if (!empty($product_array)) {
    		foreach($product_array as $key=>$value){
    	?>
				<div class="media border p-3">
					<form method="post" action="Spectacles.php?action=add&Code=<?php echo $product_array[$key]["Code"]; ?>">
						<img src="<?php echo $product_array[$key]["Image"]; ?>" class="mr-3 mt-3" style="width:255px">
				<div class="media-body">
				<p><?php echo $product_array[$key]["Name"]; ?></p>
				<p><?php echo $product_array[$key]["Category"]; ?></p>
				<p><?php echo $product_array[$key]["Date"]; ?></p>
				<p>Залишилось білетів: <?php echo $product_array[$key]["Capacity"] -  $product_array[$key]["Bought"]?></p>
				<p><?php echo $product_array[$key]["Price"]." uan"; ?></p>
				<div class="input-group mb-3">
				<p><input type="text" class="form-control" name="Quantity" value="1" size="2" />
					<div class="input-group-append">
					<input type="submit" value="Add to Cart" class="form-control btn btn-outline-dark" id="add_to_cart"/>
				</div>
				</p>
				</div>
					</div>
					</form>
				</div>
    	<?php
    		}
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
