<?php

function db_properties_latest(mysqli $conn, int $limit = 50): array {
  $limit = (int)$limit;

  $sql = "SELECT p.*, u.first_name, u.surname
          FROM properties p JOIN users u ON u.id=p.owner_user_id
          WHERE p.status IN ('published','sold')
          ORDER BY p.created_at DESC LIMIT {$limit}";

  $res = mysqli_query($conn, $sql);
  return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
}

function db_property_by_id(mysqli $conn, int $id): ?array {
  $id = (int)$id;

  $sql = "SELECT p.*, u.first_name, u.surname, u.email AS owner_email, u.phone AS owner_phone
          FROM properties p JOIN users u ON u.id=p.owner_user_id
          WHERE p.id={$id} LIMIT 1";

  $res = mysqli_query($conn, $sql);
  if (!$res) return null;
  $row = mysqli_fetch_assoc($res);
  return $row ?: null;
}

function db_property_media(mysqli $conn, int $property_id): array {
  $property_id = (int)$property_id;

  $sql = "SELECT * FROM property_media
          WHERE property_id={$property_id}
          ORDER BY is_primary DESC, sort_order ASC, id ASC";

  $res = mysqli_query($conn, $sql);
  return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
}

function db_user_properties(mysqli $conn, int $user_id): array {
  $user_id = (int)$user_id;

  $sql = "SELECT * FROM properties
          WHERE owner_user_id={$user_id}
          ORDER BY id DESC";

  $res = mysqli_query($conn, $sql);
  return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
}

function db_create_property(mysqli $conn, int $owner_id, array $data): int {
  $owner_id = (int)$owner_id;

  $title = mysqli_real_escape_string($conn, (string)$data['title']);
  $description = mysqli_real_escape_string($conn, (string)$data['description']);
  $price_bdt = (int)$data['price_bdt'];
  $land_type = mysqli_real_escape_string($conn, (string)$data['land_type']);

  $area_value = ($data['area_value'] === '' ? "NULL" : (string)((float)$data['area_value']));
  $area_unit  = mysqli_real_escape_string($conn, (string)$data['area_unit']);

  $beds  = ($data['beds'] === '' ? "NULL" : (string)((int)$data['beds']));
  $baths = ($data['baths'] === '' ? "NULL" : (string)((int)$data['baths']));

  $address_text = mysqli_real_escape_string($conn, (string)$data['address_text']);
  $city         = mysqli_real_escape_string($conn, (string)$data['city']);
  $state        = mysqli_real_escape_string($conn, (string)$data['state']);
  $country      = mysqli_real_escape_string($conn, (string)$data['country']);
  $postal_code  = mysqli_real_escape_string($conn, (string)$data['postal_code']);

  $lat = ($data['latitude'] === '' ? "NULL" : (string)((float)$data['latitude']));
  $lng = ($data['longitude'] === '' ? "NULL" : (string)((float)$data['longitude']));

  $status = mysqli_real_escape_string($conn, (string)$data['status']);
  $is_featured = (int)$data['is_featured'];

  $sql = "INSERT INTO properties
          (owner_user_id,title,description,price_bdt,land_type,area_value,area_unit,beds,baths,address_text,city,state,country,postal_code,latitude,longitude,status,is_featured)
          VALUES
          ({$owner_id},
           '{$title}',
           '{$description}',
           {$price_bdt},
           '{$land_type}',
           {$area_value},
           '{$area_unit}',
           {$beds},
           {$baths},
           '{$address_text}',
           '{$city}',
           '{$state}',
           '{$country}',
           '{$postal_code}',
           {$lat},
           {$lng},
           '{$status}',
           {$is_featured})";

  mysqli_query($conn, $sql);
  return (int)mysqli_insert_id($conn);
}

function db_update_property(mysqli $conn, int $id, int $owner_id, array $data, bool $admin_override=false): void {
  $id = (int)$id;
  $owner_id = (int)$owner_id;

  if (!$admin_override) {
    $chk = mysqli_query($conn, "SELECT id FROM properties WHERE id={$id} AND owner_user_id={$owner_id} LIMIT 1");
    $ok = $chk ? (bool)mysqli_fetch_assoc($chk) : false;
    if (!$ok) { http_response_code(403); die('Not allowed.'); }
  }

  $title = mysqli_real_escape_string($conn, (string)$data['title']);
  $description = mysqli_real_escape_string($conn, (string)$data['description']);
  $price_bdt = (int)$data['price_bdt'];
  $land_type = mysqli_real_escape_string($conn, (string)$data['land_type']);

  $area_value = ($data['area_value'] === '' ? "NULL" : (string)((float)$data['area_value']));
  $area_unit  = mysqli_real_escape_string($conn, (string)$data['area_unit']);

  $beds  = ($data['beds'] === '' ? "NULL" : (string)((int)$data['beds']));
  $baths = ($data['baths'] === '' ? "NULL" : (string)((int)$data['baths']));

  $address_text = mysqli_real_escape_string($conn, (string)$data['address_text']);
  $city         = mysqli_real_escape_string($conn, (string)$data['city']);
  $state        = mysqli_real_escape_string($conn, (string)$data['state']);
  $country      = mysqli_real_escape_string($conn, (string)$data['country']);
  $postal_code  = mysqli_real_escape_string($conn, (string)$data['postal_code']);

  $lat = ($data['latitude'] === '' ? "NULL" : (string)((float)$data['latitude']));
  $lng = ($data['longitude'] === '' ? "NULL" : (string)((float)$data['longitude']));

  $status = mysqli_real_escape_string($conn, (string)$data['status']);
  $is_featured = (int)$data['is_featured'];

  $sql = "UPDATE properties SET
            title='{$title}',
            description='{$description}',
            price_bdt={$price_bdt},
            land_type='{$land_type}',
            area_value={$area_value},
            area_unit='{$area_unit}',
            beds={$beds},
            baths={$baths},
            address_text='{$address_text}',
            city='{$city}',
            state='{$state}',
            country='{$country}',
            postal_code='{$postal_code}',
            latitude={$lat},
            longitude={$lng},
            status='{$status}',
            is_featured={$is_featured}
          WHERE id={$id}";

  mysqli_query($conn, $sql);
}

function db_delete_property(mysqli $conn, int $id, int $owner_id, bool $admin_override=false): void {
  $id = (int)$id;
  $owner_id = (int)$owner_id;

  if (!$admin_override) {
    mysqli_query($conn, "DELETE FROM properties WHERE id={$id} AND owner_user_id={$owner_id}");
    return;
  }

  mysqli_query($conn, "DELETE FROM properties WHERE id={$id}");
}
function db_properties_map(mysqli $conn): array {
  $sql = "SELECT id, title, price_bdt, city, latitude, longitude
          FROM properties
          WHERE status='published'
            AND latitude IS NOT NULL
            AND longitude IS NOT NULL
          ORDER BY id DESC";

  $res = mysqli_query($conn, $sql);
  return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
}
