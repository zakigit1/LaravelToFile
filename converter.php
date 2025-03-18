<?php

/**
 * Laravel Project to Single File Converter
 * 
 * This script converts a Laravel project folder structure into a single file.
 * It recursively scans the Laravel project directory, reads all relevant files,
 * and combines them into a single output file with clear section headers for each file path.
 */

// Configuration
$excludeDirs = [
    // The main directory you can excluded (remove it)
    'vendor',
    'node_modules',
    'storage/logs',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    '.git',
    'public/storage',
    
    //Optional (As needed)

];

$excludeExtensions = [
    'log',
    'zip',
    'gz',
    'rar',
    'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'ico',
    'mp3', 'mp4', 'avi', 'mov', 'wmv',
    'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
];

$excludeFiles = [
    'README.md',
    '.env.example',
    '.gitattributes',
    '.gitignore',
];

// Function to check if a directory should be excluded
function shouldExcludeDir($path, $excludeDirs) {
    foreach ($excludeDirs as $excludeDir) {
        if (strpos($path, $excludeDir) !== false) {
            return true;
        }
    }
    return false;
}

// Function to check if a file should be excluded based on extension or filename
function shouldExcludeFile($file, $excludeExtensions, $excludeFiles = []) {
    // Check if the file is in the excluded files list
    if (in_array($file, $excludeFiles)) {
        return true;
    }
    
    // Check if the file extension is excluded
    $extension = pathinfo($file, PATHINFO_EXTENSION);
    return in_array(strtolower($extension), $excludeExtensions);
}

// Function to recursively scan directory and get all files
function scanDirectory($dir, $excludeDirs, $excludeExtensions, $excludeFiles, $baseDir = '') {
    $result = [];
    
    if (empty($baseDir)) {
        $baseDir = $dir;
    }
    
    $files = scandir($dir);
    
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        $relativePath = substr($path, strlen($baseDir) + 1);
        
        if (is_dir($path)) {
            if (!shouldExcludeDir($relativePath, $excludeDirs)) {
                $result = array_merge($result, scanDirectory($path, $excludeDirs, $excludeExtensions, $excludeFiles, $baseDir));
            }
        } else {
            if (!shouldExcludeFile($file, $excludeExtensions, $excludeFiles)) {
                $result[] = $relativePath;
            }
        }
    }
    
    return $result;
}

// Function to read file content
function readFileContent($file) {
    if (file_exists($file)) {
        return file_get_contents($file);
    }
    return "// File not found or cannot be read\n";
}

// Function to create a single file from all project files
function convertToSingleFile($projectDir, $outputFile, $excludeDirs, $excludeExtensions, $excludeFiles) {
    if (!is_dir($projectDir)) {
        echo "Error: Project directory does not exist.\n";
        return false;
    }
    
    $files = scanDirectory($projectDir, $excludeDirs, $excludeExtensions, $excludeFiles);
    
    // Sort files to maintain a logical order
    sort($files);
    
    $output = "";
    $output .= "/*\n";
    $output .= " * Laravel Project Converted to Single File\n";
    $output .= " * Generated on: " . date('Y-m-d H:i:s') . "\n";
    $output .= " * Project: " . basename($projectDir) . "\n";
    $output .= " * Total Files: " . count($files) . "\n";
    $output .= " */\n\n";
    
    foreach ($files as $file) {
        $filePath = $projectDir . DIRECTORY_SEPARATOR . $file;
        $fileContent = readFileContent($filePath);
        
        $output .= "/* ===== FILE: {$file} ===== */\n";
        $output .= $fileContent . "\n\n";
    }
    
    // Write to output file
    if (file_put_contents($outputFile, $output)) {
        echo "Conversion completed successfully!\n";
        echo "Output file: {$outputFile}\n";
        echo "Total files processed: " . count($files) . "\n";
        return true;
    } else {
        echo "Error: Failed to write to output file.\n";
        return false;
    }
}

// Main execution
if (isset($argv) && count($argv) > 1) {
    // Command line mode
    $projectDir = $argv[1];
    $outputFile = isset($argv[2]) ? $argv[2] : 'laravel_project.txt';
    
    echo "Converting Laravel project to single file...\n";
    convertToSingleFile($projectDir, $outputFile, $excludeDirs, $excludeExtensions, $excludeFiles);
} else {
    // Interactive mode when run from browser or without arguments
    if (php_sapi_name() !== 'cli') {
        echo "<html><body style='font-family: monospace; padding: 20px;'>";
        echo "<h1>Laravel Project to Single File Converter</h1>";
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['projectDir'])) {
            $projectDir = $_POST['projectDir'];
            $outputFile = !empty($_POST['outputFile']) ? $_POST['outputFile'] : 'laravel_project.txt';
            
            echo "<div style='background-color: #f0f0f0; padding: 10px; border-radius: 5px;'>";
            echo "<p>Converting Laravel project to single file...</p>";
            
            if (convertToSingleFile($projectDir, $outputFile, $excludeDirs, $excludeExtensions, $excludeFiles)) {
                echo "<p style='color: green;'>Conversion completed successfully!</p>";
                echo "<p>Output file: {$outputFile}</p>";
            } else {
                echo "<p style='color: red;'>Conversion failed.</p>";
            }
            echo "</div>";
        }
        
        echo "<form method='post' style='margin-top: 20px;'>";
        echo "<div style='margin-bottom: 10px;'>";
        echo "<label for='projectDir'>Laravel Project Directory:</label><br>";
        echo "<input type='text' id='projectDir' name='projectDir' style='width: 100%; padding: 5px;' required>";
        echo "</div>";
        
        echo "<div style='margin-bottom: 10px;'>";
        echo "<label for='outputFile'>Output File (default: laravel_project.txt):</label><br>";
        echo "<input type='text' id='outputFile' name='outputFile' style='width: 100%; padding: 5px;'>";
        echo "</div>";
        
        echo "<button type='submit' style='padding: 8px 15px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;'>Convert Project</button>";
        echo "</form>";
        
        echo "<div style='margin-top: 20px; font-size: 0.9em;'>";
        echo "<p><strong>Note:</strong> The following directories will be excluded:</p>";
        echo "<ul>";
        foreach ($excludeDirs as $dir) {
            echo "<li>{$dir}</li>";
        }
        echo "</ul>";
        
        echo "<p><strong>Note:</strong> The following files will be excluded:</p>";
        echo "<ul>";
        foreach ($excludeFiles as $file) {
            echo "<li>{$file}</li>";
        }
        echo "</ul>";
        echo "</div>";
        
        echo "</body></html>";
    } else {
        echo "Usage: php converter.php <project_directory> [output_file]\n";
        echo "Example: php converter.php /path/to/laravel/project laravel_project.txt\n";
    }
}