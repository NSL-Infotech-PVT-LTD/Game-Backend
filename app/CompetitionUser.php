<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompetitionUser extends Model {

    use LogsActivity;
    use SoftDeletes;

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['player_id', 'competition_id', 'score', 'status', 'params', 'state'];

    /**
     * Change activity log event description
     *
     * @param string $eventName
     *
     * @return string
     */
    public function getDescriptionForEvent($eventName) {
        return __CLASS__ . " model has been {$eventName}";
    }

    public function competition() {
        return $this->hasOne(Competition::class, 'id', 'competition_id')->select('id', 'name', 'image', 'description', 'date', 'fee', 'prize_details', 'game_id', 'competition_category_id');
    }

    public function player() {
        return $this->hasOne(User::class, 'id', 'player_id');
    }
    public function payments() {
        return $this->hasMany(CompetitionUserPayment::class, 'competition_user_id', 'id');
    }

    public function getParamsAttribute($value) {
        return $value!=null?json_decode($value):(object)[];
    }

}
