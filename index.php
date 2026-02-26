<?php
require_once 'config/database.php';

// Fetch materials grouped by subject
$stmt = $pdo->query("SELECT * FROM materials ORDER BY created_at DESC");
$all_materials = $stmt->fetchAll();
$materials_by_subject = [];
foreach ($all_materials as $m) {
    $materials_by_subject[$m['subject']][] = $m;
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materi Pelajaran - Javiardi Dima</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .chat-bubble-bot { background: white; border: 1px solid #e2e8f0; border-bottom-left-radius: 2px; }
        .chat-bubble-user { background: #2563eb; color: white; border-bottom-right-radius: 2px; }
        
        .hero-pattern {
            background-image: radial-gradient(rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="text-slate-800 bg-slate-50">

    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100">
        <div class="container mx-auto px-4 sm:px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-tr from-blue-600 to-emerald-400 rounded-xl flex items-center justify-center text-white font-bold shadow-lg shadow-blue-200">JD</div>
                <div>
                    <h1 class="font-bold text-slate-800 text-lg leading-none">E-Learning</h1>
                    <p class="text-xs text-slate-500 font-medium">Javiardi Dima</p>
                </div>
            </div>
            <a href="admin/login.php" class="bg-slate-100 hover:bg-blue-50 text-slate-600 hover:text-blue-600 px-5 py-2.5 rounded-full text-sm font-semibold transition-all border border-transparent hover:border-blue-100 flex items-center gap-2">
                <i class="fas fa-user-shield"></i> <span class="hidden sm:inline">Login Guru</span>
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative bg-gradient-to-br from-blue-600 to-emerald-500 text-white pt-20 pb-24 overflow-hidden">
        <div class="absolute inset-0 hero-pattern opacity-20"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-slate-50 to-transparent"></div>
        
        <div class="container mx-auto px-4 sm:px-6 relative z-10 text-center">
            <span class="inline-block py-1 px-3 rounded-full bg-white/20 backdrop-blur-sm border border-white/30 text-xs font-bold uppercase tracking-wider mb-6 animate-pulse">Selamat Datang Siswa</span>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold mb-6 leading-tight">Belajar Kapanpun,<br>Di Manapun.</h1>
            <p class="text-lg sm:text-xl text-blue-50 max-w-2xl mx-auto mb-10 font-light">Akses materi pelajaran lengkap dan tanyakan apa saja pada asisten AI kami yang siap membantu 24/7.</p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#materi" class="bg-white text-blue-600 px-8 py-4 rounded-full font-bold text-lg hover:bg-blue-50 hover:shadow-lg hover:-translate-y-1 transition-all shadow-xl shadow-blue-900/20 flex items-center justify-center gap-2">
                    <i class="fas fa-book-open"></i> Mulai Belajar
                </a>
                <button onclick="toggleChat()" class="bg-transparent border-2 border-white/30 hover:bg-white/10 text-white px-8 py-4 rounded-full font-bold text-lg hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-robot"></i> Tanya AI
                </button>
            </div>
        </div>
    </header>

    <main id="materi" class="container mx-auto px-4 sm:px-6 -mt-16 relative z-20 pb-24">
        
        <?php if (empty($materials_by_subject)): ?>
            <div class="bg-white rounded-[2.5rem] shadow-xl p-12 text-center border border-slate-100">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <i class="fas fa-folder-open text-4xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-slate-800 mb-2">Belum Ada Materi</h2>
                <p class="text-slate-500">Materi pelajaran akan segera diupdate oleh guru.</p>
            </div>
        <?php else: ?>
            <div class="space-y-16">
                <?php foreach ($materials_by_subject as $subject => $materials): ?>
                <section>
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center text-blue-600 text-xl border border-slate-100">
                            <?php if($subject == 'Informatika'): ?><i class="fas fa-laptop-code"></i>
                            <?php elseif($subject == 'KKA'): ?><i class="fas fa-tools"></i>
                            <?php else: ?><i class="fas fa-layer-group"></i><?php endif; ?>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-800"><?= htmlspecialchars($subject) ?></h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach ($materials as $m): ?>
                        <div class="group bg-white rounded-3xl shadow-sm hover:shadow-2xl hover:shadow-blue-100/50 border border-slate-100 hover:border-blue-200 transition-all duration-300 flex flex-col h-full overflow-hidden relative">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-blue-50 to-transparent rounded-bl-full -mr-8 -mt-8 opacity-50 group-hover:scale-110 transition-transform"></div>
                            
                            <div class="p-8 flex-1 relative z-10">
                                <span class="inline-block px-3 py-1 bg-slate-100 text-slate-500 rounded-lg text-[10px] font-bold uppercase tracking-wide mb-4 border border-slate-200">
                                    <i class="far fa-clock mr-1"></i> <?= date('d M Y', strtotime($m['created_at'])) ?>
                                </span>
                                <h3 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-blue-600 transition-colors leading-tight"><?= htmlspecialchars($m['title']) ?></h3>
                                <p class="text-slate-500 text-sm line-clamp-3 leading-relaxed"><?= htmlspecialchars($m['description']) ?></p>
                            </div>
                            
                            <div class="px-8 pb-8 pt-0 mt-auto relative z-10">
                                <button onclick='openMaterial(<?= htmlspecialchars(json_encode($m), ENT_QUOTES) ?>)' class="w-full py-3.5 bg-slate-50 text-slate-600 rounded-2xl font-bold text-sm hover:bg-blue-600 hover:text-white transition-all group-hover:shadow-lg flex items-center justify-center gap-2 group/btn">
                                    <span>Pelajari Materi</span>
                                    <i class="fas fa-arrow-right group-hover/btn:translate-x-1 transition-transform"></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>

    <footer class="bg-white border-t border-slate-100 py-12">
        <div class="container mx-auto px-6 text-center">
            <div class="w-12 h-12 bg-gradient-to-tr from-blue-600 to-emerald-400 rounded-xl flex items-center justify-center text-white font-bold shadow-lg mx-auto mb-4">JD</div>
            <p class="text-slate-500 text-sm font-medium">&copy; <?= date('Y') ?> Javiardi Dima. E-Learning Platform.</p>
        </div>
    </footer>

    <!-- Floating Chat Button -->
    <button onclick="toggleChat()" class="fixed bottom-6 right-6 w-14 h-14 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 active:scale-95 transition-all flex items-center justify-center z-50 group hover:shadow-blue-500/30">
        <i class="fas fa-comment-dots text-2xl"></i>
        <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 rounded-full border-2 border-white animate-bounce"></span>
    </button>

    <!-- Chat Modal -->
    <div id="chat-modal" class="fixed bottom-24 right-6 w-full max-w-sm bg-white rounded-[2rem] shadow-2xl border border-slate-100 z-50 hidden flex-col h-[550px] transition-all transform origin-bottom-right scale-90 opacity-0 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-emerald-500 p-5 text-white flex justify-between items-center relative overflow-hidden">
            <div class="absolute inset-0 bg-white/10 hero-pattern opacity-20"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center border border-white/30">
                    <i class="fas fa-robot text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg">Asisten AI</h3>
                    <p class="text-xs opacity-90 flex items-center gap-1"><span class="w-2 h-2 bg-emerald-300 rounded-full animate-pulse"></span> Online 24/7</p>
                </div>
            </div>
            <button onclick="toggleChat()" class="text-white/80 hover:text-white hover:bg-white/10 w-8 h-8 rounded-full flex items-center justify-center transition-all relative z-10"><i class="fas fa-times"></i></button>
        </div>
        
        <div id="chat-messages" class="flex-1 overflow-y-auto p-5 space-y-4 bg-slate-50 scroll-smooth">
            <div class="flex justify-start">
                <div class="max-w-[85%] p-4 rounded-2xl shadow-sm chat-bubble-bot text-sm leading-relaxed">
                    Halo! 👋 Saya asisten AI Anda. Ada materi pelajaran yang kurang dipahami? Tanyakan saja!
                </div>
            </div>
        </div>

        <div class="p-4 bg-white border-t border-slate-100">
            <div class="relative">
                <input type="text" id="chat-input" placeholder="Ketik pertanyaan..." class="w-full pl-5 pr-12 py-3.5 bg-slate-100 border border-slate-200 rounded-full outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm shadow-inner" onkeypress="if(event.key === 'Enter') sendMessage()">
                <button onclick="sendMessage()" id="send-btn" class="absolute right-2 top-1/2 -translate-y-1/2 w-9 h-9 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-all shadow-md hover:scale-105 active:scale-95">
                    <i class="fas fa-paper-plane text-xs"></i>
                </button>
            </div>
            <p class="text-[10px] text-center text-slate-400 mt-3 flex items-center justify-center gap-1">
                <i class="fas fa-bolt text-amber-400"></i> Powered by Gemini AI
            </p>
        </div>
    </div>

    <!-- Material Modal -->
    <div id="material-modal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4 transition-opacity opacity-0">
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col transform scale-95 transition-transform duration-300" id="modal-panel">
            <div class="p-6 sm:p-8 border-b border-slate-100 flex justify-between items-start bg-slate-50/50 rounded-t-[2rem]">
                <div>
                    <span id="modal-subject" class="inline-block px-3 py-1 bg-blue-100 text-blue-600 rounded-lg text-xs font-bold uppercase tracking-wider mb-2"></span>
                    <h2 id="modal-title" class="text-2xl sm:text-3xl font-bold text-slate-800 leading-tight"></h2>
                </div>
                <button onclick="closeMaterial()" class="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50 transition-all shadow-sm shrink-0 ml-4">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 sm:p-8 overflow-y-auto prose prose-slate max-w-none prose-a:text-blue-600 hover:prose-a:underline" id="modal-content">
                <!-- Content injected here -->
            </div>
            <div class="p-6 sm:p-8 border-t border-slate-100 bg-slate-50/50 rounded-b-[2rem] flex justify-end">
                <button onclick="toggleChat(); closeMaterial();" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-emerald-500 text-white rounded-xl font-bold shadow-lg hover:shadow-blue-500/30 hover:-translate-y-1 transition-all">
                    <i class="fas fa-robot"></i> Tanya AI tentang materi ini
                </button>
            </div>
        </div>
    </div>

    <script>
        // Chat Logic
        const chatModal = document.getElementById('chat-modal');
        const chatMessages = document.getElementById('chat-messages');
        const chatInput = document.getElementById('chat-input');
        const sendBtn = document.getElementById('send-btn');
        let isChatOpen = false;

        function toggleChat() {
            isChatOpen = !isChatOpen;
            if (isChatOpen) {
                chatModal.classList.remove('hidden');
                setTimeout(() => {
                    chatModal.classList.remove('scale-90', 'opacity-0');
                    chatModal.classList.add('scale-100', 'opacity-100');
                    chatInput.focus();
                }, 10);
            } else {
                chatModal.classList.remove('scale-100', 'opacity-100');
                chatModal.classList.add('scale-90', 'opacity-0');
                setTimeout(() => {
                    chatModal.classList.add('hidden');
                }, 300);
            }
        }

        async function sendMessage() {
            const message = chatInput.value.trim();
            if (!message) return;

            addMessage(message, 'user');
            chatInput.value = '';
            chatInput.disabled = true;
            sendBtn.disabled = true;

            const loadingId = addLoading();

            try {
                const response = await fetch('api/chat.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: message })
                });
                
                const data = await response.json();
                removeLoading(loadingId);

                if (data.error) {
                    addMessage('Error: ' + data.error, 'bot');
                } else if (data.reply) {
                    addMessage(data.reply, 'bot');
                }
            } catch (error) {
                removeLoading(loadingId);
                addMessage('Maaf, terjadi kesalahan jaringan.', 'bot');
            } finally {
                chatInput.disabled = false;
                sendBtn.disabled = false;
                chatInput.focus();
            }
        }

        function addMessage(text, sender) {
            const div = document.createElement('div');
            div.className = `flex ${sender === 'user' ? 'justify-end' : 'justify-start'}`;
            
            const bubble = document.createElement('div');
            bubble.className = `max-w-[85%] p-4 rounded-2xl shadow-sm text-sm leading-relaxed ${
                sender === 'user' ? 'chat-bubble-user shadow-blue-500/20' : 'chat-bubble-bot shadow-sm'
            }`;
            
            if (sender === 'bot') {
                bubble.innerHTML = marked.parse(text);
            } else {
                bubble.textContent = text;
            }

            div.appendChild(bubble);
            chatMessages.appendChild(div);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function addLoading() {
            const id = 'loading-' + Date.now();
            const div = document.createElement('div');
            div.id = id;
            div.className = 'flex justify-start';
            div.innerHTML = `
                <div class="bg-white border border-slate-100 p-4 rounded-2xl rounded-bl-none shadow-sm flex items-center gap-2">
                    <span class="w-2 h-2 bg-slate-400 rounded-full animate-bounce"></span>
                    <span class="w-2 h-2 bg-slate-400 rounded-full animate-bounce delay-100"></span>
                    <span class="w-2 h-2 bg-slate-400 rounded-full animate-bounce delay-200"></span>
                </div>
            `;
            chatMessages.appendChild(div);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            return id;
        }

        function removeLoading(id) {
            const el = document.getElementById(id);
            if (el) el.remove();
        }

        // Material Modal Logic
        const materialModal = document.getElementById('material-modal');
        const modalPanel = document.getElementById('modal-panel');

        function openMaterial(data) {
            document.getElementById('modal-subject').textContent = data.subject;
            document.getElementById('modal-title').textContent = data.title;
            
            const content = data.content;
            const contentContainer = document.getElementById('modal-content');
            
            if (isValidURL(content)) {
                contentContainer.innerHTML = `
                    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 text-center">
                        <i class="fas fa-link text-4xl text-blue-300 mb-4"></i>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Materi berupa Link Eksternal</h3>
                        <a href="${content}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 font-bold hover:underline break-all">
                            ${content} <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                        <p class="text-sm text-slate-500 mt-4">Klik link di atas untuk membuka materi di tab baru.</p>
                    </div>
                `;
            } else {
                contentContainer.innerHTML = marked.parse(content);
            }
            
            materialModal.classList.remove('hidden');
            setTimeout(() => {
                materialModal.classList.remove('opacity-0');
                modalPanel.classList.remove('scale-95');
                modalPanel.classList.add('scale-100');
            }, 10);
        }

        function closeMaterial() {
            materialModal.classList.add('opacity-0');
            modalPanel.classList.remove('scale-100');
            modalPanel.classList.add('scale-95');
            setTimeout(() => {
                materialModal.classList.add('hidden');
            }, 300);
        }

        function isValidURL(string) {
            try { return Boolean(new URL(string)); } catch(e){ return false; }
        }

        // Close modal on click outside
        materialModal.addEventListener('click', (e) => {
            if (e.target === materialModal) closeMaterial();
        });
    </script>
</body>
</html>
