<?php if (!isset($_SESSION['student_id'])) { ?>

<div class="wrapper">
    <h2>Register</h2>
    <p>Please fill in your credentials to register.</p>
    <form action="?p=authmanager" method="post">
        <div class="form-group">
            <label>Student ID</label>
            <input type="text" name="student_id" class="form-control">
        </div>
        <div class="form-group">
            <button name="reg_student_id" type="submit" class="btn btn-primary">Next</button>
        </div>
        <p>Already have an account? <a href="?p=login">Sign in now</a>.</p>
        <p>If you forgot your password, you should inform admin.</p>
    </form>
</div>

<?php } else { ?>

<div class="wrapper">
    <h2>Register</h2>
    <p>Please fill in your credentials to register.</p>
    <form action="?p=authmanager" method="post">
        <div class="form-group">
            <label>Student Name</label>
            <input type="text" name="student_name" class="form-control" value="<?= $_SESSION['student_name'] ?>" readonly>
        </div>
        <div class="form-group">
            <label>Username (OPTIONAL)</label>
            <input type="text" name="username" class="form-control" placeholder="You can leave blank">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="form-group">
            <label>Bank</label>
            <select name="bank_name" class="form-control">
            <?php 
                foreach ($banks as $bank) {
                    echo '<option value="' . $bank . '">' . $bank . '</option>'; 
                }
            ?>
            </select>
        </div>
        <div class="form-group">
            <label>Bank Number</label>
            <input type="text" name="bank_num" class="form-control">
        </div>
        <div class="form-group">
            <button name="register" type="submit" class="btn btn-primary">Register</button>
            <a href="?p=logout" class="btn btn-dark">Cancel</a>
        </div>
        <p>Already have an account? <a href="?p=logout">Sign in now</a>.</p>
        <p>If you forgot your password, you should inform admin.</p>
    </form>
</div>

<?php } ?>