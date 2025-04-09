<!-- Reporte detallado de activos -->
<div class="card">
    <div class="card-header border-bottom">
        <h5 class="card-title mb-0">{{ __('Detailed Assets Report') }}</h5>
    </div>
    <div class="card-datatable table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>{{ __('Serial Number') }}</th>
                    <th>{{ __('Description') }}</th>
                    <th>{{ __('Class') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Acquisition Date') }}</th>
                    <th>{{ __('Real Price') }}</th>
                    <th>{{ __('In Service') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData['detailed']['assets'] ?? [] as $asset)
                    <tr>
                        <td>{{ $asset->serial_number ?? 'N/A' }}</td>
                        <td>{{ $asset->description }}</td>
                        <td>
                            <span class="badge rounded-pill 
                                @if($asset->class == 'Electronic') bg-label-info
                                @elseif($asset->class == 'Furniture') bg-label-warning
                                @elseif($asset->class == 'Gear') bg-label-secondary
                                @endif">
                                {{ $asset->class }}
                            </span>
                        </td>
                        <td>
                            <span class="badge rounded-pill 
                                @if($asset->status == 'Good') bg-label-success
                                @elseif($asset->status == 'Fine') bg-label-info
                                @elseif($asset->status == 'Bad') bg-label-warning
                                @elseif($asset->status == 'Damaged') bg-label-danger
                                @endif">
                                {{ $asset->status }}
                            </span>
                        </td>
                        <td>{{ $asset->acquisition_date ? date('d M Y', strtotime($asset->acquisition_date)) : 'N/A' }}</td>
                        <td>{{ $asset->real_price ? number_format($asset->real_price, 2) : 'N/A' }}</td>
                        <td>
                            <span class="badge rounded-pill {{ $asset->in_service ? 'bg-label-success' : 'bg-label-danger' }}">
                                {{ $asset->in_service ? __('Yes') : __('No') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">{{ __('No assets found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Paginación -->
    @if(isset($reportData['detailed']['assets']))
        <div class="card-footer">
            {{ $reportData['detailed']['assets']->links() }}
        </div>
    @endif
</div> 