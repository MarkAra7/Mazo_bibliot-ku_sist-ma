@props(['items'])

<div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    <p class="text-sm text-slate-500">
        Rāda {{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }} no {{ $items->total() }} ierakstiem
    </p>
    {{ $items->appends(request()->query())->links() }}
</div>
