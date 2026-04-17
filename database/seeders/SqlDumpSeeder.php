<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SqlDumpSeeder extends Seeder
{
    public function run(): void
    {
        $sqlPath = database_path('sql_dump.sql');
        if (!File::exists($sqlPath)) {
            return;
        }

        $sql = File::get($sqlPath);
        
        // Split SQL into individual statements
        // This is a simple regex split, might need tuning for very complex SQL
        $statements = array_filter(array_map('trim', explode(';', $sql)));

        foreach ($statements as $statement) {
            // Only execute INSERT statements
            if (stripos($statement, 'INSERT INTO') === 0) {
                // Mapping old role 'admin' to 'admin_utama' and 'guest' to 'user' in users table
                if (stripos($statement, "`users`") !== false) {
                    $statement = str_replace("'admin'", "'admin_utama'", $statement);
                    $statement = str_replace("'guest'", "'user'", $statement);
                }
                
                DB::unprepared($statement . ';');
            }
        }
    }
}
