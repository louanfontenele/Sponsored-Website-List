const express = require("express");
const session = require("express-session");
const bodyParser = require("body-parser");
const bcrypt = require("bcrypt");
const sqlite3 = require("sqlite3").verbose();
const path = require("path");

const app = express();
const port = process.env.PORT || 3000;

// IMPORTANTE: Configure o volume no Dokploy para mapear "../files/app/AppName" para "/app/database"
const dbPath = path.join(__dirname, "database", "database.db");
const db = new sqlite3.Database(dbPath, (err) => {
  if (err) {
    console.error("Erro ao conectar com o banco de dados:", err);
  } else {
    console.log("Conectado ao banco de dados SQLite");
  }
});

// Criação das tabelas e seed do usuário admin
db.serialize(() => {
  // Tabela de usuários
  db.run(`CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    password TEXT
  )`);

  // Tabela para os links
  db.run(`CREATE TABLE IF NOT EXISTS links (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    domain TEXT,
    da INTEGER,
    gambling TEXT,      -- 'unchecked', 'check' ou 'x'
    country TEXT,
    links INTEGER,
    gambling_price REAL,
    general_price REAL,
    tab TEXT            -- 'brazil_portugal', 'world' ou 'add_links'
  )`);

  // Seed do usuário admin com login "admin" e senha fixa
  db.get(
    "SELECT * FROM users WHERE username = ?",
    ["admin"],
    async (err, row) => {
      if (err) {
        console.error("Erro ao buscar usuário admin:", err);
        return;
      }
      if (!row) {
        try {
          const hashedPassword = await bcrypt.hash("JgF%9f8&!8yuECtJ", 10);
          db.run(
            "INSERT INTO users (username, password) VALUES (?, ?)",
            ["admin", hashedPassword],
            (err2) => {
              if (err2) {
                console.error("Erro ao criar usuário admin:", err2);
              } else {
                console.log("Usuário admin criado com sucesso.");
              }
            }
          );
        } catch (hashErr) {
          console.error("Erro ao gerar hash da senha:", hashErr);
        }
      } else {
        console.log("Usuário admin já existe.");
      }
    }
  );
});

// Configuração do Express
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());
app.use(
  session({
    secret: "sua_senha_super_secreta",
    resave: false,
    saveUninitialized: false,
  })
);

app.set("view engine", "ejs");
app.set("views", path.join(__dirname, "views"));
app.use(express.static(path.join(__dirname, "public")));

// Middleware de autenticação
function isAuthenticated(req, res, next) {
  if (req.session.userId) {
    return next();
  }
  res.redirect("/login");
}

// Rotas públicas
app.get("/", (req, res) => {
  res.render("index");
});

app.get("/contact", (req, res) => {
  res.render("contact");
});

// Rotas de Login
app.get("/login", (req, res) => {
  res.render("login", { error: null });
});

app.post("/login", (req, res) => {
  const { username, password } = req.body;
  db.get("SELECT * FROM users WHERE username = ?", [username], (err, user) => {
    if (err || !user) {
      return res.render("login", { error: "Invalid credentials" });
    }
    bcrypt.compare(password, user.password, (err, result) => {
      if (result) {
        req.session.userId = user.id;
        return res.redirect("/admin");
      } else {
        return res.render("login", { error: "Invalid credentials" });
      }
    });
  });
});

// Painel Admin: lista e busca de links
app.get("/admin", isAuthenticated, (req, res) => {
  const { search } = req.query;
  let sql = "SELECT * FROM links";
  const params = [];
  if (search) {
    sql += " WHERE domain LIKE ?";
    params.push(`%${search}%`);
  }
  db.all(sql, params, (err, rows) => {
    if (err) return res.send("Erro ao buscar links.");
    res.render("admin", { links: rows, search });
  });
});

// Tela de edição de um link
app.get("/admin/edit/:id", isAuthenticated, (req, res) => {
  const { id } = req.params;
  db.get("SELECT * FROM links WHERE id = ?", [id], (err, link) => {
    if (err || !link) {
      return res.send("Link não encontrado.");
    }
    res.render("edit-link", { link });
  });
});

// CRUD de Links - Adicionar link
app.post("/admin/add", isAuthenticated, (req, res) => {
  let {
    domain,
    da,
    links: linksCount,
    gambling,
    gambling_price,
    general_price,
    tab,
    country,
  } = req.body;
  da = parseInt(da, 10);
  if (Number.isNaN(da)) return res.send("Erro: D.A. must be an integer.");
  linksCount = parseInt(linksCount, 10);
  if (Number.isNaN(linksCount))
    return res.send("Erro: Links must be an integer.");
  gambling_price = parseFloat(gambling_price);
  if (Number.isNaN(gambling_price))
    return res.send("Erro: Gambling Price must be a number.");
  general_price = parseFloat(general_price);
  if (Number.isNaN(general_price))
    return res.send("Erro: General Price must be a number.");
  const stmt = db.prepare(`INSERT INTO links 
    (domain, da, gambling, country, links, gambling_price, general_price, tab)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)`);
  stmt.run(
    domain,
    da,
    gambling,
    country,
    linksCount,
    gambling_price,
    general_price,
    tab,
    function (err) {
      if (err) return res.send("Erro ao adicionar link: " + err.message);
      res.redirect("/admin");
    }
  );
});

