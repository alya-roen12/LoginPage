<?php
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "corm";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $action = $_POST['action'];
  
  if ($action == "signup") {
    // Handle login for customers
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT CustPassword FROM customer WHERE CustUsername = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $stored_password = $row['CustPassword'];
      
      if ($password === $stored_password) {
        // Valid user
        $_SESSION['username'] = $username;
        echo "<script>alert('Login successful!'); window.location.href='slide1.html';</script>";
        exit();
      } else {
        echo "<script>alert('Invalid username or password!');</script>";
      }
    } else {
      echo "<script>alert('Invalid username or password!');</script>";
    }
    $stmt->close();
    
  } elseif ($action == "create") {
    // Redirect to create account page
    echo "<script>window.location.href='createaccountform.php';</script>";
    exit();
    
  } elseif ($action == "signin") {
    // Handle login for staff/admin
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Get the selected role from the radio button
    $role = isset($_POST['individual']) ? $_POST['individual'] : '';
    
    if ($role == "Staff") {
      // For Staff: use email to authenticate since staff table doesn't have username
      $stmt = $conn->prepare("SELECT StaffEmail, StaffPassword, StaffName FROM staff WHERE StaffEmail = ?");
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_password = $row['StaffPassword'];
        
        if ($password === $stored_password) {
          $_SESSION['username'] = $row['StaffName']; // Use staff name as username
          $_SESSION['email'] = $row['StaffEmail'];
          $_SESSION['role'] = 'Staff';
          echo "<script>alert('Login successful!'); window.location.href='slide1.html';</script>";
          exit();
        } else {
          echo "<script>alert('Invalid email or password!');</script>";
        }
      } else {
        echo "<script>alert('Invalid email or password!');</script>";
      }
      $stmt->close();
      
    } elseif ($role == "Admin") {
      // For Admin: use email to authenticate
      $stmt = $conn->prepare("SELECT AdminEmail, AdminPassword, AdminName FROM admin WHERE AdminEmail = ?");
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_password = $row['AdminPassword'];
        
        if ($password === $stored_password) {
          $_SESSION['username'] = $row['AdminName']; // Use admin name as username
          $_SESSION['email'] = $row['AdminEmail'];
          $_SESSION['role'] = 'Admin';
          echo "<script>alert('Login successful!'); window.location.href='slide1.html';</script>";
          exit();
        } else {
          echo "<script>alert('Invalid email or password!');</script>";
        }
      } else {
        echo "<script>alert('Invalid email or password!');</script>";
      }
      $stmt->close();
      
    } else {
      echo "<script>alert('Please select a valid role!');</script>";
    }
  }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <style>
    /* Your existing CSS with responsive additions */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Montserrat", sans-serif;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-color: #dfd2b6;
    }

    .container {
      display: flex;
      width: 850px;
      height: 550px;
      border-style: solid;
      border-width: 1px;
      background-color: #a34f27;
    }

    .Form-box h1 {
      position: absolute;
      text-align: left;
      top: 130px;
      left: 53%;
      transform: translateX(-50%);
      font-size: 50px !important;
      color: #8f3c15;
    }

    .Form-box {
      right: 0;
      width: 60%;
      height: 100%;
      background-color: #ffffff;
      display: flex;
      align-items: center;
      color: #000000;
      text-align: left;
    }

    form {
      width: 100%;
    }

    .container h1 {
      font-size: 41px;
      margin: 10px;
    }

    .input-box {
      bottom: -10px;
      font-size: 17px;
      position: relative;
      margin: 20px;
    }

    .input-box input {
      width: 100%;
      font-size: 15px;
      padding: 13px;
      background-color: #ffffff;
      border-radius: 25px;
      border: 1px solid #ccc;
    }

    .choose-box label {
      font-size: 16px;
      margin: 25px;
      accent-color: #232323;
    }

    .button {
      display: none;
    }

    .button.customer {
      position: absolute;
      top: 550px;
      right: 250px;
      transform: translateX(-50%);
      text-align: center;
      display: flex;
      gap: 15px;
    }

    .button.staff-admin {
      position: absolute;
      top: 550px;
      right: 400px;
      text-align: center;
    }

    .button button {
      padding: 10px 20px;
      font-size: 16px;
      background-color: #000000;
      color: #e7e2e2;
      border: none;
      border-radius: 20px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .button button:hover {
      background-color: #6d6060;
    }

    .left-panel {
      width: 40%;
      height: 100%;
      background-color: #a34f27;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .logo {
      width: 119%;
      display: flex;
      justify-content: center;
      padding-left: 10px;
      position: relative;
      top: -50px;
    }

    .logo-img {
      max-width: 100px;
      height: auto;
    }

    .logo h1 {
      font-size: 55px;
      text-align: center;
      color: #2a211b;
    }

    .user {
      width: 119%;
      display: flex;
      justify-content: center;
      padding-left: 10px;
      position: relative;
      top: -30px;
    }

    .user-img {
      max-width: 400px;
      height: auto;
    }

    .user h1 {
      position: absolute;
      bottom: -80px;
      left: 49%;
      transform: translateX(-50%);
      text-align: center;
      color: #000000;
      font-size: 43.5px;
    }

    /* Responsive CSS additions */
    @media (max-width: 768px) {
      body {
        padding: 10px;
      }

      .container {
        width: 95%;
        max-width: 600px;
        height: auto;
        min-height: 550px;
        flex-direction: column;
      }

      .left-panel {
        width: 100%;
        height: 200px;
        padding: 15px;
      }

      .logo {
        width: 100%;
        top: -20px;
        padding-left: 0;
      }

      .logo h1 {
        font-size: 35px;
      }

      .logo-img {
        max-width: 60px;
      }

      .user {
        width: 100%;
        top: -10px;
        padding-left: 0;
      }

      .user-img {
        max-width: 80px;
      }

      .user h1 {
        font-size: 28px;
        bottom: -20px;
      }

      .Form-box {
        width: 100%;
        height: auto;
        padding: 20px;
        position: relative;
      }

      .Form-box h1 {
        position: relative;
        top: 0;
        left: 0;
        transform: none;
        font-size: 35px !important;
        text-align: center;
        margin-bottom: 20px;
      }

      .input-box {
        margin: 15px 0;
      }

      .choose-box label {
        margin: 15px 10px;
        font-size: 14px;
      }

      .button.customer {
        position: relative;
        top: 20px;
        right: 0;
        transform: none;
        justify-content: center;
        margin: 0 auto;
        width: 100%;
      }

      .button.staff-admin {
        position: relative;
        top: 20px;
        right: 0;
        display: flex;
        justify-content: center;
        margin: 0 auto;
        width: 100%;
      }

      .button button {
        padding: 12px 25px;
        font-size: 14px;
        min-width: 120px;
      }
    }

    @media (max-width: 480px) {
      .container {
        width: 98%;
        min-height: 500px;
      }

      .left-panel {
        height: 180px;
        padding: 10px;
      }

      .logo h1 {
        font-size: 28px;
      }

      .logo-img {
        max-width: 50px;
      }

      .user-img {
        max-width: 60px;
      }

      .user h1 {
        font-size: 22px;
        bottom: -15px;
      }

      .Form-box {
        padding: 15px;
      }

      .Form-box h1 {
        font-size: 28px !important;
      }

      .input-box {
        margin: 10px 0;
      }

      .input-box input {
        padding: 10px;
        font-size: 14px;
      }

      .choose-box label {
        margin: 10px 5px;
        font-size: 13px;
        display: block;
        text-align: center;
      }

      .button.customer {
        flex-direction: column;
        gap: 10px;
      }

      .button button {
        padding: 10px 20px;
        font-size: 13px;
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="left-panel">
      <div class="logo">
        <img src="asset/corm_logo_noword.png" alt="corm_logo" class="logo-img">
        <h1>Corm</h1>
      </div>
      <div class="user">
        <img src="asset/user_icon.png" alt="user_icon" class="user-img">
        <h1 id="userRole">Admin</h1>
      </div>
    </div>

    <div class="Form-box">
      <form name="form1" method="post" action="">
        <h1>Welcome!</h1>
        <div class="choose-box">
          <label><input type="radio" name="individual" value="Customer" onchange="displayRole(this.value)"> Customer</label>
          <label><input type="radio" name="individual" value="Staff" onchange="displayRole(this.value)"> Staff</label>
          <label><input type="radio" name="individual" value="Admin" onchange="displayRole(this.value)"> Admin</label>
        </div>

        <div class="input-box">
          <input type="text" name="username" placeholder="Enter your username" required> <br><br>
          <input type="email" name="email" placeholder="Enter your email" required> <br><br>
          <input type="password" name="password" placeholder="Password" required> <br><br>
        </div>

        <div class="button customer" id="customerButtons">
          <button type="submit" name="action" value="signup">Sign In</button>
          <button type="submit" name="action" value="create">Create Account</button>
        </div>

        <div class="button staff-admin" id="staffAdminButtons">
          <button type="submit" name="action" value="signin">Sign In</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function displayRole(selectedRole) {
      const userRoleElement = document.getElementById('userRole');
      userRoleElement.textContent = selectedRole;

      const customerButtons = document.getElementById('customerButtons');
      const staffAdminButtons = document.getElementById('staffAdminButtons');

      customerButtons.style.display = 'none';
      staffAdminButtons.style.display = 'none';

      if (selectedRole === 'Customer') {
        customerButtons.style.display = 'flex';
      } else if (selectedRole === 'Staff' || selectedRole === 'Admin') {
        staffAdminButtons.style.display = 'block';
      }
    }
  </script>
</body>
</html>