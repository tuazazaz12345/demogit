// Sakura petals animation with enhanced visuals
function createSakura() {
    const sakura = document.createElement('div');
    sakura.className = 'sakura';
    
    // Create a more natural petal shape
    const size = Math.random() * 15 + 10; // 10-25px
    sakura.style.width = `${size}px`;
    sakura.style.height = `${size}px`;
    sakura.style.background = `radial-gradient(circle at 30% 30%, 
        rgba(255, ${Math.random() * 20 + 215}, ${Math.random() * 20 + 235}, ${Math.random() * 0.3 + 0.7}),
        rgba(255, ${Math.random() * 30 + 200}, ${Math.random() * 30 + 220}, ${Math.random() * 0.2 + 0.5})
    )`;
    sakura.style.borderRadius = '100% 0% 100% 0% / 100% 100% 0% 0%';
    sakura.style.position = 'fixed';
    sakura.style.boxShadow = '0 0 10px rgba(255, 215, 230, 0.3)';
    sakura.style.filter = 'blur(0.2px)';
    sakura.style.pointerEvents = 'none';

  // Random starting position
  sakura.style.left = Math.random() * 100 + "%";
  sakura.style.top = "-10%";

  // Random animation duration
  const animationDuration = Math.random() * 3 + 2; // 2-5 seconds
  sakura.style.animation = `fall ${animationDuration}s linear forwards`;

  document.body.appendChild(sakura);

    // Create natural swaying animation
    const rotationSpeed = Math.random() * 3 + 2;
    const horizontalDistance = Math.random() * 150 - 75; // -75px to 75px
    const fallDuration = Math.random() * 5 + 5; // 5-10 seconds
    
    const animation = sakura.animate([
        {
            transform: `translate(0px, -10px) rotate(0deg)`,
            opacity: 0
        },
        {
            transform: `translate(${horizontalDistance * 0.33}px, ${window.innerHeight * 0.33}px) 
                       rotate(${120 * Math.random() - 60}deg)`,
            opacity: 0.8
        },
        {
            transform: `translate(${horizontalDistance * 0.66}px, ${window.innerHeight * 0.66}px) 
                       rotate(${120 * Math.random() - 60}deg)`,
            opacity: 0.4
        },
        {
            transform: `translate(${horizontalDistance}px, ${window.innerHeight + 50}px) 
                       rotate(${120 * Math.random() - 60}deg)`,
            opacity: 0
        }
    ], {
        duration: fallDuration * 1000,
        easing: 'cubic-bezier(0.37, 0, 0.63, 1)',
        iterations: 1
    });

    // Subtle floating effect
    const sway = sakura.animate([
        { transform: 'rotate(-3deg)' },
        { transform: 'rotate(3deg)' }
    ], {
        duration: rotationSpeed * 1000,
        easing: 'ease-in-out',
        iterations: Infinity,
        direction: 'alternate'
    });

    // Remove the petal when animation completes
    animation.onfinish = () => sakura.remove();
}

// Create petals with varying frequency
function startSakuraAnimation() {
    const createPetal = () => {
        createSakura();
        // Randomize interval between petals
        setTimeout(createPetal, Math.random() * 300 + 100);
    };
    
    // Initial burst of petals
    for (let i = 0; i < 15; i++) {
        setTimeout(createSakura, Math.random() * 1500);
    }
    
    // Continue creating petals
    createPetal();
}

// Start animation when page loads
document.addEventListener('DOMContentLoaded', startSakuraAnimation);

// Adjust petal creation based on screen size
window.addEventListener('resize', () => {
    // Reduce petals on smaller screens
    const minInterval = window.innerWidth < 768 ? 500 : 300;
    const maxInterval = window.innerWidth < 768 ? 800 : 500;
});
