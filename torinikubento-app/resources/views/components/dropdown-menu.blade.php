@props([
    'id' => 'dropdown-' . uniqid(),
    'buttonClass' => 'inline-flex items-center justify-center w-8 h-8 text-neutral-400 hover:text-white hover:bg-neutral-700 rounded-lg transition-colors duration-200',
    'menuClass' => 'w-56 bg-neutral-800 rounded-lg shadow-2xl border border-neutral-700 py-1',
    'position' => 'auto' // auto, left, right, top, bottom
])

<div class="relative" x-data="dropdownMenu('{{ $id }}')" x-init="init()">
    {{-- Dropdown Button --}}
    <button type="button" 
            @click="toggle()"
            x-ref="button"
            {{ $attributes->merge(['class' => $buttonClass]) }}>
        {{ $trigger ?? $defaultTrigger ?? '' }}
    </button>

    {{-- Dropdown Menu --}}
    <div x-ref="dropdown" 
         :id="dropdownId"
         class="hidden fixed z-50 {{ $menuClass }}"
         style="top: 0; left: 0;"
         @click.outside="close()">
        {{ $slot }}
    </div>
</div>

@once
@push('script-head')
<script>
    // Global dropdown management
    window.activeDropdowns = new Set();
    
    function dropdownMenu(id) {
        return {
            dropdownId: id,
            isOpen: false,
            button: null,
            dropdown: null,

            init() {
                this.button = this.$refs.button;
                this.dropdown = this.$refs.dropdown;
            },

            toggle() {
                if (this.isOpen) {
                    this.close();
                } else {
                    this.open();
                }
            },

            open() {
                // Close all other dropdowns first
                this.closeAllOthers();
                
                // Add to active dropdowns
                window.activeDropdowns.add(this);
                
                // Position and show dropdown
                this.positionDropdown();
                
                // Reset any existing transitions and transforms
                this.dropdown.style.transition = 'none';
                this.dropdown.style.transform = '';
                this.dropdown.style.opacity = '';
                
                // Show dropdown with slide-in effect from button position
                this.dropdown.classList.remove('hidden');
                this.dropdown.style.opacity = '0';
                this.dropdown.style.transform = 'scale(0.8)';
                
                // Force reflow to ensure initial state is applied
                this.dropdown.offsetHeight;
                
                // Animate in from button position
                requestAnimationFrame(() => {
                    this.dropdown.style.transition = 'opacity 0.2s cubic-bezier(0.16, 1, 0.3, 1), transform 0.2s cubic-bezier(0.16, 1, 0.3, 1)';
                    this.dropdown.style.opacity = '1';
                    this.dropdown.style.transform = 'scale(1)';
                });
                
                this.isOpen = true;
            },

            close() {
                if (!this.isOpen) return;
                
                // Remove from active dropdowns
                window.activeDropdowns.delete(this);
                
                // Animate out towards button position
                this.dropdown.style.transition = 'opacity 0.15s cubic-bezier(0.4, 0, 0.6, 1), transform 0.15s cubic-bezier(0.4, 0, 0.6, 1)';
                this.dropdown.style.opacity = '0';
                this.dropdown.style.transform = 'scale(0.8)';
                
                // Clean up after animation
                setTimeout(() => {
                    this.dropdown.classList.add('hidden');
                    // Reset all animation styles completely
                    this.dropdown.style.transition = '';
                    this.dropdown.style.transform = '';
                    this.dropdown.style.transformOrigin = '';
                    this.dropdown.style.opacity = '';
                    this.dropdown.style.top = '';
                    this.dropdown.style.left = '';
                    this.dropdown.style.position = '';
                    this.dropdown.style.zIndex = '';
                    this.dropdown.style.visibility = '';
                }, 150);
                
                this.isOpen = false;
            },

            closeAllOthers() {
                window.activeDropdowns.forEach(dropdown => {
                    if (dropdown !== this) {
                        dropdown.close();
                    }
                });
            },

            positionDropdown() {
                // Get button position relative to viewport
                const rect = this.button.getBoundingClientRect();
                const viewportWidth = window.innerWidth;
                const viewportHeight = window.innerHeight;
                
                // Calculate initial position
                let top = rect.top;
                let left = rect.right + 8; // 8px offset to the right of button
                let transformOrigin = 'top left'; // Default origin for right positioning
                
                // Get dropdown dimensions (temporarily show to measure)
                const wasHidden = this.dropdown.classList.contains('hidden');
                if (wasHidden) {
                    this.dropdown.style.visibility = 'hidden';
                    this.dropdown.classList.remove('hidden');
                }
                
                const dropdownRect = this.dropdown.getBoundingClientRect();
                const dropdownWidth = dropdownRect.width;
                const dropdownHeight = dropdownRect.height;
                
                // Hide again if it was hidden
                if (wasHidden) {
                    this.dropdown.classList.add('hidden');
                    this.dropdown.style.visibility = 'visible';
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
                this.dropdown.style.position = 'fixed';
                this.dropdown.style.top = top + 'px';
                this.dropdown.style.left = left + 'px';
                this.dropdown.style.zIndex = '9999';
                this.dropdown.style.transformOrigin = transformOrigin;
            },

            reposition() {
                if (this.isOpen) {
                    requestAnimationFrame(() => {
                        this.positionDropdown();
                    });
                }
            }
        }
    }

    // Global event listeners for scroll and resize
    let repositionTimeout = null;
    
    function throttledReposition() {
        if (repositionTimeout) return;
        repositionTimeout = requestAnimationFrame(() => {
            window.activeDropdowns.forEach(dropdown => {
                dropdown.reposition();
            });
            repositionTimeout = null;
        });
    }

    // Add event listeners
    window.addEventListener('scroll', throttledReposition, true);
    window.addEventListener('resize', throttledReposition);
    document.addEventListener('scroll', throttledReposition, true);
</script>
@endpush
@endonce

{{-- Default trigger if none provided --}}
@php
    $defaultTrigger = '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
    </svg>';
@endphp
