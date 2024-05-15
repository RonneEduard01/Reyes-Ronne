<?php

@include 'db_config.php';
$success_message = ''; // Variable to store success message

if(isset($_POST['submit'])){

   $name_last = mysqli_real_escape_string($conn, $_POST['lastname']);
   $name_first = mysqli_real_escape_string($conn, $_POST['firstname']);
   $name_middle = mysqli_real_escape_string($conn, $_POST['middlename']);
   $name_suffix = mysqli_real_escape_string($conn, $_POST['suffix']);
   $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
   $sex = mysqli_real_escape_string($conn, $_POST['gender']);
   $civilstatus = mysqli_real_escape_string($conn, $_POST['civilstatus']);
   $cpnum = mysqli_real_escape_string($conn, $_POST['cpnumber']);
   $birthplace = mysqli_real_escape_string($conn, $_POST['birthplace']);
   $address_no = mysqli_real_escape_string($conn, $_POST['houseno']);
   $address_street = mysqli_real_escape_string($conn, $_POST['street']);
   $address_village = mysqli_real_escape_string($conn, $_POST['village']);
   $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);
   $city = mysqli_real_escape_string($conn, $_POST['city']);
   $zipcode = mysqli_real_escape_string($conn, $_POST['zipcode']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = md5($_POST['password']);
   $cpass = md5($_POST['cpassword']);

   $select = " SELECT * FROM voter_registration WHERE email = '$email'";
   $query = " SELECT * FROM voter_registration WHERE cpnum = '$cpnum'";
   $result1 = mysqli_query($conn, $query);
   $result = mysqli_query($conn, $select);

// Process the ID Picture
if (isset($_FILES['valid_id']['tmp_name']) && isset($_FILES['2x2_id']['tmp_name'])) {
    $valid_id = file_get_contents($_FILES['valid_id']['tmp_name']);
    $id = file_get_contents($_FILES['2x2_id']['tmp_name']);
} else {
    // Handle the case where the files are not uploaded properly
    $error[] = 'Error uploading files';
}

   if(mysqli_num_rows($result) > 0){

   $error[] = 'email already exist!';
   }elseif(mysqli_num_rows($result1) > 0){
        $error[] = 'number already exist!';

   }
    else{

      if($pass != $cpass){
         $error[] = 'password not matched!';
      }else{

         $insert = "INSERT INTO voter_registration(name_last, name_first, name_middle, name_suffix, birthday, sex, civilstatus, cpnum , birthplace, address_no, address_street, address_village, barangay, city, zipcode, email, pass, valid_id,two_x_two_id) VALUES('$name_last ','$name_first ','$name_middle ','$name_suffix','$birthday ','$sex ','$civilstatus ','$cpnum ','$birthplace ','$address_no ','$address_street ','$address_village ','$barangay ','$city ','$zipcode ','$email ','$pass ',?,?)";
          
        $stmt = mysqli_prepare($conn, $insert);
    mysqli_stmt_bind_param($stmt, "ss", $valid_id , $id);
    mysqli_stmt_execute($stmt);
          
         //mysqli_query($conn, $insert);
        $success_message = 'Registration successful!';


    mysqli_stmt_close($stmt);
    mysqli_close($conn);
      }
   }
};
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Registration</title>
  <link rel="stylesheet" href="globals.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="register.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="navbar.css?v=<?php echo time(); ?>">
