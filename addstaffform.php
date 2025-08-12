<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST['StaffID'];
  $email = $_POST['StaffEmail'];
  $password = $_POST['StaffPassword'];
  $name = $_POST['StaffName'];
  $phone = $_POST['StaffPhoneNum'];
  $house = $_POST['StaffHouseNum'];
  $postcode = $_POST['StaffPostcode']; // Fixed: was $city
  $city = $_POST['StaffCity']; // Fixed: was $postcode
  $state = $_POST['StaffState'];
  $position = $_POST['StaffPosition'];
  $dob = $_POST['StaffDOB'];

  $dbc = mysqli_connect("localhost", "root", "", "corm");
  if (mysqli_connect_errno()) {
    echo "<script>alert('Failed to connect to MySQL: " . mysqli_connect_error() . "');</script>";
  } else {
    // Fixed SQL query - make sure these column names match your database table
    $sql = "INSERT INTO staff (StaffID, StaffEmail, StaffPassword, StaffName, StaffPhoneNum, StaffHouseNum, StaffPostcode, StaffCity, StaffState, StaffPosition, StaffDOB) 
            VALUES ('$id', '$email', '$password', '$name', '$phone', '$house', '$postcode', '$city', '$state', '$position', '$dob')";
    $result = mysqli_query($dbc, $sql);
    if ($result) {
      echo "<script>alert('Staff record added successfully.'); window.location.href='addstaffform.php';</script>";
    } else {
      echo "<script>alert('Error adding staff record: " . mysqli_error($dbc) . "');</script>";
    }
  }
  mysqli_close($dbc);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Corm Cafe - Add Staff</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; text-decoration: none; }
    body { font-family: Calibri, sans-serif; background-color: #f3e7d3; min-height: 100vh; }
    .navbar { background-color: #DFD2B6; display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; border-bottom: 2px solid #8F3C15; }
    .logo-area { display: flex; align-items: center; }
    .logo-area img { height: 60px; margin-right: 10px; }
    .logo-area span { font-size: 40px; font-weight: bold; color: #2A211B; }
    .nav-links { display: flex; margin-left: auto; margin-right: 20px; }
    .nav-links a { color: #8F3C15; padding: 0 35px; font-weight: bold; font-size: 16px; }
    .menu-icon { font-size: 26px; cursor: pointer; color: #8F3C15; display: block; }
    .sidebar { height: 100%; width: 0; position: fixed; top: 0; right: 0; background-color: #A0815D; overflow-x: hidden; transition: 0.3s; padding-top: 60px; z-index: 1000; }
    .sidebar a { padding: 12px 25px; text-decoration: none; font-size: 18px; color: #8F3C15; display: block; transition: 0.2s; font-weight: bold; }
    .sidebar .closebtn { position: absolute; top: 15px; right: 20px; font-size: 30px; color: #2A211B; cursor: pointer; }
    .sidebar button { background-color: black; color: white; border: none; border-radius: 10px; padding: 10px; margin: 15px 25px; font-size: 16px; cursor: pointer; width: 100px; }
    .banner { width: 100%; height: 300px; background: url('aespa.jpeg') no-repeat center center; background-size: cover; }
    .main-content { display: flex; justify-content: center; align-items: center; padding: 50px 20px; }
    .form-container { background-color: #fff; padding: 40px; border-radius: 20px; max-width: 600px; width: 100%; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .form-container h2 { color: #6f2c10; margin-bottom: 30px; font-size: 32px; text-align: center; }
    .form-input, .form-select { width: 100%; padding: 15px 20px; margin-bottom: 20px; border-radius: 10px; border: 1px solid #ccc; font-size: 16px; }
    .form-input:focus, .form-select:focus { border-color: #8F3C15; outline: none; }
    .button-container { display: flex; justify-content: space-between; margin-top: 30px; }
    .button-container button { padding: 12px 30px; font-size: 16px; border-radius: 30px; cursor: pointer; border: none; font-weight: bold; }
    .btn-cancel, .btn-create { background-color: black; color: white; }
    @media (max-width: 600px) {
      .nav-links { display: none; }
      .form-container { padding: 25px; }
    }
  </style>
</head>
<body>
  <div class="navbar">
    <div class="logo-area">
      <img src="corm_logo.png" alt="Logo">
      <span>Corm</span>
    </div>
    <div class="nav-links">
      <a href="slide1.html">HOME</a>
      <a href="slide3contactus.html">CONTACT US</a>
      <a href="slide4aboutus.html">ABOUT US</a>
    </div>
    <div class="menu-icon" onclick="openSidebar()">&#9776;</div>
    <div id="mySidebar" class="sidebar">
      <span class="closebtn" onclick="closeSidebar()">×</span>
      <button onclick="alert('Login clicked')">Login</button>
      <button onclick="alert('Logout clicked')">Logout</button>
    </div>
  </div>

  <div class="banner"></div>

  <div class="main-content">
    <div class="form-container">
      <h2>ADD STAFF</h2>
      <form method="POST" action="">
        <input class="form-input" name="StaffID" type="text" placeholder="Staff ID" required />
        <input class="form-input" name="StaffEmail" type="email" placeholder="Staff Email" required />
        <input class="form-input" name="StaffPassword" type="password" placeholder="Staff Password" required />
        <input class="form-input" name="StaffName" type="text" placeholder="Staff Name" required />
        <input class="form-input" name="StaffPhoneNum" type="tel" placeholder="Phone Number" required />
        <input class="form-input" name="StaffHouseNum" type="text" placeholder="House Number" required />

        <select class="form-select" name="StaffCity" id="city" required onchange="updatePostcode()">
          <option value="" disabled selected>Select Town/City</option>
          <option value="Kuala Lumpur">Kuala Lumpur</option>
          <option value="Petaling Jaya">Petaling Jaya</option>
          <option value="Shah Alam">Shah Alam</option>
          <option value="Subang Jaya">Subang Jaya</option>
          <option value="Seremban">Seremban</option>
          <option value="Nilai">Nilai</option>
          <option value="Melaka">Melaka</option>
          <option value="Ipoh">Ipoh</option>
          <option value="Taiping">Taiping</option>
          <option value="George Town">George Town</option>
          <option value="Butterworth">Butterworth</option>
          <option value="Johor Bahru">Johor Bahru</option>
          <option value="Batu Pahat">Batu Pahat</option>
          <option value="Kota Bharu">Kota Bharu</option>
          <option value="Kuala Terengganu">Kuala Terengganu</option>
          <option value="Kuantan">Kuantan</option>
          <option value="Alor Setar">Alor Setar</option>
          <option value="Sungai Petani">Sungai Petani</option>
          <option value="Kangar">Kangar</option>
          <option value="Kuching">Kuching</option>
          <option value="Miri">Miri</option>
          <option value="Kota Kinabalu">Kota Kinabalu</option>
          <option value="Sandakan">Sandakan</option>
          <option value="Putrajaya">Putrajaya</option>
          <option value="Labuan">Labuan</option>
        </select>

        <select class="form-select" name="StaffPostcode" id="postcode" required>
          <option value="" disabled selected>Select Postcode</option>
        </select>

        <select class="form-select" name="StaffState" required>
          <option value="" disabled selected>Select State</option>
          <option value="Johor">Johor</option>
          <option value="Kedah">Kedah</option>
          <option value="Kelantan">Kelantan</option>
          <option value="Melaka">Melaka</option>
          <option value="Negeri Sembilan">Negeri Sembilan</option>
          <option value="Pahang">Pahang</option>
          <option value="Penang">Penang</option>
          <option value="Perak">Perak</option>
          <option value="Perlis">Perlis</option>
          <option value="Sabah">Sabah</option>
          <option value="Sarawak">Sarawak</option>
          <option value="Selangor">Selangor</option>
          <option value="Terengganu">Terengganu</option>
          <option value="Kuala Lumpur">Kuala Lumpur</option>
          <option value="Labuan">Labuan</option>
          <option value="Putrajaya">Putrajaya</option>
        </select>

        <select class="form-select" name="StaffPosition" required>
          <option value="" disabled selected>Select Staff Position</option>
          <option value="Manager">Manager</option>
          <option value="Supervisor">Supervisor</option>
          <option value="Barista">Barista</option>
          <option value="Chef">Chef</option>
          <option value="Cleaner">Cleaner</option>
          <option value="Cashier">Cashier</option>
        </select>

        <input class="form-input" name="StaffDOB" type="date" required />

        <div class="button-container">
          <button class="btn-cancel" type="button" onclick="window.location.href='slide1.html'">CANCEL</button>
          <button class="btn-create" type="submit">ADD STAFF</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openSidebar() {
      document.getElementById("mySidebar").style.width = "250px";
    }
    function closeSidebar() {
      document.getElementById("mySidebar").style.width = "0";
    }

    const cityToPostcode = {
      'Kuala Lumpur': '50000', 'Petaling Jaya': '46000', 'Shah Alam': '40000',
      'Subang Jaya': '47500', 'Seremban': '70300', 'Nilai': '71800',
      'Melaka': '75000', 'Ipoh': '30000', 'Taiping': '34000', 'George Town': '10000',
      'Butterworth': '12000', 'Johor Bahru': '80000', 'Batu Pahat': '83000',
      'Kota Bharu': '15000', 'Kuala Terengganu': '20000', 'Kuantan': '25000',
      'Alor Setar': '05000', 'Sungai Petani': '08000', 'Kangar': '01000',
      'Kuching': '93000', 'Miri': '98000', 'Kota Kinabalu': '88000',
      'Sandakan': '90000', 'Putrajaya': '62000', 'Labuan': '87000'
    };

    function updatePostcode() {
      const cityInput = document.getElementById('city').value.trim();
      const postcodeSelect = document.getElementById('postcode');
      postcodeSelect.innerHTML = '<option value="" disabled>Select Postcode</option>';
      if (cityInput in cityToPostcode) {
        const postcode = cityToPostcode[cityInput];
        const option = document.createElement('option');
        option.value = postcode;
        option.textContent = postcode;
        option.selected = true;
        postcodeSelect.appendChild(option);
      }
    }
  </script>
</body>
</html>