<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Successful</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container text-center mt-5">
        <h1>Login Successful</h1>
        <?php if (isset($_GET['username'])): ?>
            <p>Welcome back, <strong><?php echo htmlspecialchars($_GET['username']); ?></strong>!</p>
        <?php else: ?>
            <p>Welcome back!</p>
        <?php endif; ?>
        <p>You have logged in successfully.</p>
        <a href="index.php" class="btn btn-primary">Go to Dashboard</a>
    </div>
</body>
</html>
