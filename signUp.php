<?php


$msgBox = '';


//include notification page
include('includes/notification.php');

//Include Function page
include('includes/Functions.php');

include('includes/db.php');

//User Signup
if(isset($_POST['signup'])){
	if($_POST['email'] == '' || $_POST['firstname'] == '' || $_POST['lastname'] == '' || $_POST['password'] == '' || $_POST['rpassword'] == '') {
				$msgBox = alertBox($SignUpEmpty);
			} else if($_POST['password'] != $_POST['rpassword']) {
				$msgBox = alertBox($PwdNotSame);

			} else {
				// Set new account
				$Email 		= $mysqli->real_escape_string($_POST['email']);
				$Password 	= encryptIt($_POST['password']);
				$FirstName	= $mysqli->real_escape_string($_POST['firstname']);
				$LastName	= $mysqli->real_escape_string($_POST['lastname']);
				$Currency	= $mysqli->real_escape_string($_POST['currency']);
				$img_name = $_FILES['imglink']['name'];
				$img_size = $_FILES['imglink']['size'];
				$img_tmp = $_FILES['imglink']['tmp_name'];
				$directory = 'uploads/';
				$target_file = $directory.$img_name;

				//Check if already register

				$sql="Select Email from user Where Email = '$Email'";

				 $c= mysqli_query($mysqli, $sql);

                    if (mysqli_num_rows($c) >= 1) {

                        $msgBox = alertBox($AlreadyRegister);
                    }
										if (file_exists($target_file)) {
											array_push($errors, "image file already exists");
										}
										if ($img_size>2097152) {
											array_push($errors, "image file size larger than 2MB, select another");
										}
                    else{

				// add new account
				$sql="INSERT INTO user (FirstName, LastName, Email, Password, Currency, imglink) VALUES (?,?,?,?,?)";
				if($statement = $mysqli->prepare($sql)){
					//bind parameters for markers, where (s = string, i = integer, d = double,  b = blob)
					$statement->bind_param('sssss', $FirstName, $LastName, $Email, $Password, $Currency, $target_file);
					$statement->execute();
				}
				$msgBox = alertBox($SuccessAccount);
				}
			}
}



?>




<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Money Manager Sign Up</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/custom.css" rel="stylesheet">

		<!--profile pic css -->
		<link href="css/login.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
		<script type="text/javascript"> //uploading profile picture
	    function previewImage(){
	      var oFReader = new FileReader();
	      oFReader.readAsDataURL(document.getElementById('imglink').files[0]);

	      oFReader.onload = function (oFREvent){
	        document.getElementById('uploadPreview').src = oFREvent.target.result;
	      };

	    };

	  </script>

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="login-panel panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center"><span class="glyphicon glyphicon-lock"></span> <?php  echo $CreateAnAccount; ?></h3>
												<center>
													upload profile picture
												<img id="uploadPreview" src="images\avatar.png" alt="profile picture" class="avatar"><br>
									      <input type="file" name="imglink" id="imglink" accept=".jpg, .jpeg, .png" onchange="previewImage();">
									    </center>
                    </div>
                    <div class="panel-body">
						<?php if ($msgBox) { echo $msgBox; } ?>
                        <form method="post" action="" role="form">
                            <fieldset>
                                <div class="form-group col-lg-6">
                                    <label for="email"><?php  echo $Emails; ?></label>
                                    <input class="form-control"  placeholder="<?php  echo $Emails; ?>" name="email" type="email" autofocus>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="email"><?php  echo $FirstNames; ?></label>
                                    <input class="form-control"  placeholder="<?php  echo $FirstNames; ?>" name="firstname" type="text" >
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="email"><?php  echo $LastNames; ?></label>
                                    <input class="form-control"  placeholder="<?php  echo $LastNames; ?>" name="lastname" type="text" >
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="email"><?php  echo $Currencys; ?></label>
                                    <select class="form-control bold"  name="currency">
										<option value="BIF">Burundian Franc (BIF)</option>
										<option value="BWP">Botswanan Pula (BWP)</option>
										<option value="DJF">Djiboutian Franc (DJF)</option>
										<option value="ETB">Ethiopian Birr (ETB)</option>
										<option value="$">Dollar ($)</option>
										<option value="TZS">Tanzanian Shilling (TZS)</option>
										<option value="UGX">Ugandan Shilling (UGX)</option>
										<option selected="" value="KES">Kenyan Shilling (KES)</option>
										<option value="S">South African Rand (ZAR)</option>

									</select>
                                </div>
                                <div class="form-group col-lg-6">
                                     <label for="password"><?php  echo $Passwords; ?></label>
                                    <input class="form-control"  placeholder="<?php  echo $Passwords; ?>" name="password" type="password" value="">
                               </div>
                                <div class="form-group col-lg-6">
                                     <label for="password"><?php  echo $RepeatPassword; ?></label>
                                    <input class="form-control"  placeholder="<?php  echo $RepeatPassword; ?>" name="rpassword" type="password" value="">
                               </div>
                               <hr>
                                <button type="submit" name="signup" class="btn btn-success btn-block"><span class="glyphicon glyphicon-log-in"></span>  <?php  echo $Save; ?></button>                                 <hr>

                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>

</body>

</html>
