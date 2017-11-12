<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Process\Process;

class NetworkConfigCommand extends Command
{
    use ConfirmableTrait;

        /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'network:configure {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure your network.';

    protected $name;
    protected $url;
    protected $connection;
    protected $host;
    protected $port;
    protected $database;
    protected $username;
    protected $password;

    protected $keys = [
        'APP_NAME'      => 'name',
        'APP_URL'       => 'url',
        'DB_CONNECTION' => 'connection',
        'DB_HOST'       => 'host',
        'DB_PORT'       => 'port',
        'DB_DATABASE'   => 'database',
        'DB_USERNAME'   => 'username',
        'DB_PASSWORD'   => 'password',
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $confirmed = $this->confirmToProceed(
            'It looks like you have already completed config for this network.
            Are you sure you want to configure again?',
            function () {
                return $this->laravel['config']['app.name'] !== 'Laravel';
            }
        );

        if (! $confirmed) {
            return;
        }

        $this->name = $this->ask('What is your name?');

        $this->url = $this->ask('What URL is your network accessible from?');

        $this->connection = $this->choice(
            'Which database connection are you using?',
            [
                'sqlite' => 'SQLite',
                'mysql' => 'MySQL',
                'pgsql' => 'PostgreSQL',
                'sqlsrv' => 'Microsoft SQL Server'
            ],
            'sqlite'
        );

        if ($this->connection !== 'sqlite') {
            $this->host = $this->anticipate('What host is your database on?', ['localhost', '127.0.0.1']);

            $this->port = $this->anticipate('What port is your database on?', ['3306']);

            $this->database = $this->ask('What is the name of the database schema you wish to use?');

            $this->username = $this->ask('What is the database username?');

            $this->password = $this->secret('What is the database password?');
        }

        if (! $this->updateEnvironmentFile()) {
            $this->error(".env file couldn't be updated. Please edit manually.");

            return;
        }

        // Run migrations
        if ($this->connection === 'sqlite') {
            $this->createSqliteDatabase();

            $this->call('migrate');
        } else {
            $this->warn("Create your database: {$this->database}");
            $this->warn("Then run database migrations: Execute `php artisan migrate`");
        }

        $this->info("Network config complete. Enjoy!");

        $this->info("Now let's create your user account...");

        $this->call('network:user');
    }

    protected function createSqliteDatabase()
    {
        $dbPath = database_path('database.sqlite');
        $createDb = new Process("touch $dbPath");
        $createDb->run();

        if (! $createDb->isSuccessful()) {
            $this->info("Database created at $dbPath.");
        } else {
            $this->info("Please create your database at $dbPath. Run `touch $dbPath`");
        }
    }

    /**
     * Write a new environment file with the given key.
     *
     * @param  string  $key
     * @return void
     */
    protected function updateEnvironmentFile()
    {
        $env = file_get_contents($this->laravel->environmentFilePath());

        foreach ($this->keys as $key => $var) {
            // Skip if it's not set
            if (! isset($this->key)) {
                continue;
            }

            $env = preg_replace(
                $this->keyReplacementPattern($key),
                $key.'='.(is_string($this->$var) ? '"'.$this->$var.'"' : $this->$var),
                $env,
                1
            );
        }

        return file_put_contents($this->laravel->environmentFilePath(), $env);
    }

    /**
     * Get a regex pattern that will match the appropriate env key.
     *
     * @return string
     */
    protected function keyReplacementPattern($key)
    {
        return "/^$key\=.*$/m";
    }
}
