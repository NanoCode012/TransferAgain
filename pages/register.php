<div class="wrapper">
    <h2>Register</h2>
    <p>Please fill in your credentials to register.</p>
    <form action="?p=authmanager" method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control">
        </div>    
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="form-group">
            <button name="register" type="submit" class="btn btn-primary">Register</button>
        </div>
        <p>Already have an account? <a href="?p=login">Sign in now</a>.</p>
        <p>If you forgot your password, you should inform admin.</p>
    </form>
</div>