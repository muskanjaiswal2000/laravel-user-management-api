<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['Authentication', 'ProfileManagement', 'Admin'] as $module) {
            DB::table('module_states')->updateOrInsert(
                ['name' => $module],
                [
                    'version' => '1.0.0',
                    'status' => 'enabled',
                    'enabled' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        DB::table('module_states')
            ->whereIn('name', ['Authentication', 'ProfileManagement', 'Admin'])
            ->delete();
    }
};
