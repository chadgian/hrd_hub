<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Oops! Error Occurred</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background: #0f0c29;
      background: linear-gradient(to right, #24243e, #302b63, #0f0c29);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .container {
      text-align: center;
      position: relative;
      z-index: 1;
    }

    .error-code {
      font-size: 150px;
      color: #fff;
      text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
      animation: float 3s ease-in-out infinite;
    }

    .message {
      color: #fff;
      font-size: 24px;
      margin: 20px 0;
      text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
    }

    .home-btn {
      background: linear-gradient(45deg, #ff6b6b, #ff8e8e);
      border: none;
      padding: 15px 40px;
      color: white;
      font-size: 18px;
      border-radius: 30px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 2px;
      position: relative;
      overflow: hidden;
    }

    .home-btn:hover {
      transform: scale(1.1);
      box-shadow: 0 0 20px rgba(255, 107, 107, 0.5);
    }

    .home-btn:active {
      transform: scale(0.95);
    }

    .alien {
      width: 100px;
      height: 100px;
      margin: 20px auto;
      animation: spin 4s linear infinite;
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-20px);
      }
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    .bubbles {
      position: absolute;
      width: 100%;
      height: 100%;
      z-index: 0;
    }

    .bubbles span {
      position: absolute;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      animation: bubble 10s linear infinite;
    }

    @keyframes bubble {
      0% {
        transform: translateY(100vh) scale(0);
        opacity: 1;
      }

      100% {
        transform: translateY(-100vh) scale(1);
        opacity: 0;
      }
    }
  </style>
</head>

<body>
  <div class="bubbles">
    <!-- JavaScript will add bubbles dynamically -->
  </div>

  <div class="container">
    <div class="alien">🛸</div>
    <h1 class="error-code">404</h1>
    <p class="message">Oops! Something went wrong.</p>
    <p class="message" style="font-size: 18px;">Our team of space monkeys is working to fix it!</p>
    <button class="home-btn" onclick="window.location.href='/hrd_hub'">Beam Me Home</button>
  </div>

  <script>
    // Create floating bubbles
    const bubblesContainer = document.querySelector('.bubbles');
    for (let i = 0; i < 20; i++) {
      const bubble = document.createElement('span');
      bubble.style.left = Math.random() * 100 + '%';
      bubble.style.width = bubble.style.height = Math.random() * 20 + 5 + 'px';
      bubble.style.animationDelay = Math.random() * 10 + 's';
      bubblesContainer.appendChild(bubble);
    }
  </script>
</body>

</html>