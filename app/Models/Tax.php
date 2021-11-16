<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tax
 * 
 * @property int $id
 * @property string $name
 * @property float $rate
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Item[] $items
 *
 * @package App\Models
 */
class Tax extends Model
{
	protected $table = 'taxes';

	protected $casts = [
		'rate' => 'float'
	];

	protected $fillable = [
		'name',
		'rate'
	];

	public function items()
	{
		return $this->belongsToMany(Item::class, 'item_taxes')
					->withPivot('id')
					->withTimestamps();
	}
}
