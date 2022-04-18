<?php

namespace App\Models;

use App\Casts\PhoneNumber;
use App\Http\Services\PhoneUtil\PhoneUtilInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use libphonenumber\PhoneNumberUtil;

/**
 * App\Models\Contact
 *
 * @package App\Models
 *
 * App\Models\Contact
 * @OA\Schema (
 *  title="Contact",
 *  type="object",
 *  @OA\Property(
 *      property="id",
 *      type="integer"
 *  ),
 *  @OA\Property(
 *      property="name",
 *      type="string"
 *  ),
 *  @OA\Property(
 *      property="phone",
 *      type="string"
 *  )
 * )
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property bool $favourite
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereFavourite($value)
 * @mixin \Eloquent
 */
class Contact extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $hidden = [
        'created_at',
        'updated_at',
        'user_id'
    ];

    protected $fillable = [
        'name',
        'phone',
        'favourite'
    ];

    protected $casts = [
        'favourite' => 'boolean',
        'phone' => PhoneNumber::class
    ];
}
