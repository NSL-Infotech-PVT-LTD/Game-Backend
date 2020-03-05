<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Banner;
class User extends Authenticatable implements MustVerifyEmail {

    use SoftDeletes;
    use HasApiTokens,
        Notifiable,
        HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'status', 'image', 'mobile', 'country', 'image_url'
        , 'social_type', 'social_id', 'social_password', 'age'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $appends = array('role', 'fullname', 'participateGames', 'wonGames');

    public function getParticipateGamesAttribute() {
        try {
            return CompetitionUser::where('player_id', \Auth::id())->get()->count();
        } catch (\Exception $ex) {
            return 0;
        }
    }

    public function getWonGamesAttribute() {
        try {
            return CompetitionUser::where('status', 'winner')->where('player_id', \Auth::id())->get()->count();
        } catch (\Exception $ex) {
            return 0;
        }
    }

    public function getFullNameAttribute() {
        try {

            return $this->first_name . ' ' . $this->last_name;
        } catch (\Exception $ex) {
            return [];
        }
    }

    public function getRoleAttribute() {
        try {
            $rolesID = \DB::table('role_user')->where('user_id', $this->id)->pluck('role_id');
            if ($rolesID->isEmpty() !== true):
                $role = Role::whereIn('id', $rolesID);
                if ($role->get()->isEmpty() !== true)
                    return $role->select('name', 'id')->with('permission')->first();
            endif;
            return [];
        } catch (Exception $ex) {
            return [];
        }
    }


    public static function usersIdByPermissionName($name) {

        $permissions = \App\Permission::where('name', 'like', '%' . $name . '%')->get();
        if ($permissions->isEmpty())
            return [];
        $role = \DB::table('permission_role')->where('permission_id', $permissions->first()->id)->get();
        if ($role->isEmpty())
            return [];
        return \DB::table('role_user')->whereIN('role_id', $role->pluck('role_id'))->pluck('user_id')->toArray();
    }

}
