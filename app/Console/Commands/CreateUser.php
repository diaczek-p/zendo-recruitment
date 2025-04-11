<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {name} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user and generate an API token.';

    /**
     * Execute the console command.
     *
     * @return int Exit status code (0 for success, 1 for failure).
     */
    public function handle(): int
    {
        // Fetch command arguments
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');

        // Validate input
        $validator = Validator::make(
            compact('name', 'email', 'password'),
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8'],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1; // Return non-zero for error
        }

        // Create a new user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        // Generate an API token
        $token = $user->createToken('user-token')->plainTextToken;

        // Output the generated token
        $this->info('User created successfully.');
        $this->info('API Token: ' . $token);

        return 0; // Return 0 for success
    }
}
