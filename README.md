# ServiSpace - Field Service Management System

![ServiSpace Logo](https://your-logo-url-here.png)

## Overview

ServiSpace is a comprehensive field service management solution designed to streamline dispatch operations, technician assignments, and customer service for repair and installation businesses. This web-based application efficiently manages the entire service lifecycle from ticket creation to completion, with features specifically tailored for IT hardware repair companies, equipment installers, and field service organizations.

## Key Features

### 🎫 Ticket Management
- **Comprehensive Ticketing System**: Track service requests from creation to resolution
- **Priority-based Assignment**: Assign tickets based on urgency (low, medium, high, urgent)
- **Status Tracking**: Monitor ticket progress through multiple stages (open, assigned, onsite, in progress, resolved, closed)
- **Detailed Documentation**: Maintain comprehensive service records with attachments and notes
- **Part Tracking**: Manage hardware components used in repairs with inventory status

### 👨‍🔧 Technician Portal
- **Personalized Dashboard**: Technicians get an overview of their assigned tickets
- **Mobile-friendly Interface**: Access service details from the field
- **Service Updates**: Log work progress, part installations, and completion details
- **Customer Signatures**: Capture electronic signatures for service verification
- **Appointment Management**: Schedule and reschedule customer appointments

### 🏢 Organization Management
- **Multi-organization Support**: Manage tickets for different client organizations
- **Client Information Repository**: Store contact details and support information
- **Document Storage**: Maintain organization-specific documentation

### 📊 Admin Dashboard
- **Performance Overview**: Monitor system-wide ticket statistics
- **Resource Management**: Add and manage technicians and organizations
- **Assignment Control**: Assign appropriate technicians to tickets
- **Complete Visibility**: Access all tickets and their statuses

## Technology Stack

- **Backend**: PHP with CodeIgniter/Laravel framework
- **Frontend**: HTML, CSS, JavaScript
- **Database**: MySQL
- **Authentication**: Custom secure login system with role-based access

## Screenshots

### Admin Dashboard
![Admin Dashboard](https://path-to-screenshot/admin-dashboard.png)

### Ticket Management
![Ticket List](https://path-to-screenshot/ticket-list.png)

### Technician Portal
![Technician Dashboard](https://path-to-screenshot/tech-dashboard.png)

## Database Structure

ServiSpace utilizes a relational database with the following core tables:
- `admins`: System administrators
- `technicians`: Field service personnel
- `organizations`: Client companies
- `customers`: End-users receiving service
- `tickets`: Service requests
- `ticket_updates`: Activity log and comments
- `ticket_parts`: Parts used in service
- `ticket_attachments`: Documents and images

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/servispace.git
```

2. Configure your web server (Apache/Nginx) to point to the project directory

3. Import the database schema:
```bash
mysql -u username -p database_name < u735253013_servispace.sql
```

4. Configure database connection in the config file

5. Access the system at your configured domain

## Usage Examples

### Creating a New Ticket
- Log in as an administrator
- Navigate to the Tickets section
- Click "Create Ticket"
- Fill in required details including organization, description, and priority
- Upload any relevant attachments
- Save the ticket

### Technician Workflow
- Log in to the technician portal
- View assigned tickets on the dashboard
- Select a ticket to view details
- Schedule an appointment with the customer
- Update service progress
- Mark parts as installed/used
- Complete the service with appropriate status code
- Capture customer signature

## License

[Your chosen license]

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Contact

For support or inquiries, please contact [your contact information].
