<?php
session_start();

if (!isset($_SESSION['userdata'])) {
    header("Location: ../index.html"); // redirect to login if session is not set
    exit;
}

$userdata = $_SESSION['userdata'];
$groupsdata = $_SESSION['groupsdata'];

$status = ($userdata['status'] == 0)
    ? '<b style="color:red">Not Voted</b>'
    : '<b style="color:green">Voted</b>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Integrity Polls - Dashboard</title>
    <link rel="stylesheet" href="../css/stylesheet.css">
    <style>
        /* Styles moved inline to simplify */
        #backbutton, #logoutbutton {
            padding: 10px;
            border-radius: 15px;
            width: 10%;
            background-color: rebeccapurple;
            color: blanchedalmond;
            margin: 10px;
        }
        #backbutton { float: left; }
        #logoutbutton { float: right; }
        #Profile {
            background-color: white;
            width: 30%;
            padding: 20px;
            float: left;
        }
        #Group {
            background-color: white;
            width: 60%;
            padding: 20px;
            float: right;
        }
        #votebtn, #voted {
            padding: 10px;
            border-radius: 15px;
            width: 20%;
            color: blanchedalmond;
            float: right;
        }
        #votebtn { background-color: rebeccapurple; }
        #voted { background-color: green; }
        #mainpanel {
            padding: 10px;
        }
    </style>
</head>
<body>
    <div id="mainSection">
        <center>
            <div id="headerSection">
                <a href="../login.html"><button id="backbutton">Back</button></a>
                <a href="logout.php"><button id="logoutbutton">Logout</button></a>
                <h1>Integrity Polls</h1>
            </div>
        </center>
        <hr>
        <div id="mainpanel">
            <div id="Profile">
                <center>
                    <img src="../uploads/<?php echo $userdata['profile_image']; ?>" width="100" height="100">
                </center><br><br>
                <b>Name:</b> <?php echo $userdata['name']; ?><br><br>
                <b>Mobile:</b> <?php echo $userdata['mobile']; ?><br><br>
                <b>Address:</b> <?php echo $userdata['address']; ?><br><br>
                <b>Status:</b> <?php echo $status; ?><br><br>
            </div>

            <div id="Group">
                <?php
                if (!empty($groupsdata)) {
                    foreach ($groupsdata as $groups) {
                        ?>
                        <div>
                            <img style="float: right" src="../uploads/<?php echo $groups['photo']; ?>" height="100" width="100">
                            <b>Group Name:</b> <?php echo $groups['name']; ?><br><br>
                            <b>Votes:</b> <?php echo $groups['votes']; ?><br><br>
                            <form action="../api/vote.php" method="post">
                                <input type="hidden" name="gvotes" value="<?php echo $groups['votes']; ?>">
                                <input type="hidden" name="gid" value="<?php echo $groups['id']; ?>">
                                <?php if ($userdata['status'] == 0) { ?>
                                    <input type="submit" name="votebtn" value="Vote" id="votebtn">
                                <?php } else { ?>
                                    <button disabled type="button" id="voted">Voted</button>
                                <?php } ?>
                            </form>
                        </div>
                        <hr>
                        <?php
                    }
                } else {
                    echo "<p>No candidate groups found.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>


