<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = ['id'];

    public function post()
    {
        return $this->morphTo();
    }
    public function ticket()
    {
        return $this->morphTo();
    }
    public function ad()
    {
        return $this->morphTo();
    }
}