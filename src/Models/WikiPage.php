<?php
namespace Czim\CmsWikiModule\Models;

/**
 * Class WikiPage
 *
 * @property integer        $id
 * @property string         $slug
 * @property string         $title
 * @property string         $body
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class WikiPage extends AbstractModel
{
    protected $table = 'wiki_pages';

    protected $fillable = [
        'slug',
        'title',
        'body',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function edits()
    {
        return $this->hasMany(WikiPageEdit::class)->orderBy('date', 'desc');
    }

}
