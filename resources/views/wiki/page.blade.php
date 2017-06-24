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
                    {{ $title }}
                </a>
            </li>
        @endif

        <li>
            {{ $title }}
        </li>

    </ol>
@endsection


@section('content')

    <div class="page-header">

        <div class="btn-toolbar pull-right">

            <div class="btn-group">
                @if (cms()->auth()->can('wiki.page.create'))
                    <a href="{{ cms_route("wiki.page.create") }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> &nbsp;
                        {{ cms_trans('wiki.button.new-page') }}
                    </a>
                @endif
            </div>
        </div>

        <h1>{{ $title }}</h1>
    </div>

    <div id="wiki-page">
        {!! $page->body !!}
    </div>

@endsection
