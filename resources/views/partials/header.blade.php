<header class="bg-white border-b">
    <div class="container mx-auto p-4 flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-xl font-semibold">Story Blog</a>
        <nav class="space-x-4">
            <a href="{{ route('home') }}" class="text-gray-700">Home</a>
            <a href="{{ route('categories.index.public') }}" class="text-gray-700">Categories</a>
            <a href="{{ route('stories.index.public') }}" class="text-gray-700">Stories</a>
        </nav>
    </div>
</header>


