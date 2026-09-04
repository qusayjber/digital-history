# Digital History

> An interactive digital museum exploring the history of computing, programming, the Internet, cybersecurity, operating systems, technologies, and the people who shaped the digital world.

**Digital History** is a full-stack PHP/MySQL educational platform designed to transform the history of technology into an interactive experience. It combines historical content, timelines, articles, technologies, people, quizzes, experiments, and an administration system in one web application.

---

## ✨ Features

### 🌐 Digital Technology History

Explore the evolution of:

* Computing
* The Internet
* The World Wide Web
* Programming
* Operating Systems
* Cybersecurity
* Modern technologies
* Emerging and future technologies

### ⏳ Interactive Timeline

Explore important events throughout the history of computing and digital technology.

### 👨‍💻 Programming History

Discover programming languages, their origins, evolution, milestones, and influence on modern software development.

### 💻 Operating Systems

Explore the history and evolution of operating systems and the technologies that shaped modern computing.

### 🌍 Internet History

Learn about the development of computer networks, the Internet, the Web, major protocols, and important milestones.

### 🔐 Cybersecurity

Explore the evolution of:

* Computer security
* Malware
* Cryptography
* Cyber attacks
* Defensive technologies
* Security concepts

### 👤 Technology Pioneers

Discover influential people who contributed to computing, programming, the Internet, and technological innovation.

### 📰 Articles

A structured article system for publishing and organizing historical and educational content.

### 🧪 Digital Laboratory

Interactive experiments and educational technology demonstrations.

### 🎮 Quizzes

Test your knowledge through interactive quizzes covering different areas of digital history.

### 🔎 Search

Search across the platform for historical events, technologies, people, articles, and other content.

### 🌍 Multilingual Support

The platform includes language management and support for multiple languages, including RTL-oriented content.

### 👨‍💼 Administration System

The application includes an administrative interface for managing:

* Articles
* Events
* People
* Technologies
* Operating systems
* Programming languages
* Quizzes
* Media
* Users
* Languages
* Settings

### 🔑 Authentication

User functionality includes:

* Registration
* Login
* Logout
* Password hashing
* Password reset workflow
* User profiles
* Role-based access
* Administrative authentication

---

## 🏗️ Architecture

Digital History follows a traditional PHP server-side architecture backed by MySQL/MariaDB.

```text
Browser
   │
   ▼
PHP Application
   │
   ├── Public Pages
   │
   ├── Authentication
   │
   ├── REST-style API Endpoints
   │
   ├── Administration
   │
   ├── Business Logic
   │
   └── Database Layer
           │
           ▼
      MySQL / MariaDB
```

---

## 🛠️ Technology Stack

### Backend

* PHP
* PDO
* MySQL / MariaDB
* Session-based authentication

### Frontend

* HTML5
* CSS3
* JavaScript
* Responsive UI
* RTL support

### Database

* MySQL
* MariaDB
* UTF-8 / `utf8mb4`

### Development Environment

* XAMPP
* Apache
* MySQL / MariaDB
* PHP
* Git
* GitHub

---

## 📁 Project Structure

```text
digital-history/
│
├── admin/
│   ├── articles.php
│   ├── events.php
│   ├── index.php
│   ├── languages.php
│   ├── login.php
│   ├── media.php
│   ├── os.php
│   ├── people.php
│   ├── quizzes.php
│   ├── settings.php
│   ├── technologies.php
│   └── users.php
│
├── api/
│   ├── save-quiz-attempt.php
│   ├── search-autocomplete.php
│   └── set-language.php
│
├── assets/
│   ├── css/
│   │   ├── rtl.css
│   │   └── style.css
│   │
│   └── js/
│       └── main.js
│
├── includes/
│   ├── auth.php
│   ├── config.example.php
│   ├── database.php
│   ├── footer.php
│   ├── functions.php
│   ├── header.php
│   ├── language.php
│   └── security.php
│
├── uploads/
│   └── .gitkeep
│
├── 404.php
├── about.php
├── ai.php
├── article.php
├── articles.php
├── computing.php
├── contact.php
├── cybersecurity.php
├── era.php
├── event.php
├── forgot-password.php
├── future.php
├── games.php
├── index.php
├── internet.php
├── lab.php
├── language.php
├── login.php
├── logout.php
├── museum.php
├── os-history.php
├── people.php
├── person.php
├── privacy.php
├── profile.php
├── programming.php
├── quiz.php
├── register.php
├── schema.sql
├── search.php
├── sitemap.php
├── sitemap.xml
├── sources.php
├── technologies.php
├── technology.php
├── terms.php
├── timeline.php
└── web.php
```

---

## 🗄️ Database

The project includes a complete database schema in:

```text
schema.sql
```

The database contains structures for areas such as:

* Users
* Administrators
* Historical eras
* Timeline events
* People
* Technologies
* Programming languages
* Operating systems
* Articles
* Categories
* Experiments
* Quizzes
* Quiz questions
* Quiz answers
* Achievements
* User achievements
* Favorites
* Quiz attempts
* Page views
* Activity logs
* Translations
* Settings
* Media
* Password resets

