<?php
namespace Czim\CmsWikiModule\Models;

/**
 * Class WikiPageEdit
 *
 * @property integer        $id
 * @property string         $author
 * @property \Carbon\Carbon $date
 */
class WikiPageEdit extends AbstractModel
{
    protected $table = 'wiki_page_edits';

    public $timestamps = false;

    protected $fillable = [
        'author',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function page()
    {
        return $this->belongsTo(WikiPage::class);
    }

}
