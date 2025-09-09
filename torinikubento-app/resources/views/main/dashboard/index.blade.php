@extends('partials.layouts.main')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang di panel admin TorinikuBento')

@section('main-content')
	{{-- Stats Cards --}}
	<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
		{{-- Total Menu --}}
		<div class="bg-neutral-800 rounded-xl p-6 border border-neutral-700/50" data-aos="fade-up" data-aos-delay="100">
			<div class="flex items-center justify-between">
				<div>
					<p class="text-neutral-400 text-sm">Total Menu</p>
					<p class="text-2xl font-bold text-white mt-1">{{ $stats['total_menu'] }}</p>
				</div>
				<div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
					<i class="fas fa-utensils text-white text-xl"></i>
				</div>
			</div>
			<div class="flex items-center mt-4">
				<span class="text-green-400 text-sm font-medium">+12%</span>
				<span class="text-neutral-400 text-sm ml-2">dari bulan lalu</span>
			</div>
		</div>

		{{-- Total Pesanan --}}
		<div class="bg-neutral-800 rounded-xl p-6 border border-neutral-700/50" data-aos="fade-up" data-aos-delay="200">
			<div class="flex items-center justify-between">
				<div>
					<p class="text-neutral-400 text-sm">Total Pesanan</p>
					<p class="text-2xl font-bold text-white mt-1">{{ $stats['total_orders'] }}</p>
				</div>
				<div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
					<i class="fas fa-shopping-cart text-white text-xl"></i>
				</div>
			</div>
			<div class="flex items-center mt-4">
				<span class="text-green-400 text-sm font-medium">+8%</span>
				<span class="text-neutral-400 text-sm ml-2">dari bulan lalu</span>
			</div>
		</div>

		{{-- Total Pelanggan --}}
		<div class="bg-neutral-800 rounded-xl p-6 border border-neutral-700/50" data-aos="fade-up" data-aos-delay="300">
			<div class="flex items-center justify-between">
				<div>
					<p class="text-neutral-400 text-sm">Total Pelanggan</p>
					<p class="text-2xl font-bold text-white mt-1">{{ $stats['total_customers'] }}</p>
				</div>
				<div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
					<i class="fas fa-users text-white text-xl"></i>
				</div>
			</div>
			<div class="flex items-center mt-4">
				<span class="text-green-400 text-sm font-medium">+15%</span>
				<span class="text-neutral-400 text-sm ml-2">dari bulan lalu</span>
			</div>
		</div>

		{{-- Total Pendapatan --}}
		<div class="bg-neutral-800 rounded-xl p-6 border border-neutral-700/50" data-aos="fade-up" data-aos-delay="400">
			<div class="flex items-center justify-between">
				<div>
					<p class="text-neutral-400 text-sm">Total Pendapatan</p>
					<p class="text-2xl font-bold text-white mt-1">Rp {{ number_format($stats['total_revenue'] / 1000000, 1) }}M</p>
				</div>
				<div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
					<i class="fas fa-money-bill-wave text-white text-xl"></i>
				</div>
			</div>
			<div class="flex items-center mt-4">
				<span class="text-green-400 text-sm font-medium">+20%</span>
				<span class="text-neutral-400 text-sm ml-2">dari bulan lalu</span>
			</div>
		</div>
	</div>

	{{-- Charts Section --}}
	<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
		{{-- Sales Chart --}}
		<div class="bg-neutral-800 rounded-xl p-6 border border-neutral-700/50" data-aos="fade-up" data-aos-delay="100">
			<h3 class="text-xl font-semibold text-white mb-6">Penjualan Bulanan</h3>
			<div class="h-64 bg-neutral-700/30 rounded-lg flex items-center justify-center">
				<p class="text-neutral-400">Chart akan ditampilkan di sini</p>
			</div>
		</div>

		{{-- Popular Menu --}}
		<div class="bg-neutral-800 rounded-xl p-6 border border-neutral-700/50" data-aos="fade-up" data-aos-delay="200">
			<h3 class="text-xl font-semibold text-white mb-6">Menu Populer</h3>
			<div class="space-y-4">
				@foreach($popular_menus as $index => $menu)
				<div class="flex items-center justify-between">
					<div class="flex items-center">
						<div class="w-10 h-10 bg-gradient-to-br 
							@if($index == 0) from-orange-500 to-red-600
							@elseif($index == 1) from-blue-500 to-blue-600
							@else from-purple-500 to-purple-600
							@endif
							rounded-lg flex items-center justify-center mr-3">
							<span class="text-white text-sm font-bold">{{ $index + 1 }}</span>
						</div>
						<div>
							<p class="text-white font-medium">{{ $menu['name'] }}</p>
							<p class="text-neutral-400 text-sm">{{ $menu['orders'] }} pesanan</p>
						</div>
					</div>
					<span class="text-green-400 font-medium">{{ $menu['percentage'] }}%</span>
				</div>
				@endforeach
			</div>
		</div>
	</div>

	{{-- Recent Orders --}}
	<div class="bg-neutral-800 rounded-xl border border-neutral-700/50" data-aos="fade-up" data-aos-delay="300">
		<div class="p-6 border-b border-neutral-700/50">
			<h3 class="text-xl font-semibold text-white">Pesanan Terbaru</h3>
		</div>
		<div class="p-6">
			<div class="overflow-x-auto">
				<table class="w-full">
					<thead>
						<tr class="text-left text-neutral-400 text-sm">
							<th class="pb-3">ID Pesanan</th>
							<th class="pb-3">Pelanggan</th>
							<th class="pb-3">Menu</th>
							<th class="pb-3">Status</th>
							<th class="pb-3">Total</th>
							<th class="pb-3">Waktu</th>
						</tr>
					</thead>
					<tbody class="text-white">
						@foreach($recent_orders as $order)
						<tr class="border-t border-neutral-700/50">
							<td class="py-4">{{ $order['id'] }}</td>
							<td class="py-4">{{ $order['customer'] }}</td>
							<td class="py-4">{{ $order['menu'] }}</td>
							<td class="py-4">
								@if($order['status'] == 'Selesai')
								<span class="bg-green-500/20 text-green-400 px-2 py-1 rounded-full text-xs">Selesai</span>
								@elseif($order['status'] == 'Proses')
								<span class="bg-yellow-500/20 text-yellow-400 px-2 py-1 rounded-full text-xs">Proses</span>
								@else
								<span class="bg-blue-500/20 text-blue-400 px-2 py-1 rounded-full text-xs">Diterima</span>
								@endif
							</td>
							<td class="py-4">Rp {{ number_format($order['total']) }}</td>
							<td class="py-4">{{ $order['time'] }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection