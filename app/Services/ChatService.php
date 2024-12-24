<?php

namespace App\Services;

use Log;
use GuzzleHttp\Client;
use App\Models\Conversation;

class ChatService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function sendMessage($message, $user = 'Guest')
    {
        $store_data = '[
            {"name": "Apple", "category": "Fruits", "price": 90, "description": "Fresh red apples."},
            {"name": "Banana", "category": "Fruits", "price": 40, "description": "Ripe yellow bananas."},
            {"name": "Carrot", "category": "Vegetables", "price": 60, "description": "Crunchy orange carrots."},
            {"name": "Potato", "category": "Vegetables", "price": 30, "description": "Fresh potatoes."},
            {"name": "Onion", "category": "Vegetables", "price": 50, "description": "Fresh red onions."},
            {"name": "Tomato", "category": "Vegetables", "price": 75, "description": "Juicy red tomatoes."},
            {"name": "Lettuce", "category": "Vegetables", "price": 120, "description": "Fresh green lettuce."},
            {"name": "Spinach", "category": "Vegetables", "price": 85, "description": "Healthy spinach leaves."},
            {"name": "Milk", "category": "Dairy", "price": 190, "description": "Fresh whole milk."},
            {"name": "Cheese", "category": "Dairy", "price": 225, "description": "Cheddar cheese."},
            {"name": "Yogurt", "category": "Dairy", "price": 150, "description": "Creamy yogurt."},
            {"name": "Butter", "category": "Dairy", "price": 175, "description": "Fresh butter."},
            {"name": "Eggs", "category": "Dairy", "price": 160, "description": "Fresh large eggs."},
            {"name": "Bread", "category": "Bakery", "price": 75, "description": "Freshly baked bread."},
            {"name": "Croissant", "category": "Bakery", "price": 180, "description": "Buttery croissants."},
            {"name": "Cake", "category": "Bakery", "price": 300, "description": "Chocolate cake."},
            {"name": "Cookies", "category": "Bakery", "price": 120, "description": "Delicious chocolate chip cookies."},
            {"name": "Rice", "category": "Grains", "price": 90, "description": "White rice."},
            {"name": "Pasta", "category": "Grains", "price": 135, "description": "Spaghetti pasta."},
            {"name": "Flour", "category": "Grains", "price": 110, "description": "All-purpose flour."},
            {"name": "Oats", "category": "Grains", "price": 150, "description": "Healthy oats."},
            {"name": "Sugar", "category": "Pantry", "price": 75, "description": "Granulated sugar."},
            {"name": "Salt", "category": "Pantry", "price": 35, "description": "Table salt."},
            {"name": "Pepper", "category": "Pantry", "price": 50, "description": "Ground black pepper."},
            {"name": "Olive Oil", "category": "Pantry", "price": 265, "description": "Extra virgin olive oil."},
            {"name": "Vinegar", "category": "Pantry", "price": 90, "description": "White vinegar."},
            {"name": "Soy Sauce", "category": "Pantry", "price": 150, "description": "Soy sauce for cooking."},
            {"name": "Cereal", "category": "Breakfast", "price": 180, "description": "Crunchy cereal."},
            {"name": "Granola", "category": "Breakfast", "price": 225, "description": "Healthy granola mix."},
            {"name": "Muesli", "category": "Breakfast", "price": 240, "description": "Mixed muesli."},
            {"name": "Orange Juice", "category": "Beverages", "price": 225, "description": "Freshly squeezed orange juice."},
            {"name": "Coffee", "category": "Beverages", "price": 270, "description": "Ground coffee beans."},
            {"name": "Tea", "category": "Beverages", "price": 180, "description": "Black tea."},
            {"name": "Soda", "category": "Beverages", "price": 135, "description": "Refreshing soda."},
            {"name": "Water", "category": "Beverages", "price": 75, "description": "Bottled water."},
            {"name": "Lemon", "category": "Fruits", "price": 45, "description": "Fresh lemons."},
            {"name": "Grapes", "category": "Fruits", "price": 150, "description": "Fresh grapes."},
            {"name": "Peach", "category": "Fruits", "price": 110, "description": "Sweet peaches."},
            {"name": "Strawberries", "category": "Fruits", "price": 225, "description": "Fresh strawberries."},
            {"name": "Blueberries", "category": "Fruits", "price": 230, "description": "Fresh blueberries."},
            {"name": "Pineapple", "category": "Fruits", "price": 180, "description": "Fresh pineapple."},
            {"name": "Mango", "category": "Fruits", "price": 130, "description": "Fresh mangoes."},
            {"name": "Watermelon", "category": "Fruits", "price": 300, "description": "Juicy watermelon."},
            {"name": "Cucumber", "category": "Vegetables", "price": 60, "description": "Fresh cucumber."},
            {"name": "Zucchini", "category": "Vegetables", "price": 90, "description": "Fresh zucchini."},
            {"name": "Bell Pepper", "category": "Vegetables", "price": 100, "description": "Colorful bell peppers."},
            {"name": "Cabbage", "category": "Vegetables", "price": 110, "description": "Green cabbage."},
            {"name": "Cauliflower", "category": "Vegetables", "price": 150, "description": "Fresh cauliflower."},
            {"name": "Broccoli", "category": "Vegetables", "price": 150, "description": "Fresh broccoli."},
            {"name": "Garlic", "category": "Vegetables", "price": 60, "description": "Fresh garlic."},
            {"name": "Ginger", "category": "Vegetables", "price": 110, "description": "Fresh ginger root."},
            {"name": "Chili Pepper", "category": "Vegetables", "price": 50, "description": "Fresh chili peppers."},
            {"name": "Lime", "category": "Fruits", "price": 40, "description": "Fresh limes."},
            {"name": "Avocado", "category": "Fruits", "price": 110, "description": "Fresh avocados."},
            {"name": "Sweet Potato", "category": "Vegetables", "price": 75, "description": "Fresh sweet potatoes."},
            {"name": "Pumpkin", "category": "Vegetables", "price": 130, "description": "Fresh pumpkin."},
            {"name": "Frozen Peas", "category": "Frozen", "price": 150, "description": "Frozen green peas."},
            {"name": "Frozen Corn", "category": "Frozen", "price": 160, "description": "Frozen corn kernels."},
            {"name": "Frozen Berries", "category": "Frozen", "price": 225, "description": "Frozen mixed berries."},
            {"name": "Ice Cream", "category": "Frozen", "price": 250, "description": "Vanilla ice cream."},
            {"name": "Pizza", "category": "Frozen", "price": 375, "description": "Frozen pizza."},
            {"name": "Chicken Breast", "category": "Meat", "price": 375, "description": "Fresh chicken breast."},
            {"name": "Ground Beef", "category": "Meat", "price": 340, "description": "Fresh ground beef."},
            {"name": "Pork Chops", "category": "Meat", "price": 450, "description": "Fresh pork chops."},
            {"name": "Fish Fillets", "category": "Meat", "price": 525, "description": "Fresh fish fillets."},
            {"name": "Bacon", "category": "Meat", "price": 300, "description": "Crispy bacon."},
            {"name": "Lamb Chops", "category": "Meat", "price": 600, "description": "Fresh lamb chops."},
            {"name": "Tofu", "category": "Vegetarian", "price": 225, "description": "Fresh tofu."},
            {"name": "Tempeh", "category": "Vegetarian", "price": 250, "description": "Fresh tempeh."},
            {"name": "Lentils", "category": "Vegetarian", "price": 150, "description": "Dry lentils."},
            {"name": "Chickpeas", "category": "Vegetarian", "price": 120, "description": "Canned chickpeas."}
          ]';
          
          $data = json_decode($store_data, true);
        try {
            // Make the API request
            $response = $this->client->post('chat/completions', [
                'json' => [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an Indian grocery store assistant. This is the available list in store JSON: ' . json_encode($store_data)],
                         ['role' => 'user', 'content' => $message],
                    ],
                ],
            ]);

            // Decode and extract the response content
            $result = json_decode($response->getBody()->getContents(), true);
            $content = $result['choices'][0]['message']['content'] ?? 'No response';
            $formattedContent = nl2br($content);

            // Save the conversation in the database
            Conversation::create([
                'user' => $user,
                'message' => $message,
                'response' => $formattedContent,
            ]);
            Log::info('Content:', ['content' => $content]);

            return $content;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return 'Error: Unable to process the request.';
        }
    }
}
