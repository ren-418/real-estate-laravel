<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param array<string, string> $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return DB::transaction(function () use ($input) {
            return tap(User::create([
                'name'     => $input['name'],
                'email'    => $input['email'],
                'password' => Hash::make($input['password']),
            ]), function (User $user) {
                $team = $this->assignOrCreateTeam($user);
                $user->switchTeam($team);
                setPermissionsTeamId($team->id);
                $user->assignRole('free');
            });
        });
    }

    /**
     * Assign the user to the first team or create a personal team.
     */
    protected function assignOrCreateTeam(User $user): Team
    {
        $firstTeam = Team::first();

        if ($firstTeam) {
            $user->teams()->attach($firstTeam);
            return $firstTeam;
        }

        return $user->ownedTeams()->save(Team::forceCreate([
            'user_id'       => $user->id,
            'name'          => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }
}
