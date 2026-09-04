// ======================================================
// DIGITAL HISTORY - MAIN JAVASCRIPT
// ======================================================

(function() {
    'use strict';

    // ===== SITE_URL detection =====
    const SITE_URL = (function() {
        const path = window.location.pathname;
        const base = path.replace(/\/[^\/]*$/, '/');
        return window.location.origin + base;
    })();

    // ======================================================
    // 1. DOM READY - Main Initialization
    // ======================================================
    document.addEventListener('DOMContentLoaded', function() {
        
        // ---- Loading Screen ----
        initLoadingScreen();
        
        // ---- Header Effects ----
        initHeaderScroll();
        
        // ---- Mobile Menu ----
        initMobileMenu();
        
        // ---- Search ----
        initSearch();
        
        // ---- Language Switcher ----
        initLanguageSwitcher();
        
        // ---- Animations ----
        initScrollAnimations();
        
        // ---- Smooth Scroll ----
        initSmoothScroll();
        
        // ---- Hero Scroll Indicator ----
        initHeroScroll();
        
        // ---- Counter Animation ----
        initCounters();
        
        // ---- Particle Background ----
        initParticles();
        
        // ---- Glitch Effect ----
        initGlitchEffect();
        
        // ---- Search Autocomplete ----
        initSearchAutocomplete();
        
        // ---- Easter Eggs ----
        initEasterEggs();
        
        // ---- Console Greeting ----
        consoleGreeting();
    });

    // ======================================================
    // 2. LOADING SCREEN
    // ======================================================
    function initLoadingScreen() {
        const loadingScreen = document.getElementById('loading-screen');
        const loaderProgress = document.getElementById('loaderProgress');
        const loaderStatus = document.getElementById('loaderStatus');
        
        if (!loadingScreen) return;
        
        const loadingSteps = [
            { progress: 20, status: 'Loading Timeline...' },
            { progress: 40, status: 'Loading Archive...' },
            { progress: 60, status: 'Loading Network...' },
            { progress: 80, status: 'Loading Future...' },
            { progress: 100, status: 'Ready!' }
        ];
        
        let stepIndex = 0;
        const loadingInterval = setInterval(() => {
            if (stepIndex < loadingSteps.length) {
                const step = loadingSteps[stepIndex];
                if (loaderProgress) loaderProgress.style.width = step.progress + '%';
                if (loaderStatus) loaderStatus.textContent = step.status;
                stepIndex++;
            } else {
                clearInterval(loadingInterval);
                setTimeout(() => {
                    loadingScreen.classList.add('hidden');
                }, 500);
            }
        }, 300);
    }

    // ======================================================
    // 3. HEADER SCROLL EFFECT
    // ======================================================
    function initHeaderScroll() {
        const header = document.querySelector('.site-header');
        if (!header) return;
        
        let lastScroll = 0;
        
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
            
            if (currentScroll > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            
            lastScroll = currentScroll;
        }, { passive: true });
    }

    // ======================================================
    // 4. MOBILE MENU
    // ======================================================
    function initMobileMenu() {
        const mobileToggle = document.getElementById('mobileMenuToggle');
        const mobileNav = document.getElementById('mobileNav');
        const mobileNavClose = document.getElementById('mobileNavClose');
        const mobileOverlay = document.getElementById('mobileNavOverlay');
        
        if (!mobileToggle || !mobileNav) return;
        
        function toggleMobileMenu(open) {
            if (open === undefined) {
                mobileNav.classList.toggle('open');
                if (mobileOverlay) mobileOverlay.classList.toggle('active');
            } else if (open) {
                mobileNav.classList.add('open');
                if (mobileOverlay) mobileOverlay.classList.add('active');
            } else {
                mobileNav.classList.remove('open');
                if (mobileOverlay) mobileOverlay.classList.remove('active');
            }
            
            // Prevent body scroll when menu is open
            document.body.style.overflow = mobileNav.classList.contains('open') ? 'hidden' : '';
        }
        
        mobileToggle.addEventListener('click', function() {
            toggleMobileMenu();
        });
        
        if (mobileNavClose) {
            mobileNavClose.addEventListener('click', function() {
                toggleMobileMenu(false);
            });
        }
        
        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', function() {
                toggleMobileMenu(false);
            });
        }
        
        // Close mobile menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileNav.classList.contains('open')) {
                toggleMobileMenu(false);
            }
        });
    }

    // ======================================================
    // 5. SEARCH
    // ======================================================
    function initSearch() {
        const searchToggle = document.getElementById('searchToggle');
        const searchBar = document.getElementById('searchBar');
        const searchClose = document.getElementById('searchClose');
        const searchInput = searchBar?.querySelector('input');
        
        if (!searchToggle || !searchBar) return;
        
        function toggleSearch(open) {
            if (open === undefined) {
                searchBar.classList.toggle('active');
            } else if (open) {
                searchBar.classList.add('active');
            } else {
                searchBar.classList.remove('active');
            }
            
            if (searchBar.classList.contains('active')) {
                setTimeout(function() {
                    if (searchInput) searchInput.focus();
                }, 300);
            }
        }
        
        searchToggle.addEventListener('click', function() {
            toggleSearch();
        });
        
        if (searchClose) {
            searchClose.addEventListener('click', function() {
                toggleSearch(false);
            });
        }
        
        // Close search with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && searchBar.classList.contains('active')) {
                toggleSearch(false);
            }
        });
    }

    // ======================================================
    // 6. LANGUAGE SWITCHER
    // ======================================================
    function initLanguageSwitcher() {
        const languageLinks = document.querySelectorAll('.lang-dropdown a[data-lang]');
        
        languageLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const lang = this.dataset.lang;
                
                // Show loading indicator
                const currentLang = document.querySelector('.lang-current');
                if (currentLang) {
                    currentLang.innerHTML = '⏳ Loading...';
                }
                
                fetch(SITE_URL + 'api/set-language.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'lang=' + encodeURIComponent(lang)
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        // Fallback: redirect with query param
                        window.location.href = window.location.pathname + '?lang=' + lang;
                    }
                })
                .catch(function() {
                    // Fallback: redirect with query param
                    window.location.href = window.location.pathname + '?lang=' + lang;
                });
            });
        });
    }

    // ======================================================
    // 7. SCROLL ANIMATIONS (Intersection Observer)
    // ======================================================
    function initScrollAnimations() {
        const animateElements = document.querySelectorAll('.animate-on-scroll');
        
        if (animateElements.length === 0 || !('IntersectionObserver' in window)) return;
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        animateElements.forEach(function(el) {
            observer.observe(el);
        });
    }

    // ======================================================
    // 8. SMOOTH SCROLL FOR ANCHOR LINKS
    // ======================================================
    function initSmoothScroll() {
        const anchorLinks = document.querySelectorAll('a[href^="#"]:not([href="#"])');
        
        anchorLinks.forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const target = document.querySelector(targetId);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    
                    // Update URL hash without scrolling
                    if (history.pushState) {
                        history.pushState(null, null, targetId);
                    }
                }
            });
        });
    }

    // ======================================================
    // 9. HERO SCROLL INDICATOR
    // ======================================================
    function initHeroScroll() {
        const scrollIndicator = document.querySelector('.hero-scroll');
        if (!scrollIndicator) return;
        
        scrollIndicator.addEventListener('click', function() {
            const nextSection = document.querySelector('.section') || document.querySelector('.timeline-container');
            if (nextSection) {
                nextSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

    // ======================================================
    // 10. COUNTER ANIMATION
    // ======================================================
    function initCounters() {
        const counters = document.querySelectorAll('.counter');
        
        if (counters.length === 0 || !('IntersectionObserver' in window)) return;
        
        const counterObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = parseInt(counter.dataset.target, 10) || 0;
                    const duration = parseInt(counter.dataset.duration, 10) || 2000;
                    const startTime = performance.now();
                    
                    function updateCounter(currentTime) {
                        const elapsed = currentTime - startTime;
                        const progress = Math.min(elapsed / duration, 1);
                        const eased = 1 - Math.pow(1 - progress, 3);
                        const current = Math.floor(eased * target);
                        
                        counter.textContent = current.toLocaleString();
                        
                        if (progress < 1) {
                            requestAnimationFrame(updateCounter);
                        } else {
                            counter.textContent = target.toLocaleString();
                        }
                    }
                    
                    requestAnimationFrame(updateCounter);
                    counterObserver.unobserve(counter);
                }
            });
        }, { threshold: 0.5 });
        
        counters.forEach(function(counter) {
            counterObserver.observe(counter);
        });
    }

    // ======================================================
    // 11. PARTICLE BACKGROUND
    // ======================================================
    function initParticles() {
        const heroBg = document.querySelector('.hero-bg');
        if (!heroBg || typeof CanvasRenderingContext2D === 'undefined') return;
        
        const canvas = document.createElement('canvas');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        canvas.style.cssText = 'display: block; width: 100%; height: 100%;';
        heroBg.appendChild(canvas);
        
        const ctx = canvas.getContext('2d');
        let particles = [];
        const particleCount = 80;
        let animationId = null;
        
        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2 + 0.5;
                this.speedX = (Math.random() - 0.5) * 0.5;
                this.speedY = (Math.random() - 0.5) * 0.5;
                this.opacity = Math.random() * 0.5 + 0.2;
            }
            
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                
                if (this.x < 0) this.x = canvas.width;
                if (this.x > canvas.width) this.x = 0;
                if (this.y < 0) this.y = canvas.height;
                if (this.y > canvas.height) this.y = 0;
            }
            
            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(0, 212, 255, ' + this.opacity + ')';
                ctx.fill();
            }
        }
        
        function initParticlesArray() {
            particles = [];
            for (let i = 0; i < particleCount; i++) {
                particles.push(new Particle());
            }
        }
        
        function drawConnections() {
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    
                    if (distance < 150) {
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        const opacity = 0.1 * (1 - distance / 150);
                        ctx.strokeStyle = 'rgba(0, 212, 255, ' + opacity + ')';
                        ctx.lineWidth = 0.5;
                        ctx.stroke();
                    }
                }
            }
        }
        
        function animateParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            particles.forEach(function(particle) {
                particle.update();
                particle.draw();
            });
            
            drawConnections();
            animationId = requestAnimationFrame(animateParticles);
        }
        
        function handleResize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            initParticlesArray();
        }
        
        initParticlesArray();
        animateParticles();
        
        window.addEventListener('resize', handleResize);
        
        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (animationId) {
                cancelAnimationFrame(animationId);
            }
        });
    }

    // ======================================================
    // 12. GLITCH EFFECT
    // ======================================================
    function initGlitchEffect() {
        const glitchElements = document.querySelectorAll('.glitch');
        
        glitchElements.forEach(function(el) {
            // Set data-text attribute for pseudo-elements
            const text = el.textContent.trim();
            if (text && !el.dataset.text) {
                el.dataset.text = text;
            }
            
            let glitchInterval = setInterval(function() {
                if (Math.random() < 0.02) {
                    const x = (Math.random() - 0.5) * 4;
                    const y = (Math.random() - 0.5) * 4;
                    el.style.transform = 'translate(' + x + 'px, ' + y + 'px)';
                    el.style.opacity = '0.9';
                    
                    setTimeout(function() {
                        el.style.transform = 'none';
                        el.style.opacity = '1';
                    }, 100);
                }
            }, 100);
            
            // Store interval for cleanup
            el._glitchInterval = glitchInterval;
        });
    }

    // ======================================================
    // 13. SEARCH AUTOCOMPLETE
    // ======================================================
    function initSearchAutocomplete() {
        const searchInput = document.querySelector('.search-container input[name="q"]');
        if (!searchInput) return;
        
        const searchContainer = searchInput.closest('form');
        if (!searchContainer) return;
        
        const searchResults = document.createElement('div');
        searchResults.className = 'search-autocomplete';
        searchResults.style.cssText = 'position:absolute;top:100%;left:0;right:0;background:rgba(13,17,23,0.98);border:1px solid rgba(255,255,255,0.08);border-radius:8px;margin-top:4px;max-height:300px;overflow-y:auto;display:none;z-index:1000;backdrop-filter:blur(20px);';
        
        searchContainer.style.position = 'relative';
        searchContainer.appendChild(searchResults);
        
        let searchTimeout = null;
        
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }
            
            searchTimeout = setTimeout(function() {
                fetch(SITE_URL + 'api/search-autocomplete.php?q=' + encodeURIComponent(query))
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        if (data.length === 0) {
                            searchResults.innerHTML = '<div style="padding:0.8rem 1rem;color:#8892b0;text-align:center;">No results found for "' + query + '"</div>';
                            searchResults.style.display = 'block';
                            return;
                        }
                        
                        var typeIcons = {
                            'event': '📅',
                            'person': '👤',
                            'technology': '🔧',
                            'language': '💻'
                        };
                        
                        var html = '';
                        data.forEach(function(item) {
                            var icon = typeIcons[item.type] || '📄';
                            html += '<a href="' + item.url + '" style="display:flex;align-items:center;gap:0.8rem;padding:0.6rem 1rem;color:#ccd6f6;text-decoration:none;transition:all 0.3s;border-bottom:1px solid rgba(255,255,255,0.03);">';
                            html += '<span style="font-size:1.2rem;">' + icon + '</span>';
                            html += '<div><div style="font-weight:500;">' + item.title + '</div>';
                            html += '<div style="font-size:0.7rem;color:#8892b0;text-transform:capitalize;">' + item.type + '</div></div>';
                            html += '</a>';
                        });
                        
                        searchResults.innerHTML = html;
                        searchResults.style.display = 'block';
                        
                        // Add hover effects
                        searchResults.querySelectorAll('a').forEach(function(link) {
                            link.addEventListener('mouseenter', function() {
                                this.style.background = 'rgba(255,255,255,0.05)';
                            });
                            link.addEventListener('mouseleave', function() {
                                this.style.background = 'transparent';
                            });
                        });
                    })
                    .catch(function() {
                        searchResults.style.display = 'none';
                    });
            }, 300);
        });
        
        // Close autocomplete on blur
        searchInput.addEventListener('blur', function() {
            setTimeout(function() {
                searchResults.style.display = 'none';
            }, 200);
        });
        
        // Keyboard navigation for autocomplete
        searchInput.addEventListener('keydown', function(e) {
            var items = searchResults.querySelectorAll('a');
            if (items.length === 0) return;
            
            var currentIndex = Array.from(items).findIndex(function(item) {
                return item.classList.contains('active');
            });
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                currentIndex = Math.min(currentIndex + 1, items.length - 1);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                currentIndex = Math.max(currentIndex - 1, 0);
            } else if (e.key === 'Enter' && currentIndex >= 0) {
                e.preventDefault();
                items[currentIndex].click();
                return;
            } else {
                return;
            }
            
            items.forEach(function(item, index) {
                if (index === currentIndex) {
                    item.classList.add('active');
                    item.style.background = 'rgba(0, 212, 255, 0.1)';
                } else {
                    item.classList.remove('active');
                    item.style.background = 'transparent';
                }
            });
        });
    }

    // ======================================================
    // 14. EASTER EGGS
    // ======================================================
    function initEasterEggs() {
        // ---- Konami Code ----
        var konamiCode = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'b', 'a'];
        var konamiIndex = 0;
        
        document.addEventListener('keydown', function(e) {
            if (e.key === konamiCode[konamiIndex] || e.key === konamiCode[konamiIndex].toUpperCase()) {
                konamiIndex++;
                if (konamiIndex === konamiCode.length) {
                    konamiIndex = 0;
                    // Trigger easter egg
                    document.body.style.animation = 'matrixRain 0.5s ease';
                    setTimeout(function() {
                        document.body.style.animation = '';
                    }, 500);
                    console.log('%c🎮 KONAMI CODE ACTIVATED!', 'font-size: 20px; color: #00f0ff;');
                    
                    // Show a fun message
                    var modal = document.getElementById('gameModal') || document.createElement('div');
                    if (!document.getElementById('gameModal')) {
                        modal.id = 'gameModal';
                        modal.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:#0d1117;border:1px solid rgba(255,255,255,0.1);border-radius:16px;padding:2rem;z-index:9999;text-align:center;max-width:400px;';
                        document.body.appendChild(modal);
                    }
                    modal.style.display = 'block';
                    modal.innerHTML = '<div style="font-size:3rem;margin-bottom:0.5rem;">🎮</div><h2 style="color:#00d4ff;">KONAMI CODE!</h2><p style="color:#8892b0;">You found the secret easter egg! 🎉</p><button onclick="this.parentElement.style.display=\'none\'" style="margin-top:1rem;padding:0.5rem 1.5rem;background:linear-gradient(135deg,#00d4ff,#7b2ffc);border:none;border-radius:8px;color:#fff;cursor:pointer;font-weight:600;">Close</button>';
                    
                    setTimeout(function() {
                        if (modal) modal.style.display = 'none';
                    }, 5000);
                }
            } else {
                konamiIndex = 0;
            }
        });
        
        // ---- Matrix Rain CSS ----
        var matrixStyle = document.createElement('style');
        matrixStyle.textContent = '@keyframes matrixRain {0% { background: rgba(0, 255, 0, 0.1); } 50% { background: rgba(0, 255, 0, 0.3); } 100% { background: rgba(0, 255, 0, 0.1); } }';
        document.head.appendChild(matrixStyle);
        
        // ---- Sudo Easter Egg ----
        document.addEventListener('keydown', function(e) {
            if (e.key === 's' && e.ctrlKey) {
                console.log('%c🔒 sudo: command not found', 'font-size: 14px; color: #ff6b6b;');
                e.preventDefault();
            }
        });
        
        // ---- Matrix Easter Egg ----
        var matrixCount = 0;
        document.addEventListener('keydown', function(e) {
            if (e.key === 'm' && e.ctrlKey) {
                matrixCount++;
                if (matrixCount >= 3) {
                    matrixCount = 0;
                    console.log('%c🔮 THE MATRIX HAS YOU...', 'font-size: 18px; color: #00ff88;');
                    document.body.style.background = 'rgba(0, 255, 0, 0.05)';
                    setTimeout(function() {
                        document.body.style.background = '';
                    }, 2000);
                }
            }
        });
    }

    // ======================================================
    // 15. CONSOLE GREETING
    // ======================================================
    function consoleGreeting() {
        console.log('%c🌐 DIGITAL HISTORY', 'font-size: 28px; font-weight: bold; color: #00d4ff; text-shadow: 0 0 20px rgba(0,212,255,0.3);');
        console.log('%cThe interactive museum of computing, programming, the internet and the future.', 'font-size: 14px; color: #8892b0;');
        console.log('%c🔍 Explore the timeline at ' + SITE_URL + 'timeline.php', 'font-size: 12px; color: #7b2ffc;');
        console.log('%c🎮 Press ↑↑↓↓←→←→BA for a secret!', 'font-size: 12px; color: #ffa500;');
        console.log('%c💻 Ctrl+M three times for another surprise!', 'font-size: 12px; color: #ffa500;');
    }

})();

