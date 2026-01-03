<?php
require_once __DIR__ . '/../Model/init.php';
require_login(BASE_URL . 'View/login.php');

require_once __DIR__ . '/layout/header.php';
?>

<div class="container">
  <div class="section-head">
    <div>
      <h2 class="section-title">Payment</h2>
      <p class="section-subtitle">Complete your payment securely.</p>
    </div>
    <span class="badge-accent">Secure Checkout</span>
  </div>

  <section class="pay">
    <!-- Summary -->
    <aside class="pay-summary card">
      <h3 class="pay-title">Summary</h3>

      <div class="pay-kv">
        <div class="kv">
          <span>Transaction ID</span>
          <strong>#TXN-LL-2025-00128</strong>
        </div>
        <div class="kv">
          <span>Type</span>
          <strong>Land Purchase</strong>
        </div>
        <div class="kv">
          <span>Amount</span>
          <strong>500,000 BDT</strong>
        </div>
      </div>

      <div class="pay-summary__cta">
        <a class="btn btn-outline" href="<?= BASE_URL ?>View/calculator.php">Recalculate</a>
      </div>
    </aside>

    <!-- Payment Method -->
    <div class="pay-method card">
      <div class="pay-head">
        <div>
          <h3 class="pay-title">Choose Method</h3>
          <p class="pay-sub">Select a method and fill the details.</p>
        </div>
      </div>

      <div class="pay-tabs" role="tablist" aria-label="Payment method tabs">
        <button class="pay-tab is-active" type="button" data-tab="card">Credit Card</button>
        <button class="pay-tab pay-tab--bkash" type="button" data-tab="bkash">bKash</button>
      </div>

      <!-- Credit Card Panel -->
      <div class="pay-panel is-open" data-panel="card">
        <form class="pay-form" action="#" method="post">
          <div class="field">
            <label for="cardNumber">Card Number</label>
            <input id="cardNumber" type="text" inputmode="numeric" placeholder="1234 5678 9012 3456">
          </div>

          <div class="pay-row">
            <div class="field">
              <label for="expiry">Expiry</label>
              <input id="expiry" type="text" placeholder="MM/YY">
            </div>

            <div class="field">
              <label for="cvv">CVV</label>
              <input id="cvv" type="password" inputmode="numeric" placeholder="***">
            </div>
          </div>

          <div class="field">
            <label for="cardName">Name on Card</label>
            <input id="cardName" type="text" placeholder="Adnan Akib">
          </div>

          <button class="btn btn-primary pay-btn" type="button">Pay Now</button>
        </form>
      </div>

      <!-- bKash Panel (UI only) -->
      <div class="pay-panel" data-panel="bkash">
        <div class="bkash-box">
          <div class="bkash-badge">bKash</div>
          <p class="bkash-text">
            This is a UI placeholder. Connect the bKash API later and ask the user to confirm payment.
          </p>

          <div class="field">
            <label for="bkashNumber">bKash Number</label>
            <input id="bkashNumber" type="text" placeholder="01XXXXXXXXX">
          </div>

          <div class="field">
            <label for="transactionid">Transaction ID</label>
            <input id="bkashPin" type="password" placeholder="TXBLKN1789">
          </div>

          <button class="btn pay-btn pay-btn--bkash" type="button">Pay with bKash</button>
          <p class="bkash-hint">Marchant Account</p>
        </div>
      </div>
    </div>
  </section>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
