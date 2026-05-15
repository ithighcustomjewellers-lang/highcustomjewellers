<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Terms & Conditions | HighCustomAI</title>
  <meta name="description" content="HighCustomAI Terms & Conditions – Learn about Gmail integration requirements, user responsibilities, and service policies.">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(135deg, #f4f9fe 0%, #eef2f8 100%);
      font-family: system-ui, -apple-system, 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Noto Sans', sans-serif;
      line-height: 1.5;
      color: #1a2c3e;
      padding: 2rem 1rem;
    }

    .terms-container {
      max-width: 1120px;
      margin: 0 auto;
    }

    .terms-card {
      background: #ffffff;
      border-radius: 2rem;
      box-shadow: 0 25px 40px -14px rgba(0, 0, 0, 0.1), 0 2px 6px -2px rgba(0, 0, 0, 0.02);
      overflow: hidden;
      transition: all 0.2s ease;
    }

    .terms-content {
      padding: 2.5rem;
    }

    @media (max-width: 680px) {
      body {
        padding: 1rem;
      }
      .terms-content {
        padding: 1.75rem;
      }
    }

    /* header & branding matching privacy policy style */
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

    .welcome-text {
      font-size: 1.05rem;
      background: #fbfefe;
      padding: 1.2rem 1.4rem;
      border-radius: 1.2rem;
      margin: 1rem 0 1.5rem 0;
      border-left: 5px solid #2b6a9f;
      color: #1c3f5c;
      font-weight: 450;
    }

    /* section styling */
    .terms-section {
      margin-bottom: 2.2rem;
      scroll-margin-top: 1rem;
    }

    .terms-section h2 {
      font-size: 1.55rem;
      font-weight: 600;
      letter-spacing: -0.2px;
      color: #0f3b4f;
      margin-bottom: 0.85rem;
      padding-bottom: 0.4rem;
      border-bottom: 2px solid #e4eef5;
      display: inline-block;
    }

    .terms-section p {
      margin-bottom: 1rem;
      color: #2c4258;
      font-weight: 440;
    }

    .terms-section ul, .terms-list {
      margin: 0.75rem 0 1rem 1.5rem;
      list-style-type: none;
    }

    .terms-section li {
      margin-bottom: 0.7rem;
      position: relative;
      padding-left: 1.6rem;
      color: #2c4e6e;
    }

    .terms-section li::before {
      content: "▹";
      position: absolute;
      left: 0;
      color: #2b6a9f;
      font-weight: 500;
      font-size: 0.9rem;
    }

    .inline-note {
      background: #f8fafd;
      padding: 0.9rem 1.2rem;
      border-radius: 1rem;
      border: 1px solid #e2edf2;
      margin: 1rem 0 0.5rem 0;
      font-size: 0.95rem;
    }

    .highlight-box {
      margin-top: 1.5rem;
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

    .highlight-box svg {
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

    hr {
      margin: 1.2rem 0 1rem;
      border: 0;
      height: 1px;
      background: #e2edf2;
    }

    footer {
      margin-top: 2rem;
      text-align: center;
      font-size: 0.8rem;
      color: #5f7f96;
      border-top: 1px solid #e2edf2;
      padding-top: 1.5rem;
    }

    @media (max-width: 480px) {
      .terms-section h2 {
        font-size: 1.35rem;
      }
      .welcome-text {
        padding: 1rem;
      }
    }
  </style>
</head>
<body>
<div class="terms-container">
  <div class="terms-card">
    <div class="terms-content">

      <div class="brand-header">
        <span class="logo">HighCustomAI</span>
        <span class="badge">⚖️ Terms of Service</span>
      </div>

      <!-- Welcome statement -->
      <div class="welcome-text">
        Welcome to HighCustomAI.<br>
        HighCustomAI is a platform that helps users send business and communication emails directly from their own Gmail account.
      </div>

      <!-- Why Gmail Connection Is Required -->
      <section class="terms-section" id="gmail-required">
        <h2>Why Gmail Connection Is Required</h2>
        <p>Our platform requires Gmail connection to:</p>
        <ul>
          <li>Authenticate users securely with Google</li>
          <li>Allow users to send emails from their own Gmail account</li>
          <li>Manage email communication workflows</li>
          <li>Provide authorized email sending functionality</li>
        </ul>
        <p>Users must explicitly grant Google authorization before using Gmail-related features.</p>
        <div class="inline-note">
          🔐 HighCustomAI uses OAuth 2.0 — your Gmail credentials are never stored or handled directly by us. All actions are performed with your explicit permission.
        </div>
      </section>

      <!-- User Responsibilities -->
      <section class="terms-section" id="responsibilities">
        <h2>User Responsibilities</h2>
        <p>By using HighCustomAI, users agree:</p>
        <ul>
          <li>Not to send spam or abusive emails</li>
          <li>Not to violate Google policies</li>
          <li>Not to use the platform for illegal activities</li>
          <li>To use their connected Gmail account responsibly</li>
        </ul>
        <p>Any violation may result in immediate suspension of access and potential reporting to relevant authorities. You are solely responsible for the content you transmit via our platform.</p>
      </section>

      <!-- Account Access -->
      <section class="terms-section" id="account-access">
        <h2>Account Access</h2>
        <p>Users are responsible for protecting their account credentials and connected Google account access. You are liable for all activities that occur under your account, including any email actions performed via HighCustomAI. If you suspect unauthorised access, you must revoke HighCustomAI's access via your Google Account settings and notify our support team immediately.</p>
      </section>

      <!-- Service Availability -->
      <section class="terms-section" id="availability">
        <h2>Service Availability</h2>
        <p>We may improve, modify, or temporarily suspend services when necessary. While we strive for 99.9% uptime, occasional maintenance, security updates, or unforeseen events might affect availability. We will provide reasonable notice whenever possible for planned modifications.</p>
        <div class="highlight-box">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" fill="#2b6a9f"/>
          </svg>
          <span><strong>Continuous improvement:</strong> HighCustomAI reserves the right to evolve features, adjust limits, or modify the user interface to enhance user experience. Material changes to functionality will be communicated via email or in-app notice.</span>
        </div>
      </section>

      <!-- Third-Party Services -->
      <section class="terms-section" id="third-party">
        <h2>Third-Party Services</h2>
        <p>Our platform integrates with Google services for Gmail authentication and email sending. By using HighCustomAI, you also agree to comply with <strong>Google's Terms of Service</strong> and <strong>Google API Services User Data Policy</strong>. HighCustomAI is not responsible for any changes, disruptions, or policies enforced by Google. Your relationship with Google remains separate, and you acknowledge that our use of Gmail APIs is subject to Google’s restrictions.</p>
      </section>

      <!-- Limitation of Liability (extra but responsible and standard) -->
      <section class="terms-section" id="liability">
        <h2>Limitation of Liability</h2>
        <p>To the maximum extent permitted by law, HighCustomAI shall not be liable for any indirect, incidental, or consequential damages resulting from the use or inability to use our platform, including but not limited to email delivery failures, data loss, or third-party actions. Our total liability shall not exceed the amount paid by you (if any) for using the service during the prior 12 months.</p>
      </section>

      <!-- Modifications to Terms -->
      <section class="terms-section" id="modifications">
        <h2>Changes to These Terms</h2>
        <p>HighCustomAI may update these Terms & Conditions from time to time. We will notify users of material changes via the email associated with your account or through a prominent notice on our website. Your continued use of the platform after the effective date constitutes acceptance of the revised terms.</p>
      </section>

      <!-- Governing Law (brief) -->
      <section class="terms-section" id="governing-law">
        <h2>Governing Law</h2>
        <p>These Terms shall be governed by the laws of the jurisdiction where HighCustomAI operates, without regard to conflict of law principles. Any disputes shall be resolved through binding arbitration or small claims court as determined by applicable rules.</p>
      </section>

      <!-- Contact -->
      <section class="terms-section" id="contact">
        <h2>Contact</h2>
        <p>For support or questions, visit:</p>
        <p style="margin-top: 0.5rem;">
          <a href="https://highcustomai.com" class="contact-link" target="_blank" rel="noopener noreferrer">
            🌐 https://highcustomai.com
          </a>
        </p>
        <p style="font-size: 0.9rem; margin-top: 0.75rem;">If you have any legal inquiries or need clarification regarding these Terms & Conditions, please reach out through our official support channels.</p>
      </section>

      <hr>
      <div style="font-size: 0.85rem; color: #4a627a; text-align: center; margin-top: 0.2rem;">
        ✅ By using HighCustomAI, you acknowledge that you have read, understood, and agreed to these Terms & Conditions.
      </div>
    </div>
  </div>
</div>
</body>
</html>