// Processa a edição de um link
app.post("/admin/edit/:id", isAuthenticated, (req, res) => {
  const { id } = req.params;
  let {
    domain,
    da,
    gambling,
    country,
    links: linksCount,
    gambling_price,
    general_price,
    tab,
  } = req.body;
  da = parseInt(da, 10);
  if (Number.isNaN(da)) return res.send("Erro: D.A. must be an integer.");
  linksCount = parseInt(linksCount, 10);
  if (Number.isNaN(linksCount))
    return res.send("Erro: Links must be an integer.");
  gambling_price = parseFloat(gambling_price);
  if (Number.isNaN(gambling_price))
    return res.send("Erro: Gambling Price must be a number.");
  general_price = parseFloat(general_price);
  if (Number.isNaN(general_price))
    return res.send("Erro: General Price must be a number.");
  db.run(
    `UPDATE links SET domain=?, da=?, gambling=?, country=?, links=?, gambling_price=?, general_price=?, tab=? WHERE id=?`,
    [
      domain,
      da,
      gambling,
      country,
      linksCount,
      gambling_price,
      general_price,
      tab,
      id,
    ],
    function (err) {
      if (err) return res.send("Erro ao editar link: " + err.message);
      res.redirect("/admin");
    }
  );
});

// Excluir link
app.post("/admin/delete/:id", isAuthenticated, (req, res) => {
  const { id } = req.params;
  db.run("DELETE FROM links WHERE id=?", [id], function (err) {
    if (err) return res.send("Erro ao excluir link.");
    res.redirect("/admin");
  });
});

// API para carregar links filtrados por aba (usado na página inicial)
app.get("/api/links", (req, res) => {
  const { tab } = req.query;
  let query = "SELECT * FROM links";
  const params = [];
  if (tab) {
    query += " WHERE tab = ?";
    params.push(tab);
  }
  db.all(query, params, (err, rows) => {
    if (err) return res.status(500).json({ error: err.message });
    res.json(rows);
  });
});

// Gerenciamento de Usuários
app.get("/admin/users", isAuthenticated, (req, res) => {
  db.all("SELECT id, username FROM users", (err, users) => {
    if (err) return res.send("Erro ao buscar usuários.");
    res.render("admin-users", { users });
  });
});

app.post("/admin/users/add", isAuthenticated, async (req, res) => {
  const { username, password } = req.body;
  db.get(
    "SELECT * FROM users WHERE username = ?",
    [username],
    async (err, row) => {
      if (row) {
        return res.redirect("/admin/users?error=UserAlreadyExists");
      } else {
        try {
          const hashedPassword = await bcrypt.hash(password, 10);
          db.run(
            "INSERT INTO users (username, password) VALUES (?, ?)",
            [username, hashedPassword],
            (err2) => {
              if (err2) return res.redirect("/admin/users?error=DbError");
              res.redirect("/admin/users");
            }
          );
        } catch (hashErr) {
          return res.redirect("/admin/users?error=HashError");
        }
      }
    }
  );
});

app.post("/admin/users/delete/:id", isAuthenticated, (req, res) => {
  const userIdToDelete = req.params.id;
  db.get(
    "SELECT username FROM users WHERE id = ?",
    [userIdToDelete],
    (err, row) => {
      if (!row) return res.redirect("/admin/users?error=UserNotFound");
      if (row.username === "admin") {
        return res.redirect("/admin/users?error=CannotDeleteAdmin");
      }
      db.run("DELETE FROM users WHERE id = ?", [userIdToDelete], (err2) => {
        if (err2) return res.redirect("/admin/users?error=DbError");
        res.redirect("/admin/users");
      });
    }
  );
});

app.post("/admin/users/edit/:id", isAuthenticated, async (req, res) => {
  const userIdToEdit = req.params.id;
  const { newPassword } = req.body;
  const currentUserId = req.session.userId;
  db.get(
    "SELECT username FROM users WHERE id = ?",
    [userIdToEdit],
    (err, row) => {
      if (!row) return res.redirect("/admin/users?error=UserNotFound");
      const targetUsername = row.username;
      db.get(
        "SELECT username FROM users WHERE id = ?",
        [currentUserId],
        async (err2, currentUserRow) => {
          if (!currentUserRow)
            return res.redirect("/admin/users?error=InvalidSession");
          const currentUsername = currentUserRow.username;
          if (targetUsername === "admin" && currentUsername !== "admin") {
            return res.redirect("/admin/users?error=CannotEditAdminPassword");
          }
          try {
            const hashedPassword = await bcrypt.hash(newPassword, 10);
            db.run(
              "UPDATE users SET password = ? WHERE id = ?",
              [hashedPassword, userIdToEdit],
              (err3) => {
                if (err3) return res.redirect("/admin/users?error=DbError");
                res.redirect("/admin/users");
              }
            );
          } catch (hashErr) {
            return res.redirect("/admin/users?error=HashError");
          }
        }
      );
    }
  );
});

app.listen(port, () => {
  console.log(`Servidor rodando na porta ${port}`);
});
