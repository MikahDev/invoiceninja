<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\Request;
use App\Models\Client;

class DestroyClientRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize() : bool
    {
        return auth()->user()->can('edit', $this->client);
    }

}
