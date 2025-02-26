<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/ststyle.css">
    <link rel="stylesheet" href="../css/ststyles.css">
    <link rel="shortcut icon" href="../images/blogo.png" type="x-icon">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="burger-container">
            <div class="burger-button" onclick="toggleSidebar()">
                &#9776; 
            </div>
        </div>
        <div class="dropdown-container">
            <div class="dropdown">
                <img src="../images/blogo.png" class="dropbtn"> 
                <div class="dropdown-content">
                    <a href="stprofile.php"><i class="fa-solid fa-gear"></i> Profile</a>
                    <a href="cpassword.php"><i class="fa-solid fa-gear"></i> Settings</a>
                    <a href="login.php"><i class="fa-solid fa-gear"></i> Logout</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <div class="sidebar-content">
            <img src="../images/blogo.png" alt="Image" class="sidebar-image">
            <p class="sidebar-text">Your text goes here.</p>
        </div>
        <ul class="sidebar-nav">
              <li><a href="stbalance.php"><i class="fa-solid fa-clipboard-list"></i> Account Statement</a></li>
              <li><a href="stonfees.php"><i class="fa-solid fa-clipboard"></i> Outgoing Fees</a></li>
        </ul>
    </div>
        
    <div class="main-content"><br><br>
      <div class="container2">
        <h2>Do you want to change your password?</h2>
        <button class="btn" onclick="showPasswordForm()">Yes</button>
        
        <div id="passwordForm" class="hidden">
            <form>
                <div class="form-group2">
                    <label for="currentPassword">Current Password</label>
                    <i class="fa-solid fa-key"></i>
                    <input type="password" id="currentPassword" required>
                </div>
                <div class="form-group2">
                    <label for="newPassword">New Password</label>
                    <i class="fa-solid fa-key"></i>
                    <input type="password" id="newPassword" required>
                </div>
                <div class="form-group2">
                    <label for="confirmPassword">Confirm Password</label>
                    <i class="fa-solid fa-key"></i>
                    <input type="password" id="confirmPassword" required>
                </div>
                <button type="submit" class="btn2">Change Password</button>
            </form>
        </div>
    </div>
    </div>

    <div id="overlay" class="overlay" onclick="toggleSidebar()"></div>
    <script>
       function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const body = document.body;
        
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
        body.classList.toggle('open-sidebar');
    } 

      function showPasswordForm() {
          document.getElementById("passwordForm").classList.remove("hidden");
      }
  </script>
</body>
</html>

