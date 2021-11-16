<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ItemTax
 * 
 * @property int $id
 * @property int $item_id
 * @property int $tax_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Item $item
 * @property Tax $tax
 *
 * @package App\Models
 */
class ItemTax extends Model
{
	protected $table = 'item_taxes';

	protected $casts = [
		'item_id' => 'int',
		'tax_id' => 'int'
	];

	protected $fillable = [
		'item_id',
		'tax_id'
	];

	public function item()
	{
		return $this->belongsTo(Item::class);
	}

	public function tax()
	{
		return $this->belongsTo(Tax::class);
	}
}
