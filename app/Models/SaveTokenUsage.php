<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaveTokenUsage extends Model
{
    protected $fillable = [
        'user_id',
        "user_name",
        "user_email",
        "session_token",
        "prompt_tokens",
        "completion_tokens",
        "total_tokens"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
