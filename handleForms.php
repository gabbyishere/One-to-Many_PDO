
<?php

// ============================================
// KOPE SHOP - FORM HANDLER
// Processes category and menu item forms
// ============================================

require_once __DIR__ . '/dbConfig.php';
require_once __DIR__ . '/models.php';


// Only process submitted POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit;
}


// Get the requested action
$action = $_POST['action'] ?? '';


// ============================================
// CATEGORY ACTIONS
// ============================================


 
if ($action === 'add_category') {

    $category_name = trim($_POST['category_name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($category_name !== '') {
        try {
            addCategory($pdo, $category_name, $description);

            header("Location: ../index.php?status=added");
            exit;

        } catch (PDOException $e) {

            // MySQL error 1062 means duplicate entry
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
                header("Location: ../index.php?status=duplicate");
                exit;
            }

            throw $e;
        }
    }

    header("Location: ../index.php?status=empty");
    exit;
}




// Update a category
if ($action === 'update_category') {

    $category_id = filter_input(
        INPUT_POST,
        'category_id',
        FILTER_VALIDATE_INT
    );

    $category_name = trim($_POST['category_name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($category_id && $category_name !== '') {
        updateCategory(
            $pdo,
            $category_id,
            $category_name,
            $description
        );
    }

    header("Location: ../index.php");
    exit;
}



if ($action === 'delete_category') {

    $category_id = filter_input(
        INPUT_POST,
        'category_id',
        FILTER_VALIDATE_INT
    );

    if ($category_id) {
        try {
            deleteCategory($pdo, $category_id);

            header("Location: ../index.php?status=deleted");
            exit;

        } catch (PDOException $e) {
            // MySQL error 1451 means a related record prevents deletion
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1451) {
                header("Location: ../index.php?status=category_in_use");
                exit;
            }

            throw $e;
        }
    }

    header("Location: ../index.php");
    exit;
}



// ============================================
// MENU ITEM ACTIONS
// ============================================

// Add a menu item
if ($action === 'add_menu_item') {

    $category_id = filter_input(
        INPUT_POST,
        'category_id',
        FILTER_VALIDATE_INT
    );

    $item_name = trim($_POST['item_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? '';
    $is_available = (int) ($_POST['is_available'] ?? 0);

    if (
        $category_id &&
        $item_name !== '' &&
        $price !== '' &&
        is_numeric($price) &&
        (float) $price >= 0
    ) {
        addMenuItem(
            $pdo,
            $category_id,
            $item_name,
            $description,
            $price,
            $is_available
        );
    }

    header("Location: ../viewprojects.php");
    exit;
}


// Update a menu item
if ($action === 'update_menu_item') {

    $menu_item_id = filter_input(
        INPUT_POST,
        'menu_item_id',
        FILTER_VALIDATE_INT
    );

    $category_id = filter_input(
        INPUT_POST,
        'category_id',
        FILTER_VALIDATE_INT
    );

    $item_name = trim($_POST['item_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? '';
    $is_available = (int) ($_POST['is_available'] ?? 0);

    if (
        $menu_item_id &&
        $category_id &&
        $item_name !== '' &&
        $price !== '' &&
        is_numeric($price) &&
        (float) $price >= 0
    ) {
        updateMenuItem(
            $pdo,
            $menu_item_id,
            $category_id,
            $item_name,
            $description,
            $price,
            $is_available
        );
    }

    header("Location: ../viewprojects.php?status=updated");
    exit;
}


// Delete a menu item
if ($action === 'delete_menu_item') {

    $menu_item_id = filter_input(
        INPUT_POST,
        'menu_item_id',
        FILTER_VALIDATE_INT
    );

    if ($menu_item_id) {
        deleteMenuItem($pdo, $menu_item_id);
    }

    header("Location: ../viewprojects.php?status=deleted");
    exit;
}


// Return to the home page if the action is unknown
header("Location: ../index.php");
exit;

?>
