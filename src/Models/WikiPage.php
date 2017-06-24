<?php
namespace Czim\CmsWikiModule\Models;
use Doctrine\Common\Collections\Collection;

/**
 * Class WikiPage
 *
 * @property integer        $id
 * @property string         $slug
 * @property string         $title
 * @property string         $body
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Collection|WikiPageEdit[] $edits
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
