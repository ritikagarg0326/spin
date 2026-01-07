document.addEventListener("DOMContentLoaded", () => {

    const canvas = document.getElementById("wheel");
    const ctx = canvas.getContext("2d");
    const rotator = document.getElementById("wheel-rotator");

    const nameInput = document.getElementById("name");
    const phoneInput = document.getElementById("phone");
    const spinBtn = document.getElementById("spinBtn");
    const spinInfo = document.getElementById("spinInfo");

    const overlay = document.getElementById("overlay");
    const popupOffer = document.getElementById("popup-offer");
    const closePopup = document.getElementById("closePopup");

    const labels = [
        "30% OFF",
        "15% OFF",
        "Try Next Time",
        "40% OFF",
        "25% OFF",
        "50% OFF"
    ];

    const colors = [
        "#000000",
        "#14c6cc",
        "#ff006f",
        "#14c6cc",
        "#ff006f",
        "#ffc107"
    ];

    const size = canvas.width;
    const center = size / 2;
    const radius = size / 2;
    const sliceRad = (2 * Math.PI) / labels.length;
    const sliceAngle = 360 / labels.length;

    let rotation = 0;
    let spinsUsed = 0;

    function drawWheel() {
        for (let i = 0; i < labels.length; i++) {
            const start = sliceRad * i;

            ctx.beginPath();
            ctx.moveTo(center, center);
            ctx.arc(center, center, radius, start, start + sliceRad);
            ctx.fillStyle = colors[i];
            ctx.fill();

            ctx.save();
            ctx.translate(center, center);
            ctx.rotate(start + sliceRad / 2);
            ctx.textAlign = "right";
            ctx.fillStyle = "#fff";
            ctx.font = "bold 14px Arial";
            ctx.fillText(labels[i], radius - 15, 5);
            ctx.restore();
        }
    }

    drawWheel();

    function updateUI() {
        const left = 2 - spinsUsed;
        spinInfo.innerHTML = `🎯 You have <b>${left}</b> spin${left !== 1 ? "s" : ""}`;
        spinBtn.disabled =
            left <= 0 ||
            !nameInput.value.trim() ||
            !phoneInput.value.trim();
    }

    nameInput.addEventListener("input", updateUI);
    phoneInput.addEventListener("input", updateUI);

    spinBtn.addEventListener("click", () => {
        if (spinsUsed >= 2) return;

        const index = spinsUsed === 0
            ? Math.floor(Math.random() * labels.length)
            : 5;

        const rotateDeg = 360 * 6 + (sliceAngle * index) + sliceAngle / 2;
        rotation += rotateDeg;
        rotator.style.transform = `rotate(${rotation}deg)`;

        spinsUsed++;
        updateUI();

        setTimeout(() => {
            popupOffer.innerHTML = `🎁 You won <span style="color:#f59e0b">${labels[index]}</span>`;
            overlay.classList.remove("hidden");
        }, 5200);
    });

    closePopup.onclick = () => {
        overlay.classList.add("hidden");
    };

    updateUI();
});
