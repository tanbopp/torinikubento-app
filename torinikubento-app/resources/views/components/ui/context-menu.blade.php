{{-- 
    Context Menu Component
    
    Usage:
    <x-ui.context-menu id="menu-1">
        <x-ui.context-menu.item icon="eye" href="/view">View</x-ui.context-menu.item>
        <x-ui.context-menu.item icon="edit" href="/edit">Edit</x-ui.context-menu.item>
        <x-ui.context-menu.divider />
        <x-ui.context-menu.item icon="trash" color="red">Delete</x-ui.context-menu.item>
    </x-ui.context-menu>
--}}

@props([
    'id' => 'context-menu-' . uniqid(),
    'width' => 'w-56'
])

<div class="relative">
    {{-- Three Dots Menu Button --}}
    <button type="button" 
            onclick="toggleContextMenu('{{ $id }}')"
            class="inline-flex items-center justify-center w-8 h-8 text-neutral-400 hover:text-white hover:bg-neutral-700 rounded-lg transition-colors duration-200">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
        </svg>
    </button>

    {{-- Context Menu Dropdown --}}
    <div id="context-menu-{{ $id }}" 
         class="hidden fixed z-50 {{ $width }} bg-neutral-800 rounded-xl shadow-2xl border border-neutral-700/30 px-1 py-1"
         style="top: 0; left: 0;">
        {{ $slot }}
    </div>
</div>

