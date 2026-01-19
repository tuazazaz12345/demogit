/**
 * CHATBOT WIDGET - Auto Chat with Icon Interaction
 * Tự động hiển thị chatbox với icon tương tác trên màn hình
 */

class ChatbotWidget {
  constructor(config = {}) {
    // Xác định base URL động
    const baseUrl = window.location.origin + "/xamcc/htdocs/phpnangcao/MVC";

    this.config = {
      apiEndpoint:
        config.apiEndpoint || baseUrl + "/index.php?url=Chatbox/send",
      getMessagesEndpoint:
        config.getMessagesEndpoint ||
        baseUrl + "/index.php?url=Chatbox/getMessages",
      checkLoginEndpoint:
        config.checkLoginEndpoint ||
        baseUrl + "/index.php?url=Chatbox/checkLogin",
      position: config.position || "bottom-right",
      autoOpen: config.autoOpen !== false,
      autoOpenDelay: config.autoOpenDelay || 3000,
      theme: config.theme || "purple",
      title: config.title || "Hỗ Trợ Khách Hàng",
      subtitle: config.subtitle || "Chúng tôi sẵn sàng hỗ trợ",
      placeholderText: config.placeholderText || "Nhập tin nhắn...",
      ...config,
    };

    this.isOpen = false;
    this.messages = [];
    this.unreadCount = 0;
    this.userInfo = null;

    this.init();
  }

  init() {
    this.createWidgetHTML();
    this.setupEventListeners();
    this.loadUserInfo();

    // Auto-open chatbox sau delay
    if (this.config.autoOpen) {
      setTimeout(() => {
        this.open();
        this.loadMessages();
      }, this.config.autoOpenDelay);
    }
  }

