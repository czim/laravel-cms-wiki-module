@extends(cms_config('views.layout'))

@section('title', $title)

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li>
            <a href="{{ cms_route(\Czim\CmsCore\Support\Enums\NamedRoute::HOME) }}">
                {{ ucfirst(cms_trans('common.home')) }}
            </a>
        </li>

        @if ( ! $isHome)
            <li>
                <a href="{{ cms_route('wiki.home') }}">
                    {{ cms_trans('wiki.menu.home') }}
                </a>
            </li>
        @endif

        <li>
            {{ $title }}
        </li>

    </ol>
@endsection


@section('content')

    <div class="page-header wiki-header">

        <div class="btn-toolbar pull-right">

            @if (cms()->auth()->can('wiki.page.edit'))
            <div class="btn-group">
                <a href="{{ cms_route("wiki.page.edit", [ $page->id ]) }}" class="btn btn-default">
                    <i class="fa fa-edit"></i> &nbsp;
                    {{ cms_trans('wiki.button.edit-page') }}
                </a>
            </div>
            @endif

            <div class="btn-group">
                <a href="{{ cms_route("wiki.page.index") }}" class="btn btn-default">
                    <i class="fa fa-list"></i> &nbsp;
                    {{ cms_trans('wiki.button.list-pages') }}
                </a>

                @if (cms()->auth()->can('wiki.page.create'))
                    <a href="{{ cms_route("wiki.page.create") }}" class="btn btn-default">
                        <i class="fa fa-plus"></i> &nbsp;
                        {{ cms_trans('wiki.button.new-page') }}
                    </a>
                @endif
            </div>
        </div>

        <h1>{{ $title }}</h1>
    </div>

    <div class="wiki-body">
        {!! $body !!}
    </div>

    <hr>

    <div class="wiki-footer">
        @if ($lastEdit)
            <span class="pull-right text-muted small">
                {{ cms_trans('wiki.page.last-edited-by') }}
                <b>{{ $lastEdit->author }}</b>
                (<em>{{ $lastEdit->date->format('Y-m-d H:i') }}</em>)
            </span>
        @endif
    </div>

@endsection
