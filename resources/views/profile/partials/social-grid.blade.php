 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <title>Digital Business Card</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
 <style>
     /* =========================================================
        GOOGLE FONT
        ========================================================= */
     @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

     /* =========================================================
        RESET
        ========================================================= */
     * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
     }

     /* =========================================================
        BODY
        ========================================================= */
     body {
         min-height: 100vh;
         margin: 0;
         padding: 0;
         background: url('{{ asset('images/diamond-bg.png') }}');
         background-size: cover;
         background-position: center;
         background-repeat: no-repeat;
         background-attachment: fixed;
     }

     /* =========================================================
        MAIN CARD
        ========================================================= */
     .profile-card {
         width: 100%;
         max-width: 620px;
         margin: auto;
         border-radius: 35px;
         overflow: hidden;
         position: relative;
         background: #050505;
         border: 2px solid rgba(255, 215, 0, .7);
         box-shadow:
             0 0 25px rgba(255, 215, 0, .15),
             0 0 90px rgba(132, 0, 255, .35),
             0 20px 70px rgba(0, 0, 0, .65);
         animation: fadeIn .8s ease;
     }

     /* =========================================================
        HEADER
        ========================================================= */
     .profile-header {
         position: relative;
         padding: 20px 20px 10px;
         text-align: center;

         background:
             radial-gradient(circle at top left,
                 rgba(255, 255, 255, .1),
                 transparent 30%),

             linear-gradient(135deg,
                 #170022 0%,
                 #995050 40%,
                 #090012 100%);
     }

     /* GOLD BORDER */
     .profile-header::after {
         content: '';
         position: absolute;
         left: 0;
         bottom: 0;
         width: 100%;
         height: 2px;

         background:
             linear-gradient(to right,
                 transparent,
                 #ffd700,
                 transparent);
     }

     /* DIAMOND EFFECT */
     .profile-header::before {
         content: '';
         position: absolute;
         top: -120px;
         right: -120px;
         width: 250px;
         height: 250px;

         background:
             radial-gradient(circle,
                 rgba(255, 215, 0, .18),
                 transparent 70%);
     }

     /* LOGO */
     /* HEADER LOGO GLOW */
     .profile-header img {
         width: 100%;
         max-width: 420px;
         object-fit: contain;

         filter:
             drop-shadow(0 0 8px rgba(255, 215, 0, .35)) drop-shadow(0 0 25px rgba(255, 0, 255, .15));

         animation: logoGlow 4s ease-in-out infinite alternate;
     }

     @keyframes logoGlow {
         from {
             filter:
                 drop-shadow(0 0 8px rgba(255, 215, 0, .35)) drop-shadow(0 0 25px rgba(255, 0, 255, .15));
         }

         to {
             filter:
                 drop-shadow(0 0 18px rgba(255, 215, 0, .55)) drop-shadow(0 0 40px rgba(255, 0, 255, .25));
         }
     }

     /* SOCIAL CARD EXTRA GLOW */
     .social-link {
         position: relative;
     }

     .social-link::after {
         content: '';
         position: absolute;
         inset: 0;
         border-radius: 24px;
         padding: 1px;

         -webkit-mask:
             linear-gradient(#fff 0 0) content-box,
             linear-gradient(#fff 0 0);

         -webkit-mask-composite: xor;
         mask-composite: exclude;

         opacity: .25;
         transition: .4s;
     }

     .social-link:hover::after {
         opacity: 1;
     }

     /* MOVING GOLD LIGHT */
     .social-links::after {
         content: '';
         position: absolute;
         inset: 0;

         background:
             linear-gradient(120deg,
                 transparent 0%,
                 rgba(255, 215, 0, .03) 40%,
                 rgba(255, 255, 255, .04) 50%,
                 rgba(255, 215, 0, .03) 60%,
                 transparent 100%);

         animation: shineMove 8s linear infinite;
         pointer-events: none;
     }

     @keyframes shineMove {
         from {
             transform: translateX(-100%);
         }

         to {
             transform: translateX(100%);
         }
     }

     /* ARROW GLOW */
     .social-arrow {
         box-shadow:
             inset 0 0 10px rgba(255, 215, 0, .08),
             0 0 10px rgba(255, 215, 0, .08);
     }

     /* CARD HOVER */
     .profile-card:hover {
         box-shadow:
             0 0 30px rgba(255, 215, 0, .28),
             0 0 90px rgba(128, 0, 255, .4),
             0 20px 70px rgba(0, 0, 0, .65);
     }

     /* =========================================================
        SOCIAL SECTION
        ========================================================= */
     .social-links {
         position: relative;
         overflow: hidden;
         padding: 30px;
         background:
             linear-gradient(rgba(0, 0, 0, .82), rgba(0, 0, 0, .88)),
             url('{{ asset('images/live_diamond-bg.jpg') }}');
         background-size: cover;
         background-position: center;
         background-repeat: repeat;
         border-top: 1px solid rgba(255, 215, 0, .15);
     }

     /* PURPLE + GOLD OVERLAY */
     .social-links::before {
         content: '';
         position: absolute;
         inset: 0;
         background:
             radial-gradient(circle at top left,
                 rgba(170, 0, 255, .18),
                 transparent 35%),
             radial-gradient(circle at bottom right,
                 rgba(255, 180, 0, .12),
                 transparent 35%);
         pointer-events: none;
     }

     /* =========================================================
        GRID
        ========================================================= */
     .social-links-grid {
         position: relative;
         z-index: 2;
         display: grid;
         grid-template-columns: repeat(2, 1fr);
         gap: 18px;
     }

     /* =========================================================
        SOCIAL CARD
        ========================================================= */
     .social-link {
         position: relative;
         display: flex;
         align-items: center;
         gap: 15px;
         padding: 18px;
         border-radius: 24px;
         overflow: hidden;
         text-decoration: none;
         background: linear-gradient(135deg, rgb(92 91 64 / 96%), rgb(1 1 32 / 96%));
         border: 1.5px solid rgb(179 160 59);
         transition: .4s ease;
         box-shadow:
             inset 0 0 18px rgba(255, 255, 255, .03),
             0 0 15px rgba(255, 140, 0, .06),
             0 10px 30px rgba(0, 0, 0, .5);
     }

     /* SHINE EFFECT */
     .social-link::before {
         content: '';
         position: absolute;
         top: 0;
         left: -120%;
         width: 100%;
         height: 100%;
         background: linear-gradient(90deg,
                 transparent,
                 rgba(255, 255, 255, .08),
                 transparent);
         transition: .8s;
     }

     .social-link:hover::before {
         left: 120%;
     }

     /* HOVER */
     .social-link:hover {
         transform: translateY(-6px) scale(1.02);
         border-color: #ffd700;
         box-shadow:
             0 0 18px rgba(255, 215, 0, .2),
             0 0 40px rgba(160, 0, 255, .25),
             0 18px 35px rgba(0, 0, 0, .6);
     }

     /* =========================================================
        ICON
        ========================================================= */
     .social-icon {
         width: 64px;
         height: 64px;
         min-width: 64px;
         display: flex;
         align-items: center;
         justify-content: center;
         border-radius: 20px;
         background: #fff;
         transition: .35s ease;
         box-shadow: 0 6px 18px rgba(0, 0, 0, .35);
     }

     .social-link:hover .social-icon {
         transform: scale(1.08) rotate(-4deg);
     }

     .social-icon i {
         font-size: 32px;
     }

     .social-icon img {
         width: 34px;
         height: 34px;
         object-fit: contain;
     }

     /* =========================================================
        TEXT
        ========================================================= */
     .social-name {
         flex: 1;
         color: #fff;
         font-size: 18px;
         font-weight: 600;
         letter-spacing: .3px;
     }

     /* =========================================================
        ARROW
        ========================================================= */
     .social-arrow {
         width: 42px;
         height: 42px;
         min-width: 42px;
         display: flex;
         align-items: center;
         justify-content: center;
         border-radius: 50%;
         background:
             linear-gradient(135deg,
                 rgba(255, 255, 255, .05),
                 rgba(255, 255, 255, .02));
         border: 1px solid rgba(255, 215, 0, .3);
         color: #ffd700;
         transition: .35s ease;
     }

     .social-link:hover .social-arrow {
         transform: translateX(5px);
         background: #ffd700;
         color: #111;
         box-shadow: 0 0 15px rgba(255, 215, 0, .4);
     }

     /* =========================================================
        ICON COLORS
        ========================================================= */
     .fa-whatsapp {
         color: #25D366;
     }

     .fa-telegram {
         color: #229ED9;
     }

     .fa-instagram {
         color: #ff4d94;
     }

     .fa-facebook {
         color: #1877F2;
     }

     .fa-youtube {
         color: #ff0000;
     }

     .fa-linkedin {
         color: #0A66C2;
     }

     .fa-twitter {
         color: #1DA1F2;
     }

     .fa-x-twitter {
         color: #000;
     }

     .fa-tiktok {
         color: #000;
     }

     .fa-threads {
         color: #000;
     }

     .fa-discord {
         color: #5865F2;
     }

     .fa-pinterest {
         color: #E60023;
     }

     .fa-quora {
         color: #B92B27;
     }

     /* =========================================================
        FOOTER
        ========================================================= */
     .footer {
         padding: 18px;
         text-align: center;
         background:
             linear-gradient(135deg,
                 #f6f6f6,
                 #ffffff);
         color: #555;
         font-size: 14px;
         font-weight: 500;
         border-top: 1px solid rgba(255, 215, 0, .15);
     }

     .footer i {
         color: #ffd700;
     }

     /* =========================================================
        ANIMATION
        ========================================================= */
     @keyframes fadeIn {
         from {
             opacity: 0;
             transform: translateY(30px);
         }

         to {
             opacity: 1;
             transform: translateY(0);
         }
     }

     /* =========================================================
        TABLET
        ========================================================= */
     @media(max-width:768px) {

         body {
             padding: 50px;
         }

         .social-links {
             background-size: cover;
             background-position: center;
             background-repeat: repeat;
         }

         .social-links-grid {
             gap: 14px;
         }

         .social-link {
             padding: 14px;
             border-radius: 18px;
         }

         .social-icon {
             width: 54px;
             height: 54px;
             min-width: 54px;
         }

         .social-icon i {
             font-size: 26px;
         }

         .social-name {
             font-size: 15px;
         }

         .social-arrow {
             width: 34px;
             height: 34px;
             min-width: 34px;
         }
     }

     /* =========================================================
    RESPONSIVE MOBILE FIX
    ========================================================= */

     @media(max-width:768px) {

         body {
             background-size: cover;
             background-position: center top;
             background-repeat: no-repeat;

             background-color: #000;
             background-attachment: scroll;
         }

         .profile-card {
             width: 100%;
             max-width: 520px;
             border-radius: 28px;

             box-shadow:
                 0 0 20px rgba(255, 215, 0, .18),
                 0 0 55px rgba(132, 0, 255, .28),
                 0 15px 45px rgba(0, 0, 0, .55);
         }

         /* HEADER */
         .profile-header {
             padding: 18px 15px 12px;
         }

         .profile-header img {
             max-width: 320px;
             width: 100%;
         }

         .social-links {
             background-size: cover;
             background-position: center;
             background-repeat: repeat;
         }

         /* KEEP 2 CARDS IN ONE ROW */
         .social-links-grid {
             grid-template-columns: repeat(2, 1fr);
             gap: 12px;
         }

         /* CARD */
         .social-link {
             padding: 12px;
             gap: 10px;
             border-radius: 18px;

             min-height: 82px;
         }

         /* ICON */
         .social-icon {
             width: 48px;
             height: 48px;
             min-width: 48px;
             border-radius: 15px;
         }

         .social-icon i {
             font-size: 24px;
         }

         .social-icon img {
             width: 24px;
             height: 24px;
         }

         /* TEXT */
         .social-name {
             font-size: 14px;
             font-weight: 600;
         }

         /* ARROW */
         .social-arrow {
             width: 32px;
             height: 32px;
             min-width: 32px;
             font-size: 13px;
         }
     }

/* =========================================================
   SMALL MOBILE
========================================================= */

     @media(max-width:480px) {

         body {
             padding: 8px;
         }

         .profile-card {
             border-radius: 24px;
         }

         .profile-header {
             padding: 15px 10px 10px;
         }

         .profile-header img {
             max-width: 270px;
         }

         .social-links {
             background-size: cover;
             background-position: center;
             background-repeat: repeat;
         }

         .social-links-grid {
             gap: 10px;
         }

         .social-link {
             padding: 10px;
             border-radius: 16px;
             gap: 8px;
             min-height: 72px;
         }

         .social-icon {
             width: 42px;
             height: 42px;
             min-width: 42px;
             border-radius: 13px;
         }

         .social-icon i {
             font-size: 20px;
         }

         .social-name {
             font-size: 12px;
         }

         .social-arrow {
             width: 28px;
             height: 28px;
             min-width: 28px;
             font-size: 11px;
         }
     }
 </style>
