function spin_wheel_shortcode() {
    ob_start();
    ?>

    <!-- ================= MAIN SECTION ================= -->
    <div class="spin-bg">
        <div class="spin-layout">

            <!-- ================= WHEEL ================= -->
            <div class="wheel-box">
                <div class="pointer"></div>
                <div id="wheel-rotator">
                    <canvas id="wheel" width="360" height="360"></canvas>
                </div>
            </div>

            <!-- ================= FORM ================= -->
            <div class="form-box">
                <h2>🎯 Try Your Luck</h2>

                <input id="name" type="text" placeholder="Enter your name" />
                <input id="phone" type="tel" placeholder="Enter 10-digit phone number" maxlength="10" />
                <input id="email" type="email" placeholder="Email (optional)" />

                <button id="spinBtn" disabled>SPIN NOW 🎡</button>
                <p id="spinInfo"></p>
            </div>

        </div>
    </div>

    <!-- ================= POPUP ================= -->
    <div id="overlay" class="overlay hidden">
        <div class="popup">
            <h1>🎉 Congratulations!</h1>
            <p id="popup-offer"></p>
            <p id="popup-coupon" class="coupon"></p>
            <button id="closePopup">Awesome 😍</button>
        </div>
    </div>

    <!-- ================= CSS ================= -->
    <style>
        .spin-bg {
            padding: 80px 20px;
            background-image: url("https://images.unsplash.com/photo-1543353071-087092ec393a");
            background-size: cover;
            background-position: center;
        }
        .spin-layout {
            display: flex;
            gap: 60px;
            justify-content: center;
            align-items: center;
            max-width: 1100px;
            margin: auto;
            flex-wrap: wrap;
        }
        .wheel-box {
            position: relative;
            width: 360px;
            height: 360px;
        }
        #wheel-rotator {
            transition: transform 5s cubic-bezier(0.25, 1, 0.5, 1);
        }
        #wheel {
            background: #fff;
            border-radius: 50%;
            border: 8px solid #000;
        }
        .pointer {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) translateY(-180px);
            border-left: 16px solid transparent;
            border-right: 16px solid transparent;
            border-bottom: 32px solid #000;
            z-index: 10;
        }
        .form-box {
            background: rgba(255,255,255,0.95);
            padding: 30px;
            width: 320px;
            border-radius: 10px;
        }
        .form-box input,
        .form-box button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
        }
        .form-box button {
            background: #ffc107;
            border: none;
            font-weight: bold;
            cursor: pointer;
        }
        .form-box button:disabled {
            background: #ccc;
        }
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.65);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 100;
        }
        .overlay.hidden { display: none; }
        .popup {
            background: linear-gradient(135deg,#fff,#fef3c7);
            padding: 40px;
            width: 320px;
            text-align: center;
            border-radius: 18px;
        }
    </style>

    <!-- ================= JS ================= -->
    <script>
        const SHEET_URL = "https://script.google.com/macros/s/AKfycbxf7CSMU3baSCkIjxBOKJb7vDS77S0rCNnvnQOPRkjukFslGtjYymxDAJq2OttvuQLG/exec";

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
            const popupCoupon = document.getElementById("popup-coupon");

            const labels = ["30% OFF","15% OFF","Try Next Time","40% OFF","25% OFF","50% OFF"];
            const colors = ["#000","#14c6cc","#ff006f","#14c6cc","#ff006f","#ffc107"];
            const slice = (2*Math.PI)/labels.length;
            let rotation = 0, spinsUsed = 0;

            function drawWheel() {
                for(let i=0;i<labels.length;i++){
                    ctx.beginPath();
                    ctx.moveTo(180,180);
                    ctx.arc(180,180,180,slice*i,slice*(i+1));
                    ctx.fillStyle = colors[i];
                    ctx.fill();
                }
            }
            drawWheel();

            function updateUI(){
                spinInfo.innerHTML = `🎯 You have <b>${2-spinsUsed}</b> spins`;
                spinBtn.disabled = spinsUsed>=2 || !nameInput.value || phoneInput.value.length!==10;
            }

            spinBtn.onclick = () => {
                if(spinsUsed>=2) return;
                const index = spinsUsed===0 ? Math.floor(Math.random()*5) : 5;
                rotation += 360*6 + index*(360/labels.length);
                rotator.style.transform = `rotate(${rotation}deg)`;
                spinsUsed++;
                updateUI();

                setTimeout(()=>{
                    const offer = labels[index];
                    if(offer==="Try Next Time"){ alert("🍀 Try again"); return; }
                    const coupon = "SONOFSWAAD"+offer.match(/\d+/)[0];
                    popupOffer.innerHTML = `🎉 You won ${offer}`;
                    popupCoupon.innerHTML = coupon;
                    overlay.classList.remove("hidden");

                    fetch(SHEET_URL,{
                        method:"POST",
                        mode:"no-cors",
                        body:JSON.stringify({
                            name:nameInput.value,
                            phone:phoneInput.value,
                            offer,
                            coupon
                        })
                    });
                },5200);
            };

            document.getElementById("closePopup").onclick = () => overlay.classList.add("hidden");
            nameInput.oninput = phoneInput.oninput = updateUI;
            updateUI();
        });
    </script>

    <?php
    return ob_get_clean();
}
add_shortcode('spin_wheel', 'spin_wheel_shortcode');
