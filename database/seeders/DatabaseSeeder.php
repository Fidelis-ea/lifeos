<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Achievement;
use App\Models\DailyEntry;
use App\Models\Habit;
use App\Models\HabitLog;
use App\Models\Goal;
use App\Models\GoalTask;
use App\Models\TimelineEntry;
use App\Models\Skill;
use App\Models\SkillProgress;
use App\Models\LearningLog;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Achievements (Needed for Achievement System)
        $achievements = [
            [
                'name' => 'First Entry',
                'description' => 'Make your first daily check-in',
                'icon' => '📝',
                'requirement_type' => 'checkin_count',
                'requirement_value' => 1
            ],
            [
                'name' => '7 Day Streak',
                'description' => 'Maintain habits for 7 days',
                'icon' => '🔥',
                'requirement_type' => 'habit_streak',
                'requirement_value' => 7
            ],
            [
                'name' => 'Code Warrior',
                'description' => 'Log 10 hours of coding',
                'icon' => '💻',
                'requirement_type' => 'coding_hours',
                'requirement_value' => 10
            ],
            [
                'name' => 'Night Owl',
                'description' => 'Check in 5 days in a row',
                'icon' => '🌙',
                'requirement_type' => 'checkin_streak',
                'requirement_value' => 5
            ],
            [
                'name' => 'Bookworm',
                'description' => 'Log 5 hours of reading',
                'icon' => '📚',
                'requirement_type' => 'reading_hours',
                'requirement_value' => 5
            ],
            [
                'name' => 'Early Bird',
                'description' => 'Check in before 8am',
                'icon' => '☀️',
                'requirement_type' => 'early_checkin',
                'requirement_value' => 1
            ],
            [
                'name' => 'Builder',
                'description' => 'Create your first project',
                'icon' => '🏗️',
                'requirement_type' => 'project_count',
                'requirement_value' => 1
            ],
            // Locked achievements
            [
                'name' => '30 Day Streak',
                'description' => 'Maintain habits for 30 days',
                'icon' => '🔥',
                'requirement_type' => 'habit_streak',
                'requirement_value' => 30
            ],
            [
                'name' => '100 Hours Coding',
                'description' => 'Mencapai 100 jam coding',
                'icon' => '💻',
                'requirement_type' => 'coding_hours',
                'requirement_value' => 100
            ],
            [
                'name' => 'Japanese Beginner',
                'description' => 'Menyelesaikan target pembelajaran bahasa Jepang dasar',
                'icon' => '🇯🇵',
                'requirement_type' => 'japanese_hours',
                'requirement_value' => 10
            ],
            [
                'name' => 'Project Builder',
                'description' => 'Menyelesaikan 5 project',
                'icon' => '🏗️',
                'requirement_type' => 'completed_projects',
                'requirement_value' => 5
            ],
            [
                'name' => 'Finance Master',
                'description' => 'Log a transaction in every category',
                'icon' => '💰',
                'requirement_type' => 'category_diversity',
                'requirement_value' => 8
            ],
        ];

        foreach ($achievements as $ach) {
            Achievement::updateOrCreate(['name' => $ach['name']], $ach);
        }

        // 2. Seed Test User
        $user = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Ahmad Fauzi',
                'username' => 'ahmadfauzi',
                'password' => Hash::make('password'),
                'level' => 4,
                'xp' => 1250,
            ]
        );

        // 3. Seed Daily Entries (Last 7 Days)
        $today = today();
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            DailyEntry::updateOrCreate(
                ['user_id' => $user->id, 'date' => $date->toDateString()],
                [
                    'mood' => rand(6, 9),
                    'energy' => rand(6, 8),
                    'sleep_hours' => rand(6, 8) + (rand(0, 2) * 0.5),
                    'productivity' => rand(6, 9),
                    'notes' => "Had a productive day. Worked on building LifeOS features.",
                    'coding_minutes' => rand(90, 180),
                    'learning_minutes' => rand(30, 90),
                    'exercise_minutes' => rand(20, 45),
                    'gaming_minutes' => rand(0, 60),
                    'japanese_minutes' => rand(20, 60),
                ]
            );
        }

        // 4. Seed Habits
        $habit1 = Habit::updateOrCreate(
            ['user_id' => $user->id, 'name' => 'Coding'],
            [
                'description' => 'Write code daily',
                'icon' => '💻',
                'color' => '#FFD43B',
                'frequency' => 'daily',
                'target' => 1,
                'current_streak' => 8,
                'longest_streak' => 15,
                'order' => 1
            ]
        );

        $habit2 = Habit::updateOrCreate(
            ['user_id' => $user->id, 'name' => 'Japanese Study'],
            [
                'description' => 'Review Anki decks and read grammar',
                'icon' => '🇯🇵',
                'color' => '#FF7EB6',
                'frequency' => 'daily',
                'target' => 1,
                'current_streak' => 5,
                'longest_streak' => 10,
                'order' => 2
            ]
        );

        $habit3 = Habit::updateOrCreate(
            ['user_id' => $user->id, 'name' => 'Exercise'],
            [
                'description' => 'Workout or run',
                'icon' => '🏋️',
                'color' => '#5BC0EB',
                'frequency' => 'daily',
                'target' => 1,
                'current_streak' => 2,
                'longest_streak' => 5,
                'order' => 3
            ]
        );

        // Seed some habit logs for the last 10 days
        for ($i = 9; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i)->toDateString();
            
            // Coding log (completed 80% of days)
            if (rand(1, 10) <= 8) {
                HabitLog::updateOrCreate(['habit_id' => $habit1->id, 'date' => $date], ['user_id' => $user->id, 'completed' => true]);
            }
            
            // Japanese log
            if (rand(1, 10) <= 7) {
                HabitLog::updateOrCreate(['habit_id' => $habit2->id, 'date' => $date], ['user_id' => $user->id, 'completed' => true]);
            }

            // Exercise log
            if (rand(1, 10) <= 5) {
                HabitLog::updateOrCreate(['habit_id' => $habit3->id, 'date' => $date], ['user_id' => $user->id, 'completed' => true]);
            }
        }

        // 5. Seed Goals
        $goal1 = Goal::updateOrCreate(
            ['user_id' => $user->id, 'title' => 'Build Personal Portfolio'],
            [
                'description' => 'Create a stunning personal portfolio website to showcase my projects.',
                'category' => 'Coding',
                'start_date' => $today->copy()->subDays(30),
                'target_date' => $today->copy()->addDays(30),
                'progress' => 75,
                'status' => 'in_progress',
                'priority' => 'high',
                'icon' => '🎨',
                'color' => '#FFD43B',
            ]
        );

        $tasks = ['Design UI', 'Create homepage', 'Create projects page', 'Deploy website'];
        $order = 1;
        foreach ($tasks as $taskTitle) {
            GoalTask::updateOrCreate(
                ['goal_id' => $goal1->id, 'title' => $taskTitle],
                [
                    'completed' => ($taskTitle !== 'Deploy website'), // 3 completed, 1 outstanding
                    'order' => $order++
                ]
            );
        }

        $goal2 = Goal::updateOrCreate(
            ['user_id' => $user->id, 'title' => 'Learn Japanese N4'],
            [
                'description' => 'Master N4 grammar, vocabulary, and kanji.',
                'category' => 'Language',
                'start_date' => $today->copy()->subDays(60),
                'target_date' => $today->copy()->addDays(90),
                'progress' => 35,
                'status' => 'in_progress',
                'priority' => 'medium',
                'icon' => '🇯🇵',
                'color' => '#5BC0EB',
            ]
        );

        $tasks2 = ['Finish Minna no Nihongo II', 'Master 300 Kanji', 'Pass N4 Practice Test'];
        $order2 = 1;
        foreach ($tasks2 as $taskTitle) {
            GoalTask::updateOrCreate(
                ['goal_id' => $goal2->id, 'title' => $taskTitle],
                [
                    'completed' => ($taskTitle === 'Finish Minna no Nihongo II'),
                    'order' => $order2++
                ]
            );
        }

        // Recalculate progress
        $goal1->recalculateProgress();
        $goal2->recalculateProgress();

        // 6. Seed Skills
        $skill1 = Skill::updateOrCreate(
            ['user_id' => $user->id, 'name' => 'Laravel'],
            ['category' => 'Programming', 'current_level' => 75, 'target_level' => 90, 'learning_hours' => 45, 'notes' => 'Very comfortable with Routing, Eloquent, and MVC.']
        );
        SkillProgress::updateOrCreate(['skill_id' => $skill1->id, 'level' => 75], ['logged_date' => $today, 'notes' => 'Advanced Eloquent and Breeze integration']);

        $skill2 = Skill::updateOrCreate(
            ['user_id' => $user->id, 'name' => 'Figma'],
            ['category' => 'Design', 'current_level' => 60, 'target_level' => 85, 'learning_hours' => 20, 'notes' => 'Comfortable with wireframes, auto-layout, and prototypes.']
        );
        SkillProgress::updateOrCreate(['skill_id' => $skill2->id, 'level' => 60], ['logged_date' => $today, 'notes' => 'Auto-layout masterclass completed']);

        $skill3 = Skill::updateOrCreate(
            ['user_id' => $user->id, 'name' => 'Japanese'],
            ['category' => 'Language', 'current_level' => 40, 'target_level' => 70, 'learning_hours' => 35, 'notes' => 'Currently studying N4 level.']
        );
        SkillProgress::updateOrCreate(['skill_id' => $skill3->id, 'level' => 40], ['logged_date' => $today, 'notes' => 'Learned passive form and honorifics']);

        // 7. Seed Learning Logs
        LearningLog::updateOrCreate(
            ['user_id' => $user->id, 'topic' => 'Eloquent Performance Optimization'],
            ['skill_id' => $skill1->id, 'subject' => 'Laravel Framework', 'duration_minutes' => 90, 'date' => $today, 'notes' => 'Learned about eager loading vs lazy loading to avoid N+1 queries.']
        );

        LearningLog::updateOrCreate(
            ['user_id' => $user->id, 'topic' => 'Auto Layout Wrap and Components'],
            ['skill_id' => $skill2->id, 'subject' => 'UI/UX Design', 'duration_minutes' => 60, 'date' => $today->copy()->subDay(), 'notes' => 'Practiced advanced nesting of cards and grids.']
        );

        // 8. Seed Projects
        $project1 = Project::updateOrCreate(
            ['user_id' => $user->id, 'name' => 'LifeOS'],
            [
                'description' => 'Personal life tracking OS with habit streak, budget tracking, goal checklist, and gamification.',
                'status' => 'in_progress',
                'progress' => 40,
                'start_date' => $today->copy()->subDays(10),
                'end_date' => $today->copy()->addDays(20),
                'tech_stack' => 'Laravel, Blade, TailwindCSS, MySQL, Chart.js',
                'github_url' => 'https://github.com/test/lifeos',
                'demo_url' => 'https://lifeos.test',
            ]
        );
        ProjectTask::updateOrCreate(['project_id' => $project1->id, 'title' => 'Design neubrutalism system'], ['completed' => true]);
        ProjectTask::updateOrCreate(['project_id' => $project1->id, 'title' => 'Create database schema and models'], ['completed' => true]);
        ProjectTask::updateOrCreate(['project_id' => $project1->id, 'title' => 'Implement views and charts'], ['completed' => false]);
        $project1->recalculateProgress();

        // 9. Seed Transactions
        Transaction::updateOrCreate(
            ['user_id' => $user->id, 'description' => 'Part-time freelance design work'],
            ['type' => 'income', 'amount' => 1500000.00, 'category' => 'Other', 'date' => $today->copy()->subDays(2)]
        );
        Transaction::updateOrCreate(
            ['user_id' => $user->id, 'description' => 'Buy Sushi for lunch'],
            ['type' => 'expense', 'amount' => 75000.00, 'category' => 'Food', 'date' => $today]
        );
        Transaction::updateOrCreate(
            ['user_id' => $user->id, 'description' => 'Monthly internet bill'],
            ['type' => 'expense', 'amount' => 350000.00, 'category' => 'Internet', 'date' => $today->copy()->subDays(5)]
        );

        // 10. Seed Timeline Entries
        TimelineEntry::updateOrCreate(
            ['user_id' => $user->id, 'title' => 'Completed Portfolio UI Design'],
            ['date' => $today->copy()->subDays(1), 'category' => 'coding', 'description' => 'Created full-fidelity layout for portfolio pages in Figma.', 'duration_minutes' => 120, 'icon' => '💻']
        );
        TimelineEntry::updateOrCreate(
            ['user_id' => $user->id, 'title' => 'Finished Minna no Nihongo Lesson 30'],
            ['date' => $today->copy()->subDays(2), 'category' => 'japanese', 'description' => 'Studied transitive vs intransitive verbs.', 'duration_minutes' => 45, 'icon' => '🇯🇵']
        );
        TimelineEntry::updateOrCreate(
            ['user_id' => $user->id, 'title' => 'Jogging around the park'],
            ['date' => $today->copy()->subDays(2), 'category' => 'exercise', 'description' => 'Ran 5km in 30 minutes.', 'duration_minutes' => 30, 'icon' => '🏋️']
        );
    }
}
