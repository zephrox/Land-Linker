<?php
require_once __DIR__ . '/../Model/init.php';

// must be logged in
require_login(BASE_URL . 'View/login.php');

require_role([1], BASE_URL . 'View/login.php');

$pageTitle  = "User Dashboard";
$activeDash = "dashboard";

// include dashboard layout START (ONLY ONCE)
require_once __DIR__ . '/../Model/dashboard-start-user.php';
?>

<!-- ================= DASHBOARD CONTENT ================= -->

<div class="dash-grid">

  <!-- Traffic Card -->
  <div class="dash-card card">
    <div class="dash-card__head">
      <div>
        <h3 class="dash-h3">Traffic</h3>
        <p class="dash-note">Last 7 days performance</p>
      </div>

      <div class="segmented">
        <button class="seg-btn is-active" type="button">Week</button>
        <button class="seg-btn" type="button">Month</button>
        <button class="seg-btn" type="button">Year</button>
      </div>
    </div>

    <div class="bar-chart">
      <div class="bar" style="--h:40%"><span class="bar__label">M</span></div>
      <div class="bar" style="--h:70%"><span class="bar__label">T</span></div>
      <div class="bar" style="--h:55%"><span class="bar__label">W</span></div>
      <div class="bar" style="--h:82%"><span class="bar__label">T</span></div>
      <div class="bar" style="--h:64%"><span class="bar__label">F</span></div>
      <div class="bar" style="--h:48%"><span class="bar__label">S</span></div>
      <div class="bar" style="--h:76%"><span class="bar__label">S</span></div>
    </div>
  </div>

  <!-- Stats Card -->
  <div class="dash-card card">
    <div class="dash-card__head">
      <div>
        <h3 class="dash-h3">Stats</h3>
        <p class="dash-note">Live snapshot</p>
      </div>
    </div>

    <div class="progress-grid">
      <div class="progress-card">
        <div class="ring" style="--p:72">
          <span class="ring__value">72%</span>
        </div>
        <div>
          <div class="progress-title">Views</div>
          <div class="progress-sub">Weekly goal</div>
        </div>
      </div>

      <div class="progress-card">
        <div class="ring" style="--p:56">
          <span class="ring__value">56%</span>
        </div>
        <div>
          <div class="progress-title">New Visitors</div>
          <div class="progress-sub">Monthly target</div>
        </div>
      </div>

      <div class="progress-card">
        <div class="ring" style="--p:34">
          <span class="ring__value">34%</span>
        </div>
        <div>
          <div class="progress-title">Leads</div>
          <div class="progress-sub">Conversion</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Recommendations -->
<div class="dash-card card" style="margin-top:16px;">
  <div class="dash-card__head">
    <div>
      <h3 class="dash-h3">Recommendations</h3>
      <p class="dash-note">Next actions to improve listing performance</p>
    </div>
  </div>

  <div class="dash-list">
    <div class="dash-item">
      <div class="dash-item__dot"></div>
      <div>
        <div class="dash-item__title">Add 8+ photos to your top listing</div>
        <div class="dash-item__sub">Listings with more photos get higher engagement.</div>
      </div>
    </div>

    <div class="dash-item">
      <div class="dash-item__dot"></div>
      <div>
        <div class="dash-item__title">Update price to match recent comps</div>
        <div class="dash-item__sub">Competitive pricing improves lead conversions.</div>
      </div>
    </div>
  </div>
</div>

<!-- ================= END DASHBOARD CONTENT ================= -->

<?php require_once __DIR__ . '/../Model/dashboard-end.php'; ?>
