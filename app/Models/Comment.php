<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed text
 * @property mixed post_id
 * @property mixed user_id
 * @property int|mixed status
 * @method static where(string $string, int $int)
 */
class Comment extends Model
{
    use HasFactory;

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function allow(): void
    {
        $this->status = 1;
        $this->save();
    }

    public function disAllow(): void
    {
        $this->status = 0;
        $this->save();
    }

    public function toggleStatus()
    {
        if ($this->status == 0) {
            $this->allow();
            return;
        }
        $this->disAllow();
    }

    public function remove()
    {
        $this->delete();
    }
}
