/* =========================================
   THE TALKING ZOO LOGIC
   ========================================= */

const animals = [
    { name: "Lion", emoji: "🦁", sound: "Roar!" },
    { name: "Elephant", emoji: "🐘", sound: "Trumpet!" },
    { name: "Dog", emoji: "🐶", sound: "Woof woof!" },
    { name: "Cat", emoji: "🐱", sound: "Meow!" },
    { name: "Monkey", emoji: "🐵", sound: "Ooh ooh ah ah!" },
    { name: "Cow", emoji: "🐮", sound: "Moooo!" }
];

function initZoo() {
    const zooGrid = document.querySelector('.zoo-grid');
    if(!zooGrid) return; // Stop if page doesn't have the zoo section

    zooGrid.innerHTML = ''; // Clear previous

    animals.forEach(animal => {
        const card = document.createElement('div');
        card.classList.add('animal-card');
        
        card.innerHTML = `
            <span class="animal-emoji">${animal.emoji}</span>
            <span class="animal-name">${animal.name}</span>
        `;

        // ADD CLICK EVENT TO SPEAK
        card.addEventListener('click', () => {
            speakWord(animal.name);
            
            // Add a little wobble animation
            card.animate([
                { transform: 'rotate(0deg)' },
                { transform: 'rotate(-10deg)' },
                { transform: 'rotate(10deg)' },
                { transform: 'rotate(0deg)' }
            ], { duration: 500 });
        });

        zooGrid.appendChild(card);
    });
}

// TEXT TO SPEECH FUNCTION (Browser Built-in)
function speakWord(word) {
    const synth = window.speechSynthesis;
    const utterance = new SpeechSynthesisUtterance(word);
    
    // Optional: Make the voice higher pitch for kids
    utterance.pitch = 1.2; 
    utterance.rate = 0.9;  // Slightly slower
    
    synth.speak(utterance);
}

// Call this immediately to build the zoo
initZoo();

/* =========================================
   THE RAINBOW ROOM LOGIC
   ========================================= */
const colors = [
    { name: "Red", hex: "#ff5252" },
    { name: "Blue", hex: "#448aff" },
    { name: "Green", hex: "#69f0ae" },
    { name: "Yellow", hex: "#ffd740" },
    { name: "Purple", hex: "#e040fb" },
    { name: "Orange", hex: "#ffab40" },
    { name: "Pink", hex: "#ff80ab" },
    { name: "Black", hex: "#333333" }
];

function initColors() {
    const colorGrid = document.querySelector('.color-grid');
    if(!colorGrid) return;

    colorGrid.innerHTML = ''; 

    colors.forEach(color => {
        const blob = document.createElement('div');
        blob.classList.add('paint-blob');
        blob.style.backgroundColor = color.hex;
        blob.innerText = color.name;
        
        blob.addEventListener('click', () => {
            // 1. Speak the color
            speakWord(color.name);
            
            // 2. Flash the background color of the body for fun!
            document.body.style.backgroundColor = color.hex;
            
            // 3. Reset background after 1 second
            setTimeout(() => {
                document.body.style.backgroundColor = ''; // Reverts to CSS variable
            }, 1000);
        });

        colorGrid.appendChild(blob);
    });
}

// Call it to build the blobs
initColors();

/* =========================================
   NAUGHTY QUIZ LOGIC
   ========================================= */

function moveButton() {
    const btn = document.getElementById('runaway-btn');
    
    // Enable floating mode only when moving
    btn.style.position = 'absolute';

    // Get the size of the play area
    const container = document.querySelector('.quiz-container');
    const maxX = container.offsetWidth - 150; // Keep inside width
    const maxY = container.offsetHeight - 50; // Keep inside height

    // Generate random X and Y positions
    const randomX = Math.floor(Math.random() * maxX);
    const randomY = Math.floor(Math.random() * maxY);

    // Move the button!
    btn.style.left = randomX + 'px';
    btn.style.top = randomY + 'px';
}

function showSuccess() {
    // Hide the buttons
    document.querySelector('.cute-buttons').style.display = 'none';
    
    // Show the cute GIF
    const msg = document.getElementById('success-msg');
    msg.style.display = 'block';
    
    // Play a "Yay" sound (Optional)
    speakWord("Yay! You are smart!");
}