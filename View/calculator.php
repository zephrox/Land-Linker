<?php require_once __DIR__ . '/layout/header.php'; ?>

<div class="container">
  <div class="section-head">
    <div>
      <h2 class="section-title">Land Calculator</h2>
      <p class="section-subtitle">Estimate total value including commission, tax, and fees.</p>
    </div>
    <span class="badge-accent">Instant Estimate</span>
  </div>

  <section class="calc">
    <div class="calc-form card">
      <h3 class="calc-title">Inputs</h3>

      <div class="calc-grid">
        <div class="field">
          <label for="landSize">Land Size (Sq Ft)</label>
          <input id="landSize" type="number" min="0" step="0.01" placeholder="e.g., 2000">
        </div>

        <div class="field">
          <label for="pricePerSqft">Price Per Sq Ft (BDT)</label>
          <input id="pricePerSqft" type="number" min="0" step="0.01" placeholder="e.g., 250">
        </div>

        <div class="field">
          <label for="commission">Commission (%)</label>
          <input id="commission" type="number" min="0" step="0.01" placeholder="e.g., 2">
        </div>

        <div class="field">
          <label for="tax">Tax (BDT)</label>
          <input id="tax" type="number" min="0" step="0.01" placeholder="e.g., 15000">
        </div>

        <div class="field">
          <label for="regFee">Registration Fee (BDT)</label>
          <input id="regFee" type="number" min="0" step="0.01" placeholder="e.g., 20000">
        </div>

        <div class="field">
          <label for="discount">Discount (BDT)</label>
          <input id="discount" type="number" min="0" step="0.01" placeholder="e.g., 5000">
        </div>
      </div>

      <div class="calc-actions">
        <button class="btn btn-primary" id="calcBtn" type="button">Calculate Total</button>
        <button class="btn btn-outline" id="calcReset" type="button">Reset</button>
      </div>
    </div>

    <aside class="calc-result card">
      <h3 class="calc-title">Estimated Value</h3>

      <div class="estimate">
        <div class="estimate__row">
          <span>Base Price</span>
          <strong id="basePrice">—</strong>
        </div>
        <div class="estimate__row">
          <span>Commission</span>
          <strong id="commissionValue">—</strong>
        </div>
        <div class="estimate__row">
          <span>Tax</span>
          <strong id="taxValue">—</strong>
        </div>
        <div class="estimate__row">
          <span>Registration Fee</span>
          <strong id="regValue">—</strong>
        </div>
        <div class="estimate__row">
          <span>Discount</span>
          <strong id="discountValue">—</strong>
        </div>

        <div class="estimate__total">
          <span>Total</span>
          <strong id="totalValue">—</strong>
        </div>

        <p class="estimate__hint">
          This is an estimate. Actual charges may vary by location and policy.
        </p>
      </div>
    </aside>
  </section>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
