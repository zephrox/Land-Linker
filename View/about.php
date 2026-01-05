<?php require_once __DIR__ . '/layout/header.php'; ?>

<div class="container">
  <div class="section-head">
    <div>
      <h2 class="section-title">About Land Linker</h2>
      <p class="section-subtitle">
        Land Linker connects buyers, sellers, and teams with a clean workflow for discovering property,
        tracking deals, and closing faster.
      </p>
    </div>
    <a class="btn btn-outline" href="<?= BASE_URL ?>View/subscription.php">View Pricing</a>
  </div>

  <section class="about-grid">
    <article class="card about-card">
      <h3>Who we are</h3>
      <p>
        Land Linker is a modern real-estate platform designed to simplify property exploration and
        transaction handling. We focus on speed, clarity, and a smooth user experience.
      </p>
    </article>

    <article class="card about-card">
      <h3>What we do</h3>
      <ul class="about-list">
        <li><span class="check" aria-hidden="true">✓</span> Discover and compare listings</li>
        <li><span class="check" aria-hidden="true">✓</span> Track deals and leads</li>
        <li><span class="check" aria-hidden="true">✓</span> Manage teams and employees</li>
        <li><span class="check" aria-hidden="true">✓</span> Estimate costs with calculators</li>
      </ul>
    </article>

    <article class="card about-card">
      <h3>Why Land Linker</h3>
      <p>
        Clean dashboards, responsive design, and a structured workflow. Built with vanilla HTML/CSS/JS
        and PHP templating for maximum control and performance.
      </p>
    </article>

    <article class="card about-card">
      <h3>Contact</h3>
      <p style="margin-bottom: 10px; color: var(--muted); font-weight: 650;">
        Want a demo or help with onboarding?
      </p>
      <div class="about-kv">
        <div class="kv"><span>Email</span><strong>support@landlinker.com</strong></div>
        <div class="kv"><span>Phone</span><strong>+880 1XXXXXXXXX</strong></div>
      </div>
      <a class="btn btn-primary" style="margin-top: 12px;" href="<?= BASE_URL ?>View/login.php">Sign In</a>
    </article>
  </section>

  <section class="card about-cta">
    <div>
      <h3 style="margin:0 0 6px;">Ready to get started?</h3>
      <p style="margin:0; color: var(--muted); font-weight: 650;">
        Create an account and start exploring properties today.
      </p>
    </div>
    <a class="btn btn-primary" href="<?= BASE_URL ?>View/signup.php">Create Account</a>
  </section>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
