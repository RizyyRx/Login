<?

if (Session::isset_session('username')) {
    $username = $_SESSION['username']; ?>
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="container p-4 rounded shadow-lg text-center">
            <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
            <p>You have successfully Logged In.</p>
        </div>
    </div><?
}
else {
    header("Location: login.php");
}
            
?>