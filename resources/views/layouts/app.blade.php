<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Boutique Virtuelle')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        accent: {
                            50: '#fdf4ff',
                            100: '#fae8ff',
                            200: '#f5d0fe',
                            300: '#f0abfc',
                            400: '#e879f9',
                            500: '#d946ef',
                            600: '#c026d3',
                            700: '#a21caf',
                            800: '#86198f',
                            900: '#701a75',
                        }
                    },
                    boxShadow: {
                        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                        'soft-lg': '0 10px 40px -10px rgba(0, 0, 0, 0.08)',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
        }
        .gradient-accent {
            background: linear-gradient(135deg, #d946ef 0%, #8b5cf6 100%);
        }
        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(14, 165, 233, 0.4);
        }
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.1);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="glass-effect shadow-sm sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-2 group">
                    <div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold bg-gradient-to-r from-primary-600 to-indigo-600 bg-clip-text text-transparent">
                        Boutique
                    </span>
                </a>

                <!-- Navigation Links -->
                <div class="flex items-center space-x-8">
                    <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-primary-600 font-medium transition relative group">
                        Produits
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-primary-500 to-indigo-500 group-hover:w-full transition-all duration-300"></span>
                    </a>

                    @auth
                        @if(auth()->user()->isClient())
                            <a href="{{ route('seller.products.index') }}" class="text-gray-600 hover:text-primary-600 font-medium transition relative group">
                                Vendre
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-primary-500 to-indigo-500 group-hover:w-full transition-all duration-300"></span>
                            </a>
                        @endif

                        @if(!auth()->user()->isAdmin())
                            <!-- Cart -->
                            <a href="{{ route('client.cart.index') }}" class="relative p-2 text-gray-600 hover:text-primary-600 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-1.638 1.638-2.517 3.71-2.293 5.708.206 1.83.987 3.327 2.293 4.126A4.904 4.904 0 0012 22a4.904 4.904 0 004.293-2.462c1.306-.799 2.087-2.296 2.293-4.126.224-1.998-.655-4.07-2.293-5.708L17 13"/>
                                </svg>
                                @if(session('cart'))
                                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs rounded-full flex items-center justify-center font-bold shadow-lg">
                                        {{ count(session('cart')) }}
                                    </span>
                                @endif
                            </a>

                            <!-- Wishlist -->
                            <a href="{{ route('client.wishlist.index') }}" class="relative p-2 text-gray-600 hover:text-primary-600 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </a>
                        @endif

                        <!-- User Menu -->
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-2 text-gray-700 hover:text-primary-600 transition">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-primary-400 to-indigo-400 flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span class="font-medium">{{ auth()->user()->name }}</span>
                            </a>

                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gradient-to-r from-primary-500 to-indigo-500 text-white rounded-lg font-medium hover:shadow-lg transition">
                                    Admin
                                </a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 text-gray-600 hover:text-red-600 font-medium transition">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-primary-600 font-medium transition">
                                Connexion
                            </a>
                            <a href="{{ route('register') }}" class="btn-primary text-white px-6 py-2.5 rounded-lg font-semibold">
                                Inscription
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in flex-1">
        @if(session('success'))
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-lg mb-6 shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-lg mb-6 shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 001 1h1a1 1 0 100-2h-1V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 rounded-lg gradient-primary flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-primary-600 to-indigo-600 bg-clip-text text-transparent">
                            Boutique Virtuelle
                        </span>
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed max-w-md">
                        Découvrez notre sélection de produits de qualité. Livraison rapide et service client exceptionnel.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Liens Rapides</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="{{ route('home') }}" class="hover:text-primary-600 transition">Accueil</a></li>
                        <li><a href="{{ route('products.index') }}" class="hover:text-primary-600 transition">Produits</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition">À propos</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Support</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="#" class="hover:text-primary-600 transition">FAQ</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition">Livraison</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition">Retours</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition">Conditions</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-100 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} Boutique Virtuelle. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Chat Widget -->
    <div class="fixed bottom-6 right-6 z-50">
        <!-- Chat Button -->
        <button onclick="toggleChat()" id="chatButton" class="w-14 h-14 rounded-full btn-primary flex items-center justify-center shadow-2xl hover:scale-110 transition-transform">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
        </button>

        <!-- Chat Window -->
        <div id="chatWindow" class="hidden absolute bottom-16 right-0 w-80 bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Chat Header -->
            <div class="gradient-primary p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold">Assistant IA</h3>
                        <p class="text-white/70 text-xs">En ligne</p>
                    </div>
                </div>
                <button onclick="toggleChat()" class="text-white/70 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Chat Messages -->
            <div id="chatMessages" class="h-64 overflow-y-auto p-4 bg-gray-50">
                <div class="bg-white rounded-lg p-3 shadow-sm mb-3">
                    <p class="text-sm text-gray-700">Bonjour ! 👋 Je suis l'assistant virtuel. Comment puis-je vous aider aujourd'hui ?</p>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="p-3 border-t border-gray-100 flex gap-2">
                <input type="text" id="chatInput" placeholder="Écrivez votre message..." 
                    class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-500">
                <button onclick="sendMessage()" class="px-4 py-2 btn-primary text-white rounded-lg text-sm font-medium">
                    Envoyer
                </button>
            </div>
        </div>
    </div>

    <script>
        function toggleChat() {
            const chatWindow = document.getElementById('chatWindow');
            chatWindow.classList.toggle('hidden');
        }

        function sendMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value.trim();
            if (!message) return;

            const messagesContainer = document.getElementById('chatMessages');
            
            // Add user message
            const userDiv = document.createElement('div');
            userDiv.className = 'flex justify-end mb-3';
            userDiv.innerHTML = `<div class="bg-primary-500 text-white rounded-lg p-3 shadow-sm max-w-xs"><p class="text-sm">${escapeHtml(message)}</p></div>`;
            messagesContainer.appendChild(userDiv);
            
            input.value = '';
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            // Simulate AI response (placeholder for API integration)
            setTimeout(() => {
                const botDiv = document.createElement('div');
                botDiv.className = 'mb-3';
                botDiv.innerHTML = `<div class="bg-white rounded-lg p-3 shadow-sm"><p class="text-sm text-gray-700">Merci pour votre message ! Je suis un assistant IA en cours de configuration. Bientôt, je pourrai répondre à vos questions.</p></div>`;
                messagesContainer.appendChild(botDiv);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }, 1000);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Enter key to send message
        document.getElementById('chatInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    </script>
</body>
</html>
