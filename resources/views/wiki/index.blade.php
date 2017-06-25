@extends(cms_config('views.layout'))

@php
    $title = cms_trans('wiki.list.title');
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

    <div class="page-header">

        <div class="btn-toolbar pull-right">

            <div class="btn-group">
                <a href="{{ cms_route("wiki.home") }}" class="btn btn-default">
                    <i class="fa fa-home"></i> &nbsp;
                    {{ cms_trans('wiki.button.home') }}
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

    <div class="row">
        <div class="wiki-listing">

            <table class="table table-striped table-hover records-table">

                <thead>
                <tr>
                    <th class="column">{{ cms_trans('common.terms.slug') }}</th>
                    <th class="column">{{ cms_trans('common.terms.title') }}</th>
                    <th class="column column-center">{{ ucfirst(cms_trans('common.attributes.created-at')) }}</th>
                    <th class="column column-center">{{ ucfirst(cms_trans('common.attributes.updated-at')) }}</th>
                    <th class="column">&nbsp;</th>
                </tr>
                </thead>

                <tbody>

                @forelse ($pages as $page)

                    @php
                        $actionUrl = cms_auth()->can('wiki.page.edit')
                                    ?   cms_route('wiki.page.edit', [ $page->getKey() ])
                                    :   cms_route('wiki.page', [ $page->slug ]);
                    @endphp

                    <tr class="records-row" data-id="{{ $page->getKey() }}" data-reference="{{ $page->title }}" data-default-action-url="{{ $actionUrl }}">

                        <td class="column default-action">
                            <a href="{{ $actionUrl }}">
                                {{ $page->slug }}
                            </a>
                        </td>
                        <td class="column default-action">
                            {{ $page->title }}
                        </td>
                        <td class="column column-center small default-action">
                            @if ($page->created_at)
                                {{ $page->created_at->format('Y-m-d H:i') }}
                            @endif
                        </td>
                        <td class="column column-center small default-action">
                            @if ($page->updated_at)
                                {{ $page->updated_at->format('Y-m-d H:i') }}
                            @endif
                        </td>
                        <td>
                            @if (cms_auth()->canAnyOf(['wiki.page.edit', 'wiki.page.delete']))
                                <div class="btn-group btn-group-xs record-actions pull-right tr-show-on-hover" role="group">

                                    @if (cms_auth()->can('wiki.page.edit'))
                                        <a class="btn btn-default edit-record-action" href="{{ cms_route('wiki.page.edit', [ $page->getKey() ]) }}" role="button"
                                           title="{{ ucfirst(cms_trans('common.action.edit')) }}"
                                        ><i class="fa fa-edit"></i></a>
                                    @endif

                                    @if (cms_auth()->can('wiki.page.delete'))
                                        <a class="btn btn-danger delete-record-action" href="#" role="button"
                                           data-toggle="modal" data-target="#delete-page-modal"
                                           title="{{ ucfirst(cms_trans('common.action.delete')) }}"
                                        ><i class="fa fa-trash-o"></i></a>
                                    @endif
                                </div>
                            @endif
                        </td>
                    </tr>

                @empty
                    <em>{{ cms_trans('wiki.list.empty-list') }}</em>
                @endforelse

                </tbody>
            </table>

        </div>
    </div>


    <div id="delete-page-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ ucfirst(cms_trans('common.action.close')) }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title delete-modal-title">
                        {{ ucfirst(cms_trans('models.button.delete-record', [ 'name' => cms_trans('common.terms.page') ])) }}
                    </h4>
                </div>
                <div class="modal-body">
                    <p class="text-danger">{{ cms_trans('common.cannot-undo') }}</p>
                </div>
                <div class="modal-footer">
                    <form class="delete-modal-form" method="post" data-url="{{ cms_route('wiki.page.delete', [ 'IDHERE' ]) }}" action="">
                        {{ method_field('delete') }}
                        {{ csrf_field() }}

                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ ucfirst(cms_trans('common.action.close')) }}
                        </button>
                        <button type="submit" class="btn btn-danger delete-modal-button">
                            {{ ucfirst(cms_trans('common.action.delete')) }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@cms_script
<script>
    $(function() {
        $('.delete-record-action').click(function () {

            var form      = $('.delete-modal-form'),
                row       = $(this).closest('tr');

            var id        = row.attr('data-id'),
                reference = row.attr('data-reference').trim();

            if ( ! reference || ! reference.length) {
                reference = '#' + id;
            }

            form.attr(
                'action',
                form.attr('data-url').replace('IDHERE', id)
            );

            $('.delete-modal-title').text(
                "{{ ucfirst(cms_trans('models.button.delete-record', [ 'name' => cms_trans('common.terms.page') ])) }}: " + reference
            );
        });

        @if (count($pages))
        $('tr.records-row td.default-action').click(function () {
            window.location.href = $(this).closest('tr').attr('data-default-action-url');
        });
        @endif
    });
</script>
@cms_endscript
