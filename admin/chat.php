<?php
require_once 'includes/auth_check.php';
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="h-[calc(100vh-140px)] flex flex-col space-y-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">AI Assistant</h1>
        <p class="text-slate-500">Tanyakan apapun tentang materi pelajaran, ide mengajar, atau bantuan teknis.</p>
    </div>

    <div class="flex-1 bg-white rounded-3xl shadow-sm border border-slate-100 flex flex-col overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-emerald-400 p-4 text-white flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                <i class="fas fa-robot"></i>
            </div>
            <div>
                <h3 class="font-bold">Gemini AI Assistant</h3>
                <div class="flex items-center gap-2 text-xs opacity-90">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span> Online
                </div>
            </div>
        </div>

        <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50/50 scroll-smooth">
            <div class="flex justify-start">
                <div class="max-w-[85%] p-4 rounded-2xl shadow-sm bg-white border border-slate-100 text-slate-700 text-sm leading-relaxed rounded-bl-none">
                    <p>👋 Halo ! Saya siap membantu Anda dalam kegiatan belajar mengajar.</p>
                </div>
            </div>
        </div>

        <div class="p-4 bg-white border-t border-slate-100 flex gap-3">
            <input type="text" id="chat-input" placeholder="Ketik pesan Anda di sini..." class="flex-1 px-6 py-3 bg-slate-100 border border-slate-200 rounded-full outline-none focus:ring-2 focus:ring-blue-500 transition-all" onkeypress="if(event.key === 'Enter') sendMessage()">
            <button onclick="sendMessage()" id="send-btn" class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    const chatMessages = document.getElementById('chat-messages');
    const chatInput = document.getElementById('chat-input');
    const sendBtn = document.getElementById('send-btn');

    async function sendMessage() {
        const message = chatInput.value.trim();
        if (!message) return;

        // Add User Message
        addMessage(message, 'user');
        chatInput.value = '';
        chatInput.disabled = true;
        sendBtn.disabled = true;

        // Show Loading
        const loadingId = addLoading();

        try {
            const response = await fetch('../api/chat.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: message })
            });
            
            const data = await response.json();
            removeLoading(loadingId);

            if (data.error) {
                addMessage('Error: ' + data.error, 'bot-error');
            } else if (data.reply) {
                addMessage(data.reply, 'bot');
            }
        } catch (error) {
            removeLoading(loadingId);
            addMessage('Terjadi kesalahan jaringan.', 'bot-error');
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
            sender === 'user' 
            ? 'bg-blue-600 text-white rounded-br-none' 
            : (sender === 'bot-error' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-white border border-slate-100 text-slate-700 rounded-bl-none')
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
</script>

<?php include 'includes/footer.php'; ?>
