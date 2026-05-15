<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>HighCustomAI – Secure Gmail Business Email Platform</title>
  <meta name="description" content="HighCustomAI helps users securely connect Gmail and send business emails with Google authentication and full user authorization.">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(135deg, #f1f5f9 0%, #e6edf4 100%);
      font-family: system-ui, -apple-system, 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Noto Sans', sans-serif;
      line-height: 1.5;
      color: #13293d;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }

    .container {
      max-width: 1100px;
      width: 100%;
      margin: 0 auto;
    }

    /* main card */
    .card {
      background: #ffffff;
      border-radius: 2rem;
      box-shadow: 0 30px 45px -20px rgba(0, 0, 0, 0.2), 0 2px 6px rgba(0, 0, 0, 0.02);
      overflow: hidden;
      transition: all 0.2s ease;
    }

    .card-content {
      padding: 3rem 2.5rem;
    }

    @media (max-width: 680px) {
      .card-content {
        padding: 2rem 1.5rem;
      }
    }

    /* header area */
    .brand {
      display: flex;
      align-items: baseline;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 1rem;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid #eef2f8;
    }

    .logo {
      font-size: 2rem;
      font-weight: 800;
      letter-spacing: -0.02em;
      background: linear-gradient(135deg, #1f4569, #2b6a9f);
      background-clip: text;
      -webkit-background-clip: text;
      color: transparent;
    }

    .badge {
      background: #eef3fc;
      padding: 0.3rem 1rem;
      border-radius: 100px;
      font-size: 0.75rem;
      font-weight: 500;
      color: #1f6392;
    }

    /* description text */
    .description {
      font-size: 1.2rem;
      color: #2c4f6e;
      background: #fafdff;
      border-left: 5px solid #2b6a9f;
      padding: 1.2rem 1.5rem;
      border-radius: 1rem;
      margin: 1.2rem 0 2rem 0;
      font-weight: 460;
      box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }

    /* features grid */
    .features-title {
      font-size: 1.6rem;
      font-weight: 700;
      margin: 1.2rem 0 1.3rem 0;
      color: #0e3b4f;
      letter-spacing: -0.2px;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
      gap: 1.2rem;
      margin-bottom: 2.5rem;
    }

    .feature-item {
      background: #ffffff;
      border: 1px solid #e5edf4;
      border-radius: 1.2rem;
      padding: 1.2rem 1rem;
      transition: all 0.2s ease;
      box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }

    .feature-item:hover {
      transform: translateY(-3px);
      border-color: #cde0ed;
      box-shadow: 0 12px 18px -10px rgba(0, 0, 0, 0.08);
    }

    .feature-icon {
      font-size: 1.8rem;
      margin-bottom: 0.6rem;
      display: inline-block;
    }

    .feature-item h3 {
      font-size: 1.15rem;
      font-weight: 600;
      margin-bottom: 0.4rem;
      color: #1e4a76;
    }

    .feature-item p {
      font-size: 0.85rem;
      color: #51718c;
    }

    /* links row */
    .links-row {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 1.2rem;
      margin-top: 1.2rem;
      padding-top: 1rem;
      border-top: 1px solid #eef2f8;
    }

    .nav-links {
      display: flex;
      gap: 2rem;
      flex-wrap: wrap;
    }

    .nav-links a {
      text-decoration: none;
      font-weight: 500;
      font-size: 1rem;
      color: #2c6288;
      transition: color 0.2s, border-bottom 0.2s;
      border-bottom: 1px dotted transparent;
    }

    .nav-links a:hover {
      color: #0f4c6e;
      border-bottom-color: #2b6a9f;
    }

    .login-btn {
      background: linear-gradient(105deg, #1f6392, #2a7fae);
      color: white;
      padding: 0.6rem 1.6rem;
      border-radius: 2rem;
      font-weight: 600;
      font-size: 0.9rem;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s ease;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .login-btn:hover {
      background: linear-gradient(105deg, #0f5177, #1f6e9e);
      transform: translateY(-2px);
      box-shadow: 0 8px 14px -8px rgba(31, 99, 146, 0.4);
    }

    /* footer note (optional, clean) */
    .footer-note {
      margin-top: 2rem;
      text-align: center;
      font-size: 0.7rem;
      color: #7d99b0;
      border-top: 1px solid #edf2f7;
      padding-top: 1rem;
    }

    @media (max-width: 560px) {
      .links-row {
        flex-direction: column;
        align-items: flex-start;
      }
      .login-btn {
        width: 100%;
        justify-content: center;
      }
      .description {
        font-size: 1rem;
        padding: 1rem;
      }
    }
  </style>
</head>
<body>
<div class="container">
  <div class="card">
    <div class="card-content">

      <!-- brand + badge -->
      <div class="brand">
        <span class="logo">HighCustomAI</span>
      </div>

      <!-- exact description from prompt -->
      <div class="description">
        HighCustomAI helps users securely connect their Gmail account and send business emails directly from our platform.
      </div>

      <!-- Features heading -->
      <div class="features-title">✨ Platform features</div>

      <!-- Features list with matching descriptions -->
      <div class="features-grid">
        <div class="feature-item">
          <div class="feature-icon">🔐</div>
          <h3>Secure Google authentication</h3>
          <p>Industry-standard OAuth 2.0 – we never store your password.</p>
        </div>
        <div class="feature-item">
          <div class="feature-icon">📧</div>
          <h3>Gmail integration</h3>
          <p>Direct, seamless connection to your Gmail account.</p>
        </div>
        <div class="feature-item">
          <div class="feature-icon">💼</div>
          <h3>Business email sending</h3>
          <p>Send professional emails from your own mailbox.</p>
        </div>
        <div class="feature-item">
          <div class="feature-icon">✅</div>
          <h3>User-authorized email communication</h3>
          <p>Every action requires explicit user consent.</p>
        </div>
      </div>

      <!-- links: Privacy Policy, Terms & Conditions, Login -->
      <div class="links-row">
        <div class="nav-links">
          <a href="https://highcustomai.com/privacy-policy" target="_blank" rel="noopener noreferrer">📄 Privacy Policy</a>
          <a href="https://highcustomai.com/terms" target="_blank" rel="noopener noreferrer">⚖️ Terms & Conditions</a>
        </div>
        <a href="https://highcustomai.com" class="login-btn">
          🔑 Login
          <span>→</span>
        </a>
      </div>

      <!-- small compliance mention (optional but adds trust) -->
      <div class="footer-note">
        HighCustomAI — Google API Limited Use compliant | Your data stays yours
      </div>
    </div>
  </div>
</div>
</body>
</html>
