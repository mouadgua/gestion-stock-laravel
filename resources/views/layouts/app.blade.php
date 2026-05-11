<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'The Vault')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }

        /* Custom Scrollbar minimaliste */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        @media print {
            #global-nav, aside, footer, #chatWidget, .no-print { display: none !important; }
            body { background: white !important; }
        }
        
        /* Prévention du scroll quand menu mobile ouvert */
        .no-scroll { overflow: hidden; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col overflow-x-hidden relative">

    <header id="global-nav" class="sticky top-0 z-50 bg-slate-950 text-white border-b-2 border-emerald-400 transition-transform duration-500">
        <div class="max-w-[1600px] mx-auto px-4 lg:px-8">
            <div class="flex lg:grid lg:grid-cols-3 items-center justify-between h-20">
                
                <div class="hidden lg:flex items-center gap-8 justify-start">
                    <a href="{{ route('products.index') }}" class="text-xs font-bold uppercase tracking-widest hover:text-emerald-400 transition-colors">
                        Catalogue
                    </a>
                    
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold uppercase tracking-widest hover:text-purple-400 transition-colors">
                                Dashboard Admin
                            </a>
                        @endif
                        @if(auth()->user()->isClient())
                            <a href="{{ route('client.profile') }}" class="text-xs font-bold uppercase tracking-widest hover:text-blue-400 transition-colors">
                                Mon Espace
                            </a>
                        @endif
                    @endauth
                </div>

                <div class="flex justify-center z-50">
                    <a href="/" class="text-3xl font-black uppercase tracking-tighter flex items-center gap-2 group">
                        <i class="fas fa-vault text-emerald-400 group-hover:rotate-12 transition-transform"></i> 
                        THE VAULT.
                    </a>
                </div>

                <div class="hidden lg:flex items-center gap-8 justify-end">
                    @auth
                        @if(auth()->user()->isClient() && !auth()->user()->isAdmin())
                            <div class="flex items-center gap-5">
                                <a href="{{ route('client.wishlist.index') }}" class="text-lg text-slate-400 hover:text-rose-500 transition-colors">
                                    <i class="fas fa-heart"></i>
                                </a>
                                <a href="{{ route('client.cart.index') }}" class="relative text-lg text-slate-400 hover:text-emerald-400 transition-colors">
                                    <i class="fas fa-shopping-cart"></i>
                                    @if(session('cart'))
                                        <span class="absolute -top-2 -right-2 w-4 h-4 bg-emerald-500 rounded-full text-slate-950 text-[9px] flex items-center justify-center font-black">{{ count(session('cart')) }}</span>
                                    @endif
                                </a>
                            </div>
                        @endif
                        
                        <div class="flex items-center gap-6 pl-6 border-l border-slate-800">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 text-slate-300 hover:text-white transition-colors group">
                                <div class="w-8 h-8 bg-slate-800 flex items-center justify-center text-xs font-black uppercase group-hover:bg-white group-hover:text-slate-950 transition-colors">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span class="text-xs font-bold uppercase tracking-widest">{{ auth()->user()->name }}</span>
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-slate-500 hover:text-rose-500 transition-colors" title="Déconnexion">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-widest hover:text-emerald-400 transition-colors">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="bg-white text-slate-950 text-xs font-black uppercase tracking-widest px-6 py-3 hover:bg-emerald-400 transition-colors">
                            S'inscrire
                        </a>
                    @endauth
                </div>

                <div class="lg:hidden flex items-center gap-4 z-50">
                    @if(auth()->check() && auth()->user()->isClient() && !auth()->user()->isAdmin())
                        <a href="{{ route('client.cart.index') }}" class="relative text-lg text-white">
                            <i class="fas fa-shopping-cart"></i>
                            @if(session('cart'))
                                <span class="absolute -top-2 -right-2 w-4 h-4 bg-emerald-500 rounded-full text-slate-950 text-[9px] flex items-center justify-center font-black">{{ count(session('cart')) }}</span>
                            @endif
                        </a>
                    @endif
                    <button id="mobileNavToggle" class="text-2xl text-white focus:outline-none">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div id="mobileNavOverlay" class="fixed inset-0 bg-slate-950 z-[45] flex flex-col pt-24 px-6 pb-8 transform translate-x-full transition-transform duration-500 ease-in-out">
        <nav class="flex flex-col gap-6 text-center mt-10">
            <a href="{{ route('products.index') }}" class="text-2xl font-black uppercase tracking-widest text-white hover:text-emerald-400">Catalogue</a>
            
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="text-2xl font-black uppercase tracking-widest text-white hover:text-purple-400">Dashboard</a>
                @endif
                @if(auth()->user()->isClient())
                    <a href="{{ route('client.profile') }}" class="text-2xl font-black uppercase tracking-widest text-white hover:text-blue-400">Mon Espace</a>
                    <a href="{{ route('client.wishlist.index') }}" class="text-2xl font-black uppercase tracking-widest text-white hover:text-rose-400">Mes Favoris</a>
                @endif
                <div class="h-px w-16 bg-slate-800 mx-auto my-4"></div>
                <a href="{{ route('profile.edit') }}" class="text-sm font-bold uppercase tracking-widest text-slate-400">Paramètres</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="text-sm font-bold uppercase tracking-widest text-rose-500">Déconnexion</button>
                </form>
            @else
                <div class="h-px w-16 bg-slate-800 mx-auto my-4"></div>
                <a href="{{ route('login') }}" class="text-xl font-bold uppercase tracking-widest text-white">Connexion</a>
                <a href="{{ route('register') }}" class="mt-4 bg-emerald-400 text-slate-950 text-xl font-black uppercase tracking-widest px-8 py-4 inline-block mx-auto">S'inscrire</a>
            @endauth
        </nav>
    </div>

    @if(auth()->check() && auth()->user()->isAdmin() && !request()->routeIs('products.*') && !request()->routeIs('home') && !request()->routeIs('profile.*'))
    <div class="flex flex-1 pt-6 px-4 lg:px-8 gap-6 max-w-[1600px] mx-auto w-full">
        <aside class="hidden lg:flex flex-col w-64 shrink-0 bg-white border-2 border-slate-200 shadow-xl p-6 gap-2 sticky top-28 h-[calc(100vh-8rem)] overflow-y-auto z-40">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 border-b-2 border-slate-100 pb-2">Administration</p>
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-widest transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-slate-900 text-white' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fas fa-home w-5 text-center"></i> Accueil
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-widest transition-all {{ request()->routeIs('admin.products.*') ? 'bg-blue-600 text-white' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fas fa-cubes w-5 text-center"></i> Produits
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-widest transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-emerald-600 text-white' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fas fa-folder w-5 text-center"></i> Catégories
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-widest transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-purple-600 text-white' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fas fa-truck w-5 text-center"></i> Commandes
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-widest transition-all {{ request()->routeIs('admin.users.*') ? 'bg-slate-900 text-white' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fas fa-users w-5 text-center"></i> Utilisateurs
            </a>
            <a href="{{ route('admin.activity-logs.index') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-widest transition-all {{ request()->routeIs('admin.activity-logs.*') ? 'bg-slate-900 text-white' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fas fa-history w-5 text-center"></i> Activités
            </a>
        </aside>

        <button onclick="toggleAdminSidebar()" class="lg:hidden fixed bottom-6 left-6 z-50 w-14 h-14 bg-slate-900 text-white rounded-none flex items-center justify-center shadow-xl hover:bg-slate-800 transition-colors">
            <i class="fas fa-layer-group text-xl"></i>
        </button>

        <aside id="mobileAdminSidebar" class="hidden fixed inset-0 z-[60] lg:hidden">
            <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="toggleAdminSidebar()"></div>
            <div class="absolute top-0 left-0 bottom-0 w-72 bg-white p-6 flex flex-col gap-2 overflow-y-auto transform transition-transform">
                <div class="flex items-center justify-between mb-8 pb-4 border-b-4 border-slate-900">
                    <span class="font-black text-xl text-slate-900 uppercase tracking-tighter">Admin.</span>
                    <button onclick="toggleAdminSidebar()" class="text-slate-500 text-xl"><i class="fas fa-times"></i></button>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-3 text-xs font-black uppercase tracking-widest text-slate-700 hover:bg-slate-50 border-l-4 border-transparent hover:border-slate-900">Accueil</a>
                <a href="{{ route('admin.products.index') }}" class="px-4 py-3 text-xs font-black uppercase tracking-widest text-slate-700 hover:bg-slate-50 border-l-4 border-transparent hover:border-blue-600">Produits</a>
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-3 text-xs font-black uppercase tracking-widest text-slate-700 hover:bg-slate-50 border-l-4 border-transparent hover:border-emerald-600">Catégories</a>
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-3 text-xs font-black uppercase tracking-widest text-slate-700 hover:bg-slate-50 border-l-4 border-transparent hover:border-purple-600">Commandes</a>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-3 text-xs font-black uppercase tracking-widest text-slate-700 hover:bg-slate-50 border-l-4 border-transparent hover:border-slate-900">Utilisateurs</a>
                <a href="{{ route('admin.activity-logs.index') }}" class="px-4 py-3 text-xs font-black uppercase tracking-widest text-slate-700 hover:bg-slate-50 border-l-4 border-transparent hover:border-slate-900">Activités</a>
            </div>
        </aside>

        <main class="flex-1 w-full relative" id="main-content">
    @else
        <main class="flex-1 w-full max-w-[1600px] mx-auto px-4 lg:px-8 py-10" id="main-content">
    @endif

            @yield('content')

        </main>
    @if(auth()->check() && auth()->user()->isAdmin() && !request()->routeIs('products.*') && !request()->routeIs('home') && !request()->routeIs('profile.*'))
    </div>
    @endif

    <footer class="mt-auto border-t-4 border-slate-900 bg-white">
        <div class="max-w-[1600px] mx-auto px-4 lg:px-8 py-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <span class="text-xl font-black uppercase tracking-tighter">The Vault.</span>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">&copy; {{ date('Y') }} Sécurisé et chiffré. | Prix en DH</p>
        </div>
    </footer>

    @if(session('success') || session('error'))
        <div id="globalAlertModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-sm">
            <div class="absolute inset-0" onclick="closeGlobalAlert()"></div>
            <div class="bg-white border-4 border-slate-900 max-w-sm w-full p-8 relative z-10 opacity-0 transform scale-95" id="globalAlertContent">
                <button onclick="closeGlobalAlert()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
                @if(session('success'))
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 bg-emerald-50 border-2 border-emerald-500 text-emerald-500 flex items-center justify-center text-3xl mb-6 shadow-[4px_4px_0px_0px_rgba(16,185,129,1)]">
                            <i class="fas fa-check"></i>
                        </div>
                        <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tighter mb-2">Succès</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ session('success') }}</p>
                    </div>
                @endif
                @if(session('error'))
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 bg-red-50 border-2 border-red-500 text-red-500 flex items-center justify-center text-3xl mb-6 shadow-[4px_4px_0px_0px_rgba(239,68,68,1)]">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tighter mb-2">Erreur</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ session('error') }}</p>
                    </div>
                @endif
                <button onclick="closeGlobalAlert()" class="w-full mt-8 bg-slate-900 text-white py-4 font-black uppercase tracking-widest hover:bg-slate-800 transition-colors text-xs">
                    Continuer
                </button>
            </div>
        </div>
    @endif

    <div id="chatWidget" class="fixed bottom-6 right-6 z-50">
        <button onclick="toggleChat()" class="w-14 h-14 bg-slate-900 flex items-center justify-center shadow-[4px_4px_0px_0px_rgba(52,211,153,1)] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all duration-200">
            <i class="fas fa-comment-dots text-white text-xl"></i>
        </button>
        
        <div id="chatWindow" class="hidden absolute bottom-20 right-0 w-[90vw] sm:w-96 bg-white border-4 border-slate-900 shadow-2xl overflow-hidden origin-bottom-right transition-transform">
            <div class="bg-slate-900 p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white text-slate-900 flex items-center justify-center font-black"><i class="fas fa-robot text-xs"></i></div>
                    <div>
                        <h3 class="text-white font-black text-sm uppercase tracking-widest">Assistant</h3>
                        <p class="text-emerald-400 text-[10px] font-bold uppercase tracking-widest flex items-center gap-1 mt-0.5"><span class="w-1.5 h-1.5 bg-emerald-400 animate-pulse"></span> En ligne</p>
                    </div>
                </div>
                <button onclick="toggleChat()" class="text-white/40 hover:text-white transition-colors"><i class="fas fa-times"></i></button>
            </div>
            
            <div id="chatMessages" class="h-72 overflow-y-auto p-4 bg-slate-50 space-y-4">
                <div class="flex items-end gap-2">
                    <div class="w-6 h-6 bg-slate-900 flex items-center justify-center shrink-0"><i class="fas fa-robot text-white text-[10px]"></i></div>
                    <div class="bg-white border-2 border-slate-200 p-3 shadow-sm max-w-[80%]">
                        <p class="text-xs font-bold text-slate-700 leading-relaxed uppercase tracking-wide">Bonjour ! Bienvenue dans The Vault. Comment puis-je vous assister ?</p>
                    </div>
                </div>
            </div>
            
            <div class="p-3 border-t-2 border-slate-900 bg-white">
                <div class="flex gap-2">
                    <input type="text" id="chatInput" placeholder="VOTRE MESSAGE..." class="flex-1 px-4 py-3 bg-slate-50 border-2 border-slate-200 text-xs font-bold uppercase tracking-widest focus:bg-white focus:border-slate-900 focus:ring-0 transition-all outline-none">
                    <button onclick="sendMessage()" class="bg-slate-900 hover:bg-slate-800 px-4 text-white transition-colors flex items-center justify-center">
                        <i class="fas fa-paper-plane text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Menu Mobile (Navbar)
        const mobileNavToggle = document.getElementById('mobileNavToggle');
        const mobileNavOverlay = document.getElementById('mobileNavOverlay');
        const body = document.body;

        mobileNavToggle.addEventListener('click', () => {
            const isClosed = mobileNavOverlay.classList.contains('translate-x-full');
            if (isClosed) {
                mobileNavOverlay.classList.remove('translate-x-full');
                mobileNavToggle.innerHTML = '<i class="fas fa-times"></i>';
                body.classList.add('no-scroll');
            } else {
                mobileNavOverlay.classList.add('translate-x-full');
                mobileNavToggle.innerHTML = '<i class="fas fa-bars"></i>';
                body.classList.remove('no-scroll');
            }
        });

        // Sidebar Mobile Admin
        function toggleAdminSidebar(){document.getElementById('mobileAdminSidebar').classList.toggle('hidden');}

        // Modale d'alerte
        const alertContent = document.getElementById('globalAlertContent');
        if (alertContent) {
            gsap.to(alertContent, { scale: 1, opacity: 1, duration: 0.5, ease: "back.out(1.5)", delay: 0.1 });
        }
        function closeGlobalAlert() {
            const alertModal = document.getElementById('globalAlertModal');
            if (alertModal && alertContent) {
                gsap.to(alertContent, { scale: 0.9, opacity: 0, duration: 0.3, onComplete: () => alertModal.remove() });
            }
        }

        // Chatbot
        let chatMessages=[],isLoadingHistory=false;
        function toggleChat(){
            const win = document.getElementById('chatWindow');
            win.classList.toggle('hidden');
            if(!win.classList.contains('hidden')) {
                gsap.fromTo(win, {scale: 0.9, opacity: 0}, {scale: 1, opacity: 1, duration: 0.3, ease: "back.out(1.7)"});
                if(chatMessages.length===0) loadChatHistory();
            }
        }
        
        function loadChatHistory(){
            if(isLoadingHistory)return;
            isLoadingHistory=true;
            fetch('{{ route("chat.history") }}').then(r=>r.json()).then(d=>{
                chatMessages=d.messages||[];
                renderChatHistory();
                isLoadingHistory=false;
            }).catch(()=>{isLoadingHistory=false;});
        }
        
        function renderChatHistory(){
            const c=document.getElementById('chatMessages');
            c.innerHTML='';
            if(chatMessages.length===0){
                c.innerHTML=`<div class="flex items-end gap-2"><div class="w-6 h-6 bg-slate-900 flex items-center justify-center shrink-0"><i class="fas fa-robot text-white text-[10px]"></i></div><div class="bg-white border-2 border-slate-200 p-3 shadow-sm max-w-[80%]"><p class="text-xs font-bold text-slate-700 leading-relaxed uppercase tracking-wide">Bonjour ! Bienvenue dans The Vault. Comment puis-je vous assister ?</p></div></div>`;
                return;
            }
            chatMessages.forEach(msg=>{
                const d=document.createElement('div');
                if(msg.sender_type==='system'){
                    d.className='flex items-end gap-2';
                    d.innerHTML=`<div class="w-6 h-6 bg-slate-900 flex items-center justify-center shrink-0"><i class="fas fa-robot text-white text-[10px]"></i></div><div class="bg-white border-2 border-slate-200 p-3 shadow-sm max-w-[80%]"><p class="text-xs font-bold text-slate-700 leading-relaxed uppercase tracking-wide">${escapeHtml(msg.message)}</p></div>`;
                }else{
                    d.className='flex items-end gap-2 flex-row-reverse';
                    d.innerHTML=`<div class="w-6 h-6 bg-slate-200 flex items-center justify-center shrink-0"><i class="fas fa-user text-slate-600 text-[10px]"></i></div><div class="bg-slate-900 p-3 shadow-sm max-w-[80%]"><p class="text-xs font-bold text-white leading-relaxed uppercase tracking-wide">${escapeHtml(msg.message)}</p></div>`;
                }
                c.appendChild(d);
            });
            c.scrollTop=c.scrollHeight;
        }
        
        function sendMessage(){
            const input=document.getElementById('chatInput');
            const msg=input.value.trim();
            if(!msg)return;
            
            const c=document.getElementById('chatMessages');
            const d=document.createElement('div');
            d.className='flex items-end gap-2 flex-row-reverse';
            d.innerHTML=`<div class="w-6 h-6 bg-slate-200 flex items-center justify-center shrink-0"><i class="fas fa-user text-slate-600 text-[10px]"></i></div><div class="bg-slate-900 p-3 shadow-sm max-w-[80%]"><p class="text-xs font-bold text-white leading-relaxed uppercase tracking-wide">${escapeHtml(msg)}</p></div>`;
            c.appendChild(d);
            
            input.value='';
            c.scrollTop=c.scrollHeight;
            
            fetch('{{ route("chat.store") }}',{
                method:'POST',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                body:JSON.stringify({message:msg})
            }).then(r=>r.json()).then(data=>{
                if(data.success) chatMessages.push(data.message);
            }).catch(()=>{});
            
            setTimeout(()=>{
                const b=document.createElement('div');
                b.className='flex items-end gap-2';
                b.innerHTML=`<div class="w-6 h-6 bg-slate-900 flex items-center justify-center shrink-0"><i class="fas fa-robot text-white text-[10px]"></i></div><div class="bg-white border-2 border-slate-200 p-3 shadow-sm max-w-[80%]"><p class="text-xs font-bold text-slate-700 leading-relaxed uppercase tracking-wide">Merci ! Un agent Vault vous répondra sous peu.</p></div>`;
                c.appendChild(b);
                c.scrollTop=c.scrollHeight;
            }, 1200);
        }
        
        function escapeHtml(text){const d=document.createElement('div');d.textContent=text;return d.innerHTML;}
        document.getElementById('chatInput').addEventListener('keypress',function(e){if(e.key==='Enter')sendMessage();});

        // Animations Globales
        document.addEventListener("DOMContentLoaded", () => {
            gsap.fromTo("#main-content", { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.6, delay: 0.2, ease: "power2.out" });
        });
    </script>

    @stack('scripts')
</body>
</html>