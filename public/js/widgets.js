// Book Flip Effect
function initBookFlip() {
  const books = document.querySelectorAll(".product-card");
  books.forEach((book) => {
    book.addEventListener("mousemove", (e) => {
      const { left, top, width, height } = book.getBoundingClientRect();
      const x = (e.clientX - left) / width;
      const y = (e.clientY - top) / height;

      const rotateY = (x - 0.5) * 30;
      const rotateX = (y - 0.5) * -30;

      book.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.05,1.05,1.05)`;
      book.style.transition = "none";
    });

    book.addEventListener("mouseleave", () => {
      book.style.transform =
        "perspective(1000px) rotateX(0) rotateY(0) scale3d(1,1,1)";
      book.style.transition = "all 0.3s ease";
    });
  });
}

// Book Quote Generator
const bookQuotes = [
  { text: "Một cuốn sách hay là một người bạn quý", author: "Ngạn ngữ" },
  {
    text: "Đọc sách là cuộc đối thoại với những bộ óc tinh túy nhất",
    author: "René Descartes",
  },
  { text: "Sách là cửa sổ nhìn ra thế giới", author: "Unknown" },
  {
    text: "Đọc sách cho ta kiến thức, kiến thức cho ta sức mạnh",
    author: "Unknown",
  },
];

function updateQuote() {
  const quoteElement = document.querySelector(".book-quote");
  if (quoteElement) {
    const randomQuote =
      bookQuotes[Math.floor(Math.random() * bookQuotes.length)];
    quoteElement.style.opacity = "0";

    setTimeout(() => {
      quoteElement.innerHTML = `
                <p class="quote-text">"${randomQuote.text}"</p>
                <p class="quote-author">- ${randomQuote.author}</p>
            `;
      quoteElement.style.opacity = "1";
    }, 500);
  }
}

// Reading Progress Widget
function initReadingProgress() {
  const progress = document.createElement("div");
  progress.className = "reading-progress";
  document.body.appendChild(progress);

  window.addEventListener("scroll", () => {
    const windowHeight =
      document.documentElement.scrollHeight -
      document.documentElement.clientHeight;
    const currentProgress = (window.scrollY / windowHeight) * 100;
    progress.style.width = currentProgress + "%";
  });
}

// Category Counter Animation
function initCategoryCounters() {
  const counters = document.querySelectorAll(".category-counter");

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const target = parseInt(entry.target.getAttribute("data-target"));
          const counter = entry.target;
          let count = 0;

          const updateCount = () => {
            const increment = target / 50;
            if (count < target) {
              count += increment;
              counter.innerText = Math.ceil(count);
              requestAnimationFrame(updateCount);
            } else {
              counter.innerText = target;
            }
          };

          updateCount();
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.5 }
  );

  counters.forEach((counter) => observer.observe(counter));
}

// Floating Books Animation
function initFloatingBooks() {
  const floatingContainer = document.querySelector(".floating-books");
  if (floatingContainer) {
    const bookIcons = ["📚", "📖", "📕", "📗", "📘", "📙"];

    bookIcons.forEach((icon, index) => {
      const book = document.createElement("span");
      book.className = "floating-book";
      book.textContent = icon;
      book.style.left = Math.random() * 100 + "%";
      book.style.animationDelay = index * 0.5 + "s";
      floatingContainer.appendChild(book);
    });
  }
}

// Initialize all widgets
document.addEventListener("DOMContentLoaded", function () {
  initBookFlip();
  initReadingProgress();
  initCategoryCounters();
  initFloatingBooks();

  // Initialize quote rotation
  updateQuote();
  setInterval(updateQuote, 5000);
});
