<?php
require_once __DIR__ . '/../Model/init.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

// ===== GET: Search (returns HTML) =====
if ($action == 'search') {
    $query = trim($_GET['q'] ?? '');
    
    if (strlen($query) < 2) {
        exit;
    }
    
    $escaped = mysqli_real_escape_string($conn, $query);
    $result = mysqli_query($conn, "SELECT id, title, city, price_bdt FROM properties WHERE title LIKE '%{$escaped}%' OR city LIKE '%{$escaped}%' LIMIT 5");
    
    $html = '';
    while ($row = mysqli_fetch_assoc($result)) {
        $url = BASE_URL . 'View/property-details.php?id=' . (int)$row['id'];
        $html .= '<a href="' . $url . '" class="result-item">';
        $html .= '<strong>' . htmlspecialchars($row['title']) . '</strong>';
        $html .= '<span>' . htmlspecialchars($row['city'] ?? 'Unknown') . ' — ' . number_format($row['price_bdt']) . ' BDT</span>';
        $html .= '</a>';
    }
    
    echo $html ?: '<div class="no-results">No properties found</div>';
    exit;
}

//json
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'])) {
    header('Content-Type: application/json');
    
    $data = json_decode($_POST['data'], true);
    $action = $data['action'] ?? '';
    
    // Toggle Favorite
    if ($action == 'toggle_favorite') {
        if (!is_logged_in()) {
            echo json_encode(['success' => false, 'message' => 'Please login first']);
            exit;
        }
        
        $user_id = (int)current_user()['id'];
        $property_id = (int)($data['property_id'] ?? 0);
        
        if ($property_id > 0) {
            $is_favorite = db_toggle_favorite($conn, $user_id, $property_id);
            echo json_encode([
                'success' => true,
                'is_favorite' => $is_favorite,
                'message' => $is_favorite ? 'Added to favorites' : 'Removed from favorites'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid property']);
        }
        exit;
    }
    
    
    if ($action == 'add_inquiry') {
        $property_id = (int)($data['property_id'] ?? 0);
        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        $message = $data['message'] ?? '';
        
        echo json_encode([
            'success' => true,
            'message' => 'Inquiry sent successfully!',
            'data' => [
                'name' => $name,
                'email' => $email
            ]
        ]);
        exit;
    }
    
    echo json_encode(['success' => false, 'message' => 'Unknown action']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);