<?php

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Letter;

class LetterTransformers extends TransformerAbstract
{

    public function transform(Letter $letter)
    {
        return [
            'id' => $letter->id,
            'content' => $letter->content,
        ];
    }
}