</head>
<body>
  <div class="user-login">
    <div class="navigation-menu">

      <a href="homepage.php">
        <img class="logo" src="img/logo.png" />
      </a>

      <div class="navbar">
        <div class="text-wrapper"><a href="homepage.php">HOME</a></div>
        <div class="text-wrapper"><a href="userlogin.php">USER</a></div>
        <div class="text-wrapper"><a href="adminlogin.php">ADMIN</a></div>
        <div class="text-wrapper"><a href="#">ABOUT US</a></div>
      </div>
      </div>
 <!--Register code here-->
      <p class="user-login-text" style="color: #002137; text-align: center; font-family: Inter; font-size: 30px; font-style: normal; font-weight: bold; line-height: normal;">Registration Form</p>
      <form action="" method="post" enctype="multipart/form-data">
              <div class="input-group">
                    <div class="input-field">
                      <input type="text" name="lastname" required spellcheck="false" oninput="changeLabel(this, 'Last Name', 'Enter Last Name')" style="width: 275px;">
                      <label>Enter Last Name</label>
                    </div>

                    <div class="input-field">
                      <input type="text" name="firstname" required spellcheck="false" oninput="changeLabel(this, 'First Name', 'Enter First Name')"style="width: 295px;">
                      <label>Enter First Name</label>
                    </div>
                  
                    <div class="input-field">
                      <input type="text" name="middlename" required spellcheck="false" oninput="changeLabel(this, 'Middle Name', 'Enter Middle Name')"style="width: 275px;">
                      <label>Enter Middle Name</label>
                    </div>

                    <div class="input-field">
                      <input type="text" name="suffix" required spellcheck="false" oninput="changeLabel(this, 'Suffix', 'Enter Suffix')"style="width: 125px;">
                      <label>Enter Suffix</label>
                    </div>
          </div>
          
          <div class="input-group">
                    <div class="input-field birthday-field">
                      <input type="date" name="birthday" required spellcheck="false" oninput="changeLabel(this, 'Birthday', 'Enter Birthday')" style="width: 160px;">
                      <label>Enter Birthday</label>
                    </div>

                    <div class="input-field birthday-field">
                        <select name="gender" required spellcheck="false" oninput="changeLabel(this, 'Gender', 'Enter Gender')" style="width: 150px;">
                            <option value="" disabled selected>Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                        <label>Select Gender</label>
                    </div>

                  
                    <div class="input-field birthday-field">
                        <select name="civilstatus" required spellcheck="false" oninput="changeLabel(this, 'Civil Status', 'Enter Civil Status')" style="width: 150px;">
                            <option value="" disabled selected>Select</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Others">Others</option>

                        </select>
                        <label>Select Civil Status</label>
                    </div>

                    <div class="input-field">
                      <input type="text" name="cpnumber" required spellcheck="false" oninput="changeLabel(this, 'Cellphone/Telephone Number', 'Enter Cellphone/Telephone Number')" style="width: 270px;">
                      <label>Enter Cellphone/Telephone Number</label>
                    </div>
              
                    <div class="input-field">
                      <input type="text" name="birthplace" required spellcheck="false" oninput="changeLabel(this, 'Place of Birth', 'Enter Place of Birth')"style="width: 220px;">
                      <label>Enter Place of Birth</label>
                    </div>
          </div>
                <div class="input-group">
                  <div class="input-field address-field">
                    <input type="text" name="houseno" required spellcheck="false" oninput="updateAddressLabel()" style="width: 115px;" placeholder="Lot , BLock , Phase , House No.">
                    <label id="houseLabel">Enter Address</label>
                  </div>

                  <div class="input-field address-field">
                    <input type="text" name="street" required spellcheck="false" oninput="updateAddressLabel()" style="width: 200px;" placeholder="Street Name">
                  </div>

                  <div class="input-field address-field">
                    <input type="text" name="village" required spellcheck="false" oninput="updateAddressLabel()" style="width: 240px;" placeholder="Subdivision , Village , Zone">
                  </div>

                    <div class="input-field">
                      <input type="text" name="barangay" required spellcheck="false" oninput="changeLabel(this, 'Barangay', 'Enter Barangay')"style="width: 200px;">
                      <label>Enter Barangay</label>
                    </div>

                    <div class="input-field">
                      <input type="text" name="city" required spellcheck="false" oninput="changeLabel(this, 'City/Municipality', 'Enter City/Municipality')"style="width: 200px;">
                      <label>Enter City/Municipality</label>
                    </div>

          </div>
                        <div class="input-group">
                    <div class="input-field">
                      <input type="text" name="zipcode" required spellcheck="false" oninput="changeLabel(this, 'Zip Code', 'Enter ZIP Code')" style="width: 115px;">
                      <label>Enter ZIP Code</label>
                    </div>

                    <div class="input-field">
                      <input type="text" name="email" required spellcheck="false" oninput="changeLabel(this, 'Email', 'Enter Email')"style="width: 310px;">
                      <label>Enter Email</label>
                    </div>
                  
                    <div class="input-field">
                      <input type="password" name="password" required spellcheck="false" oninput="changeLabel(this, 'Password', 'Enter Password')"style="width: 275px;">
                      <label>Password</label>
                    </div>

                    <div class="input-field">
                      <input type="password" name="cpassword" required spellcheck="false" oninput="changeLabel(this, 'Confirm Password', 'Enter Confirm Password')"style="width: 275px;">
                      <label>Enter Confirm Password</label>
                    </div>
          </div>
          <div class="input-group">
              <div class="input-file">
                <label for="fileInput">Upload 2x2 Picture : </label>
                <input type="file" name="2x2_id" required>
                
                <label for="fileInput">Upload 1 Valid ID : </label>
                <input type="file" name="valid_id" required>
            </div>
            <div class="input-button">

                <button type="submit" class="login-button" name="submit" value="Login">Register</button>
            </div>
          </div>

         
        
      </form>
    
      <script>
function changeLabel(input, newLabel, defaultLabel) {
  const label = input.nextElementSibling;
  if (input.value.trim() !== '') {
    label.classList.add('changed');
    label.textContent = newLabel;
  } else {
    label.classList.remove('changed');
    label.textContent = defaultLabel;
  }
}
    
function updateAddressLabel() {
  var houseInput = document.getElementsByName("house")[0].value;
  var streetInput = document.getElementsByName("street")[0].value;
  var villageInput = document.getElementsByName("village")[0].value;


  var houseLabel = document.getElementById("houseLabel");


  if (houseInput && streetInput && villageInput) {
    houseLabel.textContent = "Address";
  } else {
    houseLabel.textContent = "Enter Address";
  }
}
</script>
<?php
    if(!empty($success_message)){
        echo '<script>';
        echo 'var result = confirm("' . $success_message . '");';
        echo 'if (result) { window.location = "userlogin.php"; }'; // Redirect on OK
        echo '</script>';
    }

if(isset($error) && !empty($error)){
    echo '<script>';
    foreach($error as $error){
        echo 'alert("' . $error . '");';
    }
    echo '</script>';
}
?>
      
    </div>
    </body>
</html>