@once
    @push('script-body')
    <script>
        // Context Menu functionality
        let activeContextMenuId = null;
        let activeContextMenuButton = null;

        function toggleContextMenu(menuId) {
            const button = event.target.closest('button');
            const dropdown = document.getElementById('context-menu-' + menuId);
            const isHidden = dropdown.classList.contains('hidden');
            
            // Close all other context menus first
            document.querySelectorAll('[id^="context-menu-"]').forEach(menu => {
                if (!menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                    // Clean up styles immediately
                    menu.style.transition = '';
                    menu.style.transform = '';
                    menu.style.transformOrigin = '';
                    menu.style.opacity = '';
                }
            });
            
            // Reset active tracking
            activeContextMenuId = null;
            activeContextMenuButton = null;
            
            // Toggle current dropdown
            if (isHidden) {
                // Set active tracking
                activeContextMenuId = menuId;
                activeContextMenuButton = button;
                
                // Position and show dropdown
                positionContextMenu(button, dropdown);
                
                // Reset any existing transitions and transforms
                dropdown.style.transition = 'none';
                dropdown.style.transform = '';
                dropdown.style.opacity = '';
                
                // Show dropdown with slide-in effect from button position
                dropdown.classList.remove('hidden');
                dropdown.style.opacity = '0';
                dropdown.style.transform = 'scale(0.8)';
                
                // Force reflow to ensure initial state is applied
                dropdown.offsetHeight;
                
                // Animate in from button position
                requestAnimationFrame(() => {
                    dropdown.style.transition = 'opacity 0.2s cubic-bezier(0.16, 1, 0.3, 1), transform 0.2s cubic-bezier(0.16, 1, 0.3, 1)';
                    dropdown.style.opacity = '1';
                    dropdown.style.transform = 'scale(1)';
                });
            }
        }

        function positionContextMenu(button, dropdown) {
            // Get button position relative to viewport
            const rect = button.getBoundingClientRect();
            const viewportWidth = window.innerWidth;
            const viewportHeight = window.innerHeight;
            
            // Calculate initial position
            let top = rect.top;
            let left = rect.right + 8; // 8px offset to the right of button
            let transformOrigin = 'top left'; // Default origin for right positioning
            
            // Get dropdown dimensions (temporarily show to measure)
            const wasHidden = dropdown.classList.contains('hidden');
            if (wasHidden) {
                dropdown.style.visibility = 'hidden';
                dropdown.classList.remove('hidden');
            }
            
            const dropdownRect = dropdown.getBoundingClientRect();
            const dropdownWidth = dropdownRect.width;
            const dropdownHeight = dropdownRect.height;
            
            // Hide again if it was hidden
            if (wasHidden) {
                dropdown.classList.add('hidden');
                dropdown.style.visibility = 'visible';
            }
            
            // Smart horizontal positioning
            if (left + dropdownWidth > viewportWidth - 8) {
                // Position to the left of button with offset
                left = rect.left - dropdownWidth - 8;
                transformOrigin = 'top right'; // Change origin for left positioning
            }
            
            // If still off screen on the left, position with safe margin
            if (left < 8) {
                // Position below button if no horizontal space
                left = Math.max(8, rect.left);
                top = rect.bottom + 4; // 4px offset below button
                transformOrigin = 'top left'; // Origin for below positioning
            }
            
            // Smart vertical positioning
            if (top + dropdownHeight > viewportHeight - 8) {
                // Position above the button with offset
                top = rect.top - dropdownHeight - 4;
                // Adjust transform origin for above positioning
                if (transformOrigin === 'top right') {
                    transformOrigin = 'bottom right';
                } else {
                    transformOrigin = 'bottom left';
                }
            }
            
            // Final check - if still off screen at top, position safely
            if (top < 8) {
                top = 8;
                transformOrigin = 'top left';
            }
            
            // Apply position and transform origin immediately
            dropdown.style.position = 'fixed';
            dropdown.style.top = top + 'px';
            dropdown.style.left = left + 'px';
            dropdown.style.zIndex = '9999';
            dropdown.style.transformOrigin = transformOrigin;
        }

        // Reposition active context menu on scroll or resize
        function repositionActiveContextMenu() {
            if (activeContextMenuId && activeContextMenuButton) {
                const dropdown = document.getElementById('context-menu-' + activeContextMenuId);
                if (dropdown && !dropdown.classList.contains('hidden')) {
                    // Use requestAnimationFrame for smooth repositioning
                    requestAnimationFrame(() => {
                        positionContextMenu(activeContextMenuButton, dropdown);
                    });
                }
            }
        }

        // Add event listeners for scroll and resize with throttling
        let repositionTimeout = null;
        
        function throttledReposition() {
            if (repositionTimeout) return;
            repositionTimeout = requestAnimationFrame(() => {
                repositionActiveContextMenu();
                repositionTimeout = null;
            });
        }

        // Add event listeners
        window.addEventListener('scroll', throttledReposition, true);
        window.addEventListener('resize', throttledReposition);
        document.addEventListener('scroll', throttledReposition, true);

        // Close context menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.relative')) {
                // Reset active tracking
                activeContextMenuId = null;
                activeContextMenuButton = null;
                
                // Animate out towards button position and then hide
                document.querySelectorAll('[id^="context-menu-"]').forEach(menu => {
                    if (!menu.classList.contains('hidden')) {
                        // Set exit animation
                        menu.style.transition = 'opacity 0.15s cubic-bezier(0.4, 0, 0.6, 1), transform 0.15s cubic-bezier(0.4, 0, 0.6, 1)';
                        menu.style.opacity = '0';
                        menu.style.transform = 'scale(0.8)';
                        
                        // Clean up after animation
                        setTimeout(() => {
                            menu.classList.add('hidden');
                            // Reset all animation styles completely
                            menu.style.transition = '';
                            menu.style.transform = '';
                            menu.style.transformOrigin = '';
                            menu.style.opacity = '';
                            menu.style.top = '';
                            menu.style.left = '';
                            menu.style.position = '';
                            menu.style.zIndex = '';
                            menu.style.visibility = '';
                        }, 150);
                    }
                });
            }
        });

        // Prevent context menu from closing when clicking inside
        document.addEventListener('click', function(event) {
            if (event.target.closest('[id^="context-menu-"]')) {
                event.stopPropagation();
            }
        });
    </script>
    @endpush
@endonce
