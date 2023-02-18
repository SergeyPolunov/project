<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


/**
 * @method static create(array $all)
 * @method static paginate(int $int)
 * @method static where(string $string, $slug)
 * @method static orderBy(string $string, string $string1)
 * @property int|mixed user_id
 * @property mixed|string image
 * @property int|mixed category_id
 * @property int|mixed status
 * @property int|mixed is_featured
 * @property mixed category
 * @property mixed tag
 * @property mixed tags
 * @property mixed date
 * @property mixed id
 */
class Post extends Model
{
    use HasFactory;
    use Sluggable;

    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;

    protected $fillable = ['title', 'content', 'date', 'description'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'post_tags',
            'post_id',
            'tag_id'
        );
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

    public static function add(array $fields): Post
    {
        $post = new static;
        $post->fill($fields);
        $post->user_id = 1;
        $post->save();

        return $post;
    }

    public function edit(array $fields): void
    {
        $this->fill($fields);
        $this->save();
    }

    public function remove(): void
    {
        $this->removeImage();
        $this->delete();
    }

    public function removeImage(): void
    {
        if (!is_null($this->image)) {
            Storage::delete('uploads/' . $this->image);
        }
    }

    public function uploadImage($image)
    {
        if (is_null($image)) {
            return;
        }

        $this->removeImage();
        $filename = str_random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $filename);
        $this->image = $filename;
        $this->save();
    }

    public function getImage(): string
    {
        if (is_null($this->image)) {
            return '/img/no-image.png';
        }

        return '/uploads/' . $this->image;
    }

    public function setCategory(int $id)
    {
        if (is_null($id)) {
            return;
        }

        $this->category_id = $id;
        $this->save();
    }

    public function setTags($ids)
    {
        if (is_null($ids)) {
            return;
        }

        $this->tags()->sync($ids);
    }

    public function setDraft(): void
    {
        $this->status = self::IS_DRAFT;
        $this->save();
    }

    public function setPublic(): void
    {
        $this->status = self::IS_PUBLIC;
        $this->save();
    }

    public function toggleStatus($value): void
    {
        if ($value == null) {
            $this->setDraft();
        }
        $this->setPublic();
    }

    public function setFeatured(): void
    {
        $this->is_featured = 1;
        $this->save();
    }

    public function setStandart(): void
    {
        $this->is_featured = 0;
        $this->save();
    }

    public function toggleFeatured($value): void
    {
        if ($value == null) {
            $this->setStandart();
        }
        $this->setFeatured();
    }

    public function setDateAttribute($value): void
    {
        $date = Carbon::createFromFormat('d/m/y', $value)->format('Y-m-d');
        $this->attributes['date'] = $date;
    }

    public function getDateAttribute(): string
    {
        return Carbon::createFromFormat('Y-m-d', $this->attributes['date'])->format('d/m/y');
    }

    public function getCategoryTitle(): string
    {
        if (!is_null($this->category)) {
            return $this->category->title;
        }
        return 'Has no category';
    }

    public function getTagsTitles(): string
    {
        if (!empty($this->tags)) {
            return implode(', ', $this->tags->pluck('title')->all());
        }
        return 'Has no tags';
    }

    public function getCategoryID()
    {
        return $this->category != null ? $this->category->id : null;
    }

    public function getDate(): string
    {
        return Carbon::createFromFormat('d/m/y', $this->date)->format('F d, Y');
    }

    public function hasPrevious()
    {
        return self::where('id', '<', $this->id)->max('id');
    }

    public function getPrevious()
    {
        $postID = $this->hasPrevious();
        return self::find($postID);
    }

    public function hasNext()
    {
        return self::where('id', '>', $this->id)->min('id');
    }

    public function getNext()
    {
        $postID = $this->hasNext();
        return self::find($postID);
    }

    public function related(): Collection
    {
        return self::all()->except($this->id);
    }

    public function hasCategory(): bool
    {
        return (!is_null($this->category));
    }

    public static function getPopularPosts()
    {
        return self::orderBy('views', 'desc')->take(3)->get();
    }

    public static function getFeaturedPosts()
    {
        return self::where('is_featured', 1)->take(3)->get();
    }

    public static function getRecentPosts()
    {
        return self::orderBy('date', 'desc')->take(4)->get();
    }
}
