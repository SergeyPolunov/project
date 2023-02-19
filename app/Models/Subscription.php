<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed email
 * @property mixed|string token
 * @method static where(string $string, string $token)
 * @method static create(array $all)
 */
class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['email'];

    public static function add($email)
    {
        $sub = new static;
        $sub->email = $email;
        $sub->save();

        return $sub;
    }

    public function generateToken()
    {
        $this->token = str_random(100);
        $this->save();
    }

    public function remove()
    {
        $this->delete();
    }
}
