<?php

namespace App\Models\Notice;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NoticeBoard extends Model
{
    use HasFactory;

    protected $table = 'notice_boards';
    protected $fillable = ['Notice_Title', 'Notice_Desc', 'status', 'user_id', 'created_by'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function createdAt(): Attribute
    {
        return new Attribute(
            get: fn($value) => date('F d, Y H:i:s A', strToTime($value)),
            set: fn($value) => $value,
        );
    }

    public function updatedAt(): Attribute
    {
        return new Attribute(
            get: fn($value) => date('F d, Y H:i:s A', strToTime($value)),
            set: fn($value) => $value,
        );
    }
}
