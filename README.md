# Website List Admin Panel 🚀

A modern, responsive web application built with **Node.js**, **Express**, **SQLite**, **EJS**, **TailwindCSS** & **DaisyUI**. This app displays a list of websites in a spreadsheet-like interface with tabs, search filtering, and an admin panel to create, edit, and delete records. It also includes user management (with a master admin defined in an `.env` file) and a Docker configuration for easy deployment and persistence.

## Features ✨

- **Multi-Tab Interface**: Filter your website list by tabs (Brazil & Portugal, World Websites, Add Links, and All).
- **Live Search**: Filter results automatically as you type! 🔍
- **Admin Panel**: Secure login and a modern dashboard for managing website records.
- **User Management**: Create, edit, and delete users (master admin is protected).
- **Tri-State Checkbox for Gambling**: Uses a disabled checkbox to show state with different colors (green for Checked, red for X, and default for Unchecked).
- **Dockerized**: Easy deployment with Docker and data persistence using volume mapping.

## Prerequisites ✅

- [Node.js (v18 or later)](https://nodejs.org/)
- [Docker](https://www.docker.com/) (optional, for containerized deployment)
- [Git](https://git-scm.com/)

## Installation 🛠️

1. **Clone the repository:**

   ```bash
   git clone https://github.com/yourusername/YourRepoName.git
   cd YourRepoName
   ```

2. **Install dependencies:**

   ```bash
   npm install
   ```

3. **Set up the environment variables:**  
   Create a file named `.env` in the root directory with the following content (modify as needed):

   ```ini
   MASTER_USERNAME=admin
   MASTER_PASSWORD=JgF%9f8&!8yuECtJ
   ```

## Running the Application 🚀

### Running Locally (Without Docker)

1. **Start the server:**

   ```bash
   npm start
   ```

2. **Access the application:**

   - Public pages: [http://localhost:3000](http://localhost:3000)
   - Admin panel: [http://localhost:3000/login](http://localhost:3000/login)

### Running with Docker 🐳

1. **Build the Docker image:**

   ```bash
   docker build -t website-list .
   ```

2. **Run the container with volume mapping (to persist SQLite data):**

   ```bash
   docker run -d -p 3000:3000 -v ../files/app/AppName:/app/database --name website-list website-list
   ```

   > **Note:** Ensure that the host path (`../files/app/AppName`) exists and is accessible.

## Application Structure 📂

```
YourRepoName/
├── .env                 # Environment variables (master username & password)
├── .gitignore           # Files/folders to ignore in Git
├── Dockerfile           # Docker configuration for containerization
├── package.json         # Node.js dependencies and scripts
├── server.js            # Main server file (Express, SQLite, routing, etc.)
├── database/            # SQLite database (persisted via volume)
├── views/               # EJS templates for rendering pages
│   ├── index.ejs      # Public page (list view with search & tabs)
│   ├── contact.ejs    # Contact page
│   ├── login.ejs      # Admin login page
│   ├── admin.ejs      # Admin panel (add/search links, list view)
│   ├── edit-link.ejs  # Edit link page
│   └── admin-users.ejs# User management page (create/edit/delete users)
└── public/              # Static assets (CSS, JS, images)
```

## Usage 💡

- **Public Interface:**  
  Users can browse the website list, filter via tabs, and use the live search to find domains quickly.

- **Admin Panel:**
  - **Login:** Navigate to `/login` and use the credentials from your `.env` file.
  - **Manage Links:** Add, edit, or delete website entries using the modern admin dashboard.
  - **User Management:** Create, update, or remove users. The master admin (from `.env`) is protected and cannot be deleted or modified by others.

## UI/UX Highlights 🎨

- **Modern Design:** Utilizes TailwindCSS & DaisyUI for a clean, professional look.
- **Responsive Layout:** The application is fully responsive and works on all devices.
- **Interactive Elements:** Tabs, live search, and dynamic checkboxes make for a user-friendly experience.

## Troubleshooting ⚠️

- **Data Persistence:**  
  If data seems to be lost after redeploying, ensure you have correctly mapped the Docker volume (e.g., `-v ../files/app/AppName:/app/database`).

- **Checkbox Issues:**  
  If the disabled checkboxes are not displaying the correct state (especially for the "x" state), check that your browser supports the `indeterminate` property and that the CSS rule in your `index.ejs` and other files is in place.

- **Environment Variables:**  
  Double-check your `.env` file to ensure the master credentials are set properly.

## Contributing 🤝

Contributions are welcome! Feel free to open issues or submit pull requests for improvements, bug fixes, or new features.

## License 📄

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---

Happy coding! 😄🚀

-
