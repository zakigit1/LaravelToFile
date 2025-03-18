# Laravel to Single File Converter

[![PHP Version](https://img.shields.io/badge/PHP-7.0%2B-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

## Overview

The Laravel to Single File Converter is a utility tool that converts an entire Laravel project structure into a single, well-organized text file. This tool is particularly useful for code reviews, documentation, archiving, or sharing your Laravel project in a consolidated format.

## Features

- **Complete Project Conversion**: Recursively scans and processes all files in a Laravel project
- **Intelligent Filtering**: Automatically excludes unnecessary directories and files
- **Organized Output**: Maintains clear section headers for each original file path
- **Dual Interface**: Supports both command-line and web-based usage
- **Customizable**: Configurable exclusion lists for directories, file types, and specific files

## Requirements

- PHP 7.0 or higher
- File read/write permissions for the project directory

## Installation

1. Clone or download this repository to your local machine or server
2. Ensure the converter.php file has execution permissions (if using CLI)

## Usage

### Command Line Interface

```bash
php converter.php <project_directory> [output_file]
```

**Examples:**

```bash
# Basic usage with default output filename (laravel_project.txt)
php converter.php /path/to/laravel/project

# Specify a custom output filename
php converter.php /path/to/laravel/project my_project_export.txt
```

### Web Interface

1. Access the converter through your web browser (e.g., http://localhost/laravel/converter/converter.php)
2. Enter the Laravel project directory path in the form
3. Optionally specify a custom output filename
4. Click "Convert Project" to start the conversion process

## Configuration

You can customize the conversion process by modifying the following arrays in the `converter.php` file:

### Excluded Directories

```php
$excludeDirs = [
    'vendor',
    'node_modules',
    'storage/logs',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    '.git',
    'public/storage',
    // Add your custom directories here
];
```

### Excluded File Extensions

```php
$excludeExtensions = [
    'log',
    'zip',
    'gz',
    'rar',
    'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'ico',
    'mp3', 'mp4', 'avi', 'mov', 'wmv',
    'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
    // Add your custom extensions here
];
```

### Excluded Files

```php
$excludeFiles = [
    'README.md',
    '.env.example',
    '.gitattributes',
    '.gitignore',
    // Add your custom files here
];
```

## Output Format

The generated output file follows this structure:

```
/*
 * Laravel Project Converted to Single File
 * Generated on: YYYY-MM-DD HH:MM:SS
 * Project: project_name
 * Total Files: XXX
 */

/* ===== FILE: app/Http/Controllers/UserController.php ===== */
// Original file content here...

/* ===== FILE: app/Models/User.php ===== */
// Original file content here...

// And so on for all files in the project
```

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Acknowledgements

- The Laravel community for inspiration
- All contributors to this project