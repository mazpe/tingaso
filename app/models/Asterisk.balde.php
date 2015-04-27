<?php
class Asterisk extends \Eloquent {
    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];
    // Don't forget to fill this array
    //protected $fillable = [];
    protected $fillable = array('name', 'value', 'updated_by_id');

}