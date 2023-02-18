<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $title
 * @method static create(array $all)
 * @method static pluck(string $string, string $string1)
 * @method static where(string $string, $slug)
 */
class Category extends Model
{
    use HasFactory;
    use Sluggable;

    /** @var array  */
    protected $fillable = ['title'];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
