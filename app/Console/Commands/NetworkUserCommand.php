<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Hackzilla\PasswordGenerator\Generator\RequirementPasswordGenerator as PasswordGenerator;

class NetworkUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'network:user
                        {--P|generate : Generate a password.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create your user account';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        start:
        $name = $this->ask('What is your name? (Hint: just your first name is fine)');
        $email = $this->ask('Enter an email address (Hint: it does not have to be a real account)');

        $show_password = false;

        if ($this->option('generate')) {
            $password = $password_confirmation = (new PasswordGenerator)->setLength(12)
                ->setOptionValue(PasswordGenerator::OPTION_SYMBOLS, true)
                ->setMinimumCount(PasswordGenerator::OPTION_UPPER_CASE, 1)
                ->setMinimumCount(PasswordGenerator::OPTION_LOWER_CASE, 1)
                ->setMinimumCount(PasswordGenerator::OPTION_NUMBERS, 1)
                ->generatePassword();

            $show_password = true;
        } else {
            enter_password:
            $password = $this->secret('Set your password (must be at least 8 characters long, containing uppercase,' .
                                      'lowercase, and numeric characters)');
            $password_confirmation = $this->secret('Please re-type your password to confirm it');

            if ($password != $password_confirmation) {
                $this->alert('Password not confirmed. Please try again.');
                goto enter_password;
            }
        }

        $validator = Validator::make(compact('name', 'email', 'password', 'password_confirmation'), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).*$/',],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $this->alert('There were problems with the details you provided');
            $n = 1;

            foreach ($errors->all() as $message) {
                $this->error($n++ . '. ' . $message);
            }

            $this->line('');

            $this->line('Please try again');

            goto start;
        }

        if ($user = User::create(['name' => $name, 'email' => $email, 'password' => bcrypt($password)])) {
            $password = ($show_password ? '. Your password is: '.$password.' Do not share it with anyone!' : '');

            $this->info('Your user account has been created! '.$password);

            $this->line('<comment>Now login to your dashboard over at '.$this->laravel['config']['app.url'].'/login.</comment>');
        }
    }
}
