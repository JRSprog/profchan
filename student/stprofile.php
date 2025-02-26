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
      <div class="container">
        <h2>Your Profile</h2>
        <label class="profile-picture">
            <span class="camera-icon">📷</span>
            <input type="file" id="profilePicture" name="profilePicture" accept="image/*">
        </label>
        <form>
            <div class="form-group">
                <label for="studentID">Student ID</label>
                <i class="fa-solid fa-id-card"></i>
                <input type="text" id="studentID" name="studentID" required readonly tabindex="-1">
            </div>
            <div class="form-group">
                <label for="lastName">Last Name</label>
                <i class="fa-solid fa-clipboard"></i>
                <input type="text" id="lastName" name="lastName" required readonly tabindex="-1">
            </div>
            <div class="form-group"> 
                <label for="firstName">First Name</label>
                <i class="fa-solid fa-clipboard"></i>
                <input type="text" id="firstName" name="firstName" required readonly tabindex="-1">
            </div>
            <div class="form-group">
                <label for="middleName">Middle Name</label>
                <i class="fa-solid fa-clipboard"></i>
                <input type="text" id="middleName" name="middleName" required readonly tabindex="-1">
            </div>
            <div class="form-group">
                <label for="courseLevel">Course Level</label>
                <input type="text" id="courseLevel" name="courseLevel" required readonly tabindex="-1">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <i class="fa-solid fa-location-dot"></i>
                <input type="text" id="address" name="address" required readonly tabindex="-1">
            </div>
            <div class="form-group">
                <label for="birthdate">Birthdate</label>
                <input type="date" id="birthdate" name="birthdate" required readonly tabindex="-1">
            </div>
            <div class="form-group">
                <label for="school">School</label>
                <i class="fa-solid fa-school"></i>
                <input type="text" id="school" name="school" required readonly tabindex="-1">
            </div>
        </form>
    </div>
    </div>

    <div id="overlay" class="overlay" onclick="toggleSidebar()"></div>
<script>// Toggle sidebar visibility
  function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const body = document.body;
        
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
        body.classList.toggle('open-sidebar');
    }

    document.addEventListener("DOMContentLoaded", function() {
      console.log("Page Loaded");
  });

    window.onload = function() {
        window.scrollTo(0, 0);
    };
</script>

</body>
</html>

