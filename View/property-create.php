<?php
require_once __DIR__ . '/../Model/init.php';
require_login(BASE_URL . 'View/login.php');

$u = current_user();
$user_id = (int)$u['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    
    $data = [
        'title'       => trim($_POST['title'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'price_bdt'   => (int)($_POST['price_bdt'] ?? 0),
        'land_type'   => trim($_POST['land_type'] ?? 'residential'),
        'area_value'  => $_POST['area_value'] ?? '',
        'area_unit'   => trim($_POST['area_unit'] ?? 'sqft'),
        'address_text'=> trim($_POST['address_text'] ?? ''),
        'city'        => trim($_POST['city'] ?? ''),
        'state'       => trim($_POST['state'] ?? ''),
        'country'     => trim($_POST['country'] ?? 'Bangladesh'),
        'latitude'    => $_POST['latitude'] ?? null,
        'longitude'   => $_POST['longitude'] ?? null,
    ];
    
    if (empty($data['title'])) {
        flash_set('error', 'Title is required.');
    } else {
        $new_id = db_create_property($conn, $user_id, $data);
        if ($new_id > 0) {
            flash_set('success', 'Property created successfully!');
            redirect(BASE_URL . 'View/my-properties.php');
            exit;
        } else {
            flash_set('error', 'Failed to create property.');
        }
    }
}

require_once __DIR__ . '/layout/header.php';
?>
<div class="container">
    <h2>Create New Property</h2>
    
    <div class="card" style="padding:20px; margin-top:12px;">
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            
            <div style="margin-bottom:16px;">
                <label style="display:block; margin-bottom:6px; font-weight:600;">Title *</label>
                <input type="text" name="title" class="form-input" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;" required>
            </div>
            
            <div style="margin-bottom:16px;">
                <label style="display:block; margin-bottom:6px; font-weight:600;">Description</label>
                <textarea name="description" rows="4" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;"></textarea>
            </div>
            
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:600;">Price (BDT)</label>
                    <input type="number" name="price_bdt" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
                </div>
                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:600;">Land Type</label>
                    <select name="land_type" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
                        <option value="residential">Residential</option>
                        <option value="commercial">Commercial</option>
                        <option value="agricultural">Agricultural</option>
                        <option value="industrial">Industrial</option>
                    </select>
                </div>
            </div>
            
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:600;">Area</label>
                    <input type="number" step="0.01" name="area_value" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
                </div>
                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:600;">Unit</label>
                    <select name="area_unit" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
                        <option value="sqft">Sq Ft</option>
                        <option value="katha">Katha</option>
                        <option value="bigha">Bigha</option>
                        <option value="acre">Acre</option>
                    </select>
                </div>
            </div>
            
            <div style="margin-bottom:16px;">
                <label style="display:block; margin-bottom:6px; font-weight:600;">Address</label>
                <input type="text" name="address_text" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
            </div>
            
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; margin-bottom:16px;">
                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:600;">City</label>
                    <input type="text" name="city" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
                </div>
                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:600;">State/Division</label>
                    <input type="text" name="state" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
                </div>
                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:600;">Country</label>
                    <input type="text" name="country" value="Bangladesh" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
                </div>
            </div>
            
            <div style="margin-top:20px;">
                <button type="submit" class="btn btn-primary">Create Property</button>
                <a href="<?= BASE_URL ?>View/my-properties.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>