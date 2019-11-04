<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use LogsActivity;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

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
    protected $fillable = ['name', 'image', 'description', 'price','category_id'];



    /**
     * Change activity log event description
     *
     * @param string $eventName
     *
     * @return string
     */
    public function getDescriptionForEvent($eventName)
    {
        return __CLASS__ . " model has been {$eventName}";
    }


    protected $appends = array('category_name');


    public function getCategoryNameAttribute() {
        try {
            $productCategory = ProductCategory::where('id',$this->category_id)->get();
            if ($productCategory->isEmpty() !== true)
                    return $productCategory->first()->name;
            return '';
        } catch (Exception $ex) {
            return '';
        }
    }

}
