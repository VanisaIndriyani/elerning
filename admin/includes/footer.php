    </main>
</div>
</div> <!-- End Flex Container -->

<!-- Floating Chat Button Admin -->
<button onclick="toggleChat()" class="fixed bottom-6 right-6 w-14 h-14 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 active:scale-95 transition-all flex items-center justify-center z-50 group">
    <i class="fas fa-comment-dots text-2xl"></i>
    <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 rounded-full border-2 border-white"></span>
</button>

<!-- Chat Modal Admin -->
<div id="chat-modal" class="fixed bottom-24 right-6 w-full max-w-sm bg-white rounded-3xl shadow-2xl border border-slate-100 z-50 hidden flex-col h-[500px] transition-all transform origin-bottom-right scale-90 opacity-0">
    <div class="bg-gradient-to-r from-blue-600 to-emerald-400 p-4 rounded-t-3xl text-white flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                <i class="fas fa-robot"></i>
            </div>
            <div>
                <h3 class="font-bold">Asisten Guru</h3>
                <p class="text-xs opacity-90">Bantu ajar & administrasi</p>
            </div>
        </div>
        <button onclick="toggleChat()" class="text-white/80 hover:text-white"><i class="fas fa-times"></i></button>
    </div>
    
    <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50">
        <div class="flex justify-start">
            <div class="max-w-[85%] p-3 rounded-2xl shadow-sm bg-white border border-slate-100 text-slate-700 text-sm leading-relaxed rounded-bl-none">
                Halo Pak Guru! Ada yang bisa saya bantu untuk persiapan mengajar atau administrasi hari ini? 👨‍🏫
            </div>
        </div>
    </div>

    <div class="p-3 bg-white border-t border-slate-100">
        <div class="flex gap-2">
            <input type="text" id="chat-input" placeholder="Tanya ide mengajar..." class="flex-1 px-4 py-2 bg-slate-100 border border-slate-200 rounded-full outline-none focus:ring-2 focus:ring-blue-500 text-sm" onkeypress="if(event.key === 'Enter') sendMessage()">
            <button onclick="sendMessage()" id="send-btn" class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                <i class="fas fa-paper-plane text-sm"></i>
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    // Sidebar Toggle Logic
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebarCollapseBtn = document.getElementById('sidebar-collapse-btn');
    const sidebarTexts = document.querySelectorAll('.sidebar-text');
    let isCollapsed = false;

    // Mobile Toggle
    function toggleMobileSidebar() {
        sidebar.classList.toggle('-translate-x-full');
    }

    // Desktop Collapse
    function toggleDesktopSidebar() {
        isCollapsed = !isCollapsed;
        if (isCollapsed) {
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-20');
            sidebarTexts.forEach(el => el.classList.add('opacity-0', 'pointer-events-none', 'hidden'));
            sidebarCollapseBtn.querySelector('i').classList.remove('fa-chevron-left');
            sidebarCollapseBtn.querySelector('i').classList.add('fa-chevron-right');
        } else {
            sidebar.classList.remove('w-20');
            sidebar.classList.add('w-64');
            sidebarTexts.forEach(el => {
                el.classList.remove('hidden');
                // Small delay to allow display block to apply before opacity transition
                setTimeout(() => el.classList.remove('opacity-0', 'pointer-events-none'), 10);
            });
            sidebarCollapseBtn.querySelector('i').classList.remove('fa-chevron-right');
            sidebarCollapseBtn.querySelector('i').classList.add('fa-chevron-left');
        }
    }

    if(sidebarToggle) sidebarToggle.addEventListener('click', toggleMobileSidebar);
    if(sidebarCollapseBtn) sidebarCollapseBtn.addEventListener('click', toggleDesktopSidebar);

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
        if(window.innerWidth < 1024) {
            if(!sidebar.contains(e.target) && !sidebarToggle.contains(e.target) && !sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.add('-translate-x-full');
            }
        }
    });

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
            // Adjust path if we are in admin subfolder
            const response = await fetch('../api/chat.php', {
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
        bubble.className = `max-w-[85%] p-3 rounded-2xl shadow-sm text-sm leading-relaxed ${
            sender === 'user' 
            ? 'bg-blue-600 text-white rounded-br-none' 
            : 'bg-white border border-slate-100 text-slate-700 rounded-bl-none'
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
            <div class="bg-white border border-slate-100 p-3 rounded-2xl rounded-bl-none shadow-sm flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce"></span>
                <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce delay-100"></span>
                <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce delay-200"></span>
            </div>
        `;
        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        return id;
    }
</script>
</body>
</html>