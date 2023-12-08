# Dynamic Content Management System (DCMS)

DCMS is a lightweight content management system for managing web pages dynamically. It provides a simple way to create, edit, and display web pages with dynamic content.
Please note this isn't WordPress or even close. It is a very barebones CMS that allows you to make pages and display them. Nothing really fancy. I may update this with new ideas or new code, but for now it is just a page editor that was a 4 hour project for me to have fun creating. (I hope you like it.)

## Features

- **Page Management:** Create and manage web pages easily.
- **Rich Text Editing:** Use a rich text editor to create and format page content.
- **Image Upload:** Upload and embed images in your pages.
- **Navigation:** Automatic navigation generation based on page slugs.
- **User Authentication:** Admin login to manage pages (authentication not included in this README).

## Getting Started

1. **Clone the Repository:**


`git clone https://github.com/yourusername/DCMS.git`


### Database Setup

1. Create a MySQL database and import the `database.sql` file to set up the necessary tables.
2. Configure your database connection in `includes/db.php`.

### Web Server Configuration

Ensure that your web server (e.g., Apache) is set up to allow `.htaccess` files and mod_rewrite for clean URLs.

### Rich Text Editor

Make sure to include a rich text editor library (e.g., TinyMCE) for the content editing interface. Update the path to this library if needed.

## Usage

- Access the admin area to create and manage pages.
- Users with admin privileges can log in to `/admin/login.php`.
- Page content can be accessed using clean URLs, e.g., `/index.php?page=home` or `/about`.

## Folder Structure

- `admin/`: Admin panel for page management (authentication required).
- `images/`: Folder to store uploaded images.
- `includes/`: PHP scripts and configuration files.
- `js/`: JavaScript libraries (e.g., TinyMCE).
- `index.php`: Main page for displaying content.
- `.htaccess`: Apache server configuration for clean URLs.

## License

This project is licensed under the MIT License.

## Acknowledgments

- Thanks to [TinyMCE](https://www.tiny.cloud/) for the rich text editor.

## Contributing

Contributions are welcome! Please open an issue or create a pull request to contribute to the project.
