<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../login.php");
    exit();
}

// Include database connection
include '../connect.php'; // Ensure this file initializes $con securelinclude '../timeout.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed.");
    }

    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        // Validate file type and size
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        $file_type = $_FILES['img']['type'];
        $file_size = $_FILES['img']['size'];

        if (!in_array($file_type, $allowed_types) || $file_size > $max_size) {
            die("Invalid file type or size.");
        }

        // Sanitize file name
        $img_name = basename($_FILES['img']['name']);
        $img_name = preg_replace("/[^a-zA-Z0-9\.\-_]/", "", $img_name); // Remove special characters
        $img_tmp = $_FILES['img']['tmp_name'];
        $upload_dir = "../uploads/";

        // Ensure upload directory exists and is secure
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true); // Use 0755 for better security
        }

        // Generate a unique file name to avoid overwriting
        $img_path = $upload_dir . uniqid() . '_' . $img_name;

        // Move uploaded file to uploads directory
        if (move_uploaded_file($img_tmp, $img_path)) {
            $img_db_path = "uploads/" . basename($img_path); // Save relative path in DB

            // Validate and sanitize date input
            $date = isset($_POST['date']) ? $_POST['date'] : '';
            if (!DateTime::createFromFormat('Y-m-d\TH:i', $date)) {
                die("Invalid date format.");
            }

            // Insert data into database using prepared statements
            $stmt = $con->prepare("INSERT INTO dash (img, date) VALUES (?, ?)");
            $stmt->bind_param("ss", $img_db_path, $date);
            
            if ($stmt->execute()) {
                echo "<script>alert('Announcement added successfully!');</script>";
            } else {
                echo "<script>alert('Error: " . addslashes($stmt->error) . "');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('File upload failed!');</script>";
        }
    } else {
        echo "<script>alert('Please select a valid image.');</script>";
    }
}

// Generate CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Fetch announcements from DB
$fetchQuery = "SELECT * FROM dash ORDER BY date";
$fetchResult = mysqli_query($con, $fetchQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="../uploads/blogo.png" type="x-icon">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>


<style>
:root {
  --primary-color: #4361ee;
  --primary-hover: #3a56d4;
  --success-color: #4cc9f0;
  --success-hover: #38b6db;
  --text-color: #2b2d42;
  --text-light: #8d99ae;
  --background: #f8f9fa;
  --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
  --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

body {
  font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
  margin: 0;
  padding: 0;
  background-color: var(--background);
  color: var(--text-color);
  line-height: 1.6;
}

.dash {
  background-color: #fff;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: var(--card-shadow);
  width: 95%;
  margin: 2rem auto;
  text-align: center;
  transition: var(--transition);
}

.dash h1 {
  font-size: 2.5rem;
  color: var(--text-color);
  margin-bottom: 1.5rem;
  font-weight: 700;
  position: relative;
  display: inline-block;
}

.dash h1::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 4px;
  background: linear-gradient(90deg, var(--primary-color), var(--success-color));
  border-radius: 2px;
}

.dashadd {
  background-color: var(--primary-color);
  color: white;
  border: none;
  padding: 0.8rem 1.8rem;
  font-size: 1rem;
  border-radius: 8px;
  cursor: pointer;
  margin-bottom: 2rem;
  transition: var(--transition);
  font-weight: 600;
  letter-spacing: 0.5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.dashadd:hover {
  background-color: var(--primary-hover);
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Announcements Grid Layout */
.announcements-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 1.5rem;
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
}

.announcement-item {
  background: white;
  border-radius: 12px;
  box-shadow: var(--card-shadow);
  padding: 1.5rem;
  text-align: center;
  transition: var(--transition);
  display: flex;
  flex-direction: column;
  height: 100%;
}

.announcement-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.announcement-item img {
  width: 100%;
  max-height: 250px;
  border-radius: 8px;
  margin-bottom: 1rem;
  object-fit: cover;
  aspect-ratio: 16/9;
}

.announcement-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.announcement-date {
  color: var(--text-light);
  font-size: 0.9rem;
  font-weight: 600;
  margin-top: 1rem;
}

.announcement-title {
  font-size: 1.2rem;
  font-weight: 600;
  margin: 0.5rem 0;
  color: var(--text-color);
}

.announcement-description {
  color: var(--text-light);
  font-size: 0.95rem;
  margin-bottom: 1rem;
}

/* Modal Styles */
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(5px);
  transition: opacity 0.3s ease;
}

.modal-content {
  background-color: #fff;
  margin: 10vh auto;
  padding: 2rem;
  border-radius: 12px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  transform: translateY(-20px);
  opacity: 0;
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.modal.show .modal-content {
  transform: translateY(0);
  opacity: 1;
}

.close6 {
  color: var(--text-light);
  float: right;
  font-size: 1.8rem;
  font-weight: bold;
  cursor: pointer;
  transition: var(--transition);
  line-height: 1;
}

.close6:hover {
  color: var(--text-color);
  transform: rotate(90deg);
}

.form1 label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  text-align: left;
  color: var(--text-color);
}

.form1 input,
.form1 textarea {
  width: 100%;
  padding: 0.8rem;
  margin-bottom: 1.5rem;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  box-sizing: border-box;
  transition: var(--transition);
  font-family: inherit;
}

.form1 input:focus,
.form1 textarea:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
}

