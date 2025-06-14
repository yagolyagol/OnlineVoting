<?
include("connect.php");

$name = $_POST['Name'];
$mobile = $_POST['Mobile'];
$password = $_POST['Password'];
$cpassword = $_POST['Cpassword'];
$address = $_POST['Address'];
$image = $_FILES['name']['Photo'];
$temp_name =$_FILES['temp_name']['Photo'];
$role = $_POST['role'];

if($password == $cpassword){
    move_uploaded_file($temp_name,"../uploads/$image ");
    $insert = mysqli_query($connect, "INSERT INTO user (Name, Mobile, Password, Address,Photo,Role ,Status) VALUES('$name', '$mobile','$password','$address','$image',0,0)");
    if($insert){
        echo'
           <script>
              alert("Registration Successful");
              window.location = "../";
            </script>
        ';
    }
    else{
        echo'
            <script>
                alert("Error in Registration");
                 window.location = "../routes/register.html";
             </script>
        ';
    }
}

else{
    echo'
        <script>
            alert("Password did not match!!");
             window.location = "../routes/register.html";
        </script>
    ';
   
}
?>