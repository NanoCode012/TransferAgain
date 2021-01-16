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
$event_id =  "";
$end_event_err = "";
$resume_event_err = "";
$hide_event_err = "";
$error = FALSE;

unset($_SESSION["event_id"]);

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (isset($_POST["events"]) && isset($_POST["action_type"]))
    {
        if ($_POST["action_type"] == "add-transaction")
        {
            $_SESSION["event_id"] = $_POST["events"];
            header("location: add-transaction.php"); 
        }
        else if ($_POST["action_type"] == "end-event")
        {              
            $param_event_id = $_POST["events"];
            $sql = "SELECT creator_id FROM tbl_events WHERE event_id=$param_event_id";
            if ($result = mysqli_query($link, $sql))
            {
                if($val = mysqli_fetch_assoc($result))
                {
                    if ($val["creator_id"] == $_SESSION["id"])
                    {
                        $_SESSION["event_id"] = $_POST["events"];
                        header("location: end-event.php");
                    }
                    else
                    {
                        $end_event_err = "You do not have the permissions to end this event.";
                    }
                }
                else
                {
                    $error = TRUE;
                }
                mysqli_free_result($result);
            }
            else
            {
                $error = TRUE;
            }
        }
        else if ($_POST["action_type"] == "open-expense-history")
        {
            $_SESSION["event_id"] = $_POST["events"];
            header("location: open-expense-history.php"); 
        }
        else if ($_POST["action_type"] == "open-expense-total")
        {
            $_SESSION["event_id"] = $_POST["events"];
            header("location: open-expense-total.php");
        }
        else if ($_POST["action_type"] == "open-member-list") 
        {
            $_SESSION["event_id"] = $_POST["events"];
            header("location: open-member-list.php"); 
        }
        else if ($_POST["action_type"] == "open-event-id") 
        {
            $_SESSION["event_id"] = $_POST["events"];
            header("location: open-event-id.php"); 
        }
        else if ($_POST["action_type"] == "open-transaction")
        {
            $_SESSION["event_id"] = $_POST["events"];
            header("location: open-transaction.php");
        }
        else if ($_POST["action_type"] == "hide-event")
        {
            $param_event_id = $_POST["events"];
            $sql = "SELECT creator_id FROM tbl_events WHERE event_id=$param_event_id";
            if ($result = mysqli_query($link, $sql))
            {
                if($val = mysqli_fetch_assoc($result))
                {
                    if ($val["creator_id"] == $_SESSION["id"])
                    {
                        $_SESSION["event_id"] = $_POST["events"];
                        header("location: hide-event.php");
                    }
                    else
                    {
                        $hide_event_err = "You do not have the permissions to hide this event.";
                    }
                }
                else
                {
                    $error = TRUE;
                }
                mysqli_free_result($result);
            }
            else
            {
                $error = TRUE;
            }
        }
        else if ($_POST["action_type"] == "resume-event")
        {
            $param_event_id = $_POST["events"];
            $sql = "SELECT creator_id FROM tbl_events WHERE event_id=$param_event_id";
            if ($result = mysqli_query($link, $sql))
            {
                if($val = mysqli_fetch_assoc($result))
                {
                    if ($val["creator_id"] == $_SESSION["id"])
                    {
                        $_SESSION["event_id"] = $_POST["events"];
                        header("location: resume-event.php");
                    }
                    else
                    {
                        $resume_event_err = "You do not have the permissions to resume this event.";
                    }
                }
                else
                {
                    $error = TRUE;
                }
                mysqli_free_result($result);
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

    //unset($_SESSION["num_of_events"]);
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
        body{ font: sans-serif; text-align: left; padding: 2%;}
        .wrapper{ padding: 4%; }
    </style>

    <title>My Event</title>
</head>
<body>
    <?php
        if ($error)
        {
            echo '<div class="alert alert-danger" role="alert">Something went wrong. Please contact admin!</div>';
        }
    ?>
    <div class="page-header">
        <h1>My Event</h1>
    </div>
    <div class="dropdown">
        <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h3>Ongoing</h3>
            <br>
            <select id="select" name="action_type">
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <option class="dropdown-item" value="add-transaction">Add Transaction</option>
                    <option class="dropdown-item" value="open-expense-history">Open Expense History</option>
                    <option class="dropdown-item" value="open-member-list">Open Member List</option>
                    <option class="dropdown-item" value="open-event-id">Open Event ID</option>
                    <option class="dropdown-item" value="end-event">End Event</option>
                </div>
            </select>
            <select id="select" name="events">
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php
                        $param_id = $_SESSION["id"];
                        $sql = "SELECT * FROM tbl_members WHERE id = $param_id";

                        if ($result = mysqli_query($link, $sql))
                        {
                            while ($row = mysqli_fetch_assoc($result))
                            {
                                $param_event_id = $row["event_id"];
                                $sql = "SELECT event_name FROM tbl_events WHERE event_id = $param_event_id and event_status = 1 LIMIT 1";

                                if ($result_name = mysqli_query($link, $sql))
                                {
                                    if($val = mysqli_fetch_assoc($result_name))
                                    {
                                        echo  "<option class='dropdown-item' value='" . $param_event_id . "'>" . $val["event_name"] . "</option>";
                                    }
                                }
                            }

                            /* free result set */
                            mysqli_free_result($result);
                        }
                        else
                        {
                            $error = TRUE;
                        }
                    ?>
                </div>
                <!-- <div class="form-group">
                    <br><br>
                    <input type="submit" name="add-transaction" class="btn btn-primary" value="Add Transaction">
                    <input type="submit" name="open-expense-history" class="btn btn-secondary" value="Open Expense History">
                    <input type="submit" name="open-member-list" class="btn btn-success" value="Open Member List">
                    <input type="submit" name="open-event-id" class="btn btn-info" value="Open Event ID">
                    <input type='submit' name='end-event' class='btn btn-danger' value='End Event'>
                </div> -->
            </select>
            <br>
            <input type='submit' class='btn btn-primary' value='Go'>
        </form>
    </div>
    <span class="help-block"><?php echo $end_event_err; ?></span>

    <div class="dropdown">
        <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h3>Finished</h3>
            <br>
            <select class="select" id="select" name="action_type">
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <option class="dropdown-item" value="open-expense-history">Open Expense History</option>
                    <option class="dropdown-item" value="open-expense-total">Open Expense Total</option>
                    <option class="dropdown-item" value="open-member-list">Open Member List</option>
                    <option class="dropdown-item" value="open-transaction">Open Transaction</option>
                    <option class="dropdown-item" value="resume-event">Resume Event</option>
                    <option class="dropdown-item" value="hide-event">Hide Event</option>
                </div>
            </select>
            <select class="select" id="select" name="events">
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php
                        $param_id = $_SESSION["id"];
                        $sql = "SELECT * FROM tbl_members WHERE id = $param_id";

                        if ($result = mysqli_query($link, $sql))
                        {
                            while ($row = mysqli_fetch_assoc($result))
                            {
                                $param_event_id = $row["event_id"];
                                $sql = "SELECT event_name FROM tbl_events WHERE event_id = $param_event_id and event_status = 2 LIMIT 1";

                                if ($result_name = mysqli_query($link, $sql))
                                {
                                    if($val = mysqli_fetch_assoc($result_name))
                                    {
                                        echo  "<option class='dropdown-item' value='" . $param_event_id . "'>" . $val["event_name"] . "</option>";
                                    }
                                }
                            }

                            /* free result set */
                            mysqli_free_result($result);
                        }

                        mysqli_close($link);
                    ?>
                </div>
                <!-- <div class="form-group">
                    <input type="submit" name="open-expense-history" class="btn btn-primary" value="Open Expense History">
                    <input type="submit" name="open-expense-total" class="btn btn-secondary" value="Open Expense Total">
                    <input type="submit" name="open-member-list" class="btn btn-success" value="Open Member List">
                    <input type="submit" name="open-transaction" class="btn btn-info" value="Open Transaction">
                    <input type='submit' name='resume-event' class='btn btn-warning' value='Resume Event'>
                    <input type='submit' name='hide-event' class='btn btn-danger' value='Hide Event'>
                </div> -->
            </select>
            <br>
            <input type='submit' class='btn btn-primary' value='Go'>
        </form>
    </div>
    <span class="help-block"><?php echo $resume_event_err; ?></span>
    <span class="help-block"><?php echo $hide_event_err; ?></span>

    <br>
    <div>
        <a href="view.php" class="btn btn-warning">Back</a>
    </div>
</body>
</html>