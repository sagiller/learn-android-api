<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Image extends Model {
	protected $table = 'images';
    protected $fillable = [
        'owner_id', 'owner_type','type', 'path',
    ];

    /**
     * Get the owner of the image.
     */
    public function owner()
    {
        return $this->morphTo('owner');
    }

}