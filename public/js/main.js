class FeedbackApp {
  constructor() {
    this.form = document.getElementById('feedback-form');
    this.messagesList = document.getElementById('messages-list');
    this.statusEl = document.getElementById('form-status');
    this.bindEvents();
    this.loadMessages();
  }

  bindEvents() {
    if (!this.form) return;
    this.form.addEventListener('submit', async (e) => {
      e.preventDefault();
      await this.submitForm();
    });
  }

  async submitForm() {
    const btn = document.getElementById('submit-btn');
    const fullName = document.getElementById('full_name');
    const email = document.getElementById('email');
    const message = document.getElementById('message');

    // Сброс ошибок
    ['full_name', 'email', 'message'].forEach(id => {
      const el = document.getElementById(`error-${id}`);
      if (el) el.textContent = '';
    });
    this.statusEl.textContent = '';
    this.statusEl.className = 'status';

    if (btn) btn.disabled = true;

    try {
      const res = await fetch('index.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          action: 'submit',
          full_name: fullName.value,
          email: email.value,
          message: message.value,
        }),
      });

      const data = await res.json();

      if (!res.ok) throw new Error(data.message || 'Ошибка отправки');
      if (!data.success) throw new Error(data.message);

      this.statusEl.textContent = data.message;
      this.statusEl.classList.add('success');
      this.form.reset();
      this.loadMessages(); // обновить список

    } catch (err) {

      this.statusEl.textContent = err.message;
      this.statusEl.classList.add('error');

    } finally {

      if (btn) btn.disabled = false;
    }
  }

  async loadMessages() {
    try {
      const res = await fetch('index.php?action=list');
      const items = await res.json();
      this.renderMessages(items);

    } catch (e) {
      console.error('Failed to load messages:', e);
      this.messagesList.textContent = 'Не удалось загрузить сообщения.';
    }
  }

  renderMessages(items) {
    if (!Array.isArray(items)) {
      this.messagesList.textContent = 'Нет сообщений.';
      return;
    }

    if (items.length === 0) {
      this.messagesList.textContent = 'Пока нет сообщений.';
      return;
    }

    this.messagesList.innerHTML = '';

    items.forEach(item => {
      const card = document.createElement('div');
      card.className = 'message-card';
      
      // Защита от XSS: используем textContent, а не innerHTML
      const meta = document.createElement('div');
      meta.className = 'message-meta';
      meta.textContent = `От: ${this.escapeHtml(item.full_name)} (${item.email}) • ${new Date(item.created_at).toLocaleString()}`;
      const msg = document.createElement('div');
      msg.textContent = this.escapeHtml(item.message); // XSS-защита
      card.appendChild(meta);
      card.appendChild(msg);
      this.messagesList.appendChild(card);
    });
  }

  // Простая защита от XSS на клиенте (экранирование HTML-спецсимволов)
  escapeHtml(str) {
    if (typeof str !== 'string') return '';
    return str
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }
}

// Инициализация приложения после загрузки DOM
document.addEventListener('DOMContentLoaded', () => {
  new FeedbackApp();
});