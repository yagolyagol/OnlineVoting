<?php
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
session_start();
if(!isset($_SESSION['userdata'])){
    header("location: ../");
}

$userdata = $_SESSION['userdata'];
$groupsdata = $_SESSION['groupsdata'];
if($_SESSION['$userdata']['status'] == 0){
    $status = '<b style ="color:red">Not Voted</b>';
}
else{
    $status = '<b style = "color:green">Voted</b>';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IntegrityPolls -Dashboard</title>
    <link rel="stylesheet" href="../css/stylesheet.css">
</head>
<body>
    <style>
        #backbutton {
            padding: 10px;
            border-radius: 15px;
            width: 10%;
            background-color: rebeccapurple;
            color: blanchedalmond;
            float: left;
            margin: 10px;
            
        }
        #logoutbutton{
             padding: 10px;
            border-radius: 15px;
            width: 10%;
            background-color: rebeccapurple;
            color: blanchedalmond;
            float: right;
            margin: 10px;
        }
        #Profile{
            background-color: white;
            width: 30%;
            padding: 20px;
            float: left;
        }
        #Group{
            background-color: white;
            width: 60%;
            padding: 20px;
            float: right;
        }
         #votebtn{
            padding: 10px;
            border-radius: 15px;
            width: 20%;
            background-color: rebeccapurple;
            color: blanchedalmond;
            float: right;
        }
        #mainpanel{
            padding: 10px;
        }
        #voted{
            padding: 10px;
            border-radius: 15px;
            width: 20%;
            background-color: green;
            color: blanchedalmond;
            float: right;
        }


    </style>
    <div id="mainSection">
         <center> 
    <div id="headerSection">
    <a href="../"><button id="backbutton">Back</button>></a>
    <a href="logout.php"><button id="logoutbutton"> Logout</button></a>
    <h1>Integrity Polls</h1>
        </div>
         </center>
         <hr>
    <div id="mainpanel">      
    <div id="Profile"></div>
     <center>
        <img src="../uploads/<?php echo $userdata['profile_image']; ?>" width= "100"  height="100">
     </center><br><br>
        <b>Name:</b><?php echo $userdata['name']?><br><br>
        <b>Mobile:</b><?php echo $userdata['mobile']?><br><br>
        <b>Address:</b><?php echo $userdata['address']?><br><br>
        <b>Status:</b><?php echo $userdata['status']?><br><br>
     </div>

    <div id="Group"></div>
      <?php
            if($_SESSION['groupsdata']){
               for($i=0; $i<count($groupsdata); $i++){
                 ?>
                <div>
                  <img style="float: right" src="../uploads/<?php echo $groupsdata[$i]['photo'] ?>" height="100" width="100">
                   <b>Group Name: </b><?php echo $groupsdata[$i]['name'] ?><br><br>
                   <b>Votes: </b><?php echo $groupsdata[$i]['votes'] ?><br><br>
                   <form action="../api/vote.php" >
                       <input type="hidden" name="gvotes" value="<?php echo $groupsdata[$i]['votes'] ?>">
                       <input type="hidden" name ="gid" value="<?php echo $groupsdata[$i]['id'] ?>">
                       <?php
                           if($_SESSION['userdata']['status'] == 0){
                            ?>
                             <input type="submit" name="votebtn" value="Vote" id="votebtn">
                            <?php

                           }
                           else{
                             ?>
                             <button disabled type="button" name="votebtn" value="Vote" id="voted"> Voted</button>
                              <?php
                           }
                        ?>
                   </form>                   
                </div>
                 <hr>
                 <?php
              }
         }
          else{
         }
          ?>
      </div>
    </div>
 </div>
    

    
</body>
</html>