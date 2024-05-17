<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTable extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'events_table';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cast_name',
        'main_cast_name',
        'is_translated',
        'channel_name',
        'upload_date',
        'play_date',
    ];
}
