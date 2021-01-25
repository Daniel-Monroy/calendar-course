<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Calendar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'start', 'end', 'color', 'user_id',
    ];

    protected function getTitleAttribute()
    {
        return date_format(date_create($this->start), 'H:i').' - '.date_format(date_create($this->end), 'H:i').' '.mb_strtoupper($this->name);
    }

    protected $appends = ['title'];

    protected static function boot()
    {
        parent::boot();
        static::saving(function($cal) {
        	$cal->user_id = auth()->user()->id;
        });
    }
}
