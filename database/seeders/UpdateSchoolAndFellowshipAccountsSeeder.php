<?php

namespace Database\Seeders;

use App\Enums\RoleCode;
use App\Models\AcademicYear;
use App\Models\Mentor;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UpdateSchoolAndFellowshipAccountsSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::query()->first();
        if ($school) {
            $school->update([
                'code' => 'ZP-KUCHI-02',
                'name' => 'Z. P. Primary School No. 2 Kuchi',
                'name_marathi' => 'जिल्हा परिषद शाळा नं. २ कुची, ता. कवठेमहांकाळ, जि. सांगली',
                'email' => 'zpschool2kuchi@gmail.com',
            ]);
        }

        $superAdminRole = Role::query()->where('code', RoleCode::SuperAdmin->value)->first();
        $schoolAdminRole = Role::query()->where('code', RoleCode::SchoolAdmin->value)->first();
        $mentorRole = Role::query()->where('code', RoleCode::Mentor->value)->first();

        // 1. Create or update Amol Hankare's main account
        $amolUser = User::query()->updateOrCreate(
            ['email' => 'amolhankare04@gmail.com'],
            [
                'name' => 'श्री. अमोल किसन हंकारे',
                'password' => Hash::make('password'),
                'role_id' => $superAdminRole?->id,
                'school_id' => $school?->id,
                'preferred_locale' => 'mr',
                'is_active' => true,
            ]
        );

        // Also update admin@example.test to show Amol Hankare
        $adminUser = User::query()->where('email', 'admin@example.test')->first();
        if ($adminUser) {
            $adminUser->update([
                'name' => 'श्री. अमोल किसन हंकारे (Admin)',
            ]);
        }

        // 2. Update mentor user to Shri Somnath Walke
        $mentorUser = User::query()->where('email', 'mentor@example.test')->first();
        if ($mentorUser) {
            $mentorUser->update([
                'name' => 'श्री. सोमनाथ वाळके (मेंतॉर)',
            ]);
        }

        // Also create direct somnath.walke@example.test
        $somnathUser = User::query()->updateOrCreate(
            ['email' => 'somnath.walke@example.test'],
            [
                'name' => 'श्री. सोमनाथ वाळके',
                'password' => Hash::make('password'),
                'role_id' => $mentorRole?->id,
                'school_id' => $school?->id,
                'preferred_locale' => 'mr',
                'is_active' => true,
            ]
        );

        // Ensure mentor profile exists for Somnath Walke
        if ($somnathUser && ! Mentor::query()->where('user_id', $somnathUser->id)->exists()) {
            Mentor::query()->create([
                'user_id' => $somnathUser->id,
                'school_id' => $school?->id,
                'employee_number' => 'SPIF-MEN-01',
            ]);
        }

        // Ensure mentor profile exists for Amol Hankare as well so he can act as mentor/teacher
        if ($amolUser && ! Mentor::query()->where('user_id', $amolUser->id)->exists()) {
            Mentor::query()->create([
                'user_id' => $amolUser->id,
                'school_id' => $school?->id,
                'employee_number' => 'SPFEDU-0415',
            ]);
        }
    }
}
