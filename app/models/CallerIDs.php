<?php
class CallerID extends \Eloquent {
    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];
    // Don't forget to fill this array
    //protected $fillable = [];
    protected $fillable = array('area_code', 'prefix', 'number','status','full_number','updated_by_id');

}