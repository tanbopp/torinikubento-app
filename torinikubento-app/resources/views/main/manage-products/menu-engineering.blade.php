@extends('layouts.app')

@section('title', 'Menu Engineering Analysis')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">📊 Menu Engineering Analysis</h1>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Products
                </a>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="card-title">⭐ Stars</h6>
                                    <h3 class="mb-0">{{ $products->where('classification', 'Star')->count() }}</h3>
                                    <small>High Profit & Popular</small>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-star fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="card-title">🧩 Puzzles</h6>
                                    <h3 class="mb-0">{{ $products->where('classification', 'Puzzle')->count() }}</h3>
                                    <small>High Profit & Low Popularity</small>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-puzzle-piece fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="card-title">🐎 Plowhorses</h6>
                                    <h3 class="mb-0">{{ $products->where('classification', 'Plowhorse')->count() }}</h3>
                                    <small>Low Profit & Popular</small>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-horse fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="card-title">🐕 Dogs</h6>
                                    <h3 class="mb-0">{{ $products->where('classification', 'Dog')->count() }}</h3>
                                    <small>Low Profit & Low Popularity</small>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-dog fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Strategy Guide -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">📋 Menu Engineering Strategy Guide</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h6 class="text-success">⭐ Stars</h6>
                            <ul class="small text-muted">
                                <li>Promote heavily</li>
                                <li>Keep visible on menu</li>
                                <li>Train staff to recommend</li>
                                <li>Maintain quality consistently</li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-warning">🧩 Puzzles</h6>
                            <ul class="small text-muted">
                                <li>Increase marketing</li>
                                <li>Reposition on menu</li>
                                <li>Add appealing descriptions</li>
                                <li>Consider price reduction</li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-info">🐎 Plowhorses</h6>
                            <ul class="small text-muted">
                                <li>Reduce portion costs</li>
                                <li>Find cheaper ingredients</li>
                                <li>Increase prices carefully</li>
                                <li>Reposition to lower-cost location</li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-danger">🐕 Dogs</h6>
                            <ul class="small text-muted">
                                <li>Consider removal from menu</li>
                                <li>Or complete recipe revision</li>
                                <li>Hide from prominent menu spots</li>
                                <li>Don't promote</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Analysis -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Product Analysis Details</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Base Price</th>
                                    <th>Cost Price</th>
                                    <th>Margin %</th>
                                    <th>Popularity Score</th>
                                    <th>Classification</th>
                                    <th>Recommendation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products->sortByDesc('margin_percentage')->sortByDesc('popularity_score') as $product)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $product->name }}</strong>
                                            @if($product->name_japanese)
                                            <br><small class="text-primary">{{ $product->name_japanese }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $product->category->name }}</span>
                                    </td>
                                    <td>Rp {{ number_format($product->base_price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($product->cost_price, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $product->margin_percentage > 30 ? 'success' : ($product->margin_percentage > 15 ? 'warning' : 'danger') }}">
                                            {{ number_format($product->margin_percentage, 1) }}%
                                        </span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-{{ $product->popularity_score > 50 ? 'success' : 'secondary' }}" 
                                                 style="width: {{ min($product->popularity_score, 100) }}%">
                                                {{ $product->popularity_score }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $product->color }}">
                                            {{ $product->classification }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            @switch($product->classification)
                                                @case('Star')
                                                    Promote & maintain quality
                                                    @break
                                                @case('Puzzle')
                                                    Increase marketing efforts
                                                    @break
                                                @case('Plowhorse')
                                                    Reduce costs or increase price
                                                    @break
                                                @case('Dog')
                                                    Consider removing from menu
                                                    @break
                                            @endswitch
                                        </small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Menu Classification Distribution</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="classificationChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Profit vs Popularity Matrix</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="matrixChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Classification Distribution Chart
    const classificationData = {
        labels: ['Stars', 'Puzzles', 'Plowhorses', 'Dogs'],
        datasets: [{
            data: [
                {{ $products->where('classification', 'Star')->count() }},
                {{ $products->where('classification', 'Puzzle')->count() }},
                {{ $products->where('classification', 'Plowhorse')->count() }},
                {{ $products->where('classification', 'Dog')->count() }}
            ],
            backgroundColor: [
                '#198754',
                '#ffc107', 
                '#0dcaf0',
                '#dc3545'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    };

    new Chart(document.getElementById('classificationChart'), {
        type: 'doughnut',
        data: classificationData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Matrix Scatter Plot
    const matrixData = {
        datasets: [{
            label: 'Products',
            data: [
                @foreach($products as $product)
                {
                    x: {{ $product->popularity_score }},
                    y: {{ $product->margin_percentage }},
                    label: '{{ $product->name }}',
                    backgroundColor: 
                        @switch($product->classification)
                            @case('Star') '#198754' @break
                            @case('Puzzle') '#ffc107' @break
                            @case('Plowhorse') '#0dcaf0' @break
                            @case('Dog') '#dc3545' @break
                        @endswitch
                },
                @endforeach
            ],
            backgroundColor: function(context) {
                return context.parsed ? context.raw.backgroundColor : '#ccc';
            }
        }]
    };

    new Chart(document.getElementById('matrixChart'), {
        type: 'scatter',
        data: matrixData,
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Popularity Score'
                    },
                    min: 0,
                    max: 100
                },
                y: {
                    title: {
                        display: true,
                        text: 'Profit Margin (%)'
                    },
                    min: 0
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw.label + 
                                   ': Popularity ' + context.parsed.x + 
                                   ', Margin ' + context.parsed.y.toFixed(1) + '%';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
