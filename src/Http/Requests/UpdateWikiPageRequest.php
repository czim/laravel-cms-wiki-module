<?php
namespace Czim\CmsWikiModule\Http\Requests;

class UpdateWikiPageRequest extends Request
{

    public function rules()

    {
        return [
            'slug'  => ['string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$'],
            'title' => 'required|string|max:255',
            'body'  => 'string',
        ];
    }

}
