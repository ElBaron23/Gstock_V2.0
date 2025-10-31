<?php
session_start();
include 'inc/conn.php';
// جلب بيانات المستخدم (لإظهار اسمه في navbar)
$stmt = $conn->prepare("SELECT * FROM login WHERE id = :id");
$stmt->bindParam(":id", $_SESSION['user']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// جلب كل المنتجات
$products = $conn->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl"></html>
<head>
    <meta charset="UTF-8">
     <link rel="shortcut icon" href="./img/in-stock.png" type="image/x-icon">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض كل المنتجات</title>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/5f3973b2d2.js" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS + Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Bootstrap RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">


    <style>
        body { background-color: #f8f9fa; }
        .card img { width: 100%; height: 200px; object-fit: cover; }
        .view-toggle { margin-bottom: 20px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="home.php">إدارة المخزن</a>
    <div class="d-flex">
        <span class="navbar-text text-white me-3"><?= htmlspecialchars($user['name'] ?? 'المستخدم') ?></span>
        <a class="btn btn-outline-danger" href="logout.php">تسجيل الخروج</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <h2 class="mb-4 text-center">جميع المنتجات</h2>
     <!-- البحث والتبديل -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <input type="text" id="searchInput" class="form-control w-50" placeholder="🔍 ابحث عن منتج بالاسم...">
        <input type="number" id="maxPrice" class="form-control" placeholder="💲 السعر الأقصى" style="flex:1;">
        <input type="number" id="minQuantity" class="form-control" placeholder="📉 الكمية الأقل" style="flex:1;">

        <div></div>
        <div class="view-toggle"></div>
            <button class="btn btn-secondary" id="cardViewBtn" title="عرض كروت"><i class="fa-solid fa-id-card-clip"></i></button>
            <button class="btn btn-primary" id="tableViewBtn" title="عرض جدول"><i class="fa-solid fa-table-list"></i></button>
        </div>
    </div>

    <!-- ======= كروت المنتجات (مخفية افتراضيًا) ======= -->
    <div class="row d-none" id="cardView">
        <?php foreach ($products as $prod): ?>
        <div class="col-md-4 product-card" data-price-vent="<?= htmlspecialchars($prod['price_vent']) ?>" data-quantity="<?= htmlspecialchars($prod['quantity']) ?>">
            <div class="card mb-4">
                <?php $img = !empty($prod['image']) ? htmlspecialchars($prod['image']) : 'placeholder.png'; ?>
                <img src="img/products/<?= $img ?>" class="card-img-top" alt="<?= htmlspecialchars($prod['name']) ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($prod['name']) ?></h5>
                    <p class="card-text"><strong>الكمية:</strong> <?= htmlspecialchars($prod['quantity']) ?></p>
                    <p class="card-text"><strong>سعر الشراء:</strong> <?= htmlspecialchars($prod['price']) ?> درهم</p>
                    <p class="card-text"><strong>سعر البيع:</strong> <?= htmlspecialchars($prod['price_vent']) ?> درهم</p>
                    <div class="d-flex justify-content-between">
                        <a href="edit.php?id=<?= $prod['id'] ?>" class="btn btn-warning btn-sm">تعديل</a>
                        <button class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#confirmDeleteModal"
                                onclick="setDeleteId(<?= $prod['id'] ?>)">
                            حذف
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- ======= جدول المنتجات (يظهر افتراضيًا) ======= -->
    <div class="table-responsive" id="tableView">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>الصورة</th>
                    <th>اسم المنتج</th>
                    <th>الكمية</th>
                    <th>سعر الشراء</th>
                    <th>سعر البيع</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $prod): ?>
                <tr class="product-row" data-price-vent="<?= htmlspecialchars($prod['price_vent']) ?>" data-quantity="<?= htmlspecialchars($prod['quantity']) ?>">
                    <td><img src="img/products/<?= htmlspecialchars($prod['image']) ?>" width="80" alt="<?= htmlspecialchars($prod['name']) ?>"></td>
                    <td class="product-name"><?= htmlspecialchars($prod['name']) ?></td>
                    <td><?= htmlspecialchars($prod['quantity']) ?></td>
                    <td><?= htmlspecialchars($prod['price']) ?> درهم</td>
                    <td><?= htmlspecialchars($prod['price_vent']) ?> درهم</td>
                    <td>
                        <a href="edit.php?id=<?= $prod['id'] ?>" class="btn btn-warning btn-sm">✏️ تعديل</a>
                        <button class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#confirmDeleteModal"
                                onclick="setDeleteId(<?= $prod['id'] ?>)">
                            🗑 حذف
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<!-- Modal تأكيد الحذف -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill"></i> تأكيد الحذف</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <i class="bi bi-exclamation-circle-fill text-danger" style="font-size: 3rem;"></i>
        </div>
        <h5>هل أنت متأكد أنك تريد حذف هذا المنتج؟</h5>
        <p class="text-muted">⚠️ هذا الإجراء لا يمكن التراجع عنه.</p>
      </div>
      <div class="modal-footer justify-content-center">
        <form method="POST" action="delete_product.php">
          <input type="hidden" name="delete_id" id="deleteId">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn btn-danger">نعم، احذف</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap Bundle (JS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Function to set the ID for deletion in the modal
    function setDeleteId(id) {
        document.getElementById('deleteId').value = id;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const cardViewBtn = document.getElementById('cardViewBtn');
        const tableViewBtn = document.getElementById('tableViewBtn');
        const cardView = document.getElementById('cardView');
        const tableView = document.getElementById('tableView');
        const searchInput = document.getElementById('searchInput');
        const maxPriceInput = document.getElementById('maxPrice');
        const minQuantityInput = document.getElementById('minQuantity');

        // Toggle to Card View
        cardViewBtn.addEventListener('click', () => {
            cardView.classList.remove('d-none');
            tableView.classList.add('d-none');
            cardViewBtn.classList.add('btn-primary');
            cardViewBtn.classList.remove('btn-secondary');
            tableViewBtn.classList.add('btn-secondary');
            tableViewBtn.classList.remove('btn-primary');
        });

        // Toggle to Table View
        tableViewBtn.addEventListener('click', () => {
            tableView.classList.remove('d-none');
            cardView.classList.add('d-none');
            tableViewBtn.classList.add('btn-primary');
            tableViewBtn.classList.remove('btn-secondary');
            cardViewBtn.classList.add('btn-secondary');
            cardViewBtn.classList.remove('btn-primary');
        });

        // Unified filter function
        function filterProducts() {
            const nameFilter = searchInput.value.toLowerCase().trim();
            const maxPriceFilter = parseFloat(maxPriceInput.value);
            const minQuantityFilter = parseInt(minQuantityInput.value);

            // Filter card view
            const cards = cardView.querySelectorAll('.product-card');
            cards.forEach(card => {
                const title = card.querySelector('.card-title').textContent.toLowerCase();
                const price = parseFloat(card.dataset.priceVent);
                const quantity = parseInt(card.dataset.quantity);

                const nameMatch = title.includes(nameFilter);
                const priceMatch = isNaN(maxPriceFilter) || maxPriceFilter <= 0 || price <= maxPriceFilter;
                const quantityMatch = isNaN(minQuantityFilter) || minQuantityFilter <= 0 || quantity >= minQuantityFilter;

                card.style.display = nameMatch && priceMatch && quantityMatch ? '' : 'none';
            });

            // Filter table view
            const rows = tableView.querySelectorAll('.product-row');
            rows.forEach(row => {
                const name = row.querySelector('.product-name').textContent.toLowerCase();
                const price = parseFloat(row.dataset.priceVent);
                const quantity = parseInt(row.dataset.quantity);

                const nameMatch = name.includes(nameFilter);
                const priceMatch = isNaN(maxPriceFilter) || maxPriceFilter <= 0 || price <= maxPriceFilter;
                const quantityMatch = isNaN(minQuantityFilter) || minQuantityFilter <= 0 || quantity >= minQuantityFilter;

                row.style.display = nameMatch && priceMatch && quantityMatch ? '' : 'none';
            });
        }

        // Add event listeners to all filter inputs
        searchInput.addEventListener('keyup', filterProducts);
        maxPriceInput.addEventListener('input', filterProducts);
        minQuantityInput.addEventListener('input', filterProducts);
    });
</script>

</body>
</html>
