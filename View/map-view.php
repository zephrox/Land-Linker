<?php
require_once __DIR__ . '/../Model/init.php';

$props = db_properties_map($conn);

require_once __DIR__ . '/layout/header.php';
?>
<div class="container">
  <h2>Map View</h2>

  <div class="card" style="padding:14px; margin-top:12px;">
    <div id="map" style="height:520px; border-radius:12px;"></div>
    <p style="margin-top:10px; color:#666;">
      Only published properties with latitude/longitude appear on the map.
    </p>
  </div>
</div>

<!-- Leaflet (CDN) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
  const props = <?= json_encode($props, JSON_UNESCAPED_UNICODE) ?>;

  const map = L.map('map').setView([23.8103, 90.4125], 7); // Bangladesh default
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
  }).addTo(map);

  if (props.length === 0) {
    L.popup().setLatLng([23.8103, 90.4125]).setContent('No properties with coordinates yet.').openOn(map);
  } else {
    const group = [];
    props.forEach(p => {
      const lat = parseFloat(p.latitude);
      const lng = parseFloat(p.longitude);
      if (!isFinite(lat) || !isFinite(lng)) return;

      const url = "<?= BASE_URL ?>View/property-details.php?id=' ?>" + p.id;
      const html = `
        <strong>${p.title}</strong><br>
        Price: ${p.price_bdt} BDT<br>
        ${p.city ? ("City: " + p.city + "<br>") : ""}
        <a href="${url}">View Details</a>
      `;

      const m = L.marker([lat, lng]).addTo(map).bindPopup(html);
      group.push([lat, lng]);
    });

    if (group.length) map.fitBounds(group, { padding: [30, 30] });
  }
</script>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
