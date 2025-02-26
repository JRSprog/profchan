<?php
include '../connect.php';

if(isset($_POST['submit']))
    {

        $uname=$_POST['uname'];
        $pass=$_POST['pass'];
        $sql="select * from user where uname='$uname' and pass='$pass'";
        $sqlid = "SELECT id from `user` WHERE uname = '$uname' AND pass = '$pass'";

        $result=mysqli_query($con,$sql);
        $id = mysqli_query($con , $sqlid);


        $stid1 = "";
           if(mysqli_num_rows($result) == 1){
	
	          $stid2 = mysqli_fetch_array($id);
	          $stid1 = ($stid2['id']);
	
	          header("Location:strecord.php?id=$stid1");
           }

           else {
	             echo ("<SCRIPT LANGUAGE='JavaScript'>
                   window.alert('Invalid Username or Password')
                  window.location.href='javascript:history.go(-1)';
                  </SCRIPT>");
               }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="shortcut icon" href="../images/blogo.png" type="x-icon">
    <title>Login</title>
</head>
<body>
    <div class="login-wrapper">
        <img src="../images/blogo.png" alt="Logo">
        <h2>Login your Account</h2>
        <form id="login-form" method="post" action="">
            <div class="form-group">
                <i class="fa-solid fa-user"></i>
                <input type="text" id="student-number" placeholder=" " name="uname" required>
                <label for="student-number">Username</label>
            </div>
            <div class="form-group password-container">
                <i class="fa-solid fa-lock"></i>
                <input type="password" id="password" placeholder=" " name="pass" required>
                <label for="password">Password</label>
                <button type="button" id="toggle-password" aria-label="Toggle password visibility">
                 <i class="fa-solid fa-eye-slash"></i>
                </button>
            </div>
            <button type="submit" id="login-btn" name="submit">
                <div class="spinner"></div>
                <span class="text">Login</span>
            </button>
            <a href="forgot.html" class="forgot-password">Forgot Password</a>
        </form>
    </div>

    <script>
        const loginForm = document.getElementById('login-form');
        const loginButton = document.getElementById('login-btn');
        const togglePassword = document.getElementById('toggle-password');

        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();
            loginButton.classList.add('loading');

            // Simulate loading (remove loading after 3 seconds)
            setTimeout(() => {
                loginButton.classList.remove('loading');
                window.location.href="strecord.php?id=2"
            }, 3000);
        });

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.type === "password" ? "text" : "password";
            passwordInput.type = type;

            const icon = this.querySelector('i');
            if (passwordInput.type === "password") {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>
