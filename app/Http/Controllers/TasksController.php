<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TasksController extends Controller
{

    public function index()
    {
        // Authenticate to obtain an access token
        $authResponse = Http::withHeaders([
            'Authorization' => 'Basic QVBJX0V4cGxvcmVyOjEyMzQ1NmlzQUxhbWVQYXNz',
            'Content-Type' => 'application/json',
        ])->post('https://api.baubuddy.de/index.php/login', [
            'username' => '365',
            'password' => '1',
        ]);

        if ($authResponse->successful()) {
            $accessToken = $authResponse['oauth']['access_token'] ?? null;

            // Fetch tasks using the obtained access token
            $tasksResponse = Http::withToken($accessToken)
                ->get('https://api.baubuddy.de/dev/index.php/v1/tasks/select');

            if ($tasksResponse->successful()) {
                $tasks = $tasksResponse->json() ?? [];

                // Check the content of $tasks
                // dd($tasks);

                return view('tasks.index', compact('tasks'));
                
            } else {
                // Display error information if tasks retrieval fails
                return "Tasks Retrieval Error: " . $tasksResponse->status();
            }
        } else {
            // Display error information if authentication fails
            return "Authentication Error: " . $authResponse->status();
        }
    }

    public function refreshTask()
    {
        // Authenticate to obtain an access token
        $authResponse = Http::withHeaders([
            'Authorization' => 'Basic QVBJX0V4cGxvcmVyOjEyMzQ1NmlzQUxhbWVQYXNz',
            'Content-Type' => 'application/json',
        ])->post('https://api.baubuddy.de/index.php/login', [
            'username' => '365',
            'password' => '1',
        ]);

        if ($authResponse->successful()) {
            $accessToken = $authResponse['oauth']['access_token'] ?? null;

            // Fetch tasks using the obtained access token
            $tasksResponse = Http::withToken($accessToken)
                ->get('https://api.baubuddy.de/dev/index.php/v1/tasks/select');

            if ($tasksResponse->successful()) {
                $tasks = $tasksResponse->json() ?? [];

                // Check the content of $tasks
                // dd($tasks);

                return response()->json($tasks);
                
            } else {
                // Display error information if tasks retrieval fails
                return "Tasks Retrieval Error: " . $tasksResponse->status();
            }
        } else {
            // Display error information if authentication fails
            return "Authentication Error: " . $authResponse->status();
        }

    }
}
