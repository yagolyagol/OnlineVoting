 <?php
 include("connect.php");

 $mobile = $_POST['mobile'];
 $password = $_POST['password'];
 $role = $_POST['role'];
 
 $check = mysqli_query($connect,"SELECT * FROM user WHERE mobile='$mobile' AND password = '$password' AND role ='$role' ");

 if( mysqli_num_rows($check)>0){
    $userdata=

 }
 else{
      echo "<script>alert('imnvalid credentuals or user mot foumd'); window.location='../routes/register.html';</script>";
 }
 ?>