<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <title>Website List</title>
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
  <div class="container mx-auto p-4 space-y-4">
    <h1 class="text-3xl font-bold">Website List</h1>
    <!-- Tabs -->
    <div class="tabs">
      <button class="tab tab-bordered" onclick="filterByTab('brazil_portugal')">Brazil & Portugal</button>
      <button class="tab tab-bordered" onclick="filterByTab('world')">World Websites</button>
      <button class="tab tab-bordered" onclick="filterByTab('add_links')">Add Links</button>
      <button class="tab tab-bordered" onclick="filterByTab('')">All</button>
    </div>
    <!-- Search -->
    <div class="flex gap-2 mt-4">
      <input id="searchInput" type="text" placeholder="Search by domain" class="input input-bordered w-full max-w-xs">
      <button onclick="searchLinks()" class="btn btn-primary">Search</button>
    </div>
    <!-- Table -->
    <div class="overflow-x-auto mt-4">
      <table class="table w-full border border-gray-300">
        <thead class="bg-gray-100">
          <tr>
            <th colspan="2" class="text-center border p-2">Website Information</th>
            <th colspan="3" class="text-center border p-2">Article Details</th>
            <th colspan="2" class="text-center border p-2">Price USD</th>
          </tr>
          <tr>
            <th class="border p-2">Domain</th>
            <th class="border p-2">D.A.</th>
            <th class="border p-2">Gambling</th>
            <th class="border p-2">Country</th>
            <th class="border p-2">Links</th>
            <th class="border p-2">Gambling Price</th>
            <th class="border p-2">General Price</th>
          </tr>
        </thead>
        <tbody id="table-body" class="bg-white">
          <!-- Data loaded via AJAX -->
        </tbody>
      </table>
    </div>
  </div>
  <?php include 'footer.php'; ?>
  <script>
    let currentTab = "";
    function filterByTab(tab) {
      currentTab = tab;
      loadLinks();
    }
    function searchLinks() {
      loadLinks();
    }
    function loadLinks() {
      const searchTerm = document.getElementById('searchInput').value;
      let url = 'api-links.php?';
      if (currentTab) {
        url += 'tab=' + encodeURIComponent(currentTab) + '&';
      }
      if (searchTerm) {
        url += 'search=' + encodeURIComponent(searchTerm);
      }
      fetch(url)
        .then(response => response.json())
        .then(data => {
          const tbody = document.getElementById('table-body');
          tbody.innerHTML = '';
          data.forEach(item => {
            const row = document.createElement('tr');

            // Create a disabled checkbox for Gambling
            const cb = document.createElement('input');
            cb.type = "checkbox";
            cb.disabled = true;
            if (item.gambling === "check") {
              cb.setAttribute("checked", "checked");
              cb.className = "checkbox checkbox-success";
            } else if (item.gambling === "x") {
              cb.className = "checkbox checkbox-error";
              cb.indeterminate = true;
            } else {
              cb.className = "checkbox";
            }
            const gamblingCell = document.createElement('td');
            gamblingCell.className = "border border-gray-300 p-2";
            gamblingCell.appendChild(cb);

            row.innerHTML = `
              <td class="border border-gray-300 p-2"><a href="http://${item.domain}" target="_blank" class="link link-primary">${item.domain}</a></td>
              <td class="border border-gray-300 p-2">${item.da}</td>
            `;
            row.appendChild(gamblingCell);
            row.innerHTML += `
              <td class="border border-gray-300 p-2">${item.country}</td>
              <td class="border border-gray-300 p-2">${item.links}</td>
              <td class="border border-gray-300 p-2">$${item.gambling_price}</td>
              <td class="border border-gray-300 p-2">$${item.general_price}</td>
            `;
            tbody.appendChild(row);
          });
          document.querySelectorAll('input[type="checkbox"].checkbox-error').forEach(cb => {
            if (!cb.checked) {
              cb.indeterminate = true;
            }
          });
        });
    }
    loadLinks();
  </script>
</body>
</html>
