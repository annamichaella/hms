<?php
// Temporary test file to debug login issues
// Access via: http://localhost/hospital-management-laravel/public/test-login.php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "<h2>Login Debug Test</h2>";

try {
    // Test database connection
    echo "<h3>1. Database Connection:</h3>";
    $users = User::take(3)->get(['id', 'email', 'role']);
    echo "<p style='color: green;'>✓ Connected! Found " . $users->count() . " users</p>";
    
    // Show sample users
    echo "<h3>2. Sample Users in Database:</h3>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Email</th><th>Role</th></tr>";
    foreach ($users as $user) {
        echo "<tr><td>{$user->id}</td><td>{$user->email}</td><td>{$user->role}</td></tr>";
    }
    echo "</table>";
    
    // Test password verification
    echo "<h3>3. Test Password Verification:</h3>";
    if (isset($_GET['email']) && isset($_GET['password'])) {
        $email = $_GET['email'];
        $password = $_GET['password'];
        
        $user = User::where('email', $email)->first();
        
        if ($user) {
            echo "<p><strong>User found:</strong> {$user->email} (ID: {$user->id}, Role: {$user->role})</p>";
            echo "<p><strong>Password hash prefix:</strong> " . substr($user->password, 0, 20) . "...</p>";
            
            // Test with Laravel Hash
            $laravelCheck = Hash::check($password, $user->password);
            echo "<p><strong>Laravel Hash::check:</strong> " . ($laravelCheck ? '<span style="color: green;">✓ MATCH</span>' : '<span style="color: red;">✗ NO MATCH</span>') . "</p>";
            
            // Test with PHP password_verify
            $phpCheck = password_verify($password, $user->password);
            echo "<p><strong>PHP password_verify:</strong> " . ($phpCheck ? '<span style="color: green;">✓ MATCH</span>' : '<span style="color: red;">✗ NO MATCH</span>') . "</p>";
        } else {
            echo "<p style='color: red;'>✗ User not found with email: {$email}</p>";
        }
    } else {
        echo "<p>To test password verification, add ?email=YOUR_EMAIL&password=YOUR_PASSWORD to the URL</p>";
        echo "<p><em>Example: ?email=admin@example.com&password=admin123</em></p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

