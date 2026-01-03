<?php
require_once __DIR__ . '/../Model/init.php';
require_role([2,3,4]); // Employee/Manager/Admin

$u = current_user();
$role_id = (int)($u['role_id'] ?? 0);

$is_admin   = ($role_id === 4);
$is_manager = ($role_id === 3);
$is_employee= ($role_id === 2);

$can_manage = ($is_admin || $is_manager);


$users = [];
if ($can_manage) {
  $all = db_admin_users($conn); 

  if ($is_admin) {
    $users = $all;
  } else {
    // manager: allow only role_id 1 and 2
    foreach ($all as $row) {
      $rid = (int)($row['role_id'] ?? 0);
      if ($rid === 1 || $rid === 2) $users[] = $row;
    }
  }
}

// ---------- HANDLE POST: ADD TASK (Admin/Manager only) ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_task') {
  if (!$can_manage) {
    flash_set('error', 'You do not have permission to add tasks.');
    redirect(BASE_URL . 'View/employee-tasks.php');
  }

  csrf_check();

  $title = post_str('title', 180);
  $desc  = trim((string)($_POST['description'] ?? ''));
  $assigned_to = post_int('assigned_to_user_id') ?: null;
  $deadline = post_str('deadline', 20);
  $deadline = $deadline !== '' ? $deadline : null;

  // Manager safety: ensure assigned_to is allowed (role 1 or 2 only)
  if ($is_manager && $assigned_to) {
    $ok = false;
    foreach ($users as $uu) {
      if ((int)$uu['id'] === (int)$assigned_to) { $ok = true; break; }
    }
    if (!$ok) {
      flash_set('error', 'Manager can assign tasks only to Employee/User (not Admin).');
      redirect(BASE_URL . 'View/employee-tasks.php');
    }
  }

  if ($title === '') {
    flash_set('error', 'Task title is required.');
  } else {
    db_create_task($conn, $title, $desc, $assigned_to, (int)$u['id'], $deadline);
    flash_set('success', 'Task added.');
  }

  redirect(BASE_URL . 'View/employee-tasks.php');
}

// ---------- HANDLE POST: ADD SCHEDULE (Admin/Manager only) ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_schedule') {
  if (!$can_manage) {
    flash_set('error', 'You do not have permission to add schedules.');
    redirect(BASE_URL . 'View/employee-tasks.php');
  }

  csrf_check();

  $title = post_str('sched_title', 180);
  $user_id = post_int('sched_user_id');
  $starts = post_str('starts_at', 25);
  $ends   = post_str('ends_at', 25);
  $color  = post_str('color_tag', 10);

  // Manager safety: ensure schedule user is allowed (role 1 or 2 only)
  if ($is_manager) {
    $ok = false;
    foreach ($users as $uu) {
      if ((int)$uu['id'] === (int)$user_id) { $ok = true; break; }
    }
    if (!$ok) {
      flash_set('error', 'Manager can schedule only Employee/User (not Admin).');
      redirect(BASE_URL . 'View/employee-tasks.php');
    }
  }

  if ($title === '' || $user_id<=0 || $starts==='' || $ends==='') {
    flash_set('error', 'Schedule fields required.');
  } else {
    db_create_schedule($conn, $user_id, $title, $starts, $ends, $color ?: 'blue');
    flash_set('success', 'Schedule added.');
  }

  redirect(BASE_URL . 'View/employee-tasks.php');
}

// ---------- LOAD DATA (ROLE-BASED) ----------
if ($is_employee) {
  // Employee sees only assigned tasks + own schedules
  $tasks = db_tasks_for_user($conn, (int)$u['id']);      // <-- add this function in db_queries.php if not exists
  $schedules = db_schedules_for_user($conn, (int)$u['id']); // <-- add this function in db_queries.php if not exists
} else {
  // Admin/Manager see all
  $tasks = db_tasks($conn);
  $schedules = db_schedules($conn);
}

// ---------- PAGE META ----------
$pageTitle = $is_admin ? "Admin Tasks" : ($is_manager ? "Manager Tasks" : "My Tasks");
$activeDash = "activities";

// Layout: admin & manager use manager dashboard layout in your project
if ($is_admin) {
  include __DIR__ . '/../Model/dashboard-start-admin.php';
} elseif ($is_manager) {
  include __DIR__ . '/../Model/dashboard-start-manager.php';
} else {
  include __DIR__ . '/../Model/dashboard-start-employee.php';
}
?>

