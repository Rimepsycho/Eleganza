<?php

require_once("./db_connect.php");


$perPage = 5; //一次顯示的用戶數量
$orderString = "";  // 初始化排序字符串
$startIndex = "0"; //初始化索引
$userCount = 0; //初始化用戶數量

// 初始排序參數
if (isset($_GET["order"])) {
    $order = (int)$_GET["order"];
} else {
    $order = 1;
}


$sqlAll = "SELECT * FROM users WHERE valid=1";
$resultAll = $conn->query($sqlAll);
$userTotalCount = $resultAll->num_rows;

//計算頁數


//初始化參數
$searchString = '';
$searchPerformed = false;
$emptySearch = false;

// 搜尋功能
if (isset($_GET["search"])) {
    $search = trim($conn->real_escape_string($_GET["search"]));

    if ($search != '') {
        $searchString = " AND (name LIKE '%$search%' OR phone LIKE '%$search%' OR email LIKE '%$search%' OR account LIKE '%$search%')";
        $searchPerformed = true;

        // 新增：搜索后重新计算符合条件的记录总数
        $sqlSearchCount = "SELECT COUNT(*) FROM users WHERE valid=1 $searchString";
        $resultSearchCount = $conn->query($sqlSearchCount);
        if ($resultSearchCount !== false) {
            $userTotalCount = $resultSearchCount->fetch_row()[0];
        }
    } else {
        $emptySearch = true;
    }
}else {
    // 如果没有进行搜索，则获取所有有效用户的总数
    $sqlAll = "SELECT COUNT(*) FROM users WHERE valid=1";
    $resultAll = $conn->query($sqlAll);
    if ($resultAll !== false) {
        $userTotalCount = $resultAll->fetch_row()[0];
    }
}



$pageCount = ceil($userTotalCount / $perPage);


// 檢查排序參數並構建排序字符串
if (isset($_GET["order"])) {
    $order = $_GET["order"];
    switch ($order) {
        case 1:
            $orderString = "ORDER BY user_id ASC";
            break;
        case 2:
            $orderString = "ORDER BY user_id DESC";
            break;
        case 3:
            $orderString = "ORDER BY name ASC";
            break;
        case 4:
            $orderString = "ORDER BY name DESC";
            break;
        default:
            $orderString = "ORDER BY user_id ASC"; // 默認排序
    }
}

// 計算 startIndex
if (isset($_GET["p"])) {
    $p = max((int)$_GET["p"], 1);  // 確保頁數不小於1 //用int進行轉換確保顯示是整數(頁數)
    $startIndex = ($p - 1) * $perPage;
} else {
    $p = 1;
    $order = 1;
    $orderString = "ORDER BY user_id ASC";
}


//以下ID是你軟刪除後還會按照ID進行排序，顯示給用戶正確
$sql = "SELECT ROW_NUMBER() OVER (ORDER BY user_id) AS DisplayID, user_id, name, account, phone, email, birth 
        FROM users 
        WHERE valid=1 $searchString 
        $orderString 
        LIMIT $startIndex, $perPage";

$result = $conn->query($sql);




// 檢查 $result 是否為 false
if ($result !== false) {
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $userCount = count($rows);
} else {
    echo "處理查詢失败：" . $conn->error;
    $userCount = 0; //如果查詢失敗，設置用戶為0
}

