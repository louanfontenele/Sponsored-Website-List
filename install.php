<?php
// install.php
require_once 'config.php';

if (file_exists('install-lock')) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $masterUsername = trim($_POST['master_username']);
    $masterPassword = trim($_POST['master_password']);

    try {
        // Create users table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB;
        ");

        // Create links table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS links (
                id INT AUTO_INCREMENT PRIMARY KEY,
                domain VARCHAR(255) NOT NULL,
                da INT NOT NULL,
                gambling VARCHAR(20) NOT NULL DEFAULT 'unchecked',
                country VARCHAR(255) NOT NULL,
                links INT NOT NULL,
                gambling_price DECIMAL(10,2) NOT NULL,
                general_price DECIMAL(10,2) NOT NULL,
                tab VARCHAR(50) NOT NULL
            ) ENGINE=InnoDB;
        ");

        // Insert master user
        $hashedPassword = password_hash($masterPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$masterUsername, $hashedPassword]);

        // Create install-lock file
        file_put_contents("install-lock", "Installed on " . date("Y-m-d H:i:s"));

        echo "<h1>Installation Successful</h1>";
        echo "<p>Master user created: " . htmlspecialchars($masterUsername) . "</p>";
        echo "<p><a href='login.php'>Go to Login</a></p>";
        exit;
    } catch (PDOException $e) {
        die("Installation failed: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <title>Installation Wizard</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@2.51.5/dist/full.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-black">
  <div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6">Installation Wizard</h1>
    <form action="install.php" method="POST" class="space-y-4">
      <div>
        <label class="label">Master Username:</label>
        <input type="text" name="master_username" class="input input-bordered w-full" required>
      </div>
      <div>
        <label class="label">Master Password:</label>
        <input type="password" name="master_password" class="input input-bordered w-full" required>
      </div>
      <button type="submit" class="btn btn-primary w-full">Install</button>
    </form>
  </div>
</body>
</html>
