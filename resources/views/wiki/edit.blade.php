@extends(cms_config('views.layout'))

@php
    if ($creating) {
        $shortTitle = ucfirst(cms_trans('common.action.create')) . ' ' . cms_trans('common.terms.page');
        $title      = $shortTitle;
        $formAction = cms_route('wiki.page.store');
        $cancelUrl  = cms_route('wiki.home');
    } else {
        $shortTitle = ucfirst(cms_trans('common.action.edit')) . ' ' . cms_trans('common.terms.page');
        $title      = $shortTitle . ': ' . $page->slug;
        $formAction = cms_route('wiki.page.update', [ $page->getKey() ]);
        $cancelUrl  = cms_route('wiki.page', [ $page->slug ]);
    }
@endphp

@section('title', $title)

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li>
            <a href="{{ cms_route(\Czim\CmsCore\Support\Enums\NamedRoute::HOME) }}">
                {{ ucfirst(cms_trans('common.home')) }}
            </a>
        </li>

        <li>
            <a href="{{ cms_route('wiki.home') }}">
                {{ cms_trans('wiki.menu.home') }}
            </a>
        </li>

        <li>
            {{ $title }}
        </li>

    </ol>
@endsection


@section('content')


    <div class="row">

        <div class="col-md-6">

            <h3>{{ $shortTitle }}</h3>

            <hr>

            <form method="post" action="{{ $formAction }}" class="wiki-form">

                @if ( ! $creating)
                    {{ method_field('put') }}
                @endif
                {{ csrf_field() }}

                <div class="form-group @if ($errors->has('slug')) has-error @endif">
                    <label class="control-label required" for="input-slug">
                        {{ ucfirst(cms_trans('common.terms.slug')) }} *
                    </label>

                    @if ($creating)
                        <input name="slug" type="text" class="form-control" id="input-slug" value="{{ old('slug', $page->slug) }}" required="required">
                    @else
                        <p class="form-control-static">{{ $page->slug }}</p>
                    @endif
                </div>

                <div class="form-group @if ($errors->has('title')) has-error @endif">
                    <label class="control-label required" for="input-title">
                        {{ ucfirst(cms_trans('common.terms.title')) }} *
                    </label>

                    <input name="title" type="text" class="form-control" id="input-title" value="{{ old('title', $page->title) }}" required="required">
                </div>

                <div class="form-group @if ($errors->has('body')) has-error @endif">
                    <label class="control-label required" for="input-body">
                        {{ ucfirst(cms_trans('common.terms.page')) }}
                    </label>

                    <textarea name="body" class="form-control" id="input-body" rows="{{ config('cms-wiki-module.editor.rows', 20) }}">{{ old('body', $page->body) }}</textarea>
                </div>

                <div>
                    &nbsp;
                </div>

                <hr>

                <div class="form-group edit-button-row clearfix">

                    <div class="col-sm-4">
                        <a href="{{ $cancelUrl }}" class="btn btn-default edit-button-cancel">
                            <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span>
                            {{ ucfirst(cms_trans('common.buttons.cancel')) }}
                        </a>
                    </div>

                    <div class="col-sm-8">

                        <div class="btn-group pull-right" role="group" aria-label="save">
                            <button type="submit" class="btn btn-success edit-button-save">
                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                {{ ucfirst(cms_trans('common.action.save')) }}
                            </button>
                        </div>

                    </div>
                </div>

            </form>


        </div>

        <div class="col-md-6">

            <h3>{{ ucfirst(cms_trans('common.terms.preview')) }}</h3>

            <hr>

            <div id="wiki-preview-markdown"></div>

            <hr>

        </div>
    </div>

@endsection

@cms_scriptonce
<script>
    $(function() {

        /**
         * Converts the markdown contents of the wiki page body to a HTML preview.
         */
        var convertWikiPageMarkdown = function() {

            var converter = new showdown.Converter({
                    tables: true,
                    tasklists: true,
                    smoothLivePreview: true,

                });

            @php
                $flavor = config('cms-wiki.module.markdown.strategy')
            @endphp

            @if ($flavor == 'github')
                converter.setFlavor('github');
            @elseif ($flavor == 'traditional')
                converter.setFlavor('original');
            @endif

            $('#wiki-preview-markdown').html(converter.makeHtml($('#input-body').val()));
        };

        convertWikiPageMarkdown();

        $(document).on('keyup', '#input-body', function() {
            convertWikiPageMarkdown();
        });
    });
</script>
@cms_endscriptonce

@cms_scriptonce
<script src="{{ asset('_cms/js/wiki/showdown.min.js') }}"></script>
@cms_endscriptonce