  createWidgetHTML() {
    const container = document.createElement("div");
    container.id = "chatbot-widget-container";
    container.innerHTML = `
            <div class="chatbot-icon-container">
                <button class="chatbot-icon" id="chatbot-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                    </svg>
                    <span class="chatbot-badge" id="chatbot-badge">0</span>
                </button>

                <div class="chatbox-window" id="chatbox-window">
                    <!-- Header -->
                    <div class="chatbox-header">
                        <div>
                            <h3>${this.config.title}</h3>
                            <div class="chatbox-header-subtitle">${this.config.subtitle}</div>
                        </div>
                        <button class="chatbox-close" id="chatbox-close">×</button>
                    </div>

                    <!-- Messages Body -->
                    <div class="chatbox-body" id="chatbox-body">
                        <div class="chatbox-greeting">
                            <div class="chatbox-greeting-emoji">👋</div>
                            <h4>Xin chào!</h4>
                            <p>Chúng tôi sẵn sàng giúp bạn. Hãy gửi tin nhắn để chúng tôi có thể hỗ trợ tốt hơn.</p>
                        </div>
                    </div>

                    <!-- Footer / Input -->
                    <div class="chatbox-footer">
                        <div class="chatbox-input-group">
                            <textarea 
                                class="chatbox-input" 
                                id="chatbox-input" 
                                placeholder="${this.config.placeholderText}"
                                rows="1"
                            ></textarea>
                            <button class="chatbox-send" id="chatbox-send" title="Gửi tin nhắn">
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                                    <path d="M16.6915026,12.4744748 L3.50612381,13.2599618 C3.19218622,13.2599618 3.03521743,13.4170592 3.03521743,13.5741566 L1.15159189,20.0151496 C0.8376543,20.8006365 0.99,21.89 1.77946707,22.52 C2.41,22.99 3.50612381,23.1 4.13399899,22.8429026 L21.714504,14.0454487 C22.6563168,13.5741566 23.1272231,12.6315722 22.9702544,11.6889879 L4.13399899,1.16814159 C3.34915502,0.9110442 2.40734225,0.9110442 1.77946707,1.4380191 C0.994623095,2.0738225 0.837654326,3.16346272 1.15159189,3.94894957 L3.03521743,10.3899425 C3.03521743,10.5470399 3.34915502,10.7041373 3.50612381,10.7041373 L16.6915026,11.4896242 C16.6915026,11.4896242 17.1624089,11.4896242 17.1624089,12.0166013 C17.1624089,12.5435784 16.6915026,12.4744748 16.6915026,12.4744748 Z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

    document.body.appendChild(container);
  }

  setupEventListeners() {
    const icon = document.getElementById("chatbot-icon");
    const closeBtn = document.getElementById("chatbox-close");
    const sendBtn = document.getElementById("chatbox-send");
    const input = document.getElementById("chatbox-input");

    // Open/Close chatbox
    icon.addEventListener("click", () => {
      if (this.isOpen) {
        this.close();
      } else {
        this.open();
        this.loadMessages();
      }
    });

    closeBtn.addEventListener("click", () => this.close());

    // Send message
    sendBtn.addEventListener("click", () => this.sendMessage());
    input.addEventListener("keypress", (e) => {
      if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault();
        this.sendMessage();
      }
    });

    // Auto-resize textarea
    input.addEventListener("input", () => {
      input.style.height = "auto";
      input.style.height = Math.min(input.scrollHeight, 100) + "px";
    });
  }

  loadUserInfo() {
    // Trước tiên kiểm tra xem user có đăng nhập trong hệ thống không
    this.checkServerLogin().then(() => {
      // Nếu không có thông tin từ server, lấy từ localStorage
      if (!this.userInfo) {
        this.requestUserInfo();
      }
    });
  }

  async checkServerLogin() {
    // Kiểm tra xem user có đăng nhập trên server không
    try {
      const response = await fetch(this.config.checkLoginEndpoint);
      if (response.ok) {
        const data = await response.json();
        if (data.isLoggedIn) {
          this.userInfo = {
            email: data.email,
            name: data.name,
          };
          // Lưu vào localStorage để dùng lần sau
          localStorage.setItem(
            "chatbot-user-info",
            JSON.stringify(this.userInfo)
          );
          console.log("User logged in:", this.userInfo.email);
        }
      }
    } catch (e) {
      console.warn("Could not check server login:", e);
    }
  }

  requestUserInfo() {
    const email = localStorage.getItem("user-email");
    const name = localStorage.getItem("user-name");

    if (email && name) {
      this.userInfo = { email, name };
      localStorage.setItem("chatbot-user-info", JSON.stringify(this.userInfo));
    } else {
      // User chưa đăng nhập, sẽ hỏi khi gửi message
      this.userInfo = null;
    }
  }

  open() {
    const chatbox = document.getElementById("chatbox-window");
    const icon = document.getElementById("chatbot-icon");

    chatbox.classList.add("active");
    icon.style.opacity = "0.7";
    this.isOpen = true;

    // Clear unread badge
    this.unreadCount = 0;
    this.updateBadge();

    // Auto-focus input
    setTimeout(() => {
      document.getElementById("chatbox-input").focus();
    }, 100);
  }

  close() {
    const chatbox = document.getElementById("chatbox-window");
    const icon = document.getElementById("chatbot-icon");

    chatbox.classList.remove("active");
    icon.style.opacity = "1";
    this.isOpen = false;
  }

  async loadMessages() {
    // Nếu chưa có userInfo, không load
    if (!this.userInfo || !this.userInfo.email) {
      return;
    }

    try {
      const url =
        this.config.getMessagesEndpoint +
        (this.config.getMessagesEndpoint.includes("?") ? "&" : "?") +
        "email=" +
        encodeURIComponent(this.userInfo.email);

      const response = await fetch(url);
      if (response.ok) {
        const data = await response.json();
        if (data.success && data.messages) {
          this.messages = data.messages;
          this.renderMessages();
        }
      }
    } catch (error) {
      console.warn("Could not load messages:", error);
    }
  }

  renderMessages() {
    const body = document.getElementById("chatbox-body");

    if (this.messages.length === 0) {
      return; // Keep greeting
    }

    // Clear greeting
    const greeting = body.querySelector(".chatbox-greeting");
    if (greeting) {
      greeting.remove();
    }

    // Render messages
    this.messages.forEach((msg) => {
      const existingMsg = body.querySelector(`[data-msg-id="${msg.id}"]`);
      if (!existingMsg) {
        const msgEl = this.createMessageElement(msg);
        body.appendChild(msgEl);
      }
    });

    // Scroll to bottom
    this.scrollToBottom();
  }

  createMessageElement(msg) {
    const container = document.createElement("div");
    const isBot = msg.sender === "admin" || msg.sender === "system";
    const isBotClass = isBot ? "bot" : "user";

    const timestamp = new Date(msg.created_at || Date.now()).toLocaleTimeString(
      "vi-VN",
      {
        hour: "2-digit",
        minute: "2-digit",
      }
    );

    container.className = `chat-message ${isBotClass}`;
    container.setAttribute("data-msg-id", msg.id);
    container.innerHTML = `
            <div>
                <div class="chat-bubble">${this.escapeHtml(msg.message)}</div>
                <div class="chat-time">${timestamp}</div>
            </div>
        `;

    return container;
  }

  async sendMessage() {
    const input = document.getElementById("chatbox-input");
    const message = input.value.trim();

    if (!message) return;

    // Validate user info
    if (!this.userInfo) {
      const email = prompt("Vui lòng nhập email của bạn:");
      if (!email) return;

      const name = prompt("Vui lòng nhập tên của bạn:");
      if (!name) return;

      this.userInfo = { email, name };
      localStorage.setItem("chatbot-user-info", JSON.stringify(this.userInfo));
    }

    // Display user message immediately
    this.displayUserMessage(message);

    // Clear input
    input.value = "";
    input.style.height = "auto";

    // Send to server
    try {
      console.log("Sending message to:", this.config.apiEndpoint);
      console.log("Data:", {
        email: this.userInfo.email,
        name: this.userInfo.name,
        message: message,
      });

      const response = await fetch(this.config.apiEndpoint, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        credentials: "same-origin",
        body: JSON.stringify({
          email: this.userInfo.email,
          name: this.userInfo.name,
          message: message,
        }),
      });

      console.log("Response status:", response.status);
      console.log("Response headers:", response.headers);

      if (!response.ok) {
        console.error("HTTP error:", response.status);
        this.displayBotMessage(
          `❌ Lỗi ${response.status}: ${response.statusText}`
        );
        return;
      }

      const data = await response.json();
      console.log("Response data:", data);

      if (data.success) {
        // Show bot response after short delay
        setTimeout(() => {
          this.showBotResponse();
        }, 500);
      } else {
        this.displayBotMessage(
          "❌ " + (data.message || "Có lỗi xảy ra. Vui lòng thử lại.")
        );
      }
    } catch (error) {
      console.error("Error sending message:", error);
      this.displayBotMessage(
        "❌ Lỗi: " +
          error.message +
          "\n\nCó thể do:\n- Đường dẫn API sai\n- Server không chạy\n- CORS bị chặn"
      );
    }

    // Focus input again
    input.focus();
  }

  displayUserMessage(message) {
    const body = document.getElementById("chatbox-body");

    // Remove greeting if exists
    const greeting = body.querySelector(".chatbox-greeting");
    if (greeting) {
      greeting.remove();
    }

    const container = document.createElement("div");
    container.className = "chat-message user";
    container.innerHTML = `
            <div>
                <div class="chat-bubble">${this.escapeHtml(message)}</div>
                <div class="chat-time">Vừa xong</div>
            </div>
        `;

    body.appendChild(container);
    this.scrollToBottom();
  }

  displayBotMessage(message) {
    const body = document.getElementById("chatbox-body");

    const container = document.createElement("div");
    container.className = "chat-message bot";
    container.innerHTML = `
            <div>
                <div class="chat-bubble">${this.escapeHtml(message)}</div>
                <div class="chat-time">Vừa xong</div>
            </div>
        `;

    body.appendChild(container);
    this.scrollToBottom();
  }

  showBotResponse() {
    this.displayBotMessage(
      "Cảm ơn bạn đã gửi tin nhắn. Chúng tôi sẽ phản hồi sớm nhất có thể."
    );
  }

  scrollToBottom() {
    const body = document.getElementById("chatbox-body");
    setTimeout(() => {
      body.scrollTop = body.scrollHeight;
    }, 50);
  }

  updateBadge() {
    const badge = document.getElementById("chatbot-badge");
    if (this.unreadCount > 0) {
      badge.textContent = this.unreadCount;
      badge.classList.add("show");
    } else {
      badge.classList.remove("show");
    }
  }

  escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
  }
}

// Auto-initialize when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
  // Configuration - có thể tùy chỉnh
  const chatbot = new ChatbotWidget({
    apiEndpoint: "/MVC/index.php?controller=Chatbox&action=send",
    getMessagesEndpoint: "/MVC/index.php?controller=Chatbox&action=getMessages",
    autoOpen: true,
    autoOpenDelay: 3000,
    title: "Hỗ Trợ Khách Hàng",
    subtitle: "Chúng tôi sẵn sàng giúp bạn",
    placeholderText: "Nhập tin nhắn của bạn...",
  });

  // Lưu global reference để có thể control từ nơi khác
  window.chatbot = chatbot;
});
