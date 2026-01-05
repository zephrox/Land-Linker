<?php require_once __DIR__ . '/layout/header.php'; ?>

<div class="container">
  <div class="section-head">
    <div>
      <h2 class="section-title">Vision & Commitment</h2>
      <p class="section-subtitle">
        We aim to build a trusted network where property discovery, valuation, and transactions
        feel transparent and effortless.
      </p>
    </div>
    <span class="badge-accent">Land Linker</span>
  </div>

  <section class="vision">
    <div class="vision-hero card">
      <h3 style="margin:0 0 8px;">Our Vision</h3>
      <p style="margin:0; color: var(--muted); font-weight:650; max-width: 75ch;">
        To become one of the largest real-estate networks for buyers, sellers, and professionals by
        providing fast search, clean dashboards, smart pricing tools, and secure payment experiences.
      </p>
      <div style="margin-top: 12px; display:flex; gap:10px; flex-wrap:wrap;">
        <a class="btn btn-primary" href="<?= BASE_URL ?>View/subscription.php">See Plans</a>
        <a class="btn btn-outline" href="<?= BASE_URL ?>View/calculator.php">Use Calculator</a>
      </div>
    </div>

    <div class="vision-grid">
      <article class="card vision-card">
        <div class="vision-icon" aria-hidden="true">✅</div>
        <h4>Trust & Transparency</h4>
        <p>Clear pricing, clear steps, and a reliable workflow from listing to closing.</p>
      </article>

      <article class="card vision-card">
        <div class="vision-icon" aria-hidden="true">⚡</div>
        <h4>Speed & Simplicity</h4>
        <p>Fast browsing, quick filtering, and dashboards designed for real work.</p>
      </article>

      <article class="card vision-card">
        <div class="vision-icon" aria-hidden="true">🔒</div>
        <h4>Security</h4>
        <p>Payment experiences designed to be safe, modern, and easy to understand.</p>
      </article>

      <article class="card vision-card">
        <div class="vision-icon" aria-hidden="true">📈</div>
        <h4>Growth</h4>
        <p>Tools for teams: employee management, tasks, and performance tracking.</p>
      </article>
    </div>
  </section>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
