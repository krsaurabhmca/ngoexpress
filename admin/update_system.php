<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

require '../includes/db.php';
header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : '';
$repo_url = "https://raw.githubusercontent.com/krsaurabhmca/ngoexpress/main";

// 1. Check for updates autonomously
if ($action === 'check') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $repo_url . "/includes/version.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'NGO-Updater');
    $remote_version_content = curl_exec($ch);
    curl_close($ch);
    
    $remote_version = CORE_VERSION; // Default to local
    
    if (preg_match("/define\('CORE_VERSION',\s*'([^']+)'\);/", $remote_version_content, $matches)) {
        $remote_version = $matches[1];
    }
    
    $is_available = version_compare($remote_version, CORE_VERSION, '>');
    
    echo json_encode([
        'status' => 'success',
        'current_version' => CORE_VERSION,
        'remote_version' => $remote_version,
        'update_available' => $is_available
    ]);
    exit;
}

// 2. Perform WordPress-style ZIP overriding update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $zipURL = "https://github.com/krsaurabhmca/ngoexpress/archive/refs/heads/main.zip";
    $zipFile = '../update.zip';
    
    // Download ZIP
    $ch = curl_init($zipURL);
    $fp = fopen($zipFile, "w");
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'NGO-Updater');
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
    
    if (!file_exists($zipFile)) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to download the update package.']);
        exit;
    }
    
    // Unzip
    $zip = new ZipArchive;
    if ($zip->open($zipFile) === TRUE) {
        $extractPath = '../update_temp/';
        if (!file_exists($extractPath)) {
            mkdir($extractPath, 0755, true);
        }
        $zip->extractTo($extractPath);
        $zip->close();
        
        // GitHub ZIPs contain a root folder usually named 'ngoexpress-main'
        $extractedDirs = glob($extractPath . '*', GLOB_ONLYDIR);
        if (empty($extractedDirs)) {
             echo json_encode(['status' => 'error', 'message' => 'Invalid update package structure.']);
             exit;
        }
        
        $sourcePath = $extractedDirs[0] . '/';
        $destPath = '../';
        
        // Recursive copy function, protecting DB configuration and Uploads!
        function rcopy($src, $dst) {
            $dir = opendir($src);
            @mkdir($dst);
            while(( $file = readdir($dir)) ) {
                if (( $file != '.' ) && ( $file != '..' )) {
                    // *** CRITICAL PROTECTION LIST ***
                    // We must NEVER overwrite their live connections, locks, or media
                    if ($file === 'db.php' && strpos($dst, 'includes') !== false) {
                        continue;
                    }
                    if ($file === 'install.lock' && strpos($dst, 'installer') !== false) {
                        continue;
                    }
                    if ($file === 'uploads' && realpath($src) === realpath(dirname(__FILE__).'/../update_temp/ngoexpress-main')) {
                        continue; 
                    }
                    
                    if ( is_dir($src . '/' . $file) ) {
                        rcopy($src . '/' . $file, $dst . '/' . $file);
                    } else {
                        copy($src . '/' . $file, $dst . '/' . $file);
                    }
                }
            }
            closedir($dir);
        }
        
        rcopy($sourcePath, $destPath);
        
        // Execute structural configuration auto-sync
        if (file_exists('../admin/upgrade.php')) {
            require_once '../admin/upgrade.php';
        }
        
        // Cleanup function
        function deleteDirectory($dir) {
            if (!file_exists($dir)) { return true; }
            if (!is_dir($dir)) { return unlink($dir); }
            foreach (scandir($dir) as $item) {
                if ($item == '.' || $item == '..') continue;
                if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) return false;
            }
            return rmdir($dir);
        }
        
        deleteDirectory($extractPath);
        unlink($zipFile);
        
        echo json_encode(['status' => 'success', 'message' => "Application successfully upgraded! Existing configuration and uploads were preserved smoothly."]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to extract the update package.']);
    }
}
?>
