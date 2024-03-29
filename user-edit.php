<?php
require_once("./db_connect.php");

if (!isset($_GET["id"])) {
    $id = 0; //自己設定預設值
} else {
    $id = $_GET["id"];
}
echo "User ID: " . $_GET['id'];



$sql = "SELECT * FROM users WHERE user_id=$id AND valid=1";
//id超過你擁有的數量會顯示不存在
// echo $sql;

$result = $conn->query($sql);

$rowCount = $result->num_rows;

if ($rowCount != 0) {
    $row = $result->fetch_assoc();
}

// var_dump($row);
?>

<!doctype html>
<html lang="en">

< <head>
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
    <?php
    include("../css.php")
    ?>
    <style>
        
    </style>
    </head>

    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.php">Eleganza studio(阿爾扎工作室)</a>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</a>
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
                            <div class="sb-sidenav-menu-heading text-white">Core</div>
                            <a class="nav-link" href="user.php?id=<?= $row["user_id"] ?>">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-user"></i></div>
                                會員資料
                            </a>
                            <!-- <div class="sb-sidenav-menu-heading">Interface</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Layouts
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="layout-static.html">Static Navigation</a>
                                    <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Pages
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a> -->
                            <!-- <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Authentication
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="login.html">Login</a>
                                            <a class="nav-link" href="register.html">Register</a>
                                            <a class="nav-link" href="password.html">Forgot Password</a>
                                        </nav>
                                    </div>
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                        Error
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="401.html">401 Page</a>
                                            <a class="nav-link" href="404.html">404 Page</a>
                                            <a class="nav-link" href="500.html">500 Page</a>
                                        </nav>
                                    </div>
                                </nav>
                            </div> -->
                            <!-- <div class="sb-sidenav-menu-heading">Addons</div>
                            <a class="nav-link" href="charts.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Charts
                            </a>
                            <a class="nav-link" href="tables.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Tables
                            </a> -->
                        </div>
                    </div>
                    <!-- <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Start Bootstrap
                    </div> -->
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container px-4">
                        <h1 class="mt-4">會員</h1>
                        <ol class="breadcrumb mb-4">
                            <!-- <li class="breadcrumb-item"><a href="#">會員</a></li> -->
                            <li class="breadcrumb-item active"><a href="./user-list.php">會員清單</a></li>
                            <li class="breadcrumb-item"><a href="user.php?id=<?= $row["user_id"] ?>">會員資料</a></li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                會員資訊填寫
                            </div>
                            <div class="container">
                                <div class="py-2">
                                    <a class="btn btn-primary" href="user.php?id=<?= $row["user_id"] ?>" role="button"><i class="fa-solid fa-chevron-left"></i>返回</a>
                                </div>
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
                                                <a role="button" class="btn btn-danger" href="doDeleteUser.php?id=<?= $row["user_id"] ?>">確認</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="container">
                                    <?php if ($rowCount == 0) : ?>
                                        使用者不存在
                                    <?php else :
                                    ?>
                                        <form action="updateUser.php" method="post">
                                            <table class="table table-bordered">
                                                <input type="hidden" name="user_id" value="<?= $row["user_id"] ?>">
                                                <!-- <tr>
                                                    <th>ID</th>
                                                    <td>?= $displayId ?></td>
                                                </tr> -->
                                                <tr>
                                                    <th>姓名</th>
                                                    <td><input type="text" class="form-control" value="<?= $row["name"] ?>" name="name"></td>
                                                </tr>
                                                <tr>
                                                    <th>帳號</th>
                                                    <td><input type="text" class="form-control" value="<?= $row["account"] ?>" name="account"></td>
                                                </tr>
                                                <tr>
                                                    <th>電話</th>
                                                    <td><input type="tel" class="form-control" value="<?= $row["phone"] ?>" name="phone"></td>
                                                </tr>
                                                <tr>
                                                    <th>電子郵件</th>
                                                    <td><input type="email" class="form-control" value="<?= $row["email"] ?>" name="email"></td>
                                                </tr>
                                                <tr>
                                                    <th>生日(西元)</th>
                                                    <td><input type="date" class="form-control" value="<?= $row["birth"] ?>" name="birth"></td>
                                                </tr>
                                            </table>
                                            <div class="">
                                                <button type="submit" class="btn btn-primary">
                                                    儲存
                                                </button>
                                            </div>
                                        </form>
                                    <?php endif; ?>

                                </div>
                            </div>
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
    </body>

</html>