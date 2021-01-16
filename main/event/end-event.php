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

$error = FALSE;

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
        body{ font: sans-serif; text-align: center; padding: 2%;}
        .wrapper{ padding: 4%; }
    </style>

    <title>End Transaction</title>
</head>
<body>
    <div class="header">
        <?php
        $event_id = 0;
        if (isset($_SESSION["event_id"]))
        {
            $event_id = $_SESSION["event_id"];
            $sql = "UPDATE tbl_events SET event_status = 2 WHERE event_id=$event_id";
            if (mysqli_query($link, $sql))
            {
                $sql = "SELECT id FROM tbl_members WHERE event_id=$event_id";
                if ($result = mysqli_query($link, $sql))
                {
                    /* fetch associative array */
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $id = $row["id"];

                        $sql = "INSERT INTO tbl_expense_total (id, event_id, total_expense, notes) 
                                VALUES ($id, $event_id, (SELECT SUM(paid) FROM tbl_expense_history WHERE id=$id AND event_id=$event_id), 
                                (SELECT GROUP_CONCAT(notes) FROM tbl_expense_history WHERE id=$id AND event_id=$event_id))";
                        if(mysqli_query($link, $sql)){}
                        else
                        {
                            $error = TRUE;
                        }
                    }

                    /* free result set */
                    mysqli_free_result($result);
                }

                $sql = "SELECT id FROM tbl_members WHERE event_id=$event_id";
                if ($result = mysqli_query($link, $sql))
                {
                    /* fetch associative array */
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $id = $row["id"];

                        $sql = "INSERT INTO `tbl_transaction`(`id`, `event_id`, `owe_amount`, `receiver_id`, `transaction_status`) 
                                VALUES ($id, $event_id, (SELECT SUM(total_expense) FROM tbl_expense_total WHERE event_id=$event_id)/
                                (SELECT COUNT(*) FROM tbl_members WHERE event_id=$event_id), (SELECT creator_id FROM tbl_events WHERE event_id=$event_id), 1)";
                        if(mysqli_query($link, $sql)){}
                        else
                        {
                            $error = TRUE;
                        }
                    }

                    /* free result set */
                    mysqli_free_result($result);
                }

                $sql = "SELECT id,total_expense FROM tbl_expense_total WHERE event_id=$event_id";

                if ($result = mysqli_query($link, $sql))
                {
                    /* fetch associative array */
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $id = $row["id"];
                        $total_expense = $row["total_expense"];

                        $sql = "UPDATE tbl_transaction SET owe_amount=owe_amount-$total_expense WHERE id=$id AND event_id=$event_id";
                        if(mysqli_query($link, $sql)){}
                        else
                        {
                            $error = TRUE;
                        }
                    }

                    /* free result set */
                    mysqli_free_result($result);
                }


            }
            else
            {
                $error = TRUE;
            }

            unset($_SESSION["event_id"]);
        }
        else
        {
            $error = TRUE;
        }

        mysqli_close($link);
        ?>
    </div>
    <?php
    if ($error)
    {
        echo '<div class="alert alert-danger" role="alert">Something went wrong. Please contact admin!</div>';
    }
    else
    {
        echo "<div class='alert alert-success' role='alert'>Success</div>";
    }
    ?>
    <p>
        <a href="view.php" class="btn btn-success">Return to My View</a>
    </p>
</body>
</html>