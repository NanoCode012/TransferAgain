<?php
// Initialize the session
session_start();
 
// Include config file
require_once "../../config.php";

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

$event_name = $event_name_err = "";
$error = FALSE;

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    if(empty(trim($_POST["event_name"]))){
        $event_name_err = "Please enter an event name.";
    } else{
        $event_name = trim($_POST["event_name"]);

        $param_event_name = $event_name . "_" . date("d_M_Y");
        
        $sql = "INSERT INTO `tbl_events`(`creator_id`, `event_name`) VALUES (?, ?)";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "is", $_SESSION["id"], $param_event_name);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){

                $sql = "SELECT * FROM tbl_events ORDER BY event_id DESC LIMIT 1";
                $event_id = 0;
                if ($result = mysqli_query($link, $sql)) {

                    /* fetch associative array */
                    while ($row = mysqli_fetch_assoc($result)){$event_id = $row["event_id"];}

                    /* free result set */
                    mysqli_free_result($result);
                }
                

                $sql = "INSERT INTO `tbl_members`(`id`, `event_id`) VALUES (?, ?)";
                if($stmt = mysqli_prepare($link, $sql)){
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "ii", $_SESSION["id"], $event_id);
                    // Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt)){
                        header("location: success-create-event.php");
                    }
                    else
                    {
                        $error = TRUE;
                    }
                }
                else
                {
                    $error = TRUE;
                }
            }
            else
            {
                $error = TRUE;
            }
        }
        else
        {
            $error = TRUE;
        }
    }

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <style type="text/css">
        body{ font: sans-serif; text-align: center;}
        .wrapper{ padding: 4%; }
    </style>

    <title>Create Event</title>
</head>
<body>
    <?php
        if ($error)
        {
            echo '<div class="alert alert-danger" role="alert">Something went wrong. Please contact admin!</div>';
        }
    ?>
    <div class="wrapper">
        <h2>Create Event</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($event_name_err)) ? 'has-error' : ''; ?>">
                <label>Event Name</label>
                <input type="text" name="event_name" class="form-control" style="text-align:center;" value="<?php echo $event_name; ?>" required>
                <span class="help-block"><?php echo $event_name_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Create">
                <a href="view.php" class="btn btn-warning">Back</a>
            </div>
        </form>
    </div>   
</body>
</html>