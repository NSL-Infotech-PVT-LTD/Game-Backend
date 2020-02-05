<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Game;

class Competition extends Model {

    use LogsActivity;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'competitions';

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
    protected $appends = ['readyto_go', 'my_competition_status', 'competition_date_status', 'is_going_live'];
    protected $fillable = ['image', 'description', 'name', 'date', 'fee', 'prize_details', 'game_id', 'competition_category_id', 'hot_competitions', 'sequential_fee','start_time','state'];

    /**
     * Change activity log event description
     *
     * @param string $eventName
     *
     * @return string
     */
    public function getIsGoingLiveAttribute($value) {
//        $date = \Carbon\Carbon::createFromFormat('Y-m-d', $this->date)->subDays(1)->toDateString();
        if (\Carbon\Carbon::createFromFormat('Y-m-d', $this->date)->subDays(1)->toDateString() == \Carbon\Carbon::now()->toDateString()):
            $date = \Carbon\Carbon::createFromFormat('Y-m-d h:i:s', date("Y-m-d h:i:s", strtotime($this->date)));
            $current = \Carbon\Carbon::now();
            return $date->diffInSeconds($current);
        else:
            return 0;
        endif;
    }

    public function getCompetitionDateStatusAttribute($value) {
        try {
//            dd(\Carbon\Carbon::now()->toDateString());
//        dd($model->toArray());
            if ($this->date == \Carbon\Carbon::now()->toDateString())
                return 'Live';
            if ($this->date > \Carbon\Carbon::now()->toDateString())
                return 'UpComing';
            if ($this->date < \Carbon\Carbon::now()->toDateString())
                return 'Ended';
            return 'NAN';
        } catch (\Exception $ex) {
            return 'NAN';
        }
    }

    public function getmyCompetitionStatusAttribute($value) {
        try {
            $model = CompetitionUser::where('competition_id', $this->id)->where('player_id', \Auth::id())->get();
//        dd($model->toArray());
            if ($model->isEmpty())
                return 'Not_Enroll_Yet';
            $modelCompetitionPayment = CompetitionUserPayment::where('competition_user_id', $model->first()->id)->get();
            
            if ($modelCompetitionPayment->isEmpty() != true && $model->first()->state == 0)
                return 'Payed';
//            if ($model->first()->payment_param_2 !== null)
//                return 'Max_Allowance';
            if ($modelCompetitionPayment->isEmpty() != true && $model->first()->state == 1)
                return 'Enrolled';
            if ($modelCompetitionPayment->isEmpty() != true)
                return 'Payed';
            return 'NAN';
        } catch (\Exception $ex) {
            return 'NAN';
        }
    }

    public function getReadytoGoAttribute($value) {
        return \Carbon\Carbon::createFromTimeStamp(strtotime($this->date))->diffForHumans();
    }

    public function game() {
        return $this->hasOne(Game::class, 'id', 'game_id')->select('id', 'name', 'image');
    }

    public function category() {
        return $this->hasOne(CompetitionCategory::class, 'id', 'competition_category_id')->select('id', 'name');
    }

    public function getDescriptionForEvent($eventName) {
        return __CLASS__ . " model has been {$eventName}";
    }

}
