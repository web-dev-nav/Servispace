# ServiSpace
## A Modern Field Service Management Solution

ServiSpace is a powerful, web-based dispatch management system designed for field service businesses that handle on-site hardware repairs, installations, and technical support. The platform streamlines the entire service lifecycle from ticket creation to completion, connecting dispatchers, technicians, and customers in a unified workflow.

## ✨ Features

### For Administrators
- **Centralized Dashboard**: Real-time overview of all tickets, technicians, and service statuses
- **Smart Ticket Management**: Create, assign, track, and manage service tickets with detailed information
- **Multi-Organization Support**: Manage service requests for various client organizations
- **Technician Assignment**: Match the right technician to each job based on expertise and availability
- **Document Management**: Store and organize important files related to organizations and tickets
- **Reporting & Analytics**: Track service performance, resolution times, and technician workload

### For Technicians
- **Mobile-Ready Interface**: Access ticket details, customer information, and service history from any device
- **Service Workflow Management**: Update ticket status, log work performed, and track parts used
- **Appointment Scheduling**: Schedule and reschedule service appointments with automated notifications
- **Digital Documentation**: Upload photos, collect signatures, and complete digital service forms
- **Part Tracking**: Manage replacement parts with detailed status tracking

### For Organizations
- **Service Visibility**: View current and historical service requests
- **Document Repository**: Access to relevant technical documentation and service agreements
- **Communication Channel**: Direct communication with service providers and technicians

## 🚀 Core Workflows

### Ticket Lifecycle
1. **Creation**: Dispatcher creates ticket with customer, location, and service details
2. **Assignment**: Ticket is assigned to an appropriate technician
3. **Scheduling**: Technician schedules appointment with customer
4. **Service**: Technician performs on-site work and updates ticket status
5. **Parts Management**: Parts installation and tracking
6. **Completion**: Service is completed with documentation and customer signature
7. **Resolution**: Ticket is marked as resolved or closed

### Parts Management
- Track parts from assignment to installation
- Record part numbers, descriptions, and status
- Document replaced/defective parts for return processing

## 💻 Technical Details

### Technology Stack
- **Framework**: PHP with CodeIgniter/Laravel
- **Database**: MySQL
- **Frontend**: Responsive design with HTML5, CSS3, JavaScript
- **Security**: Role-based access control and encrypted data storage

### Database Structure
- Organized relational database design with tables for:
  - User management (admins, technicians)
  - Client data (organizations, customers)
  - Service tracking (tickets, updates, parts, attachments)
  - Session management and security

## 📸 Screenshots

### Admin Interface
![captureit_5-4-2025_at_15-10-49](https://github.com/user-attachments/assets/6fae6740-b35b-406a-9695-e29d8c54eafa)


![captureit_5-4-2025_at_15-12-29](https://github.com/user-attachments/assets/d53971e1-1b61-4136-9af0-f160ffc3065c)

### Technician Portal
![captureit_5-4-2025_at_15-13-35](https://github.com/user-attachments/assets/cd6153e8-411e-4f70-98fe-b2a11e41341b)

![captureit_5-4-2025_at_15-14-24](https://github.com/user-attachments/assets/c6720c5d-b3af-4191-9833-a05d08298008)

## 🔧 Setup Guide

1. **Prerequisites**
   - Web server with PHP 7.2+
   - MySQL/MariaDB database
   - Composer for dependency management

2. **Installation**
   ```bash
   # Clone the repository
   git clone https://github.com/your-username/servispace.git
   
   # Navigate to project directory
   cd servispace
   
   # Install dependencies
   composer install
   
   # Import database schema
   mysql -u username -p database_name < database/u735253013_servispace.sql
   
   # Configure environment
   cp .env.example .env
   # Edit .env with your database credentials
   
   # Set proper permissions
   chmod -R 755 application/
   chmod -R 777 uploads/
   ```

3. **Configuration**
   - Update database settings in the configuration file
   - Configure email settings for notifications
   - Set up virtual host in your web server

## 📋 Use Cases

- **IT Hardware Repair Services**: Track laptop, desktop, and peripheral repairs
- **Field Service Organizations**: Manage on-site technical support and installations
- **Equipment Maintenance Companies**: Schedule and document regular maintenance visits

## 📄 License

[Your license choice]

## 👨‍💻 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📞 Contact

[Your contact information]
