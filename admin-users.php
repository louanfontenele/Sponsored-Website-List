<?php
// admin-users.php
require_once 'config.php';

$stmt = $pdo->query("SELECT id, username FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <title>User Management</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@2.51.5/dist/full.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-black">
  <?php include 'header.php'; ?>
  <div class="container mx-auto p-4 space-y-6">
    <h1 class="text-3xl font-bold">User Management</h1>
    <table class="table w-full border border-gray-300">
      <thead class="bg-gray-100">
        <tr>
          <th class="border p-2">ID</th>
          <th class="border p-2">Username</th>
          <th class="border p-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user): ?>
          <tr>
            <td class="border p-2"><?= htmlspecialchars($user['id']) ?></td>
            <td class="border p-2"><?= htmlspecialchars($user['username']) ?></td>
            <td class="border p-2">
              <div class="flex space-x-2">
                <form action="admin-users-edit.php" method="POST" class="flex items-center">
                  <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                  <input type="password" name="newPassword" placeholder="New Password" class="input input-bordered w-full max-w-xs" required>
                  <button type="submit" class="btn btn-info btn-sm ml-2">Change</button>
                </form>
                <form action="admin-users-delete.php" method="POST" class="flex items-center">
                  <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                  <button type="submit" class="btn btn-error btn-sm">Delete</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <h2 class="text-2xl font-bold">Create New User</h2>
    <form action="admin-users-add.php" method="POST" class="space-y-4">
      <div>
        <label class="label">Username:</label>
        <input type="text" name="username" class="input input-bordered w-full" required>
      </div>
      <div>
        <label class="label">Password:</label>
        <input type="password" name="password" class="input input-bordered w-full" required>
      </div>
      <button type="submit" class="btn btn-success w-full">Add User</button>
    </form>
  </div>
</body>
</html>
