<?php
include("connect.php");

// Get and sanitize input
$name = mysqli_real_escape_string($connect, $_POST['name']);
$mobile = mysqli_real_escape_string($connect, $_POST['mobile']);
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$address = mysqli_real_escape_string($connect, $_POST['address']);
$role = mysqli_real_escape_string($connect, $_POST['role']);

// Validate and process uploaded image
$image = $_FILES['Photo']['name'];
$tmp_name = $_FILES['Photo']['tmp_name'];
$image_ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
$allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

if (!in_array($image_ext, $allowed_ext)) {
    echo "<script>alert('Invalid image format.'); window.location='../routes/register.html';</script>";
    exit();
}

$new_image_name = uniqid() . '.' . $image_ext;
$upload_path = "../uploads/" . $new_image_name;

// Validate passwords match
if ($password !== $cpassword) {
    echo "<script>alert('Passwords do not match!'); window.location='../routes/register.html';</script>";
    exit();
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check for duplicate mobile number
$check_mobile = mysqli_query($connect, "SELECT * FROM user WHERE mobile='$mobile'");
if (mysqli_num_rows($check_mobile) > 0) {
    echo "<script>alert('Mobile number already registered!'); window.location='../routes/register.html';</script>";
    exit();
}

// Move uploaded file
if (move_uploaded_file($tmp_name, $upload_path)) {
    // Use prepared statement to prevent SQL injection
    $stmt = $connect->prepare("INSERT INTO user (name, mobile, password, address, Photo, role, Status, votes) VALUES (?, ?, ?, ?, ?, ?, 0, 0)");
    $stmt->bind_param("ssssss", $name, $mobile, $hashed_password, $address, $new_image_name, $role);
    
    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location='../';</script>";
    } else {
        echo "<script>alert('Error in registration.'); window.location='../routes/register.html';</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('Failed to upload image.'); window.location='../routes/register.html';</script>";
}
?>

/*include("connect.php");
$name = $_POST['name'];
$mobile = $_POST['mobile'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$address = $_POST['address'];
$image = $_FILES['Photo']['name'];
$tmp_name =$_FILES['Photo']['tmp_name'];
$role = $_POST['role'];

if ($password==$cpassword){//( is_uploaded_file($tmp_name)){
    move_uploaded_file($tmp_name, "../uploads/$image");
     $insert = mysqli_query($connect, "INSERT INTO user (name, mobile, password, address, Photo, role, Status, votes) 
     VALUES('$name', '$mobile','$password','$address','$image', '$role', 0, 0)");   
     if($insert){
        echo '
           <script>
              alert("Registration Successful!!");
              window.location = "../";
            </script>
        ';
    }
    else{
        echo '
            <script>
                alert("Error in Registration");
                 window.location = "../routes/register.html";
             </script>
        ';
    }
}
else{
    echo '
        <script>
            alert("Password did not match!!");
             window.location = "../routes/register.html";
        </script>
    ';
   
}
?>*/