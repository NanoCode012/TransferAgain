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
        body{ font: sans-serif; text-align: left; padding: 2%;}
        .wrapper{ padding: 4%; }
    </style>

    <title>Expense History</title>
</head>
<body>
    <div class="page-header">
        <h1>Expense History</h1>
    </div>
    <br><br>
    <table class="table">
        <tr>
            <th>Username</th>
            <th>Total Paid</th>
            <th>Notes</th>
        </tr>
        <?php
            if (isset($_SESSION["event_id"]))
            {
                $event_id = $_SESSION["event_id"];
                $sql = "SELECT id, total_expense, notes FROM tbl_expense_total WHERE event_id=$event_id";

                if ($result = mysqli_query($link, $sql))
                {
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        echo "<tr>";
                        $id = $row["id"];
                        $total_expense = $row["total_expense"];
                        $notes = $row["notes"];

                        $sql = "SELECT username FROM users WHERE id = $id LIMIT 1";

                        if ($result_name = mysqli_query($link, $sql))
                        {
                            if($val = mysqli_fetch_assoc($result_name))
                            {
                                echo  "<td>" . $val["username"] . "</td>";
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
                        echo  "<td>" . $total_expense . "</td>";
                        echo  "<td>" . $notes . "</td>";
                        echo "</tr>";
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

            mysqli_close($link);
        ?>
    </table>
    <?php
        if ($error)
        {
            echo '<div class="alert alert-danger" role="alert">Something went wrong. Please contact admin!</div>';
        }
    ?>
    <div>
        <a href="my-event.php" class="btn btn-warning">Back</a>
    </div>
    
    </body>
</html>