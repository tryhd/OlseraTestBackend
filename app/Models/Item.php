<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Item
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Tax[] $taxes
 *
 * @package App\Models
 */
class Item extends Model
{
	protected $table = 'items';

	protected $fillable = [
		'name'
	];

	public function taxes()
	{
		return $this->belongsToMany(Tax::class, 'item_taxes')
					->withPivot('id')
					->withTimestamps();
	}
}
