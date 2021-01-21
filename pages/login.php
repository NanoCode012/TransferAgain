<div class="wrapper">
    <h2>Login</h2>
    <p>Please fill in your credentials to login.</p>
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
            <button name="login" type="submit" class="btn btn-primary">Login</button>
        </div>
        <p>Don't have an account? <a href="?p=register">Sign up now</a>.</p>
        <p>If you forgot your password, you should inform admin.</p>
    </form>
</div>