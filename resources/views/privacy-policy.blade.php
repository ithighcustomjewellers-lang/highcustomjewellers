<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Privacy Policy | HighCustomAI</title>
  <meta name="description" content="HighCustomAI Privacy Policy – Learn how we protect your data, handle Google account access, and respect your privacy.">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(145deg, #f6f9fc 0%, #edf2f7 100%);
      font-family: system-ui, -apple-system, 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Noto Sans', sans-serif;
      line-height: 1.5;
      color: #1a2c3e;
      padding: 2rem 1rem;
    }

    /* main card container */
    .privacy-container {
      max-width: 1120px;
      margin: 0 auto;
    }

    .privacy-card {
      background: #ffffff;
      border-radius: 2rem;
      box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.08), 0 2px 5px -2px rgba(0, 0, 0, 0.02);
      overflow: hidden;
      transition: all 0.2s ease;
    }

    /* inner content spacing */
    .privacy-content {
      padding: 2.5rem;
    }

    @media (max-width: 680px) {
      body {
        padding: 1rem;
      }
      .privacy-content {
        padding: 1.75rem;
      }
    }

    /* header & branding */
    .brand-header {
      display: flex;
      align-items: baseline;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-bottom: 1.25rem;
      padding-bottom: 0.75rem;
      border-bottom: 2px solid #eef2f6;
    }

    .logo {
      font-weight: 700;
      font-size: 1.6rem;
      letter-spacing: -0.02em;
      background: linear-gradient(135deg, #1e3c5c, #2b5b8b);
      background-clip: text;
      -webkit-background-clip: text;
      color: transparent;
    }

    .badge {
      background: #eef2ff;
      padding: 0.25rem 0.85rem;
      border-radius: 40px;
      font-size: 0.75rem;
      font-weight: 500;
      color: #1e4a76;
      letter-spacing: 0.01em;
    }

    h1 {
      font-size: 2.2rem;
      font-weight: 700;
      letter-spacing: -0.01em;
      background: linear-gradient(125deg, #0f2b3d, #1f5e8e);
      background-clip: text;
      -webkit-background-clip: text;
      color: transparent;
      margin: 0.5rem 0 0.25rem 0;
    }

    @media (max-width: 540px) {
      h1 {
        font-size: 1.8rem;
      }
    }

    .effective-date {
      font-size: 0.9rem;
      color: #5a6e7c;
      background: #f8fafc;
      display: inline-block;
      padding: 0.3rem 1rem;
      border-radius: 30px;
      margin: 0.5rem 0 1rem 0;
      border: 1px solid #e2edf2;
    }

    .intro-text {
      font-size: 1.08rem;
      background: #fbfefe;
      padding: 1.2rem 1.4rem;
      border-radius: 1.2rem;
      margin: 1.2rem 0 1.5rem 0;
      border-left: 5px solid #2b6a9f;
      color: #1c3f5c;
      font-weight: 450;
      box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }

    /* section styling */
    .policy-section {
      margin-bottom: 2.2rem;
      scroll-margin-top: 1rem;
    }

    .policy-section h2 {
      font-size: 1.55rem;
      font-weight: 600;
      letter-spacing: -0.2px;
      color: #0f3b4f;
      margin-bottom: 0.85rem;
      padding-bottom: 0.4rem;
      border-bottom: 2px solid #e4eef5;
      display: inline-block;
    }

    .policy-section p {
      margin-bottom: 1rem;
      color: #2c4258;
      font-weight: 440;
    }

    .policy-section ul, .policy-section .custom-list {
      margin: 0.75rem 0 1rem 1.5rem;
      list-style-type: none;
    }

    .policy-section li {
      margin-bottom: 0.6rem;
      position: relative;
      padding-left: 1.6rem;
      color: #2c4e6e;
    }

    .policy-section li::before {
      content: "▹";
      position: absolute;
      left: 0;
      color: #2b6a9f;
      font-weight: 500;
      font-size: 0.9rem;
    }

    /* for nested standard text */
    .inline-note {
      background: #f8fafd;
      padding: 0.9rem 1.2rem;
      border-radius: 1rem;
      border: 1px solid #e2edf2;
      margin: 1rem 0 0.5rem 0;
      font-size: 0.95rem;
    }

    .compliance-note {
      margin-top: 2rem;
      background: #eff6ff;
      border-radius: 1.2rem;
      padding: 1.2rem 1.4rem;
      font-size: 0.9rem;
      border-left: 4px solid #1f7ea3;
      color: #155a7e;
      display: flex;
      gap: 0.75rem;
      align-items: flex-start;
    }

    .compliance-note svg {
      flex-shrink: 0;
      margin-top: 0.1rem;
    }

    .contact-link {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      background: #eef2fa;
      padding: 0.3rem 1rem;
      border-radius: 40px;
      text-decoration: none;
      font-weight: 500;
      color: #1f6392;
      transition: all 0.2s;
      word-break: break-all;
    }

    .contact-link:hover {
      background: #e2eaf3;
      color: #0c4e73;
      transform: translateY(-1px);
    }

    a {
      color: #1f6e9e;
      text-decoration: none;
      font-weight: 500;
      border-bottom: 1px dotted #bdd4e2;
    }

    a:hover {
      color: #0c4a6e;
      border-bottom: 1px solid #1f6e9e;
    }

    footer {
      margin-top: 2rem;
      text-align: center;
      font-size: 0.8rem;
      color: #5f7f96;
      border-top: 1px solid #e2edf2;
      padding-top: 1.5rem;
    }

    hr {
      margin: 0.75rem 0;
      border: 0;
      height: 1px;
      background: #e2edf2;
    }

    @media (max-width: 480px) {
      .policy-section h2 {
        font-size: 1.35rem;
      }
      .intro-text {
        padding: 1rem;
      }
    }
  </style>
</head>
<body>
<div class="privacy-container">
  <div class="privacy-card">
    <div class="privacy-content">

      <div class="brand-header">
        <span class="logo">HighCustomAI</span>
      </div>

      <h1>Privacy Policy</h1>
      <!-- intro statement from original draft -->
      <div class="intro-text">
        HighCustomAI respects your privacy and is committed to protecting your personal information.
      </div>

      <!-- Information We Collect -->
      <section class="policy-section" id="info-collect">
        <h2>Information We Collect</h2>
        <p>When users connect their Google account to HighCustomAI, we may access:</p>
        <ul>
          <li>Basic profile information</li>
          <li>Email address</li>
          <li>Gmail sending permission</li>
          <li>Gmail received permission</li>
        </ul>
        <p class="inline-note" style="margin-top: 0.25rem;">
          ✅ We only request the minimum scopes needed to provide email-sending features. You are always in control.
        </p>
      </section>

      <!-- How We Use Google Data (exact content + extra compliance line) -->
      <section class="policy-section" id="google-use">
        <h2>How We Use Google Data</h2>
        <p>Our application uses Gmail access only for:</p>
        <ul>
          <li>Sending emails on behalf of authenticated users</li>
          <li>Managing business communication workflows</li>
          <li>User-authorized email automation</li>
        </ul>
        <p><strong>We do not read, sell, or share users' Gmail data with third parties.</strong></p>
        <!-- Explicit extra note to reflect Google's Limited Use Requirements (best practice) -->
        <div class="compliance-note" style="margin-top: 1rem; background: #eef2fa; border-left-color: #3685b5;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15h-2v-2h2v2zm0-4h-2V7h2v6z" fill="#2b6a9f"/>
          </svg>
          <span>HighCustomAI’s use and transfer of information received from Google APIs will adhere to the <strong>Google API Services User Data Policy</strong>, including the Limited Use requirements. Your Gmail data is only used to provide and improve the email-sending features you explicitly authorize.</span>
        </div>
      </section>

      <!-- User Authorization -->
      <section class="policy-section" id="user-auth">
        <h2>User Authorization</h2>
        <p>Users must explicitly authorize Google access before using Gmail-related features. The authorization process is transparent and clearly states requested permissions (profile, email, and Gmail send scope).</p>
        <p>Users can revoke access anytime from their <strong>Google Account settings</strong> (Security → Third-party apps with account access). Revocation immediately stops HighCustomAI from accessing any Google data.</p>
      </section>

      <!-- Data Security -->
      <section class="policy-section" id="security">
        <h2>Data Security</h2>
        <p>We implement reasonable security measures to protect user information and connected account data. This includes encrypted connections (TLS), restricted internal access, and regular security reviews. While no system is 100% invulnerable, we continuously evolve our safeguards to protect your privacy.</p>
      </section>

      <!-- Third-Party Sharing (exact text) -->
      <section class="policy-section" id="third-party">
        <h2>Third-Party Sharing</h2>
        <p><strong>We do not sell, trade, or share personal information or Gmail data with third parties.</strong> Your information is never leased, sold, or exchanged for any advertising or analytics purpose unrelated to HighCustomAI’s core functionality. Access to any data is strictly limited to providing the service you request.</p>
      </section>

      <!-- Contact section -->
      <section class="policy-section" id="contact">
        <h2>Contact</h2>
        <p>If you have any questions regarding this Privacy Policy, concerns about your data, or want to exercise your privacy rights, please contact us through our website:</p>
        <p style="margin-top: 0.5rem;">
          <a href="https://highcustomai.com" class="contact-link" target="_blank" rel="noopener noreferrer">
            🌐 https://highcustomai.com
          </a>
        </p>
        <p style="font-size: 0.9rem; margin-top: 0.6rem;">Our support team will respond within reasonable time. For Google account access removal, refer to your Google security dashboard.</p>
      </section>

      <!-- additional transparency notice: no other third parties -->
      <hr>
      <div style="font-size: 0.85rem; color: #4a627a; text-align: center; margin-top: 0.5rem;">
        ⚡ HighCustomAI does NOT store raw email content beyond the required transmission. No automated message scanning or unauthorized sharing.
      </div>
    </div>
  </div>
</div>
</body>
</html>
