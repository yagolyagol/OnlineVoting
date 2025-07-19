// Countdown Timer to Voting Deadline
const endDate = new Date("2025-07-04T23:59:59").getTime();
const timerElement = document.getElementById("timer");

function updateCountdown() {
    const now = new Date().getTime();
    const distance = endDate - now;

    if (distance < 0) {
        timerElement.innerHTML = "Voting closed";
        return;
    }

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    timerElement.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
}

setInterval(updateCountdown, 1000);