The schema can be imported into MySQL or MariaDB during installation.

---

## 🚀 Installation

### 1. Requirements

Install:

* XAMPP
* PHP
* Apache
* MySQL or MariaDB
* Git

Recommended PHP extensions include:

* PDO
* PDO MySQL
* GD
* Fileinfo
* JSON
* Mbstring

---

### 2. Clone the Repository

```bash
git clone https://github.com/qusayjber/digital-history.git
```

Then move into the project:

```bash
cd digital-history
```

---

### 3. Place the Project in XAMPP

Copy the project into:

```text
xampp/htdocs/digital-history
```

For example:

```text
D:\xamppp\xampp\htdocs\digital-history
```

---

### 4. Start XAMPP

Start:

```text
Apache
MySQL
```

from the XAMPP Control Panel.

---

### 5. Create the Database

Create a database named:

```text
digital_history
```

with:

```text
utf8mb4
```

Then import:

```text
schema.sql
```

using phpMyAdmin or the MySQL command line.

---

## ⚙️ Configuration

The real configuration file is intentionally excluded from Git:

```text
includes/config.php
```

A safe configuration template is included:

```text
includes/config.example.php
```

Copy it:

```text
includes/config.example.php
        ↓
includes/config.php
```

Then configure your local database credentials.

Example:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'digital_history');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');
define('DB_CHARSET', 'utf8mb4');
```

For a standard local XAMPP installation, the credentials may differ depending on your MySQL configuration.

---

## 🌐 Run the Application

After Apache and MySQL are running, open:

```text
http://localhost/digital-history/
```

---

## 🔐 Security

The project contains several security-oriented mechanisms, including:

* PDO database access
* Prepared statements
* Password hashing
* Session-based authentication
* CSRF protection
* Input sanitization
* File upload validation
* MIME-type validation
* Role-based authorization
* Password reset tokens
* Security helper functions

### Important

Never commit your local configuration file:

```text
includes/config.php
```

Do not commit:

```text
.env
```

database backups, private uploads, credentials, API keys, or other secrets.

The repository's `.gitignore` is configured to help prevent accidental inclusion of local configuration and runtime files.

---

## 📦 Uploads

Runtime uploaded files are intentionally excluded from version control.

Only:

```text
uploads/.gitkeep
```

is tracked so the directory exists after cloning the repository.

---

## 🧪 Development

After making changes:

```bash
git status
```

Review the changed files:

```bash
git diff
```

Stage the changes:

```bash
git add .
```

Create a commit:

```bash
git commit -m "Describe your changes"
```

Push:

```bash
git push
```

---

## 🧭 Main Areas of the Platform

```text
Digital History
│
├── History
│   ├── Eras
│   ├── Timeline
│   ├── Events
│   └── People
│
├── Computing
│   ├── Computing History
│   ├── Technologies
│   └── Operating Systems
│
├── Programming
│   └── Programming Languages
│
├── Internet
│   ├── Internet History
│   └── Web History
│
├── Cybersecurity
│
├── Artificial Intelligence
│
├── Future Technology
│
├── Articles
│
├── Digital Laboratory
│
└── Interactive Quizzes
```

---

## 🗺️ Roadmap

Possible future improvements include:

* [ ] Expand the historical knowledge base
* [ ] Add more programming language histories
* [ ] Add more operating-system histories
* [ ] Expand the cybersecurity section
* [ ] Add more interactive experiments
* [ ] Expand the quiz database
* [ ] Improve search capabilities
* [ ] Add advanced filtering
* [ ] Improve multilingual content
* [ ] Add richer historical timelines
* [ ] Improve administration analytics
* [ ] Add additional educational tools
* [ ] Improve accessibility
* [ ] Add automated testing
* [ ] Add deployment documentation
* [ ] Add production environment configuration

---

## 🤝 Contributing

Contributions are welcome.

A typical contribution workflow:

```bash
git clone https://github.com/qusayjber/digital-history.git
cd digital-history

git checkout -b feature/my-feature

# Make your changes

git add .
git commit -m "Add my feature"

git push origin feature/my-feature
```

Then open a Pull Request on GitHub.

---

## 📜 License

This project currently does not specify a separate open-source license.

Unless a license is added to the repository, the default copyright rules apply and permission should not be assumed for redistribution or reuse.

---

## 👨‍💻 Author

**Qusay Jber**

Software Developer with a Computer Science background and hands-on experience across:

* Web Development
* PHP
* Java
* Python
* JavaScript
* Databases
* Software Engineering
* Computer Science
* Networking
* Cybersecurity
* Application Development

GitHub:

https://github.com/qusayjber

---

## 🌐 Repository

**Digital History**

https://github.com/qusayjber/digital-history

---

## ⭐ Project

If you find the project useful or interesting, consider giving the repository a star on GitHub.

> Built to preserve, explore, and understand the history of the digital world.
