<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        //DB::table('questions')->truncate();

        // Load questions from JSON file
        $jsonPath = base_path('questions.json');

        if (!file_exists($jsonPath)) {
            $this->command->error('questions.json file not found!');
            return;
        }

        $jsonContent = file_get_contents($jsonPath);
        $questions = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('Error decoding JSON: ' . json_last_error_msg());
            return;
        }

        $this->command->info('Starting to seed questions...');
        $count = 0;

        foreach ($questions as $item) {
            try {
                $questionData = [
                    'question' => $item['question'] ?? '',
                    'options' => isset($item['options']) ? json_encode($item['options']) : json_encode([]),
                    'type' => $item['type'] ?? 'single_choice',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                Question::create($questionData);
                $count++;

                if ($count % 20 === 0) {
                    $this->command->info("Processed $count questions...");
                }
            } catch (\Exception $e) {
                Log::error('Error seeding question: ' . $e->getMessage());
                $this->command->warn("Error processing question: {$item['question']} - {$e->getMessage()}");
            }
        }

        $this->command->info("Successfully seeded $count questions!");
    }
}