//顯示所有的使用者
if (isset($_GET["search"])) {
    $userCount = $result->num_rows;
} else {
    $userCount = $userTotalCount;
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Eleganza studio (阿爾扎工作室)</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.php">Eleganza studio(阿爾扎工作室)</a>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="#!">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading text-white">Interface</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            會員清單
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="./add-user.php"><i class="fa-solid fa-user-plus"></i>增加會員</a>
                                <!-- <a class="nav-link" href="ex.php">第二個清單測試</a> -->
                                <a class="nav-link" href="./index.php">返回</a>
                            </nav>
                        </div>
                    </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container px-4">
                    <h1 class="mt-4">會員</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="./index.php">首頁</a></li>
                        <li class="breadcrumb-item active"><a href="./user-list.php">會員清單</a></li>
                    </ol>
                    <div class="card mb-4">
                        <!-- Model -->
                        <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">刪除使用者</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        確認刪除?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                        <button role="button" class="btn btn-danger" onclick="deleteuser()">確認</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--  -->
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            會員清單
                            <div>
                                共<?= $userTotalCount ?>人
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="py-2 ms-2">
                                <a href="/Eleganza/add-user.php" name="" class="btn btn-primary" role="button"><i class="fa-solid fa-user-plus fa-fw"></i></a>
                            </div>

                            <?php if (isset($_GET["search"])) : ?>
                                <div class="py-2 me-2">
                                    <a name="" id="" class="btn btn-primary" href="./user-list.php" role="button"><i class="fa-solid fa-arrow-left fa-fw"></i></a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if (!isset($_GET["search"])) : ?>
                            <div class="px-2 justify-content-end d-flex align-items-center">
                                <form action="user-list.php" method="get" class="">
                                    <input type="text" name="search" placeholder="Search">
                                    <button type="submit" class="btn btn-secondary me-3">搜索</button>
                                </form>
                                <div class="me-2">排序</div>
                                <div class="btn-group">
                                    <a class="btn btn-primary" <?php if ($order == 1) echo "active" ?> href="user-list.php?order=1&p=<?= $p ?>">id<i class="fa-solid fa-arrow-down-1-9"></i></a>
                                    <a class="btn btn-primary" <?php if ($order == 2) echo "active" ?> href="user-list.php?order=2&p=<?= $p ?>">id<i class="fa-solid fa-arrow-down-9-1"></i></a>
                                    <a class="btn btn-primary" <?php if ($order == 3) echo "active" ?> href="user-list.php?order=3&p=<?= $p ?>">name<i class="fa-solid fa-arrow-down-a-z"></i></a>
                                    <a class="btn btn-primary" <?php if ($order == 4) echo "active" ?> href="user-list.php?order=4&p=<?= $p ?>">name <i class="fa-solid fa-arrow-down-z-a"></i></a>
                                </div>
                            <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <?php if ($userCount > 0) : ?>
                                    <?php if ($emptySearch) : ?>
                                        <p>請查明後再輸入。</p>
                                    <?php endif; ?>
                                    <table id="" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>id</th>
                                                <th>姓名</th>
                                                <th>帳號</th>
                                                <th>電話</th>
                                                <th>電子郵件</th>
                                                <th>生日</th>
                                                <th>詳細資訊</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- 等資料庫 -->
                                            <!-- 參考上課users.php -->
                                            <?php
                                            foreach ($rows as $user) :
                                            ?>
                                                <tr>
                                                    <td id="id"><?= $user["DisplayID"] ?></td>
                                                    <td id="name"><?= $user["name"] ?></td>
                                                    <td id="account"><?= $user["account"] ?></td>
                                                    <td id="phone"><?= $user["phone"] ?></td>
                                                    <td id="email"><?= $user["email"] ?></td>
                                                    <td id="birth"><?= $user["birth"] ?></td>
                                                    <td><a class="btn btn-primary" href="user.php?id=<?= $user["user_id"] ?>" role="button"><i class="fa-solid fa-circle-info fa-fw"></i></a>
                                                        <button class="btn btn-danger" data-id="<?= $user["user_id"] ?>" data-bs-toggle="modal" data-bs-target="#confirmModal"><i class="fa-solid fa-trash fa-fw"></i></button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <?php if ($pageCount > 1) : ?>
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination">
                                                <?php for ($i = 1; $i <= $pageCount; $i++) : ?>
                                                    <li class="page-item <?php if ($i == $p) echo "active"; ?>">
                                                        <a class="page-link" href="user-list.php?order=<?= $order ?>&p=<?= $i ?>&<?= isset($_GET['search']) ? 'search=' . $_GET['search'] : '' ?>"><?= $i ?></a>
                                                    </li>
                                                <?php endfor; ?>
                                            </ul>
                                        </nav>
                                    <?php endif; ?>
                                <?php else : ?>
                                    沒有使用者
                                <?php endif; ?>
                            </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php include("../js.php") ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script>
        var userId = 0;
        $(document).ready(function() {
            // 使用事件委托来处理动态生成的按钮
            $(document).on('click', '.btn-danger', function() {
                userId = $(this).data('id');
                // console.log("用户 ID:", userId);
            });
        });

        function deleteuser() {
            window.location.replace("doDeleteUser.php?id=" + userId);
        }
    </script>
</body>

</html>

<!-- <a class="btn btn-danger" href="doDeleteUser.php?id=?= $user['user_id'] ?>" onclick="return confirm('確定要刪除此使用者嗎？');">
                                                            <i class="fa fa-trash"></i>
                                                        </a> -->