@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0 text-gray-800">Create Multi-Position Campaign</h1>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm mb-4">
            <h5 class="font-weight-bold"><i class="fas fa-exclamation-triangle"></i> Oops! Something went wrong:</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
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
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Summer Festival 2026" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Target Category (Optional)</label>
                                    <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                        <option value="">All Categories (Universal)</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Target Specific Article (Optional)</label>
                                    <select name="article_id" class="form-control @error('article_id') is-invalid @enderror">
                                        <option value="">None (Site-wide or Category-wide)</option>
                                        @foreach($articles as $art)
                                            <option value="{{ $art->id }}" {{ old('article_id') == $art->id ? 'selected' : '' }}>{{ $art->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('article_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="datetime-local" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="datetime-local" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Placements -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Ad Placements & Creatives</h6>
                        <small class="text-muted">Select all positions where this ad should appear.</small>
                    </div>
                    <div class="card-body">
                        @php
                            $positions = [
                                'top_banner' => ['label' => 'Top Banner', 'desc' => 'Recommended: 728x90px'],
                                'sidebar' => ['label' => 'Sidebar Widget', 'desc' => 'Recommended: 300x250px'],
                                'in_feed' => ['label' => 'In-Feed Ad', 'desc' => 'Recommended: 600x300px'],
                                'article_bottom' => ['label' => 'Article Bottom', 'desc' => 'Recommended: 728x90px or 600x300px'],
                                'popup' => ['label' => 'Popup / Overlay', 'desc' => 'Recommended: 500x500px or Large Responsive']
                            ];
                        @endphp

                        @foreach($positions as $key => $pos)
                            <div class="placement-group mb-4 p-3 border rounded">
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input placement-toggle" 
                                           id="check_{{ $key }}" name="placements[{{ $key }}][active]" 
                                           {{ old("placements.$key.active") ? 'checked' : '' }}
                                           onchange="togglePlacement('{{ $key }}')">
                                    <label class="custom-control-label font-weight-bold" for="check_{{ $key }}">
                                        {{ $pos['label'] }} <span class="small text-muted font-weight-normal">({{ $pos['desc'] }})</span>
                                    </label>
                                </div>

                                <div id="fields_{{ $key }}" class="placement-fields" style="display: none; border-top: 1px solid #eee; padding-top: 15px;">
                                    <div class="form-group">
                                        <label>Creative Type</label>
                                        <select name="placements[{{ $key }}][type]" class="form-control" onchange="toggleType('{{ $key }}', this.value)">
                                            <option value="image">Image Display</option>
                                            <option value="script">Custom Script / HTML</option>
                                        </select>
                                    </div>

                                    <div class="type-image-group_{{ $key }}">
                                        <div class="form-group">
                                            <label>Upload Image</label>
                                            <input type="file" name="placements[{{ $key }}][image]" class="form-control-file">
                                        </div>
                                        <div class="form-group">
                                            <label>Destination URL</label>
                                            <input type="url" name="placements[{{ $key }}][link_url]" class="form-control" placeholder="https://example.com/promo">
                                        </div>
                                    </div>

                                    <div class="type-script-group_{{ $key }}" style="display: none;">
                                        <div class="form-group">
                                            <label>Script / HTML Content</label>
                                            <textarea name="placements[{{ $key }}][content]" class="form-control" rows="4" placeholder='<a href="#"><img src="..."></a> or <script>...</script>'></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Budgeting -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Budget & Limits</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Total Budget (INR)</label>
                            <input type="number" step="0.01" name="total_budget" class="form-control @error('total_budget') is-invalid @enderror" value="{{ old('total_budget', '100.00') }}" required>
                            @error('total_budget')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Campaign stops when this amount is exhausted.</small>
                        </div>
                        <div class="form-group">
                            <label>Cost Per Impression (INR)</label>
                            <input type="number" step="0.0001" name="cost_per_impression" class="form-control" value="0.50" required>
                        </div>
                        <div class="form-group">
                            <label>Cost Per Click (INR)</label>
                            <input type="number" step="0.01" name="cost_per_click" class="form-control" value="5.00" required>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>Impression Limit</label>
                            <input type="number" name="limit_impressions" class="form-control" value="0">
                            <small class="text-muted">0 for unlimited (limited only by budget).</small>
                        </div>
                        <div class="form-group">
                            <label>Click Limit</label>
                            <input type="number" name="limit_clicks" class="form-control" value="0">
                        </div>
                        <div class="form-group custom-control custom-switch">
                            <input type="checkbox" name="is_active" class="custom-control-input" id="isActive" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="isActive">Campaign Enabled</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block p-3 font-weight-bold">
                            LAUNCH AD CAMPAIGN
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
