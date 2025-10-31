function setDeleteId(id) {
    document.getElementById('deleteId').value = id;
}


// عناصر الواجهة
const cardViewBtn = document.getElementById('cardViewBtn');
const tableViewBtn = document.getElementById('tableViewBtn');
const cardView = document.getElementById('cardView');
const tableView = document.getElementById('tableView');

function showCardView() {
    cardView.classList.remove('d-none');
    tableView.classList.add('d-none');

    // تمييز الأزرار
    cardViewBtn.classList.remove('btn-secondary'); cardViewBtn.classList.add('btn-primary');
    tableViewBtn.classList.remove('btn-primary'); tableViewBtn.classList.add('btn-secondary');
}

function showTableView() {
    cardView.classList.add('d-none');
    tableView.classList.remove('d-none');

    tableViewBtn.classList.remove('btn-secondary'); tableViewBtn.classList.add('btn-primary');
    cardViewBtn.classList.remove('btn-primary'); cardViewBtn.classList.add('btn-secondary');
}

// أحداث الأزرار
cardViewBtn.addEventListener('click', showCardView);
tableViewBtn.addEventListener('click', showTableView);



// البحث في الكروت والجدول
document.getElementById('searchInput').addEventListener('input', function () {
    let filter = this.value.toLowerCase();
    document.querySelectorAll('.product-item').forEach(item => {
        let name = item.getAttribute('data-name');
        if (name.includes(filter)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
});


// الحالة الابتدائية
showTableView();



