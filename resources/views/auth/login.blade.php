<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - DhoZ-Bakes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { brand: { DEFAULT: '#1e3932', light: '#2d5a4e', dark: '#15302a' } } } }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        .login-bg { background-image: url('https://images.unsplash.com/photo-1445116572660-236099ec97a0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <div class="flex min-h-screen">
        <!-- Left: Image -->
        <div class="hidden lg:flex lg:w-1/2 login-bg bg-cover bg-center relative">
            <div class="absolute inset-0 bg-brand bg-opacity-60"></div>
            <div class="relative z-10 flex flex-col justify-end p-12 text-white">
                <div class="w-16 h-16 border-2 border-white rounded-full flex items-center justify-center font-bold text-3xl mb-6">☕</div>
                <h1 class="font-display text-5xl font-bold mb-4 leading-tight">Welcome To<br><span class="text-green-300">DhoZ-Bakes</span></h1>
                <p class="text-lg text-green-100 max-w-md">Nikmati kopi & kue terbaik yang dibuat dengan penuh cinta oleh barista kami.</p>
            </div>
        </div>

        <!-- Right: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex items-center gap-3 mb-8">
                    <div class="w-12 h-12 bg-brand rounded-full flex items-center justify-center text-white font-bold text-xl">☕</div>
                    <span class="font-display font-bold text-2xl text-brand">DhoZ-Bakes</span>
                </div>

                <h2 class="text-3xl font-bold text-gray-800 mb-2">Masuk</h2>
                <p class="text-gray-500 mb-8">Pilih akun Anda untuk melanjutkan</p>

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                    @foreach($errors->all() as $e)
                    <div class="flex items-center gap-2 text-sm text-red-600">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        {{ $e }}
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Role Tabs -->
                <div class="grid grid-cols-3 bg-gray-100 rounded-xl p-1 mb-8">
                    <a href="{{ route('login') }}" class="py-2.5 rounded-lg text-sm font-semibold text-center bg-brand text-white shadow-sm">Karyawan</a>
                    <a href="{{ route('customer.login') }}" class="py-2.5 rounded-lg text-sm font-semibold text-center text-gray-500 hover:text-gray-700 transition">Pelanggan</a>
                    <a href="{{ route('register') }}" class="py-2.5 rounded-lg text-sm font-semibold text-center text-gray-500 hover:text-gray-700 transition">Daftar</a>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </span>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition"
                                placeholder="nama@email.com">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </span>
                            <input type="password" name="password" required
                                class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition"
                                placeholder="Masukkan password">
                        </div>
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand">
                            <span class="text-sm text-gray-600">Ingat saya</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-brand text-white py-3 rounded-xl font-bold text-sm hover:bg-brand-dark transition shadow-lg shadow-brand/20">
                        Masuk
                    </button>
                </form>

                <div class="text-center mt-8 text-sm text-gray-500">
                    Belum punya akun pelanggan? <a href="{{ route('customer.register') }}" class="text-brand font-semibold hover:underline">Daftar di sini</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
