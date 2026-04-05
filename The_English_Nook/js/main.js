/* =========================================
   1. THE "DATABASE" (Content for Lessons)
   ========================================= */
// This array stores all the text. We swap between 'adultContent' and 'kidsContent'
const lessons = [
    {
        id: "noun",
        title: "Nouns",
        adultContent: "A noun is a word that functions as the name of a specific object or set of objects, such as living creatures, places, actions, qualities, states of existence, or ideas.",
        kidsContent: "Nouns are NAMING words! They are names for people, places, or things. Example: Cat, Ball, Mom, London."
    },
    {
        id: "verb",
        title: "Verbs",
        adultContent: "Verbs are words that show an action (sing), occurrence (develop), or state of being (exist). Every sentence requires a verb.",
        kidsContent: "Verbs are ACTION words! They tell us what someone is doing. Example: Run, Jump, Sleep, Eat."
    },
    {
        id: "adj",
        title: "Adjectives",
        adultContent: "Adjectives are words that describe or modify other words, making your writing and speaking more specific, and a whole lot more interesting.",
        kidsContent: "Adjectives are DESCRIBING words! They tell us more about a noun. Example: 'Big' dog, 'Red' apple."
    }
];

/* =========================================
   2. NAVIGATION LOGIC
   ========================================= */
function showSection(sectionId) {
    // 1. Hide all sections
    const sections = document.querySelectorAll('.section');
    sections.forEach(section => {
        section.classList.remove('active-section');
        section.classList.add('hidden-section');
    });

    // 2. Show the clicked section
    const activeSection = document.getElementById(sectionId);
    activeSection.classList.remove('hidden-section');
    activeSection.classList.add('active-section');

    // 3. Update the Navigation Buttons (Visual highlight)
    const navButtons = document.querySelectorAll('.nav-btn');
    navButtons.forEach(btn => btn.classList.remove('active'));
    
    // Find the button that calls this function and make it active
    // (Simple hack: we look for the button text that matches the section)
    // Note: In a larger app, we might use IDs on buttons too.
}

/* =========================================
   3. AGE TOGGLE LOGIC (Kids vs Adults)
   ========================================= */
const ageToggle = document.getElementById('ageToggle');
const body = document.body;

// Listen for the "click" on the toggle switch
ageToggle.addEventListener('change', function() {
    
    // Get all the specific buttons
    const adultBtns = document.querySelectorAll('.adult-only');
    const kidsBtns = document.querySelectorAll('.kids-only');

    if (this.checked) {
        // === ENTERING KIDS MODE ===
        body.classList.remove('adult-mode');
        body.classList.add('kids-mode');
        
        // Hide Adult Buttons
        adultBtns.forEach(btn => btn.style.display = 'none');
        // Show Kid Buttons
        kidsBtns.forEach(btn => btn.style.display = 'block');
        
        // Force redirect to 'Home' so they don't get stuck on a hidden Adult page
        showSection('home'); 
        updateTextForKids();

    } else {
        // === ENTERING ADULT MODE ===
        body.classList.remove('kids-mode');
        body.classList.add('adult-mode');

        // Show Adult Buttons
        adultBtns.forEach(btn => btn.style.display = 'block');
        // Hide Kid Buttons
        kidsBtns.forEach(btn => btn.style.display = 'none');
        
        showSection('home');
        updateTextForAdults();
    }
});

// Function to update static text on the page
function updateTextForKids() {
    document.getElementById('hero-title').innerText = "English is Fun! 🎈";
    document.getElementById('hero-subtitle').innerText = "Let's play games and learn new words together!";
    document.getElementById('learn-heading').innerText = "My Bookshelf 📚";
    document.getElementById('play-heading').innerText = "Fun & Games 🎮";
}

function updateTextForAdults() {
    document.getElementById('hero-title').innerText = "Master the English Language.";
    document.getElementById('hero-subtitle').innerText = "Refine your grammar, expand your vocabulary, and explore literature.";
    document.getElementById('learn-heading').innerText = "The Library";
    document.getElementById('play-heading').innerText = "The Game Zone";
}

/* =========================================
   4. DYNAMIC CONTENT INJECTION
   ========================================= */
// This function creates the HTML cards automatically
function renderLessons() {
    const grid = document.getElementById('lesson-grid');
    grid.innerHTML = ""; // Clear existing content
    
    // Check which mode we are in
    const isKidsMode = body.classList.contains('kids-mode');

    lessons.forEach(lesson => {
        // Create a new DIV for the card
        const card = document.createElement('div');
        card.classList.add('card');
        
        // Decide which text to show
        const contentText = isKidsMode ? lesson.kidsContent : lesson.adultContent;
        
        // Fill the HTML of the card
        card.innerHTML = `
            <h3>${lesson.title}</h3>
            <p>${contentText}</p>
        `;
        
        // Add the card to the grid
        grid.appendChild(card);
    });
}

/* =========================================
   5. VOCABULARY VAULT (LocalStorage)
   ========================================= */
const addBtn = document.getElementById('add-word-btn');
const wordInput = document.getElementById('new-word');
const vocabList = document.getElementById('vocab-list');

// Load saved words when page starts
loadVocab();

addBtn.addEventListener('click', function() {
    const text = wordInput.value;
    if(text === "") return; // Don't save empty words

    // 1. Save to LocalStorage
    let savedWords = JSON.parse(localStorage.getItem('myVocab')) || [];
    savedWords.push(text);
    localStorage.setItem('myVocab', JSON.stringify(savedWords));

    // 2. Clear Input
    wordInput.value = "";

    // 3. Refresh List
    loadVocab();
});

function loadVocab() {
    vocabList.innerHTML = ""; // Clear list
    let savedWords = JSON.parse(localStorage.getItem('myVocab')) || [];
    
    savedWords.forEach(word => {
        const li = document.createElement('li');
        li.innerText = word;
        vocabList.appendChild(li);
    });
}

/* =========================================
   INITIALIZATION
   ========================================= */
// Run this once when the page loads to show the initial content
renderLessons();

/* =========================================
   SMART BUTTON LOGIC
   ========================================= */
function startJourney() {
    const isKidsMode = document.body.classList.contains('kids-mode');
    
    if (isKidsMode) {
        // Kids go to the Play Zone! 🎈
        showSection('kids-hub');
    } else {
        // Adults go to Grammar/Classes 📘
        showSection('classes'); // or 'grammar' if you prefer
    }
}

// =========================================
// CHECK USER LOGIN STATUS
// =========================================
function checkUserStatus() {
    // Ask the PHP bridge if someone is logged in
    fetch('php/status.php')
        .then(response => response.json())
        .then(data => {
            if (data.logged_in) {
                // 1. Hide Login/Register links, show Welcome message
                document.getElementById('login-link').style.display = 'none';
                document.getElementById('register-link').style.display = 'none';
                
                const welcomeMsg = document.getElementById('welcome-msg');
                welcomeMsg.style.display = 'inline-block';
                welcomeMsg.innerText = `Welcome, ${data.name}!`;

                // 2. Automatically switch themes based on age group!
                const toggle = document.getElementById('ageToggle');
                if (data.role === 'kid' && !toggle.checked) {
                    toggle.checked = true;
                    // Force the website to trigger the color change
                    toggle.dispatchEvent(new Event('change')); 
                } else if ((data.role === 'adult' || data.role === 'teacher') && toggle.checked) {
                    toggle.checked = false;
                    toggle.dispatchEvent(new Event('change'));
                }
            }
        })
        .catch(error => console.error('Error checking status:', error));
}

// Run the check immediately when the page loads
checkUserStatus();