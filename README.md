# Website List Admin Panel (PHP) 🚀

A modern, responsive web application built with **PHP** and **MySQL**, styled with **TailwindCSS** & **DaisyUI**. This project provides a spreadsheet-like interface for viewing website lists with filtering tabs and live search. It also features an admin panel for managing website records and users.

> **Note:** This version is fully rewritten in PHP (no Node.js/EJS). It includes an installation wizard (`install.php`) that sets up your MySQL database and lets you configure the master user. An `install-lock` file is created afterward to prevent re‑installation.

## Features ✨

- **Installation Wizard**: Run `install.php` on first deployment to create your database tables and set your master user. An `install-lock` file prevents re‑installation.
- **Responsive Layout**: Fully responsive and built with TailwindCSS & DaisyUI.
- **Tabbed Interface & Live Search**: Filter your website list by tabs (Brazil & Portugal, World Websites, Add Links, All) and use live search to filter results as you type. 🔍
- **Admin Panel**: Secure login and a modern dashboard for managing website records.
- **User Management**: Create, update, and delete users. The master user is protected.
- **Gambling Checkbox**: Uses a disabled checkbox to display state:
  - **Checked (green)** for state "check".
  - **Indeterminate (red with X icon)** for state "x" (custom CSS shows a red X).
  - **Unchecked** otherwise.
- **API for AJAX Filtering**: Returns JSON for live search and filtering.
- **Dockerized**: Easily deployable with Docker using volume mapping for data persistence.

## Prerequisites ✅

- **PHP 7.4+** (with PDO extension for MySQL)
- **MySQL** database
- A web server (e.g., Apache or Nginx)
- [Composer](https://getcomposer.org/) (optional, for dependency management)

> **Optional (for Docker users):** Docker

## Installation 🛠️

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/YourRepoName.git
cd YourRepoName
```

### 2. Configure Your Environment

Create a file named `.env` in the project root with your master credentials:

```ini
MASTER_USERNAME=admin
MASTER_PASSWORD=JgF%9f8&!8yuECtJ
```

Update your MySQL credentials in `config.php`.

### 3. Set Up Your Database

Create a MySQL database with the name you specified in `config.php`.

### 4. Run the Installation Wizard

Open your browser and navigate to:

```
http://yourdomain/install.php
```

Follow the instructions to set up your master user. After successful installation, an `install-lock` file will be created to prevent re‑installation.

---

## Running the Application 🚀

### Locally (Without Docker)

1. Configure your web server (Apache, Nginx, etc.) to serve the PHP files.
2. Visit [http://localhost/index.php](http://localhost/index.php).

### With Docker 🐳

1. **Build the Docker image:**

   ```bash
   docker build -t website-list-php .
   ```

2. **Run the container with volume mapping for persistence:**

   ```bash
   docker run -d -p 80:80 -v ../files/app/AppName:/app/database --name website-list-php website-list-php
   ```

   > **Note:** Adjust the volume mapping as needed.

---

## Project Structure 📂

```
YourRepoName/
├── config.php              # Database connection & session setup
├── install.php             # Installation wizard for first-time setup
├── install-lock            # File created after installation (prevents reinstall)
├── index.php               # Public homepage with tabs and live search
├── login.php               # Login page for the admin panel
├── admin.php               # Main admin panel for managing links
├── admin-edit.php          # Page for editing a link
├── admin-edit-process.php  # Processes link edits
├── admin-add-process.php   # Processes new link additions
├── admin-delete.php        # Processes link deletions
├── admin-users.php         # User management page
├── admin-users-add.php     # Processes new user creation
├── admin-users-edit.php    # Processes user password changes
├── admin-users-delete.php  # Processes user deletions
├── api-links.php           # Returns JSON for AJAX filtering/searching
├── header.php              # Common header (navigation)
├── footer.php              # Common footer
├── .env                    # Environment variables (not in version control)
├── .gitignore              # Git ignore rules
└── README.md               # Project documentation
```

---

## Usage 💡

- **Public Interface:**  
  View the website list, filter by tabs, and use live search.

- **Admin Panel:**
  - **Login:** Navigate to `/login.php` and log in with your master credentials.
  - **Manage Links:** Add, edit, or delete website records.
  - **User Management:** Create, update, or delete users (the master user is protected).

---

## Contributing 🤝

Contributions are welcome! Please open an issue or submit a pull request with improvements or bug fixes.

---

## License 📄

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---

Happy coding! 😄🚀
