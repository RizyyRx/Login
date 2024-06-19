<?

//check if token is set in $_SESSION (set by UserSession::authenticate)
if(Session::isset_session('session_token')){

    //check if authorize is successful or not
    if(UserSession::authorize(Session::get('session_token'))){

        //get username from $_SESSION and display it (set by User::login )
        if (Session::isset_session('username')) {
            $username = $_SESSION['username']; ?>
            <div class="d-flex align-items-center justify-content-center vh-100">
                <div class="container p-4 rounded shadow-lg text-center">
                    
        <!--htmlspecialchars used to display the data as html entities.(prevent XSS vulnerabilities)-->
                    <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
                    <p>You have successfully Logged In.</p>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
                
            </div><?
        }
        else {

            //redirect to login page
            header("Location: login.php");
            exit();
        }
    }
    else{
        throw new Exception("not authorized");
    }
}
else{

    //redirect to login page
    header("Location: login.php");
    exit();
}    
?>