.form1 textarea {
  min-height: 120px;
  resize: vertical;
}

.form1 button {
  background-color: var(--success-color);
  color: white;
  border: none;
  padding: 0.8rem;
  border-radius: 8px;
  cursor: pointer;
  width: 100%;
  font-size: 1rem;
  transition: var(--transition);
  font-weight: 600;
  letter-spacing: 0.5px;
}

.form1 button:hover {
  background-color: var(--success-hover);
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Responsive Adjustments */
@media (max-width: 992px) {
  .announcements-container {
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  }
}

@media (max-width: 768px) {
  .dash {
    width: 95%;
    padding: 1.5rem;
    margin: 1rem auto;
  }
  
  .dash h1 {
    font-size: 2rem;
  }
  
  .announcements-container {
    grid-template-columns: 1fr;
  }
  
  .modal-content {
    margin: 5vh auto;
    width: 95%;
    padding: 1.5rem;
  }
}

@media (max-width: 480px) {
  .dash h1 {
    font-size: 1.75rem;
  }
  
  .dashadd {
    padding: 0.7rem 1.5rem;
    font-size: 0.9rem;
  }
  
  .announcement-item {
    padding: 1.2rem;
  }
}
</style>

  <!-- Header -->
  <header>
    <div class="menu-container">
      <button class="burger-button" onclick="toggleSidebar()">☰</button>
    </div>
    <div class="dropdown">
      <button class="dropdown-button"><i class="fa-solid fa-user"></i></button>
      <div class="dropdown-content">
          <a href="../logout.php?logout=true"><i class="fa-solid fa-right-from-bracket"></i>&nbsp; Logout</a>
      </div>
    </div>
  </header>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="close">
        <span class="close-sidebar" onclick="toggleSidebar()"><i class="fa-solid fa-arrow-left"></i></span>
        <img src="../uploads/blogo.png" alt="Image" class="sidebar-image">
        <p class="sidebar-text"></p>
    </div>
    
    <div class="sidebar-content">
      <a href="dashboard.php" class="sidebar-item"><i class="fa-solid fa-house"></i>&nbsp; Dashboard</a>
      <a href="user.php" class="sidebar-item"><i class="fa-solid fa-user"></i>&nbsp; User</a>
      <a href="approval.php" class="sidebar-item"><i class="fa-solid fa-credit-card"></i>&nbsp; Online Approval</a>
      <a href="strecord.php" class="sidebar-item"><i class="fa-solid fa-clipboard-list"></i>&nbsp; Student Information</a>
      <a href="payrecord.php" class="sidebar-item"><i class="fa-solid fa-clipboard-list"></i>&nbsp; Payment Record</a>
      <a href="onfees.php" class="sidebar-item"><i class="fa-solid fa-clipboard-list"></i>&nbsp; Ongoing Fees</a>
    </div>
  </div>

<div class="main-content">
<div class="dash">
        <h1><i class="fa-solid fa-bullhorn"></i>&nbsp; Announcements</h1><br><br>
        <button class="dashadd" id="openModal"> Add Announcement</button><br><br><br><br><br>

        <ul>
            <?php while ($row = mysqli_fetch_assoc($fetchResult)) { ?>
                <li>
                    <img src="../<?php echo htmlspecialchars($row['img']); ?>" alt="Announcement"><br>
                    <p><?php echo date('F j, Y - h:i A', strtotime($row['date'])); ?></p> 
                </li>
            <?php } ?>
        </ul>
    </div>

<!-- Modal Form -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close6">&times;</span>
        <h2>Add Announcement</h2>
        <form class="form1" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <label for="img">Insert Image:</label>
            <input type="file" id="img" name="img" required>
            <br>
            <label for="datetime">Select Date and Time:</label>
            <input type="datetime-local" id="datetime" name="date" required>
            <br>
            <button type="submit">Submit</button>
        </form>
    </div>
</div>
<script>
document.getElementById("openModal").onclick = function() {
    document.getElementById("myModal").style.display = "block";
}
document.querySelector(".close6").onclick = function() {
    document.getElementById("myModal").style.display = "none";
}
window.onclick = function(event) {
    if (event.target == document.getElementById("myModal")) {
        document.getElementById("myModal").style.display = "none";
    }
}

// Set current date/time as default
window.onload = function() {
    const now = new Date();
    const formattedDate = now.toISOString().slice(0, 16);
    document.getElementById('datetime').value = formattedDate;
}
</script>
<script src="../js/script.js"></script>
</body>
</html>