<?php if ($can_manage): ?>
  <div class="card" style="padding:16px;">
    <h3>Add Task</h3>
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="action" value="add_task">

      <label>Title*</label>
      <input class="input" name="title" required>

      <label>Description</label>
      <textarea class="input" name="description" rows="3"></textarea>

      <label>Assign To</label>
      <select class="input" name="assigned_to_user_id">
        <option value="">-- optional --</option>
        <?php foreach ($users as $uu): ?>
          <option value="<?= (int)$uu['id'] ?>">
            <?= e($uu['email']) ?> (<?= e($uu['role_name'] ?? ('role '.(int)$uu['role_id'])) ?>)
          </option>
        <?php endforeach; ?>
      </select>

      <label>Deadline</label>
      <input class="input" type="date" name="deadline">

      <div style="margin-top:10px;">
        <button class="btn btn-primary" type="submit">Add Task</button>
      </div>
    </form>
  </div>
<?php endif; ?>

<div class="card" style="padding:16px; margin-top:14px;">
  <h3><?= $is_employee ? "My Tasks" : "Tasks" ?></h3>

  <table style="width:100%; border-collapse:collapse;">
    <thead>
      <tr>
        <th align="left">ID</th>
        <th align="left">Title</th>
        <th align="left">Assigned</th>
        <th align="left">Deadline</th>
        <th align="left">Status</th>
        <th align="left">Progress</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($tasks as $t): ?>
        <tr style="border-top:1px solid rgba(255,255,255,0.08);">
          <td><?= (int)$t['id'] ?></td>
          <td><?= e($t['title']) ?></td>
          <td><?= e($t['assigned_email'] ?? '-') ?></td>
          <td><?= e($t['deadline'] ?? '-') ?></td>
          <td><?= e($t['status']) ?></td>
          <td><?= (int)$t['progress'] ?>%</td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$tasks): ?><tr><td colspan="6">No tasks.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?php if ($can_manage): ?>
  <div class="card" style="padding:16px; margin-top:14px;">
    <h3>Add Schedule</h3>
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="action" value="add_schedule">

      <label>User</label>
      <select class="input" name="sched_user_id" required>
        <?php foreach ($users as $uu): ?>
          <option value="<?= (int)$uu['id'] ?>"><?= e($uu['email']) ?></option>
        <?php endforeach; ?>
      </select>

      <label>Title</label>
      <input class="input" name="sched_title" required>

      <label>Starts At (YYYY-MM-DD HH:MM:SS)</label>
      <input class="input" name="starts_at" placeholder="2025-12-30 10:00:00" required>

      <label>Ends At (YYYY-MM-DD HH:MM:SS)</label>
      <input class="input" name="ends_at" placeholder="2025-12-30 12:00:00" required>

      <label>Color Tag</label>
      <select class="input" name="color_tag">
        <option value="blue">blue</option>
        <option value="green">green</option>
        <option value="orange">orange</option>
        <option value="gray">gray</option>
        <option value="pink">pink</option>
        <option value="dark">dark</option>
      </select>

      <div style="margin-top:10px;">
        <button class="btn btn-primary" type="submit">Add Schedule</button>
      </div>
    </form>
  </div>
<?php endif; ?>

<div class="card" style="padding:16px; margin-top:14px;">
  <h3><?= $is_employee ? "My Schedule" : "Schedules" ?></h3>

  <table style="width:100%; border-collapse:collapse;">
    <thead>
      <tr>
        <th align="left">ID</th>
        <th align="left">User</th>
        <th align="left">Title</th>
        <th align="left">Start</th>
        <th align="left">End</th>
        <th align="left">Color</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($schedules as $s): ?>
        <tr style="border-top:1px solid rgba(255,255,255,0.08);">
          <td><?= (int)$s['id'] ?></td>
          <td><?= e($s['email'] ?? '-') ?></td>
          <td><?= e($s['title']) ?></td>
          <td><?= e($s['starts_at']) ?></td>
          <td><?= e($s['ends_at']) ?></td>
          <td><?= e($s['color_tag']) ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$schedules): ?><tr><td colspan="6">No schedules.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/../Model/dashboard-end.php'; ?>
