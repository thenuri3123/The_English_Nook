/* =========================================
   1. NAVIGATION LOGIC
   ========================================= */
function showSection(sectionId) {
    const sections = document.querySelectorAll('.section');
    if (sections.length === 0) return; // Not on the home page

    // 1. Hide all sections
    sections.forEach(section => {
        section.classList.remove('active-section');
        section.classList.add('hidden-section');
    });

    // 2. Show the clicked section
    const activeSection = document.getElementById(sectionId);
    if (activeSection) {
        activeSection.classList.remove('hidden-section');
        activeSection.classList.add('active-section');
    }

    // 3. Update the Navigation Buttons
    const navButtons = document.querySelectorAll('.nav-btn');
    navButtons.forEach(btn => btn.classList.remove('active'));
    
    // In our manual SPA, we'll need to know which button was clicked
    // This is handled by the inline onclick for now.
}

/* =========================================
   2. AGE TOGGLE LOGIC (Kids vs Adults)
   ========================================= */
const ageToggle = document.getElementById('ageToggle');
const body = document.body;

if (ageToggle) {
    // Check initial state from localStorage
    const savedMode = localStorage.getItem('nook_mode');
    if (savedMode === 'kids') {
        ageToggle.checked = true;
        applyMode('kids', false);
    }

    ageToggle.addEventListener('change', function() {
        const mode = this.checked ? 'kids' : 'adult';
        applyMode(mode, true);
        localStorage.setItem('nook_mode', mode);
    });
}

function applyMode(mode, shouldRedirect) {
    const adultBtns = document.querySelectorAll('.adult-only');
    const kidsBtns = document.querySelectorAll('.kids-only');

    if (mode === 'kids') {
        body.classList.remove('adult-mode');
        body.classList.add('kids-mode');
        adultBtns.forEach(btn => btn.style.display = 'none');
        kidsBtns.forEach(btn => btn.style.display = 'block');
        if (shouldRedirect) showSection('home');
        updateTextForKids();
    } else {
        body.classList.remove('kids-mode');
        body.classList.add('adult-mode');
        adultBtns.forEach(btn => btn.style.display = 'block');
        kidsBtns.forEach(btn => btn.style.display = 'none');
        if (shouldRedirect) showSection('home');
        updateTextForAdults();
    }
    
    // Re-render lessons if we are on the lessons page
    if (document.getElementById('lesson-grid')) {
        renderLessons();
    }
}

function updateTextForKids() {
    const title = document.getElementById('hero-title');
    const subtitle = document.getElementById('hero-subtitle');
    const learn = document.getElementById('learn-heading');
    const play = document.getElementById('play-heading');

    if (title) title.innerText = "English is Fun! 🎈";
    if (subtitle) subtitle.innerText = "Let's play games and learn new words together!";
    if (learn) learn.innerText = "My Bookshelf 📚";
    if (play) play.innerText = "Fun & Games 🎮";
}

function updateTextForAdults() {
    const title = document.getElementById('hero-title');
    const subtitle = document.getElementById('hero-subtitle');
    const learn = document.getElementById('learn-heading');
    const play = document.getElementById('play-heading');

    if (title) title.innerText = "Master the English Language.";
    if (subtitle) subtitle.innerText = "Refine your grammar, expand your vocabulary, and explore literature.";
    if (learn) learn.innerText = "The Library";
    if (play) play.innerText = "The Game Zone";
}

/* =========================================
   3. DYNAMIC CONTENT INJECTION
   ========================================= */
function renderLessons() {
    const grid = document.getElementById('lesson-grid');
    if (!grid) return;
    
    grid.innerHTML = "";
    const isKidsMode = body.classList.contains('kids-mode');

    // 'lessons' array comes from js/content.js
    if (typeof lessons !== 'undefined') {
        lessons.forEach(lesson => {
            const card = document.createElement('div');
            card.classList.add('card');
            const contentText = isKidsMode ? lesson.kidsContent : lesson.adultContent;
            card.innerHTML = `<h3>${lesson.title}</h3><p>${contentText}</p>`;
            grid.appendChild(card);
        });
    }
}

/* =========================================
   4. VOCABULARY VAULT
   ========================================= */
const addWordBtn = document.getElementById('add-word-btn');
const wordInput = document.getElementById('new-word');
const vocabList = document.getElementById('vocab-list');

if (addWordBtn && wordInput && vocabList) {
    loadVocab();
    addWordBtn.addEventListener('click', function() {
        const text = wordInput.value.trim();
        if(text === "") return;
        let savedWords = JSON.parse(localStorage.getItem('myVocab')) || [];
        savedWords.push(text);
        localStorage.setItem('myVocab', JSON.stringify(savedWords));
        wordInput.value = "";
        loadVocab();
    });
}

function loadVocab() {
    if (!vocabList) return;
    vocabList.innerHTML = "";
    let savedWords = JSON.parse(localStorage.getItem('myVocab')) || [];
    savedWords.forEach(word => {
        const li = document.createElement('li');
        li.innerText = word;
        vocabList.appendChild(li);
    });
}

/* =========================================
   5. SMART BUTTON LOGIC
   ========================================= */
function startJourney() {
    const isKidsMode = document.body.classList.contains('kids-mode');
    if (isKidsMode) {
        showSection('kids-hub');
    } else {
        showSection('classes');
    }
}

/* =========================================
   6. CHECK USER LOGIN STATUS
   ========================================= */
function checkUserStatus() {
    const statusUrl = (typeof SITE_ROOT !== 'undefined' ? SITE_ROOT : '') + 'php/status.php';
    fetch(statusUrl)
        .then(response => response.json())
        .then(data => {
            if (data.logged_in) {
                const loginLink = document.getElementById('login-link');
                const registerLink = document.getElementById('register-link');
                const welcomeMsg = document.getElementById('welcome-msg');
                
                if (loginLink) loginLink.style.display = 'none';
                if (registerLink) registerLink.style.display = 'none';
                if (welcomeMsg) {
                    welcomeMsg.style.display = 'inline-block';
                    welcomeMsg.innerText = `Welcome, ${data.name}!`;
                }

                const toggle = document.getElementById('ageToggle');
                if (toggle) {
                    if (data.role === 'kid' && !toggle.checked) {
                        toggle.checked = true;
                        applyMode('kids', false);
                    } else if ((data.role === 'adult' || data.role === 'teacher') && toggle.checked) {
                        toggle.checked = false;
                        applyMode('adult', false);
                    }
                }
            }
        })
        .catch(error => console.error('Error checking status:', error));
}

// Initializing
document.addEventListener('DOMContentLoaded', () => {
    renderLessons();
    checkUserStatus();
});