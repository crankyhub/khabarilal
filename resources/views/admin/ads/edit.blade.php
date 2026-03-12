@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0 text-gray-800">Edit Campaign: {{ $ad->title }}</h1>
        </div>
    </div>

    <form action="{{ route('admin.ads.update', $ad) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-lg-8">
                <!-- General Settings -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">General Campaign Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Campaign Title</label>
                            <input type="text" name="title" class="form-control" value="{{ $ad->title }}" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Target Category (Optional)</label>
                                    <select name="category_id" class="form-control">
                                        <option value="">All Categories (Universal)</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ $ad->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Target Specific Article (Optional)</label>
                                    <select name="article_id" class="form-control">
                                        <option value="">None (Site-wide or Category-wide)</option>
                                        @foreach($articles as $art)
                                            <option value="{{ $art->id }}" {{ $ad->article_id == $art->id ? 'selected' : '' }}>{{ $art->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="datetime-local" name="start_date" class="form-control" value="{{ $ad->start_date ? $ad->start_date->format('Y-m-d\TH:i') : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="datetime-local" name="end_date" class="form-control" value="{{ $ad->end_date ? $ad->end_date->format('Y-m-d\TH:i') : '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Placements -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Modify Placements & Creatives</h6>
                    </div>
                    <div class="card-body">
                        @php
                            $positions = [
                                'top_banner' => ['label' => 'Top Banner', 'desc' => '728x90px'],
                                'sidebar' => ['label' => 'Sidebar Widget', 'desc' => '300x250px'],
                                'in_feed' => ['label' => 'In-Feed Ad', 'desc' => '600x300px'],
                                'article_bottom' => ['label' => 'Article Bottom', 'desc' => '728x90px or 600x300px'],
                                'popup' => ['label' => 'Popup / Overlay', 'desc' => '500x500px']
                            ];
                        @endphp

                        @foreach($positions as $key => $pos)
                            @php 
                                $placement = $placements->get($key); 
                                $isActive = (bool)$placement;
                            @endphp
                            <div class="placement-group mb-4 p-3 border rounded {{ $isActive ? 'bg-light' : '' }}">
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input placement-toggle" 
                                           id="check_{{ $key }}" name="placements[{{ $key }}][active]" 
                                           onchange="togglePlacement('{{ $key }}')"
                                           {{ $isActive ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="check_{{ $key }}">
                                        {{ $pos['label'] }} <span class="small text-muted font-weight-normal">({{ $pos['desc'] }})</span>
                                    </label>
                                </div>

                                <div id="fields_{{ $key }}" class="placement-fields" style="display: {{ $isActive ? 'block' : 'none' }}; border-top: 1px solid #ddd; padding-top: 15px;">
                                    <div class="form-group">
                                        <label>Creative Type</label>
                                        <select name="placements[{{ $key }}][type]" class="form-control" onchange="toggleType('{{ $key }}', this.value)">
                                            <option value="image" {{ ($placement && $placement->type === 'image') ? 'selected' : '' }}>Image Display</option>
                                            <option value="script" {{ ($placement && $placement->type === 'script') ? 'selected' : '' }}>Custom Script / HTML</option>
                                        </select>
                                    </div>

                                    <div class="type-image-group_{{ $key }}" style="display: {{ ($placement && $placement->type === 'script') ? 'none' : 'block' }};">
                                        @if($placement && $placement->image_path)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $placement->image_path) }}" style="max-height: 100px; border: 1px solid #ddd;" class="img-thumbnail">
                                                <div class="small text-muted">Current image</div>
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <label>Change Image</label>
                                            <input type="file" name="placements[{{ $key }}][image]" class="form-control-file">
                                        </div>
                                        <div class="form-group">
                                            <label>Destination URL</label>
                                            <input type="url" name="placements[{{ $key }}][link_url]" class="form-control" value="{{ $placement->link_url ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="type-script-group_{{ $key }}" style="display: {{ ($placement && $placement->type === 'script') ? 'block' : 'none' }};">
                                        <div class="form-group">
                                            <label>Script / HTML Content</label>
                                            <textarea name="placements[{{ $key }}][content]" class="form-control" rows="4">{{ $placement->content ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Status & Stats -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Campaign Performance</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="small font-weight-bold text-uppercase text-muted d-block">Current Status</label>
                            <select name="status" class="form-control font-weight-bold text-{{ $ad->status === 'active' ? 'success' : 'danger' }}">
                                <option value="active" {{ $ad->status === 'active' ? 'selected' : '' }}>Active Rendering</option>
                                <option value="paused" {{ $ad->status === 'paused' ? 'selected' : '' }}>Paused / Stopped</option>
                                <option value="exhausted" {{ $ad->status === 'exhausted' ? 'selected' : '' }}>Exhausted</option>
                                <option value="expired" {{ $ad->status === 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                        </div>
                        <div class="row text-center mb-4">
                            <div class="col-6">
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ad->current_impressions }}</div>
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Impressions</div>
                            </div>
                            <div class="col-6">
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ad->current_clicks }}</div>
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Clicks</div>
                            </div>
                        </div>
                        <div class="form-group custom-control custom-switch">
                            <input type="checkbox" name="is_active" class="custom-control-input" id="isActive" {{ $ad->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="isActive">Campaign Enabled (Site-wide)</label>
                        </div>
                    </div>
                </div>

                <!-- Budgeting -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Financial Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Total Budget (INR)</label>
                            <input type="number" step="0.01" name="total_budget" class="form-control" value="{{ $ad->total_budget }}" required>
                        </div>
                        <div class="form-group">
                            <label>Remaining Wallet</label>
                            <div class="h4 font-weight-bold text-{{ $ad->remaining_budget > 0 ? 'success' : 'danger' }}">₹{{ number_format($ad->remaining_budget, 2) }}</div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>Cost Per Impression</label>
                            <input type="number" step="0.0001" name="cost_per_impression" class="form-control" value="{{ $ad->cost_per_impression }}" required>
                        </div>
                        <div class="form-group">
                            <label>Cost Per Click</label>
                            <input type="number" step="0.01" name="cost_per_click" class="form-control" value="{{ $ad->cost_per_click }}" required>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>Impression Limit</label>
                            <input type="number" name="limit_impressions" class="form-control" value="{{ $ad->limit_impressions }}">
                        </div>
                        <div class="form-group">
                            <label>Click Limit</label>
                            <input type="number" name="limit_clicks" class="form-control" value="{{ $ad->limit_clicks }}">
                        </div>
                        
                        <button type="submit" class="btn btn-info btn-block p-3 font-weight-bold">
                            UPDATE CAMPAIGN
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function togglePlacement(pos) {
        const fields = document.getElementById('fields_' + pos);
        fields.style.display = document.getElementById('check_' + pos).checked ? 'block' : 'none';
        
        const group = fields.closest('.placement-group');
        if (document.getElementById('check_' + pos).checked) {
            group.classList.add('bg-light');
        } else {
            group.classList.remove('bg-light');
        }
    }

    function toggleType(pos, type) {
        const imgGroup = document.querySelector('.type-image-group_' + pos);
        const scriptGroup = document.querySelector('.type-script-group_' + pos);
        
        if (type === 'image') {
            imgGroup.style.display = 'block';
            scriptGroup.style.display = 'none';
        } else {
            imgGroup.style.display = 'none';
            scriptGroup.style.display = 'block';
        }
    }
</script>
@endsection
