<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('menus')->truncate();
        
        // Read the combined menu JSON file
        $jsonPath = base_path('menu.json');
        
        if (!file_exists($jsonPath)) {
            $this->command->error('menu.json file not found!');
            return;
        }
        
        $jsonContent = file_get_contents($jsonPath);
        $menuItems = json_decode($jsonContent, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('Error decoding JSON: ' . json_last_error_msg());
            return;
        }
        $this->command->info(count($menuItems));
        $this->command->info('Starting to seed menu items...');
        $count = 0;
        
        foreach ($menuItems as $item) {
            try {
                // Prepare the data for insertion
                $menuData = [
                    'price' => $this->extractNumericValue($item['price'] ?? '0'),
                    'category' => $item['category'] ?? '',
                    'name' => $item['name'] ?? '',
                    'description' => $item['description'] ?? '',
                    'weight' => $this->extractNumericValue($item['weight'] ?? '0'),
                    'calories' => $this->extractNumericValue($item['calories'] ?? '0'),
                    'caloriesPercentage' => $this->extractNumericValue($item['caloriesPercentage'] ?? '0'),
                    'proteins' => $this->extractNumericValue($item['proteins'] ?? '0'),
                    'proteinsPercentage' => $this->extractNumericValue($item['proteinsPercentage'] ?? '0'),
                    'carbohydrates' => $this->extractNumericValue($item['carbohydrates'] ?? '0'),
                    'carbohydratesPercentage' => $this->extractNumericValue($item['carbohydratesPercentage'] ?? '0'),
                    'lipids' => $this->extractNumericValue($item['lipids'] ?? '0'),
                    'lipidsPercentage' => $this->extractNumericValue($item['lipidsPercentage'] ?? '0'),
                    'sodium' => $this->extractNumericValue($item['sodium'] ?? '0'),
                    'sodiumPercentage' => $this->extractNumericValue($item['sodiumPercentage'] ?? '0'),
                    'image' => $item['image'] ?? '',
                    'country' => $item['country'] ?? 'MX',
                    'hideExtraInfo' => $item['hideExtraInfo'] ?? false,
                    'urlPdf' => $item['urlPdf'] ?? null,
                    'active' => $item['active'] ?? true,
                    'fiber' => $this->extractNumericValue($item['fiber'] ?? '0'),
                    'fiberPercentage' => $this->extractNumericValue($item['fiberPercentage'] ?? '0'),
                    'saturatedFats' => $this->extractNumericValue($item['saturatedFats'] ?? '0'),
                    'saturatedFatsPercentage' => $this->extractNumericValue($item['saturatedFatsPercentage'] ?? '0'),
                    'transFats' => $this->extractNumericValue($item['transFats'] ?? '0'),
                    'transFatsPercentage' => $this->extractNumericValue($item['transFatsPercentage'] ?? '0'),
                    'allergens' => isset($item['allergens']) ? json_encode($item['allergens']) : json_encode([]),
                    'sugarTotals' => $this->extractNumericValue($item['sugarTotals'] ?? '0'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                // Insert the menu item
                Menu::create($menuData);
                $count++;
                
                // Show progress every 50 items
                if ($count % 50 === 0) {
                    $this->command->info("Processed $count menu items...");
                }
            } catch (\Exception $e) {
                Log::error('Error seeding menu item: ' . $e->getMessage());
                $this->command->warn("Error processing item: {$item['name']} - {$e->getMessage()}");
            }
        }
        
        $this->command->info("Successfully seeded $count menu items!");
    }
    
    /**
     * Extract numeric value from a string that might contain currency symbols or other non-numeric characters
     */
    private function extractNumericValue($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }
        
        // Remove currency symbols, spaces, and other non-numeric characters
        $numericString = preg_replace('/[^0-9.]/', '', trim($value));
        
        // If the string is empty after cleaning, return 0
        if (empty($numericString)) {
            return 0.0;
        }
        
        return (float) $numericString;
    }
}
