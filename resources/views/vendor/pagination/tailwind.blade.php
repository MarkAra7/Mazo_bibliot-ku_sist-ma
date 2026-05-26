@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-slate-500">
                    {!! __('Rāda no') !!}
                    @if ($paginator->firstItem())
                        <span class="font-medium text-slate-700">{{ $paginator->firstItem() }}</span>
                        {!! __('līdz') !!}
                        <span class="font-medium text-slate-700">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('no') !!}
                    <span class="font-medium text-slate-700">{{ $paginator->total() }}</span>
                    {!! __('ierakstiem') !!}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-1.5">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-sm text-slate-300 bg-slate-50 border border-slate-200 rounded-xl cursor-default">&laquo;</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-sm text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-indigo-600 transition-colors">&laquo;</a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-3 py-2 text-sm text-slate-400">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3.5 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-xl shadow-sm">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3.5 py-2 text-sm text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-indigo-600 transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-sm text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-indigo-600 transition-colors">&raquo;</a>
            @else
                <span class="px-3 py-2 text-sm text-slate-300 bg-slate-50 border border-slate-200 rounded-xl cursor-default">&raquo;</span>
            @endif
        </div>
    </nav>
@endif
