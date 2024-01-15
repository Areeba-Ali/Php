<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
        if(isset($_POST["submit"]))
        {
          $username=$_POST["full_name"];
          $email=$_POST["Email"];
          $password=$_POST["Password"];
          $conpassword=$_POST["Confirm_password"];

          $passwordHash = password_hash($password, PASSWORD_DEFAULT);

          $error=array();

          if(empty( $username) || empty( $email) || empty( $password) || empty( $conpassword))
          {
            array_push($error,"All fields are required");
          }
          if(!filter_var($email, FILTER_VALIDATE_EMAIL))
          {
            array_push($error, "Email is not valid");
          }
          if(strlen($password)<8)
          {
            array_push($error, "Password must be atleast 8 charaters...");
          }
          if ($password!==$conpassword) {
            array_push($error,"Password does not match");
           }
           require_once "database.php";
           $sql = "SELECT * FROM form WHERE email = '$email'";
           $result=mysqli_query($conn, $sql);
           $rowCount = mysqli_num_rows($result);
           if ($rowCount>0) {
            array_push($error,"Email already exists!");
           }
           if (count($error)>0) {
            foreach ($error as  $errorr) {
                echo "<div class='alert alert-danger'>$errorr</div>";
            }
           }else{
            
            $sql = "INSERT INTO form (`full_name`, `Email`, `Password`) VALUES ( ? , ? , ?)";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"sss",$username, $email, $passwordHash);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>You are registered successfully.</div>";
            }else{
                die("Something went wrong");
            }
           }
        }
        ?>
        <form action="registration.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="full_name" placeholder="User Name">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="Email" placeholder="Email Address">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="Password" placeholder="Password">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="Confirm_password" placeholder="Confirm Password">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
        </form>
        <div>
        <div><p>Already Registered <a href="login.php">Login Here</a></p></div>
      </div>
    </div>
</body>
</html>