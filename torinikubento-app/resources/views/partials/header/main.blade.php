{{-- Top Header --}}
<header x-data class="bg-neutral-900 border-b border-neutral-700/50 px-6 py-3">
	<div class="flex items-center justify-between">
		<div class="flex items-center">
			{{-- Sidebar Toggle Button - Show when sidebar is hidden --}}
			<button x-show="!$store.sidebar.open" @click="$store.sidebar.toggle()" x-transition class="relative mr-3 px-2 py-2 rounded-xl transition-colors hover:bg-neutral-700 text-white">
				<svg class="h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M17 2H7C5.67392 2 4.40215 2.52678 3.46447 3.46447C2.52678 4.40215 2 5.67392 2 7V17C2 18.3261 2.52678 19.5979 3.46447 20.5355C4.40215 21.4732 5.67392 22 7 22H17C18.3261 22 19.5979 21.4732 20.5355 20.5355C21.4732 19.5979 22 18.3261 22 17V7C22 5.67392 21.4732 4.40215 20.5355 3.46447C19.5979 2.52678 18.3261 2 17 2ZM20 17C20 17.7956 19.6839 18.5587 19.1213 19.1213C18.5587 19.6839 17.7956 20 17 20H7C6.20435 20 5.44129 19.6839 4.87868 19.1213C4.31607 18.5587 4 17.7956 4 17V7C4 6.20435 4.31607 5.44129 4.87868 4.87868C5.44129 4.31607 6.20435 4 7 4H17C17.7956 4 18.5587 4.31607 19.1213 4.87868C19.6839 5.44129 20 6.20435 20 7V17Z" fill="currentColor"/>
				<path d="M11 20V4H9V20H11Z" fill="currentColor"/>
				</svg>
			</button>
			
			<div>
				<h1 class="text-lg text-white">@yield('page-title', 'Dashboard')</h1>
			</div>
		</div>
		
		{{-- Notification Menu --}}
		<div class="flex items-center space-x-4">
			<div class="relative" x-data="{ open: false }">
				<button @click="open = !open" 
					class="relative px-2 py-2 rounded-xl transition-colors"
					:class="open ? 'bg-neutral-700' : 'hover:bg-neutral-700 text-white'">
					<svg class="h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M19.0122 12.2002C19.0122 12.9342 19.017 13.668 19.0091 14.402C19.0069 14.5935 19.0414 14.7555 19.1526 14.9149C19.4577 15.3519 19.7507 15.797 20.0469 16.2407C20.3137 16.6414 20.5814 17.0417 20.8402 17.4474C21.2785 18.1347 20.7618 19.015 19.9383 19.0143C18.5229 19.0131 17.1076 19.0131 15.6923 19.0143C15.6523 19.0143 15.6124 19.0157 15.5726 19.0143C15.5009 19.0126 15.4645 19.0415 15.453 19.116C15.353 19.7382 15.0867 20.2863 14.6786 20.7587C14.1642 21.3541 13.5236 21.7469 12.7472 21.9175C12.1968 22.0402 11.6247 22.0259 11.0812 21.8758C10.4352 21.7007 9.88664 21.3603 9.42753 20.8733C9.13512 20.5619 8.90289 20.1991 8.74259 19.8031C8.65838 19.5977 8.57967 19.3857 8.55838 19.1619C8.54689 19.0423 8.49402 19.0116 8.38134 19.0121C7.52008 19.0157 6.65882 19.014 5.79755 19.014C5.22737 19.014 4.65726 19.014 4.08723 19.014C3.54081 19.014 3.13027 18.69 3.01831 18.1546C2.95347 17.8435 3.06855 17.5678 3.24223 17.3119C3.46999 16.9769 3.69344 16.6375 3.91904 16.3001C4.13372 15.9794 4.34848 15.6589 4.56332 15.3385C4.69378 15.1435 4.82417 14.9484 4.95447 14.7531C4.99921 14.6856 4.99084 14.6078 4.99084 14.5322C4.99084 13.0048 4.98844 11.4772 4.99203 9.94963C4.99697 9.30764 5.09085 8.66943 5.27099 8.05323C5.4539 7.4126 5.73041 6.80252 6.09158 6.24275C6.47748 5.63731 6.95466 5.09522 7.5062 4.63569C7.94617 4.26705 8.42883 3.95264 8.94379 3.69922C9.26222 3.54461 9.58974 3.40413 9.93353 3.3163C10.0637 3.28303 10.1531 3.22583 10.2113 3.10234C10.3718 2.76083 10.6249 2.49781 10.9443 2.30396C11.3245 2.07301 11.7367 1.96316 12.1867 2.01103C12.825 2.07875 13.3185 2.38533 13.6841 2.90538C13.7432 2.98938 13.7865 3.08487 13.8358 3.17581C13.8483 3.20288 13.8666 3.22683 13.8895 3.24595C13.9124 3.26507 13.9392 3.27888 13.9681 3.28638C14.4255 3.40413 14.8592 3.58697 15.2736 3.80835C15.78 4.07842 16.2519 4.40868 16.6791 4.79197C16.9939 5.07355 17.2822 5.38341 17.5404 5.71767C17.7391 5.97137 17.9182 6.23978 18.0763 6.5206C18.2772 6.87813 18.4491 7.25124 18.5902 7.63633C18.7428 8.06362 18.8542 8.50455 18.9227 8.95309C19.0789 9.9604 18.9852 10.9744 19.0115 11.9851C19.0139 12.0566 19.0122 12.1284 19.0122 12.2002ZM12.0027 16.9525C13.968 16.9525 15.9333 16.9525 17.8985 16.9525C17.9399 16.9525 17.9969 16.975 18.0193 16.9285C18.0418 16.8821 17.9942 16.8493 17.9715 16.8139C17.7062 16.4166 17.4418 16.0186 17.1736 15.6233C17.026 15.4088 16.9485 15.1537 16.9521 14.8933C16.9547 13.2021 16.9547 11.5113 16.9521 9.82088C16.9521 9.20151 16.7987 8.61229 16.5588 8.04797C16.2404 7.2984 15.7566 6.66994 15.1234 6.15372C14.5239 5.66081 13.8144 5.31986 13.0551 5.15981C12.5321 5.04804 12.0039 5.00592 11.4651 5.07557C10.7713 5.16531 10.1213 5.36443 9.51629 5.71528C8.73696 6.1609 8.09614 6.81362 7.66482 7.60115C7.25451 8.33521 7.04221 9.16344 7.04878 10.0044C7.05691 11.6433 7.04997 13.2832 7.05213 14.9213C7.05213 15.1391 7.00787 15.3459 6.89207 15.5254C6.61432 15.9562 6.32316 16.3798 6.03799 16.8063C6.01239 16.8448 5.96143 16.8831 5.98583 16.9305C6.00784 16.9733 6.06574 16.9479 6.10761 16.952C6.13536 16.9546 6.16335 16.952 6.19134 16.952L12.0027 16.9525ZM12.0068 19.0131C11.5924 19.0131 11.1778 19.0131 10.7627 19.0131C10.6742 19.0131 10.6278 19.0324 10.679 19.1291C10.8943 19.536 11.2182 19.809 11.6661 19.9136C12.3269 20.0678 12.9872 19.7578 13.3121 19.1576C13.3762 19.0391 13.3623 19.014 13.2262 19.014C12.8198 19.0132 12.4133 19.0132 12.0068 19.014V19.0131Z" fill="currentColor"/>
					</svg>
					{{-- Notification Badge --}}
					<span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
				</button>
				
				{{-- Notification Dropdown --}}
				<div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-neutral-800 border border-neutral-700 rounded-lg shadow-lg z-50">
					<div class="px-4 py-3 border-b border-neutral-700">
						<h3 class="text-base font-semibold text-white">Notifikasi</h3>
					</div>
					
					{{-- Notification List --}}
					<div class="max-h-72 overflow-y-auto">
						{{-- Sample notifications --}}
						<div class="px-3 py-2.5 border-b border-neutral-700 hover:bg-neutral-700/30 transition-colors">
							<div class="flex items-center space-x-2.5">
								<div class="w-1.5 h-1.5 bg-orange-500 rounded-full flex-shrink-0"></div>
								<div class="flex-1 min-w-0">
									<div class="flex items-center justify-between">
										<p class="text-xs font-medium text-white truncate">Pesanan Baru #001</p>
										<span class="text-xs text-neutral-500 ml-2 flex-shrink-0">2m</span>
									</div>
									<p class="text-xs text-neutral-400 truncate mt-0.5">Meja 5 - Ayam Teriyaki Bento</p>
								</div>
							</div>
						</div>
						
						<div class="px-3 py-2.5 border-b border-neutral-700 hover:bg-neutral-700/30 transition-colors">
							<div class="flex items-center space-x-2.5">
								<div class="w-1.5 h-1.5 bg-green-500 rounded-full flex-shrink-0"></div>
								<div class="flex-1 min-w-0">
									<div class="flex items-center justify-between">
										<p class="text-xs font-medium text-white truncate">Pembayaran Berhasil</p>
										<span class="text-xs text-neutral-500 ml-2 flex-shrink-0">5m</span>
									</div>
									<p class="text-xs text-neutral-400 truncate mt-0.5">Pesanan #045 telah berhasil</p>
								</div>
							</div>
						</div>
						
						<div class="px-3 py-2.5 border-b border-neutral-700 hover:bg-neutral-700/30 transition-colors">
							<div class="flex items-center space-x-2.5">
								<div class="w-1.5 h-1.5 bg-blue-500 rounded-full flex-shrink-0"></div>
								<div class="flex-1 min-w-0">
									<div class="flex items-center justify-between">
										<p class="text-xs font-medium text-white truncate">Stok Rendah</p>
										<span class="text-xs text-neutral-500 ml-2 flex-shrink-0">10m</span>
									</div>
									<p class="text-xs text-neutral-400 truncate mt-0.5">Ayam Teriyaki tersisa 5 porsi</p>
								</div>
							</div>
						</div>
					</div>
					
					{{-- View All Button --}}
					<div class="p-3 border-t border-neutral-700">
						<a href="{{ route('notifications.index') }}" class="w-full bg-orange-600 hover:bg-orange-700 text-white text-xs font-medium py-1.5 px-3 rounded-md transition-colors text-center block">
							Lihat Semua Notifikasi
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
