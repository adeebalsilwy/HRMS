[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![MIT License][license-shield]][license-url]
[![LinkedIn][linkedin-shield]][linkedin-url]

<p align="center">
  <a href="https://github.com/adeebalsilwy/HRMS">
    <img src="public/assets/img/logo/logo_128.png" alt="Logo">
  </a>

  <h2 align="center">HRMS</h2>

  <p align="center">
    Human Resource Management System
    <br />
    <br />
    <a href="https://github.com/adeebalsilwy/HRMS/issues">Report Bug</a>
    ·
    <a href="https://github.com/adeebalsilwy/HRMS/issues">Request Feature</a>
  </p>
</p>
<br />

**HRMS** is an open-source web application tailored to streamline employee management and HR processes within organizations.

It optimizes organizational efficiency through clear hierarchy establishment, centralized employee records, streamlined attendance and leave management, precise salary processing, timely alerts, comprehensive HR reports, and efficient asset/device tracking.

This concise solution promotes effective workforce management and informed decision-making.

## 🛠️ Built With
* [Laravel](https://laravel.com) - PHP Framework
* [Livewire](https://livewire.laravel.com) - Full-stack Framework for Dynamic Interfaces

## ✨ Features

- **🏢 Organizational Structure:** Establish a clear hierarchy with centers, departments, and positions.

- **👥 Employee Information Management:** Maintain centralized and detailed records of employee information.

- **⚙️ Process Automation:** Reduces administrative burdens on the department by handling routine tasks.

- **📊 Attendance and Leave Tracking:** Track attendance, manage leave requests, and monitor employee availability.

- **💰 Salary and Deduction Management:** Streamline salary and deduction processes, ensuring accuracy and compliance.

- **🔔 Alerts and Messaging System:** Implement notifications for important dates and announcements.

- **📈 Comprehensive HR Reports:** Generate detailed reports for insights into employee performance and attendance.

- **🖥️ Asset and Device Management:** Efficiently manage and track organizational assets and assigned devices for each employee.

- **🌐 Support localization:** Enable multilingual support and adapt the system to various regional and cultural settings, ensuring usability and compliance with local practices. Supports both left-to-right (LTR) and right-to-left (RTL) text directions.

## 📷 Screenshots 

<div align="center">
  <img src="https://github.com/adeebalsilwy/HRMS/assets/screenshots/login.png" alt="Login Screen">
  <h3>Login</h3>
</div>

<div align="center">
  <img src="https://github.com/adeebalsilwy/HRMS/assets/screenshots/dashboard.png" alt="Dashboard">
  <h3>Dashboard</h3>
</div>

<div align="center">
  <img src="https://github.com/adeebalsilwy/HRMS/assets/screenshots/employee-info.png" alt="Employee Information">
  <h3>Employee Info</h3>
</div>

<div align="center">
  <img src="https://github.com/adeebalsilwy/HRMS/assets/screenshots/sms.png" alt="SMS Messaging">
  <h3>SMS</h3>
</div>

<div align="center">
  <img src="https://github.com/adeebalsilwy/HRMS/assets/screenshots/fingerprints.png" alt="Fingerprint Attendance">
  <h3>Fingerprints</h3>
</div>

<div align="center">
  <img src="https://github.com/adeebalsilwy/HRMS/assets/screenshots/discounts.png" alt="Employee Discounts">
  <h3>Discounts</h3>
</div>

<div align="center">
  <img src="https://github.com/adeebalsilwy/HRMS/assets/screenshots/user.png" alt="User Management">
  <h3>User</h3>
</div>

## 🚀 Getting Started

### Prerequisites
- PHP 8.1 or later
- Composer
- MySQL
- Web server (Apache/Nginx)
- Node.js and NPM (for frontend assets)

### Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/adeebalsilwy/HRMS.git
   ```

2. Navigate to the project folder:
   
   ```bash
   cd HRMS
   ```

3. Install PHP dependencies:
   
   ```bash
   composer install
   ```

4. Install JavaScript dependencies:
   
   ```bash
   npm install
   ```

5. Build frontend assets:
   
   ```bash
   npm run dev
   # or for production
   npm run build
   ```

6. Set up the environment configuration:

   - Copy the example environment file:
     ```bash
     cp .env.example .env
     ```
      
   - Configure the database in the `.env` file:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=hrms
     DB_USERNAME=root
     DB_PASSWORD=
     ```
      
   - Set your preferred application timezone:
     ```
     APP_TIMEZONE=Asia/Riyadh
     ```

7. Generate application encryption key:
   
   ```bash
   php artisan key:generate
   ```

8. Create symbolic link for storage:
   
   ```bash
   php artisan storage:link
   ```

9. Run database migrations and seed with sample data:
   
   ```bash
   php artisan migrate --seed
   ```

10. Start the development server:
   
    ```bash
    php artisan serve
    ```

11. Access the application at [http://localhost:8000](http://localhost:8000)

### Usage

1. Login with the default administrator account:
    
   ```
   Email: admin@demo.com
   Password: admin
   ```

2. After logging in, you can:
   - Navigate through the side menu to access different modules
   - Manage employees, departments, positions, and centers
   - Track attendance and leave requests
   - Process salaries and generate reports
   - Manage assets and their assignments to employees

## 🔧 Customization

- **Localization:** Modify translations in the `lang` directory for multi-language support
- **Theme:** Customize appearance in `resources/views` and `public/assets` directories
- **Business Logic:** Adjust application rules in `app/Models` and `app/Livewire` directories

## 🤝 Contribution

Contributions are welcome! If you'd like to contribute:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📞 Contact

Adeeb Alsilwy - adeebalsilwy@gmail.com

Project Link: [https://github.com/adeebalsilwy/HRMS](https://github.com/adeebalsilwy/HRMS)

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE.md) file for details.

---

<!-- MARKDOWN LINKS & IMAGES -->
[contributors-shield]: https://img.shields.io/github/contributors/adeebalsilwy/HRMS.svg?style=flat-square
[contributors-url]: https://github.com/adeebalsilwy/HRMS/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/adeebalsilwy/HRMS.svg?style=flat-square
[forks-url]: https://github.com/adeebalsilwy/HRMS/network/members
[stars-shield]: https://img.shields.io/github/stars/adeebalsilwy/HRMS.svg?style=flat-square
[stars-url]: https://github.com/adeebalsilwy/HRMS/stargazers
[issues-shield]: https://img.shields.io/github/issues/adeebalsilwy/HRMS.svg?style=flat-square
[issues-url]: https://github.com/adeebalsilwy/HRMS/issues
[license-shield]: https://img.shields.io/github/license/adeebalsilwy/HRMS.svg?style=flat-square
[license-url]: https://github.com/adeebalsilwy/HRMS/blob/master/LICENSE.md
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?style=flat-square&logo=linkedin&colorB=555
[linkedin-url]: https://linkedin.com/in/adeebalsilwy
#   H R M S 
 
 