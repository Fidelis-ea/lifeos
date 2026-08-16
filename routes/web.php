<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');


Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Daily Check-in
    Route::get('/checkin', [CheckinController::class, 'index'])->name('checkin');
    Route::post('/checkin', [CheckinController::class, 'store'])->name('checkin.store');

    // Habit Tracker
    Route::get('/habits', [HabitController::class, 'index'])->name('habits.index');
    Route::post('/habits', [HabitController::class, 'store'])->name('habits.store');
    Route::post('/habits/{habit}/log', [HabitController::class, 'log'])->name('habits.log');
    Route::delete('/habits/{habit}', [HabitController::class, 'destroy'])->name('habits.destroy');

    // Goal Tracker
    Route::get('/goals', [GoalController::class, 'index'])->name('goals.index');
    Route::post('/goals', [GoalController::class, 'store'])->name('goals.store');
    Route::patch('/goals/{goal}', [GoalController::class, 'update'])->name('goals.update');
    Route::delete('/goals/{goal}', [GoalController::class, 'destroy'])->name('goals.destroy');
    Route::post('/goals/{goal}/tasks', [GoalController::class, 'storeTask'])->name('goals.storeTask');
    Route::post('/goals/tasks/{task}/toggle', [GoalController::class, 'toggleTask'])->name('goals.toggleTask');

    // Timeline
    Route::get('/timeline', [TimelineController::class, 'index'])->name('timeline.index');
    Route::post('/timeline', [TimelineController::class, 'store'])->name('timeline.store');
    Route::put('/timeline/{timelineEntry}', [TimelineController::class, 'update'])->name('timeline.update');
    Route::delete('/timeline/{timelineEntry}', [TimelineController::class, 'destroy'])->name('timeline.destroy');

    // Skills
    Route::get('/skills', [SkillController::class, 'index'])->name('skills.index');
    Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');
    Route::post('/skills/{skill}/progress', [SkillController::class, 'updateProgress'])->name('skills.updateProgress');

    // Learning Tracker
    Route::get('/learning', [LearningController::class, 'index'])->name('learning.index');
    Route::post('/learning', [LearningController::class, 'store'])->name('learning.store');
    Route::delete('/learning/{log}', [LearningController::class, 'destroy'])->name('learning.destroy');

    // Project Tracker
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::patch('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::post('/projects/{project}/tasks', [ProjectController::class, 'storeTask'])->name('projects.storeTask');
    Route::post('/projects/tasks/{task}/toggle', [ProjectController::class, 'toggleTask'])->name('projects.toggleTask');

    // Finance Tracker
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::post('/finance', [FinanceController::class, 'store'])->name('finance.store');

    // Achievements
    Route::get('/achievements', [AchievementController::class, 'index'])->name('achievements.index');

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/test-speed', function () {
    $output = "";
    $start = microtime(true);

    $host = config('database.connections.pgsql.host');
    $port = config('database.connections.pgsql.port');
    $db = config('database.connections.pgsql.database');
    $user = config('database.connections.pgsql.username');
    $pass = config('database.connections.pgsql.password');

    $output .= "1. Resolving hostname...\n";
    $dns_start = microtime(true);
    $ip = gethostbyname($host);
    $dns_time = microtime(true) - $dns_start;
    $output .= "IP: $ip (Time: " . number_format($dns_time * 1000, 2) . " ms)\n\n";

    $output .= "2. Connecting to database via PDO...\n";
    $conn_start = microtime(true);
    try {
        $dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5,
        ]);
        $conn_time = microtime(true) - $conn_start;
        $output .= "Connected successfully! (Time: " . number_format($conn_time * 1000, 2) . " ms)\n\n";

        $output .= "3. Running simple query (SELECT 1)...\n";
        $query_start = microtime(true);
        $stmt = $pdo->query('SELECT 1');
        $stmt->execute();
        $query_time = microtime(true) - $query_start;
        $output .= "Query finished! (Time: " . number_format($query_time * 1000, 2) . " ms)\n\n";

        $output .= "4. Running table query (select count(*) from users)...\n";
        $query_start = microtime(true);
        $stmt = $pdo->query('select count(*) from users');
        $stmt->execute();
        $count = $stmt->fetchColumn();
        $query_time = microtime(true) - $query_start;
        $output .= "Count: $count (Time: " . number_format($query_time * 1000, 2) . " ms)\n\n";

    } catch (\Exception $e) {
        $output .= "ERROR: " . $e->getMessage() . "\n";
    }

    $total_time = microtime(true) - $start;
    $output .= "Total Execution Time: " . number_format($total_time * 1000, 2) . " ms\n";
    return response($output)->header('Content-Type', 'text/plain');
});
