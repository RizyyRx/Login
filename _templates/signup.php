<?

//initialize signup as false
$signup = false;

//check if credentials are present in $_POST and not empty
if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['phone'])) {
    if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['phone'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phone = $_POST['phone'];

        //execute signup, boolean value is returned to $error
        $error = User::signup($username, $email, $password, $phone);

        //change signup to true
        $signup = true;
    }
}

//check if signup credentials are provided or not
if ($signup) {

    //check if signup is successful (error=false)
    if (!$error) {

        //display "signup success" 
?>
        <div class="container">
            <div class="bg-body-tertiary p-5 rounded mt-3">
                <h1>Signup success</h1>
                <p class="lead">Now you can login from <a href="login.php">here</a></p>
            </div>
        </div>
    <?
    } else {
        
        //display "signup failed"
        ?>
        <div class="container">
            <div class="bg-body-tertiary p-5 rounded mt-3">
                <h1>Signup failed</h1>
                <p class="lead">Something went wrong, <?= $error ?></p>
            </div>
        </div>
    <?

    }
} else { 
    
    //display signup page
    ?>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="row w-100">
            <div class="col-md-6 col-lg-4 mx-auto">
                <form action="signup.php" method="post">
                    <h1 class="h3 mb-3 fw-normal text-center">Please sign up</h1>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="floatingUsername" name="username" placeholder="Username">
                        <label for="floatingUsername">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="floatingEmail" name="email" placeholder="name@example.com">
                        <label for="floatingEmail">Email address</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
                        <label for="floatingPassword">Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="tel" class="form-control" id="floatingPhone" name="phone" placeholder="Phone number">
                        <label for="floatingPhone">Phone number</label>
                    </div>

                    <button class="btn btn-primary w-100 py-2" type="submit">Sign up</button>
                </form>
            </div>
        </div>
    </div>
<? }
?>