// ======================================================
// 16. FALLBACKS FOR OLDER BROWSERS
// ======================================================

// Polyfill for older browsers (IntersectionObserver)
if (!('IntersectionObserver' in window)) {
    window.IntersectionObserver = function(callback, options) {
        this.observe = function(element) {
            // Simple fallback - just trigger immediately
            setTimeout(function() {
                callback([{ target: element, isIntersecting: true }]);
            }, 100);
        };
        this.unobserve = function() {};
        this.disconnect = function() {};
    };
}

// Polyfill for older browsers (requestAnimationFrame)
if (!('requestAnimationFrame' in window)) {
    window.requestAnimationFrame = function(callback) {
        return window.setTimeout(callback, 1000 / 60);
    };
    window.cancelAnimationFrame = function(id) {
        window.clearTimeout(id);
    };
}

// Polyfill for older browsers (Element.matches)
if (!Element.prototype.matches) {
    Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
}

// Polyfill for older browsers (Element.closest)
if (!Element.prototype.closest) {
    Element.prototype.closest = function(s) {
        var el = this;
        do {
            if (el.matches(s)) return el;
            el = el.parentElement || el.parentNode;
        } while (el !== null && el.nodeType === 1);
        return null;
    };
}

// ======================================================
// 17. EXPOSE FUNCTIONS TO GLOBAL SCOPE
// ======================================================

// Make SITE_URL available globally
window.SITE_URL = SITE_URL;

// Make some functions available globally for inline onclick
window.closeGame = function() {
    var modal = document.getElementById('gameModal');
    if (modal) modal.style.display = 'none';
};

console.log('✅ Digital History initialized successfully!');