<?php
// admin-edit.php
require_once 'config.php';
if (!isset($_GET['id'])) {
    die("Link ID not specified.");
}
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM links WHERE id = ?");
$stmt->execute([$id]);
$link = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$link) {
    die("Link not found.");
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <title>Edit Link</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@2.51.5/dist/full.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    input[type="checkbox"].checkbox-error:indeterminate {
      background-image: url('data:image/svg+xml;utf8,<svg fill="red" height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>');
      background-size: 16px 16px;
      background-repeat: no-repeat;
      background-position: center;
      -webkit-appearance: none;
      appearance: none;
    }
  </style>
</head>
<body class="bg-white text-black">
  <?php include 'header.php'; ?>
  <div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6">Edit Link</h1>
    <form action="admin-edit-process.php" method="POST" class="space-y-4">
      <input type="hidden" name="id" value="<?= htmlspecialchars($link['id']) ?>">
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="label">Domain:</label>
          <input type="text" name="domain" class="input input-bordered w-full" value="<?= htmlspecialchars($link['domain']) ?>" required>
        </div>
        <div>
          <label class="label">D.A.:</label>
          <input type="number" name="da" step="1" class="input input-bordered w-full" value="<?= htmlspecialchars($link['da']) ?>" required>
        </div>
        <div>
          <label class="label">Gambling:</label>
          <div class="flex items-center gap-2">
            <?php $gambling = trim($link['gambling']); ?>
            <input type="checkbox" id="editGamblingCheckbox" onclick="cycleEditGamblingState()" class="checkbox"
              <?php if($gambling === "check") echo 'checked="checked"'; ?>
            >
            <span id="editGamblingLabel" class="font-semibold">
              <?php 
                if($gambling === "check") echo "Checked";
                else if($gambling === "x") echo "X";
                else echo "Unchecked";
              ?>
            </span>
            <input type="hidden" name="gambling" id="editGamblingValue" value="<?= htmlspecialchars($gambling) ?>">
          </div>
        </div>
        <div>
          <label class="label">Country:</label>
          <input type="text" name="country" class="input input-bordered w-full" value="<?= htmlspecialchars($link['country']) ?>" required>
        </div>
        <div>
          <label class="label">Links:</label>
          <input type="number" name="links" step="1" class="input input-bordered w-full" value="<?= htmlspecialchars($link['links']) ?>" required>
        </div>
        <div>
          <label class="label">Gambling Price (USD):</label>
          <input type="number" step="0.01" min="0" name="gambling_price" class="input input-bordered w-full" value="<?= htmlspecialchars($link['gambling_price']) ?>" required>
        </div>
        <div>
          <label class="label">General Price (USD):</label>
          <input type="number" step="0.01" min="0" name="general_price" class="input input-bordered w-full" value="<?= htmlspecialchars($link['general_price']) ?>" required>
        </div>
        <div>
          <label class="label">Tab:</label>
          <select name="tab" class="select select-bordered w-full" required>
            <option value="brazil_portugal" <?= $link['tab'] == 'brazil_portugal' ? 'selected' : '' ?>>Brazil & Portugal</option>
            <option value="world" <?= $link['tab'] == 'world' ? 'selected' : '' ?>>World Websites</option>
            <option value="add_links" <?= $link['tab'] == 'add_links' ? 'selected' : '' ?>>Add Links</option>
          </select>
        </div>
      </div>
      <button type="submit" class="btn btn-success w-full">Save Changes</button>
    </form>
  </div>
  <script>
    function cycleEditGamblingState() {
      const checkbox = document.getElementById('editGamblingCheckbox');
      const hidden = document.getElementById('editGamblingValue');
      const label = document.getElementById('editGamblingLabel');
      let state = hidden.value;
      if (state === "unchecked") {
        state = "check";
        checkbox.checked = true;
        checkbox.indeterminate = false;
        checkbox.className = "checkbox checkbox-success";
        label.textContent = "Checked";
      } else if (state === "check") {
        state = "x";
        checkbox.checked = false;
        checkbox.indeterminate = true;
        checkbox.className = "checkbox checkbox-error";
        label.textContent = "X";
      } else {
        state = "unchecked";
        checkbox.checked = false;
        checkbox.indeterminate = false;
        checkbox.className = "checkbox";
        label.textContent = "Unchecked";
      }
      hidden.value = state;
    }
    (function initEditGamblingState() {
      const checkbox = document.getElementById('editGamblingCheckbox');
      const hidden = document.getElementById('editGamblingValue');
      const label = document.getElementById('editGamblingLabel');
      const state = hidden.value;
      if (state === "check") {
        checkbox.checked = true;
        checkbox.indeterminate = false;
        checkbox.className = "checkbox checkbox-success";
        label.textContent = "Checked";
      } else if (state === "x") {
        checkbox.checked = false;
        checkbox.indeterminate = true;
        checkbox.className = "checkbox checkbox-error";
        label.textContent = "X";
      } else {
        checkbox.checked = false;
        checkbox.indeterminate = false;
        checkbox.className = "checkbox";
        label.textContent = "Unchecked";
      }
    })();
  </script>
</body>
</html>
