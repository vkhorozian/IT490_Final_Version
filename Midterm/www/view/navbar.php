<style>
    .logout{

    }
</style>

<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">The Cryto Kings</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a href="coin_view.php">Home</a></li>
            <li><a href="user_wallet.php">Wallet</a></li>
            <li><a href="analytics.php">Analytics</a></li>
            <li><a href="printView.php">CoinTablePrintR</a></li>
            <li><a href="#">User ID = <?php echo $_SESSION['userID']?></a></li>
            <li class="logout"><a href="logout.php">Logout</a></li>
        </ul>

        <form method="post" class="navbar-form navbar-right" action="search.php">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Search" name="search" id="search">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
    </div>
</nav>