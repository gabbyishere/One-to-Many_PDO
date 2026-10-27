
<?php

// ============================================
// KOPE SHOP - DATABASE MODELS
// Database operations using PDO
// ============================================


// ============================================
// PARENT ENTITY: CATEGORIES
// ============================================

// Get all categories
function getAllCategories($pdo)
{
    $sql = "SELECT * FROM categories ORDER BY category_id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
}


// Get one category by ID
function getCategoryById($pdo, $category_id)
{
    $sql = "SELECT * FROM categories
            WHERE category_id = :category_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':category_id' => $category_id
    ]);

    return $stmt->fetch();
}


// Insert a category
function addCategory($pdo, $category_name, $description)
{
    $sql = "INSERT INTO categories
            (category_name, description)
            VALUES (:category_name, :description)";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':category_name' => $category_name,
        ':description' => $description
    ]);
}


// Update a category
function updateCategory($pdo, $category_id, $category_name, $description)
{
    $sql = "UPDATE categories
            SET category_name = :category_name,
                description = :description
            WHERE category_id = :category_id";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':category_name' => $category_name,
        ':description' => $description,
        ':category_id' => $category_id
    ]);
}


// Delete a category
function deleteCategory($pdo, $category_id)
{
    $sql = "DELETE FROM categories
            WHERE category_id = :category_id";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':category_id' => $category_id
    ]);
}


// ============================================
// CHILD ENTITY: MENU ITEMS
// ============================================

// Get all menu items with their category names
function getAllMenuItems($pdo)
{
    $sql = "SELECT
                menu_items.*,
                categories.category_name
            FROM menu_items
            INNER JOIN categories
                ON menu_items.category_id = categories.category_id
            ORDER BY menu_items.menu_item_id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
}


// Get one menu item by ID
function getMenuItemById($pdo, $menu_item_id)
{
    $sql = "SELECT * FROM menu_items
            WHERE menu_item_id = :menu_item_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':menu_item_id' => $menu_item_id
    ]);

    return $stmt->fetch();
}


// Insert a menu item
function addMenuItem(
    $pdo,
    $category_id,
    $item_name,
    $description,
    $price,
    $is_available
) {
    $sql = "INSERT INTO menu_items
            (category_id, item_name, description, price, is_available)
            VALUES
            (:category_id, :item_name, :description, :price, :is_available)";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':category_id' => $category_id,
        ':item_name' => $item_name,
        ':description' => $description,
        ':price' => $price,
        ':is_available' => $is_available
    ]);
}


// Update a menu item
function updateMenuItem(
    $pdo,
    $menu_item_id,
    $category_id,
    $item_name,
    $description,
    $price,
    $is_available
) {
    $sql = "UPDATE menu_items
            SET category_id = :category_id,
                item_name = :item_name,
                description = :description,
                price = :price,
                is_available = :is_available
            WHERE menu_item_id = :menu_item_id";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':category_id' => $category_id,
        ':item_name' => $item_name,
        ':description' => $description,
        ':price' => $price,
        ':is_available' => $is_available,
        ':menu_item_id' => $menu_item_id
    ]);
}


// Delete a menu item
function deleteMenuItem($pdo, $menu_item_id)
{
    $sql = "DELETE FROM menu_items
            WHERE menu_item_id = :menu_item_id";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':menu_item_id' => $menu_item_id
    ]);
}

?>
