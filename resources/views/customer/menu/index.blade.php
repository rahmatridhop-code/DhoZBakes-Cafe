@extends('customer.layouts.app')
@section('title', 'Menu - ' . $settings['store_name'])

@section('content')
<!-- HERO -->
<header class="relative h-[70vh] bg-cover bg-center flex items-center" style="background-image: url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');">
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="max-w-xl text-white">
            <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight mb-4 text-white drop-shadow-md bg-brand/80 inline-block px-6 py-3 rounded-lg">HANDCRAFTED</h1>
            <p class="text-xl md:text-2xl mb-8 font-medium drop-shadow-md">Pilihan kopi & makanan terbaik yang dibuat dengan penuh cinta.</p>
            <a href="#menu" class="inline-block bg-brand text-white font-bold px-8 py-3 rounded-full hover:bg-brand-dark transition duration-300 shadow-lg">Pesan Sekarang</a>
        </div>
    </div>
</header>

<!-- CATEGORIES -->
<section class="py-16 bg-[#2c2c2c]" style="background-image: url('https://images.unsplash.com/photo-1447933601403-0c6688de566e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); background-blend-mode: overlay; background-size: cover;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold mb-10 text-white text-center">Kategori</h2>
        <div class="grid grid-cols-3 md:grid-cols-6 gap-6 text-center">
            <button class="group cursor-pointer cat-filter active" data-cat="semua" onclick="filterCategory('semua', this)">
                <div class="w-24 h-24 md:w-28 md:h-28 mx-auto rounded-full overflow-hidden mb-3 border-4 border-transparent group-hover:border-brand transition bg-white flex items-center justify-center text-4xl" style="border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;">
                    ✨
                </div>
                <span class="font-semibold text-white text-sm">Semua</span>
            </button>
            @foreach($categories as $cat)
            <button class="group cursor-pointer cat-filter" data-cat="{{ $cat->slug }}" onclick="filterCategory('{{ $cat->slug }}', this)">
                <div class="w-24 h-24 md:w-28 md:h-28 mx-auto rounded-full overflow-hidden mb-3 border-4 border-transparent group-hover:border-brand transition bg-white flex items-center justify-center text-4xl" style="border-radius: {{ $loop->index % 2 == 0 ? '60% 40% 30% 70% / 60% 30% 70% 40%' : '30% 70% 70% 30% / 30% 30% 70% 70%' }};">
                    {{ $cat->icon }}
                </div>
                <span class="font-semibold text-white text-sm">{{ $cat->name }}</span>
            </button>
            @endforeach
        </div>
    </div>
</section>

<!-- MENU -->
<section id="menu" class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-bold mb-10 text-brand">Menu Kami</h2>
    <div id="productsGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8"></div>
</section>

<script>
const PRODUCTS = @json($products);
const CATEGORIES = @json($categories);
let activeCategory = 'semua';

const categoryColors = {
    'kopi': '#8B4513',
    'non-kopi': '#2e7d32',
    'pastry': '#e65100',
    'dessert': '#ad1457',
    'makanan': '#1565c0',
    'snack': '#6a1b9a'
};

const categoryImages = {
    'kopi': 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400&q=80',
    'non-kopi': 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400&q=80',
    'pastry': 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400&q=80',
    'dessert': 'https://images.unsplash.com/photo-1551024506-0bccd828d307?w=400&q=80',
    'makanan': 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400&q=80',
    'snack': 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=400&q=80'
};

function formatRp(n) { return 'Rp ' + n.toLocaleString('id-ID'); }

function getCatSlug(p) { return p.category_rel ? p.category_rel.slug : p.category; }
function getCatName(p) { return p.category_rel ? p.category_rel.name : p.category; }

function renderProducts(filter = 'semua', search = '') {
    const grid = document.getElementById('productsGrid');
    const filtered = PRODUCTS.filter(p => {
        const slug = getCatSlug(p);
        const matchCat = filter === 'semua' || slug === filter || p.category === filter;
        const matchSearch = p.name.toLowerCase().includes(search.toLowerCase());
        return matchCat && matchSearch && p.is_active;
    });

    grid.innerHTML = filtered.map(p => {
        const slug = getCatSlug(p);
        const color = categoryColors[slug] || '#1e3932';
        const img = categoryImages[slug] || 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400&q=80';
        const hasBadge = p.badge && p.badge !== '';

        return `
        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition duration-300 overflow-hidden border border-gray-100 p-4 flex flex-col">
            <div class="h-48 rounded-lg overflow-hidden mb-4 relative">
                <div class="w-full h-full flex items-center justify-center text-7xl" style="background: linear-gradient(135deg, ${color}22, ${color}44);">
                    ${p.emoji}
                </div>
            </div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-3 h-3 rounded-full border" style="background: ${color}; border-color: ${color};"></span>
                <h3 class="font-bold text-lg text-gray-800">${p.name}</h3>
            </div>
            <p class="text-xs text-gray-500 mb-2">${getCatName(p)}</p>
            ${hasBadge ? `
            <div class="flex items-center text-xs text-yellow-600 font-semibold mb-3">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                ${p.badge}
            </div>` : '<div class="mb-3"></div>'}
            <div class="flex justify-between items-center mt-auto pt-2 border-t border-gray-50">
                <span class="text-xl font-bold text-brand">${formatRp(p.price)}</span>
                <button onclick="addToCart(${p.id}, '${p.name.replace(/'/g, "\\'")}', ${p.price}, '${p.emoji}')" class="bg-brand text-white px-4 py-1.5 rounded-full text-sm font-semibold hover:bg-brand-dark transition">Tambah</button>
            </div>
        </div>`;
    }).join('');
}

function filterCategory(cat, btn) {
    activeCategory = cat;
    document.querySelectorAll('.cat-filter').forEach(b => {
        b.querySelector('div').classList.remove('border-brand');
        b.querySelector('div').classList.add('border-transparent');
    });
    if (btn) {
        btn.querySelector('div').classList.remove('border-transparent');
        btn.querySelector('div').classList.add('border-brand');
    }
    renderProducts(cat, document.getElementById('searchInput').value);
}

document.addEventListener('DOMContentLoaded', () => {
    renderProducts();
    document.getElementById('searchInput').addEventListener('input', e => {
        renderProducts(activeCategory, e.target.value);
    });
});
</script>
@endsection
