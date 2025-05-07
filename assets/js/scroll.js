const animateOnScroll = () => {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-fadeInUp');
        }
      });
    }, { threshold: 0.1 });
  
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
      observer.observe(el);
    });
  };
  
  // Lancez la fonction au chargement
  document.addEventListener('DOMContentLoaded', animateOnScroll);