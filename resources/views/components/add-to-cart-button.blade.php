<x-button-with-icon>
    <form action="{{ route('cart.add-item') }}" method="POST">
        @csrf
        <input type="hidden" name="item_id" value="{{ $item->id }}">
        <input type="hidden" name="quantity" value="1">
        <button type="submit"
            class="flex items-center space-x-2 text-white bg-blue-600 hover:bg-blue-700 rounded-md px-4 py-2 transition duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                </path>
            </svg>
            <span>Add to Cart</span>
        </button>
    </form>
</x-button-with-icon>