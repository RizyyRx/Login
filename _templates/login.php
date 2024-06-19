<?
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
$login_page = true;
if (isset($_POST['username']) && isset($_POST['password'])) {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $result = UserSession::authenticate($username,$password);
        $login_page = false;
    }
}

if (!$login_page) {
    if ($result) {
        header("Location: welcome.php");
        exit();
    } 
    else { ?>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card mt-5">
                        <div class="card-body text-center">
                            <h1 class="card-title">Invalid Credentials</h1>
                            <p class="card-text">Please log in again.</p>
                            <a href="login.php" class="btn btn-primary">Go to Login Page</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <? }
} 
else { ?>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="row w-100">
            <div class="col-md-6 col-lg-4 mx-auto">
                <form method="post" action="login.php">
                    <h1 class="h3 mb-3 fw-normal text-center">Please sign in</h1>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="floatingInput" name="username" placeholder="name@example.com">
                        <label for="floatingInput">Email address or Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
                        <label for="floatingPassword">Password</label>
                    </div>

                    <div class="form-check text-start my-3">
                        <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            Remember me
                        </label>
                    </div>
                    <button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>
                </form>
            </div>
        </div>
    </div>

<? }
?>